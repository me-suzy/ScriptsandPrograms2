<?php

class CDisplay
{
	var $output;
	var $debug;
	
	function CDisplay()
	{
		global $rwdInfo, $CONFIG;
		// Open and save the wrapper
		$filename = $rwdInfo->skinurl."/main.htm";
		$handle = fopen($filename, "r");
		$rwdInfo->skin_wrapper = fread($handle, filesize($filename));
		fclose($handle);
	}
	
	// =====================================
	// Loads new style template data
	// =====================================
	function load_template( $name )
	{
		global $CONFIG, $rwdInfo;
					
		if ( $name != 'skin_global')
		{
			if ( ! in_array( 'skin_global', $rwdInfo->loaded_templates ) )
			{
				require_once( $rwdInfo->skinurl."/skin_global.php" );
				$rwdInfo->skin_global        = new skin_global();
				$rwdInfo->loaded_templates[] = 'skin_global';
			}
			
			$rwdInfo->loaded_templates[] = $name;

			require_once( $rwdInfo->skinurl."/".$name.".php" );
			
			return new $name();
			
		}
		else
		{
			$rwdInfo->loaded_templates[] = 'skin_global';
			
			require_once( $rwdInfo->skinurl."/skin_global.php" );
			
			$rwdInfo->skin_global = new skin_global();
			return;
		}
	}

	function add_output($to_add)
    {
        $this->output .= $to_add;
    }
	
	function print_output()
	{
		global $CONFIG, $version, $rwdInfo;
		
		// Yes I know its very easy to remove this if you REALLY wanted to but you agreed not to when you downloaded this script
		// Besides I spend a lot of time making this script for you so would it really hurt you to support us and help others find
		// our script too? Leave it here you know it makes sense.
		if (!DEMO)
			$copyright = "<!-- Copyright Information -->\n\n<span class='copyright' style='font-size: 10px'>Powered by <a href='http://www.rwscripts.com/' target='_blank'>RW::Download</a> $version<br>© 2005 <a href='http://www.rwscripts.com/' target='_blank'>RW::Scripts</a></span>\n\n";
		else
			$copyright = "<!-- Copyright Information -->\n\n<span class='copyright' style='font-size: 10px'>Powered by <a href='http://www.rwscripts.com/' target='_blank'>RW::Download</a> $version DEMO VERSION<br>© 2005 <a href='http://www.rwscripts.com/' target='_blank'>RW::Scripts</a></span>\n\n";
		
		$this->showDebug();
		
		$rwdInfo->skin_wrapper = str_replace( "{links}" , $rwdInfo->links, $rwdInfo->skin_wrapper);		
		$rwdInfo->skin_wrapper = str_replace( "{nav}" , $rwdInfo->nav, $rwdInfo->skin_wrapper);
		$rwdInfo->skin_wrapper = str_replace( "{copyright}" , $copyright, $rwdInfo->skin_wrapper);		
		$rwdInfo->skin_wrapper = str_replace( "{style}" , "<LINK href='{skin_path}/style.css' type='text/css' rel='stylesheet'>", $rwdInfo->skin_wrapper);
		$rwdInfo->skin_wrapper = str_replace( "{skin_path}" , $rwdInfo->skinurl, $rwdInfo->skin_wrapper);
		$rwdInfo->skin_wrapper = str_replace( "{main_title}" , $CONFIG['isoffline']?$CONFIG['sitename']." [OFFLINE]":$CONFIG['sitename'], $rwdInfo->skin_wrapper);
				
		if ( $rwdInfo->error_log )
			$rwdInfo->skin_wrapper = str_replace( "{main_content}" , $rwdInfo->error_log, $rwdInfo->skin_wrapper);
		else
			$rwdInfo->skin_wrapper = str_replace( "{main_content}" , $this->output.$this->debug, $rwdInfo->skin_wrapper);
		
		print $rwdInfo->skin_wrapper;
	}
	
	function rwWordWrap($text)
	{
		global $CONFIG;
		$maxLen = $CONFIG['max_word_length'];   // @todo Make this configurable
		$strings = explode(" ", $text);
		foreach ( $strings as $word )
		{
			if ( strlen( $word ) > $maxLen )
			{
				$begin = substr($word, 0, $maxLen);
				$end = substr($word, $maxLen, strlen($word) - $maxLen);
				$new = $begin . " " . $this->rwWordWrap($end);
				$final .= $new." ";
			}
			else
				$final .= $word." ";
		}
		return $final;
	}
	
	function showDebug()
    {
    	global $CONFIG, $IN, $DB, $rwdInfo;
    	      
       //+----------------------------------------------
       // $IN values
       //+----------------------------------------------
       
	   if ($CONFIG['debuglevel'])
	   		$this->debug = "<div class='debugborder'>";
       if ($CONFIG['debuglevel'] >= 3)
       {
       		$output = "<hr><b>POST & GET:</b><br>";
        	
			$output .= $this->new_table();
			foreach($IN as $k=>$v )
			{
				$output .= $this->new_row()."<b>$k</b> = ";
				if ( is_array($v) )
					$output .= $this->new_col().$this->echoArray($v);
				else
					$output .= $this->new_col().$v;
			}
			
			$output .= $this->end_table();
        	$this->debug .= $output;
        }
        
        //+----------------------------------------------
        // SQL
        //+----------------------------------------------
        
        if ($CONFIG['debuglevel'] >= 2)
        {
           	$output = "<hr><b>Queries:</b><br>";
			$query_string = $DB->query_string;
        	foreach($query_string as $q)
        	{
        		$q = htmlspecialchars($q);
        		$q = preg_replace( "/^SELECT/i" , "<span class='red'>SELECT</span>"   , $q );
        		$q = preg_replace( "/^UPDATE/i" , "<span class='blue'>UPDATE</span>"  , $q );
        		$q = preg_replace( "/^DELETE/i" , "<span class='orange'>DELETE</span>", $q );
        		$q = preg_replace( "/^INSERT/i" , "<span class='green'>INSERT</span>" , $q );
        		$q = str_replace( "LEFT JOIN"   , "<span class='red'>LEFT JOIN</span>" , $q );
        		
        		//$q = preg_replace( "/(".$ibforums->vars['sql_tbl_prefix'].")(\S+?)([\s\.,]|$)/", "<span class='purple'>\\1\\2</span>\\3", $q );
        		
        		$output .= $q."<br>";
        	}
			$this->debug .= $output;
        }
		
		if ( $CONFIG['debuglevel'] >= 1 )
		{
			$load = "";
			$output = "<hr><b>Debug Information:</b><br>";
			$total_queries = $DB->query_count;
			if ( @file_exists('/proc/loadavg') )
			{
				if ( ($fh = @fopen( '/proc/loadavg', 'r' )) )
				{
					$data = @fread( $fh, 6 );
					@fclose( $fh );
			
					$load_avg = explode( " ", $data );
			
					$load = " <br><b>".trim($load_avg[0])."</b>";
				}
			}
			$output .= "SQL Queries: ".$total_queries;
			if ( $load )
				$output .= " Server Load: ".$load;
			$output .= "<br>";
			$this->debug .= $output;
		}
		if ($CONFIG['debuglevel'])
	   		$this->debug .= "</div>";
    }
	
	function echoArray($array)
	{
		$output .= "<pre>";
		ob_start();
		print_r($array);
		$output .= ob_get_contents();
		ob_end_clean();
		$output .= "</pre>";
		return $output;
	}
	
	function tableSetup($colspan = -1, $class="", $tdclass="", $width="", $colwidth="")
	{
		$return = array();
		if ( $colspan != -1 )
			$return["colspan"] = " colspan='$colspan' ";
		if ( $class )
			$return["class"] = " class='$class' ";
		if ( $tdclass )
			$return["tdclass"] = " class='$tdclass' ";
		if ( $width )
			$return["width"] = " width='$width' ";
		if ( $colwidth )
			$return["colwidth"] = " width='$colwidth' ";
		return $return;
	}
	
	function new_table($colspan = -1, $class="", $tdclass="", $width="100%", $colwidth="", $padding=2)
	{
		$data = $this->tableSetup($colspan, $class, $tdclass, $width, $colwidth);
	
		$output = "<table".$data["width"]." border='0' cellspacing='0' cellpadding='$padding'>\n";
		$output .= "<tr".$data["class"].">\n";
		$output .= "<td valign='top'".$data["colspan"].$data["colwidth"].$data["tdclass"].">\n";
		return $output;
	}
	function new_row($colspan = -1, $class="", $tdclass="", $width="")
	{
		$data = $this->tableSetup($colspan, $class, $tdclass, "", $width);
		$output = "</td>\n</tr>\n<tr".$data["class"].">\n";
		$output .= "<td valign='top'".$data["colspan"].$data["colwidth"].$data["tdclass"].">\n";
		return $output;
	}
	function new_col($colspan = -1, $tdclass="")
	{
		$data = $this->tableSetup($colspan, "", $tdclass);
		$output = "</td>\n";
		$output .= "<td valign='top'".$data["colspan"].$data["colwidth"].$data["tdclass"].">\n";
		return $output;
	}
	function end_table()
	{
		$output = "</td>\n</tr>\n</table>\n";
		return $output;
	}
}
?>

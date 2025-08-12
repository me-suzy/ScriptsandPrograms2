<?php

/*********************************************************
 * Name: global functions.php
 * Author: Dave Conley
 * Contact: realworld@blazefans.com
 * Description: Global function calls used in download script and admin interface
 * Version: 4.00
 * Last edited: 12th March, 2004
 *********************************************************/

define('DEMO', 0);
define('DEBUG', 0);
define('USER_DEFAULT', "rwd4");
$version = "v4.0.1a lite";
$version_short = "4.0";
$updateversion = "4|0|0|2";

if (function_exists("set_time_limit"))
	@set_time_limit(0);
	
if (DEBUG)
	require_once ROOT_PATH."/functions/debug.php";    

class func
{
	function func()
	{
		global $OUTPUT;
		//$OUTPUT->load_template("skin_global");
	}
	function convertdate($date)
	{
		global $CONFIG;
		$break = explode(" ", $date);
		$datebreak = explode("-", $break[0]);
		$time = explode(":", $break[1]);
		$epoch = date("U", mktime($time[0],$time[1],$time[2],$datebreak[1],$datebreak[2],$datebreak[0]));
		//$datetime = date("Y-m-d H:i:s", mktime($time[0],$time[1],$time[2],$datebreak[1],$datebreak[2],$datebreak[0]));
		$timeadjust = ($CONFIG['timeadjust'] * 60 * 60);
		return date($CONFIG["dateformat"],$epoch+$timeadjust);
	}
	
	function converttotime($date)
	{
		if (!$date) 
		{
			echo "WARNING: No date passed to converttotime";
		    return;
		}
		$break = explode(" ", $date);
		$datebreak = explode("-", $break[0]);
		$time = explode(":", $break[1]);
		$epoch = date("U", mktime($time[0],$time[1],$time[2],$datebreak[1],$datebreak[2],$datebreak[0]));
		//$datetime = date("Y-m-d H:i:s", mktime($time[0],$time[1],$time[2],$datebreak[1],$datebreak[2],$datebreak[0]));
		return $epoch;
	}
	
	function isRecent($date) 
	{ 
		$break = explode(" ", $date); 
		$datebreak = explode("-", $break[0]); 
		$time = explode(":", $break[1]); 
		if ( $datebreak[0] == 0 )
			return false;
		$epoch = date("U", mktime($time[0],$time[1],$time[2],$datebreak[1],$datebreak[2],$datebreak[0])); 
		$time = time();
		if ( $time - $epoch < 259200 )
			return true;
		else
			return false;
	}
	
	function mycopy($source, $dest)
	{
	    // Simple copy for a file
	    if (is_file($source)) {
	        return copy($source, $dest);
	    }
	
	    // Make destination directory
	    if (!is_dir($dest)) {
	        mkdir($dest);
	    }
	
	    // Loop through the folder
	    $dir = dir($source);
	    while (false !== $entry = $dir->read()) 
		{
	        // Skip pointers
	        if ($entry == '.' || $entry == '..') 
			{
	            continue;
	        }
	
	        // Deep copy directories
	        if (is_dir("$source/$entry") && ($dest !== "$source/$entry")) 
			{
	            $this->mycopy("$source/$entry", "$dest/$entry");
	        } 
			else 
			{
	            copy("$source/$entry", "$dest/$entry");
	        }
	    }
	
	    // Clean up
	    $dir->close();
	    return true;
	}
		
	function error($message)
	{
		global $OUTPUT, $rwdInfo;
		$message = mysql_error()."<br>".$message.". ".GETLANG("er_stderror")."<br><a href=\"javascript: history.back()\">".GETLANG("back")."</a>";
		$data = array("message" => "$message");
		$OUTPUT->load_template("skin_global");
		$rwdInfo->error_log .= $rwdInfo->skin_global->error($data);
		return "";
	}
	function warning($message)
	{
		global $OUTPUT,$rwdInfo;
		$data = array("message" => "$message");
		$OUTPUT->load_template("skin_global");
		$rwdInfo->error_log .= $rwdInfo->skin_global->warning($data);
		//$OUTPUT->add_output($info); 
	}
	// TODO: Phase this out
	function info($message)
	{
		global $OUTPUT,$rwdInfo;
		$data = array("message" => "$message");
		$OUTPUT->load_template("skin_global");
		$info = $rwdInfo->skin_global->info($data);
		$OUTPUT->add_output($info);
	}
	
	
	function GetFileExtention($filename)
	{ 
	    $ext = strchr($filename,"."); 
	    return $ext; 
	} 
	
	function saveConfig()
	{
	    global $CONFIG;
		ksort($CONFIG);
		foreach( $CONFIG as $name=>$val )
	    {
			// Make values safe
			$val = preg_replace( "/'/", "\\'" , $val );
			$val = preg_replace( "/\r/", ""   , $val );
	        $val = str_replace( "\\", "\\\\"  , $val );
	
			$save[ $name ] = $val;
		}
	
	    // Add PHP header to prevent file being read as text
		$saveString = '<?php'."\n";
	    // Add PHP config variables
	    foreach( $save as $name=>$val )
	    {
			$saveString .= '$CONFIG['."'".$name."'".'] = '."'".$val."';\n";
		}
	    // End PHP file
	    $saveString .= '?>';
	
	    $fileName = $CONFIG["sitepath"]."/globalvars.php";
		if ( $fp = fopen( $fileName, 'w' ) )
		{
			fwrite($fp, $saveString, strlen($saveString) );
			fclose($fp);
			return true;
		}
		else
	    {
			$this->error("Could not create/save config file. Please ensure {$fileName} is set with write permissions [777/666]");
			return false;
	    }    
	
	}
	
	function updateNavDL($nav, $dlid, $isAdmin=0)
	{
		global $DB;
		
		$DB->query("SELECT name, categoryid FROM dl_links WHERE did=$dlid");
		if ( (!$myrow = $DB->fetch_row()) )
			$this->error(GETLANG("er_nonavdlid"));
		
		if ( $isAdmin )
			$nav = " > <a href='admin.php?sid=$sid&area=files&act=editdl&id=$dlid'>{$myrow['name']}</a>".$nav;
		else
			$nav = " > <a href='index.php?dlid=$dlid'>$myrow[name]</a>".$nav;
		$nav = $this->updateNav($nav, $myrow["categoryid"], $isAdmin);
		return $nav;
	}
	
	function updateNav($nav, $id, $isAdmin=0)
	{
		global $DB, $rwdInfo, $CONFIG, $sid;
	
	    //if ( $nav )
	        //$nav = " > ".$nav;
		if ($id)
		{
			if ( !$rwdInfo->cats_saved )
			{
	            $DB->query("SELECT * FROM dl_categories");
				if ($myrow = $DB->fetch_row())
				{
	                do
					{
					    // Add category to cache
					    $rwdInfo->cat_cache[$myrow["cid"]] = $myrow;
	                } while ($myrow = $DB->fetch_row());
				}
				$rwdInfo->cats_saved = 1;
			}
	        $nav = $this->navFindCat($rwdInfo->cat_cache, $id, $nav, $isAdmin);
	
		}
	
		if ( $isAdmin )
			return $rwdInfo->nav = "<a href='".$rwdInfo->url."/admin.php?sid=$sid&area=main&act=home'>".GETLANG("nav_adhome")."</a>".$nav;
		else
			return $rwdInfo->nav = "<a href='".$rwdInfo->url."/index.php'>".GETLANG("nav_home")."</a>".$nav;
	}
	
	function navFindCat($array, $id, $nav, $isAdmin)
	{
		global $rwdInfo, $sid;
		
	    foreach ( $array as $cat )
		{
			if ($id == 0)
				break;
			if ( $cat["cid"] == $id )
			{
				if ( $isAdmin )
					$nav = " > <a href='".$rwdInfo->url."/admin.php?sid=$sid&area=files&act=edit&cid=$id'>".$cat["name"]."</a>".$nav;
				else
					$nav = " > <a href='".$rwdInfo->url."/index.php?cid=$id'>".$cat["name"]."</a>".$nav;
			}
		}
	    return $nav;
	}
	
	function pages($items, $limit, $urlstring)
	{
		$result = "";
	
		$pages = ceil(($items/$limit));
		if ($pages > 1)
		{
			$result = GETLANG("pages").": ";
			for ($x=1; $x<=$pages; $x++)
			{
				if ($x > 1)
					$result .= ", ";
				$start = ($x - 1) * $limit;
				$result .= "<a href=$urlstring&limit=$start>$x</a>";
			}
		}	
		return $result;
	}
	
	/*************************************************************************
	*	Drop down box containing categories and sub categories
	*************************************************************************/
	function catListBox($category = -1, $boxname, $permCol="canBrowse", $showAll=0)
	{
		global $DB, $rwdInfo;
					
		if ( !$rwdInfo->cats_saved )
		{
			$DB->query("SELECT * FROM dl_categories");
			if ($myrow = $DB->fetch_row())
			{
				do
				{
					// Add category to cache
					$rwdInfo->cat_cache[$myrow["cid"]] = $myrow;
				} while ($myrow = $DB->fetch_row());
			}
			$rwdInfo->cats_saved = 1;
		}
	
		if ( count($rwdInfo->cat_cache) > 0 )
		{
			$output = "<select name='$boxname'>";
			$output .= "<option value='0'>".GETLANG("nav_home")."</option>";
			$output .= "<option value='0'>----------</option>";
		}
		else
			$output = GETLANG("nobasecat");
		foreach ( $rwdInfo->cat_cache as $cat )
		{
			$output .= "<option value='".$cat["cid"]."'";
			if ( $category == $cat["cid"] )
				$output .= " selected";
			$output .= ">".$cat["name"]."</option>\n";
		}
		if ( count($rwdInfo->cat_cache) > 0 )
			$output .= "</select>";
		return $output;
	}
	
	function skinListBox($id=0)
	{
		global $DB;
		
		$DB->query("SELECT * FROM dl_skinsets");
		
		if ( $myrow=$DB->fetch_row() )
		{
			$output = "<select name='skinchoice'>";
			do
			{
				$output .= "<option value='{$myrow['setid']}'";
				if ( $myrow['setid'] == $id )
					$output .= " selected";
				$output .= ">{$myrow['name']}</option>";
			} while ( $myrow=$DB->fetch_row() );
			$output .= "</select>";
		}
		return $output;
	}
	
	function langListBox($id=0)
	{
		global $DB;
		
		$DB->query("SELECT * FROM dl_langsets");
		
		if ( $myrow=$DB->fetch_row() )
		{
			$output = "<select name='langchoice'>";
			do
			{
				$output .= "<option value='{$myrow['lid']}'";
				if ( $myrow['lid'] == $id )
					$output .= " selected";
				$output .= ">{$myrow['name']}</option>";
			} while ( $myrow=$DB->fetch_row() );
			$output .= "</select>";
		}
		return $output;
	}
	
	function emailvalidate($str)
	{ 
		// Submitted to phpfreaks.com by: Derek Ford - February 2nd, 2003
		$str = strtolower($str); 
		if(ereg("^([^[:space:]]+)@(.+)\.(ad|ae|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|fx|ga|gb|gov|gd|ge|gf|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nato|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$",$str)){ 
			return 1; 
		} 
		else
		{ 
			return 0; 
		} 
	} 

	// To be called after any move or delete operation
    function resyncCats($cid)
   	{
       	global $DB, $rwdInfo;

		$catq = $DB->query("SELECT * FROM dl_categories WHERE cid={$cid}");
		$thiscat = $DB->fetch_row($catq);

		$best["id"] = "0";
		$best["time"] = "0";
		$best["name"] = "";
		$num_files = 0;
           
		$result = $DB->query(  "SELECT did,name,date FROM dl_links WHERE categoryid={$cid} AND approved=1 ORDER BY `date` DESC");
       	$last_upload = $DB->fetch_row($result);
		$num_files += $DB->num_rows($result);

		$update = array( 	"lastid" 	=> $last_upload['did'],
   							"lastTitle" => $last_upload["name"],
							"lastDate" 	=> $this->converttotime($last_upload['date']),
                        	"downloads" => $num_files );

       	$DB->update( $update, "dl_categories", "cid=$cid");
       
		// Update the cache
		foreach( $update as $k => $v )
		{
		    $rwdInfo->cat_cache[ $cid ][ $k ] = $v;
		}
   	}

    function canUploadFiles()
    {
        global $CONFIG;

        return $CONFIG['guest_uploads'];
    }

	function saveGlobals()
	{
	    $return = array();
	    if( !empty($_GET) )
	    {
		foreach( $_GET as $i=>$v )
		{	    
		    if( is_array($_GET[$i]) )
			{
			foreach( $_GET[$i] as $i2=>$v2 )
			{
				$return[$i][$i2] = $this->makeSafe($v2);
			}
		    }
		    else
		    {
			$return[$i] = $this->makeSafe($v);
		    }
		}
		}
	
	    // Post data is more secure so if anything has duplicates then use post instead
	    if( !empty($_POST) )
	    {
		foreach( $_POST as $i=>$v )
		{	    
		    if( is_array($_POST[$i]) )
		    {
			foreach( $_POST[$i] as $i2=>$v2 )
			{
				$return[$i][$i2] = $this->makeSafe($v2);
			}
		    }
		    else
		    {
			$return[$i] = $this->makeSafe($v);
		    }
		}
	    }
	
	    $return['ipaddr'] = $_SERVER['REMOTE_ADDR'];
	    $return['referer'] = $_SERVER['HTTP_REFERER'];
	
	    return $return;
	}
	
	// Make sent data safe from mallicious code
	function makeSafe($val)
	{
	    if ($val == "")
		return "";
	
	    // Trim whitespace
	    $val = trim($val);
		
	    $val = str_replace( "&#032;"       , " "		     , $val );
	    $val = str_replace( chr(0xCA)      , ""		         , $val );
	    // Do a load of security checks on user input
	    $val = str_replace( "&"            , "&amp;"         , $val );
	    $val = str_replace( "<!--"         , "&#60;&#33;--"  , $val );
	    $val = str_replace( "-->"          , "--&#62;"       , $val );
	    $val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
	    $val = str_replace( ">"            , "&gt;"          , $val );
	    $val = str_replace( "<"            , "&lt;"          , $val );
	    $val = str_replace( "\""           , "&quot;"        , $val );
	    $val = str_replace( "\\"           , "\\\\"          , $val );
	    //$val = preg_replace( "/\n/"        , "<br>"          , $val ); VV Bad!
	    $val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
	    $val = preg_replace( "/\r/"        , ""              , $val );
	    $val = str_replace( "!"            , "&#33;"         , $val );
		$val = str_replace( "\'"            , "&#39;"         , $val );
	    $val = str_replace( "'"            , "&#39;"         , $val );
		$val = stripslashes($val);
	    return $val;
	}
	
	function undoHTMLChars($t)
	{
		$t = str_replace( "&amp;" , "&", $t );
		$t = str_replace( "&lt;"  , "<", $t );
		$t = str_replace( "&gt;"  , ">", $t );
		$t = str_replace( "&quot;", '"', $t );
		//$t = str_replace( "&#39;" , "'", $t );
		
		return $t;
	}
	
	function my_filesize($size)
	{
		// Setup some common file size measurements.
		$kb = 1024;         // Kilobyte
		$mb = 1024 * $kb;   // Megabyte
		$gb = 1024 * $mb;   // Gigabyte
		$tb = 1024 * $gb;   // Terabyte
	
		// If it's less than a kb we just return the size, otherwise we keep going until
		// the size is in the appropriate measurement range
	
		if($size < $kb)
			return $size." B";
		else if($size < $mb)
			return round($size/$kb,2)." KB";
		else if($size < $gb)
			return round($size/$mb,2)." MB";
		else if($size < $tb)
			return round($size/$gb,2)." GB";
		else
			return round($size/$tb,2)." TB";
	}
	
	function calc_time ($seconds)
	{
		$days = (int)($seconds / 86400);
		$seconds -= ($days * 86400);
		if ($seconds)
		{
			$hours = (int)($seconds / 3600);
			$seconds -= ($hours * 3600);
		}
		if ($seconds)
		{
			$minutes = (int)($seconds / 60);
			$seconds -= ($minutes * 60);
		}
		$time = array('days'=>(int)abs($days),
		'hours'=>(int)abs($hours),
		'minutes'=>(int)abs($minutes),
		'seconds'=>(int)abs($seconds));
		return $time;
	}
	
	function strip_ext($name)
	{
	     $ext = strrchr($name, '.');
	     if($ext !== false)
	     {
	         $name = substr($name, 0, -strlen($ext));
	     }
	     return $name;
	}

	function userOptions()
	{
		global $rwdInfo, $OUTPUT;

        $data = array(  "canUpload" => $this->canUploadFiles() );
		$OUTPUT->load_template("skin_global");
        $rwdInfo->links = $rwdInfo->skin_global->useroptions($data);

	}
	
	// Why is the standard function crap?
	function mynl2br( $data ) 
	{
	   return preg_replace( '!\\n!iU', "<br />", $data );
	}
	// Why isnt this a standard function?
	function br2nl( $data ) 
	{
	   return preg_replace( '!<br.*>!iU', "\n", $data );
	}

    function rmdirr($dirname)
    {
        // Sanity check
        if (!file_exists($dirname)) {
            return false;
        }

        // Simple delete for a file
        if (is_file($dirname)) {
            return unlink($dirname);
        }

        // Loop through the folder
        $dir = dir($dirname);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }

            // Recurse
            $this->rmdirr("$dirname/$entry");
        }

        // Clean up
        $dir->close();
        return rmdir($dirname);
    }
	
	function my_stripslashes($arr = array() )
	{
		foreach ($arr as $i=>$j)
			$out[$i] = stripslashes($arr[$i]);
		return $out;
	}
	
	function shorten_string($string)
	{
		$varlength = strlen($string); // count number of characters
		$limit = 256; // set character limit
		if ($varlength > $limit)  // if character number if more than character limit
			$string = substr($string,0,$limit) . "..."; // display string up to character limit, add dots 
		return $string;
	}
	
	function isExternalFile($filename)
	{
		if ( stristr( $filename, "http://" ) || stristr( $filename, "ftp://" ) || stristr( $filename, "https://" ))
			return TRUE;
		else
			return FALSE;
	}
}
    // Do something very important
if ( INSTALL != "RWD4" ){if (!($fout=fopen($rwdInfo->path."/skins/skin".$CONFIG["defaultSkin"]."/main.htm","r")))$err_msg .= $this->error(GETLANG("er_cannotwrite"));$template = fread($fout, filesize($rwdInfo->path."/skins/skin".$CONFIG["defaultSkin"]."/main.htm"));if (!stristr($template, '{copyright}')){	$err_msg .= $this->error(GETLANG("warn_copyright"));	die(GETLANG("warn_copyright"));}fclose($fout);}
?>
<?php
// +------------------------------------------------------------------+
// | evoArticles 
// | Copyright (c) 2003-2004 Evo-Dev
// +------------------------------------------------------------------+


/* ------------------------- function class ------------------------------ */
class admin
{
	var $rowcount          = 0;
	var $inputsize         = 30;
	var $rowname_size      = "10pt";
	var $compresslevel     = "5"; //get back on that

	var $clear_htmlchars   = 0;
	var $clear_slash       = 0;
	var $clear_js          = 1;
	var $clear_strip       = 1;

	var $table_align       = "center";
	var $do_check          = 0; //check onSubmit();
	var $element_condition = array();
	
	function load_buttons($name='',$field='')
	{
		global $root,$evoLANG;
		$content = $this->get_file($root.'misc/js/buttons.js');

		$content = str_replace("<<docname>>",$name,$content);
		$content = str_replace("<<field>>",$field,$content);
		
		if( is_array($this->buttons_add) )
		{
			foreach($this->buttons_add as $buttons)
			{
				$additional .= "document.write(\" <input type='button' value='$buttons[name]' onClick='javascript:$buttons[js]' title=\\\"$buttons[desc]\\\" style=\\\"font-weight:bold\\\"> \");\n";
			}
		}
		$content = str_replace("<<additional_buttons>>",$additional,$content);

		$content = preg_replace("/(\{)(LANG:)(.*)(})/seiU","\$evoLANG[\\3]",$content);
		return "<script type=\"text/javascript\">".$content."</script>";
	}

	function get_buttons()
	{
		return "<script type=\"text/javascript\">makebuttons();</script>";
	}

/*	function check_gzip()
	{
		global $_SERVER;
		if (strpos(" ".$_SERVER['HTTP_ACCEPT_ENCODING'],"x-gzip"))
		{
			$encoding = "x-gzip";
		}
		
		if (strpos(" ".$_SERVER['HTTP_ACCEPT_ENCODING'],"gzip"))
		{
			$encoding = "gzip";
		}
		return $encoding;
	}*/


	function do_compress()
	{
		global $_SERVER;

			if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') && $this->compresslevel > 0)
			{
				
				if ($this->gzip_run == 1) return;
				$this->gzip_run = "1";
				
				ob_start("ob_gzhandler");
				header("Content-Encoding: x-gzip");

			}
			
	}

	function check_active($active)
	{
		global $evoLANG,$_SERVER;
		if ($active == 0)
		{
			echo $evoLANG[disabled];
			echo $this->redirect("index.php",1);
			exit;
		}
	}
	
/*	function get_plugin($file,$usedefault=0)
	{
		global $root;

		if ($usedefault)
		{
			$folder = $root."/admin/addon";
		}

		if ($file == '') {
			break;
		}

		if (file_exists($folder.$file))
		{
			require($folder.$file);
		}
				
	}*/
	
	function convert_tag($text)
	{
		 $text = str_replace("&lt;","&amp;lt;",$text);
		 $text = str_replace("&gt;","&amp;gt;",$text);
		 $text = str_replace("<","&lt;",$text);
		 $text = str_replace(">","&gt;",$text);
		 return $text;
	}

	function nohtml($text)
	{
		$text = preg_replace("'<[\/\!]*?[^<>]*?>'si","",$text);
		return $text;
	}
	
	function remove_tag($text,$tag='')
	{
		if ($tag != "")
		{
			$text = preg_replace("'<".$tag."[\/\!]*?[^<>]*?>'si","",$text);	
		}
		return $text;
	}
	
	function nocache()
	{
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
	
	function replace_br($text)
	{
		$search = array ("/<br>/i","'<br />'i");
		$replace = array ("\n","\n");
		$text = preg_replace($search,$replace,$text);
		return $text;
	}

	function strip_br($text)
	{
		$search = array ("/<br>/i","'<br />'i");
		$replace = array ('','');
		$text = preg_replace($search,$replace,$text);
		return $text;
	}

	function strip_p($text)
	{
		$search = array ("/<p>/i","'</p>'i");
		$replace = array ("","");
		$text = preg_replace($search,$replace,$text);
		return $text;
	}

	function partial($text,$chars='25',$desc=1)
	{
		$text2 = $text;
		if (strlen($text) > $chars)
		{
			$text = substr($text, 0, $chars);
			$text = ($desc) ? "<span title=\"".$text2."\">".$text."..</span>": $text."..";;
		}

		return $text;
	}

	function intercap($text,$entire=1)
	{
		if ($entire)
		{
			return ucwords(strtolower($text));
		}
		else
		{
			return ucfirst(strtolower($text));
		}
	}
	
	function strip($value,$trim=1)
	{
		
		if ($trim)
		{
			return stripslashes(trim($value));
		}
		else
		{
			return stripslashes($value);
		}
	}

	function slash($value)
	{
		if (get_magic_quotes_gpc() == '0')
		{
			return addslashes($value);
		}
		else
		{
			return $value;
		}
	}

	function slash_array(&$array)
	{
		if(is_array($array))
		{
			if (get_magic_quotes_gpc() == '0')
			{
				reset($array);
				while(list($key,$val)=each($array))
				{
					if(is_string($val))
					{
						$array[$key]=$this->slash($val);
					}
					elseif(is_array($val))
					{
						$array[$key]=$this->slash_array($val);
					}
				}			
			}
		}

		return $array;
	}

	function strip_array(&$array,$no=0)
	{
		if (is_array($array))
		{
			//if (get_magic_quotes_gpc() == '1')
			//{
				reset($array);
				while(list($key,$val)=each($array))
				{
					if(is_string($val))
					{
						$array[$key] = $this->strip($val);
					}
					elseif(is_array($val))
					{
						$array[$key] = $this->strip_array($val);
					}
				}
			//}
		}
		return $array;
	}

	function add_row($title,$value,$desc='',$align='center',$color=1)
	{
			$this->rowcount++;
			if ($desc!='')
			{
				$desc  ="<br />".$desc;
			}
			if ($this->row_nobold != 1)
			{
				$b[f] = "<b style='font-size:".$this->rowname_size."'>";
				$b[b] = "</b>";
			}

			$this->row_align = ($this->row_align != "") ? $this->row_align:$align;
			//$size = "style='font-size:".$this->rowname_size."'";
			if ($color)
			{
				$a = "<tr> <td ".($this->row_once == 1 ? ' colspan="2" ':'').$this->get_bg($this->rowcount)." valign=\"top\" width=\"$this->row_width\">$b[f]$title$b[b]$desc</td>";
				$a .= $this->row_once == 1 ? "":"<td ".$this->get_bg($this->rowcount)." align=\"".$this->row_align."\"> $value </td></tr>\n";
			}
			else
			{
				$a = "<tr> <td valign=\"top\">$b[f]$title$b[b]$desc</td>";
				$a .= "<td align='".$align."' class=\"normal\"> $value </td>";
				$a .= "</tr>\n";
			}
		
			return $a;
		}
		
		function add_spacer($text='',$span="2",$align="left")
		{
			$type = ($this->usenormal) ? "thrdalt":"tblhead";
			return "<tr><td class=\"$type\" colspan=\"$span\" align=\"$align\">&nbsp;$text</td></tr>";
		}

		function add_saparator($text='',$width='100%')
		{
			$a .= "</table></td></tr></table><br />";
			$a .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" width=\"$width\"><tr><td class=\"tblborder\">\n";
			$a .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"1\" align=center width=\"100%\">\n";
			$a .= $this->add_spacer($text);
			return $a;
		}
		
		function add_table($content='',$width='100%')
	    {
			$a .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" align=\"center\" width=\"$width\">
				   <tr> 
				   <td class=\"tblborder\"> <table align=\"center\" cellpadding=\"4\" cellspacing=\"1\" border=\"0\" width=\"100%\">
				  ";
			$a .= $content;
			$a .= "</table></td></tr></table>\n";

			return $a;		
		}

		function get_bg($i='',$is3=0)
		{
			$bg1="class=\"firstalt\"";
			$bg2="class=\"secondalt\"";
			$bg3="class=\"thrdalt\"";

			if ($is3)
			{
				$bg = $bg3;
			}
			else
			{
			  $bg = $i % 2 ? $bg1:$bg2;
			}

			return $bg;
		}
		
		function form_check($val,$field='')
		{
			global $_POST,$evoLANG,$_SERVER,$admin;
			if (trim($_POST[$val]) == "")
			{
				$field = ($field != "") ? "<b style=\"color: red\">Field: $field</b>":"";
				echo $evoLANG[formx]." ".$field."<br />";
				echo $admin->redirect($_SERVER['HTTP_REFERER'],1);
				exit;
			}
			else
			{
				return;
			}
		}
	
		function form_make_element($postname,$name,$ctr)
		{
			if ($this->element_condition[$postname])
			{
				if ( is_array($this->element_condition[$postname]) )
				{
					foreach($this->element_condition[$postname] as $element)
					{
						$forelement .= "|| document.$ctr.$postname.value == \"".$element."\"";
					}
				}
				else
				{
					$forelement = " || document.$ctr.$postname.value == \"".$this->element_condition[$postname]."\"";
				}
			}
			
			$a .= "if (document.$ctr.$postname.value == \"\" $forelement) { alert(\"Invalid Value: $name\"); return false;} \n";
			return $a;
		}

		function form_start($file,$action,$enc=0)
		{
			$this->form_counter++;
			if ($enc) $add = "enctype=\"multipart/form-data\"";
			$file = ($file == "") ? "forms_".$this->form_counter : $file;
			if ($this->do_check) //CHECK ? 1:0
			{
				if (is_array($this->check_element))
				{					
					foreach ($this->check_element as $element => $name)
					{
						$js_element .= $this->form_make_element($element,$name,$file);
					}
					//echo $this->additional_validation;

					$this->checker = "onsubmit=\"return Check_Form_$file();\"";
					$js = "<script type=\"text/javascript\">function Check_Form_$file()\n{\n $js_element ".$this->additional_validation." return true;\n }\n</script>\n";	
				}				
			}

			return $js." <form action=\"$action\" method=\"post\" name=\"$file\" $add $this->form_start_additional $this->checker>\n";
		}

		function form_hidden($file,$value='')
		{
			return "<input type=\"hidden\" value=\"$value\" name=\"$file\" />\n";
		}

		function form_input($file,$value='',$type='text',$additional='')
		{
			//print_r($additional);
			if (is_array($additional) )
			{
				foreach ($additional as $attr_name => $attr_value)
				{
					$attributes .= $attr_name."=\"".$attr_value."\" ";
				}
			}

			if (!$attributes) $attributes = "size='".$this->inputsize."'";
			return "<input type=\"$type\" name=\"$file\" value=\"$value\" $attributes />\n";
		}

		function form_select2($file,$value)
		{
			return "<select name=\"$file\">$value</select>\n";
		}

		function form_textarea($file,$value='',$size='40|8')
		{
			$a = explode("|",$size);
			return "<textarea name=\"$file\" cols=\"$a[0]\" rows=\"$a[1]\">$value</textarea>\n";
		}

		function form_radio_yesno($file,$chk='')
		{
			global $evoLANG;

			$yes = " $evoLANG[word_yes] <input type=\"radio\" name=\"$file\" value=\"1\" />\n";
			$no = "$evoLANG[word_no] <input type=\"radio\" name=\"$file\" value=\"0\" />\n";

			if ($chk==1)
			{
				$yes = " $evoLANG[word_yes] <input type=\"radio\" name=\"$file\" value=\"1\" checked />\n";
			}
			else
			{
				$no = "$evoLANG[word_no] <input type=\"radio\" name=\"$file\" value=\"0\" checked />\n";
			}
			
			return $yes.$no;
		}
		
		function form_checkbox($name,$value='',$sel='0')
		{
			global $evoLANG;

			$sel = ($sel == "0") ? "":" checked";
			$a = "<input type=\"checkbox\" name=\"$name\" value=\"$value\" $sel $this->checkbox_extra/>\n";
			return $a;			
		}
		
		function form_check_yesno($file,$chk='')
		{
			global $evoLANG;
						
			$checked = $chk == 1 ? "CHECKED":"";
			
			$stuff = "<input type=\"checkbox\" name=\"$file\" value=\"1\" $checked />\n";
			return $stuff;
		}

		function form_select_yesno($file,$chk='')
		{
			global $evoLANG;
			$yes = "<option value=\"1\">".$evoLANG['word_yes']."</option>\n";
			$no = "<option value=\"0\">".$evoLANG['word_no']."</option>\n";
			
			if ($chk == 1) {
				$yes = "<option value=\"1\" selected=\"selected\">".$evoLANG['word_yes']."</option>\n";
			} else {
				$no = "<option value=\"0\"  selected=\"selected\">".$evoLANG['word_no']."</option>\n";
			}
			
			$stuff = "<select name=\"".$file."\">\n".$yes.$no."</select>\n";
			return $stuff;
		}
		
		// usage : form_select("name","value1|value2,val1_name,val2_name");
		function form_select($file,$val,$selected='',$def=" - - - - ",$othercrap='')
		{
			if (trim($file and $val) != '')
			{
				//if (is_array($val)) {
				// array format : options|name,options|name
				$get = explode(",",$val);
				if ($this->form_select_array == '1')
				{
					$selarray = explode(",",$selected);
				}

				for($i=0;$i < count($get); $i++)
				{
					unset($sel);
					$get2 = explode("|",$get[$i]);
					// so now you have the stupid ding-dong xx | yy splitted
					if ($this->form_select_array == '1')
					{
						if ( in_array($get2[0],$selarray) )
						{
							$sel = "selected=\"selected\"";
						}
					}
					else
					{
						if (($selected != '') && ($selected == $get2[0]))
						{
							$sel = " selected=\"selected\"";
						}
					}

					if ($get2[1] != "")
					{
						$a .= "<option value=\"".$get2[0]."\"$sel>". $get2[1]." </option>\n";
					}
				}
				
				$other = ($def != '') ? "\n<option value=\"-1\"> ".$def." </option>\n":'';
				$content = "\n<select name=\"".$file."\" $othercrap>".$other.$a."</select>\n";
				return $content;
				//}	
			}
		}

		function form_submit($file,$text='Do It!',$howmany='2')
		{
			$a = "<tr><td colspan=\"$howmany\" align=\"center\" class=\"tblhead\">";
			$a .= "<input type=\"submit\" name=\"$file\" value=\"$text\" /> ";
			$a .= "<input type=\"reset\" value=\"reset\" /></td></tr>\n";

			return $a;
		}

		
	function makecookie($file,$value="",$howlong="year",$domain="")
	{
		global $_SERVER,$settings;

		switch($howlong)
		{
			case "year":
				$expire = time() + 60 * 60 * 24 * 365; // one little year :)
			break;
			/* =-----------------= */
			case "month":
				$expire = time() + 60 * 60 * 24 * 30;
			break;
			/* =-----------------= */
			case "day":
				$expire = time() + 60 * 60 * 24;
			break;
			/* =-----------------= */
			case "hour":
				$expire = time() + 60 * 60;
			break;
			/* =-----------------= */
			default;
				$expire = trim($howlong) != '' ? time() + $howlong : 0;
		}

		setcookie ( $file, $value, $expire, '', $settings['cookiedomain'], $settings['seccookie'] );
	}

	function clearcookie($file)
	{
	  global $_SERVER;
		$secured = ($_SERVER['SERVER_PORT'] == "443") ? 1:0;
		setcookie($file,'', time()-3600, '', '', $secured);		
	}

	function redirect($too,$howlong="2")
	{
		
		$howlong =  $howlong * 1000;
		
		$a = "<script type=\"text/javascript\">
			 <!--
			setTimeout(\"this.location = '$too' \",$howlong);			
			//-->
			</script>";
		

		//$a = "<meta http-equiv=\"Refresh\" content=\"$howlong; URL=$too\">";
		return $a;
	}

	function makelink($linkname,$link,$text='',$target="_self")
	{
		$a = "<a href=\"$link\" title=\"$text\" target=\"$target\">$linkname</a> ";
		return $a;
	}
	
	function get_file($url,$read='r')
	{
		$a = fread(fopen($url, $read),filesize ($url));
		return $a;
	}
	
	function write_file($url,$value,$del=0,$write='w',$strip=1)
	{
		global $evoLANG;
		$this->write_count++;

		if ($del) @unlink ($url);
		@chmod ($url, 0666);
		
		if ($strip)
		{
			$value=stripslashes($value);
		}
		
		fputs(fopen ($url, $write),$value);
		
		return $evoLANG['filesuccess'];	
	}
	
	function makedir($path,$chmod='777')
	{
		@mkdir($path,$chmod);
	}

	function deldir($dir)
	{
		if ($dir != "./" || $dir != "." || $dir != "../")
		{
			$handle = opendir($dir);
			while ($file=readdir($handle)) {
				if ($file != "." && $file != "..") {
					$file2=explode(".",$file);
					if (!$file2[1]) {
						$this->deldir($file2[2]);
					} else {
					unlink($dir."/".$file);
						$content .= "<b".$dir."/".$file."</b> deleted <br />";
					}
				}
			} closedir($handle);
			@rmdir($dir);
			$content .= "<b> $evoLANG[dirremoved] </b><br /> ";
		} return $content;
	}
	

	function link_button($text,$loc)
	{
		if (trim($text) == "") return false;
		if (trim($loc) == "") return false;
		return "<input type=\"button\" onClick=\"window.location='".$loc."'\" value=\"".$text."\" />";
	}

	function jsconfirm($msg="")
	{
		global $script_db,$udb,$admin,$tpl,$evoLANG,$_SERVER;
		$msg = ($msg == "") ? "Are You Sure?":$msg;

		$a .= "<script type=\"text/javascript\">
				var con = confirm('".$msg."');
				if (con)
				{
					document.write('As you wish...');
				} else
				{
					
					document.write('You Coward!');
					window.location='".$_SERVER[HTTP_REFERER]."';
					confirm('Forwarding you to : $_SERVER[HTTP_REFERER]');
				}
				</script>
				";
		//document.write('".$this->redirect($_SERVER['HTTP_REFERER'])."');

		return $a;
	}

	function confirmpage($msg='',$url='')
	{
		global $script_db,$udb,$admin,$tpl,$evoLANG,$_SERVER;
		$REQUEST_URI = $_SERVER['REQUEST_URI'];
			if (preg_match("/confirm=no/",$REQUEST_URI)) {
				$REQUEST_URI = str_replace("confirm=no","confirm=yes",$REQUEST_URI);
				$url = "<a href=$REQUEST_URI>Yes</a>";
			}
				eval("\$content .= \"".$tpl->gettemplate("deletepage")."\";");
		return $content;
	}

	function do_nav($file,$links)
	{
		global $evoLANG;
		if (preg_match ("/|/", $file)) {
			$file2 = explode ("|", $file);
			$links2 = explode("|", $links);
		$a = "<p align='right' class='title'><b>$evoLANG[location] :</b> ";
			
			while (($a1 = each ($file2)) && ($a2 = each ($links2))) {
			$file3 = $file2;
			if (current($a2) != 'nolink') {
				$a .= $this->makelink(current($a1),current($a2));
			} else {
				$a .= current($a1);
			}
			
				if (current($a1) != end($file3)) {
					$a .= "> ";
				}

			}
		}
		$a .= "</p>";
		return $a;
	}

	function validate_email($email="")
	{
		if (eregi("[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}", $email))
		{
			return "1";
		}
		else
		{
			return "0";
		}
	}

	function get_ext($file="")
	{
		return substr($file,(strrpos($file,".")?strrpos($file,".")+1:strlen($file)),strlen($file)); 
	}
	
	function htmlspecialchars($array='')
	{
		if (is_array($array))
		{
			reset($array);
			while(list($key,$val)=each($array))
			{
				if(is_string($val))
				{
					$array[$key]=htmlspecialchars($val);
				}
				elseif(is_array($val))
				{
					$array[$key]=$this->htmlspecialchars($val);
				}
			}
			return $array;
		}
		else
		{
			return htmlspecialchars($array);
		}
	}

	function clean_code($array='')
	{
		if ($what != "")
		{
			/*$this -> clear_htmlchars = (isset($this -> clear_htmlchars)) ? $this -> clear_htmlchars:"1";
			$this -> clear_slash = (isset($this -> clear_slash)) ? $this -> clear_slash:"1";
			$this -> clear_js = (isset($this -> clear_js)) ? $this -> clear_js:"1";
			$this -> clear_strip = (isset($this -> clear_strip)) ? $this -> clear_strip:"0";*/
			
			while(list($key,$val)=each($array))
			{
				if(is_string($val))
				{
					$array[$key]=$this->cleaner($val);
				}
				elseif(is_array($val))
				{
					$array[$key]=$this->clean_code($val);
				}
			}			
		}
		return $array;
	}
	
	function cleaner($what)
	{
		//removed
		return $what;
	}

	function copy_dir($from='', $to='', $perm='0777')
	{
	
		if (!is_dir($from))
		{
			echo "NO Dir";
			return;
		}
	
		if (!is_dir($to_path))
		{
			//if ($this->to != "") $to = $this->to;
			if (!@mkdir($to,$perm))
			{
				echo "You dont have permission to copy directory into '$to' path";
				return;
			}
			else
			{
				@chmod($to,$perm);
			}
		}
		
				
		if (is_dir($from))
		{
			$this->path = getcwd();
			chdir($from);
			$handle=opendir('.');

			while ($file=readdir($handle))
			{
				if ($file != "." && $file != "..")
				{
					if (is_dir($file))
					{						
						$this->copy_dir($from."/".$file, $to."/".$file);
						chdir($from);
					}
					
					if ( is_file($file) )
					{
						copy($from."/".$file, $to."/".$file);
						@chmod($to."/".$file, $perm);
					} 
				}
			}
			closedir($handle); 
		}
	}

	function randomizer($length='8')
	{
		//found at Zend.com
		
		srand((double)microtime()*1000000); 
		$characters = "1,2,3,4,5,A,B,C,D,E,F";     
		$characters_length = (strlen($characters)-1)/2;
		$token = explode(",",$characters);                      
		$pass_length=$length;   // length of the password 
		 
		for($i=0;$i<$pass_length;$i++)
		{                       
			$rand = rand(0,$characters_length);                
			$out .= $token[$rand];                         
		}
		return $out;
	}

	function getdirfiles($dir='')
	{
		if ($dir == "") return;
		$files = array();

		$handle = opendir($dir);
		while ($file= readdir ($handle))
		{
			if ($file != "." && $file != "..")
			{					
				if ( is_file($file) )
				{
					array_push($files,trim($file));
				} 
			}
		}
		closedir($handle);
		return $files;
	}

	// from Matt Kment
	function get_tag($page, $string_start, $string_end)
	{
	  $start = strpos(strtolower($page), strtolower($string_start)) + strlen($string_start);
	  if(!$start) return(true); # If we don't have any settings

	  $end = strpos(strtolower($page), strtolower($string_end), $start);
	  if(!$end) return(false);
	  # Else

	  $data = substr($page, $start, $end - $start);

	  $page = trim(substr_replace($page, "", $start - strlen($string_start),
			($end + strlen($string_end)) - ($start - strlen($string_start))))
			. "\n";

	  return($data);
	}
	
	function fast_get_tag(&$page,$tag)
	{
		$content = $this->get_tag($page,"<".$tag.">","</".$tag.">");
		return trim($content);
	}

	function sec_ip($ip='')
	{
		if ($ip!="")
		{
			$split = explode(".",$ip);
			foreach($split as $splitted)
			{
				
				if (end($split) == $splitted)
				{
					$splitted = str_repeat("*",count_chars($splitted));
					$newip .= $splitted;
				}
				else
				{
					$newip .= $splitted.".";
				}
				
			}

			return $newip;
		}
	}

	function remove_root($text)
	{
		global $root;
		
		return str_replace("../","",$text);
	}

	function file_size($fsize=0)
	{	
		if ($fsize >= 1073741824) $fsize = round( $fsize /1073741824 * 100 ) /100 .' gb';
		elseif ($fsize >= 1048576) $fsize = round( $fsize / 1048576 *100 ) / 100 .' mb';
		elseif ($fsize >= 1024) $fsize = round( $fsize / 1024 * 100 ) / 100 . 'kb';
		else $fsize = $fsize . " bytes";
		return $fsize;
	}

	function check_fields($array='')
	{
		global $evoLANG,$_POST;

		if (is_array($array))
		{
			foreach ($array as $field)
			{
				if ( trim($_POST[$field]) == '' )
				{
					$this->error = 1;
				}
			}

			if ($this->error == 1)
			{
				$this->error_message = $evoLANG['checkfields'];
				return false;
			}
			else
			{
				return true;
			}
		}
	}

	function get_ip()
	{
		global $_SERVER,$_ENV;
		$getip = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR']:$_ENV['REMOTE_ADDR'];
		return $getip;
	}
	
	function warning($text)
	{
		$warn = "<div class=\"red\" style=\"border:1px dashed red;margin:4px;\"> <span style=\"color:red;font-weight:bold;margin:0;margin-left:5px;margin-right:5px;float:left;font-size:25px\">!</span> <h5 class=\"red\" style=\"margin:10px;\">".$text."</h5> </div> ";
		return $warn;
	}
}
?>
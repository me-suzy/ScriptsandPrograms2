<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
define( "BIT_0", 0 );
define( "BIT_1", 1 );
define( "BIT_2", 2 );
define( "BIT_3", 4 );
define( "BIT_4", 8 );
define( "BIT_5", 16 );
define( "BIT_6", 32 );
define( "BIT_7", 64 );
define( "BIT_8", 128 );
define( "BIT_9", 256 );
define( "BIT_10", 512 );
define( "BIT_11", 1024 );
define( "BIT_12", 2048 );
define( "BIT_13", 4096 );
define( "BIT_14", 8192 );
define( "BIT_15", 16384 );
define( "BIT_16", 32768 );
define( "BIT_17", 65536 );
define( "BIT_18", 131072 );
define( "BIT_19", 262144 );
define( "BIT_20", 524288 );
define( "BIT_21", 1048576 );
define( "BIT_22", 2097152 );
define( "BIT_23", 4194304 );
define( "BIT_24", 8388608 );
define( "BIT_25", 16777216 );
define( "BIT_26", 33554432 );
define( "BIT_27", 67108864 );
define( "BIT_28", 134217728 );
define( "BIT_29", 268435456 );
define( "BIT_30", 536870912 );
define( "BIT_31", 1073741824 );

function bittst($bitfield, $bit)
{
        return ($bitfield & $bit);
}

function setbit($bitfield, $bit)
{
        $bitfield |= $bit;
        return($bitfield);
}

function clearbit($bitfield, $bit)
{
        $bitfield &= ~$bit;
        return($bitfield);
}

function language_avail($checklang, $dirname="language/")
{
	$dir = opendir($dirname);
	$langfound=false;
	while ($file = readdir($dir))
	{
		if (ereg("^lang_", $file))
		{
			$file = str_replace("lang_", "", $file);
			$file = str_replace(".php", "", $file);
			if($file == $checklang)
				$langfound=true;
		}
	}
	return $langfound;
}

function dateToJuliandays($day, $month, $year)
{
	$juliandays = 367*$year - floor(7*($year+floor(($month+9)/12))/4)
      - floor(3*(floor(($year+($month-9)/7)/100)+1)/4)
      + floor(275*$month/9) + $day + 1721028.5 + 12/24;
    return $juliandays;
}

function juliandaysToDate($JD,$dateformat)
{
	$Z = $JD+0.5;
	$F = $Z - floor($Z);

	$Z = floor($Z);
	$W = floor(($Z - 1867216.25)/36524.25);
	$X = floor($W/4);
	$A = $Z + 1 + $W - $X;
	$B = $A + 1524;
	$C = floor(($B - 122.1)/365.25);
	$D = floor(365.25*$C);
	$E = floor(($B - $D)/30.6001);

	if($E>13)
		$NewMonth = $E-13;
	else
		$NewMonth = $E-1;
	$NewDay = $B - $D - floor(30.6001*$E) +$F;
	if($NewMonth<3)
		$NewYear = $C-4715;
	else
		$NewYear = $C-4716;
	$returndate=date($dateformat,mktime(0,0,0,$NewMonth,$NewDay,$NewYear));
	return $returndate;
}

function do_htmlentities($input)
{
	global $encodecharset;
	if(phpversion() >= '4.3.0')
		$input=htmlentities($input,ENT_COMPAT,$encodecharset);
	else
		$input=htmlentities($input);
	return $input;
}

function do_htmlspecialchars($input)
{
	global $encodecharset;
	if(phpversion() >= '4.3.0')
		$input=htmlspecialchars($input,ENT_COMPAT,$encodecharset);
	else
		$input=htmlspecialchars($input);
	return $input;
}

function undo_htmlspecialchars($input)
{
	$input = preg_replace("/&gt;/i", ">", $input);
	$input = preg_replace("/&lt;/i", "<", $input);
	$input = preg_replace("/&quot;/i", "\"", $input);
	$input = preg_replace("/&amp;/i", "&", $input);
	return $input;
}

function undo_htmlentities($input)
{
	global $encodecharset;
	if(phpversion() >= '4.3.0')
	{
		$input=html_entity_decode($input,ENT_COMPAT,$encodecharset);
	}
	else
	{
		$trans = get_html_translation_table (HTML_ENTITIES);
		$trans = array_flip($trans);
		$input=strtr($input,$trans);
	}
	return $input;
}

function validate_email($email)
{
	$email_regex="^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~ ])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~ ]+\\.)+[a-zA-Z]{2,4}\$";
	return(eregi($email_regex,$email)!=0);
}

function get_start_tag($style)
{
	$start_tags=array("","<b>","<i>","<b><i>");
	return($start_tags[$style]);
}

function get_end_tag($style)
{
	$end_tags=array("","</b>","</i>","</b></i>");
	return($end_tags[$style]);
}

function forbidden_freemailer($email, $db)
{
	global $tableprefix;

	$sql="select * from ".$tableprefix."_freemailer";
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to the database.");
	if (!$myrow = mysql_fetch_array($result))
		return false;
	do{
		if(substr_count(strtolower($email), strtolower($myrow["address"]))>0)
			return true;
	} while($myrow = mysql_fetch_array($result));
	return false;
}

function remove_htmltags($input)
{
	$temp = "";
	$add=true;
	for ($i = 0; $i < strlen($input); $i++)
	{
		if (substr($input, $i, 1) == "<")
			$add = false;
		if ($add)
			$temp .= substr($input, $i, 1);
		if (substr($input, $i, 1) == ">")
			$add = true;
	}
	return $temp;
}

function get_userip()
{
	global $REMOTE_ADDR, $try_real_ip, $realipmode, $new_global_handling;

	if($try_real_ip)
	{
		if($realipmode==0)
			$ip=get_real_ip_0();
		else
			$ip=get_real_ip_1();
	}
	else
		$ip = $REMOTE_ADDR;
	return $ip;
}

function get_real_ip_0()
{
	global $new_global_handling;

	if($new_global_handling)
		$realip = isset($_SERVER['x_forwarded_for']) ? $_SERVER['x_forwarded_for'] : $_SERVER['remote_addr'];
	else
		$realip = isset($HTTP_SERVER_VARS['x_forwarded_for']) ? $HTTP_SERVER_VARS['x_forwarded_for'] : $HTTP_SERVER_VARS['remote_addr'];
	return $realip;
}

function get_real_ip_1()
{
	global $REMOTE_ADDR;

	if( getenv('HTTP_X_FORWARDED_FOR') != '' )
		$realip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );
	else
		$realip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : $REMOTE_ADDR );
}

function is_phpfile($filename)
{
	global $php_fileext;
	$fileext=strrchr($filename,".");
	if(!$fileext)
		return false;
	$fileext=strtolower(substr($fileext,1));
	if(in_array($fileext,$php_fileext))
		return true;
	return false;
}

function file_output($filename)
{
	$filedata=get_file($filename);
	if($filedata)
		echo $filedata;
}

function get_file($filename)
{
	$return = "";
	if($fp = fopen($filename, 'rb'))
	{
		while(!feof($fp)){
			$return .= fread($fp, 1024);
		}
		fclose($fp);
		return $return;
	}
	else
	{
		return FALSE;
	}
}

function is_gecko()
{
	global $HTTP_USER_AGENT;

	if (eregi('Konqueror.([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
		return false;
	if (eregi("Netscape6",$HTTP_USER_AGENT))
		return false;
	if (eregi("Gecko",$HTTP_USER_AGENT) ||
		eregi("Mozilla/5",$HTTP_USER_AGENT))
		return true;
	return false;
}

function is_konqueror()
{
	global $HTTP_USER_AGENT;

	if (eregi('Konqueror.([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
		return true;
	return false;
}

function is_ns7()
{
	global $HTTP_USER_AGENT;

	if (eregi("Netscape/7",$HTTP_USER_AGENT))
		return true;
	return false;
}

function is_ns6()
{
	global $HTTP_USER_AGENT;

	if (eregi("Netscape6",$HTTP_USER_AGENT))
		return true;
	return false;
}

function is_ns4()
{
	global $HTTP_USER_AGENT;

	if (ereg( 'MSIE.([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
		return false;
	if (ereg( 'Opera.([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
		return false;
	if (ereg( 'Mozilla/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
	{
		list($major,$minor)=explode(".",$log_version[1]);
		if($major=="4")
			return true;
	}
	return false;
}

function is_msie3()
{
	if(!is_msie())
		return false;
	if(get_browser_version()>=4)
		return false;
	return true;
}

function is_opera()
{
	global $HTTP_USER_AGENT;

	if (ereg( 'Opera.([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
		return true;
	return false;
}

function is_opera6()
{
	global $HTTP_USER_AGENT;

	if (ereg( 'Opera.([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
	{
		list($major,$minor)=explode(".",$log_version[1]);
		if($major=="6")
			return true;
	}
	return false;
}

function is_opera7()
{
	global $HTTP_USER_AGENT;

	if (ereg( 'Opera.([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
	{
		list($major,$minor)=explode(".",$log_version[1]);
		if($major=="7")
			return true;
	}
	return false;
}

function is_msie()
{
	global $HTTP_USER_AGENT;

	if (ereg( 'MSIE.([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
		return true;
	return false;
}

function is_win()
{
	global $HTTP_USER_AGENT;

    if (preg_match('/(win[dows]*)[\s]?([0-9a-z]*)[\w\s]?([a-z0-9.]*)/i',$HTTP_USER_AGENT))
    	return true;
    return false;
}

function get_browser_version()
{
	global $HTTP_USER_AGENT;

	if (ereg( 'MSIE ([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
	    return($log_version[1]);
	elseif (ereg( 'Opera ([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
    	return($log_version[1]);
    elseif (ereg( 'Netscape6/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
    	return($log_version[1]);
    elseif (ereg( 'Konqueror.([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
    	return($log_version[1]);
    elseif (ereg( 'Mozilla/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
    	return($log_version[1]);
	else
		return(0);
}

function string2unicode($text)
{
	for ($i=0;$i<strlen($text);$i++)
	{
		$a=substr($text,$i,1);
		if (ord($a) > 126)
		{
        	$text = substr_replace ($text, "&#".ord($a).";", $i, 1);
        	$i=$i+5;
     	}
		if ((ord($a) < 32) || (ord($a) > 255))
			$text = substr_replace($text, " ", $i, 1);
		if ($a == "\"")
		{
			$text = substr_replace ($text, "&#".ord($a).";", $i, 1);
        	$i=$i+4;
     	}
		if ($a == "\'")
		{
			$text = substr_replace ($text, "&#".ord($a).";", $i, 1);
			$i=$i+4;
		}
		if ($a == "<")
		{
			$text = substr_replace ($text, "&#".ord($a).";", $i, 1);
			$i=$i+4;
		}
		if ($a == ">")
		{
			$text = substr_replace ($text, "&#".ord($a).";", $i, 1);
			$i=$i+4;
		}
		if ($a == "&")
		{
			$text = substr_replace ($text, "&#".ord($a).";", $i, 1);
			$i=$i+4;
		}
		if ($a == "\$")
		{
			$text = substr_replace ($text, "\$\$", $i, 1);
			$i=$i+1;
		}
	}
	return $text;
}

function subwords($input, $maxlength)
{
	if((strlen($input)>$maxlength))
	{
		$text = explode(" ", $input);
		$i = 0;
		$length = 0;
		$output="";
		while(($i<count($text)) && ($length<$maxlength))
		{
			$length+=strlen($text[$i]);
			if($length<=$maxlength)
			{
				$output.=$text[$i]." ";
				$i++;
			}
		}
		if($i<count($text))
			$output.="...";
		return $output;
	}
	else
		return $input;
}

function get_form_data($fieldname)
{
	@$tmpdata=$_GET[$fieldname];
	@$tmpdata2=$_POST[$fieldname];
	if($tmpdata2)
		$tmpdata=$tmpdata2;
	return $tmpdata;
}

function dump_array($data)
{
	while(list($key, $value) = each($data))
		echo "$key: $value<br>";
}
function starts_with($startchar, $searchstring)
{
	if(substr($searchstring,0,1)==$startchar)
		return true;
	return false;
}

function sn_array_key_exists($searcharray, $searchkey)
{
	$arraykeys=array_keys($searcharray);
	for($i=0;$i<count($arraykeys);$i++)
	{
		if($arraykeys[$i]==$searchkey)
			return true;
	}
	return false;
}

function encode_emoticons($input, $url_emoticons, $db) {
	global $tableprefix;

	$input = ' ' . $input;
	$sql="SELECT *, length(code) as length FROM ".$tableprefix."_emoticons ORDER BY length DESC";
	if ($result = mysql_query($sql))
	{
		while ($emoticons = mysql_fetch_array($result))
		{
			$emoticon_code = preg_quote($emoticons["code"]);
			$emiticon_code = str_replace('/', '//', $emoticon_code);
			$input = preg_replace("/([\n\\ \\.])$emoticon_code/si", '\1<IMG SRC="' . $url_emoticons . '/' . $emoticons["emoticon_url"] . '">', $input);
		}
	}
	$input = substr($input, 1);
	return($input);
}

function decode_emoticons($input, $url_emoticons, $db) {
	global $tableprefix;

	$sql="select * from ".$tableprefix."_emoticons";
	if ($result = mysql_query($sql))
	{
		while ($myrow = mysql_fetch_array($result))
		{
			$input = str_replace("<IMG SRC=\"$url_emoticons/".$myrow["emoticon_url"]."\">", $myrow["code"], $input);
      }
   }
   return($input);
}

function make_clickable($input)
{
	$ret = " " . $input;
	$ret = preg_replace("#([\n ])([a-z]+?)://([^, \n\r]+)#i", "\\1<!-- SPCode auto-link start --><a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a><!-- SPCode auto-link end -->", $ret);
	$ret = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^, \n\r]*)?)#i", "\\1<!-- SPCode auto-link start --><a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a><!-- SPCode auto-link end -->", $ret);
	$ret = substr($ret, 1);
	return($ret);
}

function undo_make_clickable($input)
{
	$input = preg_replace("#<!-- SPCode auto-link start --><a href=\"(.*?)\" target=\"_blank\">.*?</a><!-- SPCode auto-link end -->#i", "\\1", $input);
	return $input;

}

function bbencode($input)
{
	global $url_simpnews;

	$input = " " . $input;
	if (! (strpos($input, "[") && strpos($input, "]")) )
	{
		$input = substr($input, 1);
		return $input;
	}
	$input = bbencode_code($input);
	$input = bbencode_quote($input);
	$input = bbencode_list($input);
	$input = preg_replace("/\[b\](.*?)\[\/b\]/si", "<!-- SPCode Start --><b>\\1</b><!-- SPCode End -->", $input);
	$input = preg_replace("/\[i\](.*?)\[\/i\]/si", "<!-- SPCode Start --><i>\\1</i><!-- SPCode End -->", $input);
	$input = preg_replace("/\[s\](.*?)\[\/s\]/si", "<!-- SPCode Start --><s>\\1</s><!-- SPCode End -->", $input);
	$input = preg_replace("/\[u\](.*?)\[\/u\]/si", "<!-- SPCode Start --><u>\\1</u><!-- SPCode End -->", $input);
	$input = preg_replace("/\[tt\](.*?)\[\/tt\]/si", "<!-- SPCode Start --><tt>\\1</tt><!-- SPCode End -->", $input);
	$input = preg_replace("/\[sub\](.*?)\[\/sub\]/si", "<!-- SPCode Start --><sub>\\1</sub><!-- SPCode End -->", $input);
	$input = preg_replace("/\[sup\](.*?)\[\/sup\]/si", "<!-- SPCode Start --><sup>\\1</sup><!-- SPCode End -->", $input);
	$input = preg_replace("/\[center\](.*?)\[\/center\]/si", "<!-- SPCode Start --><center>\\1</center><!-- SPCode End -->", $input);
	$input = preg_replace("/\[img\](.*?)\[\/img\]/si", "<!-- SPCode Start --><img src=\"\\1\" border=\"0\"><!-- SPCode End -->", $input);
	$input = preg_replace("/\[img align=(.*?)\](.*?)\[\/img\]/si", "<!-- SPCode Start --><img align=\"\\1\" src=\"\\2\" border=\"0\"><!-- SPCode End -->", $input);
	$input = preg_replace("/\[center\](.*?)\[\/center\]/si", "<!-- SPCode Start --><center>\\1</center><!-- SPCode End -->", $input);
	$patterns = array();
	$replacements = array();
	$patterns[0] = "#\[url\]([a-z]+?://){1}(.*?)\[/url\]#si";
	$replacements[0] = '<!-- SPCode u1 Start --><a href="\1\2" target="_blank">\1\2</a><!-- SPCode u1 End -->';
	$patterns[1] = "#\[url\](.*?)\[/url\]#si";
	$replacements[1] = '<!-- SPCode u1 Start --><a href="http://\1" target="_blank">\1</a><!-- SPCode u1 End -->';
	$patterns[2] = "#\[url=([a-z]+?://){1}(.*?) target=(.*?)\](.*?)\[/url\]#si";
	$replacements[2] = '<!-- SPCode u3 Start --><a href="\1\2" target="\3">\4</a><!-- SPCode u3 End -->';
	$patterns[3] = "#\[url=(.*?) target=(.*?)\](.*?)\[/url\]#si";
	$replacements[3] = '<!-- SPCode u3 Start --><a href="http://\1" target="\2">\3</a><!-- SPCode u3 End -->';
	$patterns[4] = "#\[url=([a-z]+?://){1}(.*?)\](.*?)\[/url\]#si";
	$replacements[4] = '<!-- SPCode u2 Start --><a href="\1\2" target="_blank">\3</a><!-- SPCode u2 End -->';
	$patterns[5] = "#\[url=(.*?)\](.*?)\[/url\]#si";
	$replacements[5] = '<!-- SPCode u2 Start --><a href="http://\1" target="_blank">\2</a><!-- SPCode u2 End -->';
	$patterns[6] = "#\[email\](.*?)\[/email\]#si";
	$replacements[6] = '<!-- SPCode e1 Start --><a href="mailto:\1">\1</a><!-- SPCode e1 End -->';
	$patterns[7] = "#\[email=(.*?)\](.*?)\[/email\]#si";
	$replacements[7] = '<!-- SPCode e2 Start --><a href="mailto:\1">\2</a><!-- SPCode e2 End -->';
	$patterns[8] = "#\[color=(.*?)\](.*?)\[/color\]#si";
	$replacements[8] = '<!-- SPCode color Start --><font color="\1">\2</font><!-- SPCode color End -->';
	$patterns[9] = "#\[size=(.*?)\](.*?)\[/size\]#si";
	$replacements[9] = '<!-- SPCode size Start --><font size="\1">\2</font><!-- SPCode size End -->';
	$patterns[10] = "#\[font=(.*?)\](.*?)\[/font\]#si";
	$replacements[10] = '<!-- SPCode font Start --><font face="\1">\2</font><!-- SPCode font End -->';
	$patterns[11] = "#\[align=(.*?)\](.*?)\[/align\]#si";
	$replacements[11] = '<!-- SPCode align Start --><p align="\1">\2</p><!-- SPCode align End -->';
	$patterns[12] = "#\[attach=(.*?)\](.*?)\[/attach\]#si";
	$replacements[12] = '<!-- SPCode attach Start --><a href="'.$url_simpnews.'/sndownload.php?entrynr=\1">\2</a><!-- SPCode attach End -->';
	$input = preg_replace($patterns, $replacements, $input);
	$input = substr($input, 1);
	return $input;

}

function bbdecode($input)
{
		$code_start_html = "#<!-- SPCode Start --><table border=0 align=center width=85%><tr><td><font size=-1>Code:</font><hr></td></tr><tr><td><font size=-1><pre>#si";
		$code_end_html = "#</pre></font></td></tr><tr><td><hr></td></tr></table><!-- SPCode End -->#si";
		$input = preg_replace($code_start_html, "[code]", $input);
		$input = preg_replace($code_end_html, "[/code]", $input);
		$quote_start_html = "#<!-- SPCode Quote Start --><table border=0 align=center width=85%><tr><td><font size=-1>Quote:</font><hr></td></tr><tr><td><font size=-1><blockquote>#si";
		$quote_end_html = "#</blockquote></font></td></tr><tr><td><hr></td></tr></table><!-- SPCode Quote End -->#si";
		$input = preg_replace($quote_start_html, "[quote]", $input);
		$input = preg_replace($quote_end_html, "[/quote]", $input);
		$input = preg_replace("#<!-- SPCode Start --><s>(.*?)</s><!-- SPCode End -->#si", "[s]\\1[/s]", $input);
		$input = preg_replace("#<!-- SPCode Start --><u>(.*?)</u><!-- SPCode End -->#si", "[u]\\1[/u]", $input);
		$input = preg_replace("#<!-- SPCode Start --><tt>(.*?)</tt><!-- SPCode End -->#si", "[tt]\\1[/tt]", $input);
		$input = preg_replace("#<!-- SPCode Start --><sub>(.*?)</sub><!-- SPCode End -->#si", "[sub]\\1[/sub]", $input);
		$input = preg_replace("#<!-- SPCode Start --><sup>(.*?)</sup><!-- SPCode End -->#si", "[sup]\\1[/sup]", $input);
		$input = preg_replace("#<!-- SPCode Start --><center>(.*?)</center><!-- SPCode End -->#si", "[center]\\1[/center]", $input);
		$input = preg_replace("#<!-- SPCode Start --><b>(.*?)</b><!-- SPCode End -->#si", "[b]\\1[/b]", $input);
		$input = preg_replace("#<!-- SPCode Start --><i>(.*?)</i><!-- SPCode End -->#si", "[i]\\1[/i]", $input);
		$input = preg_replace("#<!-- SPCode u3 Start --><a href=\"([a-z]+?://)(.*?)\" target=\"(.*?)\">(.*?)</a><!-- SPCode u3 End -->#si", "[url=\\1\\2 target=\\3]\\4[/url]", $input);
		$input = preg_replace("#<!-- SPCode u2 Start --><a href=\"([a-z]+?://)(.*?)\" target=\"_blank\">(.*?)</a><!-- SPCode u2 End -->#si", "[url=\\1\\2]\\3[/url]", $input);
		$input = preg_replace("#<!-- SPCode u1 Start --><a href=\"([a-z]+?://)(.*?)\" target=\"_blank\">(.*?)</a><!-- SPCode u1 End -->#si", "[url]\\3[/url]", $input);
		$input = preg_replace("#<!-- SPCode Start --><a href=\"mailto:(.*?)\">(.*?)</a><!-- SPCode End -->#si", "[email]\\1[/email]", $input);
		$input = preg_replace("#<!-- SPCode e1 Start --><a href=\"mailto:(.*?)\">(.*?)</a><!-- SPCode e1 End -->#si", "[email]\\1[/email]", $input);
		$input = preg_replace("#<!-- SPCode e2 Start --><a href=\"mailto:(.*?)\">(.*?)</a><!-- SPCode e2 End -->#si", "[email=\\1]\\2[/email]", $input);
		$input = preg_replace("#<!-- SPCode Start --><img src=\"(.*?)\" border=\"0\"><!-- SPCode End -->#si", "[img]\\1[/img]", $input);
		$input = preg_replace("#<!-- SPCode Start --><img align=\"(.*?)\" src=\"(.*?)\" border=\"0\"><!-- SPCode End -->#si", "[img align=\\1]\\2[/img]", $input);
		$input = preg_replace("#<!-- SPCode --><li>#si", "[*]", $input);
		$input = preg_replace("#<!-- SPCode ulist Start --><ul>#si", "[list]", $input);
		$input = preg_replace("#<!-- SPCode olist Start --><ol type=([A1])>#si", "[list=\\1]", $input);
		$input = preg_replace("#</ul><!-- SPCode ulist End -->#si", "[/list]", $input);
		$input = preg_replace("#</ol><!-- SPCode olist End -->#si", "[/list]", $input);
		$input = preg_replace("#<!-- SPCode color Start --><font color=\"(.*?)\">(.*?)</font><!-- SPCode color End -->#si", "[color=\\1]\\2[/color]", $input);
		$input = preg_replace("#<!-- SPCode size Start --><font size=\"(.*?)\">(.*?)</font><!-- SPCode size End -->#si", "[size=\\1]\\2[/size]", $input);
		$input = preg_replace("#<!-- SPCode font Start --><font face=\"(.*?)\">(.*?)</font><!-- SPCode font End -->#si", "[font=\\1]\\2[/font]", $input);
		$input = preg_replace("#<!-- SPCode align Start --><p align=\"(.*?)\">(.*?)</p><!-- SPCode align End -->#si", "[align=\\1]\\2[/align]", $input);
		$input = preg_replace("#<!-- SPCode attach Start --><a href=\"(.*?)entrynr=(.*?)\">(.*?)</a><!-- SPCode attach End -->#si", "[attach=\\2]\\3[/attach]", $input);
		return($input);
}

function bbencode_quote($input)
{
	if (!strpos(strtolower($input), "[quote]"))
	{
		return $input;
	}

	$stack = Array();
	$curr_pos = 1;
	while ($curr_pos && ($curr_pos < strlen($input)))
	{
		$curr_pos = strpos($input, "[", $curr_pos);

		if ($curr_pos)
		{
			$possible_start = substr($input, $curr_pos, 7);
			$possible_end = substr($input, $curr_pos, 8);
			if (strcasecmp("[quote]", $possible_start) == 0)
			{
				array_push($stack, $curr_pos);
				++$curr_pos;
			}
			else if (strcasecmp("[/quote]", $possible_end) == 0)
			{
				if (sizeof($stack) > 0)
				{
					$start_index = array_pop($stack);
					$before_start_tag = substr($input, 0, $start_index);
					$between_tags = substr($input, $start_index + 7, $curr_pos - $start_index - 7);
					$after_end_tag = substr($input, $curr_pos + 8);
					$input = $before_start_tag . "<!-- SPCode Quote Start --><table border=0 align=center width=85%><tr><td><font size=-1>Quote:</font><hr></td></tr><tr><td><font size=-1><blockquote>";
					$input .= $between_tags . "</blockquote></font></td></tr><tr><td><hr></td></tr></table><!-- SPCode Quote End -->";
					$input .= $after_end_tag;
					if (sizeof($stack) > 0)
					{
						$curr_pos = array_pop($stack);
						array_push($stack, $curr_pos);
						++$curr_pos;
					}
					else
					{
						$curr_pos = 1;
					}
				}
				else
				{
					++$curr_pos;
				}
			}
			else
			{
				++$curr_pos;
			}
		}
	}

	return $input;
}

function bbencode_code($input)
{
	if (!strpos(strtolower($input), "[code]"))
	{
		return $input;
	}

	$input = preg_replace("/\[([0-9]+?)code\]/si", "[#\\1code]", $input);
	$input = preg_replace("/\[\/code([0-9]+?)\]/si", "[/code#\\1]", $input);

	$stack = Array();
	$curr_pos = 1;
	$max_nesting_depth = 0;
	while ($curr_pos && ($curr_pos < strlen($input)))
	{
		$curr_pos = strpos($input, "[", $curr_pos);
		if ($curr_pos)
		{
			$possible_start = substr($input, $curr_pos, 6);
			$possible_end = substr($input, $curr_pos, 7);
			if (strcasecmp("[code]", $possible_start) == 0)
			{
				array_push($stack, $curr_pos);
				++$curr_pos;
			}
			else if (strcasecmp("[/code]", $possible_end) == 0)
			{
				if (sizeof($stack) > 0)
				{
					$curr_nesting_depth = sizeof($stack);
					$max_nesting_depth = ($curr_nesting_depth > $max_nesting_depth) ? $curr_nesting_depth : $max_nesting_depth;
					$start_index = array_pop($stack);
					$before_start_tag = substr($input, 0, $start_index);
					$between_tags = substr($input, $start_index + 6, $curr_pos - $start_index - 6);
					$after_end_tag = substr($input, $curr_pos + 7);
					$input = $before_start_tag . "[" . $curr_nesting_depth . "code]";
					$input .= $between_tags . "[/code" . $curr_nesting_depth . "]";
					$input .= $after_end_tag;
					if (sizeof($stack) > 0)
					{
						$curr_pos = array_pop($stack);
						array_push($stack, $curr_pos);
						++$curr_pos;
					}
					else
					{
						$curr_pos = 1;
					}
				}
				else
				{
					++$curr_pos;
				}
			}
			else
			{
				++$curr_pos;
			}
		}
	}

	if ($max_nesting_depth > 0)
	{
		for ($i = 1; $i <= $max_nesting_depth; ++$i)
		{
			$start_tag = escape_slashes(preg_quote("[" . $i . "code]"));
			$end_tag = escape_slashes(preg_quote("[/code" . $i . "]"));
			$match_count = preg_match_all("/$start_tag(.*?)$end_tag/si", $input, $matches);
			for ($j = 0; $j < $match_count; $j++)
			{
				$before_replace = escape_slashes(preg_quote($matches[1][$j]));
				$after_replace = $matches[1][$j];
				if($i < 2)
				{
					$after_replace = htmlspecialchars($after_replace);
				}
				$str_to_match = $start_tag . $before_replace . $end_tag;
				$input = preg_replace("/$str_to_match/si", "<!-- SPCode Start --><table border=0 align=center width=85%><tr><td><font size=-1>Code:</font><hr></td></tr><tr><td><font size=-1><pre>$after_replace</pre></font></td></tr><tr><td><hr></td></tr></table><!-- SPCode End -->", $input);
			}
		}
	}
	$input = preg_replace("/\[#([0-9]+?)code\]/si", "[\\1code]", $input);
	$input = preg_replace("/\[\/code#([0-9]+?)\]/si", "[/code\\1]", $input);
	return $input;
}

function bbencode_list($input)
{
	$start_length = Array();
	$start_length["ordered"] = 8;
	$start_length["unordered"] = 6;
	if (!strpos(strtolower($input), "[list"))
	{
		return $input;
	}
	$stack = Array();
	$curr_pos = 1;
	while ($curr_pos && ($curr_pos < strlen($input)))
	{
		$curr_pos = strpos($input, "[", $curr_pos);
		if ($curr_pos)
		{
			$possible_ordered_start = substr($input, $curr_pos, $start_length["ordered"]);
			$possible_unordered_start = substr($input, $curr_pos, $start_length["unordered"]);
			$possible_end = substr($input, $curr_pos, 7);
			if (strcasecmp("[list]", $possible_unordered_start) == 0)
			{
				array_push($stack, array($curr_pos, ""));
				++$curr_pos;
			}
			else if (preg_match("/\[list=([a1])\]/si", $possible_ordered_start, $matches))
			{
				array_push($stack, array($curr_pos, $matches[1]));
				++$curr_pos;
			}
			else if (strcasecmp("[/list]", $possible_end) == 0)
			{
				if (sizeof($stack) > 0)
				{
					$start = array_pop($stack);
					$start_index = $start[0];
					$start_char = $start[1];
					$is_ordered = ($start_char != "");
					$start_tag_length = ($is_ordered) ? $start_length["ordered"] : $start_length["unordered"];
					$before_start_tag = substr($input, 0, $start_index);
					$between_tags = substr($input, $start_index + $start_tag_length, $curr_pos - $start_index - $start_tag_length);
					$between_tags = str_replace("[*]", "<!-- SPCode --><li>", $between_tags);
					$after_end_tag = substr($input, $curr_pos + 7);
					if ($is_ordered)
					{
						$input = $before_start_tag . "<!-- SPCode olist Start --><ol type=" . $start_char . ">";
						$input .= $between_tags . "</ol><!-- SPCode olist End -->";
					}
					else
					{
						$input = $before_start_tag . "<!-- SPCode ulist Start --><ul>";
						$input .= $between_tags . "</ul><!-- SPCode ulist End -->";
					}
					$input .= $after_end_tag;
					if (sizeof($stack) > 0)
					{
						$a = array_pop($stack);
						$curr_pos = $a[0];
						array_push($stack, $a);
						++$curr_pos;
					}
					else
					{
						$curr_pos = 1;
					}
				}
				else
				{
					++$curr_pos;
				}
			}
			else
			{
				++$curr_pos;
			}
		}
	}
	return $input;
}

function format_bytes($size)
{
	if($size>(1024*1024))
		return (round($size/(1024*1024),1)." MB");
	if($size>1024)
		return (round($size/1024,1)." KBytes");
	return $size." Bytes";
}

function getRealFileExtension($filename)
{
	return ereg( ".([^\.]+)$", $filename, $r ) ? $r[1] : "";
}

function getRealFilename($filename)
{
	$tmpext=".".getRealFileExtension($filename);
	$tmpfilename=str_replace($tmpext,"",$filename);
	return($tmpfilename);
}

function isAllowedFileType($filename)
{
	global $tableprefix, $db, $prohibitnoregfiletypes;
	$fileext=".".getRealFileExtension($filename);
	$sql="select mime.* from ".$tableprefix."_mimetypes mime, ".$tableprefix."_fileextensions ext where mime.entrynr=ext.mimetype and ext.extension='$fileext'";
	if(!$result = mysql_query($sql, $db))
		die("Unable to connect to database (getUploadFileType)".mysql_error());
	if($myrow=mysql_fetch_array($result))
	{
		echo "Drinnen";
		if($myrow["noupload"]==1)
			return false;
		else
			return true;
	}
	else
	{
		echo "Hallo";
		if($prohibitnoregfiletypes==0)
			return true;
		else
			return false;
	}
}

function getUploadFileType($filename)
{
	global $tableprefix, $db;
	$fileext=".".getRealFileExtension($filename);
	$sql="select mime.* from ".$tableprefix."_mimetypes mime, ".$tableprefix."_fileextensions ext where mime.entrynr=ext.mimetype and ext.extension='$fileext'";
	if(!$result = mysql_query($sql, $db))
		die("Unable to connect to database (getUploadFileType)".mysql_error());
	if($myrow=mysql_fetch_array($result))
		return($myrow["mimetype"]);
	else
		return "application/octetstream";
}

function determine_weekday($currtimestamp, $weekstart)
{
	$workdate=getdate($currtimestamp);
	$wd=$workdate["wday"];
	if(($weekstart==1) && ($wd==0))
		$wd=7;
	return $wd;
}

function week_of_year($currtimestamp, $weekstart)
{
	if($weekstart==0)
		$formatstring="%U";
	else
		$formatstring="%W";
	$woy=strftime($formatstring,$currtimestamp)+1;
	if($woy<10)
		$woy="0".$woy;
	return $woy;
}

function recode_img_for_emails($input)
{
	global $url_gfx, $simpnewssitename;
	$input = preg_replace("#<IMG(.*?)SRC=\"http://".$simpnewssitename.$url_gfx."/(.*?)\"(.*?)>#si", "<img\\1src=\"\\2\"\\3>", $input);
	return $input;
}

function recode_emoticons_for_emails($input)
{
	global $url_gfx;
	$input = preg_replace("#<IMG(.*?)SRC=\"".$url_gfx."/(.*?)\">#s", "<img\\1src=\"\\2\">", $input);
	return $input;
}

function remove_spcode_markers($input)
{
	$input = str_replace("<!-- SPCode Start -->", "", $input);
	$input = str_replace("<!-- SPCode End -->", "", $input);
	return $input;
}

function undo_html_ampersand($input)
{
	$input = preg_replace("/&amp;/i", "&", $input);
	return $input;
}

function display_encoded($input)
{
	$input = undo_html_ampersand(do_htmlentities(stripslashes($input)));
	return $input;
}

function is_leacher($useragent)
{
	global $leacherprefix, $db;

	$sql="select * from ".$leacherprefix."_leachers";
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database (is_leacher).".mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		if(strstr ($useragent, $myrow["useragent"]))
			return true;
	}
	return false;
}

function highlight_words($text,$highlightwords)
{
	global $searchhighlightcolor, $searchhighlight;

	$words=explode("|",$highlightwords);
	$newtext=$text;
	$newtext=str_replace("&#","@@",$newtext);
	for($i=0;$i<count($words);$i++)
	{
		$words[$i]=base64_decode($words[$i]);
		$words[$i]=preg_quote(str_replace("&#","@@",$words[$i]));
		$newtext = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#(" . $words[$i] . ")#i', '<span style=\"color:$searchhighlightcolor\">\\\\1</span>', '\\0')", '>' . $newtext . '<'), 1, -1));
	}
	$newtext=str_replace("@@","&#",$newtext);
	return $newtext;
}

function wordwrap2($str, $cols, $cut="\n")
{
	$result="";
	$len = strlen($str);
	$tag = 0;
	$wordlen=0;
	for($i=0; $i<$len; $i++)
	{
		$chr = $str[$i];
		if($chr == '<')
		{
			$tag++;
		} elseif ($chr == '>')
		{
			$tag--;
		} elseif ((!$tag) && (ctype_space($chr)))
		{
			$wordlen=0;
		} elseif (!$tag)
		{
			$wordlen++;
		}
		if ((!$tag) && ($wordlen) && (!($wordlen % $cols)))
		{
			$chr .= $cut;
		}
		$result .= $chr;
	}
	return $result;
}

function is_daylight_savings($gmtime, $DSTStart = '', $DSTEnd = '')
{
	if(!$DSTStart || !$DSTEnd)
		return false;

	$DSTStart = split(":",$DSTStart);
	$DSTEnd = split(":",$DSTEnd);

	$gmtMonth = date("n",$gmtime);
	if ($gmtMonth < $DSTStart[2] || $gmtMonth > $DSTEnd[2])
		return false;
 	else if(($gmtMonth > $DSTStart[2]) && ($gmtMonth < $DSTEnd[2]))
		return true;
	else
	{
		if ($gmtMonth == $DSTStart[2])
		{
			$True = true;
			$week = $DSTStart[0];
			$ImportantDay = $DSTStart[1];
		}
		else
		{
			$True = false;
			$week = $DSTEnd[0];
			$ImportantDay = $DSTEnd[1];
		}
		$gmtDay = date("j",$gmtime);
		if(!$week)
		{
			$gmtDay=date("d",$gmtime);
			if($gmtDay>=$week)
				return($True);
			else
				return(!$True);
		}
		if ($week == 'L')
		{
			$week = 4;
			$ldom = 4;//last day of month factor
		}
		//if the week in which it starts/ends has not been reached
		if($gmtDay < ($week-1)*7)
			return (!$True);
		else
		{
			$gmtDate = getdate($gmtime);
			//go by a Day of the Week Basis
			for ($i=($week-1)*7;$i<(($week*7)+$ldom);$i++)
			{
				$checkDate = mktime(0,0,0,$gmtDate["mon"],$i,$gmtDate["year"]);
				//get the actual day it starts/ends
				if (date("D",$checkDate) == "Sun" && date("n",$checkDate) == $gmtMonth )
					$day = date("j",$checkDate);
			}
		}
		if ($gmtDay < $day)
			return (!$True);
		else
			return $True;
	}
}

function calctzoffset($offset_str)
{
	$sign = substr($offset_str, 0, 1);
	$hours = substr($offset_str, 1, 2);
	$mins = substr($offset_str, 3, 2);
	$secs = ((int)$hours * 3600) + ((int)$mins * 60);
	if ($sign == '-') $secs = 0 - $secs;
	return $secs;
}

function transposetime($origtime,$origtz,$desttz)
{
	global $timezones;

	$now=time();
	if(is_daylight_savings($now,$timezones[$origtz][3],$timezones[$origtz][4]))
		$origoffset=calctzoffset($timezones[$origtz][2]);
	else
		$origoffset=calctzoffset($timezones[$origtz][1]);
	$gmtime=$origtime-$origoffset;
	if(is_daylight_savings($now,$timezones[$desttz][3],$timezones[$desttz][4]))
		$destoffset=calctzoffset($timezones[$desttz][2]);
	else
		$destoffset=calctzoffset($timezones[$desttz][1]);
	$desttime=$gmtime+$destoffset;
	return $desttime;
}

function transposetimegmt($origtime,$origtz)
{
	global $timezones;

	$now=time();
	if(is_daylight_savings($now,$timezones[$origtz][3],$timezones[$origtz][4]))
		$origoffset=calctzoffset($timezones[$origtz][2]);
	else
		$origoffset=calctzoffset($timezones[$origtz][1]);
	$gmtime=$origtime-$origoffset;
	return $gmtime;
}

function tzgmtoffset($tz)
{
	global $timezones;

	$now=time();
	if(is_daylight_savings($now,$timezones[$tz][3],$timezones[$tz][4]))
		$offset=calctzoffset($timezones[$tz][2]);
	else
		$offset=calctzoffset($timezones[$tz][1]);
	if(substr(timezonename($tz),0,3)=="GMT")
		return("");
	$gmtoffset="GMT";
	if($offset!=0)
	{
		$offsethours=abs(floor($offset/60/60));
		$offsetmins=abs((abs($offset)-($offsethours*60*60))/60);
		if($offset<0)
			$gmtoffset.="-";
		else
			$gmtoffset.="+";
		$gmtoffset.=$offsethours;
		if($offsetmins>0)
			$gmtoffset.=sprintf("%02d",$offsetmins);
	}
	return $gmtoffset;
}

function timezonename($tz)
{
	global $timezones;

	$now=time();
	if(is_daylight_savings($now,$timezones[$tz][3],$timezones[$tz][4]))
		return ($timezones[$tz][6]);
	else
		return($timezones[$tz][5]);
}

function emailencode($email)
{
	$encodedemail="";
	for($i=0;$i<strlen($email);$i++)
	{
		$encodedemail.="&#".ord($email[$i]).";";
	}
	return $encodedemail;
}

function print_array($data)
{
	for($x=0;$x<count($data);$x++)
		echo " [".$data[$x]."]<br>";
}

function wml_encode($string)
{
	$newstr = '';
	$string=undo_htmlentities($string);
	for ($i=0; $i<strlen($string); $i++)
	{
		$j = substr($string, $i,1);
		$k = ord($j);
		if($j=="$")
			$newstr.="$$";
		else if($j=="&")
			$newstr.="&amp;";
		else
			$newstr .= (($k>127) && ($k<256)) ? '&#'.$k.';' : $j;
	}
	return($newstr);
}

function do_emaillog($success, $emailadr, $addtxt="")
{
	global $path_logfiles, $emaillog, $crlf;

	if($emaillog==0)
		return;
	if(($emaillog!=2) && $success)
		return;
	if($success)
		$logfilename=$path_logfiles."/email_ok.log";
	else
		$logfilename=$path_logfiles."/email_error.log";
	$logfile=@fopen($logfilename,"a");
	if(!$logfile)
		return;
	$logtxt="[".date("d.m.Y H:i:s")."] ";
	if($success)
		$logtxt.=" SUCCESS ";
	else
		$logtxt.=" FAILED ";
	$logtxt.=$emailadr;
	if($addtxt)
		$logtxt.=" (".$addtxt.")";
	$logtxt.=$crlf;
	@fwrite($logfile,$logtxt);
	@fclose($logfile);
}

function strcontains($haystack,$needles)
{
	for($i=0;$i<strlen($needles);$i++)
	{
		if(strpos($haystack,$needles[$i])>=0)
			return true;
	}
	return false;
}

function censor_bad_words($input, $tableprefix, $db) {
	$sql = "SELECT word, replacement FROM ".$tableprefix."_bad_words";
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to the database.");
	while($myrow = mysql_fetch_array($result))
	{
		$word = quotemeta(stripslashes($myrow["word"]));
		$replacement = stripslashes($myrow["replacement"]);
		$input = eregi_replace(" $word", " $replacement", $input);
		$input = eregi_replace("^$word", "$replacement", $input);
		$input = eregi_replace("$word", "$replacement", $input);
		$input = eregi_replace("<BR>$word", "<BR>$replacement", $input);
	}
	return($input);
}

/* please uncomment only if you have PHP4 <= 4.0.4 */
/*
function is_null($var)
{
	return($var==NULL);
}
*/
?>
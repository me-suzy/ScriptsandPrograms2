<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
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

function list_recode_ref($input, $actprog)
{
	global $db, $tableprefix, $act_lang;

	if(preg_match("#<!-- SPCode faqref Start --><A HREF=\"(.*?)nr=(.*?)&catnr=(.*?)&prog=(.*?)&(.*?)\" TARGET=\"_self\">(.*?)</A><!-- SPCode faqref End -->#s", $input,$matches))
		$progid=$matches[4];
	else
		return $input;
	$input = preg_replace("#<!-- SPCode faqref Start --><A HREF=\"(.*?)nr=(.*?)&catnr=(.*?)&prog=(.*?)&(.*?)\" TARGET=\"_self\">(.*?)</A><!-- SPCode faqref End -->#s", "[faqref faq=\\2 cat=\\3 prog=\\4]\\6[/faqref]", $input);
	if($progid!=$actprog)
		$input = preg_replace("#\[faqref faq=(.*?) cat=(.*?) prog=(.*?)\](.*?)\[/faqref\]#si", "<!-- SPCode faqref Start --><A HREF=\"{url_faqengine}/faq.php?{lang}&display=faq&faqnr=\\1&catnr=\\2&prog=\\3&onlynewfaq={onlynewfaq}\" TARGET=\"_self\">\\4</A><!-- SPCode faqref End -->", $input);
	else
		$input = preg_replace("#\[faqref faq=(.*?) cat=(.*?) prog=(.*?)\](.*?)\[/faqref\]#si", "<!-- SPCode faqref Start --><A HREF=\"#\\1\" TARGET=\"_self\">\\4</A><!-- SPCode faqref End -->", $input);
	return $input;
}

function language_select($default, $name="language", $dirname="language/", $class="")
{
	$dir = opendir($dirname);
	$lang_select = "<SELECT NAME=\"$name\"";
	if($class)
		$lang_select.=" class=\"$class\"";
	$lang_select.=">\n";
	while ($file = readdir($dir))
	{
		if (ereg("^lang_", $file))
		{
			$file = str_replace("lang_", "", $file);
			$file = str_replace(".php", "", $file);
			$file == $default ? $selected = " SELECTED" : $selected = "";
			$lang_select .= "  <OPTION value=\"$file\"$selected>$file\n";
		}
	}
	$lang_select .= "</SELECT>\n";
	closedir($dir);
	return $lang_select;
}

function language_list($dirname="language/")
{
	$langs = array();
	$dir = opendir($dirname);
	while($file = readdir($dir))
	{
		if (ereg("^lang_",$file))
		{
			$file = str_replace("lang_", "", $file);
			$file = str_replace(".php", "", $file);
			array_push($langs,$file);
		}
	}
	closedir($dir);
	return $langs;
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

function undo_html_ampersand($input)
{
	$input = preg_replace("/&amp;/i", "&", $input);
	return $input;
}

function undo_htmlspecialchars($input, $quotes=true)
{
	$input = preg_replace("/&gt;/i", ">", $input);
	$input = preg_replace("/&lt;/i", "<", $input);
	if($quotes)
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

function format_bytes($size)
{
	if($size>(1024*1024))
		return (round($size/(1024*1024),1)." MB");
	if($size>1024)
		return (round($size/1024,1)." KBytes");
	return $size." Bytes";
}

function get_summary($text, $maxTextLength)
{
	global $l_bbccode, $l_bbcquote;

	$summarytext=stripslashes($text);
	$summarytext = undo_htmlspecialchars($summarytext);
	$summarytext = strip_tags($summarytext);
	$summarytext = str_replace("{bbc_code}",$l_bbccode,$summarytext);
	$summarytext = str_replace("{bbc_quote}",$l_bbcquote,$summarytext);
	$summarytext = substr($summarytext,0,$maxTextLength);
	if(strlen($text)>$maxTextLength)
		$summarytext.="...";
	return $summarytext;
}

function is_phpfile($filename)
{
	global $php_fileext;
	$fileext=strrchr($filename,".");
	if(!$fileext)
		return false;
	$fileext=strtolower(substr($fileext,1));
	if(in_array($fileext,$php_fileext))
	{
		$fdat=get_file($filename);
		return true;
	}
	return false;
}

function file_output($filename)
{
	$filedata=get_file($filename);
	if($filedata)
	{
		$filedata=str_replace("</body>","",$filedata);
		$filedata=str_replace("</html>","",$filedata);
		echo $filedata;
	}
}

function read_headerfile($filename)
{
	$filedata=get_file($filename);
	if($filedata)
	{
		$filedata=str_replace("</body>","",$filedata);
		$filedata=str_replace("</html>","",$filedata);
		return $filedata;
	}
	return "";
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

function faqe_array_key_exists($searcharray, $searchkey)
{
	$arraykeys=array_keys($searcharray);
	for($i=0;$i<count($arraykeys);$i++)
	{
		if($arraykeys[$i]==$searchkey)
			return true;
	}
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

function is_ns6()
{
	global $HTTP_USER_AGENT;

	if (eregi("Netscape6",$HTTP_USER_AGENT))
		return true;
	return false;
}

function is_ns3()
{
	global $HTTP_USER_AGENT;

	if (ereg( 'MSIE.([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
		return false;
	if (ereg( 'Opera.([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
		return false;
	if (ereg( 'Mozilla/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
	{
		list($major,$minor)=explode(".",$log_version[1]);
		if($major=="3")
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
    elseif (ereg( 'Mozilla/([0-9].[0-9]{1,2})',$HTTP_USER_AGENT,$log_version))
    	return($log_version[1]);
	else
		return(0);
}

function is_in_array($needle,$haystack)
{
	if(count($haystack)<1)
		return false;
	for($i=0;$i<count($haystack);$i++)
	{
		if($needle==$haystack[$i])
			return true;
	}
	return false;
}

function determine_inc_filename($filename, $language)
{
	$seppos=strrpos($filename,".");
	if($seppos>0)
	{
		$fileext=substr($filename,$seppos);
		$tmpfilename=substr($filename,0,$seppos);
		$tmpfilename.="_".$language.$fileext;
		if(file_exists($tmpfilename))
			return $tmpfilename;
	}
	else
	{
		$tmpfilename=$filename."_".$language;
		if(file_exists($tmpfilename))
			return $tmpfilename;
	}
	if(file_exists($filename))
		return $filename;
	else
		return "";
}

function display_encoded($input)
{
	$input = undo_html_ampersand(do_htmlentities(stripslashes($input)));
	return $input;
}

function faqe_die_asc($text)
{
	global $faqeversion;

	echo $text;
	echo "<br>";
	echo "Powered by FAQEngine v$faqeversion (c) 2001-2005 Boesch IT-Consulting";
	exit;
}

function is_leacher($useragent)
{
	global $leacherprefix, $db;

	$sql="select * from ".$leacherprefix."_leachers";
	if(!$result = faqe_db_query($sql, $db))
	    die("Unable to connect to database (is_leacher).");
	while($myrow=faqe_db_fetch_array($result))
	{
		if(strstr ($useragent, $myrow["useragent"]))
			return true;
	}
	return false;
}

function search_highlight($text,$musts,$cans)
{
	global $searchhighlightcolor, $searchhighlight;

	if($searchhighlight==0)
		return $text;
	$newtext=$text;
	$newtext=str_replace("&#","@@",$newtext);
	for($i=0;$i<count($musts);$i++)
	{
		$searchword=preg_quote(str_replace("&#","@@",$musts[$i]));
		$newtext = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#(" . $searchword . ")#i', '<span style=\"color:$searchhighlightcolor\">\\\\1</span>', '\\0')", '>' . $newtext . '<'), 1, -1));
	}
	for($i=0;$i<count($cans);$i++)
		if(!in_array($cans[$i],$musts))
		{
			$searchword=preg_quote(str_replace("&#","@@",$cans[$i]));
			$newtext = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#(" . $searchword . ")#i', '<span style=\"color:$searchhighlightcolor\">\\\\1</span>', '\\0')", '>' . $newtext . '<'), 1, -1));
		}
	$newtext=str_replace("@@","&#",$newtext);
	return $newtext;
}

function addhighlights($linkdest, $musts, $cans)
{
	global $searchhighlight;

	if($searchhighlight==0)
		return $linkdest;
	$isfirst=true;
	$highlightwords="";
	for($i=0;$i<count($musts);$i++)
	{
		if(!$isfirst)
			$highlightwords.="|";
		else
			$isfirst=false;
		$highlightwords.=base64_encode(undo_htmlentities($musts[$i]));
	}
	for($i=0;$i<count($cans);$i++)
	{
		if(!in_array($cans[$i],$musts))
		{
			if(!$isfirst)
				$highlightwords.="|";
			else
				$isfirst=false;
			$highlightwords.=base64_encode(undo_htmlentities($cans[$i]));
		}
	}
	$linkdest.="&amp;highlight=".rawurlencode($highlightwords);
	return $linkdest;
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

function calctzoffset($offset_str)
{
	$sign = substr($offset_str, 0, 1);
	$hours = substr($offset_str, 1, 2);
	$mins = substr($offset_str, 3, 2);
	$secs = ((int)$hours * 3600) + ((int)$mins * 60);
	if ($sign == '-')
		$secs = 0 - $secs;
	return $secs;
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
			$ldom = 4;
		}
		if($gmtDay < ($week-1)*7)
			return (!$True);
		else
		{
			$gmtDate = getdate($gmtime);
			for ($i=($week-1)*7;$i<(($week*7)+$ldom);$i++)
			{
				$checkDate = mktime(0,0,0,$gmtDate["mon"],$i,$gmtDate["year"]);
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

function emailencode($email)
{
	$encodedemail="";
	for($i=0;$i<strlen($email);$i++)
	{
		$encodedemail.="&#".ord($email[$i]).";";
	}
	return $encodedemail;
}

function get_user_ip()
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

function db_die($text)
{
	$text.=" ";
	$text.=faqe_db_error();
	die($text);
}

function strtobool($string)
{
	$string=strtolower($string);
	$string=trim($string);
	if($string=="true")
		return true;
	else
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

function br2nl( $data )
{
	return preg_replace( '!<br.*>!iU', "\n", $data);
}
?>
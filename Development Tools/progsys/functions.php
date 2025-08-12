<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
/* Some handy bitvalues, easy to remember */
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

function language_select($default, $name="language", $dirname="language/"){
	$dir = opendir($dirname);
	$lang_select = "<SELECT NAME=\"$name\">\n";
	while ($file = readdir($dir)) {
		if (ereg("^lang_", $file)) {
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

function language_avail($checklang, $dirname="language/"){
	$dir = opendir($dirname);
	$langfound=false;
	while ($file = readdir($dir)) {
		if (ereg("^lang_", $file)) {
			$file = str_replace("lang_", "", $file);
			$file = str_replace(".php", "", $file);
			if($file == $checklang)
				$langfound=true;
		}
	}
	return $langfound;
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
	$trans = get_html_translation_table (HTML_ENTITIES);
	$trans = array_flip($trans);
	$input=strtr($input,$trans);
	return $input;
}

function validate_email($email)
{
	$email_regex="^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~ ])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~ ]+\\.)+[a-zA-Z]{2,4}\$";
	return(eregi($email_regex,$email)!=0);
}

function parse_template ($template, $variables)
{
	while ( list ($key,$val) = each ($variables) )
	{
		if (!(empty($key)))
		{
			if(gettype($val) != "string")
			{
				settype($val,"string");
			}

			$template = ereg_replace("\{$key\}",$val,$template);
		}
	}
	$template = ereg_replace("{([A-Z0-9_]+)}","",$template);
	return $template;
}

function forbidden_freemailer($email, $db)
{
	global $tableprefix;

	$sql="select * from ".$tableprefix."_freemailer";
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to the database.".mysql_error());
	if (!$myrow = mysql_fetch_array($result))
		return false;
	do{
		if(substr_count(strtolower($email), strtolower($myrow["address"]))>0)
			return true;
	} while($myrow = mysql_fetch_array($result));
	return false;
}

function ref_allowed()
{
	global $tableprefix, $db, $HTTP_REFERER;

	$refcheck=strtolower($HTTP_REFERER);
	$sql="select * from ".$tableprefix."_allowed_referers";
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to the database.".mysql_error());
	if (!$myrow = mysql_fetch_array($result))
		return false;
	do{
		$chkaddress=strtolower(stripslashes($myrow["address"]));
		if(strstr($refcheck, $chkaddress))
			return true;
	} while($myrow = mysql_fetch_array($result));
	return false;
}

function ref_forbidden()
{
	global $tableprefix, $db, $HTTP_REFERER;

	$refcheck=strtolower(stripslashes($HTTP_REFERER));
	$sql="select * from ".$tableprefix."_forbidden_referers";
	if(!$result = mysql_query($sql, $db))
	    die("Could not connect to the database.".mysql_error());
	if (!$myrow = mysql_fetch_array($result))
		return false;
	do{
		$chkaddress=strtolower(stripslashes($myrow["address"]));
		if(strstr($refcheck, $chkaddress))
			return true;
	} while($myrow = mysql_fetch_array($result));
	return false;

}
function bittst($bitfield, $bit)
{
        return ($bitfield & $bit);
}

function setbit($bitfield, $bit)
{
        $bitfield |= $bit;
        return($bitfield);
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
	if ($sign == '-') $secs = 0 - $secs;
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

function psys_array_key_exists($searcharray, $searchkey)
{
	$arraykeys=array_keys($searcharray);
	for($i=0;$i<count($arraykeys);$i++)
	{
		if($arraykeys[$i]==$searchkey)
			return true;
	}
	return false;
}
?>
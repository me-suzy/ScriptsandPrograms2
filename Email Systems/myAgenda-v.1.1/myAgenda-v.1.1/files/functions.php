<?php
#############################################################################
# myAgenda v1.1																#
# =============																#
# Copyright (C) 2002  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#																			#
# This program is free software. You can redistribute it and/or modify		#
# it under the terms of the GNU General Public License as published by 		#
# the Free Software Foundation; either version 2 of the License.       		#
#############################################################################
include("files/config.php");

mysql_pconnect($sql_host, $sql_user, $sql_pass) or die(mysql_error());
mysql_select_db($sql_db) or die(mysql_error());

if(file_exists("language/".strtolower($myAgenda_language).".inc.php"))
{
	include ("language/".strtolower($myAgenda_language).".inc.php");
}else{
	include ("language/default.inc.php");
}

function get_user($id)
{
	global $myAgenda_tbl_users;
	$s = mysql_query("Select name, surname From ".$myAgenda_tbl_users." Where uid = '".$id."'") or die (mysql_error());
	if(mysql_num_rows($s) != 0)
	{
		$r = mysql_fetch_array($s);
		$c = $r[name]." ".$r[surname];
	}

	return $c;
}

function create_sid() 
{
		srand ((double) microtime() * 1000000);
		return md5 (uniqid (rand()));
}

function email_check ($email) {
        return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email));
}


function get_location($variable)
{
	if(IsSet($variable))
	{
		$content = "?location=".$variable;
	}
	return $content;
}

function get_remindtype($id='', $option='0', $fieldname='RemindType')
{
	if($option == 1)
	{
		$c = $GLOBALS['strRemindTypes'][$id];
	}elseif($option == "0")
	{
		$c = "<Select Name = \"$fieldname\">\n";
		while(list($key, $val) = each($GLOBALS['strRemindTypes']))
		{
			$c .= "<option value=\"$key\" ".( $key==$id ? "Selected" : "" ).">$val\n";
		}
		$c .= "</Select>\n";
	}
	return $c;
}

function get_remindrepeat($id='', $option='0', $fieldname='RemindRepeat')
{
	if($option == 1)
	{
		$c = $GLOBALS['strRemindRepeates'][$id];
	}elseif($option == "0")
	{
		$c = "<Select Name = \"$fieldname\">\n";
		while(list($key, $val) = each($GLOBALS['strRemindRepeates']))
		{
			$c .= "<option value=\"$key\" ".( $key==$id ? "Selected" : "" ).">$val\n";
		}
		$c .= "</Select>\n";
	}
	return $c;
}

function get_remindday($id='', $option='0', $fieldname='RemindDay')
{
	if($option == 1)
	{
		$c = $GLOBALS['strRemindDays'][$id];
	}elseif($option == "0")
	{
		$c = "<Select Name = \"$fieldname\">\n";
		while(list($key, $val) = each($GLOBALS['strRemindDays']))
		{
			$c .= "<option value=\"$key\" ".( $key==$id ? "Selected" : "" ).">$val\n";
		}
		$c .= "</Select>\n";
	}
	return $c;
}

function get_notes($user, $month, $day, $year, $option='1')
{
	global $myAgenda_tbl_reminders;
	$time = mktime("","","",$month, $day, $year);
	$s = mysql_query("Select id, remindtype, remindnote From ".$myAgenda_tbl_reminders." Where date = '".$time."' And uid = '".$user."' Order By id") or die (mysql_error());
	if(mysql_num_rows($s) != 0)
	{
		if($option == 1)
		{
			$c = "<table width=\"90%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">\n";
			while ($r = mysql_fetch_array($s)) 
			{
				$c .= "<tr>\n";
				$c .= "<td width=\"30%\"><font class=\"small\"><a href=\"agenda_edit.php?id=".$r[id]."\">".get_remindtype($r[remindtype],1)."</a></font></td>\n";
				$c .= "<td width=\"70%\"><font class=\"small\"><a href=\"agenda_edit.php?id=".$r[id]."\">".substr(StripSlashes($r[remindnote]),0,25)."</a></font></td>\n";
				$c .= "</tr>\n";
			}
			$c .= "</table>\n";
		}elseif($option == 2)
		{
			$c = "<font color=\"#FF0000\">*</font>";
		}
	}
	return $c;
}

function get_all_reminders($uid, $order='Desc')
{
	global $myAgenda_tbl_reminders;
	$s = mysql_query("Select id, remindtype, remindnote From ".$myAgenda_tbl_reminders." Where uid = '".$uid."' Order By date ".$order." ") or die (mysql_error());
	if(mysql_num_rows($s) != 0)
	{
		$c = "<table width=\"95%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">\n";
		while ($r = mysql_fetch_array($s)) 
		{
			$c .= "<tr>\n";
			$c .= "<td width=\"40%\"><font class=\"small\"><a href=\"agenda_edit.php?id=".$r[id]."\">".get_remindtype($r[remindtype],1)."</a></font></td>\n";
			$c .= "<td width=\"60%\"><font class=\"small\"><a href=\"agenda_edit.php?id=".$r[id]."\">".substr(StripSlashes($r[remindnote]),0,25)."</a></font></td>\n";
			$c .= "</tr>\n";
		}
		$c .= "</table>\n";
	}
	return $c;
}
?>
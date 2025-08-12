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
include("files/functions.php");

if(!isset($PHP_AUTH_USER))
{
	Header("WWW-Authenticate: Basic realm=\"Enter your username and password\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "Authentication Failed\n";
    exit;
}
	if ($PHP_AUTH_USER==$auth_us && $PHP_AUTH_PW == $auth_ps)
	{

$today = mktime("","","", date("m"), date("d"), date("Y") );

$s = mysql_query("Select * From ".$myAgenda_tbl_reminders." Where reminddate = '".$today."'");

	if(mysql_num_rows($s) != 0)
	{
		while ($r = mysql_fetch_array($s))
		{
			$gms = mysql_query("Select uid, name, surname, email From ".$myAgenda_tbl_users." Where uid = '".$r[uid]."'");
			$gms = mysql_fetch_array($gms);
			$email = $gms[email];
			$name = $gms[name];
			$surname = $gms[surname];
			if ($r[remindrepeat] == 1)
			{
				mysql_query("Delete From ".$myAgenda_tbl_reminders." Where id = '".$r[id]."' And uid = '".$gms[uid]."'");

			}else{

				switch ($r[remindrepeat])
				{
					case 2 : $next_reminddate = mktime("","","",date("m"),date("d")+1,date("Y") );	break;
					case 3 : $next_reminddate = mktime("","","",date("m"),date("d")+7,date("Y") );	break;
					case 4 : $next_reminddate = mktime("","","",date("m")+1,date("d"),date("Y") );	break;
					case 5 : $next_reminddate = mktime("","","",date("m"),date("d"),date("Y")+1 );	break;
				}
				$new_remind_message = $GLOBALS['strMailNextRemindDate'] . " : " . date($GLOBALS['date_format'], $next_reminddate) . "\n\n";
				echo $GLOBALS['strMailNextRemindDate'] . " : " . date($GLOBALS['date_format'], $next_reminddate); // for cron log

				mysql_query("Update ".$myAgenda_tbl_reminders." Set 
							reminddate = '".$next_reminddate."' 
							Where 
							id = '".$r[id]."' And uid = '".$gms[uid]."'
							");
			}
				$message = str_replace("{name}", $name." ".$surname, $GLOBALS['strMailHeader'])."\n\n";
				$message .= $GLOBALS['strReminderDate'] . " : " . date($GLOBALS['date_format'], $r[date]) . "\n";
				$message .= $GLOBALS['strReminderNote'] . " : " . StripSlashes($r[remindnote]) . "\n\n";
				$message .= $new_remind_message;
				$message .= str_replace("{programname}", $myAgenda_name, $GLOBALS['strMailFooter']);

				mail ($email, get_remindtype($r[remindtype],1), $message, $myAgenda_email_from );
				echo "|" . $GLOBALS['strMailReminderSent'] . " : " . $email . "\n"; // for cron log
		}
	}

	exit;
	}
		Header("WWW-authenticate: Basic realm=\"Enter your username and password\"");
		Header("HTTP/1.0  401  Unauthorized");
	    echo "Authentication Failed\n";
	exit;
?>

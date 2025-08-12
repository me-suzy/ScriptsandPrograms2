<?php
	$dirpath = "$Config_rootdir"."../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();

    	if ( !$ucook->LoggedIn() )
      {
          $usr->HeaderOut();
	    $csr->customMessage( 'logout' );
	    $usr->FooterOut();
   
          exit;
      }

	if($newevent)
	{ $newevent = strip_tags($newevent); }
	if($newmessage)
	{ $newmessage = strip_tags($newmessage, '<b><i>'); }

	if(!$newevent)
  	{
	 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strRemindErr1, <a href=\"javascript:history.back(1);\">retry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
      }

	$msglen = strlen($newmessage);
	if($msglen > $Config_remind_msg_max)
  	{
	 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
	 $strReminderMsgError = $csr->LangConvert($strReminderMsgError, $msglen, $Config_remind_msg_max);
       $errMsg = "<b>$strReminderMsgError <a href=\"javascript:history.back(1);\">$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
   	 $usr->Footer();
	 exit;
      }

	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row_user = mysql_fetch_array( $result_user );

	if(preg_match("/R/", $row_user[prefs]))
	$privlevel = "1";
	else
	$privlevel = "0";
	
	if($send_month == "0" && $privlevel != "1")
	{
	 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strReminderAccess ~ <a href=javascript:history.back(1)>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '70' );
	 echo("<BR>");
   	 $usr->Footer();

	 closeDB();
	 exit;
	}

	$today = getdate(); 
	$currdate = date("Ymd");

	if($send_month == "0" && $send_year == $today['year'] && $today['mon'] == "12")
	{
	if($send_date < 10)
	$calc_date = "0$send_date";
	else
	$calc_date = "$send_date";

	$givendate = "$send_year"."12"."$calc_date";

	if($currdate > $givendate)
	{
	 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strInvalidDate, <a href=\"javascript:history.back(1);\">$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
	}	
	}

	if($send_month != "0" && $send_year == "0")
	{
	if($send_date < 10)
	$calc_date = "0$send_date";
	else
	$calc_date = "$send_date";
	
	if($send_month < 10 && $send_month != 0)
	$calc_month = "0$send_month";
	else
	$calc_month = "$send_month";
	
	$calc_year = $today['year'];

	if(!checkdate($calc_month, $calc_date, $calc_year))
	{
	 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strInvalidDate, <a href=\"javascript:history.back(1);\">$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
	}
	}

	else if($send_month != "0" && $send_year != "0")
	{
	if($send_date < 10)
	$calc_date = "0$send_date";
	else
	$calc_date = "$send_date";
	
	if($send_month < 10 && $send_month != 0)
	$calc_month = "0$send_month";
	else
	$calc_month = "$send_month";
	
	$calc_year = $send_year;

	$givendate = "$calc_year"."$calc_month"."$calc_date";

	if($currdate > $givendate || !checkdate($calc_month, $calc_date, $calc_year))
	{
	 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strInvalidDate, <a href=\"javascript:history.back(1);\">$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
	}
	}

	$result = queryDB( "SELECT * FROM $tbl_reminders WHERE uid = '$uid' && event = '$newevent'" );
	$nr = mysql_num_rows( $result );
	if($nr)
  	{
	 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strRemindErr2, <a href=\"javascript:history.back(1);\">$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);


	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row = mysql_fetch_array($result);
	list($slimit, $alimit, $plimit, $rlimit) = split('[|]', $row[limits]);
	mysql_free_result( $result );

	$result = queryDB( "SELECT * FROM $tbl_reminders WHERE uid = '$uid'" );
	$nr = mysql_num_rows( $result );

	if($nr >= $rlimit && $rlimit != "0")
	{
	 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
	 $errMsg = "<b>".$csr->LangConvert($strCrossLimit, strtolower($strMenusReminders))."</b> [<a href=\"$Config_buylink\">$strBuySentence</a>] or <a href=javascript:history.back(1)>$strBack</a>...</b><br><br>\n";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
	 echo ("<br>");
   	 $usr->Footer();

	 closeDB();
	 exit;
	}
 	mysql_free_result($result);

	if($send_month != "0" && $send_year != "0")
	{
	if($send_month < 10 && $send_month != 0)
	$calc_month = "0$send_month";
	else
	$calc_month = "$send_month";

	$calc_year = $send_year;

	if($send_date < 10)
	$calc_date = "0$send_date";
	else
	$calc_date = "$send_date";
	
	$givendate = "$calc_year"."$calc_month"."$calc_date";

	if($whento == "2")
	{
		if($currdate == $givendate)
		{
		 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
	       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
	       $errMsg = "<b>$strReminderSameDate <a href=\"javascript:history.back(1);\">$strBack</a>...</b><br><br>\n";
	       $usr->errMessage( $errMsg, $strError, 'error', '70' );
		 echo ("<br>");
	   	 $usr->Footer();

		 closeDB();
		 exit;
		}

		$givendate--;

		if($currdate == $givendate)
		{
		 $errMsgAdd = $strReminderDayEarlier;
		}

	}

	else if($whento == "1")
	{
		if($currdate == $givendate)
		{
		 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
	       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
	       $errMsg = "<b>$strReminderSameDate <a href=\"javascript:history.back(1);\">$strBack</a>...</b><br><br>\n";
	       $usr->errMessage( $errMsg, $strError, 'error', '70' );
		 echo ("<br>");
	   	 $usr->Footer();

		 closeDB();
		 exit;
		}
	}

	else if($whento == "3")
	{
		if($currdate == $givendate)
		{
		 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
	       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
	       $errMsg = "<b>$strReminderSameDate <a href=\"javascript:history.back(1);\">$strBack</a>...</b><br><br>\n";
	       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
		 echo ("<br>");
	   	 $usr->Footer();

		 closeDB();
		 exit;
		}

		$givendate--;

		if($currdate == $givendate)
		{
		 $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
	       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
	       $errMsg = "<b>$strReminderSameDate <a href=\"javascript:history.back(1);\">$strBack</a>...</b><br><br>\n";
	       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
		 echo ("<br>");
	   	 $usr->Footer();

		 closeDB();
		 exit;
		}
	}
    }

	if($send_month < 10 && $send_month != 0)
	$send_month = "0$send_month";
	if($send_date < 10)
	$send_date = "0$send_date";

	$result = queryDB( "INSERT INTO $tbl_reminders VALUES(NULL, '$uid', '".addslashes(htmlspecialchars($newevent))."', '".addslashes(htmlspecialchars($newmessage))."', '$whento', '$send_year', '$send_month', '$send_date')" );


	closeDB();
     
	if($errMsgAdd)
	$delay = 15;
	else
	$delay = 1;

	$usr->Header($Config_SiteTitle ." :: $strMenusReminders", $delay, 'remind.php');
	echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
	$errMsg = "<b>$errMsgAdd $strCreated, $strRedirecting...</b><br>$strElse <a href=\"remind.php\">$strClickhere</a>\n";
	$usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
      $usr->Footer();

?>
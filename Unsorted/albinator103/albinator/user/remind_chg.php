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

if($dowhat == "edit" && $chg != "1")
{
	$usr->Header($Config_SiteTitle ." :: $strMenusReminders");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div>");

$today = getdate(); 

if($remind_id)
$result = queryDB( "SELECT * FROM $tbl_reminders WHERE uid = '$uid' && rid='$remind_id'" );
else
$result = queryDB( "SELECT * FROM $tbl_reminders WHERE uid = '$uid'" );

$nr_res = mysql_num_rows( $result );

if($nr_res < 1)
{

	if($remind_id)
	{
	    $errMsg = "<b>$strReminderExpired</b><br><br>\n";
	    $usr->errMessage( $errMsg, $strError, 'error', '70' );
	    echo("<BR>");
	    $usr->Footer();
	    exit;
	}

	else
	{
	    $errMsg = "<b>$strReminderNo</b>\n";
	    $usr->errMessage( $errMsg, $strNote );
	    echo("<BR>");
	    $usr->Footer();
	    exit;
	}

}

$result_temp = queryDB( "SELECT * FROM $tbl_reminders WHERE uid = '$uid'" );
$nr_res = mysql_num_rows( $result_temp );

if($nr_res > 1 || $nr_res == 0)
$s = $strPuralS;

$strTotalInfo = $csr->LangConvert($strTotalInfo, $nr_res, $strMenusReminders);

echo ("<p>&nbsp;</p><div align=center class=tn>$strTotalInfo, [<a href=remind.php>$strReminderAdd</a>] [<a href=remind.php#list>$strReminderList2</a>]<br></div>");

	while($row = mysql_fetch_array( $result ))
	{
	$chk_a_a = "";
	$chk_a_b = "";
	$chk_a_c = "";
	$chk_b_a = "";
	$chk_b_b = "";
	$chk_b_c = "";

	if($row[estatus] == "1")
	{ $chk_a_a = "checked"; }
	else if($row[estatus] == "2")
	{ $chk_a_c = "checked"; }
	else if($row[estatus] == "3")
	{ $chk_a_b = "checked"; }

?>

<form method=post action=remind_chg.php>
  <table width="90%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#006699" class=tn>
    <tr> 
      <td bgcolor="#dddddd" width="25%" class=tn>&nbsp;<?php echo $strEvent ?> [<a href=remind_chg.php?dowhat=delete&rid=<?php echo $row[rid] ?>&sfrom=<?php echo $sfrom ?>><?php echo $strDelete ?></a>]</td>
      <td bgcolor="#CCCCCC" width=65%>&nbsp;<input type="text" name="event" maxlength=99 size="30" class=fieldsa value="<?php echo (stripslashes($row[event])); ?>"><span class=ts> <?php echo $strReminderEventExample ?></span></td>
    </tr>
     <tr> 
      <td bgcolor="#dddddd" width="25%" class=tn valign=top>&nbsp;<?php echo $strReminderMessage ?><br>&nbsp;<span class=ts><?php echo ($csr->LangConvert($strReminderMessageLength, $Config_remind_msg_max)); ?></span></td>
      <td width=65% bgcolor="#CCCCCC" class=tn>&nbsp;<textarea name="message" rows=5 cols="30" class=fieldsa><?php echo (stripslashes($row[message])); ?></textarea></td>
	</tr>
    <tr> 
      <td bgcolor="#dddddd" width="25%" class=tn>&nbsp;<?php echo $strDate ?> </td>
      <td bgcolor="#CCCCCC" width=65%>&nbsp;
    <select name="send_year">
    <option value="0" <?php if($row[date_year] == '0') echo "selected"; ?>><?php echo $strReminderEveryYear ?></option>
<?php
	$today_year = $today['year'];
	if($row[date_year] == $today_year) $val="selected"; else $val="";
	echo("      <option value=\"$today_year\" $val>$today_year</option>\n");
	$today_year++;
	if($row[date_year] == $today_year) $val="selected"; else $val="";
	echo("      <option value=\"".$today_year."\" $val>".$today_year."</option>\n");

?>
    </select>
      <select name="send_month">
      <option value="0" <?php if($row[date_month] == '0') echo "selected"; ?>><?php echo $strReminderEveryMonth ?></option>
<?php
	$today_month = $today['mon'];

	for($i=1;$i<=12;$i++)
	{
	if($row[date_month] == $i)
	echo("      <option value=\"$i\" selected>$date_show[$i]</option>\n");
	else
	echo("      <option value=\"$i\">$date_show[$i]</option>\n");
	}

?>
      </select>
      <select name="send_date">
<?php
	$today_date = $today['mday'];

	for($i=1;$i<=31;$i++)
	{
	if($row[date_day] == $i)
	echo("      <option value=\"$i\" selected>$i</option>\n");
	else
	echo("      <option value=\"$i\">$i</option>\n");
	}

?>
      </select>
	</td>
    </tr>
    <tr> 
      <td bgcolor="#dddddd" width="25%" class=tn>&nbsp;<?php echo $strReminderWhen ?> </td>
      <td bgcolor="#CCCCCC" width=65%>&nbsp;<input type="radio" name="whento" value=1 <?php echo $chk_a_a ?>> <?php echo $strReminderWhenOpt1 ?>&nbsp;<input type="radio" name="whento" value=3 <?php echo $chk_a_b ?>> <?php echo $strReminderWhenOpt2 ?>&nbsp;<input type="radio" name="whento" value=2 <?php echo $chk_a_c ?>> <?php echo $strReminderWhenOpt3 ?></td>
    </tr>
    <tr>
      <td colspan=2 align=right>
      <input type="hidden" name="chg" value="1">
      <input type="hidden" name="dowhat" value="edit">
      <input type="hidden" name="remind_id" value="<?php echo $remind_id ?>">
      <input type="hidden" name="rid" value="<?php echo $row[rid] ?>">&nbsp;
      <input type="submit" name="Submit" value="<?php echo $strChange ?> &gt;&gt;">&nbsp;
      </td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>

<?php 
	}
}

else if($HTTP_POST_VARS["dowhat"] == "edit" && $chg == 1)
{
	if($event)
	{ $event = strip_tags($event); }
	if($message)
	{ $message = strip_tags($message, '<b><i>'); }

	if(!$event)
	{
       $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strRemindErr1, <a href=\"javascript:history.back(1);\">$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 exit;
	}

	$result_temp = queryDB( "SELECT * FROM $tbl_reminders WHERE uid = '$uid' && event = '$event' && rid != '$rid'" );
	$nr_temp = mysql_num_rows( $result_temp );
	if($nr_temp)
  	{
       $usr->Header($Config_SiteTitle ." :: $strMenusReminders");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strRemindErr2, <a href=\"javascript:history.back(1);\">$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result_temp);

	$msglen = strlen($message);
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
       $errMsg = "<b>$strInvalidDate, <a href=\"javascript:history.back(1);\">$strRetry</a>...</b>\n";
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

	#$givendate = "$calc_year"."$calc_month"."$calc_date";

	if(!checkdate($calc_month, $calc_date, $calc_year))
	{
       $usr->Header($Config_SiteTitle .' :: Reminder Updation');
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strInvalidDate, <a href=\"javascript:history.back(1);\">$strRetry</a>...</b>\n";
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
       $errMsg = "<b>$strInvalidDate, <a href=\"javascript:history.back(1);\">$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
	}
	}


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
	       $errMsg = "<b>$strReminderSameDate <a href=\"javascript:history.back(1);\">$strBack</a></b><br><br>\n";
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
	       $errMsg = "<b>$strReminderDayEarlier <a href=\"javascript:history.back(1);\">$strBack</a></b><br><br>\n";
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
	       $errMsg = "<b>$strReminderSameDate <a href=\"javascript:history.back(1);\">$strBack</a></b><br><br>\n";
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

	$result = queryDB( "UPDATE $tbl_reminders SET event='".addslashes(htmlspecialchars($event))."', message='".addslashes(htmlspecialchars($message))."', estatus='$whento', date_year='$send_year', date_month='$send_month', date_day='$send_date' WHERE rid='$rid'" );

	    closeDB();
     
          if($errMsgAdd)
	    $delay = 15;
	    else
	    $delay = 1;

	    $usr->Header($Config_SiteTitle ." :: $strMenusReminders", $delay, "remind.php#list");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strUpdated, $strRedirecting...</b><br>$strElse <a href=\"remind.php#list\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
          $usr->Footer();
	    echo("<BR>");
	    exit;
}

else if($dowhat == "delete" && $done != "1")
{
	$usr->Header($Config_SiteTitle ." :: $strMenusReminders");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div>");
      $errMsg = "<b>".$csr->LangConvert($strDelConfirm, "$strMenusReminders")." <a href=remind_chg.php?dowhat=delete&done=1&rid=$rid&sfrom=$sfrom>$strYes</a> :: <a href=javascript:history.back(1)>$strNo</a>\n";
      $usr->errMessage( $errMsg, $strWarning, 'tick', '70' );
      $usr->Footer();
	echo("<BR>");

      closeDB();

	exit;
}

else if($dowhat == "delete" && $done = "1")
{
	$result = queryDB( "DELETE FROM $tbl_reminders WHERE rid='$rid'" );

	if($sfrom)
	$redirect_to = "remind.php#list";
	else
	$redirect_to = "remind.php#list";


	$usr->Header($Config_SiteTitle ." :: $strMenusReminders", "1", "$redirect_to");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div>");
      $errMsg = "<b>$strRemindErr3, $strRedirecting...</b><br>else <a href=\"$redirect_to\">$strClickhere</a>\n";
      $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	echo("<BR>");
      $usr->Footer();

      closeDB();

	exit;
}

else
{
	$usr->Header($Config_SiteTitle ." :: $strMenusReminders");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/reminders.gif>&nbsp;</div>");
      $errMsg = "<b>$strInvalid $strData, <a href=\"remind_chg.php?dowhat=edit\">$strRetry</a>\n";
      $usr->errMessage( $errMsg, $strError, 'error', '70' );
}

closeDB();
$usr->Footer(); 

?>
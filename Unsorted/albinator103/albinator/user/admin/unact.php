<?php
	$dirpath = "$Config_rootdir"."../../";
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

 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid' && admin !='0'" );
	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
	 if($Config_makelogs == "1")
	 { $csr->MakeAdminLogs( $uid, "Denid Access to the Admin Panel :: $SCRIPT_NAME", "2"); }

       $usr->Header($Config_SiteTitle ." :: $strAdminstration");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $csr->customMessage( 'noadmin' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

	mysql_free_result( $result );	

if($dowhat == "show")
{    
	$rs = new PagedResultSet("SELECT * FROM $tbl_userinfo WHERE status='0'",$page_maker);
	$nr  = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("dowhat=$dowhat");

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUnactUsr");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/unact.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strNo $strAMenusUnactUsr</b>\n";
       $usr->errMessage( $errMsg, '', 'error' );
	 echo("<BR>");
   	 $usr->Footer();

	 closeDB();
	 exit;
	}
	
      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUnactUsr");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/unact.gif>&nbsp;</div><br>");
	echo("\n<p>&nbsp;</p><div align=center>$nr $strUser($strPuralS)<br><span class='ts'>$nav</span><br><br>
	<table width=60% align=center cellpadding=4 cellspacing=0 border=0>\n");

	$i = 0;

	while($row = $rs->fetchArray())
	{
		if($i == 1)
		{ $i=0; $rowcolor = "#dddddd"; }
		else
		{ $i++; $rowcolor = "#eeeeee"; }

	 	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$row[uid]'" );
		$row_user = mysql_fetch_array( $result_user );
?>

<tr bgcolor=<?php echo $rowcolor ?>>
<td width=50% class=tn><a href=usrmngt.php?username=<?php echo $row[uid] ?>&dowhat=show class=noundertn><?php echo $row_user[uname] ?>, (<?php echo $row[uid] ?>)</a></td>
<td class=ts align=right>[<a href='usrmngt.php?username=<?php echo $row[uid] ?>&dowhat=del'><?php echo $strDelete ?></a>] [<a href=unact.php?dowhat=activate&username=<?php echo $row[uid] ?>><?php echo $strActivate ?></a>] [<a href=unact.php?dowhat=code&username=<?php echo $row[uid] ?>><?php echo $strSendCode ?></a>]</td>
</tr>

<?php
	}			

	echo("\n</table></div>\n<p>&nbsp;</p>\n");


}

else if($dowhat == "activate")
{
	 $adddate = date("Ymd");

  	 $result_del = queryDB( "DELETE FROM $tbl_userwait WHERE uid = '$username'" );
	 $result_update = queryDB( "UPDATE $tbl_userinfo SET status = '1', adddate='$adddate' WHERE uid = '$username'" );

	 error_reporting(0);
	 mkdir ("$dirpath"."$Config_datapath/$username", 0777);
	 error_reporting(E_ERROR | E_WARNING);

	 if($Config_makelogs == "1")
	 $csr->MakeAdminLogs( $uid, "$username Activated", "2");

  	 $result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$username'" );
	 $row_user = mysql_fetch_array( $result_user );

$name = "$Config_sitename";
$email = "$Config_adminmail";
$recnameto = $row_user[uname];
$recemailto = $row_user[email];
$Config_sitename_url = "$Config_mainurl";
$Config_sitename_url_code = "$Config_mainurl/";
$subject = $strRegisterMail1a;

$premessage = $csr->LangConvert($strAdminUnactMail1, $Config_sitename, $Config_site_msg);

$endmessage = "$Config_msgfooter";
$sendmessage = "$premessage $endmessage";

$mailheader = "From: $name <$email>\nX-Mailer: $strRegisterMail1a\nContent-Type: text/plain";
mail("$recemailto","$subject","$sendmessage","$mailheader");

if($sendurl == "usrmngt")
$sendurl = "usrmngt.php?dowhat=all";
else
$sendurl = "unact.php?dowhat=show";


	if($Config_makelogs == "1")
	{ $csr->MakeAdminLogs( $uid, "Confirmation Mail sent to $username", "2"); }

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUnactUsr", '1', "$sendurl");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/unact.gif>&nbsp;</div><br>");
	$errMsg = "<b>$strRegisterMail1a, $strRedirecting...</b><br>$strElse <a href=\"$sendurl\">$strClickhere</a>\n";
	$usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	echo("<BR>");
      $usr->Footer();
	exit;
}


else if($dowhat == "code")
{
  	 $result = queryDB( "SELECT * FROM $tbl_userwait WHERE uid = '$username'" );
	 $row = mysql_fetch_array( $result );

	 if(!$row[0])
	 {
 	 $result = queryDB( "SELECT * FROM $tbl_userinfo WHERE status='0' && uid='$username'" );
	 $row = mysql_fetch_array( $result );

	 if(!$row[0])
	 {
          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUnactUsr");
          echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/unact.gif>&nbsp;</div><br>");
	    $errMsg = "<b>User is already activated</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
	 }

		$code_conf = md5(uniqid ($Config_p));
		$code_conf = substr($code_conf, 0, 10);

	      $result_addunact = queryDB("INSERT INTO $tbl_userwait VALUES('$username', '$code_conf', '$now_date')");

  	 	$result = queryDB( "SELECT * FROM $tbl_userwait WHERE uid = '$username'" );
	 	$row = mysql_fetch_array( $result );
	 }

  	 $result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$username'" );
	 $row_user = mysql_fetch_array( $result_user );


$name = "$Config_sitename";
$email = "$Config_adminmail";
$recnameto = $row_user[uname];
$recemailto = $row_user[email];
$Config_sitename_url = "$Config_mainurl";
$Config_sitename_url_code = "$Config_mainurl/confirm.php?uuid=$username&code=$row[code]";
$subject = $strRegisterMail1;

$premessage = $csr->LangConvert($strRegisterMail2, $Config_sitename, $Config_sitename_url_code, $Config_unact_days, $putmsg);
$endmessage = "$Config_msgfooter";
$sendmessage = "$premessage $endmessage";

$mailheader = "From: $name <$email>\nX-Mailer: $strRegisterMail1\nContent-Type: text/plain";
mail("$recemailto","$subject","$sendmessage","$mailheader");

	if($Config_makelogs == "1")
	$csr->MakeAdminLogs( $uid, "Confirmation Mail sent to $username", "2");

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUnactUsr", '1', "unact.php?dowhat=show");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/unact.gif>&nbsp;</div><br>");
	$errMsg = "<b>$strSent, $strRedirecting...</b><br>$strElse <a href=\"unact.php?dowhat=show\">$strClickhere</a>\n";
	$usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	echo("<BR>");
      $usr->Footer();
	exit;
}

else if($dowhat == "senddet")
{
  	 $result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$username'" );
	 $row_user = mysql_fetch_array( $result_user );

		 srand((double)microtime()*100);
		 $randpass = rand();
		 $randpass = crypt ($randpass, $Config_p);
		 $randpass = ereg_replace ("/", "", $randpass);
		 $randpass = ereg_replace ('\.', "", $randpass);

		 $enc_newpassword = md5($randpass);
		 $result = queryDB( "UPDATE $tbl_userinfo SET password = '$enc_newpassword' WHERE uid = '$username'" );


$name = "$Config_sitename";
$email = "$Config_adminmail";
$recnameto = $row_user[uname];
$recemailto = $row_user[email];
$Config_sitename_url = "$Config_mainurl";
$Config_sitename_url_code = "$Config_mainurl/login.php";
$subject = $strRegisterMail1;

$premessage = $csr->LangConvert($strAdminUnactMail2, $Config_sitename, $row_user[uid], $randpass, $Config_sitename_url_code, $Config_site_msg);

$endmessage = "$Config_msgfooter";
$sendmessage = "$premessage $endmessage";

$mailheader = "From: $name <$email>\nX-Mailer: $strRegisterMail1\nContent-Type: text/plain";
mail("$recemailto","$subject","$sendmessage","$mailheader");

	if($Config_makelogs == "1")
	{ $csr->MakeAdminLogs( $uid, "Account Details sent for $username", "2"); }

      $usr->Header($Config_SiteTitle ." :: $strAdminstration", '1', "usrmngt.php?dowhat=show&username=$username");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	$errMsg = "<b>$strSent, $strRedirecting...</b><br>$strElse <a href=\"usrmngt.php?dowhat=show&username=$username\">$strClickhere</a>\n";
	$usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	echo("<BR>");
      $usr->Footer();
	exit;
}

else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/unact.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

$usr->Footer(); 
?>
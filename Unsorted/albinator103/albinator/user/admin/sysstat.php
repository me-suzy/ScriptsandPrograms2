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
	 $csr->MakeAdminLogs( $uid, "Denied Access to the Admin Panel :: $SCRIPT_NAME", "2");

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
	 $result = queryDB( "SELECT * FROM $tbl_userinfo" );
	 $usrnum = mysql_num_rows( $result );

	 $result = queryDB( "SELECT * FROM $tbl_userinfo WHERE status='0'" );
	 $usrnum_unact = mysql_num_rows( $result );

	 $result = queryDB( "SELECT * FROM $tbl_userinfo WHERE status='2'" );
	 $usrnum_block = mysql_num_rows( $result );

	 $result = queryDB( "SELECT * FROM $tbl_userinfo WHERE admin='1'" );
	 $usrnum_admin = mysql_num_rows( $result );

	 $result = queryDB( "SELECT * FROM $tbl_reminders" );
	 $nreminders = mysql_num_rows( $result );

	 $result = queryDB( "SELECT * FROM $tbl_ecards" );
	 $necards = mysql_num_rows( $result );

	 $result = queryDB( "SELECT * FROM $tbl_albumlist" );
	 $nrows = mysql_num_rows( $result );
	 
 	 $result = queryDB( "SELECT SUM(pused) as pused_total, SUM(sused) as sused_total FROM $tbl_userinfo" );
	 $row = mysql_fetch_array( $result );

	 $size_of_dir = $csr->calcSpaceVal( $row[sused_total] );

$show_values =<<<__HTML_END_

	<p>&nbsp;</p>
	<table width=70% cellpadding=4 cellspacing=0 border=0 align=center bgcolor=#999999>
	<tr bgcolor=#dddddd>
	<td align=right class=tn width=70%>
	Photos &nbsp;
	</td>
	<td class=tn>
	$row[pused_total]
	</td>
	</tr>
	<tr bgcolor=#eeeeee>
	<td align=right class=tn width=70%>
	Users &nbsp;
	</td>
	<td class=tn>
	$usrnum
	</td>
	</tr>
	<tr bgcolor=#dddddd>
	<td align=right class=tn width=70%>
	$strAdminNotifyOpt2 &nbsp;
	</td>
	<td class=tn>
	$usrnum_unact
	</td>
	</tr>
	<tr bgcolor=#eeeeee>
	<td align=right class=tn width=70%>
	$strAdminNotifyOpt3 &nbsp;
	</td>
	<td class=tn>
	$usrnum_block
	</td>
	</tr>
	<tr bgcolor=#dddddd>
	<td align=right class=tn width=70%>
	$strAdmin &nbsp;
	</td>
	<td class=tn>
	$usrnum_admin
	</td>
	</tr>
	<tr bgcolor=#eeeeee>
	<td align=right class=tn width=70%>
	$strAlbum$strPuralS &nbsp;
	</td>
	<td class=tn>
	$nrows
	</td>
	</tr>
	<tr bgcolor=#dddddd>
	<td align=right class=tn width=70%>
	$strAMenusReminder$strPuralS ($strPending) &nbsp;
	</td>
	<td class=tn>
	$nreminders
	</td>
	</tr>
	<tr bgcolor=#eeeeee>
	<td align=right class=tn width=70%>
	$strMenusEcards ($strPending) &nbsp;
	</td>
	<td class=tn>
	$necards
	</td>
	</tr>
	<tr bgcolor=#dddddd>
	<td align=right class=tn width=70%>
	$strSpace $strUsed &nbsp;
	</td>
	<td class=tn>
	$size_of_dir
	</td>
	</tr>
	</table>
	<p>&nbsp;</p>

__HTML_END_;

##################

       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusSys");
	 echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/sysstat.gif>&nbsp;</div><br>");

	 echo($show_values);

	if($Config_sysstatus == "1")
	{
?>

<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#000000">
  <tr bgcolor="#eeeeee"> 
    <td> 
      <br><p align="center"><span class="tn"><b><?php echo $strAdminSysCmt3 ?></b></span></p>
      <form name="form" action="sysstat.php" method="post" >
        <div align="center"><span class="tn"><?php echo $strMessage ?>
          <input type="text" name="message" maxlength="199">
          <input type="hidden" name="dowhat" value="shutdown">
          <input type="submit" name="Submit" value="<?php echo $strAdminSysCmt4 ?> &gt;&gt;">
          </span></div>
      </form>
    </td>
  </tr>
</table>
      <p>&nbsp;</p>

<?php

	}

	else
	{
?>

<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#000000">
  <tr bgcolor="#eeeeee"> 
    <td height="34"> 
      <br><p align="center"><span class="tn"><b><?php echo $strAdminSysCmt1 ?></b></span></p>
      <form name="form" action="sysstat.php" method="post" >
        <div align="center"><span class="tn"> 
          <input type="hidden" name="dowhat" value="open">
          <input type="submit" name="Submit" value="<?php echo $strAdminSysCmt5 ?> &gt;&gt;">
          </span></div>
      </form>
    </td>
  </tr>
</table>
      <p>&nbsp;</p>

<?php
	}
	
}

else if($dowhat == "shutdown")
{
	if(!$message)
 	$message = $strAdminSysMsg;

 	$result = queryDB( "UPDATE $tbl_config SET fnvalue='$message' WHERE fname='sysmsg'" );
 	$result = queryDB( "UPDATE $tbl_config SET fnvalue='0' WHERE fname='sysstatus'" );

	 if($Config_makelogs == "1")
	 $csr->MakeAdminLogs( $uid, "System Status set to SHUTDOWN", "2");

	 if($Config_shut_logoff == "1")
	 { $effect = 1; $csr->userLogoff(); }
	 
	    $usr->Header($Config_SiteTitle ." :: Admin :: System Status", '1', "sysstat.php?dowhat=show");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/sysstat.gif>&nbsp;</div><br>");
	    $errMsg = "<b>System Shutdown, now redirecting...</b><br>else <a href=\"sysstat.php?dowhat=show\">click here</a>\n";
	    $usr->errMessage( $errMsg, 'Success', 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;

}

else if($dowhat == "logoff")
$csr->userLogoff();

else if($dowhat == "open")
{

 	$result = queryDB( "UPDATE $tbl_config SET fnvalue='' WHERE fname='sysmsg'" );
 	$result = queryDB( "UPDATE $tbl_config SET fnvalue='1' WHERE fname='sysstatus'" );

	 if($Config_makelogs == "1")
	 { $csr->MakeAdminLogs( $uid, "System Status set to OPEN", "2"); }


          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusSys", '1', "sysstat.php?dowhat=show");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/sysstat.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strAdminSysCmt3, $strRedirecting...</b><br>$strElse <a href=\"sysstat.php?dowhat=show\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;

}


else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusSys");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/reminders.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

$usr->Footer(); 

?>
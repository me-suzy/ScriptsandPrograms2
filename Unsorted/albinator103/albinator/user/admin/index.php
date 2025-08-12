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
	 { $csr->MakeAdminLogs( $uid, "Denied Access to the Admin Panel :: $SCRIPT_NAME", "2"); }
       $usr->Header($Config_SiteTitle .' :: Adminstration');
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $csr->customMessage( 'noadmin' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

	mysql_free_result( $result );	

      if (file_exists($dirpath."user/install.php") || file_exists($dirpath."install/install.php"))
      {
       $usr->Header($Config_SiteTitle ." :: $strAdminstration");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $errMsg = "<B>Please delete install.php to access adminstration. It is a big security threat to the system.</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
	 $usr->Footer();	
	 exit;
	}

	 if($Config_makelogs == "1")
	 $csr->MakeAdminLogs( $uid, "Entered the Admin Panel", "2");

       $usr->Header($Config_SiteTitle ." :: $strAdminstration");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
?>


<p>&nbsp;</p>
<table width="70%" border="0" cellspacing="1" cellpadding="4" align="center" class=tn bgcolor="#E8E6E6">
  <tr bgcolor="#F7F5F5"> 
    <td colspan=3> 
      <div align="right"><b>&lt; <?php echo $strAdminWelcome ?> &gt;&nbsp</b></div>
    </td>
  </tr>
  <tr bgcolor="#eeeeee"> 
    <td width="33%"><a href="usrmngt.php?dowhat=index" class=noundertn><?php echo $strAMenusUsr ?></a></td>
    <td width="34%"><a href="usrmngt.php?dowhat=all" class=noundertn><?php echo $strAMenusAllUsr ?></a></td>
    <td width="33%"><a href="sysstat.php?dowhat=show" class=noundertn><?php echo $strAMenusSys ?></a></td>
  </tr>
  <tr bgcolor="#eeeeee">
    <td width="33%"><a href=addac.php class=noundertn><?php echo $strAMenusAddAc ?></a></td>
    <td width="34%"><a href=reminders.php?dowhat=show&catog=all class=noundertn><?php echo $strAMenusReminder ?></a></td>
    <td width="33%"><a href="config.php?dowhat=show" class=noundertn><?php echo $strAMenusConfiguration ?></a></td>
  </tr>
  <tr bgcolor="#eeeeee">
    <td width="33%"><a href=notify.php?dowhat=show class=noundertn><?php echo $strAMenusNotify ?></a></td>
    <td width="34%"><a href=albums.php?dowhat=show&catog=all class=noundertn><?php echo($strAlbum.$strPuralS) ?></a></td>
    <td width="33%"><a href="userprofile.php?dowhat=show" class=noundertn><?php echo $strAMenusProfile ?></a></td>
  </tr>
  <tr bgcolor="#eeeeee">
    <td width="33%"><a href=userinfo.php?dowhat=show&catog=online class=noundertn><?php echo $strAMenusOnlineUsr ?></a></td>
    <td width="34%"><a href=ecards.php?dowhat=show&catog=all class=noundertn><?php echo $strMenusEcards ?></a></td>
    <td width="33%"><a href=userinfo.php?dowhat=show&catog=admin class=noundertn><?php echo $strAMenusAdminstrators ?></a></td>
  </tr>
  <tr bgcolor="#eeeeee">
    <td width="33%"><a href="adlogs.php?dowhat=show" class=noundertn><?php echo $strAMenusLogs ?></a></td>
    <td width="34%"><a href="unact.php?dowhat=show" class=noundertn><?php echo $strAMenusUnactUsr ?></a></td>
    <td width="33%"><a href="http://www.albinator.com/manual/" class=noundertn target=_blank><?php echo $strAMenusOnlMan ?></td>
  </tr>
  <tr bgcolor="#eeeeee">
    <td width="33%"><a href='revise.php' class=noundertn>Revise Photos</a></td>
    <td width="34%"><a href='resize.php' class=noundertn>Resize Photos</a></td>
    <td width="33%"></td>
  </tr>
</table>
<p>&nbsp;</p>
                  
<?php

$usr->Footer(); 

?>
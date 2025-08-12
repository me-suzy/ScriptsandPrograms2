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
	if(!$sort)
	{ $sort = "rid"; }


	if($sort == "rid")
	{ $sortval = "$strSortBy: $strID ~ <a href=\"reminders.php?dowhat=show&catog=$catog&username=$username&sort=uid\">$strOwner</a> ~ <a href=\"reminders.php?dowhat=show&catog=$catog&username=$username&sort=makedate\">$strDate</a>"; }
	else if($sort == "uid")
	{ $sortval = "$strSortBy: <a href=\"reminders.php?dowhat=show&catog=$catog&username=$username&sort=rid\">$strID</a> ~ $strOwner ~ <a href=\"reminders.php?dowhat=show&catog=$catog&username=$username&sort=makedate\">$strDate</a>"; }
	if($sort == "makedate")
	{ $sortval = "$strSortBy: <a href=\"reminders.php?dowhat=show&catog=$catog&username=$username&sort=rid\">$strID</a> ~ <a href=\"reminders.php?dowhat=show&catog=$catog&username=$username&sort=uid\">$strOwner</a> ~ $strDate"; }

	if($sort == "makedate")
	$sort = "date_year, date_month, date_day";
	if($catog == "all")
	$rs = new PagedResultSet("SELECT * FROM $tbl_reminders ORDER BY $sort",$page_maker);

	else if($catog == "user")
	{ $rs = new PagedResultSet("SELECT * FROM $tbl_reminders WHERE uid='$username' ORDER BY $sort",$page_maker);
        $showvalb = " :: <a href=reminders.php?dowhat=show&catog=all&&sort=$sort><?php echo $strAll ?></a>";
 	  $foruser = "$strFor $username, "; }

	$nr  = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("dowhat=$dowhat&username=$username&catog=$catog&sort=$sort");

	$sortval .= "$showvalb";

      if($Config_makelogs == "1")
      $csr->MakeAdminLogs( $uid, "Reminders List $username", "2"); 

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusReminder");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/reminders.gif>&nbsp;</div><br>");
?>

<table width="80%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr> 
    <td colspan=5><br><div align=center class=ts>
     <?php echo ("$foruser $nr $strAMenusReminder<br>$sortval"); ?><p><?php echo $nav ?>
    </div><br></td>
  </tr>

  <tr class="tn"> 
<?php echo("
    <td><b>$strID</b></td>
    <td><b>$strOwner</b></td>
    <td><b>$strEvent</b></td>
    <td><b>$strDate</b></td>
"); ?>
    <td>&nbsp;</td>
  </tr>

<?php

$i = 0;

while($row = $rs->fetchArray())
{
	if($i == 1)
	{ $i=0; $rowcolor = "#dddddd"; }
	else
	{ $i++; $rowcolor = "#eeeeee"; }
?>

  <tr class="tn" bgcolor="<?php echo $rowcolor ?>"> 
    <td><?php echo $row[rid] ?></td>
    <td><a href=usrmngt.php?dowhat=show&username=<?php echo $row[uid] ?> class=noundertn><?php echo $row[uid] ?></a></td>
    <td><?php if(strlen($row[event]) > 25) echo substr($row[event], 0, 20)."..."; else echo $row[event] ?></td>
    <td><?php echo "$row[date_year]-$row[date_month]-$row[date_day]"; ?></td>
    <td class=ts>[<a href="<?php echo "reminders.php?dowhat=edit&username=$username&catog=$catog&rid=$row[rid]&sort=$sort"; ?>" class=nounderts><?php echo $strEdit ?></a>] [<a href="<?php echo "reminders.php?dowhat=del&username=$username&catog=$catog&rid=$row[rid]&sort=$sort"; ?>" class=nounderts><?php echo $strDelete ?></a>]</td>
  </tr>

<?php

}
	echo("</table><p>&nbsp;</p>");


}

else if($dowhat == "edit")
{

	if(!$rid)
 	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusReminder");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strNo $strAMenusReminder $strID</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
   	 $usr->Footer();
	 exit;
	}

 	$result = queryDB( "SELECT * FROM $tbl_reminders WHERE rid = '$rid'" );
	$nr = mysql_num_rows( $result );

	$row = mysql_fetch_array( $result );

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusReminder");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/reminders.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strNo $strAMenusReminder</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusReminder");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/reminders.gif>&nbsp;</div><br>");

?>

<div align=center><a href="<?php echo "reminders.php?dowhat=show&catog=$catog&username=$username&sort=rid"; ?>">&lt;&lt; back</a></div>
<p>&nbsp;</p>
<form action=reminders.php method=post>
<table width="90%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right">rid</div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"><b><?php echo $row[rid] ?></b></td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right">uid</div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_uid" value="<?php echo $row[uid] ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strEvent ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_event" value="<?php echo $row[event] ?>">
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo $strMessage ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <textarea name="new_message"><?php echo $row[message] ?></textarea>
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strMailStatus ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_estatus" value="<?php echo $row[estatus] ?>">
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo $strYear ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_date_year" value="<?php echo $row[date_year] ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strMonth ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_date_month" value="<?php echo $row[date_month] ?>">
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right">date day</div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_date_day" value="<?php echo $row[date_day] ?>">
    </td>
  </tr>
  <tr> 
    <td class="tn"> 
      <div align="right"></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="hidden" name="sort" value="<?php echo $sort ?>">
      <input type="hidden" name="dowhat" value="editconf">
      <input type="hidden" name="catog" value="<?php echo $catog ?>">
      <input type="hidden" name="username" value="<?php echo $username ?>">
      <input type="hidden" name="rid" value="<?php echo $rid ?>">
      <input type="submit" name="Submit" value="<?php echo $strUpdate ?> &gt;&gt;">
    </td>
  </tr>
  <tr> 
    <td colspan=3 class="tn" height="2">&nbsp;</td>
  </tr>
  <tr bgcolor=#FFFFFF> 
    <td colspan=3 class="tn" height="2"> <?php echo $strNote ?>:
      <ul>
	<?php echo $strAdminReminderRules ?>
      </ul>
    </td>
  </tr>
</table>
</form>

<?php

}

else if($dowhat == "editconf")
{

 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$new_uid'" );
	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusReminder");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/reminders.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAlbumCrErr24</B>\n";
       $usr->errMessage( $errMsg, $strError );
	 echo("<br>");
   	 $usr->Footer();

	 mysql_free_result( $result );
	
	 closeDB();
	 exit;
	}
      mysql_free_result( $result );
	

	    $result = queryDB( "UPDATE $tbl_reminders SET uid='$new_uid',  event='$new_event',  message='$new_message',  estatus='$new_estatus', date_year='$new_date_year',  date_month='$new_date_month',  date_day='$new_date_day' WHERE rid='$rid'"); 


      if($Config_makelogs == "1")
      $csr->MakeAdminLogs( $uid, "Changed properties for ReminderID $rid", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusReminder", '1', "reminders.php?dowhat=show&catog=$catog&username=$username&sort=rid");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strSaved, $strRedirecting...</b><br>$strElse <a href=\"reminders.php?dowhat=show&catog=$catog&username=$username&sort=rid\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}


else if($dowhat == "del")
{
          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusReminder");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/reminders.gif>&nbsp;</div><br>");
	    $errMsg = "<b>You are about to delete reminder ID $rid,<br>are you sure?</b> <a href=\"reminders.php?dowhat=delconf&username=$username&catog=$catog&rid=$rid&sort=$sort\">$strYes</a> :: <a href=\"javascript:history.back(1);\">$strNo</a>\n";
	    $usr->errMessage( $errMsg, $strWarning, 'error', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "delconf")
{

     $result = queryDB( "DELETE FROM $tbl_reminders WHERE rid='$rid'"); 
    
      if($Config_makelogs == "1")
      $csr->MakeAdminLogs( $uid, "Deleted ReminderID $rid from db", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusReminder", '1', "reminders.php?dowhat=show&username=$username&catog=$catog&sort=$sort");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/reminders.gif>&nbsp;</div><br>");
	    $errMsg = "<b>Reminder $strDeleted, $strRedirecting...</b><br>$strElse <a href=\"reminders.php?dowhat=show&username=$username&catog=$catog&sort=$sort\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}


else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusReminder");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/reminders.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

$usr->Footer(); 

?>
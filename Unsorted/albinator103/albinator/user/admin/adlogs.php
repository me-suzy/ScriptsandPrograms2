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
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strLogs");
	 echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/logs.gif>&nbsp;</div><br>");

	if($wtshow == "theuser")
	$wtshowval = "WHERE uid='$username'";
	if($wtshow == "user")
	$wtshowval = "WHERE status='1'";
	else if($wtshow == "admin")
	$wtshowval = "WHERE status='2'";
	else if($wtshow == "other")
	$wtshowval = "WHERE status='3'";
	else if($username && $wtshow == "theuser")
	$wtshowval = "WHERE uid='$username'";
	else
	$wtshowval = "";

	if(!$sort || $sort == "logid")
	{ $sort = "logid DESC"; }

	$rs = new PagedResultSet("SELECT * FROM $tbl_adlogs $wtshowval ORDER BY $sort",$page_maker);
	$nr = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("dowhat=$dowhat&username=$username&logid=$logid&sort=$sort&wtshow=$wtshow");

	if($username && $wtshow == "theuser")
 	{  $optionsval = "<a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=user>$strAdminLogsOpt1</a> :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=admin>$strAdminLogsOpt2</a> :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=other>$strAdminLogsOpt3</a> :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]>$strAdminLogsOpt4</a>"; }
	
	else if($wtshow == "")
 	{ $optionsval = "<a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=user>$strAdminLogsOpt1</a> :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=admin>$strAdminLogsOpt2</a> :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=other>$strAdminLogsOpt3</a> :: $strAdminLogsOpt4 :: <a href=adlogs.php?dowhat=del&username=$username&logid=$row[logid]>$strDelete</a>"; }

	else if($wtshow == "admin")
 	{ $optionsval = "<a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=user>$strAdminLogsOpt1</a> :: $strAdminLogsOpt2 :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=other>$strAdminLogsOpt3</a> :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]>$strAdminLogsOpt4</a> :: <a href=adlogs.php?dowhat=del&username=$username&logid=$row[logid]&wtshow=admin>$strDelete</a>"; }

	else if($wtshow == "user")
 	{ $optionsval = "$strAdminLogsOpt1 :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=admin>$strAdminLogsOpt2</a> :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=other>$strAdminLogsOpt3</a> :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]>$strAdminLogsOpt4</a> :: <a href=adlogs.php?dowhat=del&username=$username&logid=$row[logid]&wtshow=user>$strDelete</a>"; }

	else if($wtshow == "other")
 	{ $optionsval = "<a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=user>$strAdminLogsOpt1</a> :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=admin>$strAdminLogsOpt2</a> :: $strAdminLogsOpt3 :: <a href=adlogs.php?dowhat=show&username=$username&logid=$row[logid]>$strAdminLogsOpt4</a> :: <a href=adlogs.php?dowhat=del&username=$username&logid=$row[logid]&wtshow=other>$strDelete</a>"; }
	
?>



<table width="98%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr> 
    <td colspan=6><br><div align=center class=ts><?php if($username && $wtshow == "theuser") echo "for $username, "; ?><?php echo $nr ?> Logs,<br><?php echo $nav ?><br><br><?php echo $optionsval ?></div><br></td>
  </tr>

  <tr class="ts"> 
    <td><b><a href=<?php echo "adlogs.php?dowhat=show&username=$username&logid=$row[logid]&sort=logid&wtshow=$wtshow"; ?>><?php echo $strID ?></a></b></td>
    <td><b><a href=<?php echo "adlogs.php?dowhat=show&username=$username&logid=$row[logid]&sort=uid&wtshow=$wtshow"; ?>><?php echo $strUser ?></a></b></td>
    <td><b><a href=<?php echo "adlogs.php?dowhat=show&username=$username&logid=$row[logid]&sort=status&wtshow=$wtshow"; ?>><?php echo $strType ?></a></b></td>
    <td width=20%><b><a href=<?php echo "adlogs.php?dowhat=show&username=$username&logid=$row[logid]&sort=acctimedate&wtshow=$wtshow"; ?>><?php echo $strDate ?></a></b></td>
    <td><b><?php echo $strMessage ?></b></td>
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

  <tr class="ts" bgcolor="<?php echo $rowcolor ?>"> 
    <td><?php echo $row[logid] ?></td>
    <td><a href=usrmngt.php?dowhat=show&username=<?php echo $row[uid] ?> class=noundertn><?php echo $row[uid] ?></a></td>
    <td><?php if($row[status] == "2") echo "$strAdmin"; else if($row[status] == "1") echo "$strUser"; else if($row[status] == "3") echo "$strAdminLogsOpt3"; ?></td>
    <td><?php echo "$row[acctimedate]"; ?></td>
    <td><?php echo "$row[msg]"; ?></td>
    <td class=ts>[<a href="<?php echo "adlogs.php?dowhat=del&username=$username&catog=$catog&logid=$row[logid]&sort=$sort&st=$st&wtshow=$wtshow\" class=nounderts>$strDelete</a>"; ?>]</td>
  </tr>

<?php
	}
	echo("</table><p>&nbsp;</p>");

}

else if($dowhat == "del")
{
	if($wtshow == "user")
	$wtshowval = $strAdminLogsOpt1;
	else if($wtshow == "admin")
	$wtshowval = $strAdminLogsOpt2;
	else if($wtshow == "other")
	$wtshowval = $strAdminLogsOpt3;
	else
	$wtshowval = $strAdminLogsOpt4;

	if($logid)
	$wtshowval = "Log$strID $logid";
	

	 $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strLogs");
	 echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/logs.gif>&nbsp;</div><br>");
	    $errMsg = "<b>You are about to delete $wtshowval,<br>are you sure?</b> <a href=\"adlogs.php?dowhat=delconf&username=$username&logid=$row[logid]&wtshow=$wtshow&logid=$logid\">Yes</a> :: <a href=\"javascript:history.back(1);\">No</a>\n";
	    $usr->errMessage( $errMsg, 'Warning', 'error', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}



else if($dowhat == "delconf")
{

	if($wtshow == "user")
	{ $wtshowval = $strAdminLogsOpt1; $whtdel = "WHERE status = '1'"; }
	else if($wtshow == "admin")
	{ $wtshowval = $strAdminLogsOpt2; $whtdel = "WHERE status = '2'"; }
	else if($wtshow == "other")
	{ $wtshowval = $strAdminLogsOpt3; $whtdel = "WHERE status = '3'"; }
	else
	{ $wtshowval = $strAdminLogsOpt4; $whtdel = ""; }

	if($logid)
	{ $wtshowval = "Log$strID $logid"; $whtdel = "WHERE logid='$logid'"; }

	    $result = queryDB( "DELETE FROM $tbl_adlogs $whtdel" ); 
    
          if($Config_makelogs == "1")
          $csr->MakeAdminLogs( $uid, "$strDeleted $wtshowval from db", "2"); 

	 $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strLogs", '1', "adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=$wtshow&logid=$logid");
	 echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/logs.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strLogs $strDeleted, $strRedirecting...</b><br>$strElse <a href=\"adlogs.php?dowhat=show&username=$username&logid=$row[logid]&wtshow=$wtshow&logid=$logid\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;

}

else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

	    $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strLogs");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/logs.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

$usr->Footer(); 

?>
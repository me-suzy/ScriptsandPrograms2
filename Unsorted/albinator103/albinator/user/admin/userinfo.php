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
     if($catog == "online")
     {
     $rs = new PagedResultSet("SELECT * FROM $tbl_userinfo",$page_maker);
     $nr  = mysql_num_rows( $rs->result );
     $nav = $rs->getPageNav("dowhat=$dowhat");

     $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsrb");
     echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");

     echo ("<table width=70% cellpadding=4 cellspacing=0 border=0 align=center>");
     echo ("<tr><td colspan=2>&nbsp;</td><td><div align=right class=tn><b><font face='verdana' size='4'>&lt; $strAMenusOnlineUsr &gt;</font><br></b><span class='ts'>$nav&nbsp;</span><br></td></tr>");

     $i = 0;

     while($row = $rs->fetchArray())
     {
		$checktime = $row[sessiontime] + 600;
		$nowtime = time();

		if($checktime >= $nowtime)
		{ 
			if($i == 1)
			{ $i=0; $rowcolor = "#dddddd"; }
			else
			{ $i++; $rowcolor = "#eeeeee"; }

			echo("<tr bgcolor=$rowcolor class=tn><td><a href=usrmngt.php?dowhat=show&username=$row[uid] class=noundertn>$row[uid]</a></td><td><b>$row[uname]</b></td><td><span class=ts>[<a href=sysstat.php?dowhat=logoff&username=$row[uid] class=nounderts>$strLogoff</a>]</span></td></tr>");
		}
     }

     echo("</table><br>");

     $usr->Footer();
     exit;
}

else if($catog == "admin")
{
     $rs = new PagedResultSet("SELECT * FROM $tbl_userinfo WHERE admin='1'",$page_maker);
     $nr  = mysql_num_rows( $rs->result );
     $nav = $rs->getPageNav("dowhat=$dowhat");

     $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusAdminstrators");
     echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");

     echo ("<table width=70% cellpadding=4 cellspacing=0 border=0 align=center>");
     echo ("<tr><td colspan=2><div align=right class=tn><b><font face=verdana size=4>&lt; $strAMenusAdminstrators &gt;</font><br></b><span class='ts'>$nav&nbsp;</span><br></td></tr>");

     $i = 0;

     while($row = $rs->fetchArray())
     {
			if($i == 1)
			{ $i=0; $rowcolor = "#dddddd"; }
			else
			{ $i++; $rowcolor = "#eeeeee"; }

			echo("<tr bgcolor=$rowcolor class=tn><td><a href=usrmngt.php?dowhat=show&username=$row[uid] class=noundertn>$row[uid]</a></td><td><b>$row[uname]</b></td></tr>");
     }

     echo("</table><br>");

     $usr->Footer();
     exit;
}

else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsrb");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strInvalid catogstate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

}


else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

	    $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusUsrb");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

$usr->Footer(); 

?>
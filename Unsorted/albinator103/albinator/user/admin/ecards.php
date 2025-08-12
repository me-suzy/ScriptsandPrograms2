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
	{ $sort = "ecid"; }

	if($catog == "all")
	$rs = new PagedResultSet("SELECT * FROM $tbl_ecards ORDER BY $sort",$page_maker);

	else if($catog == "user")
	{ $rs = new PagedResultSet("SELECT * FROM $tbl_ecards WHERE uid='$username' ORDER BY $sort",$page_maker);
        $showvalb = " :: <a href=ecards.php?dowhat=show&catog=all&&sort=$sort>$strAll</a>";
 	  $foruser = "For $username, "; }

	$nr  = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("dowhat=$dowhat&username=$username&catog=$catog&sort=$sort");

	if($sort == "ecid")
	{ $sortval = "$strSortBy: $strID ~ <a href=\"ecards.php?dowhat=show&catog=$catog&username=$username&sort=uid\">$strOwner</a> ~ <a href=\"ecards.php?dowhat=show&catog=$catog&username=$username&sort=makedate\">$strDate</a>"; }
	else if($sort == "uid")
	{ $sortval = "$strSortBy: <a href=\"ecards.php?dowhat=show&catog=$catog&username=$username&sort=ecid\">$strID</a> ~ $strOwner ~ <a href=\"ecards.php?dowhat=show&catog=$catog&username=$username&sort=makedate\">$strDate</a>"; }
	if($sort == "makedate")
	{ $sortval = "$strSortBy: <a href=\"ecards.php?dowhat=show&catog=$catog&username=$username&sort=ecid\">$strID</a> ~ <a href=\"ecards.php?dowhat=show&catog=$catog&username=$username&sort=uid\">$strOwner</a> ~ $strDate"; }

	$sortval .= "$showvalb";

      if($Config_makelogs == "1")
      $csr->MakeAdminLogs( $uid, "Ecard List $username", "2"); 

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strMenusEcards");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/ecards.gif>&nbsp;</div><br>");
?>

<table width="80%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr> 
    <td colspan=5><br><div align=center class=ts><?php echo $foruser ?><?php echo ("$nr $strMenusEcards <br> $sortval"); ?><p><?php echo $nav ?></div><br></td>
  </tr>

  <tr class="tn"> 
<?php echo("
    <td><b>$strID</b></td>
    <td><b>$strOwner</b></td>
    <td><b>$strRecipient</b></td>
    <td><b>$strDate</b></td>
    <td>&nbsp;</td>
"); ?>
  </tr>

<?php

$i = 0;

while($row = $rs->fetchArray())
{
	if($i == 1)
	{ $i=0; $rowcolor = "#dddddd"; }
	else
	{ $i++; $rowcolor = "#eeeeee"; }

	$reciver = "$row[rec_name] &lt;$row[rec_email]&gt;";
?>

  <tr class="tn" bgcolor="<?php echo $rowcolor ?>"> 
    <td><?php echo $row[ecid] ?></td>
    <td><a href=usrmngt.php?dowhat=show&username=<?php echo $row[uid] ?> class=noundertn><?php echo $row[uid] ?></a></td>
    <td><?php if(strlen($reciver) > 25) echo substr($reciver, 0, 15)."..."; else echo $reciver ?></td>
    <td><?php echo $row[makedate] ?></td>
    <td class=ts>[<a href="<?php echo "ecards.php?dowhat=edit&username=$username&catog=$catog&ecid=$row[ecid]&sort=$sort"; ?>" class=nounderts><?php echo $strEdit ?></a>] [<a href="<?php echo "ecards.php?dowhat=del&username=$username&catog=$catog&ecid=$row[ecid]&sort=$sort"; ?>" class=nounderts><?php echo $strDelete ?></a>]</td>
  </tr>

<?php

}
	echo("</table><p>&nbsp;</p>");
}

else if($dowhat == "edit")
{

	if(!$ecid)
 	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strMenusEcards");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strNo $strEcard</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
   	 $usr->Footer();
	 exit;
	}

 	$result = queryDB( "SELECT * FROM $tbl_ecards WHERE ecid = '$ecid'" );
	$nr = mysql_num_rows( $result );

	$row = mysql_fetch_array( $result );

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strMenusEcards");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/ecards.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strNo $strEcard</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strMenusEcards");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/ecards.gif>&nbsp;</div><br>");

?>

<div align=center><a href="<?php echo "ecards.php?dowhat=show&catog=$catog&username=$username&sort=ecid"; ?>">&lt;&lt; <?php echo $strBack ?></a></div>
<p>&nbsp;</p>
<form action=ecards.php method=post>
<table width="90%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right">ecid</div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"><b><?php echo $row[ecid] ?></b></td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right">uid</div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_uid" value="<?php echo $row[uid] ?>">
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo ("$strRecipient $strName"); ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_rec_name" value="<?php echo $row[rec_name] ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo ("$strRecipient $strEmail"); ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_rec_email" value="<?php echo $row[rec_email] ?>">
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo ("$strColor$strPuralS"); ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_colors" value="<?php echo $row[colors] ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strMessage ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <textarea name="new_message"><?php echo $row[message] ?></textarea>
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo("$strPhoto $strID"); ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_pic" value="<?php echo $row[pic] ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strMusic ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_music" value="<?php echo $row[music] ?>">
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo $strDate ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_makedate" value="<?php echo $row[makedate] ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strAMenusNotify ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_notify" value="<?php echo $row[notify] ?>">
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo $strID ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_code" value="<?php echo $row[code] ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strMailStatus ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_mailsent" value="<?php echo $row[mailsent] ?>">
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
      <input type="hidden" name="ecid" value="<?php echo $ecid ?>">
      <input type="submit" name="Submit" value="<?php echo $strUpdate ?> &gt;&gt;">
    </td>
  </tr>
  <tr> 
    <td colspan=3 class="tn" height="2">&nbsp;</td>
  </tr>
  <tr bgcolor=#FFFFFF> 
    <td colspan=3 class="tn" height="2"> <?php echo $strNote ?>:
      <ul>
	<?php echo $strAdminEcardsRules ?>
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
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strMenusEcards");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/ecards.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAlbumCrErr24</B>\n";
       $usr->errMessage( $errMsg, $strError );
	 echo("<br>");
   	 $usr->Footer();

	 mysql_free_result( $result );
	
	 closeDB();
	 exit;
	}


	    $result = queryDB( "UPDATE $tbl_ecards SET uid='$new_uid',  rec_name='$new_rec_name',  rec_email='$new_rec_email',  colors='$new_colors',  message='$new_message',  pic='$new_pic',  music='$new_music',  makedate='$new_makedate',  notify='$new_notify',  code='$new_code',  mailsent='$new_mailsent' WHERE ecid='$ecid'"); 


      if($Config_makelogs == "1")
      $csr->MakeAdminLogs( $uid, "Changed properties for eCardID $ecid", "2"); 

	    $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strMenusEcards", '1', "ecards.php?dowhat=show&catog=$catog&username=$username&sort=ecid");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strSaved, $strRedirecting...</b><br>$strElse <a href=\"ecards.php?dowhat=show&catog=$catog&username=$username&sort=ecid\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}


else if($dowhat == "del")
{
	    $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strMenusEcards");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/ecards.gif>&nbsp;</div><br>");
	    $errMsg = "<b>".$csr->LangConvert($strDelConfirm, "$strMenusEcards $strID $ecid")."</b> <a href=\"ecards.php?dowhat=delconf&username=$username&catog=$catog&ecid=$ecid&sort=$sort\">$strYes</a> :: <a href=\"javascript:history.back(1);\">$strNo</a>\n";
	    $usr->errMessage( $errMsg, $strWarning, 'error', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "delconf")
{
	    $result = queryDB( "DELETE FROM $tbl_ecards WHERE ecid='$ecid'"); 
    
          if($Config_makelogs == "1")
          $csr->MakeAdminLogs( $uid, "Deleted eCardID $ecid from db", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strMenusEcards", '1', "ecards.php?dowhat=show&username=$username&catog=$catog&sort=$sort");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/ecards.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strMenusEcards $strDeleted, $strRedirecting...</b><br>$strElse <a href=\"ecards.php?dowhat=show&username=$username&catog=$catog&sort=$sort\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}


else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strMenusEcards");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/ecards.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

$usr->Footer(); 

?>
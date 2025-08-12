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
	$sort = "aid";

	if($catog == "all")
	$rs = new PagedResultSet("SELECT * FROM $tbl_albumlist ORDER BY $sort",$page_maker);

	else if($catog == "user")
	{ $rs = new PagedResultSet("SELECT * FROM $tbl_albumlist WHERE uid='$username' ORDER BY $sort",$page_maker);
        $showvalb = " :: <a href=albums.php?dowhat=show&catog=all&&sort=$sort>$strAll</a>";
 	  $foruser = "$username, "; }

	$nr  = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("dowhat=$dowhat&username=$username&catog=$catog&sort=$sort");

	if($sort == "aid")
	{ $sortval = "$strSortBy: $strID ~ <a href=\"albums.php?dowhat=show&catog=$catog&username=$username&sort=uid\">$strOwner</a> ~ <a href=\"albums.php?dowhat=show&catog=$catog&username=$username&sort=aname\">$strAlbum $strName</a>"; }
	else if($sort == "uid")
	{ $sortval = "$strSortBy: <a href=\"albums.php?dowhat=show&catog=$catog&username=$username&sort=aid\">$strID</a> ~ $strOwner ~ <a href=\"albums.php?dowhat=show&catog=$catog&username=$username&sort=aname\">$strAlbum $strName</a>"; }
	else if($sort == "aname")
	{ $sortval = "$strSortBy: <a href=\"albums.php?dowhat=show&catog=$catog&username=$username&sort=aid\">$strID</a> ~ <a href=\"albums.php?dowhat=show&catog=$catog&username=$username&sort=uid\">$strOwner</a> ~ $strAlbum $strName"; }

	$sortval .= "$showvalb";

	if($nr > 1)
	$s = $strPuralS;

      if($Config_makelogs == "1")
      $csr->MakeAdminLogs( $uid, "$strAlbum $strList $username", "2"); 

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum $strList");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
?>

<table width="80%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr> 
    <td colspan=7><br><div align=center class=ts><?php echo ($foruser.$nr." ".$strAlbum.$s); ?><br><?php echo $sortval ?><p><?php echo $nav ?></div><br></td>
  </tr>

  <tr class="ts"> 
<?php
    echo("<td><b>$strID</b></td>
    <td><b>$strOwner</b></td>
    <td><b>$strAlbum</b></td>
    <td><b>$strAccess</b></td>
    <td><b>$strSpace</b></td>
    <td><b>$strPhoto$strPuralS</b></td>
    <td>&nbsp;</td>");
?>
  </tr>

<?php

if(!$username)
$usercall = "0";
else
$usercall = "1";

$i = 0;

while($row = $rs->fetchArray())
{
	$nr_pics = $row[pused];
	$space_used = $csr->calcSpaceVal($row[sused]);

	if($usercall == "0")
	$username = $row[uid];

	if($i == 1)
	{ $i=0; $rowcolor = "#dddddd"; }
	else
	{ $i++; $rowcolor = "#eeeeee"; }

	$row[aname] = stripslashes($row[aname]);
?>

  <tr class="tn" bgcolor="<?php echo $rowcolor ?>">
    <td><?php echo $row[aid] ?></td>
    <td><a href=usrmngt.php?dowhat=show&username=<?php echo $row[uid] ?> class=noundertn><?php echo $row[uid] ?></a></td>
    <td><?php if(strlen($row[aname]) > 15) echo substr($row[aname], 0, 15)."..."; else echo $row[aname] ?></td>
    <td><?php if ($row[private] == 1) echo "$strPrivate"; else echo "$strPublic"; ?></td>
    <td><?php echo $space_used ?></td>
    <td><?php echo $nr_pics ?></td>
    <td class=ts>[<a href="<?php echo "viewer/showalbum.php?username=$row[uid]&aid=$row[aid]"; ?>" class=nounderts><?php echo $strView ?></a>] [<a href="<?php echo "albums.php?dowhat=edit&username=$row[uid]&catog=$catog&aid=$row[aid]&sort=$sort"; ?>" class=nounderts><?php echo $strEdit ?></a>] [<a href="<?php echo "albums.php?dowhat=del&username=$row[uid]&catog=$catog&aid=$row[aid]&sort=$sort"; ?>" class=nounderts><?php echo $strDelete ?></a>]</td>
  </tr>

<?php

}
	echo("</table><p>&nbsp;</p>");
}

else if($dowhat == "edit")
{

	if(!$aid)
 	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum $strEditing");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAlbumCrErr1</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
	 echo("<BR>");
   	 $usr->Footer();
	 exit;
	}

 	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE aid = '$aid'" );
	$nr = mysql_num_rows( $result );

	$row = mysql_fetch_array( $result );

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum $strEditing");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAlbumCrErr1</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

	 $nr_pics = $row[pused];
	 $size_of_dir = $csr->calcSpaceVal( $row[sused] );

 	 $result = queryDB( "SELECT uid, uname FROM $tbl_userinfo" );

	 $usrlist = "\n<select name=\"new_uid2\" onclick=\"newOwner()\">\n<option value=\"$username\" selected>--- default ---</option>\n";
	 while($row_user = mysql_fetch_array( $result ))
	 { if($row_user[uid] != $username) 
         $usrlist .= "<option value=\"$row_user[uid]\">$row_user[uname] ($row_user[uid])</option>\n"; }
	 $usrlist .= "</select>\n";

       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum $strEditing");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
?>

<script> function newOwner() { document.albumedit.new_uid.value = document.albumedit.new_uid2.value; } </script>

<div align=center><a href="<?php echo("albums.php?dowhat=show&catog=$catog&username=$username&sort=aid"); ?>">&lt;&lt; <?php echo $strBack ?></a></div>
<p>&nbsp;</p>
<form name="albumedit" action=albums.php method=post>
<table width="90%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right">aid</div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"><b><?php echo ("$row[aid] ($size_of_dir)"); ?></b></td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo ("$strPhoto$strPuralS"); ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"><?php echo $nr_pics; if($nr_pics > 0) echo (" :: (<a href=\"albums.php?dowhat=editpic&catog=$catog&username=$username&sort=aid&aid=$aid\">$strEdit</a>)"); ?></td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn">
      <div align="right"><?php echo $strRegisterName4 ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_uid" value="<?php echo $row[uid] ?>"> or <?php echo $usrlist ?>
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo ("$strAlbum $strName"); ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_aname" value="<?php echo(stripslashes($row[aname])); ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strPassword ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="password" name="new_password">
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo $strAccess ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_private" value="<?php echo $row[private] ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strMessage ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_amsg" value="<?php echo(stripslashes($row[amsg])); ?>">
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo $strDate ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_cdate" value="<?php echo $row[cdate] ?>">
    </td>
  </tr>
  <tr> 
    <td class="tn"> 
      &nbsp;
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="hidden" name="sort" value="<?php echo $sort ?>">
      <input type="hidden" name="dowhat" value="editconf">
      <input type="hidden" name="catog" value="<?php echo $catog ?>">
      <input type="hidden" name="username" value="<?php echo $username ?>">
      <input type="hidden" name="aid" value="<?php echo $aid ?>">
      <input type="submit" name="Submit" value="<?php echo $strUpdate ?> &gt;&gt;">
    </td>
  </tr>
  <tr> 
    <td colspan=3 class="tn" height="2">&nbsp;</td>
  </tr>
  <tr bgcolor=#FFFFFF> 
    <td colspan=3 class="tn" height="2"> 
	<?php echo $strNote ?>:
      <ul>
        <li> <?php echo ("$strPrivate 1, $strPublic 0"); ?></li>
      </ul>
    </td>
  </tr>
</table>
</form>

<?php
}

else if($dowhat == "copy" || $dowhat == "move")
{
	if($l_confirm == "1" && !$new_aid)
	{
		$l_confirm = 0;
		$errMsg = "<b>$strAlbumCrErr1</b>";
	}

	if($l_confirm != "1")
	{
	$result = queryDB( "SELECT pused FROM $tbl_albumlist WHERE aid='$aid'" );
	$row = mysql_fetch_array( $result );
	if($row[pused] < 1)
  	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
       $errMsg = "<b>Album doesnt have images to update, <a href=javascript:history.back(1)>back</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);

	$result = queryDB( "SELECT COUNT(*) as nr FROM $tbl_albumlist" );
	$row = mysql_fetch_array( $result );
	if($row[nr] < 2)
  	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAdminAlbums1</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);
	$result1 = queryDB( "SELECT * FROM $tbl_albumlist WHERE aid != '$aid' && uid='$username'" );
	$result2 = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid!='$username'" );

	$result_orig = queryDB( "SELECT pname FROM $tbl_pictures WHERE pid = '$pid'" );
	$row_orig = mysql_fetch_array ( $result_orig );

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");

	if($errMsg)
	{
	 echo("<p>&nbsp;</p>");
       $usr->errMessage( $errMsg, $strError );
	}
?>

<br><br>
<form method=post action=albums.php>
	<table width="70%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#aaaaaa">
	  <tr bgcolor="#dddddd"> 
   	    <td width="90"><img src="<?php echo ($dirpath."$Config_datapath/$username/tb_$row_orig[pname]"); ?>" width="90" height="65" alt="<?php echo $row_orig[pname] ?>"></td>
	    <td class=tn> 
	      <div align="right"><?php echo ("$dowhat $strPhoto"); ?> 
	        <select name="new_aid">
<?php
	if(mysql_num_rows( $result1 ))
	{
		echo ("<option value=\"0\">$strAlbum$strPuralS: $username</option>\n");
		while($row = mysql_fetch_array ( $result1 ))
		{
			echo ("<option value=\"$row[aid]\">".stripslashes($row[aname])."</option>\n");
		}
	}

	if(mysql_num_rows( $result2 ))
	{
		if(mysql_num_rows( $result1 ))
		echo ("<option value=\"0\"> </option>\n");

		echo ("<option value=\"0\">$strOther $strAlbum$strPuralS</option>\n");

		while($row = mysql_fetch_array ( $result2 ))
		{
			echo ("<option value=\"$row[aid]\">".stripslashes($row[aname])." ($row[uid])</option>\n");
		}
	}
?>
	        </select>
	        <input type="hidden" name="pid" value="<?php echo $pid ?>">
		  <input type="hidden" name="aid" value="<?php echo $aid ?>">
	        <input type="hidden" name="l_confirm" value="1">
	        <input type="hidden" name="username" value="<?php echo($username) ?>">
	        <input type="hidden" name="dowhat" value="<?php echo($dowhat); ?>">
		  <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons"; ?>/change.gif" width="53" height="19" border="0">
	      </div>
	    </td>
	  </tr>
	</table>
</form>
<?php
            $usr->Footer();
		exit;
	}

	else
	{
	$result = queryDB( "SELECT COUNT(*) as nr FROM $tbl_albumlist WHERE aid='$new_aid' && uid='$username'" );
	$row = mysql_fetch_array( $result );
	
	if(!$row[nr])
	{
		$result = queryDB( "SELECT uid FROM $tbl_albumlist WHERE aid='$new_aid'" );
		$row = mysql_fetch_array( $result );
	}

	if($dowhat == "move")
	$fspace = $csr->editSize( $pid, $username, 'move', $new_aid, $row[uid] );
	else if($dowhat == "copy")
	$fspace = $csr->editSize( $pid, $username, 'copy', $new_aid, $row[uid] );

	if($fspace)
	$result = queryDB( "UPDATE $tbl_userinfo SET sused=sused-'$fspace', pused=pused-1 WHERE uid='$username'" );

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS", '1', "albums.php?dowhat=editpic&catog=all&username=$username&sort=aid&aid=$aid");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
	$errMsg = "<b>$strPhoto $strChange$strPuralS $strDone, $strRedirecting...</b><br>$strElse <a href=albums.php?dowhat=editpic&catog=all&username=$username&sort=aid&aid=$aid>$strClickhere</a>\n";
	$usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
      $usr->Footer();
	exit;
	}
}

else if($dowhat == "editconf")
{

 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$new_uid'" );
	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAlbumCrErr24</B>\n";
       $usr->errMessage( $errMsg, $strError );
	 echo("<br>");
   	 $usr->Footer();

	 mysql_free_result( $result );
	
	 closeDB();
	 exit;
	}

 	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE aid = '$aid'" );
	$row = mysql_fetch_array( $result );

	if($row[private] == "0")
	{ $albpub = "1"; }
	
	if($new_password && (strlen($new_password) < 6 || strlen($new_password) > 10))
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strRegisterError5b</B>\n";
       $usr->errMessage( $errMsg, $strError );
	 echo("<br>");
   	 $usr->Footer();

	 mysql_free_result( $result );
	
	 closeDB();
	 exit;
	}

	if($new_private == "1" && $albpub == "1" && !$new_password)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strTellWrongPassb</B>\n";
       $usr->errMessage( $errMsg, $strError );
	 echo("<br>");
   	 $usr->Footer();

	 mysql_free_result( $result );
	
	 closeDB();
	 exit;
	}

	if($username != $new_uid)
	$csr->editSize($username, $new_uid, "movalb", $aid);

$result = queryDB( "UPDATE $tbl_albumlist SET uid='$new_uid', aname='".addslashes(htmlspecialchars($new_aname))."', private='$new_private', amsg='".addslashes(htmlspecialchars($new_amsg))."', cdate='$new_cdate' WHERE aid='$aid'");

if($new_password)
{ $new_password_enc = md5($new_password);
  $result = queryDB( "UPDATE $tbl_albumlist SET password='$new_password_enc' WHERE aid='$aid'"); }


	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Changed properties for AlbumID $aid", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS", '1', "albums.php?dowhat=show&catog=$catog&username=$username&sort=aid");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strSaved, $strRedirecting...</b><br>$strElse <a href=\"albums.php?dowhat=show&catog=$catog&username=$username&sort=aid\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}


else if($dowhat == "del")
{
          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strAlbumDelConfirm</b> <a href=\"albums.php?dowhat=delconf&username=$username&catog=$catog&aid=$aid&sort=$sort\">$strYes</a> :: <a href=\"javascript:history.back(1);\">$strNo</a>\n";
	    $usr->errMessage( $errMsg, $strWarning, 'error', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "delconf")
{
	      $result_pics = queryDB( "SELECT pid FROM $tbl_pictures WHERE aid = '$aid'" );
		while ($row = mysql_fetch_array ( $result_pics ))
		{		
			$csr->editSize( $row[pid], $username, 'del', '1' );
		}
		mysql_free_result ( $result_pics );
	      $result = queryDB( "DELETE FROM $tbl_albumlist WHERE aid='$aid'"); 
    
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Deleted AlbumID $aid from db", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS", '1', "albums.php?dowhat=show&username=$username&catog=$catog&sort=$sort");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strAlbum $strDeleted, $strRedirecting...</b><br>$strElse <a href=\"albums.php?dowhat=show&username=$username&catog=$catog&sort=$sort\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "editpic")
{
	if(!$aid)
 	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAlbumCrErr1</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
	 echo("<BR>");
   	 $usr->Footer();
	 exit;
	}

 	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE aid = '$aid'" );
	$nr = mysql_num_rows( $result );

	$row = mysql_fetch_array( $result );

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAlbumCrErr20</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}
	 $row_user = $row[uid];
	 $nr = $row[pused];

       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");

	 if(!$nr)
	 {
       $errMsg = "<B>$strAlbumCrErr17</b>, <a href=\"albums.php?dowhat=show&catog=$catog&username=$username&sort=aid&aid=$aid\">$strAlbum $strList</a>\n";
       $usr->errMessage( $errMsg, '', 'error', '80' );
	 echo("<BR>");
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

echo("<div align=center><p>Aid: $aid, $strAlbum: &quot;<i>".stripslashes($row[aname])."</i>&quot;, $strPhoto$strPuralS: $nr, Owner: <i>$username</i><br><a href=\"albums.php?dowhat=edit&catog=$catog&username=$username&sort=aid&aid=$aid\">&lt;&lt; back</a> <br></div>\n<p>&nbsp;</p>"); 

	$p_count = 0;
 	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE aid = '$aid'" );

	while($row = mysql_fetch_array( $result ))
	{
	$p_count++;
	$mnsize = $csr->calcSpaceVal($row[o_used]);
	$imsize = $csr->calcSpaceVal($row[i_used]);
	if($imsize < 0) $imsize = 0;
	$tbsize = $csr->calcSpaceVal($row[t_used]);

	$sizeinfo = "$strThumbnail $tbsize, $strOrignal $mnsize, $strInter $imsize";
?>

<form action=albums.php method=post>
<table width="70%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr> 
    <td class="ts" align=center width=250 valign=bottom><?php echo $sizeinfo ?></td>
    <td width="2%">&nbsp;</td>
    <td class="tn"><a href=<?php echo $dirpath ?>user/admin/viewer/showpic.php?aid=<?php echo $aid ?>&pid=<?php echo $row[pid] ?>&username=<?php echo $row_user ?>><img src=<?php echo "$dirpath"."$Config_datapath/$row_user/tb_$row[pname]"; ?> border=0></a></td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strPid ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"><b><?php echo $row[pid] ?></b> (<a href=<?php echo ("albums.php?dowhat=pic_del&catog=$catog&username=$username&sort=aid&aid=$aid&pid=$row[pid]>$strDelete</a> : <a href=albums.php?dowhat=copy&catog=$catog&username=$username&sort=aid&aid=$aid&pid=$row[pid]>$strCopy</a> : <a href=albums.php?dowhat=move&catog=$catog&username=$username&sort=aid&aid=$aid&pid=$row[pid]>$strMove</a>)"); ?></b></td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo $strFile ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <?php echo $Config_datapath."/".$username."/".$row[pname] ?>
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strOrder ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_pindex" value="<?php echo $row[pindex] ?>">
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo $strMessage ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_pmsg" value="<?php echo(stripslashes($row[pmsg])); ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="hidden" name="sort" value="<?php echo $sort ?>">
      <input type="hidden" name="dowhat" value="editconf_pic">
      <input type="hidden" name="catog" value="<?php echo $catog ?>">
      <input type="hidden" name="username" value="<?php echo $username ?>">
      <input type="hidden" name="aid" value="<?php echo $aid ?>">
      <input type="hidden" name="pid" value="<?php echo $row[pid] ?>">
      <input type="submit" name="Submit" value="<?php echo $strUpdate ?> &gt;&gt;">
    </td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
<?php
	}
}


else if($dowhat == "editconf_pic")
{
 	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE aid = '$aid'" );
	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strAlbumCrErr20</B>\n";
       $usr->errMessage( $errMsg, $strError );
	 echo("<br>");
   	 $usr->Footer();

	 mysql_free_result( $result );
	
	 closeDB();
	 exit;
	}

$result = queryDB( "UPDATE $tbl_pictures SET pindex='$new_pindex', pmsg='".addslashes(htmlspecialchars($new_pmsg))."' WHERE pid='$pid'"); 


	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Changed pic properties for pid :: $pid", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS", '1', "albums.php?dowhat=edit&catog=$catog&username=$username&sort=aid&aid=$aid");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/usrmngt.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strSaved, $strRedirecting...</b><br>$strElse <a href=\"albums.php?dowhat=edit&catog=$catog&username=$username&sort=aid&aid=$aid\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;	
}



else if($dowhat == "pic_del")
{
          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
	    $errMsg = "<b>".$csr->LangConvert($strDelConfirm, "Pid $pid")."</b> <a href=\"albums.php?dowhat=pic_delconf&username=$username&catog=$catog&aid=$aid&sort=$sort&pid=$pid\">$strYes</a> :: <a href=\"javascript:history.back(1);\">$strNo</a>\n";
	    $usr->errMessage( $errMsg, $strWarning, 'error', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "pic_delconf")
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Deleted PicID $pid from db", "2"); 

	    $csr->editSize( $pid, $username, 'del' );

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS", '1', "albums.php?dowhat=editpic&username=$username&catog=$catog&sort=$sort&aid=$aid");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strPhoto $strDeleted, $strRedirecting...</b><br>$strElse <a href=\"albums.php?dowhat=editpic&username=$username&catog=$catog&sort=$sort&aid=$aid\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}


else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAlbum$strPuralS");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/albumlist.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

$usr->Footer(); 

?>
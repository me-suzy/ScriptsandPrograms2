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
 	$result = queryDB( "SELECT * FROM $tbl_userprofile ORDER BY findex, fid ASC" );
	$nr = mysql_num_rows( $result );

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/userprofile.gif>&nbsp;</div><br>");
?>

<table width="80%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr> 
    <td colspan=7><br><div align=center class=ts><?php echo("$nr $strField$strPuralS (<a href=userprofile.php?dowhat=add>$strAdd</a>"); ?>)</div><br></td>
  </tr>

  <tr class="tn"> 
<?php
echo("
    <td><b>$strID</b></td>
    <td><b>$strOrder</b></td>
    <td><b>$strAdminProfileOpt1</b></td>
    <td><b>$strType</b></td>
    <td><b>$strAdminProfileOpt2</b></td>
    <td><b>$strAdminProfileOpt3</b></td>
    <td>&nbsp;</td>
  </tr>
");

$i = 0;

while($row = mysql_fetch_array( $result ))
{
	if($i == 1)
	{ $i=0; $rowcolor = "#dddddd"; }
	else
	{ $i++; $rowcolor = "#eeeeee"; }
?>

  <tr class="tn" bgcolor="<?php echo $rowcolor ?>"> 
    <td><?php echo $row[fid] ?></td>
    <td><?php echo $row[findex] ?></td>
    <td><?php if(strlen($row[tname]) > 15) echo substr($row[tname], 0, 12)."..."; else echo $row[tname] ?></td>
    <td><?php if(strlen($row[type]) > 20) echo substr($row[type], 0, 17)."..."; else echo $row[type] ?></td>
    <td><?php if(strlen($row[dvalue]) > 12) echo substr($row[dvalue], 0, 10)."..."; else echo $row[dvalue] ?></td>
    <td><?php if(strlen($row[topts]) > 12) echo substr($row[topts], 0, 10)."..."; else echo $row[topts] ?></td>
    <td class=ts>[<a href="<?php echo "userprofile.php?dowhat=edit&fid=$row[fid]"; ?>" class=nounderts><?php echo $strEdit ?></a>] [<a href="<?php echo "userprofile.php?dowhat=del&fid=$row[fid]"; ?>" class=nounderts><?php echo $strDelete ?></a>]</td>
  </tr>
<?php
}
	echo("</table><p>&nbsp;</p>");
}

else if($dowhat == "edit" || $dowhat == "add")
{

if($new_type == "0" && $new_type_old)
$new_type = $new_type_old;

if($confirm == 1)
{
	if($dowhat == "add")
	{
		if($new_type == "0")
		$errMsg .= "$strAdminProfileCmt6<br>";

		if(!$new_tname)
		$errMsg .= "$strAdminProfileCmt5<br>";
	}


	if($new_type != "textfield" && $new_type != "multiple")
	{
		if(!$new_topts)
		$errMsg .= "$strAdminProfileCmt4<br>";

		else
		{
			$temparr = explode(",", $new_topts);
			$i = 0;

			while($temparr[$i])
			$i++;

			if($i < 2)
			$errMsg .= "$strAdminProfileCmt3<br>";
		}
	}

	if (!eregi ("([0-9])$", $new_findex) && $new_findex)
	$errMsg .= "$strAdminProfileCmt2<br>";
}

if($confirm != 1 || $errMsg)
{
     if($dowhat == "edit")
     {
	if(!$fid)
 	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/userprofile.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strNo $strField $strID</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
   	 $usr->Footer();
	 exit;
	}

 	$result = queryDB( "SELECT * FROM $tbl_userprofile WHERE fid = '$fid'" );
	$nr = mysql_num_rows( $result );

	$row = mysql_fetch_array( $result );

	if(!$nr)
	{
       $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/userprofile.gif>&nbsp;</div><br>");
       $errMsg = "<B>$strNo $strField</b>\n";
       $usr->errMessage( $errMsg, $strError, 'error', '80' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

	if($confirm != "1")
	{
	$fid = $row[fid];
	$type = $row[type];
	$dvalue = $row[dvalue];
	$topts = $row[topts];
	$tname = $row[tname];
	$findex = $row[findex];
	}
	else
      {
	$type = $new_type;
	$dvalue = $new_dvalue;
	$topts = $new_topts;
	$tname = $new_tname;
	$findex = $new_findex;
      }

     }

     else
     {
	$fid = "tobe assigned";
	$type = $new_type;
	$dvalue = $new_dvalue;
	$topts = $new_topts;
	$tname = $new_tname;
	$findex = $new_findex;
     }

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile", '', '', 'onload');
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/userprofile.gif>&nbsp;</div><br>");


	if($errMsg)
	{
	      $usr->errMessage( $errMsg, $strError, 'error', '70' );
	}

?>

<p>&nbsp;</p>
<form action=userprofile.php method=post>
<table width="70%" border="0" cellspacing="0" cellpadding="4" align="center">
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right">fid</div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"><b><?php echo $fid ?></b></td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strAdminProfileOpt1 ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_tname" value="<?php echo $tname ?>">
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo $strAdminProfileOpt2 ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_dvalue" value="<?php echo $dvalue ?>">
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strAdminProfileOpt3 ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_topts" value="<?php echo $topts ?>"><br><span class=ts>seperate by comman only</span>
    </td>
  </tr>
  <tr bgcolor=#DDDDDD> 
    <td class="tn"> 
      <div align="right"><?php echo $strType ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> <?php 
	if($type)
	{
	 echo "<b>$type</b> &nbsp;"; 
	 echo "<input type=hidden name=new_type_old value=$type>";
	}
	?>

		<select name="new_type">
		  <option value="0" selected>---- select new ----</option>
		  <option value="textfield">textfield</option>
		  <option value="multiple">multiple lines</option>
		  <option value="radio">radio</option>
		  <option value="checkbox">checkbox</option>
		  <option value="list">list</option>
		  <option value="pulldown">pulldown</option>
		</select>
    </td>
  </tr>
  <tr bgcolor=#EEEEEE> 
    <td class="tn"> 
      <div align="right"><?php echo $strOrder ?></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 
      <input type="text" name="new_findex" value="<?php echo $findex ?>">
    </td>
  </tr>
  <tr> 
    <td class="tn"> 
      <div align="right"></div>
    </td>
    <td width="2%" class="tn">&nbsp;</td>
    <td class="tn"> 

<?php 
if($dowhat == "edit")
{
	echo("<input type=\"hidden\" name=\"dowhat\" value=\"edit\">\n");
	echo("<input type=\"hidden\" name=\"fid\" value=\"$fid\">");
}
else if($dowhat == "add")
echo("<input type=\"hidden\" name=\"dowhat\" value=\"add\">");
?>

      <input type="hidden" name="confirm" value="1">
      <input type="submit" name="Submit" value="<?php echo $dowhat ?> &gt;&gt;">
    </td>
  </tr>
  <tr> 
    <td colspan=3 class="tn" height="2">&nbsp;</td>
  </tr>
</table>
<div class=ts align=center><?php echo("<b>$strNote:</b> $strAdminProfileRules"); ?></div>
</form>

<?php
}

else
{
if($new_type == "textfield" || $new_type == "multiple")
$new_topts = '';

else
$new_dvalue = '';

if(!$new_findex)
{
 	$result = queryDB( "SELECT findex FROM $tbl_userprofile" );
	$nr = mysql_num_rows( $result );

	if($dowhat == "add")
	{
		if($nr < 1)
		$new_findex = 1;

		else
		$new_findex = $nr + 1;
	}

	else
	$new_findex = $nr;
}

$new_tname = ereg_replace ("\|", "", $new_tname);
$new_dvalue = ereg_replace ("\|", "", $new_dvalue);
$new_topts = ereg_replace ("\|", "", $new_topts);
$new_findex = ereg_replace ("\|", "", $new_findex);

if($new_type == "checkbox")
$new_topts = ereg_replace ("\+", "", $new_topts);

if($dowhat == "add")
$result = queryDB( "INSERT INTO $tbl_userprofile VALUES(NULL, '$new_type', '$new_tname', '$new_topts', '$new_dvalue', '$new_findex') " );
else
$result = queryDB( "UPDATE $tbl_userprofile SET type='$new_type', tname='$new_tname', topts='$new_topts', dvalue='$new_dvalue', findex='$new_findex' WHERE fid='$fid'" );

          if($Config_makelogs == "1")
          $csr->MakeAdminLogs( $uid, "{$dowhat}ed field $fid from db", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile", '1', 'userprofile.php?dowhat=show');
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/userprofile.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strField {$dowhat}ed, $strRedirecting...</b><br>$strElse <a href=\"userprofile.php?dowhat=show\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;

}

}

else if($dowhat == "del")
{
          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	    $errMsg = "<b>".$csr->LangConvert($strDelConfirm, "$strField $fid, $strAdminProfileCmt1")."</b> <a href=\"userprofile.php?dowhat=delconf&fid=$fid\">$strYes</a> :: <a href=\"javascript:history.back(1);\">$strNo</a>\n";
	    $usr->errMessage( $errMsg, $strWarning, 'error', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "delconf")
{
	    $result_user = queryDB( "SELECT * FROM $tbl_userinfo" );
	    ############ delete responses #############
	    while($row_user = mysql_fetch_array( $result_user ))
	    {
	    $parray = split ('[|]', $row_user[profile]);
	    $profilenew = "";

          foreach ($parray as $pairval_first) 
	    {
		list ($pfid, $pval) = split ('[*]', $pairval_first);

		if($pfid != $fid)
		{ $profilenew .= $pfid."*".$pval."|"; }
	    }

		$profilenew = ereg_replace ("\|\*\|", "\|", $profilenew);

		$result_up = queryDB( "UPDATE $tbl_userinfo SET profile='$profilenew' WHERE uid='$row_user[uid]'" );
	    }
	    ###########################################
    
	    $result = queryDB( "DELETE FROM $tbl_userprofile WHERE fid='$fid'"); 

          if($Config_makelogs == "1")
          $csr->MakeAdminLogs( $uid, "Deleted field $fid from db", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile", '1', 'userprofile.php?dowhat=show');
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/userprofile.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strField $strDeleted, $strRedirecting...</b><br>$strElse <a href=\"userprofile.php?dowhat=show\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "editprf")
{
	if($confirm == 1)
	{
	####### photo add #######
 	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$username'" );
	$row_user = mysql_fetch_array( $result_user );

      $parray = split ('[|]', $row_user[profile]);

	foreach ($parray as $pairval_first) 
	{
		list ($cphoto, $pfile) = split ('[*]', $pairval_first);

		if($cphoto == "0")
		break;
	}
	
	if($cphoto == "0")
	$profile = $cphoto."*".$pfile."|";
	###############################

	if($emailpref == 1 && ereg("E", $row_user[prefs]))
 	$result = queryDB( "UPDATE $tbl_userinfo SET prefs = REPLACE(prefs, 'E', '') WHERE uid='$username'" );

	if($emailpref == 0 && !ereg("E", $row_user[prefs]))
 	$result = queryDB( "UPDATE $tbl_userinfo SET prefs = CONCAT(prefs, 'E') WHERE uid='$username'" );

 	$result = queryDB( "SELECT * FROM $tbl_userprofile ORDER BY findex, fid ASC" );

	while($row = mysql_fetch_array( $result ))
	{
		if($row[type] == "checkbox" && ${$row[fid]})
		{
			$i = 0;
			while(${$row[fid]}[$i])
			{
				if($i != 0)
				$checkbox_values .= "+".${$row[fid]}[$i];

				else
				$checkbox_values .= ${$row[fid]}[$i];

				$i++;		
			}
			$profile .="$row[fid]"."*"."$checkbox_values|";
			$checkbox_values = "";
		}

		else if(${$row[fid]})
		$profile .= "$row[fid]"."*"."${$row[fid]}|";		
	}

 	$result = queryDB( "UPDATE $tbl_userinfo SET profile='$profile' WHERE uid='$username'" );

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile", '1', "userprofile.php?dowhat=editprf&username=$username");
	echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	$errMsg = "<b>$strProfile $strSaved, $strRedirecting...</b><br>$strElse <a href=\"userprofile.php?dowhat=editprf&username=$username\">$strClickhere</a>\n";
	$usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	echo("<BR>");
      $usr->Footer();
	exit;
	}

	if($confirm != 1)
	{
 	$result = queryDB( "SELECT * FROM $tbl_userprofile ORDER BY findex, fid ASC" );
	$nr = mysql_num_rows( $result );

 	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$username'" );
	$row_user = mysql_fetch_array( $result_user );

      $parray = split ('[|]', $row_user[profile]);

      $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile");
	echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/userprofile.gif>&nbsp;</div><br>");
	echo ("<p>&nbsp;</p>");
	echo ("<form name=userprofile method=post action=userprofile.php>");
	echo ("<table width=80% cellspacing=1 cellpadding=0 border=0 align=center bgcolor=#CCCCCC><tr><td>\n");
	echo ("<table width=100% cellspacing=0 cellpadding=4 border=0 align=center>\n");
?>

  <tr class="tn" bgcolor="#EEEEEE"> 
    <td colspan=2 align=left><b>~ <?php echo ($strProfile." ".$row_user[uname].", ".$row_user[uid]); ?> ~</b> [<a href=<?php echo $dirpath ?>showprofile.php?uuid=<?php echo $row_user[uid] ?> target=_blank><?php echo $strProfileView ?></a>] [<a href=usrmngt.php?username=<?php echo $row_user[uid] ?>&dowhat=show><?php echo $strAdminOpen ?></a>]</td>
  </tr>

<?php

	foreach ($parray as $pairval_first) 
	{
		list ($cphoto, $pfile) = split ('[*]', $pairval_first);

		if($cphoto == "0")
		break;
	}

?>

  <tr class="tn" bgcolor="#DDDDDD"> 
    <td width=40%><?php echo("$strProfile $strPhoto"); if($pfile != "0") echo ("<div class=ts>[<a href=userprofile.php?dowhat=pdel&username=$username><?php echo $strDelete ?></a>]</div>"); ?>
</td>
    <td>
<?php
	if($cphoto == "0" && $pfile != "0")
	{
		  $picurl = "$dirpath"."$Config_datapath/$username/$pfile";
		  error_reporting(0);
	   	  $size = GetImageSize ("$picurl");
		  error_reporting(E_ERROR | E_WARNING);

  		  echo("\n<img src=\"$picurl\" border=1 $size[3]>");
	}

	else
	echo ("None");
?>
    </td>
  </tr>
  <tr class="tn" bgcolor="#EEEEEE"> 
    <td width=40%><?php echo $strProfileOpt2 ?></td>
    <td>
<?php

	if(ereg("E", $row_user[prefs]))
	$selb = "checked";
	else
	$sela = "checked";

?>
<input type=radio name=emailpref value="1" <?php echo $sela ?>> <?php echo $strYes ?> <input type=radio name=emailpref value="0" <?php echo $selb ?>> <?php echo $strNo ?>

</td>
  </tr>

<?php
$i = 0;

while($row = mysql_fetch_array( $result ))
{
	if($i == 1)
	{ $i=0; $rowcolor = "#eeeeee"; }
	else
	{ $i++; $rowcolor = "#dddddd"; }

	foreach ($parray as $pairval) 
	{
		list ($pfid, $pvalue) = split ('[*]', $pairval);

		if($pfid == $row[fid])
		{ $found = 1; break; }
		else
		$found = 0;
	}

?>

  <tr class="tn" bgcolor="<?php echo $rowcolor ?>"> 
    <td width=40%><?php echo $row[tname] ?></td>
    <td>
<?php

if($found == 1)
$print_val = $pvalue;
else
$print_val = $row[dvalue];

if($row[type] == "multiple")
echo ("<textarea name=$row[fid] cols=30 rows=5>$print_val</textarea>");

else if($row[type] == "textfield")
echo ("<input type=text name=$row[fid] size=35 value=\"$print_val\">");

else
{
	$temparr = explode(",", $row[topts]);
	$j = 0;

	if($row[type] == "radio")
	{
		foreach($temparr as $rval)
		{
			if($found == 1 && $rval == $print_val)
			$sel = "checked";		
			else
			$sel = "";

			if($rval)
			echo ("<input type=radio name=\"$row[fid]\" value=\"$rval\" $sel> $rval \n");
		}
	}
	else if($row[type] == "list" || $row[type] == "pulldown")
	{
		if($row[type] == "list")
		$listadd = "height=5 multiple";

		echo ("<select name=$row[fid] $listadd>\n");

		foreach($temparr as $rval)	
		{ 
			if($found == 1 && $rval == $print_val)
			$sel = "selected";		
			else
			$sel = "";

			if($rval)
			echo ("<option value=\"$rval\" $sel> $rval \n"); 
		}

		echo ("</select>");
	}
	else if($row[type] == "checkbox")
	{
		$j++;
		foreach($temparr as $rval)	
		{
			$print_val_checkbox = explode ("+", $print_val);

			if($found == 1 && in_array($rval, $print_val_checkbox))
			$sel = "checked";		
			else
			$sel = "";

			if($rval)
			echo ("<input type=checkbox name=\"".$row[fid]."[]\" value=\"$rval\" $sel> $rval \n");

			$j++;
		}

		echo ("</select>");
	}
}


?>
    </td>
  </tr>

<?php
}

?>

<tr>
<td>&nbsp;</td>
<td><input type=image src=<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/save.gif border="0"></td>
</tr>
</table>
</td></tr>
</table>
<input type=hidden name=username value=<?php echo $username ?>>
<input type=hidden name=dowhat value=editprf>
<input type=hidden name=confirm value=1>
</form>
<p>&nbsp;</p>

<?php
}

}


else if($dowhat == "pdel")
{
          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	    $errMsg = "<b>".$csr->LangConvert($strDelConfirm, "$username $strPhoto")."</b> <a href=\"userprofile.php?dowhat=pdelconf&username=$username\">$strYes</a> :: <a href=\"javascript:history.back(1);\">$strNo</a>\n";
	    $usr->errMessage( $errMsg, $strWarning, 'error', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "pdelconf")
{
	    $result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$username'" );
	    $row_user = mysql_fetch_array( $result_user );

	    $parray = split ('[|]', $row_user[profile]);
	    $profilenew = "";

          foreach ($parray as $pairval_first) 
	    {
		list ($pfid, $pval) = split ('[*]', $pairval_first);

		if($pfid != "0")
		{ $profilenew .= $pfid."*".$pval."|"; }
		else if($pfid == "0")
		{ $profilenew .= "0"."*"."0|"; 
		  unlink($dirpath.$Config_datapath."/".$username."/".$pval); }
	    }

		$profilenew = ereg_replace ("\|\*\|", "\|", $profilenew);
		$result_up = queryDB( "UPDATE $tbl_userinfo SET profile='$profilenew' WHERE uid='$username'" );

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile", '1', "userprofile.php?dowhat=editprf&username=$username");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strPhoto $strDeleted, $strRedirecting...</b><br>$strElse <a href=\"userprofile.php?dowhat=editprf&username=$username\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}


else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

          $usr->Header($Config_SiteTitle ." :: $strAdminstration :: $strAMenusProfile");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin/userprofile.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strInvalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

$usr->Footer(); 

?>
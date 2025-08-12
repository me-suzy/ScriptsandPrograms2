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

if($dowhat == "show")
{
 	$result = queryDB( "SELECT * FROM $tbl_userprofile ORDER BY findex, fid ASC" );
	$nr = mysql_num_rows( $result );

 	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$uid'" );
	$row_user = mysql_fetch_array( $result_user );

      $parray = split ('[|]', $row_user[profile]);

      $usr->Header($Config_SiteTitle ." :: $strProfile");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	echo ("<P>&nbsp;</P>");
?>

 <table width=80% cellspacing=0 cellpadding=4 border=0 align=center>
  <tr class="tn"> 
    <td colspan=2 align=right>
	[<a href=userprofile.php?dowhat=edit><?php echo $strEdit ?></a>]&nbsp;
    </td>
  </tr>
</table>
<?php
	echo ("<table width=80% cellspacing=1 cellpadding=0 border=0 align=center bgcolor=#CCCCCC><tr><td>\n");
	echo ("<table width=100% cellspacing=0 cellpadding=4 border=0 align=center>\n");
?>

  <tr class="tn" bgcolor="#DDDDDD"> 
    <td colspan=2>
	<table width=98% cellspacing=0 cellpadding=4 border=0 align=center>
	<tr>
	<td width=110>

<?php

	foreach ($parray as $pairval_first) 
	{
		list ($cphoto, $pfile) = split ('[*]', $pairval_first);

		if($cphoto == "0")
		break;
	}

	if($cphoto == "0" && $pfile != "0")
	{
	  srand((double)microtime()*100);
	  $randnum = rand();

	  $picurl = "$dirpath"."$Config_datapath/$uid/$pfile";
	  error_reporting(0);
   	  $size = GetImageSize ("$picurl");
	  error_reporting(E_ERROR | E_WARNING);

	  $picurl = "$dirpath"."$Config_datapath/$uid/$pfile?$randnum";
  	  echo("\n<img src=\"$picurl\" $size[3]>");
	}

	else
	{
	  $picurl = "$dirpath"."$Config_imgdir"."/noprofile.jpg";
	  error_reporting(0);
   	  $size = GetImageSize ("$picurl");
	  error_reporting(E_ERROR | E_WARNING);

  	  echo("\n<img src=\"$picurl\" border=1 $size[3]>");
	}

?>
    </td>
    <td>
<?php
    echo("<div align=left class=tn><b>&nbsp;<font size=3 color=#333333>$row_user[uname]");
  
    if(!preg_match("/E/", $row_user[prefs]))
    echo("<br>&nbsp;$row_user[email]");

    if(!$row_user[country])
    $row_user[country] = "";

    echo("<br>&nbsp;$row_user[country]</font></b></div>");
?>	
    </td>
  </tr>
</table>
</td>
</tr>
<tr class="tn" bgcolor="#EEEEEE"> 
   <td width=40%><b><font color=#666666><?php echo $strProfileOpt1 ?>:</font></b></td>
<?php

 	$result_alb = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid='$uid'" );
	$nr_alb = mysql_num_rows( $result_alb );

?>
   <td>

<?php 
if($nr_alb > 1)
$s = $strPuralS; 

if($nr_alb > 0)
echo "$nr_alb $strAlbum$s (<a href='".$dirpath."showlist.php?uuid=$uid&dowhat=user'>$strList</a>)"; 
else
echo "0 $strAlbum$strPuralS";
?>

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
    <td width=40%><b><font color=#666666><?php echo $row[tname] ?></font></b></td>
    <td>
<?php

	if($found == 1)
	{
		if($row[type] == "checkbox")
		{
			$pvalue = ereg_replace ("\+", ", ", $pvalue);	
		}

		echo ($pvalue);
	}

	else
	{
		echo(" ");
	}
?>
    </td>
   </tr>

<?php

}

?>

</table>
</td>
</tr>
</table>
<p>&nbsp;</p>

<?php

}

else if($dowhat == "edit")
{
	if($confirm == 1)
	{
	####### photo add #######
 	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$uid'" );
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

	if($emailpref == 1 && preg_match("/E/", $row_user[prefs]))
 	$result = queryDB( "UPDATE $tbl_userinfo SET prefs = REPLACE(prefs, 'E', '') WHERE uid='$uid'" );

	if($emailpref == 0 && !preg_match("/E/", $row_user[prefs]))
 	$result = queryDB( "UPDATE $tbl_userinfo SET prefs = CONCAT(prefs, 'E') WHERE uid='$uid'" );

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
			$profile .= "$row[fid]"."*"."$checkbox_values|";
			$checkbox_values = "";
		}

		else if(${$row[fid]})
		{
		${$row[fid]} = ereg_replace ("\|", '', ${$row[fid]});	
		${$row[fid]} = ereg_replace ("\*", '', ${$row[fid]});	
		$profile .= "$row[fid]"."*"."${$row[fid]}|";		
		}
	}

 	$result = queryDB( "UPDATE $tbl_userinfo SET profile='$profile' WHERE uid='$uid'" );

	$usr->Header($Config_SiteTitle ." :: $strProfile", '1', 'userprofile.php?dowhat=show');
	echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	$errMsg = "<b>$strSaved, $strRedirecting...</b><br>$strElse <a href=\"userprofile.php?dowhat=show\">$strClickhere</a>\n";
	$usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	echo("<BR>");
      $usr->Footer();
	exit;
	}

	if($confirm != 1)
	{
 	$result = queryDB( "SELECT * FROM $tbl_userprofile ORDER BY findex, fid ASC" );
	$nr = mysql_num_rows( $result );

 	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$uid'" );
	$row_user = mysql_fetch_array( $result_user );

      $parray = split ('[|]', $row_user[profile]);

	$usr->Header($Config_SiteTitle ." :: $strProfile");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	echo ("<P>&nbsp;</P>");
	echo ("<form name=userprofile method=post action=userprofile.php>");
	echo ("<table width=90% cellspacing=1 cellpadding=0 border=0 align=center bgcolor=#CCCCCC><tr><td>\n");
	echo ("<table width=100% cellspacing=0 cellpadding=4 border=0 align=center>\n");
?>

  <tr class="tn" bgcolor="#EEEEEE"> 
    <td colspan=2 align=left><b><?php echo $strProfileWelcome ?></b></td>
  </tr>
  <tr class="tn" bgcolor="#DDDDDD"> 
    <td width=40%><?php echo $strPhoto ?></td>
    <td>
<?php

	foreach ($parray as $pairval_first) 
	{
		list ($cphoto, $pfile) = split ('[*]', $pairval_first);

		if($cphoto == "0")
		break;
	}

	if($cphoto == "0" && $pfile != "0")
	echo ("[<a href=userprofile.php?dowhat=pupdate>$strUpdate</a>] [<a href=userprofile.php?dowhat=pdel>$strDelete</a>]");

	else
	echo ("[<a href=userprofile.php?dowhat=padd>$strMenusAddphoto</a>]");
?>
    </td>
  </tr>
  <tr class="tn" bgcolor="#EEEEEE"> 
    <td width=40%><?php echo $strProfileOpt2 ?></td>
    <td>
<?php

	if(preg_match("/E/", $row_user[prefs]))
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

	$whilest = 0;

	if($row[type] == "radio")
	{
		echo ("<table width=100% cellspacing=1 cellpadding=0 border=0 class=tn>\n<tr class=tn>\n");
		foreach($temparr as $rval)
		{
			$j++;
			if($found == 1 && $rval == $print_val)
			$sel = "checked";		
			else
			$sel = "";

			if($rval)
			echo ("<td width=33% valign=top><input type=radio name=\"$row[fid]\" value=\"$rval\" $sel> $rval </td>\n");

			if($j==3)
			{ echo("</tr>\n<tr class=tn>\n"); $j=0; }
		}

		while($j!=3)
		{ echo("<td width=33% valign=top>&nbsp;</td>\n"); $whilest = 1; $j++; }

		if($whilest == 1)
		echo ("</tr>\n");

		echo("</table>\n");
	}
	else if($row[type] == "list" || $row[type] == "pulldown")
	{
		if($row[type] == "list")
		$listadd = "height=5 multiple";
		else
		$listadd = "";

		echo ("<select name=$row[fid] $listadd>\n");

		foreach($temparr as $rval)	
		{ 
			if($found == 1 && $rval == $print_val)
			$sel = "selected";		
			else
			$sel = "";

			if($rval)
			echo ("<option value=\"$rval\" $sel> $rval</option>\n"); 
		}

		echo ("</select>");
	}
	else if($row[type] == "checkbox")
	{
		echo ("<table width=100% cellspacing=1 cellpadding=0 border=0 class=tn>\n<tr class=tn>\n");
		foreach($temparr as $rval)	
		{
			$j++;
			$print_val_checkbox = explode ("+", $print_val);

			if($found == 1 && in_array($rval, $print_val_checkbox))
			$sel = "checked";		
			else
			$sel = "";

			if($rval)
			echo ("<td width=33% valign=top><input type=checkbox name=\"".$row[fid]."[]\" value=\"$rval\" $sel> $rval</td> \n");

			if($j==3)
			{ echo("</tr>\n<tr class=tn>\n"); $j=0; }
		}

		while($j!=3)
		{ echo("<td>&nbsp;</td>\n"); $whilest = 1; $j++; }

		if($whilest == 1)
		echo ("</tr>\n");

		echo("</table>\n");
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
<input type=hidden name=dowhat value=edit>
<input type=hidden name=confirm value=1>
</form>
<p>&nbsp;</p>

<?php
}

}

else if($dowhat == "pdel")
{
	    $usr->Header($Config_SiteTitle ." :: $strProfile ");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	    $errMsg = "<b>".$csr->LangConvert($strDelConfirm, $strPhoto)."</b> <a href=\"userprofile.php?dowhat=pdelconf\">$strYes</a> :: <a href=\"javascript:history.back(1);\">$strNo</a>\n";
	    $usr->errMessage( $errMsg, $strWarning, 'error', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "pdelconf")
{
	    $result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$uid'" );
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
		  unlink($dirpath.$Config_datapath."/".$uid."/".$pval); }
	    }

		$profilenew = ereg_replace ("\|\*\|", "\|", $profilenew);
		$result_up = queryDB( "UPDATE $tbl_userinfo SET profile='$profilenew' WHERE uid='$uid'" );

	    $usr->Header($Config_SiteTitle ." :: $strProfile", '1', 'userprofile.php?dowhat=show');
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strAlbumCrErr23, $strRedirecting...</b><br>$strElse <a href=\"userprofile.php?dowhat=show\">$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

else if($dowhat == "padd" || $dowhat == "pupdate")
{
	    $result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$uid'" );
	    $row_user = mysql_fetch_array( $result_user );

	    $parray = split ('[|]', $row_user[profile]);
	    $profilenew = "";

          foreach ($parray as $pairval_first) 
	    {
		list ($pfid, $pval) = split ('[*]', $pairval_first);

		if($pfid == "0")
		break;
	    }


	    $usr->Header($Config_SiteTitle ." :: $strProfile");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
?>

<form enctype="multipart/form-data" action="upload.php" method="post">
<input type="hidden" name="upload_sent" value="1">
<input type="hidden" name="scall" value="1">

<input type="hidden" name="uppic" value="<?php echo $pval ?>">
<table width="70%" border="0" cellspacing="2" cellpadding="3" align="center">
  <tr> 
    <td width="30%" class="tn"> 
      &nbsp;
    </td>
    <td class=ts>&nbsp;<?php echo $strProfileOpt3 ?></td>
  </tr>
  <tr> 
    <td width="30%" class="tn"> 
      <div align="right"><?php echo $strPhoto ?>&nbsp;</div>
    </td>
    <td><input name="userfile[]" type=file></td>
  </tr>
  <tr> 
    <td width="30%">&nbsp;</td>
    <td class=ts><?php echo $strAllowedTypes ?>: <b><?php echo $Config_allow_types_show ?></b>&nbsp;</td>
  </tr>
  <tr> 
    <td width="30%">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<tr><td colspan=3 align=center class=ts>
<input type="hidden" name="aid" value="<?php echo $aid ?>">
<input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/upload.gif" width="53" height="19" border="0"></td></tr>
</table>
</form>

<?php
}

else
{
	    if($Config_makelogs == "1")
	    $csr->MakeAdminLogs( $uid, "Invalid do state at $SCRIPT_NAME, from $HTTP_REFERER", "2"); 

	    $usr->Header($Config_SiteTitle ." :: $strProfile");
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/userprofile.gif>&nbsp;</div><br>");
	    $errMsg = "<b>Invalid dostate</b>\n";
	    $usr->errMessage( $errMsg, $strError );
	    echo("<BR>");
          $usr->Footer();
	    exit;
}

$usr->Footer(); 

?>
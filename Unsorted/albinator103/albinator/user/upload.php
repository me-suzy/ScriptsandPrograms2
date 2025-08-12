<?php
	$dirpath = "$Config_rootdir"."../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr   = new Html();
	$ucook = new UserCookie();
	$csr   = new ComFunc();
	
      if($HTTP_POST_VARS["upload"] == "1" && !$ucook->LoggedIn())
      $ucook->UploadRefresh($uid);

      if ( !$ucook->LoggedIn() )
      {
          $usr->HeaderOut();
	    $csr->customMessage( 'logout' );
	    $usr->FooterOut();
   
          exit;
      }

      $usr->Header($Config_SiteTitle ." :: $strMenusAddphotos");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/upload.gif>&nbsp;</div>");

	if(!$upload_sent)
	{
	$result_user = queryDB( "SELECT limits FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row_user = mysql_fetch_array( $result_user );
	list($slimit, $alimit, $plimit, $rlimit) = split('[|]', $row_user[limits]);
	mysql_free_result( $result_user );

	$result_user = queryDB( "SELECT pused FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row_user = mysql_fetch_array( $result_user );
	$photos_used = $row_user[pused];

	if($photos_used >= $plimit && $plimit)
	{
    	 $errMsg = "<b>".$csr->LangConvert($strCrossLimit, strtolower($strPhoto.$strPuralS))."</b> [<a href=\"$Config_buylink\">$strBuySentence</a>] or <a href=javascript:history.back(1)>$strBack</a>...</b><br><br>\n";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
	 echo ("<br>");
   	 $usr->Footer();

	 exit;
      }

	mysql_free_result( $result_user );
	}

	if(!$aid && !$upload_sent)
	{
	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr1, <a href=index.php>$strCreate</a></b>\n<br>";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
   	 $usr->Footer();
	 exit;
      }
	mysql_free_result( $result );

	$result_user = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	
?>
<br><br>
<form action="upload.php" method="post">
<table width="80%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
  <tr bgcolor="#CCCCCC"> 
    <td> 
      <div align="center" class="tn"><?php echo($csr->LangConvert($strSelectAlbum, $strMenusAddphoto)); ?> 
        <select name="aid">
<?php
		while($row = mysql_fetch_array( $result_user ))
		{
			echo("<option value=$row[aid]>".stripslashes($row[aname])."</option>\n");
		}
?>
        </select>
	 <input type=submit name=submit value="<?php echo $strNext ?> &gt;" class="butfieldc">
      </div>
    </td>
  </tr>
</table>
</form>	

<p>&nbsp;</p>
<table width="80%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor=#000000>
  <tr bgcolor="#eeeeee"> 
    <td> 
      <div align="left" class="tn"> 
      <p><?php echo $strNote ?>:</p>
      </div>
<?php
	if($Config_allowed_size != "0")
	$MADE_strUploadRulesAdd = $csr->LangConvert($strUploadRulesAdd, "$Config_allowed_size $byteUnits[1]");

	echo ($csr->LangConvert($strUploadRules, $MADE_strUploadRulesAdd, $Config_allow_types_show));

?>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
<?php
	}

	else if($upload_sent != 1 && $aid)
	{

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strAlbumCrErr20, <a href=upload.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }

		if(!$showf || !is_numeric($showf) || $showf < 1 )
		{
		 if($showf > 20)
		 $showf = 20;

		 else
		 $showf = $Config_show_min;
	     }

		$i = 0;
	      $row = mysql_fetch_array( $result );

?>

<div align=right><?php echo $strUploadAdding ?> <b><?php echo (stripslashes($row[aname])); ?></b> <font size=1>(<a href="upload.php"><?php echo $strChange ?></a>)</font>&nbsp;&nbsp;</div>
<p>&nbsp;</p>  

<?php

if($showf > 4)
{
?>

<form action="upload.php" method="post">
<table width="70%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
  <tr bgcolor="#CCCCCC"> 
    <td> 
      <div align="center" class="tn"><?php echo $strShowFields ?> 
        <input type="text" name="showf" size="2" maxlength="2" class=fieldsf>
        <input type="hidden" name="aid" value="<?php echo $aid ?>">
	  <input type=submit name="submit" value="<?php echo $strGo ?>" class=butfieldc>
      </div>
    </td>
  </tr>
</table>                
</form>
<p>&nbsp;</p>
<?php
}

$strUploadAdding = urlencode($strUploadAdding);
$strUploadRelax = urlencode($strUploadRelax);
?>
<script>
<!--
function relaxUpload() 
{
relaxUpload=window.open("<?php echo("relax.php?l=$Config_LangLoad&s=$strUploadAdding&m=$strUploadRelax"); ?>","relaxUpload","status=no,resize=no,toolbar=no,scrollbars=no,width=350,height=200,maximize=no");

var WinWd, WinHt;
WinWd = screen.width/2 - 175;
WinHt = screen.height/2 - 100;
relaxUpload.moveTo(WinWd,WinHt);
}
//-->
</script>


<form enctype="multipart/form-data" action="upload.php" method="post">
<input type="hidden" name="upload_sent" value="1">
<table width="80%" border="0" cellspacing="2" cellpadding="3" align="center">
  <tr class=ts> 
    <td width="30%">&nbsp;</td>
	<td valign=top>
      <span class=impShow><?php echo $strUploadPosition ?> <select name=newWhere class=ts>
							     <option value=0><?php echo $strUploadPosition1 ?></option>
							     <option value=1 selected><?php echo $strUploadPosition2 ?></option>
						           </select> <?php echo "$strIn $strAlbum"; ?></span>
<p>
  </td>
  </tr>
  <tr> 
    <td width="30%">&nbsp;</td>
    <td><input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/upload.gif" width=53 height=19 border=0 onclick="relaxUpload()"><p></td>
  </tr>
<?php

	while($i < $showf)
	{
?>

  <tr> 
    <td width="30%" class="tn"> 
      <div align="right"><?php echo("$strPhoto ".($i+1)); ?>&nbsp;</div>
    </td>
    <td><input name="userfile[]" type=file></td>
  </tr>
  <tr> 
    <td width="30%" class="tn"> 
      <div align="right"><?php echo $strCaption ?>&nbsp;</div>
    </td>
    <td><input name="usermsg_<?php echo $i ?>" type=text maxlength=200></td>
  </tr>
  <tr> 
    <td width="30%">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

<?php
	$i++;
	}

?>

<tr><td>&nbsp;</td><td>
<input type="hidden" name="aid" value="<?php echo $aid ?>">
<input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/upload.gif" width=53 height=19 border=0 value="upload &gt;&gt;" onclick="relaxUpload()"></td></tr>
<tr><td colspan=3 class=tn height=2>&nbsp;<br></td></tr>
</table>
</form>

<div align=center><a href=upload.php>&lt;&lt; <?php echo ("$strChange ".strtolower($strAlbum)); ?></a></div>
<p>&nbsp;</p>  
<form action="upload.php" method="post">
<table width="70%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#000000">
  <tr bgcolor="#CCCCCC"> 
    <td> 
      <div align="center" class="tn"><?php echo $strShowFields ?>
        <input type="text" name="showf" size="2" maxlength="2" class=fieldsf>
        <input type="hidden" name="aid" value="<?php echo $aid ?>">
	  <input type=submit name="submit" value="<?php echo $strGo ?>" class=butfieldc>
      </div>
    </td>
  </tr>
</table>                
</form>

<?php
	}

else
{
	if($scall != "1")
	{
	if(!$aid)
  	{
       $errMsg = "<b>$strNo $strAlbum $strName, <a href=upload.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $errMsg = "<b>$strNo $strAlbum, <a href=upload.php>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
	      $row = mysql_fetch_array( $result );
		echo("<div align=right>$strUploadAdded <b>".stripslashes($row[aname])."</b>&nbsp;&nbsp;</div>");
	}
	else
	{
	      echo("<div align=right><b>$strProfilePhotoAdd</b>&nbsp;&nbsp;</div>");
	}

echo("<p>&nbsp;</p>");

if($scall != "1")
echo("<br><br><br><div align=center><a href=upload.php>&lt;&lt; $strBackAlbumSelect</a> ~ <a href=upload.php?aid=$aid>$strMenusAddMorePhotos</a></div>");

echo("<div align=center>");

$i = 0;
$j = 1;
$big_image = 0;
$big_image_size = 0;
$tb_image_size = 0;
$sused = 0;
$pused = 0;
$aused_space = 0;
$aused_pics = 0;

$pSucc = array();

$upfiles = 0;
$max_filesize = $Config_allowed_size * 1000;

echo("<p>&nbsp;</p>\n<table width=70% cellpadding=4 cellspacing=2 border=0 align=center>\n");

	while($userfile[$i])
	{
	if($userfile[$i] != "none")
	{
		if($userfile_size[$i] <= 0)
		{ $pSucc[$i] = 0; echo ("\n<tr>\n<td align=right>&nbsp;</td>\n<td class=tn align=center>".$csr->LangConvert($strUploadError1, $j)." <img src=".$dirpath.$Config_imgdir."/design/cross.gif></td>\n</tr>\n"); }

		else if(!in_array($userfile_type[$i], $Config_allow_types))
		{ $pSucc[$i] = 1; echo ("\n<tr>\n<td align=right>&nbsp;</td>\n<td class=tn align=center>".$csr->LangConvert($strUploadError2, $j)." <img src=".$dirpath.$Config_imgdir."/design/cross.gif></td>\n</tr>\n"); }

		else if($userfile_size[$i] > $max_filesize && $max_filesize != 0)
		{ $pSucc[$i] = 2;  echo ("<tr>\n<td align=right>&nbsp;</td>\n<td class=tn align=center>".$csr->LangConvert($strUploadError1, $j)." <img src=".$dirpath.$Config_imgdir."/design/cross.gif></td>\n</tr>\n"); }

		else
		{
		######################
		$result_user = queryDB( "SELECT pused FROM $tbl_userinfo WHERE uid = '$uid'" );
		$row_user = mysql_fetch_array( $result_user );
		$photos_used = $row_user[pused];

	 	$result_user = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
		$row_user = mysql_fetch_array( $result_user );
		list($slimit, $alimit, $plimit, $rlimit) = split('[|]', $row_user[limits]);
		$sused = $row_user[sused]; $pused = $row_user[pused];

		$spaceleft = ($slimit * 1000000) - ($sused + c);

		if($spaceleft < 100 && $slimit)
		{ echo("<tr>\n<td align=right>&nbsp;</td>\n<td class=tn align=center><img src=".$dirpath.$Config_imgdir."/design/cross.gif> ".$csr->LangConvert($strUploadError3, $j)."<font size=1>[<a href=\"$Config_buylink\">$strBuySentence</a>]</font></td>\n</tr>\n"); $err = 1; }

		######################
		else if($photos_used >= $plimit && $plimit)
		{ echo("<tr>\n<td align=right>&nbsp;</td>\n<td class=tn align=center><img src=".$dirpath.$Config_imgdir."/design/cross.gif> ".$csr->LangConvert($strUploadError4, $j)."<font size=1>[<a href=\"$Config_buylink\">$strBuySentence</a>]</font></td>\n</tr>\n"); $err = 1; }
	
		else
		{
		$msgvar = "usermsg_$i";

		if($HTTP_POST_VARS['newWhere'] == '0')
		{
			$result_index = queryDB( "UPDATE $tbl_pictures SET pindex=pindex+1 WHERE aid='$aid'" );
			$npindex = 1;
		}
		else
		{
			$result_index = queryDB( "SELECT MAX(pindex) FROM $tbl_pictures WHERE aid='$aid'" );
			$row_index = mysql_fetch_array( $result_index );

			$npindex = $row_index[0] + 1;
		}

		if($scall != "1")
		{
		$result = queryDB( "UPDATE $tbl_albumlist SET sused=sused+'$userfile_size[$i]', pused=pused+1 WHERE aid='$aid'" );
		$result = queryDB( "UPDATE $tbl_userinfo SET sused=sused+'$userfile_size[$i]', pused=pused+1 WHERE uid='$uid'" );
		$result = queryDB( "INSERT INTO $tbl_pictures VALUES(NULL, '$aid', '$aid', '$npindex', '".addslashes(htmlspecialchars(${$msgvar}))."','$userfile_size[$i]', '0', '0')" );
		}
		
		$npid = mysql_insert_id();
		if($userfile_type[$i] == "image/x-png")
		$ext = "png";
		else if($userfile_type[$i] == "image/gif")
		$ext = "gif";
		else if($userfile_type[$i] == "image/pjpeg" || $userfiel_type[$i] == "image/jpeg")
		$ext = "jpg";
		else if($userfile_type[$i] == "image/bmp")
		$ext = "bmp";
		else if($userfile_type[$i] == "image/pict")
		$ext = "pic";
		else if($userfile_type[$i] == "image/x-wmf")
		$ext = "wmf";
		else if($userfile_type[$i] == "image/x-macpaint")
		$ext = "mac";
		else
		$ext = "jpg";

		if($scall == "1")
		$picname = $uid.".".$ext;
		
		else
		$picname = $aid."_p".$npid."."."$ext";

		###### add to db #########
		$result = queryDB( "UPDATE $tbl_pictures SET pname='$picname' WHERE pid='$npid'" );

		if($scall == "1")
		{
		srand((double)microtime()*100);
		$randnum = rand();

		echo ("\n<tr>\n<td width=110 align=right><img src=".$dirpath."$Config_datapath/$uid/$picname?$randnum></td>\n<td class=tn align=center>$strProfilePhotoAdd <img src=".$dirpath.$Config_imgdir."/design/tick.gif></td>\n</tr>\n");
		}

		else
		echo ("\n<tr>\n<td width=110 align=right><a href=".$dirpath."showpic.php?aid=$aid&pid=$npid&uuid=$uid target=_blank><img src=".$dirpath."$Config_datapath/$uid/tb_$picname border=0 alt=\"view\"></a></td>\n<td class=tn align=center>".$csr->LangConvert($strUploadError5, $j)." <img src=".$dirpath.$Config_imgdir."/design/tick.gif></td>\n</tr>\n");

		if($uppic)
    	      unlink($dirpath.$Config_datapath."/".$uid."/".$uppic);

		if (is_uploaded_file($userfile[$i])) {
	      copy($userfile[$i], "$dirpath"."$Config_datapath/$uid/$picname"); }

	if($scall == "1")
	{
		error_reporting(0);
		$size = GetImageSize("$dirpath"."$Config_datapath/$uid/$picname");
		error_reporting(E_ERROR | E_WARNING);

		if($size[0] > $size[1])
		{ $wt = $Config_tbwidth_short;
		  $ht = $Config_tbheight_short; 

		  if($size[0] > $wt)
		  {
			$ratiosize = $size[0] / $size[1];
		 	$ht = $wt / $ratiosize;
	      	$ht = floor($ht);

			if($ht > $Config_tbheight_short)
			{
			$ratiosize = $wt / $ht;
		 	$wt = $Config_tbheight_short / $ratiosize;
	      	$wt = floor($wt);
			$ht = $Config_tbheight_short;
			}

			if($Config_ResizeBy == "1" || $Config_ResizeBy == "3")
			{
			$fileType = strtoupper($ext);
			$csr->ResizeImg($picname, $picname, $fileType, $wt, $ht, $uid);
			}

			else
		     virtual("$dirpath"."$Config_cgidir"."/albinator.cgi?uid=$uid&wt=$wt&ht=$ht&fn=$picname&sav=1&callwhat=reimageb");
		  }
		}

		else
		{ 
		  $wt = $Config_tbwidth_long;
		  $ht = $Config_tbheight_long; 

		  if($size[1] > $ht)
		  {
			$ratiosize = $size[0] / $size[1];
		 	$wt = $ht * $ratiosize;
	      	$wt = floor($wt);
	
			if($wt > $Config_tbwidth_long)
			{
			$ratiosize = $wt / $ht;
		 	$ht = $Config_tbwidth_long / $ratiosize;
	      	$ht = floor($ht);
			$wt = $Config_tbwidth_long;
			}

			if($Config_ResizeBy == "1" || $Config_ResizeBy == "3")
			{
			$fileType = strtoupper($ext);
			$csr->ResizeImg($picname, $picname, $fileType, $wt, $ht, $uid);
			}

			else
          virtual("$dirpath"."$Config_cgidir"."/albinator.cgi?uid=$uid&wt=$wt&ht=$ht&fn=$picname&sav=1&callwhat=reimageb");
		  }
	
	   }
	}

	else
	{
	 error_reporting(0);
	 $size = GetImageSize("$dirpath"."$Config_datapath/$uid/$picname");
	 error_reporting(E_ERROR | E_WARNING);

	 # to restrict the custom width max size
	 if($Config_exceed_width || $Config_exceed_height) // check if there are not zeros
	 {
	  if(!$Config_exceed_width)
	  $Config_exceed_width = $size[0];
	  if(!$Config_exceed_height)
	  $Config_exceed_height = $size[1];

	   if($size[0] > $Config_exceed_width || $size[1] > $Config_exceed_height)
	   { 
		$ratiosize = $size[0] / $size[1];
	 	$ht = $Config_exceed_width / $ratiosize;
	      $ht = floor($ht);
		$wt = $Config_exceed_width;

		if($ht > $Config_exceed_height)
		{
		#$ratiosize = $Config_exceed_width / $Config_exceed_height;
	 	$wt = $Config_exceed_height * $ratiosize;
	      $wt = floor($wt);
		$ht = $Config_exceed_height;
		}

		if($Config_forceSize == "1")
		{ $fullval = ""; $big_image = 0; }
	      else
		{ $fullval = "full_"; $big_image = 1; }

		if($Config_ResizeBy == "1" || $Config_ResizeBy == "3")
		{
		$fileType = strtoupper($ext);
		$csr->ResizeImg($picname, "$fullval".$picname, $fileType, $wt, $ht, $uid);
		}

		else
		virtual("$dirpath"."$Config_cgidir"."/albinator.cgi?uid=$uid&wt=$wt&ht=$ht&fn=$picname&callwhat=reimageb");

		$big_image = 1;		
	  }
      }
		error_reporting(0);
		$size = GetImageSize("$dirpath"."$Config_datapath/$uid/$picname");
		error_reporting(E_ERROR | E_WARNING);
		if($size[0] > $size[1])
		{ $wt = $Config_tbwidth_short;
		  $ht = $Config_tbheight_short; }
		else
		{ $wt = $Config_tbwidth_long;
		  $ht = $Config_tbheight_long; }
		

		if($Config_ResizeBy == "1" || $Config_ResizeBy == "3")
		{
		$fileType = strtoupper($ext);
		$csr->ResizeImg($picname, "tb_".$picname, $fileType, $wt, $ht, $uid);
		}
		else
		virtual("$dirpath"."$Config_cgidir"."/albinator.cgi?uid=$uid&wt=$wt&ht=$ht&fn=$picname&callwhat=reimage");

		$upfiles++;

		if($scall != "1")
		{
		if(preg_match("/A/", $Config_spaceScheme))
		$tb_image_size = filesize ($dirpath.$Config_datapath."/$uid/tb_$picname");
		if($big_image == 1 && preg_match("/B/", $Config_spaceScheme))
		$big_image_size = filesize ($dirpath.$Config_datapath."/$uid/full_$picname");
	 	else if($big_image == 1)
		$big_image_size = -1;

		$new_space = $csr->editSize( $npid, $uid, 'scheme', $tb_image_size, $big_image_size);

		$result = queryDB( "UPDATE $tbl_userinfo SET sused=sused+'$new_space' WHERE uid='$uid'" );
		$result = queryDB( "UPDATE $tbl_albumlist SET sused=sused+'$new_space' WHERE aid='$aid'" );

	      $result = queryDB( "UPDATE $tbl_pictures SET i_used='$big_image_size', t_used='$tb_image_size' WHERE pid='$npid'");
		}

		$big_image = 0;
		$big_image_size = 0;
		$tb_image_size = 0;
	      $sused = 0;
	      $pused = 0;
	      $aused_space = 0;
	      $aused_pics = 0;
		}
	    }
	  }
	}

	$i++;	$j++;
	}
?>

</table>

</div>

<?php
if($upfiles == 0 && $scall != "1")
echo("<br><br><div align=center>".$csr->LangConvert($strUploadError6, "No")."&nbsp;&nbsp;&nbsp;</div>");
else if($scall != "1")
echo("<br><br><div align=center>".$csr->LangConvert($strUploadError6, $upfiles).", <a href=".$dirpath."showalbum.php?aid=$aid&uuid=$uid target=_blank>$strView $strAlbum</a></div><p>&nbsp;</p>");

if($scall != "1" && $upfiles > 5)
echo("<br><br><br><div align=center><a href=upload.php>&lt;&lt; $strBackAlbumSelect</a> ~ <a href=upload.php?aid=$aid>$strMenusAddMorePhotos</a></div><br><br>");

else if($scall == "1")
{
srand((double)microtime()*100);
$randnum = rand();

echo("<div align=center class=tn><a href=".$dirpath."user/userprofile.php?dowhat=show&rand=$randnum>&lt;&lt;  $strMenusMyprofile</a></div><p>&nbsp;</p>"); 


	  if($pSucc[0] == 0)
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
		{ $profilenew .= "0"."*".$picname."|"; $found = "1"; }
	    }

		if($found != "1")
		$profilenew .= "0"."*".$picname."|";

		$profilenew = ereg_replace ("\|\*\|", "\|", $profilenew);
		$result_up = queryDB( "UPDATE $tbl_userinfo SET profile='$profilenew' WHERE uid='$uid'" );
	   }
}

$strUploadAdding = urlencode($strUploadAdding);
$strUploadRelax = urlencode($strUploadRelax);
?>
<script>
<!--
function relaxUpload() 
{
relaxUpload=window.open("<?php echo("relax.php?l=$Config_LangLoad&s=$strUploadAdding&m=$strUploadRelax"); ?>","relaxUpload","status=no,resize=no,toolbar=no,scrollbars=no,width=350,height=200,maximize=no");

var WinWd, WinHt;
WinWd = screen.width/2 - 175;
WinHt = screen.height/2 - 100;
relaxUpload.moveTo(WinWd,WinHt);
}

relaxUpload();
relaxUpload.close();
self.focus();
//-->
</script>
<?php
}

$usr->Footer();
exit;

?>
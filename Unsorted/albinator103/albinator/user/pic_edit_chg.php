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
   
 	    closeDB();
          exit;
      }

	if($send_url)
	{ $sendurl = "$send_url?aid=$aid"; }

	if(!$aid)
  	{
	 $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr1, <a href=$sendurl>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 closeDB();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
	 $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr4, <a href=$sendurl>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE aid = '$aid'" );
	$pic_nr = mysql_num_rows( $result );
	if(!$pic_nr)
  	{
	 $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr19, <a href=$sendurl>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);

	if(!$change)
	{
	$result_temp = queryDB( "SELECT COUNT(pid) FROM $tbl_pictures WHERE aid = '$aid'" );
	$row_nr      = mysql_fetch_array( $result_temp );

	for($i=1;$i<=$row_nr[0];$i++)
	{
		$pid_temp = "pid$i";
		$pid = ${$pid_temp};

		$pid_check_temp = "new_pcheck$pid";
	
		if(${$pid_check_temp} == "1")
		{
			$flag = true;
			break;
		}
	}

	if($flag != true)
	{
	 $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr18, <a href=$sendurl>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
	}
	}

if($change)
{
	for($i=1;$i<=$pic_nr;$i++)
	{
		$new_pindex = "new_pindex$i";
		$new_pmsg = "new_pmsg$i";
		$pid = "pid$i";

		if(${$new_pmsg})
		${$new_pmsg} = strip_tags(${$new_pmsg}, '<b><i>');

		if(!${$new_pindex})
		{ ${$new_pindex} = "0"; }	

		$result = queryDB( "UPDATE $tbl_pictures SET pindex='${$new_pindex}', pmsg='".addslashes(htmlspecialchars(${$new_pmsg}))."' WHERE pid='${$pid}' && aid ='$aid'" );		
	}
}

else if($move || $copy)
{
	if($move)
	$chg = "move";
	else
	$chg = "copy";

	if($l_confirm != '1')
	{
	$result = queryDB( "SELECT pused FROM $tbl_albumlist WHERE aid='$aid'" );
	$row = mysql_fetch_array( $result );
	if(!$row[pused])
  	{
	 $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr20, <a href=$sendurl>$strRetry</a></b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);

	$result = queryDB( "SELECT COUNT(*) as nr FROM $tbl_albumlist WHERE uid = '$uid'" );
	$row = mysql_fetch_array( $result );
	if($row[nr] < 2)
  	{
	 $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strPhotoMoveNotify</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid' && aid != '$aid'" );
	while($row = mysql_fetch_array ( $result ))
	{
		$album_list .= "<option value=\"$row[aid]\">$row[aname]</option>\n";
	}

	$usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");

?>
<br><br>
<form method=post action=pic_edit_ot.php>
	<table width="70%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#aaaaaa">
	  <tr bgcolor="#dddddd"> 
	    <td class=tn> 
	      <div align="center"><?php if($chg == "move") echo($strMove); elseif($chg == "copy") echo($strCopy); echo(" $strPhoto: "); ?>
	        <select name="new_aid">
		  <?php echo($album_list); ?>
	        </select>
		  <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/save.gif" width="53" height="19" border="0">
		<br><br>
<?php

	$result_temp = queryDB( "SELECT COUNT(pid) FROM $tbl_pictures WHERE aid = '$aid'" );
	$row_nr      = mysql_fetch_array( $result_temp );

	$j = 1;

for($i=1;$i<=$row_nr[0];$i++)
{
	$pid_temp = "pid$i";
	$pid = ${$pid_temp};

	$pid_check_temp = "new_pcheck$pid";
	
	if(${$pid_check_temp} == "1")
	{
		$result_orig = queryDB( "SELECT pname FROM $tbl_pictures WHERE pid = '$pid' && aid = '$aid'" );
		$row_orig = mysql_fetch_array ( $result_orig );

		echo("\n<img src='$dirpath"."$Config_datapath/$uid/tb_$row_orig[pname]'>&nbsp;");
		echo("\n<input type='hidden' name='pid$i' value='$pid'>");

		if($j == 4)
		{ $j = 1; echo("<br>"); }
		else
		$j++;
	}
}

?>
		  <br><br>
	        <input type="hidden" name="sf" value="<?php echo $sf ?>">
	        <input type="hidden" name="aid" value="<?php echo $aid ?>">
	        <input type="hidden" name="l_confirm" value="1">
	        <input type="hidden" name="chg" value="<?php echo($chg); ?>">
		  <input type="hidden" name="send_url" value="<?php echo $send_url ?>">
	      </div>
	    </td>
	  </tr>
	</table>
</form>

<?php
 	mysql_free_result($result);
      $usr->Footer();

      closeDB();
	exit;
	}
}


else if($delete)
{
	if($l_confirm != '1')
	{
		$result_temp = queryDB( "SELECT COUNT(pid) FROM $tbl_pictures WHERE aid = '$aid'" );
		$row_nr      = mysql_fetch_array( $result_temp );

		for($i=1;$i<=$row_nr[0];$i++)
		{
			$pid_temp = "pid$i";
			$pid = ${$pid_temp};

			$pid_check_temp = "new_pcheck$pid";
	
			if(${$pid_check_temp} == "1")
			{
				$result_orig = queryDB( "SELECT pname FROM $tbl_pictures WHERE pid = '$pid' && aid = '$aid'" );
				$row_orig = mysql_fetch_array ( $result_orig );

				$pid_list .= "&pid$i=$pid";
			}
		}

		$usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
            echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
	      $errMsg = "<b>".$csr->LangConvert($strDelConfirm, "$strPhoto$strPuralS")."<br><div align=center><a href=pic_edit_ot.php?aid=$aid&send_url=$send_url&l_confirm=1&chg=del&sf=$sf$pid_list>$strYes</a> :: <a href=$sendurl>$strNo</a></b></div>\n";
	      $usr->errMessage( $errMsg, $strWarning, 'error', '70' );
		echo('<br>');
            $usr->Footer();
		exit;
	}
}

	      closeDB();
     	      $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing", '1', $sendurl."&sf=$sf");
	      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
	      $errMsg = "<b>$strAlbumCrErr22, $strRedirecting...</b><br>$strElse <a href=$sendurl"."&sf=$sf>$strClickhere</a>\n";
	      $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
		echo("<BR>");
            $usr->Footer();
		exit;
?>
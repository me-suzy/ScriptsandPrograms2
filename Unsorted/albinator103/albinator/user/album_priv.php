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
	{ 
	   $sendurl = "$send_url.php";
	   $i=1;

	   $parray = split ('[|]', $params);

	   foreach ($parray as $pairval) 
	   {	   
	    list ($pkey, $pvalue) = split ('[-]', $pairval);

	    if($i > 1)
	    $sendurl .= "&";
	    else
	    $sendurl .= "?";

	    $sendurl .= "$pkey=$pvalue";
	    $i++;
	   }
      }

	if(!$aid)
  	{
       $usr->Header($Config_SiteTitle ." :: strAlbum strPrivate");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/apriv.gif>&nbsp;</div><br>");
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
       $usr->Header($Config_SiteTitle ." :: $strAlbum $strPrivate");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/apriv.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr4, <a href=$sendurl>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);


	if($al_confirm == 1)
	{
		$al_pass_len = strlen($al_pass);

		if(!$al_pass_len || $al_pass_len < 6 || $al_pass_len > 15)
  		{
	       $usr->Header($Config_SiteTile." :: $strAlbum $strPrivate");
	       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/apriv.gif>&nbsp;</div><br>");
	       $errMsg = "<b>$strLessPass</b>\n";
	       $usr->errMessage( $errMsg, $strError, 'error', '60' );
	       $al_confirm = 0;
		 $err = 1;
	      }
	}

	#ask form

	if($chg == "pub")
      {
		$result=queryDB( "UPDATE $tbl_albumlist SET password='0', private='0' WHERE aid='$aid'" );
		
	   	closeDB();
    
     	      $usr->Header($Config_SiteTile." :: $strAlbum $strPrivate", '1', $sendurl);
	      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/apriv.gif>&nbsp;</div>");
		echo("<br>");
	      $errMsg = "<b>$strAlbumCrErr14, $strRedirecting...</b><br>$strElse <a href=$sendurl>$strClickhere</a>\n";
	      $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
            $usr->Footer();
		exit;
	}


	if($al_confirm != 1)
	{
     	      if(!$err) { $usr->Header($Config_SiteTitle ." :: $strAlbum $strPrivate", '', '', 'onload');
	      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/apriv.gif>&nbsp;</div>"); }

?>
<br><br>
<form method=post action=album_priv.php>
  <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#666666">
    <tr><td align=right class=ts><font color=#ffffff><?php echo ("$strLessPass"); ?>&nbsp;</font></td></tr>
    <tr bgcolor="#dddddd"> 
      <td> 
	 <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center"><tr><td class=tn>
        <div align="right"> 
          password: <input type="password" name="al_pass" maxlength=15 class=fieldsnorm>
          <input type="hidden" name="aid" value="<?php echo $aid ?>">
	    <input type="hidden" name="send_url" value="album_edit">
	    <input type="hidden" name="al_confirm" value="1">
	    <input type="hidden" name="params" value="<?php echo $params ?>">
          <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/change.gif" width="53" height="19" border="0">
	  </div>
	 </td></tr></table>
      </td>
    </tr>
  </table>
</form>

<?php
	   	closeDB();
            $usr->Footer();
		exit;
    
	}

	else
      {
		$enc_pass = md5($al_pass);
		$result=queryDB( "UPDATE $tbl_albumlist SET password='$enc_pass', private='1' WHERE aid='$aid'" );
		
	   	closeDB();
    
     	      $usr->Header($Config_SiteTile." :: $strAlbum $strPrivate", '1', $sendurl);
	      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/apriv.gif>&nbsp;</div>");
	      $errMsg = "<b>$strAlbumCrErr14, $strRedirecting...</b><br>$strElse <a href=$sendurl>$strClickhere</a>\n";
		echo("<br>");
	      $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
            $usr->Footer();
		exit;
	}
?>
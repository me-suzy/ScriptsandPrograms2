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

	if($send_url)
	{ $sendurl = "$send_url?aid=$aid"; }

	if(!$aid)
  	{
	 $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr1, <a href=$sendurl>$strRetry</a></b>\n";
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

# movform show

	$result_temp = queryDB( "SELECT COUNT(pid) FROM $tbl_pictures WHERE aid = '$aid'" );
	$row_nr      = mysql_fetch_array( $result_temp );

	for($i=1;$i<=$row_nr[0];$i++)
	{
		$pid_temp = "pid$i";
		$pid = ${$pid_temp};

		if($pid)
		{
			$result_orig = queryDB( "SELECT COUNT(*) FROM $tbl_pictures WHERE pid = '$pid' && aid = '$aid'" );
			$row_orig = mysql_fetch_array ( $result_orig );

			if($row_orig[0])
			{
				if($chg == "move" && $l_confirm == 1)
				$csr->editSize( $pid, $uid, 'move', $new_aid );

				else if($chg == "copy" && $l_confirm == 1)
				$csr->editSize( $pid, $uid, 'copy', $new_aid );

				else if($chg == "del" && $l_confirm == 1)
				$csr->editSize( $pid, $uid, 'del' );
			}
		}
	}

      closeDB();

		$sendurl = "$sendurl&done=1";

     	      $usr->Header($Config_SiteTitle ." :: $strPhoto $strEditing", '1', $sendurl."&sf=$sf");
            echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/psettings.gif>&nbsp;</div><br>");
	      $errMsg = "<b>$strAlbumCrErr14, $strRedirecting...</b><br>else <a href=$sendurl"."&sf=$sf>$strClickhere</a>\n";
	      $usr->errMessage( $errMsg, $strSuccess, 'tick', '70' );
            $usr->Footer();
		exit;
?>

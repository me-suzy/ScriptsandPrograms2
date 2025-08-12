<?php
	$dirpath = "$Config_rootdir"."../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();


	if($newalbum)
	{ $newalbum = strip_tags($newalbum, '<b><i>'); }
	if($new_amsg)
	{ $newmessage = strip_tags($newmessage, '<b><i>'); }


    	if ( !$ucook->LoggedIn() )
      {
          $usr->HeaderOut();
	    $csr->customMessage( 'logout' );
	    $usr->FooterOut();
   
          exit;
      }

	if(!$newalbum)
  	{
       $usr->Header($Config_SiteTitle .' :: '.$strAlbum." ".$strCreate);
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/acreate.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr1, <a href=$send_url>$strBack</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid' && aname = '".addslashes(htmlspecialchars($newalbum))."'" );
	$nr = mysql_num_rows( $result );
	if($nr)
  	{
       $usr->Header($Config_SiteTitle .' :: '.$strAlbum." ".$strCreate);
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/acreate.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr2, <a href=$send_url>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);


	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row = mysql_fetch_array($result);
	list($slimit, $alimit, $plimit, $rlimit) = split('[|]', $row[limits]);
	mysql_free_result( $result );

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	$aused = mysql_num_rows( $result );

	if($aused >= $alimit && $alimit)
	{
       $usr->Header($Config_SiteTitle .' :: '.$strAlbum." ".$strCreate);
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/acreate.gif>&nbsp;</div><br>");
       $errMsg = "<b>".$csr->LangConvert($strCrossLimit, strtolower($strAlbum).$strPuralS)."</b> [<a href=\"$Config_buylink\">$strBuySentence</a>] or <a href=$send_url>$strBack</a>...</b><br><br>\n";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
	 echo ("<br>");
   	 $usr->Footer();

	 closeDB();
	 exit;
	}

	if($al_private)
	{ $al_pass_len = strlen($al_pass);
	  $al_pass_enc = md5($al_pass);
	  if($al_pass_len < 6 || $al_pass_len > 15)
	  {
	       $usr->Header($Config_SiteTitle .' :: '.$strAlbum." ".$strCreate);
	       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/acreate.gif>&nbsp;</div><br>");
       	 $errMsg = "<b>$strLessPass <a href=$send_url>$strRetry</a>...</b>\n";
	       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
	   	 $usr->Footer();

	  	 closeDB();
		 exit;
         }
       }	  

	if($al_private)
	{ $result = queryDB( "INSERT INTO $tbl_albumlist VALUES('".addslashes(htmlspecialchars($newalbum))."', NULL, '$uid', '$al_pass_enc', '1', '".addslashes(htmlspecialchars($newmessage))."', '$now_date', '0', '0')" ); }
	else
	{ $result = queryDB( "INSERT INTO $tbl_albumlist VALUES('".addslashes(htmlspecialchars($newalbum))."', NULL, '$uid', '0', '0', '".addslashes(htmlspecialchars($newmessage))."', '$now_date', '0', '0')" ); }

	closeDB();
     
	    $send_url = $send_url."?addconf=1";

	    $usr->Header($Config_SiteTitle .' :: '.$strAlbum." ".$strCreate, '1', $send_url);
	    echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/acreate.gif>&nbsp;</div><br>");
	    $errMsg = "<b>$strAlbumCrErr3, $strRedirecting...</b><br>$strElse <a href=$sendurl>$strClickhere</a>\n";
	    $usr->errMessage( $errMsg, $strSuccess, 'tick' );
          $usr->Footer();

?>

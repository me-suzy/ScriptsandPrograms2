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
       $usr->Header($Config_SiteTitle .' :: '. $strAlbum.' '.$strEditing);
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/edit.gif>&nbsp;</div><br>");
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
       $usr->Header($Config_SiteTitle .' :: '. $strAlbum.' '.$strEditing);
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/edit.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr4, <a href=$sendurl>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);

	if($del_confirm == 1)
	{
		$result_pics = queryDB( "SELECT pid FROM $tbl_pictures WHERE aid = '$aid'" );
		while ($row = mysql_fetch_array ( $result_pics ))
		{					
			$csr->editSize( $row[pid], $uid, 'del', '1' );
		}
		mysql_free_result ( $result_pics );
		$result = queryDB( "DELETE FROM $tbl_albumlist WHERE aid = '$aid'" );
    
     	      $usr->Header($Config_SiteTitle .' :: '. $strAlbum.' '.$strEditing, '1', $sendurl);
            echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/edit.gif>&nbsp;</div><br>");
	      $errMsg = "<b>$strAlbumCrErr5, $strRedirecting...</b><br>$strElse <a href=$sendurl>$strClickhere</a>\n";
	      $usr->errMessage( $errMsg, $strSuccess, 'tick' );
            $usr->Footer();
	}

	else
	{
	     	closeDB();
	      $usr->Header($Config_SiteTitle .' :: '. $strAlbum.' '.$strEditing);
            echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/edit.gif>&nbsp;</div><br>");
	      $errMsg = "<b>$strAlbumDelConfirm<br><div align=center><a href=album_del.php?aid=$aid&send_url=$send_url&del_confirm=1&params=$params>$strYes</a> :: <a href=$sendurl>$strNo</a></b></div>\n";
	      $usr->errMessage( $errMsg, $strWarning, 'error', '70' );
            $usr->Footer();
	}

?>

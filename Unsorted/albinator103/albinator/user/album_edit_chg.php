<?php
	$dirpath = "$Config_rootdir"."../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();

	if($new_alname)
	{ $new_alname = strip_tags($new_alname, ''); }
	if($new_amsg)
	{ $new_amsg = strip_tags($new_amsg, ''); }

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

	if(!$aid || !$new_alname)
  	{
       $usr->Header($Config_SiteTitle .' :: '.$strAlbum.' '.$strEditing);
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
       $usr->Header($Config_SiteTitle .' :: '.$strAlbum.' '.$strEditing);
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/edit.gif>&nbsp;</div><br>");
       $errMsg = "<b>$strAlbumCrErr4, <a href=$sendurl>$strRetry</a>...</b>\n";
       $usr->errMessage( $errMsg, 'Error' );
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);

	#change name

		$result = queryDB( "UPDATE $tbl_albumlist SET aname='".addslashes(htmlspecialchars($new_alname))."', amsg='".addslashes(htmlspecialchars($new_amsg))."' where aid = '$aid'" );
	   	closeDB();
    
     	      $usr->Header($Config_SiteTitle .' :: '.$strAlbum.' '.$strEditing, '1', $sendurl);
	      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/edit.gif>&nbsp;</div><br>");
	      $errMsg = "<b>$strAlbumCrErr14, $strRedirecting...</b><br>$strElse <a href=$sendurl>$strClickhere</a>\n";
	      $usr->errMessage( $errMsg, $strSuccess, 'tick', '60' );
            $usr->Footer();
?>
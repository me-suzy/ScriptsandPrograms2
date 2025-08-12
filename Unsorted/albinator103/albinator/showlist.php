<?php

	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();

      if ( !$ucook->LoggedIn() )
	{ $ShowHeader = "HeaderOut"; $ShowFooter = "FooterOut"; }
	else
	{ $ShowHeader = "Header"; $ShowFooter = "Footer"; }

	if($dowhat != "user" && $dowhat != "realname" && $dowhat != "email" && $dowhat != "country")
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strInvalid dostate</b>\n";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
   	 $usr->$ShowFooter();

	 exit;
      }
	else if(!$uuid && $dowhat == "user" || !$email_id && $dowhat == "email" || !$real_name && $dowhat == "realname" || !$country_id && $dowhat == "country")
	{
	 if($dowhat == "email")
	 $strINV = $strEmail;
	 else if($dowhat == "realname")
	 $strINV = $strName;
	 else
	 $strINV = $strUsername;

       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strInvalid $strINV</b>\n";
       $usr->errMessage( $errMsg, '', 'error', '70' );
   	 $usr->$ShowFooter();

	 exit;
      }

	if($dowhat == "realname" || $dowhat == "country")
	{
	if($dowhat == "realname")
	{
		if($bool == '2')
		{
		$search_terms = explode(" ", $real_name);
		$whereinfo    = "uname like '%";
		$whereinfo2   = "%'";
		}
		else
		{
		$search_terms = array("$real_name");
		$whereinfo    = "uname = '";
		$whereinfo2	  = "'";
		}
	}
	else
	{
	$search_terms = array("1");
	}

	$i = 0; $j = 0;
	$done_uid  = array();

	   while($search_terms[$i])
	   {
	   if($dowhat == "realname")
	   $result = queryDB( "SELECT uid,uname,pused,adddate FROM $tbl_userinfo WHERE $whereinfo$search_terms[$i]$whereinfo2" );
	   else
	   $result = queryDB( "SELECT uid,uname,pused,adddate FROM $tbl_userinfo WHERE country='$country_id'" );

		while($row = mysql_fetch_array( $result ))
		{
			if(!in_array($row[uid], $done_uid))
			{
		      $result_alb_temp = queryDB( "SELECT COUNT(*) as nr FROM $tbl_albumlist WHERE uid='$row[uid]'" );
			$row_alb_temp    = mysql_fetch_array( $result_alb_temp );

			if($j % 2 == 0)
			$rowcolor = "#dddddd";
			else
			$rowcolor = "#eeeeee";
	
			$result_lnk .= "<tr bgcolor='$rowcolor'><td><a href=\"showprofile.php?uuid=$row[uid]\">$row[uname]</a></td>\n<td>$row_alb_temp[nr]</td>\n<td>$row[pused]</td><td>".$csr->DisplayDate($row[3])."</td><td align='right'>[<a href='showlist.php?dowhat=user&&uuid=$row[uid]'>$strView</a>]&nbsp;</td></tr>\n";

			$done_uid[$j] = $row[uid];
			$j++;
			}
		}	
	   $i++;
	   }

	if($result_lnk)
	{
		$result_lnk = "<tr bgcolor='#CCCCCC' style='font-weight: bold'><td>$strName</td>\n<td>$strAlbum$strPuralS</td>\n<td>".ucwords($strPhoto)."$strPuralS</td>\n<td>$strCreated</td>\n<td>&nbsp;</td>\n</tr>\n$result_lnk";
		$result_lnk = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\" class='ts'>\n$result_lnk</table>";
	}

      $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");

	if($j > 1)
	$s = $strPuralS;
?>
	<br><p align="center" class="tn"><? echo("..: $j $strUser$s $strFound :..</b><br><a href=\"show.php\" class=\"ts\">$strShow13</a><br>"); ?><br><br></p>
	<table width="85%" border="0" cellspacing="0" cellpadding="2" align="center">
	 <tr>
	   <td>

<? echo($result_lnk); ?>

	     </td>
          </tr>
       </table>

<?
	$usr->$ShowFooter();
	exit;
	}

	else if($dowhat == "user")
	{
	$result = queryDB( "SELECT status FROM $tbl_userinfo WHERE uid = '$uuid'" );
	$nr = mysql_num_rows( $result );
	$row = mysql_fetch_array( $result );
	if(($row[status] != '2' && $row[status] != '1') || !$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strAlbumCrErr24</b>\n";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }
	else
	{
		if($row[status] == '2')
		{
	       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbums");
	       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
		 echo("<BR>");
	       $errMsg = "<b>$strShow3</b>\n";
	       $usr->errMessage( $errMsg, $strSorry, 'error', '65' );
	   	 $usr->$ShowFooter();

		 closeDB();
		 exit;
		}
	}

	mysql_free_result( $result );
	}

	else if(dowhat == "email")
	{
	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE email = '$email_id' && status = '1'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strAlbumCrErr24</b>\n";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }

	mysql_free_result( $result );
	}

	if($dowhat == "user")
	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uuid'" );
	else
	{ 
	  $result = queryDB( "SELECT * FROM $tbl_userinfo WHERE email = '$email_id'" );
	  $row = mysql_fetch_array( $result );
	  mysql_free_result( $result );
	  $result =  queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$row[uid]'" );
	}

	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
	 if($dowhat == 'email')
       $errMsg = "<b>$strAlbumCrErr24</b>\n";
	 else
       $errMsg = "<b>$strShowError2</b>\n";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }
	mysql_free_result( $result );

	if($dowhat == "user")
	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uuid' && prefs like '%L%'" );
	else
	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE email = '$email_id' && prefs like '%L%'" );

	$nr = mysql_num_rows( $result );
	if(!$nr)
  	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShow4</b>\n";
       $usr->errMessage( $errMsg, $strNote, 'error', '70' );
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }
	$row_orig = mysql_fetch_array( $result );
	mysql_free_result( $result );

	if($dowhat == "user")
	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uuid'" );

	else
	{ 
        $result = queryDB( "SELECT * FROM $tbl_userinfo WHERE email = '$email_id'" );
	  $row = mysql_fetch_array( $result );
	  mysql_free_result( $result );
	  $result =  queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$row[uid]'" );
	}

      $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
	
?>

			<br>
                  <p align="center" class="tn"><? echo("$strPublic $strAlbum$strPuralS :: <b>$row_orig[uname]</b>, (<a href=showprofile.php?uuid=$row_orig[uid]>$strProfile</a>)"); ?><br>
                    <br>
                  </p>
                  <table width="85%" border="0" cellspacing="0" cellpadding="2" align="center">
                    <tr>
                      <td>
<?

		$j = 0;
		while($row = mysql_fetch_array( $result ))
		{
		if(preg_match("/L/", $row_orig[prefs]) && $row[private] != "1" || preg_match("/l/", $row_orig[prefs]))
		{
			if($j % 2 == 0)
			$rowcolor = "#dddddd";
			else
			$rowcolor = "#eeeeee";
	
			if($row[private] == '1')
			$album_status = $strPrivate;
			else
			$album_status = $strPublic;

			$result_lnk2 .= "<tr bgcolor='$rowcolor'><td><a href=\"showalbum.php?aid=$row[aid]&uuid=$row[uid]\">".stripslashes($row[aname])."</a></td>\n<td>$row[pused]</td>\n<td>$album_status</td>\n<td>".$csr->DisplayDate($row[cdate])."</td><td align='right'>[<a href=\"showalbum.php?aid=$row[aid]&uuid=$row[uid]\">$strView</a>]&nbsp;</td></tr>\n";

			$show_done = 1;
		}

		$j++;
		}


		if($result_lnk2)
		{
			$result_lnk2 = "<tr bgcolor='#CCCCCC' style='font-weight: bold'><td>$strAlbum</td>\n<td>".ucwords($strPhoto)."$strPuralS</td>\n<td>$strAccess</td>\n<td>$strCreated</td>\n<td>&nbsp;</td>\n</tr>\n$result_lnk2";
			$result_lnk2 = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" align=\"center\" class='ts'>\n$result_lnk2</table>";
		}

		echo($result_lnk2);
?>
			   </td>
                    </tr>
                  </table>
<? 
   if($show_done != 1)
   echo("<p align=center class=tn><b>$strShow5</b></p>");

   echo("<p align=\"center\" class=\"ts\">\n");

// if($private == 1) echo "$strShow6";
   if(preg_match("/L/", $row_orig[prefs])) echo "$strShow6";

   echo("</p><p>&nbsp;</p>");
				  
                
$usr->$ShowFooter();

?>
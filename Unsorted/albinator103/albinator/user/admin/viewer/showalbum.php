<?php
	$dirpath = "$Config_rootdir"."../../../";
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
	else
	{ $ShowHeader = "Header"; $ShowFooter = "Footer"; }

 	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid = '$uid' && admin !='0'" );
	$nr = mysql_num_rows( $result );

	if(!$nr)
	{
	 if($Config_makelogs == "1")
	 { $csr->MakeAdminLogs( $uid, "Denied Access to the Admin Panel :: $SCRIPT_NAME", "2"); }

       $usr->Header($Config_SiteTitle ." :: $strAdminstration");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/admin.gif>&nbsp;</div><br>");
       $csr->customMessage( 'noadmin' );
   	 $usr->Footer();

	 mysql_free_result( $result );	

	 closeDB();
	 exit;
	}

	mysql_free_result( $result );	
	if(!$aid || !$username)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError1</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->FooterOut();

	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$username' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError2</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->FooterOut();

	 closeDB();
	 exit;
      }
	mysql_free_result( $result );

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$username' && aid = '$aid'" );

	$nr = mysql_num_rows( $result );
	$row = mysql_fetch_array( $result );

	if($sendurl == "view")
	{ $fieldadd = "<input type=hidden name=ppid value=$ppid><input type=hidden name=sendurl value=view>"; }

	mysql_free_result( $result );
	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$username'" );
	$row_orig = mysql_fetch_array( $result );
	$realname = $row_orig[uname];
	mysql_free_result( $result );

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE aid='$aid' ORDER BY pindex, pname" );
	$nr = $row[pused];

	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>".$csr->LangConvert($strShowError3, "username=$username")."</b>\n";
       $usr->errMessage( $errMsg, '' );
   	 $usr->FooterOut();
	 exit;
	}

	if($sendurl == "view")
	{ 

$sendtourl = "showpic.php?aid=$aid&pid=$ppid&username=$uid"; 
Header("Location: $sendtourl");

	  
echo <<< _HTML_END_

<div align="center" style="font-family: Verdana; font-size: 10pt; font-weight: bold; color: #990000;">
Showing Pictures, please wait ...
<div>

_HTML_END_;

exit;
	}

	$icon_links="<img src=\"{$dirpath}$Config_imgdir/design/icon_back.gif\" width=\"16\" height=\"16\" border=\"0\">&nbsp;<img src=\"{$dirpath}$Config_imgdir/design/icon_front.gif\" width=\"16\" height=\"16\" border=\"0\">&nbsp;";
      $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum", '','','','',$icon_links );
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
	
	$albumname = stripslashes(ucwords($row[aname]));
	if($row[amsg] != 0 || $row[amsg])
	{ $albummsg = "\"$row[amsg]\""; $brkl = "<br>"; }

	if($nrback != 1)
	$s = $strPuralS;

	if($row[cdate])
	{
		$reg_year = substr($row[cdate], 0, 4);
		$reg_month = substr($row[cdate], 4, 2);
		$reg_date = substr($row[cdate], 6, 2);
		$cdate = "$strCreated: ".date ("M d, Y", mktime (0,0,0,$reg_month,$reg_date,$reg_year))."<br><br>";
	}

	$rs = new PagedResultSet("SELECT * FROM $tbl_pictures WHERE aid='$aid' ORDER BY pindex, pname",$Config_maxshow);
	$nrback = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav("username=$username&aid=$aid");

	if($nav)
	$nav2 = "(".rtrim($nav).")";

	echo("<div class=ts align=center><br>\n$nrback $strPhoto$s $nav2<br>$strAlbum <b>$albumname</b>, $cdate $strOwner: <a href=\"($dirpath)showprofile.php?uuid=$username\">$realname</a><p><span class=\"tn\"><i>".stripslashes($albummsg)."</i></span></div>\n<p>&nbsp;</p>\n");
	echo("<div class=tn align=center>\n<table align=center width=600 cellpadding=4 cellspacing=4>\n");

	$result_user = queryDB( "SELECT prefs FROM $tbl_userinfo WHERE uid = '$username'" );
	$row_user = mysql_fetch_array( $result_user );

	if (preg_match("/B/", $row_user[prefs]))
	$borderval = "border=1";
	else
	$borderval = "border=0";

	$i = -1;
      $total = 0;

//	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE aid='$aid' ORDER BY pindex, pname LIMIT $rst,$Config_maxshow");

		while($row = $rs->fetchArray())
		{
	 	 $total++;
		 if($i == 3 || $i == -1)
		 { $i = 0; echo("\n<tr>"); $messagebar = "\n<tr>\n"; }
		 else
		 { $i++; }
		
		 $picurl = "$dirpath"."$Config_datapath/$username/tb_$row[pname]";
		 error_reporting(0);
   	       $size = GetImageSize ("$picurl");
		 error_reporting(E_ERROR | E_WARNING);
		 $width = $size[0];
		 $height = $size[1];

  		 echo("\n<td align=center valign=bottom><a href=\"showpic.php?aid=$aid&username=$username&pid=$row[pid]\"><img src=\"$picurl\" $borderval $size[3]></a></td>");
		 $messagebar .= "\n<td class=ts align=center valign=bottom><a href=\"showpic.php?aid=$aid&username=$username&pid=$row[pid]\">$strView</a></td>";

		 if($i == 3)
		 { echo("\n</tr>$messagebar\n</tr><tr><td colspan=4 height=4>&nbsp;</td></tr>\n"); }
		}

	if($total < 4)
	{ echo("\n</tr>$messagebar\n</tr>"); }
	else if($total%4 != 0)
	{ 
	  $i++;
	  if($i%2 == 0)
	  { echo("\n<td colspan=2>&nbsp;</td>\n</tr>$messagebar<td colspan=2>&nbsp;</td>\n</tr>"); }
	  else if($i%3 == 0)
	  { echo("\n<td>&nbsp;</td>\n</tr>$messagebar\n<td>&nbsp;</td>\n</tr>"); }
	  else
	  { echo("\n<td colspan=3>&nbsp;</td>\n</tr>$messagebar\n<td colspan=3>&nbsp;</td>\n</tr>"); }
      }

	echo("\n\n</table>\n\n</div>\n");
	echo("<div class=tn align=center><p>&nbsp;</p><span class='ts'>$nav</span><p>(<a href=showlist.php?username=$username&dowhat=user>$strShow1</a>)<p><br><div>");
	echo($csr->LangConvert($strShowAbuse, $Config_abuse_link));
	echo("<p>&nbsp;</p>");
                 
$usr->FooterOut();

?>
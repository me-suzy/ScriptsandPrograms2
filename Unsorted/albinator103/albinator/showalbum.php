<?php
	$dirpath = "$Config_rootdir";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();
	$albumcook = new Cookie();
	$letgo = "0";

      if ( !$ucook->LoggedIn() )
	{ $ShowHeader = "HeaderOut"; $ShowFooter = "FooterOut"; }
	else
	{ $ShowHeader = "Header"; $ShowFooter = "Footer"; }

	if(!$aid || !$uuid)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError1</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->$ShowFooter();

	 exit;
      }

	$result = queryDB( "SELECT status FROM $tbl_userinfo WHERE uid = '$uuid'" );
	$nr = mysql_num_rows( $result );
	$row = mysql_fetch_array( $result );
	if(($row[status] != '2' && $row[status] != '1') || !$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbums");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
	 echo("<BR>");
       $errMsg = "<b>$strAlbumCrErr24</b>\n";
       $usr->errMessage( $errMsg, $strSorry, 'error', '65' );
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

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uuid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strShowError2</b>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uuid' && aid = '$aid'" );
	$nr = mysql_num_rows( $result );
	$row = mysql_fetch_array( $result );

	##### expire #####
	if($row[private] == 1)
	{
	  if(!$albumcook->checkCookie("alid"))
	  { $albumcook->delete("alid"); }
      }
	############

	if($login_conf == 1 && $row[password] == md5($al_pass) && $row[private] == 1)
	{ $albumcook->set("alid", "$aid"); 
	  $letgo = "1"; }
	else if($login_conf == 1 && $row[password] != md5($al_pass))
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>$strInvalid $strPassword</b>, <a href=\"javascript:history.back(-1);\">$strRetry</a>\n";
       $usr->errMessage( $errMsg, $strError );
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }

	if($sendurl == "view")
	{ $fieldadd = "<input type=hidden name=ppid value=$ppid><input type=hidden name=sendurl value=view>"; }

	if($row[private] == 1 && $letgo == "0")
	{
 	 if( $albumcook->get("alid") != "$aid" )
 	 {
	 $albumcook->delete("alid");
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum", '', '', 'onload');
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div><br><br>");
	 #form to login here
?>
                  <form method=post action=showalbum.php name=alpass>
                    <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#666666">
                      <tr>
                        <td align=right class=ts>
                          <div align="center"><font color=#ffffff><?php echo $strReqPass6 ?> (<a href="show.php?dowhat=rpass&aid=<?php echo $aid ?>&uuid=<?php echo $uuid ?>"><font color=#ffffff><?php echo $strReqPass4 ?></font></a>)&nbsp;</font></div>
                        </td>
                      </tr>
                      <tr bgcolor="#dddddd"> 
                        <td> 
                          <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">
                            <tr>
                              <td class=tn> 
                                <div align="right"> <?php echo $strPassword ?>: 
                                  <input type="password" name="al_pass" maxlength=15 class=fieldsnorm>
                                  <input type="hidden" name="aid" value="<?php echo $aid ?>">
                                  <input type="hidden" name="login_conf" value="1">
                                  <input type="hidden" name="uuid" value="<?php echo $uuid ?>">
					    <?php echo $fieldadd ?>
                                  <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/view.gif" width="53" height="19" border="0">
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
			</form>                

<?php	
   	 $usr->$ShowFooter();

	 closeDB();
	 exit;
      }
}
	else if($albumcook->get("alid") == "$aid" && $row[private] == 1)
	{ $albumcook->refresh("alid"); }
	else if($row[private] != 1 && $albumcook->get("alid") != "")
	{ $albumcook->delete("alid"); }

	$result = queryDB( "SELECT * FROM $tbl_userinfo WHERE uid='$uuid'" );
	$row_orig = mysql_fetch_array( $result );
	$realname = $row_orig[uname];

	$result = queryDB( "SELECT * FROM $tbl_pictures WHERE aid='$aid' ORDER BY pindex, pname" );
	$nr = $row[pused];

	if(!$nr)
	{
       $usr->$ShowHeader($Config_SiteTitle ." :: $strView $strAlbum");
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/aview.gif>&nbsp;</div>");
       $errMsg = "<b>".$csr->LangConvert($strShowError3, "uuid=$uuid")."</b>\n";
       $usr->errMessage( $errMsg, '' );
   	 $usr->$ShowFooter();
	 exit;
	}

	if($sendurl == "view")
	{ 

$sendtourl = "showpic.php?aid=$aid&pid=$ppid&uuid=$uuid"; 
Header("Location: $sendtourl");

	  
echo <<< _HTML_END_

<div align="center" style="font-family: Verdana; font-size: 10pt; font-weight: bold; color: #990000;">
Displaying Photos, please wait ...
<div>

_HTML_END_;

exit;
	}

	$icon_links="<a href='showlist.php?uuid=$uuid&dowhat=user'><img src=\"{$dirpath}$Config_imgdir/design/icon_list.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"$strShow1\"></a>&nbsp;<img src=\"{$dirpath}$Config_imgdir/design/icon_back.gif\" width=\"16\" height=\"16\" border=\"0\">&nbsp;<img src=\"{$dirpath}$Config_imgdir/design/icon_front.gif\" width=\"16\" height=\"16\" border=\"0\">&nbsp;";

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
	$nav = $rs->getPageNav("uuid=$uuid&aid=$aid");

	if($nav)
	$nav2 = "(".rtrim($nav).")";

	echo("<div class=ts align=center><br>\n$nrback $strPhoto$s $nav2<br>$strAlbum <b>$albumname</b>, $cdate $strOwner: <a href=showprofile.php?uuid=$uuid>$realname</a><p><span class=\"tn\"><i>".stripslashes($albummsg)."</i></span></div>\n<p>&nbsp;</p>\n");

	echo("<div class=tn align=center>\n<table align=center width=600 cellpadding=4 cellspacing=4>\n");

	$result_user = queryDB( "SELECT prefs FROM $tbl_userinfo WHERE uid = '$uuid'" );
	$row_user = mysql_fetch_array( $result_user );

	if (preg_match("/B/", $row_user[prefs]))
	$borderval = "border=1";
	else
	$borderval = "border=0";

	$i = -1;
      $total = 0;

		while($row = $rs->fetchArray())
		{
	 	 $total++;
		 if($i == 3 || $i == -1)
		 { $i = 0; echo("\n<tr>"); $messagebar = "\n<tr>\n"; }
		 else
		 { $i++; }
		
		 $picurl = "$dirpath"."$Config_datapath/$uuid/tb_$row[pname]";
		 error_reporting(0);
   	       $size = GetImageSize ("$picurl");
		 error_reporting(E_ERROR | E_WARNING);

  		 echo("\n<td align=center valign=bottom><a href=\"showpic.php?aid=$aid&uuid=$uuid&pid=$row[pid]\"><img src=\"$picurl\" $borderval $size[3]></a></td>");
		 $messagebar .= "\n<td class=ts align=center valign=bottom><a href=\"showpic.php?aid=$aid&uuid=$uuid&pid=$row[pid]\">$strView</a></td>";

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
	echo("<div class=tn align=center><p>&nbsp;</p><span class='ts'>$nav</span><p>(<a href=showlist.php?uuid=$uuid&dowhat=user>$strShow1</a> ~ <a href=showprofile.php?uuid=$uuid>$strProfile</a>)$links<br><br><div>");
	echo($csr->LangConvert($strShowAbuse, $Config_abuse_link));
	echo("<p>&nbsp;</p>");
                 
$usr->$ShowFooter();

?>
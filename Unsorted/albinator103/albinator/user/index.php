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


	$result = queryDB( "SELECT uname, limits FROM $tbl_userinfo WHERE uid = '$uid'" );
	$row = mysql_fetch_array($result);
	list($slimit, $alimit, $plimit, $rlimit) = split('[|]', $row[limits]);
	mysql_free_result($result);

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	$no_albums = mysql_num_rows( $result );

	if($no_albums > 1)
	$s = $strPuralS;

	if($no_albums == 0)
	$addpic = $csr->LangConvert($strIndexAddpic, 0).$strPuralS.", $strCreate";
	else if($no_albums > 0)
	$addpic = $csr->LangConvert($strIndexAddpic, $no_albums)."$s, <a href=upload.php>$strMenusAddphotos</a>";

	if($no_albums < $alimit || !$alimit)
	$onload = "onload";
	else
	$onload = "";


	$ref_url = $HTTP_REFERER;
      if(preg_match("/(login.php)/i", $ref_url))
	{ $usr->Header($Config_SiteTitle .' :: '.$strIndexWelcome, '', '', $onload, '1'); }
	else
	{ $usr->Header($Config_SiteTitle .' :: '.$strIndexHome, '', '', $onload); }

      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/welcome.gif>&nbsp;</div><br>");
      echo ("<p>&nbsp;</p>");

	echo ("<div align=center class=tn>$strIndexWelcome <b>".ucwords($row[uname])."</b> (<a 	href=userprofile.php?dowhat=show>$strMenusMyprofile</a>), $addpic<br></div>");

if($no_albums < $alimit || !$alimit)
{
$addTitle = "$strMenusAddAlbum";

?>
<form name="addalbum" method="post" action="album_cr.php">
  <table width="82%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#006699" class=tn>
     <tr> 
	<td colspan=3 class=ts bgcolor="#333333">
	    <font color=#cccccc><b><?php echo $addTitle ?></b></font>
	</td>
     </tr>
    <tr> 
      <td bgcolor="#dddddd" width="25%" class=tn>&nbsp;<?php echo "$strNew ".strtolower($strAlbum); ?> </td>
      <td bgcolor="#CCCCCC" width=65%>&nbsp;<input type="text" name="newalbum" maxlength=99 size="30" class=fieldsa></td>
      <td width=10%>&nbsp;</td>
	</tr>
    <tr> 
      <td bgcolor="#dddddd" width="25%" class=tn>&nbsp;<?php echo $strMessage ?> </td>
      <td bgcolor="#CCCCCC" width=65%>&nbsp;<input type="text" name="newmessage" maxlength=99 size="30" class=fieldsa></td>
      <td width="10%" align="center"> 
	     <img src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/reset.gif" width="53" height="19" border="0" onclick="document.addalbum.reset();document.addalbum.newalbum.focus();">
      </td>
	</tr>
     <tr> 
      <td bgcolor="#dddddd" width="25%" class=tn>&nbsp;<?php echo $strPassword ?> *</td>
      <td width=65% bgcolor="#CCCCCC" class=tn>&nbsp;<input type="password" name="al_pass" maxlength=15 size="30" class=fieldsa>
        <input type="checkbox" name="al_private" value="yes"> <?php echo $strPrivate ?>
	</td>
      <td width="10%" align="center"> 
  	     <input type="hidden" name="send_url" value="index.php">
	     <input type="image" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/create.gif" width="53" height="19" border="0">
      </td>
	</tr>
     <tr> 
	<td colspan=3 class=ts>
    <font color=#cccccc><?php echo $strIndexAlbumNotify ?></font>
	</td>
     </tr>
  </table>
</form>
<br>
<?php

}

else
{
    echo ("<br>");
    $errMsg = "<b>".$csr->LangConvert($strCrossLimit, strtolower($strAlbum).$strPuralS)."</b><br>[<a href=\"$Config_buylink\">$strBuySentence</a>]<br>\n";
    $usr->errMessage( $errMsg, $strNote, 'error', '80' );
}

?>

<br>
<table width="98%" border="0" cellspacing="0" cellpadding="4" align="center">
                    <tr> 
                      <td width="110" height="143"> 
                        <div align="right"><img src="<?php echo $dirpath.$Config_imgdir ?>/main/logo4.gif" width="83" height="275"></div>
                      </td>
                      <td height="143"> 
                        <table width="80%" border="0" cellspacing="0" cellpadding="2" align="center">
                          <tr> 
                            <td class="tn"> <?php echo $strIndexCreateHelp ?> </td>
                          </tr>
                          <tr> 
                            <td height="12" class="tn">&nbsp;</td>
                          </tr>
                        </table>
                      </td>
                      <td height="143">&nbsp;</td>
                    </tr>
                  </table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2" align="center">
  <tr> 
    <td width="30">&nbsp; </td>
    <td>
      <div align="center" class=tn><font color="#006699"><span class=warn> 
        <b><?php echo $strAlbinatorBuyline ?></b></span></font><br>
        <br>
        <font size="3"><?php echo $strAlbinatorBuy ?></font></div>
    </td>
    <td width="30">&nbsp; </td>
    <td width="340"><img src="<?php echo $dirpath.$Config_imgdir ?>/main.gif" width="345" height="323"></td>
  </tr>
</table>
<br>

<?php

closeDB();
$usr->Footer(); 

?>
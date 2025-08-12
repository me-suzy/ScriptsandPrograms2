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

	$result = queryDB( "SELECT * FROM $tbl_albumlist WHERE uid = '$uid'" );
	$albumlist_nr = mysql_num_rows( $result );
	if(!$albumlist_nr)
  	{
       $usr->Header($Config_SiteTitle .' :: '.$strEditing);
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/edit.gif>&nbsp;</div>");
	 echo ("<br>");
       $errMsg = "<b>$strAlbumCrErr6, <a href=index.php>$strCreate</a>...</b>\n";
       $usr->errMessage( $errMsg, '' );
	 echo ("<br>");
   	 $usr->Footer();

	 closeDB();
	 exit;
      }
 	mysql_free_result($result);

	$howmany = 5;

	if(!$sf)
	$sf = 1;

	$rs = new PagedResultSet("SELECT * FROM $tbl_albumlist WHERE uid = '$uid'",$howmany);
	$nr = mysql_num_rows( $rs->result );
	$nav = $rs->getPageNav();

	if($nr > 1)
	{ $s = $strPuralS;
	  $dspinfo = "<br><p><div align='center'>$nav</div>"; }

      $usr->Header($Config_SiteTitle .' :: '.$strEditing);
      echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/edit.gif>&nbsp;</div>");

?>
<p>&nbsp;</p>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td width="12" height="2"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><img src="<?php echo $dirpath.$Config_imgdir ?>/form_tl.gif" width="12" height="12"></td>
        </tr>
        <tr>
          <td><img src="<?php echo $dirpath.$Config_imgdir ?>/form_bl.gif" width="12" height="12"></td>
        </tr>
      </table>
    </td>
    <td bgcolor="#CECECE" height="2"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class=ts>
        <tr align="center" valign="bottom"> 
          <td width=20%><a href=album_select.php?catog=1><?php echo $strCaption.$strPuralS ?></a></td>
          <td width=20%><a href=album_select.php?catog=2><?php echo $strMove ?></a></td>
          <td width=20%><a href=album_select.php?catog=3><?php echo $strCopy ?></a></td>
          <td width=20%><a href=album_select.php?catog=4><?php echo $strDelete ?></a></td>
          <td width=20%><a href=album_select.php?catog=5><?php echo strtolower($strOrder) ?></a></td>
        </tr>
      </table>
    </td>
    <td width="12" height="2"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td><img src="<?php echo $dirpath.$Config_imgdir ?>/form_tr.gif" width="12" height="12"></td>
        </tr>
        <tr> 
          <td><img src="<?php echo $dirpath.$Config_imgdir ?>/form_br.gif" width="12" height="12"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?php echo($dspinfo); ?>
  <p>&nbsp;</p>
  <table width="70%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr> 
	<td class=ts width=40%><?php echo($albumlist_nr." $strAlbum".$s."</div>"); ?></td>
      <td class=tn width=60%> 
        <div align="right">
	  <?php echo ("$strSortBy :: ");
	  
	  if($sortname != "aname")
	  echo ("<a href=album_edit.php?sf=$this_sf>$strAlbumCrErr7</a> ~ $strDate");
	  else
	  echo ("$strAlbumCrErr7 ~ <a href=album_edit.php?sf=$this_sf&sortname=aid>$strDate</a>");
	  ?>
	  </div>
      </td>
    </tr>
   </table>


<!-- bodyof editing -->

<?php
	$limitval--;
	$params = "sf-$limitval|sortname-$sortname";

	while($row = $rs->fetchArray())
	{
		$aused_space = $row[sused]; $aused_pics = $row[pused];
		$aused_space  = $csr->calcSpaceVal( $aused_space );

		if($aused_pics == "1") $s = ''; else $s = $strPuralS;

		if($aused_pics != '0')
		$viewurl = "[<a href=\"$dirpath"."showalbum.php?aid=$row[aid]&uuid=$uid\" class=\"nounderts\">$strView</a>]";
		else
		$viewurl = "";

?>

<form method=post action=album_edit_chg.php>
  <table width="70%" border="0" cellspacing="1" cellpadding="2" align="center" bgcolor="#666666">
    <tr> 
      <td class=tn> 
        <div align="right"><font color="#FFFFFF">
	  <?php 
	        if($row[private] == 1)
	        { echo ("<b>$strPrivate</b> :: "); 
		    $private_chg = "[<a href=\"album_priv.php?aid=$row[aid]&send_url=album_edit&params=$params&chg=pub\" class=nounderts>$strAlbumCrErr9</a> ~ <a href=\"album_priv.php?aid=$row[aid]&send_url=album_edit&params=$params\" class=nounderts>$strAlbumCrErr11</a>]"; }
		  else
	        { 
		    $private_chg = "[<a href=\"album_priv.php?aid=$row[aid]&send_url=album_edit&params=$params\" class=nounderts>$strAlbumCrErr10</a>]"; }

	   echo (stripslashes($row[aname])." :: $aused_pics $strPhoto$s ($aused_space)"); ?>
	  </font></div>
      </td>
    </tr>
    <tr bgcolor="#dddddd"> 
      <td> 
	 <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center"><tr>
	  <td width=90% class=tn align="right"> 
		<?php echo strtolower($strName) ?> <input type="text" name="new_alname" value="<?php echo (stripslashes($row[aname])); ?>" maxlength=50 size=40></td>
	  <td>&nbsp;</td>
	</tr><tr>
	  <td width=90% class=tn align="right"><?php echo $strMessage ?> <input type="text" name="new_amsg" value="<?php if($row[amsg] != '0') echo (stripslashes($row[amsg])); ?>" maxlength=85 size=40>
          <input type="hidden" name="aid" value="<?php echo ($row[aid]); ?>">
	    <input type="hidden" name="send_url" value="album_edit">
	    <input type="hidden" name="params" value="<?php echo $params ?>">
	 </td>
	 <td align=center>
          <input type="image" name="submit" src="<?php echo $dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/buttons" ?>/change.gif" width="53" height="19" border="0">
	 </td>
	</tr><tr>
	<td colspan=2 align=right class=ts>
          <?php echo $viewurl ?> [<a href="pic_edit.php?aid=<?php echo ($row[aid]); ?>&send_url=album_edit&params=<?php echo $params ?>" class=nounderts><?php echo ("$strAlbumCrErr13"); ?></a>] <?php echo $private_chg ?> [<a href="album_del.php?aid=<?php echo ($row[aid]); ?>&send_url=album_edit&params=<?php echo $params ?>" class=nounderts><?php echo ("$strAlbumCrErr12"); ?></a>]
	 </td>
	</tr></table>
      </td>
    </tr>
  </table>
</form>


<?php
		}

echo ("<div align=center>$nav</div>");

closeDB();
echo("<br>");


$usr->Footer();

?>
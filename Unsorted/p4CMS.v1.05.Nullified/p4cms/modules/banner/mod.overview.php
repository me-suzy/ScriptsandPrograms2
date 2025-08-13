<?
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 StyleSheet(); 
 if ($HTTP_SESSION_VARS[u_gid] == 1) {
 
if($_REQUEST['act']==""){
if($_REQUEST['zone']==""){$zone=1;}else{$zone=$_REQUEST['zone'];}
?>
 <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

  <tr> 
    <td align="left" valign="top" class="boxstandart"> <table width="100%"> 
         <tr> 
          <td colspan="2" valign="top"><b><font size="+1">Banner</font></b></td> 
        </tr> 
         <tr> 
          <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="2"> 
              <tr> 
                <td><form name="form1" method="post" action=""> 
                    <select name="zone" id="zone"> 
                      <?
						$sqlb =& new MySQLq();
						$sqlb->Query("SELECT * FROM " . $sql_prefix . "bannerzone ORDER BY id ASC");
						while ($rowb = $sqlb->FetchRow()) {
						?> 
                      <option value="<?=$rowb->id;?>" <? if($zone==$rowb->id)echo"selected";?>> 
                      <?=$rowb->name;?> 
                      </option> 
                      <? } ?> 
                    </select> 
                    <input name="Senden" type="submit" class="button" value="anzeigen"> 
                  </form></td> 
              </tr> 
              <tr> 
                <td><br> 
                  den Bannercode bitte an die gew&uuml;nschte Stelle auf Ihrer Seite platzieren</td> 
              </tr> 
            </table> 
             <br> </td> 
          <td width="1%" valign="top"><div align="right"> 
              <form name="form2" method="post" action=""> 
                <textarea name="code" cols="80" rows="5" id="code"><!-- BANNERCODE --><?="\n";?><script language="javascript" src="<?="http://".$_SERVER['HTTP_HOST']."/p4cms/modules/banner/bannerjs.php?bannerzone=$zone";?>" type="text/javascript"></script><?="\n";?><!-- BANNERCODE -->
</textarea> 
              </form> 
            </div></td> 
        </tr> 
       </table> 
     <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

         <tr bgcolor="#EAEBEE"> 
          <td height="17" class="boxheader">&nbsp;Banner</td> 
          <td width="1%" class="boxheader" alt="Abonenten der Liste anzeigen">ID</td> 
          <td width="1%" class="boxheader" alt="Abonenten der Liste anzeigen">
           <div align="center">Klicks</div></td> 
          <td width="5%" class="boxheader" alt="Abonenten der Liste anzeigen">
           <div align="center">Views</div></td> 
          <td width="5%" class="boxheader" alt="Abonenten der Liste anzeigen">
           <div align="center">Typ</div></td> 
          <td width="5%" class="boxheader" alt="Abonenten der Liste anzeigen">
           <div align="center">Aktionen</div></td> 
        </tr> 
         <?
                $sql =& new MySQLq();
                $sql->Query("SELECT * FROM " . $sql_prefix . "banner where bannerzone='$zone' ORDER BY id DESC");
                while ($row = $sql->FetchRow()) {
                	?> 
         <tr bgcolor="#FAFAFB"> 
          <td height="17"> <?
					if($row->format=="flash"){
					$src = "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0' width='$row->flash_width' height='$row->flash_height'><param name=movie value='$row->src'><param name=quality value=high><embed src='$row->src' quality=high pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash' type='application/x-shockwave-flash' width='$row->flash_width' height='$row->flash_height'></embed></object>";
					$end = "flash";
					$klicks = "keine Zählung";
					} else {
					$src = "<img src=".$row->src.">"; $end="image";
					$klicks = $row->hits;
					} 
					$views = $row->views;
					$klickperc = "";
					if($row->format=="image"){
					
					$klickperc = " (".round($klicks*100/$views,2)." %)";
					}
					echo $src;
					?> </td> 
          <td>
           <div align="center"><?php echo $row->id; ?></div></td> 
          <td nowrap>
            <div align="center"> 
              <?=$klicks.$klickperc;?> 
           </div></td> 
          <td>
            <div align="center"> 
              <?=$views;?> 
           </div></td> 
          <td>
           <div align="center"><img src="/p4cms/modules/banner/<?=$end;?>.gif"></div></td> 
          <td nowrap>
           <div align="center"><a href="module.php?module=banner&page=mod.overview.php&d4sess=<?=$d4sess;?>&act=edit&id=<?=$row->id;?>"><img src="gfx/edit.gif" alt="Bearbeiten" border="0" align="absmiddle"></a> <a href="module.php?module=banner&page=mod.overview.php&d4sess=<?=$d4sess;?>&act=del&id=<?=$row->id;?>"><img src="gfx/del.gif" alt="L&ouml;schen" border="0" align="absmiddle"></a></div></td> 
        </tr> 
         <?
                }
                $sql->Close();
                ?> 
      </table> 
      </td> 
   </tr> 
</table> 
<? 
}
if ($_REQUEST['act']=="del") {
 		$sql =& new MySQLq();
 		$sql->Query("DELETE FROM " . $sql_prefix . "banner WHERE id='$_REQUEST[id]'");
 		$sql->Close();
 		header("location:module.php?module=banner&page=mod.overview.php");
 		
} if($_REQUEST['act']=="edit"){ 
 	 if($_REQUEST['send']=="1"){
	 $sql =& new MySQLq();
	 
	 //if($_REQUEST['$von1']=="00" || $_REQUEST['$von1']==""){$von1=
	 $sql->Query("UPDATE " . $sql_prefix . "banner set
	 time_from='".$_REQUEST['von']."',
	 time_to='".$_REQUEST['bis']."',
	 name='".$_REQUEST['name']."',
	 kontakt='".$_REQUEST['kontakt']."',
	 src='".$_REQUEST['src']."',
	 target='".$_REQUEST['target']."',
	 max_views='".$_REQUEST['max_views']."',
	 max_hits='".$_REQUEST['max_hits']."',
	 alt='".$_REQUEST['alt']."',
	 link_target='".$_REQUEST['link_target']."',
	 flash_width='".$_REQUEST['flash_width']."',
	 flash_height='".$_REQUEST['flash_height']."',
	 bannerzone='".$_REQUEST['zone']."'
	 WHERE id='$_REQUEST[id]'");
	 }


	$sql =& new MySQLq();
	$sql->Query("SELECT * FROM " . $sql_prefix . "banner WHERE id='$_REQUEST[id]'");
	$row = $sql->FetchRow();
	
	$vonarr = explode("-","$row->time_from");
	$von1 = $vonarr[0];
	$von2 = $vonarr[1];
	
	$bisarr = explode("-","$row->time_to");
	$bis1 = $bisarr[0];
	$bis2 = $bisarr[1];
	?> 
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

  <tr> 
    <td align="left" valign="top" class="boxstandart"> <table> 
        <tr> 
          <td><h3>Banner bearbeiten</h3></td> 
        </tr> 
      </table> 
      <form name="f" method="post" action=""> 
        <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

          <tr bgcolor="#FAFAFB"> 
            <td nowrap>Banner-Zone</td> 
            <td> 
              <select name="zone" id="zone"> 
                <?
						$sqlb =& new MySQLq();
						$sqlb->Query("SELECT * FROM " . $sql_prefix . "bannerzone ORDER BY name");
						while ($rowb = $sqlb->FetchRow()) {
						?> 
                <option value="<?=$rowb->id;?>" <? if($row->bannerzone==$rowb->id)echo"selected";?>> 
                <?=$rowb->name;?> 
                </option> 
                <? } ?> 
            </select> </td> 
          </tr> 
          <tr bgcolor="#FAFAFB"> 
            <td nowrap>Banner-Name</td> 
            <td>
            <input name="name" type="text" id="name" value="<?=$row->name;?>" size="55"></td> 
          </tr> 
          <tr bgcolor="#FAFAFB"> 
            <td nowrap>Kontakt</td> 
            <td>
            <input name="kontakt" type="text" id="kontakt" value="<?=$row->kontakt;?>" size="55"></td> 
          </tr> 
          <tr bgcolor="#FAFAFB"> 
            <td width="10%" nowrap>Banner-Url</td> 
            <td>
            <input name="src" type="text" id="src" value="<?=$row->src;?>" size="55"></td> 
          </tr> 
          <? if($row->format=="flash"){?> 
          <tr bgcolor="#FAFAFB"> 
            <td nowrap>Breite/H&ouml;he</td> 
            <td>
              <input name="flash_width" type="text" id="flash_width" value="<?=$row->flash_width;?>" size="5"> 
              /
            <input name="flash_height" type="text" id="flash_height" value="<?=$row->flash_height;?>" size="5"></td> 
          </tr> 
          <? } if($row->format=="image"){?> 
          <tr bgcolor="#FAFAFB"> 
            <td width="10%" nowrap>Klick-Url</td> 
            <td>
            <input name="target" type="text" id="target" value="<?=$row->target;?>" size="55"></td> 
          </tr> 
          <tr bgcolor="#FAFAFB"> 
            <td nowrap>Alt-Text</td> 
            <td>
            <input name="alt" type="text" id="alt" value="<?=$row->alt;?>" size="55"></td> 
          </tr> 
          <tr bgcolor="#FAFAFB"> 
            <td width="10%" nowrap>Fenster</td> 
            <td> 
              <select name="link_target" id="link_target"> 
                <option value="_blank" <? if($row->link_target=="_blank") echo "selected";?>>Neues Fenster</option> 
                <option value="_self" <? if($row->link_target=="_self") echo "selected";?>>Gleiches Fenster</option> 
            </select></td> 
          </tr> 
          <? } ?> 
          <tr bgcolor="#FAFAFB"> 
            <td width="10%" nowrap>Views </td> 
            <td><b> 
              <input name="1" type="text" id="1" value="<?=$row->views;?>" size="10" disabled> 
              </b>abgearbeitet / von
              <input name="max_views" type="text" id="max_views" value="<?=$row->max_views;?>" size="10"> 
            (0 f&uuml;r unbegrenzt) </td> 
          </tr>
		  <? if($row->format=="image"){  ?>
          <tr bgcolor="#FAFAFB"> 
            <td width="10%" nowrap>Hits</td> 
            <td> 
              <input name="1" type="text" id="1" value="<?=$row->hits;?>" size="10" disabled> 
              abgearbeitet / von
              <input name="max_hits" type="text" id="max_hits" value="<?=$row->max_hits;?>" size="10"> 
            (0 f&uuml;r unbegrenzt)</td> 
          </tr>
		  <? } ?> 
          <tr bgcolor="#FAFAFB"> 
            <td width="10%" nowrap>&nbsp;</td> 
            <td>&nbsp;</td> 
          </tr> 
          <tr bgcolor="#FAFAFB"> 
            <td width="10%" nowrap>&nbsp;</td> 
            <td> 
              <input type="submit" class="button" value="ändern"> 
              <input name="send" type="hidden" id="send" value="1"> 
              <input name="id" type="hidden" id="id" value="<?=$_REQUEST['id'];?>">
			  <? if($row->format=="flash"){  ?>
			  <input name="max_hits" type="hidden" id="max_hits" value="0">
			  <? } ?>
		    </td> 
          </tr> 
        </table> 
      </form></td> 
  </tr> 
</table> 
<? }


 } else {
	$msg = "<center>Diese Seite darf nur von Administratoren aufgerufen werden.</center>";
	MsgBox($msg);
 }
?> 

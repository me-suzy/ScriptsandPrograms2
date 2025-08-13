<? 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 ?>
<link rel="stylesheet" href="/p4cms/style/style.css">

<? if ($HTTP_SESSION_VARS[u_gid] == 1) { ?>



<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

  <tr>
    <td align="left" valign="top" class="boxstandart">
      <? if($_REQUEST['format']==""){ ?>
	  <form name="form1" method="post" action="">
	    <table width="100%">
          <tr>
            <td height="26" colspan="2" valign="top"><b><font size="+1">Banner anlegen</font></b></td>
          </tr>
          <tr>
            <td width="10%" nowrap>&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td nowrap>Bannertyp</td>
            <td valign="top"><select name="format" id="format">
              <option value="image" selected>Bild</option>
              <option value="flash">Flash</option>
            </select>
            <input type="submit" class="button" value="weiter"></td>
          </tr>
        </table>
      </form>
	  <? } else { 
	  if($_REQUEST['save']==1)
	  {
	  $sql =& new MySQLq();
	  $sql->Query("INSERT INTO " . $sql_prefix . "banner 
	  (max_hits,format,id,time_from,time_to,name,kontakt,src,target,max_views,alt,link_target,flash_width,flash_height,bannerzone) VALUES 
	  ('".$_REQUEST['max_hits']."','".$_REQUEST['format']."','','".$_REQUEST['von']."','".$_REQUEST['bis']."','".$_REQUEST['name']."','".$_REQUEST['kontakt']."','".$_REQUEST['src']."','".$_REQUEST['target']."','".$_REQUEST['max_views']."','".$_REQUEST['alt']."','".$_REQUEST['link_target']."','".$_REQUEST['flash_width']."','".$_REQUEST['flash_height']."','".$_REQUEST['zone']."') ");
	 
	  $rel="module.php?module=banner&page=mod.overview.php&d4sess=$d4sess&act=";
	  header("location:$rel");
	  }
	  
	  ?>
	  <script language="javascript" type="text/javascript">
	  <!--
	  function checknew()
	  {
		if(document.f.name.value.length < 1){
		alert("Bitte Bannername angeben ");
		document.f.name.focus();
		return false;}
		
		if(document.f.src.value.length < 1){
		alert("Bitte Banner-Url angeben ");
		document.f.src.focus();
		return false;}
		
		<? if($_REQUEST[format]=="flash"){ ?>
		if(document.f.flash_height.value.length < 1){
		alert("Bitte Höhe angeben ");
		document.f.flash_height.focus();
		return false;}
		
		if(document.f.flash_width.value.length < 1){
		alert("Bitte Breite angeben ");
		document.f.flash_width.focus();
		return false;}
		
		if(document.f.max_views.value.length < 1){
		alert("Bitte maximale Views angeben ");
		document.f.max_views.focus();
		return false;}
		<? } ?>
		
		<? if($_REQUEST[format]=="image"){ ?>
		if(document.f.target.value.length < 1){
		alert("Bitte Klick-Url angeben ");
		document.f.target.focus();
		return false;}
		
		if(document.f.max_views.value.length < 1){
		alert("Bitte maximale Views angeben ");
		document.f.max_views.focus();
		return false;}
		
		if(document.f.max_hits.value.length < 1){
		alert("Bitte maximale Hits angeben ");
		document.f.max_hits.focus();
		return false;}
		<? } ?>
		
	  }
	  //-->
	  </script>
	  <form name="f" method="post" action="" onSubmit="return checknew();">
	    <b><font size="+1">Banner anlegen</font></b>	   <table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
          <tr bgcolor="#FAFAFB">
            <td nowrap>Banner-Zone</td>
            <td>
              <select name="zone" id="zone">
                <?
						$sqlb =& new MySQLq();
						$sqlb->Query("SELECT * FROM " . $sql_prefix . "bannerzone ORDER BY id ASC");
						while ($rowb = $sqlb->FetchRow()) {
						?>
                <option value="<?=$rowb->id;?>" <? if($row->bannerzone==$rowb->id)echo"selected";?>>
                <?=$rowb->name;?>
                </option>
                <? } ?>
              </select>
            </td>
          </tr>
          <tr bgcolor="#FAFAFB">
            <td nowrap>Banner-Name</td>
            <td><input name="name" type="text" id="name" size="55"></td>
          </tr>
          <tr bgcolor="#FAFAFB">
            <td nowrap>Kontakt</td>
            <td><input name="kontakt" type="text" id="kontakt" size="55"></td>
          </tr>
          <tr bgcolor="#FAFAFB">
            <td width="10%" nowrap>Banner-Url</td>
            <td><input name="src" type="text" id="src" size="55"></td>
          </tr>
          <? if($_REQUEST['format']=="flash"){?>
          <tr bgcolor="#FAFAFB">
            <td nowrap>Breite/H&ouml;he</td>
            <td><input name="flash_width" type="text" id="flash_width" value="468" size="5">
      /
        <input name="flash_height" type="text" id="flash_height" value="60" size="5"></td>
          </tr>
          <? } if($_REQUEST['format']=="image"){?>
          <tr bgcolor="#FAFAFB">
            <td width="10%" nowrap>Klick-Url</td>
            <td><input name="target" type="text" id="target" size="55"></td>
          </tr>
         
          <tr bgcolor="#FAFAFB">
            <td nowrap>Alt-Text</td>
            <td><input name="alt" type="text" id="alt" size="55"></td>
          </tr>
          <tr bgcolor="#FAFAFB">
            <td width="10%" nowrap>Fenster</td>
            <td>
              <select name="link_target" id="link_target">
                <option value="_blank" selected <? if($row->link_target=="_blank") echo "selected";?>>Neues Fenster</option>
                <option value="_self" <? if($row->link_target=="_self") echo "selected";?>>Gleiches Fenster</option>
              </select></td>
          </tr>
          <? } ?>
          <tr bgcolor="#FAFAFB">
            <td width="10%" nowrap>Views max </td>
            <td>      <input name="max_views" type="text" id="max_views" value="0" size="10">
      (0 f&uuml;r unbegrenzt) </td>
          </tr>
         
		 <? if($_REQUEST[format]=="image"){ ?>
		  <tr bgcolor="#FAFAFB">
            <td nowrap>Hits max </td>
            <td><input name="max_hits" type="text" id="max_hits" value="0" size="10">
(0 f&uuml;r unbegrenzt) </td>
          </tr>
		  <? } ?>
          <tr bgcolor="#FAFAFB">
            <td width="10%" nowrap>&nbsp;</td>
            <td>
              <input type="submit" class="button" value="Banner anlegen">
              <input name="save" type="hidden" id="save" value="1"> <input name="format" type="hidden" id="format" value="<?=$_REQUEST['format'];?>">
              <? if($_REQUEST[format]=="flash"){  ?>
              <input name="max_hits" type="hidden" id="max_hits" value="0">
              <? } ?></td>
          </tr>
        </table>
	  </form></td>
  </tr>
</table>
<?  }} else {
	$msg = "<center>Diese Seite darf nur von Administratoren aufgerufen werden.</center>";
	MsgBox($msg);
 }
?>
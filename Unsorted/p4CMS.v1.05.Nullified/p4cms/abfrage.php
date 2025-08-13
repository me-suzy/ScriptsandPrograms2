<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
 
 $grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
 if ($grp['m_abfragen']=="no") {
 	StyleSheet();
	$msg = "<center>Sie besitzen nicht die Rechte, diese Aktion auszuführen.</center>";
	MsgBox($msg);
	exit;
 }
?>
 <html>
<head>
<link rel="stylesheet" href="style/style.css">
 <? StyleSheet(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">
<script language="javascript">
<!--
 function DelVar(id, titel) {
 	if (window.confirm('Wollen Sie die Variable "' + titel + '" wirklich komplett löschen?')) {
 		window.open("delvar.php?id=" + id + "&d4sess=<?=$sessid;?>");
 	}
 }
//-->
</script>
<script language="JavaScript">
var copytoclip=1

function HighlightAll(theField) {
var tempval=eval("document."+theField)
tempval.focus()
tempval.select()
if (document.all&&copytoclip==1){
therange=tempval.createTextRange()
therange.execCommand("Copy")
window.status="Inhalt wird markiert (und in die Zwischenablage kopiert) !"
setTimeout("window.status=''",1800)
}
}
</script>
<form name="abfrage" method="post" action="abfrage.php">
<input type="hidden" name="switch" value="">
<input type="hidden" name="dfn" value="">
<input type="hidden" name="mode" value="save">
<?
if ($_REQUEST['mode']=="new" || $_REQUEST['mode']=="edit") {
	if ($_REQUEST['mode']=="edit") {
		$sql =& new MySQLq();
		$sql->Query("SELECT * FROM " . $sql_prefix . "abfragen WHERE id='$_REQUEST[id]'");
		$row = $sql->FetchRow();
		$sql->Close();
		
		
		$_REQUEST['abf_pos'] = $row->typ;
		$_REQUEST['abf_count'] = $row->zahl;
		$_REQUEST['abf_rub'] = $row->rubrik;
		$ed_titel = stripslashes($row->titel);
		$ed_tmpl = stripslashes($row->template);
		?>
		<input type="hidden" name="doedit" value="<?=$row->id;?>">
		<?
	} else {
		$ed_titel = "";
		$ed_rubrik = "";
		$ed_fn = "";
		$ed_tmpl = "";
	}
	?>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">


<?
					if (!isset($_REQUEST['abf_pos'])) {
						?>
					<tr>
					<td width="10%" nowrap bgcolor="#EAEBEE">Abfrage:</td>
					<td bgcolor="#FAFAFB">Hole die 
					  <select name="abf_pos" class="inputfield">
					  <option value="letzten">letzten</option><option value="ersten">ersten</option></select>
					<input class="inputfield" name="abf_count" value="5" size="6"> 
					Dokumente aus der Rubrik <select class="inputfield" name="abf_rub"><?
					$sql =& new MySQLq();
					$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken ORDER BY titel ASC");
					while ($row = $sql->FetchRow()) {
						echo "<option value=\"$row->id\">" . stripslashes($row->titel) . "</option>\n";
					}
					$sql->Close();
					?>
					</select> <input name="subm" type="submit" class="button"  onClick="document.all.mode.value='new';" value="speichern">
					</td>
					</tr>
						<?
					} else {
						?>
					<tr>
					<td width="10%" nowrap bgcolor="#EAEBEE">Abfrage:</td>
					<td bgcolor="#FAFAFB">Hole die <?=$_REQUEST['abf_pos'];?> <?=$_REQUEST['abf_count'];?> Dokumente aus der Rubrik <?
					$sql =& new MySQLq();
					$sql->Query("SELECT titel FROM " . $sql_prefix . "rubriken WHERE id='$_REQUEST[abf_rub]'");
					$row = $sql->FetchRow();
					echo stripslashes($row->titel); ?></td>
					</tr>
					<input type="hidden" name="abf_pos" value="<?=$_REQUEST['abf_pos'];?>">
					<input type="hidden" name="abf_count" value="<?=$_REQUEST['abf_count'];?>">
					<input type="hidden" name="abf_rub" value="<?=$_REQUEST['abf_rub'];?>">
					<tr>
					<td width="10%" nowrap bgcolor="#EAEBEE">Titel:</td>
					<td bgcolor="#FAFAFB">
					  <input style="width:100%;" class="inputfield" name="abf_titel" size="32" value="<?=$ed_titel;?>"></td>
					</tr>
						<?
					}
					?>
</table>					
<?
					if (isset($_REQUEST['abf_pos'])) {
						?>
<br>	
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
            <tr> 
                <td valign="top" bgcolor="#FAFAFB">
                  <p> 
					<?
					CreateEditor("100%","300",$ed_tmpl,"abf_template");
					?>
                    <input name="v" type="button" class="button"  id="v" onClick="edipop('abf_template');" value="WYSIWYG Modus">
                    <input class="button" onClick="HighlightAll('abfrage.abf_template')" style="height:10%;" type="button" value="in die Zwischenablage kopieren">

					<br>
              </td>
  </tr>
</table>  
    <br>
	<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">
 <tr>
        <td height="20" class="boxheader">&nbsp;Variable</td>
        <td height="17" class="boxheader">&nbsp;Beschreibung</td>
        <td height="17" class="boxheader">&nbsp;</td>
      </tr>
      <tr bgcolor="#FAFAFB">
        <td height="17">&nbsp;{LINK}</td>
        <td height="17" bgcolor="#FAFAFB">&nbsp;<font color="#dddddd"></font> URL zum Dokument</td>
        <td height="17" align="center" bgcolor="#FAFAFB"><a href="javascript:insertAtCaret(document.all.abf_template,'{LINK}');">Einf&uuml;gen</a></td>
      </tr>
      <?
$sql =& new MySQLq();
$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken_felder WHERE rubrik='$_REQUEST[abf_rub]' ORDER BY titel ASC");
while ($row = $sql->FetchRow()) {
					?>
      <tr bgcolor="#FAFAFB">
        <td height="17" bgcolor="#FAFAFB">&nbsp;{RUB:<? echo($row->id); ?>}</td>
        <td height="17" bgcolor="#FAFAFB"> &nbsp;<? echo(stripslashes($row->titel)); ?></td>
        <td height="17" align="center" bgcolor="#FAFAFB"><a href="javascript:insertAtCaret(document.all.abf_template,'{RUB:<? echo($row->id); ?>}');">Einf&uuml;gen</a></td>
      </tr>
      <?
}
$sql->Close();
?>
      <?
$sql =& new MySQLq();
$sql->Query("SELECT * FROM " . $sql_prefix . "variablen WHERE rub='$_REQUEST[abf_rub]' ORDER BY id ASC");
while ($row = $sql->FetchRow()) {
					?>
      <tr bgcolor="#FAFAFB">
        <td height="17">&nbsp;{USER:<? echo($row->id); ?>} (<a href="javascript:DelVar('<?=$row->id;?>','<?=$row->titel;?>');">löschen</a>)</td>
        <td height="17" bgcolor="#FAFAFB">&nbsp;<? echo(stripslashes($row->titel)); ?></td>
        <td height="17" align="center" bgcolor="#FAFAFB"><a href="javascript:insertAtCaret(document.all.abf_template,'{USER:<? echo($row->id); ?>}');">Einf&uuml;gen</a></td>
      </tr>
      <?
}
$sql->Close();
?>
      <tr>
        <td height="20" colspan="3" align="center" class="boxheader">[ <a style="" href="javascript:OpenVar('abfragen<?=$_REQUEST['abf_rub'];?>','<? echo($d4sess); ?>');">Hinzuf&uuml;gen</a> ]</td>
      </tr>
</table>      
	<br>
	<input type="button" class="button" value="speichern" onClick="document.forms['abfrage'].submit();">
<br>
<?
					}
			
}

if ($_REQUEST['mode']=="save") {
	if (isset($_REQUEST['doedit']) && $_REQUEST['doedit'] > 0) {
		$qr = "UPDATE " . $sql_prefix . "abfragen SET titel='$_REQUEST[abf_titel]', template='$_REQUEST[abf_template]' WHERE id='$_REQUEST[doedit]'";
		$sql =& new MySQLq();
		$sql->Query($qr);
		$sql->Close();
		eLog("user", "Abfrage $_REQUEST[doedit] editiert von $_SESSION[u_user]");
	} else {
		$qr  = "INSERT INTO " . $sql_prefix . "abfragen(rubrik,typ,zahl,titel,template) VALUES ";
		$qr .= "('$_REQUEST[abf_rub]','$_REQUEST[abf_pos]','$_REQUEST[abf_count]','$_REQUEST[abf_titel]','$_REQUEST[abf_template]')";
		$sql =& new MySQLq();
		$sql->Query($qr);
		$sql->Close();
		eLog("user", "Abfrage $_REQUEST[abf_titel] erstellt von $_SESSION[u_user]");
	}
	?>
<script language="javascript">
	<!--
	parent.frames['struktur'].location.href = parent.frames['struktur'].location.href;
	//-->
	</script>
<table width=100%  border="1" cellpadding="4" cellspacing="0" bordercolorlight="#DCDEE4" bordercolordark="#FFFFFF">

          <tr> 
                <td align="left" valign="top" class="boxstandart">
				Die Abfrage wurde gespeichert und steht
				ab sofort zur Verf&uuml;gung.<br>
				<?
				if (isset($_REQUEST['doedit']) && $_REQUEST['doedit'] > 0) {
					?>
					Die &Auml;nderungen werden sofort wirksam.
				<?
				}
				?>			    </td>
  </tr>
</table>
	<?
}
?>

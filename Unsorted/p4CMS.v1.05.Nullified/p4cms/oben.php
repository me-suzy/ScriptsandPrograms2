<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
<html>
<head>
 <? StyleSheet(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><style type="text/css">
<!--
body {
	background-color: #fefefe;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>
<body background="" topmargin="0" leftmargin="0">
 <table width="100%" cellspacing="0" cellpadding="0">
  <tr>
   <td align="left" valign="top" background="/p4cms/gfx/main/obgfr.gif">
  
    <form style="display:inline;" name=JUMPTO_1>
	&nbsp;<select name="categ" class="select" id="module" style=""  onChange="if (this.form.categ.options[this.form.categ.selectedIndex].value.length!=0) window.open(this.form.categ.options[this.form.categ.selectedIndex].value,'inhalt'); window.open('struktur_system.php','struktur')">
  <option value="module_main.php">Module</option>
  <option value="module_main.php">Modul installieren</option>
  <?
   	$sql =& new MySQLq();
	$sql->Query("SELECT * FROM " . $sql_prefix . "module ORDER BY name");
	while ($row = $sql->FetchRow()) {
	?>
   <OPTGROUP label="<?=$row->titel;?>">
  			<?
			$inserts = explode(";", $row->optionen);
			for ($i=0; $i<count($inserts); $i++) {
			  	$vals = explode(",", $inserts[$i]);
				
			?>
			<option value="module.php?module=<?=$row->name;?>&<? echo($sessid); ?>&page=<?
			
			for ($v=1; $v<count($vals); $v++) {
			echo $vals[$v];
			}
			 ?>"><? $name = explode(",",$inserts[$i]); echo "- ".$name[0];?></option>
			<?
			}
			?>
   <? } ?>
   </select>
   </form>
   <a href="javascript:SwitchPage('struktur_system.php?d4sess=<? echo($sessid); ?>','struktur');SwitchPage('system.php?d4sess=<? echo($sessid); ?>','inhalt');"><img src="/p4cms/gfx/main/sys.gif" width="27" height="25" vspace="3" border="0" align="absmiddle">System</a>&nbsp;&nbsp;
     <a href="javascript:SwitchPage('backup_manager.php?d4sess=<? echo($sessid); ?>','inhalt');"><img src="/p4cms/gfx/main/backup.gif" border="0" align="absmiddle">Backup</a>&nbsp;&nbsp;&nbsp;<a href="javascript:SwitchPage('wartung.php?d4sess=<? echo($sessid); ?>','inhalt');"><img src="/p4cms/gfx/main/wartung.gif" width="27" height="25" border="0" align="absmiddle">Wartung</a>&nbsp;&nbsp;&nbsp;<a href="javascript:SwitchPage('rebuild.php?d4sess=<? echo($sessid); ?>','inhalt');"><img src="/p4cms/gfx/main/rebuild.gif" width="27" height="25" border="0" align="absmiddle">Rebuild</a>&nbsp;&nbsp;&nbsp;<a href="javascript:SwitchPage('benutzer.php?d4sess=<? echo($sessid); ?>','inhalt');"><img src="/p4cms/gfx/main/benutzer_g.gif" width="27" height="25" border="0" align="absmiddle">Benutzer</a>&nbsp;&nbsp;&nbsp;<a href="javascript:SwitchPage('gruppen.php?d4sess=<? echo($sessid); ?>','inhalt');"><img src="/p4cms/gfx/main/gruppen.gif" width="27" height="25" border="0" align="absmiddle">Gruppen</a>&nbsp;&nbsp;&nbsp;<a href="javascript:SwitchPage('logs.php?d4sess=<? echo($sessid); ?>','inhalt');"><img src="/p4cms/gfx/main/logbrowser.gif" width="27" height="25" border="0" align="absmiddle">Logs</a>&nbsp;&nbsp;&nbsp;<a href="#" onClick="window.open('filemanager.php?d4sess=<? echo($sessid); ?>', 'filemanager', 'width=600,height=500,top=0,left=0');"><img src="/p4cms/gfx/main/dateimanager.gif" width="27" height="25" border="0" align="absmiddle">Dateimanager</a></td>
   <td width="5" align="right" bgcolor="FEFEFE"><a href="about.php" target="inhalt"><img src="/p4cms/gfx/main/obgfr.gif" width="5" height="52" border="0"></a></td>
 </table>
</body>
</html>

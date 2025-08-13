<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
<html>
<head>
 <title>D4C.M Frame Struktur</title>
 <? StyleSheet(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"></head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="struktur">
<table width="195" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#eeeeee">
<tr>
<td width="5" bgcolor="#ffffff">&nbsp;</td>
<td bgcolor="#ffffff" valign="top"><br>
	<script type="text/javascript">
		<!--

		d = new dTree('d');

		d.add(0,-1,"System");
	    d.add(1,0,"Übersicht","javascript:SwitchPage('system.php?d4sess=<? echo($sessid); ?>', 'inhalt');");
	    d.add(2,0,"Rebuild","javascript:SwitchPage('rebuild.php?d4sess=<? echo($sessid); ?>', 'inhalt');");
	    d.add(3,0,"Wartung","javascript:SwitchPage('wartung.php?d4sess=<? echo($sessid); ?>', 'inhalt');");
	    d.add(4,0,"Backup-Manager","javascript:SwitchPage('backup_manager.php?d4sess=<? echo($sessid); ?>', 'inhalt');");
	    d.add(5,0,"Benutzer","javascript:SwitchPage('benutzer.php?d4sess=<? echo($sessid); ?>', 'inhalt');");
	    d.add(6,0,"Gruppen","javascript:SwitchPage('gruppen.php?d4sess=<? echo($sessid); ?>', 'inhalt');");
		d.add(7,0,"Log-Browser","javascript:SwitchPage('logs.php?d4sess=<? echo($sessid); ?>', 'inhalt');");
		d.add(8,0,"Dateimanager","javascript:SwitchPage('filemanager.php?d4sess=<? echo($sessid); ?>', 'inhalt');");
		d.add(9,0,"Interne News","javascript:SwitchPage('newsintern.php?d4sess=<? echo($sessid); ?>', 'inhalt');");
		d.add(10,0,"Module","javascript:SwitchPage('module_main.php?d4sess=<? echo($sessid); ?>','inhalt');javascript: d.o(10);","","","gfx/tree/folder.gif");
		<?
		
		$zaehler = 10;
		$da = false;
		$sql =& new MySQLq();
		$sql->Query("SELECT * FROM " . $sql_prefix . "module ORDER BY titel ASC");
		while ($row = $sql->FetchRow()) {
			$zaehler++;
			$da = true;
			echo "d.add($zaehler,10,\"$row->titel\",\"\",\"\",\"\",\"gfx/tree/folder.gif\");\n";
			$optionen = explode(";", $row->optionen);
			$aktl = $zaehler;
			if ($row->optionen!="") {
				for ($i=0; $i<count($optionen); $i++) {
					$zaehler++;
					$aktuell = explode(",", $optionen[$i]);
					echo "d.add($zaehler,$aktl,\"$aktuell[0]\",\"javascript:SwitchPage('module.php?module=$row->name&d4sess=$sessid&page=$aktuell[1]','inhalt');\",\"\",\"\",\"\");\n";
				}
			}
		}
		if (!$da) {
			echo "d.add(6,5,\"(keine)\",\"\",\"\",\"\",\"\");";
		}
		$sql->Close();
		?>
				
	    document.write(d);
		
		//-->
	</script>
</td>
</tr>
</table>
</body>
</html>
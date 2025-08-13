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
</head>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">
<?
if ($HTTP_SESSION_VARS[u_gid] == 1) {
	if ($_REQUEST['action']=="do") {
		$tables = "";
		while (list($key, $val) = each($_REQUEST['tabellen'])) {
			$tables .= ", `$val`";
		}
		$tables = substr($tables, 1);
		if ($_REQUEST['whattodo']=="optimize") {
			$query = "OPTIMIZE TABLE ";
			eLog("user", "$_SESSION[u_user] optimiert Tabellen");
		} else {
			$query = "REPAIR TABLE ";
		    eLog("user", "$_SESSION[u_user] repariert Tabellen");
		}
		$query .= $tables;
		$sql =& new MySQLq();
		if ($sql->Query($query)) {
			$ok = true;
		} else {
			$ok = false;
		}
		$sql->Close();
		if ($ok) {
			$text = "<center>Die Aktion wurde erfolgreich durchgef&uuml;hrt.</center>";
		} else {
			$text = "<center>Es ist ein Fehler aufgetreten (MySQL-Fehler).</center>";
		}
		MsgBox($text);
	} else {
	$tabellen = "";
	$sql =& new MySQLq();
	$sql->Query("SHOW TABLES");
	while ($row = $sql->FetchArray()) {
		$titel = $row[0];
		if (substr($titel, 0, strlen($sql_prefix)) == $sql_prefix) {
			$tabellen .= "<option value=\"$titel\" selected>$titel</option>\n";
		}
	}
	$sql->Close();

	$msg = "Hier k&ouml;nnen Sie die p4CMS-Datenbank optimieren, sodass eventuell nicht freigewordener Datenbankspeicher freigegeben wird. Das System wird dadurch beschleunigt und es wird Speicherplatz eingespart. Auch ist eine Reperatur m&ouml;glich, falls der Datenbankserver &quot;MySQL&quot; einmal unerwartet beendet wurde und die Tabellen schaden genommen haben. Die Durchf&uuml;hrung behebt diese Probleme.<br><br>
	<form action=\"wartung.php?d4sess=$d4sess&action=do\" method=\"post\" style=\"display:inline;\"><table><tr><td><select size=14 name=\"tabellen[]\" multiple>$tabellen</select></td>
	<td>&nbsp;Markierte Tabellen:<br /><br />&nbsp;&nbsp;<input type=radio name=whattodo checked value=optimize /> Optimieren<br />
	&nbsp;&nbsp;<input type=radio name=whattodo value=repair /> Reparieren</td></tr></table>
	<center><input class=button type=\"submit\" value=\"Aktion durchf&uuml;hren\" /></center></form>";
	MsgBox($msg);
	}
} else {
StyleSheet();
	$msg = "<center>Sie besitzen nicht die Rechte, diese Aktion auszuführen.</center>";
	MsgBox($msg);
}
?>
</body>
</html>
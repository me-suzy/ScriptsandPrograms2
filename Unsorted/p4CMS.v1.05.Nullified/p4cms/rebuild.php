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
	if ($_REQUEST['really']=="yes") {
		eLog("user", "$_SESSION[u_user] lässt alle Seiten neu zusammenführen");
		$sql =& new MySQLq();
		$sql->Query("UPDATE " . $sql_prefix . "dokumente SET published='no' WHERE published='yes'");
		$anzahl = $sql->Affected();
		$sql->Close();
		RenderPages();
		$msg  = "<center>Der angeforderte Vorgang wurde durchgef&uuml;hrt. Es wurden $anzahl Seiten neu zusammengef&uuml;hrt.</center>";
		MsgBox($msg);
	} else {
		$msg  = "Hier k&ouml;nnen sie alle Seiten neu zusammenf&uuml;hren lassen. Dabei werden alle Dokumente neu aus der Datenbank eingelesen, gerendert und als HTML-Datei in das Dateisystem abgelegt. Damit stellen Sie sicher, dass alle Seiten auf dem neuesten Stand sind.<br><br><center>Wollen Sie wirklich alle Dokumente, die je erstellt wurden, neu zusammenf&uuml;hren?";
		$msg .= "<br /><br /><a href=\"rebuild.php?really=yes&d4sess=$d4sess\"><img src=\"gfx/main/ja.gif\" border=\"0\" /></a>&nbsp;<a href=\"javascript:history.back();\"><img src=\"gfx/main/nein.gif\" border=\"0\" /></a></center>";
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
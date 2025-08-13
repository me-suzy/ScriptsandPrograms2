<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
<html>
<head>
<title></title>
<? StyleSheet(); ?>
</head>
<body class="struktur" topmargin="0" leftmargin="0">
<?
$grp = Gruppe($HTTP_SESSION_VARS[u_gid]);
$msg = "<b>Wilcome, " . $HTTP_SESSION_VARS[u_name] . "!</b>
<br><br>
<i>Ihre Rechte (Gruppe [" . $grp[titel] . "]):</i>
<table>
<tr>
<td>&nbsp;&nbsp;Redakteure/Gruppen anlegen/editieren&nbsp;&nbsp;</td>
<td><b>" . Yes2Ja($grp[m_redakteur]) . "<b></td>
</tr>
<tr>
<td>&nbsp;&nbsp;Vorlagen anlegen/editieren</td>
<td><b>" . Yes2Ja($grp[m_vorlagen]) . "<b></td>
</tr>
<tr>
<td>&nbsp;&nbsp;Abfragen anlegen/editieren</td>
<td><b>" . Yes2Ja($grp[m_abfragen]) . "<b></td>
</tr>
<tr>
<td>&nbsp;&nbsp;Dokumente anlegen/editieren</td>
<td><b>" . Yes2Ja($grp[m_dokumente]) . "<b></td>
</tr>
<tr>
<td>&nbsp;&nbsp;In den Mediapool hochladen</td>
<td><b>" . Yes2Ja($grp[m_mediapool]) . "<b></td>
</tr>
<tr>
<td>&nbsp;&nbsp;Newsletter schreiben</td>
<td><b>" . Yes2Ja($grp[m_newsletter]) . "<b></td>
</tr>
</table>";

MsgBox($msg);
?>
</body>
</html>
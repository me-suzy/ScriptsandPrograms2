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
<table width="195" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F4F5F7">
  <tr>
    <td align="center" width="100%" colspan="2" height="25">[ <a href="vorlage.php?mode=new&d4sess=<? echo($sessid); ?>" target="inhalt">Neue 
      Vorlage erstellen</a> ]</td>
</tr>
<tr>
<td width="5" bgcolor="#ffffff">&nbsp;</td>
<td bgcolor="#ffffff" valign="top"><br>
	<script type="text/javascript">
		<!--

		d = new dTree('d');

		d.add(0,-1,"Vorlagen","javascript:SwitchPage('vorlagen_main.php?d4sess=<?=$sessid;?>', 'inhalt');");
<?
$i = 0;
$sql =& new MySQLq();
$sql->Query("SELECT * FROM " . $sql_prefix . "vorlagen ORDER BY titel DESC");
while ($row = $sql->FetchRow()) {
	$i++;
	echo "		d.add($i,0,\"" . stripslashes($row->titel) . "\",\"javascript:EditVorlage('$row->id','$sessid');\");\n";
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
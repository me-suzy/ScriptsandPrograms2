<?
 include("include/include.inc.php");
 
 if (!isset($HTTP_SESSION_VARS[u_loggedin]) or !($HTTP_SESSION_VARS[u_loggedin]=='yes')) {
  SessionError();
  exit;
 }
?>
<html>
<head>
 <title>Variable erstellen</title>
 <link rel="stylesheet" href="style/style.css">
 <? StyleSheet(); ?>
</head>
<body bgcolor="" background="/p4cms/gfx/main/bgbody.gif">
<?
 if ($_REQUEST[action] == "" or !isset($_REQUEST[action])) {
	 ?>
<form action="addvar.php?action=add" method="post" name="fm">
<input type="hidden" name="varfor" value="<? echo($_REQUEST[varfor]); ?>">
<?
 if (substr($_REQUEST[varfor],0,8)=="abfragen") {
	$rubrik = str_replace("abfragen", "", $_REQUEST['varfor']);

	$felder = "";
	$sql =& new MySQLq();
	$sql->Query("SELECT * FROM " . $sql_prefix . "rubriken_felder WHERE rubrik='$rubrik'");
	while ($row = $sql->FetchRow()) {
		$felder .= "<option value=\"$row->id\">" . stripslashes($row->titel) . "</option>\n";
	}
	$sql->Close();
	?>
Sie k&ouml;nnen hier eine Variable erstellen, und damit zb. einen Text in verk&uuml;rzter Form ausgeben. Sie k&ouml;nnen sowohl den Startbeginn, wie auch das Ende des Textes durch Werte von 0 - xxx definieren. <br>
<br> 
<table width="100%" border="0" cellpadding="3" cellspacing="1">
  <tr>
      <td>Quellfeld:</td>
	<td><select name="feld" class="inputfield"><?=$felder;?>
	</select></td>
	</tr>
	<tr>
	  <td width="10%" nowrap>Titel der Variable:</td>
	  <td><input name="titel" type="text" class="inputfield" size="15" /></td></tr>
	<tr>
	  <td>Typ:</td>
	<td>Text-K&uuml;rzung</td></tr>
	<tr>
	  <td>Start:</td>
	<td><input name="start" type="text" class="inputfield" value="0" size="6" maxlength="5" />
	- ab diesem Zeiche wird ausgegeben  
	</td>
	</tr>
	<tr>
	  <td>L&auml;nge:</td>
	<td><input name="count" type="text" class="inputfield" value="50" size="6" maxlength="5" />
	- bis Zeichen 50 wird der Text ausgegeben </td>
	</tr>
	<tr>
	  <td>Anhang:</td>
	<td><input name="fort" type="text" class="inputfield" value="..." size="6" /> 
	- Endung nach dem Text (nach 50 Zeichen) </td>
	</tr>
	<tr>
	  <td>&nbsp;</td>
	<td><input type="submit" class="button" value="Anlegen" /></td></tr>
</table>
	<?
 }
}

if ($_REQUEST['action']=="add") {
	$rub = str_replace("abfragen", "", $_REQUEST['varfor']);
	$query 	= "INSERT INTO " . $sql_prefix . "variablen(titel,name,tw_var,tw_var_t,tw_start,tw_count,rub) VALUES ";
	$query .= "('$_REQUEST[titel]', '', '$_REQUEST[feld]', '$_REQUEST[fort]', '$_REQUEST[start]', '$_REQUEST[count]', '$rub')";	
	$sql =& new MySQLq();
	$sql->Query($query);
	$vid = $sql->IId();
	$sql->Close();
	eLog("user", "Variable für Rubrik $rub erstellt von $_SESSION[u_user]");
	?>
  Die Variable wurde angelegt und ist &uuml;ber den Code <b>{USER:
  <?=$vid;?>
  }</b> ab sofort nutzbar. Beachten Sie, dass
	die Variable erst nach neuladen der Seite / des Variablen-Explorers
	in dem Variablen-Explorer sichtbar wird.
  <br>
  <br>
  [ <a href="javascript:window.close();">Schlie&szlig;en</a> ]
	<?
}
?>
</form>
</body>
</html>

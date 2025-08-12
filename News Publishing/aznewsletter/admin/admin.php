<?php
include ("../config.php");
//Verbindung zur Datenbank herstellen
//---------------------------------------------------------------------------------------------
mysql_connect($dbserver,$dbuser,$dbpass) or die ("Die Verbindung zum MySQL-Datenbankserver ist fehlgeschlagen");
mysql_select_db($db) or die ("Die benötigte Datenbank konnte nicht gefunden werden");
//---------------------------------------------------------------------------------------------
?>
<center>
<img src="images/newsletter_title.gif"><br><br>

<table border="1" width="600">
  <tr>
    <td width="200" nowrap>Titel des Newsletters</td>
    <td><form action="admin.php" method="get"><input name="titel" type="text" size='59'><input name="senden" type="hidden" value="1"></td>
  </tr>
  <tr>
    <td width="100" valign="top">Meldung</td>
    <td><textarea name="meldung" cols="45" rows="20">&nbsp;</textarea></td>
  </tr>
  <tr>
    <td width='100'>&nbsp;</td>
    <td align="center"><input name="" value="Newsletter versenden"  type="submit"></td>
  </tr>
</table>
</form>
<br>
<table border="1" width="600"><tr><td>eMail-Adresse</td><td width="70" nowrap>löschen ?</td></tr>
<?php
//Löschroutine
if (isset ($delete))
{
$geloescht=mysql_query ("DELETE FROM $dbtable WHERE ID=$delete");
}
//Senderoutine
elseif ($senden==1)
{
while($row = mysql_fetch_object($abfrage))
{
mail($row->MAIL,$titel,$meldung,"From: \"AZNEWSLETTER\" <$adminmail>");
}
}
$abfrage= mysql_query("SELECT * FROM $dbtable");
while($row = mysql_fetch_object($abfrage))
    {
    echo "<tr><td>",$row->MAIL,"</td><td align=\"center\"><a href=\"admin.php?delete=",$row->ID,"\")\"><img SRC=\"images/loeschen.gif\" border=\"0\"></a></td></tr>";
	};

?>			
</table><br><center><img src="images/copyright.gif"></center>

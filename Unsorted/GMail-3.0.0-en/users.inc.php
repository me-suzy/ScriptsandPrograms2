<?
 include("config.inc.php");

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_users";
$ergebnis = mysql_query($sql, $verbindung);

$count = 0;

 while($row = mysql_fetch_object($ergebnis))
  {

   $count++;
 
  }

mysql_free_result($ergebnis);
mysql_close($verbindung);

echo($count);
?>


<?
include ("config.inc.php");

if ($ads=="1") {
$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_banner ORDER BY rand() LIMIT 1";
$ergebnis = mysql_query($sql, $verbindung);

 while($row = mysql_fetch_object($ergebnis))
  {

    $bannercode = stripslashes($row->code);

  }

mysql_free_result($ergebnis);
mysql_close($verbindung);

$bannercode = str_replace ( "\"", "'", $bannercode);

$bannercode = str_replace ( "
", "", $bannercode);


echo("document.write(\"${bannercode}\");");
}
?>

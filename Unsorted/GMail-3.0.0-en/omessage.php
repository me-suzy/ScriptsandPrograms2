<? header("Cache-Control: no-cache"); ?>
<?
 session_start();
 include ("config.inc.php");

$nmail = 0;


$usermail = strtolower($user."@".$sdomain);


$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_outbox WHERE id='$id'";
$ergebnis = mysql_query($sql, $verbindung);

$cont = 0;

 while($row = mysql_fetch_object($ergebnis))
  {

    $betreff  = $row->Titel;
    $absender = $row->Von;
    $datum    = $row->Datum;
    //$text     = $row->Body;
    $cc       = $row->CC;
    $an       = $row->An;
    $message  = $row->Body;

  }

mysql_free_result($ergebnis);


$message = nl2br(htmlspecialchars(stripslashes($message)));

$sql = "UPDATE b1gmail_outbox SET Gelesen=1 WHERE id='$id'";
$ergebnis = mysql_query($sql, $verbindung);


mysql_close($verbindung);


if (strtolower($absender)==strtolower($usermail)) {


 $filename = "templates/${template}/omessage.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 


if ($cc=="") {
 $cc="<i>Keine Kopieempfänger</i>";
} else {
 $cc="$cc";
}

$text = nl2br(stripslashes(htmlspecialchars($text)));

  $output = str_replace ( "%VON%", "$absender", $tmpl);
  $output = str_replace ( "%DATUM%", "$datum", $output);
  $output = str_replace ( "%AN%", "$an", $output);
  $output = str_replace ( "%CC%", "$cc", $output);
  $output = str_replace ( "%BETREFF%", htmlspecialchars($betreff), $output);
  $output = str_replace ( "%TEXT%", "$message", $output);
  $output = str_replace ( "%ID%", "$id", $output);
  $output = str_replace ( "%COPYRIGHT%", "$copyright", $output);

  $output = stripslashes ($output);
 
  echo ($output);

} else {
?>
Fehler: Keine Berechtigung zum Anzeigen der Mail.
<?
}
?>

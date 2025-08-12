<? header("Cache-Control: no-cache");

 session_start();
 include ("config.inc.php");

$nmail = 0;


$usermail = strtolower($user."@".$sdomain);

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_ordner WHERE User='$usermail' ORDER BY Name ASC";
$ergebnis = mysql_query($sql, $verbindung);

$cont = 0;

 while($row = mysql_fetch_object($ergebnis))
  {

$adressen = $adressen . "<option value=\"$row->Name\">$row->Name</option>";

  }

mysql_free_result($ergebnis);
mysql_close($verbindung);



 $filename = "templates/${template}/move.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 


  $output = str_replace ( "%FOLDERS%", "$adressen", $tmpl);
  $output = str_replace ( "%COPYRIGHT%", "$copyright", $output);
  $output = str_replace ( "%ID%", "$id", $output);

  $output = stripslashes ($output);
 
  echo ($output);
?>


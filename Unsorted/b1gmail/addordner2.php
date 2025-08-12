<? header("Cache-Control: no-cache"); 

 session_start();
 include ("config.inc.php");

$nmail = 0;

$b="0";

$usermail = strtolower($user."@".$sdomain);

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);

$sql = "SELECT * FROM b1gmail_ordner WHERE User='$usermail' AND Name='$aname'";
$ergebnis = mysql_query($sql, $verbindung);

 while($row = mysql_fetch_object($ergebnis))
  {
   $b="1";
  }

mysql_free_result($ergebnis);

if ($b=="0") {
$sql = "INSERT INTO b1gmail_ordner (Name, User) VALUES ('$aname', '$usermail')";
$ergebnis = mysql_query($sql, $verbindung);
}


mysql_close($verbindung);


 $filename = "templates/${template}/addordner2.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 



  $output = str_replace ( "%COPYRIGHT%", "$copyright", $tmpl);

  $output = stripslashes ($output);
 
  echo ($output);
?>

<? header("Cache-Control: no-cache"); 
 session_start();
 include ("config.inc.php");

$nmail = 0;


$usermail = strtolower($user."@".$sdomain);
$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_mails WHERE id='$id'";
$ergebnis = mysql_query($sql, $verbindung);

$cont = 0;

 while($row = mysql_fetch_object($ergebnis))
  {

     $an = $row->An;
 
  }

mysql_free_result($ergebnis);

if ($an==$usermail) {
 $sql = "DELETE FROM b1gmail_mails WHERE id='$id'";
 $ergebnis = mysql_query($sql, $verbindung);
} else {
 echo ("<font face=\"arial\" color=\"#ff0000\" size=\"2\"><center>Sie sind nicht berechtigt zum löschen von dieser E-Mail!</center></font>");
}
mysql_close($verbindung);


 $filename = "templates/${template}/deletereal.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 


  $output = str_replace ( "%COPYRIGHT%", "$copyright", $tmpl);


  $output = stripslashes ($output);
 
  echo ($output);
?>

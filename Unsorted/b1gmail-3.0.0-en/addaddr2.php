<? header("Cache-Control: no-cache"); 

 session_start();
 include ("config.inc.php");

$nmail = 0;


$usermail = strtolower($user."@".$sdomain);

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "INSERT INTO b1gmail_adressen (Name, Email, User) VALUES ('$aname', '$amail', '$usermail')";
$ergebnis = mysql_query($sql, $verbindung);

mysql_close($verbindung);


 $filename = "templates/${template}/addaddr2.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 



  $output = str_replace ( "%COPYRIGHT%", "$copyright", $tmpl);

  $output = stripslashes ($output);
 
  echo ($output);
?>

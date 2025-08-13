<? header("Cache-Control: no-cache"); 

 session_start();
 include ("config.inc.php");

$nmail = 0;


$usermail = strtolower($user."@".$sdomain);

if ($pass1==$pass2) {
 $error = "0";
} else {
 $error = "1";
 $errmsg = "Die Passwörter stimmen nicht überein!";
}

$pha = md5($pass1);

if ($error=="0") {
$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);

$sql = "UPDATE b1gmail_users SET Hash='$pha' WHERE User='$usermail'";
$ergebnis = mysql_query($sql, $verbindung);

mysql_close($verbindung);
$errmsg = "Passwort geändert!";
}


 $filename = "templates/${template}/cprofil.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 


  $output = str_replace ( "%COPYRIGHT%", "$copyright", $tmpl);


  $output = str_replace ( "%MSG%", "$errmsg", $output);


  $output = stripslashes ($output);
 
  echo ($output);
?>

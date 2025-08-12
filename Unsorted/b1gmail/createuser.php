<?
 include("config.inc.php");

$error = "0";

 $gesperrt = array(1=>"admin","webmaster","mail","info","postmaster","kontakt","support","hilfe","1","2","3","4","5","6","7","8","9","0","mailrecv");

 for ($i=1;$i<=count($gesperrt);$i++) {
  if (strtolower($gesperrt[$i])==strtolower($user)) {
   $error = "1";
   $errmsg = "Username gesperrt."; 
  }
 }

 $sun = strtolower($user."@".$sdomain);

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_users";
$ergebnis = mysql_query($sql, $verbindung);

$ok = "1";

 while($row = mysql_fetch_object($ergebnis))
  {

   if ($row->User==$sun) {
    $ok = "0";
   }
 
  }

mysql_free_result($ergebnis);
mysql_close($verbindung);


if ($ok=="0") {
 $errmsg = "Username exists: $sun!";
 $error  = "1";
}

if ($pass1==$pass2) {
} else {
 if ($error=="1") {
  $errmsg = $errmsg . "<br>Error while checking passwords!";
 } else {
  $errmsg = "Error while checking passwords!";
  $error  = "1";
 }
}


if ($user=="" or $pass1=="" or $pass2=="" or $name=="" or $strasse=="" or $plz=="" or $ort=="" or $telefon=="") {
 if ($error=="1") {
  $errmsg = "$errmsg<br>Fill in all required fields (*)!";
 } else {
  $error = "1";
  $errmsg = "Fill in all required fields (*)!";
 }
}

if ($error=="0") {
$ph = md5($pass1);
$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "INSERT INTO b1gmail_users (User, Name, Hash, Strasse, PLZ, Ort, Telefon, FAX) VALUES ('$sun', '$name', '$ph', '$strasse', '$plz', '$ort', '$telefon', '$fax')";
$ergebnis = mysql_query($sql, $verbindung);

mysql_close($verbindung);

$errmsg = "Registered successfuly!<br>Your new E-Mail Address is: $sun!";
}


 $filename = "templates/${template}/createuser.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 


  $output = str_replace ( "%MESSAGE%", "$errmsg", $tmpl);
  $output = str_replace ( "%COPYRIGHT%", "$copyright", $output);

  $output = stripslashes ($output);
 
  echo ($output);
?>


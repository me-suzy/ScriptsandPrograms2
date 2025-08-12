<? header("Cache-Control: no-cache"); 
 session_start();
 include ("config.inc.php");

 $an=$an;

$heute = date("d.m.Y") . " " . date("H:i:s");

$usermail = strtolower($user."@".$sdomain);


 $cc = stripslashes($cc);
 $bcc = stripslashes($bcc);
 $betreff = stripslashes($betreff);


 $natext = $text . "

_________________________________________________________________
$sigwe
";

 $outtext = $natext;

$mimesep = "6XG-6XG07-SXGSXGSXG=:-sxgsxg";
$mimeheader = "From: \"" . $name . "\" <" . $usermail . ">\r\n";

 if ($cc=="") {
 } else {
  $mimeheader .= "Cc: $cc" . "\n";
 }

 if ($bcc=="") {
 } else {
   $mimeheader .= "Bcc: $bcc" . "\n";
 }




$mimeheader .= "MIME-Version: 1.0\r\n";
$mimeheader .= "Content-Type: multipart/mixed; BOUNDARY=\"" . $mimesep . "\"\r\n";
$mimeheader .= "X-Mailer: b1gMail (www.b1g.de)"."\r\n\r\n";

$newmessage = "--" . $mimesep . "\r\n";
$newmessage .= "Content-Type: text/plain ; CHARSET=iso-8859-1\r\n";
$newmessage .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
$newmessage .= imap_8bit (stripslashes ($natext));

if ($file1=="none" or $file1=="") {

} else {
	$fp = fopen ($HTTP_POST_FILES['file1']['tmp_name'], "rb");
	$contents = fread ($fp, filesize ($HTTP_POST_FILES['file1']['tmp_name']));
	fclose ($fp);

	$newmessage .= "\r\n\r\n--" . $mimesep . "\r\n";
	$newmessage .= "Content-Type: " . stripslashes ($HTTP_POST_FILES['file1']['type']) . "; name=\"" . $HTTP_POST_FILES['file1']['name'] . "\"\r\n";
	$newmessage .= "Content-Transfer-Encoding: base64\r\n";
	$newmessage .= "Content-Disposition: ATTACHMENT; filename=\"" . $HTTP_POST_FILES['file1']['name'] . "\"\r\n\r\n";
	$newmessage .= imap_binary ($contents);
}

if ($file2=="none" or $file2=="") {

} else {
	$fp = fopen ($HTTP_POST_FILES['file2']['tmp_name'], "rb");
	$contents = fread ($fp, filesize ($HTTP_POST_FILES['file2']['tmp_name']));
	fclose ($fp);

	$newmessage .= "\r\n\r\n--" . $mimesep . "\r\n";
	$newmessage .= "Content-Type: " . stripslashes ($HTTP_POST_FILES['file2']['type']) . "; name=\"" . $HTTP_POST_FILES['file1']['name'] . "\"\r\n";
	$newmessage .= "Content-Transfer-Encoding: base64\r\n";
	$newmessage .= "Content-Disposition: ATTACHMENT; filename=\"" . $HTTP_POST_FILES['file2']['name'] . "\"\r\n\r\n";
	$newmessage .= imap_binary ($contents);
}

$newmessage .= "\r\n--" . $mimesep . "--\r\n\r\n";

 $re = mail($an, $betreff, $newmessage, $mimeheader);
 $sql = "INSERT INTO b1gmail_outbox (Titel, Von, An, CC, BCC, Body, Datum) VALUES ('$betreff','$usermail','$an','$cc','$bcc','$outtext','$heute')";


$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";
mysql_select_db($sql_db, $verbindung);
$ergebnis = mysql_query($sql, $verbindung);

mysql_close($verbindung);


 if ($re) {
  $tdd = "Die Nachricht wurde erfolgreich versendet.";
 } else {
  $tdd = "Es gab einen Fehler beim Versenden der Nachricht.";
 }



 $filename = "templates/${template}/mailsend.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 


  $output = str_replace ( "%MESSAGE%", "$tdd", $tmpl);
  $output = str_replace ( "%COPYRIGHT%", "$copyright", $output);

  $output = stripslashes ($output);
 
  echo ($output);
?>

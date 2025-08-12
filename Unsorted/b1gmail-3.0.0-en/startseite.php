<? header("Cache-Control: no-cache"); 
 session_start();
 include ("config.inc.php");
?>



<!--

  AUSGABEN VON MAIL FETCH SCRIPT


<?



$usermail = strtolower($user."@".$sdomain);

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_mails WHERE An='$usermail'";
$ergebnis = mysql_query($sql, $verbindung);

$cont = 0;

 while($row = mysql_fetch_object($ergebnis))
  {
   if ($row->Gelesen=="0") {
    $nmail = $nmail + 1;
   }
   $vb = $vb + strlen($row->Body);
  }

mysql_free_result($ergebnis);
mysql_close($verbindung);

$vb = $vb / 1024;
$vh = $speicher * 1024;


$vb = round($vb);
$vh = round($vh);

if ($vb>$vh) {
?>
//Speicher überschritten
<?
} else {

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);







// Mails abholen und in Datenbank zuordnen
$mbox = imap_open ("$pop_host", "$pop_user", "$pop_pass");

$messages = imap_search ($mbox, "TO $usermail");

if ($messages) {
$messagecount = count($messages);

for ($i=0; $i < $messagecount; $i++) {

 $msgheader = imap_header($mbox, $messages[$i]);
 
 $to = $msgheader->to[0];
 $from = $msgheader->from[0];

 $an = strtolower($to->mailbox . "@" . $to->host);


 $von = $from->mailbox . "@" . $from->host;


 $betreff = $msgheader->subject;


// Alt $body = imap_body($mbox, $messages[$i]);
// Alt $body = get_part ($mbox, $messages[$i], "TEXT/PLAIN");

 $body = imap_fetchheader($mbox, $messages[$i], FT_INTERNAL | FT_PREFETCHTEXT) . "\n\n" . imap_body($mbox, $messages[$i]);

 $cc   = $msgheader->ccaddress;

 $datum = $msgheader->date;


$sql = "INSERT INTO b1gmail_mails (Titel, Von, Datum, CC, An, Body, Gelesen, Beantwortetet, Weitergeleitet, Ordner) VALUES ('$betreff', '$von', '$datum', '$cc', '$an', '$body', '0', '0' ,'0', 'Posteingang')";


$ergebnis = mysql_query($sql, $verbindung);


 imap_delete($mbox, $messages[$i]);
}

}
imap_close ($mbox, CL_EXPUNGE);
mysql_close($verbindung);

}
?>
-->


<?

$nmail = 0;
$vb = 0;

$usermail = strtolower($user."@".$sdomain);

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_mails WHERE An='$usermail'";
$ergebnis = mysql_query($sql, $verbindung);

$cont = 0;

 while($row = mysql_fetch_object($ergebnis))
  {
   if ($row->Gelesen=="0") {
    $nmail = $nmail + 1;
   }
   $vb = $vb + strlen($row->Body);
  }

mysql_free_result($ergebnis);
mysql_close($verbindung);

$vb = $vb / 1024;
$vs = round($vb,2);
$vh = $speicher * 1024;
$vs = "$vs KB von $vh KB belegt";

$teins = $vh / 100;
$prozent = $vb / $teins;
$prozent = round($prozent,2);

$breite = ($prozent * 150) / 100;

$balken = "<table width=\"150\" bgcolor=\"#ffffff\" border=\"0\" cellspacing=\"0\"><tr><td><img src=\"spix.gif\" height=\"10\" width=\"$breite\" border=\"0\"></td></tr></table>";

$vs = $balken . "$prozent% = ".$vs;

 $filename = "templates/${template}/startseite.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 
 
  $output = str_replace ( "%ANZAHL%", "$nmail", $tmpl);
  $output = str_replace ( "%USER%", "$usermail", $output);
  $output = str_replace ( "%SPEICHER%", $vs, $output);
  $output = str_replace ( "%COPYRIGHT%", $copyright, $output);

  $output = stripslashes ($output);
 
  echo ($output);
?>

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


$usermail = strtolower($user."@".$sdomain);

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_mails WHERE An='$usermail' AND Ordner='Posteingang' ORDER BY id DESC";
$ergebnis = mysql_query($sql, $verbindung);

$cont = 0;

 while($row = mysql_fetch_object($ergebnis))
  {
 
 if ($row->Gelesen=="0") {
  $cl = "FF";
  $lclass = "newmsg";
 } else {
  $cl = "00";
  $lclass = "oldmsg";
 }

 if ($row->Titel=="") {
  $msgt="[Kein Titel]";
 } else {
  $msgt = $row->Titel;
 }

 $emails = $emails . "
<tr>
  <td bgcolor=\"#ffffff\" align=\"center\"><input type=\"checkbox\" name=\"m$row->id\"></td>
  <td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"2\" color=\"#${cl}0000\"><center>".htmlspecialchars($row->Von)."</center></font></td>
  <td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"2\" color=\"#${cl}0000\"><center><a href=\"message.php?id=$row->id\" class=\"$lclass\">".htmlspecialchars($msgt)."</a></center></font></td>
  <td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"2\" color=\"#${cl}0000\"><center>$row->Datum</center></font></td>
 </tr>";

  }

mysql_free_result($ergebnis);
mysql_close($verbindung);


 $filename = "templates/${template}/inbox.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 


  $output = str_replace ( "%MAILS%", "$emails", $tmpl);
  $output = str_replace ( "%COPYRIGHT%", "$copyright", $output);

  $output = stripslashes ($output);
 
  echo ($output);
?>


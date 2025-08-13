<? header("Cache-Control: no-cache"); 
 session_start();
 include ("config.inc.php");

$nmail = 0;


$usermail = strtolower($user."@".$sdomain);

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_outbox WHERE Von='$usermail' ORDER BY id DESC";
$ergebnis = mysql_query($sql, $verbindung);

$cont = 0;

 while($row = mysql_fetch_object($ergebnis))
  {

  $cl = "00";
  $lclass = "oldmsg";


 if ($row->Titel=="") {
  $msgt="[Kein Titel]";
 } else {
  $msgt = $row->Titel;
 }

 $emails = $emails . "
<tr>
  <td bgcolor=\"#ffffff\" align=\"center\"><input type=\"checkbox\" name=\"m$row->id\"></td>
  <td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"2\" color=\"#${cl}0000\"><center>".htmlspecialchars($row->An)."</center></font></td>
  <td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"2\" color=\"#${cl}0000\"><center><a href=\"omessage.php?id=$row->id\" class=\"$lclass\">".htmlspecialchars($msgt)."</a></center></font></td>
  <td bgcolor=\"#ffffff\"><font face=\"arial\" size=\"2\" color=\"#${cl}0000\"><center>$row->Datum</center></font></td>
 </tr>";

  }

mysql_free_result($ergebnis);
mysql_close($verbindung);


 $filename = "templates/${template}/outbox.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 


  $output = str_replace ( "%MAILS%", "$emails", $tmpl);
  $output = str_replace ( "%COPYRIGHT%", "$copyright", $output);

  $output = stripslashes ($output);
 
  echo ($output);
?>


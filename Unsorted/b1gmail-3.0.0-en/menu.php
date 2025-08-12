<?
session_start();
include ("config.inc.php");

$usermail = strtolower($user."@".$sdomain);

 $filename = "templates/${template}/menu.htm";
 $fd = fopen ($filename, "r");
  $tmpl = fread ($fd, filesize ($filename));
 fclose ($fd); 

$odn = "";

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_ordner WHERE User='$usermail' ORDER BY id ASC";
$ergebnis = mysql_query($sql, $verbindung);

$cont = 0;

 while($row = mysql_fetch_object($ergebnis))
  {
   $odn = $odn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"ordner.gif\" border=\"0\"><a href=\"showordner.php?oname=$row->Name\" class=\"menu\">$row->Name</a><br>";
  }

mysql_free_result($ergebnis);
mysql_close($verbindung);


  $output = str_replace ( "%COPYRIGHT%", "$copyright", $tmpl);

  $output = str_replace ( "%ORDNER%", "$odn", $output);

  $output = stripslashes ($output);
 
  echo ($output);

?>

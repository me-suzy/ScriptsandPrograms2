<? header("Cache-Control: no-cache"); 
include("config.inc.php"); 

$userna = "${usern}@${adomain}";

$verbindung=mysql_connect($sql_server,$sql_user,$sql_passwort);
if (!$verbindung)
echo "Es konnte keine Datenbankverbindung hergestellt werden.";

mysql_select_db($sql_db, $verbindung);
$sql = "SELECT * FROM b1gmail_users WHERE User='$userna'";
$ergebnis = mysql_query($sql, $verbindung);

$ph = md5($pass);
$ok = "0";

 while($row = mysql_fetch_object($ergebnis))
  {

   if ($row->Hash==$ph) {
    $ok = "1";
    $anam = $row->Name;
   } 
 
  }

mysql_free_result($ergebnis);
mysql_close($verbindung);

if ($ok=="1") {
 session_start();
 
 session_register('user');
 session_register('name');
 session_register('sdomain');

 $user = $usern;
 $name = $anam;
 $sdomain = $adomain;

 include("templates/${template}/login_ok.htm");

} else {

 include("templates/${template}/login_error.htm");

}
?>

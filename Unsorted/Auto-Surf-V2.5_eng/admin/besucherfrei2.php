<?
include("header.inc.php");
require('../prepend.inc.php');
$newsid=mt_srand((double)microtime()*1000000);
$newsid=md5(str_replace('.', '', getenv('REMOTE_ADDR') + mt_rand(100000, 999999)));

$result = mysql_query("SELECT name, email, password, points, url FROM `demo_a_bebuchen` WHERE `rechnung` = '$rechn'");
$myrow = mysql_fetch_row($result);
$user = $myrow[0];
$mail = $myrow[1];
$pass = $myrow[2];
$punkte = $myrow[3];
$seite = $myrow[4];
$showup = 1;

$query="INSERT INTO demo_a_accounts (name, prename, password, email, url, showup, points, sessionid, refererid) VALUES ('$user', '$user', '$pass', '$mail', '$seite', '$showup', '$punkte', '$newsid', '$referer');";
                mysql_query($query);

$query2="DELETE FROM demo_a_bebuchen WHERE `rechnung` = '$rechn'";
         mysql_query($query2);

mail("$mail", "Sponsor account validated", "Dear sponsor \n\nYour sponsor account with an amount of $punkte visits was validated\nTo see your stats please visit $url_index and use the sponsor login\n \nYour login data\ne-mail = $mail\nPassword = $pass\n\nYours\n  $seitenname ","From: $seitenname <$emailadresse>");
?>
<?
include("../templates/admin-header.txt");
?>
<TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" align="center" width="98%">
<TR>
  <TD width="200" bgcolor="#E6E6E6"><b>Name:</b></TD>
  <TD bgcolor="#E6E6E6"><b><? echo "$user"; ?></b></TD>
</TR>
<TR>
  <TD width="200"><b>E-Mail:</b></TD>
  <TD><b><? echo "$mail"; ?></b></TD>
</TR>
<TR>
  <TD width="200" bgcolor="#E6E6E6"><b>Password:</b></TD>
  <TD bgcolor="#E6E6E6"><b><? echo "$pass"; ?></b></TD>
</TR>
<TR>
  <TD width="200"><b>Points:</b></TD>
  <TD><b><? echo "$punkte"; ?></b></TD>
</TR>
<TR>
  <TD width="200" bgcolor="#E6E6E6"><b>URL:</b></TD>
  <TD bgcolor="#E6E6E6"><b><? echo "$seite"; ?></b></TD>
</TR>
</TABLE><br><center><B>Validated!</b></center>
<?
include("../templates/admin-footer.txt");
?>
<?
include("header.inc.php");
require('../prepend.inc.php');
$newsid=mt_srand((double)microtime()*1000000);
$newsid=md5(str_replace('.', '', getenv('REMOTE_ADDR') + mt_rand(100000, 999999)));

$result = mysql_query("SELECT name, email, source, views, target, alt FROM `demo_a_babuchen` WHERE `rechnung` = '$rechn'");
$myrow = mysql_fetch_row($result);
$user = $myrow[0];
$mail = $myrow[1];
$bild = $myrow[2];
$punkte = $myrow[3];
$seite = $myrow[4];
$text = $myrow[5];
$null = 0;

$query="INSERT INTO demo_a_banners (name, email, source, views, target, alt, anzahl) VALUES ('$user', '$mail', '$bild', '$punkte', '$seite', '$text', '$punkte');";
                mysql_query($query);

$query2="DELETE FROM demo_a_babuchen WHERE `rechnung` = '$rechn'";
         mysql_query($query2);

mail("$mail", "Sponsor account validated", "Dear sponsor \n\nYour account with an amount of $punkte bannerviews has been validated\nTo see your stats please visit\n$url_index and use the sponsor login\n \nYour login username = $user\n\n\nYours\n  $seitenname ","From: $seitenname <$emailadresse>");
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
  <TD width="200"><b>e-mail:</b></TD>
  <TD><b><? echo "$mail"; ?></b></TD>
</TR>
<TR>
  <TD width="200" bgcolor="#E6E6E6"><b>Banner URL:</b></TD>
  <TD bgcolor="#E6E6E6"><b><? echo "$bild"; ?></b></TD>
</TR>
<TR>
  <TD width="200" bgcolor="#E6E6E6"><b>Target URL:</b></TD>
  <TD bgcolor="#E6E6E6"><b><? echo "$seite"; ?></b></TD>
</TR>
<TR>
  <TD width="200" bgcolor="#E6E6E6"><b>Alt Text:</b></TD>
  <TD bgcolor="#E6E6E6"><b><? echo "$text"; ?></b></TD>
</TR>
<TR>
  <TD width="200"><b>Points:</b></TD>
  <TD><b><? echo "$punkte"; ?></b></TD>
</TR>
</TABLE><br><center><B>Account validated!</b></center>
<?
include("../templates/admin-footer.txt");
?>
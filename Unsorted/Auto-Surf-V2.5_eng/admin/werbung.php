<?
include("header.inc.php");

$result8 = mysql_query("SELECT besuchera, besucherb, besucherc, besucherd, besuchere, besucherf, bannera, bannerb, bannerc FROM `demo_a_werbpreis`");
$myrow = mysql_fetch_row($result8);

$besa = $myrow[0];
$besb = $myrow[1];
$besc = $myrow[2];
$besd = $myrow[3];
$bese = $myrow[4];
$besf = $myrow[5];
$baa = $myrow[6];
$bab = $myrow[7];
$bac = $myrow[8];

?>
<?
include("../templates/admin-header.txt");
?>
<form method="post" action="werbung2.php">
<center><TABLE bgcolor="#FFFFFF" bordercolor="#000008" border="0">
<TR>
  <TD width="70%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">How much are 500 visits:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="besaneu" value="<? echo "$besa"; ?>"></TD>
<TR>
<TR>
  <TD width="70%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">How much are 1.000 visits:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="besbneu" value="<? echo "$besb"; ?>"></TD>
<TR>
<TR>
  <TD width="70%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">How much are 5.000 visits:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="bescneu" value="<? echo "$besc"; ?>"></TD>
<TR>
<TR>
  <TD width="70%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">How much are 10.000 visits:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="besdneu" value="<? echo "$besd"; ?>"></TD>
<TR>
<TR>
  <TD width="70%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">How much are 50.000 visits:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="beseneu" value="<? echo "$bese"; ?>"></TD>
<TR>
<TR>
  <TD width="70%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">How much are 100.000 visits:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="besfneu" value="<? echo "$besf"; ?>"></TD>
<TR>
<TR>
  <TD width="70%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">How much are 10.000 bannerviews:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="baaneu" value="<? echo "$baa"; ?>"></TD>
<TR>
<TR>
  <TD width="70%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">How much are 50.000 bannerviews:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="babneu" value="<? echo "$bab"; ?>"></TD>
<TR>
<TR>
  <TD width="70%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">How much are 100.000 bannerviews:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="bacneu" value="<? echo "$bac"; ?>"></TD>
<TR>

</TABLE><br><center><input type="submit" value="Update"></form></center>
<?
include("../templates/admin-footer.txt");
?>
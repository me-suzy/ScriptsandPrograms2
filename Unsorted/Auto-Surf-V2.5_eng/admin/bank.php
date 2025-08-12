<?
include("header.inc.php");

$result = mysql_query("SELECT name, konummer, banklz, bname FROM `demo_a_bank`");
$myrow = mysql_fetch_row($result);
$name = $myrow[0];
$nummer = $myrow[1];
$zahl = $myrow[2];
$bnamen = $myrow[3];
?>

<?
include("../templates/admin-header.txt");
?>
  <form method="post" action="bank2.php">
<center><TABLE bgcolor="#FFFFFF" bordercolor="#000008" border="0" width="60%">
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Account owner:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="inhaber" value="<? echo "$name"; ?>"></TD>
<TR>
  <TD width="80%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Account no:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="knummer" value="<? echo "$nummer"; ?>"></TD>
</TR>
<TR>
  <TD width="80%" bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Bank no:</TD>
  <TD bgcolor="#E6E6E6"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="blzahl" value="<? echo "$zahl"; ?>"></TD>
</TR>
<TR>
  <TD width="80%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">bank name:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><input type="text" name="neu" value="<? echo "$bnamen"; ?>"></TD>
</TR>
  </TD>
</TR>
</TABLE><br><center><input type="submit" value="Update"></center></form>
<?
include("../templates/admin-footer.txt");
?>
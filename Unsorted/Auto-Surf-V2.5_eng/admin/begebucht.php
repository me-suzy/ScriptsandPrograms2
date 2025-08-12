<?
include("header.inc.php");

?>
<?
include("../templates/admin-header.txt");
?>
<TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="1" width="99%" align="center">
<TR>
  <TD><center><b>ID</b></TD>
  <TD><center><b>Name</b></TD>
  <TD><center><b>E-Mail</b></TD>
  <TD><center><b>URL</b></TD>
  <TD><center><b>Amount</b></TD>
  <TD><center><b>Invoice no</b></TD>
</TR>

<? $result = mysql_query("SELECT id, name, email, url, points, rechnung FROM `demo_a_bebuchen`");
   while ($myrow = mysql_fetch_row($result)) {
 echo"
   <TR>
  <TD><center><b> $myrow[0] </b></TD>
  <TD><center><b> $myrow[1] </b></TD>
  <TD><center><b><A href=mailto:$myrow[2]>Send e-mail</b></A></TD>
  <TD><center><b><a href=./frame.php?url=$myrow[3] target=_blank>Website</b></a></TD>
  <TD><center><b> $myrow[4] </b></TD>
  <TD><center><b> $myrow[5] </b></TD>
</TR><br>";
};
?>
  </TABLE>
  <br><br>

<form method="post" action="besucherfrei2.php"><TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" align="center" width="50%">
<TR>
  <TD width="200" bgcolor="#E6E6E6"><b>Invoice no:</b></TD>
  <TD bgcolor="#E6E6E6"><b><input type="text" name="rechn" size="20" maxlength="50"></b></TD>
</TR>
</TABLE><br><center><input type="submit" value="Validate"></form></center>
<?
include("../templates/admin-footer.txt");
?>
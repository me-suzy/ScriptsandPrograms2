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
  <TD><center><b>e-mail</b></TD>
  <TD><center><b>Amount</b></TD>
  <TD><center><b>URL</b></TD>
  <TD><center><b>Banner</b></TD>
  <TD><center><b>Invoice no</b></TD>
</TR>

<? $result = mysql_query("SELECT id, name, email, views, source, target, rechnung FROM `demo_a_babuchen`");
   while ($myrow = mysql_fetch_row($result)) {
 echo"
   <TR>
  <TD><center><b> $myrow[0] </b></TD>
  <TD><center><b> $myrow[1] </b></TD>
  <TD><center><b><A href=mailto:$myrow[2]>Send e-mail</b></A></TD>
  <TD><center><b> $myrow[3] </b></TD>
  <TD><center><b><a href=$myrow[5] target=_blank>Website</b></a></TD>
  <TD><center><b><a href=$myrow[4] target=_blank</b>Show</TD>
  <TD><center><b> $myrow[6] </b></TD>
</TR><br>";
};
?>
  </TABLE>
  <br><br>

<form method="post" action="bannerfrei2.php"><TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" align="center" width="50%">
<TR>
  <TD width="200" bgcolor="#E6E6E6"><b>Invoice no:</b></TD>
  <TD bgcolor="#E6E6E6"><b><input type="text" name="rechn" size="20" maxlength="50"></b></TD>
</TR>
</TABLE><br><center><input type="submit" value="Validate"></form></center>

<?
include("../templates/admin-footer.txt");
?>
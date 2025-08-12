<?
include("header.inc.php");

$result6 = mysql_query("SELECT regeln  FROM `demo_a_regeln`");
$myrow = mysql_fetch_row($result6);

$rea = $myrow[0];

?>
<?
include("../templates/admin-header.txt");
?>
<form method="post" action="regeln2.php">
<center><TABLE bgcolor="#FFFFFF" bordercolor="#000008" border="0" width="95%">
<TR>
  <TD><textarea name="reaneu" cols="80" rows="25"><? echo "$rea"; ?></textarea></TD>
</TR>
</TABLE><br>
<center><input type="submit" value="Update"></form></center>
<?
include("../templates/admin-footer.txt");
?>
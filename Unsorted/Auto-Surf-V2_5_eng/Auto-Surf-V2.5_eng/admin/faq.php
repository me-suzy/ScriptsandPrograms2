<?
include("header.inc.php");

$result5 = mysql_query("SELECT faq FROM `demo_a_faq`");
$myrow5 = mysql_fetch_row($result5);

$faq = $myrow5[0];

?>
<?
include("../templates/admin-header.txt");
?>
<form method="post" action="faq2.php">
<TABLE bgcolor="#FFFFFF" bordercolor="#000008" border="0" width="95%" align="center">
<TR>
  <TD width="50%"><center><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Edit FAQ:</b><br><textarea name="faneu" type="text" cols="80" rows="25"><? echo "$faq"; ?></textarea></TD>
</TR>
</TABLE><br><br>
<center><input type="submit" value="Update"></form></center>
<?
include("../templates/admin-footer.txt");
?>
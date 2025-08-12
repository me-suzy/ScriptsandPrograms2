<?
include("header.inc.php");

$result5 = mysql_query("SELECT startseite FROM `demo_a_texte`");
$myrow5 = mysql_fetch_row($result5);

$textstart = $myrow5[0];

?>
<?
include("../templates/admin-header.txt");
?>
<form method="post" action="starts2.php">
<TABLE bgcolor="#FFFFFF" bordercolor="#000008" border="0" width="95%" align="center">
<TR>
  <TD width="50%"><center><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Edit start page:</b><br><textarea name="startneu" type="text" cols="80" rows="25"><? echo "$textstart"; ?></textarea></TD>
</TR>
</TABLE><br><br>
<center><input type="submit" value="Update"></form></center></TD>
</TR>
</TABLE>
<?
include("../templates/admin-footer.txt");
?>
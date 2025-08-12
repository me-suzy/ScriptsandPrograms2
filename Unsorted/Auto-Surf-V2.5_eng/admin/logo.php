<?
include("header.inc.php");
require('../prepend.inc.php');

$result = mysql_query("SELECT logoa FROM `demo_a_logo`");
$myrow = mysql_fetch_row($result);
$log = $myrow[0];
?>
<?
include("../templates/admin-header.txt");
?>
 <center><font size="4" color="red"><b><u>Your logo</u></b></font></center><br>
<form method="post" action="./logoadd.php">
  <TABLE bgcolor="#FFFFFF" bordercolor="#000008" border="0" width="95%" align="center">
  <TR><TD algin="center"><center><? echo "$log"; ?></TD></TR><TR><TD>&nbsp;</TD></TR>
<TR>
  <TD align="center"><center><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b></b><br><textarea name="logoneu" type="text" cols="40" rows="5"><? echo "$log"; ?></textarea></TD>
</TR></table><br>
<center><input type="submit" value="Update"></form></center>
</TD>
</TR>
</TABLE>
<?
include("../templates/admin-footer.txt");
?>
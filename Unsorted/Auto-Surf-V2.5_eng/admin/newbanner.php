<?
include("header.inc.php");
require('../prepend.inc.php');

$result = mysql_query("SELECT code FROM `demo_a_seitenbanner`");
$myrow = mysql_fetch_row($result);
$bann = $myrow[0];
?>
<?
include("../templates/admin-header.txt");
?>
 <center><font size="4" color="red"><b><u>Banner on the top of each page</u></b></font></center><br>
<form method="post" action="./badd.php">
  <TABLE bgcolor="#FFFFFF" bordercolor="#000008" border="0" width="95%" align="center">
  <TR><TD algin="center"><center><? echo "$bann"; ?></TD></TR><TR><TD>&nbsp;</TD></TR>
<TR>
  <TD align="center"><center><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>Insert HTML code</b><br><textarea name="bannerneu" type="text" cols="40" rows="5"><? echo "$bann"; ?></textarea></TD>
</TR></table><br>
<center><input type="submit" value="Update"></form></center>
<?
include("../templates/admin-footer.txt");
?>
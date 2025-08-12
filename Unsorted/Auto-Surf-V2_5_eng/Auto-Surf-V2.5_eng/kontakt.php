<?php
require('./prepend.inc.php');
?>

<?
include("./templates/main-header.txt");
?>


<br><font size="3"><form method="post" action="danke1.php">
  <TABLE bgcolor="#E6E6E6" bordercolor="#000000" border="0" align="center" width="80%">
<TR>
  <TD><br><b>&nbsp;&nbsp;&nbsp;Your name:</TD><TD><br><center><input size="20" type="text" name="name"></b></TD>
</TR>
<TR><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>
<TR>
  <TD><b>&nbsp;&nbsp;&nbsp;Your e-mail:</b></TD><TD><center><input size="25" type="text" name="email"></b></TD>
</TR>
<TR><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>
<TR>
  <TD><b><center>Text:</b></TD></TD><TD><textarea name="text" type="text" cols="35" rows="10"></textarea></b></TD>
</TR><TR><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>
<tr>
     <td colspan="2"><center><font face="Arial,helvetica"><input type="submit" name="next"
     value="Submit">&nbsp;&nbsp;<input type="reset" name="next" value="Reset"></font></td>
    </tr>
</TABLE> </form>


<?
include("./templates/main-footer.txt");
?>
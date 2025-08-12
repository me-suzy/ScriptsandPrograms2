<?
include("../templates/admin-header.txt");
?>
 <form method="post" action="newsletter2.php">
  <TABLE bgcolor="#E6E6E6" bordercolor="#000000" border="0" align="center" width="80%">
<TR><TD>&nbsp;</TD><TD>&nbsp;</TD></TR><TR>
  <TD><b><center>Subject</b></TD><TD><center><input size="35" type="text" name="betreff"></b></TD>
</TR>
<TR><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>
<TR>
  <TD><b><center>Text:</b></center></TD>
  <TD><center><textarea name="text" type="text" cols="35" rows="10"></textarea></b></TD>
</TR><TR><TD>&nbsp;</TD><TD>&nbsp;</TD></TR>
<tr>
     <td colspan="2"><center><font face="Arial,helvetica"><input type="submit" name="next"
     value="Submit">&nbsp;&nbsp;<input type="reset" name="next" value="Reset"></font></td>
    </tr>
</TABLE> </form>
<?
include("../templates/admin-footer.txt");
?>
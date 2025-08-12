<?php
require('./prepend.inc.php');
?>

<?
include("./templates/main-header.txt");
?>


<br><font size="3"><center>Lost your username??<br>No problem, we e-mail it to you.</center><form name="form1" method="post" action=sppass.php>
        <table border="0" align="center">
          <tr>
            <td width="150">e-mail:</td>
            <td width="100">
              <input type="text" name="email" maxlength="100">
            </td>
          </tr><TR><TD><br></TD></TR>
          <tr>
            <td colspan="2">
              <div align="center">
                <input type="submit" name="Submit" value="Submit">
              </div>
            </td>
          </tr>
        </table>
      </form>

<?
include("./templates/main-footer.txt");
?>
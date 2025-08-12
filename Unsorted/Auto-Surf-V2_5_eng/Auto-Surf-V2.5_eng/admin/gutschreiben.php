<?
include("../templates/admin-header.txt");
?>
<form name="form1" method="post" action="gutuser.php">
                      <table width="70%" border="0" align="center">
                        <tr>
                          <td width="40%">user's e-mail</td>
                          <td width="77%">
                            <input type="text" name="email" size="20" maxlength="50">
                          </td>
                        </tr>
                        <tr>
                          <td  width="40%">Points</td>
                           <td width="77%">
                            <input type="text" name="punkt" size="20" maxlength="50">
                          </td>
                        </tr>
                        <tr>
                          <td colspan="2">&nbsp;</td>
                        </tr>
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
include("../templates/admin-footer.txt");
?>
<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");

commonheader();
bodybegin();
logobar($logoname,$textlogo);

if (!isset($action)) {$action=0;}

echo "

<br /><br />
<center><a href=\"../\" title=\"Home page\"><b class=visible>Go back to the home page of the service</b></a></center>
<br><br>
<form action=\"log.php\" method=\"post\">
  <table border=0 bgcolor=\"#5959A7\" align=center width=400 cellspacing=1 cellpadding=1>
    <tr>
      <td bgcolor=\"#5959A7\"><font color=\"#FFFFFF\">
          <B>Content Management System</B></font>
      </td>
  </tr>
  <tr>
  <td>
        <table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" WIDTH=\"100%\" align=\"center\">
          <tr bgcolor=\"#ffffff\">
            <td width=25% style=\"color: black\">Username:</TD>
            <td align=center>
              <input type=text name=\"username\" size=\"30\"  />
            </td>
            <td width=25%>
              <br />
            </td>
          </tr>
          <tr bgcolor=\"#ffffff\">
            <td style=\"color: black\">Password:</td>
            <td align=\"center\">
              <input class=button type=\"password\" name=\"password\" size=\"30\" />
            </td>
            <td>
              <br />
            </td>
          </tr>
          <tr bgcolor=\"#ffffff\">
            <td width=25%>
              <br />
            </td>
            <td>
              <div align=\"center\">
                <input type=\"submit\"  name=\"submit\" value=\"Sign in\">
              </div>
            </td>
            <td width=25%>
              <br />
            </td>
          </tr>
        </table>
</td></tr>
</table>
</form>


";
if ($action==1){
echo "<br /><br /><b>&nbsp;&nbsp;&nbsp; Invalid username/password. Try again.</b><br /><br />";
}

if ($action==2){
echo "<br /><br /><b>&nbsp;&nbsp; You need to sign in first</b><br><br>";
}


echo"
<br><br><br><br><br><br><br>
</td>
         </tr>
         </table>
         </body>
         </html>";

?>
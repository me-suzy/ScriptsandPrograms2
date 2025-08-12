<?php
include 'config.php';

$tmp = $_GET['action'];
if($tmp == "signout"){
$cookie_name = "adminauth";
$cookie_value = "";
$cookie_expire = "0";
$cookie_domain = $domain;
setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain . $directory . "admin.php");
}
$connection = @mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$dbs = @mysql_select_db($database, $connection) or
die(mysql_error());

$sql = "SELECT * FROM $admintable WHERE username = '$_POST[username]' AND password = '$_POST[password]'";
$result = @mysql_query($sql,$connection) or die(mysql_error());
$num = @mysql_num_rows($result);

if ($num != 0) {
$cookie_name = "adminauth";
$cookie_value = "adminfook!$_POST[username]";
$cookie_expire = "0";
$cookie_domain = $domain;
setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain . $directory . "admin.php");
}
list($cookie, $tmpname) =
   split("!", $_COOKIE[adminauth], 2);

if($cookie == "adminfook"){
echo "<font face=\"Arial\" size=\"2\">Logged in!<br>Select an account to edit. | <a href=\"admin.php?action=createnew\">Insert new account.</a> | <a href=\"maillist.php\">Mailing List</a> | <a href=\"admin.php?action=signout\">Sign Out</a><br><br></font>";


if($_GET['action'] == "createnew"){
                       if($_POST['usernamenew'] == ""){
                                   echo "<font face=\"Arial\" size=\"2\">Insert a new account into the database</font>";
                                   echo "<br>";
                                   echo "<br>";
                                   echo "<form method=\"POST\" action=\"admin.php?action=createnew\">";
                                   echo "<p><font face=\"Arial\" size=\"2\">Username</font><br>";
                                   echo "<input type=\"text\" name=\"usernamenew\" size=\"20\">";
                                   echo "<br>";
                                   echo "<font face=\"Arial\" size=\"2\">Password: </font>";
                                   echo "<br>";
                                   echo "<input type=\"text\" name=\"passwordnew\" size=\"20\">";
                                   echo "<br>";
                                   echo "<font face=\"Arial\" size=\"2\">Email: </font>";
                                   echo "<br>";
                                   echo "<input type=\"text\" name=\"emailnew\" size=\"20\">";
                                   echo "<br>";
                                   echo "<input type=\"submit\" value=\"Insert\" name=\"B1\"> </p>";
                                   echo "</form>";
                                   } else {
                                          $query3 = "INSERT INTO $userstable (username,password,email,vcode)
                                          VALUES ('$_POST[usernamenew]','$_POST[passwordnew]','$_POST[emailnew]','')";
                                          $result3 = mysql_query($query3,$connection) or die ("Coundn't execute query.");
                                          echo "Account has been added.<br>";
                                          }
}
if($_POST['tmpname'] == "") {
        } else {
                if($_POST[newpassword] == "") {
                        } else {
                                $query4 = "UPDATE $userstable SET password = '$_POST[newpassword]'
                                WHERE username = '$_POST[tmpname]'";
                                $result4 = mysql_query($query4)
                                or die ("Couldn't execute query.");
                                echo "Success, Password Changed.<br>";
                                }

                $query3 = "UPDATE $userstable SET email = '$_POST[newemail]'
                WHERE username = '$_POST[tmpname]'";
                $result3 = mysql_query($query3)
                or die ("Couldn't execute query.");
                echo "Success, Email changed.";
                }



        if($_POST['B2'] == "") {
        } else {
                $sql5 = "DELETE FROM $userstable WHERE username = '$_POST[account]'";
                $result5 = mysql_query($sql5)
                           or die ("Couldn't execute query.");
                           echo "Success, Account has been deleted.";
                           echo "<br>Click <a href=\"admin.php\">here</a> to go back to account management.";
                           die;

               }
if($_POST['account'] == ""){

        } else {
                echo "<form method=\"POST\" action=\"admin.php\">";
                echo "<font face=\"Arial\" size=\"2\"> Username: $_POST[account]";
                echo "<input name=\"tmpname\" type=\"hidden\" value=\"$_POST[account]\">";
                echo "<br>";
                $sql2 = "SELECT email FROM $userstable
                WHERE username = '$_POST[account]'";
                $result2 = mysql_query($sql2)
                or die ("Couldn't execute query.");
                $tmpemail = mysql_result($result2,0);
                echo " Email:";
                echo "<br><input type=\"text\" name=\"newemail\" size=\"20\" value=\"$tmpemail\">";
                echo "<br>";
                echo " New Password (leave blank to keep current password)<br><input type=\"text\" name=\"newpassword\" size=\"20\">";
                echo "<br><br>";
                echo "<input type=\"submit\" value=\"Save Changes\" name=\"B1\"></font>";
                echo "</form>";

                }



$sql =  "SELECT * FROM $userstable ORDER BY username";
$result = mysql_query($sql)
        or die ("Couldn't execute query.");

echo "<form method=\"POST\" action=\"admin.php\">";
echo "<font face=\"Arial\" size=\"2\"><table border=\"1\" cellspacing=\"1\" cellpadding=\"3\" style=\"border-collapse: collapse\" bordercolor=\"#111111\">";
echo "  <tr>";
echo "<font face=\"Arial\" size=\"2\"><td><center><font face=\"Arial\" size=\"2\">Edit</font></center></td>";
echo "    <td><center><font face=\"Arial\" size=\"2\">Username</font></center></td>";
echo "    <td><center><font face=\"Arial\" size=\"2\">Email</center></td></font>";
echo "  </tr>";
echo "  <tr>";
while($row = mysql_fetch_array( $result )) {
echo " <td><input type=\"radio\" value=\"$row[username]\" name=\"account\"></td>";
echo " <td><font face=\"Arial\" size=\"2\">$row[username]</font></td>";
echo " <td><font face=\"Arial\" size=\"2\">$row[email]</font></td>";
echo "</tr>";
}
echo "</table>";
echo "<br><input type=\"submit\" value=\"Edit selected account!\" name=\"B1\">";
echo "<input type=\"submit\" value=\"Delete selected account!\" name=\"B2\"></font>";
echo "</form>";


$result = mysql_query($sql)
        or die ("Couldn't execute query.");


} else {
        echo "<font face=\"Arial\" size=\"2\">Administration Login:";
        echo "<br></font><br>";
        echo "<form method=\"POST\" action=\"admin.php\">";
        echo "<font face=\"Arial\" size=\"2\"> Username: </font>";
        echo "<br>";
        echo "<input type=\"text\" name=\"username\" size=\"20\">";
        echo "<br>";
        echo "<font face=\"Arial\" size=\"2\"> Password </font>";
        echo "<br>";
        echo "<input type=\"password\" name=\"password\" size=\"20\">";
        echo "<br>";
        echo "<input type=\"submit\" value=\"Submit\" name=\"B1\" size=\"20\"></p>";
        echo "</form>";
       }

?>
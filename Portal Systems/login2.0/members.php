<?php
include 'config.php';


list($cookie, $tmpname) =
   split("!", $_COOKIE[auth], 2);

if($cookie == "fook"){

$connection = mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$db = mysql_select_db($database, $connection)
        or die(mysql_error());

        $sqlcheckaccount = "SELECT username FROM $userstable
        WHERE username = '$tmpname'";

        $resultcheckaccount = mysql_query($sqlcheckaccount)
        or die ("Couldn't execute query.");
        $numcheckaccount = mysql_num_rows($resultcheckaccount);
        if($numcheckaccount == 0){
                                  echo "Error, Username doesnt exist.";
                                  die;
                                  }

echo "<font face=\"Arial\" size=\"2\">Welcome <b>$tmpname</b>, to the members section </font>";
echo "<br><a href=\"index.php?action=signout\"><font face=\"Arial\" size=\"2\">Sign Out.</a> | <a href=\"members.php\">View all members.</a> | <a href=\"profile.php\">Edit your profile.</a></font><br><br>";

$sql =  "SELECT * FROM $userstable ORDER BY username";

$result = mysql_query($sql)
        or die ("Couldn't execute query.");

$num_rows = mysql_num_rows($result);
echo "<font face=\"Arial\" size=\"2\">Below is a list of members which have registered at $domain";
echo "<br> Total members: $num_rows";
echo "<br><table border=\"1\" cellspacing=\"1\" cellpadding=\"3\">";
echo "<tr> <th><font face=\"Arial\" size=\"2\">Username</th> <th><font face=\"Arial\" size=\"2\">Email</th> <th><font face=\"Arial\" size=\"2\">Profile</th> </font></tr>";
while($row = mysql_fetch_array( $result )) {

echo "<tr><td><font face=\"Arial\" size=\"2\">";
echo $row['username'];
echo "</td><td><font face=\"Arial\" size=\"2\">";
echo $row['email'];
echo "</td>";
echo "<td><a href=\"viewprofile.php?name=$row[username]\"><font face=\"Arial\" size=\"2\">View Profile</a></td>";
echo "</tr>";
}
echo "</table></font>";



} else {
        echo "Error, You have to be logged in to view this page.";
        echo "<br>Click <a href=\"login.php\">here!</a> to login.";
        }
?>
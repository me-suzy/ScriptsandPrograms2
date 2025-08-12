<?php
include 'header.php';
include 'format.css';

$sql =  "SELECT * FROM $userstable WHERE (userlevel = '2' OR userlevel = '3') ORDER BY username";

$result = mysql_query($sql)
        or die ("Couldn't execute query.");

$num_rows = mysql_num_rows($result);

echo "<tr><td bgcolor=\"#E6F0FF\"><b>Team Members</b></td></tr>";
echo "<tr><td align=\"center\"><font face=\"Arial\" size=\"2\">A listing of Task Driver members at the domain: <b>$domain</b><br><br>";
echo "<br> Total members: $num_rows";
echo "<br><table class=\"black\" border=\"1\" bordercolor=\"#ECECEC\" cellspacing=\"1\" cellpadding=\"3\">";
echo "<tr> <td align=\"center\"><b>Username</b></td> <td align=\"center\"><b>Department</b></td> <td align=\"center\"><b>Profile</b></td></tr>";
while($row = mysql_fetch_array( $result )) {

echo "<tr><td>$row[username]</td>";
echo "<td>$row[department]</td>";
echo "<td align=\"center\"><a href=\"viewprofile.php?name=$row[username]\">View</a></font></td>";
echo "</tr>";
}
echo "</table>";

echo "<br><br><br></td></tr></table>";

echo "</td>";
echo "</tr>";
echo "</table></td></tr></table>";
include 'footer.php';
?>
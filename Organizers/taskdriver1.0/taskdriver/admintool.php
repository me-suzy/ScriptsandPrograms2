<?php
include 'header.php';
include 'format.css';
$access = $_POST['access'];
$name = $_GET['name'];

$sql = "UPDATE $userstable SET userlevel ='$access' WHERE username = '$name'";
$result = mysql_query($sql);

echo "<tr><td bgcolor=\"#E6F0FF\"><b>Modify Authentication</b></td></tr>";

echo "<tr><td align=\"center\"><br><br><br><b>$name's access has been modified successfully!</b><br><form name=\"form1\" method=\"post\" action=\"members.php\">
<input type=\"submit\" value=\"Go to Members Index\"></form></td></tr>";

echo "</table></td></tr></table>";

include 'footer.php';
?>
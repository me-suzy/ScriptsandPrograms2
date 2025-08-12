<?php
$queryasn = "SELECT username FROM $userstable WHERE userlevel ='2' ORDER BY username";
$resultasn = mysql_query($queryasn) or die ("Couldn't execute query - PERSONNEL.PHP"); 
while($rowasn = mysql_fetch_array($resultasn)) {
$username = $rowasn['username'];
echo "<OPTION value=\"$username\">$username</OPTION>";
}
?>
<?php
$connection = mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$db = mysql_select_db($database, $connection) or die(mysql_error());
$query = "SELECT catname FROM $cattable ORDER BY catname";
$result = mysql_query($query) or die ("Couldn't execute query - CATECONFRONT.PHP"); 
while($row = mysql_fetch_array($result)) {
$catname = $row['catname'];
$form .= "<OPTION value=\"$catname\">$catname</OPTION>";
}
?>
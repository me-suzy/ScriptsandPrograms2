<?php
$query = "SELECT DISTINCT catname FROM $taskstable WHERE (statusname != 'Cancelled' AND statusname != 'Complete') ORDER BY catname";
$result = mysql_query($query) or die ("Couldn't execute query."); 
while($row = mysql_fetch_array($result)) {
$catname = $row['catname'];
echo "<OPTION value=\"$catname\">$catname</OPTION>";
}
?>
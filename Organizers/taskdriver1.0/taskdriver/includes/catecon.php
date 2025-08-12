<?php
$querycat = "SELECT catname FROM $cattable ORDER BY catname";
$resultcat = mysql_query($querycat) or die ("Couldn't execute query - CATECON.PHP"); 
while($rowcat = mysql_fetch_array($resultcat)) {
$catname = $rowcat['catname'];
echo "<OPTION value=\"$catname\">$catname</OPTION>";
}
?>
<?php
$queryst = "SELECT * FROM $taskstable WHERE taskid = '$taskid' ORDER BY statusname";
$resultst = mysql_query($queryst) or die ("Couldn't execute query."); 
while($rowst = mysql_fetch_array($resultst)) {
$status = $rowst['statusname'];
$category = $rowst['categoryname'];
echo "<OPTION value=\"$status\">$status</OPTION>";
}
echo "<option value=\"Received\">Received</option>";
echo "<option value=\"Assigned\">Assigned</option>";
echo "<option value=\"In Progress\">In Progress</option>";
echo "<option value=\"On Hold\">On Hold</option>";
echo "<option value=\"Complete\">Complete</option>";
echo "<option value=\"Cancelled\">Cancelled</option>";
?>
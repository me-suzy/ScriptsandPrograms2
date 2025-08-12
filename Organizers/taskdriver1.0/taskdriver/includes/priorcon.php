<?php
$querypri = "SELECT * FROM $taskstable WHERE taskid = '$taskid' ORDER BY priority";
$resultpri = mysql_query($querypri) or die ("Couldn't execute query."); 
while($rowpri = mysql_fetch_array($resultpri)) {
$priority = $rowpri['priority'];
echo "<OPTION value=\"$priority\">$priority</OPTION>";
}
echo "<option value=\"1\">1</option>";
echo "<option value=\"2\">2</option>";
echo "<option value=\"3\">3</option>";
echo "<option value=\"4\">4</option>";
?>
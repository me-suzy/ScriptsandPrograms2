<?php
$queryper = "SELECT * FROM $taskstable WHERE taskid = '$taskid' ORDER BY status";
$resultper = mysql_query($queryper) or die ("Couldn't execute query."); 
while($rowper = mysql_fetch_array($resultper)) {
$status = $rowper['status'];
echo "<OPTION value=\"$status\">$status</OPTION>";
}
echo "<option value=\"5%\">5%</option>";
echo "<option value=\"10%\">10%</option>";
echo "<option value=\"15%\">15%</option>";
echo "<option value=\"20%\">20%</option>";
echo "<option value=\"25%\">25%</option>";
echo "<option value=\"30%\">30%</option>";
echo "<option value=\"35%\">35%</option>";
echo "<option value=\"40%\">40%</option>";
echo "<option value=\"45%\">45%</option>";
echo "<option value=\"50%\">50%</option>";
echo "<option value=\"55%\">55%</option>";
echo "<option value=\"60%\">60%</option>";
echo "<option value=\"65%\">65%</option>";
echo "<option value=\"70%\">70%</option>";
echo "<option value=\"75%\">75%</option>";
echo "<option value=\"80%\">80%</option>";
echo "<option value=\"85%\">85%</option>";
echo "<option value=\"90%\">90%</option>";
echo "<option value=\"95%\">95%</option>";
echo "<option value=\"100%\">100%</option>";
?>
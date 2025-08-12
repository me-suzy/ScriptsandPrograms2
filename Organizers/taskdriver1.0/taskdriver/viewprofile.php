<?php
include 'header.php';
$name = $_GET['name'];
include 'format.css';
$sql = "SELECT username,userlevel FROM $userstable WHERE (userlevel = '2' OR userlevel = '3') AND username = '$name'";
$result = mysql_query($sql) or die ("Couldn't execute query.");
$num = mysql_num_rows($result);
if ($num == 1) {
$tmpsql = "SELECT realname FROM $userstable WHERE username = '$name'";
$tmpresult = mysql_query($tmpsql) or die ("Couldn't execute query.");
$tmprealname = mysql_result($tmpresult,0);
$tmprealname = strip_tags($tmprealname);

$tmpsql4 = "SELECT location FROM $userstable WHERE username = '$name'";
$tmpresult4 = mysql_query($tmpsql4) or die ("Couldn't execute query.");
$tmplocation = mysql_result($tmpresult4,0);
$tmplocation = strip_tags($tmplocation);

$tmpsql6 = "SELECT workphone FROM $userstable WHERE username = '$name'";
$tmpresult6 = mysql_query($tmpsql6) or die ("Couldn't execute query.");
$tmpworkphone = mysql_result($tmpresult6,0);
$tmpworkphone = strip_tags($tmpworkphone);

$tmpsql7 = "SELECT department FROM $userstable WHERE username = '$name'";
$tmpresult7 = mysql_query($tmpsql7) or die ("Couldn't execute query.");
$tmpdepartment = mysql_result($tmpresult7,0);
$tmpdepartment = strip_tags($tmpdepartment);

$tmpsql8 = "SELECT email FROM $userstable WHERE username = '$name'";
$tmpresult8 = mysql_query($tmpsql8) or die ("Couldn't execute query.");
$tmpemail = mysql_result($tmpresult8,0);
$tmpemail = strip_tags($tmpemail);

echo "<tr><td bgcolor=\"#E6F0FF\"><b>Viewing Profiles</b></td></tr>";

echo "<table align=\"center\" class=\"black\" border=\"0\" width=\"20%\" cellspacing=\"1\" bordercolor=\"#ECECEC\">";
echo "<tr><td align=\"center\"> <b>Profile view for $name</b><br><br></td><tr>";
echo "<tr><td> <b>Name:</b> $tmprealname</td></tr>";
echo "<tr><td> <b>Work Phone:</b> $tmpworkphone</td></tr>";
echo "<tr><td> <b>Email Address:</b> $tmpemail</td></tr>";
echo "<tr><td> <b>Location:</b> $tmplocation</td><tr>";
echo "<tr><td> <b>Department:</b> $tmpdepartment</td><tr>";
echo "<tr><td></td></tr>";
echo "<tr><td></td></tr>";
echo "<tr><td><form name=\"form1\" method=\"post\" action=\"admintool.php?name=$name\"></td><tr>";

$sqlac = "SELECT * FROM $userstable WHERE (userlevel = '2' OR userlevel = '3' OR userlevel = 'A') AND username = '$tmpname'";
$resultac = mysql_query($sqlac); 
$rowac= mysql_fetch_array($resultac);
$userlev = $rowac['userlevel'];
$userchk = $rowac['username'];

if ($userchk == $tmpname && $userlev == '3' || $userlev == 'A') {

$sqll = "SELECT userlevel FROM $userstable WHERE username = '$name'";
$resultl = mysql_query($sqll) or die ("Couldn't execute query.");
$rowl = mysql_fetch_array($resultl);
$userlevel = $rowl['userlevel'];
if ($userlevel == '2'){
echo "<tr><td><label><input type=\"radio\" name=\"access\" value=\"3\">Give Manager Access</label></td><tr>";
}
if ($userlevel == '3'){
echo "<tr><td><label><input type=\"radio\" name=\"access\" value=\"2\">Remove Manager Access</label></td><tr>";
}
echo "<tr><td><input type=\"submit\" name=\"Submit\" value=\"Grant Permission\"></form></td><tr>";
}

echo "</table>";


echo "<br><br><br></td></tr></table>";

echo "</td>";
echo "</tr>";
echo "</table></td></tr></table>";				
				
} else {
		
echo "<table align=\"center\" class=\"black\" border=\"0\" width=\"20%\" cellspacing=\"1\" bordercolor=\"#ECECEC\">";
echo "<tr><td align=\"center\"><font color=\"red\"><b>Error:</b></font> Username not found<br>$access<br></td><tr>";
echo "</table>";
        }

echo "<table class=\"black\" width=\"100%\" cellspacing=\"0\" align=\"center\"><tr><td bgcolor=\"#E6F0FF\" align=\"center\"><b>$name's Completed and/or Cancelled Tasks</b></td></tr></table>";



$sqlss = "SELECT * FROM $taskstable WHERE (statusname = 'Complete' OR statusname = 'Cancelled') AND personnel = '$name' ORDER BY last_change DESC";
$resultss = mysql_query($sqlss);
$rowss = mysql_fetch_array($resultss);
if ($rowss[statusname] == 'Complete' || $rowss[statusname] == 'Cancelled') {
echo "<table class=\"tbl\" width=\"100%\" border=\"1\" cellspacing=\"0\" bordercolor=\"#ECECEC\" align=\"center\" >";
echo "<tr><td align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>Category</b></font></td>";
echo "<td width=\"20%\" align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>Task Name</b></font></td>";
echo "<td width=\"30%\" align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>Task Description</b></font></td>";
echo "<td align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>Priority</b></font></td>";
echo "<td align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>Status</b></font></td>";
echo "<td align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>Assignee</b></font></td>";
echo "<td align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>Open Date</b></font></td>";
echo "<td align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>Deadline</b></font></td>";
echo "<td align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>Last Update</b></font></td>";
echo "<td align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>%Complete</b></font></td>";
echo "<td align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>Notes</b></font></td></tr>";


$sql = "SELECT date_format(last_change, '%Y-%m-%d') as last_change,deadline,open_date,taskid,priority,title,description,statusname,catname,status,display,personnel FROM $taskstable WHERE (statusname = 'Complete' OR statusname = 'Cancelled') AND personnel = '$name' ORDER BY last_change DESC";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {

//SQL for checking the notes in the history table
$sqlnot = "SELECT notes FROM $historytable WHERE taskid = '$row[taskid]'";
$resultnot = mysql_query($sqlnot);
$rownot = mysql_fetch_array($resultnot);

echo "<tr><td>$row[catname]</td>";
echo "<td>$row[title]</td>";
echo "<td>$row[description]</td>";
echo "<td align=\"center\">$row[priority]</td>";
echo "<td align=\"center\">$row[statusname]</td>";
echo "<td align=\"center\">$row[personnel]</td>";
echo "<td align=\"center\">$row[open_date]</td>";
echo "<td align=\"center\">$row[deadline]</td>";
echo "<td align=\"center\">$row[last_change]</td>";
echo "<td align=\"center\">$row[status]</td>";
// running the IF from the history table
if ($rownot[notes] == ""){
echo "<td align=\"center\">N/A</tr>";
}else{
echo "<td align=\"center\"><a href=\"notes.php?taskid=$row[taskid]\">View</a></tr>";
}
}
//Closing WHILE and IF statements
echo "</table>";
}else{
echo "<table class=\"tbl\" width=\"100%\" border=\"1\" cellspacing=\"0\" bordercolor=\"#ECECEC\" align=\"center\" >";
echo "<tr><td align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>There are no completed tasks at this time.</b></font></td></tr></table>";
}

include 'footer.php';
?>
<?php
/****************************************************************************\
* TaskDriver                                                               
* Version:1.0                                                              
* Release date: Nov. 05 2005                                          
* Author: Todd Brillon (tbrillon@taskdriver.com)                                      
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        
******************************************************************************
* TaskDriver is free software; you can redistribute it and/or    
* modify it under the terms of the GNU General Public License as published   
* by the Free Software Foundation; either version 2 of the License, or (at  your option) any later version.                                         
*                                                                         
* TaskDriver is distributed in the hope that it will be          
* useful, but WITHOUT ANY WARRANTY; without even the implied warranty of     
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the              
* GNU General Public License for more details.                              
*                                                                           
* You should have received a copy of the GNU General Public License         
* along with this program; if not, write to the Free Software               
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA 
\****************************************************************************/
include 'header.php';
include 'format.css';

$today = date ("Y-m-d", mktime (0,0,0,date("m"),date("d"),date("Y")));

$sqluc = "SELECT COUNT(*) FROM $userstable WHERE userlevel = '2'";
$resultuc = mysql_query($sqluc);
$rowuc = mysql_fetch_assoc($resultuc);

$sqlmc = "SELECT COUNT(*) FROM $userstable WHERE userlevel = '3'";
$resultmc = mysql_query($sqlmc);
$rowmc = mysql_fetch_assoc($resultmc);

$sql = "SELECT COUNT(*) FROM $taskstable WHERE (statusname != 'Complete' AND statusname != 'Cancelled') ";
$result = mysql_query($sql);
$row = mysql_fetch_assoc($result);

$sql1 = "SELECT COUNT(*) FROM $taskstable WHERE statusname = 'Complete'";
$result1 = mysql_query($sql1);
$row1 = mysql_fetch_assoc($result1);

$sql2 = "SELECT COUNT(*) FROM $taskstable WHERE statusname = 'Cancelled'";
$result2 = mysql_query($sql2);
$row2 = mysql_fetch_assoc($result2);

echo "<tr><td colspan=\"2\" bgcolor=\"#E6F0FF\"><b>Task Driver Statisics for $today</b></td></tr>";
if ($row['COUNT(*)'] == '0') {
echo "<tr><td class=\"black\">There are no tasks available for viewing at this time</td></tr>";
}else{
echo "<tr><td>Open Tasks:  " .$row['COUNT(*)']. " </td></tr>";
echo "<tr><td>Completed Tasks:  " .$row1['COUNT(*)']. " </td></tr>";
echo "<tr><td>Cancelled Tasks:  " .$row2['COUNT(*)']. " </td></tr>";
echo "<tr><td>Task Managers:  " .$rowmc['COUNT(*)']. " </td></tr>";
echo "<tr><td>Task Assignees:  " .$rowuc['COUNT(*)']. " </td></tr>";
}
echo "</table>";

echo "</td>";

$queryu = "SELECT * FROM $userstable WHERE (userlevel = '2' OR userlevel = '3' OR userlevel = 'A') AND username = '$tmpname'";
$resultu = mysql_query($queryu); 
$rowu= mysql_fetch_array($resultu);
$userlevel = $rowu['userlevel'];

if($userlevel == '2'){
// DO NOTHING
}else{

//ELSE, SHOW FORM BELOW - Begin manager form
include 'includes/inputcheck.php';
echo "<td>";
echo "<table bgcolor=\"#ECECEC\" cellpadding=\"3\" cellspacing=\"0\" align=\"right\" class=\"black\">";
echo "<tr><form name=\"form1\" method=\"post\" onSubmit=\"return (formCheck(this))\" action=\"complete.php?getid=1\">";
echo "<td align=\"right\"><b>Category:</b></td> ";
echo "<td><select name=\"catname\">";
echo "<option value=\"\"></option>";
include 'includes/catecon.php';
echo "</select></td></tr>";
for($m = 0;$m <= 0; $m++)
{
$now = date ("Y-m-d", mktime (0,0,0,date("m"),date("d")+$m,date("Y")));
echo "<input type=\"hidden\" name=\"open_date\" value=\"" . $now . "\">";
}
echo "<tr><td align=\"right\"><b>Priority:</b></td><td><select name=\"priority\">";
echo "<option value=\"\"></option>";
include 'includes/priorcon.php';
echo "</select> <font size=\"1\">Lower numbers are higher priority</font></td></tr>";

echo "<tr><td align=\"right\"><b>Task Name:</b><td><input type=\"text\" name=\"title\" value=\"\" size=\"25\"><br></td></tr>";
echo "<tr><td align=\"right\"><b>Description:</b><td><textarea name=\"description\" cols=\"25\" rows=\"5\"></textarea><br></td></tr>";
echo "<tr><td align=\"right\"><b>Assignee:</b></td><td><select name=\"personnel\">";
echo "<option value=\"\"></option>";
include 'includes/personnel.php';
echo "</select></td></tr>";

echo "<tr><td align=\"right\"><b>Deadline:</b><td><select name=\"deadline\">";
for($m = 0;$m <= 365; $m++)
{
$to_date = date ("Y-m-d", mktime (0,0,0,date("m"),date("d")+$m,date("Y")));
echo "<option value=\"" . $to_date . "\">" . $to_date . "</option>\n";
}
echo "</select></td></tr>";
echo "<tr><td colspan=\"2\"><input type=\"submit\" value=\"Submit Task\">";
echo "</form></td></tr>";


$sql = "SELECT * FROM $taskstable WHERE taskid = '$taskid' GROUP BY open_date";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
echo "<form name=\"form2\" method=\"post\" onSubmit=\"return (formCheck(this))\" action=\"complete.php?getid=112\"><tr><td bgcolor=\"#ffffff\" align=\"right\" colspan=\"2\"><b>Category Maintenance</b></td></tr>";
echo "<tr><td align=\"right\" bgcolor=\"#F2F2F2\" colspan=\"2\"><input type=\"text\" name=\"catname\" value=\"\" size=\"20\"> <input type=\"submit\" value=\"Add Category\"></td></tr></form>";

echo "<form name=\"form2\" method=\"post\" action=\"complete.php?getid=56\"><tr><td align=\"right\" bgcolor=\"#F2F2F2\" colspan=\"2\">
<select name=\"catnamedel\"><option value=\"\"></option>";
include 'includes/catecon.php';
echo "</select> <input type=\"submit\" value=\"Delete Category\"></td>";
echo "</tr></form>";

echo "</table>";
echo "</td>";
}

echo "</tr>";
$cat = $_POST['catname'];
echo "<tr><td colspan=\"2\" width=\"100%\">";
echo "<table class=\"black\" width=\"100%\" cellspacing=\"0\" align=\"center\" >";
echo "<td align=\"center\"><form name=\"datasort\" method=\"post\" action=\"index.php\"><select name=\"catname\">";
echo "<option value=\"\"></option>";
echo "<option value=\"1\">Display All Categories</option>";
include 'includes/catecon2.php';
echo "</select> <input type=\"submit\" value=\"Sort\"><form></td></tr></table>";
echo "</td></tr>";

echo "<tr><td bgcolor=\"#ECECEC\" colspan=\"2\">";
include 'includes/hover.js';
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
echo "<td align=\"center\" class=\"bgcolorblu\"><font color=\"#ffffff\"><b>Details</b></font></td></tr>";

if ($cat == '1' || $cat == '') {
$sql = "SELECT date_format(last_change, '%Y-%m-%d') as last_change,deadline,open_date,taskid,priority,title,description,statusname,catname,status,display,personnel FROM $taskstable WHERE display = 'Y' AND (statusname != 'Cancelled' AND statusname != 'Complete') ORDER BY deadline ASC";
$result = mysql_query($sql);
}else{
$sql = "SELECT date_format(last_change, '%Y-%m-%d') as last_change,deadline,open_date,taskid,priority,title,description,statusname,catname,status,display,personnel FROM $taskstable WHERE display = 'Y' AND catname = '$cat' AND (statusname != 'Cancelled' AND statusname != 'Complete') ORDER BY deadline ASC";
$result = mysql_query($sql);
}

while($row = mysql_fetch_array($result)) {
echo "<tr><td>$row[catname]</td>";
echo "<td>$row[title]</td>";
echo "<td>$row[description]</td>";
echo "<td align=\"center\">$row[priority]</td>";
echo "<td align=\"center\">$row[statusname]</td>";
echo "<td align=\"center\">$row[personnel]</td>";
echo "<td align=\"center\">$row[open_date]</td>";
if ($today >= $row[deadline]) {
echo "<td bgcolor=\"#FF8181\" align=\"center\"><b>$row[deadline]</b></td>";
}else{
echo "<td align=\"center\">$row[deadline]</td>";
}

echo "<td align=\"center\">$row[last_change]</td>";
echo "<td align=\"center\">$row[status]</td>";
echo "<td align=\"center\"><a href=\"edit.php?taskid=$row[taskid]\">Open</a></tr>";
}
echo "</table>";

echo "</td></tr></table>";
include 'footer.php';
?>
<?php
include 'header.php';
include 'format.css';
$getid = $_GET['getid'];
$taskid = $_GET['taskid'];
$subject = "TaskDriver: New Task Submission";
switch ($getid) {
case 1:
// INITIAL TASK SUBMISSIONS
$query = "INSERT INTO $taskstable (open_date,personnel,catname,deadline,priority,title,description,manager) VALUES ('$_POST[open_date]','$_POST[personnel]','$_POST[catname]','$_POST[deadline]','$_POST[priority]','$_POST[title]','$_POST[description]','$tmpname')";
$result = mysql_query($query)or die("Unable to select database - complete.php");
$rowin2 = mysql_insert_id();
echo "<tr><td bgcolor=\"#E6F0FF\"><b>Task Submitted</b></td></tr>";

echo "<tr><td align=\"center\"><br><br><br><b>Task was submitted successfully!</b><br><form name=\"form1\" method=\"post\" action=\"index.php\">
<input type=\"submit\" value=\"Go to Index\"></form></td></tr>";

echo "</table></td></tr></table>";

$queryu = "SELECT * FROM $userstable WHERE (userlevel = '3' OR userlevel = 'A') AND username = '$tmpname'";
$resultu = mysql_query($queryu); 
$rowu= mysql_fetch_array($resultu);
$userlevel = $rowu['userlevel'];

// EMAIL TO ASSIGNEE
$emailsql = "SELECT email FROM users WHERE username = '$_POST[personnel]'";
$resultsql = mysql_query($emailsql); 
$alert = "<font face=arial><b><i>Email Alert from TaskDriver</i></b><br><br>";
$break = '<br>';
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$footer = "This email was auto generated. PLEASE DO NOT REPLY";
while(list($email) = mysql_fetch_array( $resultsql )) {
mail($email,$subject,$alert . " " . $break .
"<b>A new Task was just submitted to the queue by:  <b>$tmpname</b> " . $break . 
"<br><b>The title of this new Task:  </b> " . $_POST[title] . " " . $break . 
"<br><b>Current Task Status: <font color=\"red\">Received</font>  </b> " . $break . 
"<br><b>Task Manager for this article:  </b> " . $_POST[personnel] . " " . $break . 
"<br><br><br><font size=1>" . $footer,$headers);
}

// EMAIL TO MANAGER
$emailauth = "SELECT email FROM users WHERE username = '$tmpname' AND userlevel = '$userlevel'";
$resultauth = mysql_query($emailauth);
$alert = "<font face=arial><b><i>Email Alert from TaskDriver</i></b><br><br>";
$break = '<br>';
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$footer = "This email was auto generated. PLEASE DO NOT REPLY";
while($rowauth = mysql_fetch_array( $resultauth )) {
mail("" .$rowauth['email'] . "",$subject,$alert . " " . $break .
"<b>A new Task was just submitted to the queue by:  <b>$tmpname</b> " . $break . 
"<br><b>The title of this new Task:  </b> " . $_POST[title] . " " . $break . 
"<br><b>Current Task Status: <font color=\"red\">Received</font>  </b> " . $break . 
"<br><b>Task Manager for this article:  </b> " . $_POST[personnel] . " " . $break . 
"<br><br><br><font size=1>" . $footer,$headers);
}

break;


case 17:
// UPDATED BY MANAGER
$query = "UPDATE $taskstable SET personnel ='$_POST[personnel]',catname ='$_POST[catname]',statusname ='$_POST[statusname]', status ='$_POST[status]', priority ='$_POST[priority]', title ='$_POST[title]', description = '$_POST[description]', deadline = '$_POST[deadline]' WHERE taskid = '$taskid'";
$result = mysql_query($query)or die("Unable to select database - completeedit.php");

echo "<tr><td bgcolor=\"#E6F0FF\"><b>Task Updated</b></td></tr>";

echo "<tr><td align=\"center\"><br><br><br><b>Task was updated successfully!</b><br><form name=\"form1\" method=\"post\" action=\"index.php\">
<input type=\"submit\" value=\"Go to Index\"></form></td></tr>";

echo "</table></td></tr></table>";
break;


case 23:
// UPDATED BY ASSIGNEE
$query = "UPDATE $taskstable SET statusname ='$_POST[statusname]', status ='$_POST[status]' WHERE taskid = '$taskid'";
$result = mysql_query($query)or die("Unable to select database - completeedit_lev2.php");

$query = "INSERT INTO $historytable (notes,taskid) VALUES ('$_POST[notes]','$taskid')";
$result = mysql_query($query)or die("Unable to select database - completeedit_lev2.php - HISTORY");

echo "<tr><td bgcolor=\"#E6F0FF\"><b>Task Updated</b></td></tr>";

echo "<tr><td align=\"center\"><br><br><br><b>Task was updated successfully!</b><br><form name=\"form1\" method=\"post\" action=\"index.php\">
<input type=\"submit\" value=\"Go to Index\"></form></td></tr>";

echo "</table></td></tr></table>";
break;


case 112:
// ADDITION OF NEW CATEGORIES
$query = "INSERT INTO $cattable (catname) VALUES ('$_POST[catname]')";
$result = mysql_query($query)or die("Unable to select database - addcat.php");
$rowin2 = mysql_insert_id();

echo "<tr><td bgcolor=\"#E6F0FF\"><b>Category Creation</b></td></tr>";

echo "<tr><td align=\"center\"><br><br><br><b>Your category was successfully created</b><br><form name=\"form1\" method=\"post\" action=\"index.php\">
<input type=\"submit\" value=\"Go to Index\"></form></td></tr>";

echo "</table></td></tr></table>";
break;


case 56:
// DELETION OF EXSITING CATEGORIES
$query = "DELETE FROM $cattable WHERE catname ='$_POST[catnamedel]'";
$result = mysql_query($query)or die("Unable to select database - addcat.php");
$rowin2 = mysql_insert_id();

echo "<tr><td bgcolor=\"#E6F0FF\"><b>Category Deletion</b></td></tr>";

echo "<tr><td align=\"center\"><br><br><br><b>Your category was successfully removed</b><br><form name=\"form1\" method=\"post\" action=\"index.php\">
<input type=\"submit\" value=\"Go to Index\"></form></td></tr>";

echo "</table></td></tr></table>";
break;


case 86:
// DELETION OF EXSITING TASKS
$query = "DELETE FROM $taskstable WHERE taskid = '$taskid'";
$result = mysql_query($query)or die("Unable to select database - delete.php");

echo "<tr><td bgcolor=\"#E6F0FF\"><b>Deletion of Tasks</b></td></tr>";

echo "<tr><td align=\"center\"><br><br><br><b>Task has been removed successfully!</b><br><form name=\"form1\" method=\"post\" action=\"index.php\">
<input type=\"submit\" value=\"Go to Index\"></form></td></tr>";

echo "</table></td></tr></table>";
break;
}


include 'footer.php';
?>
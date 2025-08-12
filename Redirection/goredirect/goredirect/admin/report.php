<?php

/*********************************************

Go Redirector PHP Configuration Script
Version 0.4
Copyright (c) 2003-2004, StudentPlatinum.com and
the Edvisors Network

Provided under BSD license located at
http://www.studentplatinum.com/scripts/license.php

It is a violation of the license to distribute
this file without the accompanying license and
copyright information.

You may obtain the latest version of this software
at http://www.studentplatinum.com/scripts/

Please visit our corporate page at:
http://www.edvisorsnetwork.com/

*********************************************/

require("../goconfig.php");

/*********************************************
database connection section
*********************************************/
dbinit();

$month=$_POST['month'];
$year=$_POST['year'];
$link=$_POST['linkid'];

// connect to the database
echo "<HTML><HEAD><TITLE>Report for Link ID #$linkid</TITLE></HEAD><BODY><h1>Results for $month-$year for link $linkid</h1><table border = \"1\" cellpadding = \"5\" cellspacing =\"0\" width=\"50%\"><tr><td width=\"50%\">Day</td><td width=\"50%\">Clicks</td>";
for ($i = 1; $i < 32; $i++) {
$date1=$year."-".$month."-".$i." 00:00:00";
$date2=$year."-".$month."-".$i." 11:59:59";
$query="select count(*) from stats where linkid = $link and date > '$date1' and date < '$date2'";
$result=mysql_query($query);
$row=mysql_fetch_array($result);
echo "<tr><td>".$year."-".$month."-".$i."</td><td>".$row[0]."</td></tr>";
}
echo "</table>";
?>
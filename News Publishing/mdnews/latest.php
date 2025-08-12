<?php
/*
--------------------------------------------------------------
|MD News version 1                                           |
|(c)Matthew Dingley 2002                                     |
|For more scripts or assistance go to MD Web at:             |
|www.matthewdingley.co.uk                                    |
|For information on how to install see the readme            |
--------------------------------------------------------------
*/
$configfile = "config.php";
require $configfile;

$result = mysql_query("SELECT * FROM mdnews",$db);

$numRows = mysql_num_rows($result);

echo "Showing the first $shownum of $numRows records<br><br>";

$numRows = $numRows-$shownum;

$resultsmall = mysql_query("SELECT * FROM mdnews ORDER BY id DESC LIMIT 0,$shownum ",$db);

if ($therow = mysql_fetch_array($resultsmall))
{
do
{
echo "<b>";
printf($therow["title"]);
echo "</b><br><i>";
printf($therow["date"]);
echo "</i><br>";
printf($therow["full"]);
echo "<br><br>";
}
while ($therow = mysql_fetch_array($resultsmall));
}
else
{
echo "There were no records<br>";
}
echo "<a href=\"news/index.php\">View Archive</a>";
?>
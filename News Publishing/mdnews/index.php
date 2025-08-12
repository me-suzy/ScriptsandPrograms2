<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>News</title>
</head>

<body>
		   <h2 align="left">
		   News
		   </h2>
		   
		   <p align="left">
		   Here is all the news
		   </p>
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
echo "<p align=\"left\">";
$configfile = "config.php";
require $configfile;

if (!$pageNum)
{
$pageNum=0;
}

$fullresult = mysql_query("SELECT * FROM mdnews ORDER BY date DESC",$db);

$numRows = mysql_num_rows($fullresult);

$highNum = ($pageNum+1)*$entriesToPage;

$lowNum = ($pageNum*$entriesToPage);

$numOfPages = $numRows/$entriesToPage;
$numOfPages = ceil($numOfPages);
$actualPageNum = $pageNum+1;

echo "Showing page $actualPageNum of $numOfPages.<br><br>";

$smallresult = mysql_query("SELECT * FROM `mdnews` ORDER BY `id` DESC LIMIT $lowNum, $entriesToPage",$db);

if ($therow = mysql_fetch_array($smallresult))
{
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
while ($therow = mysql_fetch_array($smallresult));
}

if($numOfPages>1)
{
if($pageNum!=0)
{
$previousPage = $pageNum-1;
echo "&lt;&lt;<a href=\"index.php?pageNum=$previousPage\">Previous</a>&nbsp;";
}
$countPages=1;
do
{
$goToPage=$countPages-1;
if($countPages==$actualPageNum)
{
echo "&nbsp;<b>$countPages</b>&nbsp;";
}
else
{
echo "&nbsp;<a href=\"index.php?pageNum=$goToPage\">$countPages</a>&nbsp;";
}
$countPages++;
}
while($countPages<=$numOfPages);

if($numOfPages-1!=$pageNum)
{
$nextPage = $pageNum+1;
echo "&nbsp;<a href=\"index.php?pageNum=$nextPage\">Next</a>&gt;&gt;";
}
}
}

else
{
echo "There were no records";
}
echo "</p><p align=\"center\">MD News &copy; <a href=\"http://members.lycos.co.uk/matthewdingley/\">Matthew Dingley</a> 2002</p>";
?>
</body>
</html>

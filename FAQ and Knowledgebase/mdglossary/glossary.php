<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Glossary</title>
</head>

<body>

<!-- Put headers above here -->
<?php
/*
-------------------------------------------------------------
|MD Glossary                                                |
|Version 1.0.0                                              |
|This program is Copyright (c) Matthew Dingley 2003         |
|For more help or assistance go to MD Web at:               |
|www.matthewdingley.co.uk                                   |
|For information on how to install or for basic licence     |
|information, view the readme                               |
|                                                           |
|This program is not to be used on a commercial site without|
|a commercial licence. Go to www.matthewdingley.co.uk for   |
|more information. This program is not to be used without   |
|the copyright notice intact                                |
-------------------------------------------------------------
*/
$configfile = "config.php";
require $configfile;
$db = mysql_connect("$host", "$username", "$password");
mysql_select_db("$databasename", $db);
if($word)
{
$getWord=mysql_query("SELECT word,definition FROM mdglossary WHERE word LIKE '%$word%' ORDER BY word", $db);

if($getWordArray=mysql_fetch_array($getWord))
{
echo "Here are the results for $word<br><dl>";
do
{
echo "<br><dt><b>";
printf($getWordArray["word"]);
echo "</b></dt><dd>";
printf($getWordArray["definition"]);
echo "</dd>";
}
while($getWordArray=mysql_fetch_array($getWord));
echo "</dl>";
}
else
{
echo "Sorry there are no matches for $word";
}
}
if($letter)
{
if($letter=="number")
{
$getWord=mysql_query("SELECT word,definition FROM mdglossary WHERE word LIKE '0%' OR word LIKE '1%' OR word LIKE '2%' OR word LIKE '3%' OR word LIKE '4%' OR word LIKE '5%' OR word LIKE '6%' OR word LIKE '7%' OR word LIKE '8%' OR word LIKE '9%' ORDER BY word", $db);
}
else
{
$getWord=mysql_query("SELECT word,definition FROM mdglossary WHERE word LIKE '$letter%' ORDER BY word", $db);
}

if($getWordArray=mysql_fetch_array($getWord))
{
echo "Here are the results for the letter <b>$letter</b><br><dl>";
do
{
echo "<br><dt><b>";
printf($getWordArray["word"]);
echo "</b></dt><dd>";
printf($getWordArray["definition"]);
echo "</dd>";
}
while($getWordArray=mysql_fetch_array($getWord));
echo "</dl>";
}
else
{
echo "Sorry, there are currently no words under <b>$letter</b>";
}
}
if(!$word&&!$letter)
{
echo "Welcome to the glossary!<br>";
}
if($word||$letter)
{
echo "<HR>";
}
echo "You can either type in the word you are looking for in the box below or browse by letter<br><br>";
echo "Search<br>";
echo "<form name=\"getword\" action=\"glossary.php\" method=\"get\"><input name=\"word\" type=\"text\" value=\"\"> <input type=\"submit\" value=\"Search\"></form><br>";
echo "Browser by letter<br>";
echo "<a href=\"glossary.php?letter=number\">#</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=a\">A</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=b\">B</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=c\">C</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=d\">D</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=e\">E</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=f\">F</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=g\">G</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=h\">H</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=i\">I</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=j\">J</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=k\">K</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=l\">L</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=m\">M</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=n\">N</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=o\">O</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=p\">P</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=q\">Q</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=r\">R</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=s\">S</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=t\">T</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=u\">U</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=v\">V</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=w\">W</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=x\">X</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=y\">Y</a>&nbsp;&nbsp;";
echo "<a href=\"glossary.php?letter=z\">Z</a>&nbsp;&nbsp;";
//The following copyright must remain intact otherwise you will be breaching the licence
echo "<br><br>MD Glossary &copy; <a href=\"http://www.matthewdingley.co.uk\" target=\"_blank\">Matthew Dingley</a> 2002";
?>
<!-- Put footers below here -->
</body>
</html>

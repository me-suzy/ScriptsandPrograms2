<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>MD News - Admin</title>
</head>

<body>

<h2 align="left">MD News Administration Area</h2>

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

//Date stuff
$today = getdate();
$month = $today["month"];
$mday = $today["mday"];
$year = $today["year"];
$theDate = "$mday $month $year";

if($action=="add")
{

if($title||$date||$summary||$full)
{
$insertIt=mysql_query("INSERT INTO `mdnews` (`title`, `date`, `summary`, `full`) VALUES ('$title', '$date', '$summary', '$full') ",$db); 
if($insertIt)
{
echo "The newsitem $title has been successfully added<br>";
}
else
{
echo "Sorry there has been an error, please try again<br>";
}
}

else
{
echo "
<form method=\"post\" action=\"$PHP_SELF\">

<b>Title</b>
<br>
<i>The title of the news article</i>
<br>
<input name=\"title\" type=\"text\" value=\"\" size=\"30\">
<br>
<br>
<b>Date</b>
<br>
<i>The date of this article</i>
<br>
<input name=\"date\" type=\"text\" value=\"$theDate\" size=\"30\">
<br>
<br>
<b>Summary</b>
<br>
<i>A summary of this article used to help you in the administration area</i>
<br>
<input name=\"summary\" type=\"text\" value=\"\" size=\"70\">
<br>
<br>
<b>Full Story</b>
<br>
<i>The full text of your story. This can include HTML tags if you want</i>
<br>
<textarea name=\"full\" rows=\"8\" cols=\"60\" >
</textarea>
<br>
<br>
<input name=\"action\" type=\"hidden\" value=\"add\">
<input type=\"submit\" value=\"Add\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"reset\" value=\"Reset\">

</form>
";
}
echo "<br><a href=\"$PHP_SELF\">Main menu</a><br>";
}
//End Add


//Start Edit
if($action=="edit")
{
if($title||$date||$summary||$full)
{
$updateIt=mysql_query("UPDATE mdnews SET title='$title', date='$date', summary='$summary', full='$full' WHERE id='$id'",$db); 
if($updateIt)
{
echo "The newsitem $title was successfully updated<br>";
}
else
{
echo "Sorry there has been an error, please try again<br>";
}
}
else
{
$editresult = mysql_query("SELECT * FROM `mdnews` WHERE id=$id",$db);

if ($therow = mysql_fetch_array($editresult))
{
echo "
<form method=\"post\" action=\"$PHP_SELF\">
<input name=\"id\" type=\"hidden\" value=\"";
printf($therow["id"]);
echo "
\">
<b>Title</b>
<br>
<i>The title of the news article</i>
<br>
<input name=\"title\" type=\"text\" value=\"";
printf($therow["title"]);
echo "
\" size=\"30\">
<br>
<br>
<b>Date</b>
<br>
<i>The date of this article (you can change this if you want but it won't change the position that visitors see it)</i>
<br>
<input name=\"date\" type=\"text\" value=\"";
printf($therow["date"]);
echo "
\" size=\"30\">
<br>
<br>
<b>Summary</b>
<br>
<i>A summary of this article used to help you in the administration area</i>
<br>
<input name=\"summary\" type=\"text\" value=\"";
printf($therow["summary"]);
echo "
\" size=\"70\">
<br>
<br>
<b>Full Story</b>
<br>
<i>The full text of your story. This can include HTML tags if you want</i>
<br>
<textarea name=\"full\" rows=\"8\" cols=\"60\" >";
printf($therow["full"]);
echo "
</textarea>
<br>
<br>
<input name=\"action\" type=\"hidden\" value=\"edit\">
<input type=\"submit\" value=\"Update\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"reset\" value=\"Reset\">

</form>
";
}
else
{
echo "Sorry there has been an error. <a href=\"$PHP_SELF\">Back</a><br>";
}
}
echo "<br><a href=\"$PHP_SELF\">Main menu</a><br>";
}
//End edit

//Start Delete
if($action=="delete")
{
if($check=="yes")
{
$deleteIt=mysql_query("DELETE FROM mdnews WHERE id='$id' ",$db); 
if($deleteIt)
{
echo "The newsitem was successfully deleted<br>";
}
else
{
echo "Sorry there has been an error, please try again<br>";
}
}
else
{
$deleteresult = mysql_query("SELECT * FROM `mdnews` WHERE id=$id",$db);

if ($therow = mysql_fetch_array($deleteresult))
{
echo "Are you sure you want to delete the newsitem, <b>";
printf($therow["title"]);
echo "
</b>?<br><br>";
echo "<a href=\"$PHP_SELF?action=delete&check=yes&id=";
printf($therow["id"]);
echo "
\">Yes</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"$PHP_SELF\">No</a>";
}
else
{
echo "Sorry there has been an error. <a href=\"$PHP_SELF\">Back</a><br>";
}
}
echo "<br><br><a href=\"$PHP_SELF\">Main menu</a><br>";
}


//Start Full
if($action=="full")
{
$editresult = mysql_query("SELECT * FROM `mdnews` WHERE id=$id",$db);

if ($therow = mysql_fetch_array($editresult))
{
echo "<b>Title</b>:<br>";
printf($therow["title"]);
echo "<br><br><b>Date</b>:<br>";
printf($therow["date"]);
echo "<br><br><b>Summary</b>:<br>";
printf($therow["summary"]);
echo "<br><br><b>Full Story</b>:<br>";
printf($therow["full"]);
echo "<br><br>";
echo "<br><a href=\"$PHP_SELF\">Main menu</a><br>";
}
else
{
echo "Sorry there has been an error. <a href=\"$PHP_SELF\">Back</a>";
}
}


//Start Normal

if(!$action&&$action!="add"&&$action!="edit"&&$action!="delete"&&$action!="full"&&!$install)
{
echo "<a href=\"$PHP_SELF?action=add\">Add a news item</a><br><br>";

$result = mysql_query("SELECT * FROM `mdnews` ORDER BY `id` DESC",$db);

if ($therow = mysql_fetch_array($result))
{
//Do top bit of table
echo "
<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\">
	   <tr>
	   	   <td>
		   	   <b>ID</b>
		   </td>
		   <td>
		   	   <b>Title</b>
		   </td>
		   <td>
		   	   <b>Summary</b>
		   </td>
		   <td>
		   	   &nbsp;
		   </td>
		   <td>
		   	   &nbsp;
		   </td>
		   <td>
		   	   &nbsp;
		   </td>
	   </tr>
";
//Do rest of table
do
{
echo "
<tr>
	   	<td>";
printf($therow["id"]);
echo "
		</td>
		<td>";
printf($therow["title"]);
echo "
		</td>
		<td>";
printf($therow["summary"]);
echo "
		</td>
		<td>
		<a href=\"$PHP_SELF?action=full&id=
		";
printf($therow["id"]);
echo "
\">See Full</a>
		</td>
		<td>
		<a href=\"$PHP_SELF?action=edit&id=
		";
printf($therow["id"]);
echo "
\">Edit</a>
		</td>
		<td>
		<a href=\"$PHP_SELF?action=delete&id=
		";
printf($therow["id"]);
echo "
\">Delete</a>
		</td>
</tr>
";
}
while ($therow = mysql_fetch_array($result));

echo "</table>";
}
else
{
echo "<b>Sorry there are no records in the database</b><br>Click <a href=\"$PHP_SELF?action=add\">here</a> to add a news item<br>";
}
}
//End normal

//Start install
if($install=="go")
{
$installIt = mysql_query("
CREATE TABLE mdnews (
  id int(5) NOT NULL auto_increment,
  title varchar(30) default NULL,
  date varchar(20) default NULL,
  summary varchar(255) default NULL,
  full text,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  FULLTEXT KEY title (title,full)
) TYPE=MyISAM;", $db);

if($installIt)
{
echo "The database has been successfully installed. You can now go to the admin area to add a news item<br>
If you have any problems, just go to <a href=\"http://members.lycos.co.uk/matthewdingley/\">MD Web</a>";
}
else
{
echo "Sorry there has been a problem. Please refresh this page. If this is a constant problem check you have not
mistyped any details in the config.php file. If you still cannot sort it out, go to <a href=\"http://members.lycos.co.uk/matthewdingley/\">MD Web</a>
and contact Matthew Dingley";
}
}
//End install



if(!(!$action&&$action!="add"&&$action!="edit"&&$action!="delete"&&$action!="full"&&!$install))
{
echo "
</p>
<p align=\"center\">
Any problems or comments about MD News or if you want more cool programs go to <a href=\"http://members.lycos.co.uk/matthewdingley/\">MD Web</a>
</p>";
}


?>

</body>
</html>

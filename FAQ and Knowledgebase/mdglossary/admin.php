<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>MD Glossary Administration Zone</title>
</head>

<body>

<h2 align="left">MD Glossary Administration Area</h2>

<p align="left">

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

echo "<i>Version 1.0.0</i><br><br>";
$configfile = "config.php";
require $configfile;
$db = mysql_connect("$host", "$username", "$password");
mysql_select_db("$databasename", $db);

//Add word
if($action=="add")
{
if($word&&$definition)
{
$addWord=mysql_query("INSERT INTO `mdglossary` (`word`, `definition`) VALUES ('$word', '$definition')", $db);
if($addWord)
{
echo "The word $word and it's definition has been successfully added to the database";
}
if(!$addWord)
{
echo "There has been an error in adding the word $word";
}
}
if(!$word||!$definition)
{
echo "
<form name=\"mdglossary\" action=\"$PHP_SELF\" method=\"post\">
<b>Word</b>
<br>
<i>The word you want adding</i>
<br>
<input name=\"word\" type=\"text\" value=\"\" maxlength=\"40\">
<br>
<br>
<b>Definition</b>
<br>
<i>The definition of the word</i>
<br>
<textarea name=\"definition\" rows=\"6\" cols=\"50\" ></textarea>
<br>
<input name=\"action\" type=\"hidden\" value=\"add\">
<input type=\"submit\" value=\"Add\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"reset\" value=\"Reset\">
</form>
";
}
}

//Edit word
if($action=="edit")
{
if($word&&$definition)
{
$updateIt=mysql_query("UPDATE mdglossary SET word='$word', definition='$definition' WHERE id='$id'",$db);
if($updateIt)
{
echo "The word $word has been successfully updated";
}
if(!$updateIt)
{
echo "There has been an error in updating the word $word";
}
}
if(!$word&&!$defintion)
{
$getInfo=mysql_query("SELECT * FROM `mdglossary` WHERE id=$id",$db);
if($theInfo=mysql_fetch_array($getInfo))
{
echo "
<form name=\"mdglossary\" action=\"$PHP_SELF\" method=\"post\">
<b>Word</b>
<br>
<i>The word you want editing</i>
<br>
<input name=\"word\" type=\"text\" value=\"";
printf($theInfo["word"]);
echo "\" maxlength=\"40\">
<br>
<br>
<b>Definition</b>
<br>
<i>The definition of the word</i>
<br>
<textarea name=\"definition\" rows=\"6\" cols=\"50\" >";
printf($theInfo["definition"]);
echo "</textarea>
<br>
<input name=\"action\" type=\"hidden\" value=\"edit\">
<input name=\"id\" type=\"hidden\" value=\"$id\">
<input type=\"submit\" value=\"Update\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"reset\" value=\"Reset\">
</form>
";
}
else
{
echo "There has been an error";
}
}
}

//Delete word
if($action=="delete")
{
if($check=="yes")
{
$deleteIt=mysql_query("DELETE FROM mdglossary WHERE id=$id",$db);
if($deleteIt)
{
echo "The word has successfully been deleted";
}
if(!$deleteIt)
{
echo "There has been an error whilst the word was being deleted. Please try again";
}
}
else
{
$getDeleteInfo=mysql_query("SELECT * FROM mdglossary WHERE id='$id'",$db);
$deleteInfo=mysql_fetch_array($getDeleteInfo);
echo "Are you sure you want to delete the following word?<br>";
echo "
<dl>
<dt><b>
";
printf($deleteInfo["word"]);
echo "
</b></dt>
<dd>
";
printf($deleteInfo["definition"]);
echo "
</dd>
</dl>
";
echo "<br><a href=\"$PHP_SELF?action=delete&id=$id&check=yes\">Yes</a>&nbsp;&nbsp;&nbsp;<a href=\"$PHP_SELF?action=main\">No</a>";
}
}

//Get Code
if($action=="getcode")
{
$getInfo=mysql_query("SELECT * FROM `mdglossary` WHERE id=$id",$db);
if($getCode=mysql_fetch_array($getInfo))
{
$lowWord=strtolower($getCode["word"]);
echo "The code that you can copy and paste the word - <b>";
printf($getCode["word"]);
echo "</b> - into your pages is:<br><br>";
echo "<form><textarea cols=\"50\" rows=\"5\">";
echo "&lt;a href=\"$roottoglossary?word=$lowWord\" class=\"glossary\" title=\"Click here to find out what this word means\"&gt;";
printf($getCode["word"]);
echo "&lt;/a&gt;";
echo "</textarea></form>";
echo "Example:<br><br><a href=\"$roottoglossary?word=$lowWord\" class=\"glossary\" title=\"Click here to find out what this word means\">";
printf($getCode["word"]);
echo "</a>";
}
else
{
echo "Sorry, there has been an error";
}
}

//Install
if($action=="install")
{
$createDb=mysql_query("CREATE TABLE mdglossary (
  id int(6) NOT NULL auto_increment,
  word varchar(40) default NULL,
  definition text,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  FULLTEXT KEY word (word,definition)
)
");
if($createDb)
{
echo "MD Glossary has been installed successfully on your server";
}
else
{
echo "There has been a problem in installing MD Glossary";
}
}

//Add wordpack
if($action=="addwordpack")
{
if($packname)
{
echo "Include file:<br>";
if(include ($packname))
{

$insertPack=mysql_query($insertLines);

if($insertPack)
{
echo "the pack has been added successfully<br>";
}
else
{
echo "Sorry there has been an error<br>";
}
}
else
{
echo "Sorry, there has been a problem opening $packname. Check that you have uploaded it correctly and that you have typed in the name correctly.";
echo "
<br>
<form name=\"pack\" action=\"$PHP_SELF\" method=\"get\">
<input name=\"packname\" type=\"text\" value=\"\">
<input name=\"action\" type=\"hidden\" value=\"addwordpack\">
<br>
<input type=\"submit\" value=\"Add\">
</form>
";
}
$rep = "<br>(";
$insertLines = ereg_replace("\(", $rep, $insertLines);
echo $insertLines;
}
else
{
echo "Type in the filename of the wordpack you want to add. (ie <i>internetpack.php</i>)";
echo "
<br>
<form name=\"pack\" action=\"$PHP_SELF\" method=\"get\">
<input name=\"packname\" type=\"text\" value=\"\">
<input name=\"action\" type=\"hidden\" value=\"addwordpack\">
<br>
<input type=\"submit\" value=\"Add\">
</form>
";
}
}

//Help
if($action=="help")
{
echo "For up-to-date help, information and upgrades to MD Glossary, as well as other new programs to use on your website, visit <a href=\"http://www.matthewdingley.co.uk/programs/glossary/\" target=\"_blank\">MD Web</a>";
}

//Main menu
if($action=="main"||!$action)
{
echo "<a href=\"$PHP_SELF?action=add\">Add a word</a>&nbsp;&nbsp;&nbsp;<a href=\"$PHP_SELF?action=help\">Help</a><br><br>";
echo "You can also go to <a href=\"www.matthewdingley.co.uk/programs/news/\">MD Web</a> and download a wordpack. This will have loads of pre-done words for you to use. When you have downloaded the
word pack, click <a href=\"$PHP_SELF?action=addwordpack\">here</a> to install it.";
$getWords=mysql_query("SELECT * FROM mdglossary ORDER BY word");
if($getWordArray=mysql_fetch_array($getWords))
{
echo "<table width=\"90%\" cellspacing=\"8\" cellpadding=\"0\">";
do
{
echo "
	   <tr>
	   	   <td>
		   <dl>
		   <dt><b>
		   ";
printf($getWordArray["word"]);
		   echo "
		   </b></dt>
		   <dd>
		   ";
printf($getWordArray["definition"]);
		   echo "
		   </dd>
		   </dl>
		   </td>
		   
		   <td>
		   <a href=\"$PHP_SELF?action=edit&id=";
printf($getWordArray["id"]);
		   echo "
		   \">Edit</a>
		   </td>
		   
		   <td>
		   <a href=\"$PHP_SELF?action=delete&id=";
printf($getWordArray["id"]);
		   echo "
		   \">Delete</a>
		   </td>
		   <td>
		   <a href=\"$PHP_SELF?action=getcode&id=";
printf($getWordArray["id"]);
		   echo "
		   \">Get&nbsp;code</a>
		   </td>
		</tr>
";
}
while($getWordArray=mysql_fetch_array($getWords));
echo "</table>";
}
else
{
echo "<br><br>There are currently no words in the database. <a href=\"$PHP_SELF?action=add\">Add a word</a>";
}
}
if($action=="add"||$action=="edit"||$action=="delete"||$action=="getcode"||$action=="install"||$action=="addwordpack"||$action=="help")
{
echo "<br><br><a href=\"$PHP_SELF?action=main\">Main menu</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"$PHP_SELF?action=add\">Add a word</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"$PHP_SELF?action=help\">Help</a>";
}
?>
</body>
</html>

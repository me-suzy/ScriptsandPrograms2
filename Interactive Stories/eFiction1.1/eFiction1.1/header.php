<?php

session_start();
Header('Cache-Control: no-cache, must-revalidate, max_age=0, post-check=0, pre-check=0');
header ("Pragma: no-cache"); 
header ("Expires: 0"); 

if(ini_get("magic_quotes_gpc") == ("" || "0"))
{
	while($element = each($_POST))
	{
		if(($element['key'] != "gid") &&  ($element['key'] != "charid") && ($element['key'] != "wid"))
		{
	    	$$element['key'] = addslashes($element['value']);
    	}
	}
}

if($_POST)
{
	while($element = each($_POST))
	{
		if(($element['key'] != "gid") &&  ($element['key'] != "charid") && ($element['key'] != "wid"))
		{
	    	$$element['key'] = strip_tags($element['value'], '<br><b><i><u><center><img><a><hr><p><ul><li><ol>');
    	}
	}
}

include ("config.php");

if ($_SESSION['loggedin'] == "1")
{
	$userpenname = $_SESSION['penname'];
	$useruid = $_SESSION['uid'];
	$skin = $_SESSION['userskin'];
	if(isset($skinnew))
	{
		unset($_SESSION['userskin']);
		$_SESSION['userskin'] = "$skinnew";
	}
}
include ($databasepath."/dbconfig.php");
include ("lib/class.TemplatePower.inc.php");
include ("languser.php");

$home = "<a class=\"menu\" href=\"index.php\">"._HOME."</a>";
$catslink = "<a class=\"menu\" href=\"categories.php\">"._CATEGORIES."</a>";
$recent = "<a class=\"menu\" href=\"search.php?action=recent\">"._RECENTLYADDED."</a>";
$authors = "<a class=\"menu\" href=\"authors.php?action=list\">"._AUTHORS."</a>";
$help = "<a class=\"menu\" href=\"help.php\">"._HELP."</a>";
$search = "<a class=\"menu\" href=\"search.php\">"._SEARCH."</a>";
$titles = "<a class=\"menu\" href=\"titles.php\">"._TITLES."</a>";

if ($_SESSION['loggedin'] == "1")
{
	$login = "<a class=\"menu\" href=\"user.php\">"._YOURACCOUNT."</a>";

	$logout = "<a class=\"menu\" href=\"user.php?action=logout\">"._LOGOUT."</a>";

	if ($_SESSION['adminloggedin'] == "1")
	{
		$adminarea = "<a class=\"menu\" href=\"admin.php\">"._ADMIN."</a>";
	}
}
else
{
	$login = "<a class=\"menu\" href=\"user.php\">"._LOGIN."</a>";

	if ($_SESSION['adminloggedin'] == "1")
	{
		$adminarea = "<a class=\"menu\" href=\"admin.php\">"._ADMIN."</a> ";
	}
}

echo "<html>";
echo "<head>";
echo "<title>";
$sitename = stripslashes($sitename);
$slogan = stripslashes($slogan);
echo "$sitename :: $slogan";
echo "</title>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"skins/$skin/style.css\">";
include ("javascript.js");
echo "</head>";
?>
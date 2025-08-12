<?
/* ************************************************************
Head section of the document
*********************************************************** */

function commonheader()
{
echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>CMS - powered by netious.com</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
<link href='../style.css' rel='stylesheet' />
<style type=\"text/css\">

#formular td{border:solid 1px}
#formular tr{border:solid 1px}

</style>
</head>
<body>
";
}

/* Formation of the centered 800px-width document area */
function bodybegin()
{echo "
<center>
<table width=\"800\" class=\"document\" border=\"1\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"white\">
<tr>
<td valign=\"top\" align=\"left\">
";
}

/* Logobar */
function logobar($logoname,$textlogo)
{echo "
<div id=\"head\">
<table width=\"100%\">
<tr>
<td align=\"left\" valign=\"middle\">
<br />
&nbsp; &nbsp;";
if ($logoname!="")
echo "
<img src=\"../images/$logoname\" border=\"0\" alt=\"Content Management System\" />
";
else echo "$textlogo";
echo "
<br /><br />
</td>
<td valign=\"middle\" align=\"right\">
Control Panel &nbsp;
</td>
</tr>
</table>
</div>
</td>
</tr>
<tr>
<td class=\"indocument\" height=\"300px\" valign=\"top\" align=\"left\">
";
}

function mainmenu($f)
{
	echo "
	<div id=\"mainmenu\" align=\"left\">
	<table cellpadding=\"0\" cellspacing=\"0\">
	<tr>
	";

/* Depending on the selected function, $f determines which position in the menu is active */


	if ($f=="structure") {$item="<a class=\"active\" href=\"admin.php?f=structure\" title=\"Structure Management\">Structure Management</a>";}
	else {$item="<a href=\"admin.php?f=structure\" title=\"Structure Management\">Structure Management</a>";}

echo "
<td valign=\"middle\">
	&nbsp;$item &nbsp;
	</td>
	";


	if ($f=="file") {$item="<a class=\"active\" href=\"filemgr.php\" title=\"File Management\">File Management</a>";}
	else {$item="<a href=\"filemgr.php\" title=\"File Management\">File Management</a>";}

echo "
<td class=\"menu\" valign=\"middle\">
	&nbsp; $item &nbsp;
	</td>
	";

	if ($f=="content")  {$item="<a class=\"active\" href=\"content.php\" title=\"Content Management\">Content Management</a>";}
	else {$item="<a href=\"content.php\" title=\"Content Management\">Content Management</a>";}

echo "
<td class=\"menu\" valign=\"middle\">
	&nbsp; $item &nbsp;
	</td>
	";

	if ($f=="style")  {$item="<a class=\"active\" href=\"admin.php?f=style\" title=\"Style Management\">Style Management</a>";}
	else {$item="<a href=\"admin.php?f=style\" title=\"Style Management\">Style Management</a>";}

echo "
<td class=\"menu\" valign=\"middle\">
	&nbsp; $item &nbsp;
	</td>
	";

if ($f=="profile")  {$item="<a class=\"active\" href=\"profile.php\" title=\"Admin Profile\">Admin Profile</a>";}
	else {$item="<a href=\"profile.php\" title=\"Admin Profile\">Admin Profile</a>";}

echo "
<td class=\"menu\" valign=\"middle\">
	&nbsp; $item &nbsp;
	</td>
	";

if ($f=="rss")  {$item="<a class=\"active\" href=\"admin.php?f=rss\" title=\"RSS\">RSS</a>";}
	else {$item="<a href=\"admin.php?f=rss\" title=\"RSS\">RSS</a>";}


echo "
	<td class=\"menu\" valign=\"middle\">
	&nbsp; $item &nbsp;
	</td>";

echo "
	</tr>
	</table>";


echo "
	<table cellpadding=\"0\" cellspacing=\"0\">
	<tr>";


if ($f=="news")  {$item="<a class=\"active\" href=\"admin.php?f=news\" title=\"News Management\">News Management</a>";}
	else {$item="<a href=\"admin.php?f=news\" title=\"News Management\">News Management</a>";}


echo "
	<td class=\"menu\" valign=\"middle\">
	&nbsp; $item &nbsp;
	</td>";

echo "
	<td class=\"menu\" valign=\"middle\">
	&nbsp;<a href=\"signout.php\" title=\"Sign out!\">Sign out!</a> &nbsp;
	</td>
	</table>
	</div>
	";
	
}

/* *************************************************************
This is a sub-menu. Pops up in case the active section has any 
subsections.
************************************************************** */

function submenu($f,$sf)
{if ($f=="structure") 
	{echo "<div id=\"sidemenu\"><table class=\"sidemenu\" width=\"95%\" border=\"1\">";

	if ($sf=="add") $class="class=\"active\""; else $class="";
	echo "<tr><td $class width=\"100%\"><a href=\"addtomenu.php\" title=\"Add a section\">Add a new section</a></td></tr>";

	if ($sf=="edit") $class="class=\"active\""; else $class="";
	echo "<tr><td $class><a href=\"editmenu.php\" title=\"Edit a section\">Edit an existing section</a></td></tr>";

	if ($sf=="del") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"delfrommenu.php\" title=\"Delete a section\">Delete a section</a></td></tr>";

	if ($sf=="contact") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"contactform.php\" title=\"Activate/disactivate The Contact form\">Activate/disactivate The Contact form</a></td></tr>";

	echo "</table></div>";
	}
if ($f=="style") 
	{echo "<div id=\"sidemenu\"><table class=\"sidemenu\" width=\"95%\" border=\"1\">";

	if ($sf=="wbg") $class="class=\"active\""; else $class="";
	echo "<tr><td $class width=\"100%\"><a href=\"wbg.php\" title=\"General Properties\">General Properties</a></td></tr>";

	if ($sf=="font") $class="class=\"active\""; else $class="";
	echo "<tr><td $class><a href=\"font.php\" title=\"Dominant font\">Dominant Font</a></td></tr>";

	if ($sf=="head") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"head.php\" title=\"Edit header\">Header Style</a></td></tr>";

	if ($sf=="document") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"document.php\" title=\"Document Style\">Document Style</a></td></tr>";

	if ($sf=="mmenu") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"mmenu.php\" title=\"Main Menu\">Main Menu Style</a></td></tr>";

	if ($sf=="smenu") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"smenu.php\" title=\"Submenu Style\">Submenu Style</a></td></tr>";

	if ($sf=="link") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"link.php\" title=\"Link Style\">Link Style</a></td></tr>";


	echo "</table></div>";
	}
if ($f=="rss") 
	{echo "<div id=\"sidemenu\"><table class=\"sidemenu\" width=\"95%\" border=\"1\">";

	if ($sf=="addchnl") $class="class=\"active\""; else $class="";
	echo "<tr><td $class width=\"100%\"><a href=\"addchannel.php\" title=\"Create new channel\">Create new channel</a></td></tr>";

	if ($sf=="editchnl") $class="class=\"active\""; else $class="";
	echo "<tr><td $class><a href=\"editchannel.php\" title=\"Edit an existing channel\">Edit an existing channel</a></td></tr>";

	if ($sf=="delchnl") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"delchannel.php\" title=\"Delete a channel\">Delete a channel</a></td></tr>";

	if ($sf=="additem") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"additem.php\" title=\"Add an item\">Add an RSS item</a></td></tr>";

	if ($sf=="edititem") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"edititem.php\" title=\"Edit an item\">Edit an item</a></td></tr>";

	if ($sf=="delitem") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"delitem.php\" title=\"Delete an item\">Delete an item</a></td></tr>";


	echo "</table></div>";
	}



if ($f=="news") 
	{echo "<div id=\"sidemenu\"><table class=\"sidemenu\" width=\"95%\" border=\"1\">";

	if ($sf=="addnews") $class="class=\"active\""; else $class="";
	echo "<tr><td $class width=\"100%\"><a href=\"addnews.php\" title=\"Add news\">Add news</a></td></tr>";

	if ($sf=="editnews") $class="class=\"active\""; else $class="";
	echo "<tr><td $class><a href=\"editnewsSelect.php\" title=\"Edit news\">Edit news</a></td></tr>";

	if ($sf=="delnews") $class="class=\"active\""; else $class=""; 
echo "<tr><td $class><a href=\"delnews.php\" title=\"Delete news\">Delete news</a></td></tr>";

	echo "</table></div>";
	}
}





function bodyend()
{echo "
</td>
</tr>
<tr>
<td align=\"left\" bgcolor=\"#eeeeee\">
&nbsp;&nbsp;<b>CMS by <a href=\"http://www.netious.com\" target=\"blank\">netious.com</a></b>
</td>
</tr>
</table>
</center>
";
}


function commonfooter()
{
echo "
</body>
</html>
";
}

/* ********************************************************
The following function transforms the IP number of the 
visitor to a decimal number 
********************************************************* */


function f_ip2dec($a){
$d = 0.0;
$b = explode(".", $a,4);
for ($i = 0; $i < 4; $i++) {
       $d *= 256.0;
       $d += $b[$i];
   };
return $d;
}


?>
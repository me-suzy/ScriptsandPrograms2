<HTML>
<HEAD>
<TITLE>Admin Shop Stop Shopping Center</TITLE>
<HEAD>
<?php
/************************************************************************/
/*                                                                      */
/* Copyright (c) 2001-2002 by CrossWalkCentral                          */
/* http://www.crosswalkcentral.net                                      */
/*                                                                      */
/* CrossWalkCentral                                                     */
/* You Web Hosting Community!                                           */
/*                                                                      */
/* Let us customize this script for you.                                */
/*                                                                      */
/* Please let us know what you think of this script			*/
/* at http://www.crosswalkcentral.net/modules.php?name=Forum            */
/*                                                                      */
/* 									*/
/************************************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
include("header.php");
include("config.php");
require("VarCWC02.php");

if ($nuke_admin==on) {
GraphicAdmin();
}



// Request info
$result = mysql_query(
"SELECT * FROM CWC_shopstop Group by name");
if (!$result) {
echo("<P>Error performing query: " .
mysql_error() . "</P>");
exit();
}


// SYSTEM MENU
Echo ("<B><a href='admin.php?op=shopstopcfg'>Shop Stop System Settings</a></b>");

// MAIN MENU
OpenTable();
Echo ("<h4><center>Shop Stop Shopping Center</center></h4>");
Echo ("<center><a href='admin.php?op=shopstop'>Add Product</a> | <b>Product List</b> | <a href='admin.php?op=shopcat'>Categories</a></center>");
CloseTable();

Echo ("<br>");

OpenTable();
// Display the text
while ( $row = mysql_fetch_array($result) ) {
$sid=$row["pid"];
$sname=$row["name"];
$sdes=$row["des"];
$sprice=$row["price"];
$scatid=$row["catid"];

Echo ("<b>$sname</B><br>");
Echo ("$sdes<br>");
Echo ("<a href='admin.php?op=editprod&id=$sid'>Modify</a> | ");
Echo ("<a href='admin.php?op=editprod&id=$sid&delete=true'>Delete</a><br>");

echo ("<BR>");
}

CloseTable();
include("footer.php");
?>
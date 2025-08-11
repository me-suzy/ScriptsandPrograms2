<?php
########################################################################
# Edit-Point 3.00 Beta - Simple Content Management System
# Copyright (C) 2005 Todd Strattman strat@operamail.com
#
# TinyMCE WYSIWYG Editor
# Copyright © 2005 Moxiecode Systems AB
# http://tinymce.moxiecode.com/
#
# iManager
# Developed, copyrighted (c)2005 by net4visions.com. License: LGPL.
# http://www.j-cons.com/news/index.php?id=0
#
# Upload option was made from a modified version of "Simple Upload" by:
# http://tech.citypost.ca/
#
# This library is free software; you can redistribute it and/or
# modify it under the terms of the GNU Lesser General Public
# License as published by the Free Software Foundation; either
# version 2.1 of the License, or (at your option) any later version.
# 
# This library is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
# Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public
# License along with this library; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
#
########################################################################

// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "index";
}

// password protection.
if ($password_protect == "on") {
	// start password protection code:
	session_start();
	// store hash of password.
	$cmp_pass = md5("$user_password");
	if(!empty($_POST['pass2'])) {
		// store md5'ed password.
		$_SESSION['pass2'] = md5($_POST['pass2']);
	}
	// if they match, it's ok.
	if($_SESSION['pass2']!=$cmp_pass) {
		// otherwise, give login page.
		if ($head == "on") {
			include("header.php");
	}
	echo "$p
	<strong>Enter Password</strong>
	$p2
	<form action=\"index.php\" method=\"post\">
	$p
	<input type=\"password\" name=\"pass2\">
	<input type=\"submit\" value=\"login\">
	$p2
	</form>";
	if ($head == "on") {
		include("footer.php");
	}
	exit();
	}
} else {
	echo "";
}
// end password protection.

function index () {
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "index";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

include("$datadir/textlinks.txt");

if ($head == "on") {
	include("footer.php");
}
}

function index2($name){
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "index";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

$txt = 'txt'; // extension for data files.
$nametxt = $name.'.'.$txt; // add extension to $name for data files.
// open file fo editing
echo "<form action=\"index.php\" method=\"post\">
$p
<b>Editing Point : $name</b>
$p2
$p
<input type=\"hidden\" name=\"name\" value=\"$name\" />
<textarea mce_editable=\"true\" name=\"comments\" cols=\"$edit_width\" rows=\"$edit_height\">";
include("$datadir/$nametxt");
echo "</textarea>
<br />
<input type=\"hidden\" name=\"cmd\" value=\"index3\" />
<input name=\"submit\" type=\"submit\" value=\"Add/Edit\" /> <input type=\"button\" onClick=\"javascript:location='index.php';\" value=\"Cancel\">
$p2</form>";

// include footer if "on" in config.php.
if ($head == "on") {
include("footer.php");
}
}

function index3($name, $comments) {
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "index";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

$txt = 'txt'; // extension for data files.
$nametxt = $name.'.'.$txt; // add extension to $name for data files.
$comments = stripslashes($comments); //stripslashes.

// open file and write changes
$open = fopen("$datadir/$nametxt", 'wb');
fwrite($open, $comments);
fclose($open);

echo "<script type=\"text/javascript\">
<!--

var URL   = \"index.php\"
var speed = $edit_redirect

function reload() {
location = URL
}

setTimeout(\"reload()\", speed);

//-->
</script>";

echo "<p>Edit-Point <b>$name</b> Successfully Edited!!!</p>
<p>Automatically redirecting to the <a href=\"index.php\">Edit-Page</a></p>";

// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

switch($_REQUEST['cmd']){
	default:
	index();
	break; 

case "index2";
	index2($_POST['name']);
	break;

case "index3";
	index3($_POST['name'], $_POST['comments']);
	break;
}

?>
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
	$page_name = "admin";
}

// password protection.
if ($password_protect == "on") {
	// start password protection code:
	session_start();
	// store hash of password.
	$cmp_pass = md5("$admin_password");
	if(!empty($_POST['pass1'])) {
		// store md5'ed password.
		$_SESSION['pass1'] = md5($_POST['pass1']);
	}
	// if they match, it's ok.
	if($_SESSION['pass1']!=$cmp_pass) {
		// otherwise, give login page.
		if ($head == "on") {
			include("header.php");
	}
	echo "$p
	<strong>Enter Password</strong>
	$p2
	<form action=\"admin.php\" method=\"post\">
	$p
	<input type=\"password\" name=\"pass1\">
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

function  admin(){ 
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "admin";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

// Path from "text" directory (back) to the webpage directory.
$dirpath = opendir("$pagepath");

// "Choose a name for the new Edit-Point" form-field.
echo "<form action=\"admin.php\" method=\"post\">
$p
Choose a name for the new Edit-Point.
<br />
<input type=\"text\" name=\"name\" value=\"$samplename\" />
$p2
$p
Select a webpage to add the Edit-Point.
<br />
";

// "Select a webpage to add the Edit-Point", form-field and list directory contents function.
echo "<select name=\"file\">";
while(false !== ($filename = readdir($dirpath)))
{
if ($filename == $ignore[0] || $filename == $ignore[1] || $filename == $ignore[2] || $filename == $ignore[3] || $filename == $ignore[4] || $filename == $ignore[5] || $filename == $ignore[6] || $filename == $ignore[7] || $filename == $ignore[8] || $filename == $ignore[9]) 
continue;

$dirArray[] = $filename;
}

closedir($dirpath);
$indexCount= count($dirArray);
sort($dirArray);
for($index=0; $index< $indexCount; $index++)
{
echo "<option value=\"$dirArray[$index]\">$dirArray[$index]</option>
";
}

// End of "Select a webpage..." form-field.
echo "</select>
$p2
$p
<textarea name=\"comments\" cols=\"60\" rows=\"17\">$sampletext</textarea>
<br />
<input type=\"hidden\" name=\"cmd\" value=\"admin2\">
<input name=\"submit\" type=\"submit\" value=\"Create Edit-Point\" />
$p2
</form>
<hr />
<form action=\"admin.php\" method=\"post\">
$p
<input type=\"hidden\" name=\"name\" value=\"textlinks\" />
<input type=\"hidden\" name=\"cmd\" value=\"admin4\" />
<input name=\"submit\" type=\"submit\" value=\"Edit\" /> : Edit the Editor-Page (manually add or delete Edit-Points).
$p2
</form>";

// include Edit-Point links on admin page, if "yes" in config.php.
if ($adminlink == "on") {
echo "<hr />
$p
Choose an Edit-Point to modify:
$p2";
include("$datadir/textlinks.txt");
}

// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

function admin2($name, $comments, $file){
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "admin";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

// option to add one link to multiple places.
if ($multi == "off") {
	$multi2 = "admin3";
} else {
	$multi2 = "admin6";
}

$txt = 'txt'; // extension for data files.
$nametxt = $name.'.'.$txt; // add .txt extension to $name for data files.
$point = '$point'; // html output fix for printing Edit-Point link added to webpage.
$comments = stripslashes($comments);// stripslashes from text.

// open webpage for "Edit-Point include" insertion.
$data = file_get_contents("$pagepath/$file") or die("Could not open file!");

// open/create data file, write to it and close.
$open = fopen("$datadir/$nametxt", 'wb');
fwrite($open, $comments);
fclose($open);

// html for Edit-Point links on Editor-Page (index.php).
$addlink = "<form action=\"index.php\" method=\"post\">
$p
<input type=\"hidden\" name=\"name\" value=\"$name\" />
<input type=\"hidden\" name=\"cmd\" value=\"index2\" />
<input name=\"submit\" type=\"submit\" value=\"Edit\" /> : $name
$p2</form>
";

// add html for Edit-Point links to Editor-Page (index.php).
$openlink = fopen("$datadir/textlinks.txt", 'a+');
fwrite($openlink, $addlink);
fclose($openlink);

// html.
echo "$p
Edit-point <b>$name</b> was successfully created and the Edit-Point editor link has been added to the <a href=\"index.php\">Editor-Page</a>";

if ($adminlink== "on") {
echo " and the <a href=\"admin.php\">Admin-Page</a>.";
}
else {
echo ".";
}

echo "$p2
<form action=\"admin.php\" method=\"post\">
$p
To include Edit-Point <b>$name</b> to webpage <b>$file</b>, copy/paste the code below to where you want it in the webpage and click submit.
<br /><br />";

// Print Edit-Point for webpage insertion.
echo "<b>&lt;?php $point = file_get_contents('$textdir/$datadir/$nametxt'); echo $point; ?&gt;</b><br /><br />";

// Textarea with webpage code for Edit-Point insertion.
echo "<input type=\"hidden\" name=\"name\" value=\"$name\" />
<input type=\"hidden\" name=\"file\" value=\"$file\" />
<textarea name=\"comments\" cols=\"60\" rows=\"17\">";

// Print webpage code.
echo $data;

// end textarea.
echo "</textarea>
<br />
<input type=\"hidden\" name=\"cmd\" value=\"$multi2\" />
<input name=\"submit\" type=\"submit\" value=\"Submit\" /> <input type=\"button\" onClick=\"javascript:location='index.php';\" value=\"Cancel\">
$p2
</form>";


// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
} 

function admin3($name, $comments, $file){
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "admin";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

// stripslashes.
$comments = stripslashes($comments);

// write to webpage.
$open = fopen("$pagepath$file", 'wb');
fwrite($open, $comments);
fclose($open);

// redirect to admin page.
echo "<script type=\"text/javascript\">
<!--
var URL   = \"admin.php\"
var speed = $admin_redirect
function reload() {
location = URL
}
setTimeout(\"reload()\", speed);
//-->
</script>";

echo "$p
Edit-Point <b>$name</b> has been successfully added to <b>$file</b>!!!
$p2
$p
Automatically redirecting to the <a href=\"admin.php\">Admin-Page</a>
$p2";

// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

// Open the Editor-Page for editing.
function admin4(){
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "admin";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}
// Open Editor-Page for editing.
echo "<form action=\"admin.php\" method=\"post\">
$p
<textarea name=\"comments\" cols=\"60\" rows=\"17\">";
include("$datadir/textlinks.txt");
echo "</textarea>
<br />
<input type=\"hidden\" name=\"cmd\" value=\"admin5\" />
<input name=\"submit\" type=\"submit\" value=\"Add/Edit\" />
$p2</form>";

// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
} 

// Save changes to the Editor-Page.
function admin5($comments){
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "admin";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

$comments = stripslashes($comments);
// save editor-page changes.
$open = fopen("$datadir/textlinks.txt", 'wb');
fwrite($open, $comments);
fclose($open);

echo "<script type=\"text/javascript\">
<!--

var URL   = \"admin.php\"
var speed = $admin_redirect

function reload() {
location = URL
}

setTimeout(\"reload()\", speed);

//-->
</script>";

echo "<p>Edit-Page Successfully Edited!!!</p>
<p>Automatically redirecting to the <a href=\"admin.php\">Admin-Page</a></p>";

// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

// add edit-point to multiple places option.
function admin6($name, $comments, $file){
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "admin";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

// stripslashes.
$comments = stripslashes($comments);

// write to webpage.
$open = fopen("$pagepath$file", 'wb');
fwrite($open, $comments);
fclose($open);

echo "$p
Edit-Point <b>$name</b> has been successfully added to <b>$file</b>!!!
$p2
<form action=\"admin.php\" method=\"post\">
$p
Would you like to add Edit-Point <b>$name</b> to another file?
$p2
$p
<input type=\"hidden\" name=\"name\" value=\"$name\" />
<input type=\"hidden\" name=\"cmd\" value=\"admin7\" />
<input name=\"submit\" type=\"submit\" value=\"Yes\" />
$p2
</form>
<form action=\"admin.php\" method=\"post\">
$p
<input type=\"hidden\" name=\"cmd\" value=\"admin\" />
<input name=\"submit\" type=\"submit\" value=\"No\" />
$p2
</form>
";

// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

// add edit-point to multiple places select file function.
function admin7($name) {
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "admin";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

// Path from "text" directory (back) to the webpage directory.
$dirpath = opendir("$pagepath");

// "Choose a name for the new Edit-Point" form-field.
echo "<form action=\"admin.php\" method=\"post\">

$p
Select a webpage to add Edit-Point <b>$name</b>.
<br />
";

// "Select a webpage to add the Edit-Point", form-field and list directory contents function.
echo "<select name=\"file\">";
while(false !== ($filename = readdir($dirpath)))
{
if ($filename == $ignore[0] || $filename == $ignore[1] || $filename == $ignore[2] || $filename == $ignore[3] || $filename == $ignore[4] || $filename == $ignore[5] || $filename == $ignore[6] || $filename == $ignore[7] || $filename == $ignore[8] || $filename == $ignore[9]) 
continue;

$dirArray[] = $filename;
}

closedir($dirpath);
$indexCount= count($dirArray);
sort($dirArray);
for($index=0; $index< $indexCount; $index++)
{
echo "<option value=\"$dirArray[$index]\">$dirArray[$index]</option>
";
}

// End of "Select a webpage..." form-field.
echo "</select>
$p2
$p
<input type=\"hidden\" name=\"name\" value=\"$name\" />
<input type=\"hidden\" name=\"cmd\" value=\"admin8\">
<input name=\"submit\" type=\"submit\" value=\"Continue\" />
$p2
</form>";

// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

function admin8($name, $file) {
// config.php is the main configuration file.
include('config.php');
// name of page for links and title.
if ($su == "on") {
	$page_name = "su";
} else {
	$page_name = "admin";
}
// include header if "on" in config.php.
if ($head == "on") {
	include("header.php");
}

$txt = 'txt'; // extension for data files.
$nametxt = $name.'.'.$txt; // add .txt extension to $name for data files.
$point = '$point'; // html output fix for printing Edit-Point link added to webpage.

// open webpage for Edit-Point insertion.
$data = file_get_contents("$pagepath/$file") or die("Could not open file!");

echo "
$p2
<form action=\"admin.php\" method=\"post\">
$p
To include Edit-Point <b>$name</b> to webpage <b>$file</b>, copy/paste the code below to where you want it in the webpage and click submit.
<br /><br />";

// Print Edit-Point for webpage insertion.
echo "<b>&lt;?php $point = file_get_contents('$textdir/$datadir/$nametxt'); echo nl2br($point); ?&gt;</b><br /><br />";

// Textarea with webpage code for Edit-Point include insertion.
echo "<input type=\"hidden\" name=\"name\" value=\"$name\" />
<input type=\"hidden\" name=\"file\" value=\"$file\" />
<textarea name=\"comments\" cols=\"60\" rows=\"17\">";

// Print webpage code.
echo $data;

// end textarea.
echo "</textarea>
<br />
<input type=\"hidden\" name=\"cmd\" value=\"admin6\">
<input name=\"submit\" type=\"submit\" value=\"Submit\">$p2</form>";

// include footer if "on" in config.php.
if ($head == "on") {
	include("footer.php");
}
}

switch($_REQUEST['cmd']){ 
	default:
	admin();
	break; 

case "admin2";
	admin2($_POST['name'], $_POST['comments'], $_POST['file']);
	break; 

case "admin3";
	admin3($_POST['name'], $_POST['comments'], $_POST['file']);
	break;

case "admin4";
	admin4();
	break;

case "admin5";
	admin5($_POST['comments']);
	break;

case "admin6";
	admin6($_POST['name'], $_POST['comments'], $_POST['file']);
	break;

case "admin7";
	admin7($_POST['name']);
	break;

case "admin8";
	admin8($_POST['name'], $_POST['file']);
	break;

}

?>
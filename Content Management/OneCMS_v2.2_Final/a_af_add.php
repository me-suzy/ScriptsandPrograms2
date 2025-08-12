<?php
include ("config.php");

if ($ipbancheck3 == "0") {if ($numv == "0"){
	if ($warn == $naum) {
	echo "You are banned from the Admin CP...now go away!";
} else {

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}



$from = (($page * $max_results) - $max_results);echo '<SCRIPT LANGUAGE="JavaScript">var checkflag = "false";function check(field) {if (checkflag == "false") {for (i = 0; i < field.length; i++) {field[i].checked = true;}checkflag = "true";return "Uncheck All"; }else {for (i = 0; i < field.length; i++) {field[i].checked = false; }checkflag = "false";return "Check All"; }}</script>';

if ((($userlevel == "3") or ($userlevel == "4") or ($userlevel == "5"))) {
	echo "Sorry $username, but you do not have permission to manage systems. You are only a $level.";
} else {

if ($_GET['type'] == "") {

echo '<a href="a_af_add.php?type=link">Link</a><br><a href="a_af_add.php?type=button">Button</a>';

}

if ($_GET['type'] == "link") {

echo '<form action="a_af_add2.php?a=add" method="POST">
<table cellspacing="0" cellpadding="2" border="0">
<tr><td>Site Name</td><td><input type="text" name="sitename"></td></tr>
<tr><td>Site URL</td><td><input type="text" name="siteurl"></td></tr>
<tr><td>Open link in new window?</td><td><select name="where">
<option value="popup">Yes</option>
<option value="_blank">No</option></select></td></tr>
<input type="hidden" name="type" value="link">
<tr><td><input type="submit" name="submit" value="Proceed"></td></tr></table></form>';
}

if ($_GET['type'] == "button") {

echo '<form action="a_af_add2.php?a=add2" method="POST" enctype="multipart/form-data">
<table cellspacing="0" cellpadding="2" border="0">
<tr><td>Site Name</td><td><input type="text" name="sitename"></td></tr>
<tr><td>Site URL</td><td><input type="text" name="siteurl"></td></tr>
<tr><td>Open link in new window?</td><td><select name="where">
<option value="popup">Yes</option>
<option value="_blank">No</option></select></td></tr>
<input type="hidden" name="type" value="button">
<tr><td>Width of Button</td><td><input type="text" name="width"></td></tr>
<tr><td>Height of Button</td><td><input type="text" name="height"></td></tr>
<tr><td>Upload Button</td><td><input type="file" name="ss"></td></tr>
<tr><td><i>Or...</i> Provide the URL of the button here</td><td><input type="text" name="ss2"></td></tr>
<tr><td><input type="submit" name="submit2" value="Finish"></td></tr></table></form>';
}
}
}
}
}include ("a_footer.inc");
?>
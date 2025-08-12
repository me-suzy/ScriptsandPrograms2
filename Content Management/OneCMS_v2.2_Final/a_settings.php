<?php
include ("config.php");
if ($ipbancheck3 == "0") {if ($numv == "0"){
	if ($warn == $naum) {
	echo "You are banned from the Admin CP...now go away!";
} else {

if ((($userlevel == "3") or ($userlevel == "4") or ($userlevel == "5"))) {
	echo "Sorry $username, but you do not have permission to change configuration settings. You are only a $level.";
} else {

echo '<SCRIPT LANGUAGE="JavaScript">
function tags () {
alert ("You may use the following two tags in the Gallery Header and/or Gallery Footer templates:\n{title} - Name of gallery\n{imagenum} - Number of images in gallery\n{views} - Amount of views for this gallery\n{system} - Shows system name assigned to the gallery\n{icon} - Displays system icon");
}
</SCRIPT>';

if (($_GET['type'] == "templates") && ($_GET['step'] == "")) {
echo "<form action='a_settings.php?type=templates&step=2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Default content list template</td><td><select name='1'><option value='".$template1[0]."' selected>-- ".$template1[0]." --</option>";

$sql = mysql_query("SELECT * FROM onecms_templates");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".$r[name]."'>".$r[name]."</option>";
}

echo "</select></td></tr><tr><td>Default games list template</td><td><select name='2'><option value='".$template1[1]."' selected>-- ".$template1[1]." --</option>";

$sql = mysql_query("SELECT * FROM onecms_templates");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".$r[name]."'>".$r[name]."</option>";
}

echo "</select></td></tr><tr><td>Default companies list template</td><td><select name='3'><option value='".$template1[2]."' selected>-- ".$template1[2]." --</option>";

$sql = mysql_query("SELECT * FROM onecms_templates");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".$r[name]."'>".$r[name]."</option>";
}

echo "</select></td></tr><tr><td>By Default - ABC Order for games list turned on?</td><td><select name='4'><option value='".$template2[0]."' selected>-- ".$template2[0]." --</option><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr><tr><td>By Default - ABC Order for companies list turned on?</td><td><select name='5'><option value='".$template2[1]."' selected>-- ".$template2[1]." --</option><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr>";

echo "<tr><td><input type=\"submit\" name=\"submit\" value=\"Update\"></td></tr></table></form>";
}

if (($_GET['type'] == "templates") && ($_GET['step'] == "2")) {

	$upd1 = "UPDATE onecms_settings SET sitename = '".$_POST["1"]."', siteurl = '".$_POST["2"]."', online = '".$_POST["3"]."', dformat = '".$_POST["4"]."', warn = '".$_POST["5"]."' WHERE id = '6'";

	$upd = mysql_query($upd1);

	if ($upd == TRUE) {

		echo "Template Settings updated. <a href='a_settings.php?type=templates'>Template Settings Home</a>";
	}

}

if (($_GET['type'] == "chat") && ($_GET['step'] == "")) {
echo "<form action='a_settings.php?type=chat&step=2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Amount of messages in chat room shown</td><td><input type='text' name='chat' value='".$chat."'></td></tr></table><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";
for($i = 1; $i <= 6; $i++){
echo "<tr><td>Level ".$i." access to chat?</td><td><select name='chat".$i."'><option value='".$chat2[$i]."' selected>-- ".$chat2[$i]." --</option><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr>";
}
echo "<tr><td><input type=\"submit\" name=\"submit\" value=\"Update\"></td></tr></table></form>";
}

if (($_GET['type'] == "chat") && ($_GET['step'] == "2")) {

	$upd1 = "UPDATE onecms_settings SET sitename = '".$_POST["chat"]."', siteurl = '".$_POST["chat1"]."', online = '".$_POST["chat2"]."', dformat = '".$_POST["chat3"]."', warn = '".$_POST["chat4"]."', images = '".$_POST["chat5"]."', path = '".$_POST["chat6"]."' WHERE id = '5'";

	$upd = mysql_query($upd1);

	if ($upd == TRUE) {

		echo "Chat Settings updated. <a href='a_settings.php?type=chat'>Chat Settings Home</a>";
	}

}

if (($_GET['type'] == "gallery") && ($_GET['step'] == "")) {
echo "<center><a href='javascript:tags()'>Template Tags</a></center><br><form action='a_settings.php?type=gallery&step=2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Gallery Template</b></td><td><textarea name='temp1' cols='30' rows='12'>".stripslashes($albtemplate)."</textarea></td></tr><tr><td><b>Gallery Header</b></td><td><textarea name='temp2' cols='30' rows='12'>".stripslashes($albtemplate2)."</textarea></td></tr><tr><td><b>Gallery Footer</b></td><td><textarea name='temp3' cols='30' rows='12'>".stripslashes($albtemplate3)."</textarea></td></tr><tr><td><b># of Images per row</b></td><td><input type='text' name='2' value='".$albrow."'></td></tr><tr><td><b># of Images to display (if page feature is enabled, this will be for how many images to show per page)</b></td><td><input type='text' name='3' value='".$albpage."'></td></tr><tr><td><b>Pagination?</b></td><td><select name='4'><option value='".$albpages."'>-- ".$albpages." --</option><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr><tr><td><b>HTML between each row</b></td><td><textarea name='5' cols='30' rows='12'>".stripslashes($albsep)."</textarea></td></tr><tr><td><input type=\"submit\" name=\"submit\" value=\"Update\"></td></tr></table></form>";
}

if (($_GET['type'] == "gallery") && ($_GET['step'] == "2")) {

	$upd = mysql_query("UPDATE onecms_settings SET sitename = '".$_POST["temp1"]."', siteurl = '".$_POST["2"]."', online = '".$_POST["3"]."', dformat = '".$_POST["4"]."', warn = '".$_POST["5"]."', images = '".$_POST["temp2"]."', path = '".$_POST["temp3"]."' WHERE id = '4'");

	if ($upd == TRUE) {

		echo "Gallery Settings updated. <a href='a_settings.php?type=gallery'>Gallery Settings Home</a>";
	}

}

if (($_GET['type'] == "global") && ($_GET['step'] == "")) {

echo "<form action='a_settings.php?type=global&step=2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Site Name</td><td><input type=\"text\" name=\"sitename\" value=\"$sitename\"></td></tr><tr><td>Site URL (no trailing slash)</td><td><input type=\"text\" name=\"siteurl\" value=\"$siteurl\"></td></tr>";

echo "<tr><td>Site Online?</td><td><select name='online'><option value=\"$online\" selected>-- $online --</option><option value=\"Yes\">Yes</option><option value=\"No\">No</option></select></td></tr>
<tr><td>Date Format</td><td><input type=\"text\" name=\"dformat\" value=\"$dformat\"></td></tr>
<tr><td>Number of Warns allowed (before banned)</td><td><input type=\"text\" name=\"warn\" value=\"$warn\"></td></tr>
<tr><td>URL to images folder (no trailing slash)</td><td><input type=\"text\" name=\"images\" value=\"$images\"></td></tr>
<tr><td>Path to images folder (no trailing slash)</td><td><input type=\"text\" name=\"path\" value=\"$path\"></td></tr>
<tr><td>Amount of items to display (per page...recommended is 30)</td><td><input type=\"text\" name=\"max_results\" value=\"$max_results\"></td></tr>
<tr><td>Owners E-mail</td><td><input type=\"text\" name=\"email\" value=\"$email\"></td></tr>
<tr><td>Owners Name</td><td><input type=\"text\" name=\"name\" value=\"$name\"></td></tr><tr><td>Thumbnail Width</td><td><input type=\"text\" name=\"width\" value=\"$width\"></td></tr><tr><td>Thumbnail Height</td><td><input type=\"text\" name=\"height\" value=\"$height\"></td></tr><tr><td><input type=\"submit\" name=\"submit\" value=\"Update\"></td></tr></table></form>";
}

if (($_GET['type'] == "global") && ($_GET['step'] == "2")) {

	$upd = mysql_query("UPDATE onecms_settings SET sitename = '".$_POST["sitename"]."', siteurl = '".$_POST["siteurl"]."', online = '".$_POST["online"]."', dformat = '".$_POST["dformat"]."', warn = '".$_POST["warn"]."', images = '".$_POST["images"]."', path = '".$_POST["path"]."', max_results = '".$_POST["max_results"]."', email = '".$_POST["email"]."', name = '".$_POST["name"]."', height = '".$_POST["height"]."', width = '".$_POST["width"]."' WHERE id = '1'");

	if ($upd == TRUE) {

		echo "Global Settings updated. <a href='a_settings.php?type=global'>Global Settings Home</a>";
	}

}

if (($_GET['type'] == "general") && ($_GET['step'] == "")) {
echo "<form action='a_settings.php?type=general&step=2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>IP Logger</td><td>".$ip." <input type='radio' name='ip' value='".$ip."' checked></td>";

if ($ip == "True") {
	echo "<td>False <input type='radio' name='ip' value='False'></td>";
} else {
	echo "<td>True <input type='radio' name='ip' value='True'></td>";
}

echo "<tr><td>WYSIWYG Editor</td><td>".$wysiwyg." <input type='radio' name='wysiwyg' value='".$wysiwyg."' checked></td>";

if ($wysiwyg == "True") {
	echo "<td>False <input type='radio' name='wysiwyg' value='False'></td>";
} else {
	echo "<td>True <input type='radio' name='wysiwyg' value='True'></td>";
}

echo "<tr><td>Amount of PM's allowed</td><td><input type='text' name='pm' value='".$pm."'></td></tr><tr><td>Enable mod_rewrite?</td><td><select name='modrewrite'><option value='".$modrewrite."' selected>-- ".$modrewrite." --</option><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr><tr><td><input type=\"submit\" name=\"submit\" value=\"Update\"></td></tr></table></form>";

}

if (($_GET['type'] == "general") && ($_GET['step'] == "2")) {

	$upd = mysql_query("UPDATE onecms_settings SET sitename = '".$_POST["ip"]."', siteurl = '".$_POST["wysiwyg"]."', online = '".$_POST["pm"]."', warn = '".$_POST["modrewrite"]."' WHERE id = '2'");

	if ($upd == TRUE) {

		echo "General Settings updated. <a href='a_settings.php?type=general'>Update</a>";
	}

}

if (($_GET['type'] == "forum") && ($_GET['step'] == "")) {
echo "<form action='a_settings.php?type=forum&step=2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Allow Visitors to post?</td><td>".$a." <input type='radio' name='a' value='".$a."' checked></td>";

if ($a == "yes") {
	echo "<td>No <input type='radio' name='a' value='no'></td>";
} else {
	echo "<td>Yes <input type='radio' name='a' value='yes'></td>";
}

echo "</tr><tr><td>Allow Visitors to create topics?</td><td>".$b." <input type='radio' name='b' value='".$b."' checked></td>";

if ($b == "yes") {
	echo "<td>No <input type='radio' name='b' value='no'></td>";
} else {
	echo "<td>Yes <input type='radio' name='b' value='yes'></td>";
}

echo "</tr><tr><td>Allow Visitors to use smilies?</td><td>".$c." <input type='radio' name='c' value='".$c."' checked></td>";

if ($c == "yes") {
	echo "<td>No <input type='radio' name='c' value='no'></td>";
} else {
	echo "<td>Yes <input type='radio' name='c' value='yes'></td>";
}

echo "</tr><tr><td>Allow Visitors to use custom name when posting? (name cannot be already in use)</td><td>".$d." <input type='radio' name='d' value='".$d."' checked></td>";

if ($d == "yes") {
	echo "<td>No <input type='radio' name='d' value='no'></td>";
} else {
	echo "<td>Yes <input type='radio' name='d' value='yes'></td>";
}

echo "</tr><tr><td>Topics displayed per page</td><td><input type='text' name='e' value='".$e."'></td></tr><tr><td>Posts displayed per page</td><td><input type='text' name='u' value='".$u."'></td></tr><tr><td>Color link for Admins</td><td><input type='text' name='color1' value='".$color1."'></td></tr><tr><td>Color link for Global Mods</td><td><input type='text' name='color2' value='".$color2."'></td></tr><tr><td>Color link for Mods</td><td><input type='text' name='color3' value='".$color3."'></td></tr><tr><td><input type=\"submit\" name=\"submit\" value=\"Update\"></td></tr></table></form>";

}

if (($_GET['type'] == "forum") && ($_GET['step'] == "2")) {

	$upd = mysql_query("UPDATE onecms_settings SET sitename = '".$_POST["a"]."', siteurl = '".$_POST["b"]."', online = '".$_POST["c"]."', dformat = '".$_POST["d"]."', warn = '".$_POST["e"]."', images = '".$_POST["u"]."', path = '".$_POST["color1"]."', max_results = '".$_POST["color2"]."', email = '".$_POST["color3"]."' WHERE id = '3'") or die (mysql_error()); 

	if ($upd == TRUE) {

		echo "Forum Settings updated. <a href='a_settings.php?type=forum'>Update</a>";
	}

}

}
}
}
}
include ("a_footer.inc");
?>
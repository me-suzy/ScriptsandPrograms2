<?php
$functions = "no";
$la = "a";
$z = "b";
include ("config.php");

if ($_GET['view'] == "") {

$tempaf = mysql_query("SELECT * FROM onecms_templates WHERE name = 'AF Manager'");
while($row2 = mysql_fetch_array($tempaf)) {
	$aftemp = stripslashes($row2[template]);
}

$afm[0] = "/{affiliate}/";
$afm[1] = "/{date}/";

if ($_GET['limit'] == "") {
$afea = mysql_query("SELECT * FROM af_manager WHERE verified = 'yes' ORDER BY `id` DESC");
} else {
$afea = mysql_query("SELECT * FROM af_manager WHERE verified = 'yes' ORDER BY `id` DESC LIMIT ".$_GET['limit']."");
}
while($row = mysql_fetch_array($afea)) {

if ($row[type] == "link") {
$afr[0] = "<a href='af.php?view=click&id=".$row[id]."' target='".$row[where2]."'>".$row[sitename]."</a>";

} else {

$filename = "".$images."/".$row[ss]."";

$page = @file_get_contents("$filename");

if (!$page == NULL) {
$afr[0] = "<a href='af.php?view=click&id=".$row[id]."' target='".$row[where2]."'><img src='".$images."/".$row[ss]."' width='".$row[width]."' height='".$row[height]."'></a>";
} else {
$afr[0] = "<a href='af.php?view=click&id=".$row[id]."' target='".$row[where2]."'><img src='".$row[ss]."' width='".$row[width]."' height='".$row[height]."'></a>";
}
}
$afr[1] = "".date($dformat, $row['date'])."";

echo preg_replace($afm, $afr, $aftemp);
}
} else {

if ($_GET['view'] == "apply") {
headera();
	echo "<form action='af.php?view=apply2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Link</td><td><input type='radio' name='type' value='link'></td></tr><tr><td>Button</td><td><input type='radio' name='type' value='button'></td></tr><tr><td><input type='submit' name='submit' value='Continue'></td></tr></table></form>";
footera();
}

if ($_GET['view'] == "apply3") {
headera();

if (($_POST['sitename'] == "") or ($_POST['siteurl'] == "")) {
echo "Sorry but the <b>Site Name</b> and/or <b>Site URL</b> field have not been entered with data, please go back and fill in both of those fields.";
} else {
	if ($_POST['type'] == "link") {
		$apply = mysql_query("INSERT INTO af_manager VALUES ('null', '".$_POST['sitename']."', '".$_POST['siteurl']."', '".$_POST['where']."', 'link', '', '', '', 'no', '".time()."', '0')") or die(mysql_error());

	} else {
		$apply = mysql_query("INSERT INTO af_manager VALUES ('null', '".$_POST['sitename']."', '".$_POST['siteurl']."', '".$_POST['where']."', 'button', '".$_POST['width']."', '".$_POST['height']."', '".$_POST['ss']."', 'no', '".time()."', '0')") or die(mysql_error());

	}
			if ($apply == TRUE) {
			echo "Thank you, ".$sitename." has received your application and will let you know of there decision on whether to affiliate or not.";
			}
		}
footera();
}

if ($_GET['view'] == "apply2") {
headera();
	echo "<form action='af.php?view=apply3' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

	if ($_POST['type'] == "link") {
		echo '<tr><td>Site Name</td><td><input type="text" name="sitename"></td></tr>
<tr><td>Site URL</td><td><input type="text" name="siteurl"></td></tr>
<tr><td>Open link in new window?</td><td><select name="where">
<option value="popup">Yes</option>
<option value="_blank">No</option></select></td></tr>
<input type="hidden" name="type" value="link">

<tr><td><input type="submit" name="submit" value="Proceed"></td></tr></table></form>';

	} else {

		echo '<tr><td>Site Name</td><td><input type="text" name="sitename"></td></tr>
<tr><td>Site URL</td><td><input type="text" name="siteurl"></td></tr>
<tr><td>Open link in new window?</td><td><select name="where">
<option value="popup">Yes</option>
<option value="_blank">No</option></select></td></tr>
<input type="hidden" name="type" value="button">

<tr><td>Width of Button</td><td><input type="text" name="width"></td></tr>
<tr><td>Height of Button</td><td><input type="text" name="height"></td></tr>
<tr><td><i>Or...</i> Provide the URL of the button here</td><td><input type="text" name="ss"></td></tr>
<tr><td><input type="submit" name="submit2" value="Finish"></td></tr></table></form>';
	}
footera();
}
}

if ($_GET['view'] == "click") {

$sql = mysql_query("SELECT siteurl,clicks FROM af_manager WHERE id = '".$_GET['id']."'");
$fetch = mysql_fetch_row($sql);

if ($fetch[1] == "") {
$clicks = "1";
} else {
$clicks = $fetch[1] + 1;
}

mysql_query("UPDATE af_manager SET clicks = ".$clicks." WHERE id = '".$_GET['id']."'");

header("location: $fetch[0]");
}
?>
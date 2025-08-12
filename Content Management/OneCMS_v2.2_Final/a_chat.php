<?php
$z = "b";
include ("config.php");

if ($_COOKIE[username]) {
if ($_GET['view'] == "chat2") {
echo "<link rel='stylesheet' type='text/css' href='ta3.css'><form action='a_chat.php?view=chat3' method='post'><table cellspacing=\"5\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Message Subject:</td><td><input type='text' name='subject'></td></tr><tr><td>Message:</td><td><textarea name='message' cols='21' rows='5'></textarea></td></tr><tr><td><input type='submit' name='submitmessage' value='Submit Message'></td></tr></table></form>";
}

if ($_GET['view'] == "chatdelete") {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm deletion of chat message?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';
if (is_numeric($_GET['id'])) {
$delete = mysql_query("DELETE FROM onecms_chat WHERE id = '".$_GET['id']."'") or die(mysql_error());

if ($delete == TRUE) {
echo "<link rel='stylesheet' type='text/css' href='ta3.css'>Chat message deleted. <a href='a_chat.php?view=chat'>Go back to the chat</a>";
}
} else {
echo "Invalid ID number, go back";
}
}

if ($chat2[$userlevel] == "Yes") {
if ($_GET['view'] == "chat3") {

	$chatmessage = mysql_query("INSERT INTO onecms_chat VALUES ('null', '".$useridn."', '".$_POST['subject']."', '".addslashes($_POST['message'])."', '".time()."')") or die(mysql_error());

	if ($chatmessage == TRUE) {
	echo "<link rel='stylesheet' type='text/css' href='ta.css'>Message posted. <a href='a_chat.php?view=chat'>View it</a>";
	}
}

if ($_GET['view'] == "chat") {
	
echo "<link rel='stylesheet' type='text/css' href='ta3.css'><table cellspacing=\"5\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Refresh Chat <input type=checkbox onclick=\"window.location='a_chat.php?view=chat'; return true;\"></td><td><a href='a_chat.php?view=chat2'>Post Message</a><br><br></td></tr>";

$sql = mysql_query("SELECT * FROM onecms_chat ORDER BY `date` DESC LIMIT ".$chat."");
while($row = mysql_fetch_array($sql)) {

$sqal = mysql_query("SELECT username FROM onecms_profile WHERE id = '".$row[uid]."'");
$rowr = mysql_fetch_row($sqal);

	echo "<tr><td><b>".$row[subject]."</b> posted by <a href='".$siteurl."/members.php?action=profile&id=".$row[uid]."'>";

	$y2g="SELECT * FROM onecms_boardcp WHERE level = 'admin' AND uid = '".$row[uid]."'";
	$t2g=mysql_query($y2g);
    $c1 = mysql_num_rows($t2g);

	$y2g3="SELECT * FROM onecms_boardcp WHERE level = 'mod' AND uid = '".$row[uid]."'";
	$t2g3=mysql_query($y2g3);
    $c3 = mysql_num_rows($t2g3);

	$y2g2="SELECT * FROM onecms_boardcp WHERE level = 'global' AND uid = '".$row[uid]."'";
	$t2g2=mysql_query($y2g2);
    $c2 = mysql_num_rows($t2g2);
	
	if ($c3 > "0") {
		$color = $color3;
	}

	if ($c2 > "0") {
		$color = $color2;
	}

	if ($c1 > "0") {
		$color = $color1;
	}
	
	if ($color) {
		echo "<font color='".$color."'>".$rowr[0]."</font>";
	} else {
		echo "".$rowr[0]."";
	}

	echo "</a> at <i>".date($dformat, $row[date])."</i>";
	if (($userlevel == "1") or ($userlevel == "2")) {
	echo "&nbsp;&nbsp;<a href='a_chat.php?view=chatdelete&id=".$row[id]."'><b>Delete</b></a>";
	}
	echo "</td></tr><tr><td><p>".comments4(stripslashes($row[message]))."</p></td></tr>";
}
echo "</table>";
}
}
}
?>
<?php
$la = "a";
$z = "b";
include ("config.php");
include ("mods/userreviews.php");

$query = mysql_fetch_row(mysql_query("SELECT template FROM onecms_templates WHERE name = '".$template_name."'"));
$template = stripslashes($query[0]);

$query2 = mysql_fetch_row(mysql_query("SELECT template FROM onecms_templates WHERE name = '".$template_list."'"));
$template2 = stripslashes($query2[0]);

if ($ipbancheck3 == "0") {if ($numv == "0"){

headera();

if ($_GET['limit']) {
	$limit = $_GET['limit'];
} else {
	$limit = "50";
}

if ($_GET['type']) {
	$type = $_GET['type'];
} else {
	$type = "DESC";
}

if ($_GET['by']) {
	$by = $_GET['by'];
} else {
	$by = "id";
}

if (($_GET['id'] == "") &&  ($_GET['submit'] == "add2")) {
if ($_COOKIE['username']) {
$user = $_COOKIE['username'];
} else {
$user = "Visitor";
}

$insert = mysql_query("INSERT INTO onecms_userreviews VALUES ('null', '".addslashes($_POST['name'])."', '".addslashes($_POST['games'])."', '".addslashes($_POST['systems'])."', '".addslashes($_POST['review'])."', '".addslashes($_POST['overall'])."', '0|0', '".$user."', '".time()."')");

$rid = mysql_fetch_row(mysql_query("SELECT id FROM onecms_userreviews WHERE name = '".addslashes($_POST['name'])."' AND review = '".addslashes($_POST['review'])."' AND date = '".time()."'"));

if ($insert == TRUE) {
echo "User Review added.<br><br><a href='userreviews.php?id=".$rid[0]."'><b>View your Review</b></a><br><a href='userreviews.php'><b>View User Reviews</b></a>";
}
}

if ((is_numeric($_GET['id'])) &&  ($_GET['submit'] == "delete2")) {
if (((($userlevel == "1") or ($userlevel == "2") or ($userlevel == "3") or ($userlevel == "4")))) {
$update = mysql_query("UPDATE ".$table3name." SET rate = '0|0' WHERE id = '".$_GET['id']."'");
echo "Rating cleared. <a href='userreviews.php?submit=delete'>Return</a>";
}
}

if ((is_numeric($_GET['id'])) &&  ($_GET['submit'] == "delete")) {
if (((($userlevel == "1") or ($userlevel == "2") or ($userlevel == "3") or ($userlevel == "4")))) {
$delete = mysql_query("DELETE FROM ".$table3name." WHERE id = '".$_GET['id']."'");
echo "User Review deleted. <a href='userreviews.php?submit=delete'>Return</a>";
}
}

if (($_GET['id'] == "") &&  ($_GET['submit'] == "delete")) {
if (((($userlevel == "1") or ($userlevel == "2") or ($userlevel == "3") or ($userlevel == "4")))) {
$sql = mysql_query("SELECT * FROM ".$table3name." ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
while($r = mysql_fetch_array($sql)) {
	if ($r[rate] == "0|0") {
	$rate = "No Ratings";
	} else {
	$rx = explode("|", $r[rate]);
	$rate = $rx[1] / $rx[0];
	$rate = round(substr($rate, 0, 3),2);
	}

echo "<a href='userreviews.php?submit=delete&id=".$r[id]."'>".stripslashes($r[name])."</a> :: ".$rate."/<a href='userreviews.php?submit=delete2&id=".$r[id]."'>Clear Ratings</a><br>";
}
}
}


if (($_GET['id'] == "") &&  ($_GET['submit'] == "add")) {
echo "<form action='userreviews.php?submit=add2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><input type='text' name='name'></td></tr><tr><td><b>Game</b></td><td><select name='games'><option value=''>--------</option>";

	$sqla = mysql_query("SELECT * FROM onecms_games ORDER BY `name` ASC") or die(mysql_error());
	while($row2a = mysql_fetch_array($sqla)) {
			echo "<option value='".$row2a[id]."'>".$row2a[name]."</option>";
	}
	echo "</select></td></tr><tr><td><b>System</b></td><td><select name='systems'><option value='' selected>--------</option>";

	$sqlb = mysql_query("SELECT * FROM onecms_systems ORDER BY `name` ASC") or die(mysql_error());
	while($row2b = mysql_fetch_array($sqlb)) {
			echo "<option value='".$row2b[id]."'>".$row2b[name]."</option>";
	}
	echo "</select></td></tr><tr><td><b>Overall Rating</b></td><td><input type='text' name='overall' size='5'></td></tr><tr><td><b>Review</b></td><td><textarea name='review' cols='40' rows='16'></textarea></td></tr><tr><td><input type='submit' value='Submit Review'></td></tr></table></form>";
}

if (($_GET['id'] == "") &&  ($_GET['submit'] == "")) {
echo abclist("", "userreviews.php");
if ($template == "") {

if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM ".$table3name." WHERE name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM ".$table3name." ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
while($row = mysql_fetch_array($sql)) {
echo "- <a href='userreviews.php?id=".$row[id]."'>".stripslashes($row[name])."</a> :: ".$row[systems]."<br>";
}

} else {

if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM ".$table3name." WHERE name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM ".$table3name." ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
while($r = mysql_fetch_array($sql)) {

    if ($r[user] == "Visitor") {
	$user = "Visitor";
	} else {
	$fetch = mysql_fetch_row(mysql_query("SELECT id FROM onecms_profile WHERE username = '".$r[user]."'"));
    $user = "<a href='elite.php?user=".$fetch[0]."'>".$r[user]."</a>";
	}
    
	if ($r[rate] == "0|0") {
	$rate = "No Ratings";
	} else {
	$rx = explode("|", $r[rate]);
	$rate = $rx[1] / $rx[0];
	$rate = round(substr($rate, 0, 3));
	}

	$game = mysql_fetch_row(mysql_query("SELECT name FROM onecms_games WHERE id = '".stripslashes($r[games])."'"));

	$system = mysql_fetch_row(mysql_query("SELECT name FROM onecms_systems WHERE id = '".stripslashes($r[systems])."'"));

    for ($i = 1; $i <= 5; $i++) {
	$numbers .= "<option value=\"$i\">$i</option>";
    }

	$find[0] = "/{id}/";
	$find[1] = "/{name}/";
	$find[2] = "/{game}/";
	$find[3] = "/{system}/";
	$find[4] = "/{review}/";
	$find[5] = "/{overall}/";
	$find[6] = "/{rate}/";
	$find[7] = "/{form}/";
	$find[8] = "/{username}/";
	$find[9] = "/{date}/";
	$repl[0] = "".$r[id]."";
	$repl[1] = "<a href='userreviews.php?id=".$r[id]."'>".stripslashes($r[name])."</a>";
	$repl[2] = "".$game[0]."";
	$repl[3] = "".$system[0]."";
	$repl[4] = "".stripslashes($r[review])."";
	$repl[5] = "".stripslashes($r[overall])."";
	$repl[6] = $rate;
	$repl[7] = "<form action='userreviews.php?id=".$r[id]."' method='post' name='userreview'><input type='hidden' name='userreview2' value='".$r[id]."'><input type='hidden' name='userreview3' value='".$r[rate]."'><select name='userreview' onchange='this.form.submit()'><option value='' selected>-</option>".$numbers."</select></form>";
	$repl[8] = $user;
	$repl[9] = date($dformat, $r[date]);

echo preg_replace($find, $repl, $template2);
}
}

echo "<br><br><center><a href='userreviews.php?submit=add'>Submit a User Review</a>";
if (((($userlevel == "1") or ($userlevel == "2") or ($userlevel == "3") or ($userlevel == "4")))) {
echo "<br><a href='userreviews.php?submit=delete'>Delete User Reviews</a>";
}
echo "</center>";
}

if (($_GET['id']) && ($_GET['submit'] == "")) {

$sql = mysql_query("SELECT * FROM onecms_userreviews WHERE id = '".$_GET['id']."'");
while($r = mysql_fetch_array($sql)) {

    if ($r[user] == "Visitor") {
	$user = "Visitor";
	} else {
	$fetch = mysql_fetch_row(mysql_query("SELECT id FROM onecms_profile WHERE username = '".$r[user]."'"));
    $user = "<a href='elite.php?user=".$fetch[0]."'>".$r[user]."</a>";
	}
    
	if ($r[rate] == "0|0") {
	$rate = "No Ratings";
	} else {
	$rx = explode("|", $r[rate]);
	$rate = $rx[1] / $rx[0];
	$rate = round(substr($rate, 0, 3),2);
	}

	$game = mysql_fetch_row(mysql_query("SELECT name FROM onecms_games WHERE id = '".stripslashes($r[games])."'"));

	$system = mysql_fetch_row(mysql_query("SELECT name FROM onecms_systems WHERE id = '".stripslashes($r[systems])."'"));

    for ($i = 1; $i <= 5; $i++) {
	$numbers .= "<option value=\"$i\">$i</option>";
    }

	$find[] = "/{id}/";
	$find[] = "/{name}/";
	$find[] = "/{game}/";
	$find[] = "/{system}/";
	$find[] = "/{review}/";
	$find[] = "/{overall}/";
	$find[] = "/{rate}/";
	$find[] = "/{form}/";
	$find[] = "/{username}/";
	$find[] = "/{date}/";
	$repl[] = "".$r[id]."";
	$repl[] = "<a href='userreviews.php?id=".$r[id]."'>".stripslashes($r[name])."</a>";
	$repl[] = "".$game[0]."";
	$repl[] = "".$system[0]."";
	$repl[] = "".stripslashes($r[review])."";
	$repl[] = "".stripslashes($r[overall])."";
	$repl[] = $rate;
	$repl[] = "<form action='userreviews.php?id=".$r[id]."' method='post' name='userreview'><input type='hidden' name='userreview2' value='".$r[id]."'><input type='hidden' name='userreview3' value='".$r[rate]."'><select name='userreview' onchange='this.form.submit()'><option value='' selected>-</option>".$numbers."</select></form>";
	$repl[] = $user;
	$repl[] = date($dformat, $r[date]);

echo preg_replace($find, $repl, $template);
}
}

}
}

footera();
?>
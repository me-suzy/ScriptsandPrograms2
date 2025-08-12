<?php
$la = "a";
$z = "b";
include ("config.php");

if ($online == "no") {
	echo "Sorry, but ".$sitename." is offline.";
} else {
if ($ipbancheck1 == "0") {
if ($numvb == "0"){
	if ($warn == $naum) {
	echo "You are banned from the site...now go away!";
} else {

headera();

if ($_GET['user'] == "") {

echo "<table cellpadding='7' align='center' cellspacing='4' border='0'><tr><td><b>Username</b></td><td><b>E-mail</b></td><td><b># of Items posted</b></td></tr>";

$result = mysql_query("SELECT * FROM onecms_users WHERE slist = 'Yes'");
while($row = mysql_fetch_array($result)) {

		$query = mysql_query("SELECT * FROM onecms_content WHERE username = '".$row[username]."'");
		$content = mysql_num_rows($query);

		echo "<tr><td><a href='staff.php?user=".$row[username]."'>".$row[username]."</a></td><td><a href='mailto:".$row[email]."'>".$row[email]."</td><td>".$content."</td></tr>";
}

echo "</table>";
} else {

$result = mysql_query("SELECT * FROM onecms_profile WHERE username = '".$_GET['user']."'");
$profile = mysql_fetch_row($result);

$userid = $profile[0];

echo "<table cellpadding='4' cellspacing='2' border='0'><tr><td><table cellpadding='3' align='left' cellspacing='2' border='0'><tr valign='top'><tr><td><u>Info</u></td></tr><td><b>Username</b></td><td>".$profile[1]."</td></tr><tr><td><b>AIM</b></td><td>".$profile[2]."</td></tr><tr><td><b>MSN</b></td><td>".$profile[3]."</td></tr><tr><td><b>Nickname</b></td><td>".$profile[5]."</td></tr><tr><td><b>Avatar</b></td><td>";

if ($profile[8]) {
echo "<img src='".$profile[8]."' border='1'>";
} else {
echo "<i>No Avatar</i>";
}

echo "</td></td></tr></table></td><td valign='top'><table cellpadding='3' align='left' cellspacing='2' border='0'><tr><td><u>General Stats</u></td></tr>";

$result2 = mysql_query("SELECT * FROM onecms_content WHERE username = '".$_GET['user']."'");
$content = mysql_num_rows($result2);

$result21 = mysql_query("SELECT * FROM onecms_games WHERE username = '".$_GET['user']."'");
$games = mysql_num_rows($result21);

$result211 = mysql_query("SELECT * FROM onecms_posts WHERE uid = '".$userid."' AND type = 'post'");
$posts1 = mysql_num_rows($result211);

$result2111 = mysql_query("SELECT * FROM onecms_posts WHERE uid = '".$userid."' AND type = 'topic'");
$posts2 = mysql_num_rows($result2111);

echo "<tr><td><b>Content Posted</b></td><td>".$content."</td></tr><tr><td><b>Games Posted</b></td><td>".$games."</td></tr><tr><td><b>Posts posted</b></td><td>".$posts1."</td></tr><tr><td><b>Topics posted</b></td><td>".$posts2."</td></tr>";

echo "</table></td></tr><tr><td valign='top'><table cellpadding='3' align='left' cellspacing='2' border='0'><tr><td><u>Category Stats</u></td></tr>";

$cat = mysql_query("SELECT * FROM onecms_cat");
while($row = mysql_fetch_array($cat)) {

$cat2 = mysql_query("SELECT * FROM onecms_content WHERE username = '".$_GET['user']."' AND cat = '".$row[name]."'");
$catnum = mysql_num_rows($cat);
$catt = mysql_num_rows($cat2);

echo "<tr><td><b>".stripslashes($row[name])." posted</b></td><td>".$catt."</td></tr>";
}


echo "</table></td><td valign='top'><table cellpadding='3' align='left' cellspacing='2' border='0'><tr><td><u>Latest Content</u></td></tr>";

$cata = mysql_query("SELECT * FROM onecms_content WHERE username = '".$_GET['user']."' ORDER BY `id` DESC LIMIT 10");
while($row = mysql_fetch_array($cata)) {
	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."'><b>".stripslashes($row[name])."</b></a> ".$row[cat]."</td></tr>";
}

echo "</table></td></tr></table>";
}


}
}
}
}

footera();
?>
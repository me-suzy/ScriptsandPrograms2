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

if ($_GET['by']) {
$by = $_GET['by'];
} else {
$by = "id";
}

if ($_GET['type']) {
$type = $_GET['type'];
} else {
$type = "DESC";
}

if ($_GET['limit']) {
$limit = $_GET['limit'];
} else {
$limit = "50";
}

echo headera();

echo abclist("", "files.php");
if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_images WHERE name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
while($row = mysql_fetch_array($sql)) {
if ($row[type2] == "ss") {
$image = "".$images."/".$row[name]."";
} else {
$image = $row[name];
}

echo "- <a href='".$image."' target='_new'>".stripslashes($row[name])."</a><br>";
}
}
if (!$_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_images ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
while($row = mysql_fetch_array($sql)) {
if ($row[type2] == "ss") {
$image = "".$images."/".$row[name]."";
} else {
$image = $row[name];
}

echo "- <a href='".$image."' target='_new'>".stripslashes($row[name])."</a><br>";
}
}

}
}
}
}
echo footera();
?>
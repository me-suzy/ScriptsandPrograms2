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

headera();

$query="SELECT * FROM onecms_templates WHERE name = 'pages'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$template = stripslashes($row[template]);
}

if (($_GET['page'] == "") && ($_GET['id'] == "")) {
echo abclist("", "pages.php");
if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_pages WHERE type = 'backend' AND url LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
while($row = mysql_fetch_array($sql)) {
echo "- <a href='".$pagepart1."".$row[url]."".$pagepart2."'>".stripslashes($row[name])."</a><br>";
}
}
if (!$_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_pages WHERE type = 'backend' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
while($row = mysql_fetch_array($sql)) {
echo "- <a href='".$pagepart1."".$row[url]."".$pagepart2."'>".stripslashes($row[name])."</a><br>";
}
}
}

if (($_GET['page']) && ($_GET['id'] == "")) {
$query="SELECT * FROM onecms_pages WHERE url = '".$_GET['page']."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$aid = "$row[url]";

	$vatepb[] = "/{name}/";
	$vatepb[] = "/{content}/";
	$tatepb[] = "".stripslashes($row[name])."";
	$tatepb[] = "".stripslashes($row[content])."";

if ($row[online] == "No") {
	echo "Sorry, but this page is offline!";
} else {
eval (" ?>" . preg_replace($vatepb, $tatepb, $template) . " <?php ");
}
}
}

if (($_GET['page'] == "") && ($_GET['id'])) {

$query="SELECT * FROM onecms_pages WHERE id = '".$_GET['id']."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

$fetch = mysql_query("SELECT name,stats FROM onecms_content WHERE id = '".$_GET['aid']."'");
$row2 = mysql_fetch_row($fetch);

    $vatep[0] = "/{name}/";
	$vatep[1] = "/{content}/";
	$tatep[0] = "".$row2[0]."";
	$tatep[1] = "".stripslashes($row[content])."";

if ($row[online] == "No") {
	echo "Sorry, but this page is offline!";
} else {
eval (" ?>" . preg_replace($vatep, $tatep, $template) . " <?php ");
}
}

$val = $row2[1] + 1;

mysql_query("UPDATE onecms_content SET stats = '".$val."' WHERE id = '".$_GET['aid']."'");

$query2="SELECT * FROM onecms_pages WHERE url = '".$aid."' AND online = 'Yes'";
$result2=mysql_query($query2);
$num = mysql_num_rows($result2);
$num2 = $num + 1;
echo "<br><br><b>Page 1 of ".$num2."</b><br><a href='index.php?id=".$_GET['aid']."'>1</a>&nbsp;";
while($row2 = mysql_fetch_array($result2)) {

if ($_GET['id'] == $row2[id]) {
echo "<b>$row2[name]</b>&nbsp;";
} else {
echo "<a href='pages.php?id=".$row2[id]."'>".$row2[name]."</a>&nbsp;";
}
}
}

}
}
}
}
footera();
?>
<?php
$la = "a";
$z = "b";
include ("config.php");

if (((table("af_manager") == FALSE) && (table("onecms_content") == FALSE) && (table("onecms_users") == FALSE))) {
header('location: install.php');
die;
} // checks to see if onecms is not installed and if so, takes you to the install file

$postponea = mysql_query("SELECT * FROM onecms_content WHERE ver = '1'");
while($po = mysql_fetch_array($postponea)) {
if ($po[postpone]) {
if (($po[postpone] == date("YmdHi")) or ($po[postpone] < date("YmdHi"))) {
$updatea = mysql_query("UPDATE onecms_content SET ver = '0', postpone = '' WHERE id = '".$po[id]."'");
}
}
}

echo "<script language='javascript'>
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script>";

if ($online == "no") {
echo "Sorry, but ".$sitename." is offline.";
} else {
if ($ipbancheck1 == "0") {
if ($numvb == "0"){
if ($warn == $naum) {
echo "You are banned from the site...now go away!";
} else {

if ($_GET['limit']) {
	$limit = $_GET['limit'];
} else {
	$limit = "50";
}

if ($_GET['type']) {
	$type = $_GET['type'];
	$type2 = $_GET['type'];
	$type3 = $_GET['type'];
} else {
	$type = "DESC";
}

if ((!$_GET['type']) && ($template2[0] == "Yes")) {
	$type2 = "ASC";
}

if ((!$_GET['type']) && ($template2[0] == "No")) {
	$type2 = "DESC";
}

if ((!$_GET['type']) && ($template2[1] == "Yes")) {
	$type3 = "ASC";
}

if ((!$_GET['type']) && ($template2[1] == "No")) {
	$type3 = "DESC";
}

if ((!$_GET['by']) && ($template2[1] == "Yes")) {
	$by2 = "name";
}

if ((!$_GET['by']) && ($template2[1] == "No")) {
	$by2 = "id";
}

if ((!$_GET['by']) && ($template2[0] == "Yes")) {
	$by3 = "name";
}

if ((!$_GET['by']) && ($template2[0] == "No")) {
	$by3 = "id";
}

if ($_GET['by']) {
	$by = $_GET['by'];
	$by2 = $_GET['by'];
	$by3 = $_GET['by'];
} else {
	$by = "id";
}
if ($_GET['list']) {
if ($_GET['title']) {
$title = str_replace("-", " ", $_GET['title']);
echo "<title>".$title."</title>";
}

if ((((($_GET['list'] == "content") or ($_GET['list'] == "games") or ($_GET['list'] == "publisher") or ($_GET['list'] == "developer") or ($_GET['list'] == "systems"))))) {

if ($_GET['list'] == "content") {

if ($_GET['t']) {
$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$_GET['t']."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}
echo headera();
if ($_GET['s'] == "") {
echo abclist("?list=content", "");
} else {
echo abclist("?list=content&s=".$_GET['s']."", "");
}
} else {
$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template1[0]."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}
echo headera();
if ($_GET['s'] == "") {
echo abclist("?list=content", "");
} else {
echo abclist("?list=content&s=".$_GET['s']."", "");
}
}

if ($temp == "") {
if ($_GET['abc']) {
if (($_GET['s']) && ($_GET['g'])) {
$query2 = mysql_query("SELECT name FROM onecms_games WHERE id = '".$_GET['g']."'");
$row3 = mysql_fetch_row($query2);

$sql = mysql_query("SELECT * FROM onecms_content WHERE systems = '".$_GET['s']."' AND games = '".$_GET['g']."' AND ver = '0' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
if (($_GET['s']) && ($_GET['g'] == "")) {

$sql = mysql_query("SELECT * FROM onecms_content WHERE systems = '".$_GET['s']."' AND ver = '0' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'])) {
$query2 = mysql_query("SELECT name FROM onecms_games WHERE id = '".$_GET['g']."'");
$row3 = mysql_fetch_row($query2);

$sql = mysql_query("SELECT * FROM onecms_content WHERE games = '".$_GET['g']."' AND ver = '0' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE ver = '0' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
}
} else {
if (($_GET['s']) && ($_GET['g'])) {
$query2 = mysql_query("SELECT name FROM onecms_games WHERE id = '".$_GET['g']."'");
$row3 = mysql_fetch_row($query2);

$sql = mysql_query("SELECT * FROM onecms_content WHERE systems = '".$_GET['s']."' AND games = '".$_GET['g']."' AND ver = '0'  ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
if (($_GET['s']) && ($_GET['g'] == "")) {

$sql = mysql_query("SELECT * FROM onecms_content WHERE systems = '".$_GET['s']."' AND ver = '0'  ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'])) {
$query2 = mysql_query("SELECT name FROM onecms_games WHERE id = '".$_GET['g']."'");
$row3 = mysql_fetch_row($query2);

$sql = mysql_query("SELECT * FROM onecms_content WHERE games = '".$_GET['g']."' AND ver = '0'  ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE ver = '0'  ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
}
}
while($row = mysql_fetch_array($sql)) {
	echo "- <a href='".$part1."".$row[id]."".$part2."'>".stripslashes($row[name])."</a><br>";
}
echo footera();
	} else {

if ($_GET['abc']) {
if (($_GET['s']) && ($_GET['g'])) {
$query2 = mysql_query("SELECT name FROM onecms_games WHERE id = '".$_GET['g']."'");
$row3 = mysql_fetch_row($query2);

$sql = mysql_query("SELECT * FROM onecms_content WHERE systems = '".$_GET['s']."' AND games = '".$_GET['g']."' AND ver = '0' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
if (($_GET['s']) && ($_GET['g'] == "")) {

$sql = mysql_query("SELECT * FROM onecms_content WHERE systems = '".$_GET['s']."' AND ver = '0' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'])) {
$query2 = mysql_query("SELECT name FROM onecms_games WHERE id = '".$_GET['g']."'");
$row3 = mysql_fetch_row($query2);

$sql = mysql_query("SELECT * FROM onecms_content WHERE games = '".$_GET['g']."' AND ver = '0' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE ver = '0' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
}
} else {
if (($_GET['s']) && ($_GET['g'])) {
$query2 = mysql_query("SELECT name FROM onecms_games WHERE id = '".$_GET['g']."'");
$row3 = mysql_fetch_row($query2);

$sql = mysql_query("SELECT * FROM onecms_content WHERE systems = '".$_GET['s']."' AND games = '".$_GET['g']."' AND ver = '0' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
if (($_GET['s']) && ($_GET['g'] == "")) {

$sql = mysql_query("SELECT * FROM onecms_content WHERE systems = '".$_GET['s']."' AND ver = '0' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'])) {
$query2 = mysql_query("SELECT name FROM onecms_games WHERE id = '".$_GET['g']."'");
$row3 = mysql_fetch_row($query2);

$sql = mysql_query("SELECT * FROM onecms_content WHERE games = '".$_GET['g']."' AND ver = '0' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE ver = '0'  ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
}
}
while($row = mysql_fetch_array($sql)) {
    $system13 = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE id2 = '".$row[id]."' AND name = 'systems' AND cat = 'content'"));
	$systems = $system13[0];

	$squery = mysql_query("SELECT icon,name FROM onecms_systems WHERE id = '".$systems."'");
	$systemsa = mysql_fetch_row($squery);

	    $pryr[0] = "/{name}/";
		$pryr[1] = "/{link}/";
		$pryr[2] = "/{date}/";
		$pryr[3] = "/{id}/";
		$pryr[4] = "/{icon}/";
		$pryr[5] = "/{cat}/";
		$aryr[0] = "".stripslashes($row[name])."";
		$aryr[1] = "".$siteurl."/".$part1."".$row[id]."".$part2."";
		$aryr[2] = "".date($dformat, $row[date])."";
		$aryr[3] = "".$row[id]."";
		$aryr[4] = "<img src='".stripslashes($systemsa[0])."'>";
		$aryr[5] = $row[cat];

		eval (" ?>" . preg_replace($pryr, $aryr, $temp) . " <?php ");
	}
}
if (($_GET['t'] == "") && ($template1[0])) {
echo footera();
}
}

if ($_GET['list'] == "games") {

if ($_GET['t']) {
$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$_GET['t']."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}
echo headera();
if ($_GET['s'] == "") {
echo abclist("?list=games", "");
} else {
echo abclist("?list=games&s=".$_GET['s']."", "");
}
} else {
$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template1[1]."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}
echo headera();
if ($_GET['s'] == "") {
echo abclist("?list=games", "");
} else {
echo abclist("?list=games&s=".$_GET['s']."", "");
}
}

if ($temp == "") {
if ($_GET['s'] == "") {
if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_games WHERE name LIKE '".$_GET['abc']."%' ORDER BY `".$by3."` ".$type3." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM onecms_games ORDER BY `".$by3."` ".$type3." LIMIT ".$limit."");
}
} else {
if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_games WHERE system = '".$_GET['s']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by3."` ".$type3." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM onecms_games WHERE system = '".$_GET['s']."' ORDER BY `".$by3."` ".$type3." LIMIT ".$limit."");
}
}
while($row = mysql_fetch_array($sql)) {
	echo "- <a href='".$gamepart1."".$row[id]."".$gamepart2."'>".stripslashes($row[name])."</a><br>";
}

echo footera();

} else {

if ($_GET['s'] == "") {
if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_games WHERE name LIKE '".$_GET['abc']."%' ORDER BY `".$by3."` ".$type3." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM onecms_games ORDER BY `".$by3."` ".$type3." LIMIT ".$limit."");
}
} else {
if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_games WHERE system = '".$_GET['s']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by3."` ".$type3." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM onecms_games WHERE system = '".$_GET['s']."' ORDER BY `".$by3."` ".$type3." LIMIT ".$limit."");
}
}
while($row = mysql_fetch_array($sql)) {

	$r = explode("|", $row['release']);
	$release = "".$r[0]." ".$r[1]." ".$r[2]."";

	    $pryr[0] = "/{name}/";
		$pryr[1] = "/{link}/";
		$pryr[2] = "/{date}/";
		$pryr[3] = "/{id}/";
		$pryr[4] = "/{views}/";
		$pryr[5] = "/{username}/";
		$pryr[6] = "/{publisher}/";
		$pryr[7] = "/{developer}/";
		$pryr[8] = "/{genre}/";
		$pryr[9] = "/{release}/";
		$pryr[10] = "/{esrb}/";
		$pryr[11] = "/{des}/";
		$aryr[0] = "".stripslashes($row[name])."";
		$aryr[1] = "".$siteurl."/".$gamepart1."".$row[id]."".$gamepart2."";
		$aryr[2] = "".date($dformat, $row[date])."";
		$aryr[3] = "".$row[id]."";
		$aryr[4] = $row[stats];
		$aryr[5] = $row[username];
		$aryr[6] = $row[publisher];
		$aryr[7] = $row[developer];
		$aryr[8] = $row[genre];
		$aryr[9] = $release;
		$aryr[10] = $row[esrb];
		$aryr[11] = stripslashes($row[des]);

		eval (" ?>" . preg_replace($pryr, $aryr, $temp) . " <?php ");
	}
}
if (($_GET['t'] == "") && ($template1[1])) {

}
}

if ($_GET['list'] == "publisher") {

if ($_GET['t']) {
$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$_GET['t']."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}
echo headera();
echo abclist("?list=publisher", "");
} else {
$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template1[2]."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}
echo headera();
echo abclist("?list=publisher", "");
}

if ($temp == "") {
if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_pr WHERE type = '".$_GET['list']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by2."` ".$type2." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM onecms_pr WHERE type = '".$_GET['list']."' ORDER BY `".$by2."` ".$type2." LIMIT ".$limit."");
}
while($row = mysql_fetch_array($sql)) {
	echo "- <a href='".$ppart1."".$row[id]."".$ppart2."'>".stripslashes($row[name])."</a><br>";
}
echo footera();
	} else {

if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_pr WHERE type = '".$_GET['list']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by2."` ".$type2." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM onecms_pr WHERE type = '".$_GET['list']."' ORDER BY `".$by2."` ".$type2." LIMIT ".$limit."");
}
while($row = mysql_fetch_array($sql)) {
	    $pryr[0] = "/{name}/";
		$pryr[1] = "/{link}/";
		$pryr[2] = "/{date}/";
		$pryr[3] = "/{id}/";
		$pryr[4] = "/{site}/";
		$pryr[5] = "/{siteend}/";
		$pryr[6] = "/{des}/";
		$aryr[0] = "".stripslashes($row[name])."";
		$aryr[1] = "".$siteurl."/".$ppart1."".$row[id]."".$ppart2."";
		$aryr[2] = "".date($dformat, $row[date])."";
		$aryr[3] = "".$row[id]."";
		$aryr[4] = "<a href='".$row[site]."'>";
		$aryr[5] = "</a>";
		$aryr[6] = stripslashes($row[des]);

		eval (" ?>" . preg_replace($pryr, $aryr, $temp) . " <?php ");
	}
}
if (($_GET['t'] == "") && ($template1[2])) {
echo footera();
}
}

if ($_GET['list'] == "developer") {

if ($_GET['t']) {
$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$_GET['t']."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}
echo headera();
echo abclist("?list=developer", "");
} else {
$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template1[2]."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}
echo headera();
echo abclist("?list=developer", "");
}

if ($temp == "") {
if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_pr WHERE type = '".$_GET['list']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by2."` ".$type2." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM onecms_pr WHERE type = '".$_GET['list']."' ORDER BY `".$by2."` ".$type2." LIMIT ".$limit."");
}
while($row = mysql_fetch_array($sql)) {
	echo "- <a href='".$ppart1."".$row[id]."".$ppart2."'>".stripslashes($row[name])."</a><br>";
}
echo footera();

	} else {

if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_pr WHERE type = '".$_GET['list']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by2."` ".$type2." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM onecms_pr WHERE type = '".$_GET['list']."' ORDER BY `".$by2."` ".$type2." LIMIT ".$limit."");
}
while($row = mysql_fetch_array($sql)) {
	    $pryr[0] = "/{name}/";
		$pryr[1] = "/{link}/";
		$pryr[2] = "/{date}/";
		$pryr[3] = "/{id}/";
		$pryr[4] = "/{site}/";
		$pryr[5] = "/{siteend}/";
		$pryr[6] = "/{des}/";
		$aryr[0] = "".stripslashes($row[name])."";
		$aryr[1] = "".$siteurl."/".$ppart1."".$row[id]."".$ppart2."";
		$aryr[2] = "".date($dformat, $row[date])."";
		$aryr[3] = "".$row[id]."";
		$aryr[4] = "<a href='".$row[site]."'>";
		$aryr[5] = "</a>";
		$aryr[6] = stripslashes($row[des]);

		eval (" ?>" . preg_replace($pryr, $aryr, $temp) . " <?php ");
	}
}
if (($_GET['t'] == "") && ($template1[2])) {
echo footera();
}
}

if ($_GET['list'] == "systems") {

$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$_GET['t']."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}

if ($temp == "") {
echo headera();
echo abclist("?list=systems", "");

if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_systems WHERE name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM onecms_systems ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
while($row = mysql_fetch_array($sql)) {
echo "- <a href='index.php?id=systems&sid=".$row[abr]."'>".stripslashes($row[name])."</a><br>";
}

footera();
	} else {

if ($_GET['abc']) {
$sql = mysql_query("SELECT * FROM onecms_systems WHERE name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
$sql = mysql_query("SELECT * FROM onecms_systems ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
while($row = mysql_fetch_array($sql)) {
	    $pryr[0] = "/{name}/";
		$pryr[1] = "/{link}/";
		$pryr[2] = "/{abr}/";
		$pryr[3] = "/{status}/";
		$pryr[4] = "/{icon}/";
		$pryr[5] = "/{id}/";
		$aryr[0] = "".stripslashes($row[name])."";
		$aryr[1] = "index.php?id=systems&sid=".$row[abr]."";
		$aryr[2] = "".stripslashes($row[abr])."";
		$aryr[3] = "".stripslashes($row[status])."";
		$aryr[4] = "<img src='".stripslashes($row[icon])."'>";
		$aryr[5] = $row[id];

		eval (" ?>" . preg_replace($pryr, $aryr, $temp) . " <?php ");
	}
}
}

} else {
if ($_GET['list']) {
if ($_GET['t']) {
$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$_GET['t']."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}
echo headera();
if ($_GET['s'] == "") {
echo abclist("?list=".$_GET['list']."", "");
} else {
echo abclist("?list=".$_GET['list']."&s=".$_GET['s']."", "");
}
} else {
$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template1[0]."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}
echo headera();
if ($_GET['s'] == "") {
echo abclist("?list=".$_GET['list']."", "");
} else {
echo abclist("?list=".$_GET['list']."&s=".$_GET['s']."", "");
}
}

if ($temp == "") {
if ($_GET['abc']) {
if (($_GET['s']) && ($_GET['g'])) {

$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND systems = '".$_GET['s']."' AND games = '".$_GET['g']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
if (($_GET['s']) && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND systems = '".$_GET['s']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'])) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND games = '".$_GET['g']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
}
} else {
if (($_GET['s']) && ($_GET['g'])) {

$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND systems = '".$_GET['s']."' AND games = '".$_GET['g']."' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
if (($_GET['s']) && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND systems = '".$_GET['s']."' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'])) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND games = '".$_GET['g']."' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
}
}
while($row = mysql_fetch_array($sql)) {

    $system13 = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE id2 = '".$row[id]."' AND name = 'systems' AND cat = 'content'"));
	$systems = $system13[0];

	$squery = mysql_query("SELECT icon,name FROM onecms_systems WHERE id = '".$systems."'");
	$icon = mysql_fetch_row($squery);

	echo "- <a href='".$part1."".$row[id]."".$part2."'>".stripslashes($row[name])."</a> ";
	
	if ($icon[0]) {
	echo "<img src='".stripslashes($icon[0])."'>";
	}
	echo "<br>";
}
echo footera();

	} else {

if ($_GET['abc']) {
if (($_GET['s']) && ($_GET['g'])) {

$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND systems = '".$_GET['s']."' AND games = '".$_GET['g']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
if (($_GET['s']) && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND systems = '".$_GET['s']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'])) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND games = '".$_GET['g']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND name LIKE '".$_GET['abc']."%' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
}
} else {
if (($_GET['s']) && ($_GET['g'])) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND systems = '".$_GET['s']."' AND games = '".$_GET['g']."' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
} else {
if (($_GET['s']) && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND systems = '".$_GET['s']."' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'])) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' AND games = '".$_GET['g']."' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
if (($_GET['s'] == "") && ($_GET['g'] == "")) {
$sql = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$_GET['list']."' ORDER BY `".$by."` ".$type." LIMIT ".$limit."");
}
}
}
while($row = mysql_fetch_array($sql)) {

    $systems = $row[systems];

	$squery = mysql_query("SELECT icon,name FROM onecms_systems WHERE id = '".$systems."'");
	$icon = mysql_fetch_row($squery);

	    $pryr[0] = "/{name}/";
		$pryr[1] = "/{link}/";
		$pryr[2] = "/{date}/";
		$pryr[3] = "/{id}/";
		$pryr[4] = "/{icon}/";
		$pryr[5] = "/{cat}/";
		$aryr[0] = "".stripslashes($row[name])."";
		$aryr[1] = "".$siteurl."/".$part1."".$row[id]."".$part2."";
		$aryr[2] = "".date($dformat, $row[date])."";
		$aryr[3] = "".$row[id]."";
		$aryr[4] = "<img src='".$icon[0]."'>";
		$aryr[5] = $_GET['list'];

		eval (" ?>" . preg_replace($pryr, $aryr, $temp) . " <?php ");
	}
	echo footera();
}
}
}

} else {

if (((($_GET['list'] == "") && ($_GET['pid'] == "") && ($_GET['id'] == "") && ($_GET['gid'] == "")))) {
echo headera();
$query="SELECT * FROM onecms_templates WHERE name = 'index'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
eval (" ?>" . stripslashes($row[template]) . " <?php ");
}
echo footera();
}

if ($_GET['id'] == "systems") {
$sys = mysql_fetch_row(mysql_query("SELECT skin FROM onecms_systems WHERE abr = '".$_GET['sid']."'"));

if ($sys[0]) {
$query="SELECT * FROM onecms_skins WHERE id = '".$sys[0]."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

	 eval (" ?>" . stripslashes($row[header]) . " <?php ");

}
} else {
headera();
}
$query="SELECT * FROM onecms_templates WHERE name = 'systems'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$templateu = stripslashes($row[template]);
}

$queryty="SELECT * FROM onecms_systems WHERE abr = '".$_GET['sid']."'";
$resultty=mysql_query($queryty);
while($row2 = mysql_fetch_array($resultty)) {
	   $sy1[0] = "/{id}/";
       $sy1[1] = "/{name}/";
	   $sy1[2] = "/{abr}/";
	   $sy1[3] = "/{icon}/";
	   $sy2[0] = "".$row2[id]."";
	   $sy2[1] = "".$row2[name]."";
	   $sy2[2] = "".$row2[abr]."";
	   $sy2[3] = "<img src='".$row2[icon]."'>";

	   eval (" ?>" . preg_replace($sy1, $sy2, $templateu) . " <?php ");

}

$sys = mysql_fetch_row(mysql_query("SELECT skin FROM onecms_systems WHERE abr = '".$_GET['sid']."'"));

if ($sys[0]) {
$query="SELECT * FROM onecms_skins WHERE id = '".$sys[0]."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

	 eval (" ?>" . stripslashes($row[footer]) . " <?php ");

}
} else {
footera();
}

}

if ($_GET['gid']) {
$sys = mysql_fetch_row(mysql_query("SELECT skin FROM onecms_games WHERE id = '".$_GET['gid']."'"));

if ($sys[0]) {
$query="SELECT * FROM onecms_skins WHERE id = '".$sys[0]."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

	 eval (" ?>" . stripslashes($row[header]) . " <?php ");

}
} else {
headera();
}

$query="SELECT * FROM onecms_templates WHERE name = 'games'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$templateabc = stripslashes($row[template]);
}

$query="SELECT * FROM onecms_games WHERE id = '".$_GET['gid']."'";
$result=mysql_query($query);
while($rowbloo = mysql_fetch_array($result)) {
$stats = "$rowbloo[stats]";
$val = $stats + 1;

	$r = explode("|", $rowbloo['release']);
	$release = "".$r[0]." ".$r[1]." ".$r[2]."";

	$gamesql = mysql_fetch_row(mysql_query("SELECT id FROM onecms_pr WHERE name = '".$rowbloo['publisher']."'"));

	$gamesql2 = mysql_fetch_row(mysql_query("SELECT id FROM onecms_pr WHERE name = '".$rowbloo['developer']."'"));

    $btype = mysql_fetch_row(mysql_query("SELECT type2 FROM onecms_images WHERE name = '".$rowbloo['boxart']."'"));

	$vate[] = "/{name}/";
	$vate[] = "/{username}/";
	$vate[] = "/{publisher}/";
	$vate[] = "/{developer}/";
	$vate[] = "/{genre}/";
	$vate[] = "/{release}/";
	$vate[] = "/{esrb}/";
	$vate[] = "/{boxart}/";
	$vate[] = "/{des}/";
	$vate[] = "/{id}/";
	$vate[] = "/{views}/";
	$vate[] = "/{game-favorites}/";
	$vate[] = "/{game-playing}/";
	$vate[] = "/{game-tracked}/";
	$vate[] = "/{game-wishlist}/";
	$vate[] = "/{game-collection}/";
	$vate[] = "/{game-systems}/";
	$tate[] = "".$rowbloo["name"]."";
	$tate[] = "".$rowbloo["username"]."";
	$tate[] = "<a href='".$ppart1."".$gamesql[0]."".$ppart2."'>".$rowbloo['publisher']."</a>";
	$tate[] = "<a href='".$ppart1."".$gamesql2[0]."".$ppart2."'>".$rowbloo['developer']."</a>";
	$tate[] = "".$rowbloo['genre']."";
	$tate[] = "".$release."";
	$tate[] = "".$rowbloo['esrb']."";
	if ($rowbloo['boxart']) {
	if ($btype[0] == "ss") {
	$tate[] = "<img src='".$images."/".$rowbloo['boxart']."' border='1'>";
	} else {
	$tate[] = "<img src='".$rowbloo['boxart']."' border='1'>";
	}
	} else {
	$tate[] = "<img src='".$siteurl."/a_images/noboxart.jpg'>";
	}
	$tate[] = "".stripslashes($rowbloo['des'])."";
	$tate[] = $_GET['gid'];
	$tate[] = "".$val."";
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elitef&id=".$rowbloo[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1' title='Favorites' alt='Favorites'></a>";
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elitet&id=".$rowbloo[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1' title='Tracked' alt='Tracked'></a>";
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elitep&id=".$rowbloo[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1' title='Playing' alt='Playing'></a>";
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elitew&id=".$rowbloo[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1' title='Wishlist' alt='Wishlist'></a>";
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elitec&id=".$rowbloo[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1' title='Collection' alt='Collection'></a>";
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elites&id=".$rowbloo[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_s.jpg' border='1' title='Systems' alt='Systems'></a>";

	$sqlabc = mysql_query("SELECT * FROM onecms_cat ORDER BY `id` DESC");
	while ($ra = mysql_fetch_array($sqlabc)) {

		$brb = mysql_fetch_row(mysql_query("SELECT id FROM onecms_content WHERE cat = '".$ra[name]."' AND name LIKE '%" . $rowbloo["name"] . "%'"));

        if ($brb[0]) {
		$vate[] = "/{".$ra[name].";/";
		$vate[] = "/;".$ra[name]."}/";
		$tate[] = "<a href='".$part1."".$brb[0]."".$part2."'>";
		$tate[] = "</a>";
		} else {
		$vate[] = "/{".$ra[name].";/";
		$vate[] = "/;".$ra[name]."}/";
		$tate[] = "";
		$tate[] = "";
		}
		}

mysql_query("UPDATE onecms_games SET stats = '".$val."' WHERE id = '".$_GET['gid']."'");

$sql = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'games'");
while ($row = mysql_fetch_array($sql)) {
$name = stripslashes($row[name]);

$dataa = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE name = '".$name."' AND id2 = '".$rowbloo['id']."' AND cat = 'games'"));
$data = stripslashes($dataa[0]);

$vate[] = "/{".$name."}/";
$tate[] = $data;
}
eval (" ?>" . preg_replace($vate, $tate, $templateabc) . " <?php ");
}

$sys = mysql_fetch_row(mysql_query("SELECT skin FROM onecms_games WHERE id = '".$_GET['gid']."'"));

if ($sys[0]) {
$query="SELECT * FROM onecms_skins WHERE id = '".$sys[0]."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

	 eval (" ?>" . stripslashes($row[footer]) . " <?php ");

}
} else {
footera();
}

}


if ($_GET['pid']) {
headera();

$query="SELECT * FROM onecms_templates WHERE name = 'PR Manager'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$templatem = stripslashes($row[template]);
}

$queryty="SELECT * FROM onecms_pr WHERE id = '".$_GET['pid']."' ORDER BY `name` ASC";
$resultty=mysql_query($queryty);
while($rowblooe = mysql_fetch_array($resultty)) {

	$vm[] = "/{name}/";
	$vm[] = "/{site}/";
	$vm[] = "/{description}/";
	$vm[] = "/{content}/";
	$vm[] = "/{games}/";
	$tm[] = "".$rowblooe["name"]."";
	$tm[] = "".$rowblooe["site"]."";
	$tm[] = "".stripslashes($rowblooe['des'])."";
	$tm[] = "<?php include ('".$siteurl."/idlatest&p=".$rowblooe["name"]."'); ?>";
	$tm[] = "<?php include ('".$siteurl."/gamelatest&p=".$rowblooe["name"]."'); ?>";

}
eval (" ?>" . preg_replace($vm, $tm, $templatem) . " <?php ");
footera();
}

if (is_numeric($_GET['id'])) {
$sys2 = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE id2 = '".$_GET['id']."' AND name = 'games' AND cat = 'content'"));

if ($sys[0]) {
$query="SELECT * FROM onecms_skins WHERE id = '".$sys[0]."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

eval (" ?>" . stripslashes($row[header]) . " <?php ");

}
} else {
headera();
}

$query="SELECT * FROM onecms_content WHERE id = '".$_GET['id']."' AND ver = '0'";
$result=mysql_query($query);
while($row30 = mysql_fetch_array($result)) {
$cat = "$row30[cat]";
$postpone = "$row30[postpone]";
$ver = "$row30[ver]";
$stats = "$row30[stats]";
$lev = "$row30[lev]";

if (($lev == "Yes") && ($_COOKIE[username] == "")) {
	echo "Sorry, but you must be a member of ".$sitename." in order to view this content. Please <a href='members.php?action=register'>Register</a> or <a href='members.php?action=login&step=1'>Login</a>. Thank you.";
} else {

$query="SELECT * FROM onecms_templates WHERE name = '".$cat."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$templatebhe = stripslashes($row[template]);
}

$cnum2 = mysql_query("SELECT * FROM onecms_comments2 WHERE aid = '".$_GET['id']."'");
$cnum = mysql_num_rows($cnum2);

$query="SELECT * FROM onecms_pages WHERE url = '".$_GET['id']."' AND online = 'Yes'";
$result=mysql_query($query);
$num = mysql_num_rows($result);
$num2 = $num + 1;
$showthis = "<b>Page 1 of ".$num2."</b><br>1 ";
while($row = mysql_fetch_array($result)) {

$showthis .= "<a href='pages.php?id=".$row[id]."&aid=".$_GET['id']."'>$row[name]</a> ";
}

	$systems = $row30[systems];

	$games = $row30[games];

	$albums = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE id2 = '".$_GET['id']."' AND name = 'albums' AND cat = 'content'"));

	$squery = mysql_query("SELECT icon,name FROM onecms_systems WHERE id = '".$systems."'");
	$systemsa = mysql_fetch_row($squery);

	$vate[] = "/{name}/";
	$vate[] = "/{cat}/";
	$vate[] = "/{systems}/";
	$vate[] = "/{icon}/";
	$vate[] = "/{username}/";
	$vate[] = "/{date}/";
	$vate[] = "/{views}/";
	$vate[] = "/{cform}/";
	$vate[] = "/{comments}/"; // shows the comments
	$vate[] = "/{clink}/";
	$vate[] = "/{endclink}/";
	$vate[] = "/{cnum}/";
	$vate[] = "/{id}/";
	$vate[] = "/{pagelist}/";
	if (($games) && ($_COOKIE[username])) {
	$vate[] = "/{game-favorites}/";
	$vate[] = "/{game-playing}/";
	$vate[] = "/{game-tracked}/";
	$vate[] = "/{game-wishlist}/";
	$vate[] = "/{game-collection}/";
	$vate[] = "/{game-systems}/";
	}
	$tate[] = stripslashes($row30["name"]);
	$tate[] = $row30["cat"];
	$tate[] = stripslashes($systems[1]);
	$tate[] = "<img src='".stripslashes($systemsa[0])."'>";
	$tate[] = stripslashes($row30["username"]);
	$tate[] = date($dformat, $row30['date']);

	$val = $stats + 1;

	$tate[] = "".$val."";
	$tate[] = "<a href='javascript:awindow(\"comments.php?aid=".$_GET[id]."\", \"\", \"width=400,height=236,scroll=yes\")'>Post Comment</a>";
    $tate[] = "<a href='javascript:awindow(\"comments.php?j=".$_GET['id']."\", \"\", \"width=600,height=450,scroll=yes\")'>View Comments</a>";
	$tate[] = "<a href='comments.php?id=".$_GET['id']."'>";
	$tate[] = "</a>";
	$tate[] = "$cnum";
	$tate[] = $_GET['id'];
	$tate[] = $showthis;
	if (($games) && ($_COOKIE[username])) {
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elitef&id=".$games."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1' title='Favorites' alt='Favorites'></a>";
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elitet&id=".$games."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1' title='Tracked' alt='Tracked'></a>";
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elitep&id=".$games."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1' title='Playing' alt='Playing'></a>";
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elitew&id=".$games."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1' title='Wishlist' alt='Wishlist'></a>";
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elitec&id=".$games."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1' title='Collection' alt='Collection'></a>";
	$tate[] = "<a href='javascript:awindow(\"elite.php?view=elites&id=".$systems."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_s.jpg' border='1' title='Systems' alt='Systems'></a>";
	}

	$gameid = stripslashes($games);

	if ($albums) {
	$gal = mysql_fetch_row(mysql_query("SELECT id FROM onecms_albums WHERE id = '".$albums."'"));

	$vate[] = "/{".$name."}/";
	$vate[] = "/{gallery}/";
	$vate[] = "/{gallery;/";
	$vate[] = "/{endgallery}/";
	$vate[] = "/;gallery}/";
	$vate[] = "/{cat-gallery}/";
	$tate[] = "<a href='".$gpart1."".$gal[0]."".$gpart2."'>Gallery</a>";
	$tate[] = "<a href='".$gpart1."".$gal[0]."".$gpart2."'>";
	$tate[] = "<a href='".$gpart1."".$gal[0]."".$gpart2."'>";
	$tate[] = "</a>";
	$tate[] = "</a>";
	$tate[] = "".$gpart1."".$galid[0]."".$gpart2."";
	} else {
	$galid = mysql_fetch_row(mysql_query("SELECT id FROM onecms_albums WHERE name LIKE '%" . addslashes($row30["name"]) . "%'"));
    if ($galid[0]) {
	$vate[] = "/{".$name."}/";
	$vate[] = "/{gallery}/";
	$vate[] = "/{gallery;/";
	$vate[] = "/{endgallery}/";
	$vate[] = "/;gallery}/";
	$vate[] = "/{cat-gallery}/";
	$tate[] = "<a href='".$gpart1."".$galid[0]."".$gpart2."'>Gallery</a>";
	$tate[] = "<a href='".$gpart1."".$galid[0]."".$gpart2."'>";
	$tate[] = "<a href='".$gpart1."".$galid[0]."".$gpart2."'>";
	$tate[] = "</a>";
	$tate[] = "</a>";
	$tate[] = "".$gpart1."".$galid[0]."".$gpart2."";
	} else {
	$vate[] = "/{".$name."}/";
	$vate[] = "/{gallery}/";
	$vate[] = "/{gallery;/";
	$vate[] = "/{endgallery}/";
	$vate[] = "/;gallery}/";
	$vate[] = "/{cat-gallery}/";
	$tate[] = "";
	$tate[] = "";
	$tate[] = "";
	$tate[] = "";
	$tate[] = "";
	$tate[] = "";
	}
	}

    if ($gameid) {
	$sqlabc = mysql_query("SELECT * FROM onecms_cat ORDER BY `id` DESC");
	while ($ra = mysql_fetch_array($sqlabc)) {

		$brb = mysql_fetch_row(mysql_query("SELECT id FROM onecms_content WHERE cat = '".$ra[name]."' AND name LIKE '%" . $row30["name"] . "%'"));

        if ($brb[0]) {
		$vate[] = "/{".$ra[name].";/";
		$vate[] = "/;".$ra[name]."}/";
		$vate[] = "/{cat-".$ra[name]."}/";
		$tate[] = "<a href='".$part1."".$brb[0]."".$part2."'>";
		$tate[] = "</a>";
		$tate[] = "".$part1."".$brb[0]."".$part2."";
		} else {
		$vate[] = "/{".$ra[name].";/";
		$vate[] = "/;".$ra[name]."}/";
		$vate[] = "/{cat-".$ra[name]."}/";
		$tate[] = "";
		$tate[] = "";
		$tate[] = "";
		}
		}
	} else {
	$sqlabca = mysql_query("SELECT * FROM onecms_cat ORDER BY `id` DESC");
	while ($raa = mysql_fetch_array($sqlabca)) {
	$vate[] = "/{".$raa[name].";/";
	$vate[] = "/;".$raa[name]."}/";
	$vate[] = "/{cat-".$ra[name]."}/";
	$tate[] = "";
	$tate[] = "";
	$tate[] = "";
	}
	}
     
	if ($gameid) {
	$vate[] = "/{cat-games}/";
	$tate[] = "".$gamepart1."".$games."".$gamepart2."";
	$sql4 = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'games' ORDER BY `id` DESC");
	while ($l = mysql_fetch_array($sql4)) {
		$vate[] = "/{game-".$l[name]."}/";

	$z = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE id2 = '".$gameid."' AND cat = 'games' AND name = '".$l[name]."'"));

	$tate[] = "".stripslashes($z[0])."";
	}
	}

	if ($gameid == "") {
	$sql4 = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'games' ORDER BY `id` DESC");
	while ($l = mysql_fetch_array($sql4)) {

	$vate[] = "/{game-".$l[name]."}/";
	$tate[] = "";
	}
	}

    if ($row30[username]) {
	$p = mysql_fetch_row(mysql_query("SELECT aim,msn,website,nickname,location,sig,avatar FROM onecms_profile WHERE username = '".$row30[username]."'"));

	$vate[] = "/{user-aim}/";
	$vate[] = "/{user-msn}/";
	$vate[] = "/{user-website}/";
	$vate[] = "/{user-nickname}/";
	$vate[] = "/{user-location}/";
	$vate[] = "/{user-sig}/";
	$vate[] = "/{user-avatar}/";
	$tate[] = $p[0];
	$tate[] = $p[1];
	$tate[] = $p[2];
	$tate[] = $p[3];
	$tate[] = $p[4];
	$tate[] = stripslashes($p[5]);
	$tate[] = "<img src='".stripslashes($p[6])."'>";
	}

    if ($gameid) {
	$m = mysql_fetch_row(mysql_query("SELECT publisher,developer,genre,release,stats,username,des,boxart,esrb,name FROM onecms_games WHERE id = '".$gameid."'"));

	$ma = mysql_fetch_row(mysql_query("SELECT id FROM onecms_pr WHERE name = '".$m[0]."'"));

	$ma2 = mysql_fetch_row(mysql_query("SELECT id FROM onecms_pr WHERE name = '".$m[1]."'"));

	$vate[] = "/{game-id}/";
	$tate[] = $gameid;

	$vate[] = "/{game-publisher}/";
	$tate[] = "<a href='".$ppart1."".$ma[0]."".$ppart2."'>".stripslashes($m[0])."</a>";

	$vate[] = "/{game-developer}/";
	$tate[] = "<a href='".$ppart1."".$ma2[0]."".$ppart2."'>".stripslashes($m[1])."</a>";

	$vate[] = "/{game-genre}/";
	$tate[] = "".stripslashes($m[2])."";

	$re = explode("|", "".stripslashes($m[3])."");

    if ($re[1]) {
	$release = "".$re[0]." ".$re[1].", ".$re[2]."";
	} else {
	$release = "".$re[0]."";
	}

	$vate[] = "/{game-release}/";
	$tate[] = "".$release."";

	$vate[] = "/{game-views}/";
	$tate[] = "".stripslashes($m[4])."";

	$k = mysql_fetch_row(mysql_query("SELECT id FROM onecms_profile WHERE username = '".$m[5]."'"));

	$vate[] = "/{game-user}/";
	$tate[] = "<a href='elite.php?user=".$k[0]."'>".stripslashes($m[5])."</a>";

	$vate[] = "/{game-des}/";
	$tate[] = "".stripslashes($m[6])."";

	$vate[] = "/{game-boxart}/";
	$tate[] = "".$images."/".stripslashes($m[7])."";

	$vate[] = "/{game-esrb}/";
	$tate[] = "".stripslashes($m[8])."";

	$vate[] = "/{game-name}/";
	$tate[] = "".stripslashes($m[9])."";
	}
	
	if ($gameid == "") {

	$vate[] = "/{game-publisher}/";
	$tate[] = "";

	$vate[] = "/{game-developer}/";
	$tate[] = "";

	$vate[] = "/{game-genre}/";
	$tate[] = "";

	$vate[] = "/{game-release}/";
	$tate[] = "";

	$vate[] = "/{game-views}/";
	$tate[] = "";

	$vate[] = "/{game-user}/";
	$tate[] = "";

	$vate[] = "/{game-des}/";
	$tate[] = "";

	$vate[] = "/{game-boxart}/";
	$tate[] = "";

	$vate[] = "/{game-esrb}/";
	$tate[] = "";

	$vate[] = "/{game-name}/";
	$tate[] = "";
	}	

	$id = $_GET['id'];

	//

mysql_query("UPDATE onecms_content SET stats = '".$val."' WHERE id = '".$id."'");

$sys21[0] = $row30[games];

$sql = mysql_query("SELECT * FROM onecms_fields WHERE cat = '".$cat."' OR cat = ''");
while ($row = mysql_fetch_array($sql)) {
$name = "$row[name]";
$type = "$row[type]";

if ($type == "games") {
if ($gameid) {
$gamename = mysql_fetch_row(mysql_query("SELECT name FROM onecms_games WHERE id = '".$gameid."'"));
		$vate[] = "/{".$name."}/";
	    $vate[] = "/{game;/";
	    $vate[] = "/;game}/";
		$tate[] = stripslashes($gamename[0]);
		$tate[] = "<a href='".$gamepart1."".$gameid."".$gamepart2."'>";
		$tate[] = "</a>";
} else {
		$vate[] = "/{".$name."}/";
	    $vate[] = "/{game;/";
	    $vate[] = "/;game}/";
		$tate[] = "";
		$tate[] = "";
		$tate[] = "";
}
} else {
$dataa = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE name = '".$name."' AND id2 = '".$id."' AND cat = 'content'"));

if ($dataa[0]) {
$vate[] = "/{".$name."}/";
$tate[] = stripslashes($dataa[0]);
} else {
$vate[] = "/{".$name."}/";
$tate[] = "";
}
}
}

$quer = mysql_query("SELECT * FROM onecms_content WHERE id = '".$id."' AND postpone = '' AND ver = '0'");
$i = mysql_num_rows($quer);
if ($i > "0") {
if (($postpone == date("YmdHi")) or ($postpone < date("YmdHi"))) {
if ($cat) {
eval (" ?>" . preg_replace($vate, $tate, $templatebhe) . " <?php ");
}
}
} else {
if ($cat) {
eval (" ?>" . preg_replace($vate, $tate, $templatebhe) . " <?php ");
}
}
}

$sys22 = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE id2 = '".$_GET['id']."' AND name = 'albums' AND cat = 'content'"));
$sys23 = mysql_fetch_row(mysql_query("SELECT cat FROM onecms_content WHERE id = '".$_GET['id']."' AND cat = 'content'"));

$sysabc[] = $sys21[0];
$sysabc[] = $sys22[0];
$sysabc[] = $sys23[0];

$fetch7 = @implode(",", $sysabc);
$sys2 = @explode(",", $fetch7);

if ((($sys2[1] == "media") && ($albpages == "Yes") && ($sys2[2]))) {
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_images WHERE album = '".$sys2[2]."'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page</center><br>";

// Build Previous Link
if ($page > 1) {
    $prev = ($page - 1);
    echo "<a href=\"index.php?id=".$_GET['id']."&p=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"index.php?id=".$_GET['id']."&p=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if ($page > 1) {
    $next = ($page + 1);
    echo "<a href=\"index.php?id=".$_GET['id']."&p=$next\">Next>></a>";
}
echo "</center>";
}

if ($sys[0]) {
$query="SELECT * FROM onecms_skins WHERE id = '".$sys[0]."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

	 eval (" ?>" . stripslashes($row[footer]) . " <?php ");

}
} else {
footera();
}

}
}
}

}
}
}
}

if ($timeamount == "yes") {
$timediff = microtime() - $timeabc;
echo "<br><br>Time taken: ".$timediff;
}
?>
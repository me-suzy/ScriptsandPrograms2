<?php
include ("config.php");
$date = date("F j, Y");

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

if ($_GET['a'] == "add") {

$verified = "yes";

$date = date("F j, Y");

$resultID = mysql_query("INSERT INTO af_manager VALUES ('null', '".$_POST['sitename']."', '".$_POST['siteurl']."', '".$_POST['where']."', '".$_POST['type']."', '', '', '', '$verified', '".time()."', '0')") or die(mysql_error());

if ($resultID == TRUE) {

echo "".$_POST['sitename']." has been added to the list of your affiliates. <a href='a_af.php'>AF Manager Home</a>";

} else {

echo "Sorry, but ".$_POST['sitename']." could not be added to the list of your affiliates.";

}
}

if ($_GET['a'] == "add2") {

if (($_POST['ss'] == "") && ($_POST['ss2'] = "")) {

echo 'Hey, go back!';

} else {

if ($_POST['ss2'] == "") {

copy ($_FILES["ss"]["tmp_name"], "$path".$_FILES["ss"]["name"]."");

$ss3 = "".$_FILES["ss"]["name"]."";

$sql =mysql_query("INSERT INTO af_manager VALUES ('null', '".$_POST['sitename']."', '".$_POST['siteurl']."', '".$_POST['where']."', 'button', '".$_POST['width']."', '".$_POST['height']."', '$ss3', 'yes', '".time()."', '0')") or die(mysql_error());
if ($sql == TRUE) {

echo "".$_POST['sitename']." has been added to the list of your affiliates. <a href='a_af.php'>AF Manager Home</a>";

} else {

echo "Sorry, but the affiliate could not be added to the list of your affiliates.";

}

} else {

$sql=mysql_query("INSERT INTO af_manager VALUES ('null', '".$_POST['sitename']."', '".$_POST['siteurl']."', '".$_POST['where']."', 'button', '".$_POST['width']."', '".$_POST['height']."', '".$_POST['ss2']."', 'yes', '".time()."', '0')") or die(mysql_error());
if ($sql == TRUE) {

echo "".$_POST['sitename']." has been added to the list of your affiliates. <a href='a_af.php'>AF Manager Home</a>";

} else {

echo "Sorry, but ".$_POST['sitename']." could not be added to the list of your affiliates.";

}
}
}
}
}
}
}
}include ("a_footer.inc");
?>
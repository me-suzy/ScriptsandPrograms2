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
	echo "Sorry $username, but you do not have permission to manage affiliates. You are only a $level.";
} else {

echo "<center><a href='a_af_add.php'>Add Affiliate</a> | <a href='a_af.php'>Manage Affiliates</a></center><br><br>";

if (($_GET['view'] == "manage") && ($_POST['ver'])) {

echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to verify this affiliate(s)?");
if (agree)
document.write("");
else
history.go(-1);
</SCRIPT>';

while (list(, $vala) = each ($_POST['ver'])) {
	$deletea = mysql_query("UPDATE af_manager SET verified = 'yes' WHERE id = '".$vala."'") or die(mysql_error());
}
if ($deletea == TRUE) {
	echo "The affiliate(s) have been verified. <a href=\"a_af.php\">Return to AF Manager Home</a>";
}
}

if ($_GET['view'] == "") {

				echo "<title>OneCMS - www.insanevisions.com/onecms > AF Manager</title>";

	echo "<form action='a_af.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Site Name</b></td><td><b>Verified?</b></td><td><b><b>Edit</b></td><td><b><b>Verify</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM af_manager ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[sitename]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='af.php?view=click&id=".$row[id]."' target='popup'>$name</a></td><td>$row[verified]</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"ver[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM af_manager"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_af.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_af.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_af.php?page=$next\">Next>></a>";
}
echo "</center>";

}

if (($_GET['view'] == "manage") && ($_POST['id'])) {

if (!$_POST['submitok']) {
echo "<form action='a_af.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

 while (list(, $val) = each ($_POST['id'])) {
$query="SELECT * FROM af_manager WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$sitename = "$row[sitename]";
		$siteurl = "$row[siteurl]";
		$where = "$row[where2]";
		$type = "$row[type]";
		$width = "$row[width]";
		$height = "$row[height]";
		$ss = "$row[ss]";

echo "<tr><td><b><center>Affiliate # ".$val."</b></center></td></tr><tr><td>Site Name</td><td><input type=\"text\" name=\"sitename_$val\" value=\"$sitename\"></td></tr>
<tr><td>Site URL</td><td><input type=\"text\" name=\"siteurl_$val\" value=\"$siteurl\"></td></tr>
<tr><td>Open link in new window?</td><td><select name=\"where_$val\">
<option value=\"$where\">";
if ($where == "popup") {
echo "- Yes";

} else {

echo "- No";
}
echo "</option><option value=\"popup\">Yes</option>
<option value=\"_blank\">No</option></select></td></tr>
<tr><td>Type of Affiliation</td><td>
<select name=\"type_$val\">
<option value=\"$type\">";
if ($type == "link") {
echo "- Text Link";

} else {

echo "- Button";
}
echo "</option><option value=\"link\">Text Link</option>
<option value=\"button\">Button</option>
</select>
</td></tr><tr><td>
<input type=\"hidden\" name=\"id[]\" value=\"$val\">
<b>If type of affiliation is button:</b></td></tr><tr><td>Width of Button</td><td><input type=\"text\" name=\"width_$val\" value=\"$width\"></td></tr>
<tr><td>Height of Button</td><td><input type=\"text\" name=\"height_$val\" value=\"$height\"></td></tr>
<tr><td>Button URL</td><td><input type=\"text\" name=\"ss_$val\" value=\"$ss\"></td></tr>";
	}
}
echo "<tr><td><input type=\"submit\" name=\"submitok\" value=\"Edit\"></td></tr></table>";
}
}



if (($_GET['view'] == "manage") && ($_POST['delete'] == "")) {
if ($_POST['submitok']) {

$date = date("F j, Y");

while (list(, $val) = each ($_POST['id'])) {
if ($_POST["type_$val"] == "button") {

$resultID = mysql_query("UPDATE af_manager SET sitename = '".$_POST["sitename_$val"]."', siteurl = '".$_POST["siteurl_$val"]."', where2 = '".$_POST["where_$val"]."', type = '".$_POST["type_$val"]."', width = '".$_POST["width_$val"]."', height = '".$_POST["height_$val"]."', ss = '".$_POST["ss_$val"]."', date = '".time()."' WHERE id = '$val'") or die(mysql_error());

} else {

$resultID = mysql_query("UPDATE af_manager SET sitename = '".$_POST["sitename_$val"]."', siteurl = '".$_POST["siteurl_$val"]."', where2 = '".$_POST["where_$val"]."', type = '".$_POST["type_$val"]."', date = '".time()."' WHERE id = '$val'") or die(mysql_error());
}
}
	if ($resultID == TRUE) {
		print "The affiliate(s) has been updated.<br><a href=\"a_af.php\">AF Manager Home</a>";
	} else {
		print "Sorry, but the affiliate(s) could not be updated. Please try again.";
	}
}
}

if (($_GET['view'] == "manage") && ($_POST['delete'])) {
		echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {
	$delete = mysql_query("DELETE FROM af_manager WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "The affiliate(s) have been deleted. <a href=\"a_af.php\">Return to AF Manager Home</a>";
}
}

}
}
}
}
include ("a_footer.inc");
?>
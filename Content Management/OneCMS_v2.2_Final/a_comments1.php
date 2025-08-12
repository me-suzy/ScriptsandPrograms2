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
	echo "Sorry $username, but you do not have permission to access this page. You are only a $level.";
} else {

	echo "<center><a href=\"a_comments1.php?view=add1\">Add smiley</a> | <a href='a_comments1.php?view=add2'>Add bad word</a> | <a href=\"a_comments2.php\">Manage Comments</a> | <a href='a_comments1.php'>Manage smilies/bad words</a></center><br><br>";

if ($_GET['view'] == "") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Comments Manager</title>";

	echo "<form action='a_comments1.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for smiley/bad word</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_comments1.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Type</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_comments1 ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
		if ($row[type] == "smiley") {
    	echo "<tr><td><a href='".$images."/".$row[field]."' target='popup'>$name</a></td>";
		} else {
		echo "<tr><td>$name</td>";
		}
		echo "<td>$row[type]</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_comments1"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_comments1.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_comments1.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_comments1.php?page=$next\">Next>></a>";
}
echo "</center>";

}

if (($_GET['view'] == "add1") && ($_GET['add'] == "")) {
echo "<form action=\"a_comments1.php?view=add1\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many smilies to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

    if ($_POST['search']) {

echo "<form action='a_comments1.php?view=add1&add=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\"><input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
    echo "<tr><td><b><center>Smiley #".$i."</b></center></td></tr><tr><td>Smiley Tag</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>Smiley Pic</td><td><select name='url_".$i."' size='5' multiple>";
    	$query="SELECT * FROM onecms_images WHERE type = 'smiley'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	echo "<option value=\"$row[name]\">$row[name]</option>";
	}
    echo "</td></tr>";
}
echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
}
}

if (($_GET['view'] == "add2") && ($_GET['add'] == "")) {
echo "<form action=\"a_comments1.php?view=add2\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many bad words to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

    if ($_POST['search']) {

echo "<form action='a_comments1.php?view=add2&add=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\"><input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
    echo "<tr><td><b><center>Bad Word #".$i."</b></center></td></tr><tr><td>Bad Word</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>Replace With</td><td><input type=\"text\" name='name2_".$i."'></td></tr>";
}
echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
}
}

if (($_GET['view'] == "add1") && ($_GET['add'] == "yes")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   $upd = "INSERT INTO onecms_comments1 VALUES ('null', '".$_POST["name_$i"]."', '".$_POST["url_$i"]."', 'smiley')";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The smiley(s) have been created. <a href=\"a_comments1.php\">Return to Comments Manager Home</a>";
}
}

if (($_GET['view'] == "add2") && ($_GET['add'] == "yes")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   $upd = "INSERT INTO onecms_comments1 VALUES ('null', '".$_POST["name_$i"]."', '".$_POST["name2_$i"]."', 'badword')";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The bad word(s) have been created. <a href=\"a_comments1.php\">Return to Comments Manager Home</a>";
}
}
if (($_GET['view'] == "manage") && ($_POST['id'] == "")) {
		echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {
	$delete = mysql_query("DELETE FROM onecms_comments1 WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "The smiley(s)/bad word(s) have been deleted. <a href=\"a_comments1.php\">Return to Comments Manager Home</a>";
}
}
if ((($_GET['view'] == "manage") && ($_POST['delete'] == "") && ($_GET['edit'] == ""))) {

	echo "<form action='a_comments1.php?view=manage&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_comments1 WHERE id = '$i'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
    $name = stripslashes($row['name']);

     echo "<input type='hidden' name='id[]' value='".$i."'>";

    if ($row['type'] == "smiley") {
    echo "<tr><td><b><center>Smiley #".$i."</b></center></td></tr><tr><td>Smiley Tag</td><td><input type=\"text\" name='name_".$i."' value='".stripslashes($row[name])."'></td></tr><tr><td>Smiley Pic</td><td><select name='url_".$i."' size='5' multiple><option name='".$row[field]."' selected>$row[field]</option>";

    $sql = mysql_query("SELECT * FROM onecms_images WHERE type = 'smiley'");
	while($r= mysql_fetch_array($sql)) {
	echo "<option value=\"$r[name]\">$r[name]</option>";
	}
    echo "</td></tr>";
} else {
    echo "<tr><td><b><center>Bad Word #".$i."</b></center></td></tr><tr><td>Bad Word</td><td><input type=\"text\" name='name_".$i."' value='".$row[name]."'></td></tr><tr><td>Replace With</td><td><input type=\"text\" name='name2_".$i."' value='".$row[field]."'></td></tr>";
}
}
}
			echo "<tr><td><input type=\"submit\" name=\"Modify\" value=\"Modify\"></td></tr></form></table>";

			}
			if (($_GET['view'] == "manage") && ($_GET['edit'] == "2")) {

   while (list(, $i) = each ($_POST['id'])) {
   if ($_POST['type_$i'] == "smiley") {
   $upd = "UPDATE onecms_comments1 SET name = '".$_POST["name_$i"]."', field = '".$_POST["url_$i"]."' WHERE id = '".$i."'";
   $r = mysql_query($upd) or die(mysql_error());
   } else {
   $upd = "UPDATE onecms_comments1 SET name = '".$_POST["name_$i"]."', field = '".$_POST["name2_$i"]."' WHERE id = '".$i."'";
   $r = mysql_query($upd) or die(mysql_error());
   }
   }
if ($r == TRUE) {
	echo "The smiley(s)/bad word(s) have been updated. <a href='a_comments1.php'>Return to Comments Manager Home</a>";
}
}
if ($_GET['view'] == "search") {

				echo "<title>OneCMS - www.insanevisions.com/onecms > Comments Manager > Search</title>";

	echo "<form action='a_comments1.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for smiley/bad word</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_comments1.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Type</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_comments1 WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
		if ($row[type] == "smiley") {
    	echo "<tr><td><a href='".$images."/".$row[field]."' target='popup'>$name</a></td>";
		} else {
		echo "<tr><td>$name</td>";
		}
		echo "<td>$row[type]</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_comments1.php?view=add\">Add Company</a></td></tr></form></table><br><br>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_comments1 WHERE name LIKE '%" . $_POST['search'] . "%'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_comments1.php?view=search&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_comments1.php?view=search&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_comments1.php?view=search&page=$next\">Next>></a>";
}
echo "</center>";

}
}
}
}
}include ("a_footer.inc");
?>
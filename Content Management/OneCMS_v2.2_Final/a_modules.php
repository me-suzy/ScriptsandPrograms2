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
	echo "Sorry $username, but you do not have permission to the Module Manager. You are only a $level.";
} else {

echo "<center><a href='a_modules.php?view=add'>Add Modules</a> | <a href='a_modules.php'>Manage Modules</a> | <a href='a_modules.php?view=search'>Search</a></center><br><br>";

if ($_GET['view'] == "search") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Modules > Search</title>";

	echo "<form action='a_modules.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for module</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_modules.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Module Name</b></td><td><b>URL</b></td><td><b>Status</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_mods WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name = stripslashes($row[name]);
		$url = stripslashes($row[url]);
    	echo "<tr><td>".$name."</td><td><a href='".$url."' target='popup'>".$url."</a></td><td>";
		if (($row[status] == "") or ($row[status] == "Off")) {
		echo "<font color='red'>Off</font>";
		} else {
		echo "<font color='blue'>On</font>";
		}
		echo "</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_mods WHERE name LIKE '%" . $_POST['search'] . "%'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_modules.php?view=search&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_modules.php?view=search&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_modules.php?view=search&page=$next\">Next>></a>";
}
echo "</center>";

}

if ($_GET['view'] == "") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Manage Modules</title>";

	echo "<form action='a_modules.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for module</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_modules.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Module Name</b></td><td><b>URL</b></td><td><b>Status</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

$query="SELECT * FROM onecms_mods ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name = stripslashes($row[name]);
		$url = stripslashes($row[url]);
    	echo "<tr><td>".$name."</td><td><a href='".$url."' target='popup'>".$url."</a></td><td>";
		if (($row[status] == "") or ($row[status] == "Off")) {
		echo "<font color='red'>Off</font>";
		} else {
		echo "<font color='blue'>On</font>";
		}
		echo "</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_mods"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_modules.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_modules.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_modules.php?page=$next\">Next>></a>";
}
echo "</center>";
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

	$delete = mysql_query("DELETE FROM onecms_mods WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "The module(s) have been deleted. <a href=\"a_modules.php\">Manage Modules</a>";
}
}

if (($_GET['view'] == "manage") && ($_POST['delete'] == "") && ($_GET['edit'] == "")) {

	echo "<form action='a_modules.php?view=manage&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_mods WHERE id = '$i'";
	$result=mysql_query($query);
	while($r = mysql_fetch_array($result)) {

	echo "<input type=\"hidden\" name=\"id[]\" value=\"$i\"><tr><td><b><center>Module #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."' value='".$r[name]."'></td></tr><tr><td>URL (to module backend, only put in the filename and extension...ex.a_top10.php)</td><td><input type=\"text\" name='url_".$i."' value='".$r[url]."'></td></tr><tr><td>Installed?</td><td><select name='installed_".$i."'><option value='".$r[installed]."' selected>-- ".$r[installed]." --</option><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr><tr><td>Version</td><td><input type='text' name='version_".$i."' value='".$r[version]."'></td></tr><tr><td>URL to readme file</td><td><input type='text' name='readme_".$i."' value='".$r[readme]."'></td></tr><tr><td>URL (to module frontend, only put in the filename and extension...ex.top10.php)</td><td><input type='text' name='url2_".$i."' value='".$r[sql]."'></td></tr><tr><td>Module status?</td><td><select name='status_".$i."'><option value='".$r[status]."' selected>-- ".$r[status]." --</option><option value='On'>On</option><option value='Off'>Off</option></select></td></tr>";
	}
	}
	echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
}

if (($_GET['view'] == "manage") && ($_GET['edit'] == "2")) {

   while (list(, $val) = each ($_POST['id'])) {
   $upd = "UPDATE onecms_mods SET name = '".$_POST["name_$val"]."', url = '".$_POST["url_$val"]."', installed = '".$_POST["installed_$val"]."', status = '".$_POST["status_$val"]."', version = '".$_POST["version_$val"]."', readme = '".$_POST["readme_$val"]."', sql = '".$_POST["url2_$val"]."' WHERE id = '".$val."'";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
    echo "The modules has been updated. <a href=\"a_modules.php\">Manage Modules</a>";
}
}	

if ($_GET['view'] == "add") {
		echo "<form action=\"a_modules.php?view=add\" name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many modules to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='a_modules.php?view=add2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
	echo "<tr><td><b><center>Module #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>URL (to module backend, only put in the filename and extension...ex.a_top10.php)</td><td><input type=\"text\" name='url_".$i."'></td></tr><tr><td>Installed?</td><td><select name='installed_".$i."'><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr><tr><td>Version</td><td><input type='text' name='version_".$i."'></td></tr><tr><td>URL to readme file</td><td><input type='text' name='readme_".$i."'></td></tr><tr><td>URL (to module frontend, only put in the filename and extension...ex.top10.php)</td><td><input type='text' name='url2_".$i."'></td></tr><tr><td>Module status?</td><td><select name='status_".$i."'><option value='On'>On</option><option value='Off'>Off</option></select></td></tr>";
	}
	echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form>";
	}
	echo "</table>";
}

	if ($_GET['view'] == "add2") {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   $upd = "INSERT INTO onecms_mods VALUES ('null', '".$_POST["name_$i"]."', '".$_POST["url_$i"]."', '".$_POST["installed_$i"]."', '".$_POST["version_$i"]."', '".$_POST["readme_$i"]."', '".$_POST["url2_$i"]."', '".$_POST["status_$i"]."')";
$r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The module(s) have been created. <a href=\"a_modules.php\">Manage Modules</a>";
}
	}
}
}
}
}include ("a_footer.inc");
?>
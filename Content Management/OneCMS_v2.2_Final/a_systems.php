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
	echo "Sorry $username, but you do not have permission to manage systems. You are only a $level.";
} else {

echo "<center><a href='a_systems.php?view=add'>Add Systems</a> | <a href='a_systems.php'>Manage Systems</a> | <a href='a_systems.php?view=search'>Search</a></center><br><br>";

if ($_GET['view'] == "search") {

				echo "<title>OneCMS - www.insanevisions.com/onecms > Systems > Search</title>";

	echo "<form action='a_systems.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for system</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_systems.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>System Name</b></td><td><b>Abbreviation</b></td><td><b>Status</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_systems WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='index.php?id=systems&sid=".$row[abr]."' target='popup'>$name</a></td><td>$row[abr]</td><td>";
		if ($row[status]) {
		echo "Offline";
		} else {
		echo "Online";
		}
		echo "</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_systems.php?view=add\">Add system</a></td></tr></form></table><br><br>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_systems WHERE name LIKE '%" . $_POST['search'] . "%'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$next\">Next>></a>";
}
echo "</center>

    </span>
  </div></div></center>";

}

if ($_GET['view'] == "") {

				echo "<title>OneCMS - www.insanevisions.com/onecms > Manage Systems</title>";

	echo "<form action='a_systems.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for system</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_systems.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>System Name</b></td><td><b>Abbreviation</b></td><td><b>Status</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_systems ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='index.php?id=systems&sid=".$row[abr]."' target='popup'>$name</a></td><td>$row[abr]</td><td>";
		if ($row[status]) {
		echo "Offline";
		} else {
		echo "Online";
		}
		echo "</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_systems.php?view=add\">Add system</a></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_systems"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$next\">Next>></a>";
}
echo "</center>
    </span>
  </div></div></center>";

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
	$sql = mysql_fetch_row(mysql_query("SELECT name FROM onecms_systems WHERE id = '".$val."'"));
	$name = $sql[0];

	$delete2 = mysql_query("DELETE FROM onecms_content WHERE systems = '$name'") or die(mysql_error());

	$delete = mysql_query("DELETE FROM onecms_systems WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "The system(s) have been deleted. <a href=\"a_systems.php\">Manage Systems</a>";
}
}

if (($_GET['view'] == "manage") && ($_POST['delete'] == "") && ($_GET['edit'] == "")) {

	echo "<form action='a_systems.php?view=manage&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_systems WHERE id = '$val'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
		$namea = "$row2[name]";
		$abr = "$row2[abr]";
		$icon = "$row2[icon]";

	echo "<input type=\"hidden\" name=\"id[]\" value=\"$val\"><tr><td><b><center>Item #".$val."</b></center></td></tr><tr><td><b>Name</b></td><td><input type='text' name=\"name_$val\" value=\"$namea\"></td></tr><tr><td><b>Abbreviation</b></td><td><input type='text' name=\"abr_$val\" value=\"$abr\"></td></tr><tr><td><b>System Icon</b></td><td><input type='text' name='img_".$val."' value='".$icon."'></td></tr><tr><td><b>System Content Offline?</b></td><td><input type='checkbox' name='status_".$val."' value='off'";
	
	if ($row2[status]) {
	echo " checked";
	}
	echo "></td></tr><tr><td><b>Skin</b></td><td><select name='skin_".$val."'><option value=''>-------</option>";

    $sql = mysql_query("SELECT * FROM onecms_skins ORDER BY `id` DESC");
	while($r = mysql_fetch_array($sql)) {
		if ($r[id] == $row2[skin]) {
		echo "<option value='".$r[id]."' selected>-- ".$r[name]." --</option>";
		} else {
		echo "<option value='".$r[id]."'>".$r[name]."</option>";
		}
	}
	echo "</select></td></tr>";
	}
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
}

if (($_GET['view'] == "manage") && ($_GET['edit'] == "2")) {

   while (list(, $val) = each ($_POST['id'])) {
   $upd = "UPDATE onecms_systems SET name = '".$_POST["name_$val"]."', abr = '".$_POST["abr_$val"]."', icon = '".$_POST["img_$val"]."', status = '".$_POST["status_$val"]."', skin = '".$_POST["skin_$val"]."' WHERE id = '$val'";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
    echo "The systems has been updated. <a href=\"a_systems.php\">Manage Systems</a>";
}
}	

if ($_GET['view'] == "add") {
		echo "<form action=\"a_systems.php?view=add&cat=".$_GET['cat']."\" name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many systems to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='a_systems.php?view=add2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
	echo "<tr><td><b><center>System #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>Abbreviation</td><td><input type=\"text\" name='abr_".$i."'></td></tr><tr><td>System Icon</td><td><input type='text' name='img_".$i."'></td></tr><tr><td>System Content Offline?</td><td><input type='checkbox' name='status_".$i."' value='off'></td></tr><tr><td><b>Skin</b></td><td><select name='skin_".$i."'><option value=''>-------</option>";

    $sql = mysql_query("SELECT * FROM onecms_skins ORDER BY `id` DESC");
	while($r = mysql_fetch_array($sql)) {
		echo "<option value='".$r[id]."'>".$r[name]."</option>";
	}
	echo "</select></td></tr>";
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form>";
	}
	echo "</table>";
}

	if ($_GET['view'] == "add2") {

   $time = date("Ymd");
   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   $upd = "INSERT INTO onecms_systems VALUES ('null', '".$_POST["name_$i"]."', '".$_POST["abr_$i"]."', '".$_POST["img_$i"]."', '".$_POST["status_$i"]."', '".$_POST["skin_$i"]."')";
$r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The system(s) have been created. <a href=\"a_systems.php\">Manage Systems</a>";
}
	}
}
}
}
}include ("a_footer.inc");
?>
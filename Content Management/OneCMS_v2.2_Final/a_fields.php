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

if (($userlevel == "2") or ($userlevel == "3")) {
	echo "Sorry $username, but you do not have permission to manage fields. You are only a $level.";
} else {

echo "<center><a href='a_fields.php?view=add'>Add Fields</a> | <a href='a_fields.php'>Manage Fields</a> | <a href='a_fields.php?view=search'>Search</a></center><br><br>";

	echo '<SCRIPT LANGUAGE="JavaScript">
function info () {
alert ("Games - If you select the type games, then when posting content for this category, the user will be able to select a game to assign from the games database.\n\nSystems - If you select the type systems, then when posting content for this category, the user will be able to select a system to assign from the systems database.\n\nAlbums - If you select the type albums, then when posting content for this category, the user will be able to select a album to assign from the albums database. Recommended for a media or movies category.");
}
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
function info2 () {
alert ("User Profiles - If you select this `category`, then this field will be added to user profiles...allowing users to enter info for that field, viewable in there profile.");
}
</SCRIPT>';

if ($_GET['view'] == "search") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Fields > Search</title>";

	echo "<form action='a_fields.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for field</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_fields.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Category</b></td><td><b>Type</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_fields WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);

		if ($row[cat] == "") {
		$cat = "global";
		} else {
		$cat = $row[cat];
		}

    	echo "<tr><td>$name</td><td>$cat</td><td>$row[type]</td>";
		if ((($name == "games") or ($name == "systems") or ($name == "albums"))) {
		echo "<td>-</td><td>-</td></tr>";
		} else {
		echo "<td><input type=\"checkbox\" name=\"id[]\" value=\"$id\" readonly></td><td><input type=\"checkbox\" readonly name=\"delete[]\" value=\"$id\"></td></tr>";
		}
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_fields.php?view=add\">Add field</a></td></tr></form></table><br><br>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_fields WHERE name LIKE '%" . $search . "%'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_fields.php?view=search&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_fields.php?view=search&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_fields.php?view=search&page=$next\">Next>></a>";
}
echo "</center>";

}

if ($_GET['view'] == "") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Manage Fields</title>";

	echo "<form action='a_fields.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for field</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_fields.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Category</b></td><td><b>Type</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_fields ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);

		if ($row[cat] == "") {
		$cat = "global";
		} else {
		$cat = $row[cat];
		}

    	echo "<tr><td>$name</td><td>$cat</td><td>$row[type]</td>";
		if ((($name == "games") or ($name == "systems") or ($name == "albums"))) {
		echo "<td>-</td><td>-</td></tr>";
		} else {
		echo "<td><input type=\"checkbox\" name=\"id[]\" value=\"$id\" readonly></td><td><input type=\"checkbox\" readonly name=\"delete[]\" value=\"$id\"></td></tr>";
		}
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_fields.php?view=add\">Add field</a></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_fields"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_fields.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_fields.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_fields.php?page=$next\">Next>></a>";
}
echo "</center>";

}

if ((($_GET['view'] == "manage") && ($_POST['delete']) && ($_GET['view2'] == ""))) {

echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {

$sql = mysql_fetch_row(mysql_query("SELECT name,cat FROM onecms_fields WHERE id = '".$val."'"));
$name = $sql[0];
$cat = $sql[1];

if ($cat == "users") {
$delete2 = mysql_query("ALTER TABLE onecms_profile DROP `$name`") or die(mysql_error());

} else {

$delete2 = mysql_query("DELETE FROM onecms_fielddata WHERE name = '".$name."' AND cat = '".$cat."'") or die(mysql_error());
}
$delete = mysql_query("DELETE FROM onecms_fields WHERE id = '$val'") or die(mysql_error());
}
if (($delete == TRUE) && ($delete2 == TRUE)) {
echo "The field(s) has been deleted. <a href=\"a_fields.php\">Return to Manage Fields Home</a>";
}
}

if (($_GET['view'] == "manage") && ($_POST['delete'] == "") && ($_GET['edit'] == "")) {

	echo "<form action='a_fields.php?view=manage&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_fields WHERE id = '$val'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
		$namea = "$row2[name]";
		$type = "$row2[type]";
		$cat = "$row2[cat]";
		$des = "$row2[des]";

	echo "<input type=\"hidden\" name=\"id[]\" value=\"$val\">";
	echo "<tr><td><b><center>Field #".$val."</b></center></td></tr><tr><td>Name</td><td><input type=\"hidden\" name='name2_".$val."' value=\"$namea\"><input type=\"text\" name='name_".$val."' value=\"$namea\"></td></tr><tr><td>Help Info</td><td><textarea name='help_".$val."' cols='30' rows='12'>".$des."</textarea></td></tr><tr><td>Category <a href='javascript:info2()'><b>[Help]</b></a></td><td><select name='cat_".$val."'>";

		if ((!$cat == "games") or (!$cat == "users")) {
			echo "<option value='games'>Games</option><option value='users'>User Profiles</option>";
		}

		if ($cat == "") {
			echo "<option value=''>-- Global --</option>";
		} else {
			echo "<option value='".$cat."' selected>-- ".$cat." --</option>";
		}

	$query="SELECT * FROM onecms_cat";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$name = "$row[name]";
		if (!$name == $cat) {
		echo "<option value=\"$name\">$name</option>";
		}
	}

	echo "</select></td></tr><tr><td>Type <a href='javascript:info()'><b>[Help]</b></a></td><td><select name='type_".$val."'><option value=\"$type\" selected>-- $type --</option><option value='textarea'>Text Box</option><option value='textfield'>Text Field</option></select></td></tr>";
	}
	}
	echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
}

if (($_GET['view'] == "manage") && ($_GET['edit'] == "2")) {

   while (list(, $val) = each ($_POST['id'])) {
   if (((((((($_POST["name_$val"] == "id") or ($_POST["name_$val"] == "name") or ($_POST["name_$val"] == "cat") or ($_POST["name_$val"] == "username") or ($_POST["name_$val"] == "date") or ($_POST["name_$val"] == "ver") or ($_POST["name_$val"] == "postpone") or ($_POST["name_$val"] == "stats")))))))) {

   echo "Sorry, but your field cannot be named ".$_POST["name_$val"].". Please go back.";

   } else {

   $sql = mysql_query("SELECT * FROM onecms_fields WHERE name = '".$_POST["name_$val"]."'");
   $num = mysql_num_rows($sql);

   $sql2 = mysql_query("SELECT * FROM onecms_cat WHERE name = '".$_POST["name_$val"]."'");
   $num2 = mysql_num_rows($sql2);

   if (($num > "0") or ($num2 > "0")) {
	   echo "Sorry, but the field name <b>".$_POST["name_$val"]."</b> is already in use. Go back and choose another name.<br><br>";
   } else {

   $r = mysql_query("UPDATE onecms_fields SET des = '".addslashes($_POST["help_$val"])."', name = '".$_POST["name_$val"]."', type = '".$_POST["type_$val"]."', cat = '".$_POST["cat_$val"]."' WHERE id = '$val'") or die(mysql_error());

   if ($_POST["cat_$val"] == "users") {
   $r2 = mysql_query("ALTER TABLE onecms_profile CHANGE `".$_POST["name2_$val"]."` `".$_POST["name_$val"]."` TEXT DEFAULT '' NOT NULL") or die(mysql_error());
   } else {
   $r2 = mysql_query("UPDATE onecms_fielddata SET name = '".$_POST["name_$val"]."' WHERE name = '".$_POST["name2_$val"]."'") or die(mysql_error());
   }
   }
   }
   }
if (($r2 == TRUE) && ($r == TRUE)) {
    echo "The field(s) have been updated. <a href=\"a_fields.php\">Return to Fields Manager Home</a>";
}
}

if ($_GET['view'] == "add") {
		echo "<form action=\"a_fields.php?view=add\" name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many items to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='a_fields.php?view=add2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
	echo "<tr><td><b><center>Field #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>Help Info</td><td><textarea name='help_".$i."' cols='30' rows='12'></textarea></td></tr><tr><td>Category</td><td><select name='cat_".$i."'><option value=''>Global Field</option><option value='games'>Games</option><option value='users'>User Profiles</option></select><select name='cat_".$i."'>";

	$query = mysql_query("SELECT * FROM onecms_cat");
	while($r = mysql_fetch_array($query)) {
		$name = "$r[name]";
		echo "<option value=\"$name\">$name</option>";
	}

	echo "</select></td></tr><tr><td>Type <a href='javascript:info()'><b>[Help]</b></a></td><td><select name='type_".$i."'><option value='textarea'>Text Box</option><option value='textfield'>Text Field</option></select></td></tr>";
	}
	echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr>";
	}
	echo "</form></table>";
	}

if ($_GET['view'] == "add2") {

   $time = date("Ymd");
   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   if (((((((($_POST["name_$i"] == "id") or ($_POST["name_$i"] == "name") or ($_POST["name_$i"] == "cat") or ($_POST["name_$i"] == "username") or ($_POST["name_$i"] == "date") or ($_POST["name_$i"] == "ver") or ($_POST["name_$i"] == "postpone") or ($_POST["name_$i"] == "state")))))))) {

   echo "Sorry, but your field cannot be named ".$_POST["name_$i"].". Please go back.";
   } else {

   $sql = mysql_query("SELECT * FROM onecms_fields WHERE name = '".$_POST["name_$i"]."'");
   $num = mysql_num_rows($sql);

   $sql2 = mysql_query("SELECT * FROM onecms_cat WHERE name = '".$_POST["name_$i"]."'");
   $num2 = mysql_num_rows($sql2);

   if (($num > "1") or ($num2 > "1")) {
   echo "Sorry, but the field name <b>".$_POST["name_$i"]."</b> is already in use. Go back and choose another name.<br><br>";
   } else {

   $upd1 = mysql_query("INSERT INTO onecms_fields VALUES ('null', '".addslashes($_POST["name_$i"])."', '".addslashes($_POST["cat_$i"])."', '".addslashes($_POST["type_$i"])."', '".addslashes($_POST["help_$i"])."')") or die(mysql_error());

if ($_POST["cat_$i"] == "users") {
$upd2 = mysql_query("ALTER TABLE onecms_profile ADD `".$_POST["name_$i"]."` TEXT DEFAULT '' NOT NULL") or die(mysql_error());
}

   }
   }
   }
if (($upd1 == TRUE) && ($upd2 == TRUE)) {
	echo "The field(s) have been updated. <a href=\"a_fields.php\">Manage Fields</a>";
 }
}

}
}
}

}
include ("a_footer.inc");
?>
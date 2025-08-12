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

echo "<center><a href='a_games.php?view=add'>Add Games</a> | <a href='a_games.php?view=add&add=types'>Add Game Types</a> | <a href='a_games.php'>Manage Games</a> | <a href='a_games.php?view=types'>Manage Game Types</a></center><br><br>";

echo '<script language="javascript">
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script>';

$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_permissions WHERE username = '".$_COOKIE[username]."' AND games = 'yes'"));

if ($sql == "1") {

if ($_GET['view'] == "") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Game Manager</title>";

	echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><form action='a_games.php?view=manage' name='form1' method='post'><tr><td><b>Name</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	if ((($userlevel == "3") or ($userlevel == "4") or ($userlevel == "5"))) {

	$query="SELECT * FROM onecms_games WHERE username = '$username' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$gamepart1."".$row[id]."".$gamepart2."' target='popup'>$name</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }
			} else {

$query="SELECT * FROM onecms_games ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$gamepart1."".$row[id]."".$gamepart2."' target='popup'>$name</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }
			}

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_games.php?view=add\">Add Games</a></td><td><a href='a_games.php?view=add&add=types'>Add Types</a></td></tr></form></table><br><br>";


$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_games"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_games.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_games.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_games.php?page=$next\">Next>></a>";
}
echo "</center>";

}
if (($_GET['view'] == "add") && ($_GET['add'] == "")) {
        

echo "<form action=\"a_games.php?view=add\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many games to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

if ($_POST['search']) {
echo "<script language=\"JavaScript\" src=\"a_date-picker.js\"></script><form action='a_games.php?view=add2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";
echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
	echo "<tr><td><b><center>Game #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>System</td><td><select name='system_".$i."'><option value=''>-------</option>";

$sql = mysql_query("SELECT * FROM onecms_systems ORDER BY `name` ASC");
while ($r = mysql_fetch_array($sql)) {
	echo "<option value='".$r[id]."'>".$r[name]."</option>";
}
echo "</select></td></tr><tr><td>Gallery</td><td><select name='album_".$i."'><option value=''>-------</option>";

$sql = mysql_query("SELECT * FROM onecms_albums ORDER BY `name` ASC");
while ($r = mysql_fetch_array($sql)) {
	echo "<option value='".$r[id]."'>".$r[name]."</option>";
}
echo "</select></td><td>New Gallery - <input type='text' name='album2_".$i."' size='10'></td></tr><tr><td>Publisher</td><td><select name='pub_".$i."'><option value=''>-----</option>";
	$query = mysql_query("SELECT * FROM onecms_pr WHERE type = 'publisher' ORDER BY `name` ASC") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		echo "<option value=\"$row[name]\">$row[name]</option>";
	}
		echo "</select></td></tr>";

		echo "<tr><td>Developer</td><td><select name='dev_".$i."'><option value=''>-----</option>";
	$query = mysql_query("SELECT * FROM onecms_pr WHERE type = 'developer' ORDER BY `name` ASC") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		echo "<option value=\"$row[name]\">$row[name]</option>";
	}
		echo "</select></td></tr>";

		echo "<tr><td>Genre</td><td><select name='genre_".$i."'>";
	$query = mysql_query("SELECT * FROM onecms_game WHERE type = 'genre'") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		echo "<option value=\"$row[name]\">$row[name]</option>";
	}
		echo "</select></td></tr>";

		echo "<tr><td>ESRB Rating</td><td><select name='esrb_".$i."'><option value=''>-----</option>";
	$query = mysql_query("SELECT * FROM onecms_game WHERE type = 'esrb'") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		echo "<option value=\"$row[name]\">$row[name]</option>";
	}
		echo "</select></td></tr>";

		echo "<tr><td>Boxart</td><td><select name='boxart_".$i."' multiple size='5'><option value=''>-------</option>";
	$query = mysql_query("SELECT * FROM onecms_images WHERE type = 'boxart' ORDER BY `name` ASC") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		echo "<option value=\"$row[name]\">$row[name]</option>";
	}
		echo "</select></td></tr>";

echo "<tr><td>Description</td><td><textarea name='des_".$i."' cols='30' rows='10'></textarea></td></tr>";

		$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'games'") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		$name = "$row[name]";
		$type = "$row[type]";

		if ($type == "textarea") {
			echo "<tr><td>$name</td><td><textarea name='$name".$i."' cols=\"40\" rows=\"16\"></textarea></td></tr>";
		}
		if ($type == "textfield") {
			echo "<tr><td>$name</td><td><input type=\"text\" name='$name".$i."'></td></tr>";
		}
	}
	echo "<tr><td>Release Date</td>";
    echo "<td><input type=\"hidden\" name='release_".$i."'><a href=\"javascript:show_calendar('form1.release_".$i."');\" onmouseover=\"window.status='This pops up a calendar where you can choose the games release date...';return true;\" onmouseout=\"window.status='';return true;\">Calendar</a> or <select name='rel_".$i."'><option value=''>-------</option><option value='Quarter 1'>Quarter 2</option><option value='Quarter 2'>Quarter 2</option><option value='Quarter 3'>Quarter 3</option><option value='Quarter 4'>Quarter 4</option><option value='Spring'>Spring</option><option value='Summer'>Summer<option value='Fall'>Fall</option></option><option value='Winter'>Winter<option value='TBA'>TBA</option></option><option value='TBD'>TBD</option></select> (don't choose both)</td></tr><tr><td>Skin</td><td><select name='skin_".$i."'><option value=''>-------</option>";

    $sql = mysql_query("SELECT * FROM onecms_skins ORDER BY `id` DESC");
	while($r = mysql_fetch_array($sql)) {
		echo "<option value='".$r[id]."'>".$r[name]."</option>";
	}
	echo "</select></td></tr>";
	}
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
}

if (($_GET['view'] == "add") && ($_GET['add'] == "types")) {
	$sql = mysql_query("SELECT * FROM onecms_permissions WHERE username = '$username' AND games = 'yes'");
$num = mysql_num_rows($sql);

if ($num == "0") {
	echo "Sorry, but you do not have permission to add games.";
} else {
        

		echo "<form action=\"a_games.php?view=add&add=types\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many types to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='a_games.php?view=add2&add=types' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
	echo "<tr><td><b><center>Type #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>Type</td><td><select name='type_".$i."'>";
	echo "<option value='genre'>Genre</option><option value='esrb'>ESRB Rating</option></select></td></tr>";
	}
	echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr>";
	}
	echo "</form></table>";
}
}
if (($_GET['view'] == "add2") && ($_GET['add'] == "types")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   if ($_POST["name_$i"]) {
   $upd = "INSERT INTO onecms_game VALUES ('null', '".$_POST["name_$i"]."', '".$_POST["type_$i"]."')";
   $r = mysql_query($upd) or die(mysql_error());
   } else {
   echo "".$i." - no name entered<br>";
   }
   }
if ($r == TRUE) {
	echo "The type(s) have been created. <a href=\"a_games.php?view=types\">Return to Manage Types Home</a>";
}
}

	if (($_GET['view'] == "add2") && ($_GET['add'] == "")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   if ($_POST["name_$i"] == "") {
   echo "".$i." - no game name entered<br>";
   } else {
   if (($_POST["album_$i"]) && ($_POST["album2_$i"])) {
   echo "You selected an album <b>and</b> added a gallery, please go back and do one of them";
   } else {
   $_POST["des_$i"] = addslashes($_POST["des_$i"]);

   $edit2 = "INSERT INTO onecms_games VALUES ('null', '".$_POST["name_$i"]."', '0', '".$_COOKIE['username']."', '".$_POST["pub_$i"]."', '".$_POST["dev_$i"]."', '".$_POST["genre_$i"]."'";

   if ($_POST["rel_$i"]) {
   $edit2 .= ", '".$_POST["rel_$i"]."'";
   } else {
   $edit2 .= ", '".$_POST["release_$i"]."'";
   }
   
   $edit2 .= ", '".$_POST["esrb_$i"]."', '".$_POST["boxart_$i"]."', '".$_POST["des_$i"]."', '".$_POST["skin_$i"]."', '".$_POST["system_$i"]."'";

   if (($_POST["album_$i"] == "") && ($_POST["album2_$i"] == "")) {
   $edit2 .= ", ''";
   }
   
   if (($_POST["album_$i"]) && ($_POST["album2_$i"] == "")) {
   $edit2 .= ", '".$_POST["album_$i"]."'";
   }

   if (($_POST["album_$i"] == "") && ($_POST["album2_$i"])) {
   $galleryadd = mysql_query("INSERT INTO onecms_albums VALUES ('null', '".$_POST["album2_$i"]."')");

   $gfetch = mysql_fetch_row(mysql_query("SELECT id FROM onecms_albums WHERE name = '".$_POST["album2_$i"]."'"));

   $edit2 .= ", '".$gfetch[0]."'";
   }
$edit2 .= ")";
$r = mysql_query($edit2) or die(mysql_error());

$fetch = mysql_fetch_row(mysql_query("SELECT id FROM onecms_games WHERE name = '".$_POST["name_$i"]."' AND username = '".$_COOKIE['username']."' AND publisher = '".$_POST["pub_$i"]."' AND developer = '".$_POST["dev_$i"]."' AND genre = '".$_POST["genre_$i"]."' ORDER BY `id` DESC"));

$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'games' ORDER BY `id` DESC") or die(mysql_error());
while($row = mysql_fetch_array($query)) {
$name = "$row[name]";

if ($_POST["$name$i"]) {
mysql_query("INSERT INTO onecms_fielddata VALUES ('null', '".$name."', '".addslashes($_POST["$name$i"])."', '".$fetch[0]."', 'games')") or die(mysql_error());
}
}

}
}
}
if ($r == TRUE) {
	echo "The game(s) have been created. <a href=\"a_games.php\">Manage Games</a>";
}
}

if ((($_GET['view'] == "manage") && ($_POST['delete'] == "") && ($_GET['edit'] == ""))) {

echo "<form action='a_games.php?view=manage&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_games WHERE id = '$i'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
		$name = "$row2[name]";
		$dev = "$row2[developer]";
		$pub = "$row2[publisher]";
		$genre = "$row2[genre]";
		$esrb = "$row2[esrb]";
		$box = "$row2[boxart]";
		$des2 = "$row2[des]";
		$des = stripslashes($des2);
		$system = "$row2[system]";
		$album = "$row2[album]";

		$t1 = mysql_fetch_row(mysql_query("SELECT name FROM onecms_albums WHERE id = '".$album."'"));
		$t2 = mysql_fetch_row(mysql_query("SELECT name FROM onecms_systems WHERE id = '".$system."'"));

	echo "<input type='hidden' name='id[]' value='".$i."'><tr><td><b><center>Game #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."' value='".$name."'></td></tr><tr><td>System</td><td><select name='system_".$i."'><option value='".$system."' selected>-- ".$t2[0]." --</option><option value=''>-------</option>";

$sql = mysql_query("SELECT * FROM onecms_systems ORDER BY `name` ASC");
while ($r = mysql_fetch_array($sql)) {
	echo "<option value='".$r[id]."'>".$r[name]."</option>";
}
echo "</select></td></tr><tr><td>Gallery</td><td><select name='album_".$i."'><option value=''>-------</option><option value='".$album."' selected>-- ".$t1[0]." --</option>";

$sql = mysql_query("SELECT * FROM onecms_albums ORDER BY `id` DESC");
while ($r = mysql_fetch_array($sql)) {
	echo "<option value='".$r[id]."'>".$r[name]."</option>";
}
echo "</select></td></tr><tr><td>Publisher</td><td><select name='pub_".$i."'><option value='".$pub."' selected>-- ".$pub." --</option>";
	$query = mysql_query("SELECT * FROM onecms_pr WHERE type = 'publisher' ORDER BY `name` ASC") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		echo "<option value=\"$row[name]\">$row[name]</option>";
	}
		echo "</select></td></tr>";

		echo "<tr><td>Developer</td><td><select name='dev_".$i."'><option value='".$dev."' selected>-- ".$dev." --</option>";
	$query = mysql_query("SELECT * FROM onecms_pr WHERE type = 'developer' ORDER BY `name` ASC") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		echo "<option value=\"$row[name]\">$row[name]</option>";
		}
		echo "</select></td></tr>";

		echo "<tr><td>Genre</td><td><select name='genre_".$i."'><option value='".$genre."' selected>-- ".$genre." --</option>";
	$query = mysql_query("SELECT * FROM onecms_game WHERE type = 'genre'") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		echo "<option value=\"$row[name]\">$row[name]</option>";
	}
		echo "</select></td></tr>";

		echo "<tr><td>ESRB Rating</td><td><select name='esrb_".$i."'><option value='".$esrb."' selected>-- ".$esrb." --</option>";
	$query = mysql_query("SELECT * FROM onecms_game WHERE type = 'esrb'") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		echo "<option value=\"$row[name]\">$row[name]</option>";
	}
		echo "</select></td></tr>";

		echo "<tr><td>Boxart</td><td><select name='boxart_".$i."' multiple size='5'><option value=''>-------</option><option value='".$box."' selected>-- ".$box." --</option>";
	$query = mysql_query("SELECT * FROM onecms_images WHERE type = 'boxart' ORDER BY `name` ASC") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		echo "<option value=\"$row[name]\">$row[name]</option>";
	}
		echo "</select></td></tr>";
echo "<tr><td>Description</td><td><textarea name='des_".$i."' cols='30' rows='10'>".$des."</textarea></td></tr>";

		$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'games'") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		$name = "$row[name]";
		$type = "$row[type]";

		$dataa = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE name = '".$name."' AND id2 = '".$row2[id]."' AND cat = 'games'"));
		$data = stripslashes($dataa[0]);

		if ($type == "textarea") {
			echo "<tr><td>$name</td><td><textarea name='$name".$i."' cols=\"40\" rows=\"16\">".$data."</textarea></td></tr>";
		}
		if ($type == "textfield") {
			echo "<tr><td>$name</td><td><input type=\"text\" name='$name".$i."' value=\"$data\"></td></tr>";
		}
	}
	echo "<script language=\"JavaScript\" src=\"a_date-picker.js\"></script><tr><td>Release Date</td>";
	echo "<td><input type=\"hidden\" name='release_".$i."'><a href=\"javascript:show_calendar('form1.release_".$i."');\" onmouseover=\"window.status='This pops up a calendar where you can choose the games release date...';return true;\" onmouseout=\"window.status='';return true;\">Calendar</a> or <select name='rel_".$i."'><option value=''>-------</option><option value='Quarter 1'>Quarter 2</option><option value='Quarter 2'>Quarter 2</option><option value='Quarter 3'>Quarter 3</option><option value='Quarter 4'>Quarter 4</option><option value='Spring'>Spring</option><option value='Summer'>Summer<option value='Fall'>Fall</option></option><option value='Winter'>Winter<option value='TBA'>TBA</option></option><option value='TBD'>TBD</option></select> (don't choose both)</td></tr><tr><td>Select this if your are keeping the same date</td><td><input type='checkbox' name='check_".$val."' value='yep' checked></tr><tr><tr><td>Skin</td><td><select name='skin_".$i."'><option value=''>-------</option>";

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
			echo "<tr><td><input type=\"submit\" name=\"Modify\" value=\"Modify\"></td></tr></form></table>";

	}

if (($_GET['view'] == "manage") && ($_GET['edit'] == "2")) {

   while (list(, $i) = each ($_POST['id'])) {
   $_POST["des_$i"] = addslashes($_POST["des_$i"]);

   $edit2 = "UPDATE onecms_games SET name = '".$_POST["name_$i"]."', username = '$username', publisher = '".$_POST["pub_$i"]."', developer = '".$_POST["dev_$i"]."', genre = '".$_POST["genre_$i"]."'";
   
   if ($_POST["check_$i"]) {
   if ($_POST["rel_$i"]) {
   $edit2 .= ", release = '".$_POST["rel_$i"]."'";
   } else {
   $edit2 .= ", release = '".$_POST["release_$i"]."'";
   }
   }
   
   $edit2 .= ", esrb = '".$_POST["esrb_$i"]."', boxart = '".$_POST["boxart_$i"]."', des = '".$_POST["des_$i"]."', skin = '".$_POST["skin_$i"]."', album = '".$_POST["album_$i"]."', system = '".$_POST["system_$i"]."' WHERE id = '".$i."'";

   $r = mysql_query($edit2) or die(mysql_error());

$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'games' ORDER BY `id` DESC") or die(mysql_error());
while($row = mysql_fetch_array($query)) {
$name = "$row[name]";

if ($_POST["$name$i"]) {
mysql_query("UPDATE onecms_fielddata SET data = '".addslashes($_POST["$name$i"])."' WHERE id2 = '".$i."' AND cat = 'games' AND name = '".$name."'") or die(mysql_error());
}
}
   }
if ($r == TRUE) {
	echo "The game(s) have been updated. <a href=\"a_games.php\">Return to Manage Games Home</a>";
}
}

if ($_GET['view'] == "types") {

			echo "<title>OneCMS - www.insanevisions.com/onecms > Game Type Manager</title>";

			echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><form action='a_games.php?view=types1' name='form1' method='post'><tr><td><b>Name</b></td><td><b>Type</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	if ((($userlevel == "3") or ($userlevel == "4") or ($userlevel == "5"))) {

	echo "Hey, you can't edit types!";

	} else {

$query="SELECT * FROM onecms_game ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$type = "$row[type]";
		$name = stripslashes($name2);
    	echo "<tr><td>$name</td><td>$type</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }
			}

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_games.php?view=add\">Add Games</a></td><td><a href='a_games.php?view=add&add=types'>Add Types</a></td></tr></form></table><br><br>";


$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_game"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_games.php?view=types&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_games.php?view=types&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_games.php?view=types&page=$next\">Next>></a>";
}
echo "</center>";

}

	if ((($_GET['view'] == "types1") && ($_POST['delete'] == "") && ($_GET['edit'] == ""))) {

	echo "<form action='a_games.php?view=types1&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_game WHERE id = '$i'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
		$name = "$row2[name]";
		$type = "$row2[type]";

		echo "<input type='hidden' name='id[]' value='".$i."'><tr><td><center><b>Type #".$i."</b></center></td></tr><tr><td>Name</td><td><input type='text' name='name_".$i."' value=\"$name\"></td></tr><tr><td>Type</td><td><select name='type_".$i."'><option value=\"$type\" selected>-- $type --</option><option value='esrb'>ESRB Rating</option><option value='genre'>Genre</option></select></td></tr>";
	}
	}
		echo "<tr><td><input type=\"submit\" name=\"Modify\" value=\"Modify\"></td></tr></form></table>";
	}

	if (($_GET['view'] == "types1") && ($_GET['edit'] == "2")) {

   while (list(, $i) = each ($_POST['id'])) {
   $upd = "UPDATE onecms_game SET name = '".$_POST["name_$i"]."', type = '".$_POST["type_$i"]."' WHERE id = '".$i."'";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The type(s) have been updated. <a href=\"a_games.php?view=types\">Return to Manage Types Home</a>";
}
}

if (($_GET['view'] == "types1") && ($_POST['id'] == "")) {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {
$delete = mysql_query("DELETE FROM onecms_game WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
echo "The game type(s) have been deleted. <a href=\"a_games.php?view=types\">Return to Manage Types Home</a>";
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
$delete = mysql_query("DELETE FROM onecms_games WHERE id = '$val'") or die(mysql_error());
$delete2 = mysql_query("DELETE FROM onecms_fielddata WHERE id = '$val' AND cat = 'games'") or die(mysql_error());
}
if (($delete == TRUE) && ($delete2 == TRUE)) {
	echo "The game(s) have been deleted. <a href=\"a_games.php\">Return to Manage Games Home</a>";
}
}

} else {
	echo "Sorry, but you do not have permission to this page.";
}

}
}
}
include ("a_footer.inc");
?>
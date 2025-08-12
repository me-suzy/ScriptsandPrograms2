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
	echo "Sorry ".$_COOKIE[username].", but you do not have permission to manage users. You are only a $level.";
} else {

if ($_GET['view'] == "search") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Users > Search</title>";

	echo "<form action='a_users.php?view=search' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for user</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_users.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Username</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_users WHERE username LIKE '%" . $search . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$Username2 = "$row[username]";
		$Username = stripslashes($Username2);
    	echo "<tr><td><a href='elite.php?user=".$row[id]."' target='popup'>$Username</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_users.php?view=add\">Add user</a></td><td><a href='a_users.php?view=manage&change=1'>Change Passwords</a></td></tr></form></table><br><br>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_users WHERE username LIKE '%" . $search . "%'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_users.php?view=search&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_users.php?view=search&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_users.php?view=search&page=$next\">Next>></a>";
}
echo "</center>";

}

if ($_GET['view'] == "") {

				echo "<title>OneCMS - www.insanevisions.com/onecms > Manage Users</title>";

	echo "<form action='a_users.php?view=search' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for user</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_users.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Username</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_users ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$Username2 = "$row[username]";
		$Username = stripslashes($Username2);
    	echo "<tr><td><a href='elite.php?user=".$row[id]."' target='popup'>$Username</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_users.php?view=add\">Add user</a></td><td><a href='a_users.php?view=manage&change=1'>Change Passwords</a></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_users"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_users.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_users.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_users.php?page=$next\">Next>></a>";
}
echo "</center>";

}

if (($_GET['view'] == "manage") && ($_POST['delete']) && ($_GET['confirm'] == "")) {

		echo "<form action='a_users.php?view=manage&confirm=yes' method='post'>Are you sure you want to delete these user(s)?<br><input type='submit' name='de' value='Yes'>";

while (list(, $value) = each ($_POST['delete'])) {
	echo "<input type=\"hidden\" name=\"delete[]\" value=\"$value\">";
}

echo "</form>";
}

if ((($_GET['view'] == "manage") && ($_POST['id']) && ($_GET['update'] == ""))) {

	echo "<form action='a_users.php?view=manage&update=yes' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

	while (list(, $val) = each ($_POST['id'])) {

	$query="SELECT * FROM onecms_users WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

    echo "<input type=\"hidden\" name=\"id[]\" value=\"$val\"><tr><td><b><center>User #".$val."</b></center></td></tr><tr><td>Username</td><td><input type=\"text\" name=\"name_$val\" value=\"$row[username]\"><input type=\"hidden\" name=\"name2_$val\" value=\"$row[username]\"></td></tr><tr><td>Email</td><td><input type=\"text\" name=\"email_$val\" value=\"$row[email]\"></td></tr><tr><td>User Level</td><td><select name=\"level_$val\" multiple><option value=\"$row[level]\" selected>-- $row[level] --</option>";
	}

    $query2="SELECT * FROM onecms_userlevels";
	$result2=mysql_query($query2);
	while($row2 = mysql_fetch_array($result2)) {
	echo "<option value=\"$row2[name]\">$row2[name]</option>";
	}
	echo "</select></td></tr><tr><td>List this user on <a href='".$siteurl."/staff.php'>staff</a> page?</td><td><input type='checkbox' name='slist_".$val."' value='Yes'";

$find = mysql_query("SELECT slist FROM onecms_users WHERE id = '".$val."'");
$fetch = mysql_fetch_row($find);

if ($fetch[0] == "Yes") {
echo "checked";
}
echo "></td></tr>";
	}
	echo "<tr><td><input type='submit' name='editcon' value='Submit'></td></tr></table></form>";
}

if (($_GET['view'] == "manage") && ($_GET['change'] == "1")) {
		echo "<form action='a_users.php?view=manage&change=2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Username</td><td><select name=\"b\">";
			$query="SELECT * FROM onecms_users";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		echo "<option value=\"$row[username]\">$row[username]</option>";
	}

echo "</select></td></tr><tr><td>New Password</td><td><input type=\"password\" name=\"pass\"></td></tr><tr><td>Sent this user a PM with there new password?</td><td><input type='checkbox' name='send'></td></tr><tr><td><input type='submit' name='editcon' value='Submit'></td></tr></table></form>";
}

if (($_GET['view'] == "manage") && ($_GET['change'] == "2")) {
$pass = md5($_POST['pass']);
$upd = "UPDATE onecms_users SET password = '$pass' WHERE username = '".$_POST['b']."'";
mysql_query($upd) or die(mysql_error());
if ($_POST['send'] == "") {
} else {
	$date = date("F j, Y");
	$sent = mysql_query("INSERT INTO onecms_pm VALUES ('null', '1', 'You have a new password, ".$_POST['b']."', '$username has changed your password. You can find the new password below:<br><br>".$_POST['pass']."<br><br>Please keep this password in your records. Thank you.', '$username', '".$_POST['b']."', '$date')") or die(mysql_error());
}
if ($upd == TRUE) {
    echo "The user".$_POST['b']." now has a new password. <a href=\"a_users.php\">Return to User Manager Home</a>";
}
}

if (($_GET['view'] == "manage") && ($_GET['update'] == "yes")) {

while (list(, $val) = each ($_POST['id'])) {
   $sql = mysql_query("SELECT * FROM onecms_users WHERE username = '".$_POST["name_$val"]."'");
   $num = mysql_num_rows($sql);

   if ($num > "1") {
	   echo "Sorry, but the username <b>".$_POST["name_$val"]."</b> is already in use. Go back and choose another name.<br><br>";
   } else {

   $upd = "UPDATE onecms_users SET username = '".$_POST["name_$val"]."', email = '".$_POST["email_$val"]."', level = '".$_POST["level_$val"]."'";
   
   if ($_POST["slist_$val"]) {
   $upd .= ", slist = 'Yes'";
   }
   
   $upd .= "WHERE id = '$val'";
   $r = mysql_query($upd) or die(mysql_error());
   $upd2 = mysql_query("UPDATE onecms_permissions SET username = '".$_POST["name_$val"]."' WHERE username = '".$_POST["name2_$val"]."'") or die(mysql_error());
   }
if (($upd2 == TRUE) && ($r == TRUE)) {
    echo "The users have been updated. <a href=\"a_users.php\">Return to User Manager Home</a>";
}
}
}

	
if (($_GET['view'] == "manage") && ($_GET['confirm'] == "yes")) {

while (list(, $val) = each ($_POST['delete'])) {

	$sql = mysql_fetch_row(mysql_query("SELECT username FROM onecms_users WHERE id = '$val'"));

	$delete2 = mysql_query("DELETE FROM onecms_permissions WHERE username = '".$sql[0]."'") or die(mysql_error());

	$delete3 = mysql_query("DELETE FROM onecms_profile WHERE username = '".$sql[0]."'") or die(mysql_error());

	$delete = mysql_query("DELETE FROM onecms_users WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "The user(s) have been deleted. <a href=\"a_users.php\">Return to Manage user Home</a>";
}
}

if (((($_GET['view'] == "levels") && ($_GET['add'] == "") && ($_GET['edit'] == "") && ($_GET['delete'] == "")))) {

		echo "<form action='a_users.php?view=levels&delete=1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Level</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

	$query="SELECT * FROM onecms_userlevels ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$level = "$row[level]";
		$name = stripslashes($name2);
    	echo "<tr><td>$name</td><td>$level</td><td><a href='a_users.php?view=levels&edit=1&id=$id'>Edit</a></td><td><input type=\"checkbox\" name=\"del[]\" value=\"$id\"></td></tr>";
    }
		echo "<tr><td><input type=\"submit\" name=\"delete\" value=\"Delete Levels\"></td><td><a href='a_users.php?view=levels&add=1'>Add Levels</a></td></tr></form></table>";
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_userlevels"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_users.php?view=levels&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_users.php?view=levels&page=$i\">$i</a>&nbsp;";
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_users.php?view=levels&page=$next\">Next>></a>";
}
echo "</center>";

}
 if (($_GET['view'] == "levels") && ($_GET['delete'] == "1")) {

echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['del'])) {
	$delete = mysql_query("DELETE FROM onecms_userlevels WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "The userlevel(s) have been deleted. <a href=\"a_users.php?view=levels\">Return to User Levels Home</a>";
}
}


	if (($_GET['view'] == "levels") && ($_GET['edit'] == "1")) {

		echo "<form action='a_users.php?view=levels&edit=2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

	$query="SELECT * FROM onecms_userlevels WHERE id = '".intval($_GET['id'])."'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	$title2 = "$row[name]";
	$level = "$row[level]";
	echo "<tr><td>Title</td><td><input type=\"text\" name=\"name\" value=\"$title2\"><input type=\"hidden\" name=\"name2\" value=\"$title2\"></td></tr><tr><td>Level</td><td><select name='level'><option value='$level'>-- $level --</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option></select><input type='hidden' name='id' value='".intval($_GET['id'])."'></td></tr>";
	}

	echo "<tr><td><input type=\"submit\" name=\"edit\" value=\"Submit Changes\"></td></tr></form></table>";
}

if (($_GET['view'] == "levels") && ($_GET['edit'] == "2")) {
	$upd = "UPDATE onecms_userlevels SET name = '".$_POST["name"]."', level = '".$_POST["level"]."' WHERE id = '".$_POST['id']."'";

	$t = mysql_query("UPDATE onecms_users SET level = '".$_POST["name"]."' WHERE level = '".$_POST['name2']."'") or die(mysql_error());
	$b = mysql_query($upd) or die(mysql_error());
	if (($b == TRUE) && ($t == TRUE)) {
		echo "User Levels have been update. <a href='a_users.php?view=levels'>Return back to User Levels Management</a>";
	}
}

if (($_GET['view'] == "levels") && ($_GET['add'] == "1")) {

		echo "<form action=\"a_users.php?view=levels&add=1\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many levels to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='a_users.php?view=levels&add=2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {
    for($i = 0; $i < $_POST['search']; $i = $i+1)
	echo "<tr><td>Name</td><td><input type=\"text\" name='name_".$i."'></td><td>Level</td><td><select name='level_".$i."'><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option></select></td></tr>";
	}
	echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
	}

if (($_GET['view'] == "levels") && ($_GET['add'] == "2")) {
   $time = date("Ymd");
   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   $upd = "INSERT INTO onecms_userlevels VALUES ('null', '".$_POST["name_$i"]."', '".$_POST["level_$i"]."')";
   mysql_query($upd);
   }
if ($upd == TRUE) {
	echo "The user levels have been created. <a href=\"a_users.php?view=levels\">Return to User Levels Manage</a>";
}
}
if (((($_GET['view'] == "permissions") && ($_GET['add'] == "") && ($_GET['edit'] == "") && ($_GET['delete'] == "")))) {

		echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Username</b></td><td><b><b>Edit</b></td></tr>";

	$query="SELECT * FROM onecms_permissions ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id2 = mysql_fetch_row(mysql_query("SELECT id FROM onecms_users WHERE username = '".$row[username]."'"));
		$id = "$row[id]";
		$name2 = "$row[username]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='elite.php?user=".$id2[0]."' target='popup'>$name</a></td><td><input type=checkbox onclick=\"window.location='a_users.php?view=permissions&edit=1&id=$id'; return true;\"></td></tr>";
    }
		echo "</form></table>";
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_permissions"),0);

$total_pages = ceil($total_results / $max_results);

echo "<br><center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_users.php?view=permissions&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_users.php?view=permissions&page=$i\">$i</a>&nbsp;";
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_users.php?view=permissions&page=$next\">Next>></a>";
}
echo "</center>";

}

if (($_GET['view'] == "permissions") && ($_GET['edit'] == "1")) {

	echo "<form action='a_users.php?view=permissions&edit=2' method='post'><table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";

	$query="SELECT * FROM onecms_permissions WHERE id = '".intval($_GET['id'])."'";
	$result=mysql_query($query);
	while($r = mysql_fetch_array($result)) {

	echo "<input type=\"hidden\" name=\"id\" value=\"".intval($_GET['id'])."\"><input type=\"hidden\" name=\"username\" value=\"$r[username]\"><tr><td><b>Username</b></td><td>$r[username]</td></tr><tr><td><b>Force Validation?</b></td><td><input type='checkbox' name='ver' value='yes'";
		
		if ($r[ver] == "yes") {
		echo " checked";
		}
		echo "></td></tr><tr><td><b>Games</b></td><td><input type='checkbox' name='games' value='yes'";
		
		if ($r[games] == "yes") {
		echo " checked";
		}
		echo "></td></tr>";

	$query="SELECT * FROM onecms_cat";
	$result=mysql_query($query);
	while($z = mysql_fetch_array($result)) {
		$name = "$z[name]"; 

		echo "<tr><td><b>$z[name]</b></td><td><input type='checkbox' name='".$name."' value='yes'";
		
		if ($r["$name"] == "yes") {
		echo " checked";
		}
		echo "></td></tr>";
	}
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
}

if (($_GET['view'] == "permissions") && ($_GET['edit'] == "2")) {

   $upd = "UPDATE `onecms_permissions` SET ver = '".$_POST['ver']."', games = '".$_POST['games']."'";
   $result = mysql_query("SELECT * FROM `onecms_cat`");
   $count = 0;
   while ($row = mysql_fetch_array($result)) {
   	$name = $row['name'];
   		$upd .= ", " . $name . " = '" . $_POST["$name"] . "'"; // Part 2
   	}
   $upd .= " WHERE id = '" . $_POST['id'] . "'";
   $r = mysql_query($upd) or die(mysql_error());
if ($r == TRUE) {
    echo "The user permissions have been updated. <a href=\"a_users.php?view=permissions\">Return to User Permissons Manage</a>";
}
}

if (($_GET['view'] == "ban") && ($_GET['edit'] == "")) {

		echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Username</b></td><td><b>Site Ban?</b></td><td><b>CP Ban?</b></td><td><b><b>Edit</b></td></tr>";

	$query="SELECT * FROM onecms_users ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[username]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='elite.php?user=".$row[id]."' target='popup'>$name</a></td><td>$row[bansite]</td><td>$row[banadmin]</td><td><a href='a_users.php?view=ban&edit=1&id=$id'>Edit</a></td></tr>";
    }
		echo "</form></table>";
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_users"),0);

$total_pages = ceil($total_results / $max_results);

echo "<br><center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_users.php?view=ban&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_users.php?view=ban&page=$i\">$i</a>&nbsp;";
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_users.php?view=ban&page=$next\">Next>></a>";
}
echo "</center>";

}

if (($_GET['view'] == "ban") && ($_GET['edit'] == "1")) {

	echo "<form action='a_users.php?view=ban&edit=2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

	$query="SELECT * FROM onecms_users WHERE id = '".intval($_GET['id'])."'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
		$username = "$row2[username]";

	echo "<input type=\"hidden\" name=\"id\" value=\"".intval($_GET['id'])."\"><tr><td><b>Username</b></td><td><a href='elite.php?user=".$row[id]."' target='popup'>$username</a></td></tr><tr><td><b>Ban From Site?</b></td><td><select name=\"bansite\" multiple><option value=\"".$row2["bansite"]."\" selected>-- ".$row2["bansite"]." --</option><option value='yes'>Yes</option><option value='no'>No</option></select></td></tr>";
	echo "<tr><td><b>Ban From AdminCP?</b></td><td><select name=\"banadmin\" multiple><option value=\"".$row2["banadmin"]."\" selected>-- ".$row2["banadmin"]." --</option><option value='yes'>Yes</option><option value='no'>No</option></select></td></tr>";
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
}

if (($_GET['view'] == "ban") && ($_GET['edit'] == "2")) {

   $upd = "UPDATE onecms_users SET bansite = '".$_POST['bansite']."', banadmin = '".$_POST['banadmin']."' WHERE id = '" . $_POST['id'] . "'";
   $r = mysql_query($upd) or die(mysql_error());
if ($r == TRUE) {
    echo "The user ban have been updated. <a href=\"a_users.php?view=ban\">Return to Ban Control Manage</a>";
}
}
if (($_GET['view'] == "warn") && ($_GET['edit'] == "")) {

		echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Username</b></td><td><b>Warning Points</b></td><td><b><b>Edit</b></td></tr>";

	$query="SELECT * FROM onecms_users ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[username]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='elite.php?user=".$row[id]."' target='popup'>$name</a></td><td>$row[warn]</td><td><a href='a_users.php?view=warn&edit=1&id=$id'>Edit</a></td></tr>";
    }
		echo "</form></table>";
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_users"),0);

$total_pages = ceil($total_results / $max_results);

echo "<br><center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_users.php?view=warn&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_users.php?view=warn&page=$i\">$i</a>&nbsp;";
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_users.php?view=warn&page=$next\">Next>></a>";
}
echo "</center>";

}

if (($_GET['view'] == "warn") && ($_GET['edit'] == "1")) {

	echo "<form action='a_users.php?view=warn&edit=2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

	$query="SELECT * FROM onecms_users WHERE id = '".intval($_GET['id'])."'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
		$username = "$row2[username]";
        $barg = "$row2[warn]";

	echo "<input type=\"hidden\" name=\"id\" value=\"".intval($_GET['id'])."\"><tr><td><b>Username</b></td><td><a href='elite.php?user=".$row[id]."' target='popup'>$username</a></td></tr><tr><td><b>Warn Points (limit is ".$warn.")</b></td><td><select name=\"warnp\" multiple><option value=\"$barg\" selected>-- ".$barg." --</option>";
	for ($i = 0; $i <= $warn; $i++) {
    echo "<option value=\"$i\">$i</option>";
	}
	echo "</select></td></tr>";
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
}

if (($_GET['view'] == "warn") && ($_GET['edit'] == "2")) {

   if ($_POST['warnp'] == $warn) {
	   echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("You do realize setting his warn level to this will ban him, correct?\n Press ok to continue.");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';
   } else {

   $upd = "UPDATE onecms_users SET warn = '".$_POST['warnp']."' WHERE id = '" . $_POST['id'] . "'";
   $r = mysql_query($upd) or die(mysql_error());
if ($r == TRUE) {
    echo "The user warning points have been updated. <a href=\"a_users.php?view=warn\">Return to Warn Control Manager</a>";
	}
   }
}
if ($_GET['view'] == "add") {

		echo "<form action='a_users.php?view=add2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

	echo "<tr><td>Username</td><td><input type=\"text\" name='name'></td></tr><tr><td>Password</td><td><input type=\"password\" name='password1'></td></tr><tr><td>E-Mail</td><td><input type=\"text\" name='email'></td></tr><tr><td>User Level</td><td><select name='level' multiple size='5'>";
	
	$query="SELECT * FROM onecms_userlevels";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		echo "<option value=\"$row[name]\">$row[name]</option>";
	}
		echo "</td></tr><tr><td>List this user on <a href='".$siteurl."/staff.php'>staff</a> page?</td><td><input type='checkbox' name='slist' value='Yes' checked></td></tr>";
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add User\"></td></tr></form></table>";
	}

   if ($_GET['view'] == "add2") {
   
   if (((checkemail($_POST['email']) == TRUE) && ($_POST['name']) && ($_POST['password1']))) {
   $sql = mysql_query("SELECT * FROM onecms_users WHERE username = '".$_POST["name"]."'");
   $num = mysql_num_rows($sql);

   $user = strip_tags(stripcslashes($_POST["name"]));

   if ($num > "0") {
	   echo "Sorry, but the username <b>".$user."</b> is already in use. Go back and choose another name.<br><br>";
   } else {

   $register3b = "INSERT INTO onecms_profile VALUES ('null', '".$user."', '', '', '', '', '', '', ''";
   
   $resulte = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'users' ORDER BY `id` DESC");
   while ($rowe = mysql_fetch_array($resulte)) {
	   $register3b .= ", ''";
   }
   
   $register3b .= ")";
   
   $register3 = mysql_query($register3b) or die(mysql_error());

   $upd4 = mysql_query("INSERT INTO `onecms_pm` VALUES ('null', '1', 'Welcome ".$user."!', 'Welcome to OneCMS ".$user.". Below you can find your user information, please keep record of this.<br><br>Username - ".$user."<br>Password - ".$_POST["password1"]."<br><br>Thanks!', '".$_COOKIE[username]."', '".$user."', '".time()."')") or die(mysql_error());

   $upd = "INSERT INTO `onecms_permissions` VALUES ('null', '".$user."', 'no', 'yes'";

   $result = mysql_query("SELECT * FROM onecms_cat");
   while ($row = mysql_fetch_array($result)) {

   		$upd .= ", 'yes'";
   	}

	$upd .= ")";

	$r = mysql_query($upd) or die(mysql_error());

   $password2 = md5($_POST["password1"]);
   $updd = "INSERT INTO onecms_users VALUES ('null', '".$user."', '".$password2."', '".$_POST["email"]."', '".$_POST["level"]."', '0', 'no', 'no', '0', '1', '3', '".$_POST['slist']."')";
$b = mysql_query($updd) or die(mysql_error());
}
}
if (($r == TRUE) && ($b == TRUE)) {
	echo "The user has been created. <a href=\"a_users.php\">Return to Manage users Home</a><br>";
} else {
	echo "There are errors in your entry, please go back and go over the form<br>";
}
}
}

if (((($_GET['view'] == "log") && (!$_POST['search']) && ($_GET['del'] == "") && ($_GET['log'] == "")))) {


echo "<form name=\"search\" method=\"post\" action=\"a_users.php?view=log\">
<table cellspacing=\"0\" cellpading=\"2\" border=\"0\" align=\"center\"><tr><td>
Search by user  </td><td><select name=\"type\">
<option value=\"username\">Username</option>
<option value=\"ip\">IP</option>
<option value=\"url\">URL</option>
<option value=\"date\">Date</option>
</select>
</td><td><input type=\"text\" name=\"keyword\"></td><td></td><td>
<input type=\"submit\" name=\"search\" value=\"Search\"></td></tr></table>
</form>
<center><a href=\"#bottom\">Bottom of Page</a></center><br><br>
<form action='a_users.php?view=log&del=yes' method='post' name='checkboxform'><table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" align=\"center\"><tr><td>Delete all Logs?</td><td><input type='checkbox' name='all' value='yes'></td></tr><tr><td><b><center>Username</center></b></td>
<td><b><center>IP</center></b></td><td><b><center>View Info</center></b></td><td><b><center>Delete?</center></b></td></tr>";

$query="SELECT * FROM onecms_log ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$idt = "$row[id]";
                $ipt = "$row[ip]";
		$id = "$row[id]";
		$datet = "$row[date]";
                $usert = "$row[username]";

				if ($usert == "") {
					$usert == "Unkown";
				}

		echo "<tr><td>$usert</td><td><center>$ipt</center></td><td><center><a href=\"a_users.php?view=log&log=$id\">View</a></center></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></center></td></tr>";
}

		echo "<tr><td><input type='submit' name='deletebiatch' value='Delete'></td><td><input type=\"button\" value=\"Check All\" onClick=\"checkAll()\"></td><td><input type=\"button\" value=\"Uncheck All\" onClick=\"uncheckAll()\"></td></tr></form></table><br><br>";

	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_log"),0);

$total_pages = ceil($total_results / $max_results);

echo "<br><center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_users.php?view=log&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_users.php?view=log&page=$i\">$i</a>&nbsp;";
if (($i/25) == (int)($i/25)) {
  echo "<br>";
}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_users.php?view=log&page=$next\">Next>></a>";
}
echo "</center>";

echo "<a name=\"bottom\">";
}

if (((($_GET['view'] == "log") && ($_POST['search']) && ($_GET['del'] == "") && ($_GET['log'] == "")))) {

echo "<center><a href=\"#bottom\">Bottom of Page</a></center><br><br>
<form action='a_users.php?view=log&del=yes' method='post' name='checkboxform'><table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" align=\"center\"><tr><td>Delete all Logs?</td><td><input type='checkbox' name='all' value='yes'></td></tr><tr><td><b><center>Username</center></b></td>
<td><b><center>IP</center></b></td><td><b><center>View Info</center></b></td><td><b><center>Delete?</center></b></td></tr>";

$type = $_POST['type'];
$keyword = $_POST['keyword'];

$query2 = mysql_query("SELECT * FROM onecms_log WHERE $type LIKE '%" . $keyword . "%'") or die(mysql_error());
$num = mysql_num_rows($query2);

echo "<center>Number of Results: <b>$num</b></center><br><br>";

echo "<title>$num Results for '$keyword'</title>";

$query = mysql_query("SELECT * FROM onecms_log WHERE $type LIKE '%".$keyword."%' ORDER BY `id` DESC LIMIT $from, $max_results") or die(mysql_error());
while($row = mysql_fetch_array($query)) {
        $ip2 = "$row[ip]";
		$date2 = "$row[date]";
		$id = "$row[id]";
		$user2 = "$row[username]";
                
        $patterns[0] = "/".$_POST['keyword']."/";
        $replacements[0] = "<b>".$_POST['keyword']."</b>";
        $ip = preg_replace($patterns, $replacements, $ip2);
		$url = preg_replace($patterns, $replacements, $url2);
		$user = preg_replace($patterns, $replacements, $user2);
        $date = preg_replace($patterns, $replacements, $date2);

		if ($user == "") {
		$user == "Unkown";
		}


echo "<tr><td><center>$user</center></td><td><center>$ip</center></td><td><center><a href=\"a_users.php?view=log&log=$id\">View</a></center></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></center></td></tr>";
}

		if ($id == "") {

		echo "<tr><td>Sorry, but there are no results for <b>$keyword</b></td></tr>";

		}

echo "<tr><td><input type='submit' name='deletebiatch' value='Submit'></td><td><input type=\"button\" value=\"Check All\" onClick=\"checkAll()\"></td><td><input type=\"button\" value=\"Uncheck All\" onClick=\"uncheckAll()\"></td></tr></form></table><br><br>";

echo "<a name=\"bottom\">";
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_log WHERE $type LIKE '%" . $keyword . "%'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<br><center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_users.php?view=log&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_users.php?view=log&page=$i\">$i</a>&nbsp;";
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_users.php?view=log&page=$next\">Next>></a>";
}
echo "</center>";
}
if (($_GET['view'] == "log") && ($_GET['del'] == "yes")) {

echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

if ($_POST['all']) {
	$delete = mysql_query("DELETE FROM onecms_log") or die(mysql_error());
} else {

while (list(, $val) = each ($_POST['delete'])) {
	$delete = mysql_query("DELETE FROM onecms_log WHERE id = '$val'") or die(mysql_error());
}
}
if ($delete == TRUE) {
	echo "The log(s) have been deleted. <a href=\"a_users.php?view=log\">Return to IP Logger</a>";
}

echo "</form>";
}

if (($_GET['view'] == "log") && ($_GET['log'])) {

	$query="SELECT * FROM onecms_log WHERE id = '".$_GET['log']."'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$ip = "$row[ip]";
		$date = "$row[date]";
		$user = "$row[username]";

		echo "<table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" align=\"center\"><tr><td><b>Username:</b></td><td>$user</td></tr><tr><td><b>IP Address:</b></td><td><a href='http://www.dnsstuff.com/tools/whois.ch?ip=".$ip."'>$ip</a></td></tr><tr><td><b>Date:</b></td><td>$date</td></tr><tr><td><b>URL Accessed:</b></td><td><a href=\"$siteurl$row[url]\">$row[url]</a></td></tr><tr><td><b>Log Number:</b></td><td>".$_GET['log']."</td></tr></table>";

}
}

}
}
}include ("a_footer.inc");
?>
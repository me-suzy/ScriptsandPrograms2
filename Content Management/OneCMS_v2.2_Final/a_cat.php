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
	echo "Sorry $username, but you do not have permission to manage categories. You are only a $level.";
} else {

echo "<center><a href='a_cat.php?view=add'>Add Categories</a> | <a href='a_cat.php'>Manage Categories</a> | <a href='a_cat.php?view=search'>Search</a></center><br><br>";

if ($_GET['view'] == "search") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Cat > Search</title>";

	echo "<form action='a_cat.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for category</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_cat.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_cat WHERE name LIKE '%" . $search . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td>$name</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_cat.php?view=add\">Add category</a></td></tr></form></table><br><br>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_cat WHERE name LIKE '%" . $search . "%'"),0);

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

				echo "<title>OneCMS - www.insanevisions.com/onecms > Manage Categories</title>";

	echo "<form action='a_cat.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for category</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_cat.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_cat ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td>$name</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_cat.php?view=add\">Add category</a></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_cat"),0);

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

if (($_GET['view'] == "manage") && ($_POST['delete'] == "") && ($_GET['edit'] == "")) {

	echo "<form action='a_cat.php?view=manage&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_cat WHERE id = '$val'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
		$namea = "$row2[name]";

	echo "<input type=\"hidden\" name=\"id[]\" value=\"$val\"><tr><td><b><center>Category #".$val."</b></center></td></tr><tr><td><b>Name</b></td><td><input type='hidden' name=\"name2_$val\" value=\"$namea\"><input type='text' name=\"name_$val\" value=\"$namea\"></td></tr>";
	}
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
}

if (($_GET['view'] == "manage") && ($_GET['edit'] == "2")) {

   while (list(, $val) = each ($_POST['id'])) {

   if ((((((((((((((((((((((((((((((($_POST["name_$i"] == "id") or ($_POST["name_$i"] == "games") or ($_POST["name_$i"] == "users") or ($_POST["name_$i"] == "ver") or ($_POST["name_$i"] == "username") or ($_POST["name_$i"] == "Global")  or ($_POST["name_$i"] == "type") or ($_POST["name_$i"] == "contest") or ($_POST["name_$i"] == "album") or ($_POST["name_$i"] == "ad") or ($_POST["name_$i"] == "smilies") or ($_POST["name_$i"] == "badwords") or ($_POST["name_$i"] == "comments") or ($_POST["name_$i"] == "poll") or ($_POST["name_$i"] == "page") or ($_POST["name_$i"] == "file") or ($_POST["name_$i"] == "af") or ($_POST["name_$i"] == "pr") or ($_POST["name_$i"] == "settings") or ($_POST["name_$i"] == "users") or ($_POST["name_$i"] == "ip") or ($_POST["name_$i"] == "content") or ($_POST["name_$i"] == "systems") or ($_POST["name_$i"] == "cat") or ($_POST["name_$i"] == "fields") or ($_POST["name_$i"] == "stats") or ($_POST["name_$i"] == "templates") or ($_POST["name_$i"] == "posts") or ($_POST["name_$i"] == "topics") or ($_POST["name_$i"] == "stickies") or ($_POST["name_$i"] == "announcements") or ($_POST["name_$i"] == "elite") or ($_POST["name_$i"] == "userreviews"))))))))))))))))))))))))))))))) {
	   echo "Sorry, but the category name <b>".$_POST["name_$i"]."</b> is already in use. Go back and choose another name.";
   } else {

	     $sql = mysql_query("SELECT * FROM onecms_cat WHERE name = '".$_POST["name_$i"]."'");
   $num = mysql_num_rows($sql);

   if ($num > "0") {
	   echo "Sorry, but the category name <b>".$_POST["name_$i"]."</b> is already in use. Go back and choose another name.";
   } else {

   $upd = "UPDATE onecms_cat SET name = '".$_POST["name_$val"]."', date = '".time()."' WHERE id = '$val'";
   $upd2 = mysql_query("UPDATE onecms_templates SET name = '".$_POST["name_$val"]."' WHERE name = '".$_POST["name2_$val"]."'");
   $upd3 = mysql_query("ALTER TABLE onecms_permissions CHANGE `".$_POST["name2_$val"]."` `".$_POST["name_$val"]."` TEXT");
   $r = mysql_query($upd) or die(mysql_error());
   }
   }
   }
if ($r == TRUE) {
    echo "The category(s) has been updated. <a href=\"a_cat.php\">Manage Categories</a>";
   }
}	
	
if ((($_GET['view'] == "manage") && ($_GET['confirm'] == "") && ($_POST['delete']))) {

while (list(, $val) = each ($_POST['delete'])) {
	$query="SELECT * FROM onecms_cat WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$name = "$row[name]";

	$delete2 = mysql_query("DELETE FROM onecms_templates WHERE name = '$name'") or die(mysql_error());

	$delete3 = mysql_query("ALTER TABLE onecms_permissions DROP `$name`") or die(mysql_error());

	$delete4 = mysql_query("DELETE FROM onecms_content WHERE cat = '".$name."'") or die(mysql_error());

	$delete5 = mysql_query("DELETE FROM onecms_content WHERE cat = '$name'") or die(mysql_error());

	$delete = mysql_query("DELETE FROM onecms_cat WHERE id = '$val'") or die(mysql_error());
}
}
if ($delete == TRUE) {
	echo "The category has been deleted. <a href=\"a_cat.php\">Manage Categories</a>";
}
}



if ($_GET['view'] == "add") {
		echo "<form action=\"a_cat.php?view=add\" name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many items to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='a_cat.php?view=add2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
	echo "<tr><td><b><center>Category #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."'></td></tr>";
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr>";
	}
	echo "</form></table>";
}

	if ($_GET['view'] == "add2") {

   $time = date("Ymd");
   for($i = 0; $i < $_POST['s']; $i = $i+1) {

   if ((((((((((((((((((((((((((((((($_POST["name_$i"] == "id") or ($_POST["name_$i"] == "games") or ($_POST["name_$i"] == "users") or ($_POST["name_$i"] == "ver") or ($_POST["name_$i"] == "username") or ($_POST["name_$i"] == "Global")  or ($_POST["name_$i"] == "type") or ($_POST["name_$i"] == "contest") or ($_POST["name_$i"] == "album") or ($_POST["name_$i"] == "ad") or ($_POST["name_$i"] == "smilies") or ($_POST["name_$i"] == "badwords") or ($_POST["name_$i"] == "comments") or ($_POST["name_$i"] == "poll") or ($_POST["name_$i"] == "page") or ($_POST["name_$i"] == "file") or ($_POST["name_$i"] == "af") or ($_POST["name_$i"] == "pr") or ($_POST["name_$i"] == "settings") or ($_POST["name_$i"] == "users") or ($_POST["name_$i"] == "ip") or ($_POST["name_$i"] == "content") or ($_POST["name_$i"] == "systems") or ($_POST["name_$i"] == "cat") or ($_POST["name_$i"] == "fields") or ($_POST["name_$i"] == "stats") or ($_POST["name_$i"] == "templates") or ($_POST["name_$i"] == "posts") or ($_POST["name_$i"] == "topics") or ($_POST["name_$i"] == "stickies") or ($_POST["name_$i"] == "announcements") or ($_POST["name_$i"] == "elite") or ($_POST["name_$i"] == "userreviews"))))))))))))))))))))))))))))))) {

	   echo "Sorry, but the category name <b>".$_POST["name_$i"]."</b> is already in use. Go back and choose another name.<br><br>";
   } else {

   $sql = mysql_query("SELECT * FROM onecms_cat WHERE name = '".$_POST["name_$i"]."'");
   $num = mysql_num_rows($sql);

   if ($num > "0") {
	   echo "Sorry, but the category name <b>".$_POST["name_$i"]."</b> is already in use. Go back and choose another name.<br><br>";
   } else {

   $upd = mysql_query("INSERT INTO onecms_cat VALUES ('null', '".$_POST["name_$i"]."', '".time()."')") or die(mysql_error());
   $upd2 = mysql_query("INSERT INTO onecms_templates VALUES ('null', '".$_POST["name_$i"]."', '', '')") or die(mysql_error());
   $upd3 = mysql_query("ALTER TABLE onecms_permissions ADD `".$_POST["name_$i"]."` varchar(10) NOT NULL default 'no'") or die(mysql_error());
   $upd4 = mysql_query("UPDATE onecms_permissions SET ".$_POST["name_$i"]." = 'no' WHERE ".$_POST["name_$i"]." = ''") or die(mysql_error());
   }
if (((($upd2 == TRUE) && ($upd == TRUE) && ($upd3 == TRUE) && ($upd4 == TRUE)))) {
	echo "The category(s) have been created. <a href=\"a_cat.php\">Manage Categories</a>";
}
	}
}
}
}
}
}
}include ("a_footer.inc");
?>
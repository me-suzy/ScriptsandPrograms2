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
	echo "Sorry $username, but you do not have permission to manage pages. You are only a $level.";
} else {

	echo "<center><a href=\"a_pages.php?view=add1\">Add pages</a> | <a href=\"a_pages.php?view=add2\">Add pages (to content)</a> | <a href='a_pages.php'>Manage Pages</a></center><br><br>";

	if ($_GET['view'] == "") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Page Manager</title>";

	echo "<form action='a_pages.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for page</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_pages.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_pages ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$url = "$row[url]";
		$name = stripslashes($name2);
		if ($row[type] == "backend") {
    	echo "<tr><td><a href='".$pagepart1."".$url."".$pagepart2."' target='popup'>$name</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
		} else {
		echo "<tr><td><a href='pages.php?id=".$id."' target='popup'>$name</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
		}
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_pages WHERE type = 'backend'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_pages.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_pages.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_pages.php?page=$next\">Next>></a>";
}
echo "</center>";

}

if (($_GET['view'] == "add1") && ($_GET['add'] == "")) {
echo "<form action=\"a_pages.php?view=add1\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many pages to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

    if ($_POST['search']) {

echo "<form action='a_pages.php?view=add1&add=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\"><input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
    echo "<tr><td><b><center>Page #".$i."</b></center></td></tr><tr><td>Page Name</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>Coding</td><td><textarea name='content_".$i."' cols='30' rows='12'></textarea></td></tr>";
    echo "<tr><td>Page Online?</td><td><select name='online_".$i."' multiple><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr><tr><td>URL (ex. contact)</td><td><input type='text' name='url_".$i."'></td></tr>";
}
echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
}
}

if (($_GET['view'] == "add2") && ($_GET['add'] == "")) {
echo "<form action=\"a_pages.php?view=add2\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many pages to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

    if ($_POST['search']) {

echo "<form action='a_pages.php?view=add2&add=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\"><input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
    echo "<tr><td><b><center>Page #".$i."</b></center></td></tr>";
	echo "<tr><td>Content</td><td><textarea name='content_".$i."' cols='30' rows='12'></textarea></td></tr>";
    echo "<tr><td>Page Online?</td><td><select name='online_".$i."' multiple><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr><tr><td>Content for this page to be linked to</td><td><select name='url_".$i."'>";
	
	$sql = mysql_query("SELECT * FROM onecms_content ORDER BY `id` DESC");
	while($row = mysql_fetch_array($sql)) {

	$check1 = mysql_query("SELECT * FROM onecms_cat WHERE name = '".$row[cat]."'");
	$check2 = mysql_num_rows($check1);

	if ($check2 == "1") {

	$perm = mysql_query("SELECT * FROM onecms_permissions WHERE username = '".$_COOKIE[username]."' AND ".$row[cat]." = 'yes'") or die(mysql_error());
	$numper = mysql_num_rows($perm);

	if ($numper > "0") {
		echo "<option value=\"".$row[id]."\">".$row[name]."</option>";
	}
	}
	}

	echo "</td></tr>";
}
echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
}
}

if (($_GET['view'] == "add1") && ($_GET['add'] == "yes")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {

   $upd = "INSERT INTO onecms_pages VALUES ('null', '".$_POST["name_$i"]."', '".$_POST["url_$i"]."', '".addslashes($_POST["content_$i"])."', '".$_POST["online_$i"]."', 'backend')";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The page(s) have been created. <a href=\"a_pages.php\">Return to Page Manager Home</a>";
}
}

if (($_GET['view'] == "add2") && ($_GET['add'] == "yes")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {

   $findea = mysql_query("SELECT * FROM onecms_pages WHERE url = '".$_POST["url_$i"]."' AND type = 'frontend'");
   $page = mysql_num_rows($findea);
   
   $pagename = $page + 2;


   $upd = "INSERT INTO onecms_pages VALUES ('null', '".$pagename."', '".$_POST["url_$i"]."', '".addslashes($_POST["content_$i"])."', '".$_POST["online_$i"]."', 'frontend')";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The page(s) have been created. <a href=\"a_pages.php\">Return to Page Manager Home</a>";
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
	$delete = mysql_query("DELETE FROM onecms_pages WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "The page(s) have been deleted. <a href=\"a_pages.php\">Return to Page Manager Home</a>";
}
}
if ((($_GET['view'] == "manage") && ($_POST['delete'] == "") && ($_GET['edit'] == ""))) {

	echo "<form action='a_pages.php?view=manage&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_pages WHERE id = '$i'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {

 echo "<input type='hidden' name='id[]' value='".$row2[id]."'><tr><td><b><center>Page #".$i."</b></center></td></tr>";
 echo "<tr><td>Page Name</td><td><input type=\"text\" name='name_".$i."' value='".$row2[name]."'></td></tr><tr><td>Content</td><td><textarea name='content_".$i."' cols='30' rows='12'>".stripslashes($row2[content])."</textarea></td></tr><tr><td>Page Online?</td><td><select name='online_".$i."' multiple><option value='".$row2[online]."' selected>-- ".$row2[online]." --</option><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr>";
  if ($row2['type'] == "frontend") {
 echo "<tr><td>Content for this page to be linked to</td><td><select name='url_".$i."'><option value='".$row2[url]."' selected>-- ".$row2[url]." --</option>";
	
	$query="SELECT * FROM onecms_content";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	$cat = "$row[cat]";
	$name = "$row[name]";
	$id = "$row[id]";

	$queryy = mysql_query("SELECT * FROM onecms_permissions WHERE username = '$username' AND ".$cat." = 'yes'") or die(mysql_error());
	$numb = mysql_num_rows($queryy);

	if ($numb == "0") {
		echo "";
	} else {
		echo "<option value=\"$id\">$name</option>";
	}
	}

	echo "</td></tr>";
	} else {
	echo "</tr><tr><td>URL (ex. contact)</td><td><input type='text' name='url_".$i."' value='".$row2[url]."'></td></tr>";
	}
}
}
echo "<tr><td><input type=\"submit\" name=\"Modify\" value=\"Modify\"></td></tr></form></table>";

}
if ($_GET['view'] == "search") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Page Manager > Search</title>";

	echo "<form action='a_pages.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for page</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_pages.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_pages WHERE name LIKE '%" . $_POST['search'] . "%' OR url LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$url = "$row[url]";
		$name = stripslashes($name2);
		if ($row[type] == "backend") {
    	echo "<tr><td><a href='".$pagepart1."".$url."".$pagepart2."' target='popup'>$name</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
		} else {
		echo "<tr><td><a href='pages.php?id=".$id."' target='popup'>$name</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
		}
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_pages.php?view=add\">Add Company</a></td></tr></form></table><br><br>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_pages WHERE name LIKE '%" . $_POST['search'] . "%' OR url LIKE '%" . $_POST['search'] . "%'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_pages.php?view=search&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_pages.php?view=search&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_pages.php?view=search&page=$next\">Next>></a>";
}
echo "</center>";

}
if (($_GET['view'] == "manage") && ($_GET['edit'] == "2")) {

   while (list(, $i) = each ($_POST['id'])) {
   $_POST["content_$i"] = addslashes($_POST["content_$i"]);
   $upd = "UPDATE onecms_pages SET name = '".$_POST["name_$i"]."', url = '".$_POST["url_$i"]."', content = '".$_POST["content_$i"]."', online = '".$_POST["online_$i"]."' WHERE id = '".$i."'";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The page(s) have been updated. <a href='a_pages.php'>Return to Page Manager Home</a>";
}
}
}
}
}
}include ("a_footer.inc");
?>
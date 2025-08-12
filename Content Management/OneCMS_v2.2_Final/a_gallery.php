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

echo "<script language='javascript'>
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script><center><a href='a_gallery.php?view=manage&add=add1'>Add Gallery(s)</a> | <a href='a_gallery.php'>Manage Gallery(s)</a><br><br><small>In order to add images to a gallery, simply click on a gallery name and then click on 'Upload'</small></center><br><br>";

$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_permissions WHERE username = '".$_COOKIE[username]."' AND media = 'yes'"));

if ($sql == "1") {

if ($_GET['view'] == "search") {

	echo "<title>OneCMS - www.insanevisions.com > Gallery Manager > Search</title>";

	echo "<form action='a_gallery.php?view=search' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for gallery</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_gallery.php?view=manage2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Files</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr>";

	$query="SELECT * FROM onecms_albums WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
    	echo "<tr><td><a href='a_gallery.php?view=list&id=".$row[id]."'>";
		if ($row[type] == "file") {
		$sql = mysql_query("SELECT name FROM onecms_images WHERE id = '".$row[name]."'");
		$amount = mysql_num_rows($sql);
		$row2 = mysql_fetch_row($sql);
		
		if ($amount == "0") {
		echo "".$row[name]."";
		} else {
		echo "".$row2[0]."";
		}
		} else {
		echo "".$row[name]."";
		}

		$files = mysql_num_rows(mysql_query("SELECT * FROM onecms_images WHERE album = '".$row[id]."'"));

		echo "</a></td><td>$files</td><td><input type='checkbox' value='".$row[id]."' name='id[]'></td><td><input type='checkbox' value='".$row[id]."' name='delete[]'></td></tr>";
		}

echo "<tr><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><input type='submit' name='submit' value='Submit'></td></tr></form></table><br><br>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_albums WHERE name LIKE '%" . $_POST['search'] . "%'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_gallery.php?view=search&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_gallery.php?view=search&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_gallery.php?view=search&page=$next\">Next>></a>";
}
echo "</center>

    </span>
  </div></div></center>";

}

if ((($_GET['view'] == "list") && (!$_POST['fsearch']) && (is_numeric(intval($_GET['id']))))) {
	$id = intval(intval($_GET['id']));

	echo "<form action='a_gallery.php?view=list&id=".$id."' method='post'><table cellspacing=\"0\" cellpadding=\"5\" border=\"0\" align=\"center\"><tr><td>Search for file</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_upload.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"5\" border=\"0\" align=\"center\"><tr><td><b>File Name</b></td><td><b>Width/Height</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_images WHERE album = '".$id."' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id2 = "$row[id]";
		$name2 = "$row[name]";
		$name3 = stripslashes(substr($name2,0,50));
		$name = stripslashes($name2);

		if ($row[type2] == "ss") {
		$img = "".$images."/".$name."";
		} else {
		$img = $name;
		}

		list($width1, $height1, $type1, $attr) = @getimagesize($img);
		if (!$width1) {
		$width1 = "--";
		}

		if (!$height1) {
		$height1 = "--";
		}

    	echo "<tr><td><a href='".$img."' target='popup'>$name3</a></td><td>".$width1."/".$height1."</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id2\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$name\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href='a_upload.php?view=add&album=".$id."'>Upload</a></td><td><a href='a_upload.php?view=mass&album=".$id."'>Mass Upload</a></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_images WHERE album = '".$id."'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_gallery.php?view=list&id=".$id."&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_gallery.php?view=list&id=".$id."&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_gallery.php?view=list&id=".$id."&page=$next\">Next>></a>";
}
echo "</center>";
}

if (((($_GET['view'] == "") && ($_GET['edit'] == "") && ($_GET['move'] == "") && ($_GET['add'] == "")))) {

	echo "<title>OneCMS - www.insanevisions.com > Gallery Manager</title>";

	echo "<form action='a_gallery.php?view=search' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for gallery</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_gallery.php?view=manage2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Files</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr>";

	$query="SELECT * FROM onecms_albums ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
        echo "<tr><td><a href='a_gallery.php?view=list&id=".$row[id]."'>";
		if ($row[type] == "file") {
		$sql = mysql_query("SELECT name FROM onecms_images WHERE id = '".$row[name]."'");
		$amount = mysql_num_rows($sql);
		$row2 = mysql_fetch_row($sql);
		
		if ($amount == "0") {
		echo "".$row[name]."";
		} else {
		echo "".$row2[0]."";
		}
		} else {
		echo "".$row[name]."";
		}
		$files = mysql_num_rows(mysql_query("SELECT * FROM onecms_images WHERE album = '".$row[id]."'"));

		echo "</a></td><td>$files</td><td><input type='checkbox' value='".$row[id]."' name='id[]'></td><td><input type='checkbox' value='".$row[id]."' name='delete[]'></td></tr>";
		}

echo "<tr><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><input type='submit' name='submit' value='Submit'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_albums"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_gallery.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_gallery.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_gallery.php?page=$next\">Next>></a>";
}
echo "</center>
    </span>
  </div></div></center>";
}

if ($_GET['view'] == "manage2") {
if ($_POST['delete']) {

echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {
$delete = mysql_query("DELETE FROM onecms_albums WHERE id = '$val'") or die(mysql_error());
}

if ($delete == TRUE) {
	echo "The gallery(s) have been deleted. <a href='a_gallery.php'>Gallery Manager</a><br><br>";
}
}
if ($_POST['id']) {

		echo "<form action='a_gallery.php?view=manage2&edit=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

	while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_albums WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

	$query = mysql_query("SELECT name FROM onecms_systems WHERE id = '".$row[systems]."'");
	$systems = mysql_fetch_row($query);

	echo "<input type=\"hidden\" name=\"ida[]\" value=\"$val\"><input type=\"hidden\" name=\"type_".$val."\" value=\"".$row[type]."\"><tr><td><b><center>Gallery # ".$val."</b></center></td></tr><tr><td>Gallery Name</td><td><input type='text' name='name_".$val."' value='".$row[name]."'></td></tr><tr><td>System</td><td><select name='system_".$val."'><option value='".$row[systems]."' selected>-- ".$systems[0]." --</option><option value=''>-------</option>";

$sql = mysql_query("SELECT * FROM onecms_systems ORDER BY `name` ASC");
while ($r = mysql_fetch_array($sql)) {
	echo "<option value='".$r[id]."'>".$r[name]."</option>";
}
echo "</select></td></tr>";
        }
		}

		echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
	}

if (($_GET['view'] == "manage2") && ($_GET['edit'] == "yes")) {

   while (list(, $val) = each ($_POST['ida'])) {
   $update = mysql_query("UPDATE onecms_albums SET name = '".$_POST["name_$val"]."', systems = '".$_POST["system_$val"]."' WHERE id = '$val'") or die(mysql_error());
   }
if ($update == TRUE) {
    echo "The gallery(s) have been updated. <a href=\"a_gallery.php\">Gallery Manager</a>";
}
}
}

if (($_GET['view'] == "manage") && ($_GET['add'] == "add1")) {

echo "<form action=\"a_gallery.php?view=manage&add=add1\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many galleries to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

echo "<form action='a_gallery.php?view=manage&add=add2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($val = 0; $val < $_POST['search']; $val = $val+1) {
	echo "<tr><td><b><center>Gallery #".$val."</b></center></td></tr><tr><td>Name</td><td><input type='text' name='name_".$val."'></td></tr><tr><td>System</td><td><select name='system_".$val."'><option value=''>-------</option>";

$sql = mysql_query("SELECT * FROM onecms_systems ORDER BY `name` ASC");
while ($r = mysql_fetch_array($sql)) {
	echo "<option value='".$r[id]."'>".$r[name]."</option>";
}
echo "</select></td></tr>";
}
echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
	}
	echo "</form></table>";
}

if (($_GET['view'] == "manage") && ($_GET['add'] == "add2")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {

   $upd = "INSERT INTO onecms_albums VALUES ('null', '".$_POST["name_$i"]."', '0', '".$_POST["system_$i"]."')";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The gallery(s) have been created. <a href=\"a_gallery.php\">Gallery Manager</a>";
   }
	}
} else {
	echo "Sorry, but you do not have permission to this page.";
}



	}
	}
	}include ("a_footer.inc");
	?>
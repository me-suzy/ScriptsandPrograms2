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

echo "<center><a href='a_upload.php'>Manage Files</a> | <a href='a_upload.php?view=add'>Upload Files</a> | <a href='a_upload.php?view=rename'>Rename Files</a> | <a href='a_upload.php?view=mass'>Mass Upload</a>";
if ($_GET['album']) {
echo "| <a href='a_gallery.php?view=list&id=".$_GET['album']."'><b>Gallery</b></a>";
}
echo "</center><br><br>";

$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_permissions WHERE username = '".$_COOKIE[username]."' AND media = 'yes'"));

if ($sql == "1") {

if ($_GET['view'] == "mass") {
$id = $_GET['album'];

if ($id) {
echo "<form action='a_upload.php?view=mass&album=".$id."' method='post'>";
} else {
echo '<form action="a_upload.php?view=mass" method="post">';
}
echo '<table cellspacing="0" cellpading="2" border="0" align="center"><tr><td>How many folders to mass upload?</td><td><input type="text" name="much"></td><td><input type="submit" name="submita" value="Update"></td></tr></table></form>';

if ($_POST['much']) {
if ($id) {
echo "<form method='post' action='a_upload.php?view=mass2&album=".$id."'><table cellspacing='3' cellpading='3' border='0' align='center' width='95%'>";
} else {
echo '<form method="post" action="a_upload.php?view=mass2"><table cellspacing="3" cellpading="3" border="0" align="center" width="95%">';
}

for ($i = 1; $i <= $_POST['much']; $i = $i + 1) {

echo "<tr><td><center><b>Folder #".$i."</b></center><br></td></tr><tr><td><b>Path to folder (no trailing slash)</b></td><td><input type='text' name='dir_".$i."' value='".$path."'></td></tr><tr><td><b>URL to folder (no trailing slash)</b></td><td><input type='text' name='url_".$i."' value='".$images."'></td></tr>";
if ($id) {
echo "<input type='hidden' name='type_".$i."' value='screen'><input type='hidden' name='album_".$i."' value='".$id."'>";
} else {
echo "<tr><td><b>File Type</b></td><td><select name='type_".$i."'><option value='image'>Image</option><option value='boxart'>Boxart</option><option value='smiley'>Smiley</option><option value='file'>File</option><option value='movie'>Movie</option></select></td></tr><tr><td><b>Album</b></td><td><select name='album_".$i."'><option value=''>------</option>";

$sql = mysql_query("SELECT * FROM onecms_albums ORDER BY `name` ASC");
while ($r = mysql_fetch_array($sql)) {
	echo "<option value='".$r[id]."'>".$r[name]."</option>";
}
echo "</select></td></tr>";
}
// echo "<tr><td><b>Watermark</b></td><td><input type='checkbox' name='watermark_".$i."' value='yes' checked></td></tr>";
}

echo "<input type='hidden' name='muche' value='".$_POST['much']."'>";
echo "<tr><td><input type='submit' value='Upload'></td></tr>";
}
echo "</table></form>";
}

if ($_GET['view'] == "mass2") {
for ($i = 1; $i <= $_POST['muche']; $i = $i + 1) {

$dir = dir($_POST["dir_$i"]); 
while($entry = $dir->read()) { 
if (($entry!= ".") && ($entry!= "..")) { 
$filename = $entry;

$sql2 = mysql_query("INSERT INTO onecms_images VALUES ('null', '".$_POST["url_$i"]."/".$filename."', '".$_POST["album_$i"]."', '', '".$_POST["type_$i"]."', '".time()."', 'ss2')");

if ($sql2 == TRUE) {
echo "<b>".$filename."</b> is a success<br>";
}
}
} 
$dir->close(); 
}
}

if ($_GET['view'] == "") {

	echo "<form action='a_upload.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for file</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_upload.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>File Name</b></td><td><b>File Type</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_images ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$images."/".$name."' target='popup'>$name</a></td><td>$row[type]</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$name\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_images"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_upload.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_upload.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_upload.php?page=$next\">Next>></a>";
}
echo "</center>";
}

if ($_GET['view'] == "search") {

	echo "<title>OneCMS - www.insanevisions.com > File Manager > Search</title>";

	echo "<form action='a_upload.php?view=search' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for file</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_upload.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>File Name</b></td><td><b>File Type</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr>";

	$query="SELECT * FROM onecms_images WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$images."/".$name."' target='popup'>$name</a></td><td>$row[type]</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$name\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_images WHERE name LIKE '%" . $_POST['search'] . "%'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_upload.php?view=search&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_upload.php?view=search&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_upload.php?view=search&page=$next\">Next>></a>";
}
echo "</center>

    </span>
  </div></div></center>";

}

if ($_GET['view'] == "search2") {

	echo "<title>OneCMS - www.insanevisions.com > File Manager > Rename Files > Search</title>";

	echo "<form action='a_upload.php?view=search2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for file</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_upload.php?view=rename2' name='form1' method='post'><b>It is HIGHLY recommended to NOT change the filetype of the image. (ie. madden2006-catch22.jpg to madden2006-catch.jpg is fine, but halo2-1.jpg to halo2-1.png is not recommended)</b><br><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>File Name</b></td><td><b>File Type</b></td><td><b>Preview</b></td><td><b>Width/Height</b></td></tr>";

	$query="SELECT * FROM onecms_images WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name = stripslashes($row[name]);

        $theimg = "".$path."/".$name."";

		if (file_exists($theimg)) {
		$img = "".$images."/".$name."";
		} else {
		$img = $images;
        }

        list($width1, $height1, $type1, $attr) = @getimagesize($theimg);
		if (!$width1) {
		$width1 = "--";
		}

		if (!$height1) {
		$height1 = "--";
		}

    	echo "<tr><td><input type='hidden' name='id[]' value='".$id."'><input type='hidden' name='old".$id."' value='".$name."'><input type='text' name='".$id."' value='".$name."'></td><td>".$row[type]."</td><td><a href='".$img."' target='popup'><img src='".$img."' width='50' height='50'></a></td><td>".$width1."/".$height1."</td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Rename Files'></td></tr></form></table><br><br>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_images WHERE name LIKE '%" . $_POST['search'] . "%'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_upload.php?view=search2&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_upload.php?view=search2&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_upload.php?view=search2&page=$next\">Next>></a>";
}
echo "</center>

    </span>
  </div></div></center>";

}

if ($_GET['view'] == "rename") {

	echo "<form action='a_upload.php?view=search2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for file</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_upload.php?view=rename2' name='form1' method='post'><b>It is HIGHLY recommended to NOT change the filetype of the image. (ie. madden2006-catch22.jpg to madden2006-catch.jpg is fine, but halo2-1.jpg to halo2-1.png is not recommended)</b><br><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>File Name</b></td><td><b>File Type</b></td><td><b>Preview</b></td><td><b>Width/Height</b></td></tr>";

$query="SELECT * FROM onecms_images ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name = stripslashes($row[name]);

        $theimg = "".$path."/".$name."";

		if (file_exists($theimg)) {
		$img = "".$images."/".$name."";
		} else {
		$img = $images;
        }

        list($width1, $height1, $type1, $attr) = @getimagesize($theimg);
		if (!$width1) {
		$width1 = "--";
		}

		if (!$height1) {
		$height1 = "--";
		}

    	echo "<tr><td><input type='hidden' name='id[]' value='".$id."'><input type='hidden' name='old".$id."' value='".$name."'><input type='text' name='".$id."' value='".$name."'></td><td>".$row[type]."</td><td><a href='".$img."' target='popup'><img src='".$img."' width='50' height='50'></a></td><td>".$width1."/".$height1."</td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Rename Files'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_images"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_upload.php?view=rename&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_upload.php?view=rename&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_upload.php?view=rename&page=$next\">Next>></a>";
}
echo "</center>
    </span>
  </div></div></center>";

}

if ($_GET['view'] == "rename2") {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to rename these files?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $i) = each ($_POST['id'])) {
if ($_POST["$i"] == $_POST["old$i"]) {
} else {
$fetch = mysql_fetch_row(mysql_query("SELECT type2 FROM onecms_images WHERE id = '".$i."'"));

if ($fetch[0] == "ss") {
$rename = mysql_query("UPDATE onecms_images SET name = '".$_POST["$i"]."' WHERE id = '$i'") or die(mysql_error());
$rename2 = @rename("".$path."/".$_POST["old$i"]."", "".$path."/".$_POST["$i"]."");
}

if ($fetch[0] == "ss2") {
$ex = explode("".$siteurl."/", $_POST["old$i"]);
$ex2 = explode("".$siteurl."/", $_POST["$i"]);
if (strlen($dbuser) > "6") {
$ex3 = explode("_", $dbuser);
$duser = $ex3[0];
} else {
$duser = $dbuser;
}
$rename = mysql_query("UPDATE onecms_images SET name = '".$siteurl."/".$ex2[1]."' WHERE id = '".$i."'") or die(mysql_error());
$rename2 = @rename("/home/".$duser."/public_html/".$ex[1]."", "/home/".$duser."/public_html/".$ex2[1]."");
}

}
}
if (($rename == TRUE) && ($rename2 == TRUE)) {
echo "Files updated";
}
}

if ($_GET['view'] == "manage") {
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
	$delete = mysql_query("DELETE FROM onecms_images WHERE name = '$val'") or die(mysql_error());
    if (file_exists("$path/$val")) {
	unlink("$path/$val");
	}
}
if ($delete == TRUE) {
	echo "The files/images have been deleted.";
}
}

if ($_POST['id']) {
	echo '<form name="form1" method="post" action="a_upload.php?view=update"><table cellspacing="3" cellpading="3" border="0" align="center" width="95%">';

	 while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_images WHERE id = '$i'";
	$result=mysql_query($query);
	while($r = mysql_fetch_array($result)) {

     echo "<input type='hidden' name='id[]' value='".$i."'>";

echo "<tr><td><center><b>File # ".$i."</b></center></td></tr><tr><td>File</td><td>".$r[name]."</td></tr><tr>";
if (!$_GET['album']) {
echo "<td>File Type</td><td><select name='type_".$i."'><option value='".$r[type]."' selected>-- ".$r[type]." --</option><option value='image'>Image</option><option value='boxart'>Boxart</option><option value='smiley'>Smiley</option><option value='file'>File</option><option value='movie'>Movie</option></select></td>";
}
//echo "<td>Watermark Image?</td><td><input type='checkbox' name='watermark_".$i."' value='yes'";

//if ($r[watermark]) {
//echo " checked";
//}

if ($r[album]) {
$t = mysql_fetch_row(mysql_query("SELECT name FROM onecms_albums WHERE id = '".$r[album]."'"));
} else {
$t[0] = "None Selected";
}

//echo "></td>";
echo "</tr>";
if ($_GET['album']) {
echo "<tr><td>Caption</td><td><input type='text' name='caption_".$i."' value='".stripslashes($r[caption])."'></td></tr>";
}
}
}
echo '<tr><td><input type="submit" name="Submit" value="Update"></td></tr></form></table>';
}
}

if ($_GET['view'] == "update") {

while (list(, $i) = each ($_POST['id'])) {
$sql = mysql_query("UPDATE onecms_images SET caption = '".addslashes($_POST["caption_$i"])."', album = '".$_POST["album_$i"]."', type = '".$_POST["type_$i"]."', date = '".time()."' WHERE id = '".$i."'");
}

if ($sql == TRUE) {
echo "The information for the files/images has been updated";
}
}

if ($_GET['view'] == "add") {

if (!$_GET['album']) {
echo '<form action="a_upload.php?view=add" method="post">';
} else {
echo "<form action='a_upload.php?view=add&album=".$_GET['album']."' method='post'>";
}

echo '<table cellspacing="0" cellpading="2" border="0" align="center"><tr><td>How many files to Upload?</td><td><input type="text" name="much"></td><td><input type="submit" name="submita" value="Update"></td></tr></table></form>';

if ($_POST['much']) {

if (!$_GET['album']) {
echo '<form name="form1" method="post" action="a_upload.php?view=add2" enctype="multipart/form-data">';
} else {
echo "<form name='form1' method='post' action='a_upload.php?view=add2&album=".$_GET['album']."' enctype='multipart/form-data'>";
}

echo '<table cellspacing="3" cellpading="3" border="0" align="center" width="95%">';

for ($i = 1; $i <= $_POST['much']; $i = $i + 1) {

echo "<tr><td><center><b>File # ".$i."</b></center></td></tr><tr><td>File</td><td><input type='file' name='ss_".$i."'></td><td>...<i>or</i> link to a file</td><td><input type='text' name='ss2_".$i."'></td></tr><tr>";
if (!$_GET['album']) {
echo "<td>File Type</td><td><select name='type_".$i."'><option value='image'>Image</option><option value='boxart'>Boxart</option><option value='smiley'>Smiley</option><option value='file'>File</option><option value='movie'>Movie</option></select></td>";
}
//echo "<td>Watermark Image?</td><td><input type='checkbox' name='watermark_".$i."' value='yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Overwrite?&nbsp;&nbsp;<input type='checkbox' name='overwrite_".$i."' value='yes' checked></td>";
if ($_GET['album']) {
echo "<td>Caption</td><td><input type='text' name='caption_".$i."'></td></tr>";
} else {
echo "</tr>";
}
}

echo "<input type='hidden' name='muche' value='".$_POST['much']."'>";
echo '<tr><td><input type="submit" name="Submit" value="Upload"></td></tr>';
}
echo "</table></form>";
}

if ($_GET['view'] == "add2") {

for ($i = 1; $i <= $_POST['muche']; $i = $i + 1) {

if (($_POST["album_$i"] == "") && ($_GET['album'])) {
$album = $_GET['album'];
}

if ($_POST["type_$i"] == "") {
$type = "screen";
} else {
$type = $_POST["type_$i"];
}

if ($_FILES["ss_$i"]["name"]) {
if (((((($_FILES["ss_$i"]["type"] == "image/jpeg") or ($_FILES["ss_$i"]["type"] == "image/gif") or ($_FILES["ss_$i"]["type"] == "image/bmp") or ($_FILES["ss_$i"]["type"] == "image/png") && ($_FILES["ss_$i"]["type"])))))) {

copy ($_FILES["ss_$i"]["tmp_name"], "$path/".$_FILES["ss_$i"]["name"]."");

//if ($_POST["watermark_$i"]) {
//watermark($_FILES["ss_$i"]["name"]);
//}


$upd = "INSERT INTO onecms_images VALUES ('null', '".$_FILES["ss_$i"]["name"]."', '".$album."', '".addslashes($_POST["caption_$i"])."', '".$type."', '".time()."', 'ss')";
$d = mysql_query($upd) or die(mysql_error());
} else {
echo "Sorry but this file type (<b>".$_FILES["ss_$i"]["type"]."</b>) is not supported.<br>";
}

} else {
$upd = "INSERT INTO onecms_images VALUES ('null', '".$_POST["ss2_$i"]."', '".$album."', '".addslashes($_POST["caption_$i"])."', '".$type."', '".time()."', 'ss2')";
$d = mysql_query($upd) or die(mysql_error());
}
}

	if ($d == TRUE) {
		if (!$_GET['album']) {
		echo "The files have been uploaded. <a href='a_upload.php'>File Manager Home</a>";
		} else {
		echo "The files have been uploaded to the gallery. <a href='a_gallery.php?view=list&id=".$_GET['album']."'>Return to Gallery</a>";
		}
	} else {
		echo "Didn't work. Try chmoding the folder (777) and make sure the folder also exists.";
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
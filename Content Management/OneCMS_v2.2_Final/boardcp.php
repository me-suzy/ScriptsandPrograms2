<?php
$la = "a";
$z = "b";
include ("config.php");

if ($ipbancheck2 == "0") {
if ($numvb == "0"){
	if ($warn == $naum) {
	echo "You are banned from the forums...now go away!";
} else {

echo '<SCRIPT LANGUAGE="JavaScript">var checkflag = "false";function check(field) {if (checkflag == "false") {for (i = 0; i < field.length; i++) {field[i].checked = true;}checkflag = "true";return "Uncheck All"; }else {for (i = 0; i < field.length; i++) {field[i].checked = false; }checkflag = "false";return "Check All"; }}</script>';


echo '<script language="javascript">
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script>
<link rel="stylesheet" type="text/css" href="ta3.css">

<table width="70%" align="center" cellspacing="0" cellpadding="0" bgcolor="white">
<tr><td style="border:1px solid black;"><center><b><font size="5" face="verdana"><div align="center">OneCMS v2.2 - Forum CP</div></font></b></center></td></tr>
<tr><td>

<table width="100%" align="left" valign="top" cellspacing="0" cellpadding="5"><tr><td width="16%" valign="top"  style="border:1px solid black;">
<b><center>Index</center></b><br>
- <a href="';
echo $forumsurl;
echo '">View Forum</a><br>
- <a href="a_index.php?view=home">Admin CP</a><br>
- <a href="boardcp.php">CP Home</a><br><br>';
    if (($_COOKIE[username]) && (is_numeric($useridn))) {
	$result = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'admin'");
    $oj = mysql_num_rows($result);
	if ($oj > "0") {

echo '<b><center>Admin CP</center></b><br>
- <a href="boardcp.php?view=settings">Forum Settings</a><br>
- <a href="boardcp.php?view=cat">Manage/Add Categories</a><br>
- <a href="boardcp.php?view=forums">Manage/Add Forums</a><br>
- <a href="boardcp.php?view=sub">Manage/Add Sub-Forums</a><br>
- <a href="boardcp.php?view=users">Manage Mods/Admins</a><br>
- <a href="boardcp.php?view=ranks">Manage Ranks</a><br>
- <a href="boardcp.php?view=shop">Manage Shop</a><br>
<br>';
	}
	$result2 = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'global'");
	$result2b = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'admin'");
	$result2c = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'mod'");
    $ou = mysql_num_rows($result2);
	$oue = mysql_num_rows($result2b);
	$oud = mysql_num_rows($result2c);
	if ((($ou > "0") or ($oue > "0") or ($oud > "0"))) {

echo '<b><center>Moderator CP</center></b><br>
- <a href="boardcp.php?view=topics">Manage Topics</a><br>
- <a href="boardcp.php?view=posts">Manage Posts</a><br><br>';
	}
echo '</td><td width="64%" style="border:1px solid black;" valign="top"><br><br><SCRIPT LANGUAGE="JavaScript">
var checkflag = "false";
function check(field) {
if (checkflag == "false") {
for (i = 0; i < field.length; i++) {
field[i].checked = true;}
checkflag = "true";
return "Uncheck All"; }
else {
for (i = 0; i < field.length; i++) {
field[i].checked = false; }
checkflag = "false";
return "Check All"; }
}
</script>';

	$result = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'admin'");
    $oj = mysql_num_rows($result);
	if ($oj > "0") {

		if ($_GET['view'] == "shop2") {

			while (list(, $i) = each ($_POST['id'])) {

			$sql = mysql_query("UPDATE onecms_shop SET name = '".addslashes($_POST["name_$i"])."', price = '".addslashes($_POST["price_$i"])."', instock = '".addslashes($_POST["instock_$i"])."', image = '".addslashes($_POST["image_$i"])."', pid = '', date = '".time()."' WHERE id = '".$i."'");
			}

			if ($_POST['del']) {
			while (list(, $i2) = each ($_POST['del'])) {

			$sql2 = mysql_query("DELETE FROM onecms_shop WHERE id = '".$i2."'");
			}
			}


            if (($sql == TRUE) or ($sql2 == TRUE)) {
			echo "The shop items have been updated/deleted! <a href='boardcp.php?view=shop'>Manage Shop</a>";
			}
		}

		if ($_GET['view'] == "shop12") {

			for($i = 0; $i < $_POST['s']; $i = $i+1) {

			$sql = mysql_query("INSERT INTO onecms_shop VALUES ('null', '".addslashes($_POST["name_$i"])."', '".addslashes($_POST["price_$i"])."', '".addslashes($_POST["instock_$i"])."', '".addslashes($_POST["image_$i"])."', '', '".time()."')");
			}


            if ($sql == TRUE) {
			echo "The shop items have been created! <a href='boardcp.php?view=shop'>Manage Shop</a>";
			}
		}

if ($_GET['view'] == "shop1") {

echo "<form action=\"boardcp.php?view=shop1\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many shop items to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

if ($_POST['search']) {

echo "<form action='boardcp.php?view=shop12' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Price *</b></td><td><b>In Stock **</b></td><td><b>Image</b></td></tr><input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

for($i = 0; $i < $_POST['search']; $i = $i+1) {

echo "<tr><td><input type='text' name='name_".$i."' size='12'></td><td><input type='text' name='price_".$i."' size='5'></td><td><input type='text' name='instock_".$i."' size='5'></td><td><input type='text' name='image_".$i."' size='20'></td></tr>";
}

echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td><td><input type='reset' name='reset' value='Reset'></td><td><small>* How many points required to buy this item</small></td><td><small>** How many of this item that you want in stock</small></td></tr>";
}
echo "</form></table>";
}

if ($_GET['view'] == "shop") {
echo "<form action='boardcp.php?view=shop2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Price</b></td><td><b>In Stock</b></td><td><b>Image</b></td><td><b>Delete?</b></td></tr>";

	$query="SELECT * FROM onecms_shop";
	$result=mysql_query($query);
	while($r = mysql_fetch_array($result)) {
		echo "<tr><td><input type='hidden' name='id[]' value='".$r[id]."'><input type='text' name='name_".$r[id]."' value='".$r[name]."' size='16'></td><td><input type='text' name='price_".$r[id]."' value='".$r[price]."' size='5'></td><td><input type='text' name='instock_".$r[id]."' value='".$r[instock]."' size='4'></td><td><input type='text' name='image_".$r[id]."' value='".$r[image]."' size='12'></td><td><input type='checkbox' name='del[]' value='".$r[id]."'></td></tr>";
	}

	echo "<tr><td><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"boardcp.php?view=shop1\">Add Shop Item(s)</a></td></tr></form></table>";
	}


		if ($_GET['view'] == "ranks3") {

			for($i = 0; $i < $_POST['s']; $i = $i+1) {

			$sql = mysql_query("INSERT INTO onecms_ranks VALUES ('null', '".addslashes($_POST["name_$i"])."', '".addslashes($_POST["color_$i"])."', '".addslashes($_POST["points_$i"])."', '".time()."')");
			}


            if ($sql == TRUE) {
			echo "The ranks have been created! <a href='boardcp.php?view=ranks'>Manage Ranks</a>";
			}
		}

if ($_GET['view'] == "ranks2") {

echo "<form action=\"boardcp.php?view=ranks2\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many ranks to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

if ($_POST['search']) {

echo "<form action='boardcp.php?view=ranks3' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Color</b></td><td><b>Points Required</b></td></tr><input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

for($i = 0; $i < $_POST['search']; $i = $i+1) {

echo "<tr><td><input type='text' name='name_".$i."'></td><td><input type='text' name='color_".$i."' size='16'></td><td><input type='text' name='points_".$i."' size='4'></td></tr>";
}

echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td><td><input type='reset' name='reset' value='Reset'></td></tr>";
}
echo "</form></table>";
}

			if ($_GET['view'] == "ranks4") {

            if ($_POST['id']) {
			while (list(, $i) = each ($_POST['id'])) {

			$sql = mysql_query("UPDATE onecms_ranks SET name = '".addslashes($_POST["name_$i"])."', color = '".addslashes($_POST["color_$i"])."', points = '".addslashes($_POST["points_$i"])."', date = '".time()."' WHERE id = '".$i."'");
			}
			}

            if ($_POST['delete']) {
			while (list(, $ia) = each ($_POST['delete'])) {
			$sql2 = mysql_query("DELETE FROM onecms_ranks WHERE id = '".$ia."'");
			}
			}


            if (($sql == TRUE) or ($sql2 == TRUE)) {
			echo "The ranks have been updated! <a href='boardcp.php?view=ranks'>Manage Ranks</a>";
			}
		}

	if ($_GET['view'] == "ranks") {
	echo "<form action='boardcp.php?view=ranks4' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Color</b></td><td><b>Points Required</b></td><td><b>Delete?</b></td></tr>";

	$query="SELECT * FROM onecms_ranks";
	$result=mysql_query($query);
	while($r = mysql_fetch_array($result)) {
		echo "<tr><td><input type='hidden' name='id[]' value='".$r[id]."'><input type='text' name='name_".$r[id]."' value='".$r[name]."' size='16'></td><td><input type='text' name='color_".$r[id]."' value='".$r[color]."' size='12'></td><td><input type='text' name='points_".$r[id]."' value='".$r[points]."' size='4'></td><td><input type='checkbox' name='delete[]' value='".$r[id]."'></td></tr>";
	}

	echo "<tr><td><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"boardcp.php?view=ranks2\">Add Ranks</a></td></tr></form></table>";
	}

	if ($_GET['view'] == "sub") {
	echo "<form action='boardcp.php?view=sub1' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_forums WHERE type = 'sub'";
	$result=mysql_query($query);
	while($r = mysql_fetch_array($result)) {
		$explode = explode("||", $r[name]);

    	echo "<tr><td>".$explode[0]."</td><td><input type=\"checkbox\" name=\"id[]\" value=\"".$r[id]."\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"".$r[id]."\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"boardcp.php?view=sub2\">Add sub-forums</a></td></tr></form></table><br><br>";
}

if ((($_GET['view'] == "sub1") && ($_GET['add'] == "") && ($_GET['edit'] == ""))) {
if ($_POST['delete']) {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion? All post(s) within these sub-forum(s) will be deleted.");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {

$delete2 = mysql_query("DELETE FROM onecms_posts WHERE fid = '$val'") or die(mysql_error());
$delete = mysql_query("DELETE FROM onecms_forums WHERE id = '$val'") or die(mysql_error());
}

if ($delete == TRUE) {
	echo "The sub-forum(s) have been deleted. <a href='boardcp.php?view=sub'>Manage Sub-Forums</a>";
}
}

if ($_POST['id']) {
		echo "<form action='boardcp.php?view=sub11' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

	while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_forums WHERE id = '$i'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

	$explode = explode("||", $row[name]);

	echo "<tr><td><b><center>Sub-Forum #".$i."</b></center><input type='hidden' name='id[]' value='".$i."'></td></tr><tr><td>Name</td><td><input type='text' name='name_".$i."' value='".$explode[0]."'><input type='hidden' name='name2_".$i."' value='".$explode[1]."'></td></tr><tr><td>Assign to Forum</td><td><select name='place_".$i."'>";
	
	$query="SELECT * FROM onecms_forums WHERE type = 'forum'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
	if ($explode[1] == $row2[id]) {
	echo "<option value='".$row[id]."' selected>-- $row2[name] --</option>";
	} else {
	echo "<option value='".$row2[id]."'>$row2[name]</option>";
	}
	}
	echo "</select></td></tr><tr><td>Description</td><td><textarea name='des_".$i."' cols='30' rows='10'>".stripslashes($row[des])."</textarea></td></tr><tr><td>Order</td><td><input type='text' name='ord_".$i."' value='".$row[ord]."'></td></tr><tr><td>Locked?</td><td><input type='checkbox' name='lock_".$i."' value='yes'";
	
	if ($row[locked]) {
		echo " checked";
	}
	echo "></td></tr>";
	}
		}

		echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
}
}

if ($_GET['view'] == "sub11") {
	
    while (list(, $i) = each ($_POST['id'])) {

	$fin1 = mysql_query("SELECT cat FROM onecms_forums WHERE id = '".$_POST["name2_$i"]."'");
	$fin2 = mysql_fetch_row($fin1);

	$update = mysql_query("UPDATE onecms_forums SET name = '".$_POST["name_$i"]."||".$_POST["name2_$i"]."', ord = '".$_POST["ord_$i"]."', des = '".addslashes($_POST["des_$i"])."', cat = '".$fin2[0]."', locked = '".$_POST["lock_$i"]."' WHERE id = '".$i."'");
	}

	if ($update == TRUE) {
	echo "The sub-forum(s) have been updated. <a href='boardcp.php?view=sub'>Manage Sub-Forums</a>";
	}
}

if ($_GET['view'] == "sub2") {
		echo "<form action=\"boardcp.php?view=sub2\" name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many sub-forums to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='boardcp.php?view=sub21' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {

			echo "<tr><td><b><center>Sub-Forum #".$i."</b></center></td></tr><tr><td>Name</td><td><input type='text' name='name_".$i."'></td></tr><tr><td>Assign to Forum</td><td><select name='place_".$i."'>";
	
	$query="SELECT * FROM onecms_forums WHERE type = 'forum'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
	echo "<option value='".$row2[id]."'>$row2[name]</option>";
	}
	echo "</select></td></tr><tr><td>Description</td><td><textarea name='des_".$i."' cols='30' rows='10'></textarea></td></tr><tr><td>Order</td><td><input type='text' name='ord_".$i."'></td></tr><tr><td>Locked?</td><td><input type='checkbox' name='lock_".$i."' value='yes'></td></tr>";
		}

		echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
	}
}

if ($_GET['view'] == "sub21") {

	for($i = 0; $i < $_POST['s']; $i = $i+1) {

	$sql = mysql_query("SELECT cat FROM onecms_forums WHERE id = '".$_POST["place_$i"]."' AND type = 'forum'");
	$here = mysql_fetch_row($sql);

	$create = mysql_query("INSERT INTO onecms_forums VALUES ('null', '".$_POST["name_$i"]."||".$_POST["place_$i"]."', '".addslashes($_POST["des_$i"])."', 'sub', '".$here[0]."', '".$_POST["ord_$i"]."', '".$_POST["lock_$i"]."')");
	}

	if ($create == TRUE) {
	echo "The sub-forum(s) have been created. <a href='boardcp.php?view=sub'>Manage Sub-Forums</a>";
	}
}

if ($_GET['view'] == "settings") {
	echo "<form action='boardcp.php?view=settings2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Allow Visitors to post?</td><td>".$a." <input type='radio' name='a' value='".$a."' checked></td>";

if ($a == "yes") {
	echo "<td>No <input type='radio' name='a' value='no'></td>";
} else {
	echo "<td>Yes <input type='radio' name='a' value='yes'></td>";
}

echo "</tr><tr><td>Allow Visitors to create topics?</td><td>".$b." <input type='radio' name='b' value='".$b."' checked></td>";

if ($b == "yes") {
	echo "<td>No <input type='radio' name='b' value='no'></td>";
} else {
	echo "<td>Yes <input type='radio' name='b' value='yes'></td>";
}

echo "</tr><tr><td>Allow Visitors to use smilies?</td><td>".$c." <input type='radio' name='c' value='".$c."' checked></td>";

if ($c == "yes") {
	echo "<td>No <input type='radio' name='c' value='no'></td>";
} else {
	echo "<td>Yes <input type='radio' name='c' value='yes'></td>";
}

echo "</tr><tr><td>Allow Visitors to use custom name when posting? (name cannot be already in use)</td><td>".$d." <input type='radio' name='d' value='".$d."' checked></td>";

if ($d == "yes") {
	echo "<td>No <input type='radio' name='d' value='no'></td>";
} else {
	echo "<td>Yes <input type='radio' name='d' value='yes'></td>";
}

echo "</tr><tr><td>Topics displayed per page</td><td><input type='text' name='e' value='".$e."'></td></tr><tr><td>Posts displayed per page</td><td><input type='text' name='u' value='".$u."'></td></tr><tr><td>Color link for Admins</td><td><input type='text' name='color1' value='".$color1."'></td></tr><tr><td>Color link for Global Mods</td><td><input type='text' name='color2' value='".$color2."'></td></tr><tr><td>Color link for Mods</td><td><input type='text' name='color3' value='".$color3."'></td></tr><tr><td><input type=\"submit\" name=\"submit\" value=\"Update\"></td></tr></table></form>";

}

if ($_GET['view'] == "settings2") {
		$upd = mysql_query("UPDATE onecms_settings SET sitename = '".$_POST["a"]."', siteurl = '".$_POST["b"]."', online = '".$_POST["c"]."', dformat = '".$_POST["d"]."', warn = '".$_POST["e"]."', images = '".$_POST["u"]."', path = '".$_POST["color1"]."', max_results = '".$_POST["color2"]."', email = '".$_POST["color3"]."' WHERE id = '3'") or die (mysql_error()); 

	if ($upd == TRUE) {

		echo "Forum Settings updated. <a href='settings.php?type=forum'>Update</a>";
	}
}

if ((($_GET['view'] == "cat") && ($_GET['edit'] == "") && ($_GET['add'] == ""))) {
	echo "<form action='boardcp.php?view=cat2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_forums WHERE type = 'cat'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td>$name</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"boardcp.php?view=cat&add=1\">Add category</a></td></tr></form></table><br><br>";
}

if ($_GET['view'] == "cat2") {
	if ($_POST['id']) {
		echo "<form action='boardcp.php?view=cat&edit=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

		while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_forums WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$name = "$row[name]";
		$order = "$row[ord]";

		echo "<input type=\"hidden\" name=\"id[]\" value=\"$val\"><input type=\"hidden\" name=\"name2_".$val."\" value=\"".$name."\"><tr><td><b><center>Category # ".$val."</b></center></td></tr><tr><td><b>Name</b></td><td><input type='text' name='name_".$val."' value='".$name."'></td></tr><tr><td><b>Order</b></td><td><input type='text' name='order_".$val."' value='".$order."'></td></tr><tr><td>Locked?</td><td><input type='checkbox' name='lock_".$val."' value='yes'";
	
	if ($row[locked]) {
		echo " checked";
	}
	echo "></td></tr>";
	}
		}

		echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
	}
}

if (($_GET['view'] == "cat") && ($_GET['edit'] == "yes")) {
while (list(, $val) = each ($_POST['id'])) {
	$update = mysql_query("UPDATE onecms_forums SET name = '".$_POST["name_$val"]."', cat = '".$_POST["name_$val"]."', ord = '".$_POST["order_$val"]."', locked = '".$_POST["locked_$val"]."' WHERE id = '".$val."'") or die(mysql_error());
	$update2 = mysql_query("UPDATE onecms_forums SET cat = '".$_POST["name_$val"]."' WHERE cat = '".$_POST["name2_$val"]."'");
}   
	if ($update == TRUE) {
		echo "Categories have been updated. <a href='boardcp.php?view=cat'>Manage Categories</a>";
	}
}

if ($_GET['view'] == "cat2") {
	if ($_POST['delete']) {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion? All Forums within these category(s) will be deleted.");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {

$select = mysql_query("SELECT * FROM onecms_forums WHERE id = '".$val."'");
while($row = mysql_fetch_array($select)) {
	$cat = "$row[name]";
}
$delete2 = mysql_query("DELETE FROM onecms_forums WHERE cat = '".$cat."'") or die(mysql_error());
$delete = mysql_query("DELETE FROM onecms_forums WHERE id = '$val'") or die(mysql_error());
}

if ($delete == TRUE) {
	echo "The categories have been deleted. <a href='boardcp.php?view=cat'>Manage Categories</a>";
}
}
}

// END CAT, BEGIN FORUMS

if ((($_GET['view'] == "forums") && ($_GET['edit'] == "") && ($_GET['add'] == ""))) {
	echo "<form action='boardcp.php?view=forums2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_forums WHERE type = 'forum'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td>$name</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"boardcp.php?view=forums&add=1\">Add forums</a></td></tr></form></table><br><br>";
}

if ($_GET['view'] == "forums2") {
	if ($_POST['id']) {
		echo "<form action='boardcp.php?view=forums&edit=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

		while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_forums WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$name = "$row[name]";
		$order = "$row[ord]";
		$cat = "$row[cat]";
		$des = stripslashes($row[des]);

		echo "<input type=\"hidden\" name=\"id[]\" value=\"$val\"><input type=\"hidden\" name=\"name2_".$val."\" value=\"".$name."\"><tr><td><b><center>Forum # ".$val."</b></center></td></tr><tr><td>Name</td><td><input type='text' name='name_".$val."' value='".$name."'></td></tr><tr><td>Order</td><td><input type='text' name='order_".$val."' value='".$order."'></td></tr><tr><td>Category</td><td><select name='cat_".$val."'><option value='".$row[cat]."' selected>-- ".$row[cat]." --</option>";
        $query="SELECT * FROM onecms_forums WHERE type = 'cat' ORDER BY `ord` ASC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	if ($row[name] == $cat) {
	} else {
	echo "<option value='".$row[name]."'>$row[name]</option>";
	}
	}
	echo "</select></td></tr><tr><td>Description</td><td><textarea name='des_".$val."' cols='36' rows='12'>".$des."</textarea></td></tr><tr><td>Locked?</td><td><input type='checkbox' name='lock_".$val."' value='yes'";
	
	if ($row[locked]) {
		echo " checked";
	}
	echo "></td></tr>";
	}
		}

		echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
	}
}

if (($_GET['view'] == "forums") && ($_GET['edit'] == "yes")) {
while (list(, $val) = each ($_POST['id'])) {
	$update = mysql_query("UPDATE onecms_forums SET name = '".$_POST["name_$val"]."', ord = '".$_POST["order_$val"]."', des = '".addslashes($_POST["des_$val"])."', cat = '".$_POST["cat_$val"]."', locked = '".$_POST["lock_$val"]."' WHERE id = '".$val."'") or die(mysql_error());
}
	if ($update == TRUE) {
		echo "The Forum(s) have been updated. <a href='boardcp.php?view=forums'>Manage Forums</a>";
	}
}

if ($_GET['view'] == "forums2") {
	if ($_POST['delete']) {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion? All post(s) within these forum(s) will be deleted.");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {

$delete2 = mysql_query("DELETE FROM onecms_posts WHERE fid = '$val'") or die(mysql_error());
$delete = mysql_query("DELETE FROM onecms_forums WHERE id = '$val'") or die(mysql_error());
}

if ($delete == TRUE) {
	echo "The forum(s) have been deleted. <a href='boardcp.php?view=forums'>Manage Forums</a>";
}
}
}

// END FORUMS, START CAT ADD

if (($_GET['view'] == "cat") && ($_GET['add'] == "1")) {
		echo "<form action=\"boardcp.php?view=cat&add=1\" name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many categories to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='boardcp.php?view=cat&add=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
	echo "<tr><td><b><center>Category #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>Order</td><td><input type='text' name='ord_".$i."'></td></tr><tr><td>Locked?</td><td><input type='checkbox' name='locked_".$i."' value='yes'></td></tr>";
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
	}
}

if (($_GET['view'] == "cat") && ($_GET['add'] == "2")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   $upd[0] = mysql_query("INSERT INTO onecms_forums VALUES ('null', '".$_POST["name_$i"]."', '', 'cat', '".$_POST["name_$i"]."', '".$_POST["ord_$i"]."', '".$_POST["locked_$i"]."')") or die(mysql_error());
   }
if ($upd == TRUE) {
	echo "The category(s) have been created. <a href=\"boardcp.php?view=cat\">Manage Categories</a>";
}
	}

// END CAT ADD, START FORUM ADD

if (($_GET['view'] == "forums") && ($_GET['add'] == "1")) {
		echo "<form action=\"boardcp.php?view=forums&add=1\" name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many forums to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='boardcp.php?view=forums&add=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
	echo "<tr><td><b><center>Forum #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>Order</td><td><input type='text' name='ord_".$i."'></td></tr><tr><tr><td>Description</td><td><textarea name='des_".$i."' cols='25' rows='7'></textarea></td></tr><tr><td>Category</td><td><select name='cat_".$i."'>";
        $query="SELECT * FROM onecms_forums WHERE type = 'cat' ORDER BY `ord` ASC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	echo "<option value='".$row[name]."'>$row[name]</option>";
	}
	echo "</select></td></tr><tr><td>Locked?</td><td><input type='checkbox' name='lock_".$i."' value='yes'></td></tr>";
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
	}
	}

if (($_GET['view'] == "users3") && ($_GET['act'] == "edit")) {
if (!$_POST['submitt']) {
echo "<SCRIPT LANGUAGE='JavaScript'>function smiles(which) {document.form1.text1.value = document.form1.text1.value + which;}</SCRIPT><script language='javascript'>function awindow(towhere, newwinname, properties) {window.open(towhere,newwinname,properties);}</script><table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" align=\"center\"><form name='form1' method='post' action='".$HTTP_SERVER_VARS['REQUEST_URI']."'><tr><td><b>Username</b></td><td><input type='hidden' name='username' value='".$_COOKIE[username]."'>".$_COOKIE[username]."</td>";

$smilies = mysql_query("SELECT * FROM onecms_posts WHERE id = '".$_GET['id']."'");
while($row = mysql_fetch_array($smilies)) {
	$topic = stripslashes($row[subject]);
	$msg = stripslashes($row[message]);
	$tid = "$row[tid]";
	$fid = "$row[fid]";
}

echo "<input type='hidden' name='id' value='".$_GET['id']."'><input type='hidden' name='tid' value='".$tid."'></tr><tr><td><b>Subject</b></td><td><input type='text' name='subject' value='Re: ".$topic."'></td></tr><tr><td><b>Message</b></td><td><textarea name=\"text1\" cols='18' rows='7'>".$msg."

</textarea></td><td width='75'><b><center>Smilies</center></b><center><input type='hidden' name='fid' value='".$fid."'>";

$query2 = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley'");
$limit = mysql_num_rows($query2);

$smilies = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley' LIMIT 9");
while($row = mysql_fetch_array($smilies)) {
$tag = "$row[field]";
$name = "$row[name]";

list($width, $height, $type, $attr) = getimagesize("".$images."/".$tag."");
echo "<a href=\"javascript:smiles(' ".$name." ')\"><img src='".$images."/".$tag."' border='0' width='";
if ($width > "20") {
echo "20";
} else {
echo "$width";
}
echo "'></a>";
if (($limit/3) == (int)($limit/3)) {
	echo "<br>";
}
}
echo "<br><br><a href='javascript:awindow(\"comments.php?view=smilies\", \"\", \"width=200,height=200,scroll=yes\")'>View All</a>";
echo "</center></td></tr><tr><td><input type='submit' name='submit' value='Submit Post'></td><td><input type='reset' name='reset' value='Reset'></td></tr></table></form>";
}
}
	}

if (($_GET['view'] == "forums") && ($_GET['add'] == "2")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   $upd[0] = mysql_query("INSERT INTO onecms_forums VALUES ('null', '".$_POST["name_$i"]."', '".addslashes($_POST["des_$i"])."', 'forum', '".$_POST["cat_$i"]."', '".$_POST["ord_$i"]."', '".$_POST["lock_$i"]."')") or die(mysql_error());
   }
if ($upd == TRUE) {
	echo "The forum(s) have been created. <a href=\"boardcp.php?view=forums\">Manage Forums</a>";
}
	}

// END FORUM ADD, PERMISSION ADD START

if (($_GET['view'] == "users") && ($_GET['add'] == "1")) {
		echo "<form action=\"boardcp.php?view=users&add=1\" name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many permissions to assign?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='boardcp.php?view=users&add=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
	echo "<tr><td><b><center>User #".$i."</b></center></td></tr><tr><td>Username</td><td><select name='user_".$i."'><option value=''>-------</option>";
	
	$query="SELECT * FROM onecms_profile";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	echo "<option value='".$row[id]."'>$row[username]</option>";
	}
	echo "</select></td></tr><tr><td>Type</td><td><select name='type_".$i."'><option value=''>-------</option><option value='admin'>Admin</option><option value='global'>Global Mod</option><option value='mod'>Moderator</option></select></td></tr>";
	echo "<tr><td>Assign to Forum (only select a forum if your assigning this user as a moderator)</td><td><select name='place_".$i."'><option value=''>-------</option>";
	
	$query="SELECT * FROM onecms_forums WHERE type = 'forum' OR type = 'sub'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
	if ($row2[type] == "sub") {
	$explode = explode("||", $row2[name]);
	echo "<option value='".$row2[id]."'>".$explode[0]."</option>";
	} else {
	echo "<option value='".$row2[id]."'>$row2[name]</option>";
	}
	}
	echo "</select></td></tr>";

	echo "<tr><td>Assign to Category (only select a category if your assigning this user as a moderator...do not select both a forum and category)</td><td><select name='place2_".$i."'><option value=''>-------</option>";
	
	$query="SELECT * FROM onecms_forums WHERE type = 'cat'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	echo "<option value='".$row[id]."'>$row[name]</option>";
	}
	echo "</select></td></tr>";
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
	}
}

if (($_GET['view'] == "users") && ($_GET['add'] == "2")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   if ($_POST["place_$i"]) {
   $type = "forum";
   $place = "".$_POST["place_$i"]."";
   } else {
   $type = "cat";
   $place = "".$_POST["place2_$i"]."";
   }
   $upd[0] = mysql_query("INSERT INTO onecms_boardcp VALUES ('null', '".$_POST["user_$i"]."', '".$place."', '".$type."', '".$_POST["type_$i"]."')") or die(mysql_error());
   }
if ($upd == TRUE) {
	echo "The permission(s) have been assigned. <a href=\"boardcp.php?view=users\">Manage Levels</a>";
}
	}

// END PERMISSION ADD, START MANAGE PERMISSIONS

if ($_GET['view'] == "users3") {
if ($_GET['act'] == "delete") {

echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

$delete = mysql_query("DELETE FROM onecms_posts WHERE id = '".$_GET['id']."'");

if ($delete == TRUE) {
	echo "Congratulations, the post has been deleted.";
}
}

if ($_GET['act'] == "update") {

echo "<SCRIPT LANGUAGE='JavaScript'>
  function smiles(which) {
  document.form1.text1.value = document.form1.text1.value + which;
  }
</SCRIPT>
<script language='javascript'>
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script><form action='boardcp.php?view=users3&act=update2&id=".$_GET['id']."' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

$sql = mysql_query("SELECT subject,message FROM onecms_posts WHERE id = '".$_GET['id']."'");
$row = mysql_fetch_row($sql);

	echo "<tr><td>Subject</td><td><input type='text' name='subject' value='".stripslashes($row[0])."'></td></tr><tr><td>Message</td><td><textarea name='text1' cols='30' rows='10'>".stripslashes($row[1])."</textarea></td><td width='75'><b><center>Smilies</center></b><center>";

$query2 = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley'");
$limit = mysql_num_rows($query2);

$smilies = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley' LIMIT 9");
while($row = mysql_fetch_array($smilies)) {
$tag = "$row[field]";
$name = "$row[name]";

list($width, $height, $type, $attr) = getimagesize("".$images."/".$tag."");
echo "<a href=\"javascript:smiles(' ".$name." ')\"><img src='".$images."/".$tag."' border='0' width='";
if ($width > "20") {
echo "20";
} else {
echo "$width";
}
echo "'></a>";
if (($limit/3) == (int)($limit/3)) {
	echo "<br>";
}
}
echo "<br><br><a href='javascript:awindow(\"comments.php?view=smilies\", \"\", \"width=200,height=200,scroll=yes\")'>View All</a></center></td></tr><tr><td><input type='submit' name='submit' value='Submit Post'></td><td><input type='reset' name='reset' value='Reset'></td></tr></table></form>";
}

if ($_GET['act'] == "update2") {
$sql = mysql_query("UPDATE onecms_posts SET subject = '".addslashes($_POST['subject'])."', message = '".addslashes($_POST['text1'])."', date = '".time()."' WHERE id = '".$_GET['id']."'");

if ($sql == TRUE) {
	echo "The post has been updated.";
}
}
}

if ($_GET['view'] == "users2") {
if ($_POST['id']) {
		echo "<form action='boardcp.php?view=users&edit=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

	while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_boardcp WHERE id = '$i'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

	echo "<tr><td><b><center>User #".$i."</b></center></td></tr><tr><td>Username</td><td><select name='user_".$i."'>";
	
	$query="SELECT * FROM onecms_profile";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
	if ($row[uid] == $row2[id]) {
    echo "<option value=''>-------</option><option value='".$row[uid]."' selected>-- $row2[username] --</option>";
	} else {
	echo "<option value='".$row2[id]."'>$row2[username]</option>";
	}
	}
	echo "</select></td></tr><tr><td>Type</td><td><select name='type_".$i."'><option value='".$row[level]."' selected>-- $row[level] --</option><option value=''>-------</option><option value='admin'>Admin</option><option value='global'>Global Mod</option><option value='mod'>Moderator</option></select></td></tr>";
	echo "<tr><td>Assign to Forum (only select a forum if your assigning this user as a moderator)</td><td><select name='place_".$i."'><option value=''>-------</option>";
	
	$query="SELECT * FROM onecms_forums WHERE type = 'forum' OR type = 'sub'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
	if ($row[id] == $row2[id]) {
	if ($row2[type] == "sub") {
	$explode = explode("||", $row2[name]);
	echo "<option value='".$row[id]."' selected>-- ".$explode[0]." --</option>";
	} else {
	echo "<option value='".$row[id]."' selected>-- $row2[name] --</option>";
	}
	} else {
	if ($row2[type] == "sub") {
	$explode = explode("||", $row2[name]);
	echo "<option value='".$row2[id]."'>".$explode[0]."</option>";
	} else {
	echo "<option value='".$row2[id]."'>$row2[name]</option>";
	}
	}
	}
	echo "</select></td></tr>";

	echo "<tr><td>Assign to Category (only select a category if your assigning this user as a moderator...do not select both a forum and category)</td><td><select name='place2_".$i."'><option value=''>-------</option>";
	
	$query="SELECT * FROM onecms_forums WHERE type = 'cat'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
	if ($row[id] == $row2[id]) {
	echo "<option value='".$row[id]."' selected>-- $row2[name] --</option>";
	} else {
	echo "<option value='".$row2[id]."'>$row2[name]</option>";
	}
	}
	echo "</select></td></tr>";
	}
		}

		echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
}

if (($_GET['view'] == "users") && ($_GET['edit'] == "yes")) {
while (list(, $val) = each ($_POST['id'])) {
   if ($_POST["place_$i"]) {
   $type = "forum";
   $place = "".$_POST["place_$i"]."";
   } else {
   $type = "cat";
   $place = "".$_POST["place2_$i"]."";
   }
   $update = mysql_query("UPDATE onecms_boardcp SET uid = '".$_POST["user_$val"]."', place = '$place', type = '".$_POST["type_$i"]."', level = '$type' WHERE id = '".$val."'") or die(mysql_error());
}
	if ($update == TRUE) {
		echo "The Permission(s) have been updated. <a href='boardcp.php?view=users'>Manage Users</a>";
	}
}

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

$delete = mysql_query("DELETE FROM onecms_boardcp WHERE id = '$val'") or die(mysql_error());
}

if ($delete == TRUE) {
	echo "The mods/admins have been deleted.";
}
}

}

if ($_GET['view'] == "forums2") {
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

$delete = mysql_query("DELETE FROM onecms_boardcp WHERE id = '$val'") or die(mysql_error());
}

if ($delete == TRUE) {
	echo "The permission(s) have been deleted. <a href='boardcp.php?view=users'>Manage Users</a>";
}
}
}

// END MANAGE PERMISSIONS, START USERS INDEX

if ((($_GET['view'] == "users") && ($_GET['edit'] == "") && ($_GET['add'] == ""))) {
	echo "<form action='boardcp.php?view=users2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Level</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_boardcp";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$uidr = "$row[uid]";
	$querye = "SELECT * FROM onecms_profile WHERE id = '".$uidr."'";
	$resulte = mysql_query($querye);
	while($row2 = mysql_fetch_array($resulte)) {
		$user = "$row2[username]";
	}
    	echo "<tr><td>$user</td><td>$row[level]</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
	}

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"boardcp.php?view=users&add=1\">Add Users</a></td></tr></form></table><br><br>";
}

// END USERS INDEX, END BOARD CP ADMIN, BEGIN MOD BOARD CP

	}

	$result2t = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'global'");
	$result2bt = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'admin'");
	$result2ct = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'mod'");
    $out = mysql_num_rows($result2t);
	$ouet = mysql_num_rows($result2bt);
	$oudt = mysql_num_rows($result2ct);
	if ((($out > "0") or ($ouet > "0") or ($oudt > "0"))) {

if (((($_GET['view'] == "topics") && ($_GET['edit'] == "") && ($_GET['move'] == "") && ($_GET['add'] == "")))) {
	echo "<form action='boardcp.php?view=topics2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Topic Name</b></td><td><b>Forum</b></td><td><b>Edit</b></td><td><b>Delete</b></td><td><b>Move</b></td></tr><center><div align=\"center\">";

	$result2ok790 = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND level = 'admin' OR level = 'global'");
        $yee4 = mysql_num_rows($result2ok790);
		if ($yee4 > "0") {
	$query="SELECT * FROM onecms_posts WHERE type = 'topic'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	$id = "$row[id]";
	$forumid = "$row[fid]";
	$name = "$row[subject]";

	$querye = "SELECT * FROM onecms_forums WHERE id = '$forumid'";
	$resulte = mysql_query($querye);
	while($row2 = mysql_fetch_array($resulte)) {
		$forum = "$row2[name]";
	}
    	echo "<tr><td>$name</td><td>$forum</td><td><input type='checkbox' name='id[]' value='".$id."'></td><td><input type='checkbox' name='delete[]' value='".$id."'></td><td><input type='checkbox' name='move[]' value='".$id."'></td></tr>";
	}
		} else {

	$query="SELECT * FROM onecms_posts WHERE type = 'topic'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	$forumid = "$row[fid]";
	$id = "$row[id]";
	$name = "$row[subject]";

	$q = mysql_query("SELECT * FROM onecms_boardcp WHERE place = '".$forumid."' AND uid = '".$useridn."'");
	$for = mysql_num_rows($q);

	if ($for > "0") {

	$querye = "SELECT * FROM onecms_forums WHERE id = '$forumid'";
	$resulte = mysql_query($querye);
	while($row2 = mysql_fetch_array($resulte)) {
		$forum = "$row2[name]";
	}
    	echo "<tr><td>$name</td><td>$forum</td><td><input type='checkbox' name='id[]' value='".$id."'></td><td><input type='checkbox' name='delete[]' value='".$id."'></td><td><input type='checkbox' name='move[]' value='".$id."'></td></tr>";
	}
	}
		}

echo "<tr><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><input type='submit' name='submit' value='Submit >>'></td></tr></form></table><br><br>";
}

if ((($_GET['view'] == "topics2") && ($_GET['edit'] == "") && ($_GET['move'] == ""))) {
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

$delete = mysql_query("DELETE FROM onecms_posts WHERE id = '$val'") or die(mysql_error());
}

if ($delete == TRUE) {
	echo "The topic(s) have been deleted. <a href='boardcp.php?view=topics'>Manage Topics</a>";
}
}

if ($_POST['id']) {

		echo "<form action='boardcp.php?view=topics2&edit=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

	while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_posts WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

		echo "<input type=\"hidden\" name=\"ida[]\" value=\"$val\"><tr><td><b><center>Topic # ".$val."</b></center></td></tr><tr><td>Subject</td><td><input type='text' name='sub_".$val."' value='".$row[subject]."'></td></tr><tr><td>Message</td><td><textarea name='mes_".$val."' cols='36' rows='12'>".stripslashes($row[message])."</textarea></td></tr>";
}
		}

		echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table></form>";
	}

if ($_POST['move']) {

		echo "<form action='boardcp.php?view=topics2&move=yes' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

	while (list(, $val) = each ($_POST['move'])) {
	$query="SELECT * FROM onecms_posts WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	
	$me = mysql_query("SELECT * FROM onecms_forums WHERE id = '".$row[fid]."'");
	while($n = mysql_fetch_array($me)) {
	$forum = "$n[name]";
	}

		echo "<input type=\"hidden\" name=\"ida[]\" value=\"$val\"><tr><td><b><center>Topic # ".$val."</b></center></td></tr><tr><td>Topic Name</td><td>$row[subject]</td></tr><tr><td>Forum</td><td>".$forum."</td></tr><tr><td>New Forum</td><td><select name='f2_".$val."'>";
		
	$my = mysql_query("SELECT * FROM onecms_forums WHERE type = 'forum'");
	while($w = mysql_fetch_array($my)) {
	$myu = mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."' AND place = '".$w[id]."'");
	if (mysql_num_rows($myu) > "0") {
	if ($w[id] == $row[fid]) {
	} else {
	echo "<option value='".$w[id]."'>$w[name]</option>";
	}
	}
	}
	echo "</select></td></tr>";
}
		}
}

		echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
	}

if ((($_GET['view'] == "topics2") && ($_GET['edit'] == "yes") && ($_GET['move'] == ""))) {

   while (list(, $val) = each ($_POST['ida'])) {

   $update = mysql_query("UPDATE onecms_posts SET subject = '".$_POST["sub_$val"]."', message = '".addslashes($_POST["mes_$val"])."' WHERE id = '$val'") or die(mysql_error());
   }
if ($update == TRUE) {
    echo "The topic(s) has been updated. <a href=\"boardcp.php?view=topics\">Manage Topics</a>";
}
	}

	if ((($_GET['view'] == "topics2") && ($_GET['move'] == "yes") && ($_GET['edit'] == ""))) {
   while (list(, $val) = each ($_POST['ida'])) {
   $move = mysql_query("UPDATE onecms_posts SET fid = '".$_POST["f2_$val"]."' WHERE id = '$val'") or die(mysql_error());

   $move2 = mysql_query("UPDATE onecms_posts SET fid = '".$_POST["f2_$val"]."' WHERE tid = '$val'") or die(mysql_error());
   }
if (($move2 == TRUE) && ($move == TRUE)) {
    echo "The topic(s) has been moved. <a href=\"boardcp.php?view=topics\">Manage Topics</a>";
}
	}

	// END MANAGE TOPICS, BEGIN MANAGE POSTS

if (((($_GET['view'] == "posts") && ($_GET['edit'] == "") && ($_GET['move'] == "") && ($_GET['add'] == "")))) {
	echo "<form action='boardcp.php?view=posts2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Topic Name</b></td><td><b>Topic</b></td><td><b>Forum</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_posts WHERE type = 'post'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	$forumid = "$row[fid]";
	$topicid = "$row[tid]";
	$name = "$row[subject]";

	$querye = "SELECT * FROM onecms_forums WHERE id = '$forumid'";
	$resulte = mysql_query($querye);
	while($row2 = mysql_fetch_array($resulte)) {
		$forum = "$row2[name]";
	}

	$querye = "SELECT * FROM onecms_posts WHERE id = '$topicid' AND type = 'topic'";
	$resulte = mysql_query($querye);
	while($row2b = mysql_fetch_array($resulte)) {
		$topic = "$row2b[subject]";
	}
    	echo "<tr><td>";
		if ($name == "") {
			echo "<i>No Subject</i>";
		} else {
			print $name;
		}
		echo "</td><td>$topic</td><td>$forum</td><td><input type='checkbox' value='".$row[id]."' name='id[]'></td><td><input type='checkbox' value='".$row[id]."' name='delete[]'></td></tr>";
	}

echo "<tr><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><input type='submit' name='submit' value='Submit >>'></td></tr></form></table><br><br>";
}

if ((($_GET['view'] == "posts2") && ($_GET['edit'] == "") && ($_GET['move'] == ""))) {
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

$delete = mysql_query("DELETE FROM onecms_posts WHERE id = '$val'") or die(mysql_error());
}

if ($delete == TRUE) {
	echo "The post(s) have been deleted. <a href='boardcp.php?view=posts'>Manage Posts</a>";
}
}
}

if (($_GET['view'] == "posts2") && ($_GET['move'] == "")) {
if ($_POST['id']) {

		echo "<form action='boardcp.php?view=posts2&edit=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

	while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_posts WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

		echo "<input type=\"hidden\" name=\"ida[]\" value=\"$val\"><tr><td><b><center>Post # ".$val."</b></center></td></tr><tr><td>Subject</td><td><input type='text' name='sub_".$val."' value='".$row[subject]."'></td></tr><tr><td>Message</td><td><textarea name='mes_".$val."' cols='36' rows='12'>".stripslashes($row[message])."</textarea></td></tr>";
}
		}

		echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
	}
}

if ((($_GET['view'] == "posts2") && ($_GET['edit'] == "yes") && ($_GET['move'] == ""))) {

   while (list(, $val) = each ($_POST['ida'])) {

   $update = mysql_query("UPDATE onecms_posts SET subject = '".$_POST["sub_$val"]."', message = '".addslashes($_POST["mes_$val"])."' WHERE id = '$val'") or die(mysql_error());
   }
if ($update == TRUE) {
    echo "The post(s) have been updated. <a href=\"boardcp.php?view=posts\">Manage Posts</a>";
}
}

echo "</td></tr></table></table>";
}
}
}
}
?>
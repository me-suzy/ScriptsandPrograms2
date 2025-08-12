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

if (((($userlevel == "2") or ($userlevel == "3") or ($userlevel == "4") or ($userlevel == "5")))) {
	echo "Sorry $username, but you do not have permission to manage ads. You are only a $level.";
} else {

echo "<center><a href='a_ad.php?view=ad&add=add1'>Add Group(s)</a> | <a href='a_ad.php?view=ad&add=add11'>Add ad(s)</a> | <a href='a_ad.php'>Manage ads/groups</a></center><br><br>";

if (((($_GET['view'] == "") && ($_GET['edit'] == "") && ($_GET['move'] == "") && ($_GET['add'] == "")))) {
	echo "<form action='a_ad.php?view=ad2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Type</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_ad ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

    	echo "<tr><td>$row[name]</td><td>$row[type]</td><td><input type='checkbox' value='".$row[id]."' name='id[]'></td><td><input type='checkbox' value='".$row[id]."' name='delete[]'></td></tr>";
		}

echo "<tr><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><input type='submit' name='submit' value='Submit >>'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_ad"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_ad.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_ad.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_ad.php?page=$next\">Next>></a>";
}
echo "</center>";
}

if (($_GET['view'] == "ad2") && ($_GET['edit'] == "")) {
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

$delete = mysql_query("DELETE FROM onecms_ad WHERE id = '$val'") or die(mysql_error());
}

if ($delete == TRUE) {
	echo "The group(s)/ad(s) have been deleted. <a href='a_ad.php'>Manage Ads</a>";
}
}
}

if ($_GET['view'] == "ad2") {
if ($_POST['id']) {

		echo "<form action='a_ad.php?view=ad2&edit=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

echo '<SCRIPT LANGUAGE="JavaScript">

<!--  Begin
function Alert () {
alert ("Only select a group if you want the ad to be in rotation with other ads of a group.");
}
// End -->
</SCRIPT>';

	while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_ad WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	$dim = explode("/", $row[dim]);

		echo "<input type=\"hidden\" name=\"ida[]\" value=\"$val\"><tr><td><b><center>";
		
		if ($row[type] == "group") {
			
			echo "Group ";
		} else {
			echo "Ad ";
		}
		
		echo "# ".$val."</b></center></td></tr><tr><td>";
		
		if ($row[type] == "group") {
			
			echo "Group ";
		}
		
		echo "Name</td><td><input type='text' name='name_".$val."' value='".$row[name]."'></td></tr>";

		if (((($row[type] == "image") or ($row[type] == "coding") or ($row[type] == "flash") or ($row[type] == "")))) {
		echo "<tr><td>Group <a href='javascript:Alert();'><b>?</b></a></td><td><select name='group_".$val."'><option value=''>-------</option>";
	
	$queryed="SELECT * FROM onecms_ad WHERE type = 'group'";
	$resulted=mysql_query($queryed);
	while($row2 = mysql_fetch_array($resulted)) {
	if ($row[grp] == $row2[id]) {
	echo "<option value='".$row[grp]."' selected>-- $row2[name] --</option>";
	} else {
	echo "<option value='".$row2[id]."'>$row2[name]</option>";
	}
	}
	
	echo "</select></td></tr><tr><td>Type</td><td><select name='type_".$val."' multiple><option value='".$row[type]."' selected>-- $row[type] --</option><option value='flash'>Flash</option><option value='image'>Image</option><option value='coding'>Coding</option></select></td></tr><tr><td>URL of Image/SWF File</td><td><input type='text' name='coding_".$val."' value='";
	
	if ($row[type] == "coding") {
	} else {
	echo "".$row[coding]."";
	}
	echo "'></td></tr><tr><td>Dimensions (width/height)</td><td><input type='text' name='width_".$val."' value='".$dim[0]."'></td><td>/</td><td><input type='text' name='height_".$val."' value='".$dim[1]."'></td></tr><tr><td>Coding</td><td><textarea cols='30' rows='12' name='codingg_".$val."'>";
	
	if ($row[type] == "coding") {
	echo "".$row[coding]."";
	}
	echo "</textarea></td></tr><tr><td>If visitor is logged in, should they still see the ad?</td><td><select name='user_".$val."' multiple><option value='".$row[user]."' selected>-- ".$row[user]." --</option><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr>";
		}
}
	}

		echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
	}

if (($_GET['view'] == "ad2") && ($_GET['edit'] == "yes")) {

   while (list(, $val) = each ($_POST['ida'])) {
   $dim = "".$_POST["width_$val"]."/".$_POST["height_$val"]."";

   $upd = "UPDATE onecms_ad SET name = '".$_POST["name_$val"]."'";
   
   if ($_POST["type_$val"] == "coding") {
   $upd .= ", coding = '".$_POST["codingg_$val"]."'";
   } else {
   $upd .= ", coding = '".$_POST["coding_$val"]."'";
   }
   $upd .= ", type = '".$_POST["type_$val"]."', grp = '".$_POST["group_$val"]."', dim = '".$dim."', user = '".$_POST["user_$val"]."' WHERE id = '$val'";
   
   $update2 = mysql_query($upd) or die(mysql_error());
   }
if ($update2 == TRUE) {
    echo "The group(s)/ad(s) have been updated. <a href=\"a_ad.php\">Manage Ads</a>";
}
	}
}

if (($_GET['view'] == "ad") && ($_GET['add'] == "add11")) {

echo '<SCRIPT LANGUAGE="JavaScript">

<!--  Begin
function Alert () {
alert ("Only select a group if you want the ad to be in rotation with other ads of a group.");
}
// End -->
</SCRIPT>';

		echo "<form action=\"a_ad.php?view=ad&add=add11\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many ads to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='a_ad.php?view=ad&add=add12' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($val = 0; $val < $_POST['search']; $val = $val+1) {
	echo "<tr><td><b><center>Ad #".$val."</b></center></td></tr>";

	echo "<tr><td>Name</td><td><input type='text' name='name_".$val."'></td></tr><tr><td>Group <a href=\"javascript:Alert();\"><b>?</b></a></td><td><select name='group_".$val."'><option value=''>-------</option>";
	
	$query="SELECT * FROM onecms_ad WHERE type = 'group'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	echo "<option value='".$row[id]."'>$row[name]</option>";
	}
	
	echo "</select></td></tr><tr><td>Type</td><td><select name='type_".$val."' multiple><option value='flash'>Flash</option><option value='image'>Image</option><option value='coding'>Coding</option></select></td></tr><tr><td>URL of Image/SWF File</td><td><input type='text' name='coding_".$val."'></td></tr><tr><td>Dimensions (width/height)</td><td><input type='text' name='width_".$val."'></td><td>/</td><td><input type='text' name='height_".$val."'></td></tr><tr><td>Coding</td><td><textarea cols='30' rows='12' name='coding2_".$val."'></textarea></td></tr><tr><td>If visitor is logged in, should they still see the ad?</td><td><select name='user_".$val."' multiple><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr>";
}
echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr>";
	}
	echo "</form></table>";
}

if (($_GET['view'] == "ad") && ($_GET['add'] == "add12")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   $sql = mysql_query("SELECT * FROM onecms_ad WHERE name = '".$_POST["name_$i"]."'");
   $num = mysql_num_rows($sql);

   if ($num > "0") {
	   echo "Sorry, but the ad name <b>".$_POST["name_$i"]."</b> is already in use. Go back and choose another name.<br><br>";
   } else {
   $dim = "".$_POST["width_$i"]."/".$_POST["height_$i"]."";

   $upd = "INSERT INTO onecms_ad VALUES ('null', '".$_POST["name_$i"]."', '".$_POST["type_$i"]."', '".$_POST["group_$i"]."'";
   
   if ($_POST["type_$i"] == "coding") {
   $upd .= ", '".$_POST["coding2_$i"]."'";
   } else {
   $upd .= ", '".$_POST["coding_$i"]."'";
   }
   $upd .= ", '".$dim."', '".$_POST["user_$i"]."', '')";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The ad(s) have been created. <a href=\"a_ad.php\">Manage Ads</a>";
}
}
}

if (($_GET['view'] == "ad") && ($_GET['add'] == "add1")) {

echo "<form action=\"a_ad.php?view=ad&add=add1\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many groups to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

echo "<form action='a_ad.php?view=ad&add=add2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($val = 0; $val < $_POST['search']; $val = $val+1) {
	echo "<tr><td><b><center>Group #".$val."</b></center></td></tr><tr><td>Name</td><td><input type='text' name='name_".$val."'></td></tr>";
}
	echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr>";
}
	echo "</form></table>";
}

if (($_GET['view'] == "ad") && ($_GET['add'] == "add2")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {

   $sql = mysql_query("SELECT * FROM onecms_ad WHERE name = '".$_POST["name_$i"]."' AND type = 'group'");
   $num = mysql_num_rows($sql);

   if ($num > "0") {
	   echo "Sorry, but the group name <b>".$_POST["name_$i"]."</b> is already in use. Go back and choose another name.<br><br>";
   } else {

   $upd = "INSERT INTO onecms_ad VALUES ('null', '".$_POST["name_$i"]."', 'group', '', '', '', '', '')";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The group(s) have been created. <a href=\"a_ad.php\">Manage Groups</a>";
}
   }
	}
}



	}
	}
	}include ("a_footer.inc");
	?>
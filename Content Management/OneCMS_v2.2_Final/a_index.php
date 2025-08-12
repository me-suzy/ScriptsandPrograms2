<?php
$acat = "yes";
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

echo '<script language="javascript">
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script>';

if ($_GET['view'] == "home") {
	echo "<table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" align=\"center\"><tr>";
	
	$query = mysql_query("SELECT * FROM onecms_cat ORDER BY `id` DESC LIMIT 7");
	while($row = mysql_fetch_array($query)) {
		echo "<td><a href='a_index.php?view=add&cat=".$row[name]."'>Add ".$row[name]."</a></td>";
	}
	
	echo "</tr></table><iframe src='a_chat.php?view=chat' width='100%' frameborder='0' scrolling='yes'></iframe><br><br><form action='a_index.php?view=manage' name='form1' method='post'><table cellspacing=\"1\" cellpadding=\"2\" border=\"0\" align=\"center\"><tr><td valign='top'><center>Most Recent Content</center><table cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Views</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$sql = mysql_query("SELECT * FROM onecms_content ORDER BY `id` DESC LIMIT 10");
while($row = mysql_fetch_array($sql)) {
	$id = "$row[id]";

	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."'>".stripslashes($row[name])."</a></td><td>".$row[stats]."</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
}
echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td></tr></table></form></td><td valign='top'><form action='a_games.php?view=manage' name='form1' method='post'><center>Most Recent Games</center><table cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Game</b></td><td><b>Views</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$sql = mysql_query("SELECT * FROM onecms_games ORDER BY `id` DESC LIMIT 5");
while($row = mysql_fetch_array($sql)) {
	$id = "$row[id]";

	echo "<tr><td><a href='".$gamepart1."".$row[id]."".$gamepart2."'>".stripslashes($row[name])."</a></td><td>".$row[stats]."</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
}
echo "<tr><td><input type='submit' name='submit' value='Submit'></td></tr></form><tr><td><center>Most Recent Posts</center></td></tr><tr><td><b>Subject</b></td><td><b>Views</b></td><td><b><b>Poster</b></td><td><b>Replies</b></td></tr>";

$sql = mysql_query("SELECT * FROM onecms_posts ORDER BY `id` DESC LIMIT 5");
while($row = mysql_fetch_array($sql)) {

$sql2 = mysql_query("SELECT username, id FROM onecms_profile WHERE id = '".$row[uid]."'");
$row2 = mysql_fetch_row($sql2);

$sql3 = mysql_query("SELECT * FROM onecms_posts WHERE tid = '".$row[id]."' AND type = 'post'");
$replies = mysql_num_rows($sql3);

    if ($row[type] == "topic") {
	echo "<tr><td><a href='".$siteurl."/boards.php?t=".$row[id]."'>";
	} else {
	echo "<tr><td><a href='".$siteurl."/boards.php?t=".$row[tid]."#".$row[id]."'>";
	}
	
	echo "".stripslashes($row[subject])."</a></td><td>".$row[stats]."</td><td>";
	
	if ($row[uid] == "") {
		echo "Guest";
	} else {
		
		echo "<a href='".$siteurl."/members.php?action=profile&id=".$row2[1]."'>";
	
	$y2g="SELECT * FROM onecms_boardcp WHERE level = 'admin' AND uid = '".$row[uid]."'";
	$t2g=mysql_query($y2g);
    $c1 = mysql_num_rows($t2g);

	$y2g3="SELECT * FROM onecms_boardcp WHERE level = 'mod' AND uid = '".$row[uid]."'";
	$t2g3=mysql_query($y2g3);
    $c3 = mysql_num_rows($t2g3);

	$y2g2="SELECT * FROM onecms_boardcp WHERE level = 'global' AND uid = '".$row[uid]."'";
	$t2g2=mysql_query($y2g2);
    $c2 = mysql_num_rows($t2g2);
	
	if ($c3 > "0") {
		$color = $color3;
	}

	if ($c2 > "0") {
		$color = $color2;
	}

	if ($c1 > "0") {
		$color = $color1;
	}
	
	if ($color) {
		echo "<font color='".$color."'>".$row2[0]."</font></a>";
	} else {
		echo "".$row2[0]."</a>";
	}
	}

	echo "</td><td>".$replies."</td></tr>";
}

echo "</table></td></tr></table>";
}

if ($_GET['view'] == "search") {

				echo "<title>OneCMS - www.insanevisions.com/onecms > Search</title>";
if ($_GET['cat'] == "") {

echo "<form action='a_index.php?view=search' name='form1' method='post'>";

} else {

	echo "<form action='a_index.php?view=search&cat=".$_GET['cat']."' name='form1' method='post'>";

}

echo '<script language="JavaScript"> 
function openDir(form) { 
var newIndex = form.fieldname.selectedIndex; 
if ( newIndex == 0 ) { 
alert( "Please select a location!"); 
} else { 
cururl = form.fieldname.options[ newIndex ].value; 
window.location.assign( cururl ); 
} 
} 
</script>';

	echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for content</td><td><input type='text' name='search'></td><td><select name='cat2'><option value=\"\">---------</option>";

if ($_GET['cat'] == "") {

} else {

	echo "<option value='".$_GET['cat']."'>".$_GET['cat']."</option>";

}
	
	$query="SELECT * FROM onecms_cat ORDER BY `id` DESC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$name = "$row[name]";

		if ($_GET['cat'] == "") {
		} else {
			if ($name == $_GET['cat']) {
				echo "";
			} else {
    	echo "<option value=\"$name\">$name</option>";
	}
		}
	}
	
	echo "</select></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_index.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Category</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	if ((($userlevel == "3") or ($userlevel == "4") or ($userlevel == "5"))) {

			if ($cat2 == "") {

	$query="SELECT * FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%' AND username = '$username' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$cat5 = "$row[cat]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td>$cat5</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

	} else {

$query="SELECT * FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%' AND cat = '$cat2' AND username = '$username' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td>$cat2</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }
	}
	
} else {

	if ($cat2 == "") {

	$query="SELECT * FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$cat5 = "$row[cat]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td>$cat5</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

	} else {

$query="SELECT * FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%' AND cat = '$cat2' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td>$cat2</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }
	}
}
echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><select name='fieldname' size='1' onChange='openDir(this.form)'><option selected>- Add Content -</option>";

$sql = mysql_query("SELECT * FROM onecms_cat");
while($r = mysql_fetch_array($sql)) {
echo "<option value='a_index.php?view=add&cat=".stripslashes($r[name])."'>".stripslashes($r[name])."</option>";
}

echo "</select></td></tr></form></table><br><br>";

if ($cat2 == "") {
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%'"),0);
} else {
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%' AND cat = '$cat2'"),0);
}

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_index.php?cat=".$_GET['cat']."&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
			
            echo "<a href=\"a_index.php?cat=".$_GET['cat']."&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_index.php?cat=".$_GET['cat']."&page=$next\">Next>></a>";
}
echo "</center>";

}

if (($_GET['view'] == "") && (!$_GET['cat'])) {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Manage Content</title>";

echo '<script language="JavaScript"> 
function openDir(form) { 
var newIndex = form.fieldname.selectedIndex; 
if ( newIndex == 0 ) { 
alert( "Please select a location!"); 
} else { 
cururl = form.fieldname.options[ newIndex ].value; 
window.location.assign( cururl ); 
} 
} 
</script>';

	echo "<form action='a_index.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for content</td><td><input type='text' name='search'></td><td><select name='cat2'><option value=\"\">---------</option>";
	
	$query="SELECT * FROM onecms_cat ORDER BY `id` DESC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$name = "$row[name]";
    	echo "<option value=\"$name\">$name</option>";
	}
	
	echo "</select></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><form action='a_index.php?view=manage' name='form1' method='post'><tr><td><b>Name</b></td><td><b>Category</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	if ((($userlevel == "3") or ($userlevel == "4") or ($userlevel == "5"))) {

	$query="SELECT * FROM onecms_content WHERE username = '$username' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$cat2 = "$row[cat]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td>$cat2</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

} else {

$query="SELECT * FROM onecms_content ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$cat2 = "$row[cat]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td>$cat2</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }
	}

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><select name='fieldname' size='1' onChange='openDir(this.form)'><option selected>- Add Content -</option>";

$sql = mysql_query("SELECT * FROM onecms_cat");
while($r = mysql_fetch_array($sql)) {
echo "<option value='a_index.php?view=add&cat=".stripslashes($r[name])."'>".stripslashes($r[name])."</option>";
}

echo "</select></td></tr></form></table><br><br>";


$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_content"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br >";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_index.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_index.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_index.php?page=$next\">Next>></a>";
}
echo "</center>";

}

if (($_GET['view'] == "") && ($_GET['cat'])) {

				echo "<title>OneCMS - www.insanevisions.com/onecms > Manage ".$_GET['cat']."</title>";

	echo "<form action='a_index.php?view=search&cat=".$_GET['cat']."' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for content</td><td><input type='text' name='search'></td><td><select name='cat2'><option value=\"\">---------</option><option value='".$_GET['cat']."'>".$_GET['cat']."</option>";

		$query="SELECT * FROM onecms_cat ORDER BY `id` DESC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$name = "$row[name]";
		if ($name == $_GET['cat']) {
		} else {
    	echo "<option value=\"$name\">$name</option>";
	}
	}
	
	echo "</select></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

echo "<form action='a_index.php?view=manage&cat=".$_GET['cat']."' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>
   <center><div align=\"center\">";

if ((($userlevel == "3") or ($userlevel == "4") or ($userlevel == "5"))) {

	   if ($_GET['date']) {
		$time = time();
		$time3= date($time, "Ymd");
	   $query="SELECT * FROM onecms_content WHERE cat = '".$_GET['cat']."' AND date = '$time3'  AND username = '$username' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }
   } else {

$query="SELECT * FROM onecms_content WHERE cat = '".$_GET['cat']."' AND username = '$username' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }
   }
} else {

   if ($_GET['date']) {
	   	$time = time();
		$time3= date($time, "Ymd");
	   $query="SELECT * FROM onecms_content WHERE cat = '".$_GET['cat']."' AND date = '$time3' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }
   } else {

$query="SELECT * FROM onecms_content WHERE cat = '".$_GET['cat']."' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }
   }
}

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_index.php?view=add&cat=".$_GET['cat']."\">Add Content</a></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_content WHERE cat = '".$_GET['cat']."'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_index.php?page=$prev&cat=".$_GET['cat']."\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_index.php?page=$i&cat=".$_GET['cat']."\">$i</a>&nbsp;";
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_index.php?page=$next&cat=".$_GET['cat']."\">Next>></a>";
}
echo "</center>";

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
$delete = mysql_query("DELETE FROM onecms_content WHERE id = '$val'") or die(mysql_error());
$delete2 = mysql_query("DELETE FROM onecms_fielddata WHERE id = '$val' AND cat = 'content'") or die(mysql_error());
}
if (($delete == TRUE) && ($delete2 == TRUE)) {
echo "The content has been deleted. <a href=\"a_index.php\">Return to Manage Content Home</a>";
}
}

if ($_GET['view'] == "add") {

$sql = mysql_query("SELECT * FROM onecms_permissions WHERE username = '".$_COOKIE[username]."' AND ".$_GET['cat']." = 'yes'");
$cat = mysql_num_rows($sql);

if ($cat == "0") {
	echo "Sorry, but you do not have permission to add content to the ".$_GET['cat']." category.";
} else {        

echo "<form action=\"a_index.php?view=add&cat=".$_GET['cat']."\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many items to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

if ($_POST['search']) {

echo "<form action='a_index.php?view=add2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\"><input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
	echo "<input type=\"hidden\" name='cat_".$i."' value='".$_GET['cat']."'><tr><td><b><center>Item #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>Require visitor to be member, to view this?</td><td><select name='lev".$i."' multiple><option value='Yes'>Yes</option><option value='No' selected>No</option></select></td></tr>";
	$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = '" . $_GET['cat'] . "' OR cat = '' ORDER BY `id` DESC") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		$name = "$row[name]";
		$type = "$row[type]";

		if ($type == "textarea") {
			echo "<tr><td>$name <a href='javascript:awindow(\"a_help.php?id=$row[id]\", \"\", \"width=200,height=200,scroll=yes\")'><b>[Help]</b></a></td><td><textarea name='$name".$i."' cols=\"30\" rows=\"10\"></textarea></td></tr>";
		}
		if ($type == "textfield") {
			echo "<tr><td>$name <a href='javascript:awindow(\"a_help.php?id=$row[id]\", \"\", \"width=200,height=200,scroll=yes\")'><b>[Help]</b></a></td><td><input type=\"text\" name='$name".$i."' id='$name".$i."'></td></tr>";
		}
		if ($type == "games") {
			echo "<tr><td>".$name."</td><td><select name='".$name."_".$i."'><option value=''>--------</option>";

	$sqla = mysql_query("SELECT * FROM onecms_games ORDER BY `name` ASC") or die(mysql_error());
	while($row2a = mysql_fetch_array($sqla)) {
			echo "<option value='".$row2a[id]."'>".$row2a[name]."</option>";
	}
	echo "</select></td></tr>";
		}

	if ($type == "system") {
	echo "<tr><td>".$name."</td><td><select name='".$name."_".$i."[]' multiple><option value='' selected>--------</option>";

	$sqlb = mysql_query("SELECT * FROM onecms_systems ORDER BY `name` ASC") or die(mysql_error());
	while($row2b = mysql_fetch_array($sqlb)) {
			echo "<option value='".$row2b[id]."'>".$row2b[name]."</option>";
	}
	echo "</select></td></tr>";
		}

	}
	if ($wysiwyg == "False") {
	echo "<tr><td>Automatically Break lines?</td><td><input type='checkbox' name='autobr_".$i."' value='yes' checked></td></tr>";
	}
	}
	echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td><td><input type='reset' name='reset' value='Reset'></td></tr></form></table>";
	}
}
}

if ($_GET['view'] == "add2") {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   if ($_POST["name_$i"]) {
   while (list(, $val) = each ($_POST["systems_$i"])) {

   $upd = "INSERT INTO onecms_content VALUES ('null', '".addslashes($_POST["name_$i"])."', '".$_POST["cat_$i"]."', '".$username."', '".time()."'";

   if ($ver == "yes") {
   $upd .= ", '1', '', '0', '".$_POST["lev$i"]."'";
   } else {
   $upd .= ", '0', '', '0', '".$_POST["lev$i"]."'";
   }

   $upd .= ", '".addslashes($_POST["games_$i"])."', '".$val."')";

$r = mysql_query($upd) or die(mysql_error());

$fetch = mysql_fetch_row(mysql_query("SELECT id FROM onecms_content WHERE cat = '".$_POST["cat_$i"]."' ORDER BY `id` DESC"));
   	
$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = '' OR cat = '".$_POST["cat_$i"]."' ORDER BY `id`") or die(mysql_error());
while($row = mysql_fetch_array($query)) {
$name = "$row[name]";

if (($name == "games") && ($name == "systems")) {
} else {
   
if (($_POST["autobr_$i"]) && ($row[type] == "textarea")) {
$autobr = preg_replace("/<br>\n/","\n",addslashes($_POST["$name$i"]));
$autobr = preg_replace("/<br \/>\n/","\n",addslashes($_POST["$name$i"]));
$autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br>\n",addslashes($_POST["$name$i"]));
} else {
$autobr = addslashes($_POST["$name$i"]);
}
   
   if (($row[cat] == "games") or ($row[cat] == "users")) {
   } else {
   if ($row[name] == "albums") {
   if (($row[name] == "albums") && ($_POST["albums_$i"])) {
   mysql_query("INSERT INTO onecms_fielddata VALUES ('null', '".$name."', '".addslashes($_POST["albums_$i"])."', '".$fetch[0]."', 'content')");
   }
   } else {
   if ($autobr) {
   mysql_query("INSERT INTO onecms_fielddata VALUES ('null', '".$name."', '".$autobr."', '".$fetch[0]."', 'content')");
   }
   }
   }
   }
   }


   }
   }
   }
if ($r == TRUE) {
	echo "The content has been created. <a href=\"a_index.php\">Return to Manage Content Home</a>";
}
}

if ((($_GET['view'] == "manage") && ($_POST['delete'] == "") && ($_GET['edit'] == ""))) {

	echo "<form action='a_index.php?view=manage&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_content WHERE id = '$val'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
		$nameav = "$row2[name]";
		$cat = "$row2[cat]";
		$namea = stripslashes($nameav);

	echo "<input type=\"hidden\" name=\"id[]\" value=\"$val\"><input type=\"hidden\" name=\"cat_$val\" value=\"$cat\"><tr><td><b><center>Item #".$val."</b></center></td></tr><tr><td><b>Name</b></td><td><input type='text' name=\"name_$val\" value=\"$namea\"></td></tr><tr><td>Require visitor to be member, to view this?</td><td><select name='lev_".$val."' multiple><option value='".$row2[lev]."' selected>-- ".$row2[lev]." --</option><option value='Yes'>Yes</option><option value='No'>No</option></select></td></tr>";

	$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = '".$cat."' OR cat = '' ORDER BY `id` DESC") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		$name = "$row[name]";
		$type = "$row[type]";

		$dataa = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE name = '".$name."' AND id2 = '".$row2[id]."' AND cat = 'content'"));
		$data = stripslashes($dataa[0]);

		if ($type == "textarea") {
			echo "<tr><td>$name <a href='javascript:awindow(\"a_help.php?id=$row[id]\", \"\", \"width=200,height=200,scroll=yes\")'><b>[Help]</b></a></td><td><textarea name='".$name."".$val."' cols=\"30\" rows=\"10\">".$data."</textarea></td></tr>";
		}
		if ($type == "textfield") {
			echo "<tr><td>$name <a href='javascript:awindow(\"a_help.php?id=$row[id]\", \"\", \"width=200,height=200,scroll=yes\")'><b>[Help]</b></a></td><td><input type=\"text\" name='".$name."".$val."' value='".$data."'></td></tr>";
		}
			if ($type == "games") {
			$data = $row2[games];
			$woo = mysql_fetch_row(mysql_query("SELECT name FROM onecms_games WHERE id = '".$data."'"));

			echo "<tr><td>".$name."</td><td><select name='".$name."".$val."'><option value=''>--------</option>";

			if ($woo[0]) {
			echo "<option value='".$data."' selected>-- ".$woo[0]." --</option>";
			}

	$sql = mysql_query("SELECT * FROM onecms_games ORDER BY `name` ASC") or die(mysql_error());
	while($row23 = mysql_fetch_array($sql)) {
			echo "<option value='".$row23[id]."'>".$row23[name]."</option>";
	}
	echo "</select></td></tr>";
		}

			if ($type == "system") {
			$data = $row2[systems];
			$woo2 = mysql_fetch_row(mysql_query("SELECT name FROM onecms_systems WHERE id = '".$data."'"));

			echo "<tr><td>".$name."</td><td><select name='".$name."".$val."'><option value=''>--------</option>";

			if ($woo2[0]) {
			echo "<option value='".$data."' selected>-- ".$woo2[0]." --</option>";
			}

	$sql1 = mysql_query("SELECT * FROM onecms_systems ORDER BY `name` ASC") or die(mysql_error());
	while($row24 = mysql_fetch_array($sql1)) {
			echo "<option value='".$row24[id]."'>".$row24[name]."</option>";
	}
	echo "</select></td></tr>";
		}
	}
		if ($wysiwyg == "False") {
	echo "<tr><td>Automatically Break lines?</td><td><input type='checkbox' name='autobr_".$val."' value='yes'></td></tr>";
	}
	}
	}
	echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
}

if (($_GET['view'] == "manage") && ($_GET['edit'] == "2")) {

   while (list(, $val) = each ($_POST['id'])) {

   $r = mysql_query("UPDATE onecms_content SET name = '".addslashes($_POST["name_$val"])."', date = '".time()."', lev = '".$_POST["lev_$val"]."', games = '".addslashes($_POST["games$val"])."', systems = '".addslashes($_POST["systems$val"])."' WHERE id = '".$val."'") or die(mysql_error());

   $result = mysql_query("SELECT * FROM onecms_fields WHERE cat = '".$_POST["cat_$val"]."' OR cat = '' ORDER BY `id` DESC");
   while ($row = mysql_fetch_array($result)) {
   $name = "$row[name]";

   if (($name == "games") && ($name == "systems")) {
   } else {

   if (($_POST["autobr_$val"]) && ($row[type] == "textarea")) {
   $autobr = preg_replace("/<br>\n/","\n",addslashes($_POST["$name$val"]));
   $autobr = preg_replace("/<br \/>\n/","\n",addslashes($_POST["$name$val"]));
   $autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br>\n",addslashes($_POST["$name$val"]));
   } else {
   $autobr = addslashes($_POST["$name$val"]);
   }
    
   if ($_POST["$name$val"]) {
   mysql_query("UPDATE onecms_fielddata SET data = '".addslashes($_POST["$name$val"])."' WHERE id2 = '".$val."' AND cat = 'content' AND name = '".$name."'") or die(mysql_error());
   }
   }
   }

   }
if ($r == TRUE) {
    echo "The content has been updated. <a href=\"a_index.php\">Return to Content Manager Home</a>";
}
}
}

if ($_GET['view'] == "ver") {

	if ($ver == "yes") {
	echo "Sorry, but your not allowed to verify content.";
} else {

			echo "<title>OneCMS - www.insanevisions.com/onecms > Verify Content</title>";

				echo "<form action='a_index.php?view=search2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for content</td><td><input type='text' name='search'></td><td><select name='cat2'><option value=\"\">---------</option>";
	
	$query="SELECT * FROM onecms_cat ORDER BY `id` DESC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$name = "$row[name]";
    	echo "<option value=\"$name\">$name</option>";
	}
	
	echo "</select></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

			echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><form action='a_index.php?view=ver2' name='form1' method='post'><tr><td><b>Name</b></td><td><b>Category</b></td><td><b><b>Verify</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";


$query="SELECT * FROM onecms_content WHERE ver = '1' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		if ($row[postpone] == "") {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$cat2 = "$row[cat]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td>$cat2</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
		}
			}

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";


$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_content WHERE ver = '1'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$next\">Next>></a>";
}
echo "</center>";

}
}

if ($_GET['view'] == "ver2") {

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
$delete = mysql_query("DELETE FROM onecms_content WHERE id = '$val'") or die(mysql_error());
$delete2 = mysql_query("DELETE FROM onecms_fielddata WHERE id2 = '$val' AND cat = 'content'") or die(mysql_error());
}
}

if ($_POST['id']) {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to verify this content?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['id'])) {
$upd = "UPDATE onecms_content SET ver = '0' WHERE id = '$val'";
}
$verify = mysql_query($upd) or die(mysql_error());
}

if (($verify == TRUE) or ($delete == TRUE)) {
echo "Content has been verified/deleted";
}
}

if ($_GET['view'] == "search2") {

				echo "<title>OneCMS - www.insanevisions.com/onecms > Verify Content > Search</title>";

				if ($_GET['cat'] == "") {

echo "<form action='a_index.php?view=search2' name='form1' method='post'>";

} else {

	echo "<form action='a_index.php?view=search2&cat=".$_GET['cat']."' name='form1' method='post'>";

}
	echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for unverified content</td><td><input type='text' name='search'></td><td><select name='cat2'><option value=\"\">---------</option>";

if ($_GET['cat'] == "") {

} else {

	echo "<option value='".$_GET['cat']."'>".$_GET['cat']."</option>";

}
	
	$query="SELECT * FROM onecms_cat ORDER BY `id` DESC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$name = "$row[name]";

		if ($_GET['cat'] == "") {
		} else {
			if ($name == $_GET['cat']) {
				echo "";
			} else {
    	echo "<option value=\"$name\">$name</option>";
	}
		}
	}
	
	echo "</select></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_index.php?view=ver' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Category</b></td><td><b><b>Verify</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%' AND ver = '1' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$cat5 = "$row[cat]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td>$cat5</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }
echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";

if ($cat2 == "") {
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%' AND ver = '1'"),0);
} else {
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%' AND cat = '$cat2' AND ver = '1'"),0);
}

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$next\">Next>></a>";
}
echo "</center>";

}
}
}
if (($_GET['view'] == "postpone") && ($_GET['edit'] == "1")) {

	echo "<form action='a_index.php?view=postpone&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_content WHERE id = '$val'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
		$nameav = "$row2[name]";
		$namea = stripslashes($nameav);

	echo "<input type=\"hidden\" name=\"id[]\" value=\"$val\"><tr><td><b><center>Item #".$val."</b></center></td></tr>";
	echo "<tr><td>Day:</td><td><select name='day_".$val."'>";
for ($i = 01; $i <= 31; $i++) {
	echo "<option value=\"$i\">$i</option>";
}
echo "</select></td></tr>";

echo "<tr><td>Month:</td><td><select name='month_".$val."'>";
for ($i = 01; $i <= 12; $i++) {
	echo "<option value=\"$i\">$i</option>";
}
echo "</select></td></tr>";
echo "<tr><td>Year:</td><td><select name='year_".$val."'>";
for ($i = 2005; $i <= 2012; $i++) {
	echo "<option value=\"$i\">$i</option>";
}
echo "</select></td></tr><tr><td>Hour:</td><td><select name='hour_".$val."'>";
for ($i = 00; $i <= 24; $i++) {
	echo "<option value=\"$i\">$i</option>";
}
echo "</select></td></tr><tr><td>Minutes:</td><td><select name='minutes_".$val."'>";
for ($i = 00; $i <= 59; $i++) {
	echo "<option value=\"$i\">$i</option>";
}
echo "</select></td></tr>";
	}
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
}

if (($_GET['view'] == "postpone") && ($_GET['edit'] == "2")) {

   while (list(, $val) = each ($_POST['id'])) {
   $postponeok = "".$_POST["year_$val"]."";

   if ($_POST["month_$val"] < "10") {
	   $postponeok4 = "0".$_POST["month_$val"]."";
   } else {
	   $postponeok4 = "".$_POST["month_$val"]."";
   }

   if ($_POST["day_$val"] < "10") {
	   $postponeok5 = "0".$_POST["day_$val"]."";
   } else {
	   $postponeok5 = "".$_POST["day_$val"]."";
   }
   
   if ($_POST["hour_$val"] < "10") {
	   $postponeok2 = "0".$_POST["hour_$val"]."";
   } else {
	   $postponeok2 = "".$_POST["hour_$val"]."";
   }
   
   if ($_POST["minutes_$val"] < "10") {
	   $postponeok3 = "0".$_POST["minutes_$val"]."";
   } else {
	   $postponeok3 = "".$_POST["minutes_$val"]."";
   }
   $upd = "UPDATE onecms_content SET postpone = '".$postponeok."".$postponeok4."".$postponeok5."".$postponeok2."".$postponeok3."', ver = '1' WHERE id = '$val'";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
    echo "The content has been postponed. <a href=\"a_index.php?view=postpone\">Return to Postpone Manager Home</a>";
}
}

if (($_GET['view'] == "postpone") && ($_GET['edit'] == "")) {
			echo "<title>OneCMS - www.insanevisions.com/onecms > Postpone Content</title>";

				echo "<form action='a_index.php?view=search3' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for content</td><td><input type='text' name='search'></td><td><select name='cat2'><option value=\"\">---------</option>";
	
	$query="SELECT * FROM onecms_cat ORDER BY `id` DESC";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$name = "$row[name]";
    	echo "<option value=\"$name\">$name</option>";
	}
	
	echo "</select></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

			echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><form action='a_index.php?view=postpone&edit=1' name='form1' method='post'><tr><td><b>Name</b></td><td><b>Category</b></td><td><b><b>Update</b></td></tr><center><div align=\"center\">";


$query="SELECT * FROM onecms_content ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$cat2 = "$row[cat]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."' target='popup'>$name</a></td><td>$cat2</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td></tr>";
			}

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";


$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_content"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$next\">Next>></a>";
}
echo "</center>";

}


include ("a_footer.inc");
?>
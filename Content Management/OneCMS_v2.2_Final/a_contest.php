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
echo "<center><a href='a_contest.php'>Manage Contest(s)</a> | <a href='a_contest.php?view=add'>Add Contest(s)</a></center><br><br>";

if ((($userlevel == "4") or ($userlevel == "5") or ($userlevel == "6"))) {
	echo "Sorry $username, but you do not have permission to manage contests. You are only a $level.";
} else {

if ($_GET['view'] == "") {

	echo "<form action='a_contest.php?view=manage2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Type</b></td><td><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_contest ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
		if ($row[type] == "contest") {
    	echo "<tr><td><a href='contest.php?id=".$row[id]."' target='popup'>$name</a></td>";
		} else {
		echo "<tr><td>$name</td>";
		}
		echo "<td>$row[type]</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_contest"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_contest.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_contest.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_contest.php?page=$next\">Next>></a>";
}
echo "</center>";

}

if ((($_GET['view'] == "manage2") && ($_POST['delete']) && ($_POST['id'] == ""))) {

echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {
	$delete = mysql_query("DELETE FROM onecms_contest WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "The contest(s)/entry(s) have been deleted. <a href=\"a_contest.php\">Manage Contests</a>";
}
}

if ($_GET['view'] == "add") {
		echo "<form action=\"a_contest.php?view=add\" name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many contests to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

		echo "<form action='a_contest.php?view=add2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {

echo "<tr><td><b><center>Contest #".$i."</b></center></td></tr><tr><td>Name</td><td><input type='text' name='name_".$i."'></td></tr><tr><td>Description</td><td><textarea name='des_".$i."' cols='30' rows='10'></textarea></td></tr><tr><td>Rules</td><td><textarea name='rules_".$i."' cols='30' rows='10'></textarea></td></tr><tr><td>Require Membership to enter?</td><td><select name='priv_".$i."' multiple><option value='yes'>Yes</option><option value='no'>No</option></select></td></tr><tr><td>How many required posts to enter?</td><td><input type='text' name='posts_".$i."'></td></tr><tr><td><b><center>Ending Date</b></center></td></tr>";

	echo "<tr><td>Day:</td><td><select name='day_".$i."'>";
for ($op = 01; $op <= 31; $op++) {
	echo "<option value=\"$op\">$op</option>";
}
echo "</select></td></tr>";

echo "<tr><td>Month:</td><td><select name='month_".$i."'>";
for ($op = 01; $op <= 12; $op++) {
	echo "<option value=\"$op\">$op</option>";
}
echo "</select></td></tr>";
echo "<tr><td>Year:</td><td><select name='year_".$i."'>";
for ($op = 2005; $op <= 2012; $op++) {
	echo "<option value=\"$op\">$op</option>";
}
echo "</select></td></tr><tr><td>Hour:</td><td><select name='hour_".$i."'>";
for ($op = 00; $op <= 23; $op++) {
	echo "<option value=\"$op\">$op</option>";
}
echo "</select></td></tr><tr><td>Minutes:</td><td><select name='minutes_".$i."'>";
for ($op = 00; $op <= 59; $op++) {
	echo "<option value=\"$op\">$op</option>";
}
echo "</select></td></tr>";
}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr>";
}
echo "</form></table>";
}

if ($_GET['view'] == "add2") {

	for($i = 0; $i < $_POST['s']; $i = $i+1) {

	   $postponeok = "".$_POST["year_$i"]."";

   if ($_POST["month_$i"] < "10") {
	   $postponeok4 = "0".$_POST["month_$i"]."";
   } else {
	   $postponeok4 = "".$_POST["month_$i"]."";
   }

   if ($_POST["day_$i"] < "10") {
	   $postponeok5 = "0".$_POST["day_$i"]."";
   } else {
	   $postponeok5 = "".$_POST["day_$i"]."";
   }
   
   if ($_POST["hour_$i"] < "10") {
	   $postponeok2 = "0".$_POST["hour_$i"]."";
   } else {
	   $postponeok2 = "".$_POST["hour_$i"]."";
   }
   
   if ($_POST["minutes_$i"] < "10") {
	   $postponeok3 = "0".$_POST["minutes_$i"]."";
   } else {
	   $postponeok3 = "".$_POST["minutes_$i"]."";
   }

    $upd = "INSERT INTO onecms_contest VALUES ('null', '".$_POST["name_$i"]."', '".addslashes($_POST["des_$i"])."', '".$postponeok."".$postponeok4."".$postponeok5."".$postponeok2."".$postponeok3."', 'contest', '".addslashes($_POST["rules_$i"])."', '".$_POST["priv_$i"]."', '".$_POST["posts_$i"]."', '', '', '')";
	$d = mysql_query($upd) or die(mysql_error());
	}

	if ($d == TRUE) {
		echo "The contest(s) have been created. <a href='a_contest.php'>Manage Contests</a>";
	}
}
 
if (((($_GET['view'] == "manage2") && ($_POST['id']) && ($_POST['delete'] == "") && ($_GET['edit'] == "")))) {

		echo "<form action='a_contest.php?view=manage2&edit=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

	while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_contest WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

		echo "<input type=\"hidden\" name=\"ida[]\" value=\"$val\"><tr><td><b><center>Contest # ".$val."</b></center></td></tr><tr><td>Name</td><td><input type='text' name='name_".$val."' value='".$row[name]."'></td></tr><tr><td>Description</td><td><textarea name='des_".$val."' cols='30' rows='10'>".stripslashes($row[des])."</textarea></td></tr><tr><td>Rules</td><td><textarea name='rules_".$val."' cols='30' rows='10'>".stripslashes($row[rules])."</textarea></td></tr><tr><td>Require Membership to enter?</td><td><select name='priv_".$val."' multiple><option value='".$row[priv]."' selected>-- ".$row[priv]."</option><option value='yes'>Yes</option><option value='no'>No</option></select></td></tr><tr><td>How many required posts to enter?</td><td><input type='text' name='posts_".$val."' value='".$row[posts]."'></td></tr><tr><td><b><center>New Ending Date</b></center></td></tr>";

	echo "<tr><td>Day:</td><td><select name='day_".$val."'>";
for ($op = 01; $op <= 31; $op++) {
	echo "<option value=\"$op\">$op</option>";
}
echo "</select></td></tr>";

echo "<tr><td>Month:</td><td><select name='month_".$val."'>";
for ($op = 01; $op <= 12; $op++) {
	echo "<option value=\"$op\">$op</option>";
}
echo "</select></td></tr>";
echo "<tr><td>Year:</td><td><select name='year_".$val."'>";
for ($op = 2005; $op <= 2012; $op++) {
	echo "<option value=\"$op\">$op</option>";
}
echo "</select></td></tr><tr><td>Hour:</td><td><select name='hour_".$val."'>";
for ($op = 00; $op <= 23; $op++) {
	echo "<option value=\"$op\">$op</option>";
}
echo "</select></td></tr><tr><td>Minutes:</td><td><select name='minutes_".$val."'>";
for ($op = 00; $op <= 59; $op++) {
	echo "<option value=\"$op\">$op</option>";
}
echo "</select></td></tr><tr><td>Select this if your are keeping the same date</td><td><input type='checkbox' name='check_".$val."' checked></tr><tr>";
}
	}
			echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Submit Changes\"></td></tr></form></table>";
	
	}

if (($_GET['view'] == "manage2") && ($_GET['edit'] == "yes")) {

	   while (list(, $val) = each ($_POST['ida'])) {
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
   $upd = "UPDATE onecms_contest SET name = '".$_POST["name_$val"]."', des = '".addslashes($_POST["des_$val"])."', rules = '".addslashes($_POST["rules_$val"])."', priv = '".$_POST["priv_$val"]."', posts = '".$_POST["posts_$val"]."' ";
   
   if ($_POST["check_$val"] == "") {
   $upd .= ", email = '".$postponeok."".$postponeok4."".$postponeok5."".$postponeok2."".$postponeok3."' ";
   }
   
   $upd .= "WHERE id = '$val'";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
    echo "The contest(s) have been updated. <a href=\"a_contest.php\">Manage Contests</a>";
}
}

}
}
}
}
include ("a_footer.inc");
?>
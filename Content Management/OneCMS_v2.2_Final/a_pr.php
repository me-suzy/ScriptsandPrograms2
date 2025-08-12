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
	echo "Sorry $username, but you do not have permission to the PR Manager. You are only a $level.";
} else {

	echo "<center><a href=\"a_pr.php?view=add\">Add Company</a> | <a href=\"a_pr.php\">Manage Companies</a> | <a href=\"a_pr.php?view=email&step=1\">E-mail Company</a></center><br><br>";

if ($_GET['view'] == "search") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > PR Manager > Search</title>";

	echo "<form action='a_pr.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for company</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_pr.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Company Name</b></td><td><b>Type</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_pr WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$ppart1."".$row[id]."".$ppart2."' target='popup'>$name</a></td><td>$row[type]</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_pr.php?view=add\">Add Company</a></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_pr WHERE name LIKE '%" . $_POST['search'] . "%'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_pr.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_pr.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_pr.php?page=$next\">Next>></a>";
}
echo "</center>";
}

if ($_GET['view'] == "") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > PR Manager</title>";

	echo "<form action='a_pr.php?view=search'  method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for company</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_pr.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Company Name</b></td><td><b>Type</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_pr ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td><a href='".$ppart1."".$row[id]."".$ppart2."' target='popup'>$name</a></td><td>$row[type]</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_pr.php?view=add\">Add company</a></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_pr"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_pr.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_pr.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_pr.php?page=$next\">Next>></a>";
}
echo "</center>";

}

if (($_GET['view'] == "add") && ($_GET['add'] == "")) {
echo "<form action=\"a_pr.php?view=add\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many companies to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form><form action='a_pr.php?view=add2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    if ($_POST['search']) {

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
	echo "<tr><td><b><center>Company #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."'></td></tr><tr><td>E-mail</td><td><input type=\"text\" name='email_".$i."'></td></tr><tr><td>First Name</td><td><input type=\"text\" name='fname_".$i."'></td></tr><tr><td>Last Name</td><td><input type=\"text\" name='lname_".$i."'></td></tr><tr><td>Type</td><td><select name='type_".$i."' multiple><option value='publisher'>Publisher</option><option value='developer'>Developer</option></select></td></tr><tr><td>Site URL</td><td><input type='text' name='site_".$i."'></td></tr><tr><td>Description</td><td><textarea name='des_".$i."' cols='36' rows='12'></textarea></td></tr>";
}
echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr>";
}
echo "</form></table>";
}

   if (($_GET['view'] == "add2") && ($_GET['add'] == "")) {
   for($i = 0; $i < $_POST['s']; $i = $i+1) {
   if ($_POST["type_$i"]) {
   $upd = "INSERT INTO onecms_pr VALUES ('null', '".$_POST["name_$i"]."', '".$_POST["type_$i"]."', '".$_POST["fname_$i"]."', '".$_POST["lname_$i"]."', '".$_POST["email_$i"]."', '".$_POST["site_$i"]."', '".addslashes($_POST["des_$i"])."')";
   $r = mysql_query($upd) or die(mysql_error());
   } else {
   $prquery = "yep";
   echo "<b>Type</b> - field empty<br>";
   }
   }
if ($prquery == "") {
echo "The company(s) have been entered. <a href=\"a_pr.php\">Return to PR Manager Home</a>";
}
}

	if ((($_GET['view'] == "manage") && ($_POST['delete'] == "") && ($_GET['edit'] == ""))) {

	echo "<form action='a_pr.php?view=manage&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_pr WHERE id = '$i'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {
                 
    echo "<input type='hidden' name='id[]' value='".$i."'><input type='hidden' name='name2_".$i."' value='".$row2['name']."'><tr><td><b><center>Company #".$i."</b></center></td></tr><tr><td>Name</td><td><input type=\"text\" name='name_".$i."' value='".$row2['name']."'></td></tr><tr><td>E-mail</td><td><input type=\"text\" name='email_".$i."' value='".$row2['email']."'></td></tr><tr><td>First Name</td><td><input type=\"text\" name='fname_".$i."' value='".$row2['fname']."'></td></tr><tr><td>Last Name</td><td><input type=\"text\" name='lname_".$i."' value='".$row2['lname']."'></td></tr><tr><td>Type</td><td><select name='type_".$i."' multiple><option value='".$row2['type']."' selected>-- ".$row2['type']."</option><option value='publisher'>Publisher</option><option value='developer'>Developer</option></select></td></tr><tr><td>Site URL</td><td><input type='text' name='site_".$i."' value='".$row2[site]."'></td></tr><tr><td>Description</td><td><textarea name='des_".$i."' cols='36' rows='12'>".stripslashes($row2[des])."</textarea></td></tr>";
}
}
echo "<tr><td><input type=\"submit\" name=\"Modify\" value=\"Modify\"></td></tr></form></table>";
}

  if (($_GET['view'] == "manage") && ($_GET['edit'] == "2")) {

   while (list(, $i) = each ($_POST['id'])) {
   if ($_POST["type_$i"]) {
   $upd = "UPDATE onecms_pr SET name = '".$_POST["name_$i"]."', type = '".$_POST["type_$i"]."', fname = '".$_POST["fname_$i"]."', lname = '".$_POST["lname_$i"]."', email = '".$_POST["email_$i"]."', des = '".addslashes($_POST["des_$i"])."', site = '".$_POST["site_$i"]."' WHERE id = '".$i."'";
   $r = mysql_query($upd) or die(mysql_error());
   } else {
   $prquery = "yep";
   echo "<b>Type</b> - field empty<br>";
   }

if ($_POST["type_$i"] == "publisher") {
$upd2 = "UPDATE onecms_games SET name = '".$_POST["name_$i"]."' WHERE publisher = '".$_POST["name2_$i"]."'";
$r2 = mysql_query($upd2) or die(mysql_error());
} else {
$upd2 = "UPDATE onecms_games SET name = '".$_POST["name_$i"]."' WHERE developer = '".$_POST["name2_$i"]."'";
   $r2 = mysql_query($upd2) or die(mysql_error());
}

if ($r2 == FALSE) {
$prquery2 = "error";
}

}
if (($prquery == "") && ($prquery2 == "")) {
echo "The company(s) have been updated. <a href=\"a_pr.php\">Return to PR Manager Home</a>";
}
}
if (($_GET['view'] == "manage") && ($_POST['id'] == "")) {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {
	$delete = mysql_query("DELETE FROM onecms_pr WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
echo "The company(s) have been deleted. <a href=\"a_pr.php\">Return to PR Manager Home</a>";
}
}
if (($_GET['view'] == "email") && ($_GET['step'] == "1")) {
	echo "<form action='a_pr.php?view=email&step=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

		echo "<tr><td><b>Subject:</b></td><td><input type='text' name='sub'></td></tr><tr><td><b>To:</b></td><td><select name='to'>";

	$query="SELECT * FROM onecms_pr";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		echo "<option value=\"$row[email]\">$row[name]</option>";
	}
	echo "</select></td></tr><tr><td><b>Message:</b></td><td><textarea name='msg' cols='30' rows='12'></textarea></td></tr>";
	echo "<tr><td><input type=\"submit\" name=\"Send\" value=\"Send\"></td></tr></form></table>";
}
if (($_GET['view'] == "email") && ($_GET['step'] == "2")) {
	$sub = stripslashes($_POST['sub']);
	$msg = stripslashes($_POST['msg']);
	$headers .= "From: $name <$email>\r\n";
    $headers .= "Cc: $email\r\n";
    $headers .= "Bcc: $email\r\n";
	$email = mail("".$_POST['to']."", "$sub", "$msg", "$headers");
	if ($email == TRUE) {
		echo "The email has been sent to <b>".$_POST['to'].". <a href='a_pr.php'>PR Manager Home</a>";
	}
}
}
}
}
}include ("a_footer.inc");
?>
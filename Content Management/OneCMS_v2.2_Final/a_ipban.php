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
	echo "Sorry $username, but you do not have permission to manage systems. You are only a $level.";
} else {

	echo "<center><a href='a_ipban.php'>Manage Banned IP's</a> | <a href='a_ipban.php?view=add'>Ban IP's</a></center><br><br>";

	if ($_GET['view'] == "") {

				echo "<title>OneCMS - www.insanevisions.com/onecms > IP Banner</title>";

	echo "<form action='a_ipban.php?view=search' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for banned ip</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_ipban.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>IP</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_ipban ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[ip]";
		$name = stripslashes($name2);

    	echo "<tr><td>$name</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_ipban"),0);

$total_ipban = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_ipban; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_ipban){
    $next = ($page + 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$next\">Next>></a>";
}
echo "</center>
    </span>
  </div></div></center>";

}

if (($_GET['view'] == "add") && ($_GET['add'] == "")) {

echo "<form action=\"a_ipban.php?view=add\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many ip's to ban?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

    if ($_POST['search']) {

echo "<form action='a_ipban.php?view=add&add=yes' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\"><input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($i = 0; $i < $_POST['search']; $i = $i+1) {
    echo "<tr><td><b><center>IP #".$i."</b></center></td></tr><tr><td>IP</td><td><input type=\"text\" name='ip_".$i."'></td></tr><tr><td>Ban from Forums?</td><td><select name='forums_".$i."' multiple><option value='yes'>Yes</option><option value='no'>No</option></select></td></tr><tr><td>Ban from Site?</td><td><select name='site_".$i."' multiple><option value='yes'>Yes</option><option value='no'>No</option></select></td></tr><tr><td>Ban from Admin CP?</td><td><select name='cp_".$i."' multiple><option value='yes'>Yes</option><option value='no'>No</option></select></td></tr>";
}
	}
echo "<tr><td><input type=\"submit\" name=\"Add\" value=\"Add\"></td></tr></form></table>";
}

if (($_GET['view'] == "add") && ($_GET['add'] == "yes")) {

   for($i = 0; $i < $_POST['s']; $i = $i+1) {

   $upd = "INSERT INTO onecms_ipban VALUES ('null', '".$_POST["ip_$i"]."', '".$_POST["forums_$i"]."', '".$_POST["site_$i"]."', '".$_POST["cp_$i"]."', '".time()."')";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The ip(s) have been banned. <a href=\"a_ipban.php\">Manage Banned IP's</a>";
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
	$delete = mysql_query("DELETE FROM onecms_ipban WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "The banned ip(s) have been deleted. <a href=\"a_ipban.php\">Manage Banned </a>";
}
}

if (($_GET['view'] == "add") && ($_GET['add'] == "no")) {

   while (list(, $i) = each ($_POST['id'])) {

   $upd = "UPDATE onecms_ipban SET ip = '".$_POST["ip_$i"]."', site = '".$_POST["site_$i"]."', forums = '".$_POST["forums_$i"]."', cp = '".$_POST["cp_$i"]."', date = '".time()."' WHERE id = '".$i."'";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The banned ip(s) have been updated. <a href='a_ipban.php'>Mange Banned IP's</a>";
}
}

if ((($_GET['view'] == "manage") && ($_POST['delete'] == "") && ($_GET['edit'] == ""))) {

	echo "<form action='a_ipban.php?view=add&add=no' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

    while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_ipban WHERE id = '$i'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {

 echo "<input type='hidden' name='id[]' value='".$row2[id]."'><tr><td><b><center>IP #".$i."</b></center></td></tr><tr><td>IP</td><td><input type=\"text\" name='ip_".$i."' value='".$row2[ip]."'></td></tr><tr><td>Ban from Forums?</td><td><select name='forums_".$i."' multiple><option value='".$row2[forums]."' selected>-- ".$row2[forums]." --</option><option value='yes'>Yes</option><option value='no'>No</option></select></td></tr><tr><td>Ban from Site?</td><td><select name='site_".$i."' multiple><option value='".$row2[site]."' selected>-- ".$row2[site]." --</option><option value='yes'>Yes</option><option value='no'>No</option></select></td></tr><tr><td>Ban from Admin CP?</td><td><select name='cp_".$i."' multiple><option value='".$row2[cp]."' selected>-- ".$row2[cp]." --</option><option value='yes'>Yes</option><option value='no'>No</option></select></td></tr>";
	
}
	}

echo "<tr><td><input type=\"submit\" name=\"Modify\" value=\"Modify\"></td></tr></form></table>";

}

if ($_GET['view'] == "search") {

				echo "<title>OneCMS - www.insanevisions.com/onecms > Page Manager > Search</title>";

	echo "<form action='a_ipban.php?view=search' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for ip</td><td><input type='text' name='search'></td><td><input type='submit' name='Submit' value='Search'></td></tr></table></form>";

	echo "<form action='a_ipban.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>IP</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr><center><div align=\"center\">";

	$query="SELECT * FROM onecms_ipban WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[ip]";
		$name = stripslashes($name2);

    	echo "<tr><td>$name</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
		}

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td><td><a href=\"a_ipban.php?view=add\">Add Company</a></td></tr></form></table><br><br>";

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_ipban WHERE name LIKE '%" . $_POST['search'] . "%'"),0);

$total_ipban = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_ipban; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_ipban){
    $next = ($page + 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$next\">Next>></a>";
}
echo "</center>

    </span>
  </div></div></center>";

}

}
}
}
}include ("a_footer.inc");
?>
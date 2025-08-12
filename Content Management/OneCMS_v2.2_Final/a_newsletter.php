<?php
include ("config.php");

if ($ipbancheck3 == "0") {
	if ($numv == "0"){
	if ($warn == $naum) {
	echo "You are banned from the Admin CP...now go away!";
} else {

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}



$from = (($page * $max_results) - $max_results);echo '<SCRIPT LANGUAGE="JavaScript">var checkflag = "false";function check(field) {if (checkflag == "false") {for (i = 0; i < field.length; i++) {field[i].checked = true;}checkflag = "true";return "Uncheck All"; }else {for (i = 0; i < field.length; i++) {field[i].checked = false; }checkflag = "false";return "Check All"; }}</script><center><a href="a_newsletter.php?view=addcat">Add Category</a> | <a href="a_newsletter.php?view=cat">Manage Categories</a> | <a href="a_newsletter.php?view=addedition">Add Edition</a> | <a href="a_newsletter.php?view=edition">Manage Editions</a> | <a href="a_newsletter.php?view=subscribers">Manage Subscribers</a><br>[ <a href="a_newsletter.php?view=sendout"><b>Send out Newsletter(s)</b></a> ]</center><br><br>';

if ($_GET['view'] == "") {
echo "Choose what you want to do above";
}

if ($_GET['view'] == "sendout2") {
		echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want these edition(s) to be sent out to subscribers now?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $i) = each ($_POST['sendout'])) {

$ft = mysql_fetch_row(mysql_query("SELECT name,content,cat FROM onecms_newsletter WHERE id = '".$i."'"));

$ex = explode("<--seperate-->", $ft[1]);

$sql = mysql_query("SELECT * FROM onecms_newsletter WHERE type = 'subscribers' AND cat = '".$ft[2]."'");
	while($r = mysql_fetch_array($sql)) {	

	$ft2 = mysql_fetch_row(mysql_query("SELECT email FROM onecms_users WHERE username = '".$r[name]."'"));

	$headers .= "From: $name <$email>\r\n";
    $headers .= "Cc: $email\r\n";
    $headers .= "Bcc: $email\r\n";

	if ($r[content] == "text") {
	mail("".$ft2[0]."", "".$sitename." - ".stripslashes($ft[0])." Newsletter", "".strip_tags($ex[1])."", "$headers");
	} else {
	mail("".$ft2[0]."", "".$sitename." - ".stripslashes($ft[0])." Newsletter", "".$ex[0]."", "$headers");
	}
}
}
	echo "The edition(s) have been sent out.";
}

if ($_GET['view'] == "sendout") {
	echo "<form action='a_newsletter.php?view=sendout2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Category</b></td><td><b>Send Out</b></td></tr>";

$query="SELECT * FROM onecms_newsletter WHERE type = 'edition' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";

		$fetch2 = mysql_fetch_row(mysql_query("SELECT name FROM onecms_newsletter WHERE id = '".$row[cat]."'"));

		echo "<tr><td>".$row[name]."</td><td>".$fetch2[0]."</td></td><td><input type=\"checkbox\" name=\"sendout[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_newsletter WHERE type = 'edition'"),0);

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

if ($_GET['view'] == "subscribersm") {
		echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {
	$delete = mysql_query("DELETE FROM onecms_newsletter WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "The subscriber(s) have been deleted.";
}
}

if ($_GET['view'] == "subscribers") {

	echo "<form action='a_newsletter.php?view=subscribersm' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Username</b></td><td><b>Category</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_newsletter WHERE type = 'subscribers' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";

		$fetch = mysql_fetch_row(mysql_query("SELECT username FROM onecms_profile WHERE id = '".$row[name]."'"));

		$fetch2 = mysql_fetch_row(mysql_query("SELECT name FROM onecms_newsletter WHERE id = '".$row[cat]."'"));

		echo "<tr><td>".$fetch[0]."</td><td>".$fetch2[0]."</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_newsletter WHERE type = 'edition'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_newsletter.php?view=subscribers&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_newsletter.php?view=subscribers&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_newsletter.php?view=subscribers&page=$next\">Next>></a>";
}
echo "</center>";

}

if ($_GET['view'] == "editionm2") {

if ($_POST['all']) {

while (list(, $i) = each ($_POST['del'])) {
$delete = mysql_query("DELETE FROM onecms_newsletter WHERE id = '".$i."'");
}
}

if ($_POST['edit']) {
while (list(, $i) = each ($_POST['id'])) {

if ($_POST["autobr_$i"]) {
$content1 = preg_replace("/<br>\n/","\n",addslashes($_POST["content1_$i"]));
$content1 = preg_replace("/<br>\n/","\n",addslashes($_POST["content1_$i"]));
$content1 = preg_replace("/(\015\012)|(\015)|(\012)/","<br>\n",addslashes($_POST["content1_$i"]));

$content2 = preg_replace("/<br>\n/","\n",addslashes($_POST["content2_$i"]));
$content2 = preg_replace("/<br>\n/","\n",addslashes($_POST["content2_$i"]));
$content2 = preg_replace("/(\015\012)|(\015)|(\012)/","<br>\n",addslashes($_POST["content2_$i"]));
} else {
$content1 = $_POST["content1_$i"];
$content2 = $_POST["content2_$i"];
}
   
   $add = mysql_query("UPDATE onecms_newsletter SET name = '".addslashes($_POST["name_$i"])."', type = 'edition', content = '".$content1."<--seperate-->".$content2."', cat = '".$_POST["cat_$i"]."', date = '".time()."' WHERE id = '".$i."'") or die(mysql_error());
}
}
	echo "The edition(s) have been update/deleted.";
}

if ($_GET['view'] == "catm2") {

if ($_POST['all']) {

while (list(, $i) = each ($_POST['del'])) {
$delete = mysql_query("DELETE FROM onecms_newsletter WHERE id = '".$i."'");
}
}

if ($_POST['edit']) {
while (list(, $i) = each ($_POST['id'])) {
   
   $add = mysql_query("UPDATE onecms_newsletter SET name = '".addslashes($_POST["name_$i"])."', content = '".addslashes($_POST["icon_$i"])."', date = '".time()."' WHERE id = '".$i."'") or die(mysql_error());
}
}
	echo "The category(s) have been update/deleted.";
}

if ($_GET['view'] == "editionm") {
echo "<form action='a_newsletter.php?view=editionm2' method='post' name='form1'><table cellspacing='1' cellpadding='1' border='0'>";

if ($_POST['id']) {
	echo "<input type='hidden' name='edit' value='yes' checked>";

	while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_newsletter WHERE id = '".$i."'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

echo "<input type='hidden' name='id[]' value='".$i."'>";

$ex = explode("<--seperate-->", $row[content]);

$c1 = preg_replace("/<br>/","\n",stripslashes($ex[0]));
$c2 = preg_replace("/<br>/","\n",stripslashes($ex[1]));

echo "<tr><td><b><center>Edition #".$i."</center></b><br><br></td></tr><tr><td><b>Name</b></td><td><input type='text' name='name_".$i."' value='".stripslashes($row[name])."'></td></tr><tr><td><b>Newsletter Content (HTML)</b></td><td><textarea name='content1_".$i."' cols='30' rows='12'>".$c1."</textarea></td></tr><tr><td><b>Newsletter Content (text)</b></td><td><textarea name='content2_".$i."' cols='30' rows='12'>".$c2."</textarea></td></tr><tr><td><b>Category</b></td><td><select name='cat_".$i."'>";

	$sql = mysql_query("SELECT * FROM onecms_newsletter WHERE type = 'cat'");
	while($r = mysql_fetch_array($sql)) {
	if ($r[id] == $row[cat]) {
	echo "<option value='".$r[id]."' selected>-- ".$r[name]." --</option>";
	} else {
	echo "<option value='".$r[id]."'>".$r[name]."</option>";
	}
	}
	echo "</select></td></tr><tr><td><b>Auto Break Line?</b></td><td><input type='checkbox' name='autobr_".$i."' value='hey' checked></td></tr>";
}
}
}

if ($_POST['delete']) {
	echo "<tr><td><br><br>Are you sure you want to delete the following edition(s)?:<br><br>";

	while (list(, $i) = each ($_POST['delete'])) {
	$query="SELECT * FROM onecms_newsletter WHERE id = '".$i."'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	
	echo "".$row[name]."<input type='hidden' name='del[]' value='".$i."'><br>";
	}
	}
	echo "<input type='checkbox' name='all' value='yes' checked>";
}
echo "<tr><td><input type='submit' name='submit' value='Update edition(s)'></td></tr></table></form>";
}

if ($_GET['view'] == "catm") {
echo "<form action='a_newsletter.php?view=catm2' method='post' name='form1'><table cellspacing='1' cellpadding='1' border='0'>";

if ($_POST['id']) {
	echo "<input type='hidden' name='edit' value='yes' checked>";

	while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_newsletter WHERE id = '".$i."'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

echo "<input type='hidden' name='id[]' value='".$i."'>";

echo "<tr><td><b><center>Category #".$i."</center></b><br><br></td></tr><tr><td><b>Name</b></td><td><input type='text' name='name_".$i."' value='".stripslashes($row[name])."'></td></tr><tr><td><b>Icon</b></td><td><input type='text' name='icon_".$i."' value='".stripslashes($row[content])."'></td></tr>";
}
}
}

if ($_POST['delete']) {
	echo "<tr><td><br><br>Are you sure you want to delete the following category(s)?:<br><br>";

	while (list(, $i) = each ($_POST['delete'])) {
	$query="SELECT * FROM onecms_newsletter WHERE id = '".$i."'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	
	echo "".$row[name]."<input type='hidden' name='del[]' value='".$i."'><br>";
	}
	}
	echo "<input type='checkbox' name='all' value='yes' checked>";
}
echo "<tr><td><input type='submit' name='submit' value='Update category(s)'></td></tr></table></form>";
}

if ($_GET['view'] == "edition") {

	echo "<form action='a_newsletter.php?view=editionm' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Edition Name</b></td><td><b>Category</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_newsletter WHERE type = 'edition' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td>$name</td><td>";

		if ($row[cat]) {
		$fetch = mysql_fetch_row(mysql_query("SELECT name FROM onecms_newsletter WHERE id = '".$row[cat]."'"));
		echo "".$fetch[0]."";
		}
		echo "</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_newsletter WHERE type = 'edition'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_newsletter.php?view=edition&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_newsletter.php?view=edition&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_newsletter.php?view=edition&page=$next\">Next>></a>";
}
echo "</center>";

}

if ($_GET['view'] == "cat") {

	echo "<form action='a_newsletter.php?view=catm' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Category Name</b></td><td><b>Icon</b></td><td><b><b>Edit</b></td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_newsletter WHERE type = 'cat' ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td>$name</td><td>";
		if ($row[icon]) {
		echo "<img src='".$row[icon]."' border='1'>";
		}
		echo "</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_newsletter WHERE type = 'cat'"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_newsletter.php?view=cat&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_newsletter.php?view=cat&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_newsletter.php?view=cat&page=$next\">Next>></a>";
}
echo "</center>";

}

if ($_GET['view'] == "addedition") {
echo "<form action='a_newsletter.php?view=addedition' method='post'><table cellspacing='2' cellpadding='1' border='0' align='center'><tr><td>How many editions to add?</td><td><input type='text' name='many'></td><td><input type='submit' name='submit' value='Go'></td></tr></table></form>";

if ($_POST['many']) {


	echo "<form action='a_newsletter.php?view=addedition2' method='post' name='form1'><table cellspacing='2' cellpadding='1' border='0' align='center'><input type='hidden' name='am' value='".$_POST['many']."'>";

for($i = 0; $i < $_POST['many']; $i = $i+1) {
	echo "<tr><td><center><b>Edition #".$i."</b></center><br><br></td></tr><tr><td><b>Edition Name</b></td><td><input type='text' name='name_".$i."'></td></tr><tr><td><b>Newsletter Content (HTML)</b></td><td><textarea name='content1_".$i."' cols='30' rows='10'></textarea></td></tr><tr><td><b>Newsletter Content (text)</b></td><td><textarea name='content2_".$i."' cols='30' rows='10'></textarea></td></tr><tr><td><b>Auto Break Line?</b></td><td><input type='checkbox' name='autobr_".$i."' value='yay' checked></td></tr><tr><td><b>Category</b></td><td><select name='cat_".$i."'>";
	
$search = mysql_query("SELECT * FROM onecms_newsletter WHERE type = 'cat'");
while($r = mysql_fetch_array($search)) {
	echo "<option value='".$r[id]."'>".$r[name]."</option>";
}
echo "</select><br><br></td></tr>";
}
}
echo "<tr><td><input type='submit' name='submit' value='Add Edition(s)'></td></tr></table></form>";
}

if ($_GET['view'] == "addcat") {
echo "<form action='a_newsletter.php?view=addcat' method='post'><table cellspacing='2' cellpadding='1' border='0' align='center'><tr><td>How many categories to add?</td><td><input type='text' name='many'></td><td><input type='submit' name='submit' value='Go'></td></tr></table></form>";

if ($_POST['many']) {


	echo "<form action='a_newsletter.php?view=addcat2' method='post' name='form1'><table cellspacing='2' cellpadding='1' border='0' align='center'><input type='hidden' name='am' value='".$_POST['many']."'>";

for($i = 0; $i < $_POST['many']; $i = $i+1) {
	echo "<tr><td><center><b>Category #".$i."</b></center><br><br></td></tr><tr><td><b>Category Name</b></td><td><input type='text' name='name_".$i."'></td></tr><tr><td><b>Icon</b></td><td><input type='text' name='icon_".$i."'><br><br></td></tr>";
}
}
echo "<tr><td><input type='submit' name='submit' value='Add Category(s)'></td></tr></table></form>";
}

if ($_GET['view'] == "addcat2") {

for($i = 0; $i < $_POST['am']; $i = $i+1) {

   $sql = mysql_query("SELECT * FROM onecms_newsletter WHERE name = '".$_POST["name_$i"]."'");
   $num = mysql_num_rows($sql);

   if ($num > "0") {
	   echo "Sorry, but the category name <b>".$_POST["name_$i"]."</b> is already in use. Go back and choose another name.<br><br>";
   } else {
   
   $add = mysql_query("INSERT INTO onecms_newsletter VALUES ('null', '".addslashes($_POST["name_$i"])."', 'cat', '".addslashes($_POST["icon_$i"])."', '', '".time()."')") or die(mysql_error());

   }
}

if ($add == TRUE) {
	echo "Categories have been added";
}
}

if ($_GET['view'] == "addedition2") {

for($i = 0; $i < $_POST['am']; $i = $i+1) {

if ($_POST["autobr_$i"]) {
$content1 = preg_replace("/<br>\n/","\n",addslashes($_POST["content1_$i"]));
$content1 = preg_replace("/<br>\n/","\n",addslashes($_POST["content1_$i"]));
$content1 = preg_replace("/(\015\012)|(\015)|(\012)/","<br>\n",addslashes($_POST["content1_$i"]));

$content2 = preg_replace("/<br>\n/","\n",addslashes($_POST["content2_$i"]));
$content2 = preg_replace("/<br>\n/","\n",addslashes($_POST["content2_$i"]));
$content2 = preg_replace("/(\015\012)|(\015)|(\012)/","<br>\n",addslashes($_POST["content2_$i"]));
} else {
$content1 = $_POST["content1_$i"];
$content2 = $_POST["content2_$i"];
}
   
   $add = mysql_query("INSERT INTO onecms_newsletter VALUES ('null', '".addslashes($_POST["name_$i"])."', 'edition', '".$content1."<--seperate-->".$content2."', '".$_POST["cat_$i"]."', '".time()."')") or die(mysql_error());
}

if ($add == TRUE) {
	echo "Editions have been added";
}
}

}
}
}
include ("a_footer.inc");
?>
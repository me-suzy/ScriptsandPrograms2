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

$from = (($page * $max_results) - $max_results);echo '<SCRIPT LANGUAGE="JavaScript">var checkflag = "false";function check(field) {if (checkflag == "false") {for (i = 0; i < field.length; i++) {field[i].checked = true;}checkflag = "true";return "Uncheck All"; }else {for (i = 0; i < field.length; i++) {field[i].checked = false; }checkflag = "false";return "Check All"; }}</script>';

if ((($userlevel == "3") or ($userlevel == "4") or ($userlevel == "5"))) {
	echo "Sorry $username, but you do not have permission to manage templates. You are only a $level.";
} else {

echo '<SCRIPT LANGUAGE="JavaScript">
function AFManager () {
alert ("For the AF Manager template, you can use the following fields - \n\n{affiliate} : This field will be where the affiliate code goes. OneCMS automatically prints the link or button of the affiliate where that tag is placed.\n\n{date} : Wherever you place this tag, it will show when the affiliate was added.");
}
</SCRIPT><SCRIPT LANGUAGE="JavaScript">
function PRManager () {
alert ("For the PR Manager template, you can use the following fields - \n\n{name} - Name of the company\n{site} - The companies site\n{description} - Description of the company (such as what systems/type of games they make)\n{content} - This tag links to all content related to this company\n{games} - This links to all games related to this company");
}
</SCRIPT><SCRIPT LANGUAGE="JavaScript">
function skins () {
alert ("For skins, you can use the following tags - \n\n{menu1} - Includes the first set of links for the admin cp.\n\n{menu2} - Includes the second set of links for the admin cp.\n\n{menu3} - Includes the third set of links for the admin cp.\n\n{menu4} - Includes the fourth set of links for the admin cp.\n\n{menu5} - Includes the fifth set of links for the admin cp.\n\n{menu6} - Includes the sixth set of links for the admin cp.\n\n{menu7} - Includes the seventh set of links for the admin cp.\n\n{version} - What version of OneCMS you are running.\n\n{users} - Amount of users online.\n\n{online} - Lists the users that are online.\n\n{pms} - Amount of total private messages you have, in your inbox.\n\n{new} - Amount of new private messages you have.\n\n{pm} - Lists any new private messages that you have.\n\n{welcome} - Displays Welcome username if person is logged in, other wise Welcome Guest and a link to login.\n\n{chooseskin} - Allows the visitor/user to choose a skin.\n\n{skinname} - Displays the name of the current skin your using.");
}
</SCRIPT><SCRIPT LANGUAGE="JavaScript">
function ContestManager () {
alert ("For the Contest Manager template, you can use the following fields - \n\n{name} : Name of the contest.\n\n{des} : Description of contest.\n\n{rules} : The rules.\n\n{posts} : Amount of posts required to enter.\n\n{priv} : Is it required to be registered to your site, in order to enter?\n\n{register} : Register URL\n\n{forums} : URL to forums\n\n{login} : Login URL");
}
</SCRIPT><SCRIPT LANGUAGE="JavaScript">
function list () {
alert ("If the template type is list and is a games list, then you can use the following tags -\n\n{name} : Name of the game\n\n{system} : System\n\n{link} : This is the URL of the game page\n\n{publisher} : The publisher\n\n{developer} : The developer\n\n{release} : Release date\n\n\n\nIf the template type is list and is a latest posts or latest topics list, then you can use the following tags-\n\n{subject} : Subject of the post or topic\n\n{username} : Posted by...\n\n{userid} : The id of the user that posted this\n\n{link} : URL to the post/topic\n\n{date} : Date it was posted\n\n\n\nIf the template type is list and is a content, system or latest content from a system list, then you can use the following tags -\n\n{name} : Name of the content\n\n{date} : Date posted\n\n{link} : URL to the content");
}
</SCRIPT><SCRIPT LANGUAGE="JavaScript">
function systems () {
alert ("{name} : Name of the system\n\n{abr} : Abbreviation\n\n{icon} : Displays system icon\n{id} - ID number of the system\n{status} - Status of system\n{link} - The URL to the system page");
}
</SCRIPT><SCRIPT LANGUAGE="JavaScript">
function index () {
alert ("Sorry, but there are no tags for the index template. But dont forget that you can list the latest forum posts, reviews, previews, whatever on this page.");
}
</SCRIPT><SCRIPT LANGUAGE="JavaScript">
function games () {
alert ("{Name} : Name of game\n{username} : Username that posted this game\n{publisher} : Name of the publisher for this game\n{developer} : Name of the developer for this game\n{genre} : Genre of game (etc. fps, sports, action)\n{release} : Release date for this game\n{esrb} : ESRB rating\n{boxart} : URL of the boxart\n{des} : Game description\n{id} : Game ID number\n{views} : Amount of views\n{game-favorites} : Icon so the user can add this game to his favorites\n{game-playing} : Icon so the user can add this game to his playing\n{game-tracked} : Icon so the user can add this game to his tracked\n{game-wishlist} : Icon so the user can add this game to his wishlist\n{game-collection} : Icon so the user can add this game to his collection\n{game-systems} : Icon so the user can add the assign system to his systems\n");
}
</SCRIPT>';

$query="SELECT * FROM onecms_cat";
	$result=mysql_query($query);
	while($r = mysql_fetch_array($result)) {
		echo '<SCRIPT LANGUAGE="JavaScript">';
		echo "function ".$r[name]." () {";
		echo 'alert ("';
		
		$sql = mysql_query("SELECT * FROM onecms_fields WHERE cat = '".$r[name]."' OR cat = ''");
		while($b = mysql_fetch_array($sql)) {
			if ($b[help]) {
			$help = $b[help];
			} else {
			$help = "No help available";
			}
			echo "{".$b[name]."} - ".$help."";
			echo '\n';
		}
		echo '");}</SCRIPT>';
	}

echo "<center><a href='a_templates.php'>Manage Templates</a> | <a href='a_templates.php?view=add'>Add Templates</a> | <a href='a_templates.php?view=skins'>Manage Skins</a> | <a href='a_templates.php?view=add1'>Add Skin</a></center><br><br>";

if ($_GET['view'] == "skins") {

echo "<title>OneCMS - www.insanevisions.com/onecms > Skin Manager</title><form action='a_templates.php?view=manage2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b>Type</b></td><td><b><b>Edit</b></td><td><b>Delete?</b></td></tr>";

$query="SELECT * FROM onecms_skins ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td>$name</td><td>$row[type]</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		
		if ((((((($id == "1") or ($id == "2") or ($id == "3") or ($id == "4") or ($id == "5") or ($id == "6") or ($id == "7"))))))) {
		echo "<td>-</td></tr>";
		} else {
		echo "<td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
		}
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_skins"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_templates.php?view=skins&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_templates.php?view=skins&page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_templates.php?view=skins&page=$next\">Next>></a>";
}
echo "</center>";

}

if ($_GET['view'] == "") {

	echo "<title>OneCMS - www.insanevisions.com/onecms > Templates Manager</title>";

	echo "<form action='a_templates.php?view=manage' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name</b></td><td><b><b>Edit</b></td><td><b>Delete?</b></td></tr>";

$query="SELECT * FROM onecms_templates ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$id = "$row[id]";
		$name2 = "$row[name]";
		$name = stripslashes($name2);
    	echo "<tr><td>$name</td><td><input type=\"checkbox\" name=\"id[]\" value=\"$id\"></td>";
		
		if ((((((((((((((($id == "1") or ($id == "2") or ($id == "3") or ($id == "4") or ($id == "5") or ($id == "6") or ($id == "7") or ($id == "8") or ($id == "9") or ($id == "10") or ($id == "11") or ($id == "12") or ($id == "13") or ($id == "14") or ($id == "16"))))))))))))))) {
		echo "<td>-</td></tr>";
		} else {
		
		$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_cat WHERE name = '".$name."'"));
		
		if ($sql > "0") {
		echo "<td>-</td></tr>";
		} else {
		echo "<td><input type=\"checkbox\" name=\"delete[]\" value=\"$id\"></td></tr>";
		}
		}
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_templates"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"a_templates.php?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"a_templates.php?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"a_templates.php?page=$next\">Next>></a>";
}
echo "</center>";

}

if (($_GET['view'] == "manage") && ($_GET['edit'] == "")) {

if ($_POST['delete']) {
while (list(, $ia) = each ($_POST['delete'])) {
$sql2 = mysql_query("DELETE FROM onecms_templates WHERE id = '".$ia."'");
}
echo "Templates Deleted.<br><br>";
}

if ($_POST['id']) {
echo "<form action='a_templates.php?view=manage&edit=2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";
    while (list(, $i) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_templates WHERE id = '$i'";
	$result=mysql_query($query);
	while($row2 = mysql_fetch_array($result)) {

    echo "<input type='hidden' name='id[]' value='".$i."'><tr><td><i><center><div align='center'>Template #".$i."</div></center></i></td></tr>";
	
	if ($row2[type] == "list") {
	
	echo "<tr><td><b>Template Name</b></td><td><input type='text' name='name_".$i."' value='".$row2['name']."'></td></tr>";
	} else {
	echo "<tr><td><b>Template Name</b></td><td><input type='hidden' name='name_".$i."' value='".$row2['name']."'>".$row2['name']."</td></tr>";
	}

	$y = "/ /";
	$u = "";
	$res = preg_replace($y, $u, $row2[name]);
   
	echo "<tr><td><b>Template</b></td><td><textarea name='template_".$i."' cols='30' rows='12'>".stripslashes($row2['template'])."</textarea></td></tr><tr><td><b>Help File</b></td><td><a href='";
	if ($row2[type] == "list") {
		echo "javascript:list()";
	} else {
		echo "javascript:".$res."()";
	}
	echo "'>Click Me</a></td></tr>";
}
}
			echo "<tr><td><input type=\"submit\" name=\"Modify\" value=\"Modify\"></td></tr></form></table>";
}
}

if (($_GET['view'] == "manage") && ($_GET['edit'] == "2")) {

   while (list(, $i) = each ($_POST['id'])) {

   $_POST["template_$i"] = addslashes($_POST["template_$i"]);
   $upd = "UPDATE onecms_templates SET name = '".$_POST["name_$i"]."', template = '".$_POST["template_$i"]."' WHERE id = '".$i."'";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The template(s) have been updated. <a href='a_templates.php'>Return to Template Manager Home</a>";
}
}

if ($_GET['view'] == "add1") {
			echo "<form action=\"a_templates.php?view=add1\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many skins to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";
		    if ($_POST['search']) {
						echo "<form action='a_templates.php?view=add12' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($val = 0; $val < $_POST['search']; $val = $val+1) {
	echo "<tr><td><b><center>Skin #".$val."</b></center></td></tr><tr><td>Name</td><td><input type='text' name='name_".$val."'> <a href='javascript:skins();'><b>[Tags to use]</b></a></td></tr><tr><td>Header</td><td><textarea name='1_".$val."' cols='30' rows='10'></textarea></td></tr><tr><td>Footer</td><td><textarea name='2_".$val."' cols='30' rows='10'></textarea></td></tr><tr><td>Type</td><td><select name='type_".$val."'><option value='cp'>Admin CP Skin</option><option value='forum'>Forum Skin</option></select></td></tr><tr><td>Name of images folder</td><td><input type='text' name='name2_".$val."'></td></tr>";
	}
	echo "<tr><td><input type='submit' name='Add' value='Add'></td></tr></form></table>";
			}
}

if ($_GET['view'] == "add12") {

	for($i = 0; $i < $_POST['s']; $i = $i+1) {

   $upd = "INSERT INTO onecms_skins VALUES ('null', '".$_POST["name_$i"]."', '".$_POST["type_$i"]."', '".addslashes($_POST["1_$i"])."', '".addslashes($_POST["2_$i"])."', '".$_POST["name2_$i"]."')";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The skins(s) have been added. <a href=\"a_templates.php\">Manage Templates</a>";
}
	}

if ($_GET['view'] == "add") {
		echo "<form action=\"a_templates.php?view=add\" method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>How many templates to add?</td><td><input type='text' name='search'></td><td><input type='submit' name='addd' value='Submit'></td></tr></table></form>";

if ($_POST['search']) {
						echo "<form action='a_templates.php?view=add2' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"left\">";

echo "<input type=\"hidden\" name=\"s\" value='".$_POST['search']."'>";

    for($val = 0; $val < $_POST['search']; $val = $val+1) {
	echo "<tr><td><b><center>Template #".$val."</b></center></td></tr><tr><td>Name</td><td><input type='text' name='name_".$val."'></td></tr><tr><td>Template</td><td><textarea name='template_".$val."' cols='30' rows='10'></textarea></td></tr>";
	}
	echo "<tr><td><input type='submit' name='Add' value='Add'></td></tr></form></table>";
			}
}

if (($_GET['view'] == "manage2") && ($_GET['edit'] == "")) {

if ($_POST['delete']) {
while (list(, $ia) = each ($_POST['delete'])) {
$sql2 = mysql_query("DELETE FROM onecms_skins WHERE id = '".$ia."'");
}
}

echo "<form action='a_templates.php?view=manage3' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";

    while (list(, $val) = each ($_POST['id'])) {
	$query="SELECT * FROM onecms_skins WHERE id = '$val'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {

    echo "<input type='hidden' name='id[]' value='".$val."'><tr><td><b><center>Skin #".$val."</b></center></td></tr><tr><td>Name</td><td><input type='text' name='name_".$val."' value='".$row[name]."'>  <a href='javascript:skins();'><b>[Tags to use]</b></a></td></tr><tr><td>Header</td><td><textarea name='1_".$val."' cols='30' rows='10'>".stripslashes($row[header])."</textarea></td></tr><tr><td>Footer</td><td><textarea name='2_".$val."' cols='30' rows='10'>".stripslashes($row[footer])."</textarea></td></tr><tr><td>Type</td><td><select name='type_".$val."'><option value='".$row[type]."' selected>-- ".$row[type]." --</option><option value='cp'>Admin CP Skin</option><option value='forum'>Forum Skin</option></select></td></tr><tr><td>Name of images folder</td><td><input type='text' name='name2_".$val."' value='".$row[images]."'></td></tr>";
}
	}
	
	echo "<tr><td><input type=\"submit\" name=\"Modify\" value=\"Modify\"></td></tr></form></table>";

}

if ($_GET['view'] == "manage3") {

   while (list(, $i) = each ($_POST['id'])) {

   $upd = "UPDATE onecms_skins SET name = '".$_POST["name_$i"]."', header = '".addslashes($_POST["1_$i"])."', footer = '".addslashes($_POST["2_$i"])."', type = '".$_POST["type_$i"]."', images = '".$_POST["name2_$i"]."' WHERE id = '".$i."'";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The skins(s) have been updated. <a href='a_templates.php?view=skins'>Manage Skins</a>";
}
}

if ($_GET['view'] == "add2") {

	for($i = 0; $i < $_POST['s']; $i = $i+1) {
   $upd = "INSERT INTO onecms_templates VALUES ('null', '".$_POST["name_$i"]."', '".addslashes($_POST["template_$i"])."', 'list')";
   $r = mysql_query($upd) or die(mysql_error());
   }
if ($r == TRUE) {
	echo "The template(s) have been added. <a href=\"a_templates.php\">Manage Templates</a>";
}
	}
}
}
}
}
include ("a_footer.inc");
?>
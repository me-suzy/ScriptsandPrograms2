<?php
$la = "a";
$z = "b";
include ("config.php");

if ($ipbancheck1 == "0") {
if ($numvb == "0"){
	if ($warn == $naum) {
	echo "You are banned from the site...now go away!";
} else {

if (!$_GET['view'] == "forums") {
headera();
}

if ($_GET['view'] == "") {
echo "<form action='".$PHP_SELF."?view=results' method='post'><table cellpadding='2' cellspacing='2' border='0'><tr><td><input type='text' name='search' value='".$_GET['search']."'></td><td><select name='type'><option value='content'>Content</option><option value='games'>Games</option></select></td></tr><tr><td><input type='submit' name='submit' value='Search'></td></tr></table></form>";
}

if ($_GET['view'] == "results") {
headera();
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $max_results) - $max_results);

if ($_POST['type'] == "games") {
$searca = mysql_query("SELECT * FROM onecms_games WHERE name LIKE '%" . $_POST['search'] . "%'");
$results = mysql_num_rows($searca);
} else {
$searca = mysql_query("SELECT * FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%'");
$results = mysql_num_rows($searca);
}

echo "<title>".$sitename." :: ".$results." Search Results For - ".$_POST['search']."</title><a href='index.php'>Home</a> > <a href='search.php'>Search</a> > ".$_POST['search']." - ".$results." Results<br><center>".$results." Results For :: ".$_POST['search']."</center><br><br><table cellpadding='5' cellspacing='3' border='0' align='center'>";
    if ($_POST['type'] == "content") {
	$search = mysql_query("SELECT * FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `date` DESC LIMIT $from, $max_results");
	} else {
	$search = mysql_query("SELECT * FROM onecms_games WHERE name LIKE '%" . $_POST['search'] . "%' ORDER BY `id` DESC LIMIT $from, $max_results");
	}
	for($i = 1; $row = mysql_fetch_assoc($search); $i++) {
	$icona = mysql_fetch_row(mysql_query("SELECT icon FROM onecms_systems WHERE id = '".$row[systems]."'"));
		if ($row[systems]) {
		$icon = "<img src='".$icona[0]."'>";
		} else {
		$icon = "";
		}
		$n1 = "/".$_POST['search']."/";
		$n2 = hilite($_POST['search']);
		$name = preg_replace($n1, $n2, $row[name]);

		similar_text($_POST['search'], $row[name], $p);
		$p = str_replace(".", "", $p);

		echo "<tr><td><b>".$i.".</b></td><td><a href='";

		if ($_POST['type'] == "games") {
		echo $gamepart1;
		} else {
		echo $part1;
		}

		echo $row[id];

		if ($_POST['type'] == "games") {
		echo $gamepart2;
		} else {
		echo $part2;
		}
		
		echo "'>".$name."</a>";
		
		if ($_POST['type'] == "content") {
		echo "&nbsp;&nbsp;(<i>".$row[cat]."</i>)&nbsp;&nbsp;";
		}
		echo "".$icon."</td>";
		if ($_POST['type'] == "content") {
		echo "<td>".date($dformat, $row[date])."</td>";
		}
		
		echo "<td><b>Relevance</b>: ".substr($p,0,2)."%</td>";

		if ($_POST['type'] == "games") {
			echo "<td>".stripslashes($row[des])."</td>";
		}
		echo "</tr>";
	}

if ($_POST['type'] == "content") {
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_content WHERE name LIKE '%" . $_POST['search'] . "%'"),0);
} else {
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_games WHERE name LIKE '%" . $_POST['search'] . "%'"),0);
}

$total_pages = ceil($total_results / $max_results);


echo "</table><br><center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"search.php?view=results&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"search.php?view=results&page=$i\">$i</a>&nbsp;&nbsp;";
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"search.php?view=results&page=$next\">Next>></a>&nbsp;";
}
echo "</center>";

footera();
}
}

if ($_GET['view'] == "forums") {

$query="SELECT * FROM onecms_skins WHERE id = '".$forumskin."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$skin1[] = "/{version}/";
	$skin1[] = "/{users}/"; // amount of users online
	$skin1[] = "/{online}/"; // the users that are online
	$skin1[] = "/{pms}/"; // amount of private messages
	$skin1[] = "/{new}/"; // amount of new private messages
	$skin1[] = "/{pm}/"; // display new private messages
	$skin1[] = "/{welcome}/"; // Displays "Welcome username" if person is logged in, other wise "Welcome Guest" and a link to login
	$skin1[] = "/{chooseskin}/";
	$skin1[] = "/{skinname}/"; // the name of the current skin being used
	$skin2[] = "".$version."";
	$skin2[] = online();
	$skin2[] = usersonline();
	$skin2[] = totalpms();
	$skin2[] = newpms();
	$skin2[] = pms();
	$skin2[] = welcome();
	$skin2[] = "<form name='popupform'><input type='button' onClick=\"window.open('a_skins.php?view=skin2','','width=250,height=150,top=100,left=100');\" value='Choose Skin' name='choice'></form>";
	$skin2[] = skinname();

eval (" ?>" . preg_replace($skin1, $skin2, stripslashes($row[header])) . " <?php ");
}

echo '<table cellpadding=1 align=center cellspacing=1 border=0 width=100%><tr>';

echo "<td>&nbsp;<a href='boards.php'><img src='a_images/home.jpg' style='border:1px solid black'></a></td><td>&nbsp;<a href='search.php?view=forums'><img src='a_images/search.jpg' style='border:1px solid black'></a></td>";
if ($_COOKIE[username] == "") {
		echo "<td>+&nbsp;<a href='members.php?action=login&step=1&url=".$HTTP_SERVER_VARS['REQUEST_URI']."'>Login</a></td><td>+&nbsp;<a href='members.php?action=register'>Register</a></td>";

} else {
			echo "<td>&nbsp;<a href='elite.php'><img src='a_images/fprofile.jpg' style='border:1px solid black'></a></td><td>&nbsp;<a href='members.php?action=logout'><img src='a_images/logout.jpg' style='border:1px solid black'></a></td>";
}

$pms2 = mysql_query("SELECT * FROM onecms_pm WHERE jo = '".$_COOKIE[username]."' AND viewed = '1'") or die(mysql_error());
$pms = mysql_num_rows($pms2);

echo "<td>&nbsp;<a href='members.php?action=list'><img src='a_images/mlist.jpg' style='border:1px solid black'></a></td><td>";

$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_boardcp WHERE uid = '".$useridn."'"));

if ($sql > "0") {
	echo "&nbsp;<a href='boardcp.php'><img src='a_images/boardcp.jpg' style='border:1px solid black'></a></td><td>";
}
if ($_COOKIE[username] == "") {
	echo "Welcome Visitor!";
} else {
	echo "Welcome ".$_COOKIE[username]."! You have (<a href='pm.php?box=in'><b>$pms</b></a>) new pm";
if (($pms == "0") or ($pms > "1")) {
	echo "'s";
}
}
echo "</td></tr></table>";

if (!$_POST['submit']) {
echo "<form action='search.php?view=forums' method='post'><table cellpadding='2' cellspacing='2' border='0'><tr><td><input type='text' name='search'></td><td><select name='type'><option value=''>--------</option><option value='Post'>Post</option><option value='topic'>Topic</option></select></td></tr><tr><td><input type='submit' name='submit' value='Search'></td></tr></table></form>";
}

if ($_POST['submit']) {

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}



$from = (($page * $max_results) - $max_results);

if ($_POST['type'] == "") {
$searca = mysql_query("SELECT * FROM onecms_posts WHERE subject LIKE '%" . $_POST['search'] . "%' OR message LIKE '%" . $_POST['search'] . "%'");
$results = mysql_num_rows($searca);
} else {
$searca = mysql_query("SELECT * FROM onecms_posts WHERE subject LIKE '%" . $_POST['search'] . "%' OR message LIKE '%" . $_POST['search'] . "%' AND type = '".$_POST['type']."'");
$results = mysql_num_rows($searca);
}

echo "<title>".$sitename." :: ".$results." Search Results For - ".$_POST['search']."<LINK REL='stylesheet' HREF='a_ta.css' TYPE='text/css'><table cellpadding='5' cellspacing='3' border='0' align='center'><tr><td align='center'><center>".$results." Results For :: ".$_POST['search']."</center><br><br></td></tr>";

if ($_POST['type'] == "") {
$search = mysql_query("SELECT * FROM onecms_posts WHERE subject LIKE '%" . $_POST['search'] . "%' OR message LIKE '%" . $_POST['search'] . "%'");
} else {
$search = mysql_query("SELECT * FROM onecms_posts WHERE subject LIKE '%" . $_POST['search'] . "%' OR message LIKE '%" . $_POST['search'] . "%' AND type = '".$_POST['type']."'");
}
	while($row = mysql_fetch_array($search)) {
		$n1 = "/".$_POST['search']."/";
		$n2 = "<b>".$_POST['search']."</b>";
		$name = preg_replace($n1, $n2, $row[subject]);

similar_text($_POST['search'], $row[subject], $p3);
similar_text($_POST['search'], $row[message], $p2);

$p = $p3 * $p2/2;
$p = str_replace(".", "", $p);

		$n1a = "/".$_POST['search']."/";
		$n2a = "<b>".$_POST['search']."</b>";
		$msg = preg_replace($n1a, $n2a, "".stripslashes($row[message])."");

		echo "<tr><td><a href='";

		if ($row[type] == "topic") {
			echo "boards.php?t=".$row[id]."";
		} else {
		echo "boards.php?t=".$row[tid]."#".$row[id]."";
		}
		echo "'>".$name."</a></td><td> posted by: <i><a href='members.php?action=profile&id=";
		
		$uid = mysql_query("SELECT * FROM onecms_profile WHERE id = '".$row[uid]."'");
		while($row2 = mysql_fetch_array($uid)) {
			$poster = "$row2[username]";
		}
		echo "".$row[uid]."'>".$poster."</i></a></td><td><b>Relevance</b>: ".$p."%</td></tr><tr><td><p>".$msg."</p><br><br></td></tr>";

	}

if ($_POST['type'] == "") {
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_posts WHERE subject LIKE '%" . $_POST['search'] . "%' OR message LIKE '%" . $_POST['search'] . "%'"),0);
$total_pages = ceil($total_results / $max_results);
} else {
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_posts WHERE subject LIKE '%" . $_POST['search'] . "%' OR message LIKE '%" . $_POST['search'] . "%' AND type = '".$_POST['type']."'"),0);
$total_pages = ceil($total_results / $max_results);
}

echo "</table><center>Select a Page<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."&page=$i\">$i</a>&nbsp;&nbsp;";
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."&page=$next\">Next>></a>&nbsp;";
}
echo "</center>";
$query="SELECT * FROM onecms_skins WHERE id = '".$forumskin."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
	$skin1[] = "/{version}/";
	$skin1[] = "/{users}/"; // amount of users online
	$skin1[] = "/{online}/"; // the users that are online
	$skin1[] = "/{pms}/"; // amount of private messages
	$skin1[] = "/{new}/"; // amount of new private messages
	$skin1[] = "/{pm}/"; // display new private messages
	$skin1[] = "/{welcome}/"; // Displays "Welcome username" if person is logged in, other wise "Welcome Guest" and a link to login
	$skin1[] = "/{chooseskin}/";
	$skin1[] = "/{skinname}/"; // the name of the current skin being used
	$skin2[] = "".$version."";
	$skin2[] = online();
	$skin2[] = usersonline();
	$skin2[] = totalpms();
	$skin2[] = newpms();
	$skin2[] = pms();
	$skin2[] = welcome();
	$skin2[] = "<form name='popupform'><input type='button' onClick=\"window.open('a_skins.php?view=skin2','','width=250,height=150,top=100,left=100');\" value='Choose Skin' name='choice'></form>";
	$skin2[] = skinname();

eval (" ?>" . preg_replace($skin1, $skin2, stripslashes($row[footer])) . " <?php ");
}
}

}
}
}
if (!$_GET['view'] == "forums") {
footera();
}
?>
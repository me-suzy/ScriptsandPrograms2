<?php
$la = "a";
$z = "b";
include ("config.php");

if ($ipbancheck1 == "0") {
if ($numvb == "0"){
	if ($warn == $naum) {
	echo "You are banned from the site...now go away!";
} else {

function authuser() {
if ($_COOKIE[username] == "") {
return "<SCRIPT LANGUAGE=\"JavaScript\">

<!-- Begin
redirTime = \"1\";
redirURL = \"members.php?action=login&step=1&url=".$HTTP_SERVER_VARS['REQUEST_URI']."\";
function redirTimer() { self.setTimeout(\"self.location.href = redirURL;\",redirTime); }
//  End -->
</script>

<BODY onLoad=\"redirTimer()\">";
}
}

if ((((((((((((((((($_GET['view'] == "newsletterss") or ($_GET['view'] == "friendsver1") or ($_GET['view'] == "friendsver2") or ($_GET['view'] == "friendsver3") or ($_GET['view'] == "delete") or ($_GET['view'] == "friendsver4") or ($_GET['view'] == "elitef") or ($_GET['view'] == "elitec") or ($_GET['view'] == "elitep") or ($_GET['view'] == "elitet") or ($_GET['view'] == "elitew") or ($_GET['view'] == "elites") or ($_GET['view'] == "blogd") or ($_GET['view'] == "newsletters") or ($_GET['view'] == "newsletters2"))))))))))))))))) {
if (($_COOKIE['username']) && ($_COOKIE['password'])) {

if (($_GET['view'] == "delete") && ($_GET['id'])) {
$sql = mysql_query("DELETE FROM onecms_elite WHERE id = '".$_GET['id']."'");

if ($sql == TRUE) {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td><b>Item deleted</b>. This window will close in 3 seconds.";
}
}

if ($_GET['view'] == "newsletters") {

echo "Which format? HTML/Text<br><input type=checkbox onclick=\"window.location='elite.php?view=newsletterss&id=".$_GET['id']."&type=html'; return true;\"> / <input type=checkbox onclick=\"window.location='elite.php?view=newsletterss&id=".$_GET['id']."&type=text'; return true;\">";
}

if ($_GET['view'] == "newsletterss") {

	$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_newsletter WHERE cat = '".$_GET['id']."' AND type = 'subscribers' AND name = '".$_COOKIE[username]."'"));

	$fetch = mysql_fetch_row(mysql_query("SELECT name FROM onecms_newsletter WHERE id = '".$_GET['id']."'"));

	if ($sql == "0") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>You are now subcribed to <b>".$fetch[0]."</b> newsletter. This window will close in 3 seconds.";

	$sql2 = mysql_query("INSERT INTO onecms_newsletter VALUES ('null', '".$_COOKIE[username]."', 'subscribers', '".$_GET['type']."', '".$_GET['id']."', '".time()."')") or die(mysql_error());
	} else {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>You are already subcribed to this newsletter! Window will close in 3 seconds.";
	}	
}

if ($_GET['view'] == "newsletters2") {

	$fetch = mysql_fetch_row(mysql_query("SELECT name FROM onecms_newsletter WHERE id = '".$_GET['id']."'"));

	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>You are now unsubcribed to <b>".$fetch[0]."</b> newsletter. This window will close in 3 seconds.";

	$sql = mysql_query("DELETE FROM onecms_newsletter WHERE id = '".$_GET['id']."'") or die(mysql_error());
}

if ($_GET['view'] == "friendsver1") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>Friend Added. This window will close in 3 seconds.";

	$sql = mysql_query("UPDATE onecms_friends SET ver = 'yes' WHERE id = '".$_GET['id']."'");
}

if ($_GET['view'] == "blogd") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>Blog entry deleted. This window will close in 3 seconds.";

	$sql = mysql_query("DELETE FROM onecms_blog WHERE id = '".$_GET['id']."'");
}

if ($_GET['view'] == "friendsver2") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>Friend Request declined. This window will close in 3 seconds.";

	$sql = mysql_query("DELETE FROM onecms_friends WHERE id = '".$_GET['id']."'");
}

if ($_GET['view'] == "friendsver3") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>Friend Request Deleted. This window will close in 3 seconds.";

	$sql = mysql_query("DELETE FROM onecms_friends WHERE id = '".$_GET['id']."'");
}

if ($_GET['view'] == "friendsver4") {

	$sql3 = mysql_num_rows(mysql_query("SELECT * FROM onecms_friends WHERE pid = '".$useridn."' AND pid2 = '".$_GET['id']."'"));

	if ($sql3 == "0") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>Friend Request sent. This window will close in 3 seconds.";

	$sql = mysql_query("INSERT INTO onecms_friends VALUES ('null', '".$useridn."', '".$_GET['id']."', 'no', '".time()."')");

	$name = mysql_fetch_row(mysql_query("SELECT username FROM onecms_profile WHERE id = '".$_GET['id']."'"));

	$message = "Hello,<br><br>".$_COOKIE[username]." has sent a friend request to you. To accept or decline this request, click one of the links below:<br><br><center><b>[</b> <a href='javascript:awindow(\"elite.php?view=friendsver1&id=".$useridn."\", \"\", \"width=20,height=10,scroll=yes\")'>Accept</a> <b>|</b> <a href='javascript:awindow(\"elite.php?view=friendsver2&id=".$useridn."\", \"\", \"width=20,height=10,scroll=yes\")'>Decline</a> <b>]</b></center><br><br>Sincerely,<br>The ".$sitename." team";

	$sql2 = mysql_query("INSERT INTO onecms_pm VALUES ('null', '1', 'Friend Request', '".addslashes($message)."', '".$_COOKIE[username]."', '".$name[0]."', '".time()."')") or die(mysql_error());
	} else {
	
		echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>This person is already your friend! Window will close in 3 seconds.";
	}
}

if ($_GET['view'] == "elitef") {

	$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE game = '".$_GET['id']."' AND type = 'favorites' AND pid = '".$useridn."'"));

	if ($sql == "0") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>Game Added to your <b>Favorites</b>. This window will close in 3 seconds.";

	$sql2 = mysql_query("INSERT INTO onecms_elite VALUES ('null', '".$useridn."', '".$_GET['id']."', 'favorites', '".time()."')") or die(mysql_error());
	} else {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>This game is already in your favorites! Window will close in 3 seconds.";
	}	
}

if ($_GET['view'] == "elitep") {

	$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE game = '".$_GET['id']."' AND type = 'playing' AND pid = '".$useridn."'"));

	if ($sql == "0") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>Game Added to your <b>Playing</b> list. This window will close in 3 seconds.";

	$sql2 = mysql_query("INSERT INTO onecms_elite VALUES ('null', '".$useridn."', '".$_GET['id']."', 'playing', '".time()."')") or die(mysql_error());
	} else {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>This game is already in your playing list! Window will close in 3 seconds.";
	}	
}

if ($_GET['view'] == "elitet") {

	$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE game = '".$_GET['id']."' AND type = 'tracked' AND pid = '".$useridn."'"));

	if ($sql == "0") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>Game Added to your <b>Tracked</b> list. This window will close in 3 seconds.";

	$sql2 = mysql_query("INSERT INTO onecms_elite VALUES ('null', '".$useridn."', '".$_GET['id']."', 'tracked', '".time()."')") or die(mysql_error());
	} else {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>This game is already in your tracked list! Window will close in 3 seconds.";
	}	
}

if ($_GET['view'] == "elitec") {

	$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE game = '".$_GET['id']."' AND type = 'collection' AND pid = '".$useridn."'"));

	if ($sql == "0") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>Game Added to your <b>Collection</b>. This window will close in 3 seconds.";

	$sql2 = mysql_query("INSERT INTO onecms_elite VALUES ('null', '".$useridn."', '".$_GET['id']."', 'collection', '".time()."')") or die(mysql_error());
	} else {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>This game is already in your collection! Window will close in 3 seconds.";
	}	
}

if ($_GET['view'] == "elitew") {

	$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE game = '".$_GET['id']."' AND type = 'wishlist' AND pid = '".$useridn."'"));

	if ($sql == "0") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>Game Added to your <b>Wishlist</b>. This window will close in 3 seconds.";

	$sql2 = mysql_query("INSERT INTO onecms_elite VALUES ('null', '".$useridn."', '".$_GET['id']."', 'wishlist', '".time()."')") or die(mysql_error());
	} else {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>This game is already in your wishlist! Window will close in 3 seconds.";
	}	
}

if ($_GET['view'] == "elites") {

	$sql = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE game = '".$_GET['id']."' AND type = 'systems' AND pid = '".$useridn."'"));

	if ($sql == "0") {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td><b>System</b> Added. This window will close in 3 seconds.";

	$sql2 = mysql_query("INSERT INTO onecms_elite VALUES ('null', '".$useridn."', '".$_GET['id']."', 'systems', '".time()."')") or die(mysql_error());
	} else {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>You already have this system listed! Window will close in 3 seconds.";
	}	
}
} else {
	echo "<BODY onLoad='setTimeout(window.close, 3000)'><link rel='stylesheet' type='text/css' href='ta3.css'><table><tr><td>Sorry but you are<b>not logged in</b>. This window will close in 3 seconds.";
}
} else {

if (((((((((((((((($_GET['view'] == "newsletterss") && ($_GET['view'] == "friendsver1") && ($_GET['view'] == "friendsver2") && ($_GET['view'] == "friendsver3") && ($_GET['view'] == "friendsver4") && ($_GET['view'] == "elitef") && ($_GET['view'] == "elitec") && ($_GET['view'] == "elitep") && ($_GET['view'] == "elitet") && ($_GET['view'] == "elitew") && ($_GET['view'] == "elites") && ($_GET['view'] == "newsletters") && ($_GET['view'] == "blogd") && ($_GET['view'] == "newsletters2")))))))))))))))) {
} else {

headera();

if ($_GET['user'] == "") {
echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\" style=\"border:1px solid black\"><tr><td><a href='elite.php?view=friends'><img src='".$siteurl."/a_images/elite_friends.jpg' alt='Friends' border='0'></a></td><td><a href='elite.php?view=favorites'><img src='".$siteurl."/a_images/elite_favorites.jpg' alt='Favorites' border='0'></a></td><td><a href='elite.php?view=playing'><img src='".$siteurl."/a_images/elite_playing.jpg' alt='Now Playing' border='0'></a></td><td><a href='elite.php?view=tracked'><img src='".$siteurl."/a_images/elite_tracked.jpg' alt='Tracked' border='0'></a></td><td><a href='elite.php?view=collection'><img src='".$siteurl."/a_images/elite_collection.jpg' alt='Collection' border='0'></a></td><td><a href='elite.php?view=systems'><img src='".$siteurl."/a_images/elite_systems.jpg' alt='Systems' border='0'></a></td><td><a href='elite.php?view=wishlist'><img src='".$siteurl."/a_images/elite_wishlist.jpg' alt='Wishlist' border='0'></a></td></tr><tr><td><a href='elite.php?view=newsletter'><img src='".$siteurl."/a_images/elite_newsletter.jpg' alt='Newsletter' border='0'></a></td><td><a href='elite.php?view=blog'><img src='".$siteurl."/a_images/elite_blog.jpg' alt='Blog' border='0'></a></td><td><a href='elite.php?user=".$useridn."'><img src='".$siteurl."/a_images/elite_profile.jpg' alt='Profile' border='0'></a></td><td><a href='elite.php'><img src='".$siteurl."/a_images/profile2.jpg' alt='My Profile' border='0'></a></td></tr></table><script language='javascript'>function awindow(towhere, newwinname, properties) {window.open(towhere,newwinname,properties);}</script>";
} else {

echo "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\" style=\"border:1px solid black\"><tr><td><a href='elite.php?view=friends&user=".$_GET['user']."'><img src='".$siteurl."/a_images/elite_friends.jpg' alt='Friends' border='0'></a></td><td><a href='elite.php?view=favorites&user=".$_GET['user']."'><img src='".$siteurl."/a_images/elite_favorites.jpg' alt='Favorites' border='0'></a></td><td><a href='elite.php?view=playing&user=".$_GET['user']."'><img src='".$siteurl."/a_images/elite_playing.jpg' alt='Now Playing' border='0'></a></td><td><a href='elite.php?view=tracked&user=".$_GET['user']."'><img src='".$siteurl."/a_images/elite_tracked.jpg' alt='Tracked' border='0'></a></td><td><a href='elite.php?view=collection&user=".$_GET['user']."'><img src='".$siteurl."/a_images/elite_collection.jpg' alt='Collection' border='0'></a></td><td><a href='elite.php?view=systems&user=".$_GET['user']."'><img src='".$siteurl."/a_images/elite_systems.jpg' alt='Systems' border='0'></a></td><td><a href='elite.php?view=wishlist&user=".$_GET['user']."'><img src='".$siteurl."/a_images/elite_wishlist.jpg' alt='Wishlist' border='0'></a></td></tr><tr><td><a href='elite.php?view=blog&user=".$_GET['user']."'><img src='".$siteurl."/a_images/elite_blog.jpg' alt='Blog' border='0'></a></td><td><a href='elite.php?user=".$_GET['user']."'><img src='".$siteurl."/a_images/elite_profile.jpg' alt='Profile' border='0'></a></td><td><a href='elite.php'><img src='".$siteurl."/a_images/profile2.jpg' alt='My Profile' border='0'></a></td></tr></table><script language='javascript'>function awindow(towhere, newwinname, properties) {window.open(towhere,newwinname,properties);}</script>";
}

if (($_GET['view'] == "newsletter") && ($_GET['user'] == "")) {
authuser();

echo "<table cellpadding='2' cellspacing='1' border='0' align='center'><tr>";

$sql = mysql_query("SELECT * FROM onecms_newsletter WHERE type = 'cat'");
$ia = mysql_num_rows($sql);
for($i = 1; $r = mysql_fetch_assoc($sql); $i++) {

echo "<td><center><b>".$r[name]."</b><br><img src='".$r[content]."' border='1'><br>";

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_newsletter WHERE type = 'subscribers' AND name = '".$_COOKIE[username]."' AND cat = '".$r[id]."'"));

if ($sql2 == "0") {
echo "<b>[ <a href='javascript:awindow(\"elite.php?view=newsletters&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'>Subcribe</a> ]</b>";
} else {
$sql3 = mysql_fetch_row(mysql_query("SELECT id FROM onecms_newsletter WHERE type = 'subscribers' AND name = '".$_COOKIE[username]."' AND cat = '".$r[id]."'"));

echo "<b>[ <a href='javascript:awindow(\"elite.php?view=newsletters2&id=".$sql3[0]."\", \"\", \"width=20,height=10,scroll=yes\")'>Unsubscribe</a> ]</b>";
}

if (($ia % 3) === 0) {
		echo "</tr><tr>";
}
}
echo "</tr></table>";
}

if ($_GET['view'] == "t") {
authuser();

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}



$from = (($page * $max_results) - $max_results);

$searca = mysql_query("SELECT * FROM onecms_systems WHERE name LIKE '%" . $_POST['search'] . "%'");
$results = mysql_num_rows($searca);

echo "<title>".$sitename." :: ".$results." Search Results For - ".$_POST['search']."<table cellpadding='5' cellspacing='3' border='0' align='center'><tr><td align='center'><center>".$results." Results For :: ".$_POST['search']."</center><br><br></td></tr>";

$search = mysql_query("SELECT * FROM onecms_systems WHERE name LIKE '%" . $_POST['search'] . "%'");
while($row = mysql_fetch_array($search)) {
		$n1 = "/".$_POST['search']."/";
		$n2 = "<b>".$_POST['search']."</b>";
		$name = preg_replace($n1, $n2, $row[name]);

		similar_text($_POST['search'], $row[name], $p);

		echo "<tr><td><a href='elite.php?user=".$row[id]."'>".$name."</a></td><td><b>Relevance</b>: ".round(substr($p, 0, 4), 2)."%</td><td><a href='javascript:awindow(\"elite.php?view=elites&id=".$row[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_s.jpg' border='1'></a></td></tr>";
}
echo "</table>";
}

if ($_GET['view'] == "s") {
authuser();

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}



$from = (($page * $max_results) - $max_results);

$searca = mysql_query("SELECT * FROM onecms_games WHERE name LIKE '%" . $_POST['search'] . "%'");
$results = mysql_num_rows($searca);

echo "<title>".$sitename." :: ".$results." Search Results For - ".$_POST['search']."<table cellpadding='5' cellspacing='3' border='0' align='center'><tr><td align='center'><center>".$results." Results For :: ".$_POST['search']."</center><br><br></td></tr>";

$search = mysql_query("SELECT * FROM onecms_games WHERE name LIKE '%" . $_POST['search'] . "%'");
while($row = mysql_fetch_array($search)) {
		$n1 = "/".$_POST['search']."/";
		$n2 = "<b>".$_POST['search']."</b>";
		$name = preg_replace($n1, $n2, $row[name]);

		similar_text($_POST['search'], $row[name], $p);

		echo "<tr><td><a href='elite.php?user=".$row[id]."'>".$name."</a></td><td><b>Relevance</b>: ".round(substr($p, 0, 4), 2)."%</td><td><a href='javascript:awindow(\"elite.php?view=elitef&id=".$row[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitep&id=".$row[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitet&id=".$row[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitec&id=".$row[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitew&id=".$row[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1'></a></td></tr>";
}
echo "</table>";
}

if (($_GET['view'] == "blogadd") && ($_GET['user'] == "")) {
echo "<form action='elite.php?view=blogadd2' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject</b></td><td><input name='subject' type='text'></td></tr><tr><td><b>Text</b></td><td><textarea name='text' cols='25' rows='12'></textarea></td></tr><tr><td><input type='submit' value='Add Entry'></td></tr></table></form>";
}

if (($_GET['view'] == "blogadd2") && ($_GET['user'] == "")) {
$add = mysql_query("INSERT INTO onecms_blog VALUES ('null', '".$_POST['subject']."', '".$_POST['text']."', '".$useridn."', '".time()."')");

if ($add == TRUE) {
echo "Blog entry added. <a href='elite.php?view=blog'>Go back</a>";
}
}

if (($_GET['view'] == "blog") && ($_GET['user'] == "")) {
echo "<br><center><a href='elite.php?view=blogadd'>Add Blog entry</a></center><br>";

$blog = "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\" width=\"75%\"><tr><td bgcolor='".$blog1."'>{name} - <i>{date}</i> <b><a href='javascript:awindow(\"elite.php?view=blogd&id={id}\", \"\", \"width=20,height=10,scroll=yes\")'>Delete</b></td></tr><tr><td bgcolor='".$blog2."'><p>{blog}</p></td></tr></table><br>";

$search = mysql_query("SELECT * FROM onecms_blog WHERE username = '".$useridn."' ORDER BY `date` DESC");
while($r = mysql_fetch_array($search)) {
$vate[0] = "{name}";
$vate[1] = "{blog}";
$vate[2] = "{username}";
$vate[3] = "{date}";
$vate[4] = "{id}";
$tate[0] = $r[name];
$tate[1] = stripslashes($r[blog]);
$tate[2] = $r[username];
$tate[3] = date($dformat, $r[date]);
$tate[4] = $r[id];
eval (" ?>" . str_replace($vate, $tate, stripslashes($blog)) . " <?php ");
}
}

if (($_GET['view'] == "blog") && ($_GET['user'])) {
echo "<br>";

$blog = "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\" width=\"75%\"><tr><td bgcolor='".$blog1."'>{name} - <i>{date}</i></td></tr><tr><td bgcolor='".$blog2."'><p>{blog}</p></td></tr></table><br>";

$search = mysql_query("SELECT * FROM onecms_blog WHERE username = '".$_GET['user']."' ORDER BY `date` DESC");
while($r = mysql_fetch_array($search)) {
$vate[0] = "{name}";
$vate[1] = "{blog}";
$vate[2] = "{username}";
$vate[3] = "{date}";
$vate[4] = "{id}";
$tate[0] = $r[name];
$tate[1] = stripslashes($r[blog]);
$tate[2] = $r[username];
$tate[3] = date($dformat, $r[date]);
$tate[4] = $r[id];
eval (" ?>" . str_replace($vate, $tate, stripslashes($blog)) . " <?php ");
}
}

if (($_GET['view'] == "systems") && ($_GET['user'])) {
echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>System</b></td><td><b>Abbreviation</b></td><td><b>Users</b></td><td><b>Icon</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'systems' AND pid = '".$_GET['user']."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,abr,icon FROM onecms_systems WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'systems' AND game = '".$r[game]."'"));

echo "<tr><td><a href='index.php?sid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td><img src='".$sql[2]."'></td></tr>";
}

echo "</table>";
}

if (($_GET['view'] == "systems") && ($_GET['user'] == "")) {
authuser();

echo "<br><br><center><form action='elite.php?view=t' method='post'><b>Search for a system to add</b>&nbsp&nbsp<input type='text' name='search'>&nbsp&nbsp<input type='submit' name='submit' value='Search'></center></form><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>System</b></td><td><b>Abbreviation</b></td><td><b>Users</b></td><td><b>Icon</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'systems' AND pid = '".$useridn."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,abr,icon FROM onecms_systems WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'systems' AND game = '".$r[game]."'"));

echo "<tr><td><a href='index.php?sid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td><img src='".$sql[2]."'></td><td><a href='javascript:awindow(\"elite.php?view=delete&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'>Delete</a></td></tr>";
}

echo "</table>";
}

if (($_GET['view'] == "wishlist") && ($_GET['user'])) {
echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Game</b></td><td><b>Genre</b></td><td><b>Users</b></td><td><b>Score</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'wishlist' AND pid = '".$_GET['user']."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,genre FROM onecms_games WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'wishlist' AND game = '".$r[game]."'"));

$searcha = mysql_query("SELECT * FROM onecms_content WHERE games = '".$r[game]."' AND cat = 'reviews' LIMIT 1");
$jh = mysql_num_rows($searcha);
if ($jh > "0") {
while($ra = mysql_fetch_array($searcha)) {

$sql3 = $ra[Overall];
}
} else {
$sql3 = "";
}

echo "<tr><td><a href='index.php?gid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td>".$sql3."</td>";

if ($_COOKIE[username]) {
echo "<td><a href='javascript:awindow(\"elite.php?view=elitef&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitep&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitet&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitec&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1'></a></td>";
}
echo "</tr>";
}

echo "</table>";
}

if (($_GET['view'] == "wishlist") && ($_GET['user'] == "")) {
authuser();

echo "<br><br><center><form action='elite.php?view=s' method='post'><b>Search for a game to add</b>&nbsp&nbsp<input type='text' name='search'>&nbsp&nbsp<input type='submit' name='submit' value='Search'></center></form><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Game</b></td><td><b>Genre</b></td><td><b>Users</b></td><td><b>Score</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'wishlist' AND pid = '".$useridn."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,genre FROM onecms_games WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'wishlist' AND game = '".$r[game]."'"));

$searcha = mysql_query("SELECT * FROM onecms_content WHERE games = '".$r[game]."' AND cat = 'reviews' LIMIT 1");
$jh = mysql_num_rows($searcha);
if ($jh > "0") {
while($ra = mysql_fetch_array($searcha)) {

$sql3 = $ra[Overall];
}
} else {
$sql3 = "";
}

echo "<tr><td><a href='index.php?gid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td>".$sql3."</td><td><a href='javascript:awindow(\"elite.php?view=elitef&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitep&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitet&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitec&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=delete&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'>Delete</a></td></tr>";
}

echo "</table>";
}

if (($_GET['view'] == "collection") && ($_GET['user'])) {
echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Game</b></td><td><b>Genre</b></td><td><b>Users</b></td><td><b>Score</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'collection' AND pid = '".$_GET['user']."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,genre FROM onecms_games WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'collection' AND game = '".$r[game]."'"));

$searcha = mysql_query("SELECT * FROM onecms_content WHERE games = '".$r[game]."' AND cat = 'reviews' LIMIT 1");
$jh = mysql_num_rows($searcha);
if ($jh > "0") {
while($ra = mysql_fetch_array($searcha)) {

$sql3 = $ra[Overall];
}
} else {
$sql3 = "";
}

echo "<tr><td><a href='index.php?gid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td>".$sql3."</td>";
if ($_COOKIE[username]) {
echo "<td><a href='javascript:awindow(\"elite.php?view=elitef&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitep&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitet&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitew&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1'></a></td>";
}
echo "</tr>";
}

echo "</table>";
}

if (($_GET['view'] == "collection") && ($_GET['user'] == "")) {
authuser();

echo "<br><br><center><form action='elite.php?view=s' method='post'><b>Search for a game to add</b>&nbsp&nbsp<input type='text' name='search'>&nbsp&nbsp<input type='submit' name='submit' value='Search'></center></form><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Game</b></td><td><b>Genre</b></td><td><b>Users</b></td><td><b>Score</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'collection' AND pid = '".$useridn."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,genre FROM onecms_games WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'collection' AND game = '".$r[game]."'"));

$searcha = mysql_query("SELECT * FROM onecms_content WHERE games = '".$r[game]."' AND cat = 'reviews' LIMIT 1");
$jh = mysql_num_rows($searcha);
if ($jh > "0") {
while($ra = mysql_fetch_array($searcha)) {

$sql3 = $ra[Overall];
}
} else {
$sql3 = "";
}

echo "<tr><td><a href='index.php?gid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td>".$sql3."</td><td><a href='javascript:awindow(\"elite.php?view=elitef&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitep&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitet&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitew&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=delete&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'>Delete</a></td></tr>";
}

echo "</table>";
}

if (($_GET['view'] == "tracked") && ($_GET['user'])) {
echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Game</b></td><td><b>Genre</b></td><td><b>Users</b></td><td><b>Score</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'tracked' AND pid = '".$_GET['user']."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,genre FROM onecms_games WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'tracked' AND game = '".$r[game]."'"));

$searcha = mysql_query("SELECT * FROM onecms_content WHERE games = '".$r[game]."' AND cat = 'reviews' LIMIT 1");
$jh = mysql_num_rows($searcha);
if ($jh > "0") {
while($ra = mysql_fetch_array($searcha)) {

$sql3 = $ra[Overall];
}
} else {
$sql3 = "";
}

echo "<tr><td><a href='index.php?gid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td>".$sql3."</td>";
if ($_COOKIE[username]) {
echo "<td><a href='javascript:awindow(\"elite.php?view=elitef&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitep&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitec&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitew&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1'></a></td>";
}
echo "</tr>";
}

echo "</table>";
}

if (($_GET['view'] == "tracked") && ($_GET['user'] == "")) {
authuser();

echo "<br><br><center><form action='elite.php?view=s' method='post'><b>Search for a game to add</b>&nbsp&nbsp<input type='text' name='search'>&nbsp&nbsp<input type='submit' name='submit' value='Search'></center></form><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Game</b></td><td><b>Genre</b></td><td><b>Users</b></td><td><b>Score</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'tracked' AND pid = '".$useridn."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,genre FROM onecms_games WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'tracked' AND game = '".$r[game]."'"));

$searcha = mysql_query("SELECT * FROM onecms_content WHERE games = '".$r[game]."' AND cat = 'reviews' LIMIT 1");
$jh = mysql_num_rows($searcha);
if ($jh > "0") {
while($ra = mysql_fetch_array($searcha)) {

$sql3 = $ra[Overall];
}
} else {
$sql3 = "";
}

echo "<tr><td><a href='index.php?gid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td>".$sql3."</td><td><a href='javascript:awindow(\"elite.php?view=elitef&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitep&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitec&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitew&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=delete&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'>Delete</a></td></tr>";
}

echo "</table>";
}

if (($_GET['view'] == "playing") && ($_GET['user'])) {
echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Game</b></td><td><b>Genre</b></td><td><b>Users</b></td><td><b>Score</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'playing' AND pid = '".$_GET['user']."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,genre FROM onecms_games WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'playing' AND game = '".$r[game]."'"));

$searcha = mysql_query("SELECT * FROM onecms_content WHERE games = '".$r[game]."' AND cat = 'reviews' LIMIT 1");
$jh = mysql_num_rows($searcha);
if ($jh > "0") {
while($ra = mysql_fetch_array($searcha)) {

$sql3 = $ra[Overall];
}
} else {
$sql3 = "";
}

echo "<tr><td><a href='index.php?gid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td>".$sql3."</td>";
if ($_COOKIE[username]) {
echo "<td><a href='javascript:awindow(\"elite.php?view=elitef&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitet&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitec&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitew&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1'></a></td>";
}
echo "</tr>";
}

echo "</table>";
}

if (($_GET['view'] == "playing") && ($_GET['user'] == "")) {
authuser();

echo "<br><br><center><form action='elite.php?view=s' method='post'><b>Search for a game to add</b>&nbsp&nbsp<input type='text' name='search'>&nbsp&nbsp<input type='submit' name='submit' value='Search'></center></form><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Game</b></td><td><b>Genre</b></td><td><b>Users</b></td><td><b>Score</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'playing' AND pid = '".$useridn."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,genre FROM onecms_games WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'playing' AND game = '".$r[game]."'"));

$searcha = mysql_query("SELECT * FROM onecms_content WHERE games = '".$r[game]."' AND cat = 'reviews' LIMIT 1");
$jh = mysql_num_rows($searcha);
if ($jh > "0") {
while($ra = mysql_fetch_array($searcha)) {

$sql3 = $ra[Overall];
}
} else {
$sql3 = "";
}

echo "<tr><td><a href='index.php?gid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td>".$sql3."</td><td><a href='javascript:awindow(\"elite.php?view=elitef&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitet&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitec&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitew&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=delete&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'>Delete</a></td></tr>";
}

echo "</table>";
}

if (($_GET['view'] == "favorites") && ($_GET['user'])) {
echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Game</b></td><td><b>Genre</b></td><td><b>Users</b></td><td><b>Score</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'favorites' AND pid = '".$_GET['user']."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,genre FROM onecms_games WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'favorites' AND game = '".$r[game]."'"));

$searcha = mysql_query("SELECT * FROM onecms_content WHERE games = '".$r[game]."' AND cat = 'reviews' LIMIT 1");
$jh = mysql_num_rows($searcha);
if ($jh > "0") {
while($ra = mysql_fetch_array($searcha)) {

$sql3 = $ra[Overall];
}
} else {
$sql3 = "";
}

echo "<tr><td><a href='index.php?gid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td>".$sql3."</td>";
if ($_COOKIE[username]) {
echo "<td><a href='javascript:awindow(\"elite.php?view=elitep&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitet&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitec&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitew&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1'></a></td>";
}
echo "</tr>";
}

echo "</table>";
}

if (($_GET['view'] == "favorites") && ($_GET['user'] == "")) {
authuser();

echo "<br><br><center><form action='elite.php?view=s' method='post'><b>Search for a game to add</b>&nbsp&nbsp<input type='text' name='search'>&nbsp&nbsp<input type='submit' name='submit' value='Search'></center></form><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Game</b></td><td><b>Genre</b></td><td><b>Users</b></td><td><b>Score</b></td></tr>";

$search = mysql_query("SELECT * FROM onecms_elite WHERE type = 'favorites' AND pid = '".$useridn."'");
while($r = mysql_fetch_array($search)) {

$sql = mysql_fetch_row(mysql_query("SELECT name,genre FROM onecms_games WHERE id = '".$r[game]."'"));

$sql2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE type = 'favorites' AND game = '".$r[game]."'"));

$searcha = mysql_query("SELECT * FROM onecms_content WHERE games = '".$r[game]."' AND cat = 'reviews' LIMIT 1");
$jh = mysql_num_rows($searcha);
if ($jh > "0") {
while($ra = mysql_fetch_array($searcha)) {

$sql3 = $ra[Overall];
}
} else {
$sql3 = "";
}

echo "<tr><td><a href='index.php?gid=".$r[game]."'>".$sql[0]."</a></td><td>".$sql[1]."</td><td>".$sql2."</td><td>".$sql3."</td><td><a href='javascript:awindow(\"elite.php?view=elitep&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitet&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitec&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitew&id=".$r[game]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=delete&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'>Delete</a></td></tr>";
}

echo "</table>";
}


if (($_GET['view'] == "friendss") && ($_GET['user'] == "")) {
authuser();

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}



$from = (($page * $max_results) - $max_results);

$searca = mysql_query("SELECT * FROM onecms_profile WHERE username LIKE '%" . $_POST['search'] . "%'");
$results = mysql_num_rows($searca);

echo "<title>".$sitename." :: ".$results." Search Results For - ".$_POST['search']."<table cellpadding='5' cellspacing='3' border='0' align='center'><tr><td align='center'><center>".$results." Results For :: ".$_POST['search']."</center><br><br></td></tr>";

$search = mysql_query("SELECT * FROM onecms_profile WHERE username LIKE '%" . $_POST['search'] . "%'");
while($row = mysql_fetch_array($search)) {
		$n1 = "/".$_POST['search']."/";
		$n2 = "<b>".$_POST['search']."</b>";
		$name = preg_replace($n1, $n2, $row[username]);

		similar_text($_POST['search'], $row[username], $p);

		echo "<tr><td><a href='elite.php?user=".$row[id]."'>".$name."</a></td><td><b>Relevance</b>: ".round(substr($p, 0, 4), 2)."%</td><td><a href='javascript:awindow(\"elite.php?view=friendsver4&id=".$row[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f2.jpg' border='1'></a></td></tr>";
}
echo "</table>";
}

if (($_GET['view'] == "friends") && ($_GET['user'])) {

	$sqla = mysql_query("SELECT username FROM onecms_profile WHERE id = '".$_GET['user']."'");
	$sta = mysql_fetch_row($sqla);

echo "<table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" valign=\"top\"><tr><td><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\"><tr><td><center><b>".$sta[0]."'s Friends</b></center></td></tr><tr>";

$qe = mysql_query("SELECT * FROM onecms_friends WHERE pid = '".$_GET['user']."' OR pid2 = '".$_GET['user']."' AND ver = 'yes' ORDER BY `id` DESC");

$ia = mysql_num_rows($qe);

for($i = 1; $r = mysql_fetch_assoc($qe); $i++) {

	$sql = mysql_query("SELECT username,avatar FROM onecms_profile WHERE id = '".$r[pid2]."'");
	$st = mysql_fetch_row($sql);

	$user = $st[0];

	if ($user == $_COOKIE[username]) {
	} else {

	$page = @file_get_contents("$st[1]");
	if ($page == NULL) {
		$avatar = "<img src='".$siteurl."/a_images/noavatar.jpg' border='1'>";
	} else {
	$avatar = "<img src='".$st[1]."'";

	list($widtha, $heighta) = getimagesize("".$st[1]."");

	if ($widtha > $avat1) {
	$avatar .= " width='".$avat1."'";
	}
	if ($heighta > $avat2) {
	$avatar .= " height='".$avat1."'";
	}

	$avatar .= " border='1'>";
	}

	echo "<td><a href='elite.php?user=".$r[pid2]."'><i>".$user."</i></a><br>".$avatar."</td>";

	if (($ia % 3) === 0) {
		echo "</tr><tr>";
	}
}
}
echo "</tr></table></td></tr></table>";
}

if (($_GET['view'] == "friends") && ($_GET['user'] == "")) {
authuser();

echo "<br><br><center><form action='elite.php?view=friendss' method='post'><b>Search for a member to add</b>&nbsp&nbsp<input type='text' name='search'>&nbsp&nbsp<input type='submit' name='submit' value='Search'></center></form><br><table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" valign=\"top\"><tr><td><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\"><tr><td><center><b>Current Friends</b></center></td></tr>";

$qe = mysql_query("SELECT * FROM onecms_friends WHERE pid = '".$useridn."' AND ver = 'yes' ORDER BY `id` DESC");

for($i = 1; $r = mysql_fetch_assoc($qe); $i++) {

	$sql = mysql_query("SELECT username,avatar FROM onecms_profile WHERE id = '".$r[pid2]."'");
	$st = mysql_fetch_row($sql);

	$user = $st[0];

	$page = @file_get_contents("$st[1]");
	if ($page == NULL) {
		$avatar = "<img src='".$siteurl."/a_images/noavatar.jpg' border='1'>";
	} else {
	$avatar = "<img src='".$st[1]."'";

	list($widtha, $heighta) = getimagesize("".$st[1]."");

	if ($widtha > $avat1) {
	$avatar .= " width='".$avat1."'";
	}
	if ($heighta > $avat2) {
	$avatar .= " height='".$avat1."'";
	}

	$avatar .= " border='1'>";
	}

	echo "<tr><td><a href='elite.php?user=".$r[pid2]."'><i>".$user."</i></a><br>".$avatar."</td></tr>";
}

$qe = mysql_query("SELECT * FROM onecms_friends WHERE pid2 = '".$useridn."' AND ver = 'yes' ORDER BY `id` DESC");

for($i = 1; $r = mysql_fetch_assoc($qe); $i++) {

	$sql = mysql_query("SELECT username,avatar FROM onecms_profile WHERE id = '".$r[pid]."'");
	$st = mysql_fetch_row($sql);

	$user = $st[0];

	$page = @file_get_contents("$st[1]");
	if ($page == NULL) {
		$avatar = "<img src='".$siteurl."/a_images/noavatar.jpg' border='1'>";
	} else {
	$avatar = "<img src='".$st[1]."'";

	list($widtha, $heighta) = getimagesize("".$st[1]."");

	if ($widtha > $avat1) {
	$avatar .= " width='".$avat1."'";
	}
	if ($heighta > $avat2) {
	$avatar .= " height='".$avat1."'";
	}

	$avatar .= " border='1'>";
	}

	echo "<tr><td><a href='elite.php?user=".$r[pid]."'><i>".$user."</i></a><br>".$avatar."</td></tr>";
}
echo "</table></td><td><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\"><tr><td><center><b>Incoming Requests</b></center></td></tr>";

$qe = mysql_query("SELECT * FROM onecms_friends WHERE pid2 = '".$useridn."' AND ver = 'no' OR ver = '' ORDER BY `id` DESC");

for($i = 1; $r = mysql_fetch_assoc($qe); $i++) {

	$sql = mysql_query("SELECT username,avatar FROM onecms_profile WHERE id = '".$r[pid]."'");
	$st = mysql_fetch_row($sql);

	$user = $st[0];

	$page = @file_get_contents("$st[1]");
	if ($page == NULL) {
		$avatar = "<img src='".$siteurl."/a_images/noavatar.jpg' border='1'>";
	} else {
	$avatar = "<img src='".$st[1]."'";

	list($widtha, $heighta) = getimagesize("".$st[1]."");

	if ($widtha > $avat1) {
	$avatar .= " width='".$avat1."'";
	}
	if ($heighta > $avat2) {
	$avatar .= " height='".$avat1."'";
	}

	$avatar .= " border='1'>";
	}

	echo "<tr><td><a href='elite.php?user=".$r[pid]."'><i>".$user."</i></a><br>".$avatar."<br><center><b>[</b> <a href='javascript:awindow(\"elite.php?view=friendsver1&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'>Accept</a> <b>|</b> <a href='javascript:awindow(\"elite.php?view=friendsver2&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'>Decline</a> <b>]</b></center></td></tr>";
}

echo "</table></td><td><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\"><tr><td><center><b>Outgoing Requests</b></center></td></tr>";

$qe = mysql_query("SELECT * FROM onecms_friends WHERE pid = '".$useridn."' AND ver = 'no' OR ver = '' ORDER BY `id` DESC");

for($i = 1; $r = mysql_fetch_assoc($qe); $i++) {

	$sql = mysql_query("SELECT username,avatar FROM onecms_profile WHERE id = '".$r[pid2]."'");
	$st = mysql_fetch_row($sql);

	$user = $st[0];

	$page = @file_get_contents("$st[1]");
	if ($page == NULL) {
		$avatar = "<img src='".$siteurl."/a_images/noavatar.jpg' border='1'>";
	} else {
	$avatar = "<img src='".$st[1]."'";

	list($widtha, $heighta) = getimagesize("".$st[1]."");

	if ($widtha > $avat1) {
	$avatar .= " width='".$avat1."'";
	}
	if ($heighta > $avat2) {
	$avatar .= " height='".$avat1."'";
	}

	$avatar .= " border='1'>";
	}

	echo "<tr><td><a href='elite.php?user=".$r[pid2]."'><i>".$user."</i></a><br>".$avatar."<br><center><b>[</b> <a href='javascript:awindow(\"elite.php?view=friendsver3&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'>Delete</a> <b>]</b></center></td></tr>";
}

echo "</table></td></tr></table>";
}

if ($_GET['view'] == "topgames") {
echo "<center><img src='".$siteurl."/a_images/top10.png' border='1'></center><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\">";
$qe = mysql_query("SELECT * FROM onecms_games ORDER BY `stats` DESC LIMIT 10");
for($i = 1; $r = mysql_fetch_assoc($qe); $i++) {
echo "<tr><td><b>".$i."</b></td><td><a href='index.php?gid=".$r[id]."'>".$r[name]."</a></td><td>".$r[genre]."</td>";

if ($_COOKIE[username]) {echo "<td><a href='javascript:awindow(\"elite.php?view=elitef&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitep&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitet&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitec&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1'></a></td><td><a href='javascript:awindow(\"elite.php?view=elitew&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1'></a></td>";
}
echo "</tr>";
}
echo "</table>";
}

if (($_GET['view'] == "") && ($_GET['user'] == "")) {
authuser();

    if ($_COOKIE[username] == "") {
	echo "Sorry but you aren't logged in, you cant edit your profile. <a href='members.php?action=register'>Register</a> or <a href='members.php?action=login'>Login</a>";
	} else {

	if (!$_POST['submit']) {
	$query="SELECT * FROM onecms_profile WHERE username = '".$_COOKIE[username]."'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		$aim = "".stripslashes($row[aim])."";
		$msn = "".stripslashes($row[msn])."";
		$website = "".stripslashes($row[website])."";
		$nickname = "".stripslashes($row[nickname])."";
		$location = "".stripslashes($row[location])."";
		$sig = "".stripslashes($row[sig])."";
		$avatar = "".stripslashes($row[avatar])."";
	
		echo "<center><a href='elite.php?user=".$useridn."'>View Profile</a><br><a href='members.php?action=changepass'>Change Password</a></center><br><form action='elite.php' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>AIM</b></td><td><input type='text' name='aim' value='".$aim."'></td></tr><tr><td><b>MSN</b></td><td><input type='text' name='msn' value='".$msn."'></td></tr><tr><td><b>Website</b></td><td><input type='text' name='website' value='".$website."'></td></tr><tr><td><b>Nickname</b></td><td><input type='text' name='nickname' value='".$nickname."'></td></tr><tr><td><b>Location</b></td><td><input type='text' name='location' value='".$location."'></td></tr><tr><td><b>Avatar</b></td><td><input type='text' name='avatar' value='".$avatar."' size='36'></td><td>";
		if ($avatar) {
		echo "<script language='javascript'>function awindow(towhere, newwinname, properties) {window.open(towhere,newwinname,properties);}</script>";
		
		list($widtha, $heighta) = getimagesize("".$avatar."");

		$heighta2 = $heighta + 16;
		$widtha2 = $widtha + 16;

		echo "<a href='javascript:awindow(\"".$avatar."\", \"\", \"width=".$widtha2.",height=".$heighta2.",scroll=yes\")'><b>View Current Avatar</b></a>";
		}
		echo "</td></tr><tr><td><b>Signature</b></td><td><textarea name='sig' cols='33' rows='12'>".$sig."</textarea></td></tr>";

			$query2 = "SELECT * FROM onecms_fields WHERE cat = 'users' ORDER BY `id`";
	$result2 = mysql_query($query2);
	while($row2 = mysql_fetch_array($result2)) {
		$name = "$row2[name]";

		echo "<tr><td><b>".$name."";
		if ($row2[des]) {
			echo " <a href='javascript:awindow(\"a_a_help.php?id=$row2[id]\", \"\", \"width=200,height=200,scroll=yes\")'><b>?</b></a>";
		}
		
		if ($row2[type] == "textarea") {
			echo "</td><td><textarea name='$name' cols=\"40\" rows=\"16\">".$row["$name"]."</textarea></td></tr>";
		} else {
			echo "</td><td><input type=\"text\" name='$name' value='".$row["$name"]."'></td></tr>";
		}
	}
	}
	echo "<tr><td><input type='submit' name='submit' value='Submit Changes'></td></tr></table></form>";
	}
	}
	if ($_POST['submit']) {
	$query = mysql_query("SELECT * FROM onecms_profile WHERE username = '".$_COOKIE[username]."'");
	$rows = mysql_num_rows($query);

	if ($rows == "1") {

	$_POST["sig"] = addslashes($_POST["sig"]);

    $edit2 = "UPDATE onecms_profile SET aim = '".$_POST["aim"]."', msn = '".$_POST["msn"]."', website = '".$_POST["website"]."', nickname = '".$_POST["nickname"]."', location = '".$_POST["location"]."', sig = '".$_POST["sig"]."', avatar = '".$_POST["avatar"]."'";
	
	$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'users' ORDER BY `id`") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		$name = "$row[name]";
		$_POST["$name"] = addslashes($_POST["$name"]);
		if ($_POST["$name"]) {
		$edit2 .= ", ".$name." = '".$_POST["$name"]."'";
		}
	}
	
	$edit2 .= " WHERE username = '".$username."'";

    $edit = mysql_query($edit2) or die(mysql_error());
	} else {
    $edit2 = "INSERT INTO onecms_profile VALUES ('null', '".$_COOKIE[username]."', '".$_POST["aim"]."', '".$_POST["msn"]."', '".$_POST["website"]."', '".$_POST["nickname"]."', '".$_POST["location"]."', '".$_POST["sig"]."', '".$_POST["avatar"]."'";

	   		$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'users' ORDER BY `id`") or die(mysql_error());
	while($row = mysql_fetch_array($query)) {
		$name = "$row[name]";
		$_POST["$name"] = addslashes($_POST["$name"]);
		if ($_POST["$name"] == "") {
		$edit2 .= ", ''";
		} else {
		$edit2 .= ", '".$_POST["$name"]."'";
		}
	}
$edit2 .= ")";

	$edit = mysql_query($edit2) or die(mysql_error());
	}
   if ($edit == TRUE) {
	   echo "Your profile has been updated.";
   }
		}
   }

   }

footera();
}

}
}
}
?>
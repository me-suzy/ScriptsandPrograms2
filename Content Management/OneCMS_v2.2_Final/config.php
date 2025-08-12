<?php 
    $timeabc = microtime(); // do not change

    $dbhost = "localhost";
	$dbname = ""; // mysql database name
	$dbuser = ""; // mysql database username
	$dbpass = ""; // mysql database password
	$avat1 = "100"; // Avatar width
	$avat2 = "100"; // Avatar height
	$blog1 = "#555555"; // Color for blog entries for the subject/date part
	$blog2 = "#DDDDDD"; // Color for blog entries for the actual blog part

    $avat1 = "100"; // max width of avatar
    $avat2 = "100"; // max height of avatar
    $top10 = "10"; // how many games to list for random 10
	$quality = "100"; // quality of images for watermark
	$galtemplate = "<td><a href='{url}'><img src='{thumb}' border='1'></a><br><b>{name}</b><br><center>{icon}</center></td>"; // gallery.php - main template
	$timeamount = "no"; // see how on index.php it says how long it took to load the page? set this to yes for it to show and no for it not to show

	// START FUNCTIONS

    include ("functions.php");

    // END FUNCTIONS

	// POINTS START

	$points1 = "2"; // amount of points awarded for each post
	$points2 = "3"; // amount of points awarded for each topic
	$points3 = "5"; // amount of points awarded for each user review
	$points4 = "1"; // amount of points awarded for each rating
	$points5 = "2"; // amount of points awarded for each comment
	$points6 = "1"; // amount of points awarded for each friend (verified)
	$points7 = "1"; // amount of points awarded for each tracked game
	$points8 = "1"; // amount of points awarded for each game added to collection
	$points9 = "1"; // amount of points awarded for each game added to wishlist
	$points10 = "1"; // amount of points awarded for each game added to playing
	$points11 = "1"; // amount of points awarded for each game added to favorites
	$points12 = "1"; // amount of points awarded for each system added to systems

	// POINTS END

	//-- DO NOT CHANGE ANYTHING BELOW THIS LINE --
    if (!$install == "yes") {
	mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass)) or die (mysql_error());

	$query="SELECT * FROM onecms_settings WHERE id = '1'";
	$result=mysql_query($query);
	while($row = @mysql_fetch_array($result)) {
		$sitename = "$row[sitename]";
		$siteurl = "$row[siteurl]";
		if ($row[online]) {
		$online = "$row[online]";
		} else {
		$online = "Yes";
		}
		if ($row[dformat]) {
		$dformat = "$row[dformat]";
		} else {
		$dformat = "M d - Y";
		}
		if ($row[warn]) {
		$warn = "$row[warn]";
		} else {
		$warn = "3";
		}
		$path = "$row[path]";
		$images = "$row[images]";
		if ($row[max_results]) {
		$max_results = "$row[max_results]";
		} else {
		$max_results = "30";
		}
		$email = "$row[email]";
		$name = "$row[name]";
		if ($row[width]) {
		$width = "$row[width]";
		} else {
		$width = "120";
		}
		if ($row[height]) {
		$height = "$row[height]";
		} else {
		$height = "90";
		}
	}

	$query="SELECT * FROM onecms_settings WHERE id = '2'";
	$result=mysql_query($query);
	while($row = @mysql_fetch_array($result)) {
		if ($row[sitename]) {
		$ip = "$row[sitename]";
		} else {
		$ip = "True";
		}
		if ($row[siteurl]) {
		$wysiwyg = "$row[siteurl]";
		} else {
		$wysiwyg = "False";
		}
		if ($row[online]) {
		$pm = "$row[online]";
		} else {
		$pm = "50";
		}
		if ($row[warn] == "No") {
		$modrewrite = "$row[warn]";
		} else {
		$modrewrite = "Yes";
		}
	}

	$query="SELECT * FROM onecms_settings WHERE id = '3'";
	$result=mysql_query($query);
	while($row = @mysql_fetch_array($result)) {
		if ($row[sitename]) {
		$a = "$row[sitename]";
		} else {
		$a = "yes";
		}
		if ($row[siteurl]) {
		$b = "$row[siteurl]";
		} else {
		$b = "yes";
		}
		if ($row[online]) {
		$c = "$row[online]";
		} else {
		$c = "yes";
		}
		if ($row[dformat]) {
		$d = "$row[dformat]";
		} else {
		$d = "no";
		}
		if ($row[warn]) {
		$e = "$row[warn]";
		} else {
		$e = "25";
		}
		if ($row[images]) {
		$u = "$row[images]";
		} else {
		$u = "10";
		}
		$color1 = "$row[path]";
		$color2 = "$row[max_results]";
		$color3 = "$row[email]";
		$cu = "no";
	}

	$query="SELECT * FROM onecms_settings WHERE id = '4'";
	$result=mysql_query($query);
	while($row = @mysql_fetch_array($result)) {
		if ($row[sitename] == "albums") {
		mysql_query("UPDATE onecms_settings SET sitename = '<td><a href='{image}'><img src='{thumb}' border='1'></a></td>' WHERE id = '4'");
		}

		if ($row[sitename]) {
		$albtemplate = "$row[sitename]";
		} else {
		$albtemplate = "<td><a href='{image}'><img src='{thumb}' border='1'></a></td>";
		}
        if ($row[images]) {
		$albtemplate2 = "$row[images]";
		} else {
	    $albtemplate2 = "<table align='center' width='95%' border='0' cellpadding='3' cellspacing='1'><tr>";
		}
		if ($row[path]) {
		$albtemplate3 = "$row[path]";
		} else {
		$albtemplate3 = "</tr></table>";
		}
		if ($row[siteurl]) {
		$albrow = "$row[siteurl]";
		} else {
		$albrow = "3";
		}
		if ($row[online]) {
		$albpage = "$row[online]";
		} else {
		$albpage = "15";
		}
		if ($row[dformat]) {
		$albpages = "$row[dformat]";
		} else {
		$albpages = "Yes";
		}
		if ($row[warn]) {
		$albsep = "$row[warn]";
		} else {
		$albsep = "<br>";
		}
	}

		$query="SELECT * FROM onecms_settings WHERE id = '5'";
	$result=mysql_query($query);
	while($row = @mysql_fetch_array($result)) {
		if ($row[sitename]) {
		$chat = "$row[sitename]";
		} else {
		$chat = "10";
		}
		$chat2[1] = "$row[siteurl]";
		$chat2[2] = "$row[online]";
		$chat2[3] = "$row[dformat]";
		$chat2[4] = "$row[warn]";
		$chat2[5] = "$row[images]";
		$chat2[6] = "$row[path]";
	}

	$query="SELECT * FROM onecms_settings WHERE id = '6'";
	$result=mysql_query($query);
	while($row = @mysql_fetch_array($result)) {
		if ($row[sitename]) {
		$template1[0] = "$row[sitename]";
		} else {
		$template1[0] = "latestcontent";
		}
		if ($row[siteurl]) {
		$template1[1] = "$row[siteurl]";
		} else {
		$template1[1] = "list2-games";
		}
		if ($row[online]) {
		$template1[2] = "$row[online]";
		} else {
		$template1[2] = "companies";
		}
		if ($row[dformat]) {
		$template2[0] = "$row[dformat]";
		} else {
		$template2[0] = "Yes";
		}
		if ($row[warn]) {
		$template2[1] = "$row[warn]";
		} else {
		$template2[1] = "No";
		}
	}

   // MOD REWRITE START - variables set for enable/disable feature

   if ($modrewrite == "Yes") {
   $part1 = "id";
   $part2 = ".html";
   } else {
   $part1 = "index.php?id=";
   $part2 = "";
   }

   if ($modrewrite == "Yes") {
   $gamepart1 = "game";
   $gamepart2 = ".html";
   } else {
   $gamepart1 = "index.php?gid=";
   $gamepart2 = "";
   }

   if ($modrewrite == "Yes") {
   $ppart1 = "company";
   $ppart2 = ".html";
   } else {
   $ppart1 = "index.php?pid=";
   $ppart2 = "";
   }

   if ($modrewrite == "Yes") {
   $pagepart1 = "page_";
   $pagepart2 = ".html";
   } else {
   $pagepart1 = "pages.php?page=";
   $pagepart2 = "";
   }

   if ($modrewrite == "Yes") {
   $gpart1 = "gallery";
   $gpart2 = ".html";
   } else {
   $gpart1 = "gallery.php?id=";
   $gpart2 = "";
   }

   if ($modrewrite == "Yes") {
   $f1part1 = "forum";
   $f1part2 = ".html";
   $f2part1 = "topic";
   $f2part2 = ".html";
   $forumsurl = "forums.html";
   } else {
   $f1part1 = "boards.php?f=";
   $f1part2 = "";
   $f2part1 = "boards.php?t=";
   $f2part2 = "";
   $forumsurl = "boards.php";
   }

   // MOD REWRITE END

   $time = time();
   $version = "2.2"; // DO NOT CHANGE

if ($_POST['userreview']) {
$rex = explode("|", $_POST['userreview3']);
$rate1 = $rex[0] + 1;
$rate2 = $rex[1] + $_POST['userreview'];
$rate = "".$rate1."|".$rate2."";
mysql_query("UPDATE onecms_userreviews SET rate = '".$rate."' WHERE id = '".$_POST['userreview2']."'");
$rate = "";
$rate1 = "";
$rate2 = "";
$rex = "";
}

$fieldcheck1 = mysql_query("SELECT * FROM onecms_fields ORDER BY `id` DESC");
while($r = @mysql_fetch_array($fieldcheck1)) {
$fnum1 = @mysql_num_rows(mysql_query("SELECT * FROM onecms_fields WHERE name = '".$r[name]."' AND cat = '".$r[cat]."'"));

if ($fnum1 > "1") {
$delete = mysql_query("DELETE FROM onecms_fields WHERE id = '".$fid."'");
}
$fid = "";
}


$boardcheck1 = mysql_query("SELECT * FROM onecms_posts");
while($r = @mysql_fetch_array($boardcheck1)) {
if ($r[tid] == "") {
if ($r[type] == "post") {
mysql_query("DELETE FROM onecms_posts WHERE id = '".$r[id]."'");
}
if ((($r[type] == "topic") or ($r[type] == "Announcement") or ($r[type] == "Sticky"))) {
mysql_query("UPDATE onecms_posts SET tid = '".$r[id]."' WHERE id = '".$r[id]."'");
}
}
} // makes sure there are no posts with an invalid topic id

$userf = @mysql_fetch_row(mysql_query("SELECT level,email,skin,skin2,logged,warn FROM onecms_users WHERE username = '".stripslashes($_COOKIE[username])."' AND password = '".stripslashes($_COOKIE[password])."'"));
$level = $userf[0];
$email = $userf[1];
$skins = $userf[2];
$forumskin = $userf[3];
$logged = $userf[4];
$naum = $userf[5]; // fetches user info

$userflvl = @mysql_fetch_row(mysql_query("SELECT level FROM onecms_userlevels WHERE name = '".$level."'"));
$userlevel = $userflvl[0]; // fetches level number (1,2,3,4,5 or 6)

$useridf = @mysql_fetch_row(mysql_query("SELECT id FROM onecms_profile WHERE username = '".stripslashes($_COOKIE[username])."'"));
if ($useridf[0]) {
$useridn = $useridf[0]; // fetches user id
} else {
$useridn = "";
}

$userfperm = @mysql_fetch_row(mysql_query("SELECT ver FROM onecms_permissions WHERE username = '".stripslashes($_COOKIE[username])."'"));
$ver = $userfperm[0]; // fetches verification permission
}

// AUTH START...checks to see if user is logged in and if so the details are accurate
// also if user is not logged in they are taken to the login page

if ($z == "") {
if (($_COOKIE[username]) && ($_COOKIE[password])) {
$sqll = mysql_query("SELECT * FROM onecms_users WHERE username = '".$_COOKIE[username]."' AND password = '".$_COOKIE[password]."'");
$logincount = mysql_num_rows($sqll);
if ($logincount == "0") {
header('location: a_login.php');
} else {
mysql_query("UPDATE onecms_users SET logged = '".time()."' WHERE username = '".$_COOKIE[username]."' AND password = '".$_COOKIE[password]."'");
if ($_POST['skin']) {
mysql_query("UPDATE onecms_users SET skin = '".$_POST['skin']."' WHERE username = '".$_COOKIE[username]."'");
}
if ($_POST['skin2']) {
mysql_query("UPDATE onecms_users SET skin2 = '".$_POST['skin2']."' WHERE username = '".$_COOKIE[username]."'");
}
if ($userlevel > "5") { // MAKE SURE NOT A MEMBER
header('location: a_login.php?login=no');
}
}
}

if (($_COOKIE[username] == "") or ($_COOKIE[password] == "")) {
header('location: a_login.php');
}
}
// AUTH END

// WARN/BAN CHECK START
if (($j == "") && ($warn == $naum)) {
} else {
$sql = mysql_query("SELECT * FROM onecms_users WHERE banadmin = 'yes' AND username = '".stripslashes($_COOKIE[username])."' AND password = '".stripslashes($_COOKIE[password])."'");
if ($_COOKIE[username]) {
$numv = @mysql_num_rows($sql);
} else {
$numv = "0";
}

if ($install == "") {

$sql3 = mysql_query("SELECT * FROM onecms_ipban WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND site = 'yes'");
$ipbancheck1 = @mysql_num_rows($sql3);

$sql3a = mysql_query("SELECT * FROM onecms_ipban WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND forums = 'yes'");
$ipbancheck2 = @mysql_num_rows($sql3a);

$sql4a = mysql_query("SELECT * FROM onecms_ipban WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND cp = 'yes'");
$ipbancheck3 = @mysql_num_rows($sql4a);

}

$sql2 = mysql_query("SELECT * FROM onecms_users WHERE bansite = 'yes' AND username = '".stripslashes($_COOKIE[username])."' AND password = '".stripslashes($_COOKIE[password])."'");
if ($_COOKIE[username]) {
$numvb = @mysql_num_rows($sql2);
} else {
$numvb = "0";
}
}
// WARN/BAN CHECK END

if ($z == "") {
if ($numv == "0") {
include ("a_header.inc");
}

if ($wysiwyg == "True") {
	echo '<script language="javascript" type="text/javascript" src="wysiwyg/jscripts/tiny_mce/tiny_mce.js"></script><script language="javascript" type="text/javascript">tinyMCE.init({mode : "textareas"});</script>';
}
}
?>
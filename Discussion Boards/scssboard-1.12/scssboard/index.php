<?php 
$use_gzip = 1; //If your web server does not support GZIP encoding, set this to 0
include("system/gzip1.inc.php"); 
?>
<?php
/*
** sCssBoard, an extremely fast and flexible CSS-based message board system
** Copyright (CC) 2005 Elton Muuga
**
** This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 
** To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.0/ or send 
** a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*/
?>
<?php

	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$tstart = $mtime; 

if (file_exists("system/config.inc.php"))
include("system/connect.inc.php");
else
die("Before you can use sCssBoard, you need to <a href='install.php'>install it</a>.");

include("functions/global/main_functions.inc.php");
include("functions/global/breadcrumb_nav.php");

if (($_COOKIE[scb_uid]) and ($_COOKIE[scb_ident])) {
	$current_user = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]users where users_id = '$_COOKIE[scb_uid]' and users_password = '$_COOKIE[scb_ident]'"));
	if (!$current_user) {
		 die("Your cookies appear to be invalid.<br /><br /><strong>Members:</strong> Clear all your browser cookies and try again. Notify the forum administrator about this problem if it persists.<br /><br /><strong>Administrator:</strong> If you have two boards running on the same server, you must set the cookie paths to be unique for each board. Consult the sCssBoard documentation if you do not know how to do this.");
	}
	$ulvl = $current_user[users_level];
} else {
	$ulvl = 0;
}

header("Content-type: text/html; charset=utf-8");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?php echo $_MAIN[forumname] . " - " . $_PAGE[sub] . " (Powered By sCssBoard)"; 
echo "</title>\n";
if($current_user[users_style]) {
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/$current_user[users_style]\" />";
} else { 
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles/$_MAIN[default_style]\" />";
} 
?>
</head>
<body>
        
<div id='header'><a href="index.php"><?php echo $_MAIN[forumname]; ?></a></div>

<?php include("functions/global/member_bar.php"); ?>

<? echo "<div id='breadcrumb_nav'><a href='index.php?'>$_MAIN[forumname]</a>" . $_PAGE[url] . "</div>"; ?>

<br />

<?php
if($_GET[act]) { $act = $_GET[act]; }
if(!$act) { $act = "home"; }

$functions = array (
	"home"				=>		"functions/main.php",
	"register"			=>		"functions/register.php",
	"login"				=>		"functions/loginlogout.php",
	"logout"			=>		"functions/loginlogout.php",
	"profile"			=>		"functions/profile.php",
	"post"				=>		"functions/post.php",
	"users"				=>		"functions/users.php",
	"search"			=>		"functions/search.php",
	"search-results"	=>		"functions/searchresults.php",
	"showforum"			=>		"_showforum",
	//--------------------
	"admin-home"		=>		"admin/main.php",
	"admin-general"		=>		"admin/general.php",
	"admin-forums"		=>		"admin/forums.php"
	);

foreach ($functions as $func => $address) {
	if ($act == $func) {
		$inc_function = $address;
		break;
	}
}

if ($inc_function == "_showforum") {
	if ($_GET[t] == "") {
		$inc_function = "functions/showforum.php";
	} else {
		$inc_function = "functions/showtopic.php";
	}
}

if ($inc_function) {
	include($inc_function);
} else {
	include("functions/main.php");
}
?>

<br />

<div id='footer'>
	<?php 
	//Please don't remove this. We are not making this board for profit.
	//If you would like to start your own message board project, go ahead. This 
	//script is licensed under the Attribution-NonCommercial-ShareAlike 2.0 CC.
	//You may make modifications as long as you share them under the same license
	//and give us credit.
	//Thank you for using sCssBoard and we hope you can learn something from it.

	echo "<strong><a href='http://scssboard.if-hosting.com' target='_blank'>sCssBoard</a> $_MAIN[script_version]</strong><br /> <a href='http://creativecommons.org/licenses/by-nc-sa/2.0/'>(CC)</a> 2005 Elton Muuga and Mitchell Foral."; 	

	if($_MAIN[debug_level] >= 1) {
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$tend = $mtime;
		$totaltime = ($tend - $tstart);
		$totaltime = number_format($totaltime, 6, '.', '');
		echo "<br />Page generated in $totaltime seconds.";
	}
	?>
</div>


<?php
	//Debug level 2 is a potential security risk and should only be used
	//if you are adding features to the forum. Thus, it can only be activated
	//by editing the MySQL database.
		if($_MAIN[debug_level] == 2) {
			echo "<br /><table width='800' border='0' cellpadding='5' cellspacing='0' align='center'>";
			echo "<tr><td class='catheader'>GET Queries</td></tr><tr><td class='debug'>";
			echo "<pre>";
			print_r($_GET);
			echo "</pre>";
			echo "</td></tr>";
			echo "<tr><td class='catheader'>POST Queries</td></tr><tr><td class='debug'>";
			echo "<pre>";
			print_r($_POST);
			echo "</pre>";
			echo "</td></tr>";
			echo "</table>";
		}
?>

</body>
</html>
<?php include("system/gzip2.inc.php"); ?>

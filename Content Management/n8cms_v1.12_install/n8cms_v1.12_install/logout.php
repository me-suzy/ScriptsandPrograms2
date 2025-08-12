<?
session_start();

$usr_lvl = $_SESSION['user_level'];
//

if(!isset($_REQUEST['logmeout'])){
	echo "Are you sure you want to logout?<br />";
	echo "<a class='nav_links' href=logout.php?logmeout>Yes</a> | <a class='nav_links' href=javascript:history.back()>No</a>";
} else {
	session_destroy();
	if(!session_is_registered('first_name')){
		echo "<h2>You are logged out</h2><br />";
		echo "<a href=index.php class=nav_links style=font-size:12pt;><--Home</a><br>";
	echo"<script>setTimeout (document.location.replace('index.php'), 9000000);</script> ";
//		include ('html/login_form.html');		
	}
}
?>

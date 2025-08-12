<?php 

// do fileAction
$fileAction = "index";
$modArea = true;

include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");

// deal with sessions
// start the session
define("SESSIONID",md5($userinfo['userid'].$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']));

// set username and userid..
$userinfo['username'] = $_COOKIE['wtcBB_adminUsername'];
$userinfo['userid'] = $_COOKIE['wtcBB_adminUserid'];

$sessionInclude = doSessions("Admin Control Panel","none");
include("./../includes/sessions.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
<title> wtcBB Admin Panel </title>
</head>
<frameset cols="193px,100%" border="0" frameborder="0" framespacing="0">
	<frame src="navigation.php" name="nav" style="border-right: #000000 1px solid; cursor: w-resize;" />
	
	<?php
	// display different frame if modcp
	if(isset($_COOKIE['wtcBB_adminIsMod'])) {
		?>
		<frame src="moderator.php?do=index" name="content" />
		<?php
	}

	else {
		?>
		<frame src="content.php" name="content" />
		<?php
	}
	?>
</frameset>
<body>
</body>
</html>

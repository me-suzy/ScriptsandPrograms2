<?php

session_start();

error_reporting(E_ALL);

$execution_start = Utilities::getMicroTime();

function __autoload($class_name) {
	$class_file = strtolower($class_name).'.class.php';
	require_once "system/classes/$class_file";
}

include_once("system/classes/config.class.php");

$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : "main.display";
$module = Utilities::getModule($action);
$method = Utilities::getMethod($action);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?=Application::getWebsiteName();?> - Administration</title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<script language="JavaScript" type="text/javascript" src="system/resources/scripts/javascripts.js"></script>
	<link rel="stylesheet" type="text/css" href="system/resources/css/stylesheets.css">
</head>

<body>

<?php

if(Utilities::checkAdmin() == true) {
	
	if(!isset($_REQUEST['blank'])) {
		include("system/panels/admin.navigation.php");
	}
	
	if(file_exists("system/admin/$module.$method.php")) {
		
		if(!isset($_REQUEST['blank'])) {
			echo "<div id=\"contentAdmin\">";
		}
		
		include("system/admin/$module.$method.php");
		
		if(!isset($_REQUEST['blank'])) {
			echo "</div>";
		}
	}
	else {
		if(!isset($_REQUEST['blank'])) {
			echo "<div id=\"contentAdmin\">";
		}
		
		echo "Template not found.";
		
		if(!isset($_REQUEST['blank'])) {
			echo "</div>";
		}
	}
	
	include("system/panels/website.execution.php");
}
else {
	echo "<div id=\"contentAdmin\">";
	include("system/views/main.login.php");
	echo "</div>";
}

?>

</body>
</html>
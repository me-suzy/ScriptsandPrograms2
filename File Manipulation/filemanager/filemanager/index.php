<?php

session_start();

error_reporting(E_ALL);

/*
   +--------------------------------------------------------------------+
   | Project Name: FileManager | Version 1.0							|
   +--------------------------------------------------------------------+
   | Project Date: August 2005											|
   +--------------------------------------------------------------------+
   | Project Coder: Mike Kaufmann | www.cractix.ch | mike@cractix.ch	|
   +--------------------------------------------------------------------+
   | Project Comment: Rock`n Roll Baby ;)								|
   +--------------------------------------------------------------------+
*/

$execution_start = Utilities::getMicroTime();

function __autoload($class_name) {
	$class_file = strtolower($class_name).'.class.php';
	require_once "system/classes/$class_file";
}

include_once("system/classes/config.class.php");

if(isset($_SESSION['s_role']) && isset($_REQUEST['action']) && $_REQUEST['action'] == "main.login" || isset($_SESSION['s_role']) && !isset($_REQUEST['action'])) {
	
	Utilities::redirect("index.php?action=main.display");
}

$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : "";
$module = Utilities::getModule($action);
$method = Utilities::getMethod($action);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?=Application::getWebsiteName();?></title>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
	<script language="JavaScript" type="text/javascript" src="system/resources/scripts/javascripts.js"></script>
	<link rel="stylesheet" type="text/css" href="system/resources/css/stylesheets.css">
</head>

<body>

<?php

if(Utilities::checkLogin() == true) {
	include("system/panels/navigation.php");
}

if(file_exists("system/views/$module.$method.php")) {
	
	echo "<div id=\"content\">";
	include("system/views/$module.$method.php");
	echo "</div>";
}
else {
	
	echo "
	<div id=\"content\">
		Template not found.
	</div>
	";
}

$execution_end	= Utilities::getMicroTime();
$execution_time = round($execution_end - $execution_start,2);
if(Application::getExecutionTime() == "true") {
	echo "&nbsp;Execution-Time: $execution_time seconds";
}
?>

</body>
</html>
<?php

session_start();

function __autoload($class_name) {
	$class_file = strtolower($class_name).'.class.php';
	require_once "system/classes/$class_file";
}

include_once("system/classes/config.class.php");

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
	<style type="text/css" media="all">
		body {
			margin:             0px;
			padding:			10px;
			background:			#FFFFFF;
			font-family:		Arial, Helvetica, Verdana, sans-serif;
			color:				#000000;
			font-size:			11px;
			font-style:			normal;
			background-image:	none;
		}
	</style>
</head>

<body>

<?php
if(file_exists("system/admin/$module.$method.php")) {
	include("system/admin/$module.$method.php");
}
else {
	echo "Template not found.";
}
?>

</body>
</html>
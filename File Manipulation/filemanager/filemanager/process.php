<?php

session_start();

function __autoload($class_name) {
	$class_file = strtolower($class_name).'.class.php';
	require_once("system/classes/$class_file");
}

require_once("system/classes/config.class.php");

$action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : "";
$module = Utilities::getModule($action);
$method = Utilities::getMethod($action);

if(file_exists("system/actions/$module.$method.php")) {
	include("system/actions/$module.$method.php");
}
else {
	echo "Template not found.";
}

?>
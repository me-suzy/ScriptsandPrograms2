<?php

error_reporting(E_ERROR);
session_start();
global $inHead;

global $TABLE_PREFIX;
$TABLE_PREFIX = "plogger_";

session_register ("entries_per_page");

require_once("../plog-functions.php");
require_once("../plog-globals.php");

if ($_REQUEST["action"] == "log_in"){
	$query = "SELECT * FROM `".$TABLE_PREFIX."config`";
	$result = run_query($query);
	$row = mysql_fetch_assoc($result);

	if (($_REQUEST["username"] == $row["admin_username"]) && (md5($_REQUEST["password"]) == $row["admin_password"])){
		$_SESSION["plogger_logged_in"] = true;
	}
	else{
		header("Location: index.php?errorcode=1");
		exit;
	}
}
elseif($_REQUEST["action"] == "log_out"){
	$_SESSION = array();
	session_destroy();
}

if (!isset($_SESSION["plogger_logged_in"])){ 
	header("Location: index.php");
	exit;
}


function display($string, $current){
	global $inHead;
	global $config;

	$tabs = array();
	$tabs['upload'] 	= array('url' => 'plog-upload.php','caption' => 'Upload');
	$tabs['import'] 	= array('url' => 'plog-import.php','caption' => 'Import');
	$tabs['manage'] 	= array('url' => 'plog-manage.php','caption' => 'Manage');
	$tabs['feedback'] 	= array('url' => 'plog-feedback.php','caption' => 'Feedback');
	$tabs['options']	= array('url' => 'plog-options.php','caption' => 'Options');
	$tabs['view'] 		= array('url' => $config['baseurl'],'caption' => 'View');
	$tabs['logout'] 	= array('url' => $_SERVER["PHP_SELF"].'?action=log_out','caption' => 'Log out');

	$output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
				<title>Plogger Administration</title>
				<link href="../css/admin.css" type="text/css" rel="stylesheet" media="all"/>
				'.$inHead.'
			<script type="text/javascript" src="js/plogger.js"></script>
			</head>
			<body onload="focus_first_input()">
				<img src="../graphics/plogger.gif" alt="Plogger" />
				<div id="navcontainer">
					<ul id="navlist">';
					foreach($tabs as $tab => $data) {
						$output .= '<li';
						if ($current == $tab) $output .= ' id="active"';
						$output .= '><a';
						if ($current == $tab) $output .= ' id="current"';
						$output .= ' href="' . $data['url'] . '">' . $data['caption'] . '</a><li>';
					};
					$output .= '
					</ul>
				</div>
				'.$string.'
			</body>
		</html>';
	
	echo $output;
	exit;
}


?>

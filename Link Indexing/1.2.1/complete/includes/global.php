<?php
// global.php include file
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// include relevant files
include("config.php");
include("functions.php");
include("database.php");
include("usersys.php");

// check for lack of being installed and install directory
if ($dbname == ""){
	Header("Location: install/install.php");
	Die();
} elseif (is_dir("install/")){
	Header("Location: install/install.php?do=step3");
	Die();
}

// do database connection and classes
$db = New Database($dbhost, $dbuser, $dbpass, $dbname);

// check for skin setting
if ($_GET["skinselector"] <> ""){
	skinset($_GET["skinselector"]);
}

// extract configuration from db
$config = Array();
$sql = "SELECT * FROM " . $dbprefix . "config";
$result = $db->execute($sql, 1);
if ( !($result) )
	{ Die("Could not query config table");
} else {
	do{
		// put config into array
		$config[$result->fields["config_name"]] = $result->fields["config_value"];
	} while($result->loop());
}

// extract phrases from database
$phrases = Array();
$sql = "SELECT * FROM " . $dbprefix . "phrases";
$result = $db->execute($sql, 1);
if ($result){ if ($result->rows > 0){ do {
	$phrase[$result->fields["phrase_name"]] = $result->fields["phrase_value"];
} while($result->loop()); } }

// create user system
$usr = New Usersys;
StartSession();
$usr->Auth(0);
?>
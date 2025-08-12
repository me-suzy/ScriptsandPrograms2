<?php
require("../../includes/global.php");

$sql = "SELECT * FROM " . $dbprefix . "config WHERE config_name = 'versionint'";
$rec = $db->execute($sql);
$version = intval($rec->fields["config_value"]);

// check version
if ($version <> 6){ die("You are not running version 1.1.0"); }

// run the main SQL
$filename =  "../schema/upgrade_from_1.1.0.sql";
$contents = implode("", file($filename)); // join it into one string
$contents = str_replace("pl_", $dbprefix, $contents);
$contents = str_replace("\r\n", "\n", $contents); // convert to Unix EOL format if needed
$queries = explode(";\n", $contents); // split into separate queries
foreach ($queries as $query) {
	$result = $db->execute($query);
}

// let's multi-line the email body for the good people
$sql = "SELECT * FROM " . $dbprefix . "phrases WHERE phrase_name = 'submit_emailbody'";
$eml = $db->execute($sql);
if ($eml->rows > 0){
	$sql = "UPDATE " . $dbprefix . "phrases SET phrase_value = '" . addslashes($eml->fields["phrase_value"] . "\n") . "' WHERE phraseid = " . $eml->fields["phraseid"];
	$db->execute($sql);
}
$eml->clear();

// run the update SQL
$sql = "UPDATE " . $dbprefix . "config SET config_value = '1.2.0' WHERE config_name = 'version'";
$db->execute($sql);

$sql = "UPDATE " . $dbprefix . "config SET config_value = 7 WHERE config_name = 'versionint'";
$db->execute($sql);

// and redirect user to next upgrader
Header("Location: from_1.2.0.php");
?>
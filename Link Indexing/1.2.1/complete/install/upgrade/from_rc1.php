<?php
require("../../includes/global.php");

$sql = "SELECT * FROM " . $dbprefix . "config WHERE config_name = 'versionint'";
$rec = $db->execute($sql);
$version = intval($rec->fields["config_value"]);

// check version
if ($version <> 1){ die("You are not running Release Candidate 1"); }

// run the main SQL
$filename =  "../schema/upgrade_from_rc1.sql";
$contents = implode("", file($filename)); // join it into one string
$contents = str_replace("pl_", $dbprefix, $contents);
$contents = str_replace("\r\n", "\n", $contents); // convert to Unix EOL format if needed
$queries = explode(";\n", $contents); // split into separate queries
foreach ($queries as $query) {
	$result = $db->execute($query);
}

// update the config
$sql  = "INSERT INTO " . $dbprefix . "config (config_name, config_help, config_value)";
$sql .= " VALUES ('virtualpath', 'The folder from the web. For instance if it is on a domain just / or if it was in a sub-folder called example it would be /example/.', ";
$sql .= "'" . str_replace("install/upgrade/from_rc1.php", "", $_SERVER["PHP_SELF"]) . "')";
$db->execute($sql);

$sql = "UPDATE " . $dbprefix . "config SET config_value = 'RC2' WHERE config_name = 'version'";
$db->execute($sql);

$sql = "UPDATE " . $dbprefix . "config SET config_value = 2 WHERE config_name = 'versionint'";
$db->execute($sql);

// and redirect user to next upgrader
Header("Location: from_rc2.php");
?>
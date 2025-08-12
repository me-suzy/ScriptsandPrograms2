<?php
require("../../includes/global.php");

$sql = "SELECT * FROM " . $dbprefix . "config WHERE config_name = 'versionint'";
$rec = $db->execute($sql);
$version = intval($rec->fields["config_value"]);

// check version
if ($version <> 4){ die("You are not running Release Candidate 4"); }

// run the update SQL
$sql = "UPDATE " . $dbprefix . "config SET config_value = '1.0.0' WHERE config_name = 'version'";
$db->execute($sql);

$sql = "UPDATE " . $dbprefix . "config SET config_value = 5 WHERE config_name = 'versionint'";
$db->execute($sql);

// and redirect user to next upgrader
Header("Location: from_1.0.0.php");
?>
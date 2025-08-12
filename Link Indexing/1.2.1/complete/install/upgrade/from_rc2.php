<?php
require("../../includes/global.php");

$sql = "SELECT * FROM " . $dbprefix . "config WHERE config_name = 'versionint'";
$rec = $db->execute($sql);
$version = intval($rec->fields["config_value"]);

// check version
if ($version <> 2){ die("You are not running Release Candidate 2"); }

// run the update SQL
$sql = "UPDATE " . $dbprefix . "config SET config_value = 'RC3' WHERE config_name = 'version'";
$db->execute($sql);

$sql = "UPDATE " . $dbprefix . "config SET config_value = 3 WHERE config_name = 'versionint'";
$db->execute($sql);

// and redirect user to next upgrader
Header("Location: from_rc3.php");
?>
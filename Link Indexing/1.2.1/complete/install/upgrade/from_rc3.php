<?php
require("../../includes/global.php");

$sql = "SELECT * FROM " . $dbprefix . "config WHERE config_name = 'versionint'";
$rec = $db->execute($sql);
$version = intval($rec->fields["config_value"]);

// check version
if ($version <> 3){ die("You are not running Release Candidate 3"); }

// run the main SQL
$filename =  "../schema/upgrade_from_rc3.sql";
$contents = implode("", file($filename)); // join it into one string
$contents = str_replace("pl_", $dbprefix, $contents);
$contents = str_replace("\r\n", "\n", $contents); // convert to Unix EOL format if needed
$queries = explode(";\n", $contents); // split into separate queries
foreach ($queries as $query) {
	$result = $db->execute($query);
}

// update the template
$sql = "SELECT * FROM " . $dbprefix . "skinfiles WHERE shortie = 'overall_header'";
$skf = $db->execute($sql);
if ($skf->rows > 0){ do {

	$code = str_replace('<a href="{ROOT}admin.php">Admin</a>', '{ADMIN_LINK}', $skf->fields["code"]);
	$sql  = "UPDATE " . $dbprefix . "skinfiles SET code = '" . dbSecure($code) . "' WHERE fileid = " . dbSecure($skf->fields["fileid"]);
	$db->execute($sql);

} while ($skf->loop()); }

// run the update SQL
$sql = "UPDATE " . $dbprefix . "config SET config_value = 'RC4' WHERE config_name = 'version'";
$db->execute($sql);

$sql = "UPDATE " . $dbprefix . "config SET config_value = 4 WHERE config_name = 'versionint'";
$db->execute($sql);

// and redirect user to next upgrader
Header("Location: from_rc4.php");
?>
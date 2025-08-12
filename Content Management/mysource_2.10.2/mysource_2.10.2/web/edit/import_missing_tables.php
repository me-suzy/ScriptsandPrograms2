<?
require_once("../init.php");

 ######################################
# tell anyone who isn't root .... sorry
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("Import Missing Files", "You must be logged in.");
	exit();
} else if (!user_root()) {
	$SESSION->login_screen("Import Missing Files", "You must be <b>root</b> to import missing files.",$SESSION->user->login);
	exit();
}

?>
<html>
<head>
	<title>Import Missing Files.</title>
		<style type="text/css">
			p,td,body {
				font-family: arial, verdana, sans-serif; 
				font-size: 15px; 
				color: #5C5C5C;
			}
		</style>
	<body>
	<p>
		Checking for missing tables. Hope you have a backup in case anything goes wrong.<br />
	</p>
<?php

$system_config = &get_system_config();

$webdb = new Db(
	$system_config->web_db_details['name'],
	$system_config->web_db_details['login'],
	$system_config->web_db_details['password'],
	$system_config->web_db_details['host'],
	$system_config->web_db_details['querylog']
);

$userdb = new Db(
	$system_config->user_db_details['name'],
	$system_config->user_db_details['login'],
	$system_config->user_db_details['password'],
	$system_config->user_db_details['host'],
	$system_config->user_db_details['querylog']
);

# Set up some global vars.
$GLOBALS['WEBDB'] = $webdb;
$GLOBALS['WEBTABLES'] = $webdb->table_names();
$GLOBALS['USERDB'] = $userdb;
$GLOBALS['USERTABLES'] = $userdb->table_names();


# Let's do this thing now. Find all the mysql_web.sql files first ....
$web_sql_files = find_sql($XTRAS_PATH,'mysql_web.sql');

import_sql_files($web_sql_files, 'web');

# Now all the mysql_users.sql files ..
$user_sql_files = find_sql($XTRAS_PATH,'mysql_users.sql');

import_sql_files($user_sql_files, 'user');

?>
	<p>
		All done.
	</p>
	</body>
</html>
<?

/*
	Function lists below.
*/

function import_file($file='', $dbsystem='web') {
	if (!$file || !is_file($file)) return;
	$fcontents = file ($file);
	$sql = '';
	while (list ($line_num, $line) = each ($fcontents)) {
		$line = trim($line);
		if (preg_match('/^\s*#/', $line) || strlen($line) == 0) continue;
		$sql .= $line . "\n";
	}
	if (!$sql) return;
	if (!preg_match('/CREATE TABLE/', $sql)) return; # in case it's a file with "alter table ..."
	$importsql = str_replace("IF NOT EXISTS", "", $sql);
	$tablelist = explode(";", $importsql);
	foreach($tablelist as $table) {
		preg_match('/\s*CREATE TABLE\s+(.*?)\s*\(/', $table, $matches);
		if (empty($matches)) continue;
		$tablename = $matches[1];
		if (!$tablename) continue;
		if (!in_array($tablename, $GLOBALS[strtoupper($dbsystem.'TABLES')])) {
			echo 'Table ' . $tablename . ' was missing from the ' . $dbsystem . ' system.<br />';
			echo 'Importing now .. ';
			if ($GLOBALS[strtoupper($dbsystem.'DB')]->select($table)) {
				echo 'OK.<br />';
			} else {
				echo 'SQL is ' . $table . '<br />';
				echo 'Problem. Eeek.<br />';
			}
		}
	}
}

function find_sql($dir='.', $file='') {
	$result = array();

	# find files from a specified directory.
	if(file_exists("$dir/$file")) {
		$result[$dir] = "$dir/$file";
	}

	if(!$d = opendir($dir)) {
		echo "Unable to open Directory: $dir".__FILE__.__LINE__."<br>";
		return false;
	}

	while($f = readdir($d)) {
		if (is_dir("$dir/$f") && $f[0] != "." && $f != "CVS") {
			$result[$f] = find_sql("$dir/$f",$file, $prune);
		}
		if (empty($result[$f])) unset($result[$f]);
	}
	closedir($d);
	return $result;
}

/*
	Since we get a huge array of files that can theoretically traverse forever,
	eg.
	xtras/web/extensions/notitia/xtras/attribute/types/search/{type}/........
	or
	xtras/page/templates/calendar_2/xtras/types/{type}/......

	this function will separate out the files (no matter how deep they are in the tree)
	and see if they need importing.
*/
function import_sql_files($sql_files=array(), $db_type='web') {
	foreach($sql_files as $type => $data) {
		if (is_array($data)) {
			import_sql_files($data, $db_type);
			continue;
		}
		import_file($data, $db_type);
	}
}

?>
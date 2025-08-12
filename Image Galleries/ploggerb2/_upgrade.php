<?php

error_reporting(E_ERROR);

echo '
	<html>
		<head>
			<title>Upgrade Plogger</title>
			<link rel="stylesheet" type="text/css" href="admin/../css/admin.css">
		</head>
		<body>
		<img src="graphics/plogger.gif" alt="Plogger">
		<h1>Performing Upgrade...</h1>';
	
// This is the upgrade file for upgrading your Plogger gallery from Beta 1
$workdir = getcwd();
if (file_exists($workdir.'/plog-connect.php'))
{
	print "Rewriting configuration files...<br/>";
	// this check will also make sure that we can delete plog-connect when done, since deleting
	// is actually _writing_ to a directory
	if (!is_writable($workdir)) {
		print $workdir . " is not writable, but I need to create a new file in it";
		exit;
	};

	// now parse DB connection parameters out of plog-connect
	$src = file_get_contents($workdir.'/plog-connect.php');

	preg_match_all('/^\$DB_(HOST|USER|PW|NAME) = "(.*)".*$/m',$src,$data);
	$db_host = $data[2][0];
	$db_user = $data[2][1];
	$db_pw = $data[2][2];
	$db_name = $data[2][3];

	// write them to the new file
	$cfg_file = '';
	$cfg_file .= '// this is the file used to connect to your database.'."\n";
	$cfg_file .= '// you must change these values in order to run the gallery.'."\n";

	$cfg_file .= 'define("PLOGGER_DB_HOST","'.$db_host.'");'."\n";
	$cfg_file .= 'define("PLOGGER_DB_USER","'.$db_user.'");'."\n";
	$cfg_file .= 'define("PLOGGER_DB_PW","'.$db_pw.'");'."\n";
	$cfg_file .= 'define("PLOGGER_DB_NAME","'.$db_name.'");'."\n";

	$fh = fopen("plog-config.php","w");
	if (!$fh) {
		die("Could not write plog-config.php, please make the file writable and then try running this script again");
	};
	fwrite($fh,"<?php\n");
	fwrite($fh,$cfg_file);
	fwrite($fh,"?>\n");
	fclose($fh);

	unlink($workdir.'/plog-connect.php');
	print "Done!<br/>";

};

function makeDirs($strPath, $mode = 0777) //creates directory tree recursively
{
   return is_dir($strPath) or ( makeDirs(dirname($strPath), $mode) and mkdir($strPath, $mode) );
}

include("plog-functions.php");
include("plog-globals.php");

$errors = "";
global $TABLE_PREFIX;

$sql = 'ALTER TABLE '.$TABLE_PREFIX.'config
		ADD  (`feed_num_entries` int(15) NOT NULL default \'15\',
		      `rss_thumbsize` int(11) NOT NULL default \'400\',
		      `feed_title` varchar(255) NOT NULL default \'Plogger Photo Feed\',
		      `feed_language` varchar(20) NOT NULL default \'en-us\');';
		      
if ($result = @mysql_query($sql))
	echo "<p>Your Plogger database has successfully been upgraded to support RSS!</p>";
else
	echo("<p>Database has already been upgraded to support RSS!</p>");

$sql = 'ALTER TABLE '.$TABLE_PREFIX.'config
		ADD  (`use_mod_rewrite` smallint NOT NULL default \'0\');';
		      
if ($result = @mysql_query($sql))
	echo "<p>Your Plogger database has successfully been upgraded to support mod_rewrite!</p>";
else
	echo("<p>Database has already been upgraded to support mod_rewrite!</p>");

$sql = 'ALTER TABLE '.$TABLE_PREFIX.'albums
		ADD  (`thumbnail_id` int(11) NOT NULL)'; 

$result1 = mysql_query($sql);

$sql = 'ALTER TABLE '.$TABLE_PREFIX.'collections
		ADD  (`thumbnail_id` int(11) NOT NULL)'; 

$result2 = mysql_query($sql);
		      
if ($result1 || $result2)
	echo "<p>Your Plogger database has successfully been upgraded to support selectable thumbnails!</p>";
else
	echo("<p>Database has already been upgraded to support selectable thumbnails!</p>");

$sql = 'ALTER TABLE '.$TABLE_PREFIX.'albums
		ADD  (`path` varchar(255) NOT NULL)'; 

$result1 = mysql_query($sql);

$sql = 'ALTER TABLE '.$TABLE_PREFIX.'collections
		ADD  (`path` varchar(255) NOT NULL)'; 

$result1 = mysql_query($sql);

// add field for default sort directory
$sql = 'ALTER TABLE '.$TABLE_PREFIX.'config
		ADD  (`default_sortdir` varchar(5) NOT NULL,
			  `default_sortby` varchar(20) NOT NULL)';

if (mysql_query($sql))
	echo "<p>Your Plogger database has successfully been upgraded to support configurable default sort order!</p>";
else
	echo("<p>Database has already been upgraded to support configurable default sort order!</p>");

// add field for admin e-mail
$sql = 'ALTER TABLE '.$TABLE_PREFIX.'config
		ADD  (`admin_email` varchar(50) NOT NULL)';

if (mysql_query($sql))
	echo "<p>Your Plogger database has successfully been upgraded to support admin e-mail address</p>";
else
	echo("<p>Database has already been upgraded to support admin e-mail address!</p>");

// add field for comments_notify
$sql = 'ALTER TABLE '.$TABLE_PREFIX.'config
		ADD  (`comments_notify` tinyint default 1)';

if (mysql_query($sql))
	echo "<p>Your Plogger database has successfully been upgraded to support comment notification</p>";
else
	echo("<p>Database has already been upgraded to support comment notification!</p>");

// add field for square thumbnails option
$sql = 'ALTER TABLE '.$TABLE_PREFIX.'config
		ADD  (`square_thumbs` tinyint default 1)';

if (mysql_query($sql))
	echo "<p>Your Plogger database has successfully been upgraded to support square thumbnails</p>";
else
	echo("<p>Database has already been upgraded to support square thumbnails!</p>");
	
// add ip and approved fields to comments table
$sql = 'ALTER TABLE '.$TABLE_PREFIX.'comments ADD (`ip` char(64), `approved` tinyint default 1)';

if (mysql_query($sql)) {
	echo("<p>Comments table has been reorganized!</p>");
} else {
	echo("<p>Failed to reorganize comments table! " . mysql_error() . "</p>");
};

$sql = 'ALTER TABLE '.$TABLE_PREFIX.'comments ADD INDEX approved_idx (`approved`)';
mysql_query($sql);

// add ip and approved fields to comments table
$sql = 'ALTER TABLE '.$TABLE_PREFIX.'comments CHANGE `date` `date` datetime';
mysql_query($sql);

/* // add field for timestamp refresh conditions
$sql = 'ALTER TABLE '.$TABLE_PREFIX.'config
		ADD  (`small_lastmodified` datetime NOT NULL,
			  `large_lastmodified` datetime NOT NULL,
			  `rss_lastmodified` datetime NOT NULL)';

if (mysql_query($sql))
	echo "<p>Your Plogger database has successfully been upgraded to smart thumbnail caching!</p>";
else
	echo("<p>Database has already been upgraded to support smart thumbnail caching!</p>");
*/
			
echo "<p>Reorganizing your images folder...";

# strip images prefix from pictures table
$sql = "UPDATE ".$TABLE_PREFIX."pictures SET path = SUBSTRING(path,8) WHERE SUBSTRING(path,1,7) = 'images/'"; 
$result = mysql_query($sql);

$sql = "SELECT id,name FROM ".$TABLE_PREFIX."collections";
$result = mysql_query($sql) or die(mysql_error() . "<br /><br />" . $sql);
while($row = mysql_fetch_assoc($result)) {
	$sql = "UPDATE ".$TABLE_PREFIX."collections SET path = '" . strtolower(sanitize_filename($row['name'])) . "' WHERE id = " . $row['id'];
	#print $sql;
	#print "<br>";
	mysql_query($sql);
} 

$sql = "SELECT id,name FROM ".$TABLE_PREFIX."albums";
$result = mysql_query($sql) or die(mysql_error() . "<br /><br />" . $sql);
while($row = mysql_fetch_assoc($result)) {
	$sql = "UPDATE ".$TABLE_PREFIX."albums SET path = '" . strtolower(sanitize_filename($row['name'])) . "' WHERE id = " . $row['id'];
	#print $sql;
	#print "<br>";
	mysql_query($sql);
} 

// loop through each image from the pictures table, get its parent album name and parent collection
// name, create subdirectories, move the file, and update the PATH field in pictures.

// We need to do a join on the tables to get album names and collection names

$sql = "SELECT p.path AS path, p.id AS pid,c.path AS collection_path, a.path AS album_path
		FROM ".$TABLE_PREFIX."albums a, ".$TABLE_PREFIX."pictures p, ".$TABLE_PREFIX."collections c 
		WHERE p.parent_album = a.id AND p.parent_collection = c.id";
		

$result = mysql_query($sql) or die(mysql_error() . "<br /><br />" . $sql);


echo "<ul>";

while($row = mysql_fetch_assoc($result)) {
	
	$errors = 0;
	$filename = basename($row['path']);
	$directory = $row['collection_path']."/".$row['album_path']."/";
	$new_path = "images/".$directory.$filename;
	if ($row['path'] == $new_path) continue;
	echo "<li>Moving $row[path] -> $new_path</li>";
	
	// move physical file, create directory if necessary and update path in database
	if (!makeDirs("images/".$directory, 0755))
			echo "<ul><li>Error: Could not create directory $directory!</li></ul>";
	
	if (!rename("images/" . $row['path'], $new_path)) {
		echo "<li>Error: could not move file!</li>";
		$errors++; 
		}
	else {	
		$directory = mysql_real_escape_string($directory . $filename);
		// update database
		$sql = "UPDATE ".$TABLE_PREFIX."pictures SET path = '$directory' WHERE id = '$row[pid]'";
		mysql_query($sql) or die("<li>Error: ".mysql_error()." in query " . $sql . "</li>");
	}
	
} 
	
echo "</ul>";

if (!$errors)
	echo "Your files were successfully reorganized!";
else
	echo "There were $errors errors, check your permissions settings.";


	

// convert charsets
// since 4.1 MySQL has support for specifying character encoding for tables
// and I really want to  use it if avaiable. So we need figure out what version
// we are running on and to the right hting
$mysql_version = mysql_get_server_info();
$mysql_charset_support = "4.1";
$default_charset = "";

if (1 == version_compare($mysql_version,$mysql_charset_support))
{
	$charset = "utf8";
	print "<br>";
	$tables = array("collections","albums","pictures","comments","config");
	foreach($tables as $table) {
		$tablename = $TABLE_PREFIX . $table;
		$sql = "ALTER TABLE $tablename DEFAULT CHARACTER SET $charset";
		if (mysql_query($sql)) {
			print $tablename . " converted to $charset<br>";
		} else {
			print "failed to convert $tablename to $charset<br>";
			print mysql_error();
		};
	}
};
?>

		      
		  

<?php

// this file will load all the configuration elements from the database
// and place them into a global associative array called $config
global $config;
$TABLE_PREFIX="plogger_";

require_once("plog-config.php");

$query = "SELECT * FROM ".$TABLE_PREFIX."config WHERE 1";
$result = mysql_query($query) or die("Could not run query $query." . mysql_error());
$config = mysql_fetch_assoc($result);

$config["basedir"] = dirname(__FILE__)."/";

$config["baseurl"] = "http://".$_SERVER["SERVER_NAME"]. substr($_SERVER["PHP_SELF"],0,strrpos($_SERVER["PHP_SELF"],"/")) . "/"; 
// remote admin/ from the end, if present .. is there a better way to determine the full url?
if (substr($config["baseurl"],-6) == "admin/") {
	$config["baseurl"] = substr($config["baseurl"],0,-6);
};

$config['charset'] = 'UTF-8';
// charset set with HTTP headers has higher priority that that set in HTML head section
// since some servers set their own charset for PHP files, this should take care of it
// and hopefully doesn't break anything
header('Content-Type: text/html; charset=' . $config['charset']);

$thumbnail_config = array();
$thumbnail_config['small'] = array('filename_prefix' => '','size' => $config['max_thumbnail_size']);
$thumbnail_config['large'] = array('filename_prefix' => 'lrg-','size' => $config['max_display_size']);
$thumbnail_config['rss'] = array('filename_prefix' => 'rss-','size' => $config['rss_thumbsize']);

// debugging function
function display_uservariables(){
	foreach ($config as $keys => $values) {
		echo "$keys = $values<br>";
	}
}

?>

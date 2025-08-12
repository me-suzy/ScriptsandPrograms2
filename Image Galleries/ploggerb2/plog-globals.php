<?php

//error_reporting(E_NONE);

session_start();

ini_set("arg_separator.output","&amp;");

global $TABLE_PREFIX;
$TABLE_PREFIX = "plogger_";

if (!ini_get('safe_mode'))
	set_time_limit(0);
	
require_once("plog-config.php");
connect_db();

require_once("plog-load_config.php");


if (!isset($_SESSION["plogger_sortby"])){
	$_SESSION["plogger_sortby"] = $config['default_sortby'];
}

if (!isset($_SESSION["plogger_sortdir"])){
	$_SESSION["plogger_sortdir"] = $config['default_sortdir'];
}

if (!isset($_SESSION["plogger_details"])){
	$_SESSION["plogger_details"] = 0;

}

?>

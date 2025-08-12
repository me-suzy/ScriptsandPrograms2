<?php
ob_start();

$start_time = split(" ", microtime());
$start_time = $start_time[1] + $start_time[0];

ini_set("magic_quotes_gpc", "0");
ini_set("magic_quotes_runtime", "0");

foreach($_GET as $key=>$value) {
    $_GET[$key]=addslashes($value);
}

foreach($_POST as $key=>$value) {
    $_POST[$key]=addslashes($value);
}

foreach($_COOKIE as $key=>$value) {
    $_COOKIE[$key]=addslashes($value);
}

include("incl/db.php");
include("incl/functions.php");
include("incl/prefs.php");
include("incl/int_std.php");

db_connect();

session_start();

?>

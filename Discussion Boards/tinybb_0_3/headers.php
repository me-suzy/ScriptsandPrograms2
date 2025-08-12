<?php
$tinybb_release = "v0.3";
session_start();
$username=$_SESSION[tinybb];
if (file_exists("install.php")) { echo "<p>Please delete the installation file before using tinybb.</p>\n"; exit; }
require_once("config.inc.php");
if ($mysql == 0) { require_once("mysql.php"); }
require_once("smilies.php");
require_once("tags.php");
require_once("censorship.php");
if (strlen($tinybb_header) > 0) { require_once($tinybb_header); }
if (strlen($username) > 0) { echo "<p>Currently logged in as <b>$username</b> <a href=\"update.php\">[update]</a> <a href=\"logout.php\">[logout]</a></p>\n"; }
echo "<p><a href=\"index.php\">forum homepage</a> | <a href=\"newtopic.php\">start a new topic</a> | <a href=\"search.php\">search the forum</a></p>\n";
?>
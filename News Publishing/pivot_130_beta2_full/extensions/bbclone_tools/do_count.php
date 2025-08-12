<?php
/*

==================================================================
Snippet-title:          Hardened BBClone v0.1 (10th October 2005)
Creator:                Hans Fredrik Nordhaug
E-mail:                 hans@nordhaug.priv.no
Creation date:          10th October 2005
License:                GPL
==================================================================

This snippet protects your BBClone stats from referer spam.
Read readme.txt for more information.

*/


// don't access directly..
if(!defined('INPIVOT')){ exit('NO!'); }

// Setting variables so bbclone can count correctly
$title = stripslashes(urldecode($_GET["title"]));
$url = str_replace("pivot/","",$Paths['pivot_url']);
$path = str_replace("pivot/","",$Paths['pivot_path']);
$file = str_replace("http://".$_SERVER["HTTP_HOST"].$url,$path,$_GET["uri"]); 

$_SERVER["HTTP_REFERER"] = $_GET["ref"];
$_SERVER["REMOTE_ADDR"] = $_GET["rem"];
$_SERVER["SCRIPT_FILENAME"] = $file; 
$_SERVER["SCRIPT_NAME"] = $_GET["uri"]; //str_replace("http://".$_SERVER["HTTP_HOST"],"",$_GET["uri"]);
$_SERVER["PHP_SELF"] = $_GET["uri"]; //str_replace("http://".$_SERVER["HTTP_HOST"],"",$_GET["uri"]);
$_SERVER["HTTP_USER_AGENT"] = $_GET["ua"];
define( "_BBC_PAGE_NAME", $title );
define( "_BBCLONE_DIR",  $bbclone_path );
define( "COUNTER",       _BBCLONE_DIR."mark_page.php");
if( is_readable( COUNTER )) { include_once( COUNTER ); }

?>

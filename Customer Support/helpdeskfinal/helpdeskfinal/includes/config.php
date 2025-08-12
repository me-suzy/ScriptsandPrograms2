<?php
#
# Helpdesk configuration file
# 

// Uncomment the following line to enable debug mode (displays errors returned by mySQL)
define("DEBUG_MODE", false);

$dbsrv_host	= ""; // Host
$dbsrv_name	= ""; // Database name

$dbsrv_username	= ""; // MySQL server user
$dbsrv_password	= ""; // MySQL server password

$tbl_prefix = "hd_";	// Table prefix

$PATH_ATTACHMENTS = "attachments/"; // Attachments folder path

// Table names
$TABLE_ATTACHMENTS	= $tbl_prefix . "attachments";
$TABLE_CATS			= $tbl_prefix . "cats";
$TABLE_CONFIG		= $tbl_prefix . "config";
$TABLE_PRIORITIES	= $tbl_prefix . "priorities";
$TABLE_REPLIES		= $tbl_prefix . "replies";
$TABLE_STATUS		= $tbl_prefix . "status";
$TABLE_TICKETS		= $tbl_prefix . "tickets";
$TABLE_USERS		= $tbl_prefix . "users";
?>
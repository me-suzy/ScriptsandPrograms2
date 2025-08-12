<?php

$db_connected = false;
include($dirpath."essential/config.php");

$__serName		 = $database_host;
$__serPort		 = $database_port;
$__serUser		 = $database_user;
$__serPass		 = $database_pass;
$defDB             = $database_name;

$fDBConnect        = ($__PConnect) ? "mysql_pconnect" : "mysql_connect";

include($dirpath."essential/license.php");
include($dirpath."essential/gc.php");

function mysql_die( $bExit = true )
{
	echo "<b>MySQL Error</b> [" . mysql_errno(). "]: " . mysql_error() . "<br>";
      if ($bExit) { exit; }
}

function connectDB( $sServer="", $sUser="", $sPass="" )
{
	global $__serName, $__serPort, $__serUser, $__serPass, $fDBConnect, $db_connected, $__link, $defDB;

      if ( empty($sServer) ) { $sServer = $__serName; }
      if ( empty($sUser) ) { $sUser = $__serUser; }
      if ( empty($sPass) ) { $sPass = $__serPass; }

	if ( empty($__serPort) )
    	$__link = $fDBConnect ( $sServer, $sUser, $sPass ) or mysql_die();
   	else
    	$__link = $fDBConnect ( $sServer.":".$__serPort, $sUser, $sPass ) or mysql_die();

	$db_connected = mysql_select_db( $defDB, $__link );
    	return ($db_connected);
}

function closeDB( $lk = 0 )
{
	global $db_connected, $__link;
	if ($lk == 0) $lk = $__link;
	if ($db_connected) mysql_close( $lk ) or mysql_die();
	$db_connected = false;
	return (true);
}

function queryDB( $qry = "" )
{
	global $db_connected, $__link;
	if (!$db_connected) connectDB();
	$result = mysql_query( $qry );
	if (!$result)  { mysql_die(); }
	return ($result);
}

function queryDBErr( $qry = "" )
{
	global $db_connected, $__link;
	if (!$db_connected) connectDB();
	$result = mysql_query( $qry );
	return ($result);
}

function InstallConnectDB( $sServer="", $sUser="", $sPass="" )
{
      global $__serName, $__serPort, $__serUser, $__serPass, $fDBConnect, $db_connected, $__link, $defDB, $tbl_userinfo;

      if ( empty($sServer) ) { $sServer = $__serName; }
      if ( empty($sUser) ) { $sUser = $__serUser; }
      if ( empty($sPass) ) { $sPass = $__serPass; }

	if ( empty($__serPort) )
    	$__link = mysql_pconnect( $sServer, $sUser, $sPass ) or $val=1;
   	else
    	$__link = mysql_pconnect( $sServer.":".$__serPort, $sUser, $sPass ) or $val=1;


	if($val == 1)
	return (mysql_errno());

	$result = mysql_db_query( $defDB, "SELECT * FROM $tbl_userinfo" );
	if (!$result)  { return(mysql_errno()); }

	$db_connected = true;
      return ($db_connected);
}

?>
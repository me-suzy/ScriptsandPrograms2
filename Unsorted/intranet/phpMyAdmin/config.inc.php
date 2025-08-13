<?php
/* $Id: config.inc.php,v 1.28 2000/07/13 13:52:48 tobias Exp $ */

/*
 *  phpMyAdmin Configuration File
 *  All directives are explained in Documentation.html
 */

// The $cfgServers array starts with $cfgServers[1].  Do not use $cfgServers[0].
// You can disable a server config entry by setting host to ''.
$cfgServers[1]['host'] = 'localhost';           // MySQL hostname
$cfgServers[1]['port'] = '';                    // MySQL port - leave blank for default port
$cfgServers[1]['adv_auth'] = true;             // Use advanced authentication?
$cfgServers[1]['stduser'] = '';             // MySQL standard user (only needed with advanced auth)
$cfgServers[1]['stdpass'] = '';                 // MySQL standard password (only needed with advanced auth)
$cfgServers[1]['user'] = 'root';                // MySQL user (only needed with basic auth)
$cfgServers[1]['password'] = '';                // MySQL password (only needed with basic auth)
$cfgServers[1]['only_db'] = '';                 // If set to a db-name, only this db is accessible
$cfgServers[1]['verbose'] = '';                 // Verbose name for this host - leave blank to show the hostname

$cfgServers[2]['host'] = '';
$cfgServers[2]['port'] = '';
$cfgServers[2]['adv_auth'] = false;
$cfgServers[2]['stduser'] = '';
$cfgServers[2]['stdpass'] = '';
$cfgServers[2]['user'] = '';
$cfgServers[2]['password'] = '';
$cfgServers[2]['only_db'] = '';
$cfgServers[2]['verbose'] = '';

$cfgServers[3]['host'] = '';
$cfgServers[3]['port'] = '';
$cfgServers[3]['adv_auth'] = false;
$cfgServers[3]['stduser'] = '';
$cfgServers[3]['stdpass'] = '';
$cfgServers[3]['user'] = 'root';
$cfgServers[3]['password'] = '';
$cfgServers[3]['only_db'] = '';
$cfgServers[3]['verbose'] = '';

// If you have more than one server configured, you can set $cfgServerDefault
// to any one of them to autoconnect to that server when phpMyAdmin is started,
// or set it to 0 to be given a list of servers without logging in
// If you have only one server configured, $cfgServerDefault *MUST* be
// set to that server.
$cfgServerDefault = 1;                            // Default server  (0 = no default server)
$cfgServer = '';
unset($cfgServers[0]);

$cfgManualBase = "http://www.mysql.com/documentation/mysql/bychapter/";

$cfgConfirm = true;
$cfgPersistentConnections = false;

$cfgBorder      = "0";
$cfgThBgcolor  = "#D3DCE3";
$cfgBgcolorOne = "#CCCCCC";
$cfgBgcolorTwo = "#DDDDDD";
$cfgMaxRows = 30;
$cfgMaxInputsize = "300px";
$cfgOrder = "ASC";
$cfgShowBlob = true;
$cfgShowSQL = true;

require("english.inc.php");

$cfgColumnTypes = array(
   "TINYINT",
   "SMALLINT",
   "MEDIUMINT",
   "INT",
   "BIGINT",
   "FLOAT",
   "DOUBLE",
   "DECIMAL",
   "DATE",
   "DATETIME",
   "TIMESTAMP",
   "TIME",
   "YEAR",
   "CHAR",
   "VARCHAR",
   "TINYBLOB",
   "TINYTEXT",
   "TEXT",
   "BLOB",
   "MEDIUMBLOB",
   "MEDIUMTEXT",
   "LONGBLOB",
   "LONGTEXT",
   "ENUM",
   "SET");

$cfgFunctions = array(
   "ASCII",
   "CHAR",
   "SOUNDEX",
   "CURDATE",
   "CURTIME",
   "FROM_DAYS",
   "FROM_UNIXTIME",
   "NOW",
   "PASSWORD",
   "PERIOD_ADD",
   "PERIOD_DIFF",
   "TO_DAYS",
   "USER",
   "WEEKDAY",
   "RAND");

$cfgAttributeTypes = array(
   "",
   "BINARY",
   "UNSIGNED",
   "UNSIGNED ZEROFILL");

// Setting magic_quotes_runtime - do not change!
set_magic_quotes_runtime(0);
?>

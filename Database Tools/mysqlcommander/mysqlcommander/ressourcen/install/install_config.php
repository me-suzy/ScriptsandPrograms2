<?php 
$home = "./../../";

include "./class.installProperties.php";

if (isset($HTTP_POST_VARS['username'])) $o_inst_props = new installProperties("superconfig", "./../", "./../", $HTTP_POST_VARS['username'], $HTTP_POST_VARS['password']);
elseif (isset($HTTP_GET_VARS['username'])) $o_inst_props = new installProperties("superconfig", "./../", "./../", $HTTP_GET_VARS['username'], $HTTP_GET_VARS['password']);
else $o_inst_props = new installProperties("superconfig", "./../");
$o_inst_props->setMode("public");

$o_inst_props->setHeadline("Protect the script with username and password");
$o_inst_props->setVariable("commander_user", "", "Username for login");
$o_inst_props->setVariable("commander_pass", "", "Password for login");

$o_inst_props->setHeadline("Server 1");
$o_inst_props->setVariable("mysql_databasename1", "Homeserver", "MySQL - Server-Description");
$o_inst_props->setVariable("mysql_user1", "root", "MySQL - Database-User");
$o_inst_props->setVariable("mysql_password1", "", "MySQL - Database-Password");
$o_inst_props->setVariable("mysql_server1", "localhost", "MySQL - Database-Server");

$o_inst_props->setHeadline("Server 2 (optional)");
$o_inst_props->setVariable("mysql_databasename2", "", "MySQL - Server-Description");
$o_inst_props->setVariable("mysql_user2", "", "MySQL - Database-User");
$o_inst_props->setVariable("mysql_password2", "", "MySQL - Database-Password");
$o_inst_props->setVariable("mysql_server2", "", "MySQL - Database-Server");

$o_inst_props->setComment("To configure more Servers scroll down");
$o_inst_props->setComment("");

$o_inst_props->setHeadline("Configuration for URL interface");
$o_inst_props->setVariable("interface_username", "", "Username for interface");
$o_inst_props->setVariable("interface_password", "", "Password for interface");

$o_inst_props->setHeadline("Databases");
$o_inst_props->setVariable("list_dbase", "", "List here as many databases as you want to show, leave empty, if every database should appear; seperated by SPACE");
$o_inst_props->setVariable("not_list_dbase", "", "List here as many databases as you DO NOT want to show; seperated by SPACE");

$o_inst_props->setHeadline("MySQL-Commander Settings");

$o_inst_props->setVariable("default_seperator", "||#||", "Default seperator");
$o_inst_props->setVariable("strLineFeedCode", "##|n|##", "line Feed Code");
$o_inst_props->setVariable("strCarriageReturnCode", "##|r|##", "Carriage Return Code");
$o_inst_props->setVariable("default_sets_per_file", "1000", "Datasets per file (only for BIGTABLE)");
$o_inst_props->setVariable("default_setTimeLimit", "120", "Default set_time_limit ( Set the number of seconds a script is allowed to run.)");
$o_inst_props->setVariable("data_path", "./data/", "Relative/absolute data path (path where the backup files will reside)");

$o_inst_props->setVariable("language", "english", "Language");
$o_inst_props->addVariableValue("language", "english");
$o_inst_props->addVariableValue("language", "german");

$o_inst_props->setHeadline("Server 3 (optional)");
$o_inst_props->setVariable("mysql_databasename3", "", "MySQL - Server-Description");
$o_inst_props->setVariable("mysql_user3", "", "MySQL - Database-User");
$o_inst_props->setVariable("mysql_password3", "", "MySQL - Database-Password");
$o_inst_props->setVariable("mysql_server3", "", "MySQL - Database-Server");

$o_inst_props->setHeadline("Server 4 (optional)");
$o_inst_props->setVariable("mysql_databasename4", "", "MySQL - Server-Description");
$o_inst_props->setVariable("mysql_user4", "", "MySQL - Database-User");
$o_inst_props->setVariable("mysql_password4", "", "MySQL - Database-Password");
$o_inst_props->setVariable("mysql_server4", "", "MySQL - Database-Server");

$o_inst_props->setHeadline("Server 5 (optional)");
$o_inst_props->setVariable("mysql_databasename5", "", "MySQL - Server-Description");
$o_inst_props->setVariable("mysql_user5", "", "MySQL - Database-User");
$o_inst_props->setVariable("mysql_password5", "", "MySQL - Database-Password");
$o_inst_props->setVariable("mysql_server5", "", "MySQL - Database-Server");

$o_inst_props->setHeadline("Server 6 (optional)");
$o_inst_props->setVariable("mysql_databasename6", "", "MySQL - Server-Description");
$o_inst_props->setVariable("mysql_user6", "", "MySQL - Database-User");
$o_inst_props->setVariable("mysql_password6", "", "MySQL - Database-Password");
$o_inst_props->setVariable("mysql_server6", "", "MySQL - Database-Server");


$o_inst_props->compareConfigFile();
?>
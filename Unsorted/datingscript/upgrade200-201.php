<?
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               install.php                      #
# File purpose            Installation script              #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
include_once 'include/security.inc.php';
include_once 'languages/'.C_ADMINLANG.'/'.C_ADMINLANG.'a.php';
?>
<html><head><title><?=C_SNAME?> Upgrade</title>
</head>
<body bgcolor="#C2FBFE">
<center><table width="80%"><tr><td>
<basefont size="4" color="navy" face="Verdana,Tahoma">
<center><b><?=C_SNAME?> Upgrade</b></center>
<hr color=navy size=2>
<?
///////  C_URL NOT SET
if (C_URL == "http://www.test.net/AzDGDatingLite") {?>
<center><h3 style='color:red'>Installation Error: Please Change C_URL in config.inc.php</h3></center>
<?die;}
//////////////////////////

///////  C_PATH NOT SET
if (C_PATH == "Z:/home/www.test.net/www/AzDGDatingLite") {?>
<center><h3 style='color:red'>Installation Error: Please Change C_PATH in config.inc.php</h3></center>
<?die;}
//////////////////////////

///////  C_PASS NOT SET
if (C_PASS == "password") {?>
<center><h3 style='color:red'>Installation Error: Please Check  MySQL settings in config.inc.php</h3></center>
<?die;}
//////////////////////////

///////  C_ADMINP NOT SET
if (C_ADMINP == "") {?>
<center><h3 style='color:red'>Installation Error: Please change your admin login and password in config.inc.php</h3></center>
<?die;}
//////////////////////////

@mysql_connect(C_HOST, C_USER, C_PASS) or die($w[113]); 
@mysql_select_db(C_BASE) or die($w[114]);

$sql = "ALTER TABLE `".C_MYSQL_MEMBERS."` CHANGE `aim` `aim` VARCHAR(16) NOT NULL default ''";
mysql_query($sql) or die(mysql_error());

echo "<center><h1>Successfully upgraded from version 2.0.0</h1>";

?>

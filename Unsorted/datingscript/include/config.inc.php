<?php
session_start();unset($s);unset($m);
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               config.inc.php                   #
# File purpose            Main configuration file          #
# File created by         AzDG <support@azdg.com>          #
############################################################
### Url were AzDGDatingLite has been installed, not '/' in end!
define('C_URL','http://www.test.net/AzDGDatingLite');

### Internal path to AzDGDatingLite directory
define('C_PATH','Z:/home/www.test.net/www/AzDGDatingLite');

### Site Name
define('C_SNAME','AzDGDatingLite 2.1.1');

### Admin Data
define('C_ADMINL','');// Admin login
define('C_ADMINP','');// Admin password
define('C_ADMINM','admin@yoursite.com');//Admin email
define('C_ADMINLANG','default');//Admin language (By lang dir example: en)

### MySQL data
define('C_HOST','localhost');// MySQL host name (usually:localhost)
define('C_USER','user');// MySQL username
define('C_PASS','password');// MySQL password
define('C_BASE','database');// MySQL database

define('C_MYSQL_MEMBERS','pro_membersu');// Table for members info
define('C_MYSQL_ONLINE_USERS','pro_onlineu');// Table for online users info
define('C_MYSQL_ONLINE_QUESTS','pro_onlineq');// Table for online quests
define('C_MYSQL_TEMP','pro_temp');// Table for temporary info
?>

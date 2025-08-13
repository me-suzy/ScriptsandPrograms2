<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| conf.global.php :: This file contains the main script configuration  |
| settings                                                             |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: conf.global.php,v 0.10.0.1 20/09/2002 21:00:00 ssb Exp $    */

/*---------------------------------------------------------------------+
| NOTE: Due to the way in which PHP handles path information, any      |
| directory or file paths you specify can be either relative or        |
| absolute paths, but it is recommended, for reliability reasons, that |
| you always use absolute paths                                        |
/*--------------------------------------------------------------------*/
	
# Database Information
	
$CONF["dbhost"] = "localhost";                  # Database Host

$CONF["dbuser"] = "";              # Database Username

$CONF["dbpass"] = "";                # Database Password

$CONF["dbname"] = "";                # Name of Database

$CONF["mbdbname"] = "";       # Modern Bill Database

$CONF["table_prefix"] = "";                      # Table prefix

$CONF["mbtable_prefix"] = "";         # Modernbill Table Prefix

# Script Settings

$CONF["template"] = "./includes/template.inc";  # Template File

$CONF["sitename"] = "";                   # Site Title

$CONF["numnews"] = "5";         # Number of news items to display
	
$CONF["livechat"] = "on";
	
$CONF["userdriver"] = "database";

$CONF["script_name"] = "EdgePanel Version 1.00";
	
$CONF["version"] = "1.00";

# Saved Settings
# ----------------------------------------------------------------------
# The following are setting values that the admin has opted to save so
# they needed go through the same option processes over and over again,
# just like you can do in Windows. NOTE: All values, if left blank, will
# prompt the script to ask the admin

# SERVER NEWS DELETE
# ----------------------------------------------------------------------
# This is the server news delete. Possible values:
#
#    - all (delete news item from all servers)
#    - unique (delete from only this server)

$CONF["sn_delete"] = "";

# ----------------------------------------------------------------------
# CLEANUP DATABASE
# ----------------------------------------------------------------------
# This is the cleanup database value. Possible values:
#
#    - obseletes (remove only obselete entries)
#    - unique (empty everything except admin table)

$CONF["db_cleanup"] = "";

?>

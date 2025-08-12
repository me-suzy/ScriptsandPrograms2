<?php
require("config.php");
#####################################################
# File to install Stop Show
# Be sure that you have edited the VarCW02.php file to
# include the settings for your server or this install
# will not work properly.
# After you used this file, you can safely delete it.
#####################################################

 mysql_connect($dbhost, $dbname, $dbpass);
 @mysql_select_db($dbname);

####################### BEGIN THE Install #######################################

// CREATE SHOPPING CART CWC_SHOPSTOPCAT TABLE
$query = "CREATE TABLE CWC_shopstopcat (".
"cid int(11) NOT NULL auto_increment,".
"cat varchar(20) NOT NULL default '',".
"catdes varchar(50) default 'YES',".
"PRIMARY KEY (cid))";
mysql_query($query) or die("Error: ".mysql_error());


#######################

// CREATE SHOPPING CART CWC_SHOPSTOP TABLE
$query1 = "CREATE TABLE CWC_shopstop (".
"pid int(11) NOT NULL auto_increment,".
"name varchar(20) NOT NULL default '',"
"desc text NOT NULL,".
"price text NOT NULL,".
"catid int(11) NOT NULL default '',".
"PRIMARY KEY (pid))";
mysql_query($query1) or die("Error: ".mysql_error());


#######################


echo "<Center><h4>Stop Shop has been installed</h4></center><br><br>";
?>
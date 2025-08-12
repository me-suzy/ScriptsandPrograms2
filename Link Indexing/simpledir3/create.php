<?php

# SimpleDir 3.0
# Copyright 2003-2004 Tara, http://gurukitty.com/star. All rights reserved.
# Released June 19, 2004

# SimpleDir 3.0 is linkware and can be used or modified as long all notes on the SimpleDir 3.0 files remain intact, unaltered, and a link is placed on all pages used by the SimpleDir 3.0 script to http://gurukitty.com/star so others can find out about this script as well. You may modify this script to suit your wishes, but please follow my requests and do not distribute it.

# All I ask of you is the above and to not sell or distribute SimpleDir 3.0 without my permission.
# All risks of using SimpleDir 3.0 are the user's responsibility, not the creator of the script.
# For further information and updates, visit the SimpleDir 3.0 site at http://gurukitty.com/star.
# Thank you for downloading SimpleDir 3.0.

require("config.php");

$sdversion = "3.0";

// connect to database
mysql_connect ($dbhost, $dbuser, $dbpass) or die ("Cannot connect to the MySQL server. Please check your variables and re-upload the config.php file or try again later.");
@mysql_select_db ($dbname) or die ("Cannot select the database. Please check that the specified user has access to the database, that the database and username exist, and that the password is correct.");

// create table $tbconfig
mysql_query("CREATE TABLE $tbconfig (id int(6) NOT NULL auto_increment, dirpass text NOT NULL, sitename text NOT NULL, adminname text NOT NULL, visitoradd text NOT NULL, pend text NOT NULL, allowdesc text NOT NULL, adminemail text NOT NULL, emailnotify text NOT NULL, siteurl text NOT NULL, numperpage text NOT NULL, usemanager text NOT NULL, sitepath text NOT NULL, catselect text NOT NULL, KEY id (id))") or die(mysql_error());

// create table $tblinks
mysql_query("CREATE TABLE $tblinks (linkID int(6) NOT NULL auto_increment, relCatID int(6) NOT NULL DEFAULT '1', linkname text NOT NULL, linkurl text NOT NULL, linkstatus int(6) NOT NULL DEFAULT '0', linkdesc text NOT NULL, ownername text NOT NULL, owneremail text NOT NULL, linknotes text NOT NULL, KEY linkID (linkID))") or die(mysql_error());

// create the table $tbcats
mysql_query("CREATE TABLE $tbcats (catID int(6) NOT NULL auto_increment, catname text NOT NULL, catDesc text NOT NULL, KEY catID (catID))") or die(mysql_error());

// insert default category into table $tbcats
mysql_query("INSERT INTO $tbcats VALUES ('', 'Other', 'Miscellaneous links')") or die(mysql_error());

// insert default configuration values into table $tbconfig
mysql_query("INSERT INTO $tbconfig VALUES ('', md5('cooney'), 'My Directory', 'Admin', 'Y', 'Y', 'Y', 'you@yourdomain.com', 'Y', 'http://www.yourdomain.com', '25', 'N', '/home/yourdomain/public_html', 'R')") or die(mysql_error());

// print success message
include("first.inc.php");
echo '<p>Success! Tables <b>'.$tbconfig.'</b>, <b>'.$tblinks.'</b>, and <b>'.$tbcats.'</b> have been created. To avoid security risks you should immediately delete this file from your server. You may now login to your <a href="admin.php">control panel</a>.</p>';
include("footer.inc.php");

// close connection
mysql_close($db);

?>
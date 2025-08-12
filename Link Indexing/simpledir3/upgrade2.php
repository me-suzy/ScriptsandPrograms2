<?php

# SimpleDir 3.0
# Copyright 2003-2004 Tara, http://gurukitty.com/star. All rights reserved.
# Released June 19, 2004

# SimpleDir 3.0 is linkware and can be used or modified as long all notes on the SimpleDir 3.0 files remain intact, unaltered, and a link is placed on all pages used by the SimpleDir 3.0 script to http://gurukitty.com/star so others can find out about this script as well. You may modify this script to suit your wishes, but please follow my requests and do not distribute it.

# All I ask of you is the above and to not sell or distribute SimpleDir 3.0 without my permission.
# All risks of using SimpleDir 3.0 are the user's responsibility, not the creator of the script.
# For further information and updates, visit the SimpleDir 3.0 site at http://gurukitty.com/star.
# Thank you for downloading SimpleDir 3.0.

// include database configuration
require("config.php");

$sdversion = "3.0";

// add new columns to table $tbconfig
mysql_query("ALTER TABLE $tbconfig ADD usemanager text NOT NULL") or die(mysql_error());
mysql_query("ALTER TALBE $tbconfig ADD sitepath text NOT NULL") or die(mysql_error());
mysql_query("ALTER TABLE $tbconfig ADD catselect text NOT NULL") or die(mysql_error());
// add new columns to table $tbcats
mysql_query("ALTER TABLE $tbcats ADD catDesc text NOT NULL") or die(mysql_error());

// print the success message
include("first.inc.php");
echo '<p>Success! The new columns have been added to table <b>'.$tbconfig.'</b>. To avoid security risks you should immediately delete this file from your server. You may now login to your <a href="admin.php">control panel</a>.</p>';
include("footer.inc.php");

// close connection
mysql_close($db);

?>
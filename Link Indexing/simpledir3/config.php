<?php

# SimpleDir 3.0
# Copyright 2003-2004 Tara, http://gurukitty.com/star. All rights reserved.
# Released June 19, 2004

# SimpleDir 3.0 is linkware and can be used or modified as long all notes on the SimpleDir 3.0 files remain intact, unaltered, and a link is placed on all pages used by the SimpleDir 3.0 script to http://gurukitty.com/star so others can find out about this script as well. You may modify this script to suit your wishes, but please follow my requests and do not distribute it.

# All I ask of you is the above and to not sell or distribute SimpleDir 3.0 without my permission.
# All risks of using SimpleDir 3.0 are the user's responsibility, not the creator of the script.
# For further information and updates, visit the SimpleDir 3.0 site at http://gurukitty.com/star.
# Thank you for downloading SimpleDir 3.0.

# your host (usually "localhost"; check with your hosting provider if this doesn't work)
$dbhost = "localhost";

# the name of the database where the information for this script will be stored
$dbname = "data";

# username for the database
$dbuser = "user";

# username's password
$dbpass = "pass";

/* name of the table for the configuration options to be stored in */
$tbconfig = "SD_config";

/* name of the table for the links */
$tblinks = "SD_links";

/* name of the table for the categories */
$tbcats = "SD_cats";

// DO NOT EDIT BELOW THIS LINE!!
$db = mysql_connect ($dbhost, $dbuser, $dbpass) or die ("<p>Cannot connect to the MySQL server. Please check your variables and re-upload the config.php file or try again later.</p>");
@mysql_select_db ($dbname) or die ("<p>Cannot select the database. Please check that the specified user has access to the database, that the database and username exist, and that the password is correct.</p>");

?>
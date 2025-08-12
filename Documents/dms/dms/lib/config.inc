<?php
#####################################################################
# NAME/ PURPOSE - this file is used to list some important configuration
#      options.
#
# STATUS - Done
#
# LAST MODIFIED - 02/11/2005
#
# TO DO - nothing. done
#
# NOTE: Due to the nature of this program being an open-source project,
#       refer to the project website https://sourceforge.net/projects/gssdms/
#		for the most current status on this project and all files within it
#
#####################################################################

// Used for the main page heading and to
// set a realm for authentication.
$cfg[site_name]= "YOUR SITE NAME"; // Enter your site or organization'ss name here
$cfg[version]= "1.0.0";

// Enter the details for your mysql server.
$cfg[server]= "localhost"; // hostname; usually 'localhost'
$cfg[user]= "USERNAME"; // database username
$cfg[pass]= "PASSWORD"; // database password
$cfg[db]= "DATABASE NAME"; // database name

// KLG - added these two variables for use elsewhere
$cfg[site_owner]="YOUR NAME HERE"; // name of the owner or contact person for this site
$cfg[site_owner_email]="you@domain.com"; // email address of the site owner


// Don't edit anything below this line unless you know what you're doing
// Show some very rudamentary debug stuff. Change to '1' to turn these on
$cfg[debug]	= 0;

// Global vars for debugging.
$debug_time_start = 0;
$debug_time_end	= 0;

?>
<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// CONFIG.PHP > 03-11-2005

// MySQL database settings
$sqlhost 		= "localhost";		// usually you can keep localhost
$sqldb   		= "";				// the database (ex: host_somery)
$sqluser 		= "";				// the username for the database (ex: host_somery)
$sqlpass 		= "";				// the password for the database

// Website information
$prefix  		= "somery_";			// use this to have more than one somery install in one db
$website 		= "your somery site";		// used for internal systems, such as RSS. your site's title
$websiteurl		= "http://www.domain.ext";    // the URL for your website, ie http://www.yahoo.com (no trailing slash)
$owner_email	= "user@email.ext";		// your email
$cookiesite		= "www.doimain.ext";		// the domain your cookies should belong to. domain.ext or sub.domain.ext

// RSS information
$rss_maxitems	= 10;				// set this to the maximum posts downloaded when first subscribing

// These are the months of the year
$months[1]		= "January";
$months[2]		= "February";
$months[3]		= "March";
$months[4]		= "April";
$months[5]		= "May";
$months[6]		= "June";
$months[7]		= "July";
$months[8]		= "August";
$months[9]		= "September";
$months[10]		= "October";
$months[11]		= "November";
$months[12]		= "December";

// The descriptions of the levels
$levelname[0] 	= "pleb";
$levelname[1]	= "poster";
$levelname[2]	= "moderator";
$levelname[3]	= "admin";
$levelname[4]	= "super admin";
?>
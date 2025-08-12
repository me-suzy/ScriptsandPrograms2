<?php

/* ********************* */
/* CONFIGURATION OPTIONS */
/* ********************* */

# Path to the directory of the script ($sitepath is set in functionc.inc.php)
$config['sitepath'] = $sitepath;

# Path to the templates directory
$config['templatedir'] = "{$config['sitepath']}/templates";

# Path where admin logs are saved and authentication scripts
# should be placed, this should be outside of the web directory
# so that the files can't be read from the web.
$config['datadir'] = "data";

# A key that distinguishes this site from other sites, used in cookies.
$config['sitekey'] = "phpvoter";

# Name of the voting script with relative path.
$config['votescript'] = "php-voter.php"; 

# The site administrator's email address.
$config['adminaddress'] = "nobody@nobody.com";

# Path to the templates directory
$config['templatedir'] = "{$config['sitepath']}/templates";

# Path where admin logs are saved and authentication scripts
# should be placed, this should be outside of the web directory
# so that the files can't be read from the web.
$config['datadir'] = "data";

# Template set to use
$config['templateset'] = "default";

# The type of font to be used in the script.
$config['font'] = "helvetica, arial";

# The size of the font to be used.
$config['fontsize'] = "-1";
$config['fontsize2'] = "-2";

# Select the language to use in the script.
# Available: english, swedish, spanish
$config['language'] = "swedish";

# Authentication type
# Available: htaccess, basic
$config['auth_type'] = "basic";

# Width of result bar
$config['width'] = 400;

# Minimum width of a result bar
$config['minwidth'] = 2;

# Set to "yes" if users should only be able to vote once, otherwise "no"
$config['unique'] = "yes";

# Set to "yes" if the answer should be saved along with the unique identifier
$config['saveanswer'] = "yes";

# Set to "yes" if only one vote can be active at any one time
$config['singlevote'] = "yes";

# Number of empty answers on the new question form
$config['nrofanswers'] = "10";

# How to format the date, must be a valid argument to the date() function.
$config['dateformat'] = "Y-m-d";
$config['dateformat_long'] = "Y-m-d H:i:s";

# File to save admin log, set to empty if you don't want to use it.
$config['adminlog_filename'] = $config['datadir'] . "/adminlog.dat";

# How many old log files to keep.
$config['adminlog_keepfiles'] = 4;

# How big a log file can be before it's purged.
$config['adminlog_maxsize'] = 51200;


# Template files
$config['template']['showvote'] = "showvote.tmpl.php";
$config['template']['letsvote'] = "letsvote.tmpl.php";
$config['template']['header'] = "header.tmpl.php";
$config['template']['footer'] = "footer.tmpl.php";


/* ************************************ */
/* DO NOT EDIT ANYTHING BELOW THIS LINE */
/* ************************************ */

# Declare som variables for the script.
$config['document_root'] = $GLOBALS["DOCUMENT_ROOT"];

# Name of the script
$config['scriptname'] = $GLOBALS["SCRIPT_NAME"];

# Version
$config['version'] = "0.6";

# Long version
$config['longversion'] = "phpVoter {$config['version']} (C) Olle Johansson February 12, 2003";

$strError = "";

$config['debug'] = 0;

?>

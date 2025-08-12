<?php

# PHP graphical hit counter (PHPGcount)
# Version: 1.0
# File name: graphcount.php
# Written 10th May 2004 by Klemen Stirn (info@phpjunkyard.com)
# http://www.PHPJunkYard.com

##############################################################################
# COPYRIGHT NOTICE                                                           #
# Copyright 2004 PHPJunkYard All Rights Reserved.                            #
#                                                                            #
# The PHPGcount may be used and modified free of charge by anyone so long as #
# this copyright notice and the comments above remain intact. By using this  #
# code you agree to indemnify Klemen Stirn from any liability that might     #
# arise from it's use.                                                       #
#                                                                            #
# Selling the code for this program without prior written consent is         #
# expressly forbidden. In other words, please ask first before you try and   #
# make money off this program.                                               #
#                                                                            #
# Obtain permission before redistributing this software over the Internet or #
# in any other medium. In all cases copyright and header must remain intact. #
# This Copyright is in full effect in any country that has International     #
# Trade Agreements with the United States of America or with                 #
# the European Union.                                                        #
##############################################################################

// SETUP YOUR COUNTER
// Detailed information found in the readme file

// URL of the folder where script is installed. INCLUDE a trailing "/" !!!
$base_url = "http://www.yourdomain.com/gcount/";
// Default image style (font)
$default_style = "web1";
// Default counter image extension
$default_ext = "gif";
// Enable referer validation? 1 = YES, 0 = NO
$check_referer = 0;
// Domains that are allowed to access this script
$referers = array ("localhost","yourdomain.com");

#############################
#     DO NOT EDIT BELOW     #
#############################

// Get page and log file names plus style and folder with images
$page = htmlentities($_GET['page']);
$logfile = "logs/" . $page . ".log";
if (empty($_GET['style'])) {$style = $default_style;}
else {$style = $_GET['style'];}
$style_folder = "styles/" . $style . "/";
if (empty($_GET['ext'])) {$ext = $default_ext;}
else {$ext = $_GET['ext'];}

// If $check_referer is set to 1 and if HTTP_REFERER is set to
// a value let's check refering site
if ($check_referer == 1 && !(empty($_SERVER['HTTP_REFERER'])))
{
check_referer($_SERVER['HTTP_REFERER']);
}

// If the log file doesn't exist we start count from 1 ...
if (! @$file = fopen($logfile,"r+"))
{
$count="1";
}
// If the log file exist lets read count from it
else {
$count = @fread($file, filesize($logfile)) or $count=0;
fclose($file);
// Raise the value of $count by 1
$count++;
}

// Write the new $count in the log file
$file = fopen($logfile,"w+") or die("Can't open/write the log file!");
fputs($file, $count);
fclose($file);

// Print out Javascript code and exit
for ($i=0;$i<strlen($count);$i++) {
    $digit=substr("$count",$i,1);
    // Build the image URL ...
    $src = $base_url . $style_folder . $digit . "." . $ext;
    echo "document.write('<img src=\"$src\" border=0>');\n";
}

exit();

// function that will check refering URL
function check_referer($thisurl) {
	global $referers;
		for ($i=0;$i<count($referers);$i++)
        	{
				if (preg_match("/$referers[$i]/i",$thisurl)) {return true;}
			}
	die("Invalid referer!");
}
?>
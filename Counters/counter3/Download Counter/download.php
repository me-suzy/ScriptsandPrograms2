<?php
# Download Counter
# Version: 0.5
# File name: download.php
# March 23, 2005
# Author: Carter Smith
# http://www.cpspros.com

##############################################################################
# COPYRIGHT NOTICE                                                           #
# Copyright 2004-2005 Carter Productions Studios All Rights Reserved.        #
#                                                                            #
# This program may be used without consent, providing that all copyrights    #
# and links stay intact 												     #
##############################################################################

// General Settings //

$counterfile = "downloads.txt";


#############################
#     DO NOT EDIT BELOW     #
#############################

// Download Function

function download($url) {
	header("Location: $url");
}

// Check for link validity
if($_GET['id'] == "") {
	$_GET['id'] = '/';
	download($_GET['id']);
	exit;
} else {
	// Download File
	download($_GET['id']);
}

// Count Hits

$opencount = fopen($counterfile,"r") or die("couldnt open");
echo "opencount=" . $opencount;
echo "counterfile=" . $counterfile;
$currentcount = @fread($opencount, filesize($counterfile)) or die("couldnt read");
echo "currentcount=" . $currentcount;
fclose($opencount);

$newcount = $currentcount;
++$newcount;
echo " newcount= " . $newcount;
$opennewcount = @fopen($counterfile,"w") or die("couldnt open for editing");
fwrite($opennewcount, $newcount) or die ("couldnt write");
fclose ($opennewcount);


?>
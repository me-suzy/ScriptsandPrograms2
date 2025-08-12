<?php
# ---------------------------------------------------------------------
# eFusian
# Copyright (C) 2002 by the Fusian Development Team.
# http://www.efusian.co.uk
# ---------------------------------------------------------------------
# Author:              Oliver James Ibbotson Esq.
# Date:                06th December 2002
# ---------------------------------------------------------------------
# Script Name:         config.php / FileFusian
# Script Version:      2.0.1
#
# Description:         This script is the configuration file for the
#                      file-fusian script.
#
# Revision History:
# ---------------------------------------------------------------------
?>


<?php


/*  Configuration Section */

$file_dir = "/usr/local/psa/home/vhosts/efusian.co.uk/web_users/filefusian/cvs/uploads";   		# This is the absolute path (no trailing slash).
$file_url = "http://www.efusian.co.uk/~filefusian/cvs/uploads";     						# Full URL to the uploaded file.

$logo_file = "http://www.efusian.co.uk/~filefusian/images/v2logo.gif";					# FileFusian logo location.

$max_file_size = "102400";												# Max File Size Setting (In Bytes).

$skin = "skins/white.css";												# Defines Skin to be used.
$logfile = "logs/uplog.txt";											# Upload log file location, must be CHMOD 666.

$logs_active = "1";                   									# Turn ip logging on/off 1=on 0=off.

/* Directory Listing */

$listsize = 30;													# Maximum Number oF Files To Be Returned.


/* Upload Function */

# The following section allows upto 5 file types to be specified easily at startup without delving into the code of FileFusian to much.
# It is of paramount importance that something is filled in each of these filetypes or someone will be able to remove the extension from 
# the file they are uploading and get past these checks.

# If you only want a certain ammount of file types, eg 3, then fill the rest in with the word NULL as the FileFusian code will not list this on the main page.


$filetype1 = ".jpg";
$filetype2 = ".gif";
$filetype3 = ".jpeg";
$filetype4 = ".txt";
$filetype5 = "NULL";


?>
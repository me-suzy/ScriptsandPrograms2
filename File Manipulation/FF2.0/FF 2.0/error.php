<?php
# ---------------------------------------------------------------------
# eFusian
# Copyright (C) 2002 by the Fusian Development Team.
# http://www.efusian.co.uk
# ---------------------------------------------------------------------
# Author:              Oliver James Ibbotson Esq.
# Date:                1st February 2003
# ---------------------------------------------------------------------
# Script Name:         error.php / FileFusian
# Script Version:      2.0.1
#
# Description:         This script is the error file for the
#                      file-fusian script.
#
# Revision History:
# ---------------------------------------------------------------------
?>


<?php

if($_GET['id'] == "filetype")
{
	
	
	echo '<html>';
	echo '<head><title>Error In FileType</title></head>';
	echo '<body>';
	echo '<font face="verdana" color="red" size="3">';
	
	echo '<img src="http://efusian.co.uk/~filefusian/images/efusian-logo-mini.jpg">';
	echo '<br><br>';
	
	echo '<center>';
	echo 'The file type you are attempting to upload has been disallowed on this server';
	echo '</font>';
	
	echo '<br><br>';
	
	echo '<font face="verdana" color="darkblue" size="2">';
	echo 'If you feel that this is incorrect then please contact the server administrator';
	echo '</font>';
	
	echo '<br><br>';
		
	echo '<font face="verdana" color="darkblue" size="1">';
	echo '<a href="javascript:window.close();">Close Error Window</a>';
	echo '</font>';
	echo '</html>';
	
	
}

?>
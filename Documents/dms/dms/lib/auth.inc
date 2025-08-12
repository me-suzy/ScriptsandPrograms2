<?php
#####################################################################
# NAME/ PURPOSE - this file is called in the header of each page which
#       requires authentication. 
#
# STATUS - Done
#
# LAST MODIFIED - 02/11/2005
#
# TO DO - nothing. done.
#
# NOTE: Due to the nature of this program being an open-source project,
#       refer to the project website https://sourceforge.net/projects/gssdms/
#		for the most current status on this project and all files within it
#
#####################################################################


require('lib/session.inc');
session_start();

// if the user isn't logged in and tries to access a page
// that requires authentication, send them back to the index page
if(!$HTTP_SESSION_VARS["login"]) {
	header("Location: index.php");
	exit;
}
?>
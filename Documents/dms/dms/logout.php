<?php
#####################################################################
# NAME/ PURPOSE - this file logs users out of the system
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

require('lib/config.inc');
require('lib/session.inc');
session_start();
session_destroy();

////////////////////////////////////////////////////////////////////
// KLG - Original version had a redirect to document root
// but that meant that - if you installed this in a directory
// other than the root directory -  when you logged out, it would
// send you to the site's homepage. 
// Redirect now points to DMS index page
///////////////////////////////////////////////////////////////////
header("Location: index.php?xo=1");
?>

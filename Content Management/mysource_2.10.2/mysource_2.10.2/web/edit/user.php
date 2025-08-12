<?  ##############################################
   ### MySource ------------------------------###
  ##- Backend Edit file -- PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: web/edit/user.php
## Desc: Screen for managing user details
## $Source: /home/cvsroot/mysource/web/edit/user.php,v $
## $Revision: 2.1 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:22 $
#######################################################################
# Initialise
include_once("../init.php");
#---------------------------------------------------------------------#

$USER = new User($_REQUEST['userid']);
if($USER->id > 0) {
	$USER->print_backend();
} else {
	header("Location: users.php");
}

?>
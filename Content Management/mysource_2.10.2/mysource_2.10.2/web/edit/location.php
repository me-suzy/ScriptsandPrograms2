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
## File: web/edit/location.php
## Desc: Screen for managing location details
## $Source: /home/cvsroot/mysource/web/edit/location.php,v $
## $Revision: 2.1 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:22 $
#######################################################################
# Initialise
include_once("../init.php");
#---------------------------------------------------------------------#
$users_system = &get_users_system();
$LOC = &$users_system->get_location($_REQUEST['locationid']);
if(!$LOC->id) {
	if(!$_REQUEST['premisesid'] && !$_REQUEST['placementid']) {
		header("Location: users.php"); # Must have something to link to!
	} else {
		$LOC = new Location();
		$LOC->print_backend();
	}
} else {
	$LOC->print_backend();
}
?>
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
## File: web/edit/index.php
## Desc: Index file for web backend. Establishes the frameset.
## $Source: /home/cvsroot/mysource/web/edit/index.php,v $
## $Revision: 2.4 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:22 $
#######################################################################
# Initialise
include_once("../init.php");
#---------------------------------------------------------------------#
# Let MySource hangle any special actions
process_mysource_action($mysource_action);

 ####################################
# Establish web system
$WEB = &get_web_system();
$USERS = &get_users_system();
$system_config = get_system_config();
 ####################################################################
# SECUIRTY - Only let in those who have permission to work on a site
if (!$SESSION->logged_in()) {
	$SESSION->login_screen("$system_config->system_name - Backend","You must be logged in to proceed.");
}
# See if we can tell where they are from the URL
$url = ereg_replace("\/$system_config->backend_suffix.*$","",$WEB->current_url());
$info = $WEB->get_url_info($url);
if (is_array($info)) {
	foreach($info as $k => $v) { # Set these globals. Remember, globals already set (GET, POST, COOKIE) have preference
		$_REQUEST[$k] = ($_REQUEST[$k]) ? $_REQUEST[$k] : $v;
	}
}
$WEB->determine_current_objects();

 ##################################
# Send them off where they belong
$editable_sites = &$WEB->get_editable_sites(); 

if(!empty($editable_sites)) {
	$location = "mysource.php";
	if($WEB->current_siteid > 0 && isset($editable_sites[$WEB->current_siteid])) { # Current site
		$location = "site.php?siteid=$WEB->current_siteid";
		if($WEB->current_pageid > 0) {
			$site = &$WEB->get_site();
			if($site->page_read_access($WEB->current_pageid)) {
				$location = "page.php?pageid=$WEB->current_pageid&template_edit=1";
				if($WEB->current_fileid) {
					$location = "page.php?fileid=$WEB->current_fileid&file_edit=1";
				}
			}
		}
	}
} else {
	 ##############################################
	# Maybe they're a user-managing type o' person
	$managed_organisationids = &$USERS->get_managed_organisationids();
	if(!$location && !empty($managed_organisationids)) {
		$location = "users.php";
	}
}

 #########################
# Get outta here, punk
if(!$location) {
	$SESSION->login_screen("$SYSTEM_CONFIG->system_name - Backend","You do not have permission to access this area.");
}

header("Location: $location");

?>
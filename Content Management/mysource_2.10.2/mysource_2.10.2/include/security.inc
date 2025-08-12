<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
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
## File: include/security.inc
## Desc: Functions for dealing with security in the system
## $Source: /home/cvsroot/mysource/include/security.inc,v $
## $Revision: 2.5 $
## $Author: mbrydon $
## $Date: 2004/02/29 23:16:46 $
#######################################################################

#---------------------------------------------------------------------#

        ###############################
       #                             #
      #   W R I T E   A C C E S S   #
     #                             #
    ###############################


  ############################################
 # Are we root? Yes? THEN WE CAN DO ANYTHING! MWAHAHA
#############################################

function user_root($userid=0) {
	if($userid > 0) {
		$users_sys = &get_users_system();
		$user = &$users_sys->get_user($userid);
	} else {
		$session = &get_mysource_session();
		$user = $session->user;
	}

	return ($user->id == 1 && $user->login == "root");
}

  ##############################
 # Are we running as a superuser?
#####################################

function superuser($type='',$userid=0) {
	if($userid > 0) {
		$users_sys = &get_users_system();
		$user = &$users_sys->get_user($userid);
	} else {
		$session = &get_mysource_session();
		$user = $session->user;
	}
	$system_config = &get_system_config();

	if ($type) {
		return (superuser('',$userid) || in_array($user->login,$system_config->superusers[$type]));
	} else {
		return (user_root($userid) || in_array($user->login,$system_config->superusers['all']));
	}
}


  ################################################################################
 # XSS SECURITY
#
# This code takes each user-submitted value and santises it to reduce
# the possibility of an XSS exploit. This is called before any other
# "include"s and before any user variables have been referenced.
#
# My disclaimer...
#   This code will not eliminate all website vulnerabilities!
#   It is simply another layer of protection to restrict the potential for damage.
#
##################################################################################

/**
* Removes unwanted elements such as HTML tags from the varible supplied.
* This works in concert with the code to eliminate XSS attacks by user injection
* of malicious code.
*
* @param	string $user_val A variable to sanitise
* @returns	string
* @access	public
*/
function clean_val($user_val)	{
	return strip_tags(htmlentities($user_val));
}
	

/**
* Cycles through each variable potentially submitted by the user and santises
* all values to eliminate XSS attacks. All "safe" values are then re-inserted
* back into their source array so that code reliant on submitted values need not
* be changed (ie; all cleaned $_POST variables are made available through $_POST).
*
* @returns	void
* @access	public
*/
function clean_user_variables()	{
	# sanitise all user-entered request fields (GET, POST, and REQUEST)
	
	foreach($_GET as $key=>$val)	{
		if (!is_xss_exclusion($key))	{
			$_GET[$key] = clean_val($_GET[$key]);
		}
	}

	foreach($_POST as $key=>$val)	{
		if (!is_xss_exclusion($key))	{
			$_POST[$key] = clean_val($_POST[$key]);
		}
	}

	foreach($_REQUEST as $key=>$val)	{
		if (!is_xss_exclusion($key))	{
			$_REQUEST[$key] = clean_val($_REQUEST[$key]);
		}
	}
}


/**
* Compares a form field name to a list of trusted fields.
* Trusted fields include front- and backend WYSIWYG elements and the heading and content
* fields in Fixed Template
*
* @returns      boolean
* @access       public
*/
function is_xss_exclusion($field_name)	{
	# form elements with the following field names will not be scanned for XSS
	# be careful!
	$xss_exclusions = array("bodycopy_saved", "content", "squizlib_wysiwyg_content");

	return in_array($field_name, $xss_exclusions);
}


/**
* Implements XSS Security (if enabled through config)
*
* PLEASE NOTE:
* 	ALL BACKEND, WYSIWYG, and EXCLUSIONS MENTIONED IN THE PREVIOUS FUNCTION
* 	ARE NOT SCANNED FOR XSS SECURITY BREACHES *REGARDLESS* OF THE CONFIGURATION STATE.
*
* @returns      void
* @access       public
*/
function start_xss_security($in_backend)	{
	$mysource_conf = &get_config('mysource');
	$xss_security = $mysource_conf->xss_security;

	# disable XSS security for all backend pages
	if ($in_backend)	{
	  $xss_security = false;
	}

	# if XSS security is activated...
	if ($xss_security)	{
	  # sanitise all user-submitted values
	  clean_user_variables();
	}
}

?>

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
## File: include/usersobject.inc
## Desc: A parent class for classes that "belong" to the users system
## $Source: /home/cvsroot/mysource/include/usersobject.inc,v $
## $Revision: 2.4 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:59 $
#######################################################################

#---------------------------------------------------------------------#

 ########################################################
# Various classes throughout the system are "part" of
# the users system, and like to have a refernce to it
class UsersObject extends CacheableObject {

	var $users_system;

	 ###################################################
	# Child class must call this in their constructors
	function UsersObject() {
		$this->__wakeup();
	}


	 #########################################################
	# perform some actions before the object is serialised
	function __sleep() {
		$result = CacheableObject::__sleep();
		array_remove_element("users_system", $result);
		return $result;
	}#end __sleep()


	 #########################################################
	# perform some actions after the object is unserialised
	function __wakeup() {

		 #################################
		# Establish link to mother system
		$this->users_system = &get_users_system();

	}#end __wakeup()


	 ########################################
	# Returns a reference to the web database
	function &get_db() {
		return $this->users_system->get_db();
	}


	 ########################################
	# Returns a reference to the backend
	function &get_backend() {
		return $this->users_system->get_backend();
	}


	  ##########################################################################################
	 # Given an attempted userid, login or email, tries to find the user and returns the object
	function &get_user($userid=0) {
		return $this->users_system->get_user($userid);
	}

	  ##########################################################################################
	 # Given an organisationid tries to find the orgnisation and returns the object
	function &get_organisation($organisationid=0) {
		return $this->users_system->get_organisation($organisationid);
	}

	  #############################################
	 # Given locationid tries to find the location
	function &get_location($locationid=0) {
		return $this->users_system->get_location($locationid);
	}
	
}
?>

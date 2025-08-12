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
## $Source: /home/cvsroot/mysource/include/systemobject.inc,v $
## $Revision: 2.4 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:59 $
#######################################################################

#---------------------------------------------------------------------#


class SystemObject extends CacheableObject {

	 ###################################################
	# Child class must call this in their constructors
	function SystemObject() {
		$this->__wakeup();
	}


	 #########################################################
	# perform some actions before the object is serialised
	function __sleep() {
		$object_vars = get_object_vars($this);
		unset($object_vars['mysource_system']);
		return array_keys($object_vars);
	}#end __sleep()


	 #########################################################
	# perform some actions after the object is unserialised
	function __wakeup() {
		$this->mysource_system = &get_mysource_system();
	}#end __wakeup()


	 ####################################################
	# The MySource system can just use the user database
	function &get_db() {
		$mysource_system = &get_mysource_system();
		return $mysource_system->get_db();
	}

	 ######################################
	# Get the web system
	function &get_web_system() {
		return get_web_system();
	}

	function &get_backend() {
		$web_system = &$this->get_web_system();
		$backend = &$web_system->get_backend();
		return $backend;
	}

	 ######################################
	# Get the user system
	function &get_users_system() {
		return get_users_system();
	}

}
?>

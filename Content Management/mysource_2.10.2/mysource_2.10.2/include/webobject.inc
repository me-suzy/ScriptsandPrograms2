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
## File: include/web.inc
## Desc: A parent class for the classes that "belong" to the web system
## $Source: /home/cvsroot/mysource/include/webobject.inc,v $
## $Revision: 2.3 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:16 $
#######################################################################

#---------------------------------------------------------------------#



 #########################################################
# Various classes throughout the system are "part" of the
# web system, and like to have a refernce to it
class WebObject extends CacheableObject {

	var $web_system;

	 ###################################################
	# Child class must call this in their constructors
	function WebObject() {
		$this->__wakeup();
	}


	 #########################################################
	# perform some actions before the object is serialised
	function __sleep() {
		$result = CacheableObject::__sleep();
		array_remove_element("web_system", $result);
		return $result;
	}#end __sleep()


	 #########################################################
	# perform some actions after the object is unserialised
	function __wakeup() {

		 #################################
		# Establish link to mother system
		$this->web_system = &get_web_system();

	}#end __wakeup()


	 ########################################
	# Returns a reference to the web database
	function &get_db() {
		return $this->web_system->get_db();
	}


	 ########################################
	# Returns a reference to the backend
	function &get_backend() {
		return $this->web_system->get_backend();
	}
}

?>

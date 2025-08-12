<?  ##############################################
   ### SquizLib ------------------------------###
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
## File: include/object.inc
## Desc: The root class from whence all classes shall spring
## $Source: /home/cvsroot/squizlib/object/object.inc,v $
## $Revision: 2.6 $
## $Author: csmith $
## $Date: 2003/05/16 05:19:24 $
#######################################################################
#---------------------------------------------------------------------#



 ############################################
# Things every decent object should have
class Object {
		 
	 ###################################
	# Variables
	var $error_msg='';		# Current error message;
	var $print_errors = 1;	# Print errors as they occur on not;
	var $error_call='';		# Function to call with error message when printing


	 ##################################
	# Constructor
	function Object () {
	}

	 #########################################################
	# perform some actions before the object is serialised
	function __sleep() {
		$object_vars = get_object_vars($this);
		if (!is_array($object_vars)) $object_vars = array();
		return array_keys($object_vars);
	}#end __sleep()


	 ################################
	# Sets the current error message
	function _set_error($m='BAM' ,$f = __FILE__,$l = __LINE__) {
		# Strip out the file path begining
		global $SYSTEM_ROOT;
		$f = str_replace($SYSTEM_ROOT, '', $f);
		$m = str_replace($SYSTEM_ROOT, '', $m);
		$this->error_msg = $m;
		if ($this->print_errors) {
			$msg = strtoupper(get_class($this))." ERROR: $m";
			if ($this->error_call && function_exists($this->error_call)) {
				$fn = $this->error_call;
				$fn($f,$l,$msg);
			} else {
				global $ERROR_REPORTER_FUNCTION;
				if ($ERROR_REPORTER_FUNCTION && function_exists($ERROR_REPORTER_FUNCTION)) {
					$fn = $ERROR_REPORTER_FUNCTION;
					$fn($f,$l,$msg);
				} else {
					echo $msg;
				}
			}
		}
	}

	 ###################################################
	# Sets the function to call upon printing an error
	function set_error_call($function_name='') {
		if (function_exists($function_name)) {
			$this->error_call = $function_name;
		} else {
			$this->_set_error("Unable to set error call function, does not exist: $function_name");
		}
	}

	 ###################################
	# Returns the current error message
	function error_reporting($val=0) {
		$this->print_errors = $val;
	}

	 ###################################
	# Returns the current error message
	function error() {
		return $this->error_msg;
	}

	 ###################################
	# Clear the current error message
	function clear_error() {
		$this->error_msg = '';
	}

	/**
	 * Always returns false as a default for pages not being dynamic
	 *
	 * This is here for printer friendly pages
	 *
	 * @access public
	 * @returns boolean
	 */
	function is_dynamic() {
		return false;
	}
}

?>
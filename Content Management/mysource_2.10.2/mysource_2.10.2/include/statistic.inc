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
## File: include/report.inc
## Desc: Parent class for system reporting
## $Source: /home/cvsroot/mysource/include/statistic.inc,v $
## $Revision: 2.4.2.1 $
## $Author: achadszinow $
## $Date: 2004/04/14 04:56:26 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/systemobject.inc");
include_once("$INCLUDE_PATH/parameter_set.inc");
#---------------------------------------------------------------------#


/**
* The base class for creating statistics
*
* @access  public
* @package Statistics
*/
class Statistic extends SystemObject {

	/**
	* The type of statistic to record (page, file etc)
	* @var string
	*/
	var $statistic_type='';


	/**
	* Constructor
	*
	* @returns object Statistic
	* @access  public
	*/
	function Statistic() {
		SystemObject::SystemObject();
	}

	function set_statistic_type($type='') {
		$this->statistic_type = strtolower($type);
	}

	function get_available_statistics() {
		global $XTRAS_PATH;
		$types = new XtrasRegistry($XTRAS_PATH.'/statistics/', 'Statistic');
		$retval = array();
		foreach ($types->register as $code => $data) {
			$code = strtolower($code);
			if ($system_config->statistics_reporter != 'simple' && $code == 'simple') continue;
			$retval[$code] = $code;
		}
		return $retval;
	}

	function log_hit($vars) {
		return;
	}
}
?>
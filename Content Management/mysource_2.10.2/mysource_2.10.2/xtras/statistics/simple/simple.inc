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
## $Source: /home/cvsroot/xtras/statistics/simple/simple.inc,v $
## $Revision: 1.4 $
## $Author: csmith $
## $Date: 2003/09/29 06:12:19 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/statistic.inc");
#---------------------------------------------------------------------#

/**
* Simple Statistics Reporter
* A module to record total and running total hits for pages and files
*
* @access public
* @package Statistics
*/
class Simple extends Statistic {

	
	 ##############################
	# Constructor
	function Simple($statistic_type) {
		Statistic::Statistic($statistic_type);
	}


	function log_hit($vars) {
		switch ($this->statistic_type) {
			case 'page' :
				$this->log_page_hit($vars['pageid']);
				break;
			case 'file' :
				$this->log_file_hit($vars['fileid']);
				break;
		}
	
	}

	function log_page_hit($pageid) {
		$session = &get_mysource_session();
		$web = &$this->get_web_system();
		$web->db->update("UPDATE page SET hits=hits+1, hits_running_total=hits_running_total+1 WHERE pageid='$pageid'");
	}


	function log_file_hit($fileid) {
		$session = &get_mysource_session();
		$web = &$this->get_web_system();
		$web->db->update("UPDATE file SET hits=hits+1, hits_running_total=hits_running_total+1 WHERE fileid='$fileid'");
	}
}

?>

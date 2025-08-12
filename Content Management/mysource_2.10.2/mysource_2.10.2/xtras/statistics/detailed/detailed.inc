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
## $Source: /home/cvsroot/xtras/statistics/detailed/detailed.inc,v $
## $Revision: 1.2 $
## $Author: csmith $
## $Date: 2003/09/29 06:12:58 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/statistic.inc");
#---------------------------------------------------------------------#

/**
* Detailed Statistics Reporter
* A module to record detailed statistics with the ability
* to graph over any tiem period
*
* @access public
* @package Statistics
*/
class Detailed extends Statistic {

	
	 ##############################
	# Constructor
	function Detailed($statistic_type) {	
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
			case 'session' :
				$this->log_session();
				break;
		}
	
	}

	function log_page_hit($pageid) {
		$session = &get_mysource_session();
		$web = &$this->get_web_system();
		$web->db->insert("INSERT DELAYED INTO log_page_hit (pageid,sessionid,hit_time,userid) VALUES('$pageid', '".session_id()."', now(), '".$session->user->id."')");
	}


	function log_file_hit($fileid) {
		$session = &get_mysource_session();
		$web = &$this->get_web_system();
		$web->db->insert("INSERT DELAYED INTO log_file_hit (fileid,sessionid,hit_time,userid) VALUES('$fileid','".session_id()."',now(),'".$session->user->id."')");
	}

	function log_session() {
		$session = &get_mysource_session();
		$web = &$this->get_web_system();
		if (!$web->db->single_element("SELECT sessionid FROM log_session WHERE sessionid='".session_id()."'")) {
			$web->db->insert("INSERT DELAYED INTO log_session (sessionid,start_time,user_agent,remote_addr,remote_host,referer) VALUES ('".session_id()."', now(), '".addslashes($_SERVER['HTTP_USER_AGENT'])."', '".addslashes($_SERVER['REMOTE_ADDR'])."', '".(($system_config->log_remote_hosts)?gethostbyaddr($_SERVER['REMOTE_ADDR']):"")."', '".addslashes($_SERVER['HTTP_REFERER'])."')");
		}
	}
}

?>

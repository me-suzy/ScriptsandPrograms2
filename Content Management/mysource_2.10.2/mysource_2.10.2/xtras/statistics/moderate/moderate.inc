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
## $Source: /home/cvsroot/xtras/statistics/moderate/moderate.inc,v $
## $Revision: 1.1 $
## $Author: gsherwood $
## $Date: 2003/02/17 03:24:54 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/statistic.inc");
#---------------------------------------------------------------------#

/**
* Moderate Statistics Reporter
* Records page and file hits, visits, and users 
* per day with the ability to graph over any time period.
*
* @access public
* @package Statistics
*/
class Moderate extends Statistic {

	
	 ##############################
	# Constructor
	function Moderate($statistic_type) {
		Statistic::Statistic($statistic_type);
	}


	function log_hit($vars) {
		switch ($this->statistic_type) {
			case 'page' :
				$this->log_asset_hit($vars['pageid'],'page');
				break;
			case 'file' :
				$this->log_asset_hit($vars['fileid'],'file');
				break;
		}
	}

	function log_asset_hit($assetid, $table) {
		$session = &get_mysource_session();
		$hits = $session->get_var("mysource_log_hits");

		$new_user  = false;
		$new_visit = false;

		if ($session->user->id && !isset($hits[$session->user->id]["{$table}_{$assetid}"])) {
			# a user has viewed the page for the first time
			$new_user = true;
			$new_visit = true;
			$hits[$session->user->id]["{$table}_{$assetid}"] = true;
			$session->set_var("mysource_log_hits", $hits);
		} else if (!isset($hits['not_logged_in']["{$table}_{$assetid}"]) && !isset($hits[$session->user->id]["{$table}_{$assetid}"])) {
			# a normal vistor has viewed the page for the first time
			$new_visit = true;
			$hits['not_logged_in']["{$table}_{$assetid}"] = true;
			$session->set_var("mysource_log_hits", $hits);
		}

		$date = date('Y-m-d');
		$web = &$this->get_web_system();
		$query = "UPDATE log_{$table}_hit SET hits=hits+1";
		if ($new_visit) $query .= ', visits=visits+1';
		if ($new_user) $query .= ', users=users+1';
		$query .= " WHERE {$table}id='$assetid' AND hit_time='$date'";
		if (!$web->db->update($query)) {
			# update failed, try an insert
			$query = "INSERT INTO log_{$table}_hit ({$table}id, hit_time, hits, visits, users) VALUES ('$assetid', '$date', '1', '1', '".(($new_user) ? '1' : '0')."')";
			$web->db->insert($query);
		}

	}


}
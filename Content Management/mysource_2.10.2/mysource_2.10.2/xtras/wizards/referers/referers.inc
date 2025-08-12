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
## $Source: /home/cvsroot/xtras/wizards/referers/referers.inc,v $
## $Revision: 1.4 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:13 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Page Referers Wizard
* A wizard to produce a table of the pages that were 
* viewed prior to the page the wizard is being run on
*
* @access public
* @package Wizards
*/
class Referers extends Wizard {

	var $restrict_cond = '1=1';
	var $total_hit_cond = '1=1';
	var $report_desc = 'Most visited pages';
	var $log_table = '';
	var $time_field   = 'hit_time';
	var $compatible_with = array('page');
	var $parameters = array('num_results'  => '10',
							'date' => '0000-00-00',
							'range' => 'month'
							  );


	 ##############################
	# Constructor
	function Referers (&$asset) {	
		Wizard::Wizard($asset);
		switch($this->asset_type) {
			case 'page' :
				$this->log_table = 'log_page_hit LEFT JOIN page ON log_page_hit.pageid=page.pageid';
				$this->restrict_cond = "page.pageid='".$this->caller->id."'";
				$this->total_hit_cond = "page.pageid='".$this->caller->id."'";
				$this->report_desc = "Page referers for {$this->caller->name}";
				break;
		}
		$this->add_button('Quick Report','Quick',100,1);
	}


	function set_defaults() {
		$this->parameters['date'] = date('Y-m-d');
	}


	function get_date_min() {
		$db = &$this->get_db();
		$sql = "SELECT SUBSTRING(MIN($this->time_field),1,4) AS first_hit FROM $this->log_table WHERE $this->restrict_cond AND $this->time_field > '0000-00-00 00:00:00'";
		return $db->single_element($sql).'-01-01 00:00:00';
	}
	function get_date_max() {
		return date('Y').'-12-31 00:00:00';
	}


	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {
		
		if (!method_exists($backend,'open_field')) $backend = &$this->get_backend();
		$db = &$this->get_db();

		$range		 = $this->parameters['range'];
		$num_results = $this->parameters['num_results'];
		
		list($date) = explode(' ',$this->parameters['date']);
		list($year,$month,$day) = explode('-',$date);


		$stamp = mktime(0,0,0,(($month)?$month:1),(($day)?$day:1),(($year)?$year:2000));
		
		switch($range) {
			case 'year':
				$range_cond = "SUBSTRING($this->time_field,1,4) = '$year'";
				$range_desc = 'the year '.date('Y',$stamp);
				break;
			case 'month':
				$range_cond = "SUBSTRING($this->time_field,1,7) = '$year-$month'";
				$range_desc = date('F, Y',$stamp);
				break;
			case 'day':
				$range_cond = "SUBSTRING($this->time_field,1,10) = '$year-$month-$day'";
				$range_desc = date('D, j F Y',$stamp);
				break;
			default:
				$range_cond = '1=1';
		}
			
	
		$hit_info = $db->associative_array("SELECT hit_time, log_page_hit.sessionid, log_session.referer FROM log_page_hit LEFT JOIN page ON log_page_hit.pageid=page.pageid LEFT JOIN log_session ON log_page_hit.hit_time=log_session.start_time WHERE $this->restrict_cond AND $range_cond;");

		$referers = array();
		foreach ($hit_info as $hit_time => $data) {
			$ref = trim($data['referer']);
			if ($ref != '') {
				if (($pos = strpos($ref,'?')) !== false) $ref = substr($ref,0,$pos);
				if (strpos($ref,'http://') === 0) $ref = substr($ref,7);
				$referers[$ref]['hits']++;
			} else {
				# no referer means the hit was internal (maybe)
				# so try and find out the last hit
				list($pageid, $siteid) = $db->single_row("SELECT pageid FROM log_page_hit WHERE hit_time < '$hit_time' AND sessionid='".$data['sessionid']."' ORDER BY hit_time DESC LIMIT 1;");
				if ($pageid) {
					$referers[$pageid]['siteid'] = $siteid;
					$referers[$pageid]['hits']++;
				} else {
					# try the referer in log session again
					# because there may have been a time lag
					$ref = $db->single_element("SELECT referer FROM log_session WHERE sessionid='".$data['sessionid']."';");
					$ref = trim($ref);
					if ($ref != '') {
						if (($pos = strpos($ref,'?')) !== false) $ref = substr($ref,0,$pos);
						if (strpos($ref,'http://') === 0) $ref = substr($ref,7);
						$referers[$ref]['hits']++;
					} else {
						$referers['Unknown']['hits']++;
					}
				}
			}
		}

		uasort($referers,'sort_referers');

		if (!count($referers)) {
			# no results found
			$backend->open_field('Sorry', 'one_liner');
			echo("There appear to be no records during $range_desc.");
		} else {

			list($total_hits,$total_visits,$total_users) = $db->single_row("SELECT COUNT(*), COUNT(DISTINCT sessionid), COUNT(DISTINCT userid) FROM ".$this->log_table." WHERE ".$this->total_hit_cond." AND $range_cond");

			$backend->open_section("$this->report_desc during $range_desc.");

			$backend->open_section('Summary');
			$backend->open_field('Total Hits','one_liner');
			echo number_format($total_hits);
			$backend->open_field('Total Visits','one_liner');
			echo number_format($total_visits);
			$backend->open_field('Total Users','one_liner');
			echo number_format($total_users);

			$backend->open_section('Tabulated Results');
			$web_system = &$this->get_web_system();

			?>
			<table border="1" width="100%" bgcolor="#F0F0F0">
				<tr>
					<td><b>Referer</b></td>
					<td align="center"><b>Hits</b></td>
					<td align="center"><b>% Of Total Hits</b></td>
				</tr>
			<?
				foreach ($referers as $referer => $data) {
					if ($num_results <= 0) break;
					?><tr><?
					if ((int)$referer > 0) {
						$page = &$web_system->get_page($referer);
						$link = $page->get_url();
						?><td><a href="http://<?=$link?>" target="_blank"><?=$link?></a></td><?
					} else if ($referer != 'Unknown') {
						?><td><a href="http://<?=$referer?>" target="_blank"><?=$referer?></a></td><?
					} else {
						?><td>Unknown</td><?
					}
					?>
						<td align="center"><?=$data['hits']?></td>
						<td align="center"><?=number_format(($data['hits'] / $total_hits) * 100,4)?>%</td>
					  </tr>
					<?
					$num_results--;
					}
			?></table><?

		}

	}
}


function sort_referers($a,$b) {
	if ($a['hits'] == $b['hits']) return 0;
	return ($a['hits'] < $b['hits']);
}

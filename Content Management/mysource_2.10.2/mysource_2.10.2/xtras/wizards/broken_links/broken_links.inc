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
## $Source: /home/cvsroot/xtras/wizards/broken_links/broken_links.inc,v $
## $Revision: 1.6 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Broken Link Wizard
* A wizard to produce a table of links to pages that 
* caused a 404 Page Not Found error
*
* @access public
* @package Wizards
*/
class Broken_links extends Wizard {

	var $restrict_cond = '1=1';
	var $log_table = 'log_page_not_found LEFT JOIN page ON log_page_not_found.pageid=page.pageid';
	var $compatible_with = array('web');
	var $parameters = array('num_results'  => '10',
							'date' => '0000-00-00',
							'range' => 'month'
							  );


	 ##############################
	# Constructor
	function Broken_links (&$asset) {	
		Wizard::Wizard($asset);
		$this->add_button('Quick Report','Quick',100,1);
	}


	function get_date_min() {
		$db = &$this->get_db();
		$sql = "SELECT SUBSTRING(MIN(hit_time),1,4) AS first_hit FROM $this->log_table WHERE $this->restrict_cond AND hit_time > '0000-00-00 00:00:00'";
		return $db->single_element($sql).'-01-01 00:00:00'; 
	}
	function get_date_max() {
		return date('Y').'-12-31 00:00:00';
	}


	function set_defaults() {
		$this->parameters['date'] = date('Y-m-d');
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
				$range_cond = "SUBSTRING(hit_time,1,4) = '$year'";
				$range_desc = 'the year '.date('Y',$stamp);
				break;
			case 'month':
				$range_cond = "SUBSTRING(hit_time,1,7) = '$year-$month'";
				$range_desc = date('F, Y',$stamp);
				break;
			case 'day':
				$range_cond = "SUBSTRING(hit_time,1,10) = '$year-$month-$day'";
				$range_desc = date('D, j F Y',$stamp);
				break;
			default:
				$range_cond = '1=1';
		}
			
	
		$hit_info = $db->associative_array("SELECT log_page_not_found.hit_time, log_page_not_found.pageid, log_page_not_found.referer FROM $this->log_table WHERE $this->restrict_cond AND $range_cond;");

		$broken_links = array();
		foreach ($hit_info as $time => $data) {
			$ref = trim($data['referer']);
			if ($ref == '') $ref = 'Unknown';
			$broken_links[$ref]['pages'][$data['pageid']]++;
		}

		uasort($broken_links,'sort_broken_links');

		if (!count($broken_links)) {
			# no results found
			$backend->open_field('Sorry', 'one_liner');
			echo("There appear to be no broken links during $range_desc.");
		} else {

			list($total_hits) = $db->single_row("SELECT COUNT(*) FROM ".$this->log_table." WHERE ".$this->restrict_cond." AND $range_cond");

			$backend->open_section("Broken Link Report during $range_desc.");

			$backend->open_section('Summary');
			$backend->open_field('Total Broken Links','one_liner');
			echo number_format($total_hits);

			$backend->open_section('Tabulated Results');

			?>
			<table border="1" width="100%" bgcolor="#F0F0F0">
				<tr>
					<td rowspan="2" valign="top"><b>Broken Link From URL</b></td>
					<td colspan="3" align="center"><b>Links</b></td>
				</tr>
				<tr>
					<td align="center"><b>Page ID</b></td>
					<td align="center"><b>Hits</b></td>
					<td align="center"><b>% Of All Broken Links</b></td>
				</tr>
			<?
				foreach ($broken_links as $ref => $data) {
					if ($num_results <= 0) break;
					?>
					<tr>
						<td rowspan="<?=count($data['pages'])?>"><?=($ref != 'Unknown') ? "<a href=\"$ref\" target=\"_blank\">$ref</a>" : $ref ?></td>
					<?
					foreach ($data['pages'] as $pageid => $hits) {
						?>
						<td align="center"><?=$pageid?></td>
						<td align="center"><?=$hits?></td>
						<td align="center"><?=number_format(($hits / $total_hits) * 100,2)?>%</td>
						<?
					}
					?></tr><?
					$num_results--;
					}
			?></table><?

		}

	}
}


function sort_broken_links($a,$b) {
	if ($a['hits'] == $b['hits']) return 0;
	return ($a['hits'] < $b['hits']);
}
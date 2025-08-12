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
## $Source: /home/cvsroot/xtras/wizards/most_visited/most_visited.inc,v $
## $Revision: 1.5 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Most Visited Wizard
* A wizard to produce a table of the most visited pages or subpages
*
* @access public
* @package Wizards
*/
class Most_Visited extends Wizard {

	var $restrict_cond = '1=1';
	var $total_hit_cond = '1=1';
	var $report_desc = 'Most visited pages';
	var $log_table = '';
	var $time_field   = 'hit_time';
	var $compatible_with = array('site','page');
	var $parameters = array('num_results'  => '10',
							'date' => '0000-00-00',
							'range' => 'month'
							  );


	 ##############################
	# Constructor
	function Most_Visited (&$asset) {	
		Wizard::Wizard($asset);
		switch($this->asset_type) {
			case 'site' :
				$this->log_table = 'log_page_hit LEFT JOIN page ON log_page_hit.pageid=page.pageid';
				$this->restrict_cond = "page.siteid='".$this->caller->id."'";
				$this->total_hit_cond = "page.siteid='".$this->caller->id."'";
				$this->report_desc = "Most visited pages for {$this->caller->name}";
				break;
			case 'page' :
				$this->log_table = 'log_page_hit LEFT JOIN page ON log_page_hit.pageid=page.pageid';
				$this->restrict_cond = "page.parentid='".$this->caller->id."'";
				$this->total_hit_cond = "page.parentid='".$this->caller->id."'";
				$this->report_desc = "Most visited subpages for {$this->caller->name}";
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
			
	
		$hit_info = $db->associative_array("SELECT page.pageid, page.siteid, page.name, COUNT(*) AS count FROM $this->log_table WHERE $this->restrict_cond AND $range_cond GROUP BY page.pageid ORDER BY count DESC LIMIT $num_results;");

		# get a list of the resturned pageids
		$pageids = '';
		foreach ($hit_info as $result) {
			$pageids .= $result['pageid'].', ';
		}
		# remove trailing comma
		$pageids = substr($pageids,0,-2);

		if (trim($pageids) == '') {
			# no results found
			$backend->open_field('Sorry', 'one_liner');
			echo("There appear to be no records during $range_desc.");
		} else {

			$session_info = $db->associative_array("SELECT page.pageid, COUNT(DISTINCT sessionid) AS count FROM ".$this->log_table." WHERE ".$this->restrict_cond." AND $range_cond AND page.pageid IN ($pageids) GROUP BY page.pageid");

			$user_info = $db->associative_array("SELECT page.pageid, COUNT(DISTINCT userid) AS count FROM ".$this->log_table." WHERE ".$this->restrict_cond." AND $range_cond AND page.pageid IN ($pageids) GROUP BY page.pageid");

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
			global $EDIT_DIR;

			?>
			<table border="1" width="100%" bgcolor="#F0F0F0">
				<tr>
					<td align="center"><b>Page ID</b></td>
					<td><b>Page Name</b></td>
					<td align="center"><b>Hits</b></td>
					<td align="center"><b>Visits</b></td>
					<td align="center"><b>Unique Users</b></td>
					<td align="center"><b>% Of Total Hits</b></td>
				</tr>
			<?
				foreach ($hit_info as $result) {
					$link = $web_system->get_page_url($result['siteid'],$result['pageid']);
					?><tr>
						<td align="center"><a href="#" onclick="Javascript:window.opener.location='<?=$EDIT_DIR?>/page.php?p=<?=$result['pageid']?>'; return false;"><?=$result['pageid']?></a></td>
						<td><a href="<?=$link?>" target="_blank"><?=$result['name']?></a></td>
						<td align="center"><?=$result['count']?></td>
						<td align="center"><?=$session_info[$result['pageid']]?></td>
						<td align="center"><?=$user_info[$result['pageid']]?></td>
						<td align="center"><?=number_format(($result['count'] / $total_hits) * 100,2)?>%</td>
					  </tr>
					<?
					}
			?></table><?

		}

	}

}
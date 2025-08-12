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
## $Source: /home/cvsroot/xtras/wizards/detailed_hit_report/detailed_hit_report.inc,v $
## $Revision: 1.6 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Hit Report Wizard
* A wizard to produce a graph of hits, visits, 
* and unique users for a given period of time
*
* @access public
* @package Wizards
*/
class Detailed_Hit_Report extends Wizard {

	var $restrict_cond = '1=1';
	var $log_table = '';
	var $time_field   = 'hit_time';
	var $sessionid_field = 'sessionid';
	var $userid_field    = 'userid';
	var $image_save_path;
	var $subheading = 'Hit Report';
	var $layer_options = array();
	var $summary = array();
	var $compatible_with = array('site','page');
	var $parameters = array('graph_type'  => 'column',
							'layers' => array('hits','visits','users'),
							'date' => '0000-00-00',
							'range' => 'month',
							'scale' => 'dotm',
							'tabulate' => false
							  );


	 ##############################
	# Constructor
	function Detailed_Hit_Report (&$asset) {	
		Wizard::Wizard($asset);
		switch($this->asset_type) {
			case 'site' :
				$this->log_table = 'log_page_hit LEFT JOIN page ON log_page_hit.pageid=page.pageid';
				$this->restrict_cond = "page.siteid='".$this->caller->id."'";
				break;
			case 'page' :
				$this->log_table = 'log_page_hit';
				$this->restrict_cond = "pageid='".$this->caller->id."'";
				break;
		}
		$this->add_button('Quick Report','Quick',100,array(1,2,3,4));
	}

	function print_wizard() {
		$config = &get_system_config();
		if ($config->statistics_reporter != 'detailed') {
			$backend = &$this->get_backend();

			$heading = 'MySource ' . get_class($this) . ' Wizard';
			$heading .= '<br><span style="font-size:12px">';
			$heading .= ucfirst(get_class($this->caller));
			if (isset($this->caller->id)) {
				$heading .= ' '.$this->caller->id;
			} else {
				$heading .= ' System';
			}
			if (isset($this->caller->name)) $heading .= ' ('.$this->caller->name.')';
			$heading .= '</span>';
			$backend->set_heading($heading);
			$pset = &$this->get_pset();
			$backend->add_button($this->button_names['cancel'],'window.close();');
			$backend->print_header();
			?>
				Sorry, you are not running the system with detailed statistics. You can not run this wizard.
			<?
			$backend->print_commit_button();
			$backend->print_footer();
			return;
		}
		Wizard::print_wizard();
	}

	function get_date_min() {
		$db = &$this->get_db();
		$sql = "SELECT SUBSTRING(MIN($this->time_field),1,4) AS first_hit FROM $this->log_table WHERE $this->restrict_cond AND $this->time_field > '0000-00-00 00:00:00'";
		return $db->single_element($sql).'-01-01 00:00:00';
	}
	function get_date_max() {
		return date('Y').'-12-31 00:00:00';
	}



	 ########################################################
	# Changes the path where the graph images are saved
	function set_image_save_path($image_save_path) {
		$this->image_save_path = $image_save_path;
	}

	 ########################################################
	# Changes the path where the graph images are viewd
	function set_image_view_path($image_view_path) {
		$this->image_view_path = $image_view_path;
	}

	 ########################
	# Changes the subheading
	function set_subheading($subheading) {
		$this->subheading = $subheading;
	}


	function set_defaults() {
		$this->parameters['date'] = date('Y-m-d');
	}

	 
	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {
		if (!method_exists($backend,'open_field')) $backend = &$this->get_backend();
		$db = &$this->get_db();

		$this->set_image_save_path($this->caller->data_path);
		$this->set_image_view_path($this->caller->get_file_href());

		$graph_type = $this->parameters['graph_type'];
		$layers		= $this->parameters['layers'];
		$range		= $this->parameters['range'];
		$scale		= $this->parameters['scale'];
		$tabulate	= $this->parameters['tabulate'];
		
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

		 ##################################
		# Grabs some useful summary stats
		$sql = "SELECT MIN($this->time_field) AS first_hit, MAX($this->time_field) AS last_hit, COUNT($this->time_field) AS hit_count"
			 . (($this->sessionid_field)?",COUNT(DISTINCT $this->sessionid_field) AS visit_count ":",0")
			 . (($this->userid_field)?",COUNT(DISTINCT $this->userid_field) AS user_count ":",0")
			 . "FROM $this->log_table WHERE $this->restrict_cond AND $range_cond";
		
		$this->summary = $db->single_row($sql);
		
		$this->summary['first_hit_stamp'] = mysql_to_timestamp($this->summary['first_hit']);
		$this->summary['last_hit_stamp']  = mysql_to_timestamp($this->summary['last_hit']);
		
		if($this->summary['first_hit_stamp'] <= 0) {
			$backend->open_section('No Statistics found!');
			return;
		}

		if (in_array('hits',   $layers)) $this->layer_options['hits'] = "$name Hits";
		if (in_array('users',  $layers)) $this->layer_options['users']  = "$name Logged In Users";
		if (in_array('visits', $layers)) $this->layer_options['visits'] = "$name Visits";

		$backend->open_section('Summary');
		$backend->open_field('First Hit','one_liner');
		echo date("l, j F Y H:i:s",$this->summary['first_hit_stamp']);
		$backend->open_field('Last Hit','one_liner');
		echo date("l, j F Y H:i:s",$this->summary['last_hit_stamp']);

		if (in_array('hits',  $layers)) {
			$backend->open_field('Total Hits','one_liner');
			echo number_format($this->summary['hit_count']);
		}

		if (in_array('visits',  $layers)) {
			$backend->open_field('Total Visits','one_liner');
			echo number_format($this->summary['visit_count']);
		}

		if (in_array('users',  $layers)) {
			$backend->open_field('Total Logged In Users','one_liner');
			echo number_format($this->summary['user_count']);
		}

		$backend->open_section('Graph');

		# Create the graph object
		global $SQUIZLIB_PATH;
		switch($scale) {
			case 'moty':
				include_once("$SQUIZLIB_PATH/graph/moty_graph.inc");
				$graph = new Moty_Graph($graph_type, "Monthly report for $range_desc", $this->subheading,600);
				$candidate_field = "SUBSTRING($this->time_field,6,2)";
				break;
			case 'dotm':
				include_once("$SQUIZLIB_PATH/graph/dotm_graph.inc");
				$graph = new Dotm_Graph($graph_type, "Daily report for $range_desc", $this->subheading,600);
				$graph->set_month("$year-$month");
				$candidate_field = "SUBSTRING($this->time_field,9,2)";
				break;
			case 'dotw':
				include_once("$SQUIZLIB_PATH/graph/dotw_graph.inc");
				$graph = new Dotw_Graph($graph_type, "Daily report for the weeks of $range_desc", $this->subheading,600);
				$candidate_field = "WEEKDAY($this->time_field)";
				break;
			case 'hotd': default:
				include_once("$SQUIZLIB_PATH/graph/hotd_graph.inc");
				$graph = new Hotd_Graph($graph_type, "Hourly report for $range_desc", $this->subheading,600);
				$candidate_field = "SUBSTRING($this->time_field,12,2)";
				break;
		}

		$graph->set_limits(0);

		# grab the data from the database and whack it on in
		foreach($layers as $layer) {
			$graph->add_layer("$this->asset_type $layer",$this->layer_options[$layer]);
			switch($layer) {
				case 'hits':
					$info = $db->associative_array("SELECT $candidate_field AS candidate, COUNT(*) AS count FROM ".$this->log_table." WHERE ".$this->restrict_cond." AND $range_cond GROUP BY candidate ORDER BY candidate;");
					break;
				case 'visits':
					if(!$this->sessionid_field) break;
					$info = $db->associative_array("SELECT $candidate_field AS candidate, COUNT(DISTINCT $this->sessionid_field) AS count FROM ".$this->log_table." WHERE ".$this->restrict_cond." AND $range_cond GROUP BY candidate ORDER BY candidate;");
					break;
				case 'users':
					if(!$this->userid_field) break;
					$info = $db->associative_array("SELECT $candidate_field AS candidate, COUNT(DISTINCT $this->userid_field) AS count FROM ".$this->log_table." WHERE ".$this->restrict_cond." AND $range_cond GROUP BY candidate ORDER BY candidate;");
					break;
				default:
					$info = array();
					break;
			}
			foreach($info as $candidate => $value) {
				$total_value++;
				$graph->set_value("$this->asset_type $layer",$candidate,$value);
			}
		}

			
		if($total_value) {
			# print it
			$backend->open_field('');
			$session = &get_mysource_session();
			$graph->render();
			$graph->save_image("$this->image_save_path/hit_report_for_user_".$session->user->id); # Overwrite last request
			$img_src = $this->image_view_path.'hit_report_for_user_'.$session->user->id.'.png';
			# make sure we refresh the image
			$img_src .= (strstr($img_src, '?') ? '&' : '?').'time='.time();
			echo('<img src="'.$img_src.'">');
			if($tabulate) {
				$backend->open_section('Tabulated Results');
				$backend->open_field('');
				echo $graph->print_table();
			}
		} else {
			$backend->open_field('Sorry', 'one_liner');
			echo("There appear to be no records during $range_desc.");
		}
	}
}
?>
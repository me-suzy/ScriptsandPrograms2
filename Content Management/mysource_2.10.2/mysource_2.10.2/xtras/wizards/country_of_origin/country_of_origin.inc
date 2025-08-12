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
## $Source: /home/cvsroot/xtras/wizards/country_of_origin/country_of_origin.inc,v $
## $Revision: 1.5 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Country Of Origin Wizard
* A wizard to produce a table of the countries that visitors are from
* based on their IP address
*
* @access public
* @package Wizards
*/
class Country_Of_Origin extends Wizard {

	var $num_steps = 2;
	var $restrict_cond = '1=1';
	var $total_hit_cond = '1=1';
	var $report_desc = 'Most visited pages';
	var $log_table = '';
	var $time_field   = 'hit_time';
	
	# valid domains minus country codes
	var $domains_lvl1 = array('biz','info','tv','ws','cc','name');
	var $domains_lvl2 = array('com','org','net','co','gov');
	
	var $compatible_with = array('site','page');
	var $parameters = array('num_results'  => '10',
							'date' => '0000-00-00',
							'range' => 'month'
							  );


	 ##############################
	# Constructor
	function Country_Of_Origin (&$asset) {	
		Wizard::Wizard($asset);
		switch($this->asset_type) {
			case 'site' :
				$this->log_table = 'log_page_hit LEFT JOIN page ON log_page_hit.pageid=page.pageid';
				$this->restrict_cond = "page.siteid='".$this->caller->id."'";
				$this->total_hit_cond = "page.siteid='".$this->caller->id."'";
				$this->report_desc = "Countries of origin for {$this->caller->name}";
				break;
			case 'page' :
				$this->log_table = 'log_page_hit LEFT JOIN page ON log_page_hit.pageid=page.pageid';
				$this->restrict_cond = "page.pageid='".$this->caller->id."'";
				$this->total_hit_cond = "page.pageid='".$this->caller->id."'";
				$this->report_desc = "Countries of origin for {$this->caller->name}";
				break;
		}
		$this->add_button('Host Lookup','Lookup',0,1);
		$this->add_button('Quick Report','Quick',100,1);
	}


	function process_special_action($action) {
		if ($action == 'Lookup') {
			$this->do_host_lookup();
		} else if ($action == 'Lookup_finished') {
			echo status_popup('100','',false,true,'#330099','Lookup Complete','');
			exit();
		}
	}


	function set_defaults() {
		$this->parameters['date'] = date('Y-m-d');
	}


	function do_host_lookup() {
		$num_to_lookup = $_GET['num_to_lookup'];
		$num = $_GET['num'];
		$num_looked = $_GET['num_looked'];
		$started = $_GET['started'];
		$start_time = $_GET['start_time'];
		$action = $_GET['action'];

		$db = &$this->get_db();
		set_time_limit(0);

		if (isset($num) && isset($num_to_lookup)) {

			if (!$started) {
				$now = time();
				$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=0&action=Lookup&started=1&start_time=$now";
				echo status_popup(1,$process_url,false,false,'#330099','Performing Host Lookup - Please wait', "Starting to lookup $num_to_lookup hosts");
				exit();
			}

			# lookup $num hosts
			for ($i = 0; $i < $num; $i++) {
				$sql = "SELECT DISTINCT log_session.remote_addr FROM log_session LEFT JOIN log_host_lookup ON log_session.remote_addr=log_host_lookup.ip WHERE log_host_lookup.host IS NULL OR log_host_lookup.host='' LIMIT 1";
				$addr = $db->single_element($sql);
				
				if (trim($addr) == '') continue;
			
				$host = gethostbyaddr($addr);
				$db->insert('INSERT INTO log_host_lookup (ip, host) VALUES(\''.addslashes($addr).'\',\''.addslashes($host).'\');');
			}


			$num_looked += $num;
			$percent = ceil(($num_looked / $num_to_lookup) * 100);
			$finish = (($num_looked >= $num_to_lookup) ? 1 : 0);
			$time_diff = time() - $start_time;
			$time_per_lookup = $time_diff / $num_looked;
			$time_left = $time_per_lookup * ($num_to_lookup - $num_looked);
			if ($time_left <= 0) $time_left = 1;
			$status = '';

			if ($finish) {
				$process_url = $this->get_backend_href()."&action=Lookup_finished&started=1";
				$status = 'Finsihing Lookup...';
				$percent = 100;
			} else {
				$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=$num_looked&action=Lookup&started=1&start_time=$start_time";
				$status = "Completed $num_looked lookups - ".($num_to_lookup - $num_looked).' remaining - est time: '.easy_time_total($time_left);
			}
			echo status_popup($percent,$process_url,false,false,'#330099','Performing Host Lookup - Please wait',$status);
			exit();
		}

		$sql = "SELECT COUNT(DISTINCT log_session.remote_addr) FROM log_session LEFT JOIN log_host_lookup ON log_session.remote_addr=log_host_lookup.ip WHERE log_host_lookup.host IS NULL OR log_host_lookup.host=''";
		$num_to_lookup = $db->single_element($sql);
		$num = 20; # number of lookups to process at a time
		$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=0&action=Lookup&started=0";
		echo status_popup(1,$process_url,true,false,'#330099','Performing Host Lookup - Please wait', "Starting to lookup $num_to_lookup hosts");
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
			
	
		$hit_info = $db->associative_column("SELECT log_host_lookup.host, COUNT(*) AS count FROM log_page_hit LEFT JOIN page ON log_page_hit.pageid=page.pageid LEFT JOIN log_session ON log_page_hit.sessionid=log_session.sessionid LEFT JOIN log_host_lookup ON log_host_lookup.ip=log_session.remote_addr WHERE $range_cond AND $this->restrict_cond GROUP BY log_session.sessionid ORDER BY count;");

		if (!count($hit_info)) {
			# no results found
			$backend->open_field('Sorry', 'one_liner');
			echo("There appear to be no records during $range_desc.");
		} else {

			# loop over the results and work out the countries and hit count
			$countries_config = &get_config('Countries');
			$country_info = array();
			foreach($hit_info as $result => $data) {
				$host = $result;
				$domain = strrchr($host,'.');
				$country_code = substr($domain,1);

				if ($country_code == '') {
					# not yet indexed
					$country = 'Not Indexed';
					$domain = 'Not Indexed';
				} else if ($country_code == 'com') {
					$country = 'US Commerical';
				} else if ($country_code == 'net') {
					$country = 'Network';
				} else if ($country_code == 'edu') {
					$country = 'US Educational';
				} else if ($country_code == 'arpa') {
					$country = 'Old Style Arpanet';
				} else if ($country_code == 'org') {
					$country = 'Organisation';
				} else if ($country_code == 'int') {
					$country = 'International';
				} else {
					if (in_array($country_code,$this->domains_lvl1)) {
						# a special domain extensions
						$country = $domain;
					} else {
						# check country codes
						if ($country = $countries_config->countries[$country_code]) {
							# this country code was found, so check the next
							# domain extensions to see if this is a combo extension
							# eg .com.au or .org.au
							$host = substr($host,0,strrpos($host,'.'));
							$domain2 = strrchr($host,'.');
							$domain2 = substr($domain2,1);
							if (in_array($domain2,$this->domains_lvl2)) $domain = '.'.$domain2.$domain;
							$country_info[$country]['domains'][$domain] = true;
						} else {
							$country = 'Unresolved/Unknown';
							$domain = 'Unresolved/Unknown';
							$country_info[$country]['domains']['Unresolved/Unknown'] = true;
						}
					}
				}
				
				

				$hits = array_sum($data);
				$country_info[$country]['domains'][$domain] = true;
				$country_info[$country]['hits'] += $hits;
				
			}

			uasort($country_info,'sort_countries');

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

			?>
			<table border="1" width="100%" bgcolor="#F0F0F0">
				<tr>
					<td><b>Country</b></td>
					<td><b>Domain Extension(s)</b></td>
					<td align="center"><b>Hits</b></td>		
					<td align="center"><b>% Of Total Hits</b></td>
				</tr>
			<?
				foreach ($country_info as $country => $data) {
					if ($num_results <= 0) break;
					$domains = '';
					foreach($data['domains'] as $domain => $true) $domains .= $domain.', ';
					$domains = substr($domains,0,-2);
					?><tr>
						<td><?=$country?></td>
						<td><?=$domains?>&nbsp;</td>
						<td align="center"><?=$data['hits']?></td>
						<td align="center"><?=number_format(($data['hits'] / $total_hits) * 100,4)?>%</td>
					  </tr>
					<?
					$num_results--;
					}
			?></table><?

		}

	}


	function get_backend_href() {
		switch($this->asset_type) {
			case 'page':
				$web_system = &$this->get_web_system();
				$asset = &$web_system->get_page($this->asset_id);
				return $asset->get_backend_href().'&PARAMETER_SCREEN=wizards&wizard_type=country_of_origin';
				break;
			case 'site':
				$web_system = &$this->get_web_system();
				$asset = &$web_system->get_site($this->asset_id);
				return $asset->get_backend_href().'&PARAMETER_SCREEN=wizards&wizard_type=country_of_origin';
				break;
		}
		
	}

}



function sort_countries($a,$b) {
	if ($a['hits'] == $b['hits']) return 0;
	return ($a['hits'] < $b['hits']);
}

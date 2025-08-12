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
## $Source: /home/cvsroot/xtras/wizards/most_downloaded/most_downloaded.inc,v $
## $Revision: 1.4 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Most Downloaded Wizard
* A wizard to produce a table of the most download file attachments
*
* @access public
* @package Wizards
*/
class Most_Downloaded extends Wizard {

	var $restrict_cond = '1=1';
	var $log_table = '';
	var $time_field   = 'hit_time';
	var $compatible_with = array('site','page');
	var $parameters = array('num_results'  => '10',
							'date' => '0000-00-00',
							'range' => 'month'
							  );


	 ##############################
	# Constructor
	function Most_Downloaded (&$asset) {	
		Wizard::Wizard($asset);
		switch($this->asset_type) {
			case 'site' :
				$this->log_table = 'log_file_hit LEFT JOIN file ON log_file_hit.fileid=file.fileid LEFT JOIN page ON file.pageid=page.pageid';
				$this->restrict_cond = "page.siteid='".$this->caller->id."'";
				$this->report_desc = "Most downloaded file attachments for site {$this->caller->name}";
				break;
			case 'page' :
				$this->log_table = 'log_file_hit LEFT JOIN file ON log_file_hit.fileid=file.fileid';
				$this->restrict_cond = "file.pageid='".$this->caller->id."'";
				$this->report_desc = "Most downloaded file attachments for page {$this->caller->name}";
				break;
		}
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
			
	
		$hit_info = $db->associative_array("SELECT file.fileid, COUNT(*) AS count FROM $this->log_table WHERE $this->restrict_cond AND $range_cond GROUP BY file.fileid ORDER BY count DESC LIMIT $num_results;");

		if (!count($hit_info)) {
			# no results found
			$backend->open_field('Sorry', 'one_liner');
			echo("There appear to be no records during $range_desc.");
		} else {

			$total_hits = $db->single_element("SELECT COUNT(*) FROM $this->log_table WHERE $this->restrict_cond AND $range_cond");


			$backend->open_section("$this->report_desc during $range_desc.");

			$backend->open_section('Summary');
			$backend->open_field('Total Hits','one_liner');
			echo number_format($total_hits);

			$backend->open_section('Tabulated Results');
			$web_system = &$this->get_web_system();
			global $EDIT_DIR;

			?>
			<table border="1" width="100%" bgcolor="#F0F0F0">
				<tr>
					<td align="center"><b>File ID</b></td>
					<td align="center"><b>Page ID</b></td>
					<td><b>File Name</b></td>
					<td align="center"><b>Size</b></td>
					<td align="center"><b>No. Of Downloads</b></td>
					<td align="center"><b>% Of Total Downloads</b></td>
				</tr>
			<?
				foreach ($hit_info as $fileid => $hits) {
					$file = $web_system->get_file($fileid);
					?><tr>
						<td align="center"><a href="#" onclick="Javascript:window.opener.location='<?=$EDIT_DIR?>/page.php?fileid=<?=$fileid?>&file_edit=1'; return false;"><?=$fileid?></a></td>
						<td align="center"><a href="#" onclick="Javascript:window.opener.location='<?=$EDIT_DIR?>/page.php?p=<?=$file->pageid?>'; return false;"><?=$file->pageid?></a></td>
						<td><a href="http://<?=$file->get_url()?>" target="_blank"><?=$file->filename?></a></td>
						<td align="center"><?=easy_filesize($file->size)?></td>
						<td align="center"><?=$hits?></td>
						<td align="center"><?=number_format(($hits / $total_hits) * 100,4)?>%</td>
					  </tr>
					<?
					}
			?></table><?

		}

	}

}
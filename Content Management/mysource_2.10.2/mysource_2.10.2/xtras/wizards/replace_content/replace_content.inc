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
## $Source: /home/cvsroot/xtras/wizards/replace_content/replace_content.inc,v $
## $Revision: 1.10 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:13 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Replace Content Wizard
* A wizard that will replace some text with some 
* other text for all standard pages or subpages
*
* @access public
* @package Wizards
*/
class Replace_Content extends Wizard {

	var $restrict_cond = '1=1';
	var $total_hit_cond = '1=1';
	var $report_desc = 'Most visited pages';
	var $log_table = '';
	var $time_field   = 'hit_time';
	var $compatible_with = array('site','page','web');
	var $parameters = array('num_replacements' => 6,
							'search' => array(1 => "<[/]?SMALL>",	
											  2 => "<[/]?FONT[^<>]*>",
											  3 => "<em>",
											  4 => "</em>",
											  5 => "<strong>",
											  6 => "</strong>"
											  ),
							'replace' => array(1 => '',  
											   2 => '',
											   3 => '<i>',
											   4 => '</i>',
											   5 => '<b>',
											   6 => '</b>'
											   ),
							  );


	 ##############################
	# Constructor
	function Replace_Content (&$asset) {	
		Wizard::Wizard($asset);
		$this->add_button('More','More_Replacements',0,1);
		$this->add_button('Less','Less_Replacements',0,1);
	}


	function process_special_action($action) {
		$active_step = $_REQUEST['active_step'];
		$backend = &$this->get_backend();
		if ($action == 'More_Replacements') {
			$this->parameters['num_replacements']++;
			$active_step = 0;
			$pset = &$this->get_pset();
			$pset->process_screen($backend,'1');
		} else if ($action == 'Less_Replacements') {
			if ($this->parameters['num_replacements'] > 1) $this->parameters['num_replacements']--;
			$active_step = 0;
			$pset = &$this->get_pset();
			$pset->process_screen($backend,'1');
		} else if ($action == 'Commit' && $active_step == 1) {
			$search = $_REQUEST['search'];
			$replace = $_REQUEST['replace'];
			foreach ($search as $id => $string) {
				if (trim($string) == '' && trim($replace[$id]) != '') {
					$active_step = 0;
					$backend->set_error("You need to enter something to search for in replacement $id."); 
					$pset = &$this->get_pset();
					$pset->process_screen($backend,'1');
					return;
				}
			}
		}
	}

	function backend_print_replacements() {
		$num_replacements = $this->parameters['num_replacements'];
		$search = $this->parameters['search'];
		$replace = $this->parameters['replace'];
		$case_sensitive = $this->parameters['case_sensitive'];

		$backend = &$this->get_backend();
		$backend->close_field();

		for ($i = 1; $i <= $num_replacements; $i++) {
			$backend->open_field($i,'one_liner');
			echo text_box("search[$i]",$search[$i],30);
			echo "&nbsp;";
			echo text_box("replace[$i]",$replace[$i],30);
			echo "&nbsp;";
			?><input type="checkbox" value="yes" name="case_sensitive[<?=$i?>]"<?=($case_sensitive[$i] == 'yes') ? ' checked' : ''?>> case sensitive?<?
		}

	}
	function backend_process_replacements() {
		$search = $_POST['search'];
		$replace = $_POST['replace'];
		$case_sensitive = $_POST['case_sensitive'];
		
		$this->parameters['search'] = array();
		$this->parameters['replace'] = array();
		$this->parameters['case_sensitive'] = array();
		
		foreach ($search as $id => $string) {
			$this->parameters['search'][$id] = trim($string);
			$this->parameters['replace'][$id] = $replace[$id];
			$this->parameters['case_sensitive'][$id] = $case_sensitive[$id];
		}
	}


	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {
		$web_system = &$this->get_web_system();

		if ($this->asset_type == 'web') {
			# we need to do this for all sites
			$sites = &$web_system->get_editable_sites();
			$page_index = array();
			foreach ($sites as $siteid => $name) {
				$site = $web_system->get_site($siteid);
				$page_index = array_merge($page_index,array_keys($site->get_page_index()));
			}
		} else if ($this->asset_type == 'page') {
			$page_index = &$this->caller->get_all_subpageids();
			array_push($page_index,(int)$this->caller->id);
		} else {
			$page_index = &$this->caller->get_page_index();
			$page_index = array_keys($page_index);
		}


		$search = $this->parameters['search'];
		$replace = $this->parameters['replace'];
		$case_sensitive = $this->parameters['case_sensitive'];

		# make the search strings regular expressions and case insensitive
		for (reset($search); ($k = key($search)) != null; next($search)) {
			if (trim($search[$k]) != '') {
				$search[$k] =  '|'.preg_replace("|([\?\[\]\(\)\.\^\|])|","\\\\\\1",$search[$k]).'|';
			}
			if ($case_sensitive[$k] != 'yes') $search[$k] .= 'i';
		}

		$page_count		= 0;
		$table_count	= 0;
		$row_count		= 0;
		$cell_count		= 0;
		$num_matched	= 0;


		foreach($page_index as $pageid){
			if ($pageid == 0) continue;

			$page = &$web_system->get_page($pageid);
			$template = &$page->get_template();
			$parameters = $template->parameters;
			if ($template->parameters) {
				$template->parameters = $this->recursive_bodycopy_finder($parameters, $search, $replace, $table_count, $row_count, $cell_count, $num_matched);
				$template->save_parameters();
			}
			$page_count++;
		}

		$backend->open_section('Summary');
		$backend->open_field('Standard Pages Scanned','one_liner');
		echo $page_count;
		$backend->open_field('Tables Scanned','one_liner');
		echo $table_count;
		$backend->open_field('Rows Scanned','one_liner');
		echo $row_count;
		$backend->open_field('Cells Scanned','one_liner');
		echo $cell_count;
		$backend->open_field('Replacements Made','one_liner');
		echo $num_matched;

	}

	function recursive_bodycopy_finder($data, $search, &$replace, &$table_count, &$row_count, &$cell_count, &$num_matched) {
		if (!isset($data)) return $data;
		if (get_class($data) == 'bodycopy') {
			# non serialised bodycopy so fix her up and put her back the way we found her
			$bodycopy = $data;
			$bodycopy = $this->change_bodycopy($bodycopy, $search, $replace, $table_count, $row_count, $cell_count, $num_matched);
			$data = $bodycopy;
		} elseif (substr($data,0,14) == 'O:8:"bodycopy"') {
			# serialised bodycopy so fix her up and put her back the way we found her
			$bodycopy = new BodyCopy($data, 'bodycopy');
			$bodycopy = $this->change_bodycopy($bodycopy, $search, $replace, $table_count, $row_count, $cell_count, $num_matched);
			$data = $bodycopy->pack();
		} elseif (is_array($data)) {
			$data_keys = array_keys($data);
			foreach($data_keys as $data_key) {
				$data[$data_key] = $this->recursive_bodycopy_finder($data[$data_key], $search, $replace, $table_count, $row_count, $cell_count, $num_matched);
			}
		}
		return $data;
	}

	function change_bodycopy($bodycopy, $search, $replace, &$table_count, &$row_count, &$cell_count, &$num_matched) {
		# Go into each bodycopy and retrieve html in cells
		foreach($bodycopy->tables as $tableid => $table){
			$table_count++;
			foreach($table->rows as $rowid => $row) {
				$row_count++;
				foreach($row->cells as $cellid => $cell) {
					$cell_count++;

					# we can only replace content in raw html,
					# rich text, wysiwyg, and iframe cells
					$type = get_class($cell->type);
					if ($type == 'bodycopy_table_cell_type_rawhtml' || 
						$type == 'bodycopy_table_cell_type_wysiwyg' ||
						$type == 'bodycopy_table_cell_type_richtext') {

						# get html
						$html = $cell->type->html;

						# replace html with changes
						foreach ($search as $s) {
							$num_matched += preg_match_all($s, $html, $matches);
						}

						$html = preg_replace($search, $replace, $html);

						# save html
						$bodycopy->tables[$tableid]->rows[$rowid]->cells[$cellid]->type->set_html($html);

					} elseif($type == 'bodycopy_table_cell_type_iframe') {

						# do replace in url
						foreach ($search as $x => $s) $num_matched += preg_match_all($s, $cell->type->url, $matches);
						$url = preg_replace($search, $replace, $cell->type->url);
						$bodycopy->tables[$tableid]->rows[$rowid]->cells[$cellid]->type->set_url($url);

					}
				}
			}
		}
		return $bodycopy;
	}
}
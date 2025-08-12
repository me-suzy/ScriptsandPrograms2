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
## $Source: /home/cvsroot/xtras/wizards/link_report/link_report.inc,v $
## $Revision: 1.7 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Link Report Wizard
* A wizard that will find links in the pages of the MySource system and generate a report on their status
*
* @access public
* @package Wizards
*/
class Link_Report extends Wizard {

	var $compatible_with = array('site','page','web');
	var $parameters = array('show_types' => array(	'site',	
													'page',
													'file',
													'external',
													'mailto')
							);

	 ##############################
	# Constructor
	function Link_Report (&$asset) {
		Wizard::Wizard($asset);
	}

	function record_page_link(&$links_found, &$page, $i, $pageid, $note='') {
		if (!in_array('page',$this->parameters['show_types'])) return;
		$web_system = &$this->get_web_system();

		$from_url = $page->get_url();

		# get the page we are linking to
		$to_page = &$web_system->get_page($pageid);

		if (!$to_page->id) $to_url = 'Unknown page '.$pageid;
		else $to_url = $to_page->get_url();

		if (!isset($links_found['Page'][$from_url][$to_url])) {
			$links_found['Page'][$from_url][$to_url] = array();
			$links_found['Page'][$from_url][$to_url]['count'] = 0;

			$links_found['Lookup']['Page'][$from_url]['status'] = $page->effective_status();
			if ($note) $links_found['Page'][$from_url][$to_url]['note'] = $note.' ';

			# if the page doesnt have an ID - it doesnt exist
			# otherwise, get the effective status of the page
			if (!$to_page->id) {
				$links_found['Lookup']['Page'][$to_url]['status'] = 'DEL';
				$links_found['Page'][$from_url][$to_url]['note'] .= 'The page being linked to cannot be found. ';
			} else {
				$links_found['Lookup']['Page'][$to_url]['status'] = $to_page->effective_status();
			}
		}
		$links_found['Page'][$from_url][$to_url]['count']++;
	}


	function record_site_link(&$links_found, &$page, $i, $siteid, $note='') {
		if (!in_array('site',$this->parameters['show_types'])) return;
		$web_system = &$this->get_web_system();

		$from_url = $page->get_url();

		# get the page we are linking to
		$to_site = &$web_system->get_site($siteid);

		if (!$to_site->id) $to_url = 'Unknown site '.$siteid;
		else if (!count($to_site->urls)) $to_url = $to_site->get_url();
		else $to_url = $to_site->get_url();
		
		if (!isset($links_found['Site'][$from_url][$to_url])) {
			$links_found['Site'][$from_url][$to_url] = array();
			$links_found['Site'][$from_url][$to_url]['count'] = 0;

			$links_found['Lookup']['Site']['status'] = $page->effective_status();
			if ($note) $links_found['Site'][$from_url][$to_url]['note'] = $note.' ';

			# if the site doesnt have an ID - it doesnt exist
			# otherwise, get the effective status of the index page
			if (!$to_site->id) {
				$links_found['Lookup']['Site'][$to_url]['status'] = 'DEL';
				$links_found['Site'][$from_url][$to_url]['note'] .= 'The site being linked to cannot be found. ';
			} else if (!count($to_site->urls)) {
				$links_found['Lookup']['Site'][$to_url]['status'] = 'U';
				$links_found['Site'][$from_url][$to_url]['note'] .= 'The site being linked to has no URLs and will not be displayed on the frontend. ';
			} else {
				$to_page = $web_system->get_page($site->index_pageid);
				if (!$to_page->id) {
					$links_found['Lookup']['Site'][$to_url]['status'] = 'DEL';
					$links_found['Site'][$from_url][$to_url]['note'] .= 'The site being linked to does not have an index page. ';
				} else {
					$links_found['Lookup']['Site'][$to_url]['status'] = $to_page->effective_status();
				}
			}
		}
		$links_found['Site'][$from_url][$to_url]['count']++;
	}


	function record_file_link(&$links_found, &$page, $i, $fileid, $note='', $type) {
		if (!in_array('file',$this->parameters['show_types'])) return;
		$web_system = &$this->get_web_system();

		$from_url = $page->get_url();
		
		# get the file we are linking to
		$to_file = &$web_system->get_file($fileid);

		if (!$to_file->id) {
			$to_url = 'Unknown file '.$fileid;
			if (strtolower($type) == 'src') $to_url .= ' - embeded file';
		} else {
			$to_url = $to_file->get_url();
		}

		if (!isset($links_found['File'][$from_url][$to_url])) {
			$links_found['File'][$from_url][$to_url] = array();
			$links_found['File'][$from_url][$to_url]['count'] = 0;

			$links_found['Lookup']['File'][$from_url]['status'] = $page->effective_status();
			if ($note) $links_found['File'][$from_url][$to_url]['note'] = $note.' ';

			# if the file doesnt have an ID - it doesnt exist
			# otherwise the status is Live
			if (!$to_file->id) {
				$links_found['Lookup']['File'][$to_url]['status'] = 'DEL';
				$links_found['File'][$from_url][$to_url]['note'] .= 'The file being linked to cannot be found. ';
			} else {
				$links_found['Lookup']['File'][$to_url]['status'] = 'L';
			}
		}
		$links_found['File'][$from_url][$to_url]['count']++;
	}


	function record_url_link(&$links_found, &$page, $i, $url, $note='') {
		if (!in_array('external',$this->parameters['show_types'])) return;

		# ignore bookmarks
		if (substr($url,0,1) == '#') return;

		$from_url = $page->get_url();
		$to_url = $url;

		if (!isset($links_found['External_URL'][$from_url][$to_url])) {
			$links_found['Lookup']['External_URL'][$from_url]['status'] = $page->effective_status();
			if ($note) $links_found['External URL'][$from_url][$to_url]['note'] = $note.' ';
			
			# check for some know URL problems
			if (strpos('http://http://', $url) !== false || 
				strpos('http:///', $url) !== false ||
				!ereg("^(http[s]?|ftp)\:\/\/",$url)) {
				$links_found['External_URL'][$from_url][$to_url]['note'] .= 'The URL does not appear to be valid. ';
				$links_found['Lookup']['External_URL'][$to_url]['status'] = 'DEL';
			} else {
				$links_found['Lookup']['External_URL'][$to_url]['status'] = 'NONE';
			}
		}
		$links_found['External_URL'][$from_url][$to_url]['count']++;
	}


	function record_mailto_link(&$links_found, &$page, $i, $mailto, $note='') {
		if (!in_array('mailto',$this->parameters['show_types'])) return;

		$mailto = preg_replace('/mailto:/i','',$mailto);
		list($mailto) = explode('?',$mailto);
		$from_url = $page->get_url();
		$to_url = trim($mailto);
		
		if (!isset($links_found['Mailto'][$from_url][$to_url])) {
			$links_found['Lookup']['Mailto'][$from_url]['status'] = $page->effective_status();
			if ($note) $links_found['Mailto'][$i]['note'] = $note.' ';
			if (!valid_email($to_url)) {
				$links_found['Mailto'][$from_url][$to_url]['note'] .= 'The email address does not appear to be valid. ';
			}
			$links_found['Lookup']['Mailto'][$to_url]['status'] = 'NONE';
		}
		$links_found['Mailto'][$from_url][$to_url]['count']++;
	}


	function process_special_action($action) {
		if ($action == 'Parse' || $_REQUEST['active_step'] == 1) {
			$this->do_link_lookup();
		} else if ($action == 'Lookup' || $_REQUEST['active_step'] == 2) {
			$this->do_url_lookup();
		} else if ($action == 'Parse_finished') {
			if ($this->parameters['check_urls']) {
				echo status_popup('100',$this->get_backend_href().'&active_step=2',false,true,'#330099','Processing Complete','');
			} else {
				echo status_popup('100',$this->get_backend_href().'&active_step=100',false,true,'#330099','Processing Complete','');
			}
			exit();
		} else if ($action == 'Lookup_finished') {
			echo status_popup('100',$this->get_backend_href().'&active_step=100',false,true,'#330099','Lookup Complete','');
			exit();
		}
	}


	function do_link_lookup() {
		
		$num_to_lookup = $_GET['num_to_lookup'];
		$num = $_GET['num'];
		$num_looked = $_GET['num_looked'];
		$started = $_GET['started'];
		$start_time = $_GET['start_time'];
		$action = $_GET['action'];
		$session = &get_mysource_session();
		set_time_limit(0);

		$web_system = &$this->get_web_system();

		if (isset($num) && isset($num_to_lookup)) {

			if (!$started) {
				$now = time();
				$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=0&action=Parse&started=1&start_time=$now";
				echo status_popup(1,$process_url,false,false,'#330099','Parsing Page Content - Please wait', "Starting to process $num_to_lookup pages");
				exit();
			}

			global $page_count, $table_count, $row_count, $cell_count, $Page_link_count, $Site_link_count, $File_link_count, $External_URL_link_count, $Mailto_link_count, $links_found, $page_index;

			$page_count = $session->get_var('page_count');
			$table_count = $session->get_var('table_count');
			$row_count = $session->get_var('row_count');
			$cell_count = $session->get_var('cell_count');
			$Page_link_count = $session->get_var('Page_link_count');
			$Site_link_count = $session->get_var('Site_link_count');
			$File_link_count = $session->get_var('File_link_count');
			$External_URL_link_count = $session->get_var('External_URL_link_count');
			$Mailto_link_count = $session->get_var('Mailto_link_count');
			$links_found = $session->get_var('links_found');
			$page_index = $session->get_var('page_index');

			$db = &$this->get_db();

			# process $num pages
			$i = 0;
			foreach($page_index as $pageid){
				$i++;
				if ($i > $num) break;

				if ($pageid == 0) continue;

				$page = &$web_system->get_page($pageid);

				$template = &$page->get_template();
				$page_array = $db->associative_row("SELECT * FROM $template->table_name WHERE pageid='$page->id'");

				foreach($page_array as $key => $value) {
					# okay, it's a parameter set. Unserialize and have a look at what's in there.
					if($key == 'parameters') { 
						# make a reference and work with that
						$value = &$page_array[$key];
						$value = unserialize($value);
						foreach($value as $k2=>$v2) {
							# if we have a copy array inside the parameters its likely we've got some bodycopies 
							if($k2 == 'copy') {
								# loop through and try to find 'em.
								foreach($v2 as $k3 => $v3) {
									if(substr($v3,0,14) == 'O:8:"bodycopy"') {
										$bodycopy = new BodyCopy($page_array[$key][$k2][$k3], 'bodycopy');
										$this->lookup_links($bodycopy,$page);
									}
								}
							} 
							# otherwise see if its a bodycopy anyway, we might not have put them in the copy array
							if(substr($v2,0,14) == 'O:8:"bodycopy"') {
								$bodycopy = new BodyCopy($page_array[$key][$k2], 'bodycopy');
								$this->lookup_links($bodycopy,$page);
							}
						}
						$value = serialize($value);
					}
					if (is_int($key) || $key == "pageid") continue;
					if(substr($value,0,14) == 'O:8:"bodycopy"') {
						$bodycopy = new BodyCopy($value, 'bodycopy');
						$this->lookup_links($bodycopy,$page);
					}
				}
				$page_count++;
				
			}

			$page_index = array_slice($page_index,$num);

			$session->set_var('page_count',$page_count);
			$session->set_var('table_count',$table_count);
			$session->set_var('row_count',$row_count);
			$session->set_var('cell_count',$cell_count);
			$session->set_var('Page_link_count',$Page_link_count);
			$session->set_var('Site_link_count',$Site_link_count);
			$session->set_var('File_link_count',$File_link_count);
			$session->set_var('External_URL_link_count',$External_URL_link_count);
			$session->set_var('Mailto_link_count',$Mailto_link_count);
			$session->set_var('links_found',$links_found);
			$session->set_var('page_index',$page_index);


			$num_looked += $num;
			$percent = ceil(($num_looked / $num_to_lookup) * 100);
			$finish = (($num_looked >= $num_to_lookup) ? 1 : 0);
			$time_diff = time() - $start_time;
			$time_per_lookup = $time_diff / $num_looked;
			$time_left = $time_per_lookup * ($num_to_lookup - $num_looked);
			if ($time_left <= 0) $time_left = 1;
			$status = '';

			if ($finish) {
				$process_url = $this->get_backend_href()."&action=Parse_finished&started=1";
				$status = 'Finalising Report...';
				$percent = 100;
			} else {
				$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=$num_looked&action=Parse&started=1&start_time=$start_time";
				$status = "Completed $num_looked pages - ".($num_to_lookup - $num_looked).' remaining - est time: '.easy_time_total($time_left);
			}
			echo status_popup($percent,$process_url,false,false,'#330099','Parsing Page Content - Please wait',$status);
			exit();
		}

		$links_found = array();
		$page_count = 0;
		$table_count = 0;
		$row_count = 0;
		$cell_count = 0;
		$Page_link_count = 0;
		$Site_link_count = 0;
		$File_link_count = 0;
		$External_URL_link_count = 0;
		$Mailto_link_count = 0;

		$session->set_var('page_count',$page_count);
		$session->set_var('table_count',$table_count);
		$session->set_var('row_count',$row_count);
		$session->set_var('cell_count',$cell_count);
		$session->set_var('Page_link_count',$Page_link_count);
		$session->set_var('Site_link_count',$Site_link_count);
		$session->set_var('File_link_count',$File_link_count);
		$session->set_var('External_URL_link_count',$External_URL_link_count);
		$session->set_var('Mailto_link_count',$Mailto_link_count);
		$session->set_var('links_found',$links_found);

		if ($this->asset_type == 'web') {
			# we need to do this for all sites
			$sites = $web_system->get_editable_sites();
			$page_index = array();
			foreach ($sites as $siteid => $name) {
				$site = $web_system->get_site($siteid);
				$page_index = array_merge($page_index,array_keys($site->get_page_index()));
			}
		} else if ($this->asset_type == 'page') {
			$page_index = $this->caller->get_all_subpageids();
			array_push($page_index,(int)$this->caller->id);
		} else {
			$page_index = $this->caller->get_page_index();
			$page_index = array_keys($page_index);
		}

		$session->set_var('page_index',$page_index);

		$num_to_lookup = count($page_index);
		$num = 10; # number of lookups to process at a time
		$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=0&action=Parse&started=0";
		echo status_popup(1,$process_url,true,false,'#330099','Parsing Page Content - Please wait', "Starting to process $num_to_lookup pages");
	}


	function lookup_links(&$bodycopy, &$page) {
		global $page_count, $table_count, $row_count, $cell_count, $Page_link_count, $Site_link_count, $File_link_count, $External_URL_link_count, $Mailto_link_count, $links_found, $page_index;

		# Go into each bodycopy and retrieve html in cells
		foreach($bodycopy->tables as $tableid => $table){
			$table_count++;
			foreach($table->rows as $rowid => $row) {
				$row_count++;
				foreach($row->cells as $cellid => $cell) {
					$cell_count++;

					$type = get_class($cell->type);
					if ($type == 'bodycopy_table_cell_type_rawhtml' || 
						$type == 'bodycopy_table_cell_type_wysiwyg' ||
						$type == 'bodycopy_table_cell_type_richtext') {

						# get html
						$html = $cell->type->html;

						preg_match_all('%(href|src)=[\'"]([^\'\s"]*)%', $html, $master_matches);
						$matches = $master_matches[2];
						$x = 0;
						foreach ($matches as $match) {
							$match_type = $master_matches[1][$x];
							$x++;
							if (!$match) continue;

							preg_match('%[\.]?/\?([spf])=([0-9]+)%',$match, $results);
							if ($match == '.'.$results[0]) {
								$note = 'The link has an extra \\\'.\\\' before ./?'.$results[1].'='.$results[2].' resulting in a possible invalid URL - \\\''.$match.'\\\'.';
							} else {
								$note = '';
							}
							switch($results[1]) {
								case 'p' :
									$this->record_page_link($links_found, $page, $Page_link_count, $results[2], $note);
									$Page_link_count++;
									break;
								case 's' :
									$this->record_site_link($links_found, $page, $Site_link_count, $results[2], $note);
									$Site_link_count++;
									break;
								case 'f' :
									$this->record_file_link($links_found, $page, $File_link_count, $results[2], $note, $match_type);
									$File_link_count++;
									break;
								default  :
									preg_match('%(.*)?([spf])=([0-9])+%',$match, $results);

									# a default note
									$note = 'This link does not appear to be in the correct format. The text \\\''.substr($results[1],0,-1).'\\\' has been placed before the link. ';

									switch($results[2]) {
										case 'p' :
											$this->record_page_link($links_found, $page, $Page_link_count, $results[3], $note);
											$Page_link_count++;
											break;
										case 's' :
											$this->record_site_link($links_found, $page, $Site_link_count, $results[3], $note);
											$Site_link_count++;
											break;
										case 'f' :
											$this->record_file_link($links_found, $page, $File_link_count, $results[3], $note, $match_type);
											$File_link_count++;
											break;
										default  :
											
											if (strpos(strtolower($match),'mailto') === 0) {
												$this->record_mailto_link($links_found, $page, $Mailto_link_count, $match, '');
												$Mailto_link_count++;
											} else {
												$this->record_url_link($links_found, $page, $External_URL_link_count, $match, '');
												$External_URL_link_count++;
											}
									}
							}

						}
					} else {
						continue;
					}
				}
			}
		}
	}

	function do_url_lookup() {
		
		$num_to_lookup = $_GET['num_to_lookup'];
		$num = $_GET['num'];
		$num_looked = $_GET['num_looked'];
		$started = $_GET['started'];
		$start_time = $_GET['start_time'];
		$action = $_GET['action'];
		$session = &get_mysource_session();
		$links_found = $session->get_var('links_found');
		set_time_limit(0);

		if (isset($num) && isset($num_to_lookup)) {

			if (!$started) {
				$now = time();
				$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=0&action=Lookup&started=1&start_time=$now";
				echo status_popup(1,$process_url,false,false,'#330099','Looking up URLs - Please wait', "Starting to lookup $num_to_lookup URLs");
				exit();
			}

			$tested_urls = $session->get_var('tested_urls');

			# lookup $num URLs
			$i = 0;
			$k = -1;
			foreach ($links_found['External_URL'] as $from_url => $to_urls) {
				foreach ($to_urls as $url => $url_data) {
					$k++;
					if ($k < $num_looked) continue;

					if ($i >= $num) break;
					$i++;
					
					if ($links_found['Lookup']['External_URL'][$url]['status'] != 'NONE') continue;
					$url = trim($url);
					if ($url == '') continue;

					$error = false;
					if ($tested_urls[$url] == 'FAIL') {
						$error = true;
					} else if ($tested_urls[$url] == 'PASS') {
						$error = false;
					} else if (!fopen(urlencode($url),'r')) {
						$error = true;
					}

					if ($error) {
						$links_found['External_URL'][$from_url][$url]['note'] .= 'The URL returned a 404 Error (Page Not Found)';
						$links_found['Lookup']['External_URL'][$url]['status'] = 'DEL';
						$tested_urls[$url] = 'FAIL';
					} else {
						$links_found['Lookup']['External_URL'][$url]['status'] = 'L';
						$tested_urls[$url] = 'PASS';
					}
				}
			}

			$session->set_var('links_found',$links_found);
			$session->set_var('tested_urls',$tested_urls);

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
				$status = 'Finalising Report...';
				$percent = 100;
			} else {
				$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=$num_looked&action=Lookup&started=1&start_time=$start_time";
				$status = "Completed $num_looked URLs - ".($num_to_lookup - $num_looked).' remaining - est time: '.easy_time_total($time_left);
			}
			echo status_popup($percent,$process_url,false,false,'#330099','Looking up URLs - Please wait',$status);
			exit();
		}

		$tested_urls = array();
		$session->set_var('tested_urls',$tested_urls);

		
		$num_to_lookup = 0;
		foreach ($links_found['External_URL'] as $from_url => $to_urls) $num_to_lookup += count($to_urls);

		$num = 1; # number of lookups to process at a time
		$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=0&action=Lookup&started=0";
		echo status_popup(1,$process_url,true,false,'#330099','Looking up URLs - Please wait', "Starting to lookup $num_to_lookup URLs");
	}


	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {

		$web_system = &$this->get_web_system();
		$backend->enable_tooltips();
		$session = &get_mysource_session();

		$page_count = $session->get_var('page_count');
		$table_count = $session->get_var('table_count');
		$row_count = $session->get_var('row_count');
		$cell_count = $session->get_var('cell_count');
		$Page_link_count = $session->get_var('Page_link_count');
		$Site_link_count = $session->get_var('Site_link_count');
		$File_link_count = $session->get_var('File_link_count');
		$External_URL_link_count = $session->get_var('External_URL_link_count');
		$Mailto_link_count = $session->get_var('Mailto_link_count');
		$links_found = $session->get_var('links_found');
		
		$backend->open_section('Summary');
		$backend->open_field('Standard Pages Scanned','one_liner');
		echo $page_count;
		$backend->open_field('Tables Scanned','one_liner');
		echo $table_count;
		$backend->open_field('Rows Scanned','one_liner');
		echo $row_count;
		$backend->open_field('Cells Scanned','one_liner');
		echo $cell_count;
		$backend->open_section('Report');
		$backend->open_field('');

		global $PAGE_STATUS_COLOUR;

		if (!count($links_found)) {
			echo 'No links were found on the selected pages.';
		} else {

			?>
			<table border="1" bordercolor="#7F7F7F" cellspacing="0" cellpadding="4">
				<tr>
					<td>&nbsp;</td>
					<td bgcolor="#C0C0C0" align="center"><b>Link From</b></td>
					<td bgcolor="#C0C0C0" align="center"><b>Link To</b></td>
					<td>&nbsp;</td>
				</tr>
			<?

			foreach ($links_found as $link_type => $data) {
				if ($link_type == 'Lookup') continue;
				$link_count = $link_type.'_link_count';
				$first_time = true;

				$row_span = 0;
				foreach ($data as $from_url => $to_urls) $row_span += count($to_urls);


				foreach ($data as $from_url => $to_urls) {

					$from_status = $links_found['Lookup'][$link_type][$from_url]['status'];
					
					if ($from_status == 'DEL') {
						$frombg = 'bgcolor="#FF6C4A"';
					} else if ($from_status == 'NONE') {
						$frombg = '';
					} else {
						$frombg = 'background="images/page_visible_'.$PAGE_STATUS_COLOUR[$from_status].'.gif"';
					}

					?>
					<tr>
						<?
						if ($first_time) {
							?><td style="font-size:10px" align="center" valign="top" nowrap bgcolor="#C0C0C0" rowspan="<?=$row_span?>"><b><?=str_replace('_', ' ', $link_type).'s<br>'.$$link_count.' found'?></b></td><?
							$first_time = false;
						}
						?>
						<td <?=$frombg?>" style="font-size:10px" rowspan="<?=count($to_urls)?>"  valign="top">
						<?
						if ($from_url) {
							if ($from_status != 'DEL') echo '<a href="'.$from_url.'" target="_blank"  style="color:black">';
							echo $this->wrap_string($from_url);
							if ($from_status != 'DEL') echo '</a>';
						} else {
							echo '&nbsp;';
						}
						?>
						</td>
					<?
					
					$done_to_url = false;
					foreach($to_urls as $to_url => $url_data) {
					
						$to_status = $links_found['Lookup'][$link_type][$to_url]['status'];
						
						if ($to_status == 'DEL') {
							$tobg = 'bgcolor="#FF6C4A"';
						} else if ($to_status == 'NONE') {
							$tobg = '';
						} else {
							$tobg = 'background="images/page_visible_'.$PAGE_STATUS_COLOUR[$to_status].'.gif"';
						}

						if ($done_to_url) echo '<tr>';
						?>
							<td <?=$tobg?>" style="font-size:10px"  valign="top">
							<?
							if ($to_url) {
								if ($to_status != 'DEL') echo '<a href="'.(($link_type == 'Mailto') ? 'mailto:' : '').$to_url.'" target="_blank"  style="color:black">';
								echo $this->trim_string($to_url);
								if ($to_status != 'DEL') echo '</a>';
							} else {
								echo '&nbsp;';
							}
							?>
							</td>
							<td valign="top"><?
								if ($url_data['note']) { 
									?><a href="#" onmouseover="show_tooltip(event,'Warning','<?=$url_data['note']?>',300); return false;" onmouseout="hide_tooltip();"><img src="<?=$backend->rel_path?>/images/icons/warning.gif" border="0"></a><?
							} else {
								echo '&nbsp;';
							}
							?>
							</td>
						</tr>
						<?
						$done_to_url = true;
					}
				}
			}

			?></table><?
		}

	}


	function get_backend_href() {
		switch($this->asset_type) {
			case 'page':
				$web_system = &$this->get_web_system();
				$asset = &$web_system->get_page($this->asset_id);
				return $asset->get_backend_href().'&PARAMETER_SCREEN=wizards&wizard_type=link_report';
				break;
			case 'site':
				$web_system = &$this->get_web_system();
				$asset = &$web_system->get_site($this->asset_id);
				return $asset->get_backend_href().'&PARAMETER_SCREEN=wizards&wizard_type=link_report';
				break;
			case 'web':
				$web_system = &$this->get_web_system();
				return $web_system->get_backend_href().'&web_section=wizards&wizard_type=link_report';
				break;
		}
		
	}


	function trim_string($string='', $maxlen=50) {
		$strlen = strlen($string);
		if ($strlen <= $maxlen) return $string;
		return '..'.substr($string,($strlen - $maxlen));
	}

	function wrap_string($string='', $maxlen=50) {
		$strlen = strlen($string);
		if ($strlen <= $maxlen) return $string;
		$retval = '';
		while (trim($string) != '') {
			$strlen = strlen($string);
			if (trim($retval) != '') $retval .= "<br>";
			$retval .= substr($string,0,$maxlen);
			$string = substr($string,($maxlen+1));
		}
		return $retval;
	}

}
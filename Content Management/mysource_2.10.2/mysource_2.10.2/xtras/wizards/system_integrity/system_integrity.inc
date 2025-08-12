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
## Desc: A wizard that updates all the virtual paths
## $Source: /home/cvsroot/xtras/wizards/system_integrity/system_integrity.inc,v $
## $Revision: 1.13 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:13 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* System Integrity Wizard
* A wizard that checks system integrity.
* Checks page status' & directory structure and meta data for pages.
*
* @access public
* @package Wizards
*/
class System_integrity extends Wizard {

	/**
	* The description of the wizard
	* @var string
	*/
	var $report_desc = 'System Integrity Checks';

	/**
	* An array of levels that the wizard is compatible with
	* @var array
	*/
	var $compatible_with = array('site','page','web');

	/**
	* An array of default parameter set options for the wizard
	* @var array
	*/
	var $parameters = array('check_types' =>
								array(	'page',
										'metadata'
								)
							);

	/**
	* Constructor
	* @param	object &$asset This is the current asset
	* @returns	object System_integrity
	* @access	public
	*/
	function System_integrity (&$asset) {
		ini_set('track_errors', true);
		Wizard::Wizard($asset);
	}


	/**
	* process_wizard
	* This displays the results of the wizard.
	* Lists all invalid data depending on what you chose at the start.
	* Reports whether anything could be fixed, what it had to do.
	* Will also display what couldn't be fixed.
	* @param	object &$backend The backend the wizard created previously.
	* @returns	void
	* @access	private
	*/

	function process_wizard_web(&$backend) {

		$web_system = &$this->get_web_system();
		$backend->enable_tooltips();
		$session = &get_mysource_session();

		$backend->open_section('Summary');

		$backend->open_section('Report');
		$backend->open_field('');

		$valid_results		= &$session->get_var(get_class($this)."_valid");
		$invalid_results	= &$session->get_var(get_class($this)."_invalid");

		$fixed_results		= &$session->get_var(get_class($this)."_fixed");
		$notfixed_results	= &$session->get_var(get_class($this)."_notfixed");

		if (in_array('page', $this->parameters['check_types'])) {

			$backend->open_field('Pages Checked');

			?>
				<table width="100%">
			<?

			if (!empty($invalid_results['page'])) {
				ksort($invalid_results['page']);

				global $SYSTEM_ROOT;

				foreach($invalid_results['page'] as $pid => $data) {
					$page		= &$web_system->get_page($pid);
					$fixed		= $fixed_results['page'][$pid];
					$notfixed	= $notfixed_results['page'][$pid];
					$msg = ($fixed) ? $fixed : $notfixed;
					$col = ($fixed) ? $backend->WARNING_COLOUR : $backend->ERROR_COLOUR;
					?>
					<tr>
						<td colspan="2">
							<a href="<?=$page->get_backend_href(); ?>" target="_blank"><?=$page->name; ?></a> had a problem. <?=($fixed) ? 'It was fixed.' : 'It was not fixed.'; ?> ( <?=$page->get_url(); ?> )
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
						<td>
							<span style="color:<?=$col;?>;"><?=$msg?></span>
						</td>
					</tr>
					<?

					$web_system->forget_page($pid);
					unset($fixed);
					unset($notfixed);
					unset($page);
				}
			} else {
				echo '<tr><td>Page structure ok.</td></tr>';
			}
			?></table><?
		}

		if (in_array('metadata', $this->parameters['check_types'])) {

			$backend->open_field('Page Metadata Checked');

			?><table width="100%"><?

			if (!empty($invalid_results['metadata'])) {
				ksort($invalid_results['metadata']);
				foreach($invalid_results['metadata'] as $pid => $data) {
					$page = &$web_system->get_page($pid);
					?>
					<tr>
						<td colspan="2">
							<a href="<?=$page->get_backend_href(); ?>" target="_blank"><?=$page->name; ?></a> ( <?=$page->get_url(); ?> )
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
						<td>
							<?
							if (!empty($data['keywords']) && !empty($data['description'])) {
								?>No keywords or description.<br /><?
							}

							if (!empty($data['keywords']) && empty($data['description'])) {
								?>Has no keywords.<br /><?
							}

							if (empty($data['keywords']) && !empty($data['description'])) {
								?>Has no description.<br /><?
							}
							?>
						</td>
					</tr>
					<?
					$web_system->forget_page($pid);
					unset($page);
				}
			} else {
				echo '<tr><td>Meta data ok.</td></tr>';
			}
			?></table><?
		}
	}

	/**
	* get_backend_href
	* Returns the backend HREF depending on the parent asset type. The asset type is worked out by the parent.
	* @returns	string
	* @access	public
	*/
	function get_backend_href() {
		switch($this->asset_type) {
			case 'page':
				$web_system = &$this->get_web_system();
				$asset = &$web_system->get_page($this->asset_id);
				return $asset->get_backend_href().'&PARAMETER_SCREEN=wizards&wizard_type=system_integrity';
				break;
			case 'site':
				$web_system = &$this->get_web_system();
				$asset = &$web_system->get_site($this->asset_id);
				return $asset->get_backend_href().'&PARAMETER_SCREEN=wizards&wizard_type=system_integrity';
				break;
			case 'web':
				$web_system = &$this->get_web_system();
				return $web_system->get_backend_href().'&web_section=wizards&wizard_type=system_integrity';
				break;
		}

	}

	/**
	* process_special_action
	* Processes the action and sets the popups depending on what step it is up to.
	* @param	string $action The current action we need to process.
	* @returns	void
	* @access	private
	*/
	function process_special_action($action='page') {

		if ($_REQUEST['active_step'] == 1) {
			/*
				Reset all the session variables before we do anything!
			*/
			$session = &get_mysource_session();
			$session->unset_var(get_class($this)."_todo");

			$session->unset_var(get_class($this)."_valid");
			$session->unset_var(get_class($this)."_invalid");

			$session->unset_var(get_class($this)."_fixed");
			$session->unset_var(get_class($this)."_notfixed");

			$empty = array(
				'page'		=> array(),
				'metadata'	=> array()
			);

			$session->set_var(get_class($this)."_valid",    $empty);
			$session->set_var(get_class($this)."_invalid",  $empty);

			$session->set_var(get_class($this)."_fixed",    $empty);
			$session->set_var(get_class($this)."_notfixed", $empty);

			$todo = $_REQUEST['check_types'];
			$action = array_shift($todo);

			$session->set_var(get_class($this)."_todo", $todo);

		}

		switch($action) {
			case 'page':
			case 'metadata':
				if (in_array($action, $this->parameters['check_types'])) {
					$this->_check_integrity($action);
				}
			break;

			/*
				These actions need to be in the same order as the '$empty' array above
				and set the active_step appropriately.
				Also, need the check before setting the active_step because of the way it falls through the switch
				If we didn't (do the check), it would always be set to the last statement.
			*/
			case 'page_finished':
				if (!$active_step) $active_step = 2;
			case 'metadata_finished':
				if (!$active_step) $active_step = 3;
				echo status_popup('100',$this->get_backend_href().'&active_step='.$active_step.$this->get_next_action($action),false,true,'#330099','Lookup Complete','');
				exit();
			break;

			case 'integrity_finished':

				/*
					Let's print the wizard "backend" ourselves.

				*/
				$backend = &$this->get_backend();
				$backend->add_button($this->button_names['start'],'document.edit.action.value=\'\';document.edit.wizard_start.value=1;document.edit.active_step.value=\'\';document.edit.submit()');
				$backend->add_button($this->button_names['done'],'if (confirm(\'Are you sure you want to close this wizard?\')) { window.close(); }');
				$backend->print_header();
				$this->process_wizard($backend);
				$backend->print_commit_button();
				$backend->print_footer();
				exit();
			break;
		}
	}

	/**
	* get_next_action
	* Retrieves the next action from the "todo" list based on the current_action. Works in the same order as the list this->parameters['check_types']
	* @param	string $current_action The current action. From this, we can work out what the next action should be.
	* @returns	string
	* @access	private
	*/
	function get_next_action($current_action='page') {
		# get the next action and see if that works.
		$current_action = str_replace("_finished", "", $current_action);

		$session = &get_mysource_session();
		$todo_items = &$session->get_var(get_class($this)."_todo");

		$next_action = array_shift($todo_items);

		$session->set_var(get_class($this)."_todo", $todo_items);

		if ($next_action) {
			return '&action='.$next_action;
		} else {
			return '&action=integrity_finished';
		}
	}

	/**
	* get_pageid_childfirst
	* Gets the pageid's for the tree from the bottom up. We need this to fix the bottom pages first.
	* @param	int $siteid The site we are collecting pageid's for.
	* @param	int $pageid The pageid to search for children of.
	* @returns	array
	* @access	private
	*/
	function get_pageid_childfirst($siteid = 0, $pageid = 0) {
		static $page_order = array();
		$web =& get_web_system();
		$site = &$web->get_site($siteid);
		$page_index = $site->get_page_index($siteid);
		$childids = $page_index[$pageid]['childids'];
		# If we have children unshift them on our list
		if (!empty($childids)) {
			foreach ($childids as $childid) {
				$this->get_pageid_childfirst($siteid, $childid);
			}
			if ($pageid > 0) {
				array_push($page_order, $pageid);
			}
		} else {
			array_push($page_order, $pageid);
		}
		return $page_order;
	} // end function

	/**
	* _check_integrity
	* Depending on the integrity check, does various things with pages.
	* Depending on the step you are currently in, the integrity_type check does:
	* "page"		- Makes sure each page has a valid page status and directory structure is ok.
	* "metadata"	- Makes sure each page has keyword and description for metadata.
	* Also does the progress bar for the pop-up status bar.
	* @param	string $integrity_type The integrity type to check.
	* @returns	void
	* @access	private
	*/
	function _check_integrity($integrity_type='page') {
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
				$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=0&action=".$integrity_type."&started=1&start_time=$now";
				echo status_popup(1,$process_url,false,false,'#330099','Checking System Integrity - Please wait', "Starting to process $num_to_lookup pages");
				exit();
			}

			$page_count = $session->get_var('page_count');
			$page_index = $session->get_var('page_index');

			$db = &$this->get_db();

			# process $num pages
			$i = 0;

			$valid    = &$session->get_var(get_class($this).'_valid');
			$invalid  = &$session->get_var(get_class($this).'_invalid');

			$fixed    = &$session->get_var(get_class($this).'_fixed');
			$notfixed = &$session->get_var(get_class($this).'_notfixed');

			foreach($page_index as $pageid){
				$i++;
				if ($i > $num) break;

				if ($pageid == 0) continue;
				$page = &$web_system->get_page($pageid);
				$page->clear_cache();

				switch($integrity_type) {
					case 'page':

						global $DATA_PATH;

						# Grab the info from the page.
						$effective_status = $page->effective_status();
						$public = $page->effective_public();
						$page_status = $page->status();
						$live = $page->effective_unrestricted();

						if (!$page_status) {

							# First mark it as "invalid".
							$invalid[$integrity_type][$page->id] = 1;

							# Set the default new status to be Under Construction.
							$newstatus = 'U';

							$unrestricted_dpath	= $DATA_PATH . "/unrestricted/page/" . $page->id;
							$restricted_dpath	= $DATA_PATH . "/restricted/page/" . $page->id;

							if ($effective_status == 'L') {
								$newstatus = 'L';
								$dpath = $unrestricted_dpath;
								$opposite_dpath = $restricted_dpath;
							} else {
								$dpath = $restricted_dpath;
								$opposite_dpath = $unrestricted_dpath;
							}

							if (is_dir($unrestricted_dpath) && is_dir($restricted_dpath)) {
								# Woah, both directories exist!
								$msg = $this->_merge_data_paths($dpath, $opposite_dpath, $pageid);
								if ($msg) {
									$notfixed[$integrity_type][$page->id] = 'Both data directories existed. Unable to merge them together, Reason:<br />' . $msg;
								} else {
									$fixed[$integrity_type][$page->id] = 'Both data directories existed. Merged them together successfully.';
								}
								continue;
							}

							list($dpath_fixed, $dpath_message) = $this->_correct_data_paths($dpath, $opposite_dpath);

							if ($dpath_message != '') $invalid[$integrity_type][$page->id] = 1;

							if ($dpath_fixed) {
								$fixed[$integrity_type][$page->id] = $dpath_message;
							} else {
								$notfixed[$integrity_type][$page->id] = $dpath_message;
							}
							# If it passes all that, let's add the new status.
							# It will automatically create the directory etc.
							$page->add_status($newstatus);

							global $PAGE_STATUSES;
							$fixed[$integrity_type][$page->id] = 'Added a valid page status of \'' . $PAGE_STATUSES[$newstatus] . '\'';
						} else {

							# If there is a status, let's just check the directories.

							$restricted_dpath = $DATA_PATH . "/restricted/page/" . $page->id;
							$unrestricted_dpath = $DATA_PATH . "/unrestricted/page/" . $page->id;

							$effective_unrestricted = $page->effective_unrestricted();

							# If the page is public, there should be an unrestricted data directory.
							if ($effective_unrestricted) {
								$dpath = $unrestricted_dpath;
								$opposite_dpath = $restricted_dpath;
							} else {
								$dpath = $restricted_dpath;
								$opposite_dpath = $unrestricted_dpath;
							}

							if (is_dir($unrestricted_dpath) && is_dir($restricted_dpath)) {
								# First mark it as "invalid".
								$invalid[$integrity_type][$page->id] = 1;
								$msg = $this->_merge_data_paths($dpath, $opposite_dpath, $pageid);
								if ($msg) {
									$notfixed[$integrity_type][$page->id] = 'Both data directories existed. Unable to merge them together, Reason:<br />' . $msg;
								} else {
									$fixed[$integrity_type][$page->id] = 'Both data directories existed. Merged them together successfully.';
								}
								continue;
							}

							list($dpath_fixed, $dpath_message) = $this->_correct_data_paths($dpath, $opposite_dpath);

							if ($dpath_message != '') {
								$invalid[$integrity_type][$page->id] = 1;
								if ($dpath_fixed) {
									$fixed[$integrity_type][$page->id] = $dpath_message;
								} else {
									$notfixed[$integrity_type][$page->id] = $dpath_message;
								}
							}
						}
						# Now we check the page->template
						# I shouldn't call it directly like this, but there's no function to retrieve it.
						$template = $page->template;
						if (!$template) {
							# First mark it as "invalid".
							$invalid[$integrity_type][$page->id] = 1;
							$page->set_template('standard');
							$fixed[$integrity_type][$page->id] = 'Page had no template specified. Changed it to be a standard page.';
						} elseif ($template == 'frontitia') {
							if ($fixes = $this->cleanup_frontitia_page($page->id)) {
								$fixed[$integrity_type][$page->id] = $fixes;
							}
						}
					break;

					case 'metadata':
						if (empty($page->keywords)) {
							$invalid[$integrity_type][$page->id]['keywords'] = 1;
						} else {
							$valid[$integrity_type][$page->id]['keywords'] = 1;
						}

						if (empty($page->description)) {
							$invalid[$integrity_type][$page->id]['description'] = 1;
						} else {
							$valid[$integrity_type][$page->id]['description'] = 1;
						}

					break;

				}
				unset($page);
				$page_count++;
			}
			$session->set_var(get_class($this).'_valid',    $valid);
			$session->set_var(get_class($this).'_invalid',  $invalid);

			$session->set_var(get_class($this).'_fixed',    $fixed);
			$session->set_var(get_class($this).'_notfixed', $notfixed);

			$page_index = array_slice($page_index,$num);

			$session->set_var('page_count',$page_count);
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
				$process_url = $this->get_backend_href()."&action=".$integrity_type."_finished&started=1";
				$status = 'Finalising Report...';
				$percent = 100;
			} else {
				$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=$num_looked&action=".$integrity_type."&started=1&start_time=$start_time";
				$status = "Completed $num_looked pages - ".($num_to_lookup - $num_looked).' remaining - est time: '.easy_time_total($time_left);
			}
			echo status_popup($percent,$process_url,false,false,'#330099','Checking System Integrity - Please wait',$status);
			exit();
		}

		$links_found = array();
		$page_count = 0;

		$session->set_var('page_count',$page_count);
		$page_index = array();

		if ($this->asset_type == 'web') {
			# we need to do this for all sites
			$sites = $web_system->get_editable_sites();
			$page_index = array();
			foreach ($sites as $siteid => $name) {
				$site_index = $this->get_pageid_childfirst($siteid);
				$page_index = $page_index + $site_index;
			}
		} else if ($this->asset_type == 'page') {
			$site = &$web_system->get_site();
			$page = &$web_system->get_page();
			$page_index_tree = $this->get_pageid_childfirst($site->id, $page->id);
			$page_index = $page_index + $page_index_tree;
			$page_index[] = (int)$page->id;
		} else {
			$site = &$web_system->get_site();
			$page_index = $this->get_pageid_childfirst($site->id);
		}
		$session->set_var('page_index',$page_index);

		$num_to_lookup = count($page_index);
		$num = 10; # number of lookups to process at a time
		$process_url = $this->get_backend_href()."&num_to_lookup=$num_to_lookup&num=$num&num_looked=0&action=".$integrity_type."&started=0";
		echo status_popup(1,$process_url,true,false,'#330099','Checking System Integrity - Please wait', "Starting to process $num_to_lookup pages");
	}

	function _correct_data_paths($correct='', $incorrect='') {
		if (!$correct || !$incorrect) return array(false, 'Unable to correct. No paths given to me.');
		$fixed = false;
		$message = '';
		if (!is_dir($correct)) {
			# Check the opposite.
			if (is_dir($incorrect)) {
				# Eek. This page needs some attention.
				# Attempt to move the directory across.
				if (rename($incorrect, $correct)) {
					$fixed = true;
					$message = 'The page has a status but it\'s incorrect. The opposite data path existed. Moved the data directory to the correct position.';
				} else {
					$message = 'The page has a status but it\'s incorrect. The opposite data path exists. Attempted to fix but unable to. Possibly a permissions problem.';
				}
			}
			if (!is_dir($incorrect)) {
				# So the opposite directory doesn't exist, let's create our directory.
				if (create_directory($correct)) {
					$fixed = true;
					$message = 'The data directory was missing. Created successfully.';
				} else {
					$message = 'Unable to create the correct data directory.';
				}
			}
		}

		if (is_dir($correct)) {
			# check subdir's
			$subdirs = array('site_design/generated', 'template', 'thumbs');
			$subdirmsgs = array();
			foreach($subdirs as $subdir) {
				if (!is_dir($correct.'/'.$subdir)) {
					if (!create_directory($correct.'/'.$subdir)) {
						$subdirmsgs[] = 'Unable to create directory ' . $correct.'/'.$subdir;
					} else {
						$fixed = true;
						$subdirmsgs[] = 'The directory ' . $correct . '/' . $subdir . ' was missing';
					}
				}
			}
			$message = implode("<br />", $subdirmsgs);
		}
		global $SYSTEM_ROOT;
		$message = str_replace($SYSTEM_ROOT, '', $message);
		return array($fixed, $message);
	} # end function _correct_data_paths

	function _merge_data_paths($correct_path='', $incorrect_path='', $pageid=0) {
		static $returnmsg = '';

		if (!is_dir($incorrect_path)) return;
		if (!is_dir($correct_path)) create_directory($correct_path);
		$incorrect_files = list_files($incorrect_path);
		if (!empty($incorrect_files)) {
			foreach($incorrect_files as $fname) {
				if (is_array($fname)) {
					if (!is_dir($correct_path . '/' . $fname[0])) {
						if (!mkdir($correct_path . '/' . $fname[0])) {
							$returnmsg .= 'Unable to create directory '.$correct_path.'/'.$fname[0].': ' . $php_errormsg . '<br />';
						}
					}
					$this->_merge_data_paths($correct_path.'/'.$fname[0], $incorrect_path.'/'.$fname[0], $pageid);
				} else {
					$orig_name = $fname;
					while(file_exists($correct_path.'/'.$fname)) {
						$fname = increment_filename($fname);
					}
					if (!rename($incorrect_path . '/' . $orig_name, $correct_path . '/' . $fname)) {
						$returnmsg .= 'Unable to move '.$incorrect_path.'/'.$orig_name.' to '.$correct_path.'/'.$fname.': ' . $php_errormsg . '<br />';
						continue;
					}
					if ($pageid) {
						/*
							Register the file in mysource if we have a pageid to attach it to.
						*/
						$web_system = &$this->get_web_system();
						$page = &$web_system->get_page($pageid);
						$file_list = $page->file_index;
						$known_file_names = array();

						if (!empty($file_list)) {
							foreach($file_list as $fid) {
								$file = New File($fid);
								$known_file_names[] = $file->filename;
								unset($file);
							}
						}

						if (!empty($known_file_names)) {
							if (!in_array($fname, $known_file_names)) {
								$file = New File(0);
								$file->create($pageid, $fname);
								if ($file->id) {
									$file->set_visible('N');
								}
								unset($file);
							}
						}
					}
				}
			}
		}
		if (is_dir($incorrect_path)) {
			if (!rmdir($incorrect_path)) {
				$returnmsg .= 'Unable to remove directory '.$incorrect_path.': ' . $php_errormsg . '<br />';
			}
		}
		global $SYSTEM_ROOT;
		return str_replace($SYSTEM_ROOT, '', $returnmsg);
	} # end function _merge_data_paths

	/**
	* Cleansup problems with a frontitia page
	*
	* @param int	$pageid A pageid of a page that is a frontitia page
	* @returns string
	* @access public
	*/
	function cleanup_frontitia_page($pageid) {
		$fixes = '';
		$web_system = &$this->get_web_system();
		$frontitia_page = &$web_system->get_page($pageid);
		$frontitia_page_template = &$frontitia_page->get_template();
		$serialized_parameters = serialize($frontitia_page_template->parameters);
		$before_param_length = strlen($serialized_parameters);
		$things_to_delete = array('customised_category_posting_storage', 'customised_category_editing_storage', 'browsing_category_bodycopy_storage_DHTML', 'customised_category_record_list_storage', 'customised_category_record_storage', 'listings_per_page', 'prev_page_link', 'next_page_link', 'sortable_attributes_record_list_storage', 'categories_seek_columns_array', 'categories_seek_columns_columns_branching_level_array', 'customised_category_search_storage', 'local_searching_attributes_array', 'RMIUEP_pageid', 'RMIUEP_link_name', 'customised_category_searching_storage', 'categories_seek_columns', 'customised_category_deleting_storage', 'customised_category_browsing_storage');
		foreach($things_to_delete as $area) {
			if (isset($frontitia_page_template->parameters[$area])) {
				unset($frontitia_page_template->parameters[$area]);
				$fixes .= 'Junk found in parameters. '.$area.' has been deleted as it is no longer stored here<br/>';
			}
		}
		$timed_caching_category_storage = $frontitia_page_template->parameters['timed_caching_category_storage'];
		if (!empty($timed_caching_category_storage)) {
			$browsing_storage = $frontitia_page_template->get_customised_category_browsing_storage();
			$browsing_storage_categories = array_keys($browsing_storage);

			foreach($timed_caching_category_storage as $categoryid => $data) {
				if (!in_array($categoryid, $browsing_storage_categories)) {
					unset($frontitia_page_template->parameters['timed_caching_category_storage'][$categoryid]);
					$fixes .= 'Data was deleted from the timed caching category storage at categoryid '.$categoryid.'<br/>';
				}
			}
		}

		if ($fixes) {
			$frontitia_page_template->save_parameters();
			$serialized_parameters = serialize($frontitia_page_template->parameters);
			$after_param_length = strlen($serialized_parameters);
			if ($before_param_length != 0 && $after_param_length != 0) {
				$percentage = ($after_param_length/$before_param_length)*100;
				$percentage = number_format($percentage, 2);
				$fixes .= $percentage.'% of this Frontitia was excess junk data and was deleted<br/>';
			}
		}

		return $fixes;
	}
} #end class system_integrity
?>
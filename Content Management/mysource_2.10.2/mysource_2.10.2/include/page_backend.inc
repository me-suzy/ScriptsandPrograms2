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
## Desc: Functions for dealing with "page"s in the system
## $Source: /home/cvsroot/mysource/include/page_backend.inc,v $
## $Revision: 2.55.2.1 $
## $Author: dofford $
## $Date: 2004/05/03 01:13:43 $
#######################################################################
#---------------------------------------------------------------------#

/**
* This class is the result of splitting up the original page.inc
* into it's backend and frontend functions in order to make it smaller
* and therefore quicker to load and require less memory on the frontend
*
* This object should only be created from within Page::print_backend()
* and is passed a reference to the page that it is editing.
*
* Where you would normally reference $this you need to reference $this->page
* eg. $this->id becomes $this->page->id
*
* @access public
* @see Page::print_backend()
*
*/

class Page_Backend extends WebObject {

	/**
	* A boolean specifiying whether the backend has been setup or not
	* @var boolean
	* @see &setup_backend()
	*/
	var $_backend_setup = false;

	/**
	* A reference to the page that is currently being edited by this object
	* @var object Page
	*/
	var $page;

	/**
	* Constructor
	*
	* @param   object Page $page	The page object we are going to deal with
	* @returns object Page_Backend
	* @access  public
	*/
	function Page_Backend($page) {
		WebObject::WebObject();

		if (get_class($page) != 'page') {
			$this->_set_error('Attempt to use page backend with an object that is not a "page".',__FILE__,__LINE__);
		} else {
			$this->page = &$page;
		}
	}

	/**
	* Perform some actions before the object is serialised
	*
	* @returns array
	* @access  private
	*/
	function __sleep() {
		$result = WebObject::__sleep();
		array_remove_element('_backend_setup',$result);
		array_remove_element('page',$result);
		return $result;
	}#end __sleep()

	/**
	* Returns a reference to a page
	*
	* @param   int $pageid	The pageid of the page that has been requested
	* @returns object Page
	* @access  private
	*/
	function &get_page($pageid=0) {
		if(!$pageid || $pageid == $this->page->id) return $this->page; # Idiot
		return $this->web_system->get_page($pageid);
	}

	/**
	* Returns a reference to a file
	*
	* @param   int $fileid	The fileid of the file that has been requested
	* @returns object File
	* @access  private
	*/
	function &get_file($fileid=0) {
		return $this->web_system->get_file($fileid);
	}

	/**
	* Sets up the backend with all this nice things for this page
	*
	* @returns object Backend
	* @access  public
	*/
	function &setup_backend() {
		global $XTRAS;
		$site    = &$this->page->get_site();
		$backend = &$site->setup_backend();

		if (!$this->_backend_setup) {

			$lineage = &$this->page->get_lineage();
			if (!empty($lineage)) {
				foreach($lineage as $pageid => $name) {
					if($pageid == $this->page->id) $name = '<i>'.$name.'</i>';
					$content .= ' &gt; <a href="page.php?p='.$pageid.'" class="page-lineage">'.$name.'</a>';
				}
			}
			$backend->append_context_bar('page_lineage',$content);

			$backend->set_name($this->page->name);

			$backend->set_tab('page_properties','page.php?p='.$this->page->id,'Page Properties','Manage the status and identification information of this page and the position of this page in the site structure.','page');
			$backend->set_active_tab('page_properties');

			if ($this->page->status() == 'R') {
				# Add the preview button only.
				if(!empty($site->urls)) {
					$backend->set_toolbar_button('preview', $this->page->get_href(), 'Preview this page on the frontend (Alt + V).', 'preview', '_blank');
				}

				$this->_backend_setup = true;
				return $backend;
			}

			$backend->set_tab('page_internationalisation', 'page.php?p='.$this->page->id.'&PARAMETER_SCREEN=page_internationalisation', 'Internationalisation', 'Manage the language and character set settings for this page.','page');

			$backend->set_tab('page_attachments', 'page.php?p='.$this->page->id.'&PARAMETER_SCREEN=page_attachments', 'Attachments', 'Manage the file attached to and used in this page.','file');
			
			if($this->page->editor_access()) {
				$backend->set_tab('page_metadata', 'page.php?p='.$this->page->id.'&PARAMETER_SCREEN=page_metadata', 'Metadata', 'Manage the Metadata for this page.','page');
			}

			$backend->set_tab('page_security', 'page.php?p='.$this->page->id.'&PARAMETER_SCREEN=page_security', 'Security', 'Manage the read- and write-permissions for this page.','security');
			if($this->page->readonly_editor_access()) {
				$backend->set_tab('wizards', 'page.php?p='.$this->page->id.'&PARAMETER_SCREEN=wizards', 'Wizards', 'View wizards for this page.','graph');
			}

			$parameter_screen  = $_GET['PARAMETER_SCREEN'];
			if($parameter_screen && $backend->get_tab_label($parameter_screen)) {
				$backend->set_active_tab($parameter_screen);
			}
			$backend->set_hidden_field('PARAMETER_SCREEN',$parameter_screen);

    		if($this->page->designid && $site->admin_access()) {
    			$backend->set_tab('site_custom_design', 'page.php?p='.$this->page->id.'&design_edit=1', 'Custom Design', 'Customize this pages\'s design.', 'page');
    		}

			if($this->page->write_access()) {
				$template_name = $XTRAS->name('page/templates',$this->page->template);
				$template_ver  = $XTRAS->version('page/templates',$this->page->template);
				$template_desc = $XTRAS->description('page/templates',$this->page->template);
				$backend->set_tab('page_template','page.php?p='.$this->page->id.'&template_edit=1','Contents: '.$template_name,'Template: '.$template_name.' v'.$template_ver.'<br><br>'.$template_desc,'xtra');
			}

			$backend->set_heading('Edit Page','page');
			$backend->set_subheading($backend->get_tab_label($backend->active_tab));
			$backend->set_id_string($this->page->id);

			if(!empty($site->urls)) {
				$backend->set_toolbar_button('preview', $this->page->get_href(), 'Preview this page on the frontend (Alt + V).', 'preview', '_blank');
			}

			# ain't no point showing these if we are in the design or template areas because
			# these get processed b4 the delete or dupe
			if (!$_REQUEST['design_edit'] && !$_REQUEST['template_edit']) {

				# If we're on the safe-edit version of a page, we don't really want to delete this page.
				# We need to get the original page to cancel the safe-edit.
				if (!$this->page->replaceid) {
					if($this->page->delete_access()) {
						$backend->set_toolbar_button('delete', "javascript: if (confirm('Are you sure you want to delete this page?') && confirm('Really sure? This is irreversible.')) {document.edit.action.value='Delete';document.edit.submit()}",'Delete this page','delete');
					}
				}

				if($site->editor_access()) {
					$backend->set_toolbar_button('dupe',"javascript: if ((confirm('Are you sure you want to duplicate this page? This will include all its subpages.'))) {document.edit.action.value = 'Dupe';document.edit.submit();}",'Duplicate this page and all of its subpages','dupe');

					$backend->set_toolbar_button('dupe_one',"javascript: if ((confirm('Are you sure you want to duplicate this page? This will NOT include any of its subpages.'))) {document.edit.action.value = 'Dupe One';document.edit.submit();}",'Duplicate ONLY this page','dupe_one');
				}
			}

			$this->_backend_setup = true;

		}#end if

		return $backend;
	}


	/**
	* Prints the interface for editing the page
	*
	* @access  public
	*/
	function print_backend() {
		global $XTRAS;
		global $SQUIZLIB_PATH, $WEB_PATH;
		include_once("$SQUIZLIB_PATH/colour/colour.inc");

		$system_config = &get_system_config();
		$session = &get_mysource_session();

		 ##############
		# Die on no ID
		if(!$this->page->id) return;

		$site = &$this->page->get_site();

		 ###################################
		# Setup the backend a little
		$backend = &$this->setup_backend(); # Backend refrence

		$backend->set_title($this->page->name.' - Edit Page');

		 ####################################################################
		# SECUIRTY - Only let in those who have permission to work on a site
		if (!$session->logged_in()) {
			$session->login_screen($backend->title,'You must be logged in and have permission to edit the page <i>'.$this->page->name.'</i> in order to proceed.');
		}

		 ######################################################################
		# This particular user must be affiliated with an managed organisation
		if (!$site->staff_access()) {
			$session->login_screen($backend->title,'You are not a designated staff member of this site.');
		}

		$backend->set_hidden_field('action','Commit');
		$backend->set_hidden_field('pageid',$this->page->id);
		$backend->set_hidden_field('siteid',$this->page->siteid);
		$backend->set_hidden_field('parentid',$this->parentid);
		$backend->set_hidden_field('current_userid',$session->user->id);
		$backend->set_hidden_field('order_files',$_REQUEST['order_files']);
		$backend->set_hidden_field('userid');
		$backend->set_hidden_field('design_edit',$_REQUEST['design_edit']);
		$backend->set_hidden_field('template_edit',$_REQUEST['template_edit']);
		$backend->set_hidden_field('file_edit',$_REQUEST['file_edit']);
		$backend->set_hidden_field('preview_url',$this->page->get_href());

		$floating_site_map_open = $session->get_var('floating_site_map_open');

		 #############################
		# Get the character set right
		$backend->set_charset($this->page->get_effective_default_charset());

		#############################################
		# The floating site map
		if($floating_site_map) {
			$site->print_floating_site_map();
			return;
		}

		 #############################################
		# Do they want to order pages on the site?
		if($_REQUEST['order_files'] &&  $this->page->write_access()) {
			$this->_print_file_orderer();
			return;
		}

		 ##########################################################
		# Display the edit interface for the design if asked for
		if($this->page->designid && $_REQUEST['design_edit']) {
			#$backend->clear_tabs(Array('site_custom_design'));
			$backend->set_active_tab('site_custom_design');
			$design = &$this->page->get_design();
			$design->print_user_backend('page.php?p='.$this->page->id.'&design_edit=1');
			return;
		}

		 ##########################################################
		# Display the edit interface for the template if asked for
		if($_REQUEST['template_edit'] && $this->page->write_access()) {
			# make sure there is at least one url for the site
			# if not, display warning and dont print template
			if(!empty($site->urls)) {
				$template = &$this->page->get_template();
				$template->print_backend();
			} else {
				$backend->set_active_tab('page_template');
				$backend->print_header();
				$backend->open_section('You cannot edit the contents of a page unless the site has a URL');
				$backend->print_footer();
			}
			return;
		}

		 #######################################################
		# Display the edit interface for a file, if asked for
		if($_GET['file_edit']) {
			$print_file_backend = 1;
			if(!$file = &$this->get_file()) {
				# Create a file object without a record in the database
				$file = New File(0);
				if ($_GET['new_file']) {
					$ftp_folder = get_ftp_data_path();
					$f = urldecode($_GET['new_file']);
					if (!is_file($ftp_folder.'/'.$f)) {
						$backend->add_message(MYSOURCE_ERROR_CODE_WARNING, 'Unable to locate file '.$f.' in the FTP directory.');
						$print_file_backend = 0;
					} elseif (!rename($ftp_folder.'/'.$f, $this->page->data_path.'/'.$f)) {
						$backend->add_message(MYSOURCE_ERROR_CODE_WARNING, 'Unable to move file '.$f.' from the FTP directory.');
						$print_file_backend = 0;
					} else {
						# Finally got past all those checks.
						# Let's make sure it's not an illegal file before we attach it.
						$system_config = &get_system_config();
						if(!$system_config->check_file_legality($f)) {
							$backend->add_message(MYSOURCE_ERROR_CODE_WARNING, 'Unable to attach the file, illegal filename. Removing the file.');
							unlink($this->page->data_path.'/'.$f);
							$print_file_backend = 0;
						} else {
							$file->create($this->page->id, $f);
							$file->set_visible('N');
						}
					}
				}
			}
			if ($print_file_backend) {
				$file->print_backend();
				return;
			}
		}

		 #########################################
		# Process a submitted form
		$action = $_REQUEST['action'];

		if($action) {
			# Check they're who we think they are
			if($session->user->id != $_REQUEST['current_userid']) {
				# Gasp! Someone else has logged in since this form was displayed.
				# They might not have had all the options and there could be problems.
				# We can't let them do anything this time.
				$backend->add_message("You have logged in as someone else since Commiting.\nFor security reasons, any changes have been ignored.");
				$action = '';
			}
		}

		 ####################################
		# Special tab-independant functions.
		switch($action) {
			case 'Delete':
				if($this->page->delete_access()) {
					$num = (int) $system_config->batch_delete; # number of pages to process at a time
					if ($num <= 0) {
						$num = 5;
					}
					$started		= $_GET['started'];
					$start_time		= $_GET['start_time'];
					$num_deleted	= $_GET['num_deleted'];
					$num_to_delete	= $_GET['num_to_delete'];

					if (isset($num) && isset($num_to_delete)) {
						$site = &$this->page->get_site($this->page->siteid);
						# Tell the backend that it should show our messages
						$backend->set_delay_messages(false);

						if (!$started) {
							$now = time();
							$process_url = $this->page->get_backend_href()."&num_to_delete=$num_to_delete&num=$num&num_deleted=0&action=Delete&started=1&start_time=$now&current_userid=".$session->user->id;
							echo status_popup(1,$process_url,false,false,'#FF0033','Deleting Page - Please wait', 'Starting to delete '.$num_to_delete.' pages');
							exit();
						}

						$batch_delete_array = $session->get_var('batch_delete_array');
						$now_time = time();
						$last_batch_start_time = $batch_delete_array['delete_start_time'];
						$batch_size = $batch_delete_array['batch_size'];
						$batch_duration_per_page = ($now_time - $last_batch_start_time) / $batch_size;
						# 20 seconds max amount of time we will let a dupe batch possibly go for in our estimate
						if ($batch_duration_per_page > 20) {
							$batch_duration_per_page = 20;
						}
						$recomended_batch = 20 / $batch_duration_per_page;
						$recomended_batch = (int)floor($recomended_batch);
						if ($recomended_batch < $num) {
							$num = $recomended_batch;
							if (!is_int($num) || $num < 1) {
								$num = 1;
							}
							$extra_message = '<i>Mysource overridden batch size to '.$num.'</i>';
						} else {
							$extra_message = '';
						}
						$batch_delete_array['delete_start_time'] = $now_time;
						$batch_delete_array['batch_size'] = $num;
						$session->set_var('batch_delete_array', $batch_delete_array);

						# delete $num subpages
						$backend->add_message(ereg_replace("[\n]+","\n",$this->page->delete(false, $num, false)));
						$count = count($lala['1994']['childids']);
						$num_deleted += $num;
						$percent = ceil(($num_deleted / $num_to_delete) * 100);
						$finish = (($num_deleted >= $num_to_delete) ? 1 : 0);
						$time_diff = time() - $start_time;
						$time_per_page = $time_diff / $num_deleted;
						$time_left = $time_per_page * ($num_to_delete - $num_deleted);
						if ($time_left <= 0) $time_left = 1;
						$status = '';

						if ($finish) {
							# Rebuild it totally so order no is ok again cause modifying it doesn't allow for order number
							$site->clear_page_index();
							$process_url = $this->page->get_backend_href()."&cleanup=0&action=Delete_finished&started=1&current_userid=".$session->user->id;
							$status = 'Finished deleting. Cleaning up...';
						} else {
							# Save the modified page index to session so when site is reloaded site can find our page index
							$site->write_page_index_to_cache();
							$process_url = $this->page->get_backend_href()."&num_to_delete=$num_to_delete&num=$num&num_deleted=$num_deleted&action=Delete&started=1&start_time=$start_time&current_userid=".$session->user->id;
							$status = "$num_deleted deleted - ".($num_to_delete - $num_deleted).' remaining - est time: '.easy_time_total($time_left).'<br />'.$extra_message;
						}
						echo status_popup($percent,$process_url,false,false,'#FF0033','Deleting Page - Please wait',$status);
						exit();
					}

					# First guess is going to be poor because we are not actually deleting anything so I'm going to cheat here to make the estimate make the first batch always 1. I do this because if the first estimate is wrong the whole thing doesn't have a chance at running so it's better safe than sorry
					$now_time = time();
					$batch_delete_array = array('delete_start_time' => ($now_time-20), 'batch_size' => 1);
					$session->set_var('batch_delete_array', $batch_delete_array);

					$index = &$this->page->get_all_subpageids();
					$num_to_delete = count($index) + 1;
					$process_url = $this->page->get_backend_href()."&num_to_delete=$num_to_delete&num=$num&num_deleted=0&action=Delete&started=0&current_userid=".$session->user->id;
					echo status_popup(1,$process_url,true,false,'#FF0033','Deleting Page - Please wait', "Starting to delete $num_to_delete pages");
				}
				break;

			case 'Delete_finished' :

				if($this->page->delete_access()) {

					# Trick the backend so that it shows our messages
					$backend->header_printed = true;

					# finished deleting ?
					$cleanup = $_GET['cleanup'];

					if (!$cleanup) {
						$relocation = (($this->page->parentid)?'page.php?p='.$this->page->parentid:'site.php?s='.$site->id);
						if ($floating_site_map_open) $relocation .= "'; refresh_site_map(".$this->page->siteid."); '";
						$backend->add_message(ereg_replace("[\n]+","\n",$this->page->delete()));
						$session->unset_var('batch_delete_array');
						$session->unset_var('active_pageid');
						echo status_popup('100',$relocation,false,true,'#FF0033','Delete Complete','Redirecting you to page '.(($this->page->parentid)? $this->page->parentid : $site->id));
						exit();
					}
				}
				break;

			case 'Dupe':
				if($this->page->editor_access()) {
					$num = (int) $system_config->batch_dupe; # number of pages to process at a time
					if ($num <= 0) {
						$num = 5;
					}
					$started		= $_GET['started'];
					$num_duped		= $_GET['num_duped'];
					$start_time		= $_GET['start_time'];
					$num_to_dupe	= $_GET['num_to_dupe'];
					$site = &$this->page->get_site($this->page->siteid);

					if (isset($num) && isset($num_to_dupe)) {
						# Tell the backend that it should shows our messages
						$backend->set_delay_messages(false);

						if (!$started) {
							$now = time();
							$process_url = $this->page->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=1&action=Dupe&started=1&start_time=$now&current_userid=".$session->user->id;
							echo status_popup('1',$process_url,false,false,'#330099','Duping Page - Please wait', "Starting to dupe $num_to_dupe page".(($num_to_dupe > 1) ? 's' : ''));
							exit();
						}

						$new_pageid = $session->get_var('dupe_pageid');

						# dupe $num subpages
						$dupe_map = '';
						$dupe_message = '';
						$siteid = $this->page->siteid;

						$batch_dupe_array = $session->get_var('batch_dupe_array');
						$now_time = time();
						$last_batch_start_time = $batch_dupe_array['dupe_start_time'];
						$batch_size = $batch_dupe_array['batch_size'];
						$batch_duration_per_page = ($now_time - $last_batch_start_time) / $batch_size;
						# 20 seconds max amount of time we will let a dupe batch possibly go for in our estimate
						if ($batch_duration_per_page > 20) {
							$batch_duration_per_page = 20;
						}
						$recomended_batch = 20 / $batch_duration_per_page;
						$recomended_batch = (int)floor($recomended_batch);
						if ($recomended_batch < $num) {
							$num = $recomended_batch;
							if (!is_int($num) || $num < 1) {
								$num = 1;
							}
							$extra_message = '<i>Mysource overridden batch size to '.$num.'</i>';
						} else {
							$extra_message = '';
						}
						$batch_dupe_array['dupe_start_time'] = $now_time;
						$batch_dupe_array['batch_size'] = $num;
						$session->set_var('batch_dupe_array', $batch_dupe_array);
						$pages_to_dupe = $session->get_var('pages_to_dupe');

						$remaining_pages_to_dupe = $this->page->dupe_specified_pages($this->page->siteid,$new_pageid, $num, $pages_to_dupe, $dupe_map, $dupe_message, $save_page_index_cache);

						$session->set_var('pages_to_dupe', $remaining_pages_to_dupe);
						$num_duped += $num;
						$percent = ceil(($num_duped / $num_to_dupe) * 100);
						$finish = (($num_duped >= $num_to_dupe) ? 1 : 0);
						$time_diff = time() - $start_time;
						$time_per_page = $time_diff / $num_duped;
						$time_left = $time_per_page * ($num_to_dupe - $num_duped);
						if ($time_left <= 0) {
							$time_left = 1;
						}

						if ($finish) {
							# Rebuild it totally so order no is ok again cause modifying it doesn't allow for order number
							$site->clear_page_index();
							$process_url = $this->page->get_backend_href()."&cleanup=0&action=Dupe_remap&started=1&current_userid=".$session->user->id;
							$status = 'Finished duping. Preparing to remap links...';
						} else {
							$process_url = $this->page->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=$num_duped&start_time=$start_time&action=Dupe&started=1&current_userid=".$session->user->id;
							$status = "$num_duped duped - ".($num_to_dupe - $num_duped).' remaining - est time: '.easy_time_total($time_left).'<br />'.$extra_message;

							# Save the modified page index to session so when site is reloaded site can find our page index
							$site->write_page_index_to_cache();

						}

						echo status_popup($percent,$process_url,false,false,'#330099','Duping Page - Please wait', $status);
						exit();
					}

					$pages_to_dupe = $this->page->get_all_subpageids();
					$num_to_dupe = count($pages_to_dupe) + 1;
					$dupe_map = array();
					$dupe_message = '';
					$session->set_var('dupe_map', $dupe_map);
					$dupe_time = time();
					$batch_dupe_array = array('batch_size' => 1, 'dupe_start_time' =>$dupe_time);
					$session->set_var('batch_dupe_array', $batch_dupe_array);
					$dupe_map = array();
					$dupe_message = '';

					$new_pageid = $this->page->dupe($this->page->siteid,$this->page->parentid,false,false,false,$num,false,$dupe_map,$dupe_message);
					$page_index = $site->get_page_index();
					# Get all subpages with information about their level
					$pages_to_dupe_with_level_orderno = array();
					foreach($pages_to_dupe as $pageid) {
						$pages_to_dupe_with_level_orderno[$pageid] = array($page_index[$pageid]['level'],$page_index[$pageid]['orderno']);
					}

					# Sort the pages in order of level so the upper most level gets duped first, This way we don't need to waste time checking if their parent is duped already
					uasort($pages_to_dupe_with_level_orderno, "page_dupe_comparison");

					# Now it's sorted we only care about the pageids
					$pages_to_dupe = array_keys($pages_to_dupe_with_level_orderno);
					# Save this info for us to use in the future
					$session->set_var('pages_to_dupe', $pages_to_dupe);

					$dupe_map = $session->get_var('dupe_map');
					$dupe_map['page'][$this->page->id] = $new_pageid;
					$session->set_var('dupe_pageid', $new_pageid);
					$session->set_var('dupe_map', $dupe_map);
					$session->set_var('dupe_message', $dupe_message);
					$process_url = $this->page->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=1&action=Dupe&started=0&current_userid=".$session->user->id;
					echo status_popup('1',$process_url,true,false,'#330099','Duping Page - Please wait', "Starting to dupe $num_to_dupe pages");
				}
				break;

			case 'Dupe One':
				if($this->page->editor_access()) {
					$dupe_map = array();
					$dupe_message = '';

					$new_pageid = $this->page->dupe($this->page->siteid,$this->page->parentid,true,false,false,false,true,$dupe_map,$dupe_message);
					$new_page = &$this->get_page($new_pageid);
					$this->page->dupe_cleanup($new_pageid, $dupe_message);

					# Trick the backend so that it shows our messages
					$backend->header_printed = true;
					$backend->add_message(ereg_replace("[\n]+","\n",$dupe_message));

					?>
					<script language="JavaScript">
						window.location = 'page.php?p=<?=$new_pageid?>';
					</script>
					<?
				}
				break;

			case 'Dupe_remap':
				if($this->page->editor_access()) {

					$num			= $_GET['num'];
					$started		= $_GET['started'];
					$num_duped		= $_GET['num_duped'];
					$start_time		= $_GET['start_time'];
					$num_to_dupe	= $_GET['num_to_dupe'];

					if (isset($num) && isset($num_to_dupe)) {

						# Tell the backend that it should shows our messages
						$backend->set_delay_messages(false);

						if (!$started) {
							$now = time();
							$process_url = $this->page->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=0&action=Dupe_remap&started=1&start_time=$now&current_userid=".$session->user->id;
							if ($floating_site_map_open) $process_url .= "'; refresh_site_map(".$this->page->siteid."); '";
							echo status_popup('1',$process_url,false,false,'#330099','Remaping links - Please wait', "Starting to remap $num_to_dupe page".(($num_to_dupe > 1) ? 's' : ''));
							exit();
						}

						$new_pageid = $session->get_var('dupe_pageid');

						# remap $num subpages
						$this->page->dupe_remap($num);

						$num_duped += $num;
						$percent = ceil(($num_duped / $num_to_dupe) * 100);
						$finish = (($num_duped >= $num_to_dupe) ? 1 : 0);
						$time_diff = time() - $start_time;
						$time_per_page = $time_diff / $num_duped;
						$time_left = $time_per_page * ($num_to_dupe - $num_duped);
						if ($time_left <= 0) $time_left = 1;
						$status = '';

						if ($finish) {
							$process_url = $this->page->get_backend_href()."&cleanup=0&action=Dupe_finished&started=1&current_userid=".$session->user->id;
							$status = 'Finished remaping links. Cleaning up...';
						} else {
							$process_url = $this->page->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=$num_duped&start_time=$start_time&action=Dupe_remap&started=1&current_userid=".$session->user->id;
							$status = "$num_duped pages remaped - ".($num_to_dupe - $num_duped).' remaining - est time: '.easy_time_total($time_left);
						}
						echo status_popup($percent,$process_url,false,false,'#330099','Remaping Links - Please wait', $status);
						exit();
					}

					$index = $this->page->get_all_subpageids();
					$num_to_dupe = count($index) + 1;
					$num = (int) $system_config->batch_dupe; # number of pages to process at a time
					$dupe_map = $session->get_var('dupe_map');
					$new_pageid = $dupe_map['page'][$this->page->id];
					$new_page = &$this->page->web_system->get_page($new_pageid);
					$new_template = $new_page->get_template();
					$new_template->remap_link_ids($dupe_map);
					if ($num <= 0) $num = 10;
					$process_url = $this->page->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=0&action=Dupe_remap&started=0&current_userid=".$session->user->id;
					echo status_popup('1',$process_url,true,false,'#330099','Remaping Links - Please wait', "Starting to remap $num_to_dupe pages");
				}
				break;

			case 'Dupe_finished' :

				if($this->page->editor_access()) {

					# Trick the backend so that it shows our messages
					$backend->header_printed = true;

					# finished duping ?
					$cleanup = $_GET['cleanup'];

					if (!$cleanup) {
						$dupe_message = $session->get_var('dupe_message');
						$this->page->dupe_cleanup($new_pageid, $dupe_message);
						$new_pageid = $session->get_var('dupe_pageid');
						$backend->add_message(ereg_replace("[\n]+","\n",$dupe_message));
						$session->unset_var('pages_to_dupe');
						$session->unset_var('batch_dupe_array');
						$session->unset_var('dupe_pageid');
						$session->unset_var('dupe_message');
						$session->unset_var('dupe_map');
						echo status_popup('100',"page.php?p=$new_pageid",false,true,'#330099','Dupe Complete',"Redirecting you to page $new_pageid");
						exit();
					}
				}
				break;

		} # end switch

		 ################################
		# We'll be needing this for sure
		$db = &$this->get_db();
		# And this
		$index = &$site->get_page_index();


		$parameter_screen = $_GET['PARAMETER_SCREEN'];
		if ($this->page->status() == 'R' && $parameter_screen != 'page_properties') {
			echo js_location('page.php?p='.$this->page->id.'&PARAMETER_SCREEN=page_properties');
			exit();
		}

		switch($parameter_screen) {

			 ##########################################
			# The Page Internationalisation Screen
			case 'page_internationalisation':
				if($action == 'Commit' && $this->page->write_access()) {
					$default_languages = $_POST['default_languages'];
					$default_charset = $_POST['default_charset'];	$backend->add_message($this->page->set_default_languages(gpc_stripslashes(implode(',',$default_languages))));
					$backend->add_message($this->page->set_default_charset(gpc_stripslashes($default_charset)));
					$backend->set_charset($this->page->get_effective_default_charset());
					$this->page->updated();
				}

				$backend->print_header();

				$backend->open_section('Internationalisation');

				$backend->open_field('Languages','top');
				$languages_config = &get_config('languages');
				$language_options = &$languages_config->languages;
				if ($this->page->write_access()) {
					echo('<table width=100% cellpadding=2 cellspacing=0 border=0><tr>');
					$i = 0;
					if (!empty($language_options)) {
						$num_langs_per_col = ceil(count($language_options) / 4);
						$page_langs = explode(",", $this->page->default_languages);
						echo '
						<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td valign="top" width="25%">
									<table cellpadding="2" cellspacing="0" border="0">
								';
						$i = 0;
						foreach($language_options as $code => $language) {
							echo '
										<tr>
											<td valign="top"><input type="checkbox" name="default_languages[]" value="'.$code.'" '.((in_array($code, $page_langs))?'checked':'').'> '.$language.'</td>
										</tr>
								';
							$i++;
							if($i % $num_langs_per_col == 0) {
								echo '
									</table>
								</td>
								<td valign="top" width="25%">
									<table cellpadding="2" cellspacing="0" border="0">
								';
							}
						}
						echo '
									</table>
								</td>
							</tr>
						</table>
						';
					} else {
						echo('<td>None</td>');
					}
					echo('</tr></table>');
					echo('<span class=backend_smallprint><br>Leave all unselected to use the site default: <i>'.$languages_config->name_list($site->get_effective_default_languages()).'</i></span>');
				} else {
					echo '<p class=backend_data>'.$languages_config->name_list((($this->page->default_languages)?$this->page->default_languages:$site->get_effective_default_languages()));
				}


				$backend->open_field('Character Set','top');
				$charsets_config = &get_config('charsets');
				$charset_options = array('' => '-- Site Default -- '. $charsets_config->charsets[$site->get_effective_default_charset()]) + $charsets_config->charsets;
				if ($this->page->write_access()) {
					echo combo_box('default_charset',$charset_options,$this->page->default_charset, 'class=backend_data');
				} else {
					echo('<p class=backend_data>'.$charset_options[$this->page->default_charset]);
				}


				break;

			 ##########################################
			# The Page Metadata Screen
			case 'page_metadata':

				global $INCLUDE_PATH;

				include_once("$INCLUDE_PATH/metadata.inc");
				$metadata = new Metadata($this->page->siteid, $this->page->id);

				if($action == 'Commit') {
					if($this->page->editor_access()) {
						$metadata->process_backend();
					}
				}

				$metadata->set_backend_tabs();
				$backend->remove_toolbar_button('delete');
				$backend->remove_toolbar_button('dupe');
				$backend->remove_toolbar_button('dupe_one');

				$backend->print_header();
				$metadata->print_backend();
				
				break;

			 #############################
			# The Page Attachments screen
			case 'page_attachments':
				if($action == 'New File') {
					 $backend->set_relocation('page.php?p='.$this->page->id.'&file_edit=1');
				}

				if($action == 'Delete Files' && $this->page->write_access()) {
					$delete_files = $_POST['delete_files'];

					$message = '';
					if (!empty($delete_files)) {
						foreach ($delete_files as $id => $on) {
							if (in_array($id, $this->page->file_index)) {
								$file = &$this->get_file($id);
								$message .= $file->delete()."\n";
							}
						}
						$this->page->updated();
					}
					$backend->set_message($message);
					$backend->set_relocation('page.php?p='.$this->page->id.'&PARAMETER_SCREEN=page_attachments');
				}

				$backend->print_header();

				$backend->open_section('File Attachments','file');

				if($this->page->write_access()) {
					$backend->open_field('Reorder','one_liner');
					$backend->print_icon_button('javascript: popup_file_orderer('.$this->page->id.')','Reorder the file attachments','reorder');
				}

				$backend->open_field('File List','top');

				# dir_list is used to check for unregistered files in the data/ftp folder.
				$ftp_folder = get_ftp_data_path();
				if (!is_dir($ftp_folder)) create_directory($ftp_folder);
				$dir_list = list_files($ftp_folder);

				if(!empty($this->page->file_index) || !empty($dir_list)) {
					echo '<table cellspacing=2 cellpadding=2 border=0>';
					if($this->page->write_access()) {
						echo '<tr bgcolor=#d0d0e0><td colspan=4>&nbsp;</td><td><b>Delete</b></td></tr>';
					}
					$var = 0;
					$bgcolor = '';
					$registered = array();
					$orphaned = array();

					if (!empty($this->page->file_index)) {
						foreach($this->page->file_index as $id) {
							if ($var == 0) {
								$var = 1;
								$bgcolor = '#dbdbeb';
							} else {
								$var = 0;
								$bgcolor = '#d0d0e0';
							}

							$file = &$this->get_file($id);
							if(file_exists($this->page->data_path.'/'.$file->filename)) {
								echo '<tr bgcolor='.$bgcolor.'>';
								echo '<td class=backend_data><img src="images/'.(($file->visible=='Y')?'tick':'cross') .'.gif" alt="'.(($file->visible=="Y")?"Visible":"Invisible").'" width=15 height=15></td>';
								echo '<td class=backend_data>'.$file->id.'</td>';
								echo '<td class=backend_data><img src="'.lib_href('images/file_types/'.((file_exists($WEB_PATH.'/__lib/images/file_types/'.$file->type.'.gif'))?$file->type:'file').'.gif').'" width=16 height=16></td>';
								echo '<td class=backend_data width=100%><a href="page.php?fileid='.$file->id.'&file_edit=1">'.$file->filename.'</a></td>';
								if($this->page->write_access()) {
									echo '<td align=center><input type=checkbox name="delete_files['.$file->id.']"></td>';
								}
								echo '</tr>';
							} else {
								$orphaned[] = $id;
							}
							$registered[] = $file->filename;
						}
					}

					$unregistered_files = array_diff($dir_list, $registered);

					/* new files that mysource doesn't know anything about but are on the filesystem.. */
					if (!empty($unregistered_files)) {
						foreach($unregistered_files as $new_file_name) {
							if ($var == 0) {
								$var = 1;
								$bgcolor = '#dbdbeb';
							} else {
								$var = 0;
								$bgcolor = '#d0d0e0';
							}

							echo '<tr bgcolor='.$bgcolor.'>';
							echo '<td class=backend_data><img src="images/grey_cross.gif" alt="File not registered" width=15 height=15></td>';
							echo '<td class=backend_data>&nbsp;</td>';
							echo '<td class=backend_data><img src="'.lib_href('images/file_types/file.gif').'" width=16 height=16></td>';
							echo '<td class=backend_data width=100%>';
							if($this->page->write_access()) {
								echo '<a href="page.php?p='.$this->page->id.'&file_edit=1&PARAMETER_SCREEN=page_attachments&new_file='.urlencode($new_file_name).'">'.$new_file_name.'</a>';
							} else {
								echo $new_file_name;
							}
							echo ' ** Currently Unregistered</td>';
							echo '<td align=center>&nbsp;</td>';
							echo '</tr>';
						}
					}

					/* orphaned files are ones that mysource knows about, but are really missing from the filesystem. */
					if (!empty($orphaned)) {
						foreach($orphaned as $fid) {
							if ($var == 0) {
								$var = 1;
								$bgcolor = '#dbdbeb';
							} else {
								$var = 0;
								$bgcolor = '#d0d0e0';
							}
							$file = &$this->get_file($fid);

							echo '<tr bgcolor='.$bgcolor.'>';
							echo '<td class=backend_data><img src="images/grey_cross.gif" alt="Orphaned Record" width=15 height=15></td>';
							echo '<td class=backend_data>'.$file->id.'</td>';
							echo '<td class=backend_data><img src="'.lib_href('images/file_types/'.((file_exists($WEB_PATH.'/__lib/images/file_types/'.$file->type.'.gif'))?$file->type:'file').'.gif').'" width=16 height=16></td>';
							echo '<td class=backend_data width=100%><a href="page.php?fileid='.$file->id.'&file_edit=1">'.$file->filename.'</a> is missing from the data directory</td>';
							if($this->page->write_access()) {
								echo '<td align=center><input type=checkbox name="delete_files['.$file->id.']"></td>';
							}
							echo '</tr>';
						}
					}

					if(!empty($this->page->file_index) && $this->page->write_access()) {
						echo '<tr><td colspan="4">&nbsp;</td><td align="center">';
						$backend->print_icon_button("javascript: if (confirm('Are you sure you want to delete the selected files?') && confirm('Really sure? This is irreversible.')) {document.edit.action.value='Delete Files';document.edit.submit()}",'Delete selected files','delete');
						echo '</td></tr>';
					}
					
					echo '</table>';
				} else {
					echo 'None.';
				}

				$backend->open_field('New File','one_liner');

				echo '<table><tr><td>';
				$backend->print_icon_button("javascript: document.edit.action.value='New File';document.edit.submit();",'New File','create');
				echo '</td></tr></table>';

				break;

			 #############################
			# The Page Security Screen
			case 'page_security':

				if($action == 'Commit') {
					if($this->page->admin_access()) {
						$backend->add_message($this->page->set_public($_POST['public']));
						$backend->add_message($this->page->update_access_grants($_POST['access_grants']));
					}

					if($this->page->admin_access()) {

						$users_system = &get_users_system();
						if (!empty($_POST['delete_editorids'])) {
							foreach($_POST['delete_editorids'] as $id) {
								$backend->add_message($this->page->remove_editorid($id));
							}
						}

						$new_editor = $_POST['new_editor'];
						$new_readonly_editor = $_POST['new_readonly_editor'];
						if($new_editor)	{
							$user = &$users_system->get_user($new_editor);
							$readonly_editor = 0;
						}
						if($new_readonly_editor) {
							$user = &$users_system->get_user($new_readonly_editor);
							$readonly_editor = 1;
						}
						if($user->id) {
							$backend->add_message($this->page->add_editorid($user->id, $readonly_editor));
						} elseif($new_editor || $new_editor = $new_readonly_editor) {
							$backend->add_message('Couldn\'t find a user (to make a page editor) with login or email of: '.$new_editor);
						}

					}

					if($site->admin_access()) {
						if (!empty($_POST['delete_adminids'])) {
							foreach($_POST['delete_adminids'] as $id) {
								$backend->add_message($this->page->remove_adminid($id));
							}
						}

						# check for changes to email settings
						$backend->add_message($this->page->update_parameter_adminids('email_adminids', $_POST['email_adminids']));

						$new_administrator = $_POST['new_administrator'];
						if($new_administrator)	{
							$user = &$users_system->get_user($new_administrator);
							if($user->id) {
								$backend->add_message($this->page->add_adminid($user->id));
							} elseif($new_administrator) {
								$backend->add_message('Couldn\'t find a user (to make a page administrator) with login or email of: '.$new_administrator);
							}
						}
					}

					$this->page->updated();
				}

				$backend->print_header();

				$backend->open_section('Read Access Grants');

				if(!$this->page->effective_public() && !count($this->page->effective_access_grants())) {
					$backend->open_field('<span class=warning>WARNING</span>','top');
					echo('<span class=warning>You have not granted access to the public <i>or</i> any of the access groups on this page. No one can see it on the frontend. Make sure the the parent page or site also grants access.</span>');
				}

				$backend->open_field('Public');
				$bracket_colour = (($this->page->parent_public())?'green':'red');
				echo('<span style="color:'.$bracket_colour.'"><b>[<input type=checkbox value="1" name=public '.(($this->page->public)?'checked':'').' '.(($site->admin_access())?'':'onfocus="alert(\'Permission denied.\');blur();"').'>]</b></span> ');

				$parent_access_grants = &$this->page->parent_access_grants();
				$parent_public = $this->page->parent_public();
				$general_access_groups = &$this->web_system->get_general_access_group_list();
				if($this->page->admin_access() || !empty($general_access_groups)) {
					$backend->open_field('Gen. Access Groups','top');
					if(empty($general_access_groups)) {
						echo('None.');
					} else {
						foreach($general_access_groups as $groupid => $name) {
							$granted = in_array($groupid, $this->page->access_grants);
							$bracket_colour = ((in_array($groupid,$parent_access_grants) || $parent_public)?'green':'red');
							echo('<span style="color:'.$bracket_colour.'"><b>[<input type=checkbox value="'.$groupid.'" name=access_grants[] '.(($granted)?'checked':'').' '.(($site->admin_access())?'':'onfocus="alert(\'Permission denied.\');blur();"').'>]</b></span> ');
							echo('<a href="access_group.php?groupid='.$groupid.'">'.$name.'</a>');
							echo('<br>');
						}
					}
				}

				if($this->page->admin_access() || !empty($site->access_groups)) {
					$backend->open_field('Site Access Groups','top');
					if(empty($site->access_groups)) {
						echo('None.');
					} else {
						foreach($site->access_groups as $groupid => $name) {
							$granted = in_array($groupid, $this->page->access_grants);
							$bracket_colour = ((in_array($groupid,$parent_access_grants) || $parent_public)?'green':'red');
							echo ('<span style="color:'.$bracket_colour.'"><b>[<input type=checkbox value="'.$groupid.'" name=access_grants[] '.(($granted)?'checked':'').' '.(($site->admin_access())?'':'onfocus="alert(\'Permission denied.\');blur();"').'>]</b></span> ');
							echo('<a href="access_group.php?groupid='.$groupid.'">'.$name.'</a>');
							echo('<br>');
						}
					}
				}

				if($this->page->readonly_editor_access()) {
					$backend->open_field('Front-end Page Editors','top');
					$users_system = &get_users_system();
					$users_system->display_users_table($this->page->readonly_editorids,(($this->page->admin_access())?'delete_editorids':''),'Delete?');

					if($this->page->admin_access()) {
						$backend->open_field('New Front-end Editor?<span class=backend_fineprint><br>(login/email)</span>','top');
						echo text_box('new_readonly_editor','',20,128, 'class=backend_data');
					}
				}

				if($this->page->editor_access()) {
					$backend->open_section('Write Access Permissions');
					$backend->open_field('Page Administrators','top');
					$users_system = &get_users_system();
					$checkboxes='';
					$checkbox_names='';
					if ($site->admin_access()) {
						$checkbox_names = array('delete_adminids'=>array(), 'email_adminids'=>array());
						$checkboxes = array('Delete?', 'Email?');
						if (!empty($this->page->adminids)) {
							foreach ($this->page->adminids as $id) {
								$parameters = unserialize($this->page->adminids_parameters[$id]);
								while(list($name, $value) = each ($parameters)) {
									if ($parameters[$name] == 1) {
										array_push($checkbox_names[$name], $id);
									}
								}
							}
						}
					}
					$users_system->display_users_table($this->page->adminids,$checkbox_names,$checkboxes);

					if($site->admin_access()) {
						$backend->open_field('New Administrator?<span class=backend_fineprint><br>(login/email)</span>','top');
						echo text_box('new_administrator','',20,128, 'class=backend_data');
					}

					$backend->open_field('Page Editors','top');
					$users_system = &get_users_system();
					$users_system->display_users_table($this->page->editorids,(($this->page->admin_access())?'delete_editorids':''),'Delete?');

					if($this->page->admin_access()) {
						$backend->open_field('New Editor?<span class=backend_fineprint><br>(login/email)</span>','top');
						echo text_box('new_editor','',20,128, 'class=backend_data');
					}

				}
				break;


			 #####################################
			# The 'Page Statisitcs' screen
			case 'wizards':
				# Don't wanna be commitin' nothin'!
				$backend->set_hidden_field('action','');

				global $INCLUDE_PATH;
				include_once("$INCLUDE_PATH/wizard.inc");
				$wizard = new Wizard($this->page);
				$type_set = $wizard->type_set();
				if (!$type_set) $backend->print_header();
				$wizard->print_backend();
				if (!$type_set) $backend->print_footer();

				return;


			 #####################################
			# The default 'Page Properties' screen
			case 'page_properties': 
			default:
				$floating_site_map_refresh = false;

				if($action == 'Commit') {

					if($this->page->write_access()) {
						$name = $_POST['name'];
						$short_name = $_POST['short_name'];
						$page_notes = $_POST['page_notes'];
						$visible = $_POST['visible'];
						$usessl = $_POST['usessl'];
						$imageid = $_POST['imageid'];
						$description = $_POST['description'];
						$keywords = $_POST['keywords'];
						$extract_keywords = $_POST['extract_keywords'];
						$process_duplicates = $_POST['process_duplicates'];

						# Name
						$name_changed = $this->page->set_name(gpc_stripslashes($name),gpc_stripslashes($short_name));
						if ($name_changed != '') {
							$floating_site_map_refresh = true;
							$backend->add_message($name_changed);
						}

						# Page Notes
						$notes_changed = $this->page->set_page_notes(gpc_stripslashes($page_notes));
						if ($notes_changed != '') {
							$floating_site_map_refresh = true;
							$backend->add_message($notes_changed);
						}

						# Visible
						$visible_changed = $this->page->set_visible($visible);
						if ($visible_changed != '') {
							$floating_site_map_refresh = true;
							$backend->add_message($visible_changed);
						}

						# SSL
						$backend->add_message($this->page->set_usessl($usessl));

						# Image
						$backend->add_message($this->page->set_image(gpc_stripslashes($imageid)));

						# Description
						$description_changed = $this->page->set_description(gpc_stripslashes($description));
						if ($description_changed != '') {
							$floating_site_map_refresh = true;
							$backend->add_message($description_changed);
						}

						# Keywords
						$keywords = gpc_stripslashes($keywords);
						if($extract_keywords) {
							$template = &$this->page->get_template();
							if ($new_keywords = retrieve_keywords($template->extract_keywords())) {
								$keywords = (empty($keywords)) ? $new_keywords : $keywords . ' '.$new_keywords;
							} else {
								$backend->add_message('Could not extract any keywords from the page template.');
							}
						}

						$backend->add_message($this->page->set_keywords($keywords,$process_duplicates));

						# Template
						$template = $_POST['template'];
						$template_changed = $this->page->set_template($template);
						if($template_changed != '') {
							$floating_site_map_refresh = true;
							$backend->add_message($template_changed);
						}

						# The thumbnail
						$upload_result = process_image_upload('thumbnail', $this->page->data_path.'/thumbs/page_thumb', false);
						$backend->add_message($upload_result['message']);

						$dirs = $_REQUEST['dirs'];
						$ignore_dirs = $_REQUEST['ignore_dirs'];
						if($system_config->virtual_paths && $this->page->editor_access() && !$ignore_dirs) {
							$backend->add_message($this->page->update_dirs($dirs));
						}

					}

					 ########################################################
					# Status changing.. we'll need CAREFUL security for this
					$change_status_options = $this->page->change_status_access();
					$status = $_POST['status'];
					if($status && $change_status_options[$status]) {
						$old_status = $this->page->status();
						list($message,$redirect_pageid) = $this->page->add_status($status);
						if ($old_status != $this->page->status()) $floating_site_map_refresh = true;
						$backend->add_message($message);
						if($redirect_pageid) {
							$redirect_page = &$this->get_page($redirect_pageid);
							$backend->set_relocation($redirect_page->get_backend_href());
							if ($floating_site_map_open) {
								$backend->set_javascript('refresh_site_map('.$this->page->siteid.')');
							}#endif
							$backend->print_header();
							$backend->print_footer();
							exit;
						}
					}

					if($site->admin_access()) {
						$remove_future_statii = $_POST['remove_future_statii'];
						if (!empty($remove_future_statii)) {
							foreach($remove_future_statii as $date => $remove) {
								if($remove) $backend->add_message($this->page->remove_status($date));
							}
						}
						$new_future_status = $_POST['new_future_status'];
						$new_future_status_date = $_POST['new_future_status_date'];
						if($new_future_status) {
							list($message, $relocation) = $this->page->add_status($new_future_status,$new_future_status_date);
							$backend->add_message($message);
							if($relocation) {
								$backend->set_relocation($this->page->get_backend_href());
								if ($floating_site_map_open) {
									$backend->set_javascript('refresh_site_map('.$this->page->siteid.')');
								}#endif
								$backend->print_header();
								$backend->print_footer();
								exit();
							}
						}
					}

					# Reload this, if need be.
					$site->clear_page_index();
					$index = &$site->get_page_index();

					$designid = $_POST['designid'];
					if ($site->admin_access()) {
						if(!$designid || in_array($designid, $site->allowed_designids)) {
							$backend->add_message($this->page->set_design($designid));
						}
					}

					# Move to another site
					if ($site->editor_access()) {
						$new_siteid = $_POST['new_siteid'];
						$status = $_POST['status'];
						$new_future_parentid = $_POST['new_future_parentid'];
						$new_future_parentid_date = $_POST['new_future_parentid_date'];
						$remove_future_parentids = $_POST['remove_future_parentids'];

						if (!empty($remove_future_parentids)) {
							foreach($remove_future_parentids as $date => $remove) {
								if($remove) $backend->add_message($this->page->remove_parentid($date));
							}
						}
						if($new_future_parentid || $new_future_parentid == '0') {
							$backend->add_message($this->page->add_parentid($new_future_parentid,$new_future_parentid_date));
						}
						if ($new_siteid && $this->page->siteid != $new_siteid) {
							list($result,$msg) = $this->page->site_move($new_siteid);
							if ($result) {
								$message = $this->page->add_status('U');
							}
							if($result && $message) {
								$backend->add_message($msg);
								$backend->add_message($message);
								$backend->set_relocation($this->page->get_backend_href());
							}
							$status = '';
						}#end if

					}#end if

					# New subpages
					if($site->editor_access()) {
						if ($this->page->replaceid && !empty($_POST['new_subpage_names'])) {
							$backend->add_message(MYSOURCE_ERROR_CODE_ERROR, 'Unable to create subpages for a SafeEdited page');
						}
						if (!$this->page->replaceid) {
							$new_subpage_names = $_POST['new_subpage_names'];
							$new_subpage_template = $_POST['new_subpage_template'];
							if($new_subpage_names && (superuser('web') || in_array($new_subpage_template,$site->allowed_templates))) {
								$new_subpage_count = 0;
								$new_sub_page_names = split("[\r\n]+",$new_subpage_names);
								if (!empty($new_sub_page_names)) {
									foreach($new_sub_page_names as $new_subpage_name) {
										if(!($new_subpage_name = gpc_stripslashes(trim($new_subpage_name)))) continue;
										// Create the page
										list($error_code, $msg, $new_subpageid) = $site->create_page($new_subpage_name, $new_subpage_template, $this->page->id, 'U', false);
										$backend->add_message(array($error_code, $msg));

										# only add if no errors occured
										if ($error_code == MYSOURCE_ERROR_CODE_NONE) {
											// Add the page to the site page index
											$new_page_count++;
										}#end if
									}
								}
								if($new_subpage_count > 1) {
									$backend->add_message($new_subpage_count.' new subpages created.');
								} elseif($new_subpage_count == 1 && $new_subpageid) {
									$backend->set_relocation('page.php?p='.$new_subpage->id);
								# else do nothing
								}#end if
								if ($new_page_count >= 1) {
									$floating_site_map_refresh = true;
								}#end if
							}
						}#end if
					}#endif

					$this->page->updated();
					$site->write_page_index_to_cache();
					$index = &$site->get_page_index();

				} elseif ($action == 'Connect') {
					$parentid = $_POST['parentid'];
					$childid = $_POST['childid'];
					$child = &$this->get_page($childid);
					$backend->add_message($child->page_move($parentid));

					# In case things have changed.
					$backend->set_hidden_field('pageid',$this->page->id);
					$backend->set_hidden_field('siteid',$this->page->siteid);
					$backend->set_hidden_field('parentid',$this->page->parentid);
					$this->page->updated();
					$site->clear_page_index();
					$index = &$site->get_page_index();
				} elseif ($action == 'dock_floating_site_map') {
					$session->unset_var('floating_site_map_open');
				}

				 ########################################
				# For the tree navigator thingy
				$active_pageid = $_POST['active_pageid'];
				$backend->set_hidden_field('childid');
				$backend->set_hidden_field('parentid');
				$backend->set_hidden_field('active_pageid',$active_pageid);
				$backend->set_hidden_field('open_pageid');
				$backend->set_hidden_field('close_pageid');
				$backend->set_hidden_field('expand_all_pageid');
				$backend->set_hidden_field('collapse_all_pageid');
				$backend->set_hidden_field('undock_site_map',0);

				# Now print the 'Page Properties' screen.

				if ($floating_site_map_open && $floating_site_map_refresh) {
					$backend->set_javascript('refresh_site_map('.$this->page->siteid.')');
				}#endif

				$backend->print_header();

				 #########
				# STATII!
				$backend->open_section('Status');
				$backend->open_field('Currently','one_liner');
				global $PAGE_STATUSES, $PAGE_STATUS_COLOUR;
				$status_colour = Colour::adjust_lightness(Colour::adjust_saturate($PAGE_STATUS_COLOUR[$this->page->status()],1),-0.5);
				echo('<b><span style="color:'.$status_colour.'">'.$PAGE_STATUSES[$this->page->status()].'</span></b>');
				if($this->page->effective_status() != $this->page->status() ) {
					$effective_status_colour =Colour::adjust_lightness(Colour::adjust_saturate($PAGE_STATUS_COLOUR[$this->page->effective_status()],1),-0.5);
					if ($this->page->parentid) {
						echo(', but parent is <span style="color:'.$effective_status_colour.'">'.$PAGE_STATUSES[$this->page->effective_status()].'</span>');
					}
				}
				if($this->page->status() == 'R') { # put a link to the replacement page
					if($replacementid = $db->single_element("SELECT pageid FROM page WHERE replaceid='".$this->page->id."'")) {
						$replacement_name = &$index[$replacementid]['name'];
						$backend->open_field('SafeEdited at');
						echo('<a href="page.php?p='.$replacementid.'">'.$replacement_name.'</a> (ID: '.$replacementid.')');
					}
				}
				if($this->page->replaceid) {
					$replace_name = &$index[$this->page->replaceid]['name'];
					$backend->open_field('SafeEditing for','one_liner');
					echo('<a href="page.php?p='.$this->page->replaceid.'">'.$replace_name.'</a> (ID: '.$this->page->replaceid.')');
					echo('<br /><br /><i>To delete this page, you will need to go to <a href="page.php?p='.$this->page->replaceid.'">'.$replace_name.'</a> and Choose "Cancel Safeedit"</i><br />');
				}

				$change_status_options = $this->page->change_status_access();
				if(!empty($change_status_options)) {
					$backend->open_field('Change?','one_liner');
					echo combo_box('status',array(''=>'')+$change_status_options,$this->page->status() ,'class=backend_data');
				}

				$backend->open_field('Future Statuses');
				$future_statii = &$this->page->get_actions('status',date('Y-m-d H:i:s',mktime()+1));
				if(count($future_statii) >= 1) {
					echo '<table>';
					foreach($future_statii as $date => $data) {
						echo '<tr>';
						$status_colour = Colour::adjust_lightness(Colour::adjust_saturate($PAGE_STATUS_COLOUR[$data['action_value']],1),-0.5);
						echo '<td><b><span style="color:'.$status_colour.'">'.$PAGE_STATUSES[$data['action_value']].'</span></b></td><td>'.$date.'</td>';
						if($site->admin_access()) echo '<td><input type=checkbox name="remove_future_statii['.$date.']" value=1>Remove?</td>';
						echo '</tr>';
					}
					echo '</table>';
				} else {
					echo 'None.';
				}

				if($site->admin_access()) {
					$backend->open_field('New Future Status');
					echo '<table>';
					end($future_statii);
					$last_date = key($future_statii);

					echo '<tr><td>'.combo_box('new_future_status',array(''=>'')+$PAGE_STATUSES,'','class=backend_data').'</td>';

					echo '<td>'.date_box('new_future_status_date',(($last_date)?$last_date:date('Y-m-d H:i:s')),'edit',date('Y'),true).'</td></tr>';
					echo '</table>';
				}

				 ##################################
				# Standard page attributes
				$backend->open_section('Identification');

				$backend->open_field('Name','one_liner');
				if($this->page->write_access())
					echo text_box('name',$this->page->name,50,128,'class=backend_data');
				else
					echo ('<span class=backend_data>'.$this->name.'</span>');

				$backend->open_field('Short Name','one_liner');
				if($this->page->write_access())
					echo text_box('short_name',$this->page->short_name,40,40,'class=backend_data');
				else
					echo ('<span class=backend_data>'.$this->page->short_name.'</span>');

				$backend->open_field("Page Notes:<span class=backend_fineprint><br>(not shown on front-end)</span>","top");
				if($site->admin_access())
					echo text_area(page_notes,$this->page->page_notes,50,6,0,"class=backend_data");
				else
					echo nl2br('<span class=backend_data>'.$this->page->page_notes.'</span>');

				$backend->open_field('Show in Menu','one_liner');
				$bracket_colour = (($this->page->parent_visible())?'green':'red');
				echo('<span style="color:'.$bracket_colour.'"><b>[');
				if($this->page->write_access()) {
					echo ('<input type=checkbox value="1" name=visible '.(($this->page->visible)?'checked':'').' '.(($site->admin_access())?'':'onfocus="alert(\'Permission denied.\');blur();"').'>');
				} else {
					echo ('<img src="images/'.(($this->page->visible) ? 'tick' : 'cross').'.gif" alt="'.(($this->page->visible)?'Visible':'Invisible').'" width=15 height=15>');
				}
				echo (']</b></span>');

				$backend->open_field('SSL Encryption','one_liner');
				if($this->page->write_access()) {
					echo ('<input type=checkbox value="1" name=usessl '.(($this->page->usessl)?'checked':'').' '.(($site->admin_access())?'':'onfocus="alert(\'Permission denied.\');blur();"').'>');
				} else {
					echo ('<img src="images/'.(($this->page->usessl) ? 'tick' : 'cross').'.gif" alt="" width=15 height=15>');
				}

				$backend->open_field('Image','one_liner');
				$images = Array();
				if (!empty($this->page->file_index)) {
					foreach($this->page->file_index as $id) {
						$file = &$this->get_file($id);
						if (is_image($file->filename)) $images[$id] = $file->filename;
					}
				}
				if (!empty($images)) {
					$images['0'] = '[ None Selected ]';
					asort($images);
					if($this->page->write_access()) {
						echo combo_box('imageid', $images, $this->page->imageid);
					} else {
						echo $images[$this->page->imageid];
					}
				# no images loaded
				} else {
					echo 'No Images Attached to Page';
				}#end if
				echo '<br><span class=backend_fineprint>(Only useful if needed by design)</span>';

				$backend->open_field('Description');
				if($this->page->write_access())
					echo text_area('description',$this->page->description,50,6,0,'class=backend_data');
				else
					echo nl2br('<span class=backend_data>'.$this->page->description.'</span>');
				echo '<br><span class=backend_fineprint>(Used for META tags)</span>';


				$backend->open_field('Keywords');
				if($this->page->write_access()) {
					echo text_area('keywords',$this->page->keywords,50,6,0,'class=backend_data');
					echo('<input type=hidden name=extract_keywords>');
					$backend->print_icon_button('javascript: document.edit.extract_keywords.value=1;document.edit.submit();','Extract keywords from page contents','page');
					echo('<span class=backend_smallprint><br><input type=checkbox name=process_duplicates>Process and remove duplicates</span>');
				} else {
					echo nl2br('<span class=backend_data>'.$this->page->keywords.'</span>');
				}
				echo '<br><span class=backend_fineprint>(Used for META tags)</span>';

				$backend->open_field('Template','one_liner');
				if ($this->page->write_access() && (superuser('web') || count($site->allowed_templates) > 0)) {
					echo combo_box('template',$XTRAS->list_type('page/templates',((superuser('web'))?Array():array_merge(array($this->page->template),$site->allowed_templates))),$this->page->template, 'class=backend_data');
					#echo('<span class=warning><br>(WARNING: Changing could cause data loss.)</span>');
				} else {
					echo '<p class=backend_data>'.$XTRAS->name('page/templates',$this->page->template);
				}

				if($this->page->write_access()) {
					$backend->open_field('Thumbnail','top');
					echo image_upload('thumbnail', $this->page->data_path.'/thumbs/page_thumb',$this->page->get_file_href('thumbs/'),20000,100,100);
				}


				$backend->open_section('Custom Design');

				$backend->open_field('Design','one_liner');
				$site_designs = &$this->web_system->get_site_design_list();
				# let's see if we can get our effect
				$effective_design_pageid = &$index[$this->page->id]['effective_design_pageid'];
				if ($effective_design_pageid) {
					$effective_designid = $index[$effective_design_pageid]['designid'];
				# let's get from our site
				} else {
					$effective_designid = $site->designid;
				}#end if

				if ($site->admin_access() && !empty($site->allowed_designids)) {
					$extra = ($effective_design_pageid != $this->page->id) ? ' {'.$site_designs[$effective_designid].'}' : '';
					$designs = array('0' => '-- Inherit --'.$extra);
					if (!empty($site->allowed_designids)) {
						foreach($site->allowed_designids as $designid) {
							if (!$site_designs[$designid]) continue;
							$designs[$designid] = $site_designs[$designid];
						}
					}
					echo combo_box('designid', $designs, $this->page->designid, 'class=backend_data');
				} else {
					echo('<p class=backend_data>'.$site_designs[$effective_designid]);
					if (!$this->page->designid) {
						echo(' (inherited)');
					}
				}

				$pages_section_link = $backend->open_section('Position','page');

				$backend->open_field('Page Lineage','one_liner');
				$lineage = $this->page->get_lineage();
				echo('<a href="site.php?s='.$site->id.'"><i>'.$site->name.'</i></a>');
				if (!empty($lineage)) {
					foreach($lineage as $parentid => $parent_name) {
						if ($parentid == $this->page->id) break; # don't show ourselves
						echo(' : <a href="page.php?p='.$parentid.'">'.$parent_name.'</a>');
					}
				}

				 ##############################################################
				# Display a list of page moves which are to occur in the future
				$backend->open_field('Future Page Lineages','top');
				$future_parentids = &$this->page->get_actions('parentid',date('Y-m-d H:i:s',mktime()+1));
				if(!empty($future_parentids)) {
					echo '<table>';
					foreach($future_parentids as $date => $data) {
						echo '<tr>';
						if($data['action_value']) {
							$parent_page = &$this->get_page($data['action_value']);
							$future_lineage = $parent_page->get_lineage();
						}
						echo('<td><a href="site.php?s='.$site->id.'"><i>'.$site->name.'</i></a>');
						if (!empty($future_lineage)) {
							foreach($future_lineage as $parentid => $parent_name) {
								if ($parentid == $this->page->id) break; # don't show ourselves
								echo(' : <a href="page.php?p='.$parentid.'">'.$parent_name.'</a>');
							}
						}
						unset($future_lineage);
						echo '</td>';
						echo '<td>'.$date.'</td>';
						if($site->admin_access()) echo '<td><input type=checkbox name="remove_future_parentids['.$date.']" value=1>Remove?</td>';
						echo '</tr>';
					}
					echo '</table>';
				} else {
					echo 'None.';
				}

				 ################################################################################################
				# Print out a datetime box where the user can select the date/time on which they want the page to move
				if($site->admin_access()) {
					if (!$this->page->replaceid) {
						$backend->open_field('New Future Page Lineage');
						echo '<table>';
						end($future_parentids);
						$last_date = key($future_parentids);
						echo '<tr><td>';
						echo combo_box('new_future_parentid', array(''=>'','0'=>$site->name) + $this->web_system->page_array_with_sticks($site->id, 0, '', '', true), '','class=backend_data',30);
						echo '</td>';
						echo '<td>'.date_box('new_future_parentid_date',(($last_date)?$last_date:date('Y-m-d H:i:s')),'edit',date('Y'),true).'</td></tr>';
						echo '</table>';
					} else {
						?>
						<span class="warning">
							Because we are SafeEditing for <a href="page.php?p=<?=$this->page->replaceid?>"><?=$replace_name?></a> (ID: <?=$this->page->replaceid?>)<br>
							we cannot move this page.
						</span>
						<?
					}
				}

				 ##################################################################################
				# Move to another site, only if this page is not participating in any SafeEditing
				if ($site->admin_access() && $this->page->status() != 'R' && !$this->page->replaceid) {
					$editable_sites = $this->web_system->get_editable_sites();
					unset($editable_sites[$this->page->siteid]);
					if(!empty($editable_sites)) {
						$backend->open_field('Move to site?','one_liner');
						echo combo_box('new_siteid', array(''=>'') + $editable_sites, $this->page->siteid);
					}
				}#end if



				if($site->admin_access() && count($this->page->get_subpageids()) > 1) {
					$backend->open_field('Reorder Descendants','one_liner');
					$backend->print_icon_button('javascript: popup_page_orderer('.$this->page->siteid.','.$this->page->id.')','Reorder the subpages','reorder'); 
					if ($this->page->subpage_auto_order == 'az') 
						echo '&nbsp;(<span class="backend_field">Auto:</span> A-Z)';
					elseif ($this->page->subpage_auto_order == 'za') 
						echo '&nbsp;(<span class="backend_field">Auto:</span> Z-A)';
				}

				$floating_site_map_open   = $session->get_var('floating_site_map_open');
				$floating_site_map_siteid = $session->get_var('floating_site_map_siteid');

				if ($floating_site_map_open) {
					if ($floating_site_map_siteid != $this->page->siteid) {
						js('undock_site_map('.$site->id.')');
					}

					$backend->open_field('Page Descendants<span class=backend_fineprint><br>(Alt + H)</span>','top');
					?>&nbsp;&nbsp;<a href="javascript:dock_site_map(<?=$this->page->siteid?>);"><img src="images/icons/dock.gif" border="0"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:undock_site_map(<?=$this->page->siteid?>);"><img src="images/icons/bring2front.gif" border="0"></a><?
				} else {

					$site_map_jump = $backend->open_field('Page Descendants<span class=backend_fineprint><br>(Alt + H)</span>','top');

					$open_pageid = $_POST['open_pageid'];
					$close_pageid = $_POST['close_pageid'];
					if($open_pageid || $close_pageid) {
						$backend->set_jumpto($site_map_jump);
					}
					?>
					<table cellpadding=0 cellspacing=0 border=0>
						<tr>
						<? if (!$this->page->replaceid) { ?>
							<td><a href="javascript:if (connect_child > 0) {document.edit.action.value='Connect';document.edit.childid.value=connect_child;document.edit.parentid.value='<?=$this->page->id?>';document.edit.submit()}" onmouseover="if (connect_child > 0) {window.status='Make \''+connect_child_name+'\' a direct subpage.';} else{window.status='';} return true;" onmouseout="window.status='';return true;"><img src="images/icons/page.gif" width=20 height=20 border=0></a></td>
							<td nowrap><p class=backend_data><b>This Page</b> (<?=$this->page->name?>)</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td><a href="javascript:expand_all_page('<?=$this->page->id?>');"><img src="images/expand_all.gif" width="82" height="19" border="0"></a></td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td><a href="javascript:collapse_all_page('<?=$this->page->id?>');"><img src="images/collapse_all.gif" width="88" height="19" border="0"></a></td>
						<? } ?>
							<td align="right" width="100%">&nbsp;<a href="javascript:undock_site_map(<?=$this->page->siteid?>);"><img src="images/icons/undock.gif" border="0"></a></td>
						</tr>
					</table>
					<table cellpadding=0 cellspacing=0 border=0 width=100%>
					<?
					$subpages = $this->page->get_subpageids();
					$site->print_page_hierarchy($subpages);
					?></table><?
				}

				if($site->editor_access()) {
					$backend->open_field('New Subpages?','top');
					if(!$this->page->replaceid) {
						echo combo_box('new_subpage_template', $XTRAS->list_type('page/templates',((superuser('web'))?Array():$site->allowed_templates)), 'standard', 'class=backend_data').'<br>';
						echo text_area('new_subpage_names','', 50, 5, 0,'class=backend_data');
					} else {
						?>
							<span class="warning">
								Because we are SafeEditing for <a href="page.php?p=<?=$this->page->replaceid?>"><?=$replace_name?></a> (ID: <?=$this->page->replaceid?>)<br>
								we cannot create subpages of this page.
							</span>
						<?
					}
				}

				# add link to view the page update logs
				if($system_config->email_page_update && version_compare($XTRAS->version('site/design_areas','email_page_update'), 1.1) > 0){
					$web = &get_web_system();
					$site = &$web->get_site();
					
					# hmm
					$p = ($_GET['p']) ? $_GET['p'] : $_GET['pageid'];
					
					$emu = &$web->get_extension('email_page_update');
					
					$backend->open_section('Page Updates','page');
					$backend->open_field('Updates Allowed?', 'top');
					
					if ($emu->paint_page_list_interface($p)) {
						$backend->open_field('New Update', 'top');
						$emu->inline_add_update();
						
						$backend->open_field('Page Update History','top');
						
						$link = $site->get_url(true) . "p=" . $p . "&mysource_web_extension=email_page_update&view_updates=1";

						if ($emu->print_summary(5, $p)) { // only show this section if there are some page updates for this page
							echo "<BR><BR><span class=\"backend_data\"><a href=\"#\"  onClick=\"Javascript: window.open('" . $link . "', 'Page_Update', 'width=600, height=300, scrollbars=1, resizable=1'); return false;\">Full Page Update History</a></span>";
						}
					}
				}

				if($system_config->virtual_paths) {
					$backend->open_section('Virtual Paths','preview');

					# if we are safe editing for a page then we don't set the virtual paths
					# because we will use their's when we are made live
					if($this->page->replaceid) {
						$replace_name = &$index[$this->page->replaceid]['name'];
						$backend->open_field('&nbsp;','one_liner');
					?>
						<input type="hidden" name="ignore_dirs" value="1">
						<span class="warning">
							Because we are SafeEditing for <a href="page.php?p=<?=$this->page->replaceid?>"><?=$replace_name?></a> (ID: <?=$this->page->replaceid?>)<br>
							we cannot have virtual paths for this page
						</span>
					<?

					} else {
						if($this->page->write_access()) {
							?><input type="hidden" name="ignore_dirs" value="0"><?
							$dir_count = 1;
							if (!empty($this->page->dirs)) {
								foreach($this->page->dirs as $dir) {
									$backend->open_field($dir_count++ . '.','one_liner');
									echo text_box('dirs[]',$dir,20,128,'class=backend_data');
								}
							}
							$max_dir_count = count($this->page->dirs) + 1;
							while($dir_count <= $max_dir_count) {
								$backend->open_field($dir_count++ . '.','one_liner');
								echo text_box('dirs[]','',20,128,'class=backend_data');
							}
						} else {
							?><input type="hidden" name="ignore_dirs" value="1"><?
							$dir_count = 1;
							if (!empty($this->page->dirs)) {
								foreach($this->page->dirs as $dir) {
									$backend->open_field($dir_count++ . '.','one_liner');
									echo '<span class=backend_data>'.$dir.'</span>';
								}
							}
						}
						$paths = $this->web_system->get_url_paths_of_page($this->page->siteid, $this->page->id);
						if(empty($paths)) {
							$backend->open_field('<span class=warning>WARNING:</span>','one_liner');
							echo ('<span class=warning>This page has no virtual paths pointing to it. '.((count($this->page->dirs))?'Either the site has no URLs or one of the parent pages has no virtual paths. ':'').'You must access it using the syntax: <i>/?p='.$this->page->id.'</i> at the end of the site URL.</span>');
						} else {
							$backend->open_field('URLs<br><span class=backend_smallprint>(for testing)</span>','one_liner');
							echo('<p class=backend_data>');

							# Before deciding whether we protocol we should be, lets see what the site says.
							$site = &$this->web_system->get_site();
							$site_url = $site->get_url();

							list($protocol, $junk) = explode(':', $site_url);
							unset($site);

							/* Check if this page uses SSL */
							$url_prefix = (($this->page->usessl) ? 'https' : $protocol) . '://';
							foreach($paths as $path) {
								echo('<a href="'.$url_prefix.$path.'" target="_blank">'.strrev(ellipsisize(strrev($url_prefix.$path),60)).'</a><br>');
							}
							echo('</p>');
						}
					}
				}

				break;
		}
		$backend->print_commit_button('Commit','document.edit.submit()');
		$backend->print_footer();
		return;
	}

	/**
	* Prints an interface for ordering files
	*
	* @access  private
	*/
	function _print_file_orderer() {
		$backend = &$this->web_system->get_backend();

		$backend->set_heading('Reorder Files','file');
		$backend->set_subheading($this->page->name);
		$backend->set_id_string($this->page->id);
		$backend->hide_tabs();

		$js = "
			var temp_no = 0;
			var temp_name = 'Blah';
			var f = 'document.edit';
			function swap(a,b) {
				// Swap Pageids
				eval('temp_no = '+f+'.fileid' + a + '.value');
				eval(f+'.fileid' + a + '.value = '+f+'.fileid' + b + '.value');
				eval(f+'.fileid' + b + '.value = temp_no');
				// Swap page names
				eval('temp_name = '+f+'.name' + a + '.value');
				eval(f+'.name' + a + '.value = '+f+'.name' + b + '.value');
				eval(f+'.name' + b + '.value = temp_name');
			}

			function move_up(no) {
				if (no > 1) {
					swap(no, no - 1);
				}
			}
			function move_down(no) {
				if (no < ".count($this->page->file_index).") {
					swap(no, no + 1);
				}
			}

			var reverse = 1;
			function sort_list(sort_num) {
				reverse = sort_num;
				var nameArray = new Array();
				for(var i = 1; i < ".(count($this->page->file_index) + 1)."; i++)	{
					// put file names into array
					var obj = new Object();
					obj.name    = window.document.edit.elements['name'    + i].value;
					obj.fileid = window.document.edit.elements['fileid' + i].value;
					obj.i = i;
					nameArray.push (obj)
				}
				nameArray.sort(compare);
				// go through the sorted list and swap files into their correct positions
				for(var x = 0; x < nameArray.length; x++)	{
					window.document.edit.elements['name'    + (x+1)].value = nameArray[x].name;
					window.document.edit.elements['fileid' + (x+1)].value = nameArray[x].fileid;
				}
			}
			function compare(a,b) {
				if (a.name.toLowerCase() < b.name.toLowerCase()) {
					return (-1 * reverse);
				}
				if (a.name.toLowerCase() == b.name.toLowerCase()) {
					return 0;
				}
				if (a.name.toLowerCase() > b.name.toLowerCase()) {
					return (1 * reverse);
				}
			}
		";

		$backend->set_javascript($js);


		 ######################################################
		# Commit changes
		$action = $_POST['action'];
		if ($action == 'Commit') {
			$db = &$this->get_db();
			foreach($_POST as $key => $fileid) {
				if (substr($key, 0, 6) != 'fileid') continue;
				$orderno = substr($key, 6);

				# We'll call the file object. Why?
				# So it can update it's cache too for the change in order.
				$file =& new File($fileid);
				$file->set_orderno($orderno);
				unset($file);
			}
			$this->page->clear_cache();
			$backend->add_message('Files reordered');
			$backend->set_javascript('window.opener.refresh();window.close();');
		}

		$backend->set_subheading('', '');
		$backend->remove_toolbar_button('preview');
		$backend->remove_toolbar_button('delete');
		$backend->remove_toolbar_button('dupe');
		$backend->remove_toolbar_button('dupe_one');
		$backend->print_header();
		?>
		<p>&nbsp;
		<?
		if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'msie')) {
			$boxwidth = 40;
		} else {
			$boxwidth = 20;
		}
		$backend->open_section('List');
		$backend->open_field('<a href="javascript:sort_list(1);" onmouseover="javascript:show_tooltip(event,\'Sort Ascending\',\'Sorts the files in alphabetical order (A-Z)\',200); return true;" onmouseout="javascript:hide_tooltip(); return true;"><img src="images/icons/sorta_z.gif" border=0></a>&nbsp;<br><br><a href="javascript:sort_list(-1)" onmouseover="javascript:show_tooltip(event,\'Sort Descending\',\'Sorts the files in reverse alphabetical order (Z-A)\',200); return true;" onmouseout="javascript:hide_tooltip(); return true;"><img src="images/icons/sortz_a.gif" border=0></a>&nbsp;</p>','top');

		$i = 1;
		if (!empty($this->page->file_index)) {
			foreach($this->page->file_index as $fileid) {
				$file = &$this->get_file($fileid);
				$name = $file->filename;
				?>
				<a href="javascript:move_up(<?=$i?>)"><img src="images/uparrow.gif" alt="Move Up" border=0></a>
				<input type=hidden name="fileid<?=$i?>" value=<?=$fileid?>>
				<input type=text name="name<?=$i?>" value="<?=$name?>" size=<?=$boxwidth?> onfocus=blur()>
				<a href="javascript:move_down(<?=$i?>)"><img src="images/downarrow.gif" alt="Move Down" border=0></a><br>
				<?
				$i++;
			}
		}

		$backend->open_section('<input type=button value=Commit onclick="document.edit.action.value=\'Commit\';document.edit.submit();" class=field>');

		$backend->print_footer();

	}

}#end class

?>

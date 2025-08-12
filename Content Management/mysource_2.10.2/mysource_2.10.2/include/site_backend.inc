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
## Desc: Backend functions for dealing with a site
## $Source: /home/cvsroot/mysource/include/site_backend.inc,v $
## $Revision: 2.34 $
## $Author: dofford $
## $Date: 2004/03/16 23:59:46 $
#######################################################################
#---------------------------------------------------------------------#

 ##########################
# Global variables
global $URL_PROTOCOLS;
$URL_PROTOCOLS = array (
	'http' => 'http://',
	'https' => 'https://',
	'' => 'http(s)://',
);

/**
* Used for printing Site Backends
*
* This class is the result of splitting up the original site.inc
* into it's backend and frontend functions in order to make it smaller
* and therefore quicker to load and require less memory on the frontend
*
* This object should only be created from within Site::print_backend()
* and is passed a reference to the site that it is editing.
*
* Where you would normally reference $this you need to reference $this->site
* eg. $this->id becomes $this->site->id
*
* @access public
* @see Site::print_backend()
*
*/

class Site_Backend extends WebObject {

	/**
	* A boolean specifiying whether the backend has been setup or not
	* @var boolean
	* @see &setup_backend()
	*/
	var $_backend_setup = false;

	/**
	* A reference to the site that is currently being edited by this object
	* @var object Site
	*/
	var $site;

	/**
	* Constructor
	*
	* @param   object Site $site	The site object we are going to deal with
	* @returns object Site_Backend
	* @access  public
	*/
	function Site_Backend(&$site) {
		WebObject::WebObject();
		if (get_class($site) != 'site') {
			$this->_set_error('Attempt to use site backend with an object that is not a "site".',__FILE__,__LINE__);
		} else {
			$this->site = &$site;
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
		array_remove_element('_backend_setup', $result);
		array_remove_element('site', $result);
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
		return $this->web_system->get_page($pageid);
	}

	/**
	* Sets up the backend with all this nice things for an entity in the site
	*
	* @returns object Backend
	* @access  public
	*/
	function &setup_backend() {
		$backend = &$this->web_system->setup_backend();

		if (!$this->_backend_setup) {
			$system_config = &get_system_config();
			# if they want the DHTML navigation then use it
			if ($system_config->site_backend_dhtml_navigation) {

				global $SQUIZLIB_PATH;
				include_once("$SQUIZLIB_PATH/dhtml_menu/dhtml_menu.inc");
				$dhtml_menu = new dhtml_menu();
				$page_index = &$this->site->get_page_index();
				if (!empty($page_index)) {
					for(reset($page_index); NULL !== ($pageid = key($page_index)); next($page_index)){
						if (!$pageid) continue; # ignore the top level pages index
						$page = &$page_index[$pageid];
						$str  = '<a href="page.php?p='.$pageid.'" class="page-lineage" onMouseOver="window.status=\'Edit the General Properties for this Page\'; return true;" onMouseOut="window.status=\'\'; return true;">P</a>'
								.'&nbsp;&nbsp;'
								.'<a href="page.php?p='.$pageid.'&template_edit=1" class="page-lineage" onMouseOver="window.status=\'Edit the Page Contents for this Page\'; return true;" onMouseOut="window.status=\'\'; return true;">C</a>'
								.'&nbsp;&nbsp;'.
								$page['short_name'];
						$dhtml_menu->add_flat_entry($pageid, $page['parentid'], $str, "page.php?p=$pageid", false);
					}#end for
				}

				$dhtml_menu->set_attribute('left_position', '%anchor_left_pos%');
				$dhtml_menu->set_attribute('top_position',  '%anchor_top_pos% + 16');

				$dhtml_menu->set_attribute('menu_width', 250);
				$dhtml_menu->set_attribute('top_is_permanent', false);
				$dhtml_menu->set_attribute('top_is_horizontal', false);
				$dhtml_menu->set_attribute('top_is_variable_width', true);
				$dhtml_menu->set_attribute('tree_is_variable_width', true);
				$dhtml_menu->set_attribute('font_size', '9');
				$dhtml_menu->set_attribute('font_color', '#000000');
				$dhtml_menu->set_attribute('background_color', '#e0e0e0');
				$dhtml_menu->set_attribute('mouseover_font_color', '#ff0000');
				$dhtml_menu->set_attribute('mouseover_background_color', '#c0c0c0');
				$dhtml_menu->set_attribute('border_width', 1);
				$dhtml_menu->set_attribute('border_color', '#000000');
				$dhtml_menu->set_attribute('separator_size', 0);

				$anchor = '<a href="site.php?s='.$this->site->id.'" class="page-lineage" %link_name% onMouseOver="javascript: %onmouseover% return true;" onMouseOut="javascript: %onmouseout% return true;"><b>'.$this->site->name.'</b></a>';
				$content = $dhtml_menu->get_html(squizlib_href('dhtml_menu'), $anchor, true);

			# else they don't want the DHTML navigation so just print the site link
			} else {
				$content = '<a href="site.php?s='.$this->site->id.'" class="page-lineage"><b>'.$this->site->name.'</b></a>';
			}
			$backend->append_context_bar('page_lineage', $content);


			$backend->set_title($this->site->name.' - Edit Site');
			$backend->set_heading('Edit Site','site');
			$backend->set_name($this->site->name);
			$backend->set_id_string($this->site->id);

			$this->_backend_setup = true;

		}#end if

		return $backend;
	}

	/**
	* Returns the preview url for the current site based upon the current url
	* and the list of urls associated with the site
	*
	* @access public
	*/
	function get_preview_url() {
		global $IN_BACKEND;
		$system_config = &get_system_config();
		$site = &$this->site;
		$current_url = $_SERVER['HTTP_HOST'].str_replace("index.php", "", $_SERVER['PHP_SELF']);
		$protocol = 'http';

		# Get the url we are going to work with, either the current url
		# of the current url minus the backend_suffix
		if ($IN_BACKEND) {
			$parts = explode($system_config->backend_suffix, $current_url);
			$start_url = $parts[0];
		} else {
			$start_url = $current_url;
		}
		# loop over the url until we either match a site
		# or there is no more url to loop over
		$parts = explode('/', $start_url);
		while (!empty($parts)) {
			if (isset($site->urls[implode('/', $parts)])) break;
			$parts = array_slice($parts, 0, -1);
		}
		$url = implode('/', $parts);
		if (isset($site->urls[$url])) {
			$protocol = ($site->urls[$url] == '') ? 'http' : $site->urls[$url];
		}

		# Return the url
		if ($site->urls[$url] == $protocol || (isset($site->urls[$url]) && $site->urls[$url] == '')) {
			return $protocol . '://' . $url . '/';
		} else {
			reset($site->urls);
			return $protocol . '://' . key($site->urls) . '/';
		}
	}

	/**
	* Prints the interface for editing the site
	*
	* @access  public
	*/
	function print_backend() {
		global $XTRAS;

		$system_config = &get_system_config();
		$session = &get_mysource_session();

		$order_pages = $_REQUEST['order_pages'];
		$feature_help = $_REQUEST['feature_help'];
		$design_edit = $_REQUEST['design_edit'];
		$extension_edit = $_REQUEST['extension_edit'];
		$floating_site_map = $_REQUEST['floating_site_map'];

		 ##############
		# Die on no ID
		if(!$this->site->id) return;

		 ###################################
		# Setup the backend a little
		$backend = &$this->setup_backend(); # Backend refrence

		 ####################################################################
		# SECUIRTY - Only let in those who have permission to work on a site
		if (!$session->logged_in()) {
			$session->login_screen($backend->title, 'You must be logged in and have permission to edit the site <i>'.$this->site->name.'</i> in order to proceed.');
		}

		 ######################################################################
		# This particular user must be affiliated with an managed organisation
		if (!$this->site->staff_access()) {
			$session->login_screen($backend->title,'You are not a designated staff member of this site.');
		}

		 #########################################
		# Process a submitted form
		$action = $_REQUEST['action'];
		$parameter_screen = $_REQUEST['PARAMETER_SCREEN'];
		if($action) {
			# Check they're who we think they are
			$current_userid = $_REQUEST['current_userid'];
			if($session->user->id != $current_userid) {
				# Gasp! Someone else has logged in since this form was diaplyed.
				# They might not have had all the options and there could be problems.
				# We can't let them do anything this time.
				$backend->add_message("You have logged in as someone else since Commiting.\nFor security reasons, any changes have been ignored.");
				$action = '';
			}
		}

		# If we are on the site properties tab
		# and we are commiting
		# and we have admin access to the site
		if (($parameter_screen == '' || $parameter_screen == 'site_properties') && $action == 'Commit' && $this->site->admin_access() && !$extension_edit && !$order_pages && !$feature_help && !$design_edit && !$floating_site_map) {
			# update the urls here so the preview links are right
			if (!empty($_POST['urls'])) {
				foreach($_POST['urls'] as $url_data) {
					if($url_data['url']) $new_urls[$url_data['url']] = $url_data['protocol'];
				}
			}
			$backend->add_message($this->site->update_urls($new_urls));
		}

		$backend->set_hidden_field('action','Commit');
		$backend->set_hidden_field('siteid',$this->site->id);
		$backend->set_hidden_field('current_userid',$session->user->id);
		$backend->set_hidden_field('userid');
		$backend->set_hidden_field('order_pages',$order_pages);
		$backend->set_hidden_field('feature_help',$feature_help);
		$backend->set_hidden_field('design_edit',$design_edit);
		$backend->set_hidden_field('extension_edit',$extension_edit);
		$backend->set_hidden_field('preview_url',$this->get_preview_url());

		$floating_site_map_open   = $session->get_var('floating_site_map_open');

		#############################################
		# The floating site map
		if($floating_site_map) {
			$this->print_floating_site_map();
			return;
		}

		#############################################
		# Do they want to see a feature help?
		if($_REQUEST['feature_help']) {
		 	$this->_print_feature_helper();
			return;
		}

		 #################
		# Set up the tabs
		$backend->set_tab('site_properties', 'site.php?s='.$this->site->id, 'Site Properties', 'Edit this site\'s general information and the structure of the pages in this site.','site');
		$backend->set_active_tab('site_properties');
		if($this->site->admin_access()) {
			$backend->set_tab('site_custom_design', 'site.php?s='.$this->site->id.'&design_edit=1', 'Custom Design', 'Customize this site\'s design.', 'page');
		}

		$backend->set_tab('site_internationalisation', 'site.php?s='.$this->site->id.'&PARAMETER_SCREEN=site_internationalisation', 'Internationalisation', 'Manage the default language and character set settings for this site.','page');

		if($this->site->editor_access()) {
			$backend->set_tab('site_metadata', 'site.php?s='.$this->site->id.'&PARAMETER_SCREEN=site_metadata', 'Metadata', 'Manage the Metadata for this site.','page');
		}

		$backend->set_tab('site_security', 'site.php?s='.$this->site->id.'&PARAMETER_SCREEN=site_security', 'Security', 'Manage the read- and write-permissions for this site.','security');

		# if the user is an editor or above then show the wizards tab
		if($this->site->editor_access()) {
			$backend->set_tab('wizards', 'site.php?s='.$this->site->id.'&PARAMETER_SCREEN=wizards', 'Wizards', 'View wizards for this site.','graph');
		}
		$backend->set_tab('site_search', 'site.php?s='.$this->site->id.'&PARAMETER_SCREEN=site_search', 'Site Search', 'Search for pages and files in this site.','search');


		if($parameter_screen && $backend->get_tab_label($parameter_screen)) {
			$backend->set_active_tab($parameter_screen);
		}
		$backend->set_hidden_field('PARAMETER_SCREEN',$parameter_screen);

		$backend->set_subheading($backend->get_tab_label($backend->active_tab));


		 ####################################################
		# Print a little preview-site button on every screen
		if(!empty($this->site->urls)) {
			$backend->set_toolbar_button('preview',$this->get_preview_url(), 'Preview this site on the frontend (Alt + V).', 'preview', '_blank');
		}

		 #############################################
		# Do they want to order pages on the site?
		if($order_pages) {
			$this->_print_page_orderer();
			return;
		}


		 ##########################################################
		# Display the edit interface for the extension if asked for
		if($extension_edit) {
			$extension = &$this->site->get_extension($extension_edit);
			if($extension->id == $this->site->id) {
				$backend->set_subheading($extension->name);
				$extension->print_backend();
				return;
			}
		}

		 ##########################################################
		# Display the edit interface for the design if asked for
		if($design_edit) {
			$backend->set_active_tab('site_custom_design');
			$design = &$this->site->get_design();
			$design->print_user_backend('site.php?s='.$this->site->id.'&design_edit=1');
			return;
		}

		 #################
		# Toolbar buttons
		if(superuser('web')) {
			$backend->set_toolbar_button('delete',"javascript: if (confirm('Are you sure you want to delete this entire site?') && confirm('Really sure? This is irreversible.') && confirm('This is a third and final warning. \\nYou are about to delete an entire site and all of its pages and content.')) {document.edit.action.value='Delete';document.edit.submit()}",'Delete this site','delete');

			$backend->set_toolbar_button('dupe',"javascript: if ((confirm('Are you sure you want to duplicate this entire site?')) && (confirm('Are you REALLY sure you want to duplicate this entire site? This could take some time - especially on very large sites.'))) {document.edit.action.value = 'Dupe';document.edit.submit();}",'Duplicate this site','dupe');
		}

		 ################################
		# We'll be needing this for sure
		$db = &$this->get_db();
		# And this
		$index = &$this->site->get_page_index();


		 ####################################
		# Special tab-independant functions..
		switch($action) {
			case 'Delete':
				if(superuser('web')) {

					$num_to_delete = $_REQUEST['num_to_delete'];
					$num = $_REQUEST['num'];
					$num_deleted = $_REQUEST['num_deleted'];
					$started = $_REQUEST['started'];
					$start_time = $_REQUEST['start_time'];

					if (isset($num) && isset($num_to_delete)) {

						$num = (int) $system_config->batch_delete; # number of pages to process at a time
						if ($num <= 0) {
							$num = 5;
						}

						# Tell the backend that it showuld shows our messages
						$backend->set_delay_messages(false);

						if (!$started) {
							$now = time();
							$process_url = $this->site->get_backend_href()."&num_to_delete=$num_to_delete&num=$num&num_deleted=0&action=Delete&started=1&start_time=$now&current_userid=".$session->user->id;
							echo status_popup(1,$process_url,false,false,'#FF0033','Deleting Site - Please wait', "Starting to delete $num_to_delete pages");
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

						# delete $num pages from the site
						$backend->add_message(ereg_replace("[\n]+","\n",$this->site->delete($num, false)));

						$num_deleted += $num;
						$percent = ceil(($num_deleted / $num_to_delete) * 100);
						$finish = (($num_deleted >= $num_to_delete) ? 1 : 0);
						$time_diff = time() - $start_time;
						$time_per_page = $time_diff / $num_deleted;
						$time_left = $time_per_page * ($num_to_delete - $num_deleted);
						if ($time_left <= 0) $time_left = 1;
						$status = '';

						if ($finish) {
							$process_url = $this->site->get_backend_href()."&cleanup=0&action=Delete_finished&started=1&current_userid=".$session->user->id;
							$status = 'Finished deleting. Cleaning up...';
						} else {
							$process_url = $this->site->get_backend_href()."&num_to_delete=$num_to_delete&num=$num&num_deleted=$num_deleted&action=Delete&started=1&start_time=$start_time&current_userid=".$session->user->id;
							$status = "$num_deleted deleted - ".($num_to_delete - $num_deleted).' remaining - est time: '.easy_time_total($time_left) . '<br />' . $extra_message;
						}
						# write the new cache for the next refresh.
						$this->site->write_page_index_to_cache();

						echo status_popup($percent,$process_url,false,false,'#FF0033','Deleting Site - Please wait',$status);
						exit();
					}
					$index = &$this->site->get_page_index();
					$num_to_delete = count($index) -1;
					$num = (int) $system_config->batch_delete; # number of pages to process at a time
					if ($num <= 0) {
						$num = 5;
					}
					$process_url = $this->site->get_backend_href()."&num_to_delete=$num_to_delete&num=$num&num_deleted=0&action=Delete&started=0&current_userid=".$session->user->id;
					echo status_popup(1,$process_url,true,false,'#FF0033','Deleting Site - Please wait', "Starting to delete $num_to_delete pages");
				}
				break;

			case 'Delete_finished' :
				if(superuser('web')) {
					# Trick the backend so that it shows our messages
					$backend->header_printed = true;

					# finished deleting ?
					$cleanup = $_REQUEST['cleanup'];

					if (!$cleanup) {
						$backend->add_message(ereg_replace("[\n]+","\n",$this->site->delete_cleanup()));
						$session->unset_var('active_pageid');
						$session->unset_var('active_siteid');
						$process_url = 'web.php';
						if ($floating_site_map_open) $process_url .= "'; refresh_site_map(0); '";
						echo status_popup('100',$process_url,false,true,'#FF0033','Delete Complete','Redirecting you to site list');
						exit();
					}
				}
				break;

			case 'Dupe':
				if(superuser('web')) {
					$num_to_dupe = $_REQUEST['num_to_dupe'];
					$num = (int) $system_config->batch_dupe; # number of pages to process at a time
					if ($num <= 0) $num = 5;
					$num_duped = $_REQUEST['num_duped'];
					$started = $_REQUEST['started'];
					$start_time = $_REQUEST['start_time'];

					if (isset($num) && isset($num_to_dupe)) {

						# Tell the backend that it showuld shows our messages
						$backend->set_delay_messages(false);

						if (!$started) {
							$now = time();
							$process_url = $this->site->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=0&action=Dupe&started=1&start_time=$now&current_userid=".$session->user->id;
							echo status_popup(1,$process_url,false,false,'#330099','Duping Site - Please wait', "Starting to dupe $num_to_dupe pages");
							exit();
						}
						$new_siteid = $session->get_var('dupe_siteid');

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

						# dupe $num pages to the new site
						$dupe_map = null; $dupe_message = null;
						$pages_to_dupe = $session->get_var('pages_to_dupe');
						$remaining_pages_to_dupe = $this->site->dupe_specified_pages($new_siteid, $num, $pages_to_dupe, $dupe_map, $dupe_message, $save_page_index_cache);
						$session->set_var('pages_to_dupe', $remaining_pages_to_dupe);

						$num_duped += $num;
						$percent = ceil(($num_duped / $num_to_dupe) * 100);
						$finish = (($num_duped >= $num_to_dupe) ? 1 : 0);
						$time_diff = time() - $start_time;
						$time_per_page = $time_diff / $num_duped;
						$time_left = $time_per_page * ($num_to_dupe - $num_duped);
						if ($time_left <= 0) $time_left = 1;
						$status = '';

						if ($finish) {
							$process_url = $this->site->get_backend_href()."&cleanup=0&action=Dupe_remap&started=1&current_userid=".$session->user->id;
							$status = 'Finished duping. Preparing to remap links...';
							$this->site->clear_page_index();
						} else {
							$process_url = $this->site->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=$num_duped&action=Dupe&started=1&start_time=$start_time&current_userid=".$session->user->id;
							$status = "$num_duped duped - ".($num_to_dupe - $num_duped).' remaining - est time: '.easy_time_total($time_left).'<br />'.$extra_message;
							# Save the modified page index to session so when site is reloaded site can find our page index
							$this->site->write_page_index_to_cache();
						}

						echo status_popup($percent,$process_url,false,false,'#330099','Duping Site - Please wait', $status);
						exit();
					}

					$pages_to_dupe = $this->site->get_page_index();
					$num_to_dupe = count($pages_to_dupe) -1;
					$new_siteid = $this->site->dupe_prepare($dupe_map, $dupe_message);
					$session->set_var('dupe_siteid', $new_siteid);

					# Get all subpages with information about their level
					$pages_to_dupe_with_level_orderno = array();
					foreach($pages_to_dupe as $pageid => $page_info) {
						# We don't care for the root of the site
						if ($pageid == 0) continue;
						$pages_to_dupe_with_level_orderno[$pageid] = array($page_info['level'], $page_info['orderno']);
					}

					# Sort the pages in order of level so the upper most level gets duped first, This way we don't need to waste time checking if their parent is duped already
					uasort($pages_to_dupe_with_level_orderno, "page_dupe_comparison");

					# Now it's sorted we only care about the pageids
					$pages_to_dupe = array_keys($pages_to_dupe_with_level_orderno);
					# Save this info for us to use in the future
					$session->set_var('pages_to_dupe', $pages_to_dupe);


					$process_url = $this->site->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=0&action=Dupe&started=0&current_userid=".$session->user->id;
					echo status_popup(1,$process_url,true,false,'#330099','Duping Site - Please wait', "Starting to dupe $num_to_dupe pages");
				}
				break;

			case 'Dupe_remap':
				if(superuser('web')) {

					$num_to_dupe = $_REQUEST['num_to_dupe'];
					$num = $_REQUEST['num'];
					$num_duped = $_REQUEST['num_duped'];
					$started = $_REQUEST['started'];
					$start_time = $_REQUEST['start_time'];

					if (isset($num) && isset($num_to_dupe)) {

						# Tell the backend that it should shows our messages
						$backend->set_delay_messages(false);

						if (!$started) {
							$now = time();
							$process_url = $this->site->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=0&action=Dupe_remap&started=1&start_time=$now&current_userid=".$session->user->id;
							echo status_popup('1',$process_url,false,false,'#330099','Remaping links - Please wait', "Starting to remap $num_to_dupe page".(($num_to_dupe > 1) ? 's' : ''));
							exit();
						}

						$remap_list = $session->get_var('remap_list');

						# remap $num subpages
						$dupe_map = array();
						$this->site->dupe_remap($num, $dupe_map, $remap_list);

						$session->set_var('remap_list', $remap_list);

						$num_duped += $num;
						$percent = ceil(($num_duped / $num_to_dupe) * 100);
						$finish = (($num_duped >= $num_to_dupe) ? 1 : 0);
						$time_diff = time() - $start_time;
						$time_per_page = $time_diff / $num_duped;
						$time_left = $time_per_page * ($num_to_dupe - $num_duped);
						if ($time_left <= 0) $time_left = 1;
						$status = '';

						if ($finish) {
							$process_url = $this->site->get_backend_href()."&cleanup=0&action=Dupe_finished&started=1&current_userid=".$session->user->id;
							$status = 'Finished remaping links. Cleaning up...';
						} else {
							$process_url = $this->site->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=$num_duped&start_time=$start_time&action=Dupe_remap&started=1&current_userid=".$session->user->id;
							$status = "$num_duped pages remaped - ".($num_to_dupe - $num_duped).' remaining - est time: '.easy_time_total($time_left);
						}
						echo status_popup($percent,$process_url,false,false,'#330099','Remaping Links - Please wait', $status);
						exit();
					}

					$new_siteid = $session->get_var('dupe_siteid');
					$new_site = &$this->site->web_system->get_site($new_siteid);
					$index = &$new_site->get_page_index();
					$num_to_dupe = count($index) -1;
					$num = (int) $system_config->batch_dupe; # number of pages to process at a time
					if ($num <= 0) $num = 10;

					$remap_list = array();
					$session->set_var('remap_list', $remap_list);

					$process_url = $this->site->get_backend_href()."&num_to_dupe=$num_to_dupe&num=$num&num_duped=0&action=Dupe_remap&started=0&current_userid=".$session->user->id;
					echo status_popup('1',$process_url,true,false,'#330099','Remaping Links - Please wait', "Starting to remap $num_to_dupe pages");
				}
				break;

			case 'Dupe_finished' :
				if(superuser('web')) {
					$dupe_message = $session->get_var('dupe_message');
					# Trick the backend so that it shows our messages
					$backend->header_printed = true;

					# finished deleting ?
					$cleanup = $_REQUEST['cleanup'];
					if (!$cleanup) {
						$new_siteid = $session->get_var('dupe_siteid');
						$dupe_map = $session->get_var('dupe_map');
						$dupe_message = $session->get_var('dupe_message');
						$this->site->dupe_cleanup($new_siteid, $dupe_map, $dupe_message);
						$backend->add_message(ereg_replace("[\n]+","\n",$dupe_message));
						$session->unset_var('dupe_siteid');
						$session->unset_var('dupe_map');
						$session->unset_var('dupe_message');
						$process_url = "site.php?s=$new_siteid";
						if ($floating_site_map_open) $process_url .= "'; refresh_site_map(".$new_siteid."); '";
						echo status_popup('100',$process_url,false,true,'#330099','Dupe Complete',"Redirecting you to site $new_siteid");
						exit();
					}
				}
				break;
		}



		switch($parameter_screen) {

			 ##########################################
			# The Site Internationalisation Screen
			case "site_internationalisation":
				if($action == 'Commit') {
					if($this->site->admin_access()) {
						$default_languages = $_POST['default_languages'];
						$default_charset = $_POST['default_charset'];	
						$backend->add_message($this->site->set_default_languages(gpc_stripslashes(implode(",",$default_languages))));
						$backend->add_message($this->site->set_default_charset(gpc_stripslashes($default_charset)));
						$backend->set_charset($this->site->get_effective_default_charset());
					}
				}

				$backend->print_header();

				$backend->open_section('Internationalisation');

				$backend->open_field('Languages','top');
				$languages_config = &get_config('languages');
				$language_options = &$languages_config->languages;
				if ($this->site->admin_access()) {
					if (!empty($language_options)) {
						$num_langs_per_col = ceil(count($language_options) / 4);
						$site_langs = explode(",", $this->site->default_languages);
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
											<td valign="top"><input type="checkbox" name="default_languages[]" value="'.$code.'" '.((in_array($code, $site_langs))?'checked':'').'> '.$language.'</td>
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
					}
					echo("<span class=smallprint><br>Leave all unselected to use the system default: <i>".$languages_config->name_list($system_config->default_language)."</i></span>");
				} else {
					echo "<p class=backend_data>".$languages_config->name_list((($this->site->default_languages)?$this->site->default_languages:$system_config->default_language));
				}

				$backend->open_field("Character Set","top");
				$charsets_config = &get_config("charsets");
				$charset_options = array("" => "-- System Default -- ". $charsets_config->charsets[$system_config->default_charset]) + $charsets_config->charsets;
				if ($this->site->admin_access()) {
					echo combo_box("default_charset",$charset_options,$this->site->default_charset, "class=backend_data");
				} else {
					echo('<p class=backend_data>'.$charset_options[$this->site->default_charset]);
				}
				break;

			 ##########################################
			# The Site Metadata Screen
			case 'site_metadata':

				global $INCLUDE_PATH;
				include_once("$INCLUDE_PATH/metadata.inc");
				$metadata = new Metadata($this->site->id, 0);

				if($action == 'Commit') {
					if($this->site->editor_access()) {
						$metadata->process_backend();
					}
				}

				$metadata->set_backend_tabs();
				$backend->remove_toolbar_button('delete');
				$backend->remove_toolbar_button('dupe');

				$backend->print_header();
				$metadata->print_backend($metadata_group);

				break;

			 ############################
			# The Site Security screen
			case "site_security":
				if($action == 'Commit') {

					if($this->site->admin_access()) {
						$public = $_REQUEST['public'];
						$access_grants = $_REQUEST['access_grants'];
						$backend->add_message($this->site->set_public($public));
						$backend->add_message($this->site->update_access_grants($access_grants));

						$new_site_group_name = $_REQUEST['new_site_group_name'];
						if ($new_site_group_name) {
							$backend->add_message($this->site->new_access_group(gpc_stripslashes($new_site_group_name)));
						}
					}

					if(superuser('web')) {
						$allowed_extensions = $_REQUEST['allowed_extensions'];
						$allowed_templates = $_REQUEST['allowed_templates'];
						$allowed_designids = $_REQUEST['allowed_designids'];

						$backend->add_message($this->site->update_allowed_extensions($allowed_extensions));
						$backend->add_message($this->site->update_allowed_templates($allowed_templates));
						$backend->add_message($this->site->update_allowed_designids($allowed_designids));

					}
					if(superuser('web')) {
						$delete_adminids = $_REQUEST['delete_adminids'];
						$email_adminids = $_REQUEST['email_adminids'];

						# check for changes to email settings
						$backend->add_message($this->site->update_parameter_adminids('email_adminids', $email_adminids));
						if (!empty($delete_adminids)) {
							foreach($delete_adminids as $id) {
								$backend->add_message($this->site->remove_adminid($id));
							}
						}

						$new_admin = $_REQUEST['new_admin'];
						$users_system = &get_users_system();
						if($new_admin) {
							$new_admin = gpc_stripslashes($new_admin);
							$user = &$users_system->get_user($new_admin);
							if($user->id) {
								$backend->add_message($this->site->add_adminid($user->id));
							} else {
								$backend->add_message("Couldn't find a user (to make a site administrator) with login or email of: $new_admin");
							}
						}
					}

					if($this->site->admin_access()) {
						$delete_editorids = $_REQUEST['delete_editorids'];
						if (!empty($delete_editorids)) {
							foreach($delete_editorids as $id) {
								$backend->add_message($this->site->remove_editorid($id));
							}
						}

						$new_editor = $_REQUEST['new_editor'];
						$users_system = &get_users_system();
						if($new_editor) {
							$new_editor = gpc_stripslashes($new_editor);
							$user = &$users_system->get_user($new_editor);
							if($user->id) {
								$backend->add_message($this->site->add_editorid($user->id));
							} else {
								$backend->add_message("Couldn't find a user (to make a site editor) with login or email of: $new_editor");
							}
						}
					}

					$this->site->clear_page_index();
					$index = &$this->site->get_page_index();
				}
				$backend->print_header();

				$backend->open_section("Read Access Grants");

				if(!$this->site->public && empty($this->site->access_grants)) {
					$backend->open_field("<span class=warning>WARNING</span>","one_liner");
					echo("<span class=warning>You have not granted access to the public <i>or</i> any of the access groups. Noone can see this site on the frontend.</span>");
				}

				$backend->open_field("Public", 'one_liner');
				echo("<input type=checkbox value=\"1\" name=public ".(($this->site->public)?"checked":"")." ".(($this->site->admin_access())?"":"onfocus=\"alert('Permission denied.');blur();\"").">");

				$general_access_groups = &$this->web_system->get_general_access_group_list();
				if($this->site->admin_access() || !empty($general_access_groups)) {
					$backend->open_field("Gen. Access Groups","top");
					if(empty($general_access_groups)) {
						echo("None.");
					} else {
						foreach($general_access_groups as $groupid => $name) {
							$granted = in_array($groupid, $this->site->access_grants);
							echo("<input type=checkbox value=\"$groupid\" name=access_grants[] ".(($granted)?"checked":"")." ".(($this->site->admin_access())?"":"onfocus=\"alert('Permission denied.');blur();\"").">");
							echo("<a href=\"access_group.php?groupid=$groupid\">$name</a><br>");
						}
					}
				}

				if($this->site->admin_access() || !empty($this->site->access_groups)) {
					$backend->open_field("Site Access Groups","top");
					if(empty($this->site->access_groups)) {
						echo("None.");
					} else {
						foreach($this->site->access_groups as $groupid => $name) {
							$granted = in_array($groupid, $this->site->access_grants);
							echo("<input type=checkbox value=\"$groupid\" name=access_grants[] ".(($granted)?"checked":"")." ".(($this->site->admin_access())?"":"onfocus=\"alert('Permission denied.');blur();\"").">");
							echo("<a href=\"access_group.php?groupid=$groupid\">$name</a><br>");
						}
					}
				}

				if($this->site->admin_access()) {
					$backend->open_field("Create New Site Group?");
					echo text_box("new_site_group_name","",50,255, "class=backend_data");
				}

				$backend->open_section("Allowed Usage");

				 ###########################
				# List available extensions
				$backend->open_field("Site Extensions","top");
				$all_extensions = $XTRAS->list_type("site/extensions");
				$i = 0;
				if (!empty($all_extensions)) {
					foreach($all_extensions as $codename => $name) {
						$i++;
						$granted = in_array($codename,$this->site->allowed_extensions);
						if ($granted || superuser("web")) {
							if (superuser("web")) {
								echo("<input type=checkbox value=\"$codename\" name=allowed_extensions[] ".(($granted)?"checked":"").">");
							}
							echo "$name<br>";
						}
					}
				}

				$backend->open_field("Page Templates","top");
				$all_templates = $XTRAS->list_type("page/templates");
				if (!empty($all_templates)) {
					foreach($all_templates as $codename => $name) {
						$granted = in_array($codename,$this->site->allowed_templates);
						if ($granted || superuser("web")) {
							if(superuser("web")) {
								echo("<input type=checkbox value=\"$codename\" name=allowed_templates[] ".(($granted)?"checked":"").">");
							}
							echo("$name<br>");
						}
					}
				}

				$backend->open_field("Site Designs","top");
				$all_designs = &$this->web_system->get_site_design_list();
				if (!empty($all_designs)) {
					foreach($all_designs as $designid => $name) {
						$granted = in_array($designid,$this->site->allowed_designids);
						if ($granted || superuser("web")) {
							if(superuser("web")) {
								echo("<input type=checkbox value=\"$designid\" name=allowed_designids[] ".(($granted)?"checked":"").">");
							}
							echo("<a href=\"site_design.php?designid=$designid\">$name</a><br>");
						}
					}
				}

				if($this->site->editor_access()) {
					$backend->open_section("Write Access Permissions");

					$backend->open_field("Site Admins","top");
					$users_system = &get_users_system();

				/*
				   This displays all parameters and calculates any presets before passing to
				   display_users_table. Each $checkboxes has an array as it's second dimension
				   which contains all adminids that have that parameter preset (ie checkbox
				   will be checked). Fully backwards compatible.
				*/

				$checkbox_names='';
				$checkboxes='';
				if (superuser('web')) {
					$checkbox_names = array('delete_adminids'=>array(), 'email_adminids'=>array());
					$checkboxes = array('Delete?', 'Email?');
					if (!empty($this->site->adminids)) {
						foreach ($this->site->adminids as $id) {
							$parameters = unserialize($this->site->adminids_parameters[$id]);
							while(list($name, $value) = each ($parameters)) {
								if ($parameters[$name] == 1) {
									array_push($checkbox_names[$name], $id);
								}
							}
						}
					}
				}
				$users_system->display_users_table($this->site->adminids,$checkbox_names,$checkboxes);

				if(superuser('web')) {
					$backend->open_field('New Admin?<span class=backend_fineprint><br>(login/email)</span>','one_liner');
					echo text_box('new_admin','',20,128, 'class=backend_data');
				}

				$backend->open_field('Site Editors','top');
				$users_system->display_users_table($this->site->editorids,(($this->site->admin_access())?'delete_editorids':''),'Delete?');

				if($this->site->admin_access()) {
					$backend->open_field('New Editor?<span class=backend_fineprint><br>(login/email)</span>','one_liner');
					echo text_box('new_editor','',20,128, 'class=backend_data');
				}
			}
			break;


			 #####################################
			# The "Page Statisitcs" screen
			case 'wizards':
				# Don't wanna be commitin' nothin'!
				$backend->set_hidden_field('action','');

				global $INCLUDE_PATH;
				include_once("$INCLUDE_PATH/wizard.inc");
				$report = new Wizard($this->site);
				$type_set = $report->type_set();
				if (!$type_set) $backend->print_header();
				$report->print_backend();
				if (!$type_set) $backend->print_footer();

				return;

			#####################################
			# The "Site Search" screen
			case 'site_search':
				$pagesearch = $_REQUEST['pagesearch'];
				$filesearch = $_REQUEST['filesearch'];
				$pagesearchby = $_REQUEST['pagesearchby'];
				$filesearchby = $_REQUEST['filesearchby'];

				$backend->remove_toolbar_button('delete');
				$backend->remove_toolbar_button('dupe');

				if ($pagesearch) {
					$filesearch = '';
					$filesearchby = '';
				}

				$pagesearch = str_replace('\\\\', '\\', $pagesearch);
				$filesearch = str_replace('\\\\', '\\', $filesearch);

				$backend->print_header();

				$backend->open_section('Search');

				$backend->open_field('Pages','one_liner');
				?>
				<input type="text" name="pagesearch" size="30" value="<?=$pagesearch?>">
				<select name="pagesearchby" class="smallprint">
					<option value="name">by name</option>
					<option value="id"<?=($pagesearchby == 'id') ? ' selected' : ''?>>by ID</option>
				</select>
				<?
				$backend->close_field();
				$backend->open_field('Files','one_liner');
				?>
				<input type="text" name="filesearch" size="30" value="<?=$filesearch?>">
				<select name="filesearchby" class="smallprint">
					<option value="name">by name</option>
					<option value="id"<?=($filesearchby == 'id') ? ' selected' : ''?>>by ID</option>
				</select>
				<?
				$backend->close_field();
				$backend->close_section();
				$backend->print_commit_button('Search','document.edit.submit()');
				if($action == 'Commit' && ($filesearch || $pagesearch)) {

					$what_searched = 'This search looked for ';

					$db = &$this->get_db();

					if ($pagesearch) {
						$pagesearch = trim($pagesearch);
						$original = $pagesearch;
						$what_searched .= 'pages ';
						$str_sql = 'SELECT t1.pageid, t1.name, t1.parentid, t2.name AS parentname FROM page AS t1, page AS t2 WHERE ((t1.parentid = t2.pageid) OR (t1.parentid=0 and t2.pageid=t1.pageid)) AND t1.siteid='.$this->site->id.' AND ';
						if ($pagesearchby == 'name') {
							$what_searched .= 'with a page name matching the pattern:<br>';
							$pagesearch = str_replace('_', "\\_", $pagesearch);
							$pagesearch = str_replace('%', "\\%", $pagesearch);

							$str_sql .= "t1.name LIKE '";
							$pagesearch_temp = '';
							if (substr($pagesearch, 0, 1) != '^') {
								$pagesearch_temp .= '%';
								$original = '[any number of chars]'.$original;
								$pagesearch = str_replace('\^', '^', $pagesearch);
							} else {
								$pagesearch = substr($pagesearch, 1);
								$original = '[start of name]'.substr($original, 1);
							}
							if (substr($pagesearch, -1) != '$') {
								$pagesearch_temp .= "$pagesearch%";
							} else {
								if (substr($pagesearch, -2) != '\\$') {
									$pagesearch = substr($pagesearch, 0, -1);
									$pagesearch_temp .= $pagesearch;
									$original = substr($original, 0, -1).'[end of name]';
								} else {
									$pagesearch = str_replace('\\$', '$', $pagesearch);
									$pagesearch_temp .= $pagesearch;
								}
							}

							$sections = explode('*', $original);
							$i = 0;
							if (!empty($sections)) {
								foreach ($sections as $section) {
									# remove the last char if it is a "\"
									if (trim($section) == '[any number of chars]') {
										$i++;
										continue;
									}
									if (strstr($section, '[end of name]')){
										$i++;
										continue;
									}
									if (substr($section, -1) == '\\') {
										$sections[$i] = substr($section, 0, -1).'*';
									} else {
										$sections[$i] = $section.'[any number of chars]';
									}
									$i++;
								}
							}

							$original = implode('', $sections);

							$sections = explode('.', $original);
							$i = 0;
							if (!empty($sections)) {
								foreach ($sections as $section) {
									# remove the last char if it is a "\"
									if (trim($section) == '[one char]') {
										$i++;
										continue;
									}
									if (strstr($section, '[end of name]') || count($sections) == ($i+1)){
										$i++;
										continue;
									}
									if (substr($section, -1) == '\\') {
										$sections[$i] = substr($section, 0, -1).'.';
									} else {
										$sections[$i] = $section.'[one char]';
									}
									$i++;
								}
							}

							$original = implode('', $sections);

							$original = str_replace('\^', '^', $original);
							$original = str_replace('\$', '$', $original);
							$what_searched .= '<i>'.$original.'</i>';

							$temp = $original;
							$temp = str_replace('[one char]', '_', $temp);
							$temp = str_replace('[any number of chars]', '%', $temp);
							if (strstr($temp, '[start of name]')) {
								$temp = str_replace('[start of name]', '', $temp);
							} else {
								$temp = '%'.$temp;
							}
							if (strstr($temp, '[end of name]')) {
								$temp = str_replace('[end of name]', '', $temp);
							} else {
								$temp = $temp.'%';
							}
							$str_sql .= $temp."'";


						}
						if ($pagesearchby == 'id') {
							$what_searched .= "with a page ID of $pagesearch";
							$str_sql .= "t1.pageid='$pagesearch'";
						}
						$str_sql .= ' ORDER BY name';
						$pagesearch = true;
					} else if ($filesearch) {
						$original = $filesearch;
						$filesearch = trim($filesearch);
						$what_searched .= 'files ';
						$str_sql = 'SELECT t1.fileid, t1.filename, t1.pageid, t2.name FROM file AS t1, page AS t2 WHERE ((t1.pageid = t2.pageid) OR (t1.pageid=0 and t2.pageid=t1.pageid)) AND ';
						if ($filesearchby == 'name') {
							$what_searched .= 'with a filename matching the pattern:<br>';
							$filesearch = str_replace('_', '\\_', $filesearch);
							$filesearch = str_replace('%', '\\%', $filesearch);

							$str_sql .= "t1.filename LIKE '";
							$filesearch_temp = '';
							if (substr($filesearch, 0, 1) != '^') {
								$filesearch_temp .= '%';
								$original = '[any number of chars]'.$original;
								$filesearch = str_replace('\^', '^', $filesearch);
							} else {
								$filesearch = substr($filesearch, 1);
								$original = '[start of name]'.substr($original, 1);
							}
							if (substr($filesearch, -1) != '$') {
								$filesearch_temp .= "$filesearch%";
							} else {
								if (substr($filesearch, -2) != '\\$') {
									$filesearch = substr($filesearch, 0, -1);
									$filesearch_temp .= $filesearch;
									$original = substr($original, 0, -1).'[end of name]';
								} else {
									$filesearch = str_replace('\\$', '$', $filesearch);
									$filesearch_temp .= $filesearch;
								}
							}

							$sections = explode('*', $original);
							$i = 0;
							if (!empty($sections)) {
								foreach ($sections as $section) {
									# remove the last char if it is a "\"
									if (trim($section) == '[any number of chars]') {
										$i++;
										continue;
									}
									if (strstr($section, '[end of name]')){
										$i++;
										continue;
									}
									if (substr($section, -1) == '\\') {
										$sections[$i] = substr($section, 0, -1).'*';
									} else {
										$sections[$i] = $section.'[any number of chars]';
									}
									$i++;
								}
							}

							$original = implode('', $sections);

							$sections = explode('.', $original);
							$i = 0;
							if (!empty($sections)) {
								foreach ($sections as $section) {
									# remove the last char if it is a "\"
									if (trim($section) == '[one char]') {
										$i++;
										continue;
									}
									if (strstr($section, '[end of name]') || count($sections) == ($i+1)){
										$i++;
										continue;
									}
									if (substr($section, -1) == '\\') {
										$sections[$i] = substr($section, 0, -1).'.';
									} else {
										$sections[$i] = $section.'[one char]';
									}
									$i++;
								}
							}

							$original = implode('', $sections);

							$original = str_replace('\^', '^', $original);
							$original = str_replace('\$', '$', $original);
							$what_searched .= '<i>'.$original.'</i>';

							$temp = $original;
							$temp = str_replace('[one char]', '_', $temp);
							$temp = str_replace('[any number of chars]', '%', $temp);
							if (strstr($temp, '[start of name]')) {
								$temp = str_replace('[start of name]', '', $temp);
							} else {
								$temp = "%".$temp;
							}
							if (strstr($temp, '[end of name]')) {
								$temp = str_replace('[end of name]', '', $temp);
							} else {
								$temp = $temp.'%';
							}
							$str_sql .= $temp."'";


						}
						if ($filesearchby == 'id') {
							$what_searched .= "with a file ID of $filesearch";
							$str_sql .= "t1.fileid='$filesearch'";
						}
						$str_sql .= ' ORDER BY filename';
						$filesearch = true;
					}

					$results = $db->associative_array($str_sql);

					$num_results = count($results);

					if ($num_results > 0) {
						if ($num_results == 1) {
							$backend->open_section('Search Results (1 record found)');
						} else {
							$backend->open_section("Search Results ($num_results records found)");
						}
						$backend->open_field('','top');

						echo '<table cellpadding=2 cellspacing=1 border=0 width=100%>';

						if ($pagesearch) {
							echo '<tr><td><p class=field>Page ID</td><td><p class=field><p class=field>Page Name</td><td><p class=field>Parent ID</td><td><p class=field>Parent Name</td></tr>';
						} else if ($filesearch) {
							echo '<tr><td><p class=field>File ID</td><td><p class=field>File Name</td><td><p class=field>Page ID</td><td><p class=field>Page Name</td></tr>';
						}
						if (!empty($results)) {
							foreach ($results as $id => $info) {
								echo '<tr>';
								if ($pagesearch) {
									?>
									<td bgcolor=#d0d0d0><?=$info['pageid']?></td>
									<td bgcolor=#d0d0d0><a href="page.php?pageid=<?=$info['pageid']?>"><?=$info['name']?></a></td>
									<td bgcolor=#d0d0d0><?=($info['parentid'] == 0) ? '-' : $info['parentid']?></td>
									<td bgcolor=#d0d0d0><?=($info['parentid'] == 0) ? '-' : '<a href=page.php?pageid='.$info['parentid'].'>'.$info['parentname'].'</a>'?></td>
									<?
								} else if ($filesearch) {
									?>
									<td bgcolor=#d0d0d0><?=$info['fileid']?></td>
									<td bgcolor=#d0d0d0><a href="page.php?fileid=<?=$info['fileid']?>&file_edit=1"><?=$info['filename']?></a></td>
									<td bgcolor=#d0d0d0><?=($info['pageid'] == 0) ? '-' : $info['pageid'];?></td>
									<td bgcolor=#d0d0d0><?=($info['pageid'] == 0) ? '-' : '<a href=page.php?pageid='.$info['pageid'].'>'.$info['name'].'</a>'?></td>
									<?
								}
								echo '</tr>';
							}
						}

						echo "<tr><td colspan=4>$what_searched</td></tr>";
						echo '</table>';
						$backend->close_section();

					} else {
						$backend->open_section('Your search did not produce any results');
						$backend->open_field('','top');
						echo "<table><tr><td colspan=4>$what_searched</td></tr></table>";
						$backend->close_field();
					}
				}


				$backend->print_footer();
				return;


			 #################################
			# The Site Properties screen
			case 'site_properties': default:
				$floating_site_map_refresh = false;

				if($action == 'Commit') {
					if($this->site->admin_access()) {
						$name = $_REQUEST['name'];
						$description = $_REQUEST['description'];
						$default_languages = $_REQUEST['default_languages'];
						$default_charset = $_REQUEST['default_charset'];

						$name_changed = $this->site->set_name(gpc_stripslashes($name));
						if ($name_changed != '') {
							$floating_site_map_refresh = true;
							$backend->add_message($name_changed);
						}

						$backend->add_message($this->site->set_description(gpc_stripslashes($description)));

						$designid = $_REQUEST['designid'];
						if(superuser('web') || in_array($designid, $this->site->allowed_designids)) {
							$backend->add_message($this->site->set_design($designid));
						}
					}
					$backend->set_name($this->site->name);
					$backend->set_id_string($this->site->id);
					$this->site->clear_page_index();
					$index = &$this->site->get_page_index();

					if($this->site->admin_access()) {
						$index_pageid = $_POST['index_pageid'];
						$backend->add_message($this->site->set_index_pageid($index_pageid));
						$not_found_pageid = $_POST['not_found_pageid'];
						$backend->add_message($this->site->set_not_found_pageid($not_found_pageid));
						$forbidden_pageid = $_POST['forbidden_pageid'];
						$backend->add_message($this->site->set_forbidden_pageid($forbidden_pageid));
					}

					if(superuser('web')) {
						$max_pages = $_POST['max_pages'];
						$max_files = $_POST['max_files'];
						$max_file_bytes = $_POST['max_file_bytes'];
						$backend->add_message($this->site->set_maximums(
														abs((int) $max_pages),
														abs((int) $max_files),
														abs((int)($max_file_bytes * 1024 * 1024))
													));
					}

					if($this->site->editor_access()) {
						$new_page_names = $_POST['new_page_names'];
						$new_page_template = $_POST['new_page_template'];
						if($new_page_names && (superuser('web') || in_array($new_page_template,$this->site->allowed_templates))) {
							$new_page_count = 0;
							$new_pageid = 0;
							$new_page_name_list = split("[\r\n]+",$new_page_names);
							if (!empty($new_page_name_list)) {
								foreach($new_page_name_list as $new_page_name) {
									if(!($new_page_name = gpc_stripslashes(trim($new_page_name)))) continue;
									list($error_code, $msg, $new_pageid) = $this->site->create_page($new_page_name, $new_page_template, 0, 'U', false);
									$backend->add_message(Array($error_code, $msg));
									# only add if no errors occured
									if ($error_code == MYSOURCE_ERROR_CODE_NONE) {
										$new_page_count++;
									}#end if
								}#end foreach
							}
							if($new_page_count > 1) {
								$backend->add_message("$new_page_count new pages created.");
							} elseif ($new_page_count == 1 && $new_pageid) {
								$backend->set_relocation("page.php?p=$new_pageid");
							# else do nothing
							}#end if
							if ($new_page_count >= 1) {
								$floating_site_map_refresh = true;
							}#end if
						}#end if
					}#end if
					$this->site->clear_page_index();
					$index = &$this->site->get_page_index();

				} elseif ($action == 'Connect') {
					$parentid = $_POST['parentid'];
					$childid = $_POST['childid'];
					$child = new Page($childid);
					$parent = new Page($parentid);
					/*
						If you are a site admin, you can move pages around all you like.
						If you are a page admin, you have to be a page admin of the page you are moving this one to. Let's do some checks ..
					*/
					if (($this->site->admin_access()) || ($child->admin_access() && $parent->admin_access())) {
						$backend->add_message($child->page_move($parentid));
						$this->site->clear_page_index();
						$index = &$this->site->get_page_index();
					} else {
						$backend->add_message(MYSOURCE_ERROR_CODE_ERROR, "Sorry, you need to be a site administrator to move pages around, or a page administrator of both the old and new pages.");
					}

					unset($child);
					unset($parent);

				} elseif ($action == 'dock_floating_site_map') {
					$session->unset_var('floating_site_map_open');
				}

				$active_pageid = $_POST['active_pageid'];
				$backend->set_hidden_field('childid');
				$backend->set_hidden_field('parentid');
				$backend->set_hidden_field('active_pageid',$active_pageid);
				$backend->set_hidden_field('open_pageid');
				$backend->set_hidden_field('close_pageid');
				$backend->set_hidden_field('expand_all_pageid');
				$backend->set_hidden_field('collapse_all_pageid');
				$backend->set_hidden_field('undock_site_map',0);

				$floating_site_map_open = $session->get_var('floating_site_map_open');
				if ($floating_site_map_open && $floating_site_map_refresh) {
					$backend->set_javascript('refresh_site_map('.$this->site->id.')');
				}#endif

				$backend->print_header();

				$backend->open_section('Identification');

				$backend->open_field('Site Name', 'one_liner');
				if($this->site->admin_access())
					echo text_box(name,$this->site->name,50,128,'class=backend_data');
				else
					echo ('<span class=backend_data>'.$this->site->name.'</span>');


				$backend->open_field("Description of Site:<span class=backend_fineprint><br>(not shown on front-end)</span>","top");
				if($this->site->admin_access())
					echo text_area(description,$this->site->description,50,6,0,"class=backend_data");
				else
					echo nl2br('<span class=backend_data>'.$this->site->description.'</span>');

				$backend->open_section("Custom Design");

				$backend->open_field("Design", 'one_liner');
				$site_designs = &$this->web_system->get_site_design_list();
				if (
					superuser('web') ||
					($this->site->admin_access() && !empty($this->site->allowed_designids))
				) {
					$designs = array();
					if(!superuser("web")) {
						foreach($this->site->allowed_designids as $designid) {
							$designs[$designid] = $site_designs[$designid];
						}
					} else $designs = &$site_designs;
					echo combo_box("designid",$designs,$this->site->designid, "class=backend_data");
				} else {
					echo('<p class=backend_data>'.$site_designs[$this->site->designid]);
				}

				$pages_section_link = $backend->open_section("Page Tree","page");

				$backend->open_field("Count", 'one_liner');
				$page_count = count($index) - 1;
				$too_many_pages = $this->site->max_pages > 0 && $page_count >= $this->site->max_pages;
				echo ("<span ".(($too_many_pages)?"class=warning":"").">$page_count</span>");
				if(superuser("web")) {
					echo(" / ".text_box("max_pages",(($this->site->max_pages > 0)?$this->site->max_pages:"-"),4,6,"class=backend_data")." (Max)");
				} elseif($this->site->max_pages > 0) {
					echo(' / '.$this->site->max_pages.' (Max)');
				}

				$backend->set_hidden_field('max_files',$this->site->max_files); #PUT THESE IN AN PROPER FIELDS
				$backend->set_hidden_field('max_file_bytes',($this->site->max_file_bytes/1024)/1024); # WHEN THE FILES SECTION APPEARS

				$floating_site_map_open   = $session->get_var('floating_site_map_open');
				$floating_site_map_siteid = $session->get_var('floating_site_map_siteid');

				if ($floating_site_map_open) {
					if ($floating_site_map_siteid != $this->site->id) {
						?><script language="javascript">undock_site_map(<?=$this->site->id?>)</script><?
					}

					$backend->open_field('Site Map<span class=backend_fineprint><br>(Alt + H)</span>','top');
					?>&nbsp;&nbsp;<a href="javascript:dock_site_map(<?=$this->site->id?>);"><img src="images/icons/dock.gif" border="0"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:undock_site_map(<?=$this->site->id?>);"><img src="images/icons/bring2front.gif" border="0"></a><?
				} else {
					if($this->site->admin_access() && count($this->site->get_top_pageids()) > 1) {
						$backend->open_field('Reorder Main Pages', 'one_liner');
						$backend->print_icon_button('javascript: popup_page_orderer('.$this->site->id.',0)','Reorder the main pages','reorder');
						if ($this->site->subpage_auto_order == 'az') 
							echo '&nbsp;(<span class="backend_field">Auto:</span> A-Z)';
						elseif ($this->site->subpage_auto_order == 'za') 
							echo '&nbsp;(<span class="backend_field">Auto:</span> Z-A)';
					}

					$site_map_jump = $backend->open_field('Site Map<span class=backend_fineprint><br>(Alt + H)</span>','top');

					$open_pageid = $_POST['open_pageid'];
					$close_pageid = $_POST['close_pageid'];
					if($open_pageid || $close_pageid) {
						$backend->set_jumpto($site_map_jump);
					}
					?>
					<table cellpadding=0 cellspacing=0 border=0>
						<tr>
							<td><a href="javascript:if (connect_child > 0) {document.edit.action.value='Connect';document.edit.childid.value=connect_child;document.edit.parentid.value='0';document.edit.submit()}" onmouseover="if (connect_child > 0) {window.status='Make \''+connect_child_name+'\' a Main Page.';} else{window.status='';} return true;" onmouseout="window.status='';return true;"><img src="images/icons/site.gif" width=20 height=20 border=0></a></td>
							<td nowrap><p class=backend_data><b><?=$this->site->name?></td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td><a href="javascript:expand_all_page(0);"><img src="images/expand_all.gif" width="82" height="19" border="0"></a></td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td><a href="javascript:collapse_all_page(0);"><img src="images/collapse_all.gif" width="88" height="19" border="0"></a></td>
							<td align="right" width="100%">&nbsp;<a href="javascript:undock_site_map(<?=$this->site->id?>);"><img src="images/icons/undock.gif" border="0"></a></td>
						</tr>
					</table>
					<table cellpadding=0 cellspacing=0 border=0 width=100%>
					<?
					$a = $this->site->get_top_pageids();
					$this->print_page_hierarchy($a);
					?></table><?
				}

				$backend->open_field("Index Page", 'one_liner');

				# we need the pages_array for the next 3 options.
				$pages_array = $this->web_system->page_array_with_sticks($this->site->id);

				if ($this->site->admin_access()) {
					echo combo_box("index_pageid",$pages_array,$this->site->index_pageid,"class=backend_data");
				} else {
					echo ("<p class=backend_data>".$index[$this->site->index_pageid]['name']);
				}

				$backend->open_field("Not Found Page", 'one_liner');
				if ($this->site->admin_access()) {
					echo combo_box("not_found_pageid",array("")+$pages_array,$this->site->not_found_pageid,"class=backend_data");
				} else {
					echo ("<p class=backend_data>".$index[$this->site->not_found_pageid]['name']);
				}

				$backend->open_field("Forbidden Page", 'one_liner');
				if ($this->site->admin_access()) {
					echo combo_box("forbidden_pageid",array("")+$pages_array,$this->site->forbidden_pageid,"class=backend_data");
				} else {
					echo ("<p class=backend_data>".$index[$this->site->forbidden_pageid]['name']);
				}

				# We're all done. Get rid this.
				unset($pages_array);

				if($this->site->editor_access()) {
					$backend->open_field("Create New Pages?","top");
					echo combo_box("new_page_template", $XTRAS->list_type("page/templates",((superuser("web")) ? Array() : $this->site->allowed_templates)), "standard", "class=backend_data")."<br>";
					echo text_area("new_page_names","", 50, 5, 0,"class=backend_data");
				}

				$backend->open_section("URLs","preview");
				if(empty($this->site->urls)) {
					$backend->open_field("<span class=warning>WARNING:</span>", 'one_liner');
					echo ("<span class=warning>This site has no URLs pointing to it. It cannot be viewed, previewed, or edited.</span>");
				}

				global $URL_PROTOCOLS;
				if($this->site->admin_access()) {
					$url_count = 0;
					if (!empty($this->site->urls)) {
						foreach($this->site->urls as $url => $protocol) {
							$backend->open_field(++$url_count, 'one_liner');
							echo combo_box("urls[$url_count][protocol]",$URL_PROTOCOLS,$protocol,'class=backend_data');
							echo text_box("urls[$url_count][url]",$url,50,128,'class=backend_data');
						}
					}
					$site_url_count = count($this->site->urls);
					while($url_count <= $site_url_count) {
						$backend->open_field(++$url_count, 'one_liner');
						echo combo_box("urls[$url_count][protocol]",$URL_PROTOCOLS,'','class=backend_data');
						echo text_box("urls[$url_count][url]","",50,128,'class=backend_data');
					}
				} else {
					if (!empty($this->site->urls)) {
						foreach($this->site->urls as $url => $protocol) {
							$backend->open_field(++$url_count, 'one_liner');
							echo "<span class=backend_data>$URL_PROTOCOLS[$protocol]</span>";
							echo "<span class=backend_data>$url</span>";
						}
					}
				}
				break;
		}
		$backend->print_commit_button("Commit","document.edit.submit()");
		$backend->print_footer();
	}


	/**
	* Prints a hierarchy table of all the site's page information
	*
	* @param   array int $pageids		The array or pageids representing pages to print
	* @param   array boolean $depth
	* @param   boolean $static			Whether or not moving of pages is enabled
	* @param   boolean $from_popup		Whether this heirarchy is being printed in a popup or not
	* @access  public
	*/
	function print_page_hierarchy(&$pageids,$depth=array(),$static=false,$from_popup=false) {
		global $PAGE_STATUSES, $SQUIZLIB_PATH, $XTRAS;
		include_once("$SQUIZLIB_PATH/colour/colour.inc");
		$depth[] = true; # The indenting level, stalk on/off
		if ($from_popup) {
			$href_prefix = 'window.opener';
		} else {
			$href_prefix = 'document';
		}

		global $PAGE_STATUS_COLOUR;

		 ########################################
		# Make sure we know which pages are open
		$index   = &$this->site->get_page_index();
		$this->_print_page_hierarchy_open_pages();
		if (!$pageid) $pageid = array();
		if (!empty($pageids)) {
			reset($pageid);
			for($pageid = reset($pageids); $pageid; $pageid = next($pageids)) {
				if (!next($pageids)) $end = true;
				else $end = false;
				prev($pageids);

				$page    = &$index[$pageid];
				$haskids = isset($page['childids']) && !empty($page['childids']);
				$open    = $page['open'];

				$bg_img = 'images/page_'.(($page['effective_visible']) ? '' : 'in').'visible_'.$PAGE_STATUS_COLOUR[$page['effective_status']].'.gif';

				?>
				<a name=page<?=$pageid?>>
				<tr>
				<td background="<?=$bg_img?>"><table cellpadding=0 cellspacing=0 border=0>
					<tr>
					<?
					$depth_count = count($depth);
					for ($i = 0; $i < $depth_count; $i++) {
						?><td valign=top <? if ($depth[$i] && !($end && ($i == count($depth)-1))) echo "background=\"images/tree/stalk.gif\"" ?> ><?
						if ($i != count($depth) - 1) {
							?><img src="images/blank.gif" height=1 width=20><?
						} else {
							if ($haskids && !$static) {
								?><a href="javascript:<?=(($open) ? "close_page('$page[pageid]')" : "open_page('$page[pageid]')")?>;" onmouseover="window.status='<?=(($open)?"Hide subpages":"Show sub-pages")?>'; return true;" onmouseout="window.status='';return true;"><img src="images/tree/branch_<?=(($open)?"open":"closed")?>.gif" alt="<?=(($open)?"Hide":"Show")?> subpages" height=20 width=20 border=0></a><?
							} else {
								?><img src="images/tree/branch.gif" height=20 width=20><?
							}
						}
						?></td><?
					}

					$state = (($page['public'])?"Public":"Private") ."/" . $PAGE_STATUSES[$page['status']];
					$effective_state = (($page['effective_public'])?"Public":"Private") ."/" . $PAGE_STATUSES[$page['effective_status']];

					?>
						<td valign=top background="<?=$bg_img?>"><? if (!$static) { ?><a href="javascript:if (connect_child != 0) {document.edit.action.value='Connect';document.edit.childid.value=connect_child;document.edit.parentid.value='<?=$page['pageid']?>';document.edit.submit()} else {set_connect_child( ((confirm('To connect this page to a new parent, click the icon of another page you wish <?=addslashes($page['name'])?> page to be a sub-page of.'))?'<?=$page['pageid']?>':false),'<?=addslashes($page['name'])?>');}" onmouseover="if (connect_child != 0) {window.status='Make this page the parent of the \''+connect_child_name+'\'.';} else{window.status='Connect this page to a different parent.';} return true;" onmouseout="window.status='';return true;"><? } ?><img src="images/tree/page_<?=(($page['effective_public'])?"tick":"cross") ?>.gif" width=16 height=20 border=0 alt="<?=(($effective_state != $state)?"$state, but parent is $effective_state":$state) ?>"></a><img src="images/blank.gif" width=5 height=20></td>
						<td><img src="images/blank.gif" width=5 height=20></td>
						<td<?=(!$from_popup) ? ' class=backend_data' : ''?>><a href="#" <?=($from_popup) ? 'style="font-size=10"' : ''?> onClick="Javascript:<?=$href_prefix?>.location.href='page.php?pageid=<?=$page['pageid']?>'; return false;" onmouseover="show_tooltip(event,'<?=addslashes(htmlspecialchars($page['name']))?> (ID: <?=$page['pageid']?>)','<?=addslashes(htmlspecialchars(ereg_replace("[\n\r]"," ",$page['description'])))?>',300); return true;" onmouseout="hide_tooltip(); return true;"><?=$page['name']?></a></td>
					</tr>
				</table></td><td background="<?=$bg_img?>" valign="bottom">
					<?=($from_popup && $this->site->admin_access()) ? '<input type="checkbox" name="with_selected_ids['.$page['pageid'].']">&nbsp;' : ''?>
					<?=(!$from_popup) ? '(' : ''?><a href="#" onClick="Javascript:<?=$href_prefix?>.location.href='page.php?pageid=<?=$page['pageid']?>&template_edit=1'; return false;" onmouseover="show_tooltip(event,'Contents - <?=addslashes(htmlspecialchars($XTRAS->name("page/templates", $page['template'])));?> (ID: <?=$page['pageid']?>)', 'Edit the Contents of &quot;<?=addslashes(htmlspecialchars($page['name']))?>&quot;',300); return true;" onmouseout="hide_tooltip(); return true;"><?=($from_popup) ? '<img src="images/icons/contents.gif" height=15 width=15 border=0>' : 'contents'?></a><?=(!$from_popup) ? ')' : ''?></td>
				</tr>
				<?
				if (isset($page['childids']) && !empty($page['childids']) && ($open||$static)) {
					if ($end) $depth[count($depth)-1] = false;
					$this->print_page_hierarchy($page['childids'], $depth, $static, $from_popup);
				}
			}
		}
	}


	/**
	* Simply remebers which pages are open in the displayed tree
	*
	* @access  private
	*/
	function _print_page_hierarchy_open_pages() {
		global $print_page_hierarchy_open_pages_called;
		$session = &get_mysource_session();
		if($print_page_hierarchy_open_pages_called) return 0;

		 ##########################################################
		# Remember which pages have been opened in the printed tree
		if(!$open_pages = &$session->get_var('site_backend_open_pages')) {
			$open_pages = array();
			$session->set_var("site_backend_open_pages",$open_pages);
		}

		$open_pageid = $_POST['open_pageid'];
		$close_pageid = $_POST['close_pageid'];
		$expand_all_pageid = $_POST['expand_all_pageid'];
		$collapse_all_pageid = $_POST['collapse_all_pageid'];
		if ($expand_all_pageid != "") {
			$this->_print_page_hierarchy_expand_all($open_pages, $expand_all_pageid);
		}
		if($open_pageid && !in_array($open_pageid,$open_pages)) {
			$open_pages[] = $open_pageid;
		}
		if ($collapse_all_pageid != "") {
			$this->_print_page_hierarchy_collapse_all($open_pages, $collapse_all_pageid);
		}
		if($close_pageid) {
			if (!empty($open_pages)) {
				foreach($open_pages as $id => $pageid) {
					if($close_pageid == $pageid) {
						unset ($open_pages[$id]);
					}
				}
			}
		}
		$index = &$this->site->get_page_index();
		if (!empty($open_pages)) {
			foreach($open_pages as $id => $pageid) {
				if(isset($index[$pageid])) $index[$pageid]['open'] = true;
			}
		}

		return ($print_page_hierarchy_open_pages_called = 1);
	}

	/**
	* Opens out all the pages under the given page
	*
	* @param   array int $open_pages	The array of pageids representing pages that are currently being displayed in the page heirarchy
	* @param   int $pageid				The pageid to start the expansion from
	* @access  private
	*/
	function _print_page_hierarchy_expand_all(&$open_pages, $pageid=0) {
		$index = &$this->site->get_page_index();
		if (!empty($index[$pageid]['childids'])) {
			if (!in_array($pageid, $open_pages)) {
				$open_pages[] = $pageid;
			}
			foreach ($index[$pageid]['childids'] as $childid) {
				$this->_print_page_hierarchy_expand_all($open_pages, $childid);
			}#end foreach
		}#end if
	}#end _print_page_hierarchy_expand_all()

	/**
	* Closes out all the pages under the given page
	*
	* @param   array int $open_pages	The array of pageids representing pages that are currently being displayed in the page heirarchy
	* @param   int $pageid				The pageid to start collapsing from
	* @access  private
	*/
	function _print_page_hierarchy_collapse_all(&$open_pages, $pageid=0) {
		$index = &$this->site->get_page_index();
		if (!empty($index[$pageid]['childids'])) {
			if (!empty($open_pages)) {
				foreach($open_pages as $id => $open_pageid) {
					if($open_pageid == $pageid) {
						unset ($open_pages[$id]);
					}
				}
			}
			foreach ($index[$pageid]['childids'] as $childid) {
				$this->_print_page_hierarchy_collapse_all($open_pages, $childid);
			}#end foreach
		}#end if
	}#end _print_page_hierarchy_collapse_all()

	/**
	* Prints an interface for ordering pages
	*
	* @access  private
	*/
	function _print_page_orderer() {
		$backend = &$this->web_system->get_backend();
		$page = null;

		# if they passed a pageid then we are re-ordering a specific pages subpages
		# otherwise we are redordering the top-level pages
		$pageid = $_GET['pageid'];
		if ($pageid) {
			# Get the current page - will return false if none
			$page = &$this->get_page($pageid);
		}

		if (is_object($page)) {
			$curr_obj = &$page;
		} else {
			$curr_obj = &$this->site;
		}

		# Commit changes
		$action = $_POST['action'];
		if ($action == 'Commit' || $action == 'Commit_Auto') {
			$db = &$this->get_db();
			if (is_array($_POST) && !empty($_POST)) {
				foreach($_POST as $key => $childid) {
					if (substr($key, 0, 7) != "childid") continue;
					$orderno = substr($key, 7);
					$db->update("UPDATE page SET orderno='$orderno' WHERE pageid='$childid'");
					$child_page = &$this->get_page($childid);
					if ($child_page->id) {
						$child_page->clear_cache();
					}
				}
			}
			$subpage_auto_order = gpc_stripslashes($_POST['auto_order']);
			if ($subpage_auto_order) {
				if ($subpage_auto_order == 'off') {
					$curr_obj->set_subpage_auto_order(null);
				} else {
					$curr_obj->set_subpage_auto_order($subpage_auto_order);
				}
			}
			$this->site->clear_page_index();
			if ($subpage_auto_order != 'off' || $action == 'Commit') {
				$backend->add_message("Pages reordered");
				$backend->set_javascript("window.opener.refresh();window.close();");
				$backend->print_header();
				return;
			}
		}

		$backend->set_heading("Reorder ".(($page->id)?"Subp":"Main P")."ages","page");
		$backend->set_subheading((($page->id)?$page->name:$this->site->name));
		$backend->set_id_string($this->site->id);
		$backend->hide_tabs();
		$index = &$this->site->get_page_index();
		$childids = $index[$pageid]['childids'];

		?><script language="javascript" type="text/javascript" src="<?=squizlib_href('js', 'form_functions.js');?>"></script><?

		$js = "
			var temp_no = 0;
			var temp_name = 'Blah';
			var f = 'document.edit';
			function swap(a,b) {
				// Swap Pageids
				eval('temp_no = '+f+'.childid' + a + '.value');
				eval(f+'.childid' + a + '.value = '+f+'.childid' + b + '.value');
				eval(f+'.childid' + b + '.value = temp_no');
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
				if (no < ".count($childids).") {
					swap(no, no + 1);
				}
			}

			var reverse = 1;
			function sort_list(sort_num) {
				reverse = sort_num;
				var nameArray = new Array();
				for(var i = 1; i < ".(count($childids) + 1)."; i++)	{
					// put page names into array
					var obj = new Object();
					obj.name    = window.document.edit.elements['name'    + i].value;
					obj.childid = window.document.edit.elements['childid' + i].value;
					obj.i = i;
					nameArray.push (obj)
				}
				nameArray.sort(compare);
				// go through the sorted list and swap pages into their correct positions
				for(var x = 0; x < nameArray.length; x++)	{
					window.document.edit.elements['name'    + (x+1)].value = nameArray[x].name;
					window.document.edit.elements['childid' + (x+1)].value = nameArray[x].childid;
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

			function auto_order() {
				var val = elementValue(window.document.edit.elements['auto_order']);
				var prev_val = window.document.edit.elements['prev_auto_order'].value;
				if (val != undefined) {
					if (val == 'az') {
						sort_list(1);
						window.document.edit.action.value='Commit_Auto';
						window.document.edit.submit();
					} else if (val == 'za') {
						sort_list(-1);
						window.document.edit.action.value='Commit_Auto';
						window.document.edit.submit();
					} else if (val == 'off' && prev_val != '') {
						window.document.edit.action.value='Commit_Auto';
						window.document.edit.submit();
					}
				}
			}

			function set_auto_order_val() {
				var auto_order = window.document.edit.elements['auto_order'];
				var auto_val = '".(is_null($curr_obj->subpage_auto_order)?'off':$curr_obj->subpage_auto_order)."';
				checkRadioButton(auto_order, auto_val);
				window.document.edit.elements['prev_auto_order'].value = auto_val;
			}
		";

		$backend->set_javascript($js);
		$backend->set_hidden_field('prev_auto_order');
		$backend->set_subheading("", "");
		$backend->remove_toolbar_button("preview");
		$backend->print_header();

		if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "msie")) {
			$boxwidth = 40;
		} else {
			$boxwidth = 20;
		}
		$backend->open_section("List");
		$backend->open_field("Auto Re-order of sub-pages", "one_liner");
		echo '<input name="auto_order" type="radio" value="off">Off';
		echo '&nbsp;<input name="auto_order" type="radio" value="az">A-Z';
		echo '&nbsp;<input name="auto_order" type="radio" value="za">Z-A&nbsp;';
		$backend->print_icon_button('javascript: auto_order();',"Set Auto Re-order","create");

		$backend->open_field("Manual ordering");
		if (is_string($curr_obj->subpage_auto_order)) {
			echo '<div class="backend_fineprint">You cannot Manually Reorder while Auto-reodering is on</div>';
		} else {
			echo "<a href=\"javascript:sort_list(1)\" onmouseover=\"show_tooltip(event,'Sort Ascending','Sorts the pages in alphabetical order (A-Z)',200); return true;\" onmouseout=\"hide_tooltip(); return true;\"><img src=\"images/icons/sorta_z.gif\" border=0></a>&nbsp;&nbsp;<a href=\"javascript:sort_list(-1)\" onmouseover=\"show_tooltip(event,'Sort Descending','Sorts the pages in reverse alphabetical order (Z-A)',200); return true;\" onmouseout=\"hide_tooltip(); return true;\"><img src=\"images/icons/sortz_a.gif\" border=0></a><br>";
		}

		$i = 1;
		if (is_array($childids) && !empty($childids)) {
			foreach($childids as $childid) {
				$name = $index[$childid]['name'];
				if (!is_string($curr_obj->subpage_auto_order)) {
					?>
					<a href="javascript:move_up(<?=$i?>)"><img src="images/uparrow.gif" alt="Move Up" border=0></a>
				<?}?>
				<input type=hidden name="childid<?=$i?>" value=<?=$childid?>>
				<input type=text name="name<?=$i?>" value="<?=str_replace("\"","&quot;",$name)?>" size=<?=$boxwidth?> onfocus=blur()>
				<?
				if (!is_string($curr_obj->subpage_auto_order)) {
					?>
					<a href="javascript:move_down(<?=$i?>)"><img src="images/downarrow.gif" alt="Move Down" border=0></a>
					<?
				}
				echo '<br>';
				$i++;
			}
		}

		# set the reorder value
		?><script language="javascript" type="text/javascript">set_auto_order_val();</script><?

		if (!is_string($curr_obj->subpage_auto_order)) {
			$backend->print_commit_button("Commit","document.edit.action.value='Commit';document.edit.submit();");
		}

		$backend->print_footer();

	}#end _print_page_orderer()


	/**
	* Prints the interface for the floating site map
	*
	* @access  public
	*/
	function print_floating_site_map() {
		$action = $_POST['action'];
		$backend = &$this->setup_backend();
		$session = &get_mysource_session();

		if ($action == 'Connect') {
			$parentid = $_POST['parentid'];
			$childid = $_POST['childid'];
			$child = new Page($childid,$this->web_system);
			$backend->add_message($child->page_move($parentid));
			$this->site->clear_page_index();
			$index = &$this->site->get_page_index();
		} elseif ($action == 'Dock') {
			$session->unset_var('floating_site_map_open');
			?><script language="Javascript">window.close();</script><?
			return;
		} elseif ($action == 'Commit') {
			$with_selected_action = trim($_POST['with_selected_action']);
			$with_selected_ids = $_POST['with_selected_ids'];
			$with_selected_children = $_POST['with_selected_children'];
			if ($this->site->admin_access() && $with_selected_action && !empty($with_selected_ids)) {
				switch ($with_selected_action) {
					case 'live' :
						if (!empty($with_selected_ids)) {
							foreach ($with_selected_ids as $pageid => $on) {
								$page = &$this->get_page($pageid);
								$backend->add_message($page->add_status('L'));
								if($with_selected_children) {
									$subpageids = $page->get_all_subpageids();
									if (!empty($subpageids)) {
										foreach($subpageids as $subpageid) {
											$subpage = &$this->get_page($subpageid);
											$backend->add_message($subpage->add_status('L'));
											unset($subpage);
											$this->web_system->forget_page($subpageid);
										}
									}
								}
								unset($page);
								$this->web_system->forget_page($pageid);
							}
						}
						break;
					case 'under_construction' :
						if (!empty($with_selected_ids)) {
							foreach ($with_selected_ids as $pageid => $on) {
								$page = &$this->get_page($pageid);
								$backend->add_message($page->add_status('U'));
								if($with_selected_children) {
									$subpageids = $page->get_all_subpageids();
									if (!empty($subpageids)) {
										foreach($subpageids as $subpageid) {
											$subpage = &$this->get_page($subpageid);
											$backend->add_message($subpage->add_status('U'));
											unset($subpage);
											$this->web_system->forget_page($subpageid);
										}
									}
								}
								unset($page);
								$this->web_system->forget_page($pageid);
							}
						}
						break;
					case 'show' :
						if (!empty($with_selected_ids)) {
							foreach ($with_selected_ids as $pageid => $on) {
								$page = &$this->get_page($pageid);
								$backend->add_message($page->set_visible(1));
								if($with_selected_children) {
									$subpageids = $page->get_all_subpageids();
									if (!empty($subpageids)) {
										foreach($subpageids as $subpageid) {
											$subpage = &$this->get_page($subpageid);
											$backend->add_message($subpage->set_visible(1));
											unset($subpage);
											$this->web_system->forget_page($subpageid);
										}
									}
								}
								unset($page);
								$this->web_system->forget_page($pageid);
							}
						}
						break;
					case 'hide' :
						if (!empty($with_selected_ids)) {
							foreach ($with_selected_ids as $pageid => $on) {
								$page = &$this->get_page($pageid);
								$backend->add_message($page->set_visible(0));
								if($with_selected_children) {
									$subpageids = $page->get_all_subpageids();
									if (!empty($subpageids)) {
										foreach($subpageids as $subpageid) {
											$subpage = &$this->get_page($subpageid);
											$backend->add_message($subpage->set_visible(0));
											unset($subpage);
											$this->web_system->forget_page($subpageid);
										}
									}
								}
								unset($page);
								$this->web_system->forget_page($pageid);
							}
						}
						break;
					case 'public' :
						if (!empty($with_selected_ids)) {
							foreach ($with_selected_ids as $pageid => $on) {
								$page = &$this->get_page($pageid);
								$backend->add_message($page->set_public(1));
								if($with_selected_children) {
									$subpageids = $page->get_all_subpageids();
									if (!empty($subpageids)) {
										foreach($subpageids as $subpageid) {
											$subpage = &$this->get_page($subpageid);
											$backend->add_message($subpage->set_public(1));
											unset($subpage);
											$this->web_system->forget_page($subpageid);
										}
									}
								}
								unset($page);
								$this->web_system->forget_page($pageid);
							}
						}
						break;
					case 'private' :
						if (!empty($with_selected_ids)) {
							foreach ($with_selected_ids as $pageid => $on) {
								$page = &$this->get_page($pageid);
								$backend->add_message($page->set_public(0));
								if($with_selected_children) {
									$subpageids = $page->get_all_subpageids();
									if (!empty($subpageids)) {
										foreach($subpageids as $subpageid) {
											$subpage = &$this->get_page($subpageid);
											$backend->add_message($subpage->set_public(0));
											unset($subpage);
											$this->web_system->forget_page($subpageid);
										}
									}
								}
								unset($page);
								$this->web_system->forget_page($pageid);
							}
						}
						break;
				}
			}
		}
		$active_pageid = $_POST['active_pageid'];
		$backend->set_hidden_field('childid');
		$backend->set_hidden_field('parentid');
		$backend->set_hidden_field('active_pageid',$active_pageid);
		$backend->set_hidden_field('open_pageid');
		$backend->set_hidden_field('close_pageid');
		$backend->set_hidden_field('expand_all_pageid');
		$backend->set_hidden_field('collapse_all_pageid');

		$backend->print_floating_site_map_header();

		?>
		<script language="javascript">
		function markAll(form, on) {

			for(var j=0; j < form.elements.length; j++) {
				if (form.elements[j].type == 'checkbox' && form.elements[j].name != 'with_selected_children') {
					if (form.elements[j].checked != on) {
						form.elements[j].checked = on;
					}
				}
			}// end for

		}// end markAll()
		</script>
		<table width="100%" cellpadding="3" cellspacing="0" border="0" background="">
			<tr>
				<td bgcolor="#FFFFFF" valign="top">
		<?


		if($this->site->admin_access() && count($this->site->get_top_pageids()) > 1) {
			$backend->print_icon_button('javascript: popup_page_orderer('.$this->site->id.',0)','Reorder the main pages','reorder');
			echo '&nbsp;Reorder Main Pages';
		}

		if($_POST['open_pageid'] || $_POST['close_pageid']) {
			$backend->set_jumpto($site_map_jump);
		}
		?>
		<table cellpadding=0 cellspacing=0 border=0>
			<tr>
				<td><a href="javascript:if (connect_child > 0) {document.edit.action.value='Connect';document.edit.childid.value=connect_child;document.edit.parentid.value='0';document.edit.submit()}" onmouseover="if (connect_child > 0) {window.status='Make \''+connect_child_name+'\' a Main Page.';} else{window.status='';} return true;" onmouseout="window.status='';return true;"><img src="images/icons/site.gif" width=20 height=20 border=0></a></td>
				<td width="100%"><p class=backend_data></td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td><a href="javascript:expand_all_page(0);"><img src="images/expand_all.gif" width="82" height="19" border="0"></a></td>
				<td>&nbsp;&nbsp;&nbsp;</td>
				<td><a href="javascript:collapse_all_page(0);"><img src="images/collapse_all.gif" width="88" height="19" border="0"></a></td>
			</tr>
		</table>
		<table cellpadding=0 cellspacing=0 border=0 width=100%>
			<?
			$this->print_page_hierarchy($this->site->get_top_pageids(),'','',true);
			?>
		</table>
		</td></tr>
		<?
		if ($this->site->admin_access()) {
			?>
			<tr>
				<td style="font-size=10" align="right" bgcolor="#FFFFFF">&nbsp;<b>With Selected</b>&nbsp;
				<select name="with_selected_action" style="font-size=10">
					<option value="">&nbsp;</option>
					<option value="live">Make Live</option>
					<option value="under_construction">Place Under Contruction</option>
					<option value="show">Show In Menu</option>
					<option value="hide">Hide In Menu</option>
					<option value="public">Make Public</option>
					<option value="private">Make Private</option>
				</select>
				&nbsp;<b>Affect Children</b>&nbsp;<input type="checkbox" name="with_selected_children" value="on">
				</td>
			</tr>
			<tr>
				<td>
					<table border="0" width="100%" bgcolor="#F0F0F0" cellspacing="0" cellpadding="0">
						<tr>
						<td>
							<span class="sectionheading"><input type="button" class="field" value="Update" onclick="document.edit.submit()"><script language="javascript">function checksubmitform() { document.edit.submit()}</script></span>
						</td>
						<td style="font-size=10" align="right">
							&nbsp;<b>Check/Uncheck All</b>&nbsp;<input type="checkbox" name="master" value="on" onClick="javascript: markAll(this.form, this.checked);">
						</td>
						</tr>
					</table>
				</td>
			</tr>
			<?
		}
		?></table><?

		$backend->print_footer();

		$floating_site_map_open = $session->get_var('floating_site_map_open');

		if (!$floating_site_map_open) {
			?>
			<script language="Javascript">
				window.opener.edit.action.value = '';
				window.opener.edit.submit();
			</script>
			<?
			# register in the session that the popup is open
			$open = true;
			$session->set_var('floating_site_map_open', $open);
		}
		$session->set_var('floating_site_map_siteid', $this->site->id);
	}

	/*********************************************
	* Prints out a help text for backend fields
	*********************************************/
	function _print_feature_helper() {
		$action = $_POST['action'];
		#set up a nice backend page...
		$backend = &$this->web_system->get_backend();
		$backend->set_heading("Help System","page");
		$backend->hide_tabs();
		$backend->print_help_header();

		?>
			<table border="0" cellspacing="5" cellpadding="2"><tr><td>
		<?

		# For all cases except when we're editing the content, we want to display the current help.
		# This is a flag to help us out :)
		$display_current_help = true;

		# Same for the table - this just provides a little formatting (indenting)
		$end_table = true;

		 ######################################################
		# do the db inserts, updates, or deletes

		if (isset($action)) {	#if an action was taken (a button was clicked), act!
			switch ($action) {
				case 'Commit_Help' :	#a new helptext is to be created

					#get the name of the field
					$fieldname = addslashes(rawurldecode($_GET['fieldname']));
					$helptext = addslashes(trim($_POST['helptext']));
					if ($helptext !='') {
						$db = &$this->get_db();
						$db->insert("INSERT INTO mysource_help (feature_name,feature_help_text) VALUES ('".$fieldname."','".$helptext."')");
					}
					$backend->add_message("Added helptext for field:\n'" . stripslashes($fieldname) . "'");
					break;

				case 'Edit_Help' :	#an admin wants to edit a helptext. display the form

					#get the name of the field
					$fieldname = addslashes(rawurldecode($_GET['fieldname']));
					$db = &$this->get_db();

					#get the helptext for this field
					$helptext = $db->single_element("SELECT feature_help_text FROM mysource_help WHERE feature_name= '$fieldname'");
					?>
					<br><br>
					Hit Commit to save changes. <br>
					If you commit an empty form, the current helptext will be deleted from the database.<br><br>
					<textarea wrap=on name="helptext" cols="50" rows="6" class="backend_data"><?=stripslashes($helptext)?></textarea>
					<br><br>
					<span class="backend_fineprint"></span><a href="javascript:parent.window.close()" onMouseOver="window.status='Close the help system';return true;" onMouseOut=" window.status='';return true;">[ Close help system (without saving!) ]</a></span>

					<!-- close the main table now //-->
						</td></tr></table>
					<?
					$end_table = false;
					$backend->print_commit_button("Commit","document.edit.action.value='Updated_Help';document.edit.submit();");
					$display_current_help = false;
					break;

				case 'Updated_Help' :	# an admin has commited the changes. helptext needs to be changed in the db
					#get the name of the field
					$fieldname = addslashes(rawurldecode($_GET['fieldname']));
					$helptext = addslashes(trim($_POST['helptext']));
					$db = &$this->get_db();

					# delete this help-id if the text is empty.
					if ($helptext == '') {
						$db->delete("DELETE FROM mysource_help WHERE feature_name='$fieldname'");
						$backend->add_message("Deleted helptext for field\n'" . stripslashes($fieldname) . "'");
					} else {
						# update the helptext
						$db->update("UPDATE mysource_help SET feature_help_text='$helptext' WHERE feature_name='$fieldname'");
						$backend->add_message("Edited helptext for field\n'" . stripslashes($fieldname) . "'");
					}
					break;
			} #end_switch
		}
		if ($display_current_help) {
			######################################################
			# now display the normal help page (or the create help text, if none exists)
			# get the name of the field
			$fieldname = addslashes(rawurldecode($_GET['fieldname']));
			$db = &$this->get_db();
			# get the helptext for this field
			$helptext = $db->single_element("SELECT feature_help_text FROM mysource_help WHERE feature_name= '$fieldname'");

			# if a helptext exists...
			if ($helptext != '') {
				# if they are an admin, let them edit the help text
				if($this->site->admin_access()) {
				?>
					<a href="javascript:document.edit.action.value='Edit_Help'; document.edit.submit()" onmouseover="window.status='Edit this helptext'; return true;" onmouseout="window.status='';return true;"><img src="images/icons/contents.gif" width=20 height=20 border=0> Edit this helptext</a>
				<?
				} #end_if_admin
				echo "<br><br>" . nl2br(stripslashes(strip_tags($helptext, '<p><b><i><u><br><br/><br />')));
				?>
				<br><br></b></b><span class="backend_fineprint"></span><a href="javascript:parent.window.close()" onMouseOver="window.status='Close the help system';return true;" onMouseOut=" window.status='';return true;">[ Close help system ]</a></span>
				<?
			} else {
				# if NO helptext exists, print the edit form (only if they are admins)
				?>
				<br><br><span class="backend_warning">No help found for field '<?=stripslashes(stripslashes($fieldname))?>'!</span>
				<?
				# print a edit form if they are an admin
				if($this->site->admin_access()) {
					?>
					<br><br>
					To add a help text to this field, just type it in the field below and hit commit. <br>
					(You can use HTML codes, if you want)<br><br>
					<textarea wrap=on name="helptext" cols="50" rows="6" class="backend_data"></textarea>
					<br><br>
					<span class="backend_fineprint"></span><a href="javascript:parent.window.close()" onMouseOver="window.status='Close the help system';return true;" onMouseOut=" window.status='';return true;">[ Close help system (without saving!) ]</a></span>
					<br><br>
					<!-- close the main table now //-->
						</td></tr></table>
					<?
					$end_table = false;
					$backend->print_commit_button("Commit","document.edit.action.value='Commit_Help';document.edit.submit();");
				} #end if admin

			} #end_if_helptext
		} # end if (!isset($action))

		if ($end_table) {
			?></td></tr></table><?
		}

		$backend->print_footer();
	}#end _print_feature_helper()

}#end class


?>

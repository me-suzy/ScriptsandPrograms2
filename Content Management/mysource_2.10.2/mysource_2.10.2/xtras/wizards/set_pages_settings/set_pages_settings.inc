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
## $Source: /home/cvsroot/xtras/wizards/set_pages_settings/set_pages_settings.inc,v $
## $Revision: 1.4 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:13 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Extract keywords Wizard
* A wizard that extracts the keywords from all the pages in your site. 
* This wizard works on a web system, site, or page level.
*
* @access public
* @package Wizards
*/
class Set_Pages_Settings extends Wizard {
	
	/**
	* The description of the wizard
	* @var string
	*/
	var $report_desc = 'Set page settings to pages within a site by bulk';

	/**
	* An array of levels that the wizard is compatible with
	* @var array
	*/
	var $compatible_with = array('site');

	/**
	* An array of default parameter set options for the wizard
	*
	* @var array
	*/
	var $parameters = array();

	/**
	* Constructor
	* @param	object &$asset This is the current asset
	* @returns	object Extract_keywords
	* @access	public
	*/
	function Set_Pages_Settings(&$asset) {
		Wizard::Wizard($asset);
	}

	/**
	* This function does all the processing
	* @param  object &$backend A reference to the wizard backend object
	* @access public
	*/
	function process_wizard_web(&$backend) {
		$session = &get_mysource_session();
		if($session->get_var('set_pages_settings_wizard_finished')) {
			$backend = &$this->print_summary($backend);
			$done = 0;
			$session->set_var('set_pages_settings_wizard_finished', $done);
		} else {
			$web_system = &$this->get_web_system();
			# we need to do this for all sites
			$site = &$web_system->get_site($this->asset_id);
			$todo_pageids = $session->get_var('set_pages_settings_wizard_todo_pageids');

			# Empty and not isset or is_array cause they may have run it before and that's the leftover
			if (empty($todo_pages)) {
				$site_page_index = $site->get_page_index();
				$todo_pageids = array_keys($site_page_index);
			}
			$session->set_var('set_pages_settings_wizard_todo_pageids', $todo_pageids);

			$backend->open_section();
			$backend->open_field('Processing, please wait...');
			# Loop of the pages and set whatever settings we should set
			$this->do_set_page_settings();
		}
		return $backend;
	}

	/** 
	* This function handles the continual calling of the do_extract_keywords function to display the popup
	* 
	* @access public
	* @returns void
	*/
	function process_special_action($action) {
		if ($action == 'set_page_settings') {
			$this->do_set_page_settings();
		} elseif ($action == 'finished') {
			echo status_popup('100','',false,true,'#330099','Page Setting Complete',"<script language=\"Javascript\">window.opener.location='".$this->get_backend_href()."&active_step=2';</script>");
			$session = &get_mysource_session();
			$done = 1;
			$session->set_var('set_pages_settings_wizard_finished', $done);
			exit();
		}
	}

	/** 
	* Prints a text summary of the pages that have been processed and whether or not the keywords have changed
	*
	* @access public
	* @returns object $backend
	*/
	function print_summary(&$backend) {
		$session = &get_mysource_session();
		$backend->open_section('Summary');
		$backend->open_field();
		echo "Here is a summary of the pages modified:<p>";
		echo $session->get_var('set_pages_settings_wizard_report');
		return $backend;
	}

	/** 
	* Function that does the actual processing and controls the status popup
	*
	* At the moment the status popup is refreshed every time a page's keywords are extracted 
	* so that the processing doesn't time out. The pages to process are stored in the session.
	* The report that is displayed at the end is also stored in the session and added to 
	* as each page is processed. 
	*
	* <BR>The status popup is called once first to initialise all the variables such as how many pages per
	* refresh should be processed.
	* <BR>The next time it 

	*
	* @access public
	* @returns void
	* @see process_special_action()
	*/
	function do_set_page_settings() {
		$session = &get_mysource_session();
		$num_to_process     = $_GET['num_to_process'];
		$num                = $_GET['num'];
		$num_processed      = $_GET['num_processed'];
		$started            = $_GET['started'];
		$start_time         = $_GET['start_time'];
		$action             = $_GET['action'];

		set_time_limit(0);

		if (isset($num) && isset($num_to_process)) {
			if (!$started) {
				$now = time();
				$process_url = $this->get_backend_href()."&num_to_process=$num_to_process&num=$num&num_processed=0&action=set_page_settings&started=1&start_time=$now";
				echo status_popup(1,$process_url,false,false,'#330099','Performing Page Setting - Please wait', "Starting to process pages for $num_to_process pages");
				$report = '';
				$session->set_var('set_pages_settings_wizard_report', $report);
				exit();
			}

			# array of key=>pageids that need to process
			$pageids = $session->get_var('set_pages_settings_wizard_todo_pageids');

			# extract keywords for $num pages
			if (!empty($pageids)) {
				$i = 0;
				$web_system = &$this->get_web_system();
				foreach ($pageids as $index => $pageid) {
					$page = &$web_system->get_page($pageid); # try and get the page
					if($page->id) { # make sure we've actually got a page
						unset($pageids[$index]);
						$report = $session->get_var('set_pages_settings_wizard_report');
						if ($page->change_status_access()) {
							$status = $this->parameters['page_status'];
							switch($status) {
								case 'l':
        	                                                        if (!$page->live()) {
	                                                                        $page->add_status('L');
                	                                                        $report .= "Page $page->name set to Live<br />";
                        	                                        } else {
                                	                                        $report .= "Page $page->name already Live<br />";
                                        	                        }
									break;
								case 'u':
									$current_status = $page->status();
                                                                        if ($current_status != 'U') {
                                                                                $page->add_status('U');
                                                                                $report .= "Page $page->name set to Under Construction<br />";
                                                                        } else {
                                                                                $report .= "Page $page->name already Under Construction<br />";
                                                                        }
									break;
								default:
									break;
							}
						} else {
							$report .= "You do not have permission to change the status of  $page->name<br />";
						}
						$session->set_var('set_pages_settings_wizard_report', $report);
					}
					$i++;
					if ($i >= $num) break;
				}
			}
			$session->set_var('set_pages_settings_wizard_todo_pageids', $pageids);

			$num_processed += $i;
			$percent = ceil(($num_processed / $num_to_process) * 100);
			$finish = (($num_processed >= $num_to_process) ? 1 : 0);
			$time_diff = time() - $start_time;
			$time_per_lookup = $time_diff / $num_processed;
			$time_left = $time_per_lookup * ($num_to_process - $num_processed);
			if ($time_left <= 0) $time_left = 1;
			$status = '';

			if ($finish) {
				$process_url = $this->get_backend_href()."&action=finished&started=1";
				$status = 'Finishing settings page settings ...';
				$percent = 100;
			} else {
				$process_url = $this->get_backend_href()."&num_to_process=$num_to_process&num=$num&num_processed=$num_processed&action=set_page_settings&started=1&start_time=$start_time";
				$status = "Completed $num_processed pages - ".($num_to_process - $num_processed).' remaining - est time: '.easy_time_total($time_left);
			}

			echo status_popup($percent,$process_url,false,false,'#330099','Performing Page Setting - Please wait',$status);
			exit();
		}

		$num_to_process = count($session->get_var('set_pages_settings_wizard_todo_pageids'));
		$num = 1; # pages to extract at a time
		$process_url = $this->get_backend_href()."&num_to_process=$num_to_process&num=$num&num_processed=0&action=set_page_settings&started=0";
		echo status_popup(1,$process_url,true,false,'#330099','Performing Page Setting - Please wait', "Starting to process pages for $num_to_process pages");
	}

	/** 
	* Gets HREF of the backend (for the continual refresh of the status popup) depending on where you are
	* 
	* Returns the correct Backend HREF on a page, site or web system level.
	*
	* @returns string
	* @access public
	*/
	function get_backend_href() {
		$web_system = &$this->get_web_system();
		$site = &$web_system->get_site($this->asset_id);
		return $site->get_backend_href().'&PARAMETER_SCREEN=wizards&wizard_type=set_pages_settings';
	}
}
?>

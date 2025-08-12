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
## $Source: /home/cvsroot/xtras/wizards/extract_keywords/extract_keywords.inc,v $
## $Revision: 1.5 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
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
class Extract_keywords extends Wizard {
	
	/**
	* The description of the wizard
	* @var string
	*/
	var $report_desc = 'Extract keywords from your content';

	/**
	* An array of levels that the wizard is compatible with
	* @var array
	*/
	var $compatible_with = array('site','page');

	/**
	* An array of default parameter set options for the wizard
	*
	* Its an array inside an array because its a checkbox option 
	*
	* @var array
	*/
	var $parameters = array('process_duplicates' => array('1'));

	
	/**
	* Constructor
	* @param	object &$asset This is the current asset
	* @returns	object Extract_keywords
	* @access	public
	*/
	function Extract_keywords (&$asset) {	
		Wizard::Wizard($asset);
	}


	/**
	* This function does all the processing
	* @param  object &$backend A reference to the wizard backend object
	* @access public
	*/
	function process_wizard_web(&$backend) {
		$session = &get_mysource_session();
		if($session->get_var('extract_keywords_wizard_finished')) {
			$backend = &$this->print_summary($backend);
			$done = 0;
			$session->set_var('extract_keywords_wizard_finished', $done);
		} else {
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
		
			$looped_pages = $page_index;
			$session->set_var('extract_keywords_wizard_pageids',$looped_pages); 

			$session = &get_mysource_session();
			$backend->open_section();
			$backend->open_field('Processing, please wait...');
			# now loop over all the pages and extract the keywords
			$web_system = &$this->get_web_system();
			$counter = 0;

			$this->do_extract_keywords();
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
		if ($action == 'Extract') {
			$this->do_extract_keywords();
		} else if ($action == 'Extraction_finished') {
			echo status_popup('100','',false,true,'#330099','Keyword Extraction complete',"<script language=\"Javascript\">window.opener.location='".$this->get_backend_href()."&active_step=2';</script>");
			$session = &get_mysource_session();
			$done = 1;
			$session->set_var('extract_keywords_wizard_finished', $done);
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
		echo "Here is a summary of the pages that this wizard has extracted keywords for:<p>";
		echo $session->get_var('extract_keywords_wizard_report');
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
	function do_extract_keywords() {
		$session = &get_mysource_session();
		$num_to_extract     = $_GET['num_to_extract'];
		$num                = $_GET['num'];
		$num_extracted      = $_GET['num_extracted'];
		$started            = $_GET['started'];
		$start_time         = $_GET['start_time'];
		$action             = $_GET['action'];

		$db = &$this->get_db();
		set_time_limit(0);

		if (isset($num) && isset($num_to_extract)) {
			if (!$started) {
				$now = time();
				$process_url = $this->get_backend_href()."&num_to_extract=$num_to_extract&num=$num&num_extracted=0&action=Extract&started=1&start_time=$now";
				echo status_popup(1,$process_url,false,false,'#330099','Performing Keyword Extraction - Please wait', "Extracting keywords for $num_to_extract pages");
				$report = '';
				$session->set_var('extract_keywords_wizard_report', $report);
				exit();
			}

			# array of key=>pageids that need to have their keywords extracted.
			$pageids = $session->get_var('extract_keywords_wizard_pageids');

			$web_system = &$this->get_web_system();
			# extract keywords for $num pages
			for ($i = 0; $i < $num; $i++) {
				$index = $num_extracted + $i; # the offset for the pageid we're looking for 
				reset($this->parameters['process_duplicates']);
				$page = &$web_system->get_page($pageids[$index]); # try and get the page
				if($page->id) { # make sure we've actually got a page
					$template = $page->get_template(); # get the template
					$keywords = $template->extract_keywords(); # extract the keywords from the content
					# save the keywords with the page 
					$message = $page->set_keywords($keywords,current($this->parameters['process_duplicates'])); 
					$report = $session->get_var('extract_keywords_wizard_report');
					if(empty($message)) {
						$report .= 'Keywords already set for \''.$page->name.'\'<br>';
					} else {
						$report .= $message.'<br>'; 
					}
					$session->set_var('extract_keywords_wizard_report', $report);
				}
			}

			$num_extracted += $num;
			$percent = ceil(($num_extracted / $num_to_extract) * 100);
			$finish = (($num_extracted >= $num_to_extract) ? 1 : 0);
			$time_diff = time() - $start_time;
			$time_per_lookup = $time_diff / $num_extracted;
			$time_left = $time_per_lookup * ($num_to_extract - $num_extracted);
			if ($time_left <= 0) $time_left = 1;
			$status = '';

			if ($finish) {
				$process_url = $this->get_backend_href()."&action=Extraction_finished&started=1";
				$status = 'Finishing Extract Keywords...';
				$percent = 100;
			} else {
				$process_url = $this->get_backend_href()."&num_to_extract=$num_to_extract&num=$num&num_extracted=$num_extracted&action=Extract&started=1&start_time=$start_time";
				$status = "Completed $num_extracted keyword extractions - ".($num_to_extract - $num_extracted).' remaining - est time: '.easy_time_total($time_left);
			}

			echo status_popup($percent,$process_url,false,false,'#330099','Performing Keyword Extraction - Please wait',$status);
			exit();
		}

		
		$num_to_extract = count($session->get_var('extract_keywords_wizard_pageids'));
		$num = 1; # pages to extract at a time
		$process_url = $this->get_backend_href()."&num_to_extract=$num_to_extract&num=$num&num_extracted=0&action=Extract&started=0";
		echo status_popup(1,$process_url,true,false,'#330099','Performing Keyword Extraction - Please wait', "Starting to extract keywords for $num_to_extract pages");
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
		switch($this->asset_type) {
			case 'page':
				$web_system = &$this->get_web_system();
				$asset = &$web_system->get_page($this->asset_id);
				return $asset->get_backend_href().'&PARAMETER_SCREEN=wizards&wizard_type=extract_keywords';
				break;
			case 'site':
				$web_system = &$this->get_web_system();
				$asset = &$web_system->get_site($this->asset_id);
				return $asset->get_backend_href().'&PARAMETER_SCREEN=wizards&wizard_type=extract_keywords';
				break;
			case 'web':
				$asset = &$this->get_web_system();
				return $asset->get_backend_href().'&web_section=wizards&wizard_type=extract_keywords';
				break;
		}
		
	}
}

?>
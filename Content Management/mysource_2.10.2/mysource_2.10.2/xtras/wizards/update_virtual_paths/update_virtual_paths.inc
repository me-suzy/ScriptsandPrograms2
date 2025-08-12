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
## $Source: /home/cvsroot/xtras/wizards/update_virtual_paths/update_virtual_paths.inc,v $
## $Revision: 1.5 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:13 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Update Virtual Paths Wizard
* A wizard that updates all the virtual paths to be either the page short name
* or the page name. This wizard works on a web system, site, or page
* level.
*
* @access public
* @package Wizards
*/
class Update_virtual_paths extends Wizard {
	
	/**
	* The description of the wizard
	* @var string
	*/
	var $report_desc = 'Automatically set virtual paths';

	/**
	* An array of levels that the wizard is compatible with
	* @var array
	*/
	var $compatible_with = array('site','page','web');

	/**
	* An array of default parameter set options for the wizard
	* @var array
	*/
	var $parameters = array('source' => 'short_name');

	
	/**
	* Constructor
	* @param	object &$asset This is the current asset
	* @returns	object Update_virtual_paths
	* @access	public
	*/
	function Update_virtual_paths (&$asset) {
		Wizard::Wizard($asset);
	}

	/*
	*
	*/
	function get_siteids_page_list() {
	
		$web_system = &$this->get_web_system();

		$siteids = array();

		switch($this->asset_type) {
			case 'web':
				$page_list = array();

				# we need to do this for all sites
				$sites = &$web_system->get_editable_sites();
				foreach ($sites as $siteid => $name) {
					$site = $web_system->get_site($siteid);
					$site->clear_page_index(true);
					$site_page_index = &$site->get_page_index();
					$site_page_index = array_keys($site_page_index);
					$page_list = array_merge($page_list, $site_page_index);
					unset($site);
					unset($site_page_index);
					array_push($siteids, $siteid);
				}
			break;

			case 'page':
				$page_index = &$this->caller->get_all_subpageids();

				# This is already an array so let's leave it at that.
				$page_list = $page_index;

				# add ourselves to the list.
				array_push($page_list, $this->caller->id);

				array_push($siteids, $this->caller->siteid);
			break;

			case 'site':
			default:
				# Clear the index first before we try to get the list.
				$this->caller->clear_page_index(true);
				$page_index = &$this->caller->get_page_index();
				$page_list = array_keys($page_index);
				array_push($siteids, $this->caller->id);
		}

		return array($page_list,$siteids);
	}


	/**
	* This function does all the processing
	* @param  object &$backend A reference to the wizard backend object
	* @access public
	*/
	function process_wizard_web(&$backend) {

		list($page_list,$siteids) = $this->get_siteids_page_list();

		$msgs = $this->process_paths($this->parameters['source'], $page_list, $siteids);

		foreach($msgs as $msg) {
			echo '<p>'.$msg.'</p>';
		}
	}

	/*
	*
	*/
	function process_wizard_server($jobid) {
		
		list($page_list,$siteids) = $this->get_siteids_page_list();

		$msgs = $this->process_paths($this->parameters['source'], $page_list, $siteids);
	
		return implode("\n",$msgs);
	}


	/**
	* This function does the actual updating of virtual paths for the given pageids
	*
	* The number of pages that have been checked are returned
	*
	* @param	string	$type		Defines whether the page name or short 
	*								name is used for the updating of the virtual path
	* @param	array	$page_index An array of pageid's that we want to update the 
	*								virtual paths for
	* @param	array	$siteids    An array of siteids we are going to modify
	*								so we can rewrite the cache.
	* @return	int
	* @access	public
	*/
	function process_paths($type, $page_index, $siteids = array()){
		$web_system = &$this->get_web_system();
		$counter = 0;
		foreach ($page_index as $pageid){
			# stopping weird bug 
			if ($pageid == 0) { continue; }

			$page = &$web_system->get_page($pageid);

			# get the name that we want to use to convert the virtual path
			switch ($type){
				case 'name':
					$name = $page->name;
					if ($name == '') $name = $page->short_name;
					if ($name == ''){
						$msgs[] = 'The virtual path could not be set for page: '.$pageid.' as there is no name to apply.';
						continue;
					}
					break;

				default:
					$name = $page->short_name;
					if ($name == '') $name = $page->name;
					if ($name == ''){
						$msgs[] = 'The virtual path could not be set for page: '.$pageid.' as there is no name to apply.';
						continue;
					}
			}

			$dirs_list = array($this->clean_up_path($name));

			# We pass in false so at this point the site cache isn't rewritten again.
			$page->update_dirs($dirs_list, false);

			$counter++;
		}

		# Since we didn't write the cache before, let's do it now.
		# That's why we need the siteids :)
		if (is_array($siteids) && !empty($siteids)) {
			foreach($siteids as $siteid) {
				$site = &$web_system->get_site($siteid);
				$site->write_page_index_to_cache();
				unset($site);
			}
		}

		$msgs[] = 'Pages checked: '.$counter;
		return $msgs;
	}

	/**
	* Cleans up the sting that is going to be the virtual path
	*
	* The function cleans the string by removing leading and trailing
	* whitespace and converting any interior spaces to '_'s
	*
	* @param	string $name The string we want to clean up
	* @return	string
	* @access	public
	*/
	function clean_up_path($name){
		$name = trim($name);
		$name = str_replace(' ', '_', $name);
		$name = strtolower($name);

		return $name;
	}
}

?>
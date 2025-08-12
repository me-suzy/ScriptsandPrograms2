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
## $Source: /home/cvsroot/xtras/wizards/notitia_janitor/notitia_janitor.inc,v $
## $Revision: 1.1 $
## $Author: achadszinow $
## $Date: 2004/03/31 07:19:14 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* Notitia Janitor Wizard
* A wizard for performing miscellaneous Notitia tasks.
* This wizard works on a web system, site, or page level.
*
* @access public
* @package Wizards
*/
class Notitia_Janitor extends Wizard {
	
	/**
	* The description of the wizard
	* @var string
	*/
	var $report_desc = 'A wizard for miscellaneous Notitia tasks';

	/**
	* An array of levels that the wizard is compatible with
	* @var array
	*/
	var $compatible_with = array('site','page','web');

	/**
	* An array of default parameter set options for the wizard
	* @var array
	*/
	var $parameters = array('notitia_task' => 'dfr');

	
	/**
	* Constructor
	* @param	object &$asset This is the current asset
	* @returns	object Update_virtual_paths
	* @access	public
	*/
	function Notitia_Janitor(&$asset) {
		Wizard::Wizard($asset);
	}

	/**
	* This function does all the processing
	* @param  object &$backend A reference to the wizard backend object
	* @access public
	*/
	function process_wizard_web(&$backend) {
		echo "This wizard needs to be run using the Squiz Server, please set it up to run this wizard";
	}

	/*
	*
	*/
	function process_wizard_server($jobid) {
		$notitia_task = $this->parameters['notitia_task'];

		switch($notitia_task) {
			case 'dfr':
				$msgs = $this->delete_foreign_records();
				break;
		}

		return implode("\n",$msgs);
	}

	/**
	* Returns a reference to the notitia system
	*
	* @access public
	* @return &object
	*/
	function &get_notitia_system() {
		$web_system = &get_web_system();
		return $web_system->get_extension('notitia');
	}

	/**
	* Returns a reference to an object
	*
	* @access public
	* @return &object
	*/
	function &get_category_tree() {
		$n = &$this->get_notitia_system();
		return $n->get_category_tree();
	}

	/**
	* Gets the category object
	*
	* @param int $categoryid The id of the category
	* @access public
	* @return &object
	*/
	function &get_category($categoryid) {
		$n = &$this->get_notitia_system();
		return $n->get_category($categoryid);
	}

	/**
	* Gets the attribute object
	*
	* @param int $attributeid The id of the attribute
	* @access public
	* @return &object
	*/
	function &get_attribute($attributeid) {
		$n = &$this->get_notitia_system();
		return $n->get_attribute($attributeid);
	}

	/**
	* Gets the record object
	*
	* @param int $recordid The id of the record
	* @access public
	* @return &object
	*/
	function &get_record($recordid) {
		$n = &$this->get_notitia_system();
		return $n->get_record($recordid);
	}

	/**
	* Returns a list of categories, with sticks, but only three levels deep, for selecting the base category with
	*
	* @access public
	* @return array
	*/
	function get_root_category_list() {
		$tree = &$this->get_category_tree();
		$rootid = 0;
		return array($rootid => "[The Notitia Category Root]") + $tree->get_category_list_with_sticks($rootid,100);
	}

	/**
	* Finds and deletes records in one category where they are linked to a record in another specified category
	*
	* @access public
	* @return array
	*/
	function delete_foreign_records() {
		$msgs = array();
		$foreign_categoryid = $this->parameters['dfr']['foreign_categoryid'];
		$foreign_category = &$this->get_category($foreign_categoryid);

		if ($foreign_category->id) {
			# This will give us all records in the foreign category and below
			$foreign_recordids = $foreign_category->search_records(array(), array(), true);

			# Now we have a list of all the records that the local records should be linked to we will now find out what records are in the local category that are also linked to records in the the foreign category
			$local_categoryid = $this->parameters['dfr']['local_categoryid'];
			$local_category = &$this->get_category($local_categoryid);
			if ($local_category->id) {
				$foreign_key_attributeid = $this->parameters['dfr']['foreign_key_attributeid'];
				$foreign_key_attribute = &$this->get_attribute($foreign_key_attributeid);
				if ($foreign_key_attribute->id) {
					$complex_attribute_queries = array(
						$foreign_key_attribute->id => array(
							'_intersect' => array(
								'foreign_records' => $foreign_recordids
							)
						)
					);
					$complex_matching_rules = array(
						'AND',
						'TRUE',
						$foreign_key_attribute->id.'%_intersect%foreign_records'
					);

					$deletable_records = $local_category->search_records(array(), array(), true, 'OR', array(), $complex_attribute_queries, $complex_matching_rules);

					if (!empty($deletable_records)) {
						foreach($deletable_records as $recordid) {
							$record = &$this->get_record($recordid);
							$record->delete();
							$msgs[] = 'Record '.$recordid.' was deleted';
						}
					}
				}
			}
		}

		return $msgs;
	}
}
?>
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
## $Source: /home/cvsroot/xtras/wizards/created_users/created_users.inc,v $
## $Revision: 1.5 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:12 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#


/**
* Created Users Wizard
* A wizard to produce a list of all user accounts created 
* during a given period of time. The results can be
* for any or all organisations.
*
* @access public
* @package Wizards
*/
class Created_Users extends Wizard {

	var $compatible_with = array('users');
	var $parameters = array('date' => '0000-00-00',
							'range' => 'month',
							'export_type' => 'csv',
							'export_show_headers' => 0,
							'export_show_fields' => array('userid','login','firstname',
														 'surname','email','mobile_no',
														 'expiry_date','created_date','comments',
														 'title')
							  );


	 ##############################
	# Constructor
	function Created_Users (&$asset) {	
		Wizard::Wizard($asset);
		$this->add_button('Export','Export',100,3);
	}

	function set_defaults() {
		$this->parameters['date'] = date('Y-m-d');
	}


	function get_date_min() {
		$users = &get_users_system();
		$db = &$users->get_db();
		$sql = "SELECT SUBSTRING(MIN(created_date),1,4) AS first_user FROM user WHERE created_date > '0000-00-00'";
		return $db->single_element($sql).'-01-01 00:00:00';
	}
	function get_date_max() {
		return date('Y').'-12-31 00:00:00';
	}


	/**
	* Returns an array of organisations for use in the select box
	*
	* @access public
	* @returns array
	*/
	function get_org_options() {
		$options = $this->caller->organisation_array_with_sticks();
		$options[0] = 'Unaffiliated Users';
		return $options;
	}

	function process_special_action($action) {
		$backend = &$this->get_backend();
		if ($action == 'Export') {
			# show the export options screen
			$backend->add_button('Export',"document.edit.action.value='Commit_Export';document.edit.submit()");
			$backend->add_button('Cancel',"document.edit.action.value='';document.edit.submit()");
			$pset = &$this->get_pset();
			$pset->print_screen($backend,'User Export');
			exit();
		} else if ($action == 'Commit_Export') {
			$pset = &$this->get_pset();
			$pset->process_screen($backend,'User Export');
			$this->process_export();
			exit();
		}
	}


	function process_export() {
		$userids = $this->get_userids();

		$export_type		 = $this->parameters['export_type'];
		$export_show_headers = $this->parameters['export_show_headers'];
		$export_show_fields  = $this->parameters['export_show_fields'];

		$fields = array();
		foreach ($export_show_fields as $field) $fields[$field] = 1;

		switch($export_type) {
			case 'xml' :
				$this->caller->xml_export($userids,$fields);
				break;
			default :
				$this->caller->csv_export($userids,$export_show_headers,$fields);
		}
	}


	function get_userids() {
		$users = &get_users_system();
		$db = &$users->get_db();

		$range = $this->parameters['range'];
		
		# make a list of organisationids
		$organisationids = '';
		foreach ($this->parameters['organisationids'] as $id) {
			if (!$id) continue;
			$organisationids .= "$id, ";
		}
		$organisationids = '(0'.substr($organisationids,0,-2).')';
		
		# if the user selected Unaffiliated Users as well,
		# ensure that these users are also displayed
		if (in_array(0,$this->parameters['organisationids'])) {
			$organisationids .= ' OR affiliation.organisationid IS NULL';
		}

		list($date) = explode(' ',$this->parameters['date']);
		list($year,$month,$day) = explode('-',$date);

		$stamp = mktime(0,0,0,(($month)?$month:1),(($day)?$day:1),(($year)?$year:2000));


		switch($range) {
			case 'year':
				$range_cond = "SUBSTRING(created_date,1,4) = '$year'";
				$range_desc = 'during the year '.date('Y',$stamp);
				break;
			case 'month':
				$range_cond = "SUBSTRING(created_date,1,7) = '$year-$month'";
				$range_desc = 'during '.date('F, Y',$stamp);
				break;
			case 'day':
				$range_cond = "SUBSTRING(created_date,1,10) = '$year-$month-$day'";
				$range_desc = 'today';
				break;
			default:
				$range_cond = '1=1';
		}

		$userids = $db->single_column("SELECT DISTINCT(user.userid) FROM user LEFT JOIN affiliation ON affiliation.userid=user.userid WHERE $range_cond AND (affiliation.organisationid IN $organisationids) ORDER BY user.created_date DESC");

		return $userids;
	}


	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {
		
		if (!method_exists($backend,'open_field')) $backend = &$this->get_backend();
		
		$userids = $this->get_userids();
		$backend->open_section("User accounts created $range_desc");
		$backend->open_field('&nbsp;');
		$this->caller->display_users_table($userids,array(),array(),'page_counter',false,array('created_date' => 'Creation Date'),'window.opener');

	}

}
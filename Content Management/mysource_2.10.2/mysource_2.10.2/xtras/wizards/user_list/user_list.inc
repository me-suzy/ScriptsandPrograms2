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
## $Source: /home/cvsroot/xtras/wizards/user_list/user_list.inc,v $
## $Revision: 1.8 $
## $Author: bvial $
## $Date: 2004/02/25 23:02:13 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/wizard.inc");
#---------------------------------------------------------------------#

/**
* User List Wizard
* A wizard to produce list of all expired, active, locked, 
* pending or all user accounts. The results can be
* for any or all organisations or access groups
*
* @access public
* @package Wizards
*/
class User_List extends Wizard {

	var $compatible_with = array('users');
	var $parameters = array('account_status' => array('all'),
							'export_type' => 'csv',
							'export_show_headers' => 0,
							'export_show_fields' => array('userid','login','firstname',
														  'surname','email','mobile_no',
														  'expiry_date','created_date','comments',
														  'title','name')
							  );


	 ##############################
	# Constructor
	function User_List (&$asset) {
		Wizard::Wizard($asset);
		$this->add_button('Export','Export',100,4);
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

	/**
	* Returns an array of global access groups for use in check boxes
	*
	* @access public
	* @returns array
	*/
	function get_global_access_groups_options() {
		$web_system = &$this->get_web_system();
		$options = $web_system->get_general_access_group_list();
		$options[0] = 'Users Not In An Access Group';
		return $options;
	}

	/**
	* Returns an array of site access groups for use in check boxes
	*
	* @access public
	* @returns array
	*/
	function get_site_access_groups_options() {
		$web_system = &$this->get_web_system();
		$sites = $web_system->get_editable_sites();
		$options = array();
		if (! empty($sites)) {
			foreach($sites as $id=>$name) {
				$site = $web_system->get_site($id);
				if (! empty($site->access_groups)) {
					foreach($site->access_groups as $groupid=>$gname) {
						$options+= array("$groupid"=>"[$name] $gname");
					}
				}
			}
		}
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

	function print_checkall_box() {
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
		&nbsp;<input type="checkbox" name="master" value="on" onClick="javascript: markAll(this.form, this.checked);">
		<?
	}
	function get_userids() {
		$users = &get_users_system();
		$db = &$users->get_db();

		$account_status = $this->parameters['account_status'];

		$range_cond = array();

		foreach ($account_status as $status) {
			switch($status) {
				case 'active':
					$range_cond['A'] = true;
					break;
				case 'locked':
					$range_cond['L'] = true;
					break;
				case 'expired':
					$range_cond['E'] = true;
					break;
				case 'pending':
					$range_cond['P'] = true;
					break;
				default:
					$range_cond['A'] = true;
					$range_cond['L'] = true;
					$range_cond['E'] = true;
					$range_cond['P'] = true;
			}
		}

		$web_status = '';
		foreach ($range_cond as $status => $true) if ($true) $web_status .= "'$status', ";
		$web_status = substr($web_status,0,-2);
		if ($web_status == '') {
			$userids = array();
		} else {
			$userids = array();
			$sql = "SELECT user.userid FROM user WHERE user.web_status IN ($web_status) ORDER BY user.userid";
			$userids = $db->single_column($sql);

			# make a list of organisationids
			$organisationids = array();
			foreach ($this->parameters['organisationids'] as $id) {
				if (!$id) continue;
				$organisation = new Organisation($id);
				$organisationids = array_merge($organisationids, array_keys($organisation->affiliations));
			}

			# make a list of access groupids
			$groupids = array();
			$accessgroupids = array_merge($this->parameters['global_access_groupids'], $this->parameters['site_access_groupids']);
			foreach ($accessgroupids as $id) {
				if (!$id) continue;
				$access_group = new Access_Group($id);
				$groupids = array_merge($groupids, array_keys($access_group->get_all_users()));
			}

			# if the user selected Unaffiliated Users as well, ensure that these users are also displayed
			if (in_array(0,$this->parameters['organisationids'])) {
				$sql = 'SELECT user.userid FROM user LEFT JOIN affiliation ON affiliation.userid=user.userid WHERE affiliation.organisationid IS NULL';
				$organisationids = array_merge($organisationids, $db->single_column($sql));
			}

			# if the user selected Users not in access group as well, ensure that these users are also displayed
			if (in_array(0,$this->parameters['global_access_groupids'])) {
				# users not in an access group individually
				$sql = 'SELECT user.userid FROM user LEFT JOIN access_group_user_membership ON access_group_user_membership.userid=user.userid WHERE access_group_user_membership.groupid IS NULL';
				$no_groups = $db->single_column($sql);

				# users not in an access group within an organisation
				$sql = 'SELECT user.userid FROM user LEFT JOIN affiliation ON affiliation.userid=user.userid LEFT JOIN access_group_organisation_membership ON access_group_organisation_membership.organisationid=affiliation.organisationid WHERE access_group_organisation_membership.organisationid IS NOT NULL';
				$groupids = array_merge($groupids, array_diff($no_groups,$db->single_column($sql)));
			}

			$userids = array_unique(array_intersect($userids, $organisationids, $groupids));
			sort($userids);
		}

		return $userids;
	}


	 ########################################################################################
	# Prints the interface - assumes a backend has already been setup and the header printed
	function process_wizard_web(&$backend) {
		
		if (!method_exists($backend,'open_field')) $backend = &$this->get_backend();

		$userids = $this->get_userids();
		$backend->open_section('Results');
		$backend->open_field('&nbsp;');
		$this->caller->display_users_table($userids,array(),array(),'page_counter',false,array('created_date' => 'Creation Date','expiry_date' => 'Expiry Date'),'window.opener');

	}

}
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
## File: include/organisation.inc
## Desc: Functions for dealing with organisations in the user database
## $Source: /home/cvsroot/mysource/include/organisation.inc,v $
## $Revision: 2.12 $
## $Author: dofford $
## $Date: 2004/03/04 05:36:10 $
#######################################################################
#---------------------------------------------------------------------#



 #######################################################
# An organisation is something you can attach users too
class Organisation extends UsersObject {

	var $id=0;
	var $parentid  = 0;
	var $managerids = array(); # Users who manage this org directly, just store the id as these people are affiliated to the org
	var $name='';
	var $description='';

	var $p_address='';
	var $p_suburb='';
	var $p_state='';
	var $p_postcode='';
	var $p_countryid=0;
	var $p_country='';

	var $childids=array();
	var $affiliations=array(); # An array of links to users
	var $premises=array();     # An array of links to locations

	var $form='';    # A form of additional user data fields

	 ##############################
	# Constructor
	function Organisation($organisationid=0) {
		UsersObject::UsersObject();

		 #################################################
		# Load the user information if an id is specified
		if ($organisationid) {
			return $this->load($organisationid);
		}
	}


	 ############################
	# Creates a new organisation
	function create($name='',$parentid=0) {
		if(!$name) return false;
		$db   = &$this->get_db();
		$tree = &$this->users_system->get_organisation_tree();
		$this->parentid = abs((int) $parentid);
		if($parentid > 0 && !isset($tree[$this->parentid])) return;
		# Make sure this is a unique name in this context
		while($db->single_element("SELECT organisationid FROM organisation WHERE name='".addslashes($name)."' AND parentid='$this->parentid'")) {
			$name = increment_name($name," ");
		}

		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/form/form.inc");

		$form = new Form("form");
		$form->title($name);
		$form->offer_standard_var("file","type","user");
		# Save to the database
		$organisationid = $db->insert("INSERT INTO organisation (parentid,name,form) VALUES('$this->parentid','".addslashes($name)."','".$form->pack()."')");
		# Make sure the system knows
		$tree   = &$this->users_system->load_organisation_tree();
		if($parentid > 0) {
			$parent = &$this->get_organisation($this->parentid);
			$parent->clear_cache();
		}
		if($organisationid) {
			return $this->load($organisationid);
		} else {
			return false;
		}
	}


	 ################################################
	# Loads the user details, from cache or database
	function load($organisationid=0) {
		if ($organisationid <= 0 && !($organisationid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load organisation information without a valid organisationid.",__FILE__,__LINE__);
			$this = new Organisation();
			return false;
		}

		if ($this->load_from_cache($organisationid)) return $organisationid;

		if ($loaded) return $organisationid;

		 ########################
		# Load from the database
		$query = "SELECT organisationid,parentid,name,description,p_address,p_suburb,p_state,p_postcode,p_countryid,form FROM organisation WHERE organisationid='$organisationid'";
		list(
			$this->id,
			$this->parentid,
			$this->name,
			$this->description,
			$this->p_address,
			$this->p_suburb,
			$this->p_state,
			$this->p_postcode,
			$this->p_countryid,
			$this->form
			) = $this->users_system->db->single_row($query);

		if (!$this->id) { # EEk! Clear.
			$this = new Organisation();
			return false;
		}

		$countries_config = &get_config("Countries");
		$this->p_country = $countries_config->countries[$this->p_countryid];

		$query = "SELECT organisationid, name FROM organisation WHERE parentid='$organisationid' ORDER BY name";

		$this->childids = $this->users_system->db->associative_array($query);

		$query = "SELECT userid FROM affiliation WHERE organisationid='$organisationid' AND manager='Y'";

		$this->managerids = $this->users_system->db->single_column($query);

		$query = "SELECT a.userid, a.title, u.firstname, u.surname, concat(u.firstname,' ',u.surname) AS name, u.email FROM affiliation a, user u WHERE u.userid=a.userid AND a.organisationid='$organisationid' ORDER BY u.surname, u.firstname";

		$this->affiliations = $this->users_system->db->associative_array($query);


		$query = "SELECT p.locationid, concat(l.s_address,'\n',l.s_suburb,' ',l.s_state,' ',l.s_postcode) AS address, l.s_countryid as countryid, p.phone, p.fax FROM premises p, location l WHERE l.locationid=p.locationid AND p.organisationid='$organisationid' ORDER BY l.s_countryid,l.s_state";

		$this->premises = $this->users_system->db->associative_array($query);

		 ############################
		# Save to the cache
		$this->save_to_cache($organisationid);

		return $organisationid;
	}

	 ######################################################
	# Does the currently logged in user have write access?
	function write_access() {
		$managed_organisationids = &$this->users_system->get_managed_organisationids();
		return (superuser("user") || in_array($this->id,$managed_organisationids));
	}

	 #########################################################################
	# Does the user have super write access? Do they manage an even higher org
	function super_write_access() {
		$session = &get_mysource_session();
		return (superuser('user') || $this->users_system->higher_manager($session->user->id,$this->id));
	}

	 ###########################################################
	# May the currently logged in user delete this organisation?
	function delete_access() {
		$managed_organisationids = &$this->users_system->get_managed_organisationids();
		if (!in_array($this->id,$managed_organisationids)) return false;
		# Can't delete an organisation that has unmanaged children.
		$descendants = $this->users_system->organisation_descendantids($this->id);
		$managed_descendants = array_intersect($descendants, $managed_organisationids);
		if(count($managed_descendants) != count($descendants))	return false;
		return true;
	}

	 ########################################
	# Change the name of the organisation
	function set_name($name='') {
		if($name == $this->name) return "";
		if($clash = $this->users_system->db->single_element("SELECT organisationid FROM organisation WHERE parentid='$this->parentid' AND name='".addslashes($name)."'")) {
			return "Unable to change organisation name. An organisation with that name already exists here.";
		}
		$this->name = $name;
		$this->users_system->db->update("UPDATE organisation SET name='".addslashes($this->name)."' WHERE organisationid='$this->id'");
		global $CACHE;
		if($this->parentid)	$CACHE->clear($this->parentid,"organisation");
		$this->clear_cache();
		return "Name changed to $this->name";
	}

	 ########################################
	# Change the name of the organisation
	function set_description($description='') {
		if($description == $this->description) return "";
		$this->description = $description;
		$this->users_system->db->update("UPDATE organisation SET description='".addslashes($this->description)."' WHERE organisationid='$this->id'");
		$this->clear_cache($this->id);
		return array(MYSOURCE_ERROR_CODE_NONE,"Description changed to \"$this->description\"");
	}


	 ###################################################
	# Add a  manager to the organisation
	function add_managerid($userid=0) {
		# Already a manager
		if(in_array($userid,$this->managerids)) return "";
		# Make them a manager
		if($this->super_write_access()) {
			$this->users_system->db->update("UPDATE affiliation SET manager='Y' WHERE organisationid='$this->id' AND userid='$userid'");
			return array(MYSOURCE_ERROR_CODE_NONE,"New manager appointed:" . $this->users_system->db->single_element("SELECT concat(firstname,' ',surname) FROM user WHERE userid='$userid'"));
		} else {
			return array(MYSOURCE_ERROR_CODE_ERROR,"Permission denied.");
		}
	}

	 ##############################################
	# Remove a manager from the managers list
	function delete_managerid($userid=0) {
		# Not a manager
		if(!in_array($userid,$this->managerids)) return "";
		if($this->super_write_access()) {
			$this->users_system->db->update("UPDATE affiliation SET manager='N' WHERE organisationid='$this->id' AND userid='$userid'");
			return array(MYSOURCE_ERROR_CODE_NONE,"Manager removed: " . $this->affiliations[$userid]['name']);
		} else {
			return array(MYSOURCE_ERROR_CODE_ERROR,"Permission denied.");
		}
	}

	 ########################################
	# Change the postal address of the organisation
	function set_postal_address($p_address='',$p_suburb='',$p_state='',$p_postcode='',$p_countryid=0) {
		if (	$p_address   == $this->p_address
			 && $p_suburb    == $this->p_suburb
			 && $p_state     == $this->p_state
			 && $p_postcode  == $this->p_postcode
			 && $p_countryid == $this->p_countryid
			) return "";

		$this->p_address   = $p_address;
		$this->p_suburb    = $p_suburb;
		$this->p_state     = $p_state;
		$this->p_postcode  = $p_postcode;
		$this->p_countryid = $p_countryid;

		$countries_config = &get_config("Countries");
		$this->p_country = $countries_config->countries[$this->p_countryid];
		$this->users_system->db->update("UPDATE organisation SET p_address='".addslashes($this->p_address)."', p_suburb='".addslashes($this->p_suburb)."', p_state='".addslashes($this->p_state)."', p_postcode='".addslashes($this->p_postcode)."', p_countryid='".addslashes($this->p_countryid)."' WHERE organisationid='$this->id'");
		$this->clear_cache($this->id);
		return array(MYSOURCE_ERROR_CODE_NONE,"New postal address: $this->p_address, $this->p_suburb $this->p_state $this->p_postcode, $this->p_country");
	}


	 ##############################
	# Delete this bastard
	function delete() {
		if ($this->users_system->db->single_element("SELECT count(*) FROM organisation WHERE parentid='$this->id'")) {
			return ("To delete an organisation, you must first delete all its child organisations.");
		}
		$del_orgs = $this->users_system->db->delete("DELETE FROM organisation WHERE organisationid='$this->id'");
		if ($del_orgs) {
			$this->clear_cache($this->id);
			global $CACHE;
			$del_affiliations = $this->users_system->db->delete("DELETE FROM affiliation WHERE organisationid='$this->id'");

			if (!empty($this->affiliations)) {
				foreach($this->affiliations as $id => $user) $CACHE->clear($id,"user");
			}

			$del_premises = $this->users_system->db->delete("DELETE FROM premises WHERE organisationid='$this->id'");
			if (!empty($this->premises)) {
				foreach($this->premises as $id => $location) {
					$CACHE->clear($id,"location");
					$location = &$this->users_system->get_location($id);
					$message .= $location->check_tenency()."\n";
				}
			}
			if($this->parentid) {
				$CACHE->clear($this->parentid,"organisation");
			}

			$this = new Organisation(0);

			return array(MYSOURCE_ERROR_CODE_NONE,"$message\nDeleted $del_orgs organisations. ($del_affiliations affiliations / $del_premises premises).");
		}
		return array(MYSOURCE_ERROR_CODE_ERROR,"Unable to delete organisation.");
	}


	 #################################
	# Adds an premises to the user
	function add_premises($new_locationid=0,$new_phone='',$new_fax='') {
		if(!$new_locationid) return "";
		$location = &$this->get_location($new_locationid);
		$address = $location->short_line();
		$query = "INSERT INTO premises (organisationid,locationid,phone,fax) VALUES ('$this->id','$new_locationid','".addslashes($new_phone)."','".addslashes($new_fax)."')";
		if($this->users_system->db->insert($query)) {
			$this->premises[$new_locationid]['phone'] = $new_phone;
			$this->premises[$new_locationid]['fax']   = $new_fax;
			$this->clear_cache($this->id);
			global $CACHE;
			$CACHE->clear($new_locationid,"location");
			return array(MYSOURCE_ERROR_CODE_NONE,"Premises created at $address (Ph: $new_phone | Fx: $new_fax)");
		}
		return array(MYSOURCE_ERROR_CODE_ERROR,"Unable to create premises at location: $address");
	}

	 #################################
	# Adds an premises to the user
	function update_premises($locationid=0,$new_phone='',$new_fax='') {
		if(!isset($this->premises[$locationid]) && $new_phone) return $this->add_premises($locationid,$new_phone,$new_fax);
		if(!$locationid || ($this->premises[$locationid]['phone'] == $new_phone && $this->premises[$locationid]['fax'] == $new_fax)) return "";
		$location = &$this->get_location($locationid);
		$address = $location->short_line();
		$query = "UPDATE premises SET phone='".addslashes($new_phone)."', fax='".addslashes($new_fax)."' WHERE locationid='$locationid' AND organisationid='$this->id'";
		if($this->users_system->db->update($query)) {
			$this->premises[$locationid]['phone'] = $new_phone;
			$this->premises[$locationid]['fax']   = $new_fax;
			$this->clear_cache($this->id);
			global $CACHE;
			$CACHE->clear($locationid,"location");
			return array(MYSOURCE_ERROR_CODE_NONE,"Premises updated at $address (Ph: $new_phone | Fx: $new_fax)");
		}
		return "";
	}

	 #################################
	# Adds an placement to the user
	function delete_premises($locationid=0) {
		if(!$locationid) return "";
		$location = &$this->get_location($locationid);
		$address = $location->short_line();
		$query = "DELETE FROM premises  WHERE locationid='$locationid' AND organisationid='$this->id'";
		if($this->users_system->db->delete($query)) {
			unset($this->premises[$locationid]);
			$this->clear_cache($this->id);
			global $CACHE;
			$CACHE->clear($locationid,"location");
			$location = &$this->users_system->get_location($locationid);
			$message .= $location->check_tenency()."\n";
			return array(MYSOURCE_ERROR_CODE_NONE,"$message\nPremises at $address deleted.");
		}
		return array(MYSOURCE_ERROR_CODE_ERROR,"Unable to delete premises at location: $address");
	}

	 ###############################################################
	# Set the form - assumes its all ready to shove in the db
	function set_form($form='') {
        if ($form == $this->form) return "";
		$db = &$this->get_db();
        $this->form = $form;
        if ($db->update("UPDATE organisation SET form='".addslashes($this->form)."' WHERE organisationid='$this->id'"))  {
			$this->clear_cache();
            return array(MYSOURCE_ERROR_CODE_NONE,"Form updated.");
        } else {
			return "";
		}
	}

	 ##########################################################
	# Prints the backend for the user
	function print_backend() {
		global $XTRAS;

		$system_config = &get_system_config();
		$session = &get_mysource_session();
		 ###################################
		# Setup the backend a little
		$backend = &$this->users_system->setup_backend(); # Backend reference
		$backend->set_title($this->name." - Edit Organisation");

		 ###############################
		# More aliases
		$org_tree = &$this->users_system->get_organisation_tree();
		$managed_organisationids = &$this->users_system->get_managed_organisationids();
		$managed_userids = &$this->users_system->managed_userids();

		 ####################################################################
		# SECUIRTY - Only let in those who have permission to work on a site
		if (!$session->logged_in()) {
			$session->login_screen($backend->title,"You must be logged in and have permission to edit <i>$system_config->system_name</i> organisation records in order to proceed.");
		}

		 ######################################################################
		# This particular user must be affiliated with an managed organisation
		if (!$this->write_access()) {
			$session->login_screen($backend->title,"You are not a designated manager of this organisation.");
		}

		$backend->set_tab("organisation","organisation.php?organisationid=$this->id","General","Edit this organisation","organisation");
		$backend->set_tab("form","organisation.php?organisationid=$this->id&edit_am_section=Form","Special User Data","Use this form to create special user data fields for members of this organisation","page");
		$backend->set_tab("export","organisation.php?organisationid=$this->id&edit_am_section=Export","User Export","","page");
		$backend->set_heading("Edit Organisation","organisation");
		$backend->set_name($this->name);
		$backend->set_id_string($this->id);
		$backend->set_hidden_field("organisationid",$this->id);
		$backend->set_hidden_field("action");
		$backend->set_hidden_field("userid");
		$backend->set_hidden_field("locationid");
		$backend->set_hidden_field("new_locationid",$session->get_var('active_locationid'));

		$edit_am_section = $_REQUEST['edit_am_section'];
		$backend->set_hidden_field("edit_am_section",$edit_am_section);
		switch($edit_am_section) {
			case "Form":
				$this->print_form_backend();
				return;
			case "Export":
				$this->print_export_backend();
				return;
		}

		$backend->set_active_tab("organisation");

		 #########################################
		# Process a submitted form
		$action = $_POST['action'];
		switch($action) {

		 ###################################
		# Commiting changes to the database
		case "Commit":
			$name = $_POST['name'];
			$description = $_POST['description'];
			$p_address = $_POST['p_address'];
			$p_suburb = $_POST['p_suburb'];
			$p_state = $_POST['p_state'];
			$p_postcode = $_POST['p_postcode'];
			$p_countryid = $_POST['p_countryid'];
			if($this->write_access()) {
				$backend->add_message($this->set_name(gpc_stripslashes($name)));
				$backend->add_message($this->set_description(gpc_stripslashes($description)));
				$backend->add_message($this->set_postal_address(gpc_stripslashes($p_address), gpc_stripslashes($p_suburb), gpc_stripslashes($p_state), gpc_stripslashes($p_postcode), gpc_stripslashes($p_countryid)));

				 #########################
				# Update placements
				$phone = $_POST['phone'];
				if (!is_array($phone)) $phone = array();
				$fax = $_POST['fax'];
				if (!is_array($fax)) $fax = array();

				if (!empty($phone)) {
					foreach($phone as $locid => $new_phone) {
						$new_fax = $fax[$locid];
						$backend->add_message($this->update_premises($locid,gpc_stripslashes($new_phone),gpc_stripslashes($new_fax)));
					}
				}

				 ###########################
				# Delete placesments
				$delete_premises = $_POST['delete_premises'];
				if (!is_array($delete_premises)) $delete_premises = array();
				$deletables = array_keys($delete_premises);
				if (!empty($deletables)) {
					foreach($deletables as $locid) {
						$backend->add_message($this->delete_premises($locid));
					}
				}

				 #############################
				# Revoke management
				$delete_managers = $_POST['delete_managers'];
				if (!is_array($delete_managers)) $delete_managers = array();
				if (!empty($delete_managers)) {
					foreach($delete_managers as $userid) {
						$backend->add_message($this->delete_managerid($userid));
					}
				}

				 #############################
				# Add management
				$add_managers = $_POST['add_managers'];
				if (!is_array($add_managers)) $add_managers = array();
				if (!empty($add_managers)) {
					foreach($add_managers as $userid) {
						$backend->add_message($this->add_managerid($userid));
					}
				}

			}
			break;


		 ###########################################
		# Delete an organisation from the database
		case "Delete":
			if($this->delete_access()) {
				# Check that none of the affiliated users would be orhphaned by this process.
				# That is, on deletion, every affected user must still be affiliated to at least one
				# organisation managed by the current user.
				$userids = array_keys($this->affiliations);
				$user_list = "('".implode("','",$userids)."')";
				$org_list = "('".implode("','",$managed_organisationids)."')";
				$query = "SELECT u.userid, a.organisationid FROM user u LEFT JOIN affiliation a ON u.userid=a.userid WHERE a.organisationid != '$this->id'";
				if ($user_list != "()") $query .= " AND u.userid IN $user_list";
				if ($org_list != "()")  $query .= " AND a.organisationid IN $org_list";
				$okay_users = $this->users_system->db->associative_array($query);
				if (!empty($userids)) {
					foreach($userids as $userid) {
						if(!$okay_users[$userid]) {
							$failed = true;
							break;
						}
					}
				}
				if($failed) {
					$backend->add_message(array(MYSOURCE_ERROR_CODE_ERROR,"Permission Denied: Deleting this organisation would leave some of its users orphaned."));
				} else {
					$session->unset_var('active_organisationid');
					$parentid = $this->parentid;
					$backend->add_message($this->delete());
					if($parentid > 0) {
						$backend->set_relocation("organisation.php?organisationid=$parentid");
					} else {
						$backend->set_relocation("users.php");
					}
					$backend->print_header();
					unset($this);
					exit();
				}
			} else {
				$backend->add_message(array(MYSOURCE_ERROR_CODE_ERROR,"Permission Denied."));
			}
			break;

		 ###################################
		# Create a new organisation record ...
		case "New Organisation":
			$new_org_name = gpc_stripslashes($_POST['new_org_name']);
				$new_org = new Organisation();
				$new_organisationid = $new_org->create($new_org_name,$this->id);
			if ($new_organisationid) {
				$backend->add_message(array(MYSOURCE_ERROR_CODE_NONE,"New child organisation created."));
				$backend->set_relocation("organisation.php?organisationid=$new_organisationid");
			} else {
				$backend->add_message(array(MYSOURCE_ERROR_CODE_ERROR,"Unable to create new organisation."));
			}
			break;

		 ###################################
		# Create a new user record ...
		case "New User":
			$new_login = $_POST['new_login'];
			$new_title = $_POST['new_title'];
			$manager = $_POST['manager'];
			$user = new User(0,$this->users_system);
			$backend->add_message($user->create(gpc_stripslashes($new_login), $this->id, gpc_stripslashes($new_title)));
			if($user->id > 0) {
				if($manager) {
					$backend->add_message($this->add_managerid($user->id));
				}
				$backend->set_relocation("user.php?userid=$user->id");
			}
			break;

		}#end switch


		 #######################
		# Die on no ID
		if(!$this->id) {
			return "";
		}

		 ########################################
		# Refresh the information - just in case
		if ($action) {
			$this->clear_cache($this->id);
			$this->load();
		}

		if($this->delete_access()) {
			$backend->set_toolbar_button("delete","javascript: if (confirm('Are you sure you want to delete this organisation?".(($c=count($this->affiliations))?"\\n$c users are affiliated to it.":"")."') && confirm('Really sure? This is irreversible.')) {document.edit.action.value='Delete';document.edit.submit()}","Delete this organisation","delete");
		}

		$backend->print_header();

		$backend->open_section('Attributes');

		$backend->open_field('Name');
		echo text_box(name,$this->name,20,128,'class=data');

		$backend->open_field('Description','top');
		echo text_area(description,$this->description,30,5,3000,'class=data');

		$backend->open_field('Postal Address','top');
		echo text_area(p_address,$this->p_address,35,2,255,'class=data').'<br>';
		echo 'Suburb/Town:'.text_box(p_suburb,$this->p_suburb,25,128,'class=data').'<br>';
		echo 'State/Province:'.text_box(p_state,$this->p_state,5,128,'class=data');
		echo ' Post/Zip Code:'.text_box(p_postcode,$this->p_postcode,5,10,'class=data').'<br>';
		$countries_config = &get_config('Countries');
		echo combo_box(p_countryid,array_merge(array(''=>'-'),$countries_config->countries),$this->p_countryid,'class=data',35);

		$backend->open_section('Organisational Context','organisation');

		$backend->open_field('Parents');
		echo $this->users_system->print_organisation_lineage($this->id);

		$backend->open_field('Children','top');
		if (!empty($this->childids)) {
			echo('<table cellpadding=0 cellspacing=0 border=0>');
			$this->users_system->print_organisation_hierarchy(array_keys($this->childids),array(),1);
			echo('</table>');
		} else {
			echo ('None.');
		}

		$backend->open_field('New Child<span class=fineprint><br>(organisation name)');
		echo text_box('new_org_name','',25,128, 'class=data');
		$backend->print_icon_button("javascript: if (document.edit.new_org_name.value.length > 1) {document.edit.action.value='New Organisation';document.edit.submit();}","Create New Child Organisation","create");

		$backend->open_section('Affiliated Users','user');

		$backend->open_field('Managers ('.count($this->managerids).')','top');
		$this->users_system->display_users_table($this->managerids,(($this->super_write_access())?'delete_managers':''),'Revoke Management?','manager_page_counter',$this->id);

		$backend->open_field('<hr noshade size=0>');

		$non_manager_affiliations = array_diff(array_keys($this->affiliations),$this->managerids);
		$backend->open_field("Users (".count($non_manager_affiliations).")","top");

		$this->users_system->display_users_table($non_manager_affiliations,(($this->super_write_access())?'add_managers':''),'Grant Management?','user_page_counter',$this->id);


		$backend->open_field('New User:<span class=fineprint><br><b>(login & title)</b></span>','top');
		echo text_box('new_login','',15,64, 'class=data');
		# Is this user a manager of a higher organisation?
		if($this->super_write_access()) {
			echo "<b>[<input type=checkbox name=manager value=1><span style=\"color: #aa0000\">Make Manager?</span>]</b>";
		}
		echo '<br>';
		echo text_box('new_title','',20,128, 'class=data');
		$backend->print_icon_button("javascript: if (document.edit.new_title.value.length > 1) {document.edit.action.value='New User';document.edit.submit();}","Create New User","create");

		$backend->open_section('Premises','location');

		if(empty($this->premises)) {
			$backend->open_field();
			echo 'None.';
		} else {
			foreach($this->premises as $locationid=>$data) {
				$phone = $data['phone'];
				$fax   = $data['fax'];
				$location= $this->users_system->location_address($locationid);
				$backend->open_field($i++,"top");
				echo("Ph:".text_box("phone[$locationid]",$phone,15,32,"class=data"));
				echo(" at <a href=\"location.php?locationid=$locationid\"><b>$location</b></a><br>");
				echo(" Fx:".text_box("fax[$locationid]",$fax,15,32,"class=data"));
				echo(" <b>[<input type=checkbox name=delete_premises[$locationid] value=1><span style=\"color: #aa0000\">Delete?</span>]</b>");
			}
		}

		$active_locationid = $session->get_var('active_locationid');
		if ($active_locationid && !$this->premises[$active_locationid]) {
			$location     = $this->users_system->location_address($active_locationid);
			$backend->open_field("<hr noshade size=0>");
			$backend->open_field("New?<span style=\"font-size:8pt\"><br>(or leave blank)</span>","top");
			echo ("Ph:".text_box("phone[$active_locationid]","",15,128,"class=data"));
			echo (" at <a href=\"location.php?locationid=$active_locationid\"><b>$location</b></a><br>");
			echo (" Fx:".text_box("fax[$active_locationid]","",15,128,"class=data"));
		}

		$backend->open_field("<hr noshade size=0>");
		$backend->open_field("New Location?");
		echo "Ph:".text_box("new_location_direct_phone","",15,128,"class=data")."&nbsp;";
		echo "Fx:".text_box("new_location_direct_fax","",15,128,"class=data")."&nbsp;";
		$backend->print_icon_button("javascript: if (document.edit.new_location_direct_phone.value != '' || document.edit.new_location_direct_fax.value != '') window.location='location.php?premisesid=$this->id&premises_phone=' + escape(document.edit.new_location_direct_phone.value) + '&premises_fax=' + escape(document.edit.new_location_direct_fax.value);","Create New Location","create");

		$backend->print_commit_button("Commit","if(confirm('Commit this information to the system?')){document.edit.action.value='Commit';document.edit.submit();}");

		$backend->print_footer();
	}

	 ##################################################
	# Print the backend screen for extra user data
	function print_form_backend() {
		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/form/form.inc");

		$backend = &$this->get_backend();
		$backend->set_active_tab("form");
		$backend->set_subheading("&nbsp;");

		$form     = new Form($this->form);
		$form->title($this->name." User Data");
		$form->set_session_auto_answer($true = true);

		$action = $_POST['action'];
		switch($action) {
			case 'Commit':
				$backend->add_message($form->update());
				$backend->add_message($this->set_form($form->pack()));
				$this->clear_cache();
				global $CACHE;
				foreach($this->affiliations as $id => $user) $CACHE->clear($id,"user");
				break;
		}

		$backend->print_header();

		if ($request_information = $this->get_special_user_data_form_request_information()) {
			foreach($request_information as $code => $desc) {
				$form->request_information($code, $desc);
			}
		}

		$form->meta_form($backend,'Special User Data');
		$backend->print_commit_button("Commit","document.edit.action.value='Commit';document.edit.submit();");

		$backend->print_footer();

	}

	 ##################################################
	# Print the backend screen for user export
	function print_export_backend() {
		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/form/form.inc");

		$backend = &$this->get_backend();
		$backend->set_active_tab('export');
		$backend->set_subheading('&nbsp;');

		$form = new Form($this->form);

		$action = $_POST['action'];
		switch($action) {
			case 'Commit':
				$export_type = $_POST['export_type'];
				$other_fields = $_POST['other_fields'];
				$form_fields = $_POST['form_fields'];
				switch($export_type) {
					case 'csv':
						$show_field_headers = $_POST['show_field_headers'];
						$this->csv_export($show_field_headers,&$other_fields,&$form_fields);
						return;
					case 'xml':
						$this->xml_export(&$other_fields,&$form_fields);
						return;
				}
				return;
		}

		$backend->print_header();

		$backend->open_section('Export');
		$backend->open_field('Export Type');
		echo combo_box('export_type',array('csv'=>'CSV','xml'=>'XML'));
		$backend->open_field('Show Headers');
		echo combo_box('show_field_headers',array('0'=>'No','1'=>'Yes'),'','class=data').'<span class=fineprint>Only relevant for CSV export</span>';
		$backend->open_field('Show Fields','top');
		echo "<table>";
		echo "<tr><td><input type=checkbox name=other_fields[userid] value=1 checked></td><td>User ID</td></tr>";
		echo "<tr><td><input type=checkbox name=other_fields[login] value=1 checked></td><td>Login</td></tr>";
		echo "<tr><td><input type=checkbox name=other_fields[firstname] value=1 checked></td><td>Firstname</td></tr>";
		echo "<tr><td><input type=checkbox name=other_fields[surname] value=1 checked></td><td>Surname</td></tr>";
		echo "<tr><td><input type=checkbox name=other_fields[email] value=1 checked></td><td>Email</td></tr>";
		echo "<tr><td><input type=checkbox name=other_fields[mobile_no] value=1 checked></td><td>Mobile</td></tr>";
		echo "<tr><td><input type=checkbox name=other_fields[expiry_date] value=1 checked></td><td>Expiry</td></tr>";
		echo "<tr><td><input type=checkbox name=other_fields[Comments] value=1 checked></td><td>Comments</td></tr>";
		echo "<tr><td><input type=checkbox name=other_fields[title] value=1 checked></td><td>Title</td></tr>";
		if (!empty($form->sections)) {
			reset($form->sections);
			$section_index = 0;
			while(list($sectionid,$section) = each($form->sections)) {
				$section_var_prefix = "section_$sectionid"."_";
				if (!empty($section['questions'])) {
					reset($section['questions']);
					while(list($questionid,$question) = each($section['questions'])) {
						$question_index++;
						$question_var_prefix = $section_var_prefix."question_$questionid";
						echo "<tr><td><input type=checkbox name=form_fields[$question_var_prefix] checked></td><td>";
						echo "$question->title</td></tr>";
					}
				}
			}
		}
		echo "</table>";

		$backend->print_commit_button("Commit","document.edit.action.value='Commit';document.edit.submit();");

		$backend->print_footer();

	}

	 #################################
	# Exports a file in csv format
	function csv_export($show_field_headers=1,&$other_fields,&$form_fields) {
		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/csv/csv.inc");

		$db = &$this->get_db();
		$form = new Form($this->form);

		$csv = new Csv();
		$sql = "SELECT u.*, a.organisationid, a.title, a.manager, a.answers FROM user AS u LEFT JOIN affiliation AS a ON u.userid=a.userid 
				WHERE organisationid = '".addslashes($this->id)."'";
		$export_data = $db->associative_array($sql);

		if($show_field_headers) {
			if (!empty($other_fields)) {
				foreach($other_fields as $key => $on) {
					if($on) $field_headers[] = $key;
				}
			}
			if (!empty($form_fields)) {
				reset($form_fields);
				while(list($question_var_prefix,$on) = each($form_fields)) {
					if($on) {
						$sectionid = substr($question_var_prefix,8,strpos($question_var_prefix,"question_")-9);
						$questionid = substr($question_var_prefix,strpos($question_var_prefix,"question_")+9);
						$field_headers[] = $form->sections[$sectionid]["questions"][$questionid]->title;
					}
				}
			}
			$csv->set_field_headers(&$field_headers);
		}
		if (!empty($export_data)) {
			while(list($userid,$data) = each($export_data)) {
				$csv_fields = '';
				$answers = unserialize($data['answers']);
				if (!empty($other_fields)) {
					foreach($other_fields as $key => $on) {
						if($on) $csv_fields[] = $data[$key];
					}
				}
				if (!empty($form_fields)) {
					reset($form_fields);
					while(list($question_var_prefix,$on) = each($form_fields)) {
						if($on) {
							$sectionid = substr($question_var_prefix,8,strpos($question_var_prefix,'question_')-9);
							$questionid = substr($question_var_prefix,strpos($question_var_prefix,'question_')+9);
							$csv_fields[] = $answers[$sectionid][$questionid]["value"];
						}
					}
				}
				$csv_data[] = $csv_fields;
			}
		}
		$csv->set_filename('user_export_'.date('Y-m-d').'.csv');
		$csv->set_values(&$csv_data);
		$csv->export($csv_data);
		return;
	}

	 ########################################
	# Export data in xml format
	function xml_export(&$other_fields,&$form_fields) {
		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/form/form.inc");

		$db   = &$this->get_db();
		$form = new Form($this->form);

		$userids = $db->single_column("SELECT u.userid FROM user AS u, affiliation AS a WHERE u.userid = a.userid AND a.organisationid='$this->id'");
		$this->users_system->xml_export($userids,$other_fields,$form_fields,$form);
		return;
	}

	 ########################################
	# Gets special user data request information
	function get_special_user_data_form_request_information() {
		return array (
			'login' =>'User\'s Login/Username',
			'password' => 'User\'s Password',
			'firstname' => 'User\'s First Name',
			'surname' => 'User\'s Surname',
			'name' => 'User\'s Full Name (optional)',
			'title' => 'User\'s Title',
			'user_image' => 'User\'s Image',
			'phone' => 'User\'s Phone Number',
			'fax' => 'User\'s Fax Number',
			'mobile' => 'User\'s Mobile Phone Number',
			'email' => 'User\'s Email Address',
			's_address' => 'User\'s Street Address',
			's_suburb' => 'User\'s Suburb/Town',
			's_state' => 'User\'s State/Province',
			's_postcode' => 'User\'s Post/Zip Code',
			's_countryid' => 'User\'s Country',
			'comments' => 'Place in User\'s \'Comments\' Field',
			'other' => 'Other'
		);
	}
}

?>

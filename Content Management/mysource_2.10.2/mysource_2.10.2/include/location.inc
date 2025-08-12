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
## File: include/location.inc
## Desc: Functions for dealing with locations in the user database
## $Source: /home/cvsroot/mysource/include/location.inc,v $
## $Revision: 2.9 $
## $Author: bvial $
## $Date: 2003/09/12 01:08:20 $
#######################################################################
#---------------------------------------------------------------------#



 ######################################################################
# An location is something you can attach users and organisations too
class Location extends UsersObject {
	
	var $id = 0;
	var $name = '';
	var $parentid  = 0;
	var $managerids = array(); # Userid
	var $description='';
	
	var $s_address='';
	var $s_suburb='';
	var $s_state='';
	var $s_postcode='';
	var $s_countryid='';
	var $s_country='';

	var $placements = array(); # An array of links to users
	var $premises = array();   # An array of links to organisations

	 ##############################
	# Constructor
	function Location($locationid=0) {
		
		UsersObject::UsersObject();

		 #################################################
		# Load the locations information if an id is specified
		if ($locationid) {
			return $this->load($locationid);
		}
	}

	 #########################
	# Creates a new location!
	function create($s_address='', $s_suburb='', $s_state='', $s_postcode='', $s_countryid=0, $name='') {
		$this->id = $this->users_system->db->insert("INSERT INTO location (s_address, name) VALUES ('', '')");
		$this->set_address($s_address, $s_suburb, $s_state, $s_postcode, $s_countryid);
		$this->set_name($name);
		return "New location created at '".$this->short_line()."'";
	}


	 ###################################################
	# Loads the location details, from cache or database
	function load($locationid=0) {
		if ($locationid <= 0 && !($locationid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load location information without a valid locationid.",__FILE__,__LINE__);
			$this = new Location();
			return false;
		}

		 ###############################################
		# Check the cache - but remember where mum is !
		$mum = &$this->users_system;
		$loaded = $this->load_from_cache($locationid);
		$this->users_system = &$mum;

		if ($loaded) return $locationid;

		 ########################
		# Load from the database
		$query = "SELECT locationid,name,s_address,s_suburb,s_state,s_postcode,s_countryid FROM location WHERE locationid='$locationid'";
		list(
			$this->id,
			$this->name,
			$this->s_address,
			$this->s_suburb,
			$this->s_state,
			$this->s_postcode,
			$this->s_countryid,
			) = $this->users_system->db->single_row($query);

		if (!$this->id) { # EEk! Clear.
			$this = new Location();
			return false;
		}

		$countries_config = &get_config("Countries");
		$this->s_country = $countries_config->countries[$this->s_countryid];

		$query = "SELECT p.userid, p.direct_phone, p.direct_fax, u.firstname, u.surname, concat(u.firstname,' ',u.surname) AS name, u.email FROM placement p, user u WHERE p.userid=u.userid AND p.locationid='$locationid' ORDER BY u.surname, u.firstname";
		$this->placements = $this->users_system->db->associative_array($query);

		$query = "SELECT p.organisationid, o.name, o2.name AS parent_name, o.parentid FROM premises p, organisation o LEFT JOIN organisation o2 ON o.parentid=o2.organisationid WHERE o.organisationid = p.organisationid AND p.locationid = '$locationid' ORDER BY o.name, parent_name";
		$this->premises = $this->users_system->db->associative_array($query);

		 ############################
		# Save to the cache
		$tmp = &$this->users_system;
		unset($this->users_system);
		$this->save_to_cache($locationid);
		$this->users_system = &$tmp;

		return $locationid;
	}


	 ######################################################
	# Returns a short one-line description of the location
	function short_line() {
		$r = ereg_replace("[\r\n]+",", ",$this->s_address);
		if($this->s_suburb)   $r .= ", $this->s_suburb";
		if($this->s_state)    $r .= ", $this->s_state";
		if($this->s_postcode) $r .= ", $this->s_postcode";
		if($this->s_countryid) {
			$countries_config = &get_config("Countries");
			$r .= ", {$countries_config->countries[$this->s_countryid]}";
		}
		return $r;
	}


	 ####################################################################
	# Now check if this location isnt being used.. if it isn't, sionara
	function check_tenency() {
		if(count($this->users_system->db->single_column("SELECT userid FROM placement WHERE locationid='$this->id'") > 0)
		 && count($this->users_system->db->single_column("SELECT organisationid FROM premises WHERE locationid='$this->id'") > 0)) {
			return $this->delete();
		}
	}

	 ######################################################
	# Does the currently logged in user have write access?
	function write_access() {
		if (superuser('user')) return true;

		# if we are a new location, let them create it
		if (!$this->id) return true;

		$managed_organisationids = &$this->users_system->get_managed_organisationids();
		if (!is_array($managed_organisationids)) $managed_organisationids = array();

		# if they can manage the organisation, and it's premises are located here
		# they can change this location
		$managed_orgs_premises = array_intersect($managed_organisationids, array_keys($this->premises));
		if (!empty($managed_orgs_premises)) return true;

		# if they are placed here they can change this location
		#$placed_here = in_array($_SESSION['SESSION']->user->id, array_keys($this->placements));
		#if ($placed_here) return true;
		# if they manage users who live here they can change it
		$managed_userids = &$this->users_system->managed_userids();
		$managed_users_placements = array_intersect($managed_userids, array_keys($this->placements));
		if (!empty($managed_users_placements)) return true;

		# bad luck, sonny
		return false;

	}#end write_access()

	 ###########################################################
	# May the currently logged in user delete this location?
	function delete_access() {
		if (superuser('user')) return true;

		# can't delete if there ain't any id
		if (!$this->id) return false;

		# can't delete if you can't change
		if (!$this->write_access()) return false;

		$session = &get_mysource_session();

		# they need to manage all organisations that have premises here
		if (!empty($this->premises)) {
			$managed_organisationids = &$this->users_system->get_managed_organisationids();
			$managed_orgs_premises   = array_intersect($managed_organisationids, array_keys($this->premises));
			$unmanaged_orgs_premises = array_diff($managed_orgs_premises, array_keys($this->premises));
			$delete_all_premises = (count($unmanaged_orgs_premises) == 0);

		# if there ain't any premises they can delete all premises ;)
		} else {
			$delete_all_premises = true;

		}# end if

		# and they need to be the only user placed here
		$placement_count = count($this->placements);
		if ($placement_count == 1) {
			list($userid) = array_keys($this->placements);
			$delete_all_placements = ($userid == $session->user->id);

		# else if there are more that one placement, you can't delete the placements
		} elseif ($placement_count > 1) {
			$delete_all_placements = false;

		# if there ain't any placements, they can delete all placements ;)
		} else {
			$delete_all_placements = true;

		}# end if

		# need to be able to delete all the premises and all the placements
		return ($delete_all_premises && $delete_all_placements);

	}#end delete_access()

	 ################################################
	# Change the postal address of this location
	function set_address($s_address='',$s_suburb='',$s_state='',$s_postcode='',$s_countryid='') {
		if ($s_address == $this->s_address
			 && $s_suburb == $this->s_suburb
			 && $s_state == $this->s_state
			 && $s_postcode == $this->s_postcode
			 && $s_countryid == $this->s_countryid
			) return "";
		$this->s_address = $s_address;
		$this->s_suburb = $s_suburb;
		$this->s_state = $s_state;
		$this->s_postcode = $s_postcode;
		$this->s_countryid = $s_countryid;
		$countries_config = &get_config("Countries");
		$this->s_country = $countries_config->countries[$this->s_countryid];
		$updated = $this->users_system->db->update("UPDATE location SET s_address='".addslashes($this->s_address)."', s_suburb='".addslashes($this->s_suburb)."', s_state='".addslashes($this->s_state)."', s_postcode='".addslashes($this->s_postcode)."', s_countryid='".addslashes($this->s_countryid)."' WHERE locationid='$this->id'");
		$this->clear_cache($this->id);
		if($updated) return "Location address updated: ".$this->short_line();
		return;
	}


	 ################################################
	# Change name of this location
	function set_name($name='') {
		if ($name == $this->name) return '';
		$this->name = $name;
		$updated = $this->users_system->db->update("UPDATE location SET name='".addslashes($this->name)."' WHERE locationid='$this->id'");
		$this->clear_cache($this->id);
		if($updated) return "Location name updated: ".$this->name;
		return;
	}


	 ##############################
	# Delete this bastard
	function delete() {
		$del_location = $this->users_system->db->delete("DELETE FROM location WHERE locationid='$this->id'");
		if ($del_location) { 
			$this->clear_cache($this->id);
			global $CACHE;
			$session = &get_mysource_session();

			$del_placements = $this->users_system->db->delete("DELETE FROM placement WHERE locationid='$this->id'");

			if (!empty($this->placements)) {
				foreach($this->placements as $id => $user) $CACHE->clear($id,"user");
			}

			$del_premises = $this->users_system->db->delete("DELETE FROM premises WHERE locationid='$this->id'");

			if (!empty($this->premises)) {
				foreach($this->premises as $id => $org) $CACHE->clear($id,"organisation");
			}

			$this = new Location(0);
			$session->unset_var('active_locationid');

			return "Deleted $del_location location. ($del_placements placements / $del_premises premises).";
		}
		return "Unable to delete location.";
	}

	 ##########################################################
	# Prints the backend for the location
	function print_backend() {
		global $XTRAS;

		 ###################################
		# Setup the backend a little
		$backend = &$this->users_system->setup_backend(); # Backend refrence

		$system_config = &get_system_config();
		$session = &get_mysource_session();

		 ####################################################################
		# SECUIRTY - Only let in those who have permission to work
		if (!$session->logged_in()) {
			$session->login_screen($backend->title,"You must be logged in and have permission to edit <i>$system_config->system_name</i> location records in order to proceed.");
		}

		 #######################################################################################
		# This particular user must be placed here or manage an organisation with premises here
		if (!$this->write_access()) {
			$session->login_screen($backend->title,"You need to have users with placements at this location or be a designated manager of an organisation with premises at this location.");
		}

		 #########################################
		# Process a submitted form
		$action = $_POST['action'];
		switch($action) {

			 ###################################
			# Commiting changes to the database
			case 'Commit':
				$s_address = $_POST['s_address'];
				$s_suburb = $_POST['s_suburb'];
				$s_state = $_POST['s_state'];
				$s_postcode = $_POST['s_postcode'];
				$s_countryid = $_POST['s_countryid'];
				$name = $_POST['name'];
				if($this->write_access()) {
					$backend->add_message($this->set_address(gpc_stripslashes($s_address), gpc_stripslashes($s_suburb), gpc_stripslashes($s_state), gpc_stripslashes($s_postcode), gpc_stripslashes($s_countryid)));
					$backend->add_message($this->set_name(gpc_stripslashes($name)));
				}
			break;

			 ###################################
			# Create a new location record ...
			case 'New Location':
				$s_address = $_POST['s_address'];
				$s_suburb = $_POST['s_suburb'];
				$s_state = $_POST['s_state'];
				$s_postcode = $_POST['s_postcode'];
				$s_countryid = $_POST['s_countryid'];
				$name = $_POST['name'];

				$backend->add_message($this->create(gpc_stripslashes($s_address),gpc_stripslashes($s_suburb),gpc_stripslashes($s_state),gpc_stripslashes($s_postcode),gpc_stripslashes($s_countryid), gpc_stripslashes($name)));
				if ($new_locationid = $this->id) {

					# if there is a placementid then create the user object
					$placementid = $_POST['placementid'];
					$placement_phone = $_REQUEST['placement_phone'];
					$placement_fax = $_REQUEST['placement_fax'];
					if ($placementid) {
						$user = &$this->users_system->get_user($placementid);
						$backend->add_message($user->add_placement($new_locationid, $placement_phone, $placement_fax));

					}#end if

					# if there is a premiseid then create the organisation object
					$premisesid = $_POST['premisesid'];
					$premises_phone = $_REQUEST['premises_phone'];
					$premises_fax = $_REQUEST['premises_fax'];
					if ($premisesid) {
						$organisation = &$this->users_system->get_organisation($premisesid);
						$backend->add_message($organisation->add_premises($new_locationid, $premises_phone, $premises_fax));

					}#end if

					$backend->set_relocation("location.php?locationid=$new_locationid");
					$backend->print_header();
					return;
				} else {
					$backend->add_message('Unable to create new location.');
				}
			break;
			 
			 ###################################
			# Delete a location from the database
			case 'Delete':
				if($this->delete_access()) {
					$session->unset_var('active_locationid');
					$backend->add_message($this->delete());
					$backend->set_relocation('users.php');
					$backend->print_header();
					unset($this);
					exit();
				} else {
					$backend->add_message('Permission Denied.');
				}
			break;
			
		}#end switch

		 
		 ########################################
		# Refresh the information - just in case
		if ($action && $this->id) {
			$this->clear_cache($this->id);
			$this->load();
		}

		if ($this->id) {

			$backend->set_title("$this->s_address, $this->s_suburb - Edit Location");

			$backend->set_heading("Edit Location:","location");
			$backend->set_name($this->short_line());
			$backend->set_id_string($this->id);

		# else we must be creating the location
		} else {

			$backend->set_title('New Location');
			$backend->remove_tab('location');
			$backend->set_tab('location','','New Location','Create a New Location','location');
			$backend->set_active_tab('location');
			
			$backend->set_heading('New Location:','location');
		}#endif

		
		$backend->set_hidden_field('locationid',$this->id);
		$backend->set_hidden_field('action');

		# want a new location from the user object
		$placementid = $_REQUEST['placementid'];
		$placement_phone = $_REQUEST['placement_phone'];
		$placement_fax = $_REQUEST['placement_fax'];
		if ($placementid) {
			$backend->set_hidden_field('placementid',     $placementid);
			$backend->set_hidden_field('placement_phone', $placement_phone);
			$backend->set_hidden_field('placement_fax',   $placement_fax);
		}

		# want a new location from the organisation object
		$premisesid = $_REQUEST['premisesid'];
		$premises_phone = $_REQUEST['premises_phone'];
		$premises_fax = $_REQUEST['premises_fax'];
		if ($premisesid) {
			$backend->set_hidden_field('premisesid',     $premisesid);
			$backend->set_hidden_field('premises_phone', $premises_phone);
			$backend->set_hidden_field('premises_fax',   $premises_fax);
		}


		if($this->id && $this->delete_access()) {
			$backend->set_toolbar_button("delete","javascript: if (confirm('Are you sure you want to delete this location?".(($c=count($this->placements))?"\\n$c users are affiliated to it.":"").(($c=count($this->premises))?"\\n$c organisations are affiliated to it.":"")."') && confirm('Really sure? This is irreversible.')) {document.edit.action.value='Delete';document.edit.submit()}","Delete this location","delete");
		}
		
		$backend->print_header();	

		$backend->open_section('Attributes');
		
		$backend->open_field('Name:','top');
		echo text_box(name,$this->name,25,128,'class=data');

		$backend->open_section('Address');
		
		$backend->open_field('Street','top');
		echo text_area(s_address,$this->s_address,35,2,255,'class=data');

		$backend->open_field('Suburb/Town:','top');
		echo text_box(s_suburb,$this->s_suburb,25,128,'class=data');

		$backend->open_field('State/Province:','top');
		echo text_box(s_state,$this->s_state,5,128,'class=data');

		$backend->open_field('Post/Zip Code:','top');
		echo text_box(s_postcode,$this->s_postcode,5,10,'class=data');

		$countries_config = &get_config('Countries');
		$backend->open_field('Country:','top');
		echo combo_box(s_countryid,$countries_config->countries,$this->s_countryid,'class=data',35);


		# no ID ? creating a new location then
		if (!$this->id) {
			$backend->print_commit_button("Create","if(confirm('Commit this information to the system?')){document.edit.action.value='New Location';document.edit.submit();}");

		# there be an id so show the organisatison and users
		# and the COMMIT button
		} else {

		
			$backend->open_section('Organisations','organisation');

			$backend->open_field('Located Here:','top');

			reset($this->premises);
			if (empty($this->premises)) {
				echo 'None.';
			} else {
			?>
				<table cellpadding=0 cellspacing=0 border=0>
				<?
				foreach($this->premises as $organisationid => $data) {
					?> 
					<tr>
						<td><p class=data><a href="organisation.php?organisationid=<?=$organisationid?>"><?=$data['name']?></a></td>
						<? if ($data['parentid'] > 0) { ?>
						<td><p class=data>&nbsp;of <a href="organisation.php?organisationid=<?=$data['parentid']?>"><?=$data['parent_name']?></a></td>
						<? } ?>
					</tr>
					<?
				}#end foreach
				?>
				</table>
			<?
			}#end if

			$backend->open_section('Users','user');

			$backend->open_field('Located Here:','top');
			$this->users_system->display_users_table(array_keys($this->placements),'','','user_page_counter');

			$affect = "";
			if ($c = count($this->placements))$affect .= "$c user".(($c > 1) ? "s" : "");
			if ($c = count($this->premises))  $affect .= (($affect) ? " and " : "")."$c organisation".(($c > 1) ? "s" : "");
			if ($affect) $affect = "\\nThis will affect $affect.";

			$backend->print_commit_button("Commit","if(confirm('Commit this information to the system? $affect')){document.edit.action.value='Commit';document.edit.submit();}");

		}#end if

		$backend->print_footer();
	}
	 

}# end Class Location

?>

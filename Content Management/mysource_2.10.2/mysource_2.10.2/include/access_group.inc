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
## File: include/access_group.inc
## Desc: Functions for dealing with access groups
## $Source: /home/cvsroot/mysource/include/access_group.inc,v $
## $Revision: 2.14 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:58 $
#######################################################################
require_once("$INCLUDE_PATH/users.inc");
#---------------------------------------------------------------------#


 #####################################################
# This is what you talk to for Access Group issues
class Access_Group extends WebObject {
	
	# Var
	var $users_system; # Reference to the object that controls the users system;
	
	var $id = 0;
	var $siteid = 0;
	var $name = '';
	var $description = '';

	var $grants = array();
				# An array of ids, 
				# if there is no $this->siteid then it is an array of siteids
				# otherwise it is an array of pageids

	var $user_memberships = array();         # array of userids who are members of this group
	var $organisation_memberships = array(); # array of organisationids who are members of this group

	 ##############################
	# Constructor
	function Access_Group($groupid=0) {
		
		WebObject::WebObject();

		 #################################################
		# Load the group information if an id is specified
		if ($groupid) {
			return $this->load($groupid);
		}
	}


	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = WebObject::__sleep();
		array_remove_element("users_system",$result);
		return $result;
	}


	 ##############################################
	# Wake up with this handy information VERY day !
	function __wakeup() {
		WebObject::__wakeup();
		$this->users_system = &get_users_system();
	}

	 
	 ################################################
	# Returns a pointer to this group's site
	function &get_site($siteid=0) {
		if(empty($siteid)) $siteid = $this->siteid;
		if(!$siteid) return;
		return $this->web_system->get_site($siteid);
	}

	 
	 ######################################################################
	# Create a new group record in the database and load it into this object
	function create($name="",$siteid=0) {
		if (!$name) return;
		$site = &$this->get_site();

		$db = &$this->web_system->get_db();
		if ($db->single_element("SELECT count(*) as num FROM access_group WHERE name='".addslashes($name)."' and siteid='$siteid'")) {
			return "Unable to create access group: \"$name\" already in use";
		} else {
			$groupid = $db->insert("INSERT INTO access_group (name,siteid) VALUES ('".addslashes($name)."','$siteid')");
			if ($groupid) {
				$this->load($groupid);
				return "New access group \"$name\" created".(($site->id)?" for site $site->name":"").".";
			} else {
				return "Unable to create access group.";
			}#end if

		}#end if

	}#end create()


	 ################################################
	# Loads the group details, from cache or database
	function load($groupid=0) {
		if ($groupid <= 0 && !($groupid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load access group information without a valid groupid.",__FILE__,__LINE__);
			$this = new Access_Group();
			return false;
		}

		 ###############################################
		# Check the cache
		$loaded = $this->load_from_cache($groupid);

		if ($loaded) return $groupid;

		 ########################
		# Load from the database
		$query = "SELECT groupid,siteid,name,description FROM access_group WHERE groupid='$groupid'";
		list(
			$this->id,
			$this->siteid,
			$this->name,
			$this->description
			) = $this->web_system->db->single_row($query);

		if (!$this->id) { # EEk! Clear.
			$this = new Access_Group();
			return false;
		}

		# Don't need to know this in here.. not our problem. also our cahce wont get cleared
		#$sql = "SELECT ".(($this->siteid) ? "page" : "site")."id
		#		FROM ".(($this->siteid) ? "page" : "site")."_access_grant
		#		WHERE groupid = '$groupid'";
		#$this->grants = $this->web_system->db->single_column($sql);

		$sql = "SELECT userid 
				FROM access_group_user_membership
				WHERE groupid = '$groupid'";
		$this->user_memberships = $this->web_system->db->single_column($sql);

		$sql = "SELECT organisationid
				FROM access_group_organisation_membership
				WHERE groupid = '$groupid'";
		$this->organisation_memberships = $this->web_system->db->single_column($sql);

		 ############################
		# Save to the cache
		$this->save_to_cache($groupid);

		return $groupid;
	}

	 #################
	# Check the cache
	function load_from_cache($id=0) {
		if (empty($id)) $id = $this->id;
		$web_mum = &$this->web_system;
		$users_mum = &$this->users_system;
		$loaded = WebObject::load_from_cache($id);
		$this->web_system = &$web_mum;
		$this->users_system = &$users_mum;
		return $loaded;
	}#end load_from_cache()

	 ######################
	# Saves to the cache
	function save_to_cache($id=0) {

		if (empty($id)) $id = $this->id;

		$web_mum = &$this->web_system;
		$users_mum = &$this->users_system;
		unset($this->web_system);
		unset($this->users_system);
		WebObject::save_to_cache($id);
		$this->web_system = &$web_mum;
		$this->users_system = &$users_mum;
	}#end save_to_cache()



	  ##################################################
	 # Duplicates this group and all it's memberships
	function dupe($new_siteid=0) {
		if (empty($new_siteid)) $new_siteid = $this->siteid;

		$session = &get_mysource_session();
		$dupe_map = $session->get_var('dupe_map');

		$db = &$this->web_system->get_db();

		$sql = "INSERT INTO access_group 
				(siteid, name, description) 
				VALUES
				('$new_siteid', '".addslashes((($new_siteid==$this->siteid)?increment_name($this->name):$this->name))."', '".addslashes($this->description)."')";

		$new_groupid = $db->insert($sql);
		if ($new_groupid) { 
			if (!empty($this->user_memberships)) {
				# dupe user memberships
				foreach($this->user_memberships as $userid) {
					$db->insert("INSERT INTO access_group_user_membership(groupid, userid) VALUES ('$new_groupid', '$userid')");
				}
			}
			if (!empty($this->organisation_memberships)) {
				# dupe organisation memberships
				foreach($this->organisation_memberships as $organisationid) {
					$db->insert("INSERT INTO access_group_organisation_membership(groupid, organisationid) VALUES ('$new_groupid', '$organisationid')");
				}
			}

			$dupe_map['access_group'][$this->id] = $new_groupid;
			$session->set_var('dupe_map', $dupe_map);
			return $new_groupid;

		} else {
			return false;

		}#end if

	}#end dupe()


	 ######################################################
	# Does the currently logged in user have write access?
	function write_access() {
		# if this is a site specific group only need to be siteadmin to edit
		if ($this->siteid) {
			$site = &$this->get_site($this->siteid);
			return $site->admin_access();
		# groups is a general group so need to be a superuser
		} else {
			return superuser('web');
		}#end if
	}

	 ####################################################
	# Does the currently logged in user have read access?
	function read_access() {
		# if this is a site specific group only need to be siteadmin to edit
		$editable_sites = &$this->web_system->get_editable_sites();
		if ($this->siteid) isset($editable_sites[$this->siteid]);
		return !empty($editable_sites);
	}


	 ###################################################
	# May the currently logged in user delete this group?
	# if they can write to it they can delete it
	function delete_access() {
		return $this->write_access();
	}

	 ##############################
	# Delete this bastard
	function delete() {

		$db = &$this->web_system->db;
		$del_group = $db->delete("DELETE FROM access_group WHERE groupid='$this->id'");
		if ($del_group) { 
			$this->clear_cache($this->id);

			$del_users = $db->delete("DELETE FROM access_group_user_membership WHERE groupid='$this->id'");
			$del_orgs  = $db->delete("DELETE FROM access_group_organisation_membership WHERE groupid='$this->id'");
			$del_sites = $db->delete("DELETE FROM site_access_grant WHERE groupid='$this->id'");
			$del_pages = $db->delete("DELETE FROM page_access_grant WHERE groupid='$this->id'");

			# If this is a site access group?
			if($this->siteid) {
				$site = &$this->get_site($this->siteid);
				if($site->id) $site->clear_cache();
			}
			$this = new Access_Group(0, $this->web_system);
			return "Deleted access group containing a membership of $del_users users and $del_orgs organisations (".((!$this->siteid) ? "$del_sites site grants and " : "")."$del_pages page grants).";
		}
		return "Unable to delete access group.";

	}#end delete()



	 ########################################
	# Change the name of the group
	function set_name($name='') {
		if ($name == $this->name) return "";

		if ($this->web_system->db->single_element("SELECT count(*) as num FROM access_group WHERE name='".addslashes($name)."' and siteid='$this->siteid'")) {
			return "Unable to create access group: \"$name\" already in use";
		} else {
			$this->name = $name;
			$this->web_system->db->update("UPDATE access_group SET name='".addslashes($this->name)."' WHERE groupid='$this->id'");
			$this->clear_cache($this->id);
			$site = &$this->get_site(); # Site likes to know this too
			if($site) $site->clear_cache();
			return "Name changed to ".$this->name;
		}#end if

	}#end set_name()

	 ########################################
	# Change the description of the group
	function set_description($desc='') {
		if ($desc == $this->desc) return "";
		$this->description = $desc;
		$this->web_system->db->update("UPDATE access_group SET description='".addslashes($this->description)."' WHERE groupid='$this->id'");
		$this->clear_cache($this->id);
		return "Description changed.";

	}#end set_description()

	 ########################################
	# Find a user and add them
	function add_member($member_type='', $id=0, $user_search="") {
		if ((empty($id) || empty($member_type)) && empty($user_search)) return;

		$inserted = false;

		switch($member_type) {

			case "user" :
				# if we have an id use it	
				if ($id) {
					$user = new User($id);
					if (!$user->id) {
						$msg = "User not found for userid '$id', unable to add to this access group";
					}

				# try and find a matching user
				} elseif ($user_search) {
					if ($result = $this->users_system->find_user($user_search)) {
						$user = new User($result['userid'], $this->users_system);
					} else {
						$msg = "User not found for '$user_search', unable to add to this access group";
					}
				} else {
					$msg = "No User supplied, unable to add to this access group";
				}

				# if we have a user add them
				if ($user) {
					# Check if already in group
					if (!empty($this->user_memberships) && in_array($user->id, $this->user_memberships)) {
						$msg = "User '".$user->name()."' is already a member of this access group.";
					} else {
						$sql = "INSERT INTO access_group_user_membership 
								(groupid,userid) 
								VALUES 
								('$this->id','$user->id')";

						if ($inserted = $this->web_system->db->insert($sql)) {
							 $this->user_memberships[] = $user->id;
							$msg = "User '".$user->name()."' has been added to this access group.";
						} else {
							$msg = "Unable to add user '".$user->name()."' to this access group.";
						}#end if
					}#endif already in group
				}#end if found user

			break;

			case "organisation" :
				if (!is_array($id)) $id = array();
				if (!empty($id)) {
					foreach($id as $ids){
						# if we have an id use it	
						if ($ids) {
							$org = &$this->users_system->get_organisation($ids);
							if (!$org->id) {
								$msg = "Organisation not found for organisationid '$id', unable to add to this access group";
							}
						} else {
							$msg = "No Organisation ID supplied, unable to add to this access group";
						}

						# if we have a organisation, add them
						if ($org) {
							# Check if already in group
							if (!empty($this->organisation_memberships) && in_array($org->id, $this->organisation_memberships)) {
								$msg = "Organisation ".$org->name." is already a member of this access group.";
							} else {
								$sql = "INSERT INTO access_group_organisation_membership 
										(groupid,organisationid) 
										VALUES 
										('$this->id','$org->id')";

								if ($inserted = $this->web_system->db->insert($sql)) {
									$msg = "Organisation '".$org->name."' has been added to this access group.";
									$this->organisation_memberships[] = $org->id;
								} else {
									$msg = "Unable to add organisation '".$org->name."' to this access group.";
								}#end if
							}#endif already in group
						}#end if found org
					}
				}
			break;

			default : 
				$this->_set_error("Membership type '$member_type' unknown, unable to add member to this access group", __FILE__, __LINE__);
				return "Unknown membership type '$member_type', no one has been added to this access group";

		}#end switch

		return $msg;

	}#end add_member();

	 ############################################################
	# revoke membership for a certain membertype of a certain id
	function remove_member($member_type='', $id=0) {
		if (empty($id) || empty($member_type)) return;
		switch($member_type) {

			case "user" :
				$user = new User($id, $this->users_system);
				$name = ($user) ? $user->name() : "";
				if ($deleted = $this->web_system->db->delete("DELETE FROM access_group_user_membership WHERE groupid='$this->id' AND userid='$id'")) {
					$msg = "User '$name' ($id) has been removed from this access group";
					array_remove_element($id,$this->user_memberships);
				} else {
					$msg   = "Unable to remove User '$name' ($id) from this access group";
				}
				unset($user);
			break;

			case "organisation" :
				$org = &$this->users_system->get_organisation($id);
				$name = ($org) ? $org->name : "";
				if ($deleted = $this->web_system->db->delete("DELETE FROM access_group_organisation_membership WHERE groupid='$this->id' AND organisationid='$id'")) {
					$msg = "Organisation '$name' ($id) has been removed from this access group";
					array_remove_element($id,$this->organisation_memberships);
				} else {
					$msg   = "Unable to remove Organisation '$name' ($id) from this access group";
				}
				unset($org);
			break;

			default : 
				$this->_set_error("Membership type '$member_type' unknown, unable to remove id '$id'", __FILE__, __LINE__);
				return "Unknown membership type '$member_type', no one has been remove from this access group";

		}#end switch

		return $msg;

	}#end remove_member()




	 ##########################################################
	# Prints the backend for the user
	function print_backend() {
		global $XTRAS;

		$system_config = &get_system_config();
		$session = &get_mysource_session();

		 ###################################
		# Setup the backend a little
		if($this->siteid) {
			$site = &$this->get_site();
			$backend = &$site->setup_backend(); # Backend refrence
		} else {
			$backend = &$this->web_system->setup_backend(); # Backend refrence
		}
		$backend->set_title($this->name.' - Edit Access Group');

		 ####################################################################
		# SECUIRTY - Only let in those who have permission to work on a site
		if (!$session->logged_in()) {
			$session->login_screen($backend->title,"You must be logged in and have permission to edit <i>$system_config->system_name</i> web sites in order to proceed.");
		}

		 ######################################################################
		# This particular user must be affiliated with an managed organisation
		if (!$this->read_access()) {
			$session->login_screen($backend->title,'You do not have permission to view this access group.');
		}
	
		 #########################################
		# Process a submitted form
		$action = $_POST['action'];
		switch($action) {

			 ###################################
			# Commiting changes to the database
			case 'Commit':
				$name = $_POST['name'];
				$description = $_POST['description'];
				if($this->write_access()) {
					$backend->add_message($this->set_name(gpc_stripslashes($name)));
					$backend->add_message($this->set_description(gpc_stripslashes($description)));
					$delete_userids = $_POST['delete_userids'];
					if (!empty($delete_userids)) {
						foreach($delete_userids as $delete_userid) {
							$backend->add_message($this->remove_member('user', $delete_userid));
						}
					}
					$user_search = $_POST['user_search'];
					$user_searches = split("[ \t\r\n\,]+",$user_search);
					if (!empty($user_searches)) {
						foreach($user_searches as $user_search) {
							if ($user_search) {
								$backend->add_message($this->add_member('user', '', $user_search));
							}
						}
					}
				}
				$session->unset_var('general_access_group_list');
			break;

			 
			 ###################################
			# Delete this group from the database
			case 'Delete':
				if($this->delete_access()) {
					$reloc = ($this->siteid) ? "site.php?siteid=$this->siteid" : "web.php";
					$backend->add_message($this->delete());
					$backend->set_relocation($reloc);
					$backend->print_header();
					unset($this);
					exit();
				} else {
					$backend->add_message('Permission Denied.');
				}
			break;

			 ###################################
			# Dupe this group
			case 'Dupe':
				if($this->write_access()) {
					if ($new_groupid = $this->dupe()) {
						$backend->set_relocation($_SERVER['PHP_SELF']."?groupid=$new_groupid");
						$backend->add_message('Access Group Duplicated, now editing copy.');
						$backend->print_header();
						exit();
					} else {
						$backend->add_message('Duplication Failed');
					}
				} else {
					$backend->add_message('Permission Denied.');
				}
			break;

			 ########################################
			# Find a user and add them
			case 'Add User':
				if($this->write_access()) {
					$user_search = $_POST['user_search'];
					$backend->add_message($this->add_member('user', '', $user_search));
				}
			break;

			 ###########################
			# Remove a User
			case 'Remove User':
				if($this->write_access()) {
					$userid = $_REQUEST['userid'];
					$backend->add_message($this->remove_member('user', $userid));
				}
			break;

			 ########################################
			# Add an Organisation
			case 'Add Organisation':
				if($this->write_access()) {
					$new_organisationids = array();
					$new_organisationids = $_REQUEST['new_organisationids'];
					$backend->add_message($this->add_member('organisation', $new_organisationids));
				}
			break;

			 ###########################
			# Remove a Organisation
			case 'Remove Organisation':
				if($this->write_access()) {
					$organisationid = $_REQUEST['organisationid'];
					$backend->add_message($this->remove_member('organisation', $organisationid));
				}
			break;

		}#end switch ### LOOK FOR "site" in this file, make sure its okay.. also check write_access()

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
		
		$backend->set_tab("access_group", $_SERVER['PHP_SELF']."?groupid=$this->id", $this->name, "Edit Access Group? $this->name - $this->description", "group");
		$backend->set_active_tab('access_group');
		if (!$this->siteid) {
			$backend->remove_tab('site');
			$backend->remove_tab('page');
			$backend->remove_tab('file');
		}

		$backend->set_heading('Edit Access Group','group');
		$backend->set_name($this->name);
		$backend->set_id_string($this->id);
		
		$backend->set_hidden_field('action');
		$backend->set_hidden_field('groupid',$this->id);
		$backend->set_hidden_field('old_name',$this->name);

		$backend->set_hidden_field('userid');
		$backend->set_hidden_field('organisationid');
	

		if($this->write_access()) {
			$backend->set_toolbar_button("dupe","javascript: javascript: if (confirm('Are you sure you want to duplicate this access group?')) {document.edit.action.value='Dupe';document.edit.submit()}","Duplicate this Access Group","dupe");
		}

		if($this->delete_access()) {
			$backend->set_toolbar_button("delete","javascript: if (confirm('Are you sure you want to delete this access group?') && confirm('Really sure? This is irreversible.')) {document.edit.action.value='Delete';document.edit.submit()}","Delete this access group","delete");
		}


		$backend->print_header();		

		$backend->open_section('Attributes');		

		$backend->open_field('Scope:');
		if ($this->siteid) {
			$site = &$this->get_site();
			echo "$site->name (ID: $this->siteid)";

		} else {
			echo 'General';
		}#end if


		$backend->open_field('Name:', 'top');
		if($this->write_access()) {
			echo text_box(name,$this->name,20,255,'class=data');
		} else {
			echo $this->name;
		}

		$backend->open_field('Description:', 'top');
		if($this->write_access()) {		
			echo text_area(description,$this->description,30,4,255,'class=data');
		} else {
			echo $this->description;
		}

		$backend->open_section('User Memberships');

		if($this->write_access()) {
			$backend->open_field('Add Users:<br><span class=smallprint>(logins/emails)</span>','top');
			echo text_area(user_search,'',30,5,3000,'class=data');
		}

		$backend->open_field('Current Users ('.count($this->user_memberships).'):', 'top');
		
		$this->users_system->display_users_table($this->user_memberships,(($this->write_access())?'delete_userids':''),'Delete?','memberships_user_page');


		
		$backend->open_section('Organisation Memberships');
/*
		# User can only add orgs which they manage;
		if($this->write_access()) {
			$backend->open_field("Add Organisation:");
			echo combo_box(new_organisationid,$this->users_system->org_array_with_sticks(1),"class=data");
			$backend->print_icon_button("javascript: if(confirm('Add Organisation ?')){document.edit.action.value='Add Organisation';document.edit.submit()}","Add Organisation","create");
		}
*/
		$backend->open_field('Add Organisation:', 'top');
		
		# get the orgids
		$org_tree = &$this->users_system->get_organisation_tree();

		$managed_orgs = array();

		if (!empty($org_tree)) {
			foreach($org_tree as $org){
				$managed_orgs[] = $org['organisationid'];
			}
		}
		# get a list of ids of which the user is a manager of
	
		
		#get a list of the orgs with sticks
		$orgs = &$this->users_system->organisation_array_with_sticks();
		
		# create a list of orgs (with sticks) of which the user is a member
		$wanted_orgs = $orgs;

		if (!empty($orgs)) {
			foreach (array_keys($orgs) as $org_with_stick_id) {
				if(!in_array($org_with_stick_id, $managed_orgs)){
					unset($wanted_orgs[$org_with_stick_id]);
				}
			}
		}

		# create a multiselect box, so the user can
		# affiliate users with more that one organisation at a time

		if(empty($orgs)) {
			echo 'No Organisations.';
		} else { 
			echo multiple_combo_box('new_organisationids', $wanted_orgs, 'class=data', '', 70, 5);
			$backend->print_icon_button("javascript: if(confirm('Add Organisation ?')){document.edit.action.value='Add Organisation';document.edit.submit()}","Add Organisation","create");
		}

		$backend->open_field('Current Organisations ('.count($this->organisation_memberships).'):', 'top');

		if (!empty($this->organisation_memberships)) {

			$organisation_page = $_REQUEST['organisation_page'];
			if (!$organisation_page) $organisation_page = 1;
			
			$organisation_count  = count($this->organisation_memberships);
			$per_page    = 20;
			$page_count  = ceil($organisation_count / $per_page);
			if ($organisation_page > $page_count) $organisation_page = $page_count;
			$offset      = ($organisation_page - 1) * $per_page;

			$sql = "SELECT organisationid, name
					FROM organisation
					WHERE organisationid IN ('".implode("','", $this->organisation_memberships)."')
					ORDER BY name
					LIMIT $offset, $per_page";

			$organisations = $this->users_system->db->associative_array($sql);
		?>
			<input type="hidden" name="organisation_page" value="<?=$organisation_page?>">
			<table cellpadding=2 cellspacing=2 border=0>
				<tr>
					<td><p class=field>ID</td>
					<td><p class=field>Name</td>
					<td><p class=field>Users</td>
				<? if ($this->write_access()) { ?>
					<td><p class=field>&nbsp;</td>
				<? } ?>
				</tr>
			<?
			if (empty($organisations)) {
				?>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
				<?
			} else {
				foreach($organisations as $organisationid => $name) {
					$num_users = $this->users_system->db->single_element("SELECT count(*) FROM affiliation where organisationid = '$organisationid'");
					?>
					<tr bgcolor=#d0d0d0>
						<td align="right"><p class=data><?=$organisationid?></td>
						<td><p class=data><a onmouseover="window.status='View <?=addslashes($name)?>\'s information.';return true;" onmouseout="window.status='';return true;" href="organisation.php?organisationid=<?=$organisationid?>"><?=$name?></a></td>
						<td><p class=data><? echo $num_users ?></td>
						<? 
						if ($this->write_access()) {
						?>
							<td align="center">
							<?
								$backend->print_icon_button("javascript:if (confirm('Sure you want to remove this organisation from this access group?')) {document.edit.organisationid.value='$organisationid';document.edit.action.value='Remove Organisation';document.edit.submit()}","Revoke Group Membership","delete");
							?>
							</td>
						<? 
						}#end if write access
						?>
					</tr>
					<?
				}
			}
			if ($page_count > 1) {
			?>
				<script language=javascript>
					function jump_organisation_page(p) {
						document.edit.organisation_page.value = p;
						document.edit.submit();
					}
				</script>
				<tr>
					<td colspan=1 align=left><b>
					<? if ($organisation_page > 1) { ?>
						<a href="javascript:jump_organisation_page(<?=($organisation_page-1)?>);" onmouseover="javascript: window.status='View the previous page of organisations'; return true;" onmouseout="javascript: window.status=''; return true;">&lt;&lt; Prev</a>
					<? } else { ?>
						&lt;&lt; Prev
					<? }#end if ?>
					</td>
					<td colspan=2 align=center><font face=arial size=2><? echo("Page $organisation_page of $page_count") ?></td>
					<td colspan=1 align=right><font face=arial size=><b>
					<? if ($organisation_page != $page_count) { ?>
						<a href="javascript:jump_organisation_page(<?=($organisation_page+1)?>);" onmouseover="javascript: window.status='View the next page of organisations'; return true;" onmouseout="javascript: window.status=''; return true;">Next &gt;&gt;</a>
					<? } else { ?>
						Next &gt;&gt;
					<? }#end if ?>
					</td>
				</tr>
				<tr>
					<td colspan=4>
						<font face=courier>
						<?
						$digits = strlen((int) $page_count);

						for($i = 1; $i <= $page_count; $i++) {
							$num = sprintf("%0$digits"."d",$i);
							if ($i == $organisation_page) { 
							?>
								<span style="color:red;"><?=$num?></span>
							<?
							} else {
							?>
								<a href="javascript:jump_organisation_page(<?=$i?>)" onmouseover="javascript: window.status='Jump to page <?=$i?> of the organisations'; return true;" onmouseout="javascript: window.status=''; return true;"><?=$num?></a>
							<?
							}
						}

						?>						
					</td>
				</tr>
			<?
			}
			?>
			</table>
			<? 
		} else {
			echo '<p class=data>None';
		}#end if organisation_memberships

		$backend->print_commit_button("Commit","if(confirm('Commit this information to the system?')){document.edit.action.value='Commit';document.edit.submit();}");

		$backend->print_footer();

	}#end print_backend()

	   ###########################################################################
	  # for the passed user returns an array of access groups
	 # they belong to, the web system is so that this access group object doesn't 
	# need to be instanciated for this function to run
	function get_user_access_groups(&$user) {

		$web_system = &get_web_system();

		# yeh, we got a web system
		if ($web_system) {

			$user_groups = $web_system->db->single_column("SELECT groupid FROM access_group_user_membership WHERE userid='".$user->id."'");
			if (!is_array($user_groups)) $user_groups = array();
			if ($user->affiliations) {
				$orgids = array_keys($user->affiliations);
				$org_groups = $web_system->db->single_column("SELECT groupid FROM access_group_organisation_membership WHERE organisationid in ('".implode("','", $orgids)."')");
				if (!is_array($org_groups)) $org_groups = array();
			} else {
				$org_groups = array();
			}#end if

			$groups = array_merge($user_groups, $org_groups);
			$groups = array_unique($groups);

			return $groups;

		} else {
			$err = new Object();
			$err->_set_error("No Web System Available to get user access group information", __FILE__, __LINE__);
			return Array();
		}#end if

	}#end get_user_access_groups()

	 ################################################
	# Return an array of userid,name for all members
	function get_all_users() {
		$db = &$this->get_db();
		$userids = $db->single_column("SELECT userid FROM affiliation WHERE organisationid IN ('".implode("','",$this->organisation_memberships)."')"); 
		$userids = array_unique(array_merge($userids,$this->user_memberships));
		$users = $db->associative_array("SELECT userid,concat(firstname,' ',surname) FROM user WHERE USERID IN ('".implode("','",$userids)."')");
		return $users;
	}

}#end Class access_group

?>

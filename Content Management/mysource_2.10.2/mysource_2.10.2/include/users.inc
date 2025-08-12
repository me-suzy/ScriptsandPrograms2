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
## File: include/users.inc
## Desc: Functions for dealing with the user database
## $Source: /home/cvsroot/mysource/include/users.inc,v $
## $Revision: 2.49 $
## $Author: lwright $
## $Date: 2004/03/22 22:06:59 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/usersobject.inc");
include_once("$INCLUDE_PATH/organisation.inc");
include_once("$INCLUDE_PATH/location.inc");
include_once("$INCLUDE_PATH/user.inc");
#---------------------------------------------------------------------#

 #####################################################
# This is what you talk to to do various webby things
class Users extends Object {

	var $db;

	var $user_factory=array(); # An array of user objects as they've asked for.
	var $organisation_factory=array(); # An array of organisation objects as they've asked for.
	var $location_factory=array(); # An array of location objects as they've asked for.
	var $extension_factory=array(); # An array of users_extension objects as they've asked for

	var $authentication_factory=array(); # A Users_Authentication Xtra

	var $backend='';
	var $organisation_tree=array();

	 ##############################
	# Constructor
	function Users() {
		$this->get_db();
	}


	 #######################################
	# Returns a reference ot the db object
	function &get_db() {
		if(get_class($this->db) != "db") {
			 #############################################################
			# Open the connection to the database - using configuration
			$system_config = &get_system_config();
			$this->db = new Db(
				$system_config->user_db_details['name'],
				$system_config->user_db_details['login'],
				$system_config->user_db_details['password'],
				$system_config->user_db_details['host'],
				$system_config->user_db_details['querylog']
			);
			# Validate all data coming out of the database to ensure it hasn't
			# been corrupted - if the data is in a multi-byte character set
			if(in_array("users",$system_config->check_db_mbstrings)) {
				$this->db->multibyte_string_protection_charset($system_config->default_charset);
			}

			$this->db->set_error_call("report_error");
			$version = $this->db->server_version();
			if(version_no_compare($version,MYSOURCE_REQUIRED_MYSQL_VERSION) < 0) {
				if (!empty($version)) {
					report_error(__FILE__,__LINE__,MYSOURCE_LONG_NAME." requires MySQL Version ".MYSOURCE_REQUIRED_MYSQL_VERSION.". You may need to upgrade. Your user database's current version is ".$version.".");
				} else {
					report_error(__FILE__,__LINE__,MYSOURCE_LONG_NAME." requires a MySQL connection. We are unable to connect to the user database. Please check the connection details in the mysource.conf file.");
				}
				exit();
			}
		}
		return $this->db;
	}

	  ############################################################
	 # Given an attempted login or email, tries to find the user
	 # This is pretty legacy now
	function find_user($search='') {
		if (!$search) return;
		if (ereg("@",$search)) $field = "email";
		elseif (ereg("^[0-9]+$",$search)) $field = "userid";
		else                   $field = "login";
		$query = "SELECT * FROM user WHERE $field='".addslashes($search)."'";
		$result = $this->db->single_row($query);
		return $result;
	}


	  ##########################################################################################
	 # Given an attempted userid, login or email, tries to find the user and returns the object
	function &get_user($search='', $use_field=false, $allow_numeric_names=false) {
		$valid_fields = array('email', 'login', 'userid');
		if ($use_field && in_array($use_field, $valid_fields)) {
			// we have a valid field passed in
			// so dont bother working out what field
			// this is for
			$field = strtolower($use_field);
			if ($field == 'userid') {
				$userid = (int) $search;
			} else {
				$query = "SELECT userid FROM user WHERE $field='" . addslashes($search) . "'";
				$userid = $this->db->single_element($query);
			}
		} else {
			
			#var boolean $allow_numeric_names - allows to search for numeric logins

			if (!$allow_numeric_names)	{
				if("$search" == ((string)((int)$search))) $userid = (int) $search;
			}
	
			if(!$userid || $allow_numeric_names) {
				if (ereg("@",$search)) $field = "email";
				else                   $field = "login";
				$query = "SELECT userid FROM user WHERE $field='" . addslashes($search) . "'";
				$userid = $this->db->single_element($query);
				if (!$userid) {
					if("$search" == ((string)((int)$search))) $userid = (int) $search;
				}
			}

		}

		if(!$userid) return new User();
		if(get_class($this->user_factory[$userid]) != "user") {
			$this->user_factory[$userid] = new User($userid);
			#if(count($this->user_factory) > 50) { # Whey hey hey.. getting out of control here
			#	array_shift($this->user_factory);
			#}
		}

		return $this->user_factory[$userid];
	}

	  ##########################################################################################
	 # Given an organisationid tries to find the orgnisation and returns the object
	function &get_organisation($organisationid=0) {
		if(!$organisationid) return;
		if(get_class($this->organisation_factory[$organisationid]) != "organisation") {
			$this->organisation_factory[$organisationid] = new Organisation($organisationid);
		}
		return $this->organisation_factory[$organisationid];
	}

	  #############################################
	 # Given locationid tries to find the location
	function &get_location($locationid=0) {
		if(!$locationid) return;
		if(get_class($this->location_factory[$organisationid]) != "organisation") {
			$this->location_factory[$locationid] = new Location($locationid);
		}
		return $this->location_factory[$locationid];
	}


	 ######################################################
	# Clears up memory. Other code can call this if it
	# feels guilty but know its wont stuff any other code up
	function forget_user($userid=0) {
		unset($this->user_factory[$userid]);
	}
	function forget_organisation($organisationid=0) {
		unset($this->organisation_factory[$organisationid]);
	}
	function forget_location($locationid=0) {
		unset($this->location_factory[$locationid]);
	}

	  ####################################################################
	 # Given a users_extension codename, load and return a refernce to it
	function &get_extension($extension='') {
		if(!$extension) return;
		$class_name = "users_extension_$extension";
		if(get_class($this->extension_factory[$extension]) != $class_name) {
			global $XTRAS_PATH;
			include_once("$XTRAS_PATH/users/extensions/$extension/$extension.inc");
			$this->extension_factory[$extension] = new $class_name();
		}
		return $this->extension_factory[$extension];
	}


	 #########################################################################
	# Given a users_authentication codename, load and return a refernce to it
	function &get_authentication($authentication='') {
		if(!$authentication) {
			$system_config = &get_system_config();
			$authentication = $system_config->user_authentication;
		}
		$class_name = "users_authentication_$authentication";
		if(get_class($this->authentication_factory[$authentication]) != $class_name) {
			global $XTRAS_PATH;
			include_once("$XTRAS_PATH/users/authentication/$authentication/$authentication.inc");
			$this->authentication_factory[$authentication] = new $class_name();
		}
		return $this->authentication_factory[$authentication];
	}


	 ###########################################################
	# Given an Location ID, returns a nice string describing it
	function location_address($locationid=0) {
		global $LOCATION_ADDRESSES;
		if ($LOCATION_ADDRESSES[$locationid]) return $LOCATION_ADDRESSES[$locationid];
		$countries_config = &get_config("Countries");
		list($a,$s,$t,$c) = $this->db->single_row("SELECT s_address, s_suburb, s_state, s_countryid FROM location WHERE locationid='$locationid'");
		$location = "$a, $s $t {$countries_config->countries[$c]}";
		$LOCATION_ADDRESSES[$locationid] = $location;
		return $location;
	}


	 ###########################################################################################
	# Loads the bare information about all the organisations in the database into an tree array
	function &load_organisation_tree() {
		$this->organisation_tree = $this->db->associative_array("SELECT organisationid, name, description, parentid FROM organisation ORDER BY name");
		for(reset($this->organisation_tree); NULL !== ($orgid = key($this->organisation_tree)); next($this->organisation_tree)) {
			$this->organisation_tree[ $this->organisation_tree[$orgid]['parentid'] ]['childids'][] = $orgid;
		}
		reset($this->organisation_tree);
		return $this->organisation_tree;
	}


	 ###########################################################################################
	# Loads the bare information about all the organisations in the database into an tree array
	function &get_organisation_tree() {
		if(!count($this->organisation_tree)) {
			$this->load_organisation_tree();
		}
		return $this->organisation_tree;
	}

	 ####################################
	# Is this user a manager anywhere?
	function is_manager($userid=0) {
		if (!$userid) return false;
		return $this->db->single_column("SELECT userid FROM affiliation WHERE manager='Y' AND userid='$userid'");
	}

	 ######################################
	# Is this person a manager and are there any subordinates in this organisation ?
	function get_subordinates($userid=0,$orgid=0) {
		if (!$userid || !$orgid) return false;
		if (!$this->is_manager($userid)) return false;
		return $this->db->single_column("SELECT userid FROM affiliation WHERE manager='N' AND organisationid='$orgid' AND userid != '$userid'");
	}

	 #####################################
	# Lists all managers for an organisation.
	function get_managers($organisationid=0,$userid=false) {
		if (!$organisationid) return false;
		$query = "SELECT userid FROM affiliation WHERE manager='Y' AND organisationid='$organisationid'";
		if ($userid) $query .= " AND userid != '$userid'";
		return $this->db->single_column($query);
	}

	 #######################################################################################################
	# Which Organisations does this user have control of? Show their managership of children organisations?
	# Strict takes being a superuser out of the equation.
	function &get_managed_organisationids($userid=0,$show_descendants=true,$strict=false) {
		global $MANAGED_ORGANISATIONIDS;
		$session = &get_mysource_session();
		if(!$userid) $userid = $session->user->id;
		if(!$userid) {
			$tmp = Array();
			return $tmp;
		}
		if(isset($MANAGED_ORGANISATIONIDS[$userid])) return $MANAGED_ORGANISATIONIDS[$userid];
		$orgs = array();
		$org_tree = &$this->get_organisation_tree();
		# Superuser can manage everything
		if(($session->user->id == $userid) && superuser("user") && $show_descendants && !$strict) {
			$orgs[] = "0";
		} else {
			$orgs = $this->db->single_column("SELECT organisationid FROM affiliation WHERE userid='$userid' AND manager='Y'");
		}
		if (!empty($orgs)) {
			foreach($orgs as $id) {
				$MANAGED_ORGANISATIONIDS[$userid][] = (int)$id;
				if($show_descendants) {
					$MANAGED_ORGANISATIONIDS[$userid] = array_unique(array_merge($MANAGED_ORGANISATIONIDS[$userid],$this->organisation_descendantids($id)));
				}
			}
		}
		reset($org_tree);
		return $MANAGED_ORGANISATIONIDS[$userid];
	}

	 ####################################################
	# Is the user a manager of a higher up organisation?
	function higher_manager($userid=0,$orgid=0) {
		$session = &get_mysource_session();
		if(!$userid) $userid = $session->user->id;
		if(!$userid) return false;
		if(($session->user->id == $userid) && superuser("user")) {
			return true;
		}
		$org_tree = &$this->get_organisation_tree();
		$parentid = $org_tree[$orgid]['parentid'];
		$managed_organisationids = &$this->get_managed_organisationids($userid);
		if(in_array($parentid,$managed_organisationids)) {
			return true;
		} else {
			return false;
		}
	}

	 ###########################################################
	# Returns a hieracrhcy of all the organisations with sticks
	function org_array_with_sticks($managed='',$parentid=0,$stix='') {
		#did this cause other function might feed it in $parentid but that can be null so putting $parentid=0 above doesn't get run thus stuffing things up.
		if (!$parentid) $parentid = 0;
		if($managed) {
			$managed_organisationids = &$this->get_managed_organisationids();
		}
		$org_tree  = &$this->get_organisation_tree();
		$childids  = $org_tree[$parentid]['childids'];
		$result = array();
		if (!empty($childids)) {
			$countdown = count($childids);
			foreach($childids as $childid) {
				$countdown--;
				if(!$managed || in_array($childid,$managed_organisationids)) {
					$result[$childid] = $stix.(($countdown)?"|-":"`-").$org_tree[$childid]['name'];
				}
				if(count($org_tree[$childid]['childids'])) {
					$newstix = $stix.(($countdown)?"|&nbsp;":"&nbsp;&nbsp;");
					$result = $result + $this->org_array_with_sticks($managed,$childid,$newstix);
				}
			}
		}
		return $result;
	}

	 ##################################################################
	# Returns an array with a list of all the locations available in
	# the system
	function get_location_options($show_in_list='address')	{
		$db = &$this->get_db();
		if ($show_in_list == 'name') {
			$sql = "SELECT * FROM location order by name";
		} else {
			$sql = "SELECT * FROM location order by s_suburb";
		}
		$location_search_results = $db->associative_array($sql);

		if (empty($location_search_results))	{
				$string = "<b>There are no locations in the system to choose from.</b><input type=hidden name=primary_locationid value=0>";
				return $string;
		} else {
			$final = array();
			foreach ($location_search_results as $locationid=>$values)	{
				if ($show_in_list == 'name' && trim($values['name']) != '') {
					$final[$locationid] = $values['name'];
				} else {
					$final[$locationid] = $values['s_address'].', '.$values['s_suburb'].', '.$values['s_state'];
				}
			}
			return $final;
		}
	}

	 ##################################################################
	# Same as above, but returns result for locations linked to an organisation.
	# searches teh premises table
	function get_location_options_for_organisation($orgid=0) {
		$db = &$this->get_db();
		$sql = "SELECT * FROM location as a, premises as b WHERE b.organisationid = '".$orgid."' AND a.locationid = b.locationid order by a.s_suburb";
		$location_search_results = $db->associative_array($sql);

		if (empty($location_search_results))	{
				$string = array();
				return $string;
		} else {
			$final = array();
			foreach ($location_search_results as $locationid=>$values)	{
				$final[$locationid] = $values["s_address"].", ".$values["s_suburb"].", ".$values["s_state"];
			}
			return $final;
		}
	}

	 #######################################################
	# Which users does this user have control of?
	function &managed_userids($userid=0,$show_descendants=true) {
		global $MANAGED_USERIDS;
		$session = &get_mysource_session();
		if(!$userid) $userid = $session->user->id;
		if(!$userid) {
			$tmp = Array();
			return $tmp;
		}
		if(isset($MANAGED_USERIDS[$userid])) return $MANAGED_USERIDS[$userid];
		if(($session->user->id == $userid) && superuser("user") && $show_descendants) {
			$MANAGED_USERIDS[$userid] = $this->db->single_column("SELECT userid FROM user");
		} else {
			$managed_organisationids = &$this->get_managed_organisationids($userid,$show_descendants);
			$MANAGED_USERIDS[$userid] = $this->db->single_column("SELECT userid FROM affiliation WHERE organisationid IN ('".implode("','",$managed_organisationids)."')");
		}
		return $MANAGED_USERIDS[$userid];
	}

	 ######################################################################
	# Recursively returns an array of all the organisations descendant ids
	function organisation_descendantids($orgid=0,$strict=false) {
		$org_tree = &$this->get_organisation_tree();
		$app = array();
		$result = $org_tree[$orgid]['childids'];
		if (!$strict && !empty($result)) {
			foreach($result as $childid) {
				if ($childid) {
					$app = array_merge($app, $this->organisation_descendantids($childid));
				}
			}
		}
		return array_merge($result,$app);
	}

	 ##################################################
	# Returns the HREF to the backend for this object
	function get_backend_href() {
		global $EDIT_DIR;
		return "$EDIT_DIR/users.php?";
	}

	 ##################################################
	# Simply returns a reference to the web db
	function &get_backend() {
		if(get_class($this->backend) != "backend") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/backend.inc");
			global $EDIT_DIR, $EDIT_PATH;
			$this->backend = new Backend($EDIT_DIR,$EDIT_PATH);
		}
		return $this->backend;
	}

	 ################################################################################
	# Sets up a backend object for webby objects to use while doing their backends !
	function &setup_backend() {
		$session = &get_mysource_session();
		 ######################################################################
		# Remember most recently used user, organisation and location
		$session_active_userid = $session->get_var('active_userid');
		$session_active_organisationid = $session->get_var('active_organisationid');
		$session_active_locationid = $session->get_var('active_locationid');

		$active_userid = $_REQUEST['active_userid'];
		$active_organisationid = $_REQUEST['active_organisationid'];
		$active_locationid = $_REQUEST['active_locationid'];

		$userid = $_REQUEST['userid'];
		$organisationid = $_REQUEST['organisationid'];
		$locationid = $_REQUEST['locationid'];

		if ($active_userid > 0) $session_active_userid = $active_userid;
		elseif ($userid > 0)    $session_active_userid = $userid;
		if ($active_organisationid > 0) $session_active_organisationid = $active_organisationid;
		elseif ($organisationid > 0)    $session_active_organisationid = $organisationid;
		if ($active_locationid > 0) $session_active_locationid = $active_locationid;
		elseif ($locationid > 0)    $session_active_locationid = $locationid;

		$backend = &$this->get_backend();

		$backend->set_active_menu_item('users');

		if ($session_active_userid) {
			$session_active_user_name = $this->db->single_element("SELECT concat(firstname,' ',surname) FROM user WHERE userid='$session_active_userid'");
			$backend->set_context_bar("user","<span class=field>Active User:</span> <a href=\"user.php?userid=$session_active_userid\">$session_active_user_name</a>");
		}

		if ($session_active_organisationid) {
			$org_tree = &$this->get_organisation_tree();
			$session_active_organisation_name = $org_tree[$session_active_organisationid]['name'];
			$backend->set_context_bar("organisation","<span class=field>Active Organisation:</span> <a href=\"organisation.php?organisationid=$session_active_organisationid\">$session_active_organisation_name</a>");
		}

		if ($session_active_locationid) {
			$session_active_location = $this->db->single_row("SELECT locationid, s_address, s_suburb, s_state, s_countryid, s_postcode FROM location WHERE locationid='$session_active_locationid'");
			$session_active_location_name = "$session_active_location[s_address], $session_active_location[s_suburb]";
			$backend->set_context_bar("location","<span class=field>Active Location:</span> <a href=\"location.php?locationid=$session_active_locationid\">$session_active_location_name</a>");
		}

		$session->set_var('active_locationid', $session_active_locationid);
		$session->set_var('active_organisationid', $session_active_organisationid);
		$session->set_var('active_userid', $session_active_userid);

		return $backend;
	}

	 ##########################################################
	# Prints the backend for the user system
	function print_backend() {
		$system_config = &get_system_config();
		$session = &get_mysource_session();

		 ###################################
		# Setup the backend a little
		$this->setup_backend();
		$backend = &$this->backend; # Backend refrence
		$backend->set_title("$system_config->system_name - Users Backend");
		$backend->set_heading("User System","users");
		$backend->set_subheading($system_config->system_name);

		 #########################################################################
		# SECUIRTY - Only let in those who have permission to work on a user base
		if (!$session->logged_in()) {
			$session->login_screen($backend->title,"You must be logged in and have permission to edit <i>$system_config->system_name</i> user records in order to proceed.");
		}

		$backend->set_hidden_field("extension_edit",$_GET['extension_edit']);

		 ##########################################################
		# Display the edit interface for the extension if asked for
		if($_GET['extension_edit']) {
			$extension = &$this->get_extension($_GET['extension_edit']);
			$extension->print_backend();
			return;
		}

		$org_tree = &$this->get_organisation_tree();
		$managed_organisationids = &$this->get_managed_organisationids();
		# if the user is not a super user and there are organisations,
		# then they need to manage at least one of them to continue
		if (!superuser('user') && !empty($org_tree) && empty($managed_organisationids)) {
			$session->login_screen($backend->title,"You must have permission to edit <i>$system_config->system_name</i> user records in order to proceed.");
		}

		$backend->set_hidden_field('authentication_edit',$_GET['extension_edit']);

		 #######################################################################
		# Display the edit interface for the authentication system if asked for
		if($_GET['authentication_edit']) {
			$authentication = &$this->get_authentication($_GET['authentication_edit']);
			$authentication->print_backend();
			return;
		}

		$backend->set_tab('users','users.php','General','General User Management Options','users');
		if (superuser("user")) $backend->set_tab('wizards','users.php?PARAMETER_SCREEN=wizards','Wizards','General User Management Wizards','graph');

		switch ($_GET['PARAMETER_SCREEN']) {
			case 'wizards' :
				if (superuser('user')) {
					# Don't wanna be commitin' nothin'!
					$backend->set_hidden_field('action','');
					
					$backend->set_active_tab('wizards');

					global $INCLUDE_PATH;
					include_once("$INCLUDE_PATH/wizard.inc");
					$report = new Wizard($this);
					$type_set = $report->type_set();
					if (!$type_set) $backend->print_header();
					$report->print_backend();
					if (!$type_set) $backend->print_footer();
				}

				break;

			default :


			$backend->set_active_tab('users');

			 ##################################################
			# Process the submitted form
			$action = $_REQUEST['action'];
			switch($action) {

			 ###################################
			# Create a new user record ...
			case "New User":
				if (superuser("user")) {
					$new_login = $_POST['new_login'];
					$user = new User(0,$this);
					$backend->add_message($user->create(gpc_stripslashes($new_login)));
					if($user->id > 0) {
						$backend->set_relocation("user.php?userid=$user->id");
					}
				} else {
					$backend->add_message("Permission denied.");
				}
				break;

			 #########################################
			# Searching for a users...
			case "Search Users":
				$user_search = $_REQUEST['user_search'];
				$user_search_lone = $_REQUEST['user_search_lone'];
				$user_search      = gpc_stripslashes($user_search);
				$user_search_lone = gpc_stripslashes($user_search_lone);
				if (superuser("user")) {
					if($user_search_lone) {
						$query = "SELECT user.userid FROM user LEFT JOIN affiliation ON user.userid=affiliation.userid WHERE (login LIKE '".addslashes($user_search)."%' OR email LIKE '".addslashes($user_search)."%' OR firstname LIKE '".addslashes($user_search)."%' OR surname LIKE '".addslashes($user_search)."%' OR concat(firstname,' ',surname) LIKE '".addslashes($user_search)."%') AND organisationid IS NULL ORDER BY surname, firstname LIMIT 2000";
					} else {
						$query = "SELECT userid FROM user WHERE login LIKE '".addslashes($user_search)."%' OR email LIKE '".addslashes($user_search)."%' OR firstname LIKE '".addslashes($user_search)."%' OR surname LIKE '".addslashes($user_search)."%' OR concat(firstname,' ',surname) LIKE '".addslashes($user_search)."%' ORDER BY surname, firstname LIMIT 2000";
					}
				} else {
					$query = "SELECT user.userid FROM user LEFT JOIN affiliation ON user.userid=affiliation.userid WHERE (login LIKE '".addslashes($user_search)."%' OR email LIKE '".addslashes($user_search)."%' OR firstname LIKE '".addslashes($user_search)."%' OR surname LIKE '".addslashes($user_search)."%' OR concat(firstname,' ',surname) LIKE '".addslashes($user_search)."%') AND organisationid IN ('".implode("','",$managed_organisationids)."') ORDER BY surname, firstname LIMIT 2000";
				}

				$user_search_results = $this->db->single_column($query);

				$result_count = count($user_search_results);
				$print_count  = 0;

				$search_results_page = $_REQUEST['search_results_page'];
				if(!$search_results_page) { # They don't need to know this if theyre already browsing results
					if ($result_count <= 0) {
						$backend->add_message("Your search found no users.");
					} elseif ($result_count >= 2000) {
						$backend->add_message("Your search found too many users. Only the top 2000 are displayed.");
					} elseif ($result_count == 1) {
						$userid = $user_search_results[0];
						$user = new User($userid);
						$backend->add_message("User found: ".$user->name());
						$backend->set_relocation("user.php?userid=$userid");
					} else {
						$backend->add_message("Your search found $result_count users.");
					}
				}
				break;

			 #########################################
			# Searching for a users...
			case "Search Locations":
				$search_address = gpc_stripslashes($_POST['search_address']);
				$search_suburb = gpc_stripslashes($_POST['search_suburb']);
				$search_state = gpc_stripslashes($_POST['search_state']);
				$search_postcode = gpc_stripslashes($_POST['search_postcode']);
				$search_countryid = gpc_stripslashes($_POST['search_countryid']);
				$condition
					 = (($search_address)   ? "AND s_address LIKE '%".addslashes($search_address)."%' ":"")
					 . (($search_suburb)    ? "AND s_suburb LIKE '%".addslashes($search_suburb)."%' "  :"")
					 . (($search_state)     ? "AND s_state = '".addslashes($search_state)."' "         :"")
					 . (($search_postcode)  ? "AND s_postcode = '".addslashes($search_postcode)."' "   :"")
					 . (($search_countryid) ? "AND s_countryid = '".addslashes($search_countryid)."' " :"");

				$order_by = "ORDER BY s_countryid, s_state, s_suburb, s_postcode, s_address LIMIT 2000";

				if(superuser('user')) {
					$sql = "SELECT locationid FROM location WHERE 1=1 $condition $order_by";
					$location_search_results = $this->db->single_column($sql);
				} else {
					$extra_condition = "AND organisationid IN ('".implode("','",$managed_organisationids)."') ";
					$sql = "SELECT location.locationid FROM location LEFT JOIN premises ON location.locationid=premises.locationid WHERE 1=1 $condition $extra_condition $order_by";
					$managed_userids = &$this->managed_userids();
					$extra_condition = "AND userid IN ('".implode("','",$managed_userids)."') ";
					$sql2 = "SELECT location.locationid FROM location LEFT JOIN placement ON location.locationid=placement.locationid WHERE 1=1 $condition $extra_condition $order_by";
					$location_search_results = $this->db->single_column($sql);
					$location_search_results += $this->db->single_column($sql2);
				}

				$result_count = count($location_search_results);
				$print_count  = 0;

				$location_search_results_page = $_REQUEST['location_search_results_page'];
				if(!$location_search_results_page) { # They don't need to knwo this if theyre already browsing results
					if ($result_count <= 0) {
						$backend->add_message("Your search found no locations.");
					} elseif ($result_count >= 2000) {
						$backend->add_message("Your search found too many locations. Only the top 2000 are displayed.");
					} elseif ($result_count == 1) {
						$locationid = $location_search_results[0];
						$location = &$this->get_location($locationid);
						$backend->add_message("Location found: ".$location->short_line());
						$backend->set_relocation("location.php?locationid=$locationid");
					} else {
						$backend->add_message("Your search found $result_count locations.");
					}
				}
				break;

			  ##################################
			 # Make a parent-child connection between organisations
			# Check that child isnt a parent of the parent
			case "connect":
				# Only someone with full access of the child and editor access to the parent can move it.
				$parentid = $_REQUEST['parentid'];
				$childid = $_REQUEST['childid'];
				$checkid = $parentid;
				if (in_array($parentid,$managed_organisationids) && in_array($childid,$managed_organisationids)) {
					while($checkid != $childid && ((int)($checkid = $org_tree[$checkid]['parentid']) > 0));
					if ($checkid == $childid) {
						$backend->add_message("You tried to make an organisation the sub-org of one of its suborgs. Which would have been messy.");
					} elseif ($this->db->update("UPDATE organisation SET parentid='$parentid' WHERE organisationid='$childid'")) {
						if ($parentid > 0) { # Organisation parent
							$backend->add_message(ereg_replace("'","\\'",$org_tree[$childid]['name'])." is now a sub-organisation of ".ereg_replace("'","\\'",$org_tree[$parentid]['name']));
						} else { # World as the parent
							$backend->add_message(ereg_replace("'","\\'",$org_tree[$childid]['name'])." is now a one of the global organisations.");
						}
						$this->load_organisation_tree();
					} else {
						$backend->add_message("Organisation could not be moved.");
					}
				} else {
					$backend->add_message("Permission denied.");
				}
				break;

			 ###################################
			# Create a new organisation record ...
			case "New Organisation":
				if (superuser('user')) {
					$new_org_name = gpc_stripslashes($_POST['new_org_name']);
					$new_org = new Organisation();
					$new_organisationid = $new_org->create($new_org_name,0);
					if ($new_organisationid) {
						$backend->add_message("New global organisation created.");
						$backend->set_relocation("organisation.php?organisationid=$new_organisationid");
					} else {
						$backend->add_message("Unable to create new organisation.");
					}
				} else {
					$backend->add_message("Permission denied.");
				}
				break;
			}


				#################################################
			   # Convert the open pages string into an array.
			  # Store this in session so that pages remain open
			 # even if a user leaves and comes back.
			#####################################################
			$session_open_orgs = $session->get_var("session_open_orgs");
			$open_organisationid = $_REQUEST['open_organisationid'];
			$close_organisationid = $_REQUEST['close_organisationid'];
			if($open_organisationid)   $session_open_orgs[] = $open_organisationid;
			if($close_organisationid) {
				reset($session_open_orgs);
				while(list($id,$orgid) = each($session_open_orgs)) {
					if($close_organisationid == $orgid) {
						unset($session_open_orgs[$id]);
					}
				}
			}
			if (!empty($session_open_orgs)) {
				reset($session_open_orgs);
				while(list($id,$orgid) = each($session_open_orgs)) {
					$this->organisation_tree[$orgid]['open'] = true;
				}
			}

			$js = "
				function open_org(organisationid) {
					document.edit.open_organisationid.value = organisationid;
					document.edit.active_organisationid.value = organisationid;
					document.edit.submit();
				}

				function close_org(organisationid) {
					document.edit.close_organisationid.value = organisationid;
					document.edit.active_organisationid.value = organisationid;
					document.edit.submit();
				}

				function set_connect_child(id,name) {
					connect_child = id;
					connect_child_name = name;
				}

				var connect_child = false;
				";

			$backend->set_javascript($js);

			$backend->set_hidden_field("action");
			$backend->set_hidden_field("userid");
			$backend->set_hidden_field("organisationid");
			$backend->set_hidden_field("locationid");
			$backend->set_hidden_field("childid");
			$backend->set_hidden_field("parentid");
			$backend->set_hidden_field("active_organisationid");
			$backend->set_hidden_field("open_organisationid");
			$backend->set_hidden_field("close_organisationid");

			$backend->print_header();

			$backend->open_section("Users","user");

			if ($user_search_results) {
				$backend->open_field("Search Results (".count($user_search_results).")","one_liner");
				echo js("document.edit.action.value='Search Users';");
				$this->display_users_table($user_search_results,array(),array(),"search_results_page");
			}

			$backend->open_field("Find:<span class=backend_fineprint><br>(login/email/name)</span>",'one_liner');
			echo text_box("user_search",$user_search,15,64, "class=data");
			$backend->print_icon_button("javascript: document.edit.action.value='Search Users';document.edit.submit();","Find User","find");
			if(superuser("user")) {
				echo "<br><input type=checkbox name=user_search_lone value=1> <span class=smallprint>Only unaffiliated users</span>";
			}


			if (superuser("user")) {
				$backend->open_field("Create New User:<span class=fineprint><br>(login)</span>",'one_liner');
				echo text_box("new_login","",15,64, "class=data");
				$backend->print_icon_button("javascript: if (document.edit.new_login.value.length > 1) {document.edit.action.value='New User';document.edit.submit();}","Create New User","create");
			}

			$backend->open_section("Organisations/Divisions/Categories","organisation");

			$backend->open_field("Tree","top");

			 ##############################################
			# Figure out the top-most managed organisation
			if (!empty($managed_organisationids)) {
				foreach($managed_organisationids as $id) {
					if (!in_array(($pid = $org_tree[$id]['parentid']),$managed_organisationids)) {
						$session_open_orgs[] = $pid;
						$org_tree[$pid]['open'] = true;
						while((int)($pid = $org_tree[$pid]['parentid'])) {
							$session_open_orgs[] = $pid;
							$org_tree[$pid]['open'] = true;
						}
					}
				}
			}

			$session->set_var('session_open_orgs', $session_open_orgs);

			?>
			<table cellpadding=0 cellspacing=0 border=0>
				<tr>
					<td><?
						$backend->print_icon_button(((superuser("user"))?"javascript:if (connect_child.length > 0) {document.edit.action.value='connect';document.edit.childid.value=connect_child;document.edit.parentid.value='0';document.edit.submit()}":""),"Make this a global organisation.","create");
						?>
					</td>
					<td><p class=data><b>World</td>
				</tr>
			</table>
			<table cellpadding=0 cellspacing=0 border=0>
			<?
			$orgs = $org_tree["0"]['childids'];
			$this->print_organisation_hierarchy($orgs,"",0,$managed_organisationids);
			?></table><?


			if(superuser("user")) {
				$backend->open_field("New Global:<span class=fineprint><br>(organisation name)");

				echo text_box("new_org_name","",25,128, "class=data");
				$backend->print_icon_button("javascript: if (document.edit.new_org_name.value.length > 1) {document.edit.action.value='New Organisation';document.edit.submit();}","Create New Global Organisation","create");
			}


			$backend->open_section("Locations","location");

			if ($location_search_results) {
				$backend->open_field("Search Results (".count($location_search_results).")","top");
				echo js("document.edit.action.value='Search Locations';");
				$this->print_location_table($location_search_results,"location_search_results_page");
			}

			$backend->open_field("Find:","top");
			echo text_area(search_address,$search_address,35,2,255,"class=data")."<br>";
			echo "Suburb/Town:".text_box(search_suburb,$search_suburb,25,128,"class=data")."<br>";
			echo "State/Province:".text_box(search_state,$search_state,5,128,"class=data");
			echo " Post/Zip Code:".text_box(search_postcode,$search_postcode,5,10,"class=data")."<br>";
			$countries_config = &get_config("Countries");
			echo combo_box(search_countryid,array_merge(array(""=>"--Any Country--"),$countries_config->countries),$search_countryid,"class=data",35);
			$backend->print_icon_button("javascript: document.edit.action.value='Search Locations';document.edit.submit();","Find Location","find");

			$backend->print_footer();
		}

	}


	 #####################################################################
	# Prints a hyperlkinked ancestry of a certain organisation in a tree
	function print_organisation_lineage($id=0) {
		$org_tree = &$this->get_organisation_tree();
		$parentid = $org_tree[$id]['parentid'];
		if ($parentid > 0) {
			$result = $this->print_organisation_lineage($parentid);
			return "$result : <a href=\"organisation.php?organisationid=$parentid\">".$org_tree[$parentid]['name']."</a>";
		} else {
			return "World";
		}
	}


	  ################################################################
	 # Prints a hierarchy table of all the organisations in the USER_DB
	#################################################################

	function print_organisation_hierarchy(&$orgs,$depth=array(),$static='',$linkables='') {

		$org_tree = &$this->get_organisation_tree();

		$depth[] = true; # The indenting level, stalk on/off
		if (!empty($orgs)) {
			reset($orgs);
			for($orgid = reset($orgs); $orgid; $orgid = next($orgs)) {
				if (!next($orgs)) $end = true;
				else $end = false;
				prev($orgs);

				$org      = $org_tree[$orgid];
				$haskids  = isset($org['childids']) && count($org['childids']);
				$open     = $org['open'];
				$editable = (!$linkables || in_array($org['organisationid'],$linkables));
				$openable = ($editable || count(array_intersect($this->organisation_descendantids($orgid),$linkables)) > 0);
				if(!$editable && !$openable) continue;
				?>
				<a name=organisation<? echo $orgid ?>>
				<tr><td>
				<table cellpadding=0 cellspacing=0 border=0>
					<tr>
					<?
					for ($i = 0; $i < count($depth); $i++) {
						?><td valign=top <? if ($depth[$i] && !($end && ($i == count($depth)-1))) echo "background=\"images/tree/stalk.gif\"" ?> ><?
						if ($i != count($depth) - 1) {
							?><img src="images/blank.gif" height=1 width=20><?
						} else {
							if ($haskids && !$static && $editable) {
								?><a href="javascript:<? echo (($open) ? "close_org('$org[organisationid]')" : "open_org('$org[organisationid]')") ?>;" onmouseover="window.status='<? echo (($open) ? "Hide sub-organisations" : "Show sub-organisations") ?>'; return true;" onmouseout="window.status='';return true;"><img src="images/tree/branch_<? echo (($open)?"open":"closed")?>.gif" alt="<? echo (($open)?"Hide":"Show")?> sub-organisations" height=20 width=20 border=0></a><?
							} else {
								?><img src="images/tree/branch.gif" height=20 width=20><?
							}
						}
						?></td><?
					}

					?>
						<td valign=bottom><? if (!$static && $editable) { ?><a href="javascript:if (connect_child.length > 0) {document.edit.action.value='connect';document.edit.childid.value=connect_child;document.edit.parentid.value='<?echo $org['organisationid'] ?>';document.edit.submit()} else {set_connect_child( ((confirm('To connect this organisation to a new parent, click the icon of another organistion you wish <? echo addslashes($org['name']) ?> page to be a sub-organisation of.'))?'<? echo $org['organisationid'] ?>':false),'<? echo addslashes($org['name']) ?>');}" onmouseover="if (connect_child.length > 0) {window.status='Make this organisation the parent of the \''+connect_child_name+'\'.';} else{window.status='Connect this organisation to a different parent.';} return true;" onmouseout="window.status='';return true;"><? } ?><img src="images/icons/organisation.gif" width=20 height=20 border=0></a><img src="images/blank.gif" width=5 height=20></td>
						<td class=data><? if ($editable) {?><a href="organisation.php?organisationid=<? echo $org['organisationid'] ?>" onmouseover="window.status='<? echo ereg_replace("[\n\r]+"," ",ereg_replace("'","\\'",htmlspecialchars($org['description']))) ?>'; return true;" onmouseout="window.status=''; return true;"><?}?><? echo $org['name'] ?></a></td>
					</tr>
				</table>
				</td></tr>
				<?
				if (isset($org['childids']) && count($org['childids']) > 0 && ($open || $static)) {
					if ($end) $depth[count($depth)-1] = false;
					$this->print_organisation_hierarchy($org['childids'], $depth, $static,$linkables);
				}
			}
		}
	}

	  ################################################################
	 # Prints a hierarchy table of all the locations in the USER_DB
	#################################################################

	function print_location_table(&$locids, $page_counter_name='') {
		$countries_config = &get_config("Countries");
		$page = $_REQUEST[$page_counter_name];

		if(empty($locids)) {
			echo "None.";
			return;
		}

		# Do we want to limit number per page
		if($page_counter_name) {
			echo "<input type=hidden name=$page_counter_name value=$page>";
			if (!$page) $page = 1;
			$user_count  = count($locids);
			$per_page    = 10;
			$page_count  = ceil($user_count / $per_page);
			if ($page > $page_count) $page = $page_count;
			$offset      = ($page - 1) * $per_page;
		} else {
			$offset = 0;
		}

		echo("<table cellspacing=2 cellpadding=2 border=0 width=100%>");
		if (!empty($locids)) {
			# Make sure we have a numeric array
			$locids = array_values($locids);
			while($locid = $locids[($offset + $offset_counter++)]) {
				if($page_counter_name && $offset_counter > $per_page) break;
				$location = &$this->get_location($locid);
				echo ("<tr bgcolor=#d0d0d0><td class=data><a href=\"location.php?locationid=$location->id\">$location->s_address</a></td><td class=data>$location->s_suburb</td><td class=data>$location->s_state</td><td class=data>{$countries_config->countries[$location->s_countryid]}</td><td class=data>$location->s_postcode</td></tr>");
			}
		}

		if ($page_count > 1) {
		?>
		<script language=javascript>
			function jump_page_<?=$page_counter_name?>(p) {
				document.edit.<? echo $page_counter_name ?>.value = p;
				document.edit.submit();
			}
		</script>
		<tr>
			<td colspan=1 align=left><b>
			<? if ($page > 1) { ?>
				<a href="javascript:jump_page_<?=$page_counter_name?>(<?=($page-1)?>);" onmouseover="javascript: window.status='View the previous page of users'; return true;" onmouseout="javascript: window.status=''; return true;">&lt;&lt; Prev</a>
			<? } else { ?>
				&lt;&lt; Prev
			<? }#end if ?>
			</td>
			<td colspan=3 align=center><font face=arial size=2><? echo("Page $page of $page_count") ?></td>
			<td colspan=1 align=right><font face=arial size=2><b>
			<? if ($page != $page_count) { ?>
				<a href="javascript:jump_page_<?=$page_counter_name?>(<?=($page+1)?>);" onmouseover="javascript: window.status='View the next page of users'; return true;" onmouseout="javascript: window.status=''; return true;">Next &gt;&gt;</a>
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
					if ($i == $page) {
					?>
						<span style="color:red;"><?=$num?></span>
					<?
					} else {
					?>
						<a href="javascript:jump_page_<?=$page_counter_name?>(<?=$i?>)" onmouseover="javascript: window.status='Jump to page <?=$i?> of the users'; return true;" onmouseout="javascript: window.status=''; return true;"><?=$num?></a>
					<?
					}
				}

				?>
			</td>
		</tr>
		<?
		} # End if page_count
		echo ("</table>");
	}



	 #####################################################################
	# Returns an array of organisationid->name, with name preceded by tree sticks
	function organisation_array_with_sticks($organisationid=0,$stix='') {

		$org_tree = &$this->get_organisation_tree();

		if (empty($org_tree)) return array();

		if (!$organisationid) $organisationid = '0';

		$childids  = $org_tree[$organisationid]['childids'];

		if (!empty($childids)) {
			$countdown = count($childids);
			foreach($childids as $childid) {
				$countdown--;
				$result[$childid] = $stix.(($countdown)?"|-":"`-").$org_tree[$childid]['name'];
				if(count($org_tree[$childid]['childids'])) {
					$newstix = $stix.(($countdown)?"|&nbsp;":"&nbsp;&nbsp;");
					$result = $result + $this->organisation_array_with_sticks($childid,$newstix);
				}
				if($page_counter_name) if(++$counter == $per_page) break;
			}
		}

		return $result;
	}


	    #######################################################################################
	   # Function which takes a reference to an array of users and prints out a list of them #
	  # If a checkbox name is given then this delete checkbox is printed				    #
	 # user page name is used to allow multiple pages with 'prev' and 'next' button        #
	#######################################################################################
	function display_users_table($userids='', $checkbox_names='', $checkboxes='', $page_counter_name='', $organisationid='', $extra_fields=array(), $target='document') {
		$page = $_REQUEST[$page_counter_name];

		if(empty($userids)) {
			echo "None.";
			return;
		}

		# Do we want to limit number per page
		if($page_counter_name) {
			echo "<input type=hidden name=$page_counter_name value=$page>";
			if (!$page) $page = 1;
			$user_count  = count($userids);
			$per_page    = 20;
			$page_count  = ceil($user_count / $per_page);
			if ($page > $page_count) $page = $page_count;
			$offset      = ($page - 1) * $per_page;
		} else {
			$offset = 0;
		}

		/*
		  both $checkbox_names and $checkboxes are now arrays in case of extra parameters. If you don't
		  know what I mean, you don't need to worry.
		*/
		?>
		<table cellpadding=2 cellspacing=1 border=0 width="100%">
			<tr>
				<td><p class="field">ID</td>
				<td><p class="field">Login</td>
				<td><p class="field">Name</td>
				<td><p class="field">Email</td>
				<td><p class="field" align="center">Account Status</td>
				<?
				if ($organisationid) echo "<td><p class=field>Title</td>";
				if (!empty($extra_fields)) {
					foreach($extra_fields as $field_name => $title) {
						echo "<td><p class=field>$title</td>";
					}
				}
				if (is_array($checkboxes)) {
					foreach($checkboxes as $name) {
						?><td><p class=field><? echo $name ?></td><?
					}
				} elseif($checkbox_names) {
					?><td><p class=field><? echo (($checkboxes)?$checkboxes:'Delete?'); ?></td><?
				}
				?>
			</tr>
			<?
			if (!empty($userids)) {
				# Make sure we have a numeric array
				$userids = array_values($userids);
				while($userid = $userids[($offset + $offset_counter++)]) {
					if($page_counter_name && $offset_counter > $per_page) break;
					$user = $this->db->single_row("SELECT * from user u WHERE u.userid = '$userid'");
					if($organisationid) $org_title = $this->db->single_element("SELECT title FROM affiliation WHERE userid='$userid' AND organisationid='$organisationid'");
					?>
					<tr bgcolor=#d0d0d0>
						<td align="right"><p class=data><?=$user['userid']?></td>
						<td><p class=data><?=$user['login']?></td>
						<td><p class=data><a onmouseover="window.status='View <?=addslashes($user['firstname'])?>\'s information.';return 	true;" onmouseout="window.status='';return true;" onclick="<?=$target?>.location='user.php?userid=<?=$user['userid']?>';return false;" href="#"><?=trim($user['firstname'])?> <?=((trim($user['surname'])) ? trim($user['surname']) : "")?></a></td>
						<td><p class=data><? if($user['email']) {?><a onmouseover="window.status='Send <?=addslashes($user['firstname'])?> an email.';return true;" onmouseout="window.status='';return true;" href="mailto:<?=$user['firstname']?> <<?=$user['email']?>>"><?=$user['email']?></a><? } ?></td>
						<td align="center"><p class=data>
						<?
							if ($userid == 1) {
								# If this is for root, display an ego boosting comment :)
								echo 'Ever Present';
							} else {
								switch ($user['web_status']) {
									case 'A' : echo 'Active'; break;
									case 'E' : echo 'Expired'; break;
									case 'L' : echo 'Locked'; break;
									case 'P' : echo 'Pending Approval'; break;
									default : echo '&nbsp;'; break;
								}
							}
						?>
						</td>
						<?
						if ($organisationid) echo "<td><p class=data>".$org_title."</td>";
						if (!empty($extra_fields)) {
							foreach($extra_fields as $field_name => $title) {
								if (trim($user[$field_name]) == '' || $user[$field_name] == '0000-00-00') $user[$field_name] = '&nbsp;';
								echo '<td><p class=data>'.$user[$field_name].'</td>';
							}
						}
						if (is_array($checkbox_names)) {
							while(list($name, $validids) = each ($checkbox_names)) {
								?>
								<td align="center">
									<input type=checkbox name="<?=$name?>[]" value="<?=$userid?>" <? echo ((in_array($userid, $validids))?"checked":"") ?>>
								</td>
								<?
							}
							reset($checkbox_names);
						}
						elseif($checkbox_names) {
								?>
								<td align="center">
									<input type=checkbox name="<?=$checkbox_names?>[]" value="<?=$userid?>">
								</td>
								<?
						} #end if write access
						?>
					</tr>
					<?
				}#end foreach users
			}
			if ($page_count > 1) {
			?>
			<script language=javascript>
				function jump_page_<?=$page_counter_name?>(p) {
					document.edit.<? echo $page_counter_name ?>.value = p;
					document.edit.submit();
				}
			</script>
			<tr>
				<td colspan=1 align=left><b>
				<? if ($page > 1) { ?>
					<a href="javascript:jump_page_<?=$page_counter_name?>(<?=($page-1)?>);" onmouseover="javascript: window.status='View the previous page of users'; return true;" onmouseout="javascript: window.status=''; return true;">&lt;&lt; Prev</a>
				<? } else { ?>
					&lt;&lt; Prev
				<? }#end if ?>
				</td>
				<td colspan=3 align=center><font face=arial size=2><? echo("Page $page of $page_count") ?></td>
				<td colspan=1 align=right><font face=arial size=2><b>
				<? if ($page != $page_count) { ?>
					<a href="javascript:jump_page_<?=$page_counter_name?>(<?=($page+1)?>);" onmouseover="javascript: window.status='View the next page of users'; return true;" onmouseout="javascript: window.status=''; return true;">Next &gt;&gt;</a>
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
						if ($i == $page) {
						?>
							<span style="color:red;"><?=$num?></span>
						<?
						} else {
						?>
							<a href="javascript:jump_page_<?=$page_counter_name?>(<?=$i?>)" onmouseover="javascript: window.status='Jump to page <?=$i?> of the users'; return true;" onmouseout="javascript: window.status=''; return true;"><?=$num?></a>
						<?
						}
					}

					?>
				</td>
			</tr>
			<?
			} # End if page_count
		?>
		</table>
		<?
	}# end method

	 ###################################################
	# Print out a list of organisations in a nice table
	function display_orgs_table($orgids='',$checkbox_names='', $checkboxes='') {
		if(empty($orgids)) {
			echo "None.";
			return;
		}
		?>
		<table cellpadding=2 cellspacing=1 border=0>
			<tr>
				<td><p class="field">ID</td>
				<td><p class="field">Name</td>
				<?
				if (is_array($checkboxes)) {
					foreach($checkboxes as $name) {
						?><td><p class=field><? echo $name ?></td><?
					}
				} elseif($checkbox_names) {
					?><td><p class=field><? echo (($checkboxes)?$checkboxes:'Delete?') ?></td><?
				}
				?>
			</tr>
			<?
			foreach($orgids as $orgid) {
				$org = &$this->get_organisation($orgid);
				?>
				<tr bgcolor=#d0d0d0>
					<td align=right><?=$org->id;?></td>
					<td><a onmouseover="window.status='View <?=addslashes($org->name)?> information.';return true;" onmouseout="window.status='';return true;" href="organisation.php?organisationid=<?=$org->id?>"><?=$org->name?></a></td>
					<?
					if (is_array($checkbox_names)) {
						while(list($name, $validids) = each ($checkbox_names)) {
							?>
							<td align="center">
								<input type=checkbox name="<?=$name?>[]" value="<?=$org->id?>" <? echo ((in_array($org->id, $validids))?"checked":"") ?>>
							</td>
							<?
						}
						reset($checkbox_names);
					}
					elseif($checkbox_names) {
						?>
						<td align="center">
							<input type=checkbox name="<?=$checkbox_names?>[]" value="<?=$org->id?>">
						</td>
						<?
					}
					?>
				</tr>
				<?
			}
			?>
			</table>
			<?
		}


	 #################################
	# Exports a file in csv format
	function csv_export($userids=array(),$show_field_headers='',&$other_fields,&$form_fields,&$form) {
		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/csv/csv.inc");

		$db = &$this->get_db();

		$csv = new Csv();
		$sql = "SELECT u.*, a.organisationid, a.title, a.manager, a.answers FROM user AS u LEFT JOIN affiliation AS a ON u.userid=a.userid".((count($userids)) ? " WHERE u.userid IN (".implode(', ',$userids).')' : '');
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
	function xml_export($userids=array(),&$other_fields,&$form_fields,&$form) {
		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/xml/xml.inc");

		$db = &$this->get_db();

		$xml = new Xml();
		$sql = "SELECT u.*, a.organisationid, a.title, a.manager, a.answers FROM user AS u LEFT JOIN affiliation AS a ON u.userid=a.userid".((count($userids)) ? " WHERE u.userid IN (".implode(', ',$userids).')' : '');
		$export_data = $db->associative_array($sql);
		$xml->tag_open('userdata');
		if (!empty($export_data)) {
			while(list($userid,$data) = each($export_data)) {
				$answers = unserialize($data['answers']);
				$xml->tag_open('user');
				if (!empty($other_fields)) {
					foreach($other_fields as $key => $on) {
						if($on) {
							$xml->tag_open(ereg_replace(' ','_',$key));
							$xml->add_data($data[$key]);
							$xml->tag_close();
						}
					}
				}
				if (!empty($form_fields)) {
				reset($form_fields);
					while(list($question_var_prefix,$on) = each($form_fields)) {
						if($on) {
							$sectionid = substr($question_var_prefix,8,strpos($question_var_prefix,'question_')-9);
							$questionid = substr($question_var_prefix,strpos($question_var_prefix,'question_')+9);
							$xml->tag_open(ereg_replace(' ','_',$form->sections[$sectionid]['questions'][$questionid]->title));
							$xml->add_data($answers[$sectionid][$questionid]['value']);
							$xml->tag_close();
						}
					}
				}
				$xml->tag_close();
			}
		}
		$xml->tag_close();
		$xml->set_filename('user_export_'.date('Y-m-d').'.xml');
		$xml->export();
	}

}

?>

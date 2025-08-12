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
## File: include/user.inc
## Desc: An individual user
## $Source: /home/cvsroot/mysource/include/user.inc,v $
## $Revision: 2.47 $
## $Author: achadszinow $
## $Date: 2004/03/16 23:31:42 $
#######################################################################
#---------------------------------------------------------------------#

/**
* The base class for manipulating a user
*
* @access  public
* @package User
*/

class User extends UsersObject {

	# Static
	var $web_statii = array("A" => "Active", "L" => "Locked", "E" => "Account Expired", "P" => "Pending Approval");

	var $data_path		= ""; # Path to a directory where a user's stuff can be stored

	var $id				= "";
	var $login			= "";
	var $password		= ""; # Encrypted
	var $firstname		= "";
	var $surname		= "";
	var $email			= "";
	var $mobile_no		= "";
	var $created_date	= "0000-00-00";
	var $expiry_date	= "0000-00-00";
	var $web_status		= "L";
	var $comments		= "";

	var $affiliations	= array();	# An array of links to organisations
	var $placements		= array();	# An array of links to locations
	var $access_groups	= array();	# An array of links to access groups


	/**
	* Constructor
	*
	* @param   int $userid A userid to load
	* @returns object User
	* @access  public
	*/
	function User($userid=0) {
		UsersObject::UsersObject();
		if ($userid) {
			return $this->load($userid);
		}
	}

	/**
	* Create a new user with only a userid at this stage - This function can be called before
	* create to create the userid first if the userid is needed before the user can be fully created
	* Obviously the user should be deleted if it it never fully created.
	*
	* @returns void
	* @access  public
	*/
	function create_userid() {
		$db = &$this->users_system->db;
		$this->id = $db->insert("INSERT INTO user () VALUES ()");
		return $this->id;
	}


	/**
	* Create a new user and then load it in to ourselves.
	*
	* @param   string $login The login to create
	* @param   int $organisationid Whether they are part of an organisation
	* @param   string $title The users' title in the organisation
	* @param   boolean $blank_password Whether to create the user with a blank password or not.
	* @returns string Message
	* @access  public
	*/
	function create($login='',$organisationid=0, $title='', $blank_password=false) {
		$db = &$this->users_system->db;

		if ($error_msg = $this->validate_login($login)) {
			if ($this->id > 0) {
				$this->delete();
			}
			return $error_msg;
		} else {
			if(!$blank_password) {
				$password = random_password(8);
			} else {
				$password = '';
			}
			if ($this->id < 1 ) {
				$userid = $db->insert("INSERT INTO user (login,password,firstname,web_status,created_date) VALUES ('".addslashes($login)."',password('".addslashes($password)."'),'".addslashes($login)."','A',now())");
			} else {
				$db->update("UPDATE user set login='".addslashes($login)."', password=password('".addslashes($password)."'), firstname='".addslashes($login)."', web_status='A', created_date=now() where userid=$this->id");
			}
			$message = "New user \"$login\" created.\n\nIMPORTANT\nNew Password is:  $password\n\nThis can, however, be changed.\n\nMake sure to set the user's correct email address.";
			$this->load($userid);

			if ($organisationid && $title) {
				$message .= "\n\n".$this->add_affiliation($organisationid,$title);
			}
			return $message;
		}
	}


	/**
	* Load a user based on this id.
	*
	* @param   int $userid The userid to load
	* @returns int userid
	* @access  public
	*/
	function load($userid=0) {

		if ($userid <= 0 && !($userid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load user information without a valid userid.",__FILE__,__LINE__);
			$this = new User();
			return false;
		}

		if ($this->load_from_cache($userid)) {
			$this->data_path = get_data_path(true, "user/$this->id");
			return $userid;
		}

		$query = "SELECT userid,login,password,firstname,surname,email,mobile_no,created_date,expiry_date,web_status,comments FROM user WHERE userid='$userid'";
		list(
			$this->id,
			$this->login,
			$this->password,
			$this->firstname,
			$this->surname,
			$this->email,
			$this->mobile_no,
			$this->created_date,
			$this->expiry_date,
			$this->web_status,
			$this->comments
			) = $this->users_system->db->single_row($query);

		if (!$this->id) { # EEk! Clear.
			$this = new User();
			return false;
		}

		/*
			if we're the logged in person, reset *some* of the session variables.
			bulkmail and other user extensions use the session variables,
			but if we've just changed our details they still have the old ones!
			only these are being reset because
				(a) changing password will log you out :P
				(b) other extensions might put extra info in here somewhere
		*/

		$session = &get_mysource_session();
		if ($this->id == $session->user->id) {
			$session->user->firstname = $this->firstname;
			$session->user->surname = $this->surname;
			$session->user->email = $this->email;
			$session->user->comments = $this->comments;
		}

		 ##############################
		# Remember where we put things
		$this->data_path = get_data_path(true, "user/$this->id");

		$query = "SELECT a.organisationid, a.title, a.answers FROM affiliation a, organisation o LEFT JOIN organisation o2 ON o.parentid=o2.organisationid WHERE o.organisationid=a.organisationid AND a.userid='$userid' ORDER BY o.name, o2.name";

		$this->affiliations = $this->users_system->db->associative_array($query);

		$query = "SELECT wa.locationid, concat(l.s_address,'\n',l.s_suburb,' ',l.s_state,' ',l.s_postcode) AS address, l.s_countryid as countryid, wa.direct_phone, wa.direct_fax FROM placement wa, location l WHERE l.locationid=wa.locationid AND wa.userid='$userid' ORDER BY l.s_countryid,l.s_state";

		$this->placements = $this->users_system->db->associative_array($query);

		 ############################
		# Save to the cache
		$this->save_to_cache($userid);
		return $userid;
	}


	/**
	* Takes a login and password, does a verification, and loads the user if successful
	*
	* @param   string $login The login to check
	* @param   string $password The password the user typed in
	* @returns boolean
	* @access  public
	*/
	function load_with_login($login='', $password='') {
		if($login == 'root') {
			$v = explode('.', $this->users_system->db->server_version());
			if ($v[0] == '4') {
				$qry = "SELECT userid, password FROM user WHERE login='root' AND password=OLD_PASSWORD('".addslashes($password)."')";
			} else {
				$qry = "SELECT userid, password FROM user WHERE login='root' AND password=PASSWORD('".addslashes($password)."')";
			}
			$userid = $this->users_system->db->single_element($qry);
		} else {
			$authentication = &$this->users_system->get_authentication();
			$userid = $authentication->get_userid($login,$password);
		}

		if ($userid <= 0 || !$this->load($userid)) {
			return false;
		} else if (!user_root($this->id) && $this->web_status != 'A') {
			return false;
		} else if (!user_root($this->id) && ($this->expiry_date != '0000-00-00' && $this->expiry_date < date("Y-m-d"))) {
			return false;
		} else {
			return true;
		}
	}


	/**
	* Does the currently logged in user have write access?
	*
	* @returns boolean
	* @access  public
	*/
	function write_access() {
		$managed_organisationids = &$this->users_system->get_managed_organisationids();

		$manager = count(
					array_intersect(
						array_keys($this->affiliations),
						$managed_organisationids
					)
				);

		return (superuser("user") || $manager);
	}


	/**
	* May the currently logged in user delete this user?
	* Only if they manage all the organisations they belong to
	*
	* @returns boolean
	* @access  public
	*/
	function delete_access() {
		$session = &get_mysource_session();
		if($session->user->id == $this->id) return false;
		if($this->id == 1) return false;
		if (superuser('user')) return true;
		if (!empty($this->affiliations)) { # You'd better manage all their affiliations
			$managed_organisationids = &$this->users_system->get_managed_organisationids();
			foreach($this->affiliations as $orgid => $data) {
				if (!in_array($orgid,$managed_organisationids)) return false; # Fail
			}
			return true; # Pass
		}
		return false; # A lone user - no affiliations !  you have to be a superuser
	}


	/**
	* Set the status of the current user
	*
	* @param   string $web_status The new web status of the user
	* @returns string Message about success
	* @access  public
	*/
	function set_web_status($web_status='') {
		if($web_status == $this->web_status) return '';
		if ($this->id == 1 && $web_status != "A") return false;
		if(!ereg("[ALEP]",$web_status)) {
			return "Web Status may only be either A, L, or E.";
		}
		# if account has been unlocked send an email to admin and to the user informing them of this
		/*
		if($this->web_status=='L' && $web_status =='A') {
			$system_config = &get_system_config();
			$string = "The web account '$this->login' and (ID: $this->id) has been unlocked.";
			$string2 = "The System Administrator has UNLOCKED your web account.";
			mail($system_config->webmaster_email, "$system_config->system_name web account $this->login unlocked", $string,  "From:$system_config->webmaster_email");
			if($email) {
				mail($email, "$system_config->system_name web account $this->login unlocked", $string2, "From: $system_config->webmaster_email");
			}
		}
		*/
		$this->web_status = $web_status;
		$this->users_system->db->update("UPDATE user SET web_status='$this->web_status' WHERE userid='$this->id'");
		$this->clear_cache($this->id);
		return "Web Status set to {$this->web_statii[$web_status]}.";
	}


	/**
	* Change the expiry date of this user
	*
	* @param   string $expiry_date The new expiry date of the user
	* @returns string Message about success
	* @access  public
	*/
	function set_expiry_date($expiry_date='') {
		if ($this->id == 1) return false;
		if ($expiry_date == $this->expiry_date) return '';
		$now = date("Y-m-d");
		if($now >= $expiry_date && $expiry_date != '0000-00-00' && $this->web_status == 'A') {
			$system_config = &get_system_config();
			$message = $this->set_web_status("E")."\n";
			$string = "Your $system_config->system_name web account with login $this->login has expired. Please reply to this email if there is any reason this account must stay active.";
			mail($this->email, "$system_config->system_name web account $login expired", $string, "From: $system_config->webmaster_email");
		} else if($this->web_status =='E' && ($now < $expiry_date || $expiry_date=='0000-00-00')) {
			$expiry_date = $now;
		}
		$this->expiry_date = $expiry_date;
		$this->users_system->db->update("UPDATE user SET expiry_date='$this->expiry_date' WHERE userid='$this->id'");
		$this->clear_cache($this->id);
		if($expiry_date == "0000-00-00") $expiry_date = "never";
		return $message."Expiry date set to $expiry_date";
	}


	/**
	* Change the name of the user
	*
	* @param   string $firstname The new firstname of the user
	* @param   string $surname The new surname of the user
	* @returns string Message about success
	* @access  public
	*/
	function set_name($firstname='', $surname='') {
		if ($firstname == $this->firstname && $surname == $this->surname) return '';
		if ($firstname == '' && $surname == '') $firstname = $this->login;
		$this->firstname = $firstname;
		$this->surname = $surname;
		$this->users_system->db->update("UPDATE user SET firstname='".addslashes($this->firstname)."', surname='".addslashes($this->surname)."' WHERE userid='$this->id'");
		$this->clear_cache($this->id);
		return "Name changed to ".$this->name();
	}

	/**
	* Change the firstname of the user only
	*
	* @param   string $firstname The new firstname of the user
	* @returns string Message about success
	* @access  public
	*/
	function set_firstname($firstname='') {
		if ($firstname == $this->firstname) return '';
		$this->firstname = $firstname;
		$this->users_system->db->update("UPDATE user SET firstname='".addslashes($this->firstname)."' WHERE userid='$this->id'");
		$this->clear_cache($this->id);
		return 'Firstname changed to '.$this->name();
	}

	/**
	* Change the surname of the user only
	*
	* @param   string $surname The new surname of the user
	* @returns string Message about success
	* @access  public
	*/
	function set_surname($surname='') {
		if ($surname == $this->surname) return '';
		$this->surname = $surname;
		$this->users_system->db->update("UPDATE user SET surname='".addslashes($this->surname)."' WHERE userid='$this->id'");
		$this->clear_cache($this->id);
		return 'Surname changed to '.$this->name();
	}

	/**
	* Change the mobile number of the user
	*
	* @param   string $mobile_no The new mobile number of the user
	* @returns string Message about success
	* @access  public
	*/
	function set_mobile_no($mobile_no='') {
		if ($mobile_no == $this->mobile_no) return '';
		$this->mobile_no = $mobile_no;
		$this->users_system->db->update("UPDATE user SET mobile_no='".addslashes($this->mobile_no)."' WHERE userid='$this->id'");
		$this->clear_cache($this->id);
		return "Mobile no changed to $mobile_no";
	}


	/**
	* Change the login of the user
	* Also checks their super-user access and corrects that if necessary
	*
	* @param   string $login The new login of the user
	* @returns string Message about success
	* @access  public
	*/
	function set_login($login='') {
		$login = strtolower(eregi_replace("[^a-z0-9\_]+",'',$login));
		if ($login == $this->login) return '';
		if($this->id == 1) return "Cannot change root's login.";
		if(strlen($login) < 2)  return "Login too short, remains unchanged.";
		if(strlen($login) > 64) return "Login too long, remains unchanged.";
		if ($name = $this->users_system->db->single_element("SELECT concat(firstname,' ',surname) FROM user WHERE login='".addslashes($login)."'")) {
			return "Somebody already has this login, login remains unchanged.";
		}
		# if the user is a superuser, web master or user master, we need to remove their
		# old login and add there new one in
		$system_config = &get_system_config();
		$superuser = false;
		$superuser_web = false;
		$superuser_user = false;

		if (in_array($this->login,$system_config->superusers['all'])) {
			$superuser = true;
			$system_config->delete_superuser($this->login);
		}

		if (in_array($this->login,$system_config->superusers['web'])) {
			$superuser_web = true;
			$system_config->delete_web_master($this->login);
		}

		if (in_array($this->login,$system_config->superusers['user'])) {
			$superuser_user = true;
			$system_config->delete_user_master($this->login);
		}

		$this->login = $login;
		$this->users_system->db->update("UPDATE user SET login='".addslashes($this->login)."' WHERE userid='$this->id'");
		$this->clear_cache($this->id);

		if ($superuser) $system_config->add_superuser($this->login);
		if ($superuser_web) $system_config->add_web_master($this->login);
		if ($superuser_user) $system_config->add_user_master($this->login);

		$system_config->conf_updated();
		return "Login changed to '$login'.";
	}


	/**
	* Change the email address of the user.
	*
	* @param   string $email The new email address of the user
	* @returns string Message about success
	* @access  public
	*/
	function set_email($email='') {
		if ($email == $this->email) return '';
		$email = trim($email);
		if(!valid_email($email)) {
			return "Email remains unchanged: invalid email address.";
		}
		$this->email = $email;
		$this->users_system->db->update("UPDATE user SET email='".addslashes($this->email)."' WHERE userid='$this->id'");
		$this->clear_cache($this->id);
		return "Email address changed to $email";
	}


	/**
	* Change the password of the user.
	*
	* @param   string $password The new password of the user.
	* @param   string $verification The verified password of the user.
	* @returns string Message about success
	* @access  public
	*/
	function set_password($password='',$verification='') {
		$error_messages = '';
		$password_valid = $this->validate_password($password, $verification, true, '', $error_messages);
		if (!$password_valid) {
			return $error_messages.' Password remains unchanged.';
		}
		$password = $this->translate_password_for_mysql($password);
		$this->password = $password;
		$this->users_system->db->update("UPDATE user SET password='".addslashes($this->password)."' WHERE userid='$this->id'");
		$this->clear_cache($this->id);
		return "Password changed.";
	}


	/**
	* Given a password, verifies this is correct for the user
	*
	* @param   string $password The password to verify.
	* @returns string Message about success
	* @access  public
	*/
	function verify_password($password='') {
		return $this->users_system->db->single_element("SELECT 1 FROM user WHERE userid='$this->id' AND password=password('".addslashes($password)."')");
	}

	/**
	* Change the users comments
	*
	* @param   string $comments The new comments for the user.
	* @returns string Message about success
	* @access  public
	*/
	function set_comments($comments='') {
		if ($comments == $this->comments) return '';
		$this->comments = $comments;
		$this->users_system->db->update("UPDATE user SET comments='".addslashes($this->comments)."' WHERE userid='$this->id'");
		$this->clear_cache($this->id);
		return "User comments changed";
	}

	/**
	* Change the users comments
	*
	* @param   string $expiry_date The new date
	* @returns string $web_status The new web status
	* @access  public
	*/
	function set_activity($expiry_date, $web_status) {
		if($web_status == $this->web_status && $expiry_date == $this->expiry_date) return '';
		if ($this->id == 1) return false; # Should be able to change this data for ROOT
		if(!ereg("[ALEP]",$web_status)) {
			return 'Web Status may only be either A, L, or E.';
		}
		$now = date("Y-m-d");

		# Do expiry date settings
		# If date is an expired date set status expired
		if($now > $expiry_date && $expiry_date != '0000-00-00' && $this->web_status != 'E') {
			$web_status = 'E';
		} elseif(($now < $expiry_date || $expiry_date == '0000-00-00') && $this->web_status != 'A') {
			# if date not expired and status is expired web status should be set to A
			$web_status = 'A';
		}

		# Do web status settings
		# If status trying set to A and date is expired fail set
		if ($web_status == 'A' && $now > $expiry_date) {
			$web_status = $this->web_status;
		}
		# If trying to set to status expired set date to an expired date too.
		if ($web_status == 'E' && $now < $expiry_date) {
			$expiry_date = $now;
		}

		# Need to check this again cause it may be the case now after our rule checking
		if($web_status == $this->web_status && $expiry_date == $this->expiry_date) return '';
		$this->expiry_date = $expiry_date;
		$this->web_status = $web_status;
		$this->users_system->db->update("UPDATE user SET web_status='$this->web_status' WHERE userid='$this->id'");
		$this->users_system->db->update("UPDATE user SET expiry_date='$this->expiry_date' WHERE userid='$this->id'");
		$this->clear_cache($this->id);
		if($expiry_date == '0000-00-00') {
			$expiry_date = 'never';
		}
		return 'Web Status set to '.$this->web_statii[$web_status].' and Account Expiry set to '.$expiry_date;
	}

	/**
	* Delete a user.
	* Also removes their super-user status if necessary and their access group permissions.
	*
	* @returns string Message about success
	* @access  public
	*/
	function delete() {

		$del_users = $this->users_system->db->delete("DELETE FROM user WHERE userid='$this->id'");
		if ($del_users) {

			 ###############################
			# Delete our superuser status
			$system_config = &get_system_config();
			if (in_array($this->login,$system_config->superusers['all'])) $system_config->delete_superuser($this->login);
			if (in_array($this->login,$system_config->superusers['web'])) $system_config->delete_web_master($this->login);
			if (in_array($this->login,$system_config->superusers['user'])) $system_config->delete_user_master($this->login);
			$system_config->conf_updated();

			$this->clear_cache($this->id);
			global $CACHE;
			$del_orgs = $this->users_system->db->delete("DELETE FROM affiliation WHERE userid='$this->id'");
			foreach($this->affiliations as $id => $data) $CACHE->clear($id,"organisation");
			$del_locs = $this->users_system->db->delete("DELETE FROM placement WHERE userid='$this->id'");
			foreach($this->placements as $id => $data) {
				$CACHE->clear($id,"location");
				$location = &$this->users_system->get_location($id);
				$message .= $location->check_tenency()."\n";
			}

			# Delete them from the access groups.
			$access_groups = $this->access_group_list();
			if (is_array($access_groups)) {
				global $INCLUDE_PATH;
				include_once("$INCLUDE_PATH/access_group.inc");
				foreach($access_groups as $gid) {
					$group = new Access_Group($gid);
					$group->remove_member('user', $this->id);
					$group->clear_cache();
					unset($group);
				}
			}

			# Delete them from the any pages they are admin's/editor's of
			$web = &get_web_system();
			$admin_pages = $this->page_security_list('admin');
			if (is_array($admin_pages)) {
				foreach($admin_pages as $pageid) {
					$page = &$web->get_page($pageid);
					$page->remove_adminid($this->id);
					$page->clear_cache();
					unset($page);
				}
			}

			$editor_pages = $this->page_security_list('editor');
			if (is_array($editor_pages)) {
				foreach($editor_pages as $pageid) {
					$page = &$web->get_page($pageid);
					$page->remove_editorid($this->id);
					$page->clear_cache();
					unset($page);
				}
			}

			 ####################################
			# Okay, lets get rid of the data directory
			if (is_dir($this->data_path)) {
				delete_directory($this->data_path);
			}

			$this = new User(0);

			return "$message\nDeleted $del_users user. ($del_orgs affiliations / $del_locs placements).";
		}
		return "Unable to delete user.";
	}


	/**
	* Adds an affiliation to the user.
	*
	* @param   int		$new_organisationid The new organisation to associate the user with.
	* @param   string	$new_title The users' title in the organisation
	* @returns string	Message about success
	* @access  public
	*/
	function add_affiliation($new_organisationid=0,$new_title='') {
		$org_tree = &$this->users_system->get_organisation_tree();
		if(!isset($org_tree[$new_organisationid])) return "Unable to create affiliation: organisation does not exist.";
		if(!$new_organisationid || !$new_title) return '';
		$query = "INSERT INTO affiliation (userid,organisationid,title) VALUES ('$this->id','$new_organisationid','".addslashes($new_title)."')";
		if($this->users_system->db->insert($query)) {
			$this->affiliations[$new_organisationid]["title"] = $new_title;
			$this->clear_cache($this->id);
			global $CACHE;
			$CACHE->clear($new_organisationid,"organisation");
			return "Affiliation created with {$org_tree[$new_organisationid][name]} under the title of $new_title";
		}
		return "Unable to create affiliation with organisation: {$org_tree[$new_organisationid][name]}";
	}


	/**
	* Updates affiliation answers
	*
	* @param   int		$organisationid The organisationid to update the answers for.
	* @param   string	$new_title The new answers for the questions.
	* @returns string	Message about success
	* @access  public
	*/
	function update_affiliation_answers($organisationid=0,&$answers) {
		# Affiliations must have a title, even if its a trivial thing.
		if(!$organisationid || $this->affiliations[$organisationid]['answers'] == $answers) return '';
		if(!isset($this->affiliations[$organisationid])) return;
		$query = "UPDATE affiliation SET answers='".addslashes($answers)."' WHERE organisationid='$organisationid' AND userid='$this->id'";
		if($this->users_system->db->update($query)) {
			$this->affiliations[$organisationid]['answers'] = $answers;
			$this->clear_cache($this->id);
			global $CACHE;
			$CACHE->clear($organisationid,"organisation");
			$org_tree = &$this->users_system->get_organisation_tree();
			return "Affiliation with {$org_tree[$organisationid][name]} updated";
		}
		return '';
	}


	/**
	* Update an affiliation title.
	*
	* @param   int		$organisationid The organisation to update
	* @param   string	$new_title The users' new title in the organisation
	* @returns string	Message about success
	* @access  public
	*/
	function update_affiliation_title($organisationid=0,$new_title='') {
		# Affiliations must have a title, even if its a trivial thing.
		if(!$organisationid || !$new_title || $this->affiliations[$organisationid]['title'] == $new_title) return '';
		if(!isset($this->affiliations[$organisationid])) return $this->add_affiliation($organisationid,$new_title);
		$query = "UPDATE affiliation SET title='".addslashes($new_title)."' WHERE organisationid='$organisationid' AND userid='$this->id'";
		if($this->users_system->db->update($query)) {
			$this->affiliations[$organisationid]["title"] = $new_title;
			$this->clear_cache($this->id);
			global $CACHE;
			$CACHE->clear($organisationid,"organisation");
			$org_tree = &$this->users_system->get_organisation_tree();
			return "Affiliation with {$org_tree[$organisationid][name]} updated:  $new_title";
		}
		return '';
	}


	/**
	* Deletes an affiliation to the user
	*
	* @param   int		$organisationid The organisation to delete the affiliation from.
	* @returns string	Message about success
	* @access  public
	*/
	function delete_affiliation($organisationid=0) {
		if(!$organisationid) return '';
		$query = "DELETE FROM affiliation  WHERE organisationid='$organisationid' AND userid='$this->id'";
		$org_tree = &$this->users_system->get_organisation_tree();
		if($this->users_system->db->delete($query)) {
			unset($this->affiliations[$organisationid]);
			$this->clear_cache($this->id);
			global $CACHE;
			$CACHE->clear($organisationid,"organisation");
			return "Affiliation with {$org_tree[$organisationid][name]} deleted.";
		}
		return "Unable to delete affiliation with organisation: {$org_tree[$organisationid][name]}";
	}


	/**
	* Adds an placement to the user
	*
	* @param   int		$new_locationid The new location or placement of the user.
	* @param   int		$new_direct_phone The users' phone number at this placement.
	* @param   int		$new_direct_fax The users' fax number at this placement.
	* @returns string	Message about success
	* @access  public
	*/
	function add_placement($new_locationid=0,$new_direct_phone='',$new_direct_fax='') {
		if(!$new_locationid) return '';
		$address = $this->users_system->location_address($new_locationid);
		$query = "INSERT INTO placement (userid,locationid,direct_phone,direct_fax) VALUES ('$this->id','$new_locationid','".addslashes($new_direct_phone)."','".addslashes($new_direct_fax)."')";
		if($this->users_system->db->insert($query)) {
			$this->placements[$new_locationid]['direct_phone'] = $new_direct_phone;
			$this->placements[$new_locationid]['direct_fax']   = $new_direct_fax;
			$this->clear_cache($this->id);
			global $CACHE;
			$CACHE->clear($new_locationid,"location");
			return "Placement created at $address (Ph: $new_direct_phone | Fx: $new_direct_fax)";
		}
		return "Unable to create placement at location: $address";
	}


	/**
	* Updates a placement for the user
	*
	* @param   int		$locationid The location or placement of the user.
	* @param   int		$new_direct_phone The users' phone number at this placement.
	* @param   int		$new_direct_fax The users' fax number at this placement.
	* @returns string	Message about success
	* @access  public
	*/
	function update_placement($locationid=0,$new_direct_phone='',$new_direct_fax='') {
		# placements must have a title, even if its a trivial thing. Why? BECAUSE I SAID SO.
		if(!isset($this->placements[$locationid]) && $new_direct_phone) return $this->add_placement($locationid,$new_direct_phone,$new_direct_fax);
		if(!$locationid || ($this->placements[$locationid]['direct_phone'] == $new_direct_phone && $this->placements[$locationid]['direct_fax'] == $new_direct_fax)) return '';
		$address = $this->users_system->location_address($locationid);
		$query = "UPDATE placement SET direct_phone='".addslashes($new_direct_phone)."', direct_fax='".addslashes($new_direct_fax)."' WHERE locationid='$locationid' AND userid='$this->id'";
		if($this->users_system->db->update($query)) {
			$this->placements[$locationid]['direct_phone'] = $new_direct_phone;
			$this->placements[$locationid]['direct_fax']   = $new_direct_fax;
			$this->clear_cache($this->id);
			global $CACHE;
			$CACHE->clear($locationid,"location");
			return "Placement updated at $address (Ph: $new_direct_phone | Fx: $new_direct_fax)";
		}
		return '';
	}


	/**
	* Removes a placement for the user.
	*
	* @param	int			$locationid		The location or placement to remove from the user.
	* @param	boolean		$delete			deletes the location if it is nolonger in use
	* @returns	string		Message			about success
	* @access  public
	*/
	function delete_placement($locationid=0, $delete = true) {
		if(!$locationid) return '';
		$address = $this->users_system->location_address($locationid);
		$query = "DELETE FROM placement  WHERE locationid='$locationid' AND userid='$this->id'";
		if($this->users_system->db->delete($query)) {
			unset($this->placements[$locationid]);
			$this->clear_cache($this->id);
			global $CACHE;
			$CACHE->clear($locationid,"location");
			$location = &$this->users_system->get_location($locationid);
			if ($delete) {
				$message .= $location->check_tenency()."\n";
			}
			return "$message\nPlacement at $address deleted.";
		}
		return "Unable to delete placement at location: $address";
	}


	/**
	* Returns a list of access group id's this user belongs to.
	*
	* @returns array	List of id's
	* @access  public
	*/
	function access_group_list() {
		if (!$this->access_groups) {
			$web = &get_web_system();
			$query = "SELECT groupid FROM access_group_user_membership WHERE userid='" . $this->id . "'";

			// don't need to check this is an array, single_column does that for us.
			$user_groups = $web->db->single_column($query);

			if(!empty($this->affiliations)) {
				$orgids = array_keys($this->affiliations);
				$org_groups = $web->db->single_column("SELECT groupid FROM access_group_organisation_membership WHERE organisationid IN ('".implode("','",$orgids)."')");
				if(!is_array($org_groups)) $org_groups = array();
			} else  $org_groups = array();

			$groups = array_merge($user_groups, $org_groups);
			$this->access_groups = array_unique($groups);
		}
		return $this->access_groups;
	}


	/**
	* Returns a list of pages that this user is either an admin for, or an editor of
	*
	* @param string	$sec_type	type of security to check for, either 'editor' or 'admin'
	*
	* @returns array	List of id's
	* @access  public
	*/
	function page_security_list($sec_type) {
		if ($sec_type != 'editor' && $sec_type != 'admin') return Array();

		$web = &get_web_system();
		$query = "SELECT pageid FROM page_".$sec_type." WHERE userid='" . $this->id . "'";

		// don't need to check this is an array, single_column does that for us.
		return $web->db->single_column($query);
	}


	/**
	* Returns a href to a file associated with this site (e.g. a custom image)
	*
	* @param   string	$filename The filename to return the href for.
	* @returns string	The href to the filename
	* @access  public
	*/
	function get_file_href($filename='') {
		return data_href("user/$this->id/$filename");
	}


	/**
	* Returns the current URL that the browser happens to be at
	*
	* @returns string	the current url
	* @access  public
	*/
	function current_url() {
		return $_SERVER['HTTP_HOST'].str_replace("index.php", '', $_SERVER['PHP_SELF']);
	}


	/**
	* Print the backend for the user
	*
	* @returns void
	* @access  public
	*/
	function print_backend() {
		$system_config = &get_system_config();
		$session = &get_mysource_session();
		 ###################################
		# Setup the backend a little
		$backend = &$this->users_system->setup_backend(); # Backend refrence
		$backend->set_title($this->name()." - Edit User");

		 ###############################
		# More aliases
		$org_tree = &$this->users_system->get_organisation_tree();
		$managed_organisationids = &$this->users_system->get_managed_organisationids();

		 ####################################################################
		# SECUIRTY - Only let in those who have permission to work on a site
		if (!$session->logged_in()) {
			$session->login_screen($backend->title,"You must be logged in and have permission to edit <i>$system_config->system_name</i> user records in order to proceed.");
		}

		 ######################################################################
		# This particular user must be affiliated with an managed organisation
		if (!$this->write_access()) {
			$session->login_screen($backend->title,'You are not a designated manager of any organisations that this user belongs to.');
		}

		 ######################################################################
		# Only Root can edit herself
		if ($this->id == 1 && !user_root()) {
			$session->login_screen($backend->title,'You have to be root if you want to edit root.');
		}

		$backend->set_tab('user',"user.php?userid=$this->id",'General','Edit this user','user');
		foreach($this->affiliations as $organisationid => $data) {
			$organisation = &$this->users_system->get_organisation($organisationid);
			if(!$organisation->form) continue;
			$backend->set_tab($organisation->name,"user.php?userid=$this->id&edit_am_section=Form&form_organisationid=$organisation->id",$organisation->name.' Special User Data','Edit this users special data fields','page');
		}
		$backend->set_heading('Edit User','user');
		$backend->set_name($this->name());
		$backend->set_id_string($this->id);

		$backend->set_hidden_field('action');
		$backend->set_hidden_field('userid',$this->id);
		$backend->set_hidden_field('organisationid');
		$backend->set_hidden_field('locationid');
		$backend->set_hidden_field('new_organisationid',$session->get_var('active_organisationid'));
		$backend->set_hidden_field('new_locationid',$session->get_var('active_locationid'));

		$edit_am_section = $_REQUEST['edit_am_section'];
		$backend->set_hidden_field('edit_am_section',$edit_am_section);
		switch($edit_am_section) {
			case 'Form':
				$this->print_form_backend();
				return;
		}

		$backend->set_active_tab('user');

		 #########################################
		# Process a submitted form
		$action = $_POST['action'];
		switch($action) {

		 ###################################
		# Commiting changes to the database
		case 'Commit':
			$expiry_date = $_POST['expiry_date'];
			$firstname = $_POST['firstname'];
			$surname = $_POST['surname'];
			$login = $_POST['login'];
			$password = $_POST['password'];
			$email = $_POST['email'];
			$web_status = $_POST['web_status'];
			$mobile_no = $_POST['mobile_no'];
			$comments = $_POST['comments'];
			$manager = $_POST['manager'];

			if($this->write_access()) {
				$backend->add_message($this->set_name(gpc_stripslashes($firstname), gpc_stripslashes($surname)));
				$backend->add_message($this->set_email(gpc_stripslashes($email)));
				$backend->add_message($this->set_login(gpc_stripslashes($login)));
				$backend->add_message($this->set_password(gpc_stripslashes($password[0]), gpc_stripslashes($password[1])));
				$backend->add_message($this->set_mobile_no(gpc_stripslashes($mobile_no)));
				$backend->add_message($this->set_web_status(gpc_stripslashes($web_status)));
				$backend->add_message($this->set_expiry_date(gpc_stripslashes($expiry_date)));
				$backend->add_message($this->set_comments(gpc_stripslashes($comments)));
				$upload_result = process_image_upload('userimage', $this->data_path.'/userimage', false);
				$backend->add_message($upload_result['message']);
			}

			
			 #########################
			# Update affiliations
			$title = $_POST['title'];
			if (is_array($title)) {
				foreach($title as $orgid => $new_title) {
					if (in_array($orgid,$managed_organisationids)) {
						$backend->add_message($this->update_affiliation_title($orgid,$new_title));
					}
				}
			}

			 #######################
		/*	# New affiliation
			$new_title = $_POST['new_title'];
			$new_organisationid = $_POST['new_organisationid'];
			if(in_array($new_organisationid,$managed_organisationids)) {
				$backend->add_message($this->update_affiliation_title($new_organisationid,gpc_stripslashes($new_title)));
				if($manager) {
					if($this->users_system->higher_manager($session->user->id,$new_organisationid)) {
						$this->users_system->db->update("UPDATE affiliation SET manager='Y' WHERE organisationid='$new_organisationid' AND userid='$this->id'");
					} else {
						$backend->add_message('Permission denied.');
					}
				}
			}
			*/

			 ###########################
			# Delete affiliations
			$delete_affiliations = $_POST['delete_affiliations'];
			$deletables = (!is_array($delete_affiliations)) ? array() : array_keys($delete_affiliations);
			foreach($deletables as $orgid) {
				 # Must be a manager
				if (in_array($orgid,$managed_organisationids)) {
					# Not allowed to "orphan" a user, even if you mange all their organisations
					$managed_affiliations = array_intersect(array_keys($this->affiliations),$managed_organisationids);
					array_remove_element($orgid,$managed_affiliations);
					if(superuser("user") ||	count($managed_affiliations) > 0) {
						# If this user is a manager of this organisation
						$user_managed_organisationids = &$this->users_system->get_managed_organisationids($this->id);
						if(in_array($orgid,$user_managed_organisationids)) {
							# Then we can only delete if we are a manager of a higher organisation
							if(!$this->users_system->higher_manager($session->user->id,$orgid)) {
								$fail = true;
							}
						}
						if($fail) {
							$backend->add_message("Permission denied: this user is a manager!");
						} else {
							$backend->add_message($this->delete_affiliation($orgid));
						}
					} else {
						$backend->add_message("Permission denied to delete affiliation: you would lose your user!");
					}
				}
			}
			 ###########################
			# new affiliation
			$affiliated_orgs	= $_POST['affiliated_orgs'];
			$orgs_title			= $_POST['orgs_title'];

			if(is_array($affiliated_orgs)){
				#add any affiliations that are selected and that are not already affiliations, fool
				foreach($affiliated_orgs as $orgid){
					if(!in_array($orgid, array_keys($this->affiliations)) && $orgid){
						if(!$orgs_title){
							$backend->add_message("No title was selected for the organisations, No changes were committed.");
						} else {
							$this->add_affiliation($orgid, gpc_stripslashes($orgs_title));
							
							if($manager) {
								if($this->users_system->higher_manager($session->user->id,$new_organisationid)) {
									$this->users_system->db->update("UPDATE affiliation SET manager='Y' WHERE organisationid='$orgid' AND userid='$this->id'");
								} else {
									$backend->add_message('Permission denied.');
								}
							}
							$backend->add_message("Affiliation with " .$org_tree[$orgid]['name'] . " Added.");
						}
					} else {
						$backend->add_message("Affiliation with " .$org_tree[$orgid]['name'] . " already exists");
					}
				}
			}

			if($this->write_access()) {
				 #########################
				# Update placements
				$direct_phone = $_POST['direct_phone'];
				$direct_fax = $_POST['direct_fax'];
				if (is_array($direct_phone)) {
					foreach($direct_phone as $locid => $new_direct_phone) {
						$new_direct_fax = $direct_fax[$locid];
						$backend->add_message($this->update_placement($locid, gpc_stripslashes($new_direct_phone), gpc_stripslashes($new_direct_fax)));
					}
				}

				 ###########################
				# Delete placements
				$delete_placements = $_POST['delete_placements'];
				$deletables = (is_array($delete_placements)) ? array_keys($delete_placements) : array();
				if (is_array($deletables)) {
					foreach($deletables as $locid) {
						$backend->add_message($this->delete_placement($locid));
					}
				}
			}
			break;


		 ###################################
		# Delete a user from the database
		case 'Delete':
			if($this->delete_access()) {
				$blank = '';
				$session->set_var('active_userid',$blank);
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

		 #######################
		# Die on no ID
		if(!$this->id) {
			return '';
		}

		 ########################################
		# Refresh the information - just in case
		if ($action) {
			$this->clear_cache($this->id);
			$this->load();
		}

		$backend->set_toolbar_button("find","javascript: var user_search; if ((user_search = prompt('Enter the name, login or email address of a user you\'d like to find?','$this->login')) != null && user_search != 'null') {location='users.php?action=Search+Users&user_search='+escape(user_search);}","Find a different user","find");

		if($this->delete_access()) {
			$backend->set_toolbar_button("delete","javascript: if (confirm('Are you sure you want to delete this user?') && confirm('Really sure? This is irreversible.')) {document.edit.action.value='Delete';document.edit.submit()}","Delete this user","delete");
		}

		$backend->print_header();

		$authentication = &$this->users_system->get_authentication();
		if($authentication->codename != 'default') {
			$backend->open_section('Note');
			$backend->open_field('','top');
			echo("<b>WARNING:</b> This MySource system is not using the default MySource user database for authentication. It is using $authentication->name. The password field below will not be used for authentication.");
		}


		$backend->open_section('Attributes');

		$backend->open_field('Firstname','one_liner','','',150);
		echo text_box('firstname',$this->firstname,20,128,'class=backend_data');

		$backend->open_field('Surname','one_liner');
		echo text_box('surname',$this->surname,20,128,'class=backend_data');

		$backend->open_field('Login','one_liner');
		echo text_box('login',$this->login,10,64,'class=backend_data');

		$backend->open_field('Change Password','one_liner');
		?><input type="password" name="password[0]" value='' size=12 maxlength=16 class=backend_data><?
		$backend->open_field('(Verify)','one_liner');
		?><input type="password" name="password[1]" value='' size=12 maxlength=16 class=backend_data><?

		$backend->open_field('Email','one_liner');
		echo text_box('email',$this->email,40,128,'class=backend_data');

		$backend->open_field('Mobile','one_liner');
		echo text_box('mobile_no',$this->mobile_no,16,32,'class=backend_data');

		$backend->open_field('Account Expiry','one_liner');
		echo date_box('expiry_date',(($this->expiry_date) ? $this->expiry_date : ''),'edit');

		$backend->open_field('Web Status','one_liner');
		echo combo_box('web_status',$this->web_statii,$this->web_status,'',20,'class=backend_data');

		$backend->open_field('User Picture','one_liner');
		echo image_upload('userimage', $this->data_path.'/userimage', $this->get_file_href(), '', 0, 0, true);

		$backend->open_field('Comments','one_liner');
		echo text_area('comments',$this->comments,40,8,2000,'class=backend_data');

		$backend->open_section("Affiliated Organisations","organisation");

		if(count($this->affiliations) == 0) {
			$backend->open_field('','one_liner','','',150);
			echo "None.";
		} 
		
		$i = 1;
		if (is_array($this->affiliations)) {
			$db = &$this->users_system->db;

			foreach($this->affiliations as $organisationid=>$data) {
				$manager = $db->single_element("SELECT manager FROM affiliation WHERE userid=" . $this->id . " AND organisationid=" . $organisationid);
				
				$organisation = &$org_tree[$organisationid];
				$parent       = &$org_tree[$organisation['parentid']];
				$backend->open_field($i++,'one_liner','','',150);
				$managed = in_array($organisationid,$managed_organisationids);
				echo (($managed)?text_box("title[$organisationid]",$data["title"],30,128,'class=backend_data'):$data["title"]);
				$user_managed_organisationids = &$this->users_system->get_managed_organisationids($this->id,false);
				if($manager == 'Y') {
					echo "<span style=\"color: #aa0000\"><b>(Manager)</b></span>";
				}
				if($managed) {
					echo(" <b>[<input type=checkbox name=delete_affiliations[$organisationid] value=1><span style=\"color: #aa0000\">Delete?</span>]</b>");
				}
				echo ("<br> with <a href=\"organisation.php?organisationid=$organisationid\" ".(($managed)?"style=\"color: #0000ff\"":'')."><b>$organisation[name]</b></a> ".(($organisation['parentid']>0)?"(of <a href=\"organisation.php?organisationid=$parent[organisationid]\">$parent[name]</a>) ":''));
			}
		}

		# section to add a new affiliation
		$backend->open_section('Create New Affiliation','organisation');
		$backend->open_field('Title','one_liner','','',150);
		echo text_box(orgs_title,'',30,35,'class=backend_data') . '&nbsp';

		$backend->open_field('Select Organisations','one_liner','','',150);
		
		$org_tree = &$this->users_system->get_organisation_tree();
		foreach($org_tree as $org){
			$orgObj = new Organisation($org['organisationid']);
			if($orgObj->write_access()){
				$managed_orgs[] = $org['organisationid'];
			}
		}
		
		# get a list of ids of which the user is a manager of
		if(empty($managed_orgs)){
			echo "You are not a manager of any organisations.";
		} else {
		
			# get a list of the orgs with sticks
			$orgs = &$this->users_system->organisation_array_with_sticks();
			
			# create a list of orgs (with sticks) of witch the user is a member
			$wanted_orgs = $orgs;
			foreach (array_keys($orgs) as $org_with_stick_id) {
				if(!in_array($org_with_stick_id, $managed_orgs)){
					unset($wanted_orgs[$org_with_stick_id]);
				}
			}

			# create a multiselect box, so the user can
			# affiliate users with more that one organisation at a time
			if(empty($orgs)) {
				echo 'No Organisations.';
			} else { 
				echo multiple_combo_box('affiliated_orgs', $wanted_orgs, '', '', 70, 5);
			}
			echo "&nbsp;&nbsp;<b>[<input type=checkbox name=manager value=1><span style=\"color: #aa0000\">Make Manager?</span>]</b>";
		}


		$backend->open_section('Location Placements','location');

		if(empty($this->placements)) {
			$backend->open_field('','one_liner','','',150);
			echo 'None.';
		}

		$i = 1;
		if (is_array($this->placements)) {
			foreach($this->placements as $locationid=>$data) {
				$direct_phone = $data['direct_phone'];
				$direct_fax   = $data['direct_fax'];
				$location     = $this->users_system->location_address($locationid);
				$backend->open_field($i++,'one_liner','','',150);
				echo("Ph:".text_box("direct_phone[$locationid]",$direct_phone,15,32,'class=backend_data'));
				echo(" at <a href=\"location.php?locationid=$locationid\"><b>$location</b></a><br>");
				echo("Fx:".text_box("direct_fax[$locationid]",$direct_fax,15,32,'class=backend_data'));
				echo(" <b>[<input type=checkbox name=delete_placements[$locationid] value=1><span style=\"color: #aa0000\">Delete?</span>]</b>");
			}
		}

		$active_locationid = $session->get_var('active_locationid');
		if ($active_locationid && !$this->placements[$active_locationid]) {
			$location     = $this->users_system->location_address($active_locationid);
			$backend->open_field("New Placement?<span style=\"font-size:8pt\"><br>(or leave blank)</span>",'one_liner','','',150);
			echo ("Ph:".text_box("direct_phone[$active_locationid]",'',15,128,'class=backend_data'));
			echo (" at <a href=\"location.php?locationid=$active_locationid\"><b>$location</b></a><br>");
			echo ("Fx:".text_box("direct_fax[$active_locationid]",'',15,128,'class=backend_data'));
		}

		$backend->open_section('Create New Placement','location');
		$backend->open_field('New Location?','one_liner','','',150);
		echo "Ph:".text_box("new_location_direct_phone",'',15,128,'class=backend_data')."&nbsp;";
		echo "Fx:".text_box("new_location_direct_fax",'',15,128,'class=backend_data')."&nbsp;";
		$backend->print_icon_button("javascript: if (document.edit.new_location_direct_phone.value != '' || document.edit.new_location_direct_fax.value != '') window.location='location.php?placementid=$this->id&placement_phone=' + escape(document.edit.new_location_direct_phone.value) + '&placement_fax=' + escape(document.edit.new_location_direct_fax.value);","Create New Location","create");

		$backend->print_commit_button("Commit","if(check_date('edit','expiry_date')){if(confirm('Commit this information to the system?')){document.edit.action.value='Commit';document.edit.submit();}}");
		$backend->print_footer();
	}

	/**
	* Returns the fullname of a user
	*
	* @returns string	the users' name.
	* @access  public
	*/
	function name() {
		if (trim($this->firstname) == '' && trim($this->surname) == '') {
			return $this->login();
		}
		return "$this->firstname $this->surname";
	}

	/**
	* Returns the login of a User
	*
	* @returns string	The users' login
	* @access  public
	*/
	function login() {
		return $this->login;
	}

	/**
	* Print the backend screen for extra user data
	*
	* @returns string	the users' name.
	* @access  public
	*/
	function print_form_backend() {
		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/form/form.inc");

		$backend = &$this->get_backend();
		$form_organisationid = $_REQUEST['form_organisationid'];
		$backend->set_hidden_field("form_organisationid",$form_organisationid);
		$organisation = &$this->users_system->get_organisation($form_organisationid);
		$backend->set_active_tab($organisation->name);
		$backend->set_subheading("&nbsp;");

		$backend->print_header();

		$form = new Form($organisation->form);
		if ($form->process()) {
			if(!$form->check()) {
				$results = $form->check_results;
				$backend->open_field("Form Results","top");
				echo("<span class=error>You have not filled out this form correctly:</span>");
				echo("<ul>");
				foreach($results as $result) {
					echo "<li><span class=error>$result</span></li>";
				}
				echo("</ul>");
			} else {
				$backend->add_message($this->update_affiliation_answers($form_organisationid,$form->export_answers()));
			}
		}

		$form->import_answers($this->affiliations[$form_organisationid]["answers"]);

		# indices possibly don't start from 0
		$form_sections_values = array_values($form->sections);

		if(count($form->sections) == 1 && !$form_sections_values[0]["questions"]) {
			$backend->open_field('');
			echo "No special user data available for ".$organisation->name;
		} else {
			$form->render_backend(&$backend);
		}

		$backend->print_footer();

	}


	/**
	* Return the questions and default answers for the specified organisation
	*
	* @returns array	a list of question objects
	* @access  public
	*/
	function get_sud_questions($organisation_id) {
		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/form/form.inc");

		$organisation = &$this->users_system->get_organisation($organisation_id);

		$form = new Form($organisation->form);
		$form->import_answers($this->affiliations[$form_organisationid]["answers"]);

		$form_sections = $form->sections;
		$all_form_questions = array();
		
		foreach($form_sections as $section_data) {
		  while (list($key, $val) = each($section_data))  {
		    if ($key == "questions")  {
			// pull each question from its section array
			foreach($val as $question) {
				// add questions to one array
				array_push($all_form_questions, $question);
			}
		    }
		  }
		}
		
		return $all_form_questions;
	}


	/**
	 * Return the response for a specific question from the special user data
	 *
	 * @returns string	the answer for the specified question
	 * @access  public
	 */
	function get_sud_response($organisation_id, $question_title) {
		$response = null;
		$all_form_responses = $this->get_all_sud_responses($organisation_id);

		foreach($all_form_responses as $section_id=>$item)	{
			# sort each item into an array with title/value pairs
			foreach($item as $item_element)	{
				$item_title = $item_element['title'];
				$item_response = $item_element['value'];

				# have we found our desired question? - if so, return the answer
				if ($item_title == $question_title)	{
					return $item_response;
				}
			}
		}

		return $response;					
	}

	
	/**
	 * Return all responses for this user from the special user data
	 *
	 * @returns array	answers for all questions
	 * @access  public
	 */
	function get_all_sud_responses($organisation_id) {
		$responses = unserialize($this->affiliations[$organisation_id]["answers"]);

		return $responses;
	}


	/**
	* Checks if a password is valid
	*
	* @param string $password The new password of the user.
	* @param string $verification The verified password of the user. (optional if $check_validatation_aswell is false
	* @param boolean $check_validatation_aswell Whether it should test $verification
	* @param string $login Needed if this object is not yet a real user and just a blank object
	* @param string &$error_messages Blank unless you did something wrong
	* @returns boolean
	* @access  public
	*/
	function validate_password($password='',$verification='', $check_verification=true, $login='', &$error_messages) {
		if(!$password) {
			$error_messages = 'No password was entered!';
			return false;
		}
		if ($this->login == '' && $login == '') {
			$error_messages = 'A login must exist in one way or another!';
			return false;
		}
		if($check_verification && $password != $verification) {
			$error_messages = 'Password remains unchanged: password and verification do not match.';
			return false;
		}
		if (strlen($password) < 1) {
			$error_messages = 'Password invalid: must be at least 1 character long.';
			return false;
		} elseif(strlen($password) > 128) {
			$error_messages = 'Password invalid: must be less than 128 characters long.';
			return false;
		}

		if (($this->login != '' && $password == $this->login) || ($login != '' && $password == $login)) {
			$error_messages = 'Password invalid. Username and password can not be the same.';
			return false;
		}

		if ($this->login != '') {
			$password = $this->translate_password_for_mysql($password);
			if($password == $this->password) {
				$error_messages = 'Password invalid. The new password is the same as the old one.';
				return false;
			}
		}
		# Password is valid
		return true;
	}

	/**
	* Function to validate a login and return an error message if the login is no good
	*
	* @param string $login The login to validate
	* @returns string
	* @access  public
	*/
	function validate_login($login) {
		$msg = '';
		$db = &$this->users_system->db;

		if ($login != strtolower($login)) {
			$msg = 'Unable to create user: Login cannot contain capital letters.';
		} elseif (strlen($login) < 2) {
			$msg = "Unable to create user: Login must be at least two characters: '$login'.";
		} elseif(ereg("[^a-z0-9\_]",$login)) {
			$msg = "Unable to create user: Only alphanumeric characters and underscores allowed in login.";
		} elseif ($name = $db->single_element("SELECT concat(firstname,' ',surname) FROM user WHERE login='".addslashes($login)."'")) {
			$msg = 'Unable to create user: Login already exists.';
		}
		
		return $msg;
	}

	/**
	* Translates a string password as you or I know it into mysql password
	*
	* @param string $password The password to translate
	* @returns string
	* @access  public
	*/
	function translate_password_for_mysql($password) {
		$v = explode('.', $this->users_system->db->server_version());
		if ($v[0] == '4') {
			$qry = "SELECT OLD_PASSWORD('".addslashes($password)."')";
		} else {
			$qry = "SELECT PASSWORD('".addslashes($password)."')";
		}

		return $this->users_system->db->single_element($qry);
	}

	/**
	* Whether you give it a login or not it returns a suggested valid login. If you give it a taken login it will keep incrementing a any numbers on the end till if find a non taken login. If there are no numbers on the end it adds 1 to the end to start the ball rolling.
	*
	* @param string $login The optional login to test with
	* @returns string
	* @access public
	*/
	function suggest_login($login='') {
		if ($this->validate_login($login)) {
			$login = random_username();
		}
		$count = 1;
		$non_number_found = false;
		$number = '';
		while((strlen($login) - $count) > -1 && !$non_number_found) {
			# Work out if there are any numbers on the end of this login
			$character = $login[strlen($login)-$count];
			if (is_numeric($character)) {
				# We append to the start because we are going thru this password backwards
				$number = $character.$number;
				$count++;
			} else {
				$non_number_found = true;
			}
		}

		# Split the number component and login
		$count--;
		$no_number_login_component = substr($login, 0, (strlen($login)-$count));
		$number_login_component = $number;

		$db = &$this->users_system->db;
		while ($db->single_element("SELECT COUNT(login) FROM user WHERE login='".addslashes($login)."'") > 0) {
			# User name is taken so lets add one to the number component and try again
			if (!is_numeric($number_login_component)) {
				$number_login_component = 1;
			} else {
				$number_login_component++;
			}

			$login = $no_number_login_component.$number_login_component;
		}
		return $login;
	}
}
?>

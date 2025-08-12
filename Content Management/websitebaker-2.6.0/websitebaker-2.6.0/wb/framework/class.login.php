<?php

// $Id: class.login.php 239 2005-11-22 11:50:41Z stefan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/*

Login class

This class will be used to with the login application

*/

// Stop this file from being accessed directly
if(!defined('WB_URL')) {
	header('Location: ../index.php');
}

define('LOGIN_CLASS_LOADED', true);

// Load the other required class files if they are not already loaded
require_once(WB_PATH."/framework/class.admin.php");

class login extends admin {
	function login($config_array) {
		// Get language vars
		global $MESSAGE;
		$this->wb();
		// Get configuration values
		$this->USERS_TABLE = $config_array['USERS_TABLE'];
		$this->GROUPS_TABLE = $config_array['GROUPS_TABLE'];
		$this->username_fieldname = $config_array['USERNAME_FIELDNAME'];
		$this->password_fieldname = $config_array['PASSWORD_FIELDNAME'];
		$this->remember_me_option = $config_array['REMEMBER_ME_OPTION'];
		$this->max_attemps = $config_array['MAX_ATTEMPS'];
		$this->warning_url = $config_array['WARNING_URL'];
		$this->login_url = $config_array['LOGIN_URL'];
		$this->template_dir = $config_array['TEMPLATE_DIR'];
		$this->template_file = $config_array['TEMPLATE_FILE'];
		$this->frontend = $config_array['FRONTEND'];
		$this->forgotten_details_app = $config_array['FORGOTTEN_DETAILS_APP'];
		$this->max_username_len = $config_array['MAX_USERNAME_LEN'];
		$this->max_password_len = $config_array['MAX_PASSWORD_LEN'];
		$this->redirect_url = $config_array['REDIRECT_URL'];
		// Get the supplied username and password
		if ($this->get_post('username_fieldname') != ''){
			$username_fieldname = $this->get_post('username_fieldname');
			$password_fieldname = $this->get_post('password_fieldname');
		} else {
			$username_fieldname = 'username';
			$password_fieldname = 'password';
		}
		$this->username = strtolower($this->get_post($username_fieldname));
		$this->password = $this->get_post($password_fieldname);
		// Figure out if the "remember me" option has been checked
		if($this->get_post('remember') == 'true') {
			$this->remember = $this->get_post('remember');
		} else {
			$this->remember = false;
		}
		// Get the length of the supplied username and password
		if($this->get_post($username_fieldname) != '') {
			$this->username_len = strlen($this->username);
			$this->password_len = strlen($this->password);
		}
		// If the url is blank, set it to the default url
		$this->url = $this->get_post('url');
		if ($this->redirect_url!='') {
			$this->url = $this->redirect_url;
		}		
		if(strlen($this->url) < 2) {
			$this->url = $config_array['DEFAULT_URL'];
		}
		if($this->is_authenticated() == true) {
			// User already logged-in, so redirect to default url
			header('Location: '.$this->url);
			exit();
		} elseif(!isset($username_fieldname) AND $this->is_remembered() == true) {
			// User has been "remembered"
			// Get the users password
			$database = new database();
			$query_details = $database->query("SELECT * FROM ".$this->USERS_TABLE." WHERE user_id = '".substr($_COOKIE['REMEMBER_KEY'], 0, 11)."' LIMIT 1");
			$fetch_details = $query_details->fetchRow();
			$this->username = $fetch_details['username'];
			$this->password = $fetch_details['password'];
			// Check if the user exists (authenticate them)
			if($this->authenticate()) {
				// Authentication successful
				header("Location: ".$this->url);
			} else {
				$this->message = $MESSAGE['LOGIN']['AUTHENTICATION_FAILED'];
				$this->increase_attemps();
			}
		} elseif($this->username == '' AND $this->password == '') {
			$this->message = $MESSAGE['LOGIN']['BOTH_BLANK'];
			$this->increase_attemps();
		} elseif($this->username == '') {
			$this->message = $MESSAGE['LOGIN']['USERNAME_BLANK'];
			$this->increase_attemps();
		} elseif($this->password == '') {
			$this->message = $MESSAGE['LOGIN']['PASSWORD_BLANK'];
			$this->increase_attemps();
		} elseif($this->username_len < $config_array['MIN_USERNAME_LEN']) {
			$this->message = $MESSAGE['LOGIN']['USERNAME_TOO_SHORT'];
			$this->increase_attemps();
		} elseif($this->password_len < $config_array['MIN_PASSWORD_LEN']) {
			$this->message = $MESSAGE['LOGIN']['PASSWORD_TOO_SHORT'];
			$this->increase_attemps();
		} elseif($this->username_len > $config_array['MAX_USERNAME_LEN']) {
			$this->message = $MESSAGE['LOGIN']['USERNAME_TOO_LONG'];
			$this->increase_attemps();
		} elseif($this->password_len > $config_array['MAX_PASSWORD_LEN']) {
			$this->message = $MESSAGE['LOGIN']['PASSWORD_TOO_LONG'];
			$this->increase_attemps();
		} else {
			// Check if the user exists (authenticate them)
			$this->password = md5($this->password);
			if($this->authenticate()) {
				// Authentication successful
				//echo $this->url;exit();
				header("Location: ".$this->url);
			} else {
				$this->message = $MESSAGE['LOGIN']['AUTHENTICATION_FAILED'];
				$this->increase_attemps();
			}
		}
	}
	
	// Authenticate the user (check if they exist in the database)
	function authenticate() {
		// Get user information
		$database = new database();
		$query = "SELECT * FROM ".$this->USERS_TABLE." WHERE username = '".$this->username."' AND password = '".$this->password."' AND active = '1'";
		$results = $database->query($query);
		$results_array = $results->fetchRow();
		$num_rows = $results->numRows();
		if($num_rows) {
			$user_id = $results_array['user_id'];
			$this->user_id = $user_id;
			$_SESSION['USER_ID'] = $user_id;
			$_SESSION['GROUP_ID'] = $results_array['group_id'];
			$_SESSION['USERNAME'] = $results_array['username'];
			$_SESSION['DISPLAY_NAME'] = $results_array['display_name'];
			$_SESSION['EMAIL'] = $results_array['email'];
			$_SESSION['HOME_FOLDER'] = $results_array['home_folder'];
			// Run remember function if needed
			if($this->remember == true) {
				$this->remember($this->user_id);
			}
			// Set language
			if($results_array['language'] != '') {
				$_SESSION['LANGUAGE'] = $results_array['language'];
			}
			// Set timezone
			if($results_array['timezone'] != '-72000') {
				$_SESSION['TIMEZONE'] = $results_array['timezone'];
			} else {
				// Set a session var so apps can tell user is using default tz
				$_SESSION['USE_DEFAULT_TIMEZONE'] = true;
			}
			// Set date format
			if($results_array['date_format'] != '') {
				$_SESSION['DATE_FORMAT'] = $results_array['date_format'];
			} else {
				// Set a session var so apps can tell user is using default date format
				$_SESSION['USE_DEFAULT_DATE_FORMAT'] = true;
			}
			// Set time format
			if($results_array['time_format'] != '') {
				$_SESSION['TIME_FORMAT'] = $results_array['time_format'];
			} else {
				// Set a session var so apps can tell user is using default time format
				$_SESSION['USE_DEFAULT_TIME_FORMAT'] = true;
			}
			// Get group information
			$query = "SELECT * FROM ".$this->GROUPS_TABLE." WHERE group_id = '".$this->get_session('GROUP_ID')."'";
			$results = $database->query($query);
			$results_array = $results->fetchRow();
			$_SESSION['GROUP_NAME'] = $results_array['name'];
			// Set system permissions
			if($results_array['system_permissions'] != '') {
				$_SESSION['SYSTEM_PERMISSIONS'] = explode(',', $results_array['system_permissions']);
			} else {
				$_SESSION['SYSTEM_PERMISSIONS'] = array();
			}
			// Set module permissions
			if($results_array['module_permissions'] != '') {
				$_SESSION['MODULE_PERMISSIONS'] = explode(',', $results_array['module_permissions']);
			} else {
				$_SESSION['MODULE_PERMISSIONS'] = array();
			}
			// Set template permissions
			if($results_array['template_permissions'] != '') {
				$_SESSION['TEMPLATE_PERMISSIONS'] = explode(',', $results_array['template_permissions']);
			} else {
				$_SESSION['TEMPLATE_PERMISSIONS'] = array();
			}
			// Update the users table with current ip and timestamp
			$get_ts = mktime();
			$get_ip = $_SERVER['REMOTE_ADDR'];
			$query = "UPDATE ".$this->USERS_TABLE." SET login_when = '$get_ts', login_ip = '$get_ip' WHERE user_id = '$user_id'";
			$database->query($query);
		}
		// Return if the user exists or not
		return $num_rows;
	}
	
	// Increase the count for login attemps
	function increase_attemps() {
		if(!isset($_SESSION['ATTEMPS'])) {
			$_SESSION['ATTEMPS'] = 0;
		} else {
			$_SESSION['ATTEMPS'] = $this->get_session('ATTEMPS')+1;
		}
		$this->display_login();
	}
	
	// Function to set a "remembering" cookie for the user
	function remember($user_id) {
		$remember_key = '';
		// Generate user id to append to the remember key
		$length = 11-strlen($user_id);
		if($length > 0) {
			for($i = 1; $i <= $length; $i++) {
				$remember_key .= '0';
			}
		}
		// Generate remember key
		$remember_key .= $user_id.'_';
		$salt = "abchefghjkmnpqrstuvwxyz0123456789";
		srand((double)microtime()*1000000);
		$i = 0;
		while ($i <= 10) {
			$num = rand() % 33;
			$tmp = substr($salt, $num, 1);
			$remember_key = $remember_key . $tmp;
			$i++;
		}
		$remember_key = $remember_key;
		// Update the remember key in the db
		$database = new database();
		$database->query("UPDATE ".$this->USERS_TABLE." SET remember_key = '$remember_key' WHERE user_id = '$user_id' LIMIT 1");
		if($database->is_error()) {
			return false;
		} else {
			// Workout options for the cookie
			$cookie_name = 'REMEMBER_KEY';
			$cookie_value = $remember_key;
			$cookie_expire = time()+60*60*24*30;
			// Set the cookie
			if(setcookie($cookie_name, $cookie_value, $cookie_expire, '/')) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	// Function to check if a user has been remembered
	function is_remembered() {
		if(isset($_COOKIE['REMEMBER_KEY']) AND $_COOKIE['REMEMBER_KEY'] != '') {
			// Check if the remember key is correct
			$database = new database();
			$check_query = $database->query("SELECT user_id FROM ".$this->USERS_TABLE." WHERE remember_key = '".$_COOKIE['REMEMBER_KEY']."' LIMIT 1");
			if($check_query->numRows() > 0) {
				$check_fetch = $check_query->fetchRow();
				$user_id = $check_fetch['user_id'];
				// Check the remember key prefix
				$remember_key_prefix = '';
				$length = 11-strlen($user_id);
				if($length > 0) {
					for($i = 1; $i <= $length; $i++) {
						$remember_key_prefix .= '0';
					}
				}
				$remember_key_prefix .= $user_id.'_';
				$length = strlen($remember_key_prefix);
				if(substr($_COOKIE['REMEMBER_KEY'], 0, $length) == $remember_key_prefix) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	// Display the login screen
	function display_login() {
		// Get language vars
		global $MESSAGE;
		global $MENU;
		global $TEXT;
		// If attemps more than allowed, warn the user
		if($this->get_session('ATTEMPS') > $this->max_attemps) {
			$this->warn();
		}
		// Show the login form
		if($this->frontend != true) {
			require_once(WB_PATH.'/include/phplib/template.inc');
			$template = new Template($this->template_dir);
			$template->set_file('page', $this->template_file);
			$template->set_block('page', 'mainBlock', 'main');
			if($this->remember_me_option != true) {
				$template->set_var('DISPLAY_REMEMBER_ME', 'none');
			} else {
				$template->set_var('DISPLAY_REMEMBER_ME', '');
			}
			$template->set_var(array(
											'ACTION_URL' => $this->login_url,
											'ATTEMPS' => $this->get_session('ATTEMPS'),
											'USERNAME' => $this->username,
											'USERNAME_FIELDNAME' => $this->username_fieldname,
											'PASSWORD_FIELDNAME' => $this->password_fieldname,
											'MESSAGE' => $this->message,
											'INTERFACE_DIR_URL' =>  ADMIN_URL.'/interface',
											'MAX_USERNAME_LEN' => $this->max_username_len,
											'MAX_PASSWORD_LEN' => $this->max_password_len,
											'WB_URL' => WB_URL,
											'FORGOTTEN_DETAILS_APP' => $this->forgotten_details_app,
											'TEXT_FORGOTTEN_DETAILS' => $TEXT['FORGOTTEN_DETAILS'],
											'TEXT_USERNAME' => $TEXT['USERNAME'],
											'TEXT_PASSWORD' => $TEXT['PASSWORD'],
											'TEXT_REMEMBER_ME' => $TEXT['REMEMBER_ME'],
											'TEXT_LOGIN' => $TEXT['LOGIN'],
											'TEXT_HOME' => $TEXT['HOME'],
											'PAGES_DIRECTORY' => PAGES_DIRECTORY,
											'SECTION_LOGIN' => $MENU['LOGIN']
											)
									);
			$template->parse('main', 'mainBlock', false);
			$template->pparse('output', 'page');
		}
	}
	
	// Warn user that they have had to many login attemps
	function warn() {
		header('Location: '.$this->warning_url);
	}
	
}

?>
<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/user_reference/user_reference.inc,v $
## $Revision: 1.47.2.1 $
## $Author: achadszinow $
## $Date: 2004/05/10 23:35:56 $
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/user_reference/user_reference.inc,v $
## $Revision: 1.47.2.1 $
## $Author: achadszinow $
## $Date: 2004/05/10 23:35:56 $
#######################################################################

#---------------------------------------------------------------------#

 ############################
# For simple hyperlinked text
class Web_Extension_Notitia_Attribute_Type_User_Reference extends Web_Extension_Notitia_Attribute_Type {
	
	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => false,
			'value_formats' => array(
				'id' => 'User ID',
				'login' => 'User Login',
				'name' => 'User Name',
				'mailto' => 'Mailto',
				'firstname' => 'Firstname',
				'surname' => 'Surname',
				'email' => 'Email',
				'expiry' => 'Expiry',
				'web_status' => 'Web Status'
			),
			'search_formats' => array(
				'id' => 'ID',
			),
			'edit_formats' => array (
				'raw' => 'Search',
				'is' => 'Intranet Style',
				'pword_l' => 'Password Only W/Login Displayed (EDIT ONLY)',
				'pword' => 'Password Only (EDIT ONLY)',
				'activity' => 'Activity (Includes Expiry and Web Status EDIT ONLY)'
			),
			'sort_formats' => array (
				'raw' => 'By User ID',
				'raw<' => 'By User ID Desc.'
			),
			'rule_types' => array (
				'must_hold_user' => 'Must Hold User'
			),
			'requires_context_values' => false,
			'sub_type_name' => 'user_reference',
			'table_settings' => array(
				'sub_table_name' => ''
			)
	);

	 ################################################
	# Returns the value formatted in a nice way for printing
	function formatted_value(&$value, $format = 'name') {
		$users_sys = &get_users_system();
		$user = &$users_sys->get_user($value);
		if(!$user->id) {
			if (!isset($this->parameters['user_not_found_message'])) {
				return '[User not found]<br />';
			} else {
				return $this->parameters['user_not_found_message'];
			}
		}
		switch($format) {
			case 'login':
				return $user->login();
			case 'id':
				return $value;
			case 'name':
				return $user->name();
			case 'mailto':
				if($user->email) return '<a href="mailto:'.$user->email.'">'.$user->name().'</a>';
				else $user->name();
			case 'firstname':
				return $user->firstname;
			case 'surname':
				return $user->surname;
			case 'email':
				return $user->email;
			case 'expiry':
				return $user->expiry_date;
			case 'web_status':
				$statii = $this->get_all_user_statii();
				return $statii[$user->web_status];
			default:
				return $value;
		}
	}
	
	 #############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value,$format) {
		$users_sys = &get_users_system();
		$user = &$users_sys->get_user($value);

		if (is_array($format)) {
			if ($format['create_edit_user']) {
				$format = 'create_nh';
			}
		} else {
			list($format, $categoryid) = explode('|', $format);
		}

		if ($format == 'is' && $user->id) {
			$format = 'pword_l';
		}

		switch($format) {
			case 'raw': default:
				# handle it if autocomplete is on
				$autocomplete = $this->get_value('autocomplete');
				if($autocomplete) {
					$autos = $this->get_autos($autocomplete);
				}
				if (!isset($this->parameters['find_user_message'])) {
					echo 'Find:(login/email/name) ';
				} else {
					echo $this->parameters['find_user_message'];
				}
				echo '<input type=hidden name="'.$prefix.'process_type" value="user_search">';
				echo text_box($prefix.'user_search',$user->login,15,64, "class=data", $autos);
				break;
			case 'pword': case 'pword_l':
				echo '<table cellpadding="0" cellspacing="0">';
				echo '<input type="hidden" name="'.$prefix.'process_type" value="edit_user">';
				echo '<input type="hidden" name="'.$prefix.'edit_user" value="'.$user->id.'">';
				if ($format == 'pword_l') {
					$login = $this->formatted_value($value, 'login');
					echo '<tr><td><b>Login</b></td><td>'.$login.'</td></tr>';
				}
				echo '<tr><td colspan="2"><b>Modify Password</b><input type="checkbox" name="'.$prefix.'pw_mod" value="1"></td></tr>';
				echo '<tr><td><b>Password</b></td><td>'.password_box($prefix.'password','',$this->get_value('width'),'','class="data"').'</td></tr>';
				echo '<tr><td><b>Password Verification</b></td><td>'.password_box($prefix.'password_verification','',$this->get_value('width'),'','class="data"').'</td></tr>';
				echo '</table>';
				break;
			case 'is':
				echo '<table cellpadding="0" cellspacing="0">';
				echo '<input type="hidden" name="'.$prefix.'category" value="'.$categoryid.'">';
				echo '<input type="hidden" name="'.$prefix.'process_type" value="create_edit_user">';
				foreach($this->get_value('user_details') as $detail) {
					switch($detail) {
						case 'login':
							# handle it if autocomplete is on
							$autocomplete = $this->get_value('autocomplete');
							if($autocomplete) {
								$autos = $this->get_autos($autocomplete);
							}
							echo '<tr><td><b>Login</b></td><td>'.text_box($prefix.'login',$user->login,$this->get_value('width'),'','class="data"', $autos).'</td></tr>';
						break;
						case 'password':
							echo '<input type="hidden" name="'.$prefix.'pw_mod" value="1">';
							echo '<tr><td><b>Password</b></td><td>'.password_box($prefix.'password','',$this->get_value('width'),'','class="data"').'</td></tr>';
							echo '<tr><td><b>Password Verification</b></td><td>'.password_box($prefix.'password_verification','',$this->get_value('width'),'','class="data"').'</td></tr>';
						break;
						case 'firstname':
							echo '<tr><td><b>Firstname</b></td><td>'.text_box($prefix.'firstname',$user->firstname,$this->get_value('width'),'','class="data"').'</td></tr>';
						break;
						case 'surname':
							echo '<tr><td><b>Surname</b></td><td>'.text_box($prefix.'surname',$user->surname,$this->get_value('width'),'','class="data"').'</td></tr>';
						break;
						case 'email':
							echo '<tr><td><b>Email</b></td><td>'.text_box($prefix.'email',$user->email,$this->get_value('width'),'','class="data"').'</td></tr>';
						break;
						case 'mobile_no':
							echo '<tr><td><b>Mobile Number</b></td><td>'.text_box($prefix.'mobile_no',$user->mobile_no,$this->get_value('width'),'','class="data"').'</td></tr>';
						break;
						case 'organisationid':
							echo '<tr><td><b>Organisation</b></td><td>'.combo_box($prefix.'organisationid',$this->org_array_with_sticks($this->get_value('organisationid')),$user->organsationid).'</td></tr>';
						break;
						case 'activity':
							echo '<tr><td><b>Account Expiry</b></td><td>'.date_box($prefix.'expiry_date',(($user->expiry_date) ? $user->expiry_date : ''),'edit').'</td></tr>';
							echo '<tr><td><b>Web Status</b></td><td>'.combo_box($prefix.'web_status',$user->web_statii,$user->web_status,'',20).'</td></tr>';
						break;
					}
				}
				echo '</table>';
				break;
				case 'activity':
					echo '<table cellpadding="0" cellspacing="0">';
					echo '<input type="hidden" name="'.$prefix.'process_type" value="edit_user">';
					echo '<input type="hidden" name="'.$prefix.'edit_user" value="'.$user->id.'">';
					echo '<tr><td><b>Account Expiry</b></td><td>'.date_box($prefix.'expiry_date',(($user->expiry_date) ? $user->expiry_date : ''),'edit').'</td></tr>';
					echo '<tr><td><b>Web Status</b></td><td>'.combo_box($prefix.'web_status',$user->web_statii,$user->web_status,'',20).'</td></tr>';
					echo '</table>';
					break;
				break;
		}
	}

	#############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value,$format) {
		$this->print_concise_field($prefix,$value,$format);
		if($note = &$this->get_value("note")) {
			echo("<span class=smallprint><br>$note</span>");
		}
	}

	/**
	* Processes changes to an attribute
	*
	* @param  string  $prefix                 The attribute changing prefix
	* @param  &string $value_destination      The current value
	* @param  string  $format                 Don't remember
	* @param  &string &$rules_broken_messages Var to hold broken rull messages
	* @access public
	* @return string
	*/
	function process_field($prefix, &$value_destination, $format, &$rules_broken_messages) {
		$value_destination = (int) $value_destination;
		$processing_type = $_REQUEST[$prefix.'process_type'];
		$categoryid = $_REQUEST[$prefix.'category'];
		$users_sys = &get_users_system();
		$userid = $_REQUEST[$prefix.'edit_user'];

		# Creating User or editing
		if ($processing_type == 'create_edit_user' || $processing_type == 'edit_user') {
			if ($processing_type == 'create_edit_user') {
				$user = new User();
				$login = $_REQUEST[$prefix.'login'];
				# Set to default org and title first
				$this->create_user($user, $login, $rules_broken_messages);
				$userid = $user->id; 

				# Set any of the stuff from the current users account to the new user that's selected
				$session = &get_mysource_session();
				if ($session->logged_in()) {
					$submitting_user = &$users_sys->get_user($session->user->id);
					$copy_posters_settings = $this->get_value('copy_posters_settings');
					# Comments
					if (in_array('C', $copy_posters_settings)) {
						$user->set_comments($submitting_user->comments);
					}
					# Mobile Number
					if (in_array('M', $copy_posters_settings)) {
						$user->set_mobile_no($submitting_user->mobile_no);
					}
					# Firstname
					if (in_array('FN', $copy_posters_settings)) {
						$user->set_firstname($submitting_user->firstname);
					}
					# Surname
					if (in_array('S', $copy_posters_settings)) {
						$user->set_surname($submitting_user->surname);
					}
					# Email address
					if (in_array('E', $copy_posters_settings)) {
						$user->set_email($submitting_user->email);
					}
					# Account Expiry
					if (in_array('AE', $copy_posters_settings)) {
						$user->set_expiry_date($submitting_user->expiry_date);
					}
					# Web status
					if (in_array('WS', $copy_posters_settings)) {
						$user->set_web_status($submitting_user->web_status);
					}
				}
			}

			# Could be editing or continuing an edit at this point
			$user = &$users_sys->get_user($userid);
			if($user->id) {
				# Password
				if (in_array('password', $this->get_value('user_details')) && ($format == 'pword_l' || $format == 'pword' || $format == 'is')) {
					$password = $_REQUEST[$prefix.'password'];
					$password_verification = $_REQUEST[$prefix.'password_verification'];
					# This will always be true in case of create and sometimes in case of edit
					if ($_REQUEST[$prefix.'pw_mod']) {
						$error_message = $user->set_password($password, $password_verification);
						if ($error_message != 'Password changed.') {
							$rules_broken_messages = $this->add_broken_rule_message($rules_broken_messages, $error_message);
							if ($processing_type == 'create_edit_user') {
								# Generate password
								$password = random_password(8);
								$error_message = 'Password now '.$password;
								$rules_broken_messages = $this->add_broken_rule_message($rules_broken_messages, $error_message);
								$user->set_password($password, $password);
							}
						}
						# Yes we broke a password rule but in this case it's ok to keep the value
						$value_destination = $user->id;
					}
				}

				# Firstname
				if(in_array('firstname',$this->get_value('user_details'))) {
					$firstname = $_REQUEST[$prefix.'firstname'];
					$ms[] = $user->set_firstname($firstname);
				}
				# Surname
				if(in_array('surname',$this->get_value('user_details'))) {
					$surname = $_REQUEST[$prefix.'surname'];
					$ms[] = $user->set_surname($surname);
				}
				# Email
				if(in_array('email',$this->get_value('user_details'))) {
					$email = $_REQUEST[$prefix.'email'];
					$ms[] = $user->set_email($email);
				}
				# Mobile Number
				if(in_array('mobile_number',$this->get_value('user_details'))) {
					$mobile_number = $_REQUEST[$prefix.'mobile_number'];
					$ms[] = $user->set_mobile_no($mobile_number);
				}

				if (in_array('organisationid',$this->get_value('user_details'))) {
					$orgid = $v['organisationid'];
					$title = $this->get_value('title');
				} else {
					if ($processing_type == 'create_edit_user') {
						# Frontitia gave us categoryid
						$orgid = $this->get_value('organisationid');
						# Lets kill the default affiliation setting
						$user->delete_affiliation($orgid);
						$title = $this->get_value('title');
						if($categoryid) {
							$lower_organistations_titles = $this->get_value('lower_orgs_n_titles');
							if($lower_organistations_titles[$categoryid]['org']) {
								$orgid = $lower_organistations_titles[$categoryid]['org'];
							}
							if($lower_organistations_titles[$categoryid]['title']) {
								$title = $lower_organistations_titles[$categoryid]['title'];
							}
						}
					}
				}
				if ($orgid && $title) {
					$ms[] = $user->add_affiliation($orgid,$title);
				}

				if (in_array('activity',$this->get_value('user_details')) && $format == 'activity') {
					$expiry_date = $_REQUEST['year_'.$prefix.'expiry_date'].'-'.$_REQUEST['month_'.$prefix.'expiry_date'].'-'.$_REQUEST['day_'.$prefix.'expiry_date'];
					$web_status = $_REQUEST[$prefix.'web_status'];
					$ms[] = $user->set_activity($expiry_date, $web_status);
				}
			}
		} elseif ($processing_type == 'user_search') {
			# Searching for a user
			$user_search = $_REQUEST[$prefix.'user_search'];
			$user = &$users_sys->get_user($user_search, false, $this->get_value('allow_numeric_names'));
		}

		$this->validate_value($user->id, $rules_broken_messages);
		if ($rules_broken_messages) {
			return 'Rule broken in attribute '.$this->attribute->name.'. '.$rules_broken_messages;
		} else {
			$value_destination = $user->id;
			return implode("\n",$ms);
		}
	}
	
	/**
	* Checks the value is valid
	*
	* @param  string &$value            The value we need to check against the rules
	* @param  string &$rule_broken_msgs The broken rule messages
	* @access public
	* @return void
	*/
	function validate_value(&$value, &$rule_broken_msgs, $validation_type='none') {
		//if importing and value has pipes | in it then we need to do some user editing/creating
		if ($validation_type == 'importing' && substr_count($value, '|')) {
			$user_import_values = explode('|',$value);
			
			# login meant to be at position 0 in the array
			$login = $user_import_values[0];

			$users_sys = &get_users_system();
			$user = &$users_sys->get_user($login, false, $this->get_value('allow_numeric_names'));
			if(!$user->id) {
				//create user
				# Set to default org and title first
				$this->create_user($user, $login, $rule_broken_msgs);
			}

			# Setup users details
			reset($user_import_values);
			foreach($this->get_value('user_details') as $detail) {
				$current_import_value = current($user_import_values);
				switch($detail) {
					case 'login': //do nothing already handled
					break;
					case 'password':
						# only update the password if the value is not null
						if($current_import_value != null && $current_import_value != '') {
							$errors = $user->set_password($current_import_value, $current_import_value);
							if ($errors != 'Password changed.') {
								$error_message = $errors;
							}
						}
					break;
					case 'firstname':
						$error_message = $user->set_firstname($current_import_value);
					break;
					case 'surname':
						$error_message = $user->set_surname($current_import_value);
					break;
					case 'email':
						$error_message = $user->set_email($current_import_value);
					break;
					case 'mobile_no':
						$error_message = $user->set_mobile_no($current_import_value);
					break;
					case 'organisationid':
						if ($this->get_value('specify_organisation_import')) {
							$orgname = $current_import_value;
							$org_array_with_sticks = $this->org_array_with_sticks();
							foreach ($org_array_with_sticks as $org_with_stick) {
								if (preg_match("/$orgname$/",$org_with_stick)) {
									$orgid = array_search($org_with_stick,$org_array_with_sticks);
								}
							}
							next($user_import_values);
							$title = current($user_import_values);
						} else {
							$orgid = $this->get_value('organisationid');
							$title = $this->get_value('title');
						}
						if ($orgid && $title && $orgid != '' && $title != '') {
							$current_orgs = array_keys($user->affiliations);
							if (!in_array($orgid,$current_orgs)) {
								$error_message .= $user->add_affiliation($orgid,$title);
							}
						}
						else {
							$error_message = 'A vald organisation id and title was not found';
						}
					break;
					case 'activity':
						$expiry_date = $current_import_value;
						next($user_import_values);
						$web_status = current($user_import_values);
						$error_message = $user->set_activity($expiry_date,$web_status);
					break;
				}
				$rule_broken_msgs = $this->add_broken_rule_message($rule_broken_msgs,$error_message);
				next($user_import_values);
			}

			# set $value so it is contains a userid
			$value = $user->id;
		}

		#check for rules so we need the rules array
		$this->validate_value_rules($value, $rule_broken_msgs);
	}

	 ###################################################
	# Returns a query object which can be given back to
	# print_search_field() or get_search_sql_clause()
	function &process_search_field($prefix,$format,$not_null) {
		if($not_null) $userid = 0;
		else $userid = '';
		switch($format) {
			case 'id':
				$v = $_REQUEST[$prefix.'quser_search'];
				if(strlen($v) == 0) return $q;
				$this->process_field($prefix.'q',$userid);
				break;
			default:
				break;
		}
		return $userid;
	}

	 ##############################################
	# Returns an SQL WHERE subclause for searching
	function get_search_sql_clause($query,$format,$value_string='v.value') {
		$match_clause = '0=1';
		switch($format) {
			case 'id':
				$match_clause = $value_string.'="'.$query.'"';
				break;
			default:
				break;
		}
		return $match_clause;
	}


	 ###############################################################
	# Returns an SQL expression that evalutates to a relevancy score
	function get_search_sql_score($query,$format,$value_string='v.value') {
		return;
	}

	 ###########################################################
	# Returns a hieracrhcy of all the organisations with sticks
	function org_array_with_sticks($parentid) {
		$users_sys = &get_users_system();
		$org_array = &$users_sys->org_array_with_sticks(false,$parentid);
		if(count($org_array)) return array(''=>'') + $org_array;
	}

	/**
	* Gets the user not found message value. Using this for backwards compatibility
	*
	* @access  public
	* @returns string
	*/
	function get_user_not_found_message() {
		if (!isset($this->parameters['user_not_found_message'])) {
			return '[User not found]<br />';
		} else {
			return $this->parameters['user_not_found_message'];
		}
	}

	/**
	* Sets the user not found message value. Using this for backwards compatibility
	*
	* @param   string $input The text
	* @access  public
	* @returns string
	*/
	function set_user_not_found_message($input) {
		if ($this->parameters['user_not_found_message'] != $input) {
			$this->parameters['user_not_found_message'] = $input;
			return 'User Not set message has been updated';
		}
	}

	/**
	* Gets the find user message. Using this for backwards compatibility
	*
	* @access  public
	* @returns string
	*/
	function get_find_user_message() {
		if (!isset($this->parameters['find_user_message'])) {
			return 'Find:(login/email/name) ';
		} else {
			return $this->parameters['find_user_message'];
		}
	}

	/**
	* Gets the find user message. Using this for backwards compatibility
	*
	* @param   string $input The text
	* @access  public
	* @returns string
	*/
	function set_find_user_message($input) {
		if ($this->parameters['find_user_message'] != $input) {
			$this->parameters['find_user_message'] = $input;
			return 'Find user message has been modified';
		}
	}

	/**
	* Gets the auto completes making sure comments in the current user equals the comments in the returning users
	*
	* @param   int $autocomplete Number of autocomplete letters to limit to
	* @access  public
	* @returns array
	*/
	function get_autos($autocomplete) {
		$users_system = &get_users_system();
		$user_db = &$users_system->get_db();
		$org_id = $this->get_value('organisationid');

		if (!empty($org_id)) {
			# users in a certain organisation
			return $user_db->single_column("SELECT SUBSTRING(a.login,1,$autocomplete) FROM user a, affiliation b WHERE a.userid=b.userid AND b.organisationid = {$org_id} ORDER BY a.login");
		} else {
			# all users
			return $user_db->single_column("SELECT SUBSTRING(login,1,$autocomplete) FROM user ORDER BY login");
		}
	}

	/**
	* Only show the loer category customising if level is greater than zero
	*
	* @access  public
	* @returns boolean
	*/
	function show_lower_category_customising() {
		$lower_levels = $this->get_value('lower_levels');
		return ($lower_levels > 0);
	}

	/**
	* Prints a list of categories below this one at levels and inputs for organisations and titles
	*
	* @access  public
	* @returns void
	*/
	function print_lower_orgs_and_titles() {
		$lower_organistations_titles = $this->get_value('lower_orgs_n_titles');
		$lower_levels = $this->get_value('lower_levels');
		$organisations = $this->org_array_with_sticks();
		$tree = &$this->get_category_tree();
		?>
		<table>
		<?
		for($i = 0; $i < $lower_levels; $i++) {
			$category_at_level = $tree->get_descendantids_at_depth($this->attribute->categoryid, ($i+1));
		?>
			<tr><td><b>Category Name</b></td><td><b>Organisation</b></td><td><b>Title</b></td></tr>
		<?
			foreach($category_at_level as $categoryid) {
				$category = &$this->get_category($categoryid);
				$org_select = combo_box('orgs_'.$categoryid, $organisations, $lower_organistations_titles[$categoryid]['org']);
				$title = text_box('titles_'.$categoryid, $lower_organistations_titles[$categoryid]['title'], 20);
		?>
			<tr><td><?=$category->name?></td><td><?=$org_select?></td><td><?=$title?></td></tr>
		<?
			}
		}
		?>
			<tr><td colspan="3"><i>Leave Organisation and Title blank if you wish it to inherit the default. NOTE: This feature is only available to external templates if it's supported. It will not work within Notitia.</i></td></tr>
		</table>
		<?
	}

	/**
	* Processes the orgs n titles
	*
	* @access  public
	* @returns string
	*/
	function process_lower_orgs_and_titles() {
		$lower_organistations_titles = &$this->get_value('lower_orgs_n_titles');
		$lower_levels = $this->get_value('lower_levels');
		$tree = &$this->get_category_tree();
		$msgs = '';

		for($i = 0; $i < $lower_levels; $i++) {
			$category_at_level = $tree->get_descendantids_at_depth($this->attribute->categoryid, ($i+1));
			foreach($category_at_level as $categoryid) {
				if ($lower_organistations_titles[$categoryid]['org'] != $_POST['orgs_'.$categoryid]) {
					$lower_organistations_titles[$categoryid]['org'] = $_POST['orgs_'.$categoryid];
					$msgs .= 'Organisation value changed<br>';
				}
				if ($lower_organistations_titles[$categoryid]['title'] != $_POST['titles_'.$categoryid]) {
					$lower_organistations_titles[$categoryid]['title'] = $_POST['titles_'.$categoryid];
					$msgs .= 'Title value changed<br>';
				}
			}
		}
		return $msgs;
	}

	/**
	* Returns all the user statii
	*
	* @access  public
	* @returns array
	*/
	function get_all_user_statii() {
		$user = new User();
		return $user->web_statii;
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null) {
		switch($format) {
			case 'id': default:
				$this->print_concise_field($prefix.'q',$default);
				break;
		}
	}

	/**
	* Sets in the request vars a static search
	*
	* @param   string $prefix Prefix
	* @param   string $value  A value
	* @param   string $format A format
	* @access  public
	* @returns void
	*/
	function set_search_request($prefix,$value,$format) {
		switch($format) {
			case 'id': default:
				$_REQUEST[$prefix.'process_type'] = 'user_search';
				$_REQUEST[$prefix.'user_search'] = $value;
				break;
		}
	}

	function create_user(&$user, $login,&$rules_broken_messages) {
		$maybe_use_msgs = $user->create($login,$this->get_value('orgainsationid'),$this->get_value('title'));
		
		$userid = $user->id;
		# Set defualt web status and expiry
		$user->set_expiry_date($this->get_value('account_expiry'));
		$user->set_web_status($this->get_value('web_status'));

		if (!$user->id) {
			# These error messages would be because somehow the user failed to be created so lets keep these
			foreach($maybe_use_msgs as $error_message) {
				$rules_broken_messages = $this->add_broken_rule_message($rules_broken_messages, $error_message);
			}
		}
	}
}
?>
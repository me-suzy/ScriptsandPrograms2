<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/ISC_user_reference/ISC_user_reference.inc,v $
## $Revision: 1.18 $
## $Author: nduggal $
## $Date: 2004/01/16 04:24:09 $
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/types/ISC_user_reference/ISC_user_reference.inc,v $
## $Revision: 1.18 $
## $Author: nduggal $
## $Date: 2004/01/16 04:24:09 $
#######################################################################

#---------------------------------------------------------------------#

 ############################
# For simple hyperlinked text
class Web_Extension_Notitia_Attribute_Type_ISC_User_Reference extends Web_Extension_Notitia_Attribute_Type {
	
	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => false,
			'value_formats' => array(
				'id' => 'User ID',
				'name' => 'User Name',
				'login' => 'User Login',
				'mailto' => 'Mailto',
				'firstname' => 'Firstname',
				'surname' => 'Surname',
				'email' => 'Email',
			),
			'search_formats' => array(
				'id' => 'ID',
			),
			'edit_formats' => array (
				'raw' => 'Standard',
				'is' => 'Intranet Style',
				'pword_l' => 'Password Only W/Login Displayed (EDIT ONLY)',
				'pword' => 'Password Only (EDIT ONLY)'
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
				echo '<tr><td><b>Password</b></td><td>'.password_box($prefix."password",'',$this->get_value('width'),'','class="data"').'</td></tr>';
				echo '<tr><td><b>Password Verification</b></td><td>'.password_box($prefix."password_verification",'',$this->get_value('width'),'','class="data"').'</td></tr>';
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
							echo '<tr><td><b>Login</b></td><td>'.text_box($prefix."login",$user->login,$this->get_value('width'),'','class="data"', $autos).'</td></tr>';
						break;
						case 'password':
							echo '<input type="hidden" name="'.$prefix.'pw_mod" value="1">';
							echo '<tr><td><b>Password</b></td><td>'.password_box($prefix."password",'',$this->get_value('width'),'','class="data"').'</td></tr>';
							echo '<tr><td><b>Password Verification</b></td><td>'.password_box($prefix."password_verification",'',$this->get_value('width'),'','class="data"').'</td></tr>';
						break;
						case 'firstname':
							echo '<tr><td><b>Firstname</b></td><td>'.text_box($prefix."firstname",$user->firstname,$this->get_value('width'),'','class="data"').'</td></tr>';
						break;
						case 'surname':
							echo '<tr><td><b>Surname</b></td><td>'.text_box($prefix."surname",$user->surname,$this->get_value('width'),'','class="data"').'</td></tr>';
						break;
						case 'email':
							echo '<tr><td><b>Email</b></td><td>'.text_box($prefix."email",$user->email,$this->get_value('width'),'','class="data"').'</td></tr>';
						break;
						case 'mobile_no':
							echo '<tr><td><b>Mobile Number</b></td><td>'.text_box($prefix."mobile_no",$user->mobile_no,$this->get_value('width'),'','class="data"').'</td></tr>';
						break;
						case 'organisationid':
							echo '<tr><td><b>Organisation</b></td><td>'.combo_box($prefix."organisationid",$this->org_array_with_sticks($this->get_value('organisationid')),$user->organsationid).'</td></tr>';
						break;
					}
				}
				echo '</table>';
				break;
		}
	}

	#############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value,$format) {
		$this->print_concise_field($prefix,$value,$format);
		if($note = &$this->get_value("note")) {
			echo '<span class="smallprint"><br />'.$note.'</span>';
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
				$maybe_use_msgs = $user->create($login,$this->get_value('organisationid'),$this->get_value('title'));
				$userid = $user->id;

				if (!$user->id) {
					# These error messages would be because somehow the user failed to be created so lets keep these
					foreach($maybe_use_msgs as $error_message) {
						$rules_broken_messages = $this->add_broken_rule_message($rules_broken_messages, $error_message);
					}
				}

				# Set any of the stuff from the current users account to the new user that's selected
				$session = &get_mysource_session();
				if ($session->logged_in()) {
					$submitting_user = &$users_sys->get_user($session->user->id);
					$user->set_comments(addslashes($submitting_user->comments));
					$user->set_mobile_no(addslashes($submitting_user->mobile_no));
				}
			}

			# Could be editing or continuing an edit at this point
			$user = &$users_sys->get_user($userid);
			if($user->id) {
				# Password
				if (in_array('password', $this->get_value('user_details'))) {
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
				
				if(in_array('mobile_number',$this->get_value('user_details'))) {
					$mobile_number = $_REQUEST[$prefix.'mobile_number'];
					$ms[] = $user->set_mobile_no($mobile_number);
				}
				if (in_array('organisationid',$this->get_value('user_details'))) {
					$orgid = $v['organisationid'];
					$title = $this->get_value('title');
				}
				if ($orgid && $title) {
					$user->add_affiliation($orgid,$title);
				}
			}
		} elseif ($processing_type == 'user_search') {
			# Searching for a user
			$user_search = $_REQUEST[$prefix.'user_search'];
			$user = &$users_sys->get_user($user_search);
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
	function validate_value(&$value, &$rule_broken_msgs) {
		#check for rules so we need the rules array
		$this->validate_value_rules($value, $rule_broken_msgs);

		# do normal checks
		$value = (int) $value;
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
				$match_clause = $value_string.' = "'.$query.'"';
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
		$db = &$this->get_db();
		$users_sys = &get_users_system();
		$org_id = $this->get_value('organisationid');
		# get the comments of the currently logged in user
		$session = &get_mysource_session();
		if ($session->logged_in()) {
			$id = $session->user->id;
			$logged_in_user = &$users_sys->get_user($session->user->id);
			$comments = $logged_in_user->comments;
		}
		if (!$comments) {
			$comments = 'NULL';
		} else {
			$comments = '\''.$comments.'\'';
		}

		# extra condition in here matches if the logged in user has the same comments as the finding user
		if (!empty($org_id)) {
			# users in a certain organisation
			return $db->single_column("SELECT SUBSTRING(a.login,1,$autocomplete) FROM user a, affiliation b WHERE a.comments = $comments AND a.userid=b.userid AND b.organisationid = $org_id ORDER BY a.login");
		} else {
			# all users
			return $db->single_column("SELECT SUBSTRING(login, 1, $autocomplete) FROM user WHERE comments = $comments ORDER BY login");
		}
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
	* @param   string $format A search format
	* @access  public
	* @returns void
	*/
	function set_search_request($prefix,$value,$format) {
		switch($format) {
			case 'id':
				$_REQUEST[$prefix.'qprocess_type'] = 'user_search';
				$_REQUEST[$prefix.'quser_search'] = $value;
				break;
			default:
				break;
		}
	}
}
?>
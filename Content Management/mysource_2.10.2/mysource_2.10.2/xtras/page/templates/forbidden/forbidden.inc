<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## File: xtras/page/templates/squiz_imb/squiz_imb.inc
## Desc: A template wich presents a discussion board
## $Source: /home/cvsroot/xtras/page/templates/forbidden/forbidden.inc,v $
## $Revision: 1.19 $
## $Author: dofford $
## $Date: 2004/02/27 01:21:53 $
#######################################################################
global $INCLUDE_PATH, $SQUIZLIB_PATH;
include_once("$INCLUDE_PATH/page_template.inc");
include_once("$INCLUDE_PATH/parameter_set.inc");
include_once("$INCLUDE_PATH/html_body_format.inc");
include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
include_once("$SQUIZLIB_PATH/form/form.inc");
#---------------------------------------------------------------------#


class Page_Template_Forbidden extends Page_Template {

	 ##############################################################
	# Lots of interesting options and stuff!
	var $parameters = array();

	 ##############################
	# Constructor
	function Page_Template_Forbidden($pageid) {
		return Page_Template::Page_Template($pageid);
	}


	 ######################################################################
	# Create a new record in the databse and load it into this object
	function create($pageid) {
		$message = Page_Template::create($pageid);
		if(!$this->id)	return false;
		$db = &$this->get_db();
		# Set the title to the title of the current page.
		$page = &$this->get_page($pageid);

		$db->update("UPDATE $this->table_name SET parameters='".addslashes(serialize(array()))."' WHERE pageid='$pageid'");
		$id = $this->load($pageid);
		return $message;
	}



	 ################################################
	# Loads the temaplte details, from cache or database
	function load($pageid) {
		if ($pageid <= 0 && !($pageid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load $this->name page template without a valid pageid.",__FILE__,__LINE__);
		}

		 #################
		# Check the cache 
		if ($this->load_from_cache($pageid)) return $pageid;

		 ########################
		# Load from the database
		$db = &$this->get_db();
		list($this->id, $tmp) = $db->single_row("SELECT pageid, parameters FROM $this->table_name WHERE pageid='$pageid'");
		$this->parameters = unserialize($tmp);


		 #####################################
		# Create a new record if none exists
		if (!$this->id) {
			return $this->create($pageid);
		}

		$this->set_generated_information();

		 ###################
		# Save to the cache 
		$this->save_to_cache($pageid);
		return $pageid;
	}

	/**
	* Remaps stuff that page_template won't be able to decide if it should be remapped or not
	*
	* @param   array $dupe_map The dupe map array
	* @access  public
	* @returns void
	*/
	function template_defined_remapper($dupe_map) {
		$from_page_ids = array_keys($dupe_map['page']);
		$login_redirect = &$this->get_value('login_redirect');
		if (in_array($login_redirect, $from_page_ids)) {
			$login_redirect = $dupe_map['page'][$login_redirect];
			$this->save_parameters();
		}
	}

	 #########################################
	# Grabs a user record from the user system
	function &get_user($userid) {
		$user_system = &get_users_system();
		return $user_system->get_user($userid);
	}


	 #############
	# Duplicating
	function dupe($new_pageid) {
		return Page_Template::dupe($new_pageid);
	}

	 ###################
	# CHanging the ID
	function newid($new_pageid) {
		return Page_Template::newid($new_pageid);
	}

	 ###################
	# Deleting the page
	function delete() {
		return Page_Template::delete();
	}


	 ##################################################
	# Grabs keywords out of the title and bodycopy etc
	function extract_keywords() {
		$db = &$this->get_db();
		foreach($this->parameters['copy'] as $code => $bit) {
			if(substr(stripslashes($bit),0,14) == 'O:8:"bodycopy"') {
				$bodycopy = new BodyCopy($bit, $code);
				$copy .= strip_tags_padded($bodycopy->get_html())." ";
			} else $copy .= "$bit ";
		}
		return $this->parameters['title']." ".$copy;
	}

	 ################################
	# Just verifies an option is set
	function check_bool($code,$parameter) {
		return in_array($code,$this->parameters[$parameter]);
	}

	 #####################################
	# grabs the value for a valued option
	function &get_value($code) {
		return $this->parameters[$code];
	}

	 ######################################
	# Sets a parameter value
	function set_value($code,$value) {
		$this->parameters[$code] = $value;
		$this->save_parameters();
	}
	 
	 ######################################################
	# Returns an associaive array of universal replacements
	# These can be used to replace special keywords in copy
	function &get_universal_replacements($descs) {
		$session = &get_mysource_session();

		$k = &Page_Template::get_universal_replacements();
		$d = &Page_Template::get_universal_replacements(1);

		if($this->get_value('remember_login')) if (!$l = $session->user->login) $l = $session->recall_login();
		$d['username_input'] = "Username input box";
		$k['username_input'] = "<input type=text name='mysource_login' value='$l' size=".$this->get_value('username_input_size')." maxlength=64 onfocus='javascript: this.select();'>";

		$d['password_input']  = "Password input box";
		$k['password_input']  = "<input type=password name='mysource_password' value='' size=".$this->get_value('password_input_size')." maxlength=64 onfocus='javascript: this.select();'>";

		$d['login_button'] = "Login button";
		$k['login_button'] = "<input type=button value='".(($this->get_value('login_label'))?$this->get_value('login_label'):'Login')."' onclick='if (document.forbidden.mysource_login.value.length > 0 && document.forbidden.mysource_password.value.length > 1".(($this->get_value('display_disclaimer') == 'same')?' && document.forbidden.disclaimer_checkbox.checked':'').")document.forbidden.submit();'>";

		$d['logout_button'] = "Logout button";
		$k['logout_button'] = "<input type=submit value='".(($this->get_value('logout_label'))?$this->get_value('logout_label'):'Logout')."'>";

		$d['access_reject_message'] = "Access reject message (will be preceded by the access reject label)";

		$url_info = $this->web_system->get_url_info($this->web_system->current_url());
		if($url_info['p'] != $this->id) {
			global $ACCESS_REJECT_MESSAGE;
			$secure_page = &$this->get_page($url_info['p']);
			$secure_page->read_access();
			$access_reject_message = $ACCESS_REJECT_MESSAGE;
		}
		$k['access_reject_message'] = (($ACCESS_REJECT_MESSAGE)?$this->get_value('access_reject_label').' '.$ACCESS_REJECT_MESSAGE:'');

		$d['session_message'] = "Session message (will be preceded by the session message label)";
		$k['session_message'] = (($session->message())?$this->get_value('session_message_label').' '.$session->message():'');

		$d['disclaimer_checkbox'] = 'A checkbox which must be checked before a person can continue.';
		$k['disclaimer_checkbox'] = '<input type=checkbox name=disclaimer_checkbox checked>';

		$d['disclaimer_button'] = 'A button which the user clicks after agreeing to the disclaimer';
		$k['disclaimer_button'] = '<input type=button name=disclaimer_button value="'.(($this->get_value('disclaimer_button_label'))?$this->get_value('disclaimer_button_label'):'Accept').'" onclick="if(document.forbidden.disclaimer_checkbox.checked) document.forbidden.submit();">';

		if($descs) return $d;
		return $k;
	}

	 ##################################################################
	# Prints the backend for the user - usually completely overwritten
	function print_backend() {

		$backend = &$this->setup_backend();

		$pset = &$this->get_pset();
		$pset->set_tabs($backend,"template_screen", $this->get_backend_href());
		if($pset->process($backend)) {
			$this->save_parameters();
			$this->updated();
		}
		$pset->print_backend($backend);

	}

	 ###########################################################
	# Returns the title as it should be printed on the frontend
	function get_title() {
		return $this->get_value("title");
	}

	 ###########################################################################
	# This is where the REAL frontend happens. We like to be in the right scope
	function print_body() {
		$session = &get_mysource_session();
		$page = &$this->get_page();
	
		if($_POST['mysource_session_action'] == 'login') {
			if($this->parameters['login_redirect'] && $session->logged_in()) {
				$login_redirect = &$this->get_page($this->parameters['login_redirect']);
				echo '<script language=javascript>window.location="'.$login_redirect->get_url().'"</script>';
			}
			unset($_POST['next_page']);
			unset($_POST['mysource_login']);
			unset($_POST['mysource_login_key']);
			unset($_POST['mysource_password']);
			unset($_POST['mysource_session_action']);
		}

		if($_POST['mysource_session_action'] == 'logout') {
			if($this->parameters['logout_redirect'] && !$session->logged_in()) {
				$logout_redirect = &$this->get_page($this->parameters['logout_redirect']);
				echo '<script language=javascript>window.location="'.$logout_redirect->get_url().'"</script>';
			}
			unset($_POST['next_page']);
			unset($_POST['mysource_login']);
			unset($_POST['mysource_login_key']);
			unset($_POST['mysource_password']);
			unset($_POST['mysource_session_action']);
		}

		if(!$session->logged_in()) {
			######################################################################################
			# Take all the post vars and whack em in the form - important ones will get overwitten
			?>
			<form action="<? echo $_SERVER['PHP_SELF'].(($_SERVER['QUERY_STRING'])?"?".ereg_replace("[\&]?mysource_session_action=[^\&]+","",$_SERVER['QUERY_STRING']):"") ?>" name=forbidden method=post>
			<?
			unset($_POST['mysource_session_action']);			
			echo var_to_hidden_field("",$_POST,1);
			echo var_to_hidden_field('next_page',1);			

			if($_POST['next_page']) {
				unset($_POST['next_page']);
				echo var_to_hidden_field('mysource_login_key',$session->new_login_key());
				echo var_to_hidden_field('mysource_session_action','login');
				switch($this->get_value('display_disclaimer')) {
					case 'before':
						$this->print_bodycopy('login_invite');
						break;
					case 'after':
						$this->print_bodycopy('disclaimer');
						break;
				}
			} else {
				switch($this->get_value('display_disclaimer')) {
					case 'before':
						$this->print_bodycopy('disclaimer');
						break;
					case 'same':
						echo var_to_hidden_field('mysource_login_key',$session->new_login_key());
						echo var_to_hidden_field('mysource_session_action','login');
						$this->print_bodycopy('disclaimer');
						$this->print_bodycopy('login_invite');
						break;
					case 'after':
						$this->print_bodycopy('login_invite');
						break;
					default:
						echo var_to_hidden_field('mysource_login_key',$session->new_login_key());
						echo var_to_hidden_field('mysource_session_action','login');
						$this->print_bodycopy('login_invite');
						break;
				}
			}

			?>
			</form>
			<?
		} else {
			?>
			<form action="<?= $page->get_url(true).(($_SERVER['QUERY_STRING'])?"?".ereg_replace("[\&]?mysource_session_action=[^\&]+","",$_SERVER['QUERY_STRING']):"") ?>" method=post>
			<input type=hidden name=mysource_session_action value="logout">
			<?
			$this->print_bodycopy('logged_in');
			?>
			</form>
			<?
		}
	}

	 ###############################
	# Is the disclaimer turned on
	function display_disclaimer() {
		return $this->parameters['display_disclaimer'];
	}

	 #########################################
	# Prints a bit o' copy
	function print_bodycopy($code,$replacements) {
		$bodycopy = new BodyCopy($this->parameters[copy][$code], $code);
		$bodycopy->add_replace_keywords($this->get_universal_replacements());
		$bodycopy->add_replace_keywords($replacements);
		$bodycopy->paint();
	}

}

?>

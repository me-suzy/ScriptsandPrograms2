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
## File: include/session.inc
## Desc: Deals with maintaining sessions, and loggin people in an out.
## $Source: /home/cvsroot/mysource/include/session.inc,v $
## $Revision: 2.42.2.1 $
## $Author: achadszinow $
## $Date: 2004/04/14 05:02:17 $
#######################################################################

#---------------------------------------------------------------------#


class MySourceSession extends Object {

	var $registered_name = 'SESSION';      # The variable name of this object, so it can register itself
	var $login_attempts  = array();       # A counter of logins attempts under different usernames
	var $login_key       = '';           # The current random key expected at login
	var $new_login_key   = '';           # The previous random key expected at login
	var $last_access     = 0;           # Timestamp set the last time the user accessed the page
	var $user            = '';         # The currently logged-in user
	var $result_message  = '';        # The results of anything going on with logging in/out
	var $external_vars   = array();  # Allows external systems to stick there vars in the sessions
	var $access_groups   = array(); # Access groups the current user reside in.
	#var $editor_pages    = array();# Pages this use has "page editor" read access too array($siteid => array($pageid,...),..)

	 ##################################################
	# This is your first time? Let's get you settled in
	function MySourceSession($object_name='SESSION') {
		if ($object_name) {
			$this->registered_name = $object_name;
		}
	}

	 #################################
	# Get the session up and running
	function start() {
		if (!$this->last_access) {
			# This is our first visit to the system today
			session_register($this->registered_name);
		}
		$this->result_message = '';

		 #########################################
		# Anything happening here?
		$mysource_session_action = $_REQUEST['mysource_session_action'];

		switch($mysource_session_action) {
			 #############################################
			# User is trying to log in
			case 'login':
				$mysource_login = $_POST['mysource_login'];
				$mysource_password = $_POST['mysource_password'];
				$mysource_login_key = $_POST['mysource_login_key'];
				if (!$mysource_login) {
					$this->add_message('Please supply a login name.');
				} elseif ($this->user->login == $mysource_login) {
						$this->add_message('You are already logged in.');
				} else {
					# May this login be remembered forever!  (3 months)
					$this->remember_login($mysource_login);
					$this->add_message($this->login($mysource_login, $mysource_password, $mysource_login_key));
				}
				break;

			 #################################
			# User is trying to log out.
			case 'logout':
				$this->add_message($this->logout());
				break;

			 #############################
			# User is just cruisin' along
			default:
				 ################################################################
				# If someone is logged in, lets make sure they haven't timed out
				$system_config = &get_system_config();
				if($this->logged_in() && $system_config->max_idle_time > 0 && (time() - $this->last_access) > $system_config->max_idle_time) {
					$this->logout();
					$idle_mins = floor($system_config->max_idle_time / 60);
					$this->add_message("You have been logged out because your account has been idle for more than $idle_mins minutes.");
				}

				# So.. is there a web system around here?
				if (!$this->last_access && $system_config->web_db_details['name']) {
					$WEB = &get_web_system(); 
					# if we are meant to, Log this session
					if ($system_config->log_visitors && $system_config->statistics_reporter) {
						$stat_reporter = &get_statistics_reporter('session');
						$stat_reporter->log_hit(array('fileid' => $file->id));
					}#end if
					if ($system_config->log_visitors && $system_config->statistics_reporter) {
						$stat_reporter = &get_statistics_reporter('session');
						$stat_reporter->log_hit();
					}#end if
				}
				break;
		}

		 ####################
		# Remember this time
		$this->last_access = time();

		if (!$this->logged_in()) $this->user = "";
		
	}


	 #############################
	# Is somebody logged in?
	function logged_in() {
		return ($this->user->id > 0);
	}

	
	 ########################################
	# Makes a new login key and returns it
	function new_login_key() {
		if($this->get_var('new_login_key') && strlen($this->new_login_key) == 10) {
			return $this->new_login_key;
		}
		$random = random_password(10);
		$this->login_key = $random;
		$this->set_var('new_login_key', $random);
		return $this->login_key;
	}

	  #############################################################
	 # Prints out a small login screen, to appear in a popup box #
	#############################################################

	function login_prompt($heading='',$l='',$p='') {
		if (!$l) $l = $this->recall_login();
		?>
		<html>
		<head>
			<title>Login</title>
			<link rel="STYLESHEET" type="text/css" href="<?=lib_href("css/edit.css")?>">
		</head>
		<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 bgcolor=#dddddd>
		<form action="<?= $_SERVER['PHP_SELF']."?".ereg_replace("[\&]?mysource_session_action=[^\&]+","",$_SERVER['QUERY_STRING']) ?>" name=login_prompt method=post>
		<table width=200 bgcolor=#dddddd cellpadding=2 cellspacing=0 border=0>
			<tr bgcolor=#773377>
				<td colspan=2 nowrap><p class=field style="color: #ffffff"><? echo ellipsisize($heading,25) ?></td>
			</tr>
			<tr>
				<td align=right class=field>Login:</td>
				<td><input type=text name="mysource_login" value="<?=$l?>" size=10 maxlength=64 class=data onfocus="javascript: this.select();"></td>
			</tr>
			<tr>
				<td align=right class=field>Password:</td>
				<td><input type=password name="mysource_password" value="<?=$p?>" size=10 maxlength=64 class=data onfocus="javascript: this.select();">
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td align=right><input type=button value="Log In" class=button onclick="if (login_prompt.mysource_login.value.length > 0 && login_prompt.mysource_password.value.length > 1){window.opener.run_mysource_login(get_l(),get_p(),get_k());window.close();}" class=field></td>
			</tr>
		</table>
		<input type=hidden name=mysource_login_key value="<?=$this->new_login_key()?>">
		<script language=javascript>
			document.login_prompt.<? echo ((strlen($l)>0)?"mysource_password":"mysource_login") ?>.focus();
			function get_l() {return document.login_prompt.mysource_login.value;}
			function get_p() {return document.login_prompt.mysource_password.value;}
			function get_k() {return document.login_prompt.mysource_login_key.value;}
		</script>
		</form>
		</body>
		</html>
		<?
	}

	  ###################################################################################################
	 # Prints out a standard login screen, requesting a username and password, with parametric message #
	###################################################################################################

	function login_screen($heading='', $message='', $l='', $p='') {
		global $IN_BACKEND;
		$mysource_login_referer = $_POST['mysource_login_referer'];

		if(!$IN_BACKEND) {
			$web_system = &get_web_system();
			$site = &$web_system->get_site();
			if($site->id && $site->forbidden_pageid) {
				$page = &$web_system->get_page($site->forbidden_pageid);
				if($page->id) {
					if ($page->read_access()) {
						$web_system->set_current_pageid($page->id);
						$page->print_frontend(true);
						exit();
					} else {
						$this->add_message('Unable to use Forbidden Page #'.$page->id.', because you do not have read access'); 
					}
				}
			}
			# Do we need ssl encryption
			# If so...try and get an ssl url
			$system_config = &get_system_config();
			if($system_config->require_ssl_login && url_protocol() != 'https') {
				if($page = &$web_system->get_page()) {
					if($page->get_url(false,1) != $page->get_url(false,2)) { 
						header('Location: '.$page->get_url(false,1));
						exit;
					}
				}
			}
		}

		global $INCLUDE_PATH, $EDIT_PATH;
		include_once("$INCLUDE_PATH/html_general.inc");
		include_once("$INCLUDE_PATH/html_form.inc");

		if(!$mysource_login_referer) $mysource_login_referer = $_SERVER['HTTP_REFERER'];

		 ###############################################################
		# If the user is logged in, show their login name in the prompt
		if (!$l) $l = $this->user->login;
		if (!$l) $l = $this->recall_login();

		 ################################
		# Have we got a mesage for them?
		if(!$message && !$this->message()) {
			$message = "<B>Welcome.</B> This is a secured site for authorised users only.<BR>Please enter your login name and password.";
		}

		if (!PHP_CGI) {
			header("HTTP/1.0 403 Forbidden");
		}

		?>
		<html>
		<head>
			<title><? echo $heading ?></title>
			<link rel="STYLESHEET" type="text/css" href="<?=lib_href("css/edit.css")?>">
		</head>
		<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 bgcolor=#ffffff>
		<form action="<?= $_SERVER['PHP_SELF']."?".ereg_replace("[\&]?mysource_session_action=[^\&]+","",$_SERVER['QUERY_STRING']) ?>" name="login_prompt" method="post">
		<?
		 ######################################################################################
		# Take all the post vars and whack em in the form - important ones will get overwitten
		unset($_POST['mysource_login']);
		unset($_POST['mysource_password']);
		unset($_POST['mysource_login_key']);
		unset($_POST['mysource_login_referer']);
		unset($_POST['mysource_session_action']);
		echo var_to_hidden_field("",$_POST,1);

		if (is_file($EDIT_PATH.'/mysource_login.html')) {
			# found the login template - so print their custom login

			if ($this->logged_in()) { # Inform of a failed attempt and things
				$status = 'Currently logged in as: '.$this->user->name();
			} else {
				$status = 'Not currently logged in.';
			}
			if ($this->message()) { # Inform of a failed attempt and things
				$system_message = $this->message();
			}

			
			$login_code = file_to_string($EDIT_PATH.'/mysource_login.html');
			$login_code = str_replace('%login%', $l, $login_code);
			$login_code = str_replace('%password%', $p, $login_code);
			$login_code = str_replace('%user_message%', $message, $login_code);
			$login_code = str_replace('%system_message%', $system_message, $login_code);
			$login_code = str_replace('%status%', $status, $login_code);
			$login_code = str_replace('%heading%', $heading, $login_code);
			echo $login_code;
		} else {
			# cant find the login template - so print a default
			?>
			<table height=100% width=100%><tr><td align=center valign=middle>
				<table width=400 bgcolor=#ffffff cellpadding=1 cellspacing=0 border=1 bordercolor=#773377 align=center>
					<tr>
						<td bgcolor=#773377><p class=field style="color: white"><?=$heading?></td>
					</tr>
					<tr>
						<td>
							<table bgcolor=#dddddd cellpadding=2 cellspacing=0 border=0 width=100%>
								<? if ($this->logged_in()) { # Inform of a failed attempt and things ?>
								<tr bgcolor=#bbbbbb>
									<td valign=top colspan=2><p style="color: #0000dd; font-weight: bold;">Currently logged in as: <?=$this->user->name()?></td>
								</tr>
								<? } else { ?>
								<tr bgcolor=#bbbbbb>
									<td valign=top colspan=2><p style="color: #0000dd; font-weight: bold;">Not currently logged in.</td>
								</tr>
								<? } ?>
								<? if ($this->message()) { # Inform of a failed attempt and things ?>
								<tr>
									<td valign="top" colspan="2"><p style="color: #dd0000; font-weight: bold;"><?=nl2br($this->message())?></td>
								</tr>
								<? } ?>
								<tr>
									<td valign=top colspan=2 class=message><?=$message ?><br><br></td>
								</tr>
								<tr>
									<td valign=top colspan=2>
									<table width="100%" border="0">
										<tr>
											<td rowspan=3 valign=bottom>
												<input type=button value="<- Back" onclick="location='<?=$mysource_login_referer?>'" class=field>
											</td>
											<td align=right class=field>Login:</td>
											<td><input type=text name="mysource_login" value="<?=$l?>" size=10 maxlength=64 class=data onfocus="javascript: this.select();"></td>
											<td rowspan=2 valign=bottom align=right>
												&nbsp;
											</td>
										</tr>
										<tr>
											<td align=right class=field>Password:</td>
											<td><input type=password name="mysource_password" value="<?=$p?>" size=10 maxlength=64  class=data onfocus="javascript: this.select();"></td>
										</tr>
										<tr>
											<td colspan=2 class=field>
												&nbsp;
											</td>
											<td valign=bottom align=right>
												&nbsp;<input type="submit" value="Log In" onclick="if (login_prompt.mysource_login.value.length > 0 && login_prompt.mysource_password.value.length > 1)document.login_prompt.submit();"  class=field>
											</td>
										</tr>
									</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td></tr></table>
		<?
		}
		?>
		
		<script language=javascript>
			document.login_prompt.<? echo (($l)?"mysource_password":"mysource_login") ?>.focus();
		</script>
		<input type=hidden name=mysource_login_key value="<?=$this->new_login_key()?>">
		<input type=hidden name=mysource_login_referer value="<?=$mysource_login_referer?>">
		<input type=hidden name=mysource_session_action value="login">
		</form>
		</body>
		</html>
		<?
		exit();
	}



	  ##############################################
	 # Logs a user in and establishes the session #
	##############################################

	function login($login='',$password='',$key='') {
		$system_config = &get_system_config();
		$users_system = &get_users_system();

		 ########################################################################
		# Have they supplied the expected login key that was given out to them?
		if ($this->login_key && $key != $this->login_key) {
			return "Login key incorrect.";
			$this->unset_var('new_login_key');
		}
		unset($this->login_key);

		 #####################################
		# Okay, lets see if we can find them
		if ($login && $password) {
			$this->user = new User();
			if (!$this->user->load_with_login($login,$password)) {
				return $this->login_attempt($login);
			}
		} else {
			return "Please enter a login and password.";
		}	

		 ################################################################
		# Yes they were right. Now which web access groups do they belong to?
		if ($system_config->web_db_details['name']) { # So.. is there a web system around here?
			$WEB = &get_web_system();
			$this->access_groups = Access_Group::get_user_access_groups($this->user);
			# (this isnt needed anymore)
			# And which pages do they have special editor read privlidges for?
			#$this->editor_pages = $WEB->db->associative_column("SELECT page.siteid, page.pageid FROM page_editor LEFT JOIN page ON page_editor.pageid=page.pageid WHERE page_editor.userid='{$this->user->id}'");
		}

		 #########
		# Hooray!
		$this->unset_var('new_login_key');

		return "User logged in: ".$this->user->name()." ({$this->user->login})";
	}


	 ##########################################################
	# Records the number of failed login attempts for a login
	# Return false if they've exceeded the limit.
	function login_attempt($login='') {

		$system_config = &get_system_config();
		$users_system = &get_users_system();
		$bad_login_mesage = $system_config->bad_login_attempt_text;
		if (empty($bad_login_mesage)) {
			$bad_login_mesage = "Authentication credentials incorrect or account locked. Please contact your website administrator for assistance.";
		}

		 ##########################################################
		# Now how many times has this joker tried to get in today?
		$name = $users_system->db->single_element("SELECT concat(firstname,' ',surname) FROM user WHERE login='".addslashes($login)."'");

		if (!$name) {
			return $bad_login_mesage;
		}

		$lock_userid = $users_system->db->single_element("SELECT userid FROM user WHERE login='".addslashes($login)."'");
		$lock_user = new User($lock_userid);
		if (!user_root($lock_userid) && $lock_user->web_status != 'A') {
			$this->logout();
			return $bad_login_mesage;
		}

		if (!user_root($lock_userid) && ($lock_user->expiry_date != '0000-00-00' && $lock_user->expiry_date < date("Y-m-d"))) {
			$this->logout();
			$message = $lock_user->set_web_status("E");
			unset($lock_user);
			return 'Your account has expired. Please contact your web administrator.';
		}

		if($system_config->max_login_attempts > 0 && $login && (!in_array($login,$system_config->superusers['all']))) { # Superuserz rule
			if(!isset($this->login_attempts[$login])) {
				$this->login_attempts[$login] = 1;
			} else {
				$this->login_attempts[$login]++;
			}

			 ############################################
			# To many login attempts and you're banished
			if($this->login_attempts[$login] == $system_config->max_login_attempts) {
				if($lock_user->id) {
					mail($lock_user->name()." <$lock_user->email>", "Account locked", "Dear $lock_user->firstname,\n\nYour MySource account has been locked due to ".(int)$system_config->max_login_attempts." unsuccessful\nlogin attempts in a row.\n\nAccount Login: $login\nIP Address: ".$_SERVER['REMOTE_ADDR']."\nURL: ".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."\n\nThis is an automated message.", "From:$system_config->webmaster_email");

					mail("$system_config->webmaster_email", "Account locked", "This MySource account has been locked due to ".(int)$system_config->max_login_attempts." unsuccessful\nlogin attempts.\n\nAccount Login: $login\nIP Address: ".$_SERVER['REMOTE_ADDR']."\nURL: ".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."\n\nThis is an automated message.", "From: ".$lock_user->name()." <$lock_user->email>", "-f$lock_user->email");
					$message = $lock_user->set_web_status("L");
					return $bad_login_mesage;
				}
			} elseif (($tries_left = $system_config->max_login_attempts - $this->login_attempts[$login]) <= 3 && $tries_left >= 0) {
				return $bad_login_mesage;
			}
		}
		return $bad_login_mesage;
	}


	  #########################################
	 # Logs a user out and kills the session #
	#########################################

	function logout() {
		if (!$this->logged_in()) return "You were not logged in.";
		$name = $this->user->name();
		$this->user = array();
		unset($this->user);
		$this->access_groups = array();
		$files_sent = &$this->get_var("files_sent");
		$files_sent = array();
		$this->unset_var('new_login_key');
		return "User '$name' logged out.";
	}

	  ########################
	 # Set the current user #
	########################
	function set_current_user($userid) {
		if(!$userid) return;
		$users_system = &get_users_system();
		$user = &$users_system->get_user($userid);
		if($user->id) {
			$this->user = &$user;
			return $this->user->id;
		}
		return false;
	}

	 ##############################################
	# Appends a message to the "Result message"
	function add_message($new='') {
		if(!$new) return;
		if($this->result_message) $this->result_message .= "\n";
		$this->result_message .= $new;
		return $this->result_message;
	}

	 ####################################
	# Returns the current message
	function message() {
		return $this->result_message;
	}

	 ########################################################
	# Sets a cookie to remember a login name for a long time
	function remember_login($login='') {
		list($old_login,$stamp) = explode("|",$_COOKIE['MySourceLoginCookie']);
		if($login != $old_login || $stamp < strtotime("-1 month")) {
			# Refresh the cookie every month or so
			$MySourceLoginCookie = "$login|".time();
			return setcookie("MySourceLoginCookie",$MySourceLoginCookie,strtotime("+3 months"),"/",$_SERVER['HTTP_HOST']);
		}
	}

	 ########################################################
	# Sets a cookie to remember a login name for a long time
	function recall_login() {
		list($login,$stamp) = explode("|",$_COOKIE['MySourceLoginCookie']);
		return $login;
	}
	
	 #####################################################################################
	# Allows an external system to dump its var in here so they hang around in the session
	function &set_var($name='',&$var) {
		$this->external_vars[$name] = &$var;
		return $this->get_var($name);
	}


	 #####################################################################################
	# Allows an external system to dump its var in here so they hang around in the session
	function unset_var($name='') {
		unset($this->external_vars[$name]);
		return !isset($this->external_vars[$name]);
	}


	 #####################################################################################
	# Allows an external system to grab vars its stored in here and edit it
	function &get_var($name='') {
		return $this->external_vars[$name];
	}

	 #####################################################################################
	# Does this var exist in the session or not?
	function has_var($name='') {
		return isset($this->external_vars[$name]);
	}

}

 ###########################################
# This stuff has to be run or it won't work
global $CACHE_PATH;
$users_system = &get_users_system();
# Make sure that we are using files to save the session data
ini_set('session.save_handler', 'files');
session_save_path($CACHE_PATH);

session_name('MySourceSession');

 ####################################################################
# Sessions set through the GET variables get preference over cookies
$sname = session_name();

if ($_COOKIE[$sname] && $_GET[$sname]) {
	$sname = $_GET[$sname];
	setcookie($sname, $_GET[$sname]);
}

# Do we want allow persistent sessions
$system_config = &get_system_config();
if($system_config->persistent_sessions) {
	ini_set('session.gc_maxlifetime',$system_config->persistent_sessions_lifetime * 60);

	$remote_addr = ip_to_bin($_SERVER['REMOTE_ADDR']);

	for($i=0;$i<count($system_config->persistent_sessions_ip_addresses);$i++) {

		$allow_addr = ip_to_bin(substr($system_config->persistent_sessions_ip_addresses[$i],0,strpos($system_config->persistent_sessions_ip_addresses[$i],'/')));

		$allow_mask = substr($system_config->persistent_sessions_ip_addresses[$i],strpos($system_config->persistent_sessions_ip_addresses[$i],'/')+1);

		# If no mask...then set it to 32
		if(!$allow_mask) $allow_mask = 32;

		# If the remote address and allowed address match for the length of the mask...then set the persistent session
		if(substr($remote_addr,0,$allow_mask) === substr($allow_addr,0,$allow_mask)) {
			ini_set('session.cookie_lifetime',$system_config->persistent_sessions_lifetime * 60);
			break;
		}
	}
}

session_start();

?>

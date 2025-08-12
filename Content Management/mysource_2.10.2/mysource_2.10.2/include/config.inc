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
## Desc: A class which handles the management of the MySource configuration
## $Source: /home/cvsroot/mysource/include/config.inc,v $
## $Revision: 2.66.2.1 $
## $Author: achadszinow $
## $Date: 2004/04/14 04:57:00 $
#######################################################################


#---------------------------------------------------------------------#

class Config extends CacheableObject {

	var $conf_path = '';
	var $conf_file = '';
	var $id = 0;
	var $conf_parser_files = '';

	# Must supply a unique ID for this file for caching purposes
	function Config($id=0,$conf_file='') {
		$this->id = $id;
		$this->conf_path = dirname($conf_file);
		$this->conf_file = basename($conf_file);

		// do this to stop php errors - missing values from parameter_set.inc load fn.
		$null = null;
		$this->load($null);

	}

	function __sleep() {
		$result = array_keys(get_object_vars($this));
		array_remove_element('conf_parser_files',$result);
		return $result;
	}

	  #################################################
	 # Function that streamlines conf parsing
	##########################################
	function get_conf_line($file = '', $ignore_indenting=true) {
		$f = &$this->conf_parser_files[$file];
		if (!$f) {
			if (!is_file($file)) {
				report_error(__FILE__,__LINE__, "File does not exist: $file");
				exit();
			}

			if (!$f['ptr'] = fopen($file,'r')) {
				report_error(__FILE__,__LINE__,"Permission denied: $file");
				exit();
			}

			$f['line'] = 0;
		}
		if(!empty($f['include'])) {
			if($line = $this->get_conf_line($f['include'])) {
				return $line;
			} else {
				unset($f['include']);
			}
		}
		while($line = fgets($f['ptr'],4096)) {
			$f['line']++;
			if ($ignore_indenting) $line = trim($line); # Ignore indenting
			if (!$line || $line[0] == '#' || $line[0] == ';') continue; # Ignore blanks and comments

			 ##################
			# Special commands
			if($line[0] == '@') {
				$command = substr(trim(strtok($line," \t")),1);
				$args    = trim(strtok("\n"));
				switch($command) {
				case 'include':
					if($args[0] == '/') {
						$f['include'] = $args;
					} else {
						$f['include'] = dirname($file).'/'.$args;
					}
					return $this->get_conf_line($f['include']);
					break;
				default:
					break;
				}
				continue;
			}

			return strtok($line,'#');
		}
		fclose($f['ptr']);
		unset($f);
		return '';
	}


	  #################################
	 # Another conf parsing function #
	#################################
	function get_conf_line_no($file='') {
		return $this->conf_parser_files[$file]['line'];
	}

}


 ############################################################
# Loads a general MySource configuration object and returns
# a reference to it
function &get_config($name='') {
	global $CONFIGURATIONS;
	$name = strtolower($name);
	$class = "Config_$name";
	if(!isset($CONFIGURATIONS[$name]) || get_class($CONFIGURATIONS[$name]) != strtolower($class)) {
		global $CONF_PATH;
		$CONFIGURATIONS[$name] = new $class("mysource_$name","$CONF_PATH/$name.conf");
	}
	return $CONFIGURATIONS[$name];
}


 ##################################
# The main MySource Configuration
class Config_MySource extends Config {

	  ######################
	 # Some default values
	########################
	var $system_name = 'MySource System';
	var $system_owner = 'System Owner';
	var $webmaster_email = '';
	var $errors_email = '';
	var $backend_message_box_focus = 0;
	var $backend_log_messages = '';
	var $backend_brand_image = '';
	var $xss_security = 0;
	var $jupload_path;
	var $squiz_server_details = array('path'=>'','systemid'=>'','domain'=>'','port'=>'');


	var $superusers = array('all' => array(), 'web' => array(), 'users' => array());

	var $max_idle_time	= 6000;
	var $bad_login_attempt_text = '';
	var $max_login_attempts = 3;
	var $require_ssl_login = 0;
	var $log_visitors = 0;
	var $log_remote_hosts = 0;
	var $statistics_reporter = '';
	var $persistent_sessions = 0;
	var $persistent_sessions_ip_addresses = array();
	var $persistent_sessions_lifetime = 0;

	var $web_db_details = array('name'=>'','host'=>'','login'=>'','password'=>'', 'querylog'=>'');

	var $virtual_paths  = 0;
	var $strict_url_matching = 0;

	var $email_page_update = 0;
	var $backend_suffix = 'edit';
	var $default_language = '';
	var $default_charset = '';
	var $illegal_filetypes = 'php php3 php4 php5 php6 inc act';
	var $site_backend_dhtml_navigation = 0;
	var $mysource_help = 0;
	var $default_table_cell_type = 'richtext';
	var $use_css_styles = 0;

	var $user_authentication = 'default';

	var $user_db_details	= array('name'=>'','host'=>'','login'=>'','password'=>'','querylog'=>'');

	var $check_db_mbstrings = Array();


	/**
	* Load config variables
	*
	* @returns string
	* @access public
	*/
	function load() {
		  ##########################
		 # Can we load from cache #
		##########################
		if ($this->load_from_cache($this->id)) {
			return $this->id;
		}

		$parse_error_log = '';

		while($line = $this->get_conf_line("$this->conf_path/$this->conf_file")) {
			# Grab the first word of the line
			$key = strtok($line," \t");

			switch($key) {
				#!!!!!!--> INSERT NEW CONFIGURATION VARS HERE - parsing, do not process
				case 'SystemName':
					$this->set_system_name(trim(strtok("\n")));
					break;
				case 'SystemOwner':
					$this->set_system_owner(trim(strtok("\n")));
					break;
				case 'ErrorsEmail':
					$this->set_errors_email(strtok(" \t"));
					break;
				case 'WebMasterEmail':
					$this->set_webmaster_email(strtok(" \t"));
					break;
				case 'BackendMessageBoxFocus':
					$this->set_backend_message_box_focus(strtok(" \t"));
					break;
				case 'BackendLogMessages':
					$this->set_backend_log_messages(strtok("\n"));
				case 'BackendBrandImage':
					$this->set_backend_brand_image(strtok("\n"));
					break;
				case 'XSSSecurity':
					$this->set_xss_security(strtok(" \t"));
					break;
				case 'JUploadPath':
					$this->set_jupload_path(strtok("\n"));
					break;
				case 'SquizServer':
					list($key,$path,$systemid,$domain,$port) = split("[ \t]",$line,5);
					$this->set_squiz_server_details($path,$systemid,$domain,$port);
					break;
				case 'BackendMySourceIframe':
					list($key, $url, $title) = split("[ \t]",$line, 3);
					$this->set_backend_mysource_iframe($url, $title);
					break;
				case 'SuperUsers':
					$tmp_logins = split("[ \t\,]+",$line);
					for($i = 1; $i < count($tmp_logins); $i++) {
						$this->add_superuser(trim($tmp_logins[$i]));
					}
					break;
				case 'MaxIdleTime':
					$this->set_max_idle_time(floor(strtok(" \t")));
					break;
				case 'BadLoginAttemptText':
					$this->set_bad_login_attempt_text(floor(strtok(" \t")));
					break;
				case 'MaxLoginAttempts':
					$this->set_max_login_attempts(floor(strtok(" \t")));
					break;
				case 'RequireSslLogin':
					$this->set_require_ssl_login(floor(strtok(" \t")));
					break;
				case 'LogVisitors':
					$this->set_log_visitors(floor(strtok(" \t")));
					break;
				case 'StatisticsReporter' :
					$this->set_statistics_reporter(strtok(" \t"));
					break;
				case 'LogRemoteHosts':
					$this->set_log_remote_hosts(floor(strtok(" \t")));
					break;
				case 'PersistentSessions':
					list($dummy,$persistent_sessions,$ip_addresses,$lifetime) = split("[ \t]",$line);
					$this->set_persistent_sessions($persistent_sessions,explode(',',$ip_addresses),$lifetime);
					break;
				case 'WebDatabase':
					list($key,$name,$host,$login,$password,$querylog) = split("[ \t]",$line);
					$this->set_web_db_details($name,$host,$login,$password,$querylog);
					break;
				case 'WebMasters':
					$tmp_logins = split("[ \t\,]+",$line);
					for($i = 1; $i < count($tmp_logins); $i++) {
						$this->add_web_master(trim($tmp_logins[$i]));
					}
					break;
				case 'VirtualPaths':
					$this->set_virtual_paths(strtok(" \t"));
					break;
				case 'BackendSuffix':
					$this->set_backend_suffix(strtok(" \t"));
					break;
				case 'StrictUrlMatching':
					$this->set_strict_url_matching(strtok(" \t"));
					break;
				case 'EmailPageUpdate':
					$this->set_email_page_update(strtok(" \t"));
					break;
				case 'DefaultFrontendLanguage':
					$this->set_default_language(strtok(" \t"));
					break;
				case 'DefaultTableCellType':
					$this->set_default_table_cell_type(strtok(" \t"));
					break;
				case 'DefaultCharacterSet':
					$this->set_default_charset(strtok(" \t"));
					break;
				case 'IllegalFiletypes':
					$this->set_illegal_filetypes(strtok("\n"));
					break;
				case 'SiteBackendDHTMLNavigation':
					$this->set_site_backend_dhtml_navigation(strtok(" \t"));
					break;
				case 'mysource_help':
					$this->set_mysource_help(strtok(" \t"));
					break;
				case 'CheckDBMBStrings':
					$str = strtok("\n");
					$this->check_db_mbstrings = (trim($str) != '') ? split("[ \t]+", $str) : Array();
					break;
				case 'UserDatabase':
					list($key,$name,$host,$login,$password,$querylog) = split("[ \t]",$line);
					$this->set_user_db_details($name,$host,$login,$password,$querylog);
					break;
				case 'UserLDAP': case 'AuthenticationType':
					# Backwards compatiblility
					break;
				case "UserAuthentication":
					list($key,$authentication) = split("[ \t]",$line);
					$this->set_user_authentication($authentication);
					break;
				case 'UserMasters':
					$tmp_logins = split("[ \t\,]+",$line);
					$num_logins = count($tmp_logins);
					for($i = 1; $i < $num_logins; $i++) {
						$this->add_user_master(trim($tmp_logins[$i]));
					}
					break;
				case 'BatchDelete':
					$this->set_batch_delete(floor(strtok(" \t")));
					break;
				case 'BatchDupe':
					$this->set_batch_dupe(floor(strtok(" \t")));
					break;
				case 'UseCSSStyles':
					$this->set_use_css_styles(floor(strtok(" \t")));
					break;
				default:
					$parse_error_log .= 'Unrecognised keyword: "'.$key.'" on line '.$this->conf_parser_files[$this->conf_file]['line'].' of ' . $this->conf_file . '<br />';
					break;
				#<--!!!!!!
			}
		}

		# Errors found, die.
		if ($parse_error_log) {
			report_error(__FILE__,__LINE__,"<B>Parsing configuration file:</b><br><br>$parse_error_log");
			exit();
		}

		# End parsing configuration file

		# Write the cache
		$this->save_to_cache($this->id);

		return $this->id;

	}

	 #########################################################
	# functions for setting and deleting there variables

	function set_system_name($name='') {
		if($name == $this->system_name) return;
		$this->system_name = $name;
		return "System Name changed to: $name";
	}

	function set_system_owner($owner='') {
		if($owner == $this->system_owner) return;
		$this->system_owner = $owner;
		return "System Owner changed to: $owner";
	}

	function set_webmaster_email($email='') {
		if($email == $this->webmaster_email) return;
		$this->webmaster_email = $email;
		return "Webmaster Email set to: $email";
	}

	function set_errors_email($email='') {
		if($email == $this->errors_email) return;
		$this->errors_email = $email;
		return "Errors Email set to: $email";
	}

	function set_backend_message_box_focus($bool=false) {
		$bool = strtolower(trim($bool));
		if(in_array("$bool",array('y','yes','on','1'))) {
			if($this->backend_message_box_focus) return;
			$this->backend_message_box_focus = 1;
			return 'Backend Message Box Focus activated';
		} else {
			if(!$this->backend_message_box_focus) return;
			$this->backend_message_box_focus = 0;
			return 'Backend Message Box Focus deactivated';
		}
	}
	
	function set_xss_security($bool=false) {
		$bool = strtolower(trim($bool));
		if(in_array("$bool",array('y','yes','on','1'))) {
			if($this->xss_security) return;
			$this->xss_security = 1;
			return 'XSS Security activated';
		} else {
			if(!$this->xss_security) return;
			$this->xss_security = 0;
			return 'XSS Security deactivated';
		}
	}

	function set_backend_log_messages($path='') {
		if($this->backend_log_messages == $path) return;
		$this->backend_log_messages = $path;
		return (($path)?'Backend Log Messages activated':'Backend Log Messages deactivated');
	}

	function set_backend_brand_image($image='') {
		if($image == $this->backend_brand_image) return;
		$this->backend_brand_image = $image;
		return 'Brand Image '.(($this->backend_brand_image) ? '' : 'de').'activated';
	}

	function set_jupload_path($path='') {
		if($path == $this->jupload_path) return;
		if($this->jupload_path = $path) {
			return 'Updated JUpload path.';
		} else {
			return 'JUpload disabled.';
		}
	}

	function set_backend_mysource_iframe($url='',$title='') {
		if($url == $this->backend_mysource_iframe['url']
			&& $host  == $this->backend_mysource_iframe['title']
			) return;

		$this->backend_mysource_iframe['url']   = $url;
		$this->backend_mysource_iframe['title'] = $title;
		return 'Backend MySource Iframe details updated.';
	}

	function set_squiz_server_details($path='',$systemid='',$domain='',$port='') {
		if($path == $this->squiz_server_details['path'] && $systemid == $this->squiz_server_details['systemid'] && $domain == $this->squiz_server_details['domain'] && $port == $this->squiz_server_details['port']) return;
		$this->squiz_server_details['path'] = $path;
		$this->squiz_server_details['systemid'] = $systemid;
		$this->squiz_server_details['domain'] = $domain;
		$this->squiz_server_details['port'] = $port;
		return 'Squiz Server details updated.';
	}

	function set_web_db_details($name='',$host='',$login='',$password='',$querylog='') {
		if(!$name || !$host || !$login) return 'Database name, host and login are required';
		if($name == $this->web_db_details['name']
			&& $host  == $this->web_db_details['host']
			&& $login == $this->web_db_details['login']
			&& $password == $this->web_db_details['password']
			&& $querylog == $this->web_db_details['querylog']
			) return;
		# Try to connect
		global $SQUIZLIB_PATH;
		require_once("$SQUIZLIB_PATH/db/db.inc");
		$db = new Db($name,$login,$password,$host,$querylog);
		if(!$db->ptr) {
			 return 'Can not connect to database: Web Database details unchanged';
		} else {
			$db->disconnect();
		}
		$this->web_db_details['name']     = $name;
		$this->web_db_details['host']     = $host;
		$this->web_db_details['login']    = $login;
		$this->web_db_details['password'] = $password;
		$this->web_db_details['querylog'] = $querylog;
		return 'Web Database details updated.';
	}

	function set_default_language($lan='') {
		if($lan == $this->default_language) return;
		$this->default_language = $lan;
		$languages_config = &get_config('languages');
		$desc = substr($desc,0,-2);
		return 'Default Frontend Language set to: '.$languages_config->name_list($this->default_language);
	}

	function set_default_charset($charset='') {
		if($charset == $this->default_charset) return;
		$this->default_charset = $charset;
		$charsets_config = &get_config('charsets');
		return array(MYSOURCE_ERROR_CODE_NONE,"Default Character Set set to: {$charsets_config->charsets[$this->default_charset]}");
	}

	function set_illegal_filetypes($illegal_filetypes='') {
		if($illegal_filetypes == $this->illegal_filetypes) return;
		$this->illegal_filetypes = $illegal_filetypes;
		return array(MYSOURCE_ERROR_CODE_NONE,"Filetypes which may not be uploaded into MySource include now include: $this->illegal_filetypes");
	}

	function check_file_legality($filename='') {
		return !in_array(get_file_type($filename),preg_split("/[ \t\n\r]+/",$this->illegal_filetypes));
	}

	function set_check_db_mbstrings($check='') {
		if(equal_arrays($check, $this->check_db_mbstrings)) return;
		$this->check_db_mbstrings = $check;
		return array(MYSOURCE_ERROR_CODE_NONE,'Validation of multibyte strings from the database updated.');
	}

	function set_user_db_details($name='',$host='',$login='',$password='',$querylog='') {
		if(!$name || !$host || !$login) return array(MYSOURCE_ERROR_CODE_ERROR,'Database name, host and login are required');
		if($name == $this->user_db_details['name']
			&& $host == $this->user_db_details['host']
			&& $login == $this->user_db_details['login']
			&& $password == $this->user_db_details['password']
			&& $querylog == $this->user_db_details['querylog']
			) return;

		$db = new Db($name,$login,$password,$host,$querylog);
		if(!$db->ptr) {
			 return array(MYSOURCE_ERROR_CODE_ERROR,"Can not connect to database: User Database details unchanged");
		} else {
			$db->disconnect();
		}
		$this->user_db_details['name']     = $name;
		$this->user_db_details['host']     = $host;
		$this->user_db_details['login']    = $login;
		$this->user_db_details['password'] = $password;
		$this->user_db_details['querylog'] = $querylog;
		return array(MYSOURCE_ERROR_CODE_NONE,'User Database details updated.');
	}

	function add_superuser($login='') {
		if(!$login) return;
		if(in_array($login,$this->superusers['all'])) return array(MYSOURCE_ERROR_CODE_WARNING,"$login is already a Superuser");
		$msg = "Superuser added: $login";

		# See if they're listed as a web-master or user-master. It's rather redundant.
		if (in_array($login, $this->superusers['web'])) {
			$this->delete_web_master($login);
			$msg .= "\nSince $login is now a superuser, removed their webmaster status.";
		}
		if (in_array($login, $this->superusers['user'])) {
			$this->delete_user_master($login);
			$msg .= "\nSince $login is now a superuser, removed their usermaster status.";
		}
		$this->superusers['all'][] = $login;
		return array(MYSOURCE_ERROR_CODE_NONE,$msg);
	}

	function add_web_master($login='') {
		if(!$login) return;
		if(in_array($login,$this->superusers['web'])) return array(MYSOURCE_ERROR_CODE_WARNING,"$login is already a Web Master");

		# Are they a super-user? What's the point of adding them as a web-master?
		if(in_array($login,$this->superusers['all'])) return array(MYSOURCE_ERROR_CODE_WARNING,"$login is already a Superuser");

		# If they're a usermaster, and we're adding them as a webmaster, they're really a superuser.
		# Let's make it so.
		if (in_array($login, $this->superusers['user'])) return $this->add_superuser($login);

		$this->superusers['web'][] = $login;
		return array(MYSOURCE_ERROR_CODE_NONE,"Web Master added: $login");
	}

	function add_user_master($login='') {
		if(!$login) return;
		if(in_array($login,$this->superusers['user'])) return array(MYSOURCE_ERROR_CODE_WARNING,"$login is already a User Master");

		# Are they a super-user? What's the point of adding them as a user-master?
		if(in_array($login,$this->superusers['all'])) return array(MYSOURCE_ERROR_CODE_WARNING,"$login is already a Superuser");

		# If they're a webmaster, and we're adding them as a usermaster, they're really a superuser.
		# Let's make it so.
		if (in_array($login, $this->superusers['user'])) return $this->add_superuser($login);

		$this->superusers['user'][] = $login;
		return array(MYSOURCE_ERROR_CODE_NONE,"User Master added: $login");
	}

	function delete_superuser($login='') {
		if(!$login) return;
		if ($login == 'root') return array(MYSOURCE_ERROR_CODE_WARNING, "The root account must be a superuser");
		if(!in_array($login,$this->superusers['all'])) return array(MYSOURCE_ERROR_CODE_WARNING,"$login is not a Superuser");
		array_remove_element($login,$this->superusers['all']);
		return array(MYSOURCE_ERROR_CODE_NONE,"Superuser deleted: $login");
	}

	function delete_web_master($login='') {
		if(!$login) return;
		if(!in_array($login,$this->superusers['web'])) return array(MYSOURCE_ERROR_CODE_WARNING,"$login is not a Web Master");
		array_remove_element($login,$this->superusers['web']);
		return array(MYSOURCE_ERROR_CODE_NONE,"Web Master deleted: $login");
	}

	function delete_user_master($login='') {
		if(!$login) return;
		if(!in_array($login,$this->superusers['user'])) return array(MYSOURCE_ERROR_CODE_WARNING,"$login is not a User Master");
		array_remove_element($login,$this->superusers['user']);
		return array(MYSOURCE_ERROR_CODE_NONE,"User Master deleted: $login");
	}

	function set_max_login_attempts($attempts=0) {
		$attempts = max(0, (int)$attempts); # Must be a natural number
		if($attempts == $this->max_login_attempts) return;
		$this->max_login_attempts = $attempts;
		if($attempts) {
			return array(MYSOURCE_ERROR_CODE_NONE,"Maximum Allowed Login Attempts set to $attempts.");
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE,'Maximum Allowed Login Attempts: unlimited.');
		}
	}

	function set_max_idle_time($time=0) {
		$time = max(0,(int) $time);
		if ($time && $time < 60) $time = 60;
		if($time == $this->max_idle_time) return;
		$this->max_idle_time = $time;
		if($time) {
			return array(MYSOURCE_ERROR_CODE_NONE,"Maximum Idle Time set to $time seconds.");
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE,'Maximum Idle Time: unlimited.');
		}
	}

	function set_bad_login_attempt_text($text='') {
		if (empty($text)) $text = '';
		$text = trim($text);
		if($text == $this->bad_login_attempt_text) return;
		$this->bad_login_attempt_text = $text;
		if($text != '') {
			return array(MYSOURCE_ERROR_CODE_NONE,"Bad login attempt text changed to $text");
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE,'Bad login attempt text set to default');
		}
	}

	function set_require_ssl_login($bool=false) {
		$bool = strtolower(trim($bool));
		if(in_array($bool, array('y','yes','on','1'))) {
			if($this->require_ssl_login) return;
			$this->require_ssl_login = 1;
			return array(MYSOURCE_ERROR_CODE_NONE,'Login now requires SSL encryption.');
		} else {
			if(!$this->require_ssl_login) return;
			$this->require_ssl_login = 0;
			return array(MYSOURCE_ERROR_CODE_NONE,'Login no longer requires SSL encryption.');
		}
	}

	function set_log_visitors($bool=false) {
		$bool = strtolower(trim($bool));
		if(in_array($bool, array('y','yes','on','1'))) {
			if($this->log_visitors) return;
			$this->log_visitors = 1;
			return array(MYSOURCE_ERROR_CODE_NONE,'Now logging visitors (ie sessions as well as page and file hits).');
		} else {
			if(!$this->log_visitors) return;
			$this->log_visitors = 0;
			return array(MYSOURCE_ERROR_CODE_NONE,'No longer logging visitors (ie sessions as well as page and file hits).');
		}
	}

	function set_log_remote_hosts($bool=false) {
		$bool = strtolower(trim($bool));
		if(in_array("$bool",array('y','yes','on','1'))) {
			if($this->log_remote_hosts) return;
			$this->log_remote_hosts = 1;
			return array(MYSOURCE_ERROR_CODE_NONE,'Now logging remote hostnames for new sessions.');
		} else {
			if(!$this->log_remote_hosts) return;
			$this->log_remote_hosts = 0;
			return array(MYSOURCE_ERROR_CODE_NONE,'No longer logging remote hostnames.');
		}
	}

	function set_persistent_sessions($persistent_sessions=false,$ip_addresses=array(),$lifetime=0) {
		$lifetime = max(0,(int) $lifetime);
		$persistent_sessions = strtolower(trim($persistent_sessions));
		if(in_array($persistent_sessions,array('y','yes','on','1'))) {
			if($this->persistent_sessions && equal_arrays($ip_addresses,$this->persistent_sessions_ip_addresses) && $lifetime == $this->persistent_sessions_lifetime) return;
			$this->persistent_sessions = 1;
			$this->persistent_sessions_ip_addresses = $ip_addresses;
			$this->persistent_sessions_lifetime = $lifetime;
			return array(MYSOURCE_ERROR_CODE_NONE,'Persistent session details updated.');
		} else {
			if(!$this->persistent_sessions) return;
			$this->persistent_sessions = 0;
			return array(MYSOURCE_ERROR_CODE_NONE,'No longer using persistent sessions.');
		}
	}


	function set_statistics_reporter($statistics_reporter='') {
		$statistics_reporter = strtolower(trim($statistics_reporter));
		if($this->statistics_reporter == $statistics_reporter) return;
		$this->statistics_reporter = $statistics_reporter;
		return array(MYSOURCE_ERROR_CODE_NONE,"Statistics Reporter set to $statistics_reporter");
	}


	function set_virtual_paths($bool=false) {
		$bool = strtolower(trim($bool));
		if(in_array("$bool",array('y','yes','on','1'))) {
			if($this->virtual_paths) return;
			$this->virtual_paths = 1;
			return array(MYSOURCE_ERROR_CODE_WARNING,'Virtual Paths activated. Please ensure your web server configuration is correct.');
		} else {
			if(!$this->virtual_paths) return;
			$this->virtual_paths = 0;
			return array(MYSOURCE_ERROR_CODE_WARNING,'Virtual Paths deactivated.');
		}
	}

	function set_backend_suffix($suffix='') {
		$suffix = ereg_replace("/+",'',$suffix);
		if($suffix == $this->backend_suffix) return;
		$this->backend_suffix = $suffix;
		return array(MYSOURCE_ERROR_CODE_NONE,"Backend suffix set to: /$suffix");
	}

	function set_strict_url_matching($bool=false) {
		$bool = strtolower(trim($bool));
		if(in_array("$bool",array('y','yes','on','1'))) {
			if($this->strict_url_matching) return;
			$this->strict_url_matching = 1;
			return array(MYSOURCE_ERROR_CODE_NONE,'Strict URL matching activated.');
		} else {
			if(!$this->strict_url_matching) return;
			$this->strict_url_matching = 0;
			return array(MYSOURCE_ERROR_CODE_NONE,'Strict URL matching deactivated.');
		}
	}
	
	function set_email_page_update($bool=false) {
		$bool = strtolower(trim($bool));
		if(in_array("$bool",array('y','yes','on','1'))) {
			if($this->email_page_update) return;
			$this->email_page_update = 1;
			return array(MYSOURCE_ERROR_CODE_NONE,'Email Page Updates activated.');
		} else {
			if(!$this->email_page_update) return;
			$this->email_page_update = 0;
			return array(MYSOURCE_ERROR_CODE_NONE,'Email Page Updates deactivated.');
		}
	}

	function set_site_backend_dhtml_navigation($bool=false) {
		$bool = strtolower(trim($bool));
		if(in_array("$bool",array('y','yes','on','1'))) {
			if($this->site_backend_dhtml_navigation) return;
			$this->site_backend_dhtml_navigation = 1;
			return array(MYSOURCE_ERROR_CODE_NONE,'DHTML Navigation for Site Backends activated');
		} else {
			if(!$this->site_backend_dhtml_navigation) return;
			$this->site_backend_dhtml_navigation = 0;
			return array(MYSOURCE_ERROR_CODE_NONE,'DHTML Navigation for Site Backends deactivated');
		}
	}

	function set_mysource_help($bool=false) {
		$bool = strtolower(trim($bool));
		if(in_array("$bool",array('y','yes','on','1'))) {
			if($this->mysource_help) return;
			$this->mysource_help = 1;
			return array(MYSOURCE_ERROR_CODE_NONE,'MySource help for Site Backends activated');
		} else {
			if(!$this->mysource_help) return;
			$this->mysource_help = 0;
			return array(MYSOURCE_ERROR_CODE_NONE,'MySource help for Site Backends deactivated');
		}
	}

	function set_default_table_cell_type($type='') {
		$type = strtolower(trim($type));
		if($this->default_table_cell_type == $type) return;
		$this->default_table_cell_type = $type;
		return array(MYSOURCE_ERROR_CODE_NONE,"Default table cell type set to $type");
	}

	function set_use_css_styles($bool) {
		$bool = strtolower(trim($bool));
		if(in_array("$bool",array('y','yes','on','1'))) {
			if($this->use_css_styles) return;
			$this->use_css_styles = 1;
			return 'Now showing all CSS classes in WYSIWYG';
		} else {
			if(!$this->use_css_styles) return;
			$this->use_css_styles = 0;
			return 'No longer showing all CSS classes in WYSIWYG';
		}
	}

	function set_user_authentication($authentication='') {
		if (!$authentication || $authentication == $this->user_authentication) return;
		global $XTRAS;
		if($XTRAS->codename('users/authentication',$authentication)) {
			$this->user_authentication = $authentication;
			return array(MYSOURCE_ERROR_CODE_NONE,'User Authentication system changed');
		} else {
			return array(MYSOURCE_ERROR_CODE_WARNING,'Invalid User Authentication code');
		}
	}

	function set_batch_delete($batch_delete=0) {
		$batch_delete = max(0, (int)$batch_delete); # Must be a natural number
		if($batch_delete == $this->batch_delete) return;
		$this->batch_delete = $batch_delete;
		if($batch_delete) {
			return array(MYSOURCE_ERROR_CODE_NONE,"Batch Delete Pages set to $batch_delete.");
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE,'Batch Delete Pages: using default');
		}
	}

	function set_batch_dupe($batch_dupe=0) {
		$batch_dupe = max(0, (int)$batch_dupe); # Must be a natural number
		if($batch_dupe == $this->batch_dupe) return;
		$this->batch_dupe = $batch_dupe;
		if($batch_dupe) {
			return array(MYSOURCE_ERROR_CODE_NONE,"Batch Dupe Pages set to $batch_dupe.");
		} else {
			return array(MYSOURCE_ERROR_CODE_NONE,'Batch Dupe Pages: using default');
		}
	}

	  ###############################################
	 # Rewrites the conf file with current variables
	################################################
	function conf_updated() {

		  ################################
		 # Delete old conf file and cache
		#################################

		$this->clear_cache($this->id);


		# Re-write new conf file
		if(!($fd = fopen("$this->conf_path/$this->conf_file",'w'))) {
			report_error(__FILE__,__LINE__,"Configuration file not found or permission denied: $this->conf_path/$this->conf_file");
			exit();
		}

		$conf_file = "SystemName $this->system_name\n\n";
		$conf_file .= "SystemOwner $this->system_owner\n\n";
		$conf_file .= "WebMasterEmail $this->webmaster_email\n\n";
		$conf_file .= "ErrorsEmail $this->errors_email\n\n";
		$conf_file .= "BackendMessageBoxFocus $this->backend_message_box_focus\n\n";
		$conf_file .= "BackendLogMessages $this->backend_log_messages\n\n";
		$conf_file .= "BackendBrandImage $this->backend_brand_image\n\n";
		$conf_file .= "XSSSecurity $this->xss_security\n\n";
		$conf_file .= "JUploadPath $this->jupload_path\n\n";

		if($this->squiz_server_details['path']) {
			$conf_file .= 'SquizServer '.$this->squiz_server_details['path'].' '.$this->squiz_server_details['systemid'].' '.$this->squiz_server_details['domain'].' '.$this->squiz_server_details['port']."\n\n";
		}

		if ($this->backend_mysource_iframe['url']) {
			$conf_file .= 'BackendMySourceIframe '.$this->backend_mysource_iframe['url'].' '.$this->backend_mysource_iframe['title']."\n\n";
		}

		$conf_file .= 'WebDatabase '.$this->web_db_details['name'].' '.$this->web_db_details['host'].' '.$this->web_db_details['login'].' '.$this->web_db_details['password'].' '.$this->web_db_details['querylog']."\n\n";

		$conf_file .= 'UserDatabase '.$this->user_db_details['name'].' '.$this->user_db_details['host'].' '.$this->user_db_details['login'].' '.$this->user_db_details['password'].' '.$this->user_db_details['querylog']."\n\n";

		$conf_file .= 'UserAuthentication '.$this->user_authentication."\n\n";

		$conf_file .= 'SuperUsers ';
		for(reset($this->superusers['all']);$tmp_login=current($this->superusers['all']);next($this->superusers['all'])) {
			$conf_file .= $tmp_login.' ';
		}
		$conf_file .= "\n\n";
		$conf_file .= 'WebMasters ';
		if (!empty($this->superusers['web'])) {
			for(
				reset($this->superusers['web']);
				$tmp_login=current($this->superusers['web']);
				next($this->superusers['web'])
			) {
				$conf_file .= $tmp_login.' ';
			}
		}
		$conf_file .= "\n\n";
		$conf_file .= 'UserMasters ';
		if (!empty($this->superusers['user'])) {
			for(
				reset($this->superusers['user']);
				$tmp_login=current($this->superusers['user']);
				next($this->superusers['user'])
			) {
				$conf_file .= $tmp_login.' ';
			}
		}
		$conf_file .= "\n\n";
		$conf_file .= "MaxIdleTime $this->max_idle_time\n\n";
		$conf_file .= "BadLoginAttemptText $this->bad_login_attempt_text\n\n";
		$conf_file .= "MaxLoginAttempts $this->max_login_attempts\n\n";
		$conf_file .= "RequireSslLogin $this->require_ssl_login\n\n";
		$conf_file .= "LogVisitors $this->log_visitors\n\n";
		$conf_file .= "StatisticsReporter $this->statistics_reporter\n\n";
		$conf_file .= "LogRemoteHosts $this->log_remote_hosts\n\n";
		$conf_file .= 'PersistentSessions '.$this->persistent_sessions.' '.implode(',',$this->persistent_sessions_ip_addresses).' '.$this->persistent_sessions_lifetime."	\n\n";
		$conf_file .= "VirtualPaths $this->virtual_paths\n\n";
		$conf_file .= "BackendSuffix $this->backend_suffix\n\n";
		$conf_file .= "StrictUrlMatching $this->strict_url_matching\n\n";
		$conf_file .= "EmailPageUpdate $this->email_page_update\n\n";
		$conf_file .= "DefaultFrontendLanguage $this->default_language\n\n";
		$conf_file .= "DefaultTableCellType $this->default_table_cell_type\n\n";
		$conf_file .= "UseCSSStyles $this->use_css_styles\n\n";
		$conf_file .= "DefaultCharacterSet $this->default_charset\n\n";
		$conf_file .= "IllegalFiletypes $this->illegal_filetypes\n\n";
		$conf_file .= "SiteBackendDHTMLNavigation $this->site_backend_dhtml_navigation\n\n";
		$conf_file .= 'CheckDBMBStrings '.implode(' ',$this->check_db_mbstrings)."\n\n";
		$conf_file .= "BatchDelete $this->batch_delete\n\n";
		$conf_file .= "BatchDupe $this->batch_dupe\n\n";

		fwrite($fd,$conf_file);
		fclose($fd);

		# Write the cache
		$this->save_to_cache($this->id);

		return "Configuration file $this->conf_file updated.";
	}

	  ##################################
	 # Lets print the conf backend    #
	##################################
	function print_backend() {
		global $EDIT_PATH, $EDIT_DIR, $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/backend.inc");
		$backend = new Backend($EDIT_DIR,$EDIT_PATH);
		$backend->set_title("$this->system_name - Backend");

		$session = &get_mysource_session();

		 ###################################################
		# SECURITY - Only let in those who have permission
		if (!$session->logged_in()) {
			$session->login_screen($backend->title,"You must be logged in and have permission to edit <i>$this->system_name</i> in order to proceed.");
		}

		if (!superuser('web') && !superuser('user')) {
			$session->login_screen($backend->title,"You do not have permission to view or edit the system config of <i>$this->system_name</i>.");
		}

		$backend->set_active_menu_item('config');

		# Only privileged users can mess around here
		if (superuser('web') || superuser('user')) {
			$action = $_POST['action'];

			#############################
			# get users database object
			#############################
			#
			$users_system = &get_users_system();
			$db = &$users_system->db;

			##################
			# process $action
			##################
			#
			switch($action) {

				case 'Commit':

					$globalvars = array('system_name', 'system_owner', 'webmaster_email', 'errors_email', 'jupload_path', 'squiz_server_details', 'web_db_details', 'user_db_details', 'max_login_attempts', 'max_idle_time', 'bad_login_attempt_text', 'require_ssl_login','delete_superuser_ids', 'delete_webmaster_ids', 'delete_usermaster_ids', 'superuser_search', 'webmaster_search', 'usermaster_search', 'log_visitors', 'statistics_reporter', 'log_remote_hosts', 'persistent_sessions','persistent_sessions_ip_addresses','persistent_sessions_lifetime','virtual_paths', 'backend_suffix', 'strict_url_matching', 'email_page_update', 'default_language', 'default_charset', 'illegal_filetypes', 'check_db_mbstrings', 'site_backend_dhtml_navigation', 'backend_message_box_focus', 'backend_log_messages', 'user_db_details', 'user_authentication', 'default_table_cell_type', 'use_css_styles', 'batch_delete', 'batch_dupe', 'xss_security', 'mysource_help');

					foreach($globalvars as $k) {
						$$k = $_POST[$k];
					}

					if(user_root()) {

						####################
						# delete superusers
						####################
						#
						if (!empty($delete_superuser_ids)) {
							$sql = "SELECT login
									  FROM user
									 WHERE userid IN ('".implode("','", $delete_superuser_ids)."')";

							$superusers = $db->single_column($sql);
							foreach($superusers as $login) {
								$backend->add_message($this->delete_superuser($login));
							}
						}

						#################
						# add superusers
						#################
						#
						$user_searches = split("[ \t\r\n\,]+",gpc_stripslashes($superuser_search));
						if (!empty($user_searches)) {
							foreach($user_searches as $login) {
								# security & empty var check
								if (trim($login) != '') {
									# make sure user exists before adding
									if ($user_row = $users_system->find_user($login)) {
										$backend->add_message($this->add_superuser($user_row['login']));
									}
									else {
										$backend->add_message("Could not add superuser: $login. User does not exist");
									}
								}
							}# end foreach
						}

						###############################
						# update web database details
						###############################
						#
						$web_db_details = gpc_stripslashes($web_db_details);
						$backend->add_message($this->set_web_db_details($web_db_details['name'],
																		$web_db_details['host'],
																		$web_db_details['login'],
																		$web_db_details['password'],
																		$web_db_details['querylog']));

						###############################
						# update user database details
						###############################
						#
						$user_db_details     = gpc_stripslashes($user_db_details);
						$user_authentication = gpc_stripslashes($user_authentication);

						$backend->add_message($this->set_user_db_details(
								$user_db_details['name'],
								$user_db_details['host'],
								$user_db_details['login'],
								$user_db_details['password'],
								$user_db_details['querylog']
						));

						$backend->add_message($this->set_log_visitors(gpc_stripslashes($log_visitors)));

						$backend->add_message($this->set_statistics_reporter(gpc_stripslashes($statistics_reporter)));
						$backend->add_message($this->set_log_remote_hosts(gpc_stripslashes($log_remote_hosts)));

						$backend->add_message($this->set_user_authentication($user_authentication));

					}#end if user_root



					if(superuser()) {

						#####################
						# delete web masters
						#####################
						#
						if (!empty($delete_webmaster_ids)) {
							$sql = "SELECT login
									  FROM user
									 WHERE userid IN ('".implode("','", $delete_webmaster_ids)."')";

							$webmasters = $db->single_column($sql);
							foreach($webmasters as $login) {
								$backend->add_message($this->delete_web_master($login));
							}
						}

						######################
						# delete user masters
						######################
						#
						if (!empty($delete_usermaster_ids)) {
							$sql = "SELECT login
									  FROM user
									 WHERE userid IN ('".implode("','", $delete_usermaster_ids)."')";

							$usermasters = $db->single_column($sql);
							foreach($usermasters as $login) {
							  if (superuser()) {
								$backend->add_message($this->delete_user_master($login));
							  }
							}
						}

						##################
						# add web masters
						##################
						#
						$user_searches = split("[ \t\r\n\,]+", gpc_stripslashes($webmaster_search));
						if (!empty($user_searches)) {
							foreach($user_searches as $login) {
								# security and empty var check
								if (trim($login) != '') {
									# check user exists
									if ($user_row = $users_system->find_user($login)) {
										$backend->add_message($this->add_web_master($user_row['login']));
									}
									else {
										$backend->add_message("Could not add web master: $login. User does not exist");
									}
								}
							}# end foreach
						}

						###################
						# add user masters
						###################
						#
						$user_searches = split("[ \t\r\n\,]+", gpc_stripslashes($usermaster_search));
						if (!empty($user_searches)) {
							foreach($user_searches as $login) {
								# security and empty string check
								if (trim($login) != '') {
									# user exists in first place?
									if ($user_row = $users_system->find_user($login)) {
										$backend->add_message($this->add_user_master($user_row['login']));
									}
									else {
										$backend->add_message("Could not add user master: $login. User does not exist");
									}
								}
							}# end foreach
						}


						# Update other config vars
						$backend->add_message($this->set_system_name(gpc_stripslashes($system_name)));
						$backend->add_message($this->set_system_owner(gpc_stripslashes($system_owner)));
						$backend->add_message($this->set_webmaster_email(gpc_stripslashes($webmaster_email)));

						$backend->add_message($this->set_errors_email(gpc_stripslashes($errors_email)));

						$backend->add_message($this->set_backend_message_box_focus(gpc_stripslashes($backend_message_box_focus)));
						$backend->add_message($this->set_backend_log_messages(gpc_stripslashes($backend_log_messages)));


						$upload_result = process_image_upload('backend_brand_image', get_data_path(true, 'backend_brand_image'), false);
						$backend->add_message($upload_result['message']);
						$image = get_image(get_data_path(true, 'backend_brand_image'));
						if ($image) {
							$image_size = getImageSize($image);
							$image = '<img src="'.data_href(basename($image)).'" '.$image_size[3].' border="0">';
						}
						$backend->add_message($this->set_backend_brand_image($image));

						$backend->add_message($this->set_xss_security(gpc_stripslashes($xss_security)));
						$backend->add_message($this->set_jupload_path($jupload_path));

						$backend->add_message($this->set_squiz_server_details(gpc_stripslashes($squiz_server_details['path']),gpc_stripslashes($squiz_server_details['systemid']),gpc_stripslashes($squiz_server_details['domain']),gpc_stripslashes($squiz_server_details['port'])));

						$backend->add_message($this->set_max_login_attempts(gpc_stripslashes($max_login_attempts)));
						$backend->add_message($this->set_max_idle_time(gpc_stripslashes($max_idle_time)));
						$backend->add_message($this->set_bad_login_attempt_text(gpc_stripslashes($bad_login_attempt_text)));
						$backend->add_message($this->set_require_ssl_login(gpc_stripslashes($require_ssl_login)));
						$backend->add_message($this->set_batch_delete(gpc_stripslashes($batch_delete)));
						$backend->add_message($this->set_batch_dupe(gpc_stripslashes($batch_dupe)));
						
						if($this->virtual_paths) {
							$backend->add_message($this->set_backend_suffix(gpc_stripslashes($backend_suffix)));
						}
						$backend->add_message($this->set_virtual_paths(gpc_stripslashes($virtual_paths)));
						$backend->add_message($this->set_strict_url_matching(gpc_stripslashes($strict_url_matching)));

						# Format the ip_addresses into an array of strings
						foreach($persistent_sessions_ip_addresses as $i => $ip_address) {
							if(!$ip_address[0]) continue;	
							$ip_addresses[$i] = $ip_address[0].'.'.$ip_address[1].'.'.$ip_address[2].'.'.$ip_address[3].'/'.$ip_address[4];
						}
						$backend->add_message($this->set_persistent_sessions($persistent_sessions,$ip_addresses,$persistent_sessions_lifetime));
					}

					if(superuser('web')) {

						$backend->add_message($this->set_default_table_cell_type(gpc_stripslashes($default_table_cell_type)));
						$backend->add_message($this->set_use_css_styles(gpc_stripslashes($use_css_styles)));
						$backend->add_message($this->set_default_language(gpc_stripslashes(implode(',',$default_language))));
						$backend->add_message($this->set_default_charset(gpc_stripslashes($default_charset)));

						$backend->add_message($this->set_illegal_filetypes(gpc_stripslashes($illegal_filetypes)));
						$backend->add_message($this->set_check_db_mbstrings(gpc_stripslashes($check_db_mbstrings)));
						$backend->add_message($this->set_site_backend_dhtml_navigation(gpc_stripslashes($site_backend_dhtml_navigation)));
						$backend->add_message($this->set_mysource_help(gpc_stripslashes($mysource_help)));

						$backend->add_message($this->set_email_page_update(gpc_stripslashes($email_page_update)));
					}
					break;
			}

			if($action) {
				$this->clear_cache($this->id);
				$this->save_to_cache($this->id);
				$backend->add_message($this->conf_updated());
			}

		}

		$backend->set_heading('System Configuration','create');
		$backend->set_subheading($this->system_name);
		$backend->set_hidden_field('action');
		$backend->print_header();

		 ########################################
		# this stuff comes from the config file
		$superusers  = &$this->superusers['all'];
		$webmasters  = &$this->superusers['web'];
		$usermasters = &$this->superusers['user'];


		$backend->open_section('System Settings');
		$backend->open_field('System Name','one_liner');
		if(superuser()) {
			echo text_box('system_name',$this->system_name, 30, '','class=backend_data');
		} else {
			echo $this->system_name;
		}

		$backend->open_field('System Owner','one_liner');
		if(superuser()) {
			echo text_box('system_owner',$this->system_owner, 30, '','class=backend_data');
		} else {
			echo $this->system_owner;
		}

		$backend->open_field('Web Master Email','one_liner');
		if(superuser()) {
			echo text_box('webmaster_email',$this->webmaster_email, 30, '','class=backend_data');
		} else {
			echo $this->webmaster_email;
		}

		$backend->open_field('Errors Email','one_liner');
		if(superuser()) {
			$email = ($this->errors_email) ? $this->errors_email : $_SERVER['SERVER_ADMIN'];
			echo text_box('errors_email',$email, 30, '','class=backend_data');
		} else {
			echo $this->errors_email;
		}

		$backend->open_field('Backend Message Box Focus','one_liner');
		if (superuser()) {
			echo combo_box('backend_message_box_focus',array('1'=>'On','0'=>'Off'),$this->backend_message_box_focus);
		} else {
			echo (($this->backend_message_box_focus)?'On':'Off');
		}
		echo('<span class=backend_smallprint><br>This determines whether the message box in the backend pops up over the current window or not</span>');

		$backend->open_field('Backend Log Messages','one_liner');
		if (superuser()) {
			echo text_box('backend_log_messages',$this->backend_log_messages,30,'','class=backend_data');
		} else {
			echo $this->backend_log_messages;
		}
		echo '<span class=backend_smallprint><br>/path/to/log/file or leave blank for no logging.</span>';

		$backend->open_field('Backend Brand Image','one_liner');
		if (superuser()) {
			echo image_upload('backend_brand_image', get_data_path(true, 'backend_brand_image'), data_href(), 20000, 200, 100, true);
		} else {
			echo $this->backend_brand_image;
		}

		$backend->open_field('XSS Security','one_liner');
		if (superuser()) {
			echo combo_box('xss_security',array('1'=>'On','0'=>'Off'),$this->xss_security);
		} else {
			echo (($this->xss_security)?'On':'Off');
		}
		echo('<span class=backend_smallprint><br>By activating XSS Security, data submitted by users (eg; forms) will be checked for malicious tampering to reduce the possibility of system attacks. <b>Please note that regardless of the security state, <i>all WYSIWYG forms</i> will be implicitly trusted and not checked for such security breaches. All backend administration is also trusted.</b></span>');

		$backend->open_field('JUpload Path','one_liner');
		if(superuser()) {
			echo text_box('jupload_path',$this->jupload_path,40,100);
			echo '<span class=backend_smallprint><br>If you have configured tomcat or another Java Servlet handler on your server and have enabled the squizlib/jupload directory, enter the appropriate path here (from the browsers POV).<br>E.g. <i>http://www.example.com/jupload/</i><br><b>Leave this field blank if you cannot or do not wish to use the Java uploader.</b></span>';
		} else {
			echo $this->jupload_path;
		}

		if(superuser()) {
			$backend->open_field('Squiz Server Path','one_liner');
			echo text_box('squiz_server_details[path]',$this->squiz_server_details['path'], 40, '','class=backend_data');
			if($this->squiz_server_details['path']) {
				$backend->open_field('Squiz Server SystemID','one_liner');
				echo text_box('squiz_server_details[systemid]',$this->squiz_server_details['systemid'],30,'','class=backend_data');
				$backend->open_field('Squiz Server Domain','one_liner');
				echo text_box('squiz_server_details[domain]',$this->squiz_server_details['domain'],30,'','class=backend_data');
				$backend->open_field('Squiz Server Port','one_liner');
				echo text_box('squiz_server_details[port]',$this->squiz_server_details['port'],10,10,'class=backend_data');
			}
		} else {
			echo $this->squiz_server_details['path'];
		}

		 ##############
		# super-users
		if (user_root()) {

			if (!empty($superusers)) {

				$backend->open_field('Current Super Users ('.count($superusers).'):');

				$sql = "SELECT userid, login, CONCAT(firstname, ' ', surname) as name, email
						FROM user
						WHERE login IN ('".implode("','", $superusers)."')
						ORDER BY surname, firstname";
				$superusers  = $db->associative_array($sql);

				$users_system->display_users_table(array_keys($superusers), ((user_root())?'delete_superuser_ids':''), 'Delete?','super_page_counter');
			}#end if count(superusers)

			$backend->open_field('Add Super Users:<br><span class=backend_smallprint>(logins/emails)</span>');
			echo text_area(superuser_search,'',30,3,3000,'class=backend_data');

		}#end if user_root

		 ##########################################
		# login and session timeout configuration
		$backend->open_section('Login/Session Settings');
		$backend->open_field('Max Login Attempts','one_liner');
		if(superuser()) {
			echo text_box('max_login_attempts',(($this->max_login_attempts)?$this->max_login_attempts:'Unlimited'), 10, 10,'class=backend_data');
		} else {
			echo (($this->max_login_attempts)?$this->max_login_attempts:'Unlimited');
		}

		$backend->open_field('Max Idle Time','one_liner');
		if(superuser()) {
			echo text_box('max_idle_time',(($this->max_idle_time)?$this->max_idle_time:'Unlimited'), 10, 10,'class=backend_data').'<span class=backend_fineprint>(seconds. Min: 60)</span>';
		} else {
			echo (($this->max_idle_time)?$this->max_idle_time.'<span class=backend_fineprint> (seconds)</span>':'Unlimited');
		}

		$backend->open_field('Bad login attempt text','one_liner');
		if(superuser()) {
			echo text_box('bad_login_attempt_text',(($this->bad_login_attempt_text)?$this->bad_login_attempt_text:''), 50, '','class=backend_data');
		} else {
			echo (($this->bad_login_attempt_text)?$this->bad_login_attempt_text:'Default text');
		}
		echo '<span class=backend_smallprint><br>This is the text that will appear in a pop-up box upon a bad login attempt. If left blank default text is used.</span>';

		$backend->open_field('Require SSL Login','one_liner');
		if(superuser()) {
			echo combo_box('require_ssl_login',array('1'=>'On','0'=>'Off'),$this->require_ssl_login);
		} else {
			echo (($this->require_ssl_login) ? 'Yes' : 'No');
		}
		echo '<span class=backend_smallprint><br>This will make the default login box on the frontend SSL encrypted.</span>';

		$backend->open_field('Log Visitors','one_liner');
		if(user_root()) {
			echo combo_box('log_visitors', array('1'=>'On','0'=>'Off'), $this->log_visitors);
		} else {
			echo (($this->log_visitors) ? 'Yes' : 'No');
		}
		echo('<span class=backend_smallprint><br>Turning on this setting, enables logging of sessions, page hits and file hits.<br><i>NOTE: On high traffic sites enabling this option can cause the database server to crash due to the huge amounts of data being stored</i></span>');
		if (user_root() && $this->log_visitors && $this->statistics_reporter == '') {
			echo('<span class=backend_smallprint><br>Even though Log Visitors is switched on, nothing is recorded until you choose a statistics reporter.</span>');
		}

		$backend->open_field('Statistics Reporter','one_liner');
		if(user_root() && $this->statistics_reporter == '') {
			$available_statistics = array();
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/statistic.inc");
			$stat_reporter = new Statistic('config');
			$available_statistics = $stat_reporter->get_available_statistics();
			$available_statistics[''] = '[ None ]';
			echo combo_box('statistics_reporter', $available_statistics, $this->statistics_reporter);
		} else {
			echo $this->statistics_reporter;
			?><input type="hidden" name="statistics_reporter" value="<?=$this->statistics_reporter?>"><?
			if (user_root() && !$this->log_visitors) {
				echo('<span class=backend_smallprint><i><br>NOTE: Even though the statistics reporter is set, no statistics are logged until you turn Log Visitors to ON.</i></span>');
			}
		}

		$backend->open_field('Log Remote Hosts','one_liner');
		if(user_root()) {
			echo combo_box('log_remote_hosts',array('1'=>'On','0'=>'Off'),$this->log_remote_hosts);
		} else {
			echo (($this->log_remote_hosts)?'Yes':'No');
		}
		echo('<span class=backend_smallprint><br>If pages sometimes take a very long time to load at the beginning of a session, turn this option off. If on, the <i>hostname</i> of the user is logged, which can be used to figure out their ISP and country of origin.<br>Only effective if Log Visitors in enabled</span>');

		$backend->open_field('Persistent Sessions','one_liner');
		if(superuser()) {
			echo combo_box('persistent_sessions',array('1'=>'On','0'=>'Off'),$this->persistent_sessions);
		} else {
			echo (($this->persistent_sessions)?'Yes':'No');
		}
		echo '<span class=backend_smallprint><br>This will allow the user to remain <i>logged in</i> for a longer period of time.</span>';

		if(superuser() && $this->persistent_sessions) {
			
			$backend->open_field('IP Address','one_liner');

			$ip_options = range(0,255);
			$mask_options = range(0,32);
	
			for($i=0;$i<=count($this->persistent_sessions_ip_addresses);$i++) {
				$ip_address = $this->persistent_sessions_ip_addresses[$i];
				$ip_octets = explode('.',substr($ip_address,0,strpos($ip_address,'/')));
				$mask = substr($ip_address,strpos($ip_address,'/')+1);
 				echo combo_box('persistent_sessions_ip_addresses['.$i.'][0]',$ip_options,$ip_octets[0]).'.'.combo_box('persistent_sessions_ip_addresses['.$i.'][1]',$ip_options,$ip_octets[1]).'.'.combo_box('persistent_sessions_ip_addresses['.$i.'][2]',$ip_options,$ip_octets[2]).'.'.combo_box('persistent_sessions_ip_addresses['.$i.'][3]',$ip_options,$ip_octets[3]).'/'.combo_box('persistent_sessions_ip_addresses['.$i.'][4]',$mask_options,$mask).'<br/>';
			}

			echo '<span class=backend_smallprint><br>Select the IP address range of users allowed to use persistent sessions. Leave the mask value as <i>0</i> to match the exact ip.</span>';

			$backend->open_field('Session Lifetime','one_liner');
			echo text_box('persistent_sessions_lifetime',$this->persistent_sessions_lifetime, 10, 10,'class=backend_data').'<span class=backend_fineprint>(minutes)</span>';

		}


		 ############
		# WEB SYSTEM
		$backend->open_section('Web System Settings');

		 ####################################
		# Only root needs to see this stuff
		if(user_root()) {
			$backend->open_field('Database Name','one_liner');
			echo text_box('web_db_details[name]',$this->web_db_details['name'], 30, 30,'class=backend_data');

			$backend->open_field('Database Host','one_liner');
			echo text_box('web_db_details[host]',$this->web_db_details['host'], 40, 128,'class=backend_data');
			echo('<span class=backend_fineprint><br><b>Syntax:</b> host[:port][:/path/to/socket]<br><b>Examples:</b><br>localhost<br>dataserver.mycompany.com:3309<br>localhost:3309:/var/lib/mysql_special/mysql.sock</span>');

			$backend->open_field('Database Login','one_liner');
			echo text_box('web_db_details[login]',$this->web_db_details['login'], 15, 30,'class=backend_data');

			$backend->open_field('Database Password','one_liner');
			echo password_box('web_db_details[password]',$this->web_db_details['password'], 15, 30,'class=backend_data');

			$backend->open_field('Database Query Logs','one_liner');
			echo text_box('web_db_details[querylog]',$this->web_db_details['querylog'],45,255,'class=backend_data');
			echo ('<span class=backend_smallprint><br>/path/to/log/file or leave blank for no logging.</span>');
		}

		$backend->open_field('Virtual Paths','one_liner');
		if (superuser()) {
			echo combo_box('virtual_paths',array('1'=>'On','0'=>'Off'),$this->virtual_paths);
		} else {
			echo (($this->virtual_paths)?'On':'Off');
		}
		$backend->open_field('Backend Suffix','one_liner');
		if(superuser() && $this->virtual_paths) {
		echo '<span class=backend_data>/</span>';
		echo text_box('backend_suffix',$this->backend_suffix,15,255,'class=backend_data');
		echo ('<span class=backend_smallprint><br>This must correspond with your web server configuration.</span>');
		} else {
			echo "<span class=backend_data>/$this->backend_suffix</span>";
		}

		$backend->open_field('Batch Page Delete','one_liner');
		if(superuser()) {
			echo text_box('batch_delete',(($this->batch_delete)?$this->batch_delete:'5'), 10, 10,'class=backend_data');
		} else {
			echo (($this->batch_delete)?$this->batch_delete:'5');
		}
		echo ('<span class=backend_smallprint><br>The number of pages to delete at a time when deleting a page or site. Set this to a low number if your deleting is timing out. This can get over-ridden automatically if MySource determines it will time-out.</span>');

		$backend->open_field('Batch Page Dupe','one_liner');
		if(superuser()) {
			echo text_box('batch_dupe',(($this->batch_dupe)?$this->batch_dupe:'5'), 10, 10,'class=backend_data');
		} else {
			echo (($this->batch_dupe)?$this->batch_dupe:'5');
		}

		echo '<span class="backend_smallprint"><br />The number of pages to dupe at a time when duping a page or site. Set this to a low number if your duping is timing out. This can get over-ridden automatically if MySource determines it will time-out.</span>';


		$backend->open_field('Strict URL Matching','one_liner');
		if (superuser()) {
			echo combo_box('strict_url_matching',array('1'=>'On','0'=>'Off'),$this->strict_url_matching);
		} else {
			echo (($this->strict_url_matching)?'On':'Off');
		}
		echo ('<span class=backend_smallprint><br>This decides whether the system will try to match the closest URL for or display the \'Page Not Found\'.</span>');

		$backend->open_field('Email Page Updates', 'one_liner');
		echo combo_box('email_page_update',array('1'=>'Yes','0'=>'No'),$this->email_page_update);
		echo ('<span class=backend_smallprint><br>Allow users to add an update message on every page commit</span>');

		$backend->open_field('Illegal Filetypes');
		if (superuser()) {
			echo text_area('illegal_filetypes',$this->illegal_filetypes,40,3);
		} else {
			echo $this->illegal_filetypes;
		}
		echo ('<span class=backend_smallprint><br>List the file extensions that you do not want MySource users to be able to upload, and use to potentially exploit your server. (all lower-case - uppercase files will also be rejected)</span>');

		
		if (superuser('web')) {
			# DEFAULT TABLE CELL TYPE
			$backend->open_field('Default Table Cell Type','one_liner');
			echo combo_box('default_table_cell_type',array('richtext'=>'Rich Text','wysiwyg'=>'WYSIWYG','rawhtml'=>'Raw HTML'),$this->default_table_cell_type);

			# USE CSS STYLES IN WYSIYG
			$backend->open_field('Use all styles from stylesheet in WYSIWYG','one_liner');
			echo combo_box('use_css_styles',array('1'=>'Yes','0'=>'No'),$this->use_css_styles);
		}

		# LANGUAGE
		$backend->open_field('Frontend Language');
		$languages_config = &get_config('languages');
		if(superuser()) {
			if($this->default_language) {
				echo $languages_config->name_list($this->default_language).'<br>';
			}
			echo multiple_combo_box('default_language',$languages_config->languages,explode(',',$this->default_language),'class=backend_data', '', 4);
		} else {
			echo '<span class=backend_data>'.$languages_config->name_list($this->default_language).'</span>';
		}
		echo('<span class=backend_smallprint><br>This is the default language used on the frontend. It may be overridden by individual sites and pages.</span>');

		# CHARACTER SETS
		$backend->open_field('Character Set');
		if(superuser()) {
			$charsets_config = &get_config('charsets');
			echo combo_box('default_charset',array(''=>'-- Browser Default --')+$charsets_config->charsets,$this->default_charset, 'class=backend_data');
		} else {
			$languages_config = &get_config('charsets');
			echo "<span class=backend_data>{$charsets_config->charsets[$this->default_charset]}</span>";
		}
		echo('<span class=backend_smallprint><br>This is the default character set used in the backend and the frontend. It may be overridden by individual sites and pages.</span>');
		echo('<span class=backend_smallprint><br><input type=checkbox name=check_db_mbstrings[] value=web '.((in_array('web',$this->check_db_mbstrings))?'checked':'').'> Validate data coming out of the <b>web</b> database is in this character set and uncorrupted.<br><input type=checkbox name=check_db_mbstrings[] value=users '.((in_array('users',$this->check_db_mbstrings))?'checked':'').'> Validate data coming out of the <b>users</b> database is in this character set and uncorrupted.</span>');
		echo('<span class=warning style="font-size: 10px;"><br><b>Warning: These settings are only necessary for multi-byte character sets.</b> You must have the <i>mbstring</i> module for PHP installed for these two options to work correctly. Only a few character sets are supported (generally Japanese ones).</span>');

		$backend->open_field('Site Backend DHTML Navigation','one_liner');
		if (superuser('web')) {
			echo combo_box('site_backend_dhtml_navigation',array('1'=>'On','0'=>'Off'),$this->site_backend_dhtml_navigation);
		} else {
			echo (($this->site_backend_dhtml_navigation)?'On':'Off');
		}
		$backend->open_field('MySource Help','one_liner');
		if (superuser('web')) {
			echo combo_box('mysource_help',array('1'=>'On','0'=>'Off'),$this->mysource_help);
		} else {
			echo (($this->mysource_help)?'On':'Off');
		}

		 ##############
		# web-masters
		if (superuser()) {
			if (!empty($webmasters)) {
				$backend->open_field('Current Web Masters ('.count($webmasters).'):');

				$sql = "SELECT userid, login, CONCAT(firstname, ' ', surname) as name, email
						FROM user
						WHERE login IN ('".implode("','", $webmasters)."')
						ORDER BY surname, firstname";

				$webmasters = $db->associative_array($sql);

				$users_system->display_users_table(array_keys($webmasters), 'delete_webmaster_ids','Delete?','web_page_counter');
			}#end if count(webmasters)
			$backend->open_field('Add Web Masters:<br><span class=backend_smallprint>(logins/emails)</span>');
			echo text_area(webmaster_search,'',30,3,3000,'class=backend_data');
		}#end if superuser


		 ####################################
		# Only root needs to see this stuff
		$backend->open_section('Users System');
		if(user_root()) {
			$backend->open_field('Database Name','one_liner');
			echo text_box('user_db_details[name]',$this->user_db_details['name'], 30, 30,'class=backend_data');
			$backend->open_field('Database Host','one_liner');
			echo text_box('user_db_details[host]',$this->user_db_details['host'], 40, 128,'class=backend_data');
			echo('<span class=backend_fineprint><br><b>Syntax:</b> host[:port][:/path/to/socket]</span>');
			$backend->open_field('Database Login','one_liner');
			echo text_box('user_db_details[login]',$this->user_db_details['login'], 30, 30,'class=backend_data');
			$backend->open_field('Database Password','one_liner');
			echo password_box('user_db_details[password]',$this->user_db_details['password'], 30, 30,'class=backend_data');
			$backend->open_field('Database Query Logs','one_liner');
			echo text_box('user_db_details[querylog]',$this->user_db_details['querylog'],45,255,'class=backend_data');
			echo ('<span class=backend_smallprint><br>/path/to/log/file or leave blank for no logging.</span>');

			global $XTRAS;
			$backend->open_field('User Authentication');
			echo combo_box('user_authentication',$XTRAS->list_type('users/authentication'),$this->user_authentication);
			echo('(<a href="users.php?authentication_edit='.$this->user_authentication.'"><span class=backend_smallprint>Configure current authentication system</a>)<br>Note that the user "root" is always validated off the MySource user database.</span>');
		}

		 ###############
		# user-masters
		if (superuser()) {
			if (!empty($usermasters)) {
				$backend->open_field('Current Users Masters ('.count($usermasters).'):');

				$sql = "SELECT userid, login, CONCAT(firstname, ' ', surname) as name, email
						FROM user
						WHERE login IN ('".implode("','", $usermasters)."')
						ORDER BY surname, firstname";

				$usermasters = $db->associative_array($sql);

				$users_system->display_users_table(array_keys($usermasters), 'delete_usermaster_ids','Delete?','user_page_counter');
			}#end if count(usermasters)
			$backend->open_field('Add User Masters:<br><span class=backend_smallprint>(logins/emails)</span>');
			echo text_area(usermaster_search,'',30,3,3000,'class=backend_data');
		}#end if superuser



		if (superuser()) {
			global $XTRAS;
			$backend->open_section('Xtras');
			$backend->open_field('List', 'top');
			$XTRAS->print_backend($backend);
		}

		if(superuser('web')) {
			$backend->print_commit_button('Commit',"if (confirm('Commit this information to the system?')) { document.edit.action.value='Commit';document.edit.submit(); }");
		}

		$backend->print_footer();

	}#end method

}




 ################################################
# File types: descriptions and mime-types base
# on the extensions
class Config_File_Types extends Config {

	var $file_types = array();
	var $mime_types = array();

	function load() {

		# Can we load from cache ?
		$loaded = $this->load_from_cache();
		if($loaded) return;

		$parse_error_log = '';

		 ###########################################
		# Parse the file types configuration file
		while($line = $this->get_conf_line("$this->conf_path/$this->conf_file")) {
			# Grab the first word of the line
			$key   = strtok($line," \t");
			$ext   = strtok(" \t");
			$value = strtok("\n");

			switch($key) {
				case 'desc':
					$this->add_file_type($ext,trim($value));
					break;
				case 'mime':
					$this->add_mime_type($ext,trim($value));
					break;
				default:
					$parse_error_log .= "Unrecognised keyword: \"$key\" on line ".$this->conf_parser_files[$this->conf_file]['line']." of $this->conf_file<br>";
					break;
			}

		}
		# Errors found, die.
		if ($parse_error_log) {
			report_error(__FILE__,__LINE__,"<B>Parsing configuration file:</b><br><br>$parse_error_log");
			exit();
		}

		# End parsing configuration file

		# Write the cache
		$this->save_to_cache($this->id);

	}

	function add_file_type($ext='',$type='') {
		if(!$ext || !$type) return;
		if(isset($this->file_types[$ext])) return "File type already set for: $ext";
		$this->file_types[$ext] = $type;
		return "File type $ext added";
	}

	function delete_file_type($ext='') {
		if(!$ext) return;
		if(!isset($this->file_types[$ext])) return "File type $ext does not exist";
		unset($this->file_types[$ext]);
		return "File type $ext deleted";
	}

	function add_mime_type($ext='',$type='') {
		if(!$ext || !$type) return;
		if(isset($this->mime_types[$ext])) return "Mime type already set for: $ext";
		$this->mime_types[$ext] = $type;
		return "Mime type $ext added";
	}

	function delete_mime_type($ext='') {
		if(!$ext) return;
		if(!isset($this->mime_types[$ext])) return "Mime type $ext does not exist";
		unset($this->mime_types[$ext]);
		return "Mime type $ext deleted";
	}

	  ###############################################
	 # Rewrites the conf file with current variables
	function conf_updated() {

		  ################################
		 # Delete old conf file and cache
		$this->clear_cache($this->id);

		# Re-write new conf file
		if(!($fd = fopen("$this->conf_path/$this->conf_file","w"))) {
			report_error(__FILE__,__LINE__,"Configuration file not found or permission denied: $this->conf_path/$this->conf_file");
			exit();
		}

		foreach($this->file_types as $ext => $type) {
			$conf_file .= "desc $ext $type\n";
		}
		foreach($this->mime_types as $ext => $type) {
			$conf_file .= "mime $ext $type\n";
		}

		fwrite($fd,$conf_file);
		fclose($fd);

		return "Configuration file $this->conf_file updated.";
	}

}



 ###########################
# Countries and their codes
class Config_Countries extends Config {

	var $countries = array();

	function load() {

		 ############################
		# Can we load from cache ?
		$loaded = $this->load_from_cache();
		if($loaded) return;

		$parse_error_log = '';

		 ###########################################
		# Parse the file types configuration file
		$this->countries = array();
		while($line = $this->get_conf_line("$this->conf_path/$this->conf_file")) {
			# The code must be a lower-case two-letter country code
			$code = strtolower(substr(strtok($line," \t"),0,2));
			if(!ereg("^[a-z][a-z]$",$code)) {
				$parse_error_log .= "'$code' is not a valid country code.\n";
			} else {
				$this->countries[$code] = strtok("\n");
			}
		}
		# Errors found, die.
		if ($parse_error_log) {
			report_error(__FILE__,__LINE__,"<B>Parsing configuration file:</b><br><br>$parse_error_log");
			exit();
		}

		# Write the cache
		$this->save_to_cache();

	}

}



 ###########################
# Languages and their Codes
class Config_Languages extends Config {

	var $languages = array();

	function load() {

		 ############################
		# Can we load from cache ?
		$loaded = $this->load_from_cache();
		if($loaded) return;

		$parse_error_log = '';

		 ###########################################
		# Parse the file types configuration file
		$this->languages = array();
		while($line = $this->get_conf_line("$this->conf_path/$this->conf_file")) {
			# The code must be a lower-case two-letter Languages code
			$code = strtolower(substr(strtok($line," \t"),0,2));
			if(!ereg("^[a-z][a-z]$",$code)) {
				$parse_error_log .= "'$code' is not a valid language code.\n";
			} else {
				$this->languages[$code] = strtok("\n");
			}
		}
		# Errors found, die.
		if ($parse_error_log) {
			report_error(__FILE__,__LINE__,"<B>Parsing configuration file:</b><br><br>$parse_error_log");
			exit();
		}

		# Write the cache
		$this->save_to_cache();

	}

	 ###############################################
	# Given a comma-separated list of language code,
	# returns a comma-separated list of language names
	function name_list($codes='') {
		$codes = split("[ \t]*[\,\;][ \t]*",$codes);
		foreach($codes as $code) {
			$result .= "{$this->languages[$code]}, ";
		}
		return substr($result,0,-2);
	}
}


 ################################################
# Popular Character Sets and their Descriptions
class Config_Charsets extends Config {

	var $charsets = array();

	function load() {

		 ############################
		# Can we load from cache ?
		$loaded = $this->load_from_cache();
		if($loaded) return;

		$parse_error_log = '';

		 ###########################################
		# Parse the file types configuration file
		$this->charsets = array();
		while($line = $this->get_conf_line("$this->conf_path/$this->conf_file")) {
			# The code must be a lower-case two-letter Languages code
			$code = strtolower(strtok($line," \t"));
			$this->charsets[$code] = strtok("\n");
		}
		# Errors found, die.
		if ($parse_error_log) {
			report_error(__FILE__,__LINE__,"<B>Parsing configuration file:</b><br><br>$parse_error_log");
			exit();
		}

		# Write the cache
		$this->save_to_cache();
	}

}


 ################################################
# Popular Metdata standards
class Config_Metadata extends Config {

	var $standards = '';
	var $format = array();
	var $type = '';

	function load() {
		$type = $this->type;

		 ############################
		# Can we load from cache ?
		$loaded = $this->load_from_cache();
		if($loaded && $type == $this->type) return;

		$parse_error_log = '';

		if ($this->type) {

			# Parse the metadata configuration file
			$this->format = array();
			$prev_elems = array();
			$last_indent = 0;
			$indent = 0;
			while($line = $this->get_conf_line("$this->conf_path/metadata_{$this->type}.conf", false)) {
				$elements = preg_split("/\t/",$line);
				$last_indent = $indent;
				$indent = -1;
				foreach($elements as $elem) {
					$indent++;
					$elem = trim($elem);

					if ($elem == '' && empty($prev_elems)) break; # a blank line

					if ($elem != '') {
						if (empty($prev_elems)) {
							# the start of a new metatag
							$prev_elems = array();
							$this->format[$elem] = array();
							$indent = 0;
						} else {
							$eval_string = '$this->format';
							for ($i = $indent; $i <= $last_indent; $i++) $temp = array_pop($prev_elems);
							foreach ($prev_elems as $prev) {
								$eval_string .= "['$prev']";
							}
							$eval_string .= "['$elem'] = array();";
							eval($eval_string);
						}
					} else {
						continue;
					}

					$prev_elems[] = $elem;
				}
			}

			# Errors found, die.
			if ($parse_error_log) {
				report_error(__FILE__,__LINE__,"<B>Parsing configuration file:</b><br><br>$parse_error_log");
				exit();
			}

			# Write the cache
			$this->save_to_cache();

		} else {
			# just get a list of 'installed' standards
			$d = dir($this->conf_path);
			while (false !== ($entry = $d->read())) {
				if (strpos($entry,'metadata_') === 0) {
					# found a metadata file
					$entry = preg_replace('/^metadata_/', '', $entry);
					$entry = preg_replace('/\.conf$/', '', $entry);
					$this->standards[$entry] = str_replace('_', ' ', $entry);
				}
			}
			$d->close();
		}
	}
}
?>
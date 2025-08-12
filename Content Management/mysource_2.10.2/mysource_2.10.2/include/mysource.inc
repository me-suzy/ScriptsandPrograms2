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
## File: include/mysource.inc
## Desc: An object that is the intro to MySource backend
## $Source: /home/cvsroot/mysource/include/mysource.inc,v $
## $Revision: 2.11.4.1 $
## $Author: achadszinow $
## $Date: 2004/05/27 06:25:33 $
#######################################################################
include_once("$INCLUDE_PATH/html_general.inc");
include_once("$INCLUDE_PATH/html_form.inc");
#---------------------------------------------------------------------#

class Mysource extends CacheableObject {

	var $id = 'mysource'; # For cache (only ever one of these objects)

	var $extension_factory = array(); # An array of system_extension  objects as they're asked for

	 ##############
	# Constructor
	function Mysource() {
		 
		 #############################################################
		# Open the connection to the database - using configuration
		# At the moment use the user database
		$system_config = &get_system_config();
		$this->db = new Db(
			$system_config->user_db_details['name'],
			$system_config->user_db_details['login'],
			$system_config->user_db_details['password'],
			$system_config->user_db_details['host'],
			$system_config->user_db_details['querylog']
		);

		$this->db->set_error_call('report_error');
		if(version_no_compare($this->db->server_version(),MYSOURCE_REQUIRED_MYSQL_VERSION) < 0) {
			report_error(__FILE__,__LINE__,MYSOURCE_LONG_NAME.' requires MySQL Version '.MYSOURCE_REQUIRED_MYSQL_VERSION.'. You may need to upgrade. Your user database\'s current version is '.$this->db->server_version().'.');
			exit();
		}
	}

	 #######################################
	# Returns a reference ot the db object
	function &get_db() {
		return $this->db;
	}

	 ####################################################################
	# Given a system_extension codename, load and return a refernce to it
	function &get_extension($extension='') {
		if(!$extension) return;
		$class_name = 'system_extension_'.$extension;
		if(get_class($this->extension_factory[$extension]) != $class_name) {
			global $XTRAS_PATH;
			if (file_exists($XTRAS_PATH.'/system/extensions/'.$extension.'/'.$extension.'.inc')) {
				include_once($XTRAS_PATH.'/system/extensions/'.$extension.'/'.$extension.'.inc');
				$this->extension_factory[$extension] = new $class_name();
			} else {
				return false;
			}
		}
		return $this->extension_factory[$extension];
	}

	 ################################################################################
	# Sets up a backend object for webby objects to use while doing their backends !
	function &setup_backend() {
		global $INCLUDE_PATH;
		include_once("$INCLUDE_PATH/backend.inc");
		global $EDIT_DIR, $EDIT_PATH;
		$this->backend = new Backend($EDIT_DIR,$EDIT_PATH);
		return $this->backend;
	}

	 ###################################
	# Prints the backend
	function print_backend() {
		global $XTRAS;

		$system_config = &get_system_config();
		$session = &get_mysource_session();

		$backend = &$this->setup_backend(); # Backend refrence
		$backend->set_title("$system_config->system_name - Backend");
		$backend->set_active_menu_item("mysource");

		 ###################################################
		# SECURITY - Only let in those who have permission
		if (!$session->logged_in()) {
			$session->login_screen($backend->title,"You must be logged in and have permission to edit <i>$system_config->system_name</i> in order to proceed.");
		}
		
		$backend->set_heading('M<small>Y</small>S<small>OURCE</small>','swish');
		$backend->set_subheading($system_config->system_name);
		$backend->set_hidden_field('action');

		$extension_edit = $_GET['extension_edit'];
		$backend->set_hidden_field('extension_edit',$extension_edit);

		 ##########################################################
		# Display the edit interface for the extension if asked for
		if($extension_edit) {
			$extension = &$this->get_extension($extension_edit);
			$extension->print_backend();
			return;
		}

		$backend->print_header();

		$backend->open_section('System Summary');
		$backend->open_field('Web', 'one_liner');
		?>
			<p>
			This tab is your portal to look and feel of how your sites appear, where your sites 
			appear and who can view your sites.
			<p>
			The <a href="web.php">Web</a> tab allows you to control multiple sites which can choose 
			from multiple (and potentially completely different) site designs. 
		<?

		$backend->open_field('Users', 'one_liner');
		?>
			<p>
			The <a href="users.php">Users</a> tab takes you to an interface where you can manage all the user accounts you have control over.
		<?

		if (superuser()) {
			$backend->open_field('Config', 'one_liner');
			?>
				<p>
				The <a href="config.php">Config</a> allows the editing of setting that affect the whole system,
				and also displays a list of all the xtra modules that this system has (and any conflicts 
				that these have)
			<?
		}#end if

		$url   = $system_config->backend_mysource_iframe['url'];
		$title = $system_config->backend_mysource_iframe['title'];
		$field = '';

		if (!$url) {
			$url   = 'http://mysource.squiz.net/News/';
			$title = 'MySource News';
		}
		if ($url == 'http://mysource.squiz.net/News/') {
			$field = 'News from <a href="http://mysource.squiz.net" target="_blank">mysource.squiz.net</a>';
		}

		$backend->open_section($title);
		if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "msie")){
			$backend->open_field($field, "top");
			?> 
			<table border="0" cellspacing="0" cellpadding="0" height="600" width="100%" align="center">
				<tr>
					<td>
						<iframe src="<?=$url?>" width="100%" height="100%" frameborder="0"></iframe>
					</td>
				</tr>
			</table>
			<?
		} else if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "mozilla/5")){
			$backend->close_section();
			?><iframe src="<?=$url?>" width="100%" height="100%" frameborder="0"></iframe><?

		} else {
			$backend->close_section();
			?><ilayer id="newsLayer" width="600" height="800" z-index="0" src="<?=$url?>"></ilayer><?

		}#end if

		$backend->print_footer();

	}#end method

}#end class
?>

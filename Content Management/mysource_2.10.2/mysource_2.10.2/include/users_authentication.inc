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
## $Source: /home/cvsroot/mysource/include/users_authentication.inc,v $
## $Revision: 2.5 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:59 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/parameter_set.inc");
#---------------------------------------------------------------------#

class Users_Authentication extends UsersObject {
	
	var $parameters='';
	var $temp='';

	# A string representing the codename of this extention - automatic
	var $codename='';

	# A string representing the name of this extention - automatic
	var $name='';

	# The version number of this xtra instance
	var $version='';

	# The path to this Xtra's directory
	var $xtra_path='';

	# Directory where this site keeps its files.
	var $data_path='';

	# Only used by extensions which permit multiple instances, otherwise always 0
	var $id = 1;

	 ##############################
	# Constructor
	function Users_Authentication() {
		UsersObject::UsersObject();
		return $this->load();
	}


	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = UsersObject::__sleep();
		array_remove_element('temp',$result);
		array_remove_element('codename',$result);
		array_remove_element('name',$result);
		array_remove_element('version',$result);
		array_remove_element('table_name',$result);
		array_remove_element('xtra_path',$result);
		array_remove_element('data_path',$result);
		return $result;
	}


	 #################################################
	# Wake up with this handy information EVERY day !
	function __wakeup() {
		UsersObject::__wakeup();
		$this->set_generated_information();
	}

	 ###########
	# Necessay?
	function create() {

	}

	
	 ##################################################
	# Handy little wrappers
	function &get_user($search='', $use_field=false) {
		return $this->users_system->get_user($search, $use_field);
	}
	function &get_organisation($id=0) {
		return $this->users_system->get_organisation($id);
	}
	function &get_managed_organisationids() {
		return $this->users_system->get_managed_organisationids();
	}
	function &managed_userids() {
		return $this->users_system->managed_userids();
	}
	function &get_location($id=0) {
		return $this->users_system->get_location($id);
	}
	function &get_xtra_description() {
		global $XTRAS;
		return $XTRAS->description('users/authentication',$this->codename);
	}


	 ####################################################
	# Given a login/password, returns the corresponding
	# userid in the mysource user database. Returns null
	# or 0 or anything that doesn't resolve to a int
	# greater than 0 in authentication falied.
	function get_userid($login='',$password='') {
		return $this->users_system->db->single_element("SELECT userid FROM user WHERE login='".addslashes($login)."' AND password=password('".addslashes($password)."')");
	}


	 ###################################################
	# Generate random passwords when creating new users
	# in the MySource database?
	function generate_password() {
		return true;
	}


	#####################################################
	# Loads the extension details, from cache or database
	# Trivial.. to be expanded by chidlren
	function load() {

		 ###############################################
		# Check the cache - but remember where mum is !
		#if ($this->load_from_cache()) return true;

		$this->set_generated_information();

		if(!file_exists($this->data_path)) {
			create_directory($this->data_path);
		}
		$this->parameters = unserialize(file_to_string("$this->data_path/$this->codename.param"));
		if(!is_array($this->parameters)) $this->parameters = array();

		return true;
	}


	 #####################################################
	# Loads the extension details, from cache or database
	# Trivial.. to be expanded by chidlren
	function save_parameters() {
		return string_to_file(serialize($this->parameters),"$this->data_path/$this->codename.param");
	}

	 ##########################################################
	# Returns whether this object is public or not, used to
	# decide how to get the images piped through
	function public() {
		return false; # let's be more secure by default
	}

	 #########################################################################
	# Returns a href to a file associated with this extension
	function get_file_href($filename='') {
		# if we are public then anyone can view our files so no piping
		if ($this->public()) {
			return data_href("users/authentication/$this->codename/$filename");
		# OK so we are going to need some security on this
		} else {
			return web_href("mysource_action=send_file&type=users&file=extensions/$this->codename/$filename", true);
		}
	}#end get_file_href()


	 ##################################################
	# Returns the HREF to the backend for this object
	function get_backend_href() {
		return $this->users_system->get_backend_href()."&authentication_edit=$this->codename";
	}

	 ################################################################
	# set's up some useful information that shouldn't be cached
	function set_generated_information() {

		 ########################################################
		# Let us get some consitancy in what we call our tables
		global $XTRAS, $XTRAS_PATH;
		$this->codename   = $XTRAS->codename("users/authentication",ereg_replace("^users_authentication_","",get_class($this)));
		$this->name       = $XTRAS->name("users/authentication",$this->codename);
		$this->version    = $XTRAS->version("users/authentication",$this->codename);
		$this->table_name = "xtra_users_authentication_$this->codename";
		$this->xtra_path  = "$XTRAS_PATH/users/authentication/$this->codename";

		# update the data path to represent our new status
		$this->data_path = get_data_path($this->public(), "users/authentication/".$this->codename);

	}# set_generated_information()

	 ################################################################
	# returns true if the current user can view the passed file
	function file_view_access($file='') {
		return false; # more secure by default
	}# file_view_access()

     ############################
	# Sets up the backend for use
	function &setup_backend() {
		if(!file_exists($this->data_path)) {
			create_directory($this->data_path);
		}

		 ###################################
		# Setup the backend a little
		$backend = &$this->users_system->setup_backend(); # Backend refrence
		$backend->set_title("$this->name - Users Authentication");

		$backend->set_tab("users_authentication","users.php?authentication_edit=$this->codename",$this->name,$this->get_xtra_description(),"xtra");
		$backend->set_active_tab("users_authentication");

		$backend->set_heading("Users Authentication: $this->name v$this->version",'users');

		$backend->set_hidden_field('action','');
		$backend->set_hidden_field('authentication_edit',$this->codename);

		return $backend;
	}


	 ########################################################
	# Gets a references to the parameter set (if there is one)
	function &get_pset() {
		if(get_class($this->temp['pset']) !== "parameter_set") {
			$this->temp['pset'] = new Parameter_Set(get_class($this),"$this->xtra_path/$this->codename.pset",$this->parameters, $this);
		}
		return $this->temp['pset'];
	}

	 ##################################################################
	# Prints the backend for the user - usually completely overwritten
	function print_backend() {

		$backend = &$this->setup_backend();
		$session = &get_mysource_session();
		if(!user_root()) {
			$session->login_screen($backend->title,"You must be logged in as root in order to configure <i>$this->name</i> authentication parameters.");
		}

		$pset = &$this->get_pset();
		$pset->set_tabs($backend,'authentication_screen', $this->get_backend_href());
		if($pset->process($backend)) {
			$this->save_parameters();
		}
		$pset->print_backend($backend);
	}


}


?>
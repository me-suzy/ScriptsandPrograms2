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
## File: include/users_extension.inc
## Desc: Parent class for users extension xtra classes - extensions to the entire users database
## $Source: /home/cvsroot/mysource/include/users_extension.inc,v $
## $Revision: 2.6 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:59 $
#######################################################################
#---------------------------------------------------------------------#

class Users_Extension extends UsersObject {
	
	# A strign representing the root db table name 
	var $table_name='';

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
	function Users_Extension() {
		UsersObject::UsersObject();
		$this->set_generated_information();
	}


	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = UsersObject::__sleep();
		array_remove_element("codename",$result);
		array_remove_element("name",$result);
		array_remove_element("version",$result);
		array_remove_element("table_name",$result);
		array_remove_element("xtra_path",$result);
		array_remove_element("data_path",$result);
		return $result;
	}


	 #################################################
	# Wake up with this handy information EVERY day !
	function __wakeup() {
		UsersObject::__wakeup();
		$this->set_generated_information();
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
	function &get_extension_description() {
		global $XTRAS;
		return $XTRAS->description("users/extensions",$this->codename);
	}

	 ####################################################################
    # Removes the extension utterly from existance !
	# This basically resets everythign.. not sure if you'd ever need this
    function delete()   {
        $db = &$this->get_db();
        $this->clear_cache($this->id);
		return $db->delete("DELETE FROM $this->table_name");
    }

	 #####################################################################################################
	# Returns whether this object is public or not, used to decide how to get the images piped through
	function public() {
		return false; # let's be more secure by default
	}

	 #########################################################################
	# Returns a href to a file associated with this extension
	function get_file_href($filename='') {
		# if we are public then anyone can view our files so no piping
		if ($this->public()) {
			return data_href("users/extensions/$this->codename/$filename");
		# OK so we are going to need some security on this
		} else {
			return web_href("mysource_action=send_file&type=users&file=extensions/$this->codename/$filename", true);
		}
	}#end get_file_href()


	 ##################################################
	# Returns the HREF to the backend for this object
	function get_backend_href() {
		return $this->users_system->get_backend_href()."&extension_edit=$this->codename";
	}

	 ################################################################
	# set's up some useful information that shouldn't be cached
	function set_generated_information() {

		 ########################################################
		# Let us get some consitancy in what we call our tables
		global $XTRAS, $XTRAS_PATH;
		$this->codename   = $XTRAS->codename("users/extensions",ereg_replace("^users_extension_","",get_class($this)));
		$this->name       = $XTRAS->name("users/extensions",$this->codename);
		$this->version    = $XTRAS->version("users/extensions",$this->codename);
		$this->table_name = "xtra_users_extension_$this->codename";
		$this->xtra_path  = "$XTRAS_PATH/users/extensions/$this->codename";

		# update the data path to represent our new status
		$this->data_path = get_data_path($this->public(), "users/extensions/".$this->codename);

	}# set_generated_information()

	 ################################################################
	# returns true if the current user can view the passed file
	function file_view_access($file='') {
		return false; # more secure by default
	}# file_view_access()

     ############################
	# Sets up the backend for use
	function &setup_backend() {

		create_directory($this->data_path);

		 ###################################
		# Setup the backend a little
		$backend = &$this->users_system->setup_backend(); # Backend refrence
		$backend->set_title("$this->name - Users Extension");

		$backend->set_tab("users_extension","users.php?extension_edit=$this->codename",$this->name,$this->get_extension_description(),"xtra");
		$backend->set_active_tab("users_extension");

		$backend->set_heading("$this->name v$this->version","xtra");

		$backend->set_hidden_field("action","");
		$backend->set_hidden_field("extension_edit",$this->codename);

		return $backend;
	}


     ##################################################################
	# Prints the backend for the user - usually completely overwritten
	function print_backend() {
		$backend = &$this->setup_backend();
		$backend->print_header();		
		$backend->open_section("This users extension is under construction.");		
		$backend->print_footer();		
	}


}


?>
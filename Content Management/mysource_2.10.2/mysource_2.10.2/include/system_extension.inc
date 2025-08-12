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
## $Source: /home/cvsroot/mysource/include/system_extension.inc,v $
## $Revision: 2.6 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:59 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/systemobject.inc");
#---------------------------------------------------------------------#

class System_Extension extends SystemObject {
	
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

	# Directory where this extension keeps its files.
	var $data_path='';

	 ##############################
	# Constructor
	function System_Extension() {
		SystemObject::SystemObject();
		return $this->load();
	}


	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = SystemObject::__sleep();
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
		SystemObject::__wakeup();
		$this->set_generated_information();
	}


	 ######################################################################
	# Create a new record in the database and load it into this object
	# This is  trivial but may be expanded in child classes to 
	# allow multiple instances of an extension in a system.
	function create() {
		$db = &$this->get_db();
		if($db->single_element("SELECT COUNT(*) FROM $this->table_name")) {
			$this->_set_error("This system is already using the '$name' system extension.",__FILE__,__LINE__);
			return 0;
		}
		if($db->insert("INSERT INTO $this->table_name () VALUES ('$this->codename')")) {
			return true;
		}
		return false;
	}

	
	 ##################################################
	# Handy little wrappers

	function &get_web() {
		return get_web_system();
	}

	function &get_site($siteid=0) {
		$web = &$this->get_web();
		$site = $web->get_site($siteid);
		return $site;
	}

	function &get_page($pageid=0) {
		$web = &$this->get_web();
		return $web->get_page($pageid);
	}

	function &get_extension_description() {
		global $XTRAS;
		return $XTRAS->description("system/extensions",$this->codename);
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
			return data_href("system/extensions/$this->codename/$filename");
		# OK so we are going to need some security on this
		} else {
			$site = &$this->web_system->get_site();
			return $site->get_href(true)."mysource_action=send_file&type=system&file=extensions/$this->codename/$filename";
		}
	}#end get_file_href()

	 ################################################################
	# set's up some useful information that shouldn't be cached
	function set_generated_information() {

		 ########################################################
		# Let us get some consitancy in what we call our tables
		global $XTRAS,$XTRAS_PATH;
		$this->codename   = $XTRAS->codename("system/extensions",ereg_replace("^system_extension_","",get_class($this)));
		$this->name       = $XTRAS->name("system/extensions",$this->codename);
		$this->version    = $XTRAS->version("system/extensions",$this->codename);
		$this->table_name = "xtra_system_extension_$this->codename";
		$this->xtra_path  = "$XTRAS_PATH/system/extensions/$this->codename";
		
		# update the data path to represent our new status
		$this->data_path = get_data_path($this->public(), "system/extensions/".$this->codename);

	}# set_generated_information()

	 ################################################################
	# returns true if the current user can view the passed file
	function file_view_access($file="") {
		return false; # more secure by default
	}# file_view_access()
	 
	 #####################################################
	# Loads the extension details, from cache or database
	# Trivial.. to be expanded by chidlren
	function load() {

		 ###############################################
		# Check the cache - but remember where mum is !
		if ($this->load_from_cache($this->codename)) return true;

		 #################################################
		# Load from the database - not everything though 
		$db = &$this->get_db();

		 #####################################
		# Create a new record if none exists
		if (!$db->single_element("SELECT COUNT(*) FROM $this->table_name")) {
			return $this->create();
		}

		 ####################################################################
		# Save to the cache - probably not wise to do this here, but
		# children should do it when they're ready in their load() overloads
		$this->save_to_cache($this->codename);

		return true;
	}


	 ####################################################################
    # Removes the extension utterly from existance !
	# This basically resets everythign.. not sure if you'd ever need this
    function delete()   {
        $db = &$this->get_db();
        $this->clear_cache($this->id);
		return $db->delete("DELETE FROM $this->table_name");
    }



     ############################
	# Sets up the backend for use
	function &setup_backend() {
		$web = &$this->get_web();
		 ###################################
		# Setup the backend a little
		$backend = $web->setup_backend(); # Backend refrence
		
		$backend->set_title("System - $this->name Extension");
		$backend->set_tab("system_extension","mysource.php?extension_edit=$this->codename",$this->name,$this->get_extension_description(),"swish");
		$backend->set_active_tab("system_extension");

		$backend->set_heading($this->name,"swish");
		$backend->set_hidden_field("action","");
		$backend->set_hidden_field("extension_edit",$this->codename);

		return $backend;
	}


     ##################################################################
	# Prints the backend for the user - usually completely overwritten

}


?>
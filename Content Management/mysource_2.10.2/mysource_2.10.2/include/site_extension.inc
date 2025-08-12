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
## File: include/site_extension.inc
## Desc: Parent class for site extension xtra classes
## $Source: /home/cvsroot/mysource/include/site_extension.inc,v $
## $Revision: 2.11 $
## $Author: bvial $
## $Date: 2003/11/25 04:17:06 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Extension extends WebObject {
	
	# Directory where this site keeps its files.
	var $data_path='';  
	# path used to get to the files in $this->data_path
	
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

	# The siteid this object extends
	var $id = 0;

	# a boolean specifiying whether the backend has been setup or not
	var $_backend_setup = false;

	# Temporary runtime variables
	var $temp='';

	 ##############################
	# Constructor
	function Site_Extension($siteid=0) {

		WebObject::WebObject();

		 ############################################
		# Load the information if an id is specified
		if ($siteid) {
			return $this->load($siteid);
		}

	}


	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = WebObject::__sleep();
		array_remove_element('codename',$result);
		array_remove_element('name',$result);
		array_remove_element('version',$result);
		array_remove_element('table_name',$result);
		array_remove_element('data_path',$result);
		array_remove_element('xtra_path',$result);
		array_remove_element('_backend_setup',$result);
		array_remove_element('temp',$result);
		return $result;
	}


	 ##############################################
	# Wake up with this handy information EVERY day !
	function __wakeup() {
		WebObject::__wakeup();
		$this->set_generated_information();
	}


	 ######################################################################
	# Create a new record in the databse and load it into this object
	function create($siteid=0) {
		$db = &$this->get_db();
		if($db->single_element("SELECT siteid FROM $this->table_name WHERE siteid='$siteid'")) {
			$this->_set_error("This site is already using this site extension.",__FILE__,__LINE__);
			return 0;
		} elseif(!$db->single_element("SELECT siteid FROM site WHERE siteid='$siteid'")) {
			$this->_set_error("There is no record for this siteid: $siteid",__FILE__,__LINE__);
			return 0;
		}
		if($db->insert("INSERT INTO $this->table_name (siteid) VALUES ('$siteid')")) {
			$this->id = $siteid;
		}
		$this->load($siteid);
		$this->set_generated_information();
		create_directory($this->data_path);
		return $this->id;
	}

	
	 ##################################################
	# Handy little wrappers
	function &get_site($siteid=0) {
		if(!$siteid) $siteid = $this->id;
		$site = &$this->web_system->get_site($siteid);
		return $site;
	}

	function &get_page($pageid=0) {
		return $this->web_system->get_page($pageid);
	}

	function &get_extension_description() {
		global $XTRAS;
		return $XTRAS->description("site/extensions",$this->codename);
	}

	 #######################################################################
	# Returns a href that will active this site extension on the frontend
	function get_href($start_query_string=false) {
		$site = &$this->get_site($this->id);
		return $site->get_href(true).'mysource_site_extension='.$this->codename.(($start_query_string) ? '&' : '');
	}

	 #####################################
	# Lets get the URL for this extension
	# Force secure is a flag, 0 - use default, 1 - use secure, 2 - use insecure
	function get_url($start_query_string=false,$force_secure=0) {
		$site = &$this->get_site($this->id);
		return $site->get_url(true,$force_secure).'mysource_site_extension='.$this->codename.(($start_query_string) ? '&' : '');
	}

	 ###########################################################
	# Returns a href to a file associated with this site_design
	function get_file_href($filename='') {
		$site = &$this->get_site($this->id);
		return $site->get_file_href("extensions/".$this->codename."/$filename");
	}

	 #####################################################
	# Returns the href to get to this extensions backend
	function get_backend_href() {
		$site = &$this->get_site();
		return $site->get_backend_href()."&extension_edit=$this->codename";
	}


	#####################################################
	# Saves the current parameter array into the database
	function save_parameters() {
		$this->parameters['__id__']      = $this->codename;
		$this->parameters['__version__'] = $this->version;
		$db = &$this->get_db();
		if ($ret_val = $db->update("UPDATE $this->table_name SET parameters='".addslashes(serialize($this->parameters))."' WHERE siteid='$this->id'")) {
			$this->updated();
		}
		return $ret_val;
	}


	########################################################
	# Gets a references to the parameter set (if there is one)
	function &get_pset() {
		if(get_class($this->temp['pset']) !== "parameter_set") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/parameter_set.inc");
			$this->temp['pset'] = new Parameter_Set(get_class($this),"$this->xtra_path/$this->codename.pset", $this->parameters, $this);
		}
		return $this->temp['pset'];
	}

	######################################
	# Update this extension then reload 
	function updated() {
		$site = &$this->get_site();
		$site->updated();
		# now reload this object, just in case
		$this->clear_cache();
		$this->load();
	}

	 
	 ################################################################
	# set's up some useful information that shouldn't be cached
	function set_generated_information() {

		 ########################################################
		# Let us get some consitancy in what we call our tables
		global $XTRAS, $XTRAS_PATH;
		$this->codename = $XTRAS->codename("site/extensions",ereg_replace("^site_extension_","",get_class($this)));
		$this->name        = $XTRAS->name("site/extensions",$this->codename);
		$this->version     = $XTRAS->version("site/extensions",$this->codename);
		$this->table_name  = "xtra_site_extension_$this->codename";
		$this->xtra_path   = "$XTRAS_PATH/site/extensions/$this->codename";
		
		$site = $this->get_site($this->id);
		$this->data_path = $site->data_path."/extensions/".$this->codename;

	}# set_generated_information()

	 #####################################################
	# Loads the extension details, from cache or database
	function load($siteid=0) {
		if ($siteid <= 0 && !($siteid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load $this->name site extension without a valid siteid.",__FILE__,__LINE__);
		}

		 ###############################################
		# Check the cache - but remember where mum is !
		if ($this->load_from_cache($siteid)) {
			return $siteid;
		}

		 #################################################
		# Load from the database - not everything though 
		$db = &$this->get_db();
		list($this->id) = $db->single_row("SELECT siteid FROM $this->table_name WHERE siteid='$siteid'");


		 #####################################
		# Create a new record if none exists
		if (!$this->id) {
			return $this->create($siteid);
		}

		$this->set_generated_information();

		 ####################################################################
		# Save to the cache - probably not wise to do this here, but
		# children should do it when they're ready in their load() overloads
		#$this->save_to_cache($siteid);

		return $siteid;
	}


	 #################################
    # Duplicates the entire extension
    function dupe($new_siteid=0) {
        # each extension is to have their own dupe() function  which takes care of everything.
        # if not, it is defined here, so no errors will occur.
    }


	 #######################################################
    # Removes the extension utterly from existance !
    function delete()   {
		delete_directory($this->data_path);
        $db = &$this->get_db();
        $this->clear_cache($this->id);
		return $db->delete("DELETE FROM $this->table_name WHERE siteid = '$this->id'");
    }

     ############################
	# Sets up the backend for use
	function &setup_backend() {

		 ###################################
		# Setup the backend a little
		$site = &$this->get_site();
		$backend = &$site->setup_backend(); # Backend refrence
		if (!$this->_backend_setup) {

			$backend->set_title("$site->name - $this->name Extension");
		
			$backend->clear_tabs();
			$backend->set_tab("site_extension_$this->codename", "site.php?s=$site->id&extension_edit=$this->codename", $this->name, $this->get_extension_description(), "xtra");
			$backend->set_active_tab("site_extension_$this->codename");

			$backend->set_heading("$this->name v$this->version","xtra");
			$backend->set_hidden_field("action","");
			$backend->set_hidden_field("siteid",$this->id);
			$backend->set_hidden_field("extension_edit",$this->codename);

			$this->_backend_setup = true;

		}#end if

		return $backend;
	}


     ##################################################################
	# Prints the backend for the user - usually completely overwritten
	function print_backend() {
		$backend = &$this->setup_backend();
		$backend->print_header();		
		$backend->open_section("This site extension has no options.");		
		$backend->print_footer();		
	}


	 ################################################
	# Prints or does various things on the front-end
	function print_frontend() {
		# Fill me in, my children.
	}

	 ###########################################################
	# Returns the title as it should be printed on the frontend
	function get_title() {
		return $this->name;
	}

	 ####################################################################################
	# Prints what is to appear in the content area on the frontend, called by the design
	function print_body() {
	}

}


?>
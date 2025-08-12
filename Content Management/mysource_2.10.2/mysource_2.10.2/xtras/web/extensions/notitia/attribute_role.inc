<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/attribute_role.inc,v $
## $Revision: 1.4 $
## $Author: achadszinow $
## $Date: 2003/12/18 23:13:31 $
#######################################################################

#---------------------------------------------------------------------#

 #################################################################
# A role is a part to be played in the grand russian ballet of
# Notitia. Attributes an be given diffeent roles and they are
# used for various functions that happen on the frontend.
class Web_Extension_Notitia_Attribute_Role extends Web_Extension_Notitia_Object {
	
	var $attribute;  # A reference to the attribute what ownz us
	var $parameters; # A reference to the array where all the params are stored

	 #############
	# Constructor
	function Web_Extension_Notitia_Attribute_Role(&$attribute) {
		Web_Extension_Notitia_Object::Web_Extension_Notitia_Object();
		$this->attribute  = &$attribute;
		if(isset($this->attribute->roles[$this->codename])) {
			$this->parameters = &$this->attribute->roles[$this->codename];
		}
	}

	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = Web_Extension_Notitia_Object::__sleep();
		array_remove_element("temp",$result);
		array_remove_element("attribute",$result);
		array_remove_element("parameters",$result);
		array_remove_element("codename",$result);
		array_remove_element("name",$result);
		array_remove_element("table_name",$result);
		array_remove_element("version",$result);
		array_remove_element("xtra_path",$result);
		return $result;
	}

	 ##############################################
	# Wake up with this handy information EVERY day !
	function __wakeup() {
		Web_Extension_Notitia_Object::__wakeup();
		$e     = &$this->get_notitia_system();
		$xtras = &$e->get_xtras();
		$this->codename     = $xtras->codename("attribute/roles", ereg_replace("^".get_parent_class($this)."_", "", get_class($this)));
		$this->name         = $xtras->name("attribute/roles",$this->codename);
		$this->table_name   = $e->table_name."_attribute_role";
		$this->version      = $xtras->version("attribute/roles",$this->codename);
		$this->xtra_path    = "$e->xtra_path/xtras/attribute/roles/$this->codename";
		$this->temp         = array();
	}

	 ########################################
	# Gets a references to the parameter set
	function &get_pset() {
		$pset = &$this->temp['parameter_set'];
		if(get_class($pset) !== "parameter_set") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/parameter_set.inc");
			$pset = new Parameter_Set(get_class($this),"$this->xtra_path/$this->codename.pset",$this->parameters, $this);
		}
		return $pset;
	}

	 #####################################################
	# Saves the current parameter array into the database
	function save_parameters() {
		$db = &$this->get_db();
		$r = $db->update("REPLACE INTO $this->table_name (attributeid,role,parameters) VALUES ('{$this->attribute->id}','$this->codename','".addslashes(serialize($this->parameters))."')");
		if($r) {
			$this->attribute->clear_cache();
		}
		return $r;
	}

	 ##################################################
	# Loads the default parameter set that we assume
	# is included with the class
	function load_default_parameters() {
		$message = "";
		$this->load_parameters_from_file("$this->xtra_path/default.param",$message);
		return $message;
	}

	 #################################
	# Load the parameters from a file
	function load_parameters_from_file($file,&$message) {
		$p = unserialize(file_to_string($file));
		if(is_array($p)) {
			$this->parameters = &$p;
			return 1;
		}
		$message = "Unable to import parameters. The file is corrupt.";
		return;
	}

	 #################################
	# Save the parameters to a file
	function save_parameters_to_file($file) {
		return string_to_file(serialize($this->parameters),$file);
	}

	############################################################

	 ##################################################
	# Allows the current function to apply for a role
	function apply_for_role() {
		$this->attribute->roles[$this->codename] = '';
		$this->parameters = &$this->attribute->roles[$this->codename];
		$this->load_default_parameters();
		$this->save_parameters();
		$this->attribute->clear_cache();
		$this->attribute->clear_associated_record_caches();
		return "Attribute '{$this->attribute->name}' now functioning in the role of '$this->name'.";
	}

	 ##################################################
	# Allows the current function to try to resign from
	# a roll. This is not guaranteed to work. (A role may
	# need to stay active for system integrity).
	function resign_from_role() {
		unset($this->attribute->roles[$this->codename]);
		$db = &$this->get_db();
		$db->delete("DELETE FROM $this->table_name WHERE attributeid='{$this->attribute->id}' AND role='$this->codename'");
		$this->attribute->clear_cache();
		$this->attribute->clear_associated_record_caches();
		return "Attribute '{$this->attribute->name}' no longer functioning in the role of '$this->name'.";
	}

	############################################################

	 #################################################
	# Returns the value of soemthign int he parameters
	function get_value($code) {
		return $this->parameters[$code];
	}

	 #################################################
	# Returns the value of soemthign int he parameters
	function set_value($code,$value) {
		return $this->parameters[$code] = $value;
	}

	 ######################################################
	# Returns an integers, 0, 1, or 2 (overriden by child
	# classes) which indicates the level of complexity of
	# the parameter interface.
	# 0 = No parameters
	# 1 = Small amount of ptions displayed on that attribute screen
	# 2 = requires a link to another screen of ptions
	function parameter_interface_level() {
		return 0;
	}

	 #################################################
	# Prints the backend option of the attribute
	# not counting universal attribute options, these
	# are handled by attribute.inc
	function print_backend() {
		$e = &$this->get_notitia_system();
		$a = &$this->attribute;
		$backend = &$this->get_backend();
		$pset = &$this->get_pset();
		$pset->print_backend($backend,1);
	}

	 ###################################################
	# Processes the particular parameters  used by this
	# type
	function process_backend() {
		$e = &$this->get_notitia_system();
		$a = &$this->attribute;
		$backend = &$this->get_backend();
		$pset = &$this->get_pset();
		$pset->process($backend);
	}

	/**
	* Gets the compatible attribute types of this role as an array of attribute types
	*
	* @access  public
	* @returns array
	*/
	function get_compatible_attribute_types() {
		return $this->get_setting('compatible_attributes');
	}

	/**
	* Gets attribute role settings
	*
	* @param   string $setting_index The index of the array
	* @access  public
	* @returns mixed
	*/
	function get_setting($setting_index) {
		return $this->settings[$setting_index];
	}

	/**
	* Lets us know if a role is allowed for a particular this attribute
	*
	* @access  public
	* @returns boolean
	*/
	function role_allowed() {
		$compatible_attribute_types = $this->get_compatible_attribute_types();
		return in_array($this->attribute->type, $compatible_attribute_types);
	}
}
?>
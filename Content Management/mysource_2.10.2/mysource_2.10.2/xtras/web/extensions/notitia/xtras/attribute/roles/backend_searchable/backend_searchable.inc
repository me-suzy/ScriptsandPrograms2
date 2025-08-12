<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/roles/backend_searchable/backend_searchable.inc,v $
## $Revision: 1.4 $
## $Author: achadszinow $
## $Date: 2003/12/18 23:13:32 $
#######################################################################

#---------------------------------------------------------------------#

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Role_Backend_Searchable extends Web_Extension_Notitia_Attribute_Role {

	var $settings = array(
		'compatible_attributes' => array() # Whether an attribute can use this depends on if the attribute is searchable
	);

	 ####################################################
	# Requires only a bit of screen space for its thingy
	function parameter_interface_level() {
		return 1;
	}

	 ###################
	# Prints da backend
	function print_backend() {
		echo combo_box(get_class($this).'_format',array_merge(array('_default_'=>"-- Default Format --"),$this->attribute->get_type_setting('search_formats')),$this->get_value('format'),'class=data',70);
	}

	function process_backend() {
		$var_prefix = get_class($this).'_';
		$global_vars = array('format');
		foreach($global_vars as $global_var) {
			$$global_var = $_REQUEST[$var_prefix.$global_var];
			if (is_string($$global_var)) $$global_var = gpc_stripslashes($$global_var);
		}
		$this->parameters['format'] = $format;
		$this->save_parameters();
	}

	/**
	* Lets us know if this attribute can use this role
	*
	* @access  public
	* @returns boolean
	*/
	function role_allowed() {
		$attribute_type_handler = &$this->attribute->get_type_handler();
		$search_formats = $attribute_type_handler->get_setting('search_formats');
		return (!empty($search_formats));
	}
}
?>
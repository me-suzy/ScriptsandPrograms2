<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/roles/force_null/force_null.inc,v $
## $Revision: 1.4 $
## $Author: achadszinow $
## $Date: 2003/12/18 23:13:32 $
#######################################################################

#---------------------------------------------------------------------#

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Role_Force_Null extends Web_Extension_Notitia_Attribute_Role {

	var $settings = array(
		'compatible_attributes' => array() # Depend on the attribute calling
	);

	 ####################################################
	# Requires only a bit of screen space for its thingy
	function parameter_interface_level() {
		return 1;
	}

	 ###################
	# Prints da backend
	function print_backend() {
		echo("<input type=checkbox name=\"".get_class($this)."_strict\" value=1 ".(($this->get_value('strict'))?'checked':'').'> Strict (subcategories cannot vary the default). ');
	}

	function process_backend() {
		$var_prefix = get_class($this).'_';
		$global_vars = array('strict');
		foreach($global_vars as $global_var) {
			$$global_var = $_REQUEST[$var_prefix.$global_var];
			if (is_string($$global_var)) $$global_var = gpc_stripslashes($$global_var);
		}
		$this->parameters['strict'] = $strict;
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
		$requires_context_values = $attribute_type_handler->get_setting('requires_context_values');
		return (!$requires_context_values);
	}
}
?>
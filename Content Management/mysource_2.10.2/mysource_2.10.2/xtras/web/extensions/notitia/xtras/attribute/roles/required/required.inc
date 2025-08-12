<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/roles/required/required.inc,v $
## $Revision: 1.3 $
## $Author: achadszinow $
## $Date: 2003/12/18 23:13:32 $
#######################################################################

#---------------------------------------------------------------------#

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Role_Required extends Web_Extension_Notitia_Attribute_Role {

	var $settings = array(
		'compatible_attributes' => array() # Depend on the attribute calling
	);

	/**
	* Lets us know if a role is allowed for a particular this attribute
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
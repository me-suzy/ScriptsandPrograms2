<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/roles/auto_increment/auto_increment.inc,v $
## $Revision: 1.7.2.1 $
## $Author: achadszinow $
## $Date: 2004/04/16 07:06:01 $
#######################################################################

#---------------------------------------------------------------------#

 ####################
# For text fields...
class Web_Extension_Notitia_Attribute_Role_Auto_Increment extends Web_Extension_Notitia_Attribute_Role {

	var $settings = array(
		'compatible_attributes' => array(
			'number'
		)
	);

	 #################################################
	# Returns the newest value and remembers it
	function increment($number=null) {
		$db = &$this->get_db();

		# Lock the table
		$table_name = $this->attribute->get_value_table_name();
		$db->select('LOCK TABLE xtra_web_extension_notitia_attribute_role_auto_increment WRITE, '.$table_name.' READ');
		$current_max = $db->single_element('SELECT value FROM xtra_web_extension_notitia_attribute_role_auto_increment WHERE attributeid = '.$this->attribute->id);
		# Nothing there lets try to get it from the value table
		if (!$current_max) {
			$current_max = $db->single_element('SELECT FLOOR(MAX(value)) FROM '.$table_name.' WHERE attributeid = '.$this->attribute->id);
		}

		$returning_number = 0;

		if (!is_null($number)) {
			# If they give us number we can just try to keep things right and return them the number they gave us
			$returning_number = $number;
			if ($number > $current_max || !$current_max) {
				$db->replace('REPLACE INTO xtra_web_extension_notitia_attribute_role_auto_increment (value,attributeid) VALUES ('.$returning_number.','.$this->attribute->id.')');
			}
		} else {
			$returning_number = floor($current_max) + 1;
			$db->replace('REPLACE INTO xtra_web_extension_notitia_attribute_role_auto_increment (value,attributeid) VALUES ('.$returning_number.','.$this->attribute->id.')');
		}

		# Unlock the table
		$db->select("UNLOCK TABLES");

		return $returning_number;
	}

	/**
	* Processes the backend
	*
	* @access public
	* @return string
	*/
	function apply_for_role() {
		$db = &$this->get_db();
		# Lock the table
		$table_name = $this->attribute->get_value_table_name();
		$db->select('LOCK TABLE xtra_web_extension_notitia_attribute_role_auto_increment WRITE, '.$table_name.' READ');
		$role_max = $db->single_element('SELECT value FROM xtra_web_extension_notitia_attribute_role_auto_increment WHERE attributeid = '.$this->attribute->id);
		if (!$role_max) {
			$role_max = 0;
		}
		$value_max = $db->single_element('SELECT FLOOR(MAX(value)) FROM '.$table_name.' WHERE attributeid = '.$this->attribute->id);
		if ($value_max > $role_max) {
			$setting_number = $value_max;
		} else {
			$setting_number = $role_max;
		}
		$db->replace('REPLACE INTO xtra_web_extension_notitia_attribute_role_auto_increment (value,attributeid) VALUES ('.$setting_number.','.$this->attribute->id.')');

		# Unlock the table
		$db->select("UNLOCK TABLES");

		parent::apply_for_role();
	}
}
?>

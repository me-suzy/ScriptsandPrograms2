<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/attribute/roles/value_table/value_table.inc,v $
## $Revision: 1.4 $
## $Author: achadszinow $
## $Date: 2004/01/29 22:02:42 $
#######################################################################

#---------------------------------------------------------------------#

/**
* Lets all attributes choose a table to store their value in
*/
class Web_Extension_Notitia_Attribute_Role_Value_Table extends Web_Extension_Notitia_Attribute_Role {

	var $settings = array(
		'compatible_attributes' => array() # Depend on the attribute calling
	);

	/**
	* Says how deep this can be
	*
	* @access public
	* @return int
	*/
	function parameter_interface_level() {
		return 1;
	}

	/**
	* Returns the extra code to tell you the extention on the attribute value table
	*
	* @access public
	* @return string
	*/
	function get_value_table_code() {
		$valid_values = array('0', '2', '3');
		$value_table_code = $this->get_value('code');
		if (!in_array($value_table_code, $valid_values)) return ''; # Which is default
		if ($value_table_code == '0') return ''; # Which is default
		return $value_table_code;
	}

	/**
	* Prints the backend
	*
	* @access public
	* @return void
	*/
	function print_backend() {
		# 0 Is special. At runtime if the code is 0 or not set the code for the table is blank
		$selection = combo_box(get_class($this).'_code', array('0'=>'Main', '2'=>'Secondary', '3' =>'Tertiary'), $this->get_value('code'), '', '' , 1);
		echo $selection;
	}

	/**
	* Processes the backend
	*
	* @access public
	* @return string
	*/
	function process_backend() {
		$old_code = $this->get_value_table_code();
		if (isset($_POST[get_class($this).'_code']) && $old_code != $_POST[get_class($this).'_code']) {
			$this->parameters['code'] = $_POST[get_class($this).'_code'];
			$this->save_parameters();

			# Now move the data
			$new_code = $this->get_value_table_code();
			$n = &$this->get_notitia_system();
			$old_value_table_name = $n->table_name.'_attribute_value'.$old_code;
			$new_value_table_name = $n->table_name.'_attribute_value'.$this->get_value_table_code();
			$db = &$this->get_db();
			if ($db->table_exists($new_value_table_name)) {
				$attributeid = $this->attribute->id;
				$db->insert("INSERT INTO $new_value_table_name (recordid, attributeid, value) SELECT recordid, attributeid, value FROM $old_value_table_name WHERE attributeid = $attributeid");
				$db->delete("DELETE FROM $old_value_table_name WHERE attributeid = $attributeid");
				return 'Data Moved<br />';
			} else {
				$this->parameters['code'] = $old_code;
				$this->save_parameters();
				return 'Table does not exist so changes ignored<br />';
			}
		}
	}

	/**
	* Lets us know if a role is allowed for a particular this attribute
	*
	* @access  public
	* @returns boolean
	*/
	function role_allowed() {
		$n = &$this->get_notitia_system();
		$attribute_type_handler = &$this->attribute->get_type_handler();
		$requires_context_values = $attribute_type_handler->get_setting('requires_context_values');
		$value_table_name = $attribute_type_handler->get_value_table_name();
		if ($requires_context_values) {
			return false;
		} elseif ($value_table_name != $n->table_name.'_attribute_value' && $value_table_name != $n->table_name.'_attribute_value2' && $value_table_name != $n->table_name.'_attribute_value3') {
			return false;
		}
		return true;
	}
}
?>
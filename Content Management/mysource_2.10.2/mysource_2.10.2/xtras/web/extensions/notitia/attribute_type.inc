<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/attribute_type.inc,v $
## $Revision: 1.27 $
## $Author: tbarrett $
## $Date: 2004/03/25 22:00:52 $
#######################################################################

#---------------------------------------------------------------------#

 #################################################################
# A parent class for attribute types. The offer different options
# and functionality to the attributes you can assign to records.
class Web_Extension_Notitia_Attribute_Type extends Web_Extension_Notitia_Object {
	
	var $type      = 'none';
	var $type_name = 'None';
	var $id = '';

	var $attribute;  # A reference to the attribute what ownz us
	var $parameters; # A reference to the array where all the params are stored

	 ################################################################
	# This is a static thingy that stores special information about
	# a particular type.
	var $settings = array(
			'preferred_alignment' => 'left',
			'generates_varieties' => false,
			'variable'            => true,
			'variation_types'     => array (
				'replace' => 'Replace'
			),
			'value_formats' => array(
				'' => 'Raw'
			),
			'search_formats' => array(
				'match' => 'Complete Match'
			)
		);

	 #############
	# Constructor
	function Web_Extension_Notitia_Attribute_Type(&$attribute) {
		Web_Extension_Notitia_Object::Web_Extension_Notitia_Object();
		$this->attribute  = &$attribute;
		$this->parameters = &$this->attribute->parameters;
		$this->id = $attribute->id;
	}

	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = Web_Extension_Notitia_Object::__sleep();
		array_remove_element('temp',$result);
		array_remove_element('parameters',$result);
		array_remove_element('attribute',$result);
		array_remove_element('codename',$result);
		array_remove_element('name',$result);
		array_remove_element('version',$result);
		array_remove_element('xtra_path',$result);
		return $result;
	}

	 #################################################
	# Wake up with this handy information EVERY day !
	function __wakeup() {
		Web_Extension_Notitia_Object::__wakeup();
		$e     = &$this->get_notitia_system();
		$xtras = &$e->get_xtras();
		$this->codename     = $xtras->codename('attribute/types', ereg_replace('^'.get_parent_class($this).'_', '', get_class($this)));
		$this->name         = $xtras->name('attribute/types',$this->codename);
		$this->version      = $xtras->version('attribute/types',$this->codename);
		$this->xtra_path    = "$e->xtra_path/xtras/attribute/types/$this->codename";
		$this->temp         = array();
	}

	 ########################################
	# Gets a references to the parameter set
	function &get_pset() {
		if(!isset($this->temp['parameter_set'])) {
			$this->temp['parameter_set'] = array();
		} 
		$pset = &$this->temp['parameter_set'];
		if(get_class($pset) !== "parameter_set") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/parameter_set.inc");
			array_contents($this,2);
			$pset = new Parameter_Set(get_class($this), "$this->xtra_path/$this->codename.pset", $this->parameters, $this);
		}
		return $pset;
	}

	 ###################################################
	# Loads the default parameter set that we assume
	# is included with the class
	function load_default_parameters() {
		$message = '';
		$this->load_parameters_from_file("$this->xtra_path/default.param",$message);
		return $message;
	}

	 #################################
	# Load the parameters from a file
	function load_parameters_from_file($file,&$message) {
		$p = unserialize(file_to_string($file));
		if(is_array($p)) {
			if($p["__codename__"] == $this->codename) {
				if(version_no_compare($p["__version__"],$this->version) > 0) {
					$message = "WARNING: The imported parameters are from a more recent version of '$this->name'. Some of your settings may be corrupt.";
				} elseif(version_no_compare($p["__version__"],$this->version) < 0) {
					$message = "WARNING: The imported parameters are from an older version of '$this->name'. Some of your settings may be corrupt.";
				} else {
					$message = "Parameters imported successfully.";
				}
				$this->parameters = &$p;
				return 1;
			}
			$message = "Unable to import parameters - wrong attribute type: $p[__codename_]";
			return;
		}
		$message = "Unable to import parameters. The file is corrupt.";
		return;
	}

	 #################################
	# Save the parameters to a file
	function save_parameters_to_file($file) {
		$this->parameters['__codename__'] = $this->codename;
		$this->parameters['__version__']  = $this->version;
		return string_to_file(serialize($this->parameters),$file);
	}

	 #################################################
	# Returns the value of soemthign int he parameters
	function &get_value($code) {
		return $this->parameters[$code];
	}

	function &set_value($code,$value) {
		return $this->parameters[$code] = $value;
	}

	 ###################################
	# Returns the name of the attribute
	function &get_name() {
		return $this->get_value('name');
	}

	 ###################################
	# Returns the default value for this
	# attribute
	function &get_default() {
		return $this->get_value('default'); 
	}
	
	 #######################################################
	# Called when an attibute associated with a new record
	function spawn_varieties($recordid) {
		return; # Some types may like to override this.
	}

	 ######################################################################
	# Returns the number of possible varieties generated by this attribute
	function get_variety_count(&$value) {
		return 0;
	}

	function &get_variety_names(&$value) {
		$tmp = array(0 => '');
		return $tmp;
	}


	 ##################################################
	# Takes an original values, and variation and a 
	# variation type, and returns the varied result
	function vary_value($type, &$new_value, &$original) {
		# This is just a parent class, lets return the new_value
		# as if the type can only be "replace".
		return $new_value;
	}

	 ##################################################
	# Returns true of false depending on whether the
	# original value needs to be avaiable to perform
	# a variatino of a particular type. Pretty much
	# all types will need it except "replace".
	function variation_type_requires_original($type) {
		return ($type != 'replace');
	}

	 #############################################################
	# Makes a string out of a vlue, for storing in the database
	function serialize(&$v) {
		return "$v";
	}
	function unserialize(&$v) {
		# To be filled in by children.
	}

	 #############################################################
	# Paints the attribute in a nice human-readable/viewable way
	function paint(&$value,$format) {
		echo $this->formatted_value($value,$format);
	}

	/**
	 ** Returns a list of all the defined formats
	 ** @returns array
	 **/
	function &get_format_list() {
		return $this->get_setting('value_formats');
	}

	 ################################################
	# Returns the value formatted in a nice way for printing
	function formatted_value(&$value,$format) {
		return $value;
	}

	 ############################################
	# Given a value for this attribute, returns
	# a formatted version of it
	function compare_values(&$value1,&$value2) {
		return strcmp($value1,$value2);
	}

	#############################################
	# Prints the interface for filling in a value
	function print_concise_field($prefix,&$value,$format) {
		echo text_box($prefix.'value',$value,30);
	}
	
	 #############################################
	# Prints the interface for filling in a value
	function print_field($prefix,&$value,$format) {
		return $this->print_concise_field($prefix,$value,$format);
	}

	/**
	* Processes changes to an attribute
	*
	* @param  string  $prefix                 The attribute changing prefix
	* @param  &string $value_destination      The current value
	* @param  string  $format                 Don't remember
	* @param  &string &$rules_broken_messages Var to hold broken rull messages
	* @access public
	* @return string
	*/
	function process_field($prefix, &$value_destination, $format, &$rules_broken_messages) {
		$input = $_REQUEST[$prefix.'value'];
		$input = stripslashes_array($input);
		$this->validate_value($input, $rules_broken_messages);
		if($value_destination === $input) return;
		if ($rules_broken_messages) {
			return 'Rule broken in attribute '.$this->attribute->name.'. '.$rules_broken_messages;
		} else {
			$value_destination = $input;
			return $this->attribute->name.' updated.';
		}
	}

	 ###########################
	# Prints out a search field
	function print_search_field($prefix,&$default,$format,$not_null) {
		echo text_box($prefix.'q',$default,30);
	}

	 ###################################################
	# Returns a query object which can be given back to
	# print_search_field() or get_search_sql_clause()
	function &process_search_field($prefix,$format,$not_null) {
		$type = $_REQUEST[$prefix.'q'];
		$q = trim($type);
		return $q;
	}

	 ##############################################
	# Returns an SQL WHERE subclause for searching
	function get_search_sql_clause($query,$format,$value_string='v.value') {
		return "$value_string ='".addslashes($query)."'";
	}

	 ########################################################
	# Returns an SQL subclause for sorting in an ORDER clause
	function get_search_sql_sort_clause($format,$value_string='v.value') {
		return $value_string;
	}

	 ###############################################################
	# Returns an SQL expression that evalutates to a relevancy score
	function get_search_sql_score($query,$format,$value_string='v.value') {
		return;
	}

	 #################################################
	# Returns one of the hard-coded settings
	function &get_setting($setting) {
		return $this->settings[$setting];
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
		$backend = &$this->get_backend();
		$pset = &$this->get_pset();
		$pset->process($backend);
	}

	 ##########################
	# Prints the default field
	function print_default_field() {
		$default = &$this->get_value("default");
		$this->print_field("default_",$default);
	}

	 ##########################
	# Prints the default field
	function process_default_field() {
		if($m = $this->process_field("default_",$this->parameters["default"])) {
			return "Default $m";
		}
		return;
	}

	/**
	* Returns the default edit formats ie none
	*
	* @returns array
	*/
	function get_edit_formats() {
		return array('' => 'None');
	}

	/**
	* Given a rules settings it will return the interface for that rule type
	*
	* @param  string $rule_code      The index of the rule in the big rule array
	* @param  array  $rule_settings  The settings for the rule in question
	* @access public
	* @return string
	*/
	function get_rule_interface($rule_code, $rule_settings) {
		if (!empty($rule_settings)) {
			$rule_type_name = $rule_settings['type'];
			eval("\$interface =  \$this->get_rule_{$rule_type_name}_interface('rule_interface'.\$this->id.'_'.\$rule_code, \$rule_settings);");
			return $interface;
		}
	}

	/**
	* Returns the interface for the must answer rule type
	*
	* @param  string $interface_name The basic name of the interface
	* @param  array  $rule_settings  The settings for the rule in question
	* @access public
	* @return string
	*/
	function get_rule_must_answer_interface($interface_name, $rule_settings) {
		$broken_message = text_box($interface_name.'_broken_message', $rule_settings['broken_message'], 40);
		$interface  = '<table bgcolor="#AAAAAA">';
		$interface .= '	<tr bgcolor="#DDDDDD">';
		$interface .= '		<td><i>Rule Broken Message</i></td><td>'.$broken_message.'</td>';
		$interface .= '	</tr>';
		$interface .= '</table>';
		return $interface;
	}

	/**
	* Returns the basic new settings for the must answer rule type
	*
	* @param  string &$value            The value we need to check against the rules
	* @param  string &$rule_broken_msgs The broken rule messages
	* @access public
	* @return void
	*/
	function validate_value(&$value, &$rule_broken_msgs, $validation_type='none') {
		$this->validate_value_rules($value, $rule_broken_msgs);
	}

	/**
	* Given a rules settings it will return the interface for that rule type
	*
	* @param  string $rule_code     The index of the rule in the big rule array
	* @param  array  $rule_settings The settings for the rule in question
	* @access public
	* @return string
	*/
	function process_rule_interface($rule_code, &$rule_settings) {
		if (!empty($rule_settings)) {
			$rule_type_name = $rule_settings['type'];
			eval("\$msgs = \$this->process_rule_{$rule_type_name}_interface('rule_interface'.\$this->id.'_'.\$rule_code, \$rule_settings);");
			return $msgs;
		}
	}

	/**
	* Processes any changes to the rule must answer interface
	*
	* @param  string $interface_name The basic name of the interface
	* @param  array  &$rule_settings  The settings for the rule in question
	* @access public
	* @return string
	*/
	function process_rule_must_answer_interface($interface_name, &$rule_settings) {
		$msgs = '';
		if ($_POST[$interface_name.'_broken_message'] != $rule_settings['broken_message'] && isset($_POST[$interface_name.'_broken_message'])) {
			$rule_settings['broken_message'] = $_POST[$interface_name.'_broken_message'];
			$msgs .= 'Broken rule message has been updated<br />';
		}
		return $msgs;
	}

	/**
	* Returns the settings for the new rule
	*
	* @param  string $new_rule_code The rule type in question
	* @access public
	* @return array
	*/
	function get_new_rule_settings($new_rule_code) {
		eval("\$settings = \$this->get_new_rule_{$new_rule_code}_settings();");
		return $settings;
	}

	/**
	* Returns the basic new settings for the must answer rule type
	*
	* @access public
	* @return array
	*/
	function get_new_rule_must_answer_settings() {
		return array('type' => 'must_answer', 'name' => 'Must Answer', 'broken_message' => 'This field must be answered');
	}

	/**
	* Check for each set of rules against the value
	*
	* @param  string $value            The value we need to check against the rules
	* @param  string &$rule_broken_msgs The broken rule messages
	* @access public
	* @return void
	*/
	function validate_value_rules($value, &$rule_broken_msgs) {
		#check for rules so we need the rules array
		$rules = $this->parameters['rules'];
		if (empty($rules)) return;
		#go thru each rule and check each one and compile an error message
		foreach($rules as $rule_code => $rule_settings) {
			$rule_type_name = $rule_settings['type'];
			eval("\$error_msg = \$this->validate_{$rule_type_name}_rule(\$value, \$rule_settings);");

			# if we got an error msg add it to our messages
			if ($error_msg) {
				$rule_broken_msgs = $this->add_broken_rule_message($rule_broken_msgs, $error_msg);
			}
		}
	}

	/**
	* Adds a broken rule message to the broken rule message string
	*
	* @param  string $rule_broken_msgs The current string on broken rule messages
	* @param  string $new_message      The new message to add
	* @access public
	* @return string
	*/
	function add_broken_rule_message($rule_broken_msgs, $new_message) {
		$rule_broken_format = $this->parameters['rule_broken_format'];
		switch ($rule_broken_format) {
			case 'br':
			$rule_broken_msgs .= $new_message.'<br />';
			break;
		}
		return $rule_broken_msgs;
	}

	/**
	* Validating must answer value
	*
	* @param  string $value         The value we need to check against the rule
	* @param  array  $rule_settings The settings of the rule we are checking against the value
	* @access public
	* @return string
	*/
	function validate_must_answer_rule($value, $rule_settings) {
		return (($value == '')?$rule_settings['broken_message']:'');
	}

	/**
	* Returns the interface for the must hold user rule type
	*
	* @param  string $interface_name The basic name of the interface
	* @param  array  $rule_settings  The settings for the rule in question
	* @access public
	* @return string
	*/
	function get_rule_must_hold_user_interface($interface_name, $rule_settings) {
		$broken_message = text_box($interface_name.'_broken_message', $rule_settings['broken_message'], 40);
		$interface  = '<table bgcolor="#AAAAAA">';
		$interface .= '	<tr bgcolor="#DDDDDD">';
		$interface .= '		<td><i>Rule Broken Message</i></td><td>'.$broken_message.'</td>';
		$interface .= '	</tr>';
		$interface .= '</table>';
		return $interface;
	}

	/**
	* Returns the basic new settings for the must hold user rule type
	*
	* @access public
	* @return array
	*/
	function get_new_rule_must_hold_user_settings() {
		return array('type' => 'must_hold_user', 'name' => 'Must Hold User', 'broken_message' => 'User must be set/created');
	}

	/**
	* Processes any changes to the rule must answer interface
	*
	* @param  string $interface_name The basic name of the interface
	* @param  array  &$rule_settings  The settings for the rule in question
	* @access public
	* @return string
	*/
	function process_rule_must_hold_user_interface($interface_name, &$rule_settings) {
		$msgs = '';
		if ($_POST[$interface_name.'_broken_message'] != $rule_settings['broken_message'] && isset($_POST[$interface_name.'_broken_message'])) {
			$rule_settings['broken_message'] = $_POST[$interface_name.'_broken_message'];
			$msgs .= 'Broken rule message has been updated<br />';
		}
		return $msgs;
	}

	/**
	* Validating must hold user value
	*
	* @param  string $value         The value we need to check against the rule
	* @param  array  $rule_settings The settings of the rule we are checking against the value
	* @access public
	* @return string
	*/
	function validate_must_hold_user_rule($value, $rule_settings) {
		return ((!$value || $value == 0)?$rule_settings['broken_message']:'');
	}

	/**
	* Just here incase we try to call on an attribute that can't be searched
	*
	* @access public
	* @return void
	*/
	function set_search_request() {
	}

	/**
	* Prints the active xtras backend settings
	*
	* @access public
	* @return void
	*/
	function print_attribute_selected_xtra() {
		$active_xtraid = $this->get_active_xtraid();
		$attribute_xtra = &$this->get_attribute_type_xtra($active_xtraid);
		if ($attribute_xtra->id) {
			$attribute_xtra->print_backend();
		} else {
			echo 'There are no attribute Xtras.<br />';
		}
	}

	/**
	* Gets the active xtra for this attribute and this session
	*
	* @access public
	* @return int
	*/
	function get_active_xtraid() {
		$session = &get_mysource_session();
		$attributeid = $this->id;
		$info = &$session->get_var('notitia_attribute_type_xtra_active_xtraid');
		$active_xtraid = $info[$attributeid];
		$active_xtra = &$this->get_attribute_type_xtra($active_xtraid);
		if ($active_xtra->id) {
			return $active_xtraid;
		} else {
			$attribute_xtraids = $this->get_attribute_xtras();
			if (empty($attribute_xtraids)) {
				return 0;
			} else {
				return reset($attribute_xtraids);
			}
		}
	}

	/**
	* Sets the active xtra for this attribute and this session
	*
	* @param int $new_xtraid The xtraid to make active
	* @access public
	* @return string
	*/
	function set_active_xtraid($new_xtraid) {
		$active_xtraid = $this->get_active_xtraid();
		if ($active_xtraid != $new_xtraid) {
			# To stop setting going from one Xtra to the other while changing between the two
			$_POST['notitia_attribute_type_xtra_active_xtraid_changed'] = 1;
			$session = &get_mysource_session();
			$info = &$session->get_var('notitia_attribute_type_xtra_active_xtraid');
			if (!is_array($info)) {
				$info = array();
			}
			$attributeid = $this->id;
			$info[$attributeid] = $new_xtraid;
			$session->set_var('notitia_attribute_type_xtra_active_xtraid',$info);
			return 'Active Xtra ID set to '.$new_xtraid;
		}
	}

	/**
	* Gets all attribute xtras that this attribute type has and returns them in an array in order
	*
	* @access public
	* @return array
	*/
	function get_attribute_xtras($order_no='none',$greater_than=true) {
		$db = &$this->get_db();
		if ($order_no == 'none') {
			return $db->single_column("SELECT xtraid FROM xtra_web_extension_notitia_attribute_xtra WHERE attributeid='$this->id' ORDER BY order_no");
		} else {
			if (is_numeric($order_no) && $order_no > -1) {
				$order_no = (int) $order_no;
				$comparison_operator = ($greater_than)?'>':'<';
				return $db->single_column("SELECT xtraid FROM xtra_web_extension_notitia_attribute_xtra WHERE attributeid='$this->id' AND order_no $comparison_operator $order_no ORDER BY order_no");
			}
		}
		return array();
	}

	/**
	* Gets all of this attribute types xtras as an array of xtraid and xtra name
	*
	* @access public
	* @return array
	*/
	function get_attribute_xtras_names($order_no='none',$greater_than=true) {
		$attribute_type_xtras = $this->get_attribute_xtras($order_no, $greater_than);
		$returning_xtras_names = array();
		foreach($attribute_type_xtras as $xtraid) {
			$xtra = &$this->get_attribute_type_xtra($xtraid);
			$returning_xtras_names[$xtraid] = $xtra->get_name();
		}
		return $returning_xtras_names;
	}

	/**
	* Returns an array of all xtra types available
	*
	* @access public
	* @return array
	*/
	function get_attribute_xtra_types() {
		$xtras = &$this->get_attribute_type_xtras();
		if ($xtras) {
			return $xtras->list_type('types');
		} else {
			return array();
		}
	}

	/**
	* Possibly creates a new xtra for this attribute type
	*
	* @access public
	* @return string
	*/
	function process_new_attribute_xtra() {
		$msgs = '';
		$name = &$this->get_value('new_attribute_xtra_name');
		$type = &$this->get_value('new_attribute_xtras_type');
		if ($name) {
			$n = &$this->get_notitia_system();
			$attribute_xtraid = $n->create_attribute_type_xtra($type, $name, $this->id);
			if ($attribute_xtraid) {
				$this->set_active_xtraid($attribute_xtraid);
				$msgs = 'New attribute xtra created<br />';
			}
			$name = '';
		}
		return $msgs;
	}

	/**
	* Gets all attribute xtra types with their descriptions of what they do as an array with xtra type as
	* it's key and description as it's value
	*
	* @access public
	* @return array
	*/
	function get_attribute_xtra_types_with_descriptions() {
		$types = $this->get_attribute_xtra_types();
		$xtras = &$this->get_attribute_type_xtras();
		foreach(array_keys($types) as $codename) {
			$types[$codename] .= '<span class="smallprint"><br /><i>'.$xtras->description('types',$codename).'</i></span>';
		}
		return $types;
	}

	/**
	* Processes the selected xtras settings
	*
	* @access public
	* @return void
	*/
	function process_attribute_selected_xtra() {
		$active_xtraid = $this->get_active_xtraid();
		$attribute_type_xtra = &$this->get_attribute_type_xtra($active_xtraid);
		if ($attribute_type_xtra->id) {
			$attribute_type_xtra->process_type_parameters();
		}
	}

	/**
	* A function that needs to be overwritten by the attribute type because only the attribute type knows where it's going to store it's xtras.
	*
	* @access public
	* @return array
	*/
	function &get_attribute_type_xtras() {
		return;
	}

	/**
	* Returns the table name of where this attribute type stores it's value
	*
	* @access public
	* @return string
	*/
	function get_value_table_name() {
		$table_settings = $this->get_setting('table_settings');
		$sub_table_name = $table_settings['sub_table_name'];
		$n = &$this->get_notitia_system();
		if ($sub_table_name == '') {
			$role = &$this->attribute->get_role_handler('value_table');
			$value_table_code = $role->get_value_table_code();
			return $n->table_name.'_attribute_value'.$value_table_code;
		} else {
			return $n->table_name.'_attribute_value'.$sub_table_name;
		}
	}

	/**
	* Returns the table name of where this attribute type stores it's default value
	*
	* @access public
	* @return string
	*/
	function get_default_value_table_name() {
		$table_settings = $this->get_setting('table_settings');
		$sub_table_name = $table_settings['sub_table_name'];
		$n = &$this->get_notitia_system();
		return $n->table_name.'_attribute_default'.$sub_table_name;
	}

	/**
	* Processes any pre save attribute type specifics
	*
	* @access public
	* @param int $recordid - the recordid obviously
	* @param mixed $value - the value
	* @return void
	*/
	function pre_save_record_value($recordid, &$value) {
		# to be filled in by attribute types
		return;
	}
}
?>
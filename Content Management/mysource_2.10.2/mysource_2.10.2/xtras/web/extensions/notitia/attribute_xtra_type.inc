<?
/**
* A parent class for the attribute xtra
*/
class Web_Extension_Notitia_Attribute_Attribute_Xtra_Type extends Web_Extension_Notitia_Object {

	var $attribute_xtra;       # A reference to the xtra what ownz us
	var $id = '';
	var $name = '';
	var $type = '';            # The type of xtra
	var $parameters = array(); # An array of parameters, defined by the type class
	var $order_no = '';
	var $attribute_type;       # A reference to the attribute type that ownz us
	var $temp = array();

	/**
	* Constructor
	*
	* @param  &object A favourite
	* @access private
	* @return void
	*/
	function Web_Extension_Notitia_Attribute_Attribute_Xtra(&$attribute_xtra) {
		Web_Extension_Notitia_Object::Web_Extension_Notitia_Object();
		$this->attribute_xtra  = &$attribute_xtra;
		$this->parameters = &$this->attribute_xtra->parameters;
		$this->id = $this->attribute_xtra->id;
		$this->name = $this->attribute_xtra->name;
		$this->type = $this->attribute_xtra->type;
		$this->attributeid = $this->attribute_xtra->attributeid;
		$this->order_no = $this->attribute_xtra->order_no;
		$this->attribute_type = &$this->attribute_xtra->attribute_type;
	}

	/**
	* Stuff this object should know upon openning
	*
	* @access public
	* @return void
	*/
	function __wakeup() {
		parent::__wakeup();
		$n = &$this->get_notitia_system();
		$this->table_name = $n->table_name.'_attribute_xtra';
	}

	/**
	* Returns a reference to the parameter set of this xtra type
	*
	* @access public
	* @return &object
	*/
	function &get_pset() {
		if(!isset($this->temp['parameter_set'])) {
			$this->temp['parameter_set'] = array();
		}
		$pset = &$this->temp['parameter_set'];
		if(get_class($pset) !== 'parameter_set') {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/parameter_set.inc");
			$type = $this->get_type();
			$attribute = &$this->get_attribute($this->attributeid);
			$xtras_dir = $attribute->get_type_setting('xtras_dir');
			$pset = new Parameter_Set(get_class($this), dirname(__FILE__).'/xtras/attribute/types/'.$attribute->type.'/'.$xtras_dir.'/types/'.$type.'/'.$type.'.pset', $this->parameters, $this);
			$this->temp['parameter_set'] = &$pset;
		}
		return $pset;
	}

	/**
	* Prints the backend of this xtra type
	*
	* @access public
	* @return void
	*/
	function print_backend() {
		$backend = &$this->web_system->get_backend();
		$pset = &$this->get_pset();
		$pset->print_backend($backend, true);
	}

	/**
	* Processes the backend of this xtra type
	*
	* @access public
	* @return void
	*/
	function process_backend() {
		$backend = &$this->web_system->get_backend();
		$pset = &$this->get_pset();
		if (!$_POST['notitia_attribute_type_xtra_active_xtraid_changed']) {
			if ($pset->process($backend)) {
				$this->save_parameters();
			}
		}
	}

	/**
	* Gets the value in the parameters
	*
	* @param string $code The name
	* @access public
	* @return mixed
	*/
	function &get_value($code) {
		return $this->parameters[$code];
	}

	/**
	* Gets the name of this xtra
	*
	* @access public
	* @return string
	*/
	function get_name() {
		return $this->get_value('name');
	}

	
	/**
	* Gets the id of this xtra
	*
	* @access public
	* @return int
	*/
	function get_id() {
		return $this->id;
	}

	/**
	* Gets the type of this xtra
	*
	* @access public
	* @return string
	*/
	function get_type() {
		return $this->type;
	}

	/**
	* Saves this attribute xtra types parameters by calling the owning class to save the parameters
	*
	* @access public
	* @return void
	*/
	function save_parameters() {
		$this->attribute_xtra->save_parameters();
	}

	/**
	* Evaluate this attribute xtra
	*
	* @param array $option The options chosen. Will have something if an option other than no is chosen
	* @param array $options Extra options we might need to evaluate this dynamically
	* @access public
	* @return string
	*/
	function evaluate($input=array(), $options=array()) {
		return;
	}

	/**
	* Gets this xtras owning attributeid
	*
	* @access public
	* @return int
	*/
	function get_attributeid() {
		return $this->attributeid;
	}

	/**
	* Gets this xtras order no
	*
	* @access public
	* @return int
	*/
	function get_order_no() {
		return $this->order_no;
	}

	/**
	* Gets this xtra owning attribute
	*
	* @access public
	* @return &object
	*/
	function &get_owning_attribute() {
		$attributeid = $this->get_attributeid();
		return $this->get_attribute($attributeid);
	}

	/**
	* Gets a setting in an attribute xtra type
	*
	* @param string $index_location Where in the settings array you want to look
	* @access public
	* @return mixed
	*/
	function get_setting($index_location) {
		return $this->settings[$index_location];
	}


	/**
	* Returns the remapped parameters for duping
	*
	* @access public
	* @param array $dupe_map - The dupe map array
	* @return array - The parameters
	*/
	function type_defined_remapper($dupe_map) {
		# remapping needs to be defined by the individual type
		return $this->parameters;
	}
}
?>
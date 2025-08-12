<?
include_once(dirname(__FILE__).'/attribute_xtra_type.inc');

/**
* The attribute xtra class
*/
class Web_Extension_Notitia_Attribute_Type_Attribute_Xtra extends Web_Extension_Notitia_Object {
	
	var $id = '';
	var $name = '';
	var $attributeid = '';
	var $attribute_type;       # A reference to the attribute type that ownz us
	var $type = '';            # The type of xtra
	var $order_no = '';
	var $parameters = array(); # An array of parameters, defined by the type class
	var $temp = array();

	/**
	* Constructor
	*
	* @access private
	* @return int
	*/
	function Web_Extension_Notitia_Attribute_Type_Attribute_Xtra($xtraid) {
		Web_Extension_Notitia_Object::Web_Extension_Notitia_Object();
		return $this->load($xtraid);
	}

	/**
	* Loads this xtra
	*
	* @param int $xtra The id of the xtra you wish to load
	* @access public
	* @return object
	*/
	function load($xtraid=0) {
		$xtraid = abs((int) $xtraid);

		if ($xtraid) {
			 #################
			# Check the cache
			if (!$this->load_from_cache($xtraid)) {

				# Couldn't load from cache so here we go
				$db = &$this->get_db();

				list($this->id, $this->attributeid, $this->type,$tmp, $this->order_no) = $db->single_row("SELECT xtraid, attributeid, type, parameters, order_no FROM $this->table_name WHERE xtraid='$xtraid'");
				if(!$this->id) return;

				$this->parameters = unserialize($tmp);
				$this->name = $this->parameters['name'];

				 ###################
				# Save to the cache 
				$this->save_to_cache($xtraid);
			}
		}

		$attribute = &$this->get_attribute($this->attributeid);
		if ($attribute->id) {
			$this->attribute_type = &$attribute->get_type_handler();
		}

		return $this->id;
	}

	/**
	* Creates a new xtra and returns the id
	*
	* @param string $type The type of xtra to create
	* @param string $name The requested name
	* @param int $attributeid The id of the attribute this attribute type xtra will be associated with
	* @access public
	* @return int
	*/
	function create($type, $name, $attributeid) {
		if(!$type || !$name || !$attributeid) return false;
		$type_parameters = array();
		$type_parameters = serialize($type_parameters);
		$this->parameters = array('name'=>$name, 'parameters'=>$type_parameters);
		$this->type = $type;
		$this->attributeid = $attributeid;
		$db = &$this->get_db();

		$this->order_no = $db->single_element("SELECT max(order_no) + 1 FROM $this->table_name WHERE attributeid='$this->attributeid'");
		$xtraid = $db->insert("INSERT INTO $this->table_name (type,attributeid,parameters,order_no) VALUES('$this->type','$this->attributeid','".addslashes(serialize($this->parameters))."','$this->order_no')");

		if($xtraid) {
			return $this->load($xtraid);
		} else {
			return false;
		}

		return $xtraid;
	}

	/**
	* Deletes the xtra, re-numbers subsequent xtras in the order, and clears the cache as needed
	*
	* @access public
	* @return string
	*/
	function delete() {
		$db = &$this->get_db();

		# clear the cache of objects that will be affected (including this one)
		$sql = "SELECT xtraid FROM {$this->table_name} WHERE order_no >= {$this->order_no}";
		$affected_xtras = $db->single_column($sql);
		if (!empty($affected_xtras)) {
			foreach($affected_xtras as $id) {
				$this->clear_cache($id);
			}
		}

		# decrement the order numbers for all attributes after this one
		$res = $db->update("UPDATE {$this->table_name} SET order_no = order_no - 1 WHERE order_no > {$this->order_no}");

		# finally, actually delete this xtra
		$res = $db->delete("DELETE FROM {$this->table_name} WHERE xtraid='$this->id'");
		return $res;
	}

	/**
	* Stuff this object should know upon load
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
	* Prints the backend of the this xtra
	*
	* @access public
	* @return void
	*/
	function print_backend() {
		$backend = &$this->web_system->get_backend();
		$pset = &$this->get_pset();
		$pset->print_backend($backend, true);
		$this->print_xtra_type_parameters();
	}

	/**
	* Prints the specific settings of the this xtra type
	*
	* @access public
	* @return void
	*/
	function print_xtra_type_parameters() {
		$type_handler = &$this->get_type_handler();
		$type_handler->print_backend();
	}

	/**
	* Returns a reference to an object handling the type-specific operations of this xtra
	*
	* @param string $type The type of xtra
	* @access public
	* @return object
	*/
	function &get_type_handler($type) {
		if(!$type) $type = $this->type;
		if (!$type) return;
		$attribute = &$this->get_attribute($this->attributeid);
		$xtras_dir = $attribute->get_type_setting('xtras_dir');

		$class = get_class($this).'_'.$xtras_dir.'_type_'.$type;
		$handler = &$this->temp['type_handlers'][$type];
		if(get_class($handler) != $class) {
			include_once(dirname(__FILE__).'/xtras/attribute/types/'.$attribute->type.'/'.$xtras_dir.'/types/'.$type.'/'.$type.'.inc');
			$handler = new $class($this);
			$this->temp['type_handlers'][$type] = &$handler;
		}
		return $handler;
	}

	/**
	* Returns a reference to the parameter set of this xtra
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
			$pset = new Parameter_Set(get_class($this), dirname(__FILE__).'/attribute_xtra.pset', $this->parameters, $this);
			$this->temp['parameter_set'] = &$pset;
		}
		return $pset;
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
	* Saves the parameters of this xtra
	*
	* @access public
	* @return string
	*/
	function save_parameters() {
		$db = &$this->get_db();
		$this->clear_cache($this->id);
		return $db->update("UPDATE $this->table_name SET parameters='".addslashes(serialize($this->parameters))."' WHERE xtraid='$this->id'");
	}

	/**
	* Processes the xtra types parameters
	*
	* @access public
	* @return void
	*/
	function process_type_parameters() {
		$backend = &$this->web_system->get_backend();
		$pset = &$this->get_pset();
		if (!$_POST['notitia_attribute_type_xtra_active_xtraid_changed']) {
			if ($pset->process($backend)) {
				$this->save_parameters();
			}
		}
		$type_handler = &$this->get_type_handler();
		$type_handler->process_backend();
	}

	/**
	* Gets the order number of this xtra
	*
	* @access public
	* @return int
	*/
	function get_order_no() {
		return $this->order_no;
	}

	/**
	* Sets the order no for this xtra
	*
	* @param int  The order no you wish to change this xtra to
	* @param boolean $insert_rotate - flag to say wether to insert the xtra in order or rotate
	* in the params post form
	* @param boolean $found_in_post_vars Whether this setting's change request was found 
	* @access public
	* @return string
	*/
	function set_order_no($order_no, $found_in_post_vars=true, $insert_rotate=true) {
		# This needs improving
		if ($found_in_post_vars && $order_no != $this->order_no && $order_no > -1) {
			$db = &$this->get_db();
			# true means to insert
			if ($insert_rotate) {
				# insert the attribute and reorder the attributes above this into the order
				$attribute = &$this->get_attribute($this->attributeid);
				if ($attribute->id) {
					$type_handler = &$attribute->get_type_handler();
					$xtras = $type_handler->get_attribute_xtras($order_no-1);
					$curr_order = $order_no;
					foreach ($xtras as $xtraid) {
						if ($xtraid == $this->id) continue;
						$next_order = $curr_order+1;
						$db->update("UPDATE $this->table_name SET order_no='".$next_order."' WHERE xtraid='$xtraid' and order_no <= '$curr_order'");
						$this->clear_cache($xtraid);
						$curr_order++;
					}
				}
			} else {
				$res = $db->single_column("SELECT xtraid from $this->table_name WHERE order_no='$order_no' and attributeid='$this->attributeid'");
				#should only be one result
				if (count($res) == 1) {
					$xtraid = $res[0];
					$attribute = &$this->get_attribute($this->attributeid);
					if ($attribute->id) {
						$type_handler = &$attribute->get_type_handler();
						if ($type_handler->get_active_xtraid() == $xtraid && $_REQUEST['order_no'] == $order_no) {
							$_REQUEST['order_no'] = $this->order_no;
						}
					}
					$db->update("UPDATE $this->table_name SET order_no='".$this->order_no."' WHERE xtraid='$xtraid'");
					$this->clear_cache($xtraid);
				}
			}

			$this->order_no = $order_no;
			$this->clear_cache($this->id);
			$db->update("UPDATE $this->table_name SET order_no='".$order_no."' WHERE xtraid='$this->id'");
		}
		return 'one';
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
	* Sets a request to delete this xtra
	*
	* @param array $option The options chosen. Will have something if an option other than no is chosen
	* @access public
	* @return string
	*/
	function set_delete($option, $found_in_post_vars=true) {
		if ($found_in_post_vars && $option) {
			$this->delete();
			# We should redirect to here again cause this started printing the wrong xtra
			$href = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
			?>
			<script language="javascript">
				document.location='<?echo $href?>';
			</script>
			<?
		}

	}

	/**
	* Evaluates this attribute type xtra type (This function should be overwritten by the xtra type)
	*
	* @param array $input Some input
	* @param array $options Some options
	* @access public
	* @return mixed
	*/
	function evaluate($input=array(), $options=array()) {
		$type_handler = &$this->get_type_handler();
		return $type_handler->evaluate($input, $options);
	}

	/**
	* Duplicates the attribute xtra
	*
	* @param int $new_xtraid - the new xtraid to duplicate itself too
	* @param array $dupe_map - The dupe map array
	* @param bool $remap - flag to say weather to remap the parameters
	* @access public
	* @return void
	*/
	function dupe($new_xtraid, $dupe_map, $remap=true) {
		# get the type handler to fix do any remapping
		$xtra_type = $this->get_type_handler();
		if ($remap) {
			$parameters = $xtra_type->type_defined_remapper(&$dupe_map);
		} else {
			$parameters = $xtra_type->parameters;
		}

		#save the parameters for the new attribute
		$new_attribute_xtra = &$this->get_attribute_type_xtra($new_xtraid);
		if ($new_attribute_xtra->id) {
			$new_attribute_xtra->parameters = $parameters;
			$new_attribute_xtra->save_parameters();
		}

		# set the duplicate copy of the attribute to be current
		$_REQUEST['active_xtraid'] = $new_xtraid;
		$_REQUEST["notitia_attribute_type_xtra_active_xtraid_changed"] = 1;

	}
}
?>

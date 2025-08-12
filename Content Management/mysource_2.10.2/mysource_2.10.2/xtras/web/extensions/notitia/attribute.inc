<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/attribute.inc,v $
## $Revision: 1.45 $
## $Author: tbarrett $
## $Date: 2004/03/25 22:00:52 $
#######################################################################
include_once(dirname(__FILE__).'/attribute_type.inc');
include_once(dirname(__FILE__).'/attribute_role.inc');
#---------------------------------------------------------------------#

 ##############################################################
# An attribute is a element of data stored about a record
# attributes apply to all the records in the category (and subcategories)
# it is attached to.
class Web_Extension_Notitia_Attribute extends Web_Extension_Notitia_Object {
	
	# Var
	var $id;         # The attributeid
	var $categoryid; # The category, if any, this attribute is associated with
	var $recordid;  # The record, if any, this attribute is associated with
	var $type;       # The type of attribute
	var $name;       # The name of the attribiute.. but be unique within a category/record context
	var $parameters; # An array of parameters, defined by the type class
	var $roles;      # An array of roles and their parameters array(role => parameters)

	var $defaults; # The default values for this attribute in any categories where its defined.

	var $order_no; # The order compared to other attributes in this context

	var $temp; # An array of runtime data that doesnt get saved

	 #############
	# Constructor
	function Web_Extension_Notitia_Attribute($attributeid) {
		Web_Extension_Notitia_Object::Web_Extension_Notitia_Object();
		if((int) $attributeid > 0) {
			return $this->load((int) $attributeid);
		} else {
			return $this->id = 0;
		}
	}


	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = Web_Extension_Notitia_Object::__sleep();
		array_remove_element("temp",$result);
		return $result;
	}

	 ##############################################
	# Wake up with this handy information EVERY day !
	function __wakeup() {
		Web_Extension_Notitia_Object::__wakeup();
		$this->temp = array();
		$e = &$this->get_notitia_system();
		$this->table_name = "$e->table_name"."_attribute";
	}

	 #########################
	# Creates a new attribute
	function create($type,$name,$categoryid,$recordid) {
		$e     = &$this->get_notitia_system();
		$xtras = &$e->get_xtras();
		if(!$name) return false;
		if($xtras->codename("attribute/types",$type) == $type) {
			$db = &$this->get_db();
			$this->type       = $type;
			$this->categoryid = abs((int) $categoryid);
			$this->recordid  = abs((int) $recordid);
			# Make sure this is a unique name in this context
			while($db->single_element("SELECT attributeid FROM $this->table_name WHERE name='".addslashes($name)."' AND categoryid='$this->categoryid' AND recordid='$this->recordid'")) {
				$name = increment_name($name," ");
			}
			$this->parameters = array();
			$order_no = $db->single_element("SELECT max(order_no) + 1 FROM $this->table_name WHERE categoryid='$this->categoryid' AND recordid='$this->recordid'");
			$type_h   = &$this->get_type_handler($type);
			$type_h->load_default_parameters(); # Get some example data in there.
			# Save to the database
			$attributeid = $db->insert("INSERT INTO $this->table_name (type,name,categoryid,recordid,parameters,order_no) VALUES('$this->type','".addslashes($name)."','$this->categoryid','$this->recordid','".addslashes(serialize($this->parameters))."','$order_no')");
			# Make sure the record knows
			if($recordid) {
				$record = &$this->get_record($recordid);
				$record->clear_cache();
				$record->load();
			} else {
				# Heaps of records need to know about this.
				$this->clear_associated_record_caches();
			}
			if($attributeid) {
				return $this->load($attributeid);
			} else {
				return false;
			}
		} else {
			$this->_set_error('Attempt to create an attribute with an unregistered type: '.$xtras->codename('attribute/types',$type)." / $type",__FILE__,__LINE__);
		}
	}

	 #####################################################
	# Returns a reference to an object handling the type-
	# specific operations of this attribute
	function &get_type_handler($type) {
		if(!$type) $type = $this->type;
		if (!$type) return;
		$class = get_class($this).'_type_'.$type;
		$handler = &$this->temp['type_handlers'][$type];
		$e       = &$this->get_notitia_system();
		if(get_class($handler) != $class) {
			include_once("$e->xtra_path/xtras/attribute/types/$type/$type.inc");
			$handler = new $class($this);
		}
		return $handler;
	}

	 #####################################################
	# Returns a reference to an object representing a
	# certain role of the attribute
	function &get_role_handler($role) {
		$class = get_class($this).'_role_'.$role;
		$handler = &$this->temp['role_handlers'][$role];
		$e       = &$this->get_notitia_system();
		if(get_class($handler) != $class) {
			include_once("$e->xtra_path/xtras/attribute/roles/$role/$role.inc");
			$handler = new $class($this);
		}
		return $handler;
	}
	
	 ###################################
	# Changes the name of the attribute
	function set_name($name) {
		if(!$name) return "All attributes must have names.";
		if($name == $this->name) return "";
		$db = &$this->get_db();
		while($db->single_element("SELECT attributeid FROM $this->table_name WHERE name='".addslashes($name)."' AND attributeid != '$this->id' AND categoryid='$this->categoryid' AND recordid='$this->recordid'")) {
			$name = increment_name($name);
		}
		$old_name   = $this->name;
		$this->name = $name;
		$db->update("UPDATE $this->table_name SET name='".addslashes($this->name)."' WHERE attributeid='$this->id'");
		$this->clear_cache();
		$tree = &$this->get_category_tree();
		$tree->new_attribute_name($this->id,$this->name);
		return "Attribute '$old_name' renamed to '$this->name' (ID: $this->id).";
	}

	/**
	* Changes the type of the atribute
	* 
	* @param string $type The type of attribute you want to set to
	* @access public
	* @return string
	*/
	function set_type($type) {
		if(!$type || $type == $this->type) return;
		$old_value_table = $this->get_value_table_name();
		$old_default_table = $this->get_default_value_table_name();
		$n     = &$this->get_notitia_system();
		$xtras = &$n->get_xtras();
		if($xtras->codename("attribute/types",$type) != $type) return;
		$db = &$this->get_db();
		$old_type   = $this->type;
		$old_params = $this->parameters;
		$this->type = $type;
		$db->update("UPDATE $this->table_name SET type='$type' WHERE attributeid='$this->id'");
		$type_obj = &$this->get_type_handler();
		$type_obj->load_default_parameters();
		# Merge any common parameters from the old one.
		foreach($old_params as $k => $v) {
			if(isset($this->parameters[$k])) $this->parameters[$k] = $v;
		}
		$this->save_parameters();
		$this->clear_cache();
		$new_value_table = $this->get_value_table_name();
		$new_default_table = $this->get_default_value_table_name();
		# If the value tables aren't the same we'll have to move the values from one table to the other
		if($old_value_table != $new_value_table) {
			$db->insert("INSERT INTO $new_value_table SELECT * FROM $old_value_table WHERE attributeid = '$this->id'");
			$db->delete("DELETE FROM $old_value_table WHERE attributeid='$this->id'");
		}
		# If the default value tables aren't the same we'll have to move the values from one table to the other
		if ($old_default_table != $new_default_table){
			$db->insert("INSERT INTO $new_default_table SELECT * FROM $old_default_table WHERE attributeid = '$this->id'");
			$db->delete("DELETE FROM $old_default_table WHERE attributeid ='$this->id'");
		}

		return "Attribute type changed from '".$xtras->name("attribute/types",$old_type)."' to '".$xtras->name("attribute/types",$type)."' (ID: $this->id).";
	}

	 #####################################################
	# Saves the current parameter array into the database
	function save_parameters() {
		$type = &$this->get_type_handler();
		$this->parameters["__type__"]    = $type->codename;
		$this->parameters["__version__"] = $type->version;
		$db = &$this->get_db();
		$this->clear_cache();
		return $db->update("UPDATE $this->table_name SET parameters='".addslashes(serialize($this->parameters))."' WHERE attributeid='$this->id'");
	}

	 ##########################################
	# Returns a value from the parameters
	function &get_value($code) {
		return $this->parameters[$code];
	}

	 ##########################################
	# Returns a value from the parameters
	function &set_value($code,$value) {
		$this->parameters[$code] = $value;
	}

	/**
	* Checks to see if this attribute type is compatible with a role
	*
	* @param   object $role A role object
	* @access  public
	* @returns boolean
	*/
	function role_allowed($role) {
		$role_handler = &$this->get_role_handler($role);
		if ($role_handler) {
			return $role_handler->role_allowed();
		} else {
			$result = false;
		}
		return $result;
	}

	 #################################################
	# Returns a boolean whether or not the attribute
	# plays a role
	function plays_role($role) {
		return isset($this->roles[$role]);
	}

	 ##################################
	# Returns information about a role
	function &get_role_info($role) {
		if($this->plays_role($role)) return $this->roles[$role];
		else return;
	}
	
	 ################################################################
	# Loads all the relevant information about this record from the
	# database or the cache
	function load($attributeid) {

		$attributeid = abs((int) $attributeid);

		if (!$attributeid && !($attributeid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load attribute without a valid attributeid.",__FILE__,__LINE__);
			return;
		}

		 #################
		# Check the cache
		if ($this->load_from_cache($attributeid)) {
			return $attributeid;
		}

		# Load everything from the database;
		$db = &$this->get_db();

		list($this->id,$this->categoryid,$this->recordid,$this->type,$this->name,$this->parameters,$this->order_no) = $db->single_row("SELECT attributeid, categoryid, recordid, type, name, parameters, order_no FROM $this->table_name WHERE attributeid='$attributeid'");

		if(!$this->id) return;

		$this->parameters = unserialize($this->parameters);

		if(!$this->recordid && !$this->requires_context_values()) {
			$default_table_name = $this->get_default_value_table_name();
			$this->defaults = $db->associative_array("SELECT categoryid, value AS v, inherit_type AS i, sibling_type AS s FROM $default_table_name WHERE attributeid='$this->id'");
			for(reset($this->defaults); strlen($categoryid = key($this->defaults)); next($this->defaults)) {
				$this->unserialize($this->defaults[$categoryid]['v']);
			}
		} else {
			unset($this->defaults);
		}

		$this->roles = $db->associative_array("SELECT role, parameters FROM $this->table_name"."_role WHERE attributeid='$this->id'");
		for(reset($this->roles); NULL !== ($role = key($this->roles)); next($this->roles)) {
			$this->roles[$role] = unserialize($this->roles[$role]);
		}

		# Okay I think that's just about everything.

		 ###################
		# Save to the cache 
		$this->save_to_cache();

		return $this->id;
	}


	 ################################################
	# Duplicates the attribute in the current context
	function dupe() {
		$name = increment_name($this->name);
		$type = $this->type;
		$this->parameters['new_attribute_name'] = '';
		$n = &$this->get_notitia_system();
		if($attributeid = $n->create_attribute($type,$name,$this->categoryid,$this->recordid)) {
			# Parameters
			$attribute = &$this->get_attribute($attributeid);
			$attribute->parameters = $this->parameters;
			$attribute->save_parameters();
			# Roles
			$attribute->roles = $this->roles;
			foreach(array_keys($attribute->roles) as $role) {
				$role_handler = &$attribute->get_role_handler($role);
				$role_handler->save_parameters();
			}
			# Default values
			if(!$this->requires_context_values()) {
				$attribute->defaults = $this->defaults;
				foreach(array_keys($attribute->defaults) as $categoryid) {
					$attribute->save_default_override($categoryid);
				}
			}

			# Dupe attribute xtras
			if ($this->has_xtras()) {
				$this_type = $attribute_xtras = $this->get_type_handler();
				$attribute_xtras = $this_type->get_attribute_xtras();

				$dupe_map = array();
				foreach ($attribute_xtras as $xtraid) {
					$attribute_xtra = $this->get_attribute_type_xtra($xtraid);
					$new_xtraid = $n->create_attribute_type_xtra($attribute_xtra->type, $attribute_xtra->name, $attribute->id);
					$dupe_map[$xtraid] = $new_xtraid;

					$attribute_xtra->dupe($new_xtraid, $dupe_map);
				}
			}


			return "New attribute '$attribute->name' created (duplicate of $this->name).";
		}
	}


	 ##########################################################
	# Deletes an attribute and deals with all the other things
	# affected by this.
	function delete() {
		$db   = &$this->get_db();
		$e    = &$this->get_notitia_system();
		$tree = &$this->get_category_tree();
		$default_table_name = $this->get_default_value_table_name();

		 ############################################################
		# Disassociate ourselves with all the records that needed us
		$recordids = &$this->get_associated_recordids();
		foreach($recordids as $recordid) {
			$this->disassociate_with_record($recordid);
		}

		# Delete any possible role stuff for this attribute
		$db->delete('DELETE FROM '.$this->table_name.'_role WHERE attributeid='.$this->id);
		$db->delete('DELETE FROM '.$this->table_name.'_role_auto_increment WHERE attributeid='.$this->id);

		 ######################################################
		# Remove the default value and any defaultr overrides
		$db->delete("DELETE FROM $default_table_name WHERE attributeid='$this->id'");

		 #########################################################
		# Delete any variety sets that use us to generate options
		$variety_setids = $db->single_column("SELECT variety_setid FROM $e->table_name"."_variety_set_option_attribute WHERE attributeid='$this->id'");
		foreach($variety_setids as $variety_setid) {
			$variety_set = &$this->get_variety_set($variety_setid);
			$message .= $variety_set->delete()."\n";
		}

		 ######################################################
		# And any reference to us being varies by a variety set
		$db->delete("DELETE FROM $e->table_name"."_variety_set_varied_attribute WHERE attributeid='$this->id'");

		 ####################################
		# Delete any xtras this attribute has
		$db->delete('DELETE FROM '.$this->table_name.'_xtra WHERE attributeid=\''.$this->id.'\'');

		 ###################
		# Delete our record
		$db->delete("DELETE FROM $this->table_name WHERE attributeid='$this->id'");
		$this->clear_cache();
		$this->clear_associated_record_caches();

		$message .= "Attribute '$this->name' (ID: $this->id) deleted.";
		$tree->remove_attribute($this->id);
		$e->forget_attribute($this->id);
		$this->id = '';
		return $message;
	}

	/**
	* Moves an attribute to another category/record
	*
	* @param int  $categoryid      The category id to move to
	* @param bool $postvar_existed Whether it found the postvar or not
	* @param int  $recordid        The recordid to move it to (not that individual records with attributes actually works)
	* @access public
	* @return string
	*/
	function move($categoryid=0, $postvar_existed=false, $recordid=0) {
		if (!$postvar_existed) return;
		if($categoryid == $this->categoryid && $this->recordid == $recordid) return;

		$tree = &$this->get_category_tree();

		if(!$tree->exists($categoryid)) return;

		$old_recordids = $this->get_associated_recordids();

		$this->clear_associated_record_caches();

		$old_categoryid   = $this->categoryid;
		$old_recordid     = $this->recordid;
		$this->categoryid = (int) $categoryid;
		$this->recordid   = (int) $recordid;

		$old_category = &$this->get_category($old_categoryid);
		$old_category->clear_cache();
		$new_category = &$this->get_category($this->categoryid);
		$new_category->clear_cache();

		$db = &$this->get_db();
		$db->update("UPDATE $this->table_name SET categoryid='$categoryid', recordid='$this->recordid' WHERE attributeid='$this->id'");
		unset($this->temp['associated_recordids']);

		$this->set_default_override($this->categoryid,$this->defaults[$old_categoryid]['v'],$this->defaults[$old_categoryid]['i'],$this->defaults[$old_categoryid]['s']);

		$this->remove_default_override($old_categoryid);

		$new_recordids = $this->get_associated_recordids(1);

		$del_recordids = array_diff($old_recordids,$new_recordids);
		$add_recordids = array_diff($new_recordids,$old_recordids);

		foreach($del_recordids as $recordid) {
			$this->disassociate_with_record($recordid);
		}

		foreach($add_recordids as $recordid) {
			$this->associate_with_record($recordid);
		}

		$this->clear_associated_record_caches();

		$this->clear_cache();

		return "Attribute '$this->name' moved from '".$tree->name($old_categoryid)."' to '".$tree->name($this->categoryid)."'.";
	}


	 ################################################################
	# Returns true if this attribute is shared betweem many records
	# (or could be potentially anyway)
	function shared() {
		return ((int)$this->recordid) <= 0;
	}

	 #####################################################
	# Returns true if this attribute generates its values
	# from the context rather than an actual value. For example
	# it may need to know "record 1235" rather than record 1235's value
	function requires_context_values() {
		$type = &$this->get_type_handler();
		return $type->settings['requires_context_values'];
	}


	 ######################################################################
	# Clears the cache of any records associated with this attribute
	function clear_associated_record_caches() {
		global $CACHE;
		$e = &$this->get_notitia_system();
		$class = get_class($e).'_record';
		foreach($this->get_associated_recordids() as $recordid) {
			$CACHE->clear($recordid,$class);
		}
	}

	 ############################################################
	# Returns a list of the recordids that should be associated
	# with this attribute
	function &get_associated_recordids($nocache = 0) {
		if($nocache || !isset($this->temp['associated_recordids'])) {
			if($this->recordid) {
				$this->temp['associated_recordids'] = array($this->recordid);
			} else {
				$tree = &$this->get_category_tree($siteid);
				$descendants = $tree->all_descendantids($this->categoryid);
				$db = &$this->get_db();
				$e = &$this->get_notitia_system();
				$this->temp['associated_recordids'] = $db->single_column("SELECT recordid FROM $e->table_name"."_record_to_category WHERE categoryid IN ('$this->categoryid','".(implode("','",$descendants))."')");
			}
		}
		return $this->temp['associated_recordids'];
	}

	 
	 ######################################################################
	# This function is called when the system decides that for some reason
	# this attribute should be associated with a record. It should take 
	# this for granted and go ahead and get things organised
	function associate_with_record($recordid, $categoryids) {
		$db = &$this->get_db();
		# Tell the tree about this
		if (!empty($categoryids)) {
			$tree = &$this->get_category_tree();
			foreach($categoryids as $categoryid) {
				$tree->associate_attribute_with_record($this->id, $recordid, $categoryid);
			}
		}
		if(!$this->plays_role('allow_null') && !$this->plays_role('force_null') && !$this->requires_context_values()) {
			$record = &$this->get_record($recordid);
			if($this->plays_role('auto_increment')) {
				$role = &$this->get_role_handler('auto_increment');
				$record->attribute_values[$this->id] = $role->increment();
				$record->save_attribute_value($this->id);
			} elseif ($this->plays_role('creation_time')) {
				$current_time = time();
				$broken_rule_msgs = '';
				$this->validate_value($current_time, $broken_rule_msgs);
				if (!$broken_rule_msgs) {
					$record->attribute_values[$this->id] = $current_time;
					$record->save_attribute_value($this->id);
				}
			} else {
				$record->attribute_values[$this->id] = $this->get_default_value($categoryids);
				$record->save_attribute_value($this->id);
			}
		}
		$type = &$this->get_type_handler();
		$type->spawn_varieties($recordid);
	}

	 #################################################
	# Validates a value according to the type criteria
	function validate_value(&$value, &$rule_broken_msgs, $validation_type='none') {
		$type_handler = &$this->get_type_handler();
		return $type_handler->validate_value($value, $rule_broken_msgs, $validation_type);
	}


	/**
	* NOTE: Should only be called from record save_attribute_value
	* Given a recordid, saves a value for this attribute for it
	*
	* @access public
	* @param int $recordid - the recordid obviously
	* @param mixed $value - the value
	* @return void
	*/
	function save_record_value($recordid, &$value, $dont_check) {
		if($this->plays_role('force_null') || $this->requires_context_values() || $this->type == 'foreign_key' && $this->get_value('reversed')) return;
		if(!$dont_check) {
			# validate and don't save if we are breaking rules
			$rule_break_msgs = '';
			$this->validate_value($value, $rule_break_msgs);
			if ($rule_break_msgs) {
				return $rule_break_msgs;
			}
		}
		$this->pre_save_record_value($recordid, &$value);

		$db = &$this->get_db();
		$table_name = $this->get_value_table_name();
		return $db->replace("REPLACE INTO $table_name (value,attributeid,recordid) VALUES ('".addslashes($this->serialize($value))."','$this->id','$recordid')");
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
		$attribute_type = &$this->get_type_handler();
		$attribute_type->pre_save_record_value($recordid, &$value);
	}

	 ############################################################
	# Given a recordid, saves a value for this attribute for it
	# but does so in a way to increment or derement a numeric value
	# this is useful for updating an attribute and ensuring all
	# increments are recorded.
	function save_incremented_record_value($recordid, $value) {
		$db = &$this->get_db();
		$table_name = $this->get_value_table_name();
		if($db->single_element("SELECT attributeid FROM $table_name WHERE attributeid='$this->id' AND recordid='$recordid'")) {
			return $db->update("UPDATE $table_name SET value = value + '".addslashes($this->serialize($value))."' WHERE attributeid='$this->id' AND recordid='$recordid'");
		} else {
			return $db->replace("REPLACE INTO $table_name (value,attributeid,recordid) VALUES ('".addslashes($this->serialize($value))."','$this->id','$recordid')");
		}
	}

	 ############################################################
	# Maybe the attribute allows things to be null...
	# Must also handle any variations of this attribute
	function clear_record_value($recordid) {
		$db = &$this->get_db();
		$e  = &$this->get_notitia_system();
		$table_name = $this->get_value_table_name();
		$db->delete("DELETE FROM $table_name WHERE attributeid='$this->id' AND recordid='$recordid'");
		$db->delete("DELETE FROM $e->table_name"."_variation WHERE attributeid='$this->id' AND recordid='$recordid'");
	}

	 ############################################################
	# Given a varietyid, saves a value for this attribute for it
	function save_variation_value($recordid, $variety_setid, $coord, $type, &$value, $dont_check) {
		if(!$this->plays_role('force_null') && !$this->requires_context_values()) {
			if(!$dont_check) $this->validate_value($value);
			$db = &$this->get_db();
			$e  = &$this->get_notitia_system();
			return $db->replace("REPLACE INTO $e->table_name"."_variation (recordid,attributeid,variety_setid,coord,type,value) VALUES ('$recordid','$this->id','$variety_setid','$coord','$type','".addslashes($this->serialize($value))."')");
		}
	}

	 ############################################################
	# Given a varietyid, saves a value for this attribute for it
	# but does so in a way to increment or derement a numeric value
	# this is useful for updating an attribute and ensuring all
	# increments are recorded.
	function save_incremented_variation_value($recordid, $variety_setid, $coord, $type, $value) {
		$db = &$this->get_db();
		$e  = &$this->get_notitia_system();
		$table_name = $this->get_value_table_name();
		if($db->single_element("SELECT attributeid FROM $table_name WHERE attributeid='$this->id' AND recordid='$recordid' AND variety_setid='$variety_setid' AND coord='$coord'")) {
			return $db->update("UPDATE $table_name SET value = value + '".addslashes($this->serialize($value))."', type='$type' WHERE attributeid='$this->id' AND recordid='$recordid' AND variety_setid='$variety_setid' AND coord='$coord'");
		} else {
			return $db->replace("REPLACE INTO $e->table_name"."_variation (recordid,attributeid,variety_setid,coord,type,value) VALUES ('$recordid','$this->id','$variety_setid','$coord','$type',value + '".addslashes($this->serialize($value))."')");
		}
	}

	 ############################################################
	# Given a varietyid, saves a value for this attribute for it
	function change_variation_type($recordid, $variety_setid, $coord, $type) {
		$db = &$this->get_db();
		$e  = &$this->get_notitia_system();
		$db->update("UPDATE $e->table_name"."_variation SET type='$type' WHERE recordid='$recordid' AND attributeid='$this->id' AND variety_setid='$variety_setid' AND coord='$coord'");
	}

	 ##############################
	# Clears a saved variety value
	function clear_variation_value($recordid, $variety_setid, $coord) {
		$db = &$this->get_db();
		$e  = &$this->get_notitia_system();
		$db->delete("DELETE FROM $e->table_name"."_variation WHERE attributeid='$this->id' AND recordid='$recordid' AND variety_setid='$variety_setid' AND coord='$coord'");
	}

	 #######################################################
	# As above, except disaccociating itself with a record
	# this should clean up everything.
	function disassociate_with_record($recordid) {
		$db = &$this->get_db();
		$e  = &$this->get_notitia_system();
		$table_name = $this->get_value_table_name();
		# Value
		$db->delete("DELETE FROM $table_name WHERE attributeid='$this->id' AND recordid='$recordid'");
		# Variety set associations
		$variety_setids = $db->single_column("SELECT variety_setid FROM $e->table_name"."_variety_set WHERE recordid='$recordid'");
		if(count($variety_setids) > 0) {
			$db->delete("DELETE FROM $e->table_name"."_variety_set_option_attribute WHERE attributeid='$this->id' AND variety_setid IN ('".implode("','",$variety_setids)."')");
			$db->delete("DELETE FROM $e->table_name"."_variety_set_varied_attribute WHERE attributeid='$this->id' AND variety_setid IN ('".implode("','",$variety_setids)."')");
		}
		# Variations
		$db->delete("DELETE FROM $e->table_name"."_variation WHERE attributeid='$this->id' AND recordid='$recordid'");
	}


	 #################################################################
	# Returns the formatted default value for a bunch of categories
	function &get_formatted_default_value($categoryids,$format) {
		return $this->formatted_value($this->get_default_value($categoryids),$format);
	}

	 #####################################################
	# Gets the raw default value for a particular category
	function &_get_raw_default_value($categoryid) {
		if($this->requires_context_values()) {
			$ret_val = 'c'.$categoryid;
			return $ret_val;
		} elseif(isset($this->defaults[$categoryid])) {
			return $this->defaults[$categoryid]['v'];
		}
		$null = null;
		return $null;
	}

	 #############################################
	# Returns the default value for the attribute
	# this can vary depending on if certain
	# categories are overriding it
	function &get_default_value($categoryids) {
		if($categoryids) {
			if(is_array($categoryids)) {
				if($this->requires_context_values()) { # Special case for meta attributes
					$ret_val = 'm'.implode('|',$categoryids);
					return $ret_val;
				}
				# Now we have to figure out the default value 
				if(count($categoryids)) {
					$type_handler = &$this->get_type_handler();
					$tree = &$this->get_category_tree();
					$tree->sort_categoryids($categoryids);
					end($categoryids);
					$v = $this->_get_category_list_default_value(&$categoryids);
					return $v;
				}
			} else { # Presumably this is just an integer now
				if($this->requires_context_values()) {
					$ret_val = 'c'.$categoryids; # Special case for meta attributes
					return $ret_val; # Special case for meta attributes
				}
				list($v) = $this->_get_category_default_value_and_type($categoryids);
				return $v;
			}
		}
		# Special attribute type: meta, takes the context rather than the value
		return $this->_get_raw_default_value($this->categoryid);
	}

	 ######################################################
	# Returns a sticked list of categories for selection
	function get_category_list_with_sticks($categoryid=0) {
		$tree = &$this->get_category_tree();
		return $tree->get_category_list_with_sticks($categoryid,100);
	}


	 ####################################################
	# Given an array of categroyids, assumes its a list
	# and takes the id at the current pointer and returns
	# the inherited value of it there, and perhaps takes
	# into account the values of further ids depending on
	# the sibling variation type rule.
	function _get_category_list_default_value(&$categoryids) {
		if(!$categoryid = current($categoryids)) {
			return '';
		}
		list($v,$s_type) = $this->_get_category_default_value_and_type($categoryid);
		if($s_type == '__null') {
			prev($categoryids);
			return $this->_get_category_list_default_value($categoryids);
		}
		if($s_type == '') {
			prev($categoryids);
			$old_v = $this->_get_category_list_default_value($categoryids);
			if($old_v === '') {
				return $v;
			} else {
				return $old_v;
			}
		}
		$type_handler = &$this->get_type_handler();
		if($type_handler->variation_type_requires_original($s_type)) {
			prev($categoryids);
			$old_v = $this->_get_category_list_default_value($categoryids);
			return $type_handler->vary_value($s_type, $v, $old_v);
		} else {
			return $type_handler->vary_value($s_type, $v);
		}
	}

	 #####################################################
	# Returns the default value at a particular category
	# level and the sibling variation type
	function _get_category_default_value_and_type($categoryid) {
		$tree = &$this->get_category_tree();
		if(!$tree->exists($categoryid)) return;
		# Special meta attributes have different rules.
		if($this->requires_context_values()) {
			return array("c$categoryid",'replace');
		} elseif(isset($this->defaults[$categoryid])) {
			$type_handler = &$this->get_type_handler();
			if(!$type_handler->variation_type_requires_original($this->defaults[$categoryid]['i']) || $categoryid == $this->categoryid) {
				if($categoryid == $this->categoryid) {
					return array($this->defaults[$categoryid]['v'],'');
				} else {
					return array($type_handler->vary_value($this->defaults[$categoryid]['i'], $this->defaults[$categoryid]['v']),$this->defaults[$categoryid]['s']);
				}
			} else {
				if($categoryid) {
					list($v) = $this->_get_category_default_value_and_type($tree->parentid($categoryid));
					return array($type_handler->vary_value($this->defaults[$categoryid]['i'], $this->defaults[$categoryid]['v'],$v),$this->defaults[$categoryid]['s']);
				} else {
					return array($this->defaults[$categoryid]['v'],$this->defaults[$categoryid]['s']);
				}
			}
		} else {
			if(!$categoryid) {
				return array('','__null');
			}
			return $this->_get_category_default_value_and_type($tree->parentid($categoryid));
		}
	}


	 ##########################################################
	# Saves any changes made to a particular category default
	function save_default_override($categoryid) {
		if(!isset($this->defaults[$categoryid])) return;
		$db = &$this->get_db();
		$default_table_name = $this->get_default_value_table_name();
		$db->replace("REPLACE INTO $default_table_name (attributeid,categoryid,value,inherit_type,sibling_type) VALUES('$this->id','$categoryid','".addslashes($this->serialize($this->defaults[$categoryid]['v']))."','{$this->defaults[$categoryid][i]}','{$this->defaults[$categoryid][s]}')");
		$this->clear_cache();
	}
	

	 ###################################
	# Sets a new default override value
	function set_default_override($categoryid,&$value,$inherit_type,$sibling_type) {
		if($this->recordid || $this->requires_context_values()) return;
		$tree = &$this->get_category_tree();
		if(!$tree->exists($categoryid)) return;
		$this->defaults[$categoryid] = array('v'=>$value,'i'=>$inherit_type,'s'=>$sibling_type);
		$this->save_default_override($categoryid);
		return "Updated default override for '$this->name' attribute in '".$tree->name($categoryid)."' category.";
	}

	
	 #################################################################
	# Removes the overriding default value for a particular category
	function remove_default_override($categoryid) {
		if(isset($this->defaults[$categoryid]) && $categoryid != $this->categoryid) {
			unset($this->defaults[$categoryid]);
			$db = &$this->get_db();
			$default_table_name = $this->get_default_value_table_name();
			$db->delete("DELETE FROM $default_table_name WHERE attributeid='$this->id' AND categoryid='$categoryid'");
			$this->clear_cache();
			$tree = &$this->get_category_tree();
			return "Removed default override for '$this->name' attribute removed in '".$tree->name($categoryid)."' category.";
		}
	}

	 ######################################################################
	# Returns the number of possible varieties generated by this attribute
	function get_variety_count(&$value) {
		$handler = &$this->get_type_handler();
		return $handler->get_variety_count($value);
	}

	 ###########################################################
	# Returns a list of the varieties offered by this attirbute
	function &get_variety_names(&$value) {
		if(!$this->get_type_setting("generates_varieties")) {
			$tmp = array(0 => "");
			return $tmp;
		}
		$handler = &$this->get_type_handler();
		return $handler->get_variety_names($value);
	}
	
	 #################################################################
	# Returns a list of the varieties of this attribute in a certatin
	# context
	function &get_varieties_in_context($categoryid, $recordid) {
		$tree = &$this->get_category_tree();
		$r = array();
		foreach($tree->get_context_variety_sets($categoryid, $recordid) as $variety_setid => $name) {
			$variety_set = &$this->get_variety_set($variety_setid);
			if(!in_array($this->id,$variety_set->varied_attributeids)) continue;
			$r[$variety_setid] = $variety_set->get_variety_names($categoryid, $recordid);
		}
		return $r;
	}
	
	
	 ###################################################
	# Attempts to get a role performing some function
	# in the scheme of things
	function play_role($role_name) {
		if($this->plays_role($role_name)) return;
		$e     = &$this->get_notitia_system();
		$xtras = &$e->get_xtras();
		if(!$role_name = $xtras->codename('attribute/roles',$role_name)) return;
		$role = &$this->get_role_handler($role_name);
		return   $role->apply_for_role();
	}

	 ###################################################
	# Attempts to get a role performing some function
	# in the scheme of things
	function unplay_role($role_name) {
		if(!$this->plays_role($role_name)) return;
		$e     = &$this->get_notitia_system();
		$xtras = &$e->get_xtras();
		$role  = &$this->get_role_handler($role_name);
		return    $role->resign_from_role();
	}
	

	 ##############################################################
	# Serializes and unserializes values according to the way the
	# attribute type wants to do it
	function serialize(&$v) {
		$handler = &$this->get_type_handler();
		return $handler->serialize($v);
	}

	/**
	* Unserializes a value
	*
	* @param   string &$v The value to unserialize
	* @access  public
	* @returns mixed
	*/
	function unserialize(&$v) {
		$handler = &$this->get_type_handler();
		if (!$handler) return;
		$handler->unserialize($v);
	}

	 ############################################
	# Given a value for this attribute, returns
	# a formatted version of it
	function compare_values(&$value1,&$value2) {
		$type = &$this->get_type_handler();
		return $type->compare_values($value1,$value2);
	}

	 ############################################
	# Given a value for this attribute, returns
	# a formatted version of it
	function formatted_value(&$value,$format) {
		$type = &$this->get_type_handler();
		return $type->formatted_value($value,$format);
	}

	 ########################################################
	# Prints the field for filling in this attribute with 
	# a value.
	function print_field($prefix,&$value,$format) {
		$type_handler = &$this->get_type_handler();
		$type_handler->print_field($prefix, $value, $format);
	}


	 ########################################################
	# Like above, but prints a smaller field without note 
	# text etc, for those day when you jsut want to pack
	# thousands of fields all next to eachother.
	function print_concise_field($prefix,&$value,$format) {
		$type_handler = &$this->get_type_handler();
		$type_handler->print_concise_field($prefix, $value,$format);
	}
	
	 #######################################
	# Prints 'n' processes searching thigns
	# See the attribute_types for more information
	function print_search_field($prefix, &$default, $format, $not_null, $search_view_format) {
		$type_handler = &$this->get_type_handler();
		$type_handler->print_search_field($prefix, $default, $format, $not_null, $search_view_format);
	}

	function &process_search_field($prefix, $format, $not_null) {
		$type_handler = &$this->get_type_handler();
		return $type_handler->process_search_field($prefix, $format, $not_null);
	}

	function set_search_request($prefix,$value,$format) {
		$type_handler = &$this->get_type_handler();
		return $type_handler->set_search_request($prefix,$value,$format);
	}
	function get_search_sql_clause($query,$format,$value_string='v.value') {
		$type_handler = &$this->get_type_handler();
		return $type_handler->get_search_sql_clause($query,$format,$value_string);
	}
	function get_search_sql_sort_clause($format, $value_string='v.value') {
		$type_handler = &$this->get_type_handler();
		return $type_handler->get_search_sql_sort_clause($format,$value_string);
	}
	function get_search_sql_score($query,$format,$value_string='v.value') {
		$type_handler = &$this->get_type_handler();
		return $type_handler->get_search_sql_score($query,$format,$value_string);
	}
	 ########################################################
	# Prints out the value of the attribute in whatever way 
	# the attribute type thinks best. This basically means
	# "display" the value of the attribute in a way that would
	# be meaningful to a human-reader/viewer, and is not expected
	# to be just a echo($this->value); type thing.
	function paint(&$value,$format) {
		$type_handler = &$this->get_type_handler();
		$type_handler->paint($value,$format);
	}

	 ########################################################
	# Process the field for filling in this attribute with 
	# a value
	function process_field($prefix, &$value_destination, $format, &$rules_broken_messages) {
		$type_handler = &$this->get_type_handler();
		return $type_handler->process_field($prefix, $value_destination, $format, $rules_broken_messages);
	}

	 ##################################################
	# Returns a setting defined by the "type" of the 
	# attribute
	function &get_type_setting($setting) {
		$type_handler = &$this->get_type_handler();
		return $type_handler->get_setting($setting);
	}

	 #################################################
	# Does the current user have permission to delete
	# this attribute?
	function delete_access() {
		$e    = &$this->get_notitia_system();
		if($e->superuser()) return true;
		if($this->recordid) {
			$record = &$this->get_record($recordid);
			return $record->edit_access();
		} elseif($this->categoryid) {
			$category = &$this->get_category($categoryid);
			return $category->delete_attribute_access();
		} else {
			return $e->delete_attribute_access();
		}
	}

	 ########################################
	# Gets a references to the parameter set
	function &get_pset() {
		$pset = &$this->temp['parameter_set'];
		if(get_class($pset) !== "parameter_set") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/parameter_set.inc");
			$e = &$this->get_notitia_system();
			$pset = new Parameter_Set(get_class($this),"$e->xtra_path/attribute.pset",$this->parameters, $this);
		}
		return $pset;
	}

	 ####################################################
	# Returns the href for the backend of this attribute
	function get_backend_href() {
		$e = &$this->get_notitia_system();
		return $e->get_backend_href()."&edit_categoryid=$this->categoryid&edit_recordid=$this->recordid&edit_attributeid=$this->id";
	}

	 ##############################################
	# Prints a backend, for editing the attribute
	function print_backend() {
		$e = &$this->get_notitia_system();
		$session = &get_mysource_session();
		$backend = &$e->setup_backend();
		$backend->set_tab($this->table_name,$this->get_backend_href(),"Attribute: $this->name","Edit this attribute.","page");
		$backend->set_active_tab($this->table_name);

		$backend->set_heading("Edit Attribute - $backend->heading","page");
		$backend->set_name($this->name);
		$backend->set_id_string($this->id);
	
		 ###################
		# Check permissions
		if($this->recordid) {
			$record = &$this->get_record($this->recordid);
			if(!$record->edit_access()) {
				$session->login_screen($backend->title,"You must be logged in and have permission to edit the record <i>".$record->name()."</i> $e->name in order to proceed.");
			}
		} elseif($this->categoryid) {
			$category = &$this->get_category($this->categoryid);
			if(!$category->edit_attribute_access()) {
				$session->login_screen($backend->title,"You must be logged in and have permission to edit the <i>$category->name</i> $e->name category attributes in order to proceed.");
			}
		} else {
			if(!$e->edit_attribute_access()) {
				$session->login_screen($backend->title,"You must be logged in and have permission to edit the $e->name root category attributes in order to proceed.");
			}
		}

		$edit_role = $_REQUEST['edit_role'];

		if($edit_role && $this->plays_role($edit_role)) {
			$role_handler = &$this->get_role_handler($edit_role);
			$role_handler->print_backend();
			return;
		}
		
		# Deletin'
		if($this->delete_access()) {
			$delete_attribute = $_REQUEST['delete_attribute'];
			if($delete_attribute) {
				$recordid  = $this->recordid;
				$categoryid = $this->categoryid;
				$backend->add_message($this->delete());
				if(!$this->id) {
					if($recordid) {
						$record = &$this->get_record($recordid);
						$backend->set_relocation($record->get_backend_href());
					} elseif($categoryid) {
						$category = &$this->get_category($categoryid);
						$backend->set_relocation($category->get_backend_href().'&PARAMETER_SCREEN=Attributes');
					} else {
						$backend->set_relocation($e->get_backend_href());
					}
					$backend->print_header();
					return;
				}
			}
			$backend->set_toolbar_button("delete","javascript:if(confirm('Are you sure you wish to delete this attribute?')){location='".$this->get_backend_href()."&delete_attribute=1';}","Delete This Attribute","delete");
		}
	
		$pset = &$this->get_pset();

		if($pset->process($backend)) {
			$this->save_parameters();
		}
		$pset->print_backend($backend);
	
	}

	 ########################################################
	# Prints out a summary of the context of this attribute
	# I.e. Which categories/records it affects
	function print_context() {
		$e = &$this->get_notitia_system();

		if($this->recordid) {
			$record = &$this->get_record($this->recordid);
			echo("<b>This Record Only:</b><br><a href=\"".$e->get_backend_href()."&edit_categoryid=$this->categoryid&edit_recordid=$this->recordid\">".$record->name()."</a>");
		} else {
			echo("All Records");
		}
		echo("<br>");
		if($this->categoryid > 0) {
			echo("<b>In this category</b> (and subcategories):");
			$tree = &$this->get_category_tree();
			$ancestorids = $tree->ancestorids($this->categoryid);
			if(count($ancestorids) > 0) {
				echo("<span class=fineprint><br>");
				foreach($ancestorids as $ancestorid) {
					echo("<a href=\"".$e->get_backend_href()."&edit_categoryid=$ancestorid\">".$tree->name($ancestorid)."</a> : ");
				}
				echo("</span>");
			}
			echo("<br><a href=\"".$e->get_backend_href()."&edit_categoryid=$this->categoryid\">".$tree->name($this->categoryid)."</a>");
		} else {
			echo("All Categories");
		}
	}

	 #########################################################
	# Prints an interface for assigning roles to a particular 
	# attribute.
	function print_role_options() {
		$e     = &$this->get_notitia_system();
		$xtras = &$e->get_xtras();
		$roles = $xtras->list_type("attribute/roles");
		echo '<table cellpadding="2" cellspacing="0" border="0">';
		foreach($roles as $role => $name) {
			if ($this->role_allowed($role)) {
				echo '<tr>';
				echo '<td valign="top"><input type="checkbox" name="active_roles['.$role.']" value="1" '.(($this->plays_role($role))?'checked':'').'></td>';
				echo '<td valign="top"><b>'.$name.'</b>';
				if($this->plays_role($role)) {
					$role_handler = &$this->get_role_handler($role);
					switch($role_handler->parameter_interface_level()) {
					case 2:
						echo ' [<a href="'.$this->get_backend_href().'&edit_role='.$role.'">Options...</a>]';
						break;
					case 1:
						echo '<br />';
						$role_handler->print_backend();
						break;
					default:
						break;
					}
				}
				if($desc = &$xtras->description('attribute/roles',$role)) {
					echo '<span class="smallprint"><br />'.$desc.'</span>';
				}
				echo '</td></tr>';
			}
		}
		echo '</table>';
	}

	 #######################################################################
	# Processes an interface for assigning roles to a particular attribute.
	function process_role_options() {
		$e     = &$this->get_notitia_system();
		$xtras = &$e->get_xtras();
		$roles = $xtras->list_type('attribute/roles');

		# Okay first process any of the active roles that need processing
		for(reset($this->roles); NULL !== ($role = key($this->roles)); next($this->roles)) {
			$role_handler = &$this->get_role_handler($role);
			if($role_handler->parameter_interface_level() == 1) {
				$message .= $role_handler->process_backend()."\n";
			}
		}

		# Now go through the list of existing roles and see if any 
		# have been switched off
		$goners = array();
		$comers = array();
		$active_roles = $_REQUEST['active_roles'];
		for(reset($this->roles); NULL !== ($role = key($this->roles)); next($this->roles)) {
			if(!$active_roles[$role]) {
				$goners[] = $role;
			}
		}
		
		# Now go through the submitted ones
		if(count($active_roles)) {
			for(reset($active_roles); NULL !== ($role = key($active_roles)); next($active_roles)) {
				if(!isset($this->roles[$role])) {
					$comers[] = $role;
				}
			}
		}

		foreach($goners as $goner) {
			if($m = $this->unplay_role($goner)) $message .= "$m\n";
		}
		foreach($comers as $comer) {
			if($m = $this->play_role($comer)) $message .= "$m\n";
		}
		return trim($message);
	}

	 #####################################################
	# Prints the parameters for this particular type of
	# attribute.
	function print_type_parameters() {
		$n     = &$this->get_notitia_system();
		$type  = &$this->get_type_handler();
		$xtras = &$n->get_xtras();
		echo combo_box("attribute_type",$xtras->list_type("attribute/types"),$this->type)."<br>";
		$type->print_backend();
	}

	 ##############################################
	# Processes changes to the type permaeters etc
	function process_type_parameters() {
		$e     = &$this->get_notitia_system();
		$type  = &$this->get_type_handler();
		$xtras = &$e->get_xtras();
		$attribute_type = $_REQUEST['attribute_type'];
		$type->process_backend();
		$message = $this->set_type($attribute_type);
		return $message;
	}

	 ##########################
	# Prints the default field
	function print_default_field($categoryid = -1) {
		if($this->recordid) return;
		if($categoryid == -1) $categoryid = $this->categoryid;
		$type  = &$this->get_type_handler();
		if(isset($this->defaults[$categoryid])) {
			$value = &$this->defaults[$categoryid]['v'];
		} else {
			$value = '';
		}
		if($categoryid == $this->categoryid) {
			$type->print_field("a_{$this->id}_c_{$categoryid}_default_v_",$value);
		} else {
			echo('<table cellpadding=4 cellspacing=0 border=1 bordercolor=#000000>');
			echo('<tr><td colspan=2>');
			$type->print_field("a_{$this->id}_c_{$categoryid}_default_v_",$value);
			echo('</td></td><tr><td class=smallprint><b>Alter Parent: ');
			echo combo_box("a_{$this->id}_c_{$categoryid}_default_i",$this->get_type_setting('variation_types'),$this->defaults[$categoryid]['i'],'class=smallprint');
			echo('</td><td class=smallprint><b>Alter Sibling: ');
			echo combo_box("a_{$this->id}_c_{$categoryid}_default_s",$this->get_type_setting('variation_types'),$this->defaults[$categoryid]['s'],'class=smallprint');
			echo('</td></tr>');
			echo('</table>');
		}
	}

	 ##########################
	# Prints the default field
	function process_default_field($categoryid = -1) {
		if($this->recordid || $this->requires_context_values()) return;
		if($categoryid == -1) $categoryid = $this->categoryid;
		$type = &$this->get_type_handler();
		$ms = array();
		$m = $this->process_field("a_{$this->id}_c_{$categoryid}_default_v_", $this->defaults[$categoryid]['v']);
		if($m) {
			if($categoryid == $this->categoryid) {
				$ms[] = "Default $m";
			} else {
				$tree = &$this->get_category_tree();
				$ms[] = "Default override for '".$tree->name($categoryid)."' category $m";
			}
		}
		if($categoryid != $this->categoryid) {
			$i = $_REQUEST['a_'.$this->id.'_c_'.$categoryid.'_default_i'];
			$s = $_REQUEST['a_'.$this->id.'_c_'.$categoryid.'_default_s'];
			if($i != $this->defaults[$categoryid]['i']) {
				$this->defaults[$categoryid]['i'] = $i;
				$ms[] = "Override type changed for inheritance.";
			}
			if($s != $this->defaults[$categoryid]['s']) {
				$this->defaults[$categoryid]['s'] = $s;
				$ms[] = "Override type changed for siblings.";
			}
		}
		if(count($ms)) { # Something's changed
			$this->save_default_override($categoryid);
		}
		return implode("\n",$ms);
	}

	/**
	* Returns the list of rules for the attribute
	*
	* @access public
	* @return array
	*/
	function get_attribute_rule_list() {
		$type = &$this->get_type_handler();
		return $type->settings['rule_types'];
	}

	/**
	* Calls the attribute to print out the rules list 
	*
	* @access public
	* @return array
	*/
	function print_rule_list() {
		$type = &$this->get_type_handler();
		$rules_array = $this->parameters['rules'];
		?>
		<table bgcolor="#888888" cellpadding="0" cellspacing="0" border="0"><tr><td>
		<table cellspacing="1" cellpadding="3">
			<tr bgcolor="#aaaaaa"><td colspan=3><b>Rules</b></td></tr>
		<?
		if (!empty($rules_array)) {
			?><tr bgcolor="#DDDDDD"><td>Rules name</td><td>Rules Settings</td><td>Delete Rule</td></tr><?
			foreach($rules_array as $rule_code => $rule_settings) {
				$interface_html = $type->get_rule_interface($rule_code, $rule_settings);
				$name = 'rule'.$this->id.'_'.$rule_code;
				$delete_rule_box = '<input type="checkbox" name="'.$name.'" VALUE="1"><i>Delete?</i>';
				?><tr bgcolor="#eeeeee"><td valign="top"><?=$rule_settings['name']?></td><td valign="top"><?=$interface_html?></td><td valign="top"><?=$delete_rule_box?></td></tr><?
			}
		} else {
			?><tr bgcolor="#eeeeee"><td align="center">No rules in use</td></tr><?
		}
		?>
		</table>
		</td></tr></table>
		<?
	}

	/**
	* Processes any rule changes that have occured including additions, deletes and edits
	*
	* @access public
	* @return string
	*/
	function process_rule_list() {
		$msgs = '';
		$type = &$this->get_type_handler();
		$new_attribute_rule = $this->parameters['new_attribute_rule'];
		$this->parameters['new_attribute_rule'] = '0';

		# check that rules is an array in the param if not make it one otherwise things will crash
		if (!isset($this->parameters['rules'])) {
			$this->parameters['rules'] = array();
		}
		$rules = &$this->get_value('rules');

		#check for adding new rules
		if ($new_attribute_rule) {
			$new_rule_settings = $type->get_new_rule_settings($new_attribute_rule);
			array_push($rules, $new_rule_settings);
			$msgs .= 'New rule created<br />';
		}

		#check for deleteing new rules
		foreach($rules as $rule_code => $rule_settings) {
			if ($_POST['rule'.$this->id.'_'.$rule_code]) {
				#delete this rule
				unset($rules[$rule_code]);
				$msgs .= 'A rule has been deleted<br />';
			}
		}

		#check for rule setting changes
		foreach($rules as $rule_code => $rule_settings) {
			$msgs .= $type->process_rule_interface($rule_code, $rules[$rule_code]);
		}
		return $msgs;
	}

	/**
	* Returns true if some rules exist
	*
	* @access public
	* @return boolean
	*/
	function rules_exist() {
		return (count($this->parameters['rules']));
	}

	/**
	* Calls the attribute type to process a possible new xtra request
	*
	* @access public
	* @return string
	*/
	function process_new_attribute_xtra() {
		# set the new attribute xtra as current
		$type_handler = &$this->get_type_handler();
		return $type_handler->process_new_attribute_xtra();
	}

	/**
	* Calls the attribute type to print the active xtra (if there is one to print)
	*
	* @access public
	* @return void
	*/
	function print_attribute_selected_xtra() {
		$type_handler = &$this->get_type_handler();
		$type_handler->print_attribute_selected_xtra();
	}

	/**
	* Calls the attribute type to get an attay of all attribute type xtras with descriptions of what they are
	*
	* @access public
	* @return array
	*/
	function get_attribute_xtra_types_with_descriptions() {
		$type_handler = &$this->get_type_handler();
		return $type_handler->get_attribute_xtra_types_with_descriptions();
	}

	/**
	* Calls the attribute type to process the selected xtras settings
	*
	* @access public
	* @return string
	*/
	function process_attribute_selected_xtra() {
		$type_handler = &$this->get_type_handler();
		return $type_handler->process_attribute_selected_xtra();
	}

	/**
	* Calls the attribute type to get an array of all attribute type xtras that this attribute has and returns xtraid as key and name as value
	*
	* @access public
	* @return array
	*/
	function get_attribute_xtras_names($order_no='none',$greater_than=true) {
		$type_handler = &$this->get_type_handler();
		return $type_handler->get_attribute_xtras_names($order_no,$greater_than);
	}

	/**
	* Calls the attribute type to get the active xtraid for this session in this attribute
	*
	* @access public
	* @return int
	*/
	function get_active_xtraid() {
		$type_handler = &$this->get_type_handler();
		return $type_handler->get_active_xtraid();
	}

	/**
	* Calls the attribute type to set the active xtraid for this session in this attribute
	*
	* @param int $new_xtraid The xtraid to set to
	* @param boolean $found_in_post_vars Whether the param value was found in the post vars
	* @access public
	* @return string
	*/
	function set_active_xtraid($new_xtraid, $found_in_post_vars=true) {
		if ($found_in_post_vars) {
			$type_handler = &$this->get_type_handler();
			return $type_handler->set_active_xtraid($new_xtraid);
		}
	}

	/**
	* Returns true if this attribute has attribute type xtras
	*
	* @access public
	* @return int
	*/
	function has_xtras() {
		$type_handler = &$this->get_type_handler();
		$xtras = $type_handler->get_attribute_xtra_types();
		return (!empty($xtras));
	}

	/**
	* Returns the table name of where this attribute type stores it's value
	*
	* @access public
	* @return string
	*/
	function get_value_table_name() {
		$type_handler = &$this->get_type_handler();
		return $type_handler->get_value_table_name();
	}

	/**
	* Returns the table name of where this attribute type stores it's default value
	*
	* @access public
	* @return string
	*/
	function get_default_value_table_name() {
		$type_handler = &$this->get_type_handler();
		return $type_handler->get_default_value_table_name();
	}
}
?>
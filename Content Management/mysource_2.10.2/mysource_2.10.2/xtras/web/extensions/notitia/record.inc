<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/record.inc,v $
## $Revision: 1.33 $
## $Author: achadszinow $
## $Date: 2004/03/26 04:28:47 $
#######################################################################

#---------------------------------------------------------------------#

 ##############################################################
# A record represents any form of object (including services)
# that can be sold through the Notitia system
class Web_Extension_Notitia_Record extends Web_Extension_Notitia_Object {
	
	# Var
	var $id; # The recordid

	var $data_path = ''; # Where we stash our stuff

	# An array of the categoryids that this record belongs in
	var $categoryids = array(); 

	 ##################################################################################
	# Associative array($attributeid => $categoryid) of attributes which apply to this
	# record specifically and no others. The categoryid indicated whether the attribute
	# applies in the context of a particular category
	var $local_attributeids = array();

	# An associative array($attributeid => $value) of the values given to each
	# attribute for this record.
	var $attribute_values = array();

	# An array of all the variations applied by the different varieties
	# array($attributeid => array($variety_setid => array($coord => $value)))
	var $variations = array();

	# array($attributeid => array($variety_setid => array($coord => $type)))
	var $variation_types = array();

	# An array ($role => array($attributeid, ...)) that makes it fast
	# for the record to find the value for a particular role
	var $attribute_roles;

	var $temp = array();

	 #############
	# Constructor
	function Web_Extension_Notitia_record($recordid) {
		Web_Extension_Notitia_Object::Web_Extension_Notitia_Object();
		if(((int) $recordid) > 0) {
			return $this->load((int) $recordid);
		} else {
			return $this->id = 0;
		}
	}


	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = Web_Extension_Notitia_Object::__sleep();
		array_remove_element("data_path",$result);
		array_remove_element("table_name",$result);
		array_remove_element("parameters",$result);
		array_remove_element("temp",$result);
		return $result;
	}

	 ##############################################
	# Wake up with this handy information EVERY day !
	function __wakeup() {
		Web_Extension_Notitia_Object::__wakeup();
		$e = &$this->get_notitia_system();
		$this->data_path  = "$e->data_path/record/$recordid";
		$this->table_name = "$e->table_name"."_record";
		$this->parameters = array();
		$this->temp       = array();
	}


	 ###########################################################
	# Creates a new record
	function create($categoryid, &$initial_values) {
		$db = &$this->get_db();
		$tree = &$this->get_category_tree();
		$n = &$this->get_notitia_system();

		if(!$categoryid || !strlen($tree->parentid($categoryid))) return; # Category doesn't exist
		$recordid = $db->insert("INSERT INTO $this->table_name () VALUES ()");
		$db->insert("INSERT INTO $this->table_name"."_to_category (recordid,categoryid) VALUES ('$recordid','$categoryid')");

		# We load the object first cause the other functions that set values assume it is
		if($recordid) {
			$this->load($recordid);
		} else {
			return false;
		}

		# associate with factory
		$n->temp['record_factory'][$recordid] = &$this;

		# Grab all the relevent attributes
		$attributes = &$tree->get_context_attributes($categoryid);
		foreach($attributes as $attributeid => $name) {
			$attribute = &$this->get_attribute($attributeid);
			$attribute->associate_with_record($recordid,array($categoryid));
			if (!$attribute->plays_role('force_null')) {
				$value = &$initial_values[$attribute->id];
				if(isset($value)) {
					$rule_break_msgs = '';
					$attribute->validate_value($value, $rule_broken_msgs);
					if ($rule_break_msgs == '') {
						$this->attribute_values[$attribute->id] = $value;
						$this->save_attribute_value($attribute->id);
					}
				}
			}
		}

		return $this->id;
	}

	 ################################################################
	# Loads all the relevant information about this record from the
	# database or the cache
	function load($recordid) {

		$recordid = abs((int) $recordid);

		if (!$recordid && !($recordid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load record without a valid recordid.",__FILE__,__LINE__);
			return;
		}

		 #################
		# Check the cache
		if ($this->load_from_cache($recordid)) {
			return $recordid;
		}

		# We may need this to talk to
		$e = &$this->get_notitia_system();

		# Load everything from the database;
		$db = &$this->get_db();

		$category_tree = &$this->get_category_tree();

		list($this->id) = $db->single_row("SELECT recordid FROM $this->table_name WHERE recordid='$recordid'");

		if(!$this->id) return;

		if($this->id != $recordid) {
			return;
		}

		$this->categoryids = $db->single_column("SELECT categoryid FROM $this->table_name"."_to_category WHERE recordid='$this->id'");
		$category_tree->sort_categoryids($this->categoryids);

		# Get a list of the local attributes in the correct categorical order
		$local_attributeids = $db->single_column("SELECT attributeid FROM $e->table_name"."_attribute WHERE recordid='$this->id' ORDER BY order_no");
		$category_tree->sort_attributeids($local_attributeids);

		$with_categoryids = $db->associative_array("SELECT attributeid, categoryid FROM $e->table_name"."_attribute WHERE attributeid IN ('".implode("','",$local_attributeids)."')");
		foreach($with_categoryids as $attributeid => $categoryid) {
			$this->local_attributeids[$attributeid] = $categoryid;
		}

		$all_attributeids = $this->all_attributeids();

		foreach($all_attributeids as $attributeid) {
			$attribute = &$this->get_attribute($attributeid);
			if ($attribute->id) {
				$table_name = $attribute->get_value_table_name();
				# I use single column because I know if it returns an empty array it didn't find a record in the table

				$attribute_values = $db->single_column("SELECT value FROM $table_name WHERE recordid='$this->id' AND attributeid = $attributeid");
				if (!empty($attribute_values)) {
					# There should always be a value and only one hense the reset
					$this->attribute_values[$attributeid] = reset($attribute_values);
				}
			}
		}

		for(reset($this->attribute_values); NULL !== ($attributeid = key($this->attribute_values)); next($this->attribute_values)) {
			$attribute = &$this->get_attribute($attributeid);
			$attribute->unserialize($this->attribute_values[$attributeid]);
		}

		$this->variations = $db->associative_array3("SELECT attributeid, variety_setid, coord, value FROM $e->table_name"."_variation WHERE recordid='$this->id'");
		for(reset($this->variations); NULL !== ($attributeid = key($this->variations)); next($this->variations)) {
			$attribute = &$this->get_attribute($attributeid);
			$a = &$this->variations[$attributeid];
			for(reset($a); NULL !== ($variety_setid = key($a)); next($a)) {
				$v = &$a[$variety_setid];
				for(reset($v); NULL !== ($coord = key($v)); next($v)) {
					$attribute->unserialize($v[$coord]);
				}
			}
		}

		$this->variation_types = $db->associative_array3("SELECT attributeid, variety_setid, coord, type FROM $e->table_name"."_variation WHERE recordid='$this->id'");

		$this->roles = $db->associative_column("SELECT role, attributeid FROM $e->table_name"."_attribute_role WHERE attributeid IN ('".implode("','",$all_attributeids)."')");
	
		# Okay I think that's just about everything.

		 ###################
		# Save to the cache 
		$this->save_to_cache();

		return $recordid;
	}


	 #######################################################
	# Returns an array of ALL the categoryids this record
	# is in, including all parents
	function all_categoryids() {
		$t = &$this->get_category_tree();
		return array_merge($this->categoryids, $t->bulk_ancestorids($this->categoryids));
	}

	/**
	* Returns a array of category id's that this record is associated with 
	 * @access public
	* @return array
	*/
	function associated_categoryids() {
		return $this->categoryids ;
	}

	 ##########################################
	# Returns an array of ALL the attributeids 
	# relevent to this record
	function all_attributeids() {
		$tree = &$this->get_category_tree();
		return array_keys($tree->get_context_attributes(0,$this->id,&$this));
	}

	 ######################################
	# Returns an array of attirbute types
	function get_attribute_types() {
		$e = &$this->get_notitia_system();
		return $e->get_attribute_types();
	}

	 ##############################################################
	# Returns an array of attirbute types along with descriptions
	# formatted for printing in a checkbox list
	function get_attribute_types_with_descriptions() {
		$e = &$this->get_notitia_system();
		return $e->get_attribute_types_with_descriptions();
	}

	 ########################################################
	# Returns an array($categoryid => $full_path_name) array
	# of all the associated categories
	function &get_full_category_names_list() {
		if(!isset($this->temp["full_category_names_list"])) {
			$tree = &$this->get_category_tree();
			$this->temp["full_category_names_list"] = array();
			foreach($this->categoryids as $categoryid) {
				$str = $tree->name($categoryid);
				foreach($tree->ancestorids($categoryid) as $ancestorid) {
					$str = $tree->name($ancestorid)." > $str";
				}
				$this->temp["full_category_names_list"][$categoryid] = $str;
			}
		}
		return $this->temp["full_category_names_list"];
	}


	 ################################################################
	# Returns an array($id => $name) of the attributes associated with
	# this record (shared and local) that generate
	function &get_variety_generating_attribute_names() {
		$e = &$this->get_notitia_system();
		return $e->get_variety_generating_attribute_names(0,$this->id);
	}


	 ################################################################
	# Returns an array($id => $name) of the attributes associated with
	# this record (shared and local) that generate
	function &get_variable_attribute_names() {
		$e = &$this->get_notitia_system();
		return $e->get_variable_attribute_names(0,$this->id);
	}


	 ###############################################################
	# Returns an array of attributeid => array(variety_setid, ...)
	# so that you know which attirbute is affected by which variety sets
	function &get_attribute_variety_setids() {
		if(!isset($this->temp['attribute_variety_setids'])) {
			$e    = &$this->get_notitia_system();
			$db   = &$this->get_db();
			$tree = &$this->get_category_tree();

			$attributeids = array_keys($tree->get_context_attributes(0,$this->id));

			# Now let's find out which attributes will have a variety of values.
			$contextids = array_merge(
					array(0),
					$this->categoryids,
					$tree->bulk_ancestorids($this->categoryids)
				);
		
			$q = "SELECT vsva.attributeid, vsva.variety_setid
				  FROM $e->table_name"."_variety_set vs,
				  $e->table_name"."_variety_set_varied_attribute vsva
				  WHERE vsva.variety_setid=vs.variety_setid 
				  AND vsva.attributeid IN ('".implode("','",$attributeids)."')
				  AND (vs.recordid = '$this->id'
					OR vs.categoryid IN (0,'".implode("','",$contextids)."'))
				  ";

			$this->temp['attribute_variety_setids'] = $db->associative_column($q);
		}
		return $this->temp['attribute_variety_setids'];
	}


	 ######################################################
	# Returns the value of an attribute thats associated
	# with a particular role. Returns an array unless 
	# a seperated is supplied, and then it returns a string 
	# of values seperated by the seperator.
	function get_role_value($role, $seperator = '') {
		if($seperator) {
			$r = '';
		} else {
			$r = array();
		}
		if(isset($this->roles[$role])) {
			$i = 0;
			foreach($this->roles[$role] as $attributeid) {
				if($seperator) {
					$v = &$this->get_attribute_value($attributeid);
					if($i) {
						$r .= "$seperator$v";
					} else {
						$r  = $v;
					}
				} else {
					$r[] = &$this->get_attribute_values($attributeid);
				}
				$i++;
			}
		}
		return $r;
	}

	 ##########################################
	# Returns a value from the parameters
	function &get_value($code) {
		return $this->parameters[$code];
	}

	 ###############################################################################
	# Simply returns a reference to the raw value of an attribute for this record
	# If we don't have an attributeid, try to use the name to determine the attribute
	function &get_attribute_value($attributeid, $name='') {
		if (!$attributeid && $name != '') {
			$variable_attribute_names = &$this->get_variable_attribute_names();
			$name = strtolower($name);
			foreach($variable_attribute_names as $id => $attribute_name) {
				if($name == strtolower($attribute_name)) {
					$attributeid = $id;
				}
			}
		}
		if (!$attributeid) return;

		$attribute = &$this->get_attribute($attributeid);
		if($attribute->requires_context_values()) {
			# Special attribute type: meta, takes the context rather than the value
			$ret_val = 'r'.$this->id;
			return $ret_val;
		}

		if($this->check_attribute_role($attributeid,'force_null')) {
			return $attribute->get_default_value($this->categoryids);
		}
		if(!$this->null_attribute($attributeid)) {
			return $this->attribute_values[$attributeid];
		} else {
			if($this->check_attribute_role($attributeid,'allow_null')) {
				return $attribute->get_default_value($this->categoryids);
			} else {
				return;
			}
		}
	}


	 ##############################################
	# Sets the value of an attribute in this record
	function set_attribute_value($attributeid, &$value) {
		$attribute = &$this->get_attribute($attributeid);
		$this->attribute_values[$attributeid] = $value;
		$this->save_attribute_value($attributeid);
		return $value;
	}


	 ##########################################################
	# Saves a freshly updated attribute value to the database
	function save_attribute_value($attributeid) {
		$attribute = &$this->get_attribute($attributeid);
		if(!$attribute->id) return;
		if(!isset($this->attribute_values[$attributeid])) {
			$attribute->clear_record_value($this->id);
		} elseif($attribute->plays_role('allow_null')
			&& $role = &$attribute->get_role_handler('allow_null')
			&& $role->get_value('auto')
			&& $attribute->compare_values($this->attribute_values[$attributeid], $attribute->get_default_value($this->categoryids)) == 0) {
			unset($this->attribute_values[$attributeid]);
			$attribute->clear_record_value($this->id);
		} else {
			$attribute->save_record_value($this->id,$this->attribute_values[$attributeid], 1);
		}
		$this->clear_cache();
	}


	 ###########################################################
	# Returns the formatted version of a value for an attirbute
	function &get_attribute_formatted_value($attributeid,$format,$ignore_cache=false) {
		$attribute = &$this->get_attribute($attributeid);
		if(!$attribute->id) return;

		# Check with this temp array cache to see if we already worked out the formatted value and we can just use that again
		$cache_format = $format;
		if (!$cache_format) {
			$cache_format = '--no_format_cache_format--';
		}
		if (isset($this->temp['context_val_cache'][$attribute->id][$cache_format]) && !$ignore_cache) {
			# We have a cached value so we'll use that
			$returning_value = $this->temp['context_val_cache'][$attribute->id][$cache_format];
		} else {
			# No cached value so lets work it out
			if($format) {
				$returning_value = $attribute->formatted_value($this->get_attribute_value($attributeid),$format);
			} else {
				$returning_value = $attribute->formatted_value($this->get_attribute_value($attributeid));
			}
			# Save the worked out value to the cache
			$this->temp['context_val_cache'][$attribute->id][$cache_format] = $returning_value;
		}
		return $returning_value;
	}


	 ###############################################################
	# Returns true or false whether there is a special value change
	# for a particular variety
	function has_raw_variety_value($attributeid, $variety_setid, $coord) {
		return ( isset($this->variations[$attributeid])
			  && isset($this->variations[$attributeid][$variety_setid])
			  && isset($this->variations[$attributeid][$variety_setid][$coord])
			);
	}
	
	 ######################################################
	# Simply returns a reference to the raw value of an
	# variation of an attribute for this record
	function &get_raw_variety_value($attributeid, $variety_setid, $coord) {
		if($this->has_raw_variety_value($attributeid, $variety_setid, $coord)) {
			return $this->variations[$attributeid][$variety_setid][$coord];
		}
		return;
	}

	
	 ######################################################
	# Simply returns a reference to the raw type of an
	# variation of an attribute for this record
	function &get_raw_variety_type($attributeid, $variety_setid, $coord) {
		if($this->has_raw_variety_value($attributeid, $variety_setid, $coord)) {
			return $this->variation_types[$attributeid][$variety_setid][$coord];
		}
		return;
	}
	

	 ######################################################
	# Returns a vaired value for the attribute based on 
	# a selected number of variety coordinates.
	# What we're going to do is reverse through the variations
	# combining each one over the one's "before" it.. if we come
	# to one that has a "replace"-like type, i.e., one htat doesnt
	# even use the value that its varying, we don't bother to 
	# calculate the rest.
	function get_variety_value($attributeid, $varieties) {
		end($varieties);
		end($varieties[key($varieties)]);
		return $this->_rec_get_variety_value($attributeid, &$varieties);
		$v = $this->get_attribute_value($attributeid);
	}
	function _rec_get_variety_value($attributeid, &$varieties) {
		if(!$variety_setid = key($varieties)) # We've come to the end (or beginning).
			return $this->get_attribute_value($attributeid);
		$coords = &$varieties[$variety_setid];
		if(!$coord = current($coords)) {
			prev($varieties);
			if(!$variety_setid = key($varieties)) # We've come to the end (or beginning).
				return $this->get_attribute_value($attributeid);
			$coords = &$varieties[$variety_setid];
			if(!$coord = end($coords)) return $this->get_attribute_value($attributeid);
		}
		if($this->has_raw_variety_value($attributeid, $variety_setid, $coord)) {
			$new_v = &$this->get_raw_variety_value($attributeid, $variety_setid, $coord);
			$new_t = &$this->get_raw_variety_type($attributeid, $variety_setid, $coord);
			$attribute = &$this->get_attribute($attributeid);
			$type_handler = &$attribute->get_type_handler();
			if($type_handler->variation_type_requires_original($new_t)) {
				prev($coords);
				$old_v = $this->_rec_get_variety_value($attributeid, $varieties);
				return $type_handler->vary_value($new_t, $new_v, $old_v);
			} else {
				return $type_handler->vary_value($new_t, $new_v);
			}
		} else {
			prev($coords);
			return $this->_rec_get_variety_value($attributeid, $varieties);
		}
	}	

	 #################################################
	# As above, but returns the value formatted
	function get_variety_formatted_value($attributeid, $varieties) {
		$attribute = &$this->get_attribute($attributeid);
		return $attribute->formatted_value($this->get_variety_value($attributeid, $varieties));
	}


	 #####################################################
	# Checks that an attribute plays a particular roles
	function check_attribute_role($attributeid,$role) {
		return isset($this->roles[$role]) && in_array($attributeid,$this->roles[$role]);
	}
	
	 ###########################################################
	# Returns true if this attribute is null for this record
	function null_attribute($attributeid) {
		return (!isset($this->attribute_values[$attributeid]));
	}

	 ################################################
	# Returns the value of whatever attribute plays
	# the role of the "name" of the record
	function name($refresh=false) {

		if($refresh || !isset($this->temp['name'])) {
			$r = &$this->temp['name'];
			$r = '';
			if(is_array($this->roles['name']) || count($this->roles['name'])){
				$tree = &$this->get_category_tree();
				$tree->sort_attributeids($this->roles['name']);
				$i = 0;
				foreach($this->roles['name'] as $attributeid) {
					$attribute = &$this->get_attribute($attributeid);
					$info = &$attribute->get_role_info('name');
					$r .= $info['pre'].$this->get_attribute_formatted_value($attributeid,$info['format']).$info['suf'];
				}
			}
			if(!$r) $r = $this->id;
		}
		return $this->temp['name'];
	}
	

	 ######################################
	# Deletes this record from existance
	function delete() {

		$db   = &$this->get_db();
		$e    = &$this->get_notitia_system();
		$tree = &$this->get_category_tree();

		 ##################################################################
		# Okay, first delete any variety sets that are associatied with us
		$variety_setids = &$db->single_column("SELECT variety_setid FROM $e->table_name"."_variety_set WHERE recordid='$this->id'");
		foreach($variety_setids as $variety_setid) {
			$variety_set = &$this->get_variety_set($variety_setid);
			$message .= $variety_set->delete()."\n";
		}

		 #######################################################
		# Now delete any attributes that are associated with us
		foreach(array_keys($this->local_attributeids) as $attributeid) {
			$attribute = &$this->get_attribute($attributeid);
			$message .= $attribute->delete()."\n";
		}

		 ################################################################
		# Now for all the other attributes we share with other record
		# have them disaccociate themselves with us
		foreach(array_keys($tree->get_context_attributes(0,$this->id)) as $attributeid) {
			$attribute = &$this->get_attribute($attributeid);
			if($attribute->id) {
				$attribute->disassociate_with_record($this->id);
			}
		}

		 ####################################
		# Remove associations with categories
		$db->delete("DELETE FROM $this->table_name"."_to_category WHERE recordid='$this->id'");

		 ###################
		# Delete our record
		$db->delete("DELETE FROM $this->table_name WHERE recordid='$this->id'");
		$this->clear_cache();

		# Find all the foreign keys
		$foreign_key_attributeids = $db->single_column("SELECT attributeid FROM xtra_web_extension_notitia_attribute WHERE type ='foreign_key'");
		# Delete any foreign key value which is pointing to this record
		if (!empty($foreign_key_attributeids)) {
			$foreign_key_attributeids = implode(',', $foreign_key_attributeids);
			$tables = array('xtra_web_extension_notitia_attribute_value', 'xtra_web_extension_notitia_attribute_default');
			$indexs = array('recordid', 'categoryid');
			foreach($tables as $table) {
				$index = current($indexs);
				next($indexs);
				$value_changes = $db->associative_array2("SELECT $index, attributeid, REPLACE(value, '|{$this->id}|', '|') as value FROM $table WHERE attributeid IN ($foreign_key_attributeids) AND value LIKE '%|{$this->id}|%'");
				foreach($value_changes as $indexid => $changes) {
					foreach($changes as $attributeid => $value) {
						if (strlen(trim($value)) == 1) {
							# Must be just a | char and that's invalid
							$insert_value = '';
						} else {
							$insert_value = $value;
						}
						$db->replace("REPLACE INTO $table (value,attributeid,$index) VALUES ('$insert_value','$attributeid','$indexid')");
						if ($index == 'recordid') {
							$fk_record = &$this->get_record($indexid);
							if ($fk_record->id) {
								$fk_record->clear_cache();
							}
						} else {
							$fk_attribute = &$this->get_attribute($attributeid);
							if ($fk_attribute->id) {
								$fk_attribute->clear_cache();
							}
						}
					}
				}
			}
		}

		$message .= "Record '".$this->name()."' (ID: $this->id) deleted.";
		$e->forget_record($this->id);
		$this->id = '';
		return trim($message);
	}


	 ########################
	# Duplicate the record
	function dupe($categoryid = 0) {
		if(!$categoryid) $categoryid = $this->id;
		$category = &$this->get_category($categoryid);
		$values = $this->attribute_values;
		for(reset($values); $attributeid = key($values); next($values)) {
			$attribute = &$this->get_attribute($attributeid);
			if($attribute->plays_role('auto_increment')) {
				unset($values[$attributeid]);
			}
		}
		if($recordid = $category->create_record($values)) {
			$record = &$this->get_record($recordid);
			$record->variations = $this->variations;
			$record->variation_types = $this->variation_types;
			$vs = &$record->variations;
			for(reset($vs); $attributeid = key($vs); next($vs)) {
				$attribute = &$this->get_attribute($attributeid);
				$vss = &$vs[$attributeid];
				for(reset($vss); $variety_setid = key($vss); next($vss)) {
					$coords = &$vss[$variety_setid];
					for(reset($coords); $coord = key($coords); next($coords)) {
						$value = &$coords[$coord];
						$type  = &$record->variation_types[$attributeid][$variety_setid][$coord];
						$attribute->save_variation_value($attribute, $variety_setid, $coord, $type, $value);
					}
				}
			}
			$record->clear_cache();
			return $record->id;
		}
		return;
	}


	#####################################################################

	 #############################################################
	# Does the current user have permission to view this record?
	function view_access() {
		$e = &$this->get_notitia_system();
		if($e->superuser()) return true;
		$tree = &$this->get_category_tree();
		foreach($this->categoryids as $categoryid) {
			if ($tree->check_permission($categoryid,"MV")) return true;
		}
		return false;
	}
	
	 #############################################################
	# Does the current user have permission to edit this record?
	function edit_access() {
		$e = &$this->get_notitia_system();
		if($e->superuser()) return true;
		$tree = &$this->get_category_tree();
		foreach($this->categoryids as $categoryid) {
			if ($tree->check_permission($categoryid,"MP")) return true;
		}
		return false;
	}

	 #################################################
	# Does the current user have permission to delete
	# this record?
	function delete_access() {
		$e    = &$this->get_notitia_system();
		if($e->superuser()) return true;
		$tree = &$this->get_category_tree();
		foreach($this->categoryids as $catgeoryid) {
			if(!$tree->check_permission($categoryid,"MR")) return false;
		}
		return true;
	}
	
	#####################################################################

	 ########################################
	# Gets a references to the parameter set
	function &get_pset() {
		$pset = &$this->temp[parameter_set];
		if(get_class($pset) !== "parameter_set") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/parameter_set.inc");
			$e = &$this->get_notitia_system();
			$pset = new Parameter_Set(get_class($this),"$e->xtra_path/record.pset",$this->parameters, $this);
		}
		return $pset;
	}

	 ####################################################
	# Returns the href for the backend of this record
	function get_backend_href() {
		$e = &$this->get_notitia_system();
		$edit_categoryid = $_GET['edit_categoryid'];
		return $e->get_backend_href()."&edit_categoryid=$edit_categoryid&edit_recordid=$this->id";
	}

	 ####################
	# Prints the backend
	function print_backend() {
		$session = &get_mysource_session();
		
		$e       = &$this->get_notitia_system();

		$backend = &$e->setup_backend();
		$backend->set_tab($this->table_name,$this->get_backend_href(),'Record: '.$this->name(),'Edit this record.','page');
		$backend->set_active_tab($this->table_name);

		$backend->set_heading("Edit Record - $backend->heading",'page');
		$backend->set_name($this->name());
		$backend->set_id_string($this->id);
	
		 ###################
		# Check permissions
		if(!$this->view_access()) {
			$session->login_screen($backend->title,"You must be logged in and have permission to edit this record in order to proceed.");
		}


		$pset = &$this->get_pset($this->id);

		$pset->set_tabs($backend,get_class($this),$this->get_backend_href());

		# But..
		$edit_attributeid = $_GET['edit_attributeid'];
		if ($edit_attributeid) {
			$attribute = &$this->get_attribute($edit_attributeid);
			$attribute->print_backend();
			return;
		}

		# Deletin'
		if($this->delete_access()) {
			$delete_record = $_GET['delete_record'];
			if($delete_record) {
				$edit_categoryid = $_GET['edit_categoryid'];
				$backend->add_message($this->delete());
				if(!$this->id) {
					$session->unset_var("active_".get_class($this));
					$backend->set_relocation($e->get_backend_href()."&edit_categoryid=$edit_categoryid"."&PARAMETER_SCREEN=Associated+Records");
					$backend->print_header();
					return;
				}
			}
			$backend->set_toolbar_button("delete","javascript:if(confirm('Are you sure you want to delete this record?')){location='".$this->get_backend_href()."&delete_record=1';}","Delete This Record","delete");
		}

		if($pset->process($backend)) {
			#$backend->add_message($this->save());
		}

		$pset->print_backend($backend);
		
	}


	 ##############################################################
	# Prints a summary of hte categories this record is linked to
	function print_category_associations() {
		$tree = &$this->get_category_tree();
		$ancestorids = array_merge($this->categoryids,$tree->bulk_ancestorids($this->categoryids));
		$this->_print_category_associations_rec(0,$ancestorids);
		echo("Uncheck any boxes to disassociate this record with that category.");
	}
	function _print_category_associations_rec($categoryid,&$ancestorids) {
		$tree = &$this->get_category_tree();
		$e    = &$this->get_notitia_system();
		if(!count($ids = &$tree->childids($categoryid))) return;
		echo("<ul>");
		foreach($tree->childids($categoryid) as $id) {
			if(!in_array($id,$ancestorids)) continue;
			$name = "<a href=\"".$e->get_backend_href()."&edit_categoryid=$id\">".$tree->name($id)."</a>";
			if(in_array($id,$this->categoryids))  {
				if($tree->check_permission($id,"MR")) {
					$box = "<input type=checkbox name=associated_categoryids[] value=$id checked>";
				} else {
					$box = "&nbsp;<b>X</b>&nbsp;";
				}
				echo("<li>$box<b>$name</b></li>");
			} else {
				echo("<li>$name</li>");
			}
			$this->_print_category_associations_rec($id,$ancestorids);
		}
		echo("</ul>");
	}


	 ##############################################################
	# process a summary of hte categories this record is linked to
	function process_category_associations() {
		$associated_categoryids = $_POST['associated_categoryids'];
		# Now this array should have the same elements in it
		# as $this->categoryids; if it doesn't, it means the user
		# has disassociated the record with certain categories.
		# But it may also mean the user doesn't have permission to 
		# remove records from these categories anyway, so we have
		# to be careful with permissions.
		$tree = &$this->get_category_tree();
		foreach($this->categoryids as $categoryid) {
			if(in_array($categoryid,$associated_categoryids)) continue;
			# Gasp! Remove!?
			if(!$tree->check_permission($categoryid,'MR')) continue;
			if(count($this->categoryids) < 2) return "A record must be associated with at least one category.";
			$category = &$this->get_category($categoryid);
			$m = $category->disassociate_with_record($this->id);
			if($m) $ms [] = $m;
		}

		return implode("\n",$ms);
	}


	 ######################################################
	# Prints a list of the inherited attribues in a context
	function print_inherited_variety_set_summary() {
		$e = &$this->get_notitia_system();
		$e->print_inherited_variety_set_summary(0,$this->id);
		return;
	}

	 ########################################################
	# Prints an editable summary of the variety sets defined
	# in a particular context
	function print_variety_set_summary() {
		$e = &$this->get_notitia_system();
		$e->print_variety_set_summary(0,$this->id);
		return;
	}


	 ############################################################
	# Processes an editable summary of the variety sets defined
	# in a particular context
	function process_variety_set_summary() {
		$e = &$this->get_notitia_system();
		return $e->process_variety_set_summary(0,$this->id);
	}


	 ###################################################
	# ALlows the user to create local variety sets
	function process_new_variety_set() {
		$option_attributeids = $this->get_value("new_variety_set_option_attributeids");
		$varied_attributeids = $this->get_value("new_variety_set_varied_attributeids");
		unset($this->parameters["new_variety_set_option_attributeids"]);
		unset($this->parameters["new_variety_set_varied_attributeids"]);
		$e = &$this->get_notitia_system();
		if($variety_setid = $e->create_variety_set(0,$this->id,$option_attributeids,$varied_attributeids)) {
			$variety_set = &$this->get_variety_set($variety_setid);
			return "New variety set '".$variety_set->name()."' created.";
		}
	}


	 ##############################################################
	# Prints the list of attributes for this record, these are 
	# treated as the default values that varietie ss can override
	function print_attribute_form() {
		$backend     = &$this->get_backend();
		$tree        = &$this->get_category_tree();
		$db          = &$this->get_db();
		$e           = &$this->get_notitia_system();
		$edit_access = $this->edit_access();
	
		$current_catid = 0;
		$attributeids = $tree->get_context_attributes(0,$this->id);

		$attribute_variety_setids = &$this->get_attribute_variety_setids();

		foreach($attributeids as $attributeid => $name) {
			$attribute = &$this->get_attribute($attributeid);
			$categoryid = $attribute->categoryid;
			if($categoryid != $current_catid) {
				$backend->open_field("----------");
				echo("<b>Context</b>: ");
				if(isset($this->local_attributeids[$attributeid])) {
					echo("(this record only) ");
				}
				$lineage = $tree->ancestorids($categoryid);
				$lineage = array_reverse($lineage);
				$lineage[] = $categoryid;
				foreach($lineage as $ancestorid) {
					echo(" > <a href=\"".$e->get_backend_href()."&edit_categoryid=$ancestorid\">".$tree->name($ancestorid)."</a>");
				}
				$current_catid = $categoryid;
			}

			$prefix = "p_$this->id"."_a_$attributeid"."_";

			if($tree->check_permission($categoryid,"ME")) {
				$attribute_label = "$attribute->name<a href=\"".$e->get_backend_href()."&edit_categoryid=$categoryid&edit_attributeid=$attributeid\">*</a>";
			} else {
				$attribute_label = $attribute->name;
			}
			$backend->open_field($attribute_label,"top");
		
			if(isset($attribute_variety_setids[$attributeid])) {
				$variety_setids = &$attribute_variety_setids[$attributeid];
			} else {
				$variety_setids = array();
			}

			$variation_types = &$attribute->get_type_setting("variation_types");

			if($edit_access && !$this->check_attribute_role($attributeid,"force_null")) {
				echo("<input type=hidden name=editable_attributeids[$this->id][$attributeid][def] value=1>");
				if($this->check_attribute_role($attributeid,'allow_null')) {
					$name = "null_$prefix";
					$null = $this->null_attribute($attributeid);
					echo("[<input type=checkbox name=\"$name\" value=\"1\" ".(($null)?"checked":"").">");
					echo("Use the attribute default.]<br>");
				} else {
					$null = false;
				}

				if($null) {
					$attribute->paint($this->get_attribute_value($attributeid));
				} else {
					if(count($variety_setids) > 0) {
						echo("<table cellpadding=2 cellspacing=0 border=0><tr>");
						echo("<tr><td class=smallprint><b>[Default]:</b></td><td>&nbsp;</td></tr>");
						echo("<tr><td colspan=2><table cellpadding=5 border=0><tr><td>");
						$attribute->print_field($prefix, $this->get_attribute_value($attributeid));
						echo("</td></tr></table></td></tr>");
						foreach($variety_setids as $variety_setid) {
							$variety_set = &$this->get_variety_set($variety_setid);
							foreach($variety_set->get_variety_names(0,$this->id) as $coord => $name) {
								$type = $this->variation_types[$attribute->id][$variety_setid][$coord];
								echo("<tr><td class=smallprint><b>$name</b></td><td>".combo_box($prefix."_vtype_$variety_setid"."_$coord",array_merge(array(""=>"[Unchanged/Default]"),$variation_types),$type,"class=smallprint")."</td></tr>");
								if($type) {
									echo("<tr><td colspan=2><table cellpadding=5 border=0><tr><td>");
									echo("<input type=hidden name=editable_attributeids[$this->id][$attributeid][$variety_setid][] value=\"$coord\">");
									$attribute->print_field($prefix."_v_$variety_setid"."_$coord", $this->variations[$attributeid][$variety_setid][$coord]);
									echo("</td></tr></table></td></tr>");
								}
							}
						}
						echo("</table>");
					} else {
						$attribute->print_field($prefix, $this->get_attribute_value($attributeid));
					}
				}
			} else {
				if(count($variety_setids) > 0) {
					echo("<table cellpadding=2 cellspacing=0 border=0><tr>");
					echo("<tr><td class=smallprint><b>[Default]:</b></td><td>&nbsp;</td></tr>");
					echo("<tr><td colspan=2><table cellpadding=5 border=0><tr><td>");
					$attribute->paint($this->get_attribute_value($attributeid));
					echo("</td></tr></table></td></tr>");
					foreach($variety_setids as $variety_setid) {
						$variety_set = &$this->get_variety_set($variety_setid);
						foreach($variety_set->get_variety_names(0,$this->id) as $coord => $name) {
							$type = $variation_types[$this->variation_types[$attributeid][$variety_setid][$coord]];
							if(!$type) $type = "Unchanged";
							echo("<tr><td class=smallprint><b>$name</b></td><td class=smallprint>($type)</td></tr>");
							if($type != "Unchanged") {
								echo("<tr><td colspan=2><table cellpadding=5 border=0><tr><td>");
								$attribute->paint($this->variations[$attributeid][$variety_setid][$coord]);
								echo("</td></tr></table></td></tr>");
							}
						}
					}
					echo("</table>");
				} else {
					$attribute->paint($this->get_attribute_value($attributeid));
				}
			}
		}

	}

	 ##############################################################
	# Processes the list of attributes for this record, these are 
	# treated as the default values that varietie ss can override
	function process_attribute_form() {
		$editable_attributeids = $_POST['editable_attributeids'];
		$ms = array();
		$attribute_variety_setids = $this->get_attribute_variety_setids();
		foreach($editable_attributeids[$this->id] as $attributeid => $coords) {
			$prefix = "p_$this->id"."_a_$attributeid"."_";
			$attribute = &$this->get_attribute($attributeid);
			$variation_types = $attribute->get_type_setting("variation_types");
			$null = $_POST['null_'.$prefix];
			if($this->null_attribute($attributeid)) {
				if(!$null) {
					$this->attribute_values[$attributeid] = $attribute->get_default_value($this->categoryids);
					$attribute->save_record_value($this->id, $this->attribute_values[$attributeid], 1);
					$ms[] = "Attribute '$attribute->name' no longer using the attribute default.";
				}
			} else {
				# *gasp* They're trying to make this attribute null
				if($null && $this->check_attribute_role($attributeid,"allow_null")) { 
					unset($this->attribute_values[$attributeid]);
					unset($this->variations[$attributeid]);
					unset($this->variation_types[$attributeid]);
					$attribute->clear_record_value($this->id);
					$ms[] = "Attribute '$attribute->name' now using the attribute default.";
				} else {
					$m = $attribute->process_field($prefix, $this->attribute_values[$attributeid]);
					$attribute->save_record_value($this->id, $this->attribute_values[$attributeid], 1);
					if($m) $ms[] = $m;
					# Process the varieties if any
					if(isset($attribute_variety_setids[$attributeid]) && count($attribute_variety_setids[$attributeid])) {
						foreach($attribute_variety_setids[$attributeid] as $variety_setid) {
							$variety_set = &$this->get_variety_set($variety_setid);
							foreach($variety_set->get_variety_names(0,$this->id) as $coord => $name) {
								$type = &$this->variation_types[$attributeid][$variety_setid][$coord];
								$new_type = $_POST[$prefix.'_vtype_'.$variety_setid.'_'.$coord];
								if($type) {
									# This attribute has been varied here
									if(!$new_type) {
										$attribute->clear_variation_value($this->id, $variety_setid, $coord);
										$type = $new_type;
										unset($this->variations[$attributeid][$variety_setid][$coord]);
										$ms[] = "Attribute '$attribute->name' is no longer varied for the variety '$name'.";
									} else {
										if($new_type != $type) {
											$type = $new_type;
											$attribute->change_variation_type($this->id, $variety_setid, $coord, $type);
											$ms[] = "Attribute '$attribute->name' is now has a variation type of '{$variation_types[$type]}' for the variety '$name'.";
										}
										$m = $attribute->process_field($prefix."_v_$variety_setid"."_$coord", $this->variations[$attributeid][$variety_setid][$coord]);
										$attribute->save_variation_value($this->id, $variety_setid, $coord, $new_type, $this->variations[$attributeid][$variety_setid][$coord]);
										if($m) $ms[] = $m;
									}
								} else {
									# This attribute is being left unchanged for this variety
									if($new_type) {
										$value = "";
										$attribute->save_variation_value($this->id, $variety_setid, $coord, $new_type, $value);
										$type = $new_type;
										$this->variations[$attributeid][$variety_setid][$coord] = $value;
										$ms[] = "Attribute '$attribute->name' variety '$name' variation type changed to '{$variation_types[$type]}'";
									}
								}
							}
						}
					}
				}
			}
		}
		$this->clear_cache();
		return implode("\n",$ms);
	}


	 #######################################################
	# Prints a backend interface for searching the category
	function print_backend_record_search() {
		if(isset($this->temp['backend_search_categoryid'])) {
			$categoryid = $this->temp['backend_search_categoryid'];
			$category = &$this->get_category($this->temp['backend_search_categoryid']);
			if($category->id) {
				echo js('location = "'.$category->get_backend_href().'&PARAMETER_SCREEN=Associated+Records";');
			}
			unset($this->temp['backend_search_categoryid']);
			return;
		}
		$tree = &$this->get_category_tree();
		foreach($this->categoryids as $categoryid) {
			$category = &$this->get_category($categoryid);
			echo("<b>Context</b>: ");
			$lineage = $tree->ancestorids($categoryid);
			$lineage = array_reverse($lineage);
			$lineage[] = $categoryid;
			foreach($lineage as $ancestorid) {
				$ancestor = &$this->get_category($ancestorid);
				echo(" > <a href=\"".$ancestor->get_backend_href()."\">".$tree->name($ancestorid)."</a>");
			}
			$category->print_backend_record_search(array());
		}
	}


	 ##########################################################
	# Processes a backend interface for searching the category
	function process_backend_record_search() {
		$tree = &$this->get_category_tree();
		foreach($this->categoryids as $categoryid) {
			$category = &$this->get_category($categoryid);
			$category->process_backend_record_search();
			if(count($category->get_value('backend_search_queries'))) {
				$category->save();
				$this->temp['backend_search_categoryid'] = $categoryid;
				return 1;
			}
		}
	}

	 ################################################
	# Prints a summary of the inherited attributes 
	# for the category.
	function print_inherited_attribute_summary() {
		$e = &$this->get_notitia_system();
		$e->print_inherited_attribute_summary(0,$this->id);
	}
	
	 ################################################
	# Prints an interface for reordering attributes
	function print_attribute_summary() {
		$e    = &$this->get_notitia_system();
		$tree = &$this->get_category_tree();
		$e->print_attribute_summary(0,$this->id);
		foreach($this->local_attributeids as $attributeid => $categoryid) {
			if(!$categoryid) continue;
			if(!in_array($categoryid,$used_categoryids)) $used_categoryids[] = $categoryid;
		}
		foreach($used_categoryids as $categoryid) {
			echo("<hr noshade size=0>");
			$str  = "> <a href=\"".$e->get_backend_href()."&edit_categoryid=$categoryid\">"
				  . $tree->name($categoryid)."</a>";
			foreach($tree->ancestorids($categoryid) as $ancestorid) {
				$str  = "> <a href=\"".$e->get_backend_href()."&edit_categoryid=$ancestorid\">"
					  . $tree->name($ancestorid)."</a> $str";
			}
			echo "<b>Context:</b> $str<br>";
			$e->print_attribute_summary($categoryid,$this->id);
		}
	}
	
	 #####################################
	# Saves changes to attribute ordering
	function process_attribute_summary() {
		$e = &$this->get_notitia_system();
		$m = $e->process_attribute_summary(0,$this->id);
		if($m) $ms[] = trim($m);
		foreach($this->local_attributeids as $attributeid => $categoryid) {
			if(!$categoryid) continue;
			if(!in_array($categoryid,$used_categoryids)) $used_categoryids[] = $categoryid;
		}
		foreach($used_categoryids as $categoryid) {
			$m = $e->process_attribute_summary($categoryid,$this->id);
			if($m) $ms[] = trim($m);
		}
		return implode("\n",$ms);
	}

	 ##############################################
	# Processes input for creating a new attribute
	function process_new_attribute() {
		$name       = $this->get_value("new_attribute_name");
		$type       = $this->get_value("new_attribute_type");
		$categoryid = $this->get_value("new_attribute_categoryid");
		unset($this->parameters["new_attribute_name"]);
		unset($this->parameters["new_attribute_type"]);
		unset($this->parameters["new_attribute_categoryid"]);
		if(!$name) return;
		$e = &$this->get_notitia_system();
		if($attributeid = $e->create_attribute($type,$name,$categoryid,$this->id)) {
			$attribute = &$this->get_attribute($attributeid);
			return "New attribute '$attribute->name' created.";
		}
	}

	 #############################################################
	# Given an attributeid and a prefix, prints a field for that
	# attribute
	function print_field($attributeid, $prefix,$format) {
		$attribute = &$this->get_attribute($attributeid);
		$attribute->print_field($prefix,$this->get_attribute_value($attributeid),$format);
	}

	 #############################################################
	# Given an attributeid and a prefix, processes that attribute
	# and keeps the values updated.
	function process_field($attributeid, $prefix,$format) {
		$attribute = &$this->get_attribute($attributeid);
		if(!$attribute->id) return;
		$message = $attribute->process_field($prefix,$this->attribute_values[$attributeid],$format);
		if($message) $message = $this->name().": Default $message (ID: $this->id)";
		$this->save_attribute_value($attributeid);
		return $message;
	}

	 #############################################################
	# Given an attributeid, a variety set id, and a variety coord
	# and a prefix, processes that attribute and keeps the values updated.
	function process_variety_field($attributeid, $variety_setid, $coord, $prefix) {
		$attribute = &$this->get_attribute($attributeid);
		$message = $attribute->process_field($prefix,$this->variations[$attributeid][$variety_setid][$coord]);
		$variety_set   = &$this->get_variety_set($variety_setid);
		$variety_names = &$variety_set->get_variety_names();
		if($message) $message = $this->name().": {$variety_names[$coord]} $message (ID: $this->id)";
		$attribute->save_variation_value($this->id, $variety_setid, $coord, $this->variation_types[$attributeid][$variety_setid][$coord], $this->variations[$attributeid][$variety_setid][$coord]);
		$this->clear_cache();
		return $message;
	}

	/**
	* Clears the record cache but also wipes the temp array. I do this because usually when one is clearing a record cache they have just changed values in the record attributes and since I store some temp attribute formatted values in the temp array I want to wipe them too.
	*
	* @access private
	* @return boolean
	*/
	function clear_cache($id) {
		$this->temp = array();
		return parent::clear_cache($id);
	}
}
?>
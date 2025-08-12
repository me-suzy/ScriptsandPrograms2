<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/category_tree.inc,v $
## $Revision: 1.19 $
## $Author: dofford $
## $Date: 2004/04/05 11:38:03 $
#######################################################################

#---------------------------------------------------------------------#

 ##############################################################
# Loads the category tree structure into an array. If a site
# id is specified it loads only categories in that site. Other
# wise it loads all categories in the system, regardless of 
# siteid.
#
# This object also acts as a sort of brain behind the complex
# links between records categories and attributes, permissions
# etc etc.
class Web_Extension_Notitia_Category_Tree extends Web_Extension_Notitia_Object {

	# The site id, may be 0 for entire system
	var $id;

	var $table_name;

	# The, er, tree
	var $tree;

	# Quick attribute lookup information
	var $attributes;
	
	# Temporary runtime data
	var $temp;

	 #############
	# Constructor
	function Web_Extension_Notitia_Category_Tree($siteid) {
		Web_Extension_Notitia_Object::Web_Extension_Notitia_Object();
		$this->load($siteid);
	}

	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = Web_Extension_Notitia_Object::__sleep();
		array_remove_element("temp",$result);
		array_remove_element("table_name",$result);
		return $result;
	}

	 ##############################################
	# Wake up with this handy information EVERY day !
	function __wakeup() {
		Web_Extension_Notitia_Object::__wakeup();
		$this->table_name = "xtra_".ereg_replace("object$","category",get_parent_class($this));
	}

	 ################################################################
	# Loads all the relevant information about this record from the
	# database or the cache
	function load($siteid,$force) {

		$this->id = '0';

		 #################
		# Check the cache
		if (!$force && $this->load_from_cache($this->id)) {
			return count($this->tree);
		}

		# Load everything from the database;
		$db = &$this->get_db();

		$this->tree = $db->associative_array("SELECT categoryid, parentid, siteid, name, description FROM $this->table_name ORDER BY order_no");

		$this->tree[0] = array();

		for(reset($this->tree); NULL !== ($categoryid = key($this->tree)); next($this->tree)) {
			$this->tree[$this->tree[$categoryid]['parentid']]['childids'][] = $categoryid;
		}

		# Attribute information
		$attribute_table_name = substr($this->table_name,0,-strlen('category')).'attribute';
		$this->attributes = $db->associative_array("SELECT attributeid, categoryid, recordid, name, order_no FROM $attribute_table_name ORDER BY order_no");

		for(reset($this->attributes); $attributeid = key($this->attributes); $attributeid = next($this->attributes)) {
			$this->tree[$this->attributes[$attributeid]['categoryid']]['attributes'][$this->attributes[$attributeid]['recordid']][] = $attributeid;
		}

		 ###################
		# Save to the cache 
		$this->save_to_cache($this->id);

		return count($tree);
	}

	 ########################################################################
	# Refreshes the tree if changes have been made to the category structure
	function refresh() {
		return $this->load($this->id,1);
	}

	 ##########
	# Quickies
	function &record($categoryid) {
		return $this->tree[$categoryid];
	}
	function &parentid($categoryid) {
		return $this->tree[$categoryid]['parentid'];
	}
	function &siteid($categoryid) {
		return $this->tree[$categoryid]['siteid'];
	}
	function &name($categoryid) {
		return $this->tree[$categoryid]['name'];
	}
	function &description($categoryid) {
		return $this->tree[$categoryid]['description'];
	}

	/**
	* Returns a reference to the array of childids in category tree
	*
	* @param   int $categoryid	The category of which to find the children
	* @access  public
	* @returns array
	*/
	function &childids($categoryid) {
		if(is_array($this->tree[$categoryid]['childids'])) {
			return $this->tree[$categoryid]['childids'];
		} else {
			$empty = array();
			return $empty;
		}
	}

	function child_list($categoryid) {
		$r = array();
		foreach($this->tree[$categoryid]['childids'] as $id)
			$r[$id] = $this->name($id);
		return $r;
	}
	function exists($categoryid) {
		return isset($this->tree[$categoryid]);
	}


	 ##############################################
	# Returns an array of ids representing all the 
	# grandparents of a particular categoryid
	function ancestorids($categoryid) {
		$r = array();
		$categoryid = abs((int)$categoryid);
		while(($categoryid = $this->tree[$categoryid]['parentid']) > 0) {
			$r[] = $categoryid;
		}
		return $r;
	}


	 ##################################################################################
	# As above, but with accepts an array of childids, return an array of UNIQUE values
	function bulk_ancestorids($categoryids) {
		$r = array();
		foreach($categoryids as $categoryid) {
			$categoryid = abs((int)$categoryid);
			while(($categoryid = $this->tree[$categoryid]['parentid']) > 0) {
				if(!in_array($categoryid,$r)) $r[] = $categoryid;
			}
		}
		return $r;
	}

	 ##################################################################################
	# Returns an array of categoryids of all the categorys children or children of
	# chidren etc...
	function all_descendantids($categoryid) {
		if(!$categoryid) return array_keys($this->tree);
		if(!isset($this->tree[$categoryid]['childids'])) return array();
		$r = $this->tree[$categoryid]['childids'];
		if(!is_array($r)) $r = array();
		foreach($r as $childid) {
			if(isset($this->tree[$childid]['childids'])) {
				$r = array_merge($r, $this->all_descendantids($childid));
			}
		}
		return $r;
	}


	/**
	* Returns a number of all the decendant categories that the passed category has
	* chidren etc... should be more effiecient than just doing count(all_descendantids())
	*
	* @param   int $categoryid The category id
	* @access  public
	* @returns int
	*/
	function count_all_descendantids($categoryid) {
		if(!$categoryid) return count($this->tree);
		if(!isset($this->tree[$categoryid]['childids']) || !is_array($this->tree[$categoryid]['childids'])) return 0;
		$r = count($this->tree[$categoryid]['childids']);
		foreach($this->tree[$categoryid]['childids'] as $childid) {
			if(isset($this->tree[$childid]['childids'])) {
				$r += $this->count_all_descendantids($childid);
			}
		}
		return $r;
	}


	 #############################################################
	# Returns a list of all the ids at a certain depth, or depth
	# intervals.
	function get_descendantids_at_depth($categoryid=0,$depth=0,$at_intervals=0,$travelled=0) {
		if ($depth == 0) {
			$r = array($categoryid);
		} else {
			if($travelled % $depth == $depth - 1) {
				if($at_intervals) {
					$r = $this->childids($categoryid);
					foreach($r as $childid) {
						$r = array_merge($r,$this->get_descendantids_at_depth($childid,$depth,$at_intervals,$travelled+1));
					}
				} else {
					return $this->childids($categoryid);
				}
			} else {
				$r = array();
				$childids = $this->childids($categoryid);
				foreach($childids as $childid) {
					$r = array_merge($r,$this->get_descendantids_at_depth($childid,$depth,$at_intervals,$travelled+1));
				}
			}
		}
		return $r;
	}

	/*
	* Returns an array of categoryid -> name, with name preceded by tree sticks
	* 
	* @access public
	* @param int $categoryid - the root categoryid of the tree section
	* @param int $max_depth - m
	* @param array $ignore_cateogoryids - categoryids to skip
	* @param string $stix - the stix added used recursively
	* @returns array
	*/
	function get_category_list_with_sticks($categoryid, $max_depth = 1, $ignore_categoryids=array(),&$stix) {
		if(!$max_depth) return array();
		if(!$categoryid) $categoryid = 0;
		$childids = &$this->childids($categoryid);
		$result = array();
		if (!isset($stix)) {
			$reverse = true;
		}
		$end=end($childids);
		while (false !== ($childid = current($childids))) {
			if (in_array($childid, $ignore_categoryids)) {
				$end = prev($childids);
				continue;
			}
			if($max_depth > 1 && count($this->childids($childid))) {
				$newstix = $stix.(($end == $childid)?'&nbsp;&nbsp;':'|&nbsp;');
				$result = $result + $this->get_category_list_with_sticks($childid, $max_depth - 1, $ignore_categoryids, $newstix);
			}
			$result[$childid] = $stix.(($end == $childid)?'`-':'|-').$this->name($childid);
			prev($childids);
		}
		if ($reverse) {
			$result = array_reverse($result, true);
		}
		return $result;
	}

	 ##############################################################
	# Given an array of categoryids, sorts them into the correct
	# order, and removes any ids which do not exist.
	function sort_categoryids(&$categoryids) {
		if(!$categoryids || !is_array($categoryids) || !count($categoryids)) {
			$categoryids = array();
		}
		usort($categoryids,array($this,'universal_category_order_cmp'));
	}

	 ##############################################################
	# Given an array of attributeids, sorts them into the correct
	# order, and removes any ids which do not exist.
	function sort_attributeids(&$attributeids) {
		if(!$attributeids || !is_array($attributeids) || !count($attributeids)) {
			$attributeids = array();
		}
		usort($attributeids,array($this,'universal_attribute_order_cmp'));
	}

	/**
	* Returns which category is closer to the top of the notitia category tree.
	*
	* @param  int $cid2		Categoryid one
	* @param  int $cid2		Categoryid two
	* @access public
	* @return int
	*/
	function universal_category_order_cmp($cid1, $cid2) {
		if($cid1 == $cid2) return 0;
		if(!isset($this->tree[$cid1])) return -1;
		if(!isset($this->tree[$cid2])) return 1;
		while(1) {
			$cid1 = $this->tree[$cid1]['parentid'];
			$cid2 = $this->tree[$cid2]['parentid'];
			if ($cid1 == 0 && $cid2 == 0) {
				return 0;
			} elseif ($cid1 == 0) {
				return 1;
			} elseif ($cid2 == 0) {
				return -1;
			}
		}
	}

	/**
	* Returns reverse of universal_category_order_cmp.
	*
	* @param  int $cid2		Categoryid one
	* @param  int $cid2		Categoryid two
	* @access public
	* @return int
	*/
	function reverse_universal_category_order_cmp($cid1, $cid2) {
		return $this->universal_category_order_cmp($cid1, $cid2) * -1;
	}

	 ###########################################################
	# Returns 1, 0, or -1 depending whether the first attribute
	# is after, the same or before the second attributeid in the
	# global category/attribute/record order.
	function universal_attribute_order_cmp($attributeid1, $attributeid2) {
		if($attributeid1 == $attributeid2) return 0;
		$attribute1 = &$this->attributes[$attributeid1];
		$attribute2 = &$this->attributes[$attributeid2];
		if($attribute1['categoryid'] == $attribute2['categoryid']) {
			if($attribute1['recordid'] == $attribute2['recordid']) {
				if($attribute1['order_no'] > $attribute2['order_no']) return 1;
				if($attribute1['order_no'] < $attribute2['order_no']) return -1;
				return 0;
			} else {
				$record1 = &$this->get_record($attribute1->recordid);
				$record2 = &$this->get_record($attribute2->recordid);
				if(!$record1->id) return 1;
				if(!$record2->id) return -1;
				return strcmp($record1->name(), $record2->name());
			}
		} else {
			return $this->reverse_universal_category_order_cmp($attribute1['categoryid'], $attribute2['categoryid']);
		}
		return 0;
	}


	 ################################
	# Assumes permission is granted.
	# This is so hard. :(
	function move_category($child_catid,$parent_catid) {

		 ############################################
		# First check whether this is a feabsile idea.
		$descendantids = $this->all_descendantids($child_catid);
		if($this->parentid($child_catid) == $parent_catid) return; # Duh
		if($child_catid == $parent_catid) return;
		if(in_array($parent_catid,$descendantids)) {
			return "You cannot move a category into one of its own subcategories.";
		}

		 ####################################################
		# Some records are going to lose attributes, and we
		# can't leave mess lying around because of this
		$db = &$this->get_db();
		$n  = &$this->get_notitia_system();
		$old_ancestorids     = $this->ancestorids($child_catid);
		$new_ancestorids     = $this->ancestorids($parent_catid);
		$affected_recordids = $db->single_column("SELECT recordid FROM {$n->table_name}_record_to_category WHERE categoryid IN ('$child_catid','".implode("','",$descendantids)."')");

		# Disassociate the old attributes with the affected records
		$lost_ancestorids = array_diff($old_ancestorids,$new_ancestorids);
		if(count($lost_ancestorids) > 0) {
			$lost_attributeids = $db->single_column("SELECT attributeid FROM {$n->table_name}_attribute WHERE recordid=0 AND categoryid IN ('".implode("','",$lost_ancestorids)."')");
			foreach($lost_attributeids as $lost_attributeid) {
				$attribute = &$this->get_attribute($lost_attributeid);
				foreach($affected_recordids as $recordid) {
					$attribute->disassociate_with_record($recordid);
				}
			}
		}

		# Associate the old attributes with the affected records
		$gained_ancestorids  = array_diff($new_ancestorids,$old_ancestorids);
		if(count($gained_ancestorids) > 0) {
			$gained_attributeids = $db->single_column("SELECT attributeid FROM {$n->table_name}_attribute WHERE recordid=0 AND categoryid IN ('".implode("','",$gained_ancestorids)."')");
			foreach($gained_attributeids as $gained_attributeid) {
				$attribute = &$this->get_attribute($gained_attributeid);
				foreach($affected_recordids as $recordid) {
					$attribute->associate_with_record($recordid,$gained_ancestorids);
				}
			}
		}

		 ##############################################################
		# Alright now, first we're going to delete any affected caches
		# First, lets find all the affected records
		global $CACHE;
		foreach($affected_recordids as $recordid) {
			$CACHE->clear($recordid,get_class($n).'_record');
		}
		# And also the attributes
		$affected_attributeids = $db->single_column("SELECT attributeid FROM {$n->table_name}_attribute WHERE categoryid IN ('$child_catid','".implode("','",$descendantids)."')");
		foreach($affected_attributeids as $attributeid) {
			$CACHE->clear($attributeid,get_class($n)."_attribute");
		}

		# Avoid name clases
		$child_category = &$this->get_category($child_catid);
		$name = $child_category->name;
		$names = $this->child_list($parent_catid);
		while(in_array($name,$names)) {
			$name = increment_name($name, ' ');
		}
		if($name != $child_category->name) {
			$child_category->set_name($name);
		}


		 ###################################
		# Now we simply update the database
		$db->update("UPDATE $this->table_name SET parentid='$parent_catid' WHERE categoryid='$child_catid'");

		 ############################
		# Force a reload of the tree
		$this->refresh();

		 ####################################
		# Let the child know about the change
		$category = &$this->get_category($child_catid);
		$category->parentid = $parent_catid;
		$category->clear_cache();
		
		return $message.'Moved '.$this->name($child_catid).' category to under '.(($parent_catid  > 0)?$this->name($parent_catid):'Category Root').'.';
	}

	 ###################################################################
	# Saves access grants for a particular category into a table
	# Takes the data in the form of an array that would be used to
	# print a parameter in the backend
	function save_category_access_grants($categoryid = 0, $parameter) {
		$db = &$this->get_db();
		$current_entities = $db->single_column("SELECT CONCAT(entity_type,entityid) FROM $this->table_name"."_grant WHERE categoryid='$categoryid'");
		foreach($parameter as $g => $es) {
			foreach($es as $e) {
				$n[$e] .= $g;
			}
		}
		foreach($current_entities as $entity) {
			$type = $entity[0];
			$id   = (int) substr($entity,1);
			if(isset($n[$entity])) {
				$db->update("UPDATE $this->table_name"."_grant SET access_types='{$n[$entity]}' WHERE categoryid='$categoryid' AND entityid='$id' AND entity_type='$type'");
				unset($n[$entity]);
			} else {
				$db->delete("DELETE FROM $this->table_name"."_grant WHERE categoryid='$categoryid' AND entityid='$id' AND entity_type='$type'");
			}
		}
		foreach($n as $e => $gs) {
			$type = $e[0];
			$id   = (int) substr($e,1);
			$db->insert("INSERT INTO $this->table_name"."_grant (categoryid,entity_type,entityid,access_types) VALUES ('$categoryid','$type','$id','$gs')");
		}
	}

	 ########################################################
	# Loads the access grants for a category into an array
	# for use in backend displays.
	function load_category_access_grants($categoryid = 0) {
		if(!isset($this->temp['category_access_grants'])) {
			$db = &$this->get_db();
			$this->temp['category_access_grants'] = array();
			$grants = $db->associative_array("SELECT CONCAT(entity_type,entityid), access_types FROM $this->table_name"."_grant WHERE categoryid='$categoryid'");
			foreach($grants as $e => $gs) {
				for($i=0;$i<strlen($gs);$i++) $this->temp['category_access_grants'][$gs[$i]][] = $e;
			}
		}
		return $this->temp['category_access_grants'];
	}

	 
	 ####################################################
	# Returns an associative array of the current user's
	# declared access permissions
	function &get_current_access_grants() {
		$grants = &$this->temp['current_access_grants'];
		if(!is_array($grants)) {
			$e  = &$this->get_notitia_system();
			$db = &$this->get_db();
			$session = &get_mysource_session();
			$grants = $db->associative_column("SELECT categoryid, access_types FROM $e->table_name"."_category_grant WHERE ((entity_type='U' AND entityid='{$session->user->id}') OR (entity_type='G' AND entityid IN ('".implode("','",$session->access_groups)."')))");
			for(reset($grants); strlen($categoryid = key($grants)) > 0; next($grants)) {
				$str = "";
				foreach($grants[$categoryid] as $gs) {
					for($i=0;$i<strlen($gs);$i++) if(!strstr($str,$gs[$i])) $str .= $gs[$i];
				}
				$grants[$categoryid] = $str;
			}
		}
		return $grants;
	}

	 ##########################################################
	# Checks whether the currect user has a certain permission
	# in a category
	function check_permission($categoryid = 0, $type = "") {
		if(superuser("web")) {
			$perms = "MVPARECDSZ";
		} else {
			if($categoryid && $siteid = $this->siteid($categoryid)) {
				$site = &$this->web_system->get_site($siteid);
				if($site->admin_access()) {
					$perms = "MVPARECDSZ";
				}
			}
			if(!$perms) {
				$grants = &$this->get_current_access_grants();
				$perms = &$grants[$categoryid];
			}
		}
		if(!strlen($type)) return $perms;
		return eregi("[$type]",$perms);
	}

	 ###################################################################
	# Returns an array of categories ids accessible by the current user
	# with a specified privlidge
	function get_grant_categoryids($type = "V") {
		$r = &$this->temp['permission_category_lists'][$type];
		if(!is_array($r)) {
			$r = array();
			if(superuser("web")) $r = $this->all_descendantids(0);
			else {
				$grants = &$this->get_current_access_grants();
				for(reset($grants); strlen($categoryid = key($grants)); next($grants)) {
					if(eregi("[$type]",$grants[$categoryid])) $r[] = $categoryid;
				}
			}
		}
		return $r;
	}
	

	 ####################################
	# Creates a new category in the tree
	function create_category($name = '',$parentid = 0,$siteid = 0) {
		$name = trim($name);
		if(!$name) return;
		$class = ereg_replace('_tree$','',get_class($this));
		$category = new $class();
		if($category->create($name,$parentid,$siteid) > 0) {
			$e = &$this->get_notitia_system();
			$e->category_factory[$category->id] = &$category;
			$session = &get_mysource_session();
			# Ensure the new category has the same security settings as its parent.
			$grants = $this->load_category_access_grants($parentid);
			$category->parameters['permissions']['category'] =  $this->load_category_access_grants($parentid);
			# Also make sure it has the same staff list
			if($parentid) $parent_category = &$this->get_category($parentid);
			if($parentid) $staff = $parent_category->get_value('staff');
			else          $staff = $e->get_value('staff');
			$category->set_value('staff',$staff);
			# Now make sure the user creating this category has permission to delete it again
			if(!in_array($session->user->id,$staff)) $staff .= $session->user->id;
			$category->parameters['permissions']['category']['Z'][] = "U{$session->user->id}";
			$category->save();
			$category->clear_cache();
			$this->tree[$category->id] = array('id'=>$category->id,'name'=>$category->name,'description'=>'','parentid'=>$category->parentid);
			$this->tree[$category->parentid]['childids'][] = $category->id;
			$this->clear_cache();
			return $category->id;
		}
		return 0;
	}

	 #####################################################
	# Removes any evidence of the existance of a category
	function remove_category($categoryid) {
		if($this->exists($categoryid)) {
			$parentid = $this->parentid($categoryid);
			array_remove_element($categoryid,$this->childids($parentid));
			unset($this->tree[$categoryid]);
			$this->clear_cache();
		}
	}


	 #######################################################################
	# Intense recursive category GENOCIDE.. to be used with eXXtreme care
	# Also removes any records that would become orphaned in the process
	function massacre_category($categoryid) {
		$category = &$this->get_category($categoryid);
		if(!$category->id) return;
		# First wipe out all the subcategories.
		foreach($this->childids($categoryid) as $childid) {
			$ms[] = $this->massacre_category($childid);
		}
		# Now all the records
		foreach($category->associated_records() as $recordid) {
			$record = &$this->get_record($recordid);
			if(count($record->categoryids) <= 1) { # would be orphaned
				$ms[] = $record->delete();
			}
		}
		# The category will clean up the rest of the stuff (attributes etc);
		$ms[] = $category->delete();
		return implode("\n",$ms);
	}

	 ########################################
	# Remembers that a new attribute exists!
	function new_attribute($attributeid,$name,$categoryid,$recordid) {
		$place = &$this->tree[$parentid]['attributes'][$categoryid][$recordid];
		$prev_attributeid = end($place);
		$place[] = $attributeid;
		$this->attributes[$attributeid] = array('name'=>$name,'categoryid'=>$categoryid,'recordid'=>$recordid,'order_no'=>$this->attributes[$prev_attributeid]+1);
		$this->clear_cache();
	}

	/**
	* A function that will update the tree and quick attribute lookup trees. To be called whenever an attribute is associated with a record.
	*
	* @param   int $attributeid	The attributeid
	* @param   int $recordid	The record id
	* @param   int $categoryid	The category id
	* @access  public
	* @returns void
	*/
	function associate_attribute_with_record($attributeid=0, $recordid=0, $categoryid=0) {
		$this->tree[$categoryid]['attributes'][$recordid] = $attributeid;
		$context_attribute_names = &$this->temp['context_attributes']['0_'.$recordid];
		$context_attributeids = array_keys($context_attribute_names);
		if (!in_array($attributeid, $context_attributeids)) {
			$context_attribute_names[$attributeid] = $this->attributes[$attributeid]['name'];
		}
		$this->clear_cache();
	}

	 #####################################################
	# Removes any evidence of the existance of a category
	function remove_attribute($attributeid) {
		if(isset($this->attributes[$attributeid])) {
			$categoryid = $this->attributes[$attributeid]['categoryid'];
			$recordid  = $this->attributes[$attributeid]['recordid'];
			array_remove_element($attributeid,$tree->tree[$categoryid]['attributes'][$recordid]);
			unset($tree->attributes[$attributeid]);
			$this->clear_cache();
		}
	}

	 ##############################################
	# Reorders the attributes for a certai context
	function new_attribute_order($categoryid,$recordid,$new_order) {
		$place = &$this->tree[$categoryid]['attributes'][$recordid];
		$i = 0;
		foreach($new_order as $attributeid) {
			$i++;
			$place[] = $attributeid;
			$this->attributes[$attributeid]['order_no'] = $i;
		}
		$this->clear_cache();
	}

	 ##########################################
	# A category etc.'s name etc. changes
	function new_category_name($categoryid,$name) {
		$this->tree[$categoryid]['name'] = $name;
		$this->clear_cache();
	}
	function new_category_description($categoryid,$description) {
		$this->tree[$categoryid]['description'] = $description;
		$this->clear_cache();
	}
	function new_attribute_name($attributeid,$name) {
		$this->attributes[$attributeid]['name'] = $name;
		$this->clear_cache();
	}

	/**
	* Called when a category has been re-ordered to make sure the category tree stays up-to-date
	*
	* @param int $parent_categoryid		the id of the parent of the category whose order_no was changed
	*
	* @return void
	* @access public
	*/
	function new_category_order($parent_categoryid) {
		$db = &$this->get_db();
		$this->tree[$parent_categoryid]['childids'] = $db->single_column("SELECT categoryid FROM $this->table_name WHERE parentid = '".$parent_categoryid."' ORDER BY order_no");
		$this->clear_cache();
	}


	 #############################################
	# Prints out the browsable category tree
	function print_tree($rootid, $link) {
		if(!$rootid) $rootid = 0;

		$js = "
			function open_cat(catid) {
				document.edit.open_catid.value = catid;
				document.edit.action.value = 'Commit';
				document.edit.submit();
			}

			function close_cat(catid) {
				document.edit.close_catid.value = catid;
				document.edit.action.value = 'Commit';
				document.edit.submit();
			}

			function set_connect_child(id,name) {
				connect_child = id;
				connect_child_name = name;
			}

			var connect_child = false;
			";
		echo js($js);
		echo("<input type=hidden name=open_catid>");
		echo("<input type=hidden name=child_catid>");
		echo("<input type=hidden name=parent_catid>");
		echo("<input type=hidden name=close_catid>");
		echo("<table cellpadding=0 cellspacing=0 border=0>");
		$linkables = &$this->get_grant_categoryids("MV");
		$root_array = array($rootid);
		$this->_print_tree_rec($root_array,array(),0,$linkables, $link);
		echo("</table>");
		echo("<span class=smallprint><br><b>Note:</b> Only categories that you have permission to view are shown.</span>");
	}

	 ########################
	# Recursive tree printer
	function _print_tree_rec(&$cats,$depth=array(),$static,&$linkables,$link) {

		$session = &get_mysource_session();
		$e = &$this->get_notitia_system();

		if(!$link) {
			$link = $e->get_backend_href()."&edit_categoryid=";
		}

		$open_cats = &$session->get_var(get_class($this)."_open_categories");

		$depth[] = true; # The indenting level, stalk on/off

		$cat_count = 0;
		foreach($cats as $catid) {
			$end = ($cat_count + 1 >= count($cats));

			$cat       = &$this->record($catid);
			if($catid == 0) $cat["name"] = "[$e->name]";
			$haskids   = (isset($cat['childids']) && count($cat['childids']) > 0);
			$open      = in_array($catid,$open_cats);
			$clickable = in_array($catid,$linkables);
			$openable  = ($clickable && $haskids || array_any_common_elements($linkables,$this->all_descendantids($catid)));
			if(!$clickable && !$openable) continue;
			?>
			<a name=category<?=$catid?>>
			<tr><td>
			<table cellpadding=0 cellspacing=0 border=0<?=(($catid == $e->categoryid) ? " bgcolor=#ffffbb":"")?> width=100%>
				<tr>
				<?
				for ($i = 0; $i < count($depth); $i++) {
					?><td valign=top <? if ($depth[$i] && !($end && ($i == count($depth)-1))) echo "background=\"images/tree/stalk.gif\"" ?> width=20><?
					if ($i != count($depth) - 1) { 
						?><img src="images/blank.gif" height=1 width=20><?
					} else {
						if ($openable) {
							?><a href="javascript:<?=(($open)?"close_cat('$catid')":"open_cat('$catid')")?>;" onmouseover="window.status='<?=(($open)?"Hide":"Show")?> subcategories'; return true;" onmouseout="window.status='';return true;"><img src="images/tree/branch_<?=(($open)?"open":"closed")?>.gif" alt="<?=(($open)?"Hide":"Show")?> subcategories" height=20 width=20 border=0></a><?
						} else {
							?><img src="images/tree/branch.gif" height=20 width=20><?
						}
					}
					?></td><?
				}

				?>
					<td valign=top width=25><? if (!$static && $clickable) { ?><a href="javascript:if (connect_child.length > 0) {if(confirm('Make \''+connect_child_name+'\' a subcategory of \'<?=addslashes($cat['name'])?>\'?')){document.edit.action.value='connect';document.edit.child_catid.value=connect_child;document.edit.parent_catid.value='<?=$catid?>';document.edit.action.value='Commit';document.edit.submit()}else{set_connect_child(false,false);}} else {set_connect_child('<?=$catid?>','<?=addslashes($cat['name'])?>');}" onmouseover="if (connect_child.length > 0) {window.status='Make this category the parent of the \''+connect_child_name+'\'.';} else{window.status='Connect this category to a different parent.';} return true;" onmouseout="window.status='';return true;"><? } ?><img src="images/icons/site.gif" width=20 height=20 border=0></a><img src="images/blank.gif" width=5 height=20></td>
					<td class=data><? if ($clickable) {?><a href="<?="$link$catid"?>" onmouseover="window.status='<? echo ereg_replace("[\n\r]+","	 ",ereg_replace("'","\\'",$cat['description'])) ?>'; return true;" onmouseout="window.status=''; return true;"><?}?><?=$cat['name']?></a></td>
				</tr>
			</table>
			</td></tr>
			<?
			if (isset($cat['childids']) && count($cat['childids']) > 0 && ($open || $static)) {
				if ($end) $depth[count($depth)-1] = false;
				$this->_print_tree_rec($cat['childids'], $depth, $static, $linkables, $link);
			}
			$cat_count++;
		}
	}
	
	 ####################################################
	# Opens a category in the current session backend
	function open_backend_category($categoryid) {
		$session = &get_mysource_session();
		$open_cats = &$session->get_var(get_class($this)."_open_categories");
		if(strlen($categoryid) && !in_array($categoryid,$open_cats))
			$open_cats[] = (int) $categoryid;
	}

	 ####################################################
	# Closes a category in the current session backend
	function close_backend_category($categoryid) {
		$session = &get_mysource_session();
		$open_cats = &$session->get_var(get_class($this)."_open_categories");
		if(strlen($categoryid)) array_remove_element($categoryid,$open_cats);
	}

	 ####################################################
	# Handles any modifications to the category tree
	# made through the interface
	function process_tree_interface() {
		$session = &get_mysource_session();
		$open_cats = &$session->get_var(get_class($this)."_open_categories");

		$open_catid = $_POST['open_catid'];
		$close_catid = $_POST['close_catid'];
		if(strlen($open_catid))  $this->open_backend_category($open_catid);
		if(strlen($close_catid)) $this->close_backend_category($close_catid);

		 ##########################
		# Moving categories around
		$parent_catid = $_POST['parent_catid'];
		$child_catid = $_POST['child_catid'];
		if(strlen($parent_catid) && strlen($child_catid)) {
			$parent_catid = (int) $parent_catid;
			$child_catid  = (int) $child_catid;

			 ######################################################
			# Okay, to move a category from one place to another
			# you need to have permission to delete it, as well
			# as permission to create new categories under
			# the new parent.
			if(!$this->check_permission($child_catid,"D")) {
				return "You do not have permission to move this category.";
			}
			if(!$this->check_permission($parent_catid,"S")) {
				return "You do not have permission to move a category into this parent category.";
			}

			return $this->move_category($child_catid,$parent_catid);

		}

	}

	 ########################################################
	# Prints the attributes in a certain context in the form
	# of a tree, so you can see where they comes from
	function print_context_attributes($categoryid = 0) {
		if(!$categoryid) {
			return 0;
		}
		$e = &$this->get_notitia_system();
		$r = $this->print_context_attributes($this->tree[$categoryid]['parentid']);
		for($i = 0; $i < $r; $i++) echo '> ';
		$href = $e->get_backend_href()."&edit_categoryid=$categoryid";
		echo "<a href=\"$href\"><b>".$this->name($categoryid)."</b></a>:<br>";
		foreach($this->tree[$categoryid]['attributes'][0] as $attributeid) {
			for($i = 0; $i < $r; $i++) echo '> ';
			echo "- <a href=\"$href&edit_attributeid=$attributeid\">{$this->attributes[$attributeid][name]}</a><br>";
		}
		return $r + 1;
	}


	 ########################################################
	# Prints the attributes in a certain context in the form
	# of a tree, so you can see where they comes from
	function print_context_variety_sets($categoryid = 0, $recordid = 0) {

		$e     = &$this->get_notitia_system();
		$db    = &$this->get_db();
		$ancestorids = array();
		if($recordid) { # All the attributes inherited in a particular record
			$categoryids = $db->single_column("SELECT categoryid FROM $e->table_name"."_record_to_category WHERE recordid='$recordid'");
			$ancestorids = array_merge($categoryids, $this->bulk_ancestorids($categoryids));
		} elseif($categoryid) { # Alll the attribue inherited by a particular category
			$ancestorids = $this->ancestorids($categoryid);
		}

		if(count($ancestorids)) {
			$variety_set_info = &$db->associative_column("SELECT categoryid, variety_setid FROM $e->table_name"."_variety_set WHERE categoryid IN (0,'".implode("','",$ancestorids)."') AND recordid=0");
			foreach($ancestorids as $ancestorid) {
				if(!isset($attribute_info[$ancestorid])) {
					$attribute_info[$ancestorid] = array();
				}
			}
			$this->_print_context_variety_sets_rec(0,$variety_set_info,$ancestorids);
		} else {
			echo("None.");
		}
	}
	function _print_context_variety_sets_rec($categoryid = 0, &$variety_set_info, &$ancestorids) {
		$e     = &$this->get_notitia_system();
		foreach($variety_set_info[$categoryid] as $variety_setid) {
			$variety_set = &$this->get_variety_set($variety_setid);
			$variety_set->print_backend_summary();
			echo("<br>");
		}
		foreach($this->tree[$categoryid]['childids'] as $childid) {
			if(!in_array($childid,$ancestorids)) continue;
			if(!$open) {echo("<ul>"); $open = 1;}
			$href = $e->get_backend_href()."&edit_categoryid=$childid";
			$name = $this->name($childid);
			if(isset($variety_set_info[$childid])) {
				echo("<li><a href=\"$href\"><b>$name</b></a>:</li>");
			} else {
				echo("<li><a href=\"$href\">$name</a>:</li>");
			}
			$this->_print_context_variety_sets_rec($childid, $variety_set_info, $ancestorids);
		}
		if($open) {echo("</ul>"); $open = 0;}
	}



	 ########################################################
	# Returns an array($id => $name) of all the attributes in 
	# a certain context, IN THE CORRECT ORDER :D
	function &get_context_attributes($categoryid = 0, $recordid = 0, $record = null) {
		if(isset($this->temp['context_attributes'][$categoryid.'_'.$recordid])) {
			return $this->temp['context_attributes'][$categoryid.'_'.$recordid];
		} else {
			$r = &$this->temp['context_attributes'][$categoryid.'_'.$recordid];
			$r = array();
			# Wipe them
			if($recordid || $record->id) { # All the attributes inherited in a particular record
				if (!is_object($record)) {
					$record = &$this->get_record($recordid);
				}
				if (!empty($record->categoryids)) {
					foreach($record->categoryids as $categoryid) {
						if($categoryid) {
							$r += $this->get_context_attributes($categoryid);
							foreach($this->tree[$categoryid]['attributes'][$recordid] as $attributeid) {
								$r[$attributeid] = $this->attributes[$attributeid]['name'];
							}
						}
					}
				}
			} else {
				if($categoryid) {
					$r += $this->get_context_attributes($this->tree[$categoryid]['parentid']);
					$context_attributeids = reset($this->tree[$categoryid]['attributes']);
					foreach($context_attributeids as $attributeid) {
						$r[$attributeid] = $this->attributes[$attributeid]['name'];
					}
				}
			}
			return $r;
		}
	}

	/**
	* Gets a Categories local attributes
	*
	* @access public
	* @param int $categoryid - the categoryid
	* @return array
	*/
	function &get_local_category_attributes($categoryid = 0) {
		$r = array();
		if($categoryid) {
			$context_attributeids = reset($this->tree[$categoryid]['attributes']);
			foreach($context_attributeids as $attributeid) {
				$r[$attributeid] = $this->attributes[$attributeid]['name'];
			}
		}
		return $r;
	}

	 ###############################################################
	# Returns an array($id => $name) of all the local attributes of
	# a record, in the correct order
	function &get_local_attributes($recordid = 0) {
		if(!isset($this->temp['local_attributes'][$recordid])) {
			if(!$recordid) return;
			$e     = &$this->get_notitia_system();
			$db    = &$this->get_db();
			$record = &$this->get_record($recordid);
			$this->temp['context_attributes'][$recordid] = $record->local_attributes;
		}
		return $this->temp['context_attributes'][$recordid];
	}


	 ########################################################
	# Returns an array($id => $name) of all the variety sets
	# in a certain context, IN THE CORRECT ORDER :D
	function &get_context_variety_sets($categoryid = 0, $recordid = 0) {
		if(!isset($this->temp['context_variety_sets'][$categoryid."_$recordid"])) {
			$e  = &$this->get_notitia_system();
			$db = &$this->get_db();
			$ancestorids = array();
			if($recordid) { # All the attributes inherited in a particular record
				$categoryids = $db->single_column("SELECT categoryid FROM $e->table_name"."_record_to_category WHERE recordid='$recordid'");
				$ancestorids = array_merge($categoryids,$this->bulk_ancestorids($categoryids));
			} elseif($categoryid) { # Alll the attribue inherited by a particular category
				$ancestorids = array_merge(array($categoryid),$this->ancestorids($categoryid));
			}

			$variety_set_info = &$db->associative_array2("SELECT categoryid, variety_setid, '' FROM $e->table_name"."_variety_set WHERE categoryid IN (0,'".implode("','",$ancestorids)."') AND recordid=0");

			for(reset($variety_set_info); NULL !== ($categoryid = key($variety_set_info)); next($variety_set_info)) {
				$vss = &$variety_set_info[$categoryid];
				for(reset($vss); NULL !== ($variety_setid = key($vss)); next($vss)) {
					$variety_set = &$this->get_variety_set($variety_setid);
					$vss[$variety_setid] = $variety_set->name();
				}
			}
			$this->temp['context_variety_sets'][$categoryid."_$recordid"] = $this->_get_attributes_rec(0,$variety_set_info,$ancestorids);
		}
		return $this->temp['context_variety_sets'][$categoryid."_$recordid"];
	}
	function _get_attributes_rec($categoryid = 0, &$attribute_info, &$ancestorids) {
		$e     = &$this->get_notitia_system();
		if(isset($attribute_info[$categoryid]) && is_array($attribute_info[$categoryid])) {
			$r = $attribute_info[$categoryid];
		} else {
			$r = array();
		}
		foreach($this->tree[$categoryid]['childids'] as $childid) {
			if(!in_array($childid,$ancestorids)) continue;
			$r += $this->_get_attributes_rec($childid, $attribute_info, $ancestorids);
		}
		return $r;
	}

	 ################################################################
	# Returns an array($id => $name) of all the local variety sets
	# a record, in the correct order
	function &get_local_variety_sets($recordid = 0) {
		if(!isset($this->temp['local_variety_sets'][$categoryid."_$recordid"])) {
			$e     = &$this->get_notitia_system();
			$db    = &$this->get_db();
			$attribute_info = &$db->associative_array2("SELECT categoryid, variety_setid, '' FROM $e->table_name"."_variety_set WHERE recordid='$recordid'");

			for(reset($variety_set_info); NULL !== ($categoryid = key($variety_set_info)); next($variety_set_info)) {
				$vss = &$variety_set_info[$categoryid];
				for(reset($vss); NULL !== ($variety_setid = key($vss)); next($vss)) {
					$variety_set = &$this->get_variety_set($variety_setid);
					$vss[$variety_setid] = $variety_set->name();
				}
			}

			$categoryids = array_keys($attribute_info);
			$ancestorids = array_merge($categoryids,$this->bulk_ancestorids($categoryids));
			$this->temp['local_variety_sets'][$categoryid."_$recordid"] = $this->_get_attributes_rec(0,$attribute_info,$ancestorids);
		}
		return $this->temp['local_variety_sets'][$categoryid."_$recordid"];
	}

	/**
	* Returns if the categoryid has any children
	*
	* @access public
	* @return Boolean
	*/
	function has_children($categoryid) {
		return !empty($this->tree[$categoryid]['childids']);
	}

	/**
	* Returns a lineage string of category and it's parents
	*
	* @param   int    $categoryid	The category id
	* @param   string $seperator	What you want the pages to be seperated with
	* @param   int    $stop_point	If you don't want to get the whole lineage, but only 
	*								up to a certain point, then supply that category id as 
	*								the stop point
	* @access  public
	* @returns string
	*/
	function get_lineage($categoryid=0, $separator = ' > ', $stop_point = 0) {
		$categoryid = abs((int)$categoryid);
		$stop_point = abs((int)$stop_point);
		$r = $this->name($categoryid);
		while(1) {
			$categoryid = $this->tree[$categoryid]['parentid'];
			if ($categoryid <= 0 || $categoryid == $stop_point) break;
			$r = $this->name($categoryid).$separator.$r;
		}
		return $r;
	}

	/**
	* Function that returns a category list in tree order however no stix
	*
	* @param   int $categoryid The category id
	* @param   int $max_depth  Max depth you want it to go down
	* @access  public
	* @returns array
	*/
	function get_category_list($categoryid,$max_depth = 1) {
		if(!$max_depth) return array();
		if(!$categoryid) $categoryid = 0;
		$childids = &$this->childids($categoryid);
		$countdown = count($childids);
		$result = array();
		foreach($childids as $childid) {
			$countdown--;
			$result[$childid] = $this->name($childid);
			if($max_depth > 1 && count($this->childids($childid))) {
				$result = $result + $this->get_category_list($childid,$max_depth - 1);
			}
		}
		return $result;
	}
}
?>
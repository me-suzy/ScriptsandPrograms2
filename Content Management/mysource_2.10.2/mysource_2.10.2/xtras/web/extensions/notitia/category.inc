<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/category.inc,v $
## $Revision: 1.60.2.2 $
## $Author: achadszinow $
## $Date: 2004/05/19 04:14:17 $
#######################################################################
#---------------------------------------------------------------------#

 ##############################################################
# Represents a category. These objects are only needed when
# dealing in detail witha category. Categories can be managed
# en mass using the category tree class.
class Web_Extension_Notitia_Category extends Web_Extension_Notitia_Object {

	# Var
	var $id;
	var $parentid;
	var $siteid;     # Does this category belong to a particular site?
	var $name;
	var $description;
	var $parameters; # An array of parameters, security and things like that

	var $temp; # An array of runtime data that doesnt get saved

	 #############
	# Constructor
	function Web_Extension_Notitia_Category($categoryid) {
		Web_Extension_Notitia_Object::Web_Extension_Notitia_Object();
		if((int) $categoryid > 0) {
			return $this->load((int) $categoryid);
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
		$this->table_name = "$e->table_name"."_category";
	}

	 #########################
	# Creates a new attribute
	function create($name,$parentid,$siteid) {
		$e     = &$this->get_notitia_system();
		if(!$name) return false;
		$db = &$this->get_db();
		$this->parentid = abs((int) $parentid);
		$this->siteid   = abs((int) $siteid);
		# Make sure this is a unique name in this context
		# Strip naughty characters
		$name = preg_replace("/[\x7F-\x90]+/",'',$name);
		while($db->single_element("SELECT categoryid FROM $this->table_name WHERE name='".addslashes($name)."' AND parentid='$this->parentid' AND siteid='$this->siteid'")) {
			$name = increment_name($name,' ');
		}
		$this->parameters = array("name"=>$name);
		$order_no = $db->single_element("SELECT max(order_no) + 1 FROM $this->table_name WHERE parentid='$this->parentid'");
		# Save to the database
		$categoryid = $db->insert("INSERT INTO $this->table_name (name,parentid,siteid,parameters,order_no) VALUES('".addslashes($name)."','$this->parentid','$this->siteid','".addslashes(serialize($this->parameters))."','$order_no')");
		if($categoryid) {
			return $this->load($categoryid);
		} else {
			return false;
		}
	}

	/**
	* Duplicates a category
	*
	* @param int $parentid	This categories new parentid. If not fed in it uses this categories parentid
	* @param string $msgs	Msgs of dupes success
	* @access  public
	* @returns string
	*/
	function dupe($parentid=0, $msgs='') {
		if (!$parentid) {
			$parentid = $this->parentid;
		}
		$tree = &$this->get_category_tree();

		# First dupe this category. We'll need to save some stuff cause create calls load which will over
		$new_categoryid = $tree->create_category($this->name,$parentid,$this->siteid);
		$msgs .= 'Category '.$this->name.' has been duped<br />';

		# Now dupe all my children
		$child_categoryids = $tree->childids($this->id);
		foreach($child_categoryids as $child_categoryid) {
			$category = &$this->get_category($child_categoryid);
			if ($category->id) {
				$msgs .= $category->dupe($new_categoryid, $msgs);
			}
			unset($category);
		}

		return $msgs;
	}

	 ###################################
	# Changes the name of the category
	function set_name($name) {
		if(!$name) return 'All categories must have names.';
		if($name == $this->name) return '';
		$db = &$this->get_db();
		while($db->single_element("SELECT categoryid FROM $this->table_name WHERE name='".addslashes($name)."' AND categoryid != '$this->id' AND parentid='$this->parentid' AND siteid='$this->siteid'")) {
			$name = increment_name($name,' ');
		}
		$old_name   = $this->name;
		$this->name = $name;
		$this->parameters['name'] = $name;
		$db->update("UPDATE $this->table_name SET name='".addslashes($this->name)."' WHERE categoryid='$this->id'");
		$this->clear_cache();
		$tree = &$this->get_category_tree();
		$tree->new_category_name($this->id,$this->name);
		return "Category '$old_name' renamed to '$this->name' (ID: $this->id).";
	}

	 ##########################################
	# Changes the description of the category
	function set_description($description) {
		if($description == $this->description) return "";
		$db = &$this->get_db();
		$this->description = $description;
		$db->update("UPDATE $this->table_name SET description='".addslashes($this->description)."' WHERE categoryid='$this->id'");
		$this->clear_cache();
		$tree = &$this->get_category_tree();
		$tree->new_category_description($this->id,$this->description);
		return "Category '$this->name' description updated (ID: $this->id).";
	}


	/**
	* Change the order no of this category
	*
	* @param int		$order_no		the new order no
	* @param boolean	$refresh_tree	whether or not to refresh the category tree at the moment (if doing a bulk pass 
	*
	* @return void
	* @access public
	* @see process_sub_category_orderer()
	* @see Category_Tree::new_category_order()
	*/
	function set_order_no($order_no, $refresh_tree=true) {
		$order_no = (int) $order_no;

		$db = &$this->get_db();
		$db->update("UPDATE $this->table_name SET order_no='".$order_no."' WHERE categoryid='$this->id'");
		$this->clear_cache();
		if ($refresh_tree) {
			$tree = &$this->get_category_tree();
			$tree->new_category_order($this->parentid);
		}
		return "Category '$this->name' order number updated (ID: $this->id).";

	}# end set_order_no()

	 #####################################################
	# Saves the current parameter array into the database
	function save() {

		 #######################################################
		# Save the secrutiy permissions to the database - leave
		# the out of the file. But only category masters can mess
		# with these
		if($this->category_master()) {
			$cat_perms = &$this->parameters['permissions']['category'];
			$tree = &$this->get_category_tree();
			if(!$this->superuser()) { # If you're not a category superuser, you can't mess with the M's
				$old_perms = $tree->load_category_access_grants($this->id);
				$this->parameters['permissions']['category']['M'] = $old_perms['M'];
			}
			$tree->save_category_access_grants($this->id, $cat_perms);
		}

		 #################################################
		# Are they trying to create some new categories?
		$new_category_names = split("[\n\r]+",$this->parameters['new_category_names']);
		$this->parameters['new_category_names'] = "";
		if(count($new_category_names) && $this->new_subcategory_access()) {
			$tree = &$this->get_category_tree();
			foreach($new_category_names as $name) {
				if(!$name = trim($name)) continue;
				$message = trim($message)."\n";
				if ($categoryid = $tree->create_category($name,$this->id,$this->siteid)) {
					$category = &$this->get_category($categoryid);
					$message .= "Category '$category->name' created.";
				} else {
					$message .= "Unable to create new category.";
				}
			}
		}

		$tmp = &$this->parameters['permissions']['category'];
		unset(  $this->parameters['permissions']['category']); # Don't need saved

		$message .= $this->set_name($this->parameters['name']) ."\n";
		$this->parameters['name'] = $this->name;
		$message .= $this->set_description($this->parameters['description']) ."\n";
		$this->parameters['description'] = $this->description;


		$db = &$this->get_db();
		$db->update("UPDATE $this->table_name SET parameters='".addslashes(serialize($this->parameters))."' WHERE categoryid='$this->id'");

		$this->parameters['permissions']['category'] = &$tmp;

		$this->clear_cache();
		return trim($message);
	}


	 ################################################################
	# loads stuff.. geez do I really have to write a comment for this
	# function now?
	function load($categoryid) {
		$categoryid = abs((int) $categoryid);

		if (!$categoryid && !($categoryid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load category without a valid categoryid.",__FILE__,__LINE__);
			return;
		}

		 #################
		# Check the cache
		if ($this->load_from_cache($categoryid)) {
			return $categoryid;
		}

		# Load everything from the database;
		$db = &$this->get_db();

		list($this->id,$this->parentid,$this->siteid,$this->name,$this->description,$this->parameters) = $db->single_row("SELECT categoryid, parentid, siteid, name, description, parameters FROM $this->table_name WHERE categoryid='$categoryid'");

		if(!$this->id) return;

		$this->parameters = unserialize($this->parameters);

		 #############################################
		# Load security permissions from database
		$tree = &$this->get_category_tree();
		$this->parameters['permissions']['category'] = $tree->load_category_access_grants($this->id);

		# Okay I think that's just about everything.

		 ###################
		# Save to the cache
		$this->save_to_cache();

		return $this->id;
	}


	 ##########################################################
	# Deletes a category and deals with all the other things
	# affected by this.
	function delete() {

		$db   = &$this->get_db();
		$e    = &$this->get_notitia_system();
		$tree = &$this->get_category_tree();

		$ms = array();

		 ##################################################################
		# Only allow this if there are no children or associated records.
		if(count($tree->childids($this->id))) return "Cannot delete category $this->name while it has subcategories.";
		if(count($this->associated_records())) return "Cannot delete category $this->name while there are still records associated with it.";

		 ###################################
		# Delete any associated attributes
		foreach($this->associated_attributes() as $attributeid) {
			$attribute = &$this->get_attribute($attributeid);
			$ms[] = $attribute->delete();
		}

		$attributeids = array();

		 ##############################
		# Delete any default overrides
		$get_all_default_tables = $this->get_all_default_tables();
		foreach($get_all_default_tables as $default_table_name) {
			$found_attributeids = $db->single_column("DELETE FROM $default_table_name WHERE categoryid='$this->id'");
			$db->delete("DELETE FROM $default_table_name WHERE categoryid='$this->id'");
			$attributeids = array_merge($attributeids, $found_attributeids);
		}

		foreach($attributeids as $attributeid) {
			$attribute = &$this->get_attribute($attributeid);
			$attribute->clear_cache();
		}

		 ###################################
		# Delete any associated variety_sets
		foreach($this->associated_variety_sets() as $variety_setid) {
			$variety_set = &$this->get_variety_set();
			$ms[] = $variety_set->delete();
		}


		# Delete our record
		$db->delete("DELETE FROM $this->table_name"."_grant WHERE categoryid='$this->id'");
		$db->delete("DELETE FROM $this->table_name WHERE categoryid='$this->id'");
		$ms[] = "Category '$this->name' (ID: $this->id) deleted.";
		$this->clear_cache();
		$tree->remove_category($this->id);
		$e->forget_category($this->id);
		$this->id = '';
		return implode("\n",$ms);
	}

	 ##########################################
	# Returns a value from the parameters
	function &get_value($code) {
		return $this->parameters[$code];
	}

	 ################################
	# Sets a value in the parameters
	function &set_value($code,$value) {
		$this->parameters[$code] = $value;
		return $this->parameters[$code];
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

	 ###############################################################
	# Returns an array of all the records linked to this category
	function associated_records() {
		$db = $this->get_db();
		$e  = &$this->get_notitia_system();
		return $db->single_column("SELECT recordid FROM $e->table_name"."_record_to_category WHERE categoryid='$this->id'");
	}

	 #####################################################
	# Checks whether a record is realated to this category
	# or any subcategories.
	function record_exists_beneath($recordid) {
		$record = &$this->get_record($recordid);
		if(!$record->id) return;
		$tree = &$this->get_category_tree();
		return count(array_intersect($record->categoryids,array($this->id)+$tree->all_descendantids($root_cid))) > 0;
	}

	function process_joined_matching($subclauses, $subscores, &$match_clause, &$score_ar, &$searched_attributeids, $scoring, $logic='OR', &$allow_null_status, $first_time=true) {
		# Loop over the subclauses.
		foreach($subclauses as $subattributeid => $subclause) {
			if ($subclause === false) {
				continue;
			}
			if ($first_time) {
				$first_time = false;
			} else {
				$match_clause .= ' '.$logic.' ';
			}
			if (is_array($subclause)) {
				$match_clause .= '(';
				$attribute = &$this->get_attribute($subattributeid);
				$logic = $attribute->get_type_setting('search_logic');
				$this->process_joined_matching($subclause, $subscores, $match_clause, $score_ar, $searched_attributeids, $scoring, $logic, $searched_allow_null_attributeids);
				$match_clause .= ')';
			} else {
				$subattribute = $this->get_attribute($subattributeid);

				# Lets find out if defaults are being used because if it's not allow null we can speed things up by not doing the if thing and joining with the default table. We check the searched_allow_null_attributeids array first because if we are doing the same attribute again it's quicker to check the array first and then ask the attribute than always asking the attribute something we already know. If the in_array returns true due to the magic of short circuiting it will not ask the attribute
				if ((isset($allow_null_status[$subattributeid]) && $allow_null_status[$subattributeid]) || (!isset($allow_null_status[$subattributeid]) && $subattribute->plays_role('allow_null'))) {
					$allow_null_status[$subattributeid] = true;
					# Now, we need two clauses - one for cases where a value
					# is stored explicitly for an record, and one for when the
					# record uses a category default.
					$d_subclause = str_replace('v'.$subattributeid.'.value','d'.$subattributeid.'.value',$subclause);

					# Combine these together into a clause for our attribute (default and norm val)
					$submatch_clause = '(IF(v'.$subattributeid.'.value IS NULL,'.$d_subclause.','.$subclause.'))';

					# When it comes to scoring, we'll treat subquery scores
					# the same as any other query score.
					if($scoring) {
						# No score expression? No scoring on this query.
						if(strlen($v_score_exp = $subscores[$subattributeid])) {
							$d_score_exp = str_replace('v'.$subattributeid.'.value','d'.$subattributeid.'.value', $v_score_exp);
							$score_ar[] = 'IF(v'.$subattributeid.'.value IS NULL,'.$d_score_exp.','.$v_score_exp.')';
						}
					}
				} else {
					$allow_null_status[$subattributeid] = false;
					$submatch_clause = '('.$subclause.')';
					# When it comes to scoring, we'll treat subquery scores
					# the same as any other query score.
					if($scoring) {
						# No score expression? No scoring on this query.
						if(strlen($v_score_exp = $subscores[$subattributeid])) {
							$score_ar[] = $v_score_exp;
						}
					}
				}

				# Hold onto this for afterwards.
				$match_clause .= $submatch_clause;

				# Remember which attributes we've searched on, for future reference.
				if(!in_array($subattributeid,$searched_attributeids)) {
					$searched_attributeids[] = $subattributeid;
				}
			}
		}
	}

	/**
	* Builds a complex where clause using $complex_match_clauses and $complex_matching_rules where there format is outlined in a decription below this function. It actually only builds on a where clause you give the function so you have to start if off with at least WHERE
	*
	* @param   array $complex_match_clauses An array of match clauses that Search records probably has fed us index using our special format.
	* @param   array $complex_matching_rules An array of how we want the query to look eg. (B & (NOT((A AND B) | C)))
	* @param   int $sub_clause_count The number elements in the $complex_matching_rules array
	* @param   string $logic Whether all queries must be matched or at least one. Defined by 'AND' or 'OR' respectively.
	* @param   string $where A where clause to add to.
	* @access  public
	* @returns string
	*/
	function complex_where_builder($complex_match_clauses=array(), $complex_matching_rules=array(), $sub_clause_count=0, $where='') {
		$accounted_clauses = 0;
		$where .= '(';
		foreach($complex_matching_rules as $key => $sub_element) {
			$accounted_clauses++;
			# If we are just picking up what sublogic they want then that's cool we'll take note. This is an invalid normal format so we don't have to worry that it's some sort of matching query they are refering to
			if ($sub_element == 'AND' || $sub_element == 'OR' || $sub_element == 'NOT') {
				$sub_logic = $sub_element;
				continue;
			}

			if (is_array($sub_element)) {
				$sub_clause_count2 = count($sub_element);
				# Check if there is something in the array. If it's empty I guess something is being lazy at Notitia's expense. Oh well I'm fairly sure it's quicker to be lazy here than there
				if ($sub_clause_count2 < 1) {
					# So we don't make all the logic collapse in meaning by inserting a true statement if it's and AND or false statement if it's an OR the overall logic of the query is un-affected
					switch ($sub_logic) {
						case 'AND':
							$where .= '1=1';
							break;
						case 'OR':
							$where .= '1=0';
							break;
						default:
							# Can't think how to cater for a NOT at the moment cause it would require knowing the sub sub logic I think.
							break;
					}
				} else {
					# Recursively build the rest of the where clause
						$where = $this->complex_where_builder($complex_match_clauses, $sub_element, $sub_clause_count2, $where.($sub_logic == 'NOT'?' NOT ':''));
				}
			} else {
				# Check for special system matching clause
				if ($sub_element == 'TRUE') {
					$where .= '1=1';
				} elseif ($sub_element == 'FALSE') {
					$where .= '1=0';
				} else {
					if (!isset($complex_match_clauses[$sub_element])) {
						if ($sub_logic == 'AND') {
							$where .= '1=1';
						} elseif ($sub_logic == 'OR') {
							$where .= '1=0';
						}
					} else {
						# Insert our matching clause
						$where .= $complex_match_clauses[$sub_element];
					}
				}
			}
			# If we have a bunch more clauses to work out put an AND in
			if (($sub_clause_count - $accounted_clauses) > 0) {
				$where .= ' '.$sub_logic.' ';
			}
		}
		$where .= ')';
		return $where;
	}

	/**
	* Narrows down the complex matching rules array by removing elements that do not exist in the
	* complex match clauses array
	*
	* @param   array $complex_match_clauses An array of match clauses that Search records probably
	* has fed us index using our special format.
	* @param   array $complex_matching_rules An array of how we want the query to look
	* @access  public
	* @returns void
	*/
	function complex_rule_narrower(&$complex_match_clauses, &$complex_matching_rules) {
		$keys = array_keys($complex_matching_rules);
		$sub_logic = $complex_matching_rules[0];
		if ($sub_logic != 'AND' && $sub_logic != 'OR' && $sub_logic != 'NOT') {
			$unsetall = true;
		} else {
			if ($sub_logic == 'NOT' && (!is_array($complex_matching_rules[1]) || count($keys) > 2)) {
				$unsetall = true;
			} else {
				unset($keys[0]); # skip the logic in the loop
			}
		}
		foreach ($keys as $key) {
			if ($unsetall) {
				# we do not have logic set as the first element get rid of everything in bracket
				unset($complex_matching_rules[$key]);
				continue;
			}
			$sub_element = &$complex_matching_rules[$key];
			if ($sub_element == 'TRUE' || $sub_element == 'FALSE') {
				continue;
			}
			if (is_array($sub_element)) {
				$this->complex_rule_narrower($complex_match_clauses, &$sub_element);
				if (count($sub_element) <= 1) {
					# remove unwanted bracket
					unset($complex_matching_rules[$key]);
				}
			} elseif(!isset($complex_match_clauses[$sub_element])) {
				# remove unwanted rule
				unset($complex_matching_rules[$key]);
			}
		}
	}

	/*
		HOW THE COMPLEX MATCHING IS USED

		Ok so you have your $complex_attribute_queries which are the queries that say this attribute value must match this using this matching format. Then you have your $complex_matching_rules which is an array that describes how the matching comes together.

		Here we have $complex_attribute_queries in the format
		$complex_attribute_queries[$attributeid][$format][$label] = $query;

		The $complex_attribute_queries has to match the $complex_matching_rules array complex_matching_rules

		Example:
		And here we have $complex_matching_rules
		82 => array (
			id => array (
				raw => 'two'
			)
		)
		130 => array (
			subset => array (
				frontitia_system_status_approved => 'query1',
				frontitia_system_status_pending => 'query2'
			)
		)
		122 => array (
			subset => array (
				frontitia_system_status_approved => 'anotherquery'
			)
		)

		And here we have $complex_matching_rules
		0 => "OR",
		1 => array (
			0 => "OR"
			1 => "82%id%raw"
		),
		2 => array (
			0 => "AND",
			1 => "TRUE",
			2 => array (
				0 => "OR",
				1 => "130%subset%frontitia_system_status_approved",
				2 => "130%subset%frontitia_system_status_pending"
			)
		),
		3 => array (
			0 => 'NOT',
			1 => array (
				0 => 'AND'
				1 => '122%subset%frontitia_system_status_approved'
			)
		)

		Note how the data of a matching rule can match up with an attribute query. The complex rules have a few special words like AND, OR, TRUE, FALSE and finally NOT. With these essentially you can do it all. A Sub array is like putting the statement in brackets. There is a special rule with the NOT. It must be contained in an array. The first element is the NOT then you have as the second element another array and inside whatever you want.
		Finally the LOGIC clause must always be at the first element in the rule array otherwise the query building will fail.

		Now back to the $complex_attribute_queries. The format here of the index (and this goes for referal too) is ATTRIBUTEID%SEARCH_FORMAT%KEYWORD_NAME_OR_SPECIAL_FRONTITIA_SYSTEM_NAME

		There you have it. Use it wisely and never form a bad query.
	*/

	/**
	* Returns an array of all the records linked to this category
	* that meet certain search criteria.
	*
	* @param   array $attribute_queries An array in the form array(attributeid => array(search_format => array(query, query2, ...), format2 => ...), attributeid2 => ...)
	* @param   array $sort_formats An ordered array of strings in the form "$attributeid_$sort_format"
	* @param   bool $search_categories Either specify an array of categoryids to be searched or if you give it true it will search this category and all it's subs or false it will just search this category
	* @param   string $logic Whether all queries must be matched or at least one. Defined by 'AND' or 'OR' respectively.
	* @param   array $restrict_recordids An array of recordids. The search will only return a subset of these records.
	* @param   array $complex_attribute_queries An array of queries that are indexed to match up with the $complex_matching_rules. This is for advanced where clauses with unlimitted search logic. Index format is outlined above. Any query in here not mentioned in the $complex_matching_rules is a waste and will not get used but will slow down the query.
	* @param   array $complex_matching_rules An array of complex matching rules in sort of a post script format. An explaintion of this format is above this function.
	* @access  public
	* @returns array The sorted search results as record ids.
	*/
	function search_records($attribute_queries=array(), $sort_formats=array(), $search_categoryids=false, $logic='OR', $restrict_recordids = array(), $complex_attribute_queries=array(), $complex_matching_rules=array()) {
		# Restrict recordids must be an array so lets make sure
		if (!is_array($restrict_recordids)) {
			$restrict_recordids = array();
			report_error(__FILE__,__LINE__,'Notitia function search_records was passed $restrict_recordids and that was not an array when it should be. This error is not fatal but should be tracked down.');
		}

		# Get some useful objects.
		$db = $this->get_db();
		$e  = &$this->get_notitia_system();
		$tree = &$this->get_category_tree();

		# This is like the default matching rule for each query.
		# These may be overrided by appending the appropriate symbol
		# to the search format. The rules are:
		# & - Must be matched
		# | - May be matched
		# ~ - Must not be matched
		# ^ - Only one of the ^'s may be matched - no more.
		switch($logic = trim(strtoupper($logic))) {
			case 'AND':  case '&': $logic = '&'; break;
			case 'NOT':  case '~': $logic = '~'; break;
			case 'XOR':  case '^': $logic = '^'; break;
			case 'OR':   case '|': default: $logic = '|'; break;
		}
		if ((!is_array($search_categoryids) && $search_categoryids) || (is_array($search_categoryids) && empty($search_categoryids))) {
			# They want to search this category and all sub categories or they gave us an empty array of search categories
			$search_categoryids = $tree->all_descendantids($this->id);
			array_unshift($search_categoryids,(int)$this->id);
		} elseif (!is_array($search_categoryids) && !$search_categoryids) {
			# They just want to search this category
			$search_categoryids = array($this->id);
		}
		# If we're not sorting by anything, then we'll sort using
		# the scoring system. This is a quick-ref boolean
		$sorting = count($sort_formats) > 0;
		$scoring = (!$sorting || in_array('_score',$sort_formats));

		# Searching? WE may only be sorting.
		$simple_searching = count($attribute_queries);

		# Now the for the queries. These are used to restrict
		# the results to only records that match certain criteria
		# Its possible not to have any queries, in which the results
		# are simply a list of all the records in a category (and
		# possible its subcategories) probably sorted in some way.
		$searched_attributeids = array();
		$match_count = 0;
		$match_clauses = array();
		$logic_matches = array('&'=>0,'|'=>0,'~'=>0,'^'=>0);
		$score_ar = array();
		# An array of attributeid => true/false depending on if it's an allow nuller attribute
		$allow_null_status = array();

		# First look through each attribute.
		foreach($attribute_queries as $attributeid => $formats) {
			# Get to know this attribute
			$attribute = &$this->get_attribute($attributeid);
			if(!$attribute->id) continue;
			$existing_formats = $attribute->get_type_setting('search_formats');
			# Lets go through each of the different search formats
			# for this attribute.
			foreach($formats as $format => $queries) {

				# Formats trailing &s |s ^s or ~s mean the
				# queries must, may, must not, and must ONLY be
				# matched respectively. In this section it is
				# possible to override the global search logic
				# with special rules for certain queries.
				switch($f_logic = $format[strlen($format)-1]) {
					case '&': case '|': case '^': case '~':
						$format = substr($format,0,-1);
						break;
					default:
						$f_logic = $logic;
						break;
				}

				# Ensure this isn't an illegal format.
				# Formats that start with _ are magic and internal (not for users)
				# so we may come across one here that isn't listed in the format list
				# we'll exempt the magic underscore formats from this rule.
				if(!isset($existing_formats[$format]) && substr($format,0,1) != '_') continue;

				# Loop through each of the queries for this format.
				foreach($queries as $query) {
					if ($query == NULL) {
						$query = "''";
					}
					# Okay the match_bit is unique power-of-two for this
					# query. So basically we probably can't have more than
					# about 31 (or 63?) queries in one search. As you should
					# know if you write out a power of two in binary it will
					# have exactly one 1 and the rest 0's. Each query will have
					# its match bit in a different spot. We can then use bitwise
					# operations to do our and/or/not/xor logic on our queries!
					# Woohoo!
					$match_bit = pow(2,$match_count);

					# This just allows us to remember which queries (now
					# represented by their match_bit use which logic. Isn't
					# it cool how we can stor all this infor in just four
					# integers?!
					$logic_matches[$f_logic] |= $match_bit;

					# Meta attributes are a special case. They return an array of
					# subqueries to do. We will assume for now, that if any of these
					# subqueries match, then the superquery is a match (i.e. all the
					# subqueries are |s.
					if($attribute->get_type_setting('sub_type_name') == 'meta') {
						# Get the subclauses and possibly the score expression.
						$subclauses = $attribute->get_search_sql_clause($query,$format);
						if ($subclauses === false) {
							# We have a failed search attribute so lets take them from the count
							$logic_matches[$f_logic] = $logic_matches[$f_logic] - $match_bit;
							continue;
						}

						if($scoring) {
							$subscores = $attribute->get_search_sql_score($query,$format);
						}
						$match_clause = '';
						$logic = $attribute->get_type_setting('search_logic');
						$this->process_joined_matching($subclauses, $subscores, $match_clause, $score_ar, $searched_attributeids, $scoring, $logic, $allow_null_status);
						# Take off the first 4 chars cause it's an excess " OR "
						$match_clauses[] = '(('.$match_clause.')*'.$match_bit.')';
						$match_count++;
					} else {
						# Okay, normal antimeta-attributes. Lets get our subclause
						$subclause = $attribute->get_search_sql_clause($query,$format,'v'.$attributeid.'.value');
						if ($subclause === false) {
							# We have a failed search attribute so lets take them from the count
							$logic_matches[$f_logic] = $logic_matches[$f_logic] - $match_bit;
							continue;
						}

						# Lets find out if defaults are being used because if it's not allow null we can speed things up by not doing the if thing and joining with the default table. We check the searched_allow_null_attributeids array first because if we are doing the same attribute again it's quicker to check the array first and then ask the attribute than always asking the attribute something we already know. If the in_array returns true due to the magic of short circuiting it will not ask the attribute
						if ((isset($allow_null_status[$attributeid]) && $allow_null_status[$attributeid]) || (!isset($allow_null_status[$attributeid]) && $attribute->plays_role('allow_null'))) {
							$allow_null_status[$attributeid] = true;
							# And the alternate clause for matches on cetegory defautlts
							# when there is no corresponding record value.
							$d_subclause = str_replace('v'.$attributeid.'.value','d'.$attributeid.'.value',$subclause);

							# Combine these together into a clause for our attribute (default and norm val)
							$clause = '(IF(v'.$attributeid.'.value IS NULL,'.$d_subclause.', '.$subclause.')*'.$match_bit.')';
						} else {
							$allow_null_status[$attributeid] = false;
							$clause =  '(('.$subclause.')*'.$match_bit.')';
						}

						# Hang onto this clause for later.
						$match_clauses[] = $clause;

						# Keep on counting those matches (queries).
						$match_count++;

						# Remember which attributes we've searched on, for future reference.
						if(!in_array($attributeid,$searched_attributeids)) {
							$searched_attributeids[] = $attributeid;
						}

						# If we're scoring, get the scoring expression.
						if($scoring) {
							# No score expression? No scoring on this query.
							if(strlen($v_score_exp = $attribute->get_search_sql_score($query,$format, 'v'.$attributeid.'.value'))) {
								if ((isset($allow_null_status[$attributeid]) && $allow_null_status[$attributeid]) || (!isset($allow_null_status[$attributeid]) && $attribute->plays_role('allow_null'))) {
									$allow_null_status[$attributeid] = true;
									$d_score_exp = str_replace('v'.$attributeid.'.value','d'.$attributeid.'.value', $v_score_exp);
									$score_ar[] = 'IF(v'.$attributeid.'.value IS NULL,'.$d_score_exp.','.$v_score_exp.')';
								} else {
									$allow_null_status[$attributeid] = false;
									$score_ar[] = $v_score_exp;
								}
							}
						}
					}
				}
			}
		}

		# Now we will tackle any complex queries
		$complex_attributeids = array();
		# First look through each attribute.
		foreach($complex_attribute_queries as $attributeid => $formats) {
			# Get to know this attribute
			$attribute = &$this->get_attribute($attributeid);
			if(!$attribute->id) continue;
			$existing_formats = $attribute->get_type_setting('search_formats');

			# Lets go through each of the different search formats
			# for this attribute.
			foreach($formats as $format => $queries) {
				# Ensure this isn't an illegal format.
				# Formats that start with _ are magic and internal (not for users)
				# so we may come across one here that isn't listed in the format list
				# we'll exempt the magic underscore formats from this rule.
				if(!isset($existing_formats[$format]) && substr($format,0,1) != '_') continue;
				# Loop through each of the queries for this format.
				foreach($queries as $key => $query) {
					if ($query == NULL) {
						$query = "''";
					}
					# Meta attributes are a special case. They return an array of
					# subqueries to do. We will assume for now, that if any of these
					# subqueries match, then the superquery is a match (i.e. all the
					# subqueries are |s.
					$handler = &$attribute->get_type_handler();
					if($handler->settings['sub_type_name'] == 'meta') {
						$submatch_clause_ar = array();

						# Get the subclauses and possibly the score expression.
						$subclauses = $attribute->get_search_sql_clause($query,$format);
						if ($subclauses === false) {
							continue;
						}

						# Loop over the subclauses.
						foreach($subclauses as $subattributeid => $subclause) {
							if ($subclause === false) {
								continue;
							}
							# Now, we need two clauses - one for cases where a value
							# is stored explicitly for an record, and one for when the
							# record uses a category default.
							$d_subclause = str_replace('v'.$subattributeid.'.value','d'.$subattributeid.'.value',$subclause);

							# Combine these together into a clause for our attribute (default and norm val)
							$submatch_clause = '(IF(v'.$subattributeid.'.value IS NULL,'.$d_subclause.','.$subclause.'))';

							# Hold onto this for afterwards.
							$submatch_clause_ar[] = $submatch_clause;

							# Remember which attributes we've done complex searches on, for future reference.
							if(!in_array($subattributeid,$complex_attributeids)) {
								$complex_attributeids[] = $subattributeid;
							}
						}

						# Combine our subclauses into one big megaclause for out match_bit.
						if(count($submatch_clause_ar) > 0) {
							# Hang onto this clause for later. We are going to mark the clause so we can find it potentially again again and again when developing a WHERE clause.
							$complex_match_clauses[$attributeid.'%'.$format.'%'.$key] = '('.implode(' OR ',$submatch_clause_ar).')';
						}
					} else {
						# Okay, normal antimeta-attributes. Lets get our subclause
						$subclause = $attribute->get_search_sql_clause($query,$format,'v'.$attributeid.'.value');
						if ($subclause === false) {
							continue;
						}

						# Lets find out if defaults are being used because if it's not allow null we can speed things up by not doing the if thing and joining with the default table. We check the searched_allow_null_attributeids array first because if we are doing the same attribute again it's quicker to check the array first and then ask the attribute than always asking the attribute something we already know. If the in_array returns true due to the magic of short circuiting it will not ask the attribute
						if ((isset($allow_null_status[$attributeid]) && $allow_null_status[$attributeid]) || (!isset($allow_null_status[$attributeid]) && $attribute->plays_role('allow_null'))) {
							# And the alternate clause for matches on cetegory defautlts
							# when there is no corresponding record value.
							$d_subclause = str_replace('v'.$attributeid.'.value','d'.$attributeid.'.value',$subclause);

							# Combine these together into a clause for our attribute (default and norm val)
							$clause = 'IF(v'.$attributeid.'.value IS NULL,'.$d_subclause.', '.$subclause.')';
							$allow_null_status[$attributeid] = true;
						} else {
							$allow_null_status[$attributeid] = false;
							$clause = '('.$subclause.')';
						}

						# Hang onto this clause for later. We are going to mark the clause so we can find it potentially again again and again when developing a WHERE clause.
						$complex_match_clauses[$attributeid.'%'.$format.'%'.$key] = $clause;

						# Remember which attributes we've done complex searches on, for future reference.
						if(!in_array($attributeid,$complex_attributeids)) {
							$complex_attributeids[] = $attributeid;
						}
					}
				}
			}
		}

		# Revise the $simple_searching bool, since some queries may have failed.
		$simple_searching = (count($searched_attributeids) > 0);
		$searching = ((count($searched_attributeids) + count($complex_attributeids)) > 0);
		# Can't score if we're not doing any simple searching which we may only know by now
		$scoring = ($scoring && $simple_searching);

		# No point sorting only one result
		if(count($restrict_recordids) == 1) {
			if(!$searching) {
				return $restrict_recorids;
			}
			$scoring = 0;
			$sorting = 0;
		}

		# Build the massive expression that should result in the integer
		# where each bit's on/offness represents whether there has been
		# a match or not.
		if($match_count > 0) {
			# Add up all the expressions. Each one should either resolve
			# to 0 or a unique power of two. Hence the result should be
			# a useful INT.
			# We want the BIT OR of this. Since we want a summary of all a record's
			# matches, and we're grouping by record id.
			# Remember, if we're scoring, we need to hold onto this variable.
			$match_expression = implode(' + ',$match_clauses);
		} else {
			$match_expression = 0;
		}

		# Build the score expression from all the subscores.
		if($scoring && count($score_ar)) {
			# Add up all the scores.
			$score_expression = implode(' + ',$score_ar);
		} else {
			$score_expression = 0;
		}

		# Okay, time for sorting.
		$sorted_attributeids = array();
		if($sorting) {
			$sort_clauses        = array();
			$sort_clause_count   = 0;
			$sort_select         = '';
			$sort_order_by       = '';

			# Loop through all the sort_formats
			foreach($sort_formats as $sort_format) {
				if($sort_format == '_score') {
					$sort_order_by .= ', score DESC';
				}

				# Extract the attributeid, and the format
				$attributeid = (int) $sort_format;
				$format = substr(strstr($sort_format,'_'),1);

				# Now, if the format ends with a '<', then we want it
				# in REVERSE order.
				if($format[strlen($format)-1]=='<') {
					$desc = ' DESC';
					$format = substr($format,0,-1);
				} else {
					$desc = '';
				}

				# Check out this attribute.
				$attribute = &$this->get_attribute($attributeid);
				if(!$attribute->id) continue;
				# Remember this about the attribute.
				if(!in_array($attribute->id, $sorted_attributeids)) {
					$sorted_attributeids[] = $attribute->id;
				}

				# Get the sorting clause for the attribute/format.
				$subclause = $attribute->get_search_sql_sort_clause($format,'v'.$attribute->id.'.value');
				if($subclause) {
					# If we have the right attribute, use the clause, otherwiset
					# use '~', you can't get much more alphabetically low than this.
					# However, numeric attributes should have a really large number.
					if($attribute->type == 'number') {
						$pseudomax = '1152921504606846976';
					} else {
						$pseudomax = "'~~~'";
					}
					# Lets find out if defaults are being used because if it's not allow null we can speed things up by not doing the if thing and joining with the default table. We check the searched_allow_null_attributeids array first because if we are doing the same attribute again it's quicker to check the array first and then ask the attribute than always asking the attribute something we already know. If the in_array returns true due to the magic of short circuiting it will not ask the attribute
					if ((isset($allow_null_status[$attribute->id]) && $allow_null_status[$attribute->id]) || (!isset($allow_null_status[$attribute->id]) && $attribute->plays_role('allow_null'))) {
						$allow_null_status[$attribute->id] = true;

						# Replace (v)attributeid.value with (d)attributeid.value to get default sub clause
						$d_subclause = str_replace('v'.$attributeid.'.value','d'.$attributeid.'.value',$subclause);

						# Combine these together into a clause for our attribute (default and norm val)
						$d_sort_expression = '(IF(d'.$attributeid.'.value IS NULL, '.$pseudomax.', '.$d_subclause.'))';
						$sort_select .= ', (IF(v'.$attributeid.'.value IS NULL,'.$d_sort_expression.', '.$subclause.')) as sv'.$sort_clause_count;
					} else {
						$allow_null_status[$attributeid] = false;
						$sort_select .= ', ('.$subclause.') as sv'.$sort_clause_count;
					}

					$sort_order_by .= ', sv'.$sort_clause_count.$desc;
					$sort_clause_count++;
				}
			}
		}

		# Reset $sorting in case none of them worked.
		if($sorting = $sort_clause_count > 0) {
			$sort_order_by = substr($sort_order_by,2);
		}

		# Build the SELECT clause.
		$select = 'SELECT c.recordid';

		# If we're searching (or scoring) we'll need the matches.
		if($simple_searching || $scoring) {
			$select .= ', BIT_OR('.($scoring?'@match_exp:=':'').$match_expression.') AS matches';
		}

		# If we're scoring, we'll need the match COUNT added to the score
		# (which is essentially the superscore - don't forget some matches
		# don't get a score, but they still need to be recognised).
		if($scoring) {
			$select .= ",\n\nSUM(BIT_COUNT(IF(ISNULL(@match_exp),0,@match_exp)) + ".$score_expression.") AS score\n";
		}

		# If sorting, our extra select fields should be ready.
		if($sorting) {
			$select .= $sort_select;
		}

		# Work out which attributes matter here.
		$used_attributeids = array_unique(array_merge($searched_attributeids,$sorted_attributeids, $complex_attributeids));
		$from = 'FROM '.$e->table_name.'_record_to_category c';
		if(count($used_attributeids)) {
			# Combine a whole bunch of tables together to reconstruct the vertual records that we care about
			foreach($used_attributeids as $attributeid) {
				$attribute = &$this->get_attribute($attributeid);
				$table_name = $attribute->get_value_table_name();
				$default_table_name = $attribute->get_default_value_table_name();
				# Value
				$from .= "\nLEFT JOIN ".$table_name.' v'.$attributeid."\t\n ON (c.recordid=v".$attributeid.'.recordid AND v'.$attributeid.'.attributeid='.$attributeid.')';
				# Only join the default table if we know the attribute will need to do default table comparison
				if ($allow_null_status[$attributeid]) {
					# Defaults
					$from .= "\nLEFT JOIN ".$default_table_name.' d'.$attributeid."\t\n ON (d".$attributeid.'.attributeid='.$attributeid.' AND d'.$attributeid.'.categoryid='.$attribute->categoryid.')';
				}
			}
		}

		# Start building the where clause
		$where = 'WHERE c.categoryid IN ('.implode(',',$search_categoryids).')';
		# If the record has to be one in $restrict_recordids we'll take that into account
		if(count($restrict_recordids) > 0) {
			$where .= ' AND c.recordid IN ('.implode(',',$restrict_recordids).')';
		}
		# Now for my give to Notitia Searching. Complex matching
		if (!empty($complex_match_clauses)) {
			$this->complex_rule_narrower($complex_match_clauses, $complex_matching_rules);
			# make sure we having something to search upon
			if (count($complex_matching_rules) > 1) {
				$where .= ' AND ';
				$sub_clause_count = count($complex_matching_rules);
				$where = $this->complex_where_builder($complex_match_clauses, $complex_matching_rules, $sub_clause_count, $where);
			}
		}

		# Group by recordid.
		$group_by = 'GROUP BY c.recordid';
		# Apply the matching logic
		if($simple_searching) {
			$having = '';
			# ANDs must all be matched
			if($a = $logic_matches['&']) $having .= ' AND (matches & '.$a.') = '.$a;
			# No NOTs must be matched
			if($n = $logic_matches['~']) $having .= ' AND (matches & '.$n.') = 0';
			# At least one of the ORs
			if($o = $logic_matches['|']) $having .= ' AND (matches & '.$o.') > 0';
			# Only one of the XORs
			if($x = $logic_matches['^']) $having .= ' AND BIT_COUNT((matches & '.$n.')) = 1';
			if($having) $having = 'HAVING '.substr($having,5);
		}

		# Ordering either byt he score or by the sort formats.
		if($scoring && !$sorting) {
			$order_by = 'ORDER BY score DESC';
		} elseif($sorting) {
			$order_by = 'ORDER BY '.$sort_order_by;
		} else {
			$order_by = '';
		}

		# Make the query, duh.
		$query = "$select\n$from\n$where\n$group_by\n$having\n$order_by";

		# Finito!
		$r = $db->single_column($query);
		if (!is_array($r)) {
			# We must have a bad query throw an error and return an empty array
			report_error(__FILE__,__LINE__,'Notitia search_records has created the following bad query. '.$query." \n_POST\n:".array_contents($_POST)."\n_GET:\n".array_contents($_GET)."\n_SERVER:\n".array_contents($_SERVER)."\nAttribute Queries:\n".array_contents($attribute_queries)."\nSort Formats:\n".array_contents($sort_formats)."\nRestrict Recordids:\n".array_contents($restrict_recordids)."\nComplex Attribute Queries:\n".array_contents($complex_attribute_queries)."\nComplex Matching Rules:\n".array_contents($complex_matching_rules));
			# Return an empty array to maintain function integrity
			$r = array();
		}

		return $r;
	}

	/**
	* Takes in record ids and sort formats and sorts those record ids by performing a mini search
	*
	* @param   array $recordids Simple array of recordids
	* @param   array $sort_formats An ordered array of strings in the form "$attributeid_$sort_format"
	* @access  public
	* @returns array The sorted recordids.
	*/
	function resort_recordids($recordids=array(), $sort_formats=array()) {
		$db = $this->get_db();
		$e  = &$this->get_notitia_system();
		$tree = &$this->get_category_tree();
		if (empty($recordids) || empty($sort_formats)) return $recordids;

		$categoryids = $tree->all_descendantids($this->id);
		array_unshift($categoryids,(int)$this->id);
		$where = 'WHERE c.categoryid IN ('.implode(',',$categoryids).') AND c.recordid IN ('.implode(',',$recordids).')';

		# Group by recordid.
		$group_by = 'GROUP BY c.recordid';
		# Loop through all the sort_formats
		foreach($sort_formats as $sort_format) {
			# Extract the attributeid, and the format
			$attributeid = (int) $sort_format;
			$attribute = &$this->get_attribute($attributeid);
			$format = substr(strstr($sort_format,'_'),1);

			# Now, if the format ends with a '<', then we want it
			# in REVERSE order.
			if($format[strlen($format)-1]=='<') {
				$desc = ' DESC';
				$format = substr($format,0,-1);
			} else {
				$desc = '';
			}

			if($subclause) {
				$d_subclause = str_replace('v'.$attributeid.'.value','d'.$attributeid.'.value',$subclause);
				# If we have the right attribute, use the clause, otherwiset
				# use '~', you can't get much more alphabetically low than this.
				# However, numeric attributes should have a really large number.
				if($attribute->type == 'number') {
					$pseudomax = '1152921504606846976';
				} else {
					$pseudomax = '\'~~~\'';
				}
				$r_sort_expression = $subclause;
				$d_sort_expression = 'IF(d'.$attributeid.'.value IS NULL, '.$pseudomax.', '.$subclause.')';
				# Now, split into two cases, for record values and category defaults.
				$d_sort_expression = str_replace('v'.$attributeid.'.value','d'.$attributeid.'.value',$d_sort_expression);
				$sort_select .= ', MIN(IF(v'.$attributeid.'.value IS NULL, '.$d_sort_expression.', '.$r_sort_expression.')) as sv'.$sort_clause_count;
				$sort_order_by .= ", sv$sort_clause_count$desc";
				$sort_clause_count++;
			}

			# Check out this attribute.
			$attribute = &$this->get_attribute($attributeid);
			if($attribute->id) {
				# Remember this about the attribute.
				$sorted_attributeids[] = $attribute->id;

				# Get the sorting clause for the attribute/format.
				$subclause = $attribute->get_search_sql_sort_clause($format,'v'.$attributeid.'.value');

				$d_subclause = str_replace('v'.$attributeid.'.value','d'.$attributeid.'.value',$subclause);
				if($subclause) {
					$d_subclause = str_replace('v'.$attributeid.'.value','d'.$attributeid.'.value',$subclause);
					# If we have the right attribute, use the clause, otherwiset
					# use '~', you can't get much more alphabetically low than this.
					# However, numeric attributes should have a really large number.
					if($attribute->type == 'number') {
						$pseudomax = '1152921504606846976';
					} else {
						$pseudomax = '\'~~~\'';
					}
					$r_sort_expression = $subclause;
					$d_sort_expression = 'IF(d'.$attributeid.'.value IS NULL, '.$pseudomax.', '.$subclause.')';
					# Now, split into two cases, for record values and category defaults.
					$d_sort_expression = str_replace('v'.$attributeid.'.value','d'.$attributeid.'.value',$d_sort_expression);
					$sort_select .= ', MIN(IF(v'.$attributeid.'.value IS NULL, '.$d_sort_expression.', '.$r_sort_expression.')) as sv'.$sort_clause_count;
					$sort_order_by .= ", sv$sort_clause_count$desc";
				}
			}
		}
		$sort_order_by = substr($sort_order_by,2);
		$order_by = 'ORDER BY '.$sort_order_by;

		# Build the SELECT clause.
		$select = 'SELECT c.recordid'.$sort_select;

		# Work out which attributes matter here.
		$used_attributeids = array_unique($sorted_attributeids);

		# Work out which attributes matter here.
		$used_attributeids = array_unique(array_merge($searched_attributeids,$sorted_attributeids, $complex_attributeids));
		$from = 'FROM '.$e->table_name.'_record_to_category c';
		if(count($used_attributeids)) {
			# Combine a whole bunch of tables together to reconstruct the vertual records that we care about
			foreach($used_attributeids as $attributeid) {
				$attribute = &$this->get_attribute($attributeid);
				$table_name = $attribute->get_value_table_name();
				$default_table_name = $attribute->get_default_value_table_name();
				# Value
				$from .= ' LEFT JOIN '.$table_name.' v'.$attributeid.' ON (c.recordid=v'.$attributeid.'.recordid AND v'.$attributeid.'.attributeid='.$attributeid.')';
				# Defaults
				$from .= ' LEFT JOIN '.$default_table_name.' d'.$attributeid.' ON (d'.$attributeid.'.attributeid='.$attributeid.')';
			}
		}

		# Make the query, duh.
		$query = "$select\n$from\n$where\n$group_by\n$order_by";

		#Finito!
		$r = $db->single_column($query);

		return $r;
	}

	 ###############################################################
	# Returns an array of all the attributes linked to this category
	function associated_attributes() {
		$db = $this->get_db();
		$e  = &$this->get_notitia_system();
		return $db->single_column("SELECT attributeid FROM $e->table_name"."_attribute WHERE categoryid='$this->id'");
	}

	 ###############################################################
	# Returns an array of all the records linked to this category
	function associated_variety_sets() {
		$db = $this->get_db();
		$e  = &$this->get_notitia_system();
		return $db->single_column("SELECT variety_setid FROM $e->table_name"."_variety_set WHERE categoryid='$this->id'");
	}

	 #########################################
	# Associates the category with a record.
	function associate_with_record($recordid) {
		$record = &$this->get_record($recordid);
		if ($record->id) {
			if(in_array($this->id,$record->categoryids)) return;

			$tree = &$this->get_category_tree();
			$db   = &$this->get_db();
			$n    = &$this->get_notitia_system();

			# Update the database
			$db->insert("INSERT INTO $n->table_name"."_record_to_category (recordid,categoryid) VALUES('$recordid','$this->id')");

			$old_attributeids = $record->all_attributeids();
			$new_attributeids = array_keys($tree->get_context_attributes($this->id));
			$gained_attributeids = array_diff($new_attributeids,$old_attributeids);
			foreach($gained_attributeids as $attributeid) {
				$attribute = &$this->get_attribute($attributeid);
				if ($attribute->id) {
					$attribute->associate_with_record($recordid, array($this->id));
				}
			}

			$record->clear_cache();
			$record->load();

			$return_msg =  "Record '".$record->name()."' now associated with category '$this->name'.";
		} else {
			$return_msg = 'Record does not exist';
		}
		return $return_msg;
	}

	 ############################################
	# DisAssociates the category with a record.
	function disassociate_with_record($recordid) {
		$record = &$this->get_record($recordid);

		if(!in_array($this->id,$record->categoryids)) return;

		$tree = &$this->get_category_tree();
		$db   = &$this->get_db();
		$n    = &$this->get_notitia_system();

		# Update the database
		$db->delete("DELETE FROM $n->table_name"."_record_to_category WHERE recordid='$recordid' AND categoryid='$this->id'");

		$remaining_categoryids = array_diff($record->categoryids,array($this->id));
		$remaining_categoryids = array_merge($remaining_categoryids, $tree->bulk_ancestorids($remaining_categoryids));

		$old_attributeids = $record->all_attributeids();
		$new_attributeids = $db->single_column("SELECT attributeid FROM $n->table_name"."_attribute WHERE categoryid IN (0,'".implode("','", $remaining_categoryids)."') AND recordid=0");

		$lost_attributeids = array_diff($old_attributeids,$new_attributeids);
		foreach($lost_attributeids as $attributeid) {
			$attribute = &$this->get_attribute($attributeid);
			$attribute->disassociate_with_record($recordid);
		}

		$record->clear_cache();
		$record->load();

		return "Record '".$record->name()."' now disassociated with category '$this->name'.";
	}

	 #######################################################
	# Creates a new record and attaches it to this category
	function create_record(&$values) {
		$n = &$this->get_notitia_system();
		$class = get_class($n).'_record';
		$record = new $class();
		if($recordid = $record->create($this->id, $values)) {
			return $recordid;
		}
		return 0;
	}


	 ########################################
	# Gets a references to the parameter set
	function &get_pset() {
		$pset = &$this->temp['parameter_set'];
		if(get_class($pset) !== "parameter_set") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/parameter_set.inc");
			$e = &$this->get_notitia_system();
			$pset = new Parameter_Set(get_class($this),"$e->xtra_path/category.pset",$this->parameters, $this);
		}
		return $pset;
	}

	 ################################################################
	# Returns an array($id => $name) of the attributes associated with
	# this record (shared and local) that generate
	function &get_variety_generating_attribute_names() {
		$e = &$this->get_notitia_system();
		return $e->get_variety_generating_attribute_names($this->id);
	}


	 ################################################################
	# Returns an array($id => $name) of the attributes associated with
	# this record (shared and local) that generate
	function &get_variable_attribute_names() {
		$e = &$this->get_notitia_system();
		return $e->get_variable_attribute_names($this->id);
	}


	 ###############################################################
	# Returns an array of attributeid => array(variety_setid, ...)
	# so that you know which attirbute is affected by which variety sets
	function &get_attribute_variety_setids() {
		if(!isset($this->temp['attribute_variety_setids'])) {
			$e    = &$this->get_notitia_system();
			$db   = &$this->get_db();
			$tree = &$this->get_category_tree();

			$attributes = &$tree->get_context_attributes($this->id);

			$q = "SELECT vsva.attributeid, vsva.variety_setid
				  FROM $e->table_name"."_variety_set vs,
				  $e->table_name"."_variety_set_varied_attribute vsva
				  WHERE vsva.variety_setid=vs.variety_setid
				  AND vsva.attributeid IN ('".implode("','",array_keys($attributes))."')
				  AND vs.categoryid IN (0,'$this->id,".implode("','",$tree->ancestorids($this->id))."')
				  ";

			$this->temp['attribute_variety_setids'] = $db->associative_column($q);
		}
		return $this->temp['attribute_variety_setids'];
	}


	 ###########################################################
	# Gets handy data importing and exporting modules
	function &get_data_import($codename) {
		$e = &$this->get_notitia_system();
		return $e->get_data_import($codename,$this->id);
	}
	function &get_data_export($codename) {
		$e = &$this->get_notitia_system();
		return $e->get_data_export($codename,$this->id);
	}
	function &get_data_perform($codename) {
		$e = &$this->get_notitia_system();
		return $e->get_data_perform($codename,$this->id);
	}


	 ########################################################
	# Returns an array of the the permission codes and
	# their descriptions, not including superuser ones
	function get_permissions() {
		$e = &$this->get_notitia_system();
		return array_merge(
			$e->get_permissions(),
			array(
				'P' => ' Edit Records',
				'A' => ' Add Records',
				'R' => ' Remove Records',
				'Z' => ' Delete This Category'
			)
		);
	}

	 ########################################################
	# Returns an array of the the permission codes and
	# their descriptions, includeing the superuser ones
	function get_all_permissions() {
		return array_merge(
			array("M" => "Category Master"),
			$this->get_permissions()
		);
	}

	 ##############################################
	# Returns an associative array of access groups
	# and individual staff members which may be
	# granted access to things
	function get_grant_entities() {
		$e = &$this->get_notitia_system();
		$staff = &$this->get_value("staff");
		if(!$staff) $staff = array();
		return $e->get_grant_entities($staff);
	}

	 ##########################################################
	# Checks whether the currect user has a certain permission
	# in the root category
	function check_permission($type = "") {
		$tree = &$this->get_category_tree();
		return $tree->check_permission($this->id,$type);
	}
	function check_parent_permission($type = "") {
		$tree = &$this->get_category_tree();
		return $tree->check_permission($this->parentid,$type);
	}


	 ################################################
	# Is this user a superuser or not? A superuser
	function superuser() {
		if (superuser("web")) return true;
		if ($this->siteid) {
			$site = &$this->get_site($this->siteid);
			return $site->admin_access();
		}
		return false;
	}

	 ########################################
	# Is the current user a category master?
	function category_master() {
		return $this->superuser() || $this->check_permission('M');
	}

	 ####################################################
	# Is the user allowed to browse around this category?
	# (Backend)
	function read_access() {
		return $this->superuser() || $this->check_permission('MV');
	}

	 ###########################################################
	# Is the user allowed to create, edit or delete attributes
	function create_attribute_access() {
		return $this->superuser() || $this->check_permission('MC');
	}
	function edit_attribute_access() {
		return $this->superuser() || $this->check_permission('ME');
	}
	function delete_attribute_access() {
		return $this->superuser() || $this->check_permission('MD');
	}

	 ###########################################################
	# Is the user allowed to create, edit or delete records
	function create_record_access() {
		return $this->superuser() || $this->check_permission("MA");
	}
	function edit_record_access() {
		return $this->superuser() || $this->check_permission("MP");
	}
	function delete_record_access() {
		return $this->superuser() || $this->check_permission("MR");
	}

	####################################################
	# Is the user allowed to create new subcategories
	function new_subcategory_access() {
		return $this->superuser() || $this->check_permission("MS");
	}

	 #################################################
	# Does the current user have permission to change
	# the name (or description) of the curent category?
	function rename_access() {
		return $this->category_master()
			|| (
				$this->check_parent_permission("MS") # Must be able to add subcaegories to parent
				&& $this->check_permission("MZ")     # and delete this category
			);
	}

	 #################################################
	# Does the current user have permission to delete
	# this category?
	function delete_access() {
		return $this->superuser() || $this->check_permission($this->id,"MZ");
	}

	#####################################################

	function get_backend_href() {
		$e = &$this->get_notitia_system();
		return $e->get_backend_href()."&edit_categoryid=$this->id";
	}

	 ##############################################
	# Prints a backend, for editing the attribute
	function print_backend() {
		$session = &get_mysource_session();

		$e = &$this->get_notitia_system();

		$backend = &$this->get_backend();
		$backend->set_heading("Edit Category - $backend->heading","xtra");

		$tree = &$this->get_category_tree();
		$backend->set_name($this->name);
		$backend->set_id_string($this->id);

		if (!$this->read_access()) {
			$session->login_screen($backend->title,"You must be logged in and have permission to explore the <i>$this->name</i> $e->name category in order to proceed.");
		}

		 #############
		# Okay now...
		$code = "web_extention_{$e->codename}_category_$this->id";
		if($this->parentid) {
			$lineage = $tree->ancestorids($this->id);
			foreach($lineage as $ancestorid) {
				$text = "> <a href=\"".$e->get_backend_href()."&edit_categoryid=$ancestorid\">".$tree->name($ancestorid)."</a> $text";
			}
			$backend->set_context_bar($code,"<b>Category Lineage:</b> $text");
		}
		$pset = &$this->get_pset($this->id);
		$href = $this->get_backend_href();
		$backend->set_tab($code,$href,"Category: $this->name","$this->description","page");
		$backend->set_active_tab($code);
		$pset->set_tabs($backend,$code,$href);

		# But..
		$edit_attributeid = $_GET['edit_attributeid'];
		$edit_recordid = $_GET['edit_recordid'];
		if ($edit_recordid) {
			$record = &$this->get_record($edit_recordid);
			$record->print_backend();
			return;
		} elseif ($edit_attributeid) {
			$attribute = &$this->get_attribute($edit_attributeid);
			$attribute->print_backend();
			return;
		}

		# Or..
		$data_import = $_GET['data_import'];
		$data_export = $_GET['data_export'];
		$data_perform = $_GET['data_perform'];
		if($data_import) {
			$data_importer = &$this->get_data_import($data_import);
			if (!$data_importer) $pset->print_backend($backend);
			$data_importer->print_backend();
			return;
		} elseif($data_export) {
			$data_exporter = &$this->get_data_export($data_export);
			if (!$data_exporter) $pset->print_backend($backend);
			$data_exporter->print_backend();
			return;
		} elseif($data_perform) {
			$data_performer = &$this->get_data_perform($data_perform);
			if (!$data_performer) $pset->print_backend($backend);
			$data_performer->print_backend();
			return;
		}

		# Dupin'
		$dupe_category = $_GET['dupe_category'];
		if($dupe_category) {
			$parentid = $this->parentid;
			$backend->add_message($this->dupe());
			$backend->set_relocation("$href&edit_categoryid=$parentid");
			$backend->print_header();
			return;
		}
		$backend->set_toolbar_button("dupe","javascript:if(confirm('Are you sure you wish to duplicate this category and all sub categories? NOTE: This dupe will not dupe all of the category settings')){location='".$href."&dupe_category=1';}","Dupe This Category","dupe");

		# Deletin'
		if($this->delete_access()) {
			$delete_category = $_GET['delete_category'];
			if($delete_category) {
				$parentid = $this->parentid;
				$backend->add_message($this->delete());
				if(!$this->id) {
					$backend->set_relocation("$href&edit_categoryid=$parentid");
					$backend->print_header();
					return;
				}
			}
			$backend->set_toolbar_button("delete","javascript:if(confirm('Are you sure you wish to delete this category?')){location='".$href."&delete_category=1';}","Delete This Category","delete");
		}

		if($pset->process($backend)) {
			$backend->add_message($this->save());
		}

		$pset->print_backend($backend);
	}


	 #########################
	# Prints the category tree
	function print_category_tree() {
		$e = &$this->get_notitia_system();
		$e->print_category_tree($this->id);
	}

	 #########################
	# Prints the category tree
	function process_category_tree_interface() {
		$e = &$this->get_notitia_system();
		$e->process_category_tree_interface($this->id);
	}


	 ########################################################
	# Returns true if there are attributes in this context
	# that plays the backend_searchable role
	function searchable_attributes_exist() {
		$tree = &$this->get_category_tree();
		$attributes = &$tree->get_context_attributes($this->id);
		foreach($attributes as $attributeid => $name) {
			$attribute = &$this->get_attribute($attributeid);
			if($attribute->plays_role('backend_searchable')) return true;
		}
	}


	 #######################################################
	# Prints a backend interface for searching the category
	function print_backend_record_search($queries='') {
		$tree = &$this->get_category_tree();
		$attributes = &$tree->get_context_attributes($this->id);
		if($queries==='') $queries = &$this->parameters['backend_search_queries'];
		?><table><?
		$sort_by_options = array(''=>'Relevance');
		foreach($attributes as $attributeid => $name) {
			$attribute = &$this->get_attribute($attributeid);
			if($attribute->plays_role('backend_searchable')) {
				$role = &$attribute->get_role_handler('backend_searchable');
				$format = $role->get_value('format');
				if(isset($queries[$attributeid])) $query = $queries[$attributeid][$format][0];
				else $query = '';
				?><tr><td valign=top class=field><?=$name?></td><td valign=top><?=$attribute->print_search_field('c'.$this->id.'_a_'.$attribute->id.'_search',$query,$format)?></td></tr><?
			}
			foreach($attribute->get_type_setting('sort_formats') as $format => $format_label) {
				$sort_by_options[$attributeid.'_'.$format] = "$name - $format_label";
			}
		}
		?>
			<tr><td valign=top class=field>Sort by</td><td valign=top><?=combo_box('search_sort_by',$sort_by_options,$this->parameters['backend_search_sort_by'])?></td></tr>
		</table>The record list will be limited by your search criteria.<br>If you search returns only one record you will be taken directly to that record.<?
	}


	 ##########################################################
	# Processes a backend interface for searching the category
	function process_backend_record_search() {
		$tree = &$this->get_category_tree();
		$attributes = &$tree->get_context_attributes($this->id);
		unset($this->parameters['backend_search_queries']);
		foreach($attributes as $attributeid => $name) {
			$attribute = &$this->get_attribute($attributeid);
			if($attribute->plays_role('backend_searchable')) {
				$role = &$attribute->get_role_handler('backend_searchable');
				$format = $role->get_value('format');
				$query = $attribute->process_search_field('c'.$this->id.'_a_'.$attribute->id.'_search',$format);
				if($query) {
					$this->parameters['backend_search_queries'][$attributeid][$format] = array($query);
				}
			}
		}
		$this->parameters['backend_search_sort_by'] = $_REQUEST['search_sort_by'];
	}


	 ##################################################
	# Clears any backend search criteria there might be
	function clear_backend_search_queries($t) {
		if($t) $this->parameters['backend_search_queries'] = array();
	}


	 ####################################################################
	# Prints an interface for quick-editing all the associated records
	function print_record_summary($link,$readonly) {
		$db   = &$this->get_db();
		$tree = &$this->get_category_tree();
		$e    = &$this->get_notitia_system();
		$backend = &$this->get_backend();
		if(!$link) {
			$link = $e->get_backend_href()."&edit_categoryid=$this->id&edit_recordid=";
		}

                if (!$this->searchable_attributes_exist()) {    // no attribs can be searched upon!
                        $this->clear_backend_search_queries(1); // clear any hanging queries
                        $this->save();
                }

		$queries = &$this->get_value('backend_search_queries');
		$searching = count($queries) > 0;

		if($this->get_value('backend_search_sort_by')) {
			$sort_list = array($this->get_value('backend_search_sort_by'));
		}

		if($searching) {
			$all_recordids = $this->search_records($queries,$sort_list, (bool) $this->get_value('backend_search_sub'),'AND');
			if(count($all_recordids) == 1) {
				# One result! Woohoo! send em off to it
				$this->clear_backend_search_queries(1);
				$this->save();
				$record = &$this->get_record(reset($all_recordids));
				echo js('document.location="'.$record->get_backend_href().'";');
			}
		} else {
			$all_recordids = $this->search_records(array(),$sort_list);
		}
		if(!count($all_recordids) > 0) {
			if($searching) {
				echo('There are no records matching the above search criteria.');
			} else {
				echo('There are no records associated with this category.');
			}
			return;
		}

		# For duping records
		?><input type=hidden name="record_summary_<?=$this->id?>_dupe_recordid"><?

		# Only the records for this page.
		$recordids = array();
		$ppp = max(10,$this->get_value('records_per_page'));
		$record_page = $_POST['record_page'];
		$max_record_page = ceil(count($all_recordids)/$ppp);
		$record_page = min($max_record_page,max(1,$record_page));
		$i = 0;
		reset($all_recordids);
		while(1) {
			if(!($recordid = current($all_recordids)) || $i >= $ppp * $record_page) break;
			if($i >= $ppp * ($record_page-1)) {
				$recordids[] = $recordid;
			}
			$i++;
			next($all_recordids);
		}

		$attributes = &$tree->get_context_attributes($this->id);
		$summary_attributes = $db->associative_array("SELECT attributeid, parameters FROM $e->table_name"."_attribute_role WHERE attributeid IN ('".implode("','",array_keys($attributes))."') AND role='summary'");

		$attribute_variety_setids = &$this->get_attribute_variety_setids();

		# A handy javscript function
		echo js("
			function variation_tt(a_name,type,defaultv){var defaultstr=' ';if(defaultv!=null){defaultstr=' of:<br><br>'+defaultv+'<br><br>...';}show_tooltip(event, a_name + ' Variation','<span class=smallprint>The value in this field varies the default value'+defaultstr+'using the method: <i>'+type+'</i></span>',300);return true;}");

		echo("<table cellpadding=2 cellspacing=1 border=0 bgcolor=black width=100%>");
		echo("<tr bgcolor=#cccccc>");
		echo("<td class=field align=center valign=bottom rowspan=2>ID</td>");

		$total_colspan = 1;
		foreach($attributes as $attributeid => $name) {
			if(!isset($summary_attributes[$attributeid])) continue;
			$summary_attributes[$attributeid] = unserialize($summary_attributes[$attributeid]);
			$attribute = &$this->get_attribute($attributeid);
			$p = &$summary_attributes[$attributeid];
			foreach($attribute_variety_setids[$attributeid] as $variety_setid) {
				$variety_set = &$this->get_variety_set($variety_setid);
				# Let's work out the variety information first
				foreach($recordids as $recordid) {
					$names = $variety_set->get_variety_names($this->id,$recordid);
					$max_variety_width[$attributeid] = max($max_variet_width[$attributeid],count($names));
					$variety_names[$attributeid][$variety_setid][$recordid] = $names;
				}
			}
			$colspan = 1;
			if($p['show_varieties'] && count($attribute_variety_setids[$attributeid])) {
				# Hnady function for displaying information about a variation
				if(!$p['hide_default']) {
					echo js("function variation_tt_$attributeid(type){return variation_tt('".addslashes($attribute->name)."',type);}");
					if($p['show_varieties'] == 'H') $variety_header .= "<td class=smallprint>Default</td>";
				} else {
					echo js("function variation_tt_$attributeid(type,defaultv){return variation_tt('".addslashes($attribute->name)."',type,defaultv);}");
				}
				if($p['show_varieties'] == 'H') {
					for(reset($variety_names[$attributeid]); NULL !== ($vsid = key($variety_names[$attributeid])); next($variety_names[$attributeid])) {
						$variety_set = &$this->get_variety_set($vsid);
						$names = $variety_set->get_variety_names($this->id);
						foreach($names as $coord => $variety_name) {
							$variety_header .= "<td class=smallprint><a onmouseover=\"show_tooltip(event,'".addslashes($variety_set->name())."','<span class=smallprint>".addslashes($variety_name)."</span>',300);return true;\" onmouseout=\"hide_tooltip();window.status='';\" class=smallprint>$colspan</a></td>";
							$colspan++;
						}
						for($i = $colspan; $i < $max_variety_width[$attributeid]; $i++)  {
							$variety_header .= "<td class=smallprint><span class=smallprint>$colspan</span></td>";
							$colspan++;
						}
					}
				}
			}
			echo("<td class=field align=center valign=bottom colspan=$colspan ".(($colspan>1)?"":"rowspan=2")."><a href=\"".$attribute->get_backend_href()."\">$name</a></td>");
			$total_colspan += $colspan;
		}
		if($this->check_permission('MR')) {
			?>
			<SCRIPT language=JavaScript type=text/javascript>
			function toggle_all(max) {
				for (var i = 0; i < max; i++) {
					var element = document.getElementById(i);
					if (element) {
						element.checked = !element.checked;
					}
				}
			}
			</SCRIPT>
			<?
			echo '<td class="field" align="center" valign="bottom" rowspan="2">[Rmv]<br /><input name="check_all"  type="checkbox" onClick="toggle_all('.$ppp.');"</input></td>';
			$total_colspan++;
		}
		echo("</tr>");
		echo("</tr>");

		# Print out another table row of headers for any varieties.
		echo("<tr bgcolor=#cccccc>$variety_header</tr>");

		$record_count=0;
		foreach($recordids as $recordid) {
			$record = &$this->get_record($recordid);
			$href = "$link$recordid";
			?><tr bgcolor=#bbbbbb><td class=data align=right valign=top><a href="<?=$href?>"><?=$recordid?></a><?
			if(!$readonly) {
				?>&nbsp;<a href="javascript:document.edit.record_summary_<?=$this->id?>_dupe_recordid.value='<?=$recordid?>';document.edit.action.value='Commit';document.edit.submit();"><img src="images/icons/dupe.gif" width=15 height=15 border=0 alt="Duplicate this record."></a><?
			}
			?></td><?
			$record_prefix = "prodsum_p_$recordid"."_";
			foreach($attributes as $attributeid => $name) {
				if(!isset($summary_attributes[$attributeid])) continue;
				$attribute = &$this->get_attribute($attributeid);
				$p = &$summary_attributes[$attributeid];
				$attribute_prefix = $record_prefix."a_$attributeid"."_";
				$editable = (!$readonly && $p['editable'] && $this->edit_record_access());
				$align = $attribute->get_type_setting("preferred_alignment");
				if($p['show_varieties'] == 'V') {
					echo("<td bgcolor=#999999><table cellpadding=1 cellspacing=1 border=0 width=100% height=100%><tr bgcolor=#bbbbbb>");
				}
				if(!$p['hide_default'] || !count($attribute_variety_setids[$attributeid])) {
					if($p['show_varieties'] == 'V') {
						echo("</tr><tr bgcolor=#bbbbbb><td><a onmouseover=\"show_tooltip(event, 'Default','No Variety',150);return true;\" onmouseout=\"hide_tooltip();window.status='';\" class=smallprint>D</a></td>");
					}
					echo("<td class=data valign=top align=\"$align\">");
					if($editable) {
						echo("<input type=hidden name=editable_attributeids[$recordid][$attributeid][def] value=1>");
						$attribute->print_concise_field($attribute_prefix."def_", $record->get_attribute_value($attributeid));
					} else {
						if($p['format'] == '_default_') {
							$attribute->paint($record->get_attribute_value($attributeid));
						} else {
							$attribute->paint($record->get_attribute_value($attributeid), $p['format']);
						}
					}
					echo("</td>");
					echo js("function variation_tt_$attributeid"."_$recordid(type){return variation_tt_$attributeid(type);}");
				} else {
					echo js("function variation_tt_$attributeid"."_$recordid(type){return variation_tt_$attributeid(type,'".addslashes($record->get_attribute_formatted_value($attributeid))."');}");
				}
				if($p['show_varieties'] && count($attribute_variety_setids[$attributeid])) {
					$v_no = 0;
					for(reset($variety_names[$attributeid]); NULL !== ($vsid = key($variety_names[$attributeid])); next($variety_names[$attributeid])) {
						$variety_set = &$this->get_variety_set($vsid);
						$names = &$variety_names[$attributeid][$vsid][$recordid];
						foreach($names as $coord => $variety_name) {
							$v_no++;
							if($p['show_varieties'] == 'V') {
								echo("</tr><tr bgcolor=#bbbbbb><td><a onmouseover=\"show_tooltip(event,'".addslashes($variety_set->name())."','<span class=smallprint>".addslashes($variety_name)."</span>',300);return true;\" onmouseout=\"hide_tooltip();window.status='';\" class=smallprint>$v_no</a></td>");
							}
							echo("<td class=data valign=top align=\"$align\">");
							if($editable) {
								echo("<input type=hidden name=editable_attributeids[$recordid][$attributeid][$vsid][] value=$coord>");
								$attribute->print_concise_field($attribute_prefix."var_$vsid"."_$coord"."_", $record->get_raw_variety_value($attributeid,$vsid,$coord));
								$type = $record->get_raw_variety_type($attributeid,$vsid,$coord);
								$variation_types = &$attribute->get_type_setting("variation_types");
								$type = $variation_types[$type];
								if(!$type) $type = "UNCHANGED";
								# Let them know which method is being employed to vary the default
								echo "<span class=fineprint><a onmouseover=\"return variation_tt_$attributeid"."_$recordid('".addslashes($type)."')\" onmouseout=\"hide_tooltip();window.status='';\" class=smallprint>*</a></span>";
							} else {
								if($p['format'] == '_default_') {
									$attribute->paint($record->get_variety_value($attributeid,array($vsid=>array($coord))));
								} else {
									$attribute->paint($record->get_variety_value($attributeid,array($vsid=>array($coord))),$p['format']);
								}
							}
							echo("</td>");
						}
					}
				}
				if($p['show_varieties'] == 'V') {
					echo("</tr></table></td>");
				}
			}
			if($this->check_permission('MR')) {
				echo '<td class="data" valign="top" align="center"><input type="checkbox" id="'.$record_count.'" name="c_'.$this->id.'_remove_recordids[]" value="'.$record->id.'"></td>';
			}
			$record_count++;
			echo("</tr>");
		}
		if($max_record_page > 1) {
			echo("<tr bgcolor=#cccccc><td colspan=$total_colspan valign=top align=center><input type=hidden name=record_page value=$record_page><table width=100% cellpadding=1 cellspacing=0 border=0><tr>");
			echo("<td valign=top class=data>".(($record_page<=1)?'&lt;&lt;':"<a href=\"#\" onclick=\"document.edit.record_page.value='".($record_page-1)."';document.edit.submit();\">&lt;&lt;</a>")."</td><td valign=top align=center class=data>");
			for($pg = 1; $pg <= $max_record_page; $pg++) {
				if($pg == $record_page) echo("$pg ");
				else {
					echo("<a href=\"#\" onclick=\"document.edit.record_page.value='$pg';document.edit.submit();\">$pg</a> ");
				}
			}
			echo("</td><td valign=top align=right class=data>".(($record_page>=$max_record_page)?'&gt;&gt;':"<a href=\"#\" onclick=\"document.edit.record_page.value='".($record_page+1)."';document.edit.submit();\">&gt;&gt;</a>")."</td>");
			echo("</tr></table></td></tr>");
		}
		echo("</table>");


	}


	 ######################################################################
	# Processes an interface for quick-editing all the associated records
	function process_record_summary() {
		if(!$this->edit_record_access()) return;
		$editable_attributeids = $_POST['editable_attributeids'];
		$db   = &$this->get_db();
		$tree = &$this->get_category_tree();
		$e    = &$this->get_notitia_system();
		$ms = array();
		$attributes = &$tree->get_context_attributes($this->id);
		$summary_attributes = $db->associative_array("SELECT attributeid, parameters FROM $e->table_name"."_attribute_role WHERE attributeid IN ('".implode("','",array_keys($attributes))."') AND role='summary'");

		foreach($editable_attributeids as $recordid => $attributeids) {
			$record = &$this->get_record($recordid);
			if(!$record->id) continue;
			$record_prefix = "prodsum_p_$recordid"."_";
			foreach($attributeids as $attributeid => $variety_setids) {
				$attribute = &$this->get_attribute($attributeid);
				$attribute_prefix = $record_prefix."a_$attributeid"."_";
				foreach($variety_setids as $variety_setid => $coords) {
					if($variety_setid == 'def') { # The default value!
						$m = $record->process_field($attributeid,$attribute_prefix."def_");
						if($m) $ms[] = $m;
					} else {
						foreach($coords as $coord) {
							$m = $record->process_variety_field($attributeid,$variety_setid,$coord,$attribute_prefix."var_$variety_setid"."_$coord"."_");
							if($m) $ms[] = $m;
						}
					}
				}
			}
		}

		# Disaccociate records (and delete them if this is their only category).
		if($this->check_permission('MR')) {
			$remove_recordids = $_REQUEST['c_'.$this->id.'_remove_recordids'];
			foreach($remove_recordids as $recordid) {
				$record = &$this->get_record($recordid);
				if(!$record->id) continue;
				$ms[] = $this->disassociate_with_record($recordid);
				if(count($record->categoryids) < 1) {
					$ms[] = $record->delete();
				}
			}
		}

		# Dupe the record, if such as been asked for
		if($this->check_permission('MC') && ($recordid = $_POST["record_summary_{$this->id}_dupe_recordid"]) && in_array($recordid, $this->associated_records())) {
			$record = &$this->get_record($recordid);
			if($new_recordid = $record->dupe($this->id)) {
				$ms[] = "Record $recordid duped to new record $new_recordid.";
			}
		}

		return implode("\n",$ms);
	}

	 #########################################################################
	# Gives the user the option of associating the "active" record with the
	# current category, if they're allowed.
	function print_associate_active_record_option() {
		$session = &get_mysource_session();
		$e = &$this->get_notitia_system();
		if(($active_recordid = &$session->get_var('active_'.get_class($e).'_record')) && !in_array($active_recordid,$this->associated_records())) {
			echo("<input type=checkbox name=associate_existing_recordid value=$active_recordid>");
			$record = &$this->get_record($active_recordid);
			echo("Associate the active record (<a href=\"".$record->get_backend_href()."\">".$record->name()."</a>) with this category.");
		} else {
			echo("To associate an existing record with this category, make that record the <i>active record</i> by opening it, and then return here.");
		}
	}

	 #########################################################################
	# Gives the user the option of associating the "active" record with the
	# current category, if they're allowed.
	function process_associate_active_record_option() {
		$e = &$this->get_notitia_system();
		$associate_existing_recordid = $_POST['associate_existing_recordid'];
		if($associate_existing_recordid && !in_array($active_recordid,$this->associated_records())) {
			return $this->associate_with_record($associate_existing_recordid);
		}
	}

	 #################################################################
	# Prints an interface for creating a new record in this category
	function print_new_record_form() {
		$db   = &$this->get_db();
		$tree = &$this->get_category_tree();
		$e    = &$this->get_notitia_system();
		$backend = &$this->get_backend();

		echo '<input type="checkbox" name="new_record" value="1"> Yes, I want to create a new record.';

		$attributes = &$tree->get_context_attributes($this->id);
		$required_attributeids = $db->single_column("SELECT attributeid FROM $e->table_name"."_attribute_role WHERE attributeid IN ('".implode("','",array_keys($attributes))."') AND role='required'");
		foreach($attributes as $attributeid => $name) {
			if(!in_array($attributeid,$required_attributeids)) continue;
			$backend->open_field($name,"top");
			$attribute = &$this->get_attribute($attributeid);
			$attribute->print_field("new_record_$attributeid".'_',$attribute->get_default_value($this->id));
		}
	}

	 #############################################
	# Processes the input from a new record form
	function process_new_record_form() {
		$new_record = $_POST['new_record'];
		if(!$new_record) return;
		$values = array();
		$db     = &$this->get_db();
		$tree   = &$this->get_category_tree();
		$e      = &$this->get_notitia_system();
		$attributes = &$tree->get_context_attributes($this->id);
		$required_attributeids = $db->single_column("SELECT attributeid FROM $e->table_name"."_attribute_role WHERE attributeid IN ('".implode("','",array_keys($attributes))."') AND role='required'");
		foreach($attributes as $attributeid => $name) {
			if(!in_array($attributeid,$required_attributeids)) continue;
			$attribute = &$this->get_attribute($attributeid);
			$values[$attributeid] = "";
			if($m  = $attribute->process_field("new_record_$attributeid"."_",$values[$attributeid])) {
				$ms[] = $m;
			}
		}
		if($recordid = &$this->create_record($values)) {
			$record = &$this->get_record($recordid);
			$ms[] = "New record '".$record->name()."' created.";
		}
		return implode("\n",$ms);
	}

	 ######################################################
	# Prints a list of the inherited attribues in a context
	function print_inherited_variety_set_summary() {
		$e = &$this->get_notitia_system();
		$e->print_inherited_variety_set_summary($this->id,0);
		return;
	}

	 ########################################################
	# Prints an editable summary of the variety sets defined
	# in a particular context
	function print_variety_set_summary() {
		$e = &$this->get_notitia_system();
		$e->print_variety_set_summary($this->id,0);
		return;
	}


	 ############################################################
	# Processes an editable summary of the variety sets defined
	# in a particular context
	function process_variety_set_summary() {
		$e = &$this->get_notitia_system();
		return $e->process_variety_set_summary($this->id,0);
	}


	 ###################################################
	# ALlows the user to create variety sets for this
	# category.
	function process_new_variety_set() {
		$option_attributeids = $this->get_value("new_variety_set_option_attributeids");
		$varied_attributeids = $this->get_value("new_variety_set_varied_attributeids");
		unset($this->parameters["new_variety_set_option_attributeids"]);
		unset($this->parameters["new_variety_set_varied_attributeids"]);
		$e = &$this->get_notitia_system();
		if($variety_setid = $e->create_variety_set($this->id,0,$option_attributeids,$varied_attributeids)) {
			$variety_set = &$this->get_variety_set($variety_setid);
			return "New variety set '".$variety_set->name()."' created.";
		}
	}


	 ################################################
	# Prints a summary of the inherited attributes
	# for the category.
	function print_inherited_attribute_summary() {
		$e = &$this->get_notitia_system();
		return $e->print_inherited_attribute_summary($this->id);
	}


	 ########################################################
	# Prints an interface where inherited variables can have
	# their default values overriden for this category
	function print_attribute_override_interface() {
		$tree = &$this->get_category_tree();
		$attributes = &$tree->get_context_attributes($this->id);
		foreach($attributes as $attributeid => $name) {
			$attribute = &$this->get_attribute($attributeid);
			if($attribute->categoryid == $this->id) {
				unset($attributes[$attributeid]);
				continue;
			}
			if(isset($attribute->defaults[$this->id])) {
				$editable_attributeids[] = $attributeid;
				unset($attributes[$attributeid]);
				continue;
			}
			if($attribute->plays_role('force_null')) {
				$role = &$attribute->get_role_handler('force_null');
				if($role->get_value('strict')) {
					unset($attributes[$attributeid]);
					continue;
				}
			}
		}
		echo combo_box("new_override_attributeid",(array(""=>"-- Override A New Attribute? --")+$attributes));

		if(!count($editable_attributeids)) return;
		echo("<table cellpadding=2 cellspacing=0 border=0><tr>");
		foreach($editable_attributeids as $attributeid) {
			$attribute = &$this->get_attribute($attributeid);
			echo("<tr><td class=smallprint><input type=checkbox name=\"override_attributeids[]\" value=$attributeid checked><b>$attribute->name:</b></td></tr>");
			echo("<tr><td>");
			$attribute->print_default_field($this->id);
			echo("</td></tr>");
		}
		echo("</table>");
	}

	 ########################################################
	# Processes an interface where inherited variables can have
	# their default values overriden for this category
	function process_attribute_override_interface() {
		$new_override_attributeid = $_POST['new_override_attributeid'];
		$override_attributeids = $_POST['override_attributeids'];

		$tree = &$this->get_category_tree();
		$attributes = &$tree->get_context_attributes($this->id);
		$ms = array();
		foreach($attributes as $attributeid => $name) {
			$attribute = &$this->get_attribute($attributeid);
			if(isset($attribute->defaults[$this->id])) {
				if(in_array($attributeid,$override_attributeids)) {
					if($m = $attribute->process_default_field($this->id)) {
						$ms[] = $m;
					}
				} else { # Stop overidding this attribute
					if($m = $attribute->remove_default_override($this->id)) {
						$ms[] = $m;
					}
				}
			}
		}

		if($new_override_attributeid && isset($attributes[$new_override_attributeid])) {
			$attribute = &$this->get_attribute($new_override_attributeid);
			$m = $attribute->set_default_override($this->id);
			$ms[] = $m;
		}

		return implode("\n",$ms);
	}


	 ################################################
	# Prints an interface for reordering attributes
	function print_attribute_summary() {
		$e = &$this->get_notitia_system();
		return $e->print_attribute_summary($this->id);
	}

	 #####################################
	# Saves changes to attribute ordering
	function process_attribute_summary() {
		$e = &$this->get_notitia_system();
		return $e->process_attribute_summary($this->id);
	}

	 ##############################################
	# Processes input for creating a new attribute
	function process_new_attribute() {
		$name = $this->get_value('new_attribute_name');
		$type = $this->get_value('new_attribute_type');
		$this->parameters['new_attribute_name'] = '';
		$e = &$this->get_notitia_system();
		if($attributeid = $e->create_attribute($type,$name,$this->id)) {
			$attribute = &$this->get_attribute($attributeid);
			return "New attribute '$attribute->name' created.";
		}
	}

	 ##########################################################
	# Prints a form which allows the user to select a list of
	# attribute/format pairs, and give each pair a label
	function print_attribute_format_list_selection_form($code,&$list,$format_type,$order=0) {
		$tree = &$this->get_category_tree();
		$set_count = 0;
		if($order) {
			$order_array = array_flip(array_keys($list));
		}
		?>
		<table cellpadding=0 cellspacing=0 border=0 bgcolor=#333333><tr><td><table cellpadding=1 cellspacing=1 border=0>
			<tr bgcolor=#cccccc>
				<td valign=top class=backend_field>Attribute</td>
				<td valign=top class=backend_field>Formats</td>
			</tr>
		<?
			$attribute_printed = false;
			$attributes = $tree->get_context_attributes($this->id);
			foreach($attributes as $attributeid => $name) {
				#check to see if it's a foreign key
				$attribute = &$this->get_attribute($attributeid);
				if ($attribute->type == 'foreign_key' && $format_type == 'edit') {
					#check to see if it's a reverse foreign key
					$handler = &$attribute->get_type_handler();
					if ($handler->reversed()) {
						#can't submit to these types of attributes
						?>
						<tr bgcolor=#cccccc>
							<td valign=top class=backend_field><?=$name?></td>
							<td valign=top><b>Reverse Foreign Keys are slaves to normal Foreign Keys therefore you cannot post to them</b></td>
						</tr>
						<?
						continue;
					}
				}
				?>
				<tr bgcolor=#cccccc>
					<td valign=top class=backend_field><?=$name?></td>
					<td valign=top>
					<?
					if($attribute->id) {
						switch($format_type) {
						case 'search':
							$formats = $attribute->get_type_setting('search_formats');
							break;
						case 'sort':
							$formats = $attribute->get_type_setting('sort_formats');
							break;
						case 'edit':
							$formats = $attribute->get_type_setting('edit_formats');
							break;
						case 'vary':
							$formats = $attribute->get_type_setting('variation_types');
							break;
						case 'print': default:
							$formats = $attribute->get_type_setting('value_formats');
							break;
						}
						if(count($formats) > 0) {
							$attribute_printed = true;
							?>
							<table cellpadding=2 cellspacing=1 width=100% height=100%>
								<tr bgcolor=#eeeeee>
							<?
							$format_count = 0;
							$total_formats = count($formats);
							foreach($formats as $format => $format_name) {
								$option_code = $attributeid.'_'.$format;
								if($set = isset($list[$option_code])) {
									if(!($label = $list[$option_code])) {
										$label = $name;
									}
								}
								if($format_count + 1 == $total_formats) {

									$colspan = 3 - $format_count % 3;
									$c_width = 33*$colspan;
								} else {
									$c_width = 33;
									$colspan=1;
								}
								?>
								<td valign=top <?=($set?'bgcolor=#ffffaa':'')?> width=<?=$c_width?>% colspan=<?=$colspan?>>
									<input type=checkbox name="<?=$code?>_set[]" value="<?=$option_code?>" <?=($set?'checked':'')?>>&nbsp;<?=$format_name?>
									<?
									if($set){
										$set_count++;
										echo '<br>'.text_box($code."_labels[$option_code]",$label,20,100,'class=data');
										if($order) {
											echo text_box($code."_order[$option_code]",$order_array[$option_code]+1,2,10,'class=data');
										}
									}
									?>
								</td>
								<?
								if($format_count++ % 3 + 1 == 3) echo "</tr><tr bgcolor=#eeeeee>";
							}
							?>
								</tr>
							</table>
							<?
						}
					}
					?>
					</td>
				</tr><?
			}
			if (!$attribute_printed) {?>
				<tr bgcolor=#cccccc>
					<td colspan=2 valign=top class=backend_field>No Local Attributes are available in this category</td>
				</tr>
			<?}?>
		</table></td></tr></table>
		<?
	}

	 ##########################################################
	# Prints a form which allows the user to select a list of
	# attribute/format pairs, and give each pair a label
	function process_attribute_format_list_selection_form($code,&$list) {
		$set    = $_REQUEST[$code.'_set'];
		$labels = $_REQUEST[$code.'_labels'];
		$order  = $_REQUEST[$code.'_order'];
		if(count($order)) {
			$sortme = array();
			foreach($set as $option_code) {
				$sortme[$option_code] = $order[$option_code];
			}
			asort($sortme);
			$set = array_keys($sortme);
		}
		$slist = array();
		foreach($set as $option_code) {
			if(!($label = $labels[$option_code])) {
				$attribute = &$this->get_attribute((int)$option_code);
				$label = $attribute->name;
			}
			$slist[$option_code] = preg_replace('/[^a-zA-Z_ 0-9\(\)\-]+/','',$label);
		}
		return $slist;
	}


	/**
	* Prints an interface for ordering files
	*
	* @access  private
	*/
	function print_sub_category_orderer() {

		$tree = &$this->get_category_tree();
		$children = $tree->child_list($this->id);

		if (!count($children)) {
			echo 'No Sub Categories Defined';
			return;
		}

		?>
		<script language="Javascript" type="text/javascript">
			function swap_items(a, b) {

				var f = document.edit;

				var option_a = f.elements['sort_list_combo[]'].options[a];
				var option_b = f.elements['sort_list_combo[]'].options[b];

				var hidden_a = f.elements['sub_category_sort_order[' + a + ']'];
				var hidden_b = f.elements['sub_category_sort_order[' + b + ']'];

				if (hidden_a.value != option_a.value && hidden_b.value != option_b.value) {
					alert('Unable to swap items, incosistency in numbering.');
					return;
				}

				var a = {catid: option_a.value, name: option_a.text};
				var b = {catid: option_b.value, name: option_b.text};

				option_a.value = b.catid;
				option_a.text  = b.name;
				hidden_a.value = b.catid;

				option_b.value = a.catid;
				option_b.text  = a.name;
				hidden_b.value = a.catid;

			}// end swap_items();

			function move_list(direction) {
				var f = document.edit;
				var combo = f.elements['sort_list_combo[]'];
				if (combo.options.length == 0) return;
				// for easy of reading
				var move_up = (direction >= 0);

				if (move_up) {
					for(var i = 1; i < combo.options.length; i++) {
						if (combo.options[i].selected) {
							swap_items(i, i - 1);
							combo.options[i].selected = false;
							combo.options[i - 1].selected = true;
						}
					}
				// move down
				} else {
					for(var i = combo.options.length - 2; i >= 0; i--) {
						if (combo.options[i].selected) {
							swap_items(i, i + 1);
							combo.options[i].selected = false;
							combo.options[i + 1].selected = true;
						}
					}
				}// end if

			}// end move_list()

			var reverse = 1;
			function sort_list(sort_num) {

				reverse = (sort_num >= 0) ? 1 : -1;

				var f = document.edit;
				var combo = f.elements['sort_list_combo[]'];
				if (combo.options.length == 0) return;


				var sort_arr = new Array(combo.options.length);
				// go through the sorted list and swap files into their correct positions
				for(var i = 0; i < combo.options.length; i++) {
					sort_arr[i] = {catid: combo.options[i].value, name: combo.options[i].text };
				}// end for

				sort_arr.sort(compare_options);

				// go through the sorted list and swap files into their correct positions
				for(var i = 0; i < sort_arr.length; i++) {

					combo.options[i].value = sort_arr[i].catid;
					combo.options[i].text  = sort_arr[i].name;

					f.elements['sub_category_sort_order[' + i + ']'].value = sort_arr[i].catid;

				}// end for

			}// end sort_list()

			function compare_options(a,b) {
				if (a.name.toLowerCase() < b.name.toLowerCase()) {
					return (-1 * reverse);
				}
				if (a.name.toLowerCase() == b.name.toLowerCase()) {
					return 0;
				}
				if (a.name.toLowerCase() > b.name.toLowerCase()) {
					return (1 * reverse);
				}
			}// end compare_options()
		</script>
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top">
		<?

		$height = min(30, count($children));

		echo multiple_combo_box('sort_list_combo[]', $children, Array(), '', 0, $height);

		$i = 0;
		foreach($children as $id => $name) {
			?>
			<input type="hidden" name="sub_category_sort_order[<?=$i?>]" value="<?=$id?>" />
			<?
			$i++;
		}
		?>
				</td>
				<td valign="middle">
					&nbsp;&nbsp;
				</td>
				<td valign="middle">
					<a href="javascript:move_list(1)" onmouseover="javascript:show_tooltip(event,'Move Up','', 100); return true;" onmouseout="javascript:hide_tooltip(); return true;"><img src="images/uparrow.gif" border="0"></a>
					<br><br>
					<a href="javascript:move_list(-1)" onmouseover="javascript:show_tooltip(event,'Move Down','', 100); return true;" onmouseout="javascript:hide_tooltip(); return true;"><img src="images/downarrow.gif" border="0"></a>
				</td>
				<td valign="middle">
					&nbsp;&nbsp;
				</td>
				<td valign="top">
					<a href="javascript:sort_list(1);" onmouseover="javascript:show_tooltip(event,'Sort Ascending','Sorts the categories in alphabetical order (A-Z)', 200); return true;" onmouseout="javascript:hide_tooltip(); return true;"><img src="images/icons/sorta_z.gif" border="0"></a>
					<br><br>
					<a href="javascript:sort_list(-1)" onmouseover="javascript:show_tooltip(event,'Sort Descending','Sorts the categories in reverse alphabetical order (Z-A)', 200); return true;" onmouseout="javascript:hide_tooltip(); return true;"><img src="images/icons/sortz_a.gif" border="0"></a>
				</td>
			</tr>
		</table>
		<?

	}#end print_sub_category_orderer()


	/**
	* Processes the interface defiend by print_sub_category_orderer()
	* Used for specifically setting
	*
	* @return string	message to print in msg box
	* @access public
	*/
	function process_sub_category_orderer() {

		$tree = &$this->get_category_tree();
		$childids = $tree->childids($this->id);

		$msgs = '';

		$i = 0; # do this rather than get the key because we do the continue in the loop
		foreach($_POST['sub_category_sort_order'] as $sub_categoryid) {
			if (!in_array($sub_categoryid, $childids)) continue;
			$category = &$this->get_category($sub_categoryid);
			if ($category->id) {
				$msgs .= $category->set_order_no($i, false)."\n";
				$i++;
			}
			unset($category);
		}

		# now refresh the tree's version
		$tree->new_category_order($this->id);

		return $msgs;

	}#end process_sub_category_orderer()


	 ###########################################################
	# Prints a llist of all the xtras available for importing
	# and exporting data
	function print_data_import_xtras($href = -1) {
		$e = &$this->get_notitia_system();
		return $e->print_data_import_xtras($this->get_backend_href());
	}
	function print_data_export_xtras($href = -1) {
		$e = &$this->get_notitia_system();
		return $e->print_data_export_xtras($this->get_backend_href());
	}
	function print_data_perform_xtras($href = -1) {
		$e = &$this->get_notitia_system();
		return $e->print_data_perform_xtras($this->get_backend_href());
	}

	/**
	* Returns an array of every default value table being employed by this Notitia setup
	*
	* @access public
	* @return array
	*/
	function get_all_default_tables() {
		$db = &$this->get_db();
		$n = &$this->get_notitia_system();
		$attributeid = $db->single_column("SELECT attributeid FROM {$n->table_name}_attribute GROUP BY type");
		$default_table_names = array();
		if (!empty($attributeid)) {
			foreach($attributeid as $attributeid) {
				$attribute = $this->get_attribute($attributeid);
				$default_table_name = $attribute->get_default_value_table_name();
				if (!in_array($default_table_name, $default_table_names)) {
					$default_table_names[] = $default_table_name;
				}
			}
		}
		return $default_table_names;
	}
}
?>

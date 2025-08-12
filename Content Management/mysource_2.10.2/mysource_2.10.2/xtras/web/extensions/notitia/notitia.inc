<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/notitia.inc,v $
## $Revision: 1.12 $
## $Author: achadszinow $
## $Date: 2004/03/30 06:14:53 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/web_extension.inc");
require_once(dirname(__FILE__).'/notitia_object.inc');
require_once(dirname(__FILE__).'/variety_set.inc');
require_once(dirname(__FILE__).'/record.inc');
require_once(dirname(__FILE__).'/attribute.inc');
require_once(dirname(__FILE__).'/category.inc');
require_once(dirname(__FILE__).'/category_tree.inc');
include_once(dirname(__FILE__).'/attribute_xtra.inc');
#---------------------------------------------------------------------#


class Web_Extension_Notitia extends Web_Extension {
	
	# There can be only one.
	var $id; 

	# Are we current operating at the level of a particular site or catgeory?
	var $siteid = 0;
	var $categoryid = 0;

	var $parameters = array(); # All the options settable by the administrator

	# This gets filled with cached data at runtime, but isnt saved.
	var $temp = array();

	 ##############################
	# Constructor
	function Web_Extension_Notitia() {
		Web_Extension::Web_Extension();
		$this->load();
	}


	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = Web_Extension::__sleep();
		array_remove_element('siteid',$result);
		array_remove_element('categoryid',$result);
		array_remove_element('temp',$result);
		return $result;
	}

	 ##############################################
	# Wake up with this handy information EVERY day !
	function __wakeup() {
		Web_Extension::__wakeup();
		$this->temp['record_factory']   = array();
		$this->temp['attribute_factory'] = array();
		$this->temp['attribute_xtra_factory'] = array();
		$this->siteid = 0;
		$this->categoryid = 0;
		$this->id = &$this->codename;
	}

	 ################################################
	# Loads certain information that could be useful
	function load() {

		 #################
		# Check the cache
		if ($this->load_from_cache()) {
			return 1;
		}

		 ######################################
		# Grab options from the parameter file
		$this->parameters = unserialize(file_to_string("$this->data_path/$this->codename.param"));
		if(!is_array($this->parameters)) $this->parameters = array();

		 #############################################
		# Load security permissions from database
		$tree = &$this->get_category_tree();			
		$this->parameters['permissions']['category'] = $tree->load_category_access_grants(0);

		# make sure the directory is created here, this shouldn't be executed all that often
		# thanks to the wonders of caching :)
		$this->init_paths();

		# Okay I think that's just about everything.

		 ###################
		# Save to the cache 
		$this->save_to_cache();

	}

	 #############################################
	# Saves system paraters to a file or database
	function save() {

		 #######################################################
		# Save the secrutiy permissions to the database - leave
		# the out of the file. But only category masters can mess
		# with these
		if($this->category_master()) {
			$tree = &$this->get_category_tree();			
			$cat_perms = &$this->parameters[permissions][category];
			if(!$this->superuser()) { # If you're not a category superuser, you can't mess with the M's
				$old_perms = $tree->load_category_access_grants($this->id);
				$this->parameters[permissions][category]["M"] = $old_perms["M"];
			}
			$tree->save_category_access_grants($this->id, $cat_perms);
		}

		 #################################################
		# Are they trying to create some new categories?
		$new_category_names = split("[\n\r]+",$this->parameters[new_category_names]);
		$this->parameters[new_category_names] = "";
		if(count($new_category_names) && $this->new_subcategory_access()) {
			$tree = &$this->get_category_tree();			
			foreach($new_category_names as $name) {
				if(!$name = trim($name)) continue;
				$message = trim($message)."\n";
				if ($categoryid = $tree->create_category($name,0,$this->id)) {
					$category = &$this->get_category($categoryid);
					$message .= "Category '$category->name' created.";
				} else {
					$message .= "Unable to create new category.";
				}
			}
		}

		$tmp = &$this->parameters[permissions][category];
		unset(  $this->parameters[permissions][category]); # Don't need saved

		string_to_file(serialize($this->parameters),"$this->data_path/$this->codename.param");

		$this->parameters[permissions][category] = &$tmp;

		$this->clear_cache();

	}

	 
	 ##########################################
	# Returns a reference to a record object
	function &get_record($recordid) {
		$recordid = (int) $recordid;
		if($recordid <= 0) return;
		$class = get_class($this).'_record';
		$factory = &$this->temp['record_factory'];
		if(!isset($factory[$recordid]) || get_class($factory[$recordid]) != $class) {
			$factory[$recordid] = new $class($recordid);
		}
		return $factory[$recordid];
	}

	function forget_record($recordid) {
		unset($this->temp['record_factory'][$recordid]);
	}

	 ############################################
	# Returns a reference to an attribute object
	function &get_attribute($attributeid) {
		$attributeid = (int) $attributeid;
		if($attributeid <= 0) return;
		$class = get_class($this).'_attribute';
		$factory = &$this->temp['attribute_factory'];
		if(!isset($factory[$attributeid]) || get_class($factory[$attributeid]) != $class) {
			$factory[$attributeid] = new $class($attributeid);
		}
		return $factory[$attributeid];
	}
	function forget_attribute($attributeid) {
		unset($this->temp['attribute_factory'][$attributeid]);
	}

	 ############################################
	# Returns a reference to a category object
	function &get_category($categoryid) {
		$categoryid = (int) $categoryid;
		if($categoryid <= 0) return;
		$class = get_class($this)."_category";
		$factory = &$this->temp[category_factory];
		if(!isset($factory[$categoryid]) || get_class($factory[$categoryid]) != $class) {
			$factory[$categoryid] = new $class($categoryid);
		}
		return $factory[$categoryid];
	}
	function forget_category($categoryid) {
		unset($this->temp['category_factory'][$categoryid]);
	}

	 #############################################
	# Returns a reference to a variety_set object
	function &get_variety_set($variety_setid) {
		$variety_setid = (int) $variety_setid;
		if($variety_setid <= 0) return;
		$class = get_class($this)."_variety_set";
		$factory = &$this->temp[variety_set_factory];
		if(!isset($factory[$variety_setid]) || get_class($factory[$variety_setid]) != $class) {
			$factory[$variety_setid] = new $class($variety_setid);
		}
		return $factory[$variety_setid];
	}
	function forget_variety_set($variety_setid) {
		unset($this->temp['variety_set_factory'][$variety_setid]);
	}

	 ################################################
	# Returns a reference to the category tree array
	function &get_category_tree($siteid = 0) {
		$class = get_class($this)."_category_tree";
		$tree = &$this->temp[category_tree];
		if(get_class($tree) != $class) {
			$tree = new $class($siteid);
		}
		return $tree;
	}


	 #########################################
	# Returns a reference to the Notitia
	# Xtras registry
	function &get_xtras() {
		$xtras = &$this->temp['xtras'];
		if(get_class($xtras) != 'xtrasregistry') {
			$xtras = new XtrasRegistry(dirname(__FILE__).'/xtras',$this->table_name);
		}
		return $xtras;
	}

	
	 ########################
	# Returns an xtra object
	function &get_data_import($codename, $categoryid = 0) {
		$xtras = &$this->get_xtras();
		if($codename = $xtras->codename('data/import',$codename)) {
			$class = get_class($this)."_data_import_$codename";
			$factory = &$this->temp[data_import_factory];
			if(!isset($factory[$codename][$categoryid]) || get_class($factory[$codename][$categoryid]) != $class) {
				include_once("$this->xtra_path/data_import.inc");
				include_once("$this->xtra_path/xtras/data/import/$codename/$codename.inc");
				$factory[$codename][$categoryid] = new $class($categoryid);
			}
			return $factory[$codename][$categoryid];
		}
		return;
	}

	function &get_data_export($codename, $categoryid = 0) {
		$xtras = &$this->get_xtras();
		if($codename = $xtras->codename('data/export',$codename)) {
			$class = get_class($this).'_data_export_'.$codename;
			$factory = &$this->temp[data_export_factory];
			if(!isset($factory[$codename][$categoryid]) || get_class($factory[$codename][$categoryid]) != $class) {
				include_once("$this->xtra_path/data_export.inc");
				include_once("$this->xtra_path/xtras/data/export/$codename/$codename.inc");
				$factory[$codename][$categoryid] = new $class($categoryid);
			}
			return $factory[$codename][$categoryid];
		}
		return;
	}

	function &get_data_perform($codename, $categoryid = 0) {
		$xtras = &$this->get_xtras();
		if($codename = $xtras->codename('data/perform',$codename)) {
			$class = get_class($this)."_data_perform_$codename";
			$factory = &$this->temp[data_perform_factory];
			if(!isset($factory[$codename][$categoryid]) || get_class($factory[$codename][$categoryid]) != $class) {
				include_once("$this->xtra_path/data_perform.inc");
				include_once("$this->xtra_path/xtras/data/perform/$codename/$codename.inc");
				$factory[$codename][$categoryid] = new $class($categoryid);
			}
			return $factory[$codename][$categoryid];
		}
		return;
	}


	 ######################################
	# Sets the current siteid to something
	function set_siteid($siteid) {
		$this->siteid = abs((int)$siteid);
	}

	 ##########################################
	# Sets the current categoryid to something
	function set_categoryid($categoryid) {
		$this->categoryid = abs((int)$categoryid);
	}


	 ########################################
	# Gets a references to the parameter set
	function &get_category_pset($categoryid) {
		$pset = &$this->temp[category_pset][$categoryid];
		if(get_class($pset) != "parameter_set") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/parameter_set.inc");
			$category = &$this->get_category();
			$pset = new Parameter_Set(get_class($this)."_category","$this->xtra_path/category.pset",$category->parameters, $category);
		}
		return $pset;
	}
	
	 ########################################
	# Gets a references to the parameter set
	function &get_pset() {
		$pset = &$this->temp[pset];
		if(get_class($pset) != "parameter_set") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/parameter_set.inc");
			$pset = new Parameter_Set(get_class($this),"$this->xtra_path/$this->codename.pset",$this->parameters, $this);
		}
		return $pset;
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

	 ####################################################################################
	# For compatibliity with the category object, which this shares some behaviour with.
	function categoryid() {
		return 0; 
	}

	
	 ##################################################################
	# This generates a "code" string based a set of variety selections
	# this uniquely identifies an "item" of a record - that is, a
	# unique combination of selections for each variety set.
	function generate_item_code(&$selections) {
		$r = "";
		# Assume $selections is in the format:
		# array($variety_setid => array($coord, $coord, $coord))
		ksort($selections);
		foreach($selections as $variety_setid => $coords) {
			$variety_set = &$this->get_variety_set($variety_setid);
			if(!$variety_set->id) continue;
			$q = "";
			foreach($variety_set->get_variety_names() as $coord => $name) {
				if(in_array($coord,$coords)) {
					if($q) $q .= "a$coord";
					else $q = "$coord";
				}
			}
			if($q) {
				if ($r) $r .= "v$variety_setid"."c$q";
				else    $r  = $variety_setid."c$q";
			}
		}
		return $r;
	}
	# And back again
	function regenerate_item_code(&$variety_code) {
		$r = array();
		$vsets = explode('v',$variety_code);
		foreach($vsets as $vset) {
			list($variety_setid,$selections) = explode('c',$vset);
			$r[$variety_setid] = explode('a',$selections);
		}
		return $r;
	}


	############### SECURITY ####################

	 ########################################################
	# Returns an array of the the permission codes and 
	# their descriptions, not including superuser ones
	function get_permissions() {
		return array(
			'V' => 'View Contents (Records, Attributes, Categories)',
			'E' => 'Edit Attributes',
			'C' => 'Create Attributes',
			'D' => 'Delete Attributes',
			'S' => 'Add Subcategories'
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
	function get_grant_entities($staff) {
		if(!isset($staff)) $staff = $this->get_value("staff");
		$r = array();
		$users_sys = &get_users_system();
		$db = &$users_sys->get_db();
		$users = $db->associative_array("SELECT userid, concat(firstname,' ',surname) FROM user WHERE userid IN ('".implode("','",$staff)."') ORDER BY surname, firstname");
		foreach($users as $userid => $name) {
			$r["U$userid"] = "(User) <a href=\"user.php?userid=$userid\">$name</a>";
		}
		$groups = &$this->web_system->get_general_access_group_list();
		foreach($groups as $groupid => $name) {
			$r["G$groupid"] = "(Access Group) <a href=\"access_group.php?groupid=$groupid\">$name</a>";
		}
		return $r;
	}

	 ##########################################################
	# Checks whether the currect user has a certain permission
	# in the root category
	function check_permission($type = "") {
		$tree = &$this->get_category_tree();
		return $tree->check_permission(0,$type);
	}
	
	 ################################################
	# Is this user a superuser or not? A superuser
	function superuser() {
		return superuser("web");
	}

	 ########################################
	# Is the current user a category master?
	function category_master() {
		return $this->superuser() || $this->check_permission("M");
	}
	
	 ####################################################
	# Is the user allowed to browse around this category?
	# (Backend)
	function read_access() {
		return $this->superuser() || $this->check_permission("MV");
	}
	
	 ###########################################################
	# Is the user allowed to create, edit or delete attributes
	function create_attribute_access() {
		return $this->superuser() || $this->check_permission("MC");
	}
	function edit_attribute_access() {
		return $this->superuser() || $this->check_permission("ME");
	}
	function delete_attribute_access() {
		return $this->superuser() || $this->check_permission("MD");
	}

	 ####################################################
	# Is the user allowed to create new subcategories
	function new_subcategory_access() {
		return $this->superuser() || $this->check_permission("MS");
	}

	############### END SECURITY ####################

	 #################################
	# Prints the backend for the user
	function print_backend() {
		$session = &get_mysource_session();

		$backend = &$this->setup_backend();
		$backend->set_context_bar(get_class($this),"<b>Web Extension:</b> <a href=\"".$this->get_backend_href()."\">$this->name</a>");

		$edit_recordid = $_REQUEST['edit_recordid'];
		$edit_categoryid = $_REQUEST['edit_categoryid'];
		$edit_attributeid = $_REQUEST['edit_attributeid'];

		 ############################################
		# Remember that this is the "Active" record
		if($edit_recordid) {
			$session->set_var("active_".get_class($this)."_record",$edit_recordid);
		}
	
		if($active_recordid = &$session->get_var("active_".get_class($this)."_record")) {
			$active_record = &$this->get_record($active_recordid);
			$active_record_categoryid = $edit_categoryid ;
			 $active_record_categoryids = $active_record->associated_categoryids();
			 if($active_record_categoryid) {
				if (!in_array($active_record_categoryid, $active_record_categoryids)) {
					$active_record_categoryid = reset($active_record_categoryids);        
				}
			 }else {
			       $active_record_categoryid = reset($active_record_categoryids);        
			 }
			$backend->set_context_bar("active_".get_class($this)."_record","<b>Active Record:</b> <a href=\"".$this->get_backend_href()."&edit_recordid=$active_recordid&edit_categoryid=$active_record_categoryid\">".$active_record->name()."</a>");
		}

		if($edit_categoryid) {
			$this->set_categoryid($edit_categoryid);
			$backend->set_hidden_field("edit_categoryid",$this->categoryid);
			$category = &$this->get_category($this->categoryid);
			$category->print_backend();
			return;
		}
		
		# But..
		if ($edit_recordid) {
			$record = &$this->get_record($edit_recordid);
			$record->print_backend();
			return;
		} 
		
		 #############
		# Okay now...
		$pset = &$this->get_pset();
		$pset->set_tabs($backend,"web_extention_$this->codename", $this->get_backend_href());

		
		# Or..	
		$data_import = $_REQUEST['data_import'];
		$data_export = $_REQUEST['data_export'];
		$data_perform = $_REQUEST['data_perform'];

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
			if (!$data_importer) $pset->print_backend($backend);
			$data_performer->print_backend();
			return;
		}

		if ($edit_attributeid) {
			$attribute = &$this->get_attribute($edit_attributeid);
			$attribute->print_backend();
			return;
		}

		if($pset->process($backend)) {
			$this->save();
		}
		$pset->print_backend($backend);
	}

	
	 ###############################################################
	# Returns true if this system has an eCommerce Engine installed
	function ecommerce_engine_installed() {
		global $XTRAS;
		return $XTRAS->codename('web/extensions','ecommerce');
	}


	 #############################################################################
	# If passed true will convert all the data from an installed eCommerce Engine
	# and import it into this Notitia system.
	function import_from_ecommerce_engine($yes=false) {
		if(!$yes || !$this->ecommerce_engine_installed()) return;
		$db = &$this->get_db();
		$x = 'xtra_web_extension';
		$n = $x.'_notitia';
		$e = $x.'_ecommerce';
		$db->replace("REPLACE INTO {$n}_record SELECT productid FROM {$e}_product");
		$db->replace("REPLACE INTO {$n}_category SELECT categoryid, parentid, siteid, name, description, order_no, parameters FROM {$e}_category");
		$db->replace("REPLACE INTO {$n}_record_to_category SELECT productid, categoryid FROM {$e}_product_to_category");
		$db->replace("REPLACE INTO {$n}_attribute SELECT attributeid, categoryid, productid, type, name, parameters, order_no FROM {$e}_attribute");
		$db->replace("REPLACE INTO {$n}_attribute_role SELECT attributeid, role, parameters FROM {$e}_attribute_role");
		$db->replace("REPLACE INTO {$n}_attribute_value SELECT attributeid, productid, value FROM {$e}_attribute_value");
		$db->replace("REPLACE INTO {$n}_attribute_default SELECT attributeid, categoryid, inherit_type, sibling_type, value FROM {$e}_attribute_default");
		$db->replace("REPLACE INTO {$n}_variety_set SELECT variety_setid, categoryid, productid FROM {$e}_variety_set");
		$db->replace("REPLACE INTO {$n}_variety_set_option_attribute SELECT variety_setid, attributeid FROM {$e}_variety_set_option_attribute");
		$db->replace("REPLACE INTO {$n}_variety_set_varied_attribute SELECT variety_setid, attributeid FROM {$e}_variety_set_varied_attribute");
		$db->replace("REPLACE INTO {$n}_variation SELECT productid, attributeid, variety_setid, coord, type, value FROM {$e}_variation");
		$db->replace("REPLACE INTO {$n}_variation_default SELECT categoryid, attributeid, variety_setid, coord, type, value FROM {$e}_variation_default");
		$db->replace("REPLACE INTO {$n}_category_grant SELECT categoryid, entityid, entity_type, access_types FROM {$e}_category_grant");
		# Wipe out the cache.
		global $CACHE;
		$CACHE->wipe();
		return 'Data imported from eCommerce Engine.';
	}


	 #########################
	# Prints the category tree
	function print_category_tree($root = -1) {
		if($root == -1) $root = $this->categoryid;
		$tree = &$this->get_category_tree();
		if($this->categoryid) $tree->open_backend_category(0);
		foreach($tree->ancestorids($this->categoryid) as $ancestorid) {
			$tree->open_backend_category($ancestorid);
		}
		$tree->print_tree(0);
	}

	 #########################
	# Prints the category tree
	function process_category_tree_interface($root = -1) {
		if($root == -1) $root = $this->categoryid;
		$tree =&$this->get_category_tree();
		return $tree->process_tree_interface($root);
	}


	 ################################################################
	# Returns an array($id => $name) of the attributes associated with
	# this record (shared and local) that generate
	function &get_variety_generating_attribute_names($categoryid = 0, $recordid = 0) {
		if(!isset($this->temp["variety_generating_attribute_names_$recordid-$categoryid"])) {
			$a = &$this->temp["variety_generating_attribute_names_$recordid-$categoryid"];
			$a = array();
			$tree = &$this->get_category_tree();
			foreach($tree->get_context_attributes($categoryid,$recordid) as $attributeid => $name) {
				$attribute = &$this->get_attribute($attributeid);
				if($attribute->get_type_setting("generates_varieties")) {
					$a[$attributeid] = $name;
				}
			}
			if($recordid) {
				foreach($tree->get_local_attributes($recordid) as $attributeid => $name) {
					$attribute = &$this->get_attribute($attributeid);
					if($attribute->get_type_setting("generates_varieties")) {
						$a[$attributeid] = $name;
					}
				}
			}
		}
		return $this->temp["variety_generating_attribute_names_$recordid-$categoryid"];
	}


	 ################################################################
	# Returns an array($id => $name) of the attributes associated with
	# this record (shared and local) that generate
	function &get_variable_attribute_names($categoryid = 0, $recordid = 0) {
		if(!isset($this->temp["variable_attribute_names_$recordid-$categoryid"])) {
			$a = &$this->temp["variable_attribute_names_$recordid-$categoryid"];
			$a = array();
			$tree = &$this->get_category_tree();
			foreach($tree->get_context_attributes($categoryid,$recordid) as $attributeid => $name) {
				$attribute = &$this->get_attribute($attributeid);
				if($attribute->get_type_setting("variable")) {
					$a[$attributeid] = $name;
				}
			}
			if($recordid) {
				foreach($tree->get_local_attributes($recordid) as $attributeid => $name) {
					$attribute = &$this->get_attribute($attributeid);
					if($attribute->get_type_setting("variable")) {
						$a[$attributeid] = $name;
					}
				}
			}
		}
		return $this->temp["variable_attribute_names_$recordid-$categoryid"];
	}

	 ##############################################################
	# Returns an array of attirbute types along with descriptions
	# formatted for printing in a checkbox list
	function get_attribute_types_with_descriptions() {
		$types = $this->get_attribute_types();
		$xtras = &$this->get_xtras();
		foreach(array_keys($types) as $codename) {
			$types[$codename] .= '<span class=smallprint><br><i>'.$xtras->description('attribute/types',$codename).'</i></span>';
		}
		return $types;
	}

	 ##############################################################
	# Returns an associtive array of the available attribute types
	function get_attribute_types() {
		$xtras = &$this->get_xtras();
		return $xtras->list_type('attribute/types');
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
		if($variety_setid = $e->create_variety_set(0,0,$option_attributeids,$varied_attributeids)) {
			$variety_set = &$this->get_variety_set($variety_setid);
			return "New variety set '".$variety_set->name()."' created.";
		}
	}


	 ######################################################
	# Prints a list of the inherited attribues in a context
	function print_inherited_attribute_summary($categoryid = 0, $recordid = 0) {
		if(!$categoryid && !$recordid) {
			echo("This is the root category and there can be no inherited attributes.");
			return;
		}
		$tree = &$this->get_category_tree();
		if($recordid) {
			$record = &$this->get_record($recordid);
			foreach($record->categoryids as $categoryid) {
				$tree->print_context_attributes($categoryid);
			}
		} else {
			$tree->print_context_attributes($tree->parentid($categoryid));
		}
		return;
	}
	 

	 ######################################################
	# Prints a list of the inherited attribues in a context
	function print_inherited_variety_set_summary($categoryid = 0, $recordid = 0) {
		$tree = &$this->get_category_tree();
		$tree->print_context_variety_sets($categoryid,$recordid);
		return;
	}

	 ########################################################
	# Prints an editable summary of the variety sets defined
	# in a particular context
	function print_variety_set_summary($categoryid = 0, $recordid = 0) {

		$tree = &$this->get_category_tree();
		$db = &$this->get_db();

		$ids = $db->single_column("SELECT variety_setid FROM $this->table_name"."_variety_set WHERE categoryid='$categoryid' AND recordid='$recordid'");
	
		$editable = &$this->edit_attribute_access();

		foreach($ids as $variety_setid) {
			$variety_set = &$this->get_variety_set($variety_setid);
			if($editable) {
				echo("<input type=hidden name=editable_variety_setids[] value=$variety_setid>");
			}
			$variety_set->print_backend_summary($editable);
			echo("<hr noshade size=0>");
		}

		if(!count($ids)) echo("None.");

	}


	 ############################################################
	# Processes an editable summary of the variety sets defined
	# in a particular context
	function process_variety_set_summary($categoryid = 0, $recordid = 0) {


		if(!$this->edit_attribute_access()) return;

		$tree = &$this->get_category_tree();
		$db = &$this->get_db();

		$ids = $db->single_column("SELECT variety_setid FROM $this->table_name"."_variety_set WHERE categoryid='$categoryid' AND recordid='$recordid'");

		$ms = array();
		foreach($ids as $variety_setid) {
			$variety_set = &$this->get_variety_set($variety_setid);
			$m = $variety_set->process_backend_summary();
			if($m) $ms[] = $m;
		}
		return implode("\n",$ms);
	}


	 ################################################
	# Prints an interface for reordering attributes
	function print_attribute_summary($categoryid = 0, $recordid = 0) {

		$tree = &$this->get_category_tree();
		$db = &$this->get_db();
		$ids   = $db->associative_array("SELECT attributeid, name FROM $this->table_name"."_attribute WHERE categoryid='$categoryid' AND recordid='$recordid' ORDER BY order_no");
		$types = $db->associative_array("SELECT attributeid, type FROM $this->table_name"."_attribute WHERE categoryid='$categoryid' AND recordid='$recordid' ORDER BY order_no");
	
		$xtras = &$this->get_xtras();

		# Are there any?
		if(!count($ids)) {
			if($categoryid) {
				if($recordid) {
					echo('No attributes have been defined for this record only that are limited to the context of this category.');
				} else {
					echo('No attributes have been defined for this category.');
				}
			} else {
				if($recordid) {
					echo('No context-free attributes have been defined for this record only.');
				} else {
					echo('No global attributes have been defined.');
				}
			}
			return;
		}

		$href = $this->get_backend_href()."&edit_categoryid=$categoryid&edit_recordid=$recordid";

		$prefix = "attr_sum_$recordid"."_$categoryid"."_";

		$js = "
			var temp_$prefix"."id = 0;
			var temp_$prefix"."name = '';
			var f = 'document.edit';
			function $prefix"."swap(a,b) {
				// Swap ids
				eval('temp_$prefix"."id = '+f+'.$prefix"."id' + a + '.value');
				eval(f+'.$prefix"."id' + a + '.value = '+f+'.$prefix"."id' + b + '.value');
				eval(f+'.$prefix"."id' + b + '.value = temp_$prefix"."id');
				// Swap names
				eval('temp_$prefix"."name = '+f+'.$prefix"."name' + a + '.value');
				eval(f+'.$prefix"."name' + a + '.value = '+f+'.$prefix"."name' + b + '.value');
				eval(f+'.$prefix"."name' + b + '.value = temp_$prefix"."name');
			}

			function $prefix"."move_up(no) {
				if (no > 1) {
					$prefix"."swap(no, no - 1);
				}
			}
			function $prefix"."move_down(no) {
				if (no < ".count($ids).") {
					$prefix"."swap(no, no + 1);
				}
			}
			function $prefix"."edit_attribute(no) {
				var f = 'document.edit';
				eval('id = '+f+'.$prefix"."id'+no+'.value');
				location = '$href&edit_attributeid='+id;
			}
			function $prefix"."delete_attribute(no) {
				var f = 'document.edit';
				eval('id   = '+f+'.$prefix"."id'+no+'.value');
				eval('name = '+f+'.$prefix"."name'+no+'.value');
				if (confirm('Are you sure you want to delete the attribute \''+name+'\'?\\n\\nDeleting an attribute may have severe implications on the functionality of the system.\\nDo not delete this attribute unless you are sure you don\'t need it.\\nAny overriding attributes will also be deleted.')) {
					document.edit.$prefix"."delete_attributeid.value = id;
					document.edit.action.value='Commit';
					document.edit.submit();
				}
			}
			function $prefix"."dupe_attribute(no) {
				var f = 'document.edit';
				eval('id   = '+f+'.$prefix"."id'+no+'.value');
				document.edit.$prefix"."dupe_attributeid.value = id;
				document.edit.action.value='Commit';
				document.edit.submit();
			}
		";

		echo js($js);

		?>
		<input type=hidden name=<?=$prefix?>delete_attributeid>
		<input type=hidden name=<?=$prefix?>dupe_attributeid>
		<?

		$i = 1;
		foreach($ids as $id => $name) {
			if($tree->check_permission($categoryid,'ME')) { 
				?>
				<a href="javascript:<?=$prefix?>move_up(<?=$i?>)"><img src="images/uparrow.gif" alt="Move Up" border=0></a>
				<a href="javascript:<?=$prefix?>move_down(<?=$i?>)"><img src="images/downarrow.gif" alt="Move Down" border=0></a>
				<input type=hidden name="<?=$prefix?>id<?=$i?>" value=<?=$id?>><input type=hidden name="<?=$prefix?>renamed<?=$i?>"><input type=text name="<?=$prefix?>name<?=$i?>" value="<?=str_replace("\"","&quot;",$name)?>" size=30 onchange="form.<?=$prefix?>renamed<?=$i?>.value='1';">
				<a href="javascript:<?=$prefix?>edit_attribute(<?=$i?>)"><img src="images/icons/create.gif" alt="Edit Attribute" border=0></a>
				<?
			} else {
				echo $name;
			}
			if($tree->check_permission($categoryid,'MD')) {
				?><a href="javascript:<?=$prefix?>delete_attribute(<?=$i?>)"><img src="images/icons/delete.gif" alt="Delete Attribute" border=0></a><?
			}
			if($tree->check_permission($categoryid,'MC')) {
				?> <a href="javascript:<?=$prefix?>dupe_attribute(<?=$i?>)"><img src="images/icons/dupe.gif" alt="Dupe Attribute" border=0></a><?
			}
			echo(' <i>('.$xtras->name('attribute/types',$types[$id]).')</i><br>');
			$i++;
		}
		if($categoryid) {
			if($recordid) {
				echo("");
			} else {
				echo("These attributes apply to all records in this category and any subcategories.");
			}
		} else {
			if($recordid) {
				echo("These attributes apply to all this record in all contexts.");
			} else {
				echo("These attributes apply to all records in the system.");
			}
		}

	}

	 #####################################
	# Saves changes to attribute ordering
	function process_attribute_summary($categoryid = 0, $recordid = 0) {

		$tree = &$this->get_category_tree();

		 #########################
		# Reorderin' and renamin'
		if($tree->check_permission($categoryid,'ME')) {
			$db = &$this->get_db();
			$count = 0;
			$prefix = "attr_sum_$recordid"."_$categoryid"."_";
			$new_order = array();
			foreach($_POST as $key => $id) {
				if (substr($key, 0, strlen($prefix)+2) != $prefix.'id') continue;
				$order_no = substr($key, strlen($prefix)+2);
				$new_order[$order_no] = $id;
				$updated = (int) $db->update("UPDATE $this->table_name"."_attribute SET order_no='$order_no' WHERE attributeid='$id'");
				if($updated) {
					# records need to know about the change.
					$attribute = &$this->get_attribute($id);
					$attribute->clear_associated_record_caches();
				}
				$reorder_count += $updated;
				if($_POST[$prefix."renamed$order_no"]) {
					# They've changed the name of the attribute
					$attribute = &$this->get_attribute($id);
					if($m = $attribute->set_name($_POST[$prefix."name$order_no"])) {
						$message .= "$m\n";
					}
				}
			}
			if($reorder_count) {
				$message .= "$reorder_count attributes reordered.\n";
				$tree->new_attribute_order($categoryid,$recordid,$new_order);
			}
		}


		 ###########
		# Deletin'!
		$edit_categoryid = $_REQUEST['edit_categoryid'];
		if((!$recordid && $tree->check_permission($categoryid,'MD')) || ($recordid && $tree->check_permission($edit_categoryid,'MP'))) {
			$type = $_POST[$prefix.'delete_attributeid'];
			if($type) {
				$attribute = &$this->get_attribute($type);
				$message  .= $attribute->delete()."\n";
			}
		}

		 ###########
		# Dupin'!
		$edit_categoryid = $_REQUEST['edit_categoryid'];
		if((!$recordid && $tree->check_permission($categoryid,'MC')) || ($recordid && $tree->check_permission($edit_categoryid,'MP'))) {
			$type = $_POST[$prefix.'dupe_attributeid'];
			if($type) {
				$attribute = &$this->get_attribute($type);
				$message  .= $attribute->dupe()."\n";
			}
		}
		return $message;
	}


	 ##############################################
	# Processes input for creating a new attribute
	function process_new_attribute() {
		$name = $this->get_value("new_attribute_name");
		$type = $this->get_value("new_attribute_type");
		$this->parameters["new_attribute_name"] = "";
		if($attributeid = $this->create_attribute($type,$name)) {
			$attribute = &$this->get_attribute($attributeid);
			return "New attribute '$attribute->name' created.";
		}
	}

	 #########################
	# Creates a new attribute
	function create_attribute($type,$name,$categoryid=0,$recordid=0) {
		if($name && $type) {
			$class = get_class($this)."_attribute";
			$new_attribute = new $class();
			if($new_attribute->create($type,$name,$categoryid,$recordid)) {
				$this->attribute_factory[$new_attribute->id] = &$new_attribute;
				$tree = &$this->get_category_tree();
				$tree->new_attribute($new_attribute->id, $name, $categoryid, $recordid);
				return $new_attribute->id;
			}
		}
	}


	 ###########################
	# Creates a new variety_set
	function create_variety_set($categoryid=0,$recordid=0,$option_attributeids,$varied_attributeids) {
		if(count($option_attributeids) > 0) {
			$class = get_class($this)."_variety_set";
			$variety_set = new $class();
			if($variety_set->create($categoryid,$recordid,$option_attributeids,$varied_attributeids)) {
				$this->variety_set_factory[$variety_set->id] = &$variety_set;
				return $variety_set->id;
			}
		}
	}


	 ################################################################
	# Prints the list of attributes chosen for
	# display, as well as fields for removing or changin their label
	function print_attribute_list($prefix, &$list, $search_formats) {
		if(!count($list)) {
			echo('None.');
			return;
		}
		echo('<table cellpadding=0 cellspacing=0 border=0 bgcolor=#888888 width=100%><tr><td><table cellpadding=3 cellspacing=1 border=0 width=100%>');
		echo('<tr bgcolor=#aaaaaa><td valign=top><b>Label</b></td><td valign=top><b>Attribute</b></td><td valign=top><b>Format</b></td><td valign=top><b>Order</b><span class=smallprint><br>(0 = Remove)</span></td>');
		$i = 1;
		foreach($list as $attribute_code => $label) {
			$attributeid = (int) $attribute_code;
			$format = substr(strstr($attribute_code,'_'),1);
			$attribute = &$this->get_attribute($attributeid);
			if(!$attribute->id) continue;
			$formats = &$attribute->get_type_setting((($search_formats)?'search_formats':'value_formats'));
			echo('<tr bgcolor=#eeeeee><td valign=top>');
			echo text_box($prefix."_attribute_label[$attribute_code]",$label,15,30);
			echo("</td><td valign=top>$attribute->name</td>");
			echo("<td valign=top>".$formats[$format]."</td><td valign=top>");
			echo text_box($prefix."_attribute_order[$attribute_code]",$i,3,3);
			echo('</td></tr>');
			$i++;
		}
		echo('</table></td></tr></table>');
	}


	 ################################################################
	# Prints the list of attributes chosen for
	# display, as well as fields for removing or changin their label
	function process_attribute_list($prefix, &$list, $new_attribute_code) {
		$labels = $_POST[$prefix.'_attribute_label'];
		$order = $_POST[$prefix.'_attribute_order'];
		$new_list = array();

		asort($order);
		foreach($order as $attribute_code => $order) {
			if(!$order) continue;
			$new_list[$attribute_code] = $labels[$attribute_code];
		}
		if($new_attribute_code) {
			if(isset($new_list[$new_attribute_code])) return $new_list;
			$attribute = &$this->get_attribute((int)$new_attribute_code);
			if(!$attribute->id) return $new_list;
			$new_list[$new_attribute_code] = $attribute->name;
		}
		return $new_list;
	}



	 ###########################################################
	# Prints a llist of all the xtras available for importing
	# and exporting data
	function print_data_import_xtras($href = -1) {
		if($href == -1) $href = $this->get_backend_href();
		$xtras = &$this->get_xtras();
		$list = $xtras->list_type("data/import");
		if(!count($list)) {
			echo("No import Xtras available.");
			return;
		}
		echo("<ul>");
		foreach($list as $code => $name) {
			$desc = $xtras->description("data/import",$code);
			echo("<li><a href=\"$href&data_import=$code\">$name</a><span class=smallprint><br>$desc</span></li>");
		}
		echo("</ul>");
	}

	 ###########################################################
	# Prints a llist of all the xtras available for exporting
	# and exporting data
	function print_data_export_xtras($href = -1) {
		if($href == -1) $href = $this->get_backend_href();
		$xtras = &$this->get_xtras();
		$list = $xtras->list_type("data/export");
		if(!count($list)) {
			echo("No export Xtras available.");
			return;
		}
		echo("<ul>");
		foreach($list as $code => $name) {
			$desc = $xtras->description("data/export",$code);
			echo("<li><a href=\"$href&data_export=$code\">$name</a><span class=smallprint><br>$desc</span></li>");
		}
		echo("</ul>");
	}

	 ###########################################################
	# Prints a llist of all the xtras available for performing on data
	function print_data_perform_xtras($href = -1) {
		if($href == -1) $href = $this->get_backend_href();
		$xtras = &$this->get_xtras();
		$list = $xtras->list_type("data/perform");
		if(!count($list)) {
			echo("No perform Xtras available.");
			return;
		}
		echo("<ul>");
		foreach($list as $code => $name) {
			$desc = $xtras->description("data/perform",$code);
			echo("<li><a href=\"$href&data_perform=$code\">$name</a><span class=smallprint><br>$desc</span></li>");
		}
		echo("</ul>");
	}

	/**
	* Creates an attribute type Xtra
	*
	* @param int $type - the type 
	* @param int $name - the name
	* @param int $attributeid - the attributeid it belongs to
	* @access public
	* @return int - the xtraid
	*/
	function create_attribute_type_xtra($type, $name, $attributeid) {
		if($type && $attributeid && $name) {
			$class = get_class($this)."_Attribute_Type_Attribute_Xtra";
			$new_attribute_xtra = new $class();
			$factory = &$this->temp['attribute_xtra_factory'];
			if($new_attribute_xtra->create($type, $name, $attributeid)) {
				$factory[$new_attribute_xtra->id] = &$new_attribute_xtra;
				return $new_attribute_xtra->id;
			}
		}
		return 0;
	}

	/**
	* Gets a reference to an attribute type xtra
	*
	* @param int $attribute_xtraid The id of the xtra to get
	* @access public
	* @return &object
	*/
	function &get_attribute_type_xtra($attribute_xtraid) {
		if (!$attribute_xtraid) return false;
		$factory = &$this->temp['attribute_xtra_factory'];
		$class = get_class($this).'_attribute_type_attribute_xtra';
		if(!isset($factory[$attribute_xtraid]) || get_class($factory[$attribute_xtraid]) != $class) {
			$factory[$attribute_xtraid] = new $class($attribute_xtraid);
		}
		return $factory[$attribute_xtraid];
	}

	/**
	* Forgets an attribute xtra
	*
	* @param int $attribute_xtraid The id of the xtra to forget
	* @access public
	* @return void
	*/
	function forget_attribute_type_xtra($attribute_xtraid) {
		unset($this->temp['attribute_xtra_factory'][$attribute_xtraid]);
	}
}#end class
?>
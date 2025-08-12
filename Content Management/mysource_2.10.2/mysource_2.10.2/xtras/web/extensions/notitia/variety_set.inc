<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/variety_set.inc,v $
## $Revision: 1.4 $
## $Author: ramato $
## $Date: 2003/07/15 01:04:41 $
#######################################################################
#---------------------------------------------------------------------#

 ##############################################################
# A variety set exists in a certain context and applies to all
# the records in that context (and subcategories etc) a lot like
# an attribute.
#
# A variety set is a list (often of just one or two elements) of
# variety-option attributes. And also
# a list of affected attributes.
class Web_Extension_Notitia_Variety_Set extends Web_Extension_Notitia_Object {
	
	# Var
	var $id;         # The variety set id
	var $categoryid; # The category, if any, this variety set is associated with
	var $recordid;  # The record, if any, this variety set is associated with

	var $option_attributeids; # A list of the attributes that generate the varieties
	var $varied_attributeids;   # The attributes that can be varied within this set

	var $temp; # An array of runtime data that doesnt get saved

	 #############
	# Constructor
	function Web_Extension_Notitia_Variety_Set($variety_setid) {
		Web_Extension_Notitia_Object::Web_Extension_Notitia_Object();
		$this->__wakeup();
		if((int) $variety_setid > 0) {
			return $this->load((int) $variety_setid);
		} else {
			return $this->id = 0;
		}
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
		$this->temp = array();
		$e = &$this->get_notitia_system();
		$this->table_name = $e->table_name."_variety_set";
	}

	 #########################
	# Creates a new attribute
	function create($categoryid,$recordid,$option_attributeids,$varied_attributeids) {
		$e     = &$this->get_notitia_system();
		$db = &$this->get_db();
		$this->categoryid = abs((int) $categoryid);
		$this->recordid  = abs((int) $recordid);
		# Make sure there are no other variety sets using
		# the same set of option attributeids
		$existing_variety_setids = $db->single_column("SELECT variety_setid FROM $this->table_name"."_option_attribute WHERE attributeid IN ('".implode("','",$option_attributeids)."') GROUP BY variety_setid HAVING COUNT(*) = ".count($option_attributeids));
		# But we can't be sure yet, since these existing ones may also have option
		# varieties that AREN'T in our new list
		$existing_variety_setids2 = $db->single_column("SELECT variety_setid FROM $this->table_name"."_option_attribute WHERE attributeid NOT IN ('".implode("','",$option_attributeids)."') GROUP BY variety_setid HAVING COUNT(*) > 0");
		if(count($blah = array_diff($existing_variety_setids, $existing_variety_setids2)) > 0) {
			$vs = &$this->get_variety_set(current($blah));
			return "A variety set already exists for these attributes: ".$vs->name();
		}

		$this->option_attributeids = $option_attributeids;
		$this->varied_attributeids = $varied_attributeids;

		# Save to the database
		$variety_setid = $db->insert("INSERT INTO $this->table_name (categoryid,recordid) VALUES('$this->categoryid','$this->recordid')");

		if($variety_setid) {
			foreach($this->option_attributeids as $id) {
				$db->insert("INSERT INTO $this->table_name"."_option_attribute (variety_setid,attributeid) VALUES('$variety_setid','$id')");
			}
			foreach($this->varied_attributeids as $id) {
				$db->insert("INSERT INTO $this->table_name"."_varied_attribute (variety_setid,attributeid) VALUES('$variety_setid','$id')");
			}
			if($this->load($variety_setid)) {
				return "New variety set created: ". $this->name();
			}
		}
		return "Unable to create new variety set.";
	}


	 ################################################################
	# Loads all the relevant information about this record from the
	# database or the cache
	function load($variety_setid) {

		$variety_setid = abs((int) $variety_setid);

		if (!$variety_setid && !($variety_setid = $this->id)) { # Tries "re"loading
			$this->_set_error("Attempt to load variety_set without a valid variety_setid.",__FILE__,__LINE__);
			return;
		}

		 #################
		# Check the cache
		#if ($this->load_from_cache($variety_setid)) {
		#	return $variety_setid;
		#}
		
		# Load everything from the database;
		$db = &$this->get_db();

		list($this->id,$this->categoryid,$this->recordid) = $db->single_row("SELECT variety_setid, categoryid, recordid FROM $this->table_name WHERE variety_setid='$variety_setid'");

		if(!$this->id) return;

		$this->option_attributeids = $db->single_column("SELECT attributeid FROM $this->table_name"."_option_attribute WHERE variety_setid='$variety_setid'");

		$this->varied_attributeids = $db->single_column("SELECT attributeid FROM $this->table_name"."_varied_attribute WHERE variety_setid='$variety_setid'");

		# Okay I think that's just about everything.

		 ###################
		# Save to the cache 
		#$this->save_to_cache();

		return $this->id;
	}


	 ################################################
	# Returns a string as the name of the attribute
	function name() {
		if(!isset($this->temp[name])) {
			foreach($this->option_attributeids as $attributeid) {
				$attribute = &$this->get_attribute($attributeid);
				if($this->temp[name])
					$this->temp[name] .= " / $attribute->name";
				else
					$this->temp[name]  = $attribute->name;
			}
		}
		return $this->temp[name];
	}
	

	 ##########################################################
	# Deletes an attribute and deals with all the other things
	# affected by this.
	function delete() {
		global $CACHE;

		$db = &$this->get_db();
		$e  = &$this->get_notitia_system();

		$message = $this->update_varied_attributeids(array());

		 ###############################################
		# Remove assocatiations with option attirbutes
		$db->delete("DELETE FROM $this->table_name"."_option_attribute WHERE variety_setid='$this->id'");


		 ###################
		# Delete our record
		$db->delete("DELETE FROM $this->table_name WHERE variety_setid='$this->id'");
		$this->clear_cache();

		return $message."Variety Set '".$this->name()."' (ID: $this->id) deleted.";

	}


	 ###########################################################
	# Updates the list of attributes that can be varies as a 
	# results of this variety set.
	function update_varied_attributeids($new_varied_attributeids) {
		list($a,$r) = array_compare($new_varied_attributeids, $this->varied_attributeids);

		# Now its really only the removed ones that matter, since we have to
		# remove from the database any values for attributes removed.	
		$db = &$this->get_db();
		$e  = &$this->get_notitia_system();
		$recordids = $db->single_column("SELECT DISTINCT recordid FROM $e->table_name"."_variation WHERE variety_setid='$this->id' AND attributeid IN ('".implode("','",$r)."')");

		# Do the deed.
		foreach($a as $add)
			$message .= $this->add_varied_attributeid($add)."\n";
		foreach($r as $rem) {
			$message .= $this->remove_varied_attributeid($rem)."\n";
		}
		
		# Clear the record caches.
		global $CACHE;
		$class = get_class($e).'_record';
		foreach($recordids as $recordid) {
			$CACHE->clear($recordid,$class);
		}

		return $message;
	}

 
	 ################################
	# Adds a new varied attribute
	function add_varied_attributeid($attributeid) {
		if(!$attributeid) return "Attribute ID not specified for addition.";
		if(in_array($attributeid,$this->varied_attributeids)) return "";
		$db = &$this->get_db();
		$e  = &$this->get_notitia_system();
		$this->varied_attributeids[] = $attributeid;
		if (!$db->insert("INSERT INTO $this->table_name"."_varied_attribute (attributeid, variety_setid) VALUES ('$attributeid','$this->id')")) {
			return "Unable to add varied attribute: database error.";
		}

		$this->clear_cache();
		$attribute = &$this->get_attribute($attributeid);
		return "The '$attribute->name' attribute now variable in the variety set '".$this->name()."'.";
	}


	 ############################
	# Removes a new site editor
	function remove_varied_attributeid($attributeid) {
		if(!$attributeid) return "Attribute ID not specified for removal.";
		if(!in_array($attributeid,$this->varied_attributeids)) return "";
		$db = &$this->get_db();
		$e  = &$this->get_notitia_system();
		foreach($this->varied_attributeids as $k => $id) {
			if ($id == $attributeid) {
				unset($this->varied_attributeids[$k]);
				break;
			}
		}
		if (!$db->delete("DELETE FROM $this->table_name"."_varied_attribute WHERE attributeid='$attributeid' AND variety_setid='$this->id'")) {
			return "Unable to remove varied attribute: database error.";
		}
		# Also delete any values stored for records on this attribute
		$values_deleted = (int) $db->delete("DELETE FROM $e->table_name"."_variation WHERE variety_setid='$this->id' AND attributeid='$attributeid'");
		$this->clear_cache();
		$attribute = &$this->get_attribute($attributeid);
		return "The '$attribute->name' attribute may no longer be varied in the '".$this->name()."' variety set ($values_deleted value(s) deleted).";
	}


	 ####################################################
	# Returns the total number of varieties in this set
	function get_variety_count($categoryid = 0, $recordid = 0) {
		if($recordid) $record = &$this->get_record($recordid);
		$count = 1;
		foreach($this->option_attributeids as $attributeid) {
			$attribute = &$this->get_attribute($attributeid);
			if($recordid)      $value = &$record->get_attribute_value($attributeid);
			elseif($categoryid) $value = &$attribute->get_default_value($categoryid);
			else                $value = &$attribute->get_default_value();
			$count *= $attribute->get_variety_count($value);
		}
		return $count;
	}

	 ####################################################
	# Returns a list of the varieties with summary names
	function get_variety_names($categoryid = 0, $recordid = 0) {
		if($recordid) $record = &$this->get_record($recordid);
		$names = array();
		foreach($this->option_attributeids as $attributeid) {
			$attribute = &$this->get_attribute($attributeid);
			if($recordid)      $value = &$record->get_attribute_value($attributeid);
			elseif($categoryid) $value = &$attribute->get_default_value($categoryid);
			else                $value = &$attribute->get_default_value();
			$names[$attributeid] = $attribute->get_variety_names($value);
			ksort($names[$attributeid]);
		}
		ksort($names);
		for(end($names); $attributeid = key($names); prev($names)) {
			$attribute = &$this->get_attribute($attributeid);
			$old_r = $r;
			if(!is_array($old_r) || !count($old_r)) $old_r = array("x" => "");
			$r = array();
			foreach($names[$attributeid] as $id => $name) {
				if(!$id) continue;
				# Coordinations are in the form 
				foreach($old_r as $old_id => $old_name) {
					if($name) $new_name = (($old_name)?"$attribute->name: $name / $old_name":"$attribute->name: $name");
					else      $new_name = $old_name;
					$r["x$attributeid"."i"."$id$old_id"] = $new_name;
				}
			}
		}
		return $r;
	}

	 ########################################
	# Gets a references to the parameter set
	function &get_pset() {
		$pset = &$this->temp[parameter_set];
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
		
		$e       = &$this->get_notitia_system();

		$backend = &$e->setup_backend();
		$backend->set_tab($this->table_name,$this->get_backend_href(),"Attribute: $this->name","Edit this attribute.","page");
		$backend->set_active_tab($this->table_name);

		$backend->set_heading("Edit Attribute - $backend->heading","page");
		$backend->set_name($this->name);
		$backend->set_id_string($this->id);
	
		 ###################
		# Check permissions
		if($this->recordid) {
			# TODO CHECK record EDITING PERMISSIONS
		} elseif($this->categoryid) {
			$category = &$this->get_category($this->categoryid);
			if(!$category->edit_attribute_access()) {
				$SESSION->login_screen($backend->title,"You must be logged in and have permission to edit the <i>$category->name</i> $e->name category attributes in order to proceed.");
			}
		} else {
			if(!$e->edit_attribute_access()) {
				$SESSION->login_screen($backend->title,"You must be logged in and have permission to edit the $e->name root category attributes in order to proceed.");
			}
		}

		$edit_role = $_REQUEST['edit_role'];
		if($edit_role && isset($this->roles[$edit_role])) {
			$role_handler = &$this->get_role_handler($edit_role);
			$role_handler->print_backend();
			return;
		}

		$pset = &$this->get_pset();
		if($pset->process($backend)) {
			$this->save_parameters();
		}
		$pset->print_backend($backend);
	
	}


	 ####################################################
	# Prints a summary of the variety set for the backend
	# this may or maynot be editable.
	function print_backend_summary($editable) {
		$e = &$this->get_notitia_system();
		echo("<table cellpadding=5 cellspacing=0 border=1 bordercolor=#000000>");
		echo("<tr><td colspan=2 align=center bgcolor=#bbbbbb><b>\"".$this->name()."\"</b><br>(".$this->get_variety_count($this->categoryid,$this->recordid)." varieties)</td></tr>");
		echo("<tr><td valign=top><center class=smallprint><b>Option Attributes</b></center>");		foreach($this->option_attributeids as $attributeid) {
			$attribute = &$this->get_attribute($attributeid);
			echo("<li class=smallprint><a href=\"".$attribute->get_backend_href()."\">$attribute->name</a></li>");
		}
		echo("<br><br><center class=smallprint><b>Varied Attributes</b></center><span class=smallprint>");
		foreach($e->get_variable_attribute_names($this->categoryid,$this->recordid) as $attributeid => $name) {
			$attribute = &$this->get_attribute($attributeid);
			$on = in_array($attributeid,$this->varied_attributeids);
			if($editable) {
				echo("<input type=checkbox name=\"varied_attributeids[$this->id][]\" value=$attributeid ".(($on)?"checked":"").">");
				echo("<a href=\"".$attribute->get_backend_href()."\">$name</a><br>");
			} elseif($on) {
				echo("<li class=smallprint>");
				echo("<a href=\"".$attribute->get_backend_href()."\">$name</a><br>");
			}
		}
		echo("</td><td valign=top><center class=smallprint><b>Varieties</b></center>");
		foreach($this->get_variety_names($this->categoryid,$this->recordid) as $varietyid => $name) {
			echo("<li class=smallprint>$name</li>");
		}
		echo("</td></tr></table>");
	}


	 ###############################################
	# Processes changes made in the backend summary
	function process_backend_summary() {
		$editable_variety_setids = $_REQUEST['editable_variety_setids'];
		$varied_attributeids = $_REQUEST['varied_attributeids'];
		if(!in_array($this->id,$editable_variety_setids)) return;
		$new_varied_attributeids = &$varied_attributeids[$this->id];
		$m = trim($this->update_varied_attributeids($new_varied_attributeids));
		# Delete the variety_set if it has no varied_attributeids
		if(count($this->varied_attributeids) <= 0) {
			$m = trim("$m\n".$this->delete());
		}
		return $m;
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
			echo('All Records');
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

	
}

?>
<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/xtras/data/perform/category_name_attribute/category_name_attribute.inc,v $
## $Revision: 1.3 $
## $Author: sagland $
## $Date: 2003/02/05 06:04:38 $
#######################################################################

#---------------------------------------------------------------------#

class Web_Extension_Notitia_Data_Perform_Category_Name_Attribute extends Web_Extension_Notitia_Data_Perform {

	 #############
	# Constructor
	function Web_Extension_Notitia_Data_Perform_Category_Name_Attribute($categoryid) {
		Web_Extension_Notitia_Data_Perform::Web_Extension_Notitia_Data_Perform($categoryid);
	}

	 ####################################
	# Returning lists of attributes
	function get_root_attribute_list() {
		$tree = &$this->get_category_tree();
		return $tree->get_context_attributes($this->categoryid);
	}
	function get_candidate_attribute_list() {
		$r  = array(); 
		foreach($this->get_root_attribute_list() as $id => $name) {
			$attribute = &$this->get_attribute($id);
			if($attribute->type == 'text') $r[$id] = $name;
		}
		return $r;
	}

	 ########################################
	# Returns get_override_method_types
	function get_override_method_types() {
		$sample_attribute = &$this->get_attribute(key($this->get_candidate_attribute_list()));
		if($sample_attribute->id) {
			return $sample_attribute->get_type_setting('variation_types');
		} else return array();
	}

	 #########
	# Does it
	function perform() {
		$attribute = &$this->get_attribute($this->parameters['attributeid']);
		if(!$attribute->id) return "Invalid attribute.";
		$parent  = &$this->parameters['parent_override_type'];
		$sibling = &$this->parameters['sibling_override_type'];
		reset($types = $attribute->get_type_setting('variation_types'));
		if(!isset($types[$parent]))  $parent = key($types);
		if(!isset($types[$sibling])) $sibling = key($types);

		# Prepare the attribute
		$attribute->play_role('force_null');
		$role = &$attribute->get_role_handler('force_null');
		$role->set_value('strict',0);
		$role->save_parameters();

		$tree = &$this->get_category_tree();
		foreach($tree->all_descendantids($this->categoryid) as $categoryid) {
			$name = &$tree->name($categoryid);
			$ms[] = $attribute->set_default_override($categoryid,$name,$parent,$sibling);
		}
		return implode("\n",$ms);
	}



}

?>
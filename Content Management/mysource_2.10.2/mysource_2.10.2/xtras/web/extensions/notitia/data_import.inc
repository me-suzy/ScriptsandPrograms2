<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/data_import.inc,v $
## $Revision: 1.5 $
## $Author: dofford $
## $Date: 2004/03/09 03:40:37 $
#######################################################################

#---------------------------------------------------------------------#

 ##############################################################
# Superclass for extensions which can import record data
# into the notitia system.
class Web_Extension_Notitia_Data_Import extends Web_Extension_Notitia_Object {
	
	# Temporary context
	var $categoryid = 0;

	# Temporary runtime data
	var $temp;
	var $parameters;


	 #############
	# Constructor
	function Web_Extension_Notitia_Data_Import($categoryid) {
		Web_Extension_Notitia_Object::Web_Extension_Notitia_Object();
		$this->categoryid = $categoryid;
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
		$this->set_generated_information();
		$this->parameters = array();
	}


	 ################################################################
	# set's up some useful information that shouldn't be cached
	function set_generated_information() {
		$e     = &$this->get_notitia_system();
		$xtras = &$e->get_xtras();
		$this->codename   = $xtras->codename("data/import",ereg_replace('^'.get_parent_class($this).'_','',get_class($this)));
		$this->name      = &$xtras->name("data/import",$this->codename);
		$this->xtra_path = "$e->xtra_path/xtras/data/import/$this->codename";
	}

	function get_backend_href() {
		if($this->categoryid) $e = &$this->get_category($this->categoryid);
		else                  $e = &$this->get_notitia_system();
		return $e->get_backend_href()."&data_import=$this->codename";
	}

	 ########################################
	# Gets a references to the parameter set
	function &get_pset() {
		$pset = &$this->temp['parameter_set'];
		if(get_class($pset) !== "parameter_set") {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/parameter_set.inc");
			$e = &$this->get_notitia_system();
			$pset = new Parameter_Set(get_class($this), "$this->xtra_path/$this->codename.pset", $this->parameters, $this);
		}
		return $pset;
	}


	 ################################################
	# Returns the backend object after setting it up 
	# in a standard kinda way
	function &setup_backend() {
		$e       = &$this->get_notitia_system();
		$xtras   = &$e->get_xtras();
		$backend = &$this->get_backend();
		$backend->set_tab(get_class($this),$this->get_backend_href(),"Data Import: $this->name",$xtras->description("data/import",$this->codename),"page");
		$backend->set_active_tab(get_class($this));
		$backend->set_subheading($this->name);
		$backend->set_hidden_field('data_import',$this->codename);
		return $backend;
	}


	 ####################
	# Prints the backend 
	function print_backend() {
		$backend = &$this->setup_backend();
		$pset = &$this->get_pset();
		$pset->set_tabs($backend,get_class($this),$this->get_backend_href());
		$pset->process($backend);
		$pset->print_backend($backend);
	}
	
	/**
	* Returns the list of attributes 
	 if depth = -1, returns an attribute list of the current categoryid and all its sub categories
	 depth = 0, returns an attribute list of the current categoryid
	 depth > 0, returns an attribute list of the current categoryid and its sub categories to depth specified
	*
	* @param  string $type		
	* @param  int $depth	
	* @access public
	* @return array
	*/
	function get_attribute_list($type ='', $depth=0) {
		$tree = &$this->get_category_tree();
		$r = array();
		$r = $tree->get_context_attributes($this->categoryid);

		if ($depth == -1 || $depth > 0) {
			if ($depth > 0) {
				$child_categoryids = $tree->get_descendantids_at_depth($this->categoryid, $depth);
			} else {
				$child_categoryids = $tree->all_descendantids($this->categoryid);
			}
			if (!empty($child_categoryids)) {
				foreach($child_categoryids as $categoryid) {
					$r += $tree->get_local_category_attributes($categoryid);
				}
			}
		}

		# remove any unwanted attribute types
		if ($type) {
			if (!empty($r)) {
				foreach($r as $attributeid => $name) {
					$attribute = &$this->get_attribute($attributeid);
					if($attribute->type != $type) unset($r[$attributeid]);
				}
			}
		}
		return $r;
	}
}
?>
<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/data_export.inc,v $
## $Revision: 1.1 $
## $Author: dwong $
## $Date: 2003/08/05 01:28:15 $
#######################################################################

#---------------------------------------------------------------------#

 ##############################################################
# Abstract base class for extensions which can export record data
# from the notitia system.
class Web_Extension_Notitia_Data_Export extends Web_Extension_Notitia_Object {

	var $paramaters;
	var $categoryid = 0;


	/*
	 * Constructor
	 *
	 * @access public
	 */
	function Web_Extension_Notitia_Data_Export($categoryid) {
		parent::Web_Extension_Notitia_Object();
		$this->categoryid = $categoryid;
	}

	function get_backend_href() {
		if($this->categoryid) $e = &$this->get_category($this->categoryid);
		else                  $e = &$this->get_notitia_system();
		return $e->get_backend_href()."&data_export=$this->codename";
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

	/*
	 * Export records.
     *
	 * @param Array $recordids - array of record ids to export
	 * @returns mixed - depends on the subclass
	 * @access public
	 */
	
	function export_records($recordids, $attribute_formats) {
		// subclasses implement this
		return null;
	}

	/* 
	 * Export a category's records (and possibly its descendent categories).
	 *
	 * @param int $category		The category to export
	 * @param int $depth		The depth to which categories will be printed out. (-1 means records from all descendents, 0 means to restrict only to this category)
	 * @param boolean $flatten	If true, then no category information will be printed for subcategories - but records will still be printed.
	 * @access public
	 */
	function export_category($categoryid, $attribute_formats, $depth=-1, $flatten=false) {
		// subclasses implement this
		return null;
	}



	 ################################################################
	# set's up some useful information that shouldn't be cached
	function set_generated_information() {
		$e     = &$this->get_notitia_system();
		$xtras = &$e->get_xtras();
		$this->codename   = $xtras->codename("data/export",ereg_replace('^'.get_parent_class($this).'_','',get_class($this)));
		$this->name      = &$xtras->name("data/export",$this->codename);
		$this->xtra_path = "$e->xtra_path/xtras/data/export/$this->codename";
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
		$backend->set_tab(get_class($this),$this->get_backend_href(),"Data Export: $this->name",$xtras->description("data/export",$this->codename),"page");
		$backend->set_active_tab(get_class($this));
		$backend->set_subheading($this->name);
		$backend->set_hidden_field('data_export',$this->codename);
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


}

?>

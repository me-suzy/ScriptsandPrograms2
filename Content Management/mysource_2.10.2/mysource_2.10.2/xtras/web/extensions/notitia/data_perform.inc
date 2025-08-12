<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/data_perform.inc,v $
## $Revision: 1.3 $
## $Author: ramato $
## $Date: 2003/07/15 01:04:41 $
#######################################################################

#---------------------------------------------------------------------#

 ##############################################################
# Superclass for extensions which can import record data
# into the notitia system.
class Web_Extension_Notitia_Data_Perform extends Web_Extension_Notitia_Object {
	
	# Temporary context
	var $categoryid = 0;

	# Temporary runtime data
	var $temp;
	var $parameters;


	 #############
	# Constructor
	function Web_Extension_Notitia_Data_Perform($categoryid) {
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
		$this->codename   = $xtras->codename("data/perform",ereg_replace('^'.get_parent_class($this).'_','',get_class($this)));
		$this->name      = &$xtras->name("data/perform",$this->codename);
		$this->version   = $xtras->version("data/perform",$this->codename);
		$this->xtra_path = "$e->xtra_path/xtras/data/perform/$this->codename";
	}

	function get_backend_href() {
		if($this->categoryid) $e = &$this->get_category($this->categoryid);
		else                  $e = &$this->get_notitia_system();
		return $e->get_backend_href()."&data_perform=$this->codename";
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
		$backend->set_tab(get_class($this),$this->get_backend_href(),"Data Perform: $this->name",$xtras->description("data/perform",$this->codename),"page");
		$backend->set_active_tab(get_class($this));
		$backend->set_subheading($this->name);
		$backend->set_hidden_field('data_perform',$this->codename);
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

	 ########################################
	# Returns the list of current attributes
	function get_attribute_list() {
		$tree = &$this->get_category_tree();
		return $tree->get_context_attributes($this->categoryid);
	}


}


?>
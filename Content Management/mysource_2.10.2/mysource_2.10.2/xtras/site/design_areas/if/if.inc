<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: xtras/site/design_areas/if/if.inc
## Desc: this is a container class that replicates an if statement
## $Source: /home/cvsroot/xtras/site/design_areas/if/if.inc,v $
## $Revision: 1.6 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:10 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/xtras.inc");
include_once("$INCLUDE_PATH/site_design_area.inc");
include_once(dirname(__FILE__).'/if_section_type.inc');
#---------------------------------------------------------------------#

class Site_Design_Area_If extends Site_Design_Area {

	var $_types;	# available if types
	var $sections = array();  # array holding the printable sections of the menu


	function Site_Design_Area_If(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->customisable = false;

		global $XTRAS_PATH;
		$this->_types = new XtrasRegistry($XTRAS_PATH.'/conditions', 'conditions');

	}#end Site_Design_Area_Menu()

	  ############################################################
	 # Reset the owner for this object, then set this as 
	# the owner for the sections
	function reset_owner(&$owner) {

		for($i = 0; $i < count($this->sections); $i++) {
			$this->sections[$i]->reset_owner($this);
		}#end for

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object, and the sections
	function &unset_owner() {

		for($i = 0; $i < count($this->sections); $i++) {
			$this->sections[$i]->unset_owner();
		}#end for

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();

		for($i = 0; $i < count($this->sections); $i++) {
			$tmp->sections[$i] = &$this->sections[$i]->copy();
		}#end for

		return $tmp;

	}#end copy()
	   


	   ##################################################################################
	  # Converts the object into a nice string
	 # replaces the type object with the packed version of itself and
	# the include file need to find its class definitions
	function pack() {

		$tmp_owner = &$this->unset_owner();
		$tmp_types = &$this->_types;
		unset($this->_types);

		$packed_sections = array();
		for($i = 0; $i < count($this->sections); $i++) {
			$packed_sections[$i]['type'] = $this->sections[$i]->type;
			$packed_sections[$i]['data'] = $this->sections[$i]->pack();
		}

		$old_sections   = $this->sections;
		$this->sections = $packed_sections;

		$packed = serialize($this);

		$this->sections = &$old_sections;
		$this->_types = &$tmp_types;
		$this->reset_owner($tmp_owner);

		return $packed;

	}#end packed()


	    ##################################################################################
	   # Takes a string, and unpacks it into the object itself.
	  # Includes the IF type class definitions 
	 # then creates the IF type from the packed version,
	# gets the xtras again, otherwise they would've been cached twice.
	function unpack($stringified, $class) {

		$new_object = Site_Design_Area::unpack($stringified, $class);

		# get a fresh copy of the if types
		global $XTRAS_PATH;
		$new_object->_types = new XtrasRegistry($XTRAS_PATH.'/conditions', 'conditions');

		$packed_sections = $new_object->sections;
		$new_object->sections = array();

		for($i = 0; $i < count($packed_sections); $i++) {
			list($success, $index) = $new_object->_init_section($packed_sections[$i]['type'], $packed_sections[$i]['data']);
		}

		# set up all owner vars
		$new_object->reset_owner($this->_owner);

		return $new_object;

	}#end unpack()



	 ####################################################
	# since it can only be a IF type, create one
	function create(&$tag) {

		return $this->create_section($tag);
	
	}#end create()


	 #################################################################
	# try to create the section that the passed tag represents
	function create_section(&$tag) {

		$type  = strtolower($tag['attributes']['type']);
		unset($tag['attributes']['type']);

		# try initialising the section
		list($success, $index) = $this->_init_section($type);
		if ($success) {

			$ret_val = array('index' => $index);
			
			# call create on it and return any additional attributes
			$additional_attributes = $this->sections[$index]->create($tag);

			# add the additional attributes returned by create()
			if (is_array($additional_attributes)) {
				$ret_val = array_merge($ret_val, $additional_attributes);
			}

			return $ret_val;

		}#end if

		return array();

	}#end create_section()


	 #######################################################
	# Attempts to initialise the IF section type
	function _init_section($type, $packed_string='') {

		# they haven't set anything, die and tell them they suck
		if (!$type) {
			 $this->_set_error("You need to specifiy the Section Type in order to create a IF section<br>\n<i>You Specified :</i><br>\n<b>Section Type :</b> $type", __FILE__, __LINE__);
			return array(false, '', '');
		}#end if 

		$section_types = $this->_types->list_type();

		# if we don't know how to deal with this if type, die
		if (!isset($section_types[$type])) {
			$this->_set_error("If Section Type '$type' unknown", __FILE__, __LINE__);
			return Array(false, '');
		}

		if (!isset($this->sections)) $this->sections = array();
		$index = count($this->sections);

		# if we were given a packed string then unpack that object here
		if ($packed_string != '') {
			eval('$this->sections[$index] = '.Site_Design_Area_If_Section_Type.'::unpack($packed_string, \'Site_Design_Area_If_Section_Type\');');
		# else just create a new section
		} else {
			$this->sections[$index] = new Site_Design_Area_If_Section_Type($this, $type);
		}
	
		return array(true, $index);

	}#end _init_section()


	 #################################################################
	# since it can only be a section, paint it
	function paint(&$tag_attributes) {

		$this->paint_section($tag_attributes);

	}#end paint()

	 #################################################################
	# paint the section of the menu defined by the tag attributes
	function paint_section(&$tag_attributes) {
		
		$this->sections[$tag_attributes['index']]->paint();

	}#end paint_section()



}#end class Site_Design_Area_If

?>
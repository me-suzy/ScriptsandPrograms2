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
## File: xtras/site/design_areas/menu/menu.inc
## Desc: this is a generic container class for dealing with menus
##       most of the processing for the menu is done by the menu 'type'
## $Source: /home/cvsroot/xtras/site/design_areas/menu/menu.inc,v $
## $Revision: 2.20 $
## $Author: brobertson $
## $Date: 2003/09/10 08:16:32 $
#######################################################################
include_once(dirname(__FILE__)."/menu_section_type.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Menu extends Site_Design_Area {

	var $_types;              # available menu section types
	var $sections = Array();  # array holding the printable sections of the menu


	var $_excluded_pageids; # an array for caching the get_excluded_pageids() results
							# (should only be called during the execution of paint())
							# DOES NOT get saved


	function Site_Design_Area_Menu(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		$this->_types   = new XtrasRegistry(dirname(__FILE__)."/section_types", "Site_Design_Area_Menu");

	}#end Site_Design_Area_Menu()

	  ############################################################
	 # Reset the owner for this object, then set this as 
	# the owner for the sections
	function reset_owner(&$owner) {

		for(reset($this->sections); 
			NULL !== ($level = key($this->sections));
			next($this->sections)) {

			for($i = 0; $i < count($this->sections[$level]); $i++) {
				$this->sections[$level][$i]->reset_owner($this);
			}#end for

		}#end for

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object, and the sections
	function &unset_owner() {

		for(reset($this->sections); 
			NULL !== ($level = key($this->sections));
			next($this->sections)) {

			for($i = 0; $i < count($this->sections[$level]); $i++) {
				$this->sections[$level][$i]->unset_owner();

			}#end for

		}#end for

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();

		for(reset($this->sections); 
			NULL !== ($level = key($this->sections));
			next($this->sections)) {

			for($i = 0; $i < count($this->sections[$level]); $i++) {
				$tmp->sections[$level][$i] = &$this->sections[$level][$i]->copy();

			}#end for

		}#end for

		return $tmp;

	}#end copy()
	   
	   ##################################################################################
	  # Converts the object into a nice string
	 # replaces the type object with the packed version of itself and
	# the include file need to find its class definitions
	function pack() {

		# just make sure to kill the _exclude_pageids
		unset($this->_exclude_pageids);

		$tmp_owner = &$this->unset_owner();
		$tmp_types = &$this->_types;
		unset($this->_types);

		$packed_sections = array();

		for(reset($this->sections); 
			NULL !== ($level = key($this->sections));
			next($this->sections)) {

			for($i = 0; $i < count($this->sections[$level]); $i++) {
				$packed_sections[$level][$i]['type'] = $this->sections[$level][$i]->type;
				$packed_sections[$level][$i]['data'] = $this->sections[$level][$i]->pack();
			}#end for

		}#end for
		
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
	  # Includes the mrenu type class definitions 
	 # then creates the menu type from the packed version, 
	# gets the xtras again, otherwise they would've been cached twice.
	function unpack($stringified, $class) {

		$new_object = Site_Design_Area::unpack($stringified, $class);
		# if something went wrong, return null
		if ($new_object === null) return null;

		# get a fresh copy of the menu types
		$new_object->_types = new XtrasRegistry(dirname(__FILE__)."/section_types", "Site_Design_Area_Menu");

		$packed_sections = $new_object->sections;
		$new_object->sections = Array();

		for(reset($packed_sections); 
			NULL !== ($level = key($packed_sections));
			next($packed_sections)) {

			for($i = 0; $i < count($packed_sections[$level]); $i++) {

				list($success, $level, $index) = $new_object->_init_section($packed_sections[$level][$i]['type'], $level, $packed_sections[$level][$i]['data']);

			}#end for

		}#end for

		return $new_object;

	}#end unpack()

	  #########################################################################
	 # Takes a old version of itself and uses the old versions custom vars to 
	# attempting to save any customisations, previously made
	function update(&$old_this) {

		if (!Site_Design_Area::update($old_this)) return;

		for(reset($this->sections); 
			NULL !== ($level = key($this->sections));
			next($this->sections)) {

			for($i = 0; $i < count($this->sections[$level]); $i++) {

				if ($old_this->sections[$level][$i]) {
					$this->sections[$level][$i]->update($old_this->sections[$level][$i]);
				}

			}#end for

		}#end for

	}#end update()

	 ####################################################
	# since it can only be a menu section, create one
	function create(&$tag) {

		return $this->create_section($tag);

	}#end create()

	 #################################################################
	# try to create the menu section that the passed tag represents
	function create_section(&$tag, $parent_level=-1, $parent_index=-1) {

		$level   = strtolower($tag['attributes']['level']);
		$type    = strtolower($tag['attributes']['type']);

		 ###########################################
		# backwards compatabilty
		$section      = strtolower($tag['attributes']['section']);
		$display_type = strtolower($tag['attributes']['display_type']);
		switch($section) {
			case "top_level"   :
				$level = "top"; 
				$type = ($display_type == "tabs") ? "tabs" : "normal";
			break;

			case "sub_level"   :
				$level = "sub"; 
				$type  = "normal";
			break;

			case "child_pages" :
				$level = "child"; 
				$type  = "stalks";
			break;

			case "constant_button" :
				$level = "constant_button"; 
				$type  = "constant_button";
			break;

		}#end switch
		unset($tag['attributes']['section']);
		unset($tag['attributes']['display_type']);

		 # end backwards compatabilty
		###########################################

		unset($tag['attributes']['level']);
		unset($tag['attributes']['type']);

		 ####################################
		# validate the level with the parent level
		$level = $this->validate_level($level, $type, $parent_level);

		# try initialising the section
		list($success, $level, $index) = $this->_init_section($type, $level);
		if ($success) {

			$ret_val = Array('level' => $level, 'index' => $index);

			# call create on it and return any additional attributes
			$additional_attributes = $this->sections[$level][$index]->create($tag, $level, $index, $parent_level, $parent_index);

			# add the additional attributes returned by create()
			if (is_array($additional_attributes)) {
				$ret_val = array_merge($ret_val, $additional_attributes);
			}

			return $ret_val;

		}#end if

		return Array();

	}#end create_section()

	 ########################################
	# validates the level that is passed
	function validate_level($level, $type="", $parent_level=-1) {

		$level = strtolower($level);
		# convert the nice text version to usable numbers
		switch($level) {
			case "top" :
				$level = 0;
			break;

			case "sub" :
				$level = 1;
			break;

			case "child" :
				$level = 2;
			break;

			case "contant_button" :
			case "page"           :
			case "page_children"  :
				# do nothing with these, just to be left alone
			break;

			default :
				# special case for constant buttons
				if ($type == "constant_button") {
					$level = "constant_button";
				# else convert everything else to ints
				} else {
					$level = abs((int) $level);  # just in case :)
				}#end if 

		}#end switch

		 ##################################################
		# check that this level is nested under its parent
		if ((string) $level != "constant_button") {
			# if our parent is set to be a page level then we are on the page_children level
			if ((string) $parent_level == "page") {
				 $level = "page_children";
			# if they want the level to be 'page' or 'page_kids', it must NOT be nested inside another level
			} elseif ((string) $level == "page" || (string) $level == "page_children") {
				$parent_level = (int) $parent_level;
				if ($parent_level > 0) $level = $parent_level + 1;
			} else {
				$level        = (int) $level;
				$parent_level = (int) $parent_level;
				if ($level <= $parent_level) $level = $parent_level + 1;
			}
		}#end if 

		return $level;

	}#end validate_level()

	 #######################################################
	# Attempts to initialise the menu section type
	function _init_section($type, $level, $packed_string='') {

		# they haven't set anything, die and tell them they suck
		if (!$type || !isset($level)) {
			$this->_set_error("You need to set specifiy the Menu Level AND Section Type in order to create a menu section<br>\n<i>You Specified :</i><br>\n<b>Menu Level :</b> $level<br>\n<b>Section Type :</b> $type", __FILE__, __LINE__);
			return Array(false, "", "");
		}#end if 

		$level = $this->validate_level($level, $type);

		$section_types = $this->_types->list_type();
		# if we don't know how to deal with this menu type, die
		if (!isset($section_types[$type])) {
			$this->_set_error("Menu Section Type '$type' unknown", __FILE__, __LINE__);
			return Array(false, "", "");
		}

		# OK, so the section type exists, let's hope it's there
		include_once(dirname(__FILE__)."/section_types/".$type."/".$type.".inc");

		$class = 'Site_Design_Area_Menu_Section_Type_'.$type;
		# if the class doesn't exist then die
		if (!class_exists($class)) {
			$this->_set_error("Class '$class' unknown", __FILE__, __LINE__);
			return Array(false, "", "");
		}

		if (!isset($this->sections[$level])) $this->sections[$level] = Array();
		$index = count($this->sections[$level]);

		# if we were given a packed string then unpack that object here
		if ($packed_string != '') {
			eval('$this->sections[$level][$index] = '.$class.'::unpack($packed_string, $class);');
		
		# else just create a new section
		} else {
			$this->sections[$level][$index] = new $class($this);

		}#end if

		return Array(true, $level, $index);

	}#end _init_section()

	 #################################################################
	# since it can only be a section, paint it
	function paint(&$tag_attributes) {

		$this->paint_section($tag_attributes);

	}#end paint()

	 #################################################################
	# paint the section of the menu defined by the tag attributes
	function paint_section(&$tag_attributes, $pageid=0) {
		# if we are trying to print a menu level identified by its has an index
		if (isset($tag_attributes['level']) && isset($tag_attributes['index'])) {
			# special case for constant buttons
			if ((string) $tag_attributes['level'] == "constant_button") {
				$this->sections['constant_button'][$tag_attributes['index']]->paint($tag_attributes);
			
			# else just a normal level (integer from zero up), make sure we have an index
			} else if (isset($tag_attributes['index'])) {

				# the section needs to exist before we can paint it
				if ($this->sections[$tag_attributes['level']][$tag_attributes['index']]) {
					
					$temp_pageid = $this->sections[$tag_attributes['level']][$tag_attributes['index']]->get_val('use_pageid');
					if ($temp_pageid > 0) $pageid = $temp_pageid;
					list($level, $pageids_array) = $this->get_level_pageids($tag_attributes['level'], $pageid, $tag_attributes['index']);

					# if there are pageids to print
					if (isset($pageids_array)) {
						$exclude_pageids = &$this->get_excluded_pageids();
						$this->sections[$tag_attributes['level']][$tag_attributes['index']]->paint($pageids_array, $exclude_pageids, $level, $pageid);

					}#end if

				}#end if

			}#end if

		}#end if

	}#end paint()

	 #############################################################
	# return the pageids for the passed level in the page lineage
	function get_level_pageids($level, $pageid=0, $index=0) {

		static $page_lineage = Array(); # the current page_lineage

		# we need to get the page here because pageid could be set
		# to show the menu from the perspective of a page in another site
		$page = &$this->get_page($pageid);
		$site = &$this->get_site($page->siteid);
		
		# if we are not supposed to be able to see this page - we shouldnt be able to
		# see it's menu either - so return no pageids
		if (!$page->read_access()) return array($level,array());
		
		$page_index = &$site->get_page_index();

		# convert $level to the proper integer
		$level = $this->_get_valid_level($level, $pageid);

		# so we're the top level, too easy :P
		if ($level == 0){
			$pageids_array = &$site->get_top_pageids();

		# OK so we are some where further down the scale
		} else {

			# if we haven't got the lineage yet get it
			if (!isset($page_lineage[$page->id])) {
				$page_lineage[$page->id] = array_keys($page->get_lineage());
			}

			# if their is a pageid and it isn't in the current pages lineage
			# we need to get the level from the pageid's lineage
			if ($pageid){

				# if they want a pages from pageid's level or higher, got get it
				if ($page_index[$pageid]['level'] >= $level) {
					
					do {
						$pageid = $page_index[$pageid]['parentid'];
					} while($page_index[$pageid]['level'] > $level);

					$level_pageid = $pageid;

				# else the level they are after is below the pageid's page
				} else {
					
					# if the level they want is more than one below, we can't know what they actually want
					if ($page_index[$pageid]['level'] - 1 > $level) {
						$this->_set_error("Unable to get pageids for level '$level' as it is more than one level below Page $pageid", __FILE__, __LINE__);
					}
										
					$level_pageid = $pageid;

				}#end if

			# else get the level page relative to the page lineage
			} else {

				# the level page id is the page whose childids we are going to return
				$level_pageid = (isset($page_lineage[$page->id][$level - 1])) ? $page_lineage[$page->id][$level - 1] : 0;
				
				# if the level doesn't exist in the page lineage 
				# then return a blank array()
				if (!$level_pageid) {
					return Array();
				}#end if

			}#end if

			$pageids_array = &$page_index[$level_pageid]['childids'];

		}#end if

		# Now lets unset any pageids which are not visible in the menu
		for(reset($pageids_array);$pageid = current($pageids_array);next($pageids_array)) {
			if($page_index[$pageid]['effective_visible']) $final_pageids_array[] = $pageid;
		}

		return Array($level, $final_pageids_array);

	}#end get_level_pageids()

	 ######################################################################
	# return a level as an integer for the based on the passed pageid
	function _get_valid_level($level, $pageid=0) {

		# we need to get the page here because pageid could be set
		# to show the menu from the perspective of a page in another site
		$page = &$this->get_page($pageid);
		$site = &$this->get_site($page->siteid);
		
		$page_index = &$site->get_page_index();

		# if the level is page then we want the pages on the same level as the current page (ie it and its siblings)
		if ((string) $level == 'page') {
			$page  = &$this->get_page($pageid);
			$level = (int) $page_index[$page->id]['level'];

		# if the level is page_children then we want the current pages kids
		} elseif ((string) $level == 'page_children') {
			if (!$pageid) {
				$page  = &$this->get_page();
				$pageid = $page->id;
			}
			$level = (int) $page_index[$pageid]['level'] + 1;
		} else {
			$level = (int) $level;
		}

		return $level;

	}#end _get_valid_level()


	  ##################################################################
	 # returns an array of pageids that are not to be seen in the menu
	function &get_excluded_pageids() {

		if (!isset($this->_excluded_pageids)) {
			$this->_excluded_pageids = array();

			for(reset($this->sections); 
				NULL !== ($level = key($this->sections));
				next($this->sections)) {

				for($i = 0; $i < count($this->sections[$level]); $i++) {
					$this->_excluded_pageids = array_merge($this->_excluded_pageids, $this->sections[$level][$i]->get_excluded_pageids());
				}#end for

			}#end for

		}#end if

		return $this->_excluded_pageids;

	}#end get_excluded_pageids()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend() {
		$web_system = &get_web_system();
		$backend = &$web_system->get_backend();

		$changes_made = false;

		for(reset($this->sections); 
			NULL !== ($level = key($this->sections));
			next($this->sections)) {

			$backend->open_section("Menu Level '".ucwords(str_replace("_", " ", $level))."'");

			for($i = 0; $i < count($this->sections[$level]); $i++) {
				$changes_made |= $this->sections[$level][$i]->print_user_backend("menu_".$level."_".$i, $i);
			}#end for

		}#end for

		return $changes_made;

	}#end print_user_backend()

	  ################################################
	 # Returns a description of the design area 
	function get_description() {

		$desc = "Change the appearance of the menu system";
		return $desc;

	}#end get_description()

	 ########################################################################################
	# this fn returns a boolean indicating whether the page is in the current page lineage
	function current_page($pageid) {
		static $cache = Array(); # cache the results to stop the need to process each time called
								 # NOTE : this cache seems to be available over all instances of 
								 #        an object.

		if (!$pageid) return false;

		if (!isset($cache[$pageid])) { 
			$page = &$this->get_page();
			$page_lineage = array_keys($page->get_lineage());
			# if this pageid is in the page lineage 
			$cache[$pageid] = in_array($pageid,$page_lineage);
		}

		return $cache[$pageid];

	}#end current_page()


}#end class Site_Design_Area_Menu

?>

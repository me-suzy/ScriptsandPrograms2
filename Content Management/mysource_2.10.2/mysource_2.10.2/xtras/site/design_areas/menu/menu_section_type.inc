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
## File: xtras/site/design_areas/menu/type/original/original_section.inc
## Desc: handle's the individual sections of the original design
##       this be where most of the processing is done.
## $Source: /home/cvsroot/xtras/site/design_areas/menu/menu_section_type.inc,v $
## $Revision: 2.15 $
## $Author: brobertson $
## $Date: 2003/09/03 09:01:25 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Menu_Section_Type extends Site_Design_Area {

	var $type;          # the section type, generated
	var $contents;
	var $parent_level;
	var $parent_index;

	var $_active_pageid = 0; # a variable containing the active pageid so that our get_vals() 
							 # can get called from a nested design area without the need to know 
							 # which pageid we are up to

	var $_get_val_cache; # an array for caching get_val calls that require processing
						# (and that SHOULDN'T be changed during the execution of the paint())
						# DOES NOT get saved

	function Site_Design_Area_Menu_Section_Type(&$_owner) {

		$this->Site_Design_Base($_owner);
		
		$this->type = strtolower(substr(get_class($this), strlen("Site_Design_Area_Menu_Section_Type_")));
		$this->contents = Array();

		$this->_set_var("start_printing_at",  0, "text", "start printing out the pages from this item down in the page order");
		$this->_set_var("pages_to_print",     0, "text", "print out this many pages, (zero, blank, empty means print all)");
		$this->_set_var("page_link_format",  "", "text", "");
		$this->_set_var("only_print_current",  false, "boolean", "print the menu as normal, but only have the section for the current page visible");
		$this->_set_var("use_pageid",     0, "text", "print the menu from this page's perspective (zero, blank, empty means current page)");
		

	}#end constructor

	  #########################################
	 # Converts the object into a nice string
	function pack() {

		# just make sure to kill the get val cache
		unset($this->_get_val_cache);
		return Site_Design_Area::pack();

	}#end packed()

	 #########################################
	# create the object from the tag
	function create(&$tag, $level, $_index, $parent_level, $parent_index) {

		 ########################################
		# Set up any variables
		$this->parent_level = $parent_level;
		$this->parent_index = $parent_index;
		$this->_set_variables($tag, true);
		$this->_set_nested_areas($tag, "menu");

		 ############################################
		# process the contents of the element
		foreach($tag['contents'] as $index => $element) {

			# if we are dealing with a tag
			switch ($element['_type']) {

				case "TAG" : 

					# if this is a sub level ( the test for 'section' is only for backwards compatibilty)
					if ($element['attributes']['type'] || $element['attributes']['section']) {

						$contents_arr = Array("_type" => "SUB_LEVEL");

						$menu = &$this->get_menu();

						# call create on it and return any additional attributes
						$additional_attributes = $menu->create_section($element, $level, $_index);

						# add the additional attributes returned by create()
						if (is_array($additional_attributes)) {
							$contents_arr = array_merge($contents_arr, $additional_attributes);
						}

						$this->contents[] = $contents_arr;

					# must be a print tag, we'll deal with it later, 
					# so just append to contents
					} else {

						$this->contents[] = $element;

					}#end if

				break;

				case "DESIGN_AREA" : # dealt with above so just add to contents
				case "HTML"        : # nothing to do to this yet, so just add to contents array

						$this->contents[] = $element;

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

	}#end create()
 
	 ###################################################################
	# paint the passed pages, excluding some that are not needed
	function paint($pages, &$exclude_pageids, $level, $pageid) {

		$pages = $this->get_pages_to_print($pages);

		# no point going any further if there are no pages
		if (!$pages) return;

		foreach($this->contents as $element) {

			switch($element['_type']) {
				# sub level, call paint on the menu design area
				case "SUB_LEVEL" :
					$menu = &$this->get_menu();
					$menu->paint_section($element, 0, $level);

				break;

				# plain html element just echo its contents
				case "HTML" :
					echo $element['contents'];

				break;

				# nested design area so print/paint it
				case "DESIGN_AREA" :
					$this->_print_nested_area($element);

				break;

				# taged element ... should really only be a print element
				case "TAG" :

					switch($element['operation']) {
						case "print" : 
							$this->print_val($element, $pageid);
						break;
					}

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach contents

	}#end paint()

	 #########################################################################
	# returns a trimmed pages array so that we only print out 
	# what we need for this section, based upon the start_printing_at and pages_to_print values
	function get_pages_to_print(&$all_pages) {

		if (!$all_pages) return array();

		$start_at       = abs((int) $this->get_val('start_printing_at'));
		$pages_to_print = abs((int) $this->get_val('pages_to_print'));
		$only_print_current = $this->get_val('only_print_current');
		
		if ($only_print_current) {
			$valid_pages = array();
			$page = &$this->get_page();
			$valid_pages[] = $page->id;
			$current_page = &$this->get_page($page->parentid);
			while ($current_page->parentid != 0) {
				$valid_pages[] = $current_page->id;
				$current_page = &$this->get_page($current_page->parentid);
			}
			$valid_pages[] = $current_page->id;

			$new_pages = array();
			foreach ($all_pages as $pageid) {
				if (in_array($pageid,$valid_pages)) $new_pages[] = $pageid;
			}
			unset($page);
			unset($current_page);
			return $new_pages;
		}

		# if we just want all pages then return them
		if (empty($start_at) && empty($pages_to_print)) {
			return $all_pages;

		# else some processing is needed
		} else {

			# get the pages in a proper indexed array
			$pages = array_values($all_pages);
			$new_pages = array();
			for($i = $start_at; 
				$i < count($pages) && (empty($pages_to_print) || $i < ($start_at + $pages_to_print)); 
				$i++) {
				$new_pages[] = $pages[$i];
			}#end for

			return $new_pages;

		}#end if

	}#end get_pages_to_print()


	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $pageid=0, $check_current=false) {

		if (!$this->_get_val_cache) $this->_get_val_cache = Array();

		# if there is no pageid passed but we have an active pageid
		# set the pageid to that active pageid
		if (!$pageid && $this->_active_pageid) $pageid = $this->_active_pageid;

		# if the variable that we are checking has the 2 values, 
		# a normal and one for when we are talking about the current page
		# return the appropriate one (assuming the second value exists)
		if ($check_current) {

			$name = strtolower($name);

			if ($this->_current_page($pageid)) {
				# if this value ain't in the cache go get it
				if (!isset($this->_get_val_cache[$name."_current"])) {
					$this->_get_val_cache[$name."_current"] = Site_Design_Area::get_val($name."_current");
				}
				if ($this->_get_val_cache[$name."_current"]) {
					$name .= "_current";
				}
			}
			$name = strtolower($name);
			# if this value ain't in the cache go get it
			if (!isset($this->_get_val_cache[$name])) {
				$this->_get_val_cache[$name] = Site_Design_Area::get_val($name);
			}
			return $this->_get_val_cache[$name];

		}#end if

		if (strtolower($name) == 'page_link') {
			if ($format = Site_Design_Area::get_val('page_link_format')) {
				$replacements = Array('page_link' => Site_Design_Area::get_val($name, $pageid));
				return replace_keywords($format, $replacements);
			}
		}

		# lets hope they declared it themselves
		return Site_Design_Area::get_val($name, $pageid);

	}#end get_val()

	 #######################################################################################
	# returns the value for the passed variable name from the owner's scope
	function _get_val_from_owner($name, $pageid='') {
		if ($this->parent_level != -1 && $this->parent_index != -1 && isset($this->_owner->sections[$this->parent_level][$this->parent_index])) {
			return $this->_owner->sections[$this->parent_level][$this->parent_index]->get_val($name, $pageid);
		} else if ($this->_owner) {
			if (method_exists($this->_owner, 'get_val')) {
				return $this->_owner->get_val($name, $pageid);
			}
		}
		return '';
	}

	 ########################################################################################
	# this fn returns a boolean indicating whether the page is in the current page lineage
	function _current_page($pageid) {

		$menu = &$this->get_menu();
		return $menu->current_page($pageid);

	}#end _current_page()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name="", $index=0) {
		$web_system = &get_web_system();

		$changes_made = false;

		$title = ucwords(str_replace("_", " ", $index." - ".$this->type));

		$prefix_name .= "_".$this->type;

		$uneditable = array();

		$backend = &$web_system->get_backend();
		$changes_made |= Site_Design_Base::print_vars_backend($backend, $prefix_name, $uneditable, $title);

		return $changes_made;

	}#end print_user_backend()

	  ##################################################################
	 # returns an array of pageids that are not to be seen in the menu
	function get_excluded_pageids() {
		return Array();
	}#end get_excluded_pageids()

	 #######################################################################################
	# returns a reference to the menu design area 
	function &get_menu() {
		# if out owner ain't the one, try our owner's owner
		if (!is_a($this->_owner, 'site_design_area_menu')) {
			if (method_exists($this->_owner, "get_top_owner")) {
				return $this->_owner->get_menu();
			}#end if
		}#end if
		return $this->_owner;

	}#end if

}#end class  

?>
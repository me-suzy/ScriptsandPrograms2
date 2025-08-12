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
## Desc: handles the printing of "normal" menu section where the designer has created the menu
## $Source: /home/cvsroot/xtras/site/design_areas/menu/section_types/normal/normal.inc,v $
## $Revision: 2.15 $
## $Author: brobertson $
## $Date: 2003/09/03 09:01:26 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Menu_Section_Type_Normal extends Site_Design_Area_Menu_Section_Type {

	var $sub_section; # the section within this tag that is looped over for each page we are printing

	function Site_Design_Area_Menu_Section_Type_Normal(&$_owner) {

		$this->Site_Design_Area_Menu_Section_Type($_owner);
		$this->customisable = true;
		
	}#end constructor

	  ############################################################
	 # Reset the owner for this object, then reset the sections 
	# owner to this object 
	function reset_owner(&$owner) {

		if ($this->sub_section) {
			$this->sub_section->reset_owner($this);
		}

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the sections beneath it
	function &unset_owner() {

		if ($this->sub_section) {
			$this->sub_section->unset_owner();
		}

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		if ($this->sub_section) {
			$tmp->sub_section = &$this->sub_section->copy();
		}
		return $tmp;

	}#end copy()

	  #########################################################################
	 # Takes a old version of itself and uses the old versions custom vars to 
	# attempting to save any customisations, previously made
	function update(&$old_this) {

		if (!Site_Design_Area::update($old_this)) return;

		if ($old_this->sub_section && $this->sub_section) {
			$this->sub_section->update($old_this->sub_section);
		}

	}#end update()

	 #######################################################################
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

					# if this is the sub section that we use to loop over the pages
					if ($element['attributes']['section'] == "page") {

						# kill it because it's not needed anymore
						unset($element['attributes']['section']);

						$class_name = get_class($this)."_Page";
						
						$this->sub_section = new $class_name($this);
						$this->sub_section->create($element, $level, $_index, $parent_level, $parent_index);

						# append to the contents array so we know were to print this
						$this->contents[] = Array("_type" => "SUB_SECTION");


					# else if this is a sub level ( the test for 'section' is only for backwards compatibilty)
					} else if ($element['attributes']['type'] || $element['attributes']['section']) {

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

				# sub level, call paint on the meun design area
				case "SUB_LEVEL" :
					$menu = &$this->get_menu();
					$menu->paint_section($element);

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

				# sub section element
				case "SUB_SECTION" :
					$this->sub_section->paint($pages, $exclude_pageids, $pageid);

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach contents

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name="", $index=0) {

		$changes_made = false;
		$changes_made |= Site_Design_Area_Menu_Section_Type::print_user_backend($prefix_name);

		if ($this->sub_section && $this->sub_section->customisable) {
			$changes_made |= $this->sub_section->print_user_backend($prefix_name, $index);
		}

		return $changes_made;

	}#end print_user_backend()

}#end class Site_Design_Area_Menu_Section_Type_Normal


 ################################################################################################
# takes care of the looping over each page
class Site_Design_Area_Menu_Section_Type_Normal_Page extends Site_Design_Area_Menu_Section_Type {
	
	var $has_sub_levels = false;	# var set at create time, 
									# and used at paint time to help decide whether 
									# or not to print an excluded page

	function Site_Design_Area_Menu_Section_Type_Normal_Page(&$_owner) {

		$this->Site_Design_Area_Menu_Section_Type($_owner);
		$this->customisable = true;

		# we don't need these because they are taken care of by our parent
		$this->delete_var("start_printing_at");
		$this->delete_var("pages_to_print");

		  ################
		 #   EDITABLE   #
		################
		$this->_set_var("show_all_pages", "0", "boolean", "");
		$this->_set_var("class",         "", "text", "");
		$this->_set_var("class_current", "", "text", "");

		  ################
		 #  UNEDITABLE  #
		################
		$this->_set_var("bullet_image",         "", "text");
		$this->_set_var("bullet_image_current", "", "text");

	}#end constructor


	 #######################################################################
	# create the object from the tag
	function create(&$tag, $level, $_index, $parent_level, $parent_index) {

		Site_Design_Area_Menu_Section_Type::create($tag, $level, $_index, $parent_level, $parent_index);

		 ################################################
		# check to see if this section has a sub level 
		$this->has_sub_levels = false;
		foreach($tag['contents'] as $index => $element) {

			if ($element['_type'] == "SUB_LEVEL") {
				$this->has_sub_levels = true;
				break;
			}#end if
		
		}#end foreach

	}#end create()


	 ###################################################
	# loops over the contents array for each of the pages
	function paint(&$pages, &$exclude_pageids, $pageid) {

		$page = &$this->get_page($pageid);
		$site = &$this->get_site($page->siteid);
		$page_index = &$site->get_page_index();

		$pages_displayed = false;
		foreach($pages as $pageid) {
			$this->_active_pageid = $pageid;

			# if this page is on the exclude list
			if (in_array($pageid, $exclude_pageids)) {

				# OK, if this section has a sub level
				if ($this->has_sub_levels) {

					# we have a need to print that sub-level because  
					# the specific SHOW ALL PAGES is set or page is somewhere in the current in the page lineage 
					# and this page has kids
					$print_page = (
									(
										$this->get_val("SHOW_ALL_PAGES") 
										|| $this->_current_page($pageid)
									)
									&& !empty($page_index[$pageid]['childids'])
								  );

				# no sub-section so don't need to print this
				} else {

					$print_page = false;
					
				}#end if

				# go to the next page id
				if (!$print_page) continue;

			}#end if excluded

			$pages_displayed = true;

			foreach($this->contents as $element) {

				switch($element['_type']) {

					# sub level, call paint on the menu design area
					case "SUB_LEVEL" :
						# only paint if this page is in the lineage 
						# or we are supposed to be showing all pages
						if ($this->get_val("SHOW_ALL_PAGES") 
							|| $this->_current_page($pageid)) { 

							$menu = &$this->get_menu();
							$menu->paint_section($element, $pageid);

						}

					break;

					# plain html element just echo its contents
					case "HTML" :
						echo $element['contents'];

					break;

					# plain html element just echo its contents
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

			}#end for

		}#end foreach $pages
		$this->_active_pageid = 0;

		if (!$pages_displayed) {
			?><img src="<?=lib_href('images/blank.gif');?>" width="1" height="1" border="0"><?
		}

	}#end paint()

	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $pageid=0) {

		if (!$this->_get_val_cache) $this->_get_val_cache = Array();

		switch(strtolower($name)) {
			case "class" : 
				return Site_Design_Area_Menu_Section_Type::get_val($name, $pageid, true);
			break;

			case "bullet_image" :
				if ($this->_current_page($pageid)) {
					if (Site_Design_Area_Menu_Section_Type::get_val($name."_current")) {
						$name .= "_current";
					}
				}
				$name = strtolower($name);

				# if this image ain't in the cache go get it
				if (!isset($this->_get_val_cache[$name])) {
					$this->_get_val_cache[$name] = $this->get_image_href($name, true);
				}#end if isset
				return $this->_get_val_cache[$name];

			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area_Menu_Section_Type::get_val($name, $pageid);
		}#end switch

	}#end get_val()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name="", $index=0) {
		$web_system = &get_web_system();

		$changes_made = false;

		$title = ucwords(str_replace("_", " ", $index." - ".$this->type));

		$prefix_name .= "_".$this->type;

		$uneditable = array("bullet_image", "bullet_image_current");

		$backend = &$web_system->get_backend();
		$changes_made |= Site_Design_Base::print_vars_backend($backend, $prefix_name, $uneditable, $title);

		return $changes_made;

	}#end print_user_backend()

 
}#end class Site_Design_Area_Menu_Section_Type_Normal_Page

?>
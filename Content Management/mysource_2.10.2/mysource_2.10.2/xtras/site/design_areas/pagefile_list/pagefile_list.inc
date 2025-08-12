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
## File: xtras/site/designs_areas/pagefile_list/pagefile_list.inc
## Desc: this class is used to print the list of attached files
## $Source: /home/cvsroot/xtras/site/design_areas/pagefile_list/pagefile_list.inc,v $
## $Revision: 2.6 $
## $Author: csmith $
## $Date: 2004/01/19 03:14:03 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
include_once(dirname(__FILE__)."/pagefile_list_listing.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Pagefile_List extends Site_Design_Area {

	var $sections; # an array of the sub sections to be used for looping over

	function Site_Design_Area_Pagefile_List(&$_owner) {
		$this->Site_Design_Base($_owner);
		
		$this->sections = array(); # The file printing design area

	}#end Site_Design_Area_Pagefile_List()

	  ############################################################
	 # Reset the owner for this object, then reset the buttons 
	# owner to this object 
	function reset_owner(&$owner) {

		for(reset($this->sections); 
			$key = key($this->sections); 
			next($this->sections)) {

			$this->sections[$key]->reset_owner($this);

		}#end for

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the buttons beneath it
	function &unset_owner() {

		for(reset($this->sections); 
			$key = key($this->sections); 
			next($this->sections)) {

			$this->sections[$key]->unset_owner();

		}#end for

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		for(reset($this->sections); 
			$key = key($this->sections); 
			next($this->sections)) {

			$tmp->sections[$key] = &$this->sections[$key]->copy();

		}#end for
		return $tmp;

	}#end copy()

	  #########################################################################
	 # Takes a old version of itself and uses the old versions custom vars to 
	# attempting to save any customisations, previously made
	function update(&$old_this) {

		if (!Site_Design_Area::update($old_this)) return;

		for(
				reset($this->sections); 
				$key = key($this->sections); 
				next($this->sections)
			) 
		{
			if ($old_this->sections[$key]) {
				$this->sections[$key]->update($old_this->sections[$key]);
			}

		}#end for

	}#end update()

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag);
		$this->_set_nested_areas($tag, "pagefile_list");

		 ############################################
		# process the contents of the element
		foreach($tag['contents'] as $index => $element) {
			# if we are dealing with a tag
			switch ($element['_type']) {
			
				case "TAG" : 

					# if this is the sub section
					if ($element['attributes']['section']) {

						$section_name = strtolower($element['attributes']['section']);

						$class_name = get_class($this)."_listing";

						# kill it because it's not needed anymore
						unset($element['attributes']['section']);
						
						$this->sections[$section_name] = new $class_name($this);
						$this->sections[$section_name]->create($element);

						# append to the contents array so we know were to print this
						# only print results once, therefore only put it in the content array once.
						$this->contents[] = Array("_type" => "file");

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


	function paint(&$tag_attributes) {
		# Only if we're printing a page, not a site extension or something
		$site_design = &$this->get_top_owner();
		if (!$site_design->painter_is_page_template())
			return;

		$web_system = &get_web_system();

		$page = &$this->get_page();

		if (!isset($page->file_index) || count($page->file_index) == 0) {
			return 1;  # No files :~(
		}

		# Don't bother if they're all invisible
		if(!$this->get_val("count")) return;

		# Now look through all our contents
		foreach($this->contents as $element)
		{
			switch($element['_type'])
			{
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
						case "print":
							$this->print_val($element);
						break;
					}

				break;

				# sub section element for printing the file records
				case "file" :
					foreach($page->file_index as $fileid) {
						$file = &$web_system->get_file($fileid);
						if ($file->visible != "Y") continue; # Don't print invisible files
						$this->sections['file']->paint($fileid);
					}
					break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach contents


	}#end paint()


	  ################################################
	 # Returns a description of the design area 
	function get_description() {
		return "Change the appearance of the list of the current page's attached files.";
	}#end get_description()



	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name) {

		switch(strtolower($name)) {
            case "count" :
				$web_system = &get_web_system();
				$page       = $web_system->get_page();
				foreach($page->file_index as $fileid) {
					$file = &$web_system->get_file($fileid);
					if ($file->visible == "Y") $visible_count++;
				}
				return (int) $visible_count;
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name);
		}#end switch
	}


}#end class Site_Design_Area_Pagefile_List

?>
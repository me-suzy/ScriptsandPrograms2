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
## Desc: Lets the user select a page to have displayed on every page
## $Source: /home/cvsroot/xtras/site/design_areas/permanent_page/permanent_page.inc,v $
## $Revision: 2.7 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:13 $
#######################################################################
include_once(dirname(__FILE__).'/individual.inc');
#---------------------------------------------------------------------#

class Site_Design_Area_Permanent_Page extends Site_Design_Area {

	var $page_areas = Array(); # an array of individual permanent pages for this design

	function Site_Design_Area_Permanent_Page(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;
	}#end Site_Design_Area_Permanent_Page()

	  ############################################################
	 # Reset the owner for this object, then reset the buttons 
	# owner to this object 
	function reset_owner(&$owner) {

		for(reset($this->page_areas); 
			$key = key($this->page_areas); 
			next($this->page_areas)) {

			$this->page_areas[$key]->reset_owner($this);

		}#end for

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the buttons beneath it
	function &unset_owner() {

		for(reset($this->page_areas); 
			$key = key($this->page_areas); 
			next($this->page_areas)) {

			$this->page_areas[$key]->unset_owner();

		}#end for

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		for(reset($this->page_areas); 
			$key = key($this->page_areas); 
			next($this->page_areas)) {

			$tmp->page_areas[$key] = &$this->page_areas[$key]->copy();

		}#end for
		return $tmp;

	}#end copy()

	function create(&$tag) {

		$class = get_class($this).'_Individual';
		$new_page_area  = new $class($this);
		# try to create
		if ($new_page_area->create($tag)) {
			# check for matching page_area names and if they match then append a number 
			# and keep going until we have a unique page_area name
			$page_area_name = $new_page_area->get_val('page_area_name');
			$i = 0;
			while (in_array($page_area_name, array_keys($this->page_areas))) {
				$i++;
				$page_area_name = $new_page_area->get_val('page_area_name') . $i;
			}

			# if the name has changed then update the page_area objects
			if ($page_area_name != $new_page_area->get_val('page_area_name')) {

				$new_page_area->set_var('page_area_name', $page_area_name);
			}

			# add this page_area into the array
			$this->page_areas[$page_area_name] = $new_page_area;

			# return the page_area name so it can be passed to paint() later
			return Array('page_area_name' => $page_area_name);

		}

	}#end create()

	  #########################################################################
	 # Takes a old version of itself and uses the old versions custom vars to 
	# attempting to save any customisations, previously made
	function update(&$old_this) {

		if (!Site_Design_Area::update($old_this)) return;

		for(reset($this->page_areas); $key = key($this->page_areas); next($this->page_areas)) {
			if ($old_this->page_areas[$key]) {
				$this->page_areas[$key]->update($old_this->page_areas[$key]);
			}

		}#end for

	}#end update()

	function paint(&$tag_attributes) {

		# if this tag has the needed page_area name
		if ($tag_attributes['page_area_name']) {
		
			# if this page_area exists in this container
			if ($this->page_areas[$tag_attributes['page_area_name']]) {
				$this->page_areas[$tag_attributes['page_area_name']]->paint();

			} else {
				$this->_set_error("Permanent Page Area '$tag_attributes[page_area_name]' unknown", __FILE__, __LINE__);
			}#end if

		# else no page_area name
		} else {

			$this->_set_error('Permanent Page Area Name Not Present - Tag Attributes : '.array_contents($tag_attributes), __FILE__, __LINE__);

		}#end if

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise this object 
	function print_user_backend($prefix_name='') {

		$web_system = &get_web_system();
		$backend     = &$web_system->get_backend();

		$changes_made = false;
		if ($this->page_areas) {

			for(reset($this->page_areas); $key = key($this->page_areas); next($this->page_areas)) {
				$changes_made |= $this->page_areas[$key]->print_user_backend();
			}#end for
		}

		return $changes_made;


	}#end print_user_backend()

	  ################################################
	 # Returns a description of the design area 
	function get_description() {

		$desc = 'Change the settings for the ';

		$page_area_names = array_keys($this->page_areas);
		for($i = 0; $i < count($page_area_names); $i++) {
			$desc .= '<i>'.ucwords(str_replace('_', ' ', $page_area_names[$i])).'</i>';
			if ($i == count($page_area_names) - 2) {
				$desc .= ' and ';
			} else if ($i < count($page_area_names) - 2) {
				$desc .= ', ';
			}
		}#end for

		$desc .= ' permanent page areas';

		return $desc;

	}

}#end class 

?>
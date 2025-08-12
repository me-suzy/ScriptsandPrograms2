<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This software Copyright Squiz.net 2001
## All Rights Reserved.
##
## File: xtras/site/designs_areas/banner/banner.inc
## Desc: this class is used to print the the banner images for the current siteid
## $Source: /home/cvsroot/xtras/site/design_areas/banner/banner.inc,v $
## $Revision: 2.5 $
## $Author: bvial $
## $Date: 2003/10/03 05:11:18 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
include_once(dirname(__FILE__)."/individual.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Banner extends Site_Design_Area {

	var $sections; # An array of individual sections for this page

	function Site_Design_Area_Banner(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		# set up some default variables, 
		# so unless they write over them we have something to play with
		$this->sections = array();

	}#end Site_Design_Area_Banner()

	  ############################################################
	 # Reset the owner of this object and its container objects
	function reset_owner(&$owner) {

		for(reset($this->sections); 
			$key = key($this->sections); 
			next($this->sections)) {

			$this->sections[$key]->reset_owner($this);

		}#end for

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the sections beneath it
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
		
		$new_section  = new Site_Design_Area_Banner_Individual($this);

		# try to create
		if ($new_section->create($tag)) {

			$section_name = $new_section->get_val("section_name");

			# check for matching section names and if they match then append a number 
			# and keep going until we have a unique image name
			$i = 0;
			while (in_array($section_name, array_keys($this->sections))) {
				$i++;
				$section_name = $new_section->get_val("section_name") . $i;
			}

			# if the name has changed then update the image objects
			if ($section_name != $new_section->get_val("section_name")) {

				$new_section->set_var("section_name", $section_name);
			}

			# add this image into the array
			$this->sections[$section_name] = $new_section;

			# return the image name and print so they can be passed to paint() later
			return Array("section_name" => $section_name);

		}# end if creation successful

	}#end create()

	function paint(&$tag_attributes) {
		$site = &$this->get_site();

		# run a security check to see if this extension has been allowed
        if (!in_array('banner_engine',$site->allowed_extensions)) {
            return;
        }

		# if this tag has the needed image name
		if ($tag_attributes[section_name]) {

			# if this section exists in this container and we are supposed to be printing it
			if ($this->sections[$tag_attributes[section_name]]) {
				$this->sections[$tag_attributes[section_name]]->paint();
			} else {
				$this->_set_error("Banner Section '$tag_attributes[section_name]' unknown");
			}#end if

		# else no section name
		} else {

			$this->_set_error("Section Name Not Present - Tag Attributes : ".array_contents($tag_attributes));

		}#end if

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend() {
		
		$web_system = &get_web_system();

		$changes_made = false;

		$backend = &$web_system->get_backend();

		$changes_made |= Site_Design_Base::print_vars_backend($backend);

		if ($this->sections) {
			for(reset($this->sections); $key = key($this->sections); next($this->sections)) {

				$changes_made |= $this->sections[$key]->print_user_backend($key);

			}#end for
		}#end if

		return $changes_made;

	}#end print_user_backend()

	  ################################################
	 # Returns a description of the design area 
	function get_description() {

		return "Change the settings of banners to be displayed.";

	}#end get_description()


}#end class Site_Design_Area_Banner
?>

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
## File: xtras/site/design_areas/login/login.inc
## Desc: looks after the login/logout buttons and form on the page
## $Source: /home/cvsroot/xtras/site/design_areas/login/login.inc,v $
## $Revision: 2.4 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:10 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
include_once(dirname(__FILE__)."/login_display_name.inc");
include_once(dirname(__FILE__)."/login_form.inc");
include_once(dirname(__FILE__)."/login_custom_form.inc");
#---------------------------------------------------------------------#


class Site_Design_Area_Login extends Site_Design_Area {

	var $sections;   # array holding the printable sections of the login area
					 # Two sections :
					 #  - display_name  -> if person logged in print their name
					 #  - form          -> prints the buttons needed for the login form


	function Site_Design_Area_Login(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		# init the vars
		$this->sections = Array();
		$this->_set_var('require_ssl','','set','',Array(''=>'No','1'=>'Yes'));

	}#end Site_Design_Area_Login()

	  ############################################################
	 # Reset the owner for this object, then reset the sections 
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

		# if this tag is a specific menu section
		if ($tag[attributes][section]) {

				$section_name = strtolower($tag[attributes][section]);
				# kill it because it's not needed anymore
				unset($tag[attributes][section]);
				$class_name = get_class($this)."_".$section_name;
				if (class_exists($class_name)) {
					$this->sections[$section_name] = new $class_name($this, $section_name);
					$this->sections[$section_name]->create($tag);

				} else {
					$this->_set_error("Class '$class_name' unknown", __FILE__, __LINE__);

				}#end if

		}#end if is a section

		# no additional attributes needed
		return;

	}#end create()

	function paint(&$tag_attributes) {
		$web_system = &get_web_system();
		# if they want a section then, print it
		if ($tag_attributes[section]) {

			$tag_attributes[section] = strtolower($tag_attributes[section]);
		
			# make sure the section they're after exists
			if ($this->sections[$tag_attributes[section]]) {
				$this->sections[$tag_attributes[section]]->paint();
			}#end if

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

		for(reset($this->sections); $section_name = key($this->sections); next($this->sections)) { 

			if ($this->sections[$section_name]->customisable) {

				$backend->open_section(ucwords(str_replace("_", " ", $section_name)));
				$changes_made |= $this->sections[$section_name]->print_user_backend($section_name);

			}#end if

		}#end for

		return $changes_made;

	}#end print_user_backend()

	  ################################################
	 # Returns a description of the design area 
	function get_description() {

		$desc = "Change/View the text for the Login/Logout link";
		return $desc;

	}#end get_description()

}#end class Site_Design_Area_Login

?>
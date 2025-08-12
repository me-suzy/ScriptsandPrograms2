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
## File: xtras/site/design_areas/login/login_form.inc
## Desc: prints a custom login form, ie it had editable images
## $Source: /home/cvsroot/xtras/site/design_areas/login/login_custom_form.inc,v $
## $Revision: 2.4 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:11 $
#######################################################################
include_once(dirname(__FILE__).'/login_custom_form_section.inc');
#---------------------------------------------------------------------#

class Site_Design_Area_Login_Custom_Form extends Site_Design_Area {

	var $contents;
	var $sections; # an array of the containing the login and logout sections

	function Site_Design_Area_Login_Custom_Form(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		$this->contents = Array();
		$this->sections = Array();

	}#end Site_Design_Area_Login_Custom_Form()

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
		$this->_set_nested_areas($tag, 'login');


		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element) {
			# if we are dealing with a tag
			switch ($element[_type]) {
			
				case 'TAG' : 

					# if this is the sub section
					if ($element[attributes][section]) {

						$section_name = strtolower($element[attributes][section]);

						if ($section_name != 'login_form' && $section_name != 'logout_form') {
							$this->_set_error("section '$section_name' unknown, use 'login_form' or 'logout_form'", __FILE__, __LINE__);
							unset($this);
							return;
						}#end if

						$class_name = get_class($this).'_section';

						# kill it because it's not needed anymore
						unset($element[attributes][section]);

						$type = ($section_name == 'login_form') ? 'in' : 'out';
						
						$this->sections[$section_name] = new $class_name($this, $type);
						$this->sections[$section_name]->create($element);

						# Append to the contents array so we know were to print this.
						# because we only want to print either the login or logout forms 
						# add to the contents array only once
						$this->contents[one_result] = Array('_type' => 'form');

					# must be a print tag, we'll deal with it later, 
					# so just append to contents
					} else {

						$this->contents[] = $element;

					}#end if

				break;

				case 'DESIGN_AREA' : # dealt with above so just add to contents
				case 'HTML'        : # nothing to do to this yet, so just add to contents array

					$this->contents[] = $element;

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

	}#end create()
 
	function paint() {
		global $SESSION;

		# Now look through all our contents
		foreach($this->contents as $element) {
			switch($element[_type]) {
				# plain html element just echo its contents
				case 'HTML' :
					echo $element[contents];
				break;

				# nested design area so print/paint it
				case 'DESIGN_AREA' :
					$this->_print_nested_area($element);
				break;

				# taged element ... should really only be a print element
				case 'TAG' :
					switch($element[operation])	{
						case 'print':
							$this->print_val($element);
						break;
					}

				break;

				# sub section element for printing the form
				case 'form' :
					if ($SESSION->logged_in()) {
						if ($this->sections[logout_form]) {
							$this->sections[logout_form]->paint();
						}#end if
					} else {
						if ($this->sections[login_form]) {
							$this->sections[login_form]->paint();
						}#end if
					}#end if
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
	# them to customise this object 
	function print_user_backend($prefix_name='') {

		$web_system = &get_web_system();

		$changes_made = false;

		$backend = &$web_system->get_backend();
		$changes_made |= $this->print_vars_backend($backend);

		for(reset($this->sections); $section_name = key($this->sections); next($this->sections)) { 

			if ($this->sections[$section_name]->customisable) {

				$backend->open_section(ucwords(str_replace('_', ' ', $section_name)));
				$changes_made |= $this->sections[$section_name]->print_user_backend($section_name);

			}#end if

		}#end for

		return $changes_made;

	}

	 
}#end class Site_Design_Area_Login_Custom_Form

?>

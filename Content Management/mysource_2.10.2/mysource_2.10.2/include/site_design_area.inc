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
## File: include/site_design_area.inc
## Desc: defines some functions that every Design Area object must have
##       even if they are not needed for that child object
## $Source: /home/cvsroot/mysource/include/site_design_area.inc,v $
## $Revision: 2.8 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:59 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area extends Site_Design_Base {

	var $customisable = false;	# boolean indication whether this object has any
								# customisable sections

	var $setable =  false;	# boolean indication whether this object has can be created inside a 
							# MySource_SET tag. IF TRUE area_val() should be overwritten

	function Site_Design_Area(&$_owner) {
		Site_Design_Base::Site_Design_Base($_owner);
	}#end Site_Design_Area()

	   ############################################################################
	  # creates an the design area object based upon the passed element
	 # can return an array of additional attributes that can be used by the page 
	# area to help determine which part of itself to print out when the time comes
	function create(&$tag) {
		$this->_set_variables($tag, false);
		return;
	}

	    ##########################################################
	   # Takes the reference to the tag array passed into create()
	  # and set both the attributes and set operation tags into values
	 # the set tags are then removed from the array
	# if $look_for_images is true then anything with 'image' contained in it
	# will have the image root removed from its value
	function _set_variables(&$tag, $look_for_images=false) {

		 ############################################
		# assign any attributes to the right places
		if (!empty($tag['attributes'])) {
			foreach($tag['attributes'] as $name => $value) {
				if ($look_for_images) {
					# if this holds an image, then remove any image roots before saving
					if (stristr($name, "image")) {
						$value = Site_Design::remove_image_root($value, $this->get_val("IMAGE_ROOT"));
					}
				}#end if

				$this->set_var($name, $value);

			}#end foreach
		}


		 ############################################
		# process the set operations of the element
		if (!empty($tag['contents'])) {
		foreach($tag['contents'] as $index => $element) {
				if ($element['_type'] == "TAG" && $element['operation'] == "set") {

					$set_design_area = false;
					# loop through any contents and to see if this is a design area in it
					if (!empty($element['contents'])) {
						foreach($element['contents'] as $area_index => $sub_element) {
							if ($sub_element['_type'] == "TAG" && $sub_element['operation'] == "area") {
								$set_design_area = true;
								break;
							}#end if
						}#end foreach
					}

					# if we are setting a design area for this value
					if ($set_design_area) {
						$site_design = &$this->get_top_owner();

						if ($replacement_contents = $site_design->create_design_area($element['contents'][$area_index], true)) {
							# OK lets set the var reference
							if ($this->_set_var($element['attributes']['name'], $replacement_contents, "design_area", $element['attributes']['description'])) {
								# we can remove it from the element now that we have set it
								unset($tag['contents'][$index]);

							}#end if

						}#end if

					# else just a normal tag
					} else {

					if ($look_for_images) {
						# if this holds an image, then remove any image roots before saving
						if (stristr($element['attributes']['name'], "image")) {
							$element['attributes']['value'] = Site_Design::remove_image_root($element['attributes']['value'], $this->get_val("IMAGE_ROOT"));
						}
					}

					if ($this->set_from_tag($element)) {
						# we can remove it from the element now that we have set it
						unset($tag['contents'][$index]);
					}#end if

					}#end if set_design_area

				}#end if

			}#end foreach
		}

		return;

	}#end _set_variables()

	    ##########################################################
	   # Takes the reference to the tag array passed into create()
	  # and creates and sets the necessary design areas 
	 # the design area's are then removed from the array
	# and replaced with special "DESIGN_AREA" tag
	function _set_nested_areas(&$tag, $current_area="") {

		$site_design = &$this->get_top_owner();

		 ############################################
		# process the area operations of the element
		if (!empty($tag['contents'])) {
			foreach($tag['contents'] as $index => $element) {

				if ($element['_type'] == "TAG" && $element['operation'] == "area") {
					# if there is a name and it's not the the same type as the current design area
					if ($element['attributes']['name'] 
						&& $element['attributes']['name'] != $current_area
						) {

						if ($replacement_contents = $site_design->create_design_area($element)) {
							$tag['contents'][$index] = $replacement_contents;

						}#end if

					}#end if name exists

				}#end if

			}#end foreach
		}

		return;

	}#end _set_nested_areas()

	  ##########################################################
	 # Takes the reference to the element that was produced using
	# _set_nested_areas() and paints the required design area
	function _print_nested_area(&$tag) {
		
		$site_design = &$this->get_top_owner();
		return $site_design->paint_design_area($tag, $this);

	}#end _print_nested_area()

	 #####################################################
	# gets out the value for the passed variable name
	function get_val($name='', $pageid=0) {

		$name = strtolower($name);
		if ($name) {
			if (!empty($this->_set_vars[$name])) {
				# if this is a design area reference, return its value
				if ($this->_set_vars[$name]['value_type'] == "design_area") {
					$site_design = &$this->get_top_owner();
					$design_area = &$site_design->get_design_area($this->_set_vars[$name]['value']);

					return $design_area->area_val($this->_set_vars[$name]['value']['attributes']);
				}#end if
			}#end if
		}#end if
		return Site_Design_Base::get_val($name, $pageid);

	}#end get_val()

	 ############################################################################
	# prints the design area object based upon the passed attributes
	function paint(&$tag_attributes) {
	}

	  ############################################################################
	 # if this area is setable then this function returns what can be printed out
	# receives the same tag_attributes that paint() does
	function area_val(&$tag_attributes) {
		return "";
	}

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise this object 
	function print_user_backend($prefix_name='') {
		if (!$this->customisable) return false;
		$web_system = &get_web_system();
		$backend = &$web_system->get_backend();
		return $this->print_vars_backend($backend, $prefix_name);

	}

	  ################################################
	 # Returns a description of the design area 
	function get_description() {
		return "";
	}

	 ################################################
	# Returns a pointer to the current site
	function &get_site($siteid=0) {
		$site_design = &$this->get_top_owner();
		return $site_design->get_site($siteid);
	}

	 ################################################
	# Returns a pointer to the current page
	function &get_page($pageid=0) {
		$site_design = &$this->get_top_owner();
		return $site_design->get_page($pageid);
	}

	 ############################################################################
	# assumes that the passed variable name represents an image and once it has 
	# received the value for the image adds the image prefix to it
	function get_image_href($name='', $from_get_val=false) {

		# we don't want to get into a recursive loop now do we :)
		if ($from_get_val) {
			$image = Site_Design_Area::get_val($name);
		} else {
			$image = $this->get_val($name);
		}
		if (!$image) return '';
		# if the image doesn't contain the custom or normal image prefix use the normal one
		# NOTE: if this image was set to be another design area then either the custom image prefix
		#       of the image prefix would have been set
		if (  !stristr($image, $this->get_val("CUSTOM_IMAGE_PREFIX")) 
			&& !stristr($image, $this->get_val("IMAGE_PREFIX"))
			) {
			$image = $this->get_val("IMAGE_PREFIX").$image;
		}#end if
		return $image;

	}#end get_image_href();

	 ####################################################
	# returns a filesystem path to the requested image
	function get_image_src($name='', $from_get_val=false) {

		# we don't want to get into a recursive loop now do we :)
		if ($from_get_val) {
			$image = Site_Design_Area::get_val($name);
		} else {
			$image = $this->get_val($name);
		}
		if (!$image) return '';

		# OK it is a custom image, strip the prefix and add the proper dir
		if ($tmp_image = stristr($image, ($prefix = $this->get_val("CUSTOM_IMAGE_PREFIX")))) {
			return $this->get_val("CUSTOM_IMAGE_DIR").'/'.substr($tmp_image, strlen($prefix));

		# if this is a normal image, strip the prefix and add the proper dir
		} elseif ($tmp_image = stristr($image, ($prefix = $this->get_val("IMAGE_PREFIX")))) {
			return $this->get_val("IMAGE_DIR").'/'.substr($tmp_image, strlen($prefix));

		# else just assume that there is no prefix and add the default dir
		} else {
			return $this->get_val("IMAGE_DIR").'/'.$image;

		}#end if

	}#end get_image_src();

}#end class Site_Design_Area

?>
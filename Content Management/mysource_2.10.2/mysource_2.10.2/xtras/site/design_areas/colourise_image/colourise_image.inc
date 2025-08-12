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
## File: xtras/site/design_areas/colourise_image/colourise_image.inc
## Desc: a container class for handling multiple images that can be have there colour changed
## $Source: /home/cvsroot/xtras/site/design_areas/colourise_image/colourise_image.inc,v $
## $Revision: 2.6 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:09 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
include_once(dirname(__FILE__)."/individual.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Colourise_Image extends Site_Design_Area {

	var $images; # an array of individual images for this page

	function Site_Design_Area_Colourise_Image(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->customisable = true;
		$this->setable = true;

		# set up the defaults
		$this->images = Array();

	}#end Site_Design_Area_Colourise_Image()

	  ############################################################
	 # Reset the owner for this object, then reset the buttons 
	# owner to this object 
	function reset_owner(&$owner) {

		for(reset($this->images); 
			$key = key($this->images); 
			next($this->images)) {

			$this->images[$key]->reset_owner($this);

		}#end for

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the buttons beneath it
	function &unset_owner() {

		for(reset($this->images); 
			$key = key($this->images); 
			next($this->images)) {

			$this->images[$key]->unset_owner();

		}#end for

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		for(reset($this->images); 
			$key = key($this->images); 
			next($this->images)) {

			$tmp->images[$key] = &$this->images[$key]->copy();

		}#end for
		return $tmp;

	}#end copy()

	function create(&$tag) {

		$new_image  = new Site_Design_Area_Colourise_Image_Individual($this);
		# try to create
		if ($new_image->create($tag)) {

			$image_name = $new_image->get_val("image_name");
			$print      = $new_image->get_val("print");

			# if the image is not printable or if and the image name doesn't exist
			# then we need to define this image and save it
			if (!$print || !in_array($image_name, array_keys($this->images))) {

				# check for matching image names and if they match then append a number 
				# and keep going until we have a unique image name
				$i = 0;
				while (in_array($image_name, array_keys($this->images))) {
					$i++;
					$image_name = $new_image->get_val("image_name") . $i;
				}

				# if the name has changed then update the image objects
				if ($image_name != $new_image->get_val("image_name")) {

					$new_image->set_var("image_name", $image_name);
				}

				# add this image into the array
				$this->images[$image_name] = $new_image;

			}

			# return the image name and print so they can be passed to paint() later
			return Array("image_name" => $image_name, "print" => $print);

		}# end if creation successful

	}#end create()

	function paint(&$tag_attributes) {

		# if this tag has the needed image name
		if ($tag_attributes[image_name]) {

			# if we should try and print
			if ($tag_attributes["print"]) {
		
				# if this image exists in this container and we are supposed to be printing it
				if ($this->images[$tag_attributes[image_name]]) {
					$this->images[$tag_attributes[image_name]]->paint();

				} else {
					$this->_set_error("Custom Image '$tag_attributes[image_name]' unknown", __FILE__, __LINE__);
				}#end if

			}#end if printable

		# else no image name
		} else {

			$this->_set_error("Image Name Not Present - Tag Attributes : ".array_contents($tag_attributes), __FILE__, __LINE__);

		}#end if

	}#end paint()

	function area_val(&$tag_attributes) {
		# if this tag has the needed image name
		if ($tag_attributes[image_name]) {

			# if this image exists in this container and we are supposed to be printing it
			if ($this->images[$tag_attributes[image_name]]) {
				return $this->images[$tag_attributes[image_name]]->area_val();

			} else {
				$this->_set_error("Custom Image '$tag_attributes[image_name]' unknown", __FILE__, __LINE__);
			}#end if

		# else no image name
		} else {
			$this->_set_error("Image Name Not Present - Tag Attributes : ".array_contents($tag_attributes), __FILE__, __LINE__);
		}#end if

		return "";

	}#end area_val()


	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend() {
		$web_system = &get_web_system();

		$changes_made = false;

		$backend = &$web_system->get_backend();
		$backend->open_section("Colourise Images");

		$changes_made |= Site_Design_Base::print_vars_backend($backend);

		if ($this->images) {
			for(reset($this->images); $key = key($this->images); next($this->images)) {

				$changes_made |= $this->images[$key]->print_user_backend($key);

			}#end for
		}#end if

		return $changes_made;

	}#end print_user_backend()

	  ################################################
	 # Returns a description of the design area 
	function get_description() {

		$desc = "Change/View the colour values for the ";

		$image_names = array_keys($this->images);
		for($i = 0; $i < count($image_names); $i++) {
			$desc .= "<i>".ucwords(str_replace("_", " ", $image_names[$i]))."</i>";
			if ($i == count($image_names) - 2) {
				$desc .= " and ";
			} else if ($i < count($image_names) - 2) {
				$desc .= ", ";
			}
		}#end for

		return $desc;

	}#end get_desription()

}#end class Site_Design_Area_Colourise_Image

?>
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
## Desc: a container class for handling multiple objects that can layer images together
## $Source: /home/cvsroot/xtras/site/design_areas/layer_image/layer_image.inc,v $
## $Revision: 1.2 $
## $Author: blair $
## $Date: 2002/04/11 05:58:58 $
#######################################################################
include_once(dirname(__FILE__)."/individual.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Layer_Image extends Site_Design_Area {

	var $images; # an array of individual images for this page

	function Site_Design_Area_Layer_Image(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->setable     = true;

		# set up the defaults
		$this->images = Array();

	}#end constructor

	  ############################################################
	 # Reset the owner for this object, then reset the buttons 
	# owner to this object 
	function reset_owner(&$owner) {

		for(reset($this->images); NULL !== ($key = key($this->images)); next($this->images)) {
			$this->images[$key]->reset_owner($this);
		}#end for

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the buttons beneath it
	function &unset_owner() {

		for(reset($this->images); NULL !== ($key = key($this->images)); next($this->images)) {
			$this->images[$key]->unset_owner();
		}#end for

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		for(reset($this->images); NULL !== ($key = key($this->images)); next($this->images)) {
			$tmp->images[$key] = &$this->images[$key]->copy();
		}#end for
		return $tmp;

	}#end copy()

	function create(&$tag) {

		$new_image  = new Site_Design_Area_Layer_Image_Individual($this);
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

	 ################################################################################
	# returns a boolean indicating whether this image name is already used
	function image_name_used($name) {
		return in_array($name, array_keys($this->images));
	}

	function paint(&$tag_attributes) {
		$web_system = &get_web_system();
		# if this tag has the needed image name
		if ($tag_attributes['image_name']) {

			# if we should try and print
			if ($tag_attributes["print"]) {
		
				# if this image exists in this container and we are supposed to be printing it
				if ($this->images[$tag_attributes['image_name']]) {
					$this->images[$tag_attributes['image_name']]->paint();

				} else {
					$this->_set_error("Layer Image '$tag_attributes[image_name]' unknown", __FILE__, __LINE__);
				}#end if

			}#end if printable

		# else no image name
		} else {

			$this->_set_error("Image Name Not Present - Tag Attributes : ".array_contents($tag_attributes), __FILE__, __LINE__);

		}#end if

	}#end paint()

	function area_val(&$tag_attributes) {
		# if this tag has the needed image name
		if ($tag_attributes['image_name']) {

			# if this image exists in this container and we are supposed to be printing it
			if ($this->images[$tag_attributes['image_name']]) {
				return $this->images[$tag_attributes['image_name']]->area_val();

			} else {
				$this->_set_error("Layer Image '$tag_attributes[image_name]' unknown", __FILE__, __LINE__);
			}#end if

		# else no image name
		} else {
			$this->_set_error("Image Name Not Present - Tag Attributes : ".array_contents($tag_attributes), __FILE__, __LINE__);
		}#end if

		return "";

	}#end area_val()

}#end class 

?>
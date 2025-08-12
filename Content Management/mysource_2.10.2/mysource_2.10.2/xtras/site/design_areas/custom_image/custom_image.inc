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
## File: xtras/site/design_areas/custom_image/custom_image.inc
## Desc: a container class for handling multiple changeable images on a design
## $Source: /home/cvsroot/xtras/site/design_areas/custom_image/custom_image.inc,v $
## $Revision: 2.7 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:09 $
#######################################################################
include_once(dirname(__FILE__).'/individual.inc');
#---------------------------------------------------------------------#

class Site_Design_Area_Custom_Image extends Site_Design_Area {

	var $images; # an array of individual images for this page

	function Site_Design_Area_Custom_Image(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->customisable = true;
		$this->setable = true;

		# set up the defaults
		$this->images = Array();

	}#end Site_Design_Area_Custom_Image()

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

		$new_image  = new Site_Design_Area_Custom_Image_Individual($this);
		# try to create
		if ($new_image->create($tag)) {
			# check for matching image names and if they match then append a number 
			# and keep going until we have a unique image name
			$image_name = $new_image->get_val('image_name');
			$i = 0;
			while (in_array($image_name, array_keys($this->images))) {
				$i++;
				$image_name = $new_image->get_val('image_name') . $i;
			}

			# if the name has changed then update the image objects
			if ($image_name != $new_image->get_val('image_name')) {

				$new_image->set_var('image_name', $image_name);
			}

			# add this image into the array
			$this->images[$image_name] = $new_image;

			# return the image name so it can be passed to paint() later
			return Array('image_name' => $image_name);

		}

	}#end create()

	function paint(&$tag_attributes) {
		# if this tag has the needed image name
		if ($tag_attributes['image_name']) {
		
			# if this image exists in this container
			if ($this->images[$tag_attributes['image_name']]) {
				$this->images[$tag_attributes['image_name']]->paint();

			} else {
				$this->_set_error("Custom Image '$tag_attributes[image_name]' unknown", __FILE__, __LINE__);
			}#end if

		# else no image name
		} else {

			$this->_set_error('Image Name Not Present - Tag Attributes : '.array_contents($tag_attributes), __FILE__, __LINE__);

		}#end if

	}#end paint()

	function area_val(&$tag_attributes) {
		# if this tag has the needed image name
		if ($tag_attributes['image_name']) {
		
			# if this image exists in this container
			if ($this->images[$tag_attributes['image_name']]) {
				return $this->images[$tag_attributes['image_name']]->area_val();

			} else {
				$this->_set_error("Custom Image '$tag_attributes[image_name]' unknown", __FILE__, __LINE__);
			}#end if

		# else no image name
		} else {

			$this->_set_error('Image Name Not Present - Tag Attributes : '.array_contents($tag_attributes), __FILE__, __LINE__);

		}#end if

	}#end area_val()


	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend() {
		$web_system = &get_web_system();

		$changes_made = false;

		$backend = &$web_system->get_backend();

		if ($this->images) {

			$keys = array_keys($this->images);
			sort($keys);
			foreach($keys as $key) {
				$image = $this->images[$key]->get_image_file();
				$dest = $image['directory'].'/'.$image['field_name'];
				$retval = process_image_upload($image['field_name'],$dest,false);
				$message .= $retval['message'];
			}#end for

			if ($message) {
				$changes_made = true;
				echo js_alert($message);
			}

			$keys = array_keys($this->images);
			sort($keys);
			foreach($keys as $key) {
				$changes_made |= $this->images[$key]->print_user_backend();

			}#end for
		}

		return $changes_made;

	}#end print_user_backend()

	  ################################################
	 # Returns a description of the design area 
	function get_description() {

		$desc = 'Upload an image for the ';

		$image_names = array_keys($this->images);
		for($i = 0; $i < count($image_names); $i++) {
			$desc .= '<i>'.ucwords(str_replace('_', ' ', $image_names[$i])).'</i>';
			if ($i == count($image_names) - 2) {
				$desc .= ' and ';
			} else if ($i < count($image_names) - 2) {
				$desc .= ', ';
			}
		}#end for

		return $desc;

	}#end get_desription()

}#end class Site_Design_Area_Custom_Image

?>
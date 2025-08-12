<?  ##############################################
   ### MySource ------------------------------###
  ##- Text Images Module ------ PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## Desc: a container class for handling multiple objects that can create text images
## $Source: /home/cvsroot/xtras/site/design_areas/text_images/text_images.inc,v $
## $Revision: 1.4 $
## $Author: bvial $
## $Date: 2003/08/11 23:48:08 $
#######################################################################
include_once(dirname(__FILE__)."/individual.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Text_Images extends Site_Design_Area {

	var $images; # an array of individual images for this page

	function Site_Design_Area_Text_Images(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		# set up the defaults
		$this->images = Array();

	}#end constructor

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

		$class = get_class($this).'_Individual';
		$new_image  = new $class($this);
		# try to create
		if ($new_image->create($tag)) {

			$image_name = $new_image->get_val("image_name");

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

			# return the image name and print so they can be passed to paint() later
			return Array("image_name" => $image_name);

		}# end if creation successful

	}#end create()

	function paint(&$tag_attributes) {
		$web_system = &get_web_system();
		# if this tag has the needed image name
		if ($tag_attributes['image_name']) {

			# if this image exists in this container and we are supposed to be printing it
			if ($this->images[$tag_attributes['image_name']]) {
				$this->images[$tag_attributes['image_name']]->paint();

			} else {
				$this->_set_error("Text Image '$tag_attributes[image_name]' unknown", __FILE__, __LINE__);
			}#end if

		# else no image name
		} else {

			$this->_set_error("Image Name Not Present - Tag Attributes : ".array_contents($tag_attributes), __FILE__, __LINE__);

		}#end if

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend() {
		$web_system = &get_web_system();

		$changes_made = false;

		$backend = &$web_system->get_backend();

		$backend->open_field('Special Values');

		echo '<p>For each text image you can enter text to print or use one fo these special values:</p><p>site_name<br>page_name<br>page_short_name<br>page_title<br></p>';

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

}#end class 

?>
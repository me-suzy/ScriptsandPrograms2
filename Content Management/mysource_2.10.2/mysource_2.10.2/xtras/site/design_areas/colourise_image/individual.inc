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
## File: xtras/site/design_areas/colourise_mage/individual.inc
## Desc: this is a single custom image for the design
## $Source: /home/cvsroot/xtras/site/design_areas/colourise_image/individual.inc,v $
## $Revision: 2.6 $
## $Author: bvial $
## $Date: 2003/04/02 03:51:02 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
include_once("$INCLUDE_PATH/html_image.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Colourise_Image_Individual extends Site_Design_Area {

	function Site_Design_Area_Colourise_Image_Individual(&$_owner) {

		$this->Site_Design_Base($_owner);

		# declare the variables
		$this->_set_var("image_name", "", "text");
		$this->_set_var("print", "1", "boolean");
		$this->_set_var("image", "", "text");
		$this->_set_var("type", "greyscale", "set", "", array("greyscale" => "Greyscale", "multiple_colours" => "Multiple Colours"));
		$this->_set_var("tolerance", "0.2", "text");
		$this->_set_var("filename_only", "0", "boolean");
		$this->_set_var("extra",  "", "text");

	}#end Site_Design_Area_Colourise_Image_Individual()

	function create(&$tag) {

		 #######################
		# take a copy of the tag and use it to initialise that vars
		# so that we can get the type and declare some variable depending on that type 
		# below
		$tag_copy = $tag;
		$this->_set_variables($tag_copy, true);

		# declare some variables, depending on the type of colourise image
		# so that we have the var types right
		switch (strtolower($this->get_val("type"))) {

			case "greyscale" :

				$this->_set_var("black_colour", "000000", "colour");
				$this->_set_var("white_colour", "FFFFFF", "colour");

			break;

			case "multiple_colours" :

				$i = 1;
				while($this->get_val("from_colour_".$i) && $this->get_val("to_colour_".$i)) {
					$this->_set_var("from_colour_".$i, "000000", "colour");
					$this->_set_var("to_colour_".$i,   "000000", "colour");
					$i++;
				}#end while

			break;

			default :
				$this->_set_error("Colourise Image '".$this->get_val("image_name")."' with invalid type : '".$this->get_val("type")."'", __FILE__, __LINE__);
				unset($this);
				return false;

		}#end switch

		 ############################################################################################
		# now that we have declared all the necessary variables we can do the proper
		# initialisation
		$this->_set_variables($tag, true);

		#######################################################################################
		# OK, so know that we have created it lets check there are a few necessary values

		# we must have a image_name, otherwise we don't know who we are
		if (!$this->get_val("image_name")) {
			$this->_set_error("Colourise Image without a name", __FILE__, __LINE__);
			unset($this);
			return false;

		}

		# let's make sure that the type is lower case
		$this->set_var("type", strtolower($this->get_val("type")));

		return true;

	}#end create()
 
	function paint() {

		$image = $this->get_image_file();
		$image = ($image) ? $this->get_val("CUSTOM_GENERATED_PREFIX").$image : lib_href('images/blank.gif');

		# if they only want us to echo out the 
		if ($this->get_val("filename_only")) {
			echo $image;
	
		# else print out a full tag
		} else {
			?><img src="<?=$image?>" <?=$this->get_val("extra")?> ><?
		}

	}#end paint()

	function area_val() {

		$image = $this->get_image_file();
		if ($image) 
			return $this->get_val("CUSTOM_GENERATED_PREFIX").$image;
		else 
			return "";

	}#end area_val()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name="") {
		$web_system = &get_web_system();

		$changes_made = false;
		
		$uneditable = array("image_name", "image", "type", "tolerance", "filename_only", "extra", "print");

		if ($this->get_val("type") == "multiple_colours"){
			$i = 1;
			while($this->get_val("from_colour_".$i)) {
				$uneditable[] = "from_colour_".$i;
				$i++;
			}#end while
		}#endif

		$backend = &$web_system->get_backend();
		$changes_made |= $this->print_vars_backend($backend, $prefix_name, $uneditable, ucwords(str_replace("_", " ", $this->get_val("image_name"))));

		return $changes_made;

	}#end print_user_backend()
	 
	 #############################################################
	# returns the image name, depending on what type of image we are
	function get_image_file() {

		switch ($this->get_val("type")) {
		
			case "greyscale" :

				return $this->get_greyscale_image_file();

			break;

			case "multiple_colours" :

				return $this->get_multi_colour_image_file();

			break;

			default : 
				$this->_set_error("Unable to Colourise Image '".$this->get_val("image_name")."',  type '".$this->get_val("type")."' unknown.", __FILE__, __LINE__);
				return;

		}#end switch

	}#end get_image_file()

	 ##################################################################
	# returns the greyscale image name, creating the image if need-be
	function get_greyscale_image_file() {

		$custom_dir = $this->get_val("CUSTOM_GENERATED_DIR");
		$ext = get_file_type($this->get_val("image"));

		$name = $this->image_name_prefix();
		$name .= "_".strtolower($this->get_val("black_colour"));
		$name .= "_".strtolower($this->get_val("white_colour"));
		$dest = $custom_dir."/".$name.".".$ext;

		# if the destination file doesn't exist, then lets create it
		if (!file_exists($dest)) {

			$dir = $this->get_val("IMAGE_DIR");
			$source = $dir."/".$this->get_val("image");

			# useful to have the source file :)
			if (file_exists($source)) {
				global $INCLUDE_PATH;
				include_once("$INCLUDE_PATH/image.inc");
				image_gradient_palette($source,$dest,$this->get_val("black_colour"),$this->get_val("white_colour"));
			} else {
				$this->_set_error("Unable to colourise '".$this->get_val("image_name")."', source file '$source' doesn't exist", __FILE__, __LINE__);

				return "";

			}#end if source exists

		}#end if dest exists

		return basename($dest);

	}#end get_greyscale_image_file()

	 #####################################################################
	# returns the multi-colour image name, creating the image if need-be
	function get_multi_colour_image_file() {

		$colours = Array();
		$i = 1;
		while($this->get_val("from_colour_".$i) && $this->get_val("to_colour_".$i)) {
			$colours[strtolower($this->get_val("from_colour_".$i))] = strtolower($this->get_val("to_colour_".$i));
			$i++;
		}#end while

		$custom_dir = $this->get_val("CUSTOM_GENERATED_DIR");
		$ext = get_file_type($this->get_val("image"));

		$name = $this->image_name_prefix();
		foreach($colours as $from => $to) {
			$name .= "_".$from. "_".$to;
		}#end foreach

		$dest = $custom_dir."/".$name.".".$ext;

		# if the destination file doesn't exist, then lets create it
		if (!file_exists($dest)) {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/image.inc");

			$dir = $this->get_val("IMAGE_DIR");
			$source = $dir."/".$this->get_val("image");
			# useful to have the source file :)
			if (file_exists($source)) {
				image_remap_colour($source,$dest,$colours, $this->get_val("tolerance"));
			} else {
				$this->_set_error("Unable to colourise '".$this->get_val("image_name")."', source file '$source' doesn't exist", __FILE__, __LINE__);
				return "";
			}#end if source exists

		} #end if dest exists

		return basename($dest);

	}#end get_multi_colour_image_file()

	function image_name_prefix() {

		switch ($this->get_val("type")) {
			case "greyscale" : 
				$type = "g"; 
			break;

			case "multiple_colours" :
				$type = "mc"; 
			break;

			default :
				return "";

		}#end switch

		# do a bit of escaping for the var name
		$name_prefix = "colourise_image_".$type."_".$this->get_val("image_name");
		$name_prefix = str_replace(" ", "_",    $name_prefix);
		$name_prefix = str_replace("'", "_sq_", $name_prefix);
		$name_prefix = str_replace('"', "_dq_", $name_prefix);

		return $name_prefix;

	}#end image_name_prefix()

}#end class Site_Design_Area_Colourise_Image_Individual

?>

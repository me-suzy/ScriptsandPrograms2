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
## Desc: this is a single layer image
## $Source: /home/cvsroot/xtras/site/design_areas/layer_image/individual.inc,v $
## $Revision: 1.3 $
## $Author: bvial $
## $Date: 2003/04/02 03:51:02 $
#######################################################################
include_once("$INCLUDE_PATH/html_image.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Layer_Image_Individual extends Site_Design_Area {

	function Site_Design_Area_Layer_Image_Individual(&$_owner) {

		$this->Site_Design_Base($_owner);

		# declare the variables
		$this->_set_var("image_name",		"",				"text");
		$this->_set_var("output_type",		"png",			"set", "", Array("png" => "png", "jpeg" => "jpeg", "gif" => "gif"));
		$this->_set_var("num_layers",		"1",			"text");
		$this->_set_var("print",			"1",			"boolean");
		$this->_set_var("image",			"",				"text");
		$this->_set_var("filename_only",	"0",			"boolean");
		$this->_set_var("extra",			"",				"text");

	}#end constuctor

	function create(&$tag) {

		$this->_set_variables($tag, true);
		$i = 1;
		do {
			# we must have a image, otherwise what be the point
			$image = $this->get_var('image_'.$i);
			if (!$image['value'] && $image['value_type'] != "design_area") {
				$i--;
				break;
			}
			$i++;
		} while(true);

		# set up the number of layers we have so that when print
		$this->set_var('num_layers', $i);

		#######################################################################################
		# OK, so know that we have created it lets check there are a few necessary values

		# we must have a image_name, otherwise we don't know who we are
		if (!$this->get_val("image_name")) {
			$this->_set_error("Layer Image without a name", __FILE__, __LINE__);
			unset($this);
			return false;

		}

		# we must have more than one layer, otherwise what is the point
		# unless another layer image has been defined and we are just a print call
		if ($i <= 1 && (!$this->_owner->image_name_used($this->get_val("image_name")) || !$this->get_val("print"))) {
			# only show the 
			$this->_set_error("You need more than one image to create a Layer Image (for image '".$this->get_val("image_name")."'", __FILE__, __LINE__);
			unset($this);
			return false;
		}


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

	 #############################################################
	# returns the image name, depending on what type of image we are
	function get_image_file() {

		# do a bit of escaping for the var name
		$custom_dir = $this->get_val('CUSTOM_GENERATED_DIR');
		# do a bit of escaping for the var name
		$name  = 'layer_image_'.$this->get_val('image_name');
		$name  = str_replace(Array(' ', "\t", "'", '"'), Array('_', '_', '_sq_', '_dq_'), $name);
		$name .= '.'.$this->get_val("output_type");

		$dest = $custom_dir."/".$name;

		# if the destination file doesn't exist, then lets create it
		if (!file_exists($dest)) {
			global $INCLUDE_PATH;
			include_once("$INCLUDE_PATH/image.inc");

			# the first image is the BG img so use it a the main canvas
			$source = $this->get_image_src('image_1');
			if ($im = image_from_file($source)){

				$width  = ImageSX($im);
				$height = ImageSY($im);

				$num_layers = (int) $this->get_val('num_layers');
				for($i = 2; $i <= $num_layers; $i++) {
					$source = $this->get_image_src('image_'.$i);
					if ($src_im = image_from_file($source)){
						ImageCopy($im, $src_im, 0, 0, 0, 0, $width, $height);
						ImageDestroy($src_im);
					}#end if source exists
				}#end for

				image_to_file($im, $dest);
				ImageDestroy($im);

			}#end if
					
		}#end if dest exists

		return $name;

	}#end get_image_file()

}#end class

?>

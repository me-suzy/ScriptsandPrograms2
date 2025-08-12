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
## File: xtras/site/design_areas/rollover_image/individual.inc
## Desc: this is a single rollover image for the design
## $Source: /home/cvsroot/xtras/site/design_areas/rollover_image/individual.inc,v $
## $Revision: 2.8 $
## $Author: csmith $
## $Date: 2003/10/09 21:55:25 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
include_once("$INCLUDE_PATH/html_image.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Rollover_Image_Individual extends Site_Design_Area {

	var $num_prints = 0;	# the number of times this image has been printed
							# this time, used to prevent name clashes

	function Site_Design_Area_Rollover_Image_Individual(&$_owner) {

		$this->Site_Design_Base($_owner);
	
		$this->customisable = true;
		# declare the variables
		$this->_set_var("image_name", "", "text");
		$this->_set_var("image", "", "text");
		$this->_set_var("image_rollover", "", "text");
		$this->_set_var("link", "", "text");
		$this->_set_var("window_status",  "", "text");
		$this->_set_var("new_window",  "1", "boolean");
		$this->_set_var("extra",  "", "text");

	}#end Site_Design_Area_Rollover_Image_Individual()

	function create(&$tag) {

		# init all vars
		$this->_set_variables($tag, true);

		#######################################################################################
		# OK, so know that we have created it lets check there are a few necessary values

		# we must have a image_name, otherwise we don't know who we are
		if (!$this->get_val("image_name")) {
			$this->_set_error("Rollover Image without a name", __FILE__, __LINE__);
			unset($this);
			return false;

		}

		# do a bit of escaping for the var name
		$name = str_replace(" ", "_",    $this->get_val("image_name"));
		$name = str_replace("'", "_sq_", $name);
		$name = str_replace('"', "_dq_", $name);
		$this->set_var("image_name", $name);

		# we must have a image, otherwise what be the point
		$image = $this->get_var("image");
		if (!$image['value'] && $image['value_type'] != "design_area") {
			$this->_set_error("Rollover Image without an image", __FILE__, __LINE__);
			unset($this);
			return false;

		}

		# we must have a rollover image, otherwise what be the point
		$image_rollover = $this->get_var("image_rollover");
		if (!$image_rollover['value'] && $image_rollover['value_type'] != "design_area") {
			# backwards compatibility, for consitency renamed 'rollover_image' to 'image_rollover'
			$rollover_image = $this->get_var("rollover_image");
			if ($rollover_image['value'] && $rollover_image['value_type'] != "design_area") {
				$this->_set_vars["image_rollover"] = $this->get_var("rollover_image");
				$this->delete_var("rollover_image");
			} else {
				$this->_set_error("Rollover Image without an image for the rollover", __FILE__, __LINE__);
				unset($this);
				return false;
			}
		}

		return true;

	}#end create()
 
	function paint() {

		if ($this->get_val("window_status")) {
			$window_status_over = "window.status='".addslashes($this->get_val("window_status"))."';";
			$window_status_out  = "window.status='';";
		}
		if ($this->get_val("new_window")) {
			$target = "target='_blank'";
		}

		$image_name = "image_rollover_".$this->get_val("image_name").(($this->num_prints) ? "_".$this->num_prints : "");
		$this->num_prints++;

		$site_design = &$this->get_top_owner();
		$site_design->add_image_rollover($image_name, $this->get_image_href("image_rollover"));

		 ?><a <?=$target?> onmouseover="imgRoll('<?=$image_name?>'); <?=$window_status_over?> return true;" onmouseout="imgRoll('<?=$image_name?>'); <?=$window_status_out?> return true;" <?=(($this->get_val("link"))?"href=".$this->get_val("link"):"")?>><img src="<?=$this->get_image_href("image")?>" name="<?=$image_name?>" border="0" <?=$this->get_val("extra")?>></a><?

	}#end area_val()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend() {
		$web_system = &get_web_system();

		$uneditable_vars = Array('image_name','extra');

		$backend = &$web_system->get_backend();

		$changes_made |= $this->print_vars_backend($backend,$this->get_val('image_name'),$uneditable_vars, ucwords(str_replace('_', ' ', $this->get_val("image_name"))));

		return $changes_made;

	}#end print_user_backend()

}#end class

?>

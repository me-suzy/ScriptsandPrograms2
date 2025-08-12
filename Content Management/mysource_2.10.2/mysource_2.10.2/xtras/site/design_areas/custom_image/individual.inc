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
## File: xtras/site/design_areas/custom_image/individual.inc
## Desc: this is a single custom image for the design
## $Source: /home/cvsroot/xtras/site/design_areas/custom_image/individual.inc,v $
## $Revision: 2.17 $
## $Author: bvial $
## $Date: 2003/04/02 03:51:02 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/html_image.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Custom_Image_Individual extends Site_Design_Area {

	# temp var - not saved
	var $temp = false;

	function Site_Design_Area_Custom_Image_Individual(&$_owner) {

		$this->Site_Design_Base($_owner);

		# declare the variables
		$this->_set_var('image_name', '', 'text', '');
		$this->_set_var('max_width',  '', 'text', '');
		$this->_set_var('max_height', '', 'text', '');
		$this->_set_var('alt',        '', 'text', 'The text that you want to appear in the alt attribute for this image');
		$this->_set_var('extra',      '', 'text', '');
		$this->_set_var('filename_only', '0', 'boolean', '');

		$this->cacheable = false;

	}#end Site_Design_Area_Custom_Image_Individual()

	 #############################################
	# Some other things we don't want serialized
	function __sleep() {
		$result = Site_Design_Area::__sleep();
		array_remove_element('temp',$result);
		return $result;
	}


	function create(&$tag) {

		# init all vars
		$this->_set_variables($tag);
		$this->cacheable = false;

		#######################################################################################
		# OK, so know that we have created it lets check there are a few necessary values

		# we must have a image_name, otherwise we don't know who we are
		if (!$this->get_val('image_name')) {
			$this->_set_error('Custom Image without a name', __FILE__, __LINE__);
			unset($this);
			return false;

		}

		# if some extra info exists then strip out any width/height stuff because we set that ourselves
		if ($extra = $this->get_val('extra')) {
			$e = "(height|width)=['\"]?[^'\" ]+['\"]?";
			$extra = eregi_replace($e, '', $extra);
			$extra = trim($extra);

			# strip out any alt tags as we'll make those customisable
			$e = "alt=['\"]?([^'\"]+)['\"]?";
			if (ereg($e, $extra, $matches)) {
				if (!$this->get_val('alt')) {
					$this->set_var('alt', $matches[1]);
				}
				$extra = eregi_replace($e, '', $extra);
			}

			# if they have set a border then use it otherwise set border="0"
			$e = "border=['\"]?[^'\"]+['\"]?";
			if (!ereg($e, $extra)) {
				$extra .= ' border="0"';
			}
			$this->set_var('extra', $extra);
		}#end if

		return true;

	}#end create()
 
	function paint() {

		$image = $this->get_image_file();

		# if they only want us to echo out the 
		if ($this->get_val('filename_only')) {
			if ($image['filename']) { 
				echo $this->get_val('CUSTOM_IMAGE_PREFIX').$image['filename'];
			} else { # no image
				 echo lib_href('images/blank.gif');
			}#end if
		
		# else print out a full tag
		} else {
			if ($image['filename']) { 
				$image_size = getimagesize($image['directory'].'/'.$image['filename']);
				if ($alt = $this->get_val('alt')) $alt = ' alt="'.htmlspecialchars($alt).'"';
				?><img src="<?=$this->get_val('CUSTOM_IMAGE_PREFIX').$image['filename']?><?=($this->temp) ? '?'.time() : ''?>" <?=$image_size[3]?> <?=$alt?> <?=$this->get_val('extra')?>><?

			} else { # no image
				?><img src="<?=lib_href('images/blank.gif');?>" border="0" width="1" height="1"><?
			}#end if
		}#end if filename only

	}#end paint()

	function area_val() {

		$image = $this->get_image_file();

		if ($image['filename']) { 
			return $this->get_val('CUSTOM_IMAGE_PREFIX').$image['filename'];

		}#end if

		return '';

	}#end area_val()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend() {
		$web_system = &get_web_system();

		$image = $this->get_image_file();
		$backend = &$web_system->get_backend();

		$uneditable_vars = Array('image_name', 'max_width','max_height','extra','filename_only');

		$changes_made |= $this->print_vars_backend($backend, $this->get_val('image_name'), $uneditable_vars, ucwords(str_replace('_', ' ', $this->get_val('image_name'))));

		$backend->open_field('Image');

		echo image_upload($image['field_name'],
					$image['directory'].'/'.$image['field_name'],
					$this->get_val('CUSTOM_IMAGE_PREFIX'),
					'',
					$this->get_val('max_width'),
					$this->get_val('max_height'),
					true);

		return $changes_made;

	}#end print_user_backend()
	 
	 #############################################################
	# returns the image name in 2 parts, directory and filename
	function get_image_file() {

		$dir = $this->get_val('CUSTOM_IMAGE_DIR');
		# do a bit of escaping for the var name
		$name = 'Custom_Image_'.$this->get_val('image_name');
		$name = str_replace(' ',  '_',    $name);
		$name = str_replace('\'', '_sq_', $name);
		$name = str_replace('"',  '_dq_', $name);

		if ($filename = get_image($dir.'/'.$name)) {
			$filename = basename($filename);
		}

		return array('directory' => $dir, 'filename' => $filename, 'field_name' => $name);

	}#end get_image_file()


}#end class Site_Design_Area_Custom_Image_Individual

?>

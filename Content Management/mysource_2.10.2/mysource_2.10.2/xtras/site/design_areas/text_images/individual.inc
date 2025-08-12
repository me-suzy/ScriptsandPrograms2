<?  ##############################################
   ### MySource ------------------------------###
  ##- Text Images Module ------ PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## Desc: this is a single image
## $Source: /home/cvsroot/xtras/site/design_areas/text_images/individual.inc,v $
## $Revision: 1.6 $
## $Author: csmith $
## $Date: 2003/11/12 23:30:06 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Text_Images_Individual extends Site_Design_Area {

	var $args          = Array();
	var $variable_args = Array();

	function Site_Design_Area_Text_Images_Individual(&$_owner) {
		$this->Site_Design_Base($_owner);

		$this->_set_var("image_name",		"",			"text");
		# the value to call in get_val() to use as the string
		$this->_set_var("string_val",		"",			"text");
		 # the name of the font in the text images web extension
		$this->_set_var("font_name",		"",			"text");
		$this->_set_var("output_type",		"png",		"set", "", Array("png" => "png", "jpeg" => "jpeg", "gif" => "gif"));
		$this->_set_var("extras",			"",			"text");

	}#end constuctor

	function create(&$tag) {

		$this->_set_variables($tag, true);

		#######################################################################################
		# OK, so know that we have created it lets check there are a few necessary values

		# we must have a image_name, otherwise we don't know who we are
		if (!$this->get_val('image_name')) {
			$this->_set_error('Text Image without a name', __FILE__, __LINE__);
			unset($this);
			return false;
		}

		if (!$this->get_val('font_name')) {
			$this->_set_error('Text Image without a font', __FILE__, __LINE__);
			unset($this);
			return false;
		}

		if (!$this->get_val('size')) {
			$this->_set_error('Text Image without a font size', __FILE__, __LINE__);
			unset($this);
			return false;
		}

		# use the array_keys because we need will be delete bits out of the _set_vars array, and this
		# tends to stuff up the internal pointer, when we are looping
		$var_names = array_keys($this->_set_vars);
		foreach($var_names as $var_name) {
			switch($var_name) {
				# ignore these variables because we need them to be in _set_vars
				case 'image_name'  :
				case 'string_val'  :
				case 'font_name'   :
				case 'output_type' :
				case 'extras'      :
					continue; # try next var_name
				break;
					
				# for these 2 we need to make sure that they are booleans
				case 'colour_transparent' :
				case 'bg_colour_transparent' :
					$this->set_var($var_name, $this->_get_bool_value($this->get_val($var_name)));

				# NOTE : no break;, we want to fall through to default

				# OK, so it's an arg
				default :
					# if the var is a variable reference
					# then we will need to set it a paint time
					if ($this->_set_vars[$var_name]['value_type'] == "variable") {
						# we only need to add this to the variable vars array if we are in creation mode
						$this->variable_args[] = $var_name;
					} else {
						# set the value into the args array and delete it from the _set_vars
						$this->args[$var_name] = $this->get_val($var_name);
						if (strstr($var_name, 'colour')) $this->args[$var_name] = ereg_replace('^#', '', $this->args[$var_name]);
						$this->delete_var($var_name);
					}#end if
			}#end switch

		}#end for
		return true;

	}#end create()
 
	function paint() {

		# get the values for any variable args and attach them to the args array
		foreach($this->variable_args as $var_name) {
			$this->args[$var_name] = $this->get_val($var_name);
		}#end for

		if (!count($this->args)) return;

		$web = &get_web_system();
		$text_images = &$web->get_extension("text_images");

		if(!$str = $this->get_val($this->get_val('string_val'))) $str = $this->get_val('string_val');

		list($file, $href, $image_size) = $text_images->get_image($str, $this->get_val('font_name'), $this->get_val('output_type'), $this->args);

		if ($href) {
			#$href .= '?refresh='.time();
			echo '<img src="'.$href.'" '.$image_size.' '.$this->get_val('extras').'>';
		}

	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name="") {
		$web_system = &get_web_system();

		$changes_made = false;
		
		$uneditable = array("image_name", "font_name", "extras","output_type");

		$backend = &$web_system->get_backend();
		$changes_made |= $this->print_vars_backend($backend, $prefix_name, $uneditable, ucwords(str_replace("_", " ", $this->get_val("image_name"))));

		return $changes_made;

	}#end print_user_backend()

}#end class

?>

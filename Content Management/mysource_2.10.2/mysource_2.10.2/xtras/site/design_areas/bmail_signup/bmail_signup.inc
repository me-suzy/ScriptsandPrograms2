<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This software Copyright Squiz.net 2001
## All Rights Reserved.
##
## File: xtras/site/designs_areas/bmail_signup/bmail_signup.inc
## Desc: this class is used to print the customised bmail signup on this page
## $Source: /home/cvsroot/xtras/site/design_areas/bmail_signup/bmail_signup.inc,v $
## $Revision: 1.6 $
## $Author: bvial $
## $Date: 2003/10/09 06:53:17 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Bmail_Signup extends Site_Design_Area {

	function Site_Design_Area_Bmail_Signup(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		# set up some default variables, 
		# so unless they write over them we have something to play with

		#editable
		$this->_set_var("listid", "", "set", "Select the list you want the user to be signed up to", array("" => "None"));
		$this->_set_var("intro_text", "", "text", "text to appear before the boxes");
		$this->_set_var("class", "fineprint", "text", "stylesheet class for the text and boxes");
		$this->_set_var("email_input_size", "5", "text", "size of the text box");
		$this->_set_var("submit_text", "", "text", "text to appear on submit button");
		$this->_set_var("submit_image", "", "text");
		$this->_set_var("firstname_input_size", "5", "text", "size of the text box");
		$this->_set_var("surname_input_size", "5", "text", "size of the text box");
		$this->_set_var("email_default_text", "", "text", "the default text in the email text box (optional)");


	}#end Site_Design_Area_Bmail_Signup()

		function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag, true);

		 ############################################
		# process the contents of the element
		foreach($tag['contents'] as $index => $element)
		{
			# if we are dealing with a tag
			switch ($element['_type']) {
			
				case "TAG"         : # must be a print tag, we'll deal with it later, 
				case "DESIGN_AREA" : # dealt with above so just add to contents
				case "HTML"        : # nothing to do to this yet, so just add to contents array
					$this->contents[] = $element;

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

	}#end create()

	function get_val($name) {
		if (!$this->_get_val_cache) $this->_get_val_cache = Array();
		switch(strtolower($name)) {
			case "beginform" : 
				$page = &$this->get_page();
				$href = $page->get_href(true);
                $str = "<form name='bulkmail_signup' method=post action=\"".$href."mysource_users_extension=bmail\">";
				return $str;
			break;
			case "endform":
				$web_system = &get_web_system();
				$str = "<input type=hidden name=listid value=\"".$this->get_val("listid")."\">";
				$str .= "<input type=hidden name=action value=\"bulkmail_signup\">";
				$str .= "<input type=hidden name=siteid value=\"".$site->id."\">";
                $str .= "<input type=hidden name=pageid value=\"".$web_system->current_pageid."\"></form>";
				return $str;
            break;
			case "email_input":
				$str = "<input type=text name=email size=".$this->get_val("email_input_size")." value='".$this->get_val("email_default_text")."'>";
				return $str;
				break;
			case "firstname_input":
				$str = "<input type=text name=firstname size=".$this->get_val("firstname_input_size").">";
				return $str;
				break;
			case "surname_input":
				$str = "<input type=text name=surname size=".$this->get_val("surname_input_size").">";
				return $str;
				break;
			case "submit":
				$image = $this->get_val("submit_image");
				if ($image) {
					# if the image doesn't contain the custom or normal image prefix use the normal one
					if (  !stristr($image, $this->get_val("CUSTOM_IMAGE_PREFIX")) 
						&& !stristr($image, $this->get_val("IMAGE_PREFIX"))
						) {
						$image = $this->get_val("IMAGE_PREFIX").$image;
					}#end if
					$str = "<input type=image src=".$image." name=Go value=\"Go\" border=\"0\">";
				} else { # no image use default input button
					$str = "<input name=Go type=submit value=\"".$this->get_val("submit_text")."\">";
				}#end if no submit
				return $str;
				break;
			default :
				return Site_Design_Area::get_val($name, $pageid);
				break;
		}
	}


	function paint(&$tag_attributes) {
		foreach($this->contents as $element) {
			switch($element['_type'])
			{
				# plain html element just echo its contents
				case "HTML" :
					echo $element['contents'];
				break;

				# nested design area so print/paint it
				case "DESIGN_AREA" :
					$this->_print_nested_area($element);

				break;

				# taged element ... should really only be a print element
				case "TAG" :

					switch($element['operation'])
					{
						case "print" : 
							$this->print_val($element);
						break;
					}

				break;
				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach contents
	}#end paint()

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name) {

		if($this->get_val("submit_text")) $uneditable[] = "submit_image";
	
		$web_system = &get_web_system();

		$changes_made = false;

		$backend = &$web_system->get_backend();

		$users_system = &get_users_system();
		$bmail_system = &$users_system->get_extension("bmail");

		$this->_set_vars['listid']['options'] = $bmail_system->get_list_names();

		$changes_made |= Site_Design_Base::print_vars_backend($backend, $prefix_name, $uneditable);

		return $changes_made;

	}#end print_user_backend()

	  ################################################
	 # Returns a description of the design area 
	function get_description() {

		return "Change the appearance of the bMail signup area";

	}#end get_description()

}#end class Site_Design_Area_Bmail_Signup

?>
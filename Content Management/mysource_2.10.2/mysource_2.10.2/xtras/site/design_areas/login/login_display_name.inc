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
## File: xtras/site/design_areas/login/login_display_name.inc
## Desc: this printe the buttons and all code needed for the login form
## $Source: /home/cvsroot/xtras/site/design_areas/login/login_display_name.inc,v $
## $Revision: 2.1 $
## $Author: blair $
## $Date: 2002/02/11 04:33:51 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Login_Display_Name extends Site_Design_Area {

	var $contents;
	
	function Site_Design_Area_Login_Display_Name(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->customisable = true;

		# init vars
		$this->contents = Array();

	}#end Site_Design_Area_Login_Display_Name()

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag);
		$this->_set_nested_areas($tag, "login");

		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element)
		{
			# if we are dealing with a tag
			switch ($element[_type]) {
			
				case "TAG"         :   # must be a print tag, we'll deal with it later, 
				case "DESIGN_AREA" : # dealt with above so just add to contents
				case "HTML"        :  # nothing to do to this yet,

					$this->contents[] = $element;

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

	}#end create()
 
	function paint() {
		global $SESSION;

		# only do anything if they are logged in
		if ($SESSION->logged_in()) {

			foreach($this->contents as $element)
			{

				switch($element[_type])
				{
					# plain html element just echo its contents
					case "HTML" :
						echo $element[contents];

					break;

					# nested design area so print/paint it
					case "DESIGN_AREA" :
						$this->_print_nested_area($element);

					break;

					# taged element ... should really only be a print element
					case "TAG" :

						switch($element[operation])
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

		}#end if

	}#end paint()

	   ###############################################################
	  # this function returns values for the special variables
	 # if it is not a predefined variable it uses the value of the 
	# variable in this object
	function get_val($name, $pageid="") {
		global $SESSION;

		switch(strtolower($name))
		{
			case "name" : 
				return $SESSION->user->name();
			break;

			case "firstname" : 
				return $SESSION->user->firstname;
			break;

			case "surname" : 
				return $SESSION->user->surname;
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name, $pageid);
		}#end switch

	}#end get_val()
	 

}#end class Site_Design_Area_Login_Display_Name

?>
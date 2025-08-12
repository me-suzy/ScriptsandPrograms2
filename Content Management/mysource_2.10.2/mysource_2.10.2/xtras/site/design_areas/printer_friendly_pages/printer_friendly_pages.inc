<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## Desc: Controls the display of a the link to the printer friendly version of a page
## $Source: /home/cvsroot/xtras/site/design_areas/printer_friendly_pages/printer_friendly_pages.inc,v $
## $Revision: 2.8 $
## $Author: brobertson $
## $Date: 2003/09/17 00:37:27 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Printer_Friendly_Pages extends Site_Design_Area {

	function Site_Design_Area_Printer_Friendly_Pages(&$_owner) {
		$this->Site_Design_Base($_owner);
	}#end Site_Design_Area_Printer_Friendly_Pages()

	function create(&$tag) {
		
		 ########################################
		# Set up any variables
		$this->_set_variables($tag, true);
		$this->_set_nested_areas($tag, "menu");

		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element) {
			# if we are dealing with a tag
			switch ($element[_type]) {
			
				case 'TAG'        : # must be a print tag, we'll deal with it later
				case 'DESIGN_AREA': # dealt with above so just add to contents
				case 'HTML'       : # nothing to do to this yet, so just add to contents array
						$this->contents[] = $element;

				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

	}#end create()

	function paint(&$tag_attributes) {

		$site = &$this->get_site();
		# Run a security check to see if this extension has been allowed
		if (!in_array('printer_friendly_pages', $site->allowed_extensions)) {
			return;
		}

		# Make sure that the painter is a page template and not a site extension
		$site_design = &$this->get_top_owner();
		if (!$site_design->painter_is_page_template()) return;

		# Make sure that this page is not on the excluded list
		$site_extension = &$site->get_extension("printer_friendly_pages");
		$page = &$this->get_page();
		if (in_array($page->id, $site_extension->get_excluded_pageids())) return;

		foreach($this->contents as $element) {

			switch($element[_type]) {

				# plain html element just echo its contents
				case 'HTML':
					echo $element[contents];

				break;

				# nested design area so print/paint it
				case 'DESIGN_AREA':
					$this->_print_nested_area($element);

				break;

				# taged element ... should really only be a print element
				case 'TAG':

					switch($element[operation]) {
						case 'print':
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

	  ###############################################################
	 # this function returns values for the special global variable
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name) {

		switch(strtolower($name)) {

			case 'printer_friendly_link':
				$page = &$this->get_page();
				$href = $page->get_href(true);
				if (!empty($_SERVER['QUERY_STRING'])) {
					$href .= ereg_replace("p=[0-9]+\&?", "", $_SERVER['QUERY_STRING']).'&';
				}
				return $href.'mysource_site_extension=printer_friendly_pages';
			break;
		
			# Lets hope something "higher up" knows about this one
			default :
				return Site_Design_Base::get_val($name);
		}#end switch

	}#end get_val()


}#end class Site_Design_Area_Printer_Friendly_Pages
?>

<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This software Copyright Squiz.net 2001
## All Rights Reserved.
##
## $Source: /home/cvsroot/xtras/site/design_areas/quickshop/trolley.inc,v $
## $Revision: 2.3 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:13 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_QuickShop_Trolley extends Site_Design_Area {

	var $contents;

	function Site_Design_Area_QuickShop_Trolley(&$_owner) {

		$this->Site_Design_Base($_owner);

		$this->contents = Array();
		$this->_set_var("trolley_image", "", "text");
		$this->_set_var("trolley_text_link", "", "text");
		$this->_set_var("class", "", "text");

	}#end Site_Design_Area_Menu_Type_Original_Section()

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag,true);
		$this->_set_nested_areas($tag, "quickshop");

		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element)
		{
			# if we are dealing with a tag
			switch ($element[_type]) {
			
				case "TAG"         :  # must be a print tag, we'll deal with it later, 
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
 
	function paint() {

		$site = &$this->get_site();
		$this_quickshop = &$site->get_extension("quickshop");
		$trolley = $this_quickshop->get_and_unpack_trolley();    
		$total = $trolley->get_sub_total(1);

		# if no total then don't show anything
		if ($total < '0.01')    # the absolute minimum total for a valid purchase!!!
			return;

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

	}#end paint()

	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $pageid="", $total) {

		switch(strtolower($name)) {

			case "trolley_link" : 
				$page = &$this->get_page();
				$href = $page->get_href(true);
				return $href."mysource_site_extension=quickshop&action=show_trolley";
			break;

			case "trolley_total" : 
				$site = &$this->get_site();
				$this_quickshop = &$site->get_extension("quickshop");
				$trolley = $this_quickshop->get_and_unpack_trolley();    
				$total = $trolley->get_sub_total(1);
				return $total;
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name, $pageid);
		}#end switch

	}#end get_val()

}#end class Site_Design_Area_QuickShop_Trolley 

?>
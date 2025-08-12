<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## File: xtras/site/design_areas/menu/type/original/original_section.inc
## Desc: handle's the individual sections of the original design
##       this be where most of the processing is done.
## $Source: /home/cvsroot/xtras/site/design_areas/ecommerce_store/trolley_link.inc,v $
## $Revision: 1.6 $
## $Author: dofford $
## $Date: 2004/02/17 22:13:51 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Ecommerce_Store_Trolley_Link extends Site_Design_Area_Ecommerce_Store_Design_Area {

	var $contents;

	function Site_Design_Area_Ecommerce_Store_Trolley_Link(&$_owner) {
		$this->Site_Design_Base($_owner);
		$this->contents = Array();
	}#end constructor

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag,true);
		$this->_set_nested_areas($tag, 'ecommerce_store');

		 ############################################
		# process the contents of the element
		foreach($tag['contents'] as $index => $element)
		{
			# if we are dealing with a tag
			switch ($element['_type']) {
			
				case 'TAG'         :  # must be a print tag, we'll deal with it later, 
				case 'DESIGN_AREA' : # dealt with above so just add to contents
				case 'HTML'        : # nothing to do to this yet, so just add to contents array
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

		$store   = &$this->get_ecommerce_store();
		$trolley = &$store->get_trolley();
		# if there are no records in the trolley don't need a link
		if (!count($trolley->get_content_recordids())) return;

		foreach($this->contents as $element) {

			switch($element['_type']) {
				# plain html element just echo its contents
				case 'HTML' :
					echo $element['contents'];
				break;

				# nested design area so print/paint it
				case 'DESIGN_AREA' :
					$this->_print_nested_area($element);
				break;

				# taged element ... should really only be a print element
				case 'TAG' :

					switch($element['operation']) {
						case 'print' : 
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
	function get_val($name, $pageid='') {

		switch($name = strtolower($name)) {

			case 'trolley_link' : 
				$store = &$this->get_ecommerce_store();
				return $store->get_href(false, 'trolley');
			break;

			case 'trolley_total' : 
				$store   = &$this->get_ecommerce_store();
				$trolley = &$store->get_trolley();
				return number_format($trolley->get_total(), 2);
			break;

			# lets hope they declared it themselves
			default :
				$store   = &$this->get_ecommerce_store();
				if(substr($name,0,11) == 'multiplier_') {
					$value = 0;
					$code = substr($name,11);
					$trolley = &$store->get_trolley();
					foreach($trolley->get_record_and_item_lists() as $recordid => $variety_codes) {
						foreach($variety_codes as $variety_code) {
							$selections = $trolley->variety_code_to_selections();
							$value += $trolley->get_item_data($recordid, $selections, $code);
						}
					}
					return $value;
				}
				return Site_Design_Area::get_val($name, $pageid);
		}#end switch

	}#end get_val()

}#end class 

?>

<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## File: xtras/site/design_areas/menu/type/original/original_section.inc
## Desc: handle's the individual sections of the original design
##       this be where most of the processing is done.
## $Source: /home/cvsroot/xtras/site/design_areas/ecommerce_store/trolley_contents_item.inc,v $
## $Revision: 1.5 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:10 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Ecommerce_Store_Trolley_Contents_Item extends Site_Design_Area_Ecommerce_Store_Design_Area {

    var $contents;

	function Site_Design_Area_Ecommerce_Store_Trolley_Contents_Item(&$_owner) {

		$this->Site_Design_Base($_owner);
		$this->contents = Array();
        
	}#end constructor

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag, true);
		$this->_set_nested_areas($tag, "ecommerce_store");

		 ############################################
		# process the contents of the element
		foreach($tag['contents'] as $index => $element) {
			# if we are dealing with a tag
			switch ($element['_type']) {
			
				case "TAG"  :        # must be a print tag, we'll deal with it later, 
				case "DESIGN_AREA" : # dealt with above so just add to contents
				case "HTML" :        # nothing to do to this yet, so just add to contents array
						$this->contents[] = $element;
				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

	}#end create()
 
	function paint($productid, $variety_code, &$store) { 

		foreach($this->contents as $element) {

			switch($element['_type']) {

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

					switch($element['operation']) {
						case "print" : 
							$this->print_val($element, $productid, $variety_code, $store);
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

	  ######################################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $tag, $productid, $variety_code, &$store) {
		global $action;

		switch(strtolower($name)) {

			case "attribute" :
				$product = &$store->get_record($productid);
				if ($product->id) {
					$attribute = $tag['attributes']['attribute_name'];
					# if they want the "name" of the product
					switch($attribute) {
						case '__name__':
							$ret_val = $product->name();
						break;

						case '__cost__':
							$trolley = &$store->get_trolley();
							$val = $trolley->get_item_cost($productid, $variety_code);
							$ret_val = '<input type="hidden" name="'.htmlspecialchars('products['.$productid.']["'.$variety_code.'"][cost]').'" value="'.$val.'">';
							$ret_val .= number_format($val, 2);
						break;

						case '__subtotal__':
							$trolley = &$store->get_trolley();
							$val = number_format($trolley->get_item_subtotal($productid, $variety_code), 2);
							$size  = isset($tag['attributes']['size'])  ? $tag['attributes']['size']					: '';
							$class = isset($tag['attributes']['class']) ? ' class="'.$tag['attributes']['class'].'"'	: '';
							$ret_val = text_box('products['.$productid.']["'.$variety_code.'"][subtotal]', $val, $size, '', 'onFocus="javascript: this.blur();"'.$class);
						break;

						default:
							$ret_val = $product->get_attribute_value(0, $attribute);
						
					}#end switch

					return (trim($ret_val) != "") ? $ret_val : '&nbsp;';

				}#end if
				return '&nbsp;';
			break;


			case "multiplier" :
				$multiplier = $tag['attributes']['multiplier_name'];
				$trolley = &$store->get_trolley();
				$val = $trolley->get_item_data($productid, $variety_code, $multiplier);
				$size  = isset($tag['attributes']['size']) ? $tag['attributes']['size'] : '';
				$class = isset($tag['attributes']['class']) ? ' class="'.$tag['attributes']['class'].'"' : '';
				$ret_val = text_box('products['.$productid.']["'.$variety_code.'"][multipliers]['.$multiplier.']', $val, $size, '', 'onChange="javascript: ecommerce_store_trolley_change();"'.$class);
				return $ret_val;

			break;


			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name, $pageid);
		}#end switch

	}#end get_val()

	 #######################################################################################
	# print's the value for the passed name escaping as necessary
	function print_val(&$tag, $productid, $variety_code, &$store) {
		echo $this->_print_modified($tag, $this->get_val($tag['attributes']['name'], $tag, $productid, $variety_code, $store));
	}


}#end class 

?>
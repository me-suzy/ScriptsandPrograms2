<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This software Copyright Squiz.net 2001
## All Rights Reserved.
##
## $Source: /home/cvsroot/xtras/site/design_areas/quickshop/trolley_contents_items.inc,v $
## $Revision: 2.1 $
## $Author: blair $
## $Date: 2002/02/11 04:33:51 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_QuickShop_Trolley_Contents_Items extends Site_Design_Area {

    var $contents;

	function Site_Design_Area_QuickShop_Trolley_Contents_Items(&$_owner) {

		$this->Site_Design_Base($_owner);

		$this->contents = Array();
        
	}#end Site_Design_Area_QuickShop_Trolley_Contents_Items()

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag);
		$this->_set_nested_areas($tag, "quickshop");

		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element)
		{
			# if we are dealing with a tag
			switch ($element[_type]) {
			
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
 
	function paint($productid, &$trolley, &$product_handler) { # NOTE - in trolley.inc $productid is refered to as '$itemcode'

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
							$this->print_val($element, $productid, $trolley, $product_handler);
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
	function get_val($name, $productid, &$trolley, &$product_handler) {
		global $action;   
		$web_system = &get_web_system();
		$designid = Site_Design_Area::get_val('id');

		switch(strtolower($name)) {
			case "code" : 
				$code = $product_handler->get_product_code($productid); 
				return ($code) ? $code : '&nbsp;';
			break;

			case "name" : 
				$name = $product_handler->get_product_name($productid); 
				return ($name) ? $name : '&nbsp;';
			break;

			case "description" : 
				$desc = $product_handler->get_product_desc($productid); 
				return ($desc) ? $desc : '&nbsp;';
			break;

			case "price" : 
				$price = $trolley->get_item_price($productid);
				return $price.'<input type=hidden name="price['.$productid.']" value="'.$price.'">';
			break;

			case "quantity" : 
				$site = &$this->get_site();
				$this_quickshop = &$site->get_extension("quickshop");
				$usestockquantity = $this_quickshop->use_stock_quantity;
				$in_stock = $product_handler->get_product_in_stock($productid);

				$str = '<input type="text" name="quantity['.$productid.']" value="'.$trolley->get_item_quantity($productid).'" size=3 maxlength=5 ';

				if ($action == 'show_order_form') {
					$str .= 'onfocus="blur();">';
				} else {
					$str .= 'onchange="changes=true; '.(($usestockquantity) ? 'if (this.value > '.$in_stock.') {alert(\'You are trying to order more items than whats available\'); this.value = '.$in_stock.';}': '').'">';
				}
				return $str;
			break;

			case "available_stock" : 
				return $product_handler->get_product_in_stock($productid);
			break;

			case "line_total" : 
				return $trolley->get_item_total($productid,1); 
			break;

			case "remove_link" : 
				if ($action == 'show_order_form') return '&nbsp;';
				$page = &$this->get_page();
				$href = $page->get_href(true);
				$href .= 'mysource_site_extension=quickshop&action=remove_item&itemcode='.$productid.'&pageid='.$page->id.'&displaytrolley=1'; 
				return $href;
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name, $pageid);
		}#end switch

	}#end get_val()

	 #######################################################################################
	# print's the value for the passed name escaping as necessary
	function print_val(&$tag, &$productid, &$trolley, &$product_handler) {
		echo $this->_print_modified($tag, $this->get_val($tag[attributes][name], $productid, $trolley, $product_handler));
	}


}#end class Site_Design_Area_QuickShop_Trolley_Contents_Items

?>
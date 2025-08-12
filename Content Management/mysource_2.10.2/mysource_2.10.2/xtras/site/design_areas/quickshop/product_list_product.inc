<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This software Copyright Squiz.net 2001
## All Rights Reserved.
##
## $Source: /home/cvsroot/xtras/site/design_areas/quickshop/product_list_product.inc,v $
## $Revision: 2.2 $
## $Author: blair $
## $Date: 2002/02/11 04:33:51 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_QuickShop_Product_List_Product extends Site_Design_Area {

    var $contents;
	var $img_path;
	var $_get_val_cache; # an array for caching get_val calls that require processing
						# (and that SHOULDN'T be changed during the execution of the paint())
						# DOES NOT get saved
	var $_lowest_price_cache;	# an array for caching lowest_prices calls that require processing
								# (and that SHOULDN'T be changed during the execution of the paint())
								# DOES NOT get saved

	function Site_Design_Area_QuickShop_Product_List_Product(&$_owner) {

		$this->Site_Design_Base($_owner);

		$this->contents = Array();

		# init variables
		$this->_set_var("buy_image", "", "text");
		$this->_set_var("unavailable_image", "", "text");
        
	}#end Site_Design_Area_QuickShop_Product_List_Product()

	  #########################################
	 # Converts the object into a nice string
	function pack() {

		# just make sure to kill the get val cache
		unset($this->_get_val_cache);
		# just make sure to kill the lowest price cache
		unset($this->_lowest_price_cache);
		return Site_Design_Area::pack();

	}#end packed()


	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag, true);
		$this->_set_nested_areas($tag, "quickshop");

		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element)
		{
			# if we are dealing with a tag
			switch ($element[_type]) {
			
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
 
	function paint($productid, &$product_handler) {

		foreach($this->contents as $element) {

			switch($element[_type]) {

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
							$this->print_val($element, $productid, $product_handler);
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
	function get_val($name, $productid, &$product_handler) {

		if (!$this->_get_val_cache) $this->_get_val_cache = Array();

		if ($productid) {

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

				case "price" :  # be tricky and display the lowest possible price to user
					list($lowest) = $this->_get_lowest_price($productid, $product_handler);
					$str = number_format($lowest,2);
					$str .= ' <input type="hidden" name="price['.$productid.']" value="'.$lowest.'">';
					return $str;
				break;

				case "price_type" :  # get the price type for lowest possible price to user
					list($lowest, $price_type) = $this->_get_lowest_price($productid, $product_handler);
					return $price_type;
				break;

				case "buy_image" : 
					$in_stock = $product_handler->get_product_in_stock($productid);
					# Don't display the option to buy if the product isn't in stock
					if (!$in_stock)  {
						# use the unavailable image if one exists
						if ($image = Site_Design_Area::get_val("UNAVAILABLE_IMAGE")) {
							return '<img src="'.$this->get_val("IMAGE_PREFIX").$image.'" border=0 alt="Product currently unavailable">';
						} else {
							return '';
						}

					} else {
						if (!$this->_get_val_cache[$name]) {
							$image = $this->get_image_href($name, true);
							$this->_get_val_cache[$name] = '<input type=image src="'.$image.'" alt="Add items and view trolley" border=0>';
						}#end if not in cache

						return $this->_get_val_cache[$name];
						
					}#end if
				break;

				case "quantity" : 

					$in_stock = $product_handler->get_product_in_stock($productid);
					if ($in_stock) {
						$site = &$this->get_site();
						$this_quickshop = &$site->get_extension("quickshop");
						$usestockquantity = $this_quickshop->use_stock_quantity;
						$trolley = $this_quickshop->get_and_unpack_trolley();

						$thisquantity = $trolley->get_item_quantity($productid);
						if (!$thisquantity) $thisquantity = 0;

						$str = '<input type=text name=quantity['.$productid.'] value="'.$thisquantity.'" size=3 maxlength=5 onchange="if (this.value < 1 || this.value.length == 0) {alert(\'You must enter a valid quantity value\');this.value=1;} '.(($usestockquantity) ? 'if (this.value > '.$in_stock.') {alert(\'You are trying to order more items than whats available\'); this.value = '.$in_stock.';}' : '').'">';

					} else {
						$str = 'N/A';
					}#end if
					return $str;
				break;

				case "available_stock" : 
					return $product_handler->get_product_in_stock($productid);
				break;

				case "selected" : 
					return '<input type=checkbox name=selectedproduct['.$productid.'] value="checked">';
				break;

			}#end switch

		} #end if productid

		# lets hope they declared it themselves
		return Site_Design_Area::get_val($name);

	}#end get_val()

	 #######################################################################################
	# print's the value for the passed name escaping as necessary
	function print_val(&$tag, &$productid, &$product_handler) {
		echo $this->_print_modified($tag, $this->get_val($tag[attributes][name], $productid, $product_handler));
	}


	 #######################################################################################
	# returns an array of (price, price_type) for the lowest price available for a product
	function _get_lowest_price($productid, &$product_handler) {
		global $SESSION;
		# use a cache so we don't need to process every time
		if (!$this->_lowest_price_cache) $this->_lowest_price_cache = Array();

		# if it ain't in the cache go get it
		if (!$this->_lowest_price_cache[$productid]) {

			$prices = $product_handler->price_types;
			$lowest = 0;
			foreach($prices as $priceid=>$type) {
				$cost = $product_handler->get_actual_price($productid,$priceid);
				if ($cost <= '0.00')
					break;
				$loggedin_groupids = $SESSION->access_groups;
				$allowed_groupids = $product_handler->get_price_type_groupids($priceid);
				if (count($allowed_groupids) == '0') {   #means this price type is open to anyone
					if (! $lowest)  {# first time comparison
						$lowest = $cost;
						$pricetype = $type;
					} else {
						if ($cost < $lowest) {
							$pricetype = $type;
							$lowest = $cost;
						}
					}
				}
				$incommon = array_intersect($loggedin_groupids,$allowed_groupids);
				if (count($incommon) > 0)   {   # user entiteld to this price rate. check to see                                             # if it beats the lowest so far
					if (! $lowest)  {# first time comparison
						$lowest = $cost;
						$pricetype = $type;
					}   
					else    {
						if ($cost < $lowest)    {
							$pricetype = $type;
							$lowest = $cost;
						}
					}
				}      
				if (count($loggedin_groupids) == '0')   {   #display the highest for the bastard!
					if ($cost > $lowest)    {
						$pricetype = $type;
						$lowest = $cost;                            
					}
				}
				#echo 'L: '.$lowest.'  C: '.$cost;
			}
			$this->_lowest_price_cache[$productid] = Array($lowest, $pricetype);
		}#end if in cache

		return $this->_lowest_price_cache[$productid];

	}#end _get_lowest_price()


}#end class Site_Design_Area_QuickShop_Product_List_Product 

?>
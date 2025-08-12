<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## File: xtras/site/design_areas/menu/type/original/original_section.inc
## Desc: handle's the individual sections of the original design
##       this be where most of the processing is done.
## $Source: /home/cvsroot/xtras/site/design_areas/ecommerce_store/product_list.inc,v $
## $Revision: 1.3 $
## $Author: blair $
## $Date: 2002/04/11 05:58:58 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
include_once(dirname(__FILE__)."/product_list_product.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Ecommerce_Product_List extends Site_Design_Area {

	var $contents;
	var $product_section;	# a section within this tag that is either the generic "page" section
							# or it the "Child_Pages" section

	function Site_Design_Area_Ecommerce_Product_List(&$_owner, $section_name, $section_type="") {

		$this->Site_Design_Base($_owner);

		$this->contents = Array();

	}#end Site_Design_Area_Ecommerce_Product_List()

	  ############################################################
	 # Reset the owner for this object, then reset the sections 
	# owner to this object 
	function reset_owner(&$owner) {
	
		if ($this->product_section) {
			$this->product_section->reset_owner($this);
		}

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the sections beneath it
	function &unset_owner() {

		if ($this->product_section) {
			$this->product_section->unset_owner();
		}

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		if($this->product_section) {
			$tmp->product_section = &$this->product_section->copy();
		}#end if
		return $tmp;

	}#end copy()

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag, true);
		$this->_set_nested_areas($tag, "ecommerce");

		 ############################################
		# process the contents of the element
		foreach($tag[contents] as $index => $element)
		{
			# if we are dealing with a tag
			switch ($element[_type]) {
			
				case "TAG" : 

					# if this is the sub section 
					if ($element[attributes][section]) {

						$section_name = strtolower($element[attributes][section]);

						$class_name = get_class($this)."_".$section_name;

						# kill it because it's not needed anymore
						unset($element[attributes][section]);
						
						$this->product_section = new $class_name($this);
						$this->product_section->create($element);

						# append to the contents array so we know were to print this
						$this->contents[] = Array("_type" => "product_section");

					# must be a print tag, we'll deal with it later, 
					# so just append to contents
					} else {

						$this->contents[] = $element;

					}#end if

				break;

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
 
    # takes a product, and checks if the user of this page has been allocated a price. if not, return false
    function printable_product($productid,&$product_handler)    { #get priceids asociated with this product
        global $SESSION;
        $priceids = $product_handler->get_price_ids($productid);
        foreach($priceids as $priceid=>$actualprice) {
            if ($actualprice > '0.00')  {
                $groupids = $product_handler->get_price_type_groupids($priceid);
                if (count($groupids)=='0'){ # there is no access groups linked with this price type
                    return true;
                }
                if (count(array_intersect($groupids,$SESSION->access_groups)) > '0')
                    return true;
            }
        }
        return false;
    }

	function paint() {
		$site_design = &$this->get_top_owner();
		if (!$site_design->painter_is_page_template())
			return;
        global $action;
        if(($action != 'update_trolley')&&($action != 'remove_item')&&($action != ''))
            return;
		global $displaytrolley;
		if ($displaytrolley)
            return;

		$site = &$this->get_site();
		$ecommerce = &$site->get_extension("ecommerce");
        $product_handler = &$ecommerce->get_product_handler();

        #load the product handler info relative to onyy this page
		$web_system = &get_web_system();
        $product_handler->load_product_info_page_relative($web_system->current_pageid);
		# figure out if there are any printable products on the page
		for(reset($product_handler->products); $productid = key($product_handler->products); next($product_handler->products)) {
			if ($this->printable_product($productid,$product_handler)) {
				$printable_productids[] = $productid;
			}
		}

		if(!count($printable_productids)) return; # Don't bother
    
		#   check to see if any ecommerce related stuff should be printed for this page..
        if (count($product_handler->products) <= 0)
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

				# sub section element
				case "product_section" :		
					foreach($printable_productids as $productid) {
					    $this->product_section->paint($productid, $product_handler);    #dude
					}
				break;

                case "product_form":
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
	function get_val($name, $pageid="") {

		switch(strtolower($name)) {

			case "beginform" : 
				$page = &$this->get_page();
				$href = $page->get_href(true);
                $str = '<form name="productform'.$productid.'" method=post action="'.$href.'mysource_site_extension=ecommerce&action=update_trolley">';
				return $str;
			break;

            case "endform":
				$web_system = &get_web_system();
                $str = '<input type=hidden name=pageid value='.$web_system->current_pageid.'></form>';
				return $str;
            break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name, $pageid);
		}#end switch

	}#end get_val()



}#end class Site_Design_Area_Ecommerce_Product_List 

?>
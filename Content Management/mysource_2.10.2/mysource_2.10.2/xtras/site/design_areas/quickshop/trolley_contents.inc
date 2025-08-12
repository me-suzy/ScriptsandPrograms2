<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This software Copyright Squiz.net 2001
## All Rights Reserved.
##
## $Source: /home/cvsroot/xtras/site/design_areas/quickshop/trolley_contents.inc,v $
## $Revision: 2.6 $
## $Author: csmith $
## $Date: 2003/11/12 23:30:05 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/site_design_area.inc");
include_once(dirname(__FILE__)."/trolley_contents_items.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_QuickShop_Trolley_Contents extends Site_Design_Area {

	var $contents;
	var $items_section;	    # a section within this tag that is either the generic "page" section
							# or it the "Child_Pages" section

	function Site_Design_Area_QuickShop_Trolley_Contents(&$_owner, $section_name, $section_type="") {

		$this->Site_Design_Base($_owner);

		$this->contents = Array();

	}#end Site_Design_Area_QuickShop_Trolley_Contents()

	  ############################################################
	 # Reset the owner for this object, then reset the sections 
	# owner to this object 
	function reset_owner(&$owner) {
	
		if ($this->items_section) {
			$this->items_section->reset_owner($this);
		}

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the sections beneath it
	function &unset_owner() {

		if ($this->items_section) {
			$this->items_section->unset_owner();
		}

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		if($this->items_section) {
			$tmp->items_section = &$this->items_section->copy();
		}#end if
		return $tmp;

	}#end copy()

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
			
				case "TAG" : 

					# if this is the sub section 
					if ($element[attributes][section]) {

						$section_name = strtolower($element[attributes][section]);

						$class_name = get_class($this)."_".$section_name;

						# kill it because it's not needed anymore
						unset($element[attributes][section]);
						
						$this->items_section = new $class_name($this);
						$this->items_section->create($element);

						# append to the contents array so we know were to print this
						$this->contents[] = Array("_type" => "items_section");

					# must be a print tag, we'll deal with it later, 
					# so just append to contents
					} else {

						$this->contents[] = $element;

					}#end if

				break;

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
        global $action, $mysource_site_extension,$displaytrolley;
        if($mysource_site_extension != "quickshop")  return;
		if($action != 'show_trolley' && $action != 'show_order_form' && $displaytrolley != '1')   
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
				case "items_section" :
					
					$site = &$this->get_site();
					$quickshop = &$site->get_extension("quickshop");

					$product_handler = &$quickshop->get_product_handler();
                    $trolley = $quickshop->get_and_unpack_trolley();

                    #we must load all product info to get product name and code when needed
                    $product_handler->load_products();



                    # loop thru items in trolley and display the results ...
    				for(reset($trolley->trolley);$productid = key($trolley->trolley);next($trolley->trolley)){
                        $this->items_section->paint($productid, $trolley, $product_handler); 
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
	function get_val($name) {
		global $action;
		$web_system = &get_web_system();

		switch(strtolower($name))
		{
            case "beginform" : 
				$page = &$this->get_page();
				$href = $page->get_href(true);
                $str = '<form name="updatetrolleyform" method=post action="'.$href.'mysource_site_extension=quickshop';
				if ($action == 'show_order_form') {
					$str .= '&action=show_trolley">';
				} else {
					$str .= '&action=update_trolley&displaytrolley=1"><script language=javascript>var changes = false;</script>';
				}# end if
				return $str;
			break;

            case "endform":
                $str = '<input type=hidden name=pageid value='.$web_system->current_pageid.'></form>';
				return $str;
            break;

            case "update_button":
				if ($action == 'show_order_form') return;
                $str = '<input type=button value="UPDATE" onclick="if (changes) {if (confirm(\'Update changes made? \')) {updatetrolleyform.submit(); return true; }} return false;">';
				return $str;
            break;

            case "order_link":
				if ($action == 'show_order_form') return;
				$site = &$this->get_site();
				$quickshop = &$site->get_extension("quickshop");
				if ($quickshop->ssl) {
					$text = "SECURE ORDER";
				} else {
					$page = &$this->get_page();
					$text = "ORDER";
				}#end if

				$str = '<a href="'.$quickshop->get_url(true).'mysource_site_extension=quickshop&action=show_order_form&pageid='.$web_system->current_pageid.'&'.session_name().'='.session_id().'">'.$text.'</a>';

				return $str;
            break;

            case "order_button":
				if ($action == 'show_order_form') return;
				$site = &$this->get_site();
				$quickshop = &$site->get_extension("quickshop");
				if ($quickshop->ssl) {
					$href = $quickshop->get_url(true);
					$text = "SECURE ORDER";
				} else {
					$page = &$this->get_page();
					$href = $page->get_href(true);
					$text = "ORDER";
				}#end if

				$str = '<input type="button" value="'.$text.'" onclick="JavaScript: window.location=\''.$href.'mysource_site_extension=quickshop&action=show_order_form&pageid='.$web_system->current_pageid.'&'.session_name().'='.session_id().'\';">';
				return $str;
            break;

			case "insecure_order_button":
				if($action == 'show_order_form') return;
				$page = &$this->get_page();
				$href = $page->get_href(true);
				$str = '<input type="button" value="INSECURE ORDER" onclick="JavaScript: window.location=\''.$href.'mysource_site_extension=quickshop&action=show_order_form&pageid='.$web_system->current_pageid.'\';">';
				return $str;
			break;

            case "keepshopping_link":
                if ($action == 'show_order_form') return;
				$site = &$this->get_site();
                return $web_system->get_page_href($site->id,$web_system->current_pageid);
            break;

            case "sub_total" : 
				$trolley = &$this->get_trolley();
				return $trolley->get_sub_total(1);;
			break;

			case "gst_total" : 
				$trolley = &$this->get_trolley();
                return $trolley->get_gst_total(1);
			break;

			case "p_and_h_name" : 
				$trolley = &$this->get_trolley();
                return $trolley->p_and_h[desc];
			break;

            case "p_and_h_total" : 
				$trolley = &$this->get_trolley();
                return $trolley->get_p_and_h();
			break;

            case "grand_total" : 
				$trolley = &$this->get_trolley();
                return $trolley->get_grand_total(1);
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name, $pageid);
		}#end switch

	}#end get_val()

	function &get_trolley() {
		$site = &$this->get_site();
		$quickshop = &$site->get_extension("quickshop");
        $trolley = $quickshop->get_and_unpack_trolley();
		return $trolley;
	}

}#end class Site_Design_Area_QuickShop_Trolley_Contents 

?>

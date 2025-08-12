<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## File: xtras/site/design_areas/menu/type/original/original_section.inc
## Desc: handle's the individual sections of the original design
##	   this be where most of the processing is done.
## $Source: /home/cvsroot/xtras/site/design_areas/ecommerce_store/trolley_contents.inc,v $
## $Revision: 1.13 $
## $Author: bvial $
## $Date: 2003/04/02 03:51:02 $
#######################################################################
include_once(dirname(__FILE__)."/trolley_contents_item.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Ecommerce_Store_Trolley_Contents extends Site_Design_Area_Ecommerce_Store_Design_Area {

	var $contents;
	var $item_section;	# the section that we loop over for each item in the trolley

	function Site_Design_Area_Ecommerce_Store_Trolley_Contents(&$_owner, $section_name, $section_type="") {

		$this->Site_Design_Base($_owner);
		$this->contents = Array();

		$this->_set_var("update_button",		"Update",				"text");
		$this->_set_var("update_image",			"",						"text");
		
		$this->_set_var("delete_button",		"Delete Trolley",		"text");
		$this->_set_var("delete_image",			"",						"text");
		
		$this->_set_var("save_quote_button",	"Save Trolley",			"text");
		$this->_set_var("save_quote_image",		"",						"text");
		
		$this->_set_var("show_quotes_button",	"Show Saved Trolleys",	"text");
		$this->_set_var("show_quotes_image",	"",						"text");

		$this->_set_var("order_button",			"Order",				"text");
		$this->_set_var("order_image",			"",						"text");

	}#end constructor

	  ############################################################
	 # Reset the owner for this object, then reset the sections 
	# owner to this object 
	function reset_owner(&$owner) {
	
		if ($this->item_section) {
			$this->item_section->reset_owner($this);
		}

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the sections beneath it
	function &unset_owner() {

		if ($this->item_section) {
			$this->item_section->unset_owner();
		}

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		if($this->item_section) {
			$tmp->item_section = &$this->item_section->copy();
		}#end if
		return $tmp;

	}#end copy()

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
			
				case "TAG" : 

					# if this is the sub section 
					if ($element['attributes']['section']) {

						$section_name = strtolower($element['attributes']['section']);

						$class_name = get_class($this)."_".$section_name;

						# kill it because it's not needed anymore
						unset($element['attributes']['section']);
						
						$this->item_section = new $class_name($this);
						$this->item_section->create($element);

						# append to the contents array so we know were to print this
						$this->contents[] = Array("_type" => "item_section");

					# must be a print tag, we'll deal with it later, 
					# so just append to contents
					} else {

						$this->contents[] = $element;

					}#end if

				break;

				case "DESIGN_AREA" : # dealt with above so just add to contents
				case "HTML"		: # nothing to do to this yet, so just add to contents array

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
		global $mysource_site_extension, $action;
		if ($mysource_site_extension != "ecommerce_store" 
			|| ($action != 'show_trolley' && $action != 'show_order_form'))
			return;

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
							$this->print_val($element);
						break;
					}

				break;

				# sub section element
				case "item_section" :

					# for each product in the trolley loop over and display
					$store   = &$this->get_ecommerce_store();
					$trolley = &$store->get_trolley();
					$productids = $trolley->get_record_and_item_lists();
	   				foreach($productids as $productid => $variety_codes) {
						foreach($variety_codes as $code) {
							$this->item_section->paint($productid, $code, $store); 
						}
					}
				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					return;

			}#end switch

		}#end foreach contents

	}#end paint()

	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $tag=Array()) {

		switch(strtolower($name)) {
			case "beginform" : 
				$store = &$this->get_ecommerce_store();
				# if we are always displaying the trolley contents then the 
				$str = '<form name="ecommerce_store_update_trolley_form" method="POST" action="'.$store->get_href().'">'
						.'<input type="hidden" name="action" value="">'
						.'<input type="hidden" name="trolley_action" value="">'
						.$this->_js_functions();
				return $str;
			break;

			case "endform":
				$web_system = &get_web_system();
				$str = '<input type=hidden name=pageid value='.$web_system->current_pageid.'></form>';
				return $str;
			break;

			case "update_button"      :
			case "delete_button"      :
			case "save_quote_button"  :
			case "show_quotes_button" :
			case "order_button"       :
				$button = substr(strtolower($name), 0, -7);
				return $this->_create_button($button, 'ecommerce_store_trolley_'.$button.'();');
			break;

			case "subtotal":
				$store   = &$this->get_ecommerce_store();
				$trolley = &$store->get_trolley();
				$val   = number_format($trolley->get_subtotal(), 2);
				$size  = isset($tag['attributes']['size'])  ? $tag['attributes']['size']					: '';
				$class = isset($tag['attributes']['class']) ? ' class="'.$tag['attributes']['class'].'"'	: '';
				return text_box('products_subtotal', $val, $size, '', 'onFocus="javascript: this.blur();"'.$class);
			break;

			case "total":
				$store   = &$this->get_ecommerce_store();
				$trolley = &$store->get_trolley();
				$val   = number_format($trolley->get_total(), 2);
				$size  = isset($tag['attributes']['size'])  ? $tag['attributes']['size']					: '';
				$class = isset($tag['attributes']['class']) ? ' class="'.$tag['attributes']['class'].'"'	: '';
				return text_box('products_total', $val, $size, '', 'onFocus="javascript: this.blur();"'.$class);
			break;

			case "extra":
				$store   = &$this->get_ecommerce_store();
				$trolley = &$store->get_trolley();
				$code  = $tag['attributes']['code'];
				$val   = number_format($store->get_extra_cost($code,$trolley), 2);
				$size  = isset($tag['attributes']['size'])  ? $tag['attributes']['size']					: '';
				$class = isset($tag['attributes']['class']) ? ' class="'.$tag['attributes']['class'].'"'	: '';
				return text_box('products_extra['.$code.']', $val, $size, '', 'onFocus="javascript: this.blur();"'.$class);
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name);
		}#end switch

	}#end get_val()

	 #######################################################################################
	# print's the value for the passed name escaping as necessary
	function print_val(&$tag) {
		echo $this->_print_modified($tag, $this->get_val($tag['attributes']['name'], $tag));
	}

	 ###########################################################################
	# returns a string containing the JS fns needed for altering the trolley
	function _js_functions() {

		# I am doing this because I really dislike writing JS inside a string
		ob_start();

		# for each product in the trolley loop over and display
		$store   = &$this->get_ecommerce_store();
		$trolley = &$store->get_trolley();
		$productids  = $trolley->get_record_and_item_lists();
		$multipliers = $trolley->get_multipliers();
		$extra_cost_expressions = $store->get_extra_cost_js_exprs(0);

		global $SQUIZLIB_PATH;
		include_once("$SQUIZLIB_PATH/var_serialise/var_serialise.inc");
	?> 
		<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js','general.js');?>"></script>
		<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('js','debug.js');?>"></script>
		<script language="JavaScript" type="text/javascript" src="<?=squizlib_href('var_serialise', 'var_serialise.js');?>"></script>
		<script language="JavaScript" type="text/javascript">
			<!-- 
			var changes_made = false;

			var PRODUCTIDS = null;
			function ecommerce_store_trolley_get_productids() {
				if (PRODUCTIDS == null) {
					PRODUCTIDS = var_unserialise('<?=var_serialise($productids, true)?>');
				}
				return PRODUCTIDS;
			}// end ecommerce_store_trolley_get_productids()

			var	MULTIPLIERS = null;
			function ecommerce_store_trolley_get_multipliers() {
				if (MULTIPLIERS == null) {
					MULTIPLIERS = var_unserialise('<?=var_serialise($multipliers, true)?>');
				}
				return MULTIPLIERS;
			}// end ecommerce_store_trolley_get_multipliers()

			// This is actually an array of javascript assignment statements that will generate an value for an extra price, based on other data in the trolley
			var	EXTRA_COSTS_EXPRS = null;
			function ecommerce_store_trolley_get_extra_costs() {
				if (EXTRA_COSTS_EXPRS == null) {
					EXTRA_COSTS_EXPRS = var_unserialise('<?=var_serialise($extra_cost_expressions, true)?>');
				}
				return EXTRA_COSTS_EXPRS;
			}// end ecommerce_store_trolley_get_extra_costs()

			
			function ecommerce_store_trolley_change() {
				var form = document.ecommerce_store_update_trolley_form;
				var productids  = ecommerce_store_trolley_get_productids();
				var multipliers = ecommerce_store_trolley_get_multipliers();
				var extra_cost_exprs = ecommerce_store_trolley_get_extra_costs();

				var sub_total = 0;
				var extras = 0;
				var total;
				for(var productid in productids) {
					if (productids[productid] == null) continue;
					var variety_codes = productids[productid];
					for(var i in variety_codes) {
						if (variety_codes[i] == null) continue;
						var code = variety_codes[i];
						var cost = parseFloat(elementValue(form.elements['products[' + productid + ']["' + code + '"][cost]']));
						for(var multiplier in multipliers) {
							if (multipliers[multiplier] == null) continue;
							// make sure they entered a number
							var num = parseFloat(elementValue(form.elements['products[' + productid + ']["' + code + '"][multipliers][' + multiplier + ']']));
							if (isNaN(num)) num = 0;
							form.elements['products[' + productid + ']["' + code + '"][multipliers][' + multiplier + ']'].value = num;
							cost *= num;
						}// end for
						form.elements['products[' + productid + ']["' + code + '"][subtotal]'].value = number_format(cost, 2);
						sub_total += cost;
					}// end for
				}// end for
				form.elements['products_subtotal'].value = number_format(sub_total, 2);

				// Now the extra costs
				for(var extra_cost_code in extra_cost_exprs) {
					if (extra_cost_exprs[extra_cost_code] == null) continue;
					extra = '0.00';
					eval(extra_cost_exprs[extra_cost_code]); // PHP-generated formulas
					form.elements['products_extra['+extra_cost_code+']'].value = extra;
				}// end for

				total = sub_total + extras;
				form.elements['products_total'].value = number_format(total, 2);
	
				changes_made = true;

			}// end ecommerce_store_trolley_change()

			function ecommerce_store_trolley_update() {
				var form = document.ecommerce_store_update_trolley_form;
				if (changes_made) {
					form.action.value = 'update_trolley';
					form.submit(); 
					return true;
				} else {
					alert('No Changes Made');
				}//end if
				return false;
			}// end ecommerce_store_trolley_update()

			function ecommerce_store_trolley_delete() {
				var form = document.ecommerce_store_update_trolley_form;
				if (confirm('Are you sure you want to delete your trolley ?')) {
					form.action.value = 'delete_trolley';
					form.submit(); 
					return true;
				}//end if
				return false;
			}// end ecommerce_store_trolley_delete()

			function ecommerce_store_trolley_save_quote() {
				window.location = "<?=$trolley->get_href(false, 'save')?>";
			}// end ecommerce_store_trolley_save_quote()

			function ecommerce_store_trolley_show_quotes() {
				window.location = "<?=$trolley->get_href(false, 'show_archive')?>";
				return true;
			}// end ecommerce_store_trolley_show_quotes()

			function ecommerce_store_trolley_order() {
				var form = document.ecommerce_store_update_trolley_form;
				form.action.value = 'show_order_form';
				form.submit(); 
				return true;
			}// end ecommerce_store_trolley_order_button()

			// -->
		</script>
	<?
		$str = ob_get_contents();
		ob_end_clean();
		return $str;

	}#end _js_functions()

	 #####################################################
	# creates a from either the text or image
	function _create_button($button, $onclick) {

		$str = '';
		if ($image = $this->get_image_href($button.'_image')) {
			$str = '<a href="#" onClick="JavaScript: '.$onclick.'; return false;">'
					.'<img src="'.$image.'" border="0">'
					.'</a>';
		} else {
			 #########################################################################
			# NOTE: important use Site_Design_Area::get_val() otherwise we will
			# get into an infinite loop and seg fault the server
			$str = '<input type="button" value="'.Site_Design_Area::get_val($button.'_button').'" onclick="JavaScript: '.$onclick.'">';
		}#end if
		return $str;

	}#end _create_button()

}#end class 

?>
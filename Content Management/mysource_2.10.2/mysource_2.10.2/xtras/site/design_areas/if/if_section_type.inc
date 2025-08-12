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
## File: xtras/site/design_areas/if/if_section_type.inc
## Desc: handle's the individual if types that return true or false
## $Source: /home/cvsroot/xtras/site/design_areas/if/if_section_type.inc,v $
## $Revision: 1.5 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:10 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_If_Section_Type extends Site_Design_Area {

	var $type; # the section type, generated
	var $contents = array();


	function Site_Design_Area_If_Section_Type(&$_owner, $type) {

		$this->Site_Design_Base($_owner);
		
		$this->type = $type;
		$this->contents = array();
		

	}#end constructor

	  #########################################
	 # Converts the object into a nice string
	function pack() {

		# just make sure to kill the get val cache
		unset($this->_get_val_cache);
		return Site_Design_Area::pack();

	}#end packed()

	 #########################################
	# create the object from the tag
	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag, true);
		$this->_set_nested_areas($tag, '');

		 ############################################
		# process the contents of the element
		foreach($tag['contents'] as $index => $element) {

			# if we are dealing with a tag
			switch ($element['_type']) {

				case 'TAG' : 

					# if this is a sub level
					if ($element['attributes']['type'] || $element['attributes']['section']) {

						$contents_arr = array();
						$contents_arr['_type']   = 'SUB_SECTION';
						$contents_arr['_section'] = $element['attributes']['section'];
						$element['attributes']['type'] = $this->type;
					
						$if = &$this->get_if();
						# call create on it and return any additional attributes
						$additional_attributes = $if->create_section($element);

						# add the additional attributes returned by create()
						if (is_array($additional_attributes)) {
							$contents_arr = array_merge($contents_arr, $additional_attributes);
						}

						$this->contents[] = $contents_arr;

					# must be a print tag, we'll deal with it later, 
					# so just append to contents
					} else {

						$this->contents[] = $element;

					}#end if

				break;

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
 

	 ###################################################################
	# paint the passed pages, excluding some that are not needed
	function paint() {

		foreach($this->contents as $element) {

			switch($element['_type']) {

				# sub section, call paint on the if design area
				case 'SUB_SECTION' :
					global $XTRAS_PATH;
					include_once($XTRAS_PATH.'/conditions/'.$this->type.'/'.$this->type.'.inc');
					$condition = new $this->type();

					if ($condition->evaluate() && strtolower($element['_section']) == 'then') {
						$if = &$this->get_if();
						$if->paint_section($element);
					} else if (!$condition->evaluate() && strtolower($element['_section']) == 'else') {
						$if = &$this->get_if();
						$if->paint_section($element);
					}
				break;

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



	function &get_if() {
		# if our owner ain't the one, try our owner's owner
		if (strtolower(get_class($this->_owner)) != 'site_design_area_if') {
			if (method_exists($this->_owner, 'get_top_owner')) {
				return $this->_owner->get_if();
			}#end if
		}#end if
		return $this->_owner;

	}#end if
	
}#end class  

?>
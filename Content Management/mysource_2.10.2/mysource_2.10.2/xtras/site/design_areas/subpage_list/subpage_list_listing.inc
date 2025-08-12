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
## $Source: /home/cvsroot/xtras/site/design_areas/subpage_list/subpage_list_listing.inc,v $
## $Revision: 2.6 $
## $Author: csmith $
## $Date: 2004/01/19 03:12:58 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Subpage_List_Listing extends Site_Design_Area {

	function Site_Design_Area_Subpage_List_Listing(&$_owner) {
		$this->Site_Design_Base($_owner);
	}#end Site_Design_Area_Banner_Individual()

	function create(&$tag) {

		 ########################################
		# Set up any variables
		$this->_set_variables($tag);
		$this->_set_nested_areas($tag, 'subpage_list');

		 ############################################
		# process the contents of the element
		foreach($tag['contents'] as $index => $element){
			# if we are dealing with a tag
			switch ($element['_type']) {
			
				case 'TAG'  :        # must be a print tag, we'll deal with it later, 
				case 'DESIGN_AREA' : # dealt with above so just add to contents
				case 'HTML' :        # nothing to do to this yet, so just add to contents array
					$this->contents[] = $element;
				break;

				default :
					$this->_set_error("_type '$element[_type]' unknown", __FILE__, __LINE__);
					unset($this);
					return;

			}#end switch

		}#end foreach

	}#end create()
 
	function paint($pageid) { 

		foreach($this->contents as $element){
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
					switch($element['operation'])	{
						case 'print' : 
							$this->print_val($element,$pageid);
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

	  ##########################################################
	 # Prints the backend for the site administrator to allow 
	# them to customise their design 
	function print_user_backend($prefix_name='') {
	}#end print_user_backend()


	  ###############################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $pageid) {

		switch(strtolower($name)) {

			# set these 2 up as aliases, just for neatness
			case 'name' : 
			case 'description' : 
				return Site_Design_Area::get_val('page_'.$name,$pageid);
			break;

			# 
			case 'link' : 
				if (Site_Design_Area::get_val('anchor_to_parent')) {
					$page = &$this->get_page($pageid);
					if ($page->parentid) {
						if ($page->dirs[0]) {
							return Site_Design_Area::get_val('page_link', $page->parentid).'#'.$page->dirs[0];
						}
					}
				}

				# otherwise just return the page link
				return Site_Design_Area::get_val('page_link',$pageid);

			break;

			case 'thumb' : 
				$page = &$this->get_page($pageid);
				return $page->image_tag('thumbs/page_thumb',$this->get_val('page_name',$pageid),0,0,'border=0',$pageid,$page->siteid);
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name, $pageid);
		}#end switch

	}#end get_val()

	 #######################################################################################
	# print's the value for the passed name escaping as necessary
	function print_val(&$tag, &$pageid) {
		echo $this->_print_modified($tag, $this->get_val($tag['attributes']['name'], $pageid));
	}
}#end class Site_Design_Area_Results_FileResult
?>
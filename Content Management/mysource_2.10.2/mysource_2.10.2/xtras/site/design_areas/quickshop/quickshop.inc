<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This software Copyright Squiz.net 2001
## All Rights Reserved.
##
## $Source: /home/cvsroot/xtras/site/design_areas/quickshop/quickshop.inc,v $
## $Revision: 2.3 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:13 $
#######################################################################
global $INCLUDE_PATH;

include_once("$INCLUDE_PATH/site_design_area.inc");
include_once(dirname(__FILE__)."/trolley.inc");
include_once(dirname(__FILE__)."/trolley_contents.inc");
include_once(dirname(__FILE__)."/product_list.inc");
#---------------------------------------------------------------------#


class Site_Design_Area_QuickShop extends Site_Design_Area {

	var $sections;  # array holding the printable sections of the menu
					# Three sections :
					#    - trolley      -> trolley link
					#    - product_list -> prints out the list of product 
					#                      on a particular page
					#    - trolley_contents -> prints out the list of products 
					#                      that have been added to the basket


	function Site_Design_Area_QuickShop(&$_owner) {

		$this->Site_Design_Base($_owner);

		# init the vars
		$this->sections = Array();

	}#end Site_Design_Area_QuickShop()

	  ############################################################
	 # Reset the owner for this object, then reset the sections 
	# owner to this object 
	function reset_owner(&$owner) {

		for(reset($this->sections); 
			$key = key($this->sections); 
			next($this->sections)) {

			$this->sections[$key]->reset_owner($this);

		}#end for

		Site_Design_Base::reset_owner($owner);

	}#end reset_owner

	  ############################################################
	 # Unset the owner for this object and all the sections beneath it
	function &unset_owner() {

		for(reset($this->sections); 
			$key = key($this->sections); 
			next($this->sections)) {

			$this->sections[$key]->unset_owner();

		}#end for

		return Site_Design_Base::unset_owner();

	}#end unset_owner()

	 ############################################################
	# Creates a copy of this object and returns its reference
	function &copy() {

		$tmp = &Site_Design_Base::copy();
		for(reset($this->sections); 
			$key = key($this->sections); 
			next($this->sections)) {

			$tmp->sections[$key] = &$this->sections[$key]->copy();

		}#end for
		return $tmp;

	}#end copy()

	function create(&$tag) {

		# if this tag is a specific section
		if ($tag[attributes][section]) {


				$section_name = strtolower($tag[attributes][section]);
				# kill it because it's not needed anymore
				unset($tag[attributes][section]);

				$class_name = get_class($this)."_".$section_name;
				if (class_exists($class_name)) {
					$this->sections[$section_name] = new $class_name($this);
					$this->sections[$section_name]->create($tag);
				}
				else
				{
					$this->_set_error("Class '$class_name' unknown", __FILE__, __LINE__);

				}#end if

		# else must be a menu tag global to the type
		} else {

			# init all vars
			$this->_set_variables($tag, true);

		}#end if is a section

		# no additional attributes needed
		return;

	}#end create()

	function paint(&$tag_attributes) {
		$web_system = &get_web_system();
		$site = &$this->get_site();

        # run a security check to see if this extension has been allowed
        if (!in_array('quickshop',$site->allowed_extensions)) {
            return;
        }
        # if they want a section then, print it
		if ($tag_attributes[section]) {

			$tag_attributes[section] = strtolower($tag_attributes[section]);

			if ($this->sections[$tag_attributes[section]]) {
				$this->sections[$tag_attributes[section]]->paint();

			}#endif

		}#end if

	}#end paint()

}#end class Site_Design_Area_quickshop

?>
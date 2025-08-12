<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## File: xtras/site/design_areas/menu/type/original/original.inc
## Desc: handle's the normal/original menu system with allowing for 
##       fairly flexible menu design
## $Source: /home/cvsroot/xtras/site/design_areas/ecommerce_store/ecommerce_store.inc,v $
## $Revision: 1.3 $
## $Author: blair $
## $Date: 2002/04/26 02:43:05 $
#######################################################################
include_once(dirname(__FILE__)."/ecommerce_store_design_area.inc");
include_once(dirname(__FILE__)."/trolley_link.inc");
include_once(dirname(__FILE__)."/trolley_contents.inc");
include_once(dirname(__FILE__)."/product_list.inc");
#---------------------------------------------------------------------#

class Site_Design_Area_Ecommerce_Store extends Site_Design_Area_Ecommerce_Store_Design_Area {

	var $sections;  # array holding the printable sections of the menu
					# Three sections :
					#    - trolley      -> trolley link
					#    - product_list -> prints out the list of product 
					#                      on a particular page
					#    - trolley_contents -> prints out the list of products 
					#                      that have been added to the basket


	function Site_Design_Area_Ecommerce_Store(&$_owner) {

		$this->Site_Design_Base($_owner);

		# init the vars
		$this->sections = Array();

	}#end Site_Design_Area_Ecommerce_Store()

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
		if ($tag['attributes']['section']) {


				$section_name = strtolower($tag['attributes']['section']);
				# kill it because it's not needed anymore
				unset($tag['attributes']['section']);

				$class_name = get_class($this)."_".$section_name;
				if (class_exists($class_name)) {
					$this->sections[$section_name] = new $class_name($this);
					$this->sections[$section_name]->create($tag);

				} else {
					$this->_set_error("Class '$class_name' unknown", __FILE__, __LINE__);

				}#end if

		}#end if is a section

		# no additional attributes needed
		return;

	}#end create()

	function paint(&$tag_attributes) {
		$web_system = &get_web_system();
		$site = &$this->get_site();

        # run a security check to see if this extension has been allowed
        if (!in_array('ecommerce_store',$site->allowed_extensions)) {
            return;
        }
        # if they want a section then, print it
		if ($tag_attributes['section']) {

			$tag_attributes['section'] = strtolower($tag_attributes['section']);

			if ($this->sections[$tag_attributes['section']]) {
				$this->sections[$tag_attributes['section']]->paint();

			}#endif

		}#end if

	}#end paint()

	  ######################################################################
	 # this function returns values for the special variables
	# if it is not a predefined variable it uses the value of the variable in this object
	function get_val($name, $pageid) {
		global $action;

		switch(strtolower($name)) {

			case "message" :
				# for line in the message, loop and print
				global $SESSION;
				$msg = $SESSION->get_var("ecommerce_store_message");
				return nl2br($msg);
			break;

			# lets hope they declared it themselves
			default :
				return Site_Design_Area::get_val($name, $pageid);
		}#end switch

	}#end get_val()

}#end class 

?>
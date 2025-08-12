<?  ##############################################
   ### MySource ------------------------------###
  ##- Notitia   Module -------- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/web/extensions/notitia/notitia_object.inc,v $
## $Revision: 1.3 $
## $Author: achadszinow $
## $Date: 2003/10/29 03:18:28 $
#######################################################################

#---------------------------------------------------------------------#

 ##############################################################
# A parent class which provides a lot of handy function for
# objects in the notitia system
class Web_Extension_Notitia_Object extends WebObject {
	
	 #############
	# Constructor
	function Web_Extension_Notitia_Object() {
		WebObject::WebObject();
	}

	 ############################################
	# Returns a reference to the notitia system
	function &get_notitia_system() {
		return $this->web_system->get_extension('notitia');
	}

	 ##########################################
	# Returns a reference to a record object
	function &get_record($recordid) {
		$n = &$this->get_notitia_system();
		return $n->get_record($recordid);
	}

	 ##########################################
	# Returns a reference to an attribute object
	function &get_attribute($attributeid) {
		$n = &$this->get_notitia_system();
		return $n->get_attribute($attributeid);
	}

	 ##############################################
	# Returns a reference to a category tree array
	function &get_category_tree($siteid) {
		$n = &$this->get_notitia_system();
		return $n->get_category_tree($siteid);
	}

	 ##########################################
	# Returns a reference to an category object
	function &get_category($categoryid) {
		$n = &$this->get_notitia_system();
		return $n->get_category($categoryid);
	}

	 ##############################################
	# Returns a reference to a variety set object
	function &get_variety_set($variety_setid) {
		$n = &$this->get_notitia_system();
		return $n->get_variety_set($variety_setid);
	}

	/**
	* Gets a reference to an attribute type xtra
	*
	* @param int $attribute_xtraid The id of the xtra to get
	* @access public
	* @return &object
	*/
	function &get_attribute_type_xtra($attribute_xtraid) {
		$n = &$this->get_notitia_system();
		return $n->get_attribute_type_xtra($attribute_xtraid);
	}
}
?>
<?  ##############################################
   ### MySource ------------------------------###
  ##- Site Creator object ----- PHP4 ---------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## File: xtras/site/design_areas/menu/type/original/original.inc
## Desc: handle's the normal/original menu system with allowing for 
##       fairly flexible menu design
## $Source: /home/cvsroot/xtras/site/design_areas/ecommerce_store/ecommerce_store_design_area.inc,v $
## $Revision: 1.1 $
## $Author: blair $
## $Date: 2002/01/29 01:11:02 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Ecommerce_Store_Design_Area extends Site_Design_Area {

	function Site_Design_Area_Ecommerce_Store_Design_Area(&$_owner) {
		$this->Site_Design_Base($_owner);
	}#end Site_Design_Area_Ecommerce_Store_Design_Area()

	 ###################################################
	# Returns a reference to the site's ecommerce store
	function &get_ecommerce_store() {
		$site = &$this->get_site();
		return $site->get_extension("ecommerce_store");
	}

}#end class 

?>
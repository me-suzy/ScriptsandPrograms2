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
## File: xtras/site/design_areas/body/body.inc
## Desc: this class is used only to allow the body to be printed by 
##       the page template, to be called like a MySource_AREA tag
## $Source: /home/cvsroot/xtras/site/design_areas/body/body.inc,v $
## $Revision: 2.7 $
## $Author: bvial $
## $Date: 2003/04/02 03:51:02 $
#######################################################################
#---------------------------------------------------------------------#

class Site_Design_Area_Body extends Site_Design_Area {

	function Site_Design_Area_Body(&$_owner) {
		Site_Design_Area::Site_Design_Area($_owner);
	}#end Site_Design_Area_Body()

	function paint(&$tag_attributes) {
		global $SESSION;
		$site_design = &$this->get_top_owner();
		$painter     = &$site_design->get_painter();
		# just print the templates body function
		$type = $tag_attributes[type];
		if(method_exists($painter,'print_body')) {
			if ($type == 'printable' && $painter->is_dynamic()) {
				echo $SESSION->get_var('printable_version');
			} else if ($type == 'cacheable' && $painter->is_dynamic()) {
				ob_start();
				$painter->print_body();
				$cache = ob_get_contents();
				ob_end_flush();
				$SESSION->set_var('printable_version', &$cache);
			} else {
				$painter->print_body();
			}
		} 

	}#end paint()

}#end class Site_Design_Area_Body

?>
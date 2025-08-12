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
## $Source: /home/cvsroot/xtras/conditions/using_site_builder/using_site_builder.inc,v $
## $Revision: 1.3 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:23 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#



class Using_Site_Builder extends Condition {

	function Using_Site_Builder() {
	}#end constructor

	function evaluate() {

		# if the page template we are using
		# is the site builder, return true
		if (!$this->web_system) {
			$page = &$this->get_page();
		} else {
			$web_system = &$this->web_system;
			$page = &$web_system->get_page();
		}
		if (strpos(strtolower($page->template), 'site_builder') === false) return false;
		return true;

	}#end evaluate()

}#end class Using_Site_Builder

?>
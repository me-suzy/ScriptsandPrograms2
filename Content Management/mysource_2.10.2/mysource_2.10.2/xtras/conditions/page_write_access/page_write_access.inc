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
## $Source: /home/cvsroot/xtras/conditions/page_write_access/page_write_access.inc,v $
## $Revision: 1.2 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:23 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#


class Page_Write_Access extends Condition {

	function Page_Write_Access() {
	}#end constructor

	function evaluate() {

		# if we are not logged in, then they cant be an admin or editor
		$session = &get_mysource_session();
		$user = $session->user;
		if (!$user->id) return false;

		$web_system = &$this->get_web_system();
		$site = &$web_system->get_site();
		$page = &$site->get_page();
		
		# so they exist, but are they an admin?
		if ($page->admin_access()) return true;

		# so they exist and are not an admin, but are they an editor?
		if ($page->editor_access()) return true;

		# they are a nobody
		return false;

	}#end evaluate()

}#end class Site_Write_Access

?>
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
## Desc: returns true if the user is an administrator for this site
## $Source: /home/cvsroot/xtras/conditions/page_admin/page_admin.inc,v $
## $Revision: 1.2 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:23 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#



class Page_Admin extends Condition {

	function Page_Admin() {
	}#end constructor

	function evaluate() {

		# if we are not logged in, then they cant be an admin
		$session = &get_mysource_session();
		$user = $session->user;
		if (!$user->id) return false;

		# so they exist, but are they an admin?
		$web_system = &$this->get_web_system();
		$site = &$web_system->get_site();
		$page = &$site->get_page();
	
		if ($page->admin_access()) return true;
		return false;

	}#end evaluate()

}#end class Site_Admin

?>
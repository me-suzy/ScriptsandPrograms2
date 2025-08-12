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
## $Source: /home/cvsroot/xtras/conditions/logged_in/logged_in.inc,v $
## $Revision: 1.3 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:22 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#

class Logged_In extends Condition {

	function Logged_In() {
	}#end constructor

	function evaluate() {
		$session = &get_mysource_session();
		$user = $session->user;
		if (!$user->id) return false;
		return true;
	}#end evaluate()

}#end class Logged_In

?>
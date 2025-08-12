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
## $Source: /home/cvsroot/xtras/conditions/copyright_check/copyright_check.inc,v $
## $Revision: 1.3 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:22 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/condition.inc");
#---------------------------------------------------------------------#

class Copyright_Check extends Condition {

	function Copyright_Check() {
	}#end constructor

	function evaluate() {

		# Check if the copyright has been accepted
		# or if the form has been submitted to accept it.
		$session = &get_mysource_session();
		$decision = $_POST['action'];
		if ($decision == 'Accept') $session->set_var('Copyright_Check_Accept', &$decision);
		$accept_copyright = &$session->get_var('Copyright_Check_Accept');
		if ($accept_copyright) return true;
		return false;

	}#end evaluate()

}#end class Copyright_Check

?>

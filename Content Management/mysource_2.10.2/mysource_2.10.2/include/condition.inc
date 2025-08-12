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
## $Source: /home/cvsroot/mysource/include/condition.inc,v $
## $Revision: 2.3 $
## $Author: csmith $
## $Date: 2003/09/17 04:36:50 $
#######################################################################
global $INCLUDE_PATH;
include_once("$INCLUDE_PATH/systemobject.inc");
#---------------------------------------------------------------------#


class Condition extends SystemObject {

	function Condition() {
	}#end constructor

	function evaluate() {
		return false;
	}#end evaluate()

}#end class Condition

?>
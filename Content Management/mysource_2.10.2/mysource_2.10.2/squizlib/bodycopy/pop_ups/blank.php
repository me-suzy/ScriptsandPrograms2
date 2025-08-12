<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Bodycopy Editor ---- PHP4 --------------##
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
## $Source: /home/cvsroot/squizlib/bodycopy/pop_ups/blank.php,v $
## $Revision: 2.0 $
## $Author: agland $
## $Date: 2001/12/18 06:03:02 $
#######################################################################
include(dirname(__FILE__)."/header.php");
?>
<script language="JavaScript">
	function popup_init() {
		// do nothing, just here so that we overright the popup_init() fn 
		// that may have been set by a previous pop-up
		// needed for Netscape
	}// end popup_init()
</script>
&nbsp;
<?
include(dirname(__FILE__)."/footer.php");
?>
<?  ##############################################
   ### MySource ------------------------------###
  ##- Backend BodyCopy Pipe File - PHP4 ------##
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
## Desc: Pipes files through from the bodycopy section of the squizlib
## $Source: /home/cvsroot/mysource/web/edit/bodycopy.php,v $
## $Revision: 2.1 $
## $Author: gsherwood $
## $Date: 2003/02/27 03:34:23 $
#######################################################################
# Initialise
include_once("../init.php");
# call get_websystem to include web.inc
$web = &get_web_system();
include_once("$SQUIZLIB_PATH/bodycopy/bodycopy.inc");
#---------------------------------------------------------------------#

BodyCopy::external_file($_GET['bodycopy_file_action'], $_GET['bodycopy_file'], $_GET['bodycopy_file_href'], $_GET['bodycopy_stylesheet']);

?>
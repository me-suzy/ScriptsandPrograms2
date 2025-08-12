<?  ##############################################
   ### MySource ------------------------------###
  ##- Web initialisation -- PHP4 -------------##
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
## Desc: Includes init.php, but changes corrects some vars because 
##       the PHP script that we are calling from in under squizlib
##       not web or web/edit
## $Source: /home/cvsroot/mysource/web/squizlib_init.php,v $
## $Revision: 2.1 $
## $Author: blair $
## $Date: 2002/03/27 08:46:06 $
#######################################################################
# This file is intended to be included before any other processing
#

include_once(dirname(__FILE__).'/init.php');

 ########################################################################
# Alter the BASE_DIR becaause we are coming from somewhere from within 
# the squizlib directories rather than the web directory
$BASE_DIR = substr("./../".str_repeat("../", substr_count(substr($THIS_PATH,strlen($SQUIZLIB_PATH)), "/")), 0, -1); 
$EDIT_DIR = $BASE_DIR.'/'.$SYSTEM_CONFIG->backend_suffix;

?>

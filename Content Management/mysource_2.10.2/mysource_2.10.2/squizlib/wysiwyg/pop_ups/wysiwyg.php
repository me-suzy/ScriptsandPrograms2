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
## $Source: /home/cvsroot/squizlib/wysiwyg/pop_ups/wysiwyg.php,v $
## $Revision: 1.1 $
## $Author: blair $
## $Date: 2002/03/27 08:42:33 $
#######################################################################
include_once(dirname(__FILE__).'/../../../web/squizlib_init.php');
$web = &get_web_system();
include_once(dirname(__FILE__).'/../wysiwyg.inc');

$wysiwyg = new wysiwyg($_GET['name']);
$wysiwyg->paint_popup();

?>

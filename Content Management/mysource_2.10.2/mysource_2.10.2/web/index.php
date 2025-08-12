<?  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Index file -- PHP4 ------------##
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
## File: web/index.php
## Desc: Index file that handles all frontend transactions.
## $Source: /home/cvsroot/mysource/web/index.php,v $
## $Revision: 2.4.4.1 $
## $Author: achadszinow $
## $Date: 2004/05/14 00:05:14 $
#######################################################################
# Initialise
include_once('init.php');
#---------------------------------------------------------------------#

# Let MySource hangle any special actions
if($_REQUEST['mysource_action']) process_mysource_action($_REQUEST['mysource_action']);

 #########################################################################
# And now, the complex algorithm for displaying the MySource web frontend !
$web = &get_web_system();
$web->print_frontend();
?>
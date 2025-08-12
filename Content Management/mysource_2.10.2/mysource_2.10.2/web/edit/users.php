<?  ##############################################
   ### MySource ------------------------------###
  ##- Backend Edit file -- PHP4 --------------##
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
## File: web/edit/web.php
## Desc: Managing the web system from a top level.. crating sites etc
## $Source: /home/cvsroot/mysource/web/edit/users.php,v $
## $Revision: 2.0 $
## $Author: agland $
## $Date: 2001/12/18 06:03:09 $
#######################################################################
# Initialise
include_once("../init.php");
#---------------------------------------------------------------------#
$USERS = &get_users_system();
$USERS->print_backend();
?>
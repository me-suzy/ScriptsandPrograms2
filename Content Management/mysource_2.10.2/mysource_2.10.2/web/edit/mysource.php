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
## File: web/edit/mysource.php
## Desc: Managing the configuration files from a top level.. 
## $Source: /home/cvsroot/mysource/web/edit/mysource.php,v $
## $Revision: 2.0 $
## $Author: agland $
## $Date: 2001/12/18 06:03:07 $
#######################################################################
include_once("../init.php");
include_once("$INCLUDE_PATH/mysource.inc");
#---------------------------------------------------------------------#

$MYSOURCE = new Mysource();
$MYSOURCE->print_backend();
?>

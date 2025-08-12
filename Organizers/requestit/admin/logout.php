<?PHP
##########################################################################  
#                                                                        #
# Request It : Song Request System                                       #
# Version: 1.0b                                                          #
# Copyright (c) 2005 by Jonathan Bradley (jonathan@xbaseonline.com)      #   
# http://requestit.xbaseonline.com                                       #         
#                                                                        #
# This program is free software. You can redistribute it and/or modify   #
# it under the terms of the GNU General Public License as published by   #
# the Free Software Foundation; either version 2 of the License.         #
#                                                                        #
##########################################################################
?>
<?php session_start(); ?>
<?php ob_start(); ?>
<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 
header("Cache-Control: post-check=0, pre-check=0", false); 
header("Pragma: no-cache"); // HTTP/1.0 
?>
<?php
@session_unset();
@session_destroy();
header("Location: ../list.php");
exit();
?>

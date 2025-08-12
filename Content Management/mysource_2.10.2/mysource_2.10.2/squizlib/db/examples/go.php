<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Generic Include Files -- PHP4 ----------##
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
## File: dev/examples/go.php
## Desc: Example usage dev functions
## $Source: /home/cvsroot/squizlib/db/examples/go.php,v $
## $Revision: 2.0 $
## $Author: agland $
## $Date: 2001/12/18 06:03:04 $
#######################################################################

include("../dev.inc");

# array_contents()
#
# This function takes an array (or and object) and prints out
# everything inside it. This is recursive, so it handles arrays
# insides objects inside arrays inside arrays inside objects
# inside arrays.

$blah = array("name"=>"Joe","age"=>52,favourite_numbers=>array(6,7,3,234,7542,32));

echo "<pre>";

echo array_contents($blah);

?>

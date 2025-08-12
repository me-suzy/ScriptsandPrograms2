<?php
#########################################################
# Random Popup                                          #
#########################################################
#                                                       #
# Author: Doni Ronquillo                                #
#                                                       #
# This script and all included functions, images,       #
# and documentation are copyright 2003                  #
# free-php.net (http://free-php.net) unless             #
# otherwise stated in the module.                       #
#                                                       #
# Any copying, distribution, modification with          #
# intent to distribute as new code will result          #
# in immediate loss of your rights to use this          #
# program as well as possible legal action.             #
#                                                       #
#########################################################

include('inc/config.php');
include('inc/header.php');

    $url = $_REQUEST['url'];
    
    $sql = "DELETE from url where url='$url'";
	$result = mysql_query($sql);

	echo "<b>$url successfully deleted</b><br><br>";


include('inc/footer.php');

mysql_close($con);

?>
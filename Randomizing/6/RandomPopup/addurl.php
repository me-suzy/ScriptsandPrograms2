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

	if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $url = $_POST["url"];

		$sql = "INSERT INTO url (url) VALUES ('$url')";
		$result = mysql_query($sql);

		echo "<b>$url successfully added to popup rotation</b><br><br>";

	} else {

		include('inc/form.php');

	}

	include('inc/footer.php');

    mysql_close($con);

?>
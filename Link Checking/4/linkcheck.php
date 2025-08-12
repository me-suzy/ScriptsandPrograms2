<?php

require('lcfunction.php'); /* The function that actually does all the work */

/* Grab parameters from post or get methods then pass the params to the isLinking function. */
if(!(!isset($_POST['check']) || !isset($_POST['for'])) & !(!isset($_GET['check']) || !isset($_GET['for']))){
	echo "Not enough parameters. First param is site to search. Second param is your website";
}else{
	if(!isset($_GET['check'])){
		$url = $_POST['check']; 
		$for = $_POST['for'];
	}else{
		$url = $_GET['check']; 
		$for = $_GET['for'];
	}

	if(isLinking($url, $for)){
		echo "Link Found"; /* Place code you want for when link is found */
	}
	else{
		echo "Could not find link."; /* Place code you want for link is NOT found */
   	}
}
?>
<?php 

	//************************************************************************************
	// This include goes in the /PHPJK/SuggestCategory/index.php page
	//************************************************************************************
	
	// This is the email that is sent to the administrator when a user suggests a new gallery category
	// 		the 1: is replaced with the suggested category name
	//		the 2: is replaced with the suggested category description
	//		the 3: is replaced with the current domain name (eg: "www.phpjk.com")
	//		the \n should appear as a line break in the email
	$CONF_SuggestCat = "Suggested category.\n";
	$CONF_SuggestCat .= "The category being suggested is called: \n\"1:\"\n\n";
	$CONF_SuggestCat .= "And the description is: \n\"2:\"\n\n- 3: Image Gallery";
?>
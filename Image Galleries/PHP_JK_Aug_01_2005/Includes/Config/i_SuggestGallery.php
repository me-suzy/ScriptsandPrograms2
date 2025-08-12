<?php 

	//************************************************************************************
	// This include goes in the /PHPJK/SuggestGallery/index.php page
	//************************************************************************************
	
	// This is the email that is sent to the administrator when a user suggests a new gallery
	// 		the 1: is replaced with the suggested gallery name
	//		the 2: is replaced with the suggested gallery description
	//		the 3: is replaced with the current domain name (eg: "www.phpjk.com")
	//		the \n should appear as a line break in the email
	$CONF_SuggestGal = "Suggested gallery.\n";
	$CONF_SuggestGal .= "The gallery being suggested is called: \n\"1:\"\n\n";
	$CONF_SuggestGal .= "And the description is: \n\"2:\"\n\n- 3: Image Gallery";
?>
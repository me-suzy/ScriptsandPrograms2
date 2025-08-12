<?php 

	//************************************************************************************
	// This include goes in the /Includes/i_Administration.php page
	//************************************************************************************
	
	// This is the email that is sent to the user when a new image is added to any gallery they
	//	are subscribed to
	// 		the 1: is replaced with the gallery name
	//		the 2: is replaced with the current domain name (eg: "www.phpjk.com")
	//		the 3: is replaced with the gallery id number (GalleryUnq)
	//		the 4: is replaced with the category id number (CategoryUnq)
	//		the \n should appear as a line break in the email
	$CONF_NewGalImages = "New images have been added to the gallery \"1:\", you subscribed to!\n\n";
	$CONF_NewGalImages .= "<a href='2:/ThumbnailView.asp?iGalleryUnq=3:&iCategoryUnq=4:'>Click here</a>";
	$CONF_NewGalImages .= " or copy and paste this URL into your browser to go to the gallery:\n";
	$CONF_NewGalImages .= "2:/ThumbnailView.asp?iGalleryUnq=3:&iCategoryUnq=4:\n\n";
	$CONF_NewGalImages .= "No additional email messages will be sent to you until you visit the website";
	$CONF_NewGalImages .= " (this is to limit the number of emails sent to you).";
	$CONF_NewGalImages .= " To unsubscribe, go to the gallery you have subscribed to and click the \"Unsubscribe Gallery\" button.";
	
	
	// This is the email that is sent to the user when a new image is added to any category they
	//	are subscribed to
	// 		the 1: is replaced with the category name
	//		the 2: is replaced with the current domain name (eg: "www.phpjk.com")
	//		the 3: is replaced with the category id number (GalleryUnq)
	//		the \n should appear as a line break in the email
	$CONF_NewCatImages = "New images have been added to the category \"1:\", you subscribed to!\n\n";
	$CONF_NewCatImages .= "<a href='2:/G_Display.asp?iCategoryUnq=3:'>Click here</a>";
	$CONF_NewCatImages .= " or copy and paste this URL into your browser to go to the category:\n";
	$CONF_NewCatImages .= "2:/G_Display.asp?iCategoryUnq=3:\n\n";
	$CONF_NewCatImages .= "No additional email messages will be sent to you until you visit the website";
	$CONF_NewCatImages .= " (this is to limit the number of emails sent to you).";
	$CONF_NewCatImages .= " To unsubscribe, go to the category you have subscribed to and click the \"Unsubscribe Category\" button.";
?>
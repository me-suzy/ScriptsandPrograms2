<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3b
  $Date: 2005/11/25 00:38 $
**********************************************/

/* Configuration v1.3 - Please use linewrap to see the documentation of each parameter 
or go to the website. The parameters are now nicely grouped */
/*
		Settings you should adapt to your website. The title "Welcome to the Tiny Web Gallery" is language
		dependant and stored in the language files - Please change it too!
*/
$default_language = "en"; // The gallery is started with this language if no language from the browser can be read. This language file has to exist!  To add a new language you have to translate one of the existing language file (e. g. language/language_de.php - the name of the needed flag is language/language_de.gif) and copy it in the language directory (+ the flag).
$titelpasswort = "test"; // The password used for entering titles, delete comments, rotate images permanently and send user notifications!
$privatepasswort = "test"; // = "test" - To protect a gallery with a password you have to create an empty file with the name 'private.txt' in the directory you want to protect. If you want to protect a gallery with a different password you have to enter the password in the 'private.txt' file.
$enable_external_privategal_login=false; // new 1.2 - enables/disables to login a private gallery with a password - the password has to be added as parameter twg_private_login=<password>. The password is the plain password! - when the administration is available I will add a kind of encryption.  
$adminpasswort = "not used"; // new 1.2 - This is the password for the administration plug-in where the config.php can be changed! - is not available yet!
$browser_title_prefix="TinyWebGallery"; // new 1.2 -  This is the title which is shown in the browser title - you may change this to the name of your gallery 
$default_gallery_title="Welcome to the TinyWebGallery"; // new 1.3 This is the default title shown on the main page if no real $lang_titel is specified in the language file. If you want to have different titles for a language please adapt the language files!
$encrypt_passwords=false; // new 1.2 - enable/disable encryption of passwords! - if you change this to true you have to generate your passwords with the provided password.php. Sha256 is used for the encryption. Passwords titelpasswort and adminpasswort are NOT encrypted!! only privatepasswort!! see password_file and the how-to for more info!! TWG has a password.php where you can generate your passwords! 
  $use_sha1_for_password=true;  //new 1.2 - you can use SHA1 or SHA256 to generate the hash values of the passwords. true = SHA1, false=SHA256. If SHA1 is not available on your system (php <4.3) the internal SHA256 is used! TWG has a password.php where you can generate your passwords! 
$metatags=""; // new 1.3 - You can add the content of an individual metatag here. separte the entries with an ',' . Makes your gallery better to find in the web - metatags are only generated if $php_include=false

/*
		Here you can adapt all the directories - normally there is no need to change something here
		Make sure to set the permissions correctly (if you want to include the index.php read the description behind $install_dir)
*/		
$php_include=false;        // new in 1.2 - This has to be set to true if you include (with include ....) - see the settings below !!  
  $install_dir = ""; 			// new 1.2 - This is ONLY needed if you include (with include ....) twg with php into an existing php page and twg is in a subdirectory! you have to enter the path from your including page to the twg installation  e.g. "TinyWebGallery/". The / at the end is needed! 
  $ignore_parameter  = array('file'); // new 1.2 - Some parameters are not wanted to be added to all twg links - (e.g. cmsphp - file parameter) - make an array with the parameters here !
	$include_y_bottom=0;    // not used yet ! - will be implemented sometimes if someone needs it - will be used if twg is included in a php page and something is below this offset is the height of this bottom part - this can be calculated but most browsers do his wrong ;).
  $disable_frame_adjustment_ie=false; // new 1.3 - ie has a bug in i_frame adjustment! - if you integrate twg and the i_frames are all right in ff but totally rearranged in ie set this to true!   
$basedir = "pictures"; 		// The directory where the directories with the images has to be copied. The path has to be relative no absolute paths are allowed here!
$cachedir = "cache"; 			// The directory where all generated images are cached. This directory has to be made read- and writeable on the web server. The path has to be relative no absolute paths are allowed here!
$counterdir = "counter"; 	// The directory where all counter stuff is stored. This directory has to be made read- and writeable on the web server. The path has to be relative no absolute paths are allowed here!
$xmldir = "xml";					// The directory where all image titles and comments is stored. This directory has to be made read- and writeable on the web server. The path has to be relative no absolute paths are allowed here!

/* 
		Skin Settings
		This are the settings to activate a skin - this are the default settings for the gallery.
		A skin does most of the time overwrite the next settings! A skin can also set non visual
		settings to - but it is not recommended to do this! Read the Skins howto if you want to 
		share your gallery layout with others.
*/
$skin=""; // as default no skin is used - in the download  are "black","green","transparent","winter" and "newyork"  . All other style settings are still valid (check howto 9). Some of the skin have a background! Check the Skins howto to create your own skin or look in the forum of TWG - there is a skins section. if you change the skin you have to delete the *.slide.jpg images in the cache folder!
$background_default_image=$install_dir . ""; // normally you put the background in the stylesheet! but if you want to use a dynamic background the image has to be here - skins overwrite this setting if they have a background image   
$use_dynamic_background=true; // If you want a dynamic background that resizes with the browser size you have to set this to true and set a $background_default_image - skins overwrite this setting if they have a background image   	 
  $resize_only_if_too_small=false; // if you have this to true the image is not made smaller than the original its only made bigger - if false it is resized all the time!
$slideshow_backcolor_R = 255;  // for the slideshow are images created which are $small_pic_size x $small_pic_size
$slideshow_backcolor_G = 255;  // therefore we need a background color that has to match the color in the style sheet (see the comment there)
$slideshow_backcolor_B = 255;  // default is white - the values are the RGB values in decimals!
$comment_corner_size=5; // new 1.1 - when an image has a comment the right upper corner is make white by default. This value determines the size of this corner
	$comment_corner_backcolor_R = 255; // new 1.2 this are the colors of the comment corner (RGB value in decimal)
	$comment_corner_backcolor_G = 255;
	$comment_corner_backcolor_B = 255;
$enable_drop_shadow=true; // new 1.3 - you can enable/disable the default border of the image - there is a drop shadow defined in style.css -> div.twg_img-shadow - This looks very good if you have white backgrounds if not - don't use it ;)
	

/* 
  	Here you can set image sizes, number of images displayed on each page ... 
*/
$menu_x = 3; // Number of galleries which are shown in a row on the overview page.
$menu_y = 3; // Number of rows on the overview page.
$hidemenuborder = false; // new 1.3c - Shows or hides the menu border - is not done in the sylesheet because the style is used more often
$autodetect_maximum_thumbnails=true; // new 1.2 - twg tries to display as much thumbnails as possible without creating scrollbars - is turned off in low bandwidth mode!
  $thumbnails_x = 6; // Number of images in a row on the thumbnail page.
  $thumbnails_y = 4; // Number of images in a column on the thumbnail page.
$number_top10 = ($thumbnails_x * ($thumbnails_y -2)) + 1; // Number of images that are shown in the top views page. The existing calculation (13) works nice with the existing layout; The last row of the top x is alwys filled - therefore more then number_top10 images can be shown! 
$small_pic_size = 400; // max pic size - please read the description of $use_small_pic_size_as_height (at the end) before you set this!
	$resize_only_if_too_big=false; // new 1.3 - If images are equal or smaller they are not resized if true. You can save disk space if you set this to true and resize all pictures with an external program before uploading. watermarks are not generated on his images becasuse the are not touched at all! if you need them - please insert them by yourself because you wanted to keep the quality of the images. 
	$use_small_pic_size_as_height=true; // new 1.1 - use small_pic_size as height! - the small pic size restricts the picture to a maximum height and width of small_pic_size - therefore vertical and horizontal images have the same maximum side length. If you set this switch to true the size is used as maximum height. vertical images are then smaller then horizontal - but when you watch the images the navigation does not jump to the bottom if a vertical image is coming - If you use this please decrease the picture size by ~1/3 to get the vertical images in the same size as before (and delete the cache!!). The cross fade slideshow does appear smaller because the horizontal images are here still as big as the vertical ones. 
  	$maxXSize = 800; // new 1.2 - If you have panorama images you can restrict the maximum width of an image - $use_small_pic_size_as_height has to be true that this restriction is needed! A panorama is assumed if the width/height > 1.5! 
$thumb_pic_size = 120; // the thumbnail size - check the $show_clipped_images description. If this is set true the thumbnails appear bigger
$menu_pic_size_x = 100;  // size of the gallery overview pictures -  has to be dividable by 2 if using $show_colage=true; if $show_colage=false; please use the same scale as the pictures have to get the nicest results.
$menu_pic_size_y = 100;
$compression = 75; // quality of the generated jpegs - best = 100 - but biggest size !!
$compression_thumb = 80; // new 1.3 - quality of the generated thumbnail jpegs - best = 100 - but biggest size !! I make them better quality because they are really small
$numberofpics=5;   // new 1.3 - number of pictures that are displayed in the thumbnail strip off the image page - only 3,5,7 and 9 are tested - more does not make sense I would say :) !! The number has to be uneven!
$show_clipped_images=true;  // new 1.1 - clipped images in the thumbnail view - all images will be shown as squares - if you change this - delete all thumbnails in the cache!!! The size of the images (x and y) will be $thumb_pic_size! remember - all thumbnails are squares then on the detail page you cannot see if a image is horizontal or vertical. remove all thumbnails from the cache folder after changing this!  
	$center_clipped_images_horizontal=true; // new 1.3 - center clipped images horizontal if true
	$center_clipped_images_vertical=false;  // new 1.3 - center clipped images vertical if true 



/* 
		In this section you can enable or disable main features of TWG 
*/
$show_comments = true; // enable comments and shows them below the pictures !! - if you set this to false make sure that the $topx_default does not point to comments
  $show_enter_comment_at_bottom=false; // new 1.2 - shows the comment link additionally below the picture! - if you set this to true you should maybe $show_comments_in_layer set to false !
  $show_number_of_comments=true; // new 1.3 - Show the number of entered comments next to the comment text 
  $show_comments_in_layer=true;  // new 1.3 - Show the comments in a big layer instead below image - makes a nicer layout! 
  	$height_of_comment_layer=250;// new 1.3 - The additional height of the layer where the comments are shown. 
$show_number_of_pic = true; // show the number of images in a gallery in the overview.
$show_count_views = true; // shows the views counter in the right corner in the details view - this is quite slow because it has to read and write the counterxml file every time. (This is still not multithreading safe - but good enough for the gallery!)
$show_login = true; // new 1.2 - enables/disables the login button in the right upper corner
$show_optionen = true; // new 1.2 - enables/disables the options button in the right upper corner on the details page
$show_new_window = true; // true; // new 1.2 - enables/disables the "new window" button in the right upper corner and in the options pane. 
  $new_window_x="auto"; // new 1.1 - the size of the new window - this setting work nice for the actual settings - please change them if you change the image sizes
  $new_window_y="auto"; // new 1.2 if you enter "auto" at this point for both values it is resized to the maximum your screen can do ;) 
$enable_download = true; // enable download of original files
  $enable_download_counter=true; // new 1.3 - Enables the counting of download of an image - $enable_direct_download has to be set to false  
  $enable_direct_download=false; // new v1.1 - does only work with $enable_download=true - you can select if the original images are linked directly or if a call  goes to a php page which returns the image. true:  shows the image in the browser and it's easy for someone to go to your image directory and browse even into protected folders - please don't use this if you have protected galleries false: shows a download window where you can save the image. This is much saver but people with slow connections have  sometimes problems downloading properly (reported by some users - therefore I added the direct download)  - please test on your system if the recommended "false" setting work! Attention: if your filename does contains characters like äöü... you get a "you don't have permissions" warning on my windows system please don't use this characters if you using direct download - thanks
	$open_download_in_browser=true; // new v1.1 - Opens the original file as download or in the browser: true: in the browser; false as download
    $open_download_in_new_window=true; // new 1.3 - should be true if open in browser is true because the dhtml jumps back to the initial image and not to he last!
$show_rotation_buttons=true; // new 1.1 -  show the rotation buttons  ; true - shows them; false - hides them - if the rotation function cannot be defected by function_exists the rotation buttons are not shown at all! 
$show_big_left_right_buttons=false; // new 1.1 - shows the left - right buttons in the HTML navigation (they are never shown at the DHTML navigation!)
$enable_counter=true; // new 1.2 - enable/disable the counter in the left lower corner
  $show_counter=true; // new 1.2 - show the counter - if not shown the counter is still counting!
    $show_today_counter = true; // new 1.2 - show the counter of today or only the overall counter
  $enable_counter_details=true; // new 1.2 - enable/disable the detail popup when you move over the user counter
    $enable_counter_details_by_mouseover=true; // new 1.2 - If true the counter history does popup by moving over the counter, If false is by clicking on the counter!
$show_help_link=true; // new 1.2 - Shows the help link
$show_first_last_buttons=true; // shows the first and last buttons on the details page in the upper navigation
$enable_dir_description_on_image=false; // new 1.2 - shows/hides a directory description on the image page if existing. you can use the image.txt as well - see the howto's!  
$show_translator=false; // new 1.2 - shows/hides the name of the translator if he/she was specified in the language file.
$show_image_rating=true;       // new 1.3 - Enables the rating of images 
	$show_rating_security_image=true;  // new 1.3 - enables an additional page where you have to enter a 4 digit security number that is shown on an image - used to prevent robots to vote! 
	$image_rating_position="over_image";// new 1.3 - Position of voting. Valid entries:  menu, over_image, below_navigation
$enable_download_as_zip=true;	  // new 1.3 - you can enable that whole dirs can be downloaded if a zip is provided - see the howto 
$show_enhanced_file_infos=true; // new 1.3 - Shows the "Info" of an image in the menu
  $show_download_counter=true;  // new 1.3 - Shows the download counter in the info box.
  $show_exif_info=true; // New 1.3 - Shows the "Exif Info" of an image in the iframe
$show_slideshow=true;						// new 1.3 - Enables / disables the slideshow functionality of TWG
  $show_optimized_slideshow=true; // new 1.3 - Shows/hides the optimized slideshow option in the options menu - if true: $twg_slide_type should not be 'TRUE'! 
  $show_maximized_slideshow=true; // new 1.3 - Shows/hides the maximized slidshow option in the options menu - if true: $twg_slide_type should not be 'FULL'! 
$show_topx=($show_comments || show_image_rating || $enable_download_counter || $show_count_views); // before show_count_views was used but now we have much more view! - be sure to enable at least one of the topx things (views, downloads, rating, comments!)
	$show_topx_comments_details = true; // new 1.3 -  shows the latest comment next to the image - false - view like the other one !
  $topx_default="views"; // new 1.3 - "views","comments","dl","votes","average"; 
$enable_maximized_view=true; // new 1.3 - = fullscreen modus - This modus is intended for people who want to show their images like in a slideshow but with manual navigation. The images are not cached in this modus because they can get very big! If php_include is true this modus is disabled because the detection dows not work properly!  
  $default_is_fullscreen=false; // new 1.3 - default is started in full screen - Should be false as default and only be set to true with the options
  $show_warning_message_at_maximized_view=true; // new 1.2 - enable/disable a JavaScript warning that tells the user that switching to the maximized view is quite slow ;).
	$show_caption_at_maximized_view=true; // new 1.3 - show the caption in full screen modus at the bottom 
$show_search=true;  // new 1.3 - shows the search
  $preselect_caption_search=true; // new 1.3 - Preselect the caption checkbox in the search window
  $preselect_comments_search=true; // new 1.3 - Preselect the comments checkbox in the search window
  $preselect_filenames_search=true; // new 1.3 - Preselect the file names checkbox in the search window
  $show_topx_search_details = false; // new 1.3 - If you want a more detailed view for the search set this parameter to true - If you want more thumbnails and less text use false here! If you include TWG with php_include please use true here! 
/* 
		In this section you can customize/setup the  emails features of TWG 
*/
$enable_email_sending=false; // new 1.2 - You can enable/disable the sending of emails - The user can still register but no notification emails are sent. The admin side can still be used but no email are sent - set this to false if you are testing twg and you don't want to send any emails!
$show_email_notification = false; // new 1.2 - enable/disables the end-user and the admin part if logged in :). The emails are stored in the xml/subscribers.xml (plain text file!)
  $encrypt_emails=true; // new 1.2 - enable/disable encryption of emails! - if you change this your subscriber.xml file will become invalid! - you are not able to fix this file manually if you turn the encryption to true. if you set this to false make sure that the file can not be read from outside - set the xml directory to 770! 
  $encrypt_emails_key="This is the encryption key used for emails in TWG - to make your emails really save please change this string with your random string like 2342dlkASdasDkw33jl2k4jl... - the longer the better."; // new in 1.2 - this key is used to encrypt and decrypt the emails in the subscriber.xml - the longer the saver! - (please use 1-9, a-z or A-Z to make the algorithm work properly! - no e.g. ÄÖÜ are allowed!) - internal the key is permuttated 2 times and added to this string to add additional security ;). If you change this string all existing emails cannot be used anymore!
  $youremail = "test@test.com"; // new 1.2 - this email, will be the reply-to mail for the registration !!
  $default_subject = "Gallery update!"; // new 1.2 - This is the default subject for the emails which can be send to the registered users.
  $default_text = "Hello,\nThere are new images available at the web gallery you registered.\nPlease go to %s"; // new 1.2 - The default email body. %s is a iternal variable to the main page of the gallery - If you want a different link please change it.
  $email_bottomtext = "sent by TinyWebGallery. If you want to change or delete your registration please go to %s"; // new 1.2 - Every email gets this footer. It makes it easier for your users to get back to your gallery. %s is an internal variable to the main page of the gallery - If you want a different link please change it. 
$admin_email="test@test.com"; // new 1.3 - Email address where the notifications are sent to. $enable_email_sending has to set to true to make this work!
  $send_notification_if_comment=false; // new 1.3 - if true a notification is sent every time a user enters a comment
    $notification_comment_subject="A new comment was entered at " . $browser_title_prefix; // new 1.3 - Subject of the comment notification email
    $notification_comment_text="A new comment was added for image %s"; // new 1.3 - Text for the comment notification email. %s is the link to the image the comment was entered. 
  $send_notification_if_rating=false; // new 1.3 - if true a notification is sent every time a user enters a rating
    $notification_rating_subject="A new rating was entered at ". $browser_title_prefix; // new 1.3 - Subject of the rating notification email
    $notification_rating_text="A new rating was added for image %s"; // new 1.3 - Text for the rating notification email. %s is the link to the image the rating was entered. 

/* 
		In this section you can customize internal features of TWG 
		If you change something of the sorting:  the directory structure is cached - close your browser once after changing this parameter!
*/
$show_colage = true; // twg_show collage on the main page or the 1st image - you have to change the size of some preset images!
$use_random_image_for_folder=true; // new 1.2 - if true a random image of this folder is picked for the collage or the image which is shown in a folder icon.
$show_languages_as_dropdown=true;  // new 1.2 - show language flags or as a kind of dropdown
$skip_thumbnail_page = false; // Skip thumbnail page - if you set this to true, the thumbnail page is not displayed - be careful if you have subdirectories some levels cannot be displayed if a level before has pictures as well (like the demo)
$sort_images_ascending = true; // new 1.1 - true: sorts the images ascending; false: descending  (date and filename!)
  $sort_by_date = false; // new 1.1 - sorts the images by name if set to false - if set to true it tries to read the image exif data first - if  this fails it uses the filetime to sort! - read the faq for the settings you need on your server to get exif data! if you have a lot of images in a dir setting this to true could slow down the gallery because the data is read every time  a directory is read
	  $sort_by_filedate=false; // new 1.2 - uses the last modified file date and is not searching for exif data. If false is looks for exif data and uses the file time only if no exif data is available. 
$sort_albums = true; // new 1.2 - sometimes sorting is not wanted - I cannot tell how the sorting will be - but maybe exactly how you like it (most of the time it is the order the directories are created!)
  $sort_albums_ascending = true; // true: sorts the albums ascending (if $sort_album_by_date true = oldest first); false: descending () - directory struture is cached - close your browser once after changing this parameter!
    $sort_album_by_date=false; // new 1.3 - enables sorting of folders by last modified date - directory structure is cached - close your browser once after changing this parameter!
$autodetect_filenames_as_captions=true; // if true the filename is taken as caption if the filename contains less then 4 numbers (e.g. Hello. jpg is o.k,  CIM12345.job is not). if false - filenames are not used;
  $autodetect_filenames_as_captions_number=3;  // new 1.2 -  if you set $autodetect_filenames_as_captions = true you can set the number of numbers that are allowed  in a filename that it is used as caption. e.g. setting this to 3 means 3 numbers are allowed in a  filename to be used as a default caption - if a filename has 4 numbers it is not used as default
$center_cmotiongal_over_image=true;  // new 1.2 enables/disables to center the cmotion gallery when you move the mouse over the big image

/* 
		This section is responsible for all the watermark stuff 
*/
$print_text=false; // new 1.1 - you can print some text on the lower left corner to protect your images  or at least make it a little bit harder to copy it without doing anything :) if you change this setting please delete the cache folder - otherwise generated images are not changed. 
$print_text_original=false; // new 1.1 does print the text on the original as well - $enable_direct_download has to be set to false!!!
	$font = "./tahoma.ttf"; // this are the settings for the image text
	$fontsize=10;
	$fontsize_original=12;
	$text = "powered by TinyWebGallery";
	$textcolor_R = 255; // be careful with changing the colors ! if the compression is low the text becomes unreadable
	$textcolor_G = 255; // pretty fast if it is a crazy color :)
	$textcolor_B = 255;
$print_watermark = true; // new 1.1 - you can make a watermark on the images to protect your images or at least make it a little bit harder to copy it without doing anything :) if you change this setting please delete the cache folder - otherwise generated images are not changed. Please read the description of the parameters that belong to the watermark to get best results! 
$print_watermark_original=false; // new 1.2 does print the watermark on the original as well - $enable_direct_download has to be set to false!!!
	$watermark_small =  "buttons/watermark.png"; // new 1.1 - this is the watermark that is used on the detail and slideshow images - can be jpg or png - png gives better results
	$watermark_big =    "buttons/watermark.png"; // new 1.1 - this is the watermark that is used on the download images - can be jpg or png - png gives better results. you can use a larger image here because the original images are most of the times much bigger
	$position= 3; // new 1.1 you can define the location of the watermark with this setting (top:  1  2  3, middle: 4  5  6, bottom: 7  8  9
	$transparency= 50; // new 1.1 - you can also set the transparency of your logo. 0 is no transparency - 100 max; Try your logo with different settings to get best results  
	$t_x= 0; // new 1.1 - The next two settings define the position of a transparent color in your watermark. 
	$t_y= 0; // new 1.1 - If your logo has e.g. a white border you can set the values to 0:0 and the border will be transparent. If you don't want a transparent color: set these values to -1!

/* 
		Here you can set some default settings which can be changed by the user or by url or by option
*/
$show_only_small_navigation = 'FALSE'; // new 1.2 - default if only the small navigation is shown. 'TRUE' shows only the small navigation
	$default_big_navigation="DHTML"; // new 1.1 - There are two type of Big Navigation - normal (value "HTML") and dhtml (value "DHTML") - The dhtml version does a lot of preloading - please do not use this if you have a lot of images in a directories or your expected users don't have fast connections "HTML" = normal; "DHTML" = dhtml version (much cooler :))
$twg_slide_type = 'TRUE'; // new 1.1 - define the default slideshow type - 'TRUE' is the cross fade version, 'FALSE' the normal version, 'FULL' the maximized version! 
$twg_slideshow_time = '5'; // new 1.1 - Defines the default slideshowtime
$show_border='TRUE'; // new 1.2 - default for the border around the gallery. If the url parameter twg_withborder is set this value is not used. Valid values 'TRUE' and 'FALSE';

/*
 		Here are set settings for the bandwidth and the settings!
*/
$test_client_connection = true; // new 1.2 - you can enable/disable the connection test of TWG. It is now integrated into the main page (in <v1.2 it was an extra page!) - there the parameter lowbandwidth or highbandwidth are set! Optimizer settings $low_ ...  this settings are used if the parameter &lowbandwidth=true was provided once  &highbandwith=true uses the original settings. These settings are for users that have maybe only ISDN or a 56k modem. The file speed.htm does measure the speed of a connection and calls the index.php with the parameter lowbandwidth or highbandwidth - if you want to provide the same settings for all - just call the index.php directly :).  This is the minimum value (64 kbit ~ ISDN) a user have to have. If this speed is not reached the low_ parameters are used if test_bandwidth=true; With the actual setting you have to have more then normal ISDN to get the high bandwidth settings!
	$bandwidth_limit=80;
	$low_show_colage = false;
	$low_count_views = false;
	$low_cmotion_gallery_limit_ie=20;
	$low_cmotion_gallery_limit_firefox=10;
	$low_compression = 50; // be carefull with the compression parameter - if someone with low bandwidth calls the gallery first the images are generated with lower quality this will be then for all users - the maximized slideshow images are NOT cached - therefore its good to change this parameter later on to a lower value (or call the gallery first by yourself on a fast line) 
	$low_thumbnails_x = $thumbnails_x-1;
	$low_thumbnails_y = $thumbnails_y-1;
	$low_number_top10 =  ($low_thumbnails_x * ($low_thumbnails_y -2)) + 1;
	$low_show_big_left_right_buttons=false;
	$low_enable_maximized_view=false; // this is too heavy for a low bandwidth
	$low_default_is_fullscreen=false; // has to be set too because otherwise lowbandwidth starts in full screen
  $low_show_big_navigation="TRUE"; // or "FALSE" the next two settings are normally set by an url parameter - this parameter is overwritten in lowbandwidth mode 
	$low_twg_slide_type="FALSE"; // or "TRUE" or "FULL"
	$low_default_big_navigation="HTML"; // normally not shown in this modus - but the user can still enable the big navigation - and than this is the default
	$low_show_background_images=false; // new 1.3 - This disables the background images back.png, if present


/*
    You can show the user some tips or help or additional info if you like at the bottom -
    Just enter them in the language file $lang_tips_overview, $lang_tips_thumb, $lang_tips_image as array
    The style is defined in style.css: .twg_user_help_td
*/
$show_tips_overview=true;          // new 1.2 - enables to show a small tip on the overview page
  $show_tips_overview_once=true;  // new 1.2 - true: shows a tip only once per session  
$show_tips_thumb=true;             // new 1.2 - enables to show a small tip on the thumbnail page
  $show_tips_thumb_once=true;     // new 1.2 - true: shows a tip only once per session  
$show_tips_image=true;             // new 1.2 - enables to show a small tip on the image page
  $show_tips_image_once=true;     // new 1.2 - true: shows a tip only once per session  

/* 
		Internal settings - there is normally no need to change something here 
		- but you can :). 
*/
$debug_file = dirname(__FILE__) . '/' . $xmldir . '/_mydebug.out'; // new 1.1 - this is your debug file - if any error happen at TWG - they are written there! some installations don't allow writing this file to the main directory! Please write this file to a writeable directory then. If you want to disable debuging you have to set the file to '';
$cache_time=42; // new 1.1 - number of days until big images (big and slideshow images) files hat are not touched will be deleted from the cache. If you don't want cache cleanup use -1 here. some OS return at this test of the last time where the file was touched the time when it was last modified. The generated big images are deleted a little bit faster than ;). Thumbnails are never deleted!
$cmotion_gallery_limit_ie=40;  // new 1.1 - this is the max number of images that are shown in one cmotion gallery. At the end there will be a small arrow to go o the next x images :) IE does nice background loading and therefore the number of images can be much larger (after 5 images the cmotiongallery works!)  firefox does not with this script (if someone can solve this - die developer of the script couldnt :()  all images have to be loaded in the beginning (20x4k=80k - with ISDN ~ 10 sek - thats the maximum I would wait!) - better see optimizer settings
$cmotion_gallery_limit_firefox=20;
$enable_optimize_cmotion_gallery_limit_ie=true; // enable IE optimization for the cmotion gallery - if sometimes the preview images below the big images do not appear - set this value to false - cmotion_gallery_limit_ff is used then for firefox as well! 
$extension_slideshow = "slide.jpg"; // extensions of the images
$extension_thumb = "thumb.jpg";
$extension_small = "small.jpg";
$double_encode_urls = false; // new 1.2 - some servers need double encoding of all urls to work correctly - normally this is not needed but if TWG fails with spaces in filenames ... try a true here ! 
$test_email_by_checking_url=true; // new 1.2 - twg can test entered emails by trying the corresponding email domain - e.g. test@test.com would be tested by opening a connection to www.test.com - if this fails the email is not valid. This is not a 100% test because there are a few email servers that don't have a Http server running! If you set this to true these users have to enter a different email - if you are not online (test against wwww.google.de fails!) this test is skipped.  
$password_file="private.txt";     // new 1.2 - You can set the password file here. On a unix system you can use e.g. ".htprivate" to secure the private file! .txt is added here because I have a htaccess file that 
$enable_folderdescription=true;  // new 1.2 - if you don't use this turn it off - makes the gallery a little bit faster :)
$enable_foldername=true;  // new 1.2 - if you don't use this turn it off - makes the gallery a little bit faster :) - see how-tos if you don't know what this is :)
$enable_external_html_include=true; // new 1.2 - Enables disables usage of: header/footer/overview/thumb and image.htm ! - if you don't use this turn it off - makes the gallery a little bit faster :) -see how-tows if you don't know what this is :)
$enable_smily_support=true;  // new 1.2 -  :) , ;) and :( are shown as smiles - adapt the size of the smiles if you change the font size ! - read the howto about smilies !!
$twg_version="1.3";          // new 1.2 - version for the administration and the display :) - see how-tows if you don't know what this is :)
$TWG_SESSION_PREFIX="twg";   // new 1.2 - TWG caches lots of information in the session for caching - if you move between different TWG installations information from the other can be available - TWG checks the server name every time and invalidated the session if this is changing. But if two installations are on the same machine you have to use different values for both installations. If you are on a public server the change that two TWG installations are present and the user knows both is very unlikely - but to be sure just change this value to something random. 
$url_file = "url.txt"; // new 1.3 - you can place your images on an extern http - on the local server are only some working images! This only works of fsocksopen is available. Please check the how-to!
$cache_dirs = true;  // new 1.3 - content of directories are cached in the session - you can disable this if you have lots of image updates or are testing TWG while uploading.
$show_background_images=true; // new 1.3 - you can enable/disable the including of the background images - turn this of if you don't use the background image back.png 
$min=2; // new 1.3c - after how many minutes does a user count as a new user - 2 means that if he does not do anything for 2 minutes the counter is increased
?>

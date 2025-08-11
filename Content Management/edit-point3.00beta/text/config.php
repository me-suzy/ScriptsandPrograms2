<?php

// site name and page title.
$page_title = "Edit-Point 3.00 Beta";

//Pre-populated text in "Choose a name for the new Edit-Point."
$samplename = "New Point Name";

// Pre-populated text in texarea.
$sampletext = "This is sample text";

// Directories or files to ignore in drop-down list of files to choose from. Limit 10.  See README if editing by hand.
$ignore = array(".","..",".htaccess","text");

// data directory name (where the .txt files, created by the script, are stored).
$datadir = "data";

// redirect speed after editing a point (index.php). 1000 = 1 second
$edit_redirect = "3000";

// redirect speed after creating a point (admin.php)., 1000 = 1 second
$admin_redirect = "3000";

// Textarea width (rows).
$edit_width = "60";

// Textarea height (columns)
$edit_height = "18";

// html start tag
$p = "<p>";

// html end tag
$p2 = "</p>";

// add Edit-Point links to admin page. on or off.
$adminlink = "on";

// option to add one Edit-Point to multiple places. on or off.
$multi = "off";

// option to add links to all script pages on all pages.
$su = "off";

// PASSWORD PROTECTION SETTINGS

// whether or not to use the built-in password protection. NOT RECOMMENDED!!! Use .htaccess instead.
$password_protect = "off";
// admin password for admin.php and options.php.
$admin_password = "admin";
// user password for index.php.
$user_password = "user";
// upload password for upload.php.
$upload_password = "upload";

// FILE UPLOAD
// Option to use basic file upload/delete. If used a log file (upload_log.txt) will be created in the "data" directory.

// whether or not the "File Upload" option is available. on or off.
$fileupload = "off";
// domain name for fileupload. No end slash "/".
$fileupload_domain = "http://YOURDOMAIN.com";
// maximun file size. The default is 2MB. NOTE: Your server limits the size of uploads via php so you will have varying results. View your "php info" and look for "upload_max_filesize" to see your limit. (1000000 = 1MB)
$fileupload_size = "2000000";
// name of the directory that files are added to. This will be created automatically one directory above the "text" directory. For instance, your Edit-Point installation is: http://YOURDOMAIN.com/text/ and the file upload directory (files) will be: http://YOURDOMAIN.com/files/
$fileupload_directoryname = "files";
// whether or not to allow files to be deleted.
$fileupload_delete = "on";

// TinyMCE WYSIWYG EDITOR SETTINGS.

$imagedir = "images"; // image directory from domain name. This setting will allow all subdirectories to be indexed as well. No end slash "/".

// Setup Utility to automatically chmod the "data" directory and either create the image directory or set the correct permissions of the existing image directory. The script will chmod the directory and all subdirectories 755 and chmod all files 644.
$setup = "off";

 // WARNING!!! Do not edit anything below this line unless you manually edit "/text/jscripts/tiny_mce/plugins/imanager/config/config.inc.php" line 27 so that "text" equals your changed script directory name.
 
//---------------------------------------------------------------//

 // whether or not to use the header/footer. on or off. NOTE: "on" is required for the WYSIWYG option. 
$head = "on";

// script directory.
$textdir = "text";

// path from script directory to webpage directory.
$pagepath = "../";

?>
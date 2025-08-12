
 Random Popup
 by: http://free-php.net

 To use this script is fairly easy the only requirements are a server running php4 and mysql.

 You can include this in any page of your site at the very top or at the top of your header file.
 using <? include('/path/to/popup.php'); ?>

 # MySQL Database Setup

 -- Open sql.txt in the main RandomPopup folder.
 -- Paste the contents into a tool that can execute SQL commands for your MySQL DB

 # Installation:

	Step 1 - Follow the MySQL Database Setup Before Continuing
	Step 2 - Open inc/config.php and modify the settings to those of your server.

 To add urls open addurl.php
 To delete urls open deleteurl.php

 To modify the add url form edit inc/form.php
 To modify headers n footers edit inc/header.php, inc/footer.php

 TROUBLE SHOOTING

 	Make sure all paths are correct in each file use full paths if necessary.

 # Version Info

 -- v1.1
 ---- Added sql.txt to aid in creation of table
 ---- Changed code to not depend on register_globals being set
 ---- Made script more efficient

 -- v1.0
 ---- Initial Creation

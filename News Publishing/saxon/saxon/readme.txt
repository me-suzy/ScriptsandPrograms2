SAXON : Simple Accessible XHTML Online News
===================================

Copyright (c) 2004 by Black Widow
http://www.blackwidows.co.uk

SAXON is a simple accessible online news publishing system for 
personal and small corporate site owners.

Publish news using configurable templates.
Publish news on any .php page on your site.
Edit and/or delete existing news items
Create an RSS news feed from your current news postings with the single click of a mouse.
Multiple authors allowed.
Ability to configure users as Standard or Super Users (admins).
Ability to add/delete users (Super User only).
Option to change any user password (Super User only).

===================================
REQUIREMENTS

* PHP4
* MySQL
* Some XHTML skills if you weant to create your own news templates

===================================
UPGRADING

Simply copy all of the php files to your existing saxon folder.
DO NOT run setup.php. Delete it!

===================================
CONFIGURATION

Edit config.php to fit your needs

Edit fake-cron.php so that $lastrun equals today's date in the format YYYY-MM-DD (eg: 2005-09-23)
If you don't want the fake cron function to run, set $lastrun to equal "never'".

Upload the saxon folder to your website

CHMOD templates/news.xml and templates/fake-cron.php to 777 (-rwxrwxrx). Rename the xml file if neccessary.

Run the setup programme by going to http://your-sites-url.com/path_to_saxon_directory/setup.php

Goto http://your-sites-url.com/path_to_saxon_directory/login.php to log into SAXON.
Username: admin
Password: nimda

Select 'Admin' from the main menu followed by 'Change User Password'. Change the default Admin password NOW!

Delete setup.php

Begin adding news items

===================================
DISPLAYING YOUR NEWS

Add <?php include "relative_path_to/saxon_directory/news.php";?> to any page where you want to display news and ensure that this page ends  with the extension '.php'

Add <?php $user="user_name"; include "relative_path_to/saxon_directory/news.php";?> to any page where you want to display news on a 'per user' basis  (where user_name = one of your SAXON posters). Please note that user_name is case-sensitive. You need to enter it EXACTLY as it  appears in your SAXON users table or on the List All Users option of the Admin section. If you try to reference and non existent user, you will simply end up with a page displaying the message "No news to display". Ensure that this page ends with the extension '.php'. 

Add <?php include "relative_path_to/saxon_directory/archive-display.php";?> to any page where you want to display your full news archive and ensure that this page ends with the extension '.php'

===================================
TEMPLATES

Have a look at the default templates for ideas on how to create your own.  The display-header.php and display-footer.php are needed if you have limited the number of words  displayed on your main news page ($newslength are not set to 0 in config.php). Under these circumstances, the main  news script will create a link to a generated page for each, individual, news item.

##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingLite                Version 1.1.0                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 12/09/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################

******************
Requirements:
******************
PHP version > 4.x.x & MySQL

******************
Script features
******************
- Quick and easy instalation
- Easy Template (header and footer, css in header, and some templates in config file)
- Register/delete/edit users
- MySQL fast configuration and work with sometimes repair and optimize database
- Easy your language translate feature
- Uploading photo (max file size setting in config)
- Powerfull configuration in user registering
  - checking for username length
  - checking for password length
  - checking for real email
  - one mail for every user (if you set allow in config file)
  - checking for hobby and description wordsize and length
  - checking for numeric age and length
  - other fields checking
- User can view own profile after registering
- User can edit your profile
  - delete own profile (if you set allow in config file)
  - use md5 password crypting
  - Upload or change current photo
- User can send password to own mail by powerfull "forget password" form  
- Anybody can search in this dating by many parameters (country, gender, category, with photo or all profiles, how many results on page)
- Automatically Search results ranked by date (last profiles go first), and automatically divide by pages
- Hide mail from all users - anyone can write letters from feedback like form in the site
- Feedback with webmaster

Admin function
- Powerfull admin page with login and password md5-coding
- Optimize tables
- Repair tables
- Remove members 

******************
Installing script
******************
1) !! Edit config.inc.php file to match your web sites values. !!
2) Upload all files and folders to your website.
3) !!!! CHMOD directory members/uploads to 777 !!!!
4) Run install.php to setup the MySQL database.
5) ! Delete install.php for secure reasons !

Thats all!
******************          
          Admin page - http://your_installed_path/admin.php
          Main page - http://your_installed_path/index.php

******************
Upgrade script
******************
from 1.0.3 to 1.1.0
Change new config.inc.php. 
Upload new files: 
config.inc.php 
add.php 
members/index.php
search.php
languages/default/default.php

from 1.0.2 to 1.0.3
Change new config.inc.php. 
Upload new files: config.inc.php, add.php, members/index.php

from 1.0.1 to 1.0.2
Simple upload new add.php instead old file in your server!

******************
Templates
******************
         
For use this script under your site design you can:
- Change some configurations in config.inc.php
- Change header.php and footer.php
- Include your own style parameters in header.php (in <style></style>)
********
Note: Don`t change style names likes .head, .mes, .dat and other`s - you must only change variations in there tags !!!
********
          



******************
Support script
******************
       
If you buy this script you get powerfull supporting!
You can write to support@azdg.com, estof@bakinter.net or visit our site http://www.azdg.com


******************
License script
******************

GNU GENERAL PUBLIC LICENSE
Please read and agree with gpl.txt file          

##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingGold                Version 3.0.5                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 25/05/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################

******************
Script features
******************
- Quick and easy instalation
- Easy Template (header and footer, css in header, and some templates in config file)
- Register/delete/edit users
- MySQL fast configuration and work with sometimes repair and optimize database
- Powerfull Several language support - easy add new language
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
- Search can be inside country (select country, and search from city list)
- Automatically Search results ranked by date (last profiles go first), and automatically divide by pages
- Hide mail from all users - anyone can write letters from feedback like form in the site
- Feedback with webmaster
- Statistic page (Powerfull statistic with easy add some statistic scripts) - statistic for male,female,with photo or no, by age and more other
- Counting person popularity with counting page views
- Lock IP address counting if it view page (if you set allow in config file)
- Top Man and Top Woman on the main page

Admin function
- Powerfull and security admin page with login and password md5-coding
- Unsucessfull join in admin panel writes to MySQL log file (date, ip, path, sys information)
- Optimize tables
- Repair tables
- Clear member hits
- Remove members
- Powerfull search/edit/remove members tool!!!
- Powerfull Send Mail to members tool - can send mail by gender, country with notify if mailing has been ended!
- View/clear admin log file!
 

******************
Installing script
******************
1) Edit config.inc.php file to match your web sites values.
	      2) Upload all files and folders to your website.
	      3) CHMOD directory members/uploads to 777
	      4) Run install.php to setup the MySQL database.
          5) Delete install.php for secure reasons

Thats all!
******************          
          Admin page - http://your_installed_path/admin.php
          Aain page - http://your_installed_path/index.php

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

Templates files:
Don`t remove any <!--@ --> and {} tags.
Please write us for some questions.
You can only change html code change place of {variables} including in <!--@ -->. Don`t include {variable} to another <!--@ --> tag!!!
          
******************
Support script
******************
Write to support@azdg.com

          

Readme: Version 2.2.Reloaded
HelpDesk: Version 3.4.6
Date 9-6-2005
Web Site: http://www.HelpDeskReloaded.com
Help Desk Problem and New Ideas Discussion Form: http://www.helpdeskreloaded.com/help-desk-form/

1.0 Be Kind.
-------------------------------
This software copyright Help Desk Reloaded and distributed under a limited terms License. Failure to comply will result in prosecution under the maximum extent of U.S. and International Law.  Don't say we didn't warn you.

The Following Actions are prohibited.  You agree to these terms by downloading and or using the software.

You may not redistribute help desk reloaded software.  
You may not reverse engineer this software for any purpose including porting it to a different programming language.
You may not remove or alter any Help Desk Reloaded Logo or Copyright notice in the software.
This software cannot be resold or charged for in any way.
You may not hold Help Desk Reloaded or associates liable for any use of this software.
If you have any questions about Copyright or licensing please go here http://helpdeskreloaded.com/submit-help-desk-support-question.php

If you alter the software in any way that does not violate the above terms, we enourage you to contribute to the distribution, you can contact
us on ether the help desk dicussion form or our contact form on www.helpdeskreloaded.com

**********************************

OS Detetion CopyRight Notice
phpSniff: HTTP_USER_AGENT Client Sniffer for PHP
Copyright (C) 2001 Roger Raymond ~ 


-----------------------------------
System Requirements:
*No.. Stop trying to use the help desk on mysql 1.0.. really.. please.

You must at least have these versions: PHP 4.3.10, and MySQL 4.0.14 or newer.  If your version is older please upgrade or we cannot support it.
--------------------

2.0 Notes
Please backup your MySQL Database
first before trying anything, especially upgrading.  Report problems to the discussion form on helpdeskreloaded.com
Try: http://www.assurebackup.com for free MySQL Backup software, or any other MySQL backup application will work.
*Note the help desk will never alter any tables but its own.  The best way to protect your existing tables is to be sure
to use the table prefix when you install.

-------------------
Upgrade:

Run HelpDesk(VersionNumber)\upgrade\index.html
Be prepared to provide your existing MySQL Login Information and Help Desk Table Prefix.

----------------------------------------
3.0 Fresh Install

* Note default.htm is no longer supported and has been removed.  We now use index.php as the default start page.

Requirments:
PHP, MYSQL

Unzip files to your web server.  Do no change directory strucutre.

Enable Write Permission to these files: config.php & /install/config.php
For this version to work, 

Open a web browser and go to /helpdesk3??/install.php
*?? represents the version we are currently using.

Follow directions as indicated.

Once done, login to the help desk and use the help desk control panel to configure the software.
You will at least need to create a new category to test creating a new trouble ticket.

This Software Creates a Config.php file to store your MySql Login information. 
You can find all of the MySql and Config.php documentation on http://www.helpdeskreloaded.com
If your having problems with installation See config-EXAMPLE.php, 
fill in your information and change its name to config.php, then open process.php 
and following the instructions at the top of the code.

This is an ongoing project, so check our web site often for updates and new features,
or submit your own modifications to email@helpdeskreloaded.com.

NEW IMAGES NOTE:
*if you do not like the new images, you can change the files

/images/yellow.jpg
/images/red.jpg
/images/green.jpg

in the images folder. 

You can turn the images totaly off in the help desk control panel > Setttings Page.
You can also turn on and off email handling in the help desk control panel settings page as well as many other features.
----------------------------------------
4.0 Security

There are a number of files you will want to delete when you are done with the install to make the help desk more secure.
Delete or rename the following files:

install.php
process.php
install-1.php
accountsinject.php

Change config.php back to Read Only Access.

----------------------------------------


Problems:

1. Sometimes on the first login, you will have to login twice for it to accept your password.


2. Always click on buttons, do not press enter to submit.

3. All else: the best way to get a problem fixed, or a new idea added is to use the dicussion form on our web site.


--------------------------------------
For Change Log, see discussion form on www.helpdeskreloaded.com

For more details go to http://www.HelpDeskReloaded.com
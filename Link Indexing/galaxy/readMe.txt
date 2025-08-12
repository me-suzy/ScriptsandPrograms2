To Install Galaxy.php

A.
1. Setup a databse on your server. You do not have to create any tables but you must create the actual database. This program has been tested with MySQL.
2. Unzip files from galaxy.zip
3.Open the galaxy folder , open the sqlSetup.php and functions.php files in a plain text editor and change the information at the top to reflect your settings
4.  Use ftp program to upload the "galaxy" folder and all its contents to your server
5. Open the "galaxy" folder on your server and use CHMOD to change permissions on all files to 755
6. Open the "backend" folder and CHMOD all files to 755.

B.
1. Secure "backend" folder via .htaccess or protection of your choice. Your server may have this feature built in, if not I have included pwdPro in which case you should follow directions below.
Only for pwdPro users{
1. Upload "phpaccess105UnixServer.php" from the pwdPro file contained in the zip to the "backend" folder on your server.
2. Call that page (eg. http://www.yourserver.com/galaxy/backend/phpaccess105UnixServer.php), use "test" as user name and password, then configure accordingly. Additional instructions can be forund in the readme file in the pwdPro folder. pwdPro is an open source password protection agent protected under the GPL and not written by me. Details can be found in the read me file contained in the pwdpro folder.}

C. 
1. Use your browser to call sqlSetup.php. If you get any error, open your databse, erase any tables that were created and try again.
2. !!!! If there are no errors, IMMEDIATELY delete sqlSetup.php from your server!!!

D.
1. Navigate your browser to galaxy.php (eg " http://www.yourserver.com/galaxy/galaxy.php"). Choose add link and add your first link. You should recieve an email asking you to approve this link. You do not have to wait however. Simply choose admin, sign in and choose "show link" from the menu. Return to the galaxy.php page (you may have to refresh) and you should see your first link.
2. At this point you may open up galaxy.css and change styles to reflect your site.

email any questions to galaxy@lbilocal.com or find me at ashokaSQL on AIM.
This script may be further customized or installed for a small fee. However it is open source so feel free to play with it if you want.
 If you do make any changes please inform me so I may consider including them in future releases.

This script was written by Richard B Mowatt (http://www.rmowatt.lbilocal.com) and is being released as open source code under the GPL license.
Any attempt to represent this work as your own, or to sell this code for profit will incur legal action.
Script headers must be left intact and all changes notated with the date, programmer and email.

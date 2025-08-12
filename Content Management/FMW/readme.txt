Football Manager Website
By: R.Reason (c)2005
Email: richard.reason@ntlworld.com

IMPORTANT:
This script has never been completed, you may use it as it stands if it is of any use to you. 

USE THE SCRIPT AT YOUR OWN RISK !!!
There is very little security.
As far as I know what there is works, but there are likely to be some bugs, as I have never had time to test it fully. If you come accross any problems you may email me, and if I have time I will try and fix it.

This PHP/MySql script was started so I could learn some PHP. It was done in my spare time at work (while the boss wasn't looking!) and although it should be in a usable state, it was never finished. There were several more features I wanted to try, but have had no time in the last 3 months to do anything to it.
I have decided to make it available as it may be of some use to somebody out there.
Most of it is probably very poorly written, as I said I was just playing with it to learn a bit more. Please don't expect too much from it !




Installation Instructions:

Using phpMyAdmin, create a new database. (remember to add a user to the databse)
Select the new database in phpMyAdmin, and select 'SQL' to import the database data.
Browse to the folder where you extracted the files, and open the file fb.sql, then click 'go' to import the file.

Edit the file db_connect.php Here you need to replace the following values to relate to your configuration:

$db_user = 'enter_username'; here you need to enter the username you added to the database that was created
$db_pass = 'enter_password'; enter the password you use to access phpMyAdmin
$db_host = 'localhost'; This should be left as it is.
$db_name = 'enter_database_name'; Enter the database name you created. You may need to prefix this with the username (see below)

An example configuration may look like:

$db_user = 'Rich';
$db_pass = 'mypass';
$db_host = 'localhost';
$db_name = 'Rich_fmw';

Once done, save the file.

Create a new folder within your webspace. 
Upload all files to this folder.

Using your FTP program, CHMOD the following folders:
images to 777
images/avatar to 777
images/news to 777
images/topic to 777

Point your web browser to the location of your installation, ie http://www.yourwebspace.com/fmw/main.php

Click on 'login' and login as user:admin password:admin
You will now have an 'administration' link above the other links.
First create a new user, giving yourselft admin rights. Login using the new user, and delete the admin user.
Now use the configuration screen in the administration page to configure the program and upload your logo.

If you use the script for your team website, I would appreciate it if you could let me know, as I would be interested to see a site up and running using it.










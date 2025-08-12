Readme, updated 10.30.04

This script was created by Valerie of Unrighteous.Org.  All information you need should be included below in this readme file.
Beta testing?  I need you to email scripts@unrighteous.org so I have a way of contacting you with news, releases, etc.  Thanks!!!

THANK YOU
-------------------------------
Special thanks to Samantha: thedancingbear.spoken-for.org  :-D


ABOUT
--------------------------------
Simply put, I needed a random links script, my menu was just getting too long.  I wasn't able to find something I thought I could modify (random image scripts taken from a directory) so I had to write from scratch.  :-D    
This script is meant to fit seamlessly into your site's existing layout (in a php page).


EXAMPLE
--------------------------------
To view a working example of this script, please visit unrighteous.org.


LICENSE
--------------------------------
You are free to use this script as you like with the following conditions:
1. Any copyright information stays as it is, this includes in any php code itself and as displayed on the administration footer (a link).  Links do not appear anywhere on the script where your visitors will see it.  So I ask that you include a link somewhere else on your page be it with the script or on a links page or on your domain/credits page, etc.
2. If you find any bugs or have any suggestions, you email them to scripts@unrighteous.org or post them on the support forums at unrighteous.org/forums.
3. You agree to indemnify me from any and all liability from using this script.  Basically, I can't be held responsible for what you do with it or what the script itself does.  But really, it shouldn't DO anything.
4. You cannot redistribute this script in part or in whole, modified or not.  Just please don't do it.


FEATURES
--------------------------------
1. Displays a chosen number of random links
2. Links are displayed as either text or an image
3. Simple deletion from the administration panel
4. Display a readout of all of those in the database with the showall.php
5. Very customizable by including the unstyled pages into your layout
6. Unique id for each submission for use in deletion
7. Links open in a new window
8. You're given the choice to include something before and/or after each link (ie <li> & </li> or a <br> at the end, etc)



KNOWN BUGS/ISSUES
--------------------------------
None that I'm aware of, but you must be careful with deletion.  BE SURE YOU HAVE THE CORRECT ID of the one you want to delete.  There is no confirmation and deletion is permanent.
Oh, I guess this could be an issue - currently there is no way to update links.  However adding to the database is so simple that all you have to do is just add the updated link and delete the old one.


REQUIREMENTS
--------------------------------
A webserver with PHP and MySQL installed
FTP or other way to upload files


FILES
--------------------------------
You should have received the following files in your zip file.  If not please email scripts@unrighteous.org.
addlink.php - for adding links!  Password protected
configure.php - controls all settings for the script, this is the file you will edit
deletelink.php - for deleting links!
footer.php - goes at the bottom of all ADMINISTRATION files
index.php - simply keeps others from viewing the content of your directory, is not really used for anything
install.php - used to install the script
links.php - will output the random links, include it in your layout
readme.txt - the file you're reading now
showall.php - for showing all links, in alphabetical order, include it in your layout (optional)
showids.php - for administration purposes, shows all links ordered by id so that you can pick the correct id of one you want to delete; or to make sure you don't duplicate a link that's already in the database, etc.
style.css - minimal style control of the administration pages



INSTALLATION 
--------------------------------
1. Make sure you have an available MySQL database.  If you're not sure how to create one, contact your host or visit the support boards at http://unrighteous.org/forums.
Make sure you have a username linked to this database and that it has all permissions.

2. Open up configure.php in any text editor, fill out the information that's needed.  Everything should be commented well enough (denoted by // before a line) to tell you what goes where.
If you are going to be using images, make sure you tell it 'yes' in the appropriate place and create a folder (or point to a current one) on your webserver where you will upload the images.

3. Upload all files to your webserver in the folder of your choice, except for this readme.txt, that is not necessary.

4. Go to your folder and run the install.php file.  This will create the necessary tables on your database, if all your information is correct.  If you get an error message, check your configuration file and try again.
This will install one table, by default it will be named sflinks.  If this conflicts with anything you already have in there (which I would think it wouldn't), do not install it but instead either use another database or change the name of the table in the config file.

5. Delete install.php from your server.

6. You can now use the addlink.php and deletelink.php files for your site!!!

7. To include into your site - use a php include of the file links.php for example:
		<?php include('/home/path/to/links.php'); ?>
	Unsure of your absolute path?  You can ask at Codegrrl.com/forums if you need help, but I BELIEVE that typically it will be something like /home/username/public_html/restofthepathhere - mine after public_html would look like scripts/spokenfor/sflinks/links.php

8. EMAIL scripts@unrighteous.org OR POST ON THE SUPPORT FORUM AT http://unrighteous.org/forums WITH ANY QUESTIONS OR COMMENTS!!!


NOTES:
If you're not using images, the image field will still turn up on the addlink.php page - just ignore it
If you are using images, be sure that you just enter the name of the image in the image field when adding a link (this is because you should have already defined the rest of the path to the image in the configuration file)



CUSTOMIZATION
--------------------------------
There really is no other customization to do.  Just be sure that you've written something in the configure file to separate your links be it list code or line breaks, etc.  When it's included into your site, it will take on the same styles as your site!!



THE FUTURE
--------------------------------
What's planned for the future?
1. A better way to update links
2. A better way to delete links


Suggestions?  Email scripts@unrighteous.org or hit the support forums at http://unrighteous.org/forums


CHANGE LOG
--------------------------------
2.0 - Oct 21, 2004
Now supports using images as links.  The user can also choose between using images or text.

1.0 - Oct. 19, 2004
brand new script, enjoy!!

##########################################
### 
### CzarNews v1.14
### Made by: Czaries  [czaries@czaries.net]
### http://www.czaries.net/scripts/
### for more scripts and updates.
###
##########################################

# Installation/Upgrade
# -----------------------------------------------------------------

1.	Upload all the files in the ZIP file to your website in the directory of your
	choice (i.e. http://www.yoursite.com/czarnews)

2.	Open up that directory in your browser window.

3.	You should be taken to an installation page.  If you for some reason are
	not, type in www.yoursite.com/czarnews/install.php

4.	If this is your first time installing the script, or if you would like to start 'fresh'
	again (delete all existing data), choose 'Full Install', otherwise choose 'Upgrade'.

5.	Follow the instructions of the page, making sure you fill out EVERYTHING
	that is asked for, and click the Install button.  If any errors come up, try
	re-typing your MySQL username and password.  If you still get errors,
	CHOMD your directory 777 and try again.

6.	After the install, you will be brought to a configuration page.  Make sure all
	the variables are correct, and save the settings.

7.	To use the image uploading feature, you will also need to chmod your image upload
	directory to '777' (default is 'uploads').

### INCLUDING THE FILE ###

8a.	To get the proper PHP code for including the file, you can log-in to the script
	admin panel, and click "Code Generator" (only "Ultimate Admin" users have access).
	Select the appropriate options, click generate code, and copy and paste the code
	into your own php file.  This is the preferred method, and is generally the most
	accurate one.
	
	--- OR, you can do it manually with the followings steps: ---
	
8b.	On the page you wish to show your news, you must INCLUDE the file
	'news.php' via PHP's include() function.  The page you include the news
	file on must be a PHP page (.php, .php3, .php4, .phtml), NOT an HTML page (.html)

	An example of the PHP code for including news would be:
	<?
	$tpath = "/home/user/public_html/path/to/czarnews/";
	include($tpath . "news.php");
	?>

	As of CzarNews v1.14, you no longer need to define the '$tpath' variable.  The
	included script will automatically get the correct directory information from
	your server.  The '$tpath' variable in the code example shown above is just for
	clarification and your own personal use.
	---
	
9. 	You are now ready to post news.  Open up the news directory in your browser
	window, login if necessary (the install should have logged you in), and
	post news.  Your news should show up on the page you included news.php
	on.



# Troubleshooting
# -----------------------------------------------------------------

There is now a 'troubleshooting' installation mode that will help guide you through
parts of the installation if you run into problems.  If your MySQL tables did not get
setup correctly, or if your database definitions file did not get created, or something
else goes wrong during installation, please run the troubleshooting installation and
see if you can fix the problem manually.

Additionally, if you have problems, try using the 'czarnews.sql' file that was included.

If you run into some sort of problem, please post it on the FORUMS under Scripts
and Troubleshooting.

Scripts Forums: http://forum.czaries.net

If that fails, you may email me at: czaries@czaries.net



# License/Distribution
# -----------------------------------------------------------------

FREEWARE

This script is distributed for free by www.czaries.net, or my personal collection
of websites only.  It is intended for use on websites of all types, personal and
commercial, as long as the credit link remains intact and visible wherever the
news is displayed.  This script was made in 2003 by Vance Lucas.  If you
choose to modify your copy of the script, you have the right to do so freely.
You may submit your modifications to me, but please do not re-distribute the
script with your modifications, as it is not the original work.


# Changelog
# -----------------------------------------------------------------


---------------------------------------------
v1.14	- March 25, 2005

	New Features
	
		* Added 'cn_images.php' page for adding/uploading images for news articles
		* Added image upload, delete, and thumbnail functions
		* Added config options for images, like width and height for thumbnails
		* Added user permission to access and use images in user's news posts
		* Added function to build query string so CzarNews will work within most portal systems
	
	Bug Fixes/Updates

		* Update: Fully compliant XHTML 1.0 Transitional output for news items printed out by 'news.php'
		* Update: Changed all function names to start with a 'cn_' prefix to avoid conflictions with a user's predefined functions
		* FIX: Remote file inclusion security hole if 'allow_url_fopen' and 'register_globals' are turned 'On'
		* FIX: File 'fpass.php' would produce an error if register_globals was not on
		* FIX: File 'news.php' would display news from all categories, even if a category ($c) was specified


---------------------------------------------
v1.13b	- October 13, 2004
	
	Bug Fixes/Updates
	
		* Fixed category permission error when users were not granted access to post under all categories


---------------------------------------------
v1.13	- October 2, 2004

	New Features
	
		* Added 'cn_update.php' file that checks czaries.net for updates
	
	Bug Fixes/Updates
	
		* Updated all variables to superglobals ($_POST, $_GET, etc...) for increased security and portability
		* Updated existing functions for increased portability and added new function to list usernames
		* Updated generator and install page to auto-detect correct current directory of CzarNews
		* Fixed bug that allowed anonymous users to use HTML in comments
		* Fixed bug to insert line breaks for content areas ONLY instead of after news was completely formatted (would break tables, etc)
		* Fixed some cross-browser javascript compatibility issues for admin panel of CzarNews
		* Fixed bug that would not allow users to edit their own news posts when 'News Admin' was unchecked
		* Fixed 'Function redefined' errors that would appear if user included 'headlines.php' or 'news.php' more than once on the same page
		* Security warnings are now only displayed for logged-in admin users
		* Structured all PHP code to make it easier to read (at the request of CzarNews users)

---------------------------------------------
v1.12	- March 7, 2004
			
	New Features
	
		* Added 'All Categories' news display selection for category box
		* Added news search feature with highlighting of searched word
		* Added Comments with current username protection (somone cannot post a comment under a CzarNews user's name w/o their password)
		* Added 'cn_info.php' file to explain a little about the script and myself
		* Added 'cn_generate.php' file to generate PHP code that you can use to include the file 'news.php'
		* Added ability to create news summaries and full stories
		* Added ability to move and delete multiple news items at once
		* Added ability to edit HTML for 'source' and 'author' links
		* Added warning for having 'install.php' on server after script is installed 
	
	Bug Fixes
	
		* Optimized several lines of code for faster execution speed
		* Fixed bug with category display box (would not display if user was not logged in)
		* Fixed security bug that allowed users to delete news items outside their category permissions
		* Fixed news setting to allow user permission: 'allow user to edit/delete only posts that they themselves posted 

---------------------------------------------
v1.01	- December 16, 2003

	New Features
	
		* 'Forgot Password' mailer
		* Single article link (can view single news item with id) 
	
	Bug Fixes
	
		* News numbering problem in news admin page
		* Modified default selections when adding new user to make it easier
		* User listing fixes when only one user exists
		* Added selection box for viewing and displaying news in different categories when more than one category exists
		* Source URL would not display properly when used in a news post
		* Updated some functions in 'cn_config.php' to increase excecution speed
		* Minor modifications to install script
		* Provided SQL file of table structure incase install script failed (czarnews.sql) 

---------------------------------------------
v1.00	- July 30, 2003
			
		(BETA) First initial public release.  Released under BETA becuase the script had never been tested on a public scale
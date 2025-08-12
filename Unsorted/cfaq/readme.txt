CascadianFAQ v4.0 - Last Updated: November 2003


#########################################################
#                                                       #
# PHPSelect Web Development Division                    #
#                                                       #
# http://www.phpselect.com/                             #
#                                                       #
# This script and all included modules, lists or        #
# images, documentation are distributed through         #
# PHPSelect (http://www.phpselect.com/) unless          #
# otherwise stated.                                     #
#                                                       #
# Purchasers are granted rights to use this script      #
# on any site they own. There is no individual site     #
# license needed per site.                              #
#                                                       #
# This and many other fine scripts are available at     #
# the above website or by emailing the distriuters      #
# at admin@phpselect.com                                #
#                                                       #
#########################################################



---------------------------------------------------------
FILES INCLUDED IN CASCADIANFAQ
---------------------------------------------------------
If all these files are not in your distribution, you most likely have an modified version of this script.  Please download the most current version at http://eclectic-designs.com/cascadianfaq.php

	readme.txt	<-- This file...duh

	admin.php      	<-- For Administering the FAQ
	config.php 	<-- File where you edit configuration options
	footer.php		<-- For modifying look of FAQ display
	functions.php	<-- Hold common functions used in system; edit at your own risk
	gnulicense.txt 	<-- GNU license...do not redistribute without this license!
	header.php	<-- For modifying look of FAQ display
	index.php 		<-- The page that displays the FAQ
	install.php 	<-- Creates necessary tables for CascadianFAQ
	mostrecent.php 	<-- For including the most recent list in any page
	mostpopular.php 	<-- For including the most popular list in any page
	upgrade.php 	<-- Upgrades database and config file from pre v3 and prev4 versions
	
------------------------------------------------------
CASCADIANFAQ'S SYSTEM REQUIREMENTS
------------------------------------------------------
	* PHP 4.1 or higher
	* mySQL database or PostgreSQL database
	* Apache server (may work with IIS but I have never tested it there)

------------------------------------------------------
INSTALLING CASCADIANFAQ
------------------------------------------------------
	1.  Open the config.php file in a text editor (such as NotePad or HomeSite)
	    and edit the values to match your desired specifications.
	2.  Edit the header.php and footer.php files to customize the look of the FAQ
            to your site.
	3.  Create a directory on your server called cfaq and FTP all of files there.  
	4.  Run the install.php file to add the necessary tables to your database.
	5.  And that's all there is to it...now read the usage info below.
	
------------------------------------------------------------------------------------------------------------------------------------------------
UPGRADING FROM AN EARLIER VERSION OF CASCADIANFAQ (mySQL users only)
------------------------------------------------------------------------------------------------------------------------------------------------
	1.  Replace all files with the ones from the newer version EXCEPT for header.php, footer.php, and 
	    config.php.  If you made any customizations to the other files, you'll need to add those 
	    customizations back in again.  If upgrading from v3.0 to 3.2, skip to step 4!
	2.  If upgrading from 2.2 or earlier, delete the old functions.inc file and rename
	    your config.inc file to config.php.
	3.  If upgrading from a pre v3 version, make sure your config.php file is writeable, then run the 
	    upgrade.php file.  This will make some changes to your config file and to the questions table
	    in CascadianFAQ. Lastly Reset your config permissions so that it is no longer writeable.
	4.  That's it.  All new features should now be available to you.  You can modify the new "most recent"
	    and "most popular" question settings in the config.php file at any time. :-)

------------------------------------------------------------------------
USING AND ADMINISTERING CASCADIANFAQ
------------------------------------------------------------------------
To administer the FAQ, browse to http://www.mywebsite.com/admin.php where mywebsite.com is your 
domain name. On installation an administrator account was made with the username and password of admin.  
The first time you log in, I HEAVILY recommend changing this administrator information right after 
installation is finished.  From this area you can add/update/delete categories, Q&As and users for your 
FAQ.

To view the FAQ (and to link to it), link to http://www.mywebsite.com/index.php. It's just that 
easy. :-)

--------------------------------------------------------------------
USING mostpopular.php AND mostrecent.php
--------------------------------------------------------------------
You can use mostpopular.php and mostrecent.php to include a list of your most popular FAQs or your most recently added FAQs to any page on your site.  To use them on another PHP page, put the appropriate line of code where the list should appear (include the <?php and ?> if they are not already there:

	include("mostpopular.php");
	include("mostrecent.php");

You can also use these files in pages that use SSI (Server-Side Includes).  Make sure the file has a .shtml extension, and put the appropriate line where you want that list to appear.

	<!--#include virtual="mostpopular.php" -->
	<!--#include virtual="mostrecent.php" -- >
	
You can use either of these files with the mostpopular and mostrecent options turned off in your config file (so they don't show up in your main FAQ window).  Just make sure you have nummostpopular and nummostrecent set!

--------------------------------------------------------
CASCADIANFAQ VERSION HISTORY
--------------------------------------------------------
Coming in 4.2
	* - Added abililty to assign categories to multiple parents (instead of just one)
	* - For fixes to try and make CascadianFAQ run on IIS systems
	* - Made submitter email a clickable link for easy response
	* - Added option for displaying total question count in FAQ
	* - User-definable question length
	* - User-definable question and answer box sizes
	* - Question now stores submitter info, with option to display
	* - Option to allow question submitters to submit an answer
	* - User choice on question sorting (alphabetically, date submitted, popularity)

4.1
	- Fixed bug in admin url for emails sent about submitted questions
	- Fixed bug in search system that ignore category limits and that hides some cats

4.0
	- Added PostgreSQL support (queries and database connections go through new queries in functions.php which runs the appropriate mySQL or PostgreSQL statement)
	- View count switches between time and times for counts of 1 or more (respectively)
	- Date Formatted on most recently added list switched from database function to PHP for better portability
	- Most Popular and Most Recent only show if there are actual questions in the system (most popular only shows questions in systems that have been viewed at least once)
	- Added multi-level administrators.  Level 1s are omnipotent, while level 0s only have access to assigned categories
	- User Submitted, Unanswered, and Orphan Question options only show up in menus and the admin footer when they have content
	- A "View All" questions option is now available in the question management area
	- Email notification of user submitted questions now includes a link to the CascadianFAQ admin
	- Added transaction for multiple statements (PostgreSQL only)
	- Password vs Confirm Password now works
	- Fixed bug in update user that cleared out the password
	- Renamed ConnectToDatabase function to ConnectToDatabase to avoid conflicts with other scripts (as ConnectToDatabase is a commonly used name for connection scripts)

3.2
	- Major changes to fix issues script had on systems with register_globals off; script now works
	  perfectly whether register globals is on or off

3.1
	- Fixed text explanations on some pages
	- When a cat is deleted, it now shows the name of the cat that was deleted (was showing blank before)
	- When a question is deleted, it now shows which question was deleted (was showing blank before)
	- Fixed DateAdded for user submitted questions (was showing 0000-00-00)
	- Fixed problem with most recent list not always showing the correct number being displayed
	- Most Recently Added now properly ordered by date, orderid to ensure top 5 are shown
	- Question length limit replaced with better JavaScript and added to user side
	- Added returns to all functions to attempt to fix the crashing issue for IIS users
	- Minor bug fixed in delete user page
	- Added some missing stripslashes primarily on the result strings
	- Fixed bug that allowed admins to enter empty questions, which then couldn't be edited.

3.0
	- Added "Most Recent" questions feature (as stand-alone or in the FAQ)
	- Added "Most Popular" questions feature (as stand-alone or in the FAQ); won't show Qs that haven't been viewed at all!
	- Added ability to have users submit new questions to the FAQ, with the option to be emailed when new questions arrive
	- Added orphaned question list to question administrator
	- Added orphaned category list to category administrator; deleting a cat orphans it's subcats now
	- Updated category administration to prevent categories from being assigned to themselves or their subcats (i.e. idiot protection)
	- Created upgrade script to automatically change config files and database
	- Minor cosmetic changes to the admin area.
	- Whereareu rebuilt to be recursive and to allow true unlimited depth
	- In questions and answers that do not already have <p> or <BR> formatting, hard returns replaced with <BR> tags
	- Home link now appears in the footer links, except of course on the index page
	- Spelling and grammar fixes.

2.4
	- Fixed an error in the category "where are you" function
	- Added stripslashes to all data outputs to clean up ' escapes
	- Added username duplication check
	- Give credit link moved to footerlinks function and now appears below the search line
	- Manage Questions area now has you choose a category to work in first, then shows only the questions
	  in that category.  Also returns you to that category while adding/editing and preselects the 
	  chosen category when adding a question.

2.3
	- Renamed config.inc and functions.inc to config.php and functions.php to prevent their being
	  read from a browser window.
	- Added Search Function

2.2
	- Fixed navigation bug link
	- Corrected credit lines

2.1
	- Modified admin script so that navigation links don't show unless someone is logged in
	
2.0 	
	- Converted from ColdFusion to PHP and from Access to mySQL database.  
	- Removed multi-site capabilities (unnecessary feature).
	- Finalized multiple level category feature
	- Questions can now be assigned to multiple categories
	- Improved interface and administration areas
		
1.5 	
	- Table names changed
	- Partial implementation of multiple categories per question feature and 
		multi-level category features

1.0	
	- Added multisite functionality.  



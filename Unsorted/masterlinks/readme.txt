**************************************
** dB Masters Links Directory 3.1.4 **
**************************************


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



*** Requirements:
PHP 4+
MySQL 3+
Unix or Linux Operating System

*** Browser requirements:
Best if using anything over version 5 of the major browsers.
Most others will work, they will just not be as pretty and may not use the style sheets properly.
Please inform me of any browser issues if over version 4 browsers.
Known browser probs:
	Most problems stem from lack of good support for style sheets or JavaScript in older browsers.
	Netscape 4 will not expand/collapse the rating feature, it will always show.
	Various Opera versions will display the rating form while loading and collapse them after fully loading.

*** Version 3.1.4: 
1	Major category deletion fix for sub categories

*** Version 3.1.3: 
1	Fixed rating form validation
2	fixed page jumping when "Rate this website" is clicked
3	added a "back to previous page" link after ratings are submitted.

*** Version 3.1.2: 
1	Fixed a search engine bug
2	Added next/previous links at the bottom of results as well as the top

*** Version 3.1.1: 
1	Fixed the unapproved sites showing up in link count and search queries

*** Version 3.1: 
1	Added link count with each category and sub category
2	Added focus to password field when entery admin page

*** Version 3.0: 
1	Added "Most Popular Links" page to display the link with the most clicks
2	Added "Newest Links" page to display the links most recently added
3	Adding the ability to list the categories links based on different rules 
	rather than just alphabetically
4	Website rating system for visitors
5	Visitors can add an image with their listing if it is enabled in the config.php file
6	XHTML 1.1 coding standard
7	More extensive "skinning" ability via the style sheet. Four color shcemes included
8	Verification of delete commands to protect against accidental deletes (should been in verion 1.0...oh well)
9	A rather extensive re-write to allow it to run even if PHP global variables are not enabled on the server
10	SUB-CATEGORIES!

*** Version 2.1: 
1	Added password protection to the admin section

*** Version 2.0:
1	Extensive re-write eliminating slow meta redirects and adding buffer flushes 
	to allow PHP header redirects, fixes some javascript errors when editing and 
	coding some of the admin section to make it quicker and smaller.
2	Added "click count" for each link in the directory
3	Added a link to allow visitors to notify site admin of dead links

*** Version 1.0:
1	Visitor submitted links send an email alert to admin who then must approve 
	the link before it is publically available
2	Link submitter may set login info for editing listing later in time which is 
	also alerted to the admin, but the link is not removed pending approval
3	Email reminder of login info for all links submitted by given email address
4	full backend administration including link categorization
5	Simple customization via header and footer includes and style sheet

*** Installation:
1	Edit config.php to suit your MySQL and Web Site environment
2	Upload the files to your server
3	Create the MySQL tables by running the install from admin.php
4	Customize the header and footer includes
5	Full color scheme edits (a.k.a. "skins") can be made via the .css file.
	Several skins are included in this zip file.
6	Start submitting



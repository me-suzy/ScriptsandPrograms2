    .: EasyNews by Pierino :.
|===============================|
| http://www.code4fun.org       |
| mail01: info@code4fun.org     |
| mail02: sanculamo@hotmail.com |
|===============================|

:: INDEX ::

- About
- Features
- Requirements
- Files Description
- Installation & Usage


.: about:
--------------------------------------------------------------------------------
 Create and manage your own poll is REALLY easy with Poll4All.
 This free php script lets you create, edit and test your poll (up to 7 answers) through an easy to use web interface.
 You may insert your poll into any page you want simply including two php file. 
 Pol4All show results as colored graphical bars plus votes number and percentage. 
 It  prevent multiple votes using cookies and store all information in a text file so you don't need any database.
 Poll4All is valid XHTML 1.0 code and is viewable with any browser, fully customizable style.
 Note: You can manage and run just one poll, every new poll will replace the older one.
	   

.: Features:
--------------------------------------------------------------------------------
- Display your poll is very simple, just include two php file into your webpage
  and your news will be published automatically.
- No database needed
- Fully customizable look to fit well into any page.
- Up to 7 answers
- Colored graphical bar (random (different)  color function)
- Viewable with any browser (tested on Mozilla/Firefox, Ie, Opera).
- Valid XHTML 1.0 Transitional Code!


.: Requirements:
--------------------------------------------------------------------------------
Web server with PHP (this tool is tested with PHP 5, but should work with earlier and previous versions).



.: Files Description:
--------------------------------------------------------------------------------
- config.php               : Poll4All settings
- admin.php                : the main script, it manage database-file and allow you to create and edit your poll
- check.php                : include this file at the top of your page ( before any "output"), it check if visitor has already voted and, if not, store his vote.
- poll.php                 : include this file  everywhere in your web page to show the poll created
- txt/txtdb.ini.php        : database-file used to store information about poll, don't edit manually!
- includes/configmagik.php : a file reader/writer class by Benny Zaminga
- includes/functions       : some useful functions...



.: Installation & Usage:
--------------------------------------------------------------------------------
1. Download POll4ALL and edit the config.php file to suite your needs.
   Remember to set the poll4all path, relative from page that will include poll.php file!	
		
2. Place the files anywhere in your web directory: 
	All files must be placed in the same directory observing directory structure.
	Set the attributes of the file "txtdb.ini.php" to 666 (meaning read and write access for all parties).

3. Open up your webbrowser to:

   http://www.yourserver.com/poll4all_path/admin.php

   Default user id & password are: admin, admin.
   You can change default values in the "config.php" file.

   The script will check database-file (and if is writable) then will show the admin panel to create and edit your poll.

4. To "insert" created poll in your page (must be php file):

		Include "check.php" file before any "output" (including <html> and <head> tags), this is a protocol restriction.
		The best choise is place <?php include("poll4all_path/check.php"); ?> at the top of your page, at the first line.
		
		Than include "poll.php" file in your page where you want poll will be displayed with the usual syntax:
					
		<?php include("poll4all_path/poll.php"); ?>




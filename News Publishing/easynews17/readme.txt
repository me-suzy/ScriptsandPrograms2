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
- Installation
- Setup
- Know issues


.: about:
--------------------------------------------------------------------------------
EasyNews is a news management php system easy to install and customize.
EasyNEws gives you ability to post, edit and delete news articles on your website
through an easy to use web interface.
You can attach to every news an image and setting up a link to them, if no link will be set, 
script link image (real size) automatically.


.: Features:
--------------------------------------------------------------------------------
- Really easy installation and setup.
- Fully customizable.
- Emoticons.
- BBCODE (bold,italic,underline, break line and link).
- Images upload support with auto resize function.
- Paginator.
- Full-story.
- Viewable with any browser (tested on Mozilla/Firefox, Ie, Opera).
- Valid XHTML 1.0 strict.


.: Requirements:
--------------------------------------------------------------------------------
Web server with PHP (this tool is tested with PHP 5, but should work with earlier and previous versions).
Mysql DBMS.


.: Main Files Description:
--------------------------------------------------------------------------------
- config.php : settings for EasyNews
- setup.php : the main script, it setup database and allow you to manage news
- easynews.php : the file to include in your web page to show news
- preview.php : news preview like will be displayed in your page


.: Installation:
--------------------------------------------------------------------------------
1. Download EasyNews and edit the config.php file to suite your needs.
   (refer to "setup" section below for details).
 

2. Place the files anywhere in your web directory (keep files and folder structure).
   
3. Open up your webbrowser to:

   http://www.yourserver.com/easynews_directory/setup.php

   Default user id & password are: admin, admin.
   You can change default values in the "config.php" file.
	
   Manage your news through the menu on the top...   
   

4. In the page you want the news displayed use this code (must be php file):

        <?php include("easynews_path/easynews.php"); ?>
   

IMPORTANT NOTE:   
You MUST change mode of the directory "images" under EasyNews folder
into permission 777 so you can allow uploading, otherwise you will get 
an error and your uploads will allways fail! 
Changing mode can be done with most of FTP programmes otherwise ask 
to your web space provider.


.: Setup:
--------------------------------------------------------------------------------
EasyNews use a configuration file called "config.php" that you can edit with any text editor.

Before you upload the script into your web space, you have to edit this file to customize some settings.

In order to avoid problems with image and emoticon display is very important to setup the "$enPath" parameter.

The "$enPath" path "instruct" EasyNews on where to find the main script folder, so if you want to move the 
script to a new folder you simply need to update this setting.

You can set it up with absolute path like "http://www.yourdomain.com/easynews/" or, better, using local path.

Using full URL in some server cause auto-resize function to fail so I suggest you to use local path instead.

Example :
If EasyNews files and folders are located into "http://www.yourdomain.com/easynews/" 
and the file that will include the news is "http://www.yourdomain.com/file.php" 
the "$enPath" will be "$enPath='easynews/';" 
and "file.php" will include the news using <?php include'easynews/easynews.php'; ?>.
	


   
.: Know issues:
--------------------------------------------------------------------------------
Filling a word longer than the table results in a table lengthening.

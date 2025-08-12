/************************************************************************/
| Download Sentinel ++                                                     		
| ===========                                                          			
|
|  Capabilities:
|  - Protect your Bandwidth from overusage.
|  - Securly prevent unauthorized downloads via tokens.
|  - Unlimited files and sub-directories. The program will find any file requested.
|  - Log errors, downloads, tokens used, etc.
|  - Easy install program that is fault tolerant and secure.
|
| Copyright (c) Feb 2005 by Kevin Lynn - ihostwebservices.com
| Email: scripts@ihostwebservices.com
|
| This program is free software; you can redistribute it and/or
| modify it under the terms of the GNU General Public License
| as published by the Free Software Foundation; either version 2
| of the License, or (at your option) any later version.
|
| This program is distributed in the hope that it will be useful,
| but WITHOUT ANY WARRANTY; without even the implied warranty of
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
| GNU General Public License for more details: http://www.gnu.org/licenses/gpl.html
| 										 
/************************************************************************/

Version: 2.1.0 Oct 26, 2005

NOTE:
If you do not know which type of PHP you have on your server (Apache Module or CGI), 
don't worry about it, the install program will tell you everything you need to know.



QUICK INSTALL INSTRUCTIONS: (for people using CGI PHP)

1. Create "dsplus" directory under your public html directory
1. Upload install.php to the newly created "dsplus" directory.
2. Browse to install.php ( http://www.example.com/dsplus/install.php ).
3. Follow the instructions.



SLIGHTLY LONGER INSTALL INSTRUCTIONS: (for people using Apache Module PHP)

1. Upload everything in the "upload" folder. 
	a. "dsplus" goes in your public_html directory (/home/example/public_html/dsplus).
	b. "ds_files" goes  - a b o v e - public_html in your Root (/home/example/ds_files).
	 *** ds_files can now go anywhere you like, please read appendix A below.

2. CHMOD all uploaded directories and files to 777.
3. Browse to install.php ( http://www.example.com/dsplus/install.php ).
4. Follow the instructions.



HOW TO USE:

The installation creates everything you need to start using DSPLUS right away. All you need to do is:

1. Copy over all the files you wish to protect to the "files" directory. Sub-directories OK.
2. Make a link to the file like this - "http://www.example.com/dsplus/m.php?p=filename.exe"
	Where filename.exe is the name of the file to be downloaded.
3. You can customize the .html and .css files in the dsplus folder to match your site or add advertising, whatever you wish.
Just keep intact any <text /> that looks like that. Those are crucial to the proper operation of the program.
4. The config file is located here: /ds_files/scripts/ds_config.php - In there you will find many user settings you can change to your hearts content.
Short descriptions of how to use each option are written next to the option. This file is also created by the Install with common default settings.

**
New to V2.1.0 is a download page (mplus.php) that lists all files and sub directories with filesize and date generated automatically. 
The script produces valid xhtml/css unordered lists with all the information. You can also add Authors and Descriptions to each file with the admin page (ds_Admin.php).
This is a powerful tool to help easily organize and maintain your public downloads. It is also very moddable for anyone who knows CSS or does a search on working with unordered lists and CSS.
Usage for this page is all automatic, no need to do anything but place your file on your server in the ds_files/files/ folder.
**
**
Also new is an admin page where you can choose to either edit your authors and descriptions or re-install. Located in the dsplus directory.
**



SLIGHTLY MORE IMPORTANT INFO FOR ANYONE READING DOWN THIS FAR:

Installation is simply upload and chmod some files. The script will tell you of any errors.
If one exists, you are told what it is by the installer and how to fix it.

The install procedure for installation on Apache Module PHP will prevent Root from becoming owner of the new files created by the script.
Several galleries programs have this problem and once Root owns the file only a call to the admin of the server can delete it or change it (typically).
So this little issue is avoided with this script. The downside is a slightly longer and more involved install process.

I want to make it clear.. Other sites CANNOT deep link to your files. You never have to move your files to another directory again.
In fact with Version 2.1.0 it's not possible for anyone to take a file you don't want them to. Not through link sharing, bot attacks or deeplinking.

The download splash screen (on m.php) can be edited to hold your own logo(s) and other information. Thus ensuring all users know who is hosting the file.
Other sites can link to this page if they wish but your site will always be credited as the download site.

Making links to files is easy, its the same link for all files plus the filename. You can edit all your links by simply cutting and pasting the same line over and over again.
No need to remember or lookup the path to a file anymore.
Or you can skip all that work and just use mplus.php a new powerful method for displaying all your files and maintaining your files with ease.

The download file size WILL BE LIMITED by your interval length if it is too short. The formula is as follows:
Maximum Download amount, divided by maximum download time, times the interval length.

For example, the script default is 18000000000 (18GB) / 2592000 (30 days) * 10800 (3 hours). Which equals 75MB
That means in the first 3 hours of the month you will only be able to download a file as big as 75MB (based on calculation above).
HOWEVER, if no one downloads anything, the original 75MB of available bandwidth carries over to the next interval. So in the next 3 hours,
you would be able to server up 150MB of bandwidth, or 1 file that is 150MB in size.
Now you might be thinking, hey I can only download a single 150MB file in 6 hours?? 
That's correct... you're the one trying to server large files and who only has 18GB that has to last a month. Well, this is how it lasts out the month.

There are lots of useful options and info in the config file, read it through.


See the web site for the changelog and the plans for the next version. http://dsplus.sourceforge.net/changelog.php

Also visit the forums and give me some feedback or ask questions. http://sourceforge.net/forum/?group_id=139901
Or send me an email. I don't get many, so expect a response :)

For a full feature list visit the homepage. http://dsplus.sourceforge.net/index.php



APPENDIX A

WHY
The directory "ds_files" and it's sub-directories need to be protected and secured against viewing by the public. Thus the default recommended location is
understandable "outside" the public html area. Normally this is above "public_html", "html" or "htdocs" in the directory structure.
However some people do not have the luxury of placing files or creating directories outside the web visible area. 
It is for this reason that the option to put these files in a different location has been added.
BUT, I have not left you unprotected. There is an .htaccess file placed in the ds_files directory that will protect all of your files.
Now, if you do not have the ability to have .htaccess files in your account, you are out of luck. Find a new host. *cough* me *cough*.
It should be noted that the .htaccess file has nothing to do with the download or token operation, 
and is only used to prevent the ds_files directory and sub-directories from being accessible from the web.

HOW
Do NOT put a leading forward slash "/"
Create the directory "ds_files" where you want it.... if the script can't find it based on what you typed in, try again.



REQUIREMENTS:

Server side:
OS: nix* based (Linux, FreeBSD, OpenBSD, etc)
PHP: >= V4.3.0 
MySQL: Optional >= 3.23 (uses flatfiles so database not necessary)

Client side:
Browser: W3C Standards compliant: 
	     - Linux :Konqueror or Firefox
	     - MAC: Safari
	     - Windows: Opera, Firefox, or any Gecko based browser. Ok ok It will work with IE as well, but it has a lot of security holes and I don't recommend it.
	     

THANKS
- Special thanks to Christian Heilmann (http://icant.co.uk) for allowing re-distribution of his pde.js file.
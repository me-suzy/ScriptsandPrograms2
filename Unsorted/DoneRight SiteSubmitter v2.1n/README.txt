#*********************************************************#
#   Program Name    	: Site Submitter
#   Program Version 	: 2.1
#   Program Author  	: Done Right
#   Home Page       	: http://www.done-right.net/
#   Retail Price    	: $74.99 United States Dollars
#   xCGI Price		: $00.00 United States Dollars
#   Nullified By    	: cHARLIeZ
#*********************************************************#

Any attempt to redistribute this code is strictly forbidden and may result in severe legal action.
Copyright © 2000 Done-Right. All rights reserved.


Table of Contents
-----------------------------------------------------------------------------
1)  Package
2)  System Requirements
3a) Installation for Unix Servers
3b) Installation for NT Servers
4)  Technical Support
-----------------------------------------------------------------------------


1)  Package
-----------------------------------------------------------------------------
After you have unzipped the file sitesubmitter.zip, you should get the following files:

admin.cgi          		- personal admin script
customize.cgi			- part of admin script
email.cgi			- part of admin script
engines.cgi			- part of admin script
sitesubmitter.cgi     		- main script
Readme.txt			- this file

template/semod.cgi		- module script
template/start.txt		- search template
template/submit.txt		- display results template
/images            		- folder containing images
-----------------------------------------------------------------------------


2)  System Requirements
-----------------------------------------------------------------------------
In order to run the script properly, your webserver should contain:
- Perl 5
- Libwww Module
- If you are running NT, you will also need the libnet to send email.

If your server does not contain these programs, the script will not work.
Please contact your webhosting server in order to get these programs installed.
-----------------------------------------------------------------------------


3a)  Installation for Unix Servers
-----------------------------------------------------------------------------
1. Change the first line of each of the following files to match your servers location of perl:
   If your location is #!/usr/bin/perl, you do not need to edit these files.
   (In most cases it will look like this #!/usr/local/bin/perl or this #!/usr/bin/perl)
	admin.cgi
	customize.cgi
	email.cgi
	engines.cgi
	sitesubmitter.cgi

2. Upload every file in ASCII format into your cgi-bin (preferably in a folder called "sitesubmit")

3. Upload images directory to the location of your other html/image files.  The images directory should not be uploaded
   in your cgi-bin

4. Chmod The following files:
   admin.cgi - 755

5. Run the admin.cgi script to setup your variables and you're done.
   To access the actual submitter script, run sitesubmitter.cgi.
-----------------------------------------------------------------------------


3b)  Installation for NT Servers
-----------------------------------------------------------------------------
1a. Change the first line of each of the following files to match your servers location of perl:
   If your location is #!/usr/bin/perl, you do not need to edit these files.
   (In most cases it will look like this #!/usr/local/bin/perl or this #!/usr/bin/perl)
	admin.cgi
	customize.cgi
	email.cgi
	engines.cgi
	sitesubmitter.cgi

1b. Open the following files and enter in the direct data path to your sitesubmitter directory in the variable "$path".
    Ex. $path = "/www/root/website/cgi-bin/sitesubmitter/"; # With a slash at the end as shown
	admin.cgi
	customize.cgi
	email.cgi
	engines.cgi
	sitesubmitter.cgi

2. Upload every file in ASCII format into your cgi-bin (preferably in a folder called "sitesubmit")

3. Upload images directory to the location of your other html/image files.  The images directory should not be uploaded
   in your cgi-bin.

4. Run the admin.cgi script to setup your variables and you're done.
   To access the actual submitter script, run sitesubmitter.cgi.
-----------------------------------------------------------------------------


4)  Technical Support
-----------------------------------------------------------------------------
If you are having any problems with your script, please visit your own support page located at your admin.cgi script.
Your login information can be found in the email you received when you purchased sitesubmitter.
Or, you can contact us at support@done-right.net
-----------------------------------------------------------------------------


MetaSearch by Done-Right Scripts
README
Version 2.0
WebSite:  http://www.done-right.net
Email:    support@done-right.net

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
After you have unzipped the file your script, you should get the following files:

metasearch.cgi			- main script
admin.cgi			- admin script
customize.cgi			- part of admin script
settings.cgi			- part of admin script
Readme.txt			- this file

LWP/Parallel/Protocol.pm	- LWP Parallel Module
LWP/Parallel/UserAgent.pm	- LWP Parallel Module
LWP/Parallel/Protocol/http.pm	- LWP Parallel Module

-----------------------------------------------------------------------------

ONE OF THE FOLLOWING MODULES:

Auctions			- Auction Search Folder
Books				- Book Search Folder
Electronics			- Electronic Search Folder
Forums				- Forum Search Folder
Hardware			- Hardware Search Folder
Jobs				- Job Search Folder
MP3s				- MP3 Search Folder
Music				- Music Search Folder
News				- News Search Folder
Software			- Software Search Folder
Videos				- Video Search Folder
Web				- Web Search Folder

-----------------------------------------------------------------------------


2)  System Requirements
-----------------------------------------------------------------------------
In order to run the script properly, your webserver should contain:
- Perl 5
- Libwww Module

If your server does not contain these programs, the script will not work.  Please contact your webhosting server in order
to get these programs installed.
-----------------------------------------------------------------------------


3a)  Installation for Unix Servers
-----------------------------------------------------------------------------
1. Change the first line of each of the following files to match your servers location of perl:
   If your location is #!/usr/bin/perl, you do not need to edit these files.
   (In most cases it will look like this #!/usr/local/bin/perl or this #!/usr/bin/perl)
	admin.cgi
	customize.cgi
	metasearch.cgi
	settings.cgi

2. Upload every file & directory in ASCII mode into your cgi-bin (preferably in a folder called "metasearch")

3. Chmod The following file:
   admin.cgi - 755

4. Run the admin.cgi script to setup your variables and you're done.
   To access the actual search script, run metasearch.cgi.
-----------------------------------------------------------------------------


3b)  Installation for NT Servers
-----------------------------------------------------------------------------
1a. Change the first line of each of the following files to match your servers location of perl:
   If your location is #!/usr/bin/perl, you do not need to edit these files.
   (In most cases it will look like this #!/usr/local/bin/perl or this #!/usr/bin/perl)
	admin.cgi
	customize.cgi
	metasearch.cgi
	settings.cgi

1b. Open the following files and enter in the direct data path to your metasearch directory in the variable "$path".
    Ex. $path = "/www/root/website/cgi-bin/metasearch/"; # With a slash at the end as shown
	admin.cgi
	customize.cgi
	metasearch.cgi
	settings.cgi

2. Upload every file & directory in ASCII mode into your cgi-bin (preferably in a folder called "metasearch")

3. Run the admin.cgi script to setup your variables and you're done.
   To access the actual search script, run metasearch.cgi.
-----------------------------------------------------------------------------


4)  Technical Support
-----------------------------------------------------------------------------
If you are having any problems with your script, please visit your own support page located at your admin.cgi script.
Your login information can be found in the email you received when you purchased metasearch.
Or, you can contact us at support@done-right.net
-----------------------------------------------------------------------------

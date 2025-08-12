Affiliate Program by Done-Right Scripts
Bid Search Engine Add-on
README
Version 1.0
WebSite:  http://www.done-right.net
Support:  http://www.donerightscripts.com/cgi-bin/members/support.cgi?file=affiliateprogram

Any attempt to redistribute this code is strictly forbidden and may result in severe legal action.
Copyright © 2001 Done-Right. All rights reserved.

If you are looking for a more detailed readme file, you can visit the support section at:
http://www.donerightscripts.com/cgi-bin/members/support.cgi?file=affiliateprogram


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

admin.cgi			- admin script
click.cgi			- click through script
customize.cgi			- part of admin script
view.cgi			- part of admin script
signup.cgi			- member signup script
members.cgi			- member admin script
Readme.txt			- this file

template/[templates]		- 11 template files in .txt format
-----------------------------------------------------------------------------


2)  System Requirements
-----------------------------------------------------------------------------
In order to run the script properly, your webserver should contain:
- Perl 5
- Done-Right's Bid Search Engine Script available at http://www.donerightscripts.com/bidsearch.shtml
- If you are running NT, you will also need the libnet to send email.

If your server does not contain these programs, the script will not work.  Please contact your webhosting server in order to get these programs installed.
-----------------------------------------------------------------------------


3a)  Installation for Unix Servers
-----------------------------------------------------------------------------
1. Change the first line of each of the following files to match your servers location of perl:
   If your location is #!/usr/bin/perl, you do not need to edit these files.
   (In most cases it will look like this #!/usr/local/bin/perl or this #!/usr/bin/perl)
	admin.cgi
	customize.cgi
	members.cgi
	signup.cgi
	view.cgi

1b. Code added by TNO - Open the following files and enter in the full url where you will upload the done-right images to on your server. in the variable "$tno".
    Ex. $tno = "http://www.yourdomain.com/images"; # Do not add a trailing slash
	admin.cgi
	customize.cgi
	view.cgi

2. Upload every file & directory in ASCII mode into your Bid Search Engine folder under a folder called 'affiliate'.
   Ex. /cgi-bin/bidsearch/affiliate

3. Chmod The following file:
   admin.cgi - 755

4. Run the admin.cgi script to setup your variables and you're done.
   To access the sign up script, run signup.cgi.

5. If you bought the bid search engine before September 26, 2001, you will need to update it.  You can download the latest
   version from your admin or by visiting http://www.donerightscripts.com/download.shtml.  You really only need to upload
   the search.cgi file.
-----------------------------------------------------------------------------


3b)  Installation for NT Servers
-----------------------------------------------------------------------------
1. Change the first line of each of the following files to match your servers location of perl:
   If your location is #!/usr/bin/perl, you do not need to edit these files.
   (In most cases it will look like this #!/usr/local/bin/perl or this #!/usr/bin/perl)
	admin.cgi
	customize.cgi
	members.cgi
	signup.cgi
	view.cgi

1b. Open the following files and enter in the direct data path to your affiliate directory in the variable "$path".
    Ex. $path = "/www/root/website/cgi-bin/bidsearch/affiliate/"; # With a slash at the end as shown
	admin.cgi
	click.cgi
	customize.cgi
	members.cgi
	signup.cgi
	view.cgi

1c. Code added by TNO - Open the following files and enter in the full url where you will upload the done-right images to on your server. in the variable "$tno".
    Ex. $tno = "http://www.yourdomain.com/images"; # Do not add a trailing slash
	admin.cgi
	customize.cgi
	view.cgi

2. Upload every file & directory in ASCII mode into your Bid Search Engine folder under a folder called 'affiliate'.
   Ex. /cgi-bin/bidsearch/affiliate

3. Run the admin.cgi script to setup your variables and you're done.
   To access the sign up script, run signup.cgi.

4. If you bought the bid search engine before September 26, 2001, you will need to update it.  You can download the latest
   version from your admin or by visiting http://www.donerightscripts.com/download.shtml.  You really only need to upload
   the search.cgi file.
-----------------------------------------------------------------------------


4)  Technical Support
-----------------------------------------------------------------------------
If you are having any problems with your script, please visit the members support area located at:
http://www.donerightscripts.com/cgi-bin/members/support.cgi?file=affiliateprogram
-----------------------------------------------------------------------------

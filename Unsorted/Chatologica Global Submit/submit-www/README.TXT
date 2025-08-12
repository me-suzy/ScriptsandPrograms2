#########################################################################################
# Chatologica GlobalSubmit - cgi script version 1.0
# Author: Daniel Dinev; All rights reserved (c) 2000; http://www.chatologica.com/
#########################################################################################


REQUIREMENTS:
------------

  perl version 5
  Windows or UNIX. Unix is recommended.

  Note: On Windows platform server-side http connections may not timeout as set in 
  Admin Panel, and wait longer - up to 45-50 seconds if there is DNS/connect problems.


INSTALLATION:
------------

  1. EXTRACT THE FILES FROM ARCHIVE:

  The Linux unzip command is:
	unzip -a global-submit-1.0.zip -x

  2. SETTING THE RIGHT PERL PATH:

	If your Perl version 5 in not in /usr/bin/perl you have to modify the first lines
	of the following scripts:

     		nph-submit.pl
		fetch.pl
		admin/delete-log.pl
		admin/index.pl
		admin/show-log.pl

	Simply replace /usr/bin/perl with the real path of your Perl.
 	For example Hypermart.com users usually use the following first line:
	#!/usr/local/bin/perl

  3. TRANSFER THE SOFTWARE TO YOUR SERVER:

	This software has 2 directories: submit-cgi and submit-www. Transfer submit-cgi directory 
      	under your cgi-bin. Transfer submit-www directory to the directory where you keep your
	html documents. Always use ASCII text transfer mode because the perl scripts and the  
	html page are text files.

  4. SETTING THE RIGHT FILE PERMISSIONS:
	
	Set full permission 777 for:
		submit-cgi/logs
	Set world-write permission 666 for:
		submit-cgi/parameters.pl
	Set world-execute and non-write permission 755 for:
	  	submit-cgi/nph-submit.pl
		submit-cgi/fetch.pl
		submit-cgi/admin/delete-log.pl
		submit-cgi/admin/index.pl
		submit-cgi/admin/show-log.pl
		submit-cgi/admin	
		submit-cgi/
        All of the rest directories must be accessible from the web server (chmod 755).
	All of the rest files must be readable from the web server (chmod 644).

	For example You can set the above permissions with three command lines:
		chmod 755 . */ *.pl admin/*.pl
		chmod 777 logs
		chmod 666 parameters.pl
 	or run a shell script:
		sh set-permissions.sh
	
	Then point your browser to submit-cgi/admin/index.pl file to access your Admin Panel.
	More details on this software are available in submit-www/manual.htm
	To access the manual call URL like http://yourdomain/path/submit-www/manual.htm
	Demos are available at submit-www/demo.htm and submit-www/fetch.htm  Please, note
	that you may need to modify these files to make them pointing to the right perl script
	location.



	
	
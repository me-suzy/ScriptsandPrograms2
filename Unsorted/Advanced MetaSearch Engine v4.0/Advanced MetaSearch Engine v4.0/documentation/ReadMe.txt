#              --------------------------------------
#                   Advanced MetaSearch Engine
#                     Version 4.0 (standard)
#                        File: ReadMe.txt
#              --------------------------------------
#                     All Rights Reserved 
#                   (c) CurryGuide.com, 2001.
#
#
#
# IMPORTANT:
#
# This program, its components, subroutines are property of CurryGuide.
# As agreed, you are only allowed to use this program under the following
# conditions:
#
# 1. This File and other related script and templete files 
# (hereinafter mentioned as 'script') remains under the copyright of 
# CurryGuide which includes the programming design, architecture, 
# individual operational blocks, subroutines etc. You, the buyer is 
# quoted as the 'buyer' hereinafter.
# 
# 2. The 'script' as a whole or any part of it may NOT be resold, 
# copied, transferred to another party or used in any other program 
# for ANY purposes.
#
# 3. The 'buyer' is only allowed to update or modify individual elements
# or programming blocks or subroutines where they are clearly marked 
# as modifiable or commented as modifiable.
#
# 4. E-Mail Technical support is provided under the terms defined in the 
# 'Package Description' on the website.
#
# 5. Under no circumstances CurryGuide shall be held liable to ANY 
# loses, fines, judiciary proceedings directly or indirectly related 
# to purchase or usage of this 'script'. The 'buyer' uses this program 
# entirely on his/her own risk.
#
# 6. One single package entitles the 'buyer' to use it on one (1) single
# individually identifiable domain or sub-domain ONLY.
#
# 7. The 'buyer' must clearly identify his/her requirements and clarify 
# all technical compatability issues before the actual purchase. 
# The 'buyer' also understands that NO refunds can be claimed.
#
# 8. Headers and CopyRight information at the beginning of each file MUST
# remain intact. Under no circumstances you are allowed to alter or
# modify or remove them.
#
# 9. CurryGuide reserves the sole right to review and/or change the 
# 'Purchase and Usage Conditions'. Current 'Purchase and Usage 
# Conditions' will be available on a CurryGuide website.
#
# ---------------------------------------------------------------------
#                                        Ref: AM4.00(std)-01-2001-DOC01


In case of any problem/error, please read the 'Problems' Section before 
contacting Technical Support.




!!!   Special Instructions  !!!
----------------------------------------------------------------------
IF YOU ARE UPGRADING FROM A PREVIOUS VERSION, PLEASE READ THE ATTACHED 
'Upgrade.txt' FILE BEFORE MAKING ANY INSTALLATION !!!

This does not apply to new customers.
-----------------------------------------------------------------------      



TABLE OF CONTENTS
-----------------------------------------------------------------------

   	1. The Package
	
	2. System Requirements
	
    	3. Installation
	
	4. Usage
		4.1 - Search-Box
		4.2 - Advanced/Boolean Query Syntax
		4.3 - Logging
		4.4 - Cache Cleaning
	
	5. Customizations
		5.1 - Global Customizations
		5.2 - Search Result Customizations
	
	6. Problems
	
	7. Technical Support
	
---------------------------------------------------------	



________________________________________________________________________

1. The Package
________________________________________________________________________

The package usually comes in a compressed zip file. Before you can 
install it on your server you will need to uncompress the package 
and upload all the files as packed in this zip file. Please keep the 
directory structure as is (most unzip programs does it automatically);
as this will make the installation easier.

ALL files should be uploaded in ASCII FORMAT.

________________________________________________________________________

2. System Requirements
________________________________________________________________________

Fully working web server with Perl version 5.004 or later (standard 
distribution). Advanced MetaSearch should work fine on all well known 
OS (Unix, NT, Windows 2000 and Unix flavour OS like: Linux). The script 
should also work fine on Mac machines (perhaps flocking set to Off).


________________________________________________________________________

3. Installation
________________________________________________________________________

->	(A)
	Before installation, please Check the Perl path in the main script 
	file 'cgsearch.cgi'. It is the first line of code in the file. 
	Initial setting is as follows:
	
	#!/usr/local/bin/perl
	Change it to CORRECT path to Perl on your server, if path to Perl is 
	different on your server.
	
	NOTE: On most servers path to Perl is either as shown above or any 
	of the following:
	#!/usr/bin/perl  
	or 
	#!/bin/perl
	etc. 
	
	On some Windows machines you may also find your path to Perl is 
	something like:
	#!d:/perl/bin/perl
	
	However, it can be quite different on your server. Consult your 
	server administrator if not sure.
	
NOTE: You MUST use a 'Plain Text Editor' to make this path 
modification (or other customizations in the Configuration files 
as mentioned in the 'Internal Customization' section (see below).
If you do not have any commercial 'Plain Text Editor', you can use 
the one that came with your OS software. 
Example: 'Notepad' on Windows. (Do not use the 'Write' or 'WordPad' 
program or other programs unless you are sure it is a Plain Text 
Editor; as they might corrupt your Perl script by adding unnecessary 
line breaks or other control characters).
	

	
>>	3.1. Quick Installation
	------------------------

	(A)
	Create a new directory named 'cgsearch' within the 'cgi-bin' 
	directory on your server. We will call this 'Main Script 
	Directory' where your 'Advanced MetaSearch Engine' will be 
	installed. 

NOTE: generally speaking, 'cgi-bin' is a sub-directory on your 
server where you are allowed to install and run executable 
programs and scripts. It may also be called 'cgi-local' or 
'scripts' or something else on your server. We will refer to it as 
'cgi-bin' for ease of explanation.

	Upload all the files as they are in this package keeping the 
	directory structure. (Take note of the sub-directories within).
	If you are using any latest FTP clients, you may find that it 
	will allow you to upload (copy) the entire 'cgsearch' directory 
	and it's contents from your local machine to the server with 1 
	(one) single action. You may not even need to create the 
	'cgsearch' directory yourself.
	
->	(B) 
	Set permissions to the main script file as shown below:
	
		cgsearch.cgi  - chmod 755   (-rwxr-xr-x)


->	(C) 
	Set permission to the following sub-directories as shown:
	
	sub-directory:   storage  	-chmod 777  (drwxrwxrwx)
	sub-directory:   log-count  	-chmod 777  (drwxrwxrwx)
	sub-directory:   log-keyword  	-chmod 777  (drwxrwxrwx)
	
NOTE: 
Usually there is NO need to set ANY permission on Windows NT machines. 

On Unix or Linux machines you will need to set permission to the above 
files and sub-directories as mentioned. Permission to ALL other script 
files are usually set to chmod -644 automatically. In case you get any 
problem, simply set permission to all other script files as:
	chmod -644 (-rw-r--r--).


->	(D)
	Now, unzip the Engine-Module files and upload your engine-modules 
	to the 'engs-web' sub-directory.
	
	Depending on your OS, you may need to set permission to these 
	engine-module files as shown below:
	
	chmod -644 (-rw-r--r--).


->	(E)
	Now, call the script from a browser using the following URL:
	http://your-server.com/cgi-bin/cgsearch/cgsearch.cgi
	
	If everything is OK, it should be working just fine. Now, continue 
	reading the following sections for details on customization and usage.
	
	4. Usage 
	5. Customizations
	--------------------------------------------------------------




>>	3.2. Detailed Installation
	--------------------------
	
->	(A)
	Create a new directory named 'cgsearch' within the 'cgi-bin' 
	directory on your server. We will call this 'Main Script 
	Directory' where your 'Advanced MetaSearch Engine' will be 
	installed. 

NOTE: generally speaking, 'cgi-bin' is a sub-directory on your 
server where you are allowed to install and run executable 
programs and scripts. It may also be called 'cgi-local' or 
'scripts' or something else on your server. We will refer to it as 
'cgi-bin' for ease of explanation.

->	(B) 
	Create the following 3 sub-directories with-in this newly 
	created 'cgsearch' directory and set permission as shown.
	
	storage
	log-count
	log-keyword
	
	Set permission to these new 3 sub-directories as shown below:
	sub-directory:   storage  	-chmod 777  (drwxrwxrwx)
	sub-directory:   log-count  	-chmod 777  (drwxrwxrwx)
	sub-directory:   log-keyword  	-chmod 777  (drwxrwxrwx)
NOTE: 
Usually there is NO need to set ANY permission on Windows machines.
In fact you may find that there is no such thing as setting permission 
on Windows machines. In such a case it is ESSENTIAL that the 'cgsearch' 
directory is Read, Write and Execute enabled.
	
->	(C) 
	Now, create another sub-directory named 'conf' with-in the 
	'cgsearch' directory.
	
	conf		(sub-directory)

->	(D)
	Upload the 'web.pl' file to this 'conf' sub-directory. Set 
	permission to this web.pl file as shown below:
	
	web.pl     - chmod 644   (-rw-r--r--)

NOTE: This is the Configuration setting file for Web-Search. You 
will set/modify ALL internal customizations for Web-Search using 
this configuration file (web.pl).

->	(E) 
	Create another sub-directory named 'template' with-in the 
	'cgsearch' directory.
	
	template	(sub-directory)

->	(F)
	Upload the 'web.html' file to this 'template' sub-directory.
	Set permission to this Template file (web.html) as follows:
	
	web.html     - chmod 644   (-rw-r--r--)

NOTE: This is the Template file for Web-Search. You will customize 
this Template file to set overall HTML design of your Web-Search option.

->	(G) 
	Create another sub-directory named 'engs-web' with-in the 
	'cgsearch' directory.
	
	engs-web	(sub-directory)

->	(H)
	Unzip the Engine-Module packages and upload ALL the Web-Search 
	engine-module files to this 'engs-web' sub-directory 
	(Example: Yahoo.pl, Infoseek.pl etc. as delivered)
	
	Set permission to all these engime-module files as follows:
	
	Yahoo.pl		- chmod 644   (-rw-r--r--)
	Infoseek.pl		- chmod 644   (-rw-r--r--)

NOTE: Engime-Module collection may vary. The above Yahoo.pl and Infoseek.pl 
are mentioned for explanation only.

->	(I)
	Now upload the following 2 files to the 'Main Script Directory' 
	(cgsearch) and set permission as shown below:
	
	cgsearch.cgi		- chmod 755   (-rwxr-xr-x)
	global_config.pl	- chmod 644   (-rw-r--r--)

NOTE: the 'cgsearch.cgi' is the main script file and must have 
permission setting (chmod 755). global_config.pl is the 'Global 
Configuration' file. Setting on this Global Configuration file 
will affect overll running of your search engine; including 
any other Specialty-Search options (Auction, News, Job, MP3 etc.) 
you may want to add any time later.


INSTALLATION IS COMPLETE IF YOU HAVE COMPLETED ALL THE ABOVE MENTIONED 
STEPS CORRECTLY. 
Your Main Script Directory 'cgsearch' now should look like:


Directory:

cgsearch
		 cgsearch.cgi      - (main script file)
		 global_config.pl  - Global Configuration File
				
	Sub-Directories:		 
		
		storage            - Empty
		log-count          - Empty
		log-keyword        - Empty
		 
		conf               - contains Web-Search configuration  
		                     file 'web.pl'				
		 
		template           - contains Web-Search Template 
		                     file 'web.html'				
		
		engs-web           - initially empty. BUT, you will 
		                     need to upload your Web-Search 
		                     Engine-Modules after the initial 
		                     installation is complete.
		                     NOTE: DO NOT FORGET TO DOWNLOAD 
		                     YOUR Engine-Modules and upload them 
		                     to this sub-directory before testing 
		                     your search engine.



Now, you can test your Advanced MetaSearch Engine by calling it from 
your browser using an URL similar to this:

	http://your-server.com/cgi-bin/cgsearch/cgsearch.cgi
	
	Now, type some 'Keywords' and try search using your Advanced 
	MetaSearch Engine.


________________________________________________________________________

4. Usage
________________________________________________________________________


>>	4.1. Search-Box
             -----------

To put your Advanced MetaSearch Engine on your web-site (Home page) 
you will need to add Search-Box (Search Form/s) on your web pages 
where your visitors can type their Keywords and make search.

'Search-Box' is a HTML FORM which take the inputs and submit the request 
to your Advanced MetaSEarch Engine. Be careful to check the 'action' 
parameter in your search FORM, it should direct to the URL (NOT PATH) 
of the main search script (cgsearch.cgi) on your server.

Example:
<form method="get" action="/cgi-bin/cgsearch/cgsearch.cgi">
OR use the full URL
<form method="get" action="http://your.server.com/cgi-bin/cgsearch/cgsearch.cgi">

	
	Search-Box (Search-Form) inputs:
	--------------------------------
	
a)	INPUTNAME: query
	generally this should be a 'TEXT' box. It takes the Keywords
	(Query) and passes the value to the main search script. 
	NOTE: This INPUT is REQUIRED.
	

This is the minimum Form Inputs required to create a Search-Box. 
NOTE: Do NOT forget that a Form should also have a 'submit' button.

You can also add a number of other Inputs on your Search-Box. The 
following the list of Inputs you can add:


b)	INPUTNAME: match
	this can be a Radio button or Select box or something similar that lets
	choose an option. This INPUT is optional. It can be used to select a 
	matching criteria.
		
		Values:
		all    - tells the script to find results that Match 
		         all the Keywords.
		any    - tells the script to find results that Match 
		         atleast any of the Keywords.
		phrase - tells the script to find results that Match 
		         all the Keywords in exact order (as a phrase).
		
		If no value is passed OR this INPUT is not used, the first
		value ('all') will be assumed by default.

c)	INPUTNAME: pp
	this can be a Radio button or Select box or something similar that lets
	choose an option. This INPUT is optional. It can be used to select how 
	many results should be shown on each Result page.
		
		Values:
		can be any unsigned integer value greater than 1 (8, 10, 16 etc.)

		NOTE: If no value is passed OR this INPUT is not present, the Default
		value will be assumed. 
		(See the '$default_per_page' parameter in the 'Section A' of 
		your Search Configuration file located in the 'conf' sub-directory)




d)	INPUTNAME: sum
	this can be a Radio button or Select-box (List) or something 
	similar that lets choose an option. This INPUT is optional. 
	It can be used to control how the individual results should 
	be displayed.
		
		Values:
		n:n  - will show FULL Description
		(An Empty value will also show FULL Description)
		
		n:y  - will show Resource Description only. No source details 
		will be shown.

		y:y  - will show Titles only.
		
		If no value is passed OR this INPUT is not used, the Default
		value 'Show FULL Description' will be assumed.

TIP:
----
To show 'Titles Only' at any time without any visual use of this 
Form-Input; pass the approptiate value as a hidden Form-Imput.
Example:
<input type="hidden" name="sum" value="y:y">


	

e)	INPUTNAME: to
	This is an OPTIONAL parameter and this can be used as a Radio 
	button or Select-box (List) or something similar that lets 
	choose or simply pass a value. It can be used to assign a timeout  
	value (in seconds) for the current search.
		
		Values:
		can be any unsigned integer value beginning from 1 and 
		upto 20.
		
		NOTE: If the passed Timeout value is less than Default Timeout
		value than the user input will be ignored and Default Timeout 
		will be used to make the current search. 
		(See '$default_timeout' parameter in the 'Section A' of your 
		Search Configuration file located in the 'conf' sub-directory)
		
		
f)	INPUTNAME: sd
	This is an OPTIONAL parameter and this can be used as a hidden, 
	Radio button or Select-box (List) or something similar that lets 
	choose or simply pass a value. It can be used to control how 
	the individual results should sorted.
		
		Values:
		s  - will show ALL results without any duplicate removal. 
		ALL results from ALL engines will be shown with appropriate  
		sorting (Mixed or Grouped. See the 'sr' Input below).
		
		An Empty value (or no value) will remove duplicates and 
		additionally perform a Combined Ranking. 
	

g)	INPUTNAME: sr
	This is an OPTIONAL parameter and this can be used as a hidden, 
	Radio button or Select-box (List) or something similar that lets 
	choose or simply pass a value. It can be used to control if 
	the results should displayed sorted or Grouped.
		
		Values:
		Empty ("") or Not used - will show ALL results sorted in 
		combined/mixed format.
		
		g - will show results grouped by Source (major engines).
	


>>	4.2. Advanced/Boolean Query Syntax
             -----------------------------

Advanced MetaSearch Engine can handle Advanced/Boolean query syntax 
appropriately. This support is embedded in the individual engine-module 
files.

Usually, engine-modules for the general web-searching comes with support 
for such advanced/boolean syntax. Adavnced MetaSearch Engine should be 
able to handle following syntaxes/connectors:

+, -, "", AND, OR, NOT, and, or, not

NOTE, that Advanced/Boolean syntax will override usual 'all', 'any' or 
'phrase' settings passed through Form Input 'match' (or default matching 
option 'all' - all the words).


	A) Phrase Search
	Quoted queries/Keywords will be treated as a PHRASE.
	Example: "computer software" 
	will always try to match the Exact query/text as entered.
	
	B) AND Search ('+')
	Putting a '+' (plus sign) will make sure that this word MUST be matched.
	Example: +computer +software 
	will always try to make sure that both words are matched.
	
	C) NOT/Exclude Search ('-')
	Putting a '-' (minus sign) will make sure that this word must NOT be matched.
	Example: +computer -software 
	will always try to fetch documents that contain the word 'computer'
	BUT does NOT contain the word 'software'.
	
	D) OR Search ('OR' or 'or')
	Putting 'OR' (or 'or') between words will force an 'ANY WORD' matching.
	Example: 
	computer OR software
	computer or software
	are treated the same way and will find documents that contain at least 
	any of these words (computer, software).
	
	E) AND Search ('AND' or 'and')
	AND Search can also be invoked using 'AND' (or 'and'). This is same as 
	using '+'.
	Example: 
	computer AND software
	computer and software
	will try to fetch documents containing both 'computer' and 'software'.

	F) NOT/Exclude Search ('NOT' or 'not')
	This is same as using '-'.
	Example: 
	computer NOT software
	computer not software
	will try to fetch documents containing the 'computer' BUT without 
	the word 'software'.


NOTE: Boolean syntax/connectors are applied to those engine-modules where 
it is supported and may not work as effective on some major engines. This 
is due to the features available on specific major engines and does not 
always depend on Advanced MetaSearch Engine.


>>	4.3 - Logging
              --------

There are 2 types of built-in logging support in Advanced MetaSearch Engine.

(i) Search Counter - counts the total number of search requests served. The  
                     search count log is kept on a monthly basis in a log file 
                     named after the name of the month.
                     These search count log files are located in the 'log-count' 
                     sub-directory.
                     You can download and view the search-count files using any 
                     text editor.
                     You do NOT need to manually create or modify these log-files.
                     They are automatically created and maintained by the script.


(i) Keyword-Logger - Logs the clean Keywords from each new search request. The  
                     keyword-log is stored in a single DAT (.dat) file 
                     named after Search-Specialty (web.dat for the web search).
                     These keyword log files are located in the 'log-keyword' 
                     sub-directory.
                     You can download and view the log files using any 
                     text editor.
                     You do NOT need to manually create or modify these log-files.
                     They are automatically created and maintained/rotated by the 
                     script.
                     When 'Keyword matching Search' is enabled, these files are 
                     used to find the matchings.
                     You can set a maximum number of log records by setting an 
                     appropriate value in the '$Max_Keyword_Records' parameter 
                     in the Global Configuration file 'global_config.pl'.
                     The default value is 500.


>>	4.3 - Cache Cleaning
              --------------

Advanced MetaSearch Engine cache some temporary results for a certain 
period of time. These temporary data files are stored in the 'storage' 
sub-directory. This temporary cache is automatically purged by the script 
with an interval set by the '$Cache_Lifetime' parameter in the Global 
Configuration file (global_config.pl). The default should work just fine.

________________________________________________________________________

5. Customizations
________________________________________________________________________


>>	5.1 - Global Customizations
              ---------------------

ALL GLOBAL SETTINGS ARE CONTROLLED BY A GLOBAL CONFIGURATION FILE:

global_config.pl

This file is located in the main script directory (where the cgsearch.cgi is located).
Unless there is any specific need; you may not have to touch this Global 
Configuration/Setting file at all.


>>	5.2 - Search Result Customizations
              ----------------------------
              
Please see the attached 'Customization.txt' for detailed instructions on 
Template customizations and Internal customizations using Search 
Configuration File/s.


________________________________________________________________________

6. Problems
________________________________________________________________________

->	(A) 
	Internal Server Error - 500

	Make sure you upload all files in ASCII mode (not BINARY or AUTO).
	
	Also make sure you set the path to Perl correctly (see the above 
	section on 'Installation')

->	(B) 
	Check the 'Detailed Installation' section in case you missed 
	something or forgot to upload any file (or set permission). 
	Also make sure all sub-directories (with-in the 'Main Script 
	Directory') are created (and permissions set correctly if 
	required). 
	



->	(C)
	No results Found
	
	If the script runs but NO results are shown, make sure you 
	created the 'storage' sub-directory (and permission set as 
	chmod -777 if required).
	
->	(D)
	If the problem persists, set Debug Mode to 'on' and run the script 
	again. The script will try to report on possible Errors/Problems.
	You can set Debug Mode using the '$Debug_Mode' parameter in the 
	Global Configuration file 'global_config.pl'.
	To do so: open the Global Configuration file 'global_config.pl' 
	with a Plain Text Editor and assign value 'on' to the 
	'$Debug_Mode' parameter as mentioned. Example: 

	$Debug_Mode = 'on'; # -- Debug Mode is set to On.
	
	IMPORTANT !!!
	Once the problem is solved and your Advanced MetaSearch Engine is 
	working, you should set the Debug Mode to Off again.

	
	If this is a problem related to path or something similar, set 
	path to your 'Main Script Directory' exclusively.
	To do so: open the Global Configuration file 'global_config.pl' 
	with a Plain Text Editor and assign path value to the 
	'$Script_Directory' parameter as mentioned. Example: 
	
	$Script_Directory = "/doc_root/YourSite/cgi-bin/cgsearch";
	
	on Windows machines you path to 'Main Script Directory' may 
	look like something similar to:
	$Script_Directory = "d:/YourSite/cgi-bin/cgsearch";
	IMPORTANT !!!
	take note that even on Windows machines path MUST NOT be set 
	using backward slash '\'. Use forward slash '/' as shown above.
	
	IMPORTANT !!!
	make sure you set the path to the 'cgsearch' directory on your 
	own server. The above are just examples.
	
->	(E) 
	Please contact Technical Support if the problem cannot be 
	solved.
	

________________________________________________________________________

7. Technical Support
________________________________________________________________________

Please visit the User-Area for further information on your 'Advanced 
MetaSearch Engine'.

In case of technical problems, please contact support@curryguide.com 
with following details:
	(i)	Your Customer-ID
	(ii)	URL of your 'Advanced MetaSearch Engine' where it can be 
		accessed from a browser.
	(iii)	Your question or a clear description of the problem.


Technical Support:	support@curryguide.com
User-Area:		http://services.curryguide.com/meta_prog/userarea.html
Product-site:		http://services.curryguide.com/meta_prog/

________________________________________________________________________
                                          Ref: AM4.00(std)-01-2001-DOC01

No part of this document shall be reproduced, copied, published in 
any other media (including ftp sites, web documents, discussion-groups) or 
transferred to a third party without exclusive written permission.

All Rights Reserved, 2001.
CurryGuide.com (A division of 'Highfield Business Corporation')
Web:		http://services.curryguide.com/
Sales:		sales@curryguide.com
--------------------------------------------------------------------
Bid Search Engine by Done-Right Scripts
MySQL Version
README
Version 2.0
WebSite:  http://www.done-right.net
Support:  http://www.donerightscripts.com/cgi-bin/members/support.cgi?file=bidsearchengine

Any attempt to redistribute this code is strictly forbidden and may result in severe legal action.
Copyright © 2002 Done-Right. All rights reserved.

If you are looking for a more detailed readme file, you can visit the support section at:
http://www.donerightscripts.com/cgi-bin/members/support.cgi?file=bidsearchengine


Table of Contents
-----------------------------------------------------------------------------
1)  Package
2)  System Requirements
3a) Installation for Unix Servers
3b) Installation for NT Servers
4)  Mod_Perl Installation
5)  FastCGI Installation
6)  Customization
7)  Upgrading To Version 2.0
8)  Technical Support
-----------------------------------------------------------------------------


1)  Package
-----------------------------------------------------------------------------
After you have unzipped the file your script, you should get the following files:

admin.cgi			- admin script
addons.cgi			- part of admin script
customize.cgi			- part of admin script
excel.cgi			- for mass uploads with excel spreadsheet
functions.cgi			- basic functions
functions_mysql.cgi		- mysql functions
functions_text.cgi		- text functions
members.cgi			- members admin
mysql.dump			- used to setup mysql database
Readme.txt			- this file
search.cgi			- search script
settings.cgi			- part of admin script
view.cgi			- part of admin script
signup.cgi			- member signup script

template/Web.cgi		- module script
template/wordfilter.txt		- filters out bad words
template/bulksubmission.zip	- template for excel spreadsheet
template/[templates]		- 29 template files in .txt format

LWP				- LWP Parallel Module Folder
-----------------------------------------------------------------------------


2)  System Requirements
-----------------------------------------------------------------------------
In order to run the script properly, your webserver should contain:
- Perl 5
- Libwww Module OR IO::Sockets Module

OPTIONAL:
- If you want to use MySQL, you will need the DBI Module.
  If you do not have this, you will have to use the text database.
- If you are running NT, you will also need the libnet to send email.
- If you want to use the excel spreadsheet mass upload feature, you need the 'Spreadsheet::ParseExcel' module.
-----------------------------------------------------------------------------


3a)  Installation for Unix Servers
-----------------------------------------------------------------------------
1. Change the first line of each of the following files to match your servers location of perl:
   If your location is #!/usr/bin/perl, you do not need to edit these files.
   (In most cases it will look like this #!/usr/local/bin/perl or this #!/usr/bin/perl)
	addons.cgi
	admin.cgi
	customize.cgi
	excel.cgi
	members.cgi
	search.cgi
	settings.cgi
	signup.cgi
	view.cgi

2. Upload every file & directory in ASCII mode into your cgi-bin (preferably in a folder called "bidsearch")

3. Chmod The following file:
   admin.cgi - 755

- OMIT THE FOLLOWING STEP (STEP 4) IF YOU WANT TO USE THE TEXT DATABASE INSTEAD OF THE MYSQL DATABASE -
4. Create the mysql database by doing the following:
	-Log into telent
	-Navigate to your bidsearch directory through telnet
	-Type in the following command to create the database:
	 mysqladmin -u [username] -p[password] create bidsearchengine

	-Type in the following commmand to create the tables for the database:
	 mysql -u [username] -p[password] bidsearchengine < mysql.dump

   Note: The above will create a database called 'bidsearchengine' on the 'localhost' machine.
         Make sure you substitute your mysql username and password for [username] and [password].  If you do
	 not know your mysql login, please contact your web hostings tech support.
- OMIT THE ABOVE STEP (STEP 4) IF YOU WANT TO USE THE TEXT DATABASE INSTEAD OF THE MYSQL DATABASE -

5. Run the admin.cgi script to setup your variables and you're done.
   To access the actual searching script, run search.cgi.

6. If you plan to use the excel spreadsheet bulk upload feature:
   -Upload the file bulksubmission.zip (found in template folder) to the directory that holds your html files (outside the cgi-bin).
   -Modify the bulk template page by specifying the correct URL to the file bulksubmission.zip.
-----------------------------------------------------------------------------


3b)  Installation for NT Servers
-----------------------------------------------------------------------------
1. Change the first line of each of the following files to match your servers location of perl:
   If your location is #!/usr/bin/perl, you do not need to edit the files.
   (In most cases it will look like this #!/usr/local/bin/perl or this #!/usr/bin/perl)
	addons.cgi
	admin.cgi
	customize.cgi
	excel.cgi
	members.cgi
	search.cgi
	settings.cgi
	signup.cgi
	view.cgi

1b. Open the following files and enter in the direct data path to your bidsearch directory in the variable "$path".
    Ex. $path = "/www/root/website/cgi-bin/bidsearch/"; # With a slash at the end as shown
	addons.cgi
	admin.cgi
	customize.cgi
	excel.cgi
	functions.cgi
	functions_mysql.cgi
	functions_text.cgi
	members.cgi
	search.cgi
	settings.cgi
	signup.cgi
	view.cgi

2. Upload every file & directory in ASCII mode into your cgi-bin (preferably in a folder called "bidsearch")

- OMIT THE FOLLOWING STEP (STEP 3) IF YOU WANT TO USE THE TEXT DATABASE INSTEAD OF THE MYSQL DATABASE -
3. Create the mysql database by doing the following:
	-Log into telent
	-Navigate to your bidsearch directory through telnet
	-Type in the following command to create the database:
	 mysqladmin -u [username] -p[password] create bidsearchengine

	-Type in the following commmand to create the tables for the database:
	 mysql -u [username] -p[password] bidsearchengine < mysql.dump

   Note: The above will create a database called 'bidsearchengine' on the 'localhost' machine.
         Make sure you substitute your mysql username and password for [username] and [password].  If you do
	 not know your mysql login, please contact your web hostings tech support.
- OMIT THE ABOVE STEP (STEP 3) IF YOU WANT TO USE THE TEXT DATABASE INSTEAD OF THE MYSQL DATABASE -

4. Run the admin.cgi script to setup your variables and you're done.
   To access the actual searching script, run search.cgi.

5. If you plan to use the excel spreadsheet bulk upload feature:
   -Upload the file bulksubmission.zip (found in template folder) to the directory that holds your html files (outside the cgi-bin).
   -Modify the bulk template page by specifying the correct URL to the file bulksubmission.zip.
-----------------------------------------------------------------------------


4)  Mod_Perl Installation
-----------------------------------------------------------------------------
If your server supports mod_perl, you may choose to run the bid search script under mod_perl to make the searches
faster and use less CPU.  To use mod_perl, please do the following:

1.  Open the following files and enter in the direct data path to your bid search directory in the variable "$path".
    Ex. $path = "/www/root/website/cgi-bin/bidsearch/"; # With a slash at the end as shown
	addons.cgi
	admin.cgi
	customize.cgi
	excel.cgi
	functions.cgi
	functions_mysql.cgi
	functions_text.cgi
	members.cgi
	search.cgi
	settings.cgi
	signup.cgi
	view.cgi

2.  Open the file search.cgi.  Uncomment the #use lib line and fill in the "use lib" path.  This is the same
    path used for the $path variable except there is no forward slash at the end.
    Ex. use lib "/www/root/website/cgi-bin/bidsearch"; # Without a slash at the end as shown

3. Upload every file & directory in ASCII mode into your cgi-bin (preferably in a folder called "bidsearch")

4. Run the admin.cgi script to setup your variables
-----------------------------------------------------------------------------


5)  FastCGI Installation
-----------------------------------------------------------------------------
If your server supports FastCGI, you may choose to run the bid search script using FastCGI to make the searches
faster.  To use fastcgi, please do the following:

1. Make sure FastCGI is installed on your server.  If it is not, you can download the module from:
   http://cpan.valueclick.com/modules/by-module/FCGI/FCGI-0.65.tar.gz

2. Open search.cgi and change the first line that points to perl to the location of your fast cgi compiler.
   Usually the default fast cgi compiler is located at: #!/usr/local/bin/perl

3. Open search.cgi and set the '$fastcgi' variable to 1.
   Example: my $fastcgi = 1;

4. Run the search.cgi script to see if it works.
-----------------------------------------------------------------------------


6)  Customization
-----------------------------------------------------------------------------
You can easily customize this script by doing the following:
1) In your admin area, click customize to change the look of the html pages and the text in the email messages.

2) In your admin area, click configure variables where you can modify the certain variables and make the script
   compatible with a 3rd part merchant to process orders.

3) In your admin area, click settings where you can do such things as set your default options.

4) Use SSI commands to display parts of the script in your html pages.  Just remember that most servers only allow you
   to use SSI commands within a .shtml file.

   To display popular searches, use:
   <!--#include virtual="search.cgi?tab=popular&ssi=1" -->

   To display bidded listings for a certain term, use:
   <!--#include virtual="search.cgi?tab=displaybids&perpage=10&keywords=keywords here" -->
   You can add the following fields in order not to display certain items:
   &number=false
   &url=false
   &title=false
   &descrip=false
   &printurl=false
   &source=false
   &morelikethis=false

5) You can display the results in XML by adding the tag &xml=1 to your search URL.
   Example: search.cgi?keywords=[keywords+here]&xml=1
-----------------------------------------------------------------------------


7)  Upgrading To Version 2.0
-----------------------------------------------------------------------------
If you have a current member database and wish to upgrade this database to work with version 2.0, follow these instructions.
Note: You should perform these tasks before installing version 2.0.
      Also, it is required that you install version 2.0 in a new directory rather than overwriting the files in your current
      directory.  If you are satisfied with the installation, you can simply rename this new directory to your current directory.


UPGRADING YOUR TEXT DATABASE
1) Download your current 'data' folder and upload it to the directory where you will be installing version 2.0.

2) Run admin.cgi to configure your variables.


UPGRADING YOUR MYSQL DATABASE
1) Open the file mysql_upgrade.dump and replace '[bidsearchengine]' contained on the second and third lines with the name of your database:
   Example: ALTER TABLE [bidsearchengine].users ADD company VARCHAR(50) NOT NULL;
            ALTER TABLE [bidsearchengine].users CHANGE card_number card_number VARCHAR(100) DEFAULT NULL;
   So if the name of your database is bidsearch, this line should look like:
   ALTER TABLE bidsearch.users ADD company VARCHAR(50) NOT NULL;
   ALTER TABLE bidsearch.users CHANGE card_number card_number VARCHAR(100) DEFAULT NULL;

   If you cannot remember the name of your database, log into your current admin area and you will notice it listed under
   the configure variables section.

2) Run this file by typing the following:
	-Type in the following commmand to create the tables for the database:
	 mysql -u [username] -p[password] [bidsearchengine] < mysql_upgrade.dump
         
         Note: Replace '[bidsearchengine]' with the name of your database.  For example, if your database was named 'bidsearch',
               you would type in:
               mysql -u [username] -p[password] bidsearch < mysql_upgrade.dump

3) Run admin.cgi to configure your variables.

-----------------------------------------------------------------------------


8)  Technical Support
-----------------------------------------------------------------------------
If you are having any problems with your script, please visit the members support area located at:
http://www.donerightscripts.com/cgi-bin/members/support.cgi?file=bidsearchengine
-----------------------------------------------------------------------------

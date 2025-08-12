######################################################################
Active PHP Bookmarks - lbstone.com/apb/

Filename:   README.txt
Authors:    L. Brandon Stone (lbstone.com)
            Nathanial P. Hendler (retards.org)

2003-03-31  Changes made for 1.1.02. [LBS]
2002-02-11  Upgrade instructions almost added. [NPH]
2002-01-30  Rewritten for Version 1 release. [NPH]
2001-07-24  File created. [LBS]
######################################################################

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

 1. WHAT IS APB?
 2. REQUIREMENTS
 3. QUICK INSTALL
 4. MORE INSTALL INSTRUCTIONS
 5. UPGRADING
 6. DOCUMENTATION
 7. SUPPORT
 8. LIMITATIONS / BUGS
 9. AVAILABLILTY
10. LICENSE
11. COPYRIGHT


1. WHAT IS APB?
---------------

Active PHP Bookmarks (APB) is a web-based program that allows you to store your
bookmarks and display them in many useful ways. It will sort your bookmarks with
usability in mind, keeping often-used bookmarks at your fingertips. It has a
bookmark search, private/public bookmarks, nested groups, usage rankings,
popularity sorting, and a quick add feature.


2. REQUIREMENTS
---------------

APB requires PHP, MySQL, and a webserver.  It is known to work with MySQL
3.23.41 and PHP 4.0.6, but may work with older versions as well.

This version of APB assumes that:
-   PHP's "magic_quotes" option is on.
-   PHP's "register_globals" option is on.

Newer versions of APB are expected to allow for greater flexibility with these
settings.


3. QUICK INSTALL
----------------

1)  Unzip and untar apb-X.X.XX into the document root of your webserver.
    It will untar into a folder named apb-X.X.XX.

2)  Rename the apb directory from "apb-X.X.XX" to "bookmarks".  If you want it
    to be named something else, you'll have to edit the file apb.php.

3)  Use database_schema.sql to setup your database:

    bash$ mysql -u username -p database_name < database_schema.sql

4)  Edit apb.php and put in your database username, password, and database name.

5)  Go to http://yoursite.com/bookmarks/ and follow the instructions.


4. MORE INSTALL INSTRUCTIONS
----------------------------

The database_schema.sql contains the SQL to create your APB database.  All of
APB's database tables begin with apb_ so they can co-exist in an already created
database, or you can create a new database specificaly for APB.

If you don't know how to use database_schema.sql, here's an example of what
you'll want to run on the command line:

mysql -u username -p database_name < database_schema.sql

APB untars into the apb-X.X.XX/ directory.  If it isn't already in your web
server's document root, you'll need to put it there.

APB expects to be in a directory called bookmarks/  You can make it anything you
want, but you'll have to edit the apb.php file if you don't use "bookmarks".

You'll have to edit the first few lines of php in the apb.php to have the proper
database connection variables.

Go to http://yoursite.com/bookmarks/ and create a user.

The apb.php also contains user configurable variables that you may want to
change.


5. UPGRADING
------------

If you want to just do a quick patch from version 1.1.01, you only have to copy
the following listed files.  (If you're upgrading from an older version than
1.1.01, it is recommended that you update all the files.)

-   apb.php
-   apb_common.php
-   templates/head.php
-   templates/foot.php


6. DOCUMENTATION
----------------

http://lbstone.com/apb/


7. SUPPORT
----------

http://lbstone.com/apb/


8. LIMITATIONS / BUGS
---------------------

APB Version 1.X.XX isn't fully ready for multiple users.  It is well on it's way,
but we still have a few things to do.



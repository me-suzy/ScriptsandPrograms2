**********************************************************
*                                                        *
*               Proverbs PHP Web Calendar                *
*                                                        *
*                    Version 1.2.2                       *
*                                                        *
**********************************************************


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



This is a customizable web calendar developed using PHP and powered by MySQL. The calendar is viewed
in month format with a popup window detailing the events of each day as they are clicked on. 

For the most recent version of this application goto http://www.proverbs.biz

ACKNOWLEDGMENTS:
A special thank you to Marion Heider of clixworx.net for designing the initial alternate beginning day
of the week for the calendar view.

FILE LIST:
.dbaccess.inc
caladmin.php
calender.php
config.inc.php
db_mysql.inc
layout.inc.php
schedule.php
setupdb.php

INSTALLATION

1) Edit the "layout.inc.php" file to include your website information, color choices and optional title image.
Descriptions are provided for appropriate fields.  All color values must be in hexadecimal format without the
# symbol.
2) Edit the "config.inc.php" file to include the information used to attach to your MySQL database.
3) Copy all files in the file list to a single directory on your website.
4) Open your web browser to point to "setupdb.php" on your website. This file should create all the tables
needed in your MySQL database for the calendar application.  This also creates a default username and password
for calendar administration.  This default user can be removed once a new username is created.
5) Delete the "setupdb.php" file from your website. This file is only needed for the initial table creation.


REVISION HISTORY

1.2.2 - 12/06/02 - Fixed am to pm bug.

1.2.1 - 09/17/02 - Fixed problem with time being recorded incorrectly in some versions of MySQL.

1.2.0 - 05/29/02 - 1) Added variable $time_format in the "layout.inc.php" file.  This variable has
                   two settings "24" and "12".  Setting the variable to "12" will force display of time
                   on the schedule in a 12 hour AM/PM format.  Setting the variable to "24" or any other
                   setting will display the schedule in the standard 24 hour format.
                   2) Added variable $start_day in the "layoung.inc.php" file.  This variable is used
                   to set the starting day of the week on the calendar display.  The variable settings
                   range from 0 to 6 and correspond to Sunday to Saturday respectively.  Settings outside
                   the scope will set the calendar to begin with Sunday.  Alternate beginning day of the 
                   week for calendar view was created by Marion Heider of clixworx.net.
                   3) Updated the "setupdb.php" file to check for successful table creation into the
                   database and display results accordingly.

1.1.0 - 03/04/02 - 1) Added variable $time_zone in the "layout.inc.php" file.  Setting this variable
                   to "auto" will force display of the servers time zone on the schedule page.  All
                   other settings will display the variable's text; i.e. $time_zone = 'EST' will
                   display 'EST' after each hour block regardless of the servers time zone.
                   2) Added javascript onClick event to the radio buttons when selecting an entry
                   to edit in the "CalAdmin.php" Edit Existing page that automatically fills in the 
                   current values for that entry into the appropriate text boxes.  All single and 
                   double quotes are stripped from the original text due to the combination of PHP and 
                   Javascript.

1.0.1 - 02/04/02 - 1) Changed addition of recurring events with settings of(Event Day): 
                   "All(Weekly)DAY's of the Month of..." to always be a weekly event.
                   2) Added a strip_tag statement to remove unwanted HTML or PHP code from
                   any event being entered; allows <B>, <U>, <I> and <FONT> tags still.

1.0.0 - 12/31/01 - Initial release.  Written in PHP and powered by MySQL.
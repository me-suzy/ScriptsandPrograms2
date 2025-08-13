------------------------------------------------------
  ONLINE WEB INSTALLATION DIRECTIONS:
------------------------------------------------------

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


RECOMENDED:

1) Upload all of the files extracted from the zip or tar 
   file onto your web host.  

2) Make sure owner/group for your virutalhost or webserver 
   is set to you. If not or you do not know what I am talking 
   about change the permissions of the files named: config.php 
   and livehelp.js to 777 to allow the web setup program 
   access to change thoes files. If you need more help on this
   just open up the setup.php page and more directions 
   will appear.

3) Open up the setup.php file in your web browser like so:
   http://www.yourdomainname.com/livehelp/setup.php

4) follow the online installation..

5) IMPORTANT: 
   After the setup is finished change the permissions of 
   config.php and livehelp.js to either 755 or 400 
   (Depending on your server setup) 
  

------------------------------------------------------   
MANUAL INSTALLATION DIRECTIONS:
------------------------------------------------------
DO THIS ONLY IF YOU CAN NOT RUN THE SETUP.php...

1) Open up livehelp.js and change the line that 
   reads var WEBPATH = "WEB-PATH"; to be 
   the path to your livehelp installation.
   
2) Open up config.php and change the configuration 
   settings to match your configuration (mysql user, admin etc..)

3) Create a database on your Mysql server and 
   import tables.sql 

4) Log in as 
   username: admin
   password: admin   
   YOU SHOULD CHANGE THIS AFTER LOGGING IN BY CLICKING PREFERENCES
   

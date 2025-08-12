D-Ring README


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

--------------------------------------------------------------
1. Introduction
2. License
3. Configuring & Installing
4. Troubleshoting
5. Contacting Me
--------------------------------------------------------------
1. Introduction
--------------------------------------------------------------
D-Ring is a simple web ring script written in PHP4 that allows
you to run a web ring on your site, without the need of third
party servers, like webring.org or yahoo.  It is meant to be
easy to install and use, though you will be required to set a
couple of variables.

NOTE: No user authentication or protection is in place for the
admin section of the script, so you will need to either write
your own, or use a .htaccess/.htpasswd combo, which is what I
did.
--------------------------------------------------------------
2. License
--------------------------------------------------------------
D-Ring is distributed under the terms and conditions of the
GNU General Public License (GPL) version 2.0 (or, at your
discretion, any later version). You are free to moify and
redistribute copies of this script in accordance with the
terms contained within the GPL.
--------------------------------------------------------------
3. Configuring & Installing
--------------------------------------------------------------
In order to configure the script to work on your server, first
unzip all of the files into a folder on your hard drive. Next,
open config.php and set the variables as instructed.

Note: If you change the values of $submitdir and $memdir,
      they MUST be absolute paths. Also, you must manually
      create these directories. (This is done so that the
      script may be used on servers which run PHP in safe mode)

Upload all of the files to your website, and CHMOD the
"incoming" directory and the "members" directory to 777.

That's it!  If you paths and permissions have been set properly
then you're in business and your webring is now ready to use.

You may wish to take steps to password protect the index.php
file in the admin/ directory. I highly reccommended that you do
so!
--------------------------------------------------------------
4. Troubleshooting
--------------------------------------------------------------
"HELP ME PLZ!! IT WON'T SEND MAIL!!!!!!!!!!!!!!"
Calm down, calm down, calm down!

Try replacing PHP's built-in mail function with the PHP SMTP
mail module included in the zip. "smtpmail.php" instructions
are in the commented lines at the top of the file.


--------------------------------------------------------------
Thank you, that is all.
--------------------------------------------------------------
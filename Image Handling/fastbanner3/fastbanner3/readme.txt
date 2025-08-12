##############################################################################
# FastBanner v2.0                                                            #
# Author:  Scott Hough                                                       #
# Website :  http://www.designersblock.tk                                    #
#                                                                            #
#                                                                            #
# This script is provided in an 'as is' condition. I hope you will find it   #
# useful. I have made every effort to ensure that the script works properly  #
# on all platforms and the installation is as quick and painless as possible.#
# However, I can not be held responsible for any loss or damaged caused      #
# either directly or indirectly by this script. USE IT AT  YOUR OWN RISK!    #
#                                                                            #
#                                                                            #
#                                                                            #
# You are free to modify this script, however if you do drop me a line. I    #
# really would like to see what you have done.                               #              
##############################################################################
#                                                                            #
# HISTORY:                                                                   #
#                                                                            #
# Written on July 29, 2002.  I came across a script to make banners called   #
# EZBanner. I used it and it showed potential and I was looking for a little #
# more than what EZBanner offered, so I too it upon myself to modify the     #
# code and come up with QuickBanner. One week after I finished QuickBanner I #
# found http://www.quickbanner.com and I decided to change the name to       #
# FastBanner. On November 2nd I decided to offer FastBanner to the public.   #
#                                                                            #
# EZBanner's Project Page is at http://ezbanner.sourceforge.net/             #
##############################################################################

##############################################################################
# REQUIREMENTS                                                               #
##############################################################################

1. PHP (It should work with any version on any platform. It seems to work 
   faster on Apache.)
2. The GD library with png support installed.
3. A brain (Most of you already have it installed.) Sorry, bad joke :)

##############################################################################
# INSTALLATION                                                               #
##############################################################################

Installation is fairly straightforward:
index.php  - This is the script with the form.
banner.php  - The script. This does all the work!
picker.html - The file that loads the Color Picker Applet.
RGBPicker.class - The Color Picker Applet.
banners/ - This is the directory that stores the user created banners.
banner/ - This directory contains the banner backgrounds. If you add some
          banners make sure they are png files.
counter.txt    - The banner number file.  This is used so each banner has
                 a unique name.

TO INSTALL EZBANNER:

Unzip the files and directories into a directory made just for FastBanner

In the FastBanner directory on your server, chmod the directory called banners
to 777.

Chmod the counter.txt in the banners directory to 666.

##############################################################################
# USING FastBanner                                                           #
##############################################################################

FastBanner is simple to use.

To execute the script:
http://www.yourserver.com/fastbanner/

##############################################################################
# SUPPORT                                                                    #
##############################################################################

If you are having problems with this script, please post a
message at the FastBanner Support Forum at http://www.designersblock.tk.

Before you email for support make sure that your server supports PHP.  Most
of the free ones do not.

That's it!

##############################################################################


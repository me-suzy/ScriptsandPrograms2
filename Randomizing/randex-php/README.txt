#######################################################################
#                            Randex 1.21                              #
#              By Thomas Tsoi <cgi@cgihk.com> 2001-05-12              #
#           Copyright 2001 CGIHK.com.  All rights reserved.           #
#######################################################################
#                                                                     #
# CGIHK.com:                                                          #
#   http://www.cgihk.com/                                             #
# Support:                                                            #
#   http://www.cgihk.com/forum/                                       #
# ThomasTsoi.com                                                      #
#   http://www.ThomasTsoi.com/                                        #
# Winapi.com                                                          #
#   http://www.winapi.com/                                            #
# Astronomy.org.hk                                                    #
#   http://www.astronomy.org.hk/                                      #
#                                                                     #
# ################################################################### #
#                                                                     #
#        You can distribute this script and use it freely as          #
#          long as this header is not edited in the script.           #
#                                                                     #
# ################################################################### #
#                                                                     #
#              Knowledge is worth a billion bucks                     #
#                 But it shouldn't cost you a single buck             #
#                                                                     #
#######################################################################

Revision History:

    2001-05-12 : Version 1.21 (PHP) Released
    - Another fun

    1999-10-03 : Version 1.21 (Python) Released
    - For fun

    1999-08-xx : Version 1.21 (Perl) Released
    - Bug Fixes

    1999-08-xx : Version 1.2 (Perl) Released
    - Written the entire script for efficiency

    1999-07-31 : Version 1.1 (Perl) Released
    - Added IP logging
    
    1999-07-26 : Version 1.0 (Perl) Released
    - Just released ^^"

=======================================================================

TABLE OF CONTENTS
    1. About the script
    2. Registration
    3. Installation
    4. Using Randex
    5. Problems
    
=======================================================================

1. About the script
-------------------------------------------------
Yet another random HTML displayer with IP logging so a visitor won't 
see the same piece of HTML when he reloads the page.


2. Registration
-------------------------------------------------
No registration is required at the moment. Randex is a freeware and is
free for all individuals, non-profit making organizations and profit
making companies. However, if you are using Randex on your web site and
you like it, I will be most pleased if you drop me a line telling me
your comments and suggestions.

3. Installation
-------------------------------------------------
Here is a list of files that came with Randex.

      File             Desc      chmod

     randex.php    Main script    644                           
     randex.txt    Database       644
     data.txt      Data logging   666
     ip.txt        IP logging     666

Upload all these files to a directory on your server and set the 
permissions according to the above table. It should be done then.
If you wish to specify a different path to the three text files,
modify them in randex.php

4. Using Randex
-------------------------------------------------
In randex.txt, you can have as many pieces of codes/text/HTML as you like,
by using the separator [%%BREAK%%]

Have your pages named with the extension .php (or whatever exntension used
for PHP pages on your server, such as phtml or .php3).
Insert <? require("randex.php"); ?> to where you want the random text 
appears in your page. Done.

5. Problems
-------------------------------------------------
If you have any problems during the setup, please visit the support
forum of our website at:

    http://www.cgihk.com/forum/

Good Luck!

Thomas
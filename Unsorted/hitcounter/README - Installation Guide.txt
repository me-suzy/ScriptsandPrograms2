CJ Hit Counter V1.0
============================================================

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

Files Just Downloaded
~~~~~~~~~~~~~

The contents of "CJ Hit Counter.zip" ........

1.	count.php
2.	hitcounter.dat
3.	Readme.txt
4.	Copying.txt (GPL)
4.	and just incase you forgot where it came from.... an Internet Shortcut  :D

Files Required
~~~~~~~~

1.	count.php  (comes within zip file)
2.	hitcounter.dat (comes within zip file)
3.	A page to display the hitcounter (usually index.html, index.shtml, index.php, index.htm)   
	
	» note:  the page must be renamed to index.php for this counter to work, to do this open the file in 	
	» notepad and save it as "index.php" - easy!

Installation Help
~~~~~~~~~~

please note » additional help is included in the editable files.

1.  	Upload files count.php and hitcounter.dat to you webserver, CHMOD both files to 777
2.  	Rename your html page to 'pagename.php' (described above)
3.  	Enter the code where you want the hitcounter to display:

	<?php
	include("count.php");
	?>

4.	Upload the page.
5.	The hit counter will display
6.	Change the look of your counter by editing the HTML code in "count.php".
	
	» note:  make sure you keep the php echo of "hitcounter.dat" intact.

Thats All!
~~~~~~


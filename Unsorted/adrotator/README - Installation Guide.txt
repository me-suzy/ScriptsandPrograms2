CJ Ad Rotator V1.0


#########################################################
#                                                       #
# PHPSelect Web Development Division.                   #
# http://www.phpselect.com/                             #
#                                                       #
# This script and all included modules, lists or        #
# images, documentation are copyright 2004              #
# PHPSelect (http://www.phpselect.com/) unless          #
# otherwise stated in the script.                       #
#                                                       #
# Purchasers are granted rights to use this script      #
# on any site they own. There is no individual site     #
# license needed per site.                              #
#                                                       #
# Any copying, distribution, modification with          #
# intent to distribute as new code will result          #
# in immediate loss of your rights to use this          #
# program as well as possible legal action.             #
#                                                       #
# This and many other fine scripts are available at     #
# the above website or by emailing the authors at       #
# admin@phpselect.com or info@phpselect.com             #
#                                                       #
#########################################################


Files Just Downloaded
~~~~~~~~~~~~~

The contents of "CJ Ad Rotator.zip" ........

1.	adrotate.php
2.	displayad.inc
3.	linkcode.inc
4.	Example Include.php
5.	Readme.txt
6.	Copying.txt (GPL)
7.	and just incase you forgot where it came from.... an Internet Shortcut  :D

Files Required
~~~~~~~~

1.	adrotate.php  (comes within zip file)
2.	displayad.inc  (comes within zip file)
3.	linkcode.inc  (comes within zip file)
4.	A php page to display the adrotate.php
	
	» note:  the page must be renamed to something.php for this counter to work, to do this open the file in 	
	» notepad and save it as "whatever.php" (some servers require .php3 or .php4 extensions)

Installation Help
~~~~~~~~~~

please note » additional help is included in the editable files.

1.  	Open up linkcode.inc and replace what is in there with as many links as you like, remember to save them in the format:

	<a href="http://www.advertising.com/link0001"><img src="http://www.advertising.com/image00001.jpg"></a>
	<a href="http://www.advertising.com/link0002"><img src="http://www.advertising.com/image00002.jpg"></a>
	etc...

2.  	Open up adrotate.php an edit the variables if you want to:

	$directory = "/enter/your/website/root/";								//  Webserver path to your adrotator files
	$linkfile = "linkcode.inc";										//  The Link Code file
	$adcountfile = "displayad.inc";									//  The Ad Display file
	$pos = "left";												//  Postion of banner tag (left, center or right)
	$help_msg = "no";											//  Display a "Help Message" - "Yes" to enable!
	$help_message = "Please support this site by clicking on the banner below";		//  Enter "Help Message" here

3.	Add the following code to within your webpage where you want the banner adverts to appear (see Example Include)

	<? include("adrotate.php"); ?>

	Note: if you have put the files in a seperate folder you will need to link to them eg.  <? include("ads/adrotate.php"); ?>

4.	Upload the files:  adrotate.php, linkcode.inc, displayad.inc (to a suitable directory) and your webpage.php	

5.	CHMOD the file displayad.inc to 777

6.	Surf to your webpage and a banner should be displayed that changes when you click refresh (if you have more than one banner in linkcode.inc!)

7.	Note: Because you declared the server path to your files, you can insert the banner ads on any page.  Just remember to <? include ?> the correct file!

Thats All!
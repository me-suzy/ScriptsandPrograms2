PBL Tell A Friend v 1.02 Read Me
================================


Written by Lev <lev@taintedthoughts.com>
Written & Released: December 26th 2004

Simple site recommendation script with lots of extra features for those who want them & no required configuration!

* Nothing in the script needs to be modified unless you want advanced options!
* Prevent servers, not given access, permission from being recommended!
* Control how many people the user may recommend at a time
* Verifies that all email addresses processed are valid
* Enable an optional log (requires mySQL) to monitor all activity with the script
* Add additional *invisible* text which is added to all emails sent recommending sites but not displayed in the message text the user submits
* Control the added text and default message text in simple text files
* Template support; totally control the theme and appearance of the guestbook!


INSTALLATION GUIDE
 1) Requirements
 2) Simple Installation
 3) Customizing Options In The Script
 4) Methods Of Calling The Script
 5) Accessing The Log
 6) History / Help / Troubleshooting


1)===========REQUIREMENTS==========

php 4 or > access


2)===========SIMPLE INSTALLATION==========

This package should contain at least the following:

   agreement.txt
   readme.txt
   tellafriend_mes_footer.txt
   tellafriend_message.txt

   pbltellafriend.php

   template.html

If for some reason or another you do not have one or more of these files then visit www.pixelatedbylev.com/downloads.php!

Upload or move all of these files to your server in the same directory. If need be on your server, CHMOD pbltellafriend.php to 755. Make sure template.html, tellafriend_mes_footer.txt and tellafriend_message.txt are in the same directory as pbltellafriend.php. As long as you have given the script proper permissions to be executed for your server and the required files all exist in the same directory and php is installed, then the script is now working and will execute properly!

While the script should now function just fine, you should consider modifying some options and parameters to make the program more to your liking. For example, add text to the bottom of every email that is sent out, change the HTML template to create a better design and so forth. Check out the next section for info on that.


3)==========CUSTOMIZING OPTIONS IN THE SCRIPT==========

If for some reason you would like to rename the file pbltellafriend.php, then you need to update the variable $thisprog on line 8. Simply change the name of the file within the quotation marks to the filename you named it.

If you are using your own custom template and you called it something other than "template.html" then you need to update the variable $template on line 9. Your template should contain $data where the data of the recommend script is placed and $title where ever you want the title of the script (& page) to be displayed. You are advised on using your own template to give your recommend page the personality of your web-site!

How many people do you want visitors to be able to recommend a page to at a time? You can control the number of input fields for email addresses a site can be recommended to. If you think 10 is too much, make it 5 or whatever you wish. Maybe you only want users to be able to recommend one person at a time. It's up to you. Simply change the variable $people on line 10.

Change the variable $fontface on line 11 to reflect whatever font you want the text of the recommend script to display in.

On line 12 is the variable $maxlength. It is set to "50" by default. What this means is that, for viewing purposes, if a URL to be recommended is longer than this number of characters than it should just take the last X number of characters of the URL (where X is $maxlength) and display "..." followed by the last $maxlength number of characters of the URL. This is simply for the purpose of making the script view better on the browser since many URLs are very long it would stretch the data out of context. This simply trims down the displayed URL to avoid that. The actual URL itself is still recommended (un-trimmed).

Line 13, $messagefile, should be the path or location of the default message text. By default this is set to "tellafriend_message.txt", which is also a file that came with this package. Whatever text is in this file will be loaded into the TEXTAREA on the form when the user attempts to recommend the site. Write $url where ever you want the URL of the page being recommended to appear in the text file.

Line 14, $mes_footer_file, contains the path "tellafriend_mes_footer.txt", which is also a file that came with this package. Whatever text is in this file will be placed two lines below any message text when the email is sent. The user recommending the site will not see this text; it will only appear in the email.

You also will probably want to update line 15, $subject. This is set to "You were recommended!". You can change this to whatever you want your default subject to be. This subject will be displayed as the subject of the email sent to whoever is recommended.

If you want to allow users recommending the site to be able to change the subject of the message, then change the value of the variable $changesub on line 16 to "y". By doing so, you now are allowing the user to define a subject for the email. If you don't want this and want to define the subject yourself, then keep it as "n".

For security purposes of dis-allowing other web-sites from using your recommendation script for their own site, you might want to enable $requirerefer on line 17. To do this change $requirerefer to "y" on line 17. This means that the server must be validated as a valid server (line 18) to be recommended. If you set line 17 to "y", then it is necessary for you to set the valid servers in line 18.

If you are requiring that servers be validated for their pages to be recommended, then you need to define which servers are valid and may be recommended using this script. To do so write all the servers you want to grant access to on line 18 for variable $validservers within the quotation marks. Separate each server with a comma (ie: "domain.com,domain.org,www.site.net").

Do you have mySQL access and want to enable a log? If you know your mySQL username, password, hostname and database name and want to setup a simply log to monitor the usage of your recommendation script then change the variable $uselog on line 19 to "y". DO NOT ENABLE THIS OPTION UNLESS YOU HAVE mySQL ACCESS AND PROVIDE THE FOLLOWING PARAMETERS AS WELL:

   Change line 23, $truepassword, to the password you would like to use to access the log.

   Change line 26, $GLOBALS['sqlhost'], to the mySQL hostname. Usually "localhost" is correct.

   Change line 27, $GLOBALS['sqluser'], to your mySQL username.

   Change line 28, $GLOBALS['sqlpass'], to your mySQL password.

   Change line 29, $GLOBALS['sqldbnm'], to your mySQL database name.


4)==========METHODS OF CALLING THE SCRIPT==========

The easiest way to call for the script is to simply link to the URL of the pbltellafriend.php script from any of your pages (ie: <A HREF=http://www.domain.com/pbltellafriend.php>Recommend this page to a friend</A>).

Calling the script from this method will simply recommend the page calling the script. If you place the above link on "http://www.domain.com/aboutme.html", then the script will recommend this URL! It automatically takes the previous page you were at before the script to recommend it. This is useful to allow dynamic recommending; that is any page on your site can be recommended doing things this method. Just link to the recommend script from whatever pages you want to allow users to recommend.

You can also manually define a recommend URL in the query string. If the URL exists in the URL query string then the previous page is ignored and the defined URL is used instead.

   For example: http://www.domain.com/pbltellafriend.php?url=http://www.pixelatedbylev.com

You may notice that this might allow users to get the idea that they can change the URL in the string to whatever URL they want to recommend that page as well. This is why you should prevent off server access and define valid variables (consult section 3: Customizing Options In The Script).

If no URL query string can be found in GET data, POST data and no refering page is defined than the server name is used as the URL to be recommended.


5)==========ACCESSING THE LOG==========

If you have enabled a log and would like to view the data of your log then you should use the following URL format:

   http://www.domain.com/pbltellafriend.php?action=log&password=pbl

Where you replace "pbl" with whatever you set $truepassword, on line 23, to. If the password is not correct the log may not be viewed; this is simply to protect the information within this log.


6)==========HISTORY / HELP / TROUBLESHOOTING==========

Because this is the initial release of this script there is no history as of yet:

   Dec 26 2004 -  Released
   Feb 14 2005 - Added a paragraph tag before footer is inserted since FireFox does not see </FORM> as a new paragraph
   Feb 20 2005 - Fixed valid email address syntax to allow for international addresses (peter.johanson@sub.domain.com)

If you have any problems with the installation or use of this script you are more than welcome to contact lev@taintedthoughts.com. Likewise if you have ideas for improvement, suggestions, comments, complaints or concerns you are more than welcome to provide me with them.

Thanks for visiting www.pixelatedbylev.com!


Hazard Random Generator - Written by Joseph George Jacobs (joe@hazardcell.com) Version 2.0

====================================

HOW IT CAME ABOUT!

v1.0-I needed this for a site I was working on.

v1.1-Fixed a tiny bug in the generation. Certain quotes were not generated.

v2.0-You don't need to type in the arrays one by one now. Just a seperate file like the one given. Can be any format(.db, 
.txt, etc.)

====================================

HOW IT WORKS!

When the script is called, it generates a random quote. If you want, you can also specify the quote that you want to display.
More on that later.

====================================

WHAT YOU NEED!

Basically, all you need is a web server with PHP installed and of course an email address. 

This script was tested with RedHat(tm) Linux and Windows(tm) both with Apache as the webserver. The PHP version in use was
PHP 4.3.9

====================================

HOW TO INSTALL IT!
 
Open the php file in a text editor, like Notepad, and and modify the $file string which tells us the file which contains the 
quotes. Then, just insert the quotes into the other file and upload.

To include the script into your page, use <? include "Hazard.Random.Quotes.php"; ?>

To specify the quote you want displayed, type Hazard.Random.Quotes.php?quote=1, replace 1 with the number of the quote.

====================================

WHAT CAN I DO WITH THE SCRIPT?

You can use it to generate random quotes on your site like I've done. You may also use it to generate random images or links.

====================================

LICENSE

This work is licensed under the Creative Commons Attribution-ShareAlike License. To view a copy of this license, visit
 
http://creativecommons.org/licenses/by-sa/2.0/ 

or send a letter to 

Creative Commons, 
559 Nathan Abbott Way, 
Stanford, 
California 94305, 
USA.

====================================

There are currently no questions associated with the script, however if you have a problem, feel free to stop by the site and 
let me know or post a question at the forum (http://www.hazardcell.com/forum)

-- Joe
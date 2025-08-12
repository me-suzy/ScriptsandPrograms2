addicted one's counter 

Version 1.0
Release date: September 19, 2005

----------

***USAGE***

This counter script is free for you to use for whatever purpose you choose. If you are looking for a heavy, feature loaded script, then this counter is not for you. If you want something that loads quickly and simply displays the number of hits on a page, then this is for you. The total file size is VERY small, so it will not slow down page loading time at all. It uses a database, therefore is very secure, unlike the typical text file-stored counters.

----------

***INSTRUCTIONS***

To add the counter to your site, copy the contents of aocounter.php and paste it where you would like it to display on your page. If you would like the counter on all pages of your site, then simply add this where you would like the counter to go:

<?php
include "aocounter.php";
?>

For either of these to work, the page you put the script in must end in .php rather than .htm or .html. Your server must also have php installed.

To install the counter, open aocounter.php with a text editor and replace the #### at the top with the database username, password, and the database name. After that, create a new database and then run counter.sql on the database to create the required table. 

----------

***FINAL WORDS***

If both steps were completed sucessfully, then you're all set, and should have a working counter. If you are replacing an older counter with this one, then change the number in the database from the current number to the old number, and the counter will continue from there. 

If you have any problems or questions regarding the use or creation of the counter, feel free to contact me using the contact form on http://yourmomatron.com or by emailing me mshelton@oakland.edu
--------------------------------------------------------------
|MD News version 1                                           |
|(c)Matthew Dingley 2002                                     |
|For more scripts or assistance go to MD Web at:             |
|www.matthewdingley.co.uk                                    |
--------------------------------------------------------------


How to use
-Open config.php and change all the variables

-Upload all the files into the same folder (preferably a folder called news)

-Run www.yourdomain.com/newsfolder/admin.php?install=go where www.yourdomain.com is your
domain name and newsfolder is the name of the folder you have put the files in.

-Add news items. Just follow the on screen instructions.

-To add a latest news item to say your homepage insert this code:
<?php
$latest = "newsfolder/latest.php";
require $latest;
?>
Where newsfolder is the folder that the files are in as a relative link.

-That should be it!

-I hope you enjoy using MD News

For more information or help go to MD Web at
www.matthewdingley.co.uk

Requirements

-A Server with PHP 3+ installed
-Access to a MySQL database

Licence

-To use this program, you have to keep the copyright notice intact or provide
a mention back to my website at www.matthewdingley.co.uk and make it
clear that it is (c)Matthew Dingley 2002

-You may not distribute this program in any way. You can make a reasonable amount of copies
for your personal use only

-This program is free to use on a non-commercial site. If you want to use it on
a commercial site (excluding .org sites) go to the website for more information.
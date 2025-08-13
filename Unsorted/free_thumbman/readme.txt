How to install Thumbman V.1!

1. First you unpak the zip, which you proberly have since you are reading this file

2. Upload all the content from the zipfil to your webserver, you must keep directory structur.

3. Now you have to chmod 777 settings.php, template.txt,the thumbman/thumbs and the thumbman/tempthumbs folder.

4. Now point your browser to http://www.yourdomain.com/thumbman/setup.php and fill out the textboxs and hit submit after that DELETE setup.php

5. Now the script is installed and you can enter http://www.yourdomain.com/thumbman/admin/ and add your thumbs

6. The last thing to do is to add thumb.php to your site, this can be done with
<? include 'thumb.php'; ?> if your index is a .php file, if it is a .shtml you can use server side include like this:
<!--#include file="thumb.php" -->


7, Now everything is done, remember to delete setup.php and password protect your /admin folder, if something goes wrong or you dont want to install it yourself, then contact hvidlogspusher on icq #97368896 and he will install it for you for only 10$.

Enjoy :)

Known bugs:
There is a few bugs with the function that takes thumbs from other sites, it cant take the gallery also.
The function that takes thumbs galleries can fail on some galleries but i work on the most.
These bugs will get fixed and the script improved in the next version.
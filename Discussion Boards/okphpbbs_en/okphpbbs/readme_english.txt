Okphp BBS v.3.1 Trial

###########   AnnounceMent   ############

This is a Trial Version.
Please purchase LICENSE for formally use.


########### System Requirements  ############

* PHP4.3 with GD2
* MYSQL3.23
* Zend Optimizer 2.1 


########### Installtion ############

Step 1, Copy all folders&files to your server.
(If you use FTP, Please check the BINARY MOD.)

Step 2, If your WebHost is based on UNIX/LINUX, please change the mod of the following folders&files to '777'
bbs/images/headp/,bbs/config.php

Step 3, Run bbs/install.php

Step 4, Installtion complete, delete bbs/install.php,bbs/install/ .


########### How to make Okphp CMS&BBS&BLOG to work togather ############

upload Okphp CMS, Okphp BBS(except index.php), Okphp BLOG(except index.php) to the same folder.
If your database infomation is:
Host:localhost, Database:okphp, Database Username:test, Database Password:123

The installtion will be:

Okphp CMS
-----------------------------
Host: localhost 
Database: okphp 
Database Username: test 
Database Password: 123 
Prefix: cms_ 
(Bind other Okphp works)
Bind with Okphp BBS? Yes,  Prefix of Okphp BBS:bbs_ 
Bind with Okphp BLOG? Yes,  Prefix of Okphp BLOG:blog_ 
User data: Okphp CMS 

Okphp BBS
-----------------------------
Host: localhost 
Database: okphp 
Database Username: test 
Database Password: 123 
Prefix: cms_ 
(Bind other Okphp works)
Bind with Okphp CMS? Yes, Prefix of Okphp CMS:cms_ 
Bind with Okphp BLOG? Yes, Prefix of Okphp BLOG:blog_ 
User data: Okphp CMS 

Okphp BLOG
-----------------------------
Host: localhost 
Database: okphp 
Database Username: test 
Database Password: 123 
Prefix: cms_ 
(Bind other Okphp works)
Bind with Okphp CMS? Yes, Prefix of Okphp CMS:cms_ 
Bind with Okphp BBS? Yes, Prefix of Okphp BBS:bbs_ 
User data: Okphp CMS 

#################################

Okphp Group
http://www.okphp.com
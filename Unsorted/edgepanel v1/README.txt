/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
| README.txt :: Refer to this file for information on the installation |
| and maintenance of EdgePanel Version 1.0                             |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mark@totalfreelance.com)                       |
+---------------------------------------------------------------------*/

---------------------------------
1. SCRIPT INSTALLATION
---------------------------------
At present, only a MySQL version of the script is available, so you must
have MySQL running on your server to install the script.

Step One: Create a new database on your MySQL server and note the name
          of the database
          
Step Two: Upload all files to your webserver, and CHMOD:
	   ./includes/conf.global.php to 0777
	   ./includes/                to 0777
	   
Step Three: Now you must run the installer, but visiting:
	    Http://www.yourdomain.com/script_dir/install.php
	    
Step Four: Follow the step by step instructions and the script installation
           is complete
           
---------------------------------           
2. RELEASE NOTES (Version 1.00)
---------------------------------
The live chat module included in the current release of EdgePanel is in 
its experimental stage, and whilst it should function entirely correctly,
it could use a lot of server/database resources which may be a problem if
running on older system.

To check for updates of the script please refer to the 'Check For Update'
section of the Administrator's Panel.

---------------------------------
3. BUG REPORTS
---------------------------------
Please report any bugs you find in this release to either mark@totalfreelance.com
or mcarruth@totalfreelance.com. By doing this you can help us identify
problems and release a fix as quickly as possible.

Additionaly, please write to us with your comments and feature suggestions,
as we are always looking to improve our product to give you the best features
that we can.


\*--------------------------------------------------------------------*/
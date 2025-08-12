+++++++++++++++++++++++++++++++++++++
Information
+++++++++++++++++++++++++++++++++++++
Seraph Redirector
Author: Ryan Ong <Snobord787@msn.com>
Copyright (c): 2003 Ryan Ong, all rights reserved
Version: 0.3
Site: sredirector.sourceforge.net
Updated: 10/30/03
 * This Script is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License (GPL)
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

+++++++++++++++++++++++++++++++++++++
Notes:
+++++++++++++++++++++++++++++++++++++
This script takes twice the ammount of bandwidth of your 
average site due to downloading of the file from other sites
then outputing them.

You do not have to put these in the root directory you can 
put these in outer laying folders.

To use with subdomains you must have requires Wildcarded DNS.
To use with subdomains just create a folder with the chosen
  subdomain name. Then install regularly

+++++++++++++++++++++++++++++++++++++
Installation:
+++++++++++++++++++++++++++++++++++++
1) extract all files to any directory
2) change settings in config.php
3) Open www.yoursite.com/index.php
4) if you get the error Could not create .htaccess
   then

+++++++++++++++++++++++++++++++++++++
FAQ:
+++++++++++++++++++++++++++++++++++++
1) Why aren't my forms working?
That is due to the fact that you are either
 -using method="post" with frames or redirect will not work
	try using method="get" instead of method="post" in the <form> tag

 -fsockopen is not working and instead is using fopen.
	fopen does not processes any post, cookie, or file vars.
	try using method="get" instead of method="post" in the <form> tag
------
2) Why does the page keep on using frames instead of site relay?
This is because fsockopen did not work and fopen did not work.
To find out the error open the source of the page and look at the bottom of the page after </html>

+++++++++++++++++++++++++++++++++++++
Thanks to:
+++++++++++++++++++++++++++++++++++++
PHP.net for all the help
Snoopy (http://snoopy.sourceforge.com) for some code

+++++++++++++++++++++++++++++++++++++
Change Log:
+++++++++++++++++++++++++++++++++++++
From 0.2 to 0.3
1) Better recieveing and sending Header Processing
2) Readme update to update .htaccess
3) Auto create .htaccess
4) fixed another fopen bug.
5) now supports file upload

From 0.1 to 0.2
1) Fixed Redirection bug
2) Added Skin interface
3) More commentation
4) Fowards Cookies
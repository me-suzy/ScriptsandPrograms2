Baal samrt Form PHP vertion :
http://baalsystems.com
Version 3.8


BaalSystems CopryRight 2005.

GPL License: See http://www.gnu.org/copyleft/gpl.html
By using and or downloading this software you may not hold Baalsystems or associates liable for any use or functionality of this software.

Also we make use of Moxiecode TinyMCE:  This code comes with its own GPL copyright:
http://tinymce.moxiecode.com/download.php
Copyright notice: http://tinymce.moxiecode.com/wrapper.php?url=tinymce/docs/index.htm  LGPL


!Fresh Installation ::
  
  1.Extract the zip file .
  1a. Make \install\dataaccess.php , \incl\db.php, and Pefts.php write-able.
  Run \install\install.php
  Fill in data, Select "Fresh Install"
  Do not include "Old Table Prefix" data.


!- UPGRADES

** No data will be lost ** //But always be safe and backup files, and talbes first.  
Free Software to backup mysql data: http://www.assurebackup.com/   \\

lTo upgrade a previous version of the Baal Smart Form

1st open your existing dataaccess.php and print it or copy it to a new document.

2nd Extract the Zip File

3rd Overwrite all files from the new zip file to your existing Baal directory.

4th  Temporally allow full write access to the following files:
/install/dataaccess.php
/incl/db.php

5. In your web browser go to the Baal Form directory + /install/install.php
Example:  www.baalsystems.com/baalsmartform/install/install.php

6. Fill in the data from Step 1.  Only enter in Table Prefix from the old system if there was one.  Do NOT enter anything into Old Table Prefix.

7. Select Update as installation type.  NOT Update Install.

Form will update.  

Surf to /install/update1.php  Update the software again, to correct admin posting order issue. 

9. Go back and remove write permissions to the files you changed for security.
Also delete or secure the /install directory and remove or secure all instances of /regadmin.php

Change Log:
Version 3.7
This version of the Baal smart form upgrades the form input section to a wysiwyg browser.  Also includes a patch (update1.php) to correct an issue with how admin posts are ordered in the opposite direction of normal posts.   Finally it adds some nice features such as showing the time it takes to generate a page.  


Version 3.5 

Version 3.5 of the form software corrects most of the font size issues.  It also allows for moderators and administrators to edit posts.  Allows for the form to be put into free posting mode, where user login is not required to post messages.  The administrator also has a few new abilities such as re-sorting the entire form in ascending or descending order, and also customizing the form background color. 

Version 3.4
This Version allows the discussion form to be installed multiple times on the same database with table name prefixing.  It also allows users to change font size, apply italics, bold.  The new system adds support for a moderator, who can delete posts.  I also includes a new basic messaging system so users of the message board can send each other private messages.

Version 3.3 corrects problems with posting chars such as '"@! etc.


http://baaalsystems.com




  
  
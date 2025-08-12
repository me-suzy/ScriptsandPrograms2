FWCounters 2.0.3
================
Date : Apr 14, 2004
Author : Mike Frank <mike@mfrank.net>
Homepage : FrankWorld <http://www.mfrank.net/>


1-Whats New
2-Requirements
3-Installation
4-Updating
5-License
6-Support, Contributors, Bugs


1-Whats New
-----------
2.0.3 (Apr 14, 2004)
-fixed blank stats
-changed stats select box
-changed history to daily report
-added demo account
-fixed reset stats, no stat error
-added optional change counter in stats

2.0 Beta (Apr 2, 2004)
-user-friendly graphical interface
-highly customizable interface
-added header.inc and footer.inc
-account signup
-max counters per account
-statistics graph and table
-edit/delete counter option
-new counters are added to the beginning of signup.log
-easy custom counter style installation
-mew login.php
-mail user his/her name and password
-inform admin on new signups

1.90 (Mar 13, 2004):
-general code touchup in all php files
-fixed empty err() functions
-removed log.htm
-signup method is now POST

1.83 (Feb 22, 2004):
-first public release


2-Requirements
--------------
PHP4, tested with 4.1.1 and 4.2.2
GD Library extension installed and running (for the creation of graphs). 
No MySQL required!
Windows and maybe Linux and Unix


3-Installation
--------------
Step 1: Extract all source code files to a temporary directory. Open config.php
and change the settings to match your server.

Step 2: Using a FTP client (such as CuteFTP, WSFTP, etc.), upload all the files
in the temporary directory to your server.

Step 3: Once upload completes, you must chmod the following files and folders to
777:
countdb (folder),
sidtemp (folder),
userdb (folder) and
login.php (file).

Chmod the following  files to 666:
previd.db,
signup.log,
userdb/admin.db and
the three '.db' files in the countdb folder.

Step 4: Close the connection to your server.

Step 5: Open a new browser window, and navigate to where you uploaded FWCounters.

Step 6: The main FWCounters home page should show up. Your done!

How to add your own digit syles:
FWCounters 2.0 was designed to automatically pick-up new digit styles.
To add your own, you must have a sample image with the extension '.gif',  this
must be saved into the 'digits' directory. 
Next, you must have a folder in the 'digits' directory with the exact same name
as the sample image (without the .gif). In this folder you must have all ten
digit files (0,1,2,3...) in GIF format only. The file name for the digits is
required to look like this: number 7 is 7. gif, number 23 will use 2. gif and
3. gif. Get it? Good!
Need anymore help, please don't hesitate to email me: mike@mfrank.net


5-Updating
----------
Step 1: Extract all files in this archive to a temporary directory.

Step 2: Open config.php and set the variables to match your server and personal
preference.

Step 3: Download signup.log and previd.db from your server and paste them into the
temporary directory overwriting the files from the archive.

Step 4: Open signup.log with WordPad or any text editor with the replace feature.

Step 5: Replace the string, "stats.php" with "details.php". Save it.

Step 6: Overwrite all files on your server with the files in this archive.
WARNING: do -NOT- delete the countdb folder, only upload the 3 '.db' files from
this archive to the countdb folder on your server.

Step 7: Once upload completes, you must chmod the following files and folders to
777:
countdb (folder),
sidtemp (folder),
userdb (folder) and
login.php (file).

Chmod the following  files to 666:
previd.db,
signup.log,
userdb/admin.db and
the three new '.db' files in the countdb folder.

Step 8: Close the connection to your server.

Step 9: Open a new browser window, and navigate to where you uploaded FWCounters.

Step 10: The main FWCounters home page should show up. If you done everything
correctly, counters created on v1.8-v1.9 with still work perfectly! If not, please
email me immediately for assistance.


5-License
---------
These scripts are open source and can be used and distributed under the terms
of the GNU GENERAL PUBLIC LICENSE which you can read opening the file GNUlicense.txt


6-Support, Contributors, Bugs
-----------------------------
Support and bug reporting on the FrankWorld web site at : http://www.mfrank.net
or write me at : mike@mfrank.net


Thank you, and enjoy FWCounters!
-Mike
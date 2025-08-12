+------------------------------------------------------+
| Easy Admin :: Original Coding by Matthew Randles     |
|                                                      |
| This file should be viewed in a monospace font such  |
| as courier, courier new or fixedsys.                 |
+------------------------------------------------------+


#########################################################
#                                                       #
# PHPSelect Web Development Division                    #
#                                                       #
# http://www.phpselect.com/                             #
#                                                       #
# This script and all included modules, lists or        #
# images, documentation are distributed through         #
# PHPSelect (http://www.phpselect.com/) unless          #
# otherwise stated.                                     #
#                                                       #
# Purchasers are granted rights to use this script      #
# on any site they own. There is no individual site     #
# license needed per site.                              #
#                                                       #
# This and many other fine scripts are available at     #
# the above website or by emailing the distriuters      #
# at admin@phpselect.com                                #
#                                                       #
#########################################################



		+--------------+
		| Installation |
		+--------------+

	To install easy admin you now have to use the installer.

	To install Easy Admin first upload all of the files in this
distrobution to your server in its own folder like EA or admin. Set
the permission to allow writing from scripts and you, excecute from 
anyone and read from anyone. Then point your browser to 
http://www.domain.com/path/to/EA/installer.php

	Please be sure to delete installer.php afterwards, this can 
be done using easy admin.



		+-------------+
		| PLEASE NOTE |
		+-------------+

	Easy Admin 1.1 was created on Windows XP Proffesional using php 
4.2.3 with register_globals turned off using IIS as the server.

	The author cannot and does not provide any warranty of any 
kind for Easy Admin.

	Any damage obtained from modification and miss-use of Easy 
Admin is no concern of the author.

	Most of the code is in a fairly un-ordered format but it
will be cleaned up in future releases. The main objective currently
is to release a fully functional and stable release.



		+-----------------------------------+
		| As of this release / Change Notes |
		+-----------------------------------+
	
	MD5 is now used to encrypt th admin password, for this reason
you can no longer do a manual install.

	All code is in a modular format so extending the admin section
is easier.

	Please - If you wish to modify the script for re-distribution 
send me a copy of your modified code so that I can update EA for all to 
share if the modifications are suitable. Please label code with what's 
been added and which code is new. 

	If you write a new module but think not all would want it please 
send me a copy and I can offer it in your name as a separate add-on.

	Each action and its related components are in their own 
single file.

	Style is controlled by CSS. Table attributes and positioning 
are static though. Please note not all versions of Easy Admin released will 
include CSS files - don't worry though the interface looks fine without.

	Logon is now completely different. Cookies are now used and plans 
for the logon to be mixed with sessions for increased security are in the 
pipeline.

	When using the modules included with easy admin remember that you 
can't give file names with spaces. Try using underscores '_' instead.

	Easy Admin, as of this version is released under the GNU General 
Public Licence. Version 2 - June 1991. A copy of the licence is contained 
in the zip file. Use of Easy Admin signals your acceptance of the rules of 
the GNU GPL.

        On the main navigation I have included direct links to edit admin 
files such as the CSS and admin footer.  

        Cookies have to be working on the your system in order to logon, 
some systems allow users to logon but not logoff. Note :: Easy Admin should 
not be used where your cookies are shared with others using the same computer.
If anyone wishes to wirte a more secure logon feature I won't be complaining!
The plans are to mix cookies and sessions and to encrypt passwords using md5.



		+------------------------+
		| Files in this release. |
		+------------------------+


    ROOT FOLDER 	- where modules are

	admin.php 	- an interface between all defferent sections.
	buglist.txt 	- my notes about little bugs and fixes
	cdir.php 	- create a directory.
	cfile.php 	- create a file.
	chmod.php 	- change the chmod of a file (*nix).
	cpass.php 	- change admin pass.
	ddir.php 	- delete a directory.
	dfile.php 	- delete a file.
	dpfile.php 	- copy/duplicate file module.
	efile.php 	- edit a file.
	gnu.txt 	- The GNU GPL (version2, June 1991).
	index.php 	- the logon system.
	installer.php 	- main installer interface.
	logout.php 	- a very simple logout system, relies on the username
			  being something other than "nobody".
	mfile.php 	- move a file module
	mod-templ.php 	- A template used for module creation (layout only). 
	Readme.txt 	- this file.
	redirector.php 	- change file name to your default file name if other 
			  than index.php so that you may logon!.
	rnfile.php 	- rename a file - also seems to move files
	ufile.php 	- an upload module to make life easier.


    INCS 		- the common includes folder

	eacss.inc 	- easy admin css file.
	eallgo.jpg 	- samll logo - nothing to worry about.
	footer.inc 	- the easy admin notice footer.
	.htacces 	- for apache web servers - denys access to all.
	index.php 	- denies acces if not on apache server, change index.php
			  to default.php if needed.
	module-list.inc - link db of different modules so to install a
			  new module all you have to do is append this file 
			  and upload your module file.
	uinfo.php 	- the user information file - not encrypted but plans 
			  are there.


		+-----------+
		| Css notes |
		+-----------+

	The following need to be set in incs/eacss.inc if you want to use css 
to control the Admin interface colours and font sizes. Just enter your css as 
if it were in a .css file. Do *NOT* include the <style></style> tags. 

	Please note not all pages have been set up with css tags and will not be 
untill a more developed version is released.


  body
  a
  a:hover
  a:active
  div (for those without a class definition - should be the same as .admintext)
  form
  hr
  input
  textarea
 .footer
 .installtext
 .installtitle
 .logintext
 .logintitle
 .admintext
 .admintitle
 .subadmintitle
 .scripterror

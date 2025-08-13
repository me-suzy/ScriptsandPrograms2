Thank you for purchasing HTMLeditbox v 2.2

before you start reading this I wanna let you know, that nicely formatted
version of this document, to save your eyes and brains is available online at
http://www.labs4.com/htmleditbox/2.0/doc_install.php

------------------------------------------------------------------------------

This HTML editor can work with any administration based on PHP 4.0 and later.
HTMLeditbox was created with CMS users in mind and is compatible with CMS
packages like PHPNuke, PostNuke, MyPHPNuke, PHP Website, Mamboserver and many
more.

===============================================================================
How does it work?

Version 2.1 comes with not just one but four ways to enter information into
editor:
1. by adding form name and input name of editable element
2. by adding id of editable element
3. loading information directly from mysql database
4. loading flat HTML file

Let's see it in detail
-------------------------------------------------------------------------------
1. by adding form name and input name of editable element
-------------------------------------------------------------------------------
This is our original method but if you don't plan to use older support packs
rather skip to method 2, it will be easier for you.

Assume that you have website administration and on one page you can edit
articles. Text of the article is loaded from database into textarea, this 
textarea is part of some form. 

Principle is very easy - we need two parameters, third parameter is optional

- form name (if your form tag has no name in it you have to name it eg.:
<FORM name="form" ..., if form is inside php script don't forget to use
backslashes like this <FORM name=\"form\" ...) for your convinience you can
download edited files for current versions of most famous CMS systems on our
website.

- input name - textarea name eg: <TEXTAREA name="intro" ...

your standard initial link will look like this (must be in one line!):

<a href="#" onClick="window.open(
'_editor.php?formname=form&inputname=content','editor_popup',
'width=760,height=570,scrollbars=no,resizable=yes,status=yes');
return false" TARGET="_blank">open in htmlEditbox</a>


if you want to use additional options
you can find it explained at http://www.labs4.com/htmleditbox/2.0/doc_init.php


So we have passed content of text area into HTMLeditbox, when you are finished
with editing click on save button in editor and after prompt will be edited
content (it's HTML code) passed from editor back to your administration.


-------------------------------------------------------------------------------
2. by adding id of editable element
-------------------------------------------------------------------------------
this method was created as a solution for scripts using array brackets in form
element names, these PHP arrays were later mixed up with Javascript arrays and
browser got confused. So from now on you can just add id="some_unique_name" 
into textarea tag and replicate this "some_unique_name" in initial tag like:

<a href="#" onClick="window.open(
'_editor.php?id=some_unique_name','editor_popup',
'width=760,height=570,scrollbars=no,resizable=yes,status=yes');
return false" TARGET="_blank">open in htmlEditbox</a>

editor will open in new window and will import content of given textarea.
You can use as many instances of the editor in one page as you want, only
requirements is that each textarea must have unique id.


-------------------------------------------------------------------------------
3. loading information directly from mysql database
-------------------------------------------------------------------------------
another new option - from now you can even load htmlEditbox into same window to
edit one particular record in your database, this feature will be probably the
most impressive for webdevelopers who want to enable HTML editing to their
customers without ever showing them HTML code ...

there is plenty of settings, let's explain how it works
variables for connecting database are stored in _i3/inc/db_bridge.php file but
they can be overriden even from initial link (not recommended!)

information you must provide:
$dbname - database name to edit
$dbtable - table name where editable field resides
$dbfield - field name to be edited
$dbrecord - row number of given record
$dbai - auto incremented field name to recognize which row has to be edited
$dbreturn - return full path after editing is completed, try to avoid multiple
variables in the path, if you must add them don't forget to use &amp; in place
of & (eg. "editme.php?ID=24" ... or "editme.php?ID=24&amp;cat=6" ... never just
"editme.php?ID=24&cat=6")

optionally you can set
$dbsafe - 0/1 - set 0 if your PHP is running in SAFE MODE and slashes are added
automatically, set 1 if you want the script to add slashes into stored content,
default is 1


it can look tricky but it's not, see an example:

Assume, I have table ARTICLES in database called NEWS. Editable information is
stored in field FULLTEXT and auto-incremeted field is ID. Row number I want to
edit is 24.

so I want to do following query
 $query = mysql_query("select FULLTEXT from ARTICLES where ID=24",$db);

settings will look like this:
$dbname = "NEWS";
$dbtable = "ARTICLES";
$dbfield = "FULLTEXT";
$dbrecord = 24;
$dbai = "ID";
$dbreturn = "newsroom.php?news=24";

-*-*- option 1 - method GET *-*-*-

in link it will be 
_editor.php?dbname=NEWS&dbtable=articles&dbfield=FULLTEXT&dbai=ID&dbrecord=24

if you don't want to add slashes add also dbsafe=0
_editor.php?dbname=NEWS&dbtable=articles&dbfield=FULLTEXT&dbai=ID&dbrecord=24&dbsafe=0

-*-*- option 2 - method POST *-*-*-

Another approach is posting these information from hidden form (yes htmlEditbox
can handle both POST or GET information)

you can even post this information from hidden form

<form method=POST action="./_editor.php">
<input type=hidden name=dbname value="NEWS">
<input type=hidden name=dbtable value="ARTICLES">
<input type=hidden name=dbfield value="FULLTEXT">
<input type=hidden name=dbrecord value="24">
<input type=hidden name=dbai value="ID">
<input type=hidden name=dbpath value="newsroom.php?news=24">
<input type=hidden name=dbsafe value="0">
<input type=submit name=submit value="edit this document">
</form>


*** Remember that if you provide dbai editor will update given database ID
but if you don't provide it editor will create new record!

-------------------------------------------------------------------------------
4. loading flat HTML file
-------------------------------------------------------------------------------
seems like a dream? pinch yourself, htmlEditbox can really open, edit and save
even static html files!

just provide 
filename - with full path from website root,
filereturn - return file with path where to go after work
and you can start editing, to use this feature you will be unfortunatelly asked
for admins password (stored in config) to avoid security problems, this password
is then stored in cookie for 24 hours or untill you logout so you don't have to
login with each entry 

same as db-bridge also file-bridge opens in the same window and doesn't use
new window

warning: this feature is experimental now and will be improved in next builds
and versions, this is the only option which will edit full portion of HTML code
everzthing between <html> and </html> tags

===============================================================================
Remember:
1. editor must be placed in same directory as your website especially because
of image and file paths!
2. you can provide more than one links to editor on any page
3. directory /images/articles/ is empty after installation, you will see two
empty lists (folders,files), click on upload new image, click on "New Folder"
try to create new folder, try to upload picture to this new folder.



===============================================================================
you can find illustrated HTML version of this manual at
www.labs4.com/htmleditbox/2.0/doc_install.php
===============================================================================



I N S T A L L A T I O N :

STEP 1
Unpack htmlEditbox21bx.zip (where x is number of current build)
upload following file into root folder of your website.
_editor.php

create directory /_i3 on your server and copy there content of /i3 directory
inside installation package.



---------------------------------

STEP 2
Locate form inside your administration where you want to use HTMLeditbox,
find textarea you want to make HTML editable and put id="something" into its
tag like

<textarea id="something" ...

Under textarea tag add following (tag must be in one line):

<A HREF="#" onClick="window.open('../_editor.php?id=something','editor_popup',
'width=750,height=570,scrollbars=yes,resizable=yes,status=yes');">
Edit Text In HTML editbox</A>

edit tag to point to root of your website (../editor.php?) and parameter id
?id=something)

Editor uses directory /images/articles/ as default directory for storing
images and creating subfolders. You have to create it and directory
/articles must be CHMOD 0777 otherwise image and folder functions will not
work properly!

For file storage you have to create directory /files in the root of your
web, also this directory must be CHMOD 0777.

If you want to change default directories for images and/or files, open
/_i3/inc/config.php file and edit it there.


That's it, you can log-in to your administration and enjoy new functions.


-------------------------------------

If you want to use htmlEditbox with other application, check downloads 
section for support package with pre-edited files which can save you
hours of source code editing.

-------------------------------------

U P G R A D E   F R O M   V E R S I O N   2 . 0 :

If you want to upgrade from build 1 to build 2 you will need to update only 
following files:
_editor.php
/_i3/inc/config.php
/_i3/js/core_js.php
/_i3/lang/lang_english.php

and upload new files
/_i3/inc/db_bridge.php
/_i3/inc/file_bridge.php
/_i3/inc/security.php

If you are using different language, download updated language pack from
http://www.labs4.com/htmleditbox/2.0/down_language.php


-------------------------------------

U P G R A D E   F R O M   V E R S I O N   1 . 0 :

STEP 1
From webroot delete following files
_editor.php
_editori.php
_editoru.php

now delete directory /i3/


STEP 2
Unpack htmlEditbox20bx.zip (where x is number of current build)
upload following file into root folder of your website.
_editor.php

create directory /_i3 on your server and copy there content of /i3 directory
inside installation package.

create /files directory and CHMOD 0777


And that's it. Enjoy.

========================================================================================
Do you want more? visit www.labs4.com  |  Support questions send to support@labs4.com
***************************************************************
              dotNetBB v2.1 Installation ReadMe
                       November 6, 2002
***************************************************************
    (c) Copyright Andrew Putnam, 2002.  All Rights Reserved.
***************************************************************


***************************************************************
Contents
***************************************************************
1.0 Introduction
2.0 Installation
    2.1 Creating your database
    2.2 Running the MS SQL scripts 
    2.3 Copying the files
    2.4 Editing the Web.Config
    2.5 Admin Account
    2.6 Image Approvals
    2.7 Modifying the Terms of Service
    2.8 Creating a test account
3.0 System Requirements
4.0 Questions and Comments

***************************************************************


***************************************************************
1.0 Introduction
***************************************************************
Welcome and thank you for purchasing the dotNetBB forum software.
This readme file will attempt to explain the initial installation
of your forum and how to get it up and running.

Please ensure you read the 'System Requirements' section below 
before attempting installation.  This installation assumes the 
following : 
  * You have a working knowledge of MS SQL Server administration.
  * You have a working knowledge of HTML.
  * You are running a Microsoft IIS Web Server and MS SQL database 
    server.
  * You purchased this product thru legitimate channels.

These instructions are provided 'AS IS' and are not a guarantee
that you will properly install this product.  If you have any
questions about the installation, please visit the 'Registered 
Owners' section of our online forum at http://www.dotNetBB.com or
contact us by e-mail at Support@dotNetBB.com.


***************************************************************
2.0 Installation
***************************************************************
Below are step-by-step instructions on how to properly install
the dotNetBB forum on to your site.  This manual process is 
required to ensure optimum compatability for all users using 
this software, including people who are using hosting services 
who might not have direct access to the server to run an installation
program.


----------------------------------------------------
2.1 Creating your database
----------------------------------------------------
If you do not already have a database in MS SQL Server to use for
the forum tables and stored procedures, you must create a new
database before continuing. For information on how to create a new
database, please refer to the MS SQL documentation.

dotNetBB forums are designed to NOT require the use of the 'SA'
account to operate properly.  It is suggested that you create a
new user account to be used for the forum access to the database.
For more information on how to create a new user account, please
refer to the MS SQL documentation.


----------------------------------------------------
2.2 Running the MS SQL installation scripts
----------------------------------------------------
To assist in creating the table structure for dotNetBB, a script file,
'dotNetBB_v2_SQL_Install.sql', has been included with this installation.
Using MS Query Analyzer open up this file.  It should be located 
in the same folder as this readme file. Select the database 
you want to use for your dotNetBB installation and click on the 
'Execute' button.  The typical runtime for this script is between 30 seconds
and 2 minutes depending on your SQL Server's performance.  

NOTE : Running this script on an existing installation will WIPE CLEAN
the ENTIRE dotNetBB installation!  DO NOT run this script on an existing
installation of dotNetBB unless you intend on resetting all of the 
data.  YOU HAVE BEEN WARNED!


----------------------------------------------------
2.3 Copying the files
----------------------------------------------------
On your web server, find your root web folder (e.g. c:\inetpub\wwwroot)
and create a new sub-folder under it called 'forum'.  You can use any 
name for the folder that you prefer, but the examples in this documentation
are assuming you use the name 'forum' for the root forum folder. 

Copy the all of files from the '/install/forum' folder into the newly
created folder on your web server.  This will ensure that all of the 
files required for the forum are put in place.

Copy the 'dotNetBB.dll' from the '/install/bin' folder into your web servers '/bin' folder.

----------------------------------------------------
2.4 Editing the Web.Config
----------------------------------------------------
The Web.Config file is unique to ASP.NET and is used for storing variables
and settings related to your site.  You can have multiple Web.Config files
on your web server in separate folders, each affecting that folder and any
sub-folders below.  If you copied the files correctly in step 2.3 then you
should find a 'Web.Config' file located in your forum root folder.  Open 
this file using Notepad or another plain text editor of your choosing (DO
NOT USE a WYSIWYG editor like MS Word!)
 
These seven "key" values in your Web.Config are required for the forum 
to function properly :

** Key Example : <add key="boardTitle" value="The dotNetBB Message Board" /> **

siteURL       : The base URL for your server. DO NOT include a trailing /. 
rootPath      : The path after the siteURL to the root of your forum. DO 
                NOT include a trailing /. 
boardTitle    : This is used across the forum and for the notification e-mails. 
siteAdmin     : The name of the forum administrator used across the forum 
                and for the notification e-mails. 
siteAdminMail : The e-mail address of the forum administrator used across 
                the forum and for the notification e-mails. 
smtpServer    : If you are not using the local SMTP server, enter in the 
                DNS name or IP Address of a valid SMTP server. Leave 
                the value as "" to use the local SMTP server. 
dataStr       : VERY IMPORTANT! This is the connection string for your 
                SQL server. Currently ONLY MS SQL Server is supported 
                by dotNetBB. The connection string has 4 primary parts : 
                    * SERVER=YourServerName; 
                    * DATABASE=TheForumDatabaseName; 
                    * UID=TheUserName; 
                    * PWD=ThePassword; 


These seven values should already exist in the Web.Config file included
and you are only required to modify the 'value' of each key to have this work
correctly.


----------------------------------------------------
2.5 Admin Account
----------------------------------------------------
If everything up to this point has been done correctly, you should be able to
open up a web browser and access the forum site.  If the forum does not load 
properly, please go step-by-step back thru this readme file to ensure you have
set everything up properly.  You can also log into the 'Registered Owners' forum
on our site at http://www.dotNetBB.com for additional assistance or contact us
by e-mail at Support@dotNetBB.com.

Once the forum loads properly, the first thing you want to do is change the
default Admin password.  The owners and developers of the dotNetBB forum cannot
be held liable if you do not change this password and someone decides to ruin your
day by trashing your forum installation.  You can log in the first time using 
the following account : 

User Name : Admin
Password  : a6543215

Once logged in, proceed directly to the 'Modify Profile' link located at the 
top of the forum and click it.  Change the Admin password using this profile form.


----------------------------------------------------
2.6 Image Approvals
----------------------------------------------------
Now that you are logged in as the Admin, click the 'Forum Administration' link
found at the top of the forum listing.  This will take you to the forum 
administration panel.  While the forum does come with a few emoticons and avatars,
you must approve the images that are to be allowed for use on the forum. A 
default installation of emoticons are setup for you.

 - Emoticons : All emoticon images are to be stored in your '/forum/emoticons/' 
               folder.
   * Click on the 'Emoticons' link on the left navigation menu and select the
     emoticons you want to allow in your forum by clicking on the 'Enable' link
     to the right of the image.  More information regarding emoticon management
     can be found by clicking on the 'Administration Help' link on the left 
     navigation menu.

 - Avatars   : All Avatars images are to be stored in your '/forum/avatar/' 
               folder.
   * Click on the avatar image to enable their use on the forum.  More 
     information regarding avatar management can be found by clicking on 
     the 'Administration Help' link on the left navigation menu.


----------------------------------------------------
2.7 Modifying the Terms of Service
----------------------------------------------------
The Terms of Service are shown to a user when creating a new account on your forum.
These terms allow you to define any specific terms that you want or need to have 
on your forum.  The Terms of Service file (tos.xml) is located in your '/forum/xml/'
folder.  Using a text editor of your choice (e.g. Notepad) open up the file.
The contents are broken up into paragraph sections based on a set of <section></section>
tags.  The <sectionhead></sectionhead> define the paragraph header and is shown in
bold when displayed.  The <sectionbody></sectionbody> tags define the content of the 
paragraph section.



----------------------------------------------------
2.8 Creating a test account
----------------------------------------------------
The last step in setting up your forum is ensuring that new members can register.
If you are still logged in as Admin, click on the 'Log Out' link found at the top 
of the forum.  Once logged out, click on the 'Register' link.  It is suggested
that you create a secondary admin account under another name as a backup.  Use
this new membership form to create a new profile for yourself.  Once complete, 
submit the form and wait for the e-mail confirmation.  If you receive do not 
receive the confirmation, either your local server is not configured to send SMTP
or you did not properly enter in the DNS name or IP Address of your SMTP server.

Check the items in section 2.4 if you did not receive your new membership 
confirmation email.

Once your account is confirmed, log in as Admin again and assign the 
administration permissions to the account you just created. More 
information regarding Admin access can be found by clicking on the 
'Administration Help' link on the left navigation menu.


***************************************************************
3.0 System Requirements
***************************************************************
Microsoft IIS v5.0 or newer Web Server
Microsoft SQL Server v7 or v2000 
  OR Microsoft MSDE (not recommended for busy sites)
Microsoft .NET Framework 1.0
A usable SMTP Server to send e-mail notifications thru.


***************************************************************
4.0 Customization
***************************************************************
Included with all dotNetBB installations is a basic customization package that 
includes image and file samples that would be required to customize your installation.
These items can be found by looking in the 'customization' folder included with 
your installation package.  Additional customization information can be found in the
'Registered Owners' forum section online at www.dotNetBB.com.


***************************************************************
5.0 Questions and Comments
***************************************************************
* dotNetBB is a fully supported product for registered owners.
* Online support is available for registered owners online at http://www.dotNetBB.com
* E-Mail support is available for registered owners by contacting Support@dotNetBB.com



Thank you for purchasing dotNetBB!

The dotNetBB Development Team



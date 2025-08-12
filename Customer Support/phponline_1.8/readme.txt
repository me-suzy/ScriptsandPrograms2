    ******************************************************
    ******************************************************
    ***********       PHP-Online v1.8            *********
    ******************************************************
    ******************************************************


    Copyright (C) 2003-2005 
    Farhad Malekpour <fm@farhad.ca>

    Official Website	http://phponline.dayanahost.com
    Online Forums       http://www.dayanahost.com/forum
    Powered by          http://www.dayanahost.com

    Translation service provided by Google Inc.

                
Thank you for downloading PHP-Online from  http://phponline.dayanahost.com.
If you did not download your script from http://phponline.dayanahost.com, please
delete these files and download the zip again from the official site for your 
own security.


THE USE OF THIS SOFTWARE IS COMPLETELY AT YOUR OWN RISK!

--------------------------- GNU License ---------------------------

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

-------------------------------------------------------------------

The following are required to use PHP-Online:

- PHP 4 or higher (The more up-to-date the version, the better)
- MySQL
- phpMyAdmin (downloadable from http://phpmyadmin.sourceforge.net)


This program has been tested on the following server spec:

- Red Hat Linux 8.0, 9.0
- Apache Web Server 1.3.27, 1.3.33
- PHP 4.1.2
- MySQL 3.23.36, 4.0

I know that it works on these specs, and I give no guarantee of it working on other systems.


Before editing any files, I recommend downloading a great script editor called Crimson Editor.
You can download the FREEWARE program from http://www.crimsoneditor.com
If Windows asks for a program to open any of these scripts in (including .php and .290),
why not select Crimson Editor for stress free editing.


__________________________________________________________________________________

Installation


- Download the zip from http://phponline.dayanahost.com
 
- Unzip it (try WinZip available at http://www.winzip.com)
 
- First you need to create the MySQL database.
  All you need is a MySQL database which assosiate with a username/password.
  So create a database using your hosting control panel. If your Hosting Provider has CPanel
  support you may do it easily. Just login to cpanel and create a database in MySQL section.
  Then you need to create a set of username/password and then assign it to your database.
  If everything goes fine, CPanel will show you some connection samples.

- Open up the 'config.php' file in your favourite text editor (e.g. Crimson Editor), this file
  is in phponline folder.
  There are some tags which need modification here:

  $DBHost is the host name of MySQL database, normally it will be localhost.
      Example: $DBHost = 'localhost';

  $DBUsername is the username to access to database. If you use cpanel, you may find it 
              in sample connection instructions.
      Example: $DBUsername = 'youraccountusername_yourdefinedusername';

  $DBPassword is the password of the above username.
      Example: $DBPassword = 'yourpassword';

  $DBDatabase is the name of the database you created using MySQL. If you use cpanel, you
              may find it in sample connection instructions.
      Example: $DBDatabase = 'youraccountname_livesupport';

  Save these changes and exit the file

- Create a folder in the document root of your site with your favorite name, for example you
  may use "phponline". Then upload the contents of local phponline folder to phponline folder
  of your web site. So you must see for example config.php in ..../phponline/config.php, and 
  if you use cpanel, you may see it under /home/your_user_name/public_html/phponline/config.php

- Now you need to call install.php to make tables in database. This script located in 
  /phponline/install.php, so you may call it by http://www.yourdomain.com/phponline/install.php
  If you receive any sort of errors or can not connect to database you need to control database
  variables in config.php again.
  
- Installation is now complete! Login to admin area and change the configuration
  if it's necessary.


__________________________________________________________________________________

Upgrade from previous versions

As configuration system of this version has changed we recommend you to do a
complete reinstall.


__________________________________________________________________________________

The Use Of PHP-Online

Admin Area:
  You may wait for a customer using:
  http://www.yourdomain.com/phponline/staff.php
  (default login information is: username->admin  password->adm123 , Change the 
   password ASAP)

Client side:
  Put a link for your customers at:
  http://www.yourdomain.com/phponline/client.php

Status Indicator:
  You may put a link to status indicator image from anywhere is your site. Use this 
  simple code:
  <a href="http://www.yourdomain.com/phponline/client.php" target="_blank"><img
  src="http://www.yourdomain.com/phponline/statusimage.php"></a>

  A sample code to show flash based status indicator can be found at:
  http://www.yourdomain.com/phponline/status.php

  *NOTE*
  OnSite feature of Admin area will not work with flash based status indicator (yet).


That's it. You are almost done. Hope you enjoy my script.
A real working version of this script can be found at:
http://www.dayanahost.com

__________________________________________________________________________________

Getting Help For PHP-online

For help with PHP-Online, please use the forum located at:
http://www.dayanahost.com/forum

__________________________________________________________________________________
Changes in v1.1
2003-05-13 : Protect staff.php, now it needs password to login and serve customers.
             Changed files: 
                as.fla
                as.swf
                config.php
             New Files:
                login.php
                
Changes in v1.2
2003-05-14 : Now support older version of PHP.
             Changed files: 
                Almost all php files
             New Files:
                None

Changes in v1.3
2003-05-17 : Now you may change all language oriented strings without changing the
             flash files.
             Changed files: 
                Almost all php files
             New Files:
                lang.php

Changes in v1.4
2003-05-28 : Now you may change the defaut wait time to find a representative
             Some minor bugs fixed.
             Changed files: 
                config.php
                lang.php
                ch_rec.php
                ch_rec2.php
             New Files:
                None

Changes in v1.5
2005-03-04 : Many bugs fixed
             Status indicator added
             Changed files:
                config.php
                check.php
                ch0.php
                install.php
                rcq.php
                ch_send.php
                ch_send2.php
                ch_rec2.php
                ch_rec.php
                staff.php
                as2.php
                as3.php
                as1.php
                as.swf
                cs.swf
                asc.swf
             New files:
                status.php
                status0.php
                status.swf
             
             
Changes in v1.5.1
2005-04-21 : Image based status indicator added
             Some files changed

Changes in v1.6
2005-05-18 : Some minor bugs fixed
             Some files changed
             Engine improved
             Some new features added to staff side chat window
             You can now create your own themes

Changes in v1.7
2005-06-18 : Some minor bugs fixed
             Some files changed
             New language translator added
             phpOnline is now multilingual
             Added logout in staff panel
             Added language selector in staff panel
             Added language selector in client panel

Changes in v1.8
2005-07-02 : Some files changed
             New configuration system
             Real time visitor monitor

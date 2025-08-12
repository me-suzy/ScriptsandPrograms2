-- 
-- README
-- 
-- Copyright (C) Free Software Foundation
-- Portions Copyright (C) 2002 Emilio José Jiménez <ej3soft@ej3.net>
-- 
-- This is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 2, or (at your option)
-- any later version.
-- 
-- This software is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
-- 
-- You should have received a copy of the GNU General Public License
-- along with this package; see the file COPYING.  If not, write to
-- the Free Software Foundation, Inc., 59 Temple Place - Suite 330,
-- Boston, MA 02111-1307, USA.  
--



EJ3 TOPo v2.2.178 - Wed.5-I-2005


READ LICENSE AND COPYING!!!


Author: Emilio José Jiménez Jiménez
Email: ej3soft@ej3.net
Web: http://ej3soft.ej3.net

IMPORTANT: Please send me your modifications if you change anything in this script so that i can 
           integrate them in future releases.
           

-------------------------------

Description:

TOPo is a free TOP system written in PHP that works without MySQL database.
TOPo is specially designed for web sites hosted in web servers that not offer a quality MySQL support. 


-------------------------------

Features:

NO needed MySQL database: All data is store in plain text files. 
Triple anti-cheating system: Cookies for client browser, IPs store in the server and anti-redirection to prevents automatic voting scripts. 
Category support.
Multi-page support.
Build-in rating system: Rate member function for visitors. 
Build-in review system: Let visitor leave a comment about any site.
GZIP compress support.
Complete control panel: All options are easy to change. 
Auto deletion of idle sites: Time configurable. 
Easy insert of ad-banners: Just copy & paste. 
Stats info with graphics: Exclusive RATIO feature. 
Theme support: for changing topsite appearance and color schema using a Cascade Style Sheet (CSS).
Intelligent Preload of Banners feature. All banners are loaded an resized using JavaScript.
Multi-Banner support. Sites can submit more than one banner and TOPo will select a random one to display.
Personal Stats feature for users.
Search Engine Friendly feature. It optimize back-link to rank up your topsite in search engines like Google.
Flag and Country support.
Login information sent to email on signup. 
Lost password recovery for members. 
Easy to install and configure: Only upload the files and run setup.php 
Multi-language support: Translate only one file and get TOPo in your language. Currently English & Spanish are available. Please, if you translate TOPo to any language send us the translate file here. 

-------------------------------

Requirements:

Webspace with PHP v4.1 support.
TOPo have been development over a Apache+PHP platform running in Windows 98 SE and have been fully tested in Internet Explorer v5.5 

-------------------------------

Installation:

1.) READ README.TXT CAREFULLY!!!

2.) Create a ftp connection to your webserver and create a directory e.g. 
/topo within your website directory and set this folder permissions to 777.

3.) Go into the created directory!

4.) Unzip the topoXXXXX.zip on your local harddisk.

5.) Copy all unpacked files and directories to your webserver with your ftp program.

6.) Run install.php script in your browser and follow the instructions.

Have fun!!!

--------------------------------

Update from v2.x.xxx to v2.2.178

Install TOPo in a new directory of the server and then copy all the files of old DATA directory except INC_CONFIG.PHP to DATA directory of the new TOPo install directory.
For example, if you have two folders in the server:
	/TOPo_old
	/TOPo_new
copy all the files of /TOPo_old/data/ to a directory called c:/BACKUP in your local hard disk, then delete INC_CONFIG.PHP file of the c:/BACKUP directory and upload the others files to /TOPo_new/data/ directory of your server overwriting the existing ones and setting file permissions of all files that you upload to 666 (or 777).


Update from v1.x to v2.x.xxx

TOPo v2.x.xxx database is INCOMPATIBLE with TOPo v1.x series.
A converter script from v1.x -> v2.x.xxx is available in CONTROL PANEL -> TOOLs menu.

-------------------------------

History:

-> Wed.5-I-2005: TOPo v2.2.178   354846 bytes   8423 lines
	-Added a install script that set file permissions automatically
	-Added a users Online Counter that grouped users by country
	-Added complete performance info at the botton of the page
	-Added FLASH BANNERS support
	-Improve email mailed when register a new user
	-Fixed bug that prevent show Personal Stats
	-Fixed bug when set "bottom banner code"
	-Fixed bug in Multi-Banner feature when preload of banners is disabled
	-Fixed bug when show drop-down list to make login
	-Fixed bug when change page when a category select
	-...a long list of small changes and bugs fixed

-> Fri.26-IX-2003: TOPo v2.1.120   240893 bytes   5346 lines
	-Fully compatible with PHP v4.2 or superior
	-Added a complete TOPo info page
	-Improve security
	-Old vote links of TOPo v1.x series works without changes
	-Improve CONTROL PANEL -> WEBS SITES
	-Fixed problems when categories are enabled and zero categories are defined
	-Fixed bug in URLs send via email (double / removed)
	-...a long list of small changes and bugs fixed.

-> Mon.27-I-2003: TOPo v2.0.090   220305 bytes   4898 lines
	-Added a MASS E-MAIL option in CONTROL PANEL -> TOOLS
	-Improve CONTROL PANEL -> WEBS SITES
	-Fixed bug in AUTOBANNER function and added support for INTELLIGENT PRELOAD OF BANNERS.
	-Added new CSS files.
	-...a long list of small changes and bugs fixed.

-> Mon.20-I-2003: TOPo v2.0.081   214038 bytes   4763 lines
	-Added a tool to convert database format from v1.x to v2.x
	-Added SITE OF THE MOMENT feature.
	-Added PODIUM feature.
	-Template structure changed (old templates will need changes to work).
	-Some improvements in class_topo.php
	-Added a option that allows select show "All Categories" or "Unique Category" top after a vote.
	-Added support for {STATS_TEXT} tag in statistics template.
	-Added build.php file for easy maintenance of versions.
	-Added some news themes and CSS files.
	-Fixed bug in info.php: now "Last INs" are show when its number are >5
	-Fixed bug in AutoBanner function.
	-Fixed bug in "Next reset date".
	-Added dutch.php file (thanks to Bertus Holtman)
	-...and a long list of small changes and improves.

-> Sun.5-I-2003: TOPo v2.00beta
	-All code had been re-written using Object Oriented Methodology.
	-New CONTROL PANEL with advance category/webs browser.
	-Added CATEGORY support.
	-Added MULTI-PAGE support.
	-Added INTELLIGENT PRELOAD OF BANNERS feature. All banners are loaded an resized using JavaScript.
	-Added MULTI-BANNER support. Sites can submit more than one banner and TOPo will select a random one to display.
	-Added PERSONAL STATS feature.
	-Added a TEMPLATES based layout system. Now webmasters will have much more possibilities to customize their sites.
	-Re-write INFO SCREEN to shown more data about LAST INs, RATES BY COUNTRY...
	-Added SEARCH ENGINE FRIENDLY feature. It optimize back-link to rank up your topsite in search engines like Google.
	-Added FLAG & COUNTRY support.
	-Added possibility to customize WELCOME EMAIL.
	-...and much more small changes and improves!!

-> Fri.22-XI-2002: TOPo v1.43
	-Fixed "Sort by rate" bug (YES, NOW IT'S FIXED :-)
	-Added finnish.php file (thanks to Jari Kolehmainen).

-> Sun.17-XI-2002: TOPo v1.42
	-Fixed "Sort by rate" bug.
	-Added polish.php file (thanks to Piotr Janczyk).
	-Added turkish.php file (thanks to Severgezer).

-> Sat.12-X-2002: TOPo v1.41
	-Fixed "Sort by" bug.
	-Fixed bug that don't show stars when comments are disable.
	-Fixed bug that show "Warning: Division by zero" when sort the lys by average rate.
	-Added german3.php file (thanks to Markus Dange)
	-Added cyrillic.php file (thanks to Evrosex & CYI Studio)
	-Added danish.php file (thanks to Jeppe Vöge)

-> Fri.30-VIII-2002: TOPo v1.4
	-TOPoColors editor allows you to change easily all the colors of the top list.
	-Re-write Control Panel with new options and JavaScript validation of form fields.
	-Re-write install/setup script for easy correct installation of TOPo.
	-Added possibility to enable/disable comments system.
	-Added possibility to enable/disable rate system.
	-Added possibility to sort the list by: PARCIAL IN, PARCIAL OUT, TOTAL IN, TOTAL OUT & AVERAGE RATE
	-Added GZIP support to compress top list page: Now TOPo run more fast!!.
	-Added $performance variable to show or hide TOPo performance variables.
	-Added italian.php file (thanks to Andrea Arduino)
	-Added portuguese.php file (thanks to Felipe Pessoto)
	-Added english2.php file (thanks to Lolla)
	-Added german2.php file (thanks to Dano Cortello)
	-Fixed "From: [admin email]" when send emails.
	-Fixed bug when show zero sites without banner. 
	-Fixed bug when using ' and " in web site name and description.
	-Fixed bug when submit a new site more that one time.
	-Small changes in TOPoTags.
	-Small bugs fixed.

-> Sun.16-VI-2002: TOPo v1.31
	-Added ducth.php file (thanks to Kevin Brouns)
	-Added french.php file (thanks to Dano Cortello)
	-Added light_grey.css file (thanks to Vraxor)

-> Tue.4-VI-2002: TOPo v1.3
	-TOPoTags: new feature that allows you to customize the way of TOPo construct the list
	-Added german.php file (thanks to Dano Cortello)
	-Improve anti-cheating gateway.
	-Fixed bug in "Send email" window in control panel. 
	-New comment counter routine to solve all old problems.
	-Fixed a problem with redirections in in.php file.
	-Fixed a problem when change admin password in control panel.
	-Small bugs fixed.

-> Sun.19-V-2002: TOPo v1.2
	-New system for validate new webs before list them in the Top
	-New feature to email members from control panel.
	-New AutoBanner feature that allow member sites to show their banner in the TOP list
	-Fixed bug when edit HTML code for banners in control panel
	-Comments counter fixed
	-Now $topURL variable is set automatic
	-Fixed bug in section Top Config of control panel: now is possible change numeric values.
	-Small bugs fixed.

-> Mon.13-V-2002: TOPo v1.1
	-PHP v4.0 compatible: key_exists() and array_key_exists() functions change for isset() function
	-Top name bug fixed: Now is possible change the top name in the control panel
	-Added support for Metatags in control panel
	-Added support for copyright info in control panel
	-Fixed bug in HTML code mailed to get votes
	-Small bugs fixed.

-> Thu.9-V-2002: TOPo v1.0
	-This is the first version, therefore ALL FEATURES ARE NEW!! :-)

-------------------------------

Greetings:

Special thx to my parents Araceli & Emilio, they gave me the life and I only give them problems.

¡Hala Union!
Up the Irons!

-------------------------------

Emilio José Jiménez Jiménez
ej3soft@ej3.net
http://ej3soft.ej3.net

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

******************
Requirements:
******************
PHP version > 4.1.0
MySQL 3.23.15

******************
Script features
******************
- Quick and easy instalation
- Working on MySQL with optimal configuration
- Use PHP-sessions for authorization
- Multilanguage
- Can automatically detect user language and use it
- HTML direction and charset for each language file
- Last N registered users on main page
- Quick search on main page
- Quests access may be disabled for any page
- Register by send confirm letter to email (for non-bad emails)
- Admin can allow or delete new profiles before register
- "How did you find us?" question in register
- 34 field and 3 photo for uploading
- Can set max user photo, width and height
- Can set smaller and bigger age for dating
- Forgot password feature
- Easy Template (config, header, footer, css file)
- Easy your language translate feature
- User can edit your profile
  - delete own profile (if you set allow in config file)
  - Upload or change current photo
- (Anybody|Only members) can search in this dating by many parameters (country, gender, horoscope, category, with photo or all profiles, how many results on page and more)
- Hide mail from all users (Can be set to show) - anyone can write letters from feedback like form in the site
- Feedback with webmaster

Admin function
- Simple search users
- Allow/Edit/Delete users
- Optimize tables
- Repair tables
- Remove members 

******************
Installing script
******************
1) Upload all files and folders to your website.
2) Make chmod 666 of config.inc.php, options.inc.php. And chmod 777 for members/uploads directory (chmod is only for UNIX systems)
3) Run install.php to setup script
4) Delete install.php for secure reasons !!!!!

Thats all!
******************          
Admin page - http://your_installed_path/admin.php
Main page - http://your_installed_path/index.php
Forum support - http://www.azdg.com/forum/

******************
Upgrade script
******************
from 2.1.0 to 2.1.1
Upload new files:
index.php
members/index.php
admin/index.php

Also if you want you can add new language (Croatian). Upload new flag and language files:
images/flags/hr.gif
languages/hr/*

from 2.0.5 to 2.1.0
Be carefull, you don`t need to upload install.php. 
Install.php only for first installation - not for upgrade
Require to replace all files except for templates/ directory

from 2.0.4 to 2.0.5
Upload new files:
AzDGDatingLite/*
AzDGDatingLite/admin/index.php
AzDGDatingLite/images/flags/ (it.gif,fr.gif,bg.gif,tr.gif)
AzDGDatingLite/languages/ (New dirs it,fr,bg,tr)
AzDGDatingLite/members/index.php

from 2.0.3 to 2.0.4
Upload new languages directories (se,nl,no) and new flags in images/flags (se.gif,nl.gif,no.gif)
Upload new files: check.php. mail.php, feedback.php, members/index.php, remind.php

from 2.0.2 to 2.0.3
Small changes (view ChangeLog.txt)
Upload new languages directories (ar,ge,gr,dk) and new flags in images/flags (ar.gif,ge.gif,gr.gif,dk.gif)

from 2.0.1 to 2.0.2
Replace all files (except of include/* images/* classes/* files), because several small changes has been fixed in every file.

from 2.0.0 to 2.0.1
upload new files: include/security.inc.php,add.php,members/index.php,admin/index.php,languages/de/de.php,languages/en/en.php,languages/ru/ru.php,languages/default/default.php
Run upgrade200-201.php
Delete upgrade200-201.php for secure reasons

from 1.2.1 to 2.0.0
No such way to upgrade script, because in new script you can find very many fields than old. Try wait simple upgrade script.

from 1.2.0 to 1.2.1
Change new config.inc.php (in 1.2.1 added only 1 variable $img_bugs) 
Upload new files: add.php, config.inc.php, header.php, secure.php, footer.php, install.php, members/pic.php, all language/* directories, all images/flags dir
Drop $mysql_online table, and create it again with install.php


from 1.1.0 to 1.2.0
upload all files and dirs except images/*, stat/
Change config.inc.php
Run install.php to setup new MySQL table for online users

from 1.0.3 to 1.1.0
Change new config.inc.php. 
Upload new files: 
config.inc.php 
add.php 
members/index.php
search.php
languages/default/default.php

from 1.0.2 to 1.0.3
Change new config.inc.php. 
Upload new files: config.inc.php, add.php, members/index.php

from 1.0.1 to 1.0.2
Simple upload new add.php instead old file in your server!

******************
Templates
******************
You can install your template in ./template/Your_temp/ dir

For use this script under your site design you can:
- Change some configurations in config.php
- Change config.php, header.php and footer.php
********
Note: Don`t change style names likes .head, .mes, .dat and other`s - you must only change variations in there tags !!!
********


******************
License script
******************

GNU GENERAL PUBLIC LICENSE
Please read and agree with gpl.txt file          

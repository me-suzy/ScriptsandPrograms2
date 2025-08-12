<?php 
## +-----------------------------------------------------------------------+
## | BTT-License [TM] :.:.: License Distribution                   		   |
## +-----------------------------------------------------------------------+
## | Copyright (c)1998 - 2005 BTT Scripts Inc.				               |
## +-----------------------------------------------------------------------+
## | This source file is subject to the BTT Scripts Inc. End User License  |
## | Agreement (EULA), that is bundled with this package in the file       |
## | LICENSE, and is available at through the world-wide-web at            |
## | http:
## | If you did not receive a copy of the BTT Scripts Inc. license and are |
## | unable to obtain it through the world-wide-web, please send a note    |
## | to license@btt-scripts.com so we can email you a copy immediately.    |
## +-----------------------------------------------------------------------+
## | Authors: BTT Scripts Inc. <bttsoft@btt-scripts.com>     			   |
## | Support: http:
## | Questions? bttsupport@btt-scripts.com                                 |
## +-----------------------------------------------------------------------+
## | BTT, BTT-Crypt, BTT-Support, BTT-Bill, BTT-Web, BTT-Panel, BTT-Bugs,  |
## | BTT-News , BTT-AdCenter , BTT-LiveOnline , BTT-License, and BTT-Faq   |
## | are trademarks of BTT Scripts Inc. BTT Scripts is a subdivision of    |
## | BTT-Hosting Inc. All rights reserved and enforced.                    | 
## +-----------------------------------------------------------------------+
GLOBAL $script_url,$dbhost,$dbuser,$dbpass,$dbname,$https;
## [THIS IS YOUR CONFIG SETTINGS FOR THE APPLICATION]
## --------------------------------------------------
## [ENABLE HINTS ON APPLICATION]
## -----------------------------
$hint = true;
## [SET DISPLAY LIMITS ON APPLICATION]
## -----------------------------------
$limit = 25;
## [EDIT YOUR DATABASE SETTINGS]
## -----------------------------
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = 'password';
$dbname	= 'bttlicenses';
## [EDIT ADMIN LOGIN PREFIX, NOT REQUIRED]
## ---------------------------------------
$prefix = 'btt';
## [EDIT CLOSED MESSAGE HERE]
## --------------------------
$why_are_we_closed  = 'We are closed for server maintaince';
## [EDIT YOUR PAGE SETTINGS]
## -------------------------
$admin_page = 'admin.inc.php';
$user_page  = 'users.inc.php';
$login_page = 'index.php';
## [EDIT YOUR SCRIPT PATHS]
## ------------------------
$standard_url = '127.0.0.1/license-1.9.0/';# <-- ENTER YOURS HERE!
$secure_url = '127.0.0.1/license-1.9.0/';# <-- ENTER YOURS HERE!
$https = 'http';

?>
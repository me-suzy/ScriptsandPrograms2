<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 11th May 2005                           #||
||#     Filename: constants.php                          #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**

	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package main

*/

// Database Tables
define("TBL_ADMINSECTIONS", "`".$config['prefix']."adminsections`");
define("TBL_CATEGORY", "`".$config['prefix']."category`");
define("TBL_COMMENTS", "`".$config['prefix']."comments`");
define("TBL_EMOTICON", "`".$config['prefix']."emoticons`");
define("TBL_MENUSECTIONS", "`".$config['prefix']."menu_sections`");
define("TBL_MENU", "`".$config['prefix']."menu`");
define("TBL_NEWS", "`".$config['prefix']."news`");
define("TBL_NEWSCONFIG", "`".$config['prefix']."newsconfig`");
define("TBL_SEND", "`".$config['prefix']."senddb`");
define("TBL_THEMES", "`".$config['prefix']."themes`");
define("TBL_UGROUPS", "`".$config['prefix']."usergroups`");
define("TBL_USERS", "`".$config['prefix']."users`");

/* these are all for administration */
define("PAGE_HOME", "index.php");
define("PAGE_LOGIN", "login.php");
define("PAGE_NEWS", "news.php");
define("PAGE_CONFIG", "newsconfig.php");
define("PAGE_CAT", "category.php");
define("PAGE_EMOTICON", "emoticons.php");
define("PAGE_DB", "database.php");
define("PAGE_USER", "user.php");
define("PAGE_THEME", "themes.php");
define("PAGE_COMMENT", "comment.php");
define("PAGE_UGROUP", "usergroup.php");
define("PAGE_UPDATE", "update.php");

// misc
define("LINE_BREAK", "<br />");

?>

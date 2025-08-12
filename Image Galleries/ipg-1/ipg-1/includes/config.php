<?php

/******************************************************************************
* IPG: Instant Photo Gallery                                                  *
* =========================================================================== *
* Software Version:             IPG 1.0                                       *
* Copyright 2005 by:            Verosky Media - Edward Verosky                *
* Support, News, Updates at:    http://www.instantphotogallery.com            *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the GNU General Public License as published by the Free  * 
* Software Foundation; either version 2 of the License, or (at your option)   *
* any later version.                                                          *
* This program is distributed WITHOUT ANY WARRANTIES; without even any        *
* implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    *
*                                                                             *
* See www.gnu.org  for details of the GPL license.                            *
******************************************************************************/


/******************************************************************************

        CONFIGURATION:  The following is a series of configuration settings
	That should be changed to work on your web server.  This file,
	"config.php" should only be edited with a text editor that will not
	alter the basic formatting.  That means do not use a "rich text editor"
	like Word or Wordpad.  Instead, use Notepad, vi, or even a web page
	editor like Dreamweaver that knows how to leave plain text intact.
	
	Each of the configuration settings below is explained in full.
	Don't remove quotes around the settings (i.e. 'my-setting').

******************************************************************************/


/******************************************************************************

        SERVER_CONFIG:  This should be set to 'remote' if you are using
	this file and the package on a working website.  However, if you
	are running the package on your computer (with webserver capabilities)
	for the purpose of working on the look or the files, you can set this
	to 'local'.  This allows you to have two different configurations,
	for two different working environments (development or production).

******************************************************************************/

define('SERVER_CONFIG', 'remote');

/*****************************************************************************

        PDB_PREFIX:  Since many websites use a single MySQL database to run
	several applications, it's a good idea to prefix the tables IPG will
	import into your database with something unique.  This will differen-
	tiate this package's tables from others you might have that have the
	same names.  You can leave this setting alone, or change it if you
	already have tables that start with "ipg_" which you probably don't.

*****************************************************************************/
         
define('PDB_PREFIX', 'ipg_');

/*****************************************************************************

        THE FOLLOWING CONFIGURATION SETTINGS ARE FOR THE ACTUAL 
        PRODUCTION WEBSITE.  INSTRUCTIONS ARE PROVIDED FOR EACH.

*****************************************************************************/	
switch (SERVER_CONFIG) {  
	case 'remote':
	{

/*****************************************************************************

        APP_DIR:  If you are setting up this package under the main root
	directory of your site (e.g. "public_html", etc.) then leave the setting
	as '/'.  If you are not using this as your main site, but installing
	it in a sub-directory, use '/directory-name/' instead -- where
	'directory-name' is the name you're giving to this directory.

*****************************************************************************/

define('APP_DIR', '/');

/*****************************************************************************

        SITE_URL:  Your full website URL with no trailing slash.

*****************************************************************************/
																
define('SITE_URL', 'http://www.your-website.com');

/*****************************************************************************

        DB_NAME:  The name of your database as provided by your hosting co.
	DB_HOST:  The host machine of your database, usually 'localhost'.
	DB_USER:  The database username.
	DB_PASS:  The database password.
	---------------------------------------------------------------------
	Note: The database username and password are often the same as
	the username/password combination your use to access your website's
	hosting control panel.  Your hosting company should provide you with
	this information if you do not have it.

*****************************************************************************/
		
define('DB_NAME', 'your-database-name');
define('DB_HOST', 'localhost');
define('DB_USER', 'your-database-username');
define('DB_PASS', 'your-database-password');
		
/****************************************************************************

        Most should not alter this anything beyond this point. However,
	if you are developing on this package and need to run it on your
	local machine (computer equipped with a web server, PHP, and MySQL
	setup) you can alter the configuration settings as you wish.

****************************************************************************/

		define('DB_DEBUG', true);
		define('DB_DIE_ON_FAIL', true);

		define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'] . APP_DIR);

		//PATHS::IMG
		define('DIR_IMAGES', APP_ROOT . 'images');
		define('DIR_PORTFOLIOS', DIR_IMAGES . '/portfolios'); 
		define('IMAGE_URL', SITE_URL . APP_DIR . 'images');
		define('PORTFOLIO_IMAGE_URL', IMAGE_URL . '/portfolios');
		
		break;
	}


	case 'local':
	{
		//PATHS
		define('APP_DIR', '/portfolios/');              // The name of the application directory
	    define('SITE_URL', 'http://localhost');		// Basic URL of the site
		define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'] . APP_DIR);	// The application root
	
		//PATHS::IMG
		define('DIR_IMAGES', APP_ROOT . 'images');	// images directory
		define('DIR_PORTFOLIOS', DIR_IMAGES . '/portfolios');	// portfolios image URL path

		define('IMAGE_URL', SITE_URL . APP_DIR . 'images');		// image URL path
		define('PORTFOLIO_IMAGE_URL', IMAGE_URL . '/portfolios');       // model image URL path
		
		//DATABASE
		define('DB_NAME', 'portfolio');			// Database name
		define('DB_HOST', 'localhost');			// Database host machine
		define('DB_USER', 'root');			// Database username
		define('DB_PASS', '');				// Database password

		define('DB_DEBUG', true);
		define('DB_DIE_ON_FAIL', true);

		break;
	}

}//end switch

session_start();

// Hangle Magic Quotes

if (!get_magic_quotes_gpc()) slashString_gpc();

 function slashString($v) {
   return is_array($v) ? array_map('slashString', $v) : addslashes($v);
 }
 
 function unSlashString($v) {
   return is_array($v) ? array_map('unSlashString', $v) : stripslashes($v);
 }

 function slashString_gpc() {
   foreach (array('POST', 'GET', 'REQUEST', 'COOKIE') as $gpc)
   $GLOBALS["_$gpc"] = array_map('slashString', $GLOBALS["_$gpc"]);
 }

define('COPYRIGHT_NOTICE', '<font face="Arial, Helvetica, sans-serif" size="-2"><a href="http://www.instantphotogallery.com"><font color="#CCCCCC">Powered 
        by Instant Photo Gallery - &copy; 2005 Verosky Media</font></a></font>');


?>
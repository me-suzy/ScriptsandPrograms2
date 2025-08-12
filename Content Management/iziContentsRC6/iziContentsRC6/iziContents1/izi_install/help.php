<?php

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/
 
$GLOBALS["version"] = "1RC6";
 
 //	Define the page headings
$GLOBALS["Titles"]["Main"]		= 'iziContents CMS Version '.$GLOBALS["version"];
$GLOBALS["Titles"]["Installation"]	= 'iziContents Version '.$GLOBALS["version"].' Installation';
$GLOBALS["Titles"]["License"]		= 'iziContents Version '.$GLOBALS["version"].' Licence Agreement';
$GLOBALS["Titles"]["Modules"]		= 'iziContents CMS Version '.$GLOBALS["version"].' Modules';
$GLOBALS["Titles"]["Languages"]		= 'iziContents CMS Version '.$GLOBALS["version"].' Languages';
$GLOBALS["Titles"]["Database"]		= 'iziContents CMS Version '.$GLOBALS["version"].' Database Configuration';
$GLOBALS["Titles"]["Upgrade"]		= 'iziContents CMS Version '.$GLOBALS["version"].' Upgrade';
$GLOBALS["Titles"]["NewInstall"]	= 'iziContents CMS Version '.$GLOBALS["version"].' New Installation';
$GLOBALS["Titles"]["InstallLog"]	= 'iziContents Version '.$GLOBALS["version"].' Installation Log';
$GLOBALS["Titles"]["Config"]		= 'iziContents Version '.$GLOBALS["version"].' Configuration settings';
//	Help Texts
$GLOBALS["Help"]["Test"]			= 'This form displays the basic system requirements that iziContents needs to run, and determines if your system meets those minimum requirements.';
$GLOBALS["Help"]["PHPVersion"]		= 'The minimum version of PHP that is required for this version of iziContents.';
$GLOBALS["Help"]["RegisterGlobals"]	= 'If PHP is configured with \'Register Globals\' set to \'On\' this is a serious security flaw on your server.'.chr(10).chr(10).'iziContents will still work with this setting either \'On\' or \'Off\'; but we strongly advise you to reconfigure your PHP installation.';
$GLOBALS["Help"]["GDGraphics"]		= 'If PHP has been built with the GD Graphics module, then iziContents will use this, otherwise certain graphic features can only be simulated.'.chr(10).chr(10).'GD is not a prerequisite for iziContents, nor is .GIF support within GD; but it is useful.';
$GLOBALS["Help"]["mbstring"]		= 'If you plan to use the multi-lingual features of iziContents for languages that use different character sets, then the PHP Multi-byte (mbstring) extension should be available.'.chr(10).chr(10).'The mbstring extension is not a prerequisite for iziContents, unless you plan to provide translations in several languages that use different character sets.';
$GLOBALS["Help"]["SafeMode"]		= 'For security reasons, some ISPs run PHP in \'Safe Mode\' and this can restrict the functionality available in iziContents.'.chr(10).chr(10).'It does not mean that iziContents cannot run, although this depends exactly how \'Safe Mode\' has been configured.';
$GLOBALS["Help"]["OpenBasedir"]		= 'For security reasons, some ISPs restrict filesystem access within PHP using an \'Open_Basedir\' setting, and this can restrict the functionality available in iziContents if it is mis-set.'.chr(10).chr(10).'It does not mean that iziContents cannot run - a properly set \'open_basedir\' will not restrict functionality; but it is frequently set incorrectly by ISPs.';
$GLOBALS["Help"]["FileUploads"]		= 'For security reasons, some ISPs prevent http from uploading files to the server.'.chr(10).chr(10).'If this restriction is enforced, then you will not be able to upload files using iziContents, but will need to do so using ftp.';
$GLOBALS["Help"]["MySQLVersion"]	= 'The minimum version of the MySQL database that is required for this version of iziContents.';
$GLOBALS["Help"]["Apache"]			= 'Although iziContents will run on most web servers, but we would recommend using Apache version 1.3.26 or above.';
$GLOBALS["Help"]["WriteableFiles"]	= 'A number of directories need to have read/write privilege to use all the facilities of iziContents. Additionally, the iziContents configuration file must be writeable while this install script is running.'.chr(10).chr(10).'These include the /contentimage, /downloads and /scripts directories used for uploads, the /backup directory used for database backups, admin/styles/icache used for dynamic image cacheing, admin/excelexport/excelexporter/temp used for dynamic creating excel-files, and the /sites and /themes directories used for the multi-site and multi-theme options.';

$GLOBALS["Help"]["Modules"]			= 'Use this form to select which modules you wish to install.'.chr(10).chr(10).'If you decide not to install any modules now, you can always install them later through the admin functions.';
$GLOBALS["Help"]["calendar"]		= 'This is a simple calendar display, showing the current month and allowing viewers to page backward and forward from month to month and year to year.';
$GLOBALS["Help"]["diary"]			= 'The \'diary\' module is a version of calendar that allows you to record events. It is available either as a calendar display, listing events against dates, or as a simple list of events. The event list is also available as an \'inline\' version that can be included within the content of a page.'.chr(10).chr(10).'Optionally, users can also be allowed to submit events to the diary.';
$GLOBALS["Help"]["gallery"]			= 'This module provides a basic image gallery, displaying thumbnails of selected images and allowing viewers to click on any image to display it full size.'.chr(10).chr(10).'It does not yet allow viewers to submit images to the gallery.';
$GLOBALS["Help"]["guestbook"]		= 'The guestbook module allows visitors to enter comments about your site, or even just general comments.';
$GLOBALS["Help"]["links"]			= 'The \'links\' module provides a list of links to other sites that you can maintain.'.chr(10).chr(10).'Optionally, users can also be allowed to submit their own links to the list.';
$GLOBALS["Help"]["news"]			= 'Use this module for maintaining news items on your site.'.chr(10).'News is available as a module, or as an \'inline\' version that can be included within the content of a page.'.chr(10).chr(10).'Optionally, users can also be allowed to submit their own news items.';
$GLOBALS["Help"]["poll"]			= 'Set multiple-choice questions for your visitors to answer either as a module or an inline version.'.chr(10).'The style of poll is determines by one of two modes: a single answer from a series, or multiple answers (check all that apply).'.chr(10).chr(10).'User submission of polls is not supported at this point, and only registered members of the site can vote.';
$GLOBALS["Help"]["reviews"]			= 'The \'review\' module allows you to have a list of reviews for films, books, games, etc. and to give these a \'star rating\'.'.chr(10).chr(10).'Optionally, users can also be allowed to submit their own reviews.';
$GLOBALS["Help"]["search"]			= 'The \'search\' module allows users to search for specific content articles on your site.';
$GLOBALS["Help"]["sitemap"]			= 'Displays a hierarchical \'map\' of the menus on your site.';
$GLOBALS["Help"]["sitestats"]		= 'This module hasn\'t yet been written, but if you have site visitor statistics enabled it will allow users to view those statistics.'.chr(10).'Basically it will eventually be a \'front-end\' version of the admin \'View Statistics\' function';
$GLOBALS["Help"]["toprated"]		= 'Can be used to display a list of the \'top ten\' rated articles.';
$GLOBALS["Help"]["whatsnew"]		= 'Allows the viewers to see what content on your site has been added, updated (or even expired) since their last visit.';
$GLOBALS["Help"]["ModuleSelectAll"]		= 'Select all modules';
$GLOBALS["Help"]["ModuleSelectNone"]	= 'Select none of the modules';

$GLOBALS["Help"]["Languages"]	= 'Use this form to select which languages you wish to install, and select your default language.'.chr(10).'You must install at least one language.'.chr(10).chr(10).'If you decide not to install any additional languages now, you can always install them later through the admin functions.';
$GLOBALS["Help"]["da"]			= 'Danish'.chr(10).chr(10).'Partial fileset.';
$GLOBALS["Help"]["de"]			= 'German.';
$GLOBALS["Help"]["en"]			= 'English.';
$GLOBALS["Help"]["es"]			= 'Spanish.'.chr(10).chr(10).'Not yet available: only included in this list for testing the installation procedure.';
$GLOBALS["Help"]["fr"]			= 'French.';
$GLOBALS["Help"]["hu"]			= 'Hungarian.'.chr(10).chr(10).'Not yet available: only included in this list for testing the installation procedure.';
$GLOBALS["Help"]["it"]			= 'Italian.'.chr(10).chr(10).'Not yet available: only included in this list for testing the installation procedure.';
$GLOBALS["Help"]["nl"]			= 'Dutch.';
$GLOBALS["Help"]["pl"]			= 'Polish.';
$GLOBALS["Help"]["pt"]			= 'Portuguese.'.chr(10).chr(10).'Not yet available: only included in this list for testing the installation procedure.';
$GLOBALS["Help"]["ru"]			= 'Russian.';
$GLOBALS["Help"]["zh"]			= 'Chinese (Big5).';
$GLOBALS["Help"]["LanguageSelectAll"]	= 'Select all languages';
$GLOBALS["Help"]["LanguageSelectNone"]	= 'Deselect all languages';
$GLOBALS["Help"]["Config"]			= '!! For security issues you should delete the folder "/izi_install" in your homedirectory !!'.chr(10).chr(10).'If this folder is not deleted manually, iziContents will ask you to do so on first startup.';
$GLOBALS["Available"]["da"]		= 'Yes';
$GLOBALS["Available"]["de"]		= 'Yes';
$GLOBALS["Available"]["en"]		= 'Yes';
$GLOBALS["Available"]["es"]		= 'No';
$GLOBALS["Available"]["fr"]		= 'Yes';
$GLOBALS["Available"]["hu"]		= 'No';
$GLOBALS["Available"]["it"]		= 'No';
$GLOBALS["Available"]["nl"]		= 'Yes';
$GLOBALS["Available"]["pl"]		= 'Yes';
$GLOBALS["Available"]["pt"]		= 'No';
$GLOBALS["Available"]["ru"]		= 'Yes';
$GLOBALS["Available"]["zh"]		= 'Yes';

$GLOBALS["Help"]["Database"]		= 'To install iziContents, the installation procedure needs some information about your database. This form is for you to provide the script with that information.'.chr(10).chr(10).'Once you have entered all the necessary information, click on the \'Submit Database Details\' button to start the installation or upgrade.';
$GLOBALS["Help"]["InstallType"]		= 'First, you need to select whether this is a new installation, or whether you are upgrading from an older iziContents Version.';
$GLOBALS["Help"]["InstallNew"]		= 'Select this option for a new installation.';
$GLOBALS["Help"]["InstallUpgrade"]	= 'Select this option to upgrade an existing iziContents database to version '.$GLOBALS["version"].'.';
$GLOBALS["Help"]["CreateDatabase"]	= 'This is only appropriate for a new installation, not for an upgrade.'.chr(10).'Should the installation script create a new database for you, or use an existing database that you have created manually?'.chr(10).chr(10).'IMPORTANT NOTE - If the installation process \'hangs\' after you click on \'Submit Database Details\', you may need to manually create an empty database, then rerun this script to build and populate the data tables.';
$GLOBALS["Help"]["NewDatabase"]		= 'The iziContents installer should create the database as part of the installation process.';
$GLOBALS["Help"]["OldDatabase"]		= 'The database has been created manually.';
$GLOBALS["Help"]["DBDriver"]		= 'Select the appropriate database driver for MySQL.'.chr(10).chr(10).'If you don\'t know, then simply use \'MySQL\' rather than \'MySQL Transactional\'.';
$GLOBALS["Help"]["DBAddress"]		= 'You need to point iziContents to the address of the database server box.'.chr(10).'The database may be on the same box as your webserver, in which case use the default value of \'localhost\; otherwise enter the appropriate IP address.';
$GLOBALS["Help"]["DBName"]			= 'If you are upgrading from a previous installation of iziContents, enter the name of your database here.'.chr(10).chr(10).'If this is a new installation, you will need to enter a name for your database. This should be kept reasonably short (for simplicity) and should not contain any unusual characters such as spaces or punctuation marks.';
$GLOBALS["Help"]["DBLogin"]			= 'iziContents needs to know the login name for MySQL. Enter that information here.';
$GLOBALS["Help"]["DBPassword"]		= 'iziContents needs to know the password for connecting to MySQL. Enter that information here.';
$GLOBALS["Help"]["DBPrefix"]		= 'You can prefix the iziContents table names to ensure that they are unique if you are likely to be sharing a database with other users or systems.'.chr(10).'If you wish to use a prefix, enter it here. This prefix should be kept reasonably short (for simplicity) and should not contain any unusual characters such as spaces or punctuation marks (although underscores are permitted).';
$GLOBALS["Help"]["DBPersistent"]	= 'Persistent connections can improve the performance of iziContents; but your database needs to be configured to use them, and they limit the number of concurrent users that can connect to the database.'.chr(10).chr(10).'If in doubt, do NOT use Persistent Connections.';
$GLOBALS["Help"]["InstallLog"]		= 'You can log all database commands executed during the install or upgrade. If the script fails, this log can help identify the cause of the problem'.chr(10).'This log will be displayed in the pop-up debug window.';
$GLOBALS["Help"]["LogNo"]			= 'Don\'t enable logging.';
$GLOBALS["Help"]["LogYes"]			= 'Enable logging.';
$GLOBALS["Help"]["Manualinstall"]	= 'Enabling this, Database operations will be set out.'.chr(10).'You will need to install the database manually after the Instalation routine has finished'.chr(10).'You can fillout the rest of the form to write the configuration.';

$GLOBALS["Help"]["NewInstall"]			= 'Installation is now complete, and you can log in to iziContents with a username of \'admin\' and a password of \'izicontents\'. Note that usernames and passwords are case sensitive.';
$GLOBALS["Help"]["UpgradeInstall"]		= 'The upgrade is now complete, and you can log in to iziContents with your normal username and password. Remember that usernames and passwords are case sensitive.';
$GLOBALS["Help"]["SecurityComments"]	= chr(10).chr(10).'For security reasons, we would now recommend that you delete the "izi_install" subdirectory, together with the install.sql files in the language and modules subdirectories.';


//	Define the traffic light icons
$GLOBALS["Checks"]["green"]		= '<img src="../images/green_dot.gif">&nbsp;';
$GLOBALS["Checks"]["orange"]	= '<img src="../images/orange_dot.gif">&nbsp;';
$GLOBALS["Checks"]["red"]		= '<img src="../images/red_dot.gif">&nbsp;';


$GLOBALS["MaxFileSize"] = 262144;	//	256k
?>
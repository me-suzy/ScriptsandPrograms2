<?php

/***************************************************************************

 config.php
 -----------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

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

$GLOBALS["Version"]			= "Version 1 rc6";
$GLOBALS["gsAdminStyle"]		= "pixel";

// Database Configuration
$GLOBALS["dbPersistent"]		= "N";			//	Persistent database connections
								//		'Y'es or 'N'o
$GLOBALS["ezContentsDB"]		= "mysql";		//	Database server type
$GLOBALS["ezContentsDBServer"]		= "DbSevername";		//	IP Address of the database server
$GLOBALS["ezContentsDBName"]		= "DBName";		//	Database name
$GLOBALS["ezContentsDBLogin"]		= "DbUsername";		//	Database system login
$GLOBALS["ezContentsDBPassword"]	= "DbPassword";			//	Database system password


$GLOBALS["admin_home"]		= "admin/";		// admin directory
$GLOBALS["language_home"]		= "languages/";		// language base directory
$GLOBALS["image_home"]		= "contentimage/";	// user images
$GLOBALS["downloads_home"]		= "downloads/";		// user downloads
$GLOBALS["style_home"]		= "admin/styles/";	// Extra external style sheet
$GLOBALS["font_home"]		= "admin/styles/fonts/";	// Extra fonts
$GLOBALS["script_home"]		= "scripts/";		// external php script files
$GLOBALS["icon_home"]		= "images/";		// ezContents internal icons
$GLOBALS["modules_home"]		= "modules/";		// add-in modules base directory
$GLOBALS["backup_home"]		= "backup/";		// ezContents database backup directory
$GLOBALS["include_home"]		= "include/";		// include file base directory
$GLOBALS["sites_home"]		= "sites/";		// multi-site base directory
$GLOBALS["themes_home"]		= "themes/";		// multi-theme base directory


// ezContents Table Names
$GLOBALS["eztbPrefix"]		= '';
$GLOBALS["eztbMasterPrefix"]	= $GLOBALS["eztbPrefix"];
$GLOBALS["eztbSessions"]		= $GLOBALS["eztbMasterPrefix"]."sessions";
$GLOBALS["eztbSites"]		= $GLOBALS["eztbMasterPrefix"]."sites";
$GLOBALS["eztbVisitorstats"]	= $GLOBALS["eztbMasterPrefix"]."visitorstats";
$GLOBALS["eztbFunctiongroups"]	= $GLOBALS["eztbMasterPrefix"]."functiongroups";
$GLOBALS["eztbFunctions"]		= $GLOBALS["eztbMasterPrefix"]."functions";
$GLOBALS["eztbUsergroups"]		= $GLOBALS["eztbMasterPrefix"]."usergroups";
$GLOBALS["eztbPrivileges"]		= $GLOBALS["eztbMasterPrefix"]."userprivileges";
$GLOBALS["eztbCountries"]		= $GLOBALS["eztbMasterPrefix"]."countries";
$GLOBALS["eztbContinents"]		= $GLOBALS["eztbMasterPrefix"]."continents";
$GLOBALS["eztbLanguages"]		= $GLOBALS["eztbMasterPrefix"]."languages";
$GLOBALS["eztbModules"]		= $GLOBALS["eztbMasterPrefix"]."modules";
$GLOBALS["eztbAuthors"]		= $GLOBALS["eztbMasterPrefix"]."authors";
$GLOBALS["eztbThemes"]		= $GLOBALS["eztbMasterPrefix"]."themes";
$GLOBALS["eztbSettings"]		= $GLOBALS["eztbMasterPrefix"]."settings";
$GLOBALS["eztbUserdata"]		= $GLOBALS["eztbMasterPrefix"]."userdata";
$GLOBALS["eztbBanners"]		= $GLOBALS["eztbMasterPrefix"]."banners";
$GLOBALS["eztbTopgroups"]		= $GLOBALS["eztbMasterPrefix"]."topgroups";
$GLOBALS["eztbGroups"]		= $GLOBALS["eztbMasterPrefix"]."groups";
$GLOBALS["eztbSubgroups"]		= $GLOBALS["eztbMasterPrefix"]."subgroups";
$GLOBALS["eztbTagCategories"]	= $GLOBALS["eztbMasterPrefix"]."tagcategories";
$GLOBALS["eztbTags"]		= $GLOBALS["eztbMasterPrefix"]."tags";
$GLOBALS["eztbSidebartemplates"]	= $GLOBALS["eztbMasterPrefix"]."sidebartemplates";
$GLOBALS["eztbImageformattemplates"]= $GLOBALS["eztbMasterPrefix"]."imageformattemplates";
$GLOBALS["eztbContents"]		= $GLOBALS["eztbMasterPrefix"]."contents";
$GLOBALS["eztbRatings"]		= $GLOBALS["eztbMasterPrefix"]."ratings";
$GLOBALS["eztbSpecialcontents"]	= $GLOBALS["eztbMasterPrefix"]."specialcontents";
$GLOBALS["eztbModuleSettings"]	= $GLOBALS["eztbMasterPrefix"]."modulesettings";
$GLOBALS["eztbFiletypes"]		= $GLOBALS["eztbMasterPrefix"]."filetypes";


// Other Configuration Variables
$GLOBALS["RECORDS_PER_PAGE"]		= 15;			// number of lines of content on admin list pages
// Tag block controls
$GLOBALS["tqBlock"]			= '[]';
$GLOBALS["tqBlock1"]			= substr($GLOBALS["tqBlock"], 0, 1);
$GLOBALS["tqBlock2"]			= substr($GLOBALS["tqBlock"], -1, 1);
$GLOBALS["tqCloseBlock"]		= '/';
$GLOBALS["tqSeparator"]			= ',';

$GLOBALS["locale"]			= '';			// Unix locale code if not using the machine default
											// value

$GLOBALS["ShowFilePermissions"]		= 'N';
$GLOBALS["uploadmethod"]			= 'http:';				// Valid values are 'http:' and 'ftp:'
$GLOBALS["ftp"]["server"]	= 'localhost';
$GLOBALS["ftp"]["port"]		= 21;
$GLOBALS["ftp"]["username"]	= 'anonymous';
$GLOBALS["ftp"]["password"]	= 'secret password';
$GLOBALS["ftp"]["ezContents_root"]	= '/';


$imagedir = "/".$GLOBALS["image_home"];
$imageurl = "/".$GLOBALS["image_home"];
$scriptdir = "/include/htmlarea2/popups/";
$scripturl = "/include/htmlarea2/popups/";

$download = "downloads";


// Filesystem == /home/web/www (your web path)
$basedir = "";

// Website URL
$websiteurl = "http://www.yourdomain.com";

$GLOBALS["bDebug"] = False;

if ($GLOBALS["bDebug"]) { error_reporting( E_ALL & ~ E_NOTICE ); }
else { error_reporting( E_ERROR | E_PARSE ); }

?>

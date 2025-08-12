<?php
	Require("ADV/index.php");
	Require("Rights/index.php");


	//************************************************************************************
	//*																					*
	//*	Enter the default data into the databases. Read from the NavSQL files the nav	*
	//*		code to put into the database.												*
	//*																					*
	//************************************************************************************
	Function Defaults($DBConnection)
	{
		Global $PHPJK_VERSION;
		Global $sNewDirLocation;
		Global $sNewDirName;
		Global $sAdminLogin;
		Global $sAdminPassword;
		Global $sThumbnailType;
		Global $sEmailType;
		Global $sEMAIL_REMOTEHOST;
		
		// Enter other configuration variables
		DB_Insert ("INSERT INTO Configurations VALUES ('ACCOUNTS_SIGNUP','OPEN','If this is set as \"OPEN\", then anyone can signup for a new account. If it''s set as \"CLOSED\", then administrators must create them in the Manage Accounts screen.',1,'','',1,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('EMAIL_LOGIN_FROMNAME','Customer Support','Used when a user is retrieving their login/password. This is the name of the sender.',1,'','',0,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('EMAIL_LOGIN_FROMEMAIL','customersupport@YourWebSiteHere.com','Used when a user is retrieving their login/password. This is the email address of the sender.',1,'','',0,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('EMAIL_REMOTEHOST','" . $sEMAIL_REMOTEHOST . "','Email server used to route messages. Usually like mail.YourWebSiteHere.com. Leave blank to disable emails.',1,'','',0,'Email',1,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('SEND_ADMIN_REG_EMAIL','YES','Set this to YES to send an email to the administrator when a user registers for a new account. Set it to NO otherwise.',1,'','',0,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('SEND_ADMIN_REG_EMAIL_DEST','administrator@YourWebSiteHere.com','This is the email address to send the SEND_ADMIN_REG_EMAIL email to.',1,'','',0,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('SEND_ADMIN_REG_FROMNAME','PHPJK Administrator','This is the name of the user who sends the SEND_ADMIN_REG_EMAIL email.',1,'','',0,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('SEND_ADMIN_REG_FROMEMAIL','newuser@YourWebSiteHere.com','This is the email address of the user who sends the SEND_ADMIN_REG_EMAIL email.',1,'','',0,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('SEND_ADMIN_REG_DESTNAME','Administrator','This is the name of the user who receives the SEND_ADMIN_REG_EMAIL email.',1,'','',0,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('SEND_USER_REG_EMAIL','YES','Set this to YES to send an email to the user when they register for a new account. Set it to NO otherwise.',1,'','',0,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('SEND_USER_REG_EMAIL_SOURCE','administrator@YourWebSiteHere.com','This is the email address to send the SEND_USER_REG_EMAIL email from.',1,'','',0,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('SEND_USER_REG_EMAIL_NAME','Administrator','This is the name of the person who is sending the email to SEND_USER_REG_EMAIL.',1,'','',0,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('EMAIL_TYPE','" . $sEmailType . "','Either ASPMAIL, CDONTS or SENDMAIL. This is the method of sending emails. Leave blank to disable email functionality.',1,'','PHPJK_MC_CHANGE_EMAIL_METHOD',0,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('ACCOUNTS_AUTHENTICATION','YES','Set to YES to enable user authentication. Set to anything Else to disable it.',1,'','',1,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('PHP_JK_WEBROOT','" . $sNewDirLocation . "/','This is the web root drive and directory as shown in IIS. Required for cross-domain file manipulation.',1,'PHPJK_CONF_VIEW_WEBROOT','PHPJK_CONF_EDIT_WEBROOT',8,'',0,0)", $DBConnection);

		// Image Gallery Specific
		DB_Insert ("INSERT INTO Configurations VALUES ('IG','/PHPJK/Galleries','This is the Image Gallery directory name (eg: Galleries). The location of the gallery files can be found by appending the PHP_JK_WEBROOT Configuration Variable to the beginning of this one.',1,'','',7,'',1,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_FORMCOLUMNS','76','Width of text area form fields in the image gallery - also see IMAGEGALLERY_FORMWIDTH.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_FORMWIDTH','75','Width of form fields in the image gallery - not of text fields - see IMAGEGALLERY_FORMCOLUMNS for that.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_THUMBNAIL_WIDTH','100','Width of the thumbnails. Display and creation. In pixels.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_MISSING_THUMBNAIL','/PHPJK/Images/Blank.gif','Image to display instead if the thumbnail is missing.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_SCRIPTTIMEOUT','72000','Script timeout in seconds. Used when uploading images/files. If less than value in metabase, then it uses value in metabase.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_USEALPHA','YES','Set this to YES to display thumbnails with an alpha channel rollover. Or NO to display with no rollover.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_SUGGESTCATEGORY_TO_EMAIL','suggestcat@YourWebSiteHere.com','This is the email address gallery category suggestions are sent to.',1,'','',7,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_SUGGESTCATEGORY_FROM_EMAIL','suggestcat@YourWebSiteHere.com','This is the FROM: email used when sending category suggestion emails.',1,'','',7,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_SUGGESTCATEGORY_FROM_NAME','PHPJK','This is the from name used when sending category suggestion emails.',1,'','',7,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_SUGGESTGALLERY_TO_EMAIL','suggestgal@YourWebSiteHere.com','This is the email address gallery suggestions are sent to.',1,'','',7,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_SUGGESTGALLERY_FROM_EMAIL','suggestgal@YourWebSiteHere.com','This is the FROM: email used when sending gallery suggestion emails.',1,'','',7,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_SUGGESTGALLERY_FROM_NAME','PHPJK','This is the from name used when sending gallery suggestion emails.',1,'','',7,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_CAT_COLUMNS','2','This is the number of columns to display in the Category Display Action',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_CAT_IMAGE_LOC','/PHPJK/Images/CategoryImages/','This is the Image Gallery category image directory name (default: /Images/CategoryImages/). Must have write/delete access for IUSR account.',1,'','',7,'',1,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_CAT_IMAGE_WIDTH','40','This is the pixel width of the category image (default 40).',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_CAT_IMAGE_HEIGHT','40','This is the pixel height of the category image (default 40).',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_INITIAL_HD_SPACE','10000000','This is the amount of disk space each user initially gets. In bytes. Set to -1 for unlimited.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_INITIAL_MAX_NUM_GALLERIES','10','This is the initial number of galleries each user can create. Set to -1 for unlimited.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_INITIAL_NUMUPLOAD','-1','The default number of files new users can upload to their galleries. Set to -1 for unlimited.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_INITIAL_UPLOADTYPE','gif jpg png txt zip bmp jpeg pdf doc','The default file type new users can upload.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_INITIAL_UPLOADBYTES','250000','The default max number of bytes per file new users can upload. Set to -1 for unlimited.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_INITIAL_NUMDOWNLOADS','-1','The default max number of files new users can download each day. Set to -1 for unlimited.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_NONMMBR_NUMDOWNLOADS','-1','The max number of files nonmembers can download each day. Set to -1 for unlimited.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_THUMBNAILVIEW_NUMCOLUMNS','4','This is the number of columns to display the thumbnails in the galleries on the thumbnail view page.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_MAX_DISPLAYABLE_PRODUCTS','3','This is the max number of products that will be displayed on with each image. If more than this number of products is assigned to the image, this number of products will be chosen and displayed at random.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_PRODUCTIMAGES_MAX_WIDTH','120','All thumbnail images associated with products will be displayed this width (in pixels).',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_NAME','Administrator','The name of the sender of subscription emails.',1,'','',7,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_EMAIL','Subscriptions@YourWebsiteHere.com','The email address of the sender of subscription emails.',1,'','',7,'Email',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_ALTIMAGE_VIEW','RIGHTS','Change this to set how to display Alternate View image links on the image display page. ALL (everyone can see), RIGHTS (display based on rights a member has), MEMBERS (all, but only, members), or NONE (do not display at all).',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DISP_OWNER_INFO','YES','Change this to YES to display the gallery owners name and allow users to see that owners list of galleries. Set it to NO to not display the gallery owners name or link.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DISP_VOTING','YES','Set this to YES to display the voting links so users can rate images. Set to NO to not display it. Also removes the current rating.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_NEWIMAGE_DAYS','2','Set this to the number of days old images can be to have the NEW image displayed with them.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_ALLOW_ALL_DISPLAY','NO','Set this to YES to allow users to list all images within all galleries within a category and all its subcategories (very CPU intensive). Set to NO to disallow this.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DISP_OWNER_COL','YES','Set this to YES to display the owners column on the gallery listing page. Set to NO to hide this column.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_SEND_ECARD','ALL','Set this to ALL or MEMBERS. ALL allows every user to send eCards, MEMBERS allows only registered users to send eCards.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DISP_NEW_IMAGE_SEARCH','YES','Set this to YES to display the New Image Search button. Set to NO not to display it.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DISP_CAT_GAL_MAP','YES','Set this to YES to display the Category Map button. Set to NO not to display it.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DISP_SUGGEST_GALLERY','YES','Set this to YES to display the Suggest Gallery button. Set to NO not to display it.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DISP_SUGGEST_CATEGORY','YES','Set this to YES to display the Suggest Category button. Set to NO not to display it.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DISP_SUBSCRIBE_GALLERY','YES','Set this to YES to display the Subscribe to Gallery button. Set to NO not to display it.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DISP_SUBSCRIBE_CATEGORY','YES','Set this to YES to display the Subscribe to Category button. Set to NO not to display it.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DISP_LINES_ON_CATEGORY_DISPLAY','YES','Set this to YES to display the lines and graphics on the category display panel. Set to NO not to display them.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DISP_IMAGE_ON_CATEGORY_DISPLAY','RIGHT','Set this to RIGHT to display the category image on the right in the category display panel. Set to TOP to display it above the category name, or NONE not to display it at all.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_NUM_GAL_LIST_ON_CATEGORY_DISPLAY','4','This is the number of galleries to list on the category display panel.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_NUM_CAT_LIST_ON_CATEGORY_DISPLAY','4','This is the number of subcategories to list on the category display panel.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_GDISPLAY_COLUMNS','ONE','This is the number of columns on the gallery display page. There can only be ONE or TWO.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_MAX_THUMBS_DISPLAYED','50','This is the maximum number of thumbnails users can display on the thumbnail view page.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('PHPJK_IMAGEGALLERY_VERSION','" . $PHPJK_VERSION . "','Please do not change this Configuration Variable. This is used when update scripts are run.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_DOMAINCHECK','YES','Set this to NO to disable source domain checking when displaying gallery images/files. When this is disabled, anyone can use images/files from your site on their site by referencing them.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_THUMBNAIL_GENERATOR','" . $sThumbnailType . "','Set which component creates thumbnails. Set to GFL to use the GflAx xnview.com component. Set to PHP to use the native PHP (for *nix boxes). Leave blank for no thumbnail creation.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_GFLAX_VER','L','If using GflAx, enter the version. \"L\" for Lite, \"S\" for Standard, or \"F\" for Full.',1,'','',7,'',0,0)", $DBConnection);
		
		DB_Insert ("INSERT INTO Configurations VALUES ('ARICAUR_WEBMASTER_ID','','This is your Aricaur.com Webmaster ID#.',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('ARICAUR_WEBMASTER_PASSCODE','','This is your Aricaur.com passcode.',1,'','',7,'',0,0)", $DBConnection);
		
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_SITEURL','','URL of the PHPJK Site. This URL should end at the root of the PHPJK gallery folder. So, if you have put the PHPJK pages in a folder called /mygallery/ then this should be http://www.mywebsite.com/mygallery/',1,'','',7,'',0,0)", $DBConnection);
		DB_Insert ("INSERT INTO Configurations VALUES ('IMAGEGALLERY_HIGHVOLUME_DISPLAY','NO','Set this to YES to display the categories in a more streamlined method for sites with many images.',1,'','',7,'',0,0)", $DBConnection);


		// Enter the ADV's and the first account's data (first name, last name, address, etc.)
		ADV($DBConnection);

		// Create the first administrator account
		DB_Insert ("INSERT INTO Accounts (Login,Password,AddDate,RemoveDate,HomeDomain,IgnoreGlobal,Authenticated,AuthID) VALUES ('" . $sAdminLogin . "', '" . $sAdminPassword . "', GetDate(), NULL,1,'','T',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountDomainMap VALUES (1,1)", $DBConnection);
		
		// Create all the rights and set the admin's rights
		SetRights($DBConnection);
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*	Create all the tables and indexes for the tables								*
	//*																					*
	//************************************************************************************
	Function CreateTables($DBConnection)
	{
		Global $sUseDB;
		
		If ( $sUseDB == "MSSQL" ){
			mssql_select_db($GLOBALS["sDatabaseName"], $DBConnection)
				or
				Die ("Could not connect to the database: ".mssql_get_last_message());

			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'Accounts') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE Accounts", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'AccountDomainMap') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE AccountDomainMap", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'AccountMap') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE AccountMap", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'AccountData') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE AccountData", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'Rights') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE Rights", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'RightsLookup') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE RightsLookup", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'InitialRights') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE InitialRights", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'DomainInfo') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE DomainInfo", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'Configurations') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE Configurations", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'TextConstants') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE TextConstants", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'Galleries') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE Galleries", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'ImagesInGallery') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE ImagesInGallery", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'Images') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE Images", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'PrivateAccounts') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE PrivateAccounts", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGCategories') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGCategories", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGECards') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGECards", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGCopyrights') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGCopyrights", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGImageCRs') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGImageCRs", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGPLs') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGPLs", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGPLProds') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGPLProds", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGImageProds') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGImageProds", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGMiscLinks') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGMiscLinks", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGSearches') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGSearches", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGSearchResults') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGSearchResults", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IG_Subscriptions') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IG_Subscriptions", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGAllIView') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGAllIView", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGAllIViewResults') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGAllIViewResults", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGRaters') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGRaters", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGMap') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGMap", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGData') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGData", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGSubPL') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGSubPL", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGSubPLProds') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGSubPLProds", $DBConnection);
			DB_Query ("if exists (select * from dbo.sysobjects where id = object_id(N'IGSubPLUse') and OBJECTPROPERTY(id, N'IsUserTable') = 1) DROP TABLE IGSubPLUse", $DBConnection);

			$sIdentity	= "IDENTITY";
			$sClustered	= "CLUSTERED";
			$sDate		= "SMALLDATETIME";
		}ElseIf ( $sUseDB == "MYSQL" ){
			mysql_select_db($GLOBALS["sDatabaseName"], $DBConnection)
				or
				Die ("Could not connect to the database: ".mysql_error());
			
			$sIdentity	= "AUTO_INCREMENT PRIMARY KEY";
			$sClustered	= "";	// can't say UNIQUE here because MSSQL CLUSTERED is NOT always unique (as in the case of IGMiscLinks)
			$sDate		= "DATETIME";
			
			// MySQL doesn't freak if it tries to drop a table that's not there
			//	And we aren't dropping the tables from MSSQL because PHP won't allow multiple line queries, so we can't
			//	do the if/then statement necessary to drop in MSSQL (MSSQL freaks if you try to drop a table that's not there)
			DB_Query ("DROP TABLE IF EXISTS Accounts", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS AccountDomainMap", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS AccountMap", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS AccountData", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS Rights", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS RightsLookup", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS InitialRights", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS DomainInfo", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS Configurations", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS TextConstants", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS Galleries", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS ImagesInGallery", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS Images", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS PrivateAccounts", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGCategories", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGECards", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGCopyrights", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGImageCRs", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGPLs", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGPLProds", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGImageProds", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGMiscLinks", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGSearches", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGSearchResults", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IG_Subscriptions", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGAllIView", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGAllIViewResults", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGRaters", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGMap", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGData", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGSubPL", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGSubPLProds", $DBConnection);
			DB_Query ("DROP TABLE IF EXISTS IGSubPLUse", $DBConnection);
		}

		DB_Query ("CREATE TABLE Accounts(AccountUnq INT " . $sIdentity . ",Login VARCHAR(32) NOT NULL,Password VARCHAR(32) NOT NULL,AddDate DATETIME, RemoveDate DATETIME  NULL,HomeDomain INT NOT NULL,IgnoreGlobal	CHAR(3) NOT NULL, Authenticated CHAR(3) NOT NULL, AuthID INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX AccountsMain on Accounts (Login)", $DBConnection);

		DB_Query ("CREATE TABLE AccountDomainMap(AccountUnq INT NOT NULL, DomainUnq INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX AccountDomainMap1 on AccountDomainMap (AccountUnq, DomainUnq)", $DBConnection);

		DB_Query ("CREATE TABLE AccountMap(MapName VARCHAR(255) NOT NULL,DataType CHAR(3),Description VARCHAR(255) NOT NULL,Required CHAR(3) NOT NULL,Visible CHAR(3) NOT NULL,DomainUnq	INT NOT NULL,SystemUnq INT NOT NULL,RightsLvl VARCHAR(255) NOT NULL,GroupUnq INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX AccountMapMain on AccountMap (DomainUnq, SystemUnq, MapName)", $DBConnection);

		DB_Query ("CREATE TABLE AccountData(MapName VARCHAR(255) NOT NULL,AccountUnq INT NOT NULL,VarCharData VARCHAR(255) NOT NULL,TextData TEXT NOT NULL,ViewLvl VARCHAR(7) NOT NULL,DomainUnq INT NOT NULL,HomeDomain CHAR(3) NOT NULL,LastChange DATETIME NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX AccountDataMain on AccountData (DomainUnq, AccountUnq, MapName)", $DBConnection);

		DB_Query ("CREATE TABLE Rights(AccountUnq INT NOT NULL,GroupUnq INT NOT NULL,SystemUnq INT NOT NULL,DomainUnq INT NOT NULL,RightsLvl VARCHAR(255) NOT NULL,GrantDate DATETIME NOT NULL,RevokeDate DATETIME NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX RightsMain on Rights (DomainUnq, SystemUnq, RightsLvl, AccountUnq)", $DBConnection);

		DB_Query ("CREATE TABLE RightsLookup(DomainUnq INT NOT NULL,RightsLvl VARCHAR(255) NOT NULL,RightsConst VARCHAR(255) NOT NULL,SystemUnq INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX RightsLMain on RightsLookup (DomainUnq, SystemUnq, RightsLvl)", $DBConnection);
	
		DB_Query ("CREATE TABLE InitialRights(DomainUnq INT NOT NULL, SystemUnq INT NOT NULL, RightsLvl VARCHAR(255) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX InitialRightsMain on InitialRights (DomainUnq, SystemUnq)", $DBConnection);
	
		DB_Query ("CREATE TABLE DomainInfo( DomainUnq INT " . $sIdentity . ", Description VARCHAR(255) NOT NULL, Domain  VARCHAR(255) NOT NULL,Type CHAR(3) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX DomainInfoMain on DomainInfo (DomainUnq)", $DBConnection);
		DB_Query ("CREATE INDEX DomainInfoName on DomainInfo (Domain)", $DBConnection);

		DB_Query ("CREATE TABLE Configurations( ConfConst VARCHAR(255) NOT NULL, Value VARCHAR(255) NOT NULL, Description VARCHAR(255) NOT NULL, DomainUnq INT NOT NULL,RightLvlv VARCHAR(255) NOT NULL,RightLvlc VARCHAR(255) NOT NULL,SystemUnq INT NOT NULL,Type VARCHAR(255) NOT NULL,ServerUnq INT NOT NULL,GroupUnq INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX ConfMain on Configurations (DomainUnq, ConfConst)", $DBConnection);
		
		DB_Query ("CREATE TABLE TextConstants( TextConstants VARCHAR(255) NOT NULL, Value TEXT NOT NULL, Description VARCHAR(255) NOT NULL, DomainUnq INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX TextConstantsMain on TextConstants (TextConstant)", $DBConnection);

		// Image Gallery Specific
		DB_Query ("CREATE TABLE Galleries( GalleryUnq INT " . $sIdentity . ", AccountUnq INT NOT NULL, Domain INT NOT NULL, Name VARCHAR(32) NOT NULL, Description TEXT NOT NULL, Visibility CHAR(2) NOT NULL, CategoryUnq INT NOT NULL, PopupWindow VARCHAR(32) NOT NULL, ConfUnq INT NOT NULL, ThreadUnq INT NOT NULL, Position INT NOT NULL, UserName VARCHAR(255) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX GalleriesMain on Galleries (GalleryUnq)", $DBConnection);

		DB_Query ("CREATE TABLE ImagesInGallery( ImageUnq INT, GalleryUnq INT, AddDate " . $sDate . ", DomainUnq INT NOT NULL,Position INT NOT NULL,PrimaryG INT NOT NULL,PrimaryD INT NOT NULL,Rating INT NOT NULL,NumRaters INT NOT NULL,NumViews INT NOT NULL,ThreadUnq INT NOT NULL,ConfUnq INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX ImagesInGalleryMain on ImagesInGallery (GalleryUnq, ImageUnq)", $DBConnection);
		DB_Query ("CREATE INDEX IIGSecondary ON ImagesInGallery (DomainUnq, GalleryUnq, ImageUnq)", $DBConnection);

		DB_Query ("CREATE TABLE Images( ImageUnq INT " . $sIdentity . ", Comments TEXT NOT NULL, AltTag VARCHAR(255) NOT NULL, Image VARCHAR(255) NOT NULL, Thumbnail VARCHAR(255) NOT NULL, Rating INT, NumRaters INT, NumViews INT, ImageSize INT NOT NULL, XSize CHAR(4) NOT NULL, YSize CHAR(4) NOT NULL, ImageNum INT, FileType CHAR(32) NOT NULL, Image2 VARCHAR(255) NOT NULL, Image3 VARCHAR(255) NOT NULL, Image4 VARCHAR(255) NOT NULL, Image5 VARCHAR(255) NOT NULL, Image2Desc VARCHAR(255) NOT NULL, Image3Desc VARCHAR(255) NOT NULL, Image4Desc VARCHAR(255) NOT NULL, Image5Desc VARCHAR(255) NOT NULL, AltTag2 VARCHAR(255) NOT NULL, AltTag3 VARCHAR(255) NOT NULL, AltTag4 VARCHAR(255) NOT NULL, AltTag5 VARCHAR(255) NOT NULL, XSize2 CHAR(4) NOT NULL, YSize2 CHAR(4) NOT NULL, XSize3 CHAR(4) NOT NULL, YSize3 CHAR(4) NOT NULL, XSize4 CHAR(4) NOT NULL, YSize4 CHAR(4) NOT NULL, XSize5 CHAR(4) NOT NULL, YSize5 CHAR(4) NOT NULL, ImageSize2 INT NOT NULL, ImageSize3 INT NOT NULL, ImageSize4 INT NOT NULL, ImageSize5 INT NOT NULL, ConfUnq INT NOT NULL, ThreadUnq INT NOT NULL, Keywords TEXT NOT NULL, CookedComments TEXT NOT NULL, Title VARCHAR(255) NOT NULL, ImageUL INT NOT NULL,ThumbUL INT NOT NULL,Alt2UL INT NOT NULL,Alt3UL INT NOT NULL,Alt4UL INT NOT NULL,Alt5UL INT NOT NULL, EZPrints CHAR(3) NOT NULL, Aricaur VARCHAR(255) NOT NULL, AricaurThumb VARCHAR(255) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX ImagesMain on Images (ImageUnq)", $DBConnection);

		DB_Query ("CREATE TABLE PrivateAccounts( GalleryUnq INT, AccountUnq INT)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX PrivateAccountsMain on PrivateAccounts (GalleryUnq, AccountUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGCategories( CategoryUnq INT " . $sIdentity . ", DomainUnq INT NOT NULL, Name VARCHAR(255) NOT NULL, Description VARCHAR(255) NOT NULL, HasImage CHAR(3) NOT NULL, Position INT NOT NULL, Parent INT NOT NULL, NumChildren INT NOT NULL, RightsLvl VARCHAR(255) NOT NULL, TtlImages INT)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGCategoriesMain on IGCategories (CategoryUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGECards( CardUnq INT " . $sIdentity . ", DomainUnq INT NOT NULL, ImageUnq INT NOT NULL, DateSent " . $sDate . " NOT NULL, Title VARCHAR(255) NOT NULL, Message TEXT NOT NULL, SenderName VARCHAR(255) NOT NULL, SenderEmail VARCHAR(255) NOT NULL, BGColor CHAR(30) NOT NULL, BorderColor CHAR(30) NOT NULL, TextColor CHAR(30) NOT NULL, TFont CHAR(30) NOT NULL, MFont CHAR(30) NOT NULL, REmail VARCHAR(255) NOT NULL, RName VARCHAR(255) NOT NULL, Preview CHAR(3) NOT NULL, GalleryUnq INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGECardsMain on IGECards (DomainUnq, CardUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGCopyrights( CopyUnq INT " . $sIdentity . ", DomainUnq INT NOT NULL, URL VARCHAR(255) NOT NULL, Copyright VARCHAR(255) NOT NULL, Details TEXT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGCopyrightsMain on IGCopyrights (DomainUnq, CopyUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGImageCRs( ImageUnq INT NOT NULL, CopyUnq INT NOT NULL, GenericCopy TEXT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGImageCRsMain on IGImageCRs (ImageUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGPLs( PLUnq INT " . $sIdentity . ", DomainUnq INT NOT NULL, Name VARCHAR(255) NOT NULL, PLQueryText TEXT NOT NULL, ProdQueryText TEXT NOT NULL, SQLServer VARCHAR(255) NOT NULL, SQLLogin VARCHAR(255) NOT NULL, SQLPassword VARCHAR(255) NOT NULL, DSNName VARCHAR(255) NOT NULL, DSNLogin VARCHAR(255) NOT NULL, DSNPassword VARCHAR(255) NOT NULL, PurchURL VARCHAR(255) NOT NULL, DBName VARCHAR(255) NOT NULL, ImageURL VARCHAR(255) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGPLsMain on IGPLs (DomainUnq, PLUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGPLProds(ProdUnq INT " . $sIdentity . ",ProdID VARCHAR(32) NOT NULL,DomainUnq INT NOT NULL,Name VARCHAR(255) NOT NULL,Price VARCHAR(32) NOT NULL,URL VARCHAR(255) NOT NULL,ImageURL VARCHAR(255) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGPLProdsMain on IGPLProds (DomainUnq, ProdUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGImageProds(ImageUnq INT NOT NULL,ProdUnq INT NOT NULL,PLUnq INT NOT NULL,GalleryUnq INT NOT NULL,CategoryUnq INT NOT NULL,ProdID VARCHAR(32) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGImageProdsMain on IGImageProds (ImageUnq, ProdUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGMiscLinks( LinkUnq INT " . $sIdentity . ", ImageUnq INT NOT NULL, URL VARCHAR(255) NOT NULL, OnSite CHAR(3) NOT NULL, Description VARCHAR(255) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGMiscLinksMain on IGMiscLinks (ImageUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGSearches( AccountUnq INT NOT NULL, DateChanged " . $sDate . " NOT NULL, SearchID INT " . $sIdentity . " NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGSearchesMain on IGSearches (AccountUnq,SearchID)", $DBConnection);

		DB_Query ("CREATE TABLE IGSearchResults( AccountUnq INT NOT NULL, ImageUnq INT NOT NULL, GalleryUnq INT NOT NULL, CategoryUnq INT NOT NULL, SearchID INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGSearchResultsMain on IGSearchResults (ImageUnq,GalleryUnq,CategoryUnq,AccountUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IG_Subscriptions( CategoryUnq INT NOT NULL, GalleryUnq INT NOT NULL, AccountUnq INT NOT NULL,SentEmail CHAR(3) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IG_Subscriptions ON IG_Subscriptions (AccountUnq)", $DBConnection);
	
		DB_Query ("CREATE TABLE IGAllIView(AccountUnq INT NOT NULL,DateChanged " . $sDate . " NOT NULL,SearchID INT " . $sIdentity . " NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGAllIViewMain on IGAllIView (AccountUnq,SearchID)", $DBConnection);

		DB_Query ("CREATE TABLE IGAllIViewResults(AccountUnq INT NOT NULL,ImageUnq INT NOT NULL,GalleryUnq INT NOT NULL,CategoryUnq INT NOT NULL,SearchID INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGAllIViewResultsMain on IGAllIViewResults (ImageUnq,GalleryUnq,CategoryUnq,AccountUnq)", $DBConnection);
	
		DB_Query ("CREATE TABLE IGRaters(ImageUnq INT NOT NULL,AccountUnq INT NOT NULL,Rating INT NOT NULL,RateDate " . $sDate . " NOT NULL, GalleryUnq INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGRatersMain on IGRaters (ImageUnq, AccountUnq)", $DBConnection);
	
		DB_Query ("CREATE TABLE IGMap(MapUnq	INT " . $sIdentity . ",DataType CHAR(3) NOT NULL,Name	VARCHAR(255) NOT NULL,Description VARCHAR(255) NOT NULL,DomainUnq	INT NOT NULL,ImageUnq	INT NOT NULL,GalleryUnq	INT NOT NULL,CategoryUnq	INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGMapMain on IGMap (DomainUnq, MapUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGData(MapUnq INT NOT NULL,ImageUnq INT NOT NULL,GalleryUnq INT NOT NULL,CategoryUnq INT NOT NULL,VarCharData VARCHAR(255) NOT NULL,TextData TEXT NOT NULL,Position INT NOT NULL,Hidden CHAR(3) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGDataMain on IGData (CategoryUnq, GalleryUnq, ImageUnq, MapUnq)", $DBConnection);
	
		DB_Query ("CREATE TABLE IGSubPL(SubPLUnq INT " . $sIdentity . ",PLUnq INT NOT NULL,SubName VARCHAR(32) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGSubPLMain on IGSubPL (PLUnq, SubPLUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGSubPLProds(SubPLUnq INT NOT NULL,ProdID VARCHAR(32) NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGSubPLPMain on IGSubPLProds (SubPLUnq)", $DBConnection);

		DB_Query ("CREATE TABLE IGSubPLUse(SubPLUnq INT NOT NULL,CategoryUnq INT NOT NULL,GalleryUnq INT NOT NULL,ImageUnq INT NOT NULL)", $DBConnection);
		DB_Query ("CREATE " . $sClustered . " INDEX IGSubPLUMain on IGSubPLUse (SubPLUnq, CategoryUnq, GalleryUnq, ImageUnq)", $DBConnection);
	
	}
	//************************************************************************************
?>
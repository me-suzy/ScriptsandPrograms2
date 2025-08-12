<?php 
	//************************************************************************************
	//*																					*
	//*	Enters in the Accounting Data Variables (ADV).									*
	//*																					*
	//************************************************************************************
	Function ADV($DBConnection)
	{
		// Insert the actual ADV's
		// Insert each ADV into it's own group on this first domain - by making each of the last numbers unique
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_FirstName','V','First Name','Y','Y',1,0,'',1)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_MiddleName','V','Middle Name','N','Y',1,0,'',2)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_LastName','V','Last Name','Y','Y',1,0,'',3)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_HomeAddress1','V','Home Address','N','Y',1,0,'',4)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_HomeAddress2','V','Home Address','N','Y',1,0,'',5)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_HomeAddress3','V','Home Address','N','Y',1,0,'',6)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_HomeCity','V','Home City','N','Y',1,0,'',7)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_HomeState','V','Home State','N','Y',1,0,'',8)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_HomeZip','V','Home Zip','N','Y',1,0,'',9)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_WorkAddress1','V','Work Address','N','Y',1,0,'',10)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_WorkAddress2','V','Work Address','N','Y',1,0,'',11)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_WorkAddress3','V','Work Address','N','Y',1,0,'',12)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_WorkCity','V','Work City','N','Y',1,0,'',13)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_WorkState','V','Work State','N','Y',1,0,'',14)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_WorkZip','V','Work Zip','N','Y',1,0,'',15)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_HomePhone1','V','Home Phone 1','N','Y',1,0,'',16)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_HomePhone2','V','Home Phone 2','N','Y',1,0,'',17)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_WorkPhone1','V','Work Phone 1','N','Y',1,0,'',18)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_WorkPhone2','V','Work Phone 2','N','Y',1,0,'',19)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_MobilePhone','V','Mobile Phone','N','Y',1,0,'',20)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_HomeFax','V','Home Fax','N','Y',1,0,'',21)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_WorkFax','V','Work Fax','N','Y',1,0,'',22)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_EmailAddress','V','Email Address','Y','Y',1,0,'',23)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_ICQ','V','ICQ Number','N','Y',1,0,'',24)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_AIM','V','AIM Handle','N','Y',1,0,'',25)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_Yahoo','V','Yahoo IM Handle','N','Y',1,0,'',26)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_MSN','V','MSN IM Handle','N','Y',1,0,'',27)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_HomepageURL','V','Homepage URL','N','Y',1,0,'',28)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_BirthDay','V','Birth Day','N','Y',1,0,'',29)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_BirthMonth','V','Birth Month','N','Y',1,0,'',30)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_BirthYear','V','Birth Year','N','Y',1,0,'',31)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('Notes','T','Notes','N','Y',1,0,'',32)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IPAddress','V','IP Address','N','N',1,0,'',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_NumLogins','V','Number of times this user has logged in.','N','N',1,1,'',0)", $DBConnection);

		// Insert the first account's data (using the ADV)
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_FirstName',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_MiddleName',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_LastName',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_HomeAddress1',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_HomeAddress2',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_HomeAddress3',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_HomeCity',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_HomeState',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_HomeZip',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_WorkAddress1',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_WorkAddress2',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_WorkAddress3',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_WorkCity',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_WorkState',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_WorkZip',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_HomePhone1',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_HomePhone2',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_WorkPhone1',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_WorkPhone2',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_MobilePhone',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_HomeFax',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_WorkFax',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_EmailAddress',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_ICQ',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_AIM',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_Yahoo',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_MSN',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_HomepageURL',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_BirthDay',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_BirthMonth',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_BirthYear',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('Notes',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IPAddress',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_NumLogins',1,'','','PRIVATE',1,'T',GetDate())", $DBConnection);

		// Image Gallery Specific ADV's
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IG_NUMUPLOAD_LASTDATE','V','Last date/time the user uploaded a file: ','N','N',1,7,'',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IG_UPLOADTYPE','V','File types user can upload (seperate with space): ','N','N',1,7,'',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IG_UPLOADBYTES','V','Max file size user can upload in bytes (-1 infinite): ','N','N',1,7,'',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IG_TTLUPLOADABLE','V','Num bytes user is allowed to upload (-1 infinite): ','N','N',1,7,'',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IG_TTLUPLOADED','V','Num bytes user has uploaded: ','N','N',1,7,'',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IG_NUMALLOWED','V','Num files user is allowed to upload (-1 infinite): ','N','N',1,7,'',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IG_NUMUPLOADED','V','Num files user has uploaded: ','N','N',1,7,'',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IG_NUM_DL_DAY','V','Num files user is allowed to download each day (-1 infinite): ','N','N',1,7,'',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IG_NUM_HAS_DL','V','Num files user has downloaded today: ','N','N',1,7,'',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IG_DL_LASTDATE','V','Last date/time user downloaded an image: ','N','N',1,7,'',0)", $DBConnection);
		DB_Insert ("INSERT INTO AccountMap VALUES ('PHPJK_IG_NUM_GALLERIES','V','Num galleris user may create (-1 infinite): ','N','N',1,7,'',0)", $DBConnection);

		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IG_NUMUPLOAD_LASTDATE',1,'','','PRIVATE', 1, 'T', GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IG_UPLOADTYPE',1,'gif jpg png txt zip bmp jpeg pdf doc','','PRIVATE', 1, 'T', GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IG_UPLOADBYTES',1,'250000','','PRIVATE', 1, 'T', GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IG_TTLUPLOADABLE',1,'10000000','','PRIVATE', 1, 'T', GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IG_TTLUPLOADED',1,'','','PRIVATE', 1, 'T', GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IG_NUMALLOWED',1,'-1','','PRIVATE', 1, 'T', GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IG_NUMUPLOADED',1,'','','PRIVATE', 1, 'T', GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IG_NUM_DL_DAY',1,'-1','','PRIVATE', 1, 'T', GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IG_NUM_HAS_DL',1,'','','PRIVATE', 1, 'T', GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IG_DL_LASTDATE',1,'','','PRIVATE', 1, 'T', GetDate())", $DBConnection);
		DB_Insert ("INSERT INTO AccountData VALUES ('PHPJK_IG_NUM_GALLERIES',1,'-1','','PRIVATE', 1, 'T', GetDate())", $DBConnection);

	}
	//************************************************************************************
?>
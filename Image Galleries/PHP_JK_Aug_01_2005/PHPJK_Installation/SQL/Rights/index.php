<?php 
	//************************************************************************************
	//*																					*
	//*	Enters in the rights.															*
	//*																					*
	//************************************************************************************
	Function SetRights($DBConnection)
	{

		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MA_CREATE_NEW','Create NEW Accounts',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MA_REVOKE','REVOKE Accounts',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MA_UPDATE','UPDATE Accounts Login/Password''s',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MA_VIEW_PW','Rights to view passwords (update rights not required).',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MA_REINSTATE','REINSTATE Revoked Accounts',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MR_MODIFY','MODIFY Users Rights',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MR_VIEW','VIEW Users Rights',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MADV_ADD','ADD New ADV''s',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MADV_EDIT','EDIT ADV''s',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MADV_DELETE','DELETE ADV''s',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_UD_VIEW','VIEW Other Users Data',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_UD_EDIT','EDIT Users Data',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IR_UPDATE','Add and delete Initial Rights',1)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MCONF_CREATE_NEW','Create NEW Configuration Variables',8)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MCONF_DELETE','DELETE Configuration Variables',8)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MCONF_UPDATE','UPDATE Configuration Variables',8)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MCONG_EDIT_RIGHTS','View and Edit rights associated with Configuration Variables',8)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_MC_CHANGE_EMAIL_METHOD','Change the email method from ASPMail to CDONTS',8)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_CONF_VIEW_WEBROOT','Rights to view the PHP_JK_WEBROOT conf var.',8)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_CONF_EDIT_WEBROOT','Rights to edit the PHP_JK_WEBROOT conf var.',8)", $DBConnection);

		// Image Gallery Specific
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_CREATE_GALLERY','Create New Image Galleries',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ADMIN_ALL','Administer all Galleries for a domain',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ADD_CAT','Create New Image Gallery Categories',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_EDIT_CAT','Edit Image Gallery Categories',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_DEL_CAT','Delete Image Gallery Categories',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_BULK','Rights to add images to galleries in bulk.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ADD_CR','Add copyrights to the list of available copyrights.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_EDIT_CR','Edit copyrights in the list of available copyrights.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_DEL_CR','Delete copyrights from the list of available copyrights.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ADD_CR_2IMAGES','Associate copyrights with images.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ADD_PL','Add PLs.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_EDIT_PL','Edit PLs.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_DEL_PL','Delete PLs.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ADD_PROD','Add products not in a PL.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_EDIT_PROD','Edit products not in a PL.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_DEL_PROD','Delete products not in a PL.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ADD_PROD_2IMAGES','Manage Product(s) associated w/ images.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ADD_PROD_2GALLERIES','Manage Product(s) associated w/ galleries.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ALT_1','Rights to view the first alternate image.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ALT_2','Rights to view the second alternate image.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ALT_3','Rights to view the third alternate image.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ALT_4','Rights to view the fourth alternate image.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ADD_TO_NO_CAT','Rights to add galleries to no category.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ADD_CF_IMAGE','Add custom fields to images',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_EDIT_CF_DATA_IMAGE','Edit image custom field data',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_EDIT_CF_IMAGE','Edit image custom fields',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_DEL_CF_IMAGE','Delete image custom fields',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_REF_GALLERY','Rights to reference images in multiple galleries.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_ARICAUR','Rights to manage images Aricaur links.',7)", $DBConnection);
		DB_Insert ("INSERT INTO RightsLookup VALUES (1,'PHPJK_IG_PRIVATE','Rights to manage private galleries and their users (granting these rights allows the admin to see all user accounts).',7)", $DBConnection);
		
		// Give the admin ALL rights
		DB_Insert ("INSERT INTO Rights SELECT 1,0,0,1,RightsLvl,GetDate(),NULL FROM RightsLookup WHERE DomainUnq=1", $DBConnection);
	}
	//************************************************************************************
?>
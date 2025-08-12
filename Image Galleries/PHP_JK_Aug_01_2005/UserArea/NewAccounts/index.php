<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Config/i_NewAccounts.php");
	Require("i_SignUp.php");
	Require("i_ADV.php");
	Require("../../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	
	$iUBoundADVArray	= 0;
	$aADVArray			= "";
	$bHasADVRights		= "";
	$sLogin			= "";
	$sPassword		= "";
	$sOriginalLogin	= "";
	$sUserEmail		= "";
	$iAuthID		= 0;
	
	$PHPJK_FirstName = "";
	$PHPJK_FirstName_V = "";
	$PHPJK_MiddleName = "";
	$PHPJK_MiddleName_V = "";
	$PHPJK_LastName = "";
	$PHPJK_LastName_V = "";
	$PHPJK_BirthDay = "";
	$PHPJK_BirthDay_V = "";
	$PHPJK_BirthMonth = "";
	$PHPJK_BirthMonth_V = "";
	$PHPJK_BirthYear = "";
	$PHPJK_BirthYear_V = "";
	$PHPJK_HomeAddress1 = "";
	$PHPJK_HomeAddress1_V = "";
	$PHPJK_HomeAddress2 = "";
	$PHPJK_HomeAddress2_V = "";
	$PHPJK_HomeAddress3 = "";
	$PHPJK_HomeAddress3_V = "";
	$PHPJK_HomeCity = "";
	$PHPJK_HomeCity_V = "";
	$PHPJK_HomeState = "";
	$PHPJK_HomeState_V = "";
	$PHPJK_HomeZip = "";
	$PHPJK_HomeZip_V = "";
	$PHPJK_HomePhone1 = "";
	$PHPJK_HomePhone1_V = "";
	$PHPJK_HomePhone2 = "";
	$PHPJK_HomePhone2_V = "";
	$PHPJK_HomeFax = "";
	$PHPJK_HomeFax_V = "";
	$PHPJK_MobilePhone = "";
	$PHPJK_MobilePhone_V = "";
	$PHPJK_WorkAddress1 = "";
	$PHPJK_WorkAddress1_V = "";
	$PHPJK_WorkAddress2 = "";
	$PHPJK_WorkAddress2_V = "";
	$PHPJK_WorkAddress3 = "";
	$PHPJK_WorkAddress3_V = "";
	$PHPJK_WorkCity = "";
	$PHPJK_WorkCity_V = "";
	$PHPJK_WorkState = "";
	$PHPJK_WorkState_V = "";
	$PHPJK_WorkZip = "";
	$PHPJK_WorkZip_V = "";
	$PHPJK_WorkPhone1 = "";
	$PHPJK_WorkPhone1_V = "";
	$PHPJK_WorkPhone2 = "";
	$PHPJK_WorkPhone2_V = "";
	$PHPJK_WorkFax = "";
	$PHPJK_WorkFax_V = "";
	$PHPJK_EmailAddress = "";
	$PHPJK_EmailAddress_V = "";
	$PHPJK_HomepageURL = "";
	$PHPJK_HomepageURL_V = "";
	$PHPJK_ICQ = "";
	$PHPJK_ICQ_V = "";
	$PHPJK_AIM = "";
	$PHPJK_AIM_V = "";
	$PHPJK_Yahoo = "";
	$PHPJK_Yahoo_V = "";
	$PHPJK_MSN = "";
	$PHPJK_MSN_V = "";

	Main();
	Require("../../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $sUserEmail;
		Global $bHasRemoteHost;
		Global $iAuthID;
		Global $sPassword;
		Global $sLogin;
		Global $sSiteURL;
		
		$sError = "";

		If ( strtoupper(Trim(DOMAIN_Conf("ACCOUNTS_SIGNUP"))) == "OPEN" ) {
			// Anyone can sign up
			$bMustAuthenticate	= strtoupper(Trim(DOMAIN_Conf("ACCOUNTS_AUTHENTICATION")));
			$bHasRemoteHost		= DOMAIN_Has_RemoteHost();
			$sUserEmail			= Trim(Request("PHPJK_EmailAddress"));
			$sAction			= Trim(Request("sAction"));
			
			If ( $sAction == "" ) {
				$PHPJK_BirthYear = "19";
				GetCustomADVs();
				WriteADVForm("ADD");
			}Else{
				$sLogin		= Trim(Request("sLogin"));
				$sPassword	= Trim(Request("sPassword"));
				If ( $sLogin == "" ) {
					$sError = "Please enter your login.<br>";
				}Else{
					If ( LoginExists($sLogin) )
						$sError = "That login already exists. If you are trying to reinstate your account, <br>please email customer support. Or, try another login.<br>";
				}
				If ( $sPassword == "" )
					$sError = $sError . "Please enter a password.<br>";
				
				// Check all other things in the form field to see if they are required. if so then make sure they are full				
	            ForEach ($_POST as $sADV=>$sValue)
	            {
					$sQuery			= "SELECT * FROM AccountMap (NOLOCK) WHERE MapName = '" . SQLEncode($sADV) . "'";
					$rsRecordSet	= DB_Query($sQuery);
					if ( $rsRow = DB_Fetch($rsRecordSet) ){
						If ( Trim($rsRow["Required"]) == "Y" ) {
							If ( Request($sADV) == "" )
								$sError = $sError . "Please enter something into the " . $rsRow["Description"] . " form field.<br>";
						}
					}
					
	            }
	            
		
				If ( $sError == "" ) {
					// Make their account
					If ( ( $bMustAuthenticate == "YES" ) && ( $bHasRemoteHost ) ) {
						// don't mark their account as authenticate yet
						srand();
						$iAuthID = rand(100000, 100000000);
						DB_Insert ("INSERT INTO Accounts (Login,Password,AddDate,RemoveDate,HomeDomain,IgnoreGlobal,Authenticated,AuthID) VALUES ('" . SQLEncode($sLogin) . "', '" . SQLEncode($sPassword) . "', GetDate(), NULL, 1,'','', " . $iAuthID . ")");
					}Else{
						DB_Insert ("INSERT INTO Accounts (Login,Password,AddDate,RemoveDate,HomeDomain,IgnoreGlobal,Authenticated,AuthID) VALUES ('" . SQLEncode($sLogin) . "', '" . SQLEncode($sPassword) . "', GetDate(), NULL, 1,'','T',0)");
					}
	
					$sQuery			= "SELECT @@IDENTITY";
					$rsRecordSet	= DB_Query($sQuery);
					if ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						$sAccountUnq = $rsRow[0];
						// add all the ADV's to the account
						DB_Insert ("INSERT INTO AccountData SELECT MapName, " . $sAccountUnq . ", '', '', 'PRIVATE', 1, 'T', GetDate() FROM AccountMap");
						
						// Populate the ADV's
			            ForEach ($_POST as $sADV=>$sValue)
			            {
							If ( ( $sADV != "sOriginalLogin" ) && ( $sADV != "sAction" ) && ( $sADV != "sLogin" ) && ( $sADV != "sPassword" ) && ( strpos($sADV, "_V") === false ) && ( strpos($sADV, "TEXTAREA_") === false ) ) {
								If ( ( Trim(Request("TEXTAREA_" . $sADV)) == "T" ) || ( strLen(Trim($sValue)) >= 250 ) ) {
									DB_Update ("UPDATE AccountData SET TextData = '" . SQLEncode($sValue) . "' WHERE MapName = '" . SQLEncode($sADV) . "' AND AccountUnq = " . $sAccountUnq);
								}Else{
									DB_Update ("UPDATE AccountData SET VarCharData = '" . SQLEncode($sValue) . "' WHERE MapName = '" . SQLEncode($sADV) . "' AND AccountUnq = " . $sAccountUnq);
								}
							}
			            }
						
						// Enter the new user into the accounts specified by the admin (Initial Rights)
						$sQuery			= "SELECT * FROM InitialRights (NOLOCK)";
						$rsRecordSet	= DB_Query($sQuery);
						while ( $rsRow = DB_Fetch($rsRecordSet) )
							DB_Insert ("INSERT INTO Rights VALUES (" . $sAccountUnq . ", 0, 0, 1, '" . SQLEncode($rsRow["RightsLvl"]) . "', GetDate(), NULL)");

						SendAdminEmail();
						If ( ( $bMustAuthenticate == "YES" ) && ( $bHasRemoteHost ) ) {
							Echo "<BR>";
							DOMAIN_Message("Thank you for registering, " . $sLogin . ". An email has been dispatched to " . $sUserEmail . " with details on how to activate your account.<br><br>You will receive an email in your inbox. You MUST follow the link in that email before you can perform any functions on this website. Until you do that, you will be told that you do not have permission to login.", "SUCCESS");
							Echo "<BR>";
							SendAuthentication();
						}Else{
							SendUserEmail();
							?>
							<script language='JavaScript1.2' type='text/javascript'>
							
								document.location = "<?=$sSiteURL?>/UserArea/Login.php?sA=T&sReturnPage=/index.php&sLogin=<?=$sLogin?>&sPassword=<?=$sPassword?>";
							
							</script>
							<?php
							ob_flush();
							exit;
						}						

					}Else{
						DOMAIN_Message("There was an error adding this account.", "ERROR");
					}
					
				}Else{
					DOMAIN_Message($sError, "ERROR");
					GetCustomADVs();
					WriteADVForm("ADD");
				}
			}
		}Else{
			// The administrator must create the account in the Manage Accounts screen
			DOMAIN_Message("Please contact the administrator to sign up for an account.", "ERROR");
		}
	}
	//************************************************************************************
	


	//************************************************************************************
	//*																					*
	//*	This checks to see if the login they entered already exists or not.				*
	//*																					*
	//************************************************************************************
	Function LoginExists($sLogin)
	{		
		$sQuery			= "SELECT * FROM Accounts (NOLOCK) WHERE Login = '" . SQLEncode($sLogin) . "'";
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
			Return True;

		Return False;
	}
	//************************************************************************************
	

	//************************************************************************************
	//*																					*
	//*	This sends an email to the admin whenever a user creates a new account.			*
	//*																					*
	//************************************************************************************
	Function SendAdminEmail()
	{
		Global $sPassword;
		Global $sLogin;
		Global $bHasRemoteHost;
		Global $CONF_NewAccount;
		
		If ( DOMAIN_Conf("SEND_ADMIN_REG_EMAIL") == "YES" ) {
			If ( $bHasRemoteHost ) {
				$sFullLetter = str_replace("2:", $sPassword, str_replace("1:", $sLogin, $CONF_NewAccount));
				DOMAIN_Send_EMail ($sFullLetter, DOMAIN_Conf("SEND_ADMIN_REG_FROMNAME") . "--" . DOMAIN_Domain_Name("1"), DOMAIN_Conf("SEND_ADMIN_REG_FROMEMAIL"), DOMAIN_Conf("SEND_ADMIN_REG_DESTNAME"), DOMAIN_Conf("SEND_ADMIN_REG_EMAIL_DEST"), "New Account", FALSE);
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This sends an email to the user whenever they create a new account.				*
	//*																					*
	//************************************************************************************
	Function SendUserEmail()
	{
		Global $sUserEmail;
		Global $sPassword;
		Global $sLogin;
		Global $bHasRemoteHost;
		Global $CONF_NewUser;
		
		If ( DOMAIN_Conf("SEND_USER_REG_EMAIL") == "YES" ) {
			If ( $bHasRemoteHost ) {
				$sFullLetter = str_replace("2:", $sPassword, str_replace("1:", $sLogin, $CONF_NewUser));
				DOMAIN_Send_EMail ($sFullLetter, DOMAIN_Conf("SEND_USER_REG_EMAIL_NAME"), DOMAIN_Conf("SEND_USER_REG_EMAIL_SOURCE"), $sLogin, $sUserEmail, "Your New Account", FALSE);
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This sends an authentication email to the user.									*
	//*																					*
	//************************************************************************************
	Function SendAuthentication()
	{
		Global $sUserEmail;
		Global $sPassword;
		Global $sLogin;
		Global $iAuthID;
		Global $CONF_NewUserAuth;
		Global $sSiteURL;
		
		$sFullLetter = str_replace("1:", $sLogin, $CONF_NewUserAuth);
		$sFullLetter = str_replace("2:", $sSiteURL, $sFullLetter);
		$sFullLetter = str_replace("3:", $iAuthID, $sFullLetter);

		$sTempSubject = str_replace("1:", $_SERVER["SERVER_NAME"], "Account Authentication - 1:");
		
		DOMAIN_Send_EMail ($sFullLetter, DOMAIN_Conf("SEND_USER_REG_EMAIL_NAME"), DOMAIN_Conf("SEND_USER_REG_EMAIL_SOURCE"), $sLogin, $sUserEmail, $sTempSubject, FALSE);

	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This gets the custom ADV info and populates the aADVArray.						*
	//*																					*
	//************************************************************************************
	Function GetCustomADVs()
	{
		Global $iUBoundADVArray;
		Global $aADVArray;
		Global $bHasADVRights;
		Global $iLoginAccountUnq;
		
		// get custom ADVs - first get all visible adv's that don't begin with "PHPJK_" (only internal ones should begin w/ that)
		$sQuery			= "SELECT MapName, DataType, Description, Required FROM AccountMap M (NOLOCK) WHERE MapName NOT LIKE 'PHPJK_%' AND Visible = 'Y'";
		$rsRecordSet	= DB_Query($sQuery);
		while ( $rsRow = DB_Fetch($rsRecordSet) ) {
			// stick all the ADV info into the array - use ACCNT_ReturnADV to get the data because it needs to check rights, etc.
			$sViewLvl = "OVERRIDE";
			$aADVArray[0][$iUBoundADVArray] = $rsRow["MapName"];
			$aADVArray[1][$iUBoundADVArray] = $rsRow["DataType"];
			$aADVArray[2][$iUBoundADVArray] = $rsRow["Description"];
			$aADVArray[3][$iUBoundADVArray] = $rsRow["Required"];
			$aADVArray[4][$iUBoundADVArray] = ACCNT_ReturnADV($aADVArray[0][$iUBoundADVArray], $aADVArray[1][$iUBoundADVArray], $iLoginAccountUnq, 0, $sViewLvl);
			$aADVArray[5][$iUBoundADVArray] = $sViewLvl;
			$aADVArray[6][$iUBoundADVArray] = $bHasADVRights;	// if this is TRUE, display the form field, otherwise they don't have rights to this ADV
			If ( $aADVArray[5][$iUBoundADVArray] == "OVERRIDE")
				$aADVArray[5][$iUBoundADVArray] = "PUBLIC";

			$iUBoundADVArray++;
		}
	}
	//************************************************************************************
?>
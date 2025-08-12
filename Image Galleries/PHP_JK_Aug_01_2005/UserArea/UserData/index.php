<?php
	Require("../../Includes/i_Includes.php");
	Require("i_SignUp.php");
	Require("i_ADV.php");
	Require("../../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	
	$iUBoundADVArray	= 0;
	$aADVArray			= "";
	$bHasADVRights		= "";
	$sLogin			= "";
	$sPassword		= "";
	$sOriginalLogin	= "";
	
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

	If ( $bHasAccount ) {
		Main();
	}Else{
		DOMAIN_Message("You must be logged in before editing your account.", "ERROR");
	}
	Require("../../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iLoginAccountUnq;
		Global $sLogin;
		Global $sPassword;
		Global $sOriginalLogin;
		
		Global $PHPJK_FirstName;
		Global $PHPJK_FirstName_V;
		Global $PHPJK_MiddleName;
		Global $PHPJK_MiddleName_V;
		Global $PHPJK_LastName;
		Global $PHPJK_LastName_V;
		Global $PHPJK_BirthDay;
		Global $PHPJK_BirthDay_V;
		Global $PHPJK_BirthMonth;
		Global $PHPJK_BirthMonth_V;
		Global $PHPJK_BirthYear;
		Global $PHPJK_BirthYear_V;
		Global $PHPJK_HomeAddress1;
		Global $PHPJK_HomeAddress1_V;
		Global $PHPJK_HomeAddress2;
		Global $PHPJK_HomeAddress2_V;
		Global $PHPJK_HomeAddress3;
		Global $PHPJK_HomeAddress3_V;
		Global $PHPJK_HomeCity;
		Global $PHPJK_HomeCity_V;
		Global $PHPJK_HomeState;
		Global $PHPJK_HomeState_V;
		Global $PHPJK_HomeZip;
		Global $PHPJK_HomeZip_V;
		Global $PHPJK_HomePhone1;
		Global $PHPJK_HomePhone1_V;
		Global $PHPJK_HomePhone2;
		Global $PHPJK_HomePhone2_V;
		Global $PHPJK_HomeFax;
		Global $PHPJK_HomeFax_V;
		Global $PHPJK_MobilePhone;
		Global $PHPJK_MobilePhone_V;
		Global $PHPJK_WorkAddress1;
		Global $PHPJK_WorkAddress1_V;
		Global $PHPJK_WorkAddress2;
		Global $PHPJK_WorkAddress2_V;
		Global $PHPJK_WorkAddress3;
		Global $PHPJK_WorkAddress3_V;
		Global $PHPJK_WorkCity;
		Global $PHPJK_WorkCity_V;
		Global $PHPJK_WorkState;
		Global $PHPJK_WorkState_V;
		Global $PHPJK_WorkZip;
		Global $PHPJK_WorkZip_V;
		Global $PHPJK_WorkPhone1;
		Global $PHPJK_WorkPhone1_V;
		Global $PHPJK_WorkPhone2;
		Global $PHPJK_WorkPhone2_V;
		Global $PHPJK_WorkFax;
		Global $PHPJK_WorkFax_V;
		Global $PHPJK_EmailAddress;
		Global $PHPJK_EmailAddress_V;
		Global $PHPJK_HomepageURL;
		Global $PHPJK_HomepageURL_V;
		Global $PHPJK_ICQ;
		Global $PHPJK_ICQ_V;
		Global $PHPJK_AIM;
		Global $PHPJK_AIM_V;
		Global $PHPJK_Yahoo;
		Global $PHPJK_Yahoo_V;
		Global $PHPJK_MSN;
		Global $PHPJK_MSN_V;
		
		$sAction	= Trim(Request("sAction"));
		$sError		= "";
		
		If ( $sAction == "" ) {
			// Get all the data from the DB for this user - MUST alternate calls to the function and assignment of $sViewLvl because the function
			//	populates $sViewLvl with each ADVs PUBLIC or PRIVATE setting
			$PHPJK_FirstName		= ACCNT_ReturnADV("PHPJK_FirstName", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_FirstName_V		= $sViewLvl;
			$PHPJK_MiddleName		= ACCNT_ReturnADV("PHPJK_MiddleName", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_MiddleName_V		= $sViewLvl;
			$PHPJK_LastName			= ACCNT_ReturnADV("PHPJK_LastName", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_LastName_V		= $sViewLvl;
			$PHPJK_BirthDay			= ACCNT_ReturnADV("PHPJK_BirthDay", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_BirthDay_V		= $sViewLvl;
			$PHPJK_BirthMonth		= ACCNT_ReturnADV("PHPJK_BirthMonth", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_BirthMonth_V		= $sViewLvl;
			$PHPJK_BirthYear		= ACCNT_ReturnADV("PHPJK_BirthYear", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_BirthYear_V		= $sViewLvl;
			$PHPJK_HomeAddress1		= ACCNT_ReturnADV("PHPJK_HomeAddress1", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_HomeAddress1_V	= $sViewLvl;
			$PHPJK_HomeAddress2		= ACCNT_ReturnADV("PHPJK_HomeAddress2", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_HomeAddress2_V	= $sViewLvl;
			$PHPJK_HomeAddress3		= ACCNT_ReturnADV("PHPJK_HomeAddress3", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_HomeAddress3_V	= $sViewLvl;
			$PHPJK_HomeCity			= ACCNT_ReturnADV("PHPJK_HomeCity", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_HomeCity_V		= $sViewLvl;
			$PHPJK_HomeState		= ACCNT_ReturnADV("PHPJK_HomeState", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_HomeState_V		= $sViewLvl;
			$PHPJK_HomeZip			= ACCNT_ReturnADV("PHPJK_HomeZip", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_HomeZip_V		= $sViewLvl;
			$PHPJK_HomePhone1		= ACCNT_ReturnADV("PHPJK_HomePhone1", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_HomePhone1_V		= $sViewLvl;
			$PHPJK_HomePhone2		= ACCNT_ReturnADV("PHPJK_HomePhone2", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_HomePhone2_V		= $sViewLvl;
			$PHPJK_HomeFax			= ACCNT_ReturnADV("PHPJK_HomeFax", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_HomeFax_V		= $sViewLvl;
			$PHPJK_MobilePhone		= ACCNT_ReturnADV("PHPJK_MobilePhone", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_MobilePhone_V	= $sViewLvl;
			$PHPJK_WorkAddress1		= ACCNT_ReturnADV("PHPJK_WorkAddress1", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_WorkAddress1_V	= $sViewLvl;
			$PHPJK_WorkAddress2		= ACCNT_ReturnADV("PHPJK_WorkAddress2", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_WorkAddress2_V	= $sViewLvl;
			$PHPJK_WorkAddress3		= ACCNT_ReturnADV("PHPJK_WorkAddress3", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_WorkAddress3_V	= $sViewLvl;
			$PHPJK_WorkCity			= ACCNT_ReturnADV("PHPJK_WorkCity", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_WorkCity_V		= $sViewLvl;
			$PHPJK_WorkState		= ACCNT_ReturnADV("PHPJK_WorkState", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_WorkState_V		= $sViewLvl;
			$PHPJK_WorkZip			= ACCNT_ReturnADV("PHPJK_WorkZip", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_WorkZip_V		= $sViewLvl;
			$PHPJK_WorkPhone1		= ACCNT_ReturnADV("PHPJK_WorkPhone1", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_WorkPhone1_V		= $sViewLvl;
			$PHPJK_WorkPhone2		= ACCNT_ReturnADV("PHPJK_WorkPhone2", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_WorkPhone2_V		= $sViewLvl;
			$PHPJK_WorkFax			= ACCNT_ReturnADV("PHPJK_WorkFax", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_WorkFax_V		= $sViewLvl;
			$PHPJK_EmailAddress		= ACCNT_ReturnADV("PHPJK_EmailAddress", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_EmailAddress_V	= $sViewLvl;
			$PHPJK_HomepageURL		= ACCNT_ReturnADV("PHPJK_HomepageURL", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_HomepageURL_V	= $sViewLvl;
			$PHPJK_ICQ				= ACCNT_ReturnADV("PHPJK_ICQ", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_ICQ_V			= $sViewLvl;
			$PHPJK_AIM				= ACCNT_ReturnADV("PHPJK_AIM", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_AIM_V			= $sViewLvl;
			$PHPJK_Yahoo			= ACCNT_ReturnADV("PHPJK_Yahoo", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_Yahoo_V			= $sViewLvl;
			$PHPJK_MSN				= ACCNT_ReturnADV("PHPJK_MSN", "V", $iLoginAccountUnq, "0", $sViewLvl);
			$PHPJK_MSN_V			= $sViewLvl;
			
			// get the login/pw from the Accounts table
			$sQuery			= "SELECT Login, Password FROM Accounts (NOLOCK) WHERE AccountUnq = " . $iLoginAccountUnq;
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) ){
				$sLogin		= $rsRow["Login"];
				$sPassword	= $rsRow["Password"];
			}
			
		}ElseIf ( $sAction == "EditAccount" ) {
			$sLogin			= Trim(Request("sLogin"));
			$sPassword		= Trim(Request("sPassword"));
			$sOriginalLogin	= Trim(Request("sOriginalLogin"));
			
			If ( $sLogin == "" ) {
				$sError = "Please enter your login.<br>";
			}Else{
				If ( $sOriginalLogin != $sLogin ) {
					// Since the login has been change, we have to make sure it's not going to overwrite a previously existing one
					If ( LoginExists($sLogin) )
						$sError = "That login already exists. If you are trying to reinstate your account, <br>please email customer support. Or, try another login.<br>";
				}
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
				// Populate the ADV's
				// Update already existing ADV's and create new ones
	           ForEach ($_POST as $sADV=>$sValue)
	           {
	            	If ( ( strpos($sADV, "_V") > 0 ) && ( strpos($sADV, "OLD_") <= 0 ) ) {
	            		$sTempHomeDomain	= "";
	            		$sTempADV			= str_replace("_V", "", $sADV);
	            		// This is a public/private checkbox form field
						If ( Request($sADV) == "PUBLIC") {
							$sVisibility = "PUBLIC";
						}Else{
							$sVisibility = "PRIVATE";
						}
						If ( Request("OLD_" . $sADV) == "PUBLIC") {
							$sOldVisibility = "PUBLIC";
						}Else{
							$sOldVisibility = "PRIVATE";
						}

						$sOldValue	= Trim(Request("OLD_" . $sTempADV));
						$sValue		= Trim(Request($sTempADV));
						
						If ( ( Trim(Request("TEXTAREA_" . $sTempADV)) == "T" ) || ( strLen($sValue) >= 250 ) ) {
							ACCNT_WriteADV($sTempADV, $sValue, "T", $iLoginAccountUnq, 1, $sVisibility);
						}Else{
							ACCNT_WriteADV($sTempADV, $sValue, "V", $iLoginAccountUnq, 1, $sVisibility);
						}

						If ( $sOldVisibility != $sVisibility ) {
							// the ADV might have been inserted, but we'll update anyhow ("U") since it's faster
							DB_Update ("UPDATE AccountData SET ViewLvl = '" . SQLEncode($sVisibility) . "' WHERE MapName = '" . SQLEncode($sTempADV) . "' AND AccountUnq = " . $iLoginAccountUnq);
						}
					}
	            }
	            
	            // Now update the login/password the old fashion way (in Accounts table)
	            DB_Update ("UPDATE Accounts SET Login = '" . SQLEncode($sLogin) . "', Password = '" . SQLEncode($sPassword) . "' WHERE AccountUnq = " . $iLoginAccountUnq);
				DOMAIN_Message("Changes have been saved!", "SUCCESS");
			}Else{
				DOMAIN_Message($sError, "ERROR");
			}
		}
		
		GetCustomADVs();
		WriteADVForm("EDIT");
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
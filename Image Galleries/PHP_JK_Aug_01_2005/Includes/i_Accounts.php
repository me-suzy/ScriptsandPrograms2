<?php
	//************************************************************************************
	//*																					*
	//*	Sets the global var telling if the person is logged in or not.					*
	//*																					*
	//************************************************************************************
	Function INIT_LoginDetect()
	{
		Global $iLoginAccountUnq;
		Global $bHasAccount;
		Global $NONMEMBER_ID;
		
		If ( ! isset($_COOKIE["GAL1"]) ) {
			$bHasAccount			= False;
			$iLoginAccountUnq		= $NONMEMBER_ID;
		}Else{
			$sLogin			= SQLEncode($_COOKIE["GAL1"]);
			$sPW			= $_COOKIE["GAP1"];
			$sQuery			= "SELECT AccountUnq, Password FROM Accounts (NOLOCK) WHERE Login = '" . $sLogin . "' AND (AddDate > RemoveDate OR RemoveDate IS NULL)";
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) ) {
				If ( $sPW == md5($rsRow["Password"]) ) {
					$bHasAccount		= True;
					$iLoginAccountUnq	= Trim($rsRow["AccountUnq"]);
				}Else{
					$bHasAccount		= False;
					$iLoginAccountUnq	= $NONMEMBER_ID;
				}
			}Else{
				$bHasAccount		= False;
				$iLoginAccountUnq	= $NONMEMBER_ID;
				// if the login/pw combo for the cookies you have isn't in the database, this clears them out so you don't keep trying to
				//	login using them.
				setcookie("GAL1", "", mktime(12,0,0,1, 1, 1990), "/", $_SERVER["SERVER_NAME"], 0);
				setcookie("GAP1", "", mktime(12,0,0,1, 1, 1990), "/", $_SERVER["SERVER_NAME"], 0);
				setcookie("GAA1", "", mktime(12,0,0,1, 1, 1990), "/", $_SERVER["SERVER_NAME"], 0);
				setcookie("GAAuto1", "", mktime(12,0,0,1, 1, 1990), "/", $_SERVER["SERVER_NAME"], 0);
			}
		}
		Return True;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Checks to see if the user has the Rights Level.									*
	//*																					*
	//************************************************************************************
	Function ACCNT_ReturnRights($iRightsLvl)
	{
		Global $bHasAccount;
		Global $iLoginAccountUnq;
		
		If ( $bHasAccount ) {
			$sQuery			= "SELECT AccountUnq FROM Rights (NOLOCK) WHERE RightsLvl = '" . $iRightsLvl . "' AND AccountUnq = " . $iLoginAccountUnq . " AND (RevokeDate IS NULL OR RevokeDate < GrantDate)";
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
				Return True;
		}
		Return False;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This returns the login name of the user whose AccountUnq is passed in.			*
	//*																					*
	//************************************************************************************
	Function ACCNT_UserName($iAccountUnq)
	{
		If ( $iAccountUnq != "" ) {
			$sQuery			= "SELECT Login FROM Accounts WHERE AccountUnq = " . $iAccountUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) ) {
				Return $rsRow['Login'];
			}
		}
		
		Return "";		
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns an ADV.																	*
	//*																					*
	//************************************************************************************
	Function ACCNT_ReturnADV($sADV, $sType, $iAccountUnq, $iSystemUnq, &$sViewLvl)
	{
		Global $bHasADVRights;
		Global $iLoginAccountUnq;
		
		$bSecure	= False;
		$sType		= Trim($sType);

		$sQuery			= "SELECT RightsLvl FROM AccountMap (NOLOCK) WHERE MapName = '" . SQLEncode($sADV) . "'";
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) ) {
			// does this user have rights to view this ADV data?
			If ( ( ACCNT_ReturnRights($rsRow["RightsLvl"]) == True ) || ( Trim($rsRow["RightsLvl"]) == "" ) ) {
				$bHasADVRights = True;
				If ( $iAccountUnq == "" ) {
					// this is a nonmember, or someone not logged in (admins can make ADVs for nonmembers that'll apply to all nonmembers)
					$sQuery = "SELECT * FROM AccountData (NOLOCK) WHERE MapName = '" . SQLEncode($sADV) . "' AND AccountUnq = -1";
				}Else{
					$sQuery = "SELECT * FROM AccountData (NOLOCK) WHERE MapName = '" . SQLEncode($sADV) . "' AND AccountUnq = " . $iAccountUnq;
				}
				$rsRecordSet = DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) ) {
					$sViewLvl	= $rsRow["ViewLvl"];	// this is populated and can be used in the calling sub or function.
					If ( $sViewLvl == "OVERRIDE" ) {	// the developer wants to return the data even if the accountunq's are different AND the user made the data private - this is great for sending msgboard email notifications
						$bSecure = True;
					}Else{
						If ( Trim($sViewLvl) == "PRIVATE" ) {
							If ( $iAccountUnq == $iLoginAccountUnq ) {
								$bSecure = True;
							}
						}Else{
							$bSecure = True;
						}
					}
					If ( $bSecure == True ) {
						If ( $sType == "T" ) {
							Return $rsRow["TextData"];
						}ElseIf ( $sType == "V" ) {
							Return $rsRow["VarCharData"];
						}
						Return "";
					}
				}
			}Else{
				$bHasADVRights = False;
				Return "";
			}
		}Else{
			Return False;
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Writes a value to an ADV. If the ADV doesn't exist, insert it.					*
	//*																					*
	//************************************************************************************
	Function ACCNT_WriteADV($sADV, $sValue, $sType, $iAccountUnq, $sViewLvl)
	{
		$sQuery			= "SELECT * FROM AccountData (NOLOCK) WHERE AccountUnq = " . $iAccountUnq . " AND MapName = '" . SQLEncode($sADV) . "'";
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) ) {
			$sVarCharData	= Trim($rsRow["VarCharData"]);
			$sTextData		= Trim($rsRow["TextData"]);
			// It exists so UPDATE instead
			If ( $sType == "V" ) {
				If ( $sVarCharData == Trim($sValue) ) {
					// if the data is the same as what's already there, don't update the chage date.
					DB_Query("UPDATE AccountData SET VarCharData = '" . SQLEncode($sValue) . "' WHERE MapName = '" . SQLEncode($sADV) . "' AND AccountUnq = " . $iAccountUnq);
				}Else{
					DB_Query("UPDATE AccountData SET VarCharData = '" . SQLEncode($sValue) . "', LastChange = GetDate() WHERE MapName = '" . SQLEncode($sADV) . "' AND AccountUnq = " . $iAccountUnq);
				}
			}Else{
		
				If ( $sTextData == Trim($sValue) ) {
					// if the data is the same as what's already there, don't update the chage date.
					DB_Query("UPDATE AccountData SET TextData = '" . SQLEncode($sValue) . "' WHERE MapName = '" . SQLEncode($sADV) . "' AND AccountUnq = " . $iAccountUnq);
				}Else{
					DB_Query("UPDATE AccountData SET TextData = '" . SQLEncode($sValue) . "', LastChange = GetDate() WHERE MapName = '" . SQLEncode($sADV) . "' AND AccountUnq = " . $iAccountUnq);
				}
			}
		}Else{
			If ( ( $sViewLvl != "PUBLIC" ) && ( $sViewLvl != "PRIVATE" ) ) {
				// if the ViewLvl passed in is not either public or private, set it to private as a default
				$sViewLvl = "PRIVATE";
			}
			If ( $sType == "V" ) {
				DB_Query("INSERT INTO AccountData VALUES ('" . SQLEncode($sADV) . "'," . $iAccountUnq . ",'" . SQLEncode($sValue) . "','','" . $sViewLvl . "', 1, 'Y', GetDate())");
			}Else{
				DB_Query("INSERT INTO AccountData VALUES ('" . SQLEncode($sADV) . "'," . $iAccountUnq . ",'','" . SQLEncode($sValue) . "','" . $sViewLvl . "', 1, 'Y', GetDate())");
			}
		}
		Return True;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns PUBLIC if the ADV passed it has been set as					 			*
	//*		publically visible for the user currently logged in.						*
	//*	Returns PRIVATE otherwise.														*
	//*																					*
	//************************************************************************************
	Function ACCNT_ReturnVisibility($sADV, $iAccountUnq, $iSystemUnq)
	{
		ACCNT_ReturnADV($sADV, "V", $iAccountUnq, $iSystemUnq, $sViewLvl);
		If ( $sViewLvl == "" ) {
			Return "PRIVATE";
		}
		Return $sViewLvl;

	}
	//************************************************************************************
?>
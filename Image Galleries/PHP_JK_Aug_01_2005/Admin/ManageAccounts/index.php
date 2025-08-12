<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_DEFAULT.php");
	
	$sAccountUnq		= "";
	$sSortBy			= "";
	$sSearchIDNum		= "";
	$sSearchEmail		= "";
	$sSearchLogin		= "";
	$sIDNumQualifier	= "";
	$sEmailQualifier	= "";
	$sLoginQualifier	= "";
	$sBeginSearch		= "";
	$sAllowWildcards	= "";
	$sRSearch			= "";
	$sLSearch			= "";
	$sJoin				= "";
	$sSort				= "";
	$bViewEmail			= 0;
	
	If ((ACCNT_ReturnRights("PHPJK_MA_REVOKE")) || (ACCNT_ReturnRights("PHPJK_MA_UPDATE")) || (ACCNT_ReturnRights("PHPJK_MA_VIEW_PW")) || (ACCNT_ReturnRights("PHPJK_MA_REINSTATE")) || (ACCNT_ReturnRights("PHPJK_UD_VIEW")) || (ACCNT_ReturnRights("PHPJK_UD_EDIT")) ) {
		HeaderHTML();
		Main();
	}Else{
		DOMAIN_Message("You must login with Account rights.", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iNumPerPage;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iLoginAccountUnq;
		Global $sAccountUnq;
		Global $sSortBy;
		Global $sSearchIDNum;
		Global $sSearchEmail;
		Global $sSearchLogin;
		Global $sIDNumQualifier;
		Global $sEmailQualifier;
		Global $sLoginQualifier;
		Global $sBeginSearch;
		Global $sAllowWildcards;
		Global $sRSearch;
		Global $sLSearch;
		Global $sJoin;
		Global $sSort;
		Global $bViewEmail;
		
		$sAction			= "";
		$sError				= "";
		$sSuccess			= "";
		$bViewEmail			= ACCNT_ReturnRights("PHPJK_UD_VIEW") & ACCNT_ReturnRights("PHPJK_UD_EDIT");
	
		$sAccountUnq		= Trim(Request("sAccountUnq"));
		$sAction			= Trim(Request("sAction"));
		$sSortBy			= Trim(Request("sSortBy"));
		$sSearchIDNum		= DOMAIN_FixD(Trim(Request("sSearchIDNum")), -1);
		$sSearchEmail		= Trim(Request("sSearchEmail"));
		$sSearchLogin		= Trim(Request("sSearchLogin"));
		$sIDNumQualifier	= Trim(Request("sIDNumQualifier"));
		$sEmailQualifier	= Trim(Request("sEmailQualifier"));
		$sLoginQualifier	= Trim(Request("sLoginQualifier"));
		$sBeginSearch		= Trim(Request("sBeginSearch"));
		$sAllowWildcards	= Trim(Request("sAllowWildcards"));
		
		If ( $sSearchIDNum > -1 ) {
			If ( $sIDNumQualifier == "EQ" ) {
				$sLSearch = " AND A.AccountUnq = " . $sSearchIDNum;
			}ElseIf ( $sIDNumQualifier == "LT" ) {
				$sLSearch = " AND A.AccountUnq < " . $sSearchIDNum;
			}ElseIf ( $sIDNumQualifier == "GT" ) {
				$sLSearch = " AND A.AccountUnq > " . $sSearchIDNum;
			}
		}Else{
			$sSearchIDNum = "";	// this is so it's displayed properly in the form (not at -1 when 1st go to this page)
		}
		If ( $sSearchEmail != "" ) {
			If ( $sAllowWildcards != "Y" ) {
				// escape MSSQL wildcard characters
				$sSearchEmail = str_replace("[", "[[]", $sSearchEmail);	// this must be done first
				$sSearchEmail = str_replace("%", "[%]", $sSearchEmail);
				$sSearchEmail = str_replace("_", "[_]", $sSearchEmail);
				$sSearchEmail = str_replace("^", "[^]", $sSearchEmail);
			}
			If ( $sEmailQualifier == "IN" ) {		// Contains
				$sRSearch = " AND D.VarCharData LIKE '%" . SQLEncode($sSearchEmail) . "%'";
			}ElseIf ( $sEmailQualifier == "SW" ) {	// Starts With
				$sRSearch = " AND D.VarCharData LIKE '" . SQLEncode($sSearchEmail) . "%'";
			}ElseIf ( $sEmailQualifier == "EW" ) {	// Ends With
				$sRSearch = " AND D.VarCharData LIKE '%" . SQLEncode($sSearchEmail) . "'";
			}
		}Else{
			 //sRSearch = " OR D.VarCharData IS NULL"	// all other times we want to include ALL accounts - even ones w/o email addresses
		}
		If ( $sSearchLogin != "" ) {
			If ( $sAllowWildcards != "Y" ) {
				// escape MSSQL wildcard characters
				$sSearchLogin = str_replace("[", "[[]", $sSearchLogin);	// this must be done first
				$sSearchLogin = str_replace("%", "[%]", $sSearchLogin);
				$sSearchLogin = str_replace("_", "[_]", $sSearchLogin);
				$sSearchLogin = str_replace("^", "[^]", $sSearchLogin);
			}
			If ( $sLoginQualifier == "IN" ) {			// Contains
				$sLSearch = $sLSearch . " AND A.Login LIKE '%" . SQLEncode($sSearchLogin) . "%'";
			}ElseIf ( $sLoginQualifier == "SW" ) {	// Starts With
				$sLSearch = $sLSearch . " AND A.Login LIKE '" . SQLEncode($sSearchLogin) . "%'";
			}ElseIf ( $sLoginQualifier == "EW" ) {	// Ends With
				$sLSearch = $sLSearch . " AND A.Login LIKE '%" . SQLEncode($sSearchLogin) . "'";
			}
		}

		If ( $sSearchEmail != "" ) {
			$sJoin = "JOIN";	// doing a search on EMAIL, so we must not look at emails that are NULL
		}Else{
			$sJoin = "LEFT OUTER JOIN";		// doing a search on ID or LOGIN, or no search at all. We must look at accounts w/ NULL emails
		}

		// Pagination variables -- begin
		$iDBLoc			= 0;
		$iTtlNumItems	= 0;
		$iNumPerPage	= 20;
		If ( $sBeginSearch == "" ) {	// we aren't searching or starting a new search so we can use the existing numbers
			If ( isset($_REQUEST["iTtlNumItems"]) )
				$iTtlNumItems = Trim($_REQUEST["iTtlNumItems"]);
			If ( isset($_REQUEST["iDBLoc"]) )
				$iDBLoc = Trim($_REQUEST["iDBLoc"]);
			If ($iDBLoc < 0)
				$iDBLoc = 0;	
		}
			
		// Get ttl num of items from the database if it's not already in the QueryString
		if ( $iTtlNumItems == 0 ) {
			$sQuery			= "SELECT COUNT(DISTINCT A.AccountUnq) FROM Rights R (NOLOCK) INNER JOIN Accounts A (NOLOCK) ON (R.AccountUnq = " . $iLoginAccountUnq . ") AND R.RightsLvl IN ('PHPJK_MA_REVOKE','PHPJK_MA_UPDATE','PHPJK_MA_REINSTATE','PHPJK_UD_VIEW','PHPJK_UD_EDIT')" . $sLSearch . " " . $sJoin . " AccountData D (NOLOCK) ON A.AccountUnq = D.AccountUnq AND D.MapName = 'PHPJK_EmailAddress' " . $sRSearch;
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) )
				$iTtlNumItems = $rsRow[0];
		}
		// Pagination variables -- end

		If ( $sAction == "InvokeAccount" ) {
            ForEach ($_POST as $sCheckBox=>$sValue)
            {
            	If ( strpos($sCheckBox, "sAccountUnq") !== false ) {
					$sAccountUnq = $sValue;
					If ( InSystem($sAccountUnq) === False ) {
						$sError = "That person has never had an account.<br>No action taken.";
					}Else{
						If (ACCNT_ReturnRights("PHPJK_MA_REINSTATE")) {
							DB_Update ("UPDATE Accounts SET RemoveDate = NULL WHERE AccountUnq = " . $sAccountUnq);
							$sSuccess = "The accounts were reinstated successfully!";
						}
					}
            	}
            }
		}ElseIf ( $sAction == "EditAccount" ) {
			$sNewPassword	= "";
			$sOldPassword	= "";
			$sNewLogin		= "";
			$sOldLogin		= "";
			ForEach ($_POST as $sTextField=>$sValue)
			{
				If (strpos($sTextField, "sOldLogin") !== false ) {
					// check the login to see if it's changed. if so, see if one like it already exists and change it if it doesn't
					$sAccountUnq		= str_replace("sOldLogin", "", $sTextField);
					$sOldLogin			= Trim(Request($sTextField));
					$sNewLogin			= Trim(Request("sNewLogin" . $sAccountUnq));
					$sOldPassword		= Trim(Request("sOldPassword" . $sAccountUnq));
					$sNewPassword		= Trim(Request("sNewPassword" . $sAccountUnq));
					$bAuthenticated		= Trim(Request("bAuthenticated" . $sAccountUnq));
					$bOldAuthenticated	= Trim(Request("bOldAuthenticated" . $sAccountUnq));
					If ( ( $sOldPassword != $sNewPassword ) || ( $sOldLogin != $sNewLogin ) || ( $bOldAuthenticated != $bAuthenticated ) ) {
						If ( ( $sNewPassword != "" ) && ( $sNewLogin != "" ) ) {
							If ( $sAccountUnq != LoginExists2($sNewLogin) ) {
								$sError = "The login you entered already exists.";
							}Else{
								If (ACCNT_ReturnRights("PHPJK_MA_UPDATE")) {
									DB_Update ("UPDATE Accounts SET Login = '" . SQLEncode($sNewLogin) . "', Password = '" . SQLEncode($sNewPassword) . "', Authenticated = '" . $bAuthenticated . "', RemoveDate = NULL WHERE AccountUnq = " . $sAccountUnq);
									If ( InSystem($sAccountUnq) !== False ) {
										$sSuccess = "The account was reinstated and the login/password has been changed.";
									}Else{
										$sSuccess = "The login/password has been changed.";
									}
								}
							}
						}Else{
							$sError = "Please make sure you've entered a login and password.";
						}
					}
				}
			}
		}ElseIf ( $sAction == "RevokeAccount" ) {
			ForEach ($_POST as $sCheckBox=>$sValue)
            {
            	If ( strpos($sCheckBox, "sAccountUnq") !== false ) {
					$sAccountUnq = $sValue;
					If ( InSystem($sAccountUnq) === False ) {
						$sError = "That person has never had an account.<br>No action taken.";
					}Else{
						If (ACCNT_ReturnRights("PHPJK_MA_REVOKE")) {
							DB_Update ("UPDATE Accounts SET RemoveDate = GetDate() WHERE AccountUnq = " . $sAccountUnq);
							$sSuccess = "The accounts have been revoked.";
						}
					}
            	}
            }
		}

		
		If ( $sSortBy == "ID" ) {
			$sSort = " ORDER BY A.AccountUnq";
		}ElseIf ( $sSortBy == "Login" ) {
			$sSort = " ORDER BY A.Login";
		}ElseIf ( $sSortBy == "RemoveDate" ) {
			$sSort = " ORDER BY A.RemoveDate DESC";
		}ElseIf ( $sSortBy == "Email" ) {
			$sSort = " ORDER BY D.VarCharData";
		}ElseIf ( $sSortBy == "Authenticated" ) {
			$sSort = " ORDER BY A.Authenticated DESC";
		}Else{
			$sSort = " ORDER BY A.AccountUnq";
		}
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		If ( $sSuccess != "" )
			DOMAIN_Message($sSuccess, "SUCCESS");
		
		
		If ( $sAllowWildcards != "Y" ) {
			// unescape MSSQL wildcard characters -- needs to be done for the WriteForm function
			$sSearchEmail = str_replace("[%]", "%", $sSearchEmail);
			$sSearchEmail = str_replace("[_]", "_", $sSearchEmail);
			$sSearchEmail = str_replace("[^]", "^", $sSearchEmail);
			$sSearchEmail = str_replace("[[]", "[", $sSearchEmail);	// this must be done last
			$sSearchLogin = str_replace("[%]", "%", $sSearchLogin);
			$sSearchLogin = str_replace("[_]", "_", $sSearchLogin);
			$sSearchLogin = str_replace("[^]", "^", $sSearchLogin);
			$sSearchLogin = str_replace("[[]", "[", $sSearchLogin);	// this must be done last
		}

		WriteForm();
		
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This checks to see if they are in the db or not. And if they are, it returns	*
	//*		the date they were taken out of that system or NULL if they are still in	*
	//*		it.																			*
	//*																					*
	//************************************************************************************
	Function InSystem($sAccountUnq)
	{
		// REMEMBER: that sometimes this will return NULL. The if/then check calling
		//	this must take that into consideration -- a NULL does not mean FALSE! Just means
		//	that the user has never been revoked before.
		$sQuery			= "SELECT RemoveDate FROM Accounts (NOLOCK) WHERE AccountUnq = " . $sAccountUnq;
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow["RemoveDate"];

		Return False;
		
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This checks to see if the login they entered already exists or not.				*
	//*																					*
	//************************************************************************************
	Function LoginExists2($sNewLogin)
	{
		Global $sAccountUnq;

		$sQuery			= "SELECT AccountUnq FROM Accounts (NOLOCK) WHERE Login = '" . SQLEncode($sNewLogin) . "'";
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow["AccountUnq"];

		Return $sAccountUnq;
		
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $iLoginAccountUnq;
		Global $iNumPerPage;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $aVariables;
		Global $aValues;
		Global $sSortBy;
		Global $sSearchIDNum;
		Global $sSearchEmail;
		Global $sSearchLogin;
		Global $sIDNumQualifier;
		Global $sEmailQualifier;
		Global $sLoginQualifier;
		Global $sBeginSearch;
		Global $sAllowWildcards;
		Global $sRSearch;
		Global $sLSearch;
		Global $sJoin;
		Global $sSort;
		Global $bViewEmail;

		$sBGColor	= $GLOBALS["BGColor2"];
		
		$sQuery			= "SELECT DISTINCT A.AccountUnq, A.Login, A.Password, A.AddDate, A.RemoveDate, A.Authenticated, D.VarCharData FROM Rights R (NOLOCK) INNER JOIN Accounts A (NOLOCK) ON (R.AccountUnq = " . $iLoginAccountUnq . ") AND R.RightsLvl IN ('PHPJK_MA_REVOKE','PHPJK_MA_UPDATE','PHPJK_MA_REINSTATE','PHPJK_UD_VIEW','PHPJK_UD_EDIT')" . $sLSearch . " " . $sJoin . " AccountData D (NOLOCK) ON A.AccountUnq = D.AccountUnq AND D.MapName = 'PHPJK_EmailAddress' " . $sRSearch . $sSort;
		$rsRecordSet	= DB_Query($sQuery);
		
		For ( $x = 1; $x < $iDBLoc; $x++ )
			DB_Fetch($rsRecordSet);
		
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageAccounts.sAction.value = sAction;
				document.ManageAccounts.submit();
			}
			
		</script>
		<form name='ManageAccounts' action='index.php' method='post'>
		<?php
		$aVariables[0] = "sAction";
		$aVariables[1] = "iTtlNumItems";
		$aVariables[2] = "iDBLoc";
		$aVariables[3] = "sBeginSearch";
		$aValues[0] = "New";
		$aValues[1] = $iTtlNumItems;
		$aValues[2] = $iDBLoc;
		$aValues[3] = "";
		Echo DOMAIN_Link("P");
		
		DOMAIN_Link_Clear();
		?>

		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Bulk Manage Accounts</b></font>
					<br>
					<font color='<?=$GLOBALS["PageText"]?>'>
					Change login's and password's as necessary. Check the checkbox on accounts you would like to either revoke or reinstate.
					<br><br>
					Listing <b><?=number_format($iTtlNumItems, 0)?></b> accounts.
					<table cellpadding=0 width=671 cellspacing = 0 border = 0>
						<tr>
							<td>
								<b>Search:</b>
							</td>
							<td align=right>
								<b>Jump to page:</b>
								<select name='iDBLoc2' onChange='document.ManageAccounts.iDBLoc.value=this.value;SubmitForm("");'>
									<option value='0'>Select Page</option>
									<?php
									For ( $x = 0; $x < ($iTtlNumItems/$iNumPerPage); $x++)
										Echo "<option value='" . ($x*$iNumPerPage) . "'>" . ($x+1) . "</option>";
									?>
								</select>
							</td>
						</tr>
					</table>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor = <?=$GLOBALS["BorderColor1"]?> align=center>
					<table cellpadding=5 width=671 cellspacing = 0 border = 0>
						<tr>
							<td bgcolor = <?=$GLOBALS["PageBGColor"]?>>
								ID# <input type='text' name='sSearchIDNum' value="<?=rawurlencode($sSearchIDNum)?>" maxlength=32 size=8>
							</td>
							<td bgcolor = <?=$GLOBALS["PageBGColor"]?>>
								<select name='sIDNumQualifier'>
									<option value='EQ' <?php If ( $sIDNumQualifier == "EQ" ) { Echo "Selected"; } ?>>Equal</option>
									<option value='LT' <?php If ( $sIDNumQualifier == "LT" ) { Echo "Selected"; } ?>>Less Than</option>
									<option value='GT' <?php If ( $sIDNumQualifier == "GT" ) { Echo "Selected"; } ?>>Greater Than</option>
								</select>
							</td>
							<td bgcolor = <?=$GLOBALS["PageBGColor"]?> rowspan=5 align=center valign=center width=200>
								Allow MSSQL wildcards ("[%_^")? <input type='checkbox' name='sAllowWildcards' value='Y' <?php If ( $sAllowWildcards == "Y" ) { Echo "Checked"; } ?>>
								<br><br><br>
								<input type='button' value='Search' onClick='document.ManageAccounts.sBeginSearch.value=1;SubmitForm("");'>
								<br><br><br>
								<input type='button' value='Clear Search' onClick='document.ManageAccounts.sBeginSearch.value=1;document.ManageAccounts.sSearchIDNum.value="";document.ManageAccounts.sSearchLogin.value="";document.ManageAccounts.sSearchEmail.value="";SubmitForm("");'>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["PageBGColor"]?> colspan=2 align=center>
								<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td width=50%><hr></td><td><font color='<?=$GLOBALS["PageText"]?>'><b>AND</b></td><td width=50%><hr></td></tr></table>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["PageBGColor"]?>>
								Email <input type='text' name='sSearchEmail' value="<?=rawurlencode($sSearchEmail)?>" maxlength=255 size=32>
							</td>
							<td bgcolor = <?=$GLOBALS["PageBGColor"]?>>
								<select name='sEmailQualifier'>
									<option value='IN' <?php If ( $sEmailQualifier == "IN" ) { Echo "Selected"; } ?>>Contains</option>
									<option value='SW' <?php If ( $sEmailQualifier == "SW" ) { Echo "Selected"; } ?>>Starts With</option>
									<option value='EW' <?php If ( $sEmailQualifier == "EW" ) { Echo "Selected"; } ?>>Ends With</option>
								</select>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["PageBGColor"]?> colspan=2 align=center>
								<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td width=50%><hr></td><td><font color='<?=$GLOBALS["PageText"]?>'><b>AND</b></td><td width=50%><hr></td></tr></table>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["PageBGColor"]?>>
								Login <input type='text' name='sSearchLogin' value="<?=rawurlencode($sSearchLogin)?>" maxlength=255 size=32>
							</td>
							<td bgcolor = <?=$GLOBALS["PageBGColor"]?>>
								<select name='sLoginQualifier'>
									<option value='IN' <?php If ( $sLoginQualifier == "IN" ) { Echo "Selected"; } ?>>Contains</option>
									<option value='SW' <?php If ( $sLoginQualifier == "SW" ) { Echo "Selected"; } ?>>Starts With</option>
									<option value='EW' <?php If ( $sLoginQualifier == "EW" ) { Echo "Selected"; } ?>>Ends With</option>
								</select>
							</td>
						</tr>
					</table>
					</td></tr></table>
					<br><br>
					
					<b>Filter:</b>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor = <?=$GLOBALS["BorderColor1"]?> align=center>
					<table cellpadding=5 width=671 cellspacing = 0 border = 0>
						<tr>
							<td bgcolor = <?=$GLOBALS["PageBGColor"]?>>
							</td>
							<td align=right bgcolor = <?=$GLOBALS["PageBGColor"]?>>
								<font color='<?=$GLOBALS["PageText"]?>'>
								Sort By:
								<select name='sSortBy' onChange='SubmitForm("UpdateSort");'>
									<option value='ID' <?php If ( $sSortBy=="ID" ) { Echo "selected"; } ?>>ID Number</option>
									<option value='Login' <?php If ( $sSortBy=="Login" ) { Echo "selected"; } ?>>Login</option>
									<option value='RemoveDate' <?php If ( $sSortBy=="RemoveDate" ) { Echo "selected"; } ?>>Revoke Date</option>
									<option value='Email' <?php If ( $sSortBy=="Email" ) { Echo "selected"; } ?>>Email Address</option>
									<option value='Authenticated' <?php If ( $sSortBy=="Authenticated" ) { Echo "selected"; } ?>>Authenticated</option>
								</select>
							</td>
						</tr>
					</table>
					</td></tr></table>
					<br><br>
					<b>Results:</b><br>
					<table cellpadding=0 cellspacing=0 border=0 width=671>
						<tr>
							<td align=right bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>ID</b></td>
							<td bgcolor=<?=$sBGColor?>><b>&nbsp;</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Login</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Password</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Creation Date</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Revoke Date</b></td>
							<td bgcolor=<?=$sBGColor?> align=center></td>
							<td bgcolor=<?=$sBGColor?> align=center><font color='<?=$GLOBALS["TextColor2"]?>'><b></b></td>
						</tr>
						<?php
						while ( $rsRow = DB_Fetch($rsRecordSet) ) {
							If ( $sBGColor == $GLOBALS["BGColor1"] ) {
								$sBGColor	= $GLOBALS["PageBGColor"];
								$sTextColor	= $GLOBALS["PageText"];
								$sLinkColor = "SmallNavPage";
							}Else{
								$sBGColor	= $GLOBALS["BGColor1"];
								$sTextColor	= $GLOBALS["TextColor1"];
								$sLinkColor = "SmallNav1";
							}
							$sLogin			= $rsRow["Login"];
							$sPassword		= $rsRow["Password"];
							$sAccountUnq	= $rsRow["AccountUnq"];
							$sEmailAddress	= Trim($rsRow["VarCharData"]);
							$bAuthenticated	= Trim($rsRow["Authenticated"]);
							?>
							<tr><td colspan=8 bgcolor=<?=$sBGColor?>><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
							<tr>
								<td align=right bgcolor=<?=$sBGColor?>><?=$sAccountUnq?></td>
								<td bgcolor=<?=$sBGColor?>><b>&nbsp;</b></td>
								<?php If ( ACCNT_ReturnRights("PHPJK_MA_UPDATE") ) {?>
								<td bgcolor=<?=$sBGColor?>><input type='hidden' name='sOldLogin<?=$sAccountUnq?>' value="<?=htmlentities($sLogin)?>"><input type='text' name='sNewLogin<?=$sAccountUnq?>' value="<?=htmlentities($sLogin)?>" size=20 maxlength=250></td>
								<td bgcolor=<?=$sBGColor?>><input type='hidden' name='sOldPassword<?=$sAccountUnq?>' value="<?=htmlentities($sPassword)?>"><input type='text' name='sNewPassword<?=$sAccountUnq?>' value="<?=htmlentities($sPassword)?>" size=20 maxlength=250></td>
								<?php }ElseIf ( ACCNT_ReturnRights("PHPJK_MA_VIEW_PW") ) { ?>
								<td bgcolor=<?=$sBGColor?>><b><?=$sLogin?></td>
								<td bgcolor=<?=$sBGColor?>><?=$sPassword?></td>
								<?php }Else{ ?>
								<td bgcolor=<?=$sBGColor?>><b><?=$sLogin?></td>
								<td bgcolor=<?=$sBGColor?>></td>
								<?php } ?>
								<td bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor?>' size=-2><?=$rsRow["AddDate"]?></font></td>
								<td bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor?>' size=-2><?=$rsRow["RemoveDate"]?></font></td>
								<td bgcolor=<?=$sBGColor?> align=center>
								</td>
								<?php If ( ( ( ACCNT_ReturnRights("PHPJK_MA_REINSTATE") ) || ( ACCNT_ReturnRights("PHPJK_MA_REVOKE") ) ) && ( $iLoginAccountUnq != $sAccountUnq ) ){ ?>
								<td bgcolor=<?=$sBGColor?> align=center><input type='checkbox' name='sAccountUnq<?=$sAccountUnq?>' value='<?=$sAccountUnq?>'></td>
								<?php }Else{ ?>
								<td bgcolor=<?=$sBGColor?>>&nbsp;</td>
								<?php } ?>
							</tr>
							<tr><td colspan=8 bgcolor=<?=$GLOBALS["BorderColor1"]?>><img src='../../Images/Blank.gif' width=1 height=1></td></tr>
							<tr>
								<td colspan=8 bgcolor=<?=$sBGColor?>>
									<table cellpadding=0 cellspacing=0 border=0 width=100%>
										<tr>
											<td width=50%>
												<font color='<?=$sTextColor?>' size=-2>
												Authenticated?
												<?php If ( $iLoginAccountUnq != $sAccountUnq ) { ?>
												<br>
												<b>
												Yes:<input type='radio' name='bAuthenticated<?=$sAccountUnq?>' value='T' <?php If ( $bAuthenticated == "T" ) Echo "checked"; ?>>
												&nbsp;&nbsp;&nbsp;
												No:<input type='radio' name='bAuthenticated<?=$sAccountUnq?>' value='' <?php If ( $bAuthenticated != "T" ) Echo "checked"; ?>>
												</b>
												<input type='hidden' name='bOldAuthenticated<?=$sAccountUnq?>' value='<?=$bAuthenticated?>'>
												<?php }Else{ ?>
												<b>Yes</b>
												<?php } ?>
											</td>
											<?php
											If ( (ACCNT_ReturnRights("PHPJK_UD_VIEW")) || (ACCNT_ReturnRights("PHPJK_UD_EDIT")) ) { ?>
											<td width=50% align=right>
												<font color='<?=$sTextColor?>' size=-2>
												<?php
												If ( $bViewEmail )
													If ( $sEmailAddress != "" )
														Echo "Email: <b>" . $sEmailAddress . "</b><br>";
												?>
												<a href='EditUserData.php?<?=DOMAIN_Link("G")?>&sSortBy=<?=$sSortBy?>&sAccountUnq=<?=$sAccountUnq?>&iDBLoc2=<?=$iDBLoc?>&sSearchIDNum=<?=$sSearchIDNum?>&sSearchEmail=<?=rawurlencode($sSearchEmail)?>&sSearchLogin=<?=rawurlencode($sSearchLogin)?>&sIDNumQualifier=<?=$sIDNumQualifier?>&sEmailQualifier=<?=$sEmailQualifier?>&sLoginQualifier=<?=$sLoginQualifier?>&sAllowWildcards=<?=$sAllowWildcards?>' class='<?=$sLinkColor?>'>&#151;Edit User Data&#151;</a>
												<?php If ( (ACCNT_ReturnRights("PHPJK_MR_MODIFY")) || (ACCNT_ReturnRights("PHPJK_MR_VIEW")) ) { ?>
												<br>
												<a href='EditRights.php?<?=DOMAIN_Link("G")?>&sSortBy=<?=$sSortBy?>&sAccountUnq=<?=$sAccountUnq?>&iDBLoc2=<?=$iDBLoc?>&sSearchIDNum=<?=$sSearchIDNum?>&sSearchEmail=<?=rawurlencode($sSearchEmail)?>&sSearchLogin=<?=rawurlencode($sSearchLogin)?>&sIDNumQualifier=<?=$sIDNumQualifier?>&sEmailQualifier=<?=$sEmailQualifier?>&sLoginQualifier=<?=$sLoginQualifier?>&sAllowWildcards=<?=$sAllowWildcards?>' class='<?=$sLinkColor?>'>&#151;Edit User Rights&#151;</a>
												<?php } ?>
											</td>
											<?php }Else{ ?>
											<td width=50%>&nbsp;</td>
											<?php } ?>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td colspan=8 bgcolor=<?=$GLOBALS["BorderColor1"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td></tr>
							<?php
						}
						?>
						<tr>
							<td colspan=8 align=right>
								<?php PrintRecordsetNav_ADMIN( "index.php", "&sSortBy=" . $sSortBy . "&sSearchIDNum=" . $sSearchIDNum . "&sSearchEmail=" . $sSearchEmail . "&sSearchLogin=" . $sSearchLogin . "&sIDNumQualifier=" . $sIDNumQualifier . "&sEmailQualifier=" . $sEmailQualifier . "&sLoginQualifier=" . $sLoginQualifier . "&sAllowWildcards=" . $sAllowWildcards, "Accounts")?>
							</td>
						</tr>
					</table>
					<br>
					<font size=-2>
					Changing your login will log you out but not make the change. 
					Changing your password will make the change and force you to log in again.
				</td>
			</tr>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		</form>
		<?php
		
	}
	//********************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This "fixes" QueryString and Form data for input into databases and to check	*
	//*		for possible hacking.														*
	//*	This is only necessary right now for numeric types.								*
	//*																					*
	//************************************************************************************
	Function DOMAIN_FixD($vData, $iNullSet)
	{
		// can't Request it here because sometimes we are uploading files and can't use Request
		// can't do SQLEncode here either because we don't always to encode (if the data is redisplayed in a form for example)
		
		If ( ! is_null($vData) )
			If ( $vData != "" )
				If ( is_numeric($vData) )
					Return $vData;

		Return $iNullSet;
	
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function HeaderHTML()
	{
		Global $aVariables;
		Global $aValues;
		
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=10 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If (ACCNT_ReturnRights("PHPJK_MA_CREATE_NEW")) {
					// have to Request the data -- can't just use the variables because they won't have been populated by the time this function is called
					Echo "<td bgcolor=FFFFFF width=1><a href='New.php?" . DOMAIN_Link("G") . "&sSortBy=" . Request("sSortBy") . "&iDBLoc2=" . Request("iDBLoc") . "&sSearchIDNum=" . Request("sSearchIDNum") . "&sSearchEmail=" . rawurlencode(Request("sSearchEmail")) . "&sSearchLogin=" . rawurlencode(Request("sSearchLogin")) . "&sIDNumQualifier=" . Request("sIDNumQualifier") . "&sEmailQualifier=" . Request("sEmailQualifier") . "&sLoginQualifier=" . Request("sLoginQualifier") . "&sAllowWildcards=" . Request("sAllowWildcards") . "'><img src='../../Images/Administrative/New_Account.gif' Width=21 Height=39 Border=0 Alt='Create a new account.'></a></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ( ACCNT_ReturnRights("PHPJK_MA_REVOKE") ) || ( ACCNT_ReturnRights("PHPJK_MA_CROSS_REVOKE") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='revoke' SRC='../../Images/Administrative/Revoke_Account.gif' ALIGN='absmiddle' Width=34 Height=39 Border=0 Alt='Revoke checked accounts.' onClick='SubmitForm(\"RevokeAccount\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ACCNT_ReturnRights("PHPJK_MA_UPDATE") ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/Update_Account.gif' ALIGN='absmiddle' Width=34 Height=39 Border=0 Alt='Update changed account logins and passwords.' onClick='SubmitForm(\"EditAccount\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ACCNT_ReturnRights("PHPJK_MA_REINSTATE") ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='reinstate' SRC='../../Images/Administrative/Reinstate_Account.gif' ALIGN='absmiddle' Width=42 Height=39 Border=0 Alt='Reinstate checked revoked accounts.' onClick='SubmitForm(\"InvokeAccount\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				?>
				<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>
				<td bgcolor=FFFFFF width=100%>&nbsp;</td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=10 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=12 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
?>
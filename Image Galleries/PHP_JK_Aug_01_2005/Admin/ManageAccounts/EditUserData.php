<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_DEFAULT.php");
	
	$sAccountUnq = "";

	If ( (ACCNT_ReturnRights("PHPJK_UD_VIEW")) || (ACCNT_ReturnRights("PHPJK_UD_EDIT")) ) {
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
		Global $sAccountUnq;
		
		$sAction		= "";
		$sError			= "";
		$sSuccess		= "";
		$sAction		= Trim(Request("sAction"));
		$sAccountUnq	= Trim(Request("sAccountUnq"));
		
		If ( $sAction == "UpdateADVs" ) {
			If ( ACCNT_ReturnRights("PHPJK_UD_EDIT") ) {
				ForEach ($_POST as $sName=>$sValue)
				{
					If ( strpos($sName, "_VISIBILITY") !== false ) {
						$sTempADV			= substr($sName, 0, strpos($sName, "_VISIBILITY"));
						$sTempValue			= Trim(Request("ADV_" . $sTempADV));
						$sTempOldValue		= Trim(Request("OldADV_" . $sTempADV));
						$sTempType			= Trim(Request("Type_" . $sTempADV));
						$sTempVisibility	= Trim($sValue);
						$sTempOldVisibility	= Trim(Request("Old_" . $sTempADV . "_V"));
							
						If ( $sTempOldValue != $sTempValue ) {
							$sQuery			= "SELECT * FROM AccountData D (NOLOCK) WHERE D.AccountUnq = " . $sAccountUnq . " AND D.MapName = '" . SQLEncode($sTempADV) . "'";
							$rsRecordSet	= DB_Query($sQuery);
							if ( $rsRow = DB_Fetch($rsRecordSet) )
							{
								// It exists so UPDATE
								If ( $sTempType == "V" ) {
									DB_Update ("UPDATE AccountData SET VarCharData = '" . SQLEncode($sTempValue) . "', LastChange=GetDate()  WHERE MapName = '" . SQLEncode($sTempADV) . "' AND AccountUnq = " . $sAccountUnq);
									DB_Update ("UPDATE AccountData SET TextData = '' WHERE MapName = '" . SQLEncode($sTempADV) . "' AND AccountUnq = " . $sAccountUnq);
								}ElseIf ( $sTempType == "T" ) {
									DB_Update ("UPDATE AccountData SET TextData = '" . SQLEncode($sTempValue) . "', LastChange=GetDate() WHERE MapName = '" . SQLEncode($sTempADV) . "' AND AccountUnq = " . $sAccountUnq);
									DB_Update ("UPDATE AccountData SET VarCharData = '' WHERE MapName = '" . SQLEncode($sTempADV) . "' AND AccountUnq = " . $sAccountUnq);
								}
							}Else{
								// It doesn't exist so INSERT
								If ( $sTempType == "V" ) {
									DB_Insert ("INSERT INTO AccountData VALUES ('" . SQLEncode($sTempADV) . "'," . $sAccountUnq . ",'" . SQLEncode($sTempValue) . "','','" . $sTempVisibility . "',1,'Y',GetDate())");
								}ElseIf ( $sTempType == "T" ) {
									DB_Insert ("INSERT INTO AccountData VALUES ('" . SQLEncode($sTempADV) . "'," . $sAccountUnq . ",'','" . SQLEncode($sTempValue) . "','" . $sTempVisibility . "',1,'Y',GetDate())");
								}
							}
							
							If ( $sSuccess == "" )
								$sSuccess = "Updated your data successfully!";
						}
						If ( $sTempOldVisibility != $sTempVisibility ) {
							$sQuery			= "SELECT * FROM AccountMap M (NOLOCK), AccountData D (NOLOCK) WHERE D.AccountUnq = " . $sAccountUnq . " AND M.MapName = D.MapName AND D.MapName = '" . SQLEncode($sTempADV) . "'";
							$rsRecordSet	= DB_Query($sQuery);
							if ( $rsRow = DB_Fetch($rsRecordSet) ){
								// It exists so UPDATE instead
								DB_Update ("UPDATE AccountData SET ViewLvl = '" . $sTempVisibility . "', LastChange=GetDate() WHERE MapName = '" . SQLEncode($sTempADV) . "' AND AccountUnq = " . $sAccountUnq);
							}Else{
								// It doesn't exist so INSERT
								If ( $sTempType == "V" ) {
									DB_Insert ("INSERT INTO AccountData VALUES ('" . SQLEncode($sTempADV) . "'," . $sAccountUnq . ",'" . SQLEncode($sTempValue) . "','','" . $sTempVisibility . "',1,'T',GetDate())");
								}ElseIf ( $sTempType == "T" ) {
									DB_Insert ("INSERT INTO AccountData VALUES ('" . SQLEncode($sTempADV) . "'," . $sAccountUnq . ",'','" . SQLEncode($sTempValue) . "','" . $sTempVisibility . "',1,'T',GetDate())");
								}
							}
	
							If ( $sSuccess = "" )
								$sSuccess = "Updated your data successfully!";
						}
					}
				}
			}Else{
				DOMAIN_Message("You must login with Account rights.", "ERROR");
			}
		}
		
		// Pagination variables -- begin
		$iDBLoc			= 0;
		$iTtlNumItems	= 0;
		$iNumPerPage	= 20;
		If ( isset($_REQUEST["iTtlNumItems"]) )
			$iTtlNumItems = Trim($_REQUEST["iTtlNumItems"]);
		If ( isset($_REQUEST["iDBLoc"]) )
			$iDBLoc = Trim($_REQUEST["iDBLoc"]);
		If ($iDBLoc < 0)
			$iDBLoc = 0;	
			
		// Get ttl num of items from the database if it's not already in the QueryString
		if ( $iTtlNumItems == 0 ) {
			$sQuery			= "SELECT Count(*) FROM AccountMap M (NOLOCK)";
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) )
				$iTtlNumItems = $rsRow[0];
		}
		// Pagination variables -- end
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		If ( $sSuccess != "" )
			DOMAIN_Message($sSuccess, "SUCCESS");

		WriteADVForm();
		
		
	}
	//************************************************************************************
	


	//************************************************************************************
	//*																					*
	//*	This displays the ADV's according to the sSQLText passed to it					*
	//*	Used for printing several sets of ADV's from several rights levels				*
	//*		when logged in as an admin.													*
	//*																					*
	//************************************************************************************
	Function DisplayCustomADVs()
	{
		Global $iNumPerPage;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $sAccountUnq;

		$sQuery			= "SELECT * FROM AccountMap (NOLOCK) ORDER BY MapName";
		$rsRecordSet	= DB_Query($sQuery);
		For ( $x = 1; $x <= $iDBLoc; $x++)
			DB_Fetch($rsRecordSet);
		while ( $rsRow = DB_Fetch($rsRecordSet) ) {
			$sMapName	= $rsRow["MapName"];
			$iRightsLvl	= Trim($rsRow["RightsLvl"]);
			
			$sQuery			= "SELECT ViewLvl, LastChange FROM AccountData (NOLOCK) WHERE AccountUnq = " . $sAccountUnq . " AND MapName = '" . SQLEncode($sMapName) . "'";
			$rsRecordSet2	= DB_Query($sQuery);

			if ( $rsRow2 = DB_Fetch($rsRecordSet2) ){
				$sViewLvl		= $rsRow2["ViewLvl"];
				$sLastChange	= $rsRow2["LastChange"];
			}Else{
				$sViewLvl		= "PRIVATE";
				$sLastChange	= "";
			}
			$sValue = "";
			
			If ( ( ! ACCNT_ReturnRights($iRightsLvl) ) && ( $iRightsLvl != "" ) ) {
				?>
				<tr>
					<td colspan=2 bgcolor='<?=$GLOBALS["BGColor1"]?>' valign=top>
						<font color='<?=$GLOBALS["TextColor1"]?>'>
						<b><?=$sMapName?></b><br>
						<?=$rsRow["Description"]?>
					</td>
				</tr>
				<tr>
					<td colspan=2 bgcolor='<?=$GLOBALS["BGColor1"]?>' valign=top>
						<font color='<?=$GLOBALS["TextColor1"]?>'>
						<b>No edit rights
					</td>
				</tr>						
				<tr><td colspan=2 bgcolor='<?=$GLOBALS["PageBGColor"]?>'><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
				<?php
			}Else{
				?>
				<tr>
					<td bgcolor='<?=$GLOBALS["BGColor1"]?>' valign=top>
						<font color='<?=$GLOBALS["TextColor1"]?>'>
						<b><?=$sMapName?></b><br>
						<?=$rsRow["Description"]?>
					</td>
					<td bgcolor='<?=$GLOBALS["BGColor1"]?>' valign=top>
						<table cellpadding=0 cellspacing=0 border=0 width=100%>
							<tr>
								<td width=75>
									<?php
									If ( ACCNT_ReturnRights("PHPJK_UD_EDIT") ) {
										If ( ( $sViewLvl == "PRIVATE" ) || ( $sViewLvl == "" ) ) {
											Echo "Hidden <input type='radio' name=\"" . htmlentities($sMapName) . "_VISIBILITY\" value='PRIVATE' checked><br>";
											Echo "Visible <input type='radio' name=\"" . htmlentities($sMapName) . "_VISIBILITY\" value='PUBLIC'>";
											Echo "<input type='hidden' name=\"Old_" . htmlentities($sMapName) . "_V\" value='PRIVATE'>";
										}Else{
											Echo "Hidden <input type='radio' name='" . htmlentities($sMapName) . "_VISIBILITY\" value='PRIVATE'><br>";
											Echo "Visible <input type='radio' name='" . htmlentities($sMapName) . "_VISIBILITY\" value='PUBLIC' checked>";
											Echo "<input type='hidden' name=\"Old_" . htmlentities($sMapName) . "_V\" value='PUBLIC'>";
										}
									}ElseIf ( ACCNT_ReturnRights("PHPJK_UD_VIEW") ) {
										If ( ( $sViewLvl == "PRIVATE" ) || ( $sViewLvl == "" ) ) {
											Echo "Hidden";
										}Else{
											Echo "<br>Visible";
										}
									}
									?>
								</td>
								<td align=right>
									<br>
									<font size=-1 color='<?=$GLOBALS["TextColor1"]?>'><?=$sLastChange?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan=2 bgcolor='<?=$GLOBALS["BGColor1"]?>' valign=top>
						<?php
						If ( Trim($rsRow["Required"]) == "Y" ) 
							Echo "*";

						// check to make sure this admin has rights to edit or view it.
						If ( ACCNT_ReturnRights("PHPJK_UD_EDIT") ) {
							// they have rights to edit it, so display it in form fields
							If ( Trim($rsRow["DataType"]) == "V" ) {
								$sQuery			= "SELECT VarCharData FROM AccountData (NOLOCK) WHERE AccountUnq = " . $sAccountUnq . " AND MapName = '" . SQLEncode($sMapName) . "'";
								$rsRecordSet2	= DB_Query($sQuery);
								if ( $rsRow2 = DB_Fetch($rsRecordSet2) ) {
									If ( is_null($rsRow2["VarCharData"]) ) {
										$sValue = "";
									}Else{
										$sValue = $rsRow2["VarCharData"];
									}
								}
								Echo "<input type='text' name=\"ADV_" . htmlentities($sMapName) . "\" value=\"" . htmlentities($sValue) . "\" size='90' maxlen='250'>";
								Echo "<input type='hidden' name=\"Type_" . htmlentities($sMapName) . "\" value='V'>";
								Echo "<input type='hidden' name=\"OldADV_" . htmlentities($sMapName) . "\" value=\"" . htmlentities($sValue) . "\">";
							}Else{
								// This next block to get the TextData independent of the other data is because of the ASP bug when reading text fields from the database.
								$sQuery			= "SELECT TextData FROM AccountData (NOLOCK) WHERE AccountUnq = " . $sAccountUnq . " AND MapName = '" . SQLEncode($sMapName) . "'";
								$rsRecordSet2	= DB_Query($sQuery);
								if ( $rsRow2 = DB_Fetch($rsRecordSet2) ) {
									If ( is_null($rsRow2["TextData"]) ) {
										$sValue = "";
									}Else{
										$sValue = $rsRow2["TextData"];
									}
								}

								Echo "<textarea name=\"ADV_" . htmlentities($sMapName) . "\" cols=75 rows=5>" . htmlentities($sValue) . "</textarea>";
								Echo "<input type='hidden' name=\"Type_" . htmlentities($sMapName) . "\" value='T'>";
								Echo "<input type='hidden' name=\"OldADV_" . htmlentities($sMapName) . "\" value=\"" . htmlentities($sValue) . "\">";
							}
						}ElseIf ( ACCNT_ReturnRights("PHPJK_UD_VIEW") ) {
							// they only have rights to view it, do display it as plain text
							If ( Trim(rsPHPJK("DataType")) == "V" ) {
								Echo $rsRow["VarCharData"];
							}Else{
								Echo $rsRow["TextData"];
							}
						}Else{
							// they have no rights to it, so display asterisks
							Echo "<center>*************** NO EDIT or VIEW RIGHTS ***************";
						}
						?>
					</td>
				</tr>
				<tr><td colspan=2 bgcolor='<?=$GLOBALS["PageBGColor"]?>'><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
				<?php
			}
		}
	}
	//***********************************************************************************


	
	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteADVForm()
	{
		Global $aVariables;
		Global $aValues;
		Global $sAccountUnq;
		?>
		<script language='JavaScript1.2' type='text/javascript'>
		
			function PaginationLink(sQueryString){
				document.location = "EditUserData.php?<?=DOMAIN_Link("G")?>&sSortBy=<?=Request("sSortBy")?>&sAccountUnq=<?=$sAccountUnq?>&iDBLoc2=<?=Request("iDBLoc2")?>" + sQueryString;
			}
	
			function SubmitForm(sAction){
				document.ADVData.sAction.value = sAction;
				document.ADVData.submit();
			}
			
		</script>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Edit ADVs</b></font>
					<br>
					For user:  <b><?=ACCNT_UserName($sAccountUnq)?></b>
					<br><br>
					ADVs that are required have an asterisk next to them. 
					They are required for the users, but not here for administrators.
					<br><br>
					Be careful when choosing what data you enter. 
					Some of the data should have a specific format. 
					For example, the PHPJK_HomeState ADV should be the two character USA state code. 
					This code is what is necessary on the form your users have to enter their information.
					<br>
					<center>
					<form name='ADVData' action = "EditUserData.php" method = "post">
						<?php
						$aVariables[0] = "sAction";
						$aVariables[1] = "sAccountUnq";
						$aVariables[2] = "iDBLoc2";
						$aVariables[3] = "sSortBy";
						$aVariables[4] = "sSearchIDNum";
						$aVariables[5] = "sSearchEmail";
						$aVariables[6] = "sSearchLogin";
						$aVariables[7] = "sIDNumQualifier";
						$aVariables[8] = "sEmailQualifier";
						$aVariables[9] = "sLoginQualifier";
						$aVariables[10] = "sAllowWildcards";
						$aValues[0] = "";
						$aValues[1] = $sAccountUnq;
						$aValues[2] = Request("iDBLoc2");
						$aValues[3] = Request("sSortBy");
						$aValues[4] = Trim(Request("sSearchIDNum"));
						$aValues[5] = Trim(Request("sSearchEmail"));
						$aValues[6] = Trim(Request("sSearchLogin"));
						$aValues[7] = Trim(Request("sIDNumQualifier"));
						$aValues[8] = Trim(Request("sEmailQualifier"));
						$aValues[9] = Trim(Request("sLoginQualifier"));
						$aValues[10] = Trim(Request("sAllowWildcards"));
						Echo DOMAIN_Link("P");
						?>
						<table width=671 cellpadding = 0 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
						<table cellpadding = 5 width=671>
							<?php
							// display all ADVs for this user. Just don't display ALL data for the ADVs. Display the data on these conditions:
							//	--rights associated w/ them are rights the admin has
							//	--the admin has PHPJK_UD_VIEW rights - this allows for viewing
							//	--the admin has PHPJK_UD_EDIT rights - this allows for editing
							If ( (ACCNT_ReturnRights("PHPJK_UD_VIEW")) || (ACCNT_ReturnRights("PHPJK_UD_EDIT")) )
								DisplayCustomADVs();
							?>
						</table>
						</td></tr></table>
					</td>
				</tr>
				<tr>
					<td align=right>
						<?php PrintRecordsetNav_ADMIN( "EditUserData.php", "", "Accounts" ); ?>
					</td>
				</tr>
			</table>
		</form>
		<?php
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function HeaderHTML()
	{
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=5 bgcolor=FFFFFF width=100%><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If ( (ACCNT_ReturnRights("PHPJK_MR_MODIFY") ) || ( ACCNT_ReturnRights("PHPJK_MR_VIEW") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='index.php?iDomainUnq=" . Request("iDomainUnq") . "&sSortBy=" . Request("sSortBy") . "&iDBLoc=" . Request("iDBLoc2") . "&sSearchIDNum=" . Request("sSearchIDNum") . "&sSearchEmail=" . rawurlencode(Request("sSearchEmail")) . "&sSearchLogin=" . rawurlencode(Request("sSearchLogin")) . "&sIDNumQualifier=" . Request("sIDNumQualifier") . "&sEmailQualifier=" . Request("sEmailQualifier") . "&sLoginQualifier=" . Request("sLoginQualifier") . "&sAllowWildcards=" . Request("sAllowWildcards") . "'><img src='../../Images/Administrative/Return.gif' Width=30 Height=38 Border=0 Alt='Return to the main Manage Accounts page.'></a></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>";
				If ( ACCNT_ReturnRights("PHPJK_UD_EDIT") ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/UpdateUserADV.gif' ALIGN='absmiddle' Width=32 Height=42 Border=0 Alt='Save changes made to this accounts ADV information.' onClick='SubmitForm(\"UpdateADVs\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}				
				?>
				<td bgcolor=FFFFFF width=100%>&nbsp;</td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=5 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=7 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
?>
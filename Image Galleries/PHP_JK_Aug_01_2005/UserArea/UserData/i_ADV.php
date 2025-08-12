<?php 
	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*	If sAction = "ADD" then the form is being used to add new accounts.				*
	//*	Otherwise, it's being used to edit account data.								*
	//*																					*
	//************************************************************************************
	Function WriteADVForm($sAction)
	{
		Global $iUBoundADVArray;
		Global $aADVArray;
		Global $bHasADVRights;
		Global $aVariables;
		Global $aValues;
		Global $iTextScheme;
		Global $iColorScheme;
		Global $sLogin;
		Global $sPassword;
		Global $aDay;
		Global $aMonth;
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
		Global $iTableWidth;
		
		$sAction = strtoupper(Trim("sAction"));
		
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ADVData.sAction.value = sAction;
				document.ADVData.submit();
			}
			
		</script>
		<br>
		<center>
		<form name='ADVData' action = "index.php" method = "post" class='PageForm'>
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "PHPJK_IPAddress";
		$aVariables[2] = "PHPJK_TextScheme";
		$aVariables[3] = "sOriginalLogin";
		$aValues[0] = "";
		$aValues[1] = $_SERVER["REMOTE_ADDR"];
		$aValues[2] = "1";
		$aValues[3] = $sLogin;
		Echo DOMAIN_Link("P");
		?>
		<?php G_STRUCTURE_HeaderBar_Specific("YourAccount.gif", "", "", "/", "Accounts"); ?>
		<?php G_STRUCTURE_SubHeaderBar_Specific("PersonalInformation.gif", "", "", "PHPJK/", "Accounts");?>
		<table width=<?=$iTableWidth?> cellpadding = 0 cellspacing = 10 border = 0 class='TablePage_Boxed'>
			<tr>
				<td colspan=2></td>
				<td align=center>
					<b>Private
				</td>
				<td align=center>
					<b>Public
				</td>
			</tr>
			<?php If ( $PHPJK_FirstName !== False ) { ?>
			<tr>
				<td>
					First Name
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_FirstName", "32", "TEXT", "", "32", "", $PHPJK_FirstName); ?>
				</td>
				<?php PubPriCheck("PHPJK_FirstName_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_MiddleName !== False ) { ?>
			<tr>
				<td>
					Middle Name
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_MiddleName", "32", "TEXT", "", "32", "", $PHPJK_MiddleName); ?>
				</td>
				<?php PubPriCheck("PHPJK_MiddleName_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_LastName !== False ) { ?>
			<tr>
				<td>
					
					Last Name
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_LastName", "32", "TEXT", "", "32", "", $PHPJK_LastName); ?>
				</td>
				<?php PubPriCheck("PHPJK_LastName_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_BirthDay !== False ) { ?>
			<tr>
				<td>
					
					Birth Day
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_BirthDay", "", "SELECT", $aDay, "", "", $PHPJK_BirthDay); ?>
				</td>
				<?php PubPriCheck("PHPJK_BirthDay_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_BirthMonth !== False ) { ?>
			<tr>
				<td>
					
					Birth Month
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_BirthMonth", "", "SELECT", $aMonth, "", "", $PHPJK_BirthMonth); ?>
				</td>
				<?php PubPriCheck("PHPJK_BirthMonth_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_BirthYear !== False ) { ?>
			<tr>
				<td>
					
					Birth Year
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_BirthYear", "4", "TEXT", "", "4", "", $PHPJK_BirthYear); ?>
				</td>
				<?php PubPriCheck("PHPJK_BirthYear_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_HomeAddress1 !== False ) { ?>
			<tr>
				<td>
					
					Home Address
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_HomeAddress1", "250", "TEXT", "", "55", "", $PHPJK_HomeAddress1); ?>
				</td>
				<?php PubPriCheck("PHPJK_HomeAddress1_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_HomeAddress2 !== False ) { ?>
			<tr>
				<td>
					
					
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_HomeAddress2", "250", "TEXT", "", "55", "", $PHPJK_HomeAddress2); ?>
				</td>
				<?php PubPriCheck("PHPJK_HomeAddress2_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_HomeAddress3 !== False ) { ?>
			<tr>
				<td>
					
					
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_HomeAddress3", "250", "TEXT", "", "55", "", $PHPJK_HomeAddress3); ?>
				</td>
				<?php PubPriCheck("PHPJK_HomeAddress3_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_HomeCity !== False ) { ?>
			<tr>
				<td>
					
					City
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_HomeCity", "250", "TEXT", "", "32", "", $PHPJK_HomeCity); ?>
				</td>
				<?php PubPriCheck("PHPJK_HomeCity_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_HomeState !== False ) { ?>
			<tr>
				<td>
					
					State/Provence
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_HomeState", "250", "TEXT", "", "10", "", $PHPJK_HomeState); ?>
				</td>
				<?php PubPriCheck("PHPJK_HomeState_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_HomeZip !== False ) { ?>
			<tr>
				<td>
					
					Zip/Postal Code
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_HomeZip", "11", "TEXT", "", "5", "", $PHPJK_HomeZip); ?>
				</td>
				<?php PubPriCheck("PHPJK_HomeZip_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_HomePhone1 !== False ) { ?>
			<tr>
				<td>
					
					Home Phone 1
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_HomePhone1", "250", "TEXT", "", "15", "", $PHPJK_HomePhone1); ?>
				</td>
				<?php PubPriCheck("PHPJK_HomePhone1_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_HomePhone2 !== False ) { ?>
			<tr>
				<td>
					
					Home Phone 2
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_HomePhone2", "250", "TEXT", "", "15", "", $PHPJK_HomePhone2); ?>
				</td>
				<?php PubPriCheck("PHPJK_HomePhone2_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_HomeFax !== False ) { ?>
			<tr>
				<td>
					
					Home Fax
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_HomeFax", "250", "TEXT", "", "15", "", $PHPJK_HomeFax); ?>
				</td>
				<?php PubPriCheck("PHPJK_HomeFax_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_MobilePhone !== False ) { ?>
			<tr>
				<td>
					
					Mobile Phone
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_MobilePhone", "250", "TEXT", "", "15", "", $PHPJK_MobilePhone); ?>
				</td>
				<?php PubPriCheck("PHPJK_MobilePhone_V", $sAction); ?>
			</tr>
			<?php } ?>
		</table>
		

		<?php G_STRUCTURE_SubHeaderBar_Specific("OccupationalInformation.gif", "", "", "PHPJK/", "Accounts"); ?>
		<table width=<?=$iTableWidth?> cellpadding = 0 cellspacing = 10 border = 0 class='TablePage_Boxed'>
			<tr>
				<td colspan=2></td>
				<td align=center>
					<b>
					Private
				</td>
				<td align=center>
					<b>
					Public
				</td>
			</tr>
			<?php If ( $PHPJK_WorkAddress1 !== False ) { ?>
			<tr>
				<td>
					
					Work Address
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_WorkAddress1", "250", "TEXT", "", "55", "", $PHPJK_WorkAddress1); ?>
				</td>
				<?php PubPriCheck("PHPJK_WorkAddress1_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_WorkAddress2 !== False ) { ?>
			<tr>
				<td>
					
					
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_WorkAddress2", "250", "TEXT", "", "55", "", $PHPJK_WorkAddress2); ?>
				</td>
				<?php PubPriCheck("PHPJK_WorkAddress2_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_WorkAddress3 !== False ) { ?>
			<tr>
				<td>
					
					
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_WorkAddress3", "250", "TEXT", "", "55", "", $PHPJK_WorkAddress3); ?>
				</td>
				<?php PubPriCheck("PHPJK_WorkAddress3_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_WorkCity !== False ) { ?>
			<tr>
				<td>
					
					City
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_WorkCity", "250", "TEXT", "", "33", "", $PHPJK_WorkCity); ?>
				</td>
				<?php PubPriCheck("PHPJK_WorkCity_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_WorkState !== False ) { ?>
			<tr>
				<td>
					
					State/Provence
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_WorkState", "250", "TEXT", "", "10", "", $PHPJK_WorkState); ?>
				</td>
				<?php PubPriCheck("PHPJK_WorkState_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_WorkZip !== False ) { ?>
			<tr>
				<td>
					
					Zip/Postal Code
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_WorkZip", "11", "TEXT", "", "5", "", $PHPJK_WorkZip); ?>
				</td>
				<?php PubPriCheck("PHPJK_WorkZip_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_WorkPhone1 !== False ) { ?>
			<tr>
				<td>
					
					Work Phone 1
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_WorkPhone1", "250", "TEXT", "", "15", "", $PHPJK_WorkPhone1); ?>
				</td>
				<?php PubPriCheck("PHPJK_WorkPhone1_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_WorkPhone2 !== False ) { ?>
			<tr>
				<td>
					
					Work Phone 2
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_WorkPhone2", "250", "TEXT", "", "15", "", $PHPJK_WorkPhone2); ?>
				</td>
				<?php PubPriCheck("PHPJK_WorkPhone2_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_WorkFax !== False ) { ?>
			<tr>
				<td>
					
					Work Fax
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_WorkFax", "250", "TEXT", "", "15", "", $PHPJK_WorkFax); ?>
				</td>
				<?php PubPriCheck("PHPJK_WorkFax_V", $sAction); ?>
			</tr>
			<?php } ?>
		</table>
		<?php G_STRUCTURE_SubHeaderBar_Specific("InternetInformation.gif", "", "", "PHPJK/", "Accounts"); ?>
		<table width=<?=$iTableWidth?> cellpadding = 0 cellspacing = 10 border = 0 class='TablePage_Boxed'>
			<tr>
				<td colspan=2></td>
				<td align=center>
					<b>
					Private
				</td>
				<td align=center>
					<b>
					Public
				</td>
			</tr>
			<?php If ( $PHPJK_EmailAddress !== False ) { ?>
			<tr>
				<td>
					
					Email Address
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_EmailAddress", "250", "TEXT", "", "53", "", $PHPJK_EmailAddress); ?>
				</td>
				<?php PubPriCheck("PHPJK_EmailAddress_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_HomepageURL !== False ) { ?>
			<tr>
				<td>
					
					Homepage URL
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_HomepageURL", "250", "TEXT", "", "53", "", $PHPJK_HomepageURL); ?>
				</td>
				<?php PubPriCheck("PHPJK_HomepageURL_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_ICQ !== False ) { ?>
			<tr>
				<td>
					
					ICQ Number
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_ICQ", "250", "TEXT", "", "15", "", $PHPJK_ICQ); ?>
				</td>
				<?php PubPriCheck("PHPJK_ICQ_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_AIM !== False ) { ?>
			<tr>
				<td>
					
					AIM Handle
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_AIM", "250", "TEXT", "", "15", "", $PHPJK_AIM); ?>
				</td>
				<?php PubPriCheck("PHPJK_AIM_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_Yahoo !== False ) { ?>
			<tr>
				<td>
					
					Yahoo Handle
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_Yahoo", "250", "TEXT", "", "15", "", $PHPJK_Yahoo); ?>
				</td>
				<?php PubPriCheck("PHPJK_Yahoo_V", $sAction); ?>
			</tr>
			<?php } ?>
			<?php If ( $PHPJK_MSN !== False ) { ?>
			<tr>
				<td>
					
					MSN Handle
				</td>
				<td>
					<?php Disp_Form_Field("PHPJK_MSN", "250", "TEXT", "", "15", "", $PHPJK_MSN); ?>
				</td>
				<?php PubPriCheck("PHPJK_MSN_V", $sAction); ?>
			</tr>
			<?php } ?>
		</table>
		<?php 
	
		// additional information (ADVs) created by the administrator
		If ( $iUBoundADVArray > 0 ) {
			G_STRUCTURE_SubHeaderBar_Specific("", "", "&nbsp;", "PHPJK/", "Accounts");
			?>
			<table width=<?=$iTableWidth?> cellpadding = 0 cellspacing = 10 border = 0 class='TablePage_Boxed'>
			<tr>
				<td colspan=2></td>
				<td align=center>
					<b>
					Private
				</td>
				<td align=center>
					<b>
					Public
				</td>
			</tr>
				<?php 
				For ( $x = 0; $x < ($iUBoundADVArray); $x++){
					If ( $aADVArray[6][$x] ) {
						?>
						<tr>
							<td valign=top>
								<?=$aADVArray[2][$x]; ?>:
							</td>
							<td valign=top>
								<?php 
								Echo "<input type='hidden' name='OLD_" . $aADVArray[0][$x] . "' value=\"" . htmlentities($aADVArray[4][$x]) . "\">";
								If ( Trim($aADVArray[1][$x]) == "V" ) {
									If ( Trim($aADVArray[3][$x]) == "Y" ) {
										Echo "<sup>*</sup>";
									}
									Echo "<input type='text' name='" . $aADVArray[0][$x] . "' value=\"" . htmlentities($aADVArray[4][$x]) . "\" size='53' maxlength='255'>";
								}ElseIf ( Trim($aADVArray[1][$x]) == "T" ) {
									If ( Trim($aADVArray[3][$x]) == "Y" ) {
										Echo "<sup>*</sup>";
									}
									Echo "<input type='hidden' name='TEXTAREA_" . $aADVArray[0][$x] . "' value='T'>";
									Echo "<textarea name='" . $aADVArray[0][$x] . "' cols=53 rows=4>" . htmlentities($aADVArray[4][$x]) . "</textarea>";
								}
								Echo "<td align=center>";
								If ( ( $aADVArray[5][$x] == "PRIVATE" ) || ( $aADVArray[5][$x] == "" ) ) {
									Echo "<input type='radio' name='" . $aADVArray[0][$x] . "_V' value='PRIVATE' checked style='border: 0px;'>";
									Echo "<input type='hidden' name='OLD_" . $aADVArray[0][$x] . "_V' value='PRIVATE'>";
								}Else{
									Echo "<input type='radio' name='" . $aADVArray[0][$x] . "_V' value='PRIVATE' style='border: 0px;'>";
									Echo "<input type='hidden' name='OLD_" . $aADVArray[0][$x] . "_V' value='PUBLIC'>";
								}
								Echo "</td><td align=center>";
								If ( $aADVArray[5][$x] == "PUBLIC" ) {
									Echo "<input type='radio' name='" . $aADVArray[0][$x] . "_V' value='PUBLIC' checked style='border: 0px;'>";
								}Else{
									Echo "<input type='radio' name='" . $aADVArray[0][$x] . "_V' value='PUBLIC' style='border: 0px;'>";
								}
								?>
							</td>
						</tr>
						<?php 
					}
				}
				?>
			</table>
			<?php 
		}
		// end additional ADVs
		
		Echo "<BR><BR>";
		G_STRUCTURE_HeaderBar_Specific("LoginInformation.gif", "", "", "/", "Accounts");
		?>
		<table width=<?=$iTableWidth?> cellpadding = 0 cellspacing = 10 border = 0 class='TablePage_Boxed'>
			<tr>
				<td align = right>
					Your choice of logins
				</td>
				<td>
					<input type='hidden' name='sOldLogin' value='<?=$sLogin?>'>
					<sup>*</sup><input type = "text" name = "sLogin" value = '<?=$sLogin?>'>
				</td>
			</tr>
			<tr>
				<td align = right>
					Your choice of passwords
				</td>
				<td>
					<input type='hidden' name='sOldPassword' value='<?=$sPassword?>'>
					<sup>*</sup><input type = "text" name = "sPassword" value = '<?=$sPassword?>'>
				</td>
			</tr>
		</table>
		<br><br>
		<input type='image' src="<?=G_STRUCTURE_DI("SaveChanges.gif", $GLOBALS["SCHEMEBASED"])?>" style="BORDER: none; vertical-align: middle;" onClick='SubmitForm("EditAccount")'>
		<br><br>
		</form>
		<?php 
	}
	//************************************************************************************

	
	//************************************************************************************
	//*																					*
	//*	This displays the checkbox next to the form field to let the user set it as		*
	//*		either PUBLIC or PRIVATE. It also checks the box if the form field is		*
	//*		PUBLIC.																		*
	//*																					*
	//************************************************************************************
	Function PubPriCheck($sFormField, $sAction)
	{
		Global $iLoginAccountUnq;
		
		$sTemp = str_replace("_V", "", $sFormField);
		
		$sQuery			= "SELECT ViewLvl FROM AccountData (NOLOCK) WHERE AccountUnq = " . $iLoginAccountUnq . " AND MapName = '" . SQLEncode($sTemp) . "'";
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) ){
			$sTemp = Trim($rsRow["ViewLvl"]);
		}Else{
			$sTemp = "";
		}		
		
		Echo "<td align=center>";
		If ( ( $sTemp == "PRIVATE" ) || ( $sTemp == "" ) ) {
			Echo "<input type='radio' name='" . $sFormField . "' value='PRIVATE' checked style='border: 0px;'>";
			Echo "<input type='hidden' name='OLD_" . $sFormField . "' value='PRIVATE'>";
		}Else{
			Echo "<input type='radio' name='" . $sFormField . "' value='PRIVATE' style='border: 0px;'>";
			Echo "<input type='hidden' name='OLD_" . $sFormField . "' value='PUBLIC'>";
		}
		Echo "</td><td align=center>";
		If ( $sTemp == "PUBLIC" ) {
			Echo "<input type='radio' name='" . $sFormField . "' value='PUBLIC' checked style='border: 0px;'>";
		}Else{
			Echo "<input type='radio' name='" . $sFormField . "' value='PUBLIC' style='border: 0px;'>";
		}
		Echo "</td>";
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This displays the ADV's according to the sSQLText passed to it					*
	//*	Used for printing several sets of ADV's from several rights levels				*
	//*		when logged in as an admin.													*
	//*																					*
	//************************************************************************************
	Function DisplayCustomADVs($sQuery, $sAction)
	{
		
		$rsRecordSet	= DB_Query($sQuery);
		while ( $rsRow = DB_Fetch($rsRecordSet) ) {
			$bDisplay = FALSE;
			If ( Trim($rsRow["Required"]) == "Y" ) {
				$bDisplay = TRUE;
			}Else{
				If ( Trim($rsRow["Visible"]) == "Y" )
					$bDisplay = TRUE;
			}
			If ( $bDisplay ) {
				?>
				<tr>
					<td align = right valign=top>
						
						<?=$rsRow["Description"]; ?>
					</td>
					<td valign=top>
						<?php 
						If ( Trim($rsRow["DataType"]) == "V" ) {
							Disp_Form_Field($rsRow["MapName"], "250", "TEXT", "", "50", "", $sTemp);
						}Else{
							Disp_Form_Field($rsRow["MapName"], "", "TEXTFIELD", "", "40", "5", $sTemp);
						}
						PubPriCheck($rsRow["MapName"] . "_V", $sAction);
						?>
					</td>
				</tr>
				<?php 
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Prints out form field elements based on the ADV's in the DB.					*
	//*	sADV is the ADV name to look for and use in this form field.					*
	//*	sMaxLen is for a text form field - it's the max length							*
	//*	sType is the type of form field: TEXT, HIDDEN, RADIO, CHECKBOX, SELECT, 		*
	//*		MULTISELECT, FILE, TEXTFIELD												*
	//*	aData is the array that hold the data for RADIO, CHECKBOX, SELECT and 			*
	//*		MULTISELECT																	*
	//*	sCols is the number of columns for any text type of field like TEXT, TEXTFIELD	*
	//*	sRows is the number of rows for TEXTFIELD or SELECT or MULTISELECT				*
	//*	sValue is the value as passed from the caller									*
	//*																					*
	//************************************************************************************
	Function Disp_Form_Field($sADV, $sMaxLen, $sType, $aData, $sCols, $sRows, $sValue)
	{
		Global $iLoginAccountUnq;
		
		$sTemp = "";
		If ( strtoupper($sType) == "MULTISELECT" ) {
			// The only difference between a multiselect form field and a single is the "multiple" in the <select>
			$sTemp = " multiple";
			$sType = "SELECT";
		}

		If ( Trim($sValue) == "" )
			$sValue = Request($sADV);
		
		Echo "<input type='hidden' name='OLD_" . $sADV . "' value=\"" . htmlentities(ACCNT_ReturnADV($sADV, "V", $iLoginAccountUnq, "0", $sViewLvl)) . "\">";
		$sType = strtoupper($sType);
		
		If ( $sType == "TEXT" ) {
			// Check to see if it's Required and/or Visible and that it even exists!
			$sQuery			= "SELECT * FROM AccountMap (NOLOCK) WHERE MapName = '" . SQLEncode($sADV) . "'";
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) ) {
				$sTemp = "<input type='text' name='" . $sADV . "' value=\"" . htmlentities($sValue) . "\" size='" . $sCols . "' maxlength='" . $sMaxLen . "'>";
				If ( Trim($rsRow["Required"]) == "Y" ) {
					Echo "<sup>*</sup>" . $sTemp;
				}Else{
					If ( Trim($rsRow["Visible"]) == "Y" )
						Echo "&nbsp;&nbsp;" . $sTemp;
				}
			}
		}ElseIf ( $sType == "TEXTFIELD" ) {
			// Check to see if it's Required and/or Visible and that it even exists!
			$sQuery			= "SELECT * FROM AccountMap (NOLOCK) WHERE MapName = '" . SQLEncode($sADV) . "'";
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) ) {
				$sTemp = "<textarea name='" . $sADV . "' cols=" . $sCols . " rows=" . $sRows . ">";
				If ( Trim($rsRow["Required"]) == "Y" ) {
					Echo "<sup>*</sup>" . $sTemp;
				}Else{
					If ( Trim($rsRow["Visible"]) == "Y" )
						Echo "&nbsp;&nbsp;" . $sTemp;
				}
				Echo htmlentities($sValue) . "</textarea>";
			}
		}ElseIf ( $sType == "HIDDEN" ) {
			// Check to see if it's Required and/or Visible and that it even exists!
			$sQuery			= "SELECT * FROM AccountMap (NOLOCK) WHERE MapName = '" . SQLEncode($sADV) . "'";
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) ) {
				$sTemp = "<input type='hidden' name='" . $sADV . "' value=\"" . htmlentities($sValue) . "\">";
				If ( Trim($rsRow["Required"]) == "Y" ) {
					Echo $sTemp;
				}Else{
					If ( Trim($rsRow["Visible"]) == "Y" )
						Echo $sTemp;
				}
			}
		}ElseIf ( $sType == "SELECT" ) {
			$bDisplay = FALSE;
			// Check to see if it's Required and/or Visible and that it even exists!
			$sQuery			= "SELECT * FROM AccountMap (NOLOCK) WHERE MapName = '" . SQLEncode($sADV) . "'";
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) ) {
				If ( Trim($rsRow["Required"]) == "Y" ) {
					Echo "<sup>*</sup>";
					$bDisplay = TRUE;
				}Else{
					If ( Trim($rsRow["Visible"]) == "Y" ) {
						Echo "&nbsp;&nbsp;";
						$bDisplay = TRUE;
					}
				}
				If ( $bDisplay ) {
					Echo "<select name=\"" . htmlentities($sADV) . "\"" . $sTemp . ">";
					For ( $x = 0; $x < count($aData); $x++)
					{
						If ( $sValue == $aData[$x] ) {
							Echo "<option value=\"" . htmlentities($aData[$x]) . "\" selected>" . $aData[$x] . "</option>";
						}Else{
							Echo "<option value=\"" . htmlentities($aData[$x]) . "\">" . $aData[$x] . "</option>";
						}
					}
					Echo "</select>";
				}
			}
		}
	}
	//************************************************************************************
?>
<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_DEFAULT.php");
	
	If (ACCNT_ReturnRights("PHPJK_IR_UPDATE")) {
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
		$sRightsLvl			= Trim(Request("sRightsLvl"));
		$sRemoveRightsLvl	= Trim(Request("sRemoveRightsLvl"));
		$sAction			= Request("sAction");
		$sError				= "";
		$sSuccess			= "";
		
		If ( $sAction == "UpdateRightsLvl" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_IR_UPDATE") ) {
				$sError = "You must login with Domains rights.<br>";
			}Else{
	            ForEach ($_POST as $sRadio=>$sValue)
	            {
	            	If ( strpos($sRadio, "sInitialRight") !== false ) {
	            		$sRightsLvl	= str_replace("sInitialRight", "", $sRadio);
	            		$sSetting = $sValue;
	            		If ( Request("sOldInitialRight" . $sRightsLvl) != $sSetting ) {
	            			// it's different than it originally was, so change it.
							If ( $sSetting == "Y" ) {
								If ( $sRightsLvl == "" ) {
									$sError = "Please enter the rights level.";
								}Else{
									If ( $sError == "" ) {
										If ( ! InSystem($sRightsLvl) ) {
											If ( ACCNT_ReturnRights($sRightsLvl) ) {	// admins can only change rights they have
												DB_Insert ("INSERT INTO InitialRights VALUES (1, 0, '" . SQLEncode($sRightsLvl) . "')");
												$sSuccess = "Initial Rights setting successfully modified.";
											}Else{
												$sError = "You must login with Domains rights.<br>";
											}
										}Else{
											$sError = "This rights level is already an initial rights level.";
										}
									}
								}
							}
							If ( $sSetting == "N" ) {
								If ( $sRightsLvl == "" ) {
									$sError = "Please enter the rights level.";
								}Else{
									If ( InSystem($sRightsLvl) ) {
										DB_Update ("DELETE FROM InitialRights WHERE RightsLvl = '" . SQLEncode($sRightsLvl) . "'");
										$sSuccess = "Initial Rights setting successfully modified.";
									}
								}
							}
						}	
	            	}
	            }
			}
		}
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		If ( $sSuccess != "" )
			DOMAIN_Message($sSuccess, "SUCCESS");

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
	Function InSystem($RightsLvl)
	{
		$sQuery			= "SELECT * FROM InitialRights (NOLOCK) WHERE RightsLvl = '" . $RightsLvl . "'";
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
			Return True;
			
		Return False;
	}
	//************************************************************************************

	
	
	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $aVariables;
		Global $aValues;
		
		$sBGColor = $GLOBALS["BGColor2"];
		
		$sQuery			= "SELECT * FROM RightsLookup (NOLOCK) ORDER BY RightsLvl";
		$rsRecordSet	= DB_Query($sQuery);
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageADV.sAction.value = sAction;
				document.ManageADV.submit();
			}
			
		</script>
		<form name='ManageADV' action='index.php' method='post'>
		<?php
		$aVariables[0] = "sAction";
		$aValues[0] = "New";
		Echo DOMAIN_Link("P");
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Manage Initial User Rights</b></font>
					<br>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Right</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Initial Right?</b></td>
						</tr>
						<?php
						while ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor == $GLOBALS["BGColor1"] ) 
							{
								$sBGColor	= $GLOBALS["PageBGColor"];
								$sTextColor	= $GLOBALS["PageText"];
							}Else{
								$sBGColor	= $GLOBALS["BGColor1"];
								$sTextColor	= $GLOBALS["TextColor1"];
							}
							$sRightsConst	= Trim($rsRow["RightsConst"]);
							$sRightsLvl		= Trim($rsRow["RightsLvl"]);
							
							$sQuery			= "SELECT * FROM InitialRights (NOLOCK) WHERE RightsLvl = '" . $sRightsLvl . "'";
							$rsRecordSet2	= DB_Query($sQuery);
							if ( $rsRow2 = DB_Fetch($rsRecordSet2) )
							{
								$sNo				= "";
								$sYes				= "checked";
								$sOldInitialRight	= "Y";
							}Else{
								$sYes				= "";
								$sNo				= "checked";
								$sOldInitialRight	= "N";
							}
							?>
							<tr>
								<td bgcolor=<?=$sBGColor?> width=100%><font color='<?=$sTextColor?>'><b><?=$sRightsLvl?></b>&#151;<?=$sRightsConst?></font></td>
								<td bgcolor=<?=$sBGColor?>>
									<?php If ( ACCNT_ReturnRights($sRightsLvl) ) { ?>
									<table cellpadding=0 cellspacing=0 border=0>
										<tr>
											<td bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor?>'>Yes:</td>
											<td align=center bgcolor=<?=$sBGColor?>><input type='radio' name='sInitialRight<?=$sRightsLvl?>' value='Y' <?=$sYes?>></td>
											<td bgcolor=<?=$sBGColor?>>&nbsp;&nbsp;&nbsp;&nbsp;<input type='hidden' name='sOldInitialRight<?=$sRightsLvl?>' value='<?=$sOldInitialRight?>'></td>
											<td bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor?>'>No:</td>
											<td align=center bgcolor=<?=$sBGColor?>><input type='radio' name='sInitialRight<?=$sRightsLvl?>' value='N' <?=$sNo?>></td>
										</tr>
									</table>
									<?php }Else{ ?>
									<center>No Access</center>
									<?php } ?>
								</td>
							</tr>
							<?php
						}
						?>
					</table>
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
	//*																					*
	//************************************************************************************
	Function HeaderHTML()
	{
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=4 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If (ACCNT_ReturnRights("PHPJK_IR_UPDATE")) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/Update_Account.gif' ALIGN='absmiddle' Width=31 Height=39 Border=0 Alt='Update Initial Rights changes you have made.' onClick='SubmitForm(\"UpdateRightsLvl\")'></td>";
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
				<td colspan=4 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=6 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
?>
<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_DEFAULT.php");
	
	$sAccountUnq = "";
	
	If ( (ACCNT_ReturnRights("PHPJK_MR_MODIFY")) || (ACCNT_ReturnRights("PHPJK_MR_VIEW")) ) {
		HeaderHTML();
		Main();
	}Else{
		DOMAIN_Message("You must login with Account rights.", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_ADMIN_CLOSE.php");
	

	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iLoginAccountUnq;
		Global $sAccountUnq;
		
		$sAccountUnq = Trim(Request("sAccountUnq"));
		If ( $sAccountUnq == "" ) {
			// there is a screwup and no accountunq on the querystring - happens if they logout, login as someone else, logout, and relogin...weird, but happens
			ob_flush();
			header( 'location:index.php' );
		}
		
		$sAction	= Request("sAction");
		$sSuccess	= "";
		$sError		= "";

		If ( $sAction == "UpdateRights" ) {
			If (ACCNT_ReturnRights("PHPJK_MR_MODIFY")) {
	            ForEach ($_POST as $sRadio=>$sValue)
	            {
            		If ( strpos($sRadio, "ORIGINAL") !== false ) {
	            		$sRightsLvl = str_replace("ORIGINAL", "", $sRadio);
	            		If ( ( $sValue == "NO" ) && ( Request($sRightsLvl) == "YES" ) ) {
	            			// Give them the rights
							If ( ACCNT_ReturnRights($sRightsLvl) ) {	// admins can only change rights they have
								If ( $sAccountUnq != "" ) {
									// Modify the system
									If ( InSystem($sRightsLvl, $sAccountUnq) === False ) {
										DB_Insert ("INSERT INTO Rights VALUES (" . $sAccountUnq . ", 0, 0, 1, '" . SQLEncode($sRightsLvl) . "', GetDate(), NULL)");
										$sSuccess = "Rights granted successfully.";
									}Else{
										DB_Update ("UPDATE Rights SET RevokeDate = NULL WHERE AccountUnq = " . $sAccountUnq . " AND RightsLvl = '" . SQLEncode($sRightsLvl) . "'");
										$sSuccess = "Rights were reinstated successfully.";
									}
								}Else{
									$sError = "Please choose an account to work with.";
								}
							}Else{
								$sError = "You cannot modify this right.";
							}
	            		}ElseIf ( ( $sValue == "YES" ) && ( Request($sRightsLvl) == "NO" ) ) {
							// Modify the system - revoke the right
							If ( InSystem($sRightsLvl, $sAccountUnq) === False ) {
								//$sError = "That person has never been associated with the rights level.<br>No action taken."
							}Else{
								If ( ( $iLoginAccountUnq == $sAccountUnq ) && ( $sRightsLvl = "PHPJK_MR_MODIFY" ) ) {
									// If people could revoke their own rights, they wouldn't be able to add them back again if they did it in error.
									$sError = "You cannot revoke your own rights.<br>No action taken.";
								}Else{
									DB_Update ("UPDATE Rights SET RevokeDate = GetDate() WHERE AccountUnq = " . $sAccountUnq . " AND RightsLvl = '" . SQLEncode($sRightsLvl) . "'");
									$sSuccess = "Rights revoked successfully.";
								}
							}
	            		}
					}
	            }
			}Else{
				$sError = "You cannot modify rights.";
			}
		}
		
		If ( $sSuccess != "" )
			DOMAIN_Message($sSuccess, "SUCCESS");
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		
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
	Function InSystem($sRightsLvl, $sAccountUnq)
	{
		// REMEMBER: that sometimes this will return NULL. The if/then check calling
		//	this must take that into consideration -- a NULL does not mean FALSE! Just means
		//	that the user has never been revoked before.
		$sQuery			= "SELECT RevokeDate FROM Rights (NOLOCK) WHERE AccountUnq = " . $sAccountUnq . " AND RightsLvl = '" . SQLEncode($sRightsLvl) . "'";
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow["RevokeDate"];

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
		Global $sAccountUnq;
		
		$sBGColor = $GLOBALS["BGColor2"];
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageRights.sAction.value = sAction;
				document.ManageRights.submit();
			}
			
		</script>
		<form name='ManageRights' action='EditRights.php' method='post'>
		<?php
		$aVariables[0] = "sAction";
		$aVariables[1] = "sAccountUnq";
		$aVariables[2] = "iDBLoc";
		$aVariables[3] = "sSortBy";
		$aVariables[4] = "sSearchIDNum";
		$aVariables[5] = "sSearchEmail";
		$aVariables[6] = "sSearchLogin";
		$aVariables[7] = "sIDNumQualifier";
		$aVariables[8] = "sEmailQualifier";
		$aVariables[9] = "sLoginQualifier";
		$aVariables[10] = "sBeginSearch";
		$aVariables[11] = "sAllowWildcards";
		$aVariables[12] = "sRSearch";
		$aVariables[13] = "sLSearch";
		$aVariables[14] = "sJoin";
		$aVariables[15] = "sSort";
		$aValues[0] = "";
		$aValues[1] = $sAccountUnq;
		$aValues[2] = Request("iDBLoc");
		$aValues[3] = Request("sSortBy");
		$aValues[4] = Request("sSearchIDNum");
		$aValues[5] = Request("sSearchEmail");
		$aValues[6] = Request("sSearchLogin");
		$aValues[7] = Request("sIDNumQualifier");
		$aValues[8] = Request("sEmailQualifier");
		$aValues[9] = Request("sLoginQualifier");
		$aValues[10] = Request("sBeginSearch");
		$aValues[11] = Request("sAllowWildcards");
		$aValues[12] = Request("sRSearch");
		$aValues[13] = Request("sLSearch");
		$aValues[14] = Request("sJoin");
		$aValues[15] = Request("sSort");
		Echo DOMAIN_Link("P");
		
		DOMAIN_Link_Clear();
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Manage Account Rights </b></font>
					<br>
					<table><tr>
						<td width=50%><font color='<?=$GLOBALS["PageText"]?>'>
							User: <b><?=ACCNT_UserName($sAccountUnq)?></b><br>
						</td>
						<td width=50%><font color='<?=$GLOBALS["PageText"]?>'>
						</td>
					</tr></table>
					<br>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Right</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Grant Date</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Revoke Date</b></td>
							<td colspan=2 bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Give Rights?</b></td>
						</tr>
						<?php
						$sQuery			= "SELECT * FROM RightsLookup (NOLOCK)";
						$rsRecordSet	= DB_Query($sQuery);
						while ( $rsRow = DB_Fetch($rsRecordSet) ) {
							If ( $sBGColor == $GLOBALS["BGColor1"] ) {
								$sBGColor	= $GLOBALS["PageBGColor"];
								$sTextcolor	= $GLOBALS["PageText"];
							}Else{
								$sBGColor	= $GLOBALS["BGColor1"];
								$sTextcolor	= $GLOBALS["TextColor1"];
							}
							$sHasRights = FALSE;
							$sRightsLvl = Trim($rsRow["RightsLvl"]);
							
							$sQuery			= "SELECT * FROM Rights (NOLOCK) WHERE RightsLvl = '" . $sRightsLvl . "' AND AccountUnq = " . $sAccountUnq;
							$rsRecordSet2	= DB_Query($sQuery);
							If ( $rsRow2 = DB_Fetch($rsRecordSet2) ){
								If ( is_null($rsRow2["RevokeDate"]) ) {
									$sHasRights = TRUE;
								}Else{
									If ( ! is_null($rsRow2["GrantDate"]) ) {
										If ( strtotime($rsRow2["RevokeDate"]) < strtotime($rsRow2["GrantDate"]) )
											$sHasRights = TRUE;
									}
								}
								$sGrantDate = $rsRow2["GrantDate"];
								$sRevokeDate = $rsRow2["RevokeDate"];
							}Else{
								$sGrantDate = "";
								$sRevokeDate = "";
							}
							?>
							<tr>
								<td valign=top bgcolor=<?=$sBGColor?>><font size=-2 color='<?=$GLOBALS["TextColor1"]?>'><b><?=$sRightsLvl?></b>&#151;<?=$rsRow["RightsConst"]?></td>
								<td valign=top bgcolor=<?=$sBGColor?>><font size=-2 color='<?=$GLOBALS["TextColor1"]?>'><?=$sGrantDate?></td>
								<td valign=top bgcolor=<?=$sBGColor?>><font size=-2 color='<?=$GLOBALS["TextColor1"]?>'><?=$sRevokeDate?></td>
								<td valign=top bgcolor=<?=$sBGColor?>>
									<?php If ( ACCNT_ReturnRights($sRightsLvl) ) { ?>
									<table cellpadding=0 cellspacing=0 border=0>
										<tr>
										<td><font size=-2 color='<?=$sTextcolor?>'>Yes: </td>
										<td>
											<input type='hidden' name='ORIGINAL<?=$sRightsLvl?>' value='<?php If ( $sHasRights ) { Echo "YES"; }Else{ Echo "NO"; } ?>'>
											<input type='radio' name='<?=$sRightsLvl?>' value='YES'<?php If ( $sHasRights ) Echo " checked"; ?>>
										</td>
										<td><font size=-2 color='<?=$sTextcolor?>'>No: </td>
										<td>
											<input type='radio' name='<?=$sRightsLvl?>' value='NO'<?php If ( ! $sHasRights ) Echo " checked"; ?>>
										</td>
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
		Global $aVariables;
		Global $aValues;
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
					Echo "<td bgcolor=FFFFFF width=1><a href='index.php?" . DOMAIN_Link("G") . "&sSortBy=" . Request("sSortBy") . "&iDBLoc2=" . Request("iDBLoc") . "&sSearchIDNum=" . Request("sSearchIDNum") . "&sSearchEmail=" . rawurlencode(Request("sSearchEmail")) . "&sSearchLogin=" . rawurlencode(Request("sSearchLogin")) . "&sIDNumQualifier=" . Request("sIDNumQualifier") . "&sEmailQualifier=" . Request("sEmailQualifier") . "&sLoginQualifier=" . Request("sLoginQualifier") . "&sAllowWildcards=" . Request("sAllowWildcards") . "'><img src='../../Images/Administrative/Return.gif' Width=30 Height=38 Border=0 Alt='Return to the main Manage Accounts page.'></a></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If (ACCNT_ReturnRights("PHPJK_MR_MODIFY")) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/Update_Account.gif' ALIGN='absmiddle' Width=32 Height=39 Border=0 Alt='Update changed rights status for this user.' onClick='SubmitForm(\"UpdateRights\")'></td>";
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
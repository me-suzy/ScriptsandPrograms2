<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_DEFAULT.php");
	
	$sConfConst		= "";
	$sDescription	= "";
	$sValue			= "";
	$sViewRightsLvl	= "";
	$sEditRightsLvl	= "";
	
	If ( (ACCNT_ReturnRights("PHPJK_MCONF_UPDATE")) || (ACCNT_ReturnRights("PHPJK_MCONG_EDIT_RIGHTS")) ) {
		HeaderHTML();
		Main();
	}Else{
		DOMAIN_Message("You must login with Domains rights.<br>", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $sEditRightsLvl;
		Global $sViewRightsLvl;
		Global $sValue;
		Global $sDescription;
		Global $sConfConst;
		
		$sAction	= Trim(Request("sAction"));
		$sError		= "";
	
		If ( $sAction == "Update" ) {
			$sConfConst		= Trim(Request("sConfConst"));
			$sOldConfConst	= Trim(Request("sOldConfConst"));
			$sDescription	= Trim(Request("sDescription"));
			$sValue			= Trim(Request("sValue"));
			$sViewRightsLvl	= Trim(Request("sViewRightsLvl"));
			$sEditRightsLvl	= Trim(Request("sEditRightsLvl"));
			If ( ( ! ACCNT_ReturnRights("PHPJK_MCONF_UPDATE") ) && ( ! ACCNT_ReturnRights("PHPJK_MCONG_EDIT_RIGHTS") ) ) {
				$sError = "You must login with Domains rights.<br>";
			}Else{
				If ( $sConfConst == "" )
					$sError = "Please enter a configuration variable constant.<br>";
				If ( $sError == "" ) {
					If ( $sConfConst != $sOldConfConst ) {
						If ( ! DoesntExist($sConfConst) ){
							$sError = "This configuration variable already exists.<br>";
							$sConfConst = $sOldConfConst;
						}						
					}
					If ( $sError == "" ) {
						DB_Update ("UPDATE Configurations SET ConfConst = '" . SQLEncode($sConfConst) . "', Value = '" . SQLEncode($sValue) . "', Description = '" . SQLEncode($sDescription) . "', RightLvlv = '" . $sViewRightsLvl . "', ServerUnq = '0', GroupUnq = 0 WHERE ConfConst = '" . SQLEncode($sOldConfConst) . "'");
						If ( ( ACCNT_ReturnRights($sEditRightsLvl) ) || ( $sEditRightsLvl == "" ) ) {
							// only change the RightLvlc if they still have rights to change it - prevents them from changing
							//	to a rights level they don't have, but doesn't stop them from setting it to nothing
							DB_Update ("UPDATE Configurations SET RightLvlc = '" . $sEditRightsLvl . "' WHERE ConfConst = '" . SQLEncode($sConfConst) . "'");
						}
						DOMAIN_Message("Configuration Variable details updated.", "SUCCESS");
					}
				}
			}
		}Else{
			$sConfConst = Trim(Request("sConfConst"));

			$sQuery			= "SELECT * FROM Configurations WHERE ConfConst = '" . SQLEncode($sConfConst) . "'";
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) ) {
				$sDescription	= $rsRow["Description"];
				$sValue			= $rsRow["Value"];
				$sViewRightsLvl	= Trim($rsRow["RightLvlv"]);
				$sEditRightsLvl	= Trim($rsRow["RightLvlc"]);
			}
		}
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		
		WriteForm();
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
		Global $sEditRightsLvl;
		Global $sViewRightsLvl;
		Global $sValue;
		Global $sDescription;
		Global $sConfConst;
		 ?>
		<form name='UpdateConfConst' action='Edit.php' method='post'>
		<?php 

		$aVariables[0] = "sAction";
		$aValues[0] = "Update";
		
		Echo DOMAIN_Link("P");
		
		DOMAIN_Link_Clear();
		
		 ?>
		<input type='hidden' name='sOldValue' value="<?=htmlentities($sValue)?>">
		<input type='hidden' name='sOldDescription' value="<?=htmlentities($sDescription)?>">
		<input type='hidden' name='sOldViewRightsLvl' value='<?=$sViewRightsLvl ?>'>
		<input type='hidden' name='sOldEditRightsLvl' value='<?=$sEditRightsLvl ?>'>

		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Edit a Configuration Variable</b></font>
					<br>
					<table>
						<tr>
							<td><font color='<?=$GLOBALS["PageText"]?>'><b>Configuration Constant</b></font></td>
							<td><font color='<?=$GLOBALS["PageText"]?>'><b>Value</b></font></td>
						</tr>
						<?php If ( ( ACCNT_ReturnRights($sEditRightsLvl) ) || ( $sEditRightsLvl == "" ) ) { ?>
						<tr>
							<td><input type='hidden' name='sOldConfConst' value="<?=htmlentities($sConfConst)?>"><input type='text' name='sConfConst' value="<?=htmlentities($sConfConst)?>" size=45 maxlength=250><br></td>
							<td><input type='text' name='sValue' value="<?=htmlentities($sValue)?>" size=35 maxlength=250></td>
						</tr>
						<tr>
							<td colspan=2><input type='text' name='sDescription' value="<?=htmlentities($sDescription)?>" size=87 maxlength=250></td>
						</tr>
						<?php }ElseIf ( ( ACCNT_ReturnRights($sViewRightsLvl) || ( $sViewRightsLvl == "" ) ) ) { ?>
						<tr>
							<td><font color='<?=$GLOBALS["PageText"]?>'><?=htmlentities($sConfConst)?><br></td>
							<td><font color='<?=$GLOBALS["PageText"]?>'><?=htmlentities($sValue)?></td>
						</tr>
						<tr>
							<td colspan=2><font color='<?=$GLOBALS["PageText"]?>'><?=htmlentities($sDescription)?></td>
						</tr>
						<?php } ?>
					</table>
					<?php If ( ACCNT_ReturnRights("PHPJK_MCONG_EDIT_RIGHTS") ) { ?>
					<table>
						<tr>
							<td><font color='<?=$GLOBALS["PageText"]?>'><b>View Rights</b></font></td>
						</tr>
						<tr>
							<td>
								<select name = "sViewRightsLvl">
									<option value = ''>No associated rights level.</option>
									<?php 
									$sQuery			= "SELECT * FROM RightsLookup (NOLOCK)";
									$rsRecordSet	= DB_Query($sQuery);
									while ( $rsRow = DB_Fetch($rsRecordSet) )
									{
										If ( strLen ($rsRow["RightsConst"]) > 45 ) {
											$sTemp = substr($rsRow["RightsConst"], 0, 42) . "...";
										}Else{
											$sTemp = $rsRow["RightsConst"];
										}
										If ( $sViewRightsLvl == $rsRow["RightsLvl"] ) {
											Echo "<option value = \"" . htmlentities($rsRow["RightsLvl"]) . "\" Selected>" . $rsRow["RightsLvl"] . " - " . $sTemp . "</option>";
										}Else{
											Echo "<option value = \"" . htmlentities($rsRow["RightsLvl"]) . "\">" . $rsRow["RightsLvl"] . " - " . $sTemp . "</option>";
										}
									}									
									 ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><font color='<?=$GLOBALS["PageText"]?>'><b>Edit Rights</b></font></td>
						</tr>
						<tr>
							<td>
								<select name = "sEditRightsLvl">
									<option value = ''>No associated rights level.</option>
									<?php 
									$sQuery			= "SELECT * FROM RightsLookup (NOLOCK)";
									$rsRecordSet	= DB_Query($sQuery);
									while ( $rsRow = DB_Fetch($rsRecordSet) )
									{
										If ( strLen ($rsRow["RightsConst"]) > 45 ) {
											$sTemp = substr($rsRow["RightsConst"], 0, 42) . "...";
										}Else{
											$sTemp = $rsRow["RightsConst"];
										}
										If ( $sEditRightsLvl == $rsRow["RightsLvl"] ) {
											Echo "<option value = \"" . htmlentities($rsRow["RightsLvl"]) . "\" Selected>" . $rsRow["RightsLvl"] . " - " . $sTemp . "</option>";
										}Else{
											Echo "<option value = \"" . htmlentities($rsRow["RightsLvl"]) . "\">" . $rsRow["RightsLvl"] . " - " . $sTemp . "</option>";
										}
									}									
									 ?>
								</select>
							</td>
						</tr>
					</table>
					<?php } ?>
					<table width=671>
						<tr>
							<td align=center><input type='submit' value='Update Configuration Variable'></td>
						</tr>
					</table>

				</td>
			</tr>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		</form>
		<?php 
		
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This checks to see if the configuration variable they are trying to add is 		*
	//*		already in the DB.															*
	//*																					*
	//************************************************************************************	
	Function DoesntExist($sConfConst)
	{
		$sQuery			= "SELECT * FROM Configurations (NOLOCK) WHERE ConfConst = '" . SQLEncode($sConfConst) . "'";
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
			Return False;
			
		Return True;
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
				<td colspan=4 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If ( (ACCNT_ReturnRights("PHPJK_MCONF_CREATE_NEW")) || (ACCNT_ReturnRights("PHPJK_MCONF_DELETE")) || (ACCNT_ReturnRights("PHPJK_MCONF_UPDATE")) ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='index.php'><img src='../../Images/Administrative/Return.gif' Width=30 Height=38 Border=0 Alt='Return to edit Configuration Variables.'></a></td>";
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
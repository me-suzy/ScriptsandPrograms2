<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_DEFAULT.php");
	
	If ( (ACCNT_ReturnRights("PHPJK_MADV_DELETE")) || (ACCNT_ReturnRights("PHPJK_MADV_EDIT")) ) {
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
	
		$sAction	= Request("sAction");
		$sVisible	= "N";
		$sRequired	= "N";
		$sError		= "";
		$sSuccess	= "";

		If ( $sAction == "EditADV" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_MADV_EDIT") ) {
				$sError = "You must login with Domains rights.<br>";
			}Else{
				ForEach ($_POST as $sADVField=>$sValue)
				{
					If ( strpos($sADVField, "sOldMapName") !== false ) {
						// it's illegal to change the MapName of the ADV because it'll have to change all the user data and copy the data over if it's a different datatype
						$sOldMapName	= str_replace("sOldMapName", "", $sADVField);
						$sDescription	= Request("sDescription" . $sOldMapName);
						$sRequired		= Request("sRequired" . $sOldMapName);
						$sVisible		= Request("sVisible" . $sOldMapName);
						$sDataType		= Request("sDataType" . $sOldMapName);
						$sRightsLvl		= Request("sRightsLvl" . $sOldMapName);
						If ( $sError == "" ) {
							If ( $sDescription != Trim(Request("sOldDescription" . $sOldMapName)) )
								DB_Update ("UPDATE AccountMap SET Description = '" . SQLEncode($sDescription) . "' WHERE MapName = '" . SQLEncode($sOldMapName) . "'");
					
							If ( $sRequired != Trim(Request("sOldRequired" . $sOldMapName)) )
								DB_Update ("UPDATE AccountMap SET Required = '" . SQLEncode($sRequired) . "' WHERE MapName = '" . SQLEncode($sOldMapName) . "'");
					
							If ( $sVisible != Trim(Request("sOldVisible" . $sOldMapName)) )
								DB_Update ("UPDATE AccountMap SET Visible = '" . SQLEncode($sVisible) . "' WHERE MapName = '" . SQLEncode($sOldMapName) . "'");
					
							If ( $sDataType != Trim(Request("sOldDataType" . $sOldMapName)) )
								DB_Update ("UPDATE AccountMap SET DataType = '" . SQLEncode($sDataType) . "' WHERE MapName = '" . SQLEncode($sOldMapName) . "'");
					
							If ( $sRightsLvl != Trim(Request("sOldRightsLvl" . $sOldMapName)) )
								DB_Update ("UPDATE AccountMap SET RightsLvl = '" . SQLEncode($sRightsLvl) . "' WHERE MapName = '" . SQLEncode($sOldMapName) . "'");

							$sSuccess = "The Account Data Variable has been changed successfully!";
						}
					}
				}
			}
		}ElseIf ( $sAction == "DeleteADV" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_MADV_DELETE") ) {
				$sError = "You must login with Domains rights.<br>";
			}Else{			
				ForEach ($_POST as $sADVField=>$sValue)
				{
					If ( strpos($sADVField, "sDelete") !== false ) {
						$sOldMapName = str_replace("sDelete", "", $sADVField);
						If ( $sOldMapName != "" ) {
							DB_Update ("DELETE FROM AccountMap WHERE MapName = '" . SQLEncode($sOldMapName) . "'");
							$sDescription	= Request("sOldDescription" . $sOldMapName);
							$sRequired		= Request("sOldRequired" . $sOldMapName);
							$sVisible		= Request("sOldVisible" . $sOldMapName);
							$sDataType		= Request("sOldDataType" . $sOldMapName);
							$sRightsLvl		= Request("sOldRightsLvl" . $sOldMapName);
							$sTemp			= Request("sGroupUnq" . $sOldMapName);
							
							$sSuccess = "The Account Data Variable has been deleted successfully!<br><br>All user information is still intact.<br>If you need to retrieve it, simply add back the Variable you deleted.";
						}Else{
							$sError = "Please choose an Account Data Variable to delete.";
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
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $aVariables;
		Global $aValues;
		
		$sBGColor = $GLOBALS["BGColor2"];
		
		// Get the list of Rights Levels
		$x = 0;
		$sQuery			= "SELECT * FROM RightsLookup (NOLOCK)";
		$rsRecordSet	= DB_Query($sQuery);
		while ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			If ( strLen ($rsRow["RightsConst"]) > 45 ) {
				$sTemp = substr($rsRow["RightsConst"], 0, 42) . "...";
			}Else{
				$sTemp = $rsRow["RightsConst"];
			}
			$aRightsLvl[0][$x] = $rsRow["RightsLvl"];
			$aRightsLvl[1][$x] = $sTemp;
			$x++;
		}
		$iUBoundRightsLvl = $x;
		
		
		$sQuery			= "SELECT * FROM AccountMap (NOLOCK) ORDER BY MapName";
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
					<font size=+1><b>Manage ADVs (Account Data Variables)</b></font>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Name</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Description</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Required?</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b><sup>*</sup>Visible?</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>' size=-2>Textfield<br>&lt;250 char</td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>' size=-2>Notesfield<br>&gt;250 char</td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Delete?</b></td>
						</tr>
						<tr>
						<?php
						while ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor == $GLOBALS["BGColor1"] ) {
								$sBGColor	= $GLOBALS["PageBGColor"];
								$sFontColor	= $GLOBALS["PageText"];
								$sLinkColor	= "LargeNavPage";
							}Else{
								$sBGColor	= $GLOBALS["BGColor1"];
								$sFontColor = $GLOBALS["TextColor1"];
								$sLinkColor = "LargeNav1";
							}
							$sMapName		= $rsRow["MapName"];
							$sRequired		= Trim(strtoupper($rsRow["Required"]));
							$sVisible		= Trim(strtoupper($rsRow["Visible"]));
							$sDataType		= Trim(strtoupper($rsRow["DataType"]));
							$sRightsLvl		= $rsRow["RightsLvl"];
							$sDescription	= $rsRow["Description"];
							?>
							<input type='hidden' name="sOldDescription<?=htmlentities($sMapName)?>" value="<?=htmlentities($sDescription)?>">
							<input type='hidden' name="sOldRequired<?=htmlentities($sMapName)?>" value="<?=htmlentities($sRequired)?>">
							<input type='hidden' name="sOldVisible<?=htmlentities($sMapName)?>" value="<?=htmlentities($sVisible)?>">
							<input type='hidden' name="sOldDataType<?=htmlentities($sMapName)?>" value="<?=htmlentities($sDataType)?>">
							<input type='hidden' name="sOldRightsLvl<?=htmlentities($sMapName)?>" value="<?=htmlentities($sRightsLvl)?>">
							<input type='hidden' name="sGroupUnq<?=htmlentities($sMapName)?>" value="<?=htmlentities($rsRow["GroupUnq"])?>">
							<tr><td colspan=7 bgcolor='<?=$sBGColor?>'><img src='../../Images/Blank.gif' width=3 height=7></td></tr>
							<tr>
								<td bgcolor=<?=$sBGColor?>><input type='hidden' name="sOldMapName<?=htmlentities($sMapName)?>" value="<?=htmlentities($sMapName)?>"><font color='<?=$sFontColor?>'><?=$sMapName?></font></td>
								<td bgcolor=<?=$sBGColor?>><input type='text' name="sDescription<?=htmlentities($sMapName)?>" value="<?=htmlentities($sDescription)?>" size=25 maxlength=250></td>
								<td align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name="sRequired<?=htmlentities($sMapName)?>" value='Y' <?php If ( $sRequired == "Y" )  Echo "checked"; ?>></td>
								<td align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name="sVisible<?=htmlentities($sMapName)?>" value='Y' <?php If ( $sVisible == "Y" )  Echo "checked"; ?>></td>
								<td align=center bgcolor=<?=$sBGColor?>><input type='radio' name="sDataType<?=htmlentities($sMapName)?>" value='V' <?php If ( $sDataType == "V" )  Echo "checked"; ?>></td>
								<td align=center bgcolor=<?=$sBGColor?>><input type='radio' name="sDataType<?=htmlentities($sMapName)?>" value='T' <?php If ( $sDataType == "T" )  Echo "checked"; ?>></td>
								<td align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name="sDelete<?=htmlentities($sMapName)?>" value='DELETE'></td>
							</tr>
							<tr>
								<td colspan=7 bgcolor=<?=$sBGColor?>>
									<b>Optional Rights Level needed to access this ADV:</b>
									<br>
									<select name = "sRightsLvl<?=htmlentities($sMapName)?>">
										<option value = ''>No associated rights level.</option>
										<?php
										For ( $x = 0; $x < ($iUBoundRightsLvl - 1); $x++)
										{
											If ( $sRightsLvl == $aRightsLvl[0][$x] ) {
												Echo "<option value = \"" . htmlentities($aRightsLvl[0][$x]) . "\" Selected>" . htmlentities($aRightsLvl[0][$x]) . " - " . htmlentities($aRightsLvl[1][$x]) . "</option>";
											}Else{
												Echo "<option value = \"" . htmlentities($aRightsLvl[0][$x]) . "\">" . htmlentities($aRightsLvl[0][$x]) . " - " . htmlentities($aRightsLvl[1][$x]) . "</option>";
											}
										}
										?>
									</select>
								</td>
							</tr>
							<tr><td colspan=7 bgcolor='<?=$sBGColor?>'><img src='../../Images/Blank.gif' width=3 height=7></td></tr>
							<?php
						}
						?>
					</table>
					<br>
					<font size=-2>
					<sup>*</sup>If the variable is set as "Required", it will always be visible.<br>
					Deleting ADVs will leave the actual user data. This only removed the variable. 
					If you recreate the variable, all the user data will still be there.
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
				<td colspan=8 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If (ACCNT_ReturnRights("PHPJK_MADV_ADD")) {
					Echo "<td bgcolor=FFFFFF width=1><a href='New.php'><img src='../../Images/Administrative/NewADV.gif' Width=22 Height=39 Border=0 Alt='Create a new ADV.'></a></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If (ACCNT_ReturnRights("PHPJK_MADV_EDIT")) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/UpdateADV.gif' ALIGN='absmiddle' Width=31 Height=44 Border=0 Alt='Update ADV changes you''ve made.' onClick='SubmitForm(\"EditADV\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If (ACCNT_ReturnRights("PHPJK_MADV_DELETE")) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='delete' SRC='../../Images/Administrative/DeleteADV.gif' ALIGN='absmiddle' Width=27 Height=39 Border=0 Alt='Delete selected ADVs.' onClick='SubmitForm(\"DeleteADV\")'></td>";
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
				<td colspan=8 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=10 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
?>
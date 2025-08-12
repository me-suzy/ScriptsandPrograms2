<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iGalleryUnq	= Trim(Request("iGalleryUnq"));
	$iDBLoc			= Trim(Request("iDBLoc"));
	WriteScripts();
	
	If ((ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL")) || (ACCNT_ReturnRights("PHPJK_IG_ADD_CF_IMAGE")) || (ACCNT_ReturnRights("PHPJK_IG_EDIT_CF_DATA_IMAGE")) || (ACCNT_ReturnRights("PHPJK_IG_EDIT_CF_IMAGE")) || (ACCNT_ReturnRights("PHPJK_IG_DEL_CF_IMAGE")) ){
		HeaderHTML();
		Main();
	}Else{
		DOMAIN_Message("Please log in with Image Gallery management rights.", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_CLOSE.php");
	
	

	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iGalleryUnq;
		Global $iLoginAccountUnq;
		Global $iImageUnq;
		Global $sName;
		Global $sDescription;
		Global $sLongText;
		Global $bIsHidden;
		
		$sAction		= Trim(Request("sAction"));
		$iImageUnq		= Trim(Request("iImageUnq"));
		$sError			= "";
		$sSuccess		= "";
		
		If ( $iImageUnq != "" )
		{
			If ( $sAction == "AddCustom" ){
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{
					$sName			= Trim(Request("sName"));
					$sDescription	= Trim(Request("sDescription"));
					$sLongText		= Trim(Request("sLongText"));
					$bIsHidden		= Trim(Request("bIsHidden"));
					If ( $sName == "" ) {
						$sError = "Please enter a display name for this custom field.";
					}Else{
						If ( strlen($sLongText) >= 250 ) {
							$sDataType = "T";	// text > 250
						}Else{
							$sDataType = "V";	// varchar < 250
						}
						DB_Insert ("INSERT INTO IGMap (DataType,Name,Description,DomainUnq,ImageUnq,GalleryUnq,CategoryUnq) VALUES ('" . $sDataType . "', '" . SQLEncode($sName) . "', '" . SQLEncode($sDescription) . "', 1, " . $iImageUnq . ", 0, 0)");
						$sQuery			= "SELECT @@IDENTITY";
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							$sShortText	= "";
							If ( strlen($sLongText) < 250 ) {
								$sShortText	= $sLongText;
								$sLongText	= "";
							}
							DB_Insert ("INSERT INTO IGData VALUES (" . $rsRow[0] . ", " . $iImageUnq . ", 0, 0, '" . SQLEncode($sShortText) . "', '" . SQLEncode($sLongText) . "', 0, '" . $bIsHidden . "')");
							$sSuccess = "Custom field added to image successfully.";
						}Else{
							$sError = "Custom field added successfully, but there was a problem adding the data.";
						}
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot add custom fields to images within it.<br>";
				}
			}ElseIf ( $sAction == "UpdateCustom" ){
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{
					ForEach ($_POST as $sTextField=>$sValue)
					{
						If ( strpos($sTextField, "sOldName") !== false )
						{
							$iMapUnq			= str_replace("sOldName", "", $sTextField);
							$sOldName			= $sValue;
							$sNewName			= Request("sNewName" . $iMapUnq);
							$sOldDescription	= Request("sOldDescription" . $iMapUnq);
							$sNewDescription	= Request("sNewDescription" . $iMapUnq);
							$sOldLongText		= Request("sOldLongText" . $iMapUnq);
							$sNewLongText		= Request("sNewLongText" . $iMapUnq);
							$bOldIsHidden		= Request("bOldIsHidden" . $iMapUnq);
							$bNewIsHidden		= Request("bNewIsHidden" . $iMapUnq);
							
							If ( ( $sOldName != $sNewName ) || ( $sOldDescription != $sNewDescription ) )
							{
								DB_Update ("UPDATE IGMap SET Name = '" . SQLEncode($sNewName) . "', Description = '" . SQLEncode($sNewDescription) . "' WHERE MapUnq = " . $iMapUnq);
								If ( $sSuccess == "" )
									$sSuccess = "Successfully updated custom field information.";
							}
							If ( ( $sOldLongText != $sNewLongText ) || ( $bOldIsHidden != $bNewIsHidden ) )
							{
								If ( strlen($sNewLongText) < 250 ) {
									DB_Update ("UPDATE IGMap SET DataType = 'V' WHERE MapUnq = " . $iMapUnq);
								}Else{
									DB_Update ("UPDATE IGMap SET DataType = 'T' WHERE MapUnq = " . $iMapUnq);
								}
								$sNewShortText	= "";
								If ( strlen($sNewLongText) < 250 ) {
									$sNewShortText	= $sNewLongText;
									$sNewLongText	= "";
								}
								DB_Update ("UPDATE IGData SET Hidden = '" . $bNewIsHidden . "', VarCharData = '" . SQLEncode($sNewShortText) . "', TextData = '" . SQLEncode($sNewLongText) . "' WHERE MapUnq = " . $iMapUnq);
								If ( $sSuccess == "" )
									$sSuccess = "Successfully updated custom field information.";
							}
						}ElseIf ( strpos($sTextField, "sDelete") !== false )
						{
							$iMapUnq = str_replace("sDelete", "", $sTextField);
							DB_Update ("DELETE FROM IGMap WHERE MapUnq = " . $iMapUnq);
							DB_Update ("DELETE FROM IGData WHERE MapUnq = " . $iMapUnq);
							If ( $sSuccess == "" )
								$sSuccess = "Successfully deleted the custom field.";
						}
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot manage custom image data from images within it.<br>";
				}
			}

			If ( $sError != "" )
				DOMAIN_Message($sError, "ERROR");
			If ( $sSuccess != "" )
				DOMAIN_Message($sSuccess, "SUCCESS");
			
			WriteForm();
		}Else{
			DOMAIN_Message("Missing iImageUnq. Unable to edit the custom data for the image.", "ERROR");
		}
	}
	//************************************************************************************



	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iImageUnq;
		Global $iGalleryUnq;
		Global $aVariables;
		Global $aValues;

		$sBGColor	= $GLOBALS["BGColor1"];
		$sTextColor	= $GLOBALS["TextColor1"];
		
		If ( ( ACCNT_ReturnRights("PHPJK_IG_DEL_CF_IMAGE") ) || ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
			$bDelCustom = TRUE;
		}Else{
			$bDelCustom = FALSE;
		}
		If ( ( ACCNT_ReturnRights("PHPJK_IG_EDIT_CF_DATA_IMAGE") ) || ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
			$bEditData = TRUE;
		}Else{
			$bEditData = FALSE;
		}
		If ( ( ACCNT_ReturnRights("PHPJK_IG_EDIT_CF_IMAGE") ) || ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
			$bEditCustFields = TRUE;
		}Else{
			$bEditCustFields = FALSE;
		}

		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Manage Image Custom Data</b></font>
					<br>
					<b>Manage custom data for image:</b> <?=ReturnImageName($iImageUnq);?>
					<br><br>
					<?php If ( ACCNT_ReturnRights("PHPJK_IG_ADD_CF_IMAGE") ) {?>
					<form name='AddCustom' action='EditCustom.php' method='post'>
					<b>Add a new custom field:</b>
					<?php 
					$aVariables[0] = "sAction";
					$aVariables[1] = "iTtlNumItems";
					$aVariables[2] = "iDBLoc";
					$aVariables[3] = "iImageUnq";
					$aVariables[4] = "iGalleryUnq";
					$aValues[0] = "AddCustom";
					$aValues[1] = $iTtlNumItems;
					$aValues[2] = $iDBLoc;
					$aValues[3] = $iImageUnq;
					$aValues[4] = $iGalleryUnq;
					Echo DOMAIN_Link("P")
					?>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
					<table cellpadding = 5 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor = <?=$sBGColor?>><font color=<?=$sTextColor?>>Name (shown on the image display page):</td>
							<td bgcolor = <?=$sBGColor?>><input type='text' name='sName' value='' maxlength=250></td>
							<td bgcolor = <?=$sBGColor?>><font color=<?=$sTextColor?>>Description (for your use only):</td>
							<td bgcolor = <?=$sBGColor?>><input type='text' name='sDescription' value='' maxlength=250></td>
						</tr>
						<tr>
							<td colspan=4 bgcolor = <?=$sBGColor?>>
								<table width=100%>
									<tr>
										<td colspan=2>
											<font color=<?=$sTextColor?>>Enter a value:
										</td>
									</tr>
									<tr>
										<td valign=top><font color=<?=$sTextColor?>>Text:<br><textarea cols=30 rows=3 WRAP="soft" NAME="sLongText"></textarea></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan=4 bgcolor = <?=$sBGColor?>>
								<font color=<?=$sTextColor?>>
								Hide this data from the public? If you hide this, it will not appear on the image display page and only administrators and gallery owners will be able to see it here.
								<table>
									<tr>
										<td>Yes</td>
										<td><input type='radio' name='bIsHidden' value='Y'></tD>
										<td>No</td>
										<td><input type='radio' name='bIsHidden' value='N' checked></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan=4 align=center bgcolor = <?=$sBGColor?>><input type='submit' value='Create Custom Field'></td>
						</tr>
					</table>
					</td></tr></table>
					</form>
					<?php }?>
					
					<form name='ManageCustom' action='EditCustom.php' method='post'>
					<?php 
					$aValues[0] = "UpdateCustom";
					Echo DOMAIN_Link("P");
					$sTextColor	= $GLOBALS["TextColor2"];
					$sBGColor	= $GLOBALS["BGColor2"];
					?>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor?>'><b>Name</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor?>'><b>Description</b></td>
							<?php If ( $bDelCustom ) {?>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$sTextColor?>'><b>Delete</b></td>
							<?php }?>
						</tr>
						<?php 
						$sColor1 = $GLOBALS["BGColor1"];
						$sColor2 = $GLOBALS["PageBGColor"];
						
						$sQuery			= "SELECT M.MapUnq, M.Name, M.Description, D.VarCharData, D.Hidden, D.TextData FROM IGMap M (NOLOCK), IGData D (NOLOCK) WHERE D.ImageUnq = " . $iImageUnq . " AND M.MapUnq = D.MapUnq";
						$rsRecordSet	= DB_Query($sQuery);
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor == $sColor1 ) {
								$sBGColor = $sColor2;
							}Else{
								$sBGColor = $sColor1;
							}

							$iMapUnq		= $rsRow["MapUnq"];
							$sName			= $rsRow["Name"];
							$sDescription	= $rsRow["Description"];
							$sShortText		= Trim($rsRow["VarCharData"]);
							$bIsHidden		= strtoupper(Trim($rsRow["Hidden"]));
							$sLongText		= Trim($rsRow["TextData"]);
							If ( $sLongText == "" )
								$sLongText = $sShortText;
							?>
							<tr>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<?php If ( $bEditCustFields ) {?>
									<input type='hidden' name='sOldName<?=$iMapUnq?>' value="<?=htmlentities($sName)?>">
									<input type='text' name='sNewName<?=$iMapUnq?>' value="<?=htmlentities($sName)?>" size=30 maxlength=250>
									<?php }?>
								</td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<?php If ( $bEditCustFields ) {?>
									<input type='hidden' name='sOldDescription<?=$iMapUnq?>' value="<?=htmlentities($sDescription)?>">
									<input type='text' name='sNewDescription<?=$iMapUnq?>' value="<?=htmlentities($sDescription)?>" size=30 maxlength=250>
									<?php }?>
								</td>
								<?php If ( $bDelCustom ) {?>
								<td align=center bgcolor=<?=$sBGColor?> valign=top><input type='checkbox' name="sDelete<?=$iMapUnq?>" value="<?=$iMapUnq?>"></td>
								<?php }?>
							</tr>
							<?php If ( $bEditData ) {?>
							<tr>
								<td bgcolor=<?=$sBGColor?> valign=top>

								</td>
								<?php If ( $bDelCustom ) {?>
								<td bgcolor=<?=$sBGColor?> valign=top colspan=2>
								<?php }Else{?>
								<td bgcolor=<?=$sBGColor?> valign=top>
								<?php }?>
									<input type='hidden' name='sOldLongText<?=$iMapUnq?>' value="<?=htmlentities($sLongText)?>">
									<textarea cols=30 rows=3 WRAP="soft" NAME="sNewLongText<?=$iMapUnq?>"><?=htmlentities($sLongText)?></textarea>
								</td>
							</tr>
							<?php }?>
							<tr>
								<?php If ( $bDelCustom ) {?>
								<td bgcolor=<?=$sBGColor?> valign=top colspan=3>
								<?php }Else{?>
								<td bgcolor=<?=$sBGColor?> valign=top colspan=2>
								<?php }?>
									<input type='hidden' name='bOldIsHidden<?=$iMapUnq?>' value="<?=htmlentities($bIsHidden)?>">
									Hide this custom data?
									<table>
										<tr>
											<td>Yes</td>
											<td><input type='radio' name='bNewIsHidden<?=$iMapUnq?>' value='Y' <?php If ( $bIsHidden == "Y" )  Echo "checked";?>></tD>
											<td>No</td>
											<td><input type='radio' name='bNewIsHidden<?=$iMapUnq?>' value='N' <?php If ( $bIsHidden != "Y" )  Echo "checked";?>></td>
										</tr>
									</table>
								</td>
							</tr>
							<?php 
						}
						?>
					</table>
					<center>
					<input type='submit' value='Update/Delete Custom Fields'>
					</form>
				</td>
			</tr>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		<?php 
	}
	//************************************************************************************
	
	

	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function ReturnImageName($iImageUnq)
	{
		$sQuery			= "SELECT Image FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow[0];
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This writes the JavaScript out even if the Main() Function isn't called in case the	*
	//*		admin has creation rights, but not edit rights.								*
	//*																					*
	//************************************************************************************
	Function WriteScripts()
	{
		Global $iGalleryUnq;
		Global $iTtlNumItems;
		Global $iDBLoc;
		?>
		<SCRIPT LANGUAGE=javascript>
		<!--
		
			function ReturnToMain(){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$iGalleryUnq?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
			}
			
		-->
		</SCRIPT>
		<?php 
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
				<td colspan=4 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:ReturnToMain();'><img src='../../Images/Administrative/Return.gif' Width=30 Height=38 Border=0 Alt=''></a></td>";
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
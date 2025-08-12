<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");

	$sName				= "";
	$sDescription		= "";
	$sVisibility		= "";
	$iCategoryUnq		= Trim(Request("iCategoryUnq"));
	$sPopupWindow		= "";
	$sCreateThread		= "";
	$iGalleriesLeft		= 0;
	$iOldCategoryUnq	= "";
	$sOldCreateThread	= "";
	$iGalleryUnq		= "";
	
	WriteScripts();
	
	If ( ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) || ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
		HeaderHTML();
		Main();
	}Else{
		DOMAIN_Message("Please log in with Image Gallery management rights.", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*	This lets you edit galleries.													*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iLoginAccountUnq;
		Global $sName;
		Global $sDescription;
		Global $sVisibility;
		Global $iCategoryUnq;
		Global $sPopupWindow;
		Global $iOldCategoryUnq;
		Global $sOldCreateThread;
		Global $sCreateThread;
		Global $iDBLoc;
		Global $iGalleriesLeft;
		Global $iGalleryUnq;
		Global $sUseDB;
		
		$sError			= "";
		$iGalleryUnq	= Trim(Request("iGalleryUnq"));
		
		If ( $iGalleryUnq != "" ) {
			If ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				If ( Trim(Request("sAction")) == "EditGallery" ) {
					$sName				= Trim(Request("sName"));
					$sDescription		= Trim(Request("HTMLDATA"));
					$sVisibility		= Trim(Request("sVisibility"));
					$iCategoryUnq		= Trim(Request("iCategoryUnq"));
					$sPopupWindow		= Trim(Request("sPopupWindow"));
					$iOldCategoryUnq	= Trim(Request("iOldCategoryUnq"));
					$sCreateThread		= Trim(Request("sCreateThread"));
					$sOldCreateThread	= Trim(Request("sOldCreateThread"));
		
					If ( $sName == "" ) {
						DOMAIN_Message("Please enter a name for your new gallery.<br>", "ERROR");
					}Else{
						// update the gallery
						DB_Update ("UPDATE Galleries SET Name = '" . SQLEncode($sName) . "', Description = '" . SQLEncode($sDescription) . "', Visibility = '" . SQLEncode($sVisibility) . "', PopupWindow = '" . $sPopupWindow . "' WHERE GalleryUnq = " . $iGalleryUnq);
						
						
						If ( $iCategoryUnq != $iOldCategoryUnq ) {
							// Decrement (by 1) all Galleries AFTER the one we are moving.
							If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
								$sQuery	= "SELECT GalleryUnq, Position FROM Galleries (NOLOCK) WHERE Position > (SELECT Position FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq . ") AND CategoryUnq = " . $iOldCategoryUnq . " ORDER BY Position";
							}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
								$sQuery	= "SELECT Position FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
								$rsRecordSet	= DB_Query($sQuery);
								If ( $rsRow = DB_Fetch($rsRecordSet) ){
									$sQuery	= "SELECT GalleryUnq, Position FROM Galleries (NOLOCK) WHERE Position > " . $rsRow["Position"] . " AND CategoryUnq = " . $iOldCategoryUnq . " ORDER BY Position";
								}Else{
									$sQuery	= "SELECT GalleryUnq, Position FROM Galleries (NOLOCK) WHERE Position > 0 AND CategoryUnq = " . $iOldCategoryUnq . " ORDER BY Position";
								}
							}
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
								DB_Update ("UPDATE Galleries SET Position = " . ($rsRow["Position"] - 1) . " WHERE GalleryUnq = " . $rsRow["GalleryUnq"]);
							
							/* get a new position number for the gallery in the category it's going to
								must do this before moving it or it might skew the results*/
							If ( $GLOBALS["sUseDB"] == "MSSQL" ){
								$sQuery = "SELECT ISNULL(MAX(Position), 0) + 1 FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq;
								$rsRecordSet	= DB_Query($sQuery);
								If ( $rsRow = DB_Fetch($rsRecordSet) ){
									DB_Update ("UPDATE Galleries SET Position = " . $rsRow[0] . " WHERE GalleryUnq = " . $iGalleryUnq);
								}Else{
									DB_Update ("UPDATE Galleries SET Position = 1 WHERE GalleryUnq = " . $iGalleryUnq);
								}
							}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ){
								$sQuery = "SELECT MAX(Position) FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq;
								$rsRecordSet	= DB_Query($sQuery);
								If ( $rsRow = DB_Fetch($rsRecordSet) ){
									DB_Update ("UPDATE Galleries SET Position = " . ($rsRow[0]+1) . " WHERE GalleryUnq = " . $iGalleryUnq);
								}Else{
									DB_Update ("UPDATE Galleries SET Position = 1 WHERE GalleryUnq = " . $iGalleryUnq);
								}
							}							
							DB_Update ("UPDATE Galleries SET CategoryUnq = '" . $iCategoryUnq . "' WHERE GalleryUnq = " . $iGalleryUnq);
							$iOldCategoryUnq = $iCategoryUnq;
						}
						DOMAIN_Message("Gallery modified successfully.", "SUCCESS");
					}
				}Else{
					// get the gallery information from the database
					$sQuery = "SELECT * FROM Galleries WHERE GalleryUnq = " . $iGalleryUnq;
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) ){
						$sName				= Trim($rsRow["Name"]);
						$sDescription		= Trim($rsRow["Description"]);
						$sVisibility		= Trim($rsRow["Visibility"]);
						$iCategoryUnq		= Trim($rsRow["CategoryUnq"]);
						$sPopupWindow		= Trim($rsRow["PopupWindow"]);
						$iOldCategoryUnq	= Trim($rsRow["CategoryUnq"]);
					}Else{
						DOMAIN_Message("Unable to find the gallery in the database.", "ERROR");
					}
				}
				WriteEditForm();
			}Else{
				DOMAIN_Message("Invalid Login. If you think this is an error, please contact support.", "ERROR");
			}
		}Else{
			DOMAIN_Message("Unable to find the gallery in the database.", "ERROR");
		}
	}
	//************************************************************************************
	
	

	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteEditForm()
	{
		Global $aVariables;
		Global $aValues;
		Global $PUBLIC_GALLERIES;
		Global $PRIVATE_GALLERIES;
		Global $iLoginAccountUnq;
		Global $sName;
		Global $sDescription;
		Global $sVisibility;
		Global $iCategoryUnq;
		Global $sPopupWindow;
		Global $sCreateThread;
		Global $iDBLoc;
		Global $iGalleriesLeft;
		Global $iFormWidth;
		Global $iFormColumns;
		Global $iGalleryUnq;
		?>
		<form action = "Edit.php" method = "post" name="EditGallery">
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "iGalleryUnq";
		$aVariables[2] = "iDBLoc";
		$aValues[0] = "EditGallery";
		$aValues[1] = $iGalleryUnq;
		$aValues[2] = Trim(Request("iDBLoc"));
		Echo DOMAIN_Link("P");
		DOMAIN_Link_Clear();
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Edit Gallery Attributes</b></font>
				</td>
			</tr>
			<tr>
				<td>
					<table width=100% cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor = <?=$GLOBALS["BorderColor1"]?> align=center>
					<table cellpadding=5 width=100% cellspacing = 0 border = 0>
						<tr>
							<td colspan=2 bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<center><input type = "submit" value = "Update Gallery">
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<b><font color='<?=$GLOBALS["TextColor1"]?>'>Gallery Name:
							</td>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<input type = "Text" name = "sName" value = "<?=htmlentities($sName)?>" maxlength=32 size=<?=$iFormWidth?>>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<b><font color='<?=$GLOBALS["TextColor1"]?>'>Popup Window:
							</td>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<select name = "sPopupWindow">
									<option value = 'FULL' <?php If ($sPopupWindow == "FULL") Echo "selected";?>>Full Screen</option>
									<option value = 'WINDOW' <?php If ($sPopupWindow == "WINDOW") Echo "selected";?>>Popup Window</option>
								</select>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<b><font color='<?=$GLOBALS["TextColor1"]?>'>Visibility?
							</td>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<select name = "sVisibility">
									<option value = '<?=$PUBLIC_GALLERIES?>' <?php If ($sVisibility == $PUBLIC_GALLERIES) Echo "selected";?>>Public</option>
									<option value = '<?=$PRIVATE_GALLERIES?>' <?php If ($sVisibility == $PRIVATE_GALLERIES) Echo "selected";?>>Private</option>
								</select>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<b><font color='<?=$GLOBALS["TextColor1"]?>'>Category:
							</td>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<input type='hidden' name='iOldCategoryUnq' value='<?=$iCategoryUnq?>'>
								<select name = "iCategoryUnq">
									<?php 
									$iCatLevel = 0;
									CategoryUnqDropDown($iCategoryUnq, 0);
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?> valign = top colspan=2>
								<b><font color='<?=$GLOBALS["TextColor1"]?>'>Description:<br>
								<TEXTAREA COLS=<?=$iFormColumns?> ROWS=5 WRAP="soft" NAME="HTMLDATA"><?=htmlentities($sDescription)?></TEXTAREA>
							</td>
						</tr>
						
					</table>
					</td></tr></table>
					<br>
					<center><input type = "submit" value = "Update Gallery">
				</td>
			</tr>
		</table>
		</form>
		<?php 
	}
	//************************************************************************************
	

	
	//************************************************************************************
	//*																					*
	//*	This recursively displays the categories in a drop-down list.					*
	//*																					*
	//************************************************************************************
	Function CategoryUnqDropDown($iCategoryUnq, $iCurCatUnq)
	{
		Static $iCatLevel = 0;
		
		$sQuery 		= "SELECT * FROM IGCategories (NOLOCK) WHERE Parent = " . $iCurCatUnq . " ORDER BY Position";
		$rsRecordSet	= DB_Query($sQuery);
		If ( DB_NumRows($rsRecordSet) > 0 )
		{
			If ( ACCNT_ReturnRights("PHPJK_IG_ADD_TO_NO_CAT") ) {
				// only display this if they are allowed to add galleries to "no category"
				If ( ( $iCatLevel == 0 ) && ( $iCurCatUnq == 0 ) )
					Echo "<option value='0'>Galleries not in a category</option>";
			}
			While ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$iCurCatUnq = $rsRow["CategoryUnq"];
				If ( ( ACCNT_ReturnRights($rsRow["RightsLvl"]) ) || ( Trim($rsRow["RightsLvl"]) == "" ) ) {
					If ( $iCategoryUnq == $iCurCatUnq ) {
						Echo "<option value='" . $iCurCatUnq . "' Selected>" . str_repeat(CHR(151), $iCatLevel) . $rsRow["Name"] . "</option>";
					}Else{
						Echo "<option value='" . $iCurCatUnq . "'>" . str_repeat(CHR(151), $iCatLevel) . $rsRow["Name"] . "</option>";
					}
				}
				$iCatLevel++;
				CategoryUnqDropDown($iCategoryUnq, $iCurCatUnq);
				$iCatLevel--;
			}
		}Else{
			If ( ACCNT_ReturnRights("PHPJK_IG_ADD_TO_NO_CAT") ) {
				// only display this if they are allowed to add galleries to "no category"
				If ( ( $iCatLevel == 0 ) && ( $iCurCatUnq == 0 ) )
					Echo "<option value='0'>Galleries not in a category</option>";
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This writes the JavaScript out even if the Main() Sub isn't called in case the	*
	//*		admin has creation rights, but not edit rights.								*
	//*																					*
	//************************************************************************************
	Function WriteScripts()
	{
		Global $iCategoryUnq;
		Global $iDBLoc;
		Global $aVariables;
		Global $aValues;
		 ?>
		<SCRIPT LANGUAGE=javascript>
		<!--
		
		function ReturnToMain(){
			document.location = "index.php?<?=DOMAIN_Link("G")?>&iCategoryUnq=<?=$iCategoryUnq ?>&iDBLoc=<?=$iDBLoc ?>";
		}
		
		//-->
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
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:ReturnToMain();'><img src='../../Images/Administrative/Return.gif' Width=30 Height=38 Border=0 Alt='Return to the main gallery management screen.'></a></td>";
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
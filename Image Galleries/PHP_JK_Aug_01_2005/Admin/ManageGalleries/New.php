<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$sName			= "";
	$sDescription	= "";
	$sVisibility	= "";
	$iCategoryUnq	= Trim(Request("iCategoryUnq"));
	$sPopupWindow	= "";
	$sCreateThread	= "";
	$iGalleriesLeft	= 0;
	
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
	//*	This lets you add a new Gallery - calls WriteAddForm().							*
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
		Global $sCreateThread;
		Global $iDBLoc;
		Global $iGalleriesLeft;
		
		$sError		= "";
		$sAction	= Trim(Request("sAction"));

		If ( $sAction == "AddGallery" ) {
			$sName			= Trim(Request("sName"));
			$sDescription	= Trim(Request("HTMLDATA"));
			$sVisibility	= Trim(Request("sVisibility"));
			$iCategoryUnq	= Trim(Request("iCategoryUnq"));
			$sPopupWindow	= Trim(Request("sPopupWindow"));
			$sCreateThread	= Trim(Request("sCreateThread"));

			If ( $sName == "" ) {
				DOMAIN_Message("Please enter a name for your new gallery.<br>", "ERROR");
			}Else{
				If ( $iCategoryUnq != "" )
				{
					// Make their new gallery
					// get the next position for this category - need to check domain too cuz categoryunq = 0 a lot (ie. not in category)
					If ( $GLOBALS["sUseDB"] == "MSSQL" ){
						$sQuery = "SELECT ISNULL(MAX(Position), 0) + 1 FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq;
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) ){
							$iPosition = $rsRow[0];
						}Else{
							$iPosition = 1;
						}
					}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ){
						$sQuery = "SELECT MAX(Position) FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq;
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) ){
							$iPosition = $rsRow[0]+1;
						}Else{
							$iPosition = 1;
						}
					}
	
					DB_Insert ("INSERT INTO Galleries (AccountUnq,Domain,Name,Description,Visibility,CategoryUnq,PopupWindow,ConfUnq,ThreadUnq,Position,UserName) VALUES (" . $iLoginAccountUnq . ", 1, '" . SQLEncode($sName) . "', '" . SQLEncode($sDescription) . "', '" . $sVisibility . "', " . $iCategoryUnq . ", '" . $sPopupWindow . "', 0, 0, " . $iPosition . ", '" . Trim(ACCNT_UserName($iLoginAccountUnq)) . "')");
					// set the gallery images visible to everyone
					$sQuery			= "SELECT @@IDENTITY";
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) )
						DB_Insert ("INSERT INTO PrivateAccounts VALUES (" . $rsRow[0] . ", -1)");
					
					DOMAIN_Message("Gallery created successfully.", "SUCCESS");
				}Else{
					DOMAIN_Message("Galleries must be created within a category. If there are no categories available, then no gallery can be created. Please contact the administrator for more information.<br>", "ERROR");
				}
			}
		}
		
		$iGalleriesLeft = G_ADMINISTRATION_GalleriesLeft($iLoginAccountUnq);
		If ( ( $iGalleriesLeft > 0 ) || ( $iGalleriesLeft == -1 ) ) {
			WriteAddForm();
		}Else{
			DOMAIN_Message("Sorry but you have reached your limit for creating galleries on this domain. You may not create any more galleries until you delete an existing gallery.", "ERROR");
		}
	}
	//************************************************************************************
	

	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteAddForm()
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
		
		 ?>
		<br><center>
		<form action = "New.php" method = "post" name="AddGallery">
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "iDBLoc";
		$aValues[0] = "AddGallery";
		$aValues[1] = Trim(Request("iDBLoc"));
		Echo DOMAIN_Link("P");
		DOMAIN_Link_Clear();
		 ?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Add a new Image Gallery to your account.</b></font>
					<br>
					<?php 
					If ( $iGalleriesLeft != -1 ) {
						Echo "You may create <b>" . $iGalleriesLeft . "</b> more galleries on this domain.";
					}Else{
						Echo "You may create as many galleries as you want on this domain.";
					}
					 ?>
				</td>
			</tr>
			<tr>
				<td>
					<table width=100% cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor = <?=$GLOBALS["BorderColor1"]?> align=center>
					<table cellpadding=5 width=100% cellspacing = 0 border = 0>
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
									<option value = 'FULL' <?php If ($sPopupWindow == "FULL")  Echo "selected"; ?>>Full Screen</option>
									<option value = 'WINDOW' <?php If ($sPopupWindow == "WINDOW")  Echo "selected"; ?>>Popup Window</option>
								</select>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<b><font color='<?=$GLOBALS["TextColor1"]?>'>Visibility?
							</td>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<select name = "sVisibility">
									<option value = '<?=$PUBLIC_GALLERIES ?>' <?php If ($sVisibility == $PUBLIC_GALLERIES)  Echo "selected"; ?>>Public</option>
									<option value = '<?=$PRIVATE_GALLERIES ?>' <?php If ($sVisibility == $PRIVATE_GALLERIES) Echo "selected"; ?>>Private</option>
								</select>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<b><font color='<?=$GLOBALS["TextColor1"]?>'>Category:
							</td>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>>
								<select name = "iCategoryUnq">
									<?php 
									If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") )
									{
										$iCatLevel = 0;
										CategoryUnqDropDown($iCategoryUnq, 0);
									}Else{
										// just list the ones they can add to -- this is because if a subcategory allows them to add,
										//	but the parent doesn't, then CategoryUnqDropDown will stop at the parent and go no further.
										//	Also, if we made a recursive function that did detect if subcategories were allowed, how
										//	would we stop a user from choosing from the drop-down list a parent cat if the drop-down
										//	display was the same as CategoryUnqDropDown()? Maybe w/ some crazy JavaScript, but it's
										//	not worth it.
										LimitedCatDropDown($iCategoryUnq);
									}
									 ?>
									 
								</select>
							</td>
						</tr>
						<tr>
							<td colspan=2 bgcolor = <?=$GLOBALS["BGColor1"]?> valign = top>
								<b><font color='<?=$GLOBALS["TextColor1"]?>'>Description:<br>
								<center>
								<TEXTAREA COLS=<?=$iFormColumns ?> ROWS=5 WRAP="soft" NAME="HTMLDATA"><?=htmlentities($sDescription)?></TEXTAREA>
							</td>
						</tr>
					</table>
					</td></tr></table>
					<br>
					<center><input type = "submit" value = "Add this Gallery">
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
	//*																					*
	//************************************************************************************
	Function LimitedCatDropDown($iCurCatUnq)
	{
		Global $sCatBreadcrumbs;
		
		$sQuery			= "SELECT * FROM IGCategories (NOLOCK) ORDER BY Position";
		$rsRecordSet	= DB_Query($sQuery);
		While ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			If ( ( ACCNT_ReturnRights($rsRow["RightsLvl"]) === True ) || ( $rsRow["RightsLvl"] == "" ) )
			{
				$iCategoryUnq = $rsRow["CategoryUnq"];
				$sCatBreadcrumbs = "";
				If ( $iCategoryUnq == $iCurCatUnq ) {
					Echo "<option value='" . $iCategoryUnq . "' Selected>" . CatBreadcrumbs($rsRow["Parent"]) . "-&gt;" . $rsRow["Name"] . "</option>\n";
				}Else{
					Echo "<option value='" . $iCategoryUnq . "'>" . CatBreadcrumbs($rsRow["Parent"]) . "-&gt;" . $rsRow["Name"] . "</option>\n";
				}
				CatBreadcrumbs($rsRow["CategoryUnq"]);
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function CatBreadcrumbs($iParentUnq)
	{
		Global $sCatBreadcrumbs;
		
		If ( $iParentUnq == "" ) {
			$iParentUnq = 0;
		}Else{
			If ( ! is_numeric($iParentUnq) )
				$iParentUnq = 0;
		}

		// Now get the actual data for the category
		$sQuery			= "SELECT Name, Parent FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iParentUnq;
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			if ( $sCatBreadcrumbs == "" ) {
				$sCatBreadcrumbs = $rsRow["Name"];
			}Else{
				$sCatBreadcrumbs = $rsRow["Name"] . "-&gt;" . $sCatBreadcrumbs;
			}
			CatBreadcrumbs($rsRow["Parent"]);
		}
		Return $sCatBreadcrumbs;
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
		
		$sQuery			= "SELECT * FROM IGCategories (NOLOCK) WHERE Parent = " . $iCurCatUnq . " ORDER BY Position";
		$rsRecordSet	= DB_Query($sQuery);
		if ( DB_NumRows($rsRecordSet) > 0 )
		{
			If ( ACCNT_ReturnRights("PHPJK_IG_ADD_TO_NO_CAT") ) {
				// only display this if they are allowed to add galleries to "no category"
				If ( ( $iCatLevel == 0 ) && ( $iCurCatUnq == 0 ) )
					Echo "<option value='0'>Galleries not in a category</option>\n";
			}
			While ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$iCurCatUnq = $rsRow["CategoryUnq"];
				If ( ( ACCNT_ReturnRights($rsRow["RightsLvl"]) ) || ( Trim($rsRow["RightsLvl"]) == "" ) ) {
					// make sure this user has rights to add to this category
					If ( $iCategoryUnq == $iCurCatUnq ) {
						Echo "<option value='" . $iCurCatUnq . "' Selected>" . str_repeat(CHR(151), $iCatLevel) . $rsRow["Name"] . "</option>\n";
					}Else{
						Echo "<option value='" . $iCurCatUnq . "'>" . str_repeat(CHR(151), $iCatLevel) . $rsRow["Name"] . "</option>\n";
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
					Echo "<option value='0'>Galleries not in a category</option>\n";
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
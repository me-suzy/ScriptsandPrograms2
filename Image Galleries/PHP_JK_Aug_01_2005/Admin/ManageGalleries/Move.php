<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iCategoryUnq2	= "";
	$iDestCatUnq	= "";
	$iCategoryUnq	= Trim(Request("iCategoryUnq"));
	
	$iTemp = DOMAIN_Conf("IMAGEGALLERY_SCRIPTTIMEOUT");
	If ( $iTemp = "" )
		$iTemp = 5400;
	set_time_limit($iTemp);
	
	WriteScripts();
	
	If ( (ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL")) || (ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY")) ) {
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
		Global $iLoginAccountUnq;
		Global $iGalleryUnq;
		Global $iDBLoc;
		Global $iTtlNumItems;
		Global $iNumPerPage;
		Global $iCategoryUnq2;
		Global $iDestCatUnq;
		Global $iCategoryUnq;
		
		$sError			= "";
		$sSuccess		= "";
		$sAction		= Trim(Request("sAction"));
		$iCategoryUnq2	= Trim(Request("iCategoryUnq2"));
		$iDestCatUnq	= Trim(Request("iDestCatUnq"));
		
		If ( $iCategoryUnq == "" )
			$iCategoryUnq = "0";
		If ( $iCategoryUnq2 == "" )
			$iCategoryUnq2 = $iCategoryUnq;

		If ( $sAction == "Move" )
		{
			ForEach ($_POST as $sCheckbox=>$sValue)
			{
				If ( strpos($sCheckbox, "sMove") !== false )
				{
					$iGalleryUnq = str_replace("sMove", "", $sCheckbox);
					If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
					{
						If ( $iDestCatUnq != $iCategoryUnq2 ) {
							// Decrement (by 1) all Galleries AFTER the one we are moving.
							If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
								$sQuery	= "SELECT GalleryUnq, Position FROM Galleries (NOLOCK) WHERE Position > (SELECT Position FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq . ") AND CategoryUnq = " . $iCategoryUnq2 . " ORDER BY Position";
							}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
								$sQuery	= "SELECT Position FROM Galleries WHERE GalleryUnq = " . $iGalleryUnq;
								$rsRecordSet	= DB_Query($sQuery);
								If ( $rsRow = DB_Fetch($rsRecordSet) ){
									$sQuery	= "SELECT GalleryUnq, Position FROM Galleries (NOLOCK) WHERE Position > " . $rsRow[0] . " AND CategoryUnq = " . $iCategoryUnq2 . " ORDER BY Position";
								}Else{
									$sQuery	= "SELECT GalleryUnq, Position FROM Galleries (NOLOCK) WHERE Position > 1 AND CategoryUnq = " . $iCategoryUnq2 . " ORDER BY Position";
								}
							}
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
								DB_Update ("UPDATE Galleries SET Position = " . ($rsRow["Position"] - 1) . " WHERE GalleryUnq = " . $rsRow["GalleryUnq"]);
							
							/* get a new position number for the gallery in the category it's going to
								must do this before moving it or it might skew the results*/
							If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
								$sQuery	= "SELECT ISNULL(MAX(Position), 0) + 1 FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iDestCatUnq;
								$rsRecordSet	= DB_Query($sQuery);
								If ( $rsRow = DB_Fetch($rsRecordSet) ){
									DB_Update ("UPDATE Galleries SET Position = " . $rsRow[0] . " WHERE GalleryUnq = " . $iGalleryUnq);
								}Else{
									DB_Update ("UPDATE Galleries SET Position = 1 WHERE GalleryUnq = " . $iGalleryUnq);
								}
							}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
								$sQuery = "SELECT MAX(Position) sPos FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iDestCatUnq;
								$rsRecordSet	= DB_Query($sQuery);
								If ( $rsRow = DB_Fetch($rsRecordSet) ){
									DB_Update ("UPDATE Galleries SET Position = " . ($rsRow[0]+1) . " WHERE GalleryUnq = " . $iGalleryUnq);
								}Else{
									DB_Update ("UPDATE Galleries SET Position = 1 WHERE GalleryUnq = " . $iGalleryUnq);
								}
							}
						
							DB_Update ("UPDATE Galleries SET CategoryUnq = '" . $iDestCatUnq . "' WHERE GalleryUnq = " . $iGalleryUnq);
						}

						If ( $sSuccess == "" )
							$sSuccess = "Successfully moved the galleries.";
					}Else{
						If ( $sError == "" )
							$sError = "Sorry but you are not the owner of this gallery and are not allowed to move it.";
					}
				}
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
			
		If ( $sAction == "UpdateCategoryUnq" ) {
			$iDBLoc = 0;
			$iTtlNumItems = 0;
		}ElseIf ( $sAction == "Move" ) {
			$iDBLoc = 0;
			$iTtlNumItems = 0;
		}
		
		if ( $iTtlNumItems == 0 ) {
			If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				// get all galleries from all users
				$sQuery = "SELECT Count(*) FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq2;
			}Else{
				// just get galleries from the current user
				$sQuery = "SELECT Count(*) FROM Galleries (NOLOCK) WHERE AccountUnq = " . $iLoginAccountUnq . " AND CategoryUnq = " . $iCategoryUnq2;
			}
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
				$iTtlNumItems = $rsRow[0];
		}
		// Pagination variables -- end
		
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
		Global $iLoginAccountUnq;
		Global $iDBLoc;
		Global $iTtlNumItems;
		Global $iCategoryUnq;
		Global $iCategoryUnq2;
		Global $iNumPerPage;
		Global $iDestCatUnq;
		
		$sBGColor = $GLOBALS["BGColor2"];
		
		?>
		<form name='ManageGalleries' action='Move.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "iTtlNumItems";
		$aVariables[2] = "iDBLoc";
		$aVariables[3] = "iCategoryUnq";
		$aValues[0] = "Move";
		$aValues[1] = $iTtlNumItems;
		$aValues[2] = $iDBLoc;
		$aValues[3] = $iCategoryUnq;
		Echo DOMAIN_Link("P");
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Move Galleries</b></font>
					<br><br>
					<table width=671>
						<tr>
							<td>
								<font color='<?=$GLOBALS["PageText"]?>'><b>Source Category</b></font>
							</td>
							<td>
								<select name = "iCategoryUnq2" onChange='SubmitForm("UpdateCategoryUnq");'>
									<?php 
									$iCatLevel = 0;
									CategoryUnqDropDown($iCategoryUnq2, 0);
									?>
								</select>
							</td>
							<td>
								<font color='<?=$GLOBALS["PageText"]?>'>(change this to change the galleries listed below)
							</td>
						</tr>
						<tr>
							<td>
								<font color='<?=$GLOBALS["PageText"]?>'><b>Destination Category</b></font>
							</td>
							<td colspan=2>
								<select name = "iDestCatUnq">
									<?php 
									$iCatLevel = 0;
									CategoryUnqDropDown($iDestCatUnq, 0);
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan=3>
								<input type = "submit" value = "Move Galleries">
							</td>
						</tr>
					</table>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>ID</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Name</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Move</b></td>
						</tr>
						<?php 
						$sColor1 = $GLOBALS["BGColor1"];
						$sColor2 = $GLOBALS["PageBGColor"];
						$sColor3 = $GLOBALS["PageText"];
						$sColor4 = $GLOBALS["TextColor1"];
						$sBGColor = $GLOBALS["BGColor2"];
						
						If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
							// get all galleries from all users
							$sQuery = "SELECT * FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq2 . " ORDER BY Position";
						}Else{
							// just get galleries from the current user
							$sQuery = "SELECT * FROM Galleries (NOLOCK) WHERE AccountUnq = " . $iLoginAccountUnq . " AND CategoryUnq = " . $iCategoryUnq2 . " ORDER BY Position";
						}
						DB_Query("SET ROWCOUNT " . ($iDBLoc + $iNumPerPage));
						$rsRecordSet	= DB_Query($sQuery);
						DB_Query("SET ROWCOUNT 0");
						For ( $x = 1; $x < $iDBLoc; $x++ )
							DB_Fetch($rsRecordSet);

						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor == $sColor1 ) {
								$sBGColor = $sColor2;
								$sTextColor = $sColor3;
								$sLinkColor = "MediumNavPage";
							}Else{
								$sBGColor = $sColor1;
								$sTextColor = $sColor4;
								$sLinkColor = "MediumNav1";
							}
							$sName 			= $rsRow["Name"];
							$iGalleryUnq 	= $rsRow["GalleryUnq"];
							?>
							<tr>
								<td bgcolor=<?=$sBGColor?> align=center valign=top><font color='<?=$sTextColor?>'><?=$iGalleryUnq?></td>
								<td bgcolor=<?=$sBGColor?> valign=top width=10><input type='text' name="sNewName" value="<?=htmlentities($sName)?>" size=45 maxlength=250></td>
								<td align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name="sMove<?=$iGalleryUnq?>" value="<?=$iGalleryUnq?>"></td>
							</tr>
							<?php 
						}
						?>
						<tr>
							<td colspan=3 align=right>
								<?php PrintRecordsetNav_ADMIN( "Move.php", "", "Galleries" ); ?>
							</td>
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
	//*	This recursively displays the categories in a drop-down list.					*
	//*																					*
	//************************************************************************************
	Function CategoryUnqDropDown($iCategoryUnq2, $iCurCatUnq)
	{
		Static $iCatLevel = 0;
		
		$sQuery			= "SELECT * FROM IGCategories (NOLOCK) WHERE Parent = " . $iCurCatUnq . " ORDER BY Position";
		$rsRecordSet	= DB_Query($sQuery);
		If ( DB_NumRows($rsRecordSet) > 0 )
		{
			If ( ACCNT_ReturnRights("PHPJK_IG_ADD_TO_NO_CAT") ) {
				// only display this if they are allowed to add galleries to "no category"
				If ( ( $iCatLevel == 0 ) && ( $iCurCatUnq == 0 ) )
					Echo "<option value='0'>Galleries not in a category (" . NumGalleries(0) . ")</option>";
			}
			While ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$iCurCatUnq = $rsRow["CategoryUnq"];
				If ( ( ACCNT_ReturnRights($rsRow["RightsLvl"]) ) || ( Trim($rsRow["RightsLvl"]) == "" ) ) {
					If ( $iCategoryUnq2 == $iCurCatUnq ) {
						Echo "<option value='" . $iCurCatUnq . "' Selected>" . str_repeat(CHR(151), $iCatLevel) . $rsRow["Name"] . " (" . NumGalleries($iCurCatUnq) . ")</option>";
					}Else{
						Echo "<option value='" . $iCurCatUnq . "'>" . str_repeat(CHR(151), $iCatLevel) . $rsRow["Name"] . " (" . NumGalleries($iCurCatUnq) . ")</option>";
					}
				}
				$iCatLevel++;
				CategoryUnqDropDown($iCategoryUnq2, $iCurCatUnq);
				$iCatLevel--;
			}
		}Else{
			If ( ACCNT_ReturnRights("PHPJK_IG_ADD_TO_NO_CAT") ) {
				// only display this if they are allowed to add galleries to "no category"
				If ( ( $iCatLevel == 0 ) && ( $iCurCatUnq == 0 ) )
					Echo "<option value='0'>Galleries not in a category (" . NumGalleries(0) . ")</option>";
			}
		}
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the number of galleries in the category.								*
	//*																					*
	//************************************************************************************
	Function NumGalleries($iCategoryUnq2)
	{
		$sQuery			= "SELECT Count(*) FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq2;
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow[0];
			
		Return 0;		
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
		Global $iCategoryUnq;
		Global $iDBLoc;
		Global $aVariables;
		Global $aValues;
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageGalleries.sAction.value = sAction;
				document.ManageGalleries.submit();
			}
			
			function PaginationLink(sQueryString){
				document.location = "Move.php?<?=DOMAIN_Link("G")?>&iCategoryUnq=<?=$iCategoryUnq?>" + sQueryString;
			}
			
			function ReturnToMain(){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iCategoryUnq=<?=$iCategoryUnq ?>&iDBLoc=<?=$iDBLoc ?>";
			}
			
		</script>
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
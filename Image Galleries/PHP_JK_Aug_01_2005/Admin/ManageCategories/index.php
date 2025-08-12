<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	If ( (ACCNT_ReturnRights("PHPJK_IG_DEL_CAT")) || (ACCNT_ReturnRights("PHPJK_IG_EDIT_CAT")) ) {
		HeaderHTML();
		Main();
	}Else{
		WriteScripts();
		DOMAIN_Message("You must login with Image Gallery System rights.", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_CLOSE.php");
	

	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{		
		Global $iNumPerPage;
		Global $iParentUnq;
		Global $iTtlNumItems;
		Global $iDBLoc;
		
		$sAction		= Request("sAction");
		$iParentUnq		= Trim(Request("iParentUnq"));
		$sSuccess		= "";
		$sError			= "";
		
		If ( $iParentUnq == "" )
			$iParentUnq = "0";

		If ( $sAction == "MoveUp" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_IG_EDIT_CAT") ) {
				$sError = "You must have rights to update Image Gallery categories before performing this action.";
			}Else{
				$iCategoryUnq	= Trim(Request("iMoveCategoryUnq"));
				If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
					$sQuery	= "SELECT CategoryUnq, Position FROM IGCategories (NOLOCK) WHERE Position <= (SELECT Position FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq . ") AND Parent = " . $iParentUnq . " ORDER BY Position DESC";
				}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
					$sQuery			= "SELECT Position FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq;
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) )
						$sQuery	= "SELECT CategoryUnq, Position FROM IGCategories (NOLOCK) WHERE Position <= " . $rsRow["Position"] . " AND Parent = " . $iParentUnq . " ORDER BY Position DESC";
				}
				DB_Query("SET ROWCOUNT 2");
				$rsRecordSet	= DB_Query($sQuery);
				DB_Query("SET ROWCOUNT 0");
				If ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$sCurPosition = $rsRow["Position"];
					If ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						// if this is false, then the admin is trying to move the last image past the end
						DB_Update ("UPDATE IGCategories SET Position = " . $rsRow["Position"] . " WHERE CategoryUnq = " . $iCategoryUnq);
						DB_Update ("UPDATE IGCategories SET Position = " . $sCurPosition . " WHERE CategoryUnq = " . $rsRow["CategoryUnq"]);
					}
				}
				
			}
		}ElseIf ( $sAction == "MoveDown" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_IG_EDIT_CAT") ) {
				$sError = "You must have rights to update Image Gallery categories before performing this action.";
			}Else{
				$iCategoryUnq = Trim(Request("iMoveCategoryUnq"));
				If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
					$sQuery = "SELECT CategoryUnq, Position FROM IGCategories (NOLOCK) WHERE Position >= (SELECT Position FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq . ") AND Parent = " . $iParentUnq . " ORDER BY Position ASC";
				}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
					$sQuery			= "SELECT Position FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq;
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) )
						$sQuery = "SELECT CategoryUnq, Position FROM IGCategories (NOLOCK) WHERE Position >= " . $rsRow["Position"] . " AND Parent = " . $iParentUnq . " ORDER BY Position ASC";
				}
				DB_Query("SET ROWCOUNT 2");
				$rsRecordSet	= DB_Query($sQuery);
				DB_Query("SET ROWCOUNT 0");
				If ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$sCurPosition = $rsRow["Position"];
					If ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						DB_Update ("UPDATE IGCategories SET Position = " . $rsRow["Position"] . " WHERE CategoryUnq = " . $iCategoryUnq);
						DB_Update ("UPDATE IGCategories SET Position = " . $sCurPosition . " WHERE CategoryUnq = " . $rsRow["CategoryUnq"]);
					}
				}
			}
		}ElseIf ( $sAction == "EditCategories" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_IG_EDIT_CAT") ) {
				$sError = "You must have rights to update Image Gallery categories before performing this action.";
			}Else{
				ForEach ($_POST as $sTextField=>$sValue)
				{
					If ( strpos($sTextField, "sOldName") !== false )
					{
						$iCategoryUnq		= str_replace("sOldName", "", $sTextField);
						$sDescription		= Trim(Request("sNewDescription" . $iCategoryUnq));
						$sName				= Trim(Request("sNewName" . $iCategoryUnq));
						$sOldDescription	= Trim(Request("sOldDescription" . $iCategoryUnq));
						$sOldName			= Trim(Request("sOldName" . $iCategoryUnq));
						$sRightsLvl			= Trim(Request("sRightsLvl" . $iCategoryUnq));
						$sOldRightsLvl		= Trim(Request("sOldRightsLvl" . $iCategoryUnq));

						If (( $sDescription != $sOldDescription ) || ( $sOldName != $sName ) || ( $sOldRightsLvl != $sRightsLvl ))
						{
							DB_Update ("UPDATE IGCategories SET Name = '" . SQLEncode($sName) . "', Description = '" . SQLEncode($sDescription) . "', RightsLvl = '" . SQLEncode($sRightsLvl) . "' WHERE CategoryUnq = " . $iCategoryUnq);
							If ( $sSuccess == "")
								$sSuccess = "Changes to categories was successful.";
						}
					}
				}
			}
		}ElseIf ( $sAction == "DeleteCategories" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_IG_DEL_CAT") ) {
				$sError = "You must have rights to delete Image Gallery categories before performing this action.";
			}Else{
				ForEach ($_POST as $sCheckbox=>$sValue)
				{
					If ( strpos($sCheckbox, "sDelete") !== false )
					{
						$iCategoryUnq		= str_replace("sDelete", "", $sCheckbox);

						// recursively delete all conferences, CMB entries, threads, messages, and email notifications for those messages
						RecursivelyDelCat($iCategoryUnq, $iParentUnq);
						
						// remove one from the conference's parent NumChildren count
						$sQuery			= "SELECT NumChildren FROM IGCategories WHERE CategoryUnq = " . $iParentUnq;
						$rsRecordSet	= DB_Query($sQuery);
						If ( ( $rsRow = DB_Fetch($rsRecordSet) ) && ( ! is_null($rsRow["NumChildren"]) ) ) {
							$iTemp = $rsRow["NumChildren"] - 1;
						}Else{
							$iTemp = 0;
						}
						
						DB_Update ("UPDATE IGCategories SET NumChildren = " . $iTemp . " WHERE CategoryUnq = " . $iParentUnq);
						
						If ( $sSuccess == "")
							$sSuccess = "Deletion of categories was successful.";
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
			
		If ( $sAction == "DeleteCategories" )
			$iTtlNumItems	= 0;
		
		if ( $iTtlNumItems == 0 ) {
			$sQuery			= "SELECT Count(*) FROM IGCategories (NOLOCK) WHERE Parent = " . $iParentUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
				$iTtlNumItems = $rsRow[0];
		}
		// Pagination variables -- end		
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		If ( $sSuccess != "" )
			DOMAIN_Message($sSuccess, "SUCCESS");
		
		WriteScripts();
		WriteForm();
	}
	//************************************************************************************


	//************************************************************************************
	//*																					*
	//*	This recursively delete all categories and their images.						*
	//*																					*
	//************************************************************************************
	Function RecursivelyDelCat($iCategoryUnq, $iParentUnq)
	{
		// Decrement (by 1) all Categories AFTER the one we are deleting.
		If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
			$sQuery	= "SELECT CategoryUnq, Position FROM IGCategories (NOLOCK) WHERE Position > (SELECT Position FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq . ") AND Parent = " . $iParentUnq . " ORDER BY Position";
		}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
			$sQuery			= "SELECT Position FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
				$sQuery	= "SELECT CategoryUnq, Position FROM IGCategories (NOLOCK) WHERE Position > " . $rsRow["Position"] . " AND Parent = " . $iParentUnq . " ORDER BY Position";
		}
		$rsRecordSet	= DB_Query($sQuery);
		While ( $rsRow = DB_Fetch($rsRecordSet) )
			DB_Update ("UPDATE IGCategories SET Position = " . ($rsRow["Position"] - 1) . " WHERE CategoryUnq = " . $rsRow["CategoryUnq"]);
		
		DB_Update ("DELETE FROM IGCategories WHERE CategoryUnq = " . $iCategoryUnq);
		If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
			DB_Update ("UPDATE Galleries SET Position = (SELECT ISNULL(MAX(Position),0)+1 FROM Galleries WHERE CategoryUnq = 0), CategoryUnq = 0 WHERE CategoryUnq = " . $iCategoryUnq);
		}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
			$sQuery = "SELECT MAX(Position) FROM Galleries (NOLOCK) WHERE CategoryUnq = 0";
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) ){
				$iPosition = $rsRow[0]+1;
			}Else{
				$iPosition = 1;
			}
			DB_Update ("UPDATE Galleries SET Position = " . $iPosition . ", CategoryUnq = 0 WHERE CategoryUnq = " . $iCategoryUnq);
		}
		DelOldCatFiles($iCategoryUnq);

		// now, get all categories who are children of the current category and run this Function on them.
		$sQuery			= "SELECT CategoryUnq FROM IGCategories WHERE Parent = " . $iCategoryUnq;
		$rsRecordSet2	= DB_Query($sQuery);
		While ( $rsRow2 = DB_Fetch($rsRecordSet2) )
			If ( ( $rsRow2["CategoryUnq"] != "" ) && ( ! is_null($rsRow2["CategoryUnq"]) ) )
				RecursivelyDelCat($rsRow2["CategoryUnq"], $iCategoryUnq);
	}
	//************************************************************************************

	
	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $iParentUnq;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iNumPerPage;
		Global $aValues;
		Global $aVariables;
		Global $sGalleryPath;
		
		$iHeight	= DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_HEIGHT");
		$iWidth		= DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_WIDTH");
		$sBGColor	= $GLOBALS["BGColor2"];
		$sLinkColor = "";
		$sTextColor = "";
		
		// Get the list of Rights Levels
		$x = 0;
		$sQuery			= "SELECT * FROM RightsLookup (NOLOCK) ORDER BY RightsLvl";
		$rsRecordSet	= DB_Query($sQuery);
		While ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			If ( strlen ($rsRow["RightsConst"]) > 45 ) {
				$sTemp = substr($rsRow["RightsConst"], 42) . "...";
			}Else{
				$sTemp = $rsRow["RightsConst"];
			}
			$aRightsLvl[0][$x] = $rsRow["RightsLvl"];
			$aRightsLvl[1][$x] = $sTemp;
			$x++;
		}
		
		$iUBoundRightsLvl = $x;
		
		$sQuery = "SELECT * FROM IGCategories (NOLOCK) WHERE Parent = " . $iParentUnq . " ORDER BY Position";
		DB_Query("SET ROWCOUNT " . ($iDBLoc + $iNumPerPage));
		$rsRecordSet = DB_Query($sQuery);
		DB_Query("SET ROWCOUNT 0");
		For ( $x = 1; $x <= $iDBLoc; $x++)
			DB_Fetch($rsRecordSet);
			
		?>
		<form name='ManageCategories' action='index.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "iTtlNumItems";
		$aVariables[2] = "iDBLoc";
		$aVariables[3] = "iMoveCategoryUnq";
		$aVariables[4] = "iMovePosition";
		$aVariables[5] = "iParentUnq";
		$aValues[0] = "New";
		$aValues[1] = $iTtlNumItems;
		$aValues[2] = $iDBLoc;
		$aValues[3] = "";
		$aValues[4] = "";
		$aValues[5] = $iParentUnq;
		Echo DOMAIN_Link("P");
		
		DOMAIN_Link_Clear();
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Manage Image Gallery Categories</b></font>
					<br><br>
					From this page you can change or delete existing categories. 
					You can also create new categories by clicking the button above.
					<br><br>
					Return to the parent of the category: 
					<a href='JavaScript:ViewParent()' class='MediumNavPage'><?=G_STRUCTURE_GetParentName($iParentUnq)?></a>.
					<br>
					Creating new categories here will make them children of the "<?=G_STRUCTURE_GetParentName($iParentUnq)?>" category.
					<br>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Image</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Category Name</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Category Description</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b># Children</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>&nbsp;</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Delete</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Order</b></td>
						</tr>
						<tr>
						<?php 
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor == $GLOBALS["BGColor1"] )
							{
								$sBGColor = $GLOBALS["PageBGColor"];
								$sLinkColor = "MediumNavPage";
								$sTextColor = $GLOBALS["PageText"];
							}Else{
								$sBGColor = $GLOBALS["BGColor1"];
								$sLinkColor = "MediumNav1";
								$sTextColor = $GLOBALS["TextColor1"];
							}
							$sDescription	= Trim($rsRow["Description"]);
							$iCategoryUnq	= Trim($rsRow["CategoryUnq"]);
							$sName			= Trim($rsRow["Name"]);
							$sPosition		= $rsRow["Position"];
							$sHasImage		= strtoupper(Trim($rsRow["HasImage"]));
							$iNumChildren	= $rsRow["NumChildren"];
							?>
							<tr>
								<td valign=top bgcolor=<?=$sBGColor?>>
									<?php 
									$sFilePath	= DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_LOC") . "\\" . "CatImage_" . $iCategoryUnq . ".jpg";
									$sFilePath	= str_replace("\\", "/", $sFilePath);
									$sFilePath	= str_replace("//", "/", $sFilePath);
									If ( $sHasImage == "Y" ) {
										?>
										<img src='<?=$sFilePath?>' width=<?=$iWidth?> height=<?=$iHeight?> border=1>
										<?php 
									}
									?>
								</td>
								<td valign=top bgcolor=<?=$sBGColor?>>
									<input type='hidden' name="sOldName<?=$iCategoryUnq?>" value="<?=htmlentities($sName)?>">
									<input type='text' name='sNewName<?=$iCategoryUnq?>' value="<?=htmlentities($sName)?>" size=20 maxlength=250>
									<br>
									<a href='EditImage.php?iParentUnq=<?=$iParentUnq?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>&iCategoryUnq=<?=$iCategoryUnq?>' class='<?=$sLinkColor?>'>Edit Image</a>
								</td>
								<td valign=top bgcolor=<?=$sBGColor?>>
									<input type='hidden' name="sOldDescription<?=$iCategoryUnq?>" value="<?=htmlentities($sDescription)?>">
									<input type='text' name='sNewDescription<?=$iCategoryUnq?>' value="<?=htmlentities($sDescription)?>" size=40 maxlength=250>
								</td>
								<td valign=top align=center bgcolor=<?=$sBGColor?>><?=$iNumChildren?></td>
								<td valign=top align=center bgcolor=<?=$sBGColor?>>
									<?php If ( $iNumChildren > 0 ) {?>
									<a href='JavaScript:ViewChild("<?=$iCategoryUnq?>")' class='<?=$sLinkColor?>'>View</a>
									<?php }Else{?>
									<a href='JavaScript:ViewChild("<?=$iCategoryUnq?>")' class='<?=$sLinkColor?>'>Add</a>
									<?php }?>
								</td>
								<td valign=top align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name='sDelete<?=$iCategoryUnq?>' value='Y'></td>
								<td valign=top align=center bgcolor=<?=$sBGColor?>>
									<Table cellpadding=0 cellspacing=0 border=0>
										<?php If ( $sPosition > 1 ) {?>
										<tr><td><a href='JavaScript:ReorderCategories(<?=$sPosition?>, <?=$iCategoryUnq?>, "MoveUp")'><img src='../../Images/Administrative/MoveUp.gif' border=0 width=12 height=6 alt=' Move category up one '></a></td></tr>
										<?php }?>
										<tr><td><img src='../../Images/Blank.gif' border=0 width=12 height=6></td></tr>
										<?php
										If (( $sPosition < $iTtlNumItems ) && ( $iTtlNumItems >= 1 )) {?>
										<tr><td><a href='JavaScript:ReorderCategories(<?=$sPosition?>, <?=$iCategoryUnq?>, "MoveDown")'><img src='../../Images/Administrative/MoveDown.gif' border=0 width=12 height=6 alt=' Move category down one '></a></td></tr>
										<?php }?>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan=7>
									<input type='hidden' name='sOldRightsLvl<?=$iCategoryUnq?>' value="<?=htmlentities(Trim($rsRow["RightsLvl"]))?>">
									<select name = "sRightsLvl<?=$iCategoryUnq?>">
										<?php 
										Echo "<option value = ''>No Rights Level</option>";
										for ( $x = 0; $x < ($iUBoundRightsLvl - 1); $x++)
										{
											If ( Trim($rsRow["RightsLvl"]) == $aRightsLvl[0][$x] ) {
												Echo "<option value = \"" . htmlentities($aRightsLvl[0][$x]) . "\" Selected>" . htmlentities($aRightsLvl[0][$x]) . " - " . htmlentities($aRightsLvl[1][$x]) . "</option>";
											}Else{
												Echo "<option value = \"" . htmlentities($aRightsLvl[0][$x]) . "\">" . htmlentities($aRightsLvl[0][$x]) . " - " . htmlentities($aRightsLvl[1][$x]) . "</option>";
											}
										}
										?>
									</select>
								</td>
							</tr>
							<?php
						}?>
						<tr>
							<td colspan=7 align=right>
								<?php PrintRecordsetNav_ADMIN( "index.php", "", "Galleries" );?>
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
	//********************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*	This writes the JavaScript out even if the Main() Function isn't called in case the	*
	//*		admin has creation rights, but not edit rights.								*
	//*																					*
	//************************************************************************************
	Function WriteScripts()
	{
		Global $iParentUnq;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $aValues;
		Global $aVariables;
		
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageCategories.sAction.value = sAction;
				document.ManageCategories.submit();
			}
			
			function PaginationLink(sQueryString){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iParentUnq=<?=$iParentUnq?>&" + sQueryString;
			}
			
			function NewCategory(){
				document.location = "New.php?<?=DOMAIN_Link("G")?>&iParentUnq=<?=$iParentUnq?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
			}
			
			function Recalculate(){
				document.location = "Recalculate.php?<?=DOMAIN_Link("G")?>&iParentUnq=<?=$iParentUnq?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
			}
			
			function ReorderCategories(iMovePosition, iMoveCategoryUnq, sAction){
				document.ManageCategories.iMoveCategoryUnq.value=iMoveCategoryUnq;
				document.ManageCategories.iMovePosition.value=iMovePosition;
				SubmitForm(sAction);
			}
			
			function ViewChild(iCategoryUnq){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iDBLoc=<?=$iDBLoc?>&iParentUnq=" + iCategoryUnq;
			}
			
			
			function ViewParent(){
				<?php 
				// must check if they have rights or this crashes
				If ( (ACCNT_ReturnRights("PHPJK_IG_DEL_CAT")) || (ACCNT_ReturnRights("PHPJK_IG_EDIT_CAT")) ) {
					// Get the parent's parent so we know where to go up from here
					$sQuery			= "SELECT Parent FROM IGCategories WHERE CategoryUnq = " . $iParentUnq;
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) ){
						?>document.location = "index.php?<?=DOMAIN_Link("G")?>&iDBLoc=<?=$iDBLoc?>&iParentUnq=<?=$rsRow["Parent"]?>";<?php 
					}Else{
						?>document.location = "index.php?<?=DOMAIN_Link("G")?>&iDBLoc=<?=$iDBLoc?>&iParentUnq=0";<?php 
					}
					
				}
				?>
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
				<td colspan=10 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If ( ACCNT_ReturnRights("PHPJK_IG_EDIT_CAT") ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/UpdateCategory.gif' ALIGN='absmiddle' Width=31 Height=44 Border=0 Alt='Save changes to the categories.' onClick='SubmitForm(\"EditCategories\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ACCNT_ReturnRights("PHPJK_IG_DEL_CAT") ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='delete' SRC='../../Images/Administrative/DelCategory.gif' ALIGN='absmiddle' Width=27 Height=39 Border=0 Alt='Delete checked categories.' onClick='SubmitForm(\"DeleteCategories\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ACCNT_ReturnRights("PHPJK_IG_ADD_CAT") ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:NewCategory();'><img src='../../Images/Administrative/AddCategory.gif' Width=22 Height=39 Border=0 Alt='Add a new category.'></a></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=45><img src='../../Images/Blank.gif' Width=45 Height=2></td>";
				If ( ACCNT_ReturnRights("PHPJK_IG_EDIT_CAT") ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:Recalculate();'><img src='../../Images/Administrative/Recalculate.gif' Width=56 Height=45 Border=0 Alt='Recalculate category image totals.'></a></td>";
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
				<td colspan=10 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=12 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
?>
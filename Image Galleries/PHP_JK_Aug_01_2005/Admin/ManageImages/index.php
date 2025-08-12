<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iGalleryUnq	= "";
	$bIsImage		= False;

	If ( (ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL")) || (ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY")) )
	{
		HeaderHTML();
		Main();
	}Else{
		WriteScripts();
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
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iNumPerPage;
		Global $iGalleryUnq;
		
		$sError			= "";
		$sSuccess		= "";
		$sAction		= Trim(Request("sAction"));
		$iGalleryUnq	= Trim(Request("iGalleryUnq"));
		
		If ( $sAction == "UpdateDomainUnq" )
			$iGalleryUnq = "";
		
		If ( $iGalleryUnq == "" )
		{
			If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				$sQuery = "SELECT GalleryUnq FROM Galleries (NOLOCK) ORDER BY Name";
			}Else{
				$sQuery = "SELECT GalleryUnq FROM Galleries (NOLOCK) WHERE AccountUnq = " . $iLoginAccountUnq . " ORDER BY Name";
			}
			DB_Query("SET ROWCOUNT 1");
			$rsRecordSet = DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			If ( $rsRow = DB_Fetch($rsRecordSet) ){
				$iGalleryUnq = $rsRow["GalleryUnq"];
			}Else{
				$iGalleryUnq = -1;
			}
		}

		If ( $sAction == "MoveUp" ) {
			$iImageUnq = Trim(Request("iMoveImageUnq"));
			If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
				If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
					$sQuery	= "SELECT * FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE I.ImageUnq = IG.ImageUnq AND IG.GalleryUnq = " . $iGalleryUnq . " AND IG.Position <= (SELECT Position FROM ImagesInGallery (NOLOCK) WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq . ") ORDER BY IG.Position DESC";
				}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
					$sQuery			= "SELECT Position FROM ImagesInGallery (NOLOCK) WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq;
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) )
						$sQuery	= "SELECT * FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE I.ImageUnq = IG.ImageUnq AND IG.GalleryUnq = " . $iGalleryUnq . " AND IG.Position <= " . $rsRow["Position"] . " ORDER BY IG.Position DESC";
				}
				DB_Query("SET ROWCOUNT 2");
				$rsRecordSet	= DB_Query($sQuery);
				DB_Query("SET ROWCOUNT 0");
				If ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$sCurImagePos = $rsRow["Position"];
					If ( $rsRow = DB_Fetch($rsRecordSet) )	// if this is false, then the admin is trying to move the last image past the end
					{
						DB_Update ("UPDATE ImagesInGallery SET Position = " . $rsRow["Position"] . " WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq);
						DB_Update ("UPDATE ImagesInGallery SET Position = " . $sCurImagePos . " WHERE ImageUnq = " . $rsRow["ImageUnq"] . " AND GalleryUnq = " . $iGalleryUnq);
					}
				}
			}Else{
				$sError = "Sorry but you are not the owner of this gallery and cannot reorder images in it.<br>";
			}
		}ElseIf ( $sAction == "MoveDown" ) {
			$iImageUnq = Trim(Request("iMoveImageUnq"));
			If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
				If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
					$sQuery = "SELECT * FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE I.ImageUnq = IG.ImageUnq AND IG.GalleryUnq = " . $iGalleryUnq . " AND IG.Position >= (SELECT Position FROM ImagesInGallery (NOLOCK) WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq . ") ORDER BY IG.Position ASC";
				}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
					$sQuery			= "SELECT Position FROM ImagesInGallery (NOLOCK) WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq;
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) )
						$sQuery = "SELECT * FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE I.ImageUnq = IG.ImageUnq AND IG.GalleryUnq = " . $iGalleryUnq . " AND IG.Position >= " . $rsRow["Position"] . " ORDER BY IG.Position ASC";
				}
				DB_Query("SET ROWCOUNT 2");
				$rsRecordSet	= DB_Query($sQuery);
				DB_Query("SET ROWCOUNT 0");
				If ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$sCurImagePos = $rsRow["Position"];
					If ( $rsRow = DB_Fetch($rsRecordSet) )	// if this is false, then the admin is trying to move the last image past the end
					{
						DB_Update ("UPDATE ImagesInGallery SET Position = " . $rsRow["Position"] . " WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq);
						DB_Update ("UPDATE ImagesInGallery SET Position = " . $sCurImagePos . " WHERE ImageUnq = " . $rsRow["ImageUnq"] . " AND GalleryUnq = " . $iGalleryUnq);
					}
				}
			}Else{
				$sError = "Sorry but you are not the owner of this gallery and cannot reorder images in it.<br>";
			}
		}ElseIf ( $sAction == "DeleteImage" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				If ( ! ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") )
					$sError = "Please log in with Image Gallery management rights.";
			}

			If ( $sError == "" )
			{
				ForEach ($_POST as $sCheckbox=>$sValue)
				{
					If ( strpos($sCheckbox, "sDeleteI") !== false )
					{
						$iImageUnq	= str_replace("sDeleteI", "", $sCheckbox);
						If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
						{
							// Decrement (by 1) all ImageNum's AFTER the one we are deleting.
							If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
								$sQuery	= "SELECT I.ImageUnq, IG.Position FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IG.GalleryUnq = " . $iGalleryUnq . " AND I.ImageUnq = IG.ImageUnq AND IG.Position > (SELECT Position FROM ImagesInGallery (NOLOCK) WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq . ") ORDER BY IG.Position";
							}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
								$sQuery	= "SELECT Position FROM ImagesInGallery (NOLOCK) WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq;
								$rsRecordSet	= DB_Query($sQuery);
								If ( $rsRow = DB_Fetch($rsRecordSet) ){
									$sQuery	= "SELECT I.ImageUnq, IG.Position FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IG.GalleryUnq = " . $iGalleryUnq . " AND I.ImageUnq = IG.ImageUnq AND IG.Position > (" . $rsRow["Position"] . ") ORDER BY IG.Position";
								}Else{
									$sQuery	= "SELECT I.ImageUnq, IG.Position FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IG.GalleryUnq = " . $iGalleryUnq . " AND I.ImageUnq = IG.ImageUnq AND IG.Position > 1 ORDER BY IG.Position";
								}
							}
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
								DB_Update ("UPDATE ImagesInGallery SET Position = " . ($rsRow["Position"] - 1) . " WHERE ImageUnq = " . $rsRow["ImageUnq"] . " AND GalleryUnq = " . $iGalleryUnq);
							
							// must also delete all the images
							G_UPLOAD_DelFiles($iImageUnq, TRUE);
							
							DB_Update ("DELETE FROM IGECards WHERE ImageUnq = " . $iImageUnq);
							DB_Update ("DELETE FROM Images WHERE ImageUnq = " . $iImageUnq);
							DB_Update ("DELETE FROM ImagesInGallery WHERE ImageUnq = " . $iImageUnq);	// deletes the primary image and all references
							DB_Update ("DELETE FROM IGImageCRs WHERE ImageUnq = " . $iImageUnq);
							DB_Update ("DELETE FROM IGImageProds WHERE ImageUnq = " . $iImageUnq);
							DB_Update ("DELETE FROM IGMiscLinks WHERE ImageUnq = " . $iImageUnq);
							DB_Update ("DELETE FROM IGSearchResults WHERE ImageUnq = " . $iImageUnq);
							
							If ( $sSuccess == "" )
								$sSuccess = "Successfully deleted image.<br>";
						}Else{
							If ( $sError == "" )
								$sError = "Sorry but you are not the owner of this gallery and cannot delete images in it.<br>";
						}
					}
					
					// search for images being referenced and remove them
					If ( strpos($sCheckbox, "sDeleteR") !== false )
					{
						$iImageUnq	= str_replace("sDeleteR", "", $sCheckbox);
						If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
						{
							// Decrement (by 1) all ImageNum's AFTER the one we are deleting.
							$sQuery = "SELECT I.ImageUnq, IG.Position FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IG.GalleryUnq = " . $iGalleryUnq . " AND I.ImageUnq = IG.ImageUnq AND IG.Position > (SELECT Position FROM ImagesInGallery (NOLOCK) WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq . ") ORDER BY IG.Position";
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
								DB_Update ("UPDATE ImagesInGallery SET Position = " . ($rsRow["Position"] - 1) . " WHERE ImageUnq = " . $rsRow["ImageUnq"] . " AND GalleryUnq = " . $iGalleryUnq);
							
							DB_Update ("DELETE FROM IGECards WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " .  $iGalleryUnq);
							DB_Update ("DELETE FROM ImagesInGallery WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " .  $iGalleryUnq);
							DB_Update ("DELETE FROM IGSearchResults WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " .  $iGalleryUnq);
							
							If ( $sSuccess == "" )
								$sSuccess = "Successfully deleted image.<br>";
						}Else{
							If ( $sError == "" )
								$sError = "Sorry but you are not the owner of this gallery and cannot delete images in it.<br>";
						}
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
			
		If ( $sAction == "UpdateGalleryUnq" ) {
			$iDBLoc			= 0;
			$iTtlNumItems	= 0;
		}ElseIf ( $sAction == "DeleteImage" ) {
			$iTtlNumItems	= 0;
		}
		
		if ( $iTtlNumItems == 0 ) {
			$sQuery			= "SELECT Count(*) FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.GalleryUnq = " . $iGalleryUnq . " AND IG.GalleryUnq = " . $iGalleryUnq;
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
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $aVariables;
		Global $aValues;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iNumPerPage;
		Global $iLoginAccountUnq;
		Global $iGalleryUnq;
		Global $bIsImage;
		Global $sSiteURL;
		
		$sBGColor = $GLOBALS["BGColor2"];
		
		If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( ACCNT_ReturnRights("PHPJK_IG_ADD_CR_2IMAGES") ) ) {
			$bAddCRRights = TRUE;
		}Else{
			$bAddCRRights = FALSE;
		}
		If ( ( ACCNT_ReturnRights("PHPJK_IG_ADD_CF_IMAGE") ) || ( ACCNT_ReturnRights("PHPJK_IG_EDIT_CF_DATA_IMAGE") ) || ( ACCNT_ReturnRights("PHPJK_IG_EDIT_CF_IMAGE") ) Or ( ACCNT_ReturnRights("PHPJK_IG_DEL_CF_IMAGE") ) ) {
			$bAddCustFieldsRights = TRUE;
		}Else{
			$bAddCustFieldsRights = FALSE;
		}
		If ( ( ACCNT_ReturnRights("PHPJK_IG_REF_GALLERY") ) || ( ACCNT_ReturnRights("PHPJK_IG_REF_GALLERY") ) ) {
			$bReference = TRUE;
		}Else{
			$bReference = FALSE;
		}
		
		$bAricaur = FALSE;
		If ( ACCNT_ReturnRights("PHPJK_IG_ARICAUR") ) {
			If ( DOMAIN_Conf("ARICAUR_WEBMASTER_ID") != "" ) {
				$bAricaur = TRUE;
			}
		}
		
		?>
		<form name='ManageImages' action='index.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "iTtlNumItems";
		$aVariables[2] = "iDBLoc";
		$aVariables[3] = "iMoveImageUnq";
		$aVariables[4] = "iMoveImagePos";
		$aValues[0] = "New";
		$aValues[1] = $iTtlNumItems;
		$aValues[2] = $iDBLoc;
		$aValues[3] = 0;
		$aValues[4] = 0;
		Echo DOMAIN_Link("P");
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Manage Images</b></font>
					<br><br>
					<table width=671>
						<tr>
							<td><font color='<?=$GLOBALS["PageText"]?>'><b>Category -- Gallery:</b></font></td>
						</tr>
						<tr>
							<td>
								<select name='iGalleryUnq' onChange='SubmitForm("UpdateGalleryUnq");'>
									<?php 
									If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
										$sQuery = "SELECT G.GalleryUnq, G.Name, C.Name CatName FROM Galleries G LEFT OUTER JOIN IGCategories C ON G.CategoryUnq = C.CategoryUnq ORDER BY C.Name, G.Name";
									}Else{
										$sQuery = "SELECT G.GalleryUnq, G.Name, C.Name CatName FROM Galleries G LEFT OUTER JOIN IGCategories C ON G.CategoryUnq = C.CategoryUnq WHERE G.AccountUnq = " . $iLoginAccountUnq . " ORDER BY C.Name, G.Name";
									}
									$rsRecordSet = DB_Query($sQuery);
									If ( DB_NumRows($rsRecordSet) > 0 )
									{
										While ( $rsRow = DB_Fetch($rsRecordSet) )
										{
											$sCatName = $rsRow["CatName"];
											If ( !$sCatName )
												$sCatName = "Galleries not in category";
											If ( $iGalleryUnq == Trim($rsRow["GalleryUnq"]) ) {
												Echo "<option value='" . $rsRow["GalleryUnq"] . "' Selected>" . htmlentities($sCatName) . " -- " . htmlentities($rsRow["Name"]) . "</option>";
											}Else{
												Echo "<option value='" . $rsRow["GalleryUnq"] . "'>" . htmlentities($sCatName) . " -- " . htmlentities($rsRow["Name"]) . "</option>";
											}
										}
									}Else{
										Echo "<option value='-1'>No Galleries</option>";
									}
									?>
								</select>
							</td>
						</tr>
					</table>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>ID</b></td>
							<td bgcolor=<?=$sBGColor?>>&nbsp;</td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Name</b></td>
							<td bgcolor=<?=$sBGColor?>>&nbsp;</td>
							<td bgcolor=<?=$sBGColor?>>&nbsp;</td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Delete</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Order</td>
						</tr>
						<?php 
						$sColor1 = $GLOBALS["BGColor1"];
						$sColor2 = $GLOBALS["PageBGColor"];
						$sColor3 = $GLOBALS["PageText"];
						$sColor4 = $GLOBALS["TextColor1"];
						$sColor5 = $GLOBALS["BGColor2"];
						$sColor6 = $GLOBALS["TextColor2"];
						
						$sQuery			= "SELECT I.ImageUnq, I.Image, I.Thumbnail, I.FileType, I.Image2, I.Image3, I.Image4, I.Image5, I.Image2Desc, I.Image3Desc, I.Image4Desc, I.Image5Desc, I.AltTag2, I.AltTag3, I.AltTag4, I.AltTag5, I.XSize2, I.YSize2, I.XSize3, I.YSize3, I.XSize4, I.YSize4, I.XSize5, I.YSize5, I.ImageSize2, I.ImageSize3, I.ImageSize4, I.ImageSize5, G.AccountUnq, IG.GalleryUnq, IG.Position, IG.PrimaryG, IG.PrimaryD FROM Images I (NOLOCK), Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.GalleryUnq = " . $iGalleryUnq . " AND IG.GalleryUnq = " . $iGalleryUnq . " AND I.ImageUnq = IG.ImageUnq ORDER BY IG.Position";
						DB_Query("SET ROWCOUNT " . ($iDBLoc + $iNumPerPage));
						$rsRecordSet = DB_Query($sQuery);
						DB_Query("SET ROWCOUNT 0");
						For ( $x = 1; $x <= $iDBLoc; $x++)
							DB_Fetch($rsRecordSet);
							
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor == $sColor1 ) {
								$sBGColor = $sColor2;
								$sTextColor = $sColor3;
								$sLinkColor = "SmallNavPage";
							}Else{
								$sBGColor = $sColor1;
								$sTextColor = $sColor4;
								$sLinkColor = "SmallNav1";
							}
							$sImage 		= htmlentities(Trim($rsRow["Image"]));
							$sThumbnail		= Trim($rsRow["Thumbnail"]);
							$iImageUnq 		= $rsRow["ImageUnq"];
							$iPosition		= $rsRow["Position"];
							$sAccountUnq	= $rsRow["AccountUnq"];
							$iGalleryUnq	= $rsRow["GalleryUnq"];
							$aAltImage[0]	= Trim($rsRow["Image2"]);
							$aAltImage[1]	= Trim($rsRow["Image3"]);
							$aAltImage[2]	= Trim($rsRow["Image4"]);
							$aAltImage[3]	= Trim($rsRow["Image5"]);
							$aXSize[0]		= Trim($rsRow["XSize2"]);
							$aXSize[1]		= Trim($rsRow["XSize3"]);
							$aXSize[2]		= Trim($rsRow["XSize4"]);
							$aXSize[3]		= Trim($rsRow["XSize5"]);
							$aYSize[0]		= Trim($rsRow["YSize2"]);
							$aYSize[1]		= Trim($rsRow["YSize3"]);
							$aYSize[2]		= Trim($rsRow["YSize4"]);
							$aYSize[3]		= Trim($rsRow["YSize5"]);
							$aImageSize[0]	= Trim($rsRow["ImageSize2"]);
							$aImageSize[1]	= Trim($rsRow["ImageSize3"]);
							$aImageSize[2]	= Trim($rsRow["ImageSize4"]);
							$aImageSize[3]	= Trim($rsRow["ImageSize5"]);
							$iPrimaryG		= Trim($rsRow["PrimaryG"]);

							If ( $iPrimaryG == $iGalleryUnq ) {
								$bIsReferenced = FALSE;
							}Else{
								$bIsReferenced = TRUE;
							}
							
							?>
							<tr>
								<td bgcolor=<?=$sBGColor?> align=center valign=top><font color='<?=$sTextColor?>'><?=$iImageUnq?></td>
								<td bgcolor=<?=$sBGColor?> align=center valign=top width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?>>
									<?php DispThumb( $iImageUnq, $iGalleryUnq, $sAccountUnq, $sThumbnail, $iPrimaryG);?>
								</td>
								<td bgcolor=<?=$sBGColor?> valign=top><font color='<?=$sTextColor?>'>
									<?php 
									$sType = Trim($rsRow["FileType"]);
									$bIsImage = true;
									G_STRUCTURE_FileType($sType, $bIsImage, $iImageUnq, "../../../../", 0);
									If ( $bIsImage == false )
										Echo "<img src='../../Images/MediaIcons/" . $sType . ".gif' alt = '" . $sType . " file'>&nbsp;";
									?>
									<a href = '<?=$sSiteURL?>/Attachments/DownloadAttach.php?sAccountUnq=<?=$sAccountUnq?>&iGalleryUnq=<?=$iPrimaryG?>&iImageUnq=<?=$iImageUnq?>' class='SmallNav1' target='_blank'>
									<?=htmlentities($sImage)?>
									</a>
								</td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<li><a href='Edit.php?iGalleryUnq=<?=$iGalleryUnq?>&iDBLoc=<?=$iDBLoc?>&iImageUnq=<?=$iImageUnq?>' class='<?=$sLinkColor?>'>Edit Image</a><br><br>
									<li><a href='EditLinks.php?iGalleryUnq=<?=$iGalleryUnq?>&iDBLoc=<?=$iDBLoc?>&iImageUnq=<?=$iImageUnq?>' class='<?=$sLinkColor?>'>Edit Links</a><br><br>
									<?php If ( $bAddCRRights ) {?>
									<li><a href='EditCopyrights.php?iGalleryUnq=<?=$iGalleryUnq?>&iDBLoc=<?=$iDBLoc?>&iImageUnq=<?=$iImageUnq?>' class='<?=$sLinkColor?>'>Edit Copyrights</a><br><br>
									<?php }?>
									<?php If ( $bIsReferenced == FALSE ) {?>
									<li><a href='EditThumbnail.php?iGalleryUnq=<?=$iGalleryUnq?>&iDBLoc=<?=$iDBLoc?>&iImageUnq=<?=$iImageUnq?>' class='<?=$sLinkColor?>'>Edit Thumbnail</a><br><br>
									<?php }?>
									<?php If ( $bReference ) {?>
									<li><a href='Referencing.php?iGalleryUnq=<?=$iGalleryUnq?>&iDBLoc=<?=$iDBLoc?>&iImageUnq=<?=$iImageUnq?>' class='<?=$sLinkColor?>'>Reference Elsewhere</a>
									<?php }?>
								</td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<?php If ( $bIsReferenced == FALSE ) {?>
									<li><a href='AltViews.php?iGalleryUnq=<?=$iGalleryUnq?>&iDBLoc=<?=$iDBLoc?>&iImageUnq=<?=$iImageUnq?>' class='<?=$sLinkColor?>'>Edit Alternate Views</a><br><br>
									<?php }?>
									<?php If ( $bIsReferenced == FALSE ) {?>
									<li><a href='Move.php?iGalleryUnq=<?=$iGalleryUnq?>&iDBLoc=<?=$iDBLoc?>&iImageUnq=<?=$iImageUnq?>' class='<?=$sLinkColor?>'>Move to Another Gallery</a><br><br>
									<?php }?>
									<?php If ( $bAddCustFieldsRights ) {?>
									<li><a href='EditCustom.php?iGalleryUnq=<?=$iGalleryUnq?>&iDBLoc=<?=$iDBLoc?>&iImageUnq=<?=$iImageUnq?>' class='<?=$sLinkColor?>'>Edit Custom Data</a><br><br>
									<?php }?>
									<?php //If ( ( $bAricaur ) && ( ( $aAltImage[0] != "" ) || ( $aAltImage[1] != "" ) || ( $aAltImage[2] != "" ) || ( $aAltImage[3] != "" ) ) ) {?>
									<?php If ( $bAricaur ) {?>
									<?php If ( $bIsReferenced == FALSE ) {?>
									<li><a href='EditAricaur.php?iGalleryUnq=<?=$iGalleryUnq?>&iDBLoc=<?=$iDBLoc?>&iImageUnq=<?=$iImageUnq?>' class='<?=$sLinkColor?>'>Edit Aricaur Links</a>
									<?php }?>
									<?php }?>
								</td>
								<td align=center bgcolor=<?=$sBGColor?> valign=top>
									<?php If ( $bIsReferenced == FALSE ) {?>
									<input type='checkbox' name="sDeleteI<?=$iImageUnq?>" value="<?=$iImageUnq?>">
									<?php }Else{?>
									<font size=-2 color='<?=$sTextColor?>'>(Remove Reference)</font><br>
									<input type='checkbox' name="sDeleteR<?=$iImageUnq?>" value="<?=$iImageUnq?>">
									<?php }?>
								</td>
								<td bgcolor=<?=$sBGColor?> align=center valign=top>
									<Table cellpadding=0 cellspacing=0 border=0>
										<?php If ( $iPosition > 1 ) {?>
										<tr><td><a href='JavaScript:ReorderImages(<?=$iPosition?>, <?=$iImageUnq?>, "MoveUp")'><img src='../../Images/Administrative/MoveUp.gif' border=0 width=12 height=6 alt=' Move image up one '></a></td></tr>
										<?php }?>
										<tr><td><img src='../../Images/Blank.gif' border=0 width=12 height=6></td></tr>
										<?php If ( ( $iPosition < $iTtlNumItems ) && ( $iTtlNumItems > 1 ) ) {?>
										<tr><td><a href='JavaScript:ReorderImages(<?=$iPosition?>, <?=$iImageUnq?>, "MoveDown")'><img src='../../Images/Administrative/MoveDown.gif' border=0 width=12 height=6 alt=' Move image down one '></a></td></tr>
										<?php }?>
									</table>
								</td>
							</tr>
							
							<?php If ( $bIsReferenced ) {?>
							<tr>
								<td colspan=7 bgcolor=<?=$sColor5?>><font color='<?=$sColor6?>'>
									<?php 
									Echo "<b>Image ";
									Echo $iImageUnq;
									Echo " is being referenced from: ";
									Echo "<a href='" . $sSiteURL . "/Admin/ManageImages/index.php?iGalleryUnq=";
									Echo $iPrimaryG;
									Echo "' class='SmallNav1' target='_new'>";
									Echo G_ADMINISTRATION_GetGalleryName($iPrimaryG);
									Echo "</a></b>";
									?>
								</td>
							</tr>
							<?php }?>
							<?php 
						}						
						?>
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
		
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageImages.sAction.value = sAction;
				document.ManageImages.submit();
			}
			
			function PaginationLink(sQueryString){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$iGalleryUnq?>" + sQueryString;
			}
			
			function ReorderImages(iMoveImagePos, iMoveImageUnq, sAction){
				document.ManageImages.iMoveImageUnq.value=iMoveImageUnq;
				document.ManageImages.iMoveImagePos.value=iMoveImagePos;
				SubmitForm(sAction);
			}
			
			function NewImage(){
				document.location = "New.php?<?=DOMAIN_Link("G")?>&iTtlNumItems=" + document.ManageImages.iTtlNumItems.value + "&iGalleryUnq=" + document.ManageImages.iGalleryUnq.value + "&iDBLoc=" + document.ManageImages.iDBLoc.value;
			}
			
		</script>
		<?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DispThumb( $iImageUnq, $iGalleryUnq, $sAccountUnq, $sThumbnail, $iPrimaryG )
	{
		Global $sGalleryPath;
		Global $sSiteURL;
		
		?>
		<table cellpadding=0 cellspacing=0 border=0><tr>
			<td align=center bgcolor=<?=$GLOBALS["BGColor1"]?>>
				<table cellpadding=0 cellspacing=0 border=0 width=100% height=100%>
					<tr>
						<td colspan=3 align=center>
							<table cellpadding=0 cellspacing=0 border=0 width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?>>
								<tr><td colspan=3 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../../Images/Blank.gif" width=1 height=1></td></tr>
								<tr>
									<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../../Images/Blank.gif" width=1 height=1></td>
									<?php If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DOMAINCHECK"))) == "YES" ) { ?>
									<td><a href = '<?=$sSiteURL?>/ImageDetail.php?iGalleryUnq=<?=$iGalleryUnq?>&iImageUnq=<?=$iImageUnq?>' target='_blank'><img src = "<?=$sSiteURL?>/Attachments/DispThumb.php?sAccountUnq=<?=$sAccountUnq?>&sThumbnail=<?=$sThumbnail?>&iGalleryUnq=<?=$iPrimaryG?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> border=0></a></td>
									<?php }Else{
									$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iPrimaryG . "/Thumbnails/" . $sThumbnail;
									$sFilePath	= str_replace("\\", "/", $sFilePath);
									$sFilePath	= str_replace("//", "/", $sFilePath);
									If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
									{
										?>
										<td><a href = '<?=$sSiteURL?>/ImageDetail.php?iGalleryUnq=<?=$iGalleryUnq?>&iImageUnq=<?=$iImageUnq?>' target='_blank'><img src = "<?=DOMAIN_Conf("IG")?>/<?=$sAccountUnq?>/<?=$iPrimaryG?>/Thumbnails/<?=$sThumbnail?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> border=0></a></td>
										<?php
									}Else{
										?>
										<td><a href = '<?=$sSiteURL?>/ImageDetail.php?iGalleryUnq=<?=$iGalleryUnq?>&iImageUnq=<?=$iImageUnq?>' target='_blank'><img src = "<?=DOMAIN_Conf("IMAGEGALLERY_MISSING_THUMBNAIL")?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> border=0></a></td>
										<?php
									}
									} ?>
									<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../../Images/Blank.gif" width=1 height=1></td>
								</tr>
								<tr><td colspan=3 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../../Images/Blank.gif" width=1 height=1></td></tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr></table>
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
				<td colspan=6 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='delete' SRC='../../Images/Administrative/DelImage.gif' ALIGN='absmiddle' Width=27 Height=39 Border=0 Alt='Delete checked images.' onClick='SubmitForm(\"DeleteImage\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='javascript:NewImage()'><img src='../../Images/Administrative/AddImage.gif' Width=22 Height=39 Border=0 Alt='Add a new image.'></a></td>";
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
				<td colspan=6 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=8 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
?>
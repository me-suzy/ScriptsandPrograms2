<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$bIsAdmin				= False;
	$iCategoryUnq			= 0;
	$bHasGalleryProdRights	= False;
	$bHasCR_Rights			= False;
	$iThumbComponent		= "";
	$bReferenceDomain		= False;

	$iTemp = DOMAIN_Conf("IMAGEGALLERY_SCRIPTTIMEOUT");
	If ( $iTemp = "" )
		$iTemp = 5400;
	set_time_limit($iTemp);
	
	If ( (ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL")) || (ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY")) ) {
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
		Global $bIsAdmin;
		Global $iLoginAccountUnq;
		Global $ASPIMAGE;
		Global $GFL;
		Global $iDBLoc;
		Global $iTtlNumItems;
		Global $iNumPerPage;
		Global $iCategoryUnq;
		Global $bHasGalleryProdRights;
		Global $bHasCR_Rights;
		Global $iThumbComponent;
		Global $bReferenceDomain;
		Global $sGalleryPath;
		Global $bAricaur;
		
		// these two must be global for G_UPLOAD_MakeThumb()
		Global $sAccountUnq;
		Global $iGalleryUnq;
		
		$sError			= "";
		$sSuccess		= "";
		$sAction		= Trim(Request("sAction"));
		
		If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
			$bIsAdmin = TRUE;
		}Else{
			$bIsAdmin = FALSE;
		}
		
		$iCategoryUnq	= Trim(Request("iCategoryUnq"));
		If ( $iCategoryUnq == "" )
		{
			If ( $bIsAdmin )
			{
				$iCategoryUnq = "0";
			}Else{
				// since this person is not an admin, must show galleries within the 1st category that they CAN put galleries within
				$sQuery = "SELECT * FROM Galleries (NOLOCK) WHERE AccountUnq = " . $iLoginAccountUnq;
				$rsRecordSet	= DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) ){
					$iCategoryUnq = $rsRow["CategoryUnq"];
				}Else{
					// they haven't added any galleries yet, so it doesn't matter
					$iCategoryUnq = "0";
				}
			}
		}

		$bHasGalleryProdRights	= ACCNT_ReturnRights("PHPJK_IG_ADD_PROD_2GALLERIES");
		$bHasCR_Rights			= ACCNT_ReturnRights("PHPJK_IG_ADD_CR_2IMAGES");
		$iThumbComponent		= G_ADMINISTRATION_ASPImageInstalled();
		If ( ( ACCNT_ReturnRights("PHPJK_IG_REF_GALLERY") ) || ( ACCNT_ReturnRights("PHPJK_IG_REF_GALLERY") ) ) {
			$bReferenceDomain = TRUE;
		}Else{
			$bReferenceDomain = FALSE;
		}
		
		$bAricaur = FALSE;
		If ( ACCNT_ReturnRights("PHPJK_IG_ARICAUR") ) {
			If ( DOMAIN_Conf("ARICAUR_WEBMASTER_ID") != "" ) {
				$bAricaur = TRUE;
			}
		}

		If ( $sAction == "MoveUp" ) {
			$iGalleryUnq	= Trim(Request("iMoveGalleryUnq"));
			If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
				$sQuery	= "SELECT GalleryUnq, Position FROM Galleries (NOLOCK) WHERE Position <= (SELECT Position FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq . ") AND CategoryUnq = " . $iCategoryUnq . " ORDER BY Position DESC";
			}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
				$sQuery	= "SELECT Position FROM Galleries WHERE GalleryUnq = " . $iGalleryUnq;
				$rsRecordSet	= DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) ){
					$sQuery	= "SELECT GalleryUnq, Position FROM Galleries WHERE Position <= " . $rsRow[0] . " AND CategoryUnq = " . $iCategoryUnq . " ORDER BY Position DESC";
				}Else{
					$sQuery	= "SELECT GalleryUnq, Position FROM Galleries WHERE Position <= 1 AND CategoryUnq = " . $iCategoryUnq . " ORDER BY Position DESC";
				}
			}
			DB_Query("SET ROWCOUNT 2");
			$rsRecordSet	= DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			If ( $rsRow = DB_Fetch($rsRecordSet) ){
				$sCurPosition = $rsRow["Position"];
				// if this is false, then the admin is trying to move the last image past the end
				If ( $rsRow = DB_Fetch($rsRecordSet) ){
					DB_Update ("UPDATE Galleries SET Position = " . $rsRow["Position"] . " WHERE GalleryUnq = " . $iGalleryUnq);
					DB_Update ("UPDATE Galleries SET Position = " . $sCurPosition . " WHERE GalleryUnq = " . $rsRow["GalleryUnq"]);
				}
			}
		}ElseIf ( $sAction == "MoveDown" ) {
			$iGalleryUnq = Trim(Request("iMoveGalleryUnq"));
			If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
				$sQuery	= "SELECT GalleryUnq, Position FROM Galleries (NOLOCK) WHERE Position >= (SELECT Position FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq . ") AND CategoryUnq = " . $iCategoryUnq . " ORDER BY Position ASC";
			}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
				$sQuery	= "SELECT Position FROM Galleries WHERE GalleryUnq = " . $iGalleryUnq;
				$rsRecordSet	= DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) ){
					$sQuery	= "SELECT GalleryUnq, Position FROM Galleries WHERE Position >= " . $rsRow[0] . " AND CategoryUnq = " . $iCategoryUnq . " ORDER BY Position ASC";
				}Else{
					$sQuery	= "SELECT GalleryUnq, Position FROM Galleries WHERE Position >= 1 AND CategoryUnq = " . $iCategoryUnq . " ORDER BY Position DESC";
				}
			}
			DB_Query("SET ROWCOUNT 2");
			$rsRecordSet	= DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			If ( $rsRow = DB_Fetch($rsRecordSet) ){
				$sCurPosition = $rsRow["Position"];
				// if this is false, then the admin is trying to move the last image past the end
				If ( $rsRow = DB_Fetch($rsRecordSet) ){
					DB_Update ("UPDATE Galleries SET Position = " . $rsRow["Position"] . " WHERE GalleryUnq = " . $iGalleryUnq);
					DB_Update ("UPDATE Galleries SET Position = " . $sCurPosition . " WHERE GalleryUnq = " . $rsRow["GalleryUnq"]);
				}
			}
		}ElseIf ( $sAction == "UpdateGallery" ) {
			If ( ! $bIsAdmin ) {
				If ( ! ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) {
					$sError = "Please log in with Image Gallery management rights.";
				}
			}

			If ( $sError == "" ) {
				ForEach ($_POST as $sTextField=>$sValue)
				{
					If ( strpos($sTextField, "sOldName") !== false )
					{
						$iGalleryUnq	= str_replace("sOldName", "", $sTextField);
						If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
							$sOldName = FixFormData($sValue);
							$sNewName = FixFormData(Request("sNewName" . $iGalleryUnq));
							If ( $sOldName != $sNewName ) {
								DB_Update ("UPDATE Galleries SET Name = '" . SQLEncode($sNewName) . "' WHERE GalleryUnq = " . $iGalleryUnq);
								If ( $sSuccess == "" )
									$sSuccess = "Successfully updated gallery information.<br>";
							}
						}Else{
							If ( $sError == "" )
								$sError = "Sorry but you are not the owner of this gallery and cannot modify it.<br>";
						}
					}
				}
				
				// Recreate thumbnails
				If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) ) {
					ForEach ($_POST as $sCheckbox=>$sValue)
					{
						If ( strpos($sCheckbox, "sRecreateThumbnails") !== false )
						{
							$iGalleryUnq = str_replace("sRecreateThumbnails", "", $sCheckbox);
							If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") ) || ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
								// only process images actually in this gallery -- not referenced ones -- so check for those w/ this gallery as their PrimaryG
								$sQuery			= "SELECT DISTINCT IG.ImageUnq, I.Image, I.Thumbnail, I.ImageUL FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK), Images I (NOLOCK) WHERE G.GalleryUnq = " . $iGalleryUnq . " AND IG.PrimaryG = " . $iGalleryUnq . " AND I.ImageUnq = IG.ImageUnq";
								$rsRecordSet	= DB_Query($sQuery);
								While ( $rsRow = DB_Fetch($rsRecordSet) )
								{
									$sAccountUnq	= $rsRow["ImageUL"];
									$sThumbname	= Trim($rsRow["Thumbnail"]);
									$sFilePath	= $sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\" . "Thumbnails\\" . $sThumbname;
									$sFilePath	= str_replace("\\", "/", $sFilePath);
									$sFilePath	= str_replace("//", "/", $sFilePath);
									If ( file_exists($sFilePath) && ( is_file($sFilePath) ) ) {
										/* first see if there is already a thumbnail, find out it's size . delete it
											must also decrement HD space used by the thumbnail because G_UPLOAD_MakeThumb
											will increment it*/
										$iFileSize = filesize($sFilePath) * -1;
										G_ADMINISTRATION_IncrementHDSpaceUsed($iLoginAccountUnq, $iFileSize);
										unlink($sFilePath);
									}Else{
										// check for a thumbnail w/ the original file name...just in case
										$sFilePath	= $sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\" . "Thumbnails\\" . Trim($rsRow["Image"]);
										$sFilePath	= str_replace("\\", "/", $sFilePath);
										$sFilePath	= str_replace("//", "/", $sFilePath);
										If ( ( file_exists($sFilePath) ) && ( is_file($sFilePath) ) ) {
											/* first see if there is already a thumbnail, find out it's size . delete it
												must also decrement HD space used by the thumbnail because G_UPLOAD_MakeThumb
												will increment it*/
											$iFileSize = filesize($sFilePath) * -1;
											G_ADMINISTRATION_IncrementHDSpaceUsed($iLoginAccountUnq, $iFileSize);
											unlink($sFilePath);
										}
									}

									// now see if there is a primary image w/ the same name as the thumbnail
									//	this is because sometimes admins will have a different thumbnail than primary image, and
									//	we aught to check to see if a full-size version of the thumbnail image exists and use that to recreate it
									$sFilePath	= $sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\" . $sThumbname;
									$sFilePath	= str_replace("\\", "/", $sFilePath);
									$sFilePath	= str_replace("//", "/", $sFilePath);
									If ( ( file_exists($sFilePath) ) && ( is_file($sFilePath) ) ) {
										$sFileName = $sThumbname;
									}Else{
										$sFileName = $rsRow["Image"];
									}
									G_UPLOAD_MakeThumb($sThumbname, $sFileName);
									DB_Update ("UPDATE Images SET Thumbnail = '" . SQLEncode($sThumbname) . "' WHERE ImageUnq = " . $rsRow["ImageUnq"]);
								}
								If ( $sSuccess == "" )
									$sSuccess = "Successfully processed thumbnails.";
							}Else{
								If ( $sError == "" )
									$sError = "Please login with rights to administer this gallery.";
							}
						}
					}
				}
				
			}
		}ElseIf ( $sAction == "DeleteGallery" ) {
			If ( ! $bIsAdmin ) {
				If ( ! ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) {
					$sError = "Please log in with Image Gallery management rights.";
				}
			}
			If ( $sError == "" ) {
				ForEach ($_POST as $sCheckbox=>$sValue)
				{
					If ( strpos($sCheckbox, "sDelete") !== false )
					{
						$iGalleryUnq = str_replace("sDelete", "", $sCheckbox);
						If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
							// only process images actually in this gallery -- not referenced ones -- so check for those w/ this gallery as their PrimaryG
							// must delete all the images & set the bClearDB flag to TRUE to del all the db info associated w/ the image
							$sQuery			= "SELECT IG.ImageUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.GalleryUnq = " . $iGalleryUnq . " AND IG.PrimaryG = " . $iGalleryUnq;
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
								G_UPLOAD_DelFiles($rsRow["ImageUnq"], TRUE);							

							// Decrement (by 1) all Galleries AFTER the one we are deleting.
							If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
								$sQuery = "SELECT GalleryUnq, Position FROM Galleries (NOLOCK) WHERE Position > (SELECT Position FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq . ") AND CategoryUnq = " . $iCategoryUnq . " ORDER BY Position";
							}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
								$sQuery	= "SELECT Position FROM Galleries WHERE GalleryUnq = " . $iGalleryUnq;
								$rsRecordSet	= DB_Query($sQuery);
								If ( $rsRow = DB_Fetch($rsRecordSet) ){
									$sQuery = "SELECT GalleryUnq, Position FROM Galleries (NOLOCK) WHERE Position > " . $rsRow[0] . " AND CategoryUnq = " . $iCategoryUnq . " ORDER BY Position";
								}Else{
									$sQuery = "SELECT GalleryUnq, Position FROM Galleries (NOLOCK) WHERE Position > 1 AND CategoryUnq = " . $iCategoryUnq . " ORDER BY Position";
								}
							}
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
								DB_Update ("UPDATE Galleries SET Position = " . ($rsRow["Position"] - 1) . " WHERE GalleryUnq = " . $rsRow["GalleryUnq"]);

							DB_Update ("DELETE FROM PrivateAccounts WHERE GalleryUnq = " . $iGalleryUnq);
							DB_Update ("DELETE FROM IG_Subscriptions WHERE GalleryUnq = " . $iGalleryUnq);
							DB_Update ("DELETE FROM IGSearchResults WHERE GalleryUnq = " . $iGalleryUnq);
							DB_Update ("DELETE FROM Galleries WHERE GalleryUnq = " . $iGalleryUnq);
							// have to add this next line to delete any remaining images from the gallery that are being referenced in it
							DB_Update ("DELETE FROM ImagesInGallery WHERE GalleryUnq = " . $iGalleryUnq);
							If ( $sSuccess == "" )
								$sSuccess = "Successfully deleted gallery information.<br>";
						}Else{
							If ( $sError == "" )
								$sError = "Sorry but you are not the owner of this gallery and cannot delete it.<br>";
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
		
		If ( $sAction == "UpdateCategoryUnq" ) {
			$iDBLoc			= 0;
			$iTtlNumItems	= 0;
		}ElseIf ( $sAction == "DeleteGallery" ) {
			$iTtlNumItems	= 0;
		}
			
		// Get ttl num of items from the database if it's not already in the QueryString
		if ( $iTtlNumItems == 0 ) {
			If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				// get all galleries from all users
				$sQuery = "SELECT Count(*) FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq;
			}Else{
				// just get galleries from the current user
				$sQuery = "SELECT Count(*) FROM Galleries (NOLOCK) WHERE AccountUnq = " . $iLoginAccountUnq . " AND CategoryUnq = " . $iCategoryUnq;
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
		Global $iDBLoc;
		Global $iTtlNumItems;
		Global $iNumPerPage;
		Global $iCategoryUnq;
		Global $bHasGalleryProdRights;
		Global $bHasCR_Rights;
		Global $iThumbComponent;
		Global $GFL;
		Global $ASPIMAGE;
		Global $bIsAdmin;
		Global $bReferenceDomain;
		Global $iLoginAccountUnq;
		Global $bAricaur;
		
		$sBGColor = $GLOBALS["BGColor2"];

		?>
		<form name='ManageGalleries' action='index.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "iTtlNumItems";
		$aVariables[2] = "iDBLoc";
		$aVariables[3] = "iMoveGalleryUnq";
		$aVariables[4] = "iMovePosition";
		$aValues[0] = "New";
		$aValues[1] = $iTtlNumItems;
		$aValues[2] = $iDBLoc;
		$aValues[3] = "";
		$aValues[4] = "";
		Echo DOMAIN_Link("P");
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Manage Image Galleries</b></font>
					<br><br>
					<table width=671>
						<tr>
							<td><font color='<?=$GLOBALS["PageText"]?>'><b>Category:</b></font></td>
						</tr>
						<tr>
							<td>
								<select name = "iCategoryUnq" onChange='SubmitForm("UpdateCategoryUnq");'>
									<?php 
									$iCatLevel	= 0;
									$bGotOne	= FALSE;
									CategoryUnqDropDown($iCategoryUnq, 0);
									?>
								</select>
							</td>
						</tr>
					</table>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>ID</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Name</b></td>
							<td bgcolor=<?=$sBGColor?>>&nbsp;</td>
							<td bgcolor=<?=$sBGColor?>>&nbsp;</td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Delete</b></td>
							<?php If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) ) {?>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Recreate<br>Thumbnails*</b></td>
							<?php }?>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Order</b></td>
						</tr>
						<?php 
						$sColor1	= $GLOBALS["BGColor1"];
						$sColor2	= $GLOBALS["PageBGColor"];
						$sColor3	= $GLOBALS["PageText"];
						$sColor4	= $GLOBALS["TextColor1"];
						$sBGColor	= $GLOBALS["BGColor2"];
						
						If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
							// get all galleries from all users
							$sQuery = "SELECT * FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq . " ORDER BY Position";
						}Else{
							// just get galleries from the current user
							$sQuery = "SELECT * FROM Galleries (NOLOCK) WHERE AccountUnq = " . $iLoginAccountUnq . " AND CategoryUnq = " . $iCategoryUnq . " ORDER BY Position";
						}
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
								$sLinkColor = "MediumNavPage";
							}Else{
								$sBGColor = $sColor1;
								$sTextColor = $sColor4;
								$sLinkColor = "MediumNav1";
							}
							$sName 			= $rsRow["Name"];
							$iGalleryUnq 	= $rsRow["GalleryUnq"];
							$sPosition		= $rsRow["Position"];
							
							// just as a precaution in case anyone changes their login name, update the galleries UserName column here
							DB_Update ("UPDATE Galleries SET UserName = '" . Trim(ACCNT_UserName($rsRow["AccountUnq"])) . "' WHERE AccountUnq = " . $rsRow["AccountUnq"]);
							
							?>
							<tr>
								<td bgcolor=<?=$sBGColor?> align=center valign=top><font color='<?=$sTextColor?>'><?=$iGalleryUnq?></td>
								<td bgcolor=<?=$sBGColor?> valign=top width=10><input type='hidden' name="sOldName<?=$iGalleryUnq?>" value="<?=htmlentities($sName)?>"><input type='text' name="sNewName<?=$iGalleryUnq?>" value="<?=htmlentities($sName)?>" size=35 maxlength=32></td>
								<td bgcolor=<?=$sBGColor?> valign=top width=50><a href='Edit.php?iDBLoc=<?=$iDBLoc?>&iGalleryUnq=<?=$iGalleryUnq?>' class='<?=$sLinkColor?>'>Edit</a></td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<?php If ( $bHasGalleryProdRights ) {?>
									<a href='EditProducts.php?iDBLoc=<?=$iDBLoc?>&iGalleryUnq=<?=$iGalleryUnq?>' class='<?=$sLinkColor?>'>Edit Products</a><br>
									<?php }?>
									<?php If ( $bHasCR_Rights ) {?>
									<a href='EditCopyrights.php?iDBLoc=<?=$iDBLoc?>&iGalleryUnq=<?=$iGalleryUnq?>' class='<?=$sLinkColor?>'>Edit Copyrights</a><br>
									<?php }?>
									<?php If ( $bReferenceDomain ) {?>
									<a href='Referencing.php?iDBLoc=<?=$iDBLoc?>&iGalleryUnq=<?=$iGalleryUnq?>' class='<?=$sLinkColor?>'>Referencing</a><br>
									<?php }?>
									<a href='EditLinks.php?iDBLoc=<?=$iDBLoc?>&iGalleryUnq=<?=$iGalleryUnq?>' class='<?=$sLinkColor?>'>Edit Links</a>
									<?php If ( $bAricaur ) {?>
									<br><a href='EditAricaur.php?iDBLoc=<?=$iDBLoc?>&iGalleryUnq=<?=$iGalleryUnq?>' class='<?=$sLinkColor?>'>Aricaur Links</a><br>
									<?php }?>
								</td>
								<td align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name="sDelete<?=$iGalleryUnq?>" value="<?=$iGalleryUnq?>"></td>
								<?php If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) ) {?>
								<td align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name="sRecreateThumbnails<?=$iGalleryUnq?>" value="<?=$iGalleryUnq?>"></td>
								<?php }?>
								<td valign=top align=center bgcolor=<?=$sBGColor?>>
									<Table cellpadding=0 cellspacing=0 border=0>
										<?php 
										If ( $sPosition > 1 ) {?>
										<tr><td><a href='JavaScript:ReorderGalleries(<?=$sPosition?>, <?=$iGalleryUnq?>, "MoveUp")'><img src='../../Images/Administrative/MoveUp.gif' border=0 width=12 height=6 alt=' Move gallery up one '></a></td></tr>
										<?php }?>
										<tr><td><img src='../../Images/Blank.gif' border=0 width=12 height=6></td></tr>
										<?php If ( ( $sPosition < $iTtlNumItems ) && ( $iTtlNumItems > 1 ) ) {?>
										<tr><td><a href='JavaScript:ReorderGalleries(<?=$sPosition?>, <?=$iGalleryUnq?>, "MoveDown")'><img src='../../Images/Administrative/MoveDown.gif' border=0 width=12 height=6 alt=' Move gallery down one '></a></td></tr>
										<?php }?>
									</table>
								</td>
							</tr>
							<?php 
						}
						?>
						<tr>
							<?php If ( $bIsAdmin ) {?>
							<td colspan=7 align=right>
							<?php }Else{?>
							<td colspan=6 align=right>
							<?php }?>
								<?php PrintRecordsetNav_ADMIN( "index.php", "", "Galleries" ); ?>
							</td>
						</tr>
					</table>
					* Recreating thumbnails only processes images from the gallery -- it will not process referenced images.
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
	Function CategoryUnqDropDown(&$iCatUnq, $iCurCatUnq)
	{
		Static $iCatLevel = 0;
		Global $iCategoryUnq;
		
		$sQuery			= "SELECT * FROM IGCategories (NOLOCK) WHERE Parent = " . $iCurCatUnq . " ORDER BY Position";
		$rsRecordSet	= DB_Query($sQuery);
		If ( DB_NumRows($rsRecordSet) > 0 )
		{
			If ( ACCNT_ReturnRights("PHPJK_IG_ADD_TO_NO_CAT") ) {
				// only display this if they are allowed to add galleries to "no category"
				If ( ( $iCatLevel == 0 ) && ( $iCurCatUnq == 0 ) ) {
					Echo "<option value='0'>Galleries not in a category (" . NumGalleries(0) . ")</option>";
					$bGotOne = TRUE;
				}
			}
			While ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$iCurCatUnq = $rsRow["CategoryUnq"];
				If ( ( ACCNT_ReturnRights($rsRow["RightsLvl"]) ) || ( Trim($rsRow["RightsLvl"]) == "" ) ) {
					If ( $iCatUnq == $iCurCatUnq ) {
						Echo "<option value='" . $iCurCatUnq . "' Selected>" . str_repeat (CHR(151), $iCatLevel) . $rsRow["Name"] . " (" . NumGalleries($iCurCatUnq) . ")</option>";
					}Else{
						Echo "<option value='" . $iCurCatUnq . "'>" . str_repeat (CHR(151), $iCatLevel) . $rsRow["Name"] . " (" . NumGalleries($iCurCatUnq) . ")</option>";
					}
					If ( ! $bGotOne ) // get the first one it returns
						//$iCategoryUnq = $iCurCatUnq;	// set the global iCategoryUnq to the current categoryunq so that it'll automatically bring up galleries within the selected category if it isn't already the default
					$bGotOne = TRUE;
				}
				$iCatLevel++;
				CategoryUnqDropDown($iCatUnq, $iCurCatUnq);
				$iCatLevel--;
			}
		}Else{
			If ( ACCNT_ReturnRights("PHPJK_IG_ADD_TO_NO_CAT") ) {
				// only display this if they are allowed to add galleries to "no category"
				If ( ( $iCatLevel == 0 ) && ( $iCurCatUnq == 0 ) ) {
					Echo "<option value='0'>Galleries not in a category (" . NumGalleries(0) . ")</option>";
					If ( ! $bGotOne ) 	// get the first one it returns
						$iCategoryUnq = $iCurCatUnq;	// set the global iCategoryUnq to the current categoryunq so that it'll automatically bring up galleries within the selected category if it isn't already the default
					$bGotOne = TRUE;
				}
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the number of galleries in the category.								*
	//*																					*
	//************************************************************************************
	Function NumGalleries($iCatUnq)
	{
		$sQuery			= "SELECT Count(*) FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iCatUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
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
		Global $iDBLoc;
		Global $iTtlNumItems;
		Global $iCategoryUnq;
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageGalleries.sAction.value = sAction;
				document.ManageGalleries.submit();
			}
			
			function PaginationLink(sQueryString){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iCategoryUnq=<?=$iCategoryUnq?>" + sQueryString;
			}
			
			function NewGallery(){
				document.location = "New.php?<?=DOMAIN_Link("G")?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
			}
			
			function MoveGalleries(){
				document.location = "Move.php?<?=DOMAIN_Link("G")?>&iCategoryUnq=<?=$iCategoryUnq?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
			}
			
			function ReorderGalleries(iMovePosition, iMoveGalleryUnq, sAction){
				document.ManageGalleries.iMoveGalleryUnq.value=iMoveGalleryUnq;
				document.ManageGalleries.iMovePosition.value=iMovePosition;
				SubmitForm(sAction);
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
				If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/UpdateGallery.gif' ALIGN='absmiddle' Width=31 Height=44 Border=0 Alt='Save changes to galleries.' onClick='SubmitForm(\"UpdateGallery\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='delete' SRC='../../Images/Administrative/DelGallery.gif' ALIGN='absmiddle' Width=27 Height=39 Border=0 Alt='Delete checked galleries.' onClick='SubmitForm(\"DeleteGallery\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:NewGallery();'><img src='../../Images/Administrative/NewGallery.gif' Width=53 Height=43 Border=0 Alt='Add a new gallery.'></a></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:MoveGalleries();'><img src='../../Images/Administrative/MoveGalleries.gif' Width=65 Height=49 Border=0 Alt='Move multiple galleries from category to category.'></a></td>";
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
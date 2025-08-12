<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	Global $iLoginAccountUnq;
	
	$iTemp = DOMAIN_Conf("IMAGEGALLERY_SCRIPTTIMEOUT");
	If ( $iTemp = "" )
		$iTemp = 5400;
	set_time_limit($iTemp);

	$sAction			= Trim(Request("sAction"));
	$iTtlNumItems		= Trim(Request("iTtlNumItems"));
	$iDBLoc				= Trim(Request("iDBLoc"));
	$iGalleryUnq		= Trim(Request("iGalleryUnq"));
	$iImageUnq			= Trim(Request("iImageUnq"));
	$sAltImageNum		= Trim(Request("sAltImageNum"));
	
	// variables used when modifying existing images
	$sOldDescription2	= Trim(Request("sOldDescription2"));
	$sNewDescription2	= Trim(Request("sNewDescription2"));
	$sOldAltTag2		= Trim(Request("sOldAltTag2"));
	$sNewAltTag2		= Trim(Request("sNewAltTag2"));
	$sDelete2			= Trim(Request("sDelete2"));
	$sOldDescription3	= Trim(Request("sOldDescription3"));
	$sNewDescription3	= Trim(Request("sNewDescription3"));
	$sOldAltTag3		= Trim(Request("sOldAltTag3"));
	$sNewAltTag3		= Trim(Request("sNewAltTag3"));
	$sDelete3			= Trim(Request("sDelete3"));
	$sOldDescription4	= Trim(Request("sOldDescription4"));
	$sNewDescription4	= Trim(Request("sNewDescription4"));
	$sOldAltTag4		= Trim(Request("sOldAltTag4"));
	$sNewAltTag4		= Trim(Request("sNewAltTag4"));
	$sDelete4			= Trim(Request("sDelete4"));
	$sOldDescription5	= Trim(Request("sOldDescription5"));
	$sNewDescription5	= Trim(Request("sNewDescription5"));
	$sOldAltTag5		= Trim(Request("sOldAltTag5"));
	$sNewAltTag5		= Trim(Request("sNewAltTag5"));
	$sDelete5			= Trim(Request("sDelete5"));

	$sValidExtensions	= G_ADMINISTRATION_ValidFileExtensions($iLoginAccountUnq);
	$iMaxFileSize		= G_ADMINISTRATION_MaxFileSize($iLoginAccountUnq);
	$iHDSpaceLeft		= G_ADMINISTRATION_HDSpaceLeft($iLoginAccountUnq);
	$iThumbComponent	= G_ADMINISTRATION_ASPImageInstalled();
	$sAccountUnq		= G_ADMINISTRATION_GetGalleryOwner($iGalleryUnq);
	
	$sPixelsWide		= Trim(Request("sPixelsWide"));
	$sPercentWide		= Trim(Request("sPercentWide"));
	$sAltTag			= Trim(Request("sAltTag"));
	$sImageDesc			= Trim(Request("sImageDesc"));
	$sUseASPImage		= Trim(Request("sUseASPImage"));
	
	WriteScripts();
	
	If ((ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL")) || (ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY"))) {
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
		Global $iImageUnq;
		Global $iThumbComponent;
		Global $ASPIMAGE;
		Global $GFL;
		Global $sPixelsWide;
		Global $sPercentWide;
		Global $sAltImageNum;
		
		Global $sImageDesc;
		Global $sAltTag;
		Global $iXSize;
		Global $iYSize;
		Global $iFileSize;
		Global $sAction;
		Global $sUseASPImage;
		Global $sOldDescription2;
		Global $sNewDescription2;
		Global $sOldAltTag2;
		Global $sNewAltTag2;
		Global $sDelete2;
		Global $sOldDescription3;
		Global $sNewDescription3;
		Global $sOldAltTag3;
		Global $sNewAltTag3;
		Global $sDelete3;
		Global $sOldDescription4;
		Global $sNewDescription4;
		Global $sOldAltTag4;
		Global $sNewAltTag4;
		Global $sDelete4;
		Global $sOldDescription5;
		Global $sNewDescription5;
		Global $sOldAltTag5;
		Global $sNewAltTag5;
		Global $sDelete5;
		
		$sError			= "";
		$sSuccess		= "";
		$sSuccess[2]	= "";
		$sSuccess[3]	= "";
		$sSuccess[4]	= "";
		$sSuccess[5]	= "";
		
		If ( $sAction == "New" )
		{
			If ( ! ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				If ( ! ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") )
					$sError = "Please log in with Image Gallery management rights.";
			}

			If ( $sError == "" )
			{
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{
					If ( ( $sUseASPImage == "N" ) || ( $sUseASPImage == "" ) )
					{
						// they uploaded a file, or nothing
						$sFileName	= trim($_FILES["File1"]["name"]);
						If ( $sFileName != "" )
						{
							$sError = $sError . G_UPLOAD_SaveFile("File1", "ALTERNATE", $sFileName);	// this function has the possibility of changing the file name, so must pass in by reference
							If ( $sError == "" )
								If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) )
									$sError = $sError . G_UPLOAD_GetDimensions($sFileName);
						}Else{
							$sError = "Please enter a file to upload.";
						}
					}Else{
						If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) )
						{
							If ( ( !is_numeric($sPixelsWide) ) && ( $sPixelsWide != "" ) )
								$sError = "Please enter a numeric value for the number of pixels wide to make the image.";
							If ( ( !is_numeric($sPercentWide) ) && ($sPercentWide != ""  ) )
								$sError = "Please enter a numeric value for the percent wide to make the image.";
							If ( ( $sPixelsWide == "" ) && ($sPercentWide == ""  ) )
								$sError = "Please enter either a pixel or percent width for the new image.";
							If ( ( $sPixelsWide != "" ) && ($sPercentWide != ""  ) )
								$sError = "Please enter either a pixel or percent width for the new image. But not both.";

							If ( $sError == "" )
							{
								If ( ( intval($sPixelsWide) <= 0 ) && ( $sPixelsWide != "" ) )
									$sError = "Please enter a positive value for the number of pixels wide.";
								If ( ( intval($sPercentWide) <= 0 ) && ( $sPercentWide != "" ) )
									$sError = "Please enter a positive value for the percent wide.";
								If ( $sError == "" )
								{
									/* they want ASPImage or GFL to make the file for them
									 get the Primary Image name so we can use it to make the resized copy*/
									$sQuery			= "SELECT Image, XSize, YSize FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
									$rsRecordSet	= DB_Query($sQuery);
									If ( $rsRow = DB_Fetch($rsRecordSet) ) {
										$sFileName	= "";
										$sError		= $sError . G_UPLOAD_MakeFile(Trim($rsRow["Image"]), $sPixelsWide, $sPercentWide, 0, $rsRow["XSize"], $rsRow["YSize"], $sFileName);
									}Else{
										$sError = $sError . "Unable to find the Primary Image to make a resized copy of it.<br>";
									}
								}
							}
						}
					}
					If ( $sError == "" )
					{
						// if it is not an image type (txt pdf etc)
						If ( $iFileSize == "" )
							$iFileSize = "0";
						If ( $iXSize == "" )
							$iXSize = "0";
						If ( $iYSize == "" )
							$iYSize = "0";

						DB_Update ("UPDATE Images SET Image" . $sAltImageNum . " = '" . SQLEncode($sFileName) . "', Image" . $sAltImageNum . "Desc = '" . SQLEncode($sImageDesc) . "', AltTag" . $sAltImageNum . " = '" . SQLEncode($sAltTag) . "', XSize" . $sAltImageNum . " = '" . $iXSize . "', YSize" . $sAltImageNum . " = '" . $iYSize . "', ImageSize" . $sAltImageNum . " = " . $iFileSize . ", Alt" . $sAltImageNum . "UL = " . $iLoginAccountUnq . " WHERE ImageUnq = " . $iImageUnq);
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot add Alternate Images to images within it.<br>";
				}
			}
		}ElseIf ( $sAction == "Update" ){
			If ( ! ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				If ( ! ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") )
					$sError = "Please log in with Image Gallery management rights.";
			}

			If ( $sError == "" )
			{
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{
					If ( $sDelete2 == "2" )
					{
						DeleteAlternate("2", $iImageUnq);
						$sSuccess[2] = DeleteAricaur("2", $iImageUnq, $iGalleryUnq);
						DB_Update ("UPDATE Images SET Image2 = '', Image2Desc = '', AltTag2 = '', XSize2 = '', YSize2 = '', ImageSize2 = '', Alt2UL = 0 WHERE ImageUnq = " . $iImageUnq);
					}Else{
						If ( $sOldDescription2 != $sNewDescription2 )
							DB_Update ("UPDATE Images SET Image2Desc = '" . SQLEncode($sNewDescription2) . "' WHERE ImageUnq = " . $iImageUnq);
						If ( $sOldAltTag2 != $sNewAltTag2 )
							DB_Update ("UPDATE Images SET AltTag2 = '" . SQLEncode($sNewAltTag2) . "' WHERE ImageUnq = " . $iImageUnq);
					}
					
					If ( $sDelete3 == "3" ) 
					{
						DeleteAlternate("3", $iImageUnq);
						$sSuccess[3] = DeleteAricaur("3", $iImageUnq, $iGalleryUnq);
						DB_Update ("UPDATE Images SET Image3 = '', Image3Desc = '', AltTag3 = '', XSize3 = '', YSize3 = '', ImageSize3 = '', Alt3UL = 0 WHERE ImageUnq = " . $iImageUnq);
					}Else{
						If ( $sOldDescription3 != $sNewDescription3 )
							DB_Update ("UPDATE Images SET Image3Desc = '" . SQLEncode($sNewDescription3) . "' WHERE ImageUnq = " . $iImageUnq);
						If ( $sOldAltTag3 != $sNewAltTag3 )
							DB_Update ("UPDATE Images SET AltTag3 = '" . SQLEncode($sNewAltTag3) . "' WHERE ImageUnq = " . $iImageUnq);
					}
					
					If ( $sDelete4 == "4" ) 
					{
						DeleteAlternate("4", $iImageUnq);
						$sSuccess[4] = DeleteAricaur("4", $iImageUnq, $iGalleryUnq);
						DB_Update ("UPDATE Images SET Image4 = '', Image4Desc = '', AltTag4 = '', XSize4 = '', YSize4 = '', ImageSize4 = '', Alt4UL = 0 WHERE ImageUnq = " . $iImageUnq);
					}Else{
						If ( $sOldDescription4 != $sNewDescription4 )
							DB_Update ("UPDATE Images SET Image4Desc = '" . SQLEncode($sNewDescription4) . "' WHERE ImageUnq = " . $iImageUnq);
						If ( $sOldAltTag4 != $sNewAltTag4 )
							DB_Update ("UPDATE Images SET AltTag4 = '" . SQLEncode($sNewAltTag4) . "' WHERE ImageUnq = " . $iImageUnq);
					}
					
					If ( $sDelete5 == "5" ) 
					{
						DeleteAlternate("5", $iImageUnq);
						$sSuccess[5] = DeleteAricaur("5", $iImageUnq, $iGalleryUnq);
						DB_Update ("UPDATE Images SET Image5 = '', Image5Desc = '', AltTag5 = '', XSize5 = '', YSize5 = '', ImageSize5 = '', Alt5UL = 0 WHERE ImageUnq = " . $iImageUnq);
					}Else{
						If ( $sOldDescription5 != $sNewDescription5 )
							DB_Update ("UPDATE Images SET Image5Desc = '" . SQLEncode($sNewDescription5) . "' WHERE ImageUnq = " . $iImageUnq);
						If ( $sOldAltTag5 != $sNewAltTag5 )
							DB_Update ("UPDATE Images SET AltTag5 = '" . SQLEncode($sNewAltTag5) . "' WHERE ImageUnq = " . $iImageUnq);
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot modify Alternate Images within it.<br>";
				}
			}
		}
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		$sSuccess = $sSuccess[2] . $sSuccess[3] . $sSuccess[4] . $sSuccess[5];
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
		Global $sValidExtensions;
		Global $iGalleryUnq;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $sAltImageNum;
		Global $iImageUnq;
		Global $iThumbComponent;
		Global $ASPIMAGE;
		Global $GFL;
		Global $aVariables;
		Global $aValues;
		Global $iTextScheme;
		Global $iColorScheme;
		Global $sUseASPImage;
		Global $sPixelsWide;
		Global $sPercentWide;
		Global $sAltTag;
		Global $sImageDesc;
		Global $iTableWidth;
	
		$sBGColor		= $GLOBALS["BGColor1"];
		$sTextColor		= $GLOBALS["TextColor1"];
		
		$sQuery			= "SELECT * FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			If ( Trim($rsRow["Image2"]) == "" ) {
				$sAltImageNum = "2";
			}ElseIf ( Trim($rsRow["Image3"]) == "" ) {
				$sAltImageNum = "3";
			}ElseIf ( Trim($rsRow["Image4"]) == "" ) {
				$sAltImageNum = "4";
			}ElseIf ( Trim($rsRow["Image5"]) == "" ) {
				$sAltImageNum = "5";
			}Else{
				$sAltImageNum = "-1";
			}
			?>
			<script language='JavaScript1.2' type='text/javascript'>
			
				<?php 
				// get array for JavaScript of all the allowed file types
				Echo "extArray = new Array(";
				$aTypes = explode(" ", $sValidExtensions);
				For ( $x = 0; $x < Count($aTypes); $x++ )
					Echo "\"." . $aTypes[$x] . "\",";
				Echo "\"\");"
				?>
	
				function LimitAttach()
				{
					file = document.NewImage.File1.value;
					
					allowSubmit = false;
					if (!file) return true;
					while (file.indexOf("\\") != -1)
					file = file.slice(file.indexOf("\\") + 1);
					ext = file.slice(file.indexOf(".")).toLowerCase();
					
					for (var i = 0; i < extArray.length; i++) {
						if (extArray[i] == ext) 
						{ 
							allowSubmit = true; 
							break;
						}
					}
					
					if (allowSubmit) 
					{
						return true;
					}else{
						alert("Please only upload files that end in types:  " + (extArray.join("  ")) + "\nPlease select a new file to upload and submit again.");
						return false;
					}
				}
				
			</script>
	
			<table cellpadding=0 cellspacing=0 border=0 width=671>
				<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
				<tr><td>
						<font color='<?=$GLOBALS["PageText"]?>'>
						<b>Adding alternate image to image:</b> <?=ReturnImageName($iImageUnq)?>
						<br><br>
						Up to four additional images may be associated with this image entry. 
						The other images, or "Alternate View Images" can be things like various size 
						copies of the original image or different angles.
				</td></tr>
				<tr>
					<td>
						<br>
						<?php 
						If ( $sAltImageNum != -1 )
						{
							$iTableWidth = 671;
							?>
							<form ENCTYPE="multipart/form-data" onsubmit="return LimitAttach()" name='NewImage' action='AltViews.php' method='post'>
							<input type='hidden' name='sAltImageNum' value='<?=$sAltImageNum?>'>
							<input type='hidden' name='iImageUnq' value='<?=$iImageUnq?>'>
							<input type='hidden' name='iGalleryUnq' value='<?=$iGalleryUnq?>'>
							<input type='hidden' name='iTtlNumItems' value='<?=$iTtlNumItems?>'>
							<input type='hidden' name='iDBLoc' value='<?=$iDBLoc?>'>
							
							<input type='hidden' name='sAction' value='New'>
							<?php G_STRUCTURE_HeaderBar_ADMIN("AddAltImagesHead.gif", "", "", "Galleries");?>
							<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
							<table cellpadding = 5 cellspacing=0 border=0 width=671>
								<tr>
									<td bgcolor = <?=$sBGColor?>>
										<table width=100%>
											<tr>
												<td valign=top><font color='<?=$sTextColor?>'><b>Upload Alternate View Image:</td>
												<td>
													<input TYPE='FILE' NAME='File1' SIZE=30>
													<?php DisplayImageInfo();?>
												</td>
											</tr>
											<?php If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) ) {?>
											<tr><td valign=top colspan=2 align=center><font color='<?=$sTextColor?>'><b>&#151; OR &#151;</td></tr>
											<tr>
												<td colspan=2>
													<table cellpadding = 0 cellspacing=0 border=0>
														<tr>
															<td><font color='<?=$sTextColor?>'><b>
																Would you like a different size copy of the Primary Image to be automatically created for you?
																<?php 
																If ( $iThumbComponent == $ASPIMAGE ) {
																	Echo "Check this box if you would like to have a thumbnail (" . DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH") . " pixels wide) automatically created for this image (this only works if you upload a .jpg, .bmp, or .png image type as your Primary Image)?";
																}ElseIf ( $iThumbComponent == $GFL ) {
																	Echo "</b><font size=-2><BR>Supported file types include: &nbsp;";
																	Echo GFL_Supported($sValidExtensions);
																}ElseIf ( $iThumbComponent == "PHP" ){
																	Echo "</b><font size=-2><BR>Supported file types include: GIF, JPG, PNG, BMP.";
																}
																?>
															</td>
															<td>&nbsp;&nbsp;&nbsp;</td>
															<td><font color='<?=$sTextColor?>'><b>Yes:</td>
															<td><input type='radio' name='sUseASPImage' value='Y' <?php If ( $sUseASPImage == "Y" )  Echo "checked";?>></td>
															<td>&nbsp;</td>
															<td><font color='<?=$sTextColor?>'><b>No:</td>
															<td><input type='radio' name='sUseASPImage' value='N' <?php If (( $sUseASPImage == "N" ) || ( $sUseASPImage == "" ))  Echo "checked";?>></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td colspan=2>
													<table width=100% cellpadding = 0 cellspacing=0 border=0>
														<tr>
															<td><font color='<?=$sTextColor?>'><b>If so, how wide would you like this image to be (the height will be determined by the width)?</td>
															<td align=right><font color='<?=$sTextColor?>'><b>Pixels wide:</td>
															<td>&nbsp;</td>
															<td width=1><input type='text' name='sPixelsWide' value='<?=$sPixelsWide?>' size=8 maxlength=8></td>
															<td width=1><font color='<?=$sTextColor?>'><b><u>OR</u>&nbsp;</td>
															<td align=right><font color='<?=$sTextColor?>'><b>Percent width of the primary image:</td>
															<td>&nbsp;</td>
															<td width=1><input type='text' name='sPercentWide' value='<?=$sPercentWide?>' size=8 maxlength=8></td>
														</tr>
													</table>
												</td>
											</tr>
											<tr><td valign=top colspan=2 align=center><font color='<?=$sTextColor?>'><b><br><br></td></tr>
											<?php }?>
											<tr>
												<td valign=top><font color='<?=$sTextColor?>'><b>Alt Tag:</td>
												<td><input type='text' name='sAltTag' value="<?=htmlentities($sAltTag)?>" size=70 maxlength=250></td>
											</tr>
											<tr>
												<td valign=top><font color='<?=$sTextColor?>'><b>Short Description:</td>
												<td><input type='text' name='sImageDesc' value="<?=htmlentities($sImageDesc)?>" size=70 maxlength=250></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td bgcolor = <?=$GLOBALS["BGColor2"]?> align=center>
										<input type='image' src="../../Images/SchemeBased/<?=$iTextScheme?>/<?=$iColorScheme?>/AddAlternate.gif" style="BORDER: none; vertical-align: sub;" onClick='document.NewImage.sAction.value="New"'>
									</td>
								</tr>
							</table>
							</td></tr></table>
							</form>
						<?php }Else{?>
							All of the alternate images for this image have been populated. 
							If you would like to add another alternate image, please remove an existing one first.
							<br><br>
						<?php }?>
						
						<form name='UpdateImages' action='AltViews.php' method='post'>
						<?php G_STRUCTURE_HeaderBar_ADMIN("UpdateAltsHead.gif", "", "", "Galleries");?>
						<?php 
						$aVariables[0] = "sAction";
						$aVariables[1] = "iTtlNumItems";
						$aVariables[2] = "iDBLoc";
						$aVariables[3] = "sAltImageNum";
						$aVariables[4] = "iImageUnq";
						$aVariables[5] = "iGalleryUnq";
						$aValues[0] = "Update";
						$aValues[1] = $iTtlNumItems;
						$aValues[2] = $iDBLoc;
						$aValues[3] = $sAltImageNum;
						$aValues[4] = $iImageUnq;
						$aValues[5] = $iGalleryUnq;
						Echo DOMAIN_Link("P");
						?>
						<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
						<table cellpadding = 0 cellspacing=0 border=0 width=671>
							<tr>
								<td bgcolor = <?=$sBGColor?>>
									<table cellpadding=2 cellspacing=0 border=0 width=671>
										<tr>
											<td bgcolor=<?=$sBGColor?> valign=bottom><font color='<?=$sTextColor?>'><b>Small Rendition<br><font size=-2>(not an actual thumbnail)</td>
											<td bgcolor=<?=$sBGColor?> valign=bottom><font color='<?=$sTextColor?>'><b>Image Name</b></td>
											<td bgcolor=<?=$sBGColor?> valign=bottom><font color='<?=$sTextColor?>'><b>Description</b></td>
											<td bgcolor=<?=$sBGColor?> valign=bottom><font color='<?=$sTextColor?>'><b>Alt Tag</b></td>
											<td align=center bgcolor=<?=$sBGColor?> valign=bottom><font color='<?=$sTextColor?>'><b>Delete</b></td>
										</tr>
										<?php 
										If ( Trim($rsRow["Image2"]) != "" ) {
											?>
											<tr>
												<td bgcolor=<?=$sBGColor?> align=center valign=top width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?>>
													<?php DisplayAsThumb($rsRow["Image2"],2);?>
												</td>
												<td bgcolor=<?=$sBGColor?> valign=top><font color='<?=$sTextColor?>' size=-2><?=htmlentities($rsRow["Image2"])?></td>
												<td bgcolor=<?=$sBGColor?> valign=top>
													<input type='hidden' name='sOldDescription2' value="<?=htmlentities($rsRow["Image2Desc"])?>">
													<input type='text' name='sNewDescription2' value="<?=htmlentities($rsRow["Image2Desc"])?>" size=15 maxlength=250>
												</td>
												<td bgcolor=<?=$sBGColor?> valign=top>
													<input type='hidden' name='sOldAltTag2' value="<?=htmlentities($rsRow["AltTag2"])?>">
													<input type='text' name='sNewAltTag2' value="<?=htmlentities($rsRow["AltTag2"])?>" size=15 maxlength=250>
												</td>
												<td align=center bgcolor=<?=$sBGColor?> valign=top><input type='checkbox' name="sDelete2" value="2"></td>
											</tr>
											<?php 
										}
										If ( Trim($rsRow["Image3"]) != "" ) {
											?>
											<tr>
												<td bgcolor=<?=$sBGColor?> align=center valign=top width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?>>
													<?php DisplayAsThumb($rsRow["Image3"],3);?>
												</td>
												<td bgcolor=<?=$sBGColor?> valign=top><font color='<?=$sTextColor?>' size=-2><?=htmlentities($rsRow["Image3"])?></td>
												<td bgcolor=<?=$sBGColor?> valign=top>
													<input type='hidden' name='sOldDescription3' value="<?=htmlentities($rsRow["Image3Desc"])?>">
													<input type='text' name='sNewDescription3' value="<?=htmlentities($rsRow["Image3Desc"])?>" size=15 maxlength=250>
												</td>
												<td bgcolor=<?=$sBGColor?> valign=top>
													<input type='hidden' name='sOldAltTag3' value="<?=htmlentities($rsRow["AltTag3"])?>">
													<input type='text' name='sNewAltTag3' value="<?=htmlentities($rsRow["AltTag3"])?>" size=15 maxlength=250>
												</td>
												<td align=center bgcolor=<?=$sBGColor?> valign=top><input type='checkbox' name="sDelete3" value="3"></td>
											</tr>
											<?php 
										}
										If ( Trim($rsRow["Image4"]) != "" ) {
											?>
											<tr>
												<td bgcolor=<?=$sBGColor?> align=center valign=top width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?>>
													<?php DisplayAsThumb($rsRow["Image4"],4);?>
												</td>
												<td bgcolor=<?=$sBGColor?> valign=top><font color='<?=$sTextColor?>' size=-2><?=htmlentities($rsRow["Image4"])?></td>
												<td bgcolor=<?=$sBGColor?> valign=top>
													<input type='hidden' name='sOldDescription4' value="<?=htmlentities($rsRow["Image4Desc"])?>">
													<input type='text' name='sNewDescription4' value="<?=htmlentities($rsRow["Image4Desc"])?>" size=15 maxlength=250>
												</td>
												<td bgcolor=<?=$sBGColor?> valign=top>
													<input type='hidden' name='sOldAltTag4' value="<?=htmlentities($rsRow["AltTag4"])?>">
													<input type='text' name='sNewAltTag4' value="<?=htmlentities($rsRow["AltTag4"])?>" size=15 maxlength=250>
												</td>
												<td align=center bgcolor=<?=$sBGColor?> valign=top><input type='checkbox' name="sDelete4" value="4"></td>
											</tr>
											<?php 
										}
										If ( Trim($rsRow["Image5"]) != "" ) {
											?>
											<tr>
												<td bgcolor=<?=$sBGColor?> align=center valign=top width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?>>
													<?php DisplayAsThumb($rsRow["Image5"],5);?>
												</td>
												<td bgcolor=<?=$sBGColor?> valign=top><font color='<?=$sTextColor?>' size=-2><?=htmlentities($rsRow["Image5"])?></td>
												<td bgcolor=<?=$sBGColor?> valign=top>
													<input type='hidden' name='sOldDescription5' value="<?=htmlentities($rsRow["Image5Desc"])?>">
													<input type='text' name='sNewDescription5' value="<?=htmlentities($rsRow["Image5Desc"])?>" size=15 maxlength=250>
												</td>
												<td bgcolor=<?=$sBGColor?> valign=top>
													<input type='hidden' name='sOldAltTag5' value="<?=htmlentities($rsRow["AltTag5"])?>">
													<input type='text' name='sNewAltTag5' value="<?=htmlentities($rsRow["AltTag5"])?>" size=15 maxlength=250>
												</td>
												<td align=center bgcolor=<?=$sBGColor?> valign=top><input type='checkbox' name="sDelete5" value="5"></td>
											</tr>
											<?php 
										}
										?>
										<tr>
											<td bgcolor = <?=$GLOBALS["BGColor2"]?> align=center colspan=5>
												<input type='image' src="../../Images/SchemeBased/<?=$iTextScheme?>/<?=$iColorScheme?>/UpdateAlternates.gif" style="BORDER: none; vertical-align: sub;" onClick='document.UpdateImages.sAction.value="Update"'>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						</td></tr></table>
						</form>
					</td>
				</tr>
				<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			</table>
			<?php 
		}Else{
			DOMAIN_Message("Unable to find this image in the database.", "ERROR");
		}
		
	}
	//************************************************************************************

	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayImageInfo()
	{
		Global $iHDSpaceLeft;
		Global $sValidExtensions;
		Global $iMaxFileSize;
		Global $iULLeft;
		
		Echo "<font color='" . $GLOBALS["TextColor1"] . "' size=-2><br>";
		Echo "Valid file extensions: " . $sValidExtensions;
		Echo "<br>Maximum file size: ";
		If ( $iMaxFileSize == -1 ) {
			Echo "Unlimited";
		}Else{
			Echo $iMaxFileSize;
		}
		Echo "<br>Disk space left: ";
		If ( $iHDSpaceLeft == -1 ) {
			Echo "Unlimited";
		}Else{
			Echo $iHDSpaceLeft;
		}
		If ( $iULLeft > 0 )
			Echo "<br>You may add " . $iULLeft . " more images.";
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
	//*																					*
	//*																					*
	//************************************************************************************
	Function DeleteAlternate($sAltImageNum, $iImageUnq)
	{
		Global $sGalleryPath;
		Global $iGalleryUnq;
		
		// need to get from the db the image name then delete the image
		$sQuery			= "SELECT G.AccountUnq, I.Image" . $sAltImageNum . ", I.Alt" . $sAltImageNum . "UL FROM Images I (NOLOCK), Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE I.ImageUnq = " . $iImageUnq . " AND IG.ImageUnq = " . $iImageUnq . " AND IG.GalleryUnq = G.GalleryUnq";
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$sAccountUnq	= $rsRow["AccountUnq"];
			$sImageName		= $rsRow["Image" . $sAltImageNum];
			$iAltUL			= $rsRow["Alt" . $sAltImageNum . "UL"];

			$sFilePath	= $sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\" . $sImageName;
			$sFilePath	= str_replace("\\", "/", $sFilePath);
			$sFilePath	= str_replace("//", "/", $sFilePath);
			If ( file_exists($sFilePath) )
			{
				$iFileSize = filesize($sFilePath);
				
				// the file exists - delete it
				unlink($sFilePath);
				
				// subtract the num of bytes of the file from the users ttl uploaded bytes
				G_ADMINISTRATION_IncrementHDSpaceUsed($iAltUL, (-1 * $iFileSize));
			}
		}
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayAsThumb($sImage, $sImageNum)
	{
		Global $bIsImage;
		Global $sType;
		Global $iImageUnq;
		Global $sAccountUnq;
		Global $sGalleryPath;
		Global $iThumbWidth;
		Global $iGalleryUnq;
		Global $sSiteURL;
		
		$iThumbWidth = DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH");
		
		$sType = "";
		G_STRUCTURE_FileType($sType, $bIsImage, $iImageUnq, "../../../../", $sImageNum);

		$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/" . $sImage;
		$sFilePath	= str_replace("\\", "/", $sFilePath);
		$sFilePath	= str_replace("//", "/", $sFilePath);

		If ( file_exists($sFilePath) )
		{
			If ( $bIsImage ) {
				If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DOMAINCHECK"))) == "YES" ) {
					?>
					<img src = "<?=$sSiteURL?>/Attachments/DispAsThumb.php?sAccountUnq=<?=$sAccountUnq?>&iGalleryUnq=<?=$iGalleryUnq?>&sImage=<?=$sImage?>" width=<?=$iThumbWidth?> border=1>
					<?php
				}Else{
					?>
					<img src = "<?=DOMAIN_Conf("IG")?>/<?=$sAccountUnq?>/<?=$iGalleryUnq?>/<?=$sImage?>" width=<?=$iThumbWidth?> border=1>
					<?php 
				}
			}Else{
				Echo "<br><img src='../../Images/MediaIcons/" . $sType . ".gif' alt = '" . $sType . " file' border=0>&nbsp;<br>" . $sType . " file";
			}
		}
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
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function DeleteAricaur($sAltNum, $iImageUnq, $iGalleryUnq)
	{
		Global $sGalleryPath;
		
		$sQuery			= "SELECT Image" . $sAltNum . ", Alt" . $sAltNum . "UL, Aricaur, AricaurThumb FROM Images WHERE ImageUnq = " . $iImageUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$iAccountUnq	= $rsRow["Alt" . $sAltNum . "UL"];
			$sFilePath		= $sGalleryPath . "/" . $iAccountUnq . "/" . $iGalleryUnq . "/Aricaur/" . trim($rsRow["Image" . $sAltNum]);
			$sFilePath		= str_replace("\\", "/", $sFilePath);
			$sFilePath		= str_replace("//", "/", $sFilePath);
			$sFilePath		= str_replace("//", "/", $sFilePath);
			DelAnyFile($sFilePath, $iAccountUnq);
			If ( ( trim($rsRow["Aricaur"]) != "" ) && ( trim($rsRow["AricaurThumb"]) == $sAltNum ) )
			{
				DB_Update ("UPDATE Images SET Aricaur = '', aricaurthumb = '' WHERE ImageUnq = " . $iImageUnq);
				return "Successfully deleted Alternate View Image " . trim($rsRow["Image" . $sAltNum]) . ". <b>This image was the Aricaur image link. The Aricaur link has been removed.</b><br><br>";
			}
			return "Successfully deleted Alternate View Image: " . trim($rsRow["Image" . $sAltNum]) . ".<br><br>";
		}
	}
	//************************************************************************************
?>
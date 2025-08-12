<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iGalleryUnq	= Trim(Request("iGalleryUnq"));
	$iTtlNumItems	= Trim(Request("iTtlNumItems"));
	$iDBLoc			= Trim(Request("iDBLoc"));
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
		Global $iGalleryUnq;
		Global $iImageUnq;
		Global $sAccountUnq;
		Global $iThumbWidth;
		Global $iDestination;
		Global $iLoginAccountUnq;
		Global $sGalleryPath;
		Global $iTtlNumItems;
		Global $iDBLoc;
		
		Global $sName;
		Global $sThumbnail;
		Global $sImage;
		Global $aImage;
		Global $iImageNum;
		Global $iPrimaryG;
		
		$sAction		= Trim(Request("sAction"));
		$iImageUnq		= Trim(Request("iImageUnq"));
		$sAccountUnq	= G_ADMINISTRATION_GetGalleryOwner($iGalleryUnq);
		$iThumbWidth	= DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH");
		$sError			= "";
		
		$sQuery			= "SELECT I.Thumbnail, I.Image, I.Image2, I.Image3, I.Image4, I.Image5, I.ImageUL, I.ThumbUL, I.Alt2UL, I.Alt3UL, I.Alt4UL, I.Alt5UL, IG.Position, IG.PrimaryG FROM Images I (NOLOCK), Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE I.ImageUnq = " . $iImageUnq . " AND IG.ImageUnq = " . $iImageUnq . " AND IG.GalleryUnq = G.GalleryUnq AND G.GalleryUnq = " . $iGalleryUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$sName		= G_ADMINISTRATION_GetGalleryName(Trim($rsRow["PrimaryG"]));
			$sThumbnail	= Trim($rsRow["Thumbnail"]);
			$sImage		= Trim($rsRow["Image"]);
			$aImage[2]	= Trim($rsRow["Image2"]);
			$aImage[3]	= Trim($rsRow["Image3"]);
			$aImage[4]	= Trim($rsRow["Image4"]);
			$aImage[5]	= Trim($rsRow["Image5"]);
			$iImageNum	= Trim($rsRow["Position"]);
			$iPrimaryG	= Trim($rsRow["PrimaryG"]);
			$iOwner[0]	= Trim($rsRow["ImageUL"]);
			$iOwner[1]	= Trim($rsRow["ThumbUL"]);
			$iOwner[2]	= Trim($rsRow["Alt2UL"]);
			$iOwner[3]	= Trim($rsRow["Alt3UL"]);
			$iOwner[4]	= Trim($rsRow["Alt4UL"]);
			$iOwner[5]	= Trim($rsRow["Alt5UL"]);
		}
		
		
		If ( $iImageUnq != "" )
		{
			If ( $sAction == "MoveImage" )
			{
				$iDestination		= Trim(Request("iDestination"));
				$sDestinationUser	= G_ADMINISTRATION_GetGalleryOwner($iDestination);
				
				If ( ! ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
					/* if the current user IS NOT an admin, then double check that they own the dest gallery - in case they try putting
						iDestination on the querystring and spoofing their way in*/
					If ( ( ! G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iDestination, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
						$sError = "Sorry but you are not the owner of the destination gallery and cannot move images into it.<br>";
				}
				
				If ( $sError == "" )
				{
					// if we are moving the image into a gallery that it's already being referenced in, we must detect and delete the reference
					DB_Update ("DELETE FROM ImagesInGallery WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iDestination);
					
					// reset the images position to the last entry in its new gallery (put it at the end of the gallery)
					If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
						$sQuery	= "SELECT ISNULL(MAX(G.Position), 0) + 1 FROM Images I, ImagesInGallery G WHERE G.ImageUnq=I.ImageUnq AND G.GalleryUnq=" . $iDestination;
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) ){
							DB_Update ("UPDATE ImagesInGallery SET Position = " . $rsRow[0] . ", GalleryUnq = " . $iDestination . ", PrimaryG = " . $iDestination . " WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq);
						}Else{
							DB_Update ("UPDATE ImagesInGallery SET Position = 1, GalleryUnq = " . $iDestination . ", PrimaryG = " . $iDestination . " WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq);
						}
					}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
						$sQuery	= "SELECT MAX(G.Position) FROM Images I, ImagesInGallery G WHERE G.ImageUnq=I.ImageUnq AND G.GalleryUnq=" . $iDestination;
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) ){
							DB_Update ("UPDATE ImagesInGallery SET Position = " . ($rsRow[0] + 1) . ", GalleryUnq = " . $iDestination . ", PrimaryG = " . $iDestination . " WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq);
						}Else{
							DB_Update ("UPDATE ImagesInGallery SET Position = 1, GalleryUnq = " . $iDestination . ", PrimaryG = " . $iDestination . " WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq);
						}
					}
					
					// now update any references
					DB_Update ("UPDATE ImagesInGallery SET PrimaryG = " . $iDestination . " WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq != " . $iGalleryUnq);
					
					// Decrement (by 1) all ImageNum's AFTER the one we are moving.
					$sQuery			= "SELECT I.ImageUnq, G.Position FROM Images I (NOLOCK), ImagesInGallery G (NOLOCK) WHERE G.GalleryUnq = " . $iGalleryUnq . " AND I.ImageUnq = G.ImageUnq AND G.Position > " . $iImageNum . " ORDER BY G.Position";
					$rsRecordSet	= DB_Query($sQuery);
					While ( $rsRow = DB_Fetch($rsRecordSet) )
						DB_Update ("UPDATE ImagesInGallery SET Position = " . ($rsRow["Position"] - 1) . " WHERE ImageUnq = " . $rsRow["ImageUnq"] . " AND GalleryUnq = " . $iGalleryUnq);
					
					//Move Primary Image, any Alternate View Images & Thumbnail & Aricaur images!
					// =======================================================================
					$sFilePath	= $sGalleryPath . "/" . $sDestinationUser . "/" . $iDestination . "/Aricaur/";
					$sFilePath	= str_replace("\\", "/", $sFilePath);
					$sFilePath	= str_replace("//", "/", $sFilePath);
					// Make sure the directories exist...make them if they don't
					If ( ! file_exists($sFilePath))
					{
						If ( $GLOBALS["sOS"] == "UNIX" ) {
							mkdirs_nix($sFilePath);
						}Else{
							mkdirs_win($sFilePath);
						}
					}
					
					$sFilePath	= $sGalleryPath . "/" . $sDestinationUser . "/" . $iDestination . "/Thumbnails/";
					$sFilePath	= str_replace("\\", "/", $sFilePath);
					$sFilePath	= str_replace("//", "/", $sFilePath);
					// Make sure the directories exist...make them if they don't
					If ( ! file_exists($sFilePath))
					{
						If ( $GLOBALS["sOS"] == "UNIX" ) {
							mkdirs_nix($sFilePath);
						}Else{
							mkdirs_win($sFilePath);
						}
					}
	
					// move the Primary image
					$sFileCur = $sGalleryPath . "/" . $iOwner[0] . "/" . $iGalleryUnq . "/" . $sImage;
					$sFileCur = str_replace("\\", "/", $sFileCur);
					$sFileCur = str_replace("//", "/", $sFileCur);
					If ( file_exists($sFileCur) )
					{
						$sFileDest = $sGalleryPath . "/" . $sDestinationUser . "/" . $iDestination . "/" . $sImage;
						$sFileDest = str_replace("\\", "/", $sFileDest);
						$sFileDest = str_replace("//", "/", $sFileDest);
						// now check to see if there is already a file of the dest name in the directory. if so, get another name for it.
						If ( file_exists($sFileDest) )
						{
							$sImage = GetNewFilename($sDestinationUser, $iDestination, $sImage);
							DB_Update ("UPDATE Images SET Image = '" . SQLEncode($sImage) . "' WHERE ImageUnq = " . $iImageUnq);
						}
						copy($sFileCur, $sFileDest);
						unlink($sFileCur);
					}
	
					// move any existing thumbnail
					$sFileCur = $sGalleryPath . "/" . $iOwner[1] . "/" . $iGalleryUnq . "/Thumbnails/" . $sThumbnail;
					$sFileCur = str_replace("\\", "/", $sFileCur);
					$sFileCur = str_replace("//", "/", $sFileCur);
					If ( file_exists($sFileCur) )
					{
						$sFileDest = $sGalleryPath . "/" . $sDestinationUser . "/" . $iDestination . "/Thumbnails/" . $sThumbnail;
						$sFileDest = str_replace("\\", "/", $sFileDest);
						$sFileDest = str_replace("//", "/", $sFileDest);
						If ( file_exists($sFileDest) )
						{
							$sThumbnail = GetNewFilename($sDestinationUser, $iDestination, $sThumbnail);
							DB_Update ("UPDATE Images SET Thumbnail = '" . SQLEncode($sThumbnail) . "' WHERE ImageUnq = " . $iImageUnq);
						}
						copy($sFileCur, $sFileDest);
						unlink($sFileCur);
					}
					
					// move any existing alternate view images
					For ( $x = 2; $x <= 5; $x++)
					{
						If ( $aImage[$x] != "" )
						{
							$sTemp = $aImage[$x];	// just in case it's not set below and there is an Aricaur image - but if this happens, then there's a chance that the Aricaur image will overwrite an already existing one because there's no check for when there is no alt image and there is an aricaur image (but this should never happen anyhow)
							$sFileCur = $sGalleryPath . "/" . $iOwner[$x] . "/" . $iGalleryUnq . "/" . $aImage[$x];
							$sFileCur = str_replace("\\", "/", $sFileCur);
							$sFileCur = str_replace("//", "/", $sFileCur);
							If ( file_exists($sFileCur) )
							{
								$sFileDest = $sGalleryPath . "/" . $sDestinationUser . "/" . $iDestination . "/" . $aImage[$x];
								$sFileDest = str_replace("\\", "/", $sFileDest);
								$sFileDest = str_replace("//", "/", $sFileDest);
								If ( file_exists($sFileDest) )
								{
									$sTemp = GetNewFilename($sDestinationUser, $iDestination, $aImage[$x]);
									DB_Update ("UPDATE Images SET Image2 = '" . SQLEncode($sTemp) . "' WHERE ImageUnq = " . $iImageUnq);
								}
								copy($sFileCur, $sFileDest);
								unlink($sFileCur);
								
								// move any Aricaur images -- but only if there is an alt image
								$sFileCur = $sGalleryPath . "/" . $iOwner[$x] . "/" . $iGalleryUnq . "/Aricaur/" . $aImage[$x];
								$sFileCur = str_replace("\\", "/", $sFileCur);
								$sFileCur = str_replace("//", "/", $sFileCur);
								If ( file_exists($sFileCur) )
								{
									$sFileDest = $sGalleryPath . "/" . $sDestinationUser . "/" . $iDestination . "/Aricaur/" . $sTemp;
									$sFileDest = str_replace("\\", "/", $sFileDest);
									$sFileDest = str_replace("//", "/", $sFileDest);
									copy($sFileCur, $sFileDest);
									unlink($sFileCur);
								}
							}
						}
					}
					// ========================================================================
					
					Echo "<SCRIPT LANGUAGE=javascript>\n";
					Echo "document.location=\"index.php?" . DOMAIN_Link("G") . "&iGalleryUnq=" . $iGalleryUnq . "&iTtlNumItems=" . ($iTtlNumItems+1) . "&iDBLoc=" . $iDBLoc . "\";\n";
					Echo "</script>";
					Echo "If this page does not automatically forward you, please " . "<a href='index.php?" . DOMAIN_Link("G") . "&iGalleryUnq=" . $iGalleryUnq . "&iTtlNumItems=" . ($iTtlNumItems+1) . "&iDBLoc=" . $iDBLoc . "' class='MediumNavPage'>click here to continue</a>.";
				}
			}
			
			If ( $sError != "" )
				DOMAIN_Message($sError, "ERROR");
			
			WriteForm();
		}Else{
			DOMAIN_Message("Missing iImageUnq. Unable to move an image.", "ERROR");
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
		Global $iLoginAccountUnq;
		Global $iTextScheme;
		Global $iColorScheme;		
		Global $sThumbnail;
		Global $sImage;
		Global $iThumbWidth;
		Global $sName;
		Global $iTableWidth;
		Global $aVariables;
		Global $aValues;

		$sBGColor		= $GLOBALS["PageBGColor"];
		$iTableWidth	= 671;
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td><br>
					<form name='MoveImage' action='Move.php' method='post'>
					<?php G_STRUCTURE_HeaderBar_ADMIN("MoveImageHead.gif", "", "", "Galleries");
					
					$aVariables[0] = "sAction";
					$aVariables[1] = "iTtlNumItems";
					$aVariables[2] = "iDBLoc";
					$aVariables[3] = "iImageUnq";
					$aVariables[4] = "iGalleryUnq";
					$aValues[0] = "MoveImage";
					$aValues[1] = $iTtlNumItems;
					$aValues[2] = $iDBLoc;
					$aValues[3] = $iImageUnq;
					$aValues[4] = $iGalleryUnq;
					Echo DOMAIN_Link("P");
					?>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
					<table cellpadding = 0 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor = <?=$sBGColor?>>
								<table cellpadding = 5 cellspacing = 3 border = 0 bordercolor = <?=$GLOBALS["BorderColor1"]?> width = 100%>
									<tr>
										<td width=<?=$iThumbWidth?> align=middle valign=top>
											<?php DisplayThumb($sThumbnail, $sImage);?>
										</td>
										<td valign=top>
											<table cellpadding=5 cellspacing=0 border=0>
												<tr>
													<td valign=top><font color='<?=$GLOBALS["TextColor1"]?>'>Current Gallery:</td>
													<td>
														<b><?=$sName?></b>
														<br><br>
													</td>
												</tr>
												<tr>
													<td valign=top><font color='<?=$GLOBALS["TextColor1"]?>'>Destination Gallery:</td>
													<td>
														<select name="iDestination">
															<?php 
															If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
																$sQuery = "SELECT AccountUnq, GalleryUnq, Name FROM Galleries";
															}Else{
																$sQuery = "SELECT AccountUnq, GalleryUnq, Name FROM Galleries WHERE AccountUnq = " . $iLoginAccountUnq;
															}
															$rsRecordSet	= DB_Query($sQuery);
															While ( $rsRow = DB_Fetch($rsRecordSet) )
															{
																$iCurrGalleryUnq	= $rsRow["GalleryUnq"];
																$iCurrGalleryName	= $rsRow["Name"];
																If ( intval($iGalleryUnq) != intval($iCurrGalleryUnq) )
																	Echo "<option value=\"" . $iCurrGalleryUnq . "\">" . $iCurrGalleryName . "</option>";
															}															
															?>
														</select>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BGColor2"]?> align=center>
								<input type='image' src="../../Images/SchemeBased/<?=$iTextScheme?>/<?=$iColorScheme?>/MoveImage.gif" style="BORDER: none; vertical-align: sub;">
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
	}
	//************************************************************************************
	
	
	//************************************************************************************
	Function DisplayThumb($sThumbnail, $sImage)
	{
		Global $sAccountUnq;
		Global $iPrimaryG;
		Global $sSiteURL;
		
		$iThumbWidth = DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH");
		?><img src = "<?=$sSiteURL?>/Attachments/DispThumb.php?sAccountUnq=<?=$sAccountUnq?>&sThumbnail=<?=$sThumbnail?>&iGalleryUnq=<?=$iPrimaryG?>" width=<?=$iThumbWidth?> border=1><?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	If a file in the dest gallery directory of the same name as the source image, 	*
	//*		we need to make the dest file with a new name. This gets a new name for it.	*
	//*																					*
	//************************************************************************************
	Function GetNewFilename($sDestinationUser, $iDestination, $sImage)
	{
		Global $sGalleryPath;
		
		srand();
		Do
		{
			$sImage = rand(100000, 999999) . "_" . $sImage;
			$sFileCur = $sGalleryPath . "\\" . $sDestinationUser . "\\" . $iDestination . "\\" . $sImage;
			$sFileCur = str_replace("\\", "/", $sFileCur);
			$sFileCur = str_replace("//", "/", $sFileCur);
		} While ( file_exists($sFileCur) );
		
		Return $sImage;
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
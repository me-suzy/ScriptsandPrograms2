<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iTemp = DOMAIN_Conf("IMAGEGALLERY_SCRIPTTIMEOUT");
	If ( $iTemp = "" )
		$iTemp = 5400;
	set_time_limit($iTemp);
	
	$sAction				= Trim(Request("sAction"));
	$iTtlNumItems			= Trim(Request("iTtlNumItems"));
	$iDBLoc					= Trim(Request("iDBLoc"));
	$iGalleryUnq			= Trim(Request("iGalleryUnq"));
	$iImageUnq				= Trim(Request("iImageUnq"));
	$sAutoCreateThumbnail	= Trim(Request("sAutoCreateThumbnail"));
	$sValidExtensions		= G_ADMINISTRATION_ValidFileExtensions($iLoginAccountUnq);
	$iMaxFileSize			= G_ADMINISTRATION_MaxFileSize($iLoginAccountUnq);
	$iHDSpaceLeft			= G_ADMINISTRATION_HDSpaceLeft($iLoginAccountUnq);
	$iThumbComponent		= G_ADMINISTRATION_ASPImageInstalled();
	$sAccountUnq			= G_ADMINISTRATION_GetGalleryOwner($iGalleryUnq);
	$sThumbnail				= "";
	$iPrimaryG				= "";
	
	If ( ! isset($_POST["sAction"]) )
	{
		// get the rest of the information from the database
		$sQuery			= "SELECT I.Thumbnail, G.AccountUnq, IG.PrimaryG FROM Images I (NOLOCK), Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE I.ImageUnq = " . $iImageUnq . " AND IG.ImageUnq = " . $iImageUnq . " AND IG.GalleryUnq = G.GalleryUnq AND G.GalleryUnq = " . $iGalleryUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$sThumbnail		= Trim($rsRow["Thumbnail"]);
			$iPrimaryG		= Trim($rsRow["PrimaryG"]);
		}
	}
	
	WriteScripts();		// must run this once $iGalleryUnq has been filled
	
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
		Global $sAction;
		Global $sAccountUnq;
		Global $iGalleryUnq;
		Global $iLoginAccountUnq;
		Global $iImageUnq;
		Global $ASPIMAGE;
		Global $GFL;
		Global $iThumbComponent;
		Global $sAutoCreateThumbnail;
		Global $sThumbnail;
		Global $iPrimaryG;
		Global $sGalleryPath;
		Global $iTtlNumItems;
		Global $iDBLoc;
		
		$sError			= "";
		$sSuccess		= "";

		If ( $sAction == "Update" )
		{
			If ( ! ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				If ( ! ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") )
					$sError = "Please log in with Image Gallery management rights.";
			}

			If ( $sError == "" )
			{
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{					
					If ( $sError == "" )
					{
						// get the original thumbnail name so we can know if we need to delete the original thumb and/or change its name in the db
						$sQuery			= "SELECT Image, Thumbnail, FileType, ThumbUL FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							$sOriginalThumbName	= Trim($rsRow["Thumbnail"]);
							$sFileName			= Trim($rsRow["Image"]);		// this is only used for G_UPLOAD_MakeThumb() because it needs the $sFileName position filled with the Primary Image name so it can dynamically create the thumbnail
							$sExtension			= Trim($rsRow["FileType"]);
							$iThumbUL			= Trim($rsRow["ThumbUL"]);
						}

						If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) ) {
							If ( $sAutoCreateThumbnail == "Y" ) {
								$sThumbname = $sOriginalThumbName;	// didn't change because they aren't uploading a new thumb image
								If ( $iThumbComponent == $ASPIMAGE )
								{
									If ( ( strtoupper($sExtension) == "JPG" ) || ( strtoupper($sExtension) == "PNG" ) || ( strtoupper($sExtension) == "BMP" ) )
									{
										$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/Thumbnails/" . $sOriginalThumbName;
										$sFilePath	= str_replace("\\", "/", $sFilePath);
										$sFilePath	= str_replace("//", "/", $sFilePath);
										DelAnyFile($sFilePath, $iThumbUL);
										$sError = $sError . G_UPLOAD_MakeThumb($sThumbname, $sFileName);
									}Else{
										$sError = "Unable to automatically create a thumbnail from a source file of type: " . $sExtension;
									}
								}Else{
									$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/Thumbnails/" . $sOriginalThumbName;
									$sFilePath	= str_replace("\\", "/", $sFilePath);
									$sFilePath	= str_replace("//", "/", $sFilePath);
									DelAnyFile($sFilePath, $iThumbUL);
									$sError = $sError . G_UPLOAD_MakeThumb($sThumbname, $sFileName);
								}
								// since we automatically created the thumbnail, skip saving that file in the file array
							}Else{
								// save the thumbnail file if one exists
								$sThumbname = Trim($_FILES["ThumbnailFile"]["name"]);	// must get this a bit later so we know it's not just blank
								$sError = $sError . G_UPLOAD_SaveFile("ThumbnailFile", "THUMB", $sThumbname);
							}
						}Else{
							// save the thumbnail file if one exists
							$sThumbname = Trim($_FILES["ThumbnailFile"]["name"]);	// must get this a bit later so we know it's not just blank
							$sError = $sError . G_UPLOAD_SaveFile("ThumbnailFile", "THUMB", $sThumbname);
						}

						$iPos		= strpos($sThumbname, ".");
						$sThumbname	= substr($sThumbname, 0, $iPos) . ".jpg";

						If ( ( $sThumbname != ".jpg" ) && ( $sOriginalThumbName != $sThumbname ) )
						{
							/* if sThumbname is not blank (just ".jpg" ) that means they uploaded a thumb image
							 it did not overwrite the previous thumbnail, so delete it now, and change the name in the db*/
							$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/Thumbnails/" . $sOriginalThumbName;
							$sFilePath	= str_replace("\\", "/", $sFilePath);
							$sFilePath	= str_replace("//", "/", $sFilePath);
							DelAnyFile($sFilePath, $iThumbUL);
							DB_Update ("UPDATE Images SET Thumbnail = '" . SQLEncode($sThumbname) . "', ThumbUL = " . $iLoginAccountUnq . " WHERE ImageUnq = " . $iImageUnq);
						}

						If ( $sError == "" )
						{
							Echo "<SCRIPT LANGUAGE=javascript>\n";
							Echo "document.location=\"index.php?" . DOMAIN_Link("G") . "&iGalleryUnq=" . $iGalleryUnq . "&iTtlNumItems=" . ($iTtlNumItems+1) . "&iDBLoc=" . $iDBLoc . "\";\n";
							Echo "</script>";
							Echo "If this page does not automatically forward you, please " . "<a href='index.php?" . DOMAIN_Link("G") . "&iGalleryUnq=" . $iGalleryUnq . "&iTtlNumItems=" . ($iTtlNumItems+1) . "&iDBLoc=" . $iDBLoc . "' class='MediumNavPage'>click here to continue</a>.";
						}
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot edit images in it.<br>";
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
		Global $ASPIMAGE;
		Global $GFL;
		Global $iTextScheme;
		Global $iColorScheme;
		Global $sValidExtensions;
		Global $iThumbComponent;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iTableWidth;
		Global $iGalleryUnq;
		Global $iImageUnq;
		Global $iMaxFileSize;
		Global $sAutoCreateThumbnail;
		Global $sThumbnail;
	
		$sBGColor	= $GLOBALS["BGColor1"];
		$sTextColor	= $GLOBALS["TextColor1"];
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
				file = document.EditThumbnail.ThumbnailFile.value;
				
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
			
			function ReturnToMain(){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$iGalleryUnq?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
			}
			
		</script>

		<form ENCTYPE="multipart/form-data" onsubmit="return LimitAttach()" name='EditThumbnail' action='EditThumbnail.php' method='post'>
		<input type='hidden' name='iImageUnq' value='<?=$iImageUnq?>'>
		<input type='hidden' name='iGalleryUnq' value='<?=$iGalleryUnq?>'>
		<input type='hidden' name='iTtlNumItems' value='<?=$iTtlNumItems?>'>
		<input type='hidden' name='iDBLoc' value='<?=$iDBLoc?>'>
		<input type='hidden' name='MAX_FILE_SIZE' value='<?=$iMaxFileSize?>'>		
		<input type='hidden' name='sAction' value='Update'>
		
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr><td><font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Edit Thumbnail</b></font></td></tr>
			<tr>
				<td>
					<br>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
					<table cellpadding = 5 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor='<?=$sBGColor?>' align=center>
								<table width=100%>
									<?php If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) ){
									$sQuery = "SELECT FileType FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
									$rsRecordSet	= DB_Query($sQuery);
									If ( $rsRow = DB_Fetch($rsRecordSet) )
										$sFileType = strtoupper(Trim($rsRow["FileType"]));
									
									If ( ( $sFileType == "JPG" ) || ( $sFileType == "PNG" ) || ( $sFileType == "BMP" ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) ) {
									?>
									<tr>
										<td colspan=2>
											<table width=100% cellpadding = 0 cellspacing=0 border=0>
												<tr>
													<td valign=top>
														<font color='<?=$sTextColor?>'><b>
														<?php 
														If ( $iThumbComponent == $ASPIMAGE ) {
															Echo "Check this box if you would like to have a thumbnail (" . DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH") . " pixels wide) automatically created for this image (this only works if you upload a .jpg, .bmp, or .png image type as your Primary Image)?";
														}ElseIf ( $iThumbComponent == $GFL ) {
															Echo "Check this box if you would like to have a thumbnail (" . DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH") . " pixels wide) automatically created for this image (this only works if you upload images supported by GflAx as your Primary Image)?";
															Echo "</b><font size=-2><BR>Supported file types include: &nbsp;";
															Echo GFL_Supported($sValidExtensions);
														}ElseIf  ( $iThumbComponent == "PHP" ) {
															Echo "Check this box if you would like to have a thumbnail (" . DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH") . " pixels wide) automatically created for this image?";
															Echo "</b><font size=-2><BR>Supported file types include: GIF, JPG, PNG and BMP.";
														}
														?>
													</td>
													<td><input type='checkbox' name='sAutoCreateThumbnail' value='Y' <?php If ( $sAutoCreateThumbnail == "Y" )  Echo "checked";?>></td>
												</tr>
											</table>
										</td>
									</tr>
									<?php }Else{?>
									<tr>
										<td colspan=2>
											<table width=100% cellpadding = 0 cellspacing=0 border=0>
												<tr>
													<td valign=top><font color='<?=$sTextColor?>'>
													<?php 
													If ( $iThumbComponent == $ASPIMAGE ) {
														Echo "The option to dynamically create a thumbnail from the Primary Image has been disabled because the Primary Image is not of file type .jpg, .bmp, or .png. The Primary Image is of file type:";
													}ElseIf ( $iThumbComponent == $GFL ) {
														Echo "The option to dynamically create a thumbnail from the Primary Image has been disabled because the Primary Image is not an image supported by GflAx. The Primary Image is of file type:";
													}ElseIf ( $iThumbComponent == "PHP" ) {
														Echo "The option to dynamically create a thumbnail from the Primary Image has been disabled because the Primary Image is not an image supported by PHP. The Primary Image is of file type:";
													}
													?>
													.<?=$sFileType?>.
												</tr>
											</table>
										</td>
									</tr>
									<?php }?>
									<tr>
										<?php If ( ( $sFileType = "JPG" ) || ( $sFileType = "PNG" ) || ( $sFileType = "BMP" ) ) {?>
										<td valign=top><font color='<?=$sTextColor?>'><b>Or, you may upload your own thumbnail image: </td>
										<?php }Else{?>
										<td valign=top><font color='<?=$sTextColor?>'><b>Thumbnail Image</td>
										<?php }?>
										<td>
											<input TYPE='FILE' NAME='ThumbnailFile' SIZE=30>
											<?php DisplayImageInfo();?>
										</td>
									</tr>
									<?php }Else{?>
									<tr>
										<td valign=top><font color='<?=$sTextColor?>'><b>Thumbnail Image</td>
										<td>
											<input TYPE='FILE' NAME='ThumbnailFile' SIZE=30>
											<?php DisplayImageInfo();?>
										</td>
									</tr>
									<?php }?>
									<tr>
										<td valign=top><font color='<?=$sTextColor?>'><b>Current Thumbnail:</td>
										<td>
											<?php DisplayThumb($sThumbnail);?>
										</td>
									</tr>
								</table>
								<br><br>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BGColor2"]?> align=center>
								<input type='image' src="../../Images/SchemeBased/<?=$iTextScheme?>/<?=$iColorScheme?>/UpdateThumbnail.gif" style="BORDER: none; vertical-align: sub;">
							</td>
						</tr>
					</table>
					</td></tr></table>
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
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayImageInfo()
	{
		Global $sValidExtensions;
		Global $iMaxFileSize;
		Global $iULLeft;
		Global $iHDSpaceLeft;
		
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
	//*	Try to display the thumbnail. If that doesn't exist, try displaying the image	*
	//*		as a thumbnail.																*
	//*																					*
	//************************************************************************************
	Function DisplayThumb($sThumbnail)
	{
		Global $sAccountUnq;
		Global $iPrimaryG;
		Global $sGalleryPath;
		Global $sSiteURL;
		
		$iThumbWidth	= DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH");
		If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DOMAINCHECK"))) == "YES" ) {
			?>
			<img src = "<?=$sSiteURL?>/Attachments/DispThumb.php?sAccountUnq=<?=$sAccountUnq?>&sThumbnail=<?=$sThumbnail?>&iGalleryUnq=<?=$iPrimaryG?>" width=<?=$iThumbWidth?> border=1>
			<?php 
		}Else{
			$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iPrimaryG . "/Thumbnails/" . $sThumbnail;
			$sFilePath	= str_replace("\\", "/", $sFilePath);
			$sFilePath	= str_replace("//", "/", $sFilePath);
			If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
			{
				?>
				<img src = "<?=DOMAIN_Conf("IG")?>/<?=$sAccountUnq?>/<?=$iPrimaryG?>/Thumbnails/<?=$sThumbnail?>" width=<?=$iThumbWidth?> border=1>
				<?php
			}Else{
				?>
				<img src = "<?=DOMAIN_Conf("IMAGEGALLERY_MISSING_THUMBNAIL")?>" width=<?=$iThumbWidth?> border=1>
				<?php
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
?>
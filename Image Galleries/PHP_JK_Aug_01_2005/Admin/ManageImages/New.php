<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	include("../../fckeditor/fckeditor.php") ;

	$iTemp = DOMAIN_Conf("IMAGEGALLERY_SCRIPTTIMEOUT");
	If ( $iTemp = "" )
		$iTemp = 5400;
	set_time_limit($iTemp);

	$sAction				= Trim(Request("sAction"));
	$iTtlNumItems			= Trim(Request("iTtlNumItems"));
	$iDBLoc					= Trim(Request("iDBLoc"));
	$iGalleryUnq			= Trim(Request("iGalleryUnq"));
	$sFileName				= "";
	$sTempName				= "";
	$sExtension				= "";
	$iFileSize				= 0;
	$iMaxFileSize			= 0;
	$sAccountUnq			= 0;
	$iYSize					= 0;
	$iXSize					= 0;

	$sValidExtensions	= G_ADMINISTRATION_ValidFileExtensions($iLoginAccountUnq);
	$iMaxFileSize		= G_ADMINISTRATION_MaxFileSize($iLoginAccountUnq);
	$iHDSpaceLeft		= G_ADMINISTRATION_HDSpaceLeft($iLoginAccountUnq);
	$iThumbComponent	= G_ADMINISTRATION_ASPImageInstalled();

	WriteScripts();		// must run this once $iGalleryUnq has been filled
	
	// check number of files left the user can upload. If no more, display an error message and no form.
	$iULLeft = G_ADMINISTRATION_ULLeft($iLoginAccountUnq);
	If ( ( $iULLeft == -1 ) || ( $iULLeft > 0 ) )
	{
		// make sure they have HD space left
		If ( ( $iHDSpaceLeft == -1 ) || ( $iHDSpaceLeft > 0 ) )
		{
			If ( (ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL")) || (ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY")) )
			{
				If ( ( $iGalleryUnq != "" ) && ( $iGalleryUnq != "-1" ) ) {
					HeaderHTML();
					Main();
				}Else{
					DOMAIN_Message("Please choose (or create) a gallery before adding images.", "ERROR");
				}
			}Else{
				DOMAIN_Message("Please log in with Image Gallery management rights.", "ERROR");
			}
		}Else{
			DOMAIN_Message("Sorry but your disk space limit has been reached. Please delete an image to free up space before adding new images.", "ERROR");
		}
	}Else{
		DOMAIN_Message("Sorry but you have reached your maximum uploadable images and are not allowed to upload more.<br><br>You may still str_replace your existing images with new ones.<br><br>Your maximum number of images is: " . $iULLeft, "ERROR");
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
		Global $sValidExtensions;
		Global $iMaxFileSize;
		Global $iHDSpaceLeft;
		Global $iThumbComponent;
		Global $sAction;
		Global $sComments;
		Global $sAltTag;
		Global $sKeywords;
		Global $sTitle;
		Global $sAutoCreateThumbnail;
		Global $ASPIMAGE;
		Global $GFL;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iLoginAccountUnq;
		Global $sAccountUnq;
		Global $iYSize;
		Global $iXSize;
		
		Global $sFileName;
		Global $sTempName;
		Global $sExtension;
		Global $iFileSize;
		Global $sThumbname;
		
		$sError		= "";
		$sSuccess	= "";
		
		If ( $iGalleryUnq != "" )
		{
			If ( $sAction == "New" ) {
				
				$sComments				= Trim(Request("sComments"));
				$sAltTag				= Trim(Request("sAltTag"));
				$sKeywords				= Trim(Request("sKeywords"));
				$sTitle					= Trim(Request("sTitle"));
				$sAutoCreateThumbnail	= Trim(Request("sAutoCreateThumbnail"));

				If ( ! ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
					If ( ! ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") )
						$sError = "Please log in with Image Gallery management rights.";
				}

				If ( $sError == "" )
				{
					If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
					{
						/* sAccountUnq is global and used throughout the functions below
							we cannot just use $iLoginAccountUnq because an admin might upload an image to a gallery that he does not own...which is
							ok. but if the AccountUnq is set as the admin's account, then the images will be saved in the wrong directory*/
						$sAccountUnq	= G_ADMINISTRATION_GetGalleryOwner($iGalleryUnq);
						$sFileName		= $_FILES["File1"]["name"];
						$sThumbname		= $_FILES["ThumbnailFile"]["name"];
						$sError			= G_UPLOAD_SaveFile("File1", "PRIMARY", $sFileName);
						If ( $sError == "" )
						{
							If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) )
							{
								$sError = $sError . G_UPLOAD_GetDimensions($sFileName);
 								If ( $sAutoCreateThumbnail == "Y" )
								{
									If ( $iThumbComponent == $ASPIMAGE )
									{
										If ( ( strtoupper($sExtension) == "JPG" ) || ( strtoupper($sExtension) == "PNG" ) || ( strtoupper($sExtension) == "BMP" ) ) {
											$sError = $sError . G_UPLOAD_MakeThumb($sThumbname, $sFileName);
										}Else{
											$sError = "Unable to automatically create a thumbnail from a source file of type: " . $sExtension;
										}
									}Else{
										$sError = $sError . G_UPLOAD_MakeThumb($sThumbname, $sFileName);
									}
									// since we automatically created the thumbnail, skip saving that file in the file array
								}Else{
									// save the thumbnail file if one exists
									$sError = $sError . G_UPLOAD_SaveFile("ThumbnailFile", "THUMB", $sThumbname);
								}
							}Else{
								// save the thumbnail file if one exists
								$sError = $sError . G_UPLOAD_SaveFile("ThumbnailFile", "THUMB", $sThumbname);
							}
							
							$iPos		= strpos($sThumbname, ".");
							If ( $sThumbname != "" )
								$sThumbname	= substr($sThumbname, 0, $iPos) . ".jpg";

							If ( $sError == "" )
							{
								$sQuery			= "SELECT IG.Position FROM Images I, ImagesInGallery IG WHERE IG.GalleryUnq = " . $iGalleryUnq . " AND I.ImageUnq = IG.ImageUnq ORDER BY IG.Position DESC";
								$rsRecordSet	= DB_Query($sQuery);
								If ( $rsRow = DB_Fetch($rsRecordSet) )
								{
									If ( is_null($rsRow["Position"] ) )
									{
										$iImageNum = 1;
									}Else{
										$iImageNum = $rsRow["Position"] + 1;
									}
								}Else{
									$iImageNum = 1;
								}
								
								// if it is not an image type (txt pdf etc)
								If ( $iFileSize == "" )
									$iFileSize = 0;
								If ( $iXSize == "" )
									$iXSize = 0;
								If ( $iYSize == "" )
									$iYSize = 0;
								
								If ( $sThumbname != "" ) {
									// set the user who uploaded the thumbnail as the current user
									DB_Insert ("INSERT INTO Images (Comments,AltTag,Image,Thumbnail,Rating,NumRaters,NumViews,ImageSize,XSize,YSize,ImageNum,FileType,Image2,Image3,Image4,Image5,Image2Desc,Image3Desc,Image4Desc,Image5Desc,AltTag2,AltTag3,AltTag4,AltTag5,XSize2,YSize2,XSize3,YSize3,XSize4,YSize4,XSize5,YSize5,ImageSize2,ImageSize3,ImageSize4,ImageSize5,ConfUnq,ThreadUnq,Keywords,CookedComments,Title,ImageUL,ThumbUL,Alt2UL,Alt3UL,Alt4UL,Alt5UL,EZPrints,Aricaur,AricaurThumb) VALUES ('" . SQLEncode($sComments) . "', '" . SQLEncode($sAltTag) . "', '" . SQLEncode($sFileName) . "', '" . SQLEncode($sThumbname) . "',0,0,0," . $iFileSize . ",'" . $iXSize . "','" . $iYSize . "'," . $iImageNum . ", '" . $sExtension . "', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0,0,'" . SQLEncode($sKeywords) . "','" . SQLEncode($sComments) . "', '" . SQLEncode($sTitle) . "', " . $iLoginAccountUnq . ", " . $iLoginAccountUnq . ", 0, 0, 0, 0, '','','')");
								}Else{
									// no thumb, so set the user who uploaded the thumbnail as 0
									DB_Insert ("INSERT INTO Images (Comments,AltTag,Image,Thumbnail,Rating,NumRaters,NumViews,ImageSize,XSize,YSize,ImageNum,FileType,Image2,Image3,Image4,Image5,Image2Desc,Image3Desc,Image4Desc,Image5Desc,AltTag2,AltTag3,AltTag4,AltTag5,XSize2,YSize2,XSize3,YSize3,XSize4,YSize4,XSize5,YSize5,ImageSize2,ImageSize3,ImageSize4,ImageSize5,ConfUnq,ThreadUnq,Keywords,CookedComments,Title,ImageUL,ThumbUL,Alt2UL,Alt3UL,Alt4UL,Alt5UL,EZPrints,Aricaur,AricaurThumb) VALUES ('" . SQLEncode($sComments) . "', '" . SQLEncode($sAltTag) . "', '" . SQLEncode($sFileName) . "', '" . SQLEncode($sThumbname) . "',0,0,0," . $iFileSize . ",'" . $iXSize . "','" . $iYSize . "'," . $iImageNum . ", '" . $sExtension . "', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0,0,'" . SQLEncode($sKeywords) . "','" . SQLEncode($sComments) . "', '" . SQLEncode($sTitle) . "', " . $iLoginAccountUnq . ", 0, 0, 0, 0, 0, '','','')");
								}
								$rsRecordSet	= DB_Query("SELECT @@IDENTITY");
								If ( $rsRow = DB_Fetch($rsRecordSet) )
								{
									$iImageUnq = $rsRow[0];
									DB_Insert ("INSERT INTO ImagesInGallery VALUES (" . $iImageUnq . ", " . $iGalleryUnq . ", GetDate(), 1, " . $iImageNum . ", " . $iGalleryUnq . ", 1, 0, 0, 0, 0,0)");
								}Else{
									$sError = $sError . "There was an error adding this image.";
								}
								
								// send any subscription emails
								G_ADMINISTRATION_SendSubscriptionEmail($iGalleryUnq);
								Echo "<SCRIPT LANGUAGE=javascript>\n";
								Echo "document.location=\"index.php?" . DOMAIN_Link("G") . "&iGalleryUnq=" . $iGalleryUnq . "&iTtlNumItems=" . ($iTtlNumItems+1) . "&iDBLoc=" . $iDBLoc . "\";\n";
								Echo "</script>";
								Echo "If this page does not automatically forward you, please " . "<a href='index.php?" . DOMAIN_Link("G") . "&iGalleryUnq=" . $iGalleryUnq . "&iTtlNumItems=" . ($iTtlNumItems+1) . "&iDBLoc=" . $iDBLoc . "' class='MediumNavPage'>click here to continue</a>.";
							}
						}
					}Else{
						$sError = "Sorry but you are not the owner of this gallery and cannot add images to it.<br>";
					}
				}
			}
		
			If ( $sError != "" )
				DOMAIN_Message($sError, "ERROR");
			If ( $sSuccess != "" )
				DOMAIN_Message($sSuccess, "SUCCESS");
			
			WriteForm();
		}Else{
			DOMAIN_Message("Unable to find the gallery to add the image to. Cannot add an image.", "ERROR");
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
		Global $ASPIMAGE;
		Global $GFL;
		Global $iTextScheme;
		Global $iColorScheme;
		Global $sValidExtensions;
		Global $iThumbComponent;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iTableWidth;
		
		Global $sComments;
		Global $iGalleryUnq;
		Global $sComments;
		Global $sAltTag;
		Global $sKeywords;
		Global $sTitle;
		Global $sAutoCreateThumbnail;
		Global $iMaxFileSize;
		
		$sBGColor		= $GLOBALS["BGColor1"];
		$sTextColor		= $GLOBALS["TextColor1"];
		$iTableWidth	= 671;
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
					file = document.NewImage.ThumbnailFile.value;
					
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
						alert("Please only upload thumbnail files that end in types:  " + (extArray.join("  ")) + "\nPlease select a new thumbnail to upload and submit again.");
						return false;
					}
				}else{
					alert("Please only upload files that end in types:  " + (extArray.join("  ")) + "\nPlease select a new file to upload and submit again.");
					return false;
				}
			}
			
		</script>

		<form ENCTYPE="multipart/form-data" onsubmit="return LimitAttach()" name='NewImage' action='New.php' method='post'>
		<input type='hidden' name='iGalleryUnq' value='<?=$iGalleryUnq?>'>
		<input type='hidden' name='iTtlNumItems' value='<?=$iTtlNumItems?>'>
		<input type='hidden' name='iDBLoc' value='<?=$iDBLoc?>'>
		<?php
		If ( $iMaxFileSize == "-1" )
		{
			echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000000'>";
		}Else{
			echo "<input type='hidden' name='MAX_FILE_SIZE' value='" . $iMaxFileSize . "'>";
		}
		?>
		
		<input type='hidden' name='sAction' value='New'>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr><td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					Adding image to gallery: <b><?=DispGalleryName();?></b>
			</td></tr>
			<tr>
				<td>
					<br>
					<?php G_STRUCTURE_HeaderBar_ADMIN("NewImageHead.gif", "", "", "Galleries");?>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
					<table cellpadding = 5 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor = <?=$sBGColor?>>
								<?php
								// fckeditor is VERY sensitive of the url and it can't have two /'s in it. so if the admin entered
								//	something like http://www.asb.com/ and /fckeditor/ then it'd be http://www.asb.com//fckeditor
								//	and it wouldn't work. And you can't just do a str_replace on // because it'll remove the one
								//	at the end of the http://
								$sBasePath = DOMAIN_Conf("IMAGEGALLERY_SITEURL");
								If ( $sBasePath[strlen($sBasePath)-1] == "/" )
								{
									$sBasePath = DOMAIN_Conf("IMAGEGALLERY_SITEURL") . "fckeditor/";
								}Else{
									$sBasePath = DOMAIN_Conf("IMAGEGALLERY_SITEURL") . "/fckeditor/";
								}
								
								$ofckeditor = new fckeditor('sComments') ;
								$ofckeditor->BasePath	= $sBasePath ;
								$ofckeditor->Value		= $sComments;
								$ofckeditor->Create() ;
								?>
							</td>
						</tr>
					</table>
					</td></tr></table>
					<?php G_STRUCTURE_SubHeaderBar_ADMIN("", "", "", "Galleries");?>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
					<table cellpadding = 5 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor='<?=$sBGColor?>' align=center>
								<table width=100%>
									<tr>
										<td><font color='<?=$sTextColor?>'><b>Title</td>
										<td><input type='text' name='sTitle' value="<?=htmlentities($sTitle)?>" size=70 maxlength=250></td>
									</tr>
									<tr>
										<td><font color='<?=$sTextColor?>'><b>Keywords</b> (seperate with a comma)</td>
										<td><input type='text' name='sKeywords' value="<?=htmlentities($sKeywords)?>" size=70 maxlength=250></td>
									</tr>
									<tr>
										<td><font color='<?=$sTextColor?>'><b>Alt Tag</td>
										<td><input type='text' name='sAltTag' value="<?=htmlentities($sAltTag)?>" size=70 maxlength=250></td>
									</tr>
									
									<tr>
										<td valign=top><font color='<?=$sTextColor?>'><b>Primary Image</td>
										<td>
											<input TYPE='FILE' NAME='File1' SIZE=30>
											<?php DisplayImageInfo();?>
										</td>
									</tr>
									<?php If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) ) {?>
									<tr>
										<td colspan=2>
											<table width=100% cellpadding = 0 cellspacing=0 border=0>
												<tr>
													<td valign=top><font color='<?=$sTextColor?>'><b>
													<?php 
													If ( $iThumbComponent == $ASPIMAGE ) {
														Echo "Check this box if you would like to have a thumbnail (" . DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH") . " pixels wide) automatically created for this image (this only works if you upload a .jpg, .bmp, or .png image type as your Primary Image)?";
													}ElseIf ( $iThumbComponent == $GFL ) {
														Echo "Check this box if you would like to have a thumbnail (" . DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH") . " pixels wide) automatically created for this image (this only works if you upload images supported by GflAx as your Primary Image)?";
														Echo "</b><font size=-2><BR>Supported file types include: &nbsp;";
														Echo GFL_Supported($sValidExtensions);
													}ElseIf ( $iThumbComponent == "PHP" ) {
														Echo "Check this box if you would like to have a thumbnail (" . DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH") . " pixels wide) automatically created for this image (this only works if you upload a GIF, JPG, PNG or BMP image type as your Primary Image)?";
													}
													?></td>
													<td><input type='checkbox' name='sAutoCreateThumbnail' value='Y' <?php If ( $sAutoCreateThumbnail == "Y" ) Echo "checked"; ?>></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td valign=top><font color='<?=$sTextColor?>'><b>Or, you may upload your own thumbnail image: </td>
										<td>
											<input TYPE='FILE' NAME='ThumbnailFile' SIZE=30>
											<?php DisplayImageInfo();?>
										</td>
									</tr>
									<?php }Else{ ?>
									<tr>
										<td valign=top><font color='<?=$sTextColor?>'><b>Thumbnail Image</td>
										<td>
											<input TYPE='FILE' NAME='ThumbnailFile' SIZE=30>
											<?php DisplayImageInfo();?>
										</td>
									</tr>
									<?php }?>

									<tr><td colspan=2><br><br></td></tr>
									<?php If ( ACCNT_ReturnRights("PHPJK_IG_ADD_CR_2IMAGES") ) {?>
									<tr><td colspan=2><font color='<?=$sTextColor?>'><b><li>You may add copyright notices to the new image from the main image management screen.</td></tr>
									<?php }?>
									<tr><td colspan=2><font color='<?=$sTextColor?>'><b><li>You may add products to the new image from the main image management screen.</td></tr>
									<?php If ( ACCNT_ReturnRights("PHPJK_IG_ADD_PROD_2IMAGES") ) {?>
									<tr><td colspan=2><font color='<?=$sTextColor?>'><b><li>You may add miscellaneous links to the new image from the main image management screen.</td></tr>
									<?php }?>
									<tr><td colspan=2><font color='<?=$sTextColor?>'><b><li>You may add Alternate View Images to the new image from the main image management screen.</td></tr>
								</table>
								<br><br>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BGColor2"]?> align=center>
								<input type='image' src="../../Images/SchemeBased/<?=$iTextScheme?>/<?=$iColorScheme?>/AddImage.gif" style="BORDER: none; vertical-align: sub;" onClick='document.NewImage.sAction.value="New"'>
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
	//*																					*
	//*																					*
	//************************************************************************************
	Function DispGalleryName()
	{
		Global $iGalleryUnq;
		
		$sQuery			= "SELECT Name FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow["Name"];
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
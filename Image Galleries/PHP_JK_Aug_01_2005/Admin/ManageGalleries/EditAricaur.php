<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");

	set_time_limit(20000);
	
	$iGalleryUnq		= Trim(Request("iGalleryUnq"));
	$iDBLoc				= Trim(Request("iDBLoc"));
	$iCategoryUnq		= Trim(Request("iCategoryUnq"));
	$iThumbComponent	= G_ADMINISTRATION_ASPImageInstalled();
	
	WriteScripts();
	
	If ( ACCNT_ReturnRights("PHPJK_IG_ARICAUR") ) {
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
		Global $iDBLoc;
		Global $iLoginAccountUnq;
		Global $sGalleryPath;
		Global $GFL;
		
		$sAction	= Trim(Request("sAction"));
		$sError		= "";
		$sSuccess	= "";
		
		If ( $iGalleryUnq != "" )
		{
			If ( $sAction == "EditAricaur" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
					echo "<br><table border=1 cellpadding=5 cellspacing=0>";
					echo "<tr><td colspan=3>&nbsp;</td><td colspan=2 align=center><b>These two images should be identical<br>(their size can differ)</td></tr>";
					echo "<tr><td align=center><b>Image</td><td align=center><b>Alt Image</td><td align=center><b>Result</td><td><b>Gallery Thumb</td><td align=center><b>Aricaur Thumb</td></tr>";
					$sHow = trim(Request("sHow"));	// CHANGE or SAME
					// loop through every image within this gallery
					$sQuery	= "SELECT I.ImageUnq, I.Image, I.Thumbnail, I.Image2, I.Image3, I.Image4, I.Image5, I.XSize2, I.YSize2, I.XSize3, I.YSize3, I.XSize4, I.YSize4, I.XSize5, I.YSize5, I.ThumbUL, I.Alt2UL, I.Alt3UL, I.Alt4UL, I.Alt5UL, I.Aricaur FROM Images I, ImagesInGallery IG WHERE I.ImageUnq = IG.ImageUnq AND IG.GalleryUnq = " . $iGalleryUnq . " AND IG.PrimaryG = IG.GalleryUnq";
					$rsRecordSet = DB_Query($sQuery);
					While ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						$sResults	= "";
						$sAricaur	= trim($rsRow["Aricaur"]);
						$iMax		= 0;	// max found file size
						$iNum		= 0;	// chosen image number
						$bReset		= FALSE;
						// only change images if the sHow is CHANGE, or if there hasn't been an Aricaur link created for the image yet
						If ( ( $sHow == "CHANGE" ) || ( $sAricaur == "" ) )
						{
							For ( $x=2;$x<6;$x++ )
							{
								$sImageName[$x]	= trim($rsRow["Image" . $x]);
								If ( $sImageName[$x] != "" )
								{
									$iAccountUnq	= trim($rsRow["Alt" . $x . "UL"]);
									$iFileSize		= 0;	// this is returned from GetAltType ByRef
									$iXSize			= $rsRow["XSize" . $x];	// this is returned from GetAltType ByRef
									$iYSize			= $rsRow["YSize" . $x];	// this is returned from GetAltType ByRef
									$iType[$x]		= 0;	// this is returned from GetAltType ByRef
									$sStatus[$x]	= GetAltType($x, $rsRow["ImageUnq"], $sImageName[$x], $iAccountUnq, $iGalleryUnq, $sGalleryPath, $iType[$x], $iFileSize, $iXSize, $iYSize, $x);
									If ( $sStatus[$x] == "" )
									{
										// only check alt view images that end in JPG, PSD, TIF, TGA, FLASHPIX (FPX) or PNG
										If ( ( $iType[$x] == $GFL ) || ( $iType[$x] == 3 ) )		// 3=native php
										{
											If ( ( $iXSize <= 240 ) || ( $iYSize <= 240 ) )
											{
												// don't want to try and add images that are too small
											}Else{
												If ( $iMax < (intval($iXSize) * intval($iYSize)) )
												{
													$iMax = intval($iXSize) * intval($iYSize);
													$iNum = $x;
												}
											}
										}Else{
											// if none of the alt view images are file types we can use, this will be set several times
											$sStatus[$x] = "Alt View Image # " . $x . " is not a compatable file type.";
										}
									}
								}
							}

							If ( $iNum > 0 )
							{
								// we found one! create the thumbnail and change the Aricaur column
								$sStatus[$iNum] = CreateThumb($sImageName[$iNum], $iAccountUnq, $iGalleryUnq, $sGalleryPath, $iType[$iNum]);
								If ( $sStatus[$iNum] == "" )
								{
									$sResults = "Aricaur link created!";
									DB_Update ("UPDATE Images SET Aricaur = '" . $iNum . "', aricaurthumb = '" . $iNum . "' WHERE ImageUnq = " . $rsRow["ImageUnq"]);
								}Else{
									$sResult = $rsRow["ImageUnq"] . ". Unable to create the thumbnail for the chosen Alt Image.";
									$bReset = TRUE;
								}
								// this is in case we are converting a .tif, .tga, .png or PSD. All these are made into .jpg thumbs so we can't use the original 
								// 	alt view image's extension
								$sTempName	= $sImageName[$iNum];
								$sTempName	= str_replace(".tif", ".jpg", $sTempName);
								$sTempName	= str_replace(".tga", ".jpg", $sTempName);
								$sTempName	= str_replace(".png", ".jpg", $sTempName);
								$sTempName	= str_replace(".psd", ".jpg", $sTempName);
								$sFilePath	= DOMAIN_Conf("IG") . "/" . $iAccountUnq . "/" . $iGalleryUnq . "/Aricaur/" . $sTempName;
								$sFilePath	= str_replace("\\", "/", $sFilePath);
								$sFilePath	= str_replace("//", "/", $sFilePath);
								echo "<tr><td valign=top>" . $rsRow["ImageUnq"] . "|" . trim($rsRow["Image"]) . "</td><td valign=top>" . $sTempName . "</td><td valign=top>" . $sResults . "</td><td valign=top><img src='" . "../../Attachments/DispThumb.php?sAccountUnq=" . $iAccountUnq . "&sThumbnail=" . trim($rsRow["Thumbnail"]) . "&iGalleryUnq=" . $iGalleryUnq . "'></td><td valign=top><img src='" . $sFilePath . "'></td></tr>";
								echo "<tr><td colspan=3>&nbsp;</td><td colspan=2 align=center><b>If the images are different, <a href='../ManageImages/EditAricaur.php?iGalleryUnq=" . $iGalleryUnq . "&iDBLoc=0&iImageUnq=" . $rsRow["ImageUnq"] . "' class='MediumNavPage' target='_blank'>choose another image.</a><br><br></td></tr>";
							}Else{
								$sResults = $rsRow["ImageUnq"] . ". No Alternate View Images";
								$bReset = TRUE;
								echo "<tr><td valign=top>" . trim($rsRow["Image"]) . "</td><td valign=top>none exist</td><td valign=top>" . $sResults . "</td><td valign=top><img src='" . "../../Attachments/DispThumb.php?sAccountUnq=" . trim($rsRow["ThumbUL"]) . "&sThumbnail=" . trim($rsRow["Thumbnail"]) . "&iGalleryUnq=" . $iGalleryUnq . "'></td><td valign=top>none created</td></tr>";
							}
						}Else{
							echo "<tr><td colspan=3 align=center>" . $rsRow["ImageUnq"] . ". Aricaur link already exists - no change.</td></tr>";
						}
						
						If ( $bReset )
						{
							// figure out if we are resetting the aricaur image
							//	if we are, reset it, AND delete the aricaur thumb too
							If ( $sAricaur != "" )
							{
								$sFilePath		= $sGalleryPath . "/" . $iAccountUnq . "/" . $iGalleryUnq . "/Aricaur/" . trim($rsRow["Image" . $sAricaur]);
								$sFilePath		= str_replace("\\", "/", $sFilePath);
								$sFilePath		= str_replace("//", "/", $sFilePath);
								$sFilePath		= str_replace("//", "/", $sFilePath);
								DelAnyFile($sFilePath, $iAccountUnq);
								DB_Update ("UPDATE Images SET Aricaur = '', aricaurthumb = '' WHERE ImageUnq = " . $rsRow["ImageUnq"]);
							}
						}
					}
					echo "</table>";
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot manage Aricaur links within it.<br>";
				}
			}
			
			If ( $sError != "" )
				DOMAIN_Message($sError, "ERROR");
			If ( $sSuccess != "" )
				DOMAIN_Message($sSuccess, "SUCCESS");

			WriteForm();
		}Else{
			DOMAIN_Message("Missing iGalleryUnq. Unable to edit the gallery.", "ERROR");
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
		Global $iGalleryUnq;
		Global $aVariables;
		Global $aValues;
		Global $iDBLoc;
		Global $iThumbComponent;
		Global $GFL;
		Global $iCategoryUnq;
		
		$sBGColor = $GLOBALS["BGColor2"];
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Manage Aricaur Links</b></font>
					<br>
					<b>Manage Aricaur links for gallery: </b> <?=ReturnGalleryName($iGalleryUnq)?>
					<br><br>
					This will add Aricaur links to all the images within this gallery. When adding Aricaur
					links, this script will choose the Alternate View Image with the highest resolution (smaller than the 54 megabyte limit) 
					as the Aricaur Primary Image. 
					<br><br>
					<?php 
					If ( $iThumbComponent == $GFL ) {
						Echo "Aricaur links will be generated using GflAx for JPG, PSD, TIFF (uncompressed - no LZW), TGA or PNG Alternate View Images.";
					}Else{
						Echo "Aricaur links will be generated using PHP Native code for JPG, BMP and PNG Alternate View Images.";
					}
					?>
					GIF files are not allowed.
					<br><br>
					Links will only be added to images with at least one Alternate View Image.
					<br><br>
					Referenced images within the gallery will not be changed.
					<form name='EditAricaur' action='EditAricaur.php' method='post'>
					<?php 
					$aVariables[0] = "sAction";
					$aVariables[1] = "iDBLoc";
					$aVariables[2] = "iGalleryUnq";
					$aVariables[3] = "iCategoryUnq";
					$aValues[0] = "EditAricaur";
					$aValues[1] = $iDBLoc;
					$aValues[2] = $iGalleryUnq;
					$aValues[3] = $iCategoryUnq;
					Echo DOMAIN_Link("P");
					?>
					<table width=100% border=1 cellpadding=0 cellspacing=0><tr><td>
					<table width=100% border=0 cellpadding=5 cellspacing=0>
						<tr>
							<td align=center width=50%>
								<input type='radio' name='sHow' value='CHANGE' checked>
							</td>
							<td align=center width=50%>
								<input type='radio' name='sHow' value='SAME'>
							</tD>
						</tr>
						<tr>
							<td align=center>
								If necessary, change the Alternate View Image on images that already have an Aricaur link.
							</td>
							<td align=center>
								Do not change anything on images that already have an Aricaur link.
							</tD>
						</tr>
					</table>
					</td></tr></table>
					<center>
					<br><br>
					<input type='submit' value=' Add Links '>
					<br>
					Please be patient. This can take some time to process all the images in the gallery (it's creating thumbnails).
					</form>
				</td>
			</tr>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		<?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function ReturnGalleryName($iGalleryUnq)
	{
		$sQuery			= "SELECT Name FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow[0];
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
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function GetAltType($iAltImageNum, $iImageUnq, &$sImageName, $iAccountUnq, $iGalleryUnq, $sGalleryPath, &$iType, &$iFileSize, &$iXSize, &$iYSize, $x)
	{
		Global $iThumbComponent;
		Global $GFL;
		
		$sStatus = "";
		$bIsTif = False;
		$sFilePath	= $sGalleryPath . "/" . $iAccountUnq . "/" . $iGalleryUnq . "/" . $sImageName;
		$sFilePath	= str_replace("\\", "/", $sFilePath);
		$sFilePath	= str_replace("//", "/", $sFilePath);
		$sFilePath	= str_replace("//", "/", $sFilePath);
		If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
		{
			// check that file size is less than 45 megs
			$iFileSize = filesize($sFilePath);
			If ( $iFileSize <= 47000000 )	// should be 47185920 but want a bit of slack
			{
				// get the type
				If ( $iThumbComponent == $GFL )
				{
					// turn off all error reporting because this COM object won't report if the file is not
					//	really an image. Then it'll just crash when trying to LoadBitmap. If the file uploaded
					//	is something like an Excel file (w/o X or Y dimensions) then we just want the 
					//	$1XSize and $1YSize to be left as 0.
					error_reporting(0);
					$objGFL = new COM("GflAx.GflAx");
					$objGFL->EnableLZW = TRUE;	// must do this to be able to process tif images (see below) otherwise it crashes when trying to open tif's
					$objGFL->LoadBitmap($sFilePath);
					If ( $objGFL->SaveFormat == 5 )		// 5 is TIF & since Aricaur doesn't do LZW (it does TIF, but GflAx can't tell me if it's LZW or not) then I have to convert it to JPG and then make the thumbnail
					{
						// convert to .jpg
						$sNewFilePath = str_replace(".tif", ".jpg", $sFilePath);
						$sNewFilePath = str_replace(".TIF", ".jpg", $sNewFilePath);
						$sImageName = str_replace(".TIF", ".jpg", str_replace(".tif", ".jpg", $sImageName));
						$objGFL->SaveFormat = 1;
						$objGFL->SaveBitmap($sNewFilePath);
						$objGFL->LoadBitmap($sNewFilePath);
						DB_Update ("UPDATE Images SET ImageSize" . $iAltImageNum . " = " . filesize($sNewFilePath) . ", Image" . $iAltImageNum . " = '" . $sImageName . "' WHERE ImageUnq = " . $iImageUnq);
						unlink($sFilePath);	// delete the original tif
						$bIsTif = True;
					}Else{
						$objGFL->EnableLZW = FALSE;		// LZW is not allowed w/ Aricaur - this should also stop GIF's
					}
					$iXSize	= $objGFL->Width;
					$iYSize	= $objGFL->Height;
					If ( $iXSize != 0 )
						$iType = $GFL;
					If ( $bIsTif )	// if its a tif, make sure the x and y are updated (uploading images sometimes doesn't set it w/ gflax!)
						DB_Update ("UPDATE Images SET XSize" . $iAltImageNum . " = " . $iXSize . ", YSize" . $iAltImageNum . " = " . $iYSize . " WHERE ImageUnq = " . $iImageUnq);
DB_Update ("UPDATE Images SET XSize" . $iAltImageNum . " = " . $iXSize . ", YSize" . $iAltImageNum . " = " . $iYSize . " WHERE ImageUnq = " . $iImageUnq);
					unset($objGFL);
					error_reporting(E_ALL ^ E_NOTICE);	// set error reporting back to the default
				}Else{
					// use native PHP
				    $image_info = getImageSize($sFilePath);
				    
				    switch ($image_info['mime']) {
				        case 'image/jpeg':
				            if (imagetypes() & IMG_JPG)  {
				                $imgSource = imageCreateFromJPEG($sFilePath);
				                $iType = 3;
				            }
				            break;
				        case 'image/png':
				            if (imagetypes() & IMG_PNG)  {
				                $imgSource = imageCreateFromPNG($sFilePath);
				                $iType = 3;
				            }
				            break;
				        case 'image/wbmp':
				            if (imagetypes() & IMG_WBMP)  {
				                $imgSource = imageCreateFromWBMP($sFilePath);
				                $iType = 3;
				            }
				            break;
				    }
				    If ( $iType == 3 ) {
				    	If ( ( $iXSize == "" ) || ( $iXSize == "0" ) )	// check that the X, Y dimensions exist
				    	{
				        	$iXSize = imagesx($imgSource);
				        	$iYSize = imagesy($imgSource);
				        }
					}
				}
			}Else{
				$sStatus = "Alt View Image # " . $x . " is larger than 45 megabytes and will not be used.";
			}
		}Else{
			$sStatus = "The actual file for Alt View Image # " . $x . " could not be found.";
		}	
		
		Return $sStatus;
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function CreateThumb($sImageName, $iAccountUnq, $iGalleryUnq, $sGalleryPath, $iType)
	{
		Global $iThumbComponent;
		Global $GFL;
		Global $iLoginAccountUnq;
	
		$intXSize		= 240;
		$sFilePath		= $sGalleryPath . "/" . $iAccountUnq . "/" . $iGalleryUnq . "/Aricaur/";
		$sFilePath		= str_replace("\\", "/", $sFilePath);
		$sFilePath		= str_replace("//", "/", $sFilePath);
		$sFilePath		= str_replace("//", "/", $sFilePath);
		$sSourcePath	= $sGalleryPath . "/" . $iAccountUnq . "/" . $iGalleryUnq . "/" . $sImageName;
		$sSourcePath	= str_replace("\\", "/", $sSourcePath);
		$sSourcePath	= str_replace("//", "/", $sSourcePath);
		$sSourcePath	= str_replace("//", "/", $sSourcePath);
		// Make sure the Aricaur directory exist...make it if it doesn't
		If ( ! file_exists($sFilePath))
		{
			If ( $GLOBALS["sOS"] == "UNIX" ) {
				mkdirs_nix($sFilePath);
			}Else{
				mkdirs_win($sFilePath);
			}
		}
		$sFilePath		= $sFilePath . $sImageName;

		If ( $iType == $GFL ){
			$objGFL = new COM("GflAx.GflAx");
			$objGFL->EnableLZW = FALSE;
			error_reporting(0);
			$objGFL->LoadBitmap($sSourcePath);
			$intYSize = intval(($intXSize / $objGFL->Width) * $objGFL->Height);
			$objGFL->LoadThumbnail($sSourcePath, $intXSize, $intYSize);
			$objGFL->SaveFormat = 1;
			$objGFL->SaveBitmap($sFilePath);
			error_reporting(E_ALL ^ E_NOTICE);
			unset($objGFL);
		}Else{
			// use native PHP
		    $image_info = getImageSize($sSourcePath) ;
		    
		    switch ($image_info['mime']) {
		        case 'image/jpeg':
		            if (imagetypes() & IMG_JPG)  {
		                $imgSource = imageCreateFromJPEG($sSourcePath) ;
		            }
		            break;
		        case 'image/png':
		            if (imagetypes() & IMG_PNG)  {
		                $imgSource = imageCreateFromPNG($sSourcePath) ;
		            }
		            break;
		        case 'image/wbmp':
		            if (imagetypes() & IMG_WBMP)  {
		                $imgSource = imageCreateFromWBMP($sSourcePath) ;
		            }
		            break;
		    }

	        $iSourceXSize = imagesx($imgSource);
	        $iSourceYSize = imagesy($imgSource);

	        // thumbnail width = target * original width / original height
	        $intYSize = intval(($intXSize / $iSourceXSize) * $iSourceYSize);
	        $imgThumb = imageCreateTrueColor($intXSize,$intYSize);
	        
	        imageCopyResampled($imgThumb, $imgSource, 0, 0, 0, 0, $intXSize, $intYSize, $iSourceXSize, $iSourceYSize);
	        
	        imageJPEG($imgThumb,$sFilePath);
	        
	        imageDestroy($imgSource);
	        imageDestroy($imgThumb);
		}

		// get the file size and increment the amount of HD space they are using
		If ( file_exists($sFilePath))
		{
			$iFileSize = filesize($sFilePath);
			G_ADMINISTRATION_IncrementHDSpaceUsed($iAccountUnq, $iFileSize);
		}
	}
	//************************************************************************************
?>
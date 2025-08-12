<?php 
	//************************************************************************************
	//*																					*
	//*	This saves the file (if any).													*
	//*		this function has the possibility of changing the file name, so must pass 	*
	//*		the file name in by reference												*
	//*																					*
	//************************************************************************************
	Function G_UPLOAD_SaveFile($sFileFormName, $sType, &$sFileName)
	{
		Global $iLoginAccountUnq;
		Global $_FILES;
		Global $sTempName;
		Global $sExtension;
		Global $iFileSize;
		Global $sGalleryPath;
		Global $sAccountUnq;
		Global $iGalleryUnq;
		
		$sError		= "";

		$sFileName	= $_FILES[$sFileFormName]['name'];
		$sTempName	= $_FILES[$sFileFormName]['tmp_name'];
		$FileError	= $_FILES[$sFileFormName]['error'];

		If ( $sFileName != "" )
		{
			// check to make sure that the file is not too big
			$iFileSize		= $_FILES[$sFileFormName]["size"];
			$iHDSpaceLeft	= G_ADMINISTRATION_HDSpaceLeft($iLoginAccountUnq);
			$iTemp			= Trim(G_ADMINISTRATION_MaxFileSize($iLoginAccountUnq));
			
			If ( ( $iTemp < $iFileSize ) && ( $iTemp != -1 ) ) {
				$sError = "The file " . $sFileName . " exceeds the maximum size of " . $iTemp . " k. Please upload a smaller file.<br>";
			}Else{
				If ( ( ( $iHDSpaceLeft - $iFileSize ) > 0 ) || ( $iHDSpaceLeft == -1 ) )
				{
					$sExtension	= substr(strrchr($_FILES[$sFileFormName]['name'], '.'), 1); 
					$sFileName	= FixFilename($sFileName, $sType);
					$fldr		= $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/Thumbnails/";
					$fldr		= str_replace("\\", "/", $fldr);
					$fldr		= str_replace("//", "/", $fldr);
					$fldr		= str_replace("//", "/", $fldr);

					If ( $sType == "THUMB" ) {
						$sFilePath	= $fldr . $sFileName;
					}Else{
						$sFilePath	= $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/" . $sFileName;
					}
					$sFilePath	= str_replace("\\", "/", $sFilePath);
					$sFilePath	= str_replace("//", "/", $sFilePath);
					$sFilePath	= str_replace("//", "/", $sFilePath);
					$sFilePath	= str_replace("//", "/", $sFilePath);
					$sFilePath	= str_replace("//", "/", $sFilePath);	// do it twice because we get quads sometimes
					
					If ( $GLOBALS["sOS"] = "UNIX" )
						$fldr = str_replace("\\", "/", $fldr);

					// Make sure the directories exist...make them if they don't
					If ( ! file_exists($fldr))
					{
						If ( $GLOBALS["sOS"] == "UNIX" ) {
							mkdirs_nix($fldr);
						}Else{
							mkdirs_win($fldr);
						}
					}

					// save it
					If ( $FileError == "2" )
					{
						$sError = "File uploaded exceeds max file size as set in the PHPJK_IG_UPLOADBYTES ADV or IMAGEGALLERY_INITIAL_UPLOADBYTES Conf Var.";
					}Else{
						If (move_uploaded_file($sTempName, $sFilePath))
						{
							If ( $sType == "PRIMARY" ) {
								G_ADMINISTRATION_IncrementUL($iLoginAccountUnq);
								G_ADMINISTRATION_IncrementHDSpaceUsed($iLoginAccountUnq, $iFileSize);
							}ElseIf ( ( $sType == "ALTERNATE" ) || ( $sType == "THUMB" ) ) {
								G_ADMINISTRATION_IncrementHDSpaceUsed($iLoginAccountUnq, $iFileSize);
							}
						}Else{
							$sError = "Unable to save file: " . $sFileName . "<BR>";
						}
					}
				}Else{
					$sError = "The file " . $sFileName . " was not processed because you have used up all of your disk space.<br>";
				}
			}
		}
		Return $sError;
	}
	//************************************************************************************


	
	//************************************************************************************
	//*																					*
	//*	This makes a thumbnail.															*
	//*																					*
	//************************************************************************************
	Function G_UPLOAD_MakeThumb(&$sThumbname, $sSourceFileName)
	{
		Global $iThumbComponent;
		Global $ASPIMAGE;
		Global $GFL;
		Global $sGalleryPath;
		Global $sAccountUnq;
		Global $iGalleryUnq;
		Global $iLoginAccountUnq;
		
		$sError = "";

		If ( Trim($sSourceFileName) != "" )
		{
			$intXSize	= intval(DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH"));
			$sThumbname	= FixFilename($sSourceFileName, "THUMB");
			$sFilePath	= $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/";
			$sFilePath	= str_replace("\\", "/", $sFilePath);
			$sFilePath	= str_replace("//", "/", $sFilePath);
			$sFilePath	= str_replace("//", "/", $sFilePath);

			// Make sure the thumbnail directory exist...make it if it doesn't
			If ( ! file_exists($sFilePath . "Thumbnails/"))
			{
				If ( $GLOBALS["sOS"] == "UNIX" ) {
					mkdirs_nix($sFilePath . "Thumbnails/");
				}Else{
					mkdirs_win($sFilePath . "Thumbnails/");
				}
			}
			
			If ( $iThumbComponent == $ASPIMAGE ) {
				$objASPIMAGE = new COM("ASPIMAGE.Image");
				$objASPIMAGE->LoadImage($sFilePath . $sSourceFileName);
				If ( ( ($objASPIMAGE->ImageFormat==1) || ($objASPIMAGE->ImageFormat==2) || ($objASPIMAGE->ImageFormat==3) ) && ($objASPIMAGE->MaxX>0) )
				{
					$intYSize = intval(($intXSize / $objASPIMAGE->MaxX) * $objASPIMAGE->MaxY);
					$objASPIMAGE->Resize($intXSize, $intYSize);
					$objASPIMAGE->FileName = ($sFilePath . "Thumbnails/" . $sThumbname);
					$objASPIMAGE->SaveImage;
				}Else{
					Echo "Unable to use ASPIMAGE to create a thumbnail from the source image (" . $sFilePath . $sSourceFileName . ") file type.<br>";
				}
				unset($objASPIMAGE);
			}ElseIf ( $iThumbComponent == $GFL ){
				$objASPIMAGE = new COM("GflAx.GflAx");
				$objASPIMAGE->EnableLZW = TRUE;
				error_reporting(0);
				$objASPIMAGE->LoadBitmap($sFilePath . $sSourceFileName);
				//If ( Err.Number != 0 ) {
				//	$sError = "Unable to create thumbnail for file: <b>" . $sSourceFileName . "</b>. The file type is not supported by the GflAx component.<br>";
				//}Else{
					$intYSize = intval(($intXSize / $objASPIMAGE->Width) * $objASPIMAGE->Height);
					$objASPIMAGE->LoadThumbnail($sFilePath . $sSourceFileName, $intXSize, $intYSize);
					$objASPIMAGE->SaveFormat = 1;
					$objASPIMAGE->SaveBitmap($sFilePath . "Thumbnails/" . $sThumbname);
				//}
				error_reporting(E_ALL ^ E_NOTICE);
				unset($objASPIMAGE);
			}Else{
				// use native PHP
			    $image_info = getImageSize($sFilePath . $sSourceFileName) ;
			    
			    switch ($image_info['mime']) {
			        case 'image/gif':
			            if (imagetypes() & IMG_GIF)  {
			                $imgSource = imageCreateFromGIF($sFilePath . $sSourceFileName) ;
			            } else {
			                $sError = 'GIF images are not supported<br />';
			            }
			            break;
			        case 'image/jpeg':
			            if (imagetypes() & IMG_JPG)  {
			                $imgSource = imageCreateFromJPEG($sFilePath . $sSourceFileName) ;
			            } else {
			                $sError = 'JPEG images are not supported<br />';
			            }
			            break;
			        case 'image/png':
			            if (imagetypes() & IMG_PNG)  {
			                $imgSource = imageCreateFromPNG($sFilePath . $sSourceFileName) ;
			            } else {
			                $sError = 'PNG images are not supported<br />';
			            }
			            break;
			        case 'image/wbmp':
			            if (imagetypes() & IMG_WBMP)  {
			                $imgSource = imageCreateFromWBMP($sFilePath . $sSourceFileName) ;
			            } else {
			                $sError = 'WBMP images are not supported<br />';
			            }
			            break;
			        default:
			            $sError = $image_info['mime'].' images are not supported<br />';
			            break;
			    }

			    If ( $sError == "" ) {
			        $iSourceXSize = imagesx($imgSource);
			        $iSourceYSize = imagesy($imgSource);

			        // thumbnail width = target * original width / original height
			        $intYSize = intval(($intXSize / $iSourceXSize) * $iSourceYSize);
			        $imgThumb = imageCreateTrueColor($intXSize,$intYSize);
			        
			        imageCopyResampled($imgThumb, $imgSource, 0, 0, 0, 0, $intXSize, $intYSize, $iSourceXSize, $iSourceYSize);
			        
			        imageJPEG($imgThumb,$sFilePath . "Thumbnails/" . $sThumbname);
			        
			        imageDestroy($imgSource);
			        imageDestroy($imgThumb);
			    }
			}

			// get the file size and increment the amount of HD space they are using
			$sFilePath = $sFilePath . "Thumbnails/" . $sThumbname;
			$sFilePath	= str_replace("\\", "/", $sFilePath);
			$sFilePath	= str_replace("//", "/", $sFilePath);
			If ( file_exists($sFilePath))
			{
				$iFileSize = filesize($sFilePath);
				G_ADMINISTRATION_IncrementHDSpaceUsed($iLoginAccountUnq, $iFileSize);
			}
		}
		Return $sError;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Uses ASPIMAGE to make a copy of the file $sFileName.								*
	//*																					*
	//************************************************************************************
	Function G_UPLOAD_MakeFile($sSourceFileName, $sPixelsWide, $sPercentWide, $iFileNum, $iOrigX, $iOrigY, &$sDestFileName)
	{
		Global $ASPIMAGE;
		Global $GFL;
		Global $sGalleryPath;
		Global $sAccountUnq;
		Global $iGalleryUnq;
		Global $iThumbComponent;
		Global $iLoginAccountUnq;
		Global $iXSize;
		Global $iYSize;
		Global $iFileSize;
		
		$sFilePath	= $sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\";
		$sFilePath	= str_replace("\\", "/", $sFilePath);
		$sFilePath	= str_replace("//", "/", $sFilePath);
		$sError		= "";

		// get the image dimensions
		If ( $iThumbComponent == $ASPIMAGE )
		{
			$objASPIMAGE = new COM("ASPIMAGE.Image");
			$objASPIMAGE->LoadImage($sFilePath . $sSourceFileName);
			If ( ( ($objASPIMAGE->ImageFormat==1) || ($objASPIMAGE->ImageFormat==2) || ($objASPIMAGE->ImageFormat==3) ) && ($objASPIMAGE->MaxX>0) )
			{
				$iXSize	= $objASPIMAGE->MaxX;
				$iYSize	= $objASPIMAGE->MaxY;
			}Else{
				$sError = "Only .jpg, .bmp or .png files may be automatically resized and copied.<br>";
			}
		}ElseIf ( $iThumbComponent == $GFL )
		{
			error_reporting(0);
			$objASPIMAGE = new COM("GflAx.GflAx");
			$objASPIMAGE->EnableLZW = TRUE;
			$objASPIMAGE->LoadBitmap($sFilePath . $sSourceFileName);
			//If ( Err.Number != 0 )
			//{
			//	$sError = "Unable to resize or copy this file. The file type is not supported by the GflAx component.";
			//}Else{
				$iXSize	= $objASPIMAGE->Width;
				$iYSize	= $objASPIMAGE->Height;
			//}
			error_reporting(E_ALL ^ E_NOTICE);	// set error reporting back to the default
		}Else{
			// use native PHP
		    $image_info = getImageSize($sFilePath . $sSourceFileName) ;
		    
		    switch ($image_info['mime']) {
		        case 'image/gif':
		            if (imagetypes() & IMG_GIF)  {
		                $imgSource = imageCreateFromGIF($sFilePath . $sSourceFileName) ;
		            } else {
		                $sError = 'GIF images are not supported<br />';
		            }
		            break;
		        case 'image/jpeg':
		            if (imagetypes() & IMG_JPG)  {
		                $imgSource = imageCreateFromJPEG($sFilePath . $sSourceFileName) ;
		            } else {
		                $sError = 'JPEG images are not supported<br />';
		            }
		            break;
		        case 'image/png':
		            if (imagetypes() & IMG_PNG)  {
		                $imgSource = imageCreateFromPNG($sFilePath . $sSourceFileName) ;
		            } else {
		                $sError = 'PNG images are not supported<br />';
		            }
		            break;
		        case 'image/wbmp':
		            if (imagetypes() & IMG_WBMP)  {
		                $imgSource = imageCreateFromWBMP($sFilePath . $sSourceFileName) ;
		            } else {
		                $sError = 'WBMP images are not supported<br />';
		            }
		            break;
		        default:
		            $sError = $image_info['mime'].' images are not supported<br />';
		            break;
		    }
		    If ( $sError == "" ) {
		        $iXSize = imagesx($imgSource);
		        $iYSize = imagesy($imgSource);
			}
		}
		
		// calculate the new dimensions of the image to create
		If ( $sPixelsWide != "" )
		{
			If ( is_numeric($sPixelsWide) )
			{
				If ( $sPixelsWide > 9999 )
					$sError = $sError . "Please make sure the width of the image does not exceed 9999 pixels.<br>";
			}Else{
				$sError = $sError . "Please make sure the width of the new image is a number.<br>";
			}
			If ( $sError == "" )
			{
				$intXSize = $sPixelsWide;
 				$intYSize = ($intXSize / $iXSize) * $iYSize;
			}
		}ElseIf ( $sPercentWide != "" ){
			$sPercentWide = str_replace("%", "", $sPercentWide);
			If ( is_numeric($sPercentWide) )
			{
				If ( $sPercentWide > 500 )
					$sError = $sError . "Please make sure the percentage size increase of the image does not exceed 500 percent of the original.<br>";
			}Else{
				$sError = $sError . "Please make sure the width of the new image is a numeric percentage.<br>";
			}
			If ( $sError == "" )
			{
				If ( is_numeric($sPercentWide) )
				{
					$intXSize = (int)(($sPercentWide/100) * $iOrigX);
					$intYSize = (int)(($sPercentWide/100) * $iOrigY);	// same calculation because in order to keep the aspect ratio, we must use the same % for both width and height
				}Else{
					$sError = $sError . "Invalid image width percentage for image.<BR>";
				}
			}
		}Else{
			// add one because the $iFileNum starts at 0. users start at 1
			$sError = $sError . "Invalid or missing width of image. Please enter the width in pixels or a percentage in the form below.<BR>";
		}
		
		// save the new image and update the users info
		If ( $sError == "" )
		{
			$sDestFileName	= FixFilename($sSourceFileName, "SECONDARY");		// this will make a unique name for this copy of the file in case prepending w/ $iFileNum doesnt
			
			If ( $iThumbComponent == $ASPIMAGE )
			{
				$objASPIMAGE->Resize ($intXSize, $intYSize);
				$objASPIMAGE->FileName = ($sFilePath . $sDestFileName);
				$objASPIMAGE->SaveImage;
				unset($objASPIMAGE);
			}ElseIf ( $iThumbComponent == $GFL )
			{
				$objASPIMAGE->Resize ($intXSize, $intYSize);
				$objASPIMAGE->SaveBitmap ($sFilePath . $sDestFileName);
				unset($objASPIMAGE);
			}Else{
				// use native PHP
				$imgDest = imagecreatetruecolor($intXSize, $intYSize);
			    imagecopyresampled ( $imgDest, $imgSource, 0, 0, 0, 0, $intXSize, $intYSize, $iXSize, $iYSize);
			    ImageDestroy($imgSource);
			    
			    switch ($image_info['mime']) {
			        case 'image/gif':
			            if (imagetypes() & IMG_GIF)  {
			                imageGIF($imgDest, $sFilePath . $sDestFileName) ;
			            } else {
			                $sError = 'GIF images are not supported<br />';
			            }
			            break;
			        case 'image/jpeg':
			            if (imagetypes() & IMG_JPG)  {
			                ImageJPEG($imgDest, $sFilePath . $sDestFileName, 100) ;
			            } else {
			                $sError = 'JPEG images are not supported<br />';
			            }
			            break;
			        case 'image/png':
			            if (imagetypes() & IMG_PNG)  {
			                imagePNG($imgDest, $sFilePath . $sDestFileName) ;
			            } else {
			                $sError = 'PNG images are not supported<br />';
			            }
			            break;
			        case 'image/wbmp':
			            if (imagetypes() & IMG_WBMP)  {
			                imageWBMP($imgDest, $sFilePath . $sDestFileName) ;
			            } else {
			                $sError = 'WBMP images are not supported<br />';
			            }
			            break;
			        default:
			            $sError = $image_info['mime'].' images are not supported<br />';
			            break;
			    }
				imagedestroy($imgDest);
			}

			$iXSize			= $intXSize;
			$iYSize			= $intYSize;
			$iHDSpaceLeft	= G_ADMINISTRATION_HDSpaceLeft($iLoginAccountUnq);
			
			// get the file size and increment the HD space the user is using
			$sFilePath = $sFilePath . $sDestFileName;
			If ( file_exists($sFilePath) )
			{
				$iFileSize = filesize($sFilePath);
				
				// check to make sure the user doesn't exceed their hard disk space limitations
				If ( ( ( $iHDSpaceLeft - $iFileSize ) > 0 ) || ( $iHDSpaceLeft == -1 ) ) {
					G_ADMINISTRATION_IncrementHDSpaceUsed($iLoginAccountUnq, $iFileSize);
				}Else{
					// must del the file and report an error
					unlink($sFilePath);
					$sError = "The file " . $sSourceFileName . " was not processed because you have used up all of your disk space.<br>";
				}
			}
		}
		Return $sError;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Uses ASPIMAGE to get the dimensions of the image.								*
	//*																					*
	//************************************************************************************
	Function G_UPLOAD_GetDimensions($sFileName)
	{
		Global $ASPIMAGE;
		Global $GFL;
		Global $sGalleryPath;
		Global $sAccountUnq;
		Global $iGalleryUnq;
		Global $iYSize;
		Global $iXSize;
		Global $iThumbComponent;
		
		$sError = "";
		
		$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/";
		$sFilePath = str_replace("\\", "/", $sFilePath);
		$sFilePath = str_replace("//", "/", $sFilePath);

		If ( $iThumbComponent == $ASPIMAGE )
		{
			error_reporting(0);	// if we can't get the X and Y dimensions, it's ok to continue
			$objASPIMAGE = new COM("ASPIMAGE.Image");
			$objASPIMAGE->LoadImage($sFilePath . $sFileName);
			If ( ( ($objASPIMAGE->ImageFormat==1) || ($objASPIMAGE->ImageFormat==2) || ($objASPIMAGE->ImageFormat==3) ) && ($objASPIMAGE->MaxX>0) )
			{
				$iXSize	= $objASPIMAGE->MaxX;
				$iYSize	= $objASPIMAGE->MaxY;
			}Else{
				// this should be ok because sometimes people might not upload images...and those files do not have a "width"
				//G_UPLOAD_GetDimensions = "Invalid or missing width of image number " . $iFileNum+1 . ".<BR>"
			}
			unset($objASPIMAGE);
			error_reporting(E_ALL ^ E_NOTICE);	// set error reporting back to the default
		}ElseIf ( $iThumbComponent == $GFL )
		{
			// turn off all error reporting because this COM object won't report if the file is not
			//	really an image. Then it'll just crash when trying to LoadBitmap. If the file uploaded
			//	is something like an Excel file (w/o X or Y dimensions) then we just want the 
			//	$1XSize and $1YSize to be left as 0.
			error_reporting(0);
			$objASPIMAGE = new COM("GflAx.GflAx");
			$objASPIMAGE->EnableLZW = TRUE;
			$objASPIMAGE->LoadBitmap($sFilePath . $sFileName);
			$iXSize	= $objASPIMAGE->Width;
			$iYSize	= $objASPIMAGE->Height;
			unset($objASPIMAGE);
			error_reporting(E_ALL ^ E_NOTICE);	// set error reporting back to the default
		}Else{
			// use native PHP
		    $image_info = getImageSize($sFilePath . $sFileName) ;
		    
		    switch ($image_info['mime']) {
		        case 'image/gif':
		            if (imagetypes() & IMG_GIF)  {
		                $imgSource = imageCreateFromGIF($sFilePath . $sFileName) ;
		            } else {
		                $sError = 'GIF images are not supported<br />';
		            }
		            break;
		        case 'image/jpeg':
		            if (imagetypes() & IMG_JPG)  {
		                $imgSource = imageCreateFromJPEG($sFilePath . $sFileName) ;
		            } else {
		                $sError = 'JPEG images are not supported<br />';
		            }
		            break;
		        case 'image/png':
		            if (imagetypes() & IMG_PNG)  {
		                $imgSource = imageCreateFromPNG($sFilePath . $sFileName) ;
		            } else {
		                $sError = 'PNG images are not supported<br />';
		            }
		            break;
		        case 'image/wbmp':
		            if (imagetypes() & IMG_WBMP)  {
		                $imgSource = imageCreateFromWBMP($sFilePath . $sFileName) ;
		            } else {
		                $sError = 'WBMP images are not supported<br />';
		            }
		            break;
		        default:
		            $sError = $image_info['mime'].' images are not supported<br />';
		            break;
		    }
		    If ( $sError == "" ) {
		        $iXSize = imagesx($imgSource);
		        $iYSize = imagesy($imgSource);
			}
		}
		Return "";
	}
	//************************************************************************************

	
	//************************************************************************************
	//*																					*
	//*	Looks for and deletes any files and/or thumbnails.								*
	//*																					*
	//************************************************************************************
	Function G_UPLOAD_DelFiles($iImageUnq, $bClearDB)
	{
		Global $sGalleryPath;
		Global $sAccountUnq;
		Global $iGalleryUnq;
		
		// get the file info for all the files (up to five main and one thumbnail) to delete
		$sQuery			= "SELECT I.Image, I.Thumbnail, I.Image2, I.Image3, I.Image4, I.Image5, I.ImageUL, I.ThumbUL, I.Alt2UL, I.Alt3UL, I.Alt4UL, I.Alt5UL, G.GalleryUnq, G.AccountUnq FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK) WHERE I.ImageUnq = " . $iImageUnq . " AND I.ImageUnq = IG.ImageUnq AND IG.GalleryUnq = G.GalleryUnq";
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$aImages[0]	= Trim($rsRow["Image"]);
			$aImages[1]	= Trim($rsRow["Thumbnail"]);
			$aImages[2]	= Trim($rsRow["Image2"]);
			$aImages[3]	= Trim($rsRow["Image3"]);
			$aImages[4]	= Trim($rsRow["Image4"]);
			$aImages[5]	= Trim($rsRow["Image5"]);
			$aUL[0]		= Trim($rsRow["ImageUL"]);
			$aUL[1]		= Trim($rsRow["ThumbUL"]);
			$aUL[2]		= Trim($rsRow["Alt2UL"]);
			$aUL[3]		= Trim($rsRow["Alt3UL"]);
			$aUL[4]		= Trim($rsRow["Alt4UL"]);
			$aUL[5]		= Trim($rsRow["Alt5UL"]);
			
			$sAccountUnq = Trim($rsRow["AccountUnq"]);
			$iGalleryUnq = Trim($rsRow["GalleryUnq"]);
			For ( $x = 0; $x <= 5; $x++)
			{
				// delete the files
				If ( $x == 1 )
				{
					// its the thumbnail
					$sFilePath	= $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/Thumbnails/" . $aImages[$x];
				}Else{
					$sFilePath	= $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/" . $aImages[$x];
				}
				$sFilePath	= str_replace("\\", "/", $sFilePath);
				$sFilePath	= str_replace("//", "/", $sFilePath);
				
				If ( ( file_exists($sFilePath) ) && ( $aImages[$x] != "" ) )
				{	
					$iFileSize = filesize($sFilePath);
					
					// the file exists - delete it
					unlink($sFilePath);
					
					// subtract the num of bytes of the file from the users ttl uploaded bytes
					If ( $aUL[$x] != 0 )
						G_ADMINISTRATION_IncrementHDSpaceUsed($aUL[$x], (-1 * $iFileSize));
				}
			}
			
			// delete any Aricaur images
			For ( $x = 2; $x <= 5; $x++)
			{
				$sFilePath	= $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/Aricaur/" . $aImages[$x];
				$sFilePath	= str_replace("\\", "/", $sFilePath);
				$sFilePath	= str_replace("//", "/", $sFilePath);
				
				If ( ( file_exists($sFilePath) ) && ( $aImages[$x] != "" ) )
				{	
					$iFileSize = filesize($sFilePath);
					
					// the file exists - delete it
					unlink($sFilePath);
					
					// subtract the num of bytes of the file from the users ttl uploaded bytes
					If ( $aUL[$x] != 0 )
						G_ADMINISTRATION_IncrementHDSpaceUsed($aUL[$x], (-1 * $iFileSize));
				}
			}
			
			// subtract one from the number of files they have uploaded - only do this once because it's 
			//	based on image entry in the db, not actual images
			$sVisibility = "PRIVATE";
			$iNumUploaded = Trim(ACCNT_ReturnADV("PHPJK_IG_NUMUPLOADED", "V", $aUL[0], 7, $sVisibility));
			If ( $iNumUploaded == "" )
				$iNumUploaded = 0;
			If ( $iNumUploaded > 0 )
				$iNumUploaded = $iNumUploaded - 1;
			ACCNT_WriteADV("PHPJK_IG_NUMUPLOADED", $iNumUploaded, "V", $aUL[0], $sVisibility);
			
			If ( $bClearDB )
			{
				// clear the columns associated with the files
				DB_Update ("DELETE FROM Images WHERE ImageUnq = " . $iImageUnq);
				DB_Update ("DELETE FROM ImagesInGallery WHERE ImageUnq = " . $iImageUnq);
				DB_Update ("DELETE FROM IGECards WHERE ImageUnq = " . $iImageUnq);
				DB_Update ("DELETE FROM IGImageCRs WHERE ImageUnq = " . $iImageUnq);
				DB_Update ("DELETE FROM IGImageProds WHERE ImageUnq = " . $iImageUnq);
				DB_Update ("DELETE FROM IGMiscLinks WHERE ImageUnq = " . $iImageUnq);
				DB_Update ("DELETE FROM IGSearchResults WHERE ImageUnq = " . $iImageUnq);
				DB_Update ("DELETE FROM IGRaters WHERE ImageUnq = " . $iImageUnq);
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This saves the catalog image. 													*
	//*																					*
	//************************************************************************************
	Function G_UPLOAD_SaveCatalogFile($iCategoryUnq)
	{
		Global $iLoginAccountUnq;
		Global $sGalleryPath;
		
		DelOldCatFiles($iCategoryUnq);
		$sError		= "";
		$sFileName	= $_FILES["File1"]["name"];
		$sTempName	= $_FILES["File1"]["tmp_name"];
		
		If ( $sFileName != "" )
		{
			// check to make sure that:
			//		the file is not too big
			$iFileSize	= $_FILES["File1"]["size"];
			$iTemp		= Trim(G_ADMINISTRATION_MaxFileSize($iLoginAccountUnq));
			If ( ( $iTemp < $iFileSize ) && ( $iTemp != -1 ) )
			{
				$sError = "The file " . $sFileName . " exceeds the maximum size of " . $iTemp . " k. Please upload a smaller file.<br>";
				DOMAIN_Message($sError, "ERROR");
			}Else{
				$sFilePath = DOMAIN_Conf("PHP_JK_WEBROOT") . "/" . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_LOC") . "/" . "CatImage_" . $iCategoryUnq . ".jpg";
				$sFilePath = str_replace("\\", "/", $sFilePath);
				$sFilePath = str_replace("//", "/", $sFilePath);
				$sFilePath = str_replace("//", "/", $sFilePath);

				If ( ! move_uploaded_file($sTempName, $sFilePath))
					$sError = "Unable to save file: " . $sFileName . "<BR>";
			}
		}
		Return $sError;
	}
	//************************************************************************************

	
	//************************************************************************************
	//*																					*
	//*	This deletes the old catalog image.												*
	//*																					*
	//************************************************************************************
	Function DelOldCatFiles($iCategoryUnq)
	{
		If ( $iCategoryUnq != "" )
		{
			// delete the file
			$sFilePath = DOMAIN_Conf("PHP_JK_WEBROOT") . "/" . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_LOC") . "/" . "CatImage_" . $iCategoryUnq . ".jpg";
			//$sFilePath = $_SERVER['DOCUMENT_ROOT'] . "/" . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_LOC") . "/" . "CatImage_" . $iCategoryUnq . ".jpg";
			$sFilePath = str_replace("\\", "/", $sFilePath);
			$sFilePath = str_replace("//", "/", $sFilePath);
			$sFilePath = str_replace("//", "/", $sFilePath);
			// the file exists - delete it
			If ( file_exists($sFilePath) )
				unlink($sFilePath);
		}
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*	This deletes the file passed in. The path to the file from the web root must be	*
	//*		passed in.																	*
	//*																					*
	//************************************************************************************
	Function DelAnyFile($sFilePath, $sAccountUnq)
	{
		If ( $sFilePath != "" )
		{
			$sFilePath	= str_replace("\\", "/", $sFilePath);
			$sFilePath	= str_replace("//", "/", $sFilePath);
			
			If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )	// make sure it's a file in case we try an del a thumbnail that never existed in the first place
			{
				$iFileSize = filesize($sFilePath);

				// the file exists - delete it
				unlink($sFilePath);

				// subtract the num of bytes of the file from the users ttl uploaded bytes
				G_ADMINISTRATION_IncrementHDSpaceUsed($sAccountUnq, (-1 * $iFileSize));
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This simply verifies that the file is a type that the image component that's	*
	//*		installed can work with.													*
	//*																					*
	//************************************************************************************
	Function VerifyFileType($sFileName)
	{
		Global $ASPIMAGE;
		Global $GFL;
		
/*		If ( $iThumbComponent == $ASPIMAGE )
		{
			$objASPIMAGE = new COM("ASPIMAGE.Image")
			$objASPIMAGE.LoadImage(server.mappath($sFileName));
			If ( ( ($objASPIMAGE.ImageFormat==1) || ($objASPIMAGE.ImageFormat==2) || ($objASPIMAGE.ImageFormat==3) ) && ($objASPIMAGE.MaxX>0) )
				Return True;
			$objASPIMAGE = Nothing
		}ElseIf ( $iThumbComponent == $GFL )
		{
			$objASPIMAGE.LoadBitmap(server.mappath($sFileName))
			$iTemp = $objASPIMAGE.SaveFormat
			If ( ( $iTemp > 0 ) And ( $iTemp < 50 ) )
				Return True;
		}*/
	}
	//************************************************************************************
?>
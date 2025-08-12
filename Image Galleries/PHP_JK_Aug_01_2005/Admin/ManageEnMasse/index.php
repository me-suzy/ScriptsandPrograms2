<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");

	$iTemp = DOMAIN_Conf("IMAGEGALLERY_SCRIPTTIMEOUT");
	If ( $iTemp = "" )
		$iTemp = 5400;
	set_time_limit($iTemp);
	
	$iThumbComponent	= G_ADMINISTRATION_ASPImageInstalled();
	$lngNumberUploaded	= 1;
	$bUseASPImage		= Trim(Request("bUseASPImage"));
	$iGalleryUnq		= Trim(Request("iGalleryUnq"));
	$sValidExtensions	= strtoupper(G_ADMINISTRATION_ValidFileExtensions($iLoginAccountUnq));
	$iMaxFileSize		= G_ADMINISTRATION_MaxFileSize($iLoginAccountUnq);
	$iHDSpaceLeft		= G_ADMINISTRATION_HDSpaceLeft($iLoginAccountUnq);
	$iProcessImage		= 0;
	
	If ( ACCNT_ReturnRights("PHPJK_IG_BULK") ) {
		HeaderHTML();
		Main();
	}Else{
		DOMAIN_Message("Please login with rights to add images to galleries in bulk.", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iThumbComponent;
		Global $lngNumberUploaded;
		Global $bUseASPImage;
		Global $sAccountUnq;
		Global $iGalleryUnq;
		Global $sValidExtensions;
		Global $iMaxFileSize;
		Global $iHDSpaceLeft;
		Global $iProcessImage;
		Global $iLoginAccountUnq;
		Global $sGalleryPath;
		
		Global $sKeywords;
		Global $sAltTag;
		Global $sTitle;
		Global $sComments;
		Global $sURL;
		Global $sDescription;
		Global $sOnSite;
		Global $sName;
		Global $sCDescription;
		Global $sShortText;
		Global $sLongText;
		Global $bIsHidden;
		Global $sExtension;
		Global $iImageNum;
		
		$sError			= "";
		$sSuccess		= "";
		$sAction		= Trim(Request("sAction"));
		
		If ( $iGalleryUnq == "" )
		{
			If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				$sQuery = "SELECT GalleryUnq FROM Galleries (NOLOCK)";
			}Else{
				$sQuery = "SELECT GalleryUnq FROM Galleries (NOLOCK) WHERE AccountUnq = " . $iLoginAccountUnq;
			}
			DB_Query("SET ROWCOUNT 1");
			$rsRecordSet	= DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			If ( $rsRow = DB_Fetch($rsRecordSet) ){
				$iGalleryUnq = $rsRow["GalleryUnq"];
			}Else{
				$iGalleryUnq = "-1";
			}
		}

		If ( $sAction == "AddImages" ) {
			$sKeywords		= Trim(Request("sKeywords"));
			$sAltTag		= Trim(Request("sAltTag"));
			$sTitle			= Trim(Request("sTitle"));
			$sComments		= Trim(Request("sComments"));
			
			// links data
			$sURL			= Trim(Request("sURL"));
			$sDescription	= Trim(Request("sDescription"));
			$sOnSite		= Trim(Request("sOnSite"));
			
			// custom data
			$sName			= Trim(Request("sName"));
			$sCDescription	= Trim(Request("sCDescription"));
			$sShortText		= Trim(Request("sShortText"));
			$sLongText		= Trim(Request("sLongText"));
			$bIsHidden		= Trim(Request("bIsHidden"));
			
			Echo "<table cellpadding=0 cellspacing=0 border=0 width=671><tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr><tr><td><font color='" . $GLOBALS["PageText"] . "'>";
			Echo "<font size=+1><b>Results:</b></font><br><br>";
			Echo "<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor = " . $GLOBALS["BorderColor1"] . " align=center>";
			Echo "<table cellpadding=5 cellspacing=0 border=0 width=671><tr><td bgcolor=" . $GLOBALS["PageBGColor"] . ">";
			Echo "<a href='index.php' class='MediumNavPage'>Return</a></td></tr></table>";
			Echo "</td></tr></table><br>";
			$sQuery = "SELECT AccountUnq FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
			DB_Query("SET ROWCOUNT 1");
			$rsRecordSet	= DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			If ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$sAccountUnq = Trim($rsRow["AccountUnq"]);
				If (( $sAccountUnq == $iLoginAccountUnq ) || ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ))
				{
					// now because when we insert new images, we need to know the position #, get that for the current gallery (it might alreayd have images in it)
					If ( $GLOBALS["sUseDB"] == "MSSQL" ){
						$sQuery	= "SELECT ISNULL(MAX(G.Position), 0) + 1 FROM Images I (NOLOCK), ImagesInGallery G (NOLOCK) WHERE G.GalleryUnq = " . $iGalleryUnq . " AND I.ImageUnq = G.ImageUnq";
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) ){
							$iImageNum = $rsRow[0];
						}Else{
							$iImageNum = 1;
						}
					}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ){
						$sQuery	= "SELECT MAX(G.Position) FROM Images I (NOLOCK), ImagesInGallery G (NOLOCK) WHERE G.GalleryUnq = " . $iGalleryUnq . " AND I.ImageUnq = G.ImageUnq";
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) ){
							$iImageNum = $rsRow[0]+1;
						}Else{
							$iImageNum = 1;
						}
					}
					
					// Make sure the directories exist...make them if they don't
					$fldr	= $sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\Thumbnails\\";
					$fldr	= str_replace("\\", "/", $fldr);
					$fldr	= str_replace("//", "/", $fldr);
					If ( $GLOBALS["sOS"] == "UNIX" ) {
						mkdirs_nix($fldr);
					}Else{
						mkdirs_win($fldr);
					}
						
					// copy the files
					$sDir	= DOMAIN_Conf("PHP_JK_WEBROOT") . "/Admin/ManageEnMasse/Images";
					If ($handle = opendir($sDir))
					{
						while ( ($file = readdir($handle)) !== False )
						{
							if ($file != "." && $file != "..")
							{
								If ( $iProcessImage == 0 )
								{
									$sFileName	= $file;
									$iFileSize	= filesize($sDir . "/" . $file);
									$iPos		= strrpos($file, ".");
									$sExtension	= substr($file, $iPos+1 );	// some extensions aren't 3 characters (they can be 4 or more chars)
									If ( strpos($sValidExtensions, strtoupper($sExtension)) > 0 ) {
										$iProcessImage = ProcessImage($sFileName, $file, $iFileSize, $bUseASPImage);
									}Else{
										Echo "Image <b>" . $sFileName . "</b> was not copied because it was not an allowed file type (" . $sValidExtensions . ").<br>";
									}
								}Else{
									// either ran out of disk space, or maxed out on num uploadable (via system license or individual user limits)
									break;
								}
							}
						}
						closedir($handle);
					}
					
					// send any subscription emails
					If ( $lngNumberUploaded > 1 )
						G_ADMINISTRATION_SendSubscriptionEmail($iGalleryUnq);
					
					Echo "<br><b>" . ($lngNumberUploaded-1) . "</b> images entered and copied to the gallery.";
					Echo "<table cellpadding=0 cellspacing=0 border=0 width=671><tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr><tr><td><font color='" . $GLOBALS["PageText"] . "'>";
					Echo "<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor = " . $GLOBALS["BorderColor1"] . " align=center>";
					Echo "<table cellpadding=5 cellspacing=0 border=0 width=671><tr><td bgcolor=" . $GLOBALS["PageBGColor"] . ">";
					Echo "<a href='index.php' class='MediumNavPage'>Return</a></td></tr></table>";
					Echo "</td></tr></table><br>";
				}
			}Else{
				DOMAIN_Message("The gallery is not assigned to any user. The gallery must have an AccountUnq associated with it.", "ERROR");
			}
		}Else{
			If ( $iGalleryUnq != "-1" ) {
				WriteForm();
			}Else{
				DOMAIN_Message("Please create a gallery before adding images to it.", "ERROR");
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This processes the image.														*
	//*																					*
	//************************************************************************************
	Function ProcessImage($sFileName, $sFile, $iFileSize, $bUseASPImage)
	{
		Global $sGalleryPath;
		Global $iMaxFileSize;
		Global $bUseASPImage;
		Global $iLoginAccountUnq;
		Global $iHDSpaceLeft;
		Global $sAccountUnq;
		Global $iGalleryUnq;
		
		Global $sKeywords;
		Global $sAltTag;
		Global $sTitle;
		Global $sComments;
		Global $sURL;
		Global $sDescription;
		Global $sOnSite;
		Global $sName;
		Global $sCDescription;
		Global $sShortText;
		Global $sLongText;
		Global $bIsHidden;
		Global $sExtension;
		Global $intOrigXSize;
		Global $intOrigYSize;
		Global $iImageNum;
		Global $lngNumberUploaded;

		$sTempFileName	= FixFilename($sFile, "PRIMARY");
		$sOriginalName	= $sFile;
		$sThumbName		= $sOriginalName;

		$iPos			= strrpos($sOriginalName, ".");
		$sTemp			= substr($sOriginalName, $iPos+1 );	// some extensions aren't 3 characters (they can be 4 or more chars)
		$iPos			= strrpos($sTempFileName, ".");
		$sTemp2			= substr($sTempFileName, $iPos+1 );	// some extensions aren't 3 characters (they can be 4 or more chars)
		$sDir			= DOMAIN_Conf("PHP_JK_WEBROOT") . "/Admin/ManageEnMasse/";
		$sDir			= str_replace("\\", "/", $sDir);
		$sDir			= str_replace("//", "/", $sDir);

		If ( file_exists($sDir . "Images/" . $sTemp . ".jpg" ) ) {
			// the thumbnail was found - it's a jpg.
			$sThumbName = $sTemp . ".jpg";
		}ElseIf ( file_exists($sDir . "Images/" . $sTemp . ".gif" ) ) {
			$sThumbName = $sTemp . ".gif";
		}ElseIf ( file_exists($sDir . "Images/" . $sTemp . ".bmp" ) ) {
			$sThumbName = $sTemp . ".bmp";
		}

		If ( $sFileName != $sTempFileName )
		{
			/* also have to do the thumbnail if one exists since the original and thumb must be the same name
				must do the thumbnail first otherwise $sFile returns an error
			 all this iPos and sTemp crap is in case the primary file is not a .jpg, .gif, etc. if it's a .txt then the thumbnail is still going to be .jpg and won't be the same filename*/
			$iPos			= strrpos($sFile, ".");
			$sTemp2			= substr($sFile, $iPos+1 );	// some extensions aren't 3 characters (they can be 4 or more chars)
			$iPos			= strrpos($sTempFileName, ".");
			$sTemp2			= substr($sTempFileName, $iPos+1 );	// some extensions aren't 3 characters (they can be 4 or more chars)

			If ( file_exists($sDir . "Images/" . $sTemp . ".jpg" ) ) {
				// the thumbnail was found - it's a jpg. rename it to the new filename
				rename ($sDir . "Thumbnails/" . $sTemp . ".jpg", $sDir . "Thumbnails/" . $sTemp2 . ".jpg");
				$sThumbName = $sTemp2 . ".jpg";
			}ElseIf ( file_exists($sDir . "Images/" . $sTemp . ".gif" ) ) {
				rename ($sDir . "Thumbnails/" . $sTemp . ".gif", $sDir . "Thumbnails/" . $sTemp2 . ".gif");
				$sThumbName = $sTemp2 . ".gif";
			}ElseIf ( file_exists($sDir . "Images/" . $sTemp . ".bmp" ) ) {
				rename ($sDir . "Thumbnails/" . $sTemp . ".bmp", $sDir . "Thumbnails/" . $sTemp2 . ".bmp");
				$sThumbName = $sTemp2 . ".bmp";
			}
			/* this renames the file to the "fixed" file name. but only does it if the fixed file name is different than e original
			 since FixFileName looks in the galleries directory, and not the bulk upload directory, we must again make sure
				 the filename does not exist*/
			rename ($sDir . "Images/" . $sFile, $sDir . "Images/" . $sTempFileName);
		}
		$sFileName = $sTempFileName;

		If ( ( $iFileSize > $iMaxFileSize ) && ( $iMaxFileSize != -1 ) ) {
			// the file is too big - don't copy it.
			Echo "The file <b>" . $sFileName . "</b> is " . $iFileSize . " bytes. This exceeds the IMAGEGALLERY_MAXFILESIZE Configuration Variable setting of " . $iMaxFileSize . ". File skipped<br>";
		}Else{
			// check to make sure the user has enough hard disk left
			If ( ( ( $iHDSpaceLeft - $iFileSize ) > 0 ) || ( $iHDSpaceLeft = -1 ) )
			{
				// Check to make sure the file doesn't already exist
				If ( file_exists($sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/" . $sFileName) ) {
					// do not copy it
					Echo "The file <b>" . $sFileName . "</b> already exists in the gallery directory. No action taken for that file.<br>";
				}Else{
					// copy the file and thumbnail if they aren't using ASPImage, or create a new thumbnail if they are.
					If ( $bUseASPImage == "Y" )
					{
						/* the sTempFileName below used to be sThumbName until errors happened when files having the same name as
							existing files were being added*/
						Thumbnail($sTempFileName);
						$iPos = strpos($sTempFileName, ".");
						$sTempFileName = substr($sTempFileName, $iPos) . "jpg";
					}Else{
						If ( file_exists($sDir . "Thumbnails/" . $sThumbName ) ) {
							// the thumbnail does exist, copy it.
							copy ($sDir . "Thumbnails/" . $sThumbName, $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/Thumbnails" . "/" . $sThumbName);
							Echo "Thumbnail image copied.<BR>";
						}Else{
							// the thumbnail image does not exist. do nothing.
							$sThumbName = "";
							Echo "Thumbnail file does not exist.<BR>";
						}
					}
	
					$sTemp = $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/" . $sFileName;
					$sTemp = str_replace("\\", "/", $sTemp);
					$sTemp = str_replace("//", "/", $sTemp);
					$sTemp = str_replace("//", "/", $sTemp);
					copy ($sDir . "Images/" . $sFileName, $sTemp);
					If ( $sThumbName == "" ) {
						DB_Insert ("INSERT INTO Images (Comments,AltTag,Image,Thumbnail,Rating,NumRaters,NumViews,ImageSize,XSize,YSize,ImageNum,FileType,Image2,Image3,Image4,Image5,Image2Desc,Image3Desc,Image4Desc,Image5Desc,AltTag2,AltTag3,AltTag4,AltTag5,XSize2,YSize2,XSize3,YSize3,XSize4,YSize4,XSize5,YSize5,ImageSize2,ImageSize3,ImageSize4,ImageSize5,ConfUnq,ThreadUnq,Keywords,CookedComments,Title,ImageUL,ThumbUL,Alt2UL,Alt3UL,Alt4UL,Alt5UL,EZPrints,Aricaur,AricaurThumb) VALUES ('" . SQLEncode($sComments) . "', '" . SQLEncode($sAltTag) . "', '" . SQLEncode($sFileName) . "', '" . SQLEncode($sThumbName) . "',0,0,0,'" . Trim($iFileSize) . "','" . Trim($intOrigXSize) . "','" . Trim($intOrigYSize) . "'," . Trim($iImageNum) . ", '" . $sExtension . "', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0,0,'" . SQLEncode($sKeywords) . "','" . SQLEncode($sComments) . "', '" . SQLEncode($sTitle) . "', " . $iLoginAccountUnq . ",0,0,0,0,0,'','','')");
					}Else{
						DB_Insert ("INSERT INTO Images (Comments,AltTag,Image,Thumbnail,Rating,NumRaters,NumViews,ImageSize,XSize,YSize,ImageNum,FileType,Image2,Image3,Image4,Image5,Image2Desc,Image3Desc,Image4Desc,Image5Desc,AltTag2,AltTag3,AltTag4,AltTag5,XSize2,YSize2,XSize3,YSize3,XSize4,YSize4,XSize5,YSize5,ImageSize2,ImageSize3,ImageSize4,ImageSize5,ConfUnq,ThreadUnq,Keywords,CookedComments,Title,ImageUL,ThumbUL,Alt2UL,Alt3UL,Alt4UL,Alt5UL,EZPrints,Aricaur,AricaurThumb) VALUES ('" . SQLEncode($sComments) . "', '" . SQLEncode($sAltTag) . "', '" . SQLEncode($sFileName) . "', '" . SQLEncode($sThumbName) . "',0,0,0,'" . Trim($iFileSize) . "','" . Trim($intOrigXSize) . "','" . Trim($intOrigYSize) . "'," . Trim($iImageNum) . ", '" . $sExtension . "', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0,0,'" . SQLEncode($sKeywords) . "','" . SQLEncode($sComments) . "', '" . SQLEncode($sTitle) . "', " . $iLoginAccountUnq . "," . $iLoginAccountUnq . ",0,0,0,0,'','','')");
					}
					$rsRecordSet = DB_Query("SELECT @@IDENTITY");
					If ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						$iTempImageUnq = $rsRow[0];
						DB_Insert ("INSERT INTO ImagesInGallery VALUES (" . $iTempImageUnq . ", " . $iGalleryUnq . ", GetDate(),1, " . Trim($iImageNum) . ", " . $iGalleryUnq . ",1,0,0,0,0,0)");
						// enter any links
						If ( $sDescription <> "" )
							DB_Insert ("INSERT INTO IGMiscLinks (ImageUnq,URL,OnSite,Description) VALUES (" . $iTempImageUnq . ", '" . SQLEncode($sURL) . "', '" . SQLEncode($sOnSite) . "', '" . SQLEncode($sDescription) . "')");
						// enter any copyrights
						If ( isset($_POST["iCopyUnq"]) )
							ForEach ($_POST["iCopyUnq"] as $sCheckbox=>$sValue)
								DB_Insert ("INSERT INTO IGImageCRs VALUES (" . $iTempImageUnq . ", " . $sValue . ", '')");
						// enter any custom data
						If ( $sName <> "" )
						{
							If ( $sShortText == "" ) {
								$sDataType = "T";	// text > 250
							}Else{
								$sDataType = "V";	// varchar < 250
							}
							DB_Insert ("INSERT INTO IGMap (DataType,Name,Description,DomainUnq,ImageUnq,GalleryUnq,CategoryUnq) VALUES ('" . $sDataType . "', '" . SQLEncode($sName) . "', '" . SQLEncode($sDescription) . "', 1, " . $iTempImageUnq . ", 0, 0)");
							$rsRecordSet = DB_Query("SELECT @@IDENTITY");
							If ( $rsRow = DB_Fetch($rsRecordSet) )
								DB_Insert ("INSERT INTO IGData VALUES (" . $rsRow[0] . ", " . $iTempImageUnq . ", 0, 0, '" . SQLEncode($sShortText) . "', '" . SQLEncode($sLongText) . "', 0, '" . $bIsHidden . "')");
						}
						// process any Alternate View images - must send it the original file name
						ProcessAlts($sFileName, $bUseASPImage, $iTempImageUnq, $sOriginalName);
						Echo $lngNumberUploaded . ": Image <b>" . $sFileName . "</b> copied to the gallery.<br>";
						$lngNumberUploaded	= $lngNumberUploaded + 1;	// this is just a count of the number of images added
						$iImageNum			= $iImageNum + 1;	// this is the image position - it may be different if there were already images in the gallery
					}Else{
						DOMAIN_Message("There was an error adding this image.", "ERROR");
					}
				}
			}Else{
				Echo "Unable to process file: <b>" . $sFileName . "</b>. No remaining disk space.<br>";
				Return -1;
			}
		}
		Return 0;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This makes a thumbnail.															*
	//*																					*
	//************************************************************************************
	Function Thumbnail($FileName)
	{
		Global $ASPIMAGE;
		Global $GFL;
		Global $iThumbComponent;
		Global $intOrigXSize;
		Global $intOrigYSize;
		Global $sGalleryPath;
		Global $sAccountUnq;
		Global $iGalleryUnq;
		
		If ( Trim($FileName) != "" )
		{
			$intXSize		= intval(DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH"));
			$bThumbCreated	= FALSE;
			$sDir			= DOMAIN_Conf("PHP_JK_WEBROOT") . "/Admin/ManageEnMasse/";
			$sDir			= str_replace("\\", "/", $sDir);
			$sDir			= str_replace("//", "/", $sDir);

			If ( $iThumbComponent == $ASPIMAGE )
			{
				$objASPIMAGE = new COM("ASPIMAGE.Image");
				$objASPIMAGE->LoadImage($sDir . "Images\\" . $FileName);
				If ( ( ($objASPIMAGE->ImageFormat==1) || ($objASPIMAGE->ImageFormat==2) || ($objASPIMAGE->ImageFormat==3) ) && ($objASPIMAGE->MaxX>0) )
				{
					$intOrigXSize	= $objASPIMAGE->MaxX;
					$intOrigYSize	= $objASPIMAGE->MaxY;
					$intYSize = intval(($intXSize / $intOrigXSize) * $intOrigYSize);
					$objASPIMAGE->Resize($intXSize, $intYSize);
					$iPos = strrpos($FileName, ".");
					$FileName = substr($FileName, $iPos+1) . "jpg";
					$objASPIMAGE->FileName = ($sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\Thumbnails\\" . $FileName);
					$objASPIMAGE->SaveImage;
					Echo "Thumbnail created for <b>" . $FileName . "</b>.<br>";
					$bThumbCreated = TRUE;
				}
				unset($objASPIMAGE);
			}ElseIf ( $iThumbComponent == $GFL )
			{
				$objASPIMAGE = new COM("GflAx.GflAx");
				$objASPIMAGE->EnableLZW = TRUE;
				error_reporting(0);
				$objASPIMAGE->LoadBitmap($sDir . "Images/" . $FileName);
				//If ( Err.Number != 0 ) {
				//	$sError = "Unable to create thumbnail for file: <b>" . $sSourceFileName . "</b>. The file type is not supported by the GflAx component.<br>";
				//}Else{
					$intOrigXSize	= $objASPIMAGE->Width;
					$intOrigYSize	= $objASPIMAGE->Height;
					$intYSize = intval(($intXSize / $intOrigXSize) * $intOrigYSize);
					$iPos = strrpos($FileName, ".");
					$FileName = substr($FileName, 0, $iPos+1) . "jpg";
					$objASPIMAGE->LoadThumbnail($sDir . "Images\\" . $FileName, $intXSize, $intYSize);
					$objASPIMAGE->SaveFormat = 1;
					$objASPIMAGE->SaveBitmap($sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\Thumbnails\\" . $FileName);
					$bThumbCreated = TRUE;
				//}
				error_reporting(E_ALL ^ E_NOTICE);
				unset($objASPIMAGE);
			}Else{
				// use native PHP
			    $image_info = getImageSize($sDir . "Images/" . $FileName) ;
			    
			    switch ($image_info['mime']) {
			        case 'image/gif':
			            if (imagetypes() & IMG_GIF)  {
			                $imgSource = imageCreateFromGIF($sDir . "Images/" . $FileName) ;
			            } else {
			                $sError = 'GIF images are not supported<br />';
			            }
			            break;
			        case 'image/jpeg':
			            if (imagetypes() & IMG_JPG)  {
			                $imgSource = imageCreateFromJPEG($sDir . "Images/" . $FileName) ;
			            } else {
			                $sError = 'JPEG images are not supported<br />';
			            }
			            break;
			        case 'image/png':
			            if (imagetypes() & IMG_PNG)  {
			                $imgSource = imageCreateFromPNG($sDir . "Images/" . $FileName) ;
			            } else {
			                $sError = 'PNG images are not supported<br />';
			            }
			            break;
			        case 'image/wbmp':
			            if (imagetypes() & IMG_WBMP)  {
			                $imgSource = imageCreateFromWBMP($sDir . "Images/" . $FileName) ;
			            } else {
			                $sError = 'WBMP images are not supported<br />';
			            }
			            break;
			        default:
			            $sError = $image_info['mime'].' images are not supported<br />';
			            break;
			    }

			    If ( $sError == "" ) {
			        $intOrigXSize = imagesx($imgSource);
			        $intOrigYSize = imagesy($imgSource);

			        // thumbnail width = target * original width / original height
			        $intYSize = intval(($intXSize / $intOrigXSize) * $intOrigYSize);
			        $imgThumb = imageCreateTrueColor($intXSize,$intYSize);
			        
			        imageCopyResampled($imgThumb, $imgSource, 0, 0, 0, 0, $intXSize, $intYSize, $intOrigXSize, $intOrigYSize);
			        imageJPEG($imgThumb, $sDir . "Thumbnails/" . $FileName);
			        
			        imageDestroy($imgSource);
			        imageDestroy($imgThumb);
			        
			       // $bThumbCreated = TRUE;
			    }
			}

			If ( ! $bThumbCreated )
			{
				If ( $iThumbComponent == $ASPIMAGE ) {
					Echo "Thumbnail not created for <b>" . $FileName . "</b> because it is not a .jpg, .bmp, or .png image.<BR>";
				}ElseIf ( $iThumbComponent == $GFL ) {
					Echo "Thumbnail not created for <b>" . $FileName . "</b> because it is not an image supported by GflAx.<BR>";
				}Else{
					//Echo "Thumbnail not created for <b>" . $FileName . "</b> because it is not an image supported by PHP.<BR>";
				}
				
				// check to see if there's a thumbnail in the Thumbnails directory
				If ( file_exists($sDir . "Thumbnails/" . $FileName ) ) {
					// the thumbnail does exist, copy it.
					$sTempDir	= $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/Thumbnails/" . $FileName;
					$sTempDir	= str_replace("\\", "/", $sTempDir);
					$sTempDir	= str_replace("//", "/", $sTempDir);
					$sTempDir	= str_replace("//", "/", $sTempDir);
					//copy ($sDir . "Thumbnails/" . $FileName, $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/Thumbnails" . "/" . $FileName);
					copy ($sDir . "Thumbnails/" . $FileName, $sTempDir);
					Echo "Thumbnail image copied from the Thumbnails directory.<BR>";
				}Else{
					// the thumbnail image does not exist. do nothing.
					Echo "Thumbnail file does not exist in the Thumbnails directory.<BR>";
				}
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Checks for and copies Alt images to the gallery.								*
	//*																					*
	//************************************************************************************
	Function ProcessAlts($sFileName, $bUseASPImage, $iImageUnq, $sOriginalName)
	{
		Global $sGalleryPath;
		Global $iThumbComponent;
		Global $ASPIMAGE;
		Global $GFL;
		Global $iXSize;
		Global $iYSize;
		Global $iHDSpaceLeft;
		Global $iMaxFileSize;
		Global $iLoginAccountUnq;
		Global $sAccountUnq;
		Global $iGalleryUnq;
		
		$sDir	= DOMAIN_Conf("PHP_JK_WEBROOT") . "/Admin/ManageEnMasse/";
		$sDir	= str_replace("\\", "/", $sDir);
		$sDir	= str_replace("//", "/", $sDir);
		$iXSize	= 0;
		$iYSize	= 0;
		
		For ( $x = 1; $x <= 4; $x++)
		{
			$sTempName		= $sFileName;		//FixFilename(sFileName, "PRIMARY")	' get a unique name for the alt -- not the primary images name either
			$sFilePath		= ($sDir . "Alt" . $x) . "/" . $sTempName;
			$iPos			= strrpos($sOriginalName, ".");
			$sFileNameNoExt	= strtoupper(substr($sOriginalName, $iPos - 1));

			If ( file_exists($sFilePath) )
			{	
				$iFileSize = filesize($sFilePath);
				If ( $bUseASPImage == "Y" )
				{
					If ( $iThumbComponent == $ASPIMAGE )
					{
						$objASPIMAGE = new COM("ASPIMAGE.Image");
						$objASPIMAGE->LoadImage($sFilePath);
						If ( ( ($objASPIMAGE->ImageFormat==1) || ($objASPIMAGE->ImageFormat==2) || ($objASPIMAGE->ImageFormat==3) ) && ($objASPIMAGE->MaxX>0) )
						{
							$iXSize	= $objASPIMAGE->MaxX;	// get this for the database
							$iYSize	= $objASPIMAGE->MaxY;
						}
						unset($objASPIMAGE);
					}ElseIf ( $iThumbComponent == $GFL )
					{
						// in case we can't open the image, set the sizes to 0
						$iXSize	= 0;
						$iYSize	= 0;
						$objASPIMAGE = new COM("GflAx.GflAx");
						$objASPIMAGE->EnableLZW = TRUE;
						error_reporting(0);
						$objASPIMAGE->LoadBitmap($sFilePath);
						//If ( Err.Number != 0 ) {
						//	$sError = "Unable to create thumbnail for file: <b>" . $sSourceFileName . "</b>. The file type is not supported by the GflAx component.<br>";
						//}Else{
							$iXSize	= $objASPIMAGE->Width;
							$iYSize	= $objASPIMAGE->Height;
						//}
						error_reporting(E_ALL ^ E_NOTICE);
						unset($objASPIMAGE);
					}Else{
						// use native PHP
					    $image_info = getImageSize($sFilePath) ;
					    
					    switch ($image_info['mime']) {
					        case 'image/gif':
					            if (imagetypes() & IMG_GIF)  {
					                $imgSource = imageCreateFromGIF($sFilePath) ;
					            } else {
					                $sError = 'GIF images are not supported<br />';
					            }
					            break;
					        case 'image/jpeg':
					            if (imagetypes() & IMG_JPG)  {
					                $imgSource = imageCreateFromJPEG($sFilePath) ;
					            } else {
					                $sError = 'JPEG images are not supported<br />';
					            }
					            break;
					        case 'image/png':
					            if (imagetypes() & IMG_PNG)  {
					                $imgSource = imageCreateFromPNG($sFilePath) ;
					            } else {
					                $sError = 'PNG images are not supported<br />';
					            }
					            break;
					        case 'image/wbmp':
					            if (imagetypes() & IMG_WBMP)  {
					                $imgSource = imageCreateFromWBMP($sFilePath) ;
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
				}
				$bExists = TRUE;
			}Else{
				$bExists = FALSE;
				// now loop through all the files in the alt directory looking for the first file that has the 
				//	same filename w/ a different extension
				If ($handle = opendir($sDir . "Alt" . $x))
				{
					while (false !== ($file = readdir($handle)))
					{
						if ($file != "." && $file != "..")
						{
							If ( strpos(strtoupper($file), $sFileNameNoExt) > 0 )
							{
								$sTempFilePath	= $sDir . "Alt" . $x . "/" . $file;
								$iFileSize		= filesize($sTempFilePath);
								$sTempName		= $file;
								If ( $bUseASPImage == "Y" )
								{
									If ( $iThumbComponent == $ASPIMAGE )
									{
										$objASPIMAGE = new COM("ASPIMAGE.Image");
										$objASPIMAGE->LoadImage($sTempFilePath);
										If ( ( ($objASPIMAGE->ImageFormat==1) || ($objASPIMAGE->ImageFormat==2) || ($objASPIMAGE->ImageFormat==3) ) && ($objASPIMAGE->MaxX>0) )
										{
											$iXSize	= $objASPIMAGE->MaxX;	// get this for the database
											$iYSize	= $objASPIMAGE->MaxY;
										}
										unset($objASPIMAGE);
									}ElseIf ( $iThumbComponent == $GFL )
									{
										// in case we can't open the image, set the sizes to 0
										$iXSize	= 0;
										$iYSize	= 0;
										$objASPIMAGE = new COM("GflAx.GflAx");
										$objASPIMAGE->EnableLZW = TRUE;
										error_reporting(0);
										$objASPIMAGE->LoadBitmap($sTempFilePath);
										//If ( Err.Number != 0 ) {
										//	$sError = "Unable to create thumbnail for file: <b>" . $sSourceFileName . "</b>. The file type is not supported by the GflAx component.<br>";
										//}Else{
											$iXSize	= $objASPIMAGE->Width;
											$iYSize	= $objASPIMAGE->Height;
										//}
										error_reporting(E_ALL ^ E_NOTICE);
										unset($objASPIMAGE);
									}
								}
								$bExists	= TRUE;
								break;
							}
						}
					}
					closedir($handle);
				}
			}
		
			If ( $bExists )
			{
				If ( ( $iFileSize > $iMaxFileSize) && ( $iMaxFileSize != -1 ) )
				{
					// the file is too big - don't copy it.
					Echo "The Alternate View image <b>" . $sOriginalName . "</b> is " . $iFileSize . " bytes. This exceeds the IMAGEGALLERY_MAXFILESIZE Configuration Variable setting of " . $iMaxFileSize . ". File skipped<br>";
				}Else{
					// check to make sure the user has enough hard disk left
					If ( ( $iHDSpaceLeft - $iFileSize > 0 ) || ( $iHDSpaceLeft == -1 ) )
					{
						copy ($sFilePath, $sGalleryPath . "/" . $sAccountUnq . "/" . $iGalleryUnq . "/Alt_" . $x . $sOriginalName);
						If ( $bUseASPImage == "Y" ) {
							DB_Update ("UPDATE Images SET Image" . ($x+1) . " = 'Alt_" . $x . $sOriginalName . "', XSize" . ($x+1) . " = " . $iXSize . ", YSize" . ($x+1) . " = " . $iYSize . ", ImageSize" . ($x+1) . " = " . $iFileSize . ", Alt" . ($x+1) . "UL = " . $iLoginAccountUnq . " WHERE ImageUnq = " . $iImageUnq);
						}Else{
							DB_Update ("UPDATE Images SET Image" . ($x+1) . " = 'Alt_" . $x . $sOriginalName . "', ImageSize" . ($x+1) . " = " . $iFileSize . ", Alt" . ($x+1) . "UL = " . $iLoginAccountUnq . " WHERE ImageUnq = " . $iImageUnq);
						}
						G_ADMINISTRATION_IncrementHDSpaceUsed($iLoginAccountUnq, $iFileSize);
						Echo "Added Alternate View image number " . $x . "<BR>";
					}Else{
						Echo "The Alternate View image " . $sOriginalName . " was not processed because no more of your disk space allotment remains. No more files will be processed.<br>";
					}
				}
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Fixes the Alt file's name.														*
	//*																					*
	//************************************************************************************
	Function FixAltName($sFileName, $sAltDir, $sAltNum)
	{		
		$sFilePath	= $sAltDir . "/" . $sFileName;
		$sFilePath2	= $sAltDir . "/" . "Alt_" . $sAltNum . $sFileName;
		
		If ( file_exists($sFilePath) ) {
			rename ($sFilePath, $sFilePath2);
			Return "Alt_" . $sAltNum . $sFileName;
		}Else{
			Return "";	// there wasn't an alt image
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
		Global $aVariables;
		Global $aValues;
		Global $GFL;
		Global $ASPIMAGE;
		Global $iThumbComponent;
		Global $sValidExtensions;
		Global $iHDSpaceLeft;
		Global $iMaxFileSize;
		Global $iloginAccountUnq;
		Global $iGalleryUnq;
		
		$sBGColor	= $GLOBALS["BGColor1"];
		$sTextColor	= $GLOBALS["TextColor1"];
		?>
		<form name='ManageEnMasse' action='index.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aValues[0] = "AddImages";
		Echo DOMAIN_Link("P");
		
		DOMAIN_Link_Clear();
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Add Images in Bulk</b></font>
					<br>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<i>You must have server filesystem access to use this script.</i>
					<br>
					<ol>
						<li>Upload your files to these folders:<br>
							--Upload thumbnails (optional) to: <?=str_replace("//", "/", DOMAIN_Conf("IG") . "/Admin/ManageEnMasse/Thumbnails/")?><br>
							--Upload primary images to: <?=str_replace("//", "/", DOMAIN_Conf("IG") . "/Admin/ManageEnMasse/Images/")?><br>
							--Upload Alternate View image 1 (optional) to: <?=str_replace("//", "/", DOMAIN_Conf("IG") . "/Admin/ManageEnMasse/Alt1/")?><br>
							--Upload Alternate View image 2 (optional) to: <?=str_replace("//", "/", DOMAIN_Conf("IG") . "/Admin/ManageEnMasse/Alt2/")?><br>
							--Upload Alternate View image 3 (optional) to: <?=str_replace("//", "/", DOMAIN_Conf("IG") . "/Admin/ManageEnMasse/Alt3/")?><br>
							--Upload Alternate View image 4 (optional) to: <?=str_replace("//", "/", DOMAIN_Conf("IG") . "/Admin/ManageEnMasse/Alt4/")?><br>
						<?php 
						If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) )
						{
							If ( $iThumbComponent == $ASPIMAGE ) {
								Echo "<ul><li>If you want ASPImage to attempt to create thumbnails for your .jpg images, leave the \"Thumbnails\" directory empty and simply choose \"Yes\" to the ASPImage question below.<li>If you want to provide your own thumbnail files, place them in the \"Thumbnails\" directory (<u>ensuring their filenames are identical to their respective full-size images</u>) and choose \"No\" to the ASPImage question below.</ul>";
							}ElseIf ( $iThumbComponent == $GFL ) {
								Echo "<ul><li>If you want GflAx to attempt to create thumbnails for your images, leave the \"Thumbnails\" directory empty and simply choose \"Yes\" to the GflAx question below.<li>If you want to provide your own thumbnail files, place them in the \"Thumbnails\" directory (<u>ensuring their filenames are identical to their respective full-size images</u>) and choose \"No\" to the GflAx question below.</ul>";
							}
						}Else{
							//Echo "<li>If you want to provide your own thumbnail files, place them in the \"Thumbnails\" directory (<u>ensuring their filenames are identical to their respective full-size images</u>).";
							Echo "<ul><li>If you want PHP to attempt to create thumbnails for your images, leave the \"Thumbnails\" directory empty and simply choose \"Yes\" to the PHP question below.<li>If you want to provide your own thumbnail files, place them in the \"Thumbnails\" directory (<u>ensuring their filenames are identical to their respective full-size images</u>) and choose \"No\" to the PHP question below.</ul>";
						}
						?>
						<li>If you want to include Alternate View Images, place them in the Alt# directory. <u>Their filenames should be
							identical to their respective primary images (as with the thumbnails).</u>
						<li>Select the Gallery you wish to add your images to from the dropdown box below.<li>Click the "Add Images" button 
							when you are ready to import your files into the selected Gallery.
						<li>After the operation is completed, you should remove the files you uploaded in steps 
							2, 3 and 4&#151;these files will be copied to their appropriate Gallery subfolder, and will no longer 
							be needed.
					</ol>
					<font size=-2><strong>NOTES</strong><br>Files of the same name that already exist in the gallery will 
					<b>not</b> be overwritten. These files will be noted in the report printed out during image processing.
					<br><br>
					Only <b><?=$sValidExtensions?></b> images/files will be processed.
					<br>
					<?php If ( $iHDSpaceLeft != -1 ) {?>
					Files will be processed until your disk space allotment (<b><?=$iHDSpaceLeft?></b> bytes left) is reached.
					<br>
					<?php }?>
					<?php If ( $iMaxFileSize != -1 ) {?>
					Only files smaller than <b><?=$iMaxFileSize?></b> bytes will be processed.
					<br>
					<?php }?>
					<?php If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) ) {?>
					<?php 
					If ( $iThumbComponent == $ASPIMAGE ) {
						Echo "Thumbnails will only be generated for JPG, BMP and PNG images.";
					}ElseIf ( $iThumbComponent == $GFL ) {
						Echo "Thumbnails will only be generated for images supported by GflAx.";
					}Else
					{
						Echo "Thumbnails will only be generated for images supported by PHP.";
					}
					?>
					<br>
					<?php }?>
					<br><br>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor = <?=$GLOBALS["BorderColor1"]?> align=center>
					<table cellpadding=5 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?> colspan=2>
								<font color=<?=$sTextColor?>><b>
								Gallery to add the images to: 
								<?php 
								Echo "<select name='iGalleryUnq'>";
									If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
										$sQuery = "SELECT GalleryUnq, Name FROM Galleries (NOLOCK) ORDER BY Name";
									}Else{
										$sQuery = "SELECT GalleryUnq, Name FROM Galleries (NOLOCK) WHERE AccountUnq = " . $iloginAccountUnq . " ORDER BY Name";
									}
									$rsRecordSet	= DB_Query($sQuery);
									If ( DB_NumRows($rsRecordSet) > 0 )
									{
										While ( $rsRow = DB_Fetch($rsRecordSet) )
										{
											$iTempGalleryUnq = $rsRow["GalleryUnq"];
											If ( Trim($iGalleryUnq) == Trim($iTempGalleryUnq) ) {
												Echo "<option value='" . $iTempGalleryUnq . "' Selected>" . $rsRow["Name"] . "</option>";
											}Else{
												Echo "<option value='" . $iTempGalleryUnq . "'>" . $rsRow["Name"] . "</option>";
											}
										}
									}Else{
										Echo "<option value=''>No Galleries</option>";
									}
								Echo "</select>";
								?>
							</td>
						</tr>
						<tr>
							<td bgcolor=<?=$GLOBALS["PageBGColor"]?>>
								<b>
								<?php 
								//If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) ) {
									If ( $iThumbComponent == $ASPIMAGE ) {
										Echo "Would you like to use ASPImage to create thumbnails (this only works with .jpg, .bmp, or .png images)?";
									}ElseIf ( $iThumbComponent == $GFL ) {
										Echo "Would you like to use GflAx to create thumbnails (this only works with images supported by GflAx)?";
										Echo "</b><font size=-2><BR>Supported file types include: &nbsp;";
										Echo GFL_Supported($sValidExtensions);
									}Else{
										Echo "Would you like to use PHP to create thumbnails (this only works with .jpg, .bmp, or .png images)?";
									}
									?>
									<br>
									Yes: <input type='radio' name='bUseASPImage' value='Y' <?php If ( ( $bUseASPImage == "Y" ) || ( $bUseASPImage == "" ) ) { Echo "checked"; }?>>
									No: <input type='radio' name='bUseASPImage' value='N'>
									</b>
									<font size=-2>(if you select "No", then, if images exist, the images in the thumbnail directory will be used)</font>
									<?php 
								//}
								?>
							</td>
						</tr>
<!--
						<tr>
							<td bgcolor = <?=$sBGColor?> colspan=2>
								<font color=<?=$sTextColor?>><b>Would you like to create Alternate View Images?
								<table>
									<tr>
										<td>
											From which directory?
										</td>
										<td>
											<select name=''>
												<option value='Images'>Images</option>
												<option value='Alt1'>Alt1</option>
												<option value='Alt2'>Alt2</option>
												<option value='Alt3'>Alt3</option>
												<option value='Alt4'>Alt4</option>
											</select>
										</td>
									</tr>
								</table>
								<table>
									<tr>
										<td nowrap>
											What size(s)?
										</td>
										<td width=100%>
											<table width=100%>
												<tr>
													<td width=20%></td>
													<td align=center width=20%>
														Alt. 1
													</td>
													<td align=center width=20%>
														Alt. 2
													</td>
													<td align=center width=20%>
														Alt. 3
													</td>
													<td align=center width=20%>
														Alt. 4
													</td>
												<tr>
													<td align=right>
														<font size=-2>Percent
													</td>
													<td align=center>
														<input type='text' name='' value='' size=3 maxlength=4>%
													</td>
													<td align=center>
														<input type='text' name='' value='' size=3 maxlength=4>%
													</td>
													<td align=center>
														<input type='text' name='' value='' size=3 maxlength=4>%
													</td>
													<td align=center>
														<input type='text' name='' value='' size=3 maxlength=4>%
													</td>
												</tr>
												<tr>
													<td align=right>
														<font size=-2>Pixels Wide
													</td>
													<td align=center>
														<input type='text' name='' value='' size=3 maxlength=4><font color=<?=$sBGColor?>>%
													</td>
													<td align=center>
														<input type='text' name='' value='' size=3 maxlength=4><font color=<?=$sBGColor?>>%
													</td>
													<td align=center>
														<input type='text' name='' value='' size=3 maxlength=4><font color=<?=$sBGColor?>>%
													</td>
													<td align=center>
														<input type='text' name='' value='' size=3 maxlength=4><font color=<?=$sBGColor?>>%
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
stopping now because not sure how wayne/vance wants it to work.
							</td>
						</tr>
-->
						<tr>
							<td bgcolor=<?=$GLOBALS["PageBGColor"]?> colspan=2>
								<!-- <font color=<?=$sTextColor?>><b>Would you like to watermark ALL images? -->&nbsp;
							</td>
						</tr>
						
						<tr>
							<td colspan=2 bgcolor = <?=$sBGColor?>>
								<font color=<?=$sTextColor?>>
								<b>Would you like to add a link to ALL images?
								<br>
								<table width=100%>
									<tr>
										<td><font color=<?=$sTextColor?>>URL: </td>
										<td><input type='text' name='sURL' value=''></td>
										<td><font color=<?=$sTextColor?>>Description: </td>
										<td><input type='text' name='sDescription' value=''></td>
										<td>
											<table>
												<tr>
													<td><font color=<?=$sTextColor?>>Onsite</td>
													<td><input type='radio' name='sOnSite' value='Y' checked></td>
													<td><font color=<?=$sTextColor?>>Offsite</td>
													<td><input type='radio' name='sOnSite' value='N'></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
						<tr>
							<td bgcolor=<?=$GLOBALS["PageBGColor"]?> colspan=2>
								<b>Would you like to add copyright(s) to ALL images?
								<br>
								<table width=100%>
									<tr>
										<td>
											<select name='iCopyUnq[]' multiple size=6>
												<?php 
												$sQuery			= "SELECT CopyUnq, Copyright FROM IGCopyrights (NOLOCK) ORDER BY Copyright";
												$rsRecordSet	= DB_Query($sQuery);
												While ( $rsRow = DB_Fetch($rsRecordSet) )
													Echo "<option value='" . $rsRow["CopyUnq"] . "'>" . htmlentities($rsRow["Copyright"]) . "</option>";
												?>
											</select>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
						<?php If ( ACCNT_ReturnRights("PHPJK_IG_ADD_CF_IMAGE") ) {?>
						<tr>
							<td bgcolor = <?=$sBGColor?> colspan=2>
								<font color=<?=$sTextColor?>>
								<b>Would you like to add a custom data entry to ALL images?
								<br>
								<table cellpadding = 5 cellspacing=0 border=0 width=671>
									<tr>
										<td><font color=<?=$sTextColor?>>Name (shown on the image display page):</td>
										<td><input type='text' name='sName' value='' maxlength=250></td>
										<td><font color=<?=$sTextColor?>>Description (for your use only):</td>
										<td><input type='text' name='sCDescription' value='' maxlength=250></td>
									</tr>
									<tr>
										<td colspan=4>
											<table width=100%>
												<tr>
													<td colspan=2>
														<font color=<?=$sTextColor?>>Enter a short text value, or long text value, but not both.
													</td>
												</tr>
												<tr>
													<td valign=top><font color=<?=$sTextColor?>>Short Text (250 char or less):<br><input type='text' name='sShortText' value='' size=50 maxlength=250></td>
													<td valign=top><font color=<?=$sTextColor?>>Long Text:<br><textarea cols=30 rows=3 WRAP="soft" NAME="sLongText"></textarea></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan=4 bgcolor = <?=$sBGColor?>>
											<font color=<?=$sTextColor?>>
											Hide this data from the public? If you hide this, it will not appear on the image display page and only administrators and gallery owners will be able to see it here.
											<table>
												<tr>
													<td><font color=<?=$sTextColor?>>Yes</td>
													<td><input type='radio' name='bIsHidden' value='Y'></tD>
													<td><font color=<?=$sTextColor?>>No</td>
													<td><input type='radio' name='bIsHidden' value='N' checked></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<?php }?>
						
						<tr>
							<td bgcolor=<?=$GLOBALS["PageBGColor"]?> colspan=2>
								<b>Would you like to add a long description to ALL images?
								<br>
								<table width=100%>
									<tr>
										<td>
											<textarea cols=68 rows=4 WRAP="soft" NAME="sComments"></textarea>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
						<tr>
							<td bgcolor=<?=$sBGColor?> colspan=2><font color=<?=$sTextColor?>>
								<b>Would you like to add a title to ALL images?
								<br>
								<table width=100%>
									<tr>
										<td>
											<input type='text' name='sTitle' size=70 maxlength=250>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
						<tr>
							<td bgcolor=<?=$GLOBALS["PageBGColor"]?> colspan=2>
								<b>Would you like to add a alt tag to ALL images?
								<br>
								<table width=100%>
									<tr>
										<td>
											<input type='text' name='sAltTag' size=70 maxlength=250>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
						<tr>
							<td bgcolor=<?=$sBGColor?> colspan=2><font color=<?=$sTextColor?>>
								<b>Would you like to add keywords to ALL images?
								<br>
								<table width=100%>
									<tr>
										<td>
											<input type='text' name='sKeywords' size=70 maxlength=250>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
						<tr>
							<td bgcolor=<?=$sBGColor?> colspan=2 align=center><font color=<?=$sTextColor?>>
								<input type='submit' value='Add Images'>
								<br>
								<font size=-2>Depending on how many images you have, this may take some time.</font>
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
	//********************************************************************************
	
	
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
				<td colspan=2 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=FFFFFF width=100%>&nbsp;</td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=2 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=4 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
?>
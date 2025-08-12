<?php
	Require("../Includes/i_Includes.php");
	
	DB_OpenDomains();
	DB_OpenImageGallery();
	INIT_LoginDetect();
	
	Global $sSiteURL;
	
	$sPasscode	= trim(Request("sPasscode"));
	
	If ( trim(Request("sAction")) == "Test" )
	{
		$iWebmasterID = trim(Request("iWebmasterID"));
		If ( $sPasscode == trim(DOMAIN_Conf("ARICAUR_WEBMASTER_PASSCODE")) )
		{
			Echo "Passcode entered correctly!<br>";
		}Else{
			Echo "Invalid or missing Passcode! Please populate the ARICAUR_WEBMASTER_PASSCODE Configuration Variable.<br>";
		}
		If ( $iWebmasterID == trim(DOMAIN_Conf("ARICAUR_WEBMASTER_ID")) )
		{
			Echo "Webmaster ID entered correctly!<br>";
		}Else{
			Echo "Invalid or missing Webmaster ID! Please populate the ARICAUR_WEBMASTER_ID Configuration Variable.<br>";
		}
		Echo "The AricaurText.php URL you have entered is correct.<br>";
		Echo "<br>AricaurText.php URL test successfull!";
	}Else{
		$iImageUnq	= trim(Request("iImageUnq"));
		If ( $iImageUnq != "" )
		{
			If ( $sPasscode == trim(DOMAIN_Conf("ARICAUR_WEBMASTER_PASSCODE")) )
			{
				// now get the thumbnail and Aricaur primary image used w/ this imageunq
				//	make sure both exist
				//	return the Aricaur primary images' x and y, the full thumb url, and full Aricaur primary image url
				//	return error if either don't exist
				$sQuery = "SELECT I.Thumbnail, I.Image2, I.Image3, I.Image4, I.Image5, I.XSize2, I.YSize2, I.XSize3, I.YSize3, I.XSize4, I.YSize4, I.XSize5, I.YSize5, I.ImageSize2, I.ImageSize3, I.ImageSize4, I.ImageSize5, I.ThumbUL, I.Alt2UL, I.Alt3UL, I.Alt4UL, I.Alt5UL, I.Aricaur, I.AricaurThumb, IG.PrimaryG FROM Images I, ImagesInGallery IG WHERE I.ImageUnq = " . $iImageUnq . " AND IG.GalleryUnq = IG.PrimaryG AND IG.ImageUnq = I.ImageUnq";
				$rsRecordSet = DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					If ( trim($rsRow["Aricaur"]) != "" )
					{
						$iAriThmUL		= "";
						$iAriImage		= "";
						
						$sAricaur		= str_replace(" ", "", $rsRow["Aricaur"]);		// sometimes this returns w/ spaces in it
						$sAricaurThumb	= str_replace(" ", "", $rsRow["AricaurThumb"]);
						If ( $sAricaurThumb != "" )
						{
							$iAriThmUL		= $rsRow["Alt" . $sAricaurThumb . "UL"];	// this is the person who uploaded the Aricaur thumb (probably same as iAltUL, but just in case...)
							$iAriImage		= $rsRow["Image" . $sAricaurThumb];
						}
						$sThumbnail		= $rsRow["Thumbnail"];
						$iThumbUL		= $rsRow["ThumbUL"];					// accountunq of person who uploaded the thumbnail
						$iAltUL			= $rsRow["Alt" . $sAricaur . "UL"];		// accountunq of person who uploaded the alt image we are going to use
						$iPrimaryG		= $rsRow["PrimaryG"];
						$iAltImage		= $rsRow["Image" . $sAricaur];
						
						$iToUse_UL		= "";
						$iToUse_File	= "";
						$iToUse_Dir		= "";
						If ( $sAricaurThumb == "" )
						{
							// use the gallery thumbnail
							$iToUse_UL		= $iThumbUL;
							$iToUse_File	= $sThumbnail;
							$iToUse_Dir		= "Thumbnails";
						}Else{
							// use the Aricaur thumbnail
							$iToUse_UL		= $iAriThmUL;
							$iToUse_File	= $iAriImage;
							$iToUse_Dir		= "Aricaur";
						}
						
						$sTempName	= $iToUse_File;
						$sTempName	= str_replace(".tif", ".jpg", $sTempName);
						$sTempName	= str_replace(".tga", ".jpg", $sTempName);
						$sTempName	= str_replace(".png", ".jpg", $sTempName);
						$sTempName	= str_replace(".psd", ".jpg", $sTempName);
						$sFilePath		= $sGalleryPath . "/" . $iToUse_UL . "/" . $iPrimaryG . "/" . $iToUse_Dir . "/" . $sTempName;
						$sFilePath		= str_replace("\\", "/", $sFilePath);
						$sFilePath		= str_replace("//", "/", $sFilePath);
						$sFilePath		= str_replace("//", "/", $sFilePath);
						If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
						{
							$sFilePath	= $sGalleryPath . "/" . $iAltUL . "/" . $iPrimaryG . "/" . $iAltImage;
							$sFilePath	= str_replace("\\", "/", $sFilePath);
							$sFilePath	= str_replace("//", "/", $sFilePath);
							If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
							{
								echo "http://" . $_SERVER["SERVER_NAME"] . DOMAIN_Conf("IG") . "/" . $iToUse_UL . "/" . $iPrimaryG . "/" . $iToUse_Dir . "/" . $sTempName . "\n";
								echo "http://" . $_SERVER["SERVER_NAME"] . DOMAIN_Conf("IG") . "/" . $iAltUL . "/" . $iPrimaryG . "/" . $iAltImage . "\n";
								$iXSize = $rsRow["XSize" . $sAricaur];
								If ( ( $iXSize == "" ) || ( $iXSize == "0" ) )
								{
									// try and get the X, Y dimensions again
									$sAccountUnq	= $iAltUL;
									$iGalleryUnq	= $iPrimaryG;
									$iXSize			= 0;
									$iYSize			= 0;
									error_reporting(0);		// need to turn off error reporting because there is no clean error exiting from this function
									G_UPLOAD_GetDimensions($iAltImage);
									error_reporting(E_ALL ^ E_NOTICE);
									echo $iXSize . "\n";
									echo $iYSize . "\n";
								}Else{
									echo $iXSize . "\n";
									echo $rsRow["YSize" . $sAricaur] . "\n";
								}
								echo $rsRow["ImageSize" . $sAricaur] . "\n";
							}Else{
								echo "Error 4";		// the alt image being used as the Aricaur Primary image does not exist
							}
						}Else{
							echo "Error 3";		// thumbnail does not exist
						}
					}Else{
						echo "Error 2";		// the Aricaur link is not turned on for this image
					}
				}Else{
					echo "Error 13";	// image is missing from the system
				}
			}Else{
				Echo "Error 1";		// invalid or missing passcode
			}
		}Else{
			echo "Error 0";		// missing ImageUnq
		}
	}
?>
<?php
	Require("Includes/i_Includes.php");
	
	Global $bHasAccount;
	Global $bGotAllData;
	Global $sMetaDescription;
	Global $sKeywords;
	Global $sHeadTitle;
	Global $iLoginAccountUnq;
	Global $iNumViews;
	Global $iImageUnq;
	Global $iGalleryUnq;
	Global $sTitle;
	Global $sImage;
	Global $sError;
	
	
	$bGotAllData		= FALSE;
	$sMetaDescription	= "";
	$sKeywords			= "";
	$sHeadTitle			= "";
	
	DB_OpenDomains();
	DB_OpenImageGallery();
	INIT_LoginDetect();

	Main();
	
	If ( $bGotAllData )
		$sMetaDescription = str_replace(CHR(13), "", str_replace(CHR(10), "", substr($sComments, 0, 300) . "..."));
		
	$sTemp		= $sKeywords;
	$sKeywords	= $sHeadTitle;
	If ( $sHeadTitle == "" )
	{
		$sKeywords = $sTitle;	// do this because PHP_JK_GALLERIES_OPEN_IMAGEDISPLAY requires sKeywords to be set and if it's blank use the title of the image
		If ( $sKeywords == "" )
			$sKeywords = $sImage;
	}

	Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN_IMAGEDISPLAY.php");

	$sKeywords = $sTemp;	// put it back
	If ( $bGotAllData )
	{
		// print out the information
		DisplayImageInfo();
		
		// increment the number of times this image has been seen
		DB_Update ("UPDATE ImagesInGallery SET NumViews = " . ($iNumViews + 1) . " WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq);

		G_ADMINISTRATION_IncrementDailyDL($iLoginAccountUnq);
		
		DOMAIN_Link_Clear();
	}Else{
		DOMAIN_Message($sError, "ERROR");
	}
	
	Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	//************************************************************************************
	//*																					*
	//*	Displays a single image and it's details.										*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $bGotAllData;
		Global $iImageUnq;
		Global $sTimer;
		Global $sSort;
		Global $iGalleryUnq;
		Global $iNumProds;
		Global $sSendECard;
		Global $iTableWidth;
		Global $sNewDays;
		Global $iLoginAccountUnq;
		Global $iAccountUnq;
		Global $PRIVATE_GALLERIES;
		Global $PUBLIC_GALLERIES;
		Global $sError;
		Global $sHeadTitle;
		
		Global $sTitle;
		Global $sImage;
		Global $sComments;
		Global $sAltTag;
		Global $sThumbnail;
		Global $iRating;
		Global $iNumRaters;
		Global $iNumViews;
		Global $sImageSize;
		Global $sXSize;
		Global $sYSize;
		Global $sFileType;
		Global $aAltImage;
		Global $aImageDesc;
		Global $aAltTag;
		Global $aXSize;
		Global $aYSize;
		Global $aImageSize;
		Global $iConfUnq;
		Global $iThreadUnq;
		Global $sKeywords;
		Global $sAricaur;

		Global $bIsImage;
		Global $iGalleryUnq;
		Global $dAddDate;
		Global $iAccountUnq;
		Global $sName;
		Global $iVisibility;
		Global $iCategoryUnq;
		Global $sPopupWindow;
		Global $iPrimaryG;
		Global $sBreadCrumb;
		Global $iPrevImageUnq;
		Global $iNextImageUnq;
		Global $sNextImageName;
		Global $sPrevImageName;
		Global $iImagePos;
		Global $iTotalImages;
		
		Global $aCopyLinks;
		Global $iNumCopyrights;
		Global $aMiscLinks;
		Global $iNumLinks;
		Global $iNumCustFields;
		Global $aCustFields;
		Global $aProdLinks;
		Global $iNotInPL;
		Global $iInPL;
		Global $aProdQueries;
		Global $aProducts;
		
		$iImageUnq		= Trim(Request("iImageUnq"));
		$sTimer			= Trim(Request("sTimer"));
		$sSort			= Trim(Request("sSort"));
		$iGalleryUnq	= Trim(Request("iGalleryUnq"));	// on newer installations, this will be on the QueryString because for the new referencing function, we need to know which gallery this image is being called from
		$iNumProds		= Trim(DOMAIN_Conf("IMAGEGALLERY_MAX_DISPLAYABLE_PRODUCTS"));
		$sSendECard		= Trim(DOMAIN_Conf("IMAGEGALLERY_SEND_ECARD"));
		$sNewDays		= Trim(DOMAIN_Conf("IMAGEGALLERY_NEWIMAGE_DAYS"));

		If ( $iNumProds == "" )
			$iNumProds = 0;
		If ( ! is_numeric($sNewDays) )
			$sNewDays = 2;

		/* get:
			--image information
			--misc links from the IGMiscLinks
			--product links from IGImageProds and IGPLProds or IGPLs
			--copyright info from IGImageCRs and IGCopyrights
			--next and previous image links and names*/
		If ( ( $iImageUnq != "" ) && ( is_numeric($iImageUnq) ) )
		{
			// check to make sure that the current user has not surpassed their daily allotment of downloads
			$iTemp = G_ADMINISTRATION_DailyDLLeft($iLoginAccountUnq);
			If ( ( $iTemp > 0 ) || ( $iTemp == -1 ) )
			{
				If ( $iGalleryUnq == "" )
				{
					/* lets hope we get the right one -- and it's not a referenced copy (this is to protect legacy bookmarks people may have created)
						 if it's the wrong one, the breadcrumbs will be wrong and so will the date uploaded and the "New" image as well as all things referencing the images gallery (gallery drop-down, category drop-down, etc)
						 at least try and get one from the current domain*/
					$sQuery = "SELECT IG.*, G.AccountUnq, G.Name, G.Visibility, G.CategoryUnq, G.PopupWindow, I.Comments, I.AltTag, I.Image, I.Thumbnail, I.ImageSize, I.XSize, I.YSize, I.FileType, I.Image2, I.Image3, I.Image4, I.Image5, I.Image2Desc, I.Image3Desc, I.Image4Desc, I.Image5Desc, I.AltTag2, I.AltTag3, I.AltTag4, I.AltTag5, I.XSize2, I.YSize2, I.XSize3, I.YSize3, I.XSize4, I.YSize4, I.XSize5, I.YSize5, I.ImageSize2, I.ImageSize3, I.ImageSize4, I.ImageSize5, I.Keywords, I.CookedComments, I.Title, I.ImageUL, I.ThumbUL, I.Alt2UL, I.Alt3UL, I.Alt4UL, I.Alt5UL, I.Aricaur FROM ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK), Images I (NOLOCK) WHERE IG.ImageUnq = " . $iImageUnq . " AND IG.GalleryUnq = G.GalleryUnq AND I.ImageUnq = " . $iImageUnq;
				}Else{
					$sQuery = "SELECT IG.*, G.AccountUnq, G.Name, G.Visibility, G.CategoryUnq, G.PopupWindow, I.Comments, I.AltTag, I.Image, I.Thumbnail, I.ImageSize, I.XSize, I.YSize, I.FileType, I.Image2, I.Image3, I.Image4, I.Image5, I.Image2Desc, I.Image3Desc, I.Image4Desc, I.Image5Desc, I.AltTag2, I.AltTag3, I.AltTag4, I.AltTag5, I.XSize2, I.YSize2, I.XSize3, I.YSize3, I.XSize4, I.YSize4, I.XSize5, I.YSize5, I.ImageSize2, I.ImageSize3, I.ImageSize4, I.ImageSize5, I.Keywords, I.CookedComments, I.Title, I.ImageUL, I.ThumbUL, I.Alt2UL, I.Alt3UL, I.Alt4UL, I.Alt5UL, I.Aricaur FROM ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK), Images I (NOLOCK) WHERE IG.ImageUnq = " . $iImageUnq . " AND IG.GalleryUnq = G.GalleryUnq AND G.GalleryUnq = " . $iGalleryUnq . " AND I.ImageUnq = " . $iImageUnq;
				}
				$rsRecordSet = DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					// Image information
					$sComments		= Trim($rsRow["CookedComments"]);
					$sAltTag		= Trim($rsRow["AltTag"]);
					$sImage			= Trim($rsRow["Image"]);
					$sThumbnail		= Trim($rsRow["Thumbnail"]);
					$iRating		= Trim($rsRow["Rating"]);
					$iNumRaters		= Trim($rsRow["NumRaters"]);
					$iNumViews		= number_format(Trim($rsRow["NumViews"]),0);
					$sImageSize		= Trim($rsRow["ImageSize"]);
					$sXSize			= Trim($rsRow["XSize"]);
					$sYSize			= Trim($rsRow["YSize"]);
					$sFileType		= Trim($rsRow["FileType"]);
					$aAltImage[0]	= Trim($rsRow["Image2"]);
					$aAltImage[1]	= Trim($rsRow["Image3"]);
					$aAltImage[2]	= Trim($rsRow["Image4"]);
					$aAltImage[3]	= Trim($rsRow["Image5"]);
					$aImageDesc[0]	= Trim($rsRow["Image2Desc"]);
					$aImageDesc[1]	= Trim($rsRow["Image3Desc"]);
					$aImageDesc[2]	= Trim($rsRow["Image4Desc"]);
					$aImageDesc[3]	= Trim($rsRow["Image5Desc"]);
					$aAltTag[0]		= Trim($rsRow["AltTag2"]);
					$aAltTag[1]		= Trim($rsRow["AltTag3"]);
					$aAltTag[2]		= Trim($rsRow["AltTag4"]);
					$aAltTag[3]		= Trim($rsRow["AltTag5"]);
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
					$iConfUnq		= Trim($rsRow["ConfUnq"]);
					$iThreadUnq		= Trim($rsRow["ThreadUnq"]);
					$sKeywords		= Trim($rsRow["Keywords"]);
					$sTitle			= Trim($rsRow["Title"]);
					$sAricaur		= Trim($rsRow["Aricaur"]);
	
					// Gallery information
					$iGalleryUnq	= Trim($rsRow["GalleryUnq"]);
					$dAddDate		= Trim($rsRow["AddDate"]);
					$iAccountUnq	= Trim($rsRow["AccountUnq"]);
					$sName			= Trim($rsRow["Name"]);
					$iVisibility	= Trim($rsRow["Visibility"]);
					$iCategoryUnq	= Trim($rsRow["CategoryUnq"]);
					$sPopupWindow	= strtoupper(Trim($rsRow["PopupWindow"]));
					$iPrimaryG		= Trim($rsRow["PrimaryG"]);
					
					// must set these three arrays to 0 if they have blank entries (the db has blanks, the code requires something)
					For ( $x = 0; $x <= 3; $x++ )
						If ( $aXSize[$x] == "" )
							$aXSize[$x] = 0;
					For ( $x = 0; $x <= 3; $x++ )
						If ( $aYSize[$x] == "" )
							$aYSize[$x] = 0;
					For ( $x = 0; $x <= 3; $x++ )
						If ( $aImageSize[$x] == "" )
							$aImageSize[$x] = 0;

					/* now that we have the gallery and gallery owner information,
					 make sure image is not in a gallery that is locked to the current user or they aren't admin*/
					If ( G_ADMINISTRATION_AccessLocked($iGalleryUnq, $iAccountUnq) )
					{
						// make sure image is not in a PRIVATE gallery that is not their gallery or they aren't admin
						If ( ( ( $iVisibility == $PRIVATE_GALLERIES ) && ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( $iAccountUnq == $iLoginAccountUnq ) ) ) || ( $iVisibility == $PUBLIC_GALLERIES ) )
						{
							// Get the copyright information
							$iCount			= 0;
							$sQuery			= "SELECT ICR.CopyUnq, ICR.GenericCopy, C.URL C_URL, C.Copyright, C.Details FROM IGImageCRs ICR (NOLOCK), IGCopyrights C (NOLOCK) WHERE ICR.ImageUnq = " . $iImageUnq . " AND ICR.CopyUnq = C.CopyUnq";
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
							{
								$aCopyLinks[0][$iCount]	= $rsRow["CopyUnq"];
								$aCopyLinks[1][$iCount]	= Trim($rsRow["GenericCopy"]);
								$aCopyLinks[2][$iCount]	= Trim($rsRow["C_URL"]);
								$aCopyLinks[3][$iCount]	= Trim($rsRow["Copyright"]);
								$aCopyLinks[4][$iCount]	= Trim($rsRow["Details"]);
								$iCount++;
							}
							$iNumCopyrights = $iCount;
							
							// Get the misc links information
							$iCount			= 0;
							$sQuery			= "SELECT URL, OnSite, Description FROM IGMiscLinks (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
							{
								$aMiscLinks[0][$iCount]	= Trim($rsRow["URL"]);
								$aMiscLinks[1][$iCount]	= Trim($rsRow["OnSite"]);
								$aMiscLinks[2][$iCount]	= Trim($rsRow["Description"]);
								$iCount++;
							}
							$iNumLinks = $iCount;
							
							// Get the custom fields information
							$iCount			= 0;
							$sQuery			= "SELECT M.DataType, M.Name, D.VarCharData, D.TextData FROM IGMap M (NOLOCK), IGData D (NOLOCK) WHERE D.ImageUnq = " . $iImageUnq . " AND M.MapUnq = D.MapUnq AND D.Hidden = 'N' ORDER BY D.Position";
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
							{
								If ( strtoupper(Trim($rsRow["DataType"])) == "T" ) {
									$aCustFields[1][$iCount] = Trim($rsRow["TextData"]);
								}Else{
									$aCustFields[1][$iCount] = Trim($rsRow["VarCharData"]);
								}
								$aCustFields[0][$iCount] = Trim($rsRow["Name"]);
								$iCount++;
							}
							$iNumCustFields = $iCount;
							
							If ( $iNumProds > 0 )
							{
								// Get the products not in PLs (must check domainunq because categoryunq=0 is on all domains)
								$iNotInPL = 0;
								$sQuery	= "SELECT PLP.ProdUnq, PLP.ProdID, PLP.Name, PLP.Price, PLP.URL, PLP.ImageURL FROM IGPLProds PLP (NOLOCK), IGImageProds IPRODS (NOLOCK) WHERE (IPRODS.ImageUnq = " . $iImageUnq . " OR IPRODS.GalleryUnq = " . $iGalleryUnq . " OR IPRODS.CategoryUnq = " . $iCategoryUnq . ") AND IPRODS.ProdUnq = PLP.ProdUnq AND IPRODS.PLUnq = 0 ORDER BY NEWID()";
								DB_Query("SET ROWCOUNT " . $iNumProds);
								$rsRecordSet = DB_Query($sQuery);
								DB_Query("SET ROWCOUNT 0");
								While ( $rsRow = DB_Fetch($rsRecordSet) )
								{
									$aProducts[0][$iNotInPL]		= $rsRow["ProdID"];
									$aProducts[1][$iNotInPL]		= Trim($rsRow["Name"]);
									$aProducts[2][$iNotInPL]		= Trim($rsRow["Price"]);
									$aProducts[3][$iNotInPL]		= Trim($rsRow["URL"]);
									$aProducts[4][$iNotInPL]		= Trim($rsRow["ImageURL"]);
									$iNotInPL++;
								}
	
								// Get the PL query information (must check domainunq because categoryunq=0 is on all domains)
								//	Pull $iNumProds number of PL's at random
								//	then later on we'll pull $iNumProds number of prods from each of the PL's.
								//	So, we'll have and array w/ $iNumProds prods not in a PL, and up to $iNumProds*$iNumProds
								//	prods from PLs. Then we'll pick at random $iNumProds from that array to display.
								//	This will make sure we get a complete random set.
								$iInPL = 0;
								$sQuery = "SELECT IPRODS.ProdID, PL.ProdQueryText, PL.SQLServer, PL.SQLLogin, PL.SQLPassword, PL.DSNName, PL.DSNLogin, PL.DSNPassword, PL.PurchURL, PL.DBName, PL.ImageURL, NEWID() FROM IGImageProds IPRODS (NOLOCK), IGPLs PL (NOLOCK) WHERE (IPRODS.ImageUnq = " . $iImageUnq . " OR IPRODS.GalleryUnq = " . $iGalleryUnq . " OR IPRODS.CategoryUnq = " . $iCategoryUnq . ") AND IPRODS.PLUnq = PL.PLUnq UNION ALL SELECT SP.ProdID, PL.ProdQueryText, PL.SQLServer, PL.SQLLogin, PL.SQLPassword, PL.DSNName, PL.DSNLogin, PL.DSNPassword, PL.PurchURL, PL.DBName, PL.ImageURL, NEWID() FROM IGSubPLUse SU (NOLOCK), IGSubPLProds SP (NOLOCK), IGSubPL S (NOLOCK), IGPLs PL (NOLOCK) WHERE (SU.ImageUnq = " . $iImageUnq . " OR SU.GalleryUnq = " . $iGalleryUnq . " OR SU.CategoryUnq = " . $iCategoryUnq . ") AND SU.SubPLUnq = SP.SubPLUnq AND SU.SubPLUnq = S.SubPLUnq AND PL.PLUnq = S.PLUnq ORDER BY NEWID()";
								DB_Query("SET ROWCOUNT " . $iNumProds);
								$rsRecordSet = DB_Query($sQuery);
								DB_Query("SET ROWCOUNT 0");
								While ( $rsRow = DB_Fetch($rsRecordSet) )
								{
									$aProdQueries[0][$iCount]		= $rsRow["ProdID"];
									$aProdQueries[1][$iCount]		= Trim($rsRow["ProdQueryText"]);
									$aProdQueries[2][$iCount]		= Trim($rsRow["SQLServer"]);
									$aProdQueries[3][$iCount]		= Trim($rsRow["SQLLogin"]);
									$aProdQueries[4][$iCount]		= Trim($rsRow["SQLPassword"]);
									$aProdQueries[5][$iCount]		= Trim($rsRow["DSNName"]);
									$aProdQueries[6][$iCount]		= Trim($rsRow["DSNLogin"]);
									$aProdQueries[7][$iCount]		= Trim($rsRow["DSNPassword"]);
									$aProdQueries[8][$iCount]		= Trim($rsRow["PurchURL"]);
									$aProdQueries[9][$iCount]		= Trim($rsRow["DBName"]);
									$aProdQueries[10][$iCount]		= Trim($rsRow["ImageURL"]);
									$iInPL++;
								}
							}

							If ( $sSort == "" ) {
								$sSortString = "IG.Position ASC";
							}Else{
								If ( $sSort == "ImageNum" ) {
									$sSortString = "IG.Position ASC";
								}ElseIf ( $sSort == "Image_A" ) {
									$sSortString = "I.Image ASC";
								}ElseIf ( $sSort == "Image_D" ) {
									$sSortString = "I.Image DESC";
								}ElseIf ( $sSort == "Rating_A" ) {
									$sSortString = "IG.Rating/is_null(NullIf(IG.Numraters,0),1) ASC, IG.Numraters ASC, IG.Position";
								}ElseIf ( $sSort == "Rating_D" ) {
									$sSortString = "IG.Rating/is_null(NullIf(IG.Numraters,0),1) DESC, IG.Numraters DESC, IG.Position";
								}ElseIf ( $sSort == "NumViews_A" ) {
									$sSortString = "IG.NumViews ASC";
								}ElseIf ( $sSort == "NumViews_D" ) {
									$sSortString = "IG.NumViews DESC";
								}ElseIf ( $sSort == "FileType_A" ) {
									$sSortString = "I.FileType ASC";
								}ElseIf ( $sSort == "FileType_D" ) {
									$sSortString = "I.FileType DESC";
								}Else{
									$sSortString = "IG.Position ASC";
								}
							}
							
							/* get the NEXT and PREVIOUS image(s)...do it according to the sSort sorting (since they might have manually set the sort
								on the thumbnailview.php page, we don't want to mess them up*/
							$bDone			= FALSE;
							$iImagePos		= 1;
							$iCurImagePos	= 0;
							$sQuery			= "SELECT I.ImageUnq, I.Title FROM ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK), Images I (NOLOCK) WHERE IG.ImageUnq = I.ImageUnq AND IG.GalleryUnq = G.GalleryUnq AND G.GalleryUnq = " . $iGalleryUnq . " ORDER BY " . $sSortString;
							$rsRecordSet	= DB_Query($sQuery);
							While ( ( $rsRow = DB_Fetch($rsRecordSet) ) && ( ! $bDone ) )
							{
								$iCurImageUnq	= Trim($rsRow["ImageUnq"]);
								$sCurImageName	= Trim($rsRow["Title"]);
								$iCurImagePos	= $iCurImagePos + 1;
								If ( $iCurImageUnq == $iImageUnq )
								{
									If ( $rsRow = DB_Fetch($rsRecordSet) )
									{
										$iNextImageUnq	= $rsRow["ImageUnq"];
										$sNextImageName	= $rsRow["Title"];
									}
									$bDone = TRUE;
								}Else{
									$iPrevImageUnq	= $iCurImageUnq;
									$sPrevImageName	= $sCurImageName;
									$iImagePos		= $iCurImagePos + 1;
								}
							}
							
							$sQuery			= "SELECT Count(*) FROM ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK), Images I (NOLOCK) WHERE IG.ImageUnq = I.ImageUnq AND IG.GalleryUnq = G.GalleryUnq AND G.GalleryUnq = " . $iGalleryUnq;
							$rsRecordSet	= DB_Query($sQuery);
							If ( $rsRow = DB_Fetch($rsRecordSet) )
								$iTotalImages = $rsRow[0];
							
							// reset any subscription emails
							G_ADMINISTRATION_ResetSubscriptionEmail($iLoginAccountUnq);

							G_STRUCTURE_FileType($sFileType, $bIsImage, $iImageUnq, "../../", 0);

							G_STRUCTURE_CatBreadcrumbs($sBreadCrumb, $iCategoryUnq, $iGalleryUnq, $iImageUnq);

							GetTitle($sHeadTitle, $iCategoryUnq, $iGalleryUnq, $iImageUnq);	// same function as getting breadcrumbs, but puts in the title

							$bGotAllData = TRUE;
						}Else{
							// the image is within a private gallery and the user is not an admin or the gallery owner
							$sError = "Sorry this image is within a private gallery.";
						}
					}Else{
						// the image is within a locked gallery
						$sError = "Sorry this image is within a locked gallery.";
					}
				}Else{
					// unable to find the image
					$sError = "Unable to find the image in the database.";					
				}
			}Else{
				// the current user has downloaded their max for today
				$sError = "Sorry, but you have reached your daily allotment of image downloads.";
			}
		}Else{
			// can't display the image because there is no iImageUnq on the QueryString
			$sError = "No iImageUnq on the QueryString - unable to display any image.";
		}
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*	Displays the image and it's information.										*
	//*																					*
	//************************************************************************************
	Function DisplayImageInfo()
	{
		Global $sXSize;
		Global $sYSize;
		Global $sImageSize;
		Global $sBreadCrumb;
		Global $iAccountUnq;
		Global $iImagePos;
		Global $iNextImageUnq;
		Global $sSort;
		Global $sTimer;
		Global $iGalleryUnq;
		Global $aVariables;
		Global $aValues;
		Global $iNumViews;
		Global $iTotalImages;
		Global $dAddDate;
		Global $iDBLoc;
		Global $iTtlNumItems;
		Global $iCategoryUnq;
		Global $iNumPerPage;
		Global $iImageUnq;
		Global $sPopupWindow;
		Global $iTableWidth;
		Global $bIsImage;
		Global $iPrevImageUnq;
		Global $iNextImageUnq;
		Global $sComments;
		Global $bHasAccount;
		Global $sSendECard;
		Global $sNextImageName;
		Global $sPrevImageName;
		Global $aAltImage;
		Global $aXSize;
		Global $aYSize;
		Global $sThumbnail;
		Global $sAricaur;
		Global $iPrimaryG;
		Global $sTemplates;
		Global $sTitle;
		Global $sSiteURL;
		
		$sBorderColor = $GLOBALS["PageBorder"];
		If ( $sXSize == "" )
			$sXSize = 0;
		If ( $sYSize == "" )
			$sYSize = 0;
		?>
		<script language='JavaScript1.2' type='text/javascript'>

			function IG_Popup(x, y, iImageUnq, sImageNum){
				var leftprop, topprop, screenX, screenY, cursorX, cursorY, wWindow;

				if(navigator.appName == "Microsoft Internet Explorer") {
					screenY = document.body.offsetHeight;
					screenX = window.screen.availWidth;
				}else{
					screenY = window.outerHeight
					screenX = window.outerWidth
				}
		
				leftvar = (screenX - x) / 2;
				rightvar = (screenY - y) / 2;
				if(navigator.appName == "Microsoft Internet Explorer") {
					leftprop = leftvar;
					topprop = rightvar;
				}else{
					leftprop = (leftvar - pageXOffset);
					topprop = (rightvar - pageYOffset);
		   		}

				if ( ( screen.width > (2*screen.height) ) || ( screen.height > (2*screen.width) ) ) {
					// we probably have a dual-monitor setup if the width is more than 2x the height, or the height is more than 2x the width
					wWindow=window.open("<?=$sSiteURL?>/IG_Popup.php?iImageUnq=" + iImageUnq,"","scrollbars,resizable=yes");
				}else{
					if ( ( x == 0 ) || ( y == 0 ) ) {
						<?php If ( $sPopupWindow == "FULL" ) {?>
						wWindow=window.open("<?=$sSiteURL?>/IG_Popup.php?iImageUnq=" + iImageUnq + "&sImageNum=" + sImageNum,"","fullscreen=yes, scrollbars=auto");
						<?php }Else{?>
						wWindow=window.open("<?=$sSiteURL?>/IG_Popup.php?iImageUnq=" + iImageUnq + "&sImageNum=" + sImageNum,"","scrollbars,resizable=yes");
						<?php }?>
					}else{
						<?php If ( $sPopupWindow == "FULL" ) {?>
						wWindow=window.open("<?=$sSiteURL?>/IG_Popup.php?iImageUnq=" + iImageUnq + "&sImageNum=" + sImageNum,"","fullscreen=yes, scrollbars=auto");
						<?php }Else{?>
						wWindow=window.open("<?=$sSiteURL?>/IG_Popup.php?iImageUnq=" + iImageUnq + "&sImageNum=" + sImageNum,"","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=auto,resizable=no,width=" + x + ",height=" + y + ", left=" + leftprop + ", top=" + topprop);
						<?php }?>
					}
				}
				wWindow.focus();
			}
			
			function CopyRightPopup(iCopyrightUnq){
				var leftprop, topprop, screenX, screenY, cursorX, cursorY, wWindow, x, y;
				
				x=500;
				y=500;

				if(navigator.appName == "Microsoft Internet Explorer") {
					screenY = document.body.offsetHeight;
					screenX = window.screen.availWidth;
				}else{
					screenY = window.outerHeight
					screenX = window.outerWidth
				}
		
				leftvar = (screenX - x) / 2;
				rightvar = (screenY - y) / 2;
				if(navigator.appName == "Microsoft Internet Explorer") {
					leftprop = leftvar;
					topprop = rightvar;
				}else{
					leftprop = (leftvar - pageXOffset);
					topprop = (rightvar - pageYOffset);
		   		}
		   		
				wWindow=window.open("<?=$sSiteURL?>/IG_CopyrightPopup.php?iCopyrightUnq=" + iCopyrightUnq,"","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=" + x + ",height=" + y + ", left=" + leftprop + ", top=" + topprop);
				wWindow.focus();
			}
			
			
			function ReturnScreenWidth(){
				if(navigator.appName == "Microsoft Internet Explorer") {
					return window.screen.availWidth;
				}else{
					return window.outerWidth
				}
			}
			
			
			<?php If ( ( $sTimer != "" ) && ( $iNextImageUnq != "" ) ) {?>
				function Slideshow() {
					location.href = "ImageDetail.php?iImageUnq=<?=$iNextImageUnq?>&sSort=<?=$sSort?>&sTimer=<?=$sTimer?>&iGalleryUnq=<?=$iGalleryUnq?>";
				}
				
				setTimeout("Slideshow();",<?=$sTimer?>);
			<?php }?>

		</script>
		<?php
		$sDisplayCustomFields		= DisplayCustomFields();
		$sDisplayTimerDown			= DisplayTimerDown();
		$sDisplayCopyrights			= DisplayCopyrights();
		$sDisplayKeywords			= DisplayKeywords();
		$sDisplayOtherLinks			= DisplayOtherLinks();
		$sDisplayAltViews			= DisplayAltViews();
		$sDisplayImage				= DisplayImage();
		$sDisplayProducts			= DisplayProducts();
		$sDisplayRatings			= DisplayRatings();
		$sDisplayGalleriesDropDown	= DisplayGalleriesDropDown();
		$sDisplayCategoryDropDown	= DisplayCategoryDropDown();
		$sDomainLink				= DOMAIN_Link("G");
		$sPrevImage					= "";
		$sNextImage					= "";
		$sEcardLink					= "";
		$sImageSizeText				= "";
		$sAricaurLink				= "";
		$sAddDate					= date("F j, Y", strtotime($dAddDate));
		$sGalleryOwnerName			= "";
		$sBreadcrumbArrow			= "";

		If ( $iPrevImageUnq != "" )
		{
			$sPrevImageName = " View the previous image " . htmlentities($sPrevImageName);
			$sPrevImage = "<a href='ImageDetail.php?iImageUnq=" . $iPrevImageUnq . "&sSort=" . $sSort . "&iGalleryUnq=" . $iGalleryUnq . "'><img src='" . G_STRUCTURE_DI("Previous.gif", $GLOBALS["COLORBASED"]) . "' alt=\"" . $sPrevImageName . "\" border=0></a><br><font size=-2>Previous</font>";
		}
		
		If ( $iNextImageUnq != "" )
		{
			$sNextImageName = " View the next image " . htmlentities($sNextImageName);
			$sNextImage = "<a href='ImageDetail.php?iImageUnq=" . $iNextImageUnq . "&sSort=" . $sSort . "&iGalleryUnq=" . $iGalleryUnq . "'><img src='" . G_STRUCTURE_DI("Next.gif", $GLOBALS["COLORBASED"]) . "' alt=\"" . $sNextImageName . "\" border=0></a><br><font size=-2>Next</font>";
		}
		
		If ( $bIsImage )
		{
			If ( DOMAIN_Has_RemoteHost() ) 
			{
				If ( ( $bHasAccount ) || ( $sSendECard == "ALL" ) )
				{
					$aVariables[0] = "iDBLoc";
					$aVariables[1] = "iGalleryUnq";
					$aVariables[2] = "sSort";
					$aVariables[3] = "iTtlNumItems";
					$aVariables[4] = "iCategoryUnq";
					$aVariables[5] = "iNumPerPage";
					$aVariables[6] = "iImageUnq";
					$aValues[0] = $iDBLoc;
					$aValues[1] = $iGalleryUnq;
					$aValues[2] = $sSort;
					$aValues[3] = $iTtlNumItems;
					$aValues[4] = $iCategoryUnq;
					$aValues[5] = $iNumPerPage;
					$aValues[6] = $iImageUnq;
					$sEcardLink = "<a href='SendECard?" . DOMAIN_Link("G") . "'><img src='" . G_STRUCTURE_DI("SendECard.gif", $GLOBALS["SCHEMEBASED"]) . "' alt=\" Send this image as an eCard \" border=0></a>";
				}Else{
					$sEcardLink = "Please <a href='" . $sSiteURL . "/UserArea/Login.php' class='MediumNavPage'>login</a> or <a href='" . $sSiteURL . "/UserArea/NewAccounts/index.php' class='MediumNavPage'>register</a> to send this image as an eCard.";
				}
			}
		}
		
		If ( $sImageSize == "" )
			$sImageSize = 0;
		If ( $sImageSize > 0 )
			$sImageSizeText = number_format($sImageSize/1024,0) . " k";
			
		If ( $sAricaur != "" )
		{
			$VI = trim(DOMAIN_Conf("ARICAUR_WEBMASTER_ID"));
			If ( $VI != "" )
			{
				If ( $_SERVER['REMOTE_ADDR'] == "192.168.1.2" ) {
					$sAricaurLink = "<a href='http://www.aricaurdev.com/catalog/index.php?iA=1&VI=" . $VI . "&iImageUnq=" . $iImageUnq . "' target='_blank' class='MediumNavPage'><img src='" . G_STRUCTURE_DI("AricaurPurchase.gif", $GLOBALS["SCHEMEBASED"]) . "' border=0 alt=''></a>";
				}Else{
					$sAricaurLink = "<a href='http://www.aricaur.com/catalog/index.php?iA=1&VI=" . $VI . "&iImageUnq=" . $iImageUnq . "' target='_blank' class='MediumNavPage'><img src='" . G_STRUCTURE_DI("AricaurPurchase.gif", $GLOBALS["SCHEMEBASED"]) . "' border=0 alt=''></a>";
				}
			}
		}
		
		$sVisibility	= "PUBLIC";
		$sDispOwnerName = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_OWNER_INFO")));
		If ( $sDispOwnerName == "YES" )
		{
			$sTemp = Trim(ACCNT_ReturnADV("PHPJK_FirstName", "V", $iAccountUnq, 0, $sVisibility) . " " . ACCNT_ReturnADV("PHPJK_LastName", "V", $iAccountUnq, 0, $sVisibility));
			If ( $sTemp === "" )
				$sTemp = ACCNT_UserName($iAccountUnq);
			$sGalleryOwnerName = "By:  <a href='G_Display.php?iAccountUnq=" . $iAccountUnq . "&iCategoryUnq=-1' class='SmallNavPage'>" . $sTemp . "</a>";
		}
		
		$sBreadcrumbArrow = G_STRUCTURE_DI("Arrow.gif", $GLOBALS["COLORBASED"]);

		Require("Templates/" . $sTemplates . "/ImageDetail.php");
		
		echo $sImageDetail;
		?>
		<?php 

	}
	//************************************************************************************


	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayImage()
	{		
		Global $iImageUnq;
		Global $iGalleryUnq;
		Global $sXSize;
		Global $sYSize;
		Global $iTableWidth;
		Global $iPrimaryG;
		Global $iAccountUnq;
		Global $sAltTag;
		Global $sFileType;
		Global $sDispText;
		Global $dAddDate;
		Global $sNewDays;
		Global $bIsImage;
		Global $sThumbnail;
		Global $sImage;
		Global $sDisplayImage;
		Global $sSiteURL;
		
		$bDomainCheck		= strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DOMAINCHECK")));
		$iScreenModifyer	= 220;

		// print it out
		If ( $bIsImage )
		{
			// check to see if the width of the image is more than iTableWidth. If it is, then limit the displayed width of the image to iTableWidth
			$sTemp = "";
			If ( strpos($iTableWidth, "%") !== false )
			{
				// since we've got a tablewidth of a percentage, we can't do the math...so guess at 640 pixels wide
				If ( $sXSize > (640 - $iScreenModifyer) )
					$sTemp = "width=" . (640 - $iScreenModifyer);
			}Else
			{
				If ( $sXSize > ($iTableWidth - $iScreenModifyer) )
					$sTemp = "width=" . ($iTableWidth - $iScreenModifyer);	// this is the width of the Aricaur button (plus a bit more) -- the widest thing in the left nav (if they use this template)
			}

			If ( $sXSize > 0 )
			{
				// at least try and use JavaScript to resize the image in case it's too big
				$sDispText = "<script language='JavaScript1.2' type='text/javascript'>\n";
				$sDispText = $sDispText . "	if (ReturnScreenWidth() < " . ($sXSize+$iScreenModifyer) . "){\n";
				$sDispText = $sDispText . "		document.write(\"<img src = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sAccountUnq=" . $iAccountUnq . "&iGalleryUnq=" . $iPrimaryG . "&iImageUnq=" . $iImageUnq . "' alt = '" . htmlentities(Trim($sAltTag)) . "' border=0 " . $sTemp . "\")\n";

				If ( $bDomainCheck == "YES" ) {		// they want to go through the script to dl images (and/or their mime types are ok w/ going through the script)
					$sDispText = $sDispText . "		document.write(\"<BR><center><A href='JavaScript:IG_Popup(" . $sXSize . ", " . $sYSize . ", " . $iImageUnq . ", \\\"0\\\");' class='SmallNavPage'>This image has been reduced to fit your screen. Click here to view it full size.</a></center>\")\n";
				}Else{
					$sDispText = $sDispText . "		document.write(\"<img src = '" . DOMAIN_Conf("IG") . "/" . $iAccountUnq . "/" . $iPrimaryG . "/" . $sImage . "' alt = '" . htmlentities(Trim($sAltTag)) . "' border=0 " . $sTemp . ">\")\n";
				}
				$sDispText = $sDispText . "	}else{\n";
				If ( $bDomainCheck == "YES" ) {		// they want to go through the script to dl images (and/or their mime types are ok w/ going through the script)
					$sDispText = $sDispText . "		document.write(\"<img src = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sAccountUnq=" . $iAccountUnq . "&iGalleryUnq=" . $iPrimaryG . "&iImageUnq=" . $iImageUnq . "' alt = '" . htmlentities(Trim($sAltTag)) . "' border=0 " . $sTemp . ">\")\n";
				}Else{
					$sDispText = $sDispText . "		document.write(\"<img src = '" . DOMAIN_Conf("IG") . "/" . $iAccountUnq . "/" . $iPrimaryG . "/" . $sImage . "' alt = '" . htmlentities(Trim($sAltTag)) . "' border=0>\")\n";
				}
				If ( $sTemp != "" )
				{
					// need to dbl check screen width -- in case this link was already displayed because of the code above
					$sDispText = $sDispText . "	if (ReturnScreenWidth() >= " . ($sXSize+$iScreenModifyer) . "){\n";
					$sDispText = $sDispText . "		document.write(\"<BR><center><A href='JavaScript:IG_Popup(" . $sXSize . ", " . $sYSize . ", " . $iImageUnq . ", \\\"0\\\");' class='SmallNavPage'>This image has been reduced to fit your screen. Click here to view it full size.</a></center>\")\n";
					$sDispText = $sDispText . "	}\n";
				}
				$sDispText = $sDispText . "	}\n";
				$sDispText = $sDispText . "</script>\n";
			}Else{
				If ( $bDomainCheck == "YES" ) {		// they want to go through the script to dl images (and/or their mime types are ok w/ going through the script)
					$sDispText = "<img src = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sAccountUnq=" . $iAccountUnq . "&iGalleryUnq=" . $iPrimaryG . "&iImageUnq=" . $iImageUnq . "' alt = \"" . htmlentities(Trim($sAltTag)) . "\" border=0 " . $sTemp . ">";
				}Else{
					$sDispText = "<img src = '" . DOMAIN_Conf("IG") . "/" . $iAccountUnq . "/" . $iPrimaryG . "/" . $sImage . "' alt = '" . htmlentities(Trim($sAltTag)) . "' border=0>\n";
				}				
			}
		}Else{
			If ( $sTitle == "" ) 
			{
				$sTempTitle = $sAltTag;
				If ( $sTempTitle == "" )
					$sTempTitle = "Download File";
			}Else{
				$sTempTitle = $sTitle;
			}
			$sDispText = "<a href = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sAccountUnq=" . $iAccountUnq . "&iGalleryUnq=" . $iPrimaryG . "&iImageUnq=" . $iImageUnq . "'><img src = \"" . $sSiteURL . "/Attachments/DispThumb.php?sAccountUnq=" . $iAccountUnq . "&sThumbnail=" . $sThumbnail . "&iGalleryUnq=" . $iPrimaryG . "\" alt = \"" . htmlentities($sTempTitle) . "\" border=0></a><br>";
			$sDispText = $sDispText . "<img src='Images/MediaIcons/" . $sFileType . ".gif' alt = '" . $sFileType . " file'>&nbsp;<a href = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sAccountUnq=" . $iAccountUnq . "&iGalleryUnq=" . $iPrimaryG . "&iImageUnq=" . $iImageUnq . "' class='LargeNavPage'>" . $sTempTitle . "</a>";
		}
		$sDisplayImage .= $sDispText;
		If ( DateDiff("d", $dAddDate, time()) <= $sNewDays )
			$sDisplayImage .= "<br><img src='" . G_STRUCTURE_DI("NewImage.gif", $GLOBALS["SCHEMEBASED"]) . "' alt=\" NEW! \" border=0>";

		Return $sDisplayImage;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayProducts()
	{
		
		Global $aProdLinks;
		Global $aProdQueries;
		Global $iNumProds;
		Global $QueryConnection;
		Global $aProducts;
		Global $iNotInPL;
		Global $iInPL;
		Global $sDisplayProducts;
	
		//$aProdLinks[0][$iCount]		= $rsRow["ProdID")
		//$aProdLinks[1][$iCount]		= Trim($rsRow["Name"))
		//$aProdLinks[2][$iCount]		= Trim($rsRow["Price"))
		//$aProdLinks[3][$iCount]		= Trim($rsRow["URL"))
		//$aProdLinks[4][$iCount]		= Trim($rsRow["ImageURL"))
	
		//$aProdQueries[0][$x]		= $rsRow["ProdID")
		//$aProdQueries[1][$x]		= Trim($rsRow["ProdQueryText"))
		//$aProdQueries[2][$x]		= Trim($rsRow["SQLServer"))
		//$aProdQueries[3][$x]		= Trim($rsRow["SQLLogin"))
		//$aProdQueries[4][$x]		= Trim($rsRow["SQLPassword"))
		//$aProdQueries[5][$x]		= Trim($rsRow["DSNName"))
		//$aProdQueries[6][$x]		= Trim($rsRow["DSNLogin"))
		//$aProdQueries[7][$x]		= Trim($rsRow["DSNPassword"))
		//$aProdQueries[8][$x]		= Trim($rsRow["PurchURL"))
		//$aProdQueries[9][$x]		= Trim($rsRow["DBName"))
		//$aProdQueries[10][$x]	= Trim($rsRow["ImageURL"))

		$iCount = $iNotInPL-1;	// $iCount will become the TOTAL number of products in the $aProducts array
		For ( $x = 0; $x < $iInPL; $x++ )
		{
			// get the product info from the PL info and put into the array
			If ( ( $aProdQueries[5][$x] != "" ) || ( $aProdQueries[6][$x] != "" ) || ( $aProdQueries[7][$x] != "" ) )
			{
				// use the DSN
				OpenDB_DSN($aProdQueries[5][$x], $aProdQueries[6][$x], $aProdQueries[7][$x]);
				DB_Query("SET ROWCOUNT " . $iNumProds);
				$rsRecordSet = DB_ODBC_Query($QueryConnection, str_replace("1:", $aProdQueries[0][$x], $aProdQueries[1][$x]));
				DB_Query("SET ROWCOUNT 0");
				While ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$aProducts[0][$iCount] = $aProdQueries[0][$x];
					$aProducts[1][$iCount] = Trim($rsRow[0]);
					$aProducts[2][$iCount] = Trim($rsRow[1]);
					$aProducts[3][$iCount] = str_replace($aProdQueries[8][$x], "1:", $aProdQueries[0][$x]);
					If ( Trim($rsRow[2]) != "" )
						$aProducts[4][$iCount] = str_replace($aProdQueries[10][$x], "1:", Trim($rsRow[2]));
					$iCount++;
				}
			}Else{
				// use the connection string
				OpenDB_CS($aProdQueries[3][$x], $aProdQueries[4][$x], $aProdQueries[2][$x], $aProdQueries[9][$x]);
				DB_Query("SET ROWCOUNT " . $iNumProds);
				$rsRecordSet = DB_Query(str_replace("1:", $aProdQueries[0][$x], $aProdQueries[1][$x]), $QueryConnection);
				DB_Query("SET ROWCOUNT 0");
				While ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$aProducts[0][$iCount] = $aProdQueries[0][$x];
					$aProducts[1][$iCount] = Trim($rsRow[0]);
					$aProducts[2][$iCount] = Trim($rsRow[1]);
					$aProducts[3][$iCount] = str_replace($aProdQueries[8][$x], "1:", $aProdQueries[0][$x]);
					If ( Trim($rsRow[2]) != "" )
						$aProducts[4][$iCount] = str_replace($aProdQueries[10][$x], "1:", Trim($rsRow[2]));
					$iCount++;
				}
			}
		}

		$iProdThumbWidth = Trim(DOMAIN_Conf("IMAGEGALLERY_PRODUCTIMAGES_MAX_WIDTH"));
		If ( $iProdThumbWidth == "" )
			$iProdThumbWidth = 120;
		
		If ( $iCount > 0 )
		{
		$sDisplayProducts .= "</td><td><img src='Images/Blank.gif' width=10 height=10></td><td align=right valign=top>";
		$sDisplayProducts .= "<table width=" . $iProdThumbWidth . " cellpadding = 0 cellspacing = 0 border = 0><tr><td bgcolor='" . $sBorderColor . "'><table cellpadding = 5 cellspacing=1 border=0 width=" . $iProdThumbWidth . ">";
				$sDisplayProducts .= "<tr><td align=center bgcolor=" . $GLOBALS["PageBGColor"] . ">";
				$sDisplayProducts .= "<font size=-2 color=" . $GLOBALS["PageText"] . "><b>2Products & Services</b><br>";
				$sDisplayProducts .= "</td></tr>";
				$sDisplayProducts .= "<tr><td align=center bgcolor = " . $sBGColor . ">";
				$sDisplayProducts .= "</td></tr>";
				srand();
				For ( $x = 0; $x < $iNumProds; $x++)	// reset the array $aY
					$aY[$x] = "";
				For ( $x = 0; $x <= $iCount; $x++)	// just get $iNumProds products from the big array
				{
					$iRandCount = 0;
					$y = rand(0, $iCount);
					While ( $y !== False )
					{
						If ( $iRandCount >= $iCount )
						{
							// now loop through all the $aY's to see if the randomization missed one before $iRandCount got up to $iCount
							For ( $z = 0; $z <= $iCount; $z++)
							{
								If ( $aY[$z] != "T" )
								{
									$y			= $z;		// found one in the $x position, so set $y to $x and let it go. the next if/then will break the outer loop
									$iRandCount = $iCount;	// reduce $iRandCount so the if/then below will go through now that we found one
									break;
								}
							}
							If ( $iRandCount = $iCount ) 	// then we found one since we reset $iRandCount
								break;
						}
						If ( $aY[$y] != "T" )
							break;	// found a free one!
	
						$iRandCount++;
						$y = rand(0, $iCount);
					}
	
					If ( $iRandCount <= $iCount )
					{
						If ( $aProducts[1][$y] != "" )
						{
							$sDisplayProducts .= "<tr><td bgcolor = " . $sBGColor . " align=center>";
							$sDisplayProducts .= "<a href='" . $aProducts[3][$y] . "' class='SmallNav1'>";
							If ( $aProducts[4][$y] != "" )
								$sDisplayProducts .= "<img src='" . $aProducts[4][$y] . "' border=0 alt=\"" . htmlentities($aProducts[1][$y]) . "\" width=" . $iProdThumbWidth . "><br>";
							$sDisplayProducts .= $aProducts[1][$y] . "</a><br><br>";
							$sDisplayProducts .= "</td></tr>";
							$aY[$y] = "T";	// mark this products index as used
						}
					}Else{
						break;
					}
				}
								$sDisplayProducts .= "</table></td></tr></table>";
$sDisplayProducts .= "</td></tr></table>";								
		}
		Return $sDisplayProducts;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayRatings()
	{
		
		Global $iImageUnq;
		Global $iGalleryUnq;
		Global $iLoginAccountUnq;
		Global $iTtlRate;
		Global $iRating;
		Global $iNumRaters;
		Global $bAlreadyVoted;
		Global $iRating;
		Global $sDisplayRatings;

		$sDispVoting	= strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_VOTING")));
		
		If ( $sDispVoting == "YES" )
		{
			$sQuery			= "SELECT Rating FROM IGRaters (NOLOCK) WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $iGalleryUnq . " AND AccountUnq = " . $iLoginAccountUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) ) {
				$bAlreadyVoted = TRUE;
			}Else{
				$bAlreadyVoted = FALSE;
			}
		}
		
		If ( $iRating != "" )
		{
			If ( ( $iRating == 0 ) || ( $iNumRaters == 0 ) ) {
				$iTtlRate = 0;
			}Else{
				$iTtlRate = $iRating / $iNumRaters;
			}
		}Else{
			$iTtlRate = 0;
		}
		
		If ( $sDispVoting == "YES" )
		{
			If ( $iTtlRate > 0 )
			{
				$sDisplayRatings .= "<font size=-2><b>Rating: </b>";
				$sDisplayRatings .= number_format($iTtlRate, 2);
				$sDisplayRatings .= "(" . number_format($iNumRaters, 0) . " votes)</font>";
			}
			If ( ! $bAlreadyVoted )
			{
				$sDisplayRatings .= "<Center><form action='RateImage.php?" . DOMAIN_Link("G") . "' method='post' class='PageForm'>";
				$sDisplayRatings .= "<input type='hidden' name='iImageUnq' value='" . $iImageUnq . "'>";
				$sDisplayRatings .= "<input type='hidden' name='iGalleryUnq' value='" . $iGalleryUnq . "'>";
				$sDisplayRatings .= "<select name='iRating'>";
				$sDisplayRatings .= "	<option value='1'>1</option>";
				$sDisplayRatings .= "	<option value='2'>2</option>";
				$sDisplayRatings .= "	<option value='3'>3</option>";
				$sDisplayRatings .= "	<option value='4'>4</option>";
				$sDisplayRatings .= "	<option value='5'>5</option>";
				$sDisplayRatings .= "	<option value='6'>6</option>";
				$sDisplayRatings .= "	<option value='7'>7</option>";
				$sDisplayRatings .= "	<option value='8'>8</option>";
				$sDisplayRatings .= "	<option value='9'>9</option>";
				$sDisplayRatings .= "	<option value='10'>10</option>";
				$sDisplayRatings .= "</select>";
				$sDisplayRatings .= "<input type='submit' value='Rate Image'>";
				$sDisplayRatings .= "</form></center>";
			}
		}
		Return $sDisplayRatings;
	}
	//************************************************************************************


	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayAltViews()
	{
		Global $iAccountUnq;
		Global $iLoginAccountUnq;
		Global $aAltImage;
		Global $bHasAccount;
		Global $aXSize;
		Global $aYSize;
		Global $aImageDesc;
		Global $aAltTag;
		Global $iPrimaryG;
		Global $aImageSize;
		Global $iImageUnq;
		Global $sSiteURL;
	
		$sDisplayAltViews = "";
		$iNumColumns = 0;
		$sHowDisplay = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_ALTIMAGE_VIEW")));
		If ( $sHowDisplay == "" )
			$sHowDisplay = "RIGHTS";
		
		If ( Trim($iAccountUnq) == $iLoginAccountUnq ) {
			$bGalleryOwner = TRUE;
		}Else{
			$bGalleryOwner = FALSE;
		}
		
		If ( ( ( $aAltImage[0] != "" ) || ( $aAltImage[1] != "" ) || ( $aAltImage[2] != "" ) || ( $aAltImage[3] != "" ) ) && ( $sHowDisplay != "NONE" ) )
		{
			// at least one exists
			If ( $sHowDisplay == "RIGHTS" ) {
				If ( $bHasAccount ) {
					$sRegMsg = "<BR>--Upgrade to download--";
				}Else{
					$sRegMsg = "<br><a href='" . $sSiteURL . "/UserArea/NewAccounts/index.php' class='SmallNav1'>--Register to download--</a>";
				}
			}ElseIf ( $sHowDisplay == "MEMBERS" ) {
				$sRegMsg = "<br><a href='" . $sSiteURL . "/UserArea/NewAccounts/index.php' class='SmallNav1'>--Register to download--</a>";
			}

			// must declare these so in case not all four Alt images exist, the if/then statements below don't error-out
			$aText[0]		= "";
			$aText[1]		= "";
			$aText[2]		= "";
			$aText[3]		= "";
			$sSeperator2	= "";
			$sSeperator3	= "";
			$sSeperator4	= "";
			
			For ( $x = 0; $x <= 3; $x++ )
			{
				If ( $aAltImage[$x] != "" )
				{
					If ( $aImageDesc[$x] == "" )
						$aImageDesc[$x] = $aAltTag[$x];
					If ( $aImageDesc[$x] == "" )
						$aImageDesc[$x] = "Alternate " . ($x+1);
						
					$iPos		= strrpos($aAltImage[$x], ".");
					$sTemp		= strtoupper(substr($aAltImage[$x], $iPos + 1, strlen($aAltImage[$x])-($iPos + 1)));	// this is the file extension
					$aText[$x]	= "&#149;&nbsp;";
					If ( $sHowDisplay == "RIGHTS" ) {
						If ( ( ACCNT_ReturnRights("PHPJK_IG_ALT_" . ($x+1)) ) || ( $bGalleryOwner ) )
						{
							If ( ( $sTemp == "JPG") || ( $sTemp == "GIF" ) || ( $sTemp == "PNG" ) || ( $sTemp == "BMP" ) ) {
								$aText[$x] = $aText[$x] . "<a href='JavaScript:IG_Popup(" . $aXSize[$x] . ", " . $aYSize[$x] . ", " . $iImageUnq . ", " . ($x+2) . ");' class='SmallNavPage'>" . $aImageDesc[$x] . "</a><br><font size=-2>";
							}Else{
								$aText[$x] = $aText[$x] . "<img src='Images/MediaIcons/" . $sTemp . ".gif' alt = '" . $sTemp . " file'>&nbsp;<a href = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sImageNum=" . ($x+2) . "&sAccountUnq=" . $iAccountUnq . "&iGalleryUnq=" . $iPrimaryG . "&iImageUnq=" . $iImageUnq . "' class='SmallNavPage'>" . $aImageDesc[$x] . "</a><br><font size=-2>";
							}
						}Else{
							If ( $bHasAccount ) {
								$aText[$x] = $aText[$x] . "<font size=-2>" . $aImageDesc[$x] . $sRegMsg . "<br><font size=-2>";
							}Else{
								$aText[$x] = $aText[$x] . "<font size=-2>" . $aImageDesc[$x] . "<br><font size=-2>";
							}
						}
					}ElseIf ( $sHowDisplay == "MEMBERS" ) {
						If ( $bHasAccount ) {
							If ( ( $sTemp == "JPG") || ( $sTemp == "GIF" ) || ( $sTemp == "PNG" ) || ( $sTemp == "BMP" ) ) {
								$aText[$x] = $aText[$x] . "<a href='JavaScript:IG_Popup(" . $aXSize[$x] . ", " . $aYSize[$x] . ", " . $iImageUnq . ", " . ($x+2) . ");' class='SmallNavPage'>" . $aImageDesc[$x] . "</a><br><font size=-2>";
							}Else{
								$aText[$x] = $aText[$x] . "<img src='Images/MediaIcons/" . $sTemp . ".gif' alt = '" . $sTemp . " file'>&nbsp;<a href = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sImageNum=" . ($x+2) . "&sAccountUnq=" . $iAccountUnq . "&iGalleryUnq=" . $iPrimaryG . "&iImageUnq=" . $iImageUnq . "' class='SmallNavPage'>" . $aImageDesc[$x] . "</a><br><font size=-2>";
							}
						}Else{
							//$aText[$x] = $aText[$x] . "<font size=-2>" . $aImageDesc[$x] . $sRegMsg . "<br><font size=-2>";
						}
					}ElseIf ( $sHowDisplay == "ALL" ) {
						If ( ( $sTemp == "JPG") || ( $sTemp == "GIF" ) || ( $sTemp == "PNG" ) || ( $sTemp == "BMP" ) ) {
							$aText[$x] = $aText[$x] . "<a href='JavaScript:IG_Popup(" . $aXSize[$x] . ", " . $aYSize[$x] . ", " . $iImageUnq . ", " . ($x+2) . ");' class='SmallNavPage'>" . $aImageDesc[$x] . "</a><br><font size=-2>";
						}Else{
							$aText[$x] = $aText[$x] . "<img src='Images/MediaIcons/" . $sTemp . ".gif' alt = '" . $sTemp . " file'>&nbsp;<a href = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sImageNum=" . ($x+2) . "&sAccountUnq=" . $iAccountUnq . "&iGalleryUnq=" . $iPrimaryG . "&iImageUnq=" . $iImageUnq . "' class='SmallNavPage'>" . $aImageDesc[$x] . "</a><br><font size=-2>";
						}
					}

					If ( ( $aXSize[$x] != "" ) && ( $aYSize[$x] != "" ) ) {
						If ( ( $aXSize[$x] != "0" ) && ( $aYSize[$x] != "0" ) )
							$aText[$x] = $aText[$x] . "&nbsp;&nbsp;" . $aXSize[$x] . "x" . $aYSize[$x];
					}
					If ( $aImageSize[$x] != "" )
						$aText[$x] = $aText[$x] . "&nbsp;|&nbsp;" . number_format($aImageSize[$x]/1024,0) . " k";
					$iNumColumns++;
				}
			}
			
			If ( ( $aText[0] != "" ) && ( ( $aText[1] != "" ) || ( $aText[2] != "" ) || ( $aText[3] != "" ) ) ) {
				$sSeperator2 = "<br>";
				$iNumColumns++;
			}
			If ( ( $aText[1] != "" ) && ( ( $aText[2] != "" ) || ( $aText[3] != "" ) ) ) {
				$sSeperator3 = "<br>";
				$iNumColumns++;
			}
			If ( ( $aText[2] != "" ) && ( $aText[3] != "" ) ) {
				$sSeperator4 = "<br>";
				$iNumColumns++;
			}
			
			// write out the colored table and links
			$sDisplayAltViews .= "<table cellpadding = 0 cellspacing=0 border=0 width=100" . "%" . " class='TablePage'><tr><td>";
			$sDisplayAltViews .= $aText[0];
			$sDisplayAltViews .= $sSeperator2;
			$sDisplayAltViews .= $aText[1];
			$sDisplayAltViews .= $sSeperator3;
			$sDisplayAltViews .= $aText[2];
			$sDisplayAltViews .= $sSeperator4;
			$sDisplayAltViews .= $aText[3];
			If ( $sHowDisplay == "MEMBERS" )
				$sDisplayAltViews .= $sRegMsg;
			If ( ( $sHowDisplay == "RIGHTS" ) && ( !$bHasAccount ) )
				$sDisplayAltViews .= $sRegMsg;
			$sDisplayAltViews .= "</td></tr></table>";
		}

		Return $sDisplayAltViews;
	}
	//************************************************************************************


	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayKeywords()
	{
		Global $sKeywords;
		Global $sSiteURL;
		
		$sDisplayKeywords = "";
		If ( $sKeywords != "" )
		{
			$sDisplayKeywords .= "<font size=-2><b>Quick Search: </b></font>";
			$aKeywords = explode(",", $sKeywords);
			$iNumKeywords = Count($aKeywords);
			For ( $x = 0; $x < $iNumKeywords; $x++)
			{
				$sDisplayKeywords .= "<a href='" . $sSiteURL . "/Search/PrepareResults.php?sAction=1&sKeywords=" . URLEncode($aKeywords[$x]) . "&sReferer=" . htmlentities($_SERVER["SCRIPT_NAME"]) . "' class='SmallNavPage'>" . $aKeywords[$x] . "</a>";
				If ( $x < $iNumKeywords-1 )
					$sDisplayKeywords .= ", ";
			}
		}
		
		Return $sDisplayKeywords;
	}
	//************************************************************************************


	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayOtherLinks()
	{
		Global $aMiscLinks;
		Global $iNumLinks;
		
		//$aMiscLinks[0][$iCount]		= Trim($rsRow["URL"))
		//$aMiscLinks[1][$iCount]		= Trim($rsRow["OnSite"))
		//$aMiscLinks[2][$iCount]		= Trim($rsRow["Description"))

		$sDisplayOtherLinks = "";
		If ( $iNumLinks > 0 )
		{
			$sDisplayOtherLinks .= "<font size=-2><b>Additional Links of Interest: </b>";
			For ( $x = 0; $x < $iNumLinks; $x++)
			{
				If ( $aMiscLinks[1][$x] == "N" )
				{
					$aMiscLinks[0][$x] = str_replace("HTTP://", "", $aMiscLinks[0][$x]);	// can't do strtoupper() because some sites url's are case-sensitive (unix I think)
					$aMiscLinks[0][$x] = str_replace("http://", "", $aMiscLinks[0][$x]);
					$aMiscLinks[0][$x] = str_replace("Http://", "", $aMiscLinks[0][$x]);
					$aMiscLinks[0][$x] = "HTTP://" . $aMiscLinks[0][$x];
					$sDisplayOtherLinks .= "<a href='" . $aMiscLinks[0][$x] . "' class='SmallNavPage' target='_blank'><img src='" . G_STRUCTURE_DI("OffsiteLinks.gif", $GLOBALS["COLORBASED"]) . "' alt=' Offsite link ' border=0>" . $aMiscLinks[2][$x] . "</a>";
				}Else{
					$sDisplayOtherLinks .= "<a href='" . $aMiscLinks[0][$x] . "' class='SmallNavPage'>" . $aMiscLinks[2][$x] . "</a>";
				}
				If ( $x < $iNumLinks-1 )
					$sDisplayOtherLinks .= ", ";
			}
			$sDisplayOtherLinks .= "</font>";
		}
		
		Return $sDisplayOtherLinks;
	}
	//************************************************************************************


	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayCopyrights()
	{
		Global $aCopyLinks;
		Global $iNumCopyrights;
		
		//$aCopyLinks[0][$x]		= $rsRow["CopyUnq")
		//$aCopyLinks[1][$x]		= Trim($rsRow["GenericCopy"))
		//$aCopyLinks[2][$x]		= Trim($rsRow["C_URL"))
		//$aCopyLinks[3][$x]		= Trim($rsRow["Copyright"))
		//$aCopyLinks[4][$x]		= Trim($rsRow["Details"))
		
		$sDisplayCopyrights = "";
		If ( $iNumCopyrights > 0 )
		{
			$sDisplayCopyrights .= "<font size=-2><b>Image Copyright: </b>";
			For ( $x = 0; $x < $iNumCopyrights; $x++)
			{
				If ( $aCopyLinks[2][$x] == "" )
				{
					// there is no URL w/ the copyright
					If ( $aCopyLinks[1][$x] == "" )
					{
						// there is no generic copyright info
						If ( $aCopyLinks[4][$x] == "" )
						{
							// there are no details
							$sDisplayCopyrights .= $aCopyLinks[3][$x];
						}Else{
							$sDisplayCopyrights .= "<a href='JavaScript:CopyRightPopup(" . $aCopyLinks[0][$x] . ");' class='SmallNavPage'>" . $aCopyLinks[3][$x] . "</a>";
						}
					}Else{
						$sDisplayCopyrights .= $aCopyLinks[1][$x];
					}
				}Else{
					$sDisplayCopyrights .= "<a href='" . $aCopyLinks[2][$x] . "' class='SmallNavPage'>" . $aCopyLinks[3][$x] . "</a>";
				}
				If ( $x < $iNumCopyrights-1 )
					$sDisplayCopyrights .= ", ";
			}
			$sDisplayCopyrights .= "</font>";
		}
		
		Return $sDisplayCopyrights;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayCustomFields()
	{
		Global $iNumCustFields;
		Global $aCustFields;
		
		$sDisplayCustomFields = "";
		If ( $iNumCustFields > 0 )
		{
			For ( $x = 0; $x < $iNumCustFields; $x++)
			{
				If ( $aCustFields[1][$x] != "" )
				{
					$sDisplayCustomFields .= "<br><b>" . $aCustFields[0][$x] . "</b>";
					$sDisplayCustomFields .= "<br>" . $aCustFields[1][$x] . "<BR>";
				}
			}
		}
		
		Return $sDisplayCustomFields;
	}
	//************************************************************************************


	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayTimerDown()
	{
		Global $iImageUnq;
		Global $sSort;
		Global $iGalleryUnq;
		Global $sTimer;
		
		$sDisplayTimerDown = "";
		$sDisplayTimerDown .= "<form name='ChangeTimer' action='ImageDetail.php' class='PageForm'>";
		$sDisplayTimerDown .= "<input type='hidden' name='iImageUnq' value='" . $iImageUnq . "'>";
		$sDisplayTimerDown .= "<input type='hidden' name='sSort' value='" . $sSort . "'>";
		$sDisplayTimerDown .= "<input type='hidden' name='iGalleryUnq' value='" . $iGalleryUnq . "'>";
		$sDisplayTimerDown .= "<b>Slideshow: </b>";
		$sDisplayTimerDown .= "<select name = 'sTimer' onChange='document.ChangeTimer.submit();'>";
		$sDisplayTimerDown .= "	<option value=''> ";
		$sDisplayTimerDown .= "Off</option>";
		If ( $sTimer == "" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "	<option value=''>Stop</option>";
		$sDisplayTimerDown .= "	<option value='5000'>";
		If ( $sTimer == "5000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "5 sec</option>";
		$sDisplayTimerDown .= "	<option value='10000'>";
		If ( $sTimer == "10000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "10 sec</option>";
		$sDisplayTimerDown .= "	<option value='15000'>";
		If ( $sTimer == "15000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "15 sec</option>";
		$sDisplayTimerDown .= "	<option value='20000'>";
		If ( $sTimer == "20000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "20 sec</option>";
		$sDisplayTimerDown .= "	<option value='25000'>";
		If ( $sTimer == "25000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "25 sec</option>";
		$sDisplayTimerDown .= "	<option value='30000'>";
		If ( $sTimer == "30000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "30 sec</option>";
		$sDisplayTimerDown .= "	<option value='35000'>";
		If ( $sTimer == "35000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "35 sec</option>";
		$sDisplayTimerDown .= "	<option value='40000'>";
		If ( $sTimer == "40000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "40 sec</option>";
		$sDisplayTimerDown .= "	<option value='45000'>";
		If ( $sTimer == "45000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "45 sec</option>";
		$sDisplayTimerDown .= "	<option value='50000'>";
		If ( $sTimer == "50000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "50 sec</option>";
		$sDisplayTimerDown .= "	<option value='55000'>";
		If ( $sTimer == "55000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "55 sec</option>";
		$sDisplayTimerDown .= "	<option value='60000'>";
		If ( $sTimer == "60000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "1 min</option>";
		$sDisplayTimerDown .= "	<option value='120000'>";
		If ( $sTimer == "120000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "2 min</option>";
		$sDisplayTimerDown .= "	<option value='180000'>";
		If ( $sTimer == "180000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "3 min</option>";
		$sDisplayTimerDown .= "	<option value='270000'>";
		If ( $sTimer == "270000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "4 min</option>";
		$sDisplayTimerDown .= "	<option value='360000'>";
		If ( $sTimer == "360000" ) $sDisplayTimerDown .= "selected";
		$sDisplayTimerDown .= "5 min</option>";

		$sDisplayTimerDown .= "</select>";
		$sDisplayTimerDown .= "</form>";

		Return $sDisplayTimerDown;
	}
	//************************************************************************************


	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayCategoryDropDown()
	{
		Global $iCategoryUnq;

		$sDisplayCategoryDropDown = "";
		$sDisplayCategoryDropDown .= "<form name='iCategoryUnq' action='G_Display.php' class='PageForm'>";
		$sDisplayCategoryDropDown .= "<b>Category:</b>";
		$sDisplayCategoryDropDown .= "<select name = 'iCategoryUnq' onChange='document.iCategoryUnq.submit();'>";

			$sQuery			= "SELECT * FROM IGCategories (NOLOCK) WHERE Parent = 0 ORDER BY Position";
			$sQuery			= "SELECT * FROM IGCategories (NOLOCK) ORDER BY Position";
			$rsRecordSet	= DB_Query($sQuery);
			If ( DB_NumRows($rsRecordSet) > 0 )
			{
				$sDisplayCategoryDropDown .= "<option value='" . $iCategoryUnq . "'>Select Category</option>";
				If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) )
				{
					If ( $iCategoryUnq == "-1" ) {
						$sDisplayCategoryDropDown .= "<option value='-1' selected>My Galleries</option>";
					}Else{
						$sDisplayCategoryDropDown .= "<option value='-1'>My Galleries</option>";
					}
				}
				If ( $iCategoryUnq == "0" ) {
					$sDisplayCategoryDropDown .= "<option value='0' selected>Galleries not in a category</option>";
				}Else{
					$sDisplayCategoryDropDown .= "<option value='0'>Galleries not in a category</option>";
				}
				While ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$sName = htmlentities($rsRow["Name"]);
					If ( $iCategoryUnq == Trim($rsRow["CategoryUnq"]) ) {
						$sDisplayCategoryDropDown .= "<option value='" . $rsRow["CategoryUnq"] . "' Selected>" . $sName . "</option>";
					}Else{
						$sDisplayCategoryDropDown .= "<option value='" . $rsRow["CategoryUnq"] . "'>" . $sName . "</option>";
					}
				}
			}Else{
				$sDisplayCategoryDropDown .= "<option value='0'>Galleries not in a category</option>";
			}

		$sDisplayCategoryDropDown .= "</select>";
		$sDisplayCategoryDropDown .= "</form>";
		
		Return $sDisplayCategoryDropDown;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayGalleriesDropDown()
	{
		Global $iCategoryUnq;
		Global $iGalleryUnq;
		
		$sDisplayGalleriesDropDown = "";
		$sDisplayGalleriesDropDown .= "<form name='ThumbnailView' action='ThumbnailView.php' class='PageForm'>";
		$sDisplayGalleriesDropDown .= "<b>Galleries: </b>";
		$sDisplayGalleriesDropDown .= "<select name = 'iGalleryUnq' onChange='document.ThumbnailView.submit();'>";
		$sDisplayGalleriesDropDown .= "<optionvalue='" . $iGalleryUnq . "'>Select Gallery</option>";
		$sQuery			= "SELECT DISTINCT G.GalleryUnq, G.Name FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq ORDER BY G.Name";
		$rsRecordSet	= DB_Query($sQuery);
		While ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$sName = htmlentities($rsRow["Name"]);
			If ( $iGalleryUnq == $rsRow["GalleryUnq"] ) {
				$sDisplayGalleriesDropDown .= "<option value='" . $rsRow["GalleryUnq"] . "' Selected>" . $sName . "</option>";
			}Else{
				$sDisplayGalleriesDropDown .= "<option value='" . $rsRow["GalleryUnq"] . "'>" . $sName . "</option>";
			}
		}
		$sDisplayGalleriesDropDown .= "</select>";
		$sDisplayGalleriesDropDown .= "</form>";
		
		Return $sDisplayGalleriesDropDown;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function GetTitle(&$sHeadTitle, $iParentUnq, $iGalleryUnq, $iImageUnq)
	{
		
		If ( $iParentUnq == "" )
			$iParentUnq = 0;

		// Now get the actual data for the category
		$sQuery			= "SELECT Name, Parent FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iParentUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			if ( $sHeadTitle == "" ) 
			{
				If ( $iGalleryUnq != -1 ) {
					$sHeadTitle = $rsRow["Name"] . "--";
				}Else{
					$sHeadTitle = $rsRow["Name"];
				}
			}Else{
				$sHeadTitle = $rsRow["Name"] . "--" . $sHeadTitle;
			}
			GetTitle($sHeadTitle, $rsRow["Parent"], $iGalleryUnq, $iImageUnq);
		}Else{
			// we are done getting the categories, so get and add the gallery and image (if there is one)
			GetTitleGAndI($sHeadTitle, $iGalleryUnq, $iImageUnq);
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function GetTitleGAndI(&$sHeadTitle, $iGalleryUnq, $iImageUnq)
	{		
		$sQuery			= "SELECT Name FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
		$rsRecordSet2	= DB_Query($sQuery);
		If ( $rsRow2 = DB_Fetch($rsRecordSet2) )
		{
			If ( $iImageUnq != -1 )
			{
				// we must add the gallery name as a link to the gallery thumbnail page
				$sHeadTitle = $sHeadTitle . Trim($rsRow2["Name"]) . "--";
				// now add the image name
				$sQuery			= "SELECT Image, Title FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
				$rsRecordSet3	= DB_Query($sQuery);
				If ( $rsRow3 = DB_Fetch($rsRecordSet3) )
				{
					If ( Trim($rsRow3["Title"]) == "" ) {
						$sHeadTitle = $sHeadTitle . Trim($rsRow3["Image"]);
					}Else{
						$sHeadTitle = $sHeadTitle . Trim($rsRow3["Title"]);
					}
				}
			}Else{
				$sHeadTitle = $sHeadTitle . Trim($rsRow2["Name"]);
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Open the database w/ a connection string.										*
	//*																					*
	//************************************************************************************
	Function OpenDB_CS($sSQLLogin, $sSQLPassword, $sSQLServer, $sDBName)
	{
		Global $QueryConnection;
		
		$QueryConnection = DB_DBConnect($sSQLServer, $sSQLLogin, $sSQLPassword);
		If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
			mssql_select_db($sDBName, $QueryConnection)
				or
				Die ("Could not connect to the database: ".mssql_get_last_message());
		}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
			mysql_select_db($sDBName, $QueryConnection)
				or
				Die ("Could not connect to the database: ".mysql_error());
		}
	} 
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Open the database w/ a DSN.														*
	//*																					*
	//************************************************************************************
	Function OpenDB_DSN($sDSNName, $sDSNLogin, $sDSNPassword)
	{
		Global $sUseDB;
		Global $QueryConnection;
		
		If ( $sUseDB == "MSSQL" ){
			$QueryConnection = odbc_connect( $sDSNName, $sDSNLogin, $sDSNPassword )
				or
				Die ("Could not connect to DSN: ".odbc_errormsg());
		}
	} 
	//************************************************************************************
?>
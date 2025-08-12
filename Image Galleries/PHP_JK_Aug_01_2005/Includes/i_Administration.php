<?php 
	Require("Config/i_Administration.php");
	//************************************************************************************
	//*																					*
	//*	Checks to see if the user passed in is the owner of the gallery passed in.		*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_IsGalleryOwner($iAccountUnq, $iGalleryUnq, $iImageUnq)
	{
		$bIsOwner = False;
		
		If ( ( $iGalleryUnq != "" ) && ( $iAccountUnq != "" ) ) {
			$sQuery			= "SELECT GalleryUnq FROM Galleries (NOLOCK) WHERE AccountUnq = " . $iAccountUnq . " AND GalleryUnq = " . $iGalleryUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
				$bIsOwner = TRUE;
			
			If ( $iImageUnq != "" ) {
				// check to make sure that this imageunq is actually in this gallery
				//	( DO NOT check for Accountunq because an admin can upload to galleries 
				//	they don't own, but the gallery owner should still be able to admin those 
				//	images because they are in their gallery -- even though another person uploaded them)
				If ( $bIsOwner ) {
					// they have to be the gallery owner even before bothering to check if the image is in that gallery
					$sQuery			= "SELECT GalleryUnq FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq . " AND ImageUnq = " . $iImageUnq;
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) ){
						$bIsOwner = TRUE;
					}Else{
						// since the image isn't in the gallery that they own, return false -- the user might be trying to spoof by saying they are editing a gallery they own, but an image they don't own
						$bIsOwner = FALSE;
					}
					
				}
			}
		}
		Return $bIsOwner;		
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the AccountUnq of the gallery - used to know where to save files, etc..	*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_GetGalleryOwner($iGalleryUnq)
	{		
		If ( $iGalleryUnq != "" ) {
			$sQuery			= "SELECT AccountUnq FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
				Return $rsRow["AccountUnq"];
		}
		Return 0;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns TRUE if the current user has access to the images in the gallery.		*
	//*		Also returns TRUE if the user owns the gallery or has PHP_JK_IG_ADMIN_ALL 		*
	//*		rights.																		*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_AccessLocked($iGalleryUnq, $iGalleryOwner)
	{
		Global $iLoginAccountUnq;
		
		$bHasAccess = False;
	
		If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( $iGalleryOwner == $iLoginAccountUnq ) ) {
			$bHasAccess = TRUE;
		}Else{
			// check to see if the current user is in the list of accounts
			If ( ! is_numeric($iGalleryUnq) ) 	// some wankers were changing the querystring to put 8_8 as the galleryunq...why??
				$iGalleryUnq = "-1";

			$sQuery			= "SELECT * FROM PrivateAccounts (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq . " AND AccountUnq = " . $iLoginAccountUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) ){
				$bHasAccess = TRUE;		// yes they can access this locked gallery because the current users accountunq is in the list for this gallery
			}Else{
				// not found anywhere for the individual so fall back on the global setting - do it this way because
				//	if we choose the global setting first, we might miss the fact that the individual may have more
				//	access than the global access!
				If ( ! $bHasAccess ) {
					$sQuery			= "SELECT * FROM PrivateAccounts (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq . " AND AccountUnq = -1";
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) )
						$bHasAccess = TRUE;		// yes they can access this locked gallery because it's AccountUnq is = -1
				}
			}
		}
		Return $bHasAccess;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the number of images that the user can still download for today, or		*
	//*		-1 if they can download an unlimited number.								*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_DailyDLLeft($iAccountUnq)
	{
		Global $bHasAccount;
		
		$iLeft		= 0;
		$sViewLvl	= "PRIVATE";
				
		If ( $bHasAccount ) {
			/* the user is a member, check the PHP_JK_IG_NUM_DL_DAY and PHP_JK_IG_NUM_HAS_DL
				--num downloads per day
					--PHP_JK_IG_NUM_DL_DAY
				--num files user has downloaded today
					--PHP_JK_IG_NUM_HAS_DL
				--last date/time user downloaded a file
					--PHP_JK_IG_DL_LASTDATE*/
			$iNumDlDay = Trim(ACCNT_ReturnADV("PHPJK_IG_NUM_DL_DAY", "V", $iAccountUnq, 7, $sViewLvl));
			If ( $iNumDlDay == "" )
			{
				$iNumDlDay = Trim(DOMAIN_Conf("IMAGEGALLERY_INITIAL_NUMDOWNLOADS"));
				ACCNT_WriteADV("PHPJK_IG_NUM_DL_DAY", $iNumDlDay, "V", $iAccountUnq, $sViewLvl);
			}
			
			If ( $iNumDlDay == -1 ) {
				Return -1;
			}Else{
				$iNumDlToday = Trim(ACCNT_ReturnADV("PHPJK_IG_NUM_HAS_DL", "V", $iAccountUnq, 7, $sViewLvl));
				$dLastDlDate = Trim(ACCNT_ReturnADV("PHPJK_IG_DL_LASTDATE", "V", $iAccountUnq, 7, $sViewLvl));
				If ( $iNumDlToday == "" )
					$iNumDlToday = 0;
				If ( $dLastDlDate == "" )
					$dLastDlDate = time(); //CDate("1/1/80")

				If ( DateDiff("d", $dLastDlDate, time()) >= 1 ) {
					// they have not downloaded a file within a day
					Return $iNumDlDay;
				}Else{
					$iLeft = $iNumDlDay - $iNumDlToday;
					If ( $iLeft == -1 ) {
						// this is because we've already checked to see if nonmmbrs can dl unlimited - they cannot, so the difference of the ttl downloadable
						//	and the num they have downloaded today just happened to be -1
						Return 0;
					}
				}
			}
		}Else{
			// the user is not a member, check the IMAGEGALLERY_NONMMBR_NUMDOWNLOADS conf
			//	variable compared to their cookie
			
			// first check to see if the user_agent is a robot. if so, let them dl any number -- don't track it
			$sTemp = Trim(strtoupper("FunnelWeb\nGooglebot\nwired-digital-newsbot\nInfoseek\nJCrawler/0.2\nLycos\nMOMspider\nMSNBOT\nNomad-V2.x\nNorthStar\nScooter\nOpen Text\nSlurp/2.0\nMozilla\nTITAN/0.1\nValkyrie/1.0 libwww-perl/0.40\nWebReaper [webreaper@otway.com]\nwhatUseek_winona/3.0"));
			If ( ( strpos($sTemp, strtoupper($_SERVER["HTTP_USER_AGENT"])) > 0 ) && ( $sTemp != "" ) ) {
				Return -1;
			}Else{
				$iNonMmbrNumDl = Trim(DOMAIN_Conf("IMAGEGALLERY_NONMMBR_NUMDOWNLOADS"));
				If ( $iNonMmbrNumDl == -1 ) {
					Return -1;
				}Else{
					$iCookieValue = "";
					If ( isset($_COOKIE["PHP_JK_IG_NUM_HAS_DL1"]) )
						$iCookieValue = Trim($_COOKIE["PHP_JK_IG_NUM_HAS_DL1"]);
					If ( $iCookieValue == "" ) {
						// their cookie is blank so they have not downloaded any images yet
						Return $iNonMmbrNumDl;
					}Else{
						$iLeft = $iNonMmbrNumDl - $iCookieValue;
						If ( $iLeft == -1 ) {
							// this is because we've already checked to see if nonmmbrs can dl unlimited - they cannot, so the difference of the cookie and
							//	conf var just happened to be -1
							Return 0;
						}Else{
							Return $iLeft;
						}
					}
				}
			}
		}
		Return 0;
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*	Increments the number of images the user has downloaded for today.				*
	//*	If the user is not a member, sets and increments the cookie.					*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_IncrementDailyDL($iAccountUnq)
	{	
		Global $bHasAccount;
		
		$sViewLvl = "PRIVATE";
		
		If ( $bHasAccount ) {
			// they are a member, increment PHP_JK_IG_NUM_HAS_DL ADV and set the PHP_JK_IG_DL_LASTDATE to now
			$dLastDL = Trim(ACCNT_ReturnADV("PHPJK_IG_DL_LASTDATE", "V", $iAccountUnq, 7, $sViewLvl));

			If ( $dLastDL == "" ) {
				$iNumDL = "1";
			}Else{
				If ( DateDiff("d", $dLastDL, time()) > 0 )
				{
					ACCNT_WriteADV("PHPJK_IG_DL_LASTDATE", date("r", time()), "V", $iAccountUnq, $sViewLvl);
					$iNumDL = "1";
				}Else{
					$iNumDL = Trim(ACCNT_ReturnADV("PHPJK_IG_NUM_HAS_DL", "V", $iAccountUnq, 7, $sViewLvl)) + 1;
				}
			}
			ACCNT_WriteADV("PHPJK_IG_NUM_HAS_DL", $iNumDL, "V", $iAccountUnq, $sViewLvl);
			ACCNT_WriteADV("PHPJK_IG_DL_LASTDATE", date("r", time()), "V", $iAccountUnq, $sViewLvl);
		}Else{
			// they are not a member so create (if not already there) a cookie and increment it by one
			$sTemp = "";
			If ( isset($_COOKIE["PHP_JK_IG_NUM_HAS_DL1"]) )
				$sTemp = Trim($_COOKIE["PHP_JK_IG_NUM_HAS_DL1"]);
			If ( $sTemp == "" ) {
				$iNumDL = 1;
			}Else{
				$iNumDL = $sTemp + 1;
			}
			// expire first thing tomorrow
			setcookie("PHP_JK_IG_NUM_HAS_DL1", $iNumDL, (time()+43200), "/", $_SERVER["SERVER_NAME"], 0);
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the valid file extensions for the accountunq passed in.					*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_ValidFileExtensions($iAccountUnq)
	{
		$sViewLvl = "OVERRIDE";
		$sTemp = Trim(ACCNT_ReturnADV("PHPJK_IG_UPLOADTYPE", "V", $iAccountUnq, 7, $sViewLvl));
		
		If ( $sTemp == "" ) {
			$sTemp = Trim(DOMAIN_Conf("IMAGEGALLERY_INITIAL_UPLOADTYPE"));
			$sViewLvl = "PRIVATE";
			ACCNT_WriteADV("PHPJK_IG_UPLOADTYPE", $sTemp, "V", $iAccountUnq, $sViewLvl);
		}
		Return $sTemp;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the max file size in bytes or -1 for unlimited for the accountunq 		*
	//*		passed in.																	*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_MaxFileSize($iAccountUnq)
	{
		$sViewLvl = "OVERRIDE";
		$sTemp = Trim(ACCNT_ReturnADV("PHPJK_IG_UPLOADBYTES", "V", $iAccountUnq, 7, $sViewLvl));
		
		If ( $sTemp == "" ) {
			$sTemp = Trim(DOMAIN_Conf("IMAGEGALLERY_INITIAL_UPLOADBYTES"));
			$sViewLvl = "PRIVATE";
			ACCNT_WriteADV("PHPJK_IG_UPLOADBYTES", $sTemp, "V", $iAccountUnq, $sViewLvl);
		}
		
		If ( $sTemp == "" )
			Return  0;
		
		Return $sTemp;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the number of images that the user can still upload, or					*
	//*		-1 if they can upload an unlimited number.									*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_ULLeft($iAccountUnq)
	{
		$sTemp = 0;
		$sViewLvl = "OVERRIDE";
		
		/*--num files user can upload
			--PHP_JK_IG_NUMALLOWED
		--num files user has uploaded
			--PHP_JK_IG_NUMUPLOADED*/
		$iULAllowed = Trim(ACCNT_ReturnADV("PHPJK_IG_NUMALLOWED", "V", $iAccountUnq, 7, $sViewLvl));
		If ( $iULAllowed == "" ) {
			$sTemp = Trim(DOMAIN_Conf("IMAGEGALLERY_INITIAL_NUMUPLOAD"));
			ACCNT_WriteADV("PHPJK_IG_NUMALLOWED", $sTemp, "V", $iAccountUnq, "PRIVATE");
		}Else{
			If ( $iULAllowed == -1 ) {
				Return -1;
			}Else{
				$iULAlready = Trim(ACCNT_ReturnADV("PHPJK_IG_NUMUPLOADED", "V", $iAccountUnq, 7, $sViewLvl));
				If ( $iULAlready == "" )
					$iULAlready = 0;
				$sTemp = $iULAllowed - $iULAlready;
				// incase the difference is -1
				If ( $sTemp == -1 )
					Return 0;
			}
		}
		Return $sTemp;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Increments the number of images the user has uploaded.							*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_IncrementUL($iAccountUnq)
	{	
		$sViewLvl = "OVERRIDE";
			
		/*--num files user has uploaded
			--PHP_JK_IG_NUMUPLOADED*/
		$iNumUL = Trim(ACCNT_ReturnADV("PHPJK_IG_NUMUPLOADED", "V", $iAccountUnq, 7, $sViewLvl));
		If ( $iNumUL == "" )
			$iNumUL = 1;
		
		$sViewLvl = "PRIVATE";	
		ACCNT_WriteADV("PHPJK_IG_NUMUPLOADED", $iNumUL, "V", $iAccountUnq, $sViewLvl);
		ACCNT_WriteADV("PHPJK_IG_NUMUPLOAD_LASTDATE", date("r", Time()), "V", $iAccountUnq, $sViewLvl);
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the number of bytes left in this users HD storage or -1 for unlimited 	*
	//*		for the accountunq passed in.												*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_HDSpaceLeft($iAccountUnq)
	{	
		/*--hd space the user has
			--PHP_JK_IG_TTLUPLOADABLE
		--hd space user has used
			--PHP_JK_IG_TTLUPLOADED*/
		$sTemp = 0;
		$sViewLvl = "OVERRIDE";
		
		$iTtlHD = Trim(ACCNT_ReturnADV("PHPJK_IG_TTLUPLOADABLE", "V", $iAccountUnq, 7, $sViewLvl));
		If ( $iTtlHD == "" ) {
			$sTemp = Trim(DOMAIN_Conf("IMAGEGALLERY_INITIAL_HD_SPACE"));
			ACCNT_WriteADV("PHPJK_IG_TTLUPLOADABLE", $sTemp, "V", $iAccountUnq, "PRIVATE");
		}Else{
			If ( $iTtlHD == -1 ) {
				Return -1;
			}Else{
				$iTtlUsed = Trim(ACCNT_ReturnADV("PHPJK_IG_TTLUPLOADED", "V", $iAccountUnq, 7, $sViewLvl));
				If ( $iTtlUsed == "" ) {
					Return $iTtlHD;
				}Else{
					$sTemp = $iTtlHD - $iTtlUsed;
					If ( $sTemp == -1 ) 	// we've already checked for unlimited, so this just happens that the difference is -1
						Return 0;
				}
			}
		}
		Return $sTemp;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Increments the users harddrive space left.										*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_IncrementHDSpaceUsed($iAccountUnq, $iBytes)
	{
		$sViewLvl = "OVERRIDE";

		$iTtlUsed = Trim(ACCNT_ReturnADV("PHPJK_IG_TTLUPLOADED", "V", $iAccountUnq, 7, $sViewLvl));

		If ( $iTtlUsed == "" )
			$iTtlUsed = 0;

		$iTtlUsed	= $iTtlUsed + $iBytes;

		If ( $iAccountUnq == "" )
		{
			DOMAIN_Message("Missing AccountUnq in G_ADMINISTRATION_IncrementHDSpaceUsed.", "ERROR");
		}Else{
			ACCNT_WriteADV("PHPJK_IG_TTLUPLOADED", $iTtlUsed, "V", $iAccountUnq, "PRIVATE");
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Checks to see if ASPImage is installed or not.									*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_ASPImageInstalled()
	{
		Global $ASPIMAGE;
		Global $GFL;
		
		$sGenerator = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_GENERATOR")));
		$sTemp = 0;
		
		If ( $sGenerator == "ASPIMAGE" ) {
			$sTemp = 0;		// no longer supporting ASPImage -- doesn't work w/ PHP
		}ElseIf ( $sGenerator == "GFL" ) {
			$sTemp = $GFL;
		}
		
		$iThumbComponent = $sTemp;	// set the global variable so other functions can access it
		Return $sTemp;		
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the number of bytes left in this users HD storage or -1 for unlimited 	*
	//*		for the accountunq passed in.												*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_GalleriesLeft($iAccountUnq)
	{
		$sTemp = 0;
		$sViewLvl = "OVERRIDE";
		$iGalleriesAllowed = Trim(ACCNT_ReturnADV("PHPJK_IG_NUM_GALLERIES", "V", $iAccountUnq, 7, $sViewLvl));
		$sQuery			= "SELECT Count(*) FROM Galleries G (NOLOCK) WHERE AccountUnq = " . $iAccountUnq;
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) ){
			$iGalleriesAlready = $rsRow[0];
		}Else{
			$iGalleriesAlready = 0;
		}

		If ( $iGalleriesAllowed == "" ) {
			$iGalleriesAllowed = Trim(DOMAIN_Conf("IMAGEGALLERY_INITIAL_MAX_NUM_GALLERIES"));
			ACCNT_WriteADV("PHPJK_IG_NUM_GALLERIES", $iGalleriesAllowed, "V", $iAccountUnq, "PRIVATE");
			If ( $iGalleriesAllowed == "" )
				$iGalleriesAllowed = 0;
		}
		
		If ( $iGalleriesAllowed == -1 ) {
			Return -1;
		}Else{
			$sTemp = $iGalleriesAllowed - $iGalleriesAlready;
			If ( $sTemp == -1 )		// in case the difference is -1
				Return 0;
		}
		Return $sTemp;
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_SendSubscriptionEmail($iGalleryUnq)
	{	
		Global $CONF_NewGalImages;
		Global $CONF_NewCatImages;
		
		$sViewLvl = "OVERRIDE";
		
		// don't send anything about a specific image since the admin could have uploaded many via the bulk upload.
		// must figure out the CategoryUnq from the GalleryUnq and see first if the user is subscribed to the gallery, then to the category
		If ( ( $iGalleryUnq != "" ) && ( DOMAIN_Has_RemoteHost() ) ) {			
			$sQuery			= "SELECT S.AccountUnq, G.Name, G.CategoryUnq FROM IG_Subscriptions S (NOLOCK), Galleries G (NOLOCK) WHERE S.GalleryUnq = " . $iGalleryUnq . " AND S.SentEmail = 'N' AND G.GalleryUnq = " . $iGalleryUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( DB_NumRows($rsRecordSet) > 0 )
			{
				// send emails to people who are subscribed to the gallery
				While ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$iAccountUnq = $rsRow["AccountUnq"];
					$sFullLetter = $CONF_NewGalImages;
					$sFullLetter = str_replace($sFullLetter, "1:", $rsRow["Name"]);
					$sFullLetter = str_replace($sFullLetter, "2:", DOMAIN_Conf("IMAGEGALLERY_SITEURL"));
					$sFullLetter = str_replace($sFullLetter, "3:", $iGalleryUnq);
					$sFullLetter = str_replace($sFullLetter, "4:", $rsRow["CategoryUnq"]);
					$sName		= Trim(ACCNT_ReturnADV("PHPJK_FirstName", "V", $iAccountUnq, 0, $sViewLvl)) . " " . Trim(ACCNT_ReturnADV("PHPJK_LastName", "V", $iAccountUnq, 7, $sViewLvl));
					$sEmail		= Trim(ACCNT_ReturnADV("PHPJK_EmailAddress", "V", $iAccountUnq, 0, $sViewLvl));
					If ( $sEmail != "" )
						$sEmailResponse = DOMAIN_Send_EMail($sFullLetter, DOMAIN_Conf("IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_NAME"), DOMAIN_Conf("IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_EMAIL"), $sName, $sEmail, $_SERVER["SERVER_NAME"] . " Gallery New Image Notification", FALSE);

					DB_Update ("UPDATE IG_Subscriptions SET SentEmail = 'Y' WHERE AccountUnq = " . $iAccountUnq);
				}
				
				// now sent emails to people who are subscribed to the category that the gallery is in, if they didn't already get it from the gallery
				$sQuery			= "SELECT CategoryUnq FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
				$rsRecordSet	= DB_Query($sQuery);
				If ( DB_NumRows($rsRecordSet) > 0 )
					$iCategoryUnq = $rsRow["CategoryUnq"];
				
				If ( $iCategoryUnq != "" ) 
				{
					$sQuery			= "SELECT AccountUnq FROM IG_Subscriptions (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq . " AND GalleryUnq != " . $iGalleryUnq . " AND SentEmail = 'N'";
					$rsRecordSet	= DB_Query($sQuery);
					While ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						$iAccountUnq = $rsRow["AccountUnq"];
						$sFullLetter = $CONF_NewCatImages;
						$sFullLetter = str_replace($sFullLetter, "1:", G_ADMINISTRATION_GetCatName($iCategoryUnq));
						$sFullLetter = str_replace($sFullLetter, "2:", DOMAIN_Conf("IMAGEGALLERY_SITEURL"));
						$sFullLetter = str_replace($sFullLetter, "3:", $iCategoryUnq);
						$sName		= Trim(ACCNT_ReturnADV("PHPJK_FirstName", "V", $iAccountUnq, 0, $sViewLvl)) . " " . Trim(ACCNT_ReturnADV("PHPJK_LastName", "V", $iAccountUnq, 7, $sViewLvl));
						$sEmail		= Trim(ACCNT_ReturnADV("PHPJK_EmailAddress", "V", $iAccountUnq, 0, $sViewLvl));
						If ( $sEmail != "" )
							$sEmailResponse = DOMAIN_Send_EMail($sFullLetter, DOMAIN_Conf("IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_NAME"), DOMAIN_Conf("IMAGEGALLERY_SENDSUBSCRIPTIONS_FROM_EMAIL"), $sName, $sEmail, $_SERVER["SERVER_NAME"] . " Category New Image Notification", FALSE);
						
						DB_Update ("UPDATE IG_Subscriptions SET SentEmail = 'Y' WHERE AccountUnq = " . $iAccountUnq);
					}
				}
			}
		}
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_HasGallerySubscription($iAccountUnq, $iGalleryUnq)
	{
		If ( $iGalleryUnq != "" ) {
			$sQuery			= "SELECT AccountUnq FROM IG_Subscriptions (NOLOCK) WHERE AccountUnq = " . $iAccountUnq . " AND GalleryUnq = " . $iGalleryUnq;
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) )
				Return TRUE;
		}
		Return False;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_HasCategorySubscription($iAccountUnq, $iCategoryUnq)
	{
		If ( $iCategoryUnq != "" ) {
			$sQuery			= "SELECT AccountUnq FROM IG_Subscriptions (NOLOCK) WHERE AccountUnq = " . $iAccountUnq . " AND CategoryUnq = " . $iCategoryUnq;
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) )
				Return TRUE;
		}
		Return False;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_ResetSubscriptionEmail($iAccountUnq)
	{
		DB_Update ("UPDATE IG_Subscriptions SET SentEmail = 'N' WHERE AccountUnq = " . $iAccountUnq);
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the full path of the gallery owner of the primary gallery and domain of *
	//*		the ImageUnq passed in (doesn't have the image name on it so this can be	*
	//*		used for either images or thumbnails).										*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_GetImageDir($iImageUnq)
	{
		If ( $iImageUnq != "" ) {
			$sQuery			= "SELECT G.GalleryUnq, G.AccountUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IG.ImageUnq = " . $iImageUnq . " AND IG.PrimaryG = G.GalleryUnq AND IG.GalleryUnq = IG.PrimaryG";
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) )
				Return "\\" . DOMAIN_Conf("IG") . "\\" . $rsRow["AccountUnq"] . "\\" . $rsRow["GalleryUnq"] . "\\";
		}
		Return "";
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the name of the gallery.												*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_GetGalleryName($iGalleryUnq)
	{
		If ( $iGalleryUnq != "" ) {
			$sQuery			= "SELECT Name FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) )
				Return Trim($rsRow["Name"]);
		}
		Return "";
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Returns the name of the category.												*
	//*																					*
	//************************************************************************************
	Function G_ADMINISTRATION_GetCatName($iCategoryUnq)
	{
		If ( $iCategoryUnq != "" ) {
			$sQuery			= "SELECT Name FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iCategoryUnq;
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) )
				Return Trim($rsRow["Name"]);
		}
		Return "";
	}
	//************************************************************************************
?>
<?php
	Require("Includes/i_Includes.php");

	If ( (Trim(Request("iGalleryUnq")) == "") && (Trim(Request("iCategoryUnq")) == "") ) {
		?>
		<script language='JavaScript1.2' type='text/javascript'>
		
			document.location = "index.php";
		
		</script>
		<?php
		ob_flush();
		exit;
	}Else{
		$iGalleryUnq	= Trim(Request("iGalleryUnq"));
		$iCategoryUnq	= Trim(Request("iCategoryUnq"));
		If ( $iCategoryUnq == "" ) {
			$iCategoryUnq = "0";
		}Else{
			If ( $iGalleryUnq == "" )
			{
				If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_ALLOW_ALL_DISPLAY"))) == "YES" ) {
					$bDispAllImages = TRUE;
				}Else{
					// should't be here
					?>
					<script language='JavaScript1.2' type='text/javascript'>
					
						document.location = "index.php";
					
					</script>
					<?php
					ob_flush();
					exit;
				}
			}
		}
	
		Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
		DeleteOldAllViewResults();
		Main();
		Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	}
	
	
	//************************************************************************************
	//*																					*
	//*	Displays and Edits images within a gallery.										*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iNumColumns;
		Global $sSort;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iNumPerPage;
		Global $bUseAlpha;
		Global $bAccessLocked;
		Global $iThumbWidth;
		Global $iSearchID;
		Global $bDispAllImages;
		Global $bAdmin;
		Global $sNewDays;
		Global $sAccountUnq;
		Global $sGalleryName;
		Global $sGalleryDesc;
		Global $iCategoryUnq;
		Global $iLoginAccountUnq;
		Global $aVariables;
		Global $aValues;
		Global $sBreadCrumb;
		Global $iGalleryUnq;
		Global $iColorScheme;
		Global $iTableWidth;
		Global $bHasAccount;
		Global $sTemplates;
		Global $sSiteURL;
		
		$iNumColumns		= DOMAIN_Conf("IMAGEGALLERY_THUMBNAILVIEW_NUMCOLUMNS");
		$sSort				= Trim(Request("sSort"));
		$iTtlNumItems		= Request("iTtlNumItems");
		$bUseAlpha			= Trim(strtoupper(DOMAIN_Conf("IMAGEGALLERY_USEALPHA")));
		$iNumPerPage		= Trim(Request("iNumPerPage"));
		$bAccessLocked		= G_ADMINISTRATION_AccessLocked($iGalleryUnq, $sAccountUnq);
		$iThumbWidth		= DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH");
		$iSearchID			= Trim(Request("iSearchID"));
		$bDispAllImages		= FALSE;
		$bAdmin				= ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL");
		$sNewDays			= Trim(DOMAIN_Conf("IMAGEGALLERY_NEWIMAGE_DAYS"));

		If ( $iNumColumns == "" )
			$iNumColumns = 4;
		If ( $iNumPerPage == "" )
			$iNumPerPage = $iNumColumns * 5;
		If ( ! is_numeric($sNewDays) )
			$sNewDays = 2;

		$iDBLoc			= 0;
		$iDBLoc = Trim(Request("iDBLoc"));
		If ($iDBLoc < 0)
			$iDBLoc = 0;
		
		If ( $sSort == "" ) {
			$sSortString = "IG.Position ASC";
		}Else{
			If ( $sSort == "ImageNum" ) {
				$sSortString = "IG.Position ASC";
			}ElseIf ( $sSort == "Image_A" ) {
				$sSortString = "I.Title, I.AltTag, I.Image ASC";
			}ElseIf ( $sSort == "Image_D" ) {
				$sSortString = "I.Title, I.AltTag, I.Image DESC";
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
			}ElseIf ( $sSort == "FileSize_A" ) {
				$sSortString = "I.ImageSize ASC";
			}ElseIf ( $sSort == "FileSize_D" ) {
				$sSortString = "I.ImageSize DESC";
			}
		}
		
		If ( $bDispAllImages == FALSE )
		{
			// Get ttl num of items from the database if it's not already in the QueryString
			if ($iTtlNumItems == 0)
			{
				$sQuery			= "SELECT COUNT(*) FROM ImagesInGallery WHERE GalleryUnq = " . $iGalleryUnq;
				$rsRecordSet	= DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) )
					$iTtlNumItems = $rsRow[0];
			}
			
			$sQuery			= "SELECT AccountUnq, Name, Description, CategoryUnq, ConfUnq, ThreadUnq FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$sAccountUnq	= $rsRow["AccountUnq"];
				$sGalleryName	= Trim($rsRow["Name"]);
				$sGalleryDesc	= Trim($rsRow["Description"]);
				If ( $bDispAllImages == FALSE )
					$iCategoryUnq	= $rsRow["CategoryUnq"];
			}
		}Else{
			If ( $iSearchID == "" )
			{
				// Create the new "search"
				DB_Insert ("INSERT INTO IGAllIView (AccountUnq,DateChanged) VALUES (" . $iLoginAccountUnq . ", GetDate())");
				$rsRecordSet = DB_Query("SELECT @@IDENTITY");	// get the just inserted SearchID for use in the IGSearchResults table
				If ( $rsRow = DB_Fetch($rsRecordSet) )
					$iSearchID = $rsRow[0];
				
				// Recursively add images from all galleries in the current category and it's children.
				AddImagesRecursively($iCategoryUnq, $iSearchID);
			}
			
			// Get that sum for iTtlNumItems
			if ( $iTtlNumItems == 0) 
			{
				$sQuery			= "SELECT COUNT(*) FROM IGAllIViewResults WHERE SearchID = " . $iSearchID;
				$rsRecordSet	= DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) )
					$iTtlNumItems = $rsRow[0];
			}
		}
		
		// reset any subscription emails
		G_ADMINISTRATION_ResetSubscriptionEmail($iLoginAccountUnq);
		?>
		<script language = "javascript">
			
			<?php If ( $bUseAlpha == "YES" ) {?>
			function UseAlpha(cur,which){
				if (which==0)
					cur.filters.alpha.opacity=100
				else
					cur.filters.alpha.opacity=85
			}
			<?php }?>
			
			function PaginationLink(sQueryString){
				document.location = "ThumbnailView.php?<?=DOMAIN_Link("G")?>" + sQueryString;
			}
			
		</script>
		
		<?php
		
		$sDisplayCategoryDropDown		= DisplayCategoryDropDown();
		$sDisplayGalleriesDropDown		= DisplayGalleriesDropDown();
		$sDisplayNumPerPageDropDown		= DisplayNumPerPageDropDown();
		$sDisplaySortDropDown			= DisplaySortDropDown();
		$sBorderColor1					= $GLOBALS["BorderColor1"];
		$sPageBGColor					= $GLOBALS["PageBGColor"];
		$sLinkSuggestGallery			= "";
		$sLinkSubscribeGallery			= "";
		$sDomainLink					= DOMAIN_Link("G");
		$sGalleryOwnerLink				= "";
		$sHeaderBar						= "";
		$sRecordsetNav					= "";
		$sBreadcrumbArrow				= G_STRUCTURE_DI("Arrow.gif", $GLOBALS["COLORBASED"]);
		
	
		If ( $bHasAccount ) {
			If ( DOMAIN_Has_RemoteHost() ) {
				If ( $bDispAllImages == FALSE ) {
					$sTemp = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_SUGGEST_GALLERY")));
					If ( $sTemp == "YES" )
						$sLinkSuggestGallery = "<a href='" . $sSiteURL . "/SuggestGallery/index.php'><img src='" . G_STRUCTURE_DI("SuggestGallery.gif", $GLOBALS["SCHEMEBASED"]) . "' border=0></a>";
				}
			}
		}
		
		If ( $bHasAccount ) {
			If ( DOMAIN_Has_RemoteHost() ) {
				If ( $bDispAllImages == FALSE ) {
					$sTemp = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_SUBSCRIBE_GALLERY")));
					
					If ( $sTemp == "YES" ) {
						If ( G_ADMINISTRATION_HasGallerySubscription($iLoginAccountUnq, $iGalleryUnq) ) {
							$sLinkSubscribeGallery = "<a href='" . $sSiteURL . "/SubscribeGallery/Unsubscribe.php?iGalleryUnq=" . $iGalleryUnq . "'><img src='" . G_STRUCTURE_DI("UnsubscribeGalButton.gif", $GLOBALS["SCHEMEBASED"]) . "' border=0></a>";
						}Else{
							$sLinkSubscribeGallery = "<a href='" . $sSiteURL . "/SubscribeGallery/index.php?iGalleryUnq=" . $iGalleryUnq . "'><img src='" . G_STRUCTURE_DI("SubscribeGalButton.gif", $GLOBALS["SCHEMEBASED"]) . "' border=0></a>";
						}
					}
				}
			}
		}
		
		If ( $bDispAllImages == FALSE ) {
			G_STRUCTURE_CatBreadcrumbs($sBreadCrumb, $iCategoryUnq, $iGalleryUnq, -1);
		}Else{
			G_STRUCTURE_CatBreadcrumbs($sBreadCrumb, $iCategoryUnq, -1, -1);
		}

		$sViewable = "PUBLIC";
		If ( ! $bDispAllImages )
		{
			$sDispOwnerName = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_OWNER_INFO")));
			If ( $sDispOwnerName == "YES" )
			{
				$sTemp = Trim(ACCNT_ReturnADV("PHPJK_FirstName", "V", $sAccountUnq, 0, $sViewable) . " " . ACCNT_ReturnADV("PHPJK_LastName", "V", $sAccountUnq, 0, $sViewable));
				If ( $sTemp == "" )
					$sTemp = ACCNT_UserName($sAccountUnq);
				$sGalleryOwnerLink = "<br>By: <a href='G_Display.php?iAccountUnq=" . $sAccountUnq . "&iCategoryUnq=-1' class='SmallNavPage'>" . $sTemp . "</a>";
			}
		}
		
		$sHeaderBar = G_STRUCTURE_HeaderBar_ReallySpecific_Return("ThumbnailsHead.gif", "", "", "", "Galleries");
		
		If ( $iTtlNumItems > $iNumPerPage )
			$sRecordsetNav = PrintRecordsetNav_Return( "", "iNumPerPage=" . $iNumPerPage . "&iSearchID=" . $iSearchID . "&iCategoryUnq=" . $iCategoryUnq . "&sSort=" . $sSort . "&iNumColumns=" . $iNumColumns . "&iGalleryUnq=" . $iGalleryUnq, "Galleries" );


		Require("Templates/" . $sTemplates . "/ThumbnailView.php");
		
		echo $sThumbnailView;
		
		// display the thumbnails
		If ( $bAccessLocked )
		{
			If ( ! $bDispAllImages ) {
				$sQuery = "SELECT * FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IG.GalleryUnq = " . $iGalleryUnq . " AND I.ImageUnq = IG.ImageUnq ORDER BY " . $sSortString;
			}Else{
				// read from the IGAllIViewResults table for the current "search" - join it with the Images table to get all the fields required below
				$sQuery = "SELECT R.AccountUnq, R.GalleryUnq, I.ImageUnq, I.AltTag, I.Image, I.Thumbnail, I.ImageSize, I.FileType, I.Title, IG.AddDate, IG.Position FROM Images I (NOLOCK), IGAllIViewResults R (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IG.ImageUnq = I.ImageUnq AND R.SearchID = " . $iSearchID . " AND I.ImageUnq = R.ImageUnq ORDER BY " . $sSortString;
			}
			DB_Query("SET ROWCOUNT " . ($iDBLoc + $iNumPerPage));
			$rsRecordSet = DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			For ( $x = 1; $x <= $iDBLoc; $x++)
				DB_Fetch($rsRecordSet);

			Echo "<table cellpadding = 5 cellspacing=0 border=0 width=" . $iTableWidth . " class='TablePage_Boxed'>\n";
			$bDone = False;
			While ( ! $bDone )
			{
				Echo "<tr>";
				For ( $x = 1; $x <= $iNumColumns; $x++ )
				{
					If ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						Echo "<td align=center valign=top>";
						If ( ! $bDispAllImages ) {
							DispThumb( $rsRow["ImageUnq"], $iGalleryUnq, $sAccountUnq, Trim($rsRow["Thumbnail"]), Trim($rsRow["AltTag"]), $rsRow["Position"], Trim($rsRow["ImageSize"]), Trim($rsRow["Image"]), Trim($rsRow["FileType"]), $rsRow["AddDate"], Trim($rsRow["Title"]), Trim($rsRow["PrimaryG"]) );
						}Else{
							DispThumb( $rsRow["ImageUnq"], $rsRow["GalleryUnq"], $rsRow["AccountUnq"], Trim($rsRow["Thumbnail"]), Trim($rsRow["AltTag"]), $rsRow["Position"], Trim($rsRow["ImageSize"]), Trim($rsRow["Image"]), Trim($rsRow["FileType"]), $rsRow["AddDate"], Trim($rsRow["Title"]), Trim($rsRow["PrimaryG"]) );
						}
						Echo "</td>";
					}Else{
						Echo "<td>&nbsp;</td>";
						$bDone = True;
					}
				}
				Echo "</tr>\n";
			}
			Echo "</table>\n";
			
			Echo $sRecordsetNav;
		}Else{
			DOMAIN_Message("This gallery is locked and requires special access to view the images within it.", "ERROR");
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This recurses to fill up the IGAllIViewResults table.							*
	//*																					*
	//************************************************************************************
	Function AddImagesRecursively($iCategoryUnq, $iSearchID)
	{		
		DB_Insert ("INSERT INTO IGAllIViewResults SELECT G.AccountUnq, I.ImageUnq, G.GalleryUnq, G.CategoryUnq, " . $iSearchID . " FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK) WHERE G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq AND IG.ImageUnq = I.ImageUnq");

		$sQuery			= "SELECT CategoryUnq FROM IGCategories WHERE Parent = " . $iCategoryUnq;
		$rsRecordSet	= DB_Query($sQuery);
		While ( $rsRow = DB_Fetch($rsRecordSet) )
			AddImagesRecursively($rsRow["CategoryUnq"], $iSearchID);
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This deletes all old search results.											*
	//*																					*
	//************************************************************************************
	Function DeleteOldAllViewResults()
	{	
		$sQuery			= "SELECT * FROM IGAllIView WHERE DateChanged < '" . DOMAIN_FormatDate(DateAdd("h", -1, time()), "L") . "'";
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			While ( $rsRow = DB_Fetch($rsRecordSet) )
				DB_Update ("DELETE FROM IGAllIViewResults WHERE SearchID = " . $rsRow["SearchID"]);

			DB_Update ("DELETE FROM IGAllIView WHERE DateChanged < '" . DOMAIN_FormatDate(DateAdd("h", -1, time()), "L") . "'");
		}
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
		Global $bAdmin;
		
		$sDisplayCategoryDropDown = "";
		$sDisplayCategoryDropDown .= "<form name='iCategoryUnq' action='G_Display.php' class='PageForm'>";
		$sDisplayCategoryDropDown .= "<b>Category:&nbsp;</b>";
		$sDisplayCategoryDropDown .= "<select name = 'iCategoryUnq' onChange='document.iCategoryUnq.submit();'>";

		$sQuery			= "SELECT * FROM IGCategories (NOLOCK) WHERE Parent = 0 ORDER BY Position";
		$rsRecordSet	= DB_Query($sQuery);
		If ( DB_NumRows($rsRecordSet) > 0 )
		{
			$sDisplayCategoryDropDown .= "<option value='" . $iCategoryUnq . "'>Select Category</option>";
			If ( ( $bAdmin ) || ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) )
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
				If ( $iCategoryUnq == $rsRow["CategoryUnq"] ) {
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
		Global $sSort;
		Global $iNumPerPage;
		Global $iCategoryUnq;
		Global $iGalleryUnq;
		
		$sDisplayGalleriesDropDown = "";
		$sDisplayGalleriesDropDown .= "<form name='ThumbnailView' action='ThumbnailView.php' class='PageForm'>";
		$sDisplayGalleriesDropDown .= "<input type='hidden' name='sSort' value='" . $sSort . "'>";
		$sDisplayGalleriesDropDown .= "<input type='hidden' name='iNumPerPage' value='" . $iNumPerPage . "'>";
		$sDisplayGalleriesDropDown .= "<b>Galleries: </b>";
		$sDisplayGalleriesDropDown .= "<select name = 'iGalleryUnq' onChange='document.ThumbnailView.submit();'>";

		$sQuery			= "SELECT DISTINCT G.GalleryUnq, G.Name FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq ORDER BY G.Name";
		$rsRecordSet	= DB_Query($sQuery);
		If ( DB_NumRows($rsRecordSet) > 0 )
		{
			$sDisplayGalleriesDropDown .= "<option value='" . $iGalleryUnq . "'>Select Gallery</option>";
			While ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$sName = htmlentities($rsRow["Name"]);
				If ( $iGalleryUnq == $rsRow["GalleryUnq"] ) {
					$sDisplayGalleriesDropDown .= "<option value='" . $rsRow["GalleryUnq"] . "' Selected>" . $sName . "</option>";
				}Else{
					$sDisplayGalleriesDropDown .= "<option value='" . $rsRow["GalleryUnq"] . "'>" . $sName . "</option>";
				}
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
	Function DisplayNumPerPageDropDown()
	{
		Global $iGalleryUnq;
		Global $iTtlNumItems;
		Global $sSort;
		Global $iCategoryUnq;
		Global $iSearchID;
		Global $iMaxPerPage;
		Global $bAdmin;
		Global $iNumColumns;
		Global $iNumPerPage;
		
		If ( $bAdmin ) {
			$iMaxPerPage = 9999;
		}Else{
			$iMaxPerPage = Trim(strtoupper(DOMAIN_Conf("IMAGEGALLERY_MAX_THUMBS_DISPLAYED")));
			If ( $iMaxPerPage == "" )
				$iMaxPerPage = 20;
		}
		
		$sDisplayNumPerPageDropDown = "";

		$sDisplayNumPerPageDropDown .= "<form name='ChangeNumPerPage' action='ThumbnailView.php' class='PageForm'>";
		$sDisplayNumPerPageDropDown .= "<input type='hidden' name='iGalleryUnq' value='" . $iGalleryUnq . "'>";
		$sDisplayNumPerPageDropDown .= "<input type='hidden' name='iTtlNumItems' value='" . $iTtlNumItems . "'>";
		$sDisplayNumPerPageDropDown .= "<input type='hidden' name='sSort' value='" . $sSort . "'>";
		$sDisplayNumPerPageDropDown .= "<input type='hidden' name='iCategoryUnq' value='" . $iCategoryUnq . "'>";
		$sDisplayNumPerPageDropDown .= "<input type='hidden' name='iSearchID' value='" . $iSearchID . "'>";
		$sDisplayNumPerPageDropDown .= "<b>Thumbnails on each page: </b>";
		$sDisplayNumPerPageDropDown .= "<select name = 'iNumPerPage' onChange='document.ChangeNumPerPage.submit();'>";
		$sDisplayNumPerPageDropDown .= "	<option value=''>#</option>";

			for ( $iTemp = 1; $iTemp <= 25; $iTemp++ )
			{
				If ( ($iNumColumns*$iTemp) <= $iMaxPerPage ) 
				{
					If ( $iNumPerPage == ($iNumColumns*$iTemp) ) {
						$sDisplayNumPerPageDropDown .= "<option value='" . ($iNumColumns*$iTemp) . "' selected>" . ($iNumColumns*$iTemp) . "</option>";
					}Else{
						$sDisplayNumPerPageDropDown .= "<option value='" . ($iNumColumns*$iTemp) . "'>" . ($iNumColumns*$iTemp) . "</option>";
					}
					If ( ( ($iNumColumns*$iTemp) + $iNumColumns ) >= ( $iTtlNumItems + $iNumColumns) )
						$iTemp = 26;
				}Else{
					break;
				}
			}

		$sDisplayNumPerPageDropDown .= "</select>";
		$sDisplayNumPerPageDropDown .= "</form>";
		
		Return $sDisplayNumPerPageDropDown;

	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplaySortDropDown()
	{
		Global $iGalleryUnq;
		Global $iDBLoc;
		Global $iTtlNumItems;
		Global $iNumPerPage;
		Global $iCategoryUnq;
		Global $iSearchID;
		Global $sSort;
		
		$sDisplaySortDropDown = "";

		$sDisplaySortDropDown .= "<form name='ChangeSort' action='ThumbnailView.php' class='PageForm'>";
		$sDisplaySortDropDown .= "<input type='hidden' name='iGalleryUnq' value='" . $iGalleryUnq . "'>";
		$sDisplaySortDropDown .= "<input type='hidden' name='iDBLoc' value='" . $iDBLoc . "'>";
		$sDisplaySortDropDown .= "<input type='hidden' name='iTtlNumItems' value='" . $iTtlNumItems . "'>";
		$sDisplaySortDropDown .= "<input type='hidden' name='iNumPerPage' value='" . $iNumPerPage . "'>";
		$sDisplaySortDropDown .= "<input type='hidden' name='iCategoryUnq' value='" . $iCategoryUnq . "'>";
		$sDisplaySortDropDown .= "<input type='hidden' name='iSearchID' value='" . $iSearchID . "'>";
		$sDisplaySortDropDown .= "<b>Sort images by: </b>";
		$sDisplaySortDropDown .= "<select name = 'sSort' onChange='document.ChangeSort.submit();'>";
			$sDisplaySortDropDown .= "<option value='ImageNum' ";
			If (( $sSort == "ImageNum" ) || ( $sSort == "" ))  $sDisplaySortDropDown .= "selected"; 
			$sDisplaySortDropDown .= ">Default Order</option>";
			$sDisplaySortDropDown .= "<option value='Image_A' ";
			If ( $sSort == "Image_A" )  $sDisplaySortDropDown .= "selected"; 
			$sDisplaySortDropDown .= ">Name - Ascending</option>";
			$sDisplaySortDropDown .= "<option value='Image_D' ";
			If ( $sSort == "Image_D" )  $sDisplaySortDropDown .= "selected"; 
			$sDisplaySortDropDown .= ">Name - Descending</option>";
			If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_VOTING"))) == "YES" ) {
				$sDisplaySortDropDown .= "<option value='Rating_A' ";
				If ( $sSort == "Rating_A" )  $sDisplaySortDropDown .= "selected"; 
				$sDisplaySortDropDown .= ">Rating - Ascending</option>";
				$sDisplaySortDropDown .= "<option value='Rating_D' ";
				If ( $sSort == "Rating_D" )  $sDisplaySortDropDown .= "selected"; 
				$sDisplaySortDropDown .= ">Rating - Descending</option>";
			}
			$sDisplaySortDropDown .= "<option value='NumViews_A' ";
			If ( $sSort == "NumViews_A" )  $sDisplaySortDropDown .= "selected"; 
			$sDisplaySortDropDown .= "># of Views - Ascending</option>";
			$sDisplaySortDropDown .= "<option value='NumViews_D' ";
			If ( $sSort == "NumViews_D" )  $sDisplaySortDropDown .= "selected"; 
			$sDisplaySortDropDown .= "># of Views - Descending</option>";
			$sDisplaySortDropDown .= "<option value='FileType_A' ";
			If ( $sSort == "FileType_A" )  $sDisplaySortDropDown .= "selected"; 
			$sDisplaySortDropDown .= ">File Type - Ascending</option>";
			$sDisplaySortDropDown .= "<option value='FileType_D' ";
			If ( $sSort == "FileType_D" )  $sDisplaySortDropDown .= "selected"; 
			$sDisplaySortDropDown .= ">File Type - Descending</option>";
			$sDisplaySortDropDown .= "<option value='FileSize_A' ";
			If ( $sSort == "FileSize_A" )  $sDisplaySortDropDown .= "selected"; 
			$sDisplaySortDropDown .= ">File Size - Ascending</option>";
			$sDisplaySortDropDown .= "<option value='FileSize_D' ";
			If ( $sSort == "FileSize_D" )  $sDisplaySortDropDown .= "selected"; 
			$sDisplaySortDropDown .= ">File Size - Descending</option>";
		$sDisplaySortDropDown .= "</select>";
		$sDisplaySortDropDown .= "</form>";
		
		Return $sDisplaySortDropDown;

	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This writes out a single image - used when searching. The IG_Popup JavaScript 	*
	//*		needs to be printed out before this Function is called.							*
	//*	This writes it out for the thumbnail view.										*
	//*																					*
	//************************************************************************************
	Function DispThumb( $iImageUnq, $iGalleryUnq, $sAccountUnq, $sThumbnail, $sAltTag, $iImageNum, $sImageSize, $sImage, $sType, $sAddDate, $sTitle, $iPrimaryG )
	{
		Global $iDBLoc;
		Global $iNumColumns;
		Global $sSort;
		Global $iTtlNumItems;
		Global $iCategoryUnq;
		Global $iNumPerPage;
		Global $aVariables;
		Global $aValues;
		Global $iTextScheme;
		Global $iColorScheme;
		Global $iThumbWidth;
		Global $sNewDays;
		Global $sGalleryPath;
		Global $sSiteURL;
		
		$bIsImage = False;
		
		// Only display this if the primary file exists.
		$sTempAlt = $sAltTag;
		If ( $sAltTag == "" )	// if alt tag is blank, use the image name (switch is back to sTempAlt below)
			$sAltTag = $sTitle;
	
		$sTempImage = $sTitle;
		If ( $sTempImage == "" )
			$sTempImage = $sImage;
		If ( strlen($sTempImage) > 12 )
			$sTempImage = substr($sTempImage, 0, 12) . "...";

		If ( Trim(strtoupper(DOMAIN_Conf("IMAGEGALLERY_USEALPHA"))) == "YES" )
			$sAlphaCode = " style='filter:alpha(opacity=85)' onMouseOver='UseAlpha(this,0)' onMouseOut='UseAlpha(this,1)'";
		
		$aVariables[0] = "iDBLoc";
		$aVariables[1] = "iGalleryUnq";
		$aVariables[2] = "iNumColumns";
		$aVariables[3] = "sSort";
		$aVariables[4] = "iTtlNumItems";
		$aVariables[5] = "iCategoryUnq";
		$aVariables[6] = "iNumPerPage";
		$aVariables[7] = "iImageUnq";
		$aValues[0] = $iDBLoc;
		$aValues[1] = $iGalleryUnq;
		$aValues[2] = $iNumColumns;
		$aValues[3] = $sSort;
		$aValues[4] = $iTtlNumItems;
		$aValues[5] = $iCategoryUnq;
		$aValues[6] = $iNumPerPage;
		$aValues[7] = $iImageUnq;
	
		G_STRUCTURE_FileType($sType, $bIsImage, $iImageUnq, "../../", 0);

		?>
		<table cellpadding=0 cellspacing=0 border=0 width=<?=$iThumbWidth?> class='TablePage_Boxed'>
			<tr>
				<?php If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DOMAINCHECK"))) == "YES" ) { ?>
				<td colspan=3 align=center><a href = 'ImageDetail.php?<?=DOMAIN_Link("G")?>'><img src = "<?=$sSiteURL?>/Attachments/DispThumb.php?sAccountUnq=<?=$sAccountUnq?>&sThumbnail=<?=$sThumbnail?>&iGalleryUnq=<?=$iPrimaryG?>" width=<?=$iThumbWidth?> alt = "<?=htmlentities($sAltTag)?>" border=0<?=$sAlphaCode?>></a></td>
				<?php }Else{
				$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iPrimaryG . "/Thumbnails/" . $sThumbnail;
				$sFilePath	= str_replace("\\", "/", $sFilePath);
				$sFilePath	= str_replace("//", "/", $sFilePath);
				If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
				{
					?>
					<td colspan=3 align=center><a href = 'ImageDetail.php?<?=DOMAIN_Link("G")?>'><img src = "<?=DOMAIN_Conf("IG")?>/<?=$sAccountUnq?>/<?=$iPrimaryG?>/Thumbnails/<?=$sThumbnail?>" width=<?=$iThumbWidth?> alt = "<?=htmlentities($sAltTag)?>" border=0<?=$sAlphaCode?>></a></td>
					<?php
				}Else{
					?>
					<td colspan=3 align=center><a href = 'ImageDetail.php?<?=DOMAIN_Link("G")?>'><img src = "<?=DOMAIN_Conf("IMAGEGALLERY_MISSING_THUMBNAIL")?>" width=<?=$iThumbWidth?> alt = "<?=htmlentities($sAltTag)?>" border=0<?=$sAlphaCode?>></a></td>
					<?php
				}
				} ?>
			</tr>
			<tr><td colspan=3 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "Images/Blank.gif" width=1 height=1></td></tr>
			<tr>
				<td width=50% align=center><font color='<?=$GLOBALS["PageText"]?>' size=-2>#<?=number_format($iImageNum,0)?>&nbsp;</font></td>
				<td width=1 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "Images/Blank.gif" width=1 height=1></td>
				<td width=50% align=center><font size=-2>&nbsp;<?=number_format($sImageSize/1024,0)?>k</font></td>
			</tr>
			<tr><td colspan=3 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "Images/Blank.gif" width=1 height=1></td></tr>
			<tr>
				<td colspan=3 align=center><a href = 'ImageDetail.php?<?=DOMAIN_Link("G")?>' class='SmallNavPage'><?=$sTempImage?></a></td>
			</tr>
			<tr><td colspan=3 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "Images/Blank.gif" width=1 height=1></td></tr>
			<tr>
				<td colspan=3 align=center><?php 
					If ( ! $bIsImage )
						Echo "<img src='Images/MediaIcons/" . $sType . ".gif' alt = '" . $sType . " file'>&nbsp;";
					?><a href = 'ImageDetail.php?<?=DOMAIN_Link("G")?>' class='SmallNav2'>Details</a></td>
			</tr>
		</table>
		<?php 
		If ( DateDiff("d", $sAddDate, time()) <= $sNewDays )
			Echo "&nbsp;<img src='" . G_STRUCTURE_DI("NewImage.gif", $GLOBALS["SCHEMEBASED"]) . "' alt=\" NEW! \" border=0>";

	}
	//************************************************************************************
?>
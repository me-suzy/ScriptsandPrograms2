<?php
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_HeaderBar($sImage, $sAlt, $sText, $sSystem)
	{
		G_STRUCTURE_HeaderBar_Specific($sImage, $sAlt, $sText, "", $sSystem);
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is required because the Preferences pages need to look one dir level 		*
	//*		further down to find the header image.										*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_HeaderBar_Specific($sImage, $sAlt, $sText, $sDir, $sSystem)
	{
		Global $sSiteURL;
		
		G_STRUCTURE_HeaderBar_ReallySpecific($sImage, $sAlt, $sText, $sSiteURL . "/" . $sDir, $sSystem);
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_SubHeaderBar($sImage, $sAlt, $sText, $sSystem)
	{
		Global $sSiteURL;
		
		G_STRUCTURE_SubHeaderBar_Specific($sImage, $sAlt, $sText, $sSiteURL, $sSystem);
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This displays category titles for the News and Image Gallery and whatever...	*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_Category($sHeader, $sSmallText, $sURL, $sSystem)
	{
		G_STRUCTURE_Category_Lines($sHeader, $sSmallText, $sURL, $sSystem, "YES");
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is required because sometimes (especially for Actions), the directory needs*
	//*		to be from the root.														*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_HeaderBar_ReallySpecific($sImage, $sAlt, $sText, $sDir, $sSystem)
	{
		Global $iTableWidth;
		Global $iColorScheme;
		Global $iTextScheme;

		?>
		<table cellpadding=0 cellspacing=0 border=0 width=<?=$iTableWidth?> class='TablePage'>
			<tr>
				<td width=1><img src='<?=G_STRUCTURE_DI("TopBar_Left.gif", $GLOBALS["COLORBASED"])?>' border=0 alt=''></td>
				<?php
				If ( Trim($sImage) == "" ) {
					Echo "<td background='" . G_STRUCTURE_DI("TopBar_BG.gif", $GLOBALS["COLORBASED"]) . "'>";
					Echo $sText;
					Echo "</td>";
				}Else{
					Echo "<td background='" . G_STRUCTURE_DI("TopBar_BG.gif", $GLOBALS["COLORBASED"]) . "'>";
					Echo "<img src=\"" . G_STRUCTURE_DI(htmlentities($sImage), $GLOBALS["SCHEMEBASED"]) . "\" border=0 alt=\"" . htmlentities($sAlt) . "\">";
					Echo "</td>";
				}
				?>
				<td width=1><img src='<?=G_STRUCTURE_DI("TopBar_Right.gif", $GLOBALS["COLORBASED"])?>' border=0 alt=''></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
	

	//************************************************************************************
	//*																					*
	//*	This is required because sometimes (especially for Actions), the directory needs*
	//*		to be from the root.														*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_HeaderBar_ReallySpecific_Return($sImage, $sAlt, $sText, $sDir, $sSystem)
	{
		Global $iTableWidth;
		Global $iColorScheme;
		Global $iTextScheme;

		$sG_STRUCTURE_HeaderBar_ReallySpecific_Return = "";
		$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= "<table cellpadding=0 cellspacing=0 border=0 width=" . $iTableWidth . " class='TablePage'>";
		$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= "	<tr>";
		$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= "		<td width=1><img src='" . G_STRUCTURE_DI("TopBar_Left.gif", $GLOBALS["COLORBASED"]) . "' border=0 alt=''></td>";
			If ( Trim($sImage) == "" ) {
				$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= "<td background='" . G_STRUCTURE_DI("TopBar_BG.gif", $GLOBALS["COLORBASED"]) . "'>";
				$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= $sText;
				$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= "</td>";
			}Else{
				$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= "<td background='" . G_STRUCTURE_DI("TopBar_BG.gif", $GLOBALS["COLORBASED"]) . "'>";
				$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= "<img src=\"" . G_STRUCTURE_DI(htmlentities($sImage), $GLOBALS["SCHEMEBASED"]) . "\" border=0 alt=\"" . htmlentities($sAlt) . "\">";
				$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= "</td>";
			}
		$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= "		<td width=1><img src='" . G_STRUCTURE_DI("TopBar_Right.gif", $GLOBALS["COLORBASED"]) . "' border=0 alt=''></td>";
		$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= "	</tr>";
		$sG_STRUCTURE_HeaderBar_ReallySpecific_Return .= "</table>";
		
		Return $sG_STRUCTURE_HeaderBar_ReallySpecific_Return;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is required because the Preferences pages need to look one dir level 		*
	//*		further down to find the header image.										*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_SubHeaderBar_Specific($sImage, $sAlt, $sText, $sDir, $sSystem)
	{
		Global $iTableWidth;
		Global $iColorScheme;
		Global $iTextScheme;
		
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=<?=$iTableWidth?> class='TablePage'>
			<tr>
				<td bgcolor='<?=$GLOBALS["BorderColor1"]?>' width=1><img src='/Blank.gif' width=1 height=1 alt='' border=0></td>
				<td background='<?=G_STRUCTURE_DI("SubHeader_BG.gif", $GLOBALS["COLORBASED"])?>' width=283><?php
				If ( Trim($sImage) == "" ) {
					Echo $sText;
				}Else{
					Echo "<img src=\"" . G_STRUCTURE_DI(htmlentities($sImage), $GLOBALS["SCHEMEBASED"]) . "\" border=0 alt=\"" . htmlentities($sAlt) . "\">";
				}
				?></td>
				<td bgcolor='<?=$GLOBALS["BGColor1"]?>'>&nbsp;</td>
				<td bgcolor='<?=$GLOBALS["BorderColor1"]?>' width=1><img src='/Blank.gif' width=1 height=1 alt='' border=0></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This displays category titles for the News and Image Gallery and whatever...	*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_Category_Lines($sHeader, $sSmallText, $sURL, $sSystem, $sLines)
	{
		If ( $sLines == "YES" ) {
			?>
			<table cellpadding=0 cellspacing=0 border=0 width=100% height=15 class='TablePage'>
				<tr>
					<td width=100%>&nbsp;<b><a href='<?=$sURL?>' class='MediumNavPage'><?=$sHeader?></a></td>
					<td rowspan=2 width=12 valign=bottom><img src='<?=G_STRUCTURE_DI("CatHead_Middle.gif", $GLOBALS["COLORBASED"])?>' width=12 height=15></td>
					<td rowspan=2 width=5 valign=bottom><a href='<?=$sURL?>'><img src='<?=G_STRUCTURE_DI("CatHead_Right.gif", $GLOBALS["COLORBASED"])?>' width=13 height=15 border=0 alt="<?=htmlentities($sSmallText)?>"></a></td>
				</tr>
				<tr><td><table width=100% cellpadding=0 cellspacing=0 border=0 class='TablePage_Boxed'><tr><td></td></tr></table></td></tr>
			</table>
			<?php
		}Else{
			?>
			<table cellpadding=0 cellspacing=0 border=0 width=100% class='TablePage'>
				<tr>
					<td width=100%>&nbsp;<b><a href='<?=$sURL?>' class='MediumNavPage'><?=$sHeader?></a></td>
				</tr>
			</table>
			<?php
		}
	}
	//************************************************************************************
	
	
	//********************************************************************************
	//*																				*
	//*	$iNumPerPage is the number of items to display on each page					*
	//*	$sURL is the page to put with the link, $sQuerystring is additional qs stuff	*
	//*																				*
	//*	Print out the numbers (if any) between the "previous" and "next" buttons	*
	//*	It'll act like this (current # is in bold):									*
	//*	1 2 3 4 5 6 7 8 9 <b>10</b> >> next											*
	//*	previous << <b>11</b> 12 13 14 15 16 17 18									*
	//*																				*
	//********************************************************************************
	Function PrintRecordsetNav( $sURL, $sQuerystring, $sSystem )
	{
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iNumPerPage;
		Global $iColorScheme;
		
		$iTtlNumItems		= $iTtlNumItems;
		$iDBLoc				= $iDBLoc;
		$iNumPerPage		= $iNumPerPage;
		$iTtlTemp			= (int) $iTtlNumItems / $iNumPerPage;	// this is the number of numbers overall (use the "\" to return int)
		$iDBLocTemp		= (int) $iDBLoc / $iNumPerPage;		// this is which number we are currently on (use the "\" to return int)
		$iNumLinksPerPage	= 10;							// this is the number of links to display

		If ( $sQuerystring <> "" ) {
			$sURLBeg = "<a href = \"JavaScript:PaginationLink('&" . $sQuerystring . "&iTtlNumItems=" . $iTtlNumItems . "&iDBLoc=";
		}Else{
			$sURLBeg = "<a href = \"JavaScript:PaginationLink('&iTtlNumItems=" . $iTtlNumItems . "&iDBLoc=";
		}

		//***** BEGIN DISPLAY *****
		Echo "<table cellpadding=0 cellspacing=5 border=0 class='TablePage'><tr>";
		// Print the <<
		if ($iDBLocTemp >= $iNumPerPage) {
			$iTemp = ( $iDBLocTemp * $iNumPerPage ) - ( $iNumPerPage * $iNumLinksPerPage);
			Echo "<td background='" . G_STRUCTURE_DI("PrintRecordsetNav.gif", $GLOBALS["COLORBASED"]) . "' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\"  class='MediumNav3'>&lt;&lt;</a></td>";
		}
		// Print the "Previous"
		if ($iDBLoc <> 0) {
			$iTemp = $iDBLoc - $iNumPerPage;
			Echo "<td background='" . G_STRUCTURE_DI("PrintRecordsetNav.gif", $GLOBALS["COLORBASED"]) . "' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\" class='MediumNav3'> &#8592; </a></td>";
		}

		// Print the numbers in between. Print them out in sets of 10.
		$iA = $iDBLocTemp-5;
		If ( $iA < 0 ) {
			$iA = 0;
		}
		$iB = $iA + $iNumLinksPerPage;
		For ($x = $iA; $x < $iB; $x++){
			$iTemp = ($x * $iNumPerPage);
			if ($iTemp < $iTtlNumItems) {	// takes care of extra numbers after the overall final number
				If ( ( $iDBLoc >= $iTemp ) && ( $iDBLoc < ($iTemp + $iNumPerPage) ) ) {
					Echo "<td><font size=+1>";
					Echo $x+1;
					Echo "</font></td>";
				}else{
					Echo "<td background='" . G_STRUCTURE_DI("PrintRecordsetNav.gif", $GLOBALS["COLORBASED"]) . "' style='width=29; height=29;' align=center valign=middle>";
					Echo $sURLBeg . ($x * $iNumPerPage);
					Echo "')\"  class='MediumNavPage'>";
					Echo $x+1;
					Echo "</a></td>";
				}
			}else{
				break;
			}
		}

		// Print the "Next"
		if (($iDBLoc + $iNumPerPage) < $iTtlNumItems) {
			$iTemp = ($iDBLoc + $iNumPerPage);
			Echo "<td background='" . G_STRUCTURE_DI("PrintRecordsetNav.gif", $GLOBALS["COLORBASED"]) . "' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\"  class='MediumNav3'> &#8594; </a></td>";
		}
		// Print the >>
		if (($iDBLocTemp + $iNumLinksPerPage) <= $iTtlTemp) {
			$iTemp = ( $iDBLocTemp * $iNumPerPage ) + ( $iNumPerPage * $iNumLinksPerPage);
			Echo "<td background='" . G_STRUCTURE_DI("PrintRecordsetNav.gif", $GLOBALS["COLORBASED"]) . "' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\"  class='MediumNav3'>&gt;&gt;</a></td>";
		}
		Echo "</tr></table>\n";
		//***** END DISPLAY *****

	}
	//********************************************************************************
	
	
	//********************************************************************************
	//*																				*
	//*	$iNumPerPage is the number of items to display on each page					*
	//*	$sURL is the page to put with the link, $sQuerystring is additional qs stuff	*
	//*																				*
	//*	Print out the numbers (if any) between the "previous" and "next" buttons	*
	//*	It'll act like this (current # is in bold):									*
	//*	1 2 3 4 5 6 7 8 9 <b>10</b> >> next											*
	//*	previous << <b>11</b> 12 13 14 15 16 17 18									*
	//*																				*
	//********************************************************************************
	Function PrintRecordsetNav_Return( $sURL, $sQuerystring, $sSystem )
	{
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iNumPerPage;
		Global $iColorScheme;
		
		$sPrintRecordsetNav_Return = "";
		$iTtlNumItems		= $iTtlNumItems;
		$iDBLoc				= $iDBLoc;
		$iNumPerPage		= $iNumPerPage;
		$iTtlTemp			= (int) $iTtlNumItems / $iNumPerPage;	// this is the number of numbers overall (use the "\" to return int)
		$iDBLocTemp		= (int) $iDBLoc / $iNumPerPage;		// this is which number we are currently on (use the "\" to return int)
		$iNumLinksPerPage	= 10;							// this is the number of links to display

		If ( $sQuerystring <> "" ) {
			$sURLBeg = "<a href = \"JavaScript:PaginationLink('&" . $sQuerystring . "&iTtlNumItems=" . $iTtlNumItems . "&iDBLoc=";
		}Else{
			$sURLBeg = "<a href = \"JavaScript:PaginationLink('&iTtlNumItems=" . $iTtlNumItems . "&iDBLoc=";
		}

		//***** BEGIN DISPLAY *****
		$sPrintRecordsetNav_Return .= "<table cellpadding=0 cellspacing=5 border=0 class='TablePage'><tr>";
		// Print the <<
		if ($iDBLocTemp >= $iNumPerPage) {
			$iTemp = ( $iDBLocTemp * $iNumPerPage ) - ( $iNumPerPage * $iNumLinksPerPage);
			$sPrintRecordsetNav_Return .= "<td background='" . G_STRUCTURE_DI("PrintRecordsetNav.gif", $GLOBALS["COLORBASED"]) . "' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\"  class='MediumNav3'>&lt;&lt;</a></td>";
		}
		// Print the "Previous"
		if ($iDBLoc <> 0) {
			$iTemp = $iDBLoc - $iNumPerPage;
			$sPrintRecordsetNav_Return .= "<td background='" . G_STRUCTURE_DI("PrintRecordsetNav.gif", $GLOBALS["COLORBASED"]) . "' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\" class='MediumNav3'> &#8592; </a></td>";
		}

		// Print the numbers in between. Print them out in sets of 10.
		$iA = $iDBLocTemp-5;
		If ( $iA < 0 ) {
			$iA = 0;
		}
		$iB = $iA + $iNumLinksPerPage;
		For ($x = $iA; $x < $iB; $x++){
			$iTemp = ($x * $iNumPerPage);
			if ($iTemp < $iTtlNumItems) {	// takes care of extra numbers after the overall final number
				If ( ( $iDBLoc >= $iTemp ) && ( $iDBLoc < ($iTemp + $iNumPerPage) ) ) {
					$sPrintRecordsetNav_Return .= "<td><font size=+1>";
					$sPrintRecordsetNav_Return .= $x+1;
					$sPrintRecordsetNav_Return .= "</font></td>";
				}else{
					$sPrintRecordsetNav_Return .= "<td background='" . G_STRUCTURE_DI("PrintRecordsetNav.gif", $GLOBALS["COLORBASED"]) . "' style='width=29; height=29;' align=center valign=middle>";
					$sPrintRecordsetNav_Return .= $sURLBeg . ($x * $iNumPerPage);
					$sPrintRecordsetNav_Return .= "')\"  class='MediumNavPage'>";
					$sPrintRecordsetNav_Return .= $x+1;
					$sPrintRecordsetNav_Return .= "</a></td>";
				}
			}else{
				break;
			}
		}

		// Print the "Next"
		if (($iDBLoc + $iNumPerPage) < $iTtlNumItems) {
			$iTemp = ($iDBLoc + $iNumPerPage);
			$sPrintRecordsetNav_Return .= "<td background='" . G_STRUCTURE_DI("PrintRecordsetNav.gif", $GLOBALS["COLORBASED"]) . "' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\"  class='MediumNav3'> &#8594; </a></td>";
		}
		// Print the >>
		if (($iDBLocTemp + $iNumLinksPerPage) <= $iTtlTemp) {
			$iTemp = ( $iDBLocTemp * $iNumPerPage ) + ( $iNumPerPage * $iNumLinksPerPage);
			$sPrintRecordsetNav_Return .= "<td background='" . G_STRUCTURE_DI("PrintRecordsetNav.gif", $GLOBALS["COLORBASED"]) . "' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\"  class='MediumNav3'>&gt;&gt;</a></td>";
		}
		$sPrintRecordsetNav_Return .= "</tr></table>\n";
		//***** END DISPLAY *****
		Return $sPrintRecordsetNav_Return;
	}
	//********************************************************************************
	
	
// ImageGallery specific
	//************************************************************************************
	//*																					*
	//*	displays the filetype icon if the file is not an image type.					*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_FileType(&$sType, &$bIsImage, $iImageUnq, $sPath, $sAltImageNum)
	{
		$sType = Trim($sType);
		If ( $sType == "" ) {
			$sQuery			= "SELECT FileType, Image2, Image3, Image4, Image5 FROM Images WHERE ImageUnq = " . $iImageUnq;
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				If ( $sAltImageNum > 0 )
				{
					$sTemp = Trim($rsRow["Image" . $sAltImageNum]);
					$iPos = strrpos( $sTemp, "." );
					If ( $iPos > 0 )
					{
						$sType = substr($sTemp, $iPos+1, strlen($sTemp)-$iPos);
					}
				}Else{
					$sType = Trim($rsRow["FileType"]);
				}
			}
		}
		
		$sPath2 = realpath($_SERVER["PATH_TRANSLATED"] . $sPath);
		$sFilePath = $sPath2 . "/Images/MediaIcons/" . strtoupper($sType) . ".gif";
		$sFilePath = str_replace("\\", "/", $sFilePath);
		$sFilePath = str_replace("//", "/", $sFilePath);
		If ( file_exists($sFilePath) ) {
			$bIsImage = False;
		}Else{
			$bIsImage = True;
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Gets the parent's category name from the parent's CategoryUnq and DomainUnq.	*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_GetParentName($iParentUnq)
	{
		$sQuery			= "SELECT Name FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iParentUnq;
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow["Name"];

		Return "&lt;Top Level Category&gt;";		
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_CatBreadcrumbs(&$sBreadCrumb, $iParentUnq, $iGalleryUnq, $iImageUnq)
	{
		Global $iColorScheme;
		Global $sSiteURL;
		
		If ( $iParentUnq == "" ) {
			$iParentUnq = 0;
		}Else{
			If ( ! is_numeric($iParentUnq) )
				$iParentUnq = 0;
		}

		// Now get the actual data for the category
		$sQuery			= "SELECT Name, Parent FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iParentUnq;
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			if ( $sBreadCrumb == "" ) {
				If ( $iGalleryUnq != -1 ) {
					$sBreadCrumb = "<a href = '" . $sSiteURL . "/index.php?iParentUnq=" . $iParentUnq . "' class='MediumNavPage'>" . $rsRow["Name"] . "</a>" . "&nbsp;<img src = '" . G_STRUCTURE_DI("Arrow.gif", $GLOBALS["COLORBASED"]) . "'>&nbsp;";
				}Else{
					$sBreadCrumb = "<b>" . $rsRow["Name"] . "</b>  ";
				}
			}Else{
				$sBreadCrumb = "<a href = '" . $sSiteURL . "/index.php?iParentUnq=" . $iParentUnq . "' class='MediumNavPage'>" . $rsRow["Name"] . "</a>" . "&nbsp;<img src = '" . G_STRUCTURE_DI("Arrow.gif", $GLOBALS["COLORBASED"]) . "'>&nbsp;" . $sBreadCrumb;
			}
			G_STRUCTURE_CatBreadcrumbs($sBreadCrumb, $rsRow["Parent"], $iGalleryUnq, $iImageUnq);
		}Else{
			// we are done getting the categories, so get and add the gallery and image (if there is one)
			GetGalleryAndImage($sBreadCrumb, $iGalleryUnq, $iImageUnq);
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function GetGalleryAndImage(&$sBreadCrumb, $iGalleryUnq, $iImageUnq)
	{
		Global $iColorScheme;
		Global $sSiteURL;
		
		$sQuery			= "SELECT Name FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			If ( $iImageUnq != -1 ) {
				// we must add the gallery name as a link to the gallery thumbnail page
				$sBreadCrumb = $sBreadCrumb . "<a href = '" . $sSiteURL . "/ThumbnailView.php?iGalleryUnq=" . $iGalleryUnq . "' class='MediumNavPage'>" . Trim($rsRow["Name"]) . "</a>" . "&nbsp;<img src = '" . G_STRUCTURE_DI("Arrow.gif", $GLOBALS["COLORBASED"]) . "'>&nbsp;";
				// now add the image name
				$sQuery			= "SELECT Image, Title FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
				$rsRecordSet2	= DB_Query($sQuery);
				if ( $rsRow2 = DB_Fetch($rsRecordSet2) )
				{
					If ( Trim($rsRow2["Title"]) == "" ) {
						$sBreadCrumb = $sBreadCrumb . "<b>" . Trim($rsRow2["Image"]) . "</b>";
					}Else{
						$sBreadCrumb = $sBreadCrumb . "<b>" . Trim($rsRow2["Title"]) . "</b>";
					}
				}
			}Else{
				$sBreadCrumb = $sBreadCrumb . "<b>" . Trim($rsRow["Name"]) . "</b>";
			}
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	displays the HTMLCodes button bar.												*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_HTMLCode_Buttons()
	{
		// removed till further notices
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_LINK_New_Image_Search()
	{
		Global $iTextScheme;
		Global $iColorScheme;
		Global $sSiteURL;
		
		$sTemp = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_NEW_IMAGE_SEARCH")));
		
		If ( $sTemp == "YES" )
			?><a href = '<?=$sSiteURL?>/Search/PrepareResults.php?<?=DOMAIN_Link("G")?>&sKeywords=PHP_JK_NEW'><img src='<?=G_STRUCTURE_DI("NewImageSearch.gif", $GLOBALS["SCHEMEBASED"])?>' alt='' border=0></a><?php
	}
	//************************************************************************************
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_LINK_Category_Gallery_Map()
	{
		Global $iTextScheme;
		Global $iColorScheme;
		Global $sSiteURL;
		
		$sTemp = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_CAT_GAL_MAP")));
		
		If ( $sTemp == "YES" )
			?><a href = '<?=$sSiteURL?>/Map.php?<?=DOMAIN_Link("G")?>'><img src='<?=G_STRUCTURE_DI("CategoryMap.gif", $GLOBALS["SCHEMEBASED"])?>' alt='View a heirarchical map of the categories and galleries.' border=0></a><?php
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_LINK_Suggest_Category()
	{
		Global $iTextScheme;
		Global $iColorScheme;
		Global $sSiteURL;
		
		$sTemp = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_SUGGEST_CATEGORY")));
		
		If ( $sTemp == "YES" )
			?><a href='<?=$sSiteURL?>/SuggestCategory/index.php'><img src='<?=G_STRUCTURE_DI("SuggestCatButton.gif", $GLOBALS["SCHEMEBASED"])?>' border=0></a><?php
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_LINK_Suggest_Gallery()
	{
		Global $iTextScheme;
		Global $iColorScheme;
		Global $sSiteURL;
		
		$sTemp = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_SUGGEST_GALLERY")));
		
		If ( $sTemp == "YES" )
			?><a href='<?=$sSiteURL?>/SuggestGallery/index.php'><img src='<?=G_STRUCTURE_DI("SuggestGallery.gif", $GLOBALS["SCHEMEBASED"])?>' border=0></a><?php
	}
	//************************************************************************************


	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_LINK_Subscribe_Category($iCategoryUnq)
	{
		Global $iLoginAccountUnq;
		Global $iTextScheme;
		Global $iColorScheme;
		Global $sSiteURL;
		
		$sTemp = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_SUBSCRIBE_CATEGORY")));
		
		If ( $sTemp == "YES" ) {
			If ( G_ADMINISTRATION_HasCategorySubscription($iLoginAccountUnq, $iCategoryUnq) ) {
				?><a href='<?=$sSiteURL?>/SubscribeCategory/Unsubscribe.php?iCategoryUnq=<?=$iCategoryUnq?>'><img src='<?=G_STRUCTURE_DI("UnsubscribeCatButton.gif", $GLOBALS["SCHEMEBASED"])?>' border=0></a><?php
			}Else{
				?><a href='<?=$sSiteURL?>/SubscribeCategory/index.php?iCategoryUnq=<?=$iCategoryUnq?>'><img src='<?=G_STRUCTURE_DI("SubscribeCatButton.gif", $GLOBALS["SCHEMEBASED"])?>' border=0></a><?php
			}
		}
	}
	//************************************************************************************	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_LINK_Subscribe_Gallery($iGalleryUnq)
	{
		Global $iLoginAccountUnq;
		Global $iTextScheme;
		Global $iColorScheme;
		Global $sSiteURL;
		
		$sTemp = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_SUBSCRIBE_GALLERY")));
		
		If ( $sTemp == "YES" ) {
			If ( G_ADMINISTRATION_HasGallerySubscription($iLoginAccountUnq, $iGalleryUnq) ) {
				?><a href='<?=$sSiteURL?>/SubscribeGallery/Unsubscribe.php?iGalleryUnq=<?=$iGalleryUnq?>'><img src='<?=G_STRUCTURE_DI("UnsubscribeGalButton.gif", $GLOBALS["SCHEMEBASED"])?>' border=0></a><?php
			}Else{
				?><a href='<?=$sSiteURL?>/SubscribeGallery/index.php?iGalleryUnq=<?=$iGalleryUnq?>'><img src='<?=G_STRUCTURE_DI("SubscribeGalButton.gif", $GLOBALS["SCHEMEBASED"])?>' border=0></a><?php
			}
		}
	}
	//************************************************************************************	
	
	
	//************************************************************************************
	//*																					*
	//*	This displays a structural image.												*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_DI($sImageName, $sType)
	{
		Global $sSiteURL;
		// $sType =
		//	$COLORBASED			= 1;
		//	$SCHEMEBASED		= 2;
		//	$ADMINISTRATIVE		= 3;
		
		If ( $sType == $GLOBALS["COLORBASED"] ) {
			Return $sSiteURL . "/Templates/" . $GLOBALS["sTemplates"] . "/Images/ColorBased/" . $sImageName;
		}ELseIf ( $sType == $GLOBALS["SCHEMEBASED"] ) {
			Return $sSiteURL . "/Templates/" . $GLOBALS["sTemplates"] . "/Images/SchemeBased/" . $GLOBALS["iTextScheme"] . "/" . $sImageName;
		}Else{
			Return $sSiteURL . "/Templates/" . $GLOBALS["sTemplates"] . "/Images/Administrative/" . $sImageName;
		}
	}
	//************************************************************************************
	
	
	
	//***************ADMIN SPECIFIC VERSIONS -- COMPATABLE W/ THE OLD COLOR/TEXT SCHEME FUNCTIONALITY***********
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_HeaderBar_ADMIN($sImage, $sAlt, $sText, $sSystem)
	{
		G_STRUCTURE_HeaderBar_Specific_ADMIN($sImage, $sAlt, $sText, "", $sSystem);
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is required because the Preferences pages need to look one dir level 		*
	//*		further down to find the header image.										*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_HeaderBar_Specific_ADMIN($sImage, $sAlt, $sText, $sDir, $sSystem)
	{
		Global $sSiteURL;
		
		G_STRUCTURE_HeaderBar_ReallySpecific_ADMIN($sImage, $sAlt, $sText, $sSiteURL . "/" . $sDir, $sSystem);
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_SubHeaderBar_ADMIN($sImage, $sAlt, $sText, $sSystem)
	{
		Global $sSiteURL;
		
		G_STRUCTURE_SubHeaderBar_Specific_ADMIN($sImage, $sAlt, $sText, $sSiteURL, $sSystem);
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is required because sometimes (especially for Actions), the directory needs*
	//*		to be from the root.														*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_HeaderBar_ReallySpecific_ADMIN($sImage, $sAlt, $sText, $sDir, $sSystem)
	{
		Global $iTableWidth;
		Global $iColorScheme;
		Global $iTextScheme;

		If ( strpos($iTableWidth, "%") > 0 ) {
			$iTemp = $iTableWidth;
		}Else{
			$iTemp = $iTableWidth + 2;
		}

		?>
		<table cellpadding=0 cellspacing=0 border=0 width=<?=$iTemp?>>
			<tr>
				<td width=1><img src='<?=$sDir?>Images/ColorBased/<?=$iColorScheme?>/TopBar_Left.gif' border=0 alt=''></td>
				<?php
				If ( Trim($sImage) == "" ) {
					Echo "<td background='" . $sDir . "Images/ColorBased/" . $iColorScheme . "/TopBar_BG.gif'>";
					Echo "<font color='" . $GLOBALS["PageText"] . "' size=+1>" . $sText;
					Echo "</td>";
				}Else{
					Echo "<td background='" . $sDir . "Images/ColorBased/" . $iColorScheme . "/TopBar_BG.gif'>";
					Echo "<img src=\"" . $sDir . "Images/SchemeBased/" . $iTextScheme . "/" . $iColorScheme . "/";
					Echo htmlentities($sImage);
					Echo "\" border=0 alt=\"" . htmlentities($sAlt) . "\">";
					Echo "</td>";
				}
				?>
				<td width=1><img src='<?=$sDir?>Images/ColorBased/<?=$iColorScheme?>/TopBar_Right.gif' border=0 alt=''></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is required because the Preferences pages need to look one dir level 		*
	//*		further down to find the header image.										*
	//*																					*
	//************************************************************************************
	Function G_STRUCTURE_SubHeaderBar_Specific_ADMIN($sImage, $sAlt, $sText, $sDir, $sSystem)
	{
		Global $iTableWidth;
		Global $iColorScheme;
		Global $iTextScheme;
		
		If ( strpos($iTableWidth, "%") > 0 ) {
			$iTemp = $iTableWidth;
		}Else{
			$iTemp = $iTableWidth + 2;
		}
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=<?=$iTemp?>>
			<tr>
				<td bgcolor='<?=$GLOBALS["BorderColor1"]?>' width=1><img src='/Blank.gif' width=1 height=1 alt='' border=0></td>
				<td background='/<?=$sDir?>Images/ColorBased/<?=$iColorScheme?>/SubHeader_BG.gif' width=283><?php
				If ( Trim($sImage) == "" ) {
					Echo "<font color='" . $GLOBALS["PageText"] . "' size=+1>" . $sText;
				}Else{
					Echo "<img src=\"/" . $sDir . "Images/SchemeBased/" . $iTextScheme . "/" . $iColorScheme . "/" . htmlentities($sImage) . "\" border=0 alt=\"" . htmlentities($sAlt) . "\">";
				}
				?></td>
				<td bgcolor='<?=$GLOBALS["BGColor1"]?>'>&nbsp;</td>
				<td bgcolor='<?=$GLOBALS["BorderColor1"]?>' width=1><img src='/Blank.gif' width=1 height=1 alt='' border=0></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
	
	
	//********************************************************************************
	//*																				*
	//*	$iNumPerPage is the number of items to display on each page					*
	//*	$sURL is the page to put with the link, $sQuerystring is additional qs stuff	*
	//*																				*
	//*	Print out the numbers (if any) between the "previous" and "next" buttons	*
	//*	It'll act like this (current # is in bold):									*
	//*	1 2 3 4 5 6 7 8 9 <b>10</b> >> next											*
	//*	previous << <b>11</b> 12 13 14 15 16 17 18									*
	//*																				*
	//********************************************************************************
	Function PrintRecordsetNav_ADMIN( $sURL, $sQuerystring, $sSystem )
	{
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iNumPerPage;
		Global $iColorScheme;
		
		$iTtlNumItems		= $iTtlNumItems;
		$iDBLoc				= $iDBLoc;
		$iNumPerPage		= $iNumPerPage;
		$iTtlTemp			= (int) $iTtlNumItems / $iNumPerPage;	// this is the number of numbers overall (use the "\" to return int)
		$iDBLocTemp		= (int) $iDBLoc / $iNumPerPage;		// this is which number we are currently on (use the "\" to return int)
		$iNumLinksPerPage	= 10;							// this is the number of links to display

		If ( $sQuerystring <> "" ) {
			$sURLBeg = "<a href = \"JavaScript:PaginationLink('&" . $sQuerystring . "&iTtlNumItems=" . $iTtlNumItems . "&iDBLoc=";
		}Else{
			$sURLBeg = "<a href = \"JavaScript:PaginationLink('&iTtlNumItems=" . $iTtlNumItems . "&iDBLoc=";
		}

		//***** BEGIN DISPLAY *****
		Echo "<table cellpadding=0 cellspacing=5 border=0><tr>";
		// Print the <<
		if ($iDBLocTemp >= $iNumPerPage) {
			$iTemp = ( $iDBLocTemp * $iNumPerPage ) - ( $iNumPerPage * $iNumLinksPerPage);
			Echo "<td background='Images/ColorBased/" . $iColorScheme . "/PrintRecordsetNav.gif' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\"  class='MediumNav3'>&lt;&lt;</a></td>";
		}
		// Print the "Previous"
		if ($iDBLoc <> 0) {
			$iTemp = $iDBLoc - $iNumPerPage;
			Echo "<td background='Images/ColorBased/" . $iColorScheme . "/PrintRecordsetNav.gif' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\" class='MediumNav3'> &#8592; </a></td>";
		}

		// Print the numbers in between. Print them out in sets of 10.
		$iA = $iDBLocTemp-5;
		If ( $iA < 0 ) {
			$iA = 0;
		}
		$iB = $iA + $iNumLinksPerPage;
		For ($x = $iA; $x < $iB; $x++){
			$iTemp = ($x * $iNumPerPage);
			if ($iTemp < $iTtlNumItems) {	// takes care of extra numbers after the overall final number
				If ( ( $iDBLoc >= $iTemp ) && ( $iDBLoc < ($iTemp + $iNumPerPage) ) ) {
					Echo "<td><font color='" . $GLOBALS["PageText"] . "' size=+1>";
					Echo $x+1;
					Echo "</font></td>";
				}else{
					Echo "<td background='Images/ColorBased/" . $iColorScheme . "/PrintRecordsetNav.gif' style='width=29; height=29;' align=center valign=middle>";
					Echo $sURLBeg . ($x * $iNumPerPage);
					Echo "')\"  class='MediumNavPage'>";
					Echo $x+1;
					Echo "</a></td>";
				}
			}else{
				break;
			}
		}

		// Print the "Next"
		if (($iDBLoc + $iNumPerPage) < $iTtlNumItems) {
			$iTemp = ($iDBLoc + $iNumPerPage);
			Echo "<td background='Images/ColorBased/" . $iColorScheme . "/PrintRecordsetNav.gif' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\"  class='MediumNav3'> &#8594; </a></td>";
		}
		// Print the >>
		if (($iDBLocTemp + $iNumLinksPerPage) <= $iTtlTemp) {
			$iTemp = ( $iDBLocTemp * $iNumPerPage ) + ( $iNumPerPage * $iNumLinksPerPage);
			Echo "<td background='Images/ColorBased/" . $iColorScheme . "/PrintRecordsetNav.gif' style='width=29; height=29;' align=center valign=middle>" . $sURLBeg . $iTemp . "')\"  class='MediumNav3'>&gt;&gt;</a></td>";
		}
		Echo "</tr></table>\n";
		//***** END DISPLAY *****

	}
	//********************************************************************************
?>
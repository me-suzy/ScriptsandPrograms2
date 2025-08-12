<?php
	Require("../Includes/i_Includes.php");
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	If ( Trim(Request("iSearchID")) != "" ) {
		Main();
	}Else{
		DOMAIN_Message("Search Expired.", "ERROR");
	}
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $aVariables;
		Global $aValues;
		Global $iTableWidth;
		Global $sNewDays;
		
		$bUseAlpha		= Trim(strtoupper(DOMAIN_Conf("IMAGEGALLERY_USEALPHA")));
		$sNewDays		= Trim(DOMAIN_Conf("IMAGEGALLERY_NEWIMAGE_DAYS"));
		
		If ( ! is_numeric($sNewDays) ) 
			$sNewDays = 2;
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function PaginationLink(sQueryString){
				document.location = "DisplayResults.php?<?=DOMAIN_Link("G")?>" + sQueryString;
			}
			
			<?php If ( $bUseAlpha == "YES" ) {?>
			function UseAlpha(cur,which){
				if (which==0)
					cur.filters.alpha.opacity=100
				else
					cur.filters.alpha.opacity=85
			}
			<?php }?>
			
		</script>
		<?php 
		RefreshSearchResults();
		SearchConf();	// Display the search results
	}
	//************************************************************************************

	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function SearchConf()
	{
		Global $iTableWidth;
		Global $iTtlNumItems;
		Global $iGalleryUnq;
		Global $iTtlNumItems;
		Global $iSearchID;
		Global $iNumColumns;
		Global $iNumPerPage;
		Global $iDBLoc;
		
		$iTtlNumItems	= Request("iTtlNumItems");
		$iNumPerPage	= Trim(Request("iNumPerPage"));
		$iNumColumns	= DOMAIN_Conf("IMAGEGALLERY_THUMBNAILVIEW_NUMCOLUMNS");
		
		If ( $iNumColumns == "" )
			$iNumColumns = 4;
		If ( $iNumPerPage == "" )
			$iNumPerPage = $iNumColumns * 5;

		// Pagination variables -- begin
		$iDBLoc			= 0;
		$iTtlNumItems	= 0;
		If ( isset($_REQUEST["iTtlNumItems"]) )
			$iTtlNumItems = Trim($_REQUEST["iTtlNumItems"]);
		If ( isset($_REQUEST["iDBLoc"]) )
			$iDBLoc = Trim($_REQUEST["iDBLoc"]);
		If ($iDBLoc < 0)
			$iDBLoc = 0;
			
		if ( $iTtlNumItems == 0 ) {
			$sQuery			= "SELECT Count(*) FROM IGSearchResults R (NOLOCK), Images I (NOLOCK) WHERE R.SearchID = " . Request("iSearchID") . " AND R.ImageUnq=I.ImageUnq";
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
				$iTtlNumItems = $rsRow[0];
		}
		// Pagination variables -- end

		$SearchsqlText = "SELECT R.ImageUnq, R.GalleryUnq, R.CategoryUnq, G.AccountUnq, G.Name, I.Thumbnail, I.AltTag, I.ImageNum, I.ImageSize, I.Image, I.FileType, I.Title, IG.AddDate, IG.PrimaryG FROM IGSearchResults R (NOLOCK), Images I (NOLOCK), ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK) WHERE R.SearchID = " . Request("iSearchID") . " AND R.ImageUnq=I.ImageUnq AND IG.ImageUnq = I.ImageUnq AND IG.GalleryUnq = G.GalleryUnq AND G.GalleryUnq = R.GalleryUnq";
		DB_Query("SET ROWCOUNT " . ($iDBLoc + $iNumPerPage));
		$rsRecordSet = DB_Query($SearchsqlText);
		DB_Query("SET ROWCOUNT 0");
		If ( DB_NumRows($rsRecordSet) > 0 )
		{
			For ( $x = 1; $x <= $iDBLoc; $x++)
				DB_Fetch($rsRecordSet);
			Echo "<BR>";
			G_STRUCTURE_HeaderBar("SearchResultsHead.gif", "", "", "Galleries");
			?>
			<table cellpadding=1 cellspacing=0 border=0 width=<?=$iTableWidth?> class='Table1_Boxed'>
				<tr>
					<td>
						<?php 
						Echo "Results for search: ";
						If ( strtoupper(Trim(Request("sKeywords"))) == "PHP_JK_NEW" ) {
							Echo "<b><i>New Images</i></b>";
						}Else{
							Echo "<b>" . Request("sKeywords") . "</b>";
						}
						?>
					</td>
					<td align=right>
						<b><?=$iTtlNumItems?></b> images found
					</td>
				</tr>
			</table>
			<?php 
			Echo "<table width=" . $iTableWidth . " cellpadding = 1 cellspacing = 0 border = 0 class='TablePage_Boxed'>\n";
			$bDone = False;
			While ( ! $bDone )
			{
				Echo "<tr>";
				For ( $x = 1; $x <= $iNumColumns; $x++ )
				{
					If ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						$iImageUnq 		= $rsRow["ImageUnq"];
						$iGalleryUnq	= $rsRow["GalleryUnq"];
						$iCategoryUnq	= $rsRow["CategoryUnq"];
						Echo "<td align=center valign=top>";
						DispThumb( $iImageUnq, $iGalleryUnq, $rsRow["AccountUnq"], Trim($rsRow["Thumbnail"]), Trim($rsRow["AltTag"]), $rsRow["ImageNum"], Trim($rsRow["ImageSize"]), Trim($rsRow["Image"]), Trim($rsRow["FileType"]), Trim($rsRow["AddDate"]), Trim($rsRow["Title"]), $rsRow["PrimaryG"], Trim($rsRow["Name"]) );
						Echo "<br></td>";
					}Else{
						Echo "<td>&nbsp;</td>";
						$bDone = True;
					}
				}
				Echo "</tr>\n";
			}
			Echo "</table>\n";

			PrintRecordsetNav( "DisplayResults.php", "iSearchID=" . Request("iSearchID") . "&sKeywords=" . URLEncode(Request("sKeywords")), "Galleries" );
		}Else{
			Echo "<BR>";
			G_STRUCTURE_HeaderBar("SearchResultsHead.gif", "", "", "Galleries");
			?>
			<table cellpadding=0 cellspacing=0 border=0 width=<?=$iTableWidth?> class='TablePage_Boxed'>
				<tr>
					<td>
						<?php 
						If ($iTtlNumItems == 0) 
						{
							If ( strtoupper(Trim(Request("sKeywords"))) == "PHP_JK_NEW" ) {
								Echo "There are no new images.";
							}Else{
								Echo "No images returned for your search: <b>" . Request("sKeywords") . "</b>.";
							}
						}Else{
							DOMAIN_Message("Search Expired.", "ERROR");
						}
						?>
					</td>
				</tr>
			</table>
			<br>
			<?php 
		}		
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This resets the timeout period of the search results to the current time.		*
	//*																					*
	//************************************************************************************
	Function RefreshSearchResults()
	{
		If ( Trim(Request("iSearchID")) != "" )
			DB_Update ("UPDATE IGSearches SET DateChanged = GetDate() WHERE SearchID = " . Request("iSearchID"));
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This writes out a single image - used when searching. The IG_Popup JavaScript 	*
	//*		needs to be printed out before this Function is called.						*
	//*	This writes it out for the thumbnail view.										*
	//*																					*
	//************************************************************************************
	Function DispThumb( $iImageUnq, $iGalleryUnq, $sAccountUnq, $sThumbnail, $sAltTag, $iImageNum, $sImageSize, $sImage, $sType, $sAddDate, $sTitle, $iPrimaryG, $sGName )
	{
		Global $iDBLoc;
		Global $iNumColumns;
		Global $sSort;
		Global $iTtlNumItems;
		Global $iCategoryUnq;
		Global $iNumPerPage;
		Global $aVariables;
		Global $aValues;
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
			$sTempImage = substr($sTempImage, 12) . "...";

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
		
		$sType = "";
		G_STRUCTURE_FileType($sType, $bIsImage, $iImageUnq, "../../../", 0);
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> class='TablePage_Boxed'>
			<tr>
				<?php If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DOMAINCHECK"))) == "YES" ) { ?>
				<td colspan=3 align=center><a href = '<?=$sSiteURL?>/ImageDetail.php?<?=DOMAIN_Link("G")?>'><img src = "<?=$sSiteURL?>/Attachments/DispThumb.php?sAccountUnq=<?=$sAccountUnq?>&sThumbnail=<?=$sThumbnail?>&iGalleryUnq=<?=$iPrimaryG?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> alt = "<?=htmlentities($sAltTag)?> <?=$sGName?>" border=0<?=$sAlphaCode?>></a></td>
				<?php }Else{
				$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iPrimaryG . "/Thumbnails/" . $sThumbnail;
				$sFilePath	= str_replace("\\", "/", $sFilePath);
				$sFilePath	= str_replace("//", "/", $sFilePath);
				If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
				{
					?>
					<td colspan=3 align=center><a href = '<?=$sSiteURL?>/ImageDetail.php?<?=DOMAIN_Link("G")?>'><img src = "<?=DOMAIN_Conf("IG")?>/<?=$sAccountUnq?>/<?=$iPrimaryG?>/Thumbnails/<?=$sThumbnail?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> alt = "<?=htmlentities($sAltTag)?> <?=$sGName?>" border=0<?=$sAlphaCode?>></a></td>
					<?php
				}Else{
					?>
					<td colspan=3 align=center><a href = '<?=$sSiteURL?>/ImageDetail.php?<?=DOMAIN_Link("G")?>'><img src = "<?=DOMAIN_Conf("IMAGEGALLERY_MISSING_THUMBNAIL")?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> alt = "<?=htmlentities($sAltTag)?> <?=$sGName?>" border=0<?=$sAlphaCode?>></a></td>
					<?php
				}
				}?>
			</tr>
			<tr>
				<td width=50% align=center><font color='<?=$GLOBALS["PageText"]?>' size=-2>#<?=number_format($iImageNum,0)?>&nbsp;</font></td>
				<td width=1 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "/WS_Content../Images/Blank.gif" width=1 height=1></td>
				<td width=50% align=center><font color='<?=$GLOBALS["PageText"]?>' size=-2>&nbsp;<?=number_format($sImageSize/1024,0)?>k</font></td>
			</tr>
			<tr><td colspan=5 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "/WS_Content../Images/Blank.gif" width=1 height=1></td></tr>
			<tr>
				<td colspan=3 align=center><a href = '<?=$sSiteURL?>/ImageDetail.php?<?=DOMAIN_Link("G")?>' class='SmallNavPage'><?=$sTempImage?></a></td>
			</tr>
			<tr><td colspan=5 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "/WS_Content../Images/Blank.gif" width=1 height=1></td></tr>
			<tr>
				<td colspan=3 align=center><?php 
					If ( ! $bIsImage )
						Echo "<img src='../Images/MediaIcons/" . $sType . ".gif' alt = '" . $sType . " file'>&nbsp;";
					?><a href = '<?=$sSiteURL?>/ImageDetail.php?<?=DOMAIN_Link("G")?>' class='SmallNav2'>Details</a></td>
			</tr>
			<tr><td colspan=5 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "/WS_Content../Images/Blank.gif" width=1 height=1></td></tr>
			<tr>
				<td colspan=3 align=center>
				<a href='<?=$sSiteURL?>/ThumbnailView.php?iGalleryUnq=<?=$iGalleryUnq?>' class='SmallNav2'><?=$sGName?></a>
				</td>
			</tr>
		</table>
		<?php 
		If ( DateDiff("d", $sAddDate, time()) <= $sNewDays )
			Echo "&nbsp;<img src='" . G_STRUCTURE_DI("NewImage.gif", $GLOBALS["SCHEMEBASED"]) . "' alt=\" NEW! \" border=0>";

	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayNumPerPageDropDown()
	{
		Global $iTtlNumItems;
		Global $iGalleryUnq;
		Global $iTtlNumItems;
		Global $iSearchID;
		Global $iNumColumns;
		
		?>
		<form name='ChangeNumPerPage' action='DisplayResults.php' class='PageForm'>
		<input type='hidden' name='iGalleryUnq' value='<?=$iGalleryUnq?>'>
		<input type='hidden' name='iTtlNumItems' value='<?=$iTtlNumItems?>'>
		<input type='hidden' name='iSearchID' value='<?=$iSearchID?>'>
		<input type='hidden' name='sKeywords' value="<?=URLEncode(sKeywords)?>">
		<b>Thumbnails on each page: </b>
		<select name = "iNumPerPage" onChange='document.ChangeNumPerPage.submit();'>
			<option value=''>#</option>
			<?php 
			For ( $iTemp = 1; $iTemp < 25; $iTemp++ )
			{
				If ( $iNumPerPage == ($iNumColumns*$iTemp) ) {
					Echo "<option value='" . $iNumColumns*$iTemp . "' selected>" . $iNumColumns*$iTemp . "</option>";
				}Else{
					Echo "<option value='" . $iNumColumns*$iTemp . "'>" . $iNumColumns*$iTemp . "</option>";
				}
				If ( ( $iNumColumns*$iTemp + $iNumColumns ) >= ( $iTtlNumItems + $iNumColumns) )
					$iTemp = 26;
			}
			?>
		</select>
		</form>
		<?php 
	}
	//************************************************************************************
?>
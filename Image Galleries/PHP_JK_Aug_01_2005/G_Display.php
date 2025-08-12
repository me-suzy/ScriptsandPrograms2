<?php
	Require("Includes/i_Includes.php");
	Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	
	$aGalleryInfo		= "";
	$iAccountUnq		= 0;
	$sSort				= "";
	$sBreadCrumb		= "";
	$bDispOwnerCol		= "";
	$bAnyChildWGallery	= False;
	$bAdmin				= False;
	$bCanCreate			= False;
	$sTextColor			= "";
	$iTtlNumItems		= "";
	$iCount				= 0;
	$sColor1			= $GLOBALS["PageBGColor"];
	$sColor2			= $GLOBALS["BGColor1"];
	$sColor3			= $GLOBALS["PageText"];
	$sColor4			= $GLOBALS["TextColor1"];
	$sTextColor			= $GLOBALS["TextColor2"];
	$sBGColor			= "";
	$iThumbWidth		= DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH");
	
	$iCategoryUnq = Trim(Request("iCategoryUnq"));
	If ( $iCategoryUnq == "" )
		$iCategoryUnq = "0";

	Main();
	Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*	Display the galleries and the links to edit galleries and edit/view images.		*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iLoginAccountUnq;
		Global $iTableWidth;
		Global $iColorScheme;
		Global $iTextScheme;
		Global $iAccountUnq;
		Global $sSort;
		Global $sBreadCrumb;
		Global $iCategoryUnq;
		Global $bHasAccount;
		Global $bDispOwnerCol;
		Global $bAdmin;
		Global $bCanCreate;
		Global $sTextColor;
		Global $iTtlNumItems;
		Global $iCount;
		Global $sColor1;
		Global $sColor2;
		Global $sColor3;
		Global $sColor4;
		Global $sTextColor;
		Global $sBGColor;
		Global $iThumbWidth;
		Global $iDBLoc;
		Global $iNumPerPage;

		$iCount			= 0;
		$iNumPerPage	= Trim(Request("iNumPerPage"));
		$iDBLoc			= Trim(Request("iDBLoc"));
		$iTtlNumItems	= Trim(Request("iTtlNumItems"));
		$sSort			= Trim(Request("sSort"));
		$iAccountUnq	= Trim(Request("iAccountUnq"));
		$bDispOwnerCol	= strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_OWNER_COL")));
		$bAdmin			= ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL");
		$bCanCreate		= ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY");
		
		G_STRUCTURE_CatBreadcrumbs($sBreadCrumb, $iCategoryUnq, -1, -1);
		
		If ( $iAccountUnq == "" )
			$iAccountUnq = $iLoginAccountUnq;
		If ( $iNumPerPage == "" )
			$iNumPerPage = 20;

		If ( ( is_null($iDBLoc) ) || ( $iDBLoc == "" ) ) {
			$iDBLoc = 0;
		}Else{
			If ( $iDBLoc < 0 )
				$iDBLoc = 0;
		}
		
		If ( $iTtlNumItems == "" ) {
			// must get everything :(
			$iTtlNumItems = GetAll();
		}Else{
			// get as few as possible
			GetFew();
		}

		?>
		<script language='JavaScript1.2' type='text/javascript'>

			function PaginationLink(sQueryString){
				document.location = "G_Display.php?<?=DOMAIN_Link("G")?>" + sQueryString;
			}

		</script>
		<table width=<?=$iTableWidth?> cellpadding=0 cellspacing=0 border=0 class='TablePage'>
			<tr>
				<td>
					<?php 
					If ( $bHasAccount ) {
						If ( DOMAIN_Has_RemoteHost() ) {
							G_LINK_Suggest_Category();
							echo "<br>";
							G_LINK_Subscribe_Category($iCategoryUnq);
						}
					}
					?>
				</td>
				<td align=right>
					<?php DisplayCategoryDropDown($iCategoryUnq);?>
					<?php DisplayPerPageDropDown();?>
				</td>
			</tr>
			<tr>
				<td colspan=2>
					<?php 
					If ( $sBreadCrumb != "" )
						Echo "<a href = 'index.php?" . DOMAIN_Link("G") . "' class='MediumNavPage'>Galleries</a> <img src = '" . G_STRUCTURE_DI("Arrow.gif", $GLOBALS["COLORBASED"]) . "'> " . $sBreadCrumb;
					?>
				</td>
			</tR>
			<?php If ( $iTtlNumItems > $iNumPerPage ) {?>
			<tr>
				<td colspan=2>
					<?php PrintRecordsetNav( "G_Display.php", "$sSort=" . $sSort . "&iNumPerPage=" . $iNumPerPage . "&iCategoryUnq=" . $iCategoryUnq, "Galleries" ); ?>
				</td>
			</tr>
			<?php }?>
		</table>

		<?php 
		If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_GDISPLAY_COLUMNS"))) == "TWO" ) {
			DoubleColumn();
		}Else{
			SingleColumn();
		}
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function SingleColumn()
	{
		Global $aVariables;
		Global $aValues;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iNumPerPage;
		Global $iCategoryUnq;
		Global $sSort;
		Global $iColorScheme;
		Global $iTextScheme;
		Global $aGalleryInfo;
		Global $iTableWidth;
		Global $bDispOwnerCol;
		Global $sColor1;
		Global $sColor2;
		Global $sColor3;
		Global $sColor4;
		Global $sTextColor;
		Global $sBGColor;
		Global $iCount;
		Global $iThumbWidth;

		$sTemp = "";
		
		G_STRUCTURE_HeaderBar_ReallySpecific("GalleriesHead.gif", "", "", "", "Galleries");
		?>
		<table cellpadding=5 width=<?=$iTableWidth?> cellspacing = 0 border = 0 class='TablePage_Boxed'>
			<tr>
				<td background='<?=G_STRUCTURE_DI("SubheaderShadow.gif", $GLOBALS["COLORBASED"])?>' colspan=2><font color='<?=$sTextColor?>'><b>Sort images by: </b>
					<?php 
					$aVariables[0] = "sSort";
					$aVariables[1] = "iTtlNumItems";
					$aVariables[2] = "iDBLoc";
					$aVariables[3] = "iNumPerPage";
					$aVariables[4] = "iCategoryUnq";
					$aValues[1] = $iTtlNumItems;
					$aValues[2] = $iDBLoc;
					$aValues[3] = $iNumPerPage;
					$aValues[4] = $iCategoryUnq;
					If ( $sSort == "Name_A" ) {
						$aValues[0]	= "Name_D";
						$sTemp		= "<img src = '" . G_STRUCTURE_DI("UpArrow.gif", $GLOBALS["COLORBASED"]) . "' width = 16 height = 14 alt = 'Sorted by gallery name ascending.'>";
					}ElseIf ( $sSort == "Name_D" ) {
						$aValues[0]	= "Name_A";
						$sTemp		= "<img src = '" . G_STRUCTURE_DI("DownArrow.gif", $GLOBALS["COLORBASED"]) . "' width = 16 height = 14 alt = 'Sorted by gallery name descending.'>";
					}Else{
						$aValues[0]	= "Name_A";
					}
					Echo "&nbsp;<a href='G_Display.php?" . DOMAIN_Link("G") . "' class='MediumNav2'>Gallery</a>&nbsp;" . $sTemp;
					$sTemp = "";
					?>
				</td>
				<td background='<?=G_STRUCTURE_DI("SubheaderShadow.gif", $GLOBALS["COLORBASED"])?>'><font color='<?=$sTextColor?>'>&nbsp;<b><font size=-2><center>Images&nbsp;</b></td>
				<?php If ( $bDispOwnerCol == "YES" ) {?>
				<td background='<?=G_STRUCTURE_DI("SubheaderShadow.gif", $GLOBALS["COLORBASED"])?>'>
					<?php 
					If ( $sSort == "UserName_A" ) {
						$aValues[0]	= "UserName_D";
						$sTemp		= "<img src = '" . G_STRUCTURE_DI("UpArrow.gif", $GLOBALS["COLORBASED"]) . "' width = 16 height = 14 alt = 'Sorted by user name ascending.'>";
					}ElseIf ( $sSort == "UserName_D" ) {
						$aValues[0]	= "UserName_A";
						$sTemp		= "<img src = '" . G_STRUCTURE_DI("DownArrow.gif", $GLOBALS["COLORBASED"]) . "' width = 16 height = 14 alt = 'Sorted by user name descending.'>";
					}Else{
						$aValues[0]	= "UserName_A";
					}
					Echo "&nbsp;<center><a href='G_Display.php?" . DOMAIN_Link("G") . "' class='MediumNav2'>Owner</a>&nbsp;" . $sTemp;
					?>
				</td>
				<?php }?>
			</tr>
			<?php 
			
			DOMAIN_Link_Clear();
			If ( $iTtlNumItems > 0 ) {
				If ( $iCount > $iNumPerPage )
					$iCount = $iNumPerPage;
				For ( $x = 0; $x < $iCount; $x++)
				{
					If ( $sBGColor == $sColor1 ) {
						$sBGColor	= $sColor2;
						$sTextColor	= $sColor4;
						$sLinkColor	= "MediumNav1";
					}Else{
						$sBGColor	= $sColor1;
						$sTextColor	= $sColor3;
						$sLinkColor	= "MediumNavPage";
					}
					$sNumImages = GetNumImages($aGalleryInfo[0][$x]);
					?>
					<tr>
						<td valign=top width=<?=$iThumbWidth?>>
							<table cellpadding=0 cellspacing=0 border=0 class='TablePage_Boxed'><tr><td><a href='ThumbnailView.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$aGalleryInfo[0][$x]?>&iCategoryUnq=<?=$aGalleryInfo[5][$x]?>'><?php DisplayThumb($aGalleryInfo[1][$x], $aGalleryInfo[0][$x]); ?></a></td></tr></table>
						</td>
						<td valign=top>
							<a href='ThumbnailView.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$aGalleryInfo[0][$x]?>&iCategoryUnq=<?=$aGalleryInfo[5][$x]?>' class='<?=$sLinkColor?>'><?=$aGalleryInfo[2][$x]?></a><br>
							<?=$aGalleryInfo[3][$x]?>
						</td>
						<td valign=top align=center>
							<?=$sNumImages?>&nbsp;
							<?php 
							$sQuery = "SELECT AddDate FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $aGalleryInfo[0][$x] . " ORDER BY AddDate DESC";
							DB_Query("SET ROWCOUNT 1");
							$rsRecordSet = DB_Query($sQuery);
							DB_Query("SET ROWCOUNT 0");
							if ( $rsRow = DB_Fetch($rsRecordSet) ){
								$sTemp = Trim(DOMAIN_Conf("IMAGEGALLERY_NEWIMAGE_DAYS"));
								If ( ! is_numeric($sTemp) )
									$sTemp = 2;
								If ( DateDiff("d", $rsRow["AddDate"], time()) < $sTemp )
									Echo "&nbsp;<img src='" . G_STRUCTURE_DI("NewImage.gif", $GLOBALS["SCHEMEBASED"]) . "' alt=\" NEW! \" border=0>";
							}
							
							?>
						</td>
						<?php If ( $bDispOwnerCol == "YES" ) {?>
						<td valign=top align=center>
							<?php 
							$sDispOwnerName = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_OWNER_INFO")));
							If ( $sDispOwnerName == "YES" ) {
								Echo "<a href='G_Display.php?iAccountUnq=" . $aGalleryInfo[1][$x] . "&iCategoryUnq=-1' class='MediumNavPage'>" . Trim(ACCNT_UserName($aGalleryInfo[1][$x])) . "</a>&nbsp;";
							}Else{
								Echo Trim(ACCNT_UserName($aGalleryInfo[1][$x])) . "&nbsp;";
							}
							?>
						</td>
						<?php }?>
					</tr>
					<?php 
				}
				Echo "</table>";
				PrintRecordsetNav( "G_Display.php", "$sSort=" . $sSort . "&iNumPerPage=" . $iNumPerPage . "&iCategoryUnq=" . $iCategoryUnq, "Galleries" );
			}Else{
				If ( $bDispOwnerCol == "YES" ) {
					Echo "<tr><td colspan=4>";
				}Else{
					Echo "<tr><td colspan=3>";
				}
				Echo "</td></tr></table>";
				DOMAIN_Message("--No galleries to display--", "ERROR");
			}
			
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DoubleColumn()
	{
		Global $aVariables;
		Global $aValues;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iNumPerPage;
		Global $iCategoryUnq;
		Global $sSort;
		Global $iColorScheme;
		Global $iTextScheme;
		Global $aGalleryInfo;
		Global $bDispOwnerCol;
		Global $iCount;
		Global $sColor1;
		Global $sColor2;
		Global $sColor3;
		Global $sColor4;
		Global $sTextColor;
		Global $sBGColor;
		Global $iThumbWidth;
		Global $iTableWidth;
		
		G_STRUCTURE_HeaderBar_ReallySpecific("GalleriesHead.gif", "", "", "", "Galleries");?>
		<table cellpadding=5 width=<?=$iTableWidth?> cellspacing = 0 border = 0 class='TablePage'>
			<tr>
				<td background='<?=G_STRUCTURE_DI("SubheaderShadow.gif", $GLOBALS["COLORBASED"])?>' colspan=2><font color='<?=$sTextColor?>'><b>Sort images by: </b>
					<?php 
					$aVariables[0] = "sSort";
					$aVariables[1] = "iTtlNumItems";
					$aVariables[2] = "iDBLoc";
					$aVariables[3] = "iNumPerPage";
					$aVariables[4] = "iCategoryUnq";
					$aValues[1] = $iTtlNumItems;
					$aValues[2] = $iDBLoc;
					$aValues[3] = $iNumPerPage;
					$aValues[4] = $iCategoryUnq;
					If ( $sSort = "Name_A" ) {
						$aValues[0]	= "Name_D";
						$sTemp		= "<img src = '" . G_STRUCTURE_DI("UpArrow.gif", $GLOBALS["COLORBASED"]) . "' width = 16 height = 14 alt = 'Sorted by gallery name ascending.'>";
					}ElseIf ( $sSort = "Name_D" ) {
						$aValues[0]	= "Name_A";
						$sTemp		= "<img src = '" . G_STRUCTURE_DI("DownArrow.gif", $GLOBALS["COLORBASED"]) . "' width = 16 height = 14 alt = 'Sorted by gallery name descending.'>";
					}Else{
						$aValues[0]	= "Name_A";
					}
					Echo "&nbsp;<a href='G_Display.php?" . DOMAIN_Link("G") . "' class='MediumNav2'>Gallery</a>&nbsp;" . $sTemp;
					$sTemp = "";
					?>
				</td>
				<?php If ( $bDispOwnerCol == "YES" ) {?>
				<td background='<?=G_STRUCTURE_DI("SubheaderShadow.gif", $GLOBALS["COLORBASED"])?>'>
					<?php 
					If ( $sSort == "UserName_A" ) {
						$aValues[0]	= "UserName_D";
						$sTemp		= "<img src = '" . G_STRUCTURE_DI("UpArrow.gif", $GLOBALS["COLORBASED"]) . "' width = 16 height = 14 alt = 'Sorted by user name ascending.'>";
					}ElseIf ( $sSort == "UserName_D" ) {
						$aValues[0]	= "UserName_A";
						$sTemp		= "<img src = '" . G_STRUCTURE_DI("DownArrow.gif", $GLOBALS["COLORBASED"]) . "' width = 16 height = 14 alt = 'Sorted by user name descending.'>";
					}Else{
						$aValues[0]	= "UserName_A";
					}
					Echo "&nbsp;<center><a href='G_Display.php?" . DOMAIN_Link("G") . "' class='MediumNav2'>Owner</a>&nbsp;" . $sTemp;
					DOMAIN_Link_Clear();
					?>
				</td>
				<?php }?>
				<td background='<?=G_STRUCTURE_DI("SubheaderShadow.gif", $GLOBALS["COLORBASED"])?>' colspan=2>&nbsp;</td>
				<?php If ( $bDispOwnerCol == "YES" ) {?>
				<td background='<?=G_STRUCTURE_DI("SubheaderShadow.gif", $GLOBALS["COLORBASED"])?>'><center><b>Owner</td>
				<?php }?>
			</tr>
			<?php 
			
			DOMAIN_Link_Clear();
			If ( $iTtlNumItems > 0 ) {
				If ( $iCount > $iNumPerPage ) 
					$iCount = $iNumPerPage;
				For ( $x = 0; $x < $iCount; $x++)
				{
					If ($sBGColor == $sColor1) {
						$sBGColor	= $sColor2;
						$sTextColor	= $sColor4;
						$sLinkColor	= "MediumNav1";
					}Else{
						$sBGColor	= $sColor1;
						$sTextColor	= $sColor3;
						$sLinkColor	= "MediumNavPage";
					}
					$sNumImages = GetNumImages($aGalleryInfo[0][$x]);
					?>
					<tr>
						<td valign=top width=<?=$iThumbWidth?>>
							<table cellpadding=0 cellspacing=0 border=0 class='TablePage_Boxed'><tr><td><a href='ThumbnailView.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$aGalleryInfo[0][$x]?>&iCategoryUnq=<?=$aGalleryInfo[5][$x]?>'><?php DisplayThumb($aGalleryInfo[1][$x], $aGalleryInfo[0][$x]);?></a></td></tr></table>
						</td>
						<td valign=top>
							<a href='ThumbnailView.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$aGalleryInfo[0][$x]?>&iCategoryUnq=<?=$aGalleryInfo[5][$x]?>' class='<?=$sLinkColor?>'><?=$aGalleryInfo[2][$x]?> (<?=$sNumImages?>)</a>
							<?php 
							$sQuery = "SELECT AddDate FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $aGalleryInfo[0][$x] . " ORDER BY AddDate DESC";
							DB_Query("SET ROWCOUNT 1");
							$rsRecordSet = DB_Query($sQuery);
							DB_Query("SET ROWCOUNT 0");
							if ( $rsRow = DB_Fetch($rsRecordSet) ){
								$sTemp = Trim(DOMAIN_Conf("IMAGEGALLERY_NEWIMAGE_DAYS"));
								If ( ! is_numeric($sTemp) )
									$sTemp = 2;
								If ( DateDiff("d", $rsRow["AddDate"], time()) < $sTemp )
									Echo "&nbsp;<img src='" . G_STRUCTURE_DI("NewImage.gif", $GLOBALS["SCHEMEBASED"]) . "' alt=\" NEW! \" border=0>";
							}
							
							?>
							<br>
							<?=$aGalleryInfo[3][$x]?>
						</td>
						<?php If ( $bDispOwnerCol == "YES" ) {?>
						<td valign=top align=center>
							<?php 
							$sDispOwnerName = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_OWNER_INFO")));
							If ( $sDispOwnerName == "YES" ) {
								Echo "<a href='G_Display.php?iAccountUnq=" . $aGalleryInfo[1][$x] . "&iCategoryUnq=-1' class='MediumNavPage'>" . Trim(ACCNT_UserName($aGalleryInfo[1][$x])) . "</a>&nbsp;";
							}Else{
								Echo Trim(ACCNT_UserName($aGalleryInfo[1][$x])) . "&nbsp;";
							}
							?>
						</td>
						<?php }?>
					<?php 
					If ( $x < $iCount - 1 ) {
						$x++;
						$sNumImages = GetNumImages($aGalleryInfo[0][$x]);
						?>
						<td valign=top width=<?=$iThumbWidth?>>
							<table cellpadding=0 cellspacing=0 border=0 class='TablePage_Boxed'><tr><td><a href='ThumbnailView.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$aGalleryInfo[0][$x]?>&iCategoryUnq=<?=$aGalleryInfo[5][$x]?>'><?php DisplayThumb($aGalleryInfo[1][$x], $aGalleryInfo[0][$x]);?></a></td></tr></table>
						</td>
						<td valign=top>
							<a href='ThumbnailView.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$aGalleryInfo[0][$x]?>&iCategoryUnq=<?=$aGalleryInfo[5][$x]?>' class='<?=$sLinkColor?>'><?=$aGalleryInfo[2][$x]?> (<?=$sNumImages?>)</a>
							<?php 
							$sQuery = "SELECT AddDate FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $aGalleryInfo[0][$x] . " ORDER BY AddDate DESC";
							DB_Query("SET ROWCOUNT 1");
							$rsRecordSet = DB_Query($sQuery);
							DB_Query("SET ROWCOUNT 0");
							if ( $rsRow = DB_Fetch($rsRecordSet) ){
								$sTemp = Trim(DOMAIN_Conf("IMAGEGALLERY_NEWIMAGE_DAYS"));
								If ( ! is_numeric($sTemp) )
									$sTemp = 2;
								If ( DateDiff("d", $rsRow["AddDate"], time()) < $sTemp )
									Echo "&nbsp;<img src='" . G_STRUCTURE_DI("NewImage.gif", $GLOBALS["SCHEMEBASED"]) . "' alt=\" NEW! \" border=0>";
							}
							
							?>
							<br>
							<?=$aGalleryInfo[3][$x]?>
						</td>
						<?php If ( $bDispOwnerCol == "YES" ) {?>
						<td valign=top align=center>
							<?php 
							$sDispOwnerName = strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_OWNER_INFO")));
							If ( $sDispOwnerName == "YES" ) {
								Echo "<a href='G_Display.php?iAccountUnq=" . $aGalleryInfo[1][$x] . "&iCategoryUnq=-1' class='MediumNavPage'>" . Trim(ACCNT_UserName($aGalleryInfo[1][$x])) . "</a>&nbsp;";
							}Else{
								Echo Trim(ACCNT_UserName($aGalleryInfo[1][$x])) . "&nbsp;";
							}
							?>
						</td>
						<?php }
					}Else{
						Echo "<td></td><td></td>";
						If ( $bDispOwnerCol == "YES" )
							Echo "<td></td>";
					}
					Echo "</tr>";
				}
				Echo "</table>";
				PrintRecordsetNav( "G_Display.php", "$sSort=" . $sSort . "&iNumPerPage=" . $iNumPerPage . "&iCategoryUnq=" . $iCategoryUnq, "Galleries" );
			}Else{
				Echo "</td></tr></table>";
				DOMAIN_Message("--No galleries to display--", "ERROR");
			}
			
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayCategoryDropDown($iCategoryUnq)
	{
		Global $iDBLoc;
		Global $iTtlNumItems;
		Global $iNumPerPage;
		Global $sSort;
		Global $bAnyChildWGallery;
		
		?>
		<form name='G_Display' action='index.php' class='PageForm'>
		<input type='hidden' name='iDBLoc' value='<?=$iDBLoc?>'>
		<input type='hidden' name='iTtlNumItems' value='<?=$iTtlNumItems?>'>
		<input type='hidden' name='iNumPerPage' value='<?=$iNumPerPage?>'>
		<input type='hidden' name='$sSort' value='<?=$sSort?>'>
		<b>Category:</b>
		<select name = "iParentUnq" onChange='document.G_Display.submit();'>
			<?php 
			$sQuery		 = "SELECT * FROM IGCategories (NOLOCK) WHERE Parent = 0 ORDER BY Position";
			$rsRecordSet = DB_Query($sQuery);
			if ( DB_NumRows($rsRecordSet) > 0 )
			{
				Echo "<option value='" . $iCategoryUnq . "'>Select Category</option>";
				$bAnyChildWGallery = FALSE;
				AnyChildWGallery("0");
				If ( $bAnyChildWGallery ) {
					If ( $iCategoryUnq == "0" ) {
						Echo "<option value='0' selected>Galleries not in a category</option>";
					}Else{
						Echo "<option value='0'>Galleries not in a category</option>";
					}
				}

				while ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$bAnyChildWGallery = FALSE;
					AnyChildWGallery($rsRow["CategoryUnq"]);
					If ( $bAnyChildWGallery ) {
						$sName = htmlentities($rsRow["Name"]);
						If ( $iCategoryUnq == $rsRow["CategoryUnq"] ) {
							Echo "<option value='" . $rsRow["CategoryUnq"] . "' Selected>" . $sName . "</option>";
						}Else{
							Echo "<option value='" . $rsRow["CategoryUnq"] . "'>" . $sName . "</option>";
						}
					}
				}
			}Else{
				Echo "<option value='0'>Galleries not in a category</option>";
			}
			
			?>
		</select>
		</form>
		<?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is a recursive function to search for any category or it's children who	*
	//*		have a gallery -- stops at the first gallery it finds and returns true.		*
	//*																					*
	//************************************************************************************
	Function AnyChildWGallery($iCategoryUnq)
	{
		Global $iLoginAccountUnq;
		Global $PUBLIC_GALLERIES;
		Global $bAnyChildWGallery;
		Global $bAdmin;
		Global $bCanCreate;
		
		$sQuery		 = "SELECT CategoryUnq FROM IGCategories (NOLOCK) WHERE Parent = " . $iCategoryUnq;
		$rsRecordSet1 = DB_Query($sQuery);
		if ( DB_NumRows($rsRecordSet1) > 0 ){
			while ( ( $rsRow1 = DB_Fetch($rsRecordSet1) ) && ( ! $bAnyChildWGallery ) )
			{
				If ( $bAdmin ) {
					// they can see ALL galleries for this domain
					$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $rsRow1["CategoryUnq"] . " AND IG.GalleryUnq = G.GalleryUnq";
				}ElseIf ( $bCanCreate ) {
					// they can see their and public galleries
					$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE (G.AccountUnq = " . $iLoginAccountUnq . " OR G.Visibility = '" . $PUBLIC_GALLERIES . "') AND G.CategoryUnq = " . $rsRow1["CategoryUnq"] . " AND IG.GalleryUnq = G.GalleryUnq";
				}Else{
					// they can only see public galleries
					$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.Visibility = '" . $PUBLIC_GALLERIES . "' AND G.CategoryUnq = " . $rsRow1["CategoryUnq"] . " AND IG.GalleryUnq = G.GalleryUnq";
				}
				DB_Query("SET ROWCOUNT 1");
				$rsRecordSet2 = DB_Query($sQuery);
				DB_Query("SET ROWCOUNT 0");
				If ( $rsRow2 = DB_Fetch($rsRecordSet2) )
					$bAnyChildWGallery = True;

				If ( ! $bAnyChildWGallery ) 
					AnyChildWGallery($rsRow1["CategoryUnq"]);
			}
		}Else{
			// no more child categories, so just check the current (leaf) one
			$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $iCategoryUnq . " AND IG.GalleryUnq = G.GalleryUnq";
			DB_Query("SET ROWCOUNT 1");
			$rsRecordSet2 = DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			If ( $rsRow2 = DB_Fetch($rsRecordSet2) )
				$bAnyChildWGallery = TRUE;
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayPerPageDropDown()
	{
		Global $iDBLoc;
		Global $iTtlNumItems;
		Global $sSort;
		Global $iCategoryUnq;
		Global $iNumPerPage;
		
		?>
		<form name='G_Display_PerPage' action='G_Display.php' class='PageForm'>
		<input type='hidden' name='iDBLoc' value='<?=$iDBLoc?>'>
		<input type='hidden' name='iTtlNumItems' value='<?=$iTtlNumItems?>'>
		<input type='hidden' name='$sSort' value='<?=$sSort?>'>
		<input type='hidden' name='iCategoryUnq' value='<?=$iCategoryUnq?>'>
		<b>Galleries Per Page: </b>
		<select name = "iNumPerPage" onChange='document.G_Display_PerPage.submit();'>
			<option value='5' <?php If ( $iNumPerPage == 5 ) Echo "selected";?>>5</option>
			<option value='10' <?php If ( $iNumPerPage == 10 ) Echo "selected";?>>10</option>
			<option value='15' <?php If ( $iNumPerPage == 15 ) Echo "selected";?>>15</option>
			<option value='20' <?php If ( $iNumPerPage == 20 ) Echo "selected";?>>20</option>
			<option value='25' <?php If ( $iNumPerPage == 25 ) Echo "selected";?>>25</option>
			<option value='30' <?php If ( $iNumPerPage == 30 ) Echo "selected";?>>30</option>
			<option value='35' <?php If ( $iNumPerPage == 35 ) Echo "selected";?>>35</option>
			<option value='40' <?php If ( $iNumPerPage == 40 ) Echo "selected";?>>40</option>
			<option value='45' <?php If ( $iNumPerPage == 45 ) Echo "selected";?>>45</option>
			<option value='50' <?php If ( $iNumPerPage == 50 ) Echo "selected";?>>50</option>
			<option value='55' <?php If ( $iNumPerPage == 55 ) Echo "selected";?>>55</option>
			<option value='60' <?php If ( $iNumPerPage == 60 ) Echo "selected";?>>60</option>
			<option value='65' <?php If ( $iNumPerPage == 65 ) Echo "selected";?>>65</option>
			<option value='70' <?php If ( $iNumPerPage == 70 ) Echo "selected";?>>70</option>
			<option value='75' <?php If ( $iNumPerPage == 75 ) Echo "selected";?>>75</option>
			<option value='80' <?php If ( $iNumPerPage == 80 ) Echo "selected";?>>80</option>
			<option value='85' <?php If ( $iNumPerPage == 85 ) Echo "selected";?>>85</option>
			<option value='90' <?php If ( $iNumPerPage == 90 ) Echo "selected";?>>90</option>
			<option value='95' <?php If ( $iNumPerPage == 95 ) Echo "selected";?>>95</option>
			<option value='100' <?php If ( $iNumPerPage == 100 ) Echo "selected";?>>100</option>
		</select>
		</form>
		<?php 
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function GetNumImages($iGalleryUnq)
	{
		$sQuery			= "SELECT COUNT(*) FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow[0];
			
		Return 0;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Try to display the thumbnail.													*
	//*																					*
	//************************************************************************************
	Function DisplayThumb($sAccountUnq, $iGalleryUnq)
	{
		Global $iThumbWidth;
		Global $sSiteURL;
		
		$sQuery			= "SELECT I.Thumbnail, IG.PrimaryG FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK) WHERE IG.GalleryUnq = " . $iGalleryUnq . " AND G.GalleryUnq = " . $iGalleryUnq . " AND IG.ImageUnq = I.ImageUnq AND I.Thumbnail != '' ORDER BY ImageNum";
		DB_Query("SET ROWCOUNT 1");
		$rsRecordSet = DB_Query($sQuery);
		DB_Query("SET ROWCOUNT 0");
		If ( $rsRow = DB_Fetch($rsRecordSet) )
			?>
			<img src = "<?=$sSiteURL?>/Attachments/DispThumb.php?sAccountUnq=<?=$sAccountUnq?>&sThumbnail=<?=Trim($rsRow["Thumbnail"])?>&iGalleryUnq=<?=$rsRow["PrimaryG"]?>" width=<?=$iThumbWidth?> border=0>
			<?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	GetAll() and GetFew() allow the system to count the number of galleries			*
	//*		the user can see (because of gallery locking) for use with pagination.		*
	//*		Since we only need to get the count . check locking the first time and		*
	//*		because that takes a long time, I created another function to just get and	*
	//*		check locking for the ones on the current page (assuming the locking doesnt	*
	//*		change for the galleries not on the current page.							*
	//*																					*
	//************************************************************************************
	Function GetAll()
	{
		Global $sSort;
		Global $iLoginAccountUnq;
		Global $PUBLIC_GALLERIES;
		Global $iAccountUnq;
		Global $iCategoryUnq;
		Global $aGalleryInfo;
		Global $iCount;
		
		$iCount = 0;
		
		If ( $sSort == "" ) {
			$sTemp = "G.Position";
		}Else{
			If ( $sSort == "Name_A" ) {
				$sTemp = "G.Name ASC";
			}ElseIf ( $sSort == "Name_D" ) {
				$sTemp = "G.Name DESC";
			}ElseIf ( $sSort == "UserName_A" ) {
				$sTemp = "G.UserName ASC";
			}ElseIf ( $sSort == "UserName_D" ) {
				$sTemp = "G.UserName DESC";
			}
		}

		If ( $iCategoryUnq == "-1" ) {
			// they want to see "My Galleries" -- so only display galleries they have created - from ANY category
			// or they want to see a specific users list of galleries
			$sQuery = "SELECT DISTINCT G.GalleryUnq, G.AccountUnq, G.Name, G.CategoryUnq, G.Position, G.UserName FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.AccountUnq = " . $iAccountUnq . " AND G.GalleryUnq = IG.GalleryUnq ORDER BY " . $sTemp;
		}Else{
			If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				// they can see ALL galleries for this domain
				$sQuery = "SELECT DISTINCT G.GalleryUnq, G.AccountUnq, G.Name, G.CategoryUnq, G.Position, G.UserName FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq ORDER BY " . $sTemp;
			}ElseIf ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) {
				// they can see their and public galleries
				$sQuery = "SELECT DISTINCT G.GalleryUnq, G.AccountUnq, G.Name, G.CategoryUnq, G.Position, G.UserName FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE (G.AccountUnq = " . $iLoginAccountUnq . " OR G.Visibility = '" . $PUBLIC_GALLERIES . "') AND G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq ORDER BY " . $sTemp;
			}Else{
				// they can only see public galleries
				$sQuery = "SELECT DISTINCT G.GalleryUnq, G.AccountUnq, G.Name, G.CategoryUnq, G.Position, G.UserName FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.Visibility = '" . $PUBLIC_GALLERIES . "' AND G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq ORDER BY " . $sTemp;
			}
		}
		$rsRecordSet = DB_Query($sQuery);
		if ( DB_NumRows($rsRecordSet) > 0 )
		{
			while ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				If ( G_ADMINISTRATION_AccessLocked($rsRow["GalleryUnq"], $rsRow["AccountUnq"]) ) {
					$aGalleryInfo[0][$iCount] = $rsRow["GalleryUnq"];
					$aGalleryInfo[1][$iCount] = $rsRow["AccountUnq"];
					$aGalleryInfo[2][$iCount] = Trim($rsRow["Name"]);
					$sQuery = "SELECT Description FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $aGalleryInfo[0][$iCount];
					$rsRecordSet2 = DB_Query($sQuery);
					if ( $rsRow2 = DB_Fetch($rsRecordSet2) )
						$aGalleryInfo[3][$iCount] = Trim($rsRow2["Description"]);
					$aGalleryInfo[4][$iCount] = Trim($rsRow["UserName"]);
					$aGalleryInfo[5][$iCount] = Trim($rsRow["CategoryUnq"]);	// need this when they are on "My Galleries" so we know which category the gallery is from
					$iCount++;
				}
			}
			Return $iCount;
		}
		Return 0;		
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function GetFew()
	{
		Global $sSort;
		Global $iLoginAccountUnq;
		Global $PUBLIC_GALLERIES;
		Global $iAccountUnq;
		Global $iCategoryUnq;
		Global $aGalleryInfo;
		Global $iCount;
		Global $iDBLoc;
		Global $iNumPerPage;
		Global $iAccountUnq;
		
		$iCount = 0;
		
		If ( $sSort == "" ) {
			$sTemp = "G.Position";
		}Else{
			If ( $sSort == "Name_A" ) {
				$sTemp = "G.Name ASC";
			}ElseIf ( $sSort == "Name_D" ) {
				$sTemp = "G.Name DESC";
			}ElseIf ( $sSort == "UserName_A" ) {
				$sTemp = "G.UserName ASC";
			}ElseIf ( $sSort == "UserName_D" ) {
				$sTemp = "G.UserName DESC";
			}
		}

		If ( $iCategoryUnq == "-1" ) {
			// they want to see "My Galleries" -- so only display galleries they have created - from ANY category
			// or they want to see a specific users list of galleries
			$sQuery = "SELECT DISTINCT G.GalleryUnq, G.AccountUnq, G.Name, G.CategoryUnq, G.Position, G.UserName FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.AccountUnq = " . $iAccountUnq . " AND G.GalleryUnq = IG.GalleryUnq ORDER BY " . $sTemp;
		}Else{
			If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				// they can see ALL galleries for this domain
				$sQuery = "SELECT DISTINCT G.GalleryUnq, G.AccountUnq, G.Name, G.CategoryUnq, G.Position, G.UserName FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq ORDER BY " . $sTemp;
			}ElseIf ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) {
				// they can see their and public galleries
				$sQuery = "SELECT DISTINCT G.GalleryUnq, G.AccountUnq, G.Name, G.CategoryUnq, G.Position, G.UserName FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE (G.AccountUnq = " . $iLoginAccountUnq . " OR G.Visibility = '" . $PUBLIC_GALLERIES . "') AND G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq ORDER BY " . $sTemp;
			}Else{
				// they can only see public galleries
				$sQuery = "SELECT DISTINCT G.GalleryUnq, G.AccountUnq, G.Name, G.CategoryUnq, G.Position, G.UserName FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.Visibility = '" . $PUBLIC_GALLERIES . "' AND G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq ORDER BY " . $sTemp;
			}
		}
		DB_Query("SET ROWCOUNT " . ($iDBLoc + $iNumPerPage));
		$rsRecordSet = DB_Query($sQuery);
		DB_Query("SET ROWCOUNT 0");
		if ( DB_NumRows($rsRecordSet) > 0 )
		{
			For ( $x = 1; $x <= $iDBLoc; $x++)
				DB_Fetch($rsRecordSet);

			While ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				If ( G_ADMINISTRATION_AccessLocked($rsRow["GalleryUnq"], $rsRow["AccountUnq"]) ) {
					$aGalleryInfo[0][$iCount] = $rsRow["GalleryUnq"];
					$aGalleryInfo[1][$iCount] = $rsRow["AccountUnq"];
					$aGalleryInfo[2][$iCount] = Trim($rsRow["Name"]);
					$sQuery = "SELECT Description FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $aGalleryInfo[0][$iCount];
					$rsRecordSet2 = DB_Query($sQuery);
					if ( $rsRow2 = DB_Fetch($rsRecordSet2) )
						$aGalleryInfo[3][$iCount] = Trim($rsRow2["Description"]);

					$aGalleryInfo[4][$iCount] = Trim($rsRow["UserName"]);
					$aGalleryInfo[5][$iCount] = Trim($rsRow["CategoryUnq"]);	// need this when they are on "My Galleries" so we know which category the gallery is from
					$iCount++;
				}
			}
		}
	}
	//************************************************************************************
?>
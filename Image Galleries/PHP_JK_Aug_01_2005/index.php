<?php
	Require("Includes/i_Includes.php");
	Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	
	$bAnyChildWGallery	= False;
	$bAdmin				= False;
	$bCanCreate			= False;
	$sBreadCrumb		= "";
	$iParentUnq			= "";
	
	$iCategoryUnq		= "";
	$aGalleryInfo		= "";
	$iAccountUnq		= 0;
	$sSort				= "";
	$bDispOwnerCol		= "";
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

	Echo "<center>";
	If ( DOMAIN_Conf("IMAGEGALLERY_HIGHVOLUME_DISPLAY") == "YES" ) {
		DispCategoriesLargeVolume();
	}Else{
		DispCategories();
	}
	Echo "<BR>";
	Require("Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DispCategoriesLargeVolume()
	{
		Global $sBreadCrumb;
		Global $iTableWidth;
		Global $sSiteURL;
		Global $iCategoryUnq;
		
		$iWidth			= DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_WIDTH");
		$iHeight		= DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_HEIGHT");
		$sCatImageLoc	= DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_LOC");
		$sCurCatName	= "";
		
		$iParentUnq = Trim(Request("iParentUnq"));
		If ( $iParentUnq == "" )
			$iParentUnq = "0";
		$iCategoryUnq = $iParentUnq;	// this is so the functions that display the galleries will work

		G_STRUCTURE_CatBreadcrumbs($sBreadCrumb, $iParentUnq, -1, -1);
		
		?>
		<table cellpadding=5 width=<?=$iTableWidth?> cellspacing = 0 border = 0 class='TablePage'><tr><td><?php G_LINK_Category_Gallery_Map(); ?></td><td align=right><?php G_LINK_New_Image_Search()?></td></tr></table>
		<?php G_STRUCTURE_HeaderBar_ReallySpecific("CategoriesHead.gif", "", "", "", "Galleries"); ?>
		<table cellpadding = 5 cellspacing=0 border=0 width=<?=$iTableWidth?> class='TablePage_Boxed'>
		<?php If ( $sBreadCrumb != "" ) {?>
			<tr><td colspan=2 bgcolor = <?=$GLOBALS["PageBGColor"]?>><a href = 'index.php?<?php DOMAIN_Link("G");?>' class='MediumNavPage'>Galleries</a> <img src = '<?=G_STRUCTURE_DI("Arrow.gif", $GLOBALS["COLORBASED"])?>'> <?=$sBreadCrumb?></td></tr>
			<tr><td colspan=2 bgcolor = <?=$GLOBALS["PageBGColor"]?>><table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td bgcolor = <?=$GLOBALS["BorderColor1"]?>><img src='Images/Blank.gif' width=10 height=1></td></tr></table></td></tr>
		<?php }?>
			<tr>
				<td valign=top>
					<?php 
					If ( $iParentUnq != "0" ){
						?>
						<table cellpadding = 5 cellspacing=0 border=0 width=160 class='TablePage_Boxed'>
						<tr><td background='<?=G_STRUCTURE_DI("SubheaderShadow.gif", $GLOBALS["COLORBASED"])?>'><b>Categories</b></td></tr>
						<tr><td>
						<?php
						$sQuery = "SELECT * FROM IGCategories (NOLOCK) WHERE (Parent = 0) ORDER BY Position";
						$rsRecordSet = DB_Query($sQuery);
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							echo "<li><a href='" . $sSiteURL ."/index.php?iParentUnq=" . $rsRow["CategoryUnq"] . "' class='SmallNavPage'>" . $rsRow["Name"] . "</a><BR>";
						}
						?>
						</td></tr></table>
						<br>
						<?php
						$sQuery = "SELECT Name FROM IGCategories (NOLOCK) WHERE CategoryUnq = " . $iParentUnq;
						$rsRecordSet = DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							$sCurCatName = $rsRow["Name"];
						}
					}
					?>
					<table cellpadding = 5 cellspacing=0 border=0 width=160 class='TablePage_Boxed'>
					<tr><td background='<?=G_STRUCTURE_DI("SubheaderShadow.gif", $GLOBALS["COLORBASED"])?>'><b>Sponsored Links</b></td></tr></table>
					<script type="text/javascript"><!--
					google_ad_client = "pub-3125950574976235";
					google_ad_width = 160;
					google_ad_height = 600;
					google_ad_format = "160x600_as";
					google_ad_type = "text_image";
					google_ad_channel ="";
					google_color_border = "CCCCCC";
					google_color_bg = "FFFFFF";
					google_color_link = "000000";
					google_color_url = "666666";
					google_color_text = "333333";
					//--></script>
					<script type="text/javascript"
					  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
					</script>
				</td>
				<td valign=top width=100%>
					<?php
					
					If ( $iParentUnq == "0" )
					{
						$sQuery = "SELECT * FROM TextConstants (NOLOCK) WHERE TextConstants = 'HOMEPAGE_CONTENT'";
						$rsRecordSet = DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) )
							echo $rsRow["Value"];
					}
					
					DisplayFour($iParentUnq, False);

					$sQuery = "SELECT * FROM IGCategories (NOLOCK) WHERE (Parent = " . $iParentUnq . ") ORDER BY Position";
					$rsRecordSet = DB_Query($sQuery);
					If ( DB_NumRows($rsRecordSet) > 0 )
					{
						?>
						<table cellpadding=5 cellspacing=0 border=0 width=100% class='TablePage'>
						<tr>
							<td colspan=2 background='<?=G_STRUCTURE_DI("SubheaderShadow.gif", $GLOBALS["COLORBASED"])?>'>&nbsp;&nbsp;<font color='<?=$GLOBALS["TextColor2"]?>'><b><?=$sCurCatName?> Categories</b></td>
						</tr>
						<?php
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							$iCategoryUnq	= $rsRow["CategoryUnq"];
							$sDesc			= ltrim(rtrim($rsRow["Description"]));
							If ( $sDesc != "" )
								$sDesc = "<BR>" . $sDesc;

							?>
								<tr>
									<td valign=top>
										<li><a href='<?=$sSiteURL?>/index.php?iParentUnq=<?=$iCategoryUnq?>' class='MediumNavPage'><u><?=$rsRow["Name"]?></u></a>&nbsp;(<?=$rsRow["TtlImages"]?>)
										<?=$sDesc?>
										<?php
										DB_Query("SET ROWCOUNT 3");
										$sQuery = "SELECT * FROM IGCategories (NOLOCK) WHERE Parent = " . $iCategoryUnq;
										$rsRecordSet2 = DB_Query($sQuery);
										If ( DB_NumRows($rsRecordSet) > 0 )
										{
											echo "<br>";
											$sTemp = "";
											$iCount = 0;
											While ( $rsRow2 = DB_Fetch($rsRecordSet2) )
											{
												$sTemp.="<a href='" . $sSiteURL . "/index.php?iParentUnq=" . $rsRow2["CategoryUnq"] . "' class='SmallNavPage'><u>" . $rsRow2["Name"] . "</u></a>&nbsp;|&nbsp;";
												$iCount++;
											}
											If ( $iCount >= 3 )
											{
												echo substr($sTemp, 0, strlen($sTemp)-7) . " | <a href='" . $sSiteURL . "/index.php?iParentUnq=" . $iCategoryUnq . "' class='SmallNavPage'><u>more...</u></a>";
											}Else{
												echo substr($sTemp, 0, strlen($sTemp)-7);
											}
										}
										DB_Query("SET ROWCOUNT 0");
										?>
									</td>
									<td valign=top width=<?=$iWidth?>>
										<?php
										If ( $rsRow["HasImage"] == "Y" ) {
											$sFilePath = $sCatImageLoc . "/" . "CatImage_" . $iCategoryUnq . ".jpg";
											$sFilePath = str_replace("\\", "/", $sFilePath);
											$sFilePath = str_replace("//", "/", $sFilePath);
											echo "<img src='" . $sFilePath . "' alt = '" . $rsRow["Name"] . "' width=" . $iWidth . " height=" . $iHeight . " border=0>";
										}
										?>
									</td>
								</tr>
							<?php
						}
						echo "</table><BR><br>";
					}Else{
						// since it always goes back to index.php, we must check if there are no child categories
						//	and redirect them if there aren't. this is to save processing, so we don't
						//	have to check each category that's displayed on the page to see if it has
						//	a child category (if it does, then we'd have to link to G_Display.asp)
						header( 'location:' . $sSiteURL . '/G_Display.php?iCategoryUnq=' . $iParentUnq );
						ob_flush();
						exit;
					}
					
					// only display if the category DOES NOT have any galleries in it
					DisplayFour($iParentUnq, True);

					DisplayGalleries($iParentUnq);
					?>
				</td>
			</tr>
		</table>
		</td></tr></table>
		<?php
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayFour($iCategoryUnq, $bCheck)
	{
		Global $iParentUnq;
		Global $iLoginAccountUnq;

		If ( $bCheck )
		{
			$bCheck = False;
			If ( $iCategoryUnq == "-1" ) {
				// they want to see "My Galleries" -- so only display galleries they have created - from ANY category
				// or they want to see a specific users list of galleries
				$sQuery = "SELECT COUNT(*) AS NumGalleries FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.AccountUnq = " . $iAccountUnq . " AND G.GalleryUnq = IG.GalleryUnq";
			}Else{
				If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
					// they can see ALL galleries for this domain
					$sQuery = "SELECT COUNT(*) AS NumGalleries FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq";
				}ElseIf ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) {
					// they can see their and public galleries
					$sQuery = "SELECT COUNT(*) AS NumGalleries FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE (G.AccountUnq = " . $iLoginAccountUnq . " OR G.Visibility = '" . $PUBLIC_GALLERIES . "') AND G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq";
				}Else{
					// they can only see public galleries
					$sQuery = "SELECT COUNT(*) AS NumGalleries FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.Visibility = '" . $PUBLIC_GALLERIES . "' AND G.CategoryUnq = " . $iCategoryUnq . " AND G.GalleryUnq = IG.GalleryUnq";
				}
			}

			$rsRecordSet = DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				If ( $rsRow["NumGalleries"] <= 0 )
				{
					$bCheck = True;
				}
			}
		}else{
			// don't bother checking, just set it to True so the images will always display
			$bCheck = True;
		}
		
		// display four images at random
		If ( $bCheck )
		{
			DB_Query("SET ROWCOUNT 4");
			$sQuery = "SELECT * FROM Galleries (NOLOCK) ORDER BY NEWID()";
			$rsRecordSet2 = DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			If ( DB_NumRows($rsRecordSet2) > 0 )
			{
				echo "<table width=100%><tr>";
				While ( $rsRow2 = DB_Fetch($rsRecordSet2) )
				{
					?><td align=center valign=top><table cellpadding=0 cellspacing=0 border=0 class='TablePage_Boxed'><tr><td><a href='ThumbnailView.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$rsRow2["GalleryUnq"]?>&iCategoryUnq=<?=$rsRow2["CategoryUnq"]?>'><?php DisplayThumb($rsRow2["AccountUnq"], $rsRow2["GalleryUnq"]);?></a></td></tr></table></td><?php
				}
				echo "</tr></table>";
			}
			echo "<br>";
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DispCategories()
	{
		Global $bAnyChildWGallery;
		Global $iLoginAccountUnq;
		Global $PUBLIC_GALLERIES;
		Global $bAdmin;
		Global $bCanCreate;
		Global $aVariables;
		Global $aValues;
		Global $sBreadCrumb;
		Global $iParentUnq;
		Global $iTableWidth;
		Global $iColorScheme;
		Global $iLevel;
		Global $iNumCatsToDisp;
		Global $sSiteURL;
		
		$iParentUnq = Trim(Request("iParentUnq"));
		If ( $iParentUnq == "" )
			$iParentUnq = "0";

		G_STRUCTURE_CatBreadcrumbs($sBreadCrumb, $iParentUnq, -1, -1);
		
		$iNumColumns 	= DOMAIN_Conf("IMAGEGALLERY_CAT_COLUMNS");
		$bAdmin			= ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL");
		$bCanCreate		= ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY");
		$iNumCatsToDisp	= strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_NUM_CAT_LIST_ON_CATEGORY_DISPLAY")));
		$iNumGalsToDisp	= strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_NUM_GAL_LIST_ON_CATEGORY_DISPLAY")));
		$sLines			= strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_LINES_ON_CATEGORY_DISPLAY")));
		$sCatImageLoc	= strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DISP_IMAGE_ON_CATEGORY_DISPLAY")));
		$iWidth			= DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_WIDTH");
		$iHeight		= DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_HEIGHT");
		
		If ( $iNumCatsToDisp == "" ) {
			$iNumCatsToDisp = 4;
		}Else{
			$iNumCatsToDisp = $iNumCatsToDisp;
		}
		If ( $iNumGalsToDisp == "" ) {
			$iNumGalsToDisp = 4;
		}Else{
			$iNumGalsToDisp = $iNumGalsToDisp;
		}
		
		$bAnyChildWGallery = False;
		AnyChildWGallery(0);
		If ( ( $bAnyChildWGallery ) && ( $iParentUnq == "0" ) ) {
			$aCategories[0][0]	= "Galleries not in a category";
			$aCategories[1][0]	= 0;
			$aCategories[2][0]	= "N";
			$aCategories[3][0]	= 0;
			$iNumCategories		= 1;
		}Else{
			$iNumCategories		= 0;
		}

		$sQuery			= "SELECT * FROM IGCategories (NOLOCK) WHERE (Parent = " . $iParentUnq . ") ORDER BY Position";
		$rsRecordSet	= DB_Query($sQuery);
		While ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$bAnyChildWGallery = False;
			AnyChildWGallery($rsRow["CategoryUnq"]);
			If ( $bAnyChildWGallery ) {
				$aCategories[0][$iNumCategories] = $rsRow["Name"];
				$aCategories[1][$iNumCategories] = $rsRow["CategoryUnq"];
				$aCategories[2][$iNumCategories] = strtoupper(Trim($rsRow["HasImage"]));
				$aCategories[3][$iNumCategories] = $rsRow["NumChildren"];
				$iNumCategories++;
			}
		}

		// if iNumCategories = 0, but there is a iParentUnq, then check to see if this category has any galleries in it. if it does, redirect to g_display
		If ( $iNumCategories <= 0 ) {
			If ( $bAdmin ) {
				$sQuery = "SELECT GalleryUnq, AccountUnq, Name FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $iParentUnq . " ORDER BY Position";
			}Else{
				// they are not an admin so only display public galleries, or galleries they own
				$sQuery = "SELECT G.GalleryUnq, G.AccountUnq, G.Name FROM Galleries G (NOLOCK) LEFT OUTER JOIN PrivateAccounts P (NOLOCK) ON G.GalleryUnq = P.GalleryUnq WHERE (G.Visibility = " . $PUBLIC_GALLERIES . " OR (G.AccountUnq = " . $iLoginAccountUnq . ")) AND G.CategoryUnq = " . $iParentUnq . " AND P.AccountUnq = -1";
			}
			DB_Query("SET ROWCOUNT 1");
			$rsRecordSet = DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			If ( $rsRow = DB_Fetch($rsRecordSet) ){
				header( 'location:' . $sSiteURL . '/G_Display.php?iCategoryUnq=' . $iParentUnq );
				ob_flush();
				exit;
			}
		}
		
		If ( $iParentUnq == "0" )
		{
			$sQuery = "SELECT * FROM TextConstants (NOLOCK) WHERE TextConstants = 'HOMEPAGE_CONTENT'";
			$rsRecordSet = DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
				echo $rsRow["Value"];
		}
		?>
		<table cellpadding=5 width=<?=$iTableWidth?> cellspacing = 0 border = 0 class='TablePage'><tr><td><?php G_LINK_Category_Gallery_Map(); ?></td><td align=right><?php G_LINK_New_Image_Search()?></td></tr></table>
		<?php G_STRUCTURE_HeaderBar_ReallySpecific("CategoriesHead.gif", "", "", "", "Galleries"); ?>
		<table cellpadding = 5 cellspacing=0 border=0 width=<?=$iTableWidth?> class='TablePage_Boxed'>
		<?php If ( $sBreadCrumb != "" ) {?>
			<tr><td colspan=<?=$iNumColumns?> bgcolor = <?=$GLOBALS["PageBGColor"]?>><a href = 'index.php?<?php DOMAIN_Link("G");?>' class='MediumNavPage'>Galleries</a> <img src = '<?=G_STRUCTURE_DI("Arrow.gif", $GLOBALS["COLORBASED"])?>'> <?=$sBreadCrumb?></td></tr>
			<tr><td colspan=<?=$iNumColumns?> bgcolor = <?=$GLOBALS["PageBGColor"]?>><table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td bgcolor = <?=$GLOBALS["BorderColor1"]?>><img src='Images/Blank.gif' width=10 height=1></td></tr></table></td></tr>
		<?php }
		For ( $x = 0; $x < $iNumCategories; $x++)
		{
			Echo "<tr>\n";
			For ( $y = 1; $y <= $iNumColumns; $y++)
			{
				If ( $y > 1 )
					$x++;
				If ( $x < $iNumCategories ) {
					$sFilePath = DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_LOC") . "/" . "CatImage_" . $aCategories[1][$x] . ".jpg";
					$sFilePath = str_replace("\\", "/", $sFilePath);
					$sFilePath = str_replace("//", "/", $sFilePath);
					
					$iCount = 0;
					If ( $bAdmin ) {
						$sQuery = "SELECT GalleryUnq, AccountUnq, Name FROM Galleries (NOLOCK) WHERE CategoryUnq = " . $aCategories[1][$x] . " ORDER BY Position";
					}Else{
						// they are not an admin so only display public galleries, or galleries they own
						$sQuery = "SELECT G.GalleryUnq, G.AccountUnq, G.Name FROM Galleries G (NOLOCK) LEFT OUTER JOIN PrivateAccounts P (NOLOCK) ON G.GalleryUnq = P.GalleryUnq WHERE (G.Visibility = " . $PUBLIC_GALLERIES . " OR (G.AccountUnq = " . $iLoginAccountUnq . ")) AND G.CategoryUnq = " . $aCategories[1][$x] . " AND P.AccountUnq = -1 ORDER BY G.Position";
					}
					$rsRecordSet = DB_Query($sQuery);
					If ( DB_NumRows($rsRecordSet) > 0 )
					{
						?>
						<td align=center valign=top>
							<table cellpadding = 0 cellspacing=0 border=0 width=100% class='TablePage'>
								<tr>
									<td colspan=2>
										<?php If ( $sCatImageLoc == "TOP" ) {?>
										<?php If ( $aCategories[2][$x] == "Y" ) {?>
											<table cellpadding=0 cellspacing=0 border=0 width=<?=$iWidth?> class='Table1_Boxed'><tr><td><a href='G_Display.php?iCategoryUnq=<?=$aCategories[1][$x]?>'><img src='<?=$sFilePath?>' alt = '<?=$aCategories[0][$x]?>' width=<?=$iWidth?> height=<?=$iHeight?> border=0></a></td></tr></table>
										<?php }?>
										<?php }?>
									</td>
								</tr>
								<tr>
									<td valign=top colspan=2>
										<?php 
										If ( $aCategories[3][$x] > 0 ) {
											G_STRUCTURE_Category_Lines($aCategories[0][$x], $aCategories[0][$x], "index.php?iParentUnq=" . $aCategories[1][$x], "Galleries", $sLines);
										}Else{
											G_STRUCTURE_Category_Lines($aCategories[0][$x], $aCategories[0][$x], "G_Display.php?iCategoryUnq=" . $aCategories[1][$x], "Galleries", $sLines);
										}
										?>
									</td>
								</tr>
								<tr><td colspan=2><img src='Images/Blank.gif' width=3 height=3></td></tr>
								<tr>
									<td valign=top width=100%>
										<?php
										$iX = DB_NumRows($rsRecordSet); 
										if ( $iX > 0 )
										{
											while ( ( $rsRow = DB_Fetch($rsRecordSet) ) && ( $iCount < $iNumGalsToDisp ) )
											{
												$iGalleryUnq = $rsRow["GalleryUnq"];
												$iAccountUnq = $rsRow["AccountUnq"];
												$sQuery = "SELECT Count(*) FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
												$rsRecordSet2 = DB_Query($sQuery);
												if ( $rsRow2 = DB_Fetch($rsRecordSet2) ) {
													$iNumImages = $rsRow2[0];
													$sTemp = " <i>(" . $rsRow2[0] . " images)</i>";
												}Else{
													$iNumImages = 0;
													$sTemp = " <i>(no images)</i>";
												}
												If ( $iNumImages > 0 ) {
													If ( $bAdmin ) {
														Echo "<img src='Images/Blank.gif' width=25 height=2 border=0>&#149;<a href='ThumbnailView.php?iGalleryUnq=" . $iGalleryUnq . "' class='SmallNavPage'>" . $rsRow["Name"] . "</a>";
														$iCount++;
													}Else{
														If ( G_ADMINISTRATION_AccessLocked($iGalleryUnq, $iAccountUnq) ) {
															Echo "<img src='Images/Blank.gif' width=25 height=2 border=0>&#149;<a href='ThumbnailView.php?iGalleryUnq=" . $iGalleryUnq . "' class='SmallNavPage'>" . $rsRow["Name"] . "</a>";
															$iCount++;
														}
													}
													Echo $sTemp . "<br>";
												}
											}
											If ( $iCount > $iNumGalsToDisp-1 )
											{
												If ( ( $iX == 1 ) && ( $iNumGalsToDisp > 0 ) )
												{
													$iGalleryUnq = $rsRow["GalleryUnq"];
													$iAccountUnq = $rsRow["AccountUnq"];
													$sQuery = "SELECT Count(*) FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
													$rsRecordSet2 = DB_Query($sQuery);
													if ( $rsRow2 = DB_Fetch($rsRecordSet2) ) {
														$iNumImages = $rsRow2[0];
														$sTemp = " <i>(" . $rsRow2[0] . " image)</i>";
													}Else{
														$iNumImages = 0;
													}
													If ( $iNumImages > 0 ) {
														If ( $bAdmin ) {
															Echo "<img src='Images/Blank.gif' width=25 height=2 border=0>&#149;<a href='ThumbnailView.php?iGalleryUnq=" . $iGalleryUnq . "' class='SmallNavPage'>" . $rsRow["Name"] . "</a>";
															$iCount++;
														}Else{
															If ( G_ADMINISTRATION_AccessLocked($iGalleryUnq, $iAccountUnq) ) {
																Echo "<img src='Images/Blank.gif' width=25 height=2 border=0>&#149;<a href='ThumbnailView.php?iGalleryUnq=" . $iGalleryUnq . "' class='SmallNavPage'>" . $rsRow["Name"] . "</a>";
																$iCount++;
															}
														}
														Echo $sTemp . "<br>";
													}
												}ElseIf ( ( $iX > 1 ) && ( $iNumGalsToDisp > 0 ) ){
													Echo "<img src='Images/Blank.gif' width=25 height=2 border=0>&#149;<a href='G_Display.php?iCategoryUnq=" . $aCategories[1][$x] . "' class='SmallNavPage'><b>Full Gallery List...</b></a><br>";
												}
											}
										}Else{
											If ( $aCategories[3][$x] <= 0 )
												Echo "<img src='Images/Blank.gif' width=25 height=2 border=0>&#149;No galleries in this category.<br>";
										}
										?>
									</td>
									<td valign=top align=right>
										<?php If ( $sCatImageLoc == "RIGHT" ) {?>
										<?php If ( $aCategories[2][$x] == "Y" ) {?>
											<table cellpadding=0 cellspacing=0 border=0 width=<?=$iWidth?> class='Table1_Boxed'><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'><a href='G_Display.php?iCategoryUnq=<?=$aCategories[1][$x]?>'><img src='<?=$sFilePath?>' alt = '<?=$aCategories[0][$x]?>' width=<?=$iWidth?> height=<?=$iHeight?> border=0></a></td></tr></table>
										<?php }?>
										<?php }?>
									</td>
								</tr>
								<tr>
									<td colspan=2>
										<?php 
										If ( $iParentUnq != $aCategories[1][$x] ) {
											$iLevel = 0;
											DispSubCats($aCategories[1][$x]);
										}

										If ( ( $iCount > 0 ) || ( $aCategories[3][$x] >  0 ) ) {	// only display this if there are galleries and/or subcategories
											If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_ALLOW_ALL_DISPLAY"))) == "YES" ) {
												?>
												<br><a href='ThumbnailView.php?iCategoryUnq=<?=$aCategories[1][$x]?>' class='SmallNavPage'>List <u>ALL</u> images in this category and its subcategories.</a>
												<?php
											}
										}
										?>
									</td>
								</tr>
							</table>
						</td>
						<?php 
					}Else{
						If ( $aCategories[3][$x] > 0 ) {
							?>
							<td align=center valign=top>
								<table cellpadding = 0 cellspacing=0 border=0 width=100% class='TablePage'>
									<?php If ( $sCatImageLoc == "TOP" ) {?>
									<?php If ( $aCategories[2][$x] == "Y" ) {?>
									<tr>
										<td colspan=2>
											<table cellpadding=0 cellspacing=0 border=0 width=<?=$iWidth?> class='Table1_Boxed'><tr><td><a href='G_Display.php?iCategoryUnq=<?=$aCategories[1][$x]?>'><img src='<?=$sFilePath?>' alt = '<?=$aCategories[0][$x]?>' width=<?=$iWidth?> height=<?=$iHeight?> border=0></a></td></tr></table>
										</td>
									</tr>
									<?php }?>
									<?php }?>
									<tr>
										<td valign=top colspan=2>
											<?php G_STRUCTURE_Category_Lines($aCategories[0][$x], $aCategories[0][$x], "index.php?iParentUnq=" . $aCategories[1][$x], "Galleries", $sLines);?>
										</td>
									</tr>
									<?php If ( $sCatImageLoc == "RIGHT" ) {?>
									<?php If ( $aCategories[2][$x] == "Y" ) {?>
									<tr>
										<td valign=top align=right colspan=2>
											<table cellpadding=0 cellspacing=0 border=0 width=<?=$iWidth?> class='Table1_Boxed'><tr><td><a href='G_Display.php?iCategoryUnq=<?=$aCategories[1][$x]?>'><img src='<?=$sFilePath?>' alt = '<?=$aCategories[0][$x]?>' width=<?=$iWidth?> height=<?=$iHeight?> border=0></a></td></tr></table>
										</td>
									</tr>
									<?php }?>
									<?php }?>
									<tr>
										<td colspan=2>
											<?php 
											If ( $iParentUnq != $aCategories[1][$x] ) {
												$iLevel = 0;
												DispSubCats($aCategories[1][$x]);
											}
											
											If ( ( $iCount > 0 ) || ( $aCategories[3][$x] >  0 ) ) {	// only display this if there are galleries and/or subcategories
												If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_ALLOW_ALL_DISPLAY"))) == "YES" ) {
													?>
													<br><a href='ThumbnailView.php?iCategoryUnq=<?=$aCategories[1][$x]?>' class='SmallNavPage'>List <u>ALL</u> images in this category and its subcategories.</a>
													<?php
												}
											}
											?>
										</td>
									</tr>
								</table>
							</td>
							<?php 
						}Else{
							If ( $y <= 1 )
								$x++;
							$y--;
						}
					}
				}Else{
					Echo "<td bgcolor = " . $GLOBALS["PageBGColor"] . ">&nbsp;</td>";
				}
			}
			Echo "</tr>\n";
		}
		Echo "</table>\n";
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DispSubCats($iCategoryUnq)
	{
		Global $bAnyChildWGallery;
		Global $iParentUnq;
		Global $iLevel;
		Global $iNumCatsToDisp;
		
		$sQuery			= "SELECT Name, CategoryUnq FROM IGCategories (NOLOCK) WHERE Parent = " . $iCategoryUnq . " AND Parent != 0 ORDER BY Position";
		$rsRecordSet2	= DB_Query($sQuery);
		if ( DB_NumRows($rsRecordSet2) > 0 )
		{
			$iCount2 = 0;
			If ( $iNumCatsToDisp > 0 )
			{
				If ( $iParentUnq == $iCategoryUnq ) {
					Echo "<span class='SmallPageText'><b>Subcategories:&nbsp;</b></span>";
				}ElseIf ( $iLevel == 0 ) {
					Echo "<b><a href='index.php?iParentUnq=" . $iCategoryUnq . "' class='SmallNavPage'>Subcategories:</a>&nbsp;</b></font>";
				}
			}
			If ( $iNumCatsToDisp > 0 )
			{
				While ( ( $rsRow2 = DB_Fetch($rsRecordSet2) ) && ( $iCount2 < $iNumCatsToDisp ) ) 
				{
					$bAnyChildWGallery = False;
					AnyChildWGallery($rsRow2["CategoryUnq"]);
					$iNumImages = GetNumImages($rsRow2["CategoryUnq"]);
					If ( ( $bAnyChildWGallery ) || ( $iNumImages > 0 ) ) {
						$iCount2++;
						If ( $iNumImages > 0 ) {
							$sTemp = " (" . $iNumImages . ")";
						}Else{
							$sTemp = "";
						}
						$iNumSubs = PopulateSubCatCount($rsRow2["CategoryUnq"]);
						If ( $iParentUnq == $iCategoryUnq ) {
							Echo "<BR><img src='Images/Blank.gif' width=" . (25*($iLevel+1)) . " height=2><span class='SmallPageText'>" . $rsRow2["Name"] . "</span> (" . $iNumSubs . ")";
						}Else{
							If ( $iNumSubs == 0 ) {
								Echo "<BR><img src='Images/Blank.gif' width=" . (25*($iLevel+1)) . " height=2><a href='G_Display.php?iCategoryUnq=" . $rsRow2["CategoryUnq"] . "' class='SmallNavPage'>" . $rsRow2["Name"] . "</a>" . $sTemp;
							}Else{
								Echo "<BR><img src='Images/Blank.gif' width=" . (25*($iLevel+1)) . " height=2><a href='index.php?iParentUnq=" . $rsRow2["CategoryUnq"] . "' class='SmallNavPage'>" . $rsRow2["Name"] . "</a>" . $sTemp;
							}
						}
						$iLevel++;
						DispSubCats($rsRow2["CategoryUnq"]);
						$iLevel--;
					}
				}
				Echo "<BR>";
	
				While ( $rsRow2 = DB_Fetch($rsRecordSet2) )
				{
					$bAnyChildWGallery = False;
					AnyChildWGallery($rsRow2["CategoryUnq"]);
					If ( $bAnyChildWGallery )
						$iCount2++;
				}
			}
			If ( $iCount2 > $iNumCatsToDisp ) {
				If ( $iParentUnq != $iCategoryUnq ) {
					Echo "<img src='Images/Blank.gif' width=25 height=2><a href='index.php?iParentUnq=" . $iCategoryUnq . "' class='SmallNavPage'>..." . ($iCount2 - $iNumCatsToDisp) . " more</a>";
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
	Function PopulateSubCatCount($iCategoryUnq)
	{		
		$iCount			= 0;
		If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
			$sQuery	= "SELECT Count(*), C.CategoryUnq FROM Galleries G (NOLOCK), IGCategories C (NOLOCK) WHERE C.Parent = " . $iCategoryUnq . " AND C.CategoryUnq = G.CategoryUnq AND G.GalleryUnq IN (SELECT DISTINCT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.GalleryUnq = IG.GalleryUnq AND G.CategoryUnq = C.CategoryUnq) GROUP BY C.CategoryUnq";
		}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
			$sTemp			= "";
			$sQuery			= "SELECT DISTINCT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG, IGCategories C (NOLOCK) WHERE G.GalleryUnq = IG.GalleryUnq AND G.CategoryUnq = C.CategoryUnq";
			$rsRecordSet	= DB_Query($sQuery);
			While ( $rsRow = DB_Fetch($rsRecordSet) )
				$sTemp .= $rsRow["GalleryUnq"] . ",";
			$sTemp .= "0";
			$sQuery	= "SELECT Count(*), C.CategoryUnq FROM Galleries G (NOLOCK), IGCategories C (NOLOCK) WHERE C.Parent = " . $iCategoryUnq . " AND C.CategoryUnq = G.CategoryUnq AND G.GalleryUnq IN (" . $sTemp . ") GROUP BY C.CategoryUnq";
		}
		$rsRecordSet	= DB_Query($sQuery);
		while ( $rsRow = DB_Fetch($rsRecordSet) )
			$iCount++;
			
		Return $iCount;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function GetNumImages($iCategoryUnq)
	{
		$sQuery			= "SELECT count(*) FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.GalleryUnq = IG.GalleryUnq AND G.CategoryUnq = " . $iCategoryUnq;
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow[0];
			
		Return 0;		
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
		Global $bAdmin;
		Global $bCanCreate;
		Global $PUBLIC_GALLERIES;
		Global $iLoginAccountUnq;
		Global $bAnyChildWGallery;
		
		$sQuery			= "SELECT CategoryUnq FROM IGCategories (NOLOCK) WHERE Parent = " . $iCategoryUnq;
		$rsRecordSet3	= DB_Query($sQuery);
		if ( DB_NumRows($rsRecordSet3) > 0 )
		{
			while ( ( $rsRow3 = DB_Fetch($rsRecordSet3) ) && ( $bAnyChildWGallery == False ) ) 
			{
				If ( $bAdmin ) {
					// they can see ALL galleries for this domain
					$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $rsRow3["CategoryUnq"] . " AND IG.GalleryUnq = G.GalleryUnq";
				}ElseIf ( $bCanCreate ) {
					// they can see their and public galleries
					$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE (G.AccountUnq = " . $iLoginAccountUnq . " OR G.Visibility = '" . $PUBLIC_GALLERIES . "') AND G.CategoryUnq = " . $rsRow3["CategoryUnq"] . " AND IG.GalleryUnq = G.GalleryUnq";
				}Else{
					// they can only see public galleries
					$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.Visibility = '" . $PUBLIC_GALLERIES . "' AND G.CategoryUnq = " . $rsRow3["CategoryUnq"] . " AND IG.GalleryUnq = G.GalleryUnq";
				}
				DB_Query("SET ROWCOUNT 1");
				$rsRecordSet4 = DB_Query($sQuery);
				DB_Query("SET ROWCOUNT 0");
				If ( $rsRow4 = DB_Fetch($rsRecordSet4) )
					$bAnyChildWGallery = True;

				If ( ! $bAnyChildWGallery )
					AnyChildWGallery($rsRow3["CategoryUnq"]);
			}
		}Else{
			// no more child categories, so just check the current (leaf) one
			$sQuery = "SELECT G.GalleryUnq FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE G.CategoryUnq = " . $iCategoryUnq . " AND IG.GalleryUnq = G.GalleryUnq";
			DB_Query("SET ROWCOUNT 1");
			$rsRecordSet4 = DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			If ( $rsRow4 = DB_Fetch($rsRecordSet4) )
				$bAnyChildWGallery = True;
		}
	}
	//************************************************************************************
	
	
	
	
	//************************************************************************************
	//*																					*
	//*	Display the galleries and the links to edit galleries and edit/view images.		*
	//*																					*
	//************************************************************************************
	Function DisplayGalleries($iCategoryUnq)
	{
		Global $iLoginAccountUnq;
		Global $iTableWidth;
		Global $iColorScheme;
		Global $iTextScheme;
		Global $iAccountUnq;
		Global $sSort;
		Global $sBreadCrumb;
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
			$iTtlNumItems = GetAll($iCategoryUnq);
		}Else{
			// get as few as possible
			GetFew($iCategoryUnq);
		}
		If ( intval($iTtlNumItems) > 0 )
		{
			?>
			<script language='JavaScript1.2' type='text/javascript'>
	
				function PaginationLink(sQueryString){
					document.location = "G_Display.php?<?=DOMAIN_Link("G")?>" + sQueryString;
				}
	
			</script>
			<table width=100% cellpadding=0 cellspacing=0 border=0 class='TablePage'>
				<tr>
					<td>
						<?php 
						If ( $bHasAccount ) {
							If ( DOMAIN_Has_RemoteHost() ) {
								G_LINK_Suggest_Category();
							}
						}
						?>
					</td>
					<td align=right>
						<?php 
						If ( $bHasAccount ) {
							If ( DOMAIN_Has_RemoteHost() ) {
								G_LINK_Subscribe_Category($iCategoryUnq);
							}
						}
						?>
					</td>
				</tr>
				<?php If ( $iTtlNumItems > $iNumPerPage ) {?>
				<tr>
					<td colspan=2>
						<?php PrintRecordsetNav( "index.php", "$sSort=" . $sSort . "&iNumPerPage=" . $iNumPerPage . "&iCategoryUnq=" . $iCategoryUnq, "Galleries" ); ?>
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
		
		?>
		<table cellpadding=5 width=100% cellspacing = 0 border = 0 class='TablePage_Boxed'>
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

					$sNumImages = GetNumImages_Gallery($aGalleryInfo[0][$x]);
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
				PrintRecordsetNav( "index.php", "$sSort=" . $sSort . "&iNumPerPage=" . $iNumPerPage . "&iCategoryUnq=" . $iCategoryUnq, "Galleries" );
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
		
		?>
		<table cellpadding=5 width=100% cellspacing = 0 border = 0 class='TablePage'>
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
					$sNumImages = GetNumImages_Gallery($aGalleryInfo[0][$x]);
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
						$sNumImages = GetNumImages_Gallery($aGalleryInfo[0][$x]);
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
				PrintRecordsetNav( "index.php", "$sSort=" . $sSort . "&iNumPerPage=" . $iNumPerPage . "&iCategoryUnq=" . $iCategoryUnq, "Galleries" );
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
	Function GetNumImages_Gallery($iGalleryUnq)
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
		Global $sAccountUnq;
		Global $iPrimaryG;
		Global $sGalleryPath;
		Global $sSiteURL;
		
		$iThumbWidth	= DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH");

		$sQuery			= "SELECT I.Thumbnail, I.ImageUL, IG.PrimaryG FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK) WHERE IG.GalleryUnq = " . $iGalleryUnq . " AND G.GalleryUnq = " . $iGalleryUnq . " AND IG.ImageUnq = I.ImageUnq AND I.Thumbnail != '' ORDER BY ImageNum";
		DB_Query("SET ROWCOUNT 1");
		$rsRecordSet = DB_Query($sQuery);
		DB_Query("SET ROWCOUNT 0");
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$sFilePath = $sGalleryPath . "/" . $rsRow["ImageUL"] . "/" . $iGalleryUnq . "/Thumbnails/" . $rsRow["Thumbnail"];
			$sFilePath	= str_replace("\\", "/", $sFilePath);
			$sFilePath	= str_replace("//", "/", $sFilePath);
			$sFilePath	= str_replace("//", "/", $sFilePath);
			If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
			{
				?>
				<img src = "<?=DOMAIN_Conf("IG")?>/<?=$rsRow["ImageUL"]?>/<?=$iGalleryUnq?>/Thumbnails/<?=$rsRow["Thumbnail"]?>" width=<?=$iThumbWidth?> border=0>
				<?php
			}Else{
				?>
				<img src = "<?=DOMAIN_Conf("IMAGEGALLERY_MISSING_THUMBNAIL")?>" width=<?=$iThumbWidth?> border=1>
				<?php
			}
		}
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
	Function GetAll($iCategoryUnq)
	{
		Global $sSort;
		Global $iLoginAccountUnq;
		Global $PUBLIC_GALLERIES;
		Global $iAccountUnq;
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
	Function GetFew($iCategoryUnq)
	{
		Global $sSort;
		Global $iLoginAccountUnq;
		Global $PUBLIC_GALLERIES;
		Global $iAccountUnq;
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
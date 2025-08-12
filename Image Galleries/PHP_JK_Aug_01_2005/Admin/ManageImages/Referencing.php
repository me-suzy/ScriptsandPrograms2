<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iGalleryUnq	= Trim(Request("iGalleryUnq"));
	$iTtlNumItems	= Trim(Request("iTtlNumItems"));
	$iDBLoc			= Trim(Request("iDBLoc"));
	
	If ( (ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL")) || (ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY")) )
	{		
		If (ACCNT_ReturnRights("PHPJK_IG_REF_GALLERY")) {
			HeaderHTML();
			Main();
		}Else{
			DOMAIN_Message("Please log in with Image Gallery management rights.", "ERROR");
		}
	}Else{
		DOMAIN_Message("Please log in with Image Gallery management rights.", "ERROR");
	}
	WriteScripts();
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_CLOSE.php");
	

	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iLoginAccountUnq;
		Global $iThumbWidth;
		Global $iImageUnq;
		Global $iGalleryUnq;
		Global $sAccountUnq;
		Global $sThumbnail;
		
		$sAction		= Trim(Request("sAction"));
		$iImageUnq		= Trim(Request("iImageUnq"));
		$sAccountUnq	= G_ADMINISTRATION_GetGalleryOwner($iGalleryUnq);
		$iThumbWidth	= DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH");
		$sError			= "";
		$sSuccess		= "";
		
		$sQuery			= "SELECT G.Name, I.Thumbnail, I.Image, I.ImageNum, I.Image2, I.Image3, I.Image4, I.Image5 FROM Images I (NOLOCK), Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE I.ImageUnq = " . $iImageUnq . " AND IG.ImageUnq = " . $iImageUnq . " AND IG.GalleryUnq = G.GalleryUnq";
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$sThumbnail	= Trim($rsRow["Thumbnail"]);
			$sImage		= Trim($rsRow["Image"]);
		}
		
		If ( $iImageUnq != "" )
		{
			If ( $sAction == "AddReference" )
			{
				If ( ACCNT_ReturnRights("PHPJK_IG_REF_GALLERY") ) 
				{
					ForEach ($_POST["iDestinations"] as $sCheckbox=>$sValue)
					{
						// see if they can even reference galleries on the other domain that this image is referenced in
						If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $sValue, "") || ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) )
						{
							// get the position of the image in the new gallery
							$sQuery			= "SELECT COUNT(*)+1 FROM ImagesInGallery WHERE GalleryUnq = " . $sValue;
							$rsRecordSet	= DB_Query($sQuery);
							If ( $rsRow = DB_Fetch($rsRecordSet) ) {
								$iPosition = $rsRow[0];
							}Else{
								$iPosition = 1;
							}							
							
							// get the primary gallery and domain
							$sQuery			= "SELECT DISTINCT PrimaryG FROM ImagesInGallery WHERE ImageUnq = " . $iImageUnq;
							$rsRecordSet	= DB_Query($sQuery);
							If ( $rsRow = DB_Fetch($rsRecordSet) )
								$iPrimaryG = $rsRow["PrimaryG"];
							
							// insert the new record
							DB_Insert ("INSERT INTO ImagesInGallery VALUES (" . $iImageUnq . ", " . $sValue . ", GetDate(), 1, " . $iPosition . ", " . $iPrimaryG . ", 1, 0, 0, 0, 0, 0)");
							
							If ( $sSuccess == "" )
								$sSuccess = "Image successfully added to gallery or galleries.";
						}
					}
				}
			}ElseIf ( $sAction == "RemoveReference" ){
				If ( ACCNT_ReturnRights("PHPJK_IG_REF_GALLERY") )
				{
					ForEach ($_POST["iSources"] as $sCheckbox=>$sValue)
					{
						// see if they can even reference galleries on the other domain that this image is referenced in
						If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $sValue, "") || ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) )
						{
							// Decrement (by 1) all ImageNum's AFTER the one we are removing from the gallery.
							If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
								$sQuery	= "SELECT I.ImageUnq, IG.Position FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IG.GalleryUnq = " . $sValue . " AND I.ImageUnq = IG.ImageUnq AND IG.Position > (SELECT DISTINCT Position FROM ImagesInGallery (NOLOCK) WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $sValue . ") ORDER BY IG.Position";
							}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
								$sQuery			= "SELECT DISTINCT Position FROM ImagesInGallery (NOLOCK) WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $sValue;
								$rsRecordSet	= DB_Query($sQuery);
								If ( $rsRow = DB_Fetch($rsRecordSet) )
									$sQuery	= "SELECT I.ImageUnq, IG.Position FROM Images I (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IG.GalleryUnq = " . $sValue . " AND I.ImageUnq = IG.ImageUnq AND IG.Position > (" . $rsRow["Position"] . ") ORDER BY IG.Position";
							}
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
								DB_Update ("UPDATE ImagesInGallery SET Position = " . ($rsRow["Position"] - 1) . " WHERE ImageUnq = " . $rsRow["ImageUnq"] . " AND GalleryUnq = " . $sValue);
							
							DB_Update ("DELETE FROM ImagesInGallery WHERE ImageUnq = " . $iImageUnq . " AND GalleryUnq = " . $sValue);
							If ( $sSuccess == "" )
								$sSuccess = "Image successfully removed from gallery or galleries.";
						}
					}
				}
			}
			
			If ( $sError != "" )
				DOMAIN_Message($sError, "ERROR");
			If ( $sSuccess != "" )
				DOMAIN_Message($sSuccess, "SUCCESS");
			
			WriteForm();
		}Else{
			DOMAIN_Message("Unable to load the image - missing ImageUnq.", "ERROR");
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
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iImageUnq;
		Global $iGalleryUnq;
		Global $sAccountUnq;
		Global $sThumbnail;
		Global $iThumbWidth;
		
		$sBGColor = $GLOBALS["PageBGColor"]
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ReferenceImage.sAction.value = sAction;
				document.ReferenceImage.submit();
			}
			
		</script>
		
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td><br>
					When referencing images, please note:
					<ul>
						<li>If this image was linked to a message board, all referenced images will no longer have that link.
							You may re-enter new message board information on the image editing screen.
					</ul>
					<form name='ReferenceImage' action='Referencing.php' method='post'>
					<?php G_STRUCTURE_HeaderBar_ADMIN("ReferenceImagesHead.gif", "", "", "Galleries");
					
					$aVariables[0] = "sAction";
					$aVariables[1] = "iTtlNumItems";
					$aVariables[2] = "iDBLoc";
					$aVariables[3] = "iImageUnq";
					$aVariables[4] = "iGalleryUnq";
					$aValues[0] = "MoveImage";
					$aValues[1] = $iTtlNumItems;
					$aValues[2] = $iDBLoc;
					$aValues[3] = $iImageUnq;
					$aValues[4] = $iGalleryUnq;
					Echo DOMAIN_Link("P");
					?>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
					<table cellpadding = 0 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor = <?=$sBGColor?>>
								<table cellpadding = 5 cellspacing = 3 border = 1 bordercolor = <?=$GLOBALS["BorderColor1"]?> width = 100%>
									<tr>
										<td width=<?=$iThumbWidth?> align=middle valign=top>
											<?php 
											$sQuery			= "SELECT G.GalleryUnq, G.AccountUnq, G.Domain, G.Name, IG.PrimaryG FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IG.GalleryUnq = G.GalleryUnq AND IG.ImageUnq = " . $iImageUnq . " ORDER BY G.Name";
											DB_Query("SET ROWCOUNT 1");
											$rsRecordSet	= DB_Query($sQuery);
											DB_Query("SET ROWCOUNT 0");
											If ( $rsRow = DB_Fetch($rsRecordSet) )
												DispThumb( $iImageUnq, $rsRow["GalleryUnq"], $sAccountUnq, $sThumbnail, $rsRow["PrimaryG"] );
											?>
										</td>
										<td valign=top>
											<font color='<?=$GLOBALS["TextColor1"]?>'>
											<?php 
											$sQuery			= "SELECT G.GalleryUnq, G.AccountUnq, G.Name, IG.PrimaryG FROM Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IG.GalleryUnq = G.GalleryUnq AND IG.ImageUnq = " . $iImageUnq . " ORDER BY G.Name";
											$rsRecordSet	= DB_Query($sQuery);
											If ( DB_NumRows($rsRecordSet) > 0 )
											{
												$x = 0;
												While ( $rsRow = DB_Fetch($rsRecordSet) )
												{
													$bDispGallery = FALSE;
													If ( ACCNT_ReturnRights("PHPJK_IG_REF_GALLERY") )
													{
														// see if they can even reference galleries on the other domain that this image is referenced in
														If ( ! ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") )
														{
															// see if they are the owner of the gallery
															If ( $iLoginAccountUnq == $rsRow["AccountUnq"] )
																$bDispGallery = TRUE;
														}Else{
															$bDispGallery = TRUE;
														}
													}

													If ( $bDispGallery )
													{
														$aReferencing[0][$x] = $rsRow["GalleryUnq"];
														$aReferencing[2][$x] = Trim($rsRow["Name"]);
														$aReferencing[3][$x] = $rsRow["PrimaryG"];
														$x++;
													}
												}
												Echo "<br><b>Currently Referenced in these Galleries:</b><br>";
												Echo "<select name='iSources[]' multiple size=5>";
												For ( $y = 0; $y < $x; $y++)
												{
													If ( $aReferencing[0][$y] == $aReferencing[3][$y] )
													{
														// skip it because it's the primary copy of the image
													}Else{
														Echo "<option value=\"";
														Echo $aReferencing[0][$y];
														Echo "\">";
														Echo $aReferencing[2][$y];
														Echo "</option>";
													}
												}
												Echo "</select><br><br>";
												Echo "<center><input type='button' value='Remove from Galleries' onClick=\"SubmitForm('RemoveReference');\"></center>";
											}Else{
												Echo "<b>Error: Image not found!</b>";
											}
											?>
										</td>
										<td valign=top>
											<table cellpadding=0 cellspacing=0 border=0>
												<tr>
													<td valign=top>
														<br><br>
														<font color='<?=$GLOBALS["TextColor1"]?>'>
														<?php 
														If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
															$sQuery = "SELECT AccountUnq, GalleryUnq, Name FROM Galleries (NOLOCK)";
														}Else{
															$sQuery = "SELECT AccountUnq, GalleryUnq, Name FROM Galleries (NOLOCK) WHERE AccountUnq = " . $iLoginAccountUnq;
														}
														$rsRecordSet = DB_Query($sQuery);
														If ( DB_NumRows($rsRecordSet) > 0 )
														{
															Echo "<b>Galleries to display image in:</b><br>";
															Echo "<select name='iDestinations[]' multiple>";
															While ( $rsRow = DB_Fetch($rsRecordSet) )
															{
																$iTempGalleryUnq	= $rsRow["GalleryUnq"];
																$bGalleryFound		= FALSE;
																For ( $y = 0; $y < $x; $y++)
																{
																	If ( $aReferencing[0][$y] == $iTempGalleryUnq ) 
																	{
																		$bGalleryFound = TRUE;
																		break;
																	}
																}
																If ( ! $bGalleryFound ) {
																	// only display galleries that the image is NOT already in
																	?><option value="<?= $iTempGalleryUnq ?>"><?= $rsRow["Name"] ?></option><?php 
																}
															}															
															Echo "</select>";
															Echo "<br><br>";
															Echo "<center><input type='button' value='Add to Galleries' onClick=\"SubmitForm('AddReference');\"></center>";
														}Else{
															Echo "No galleries to add to.";
														}
														?>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					</td></tr></table>
					</form>
				</td>
			</tr>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		<?php 
	}
	//************************************************************************************

	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DispThumb( $iImageUnq, $iGalleryUnq, $sAccountUnq, $sThumbnail, $iPrimaryG )
	{
		Global $sGalleryPath;
		Global $sSiteURL;
		
		?>
		<table cellpadding=0 cellspacing=0 border=0><tr>
			<td align=center bgcolor=<?=$GLOBALS["BGColor1"]?>>
				<table cellpadding=0 cellspacing=0 border=0 width=100% height=100%>
					<tr>
						<td colspan=3 align=center>
							<table cellpadding=0 cellspacing=0 border=0 width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?>>
								<tr><td colspan=3 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../../Images/Blank.gif" width=1 height=1></td></tr>
								<tr>
									<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../../Images/Blank.gif" width=1 height=1></td>
									<?php If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DOMAINCHECK"))) == "YES" ) { ?>
									<td><a href = '<?=$sSiteURL?>/ImageDetail.php?iGalleryUnq=<?=$iGalleryUnq?>&iImageUnq=<?=$iImageUnq?>' target='_blank'><img src = "<?=$sSiteURL?>/Attachments/DispThumb.php?sAccountUnq=<?=$sAccountUnq?>&sThumbnail=<?=$sThumbnail?>&iGalleryUnq=<?=$iPrimaryG?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> border=0></a></td>
									<?php }Else{
									$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iPrimaryG . "/Thumbnails/" . $sThumbnail;
									$sFilePath	= str_replace("\\", "/", $sFilePath);
									$sFilePath	= str_replace("//", "/", $sFilePath);
									If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
									{
										?>
										<td><a href = '<?=$sSiteURL?>/ImageDetail.php?iGalleryUnq=<?=$iGalleryUnq?>&iImageUnq=<?=$iImageUnq?>' target='_blank'><img src = "<?=DOMAIN_Conf("IG")?>/<?=$sAccountUnq?>/<?=$iPrimaryG?>/Thumbnails/<?=$sThumbnail?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> border=0></a></td>
										<?php 
									}Else{
										?>
										<td><a href = '<?=$sSiteURL?>/ImageDetail.php?iGalleryUnq=<?=$iGalleryUnq?>&iImageUnq=<?=$iImageUnq?>' target='_blank'><img src = "<?=DOMAIN_Conf("IMAGEGALLERY_MISSING_THUMBNAIL")?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> border=0></a></td>
										<?php 
									}
									} ?>
									<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../../Images/Blank.gif" width=1 height=1></td>
								</tr>
								<tr><td colspan=3 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../../Images/Blank.gif" width=1 height=1></td></tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr></table>
		<?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This writes the JavaScript out even if the Main() Function isn't called in case the	*
	//*		admin has creation rights, but not edit rights.								*
	//*																					*
	//************************************************************************************
	Function WriteScripts()
	{
		Global $iGalleryUnq;
		Global $iTtlNumItems;
		Global $iDBLoc;
		?>
		<SCRIPT LANGUAGE=javascript>
		<!--
		
			function ReturnToMain(){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$iGalleryUnq?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
			}
			
		-->
		</SCRIPT>
		<?php 
	}
	//************************************************************************************
	
	
	
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
				<td colspan=4 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:ReturnToMain();'><img src='../../Images/Administrative/Return.gif' Width=30 Height=38 Border=0 Alt=''></a></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				?>
				<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>
				<td bgcolor=FFFFFF width=100%>&nbsp;</td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=4 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=6 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
?>
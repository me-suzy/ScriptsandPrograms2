<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	include("../../fckeditor/fckeditor.php") ;

	$iTemp = DOMAIN_Conf("IMAGEGALLERY_SCRIPTTIMEOUT");
	If ( $iTemp = "" )
		$iTemp = 5400;
	set_time_limit($iTemp);
	
	$sAction				= Trim(Request("sAction"));
	$iTtlNumItems			= Trim(Request("iTtlNumItems"));
	$iDBLoc					= Trim(Request("iDBLoc"));
	$iGalleryUnq			= Trim(Request("iGalleryUnq"));
	$iImageUnq				= Trim(Request("iImageUnq"));
	$sFileName				= "";
	$sExtension				= "";
	$iFileSize				= 0;
	$iMaxFileSize			= 0;
	$sAccountUnq			= 0;
	$iYSize					= 0;
	$iXSize					= 0;
	$bIsImage				= False;

	$sValidExtensions		= G_ADMINISTRATION_ValidFileExtensions($iLoginAccountUnq);
	$iMaxFileSize			= G_ADMINISTRATION_MaxFileSize($iLoginAccountUnq);
	$iHDSpaceLeft			= G_ADMINISTRATION_HDSpaceLeft($iLoginAccountUnq);
	$iThumbComponent		= G_ADMINISTRATION_ASPImageInstalled();
	$sAccountUnq			= G_ADMINISTRATION_GetGalleryOwner($iGalleryUnq);

	If ( ! isset($_POST["sAction"]) )
	{
		// get the rest of the information from the database
		$sQuery			= "SELECT I.Comments, I.AltTag, I.Image, I.Keywords, I.Title, I.Image2, I.Image3, I.Image4, I.Image5, I.Image2Desc, I.Image3Desc, I.Image4Desc, I.Image5Desc, I.AltTag2, I.AltTag3, I.AltTag4, I.AltTag5, I.XSize2, I.YSize2, I.XSize3, I.YSize3, I.XSize4, I.YSize4, I.XSize5, I.YSize5, I.ImageSize2, I.ImageSize3, I.ImageSize4, I.ImageSize5, G.AccountUnq, IG.PrimaryG, IG.ThreadUnq, IG.ConfUnq FROM Images I (NOLOCK), Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE I.ImageUnq = " . $iImageUnq . " AND IG.ImageUnq = " . $iImageUnq . " AND IG.GalleryUnq = G.GalleryUnq AND IG.GalleryUnq = " . $iGalleryUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$sComments		= Trim($rsRow["Comments"]);
			$sAltTag		= Trim($rsRow["AltTag"]);
			$sImage			= Trim($rsRow["Image"]);
			$sKeywords		= Trim($rsRow["Keywords"]);
			$sTitle			= Trim($rsRow["Title"]);
	
			$iPrimaryG		= Trim($rsRow["PrimaryG"]);
		}
	}Else{
		$sComments			= Trim(Request("sComments"));
		$sAltTag			= Trim(Request("sAltTag"));
		$sKeywords			= Trim(Request("sKeywords"));
		$sTitle				= Trim(Request("sTitle"));
		$sImage				= Trim(Request("sImage"));
	}
	
	WriteScripts();		// must run this once $iGalleryUnq has been filled

	If ( (ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL")) || (ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY")) ) {
		HeaderHTML();
		Main();
	}Else{
		DOMAIN_Message("Please log in with Image Gallery management rights.", "ERROR");
	}

	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iGalleryUnq;
		Global $iImageUnq;
		Global $sValidExtensions;
		Global $iMaxFileSize;
		Global $iHDSpaceLeft;
		Global $iThumbComponent;
		Global $sAction;
		Global $sComments;
		Global $sAltTag;
		Global $sKeywords;
		Global $sTitle;
		Global $sImage;
		Global $ASPIMAGE;
		Global $GFL;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iLoginAccountUnq;
		Global $sAccountUnq;
		Global $iYSize;
		Global $iXSize;
		Global $sGalleryPath;
		
		Global $sExtension;
		Global $iFileSize;
		Global $sThumbname;
		
		$sError		= "";
		$sSuccess	= "";

		If ( $sAction == "Update" )
		{
			If ( ! ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
				If ( ! ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") )
					$sError = "Please log in with Image Gallery management rights.";
			}

			If ( $sError == "" )
			{
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{
					$sFileName = Trim($_FILES["File1"]["name"]);
					If ( $sFileName != "" )
					{
						// they uploaded a new primary image. delete the original one. save the new one
						$sQuery			= "SELECT Image, ImageUL FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							$sFilePath = $sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\" . Trim($rsRow["Image"]);
							DelAnyFile($sFilePath, $rsRow["ImageUL"]);
						}
						
						$sError = G_UPLOAD_SaveFile("File1", "PRIMARY", $sFileName);
						If ( ( $iThumbComponent == $ASPIMAGE ) || ( $iThumbComponent == $GFL ) || ( $iThumbComponent == "PHP" ) )
							$sError = $sError . G_UPLOAD_GetDimensions($sFileName);
						
						// if it is not an image type (txt pdf etc)
						If ( $iFileSize == "" )
							$iFileSize = "0";
						If ( $iXSize == "" )
							$iXSize = "0";
						If ( $iYSize == "" )
							$iYSize = "0";
					
						DB_Update ("UPDATE Images SET Image = '" . SQLEncode($sFileName) . "', ImageSize = " . $iFileSize . ", XSize = " . $iXSize . ", YSize = " . $iYSize . ", FileType = '" . SQLEncode($sExtension) . "', ImageUL = " . $iLoginAccountUnq . " WHERE ImageUnq = " . $iImageUnq);
					}

					If ( $sError == "" )
					{
						DB_Update ("UPDATE Images SET Comments = '" . SQLEncode($sComments) . "', CookedComments = '" . SQLEncode($sComments) . "', Keywords = '" . SQLEncode($sKeywords) . "', AltTag = '" . SQLEncode($sAltTag) . "', Title = '" . SQLEncode($sTitle) . "' WHERE ImageUnq = " . $iImageUnq);

						Echo "<SCRIPT LANGUAGE=javascript>\n";
						Echo "document.location=\"index.php?" . DOMAIN_Link("G") . "&iGalleryUnq=" . $iGalleryUnq . "&iTtlNumItems=" . ($iTtlNumItems+1) . "&iDBLoc=" . $iDBLoc . "\";\n";
						Echo "</script>";
						Echo "If this page does not automatically forward you, please " . "<a href='index.php?" . DOMAIN_Link("G") . "&iGalleryUnq=" . $iGalleryUnq . "&iTtlNumItems=" . ($iTtlNumItems+1) . "&iDBLoc=" . $iDBLoc . "' class='MediumNavPage'>click here to continue</a>.";
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot edit images in it.<br>";
				}
			}
		}
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		If ( $sSuccess != "" )
			DOMAIN_Message($sSuccess, "SUCCESS");

		WriteForm();
	}
	//************************************************************************************



	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $ASPIMAGE;
		Global $GFL;
		Global $iTextScheme;
		Global $iColorScheme;
		Global $sValidExtensions;
		Global $iThumbComponent;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iTableWidth;
		
		Global $sComments;
		Global $iGalleryUnq;
		Global $sComments;
		Global $sAltTag;
		Global $sKeywords;
		Global $sTitle;
		Global $sImage;
		Global $iMaxFileSize;
		Global $iImageUnq;
		Global $iPrimaryG;
		
		$sBGColor		= $GLOBALS["BGColor1"];
		$sTextColor		= $GLOBALS["TextColor1"];
		$iTableWidth	= 671;
		
		If ( $iPrimaryG == $iGalleryUnq ) {
			$bIsReferenced = FALSE;
		}Else{
			$bIsReferenced = TRUE;
		}
		
		?>
		<script language='JavaScript1.2' type='text/javascript'>
		
			<?php 
			// get array for JavaScript of all the allowed file types
			Echo "extArray = new Array(";
			$aTypes = explode(" ", $sValidExtensions);
			For ( $x = 0; $x < Count($aTypes); $x++ )
				Echo "\"." . $aTypes[$x] . "\",";
			Echo "\"\");"
			?>

			function LimitAttach()
			{
				file = document.EditImage.File1.value;
				
				allowSubmit = false;
				if (!file) return true;
				while (file.indexOf("\\") != -1)
				file = file.slice(file.indexOf("\\") + 1);
				ext = file.slice(file.indexOf(".")).toLowerCase();
				
				for (var i = 0; i < extArray.length; i++) {
					if (extArray[i] == ext) 
					{ 
						allowSubmit = true; 
						break;
					}
				}
				
				if (allowSubmit) 
				{
					return true;
				}else{
					alert("Please only upload files that end in types:  " + (extArray.join("  ")) + "\nPlease select a new file to upload and submit again.");
					return false;
				}
			}
			
		</script>

		<form ENCTYPE="multipart/form-data" onsubmit="return LimitAttach()" name='EditImage' action='Edit.php' method='post'>
		<input type='hidden' name='iImageUnq' value='<?=$iImageUnq?>'>
		<input type='hidden' name='iGalleryUnq' value='<?=$iGalleryUnq?>'>
		<input type='hidden' name='iTtlNumItems' value='<?=$iTtlNumItems?>'>
		<input type='hidden' name='iDBLoc' value='<?=$iDBLoc?>'>
		<?php
		If ( $iMaxFileSize == "-1" )
		{
			echo "<input type='hidden' name='MAX_FILE_SIZE' value='100000000'>";
		}Else{
			echo "<input type='hidden' name='MAX_FILE_SIZE' value='" . $iMaxFileSize . "'>";
		}
		?>
		<input type='hidden' name='sAction' value='Update'>
		
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<?php 
					$iTableWidth = 671;
					G_STRUCTURE_HeaderBar_ADMIN("EditImageHead.gif", "", "", "Galleries");?>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
					<table cellpadding = 5 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor = <?=$sBGColor?>>
								<?php
								// fckeditor is VERY sensitive of the url and it can't have two /'s in it. so if the admin entered
								//	something like http://www.asb.com/ and /fckeditor/ then it'd be http://www.asb.com//fckeditor
								//	and it wouldn't work. And you can't just do a str_replace on // because it'll remove the one
								//	at the end of the http://
								$sBasePath = DOMAIN_Conf("IMAGEGALLERY_SITEURL");
								If ( $sBasePath[strlen($sBasePath)-1] == "/" )
								{
									$sBasePath = DOMAIN_Conf("IMAGEGALLERY_SITEURL") . "fckeditor/";
								}Else{
									$sBasePath = DOMAIN_Conf("IMAGEGALLERY_SITEURL") . "/fckeditor/";
								}
								
								$ofckeditor = new fckeditor('sComments') ;
								$ofckeditor->BasePath	= $sBasePath ;
								$ofckeditor->Value		= $sComments;
								$ofckeditor->Create() ;
								?>
							</td>
						</tr>
					</table>
					</td></tr></table>
					<?php G_STRUCTURE_SubHeaderBar_ADMIN("", "", "", "Galleries");?>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
					<table cellpadding = 5 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor='<?=$sBGColor?>' align=center>
								<table width=100%>
									<tr>
										<td><font color='<?=$sTextColor?>'><b>Title</td>
										<td><input type='text' name='sTitle' value="<?=htmlentities($sTitle)?>" size=70 maxlength=250></td>
									</tr>
									<tr>
										<td><font color='<?=$sTextColor?>'><b>Keywords</b> (seperate with a comma)</td>
										<td><input type='text' name='sKeywords' value="<?=htmlentities($sKeywords)?>" size=70 maxlength=250></td>
									</tr>
									<tr>
										<td><font color='<?=$sTextColor?>'><b>Alt Tag</td>
										<td><input type='text' name='sAltTag' value="<?=htmlentities($sAltTag)?>" size=70 maxlength=250></td>
									</tr>
									
									<?php If ( $bIsReferenced == FALSE ) {?>
									<tr>
										<td valign=top><font color='<?=$sTextColor?>'><b>Primary Image</td>
										<td>
											<input TYPE='FILE' NAME='File1' SIZE=30>
											<?php DisplayImageInfo();?>
										</td>
									</tr>
									<?php }?>
									<tr>
										<td valign=top><font color='<?=$sTextColor?>'><b>Current Primary Image</b> <font size=-2>(displayed here as a thumbnail or filename)</td>
										<td>
											<?php DisplayAsThumb($sImage, $iPrimaryG, $iImageUnq);?>
										</td>
									</tr>
								</table>
								<br><br>
							</td>
						</tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BGColor2"]?> align=center>
								<input type='image' src="../../Images/SchemeBased/<?=$iTextScheme?>/<?=$iColorScheme?>/SaveImageChanges.gif" style="BORDER: none; vertical-align: sub;">
								<br>
								*This will not change the thumbnail.
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
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayImageInfo()
	{
		Global $sValidExtensions;
		Global $iMaxFileSize;
		Global $iULLeft;
		Global $iHDSpaceLeft;
		
		Echo "<font color='" . $GLOBALS["TextColor1"] . "' size=-2><br>";
		Echo "Valid file extensions: " . $sValidExtensions;
		Echo "<br>Maximum file size: ";
		If ( $iMaxFileSize == -1 ) {
			Echo "Unlimited";
		}Else{
			Echo $iMaxFileSize;
		}
		Echo "<br>Disk space left: ";
		If ( $iHDSpaceLeft == -1 ) {
			Echo "Unlimited";
		}Else{
			Echo $iHDSpaceLeft;
		}
		If ( $iULLeft > 0 )
			Echo "<br>You may add " . $iULLeft . " more images.";
	}
	//************************************************************************************

	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function DisplayAsThumb($sImage, $iPrimaryG, $iImageUnq)
	{
		Global $bIsImage;
		Global $sType;
		Global $sAccountUnq;
		Global $sGalleryPath;
		Global $iThumbWidth;
		Global $sSiteURL;

		$iThumbWidth = DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH");

		G_STRUCTURE_FileType($sType, $bIsImage, $iImageUnq, "../../../../", 0);

		$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iPrimaryG . "/" . $sImage;
		$sFilePath	= str_replace("\\", "/", $sFilePath);
		$sFilePath	= str_replace("//", "/", $sFilePath);
		
		If ( file_exists($sFilePath) )
		{
			If ( $bIsImage ) {
				If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DOMAINCHECK"))) == "YES" ) {
					?>
					<img src = "<?=$sSiteURL?>/Attachments/DispAsThumb.php?sAccountUnq=<?=$sAccountUnq?>&iGalleryUnq=<?=$iPrimaryG?>&sImage=<?=$sImage?>" width=<?=$iThumbWidth?> border=1>
					<?php 
				}Else{
					?>
					<img src = "<?=DOMAIN_Conf("IG")?>/<?=$sAccountUnq?>/<?=$iPrimaryG?>/<?=$sImage?>" width=<?=$iThumbWidth?> border=1>
					<?php 
				}
			}Else{
				Echo "<img src='../../Images/MediaIcons/" . $sType . ".gif' alt = '" . $sType . " file' border=0>&nbsp;";
				Echo $sImage;
			}
		}Else{
			Echo $sImage;
		}
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
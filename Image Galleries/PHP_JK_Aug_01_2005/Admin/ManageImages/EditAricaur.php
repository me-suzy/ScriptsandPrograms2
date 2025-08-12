<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iTemp = DOMAIN_Conf("IMAGEGALLERY_SCRIPTTIMEOUT");
	If ( $iTemp = "" )
		$iTemp = 5400;
	set_time_limit($iTemp);
	
	$iGalleryUnq		= Trim(Request("iGalleryUnq"));
	$iDBLoc				= Trim(Request("iDBLoc"));
	$iImageUnq			= Trim(Request("iImageUnq"));
	
	WriteScripts();
	
	If ( ACCNT_ReturnRights("PHPJK_IG_ARICAUR") ) {
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
		Global $iDBLoc;
		Global $iImageUnq;
		Global $iLoginAccountUnq;
		
		$sAction	= Trim(Request("sAction"));
		$sError		= "";
		$sSuccess	= "";		
		
		If ( $iImageUnq != "" )
		{
			If ( $sAction == "EditAricaur" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
					$sAricaurLink = Trim(Request("sAricaurLink"));
					If ( $sAricaurLink != "" )
					{
						If ( $sAricaurLink == "R" )
						{
							DB_Update ("UPDATE Images SET Aricaur = '', AricaurThumb = '' WHERE ImageUnq = " . $iImageUnq);
						}Else{
							DB_Update ("UPDATE Images SET Aricaur = '" . $sAricaurLink . "' WHERE ImageUnq = " . $iImageUnq);
							$sQuery	= "SELECT Image" . $sAricaurLink . ", Alt" . $sAricaurLink . "UL FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
							$rsRecordSet = DB_Query($sQuery);
							If ( $rsRow = DB_Fetch($rsRecordSet) )
							{
								CreateThumb($rsRow["Image" . $sAricaurLink], $rsRow["Alt" . $sAricaurLink . "UL"], $iGalleryUnq);
								DB_Update ("UPDATE Images SET AricaurThumb = '" . $sAricaurLink . "' WHERE ImageUnq = " . $iImageUnq);
							}
						}
						$sSuccess = "Succesfully updated the Aricaur link for this image.";
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot manage Aricaur links within it.<br>";
				}
			}
			
			If ( $sError != "" )
				DOMAIN_Message($sError, "ERROR");
			If ( $sSuccess != "" )
				DOMAIN_Message($sSuccess, "SUCCESS");

			WriteForm();
		}Else{
			DOMAIN_Message("Missing iImageUnq. Unable to edit the image.", "ERROR");
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
		Global $iGalleryUnq;
		Global $iImageUnq;
		Global $aVariables;
		Global $aValues;
		Global $iDBLoc;
		Global $sGalleryPath;
		Global $sSiteURL;
		
		$sBGColor = $GLOBALS["BGColor2"];
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Manage Aricaur Link</b></font>
					<br>
					<b>Manage Aricaur link for image: </b> <?=ReturnImageName($iImageUnq)?>
					<br><br>
					<form name='EditAricaur' action='EditAricaur.php' method='post'>
					<?php 
					$aVariables[0] = "sAction";
					$aVariables[1] = "iImageUnq";
					$aVariables[2] = "iDBLoc";
					$aVariables[3] = "iGalleryUnq";
					$aValues[0] = "EditAricaur";
					$aValues[1] = $iImageUnq;
					$aValues[2] = $iDBLoc;
					$aValues[3] = $iGalleryUnq;
					Echo DOMAIN_Link("P");
					$sQuery	= "SELECT Image, Thumbnail, Image2, Image3, Image4, Image5, Image2Desc, Image3Desc, Image4Desc, Image5Desc, AltTag2, AltTag3, AltTag4, AltTag5, XSize2, YSize2, XSize3, YSize3, XSize4, YSize4, XSize5, YSize5, ImageSize2, ImageSize3, ImageSize4, ImageSize5, ThumbUL, Alt2UL, Alt3UL, Alt4UL, Alt5UL, Aricaur FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
					$rsRecordSet = DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						$sImage2	= trim($rsRow["Image2"]);
						$sImage3	= trim($rsRow["Image3"]);
						$sImage4	= trim($rsRow["Image4"]);
						$sImage5	= trim($rsRow["Image5"]);
						$sAricaur	= trim($rsRow["Aricaur"]);
						$iXSize2	= $rsRow["XSize2"];
						$iXSize3	= $rsRow["XSize3"];
						$iXSize4	= $rsRow["XSize4"];
						$iXSize5	= $rsRow["XSize5"];
						$iYSize2	= $rsRow["YSize2"];
						$iYSize3	= $rsRow["YSize3"];
						$iYSize4	= $rsRow["YSize4"];
						$iYSize5	= $rsRow["YSize5"];
						$iFileSize2	= $rsRow["ImageSize2"];
						$iFileSize3	= $rsRow["ImageSize3"];
						$iFileSize4	= $rsRow["ImageSize4"];
						$iFileSize5	= $rsRow["ImageSize5"];
						$sStatus2	= "";
						$sStatus3	= "";
						$sStatus4	= "";
						$sStatus5	= "";
						$bImage2	= FALSE;
						$bImage3	= FALSE;
						$bImage4	= FALSE;
						$bImage5	= FALSE;
						If ( $sImage2 != "" )
							$bImage2	= ValidImage($sImage2, $iGalleryUnq, $rsRow["Alt2UL"], $iXSize2, $iYSize2, $iFileSize2, $sStatus2, 2);
						If ( $sImage3 != "" )
							$bImage3	= ValidImage($sImage3, $iGalleryUnq, $rsRow["Alt3UL"], $iXSize3, $iYSize3, $iFileSize3, $sStatus3, 3);
						If ( $sImage4 != "" )
							$bImage4	= ValidImage($sImage4, $iGalleryUnq, $rsRow["Alt4UL"], $iXSize4, $iYSize4, $iFileSize4, $sStatus4, 4);
						If ( $sImage5 != "" )
							$bImage5	= ValidImage($sImage5, $iGalleryUnq, $rsRow["Alt5UL"], $iXSize5, $iYSize5, $iFileSize5, $sStatus5, 5);
						
						If ( ( !$bImage2 ) && ( !$bImage2 ) && ( !$bImage2 ) && ( !$bImage2 ) )
							echo "Even though this image has Alternate View Images, none of them are appropriate for use with Aricaur.<br><br>Images must be JPG, PSD, TIFF (uncompressed - no LZW), TGA, FLASHPIX or PNG. <b>GIF are not allowed. And smaller than 45megs.</b><br><br>";
						If ( ( $bImage2 ) || ( $bImage2 ) || ( $bImage2 ) || ( $bImage2 ) )
						{
							echo "Add Aricaur link using which Alternate View Image as the Aricaur Primary Image?<br>Please make sure the thumbnail and image chosen for Aricaur are identical.<br><br>";
							echo "<b>Current Gallery Thumbnail:</b><br><img src = '" . $sSiteURL . "/Attachments/DispThumb.php?sAccountUnq=" . $rsRow["ThumbUL"] . "&sThumbnail=" . $rsRow["Thumbnail"] . "&iGalleryUnq=" . $iGalleryUnq . "'><br><br>";
						}
						If ( $bImage2 ){
							$sTempName	= $sImage2;
							$sTempName	= str_replace(".tif", ".jpg", $sTempName);
							$sTempName	= str_replace(".tga", ".jpg", $sTempName);
							$sTempName	= str_replace(".png", ".jpg", $sTempName);
							$sTempName	= str_replace(".psd", ".jpg", $sTempName);
							echo "<table border=1 cellspacing=0 width=50%>";
							echo "<tr><td><b>File name: </td><td>" . $sTempName . "</td><td align=center><b>Current Aricaur Thumbnail</td></tr>";
							$sFilePath		= $sGalleryPath . "/" . $rsRow["Alt2UL"] . "/" . $iGalleryUnq . "/Aricaur/" . $sTempName;
							$sFilePath		= str_replace("\\", "/", $sFilePath);
							$sFilePath		= str_replace("//", "/", $sFilePath);
							If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
							{
								$sFilePath	= DOMAIN_Conf("IG") . "/" . $rsRow["Alt2UL"] . "/" . $iGalleryUnq . "/Aricaur/" . $sTempName;
								$sFilePath	= str_replace("\\", "/", $sFilePath);
								$sFilePath	= str_replace("//", "/", $sFilePath);
								echo "<tr><td><b>Description: </td><td>" . $rsRow["Image2Desc"] . "</td><td rowspan=6 valign=top><img src='" . $sFilePath . "'></td></tr>";
							}Else{
								echo "<tr><td><b>Description: </td><td>" . $rsRow["Image2Desc"] . "</td><td rowspan=6 valign=top>No thumb.</td></tr>";
							}
							echo "<tr><td><b>Alt Tag: </td><td>" . $rsRow["AltTag2"] . "</td></tr>";
							If ( $rsRow["XSize2"] == "0" ){
								echo "<tr><td><b>Dimensions: </td><td>unknown</td></tr>";
							}Else{
								echo "<tr><td><b>Dimensions: </td><td>" . $iXSize2 . "x" . $iYSize2 . "</td></tr>";
							}
							echo "<tr><td><b>File size: </td><td>" . number_format($iFileSize2,0) . " bytes</td></tr>";
							echo "<tr><td colspan=2 align=center><a href='" . $sSiteURL . "/IG_Popup.php?iImageUnq=" . $iImageUnq . "&sImageNum=2' target='_blank' class='MediumNavPage'>View image in popup</a></td></tr>";
							If ( $sAricaur == "2" ){
								echo "<tr><td><b>Use this image </td><td><input type='radio' name='sAricaurLink' value='2' checked></td></tr>";
							}Else{
								echo "<tr><td><b>Use this image </td><td><input type='radio' name='sAricaurLink' value='2'></td></tr>";
							}
							echo "</table>";
						}Else{
							echo $sStatus2;
						}
						If ( $bImage3 ){
							$sTempName	= $sImage3;
							$sTempName	= str_replace(".tif", ".jpg", $sTempName);
							$sTempName	= str_replace(".tga", ".jpg", $sTempName);
							$sTempName	= str_replace(".png", ".jpg", $sTempName);
							$sTempName	= str_replace(".psd", ".jpg", $sTempName);
							echo "<br><br><table border=1 cellspacing=0 width=50%>";
							echo "<tr><td><b>File name: </td><td>" . $sTempName . "</td><td align=center><b>Current Aricaur Thumbnail</td></tr>";
							$sFilePath		= $sGalleryPath . "/" . $rsRow["Alt3UL"] . "/" . $iGalleryUnq . "/Aricaur/" . $sTempName;
							$sFilePath		= str_replace("\\", "/", $sFilePath);
							$sFilePath		= str_replace("//", "/", $sFilePath);
							$sFilePath		= str_replace("//", "/", $sFilePath);
							If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
							{
								$sFilePath	= DOMAIN_Conf("IG") . "/" . $rsRow["Alt3UL"] . "/" . $iGalleryUnq . "/Aricaur/" . $sTempName;
								$sFilePath	= str_replace("\\", "/", $sFilePath);
								$sFilePath	= str_replace("//", "/", $sFilePath);
								echo "<tr><td><b>Description: </td><td>" . $rsRow["Image3Desc"] . "</td><td rowspan=6 valign=top><img src='" . $sFilePath . "'></td></tr>";
							}Else{
								echo "<tr><td><b>Description: </td><td>" . $rsRow["Image3Desc"] . "</td><td rowspan=6 valign=top>No thumb.</td></tr>";
							}
							echo "<tr><td><b>Alt Tag: </td><td>" . $rsRow["AltTag3"] . "</td></tr>";
							If ( $rsRow["XSize3"] == "0" ){
								echo "<tr><td><b>Dimensions: </td><td>unknown</td></tr>";
							}Else{
								echo "<tr><td><b>Dimensions: </td><td>" . $iXSize3 . "x" . $iYSize3 . "</td></tr>";
							}
							echo "<tr><td><b>File size: </td><td>" . number_format($iFileSize3,0) . "k</td></tr>";
							echo "<tr><td colspan=2 align=center><a href='" . $sSiteURL . "/IG_Popup.php?iImageUnq=" . $iImageUnq . "&sImageNum=3' target='_blank' class='MediumNavPage'>View image in popup</a></td></tr>";
							If ( $sAricaur == "3" ){
								echo "<tr><td><b>Use this image </td><td><input type='radio' name='sAricaurLink' value='3' checked></td></tr>";
							}Else{
								echo "<tr><td><b>Use this image </td><td><input type='radio' name='sAricaurLink' value='3'></td></tr>";
							}
							echo "</table>";
						}Else{
							echo $sStatus3;
						}
						If ( $bImage4 ){
							$sTempName	= $sImage4;
							$sTempName	= str_replace(".tif", ".jpg", $sTempName);
							$sTempName	= str_replace(".tga", ".jpg", $sTempName);
							$sTempName	= str_replace(".png", ".jpg", $sTempName);
							$sTempName	= str_replace(".psd", ".jpg", $sTempName);
							echo "<br><br><table border=1 cellspacing=0 width=50%>";
							echo "<tr><td><b>File name: </td><td>" . $sTempName . "</td><td align=center><b>Current Aricaur Thumbnail</td></tr>";
							$sFilePath		= $sGalleryPath . "/" . $rsRow["Alt4UL"] . "/" . $iGalleryUnq . "/Aricaur/" . $sTempName;
							$sFilePath		= str_replace("\\", "/", $sFilePath);
							$sFilePath		= str_replace("//", "/", $sFilePath);
							If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
							{
								$sFilePath	= DOMAIN_Conf("IG") . "/" . $rsRow["Alt4UL"] . "/" . $iGalleryUnq . "/Aricaur/" . $sTempName;
								$sFilePath	= str_replace("\\", "/", $sFilePath);
								$sFilePath	= str_replace("//", "/", $sFilePath);
								echo "<tr><td><b>Description: </td><td>" . $rsRow["Image4Desc"] . "</td><td rowspan=6 valign=top><img src='" . $sFilePath . "'></td></tr>";
							}Else{
								echo "<tr><td><b>Description: </td><td>" . $rsRow["Image4Desc"] . "</td><td rowspan=6 valign=top>No thumb.</td></tr>";
							}
							echo "<tr><td><b>Alt Tag: </td><td>" . $rsRow["AltTag4"] . "</td></tr>";
							If ( $rsRow["XSize4"] == "0" ){
								echo "<tr><td><b>Dimensions: </td><td>unknown</td></tr>";
							}Else{
								echo "<tr><td><b>Dimensions: </td><td>" . $iXSize4 . "x" . $iYSize4 . "</td></tr>";
							}
							echo "<tr><td><b>File size: </td><td>" . number_format($iFileSize4,0) . "k</td></tr>";
							echo "<tr><td colspan=2 align=center><a href='" . $sSiteURL . "/IG_Popup.php?iImageUnq=" . $iImageUnq . "&sImageNum=4' target='_blank' class='MediumNavPage'>View image in popup</a></td></tr>";
							If ( $sAricaur == "4" ){
								echo "<tr><td><b>Use this image </td><td><input type='radio' name='sAricaurLink' value='4' checked></td></tr>";
							}Else{
								echo "<tr><td><b>Use this image </td><td><input type='radio' name='sAricaurLink' value='4'></td></tr>";
							}
							echo "</table>";
						}Else{
							echo $sStatus4;
						}
						If ( $bImage5 ){
							$sTempName	= $sImage5;
							$sTempName	= str_replace(".tif", ".jpg", $sTempName);
							$sTempName	= str_replace(".tga", ".jpg", $sTempName);
							$sTempName	= str_replace(".png", ".jpg", $sTempName);
							$sTempName	= str_replace(".psd", ".jpg", $sTempName);
							echo "<br><br><table border=1 cellspacing=0 width=50%>";
							echo "<tr><td><b>File name: </td><td>" . $sTempName . "</td><td align=center><b>Current Aricaur Thumbnail</td></tr>";
							$sFilePath		= $sGalleryPath . "/" . $rsRow["Alt5UL"] . "/" . $iGalleryUnq . "/Aricaur/" . $sTempName;
							$sFilePath		= str_replace("\\", "/", $sFilePath);
							$sFilePath		= str_replace("//", "/", $sFilePath);
							If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
							{
								$sFilePath	= DOMAIN_Conf("IG") . "/" . $rsRow["Alt5UL"] . "/" . $iGalleryUnq . "/Aricaur/" . $sTempName;
								$sFilePath	= str_replace("\\", "/", $sFilePath);
								$sFilePath	= str_replace("//", "/", $sFilePath);
								echo "<tr><td><b>Description: </td><td>" . $rsRow["Image5Desc"] . "</td><td rowspan=6 valign=top><img src='" . $sFilePath . "'></td></tr>";
							}Else{
								echo "<tr><td><b>Description: </td><td>" . $rsRow["Image5Desc"] . "</td><td rowspan=6 valign=top>No thumb.</td></tr>";
							}
							echo "<tr><td><b>Alt Tag: </td><td>" . $rsRow["AltTag5"] . "</td></tr>";
							If ( $rsRow["XSize5"] == "0" ){
								echo "<tr><td><b>Dimensions: </td><td>unknown</td></tr>";
							}Else{
								echo "<tr><td><b>Dimensions: </td><td>" . $iXSize5 . "x" . $iYSize5 . "</td></tr>";
							}
							echo "<tr><td><b>File size: </td><td>" . number_format($iFileSize5,0) . "k</td></tr>";
							echo "<tr><td colspan=2 align=center><a href='" . $sSiteURL . "/IG_Popup.php?iImageUnq=" . $iImageUnq . "&sImageNum=5' target='_blank' class='MediumNavPage'>View image in popup</a></td></tr>";
							If ( $sAricaur == "5" ){
								echo "<tr><td><b>Use this image </td><td><input type='radio' name='sAricaurLink' value='5' checked></td></tr>";
							}Else{
								echo "<tr><td><b>Use this image </td><td><input type='radio' name='sAricaurLink' value='5'></td></tr>";
							}
							echo "</table>";
						}Else{
							echo $sStatus5;
						}
						
						If ( ( $sAricaur == "" ) ){
							echo "<br><b>**Currently this image has no Aricaur link.</b><br><br>";
						}Else{
							echo "<br><b>OR</b><br><input type='radio' name='sAricaurLink' value='R'> Remove Aricaur link from this image<br><br>";
							echo "**Note: creating an Aricaur link involved also creating a special Aricaur thumbnail. Depending on how big (in bytes) the image you choose to use with your Aricaur link is, this may take several seconds to process.<br>";
						}
						echo "<input type='submit' value='Edit Aricaur Link'>";
					}Else{
						echo "Error finding image information.";
					}
					?>
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
	Function ReturnImageName($iImageUnq)
	{
		$sQuery			= "SELECT Image FROM Images (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow[0];
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
		Global $iDBLoc;
		Global $aVariables;
		Global $aValues;
		
		?>
		<SCRIPT LANGUAGE=javascript>
		<!--
		
			function ReturnToMain(){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iGalleryUnq=<?=$iGalleryUnq?>&iDBLoc=<?=$iDBLoc?>";
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
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function ValidImage($sImageName, $iGalleryUnq, $iAccountUnq, &$iXSize, &$iYSize, &$iFileSize, &$sStatus, $x)
	{
		Global $iThumbComponent;
		Global $GFL;
		Global $sGalleryPath;
		
		$sStatus = "";
		$bValidFile = FALSE;
		$sTempName	= $sImageName;
		$sTempName	= str_replace(".tif", ".jpg", $sTempName);
		$sTempName	= str_replace(".tga", ".jpg", $sTempName);
		$sTempName	= str_replace(".png", ".jpg", $sTempName);
		$sTempName	= str_replace(".psd", ".jpg", $sTempName);
		$sFilePath	= $sGalleryPath . "/" . $iAccountUnq . "/" . $iGalleryUnq . "/" . $sTempName;
		$sFilePath	= str_replace("\\", "/", $sFilePath);
		$sFilePath	= str_replace("//", "/", $sFilePath);

		If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
		{
			// check that file size is less than 45 megs
			$iFileSize = filesize($sFilePath);
			If ( $iFileSize <= 47000000 )	// should be 47185920 but want a bit of slack
			{
				// get the type
				If ( $iThumbComponent == $GFL )
				{
					// turn off all error reporting because this COM object won't report if the file is not
					//	really an image. Then it'll just crash when trying to LoadBitmap. If the file uploaded
					//	is something like an Excel file (w/o X or Y dimensions) then we just want the 
					//	$1XSize and $1YSize to be left as 0.
					error_reporting(0);
					$objGFL = new COM("GflAx.GflAx");
					$objGFL->EnableLZW = FALSE;		// LZW is not allowed w/ Aricaur - this should also stop GIF's
					$objGFL->LoadBitmap($sFilePath);
					$iXSize	= $objGFL->Width;
					$iYSize	= $objGFL->Height;
					If ( $iXSize != 0 )
						$bValidFile = TRUE;
					unset($objGFL);
					error_reporting(E_ALL ^ E_NOTICE);	// set error reporting back to the default
				}Else{
					// use native PHP
				    $image_info = getImageSize($sFilePath);
				    
				    switch ($image_info['mime']) {
				        case 'image/jpeg':
				            if (imagetypes() & IMG_JPG)  {
				                $imgSource = imageCreateFromJPEG($sFilePath);
				                $bValidFile = TRUE;
				            }
				            break;
				        case 'image/png':
				            if (imagetypes() & IMG_PNG)  {
				                $imgSource = imageCreateFromPNG($sFilePath);
				                $bValidFile = TRUE;
				            }
				            break;
				        case 'image/wbmp':
				            if (imagetypes() & IMG_WBMP)  {
				                $imgSource = imageCreateFromWBMP($sFilePath);
				                $bValidFile = TRUE;
				            }
				            break;
				    }
				    If ( $bValidFile ) {
				    	If ( ( $iXSize == "" ) || ( $iXSize == "0" ) )	// check that the X, Y dimensions exist
				    	{
				        	$iXSize = imagesx($imgSource);
				        	$iYSize = imagesy($imgSource);
				        }
					}
				}
			}Else{
				$sStatus = "Alt View Image # " . $x . " is larger than 45 megabytes and will not be used.";
			}
		}Else{
			$sStatus = "The actual file for Alt View Image # " . $x . " could not be found.";
		}	
		
		Return $bValidFile;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function CreateThumb($sImageName, $iAccountUnq, $iGalleryUnq)
	{
		Global $iThumbComponent;
		Global $GFL;
		Global $iLoginAccountUnq;
		Global $sGalleryPath;
	
		$intXSize		= 240;
		$sFilePath		= $sGalleryPath . "/" . $iAccountUnq . "/" . $iGalleryUnq . "/Aricaur/";
		$sFilePath		= str_replace("\\", "/", $sFilePath);
		$sFilePath		= str_replace("//", "/", $sFilePath);
		$sFilePath		= str_replace("//", "/", $sFilePath);
		$sSourcePath	= $sGalleryPath . "/" . $iAccountUnq . "/" . $iGalleryUnq . "/" . $sImageName;
		$sSourcePath	= str_replace("\\", "/", $sSourcePath);
		$sSourcePath	= str_replace("//", "/", $sSourcePath);
		$sSourcePath	= str_replace("//", "/", $sSourcePath);
		// Make sure the Aricaur directory exist...make it if it doesn't
		If ( ! file_exists($sFilePath))
		{
			If ( $GLOBALS["sOS"] == "UNIX" ) {
				mkdirs_nix($sFilePath);
			}Else{
				mkdirs_win($sFilePath);
			}
		}
		$sFilePath		= $sFilePath . $sImageName;

		If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
		{
			// don't bother recreating it...especially if the source file is 40 megs!
		}Else{
			If ( $iThumbComponent == $GFL ){
				$objGFL = new COM("GflAx.GflAx");
				$objGFL->EnableLZW = FALSE;
				error_reporting(0);
				$objGFL->LoadBitmap($sSourcePath);
				$intYSize = intval(($intXSize / $objGFL->Width) * $objGFL->Height);
				$objGFL->LoadThumbnail($sSourcePath, $intXSize, $intYSize);
				$objGFL->SaveFormat = 1;
				$objGFL->SaveBitmap($sFilePath);
				error_reporting(E_ALL ^ E_NOTICE);
				unset($objGFL);
			}Else{
				// use native PHP
			    $image_info = getImageSize($sSourcePath) ;
			    
			    switch ($image_info['mime']) {
			        case 'image/jpeg':
			            if (imagetypes() & IMG_JPG)  {
			                $imgSource = imageCreateFromJPEG($sSourcePath) ;
			            }
			            break;
			        case 'image/png':
			            if (imagetypes() & IMG_PNG)  {
			                $imgSource = imageCreateFromPNG($sSourcePath) ;
			            }
			            break;
			        case 'image/wbmp':
			            if (imagetypes() & IMG_WBMP)  {
			                $imgSource = imageCreateFromWBMP($sSourcePath) ;
			            }
			            break;
			    }
	
		        $iSourceXSize = imagesx($imgSource);
		        $iSourceYSize = imagesy($imgSource);
	
		        // thumbnail width = target * original width / original height
		        $intYSize = intval(($intXSize / $iSourceXSize) * $iSourceYSize);
		        $imgThumb = imageCreateTrueColor($intXSize,$intYSize);
		        
		        imageCopyResampled($imgThumb, $imgSource, 0, 0, 0, 0, $intXSize, $intYSize, $iSourceXSize, $iSourceYSize);
		        
		        imageJPEG($imgThumb,$sFilePath);
		        
		        imageDestroy($imgSource);
		        imageDestroy($imgThumb);
			}
	
			// get the file size and increment the amount of HD space they are using
			If ( file_exists($sFilePath))
			{
				$iFileSize = filesize($sFilePath);
				G_ADMINISTRATION_IncrementHDSpaceUsed($iAccountUnq, $iFileSize);
			}
		}
	}
	//************************************************************************************
?>
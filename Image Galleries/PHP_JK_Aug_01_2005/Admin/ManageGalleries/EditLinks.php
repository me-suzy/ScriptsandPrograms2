<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iGalleryUnq	= "";
	$iCategoryUnq	= Trim(Request("iCategoryUnq"));
	
	WriteScripts();

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
		Global $sURL;
		Global $sOnSite;
		Global $sDescription;
		Global $iLoginAccountUnq;
		Global $sOldURL;
		Global $sNewURL;
		Global $sOldDescription;
		Global $sNewDescription;
		Global $sOldOnSite;
		
		$sError			= "";
		$sSuccess		= "";
		$sAction		= Trim(Request("sAction"));
		$iGalleryUnq	= Trim(Request("iGalleryUnq"));
		
		If ( $iGalleryUnq != "" ) {
			If ( $sAction == "AddLink" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
					$sURL			= Trim(Request("sURL"));
					$sDescription	= Trim(Request("sDescription"));
					$sOnSite		= Trim(Request("sOnSite"));
					If ( $sURL == "" ) {
						$sError = "Please enter a URL for the new link.<br>";
					}Else{
						If ( $sDescription == "" ) {
							$sError = "Please enter a description for the new link.<br>";
						}Else{
							$sQuery			= "SELECT ImageUnq FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
								DB_Insert ("INSERT INTO IGMiscLinks (ImageUnq,URL,OnSite,Description) VALUES (" . $rsRow["ImageUnq"] . ", '" . SQLEncode($sURL) . "', '" . SQLEncode($sOnSite) . "', '" . SQLEncode($sDescription) . "')");
							
							$sSuccess = "New link added successfully.";
						}
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot add links to images within it.<br>";
				}
			}ElseIf ( $sAction == "UpdateLinks" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
					ForEach ($_POST as $sTextField=>$sValue)
					{
						If ( strpos($sTextField, "sOldURL") !== false )
						{
							$iLinkUnq			= str_replace("sOldURL", "", $sTextField);
							$sOldURL			= Request($sTextField);
							$sNewURL			= Request("sNewURL" . $iLinkUnq);
							$sOldDescription 	= Request("sOldDescription" . $iLinkUnq);
							$sNewDescription	= Request("sNewDescription" . $iLinkUnq);
							$sOldOnSite			= Request("sOldOnSite" . $iLinkUnq);
							$sOnSite			= Request("sOnSite" . $iLinkUnq);
							
							If ( ( $sOldURL != $sNewURL ) || ( $sOldDescription != $sNewDescription ) || ( $sOldOnSite != $sOnSite ) ) {
								$sQuery			= "SELECT ImageUnq FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
								$rsRecordSet	= DB_Query($sQuery);
								While ( $rsRow = DB_Fetch($rsRecordSet) )
									DB_Update ("UPDATE IGMiscLinks SET URL = '" . SQLEncode($sNewURL) . "', OnSite = '" . $sOnSite . "', Description = '" . SQLEncode($sNewDescription) . "' WHERE URL = '" . SQLEncode($sOldURL) . "' AND OnSite = '" . SQLEncode($sOldOnSite) . "' AND Description = '" . SQLEncode($sOldDescription) . "' AND ImageUnq = " . $rsRow["ImageUnq"]);
								
								If ( $sSuccess == "" )
									$sSuccess = "Links successfully modified.";
							}
						}ElseIf ( strpos($sTextField, "sDelete") !== false ){						
							$iLinkUnq			= str_replace("sDelete", "", $sTextField);
							$sOldURL			= Request("sOldURL" . $iLinkUnq);
							$sOldDescription	= Request("sOldDescription" . $iLinkUnq);
							$sOldOnSite			= Request("sOldOnSite" . $iLinkUnq);
							
							$sQuery			= "SELECT ImageUnq FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
								DB_Update ("DELETE FROM IGMiscLinks WHERE URL = '" . SQLEncode($sOldURL) . "' AND OnSite = '" . SQLEncode($sOldOnSite) . "' AND Description = '" . SQLEncode($sOldDescription) . "' AND ImageUnq = " . $rsRow["ImageUnq"]);
						}
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot update or remove links from images within it.<br>";
				}
			}
			
			If ( $sError != "" )
				DOMAIN_Message($sError, "ERROR");
			If ( $sSuccess != "" )
				DOMAIN_Message($sSuccess, "SUCCESS");

			WriteForm();
		}Else{
			DOMAIN_Message("Missing iGalleryUnq. Unable to edit the gallery.", "ERROR");
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
		Global $iDBLoc;
		Global $aVariables;
		Global $aValues;
		Global $sOnSite;
		
		$sBGColor	= $GLOBALS["BGColor2"];
		$iCount		= 0;
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Manage Links in Gallery</b></font>
					<br>
					<b>Managing links for all images in the gallery:</b> <?=ReturnGalleryName($iGalleryUnq);?>
					<br>
					This will change links on all images within the gallery. 
					If a link is added here, it will be added to all images in the gallery. 
					If a link is changed or deleted, it will be changed or deleted from all images in the gallery.
					<br><br>
					<form name='AddLink' action='EditLinks.php' method='post'>
					Add a new link:
					<?php 
					$aVariables[0] = "sAction";
					$aVariables[1] = "iDBLoc";
					$aVariables[2] = "iGalleryUnq";
					$aValues[0] = "AddLink";
					$aValues[1] = $iDBLoc;
					$aValues[2] = $iGalleryUnq;
					Echo DOMAIN_Link("P");
					?>
					<table width=100?>
						<tr>
							<td>URL: </td>
							<td><input type='text' name='sURL' value=''></td>
							<td>Description: </td>
							<td><input type='text' name='sDescription' value=''></td>
							<td>
								<table>
									<tr>
										<td><b>Onsite</td>
										<td><input type='radio' name='sOnSite' value='Y' checked></td>
										<td><b>Offsite</td>
										<td><input type='radio' name='sOnSite' value='N'></td>
									</tr>
								</table>
							</td>
							<td><input type='submit' value='Create Link'></td>
						</tr>
					</table>
					</form>
					
					<form name='ManageLinks' action='EditLinks.php' method='post'>
					<?php 
					$aValues[0] = "UpdateLinks";
					Echo DOMAIN_Link("P");
					?>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>URL</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Description</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Type</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Delete</b></td>
						</tr>
						<?php 
						$sColor1 = $GLOBALS["BGColor1"];
						$sColor2 = $GLOBALS["PageBGColor"];
						
						$sQuery			= "SELECT DISTINCT L.URL, L.OnSite, L.Description FROM IGMiscLinks L (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE L.ImageUnq = IG.ImageUnq AND IG.GalleryUnq = " . $iGalleryUnq;
						$rsRecordSet	= DB_Query($sQuery);
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor == $sColor1 ) {
								$sBGColor = $sColor2;
							}Else{
								$sBGColor = $sColor1;
							}
							$sURL				= htmlentities($rsRow["URL"]);
							$sDescription		= htmlentities($rsRow["Description"]);
							$sOnSite			= Trim($rsRow["OnSite"]);
							?>
							<tr>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<input type='hidden' name='sOldURL<?=$iCount?>' value="<?=htmlentities($sURL)?>">
									<input type='text' name='sNewURL<?=$iCount?>' value="<?=htmlentities($sURL)?>" size=30 maxlength=250>
								</td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<input type='hidden' name='sOldDescription<?=$iCount?>' value="<?=htmlentities($sDescription)?>">
									<input type='text' name='sNewDescription<?=$iCount?>' value="<?=htmlentities($sDescription)?>" size=30 maxlength=250>
								</td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<table>
										<tr>
											<td><input type='hidden' name='sOldOnSite<?=$iCount?>' value='<?=$sOnSite?>'><b>Onsite</td>
											<td><input type='radio' name='sOnSite<?=$iCount?>' value='Y' <?php If ( $sOnSite == "Y" )  Echo "checked";?>></td>
											<td><b>Offsite</td>
											<td><input type='radio' name='sOnSite<?=$iCount?>' value='N' <?php If (( $sOnSite == "N" ) Or ( $sOnSite == "" ))  Echo "checked";?>></td>
										</tr>
									</table>
								</td>
								<td align=center bgcolor=<?=$sBGColor?> valign=top><input type='checkbox' name="sDelete<?=$iCount?>" value="<?=$iCount?>"></td>
							</tr>
							<?php 
							$iCount++;
						}
						?>
					</table>
					<center>
					<input type='submit' value='Update Links'>
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
	Function ReturnGalleryName($iGalleryUnq)
	{
		$sQuery			= "SELECT Name FROM Galleries (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
			Return $rsRow[0];
			
		Return "";
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This writes the JavaScript out even if the Main() Sub isn't called in case the	*
	//*		admin has creation rights, but not edit rights.								*
	//*																					*
	//************************************************************************************
	Function WriteScripts()
	{
		Global $iCategoryUnq;
		Global $iDBLoc;
		Global $aVariables;
		Global $aValues;
		 ?>
		<SCRIPT LANGUAGE=javascript>
		<!--
		
		function ReturnToMain(){
			document.location = "index.php?<?=DOMAIN_Link("G")?>&iCategoryUnq=<?=$iCategoryUnq ?>&iDBLoc=<?=$iDBLoc ?>";
		}
		
		//-->
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
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:ReturnToMain();'><img src='../../Images/Administrative/Return.gif' Width=30 Height=38 Border=0 Alt='Return to the main gallery management screen.'></a></td>";
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
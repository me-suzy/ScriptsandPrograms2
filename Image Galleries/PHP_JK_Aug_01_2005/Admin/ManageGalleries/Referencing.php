<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$sAction		= Trim(Request("sAction"));
	$iGalleryUnq	= Trim(Request("iGalleryUnq"));
	$iDestination	= Trim(Request("iDestination"));
	$iDBLoc			= Trim(Request("iDBLoc"));
	$iTtlNumItems	= Trim(Request("iTtlNumItems"));
	$iCategoryUnq	= Trim(Request("iCategoryUnq"));
	
	WriteScripts();
	
	If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") || ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) && (ACCNT_ReturnRights("PHPJK_IG_REF_GALLERY")) ) {
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
		Global $iDestination;
		Global $iDBLoc;
		Global $sAction;
		Global $iLoginAccountUnq;
		
		$sError		= "";
		$sSuccess	= "";

		If ( $sAction == "ReferenceGallery" ) {
			If ( ACCNT_ReturnRights("PHPJK_IG_REF_GALLERY") ) {
				$bDomainAdmin = FALSE;
				If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") )
					$bDomainAdmin = TRUE;
	
				// see if they can even reference galleries on the other domain that this image is referenced in
				If ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iDestination, "") )
				{
					// get the position of the FIRST image in the new gallery
					$sQuery			= "SELECT COUNT(*)+1 FROM ImagesInGallery WHERE GalleryUnq = " . $iDestination;
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) ) {
						$iPosition = $rsRow[0];
					}Else{
						$iPosition = 1;
					}
					
					// get all the images in the source gallery -- loop through each and process each
					$sQuery			= "SELECT * FROM ImagesInGallery WHERE GalleryUnq = " . $iGalleryUnq;
					$rsRecordSet	= DB_Query($sQuery);
					While ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						$iImageUnq = $rsRow["ImageUnq"];
						//	make sure the image is not already referenced in the dest gallery
						If ( NotInDest($iImageUnq, $iDestination) )
						{
							/*	if not, get the primary gallery and domain - do for each in case it changes
									insert the new record
									add 1 to the position
							 get the primary gallery and domain (must do this for each image in the source gallery)*/
							$sQuery			= "SELECT DISTINCT PrimaryG, PrimaryD FROM ImagesInGallery WHERE ImageUnq = " . $iImageUnq;
							$rsRecordSet2	= DB_Query($sQuery);
							If ( $rsRow2 = DB_Fetch($rsRecordSet2) )
								$iPrimaryG = $rsRow2["PrimaryG"];
							
							// insert the new record
							DB_Insert ("INSERT INTO ImagesInGallery VALUES (" . $iImageUnq . ", " . $iDestination . ", GetDate(), 1, " . $iPosition . ", " . $iPrimaryG . ", 1, 0, 0, 0, 0, 0)");
							$iPosition++;
						}
					}

					If ( $sSuccess == "" ) {
						$sSuccess = "Image(s) successfully added to the destination gallery.";
						DOMAIN_Message($sSuccess, "SUCCESS");
					}
				}
			}
		}
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		
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
		Global $iGalleryUnq;
		Global $iDestination;
		Global $iDBLoc;
		Global $iTtlNumItems;
		Global $iLoginAccountUnq;
		Global $aVariables;
		Global $aValues;
		
		$sBGColor		= $GLOBALS["PageBGColor"];
		$sGalleryName	= G_ADMINISTRATION_GetGalleryName($iGalleryUnq);
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ReferenceGallery.sAction.value = sAction;
				document.ReferenceGallery.submit();
			}
			
		</script>
		
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<br>
					When referencing images, please note:
					<ul>
						<li>You can add the same image(s) to galleries multiple times without
							the system adding it more that one time. If you are referencing
							the images from one gallery in another and add images to the
							source gallery, re-referencing those images will not create
							duplicate entries in the destination directory.
					</ul>
					<form name='ReferenceGallery' action='Referencing.php' method='post'>
					<?php G_STRUCTURE_HeaderBar_ADMIN("ReferenceGalleriesHead.gif", "", "", "Galleries");
					
					$aVariables[0] = "sAction";
					$aVariables[1] = "iTtlNumItems";
					$aVariables[2] = "iDBLoc";
					$aVariables[3] = "iGalleryUnq";
					$aValues[0] = "";
					$aValues[1] = $iTtlNumItems;
					$aValues[2] = $iDBLoc;
					$aValues[3] = $iGalleryUnq;
					Echo DOMAIN_Link("P")
					?>
					<table width=671 cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
					<table cellpadding = 10 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor = <?=$sBGColor?>>
								<font color='<?=$GLOBALS["TextColor1"]?>'>
								<?php 
								If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
									$sQuery = "SELECT AccountUnq, GalleryUnq, Name FROM Galleries (NOLOCK) WHERE GalleryUnq <> " . $iGalleryUnq;
								}Else{
									$sQuery = "SELECT AccountUnq, GalleryUnq, Name FROM Galleries (NOLOCK) WHERE AccountUnq = " . $iLoginAccountUnq . " AND GalleryUnq <> " . $iGalleryUnq;
								}
								$rsRecordSet	= DB_Query($sQuery);
								If ( DB_NumRows($rsRecordSet) > 0 )
								{
									Echo "Gallery to reference this galleries (<b>" . $sGalleryName . "</b>) images in:</b><br>";
									Echo "<select name='iDestination'>";
									While ( $rsRow = DB_Fetch($rsRecordSet) )
										Echo "<option value=\"" . $rsRow["GalleryUnq"] . "\">" . $rsRow["Name"] . "</option>";
									Echo "</select>";
									Echo "<font size=-2><br>Images from the " . $sGalleryName . " gallery will appear in the gallery chosen from the above list.</font>";
									Echo "<br><br>";
									Echo "<center><input type='button' value=\"Reference Images\" onClick=\"SubmitForm('ReferenceGallery');\"></center>";
								}Else{
									Echo "No galleries to reference within.";
								}
								?>
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
	Function NotInDest($iImageUnq, $iDestGalleryUnq)
	{
		$sQuery			= "SELECT * FROM ImagesInGallery WHERE GalleryUnq = " . $iDestGalleryUnq . " AND ImageUnq = " . $iImageUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) ){
			Return False;
		}Else{
			Return True;
		}
		Return False;
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
<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iGalleryUnq		= Trim(Request("iGalleryUnq"));
	$iDBLoc				= Trim(Request("iDBLoc"));
	$iCategoryUnq		= Trim(Request("iCategoryUnq"));
	
	WriteScripts();
	
	If ( ACCNT_ReturnRights("PHPJK_IG_ADD_CR_2IMAGES") ) {
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
		Global $iLoginAccountUnq;
		Global $iDBLoc;
		Global $iGalleryUnq;
		
		$sError		= "";
		$sSuccess	= "";
		
		$sAction			= Trim(Request("sAction"));
		
		If ( $iGalleryUnq != "" )
		{
			If ( $sAction == "AddCopyright" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{
					$sQuery			= "SELECT ImageUnq FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq;
					$rsRecordSet	= DB_Query($sQuery);
					If ( DB_NumRows($rsRecordSet) > 0 )		// first check to see if there are even any images in the gallery (the error messages below are confusing if the prob is just that there are no images)
					{
						ForEach ($_POST["iCopyUnq"] as $sCheckbox=>$sValue)
						{
							$iCount			= 0;
							If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
								$sQuery	= "SELECT ImageUnq FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq . " AND ImageUnq NOT IN (SELECT DISTINCT CR.ImageUnq FROM ImagesInGallery IG (NOLOCK), IGImageCRs CR (NOLOCK) WHERE IG.GalleryUnq = " . $iGalleryUnq . " AND CR.ImageUnq = IG.ImageUnq AND CR.CopyUnq = " . $sValue . ")";
							}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
								$sTemp			= "";
								$sQuery			= "SELECT DISTINCT CR.ImageUnq FROM ImagesInGallery IG (NOLOCK), IGImageCRs CR (NOLOCK) WHERE IG.GalleryUnq = " . $iGalleryUnq . " AND CR.ImageUnq = IG.ImageUnq AND CR.CopyUnq = " . $sValue;
								$rsRecordSet	= DB_Query($sQuery);
								While ( $rsRow = DB_Fetch($rsRecordSet) )
									$sTemp .= $rsRow["ImageUnq"] . ",";
								$sTemp .= "0";
								$sQuery	= "SELECT ImageUnq FROM ImagesInGallery (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq . " AND ImageUnq NOT IN (" . $sTemp . ")";
							}
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
							{
								DB_Insert ("INSERT INTO IGImageCRs VALUES (" . $rsRow["ImageUnq"] . ", " . $sValue . ", '')");
								$iCount++;
							}
							
							// got to give some sort of feedback - this is a lot for that, but they need SOMETHING
							$sQuery			= "SELECT Copyright FROM IGCopyrights (NOLOCK) WHERE CopyUnq = " . $sValue;
							$rsRecordSet	= DB_Query($sQuery);
							If ( $rsRow = DB_Fetch($rsRecordSet) )
							{
								If ( $iCount > 0 ) {
									$sSuccess = $sSuccess . "Successfully added the copyright <b>" . $rsRow["Copyright"] . "</b> to the images in this gallery.<br>";
								}Else{
									$sSuccess = $sSuccess . "All images in this gallery already had the copyright <b>" . $rsRow["Copyright"] . "</b>. No new copyrights added.<br>";
								}
							}
						}
					}Else{
						$sError = "No images in this gallery to add copyrights to!";
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot add copyrights to images within it.<br>";
				}
			}ElseIf ( $sAction == "RemoveCopyright" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{
					ForEach ($_POST["iCopyUnq"] as $sCheckbox=>$sValue)
					{
						$iCount			= 0;
						$sQuery			= "SELECT IG.ImageUnq FROM ImagesInGallery IG (NOLOCK), IGImageCRs CR (NOLOCK) WHERE IG.GalleryUnq = " . $iGalleryUnq . " AND CR.ImageUnq = IG.ImageUnq AND CR.CopyUnq = " . $sValue;
						$rsRecordSet	= DB_Query($sQuery);
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							DB_Update ("DELETE FROM IGImageCRs WHERE ImageUnq = " . $rsRow["ImageUnq"] . " AND CopyUnq = " . $sValue);
							$iCount++;
						}						
						
						// got to give some sort of feedback - this is a lot for that, but they need SOMETHING
						$sQuery			= "SELECT Copyright FROM IGCopyrights (NOLOCK) WHERE CopyUnq = " . $sValue;
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $iCount > 0 ) {
								$sSuccess = $sSuccess . "Successfully removed the copyright <b>" . $rsRow["Copyright"] . "</b> from the images in this gallery.<br>";
							}Else{
								$sSuccess = $sSuccess . "No images in this gallery have the copyright <b>" . $rsRow["Copyright"] . "</b>. The copyright was not deleted.<br>";
							}
						}
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot remove copyrights from images within it.<br>";
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
		Global $aVariables;
		Global $aValues;
		Global $iDBLoc;
		
		$sBGColor = $GLOBALS["BGColor2"];
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Manage Copyrights in Gallery</b></font>
					<br>
					<b>Managing copyrights for all images in the gallery:</b> <?=ReturnGalleryName($iGalleryUnq)?>
					<br>
					Changes made on this page will affect all images within this gallery.
					<br><br>
					<form name='AddCopyright' action='EditCopyrights.php' method='post'>
					Some images may already have these copyrights associated with them. 
					Adding copyrights here will not duplicate copyright associations that images in the gallery already have.
					<br><br>
					Add copyrights:<br>
					<?php 
					$aVariables[0] = "sAction";
					$aVariables[1] = "iDBLoc";
					$aVariables[2] = "iGalleryUnq";
					$aValues[0] = "AddCopyright";
					$aValues[1] = $iDBLoc;
					$aValues[2] = $iGalleryUnq;
					Echo DOMAIN_Link("P");
					?>
					<select name='iCopyUnq[]' multiple size=6>
						<?php 
						$sQuery			= "SELECT CopyUnq, Copyright FROM IGCopyrights (NOLOCK) ORDER BY Copyright";
						$rsRecordSet	= DB_Query($sQuery);
						While ( $rsRow = DB_Fetch($rsRecordSet) )
							Echo "<option value='" . $rsRow["CopyUnq"] . "'>" . htmlentities($rsRow["Copyright"]) . "</option>";
						?>
					</select>
					<br>
					<input type='submit' value='Add Copyrights'>
					</form>
					
					<form name='RemoveCopyright' action='EditCopyrights.php' method='post'>
					Remove copyrights:<br>
					<?php 
					$aValues[0] = "RemoveCopyright";
					Echo DOMAIN_Link("P");
					?>
					<SELECT NAME='iCopyUnq[]' MULTIPLE size=6>
						<?php 
						$sQuery			= "SELECT DISTINCT IC.CopyUnq, IC.Copyright FROM IGCopyrights IC (NOLOCK), IGImageCRs II (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE IC.CopyUnq = II.CopyUnq AND IG.ImageUnq = II.ImageUnq AND IG.GalleryUnq = " . $iGalleryUnq . " ORDER BY IC.Copyright";
						$rsRecordSet	= DB_Query($sQuery);
						While ( $rsRow = DB_Fetch($rsRecordSet) )
							Echo "<option value='" . $rsRow["CopyUnq"] . "'>" . htmlentities($rsRow["Copyright"]) . "</option>";
						?>
					</select>
					<br>
					<input type='submit' value='Remove Copyrights'>
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
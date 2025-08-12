<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$sAction			= Trim(Request("sAction"));
	$iCategoryUnq		= Trim(Request("iCategoryUnq"));
	$iTtlNumItems		= Trim(Request("iTtlNumItems"));
	$iDBLoc				= Trim(Request("iDBLoc"));
	$iParentUnq			= Trim(Request("iParentUnq"));
	$iThumbComponent	= G_ADMINISTRATION_ASPImageInstalled();
	
	WriteScripts();

	If (ACCNT_ReturnRights("PHPJK_IG_EDIT_CAT")) {
		HeaderHTML();
		Main();
	}Else{
		DOMAIN_Message("You must login with Image Gallery System rights.", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $sAction;
		Global $iCategoryUnq;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iParentUnq;
		Global $iThumbComponent;
		
		$sError = "";
		
		If ( $sAction == "Upload" ) {
			$sError = G_UPLOAD_SaveCatalogFile($iCategoryUnq);
			If ( $sError == "" ) {
				DB_Update ("UPDATE IGCategories SET HasImage = 'Y' WHERE CategoryUnq = " . $iCategoryUnq);
				DOMAIN_Message("Category image updated successfully. You may have to refresh the webpage for the new image to appear.", "SUCCESS");
			}Else{
				DOMAIN_Message($sError, "ERROR");
			}
		}ElseIf ( $sAction == "Delete" ) {
			DelOldCatFiles($iCategoryUnq);
			DB_Update ("UPDATE IGCategories SET HasImage = 'N' WHERE CategoryUnq = " . $iCategoryUnq);
			DOMAIN_Message("Category image deleted successfully.", "SUCCESS");
		}

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
		Global $iCategoryUnq;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iParentUnq;
		Global $ASPIMAGE;
		Global $GFL;
		Global $iThumbComponent;

		?>
		<SCRIPT LANGUAGE=javascript>
		<!--
			extArray = new Array(".jpg",".gif",".JPG",".GIF");

			function LimitAttach()
			{
				file = document.ManageCatImage.File1.value;
				
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

		//-->
		</SCRIPT>
		<form ENCTYPE="multipart/form-data" onsubmit="return LimitAttach()" name='ManageCatImage' action='EditImage.php' method='post'>
		<input type='hidden' name='iCategoryUnq' value='<?=$iCategoryUnq?>'>
		<input type='hidden' name='iTtlNumItems' value='<?=$iTtlNumItems?>'>
		<input type='hidden' name='iDBLoc' value='<?=$iDBLoc?>'>
		<input type='hidden' name='iParentUnq' value='<?=$iParentUnq?>'>
		<input type='hidden' name='sAction' value='Upload'>
		
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Edit Category Image</b></font>
					<br><br>
					<input TYPE='FILE' NAME='File1' SIZE=30> <input type='submit' value='Upload Category Image'>
					<br>
					<?php 
					If ( $iThumbComponent == $ASPIMAGE ) {
						Echo "Upload .gif or .jpg files only. The files should be " . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_WIDTH") . " pixels wide and " . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_HEIGHT") . " pixels high. If the image is a different size, it will be stretched in the browser.";
					}ElseIf ( $iThumbComponent == $GFL ) {
						Echo "Upload images supported by GflAx only. The files should be " . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_WIDTH") . " pixels wide and " . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_HEIGHT") . " pixels high. If the image is a different size, it will be stretched in the browser.";
					}
					?>
				</td>
			</tr>
		</table>
		</form>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr>
				<td>
					<br><br>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<?php 
					$sFilePath = DOMAIN_Conf("PHP_JK_WEBROOT") . "/" . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_LOC") . "/" . "CatImage_" . $iCategoryUnq . ".jpg";
					$sFilePath	= str_replace("\\", "/", $sFilePath);
					$sFilePath	= str_replace("//", "/", $sFilePath);
					$sFilePath	= str_replace("//", "/", $sFilePath);
					If ( file_exists($sFilePath) )
					{
						// good place to make sure the database is up to date
						DB_Update ("UPDATE IGCategories SET HasImage = 'Y' WHERE CategoryUnq = " . $iCategoryUnq);
						?>
						<b>Current Image:</b><br>
						<img src='<?=DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_LOC"). "\\" . "CatImage_" . $iCategoryUnq . ".jpg"?>' width=<?=DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_WIDTH")?> height=<?=DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_HEIGHT")?> border=1>
						<br><br>
						<form name='DelCatImage' action='EditImage.php?iCategoryUnq=<?=$iCategoryUnq?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>&sAction=Delete' method='post'>
							<input type='submit' value=' Delete Category Image'>
						</form>
					<?php }Else{
						DB_Update ("UPDATE IGCategories SET HasImage = 'N' WHERE CategoryUnq = " . $iCategoryUnq);
						?>
						No category image.
					<?php }?>
				</td>
			</tR>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
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
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iParentUnq;
		?>
		<SCRIPT LANGUAGE=javascript>
		<!--
			
			function ReturnToMain(){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iParentUnq=<?=$iParentUnq?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
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
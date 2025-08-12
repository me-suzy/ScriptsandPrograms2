<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$sAction			= Request("sAction");
	$sDescription		= Trim(Request("sDescription"));
	$sName				= Trim(Request("sName"));
	$iTtlNumItems		= Trim(Request("iTtlNumItems"));
	$iDBLoc				= Trim(Request("iDBLoc"));
	$iParentUnq			= Trim(Request("iParentUnq"));
	$sRightsLvl			= Trim(Request("sRightsLvl"));
	$iThumbComponent	= G_ADMINISTRATION_ASPImageInstalled();
	
	WriteScripts();
	
	If ( $iParentUnq == "" )
		$iParentUnq = "0";
	
	If ( ACCNT_ReturnRights("PHPJK_IG_ADD_CAT") ) {
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
		Global $sGalleryPath;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $sAction;
		Global $sDescription;
		Global $sName;
		Global $iParentUnq;
		Global $sRightsLvl;
		Global $iThumbComponent;
		Global $sUseDB;
		
		$sError			= "";
		$sSuccess		= "";

		If ( $sAction == "AddCategory" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_IG_ADD_CAT") ) {
				$sError = "You must have rights to add Image Gallery categories before performing this action.";
			}Else{
				If ( $sName != "" )
				{
					If ( $GLOBALS["sUseDB"] == "MSSQL" ){
						$sQuery = "SELECT ISNULL(MAX(Position), 0) + 1 FROM IGCategories (NOLOCK) WHERE Parent = " . $iParentUnq;
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) ){
							$iMaxPos = $rsRow[0];
						}Else{
							$iMaxPos = 1;
						}
					}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ){
						$sQuery = "SELECT MAX(Position) FROM IGCategories (NOLOCK) WHERE Parent = " . $iParentUnq;
						$rsRecordSet	= DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) ){
							$iMaxPos = $rsRow[0]+1;
						}Else{
							$iMaxPos = 1;
						}
					}
					
					$sHasImage = "N";
					DB_Insert ("INSERT INTO IGCategories (DomainUnq,Name,Description,HasImage,Position,Parent,NumChildren,RightsLvl,TtlImages) VALUES (1, '" . SQLEncode($sName) . "', '" . SQLEncode($sDescription) . "', '" . $sHasImage . "', " . $iMaxPos . ", " . $iParentUnq . ", 0, '" . SQLEncode($sRightsLvl) . "',0)");
					
					$sQuery			= "SELECT @@IDENTITY";
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						$iCategoryUnq = $rsRow[0];
						$sError = G_UPLOAD_SaveCatalogFile($iCategoryUnq);
						If ( $sError == "" )
						{
							// insert the new record - check if there was an image or not
							$sFilePath	= DOMAIN_Conf("PHP_JK_WEBROOT") . "/" . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_LOC"). "\\" . "CatImage_" . $iCategoryUnq . ".jpg";
							$sFilePath	= str_replace("\\", "/", $sFilePath);
							$sFilePath	= str_replace("//", "/", $sFilePath);
							If ( file_exists($sFilePath) ) {
								DB_Update ("UPDATE IGCategories SET HasImage = 'Y' WHERE CategoryUnq = " . $iCategoryUnq);
							}Else{
								DB_Update ("UPDATE IGCategories SET HasImage = 'N' WHERE CategoryUnq = " . $iCategoryUnq);
							}
							DOMAIN_Message("New category created successfully.", "SUCCESS");
						}Else{
							DOMAIN_Message($sError, "ERROR");
						}
					}Else{
						DOMAIN_Message("Error creating new category.", "ERROR");
					}
					
					// add one to the number of children this new conference's parent has (if it's not a top level conf)
					$sQuery = "SELECT NumChildren FROM IGCategories WHERE CategoryUnq = " . $iParentUnq;
					$rsRecordSet	= DB_Query($sQuery);
					If ( ( $rsRow = DB_Fetch($rsRecordSet) ) && ( ! is_null($rsRow["NumChildren"]) ) ) {
						$iTemp = $rsRow["NumChildren"] + 1;
					}Else{
						$iTemp = 1;
					}
					
					DB_Update ("UPDATE IGCategories SET NumChildren = " . $iTemp . " WHERE CategoryUnq = " . $iParentUnq);

					$sDescription = "";
					$sName = "";
				}Else{
					$sError = "Please enter a name for your new category.";
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
		Global $iParentUnq;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $ASPIMAGE;
		Global $GFL;
		Global $iThumbComponent;
		Global $sRightsLvl;
		Global $sName;
		Global $sDescription;
		
		$sBGColor = $GLOBALS["BGColor2"];
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.NewCategory.sAction.value = sAction;
				document.NewCategory.submit();
			}
			
			extArray = new Array(".jpg",".gif",".JPG",".GIF");

			function LimitAttach()
			{
				file = document.NewCategory.File1.value;
				
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
		<form ENCTYPE="multipart/form-data" onsubmit="return LimitAttach()" name='NewCategory' action='New.php' method='post'>
		<input type='hidden' name='iTtlNumItems' value='<?=$iTtlNumItems?>'>
		<input type='hidden' name='iDBLoc' value='<?=$iDBLoc?>'>
		<input type='hidden' name='iParentUnq' value='<?=$iParentUnq?>'>
		<input type='hidden' name='sAction' value='New'>
		
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Create New Categories</b></font>
					<br>
					Return to the parent of the category: 
					<a href='index.php?iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>&iParentUnq=<?=$iParentUnq?>' class='MediumNavPage'><?=G_STRUCTURE_GetParentName($iParentUnq)?></a>.
					<br>
					Creating new categories here will make them children of the "<?=G_STRUCTURE_GetParentName($iParentUnq)?>" category.
					<br><br>
					<?php 
					If ( $iThumbComponent == $ASPIMAGE ) {
						Echo "Upload .gif or .jpg files only. The files should be " . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_WIDTH") . " pixels wide and " . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_HEIGHT") . " pixels high. If the image is a different size, it will be stretched in the browser.";
					}ElseIf ( $iThumbComponent == $GFL ) {
						Echo "Upload images supported by GflAx only. The files should be " . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_WIDTH") . " pixels wide and " . DOMAIN_Conf("IMAGEGALLERY_CAT_IMAGE_HEIGHT") . " pixels high. If the image is a different size, it will be stretched in the browser.";
					}
					?>
					<br><br>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Category Name</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Description</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Image</b></td>
						</tr>
						<?php $sBGColor = $GLOBALS["BGColor1"];?>
						<tr>
							<td bgcolor=<?=$sBGColor?>><input type='text' name='sName' value="<?=htmlentities($sName)?>" size=23 maxlength=250></td>
							<td bgcolor=<?=$sBGColor?>><input type='text' name='sDescription' value="<?=htmlentities($sDescription)?>" size=40 maxlength=250></td>
							<td bgcolor=<?=$sBGColor?>><input TYPE='FILE' NAME='File1' SIZE=20></td>
						</tr>
						<tr>
							<td colspan=3>
							<select name = "sRightsLvl">
								<?php 
								Echo "<option value = ''>No Rights Level</option>";
								$sQuery			= "SELECT * FROM RightsLookup (NOLOCK) ORDER BY RightsLvl";
								$rsRecordSet	= DB_Query($sQuery);
								While ( $rsRow = DB_Fetch($rsRecordSet) )
								{
									If ( strlen ($rsRow["RightsConst"]) > 45 ) {
										$sTemp = substr($rsRow["RightsConst"], 42) . "...";
									}Else{
										$sTemp = $rsRow["RightsConst"];
									}
									If ( $sRightsLvl == $rsRow["RightsLvl"] ) {
										Echo "<option value = \"" . htmlentities(Trim($rsRow["RightsLvl"])) . "\" Selected>" . $rsRow["RightsLvl"] . " - " . $sTemp . "</option>";
									}Else{
										Echo "<option value = \"" . htmlentities(Trim($rsRow["RightsLvl"])) . "\">" . $rsRow["RightsLvl"] . " - " . $sTemp . "</option>";
									}
								}
								?>
							</select>
							</td>
						</tr>
					</table>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<?php $sBGColor = $GLOBALS["PageBGColor"];?>
						<tr>
							<td bgcolor=<?=$sBGColor?> align=center>
								<input type="button" value=" Create New Category " onClick='SubmitForm("AddCategory")'>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		</form>
		<?php 
	}
	//********************************************************************************
	
	
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
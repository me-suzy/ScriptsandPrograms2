<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iGalleryUnq	= Trim(Request("iGalleryUnq"));
	$iTtlNumItems	= Trim(Request("iTtlNumItems"));
	$iDBLoc			= Trim(Request("iDBLoc"));
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
		Global $iLoginAccountUnq;
		Global $iImageUnq;
		
		$sError			= "";
		$sSuccess		= "";
		$sAction		= Trim(Request("sAction"));
		$iImageUnq		= Trim(Request("iImageUnq"));
		
		If ( $iImageUnq != "" )
		{
			If ( $sAction == "AddLink" )
			{
				If ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") )
				{
					$sURL			= Trim(Request("sURL"));
					$sDescription	= Trim(Request("sDescription"));
					$sOnSite		= Trim(Request("sOnSite"));
					If ( $sURL == "" ) {
						$sError = "Please enter a URL for the new link.<br>";
					}Else{
						If ( $sDescription == "" ) {
							$sError = "Please enter a description for the new link.<br>";
						}Else{
							DB_Insert ("INSERT INTO IGMiscLinks (ImageUnq,URL,OnSite,Description) VALUES (" . $iImageUnq . ", '" . SQLEncode($sURL) . "', '" . SQLEncode($sOnSite) . "', '" . SQLEncode($sDescription) . "')");
							$sSuccess = "New link added successfully.";
						}
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot add links to images within it.<br>";
				}
			}ElseIf ( $sAction == "UpdateLinks" ){
				If ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") )
				{
					ForEach ($_POST as $sTextField=>$sValue)
					{
						If ( strpos($sTextField, "sOldURL") !== false )
						{
							$iLinkUnq			= str_replace("sOldURL", "", $sTextField);
							$sOldURL			= $sValue;
							$sNewURL			= Request("sNewURL" . $iLinkUnq);
							$sOldDescription 	= Request("sOldDescription" . $iLinkUnq);
							$sNewDescription	= Request("sNewDescription" . $iLinkUnq);
							$sOldOnSite			= Request("sOldOnSite" . $iLinkUnq);
							$sOnSite			= Request("sOnSite" . $iLinkUnq);
							
							If ( $sOldURL != $sNewURL Or $sOldDescription != $sNewDescription Or $sOldOnSite != $sOnSite )
							{
								DB_Update ("UPDATE IGMiscLinks SET URL = '" . SQLEncode($sNewURL) . "', OnSite = '" . $sOnSite . "', Description = '" . SQLEncode($sNewDescription) . "' WHERE LinkUnq = " . $iLinkUnq);
								If ( $sSuccess == "" )
									$sSuccess = "Links successfully modified.";
							}
						}ElseIf ( strpos($sTextField, "sDelete") !== false )
						{
							$iLinkUnq = str_replace("sDelete", "", $sTextField);
							DB_Update ("DELETE FROM IGMiscLinks WHERE LinkUnq = " . $iLinkUnq);
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
		Global $iImageUnq;
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iLoginAccountUnq;
		Global $iGalleryUnq;
		Global $aVariables;
		Global $aValues;
		
		$sBGColor = $GLOBALS["BGColor2"];
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Manage Image Links</b></font>
					<br>
					<b>Manage links for image: </b> <?=ReturnImageName($iImageUnq);?>
					<br><br>
					<form name='AddLink' action='EditLinks.php' method='post'>
					Add a new link:
					<?php 
					$aVariables[0] = "sAction";
					$aVariables[1] = "iTtlNumItems";
					$aVariables[2] = "iDBLoc";
					$aVariables[3] = "iImageUnq";
					$aVariables[4] = "iGalleryUnq";
					$aValues[0] = "AddLink";
					$aValues[1] = $iTtlNumItems;
					$aValues[2] = $iDBLoc;
					$aValues[3] = $iImageUnq;
					$aValues[4] = $iGalleryUnq;
					Echo DOMAIN_Link("P");
					?>
					<table width=100%>
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
						
						$sQuery			= "SELECT * FROM IGMiscLinks (NOLOCK) WHERE ImageUnq = " . $iImageUnq;
						$rsRecordSet	= DB_Query($sQuery);
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor == $sColor1 ) {
								$sBGColor = $sColor2;
							}Else{
								$sBGColor = $sColor1;
							}

							$iLinkUnq		= $rsRow["LinkUnq"];
							$sURL			= htmlentities($rsRow["URL"]);
							$sDescription	= htmlentities($rsRow["Description"]);
							$sOnSite		= Trim($rsRow["OnSite"]);
							?>
							<tr>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<input type='hidden' name='sOldURL<?=$iLinkUnq?>' value="<?=htmlentities($sURL)?>">
									<input type='text' name='sNewURL<?=$iLinkUnq?>' value="<?=htmlentities($sURL)?>" size=30 maxlength=250>
								</td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<input type='hidden' name='sOldDescription<?=$iLinkUnq?>' value="<?=htmlentities($sDescription)?>">
									<input type='text' name='sNewDescription<?=$iLinkUnq?>' value="<?=htmlentities($sDescription)?>" size=30 maxlength=250>
								</td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<table>
										<tr>
											<td><input type='hidden' name='sOldOnSite<?=$iLinkUnq?>' value='<?=$sOnSite?>'><b>Onsite</td>
											<td><input type='radio' name='sOnSite<?=$iLinkUnq?>' value='Y' <?php If ( $sOnSite == "Y" )  Echo "checked";?>></td>
											<td><b>Offsite</td>
											<td><input type='radio' name='sOnSite<?=$iLinkUnq?>' value='N' <?php If (( $sOnSite == "N" ) || ( $sOnSite == "" ))  Echo "checked";?>></td>
										</tr>
									</table>
								</td>
								<td align=center bgcolor=<?=$sBGColor?> valign=top><input type='checkbox' name="sDelete<?=$iLinkUnq?>" value="<?=$iLinkUnq?>"></td>
							</tr>
							<?php 
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
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $aVariables;
		Global $aValues;
		
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
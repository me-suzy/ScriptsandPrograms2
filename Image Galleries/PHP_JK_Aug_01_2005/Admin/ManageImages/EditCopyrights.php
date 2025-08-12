<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iGalleryUnq		= Trim(Request("iGalleryUnq"));
	$iDBLoc				= Trim(Request("iDBLoc"));
	$iImageUnq			= Trim(Request("iImageUnq"));
	
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
		Global $iGalleryUnq;
		Global $iDBLoc;
		Global $iImageUnq;
		Global $iLoginAccountUnq;
		
		$sAction	= Trim(Request("sAction"));
		$sError		= "";
		$sSuccess	= "";		
		
		If ( $iImageUnq != "" )
		{
			If ( $sAction == "AddCopyright" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
					ForEach ($_POST["iCopyUnq"] as $sCheckbox=>$sValue)
						DB_Insert ("INSERT INTO IGImageCRs VALUES (" . $iImageUnq . ", " . $sValue . ", '')");
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot add copyrights to images within it.<br>";
				}
			}ElseIf ( $sAction == "RemoveCopyright" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, $iImageUnq) || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) ) {
					ForEach ($_POST["iCopyUnq"] as $sCheckbox=>$sValue)
						DB_Update ("DELETE FROM IGImageCRs WHERE ImageUnq = " . $iImageUnq . " AND CopyUnq = " . $sValue);
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
		Global $iImageUnq;
		Global $aVariables;
		Global $aValues;
		Global $iDBLoc;
		
		$sBGColor = $GLOBALS["BGColor2"];
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Manage Image Copyright Information</b></font>
					<br>
					<b>Manage copyrights for image: </b> <?=ReturnImageName($iImageUnq)?>
					<br><br>
					<form name='AddCopyright' action='EditCopyrights.php' method='post'>
					Add copyrights:<br>
					<?php 
					$aVariables[0] = "sAction";
					$aVariables[1] = "iImageUnq";
					$aVariables[2] = "iDBLoc";
					$aVariables[3] = "iGalleryUnq";
					$aValues[0] = "AddCopyright";
					$aValues[1] = $iImageUnq;
					$aValues[2] = $iDBLoc;
					$aValues[3] = $iGalleryUnq;
					Echo DOMAIN_Link("P");
					?>
					<select name='iCopyUnq[]' multiple size=6>
						<?php 
						If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
							$sQuery	= "SELECT CopyUnq, Copyright FROM IGCopyrights (NOLOCK) WHERE CopyUnq NOT IN (SELECT CopyUnq FROM IGImageCRs WHERE ImageUnq = " . $iImageUnq . ") ORDER BY Copyright";
						}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
							$sTemp			= "";
							$sQuery			= "SELECT CopyUnq FROM IGImageCRs WHERE ImageUnq = " . $iImageUnq;
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
								$sTemp .= $rsRow["CopyUnq"] . ",";
							$sTemp .= "0";
							$sQuery	= "SELECT CopyUnq, Copyright FROM IGCopyrights WHERE CopyUnq NOT IN (" . $sTemp . ") ORDER BY Copyright";
						}
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
						If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
							$sQuery = "SELECT CopyUnq, Copyright FROM IGCopyrights (NOLOCK) WHERE CopyUnq IN (SELECT CopyUnq FROM IGImageCRs WHERE ImageUnq = " . $iImageUnq . ") ORDER BY Copyright";
						}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
							$sTemp			= "";
							$sQuery			= "SELECT CopyUnq FROM IGImageCRs WHERE ImageUnq = " . $iImageUnq;
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
								$sTemp .= $rsRow["CopyUnq"] . ",";
							$sTemp .= "0";
							$sQuery = "SELECT CopyUnq, Copyright FROM IGCopyrights (NOLOCK) WHERE CopyUnq IN (" . $sTemp . ") ORDER BY Copyright";
						}
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
?>
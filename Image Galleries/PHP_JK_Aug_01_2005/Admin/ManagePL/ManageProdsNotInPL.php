<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iTtlNumItems	= Trim(Request("iTtlNumItems"));
	$iDBLoc			= Trim(Request("iDBLoc"));
	
	WriteScripts();
	
	If (ACCNT_ReturnRights("PHPJK_IG_ADD_PROD_2IMAGES")) {
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
		Global $iProdID;
		Global $sName;
		Global $sPrice;
		Global $sURL;
		Global $sImageURL;
		Global $sOldName;
		Global $sOldPrice;
		Global $sOldURL;
		Global $sOldImageURL;
		
		$sAction		= Trim(Request("sAction"));
		$iGalleryUnq	= Trim(Request("iGalleryUnq"));
		$sError			= "";
		$sSuccess		= "";

		If ( $sAction == "AddNotInPL" ) {
			$iProdID	= Trim(Request("iProdID"));
			$sName		= Trim(Request("sName"));
			$sPrice		= Trim(Request("sPrice"));
			$sURL		= Trim(Request("sURL"));
			$sImageURL	= Trim(Request("sImageURL"));
			If ( $iProdID == "" )
				$sError = "Please enter a Product ID for the new product.<br>";
			If ( $sName == "" )
				$sError = $sError . "Please enter a name for the new product.<br>";
			If ( $sPrice == "" )
				$sError = $sError . "Please enter a price for the new product.<br>";
			If ( $sURL == "" )
				$sError = $sError . "Please enter a URL for the new product.<br>";
			
			If ( $sError == "" ) {
				DB_Insert ("INSERT INTO IGPLProds (ProdID,DomainUnq,Name,Price,URL,ImageURL) VALUES ('" . SQLEncode($iProdID) . "', 1, '" . SQLEncode($sName) . "', '" . SQLEncode($sPrice) . "', '" . SQLEncode($sURL) . "', '" . SQLEncode($sImageURL) . "')");
				$sSuccess = "New product added successfully.";
			}
		}ElseIf ( $sAction == "UpdateNotINPL" ) {
			ForEach ($_POST as $sTextField=>$sValue)
			{
				If ( strpos($sTextField, "sOldName") !== false )
				{
					$iProdUnq		= str_replace("sOldName", "", $sTextField);
					$sOldName		= $sValue;
					$sNewName		= Request("sNewName" . $iProdUnq);
					$sOldPrice		= Request("sOldPrice" . $iProdUnq);
					$sNewPrice		= Request("sNewPrice" . $iProdUnq);
					$sOldURL		= Request("sOldURL" . $iProdUnq);
					$sNewURL		= Request("sNewURL" . $iProdUnq);
					$sOldImageURL	= Request("sOldImageURL" . $iProdUnq);
					$sNewImageURL	= Request("sNewImageURL" . $iProdUnq);
					
					If ( ( $sOldURL != $sNewURL ) || ( $sOldName != $sNewName ) || ( $sOldPrice != $sNewPrice ) || ( $sOldImageURL != $sNewImageURL ) )
					{
						DB_Update ("UPDATE IGPLProds SET Name = '" . SQLEncode($sNewName) . "', Price = '" . SQLEncode($sNewPrice) . "', URL = '" . SQLEncode($sNewURL) . "', ImageURL = '" . SQLEncode($sNewImageURL) . "' WHERE ProdUnq = " . $iProdUnq);
						If ( $sSuccess == "" )
							$sSuccess = "Product(s) updated successfully.";
					}
				}ElseIf ( strpos($sTextField, "sDelete") !== false )
				{
					$iProdUnq = str_replace("sDelete", "", $sTextField);
					DB_Update ("DELETE FROM IGPLProds WHERE ProdUnq = " . $iProdUnq);
					DB_Update ("DELETE FROM IGImageProds WHERE ProdUnq = " . $iProdUnq);
					If ( $sSuccess == "" )
						$sSuccess = "Product(s) deleted successfully.";
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
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $aVariables;
		Global $aValues;
		
		Global $iGalleryUnq;
		Global $iProdID;
		Global $sName;
		Global $sPrice;
		Global $sURL;
		Global $sImageURL;
		Global $sOldName;
		Global $sOldPrice;
		Global $sOldURL;
		Global $sOldImageURL;
		
		$sBGColor = $GLOBALS["BGColor2"];
		
		$aVariables[0] = "sAction";
		$aVariables[1] = "iTtlNumItems";
		$aVariables[2] = "iDBLoc";
		$aVariables[3] = "iGalleryUnq";
		$aValues[0] = "AddProductFromPL";
		$aValues[1] = $iTtlNumItems;
		$aValues[2] = $iDBLoc;
		$aValues[3] = $iGalleryUnq;
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Manage Products Not in a Product List (PL)</b></font>
					<br>
					<form name='AddProductNotInPL' action='ManageProdsNotInPL.php' method='post'>
					Add products not in a Product List (PL).
					<?php 
					$aValues[0] = "AddNotInPL";
					Echo DOMAIN_Link("P");
					?>
					<table width=100% cellpadding=0 cellspacing=0 border=0>
						<tr>
							<td><b>Product ID</td>
							<td><b>Name</td>
							<td><b>Price</td>
							<td><b>URL</td>
							<td><b>Image URL</td>
						</tr>
						<tr>
							<td><input type='text' name='iProdID' value='' maxlength=32></td>
							<td><input type='text' name='sName' value='' maxlength=255></td>
							<td><input type='text' name='sPrice' value='' size=5 maxlength=32></td>
							<td><input type='text' name='sURL' value='' maxlength=255></td>
							<td><input type='text' name='sImageURL' value='' maxlength=255></td>
						</tr>
					</table>
					<input type='submit' value='Add Product'>
					</form>
					
					<br>
					<form name='ManageProductsNotInPL' action='ManageProdsNotInPL.php' method='post'>
					<?php 
					$aValues[0] = "UpdateNotINPL";
					Echo DOMAIN_Link("P");
					?>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Product ID</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Name</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Price</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>URL</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Image URL</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Delete</b></td>
						</tr>
						<?php 
						$sColor1 = $GLOBALS["BGColor1"];
						$sColor2 = $GLOBALS["PageBGColor"];
						$sColor3 = $GLOBALS["TextColor1"];
						$sColor4 = $GLOBALS["PageText"];
						
						$sQuery			= "SELECT * FROM IGPLProds P (NOLOCK) ORDER BY ProdID";
						$rsRecordSet	= DB_Query($sQuery);
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor == $sColor1 ) {
								$sBGColor = $sColor2;
								$sTextColor = $sColor4;
							}Else{
								$sBGColor = $sColor1;
								$sTextColor = $sColor3;
							}

							$iProdID	= $rsRow["ProdID"];
							$iProdUnq	= $rsRow["ProdUnq"];
							$sName		= htmlentities($rsRow["Name"]);
							$sPrice		= htmlentities($rsRow["Price"]);
							$sURL		= htmlentities($rsRow["URL"]);
							$sImageURL	= htmlentities($rsRow["ImageURL"]);
							?>
							<tr>
								<td bgcolor=<?=$sBGColor?> valign=top><font color='<?=$sTextColor?>'><?=$iProdID?></td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<input type='hidden' name='sOldName<?=$iProdUnq?>' value="<?=$sName?>">
									<input type='text' name='sNewName<?=$iProdUnq?>' value="<?=$sName?>" size=20 maxlength=250>
								</td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<input type='hidden' name='sOldPrice<?=$iProdUnq?>' value="<?=$sPrice?>">
									<input type='text' name='sNewPrice<?=$iProdUnq?>' value="<?=$sPrice?>" size=5 maxlength=32>
								</td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<input type='hidden' name='sOldURL<?=$iProdUnq?>' value="<?=$sURL?>">
									<input type='text' name='sNewURL<?=$iProdUnq?>' value="<?=$sURL?>" size=20 maxlength=250>
								</td>
								<td bgcolor=<?=$sBGColor?> valign=top>
									<input type='hidden' name='sOldImageURL<?=$iProdUnq?>' value="<?=$sImageURL?>">
									<input type='text' name='sNewImageURL<?=$iProdUnq?>' value="<?=$sImageURL?>" size=20 maxlength=250>
								</td>
								<td align=center bgcolor=<?=$sBGColor?> valign=top><input type='checkbox' name="sDelete<?=$iProdUnq?>" value="<?=$iProdID?>"></td>
							</tr>
							<?php 
						}						
						?>
					</table>
					<center>
					<input type='submit' value='Update Products'>
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
	//*	This writes the JavaScript out even if the Main() Function isn't called in case the	*
	//*		admin has creation rights, but not edit rights.								*
	//*																					*
	//************************************************************************************
	Function WriteScripts()
	{
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $aVariables;
		Global $aValues;
		
		?>
		<SCRIPT LANGUAGE=javascript>
		<!--
			
			function ReturnToMain(){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
			}
			
			function SubmitForm(sAction){
				document.ManageProducts.sAction.value = sAction;
				document.ManageProducts.submit();
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
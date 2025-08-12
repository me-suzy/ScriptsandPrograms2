<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iGalleryUnq		= "";
	$aProducts			= "";
	$iProductsUBound	= "";
	$iPLUnq				= "";
	$iCategoryUnq	= Trim(Request("iCategoryUnq"));
	
	WriteScripts();
	
	If ( ACCNT_ReturnRights("PHPJK_IG_ADD_PROD_2GALLERIES") ) {
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
		Global $aProducts;
		Global $iProductsUBound;
		Global $iPLUnq;
		Global $iLoginAccountUnq;
		
		$sAction			= Trim(Request("sAction"));
		$iGalleryUnq		= Trim(Request("iGalleryUnq"));
		$iPLUnq				= Trim(Request("iPLUnq"));
		$sError				= "";
		$sSuccess			= "";
		
		If ( $iGalleryUnq != "" ) {
			If ( $sAction == "AddProductFromPL" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{
					// add it to the list from the PL
					ForEach ($_POST["iProdID"] as $sCheckbox=>$sValue)
						DB_Insert ("INSERT INTO IGImageProds VALUES (-1, 0, " . $iPLUnq . ", " . $iGalleryUnq . ", -1, '" . SQLEncode($sValue) . "')");
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot add products to images within it.<br>";
				}
			}ElseIf ( $sAction == "RemoveProductFromPL" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{
					// remove it from the list from the PL
					ForEach ($_POST["iProdID"] as $sCheckbox=>$sValue)
						DB_Update ("DELETE FROM IGImageProds WHERE GalleryUnq = " . $iGalleryUnq . " AND ProdID = '" . SQLEncode($sValue) . "' AND PLUnq = " . $iPLUnq);
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot remove products from images within it.<br>";
				}
			}ElseIf ( $sAction == "AddNotInPL" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{
					If ( isset($_POST["iProdUnq"]) )
					{
						ForEach ($_POST["iProdUnq"] as $sCheckbox=>$sValue)
							DB_Insert ("INSERT INTO IGImageProds VALUES (-1, " . $sValue . ", '', " . $iGalleryUnq . ", -1, '')");
						$sSuccess = "Product added to gallery successfully.";
					}Else{
						$sError = "Please choose a product to add to this gallery.";
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot add products to images within it.<br>";
				}
			}ElseIf ( $sAction == "UpdateNotINPL" ) {
				If ( ( G_ADMINISTRATION_IsGalleryOwner($iLoginAccountUnq, $iGalleryUnq, "") || ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) )
				{
					ForEach ($_POST as $sTextField=>$sValue)
					{
						If ( strpos($sTextField, "sDelete") !== false )
						{
							$iProdUnq = str_replace("sDelete", "", $sTextField);
							DB_Update ("DELETE FROM IGImageProds WHERE GalleryUnq = " . $iGalleryUnq . " AND ProdUnq = " . $iProdUnq . " AND PLUnq = ''");
							If ( $sSuccess == "" )
								$sSuccess = "Products updated successfully.";
						}
					}
				}Else{
					$sError = "Sorry but you are not the owner of this gallery and cannot update products within it.<br>";
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
		Global $iDBLoc;
		Global $aVariables;
		Global $aValues;
		Global $iTtlNumItems;
		Global $iGalleryUnq;
		
		Global $sPurchURL;
		Global $sSQLServer;
		Global $sSQLLogin;
		Global $sSQLPassword;
		Global $sDSNName;
		Global $sDSNLogin;
		Global $sDSNPassword;
		Global $sPLQueryText;
		Global $sProdQueryText;
		Global $sDBName;
		Global $QueryConnection;
		Global $iPLUnq;
		
		$sBGColor = $GLOBALS["BGColor2"];
		
		// all the forms use this information
		$aVariables[0] = "sAction";
		$aVariables[1] = "iGalleryUnq";
		$aVariables[2] = "iTtlNumItems";
		$aVariables[3] = "iDBLoc";
		$aValues[0] = "AddProductFromPL";
		$aValues[1] = $iGalleryUnq;
		$aValues[2] = $iTtlNumItems;
		$aValues[3] = $iDBLoc;
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageProducts.sAction.value = sAction;
				document.ManageProducts.submit();
			}
			
		</script>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Manage Products Associated with Galleries</b></font>
					<br>
					<b>Manage products for gallery: </b> <?=ReturnGalleryName($iGalleryUnq);?>
					<br>
					Remember that no more than <b><?=DOMAIN_Conf("IMAGEGALLERY_MAX_DISPLAYABLE_PRODUCTS")?></b> products 
					<?php If ( ACCNT_ReturnRights("PHPJK_MCONF_UPDATE") ) {?>
					(administrators may change this number by changing the IMAGEGALLERY_MAX_DISPLAYABLE_PRODUCTS 
					Configuration Variable in the Image Gallery System) 
					<?php }?>
					will be displayed with any image at one time. If more products are assigned to an image, this number of 
					products will be chosen at random and displayed with the image.
					<br><br>
					<form name='ManageProducts' method='post'>
					<?=DOMAIN_Link("P");?>
					<b>Product Lists:</b>
					<select name='iPLUnq' onChange='SubmitForm("UpdatePLUnq");'>
						<option value='-1'>No Product List</option>
						<?php 
						$sQuery			= "SELECT PLUnq, Name FROM IGPLs (NOLOCK) ORDER BY Name";
						$rsRecordSet	= DB_Query($sQuery);
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $iPLUnq == Trim($rsRow["PLUnq"]) ) {
								Echo "<option value='" . $rsRow["PLUnq"] . "' selected>" . htmlentities($rsRow["Name"]) . "</option>";
							}Else{
								Echo "<option value='" . $rsRow["PLUnq"] . "'>" . htmlentities($rsRow["Name"]) . "</option>";
							}
						}
						?>
					</select>
					</form>
					
					<?php 
					// don't declare these before the iPLUnq form is shown or there will be two iPLUnq variables in the form
					$aVariables[6] = "iPLUnq";
					$aValues[6] = $iPLUnq;
					
					If ( ( $iPLUnq != "-1" ) && ( $iPLUnq != "" ) )
					{
						// get the PL information
						$sQuery		= "SELECT * FROM IGPLs (NOLOCK) WHERE PLUnq = " . $iPLUnq;
						$rsRecordSet = DB_Query($sQuery);
						If ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							$sSQLServer		= Trim($rsRow["SQLServer"]);
							$sSQLLogin		= Trim($rsRow["SQLLogin"]);
							$sSQLPassword	= Trim($rsRow["SQLPassword"]);
							$sDSNName		= Trim($rsRow["DSNName"]);
							$sDSNLogin		= Trim($rsRow["DSNLogin"]);
							$sDSNPassword	= Trim($rsRow["DSNPassword"]);
							$sPLQueryText	= Trim($rsRow["PLQueryText"]);
							$sProdQueryText	= Trim($rsRow["ProdQueryText"]);
							$sDBName		= Trim($rsRow["DBName"]);
						}

						If ( $sPLQueryText != "" )
						{
							// put products (ProdID's) that have been added to this image in an array
							$iCount = 0;
							$sQuery			= "SELECT ProdID FROM IGImageProds (NOLOCK) WHERE GalleryUnq = " . $iGalleryUnq . " AND PLUnq = " . $iPLUnq;
							$rsRecordSet	= DB_Query($sQuery);
							While ( $rsRow = DB_Fetch($rsRecordSet) )
							{
								$aProdID[$iCount] = Trim($rsRow["ProdID"]);
								$iCount++;
							}
							$iCount--;
							?>
							<form name='AddProductFromPL' action='EditProducts.php' method='post'>
							Add products From the current Product List to this gallery:<br>
							<?php 
							If ( ( $sDSNName != "" ) || ( $sDSNLogin != "" ) || ( $sDSNPassword != "" ) ) {
								// use the DSN
								OpenDB_DSN($sDSNName, $sDSNLogin, $sDSNPassword);
								$bUSEODBC = True;
							}Else{
								// use the connection string
								OpenDB_CS($sSQLLogin, $sSQLPassword, $sSQLServer, $sDBName);
								$bUSEODBC = False;
							}

							Echo DOMAIN_Link("P");
							?>
							<select name='iProdID[]' multiple size=6>
								<?php 
								If ( $bUSEODBC )
								{
									$rsRecordSet = DB_ODBC_Query($QueryConnection, $sPLQueryText);
									While ( $rsRow = DB_ODBC_Fetch($rsRecordSet) )
									{
										$sTempProdID	= $rsRow[2];
										$sTempName		= $rsRow[0];
										$bBeenAdded		= FALSE;
										// now see if this ProdID is in the aProdID array (meaning it's already been added to this image)
										For ($x = 0; $x <= $iCount; $x++)
										{										
											If ( $aProdID[$x] == Trim($sTempProdID) )
											{
												$bBeenAdded = TRUE;
												$x = $iCount + 1;
											}
										}
										If ( ! $bBeenAdded )
											Echo "<option value='" . $sTempProdID . "'>" . htmlentities($sTempName) . "</option>";
									}
								}Else{
									$rsRecordSet = DB_Query($sPLQueryText, $QueryConnection);
									While ( $rsRow = DB_Fetch($rsRecordSet) )
									{
										$sTempProdID	= $rsRow[2];
										$sTempName		= $rsRow[0];
										$bBeenAdded		= FALSE;
										// now see if this ProdID is in the aProdID array (meaning it's already been added to this image)
										For ($x = 0; $x <= $iCount; $x++)
										{										
											If ( $aProdID[$x] == Trim($sTempProdID) )
											{
												$bBeenAdded = TRUE;
												$x = $iCount + 1;
											}
										}
										If ( ! $bBeenAdded )
											Echo "<option value='" . $sTempProdID . "'>" . htmlentities($sTempName) . "</option>";
									}
								}
								?>
							</select>
							<br>
							<input type='submit' value='Add Products'>
							</form>
							
							<form name='RemoveProductFromPL' action='EditProducts.php' method='post'>
							Remove products from this gallery:<br>
							<?php 
							$aValues[0] = "RemoveProductFromPL";
							Echo DOMAIN_Link("P");
							?>
							<SELECT NAME='iProdID[]' MULTIPLE size=6>
								<?php 
								If ( $bUSEODBC )
								{
									$rsRecordSet = DB_ODBC_Query($QueryConnection, $sPLQueryText);
									While ( $rsRow = DB_ODBC_Fetch($rsRecordSet) )
									{
										$sTempProdID	= $rsRow[2];
										$sTempName		= $rsRow[0];
										$bBeenAdded		= FALSE;
										// now see if this ProdID is in the aProdID array (meaning it's already been added to this image)
										For ($x = 0; $x <= $iCount; $x++)
										{
 											If ( $aProdID[$x] == Trim($sTempProdID) )
											{
												$bBeenAdded = TRUE;
												$x = $iCount + 1;
											}
										}
										If ( $bBeenAdded )
											Echo "<option value='" . $sTempProdID . "'>" . htmlentities($sTempName) . "</option>";
									}
								}Else{
									$rsRecordSet = DB_Query($sPLQueryText, $QueryConnection);
									While ( $rsRow = DB_Fetch($rsRecordSet) )
									{
										$sTempProdID	= $rsRow[2];
										$sTempName		= $rsRow[0];
										$bBeenAdded		= FALSE;
										// now see if this ProdID is in the aProdID array (meaning it's already been added to this image)
										For ($x = 0; $x <= $iCount; $x++)
										{
											If ( $aProdID[$x] == Trim($sTempProdID) )
											{
												$bBeenAdded = TRUE;
												$x = $iCount + 1;
											}
										}
										If ( $bBeenAdded )
											Echo "<option value='" . $sTempProdID . "'>" . htmlentities($sTempName) . "</option>";
									}
								}
								?>
							</select>
							<br>
							<input type='submit' value='Remove Products'>
							</form>
							<?php 
						}Else{
							Echo "Product List has no Product List Query. Unable to list products for this PL. Please add the query before trying to add products from it to galleries.";
						}
					}
					?>
					
					<br>
					<hr>
					<form name='AddProductNotInPL' action='EditProducts.php' method='post'>
					Add products to this gallery that are not in a Product List:<br>
					<?php 
					$aValues[0] = "AddNotInPL";
					Echo DOMAIN_Link("P");
					
					Echo "<SELECT NAME='iProdUnq[]' MULTIPLE size=6>";
					If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
						$sQuery	= "SELECT * FROM IGPLProds P (NOLOCK) WHERE ProdUnq NOT IN (SELECT IP.ProdUnq FROM IGImageProds IP (NOLOCK), IGPLProds P (NOLOCK) WHERE IP.GalleryUnq = " . $iGalleryUnq . " AND (IP.PLUnq = '' OR IP.PLUnq = '0') AND IP.ProdUnq = P.ProdUnq) ORDER BY ProdID";
					}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
						$sTemp			= "";
						$sQuery			= "SELECT IP.ProdUnq FROM IGImageProds IP (NOLOCK), IGPLProds P (NOLOCK) WHERE IP.GalleryUnq = " . $iGalleryUnq . " AND (IP.PLUnq = '' OR IP.PLUnq = '0') AND IP.ProdUnq = P.ProdUnq";
						$rsRecordSet	= DB_Query($sQuery);
						While ( $rsRow = DB_Fetch($rsRecordSet) )
							$sTemp .= $rsRow["ProdUnq"] . ",";
						$sTemp .= "0";
						$sQuery	= "SELECT * FROM IGPLProds P (NOLOCK) WHERE ProdUnq NOT IN (" . $sTemp . ") ORDER BY ProdID";
					}
					$rsRecordSet	= DB_Query($sQuery);
					While ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						$iProdID	= $rsRow["ProdID"];
						$iProdUnq	= $rsRow["ProdUnq"];
						$sName		= htmlentities($rsRow["Name"]);
						$sPrice		= htmlentities($rsRow["Price"]);
						Echo "<option value='" . $iProdUnq . "'>" . $iProdID . "--" . $sName . "--" . $sPrice . "</option>\n";
					}					
					?>
					</select>
					<br>
					<input type='submit' value='Add Product to Gallery'>
					</form>
					
					<br>
					<form name='ManageProductsNotInPL' action='EditProducts.php' method='post'>
					<?php 
					$aValues[0] = "UpdateNotINPL";
					Echo DOMAIN_Link("P");
					?>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Product ID</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Name</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Price</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Remove</b></td>
						</tr>
						<?php 
						$sColor1 = $GLOBALS["BGColor1"];
						$sColor2 = $GLOBALS["PageBGColor"];
						$sColor3 = $GLOBALS["TextColor1"];
						$sColor4 = $GLOBALS["PageText"];
						
						$sQuery			= "SELECT * FROM IGImageProds IP (NOLOCK), IGPLProds P (NOLOCK) WHERE IP.GalleryUnq = " . $iGalleryUnq . " AND (IP.PLUnq = '' OR IP.PLUnq = '0') AND IP.ProdUnq = P.ProdUnq";
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
								<td bgcolor=<?=$sBGColor?> valign=top><font color='<?=$sTextColor?>'><?=$sName?></td>
								<td bgcolor=<?=$sBGColor?> valign=top><font color='<?=$sTextColor?>'><?=$sPrice?></td>
								<td align=center bgcolor=<?=$sBGColor?> valign=top><input type='checkbox' name="sDelete<?=$iProdUnq?>" value="<?=$iProdUnq?>"></td>
							</tr>
							<tr>
								<td bgcolor=<?=$sBGColor?> valign=top colspan=2><font color='<?=$sTextColor?>'><b>URL:</b> <?=$sURL?></td>
								<td bgcolor=<?=$sBGColor?> valign=top colspan=2><font color='<?=$sTextColor?>'><b>Image URL:</b> <?=$sImageURL?></td>
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
	//*	Open the database w/ a connection string.										*
	//*																					*
	//************************************************************************************
	Function OpenDB_CS($sSQLLogin, $sSQLPassword, $sSQLServer, $sDBName)
	{
		Global $QueryConnection;
		
		$QueryConnection = DB_DBConnect($sSQLServer, $sSQLLogin, $sSQLPassword);
		If ( $GLOBALS["sUseDB"] == "MSSQL" ) {
			mssql_select_db($sDBName, $QueryConnection)
				or
				Die ("Could not connect to the database: ".mssql_get_last_message());
		}ElseIf ( $GLOBALS["sUseDB"] == "MYSQL" ) {
			mysql_select_db($sDBName, $QueryConnection)
				or
				Die ("Could not connect to the database: ".mysql_error());
		}
	} 
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Open the database w/ a DSN.														*
	//*																					*
	//************************************************************************************
	Function OpenDB_DSN($sDSNName, $sDSNLogin, $sDSNPassword)
	{
		Global $sUseDB;
		Global $QueryConnection;
		
		If ( $sUseDB == "MSSQL" ){
			$QueryConnection = odbc_connect( $sDSNName, $sDSNLogin, $sDSNPassword )
				or
				Die ("Could not connect to DSN: ".odbc_errormsg());
		}
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
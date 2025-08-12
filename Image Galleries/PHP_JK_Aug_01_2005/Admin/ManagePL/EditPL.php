<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iTtlNumItems	= Trim(Request("iTtlNumItems"));
	$iDBLoc			= Trim(Request("iDBLoc"));
	
	WriteScripts();
	
	If (ACCNT_ReturnRights("PHPJK_IG_EDIT_PL")) {
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
		Global $sName;
		Global $iPLUnq;
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
		Global $sImageURL;
		
		Global $sOldName;
		Global $sOldPurchURL;
		Global $sOldSQLServer;
		Global $sOldSQLLogin;
		Global $sOldSQLPassword;
		Global $sOldDSNName;
		Global $sOldDSNLogin;
		Global $sOldDSNPassword;
		Global $sOldPLQueryText;
		Global $sOldProdQueryText;
		Global $sOldDBName;
		
		$sName		= Trim(Request("sName"));
		$sAction	= Trim(Request("sAction"));
		$iPLUnq		= Trim(Request("iPLUnq"));
		$sError		= "";
		
		If ( $iPLUnq == "" ) {
			DOMAIN_Message("Missing PLUnq. Unable to update or edit the Purchase List.", "ERROR");
		}Else{
			If ( $sAction == "UpdatePL" ) {
				If ( ! ACCNT_ReturnRights("PHPJK_IG_EDIT_PL") ) {
					$sError = "Please log in with Image Gallery management rights.";
				}Else{
					$sName			= Trim(Request("sName"));
					$sPurchURL		= Trim(Request("sPurchURL"));
					$sSQLServer		= Trim(Request("sSQLServer"));
					$sSQLLogin		= Trim(Request("sSQLLogin"));
					$sSQLPassword	= Trim(Request("sSQLPassword"));
					$sDSNName		= Trim(Request("sDSNName"));
					$sDSNLogin		= Trim(Request("sDSNLogin"));
					$sDSNPassword	= Trim(Request("sDSNPassword"));
					$sPLQueryText	= Trim(Request("sPLQueryText"));
					$sProdQueryText	= Trim(Request("sProdQueryText"));
					$sDBName		= Trim(Request("sDBName"));
					$sImageURL		= Trim(Request("sImageURL"));
					
					If ( $sName == "" )
						$sError = "Please enter a name for the new Purchase List.<br>";
					If ( $sPLQueryText == "" )
						$sError = $sError . "Please enter a SQL query to return the complete list of products in the new Purchase List.<br>";
					If ( $sProdQueryText == "" ) {
						$sError = $sError . "Please enter a SQL query to return the Specific Product information in the new Purchase List.<br>";
					}Else{
						If ( strpos($sProdQueryText, "1:") < 1 )
							$sError = $sError . "Please make sure to include the 1: in the Specific Product Query in the position of the product id.<br>";
					}
					If ( $sDBName == "" )
							$sError = $sError . "Please enter the Database name in which the product information is held.<br>";
					If ( ( ( $sSQLServer != "" ) || ( $sSQLLogin != "" ) || ( $sSQLPassword != "" ) ) && ( ( $sDSNName != "" ) || ( $sDSNLogin != "" ) || ( $sDSNPassword != "" ) ) )
						$sError = $sError . "Please enter either Direct Connection information or DSN information, but not both.<br>";
					If ( ( ( $sSQLServer == "" ) && ( $sSQLLogin == "" ) && ( $sSQLPassword == "" ) && ( $sDSNName == "" ) && ( $sDSNLogin == "" ) && ( $sDSNPassword == "" ) ) )
						$sError = $sError . "Please enter either Direct Connection or DSN information.<br>";
					If ( ( $sSQLServer != "" ) || ( $sSQLLogin != "" ) || ( $sSQLPassword != "" ) ) {
						If ( $sSQLServer == "" )
							$sError = $sError . "Please enter the SQL Server Name.<br>";
						If ( $sSQLLogin == "" )
							$sError = $sError . "Please enter the SQL Server Login.<br>";
						If ( $sSQLPassword == "" )
							$sError = $sError . "Please enter the SQL Server Password.<br>";
					}
					If ( ( $sDSNName != "" ) || ( $sDSNLogin != "" ) || ( $sDSNPassword != "" ) ) {
						If ( $sDSNName == "" )
							$sError = $sError . "Please enter the DSN Name.<br>";
						If ( $sDSNLogin == "" )
							$sError = $sError . "Please enter the DSN Login.<br>";
						If ( $sDSNPassword == "" )
							$sError = $sError . "Please enter the DSN Password.<br>";
					}
					If ( $sPurchURL == "" ) {
						$sError = $sError . "Please enter the Store URL.<br>";
					}Else{
						If ( strpos($sPurchURL, "1:") < 1 )
							$sError = $sError . "Please make sure to include the 1: in the Store URL in the position of the product id.<br>";
					}
					If ( $sError == "" ) {
						DB_Update ("UPDATE IGPLs SET Name = '" . SQLEncode($sName) . "', PLQueryText = '" . SQLEncode($sPLQueryText) . "', ProdQueryText = '" . SQLEncode($sProdQueryText) . "', SQLServer = '" . SQLEncode($sSQLServer) . "', SQLLogin = '" . SQLEncode($sSQLLogin) . "', SQLPassword = '" . SQLEncode($sSQLPassword) . "', DSNName = '" . SQLEncode($sDSNName) . "', DSNLogin = '" . SQLEncode($sDSNLogin) . "', DSNPassword = '" . SQLEncode($sDSNPassword) . "', PurchURL = '" . SQLEncode($sPurchURL) . "', DBName = '" . SQLEncode($sDBName) . "', ImageURL = '" . SQLEncode($sImageURL) . "' WHERE PLUnq = " . $iPLUnq);
						DOMAIN_Message("Purchase List updated successfully.", "SUCCESS");
					}Else{
						// set the "old" values back to the original old values
						$sQuery = "SELECT * FROM IGPLs (NOLOCK) WHERE PLUnq = " . $iPLUnq;
						$rsRecordSet	= DB_Query($sQuery);						
						If ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							$sOldName			= Trim($rsRow["Name"]);
							$sOldPurchURL		= Trim($rsRow["PurchURL"]);
							$sOldSQLServer		= Trim($rsRow["SQLServer"]);
							$sOldSQLLogin		= Trim($rsRow["SQLLogin"]);
							$sOldSQLPassword	= Trim($rsRow["SQLPassword"]);
							$sOldDSNName		= Trim($rsRow["DSNName"]);
							$sOldDSNLogin		= Trim($rsRow["DSNLogin"]);
							$sOldDSNPassword	= Trim($rsRow["DSNPassword"]);
							$sOldPLQueryText	= Trim($rsRow["PLQueryText"]);
							$sOldProdQueryText	= Trim($rsRow["ProdQueryText"]);
							$sOldDBName			= Trim($rsRow["DBName"]);
							$sImageURL			= Trim($rsRow["ImageURL"]);
						}
					}
				}
			}Else{
				// get the PL info
				$sQuery = "SELECT * FROM IGPLs (NOLOCK) WHERE PLUnq = " . $iPLUnq;
				$rsRecordSet	= DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$sName				= Trim($rsRow["Name"]);
					$sPurchURL			= Trim($rsRow["PurchURL"]);
					$sSQLServer			= Trim($rsRow["SQLServer"]);
					$sSQLLogin			= Trim($rsRow["SQLLogin"]);
					$sSQLPassword		= Trim($rsRow["SQLPassword"]);
					$sDSNName			= Trim($rsRow["DSNName"]);
					$sDSNLogin			= Trim($rsRow["DSNLogin"]);
					$sDSNPassword		= Trim($rsRow["DSNPassword"]);
					$sPLQueryText		= Trim($rsRow["PLQueryText"]);
					$sProdQueryText		= Trim($rsRow["ProdQueryText"]);
					$sDBName			= Trim($rsRow["DBName"]);
					$sImageURL			= Trim($rsRow["ImageURL"]);
					$sOldName			= $sName;
					$sOldPurchURL		= $sPurchURL;
					$sOldSQLServer		= $sSQLServer;
					$sOldSQLLogin		= $sSQLLogin;
					$sOldSQLPassword	= $sSQLPassword;
					$sOldDSNName		= $sDSNName;
					$sOldDSNLogin		= $sDSNLogin;
					$sOldDSNPassword	= $sDSNPassword;
					$sOldPLQueryText	= $sPLQueryText;
					$sOldProdQueryText	= $sProdQueryText;
					$sOldDBName			= $sDBName;
				}
			}
			
			If ( $sError != "" )
				DOMAIN_Message($sError, "ERROR");
		
			WriteForm();
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
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $aVariables;
		Global $aValues;
		
		Global $sName;
		Global $iPLUnq;
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
		Global $sImageURL;
		
		Global $sOldName;
		Global $sOldPurchURL;
		Global $sOldSQLServer;
		Global $sOldSQLLogin;
		Global $sOldSQLPassword;
		Global $sOldDSNName;
		Global $sOldDSNLogin;
		Global $sOldDSNPassword;
		Global $sOldPLQueryText;
		Global $sOldProdQueryText;
		Global $sOldDBName;
		
		$sBGColor = $GLOBALS["BGColor1"];
		?>
		<form name='EditPL' action='EditPL.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "iDBLoc";
		$aVariables[2] = "iTtlNumItems";
		$aVariables[3] = "iPLUnq";
		$aValues[0] = "UpdatePL";
		$aValues[1] = $iDBLoc;
		$aValues[2] = $iTtlNumItems;
		$aValues[3] = $iPLUnq;
		Echo DOMAIN_Link("P");
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr><td><font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Edit Purchase List (PL)</b></font></td></tr>
			<tr>
				<td>
					<table cellpadding=1 cellspacing=0 border=0 width=671><tr><td bgcolor=<?=$GLOBALS["BGColor2"]?>>
					<table cellpadding=0 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>>
								<table cellpadding=10 cellspacing=0 border=0>
									<tr>
										<td><font color='<?=$GLOBALS["TextColor1"]?>'><b>Purchase List Name:</td>
										<td><input type='hidden' name='sOldName' value="<?=htmlentities($sOldName)?>"><input type='text' name='sName' value="<?=htmlentities($sName)?>" size=45 maxlength=250></td>
									</tr>
									<tr>
										<td colspan=2>
											<font color='<?=$GLOBALS["TextColor1"]?>'><b>Store URL:</b>
											<ul>
												<li>The Store URL must include the filename of the webpage, 
													and the QueryString information necessary to display the product detail page.
												<li>If the Store URL is offsite, it must begin with 
													"http://". Otherwise, it can be a relative link (for 
													example: /Store/index.php?ProdId=1:)
												<li>In place of an actual product id, use 1: 
													(the 1: will automatically be str_replaced with the actual product id)
												<li>For example: /Store/index.php?StoreID=123&OtherInfoHere=abcdefg&ProdId=1:
													<br>
													Or, for a store on a different website: 
													http://www.otherurl.com/Store/index.php?ProdId=1:
											</ul>
											<input type='hidden' name='sOldPurchURL' value="<?=htmlentities($sOldPurchURL)?>">
											<input type='text' name='sPurchURL' value="<?=htmlentities($sPurchURL)?>" size=100 maxlength=250>
										</td>
									</tr>
									<tr>
										<td colspan=2>
											<font color='<?=$GLOBALS["TextColor1"]?>'><b>Image URL:</b>
											<ul>
												<li>The Image URL is optional, and used to display thumbnail images with products.
												<li>If the Image URL is offsite, it must begin with "http://". 
													Otherwise, it can be a relative link (for example: /Store../../Images/1:)
												<li>In place of an actual image file name, use 1: (the 1: will automatically be 
													replaced with the actual file name from the Product List Query)
												<li>For example: /Store../../Images/1:
													<br>
													Or, for an image on a different website: http://www.otherurl.com/Store../../Images/1:
											</ul>
											<input type='text' name='sImageURL' value="<?=htmlentities($sImageURL)?>" size=100 maxlength=250>
										</td>
									</tr>
									<tr>
										<td colspan=2>
											<font color='<?=$GLOBALS["TextColor1"]?>'>
											<b>Please enter either "Direct Connection" or "DSN" information to connect to the product data source.</b>
										</td>
									</tr>
									<tr>
										<td align=center width=50% bgcolor=<?=$GLOBALS["PageBGColor"]?>><font color='<?=$GLOBALS["PageText"]?>'><b>USE DIRECT CONNECTION</td>
										<td align=center width=50% bgcolor=<?=$GLOBALS["PageBGColor"]?>><font color='<?=$GLOBALS["PageText"]?>'><b>USE DSN</td>
									</tr>
									<tr>
										<td width=50?>
											<table width=100%>
												<tr>
													<td><font color='<?=$GLOBALS["TextColor1"]?>'><b>SQL Server Name:</td>
													<td><input type='hidden' name='sOldSQLServer' value="<?=htmlentities($sOldSQLServer)?>"><input type='text' name='sSQLServer' value="<?=htmlentities($sSQLServer)?>" size=25 maxlength=250></td>
												</tr>
												<tr>
													<td><font color='<?=$GLOBALS["TextColor1"]?>'><b>SQL Server Login:</td>
													<td><input type='hidden' name='sOldSQLLogin' value="<?=htmlentities($sOldSQLLogin)?>"><input type='text' name='sSQLLogin' value="<?=htmlentities($sSQLLogin)?>" size=25 maxlength=250></td>
												</tr>
												<tr>
													<td><font color='<?=$GLOBALS["TextColor1"]?>'><b>SQL Server Password:</td>
													<td><input type='hidden' name='sOldSQLPassword' value="<?=htmlentities($sOldSQLPassword)?>"><input type='text' name='sSQLPassword' value="<?=htmlentities($sSQLPassword)?>" size=25 maxlength=250></td>
												</tr>
											</table>
										</td>
										<td width=50?>
											<table width=100%>
												<tr>
													<td><font color='<?=$GLOBALS["TextColor1"]?>'><b>DSN Name:</td>
													<td><input type='hidden' name='sOldDSNName' value="<?=htmlentities($sOldDSNName)?>"><input type='text' name='sDSNName' value="<?=htmlentities($sDSNName)?>" size=25 maxlength=250></td>
												</tr>
												<tr>
													<td><font color='<?=$GLOBALS["TextColor1"]?>'><b>DSN Login:</td>
													<td><input type='hidden' name='sOldDSNLogin' value="<?=htmlentities($sOldDSNLogin)?>"><input type='text' name='sDSNLogin' value="<?=htmlentities($sDSNLogin)?>" size=25 maxlength=250></td>
												</tr>
												<tr>
													<td><font color='<?=$GLOBALS["TextColor1"]?>'><b>DSN Password:</td>
													<td><input type='hidden' name='sOldDSNPassword' value="<?=htmlentities($sOldDSNPassword)?>"><input type='text' name='sDSNPassword' value="<?=htmlentities($sDSNPassword)?>" size=25 maxlength=250></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td><font color='<?=$GLOBALS["TextColor1"]?>'><b>Product Database Name:</td>
										<td><input type='hidden' name='sOldDBName' value="<?=htmlentities($sOldDBName)?>"><input type='text' name='sDBName' value="<?=htmlentities($sDBName)?>" size=45 maxlength=250></td>
									</tr>
									<tr>
										<td colspan=2>
											<font color='<?=$GLOBALS["TextColor1"]?>'><br>
											<b>Product List Query</b>
											<ul>
												<li>Please enter the SQL query to return the complete list of products you would like to 
													be associated with this Purchase List.
												<li>Make sure to return (in this order): Product Name, Product Price and Product ID.
												<li>An example query: SELECT PName, PPrice, ProdID FROM ProductList WHERE StoreUnq = 1
											</ul>
											<input type='hidden' name='sOldPLQueryText' value="<?=htmlentities($sOldPLQueryText)?>">
											<textarea name='sPLQueryText' cols=78 rows=4 wrap=off><?=htmlentities($sPLQueryText)?></textarea>
										</td>
									</tr>
									<tr>
										<td colspan=2>
											<font color='<?=$GLOBALS["TextColor1"]?>'><br>
											<b>Specific Product Query</b>
											<ul>
												<li>Please enter the SQL query to return each individual product. 
													In place of the product id, put a 1:
												<li>Make sure to return (in this order): Product Name, Product Price and Product Image.
												<li>An example query: SELECT PName, PPrice, PImage FROM ProductList WHERE PID = 1:
											</ul>
											<input type='hidden' name='sOldProdQueryText' value="<?=htmlentities($sOldProdQueryText)?>">
											<textarea name='sProdQueryText' cols=78 rows=4 wrap=off><?=htmlentities($sProdQueryText)?></textarea>
										</td>
									</tr>
									<tr>
										<td colspan=2>
											<a href='JavaScript:TestPL()' class='MediumNav1'>Test Queries</a>
										</td>
									</tr>
								</table>
							</td>
						</tR>
					</table>
					</td></tr></table>
				</td>
			</tr>
			<tr>
				<td align=center>
					<br>
					<input type='submit' value='Save Changes'>
				</td>
			</tr>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		</form>
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
			
			function TestPL(){
				window.open("TestPLResults.php?<?=DOMAIN_Link("G")?>&sDBName=" + document.EditPL.sDBName.value + "&sName=" + document.EditPL.sName.value + "&sSQLServer=" + document.EditPL.sSQLServer.value + "&sSQLLogin=" + document.EditPL.sSQLLogin.value + "&sSQLPassword=" + document.EditPL.sSQLPassword.value + "&sDSNName=" + document.EditPL.sDSNName.value + "&sDSNLogin=" + document.EditPL.sDSNLogin.value + "&sDSNPassword=" + document.EditPL.sDSNPassword.value + "&sPLQueryText=" + document.EditPL.sPLQueryText.value + "&sProdQueryText=" + document.EditPL.sProdQueryText.value ,"","");
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
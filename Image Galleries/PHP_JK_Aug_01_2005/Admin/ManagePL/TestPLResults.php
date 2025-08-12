<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iTtlNumItems	= Trim(Request("iTtlNumItems"));
	$iDBLoc			= Trim(Request("iDBLoc"));
	
	WriteScripts();
	
	HeaderHTML();
	Main();

	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $sName;
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
		Global $iPLUnq;
		
		$sError	= "";
		$iPLUnq = Trim(Request("iPLUnq"));
		
		If ( $iPLUnq != "" )
		{
			// get the PL info
			$sQuery			= "SELECT * FROM IGPLs (NOLOCK) WHERE PLUnq = " . $iPLUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$sName			= Trim($rsRow["Name"]);
				$sPurchURL		= Trim($rsRow["PurchURL"]);
				$sSQLServer		= Trim($rsRow["SQLServer"]);
				$sSQLLogin		= Trim($rsRow["SQLLogin"]);
				$sSQLPassword	= Trim($rsRow["SQLPassword"]);
				$sDSNName		= Trim($rsRow["DSNName"]);
				$sDSNLogin		= Trim($rsRow["DSNLogin"]);
				$sDSNPassword	= Trim($rsRow["DSNPassword"]);
				$sPLQueryText	= Trim($rsRow["PLQueryText"]);
				$sProdQueryText	= Trim($rsRow["ProdQueryText"]);
				$sDBName		= Trim($rsRow["DBName"]);
				$sImageURL		= Trim($rsRow["ImageURL"]);
			}
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
		}
			
			
		If ( $sPLQueryText == "" )
			$sError = $sError . "Please enter a SQL query to return the complete list of products in the Purchase List.<br>";
		If ( $sProdQueryText == "" ) {
			$sError = $sError . "Please enter a SQL query to return the Specific Product information in the Purchase List.<br>";
		}Else{
			If ( strpos($sProdQueryText, "1:") < 1 )
				$sError = $sError . "Please make sure to include the 1: in the Specific Product Query in the position of the product id.<br>";
		}
		If ( $sDBName == "" )
			$sError = $sError . "Please enter the Database name in which the product information is held.<br>";
		If ( ( $sSQLServer != "" ) || ( $sSQLLogin != "" ) || ( $sSQLPassword != "" ) )
		{
			If ( $sSQLServer == "" )
				$sError = $sError . "Please enter the SQL Server Name.<br>";
			If ( $sSQLLogin == "" )
				$sError = $sError . "Please enter the SQL Server Login.<br>";
			If ( $sSQLPassword == "" )
				$sError = $sError . "Please enter the SQL Server Password.<br>";
		}
		If ( ( $sDSNName != "" ) || ( $sDSNLogin != "" ) || ( $sDSNPassword != "" ) )
		{
			If ( $sDSNName == "" )
				$sError = $sError . "Please enter the DSN Name.<br>";
			If ( $sDSNLogin == "" )
				$sError = $sError . "Please enter the DSN Login.<br>";
			If ( $sDSNPassword == "" )
				$sError = $sError . "Please enter the DSN Password.<br>";
		}
		If ( $sError == "" ) {
			WriteForm();
		}Else{
			DOMAIN_Message($sError, "ERROR");
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
		Global $sName;
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
		Global $iPLUnq;
		Global $QueryConnection;
		
		$sBGColor = $GLOBALS["BGColor1"];
		
		If ( ( $sDSNName != "" ) || ( $sDSNLogin != "" ) || ( $sDSNPassword != "" ) ) {
			// use the DSN
			OpenDB_DSN($sDSNName, $sDSNLogin, $sDSNPassword);
			$bUSEODBC = True;
		}Else{
			// use the connection string
			OpenDB_CS($sSQLLogin, $sSQLPassword, $sSQLServer, $sDBName);
			$bUSEODBC = False;
		}
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Test Purchase List (PL)</b></font>
					<br>
					<b>Purchase List name:</b> <?=$sName?>
					<br><br>
					This only displays the first 20 entries in the Purchase List.
				</td>
			</tr>
			<tr>
				<td>
					<table cellpadding=1 cellspacing=0 border=0 width=671><tr><td bgcolor=<?=$GLOBALS["BGColor2"]?>>
					<table cellpadding=5 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?> align=center width=50%><b>Product List Results</td>
							<td bgcolor=<?=$sBGColor?> align=center width=50%><b>Individual Product</td>
						</tR>
						<tr>
							<td bgcolor=<?=$sBGColor?> width=50%><b>Query Used: </b><?=$sPLQueryText?></td>
							<td bgcolor=<?=$sBGColor?> width=50%><b>Query Used: </b><?=$sProdQueryText?></td>
						</tR>
					</table>
					</td></tr></table>
				</td>
			</tr>
			<tr>
				<td align=center width=50%>
					<table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td bgcolor=<?=$GLOBALS["BGColor2"]?>>
					<table cellpadding=5 cellspacing=1 border=0 width=100%>
						<?php 
						Echo "<tr><td bgcolor=" . $sBGColor . "><b>Name</td><td bgcolor=" . $sBGColor . "><b>Price</td><td bgcolor=" . $sBGColor . "><b>Prod ID</td><td bgcolor=" . $sBGColor . "><b>Name</td><td bgcolor=" . $sBGColor . "><b>Price</td><td bgcolor=" . $sBGColor . "><b>Image</td></tr>";
						If ( $bUSEODBC ) {
							DB_ODBC_Query($QueryConnection, "SET ROWCOUNT 20");
							$rsRecordSet = DB_ODBC_Query($QueryConnection, $sPLQueryText);
							DB_ODBC_Query($QueryConnection, "SET ROWCOUNT 0");
							While ( $rsRow = DB_ODBC_Fetch($rsRecordSet) )
							{
								Echo "<tr><td bgcolor=" . $sBGColor . ">" . $rsRow[0] . "</td><td bgcolor=" . $sBGColor . ">" . $rsRow[1] . "</td><td bgcolor=" . $sBGColor . ">" . $rsRow[2] . "</td>";
								$rsRecordSet2 = DB_ODBC_Query($QueryConnection, str_replace("1:", $rsRow[2], $sProdQueryText));
								If ( $rsRow2 = DB_ODBC_Fetch($rsRecordSet2) ) {
									Echo "<td bgcolor=" . $sBGColor . ">" . $rsRow2[0] . "</td><td bgcolor=" . $sBGColor . ">" . $rsRow2[1] . "</td><td bgcolor=" . $sBGColor . ">" . $rsRow2[2] . "</td></tr>";
								}Else{
									Echo "<td bgcolor=" . $sBGColor . " colspan=3>Product not found!</td></tr>";
								}
							}
						}Else{
							DB_Query("SET ROWCOUNT 20");
							$rsRecordSet = DB_Query($sPLQueryText);
							DB_Query("SET ROWCOUNT 0");
							While ( $rsRow = DB_Fetch($rsRecordSet) )
							{
								Echo "<tr><td bgcolor=" . $sBGColor . ">" . $rsRow[0] . "</td><td bgcolor=" . $sBGColor . ">" . $rsRow[1] . "</td><td bgcolor=" . $sBGColor . ">" . $rsRow[2] . "</td>";
								$sTemp = str_replace("1:", $rsRow[2], $sProdQueryText);
								$rsRecordSet2 = DB_Query($sTemp);
								If ( $rsRow2 = DB_Fetch($rsRecordSet2) ) {
									Echo "<td bgcolor=" . $sBGColor . ">" . $rsRow2[0] . "</td><td bgcolor=" . $sBGColor . ">" . $rsRow2[1] . "</td><td bgcolor=" . $sBGColor . ">" . $rsRow2[2] . "</td></tr>";
								}Else{
									Echo "<td bgcolor=" . $sBGColor . " colspan=3>Product not found!</td></tr>";
								}
							}
						}
						?>
					</table></td></tr></table>
				</td>
			</tR>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		</form>
		<?php 
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
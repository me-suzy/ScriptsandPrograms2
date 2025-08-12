<?php 
	//************************************************************************************
	//*																					*
	//*	This is a generic database connection function.						 			*
	//*																					*
	//************************************************************************************
	function DB_DBConnect($sSQLServerName, $sPHP_JKLogin, $sPHP_JKPassword)
	{
		Global $sUseDB;
		
		If ( $sUseDB == "MSSQL" ){
			$DBConnection = mssql_connect ($sSQLServerName, $sPHP_JKLogin, $sPHP_JKPassword)
				or
				Die ("Could not connect to database server: ".mssql_get_last_message()."<br>");
	
			mssql_select_db($GLOBALS["sDatabaseName"], $DBConnection)
				or
				Die ("Could not connect to the database: ".mssql_get_last_message());
				
			Return $DBConnection;
		}ElseIf ( $sUseDB == "MYSQL" ){
			$DBConnection = mysql_connect ($sSQLServerName, $sPHP_JKLogin, $sPHP_JKPassword)
				or
				Die ("Could not connect to database server: ".mysql_error()."<br>");
	
			mysql_select_db($GLOBALS["sDatabaseName"], $DBConnection)
				or
				Die ("Could not connect to the database: ".mysql_error());
				
			Return $DBConnection;
		}

	  return 0;
	} 
	//************************************************************************************


	//************************************************************************************
	//*																					*
	//*	Close the connection and command objects for the databases.			 			*
	//*																					*
	//************************************************************************************
	function DB_CloseDomains()
	{
		Global $sUseDB;
		Global $PHPJKConnection;
		
		If ( $sUseDB == "MSSQL" ){
			mssql_close($PHPJKConnection);
		}ElseIf ( $sUseDB == "MYSQL" ){
			mysql_close($PHPJKConnection);
		}
		
		ob_flush();
	
	  return 0;
	} 
	//************************************************************************************
	
	
	//***********************************************************************************
	//*																					*
	//*	sends a query to the database -- does this whichever way the admin set it up	*
	//*	either MSSQL, MySQL, ODBC, or whatever database.								*
	//*																					*
	//*	Sometimes the ResourceID must be passed in -- when getting PL's from other data	*
	//*		sources for example.														*
	//*																					*
	//***********************************************************************************
	Function DB_Query($sQuery, $sResourceID = "-1" )
	{
		Global $sUseDB;
		Global $PHPJKConnection;

		If ( $sResourceID == "-1" )
			$sResourceID = $PHPJKConnection;

		If ( $sUseDB == "MSSQL" ){
			Return mssql_query($sQuery, $sResourceID);
		}ElseIf ( $sUseDB == "MYSQL" ){
			$sQuery = str_replace(" (NOLOCK)", "", $sQuery);
			$sQuery = str_replace("GetDate()", "Now()", $sQuery);
			$sQuery = str_replace("NEWID()", "RAND()", $sQuery);
			If ( strpos($sQuery, "SET ROWCOUNT ") !== false ) {	// have to do this because in MySQL if you set the rowcount to 0, it IS 0. have to set to DEFAULT to reset it
				$sQuery = str_replace("SET ROWCOUNT ", "SET SQL_SELECT_LIMIT = ", $sQuery);
				$iPos = strrpos($sQuery, " ");
				If ($iPos !== false) {
					$sTemp = substr($sQuery, $iPos, strlen($sQuery)-$iPos);
					If ( $sTemp == "0" )
						$sQuery = "SET SQL_SELECT_LIMIT = DEFAULT";
				}
			}
//echo ":" . $sQuery . ":";
			Return mysql_query($sQuery, $sResourceID);
		}
	}
	//************************************************************************************
	
	
	//***********************************************************************************
	//*																					*
	//*	performs the database fetch according to whichever database we are using		*
	//*																					*
	//***********************************************************************************
	Function DB_Fetch($rsRecordSet)
	{
		Global $sUseDB;
		
		If ( $sUseDB == "MSSQL" ){
			Return mssql_fetch_array($rsRecordSet);
		}ElseIf ( $sUseDB == "MYSQL" ){
			Return mysql_fetch_array($rsRecordSet);
		}
	}
	//***********************************************************************************
	
	
	//***********************************************************************************
	//*																					*
	//*	performs the database update according to whichever database we are using		*
	//*																					*
	//***********************************************************************************
	Function DB_Update($sQuery, $sResourceID = "-1" )
	{
		Global $sUseDB;
		Global $PHPJKConnection;
		
		If ( $sResourceID == "-1" )
			$sResourceID = $PHPJKConnection;
			
		If ( $sUseDB == "MSSQL" ){
			Return mssql_query($sQuery, $sResourceID);
		}ElseIf ( $sUseDB == "MYSQL" ){
			$sQuery = str_replace(" (NOLOCK)", "", $sQuery);
			$sQuery = str_replace("GetDate()", "Now()", $sQuery);
			$sQuery = str_replace("\\", "\\\\", $sQuery);
			Return mysql_query($sQuery, $sResourceID);
		}
	}
	//***********************************************************************************
	
	
	//***********************************************************************************
	//*																					*
	//*	performs the database insert according to whichever database we are using		*
	//*																					*
	//***********************************************************************************
	Function DB_Insert($sQuery, $sResourceID = "-1" )
	{
		Global $sUseDB;
		Global $PHPJKConnection;
		
		If ( $sResourceID == "-1" )
			$sResourceID = $PHPJKConnection;

		If ( $sUseDB == "MSSQL" ){
			Return mssql_query($sQuery, $sResourceID);
		}ElseIf ( $sUseDB == "MYSQL" ){
			$sQuery = str_replace(" (NOLOCK)", "", $sQuery);
			$sQuery = str_replace("GetDate()", "Now()", $sQuery);
			$sQuery = str_replace("\\", "\\\\", $sQuery);
			Return mysql_query($sQuery, $sResourceID);
		}
	}
	//***********************************************************************************
	
	
	//***********************************************************************************
	//*																					*
	//*	Returns the number of rows returned from a query according to whichever database*
	//*		 we are using																*
	//*																					*
	//***********************************************************************************
	Function DB_NumRows($rsRecordSet)
	{
		Global $sUseDB;
			
		If ( $sUseDB == "MSSQL" ){
			Return mssql_num_rows($rsRecordSet);
		}ElseIf ( $sUseDB == "MYSQL" ){
			Return mysql_num_rows($rsRecordSet);
		}
	}
	//***********************************************************************************
	
	
	//***********************************************************************************
	//*																					*
	//*	connected to a MSSQL database via ODBC											*
	//*																					*
	//***********************************************************************************
	Function DB_ODBC_Query($QueryConnection, $sQuery)
	{
		Global $sUseDB;

		If ( $sUseDB == "MSSQL" ){
			Return odbc_exec($QueryConnection, $sQuery);
		}ElseIf ( $sUseDB == "MYSQL" ){
			Return odbc_exec($QueryConnection, $sQuery);
		}
	}
	//************************************************************************************
	
	
	//***********************************************************************************
	//*																					*
	//*	connected to a MSSQL database via ODBC											*
	//*																					*
	//***********************************************************************************
	Function DB_ODBC_Fetch($rsRecordSet)
	{
		Global $sUseDB;
			
		If ( $sUseDB == "MSSQL" ){
			unset($ar);
			if (odbc_fetch_row($rsRecordSet))
			{
				for ($i = 1; $i <= odbc_num_fields($rsRecordSet); $i++)
				{
					$field_name = odbc_field_name($rsRecordSet, $i);
					$ar[$field_name] = odbc_result($rsRecordSet, $field_name);
				}
				return $ar;
			}Else{
				return false;
			}
		}ElseIf ( $sUseDB == "MYSQL" ){
			unset($ar);
			if (odbc_fetch_row($rsRecordSet))
			{
				for ($i = 1; $i <= odbc_num_fields($rsRecordSet); $i++)
				{
					$field_name = odbc_field_name($rsRecordSet, $i);
					$ar[$field_name] = odbc_result($rsRecordSet, $field_name);
				}
				return $ar;
			}Else{
				return false;
			}
		}
	}
	//***********************************************************************************


	//***********************************************************************************
	//*																					*
	//*	Fetches configuration setings from the database.					 			*
	//*																					*
	//***********************************************************************************
	Function DOMAIN_Conf($sConfConst)
	{
		If ( $sConfConst == "" ){
			Echo "<br>Critical Error: Missing sConfConst.<br>";
		}Else{
			$sQuery			= "SELECT Value FROM Configurations (NOLOCK) WHERE ConfConst = '" . SQLEncode($sConfConst) . "'";
			$rsRecordSet	= DB_Query($sQuery);
			if ( $rsRow = DB_Fetch($rsRecordSet) )
				return $rsRow['Value'];
		}
		
		Return $sConfConst;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Encodes a string for use in a SQL Statement.						 			*
	//*																					*
	//************************************************************************************
	Function SQLEncode( $sStr )
	{
		If ( !is_null($sStr) ) {
			If ( !is_numeric($sStr) ) {
				If ( $sStr !== "" ) {
					Return str_replace("'", "''", $sStr);
				}
			}Else{
				Return strval($sStr);
			}
		}
		
		Return "";

	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Because DOMAIN_Link doesn't clear out the variables, extra variables are 		*
	//*		sometimes appended to the QueryString or put in the form. This function		*
	//*		clears all the variables. I split them up so if you have multiple instances	*
	//*		of the same link (or similar links), you don't have to reinitialize the		*
	//*		variables each time.														*
	//*																					*
	//************************************************************************************
	Function DOMAIN_Link_Clear()
	{
		Global $aVariables;
		Global $aValues;
		
		$aVariables	= array_fill(0, 19, '');
		$aValues	= array_fill(0, 19, '');
		
		Return 0;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This returns the domain name of the Domain whose DomainUnq is passed in.		*
	//*																					*
	//************************************************************************************
	Function DOMAIN_Domain_Name($iDomainUnq)
	{
		$sQuery			= "SELECT Domain FROM DomainInfo (NOLOCK) WHERE DomainUnq = " . $iDomainUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) ) {
			Return $rsRow['Domain'];
		}
		
		Return "";
		
	}
	//************************************************************************************

	
	
	//************************************************************************************
	//*																					*
	//*	This pops open a window once and sets a cookie so it doesn't pop the window		*
	//*		open again.																	*
	//*																					*
	//************************************************************************************
	Function DOMAIN_OneTimePopup($sPopupFile, $sTextConstant)
	{
		//DOMAIN_OneTimePopup("/Help/Popup.asp", "BETA_TEST");
		If ( ! isset($_COOKIE["PHPJK_OneTime"]) )
		{
			setcookie("PHPJK_OneTime", "T", 0, "/", $_SERVER["SERVER_NAME"], 0);
			?>
			<script language = "javascript">
			
				x = window.open('<?=$sPopupFile?>?sAction=<?=$sTextConstant?>','OneTime','scrollbars,resizable=yes')
				
			</script>
			<?php
		}
		
		Return True;
	}
	//************************************************************************************

	
	//************************************************************************************
	//*																					*
	//*	This returns blank if the email could be sent successfully, or an error message	*
	//*		otherwise.																	*
	//*																					*
	//************************************************************************************
	Function DOMAIN_Send_EMail($sFullLetter, $sFromName, $sFromAddress, $sRecipientName, $sRecipientEmail, $sSubject, $bIsHTML)
	{
		If ( Trim($sFromName) == "" ) {
			Return "Please enter the senders name for this email.";
		}Else{
			If ( Trim($sFromAddress) == "" ) {
				Return "Please enter the senders email address for this email.";
			}Else{
				If ( Trim($sRecipientEmail) == "" ) {
					Return "Please enter the recipients email address for this email message.";
				}Else{
					$sTemp = strtoupper(DOMAIN_Conf("EMAIL_TYPE"));
					If ( $sTemp == "ASPMAIL" ) {
						$Mailer					= new COM("SMTPsvg.Mailer");
						$Mailer->RemoteHost		= DOMAIN_Conf("EMAIL_REMOTEHOST");
						$Mailer->FromName		= $sFromName;
						$Mailer->FromAddress	= $sFromAddress;
						$Mailer->AddRecipient ($sRecipientName, $sRecipientEmail);
						If ( $bIsHTML )
							$Mailer->ContentType = "text/html";
						$Mailer->Subject		= $sSubject;
						$Mailer->BodyText		= $sFullLetter;
						$Mailer->QMessage		= true;
						$Mailer->SendMail();
						$sTemp					=$Mailer->Response();
						unset($Mailer);
						Return $sTemp;
					}ElseIf ( $sTemp == "CDONTS" ) {
						$oCDO			= new COM("CDONTS.NewMail");
						$oCDO->From		= $sFromAddress . " " . $sFromName;
						$oCDO->To		= $sRecipientEmail . " " . $sRecipientName;
						$oCDO->Subject	= $sSubject;
						If ( $bIsHTML ) {
							$oCDO->BodyFormat = 0;
							$oCDO->MailFormat = 0;
						}
						$oCDO->Body = $sFullLetter;
						$oCDO->Send();
						unset($oCDO);
					}ElseIf ( $sTemp == "SENDMAIL" ) {
						$mail = new PHPMailer();
						
						//$mail->IsSMTP();
						$mail->Host = DOMAIN_Conf("EMAIL_REMOTEHOST");
						//$mail->SMTPAuth = true;     // turn on SMTP authentication
						//$mail->Username = "jswan";  // SMTP username
						//$mail->Password = "secret"; // SMTP password
						
						$mail->From = $sFromAddress;
						$mail->FromName = $sFromName;
						$mail->AddAddress($sRecipientEmail, $sRecipientName);
						//$mail->AddReplyTo("info@example.com", "Information");
						
						$mail->WordWrap = 50;                                 // set word wrap to 50 characters
						If ( $bIsHTML )
							$mail->IsHTML(true);
						
						$mail->Subject = $sSubject;
						$mail->Body    = $sFullLetter;
						//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
						
						if(!$mail->Send())
						   Return $mail->ErrorInfo;
					}
				}
			}
		}
	
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This returns TRUE if the email Remote Host has something in it, FALSE otherwise.*
	//*																					*
	//************************************************************************************
	Function DOMAIN_Has_RemoteHost()
	{
		If ( trim(DOMAIN_Conf("EMAIL_REMOTEHOST")) != "" ) {
			Return True;
		}
		
		Return False;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Creates the all links and hidden form fields so that state can be kept across	*
	//*		subdomains/domains. This is so if there are several sub or domains in 		*
	//*		rotation (load balancing) that the login can be kept when the user goes back*
	//*		and forth between servers.													*
	//*	sType is "G" for Get (querystring) or "P" for Post (hidden form fields)			*
	//*																					*
	//************************************************************************************
	Function DOMAIN_Link($sType)
	{
		Global $aVariables;
		Global $aValues;
		
		$sTemp		= "";
		$iNumVars	= Count($aVariables);
		$iNumVals	= Count($aValues);

		If ( $iNumVars == $iNumVals ) {
			If ( $sType == "G" ) {
				If ( $aVariables[0] != "" ) {
					For ($x = 0; $x < $iNumVars; $x++){
						If ( $aVariables[$x] != "" ) {
							$sTemp = $sTemp . $aVariables[$x] . "=" . $aValues[$x] . "&";
						}
					}
				}Else{
					// need to do this for all the cases where we have <a href='default.asp?<%=DOMAIN_Link()&abc=123> and 
					//	no aValues or aVariables have been entered (happens sometimes - especially with JavaScript calls).
					Return "DOMAIN_Link=";
				}
			}ElseIf ( $sType == "P" ) {
				For ($x = 0; $x < $iNumVars; $x++){
					If ( $aVariables[$x] != "" ) {
						$sTemp = $sTemp . "<input type='hidden' name='" . $aVariables[$x] . "' value='" . $aValues[$x] . "'>\n";
					}
				}
			}Else{
				Return "Invalid sType: " . $sType;
			}
		}Else{
			Return "The number of variables (" . $iNumVars . ") and values (" . $iNumVals . ") differ.";
		}
		
		Return $sTemp;
		
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*	Displays a message. The sType determines if it's an "error" or "success" 		*
	//*		message. "SUCCESS" or "ERROR" are the two types.							*
	//*																					*
	//************************************************************************************
	Function DOMAIN_Message($sText, $sType)
	{
		Global $iTableWidth;
		
		If ( $sType == "ERROR" ) {
			$sTableClass = "TableError";
		}Else{
			$sTableClass = "TableSuccess";
		}
		?>
		<table width=<?=$iTableWidth?> class='<?=$sTableClass?>'>
			<tr>
				<td>
					<span class='MediumPageText'><?=$sText?></span>
					<br>
				</td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Removes any added \'s because of PHP's auto-encoding.							*
	//*																					*
	//************************************************************************************
   function FixFormFields($f_name) {
   	
   		If ( isset($_REQUEST[$f_name]) ) {
			$f_data = $_REQUEST[$f_name];
			if ($f_data) {
				if (get_magic_quotes_gpc()) {
					if (is_array($f_data)) {
						foreach ($f_data as $key => $value) {
							$array[$key] = stripslashes($value);
						}
						return $array;
					} else {
						return stripslashes($f_data);
					}
				} else {
					return $f_data;
				}
			} else {
				return "";
			}
		}Else{
			return "";
		}
   }
   //************************************************************************************
   
   
	//************************************************************************************
	//*																					*
	//*	Removes any added \'s because of PHP's auto-encoding.							*
	//*		This works on any data passed it, whereas FixFormFields actually Requests	*
	//*		the data by name.															*
	//*																					*
	//************************************************************************************
   function FixFormData($f_data) {
   	
		if ($f_data != "") {
			if (get_magic_quotes_gpc()) {
				if (is_array($f_data)) {
					foreach ($f_data as $key => $value) {
						$array[$key] = stripslashes($value);
					}
					return $array;
				} else {
					return stripslashes($f_data);
				}
			} else {
				return $f_data;
			}
		} else {
			return "";
		}
   }
   //************************************************************************************
   
   
   
	//************************************************************************************
	//*																					*
	//*	Return a value from the Request.									 			*
	//*	This is more like the ASP Request where it doesn't crash if nothing is there.	*
	//*																					*
	//************************************************************************************
	Function Request($sFieldName)
	{
		If ( isset($_REQUEST[$sFieldName]) )
			Return FixFormData(Trim($_REQUEST[$sFieldName]));
			
		Return "";
	} 
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Open the connection and command objects for the ImageGallery database.	 		*
	//*																					*
	//************************************************************************************
	Function DB_OpenImageGallery()
	{
		Global $sGalleryPath;
		Global $iFormColumns;
		Global $iFormWidth;
		Global $sSiteURL;
		
		$sGalleryPath 	= DOMAIN_Conf("PHP_JK_WEBROOT") . "\\" . DOMAIN_Conf("IG");
		$iFormColumns 	= Trim(DOMAIN_Conf("IMAGEGALLERY_FORMCOLUMNS"));
		$iFormWidth 	= Trim(DOMAIN_Conf("IMAGEGALLERY_FORMWIDTH"));
		$sSiteURL		= Trim(DOMAIN_Conf("IMAGEGALLERY_SITEURL"));

		If ( $iFormWidth < 21 )		// it's usually got 20 taken away from it - see the search.php file for examples
			$iFormWidth = 30;
		
		If ( $iFormColumns < 5 )
			$iFormColumns = 5;
	}
	//************************************************************************************


	//************************************************************************************
	//*																					*
	//*	str_replaces crazy characters in file names with underscores.					*
	//*																					*
	//************************************************************************************
	Function FixFilename($sName, $sType)
	{
		Global $sGalleryPath;
		Global $sAccountUnq;
		Global $iGalleryUnq;
		
		$iLen			= strLen($sName);
		$iCount			= 0;
		$sName			= preg_replace('/[^a-z0-9_\-\.]/i', '_', $sName);
		$sOriginalName	= $sName;

		If ( $sType == "SECONDARY" )
			$sName = "Resized_" . $sName;
		If ( $sType == "THUMB" ) {
			$sFilePath	= $sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\Thumbnails\\" . $sName;
		}Else{
			$sFilePath	= $sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\" . $sName;
		}
		$sFilePath	= str_replace("\\", "/", $sFilePath);
		$sFilePath	= str_replace("//", "/", $sFilePath);

		// check for the file first...w/o changing sName. If it IS found, then modify sName and check until not found
		While ( file_exists($sFilePath) )
		{
			$iCount++;
			$sName	= $sOriginalName;	// set it back to what it was originally and try the next iCount on it
			If ( $sType == "SECONDARY" )
				$sName = "Resized_" . $sName;
			$sName	= $iCount . $sName;
			If ( $sType == "THUMB" ) {
				$sFilePath	= $sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\Thumbnails\\" . $sName;
			}Else{
				$sFilePath	= $sGalleryPath . "\\" . $sAccountUnq . "\\" . $iGalleryUnq . "\\" . $sName;
			}
			$sFilePath	= str_replace("\\", "/", $sFilePath);
			$sFilePath	= str_replace("//", "/", $sFilePath);
		}

		Return $sName;
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*	Makes Windows directories recursively.											*
	//*																					*
	//************************************************************************************
	function mkdirs_win($dirname) //mkdir
	{
		$path		= "";
		$dirname	= str_replace("/", "\\", $dirname);
		
		$dir=split("\\\\", $dirname);
		for ($i=0;$i<count($dir);$i++)
		{
			$path.=$dir[$i]."\\";
			
			if (!is_dir($path))
				@mkdir($path,0777);
				
			@chmod($path,0777);
		}
		if (is_dir($dirname))
			return 1;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Makes *nix directories recursively.												*
	//*																					*
	//************************************************************************************
	function mkdirs_nix($dirname) //mkdir
	{
		$path		= "";
		$dirname	= str_replace("\\", "/", $dirname);
		$dirname	= str_replace("//", "/", $dirname);
		
		$dir=split("/", $dirname);
		for ($i=0;$i<count($dir);$i++)
		{
			$path.=$dir[$i]."/";
			
			if (!is_dir($path))
				@mkdir($path,0777);
				
			@chmod($path,0777);
		}
		if (is_dir($dirname))
			return 1;
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is a simple version of the ASP DateDiff									*
	//*																					*
	//************************************************************************************
	Function DateDiff($sDiff, $sDate1, $sDate2)
	{
		If ( ! is_numeric($sDate1) )
			$sDate1	= strtotime($sDate1);
		If ( ! is_numeric($sDate2) )
			$sDate2	= strtotime($sDate2);

		// they want the number of days different
		If ( $sDiff == "d" )
		{
			// this next bit may seem odd. But in order to round to the nearest day, we need
			//	to convert back to a normal date string, then back to the UNIX timestamp
			//	otherwise, if DateDiff is called w/ time() as a paramater vs/ an actual
			//	date string, it could return differing values depending on where the
			//	day gets rounded
			$sDate1=date("F j, Y", $sDate1);
			$sDate2=date("F j, Y", $sDate2);
			$sDate1	= strtotime($sDate1);
			$sDate2	= strtotime($sDate2);

			If ( $sDate2 - $sDate1 == 0 )
			{
				// the date() function will return 31 if the dif in dates is 0!
				Return 0;
			}Else{
				Return date("j", $sDate2 - $sDate1);
			}
		}
	} 
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Replicates ASP's DateAdd function.												*
	//*																					*
	//************************************************************************************
	function DateAdd($sDatePart, $iAmount, $sDate)
	{
		If ( ! is_numeric($sDate) )
			$sDate	= strtotime($sDate);

		If ( $sDatePart == "d" ) {
			$sDate		= $sDate + ($iAmount*86400);	// add the number of seconds in each day
			$timePieces = getdate($sDate);
		}
		
		If ( $sDatePart == "h" ) {
			$sDate		= $sDate + ($iAmount*3600);
			$timePieces = getdate($sDate);
		}
		
		return $timePieces["mon"] . "/" . ($timePieces["mday"]) . "/" . $timePieces["year"] . " " . $timePieces["hours"] . ":" . $timePieces["minutes"] . ":" . $timePieces["seconds"];
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This formats the date for SQL statements. This is required because not all  	*
	//*	places in the world use the USA date format and MSSQL requires that dates		*
	//*	be in this format: mm/dd/yyyy or mm/dd/yyyy hh:mm:ss							*
	//*	Also in some languages May is Mai, etc. and MSSQL doesn't understand Mai.		*
	//*	The sLength parameter is "S" for shortdatetime and "L" for datetime (long date)	*
	//*																					*
	//************************************************************************************
	Function DOMAIN_FormatDate($sDate, $cLength)
	{
		If ( ! is_numeric($sDate) )
			$sDate	= strtotime($sDate);

		If ( $cLength == "L" ) {
			// make the date long
			Return date("n/j/Y H:m:s", $sDate);
		}Else{
			// make the date short - default to this since it's more compatable
			Return date("n/j/Y", $sDate);
		}
	}
	//************************************************************************************
?>
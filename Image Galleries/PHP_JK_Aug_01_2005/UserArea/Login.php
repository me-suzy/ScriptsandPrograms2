<?php
	Require("../Includes/i_Includes.php");
	Require("../Includes/Config/i_Login.php");
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	
	$sLogin			= "";
	$sPassword		= "";
	$sReturnPage	= "";
	$sEmail			= "";
	
	Main();
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $bHasAccount;
		Global $iLoginAccountUnq;
		Global $sLogin;
		Global $sPassword;
		Global $sReturnPage;
		Global $sEmail;
		Global $CONF_LPemail;
		
		$sA				= "";
		$sError			= "";
		
		$sReturnPage = Trim(Request("sReturnPage"));
		If ( $sReturnPage == "" )
		{
			If ( isset($_SERVER['HTTP_REFERER_http']) )
			{
				$sReturnPage = $_SERVER["HTTP_REFERER_http"];
			}ElseIf ( isset($_SERVER['HTTP_REFERER']) )
			{
				$sReturnPage = $_SERVER["HTTP_REFERER"];
			}
		}
		$sA = Trim(Request("sA"));
			
		If ( $sA == "" ) {
			If ( $bHasAccount == True ) {
				DOMAIN_Message("You are already logged in.", "ERROR");
			}Else{
				WriteForm();
			}
		}Else{
			If ( Request("sForgotPassword") == "T" ) {
				// they are trying to retrieve their password
				$sEmail = Trim(Request("sEmail"));
				If ( (strpos($sEmail, "@") < strpos($sEmail, ".")) && (strpos($sEmail, ".") > 3) ) {
					$sQuery			= "SELECT A.Login, A.Password FROM Accounts A, AccountData D WHERE D.MapName = 'PHPJK_EmailAddress' AND D.VarCharData = '" . $sEmail . "' AND A.AccountUnq = D.AccountUnq";
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) ) {
						$sLogin		= $rsRow["Login"];
						$sPassword	= $rsRow["Password"];
						// Send the emails*********
						$sFullLetter = str_replace("2:", $sPassword, str_replace("1:", $sLogin, $CONF_LPemail));
						$sEmailResponse = DOMAIN_Send_EMail($sFullLetter, DOMAIN_Conf("EMAIL_LOGIN_FROMNAME"), DOMAIN_Conf("EMAIL_LOGIN_FROMEMAIL"), $sLogin, $sEmail, "Found login/password", FALSE);
						if ( ( $sEmailResponse === True ) || ( trim($sEmailResponse) == "" ) ) {
							DOMAIN_Message("Your login and password have been sent...<br>", "SUCCESS");
						}Else{
							$sError = str_replace("1:", $sEmail, "Mail failure sending to 1:. Check mail host server name and tcp/ip connection...<br>");
							DOMAIN_Message($sError . $sEmailResponse . "<BR>", "ERROR");
						}
						// END Send the emails*********
						// blank out the login/pw so it doesn't show up in the form
						$sLogin = "";
						$sPassword = "";
					}Else{
						$sLogin		= "";
						$sPassword	= "";
						DOMAIN_Message("Sorry but the email you entered was either invalid or not found in our email list. Please enter a different email address.", "ERROR");
					}
				}Else{
					DOMAIN_Message("The email address you entered is not valid. Please enter another email address and try again.", "ERROR");
				}
				WriteForm();
			}Else{
				// they are trying to login
				$sLogin		= Trim(Request("sLogin"));
				$sPassword	= Trim(Request("sPassword"));
				
				If ( $sLogin == "" )
					$sError = "Please enter your login.<br>";
				If ( $sPassword == "" )
					$sError = $sError . "Please enter your password.<br>";
				
				If ( $sError == "" ) {
					$sQuery			= "SELECT AccountUnq, Password FROM Accounts (NOLOCK) WHERE Login = '" . SQLEncode($sLogin) . "' AND Password = '" . SQLEncode($sPassword) . "' AND (AddDate > RemoveDate OR RemoveDate IS NULL) AND Authenticated = 'T'";
					$rsRecordSet	= DB_Query($sQuery);
					If ( $rsRow = DB_Fetch($rsRecordSet) ) {
						$sAccountUnq = $rsRow["AccountUnq"];
					}Else{
						$sError = $sError . "Invalid login and/or password.<br>";
					}
				}
				
				If ( $sError == "" ) {
					If ( Request("bAutoLogin") == "Y" )
					{
						setcookie("GAL1", $sLogin, (time()+15768000), "/", $_SERVER["SERVER_NAME"]);
						setcookie("GAP1", md5($sPassword), (time()+15768000), "/", $_SERVER["SERVER_NAME"]);
						setcookie("GAA1", $sAccountUnq, (time()+15768000), "/", $_SERVER["SERVER_NAME"]);
						setcookie("GAAuto1", "Y", (time()+15768000), "/", $_SERVER["SERVER_NAME"]);
					}Else{
						setcookie("GAL1", $sLogin, 0, "/", $_SERVER["SERVER_NAME"]);
						setcookie("GAP1", md5($sPassword), 0, "/", $_SERVER["SERVER_NAME"]);
						setcookie("GAA1", $sAccountUnq, 0, "/", $_SERVER["SERVER_NAME"]);
					}
					// increment the number of times this account has logged in: PHPJK_NumLogins
					$iLoginAccountUnq = $sAccountUnq;	// need to set this here for ACCNT_ReturnADV
					$sTemp = Trim(ACCNT_ReturnADV("PHPJK_NumLogins", "V", $sAccountUnq, 1, $x));
					If ( $sTemp != "" ) {
						$sTemp++;
					}Else{
						$sTemp = 1;
					}
					ACCNT_WriteADV("PHPJK_NumLogins", $sTemp, "V", $sAccountUnq, "PRIVATE");
					ACCNT_WriteADV("PHPJK_IPAddress", $_SERVER["REMOTE_ADDR"], "V", $sAccountUnq, "PRIVATE");
					If ( Trim($sReturnPage) == "" ) {
						// unfortunately there is a PHP/IIS bug that prevents us from using the "Header" function to send a redirect header after setting cookies
						//header( 'location:/' );	// go home on an emergency where neither the referrer nor return page have data
						?>
						<script language='JavaScript1.2' type='text/javascript'>
						
							document.location = "/";
						
						</script>
						<?php
						ob_flush();
						exit;
					}Else{
						//header( 'location:' . $sReturnPage );
						?>
						<script language='JavaScript1.2' type='text/javascript'>
						
							document.location = "<?=$sReturnPage?>";
						
						</script>
						<?php
						ob_flush();
						exit;
					}
				}Else{
					DOMAIN_Message($sError, "ERROR");
					WriteForm();
				}
			}
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
		Global $sLogin;
		Global $sPassword;
		Global $sReturnPage;
		Global $aVariables;
		Global $aValues;
		Global $sEmail;
		Global $iTableWidth;

		$aVariables[0] = "sA";
		$aValues[0] = "T";
		?>
		<br>
		<center>
		<form action = "Login.php?<?=DOMAIN_Link("G")?>" method = "post" class='PageForm'>
			<input type = "hidden" name = "sReturnPage" value = "<?=$sReturnPage?>">
			<table cellpadding = 5 cellspacing = 5 border = 0 width=<?=$iTableWidth?> class='TablePage_Boxed'>
				<tr>
					<td align = right width=50%>
						Enter your login
					</td>
					<td width=50%>
						<input type = "text" name = "sLogin" value = "<?=$sLogin?>" maxlength=32>
					</td>
				</tr>
				<tr>
					<td align = right>
						Enter your password
					</td>
					<td>
						<input type = "password" name = "sPassword" value = "" maxlength=32>
					</td>
				</tr>
				<tr>
					<td align = right>
						Turn on auto-login
					</td>
					<td>
						<input type = "checkbox" name = "bAutoLogin" value = "Y" <?php
						If ( Request("bAutoLogin") == "Y" )
							Echo "checked";
						?> style='border: 0px;'>
					</td>
				</tr>
				<tr>
					<td colspan = 2 align = center>
						<input type = "submit" value = "Log In">
					</td>
				</tr>
			</table>
		</form>
		<?php
		If ( DOMAIN_Has_RemoteHost() == True ) {
			// don't print this out if the remotehost for the server is blank.
			$aVariables[0] = "sA";
			$aVariables[1] = "sForgotPassword";
			$aValues[0] = "T";
			$aValues[1] = "T";
			?>
			<br>
			<form action = "Login.php?<?=DOMAIN_Link("G")?>" method = "post" class='PageForm'>
				<table cellpadding = 5 cellspacing = 5 border = 0 width=<?=$iTableWidth?> class='TablePage_Boxed'>
					<tr>
						<td colspan=2>
							If you have forgotten your password, please enter the email address you used when signing up for your account in the form below. Your password will be emailed to that address.
						</td>
					</tr>
					<tr>
						<td align = right>
							Enter your email address
						</td>
						<td>
							<input type = "text" name = "sEmail" value = "<?=$sEmail?>">
						</td>
					</tr>
					<tr>
						<td colspan = 2 align = center>
							<input type = "submit" value = "Retrieve Password">
						</td>
					</tr>
				</table>
			</form>
			<?php
		}
		Echo "<br>";
	}
	//************************************************************************************
?>
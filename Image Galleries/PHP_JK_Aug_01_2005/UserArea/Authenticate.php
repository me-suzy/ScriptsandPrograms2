<?php
	require("../Includes/i_Includes.php");
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	Main();
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iID;
		Global $sUName;
		
		$sID	= Trim(Request("id"));
		$iID	= Trim(Request("iID"));
		$sUName	= Trim(Request("sUName"));
		
		If ( $sID != "" )
		{
			If ( is_numeric($sID) )
			{
				// try and use the sID to authenticate the user
				$sQuery			= "SELECT AccountUnq, Login, Password FROM Accounts WHERE AuthID = " . $sID;
				$rsRecordSet	= DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) ) {
					ActivateOk($rsRow["AccountUnq"], $rsRow["Login"], $rsRow["Password"]);
				}Else{
					DOMAIN_Message("Unable to authenticate account - incorrect authentication id.", "ERROR");
					WriteForm();
				}
			}Else{
				DOMAIN_Message("Unable to authenticate account - incorrect authentication id.", "ERROR");
				WriteForm();
			}
		}ElseIf ( $iID != "" ) {
			If ( is_numeric($iID) )
			{
				// they submitted the form so see if the iID and sUName match up
				$sQuery			= "SELECT AccountUnq, Login, Password FROM Accounts WHERE Login = '" . SQLEncode($sUName) . "' AND AuthID = " . $iID;
				$rsRecordSet	= DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) ) {
					ActivateOk($rsRow["AccountUnq"], $rsRow["Login"], $rsRow["Password"]);
				}Else{
					DOMAIN_Message("Unable to authenticate account - incorrect Activation ID and/or User Name.", "ERROR");
					WriteForm();
				}
			}Else{
				DOMAIN_Message("Unable to authenticate account - incorrect Activation ID.", "ERROR");
				WriteForm();
			}
		}Else{
			// print out the form so the user can enter their login and ID #
			WriteForm();
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function ActivateOk($iAccountUnq, $sLogin, $sPassword)
	{
		Global $iTableWidth;
		Global $aValues;
		Global $aVariables;
		Global $sSiteURL;
		
		/* do not remove the AuthID from the Accounts table because many times
			the person comes back multiple times -- if the system can't find their
			AuthID (even if they have already been authenticated), it'll say
			that they weren't authenticated -- even tho they were before and their
			login already works (they usually won't try their login/pw when they
			get such a message).*/
		DB_Update ("UPDATE Accounts SET Authenticated = 'T' WHERE AccountUnq = " . $iAccountUnq);
		Echo "<br><br>";
		Echo "<table width=" . $iTableWidth . " cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor = " . $GLOBALS["BorderColor1"] . " align=center><table cellpadding=5 width=100% cellspacing = 0 border = 0><tr><td bgcolor = " . $GLOBALS["BGColor1"] . " valign=top align=center><font color='" . $GLOBALS["TextColor1"] . "'>";
		Echo "<br>";
		DOMAIN_Message("Thank you for registering. Your account has been successfully authenticated and activated.<br><Br>You will now be automatically logged in and forwarded to the home page.", "SUCCESS");
		Echo "<br><br>";
		Echo "</table></td></tr></table><br>";
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			timer = setTimeout('document.location = "<?=$sSiteURL?>/UserArea/Login.php?<?=DOMAIN_Link("G")?>&sA=T&sReturnPage=/&=<?=DOMAIN_Link("G")?>&sLogin=<?=URLEncode($sLogin)?>&sPassword=<?=URLEncode($sPassword)?>";', 7000);

		</script>
		<?php 
	}
	//************************************************************************************
	

	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $iID;
		Global $sUName;
		?>
		<center>
		<form name='Authenticate' action='Authenticate.php' method='post'>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='/Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Activate Your Account</b></font>
					<table cellpadding = 1 cellspacing = 0 border = 0 width=671><tr><td bgcolor='<?=$GLOBALS["BorderColor1"]?>'>
					<table cellpadding = 5 cellspacing = 0 width=100%><tr><td bgcolor=<?=$GLOBALS["PageBGColor"]?>>
					<br><br>
					<center>
					<table>
						<tr>
							<td bgcolor=<?=$GLOBALS["PageBGColor"]?>><font color='<?=$GLOBALS["PageText"]?>'>User Name:</tD>
							<td bgcolor=<?=$GLOBALS["PageBGColor"]?>><input type='text' name='sUName' value='<?=$sUName?>'></td>
						</tr>
						<tr>
							<td bgcolor=<?=$GLOBALS["PageBGColor"]?>><font color='<?=$GLOBALS["PageText"]?>'>Activation ID:</tD>
							<td bgcolor=<?=$GLOBALS["PageBGColor"]?>><input type='text' name='iID' value='<?=$iID?>'></td>
						</tr>
					</table>
					<br>
					<input type='submit' value='Activate Now'>
					</tr></td></table>
					</tr></td></table>
				</td>
			</tr>
			<tr><td><img src='/Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		</form>
		<?php 
	}
	//************************************************************************************
?>
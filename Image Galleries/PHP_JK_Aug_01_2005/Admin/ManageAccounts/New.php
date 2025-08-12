<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_DEFAULT.php");
	
	$sNewLogin		= "";
	$sNewPassword	= "";
	
	If (ACCNT_ReturnRights("PHPJK_MA_CREATE_NEW")) {
		HeaderHTML();
		Main();
	}Else{
		DOMAIN_Message("You must login with Account rights.", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $sNewLogin;
		Global $sNewPassword;
		
		$sAction = "";
		
		If ( isset($_REQUEST["sAction"]) )
			$sAction = Trim($_REQUEST["sAction"]);
		If ( isset($_REQUEST["sNewLogin"]) )
			$sNewLogin = Trim($_REQUEST["sNewLogin"]);
		If ( isset($_REQUEST["sNewPassword"]) )
			$sNewPassword = Trim($_REQUEST["sNewPassword"]);
		
		If ( $sAction == "AddAccount" ) {
			If ( ( $sNewPassword != "" ) && ( $sNewLogin != "" ) ) {
				If ( LoginExists() ) {
					DOMAIN_Message("The login you entered already exists.", "ERROR");
				}Else{
					DB_Insert ("INSERT INTO Accounts (Login,Password,AddDate,RemoveDate,HomeDomain,IgnoreGlobal,Authenticated,AuthID) VALUES ('" . SQLEncode($sNewLogin) . "', '" . SQLEncode($sNewPassword) . "', GetDate(), NULL, 1, '','T',0)");
					$sQuery			= "SELECT @@IDENTITY";
					$rsRecordSet	= DB_Query($sQuery);
					if ( $rsRow = DB_Fetch($rsRecordSet) )
					{
						$sTempAccountUnq = $rsRow[0];
						
						// Add all the Initial Rights to the new account
						$sQuery			= "SELECT * FROM InitialRights (NOLOCK)";
						$rsRecordSet	= DB_Query($sQuery);
						while ( $rsRow = DB_Fetch($rsRecordSet) )
							DB_Insert ("INSERT INTO RIGHTS VALUES (" . $sTempAccountUnq . ", 0, 0, 1, '" . $rsRow["RightsLvl"] . "', GetDate(), NULL)");
						
						$sNewLogin		= "";
						$sNewPassword	= "";
					}Else{
						$sError = "Cannot get the new Account ID#. Initial Rights have not been assigned.";
					}
					DOMAIN_Message("The new login was created successfully!", "SUCCESS");
				}
			}Else{
				DOMAIN_Message("Please make sure you've entered a login and password.", "ERROR");
			}
		}
		
		WriteForm();
		
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This checks to see if the login they entered already exists or not.				*
	//*																					*
	//************************************************************************************
	Function LoginExists()
	{
		Global $sNewLogin;
	
		$sQuery			= "SELECT * FROM Accounts (NOLOCK) WHERE Login = '" . SQLEncode($sNewLogin) . "'";
		$rsRecordSet	= DB_Query($sQuery);
		if ( $rsRow = DB_Fetch($rsRecordSet) )
			Return True;
			
		Return False;
	}
	//************************************************************************************
	

	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $sNewLogin;
		Global $sNewPassword;
		Global $aVariables;
		Global $aValues;
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.Rights.sAction.value = sAction;
				document.Rights.submit();
			}
			
		</script>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Add New Accounts</b></font>
					<br>
					This creates a new account. The new account will have all the Initial Rights as were set up. 
					<br>
					<center>
					<form name='Rights' action='New.php' method='post'>
					<?php
					$aVariables[0] = "sAction";
					$aVariables[1] = "iDBLoc";
					$aVariables[2] = "sSortBy";
					$aVariables[3] = "sSearchIDNum";
					$aVariables[4] = "sSearchEmail";
					$aVariables[5] = "sSearchLogin";
					$aVariables[6] = "sIDNumQualifier";
					$aVariables[7] = "sEmailQualifier";
					$aVariables[8] = "sLoginQualifier";
					$aVariables[9] = "sBeginSearch";
					$aVariables[10] = "sAllowWildcards";
					$aVariables[11] = "sRSearch";
					$aVariables[12] = "sLSearch";
					$aVariables[13] = "sJoin";
					$aVariables[14] = "sSort";
					$aValues[0] = "New";
					$aValues[1] = Request("iDBLoc");
					$aValues[2] = Request("sSortBy");
					$aValues[3] = Request("sSearchIDNum");
					$aValues[4] = Request("sSearchEmail");
					$aValues[5] = Request("sSearchLogin");
					$aValues[6] = Request("sIDNumQualifier");
					$aValues[7] = Request("sEmailQualifier");
					$aValues[8] = Request("sLoginQualifier");
					$aValues[9] = Request("sBeginSearch");
					$aValues[10] = Request("sAllowWildcards");
					$aValues[11] = Request("sRSearch");
					$aValues[12] = Request("sLSearch");
					$aValues[13] = Request("sJoin");
					$aValues[14] = Request("sSort");
					Echo DOMAIN_Link("P");
					?>
					<table cellpadding = 0 cellspacing = 0 border = 0 width=100%><tr><td bgcolor = <?=$GLOBALS["BorderColor1"]?> align=center>
						<table cellpadding = 5 width=100%>
							<tr>
								<td valign=top bgcolor = <?=$GLOBALS["BGColor1"]?> align = right width=50%>
									<font color='<?=$GLOBALS["TextColor1"]?>'>Account Login:
								</td>
								<td valign=top bgcolor = <?=$GLOBALS["BGColor1"]?>>
									<input type="text" name="sNewLogin" value='<?=$sNewLogin?>' Size=32 maxlength=32>
								</td>
							</tr>
							<tr>
								<td valign=top bgcolor = <?=$GLOBALS["BGColor1"]?> align = right>
									<font color='<?=$GLOBALS["TextColor1"]?>'>Account Password:
								</td>
								<td valign=top bgcolor = <?=$GLOBALS["BGColor1"]?>>
									<input type="text" name="sNewPassword" value='<?=$sNewPassword?>' Size=32 maxlength=32>
								</td>
							</tr>
						</table>
						<table cellpadding = 5 width=100%>
							<tr>
								<td valign=top bgcolor = <?=$GLOBALS["BGColor2"]?> align = center>
									<input type="button" value=" Add New Account " onClick='SubmitForm("AddAccount")'>
								</td>
							</tr>
						</table>
					</td></tr></table>
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
	//************************************************************************************
	Function HeaderHTML()
	{
		Global $aVariables;
		Global $aValues;
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=3 bgcolor=FFFFFF width=100%><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If ( (ACCNT_ReturnRights("PHPJK_MR_MODIFY") ) || ( ACCNT_ReturnRights("PHPJK_MR_VIEW") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='index.php?" . DOMAIN_Link("G") . "&sSortBy=" . Request("sSortBy") . "&iDBLoc2=" . Request("iDBLoc") . "&sSearchIDNum=" . Request("sSearchIDNum") . "&sSearchEmail=" . rawurlencode(Request("sSearchEmail")) . "&sSearchLogin=" . rawurlencode(Request("sSearchLogin")) . "&sIDNumQualifier=" . Request("sIDNumQualifier") . "&sEmailQualifier=" . Request("sEmailQualifier") . "&sLoginQualifier=" . Request("sLoginQualifier") . "&sAllowWildcards=" . Request("sAllowWildcards") . "'><img src='../../Images/Administrative/Return.gif' Width=30 Height=38 Border=0 Alt='Return to the main Manage Accounts page.'></a></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				?>
				<td bgcolor=FFFFFF width=100%>&nbsp;</td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=3 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=5 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
?>
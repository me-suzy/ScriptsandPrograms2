<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_DEFAULT.php");
	
	$sVisible		= "";
	$sRequired		= "";
	$sDataType		= "";
	$sDescription	= "";
	$sMapName		= "";
	$sRightsLvl		= "";
	
	If (ACCNT_ReturnRights("PHPJK_MADV_ADD")) {
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
		Global $sVisible;
		Global $sRequired;
		Global $sDataType;
		Global $sDescription;
		Global $sMapName;
		
		$sAction		= Request("sAction");
		$sVisible		= Trim(Request("sVisible"));
		$sRequired		= Trim(Request("sRequired"));
		$sDataType		= Trim(Request("sDataType"));
		$sDescription	= Trim(Request("sDescription"));
		$sMapName		= Trim(Request("sMapName"));
		$sRightsLvl		= Trim(Request("sRightsLvl"));
		$sError			= "";
		
		If ( $sVisible == "" )
			$sVisible = "N";
		If ( $sRequired == "" )
			$sRequired = "N";
		If ( $sDataType == "" )
			$sDataType = "V";

		If ( $sAction == "AddADV" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_MADV_ADD") ) {
				$sError = "You must login with Domains rights.<br>";
			}Else{
				// See if it already exists then add it.
				If ( $sMapName == "" )
					$sError = "Please enter the Account Data Variable Name.";
				If ( $sError == "" ) {
					If ( MapExists($sMapName) ) {
						$sError = "The Account Data Variable you are trying to create already exists.";
					}Else{
						$sTemp = "";
						If ( $sRequired == "Y" ) {
							$sVisible	= "Y";
							$sTemp		= "<br>The variable has been set to \"Visible\".";
						}
						DB_Insert ("INSERT INTO AccountMap VALUES ('" . SQLEncode($sMapName) . "','" . $sDataType . "','" . SQLEncode($sDescription) . "','" . $sRequired . "','" . $sVisible . "',1, 0, '" . SQLEncode($sRightsLvl) . "',0)");
						DOMAIN_Message("The new Account Data Variable has been created successfully!" . $sTemp, "SUCCESS");
						$sVisible		= "";
						$sRequired		= "";
						$sDataType		= "";
						$sDescription	= "";
						$sMapName		= "";
					}
				}
			}
		}

		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		
		WriteForm();
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	Checks to see if the Account Data Variable they are trying to enter is already	*
	//*		in the database.															*
	//*																					*
	//************************************************************************************
	Function MapExists($sMapName)
	{
		$sQuery			= "SELECT * FROM AccountMap (NOLOCK) WHERE MapName = '" . SQLEncode($sMapName) . "'";
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
		Global $sVisible;
		Global $sRequired;
		Global $sDataType;
		Global $sDescription;
		Global $sMapName;
		Global $sRightsLvl;
		Global $aVariables;
		Global $aValues;
		
		$sBGColor = $GLOBALS["BGColor1"];
		
		// Get the list of Rights Levels
		$x = 0;
		$sQuery			= "SELECT * FROM RightsLookup (NOLOCK)";
		$rsRecordSet	= DB_Query($sQuery);
		while ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			If ( strLen ($rsRow["RightsConst"]) > 45 ) {
				$sTemp = substr($rsRow["RightsConst"], 0, 42) . "...";
			}Else{
				$sTemp = $rsRow["RightsConst"];
			}
			$aRightsLvl[0][$x] = $rsRow["RightsLvl"];
			$aRightsLvl[1][$x] = $sTemp;
			$x++;
		}
		$iUBoundRightsLvl = $x;
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageADV.sAction.value = sAction;
				document.ManageADV.submit();
			}
			
		</script>
		<form name='ManageADV' action='New.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aValues[0] = "New";
		Echo DOMAIN_Link("P");
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Add New ADVs</b></font>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor1"]?>'><b>Name</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor1"]?>'><b>Description</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor1"]?>'><b>Required?</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor1"]?>'><b><sup>*</sup>Visible?</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor1"]?>' size=-2>Textfield<br><250 char</td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor1"]?>' size=-2>Notesfield<br>>250 char</td>
						</tr>
						<?php $sBGColor = $GLOBALS["PageBGColor"]; ?>
						<tr>
							<td bgcolor=<?=$sBGColor?>><input type='text' name='sMapName' value='<?=$sMapName?>' size=10 maxlength=250></td>
							<td bgcolor=<?=$sBGColor?>><input type='text' name='sDescription' value='<?=$sDescription?>' size=25 maxlength=250></td>
							<td align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name='sRequired' value='Y' <?php If ( $sRequired == "Y" ) Echo "checked"; ?>></td>
							<td align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name='sVisible' value='Y' <?php If ( $sVisible == "Y" ) Echo "checked"; ?>></td>
							<td align=center bgcolor=<?=$sBGColor?>><input type='radio' name='sDataType' value='V' <?php If ( $sDataType == "V" ) Echo "checked"; ?>></td>
							<td align=center bgcolor=<?=$sBGColor?>><input type='radio' name='sDataType' value='T' <?php If ( $sDataType == "T" ) Echo "checked"; ?>></td>
						</tr>
					</table>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
					<tr>
						<td bgcolor=<?=$sBGColor?> colspan=2>
							<font color='<?=$GLOBALS["PageText"]?>'><b>Optional Rights Level needed to access this ADV:</b><br>
							<select name = "sRightsLvl">
								<?php
								Echo "<option value = ''>No associated rights level.</option>";
								For ( $x = 0; $x < ($iUBoundRightsLvl - 1); $x++)
								{
									If ( $sRightsLvl == $aRightsLvl[0][$x] ) {
										Echo "<option value = \"" . FixFormData($aRightsLvl[0][$x]) . "\" Selected>" . FixFormData($aRightsLvl[0][$x]) . " - " . FixFormData($aRightsLvl[1][$x]) . "</option>";
									}Else{
										Echo "<option value = \"" . FixFormData($aRightsLvl[0][$x]) . "\">" . FixFormData($aRightsLvl[0][$x]) . " - " . FixFormData($aRightsLvl[1][$x]) . "</option>";
									}
								}
								?>
							</select>
						</td>
					</tr>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$GLOBALS["BGColor1"]?> align=center>
								<input type="button" value="  Add New ADV  " onClick='SubmitForm("AddADV")'>
							</td>
						</tr>
					</table>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td>
								<font color='<?=$GLOBALS["PageText"]?>'>
								<font size=-2><br>
								<sup>**</sup>If the variable is set as "Required", it will always be visible.
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		</form>
		<?php 
	}
	//********************************************************************************
	
	

	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function HeaderHTML()
	{
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
				If ((ACCNT_ReturnRights("PHPJK_MADV_ADD")) || (ACCNT_ReturnRights("PHPJK_MADV_EDIT")) || (ACCNT_ReturnRights("PHPJK_MADV_DELETE"))) {
					Echo "<td bgcolor=FFFFFF width=1><a href='index.php'><img src='../../Images/Administrative/Return.gif' Width=30 Height=38 Border=0 Alt='Return to the main Manage ADV page.'></a></td>";
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
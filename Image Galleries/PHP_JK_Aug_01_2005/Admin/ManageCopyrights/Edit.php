<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iTtlNumItems	= Trim(Request("iTtlNumItems"));
	$iDBLoc			= Trim(Request("iDBLoc"));
	$sDetails		= Trim(Request("sDetails"));
	$sOldDetails	= Trim(Request("sOldDetails"));
	$sAction		= Trim(Request("sAction"));
	$iCopyUnq		= Trim(Request("iCopyUnq"));
	
	
	WriteScripts();

	If (ACCNT_ReturnRights("PHPJK_IG_EDIT_CR")) {
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
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $sDetails;
		Global $sAction;
		Global $iCopyUnq;
		Global $sOldDetails;
		
		$sError = "";

		If ( $iCopyUnq == "" ) {
			DOMAIN_Message("Missing CopyUnq. Unable to update or edit the copyright details.", "ERROR");
		}Else{
			If ( $sAction == "UpdateCRDetails" ){
				If ( ! ACCNT_ReturnRights("PHPJK_IG_EDIT_CR") ) {
					$sError = "Please log in with Image Gallery management rights.";
				}Else{
					$sDetails		= Trim(Request("sDetails"));
					$sOldDetails	= Trim(Request("sOldDetails"));

					If ( $sDetails != $sOldDetails ) {
						DB_Update ("UPDATE IGCopyrights SET Details = '" . SQLEncode($sDetails) . "' WHERE CopyUnq = " . $iCopyUnq);
						DOMAIN_Message("Copyright details updated successfully.", "SUCCESS");
					}
				}
			}Else{
				// get the CR Details
				$sQuery			= "SELECT Details FROM IGCopyrights (NOLOCK) WHERE CopyUnq = " . $iCopyUnq;
				$rsRecordSet	= DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					$sDetails		= Trim($rsRow["Details"]);
					$sOldDetails	= $sDetails;
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
		Global $sOldDetails;
		Global $sDetails;
		Global $iCopyUnq;
		
		$sBGColor = $GLOBALS["BGColor1"];
		?>
		<form name='EditCRDetails' action='Edit.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "iDBLoc";
		$aVariables[2] = "iTtlNumItems";
		$aVariables[3] = "iCopyUnq";
		$aValues[0] = "UpdateCRDetails";
		$aValues[1] = $iDBLoc;
		$aValues[2] = $iTtlNumItems;
		$aValues[3] = $iCopyUnq;
		Echo DOMAIN_Link("P");
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr><td><font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Edit Copyright Details</b></font></td></tr>
			<tr>
				<td>
					<br>
					<table cellpadding=1 cellspacing=0 border=0 width=671><tr><td bgcolor=<?=$GLOBALS["BGColor2"]?>>
					<table cellpadding=5 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>>
								<font color='<?=$GLOBALS["TextColor1"]?>'>
								<b>Details:</b>
								<center>
								<input type='hidden' name='sOldDetails' value="<?=htmlentities($sOldDetails)?>">
								<textarea name='sDetails' cols=78 rows=4 wrap=off><?=htmlentities($sDetails)?></textarea>
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
		Global $iParentUnq;
		Global $aVariables;
		Global $aValues;
		
		?>
		<SCRIPT LANGUAGE=javascript>
		<!--
			
			function ReturnToMain(){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iParentUnq=<?=$iParentUnq?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
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
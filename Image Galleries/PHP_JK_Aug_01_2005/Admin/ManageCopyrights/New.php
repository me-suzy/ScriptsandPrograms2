<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iTtlNumItems	= Trim(Request("iTtlNumItems"));
	$iDBLoc			= Trim(Request("iDBLoc"));
	$sURL			= Trim(Request("sURL"));
	$sCopyright		= Trim(Request("sCopyright"));
	$sDetails		= Trim(Request("sDetails"));
	$sAction		= Trim(Request("sAction"));
	
	WriteScripts();
	
	If (ACCNT_ReturnRights("PHPJK_IG_ADD_CR")) {
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
		Global $sURL;
		Global $sCopyright;
		Global $sDetails;
		Global $sAction;
		
		If ( $sAction == "AddCopyright" )
		{
			If ( ! ACCNT_ReturnRights("PHPJK_IG_ADD_CR") ) {
				$sError = "Please log in with Image Gallery management rights.";
			}Else{
				If ( $sCopyright == "" ) {
					DOMAIN_Message("Please enter the copyright.", "ERROR");
				}Else{
					DB_Insert ("INSERT INTO IGCopyrights (DomainUnq,URL,Copyright,Details) VALUES (1, '" . SQLEncode($sURL) . "', '" . SQLEncode($sCopyright) . "', '" . SQLEncode($sDetails) . "')");
					DOMAIN_Message("Copyright added successfully.", "SUCCESS");
				}
			}
		}
			
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
		Global $sURL;
		Global $sCopyright;
		Global $sDetails;
		
		$sBGColor = $GLOBALS["BGColor1"];
		?>
		<form name='NewCopyright' action='New.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "iDBLoc";
		$aVariables[2] = "iTtlNumItems";
		$aValues[0] = "AddCopyright";
		$aValues[1] = $iDBLoc;
		$aValues[2] = $iTtlNumItems;
		Echo DOMAIN_Link("P");
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr><td><font color='<?=$GLOBALS["PageText"]?>'><font size=+1><b>Add New Copyright</b></font></td></tr>
			<tr>
				<td>
					<table cellpadding=1 cellspacing=0 border=0 width=671><tr><td bgcolor=<?=$GLOBALS["BGColor2"]?>>
					<table cellpadding=0 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>>
								<table cellpadding=10 cellspacing=0 border=0>
									<tr>
										<td><font color='<?=$GLOBALS["TextColor1"]?>'><b>Optional URL</td>
										<td><input type='text' name='sURL' value="<?=htmlentities($sURL)?>" size=40 maxlength=250></td>
									</tr>
									<tr>
										<td><font color='<?=$GLOBALS["TextColor1"]?>'><b>Copright</td>
										<td><input type='text' name='sCopyright' value="<?=htmlentities($sCopyright)?>" size=40 maxlength=250></td>
									</tr>
									<tr>
										<td colspan=2>
											<font color='<?=$GLOBALS["TextColor1"]?>'><b>Optional Details</b><br>
											<textarea cols=78 rows=12 WRAP="soft" NAME="sDetails"><?=$sDetails?></textarea>
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
					<input type='submit' value='Add New Copyright'>
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
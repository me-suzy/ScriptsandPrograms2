<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	If ( (ACCNT_ReturnRights("PHPJK_IG_ADD_CR")) || (ACCNT_ReturnRights("PHPJK_IG_EDIT_CR")) || (ACCNT_ReturnRights("PHPJK_IG_DEL_CR")) ) {
		HeaderHTML();
		Main();
	}Else{
		WriteScripts();
		DOMAIN_Message("You must login with Image Gallery System rights.", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iNumPerPage;
		Global $iTtlNumItems;
		Global $iDBLoc;
		
		$sAction		= Trim(Request("sAction"));
		$sSuccess		= "";
		$sError			= "";

		If ( $sAction == "UpdateCopyright" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_IG_EDIT_CR")) {
				$sError = "Please log in with Image Gallery management rights.";
			}Else{
				ForEach ($_POST as $sTextField=>$sValue)
				{
					If ( strpos($sTextField, "sOldURL") !== false )
					{
						$iCopyUnq		= str_replace("sOldURL", "", $sTextField);
						$sOldURL		= $sValue;
						$sNewURL		= Request("sNewURL" . $iCopyUnq);
						$sOldCopyright	= Request("sOldCopyright" . $iCopyUnq);
						$sNewCopyright	= Request("sNewCopyright" . $iCopyUnq);
						If ( ( $sOldURL != $sNewURL || $sOldCopyright != $sNewCopyright ) )
						{
							DB_Update ("UPDATE IGCopyrights SET URL = '" . SQLEncode($sNewURL) . "', Copyright = '" . SQLEncode($sNewCopyright) . "' WHERE CopyUnq = " . $iCopyUnq);
							If ( $sSuccess == "" )
								$sSuccess = "Successfully updated copyright information.";
						}
					}
				}
			}
		}ElseIf ( $sAction == "DeleteCopyright" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_IG_DEL_CR")) {
				$sError = "Please log in with Image Gallery management rights.";
			}Else{
				ForEach ($_POST as $sCheckbox=>$sValue)
				{
					If ( strpos($sCheckbox, "sDelete") !== false )
					{
						$iCopyUnq		= str_replace("sDelete", "", $sCheckbox);
						DB_Update ("DELETE FROM IGCopyrights WHERE CopyUnq = " . $iCopyUnq);
						DB_Update ("DELETE FROM IGImageCRs WHERE CopyUnq = " . $iCopyUnq);
						If ( $sSuccess == "" )
							$sSuccess = "Successfully deleted copyright information.";
					}
				}
			}
		}

		// Pagination variables -- begin
		$iDBLoc			= 0;
		$iTtlNumItems	= 0;
		$iNumPerPage	= 20;
		If ( isset($_REQUEST["iTtlNumItems"]) )
			$iTtlNumItems = Trim($_REQUEST["iTtlNumItems"]);
		If ( isset($_REQUEST["iDBLoc"]) )
			$iDBLoc = Trim($_REQUEST["iDBLoc"]);
		If ($iDBLoc < 0)
			$iDBLoc = 0;
			
		If ( $sAction == "DeleteCopyright" )
			$iTtlNumItems	= 0;
		
		if ( $iTtlNumItems == 0 ) {
			$sQuery			= "SELECT Count(*) FROM IGCopyrights (NOLOCK)";
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
				$iTtlNumItems = $rsRow[0];
		}
		// Pagination variables -- end		
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		If ( $sSuccess != "" )
			DOMAIN_Message($sSuccess, "SUCCESS");
		
		WriteScripts();
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
		Global $iNumPerPage;
		Global $aValues;
		Global $aVariables;
		
		$sBGColor = $GLOBALS["BGColor2"];
		
		$sQuery = "SELECT * FROM IGCopyrights (NOLOCK) ORDER BY Copyright";
		DB_Query("SET ROWCOUNT " . ($iDBLoc + $iNumPerPage));
		$rsRecordSet = DB_Query($sQuery);
		DB_Query("SET ROWCOUNT 0");
		For ( $x = 1; $x <= $iDBLoc; $x++)
			DB_Fetch($rsRecordSet);
		?>
		<form name='ManageCopyrights' action='index.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "iTtlNumItems";
		$aVariables[2] = "iDBLoc";
		$aValues[0] = "New";
		$aValues[1] = $iTtlNumItems;
		$aValues[2] = $iDBLoc;
		Echo DOMAIN_Link("P");
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Manage Copyrights</b></font>
					<br><br>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>ID</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>URL</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Copright</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>&nbsp;</b></td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Delete</b></td>
						</tr>
						<?php 
						$sColor1 = $GLOBALS["BGColor1"];
						$sColor2 = $GLOBALS["PageBGColor"];
						$sColor3 = $GLOBALS["PageText"];
						$sColor4 = $GLOBALS["TextColor1"];
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor == $sColor1 )
							{
								$sBGColor = $sColor2;
								$sTextColor = $sColor3;
								$sLinkColor = "MediumNavPage";
							}Else{
								$sBGColor = $sColor1;
								$sTextColor = $sColor4;
								$sLinkColor = "MediumNav1";
							}
							$sURL 			= $rsRow["URL"];
							$sCopyright	 	= $rsRow["Copyright"];
							$iCopyUnq 		= $rsRow["CopyUnq"];
							?>
							<tr>
								<td bgcolor=<?=$sBGColor?> align=center valign=top><font color='<?=$sTextColor?>'><?=$iCopyUnq?></td>
								<td bgcolor=<?=$sBGColor?> valign=top><input type='hidden' name="sOldURL<?=$iCopyUnq?>" value="<?=htmlentities($sURL)?>"><input type='text' name="sNewURL<?=$iCopyUnq?>" value="<?=htmlentities($sURL)?>" size=25 maxlength=250></td>
								<td bgcolor=<?=$sBGColor?> valign=top><input type='hidden' name="sOldCopyright<?=$iCopyUnq?>" value="<?=htmlentities($sCopyright)?>"><input type='text' name="sNewCopyright<?=$iCopyUnq?>" value="<?=htmlentities($sCopyright)?>" size=45 maxlength=250></td>
								<td bgcolor=<?=$sBGColor?> align=center valign=top><a href='Edit.php?iCopyUnq=<?=$iCopyUnq?>' class='<?=$sLinkColor?>'>Edit Details</a></td>
								<td align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name="sDelete<?=$iCopyUnq?>" value="<?=$iCopyUnq?>"></td>
							</tr>
							<?php 
						}
						?>
						<tr>
							<td colspan=6 align=right>
								<?php PrintRecordsetNav_ADMIN( "index.php", "", "Galleries" );?>
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
		Global $aValues;
		Global $aVariables;
		
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageCopyrights.sAction.value = sAction;
				document.ManageCopyrights.submit();
			}
			
			function PaginationLink(sQueryString){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>" + sQueryString;
			}
			
			function NewCopyright(){
				document.location = "New.php?<?=DOMAIN_Link("G")?>&iDBLoc=<?=$iDBLoc?>";
			}
			
		</script>
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
				<td colspan=8 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If ( ACCNT_ReturnRights("PHPJK_IG_EDIT_CR") ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/UpdateCopyright.gif' ALIGN='absmiddle' Width=31 Height=44 Border=0 Alt='Save changes to the copyrights.' onClick='SubmitForm(\"UpdateCopyright\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ACCNT_ReturnRights("PHPJK_IG_DEL_CR") ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='delete' SRC='../../Images/Administrative/DelCopyright.gif' ALIGN='absmiddle' Width=27 Height=39 Border=0 Alt='Delete checked copyrights.' onClick='SubmitForm(\"DeleteCopyright\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ACCNT_ReturnRights("PHPJK_IG_ADD_CR") ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:NewCopyright();'><img src='../../Images/Administrative/AddCopyright.gif' Width=22 Height=39 Border=0 Alt='Add a new copyright.'></a></td>";
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
				<td colspan=8 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=10 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
?>
<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$iTtlNumItems	= 0;
	$iDBLoc			= 0;
	
	WriteScripts();
	
	If ( (ACCNT_ReturnRights("PHPJK_IG_ADD_PL")) || (ACCNT_ReturnRights("PHPJK_IG_EDIT_PL")) || (ACCNT_ReturnRights("PHPJK_IG_DEL_PL")) ) {
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
		Global $iNumPerPage;
		
		$sError			= "";
		$sSuccess		= "";
		$sAction		= Trim(Request("sAction"));

		If ( $sAction == "UpdatePL" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_IG_EDIT_PL")) {
				$sError = "Please log in with Image Gallery management rights.";
			}Else{
				ForEach ($_POST as $sTextField=>$sValue)
				{
					If ( strpos($sTextField, "sOldName") !== false )
					{
						$iPLUnq		= str_replace("sOldName", "", $sTextField);
						$sOldName	= $sValue;
						$sNewName	= Request("sNewName" . $iPLUnq);
						If ( $sOldName != $sNewName )
						{
							DB_Update ("UPDATE IGPLs SET Name = '" . SQLEncode($sNewName) . "' WHERE PLUnq = " . $iPLUnq);
							If ( $sSuccess == "" )
								$sSuccess = "Successfully updated Purchase List name.";
						}
					}
				}
			}
		}ElseIf ( $sAction == "DeletePL" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_IG_DEL_PL")) {
				$sError = "Please log in with Image Gallery management rights.";
			}Else{
				ForEach ($_POST as $sCheckbox=>$sValue)
				{
					If ( strpos($sCheckbox, "sDelete") !== false )
					{
						$iPLUnq		= str_replace("sDelete", "", $sCheckbox);
						DB_Update ("DELETE FROM IGPLs WHERE PLUnq = " . $iPLUnq);
						DB_Update ("DELETE FROM IGImageProds WHERE PLUnq = " . $iPLUnq);
						If ( $sSuccess == "" )
							$sSuccess = "Successfully deleted Purchase List.";
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
			
		If ( $sAction == "DeletePL" )
			$iTtlNumItems	= 0;
		
		if ( $iTtlNumItems == 0 ) {
			$sQuery			= "SELECT Count(*) FROM IGPLs (NOLOCK)";
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
				$iTtlNumItems = $rsRow[0];
		}
		// Pagination variables -- end		
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		If ( $sSuccess != "" )
			DOMAIN_Message($sSuccess, "SUCCESS");
		
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
		
		$sQuery = "SELECT * FROM IGPLs (NOLOCK) ORDER BY Name";
		DB_Query("SET ROWCOUNT " . ($iDBLoc + $iNumPerPage));
		$rsRecordSet = DB_Query($sQuery);
		DB_Query("SET ROWCOUNT 0");
		For ( $x = 1; $x <= $iDBLoc; $x++)
			DB_Fetch($rsRecordSet);
		?>
		<form name='ManagePLs' action='index.php' method='post'>
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
					<font size=+1><b>Manage Purchase Lists (PLs)</b></font>
					<br><br>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>ID</b></td>
							<td bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Name</b></td>
							<td bgcolor=<?=$sBGColor?>>&nbsp;</td>
							<td bgcolor=<?=$sBGColor?>>&nbsp;</td>
							<td align=center bgcolor=<?=$sBGColor?>><font color='<?=$GLOBALS["TextColor2"]?>'><b>Delete</b></td>
						</tr>
						<?php 
						$sColor1 = $GLOBALS["BGColor1"];
						$sColor2 = $GLOBALS["PageBGColor"];
						$sColor3 = $GLOBALS["PageText"];
						$sColor4 = $GLOBALS["TextColor1"];
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor == $sColor1 ) {
								$sBGColor = $sColor2;
								$sTextColor = $sColor3;
								$sLinkColor = "MediumNavPage";
							}Else{
								$sBGColor = $sColor1;
								$sTextColor = $sColor4;
								$sLinkColor = "MediumNav1";
							}
							$sName 		= $rsRow["Name"];
							$iPLUnq 	= $rsRow["PLUnq"];
							?>
							<tr>
								<td bgcolor=<?=$sBGColor?> align=center valign=top><font color='<?=$sTextColor?>'><?=$iPLUnq?></td>
								<td bgcolor=<?=$sBGColor?> valign=top><input type='hidden' name="sOldName<?=$iPLUnq?>" value="<?=htmlentities($sName)?>"><input type='text' name="sNewName<?=$iPLUnq?>" value="<?=htmlentities($sName)?>" size=25 maxlength=250></td>
								<td bgcolor=<?=$sBGColor?> valign=top><a href='TestPLResults.php?iPLUnq=<?=$iPLUnq?>' class='<?=$sLinkColor?>'>View PL Results</a></td>
								<td bgcolor=<?=$sBGColor?> valign=top><a href='EditPL.php?iPLUnq=<?=$iPLUnq?>' class='<?=$sLinkColor?>'>Edit PL</a></td>
								<td align=center bgcolor=<?=$sBGColor?>><input type='checkbox' name="sDelete<?=$iPLUnq?>" value="<?=$iPLUnq?>"></td>
							</tr>
							<?php 
						}
						?>
						<tr>
							<td colspan=5 align=right>
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
				document.ManagePLs.sAction.value = sAction;
				document.ManagePLs.submit();
			}
	
			function PaginationLink(sQueryString){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>" + sQueryString;
			}
			
			function NewPL(){
				document.location = "New.php?<?=DOMAIN_Link("G")?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
			}
			
			function ManageNotInPL(){
				document.location = "ManageProdsNotInPL.php?<?=DOMAIN_Link("G")?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
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
				<td colspan=10 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If ( ACCNT_ReturnRights("PHPJK_IG_EDIT_PL") ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/UpdatePL.gif' ALIGN='absmiddle' Width=31 Height=44 Border=0 Alt='Save changes to Purchase List names.' onClick='SubmitForm(\"UpdatePL\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ACCNT_ReturnRights("PHPJK_IG_DEL_PL") ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='delete' SRC='../../Images/Administrative/DelPL.gif' ALIGN='absmiddle' Width=27 Height=39 Border=0 Alt='Delete checked Purchase Lists.' onClick='SubmitForm(\"DeletePL\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ACCNT_ReturnRights("PHPJK_IG_ADD_PL") ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:NewPL();'><img src='../../Images/Administrative/AddPL.gif' Width=22 Height=39 Border=0 Alt='Create a new Purchase List.'></a></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				Echo "<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>";
				If ( ACCNT_ReturnRights("PHPJK_IG_ADD_PROD_2IMAGES") ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:ManageNotInPL();'><img src='../../Images/Administrative/NotInPL.gif' Width=41 Height=43 Border=0 Alt='Manage products not in Purchase Lists (PL).'></a></td>";
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
				<td colspan=10 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=12 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
?>
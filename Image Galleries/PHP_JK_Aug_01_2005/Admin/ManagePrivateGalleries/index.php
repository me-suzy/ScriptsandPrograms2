<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	If ( (ACCNT_ReturnRights("PHPJK_IG_PRIVATE")) || (ACCNT_ReturnRights("PHPJK_IG_EDIT_CAT")) ) {
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
		Global $iGalleryUnq;
		Global $sAccountUnq;
		
		$sAccountUnq	= Trim(Request("sAccountUnq"));
		$sAction		= Request("sAction");
		$iGalleryUnq	= Trim(Request("iGalleryUnq"));
		$sError			= "";
		$sSuccess		= "";
		
		If ( $iGalleryUnq == "" )
		{
			$iGalleryUnq = "-1";
			$sQuery			= "SELECT GalleryUnq, Name FROM Galleries (NOLOCK) ORDER BY Name";
			DB_Query("SET ROWCOUNT 1");
			$rsRecordSet	= DB_Query($sQuery);
			DB_Query("SET ROWCOUNT 0");
			If ( DB_NumRows($rsRecordSet) > 0 )
			{
				While ( $rsRow = DB_Fetch($rsRecordSet) )
				{
					// make sure the current user has rights to admin whichever gallery we assign the galleryunq)
					$iGalleryUnq = $rsRow["GalleryUnq"];
					If ( RightsToGallery($iGalleryUnq) ) {
						break;
					}
				}
			}
		}

		If ( $sAction == "SetAccounts" ) {
			If ( $iGalleryUnq != "-1" )
			{
				If ( RightsToGallery($iGalleryUnq) ) 
				{
					$bOldAllUsers	= Trim(Request("bOldAllUsers"));
					$bAllUsers		= Trim(Request("bAllUsers"));
					If ( $bAllUsers != $bOldAllUsers )
					{
						// delete it regardless, in case they already submitted this form
						DB_Update ("DELETE FROM PrivateAccounts WHERE AccountUnq = -1 AND GalleryUnq = " . $iGalleryUnq);
						If ( $bAllUsers == "Y" ) {
							DB_Insert ("INSERT INTO PrivateAccounts VALUES (" . $iGalleryUnq . ", -1)");
							DOMAIN_Message("Successfully made the gallery viewable by all users.", "SUCCESS");
						}Else{
							DOMAIN_Message("The gallery is now viewable only by selected users.", "SUCCESS");
						}
					}
					ForEach ($_POST as $sRadio=>$sValue)
					{
						If ( strpos($sRadio, "sAccountUnq") !== false )
						{
							$sAccountUnq	= $sValue;
							$sOldViewable	= Trim(Request("sOldViewable" . $sAccountUnq));
							$sViewable		= Trim(Request("sViewable" . $sAccountUnq));
							If ( $sOldViewable != $sViewable )
							{
								// delete it regardless, in case they already submitted the form
								DB_Update ("DELETE FROM PrivateAccounts WHERE AccountUnq = " . $sAccountUnq . " AND GalleryUnq = " . $iGalleryUnq);
								If ( $sViewable == "Y" )
									DB_Insert ("INSERT INTO PrivateAccounts VALUES (" . $iGalleryUnq . ", " . $sAccountUnq . ")");
								If ( $sSuccess == "" )
									$sSuccess = "Update successful.";
							}
		            	}
		            }
				}
			}
		}
		
		// Pagination variables -- begin
		$iDBLoc			= 0;
		$iTtlNumItems	= 0;
		$iNumPerPage	= 40;
		If ( isset($_REQUEST["iTtlNumItems"]) )
			$iTtlNumItems = Trim($_REQUEST["iTtlNumItems"]);
		If ( isset($_REQUEST["iDBLoc"]) )
			$iDBLoc = Trim($_REQUEST["iDBLoc"]);
		If ($iDBLoc < 0)
			$iDBLoc = 0;
			
		If ( $sAction == "UpdateGalleryUnq" )
			$iDBLoc = 0;
			
		if ( $iTtlNumItems == 0 ) {
			$sQuery			= "SELECT DISTINCT COUNT(*) FROM Accounts A (NOLOCK) LEFT OUTER JOIN AccountDomainMap M (NOLOCK) ON A.AccountUnq = M.AccountUnq";
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
		If ( $iGalleryUnq != "-1" ) {	// so if there are no galleries, nothing is displayed
			WriteForm();
		}Else{
			DOMAIN_Message("Please create an image gallery first.", "ERROR");
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
		Global $iNumPerPage;
		Global $iDBLoc;
		Global $iTtlNumItems;
		Global $aVariables;
		Global $aValues;
		Global $iGalleryUnq;
		Global $sAccountUnq;
		
		$sBGColor = $GLOBALS["BGColor2"];
		
		$sQuery = "SELECT DISTINCT A.AccountUnq, A.Login FROM Accounts A (NOLOCK) LEFT OUTER JOIN AccountDomainMap M (NOLOCK) ON A.AccountUnq = M.AccountUnq ORDER BY A.Login";
		DB_Query("SET ROWCOUNT " . ($iDBLoc + $iNumPerPage));
		$rsRecordSet = DB_Query($sQuery);
		DB_Query("SET ROWCOUNT 0");
		For ( $x = 1; $x <= $iDBLoc; $x++)
			DB_Fetch($rsRecordSet);
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManagePrivateGalleries.sAction.value = sAction;
				document.ManagePrivateGalleries.submit();
			}
			
		</script>
		<form name='ManagePrivateGalleries' action='index.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aVariables[1] = "iTtlNumItems";
		$aVariables[2] = "iDBLoc";
		$aValues[0] = "New";
		$aValues[1] = $iTtlNumItems;
		$aValues[2] = $iDBLoc;
		Echo DOMAIN_Link("P");
		
		DOMAIN_Link_Clear();
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Manage Private Galleries</b></font>
					<br>
					<font color='<?=$GLOBALS["PageText"]?>'>

					<br>
					<table cellpadding=2 cellspacing=0 border=0 width=671>
						<tr>
							<td>
								<font color='<?=$GLOBALS["PageText"]?>'>
								Image Gallery
								<?php 
									Echo "<select name='iGalleryUnq' onChange='SubmitForm(\"UpdateGalleryUnq\");'>";
									$sQuery			= "SELECT GalleryUnq, Name FROM Galleries (NOLOCK) ORDER BY Name";
									$rsRecordSet2	= DB_Query($sQuery);
									If ( DB_NumRows($rsRecordSet2) > 0 )
									{
										$bTemp = FALSE;
										While ( $rsRow2 = DB_Fetch($rsRecordSet2) )
										{
											$iTempGalleryUnq = $rsRow2["GalleryUnq"];
											If ( RightsToGallery($iTempGalleryUnq) )
											{
												$bTemp = TRUE;
												If ( Trim($iGalleryUnq) == Trim($iTempGalleryUnq) ) {
													Echo "<option value='" . $iTempGalleryUnq . "' Selected>" . $rsRow2["Name"] . "</option>";
												}Else{
													Echo "<option value='" . $iTempGalleryUnq . "'>" . $rsRow2["Name"] . "</option>";
												}
											}
										}
										If ( ! $bTemp )
											Echo "<option value=''>No Galleries</option>";
									}Else{
										Echo "<option value=''>No Galleries</option>";
									}
									Echo "</select>";
								?>
							</td>
							<?php 
							$sQuery			= "SELECT AccountUnq FROM PrivateAccounts (NOLOCK) WHERE AccountUnq = -1 AND GalleryUnq = " . $iGalleryUnq;
							$rsRecordSet2	= DB_Query($sQuery);
							If ( DB_NumRows($rsRecordSet2) > 0 ) {
								$bAllUsers = "Y";
							}Else{
								$bAllUsers = "N";
							}
							?>
							<td align=right>
								<font color='<?=$GLOBALS["PageText"]?>'>
								Gallery images are viewable by all users: 
								Yes <input type='radio' name='bAllUsers' value='Y'<?php If ( Trim($bAllUsers) == "Y" )  Echo " checked";?>>
								No <input type='radio' name='bAllUsers' value='N'<?php If ( Trim($bAllUsers) == "N" )  Echo " checked";?>>
								<input type='hidden' name='bOldAllUsers' value='<?=$bAllUsers?>'>
								<br>
								<font size=-2>If this is "Yes", settings made below are overridden.
							</td>
						</tr>
					</table>
					<br><br>
					<table cellpadding=0 cellspacing=0 border=0 width=671>
						<tr>
							<td bgcolor=<?=$sBGColor?>><img src='../../Images/Blank.gif' Width=5 Height=15><font color='<?=$GLOBALS["TextColor2"]?>'><b>Login</b></td>
							<td bgcolor=<?=$sBGColor?> align=center><font color='<?=$GLOBALS["TextColor2"]?>'><b>Can see gallery images?</b></td>
						</tr>
						<tr><td colspan=3 bgcolor=<?=$sBGColor?>><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
						<?php 
						While ( $rsRow = DB_Fetch($rsRecordSet) )
						{
							If ( $sBGColor = $GLOBALS["BGColor1"] ) {
								$sBGColor = $GLOBALS["PageBGColor"];
							}Else{
								$sBGColor = $GLOBALS["BGColor1"];
							}
							$sLogin			= $rsRow["Login"];
							$sAccountUnq	= $rsRow["AccountUnq"];
							
							$sQuery			= "SELECT * FROM PrivateAccounts (NOLOCK) WHERE AccountUnq = " . $sAccountUnq . " AND GalleryUnq = " . $iGalleryUnq;
							$rsRecordSet2	= DB_Query($sQuery);
							If ( DB_NumRows($rsRecordSet2) > 0 ) {
								$bCanView = TRUE;
							}Else{
								$bCanView = FALSE;
							}
							?>
							<tr><td colspan=3 bgcolor=<?=$sBGColor?>><img src='../../Images/Blank.gif' Width=1 Height=2></td></tr>
							<tr>
								<td bgcolor=<?=$sBGColor?>><img src='../../Images/Blank.gif' Width=5 Height=5><font color='<?=$GLOBALS["TextColor1"]?>'><?=$sLogin?></td>
								<td bgcolor=<?=$sBGColor?> align=center>
									Yes <input type='radio' name='sViewable<?=$sAccountUnq?>' value='Y'<?php If ( $bCanView )  Echo " checked";?>>
									<img src='../../Images/Blank.gif' Width=15 Height=10>
									No <input type='radio' name='sViewable<?=$sAccountUnq?>' value='N'<?php If ( ! $bCanView )  Echo " checked";?>>
									<input type='hidden' name='sOldViewable<?=$sAccountUnq?>' value='<?php If ( $bCanView ) { Echo "Y"; }Else{ Echo "N"; }?>'>
									<input type='hidden' name='sAccountUnq<?=$sAccountUnq?>' value='<?=$sAccountUnq?>'>
								</td>
							</tr>
							<tr><td colspan=3 bgcolor=<?=$sBGColor?>><img src='../../Images/Blank.gif' Width=1 Height=2></td></tr>
							<?php 
						}
						?>
						<tr>
							<td colspan=3 align=right>
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
	//********************************************************************************

	
	//********************************************************************************
	//*																				*
	//*	Returns TRUE if the current user has rights to change the users who can		*
	//*		view this galleries images.												*
	//*																				*
	//********************************************************************************
	Function RightsToGallery($iTempGalleryUnq)
	{
		Global $bHasAccount;
		Global $iLoginAccountUnq;
		
		If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) {
			Return True;
		}Else{
			If ( $bHasAccount )
			{
				$sQuery			= "SELECT AccountUnq FROM Galleries (NOLOCK) Where AccountUnq = " . $iLoginAccountUnq . " AND GalleryUnq = " . $iTempGalleryUnq;
				$rsRecordSet	= DB_Query($sQuery);
				If ( DB_NumRows($rsRecordSet) > 0 )
					Return True;
			}
		}
		Return False;
	}
	//********************************************************************************
	
	
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
				<td colspan=4 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") || ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/UpdateUsers.gif' ALIGN='absmiddle' Width=31 Height=44 Border=0 Alt='Save all changes' onClick='SubmitForm(\"SetAccounts\")'></td>";
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
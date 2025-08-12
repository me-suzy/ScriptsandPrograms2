<?php
	Require("../Includes/i_Includes.php");
	Require("../Includes/Config/i_SendECard.php");
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	Require("i_ECard.php");
	Main();	
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iImageUnq;
		Global $sSenderName;
		Global $sSenderEmail;
		Global $iLoginAccountUnq;
		Global $iGalleryUnq;
		Global $sSort;
		Global $iCategoryUnq;
		Global $iImageUnq;
		Global $iCardUnq;
		Global $sTitle;
		Global $sMessage;
		Global $sTextColor;
		Global $sBorderColor;
		Global $sBGColor;
		Global $sTFont;
		Global $sMFont;
		Global $sREmail;
		Global $sRName;
		Global $sSenderEmail;
		Global $sSenderName;
	
		$iGalleryUnq	= Trim(Request("iGalleryUnq"));
		$sSort			= Trim(Request("sSort"));
		$iTtlNumItems	= Request("iTtlNumItems");
		$iCategoryUnq	= Trim(Request("iCategoryUnq"));
		$iNumPerPage	= Trim(Request("iNumPerPage"));
		$iDBLoc			= Trim(Request("iDBLoc"));
		$sAction		= Trim(Request("sAction"));
		$iImageUnq		= Trim(Request("iImageUnq"));
		$sBGColor		= Trim(Request("sBGColor"));
		$sBorderColor	= Trim(Request("sBorderColor"));
		$sTextColor		= Trim(Request("sTextColor"));
		$iCardUnq		= Trim(Request("iCardUnq"));
		$sREmail		= Trim(Request("sREmail"));
		$sRName			= Trim(Request("sRName"));
		$sSenderEmail	= Trim(Request("sSenderEmail"));
		$sSenderName	= Trim(Request("sSenderName"));
		$sTitle			= Trim(Request("sTitle"));
		$sMessage		= Trim(Request("sMessage"));
		$sTFont			= Trim(Request("sTFont"));
		$sMFont			= Trim(Request("sMFont"));
		$sError			= "";
		
		If ( $sBGColor == "" )
			$sBGColor = "FFFFFF";
		If ( $sBorderColor == "" )
			$sBorderColor = "000000";
		If ( $sTextColor == "" )
			$sTextColor = "000000";
		If ( $iImageUnq == "" )
			$iImageUnq = "0";
		If ( $sTFont == "" )
			$sTFont = "Arial";
		If ( $sMFont == "" )
			$sMFont = "Arial";

		If ( $sAction == "Preview" ) {
			If ( $sREmail == "" )
				$sError = "Please enter the recipient email.<br>";
			If ( $sRName == "" )
				$sError = $sError . "Please enter the recipient name.<br>";
			If ( $sError == "" )
			{
				$sMessage = str_replace("\n", "<BR>", $sMessage);
				If ( $iCardUnq == "" )
				{
					// insert card w/ preview flag
					DB_Insert ("INSERT INTO IGECards (DomainUnq,ImageUnq,DateSent,Title,Message,SenderName,SenderEmail,BGColor,BorderColor,TextColor,TFont,MFont,REmail,RName,Preview,GalleryUnq) VALUES (1, " . $iImageUnq . ", GetDate(), '" . SQLEncode($sTitle) . "', '" . SQLEncode($sMessage) . "', '" . SQLEncode($sSenderName) . "', '" . SQLEncode($sSenderEmail) . "', '" . SQLEncode($sBGColor) . "', '" . SQLEncode($sBorderColor) . "', '" . SQLEncode($sTextColor) . "', '" . SQLEncode($sTFont) . "', '" . SQLEncode($sMFont) . "', '" . SQLEncode($sREmail) . "', '" . SQLEncode($sRName) . "', 'Y', " . $iGalleryUnq . ")");

					$rsRecordSet = DB_Query("SELECT @@IDENTITY");
					If ( $rsRow = DB_Fetch($rsRecordSet) ) {
						header( 'location:Preview.php?iCardUnq='  . $rsRow[0] );
						ob_flush();
						exit;
					}Else{
						DOMAIN_Message("Unable to save your E-Card in the database! Please alert the webmaster.", "ERROR");
					}
				}Else{
					// it's already in the database -- just update it
					DB_Update ("UPDATE IGECards SET Title = '" . SQLEncode($sTitle) . "', Message = '" . SQLEncode($sMessage) . "', SenderName = '" . SQLEncode($sSenderName) . "', SenderEmail = '" . SQLEncode($sSenderEmail) . "', BGColor = '" . SQLEncode($sBGColor) . "', BorderColor = '" . SQLEncode($sBorderColor) . "', TextColor = '" . SQLEncode($sTextColor) . "', TFont = '" . SQLEncode($sTFont) . "', MFont = '" . SQLEncode($sMFont) . "', REmail = '" . SQLEncode($sREmail) . "', RName = '" . SQLEncode($sRName) . "' WHERE CardUnq = " . $iCardUnq);
					header( 'location:Preview.php?iCardUnq='  . $iCardUnq );
					ob_flush();
					exit;
				}
			}Else{
				DOMAIN_Message($sError, "ERROR");
				WriteForm();
			}
		}ElseIf ( $sAction == "Edit" ) {
			If ( $iCardUnq == "" ) {
				DOMAIN_Message("Unable to edit card. No card number was found!", "ERROR");
			}Else{
				// load all the data for the card from the db
				$sQuery			= "SELECT * FROM IGECards (NOLOCK) WHERE CardUnq = " . $iCardUnq;
				$rsRecordSet	= DB_Query($sQuery);
				If ( $rsRow = DB_Fetch($rsRecordSet) ) {
					$sBGColor		= Trim($rsRow["BGColor"]);
					$sBorderColor	= Trim($rsRow["BorderColor"]);
					$sTextColor		= Trim($rsRow["TextColor"]);
					$iImageUnq		= Trim($rsRow["ImageUnq"]);
					$sREmail		= Trim($rsRow["REmail"]);
					$sRName			= Trim($rsRow["RName"]);
					$sSenderEmail	= Trim($rsRow["SenderEmail"]);
					$sSenderName	= Trim($rsRow["SenderName"]);
					$sTitle			= Trim($rsRow["Title"]);
					$sMessage		= Trim($rsRow["Message"]);
					$sTFont			= Trim($rsRow["TFont"]);
					$sMFont			= Trim($rsRow["MFont"]);
					$sMessage		= str_replace("<BR>", "\n", $sMessage);
					
					WriteForm();
				}Else{
					DOMAIN_Message("Unable to edit card. The card was not found in the database!", "ERROR");
				}
			}
		}ElseIf ( $sAction == "Send" ) {
			If ( $sREmail == "" )
				$sError = "Please enter the recipient email.<br>";
			If ( $sRName == "" )
				$sError = $sError . "Please enter the recipient name.<br>";
			If ( $sError == "" )
			{
				$sMessage = str_replace("\n", "<BR>", $sMessage);
				If ( $iCardUnq == "" ) 	// they never previewed it, so insert it for the 1st time
				{
					// insert into database w/o preview flag set
					DB_Insert ("INSERT INTO IGECards (DomainUnq,ImageUnq,DateSent,Title,Message,SenderName,SenderEmail,BGColor,BorderColor,TextColor,TFont,MFont,REmail,RName,Preview,GalleryUnq) VALUES (1, " . $iImageUnq . ", GetDate(), '" . SQLEncode($sTitle) . "', '" . SQLEncode($sMessage) . "', '" . SQLEncode($sSenderName) . "', '" . SQLEncode($sSenderEmail) . "', '" . SQLEncode($sBGColor) . "', '" . SQLEncode($sBorderColor) . "', '" . SQLEncode($sTextColor) . "', '" . SQLEncode($sTFont) . "', '" . SQLEncode($sMFont) . "', '" . SQLEncode($sREmail) . "', '" . SQLEncode($sRName) . "', 'N', " . $iGalleryUnq . ")");
					
					$rsRecordSet = DB_Query("SELECT @@IDENTITY");
					If ( $rsRow = DB_Fetch($rsRecordSet) ) {
						$iCardUnq = $rsRow[0];
					}Else{
						$sError = "Unable to save your E-Card in the database! Please alert the webmaster.";
					}
				}Else{
					// it's already in the database -- just update it
					DB_Update ("UPDATE IGECards SET Title = '" . SQLEncode($sTitle) . "', Message = '" . SQLEncode($sMessage) . "', SenderName = '" . SQLEncode($sSenderName) . "', SenderEmail = '" . SQLEncode($sSenderEmail) . "', BGColor = '" . SQLEncode($sBGColor) . "', BorderColor = '" . SQLEncode($sBorderColor) . "', TextColor = '" . SQLEncode($sTextColor) . "', TFont = '" . SQLEncode($sTFont) . "', MFont = '" . SQLEncode($sMFont) . "', REmail = '" . SQLEncode($sREmail) . "', RName = '" . SQLEncode($sRName) . "' WHERE CardUnq = " . $iCardUnq);
				}
				If ( $sError == "" )
				{
					// send email
					If ( Send_Card($iCardUnq) )
					{
						header( 'location:ThankYou.php?iCardUnq='  . $iCardUnq );
						ob_flush();
						exit;
					}
				}Else{
					DOMAIN_Message($sError, "ERROR");
					WriteForm();
				}
			}Else{
				DOMAIN_Message($sError, "ERROR");
				WriteForm();
			}
		}ElseIf ( $sAction == "SendFromPreview" ) {
			// since coming from a preview, don't check the form fields for anyting, dont update any db, dont insert.
			If ( Send_Card($iCardUnq) ) {
				header( 'location:ThankYou.php?iCardUnq='  . $iCardUnq );
				ob_flush();
				exit;
			}
		}Else{
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
		Global $sSenderName;
		Global $sSenderEmail;
		Global $iLoginAccountUnq;
		Global $aVariables;
		Global $aValues;
		Global $iDBLoc;
		Global $iGalleryUnq;
		Global $sSort;
		Global $iTtlNumItems;
		Global $iCategoryUnq;
		Global $iNumPerPage;
		Global $iImageUnq;
		Global $iCardUnq;
		Global $iTableWidth;
		Global $sTitle;
		Global $iFormWidth;
		Global $sMessage;
		Global $sTextColor;
		Global $sBorderColor;
		Global $sBGColor;
		Global $sTFont;
		Global $sMFont;
		Global $sREmail;
		Global $sRName;
		Global $sSenderEmail;
		Global $sSenderName;
		Global $iFormColumns;
		Global $sSiteURL;
		
		$bUseAlpha		= Trim(strtoupper(DOMAIN_Conf("IMAGEGALLERY_USEALPHA")));
		$sVisibility	= "OVERRIDE";
		
		If ( $sSenderName == "" )
			$sSenderName = ACCNT_ReturnADV("PHPJK_FirstName", "V", $iLoginAccountUnq, 0, $sVisibility) . " " . ACCNT_ReturnADV("PHPJK_LastName", "V", $iLoginAccountUnq, 0, $sVisibility);
		If ( $sSenderEmail == "" )
			$sSenderEmail = ACCNT_ReturnADV("PHPJK_EmailAddress", "V", $iLoginAccountUnq, 0, $sVisibility);
		?>
		<script language = "javascript">

			function IG_Popup(x, y, iImageUnq, iGalleryUnq, sAccountUnq, bResize){
				var leftprop, topprop, screenX, screenY, cursorX, cursorY, wWindow;
				
				if(navigator.appName == "Microsoft Internet Explorer") {
					screenY = document.body.offsetHeight;
					screenX = window.screen.availWidth;
				}else{
					screenY = window.outerHeight
					screenX = window.outerWidth
				}
		
				leftvar = (screenX - x) / 2;
				rightvar = (screenY - y) / 2;
				if(navigator.appName == "Microsoft Internet Explorer") {
					leftprop = leftvar;
					topprop = rightvar;
				}else{
					leftprop = (leftvar - pageXOffset);
					topprop = (rightvar - pageYOffset);
		   		}
			
				if (bResize == 1) {
					window.open("<?=$sSiteURL?>/IG_Popup.php?sAccountUnq=" + sAccountUnq + "&iGalleryUnq=" + iGalleryUnq + "&iImageUnq=" + iImageUnq,"","scrollbars,resizable=yes");
				}else{
					window.open("<?=$sSiteURL?>/IG_Popup.php?sAccountUnq=" + sAccountUnq + "&iGalleryUnq=" + iGalleryUnq + "&iImageUnq=" + iImageUnq,"","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=auto,resizable=no,width=" + x + ",height=" + y + ", left=" + leftprop + ", top=" + topprop);
				}
			}
			
			<?php If ( $bUseAlpha == "YES" ) {?>
			function UseAlpha(cur,which){
				if (which==0)
					cur.filters.alpha.opacity=100
				else
					cur.filters.alpha.opacity=85
			}
			<?php }?>

		</script>
		<form name='SendECard' action='index.php' method='post' class='PageForm'>
		<?php 
		$aVariables[0] = "iDBLoc";
		$aVariables[1] = "iGalleryUnq";
		$aVariables[2] = "sSort";
		$aVariables[3] = "iTtlNumItems";
		$aVariables[4] = "iCategoryUnq";
		$aVariables[5] = "iNumPerPage";
		$aVariables[6] = "iImageUnq";
		$aVariables[7] = "iCardUnq";
		$aVariables[8] = "sAction";
		$aValues[0] = $iDBLoc;
		$aValues[1] = $iGalleryUnq;
		$aValues[2] = $sSort;
		$aValues[3] = $iTtlNumItems;
		$aValues[4] = $iCategoryUnq;
		$aValues[5] = $iNumPerPage;
		$aValues[6] = $iImageUnq;
		$aValues[7] = $iCardUnq;
		$aValues[8] = "Preview";
		
		Echo DOMAIN_Link("P");
		Echo "<br>";
		G_STRUCTURE_HeaderBar_ReallySpecific("SendECardHead.gif", "", "", "/PHPJK/", "Galleries");
		Echo "<table width=" . $iTableWidth . " cellpadding = 1 cellspacing = 0 border = 0 class='TablePage_Boxed'>\n";
		?>
		<tr>
			<td valign=top align=center>

			</td>
		</tr>
		</table>
		<?php G_STRUCTURE_SubHeaderBar("ECardLayoutSubHead.gif", "", "", "Galleries");?>
		<table width=<?=$iTableWidth?> cellpadding = 10 cellspacing = 0 border = 0 class='TablePage_Boxed'>
		<tr>
			<td valign=top>
				<table cellpadding = 10 cellspacing=0 border=0 class='TablePage' width=100%>
					<tr>
						<td>
							<b>Please Enter Your Message:<br>
							<TEXTAREA COLS=<?=$iFormColumns?> ROWS=10 WRAP="soft" NAME="sMessage"><?=htmlentities($sMessage)?></TEXTAREA>
						</td>
						<td align=right valign=center>
							<?php 
							$sQuery			= "SELECT I.ImageUnq, I.AltTag, I.Image, I.Thumbnail, I.ImageSize, I.ImageNum, I.FileType, I.Title, G.GalleryUnq, G.AccountUnq, IG.PrimaryG, IG.PrimaryD FROM Images I (NOLOCK), Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE I.ImageUnq = " . $iImageUnq . " AND I.ImageUnq = IG.ImageUnq AND IG.GalleryUnq = G.GalleryUnq";
							$rsRecordSet	= DB_Query($sQuery);
							If ( $rsRow = DB_Fetch($rsRecordSet) ) {
								DispThumb( $iImageUnq, $rsRow["GalleryUnq"], $rsRow["AccountUnq"], Trim($rsRow["Thumbnail"]), Trim($rsRow["AltTag"]), $rsRow["ImageNum"], Trim($rsRow["ImageSize"]), Trim($rsRow["Image"]), Trim($rsRow["FileType"]), Trim($rsRow["Title"]), $rsRow["PrimaryG"], $rsRow["PrimaryD"] );
							}Else{
								Echo "Image Not Available.";
							}
							?>
						</td>
					</tr>
				</table>
				<table cellpadding = 3 cellspacing=0 border=0 class='TablePage'>
					<tr>
						<td><b>Background Color:</td>
						<td>
							<table>
								<tr>
									<td bgcolor=000000>&nbsp</td>
									<td bgcolor=FFFFFF>&nbsp</td>
									<td bgcolor=FF0000>&nbsp</td>
									<td bgcolor=00FF00>&nbsp</td>
									<td bgcolor=0000FF>&nbsp</td>
									<td bgcolor=FFFF00>&nbsp</td>
									<td bgcolor=00FFFF>&nbsp</td>
									<td bgcolor=FF00FF>&nbsp</td>
									<td bgcolor=990000>&nbsp</td>
									<td bgcolor=009900>&nbsp</td>
									<td bgcolor=000099>&nbsp</td>
									<td bgcolor=999900>&nbsp</td>
									<td bgcolor=009999>&nbsp</td>
									<td bgcolor=990099>&nbsp</td>
									<td bgcolor=660000>&nbsp</td>
									<td bgcolor=006600>&nbsp</td>
									<td bgcolor=000066>&nbsp</td>
									<td bgcolor=666600>&nbsp</td>
									<td bgcolor=006666>&nbsp</td>
									<td bgcolor=660066>&nbsp</td>
								</tr>
								<tr>
									<td><input type='radio' name='sBGColor' value='000000' <?php If ($sBGColor == "000000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='FFFFFF' <?php If ($sBGColor == "FFFFFF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='FF0000' <?php If ($sBGColor == "FF0000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='00FF00' <?php If ($sBGColor == "00FF00") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='0000FF' <?php If ($sBGColor == "0000FF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='FFFF00' <?php If ($sBGColor == "FFFF00") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='00FFFF' <?php If ($sBGColor == "00FFFF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='FF00FF' <?php If ($sBGColor == "FF00FF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='990000' <?php If ($sBGColor == "990000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='009900' <?php If ($sBGColor == "009900") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='000099' <?php If ($sBGColor == "000099") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='999900' <?php If ($sBGColor == "999900") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='009999' <?php If ($sBGColor == "009999") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='990099' <?php If ($sBGColor == "990099") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='660000' <?php If ($sBGColor == "660000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='006600' <?php If ($sBGColor == "006600") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='000066' <?php If ($sBGColor == "000066") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='666600' <?php If ($sBGColor == "666600") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='006666' <?php If ($sBGColor == "006666") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBGColor' value='660066' <?php If ($sBGColor == "660066") Echo "checked"; ?> style='border: 0px;'></td>
								</tr>
							</table>
						</tD>
					</tr>
					<tr>
						<td><b>Border Color:</td>
						<td>
							<table>
								<tr>
									<td bgcolor=000000>&nbsp</td>
									<td bgcolor=FFFFFF>&nbsp</td>
									<td bgcolor=FF0000>&nbsp</td>
									<td bgcolor=00FF00>&nbsp</td>
									<td bgcolor=0000FF>&nbsp</td>
									<td bgcolor=FFFF00>&nbsp</td>
									<td bgcolor=00FFFF>&nbsp</td>
									<td bgcolor=FF00FF>&nbsp</td>
									<td bgcolor=990000>&nbsp</td>
									<td bgcolor=009900>&nbsp</td>
									<td bgcolor=000099>&nbsp</td>
									<td bgcolor=999900>&nbsp</td>
									<td bgcolor=009999>&nbsp</td>
									<td bgcolor=990099>&nbsp</td>
									<td bgcolor=660000>&nbsp</td>
									<td bgcolor=006600>&nbsp</td>
									<td bgcolor=000066>&nbsp</td>
									<td bgcolor=666600>&nbsp</td>
									<td bgcolor=006666>&nbsp</td>
									<td bgcolor=660066>&nbsp</td>
								</tr>
								<tr>
									<td><input type='radio' name='sBorderColor' value='000000' <?php If ($sBorderColor == "000000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='FFFFFF' <?php If ($sBorderColor == "FFFFFF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='FF0000' <?php If ($sBorderColor == "FF0000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='00FF00' <?php If ($sBorderColor == "00FF00") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='0000FF' <?php If ($sBorderColor == "0000FF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='FFFF00' <?php If ($sBorderColor == "FFFF00") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='00FFFF' <?php If ($sBorderColor == "00FFFF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='FF00FF' <?php If ($sBorderColor == "FF00FF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='990000' <?php If ($sBorderColor == "990000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='009900' <?php If ($sBorderColor == "009900") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='000099' <?php If ($sBorderColor == "000099") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='999900' <?php If ($sBorderColor == "999900") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='009999' <?php If ($sBorderColor == "009999") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='990099' <?php If ($sBorderColor == "990099") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='660000' <?php If ($sBorderColor == "660000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='006600' <?php If ($sBorderColor == "006600") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='000066' <?php If ($sBorderColor == "000066") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='666600' <?php If ($sBorderColor == "666600") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='006666' <?php If ($sBorderColor == "006666") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sBorderColor' value='660066' <?php If ($sBorderColor == "660066") Echo "checked"; ?> style='border: 0px;'></td>
								</tr>
							</table>
						</tD>
					</tr>
					<tr>
						<td><b>Text Color:</td>
						<td>
							<table>
								<tr>
									<td bgcolor=000000>&nbsp</td>
									<td bgcolor=FFFFFF>&nbsp</td>
									<td bgcolor=FF0000>&nbsp</td>
									<td bgcolor=00FF00>&nbsp</td>
									<td bgcolor=0000FF>&nbsp</td>
									<td bgcolor=FFFF00>&nbsp</td>
									<td bgcolor=00FFFF>&nbsp</td>
									<td bgcolor=FF00FF>&nbsp</td>
									<td bgcolor=990000>&nbsp</td>
									<td bgcolor=009900>&nbsp</td>
									<td bgcolor=000099>&nbsp</td>
									<td bgcolor=999900>&nbsp</td>
									<td bgcolor=009999>&nbsp</td>
									<td bgcolor=990099>&nbsp</td>
									<td bgcolor=660000>&nbsp</td>
									<td bgcolor=006600>&nbsp</td>
									<td bgcolor=000066>&nbsp</td>
									<td bgcolor=666600>&nbsp</td>
									<td bgcolor=006666>&nbsp</td>
									<td bgcolor=660066>&nbsp</td>
								</tr>
								<tr>
									<td><input type='radio' name='sTextColor' value='000000' <?php If ($sTextColor == "000000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='FFFFFF' <?php If ($sTextColor == "FFFFFF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='FF0000' <?php If ($sTextColor == "FF0000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='00FF00' <?php If ($sTextColor == "00FF00") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='0000FF' <?php If ($sTextColor == "0000FF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='FFFF00' <?php If ($sTextColor == "FFFF00") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='00FFFF' <?php If ($sTextColor == "00FFFF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='FF00FF' <?php If ($sTextColor == "FF00FF") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='990000' <?php If ($sTextColor == "990000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='009900' <?php If ($sTextColor == "009900") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='000099' <?php If ($sTextColor == "000099") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='999900' <?php If ($sTextColor == "999900") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='009999' <?php If ($sTextColor == "009999") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='990099' <?php If ($sTextColor == "990099") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='660000' <?php If ($sTextColor == "660000") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='006600' <?php If ($sTextColor == "006600") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='000066' <?php If ($sTextColor == "000066") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='666600' <?php If ($sTextColor == "666600") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='006666' <?php If ($sTextColor == "006666") Echo "checked"; ?> style='border: 0px;'></td>
									<td><input type='radio' name='sTextColor' value='660066' <?php If ($sTextColor == "660066") Echo "checked"; ?> style='border: 0px;'></td>
								</tr>
							</table>
						</tD>
					</tr>
					<tr>
						<td><b>Title Font:</td>
						<td>
							<table width=100%>
								<tr>
									<td align=center><font face='Arial'>Arial</td>
									<td align=center><font face='Verdana'>Verdana</td>
									<td align=center><font face='Times New Roman'>Times New Roman</td>
									<td align=center><font face='Helvetica'>Helvetica</td>
									<td align=center><font face='Impact'>Impact</td>
								</tr>
								<tr>
									<td align=center><input type='radio' name='sTFont' value='Arial' <?php If ($sTFont == "Arial") Echo "checked"; ?> style='border: 0px;'></td>
									<td align=center><input type='radio' name='sTFont' value='Verdana' <?php If ($sTFont == "Verdana") Echo "checked"; ?> style='border: 0px;'></td>
									<td align=center><input type='radio' name='sTFont' value='Times New Roman' <?php If ($sTFont == "Times New Roman") Echo "checked"; ?> style='border: 0px;'></td>
									<td align=center><input type='radio' name='sTFont' value='Helvetica' <?php If ($sTFont == "Helvetica") Echo "checked"; ?> style='border: 0px;'></td>
									<td align=center><input type='radio' name='sTFont' value='Impact' <?php If ($sTFont == "Impact") Echo "checked"; ?> style='border: 0px;'></td>
								</tr>
							</table>
						</tD>
					</tr>
					<tr>
						<td><b>Message Font:</td>
						<td>
							<table width=100%>
								<tr>
									<td align=center><font face='Arial'>Arial</td>
									<td align=center><font face='Verdana'>Verdana</td>
									<td align=center><font face='Times New Roman'>Times New Roman</td>
									<td align=center><font face='Helvetica'>Helvetica</td>
									<td align=center><font face='Impact'>Impact</td>
								</tr>
								<tr>
									<td align=center><input type='radio' name='sMFont' value='Arial' <?php If ($sMFont == "Arial") Echo "checked"; ?> style='border: 0px;'></td>
									<td align=center><input type='radio' name='sMFont' value='Verdana' <?php If ($sMFont == "Verdana") Echo "checked"; ?> style='border: 0px;'></td>
									<td align=center><input type='radio' name='sMFont' value='Times New Roman' <?php If ($sMFont == "Times New Roman") Echo "checked"; ?> style='border: 0px;'></td>
									<td align=center><input type='radio' name='sMFont' value='Helvetica' <?php If ($sMFont == "Helvetica") Echo "checked"; ?> style='border: 0px;'></td>
									<td align=center><input type='radio' name='sMFont' value='Impact' <?php If ($sMFont == "Impact") Echo "checked"; ?> style='border: 0px;'></td>
								</tr>
							</table>
						</tD>
					</tr>					
				</table>
			</td>
		</tr>
		</table>
		<?php G_STRUCTURE_SubHeaderBar("SenderNRecInfo.gif", "", "", "Galleries");?>
		<table width=<?=$iTableWidth?> cellpadding = 1 cellspacing = 0 border = 0 class='TablePage_Boxed'>
		<tr>
			<td valign=top>
				<table class='TablePage'>
					<tr>
						<td><b>Card Title:</td>
						<td><input type='text' name='sTitle' value="<?=htmlentities($sTitle)?>" size=<?=$iFormWidth?> maxlength=250></tD>
					</tr>
					<tr>
						<td><b>Recipient Email:</td>
						<td><input type='text' name='sREmail' value="<?=htmlentities($sREmail)?>" size=<?=$iFormWidth?> maxlength=250></tD>
					</tr>
					<tr>
						<td><b>Recipient Name:</td>
						<td><input type='text' name='sRName' value="<?=htmlentities($sRName)?>" size=<?=$iFormWidth?> maxlength=250></tD>
					</tr>
					<tr>
						<td><b>Your Email:</td>
						<td><input type='text' name='sSenderEmail' value="<?=htmlentities($sSenderEmail)?>" size=<?=$iFormWidth?> maxlength=250></tD>
					</tr>
					<tr>
						<td><b>Your Name:</td>
						<td><input type='text' name='sSenderName' value="<?=htmlentities($sSenderName)?>" size=<?=$iFormWidth?> maxlength=250></tD>
					</tr>
				</table>
				<br>
				All recipient emails will be deleted from the system immediately after the eCard is sent.
			</td>
		</tr>
		</table>
		<table width=<?=$iTableWidth?> class='TablePage'>
			<tr>
				<td align=center>
					<input type='image' src="<?=G_STRUCTURE_DI("SendECard2.gif", $GLOBALS["SCHEMEBASED"])?>" style="BORDER: none; vertical-align: sub;" onClick='document.forms.SendECard.sAction.value="Send";'>
				</td>
				<td align=center>
					<input type='image' src="<?=G_STRUCTURE_DI("PreviewECard.gif", $GLOBALS["SCHEMEBASED"])?>" style="BORDER: none; vertical-align: sub;" onClick='document.forms.SendECard.sAction.value="Preview";'>
				</td>
			</tr>
		</table>
		<br>
		</form>
		<?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is the function that sends the card invitation email.						*
	//*																					*
	//************************************************************************************
	Function Send_Card($iCardUnq)
	{
		Global $CONF_ECardEmail;
		Global $sSiteURL;

		$sQuery			= "SELECT * FROM IGECards WHERE CardUnq = " . $iCardUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$sFullLetter = str_replace("1:", Trim($rsRow["SenderName"]), $CONF_ECardEmail);
			$sFullLetter = str_replace("2:", DOMAIN_Conf("IMAGEGALLERY_SITEURL"), $sFullLetter);
			$sFullLetter = str_replace("3:", $iCardUnq, $sFullLetter);
			$sEmailResponse = DOMAIN_Send_EMail($sFullLetter, Trim($rsRow["SenderName"]), Trim($rsRow["SenderEmail"]), Trim($rsRow["RName"]), Trim($rsRow["REmail"]), str_replace(str_replace("1: has sent you an E-Card from 2:!", "1:", Trim($rsRow["SenderName"])), "2:", $_SERVER["SERVER_NAME"]), TRUE);
			if ( ( $sEmailResponse === True ) || ( trim($sEmailResponse) == "" ) ) {
				Return True;
			}Else{
				DOMAIN_Message("Unable to send the E-Card email. Instead, please email your friend the following link: <br><br>" . $sSiteURL . "/EC.php?iCardUnq=" . $iCardUnq . "<BR><BR>" . $sEmailResponse, "ERROR");
				WriteForm();
				Return False;
			}
		}Else{
			DOMAIN_Message("Unable to send the E-Card email. Instead, please email your friend the following link: <br><br>" . $sSiteURL . "/EC.php?iCardUnq=" . $iCardUnq . "<BR><BR>" . $sEmailResponse, "ERROR");
			Return False;
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This writes out a single image - used when searching. The IG_Popup JavaScript 	*
	//*		needs to be printed out before this Function is called.							*
	//*	This writes it out for the thumbnail view.										*
	//*																					*
	//************************************************************************************
	Function DispThumb( $iImageUnq, $iGalleryUnq, $sAccountUnq, $sThumbnail, $sAltTag, $iImageNum, $sImageSize, $sImage, $sType, $sTitle, $iPrimaryG )
	{
		Global $sGalleryPath;
		Global $sSiteURL;
		
		// Only display this if the primary file exists.
		$sTempAlt	= $sAltTag;
		$sTempImage = $sTitle;
		$bIsImage	= False;
		
		If ( $sAltTag == "" )	// if alt tag is blank, use the image name (switch is back to sTempAlt below)
			$sAltTag = $sTitle;
		If ( strlen($sTempImage) > 12 )
			$sTempImage = substr($sTempImage, 12) . "...";

		G_STRUCTURE_FileType($sType, $bIsImage, $iImageUnq, "../../../", 0);
		?>
		<table cellpadding=0 cellspacing=0 border=0><tr>
			<td align=center bgcolor=<?=$GLOBALS["PageBGColor"]?>>
				<table cellpadding=0 cellspacing=0 border=0 width=100% height=100%>
					<tr>
						<td colspan=3 align=center>
							<table cellpadding=0 cellspacing=0 border=0 width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?>>
								<tr><td colspan=5 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../Images/Blank.gif" width=1 height=1></td></tr>
								<tr>
									<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../Images/Blank.gif" width=1 height=1></td>
									<?php If ( strtoupper(Trim(DOMAIN_Conf("IMAGEGALLERY_DOMAINCHECK"))) == "YES" ) { ?>
									<td colspan=3 align=center><img src = "<?=$sSiteURL?>/Attachments/DispThumb.php?sAccountUnq=<?=$sAccountUnq?>&iGalleryUnq=<?=$iPrimaryG?>&sThumbnail=<?=$sThumbnail?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> alt = "<?=htmlentities($sAltTag)?>" border=0></td>
									<?php }Else{
									$sFilePath = $sGalleryPath . "/" . $sAccountUnq . "/" . $iPrimaryG . "/Thumbnails/" . $sThumbnail;
									$sFilePath	= str_replace("\\", "/", $sFilePath);
									$sFilePath	= str_replace("//", "/", $sFilePath);
									If ( file_exists($sFilePath) && ( is_file($sFilePath) ) )
									{
										?>
										<td colspan=3 align=center><img src = "<?=DOMAIN_Conf("IG")?>/<?=$sAccountUnq?>/<?=$iPrimaryG?>/Thumbnails/<?=$sThumbnail?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> border=0></td>
										<?php 
									}Else{
										?>
										<td colspan=3 align=center><img src = "<?=DOMAIN_Conf("IMAGEGALLERY_MISSING_THUMBNAIL")?>" width=<?=DOMAIN_Conf("IMAGEGALLERY_THUMBNAIL_WIDTH")?> border=0></td>
										<?php 
									}
									} ?>
									<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../Images/Blank.gif" width=1 height=1></td>
								</tr>
								<tr><td colspan=5 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../Images/Blank.gif" width=1 height=1></td></tr>
								<tr>
									<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../Images/Blank.gif" width=1 height=1></td>
									<td colspan=3 align=center><font color='<?=$GLOBALS["PageText"]?>' size=-2><?=$sTempImage?></td>
									<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../Images/Blank.gif" width=1 height=1></td>
								</tr>
								<?php If ( ! $bIsImage ) {?>
								<tr><td colspan=5 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../Images/Blank.gif" width=1 height=1></td></tr>
								<tr>
									<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../Images/Blank.gif" width=1 height=1></td>
									<td bgcolor=<?=$GLOBALS["BGColor2"]?> colspan=3 align=center><img src='/PHPJK../Images/MediaIcons/<?=$sType?>.gif' alt = '<?=$sType?> file'></td>
									<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../Images/Blank.gif" width=1 height=1></td>
								</tr>
								<?php }?>
								<tr><td colspan=5 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src = "../Images/Blank.gif" width=1 height=1></td></tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr></table>
		<?php 
	}
	//************************************************************************************
?>
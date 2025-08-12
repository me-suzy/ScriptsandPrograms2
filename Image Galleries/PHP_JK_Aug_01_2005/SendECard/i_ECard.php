<?php 
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function L_Display_ECard($iCardUnq)
	{
		Global $iTableWidth;
		Global $sSiteURL;
		
		If ( $iCardUnq == "" ) {
			Echo "<br><table width=" . $iTableWidth . " cellpadding = 1 cellspacing = 0 border = 0 class='TablePage'>\n";
			Echo "<tr><td valign=top align=center>";
			DOMAIN_Message("Sorry! We are unable to find the E-Card you requested.", "ERROR");
			Echo "</td></tr></table><br>";
		}Else{
			$sQuery			= "SELECT C.*, I.ImageUnq, I.AltTag, I.Image, I.NumViews, I.XSize, G.GalleryUnq, G.AccountUnq, IG.PrimaryG, IG.PrimaryD FROM IGECards C (NOLOCK), Images I (NOLOCK), Galleries G (NOLOCK), ImagesInGallery IG (NOLOCK) WHERE I.ImageUnq = C.ImageUnq AND C.CardUnq = " . $iCardUnq . " AND I.ImageUnq = IG.ImageUnq AND IG.GalleryUnq = G.GalleryUnq AND C.GalleryUnq = IG.GalleryUnq";
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$sBGColor		= Trim($rsRow["BGColor"]);
				$sBorderColor	= Trim($rsRow["BorderColor"]);
				$sTFont			= Trim($rsRow["TFont"]);
				$sTextColor		= Trim($rsRow["TextColor"]);
				$sTitle			= Trim($rsRow["Title"]);
				$sMFont			= Trim($rsRow["MFont"]);
				$sXSize			= Trim($rsRow["XSize"]);
				If ( $sXSize == "" )
					$sXSize = 0;
				If ( strpos($iTableWidth, "%") <= 0 ) {
					If ( $sXSize > $iTableWidth+100 ) {
						$sHowDisplay	= "VERTICAL";
						$sXSize			= $iTableWidth - 10;
					}Else{
						$sHowDisplay	= "HORIZONTAL";
					}
				}Else{
					$sHowDisplay = "HORIZONTAL";
				}
				?>
				<STYLE TYPE="text/css" MEDIA=screen>
					.PHP_JK_ImageBorder {  border: #<?=$sBorderColor?>; border-style: solid; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px}
				</style>
				<br>
				<table width=<?=$iTableWidth?> cellpadding = 1 cellspacing = 0 border = 0><tr><td bgcolor='<?=$sBorderColor?>'>
				<table cellpadding = 5 cellspacing=0 border=0 width=100%>
					<tr><td bgcolor = <?=$sBGColor?> valign=top>
					<?php 
					If ( $sHowDisplay == "HORIZONTAL" ) {
						Echo "<img src = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sAccountUnq=" . $rsRow["AccountUnq"] . "&iGalleryUnq=" . $rsRow["PrimaryG"] . "&iImageUnq=" . Trim($rsRow["ImageUnq"]) . "&iDomainUnq=" . Trim($rsRow["PrimaryD"]) . "' alt = \"" . Trim($rsRow["AltTag"]) . "\" class='PHP_JK_ImageBorder' align=left>";
					}Else{
						Echo "<img src = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sAccountUnq=" . $rsRow["AccountUnq"] . "&iGalleryUnq=" . $rsRow["PrimaryG"] . "&iImageUnq=" . Trim($rsRow["ImageUnq"]) . "&iDomainUnq=" . Trim($rsRow["PrimaryD"]) . "' alt = \"" . Trim($rsRow["AltTag"]) . "\" class='PHP_JK_ImageBorder' align=center>";
					}
					DB_Update ("UPDATE Images SET NumViews = " . ($rsRow["NumViews"] + 1) . " WHERE ImageUnq = " . $rsRow["ImageUnq"]);
					?>
					<font face='<?=$sTFont?>' color='<?=$sTextColor?>' size=+2><?=$sTitle?></font><br><br>
					<font face='<?=$sMFont?>' color='<?=$sTextColor?>'>
					<?=$rsRow["Message"]?>
					<br><br>
					<?=$rsRow["RName"]?>
					<br>
					<a href='mailto:<?=$rsRow["REmail"]?>'><font face='<?=$sMFont?>' color='<?=$sTextColor?>'><?=$rsRow["REmail"]?></a>
					</td></tr>
				</table></td></tr></table>
				<br>
				<a href='<?=$sSiteURL?>/ImageDetail.php?iImageUnq=<?=$rsRow["ImageUnq"]?>&iGalleryUnq=<?=$rsRow["GalleryUnq"]?>' class='LargeNavPage'>&#151;Download this image&#151;</a>
				<br><Br>
				<?php 
			}Else{
				Echo "<br><table width=" . $iTableWidth . " cellpadding = 1 cellspacing = 0 border = 0 class='TablePage'>\n";
				Echo "<tr><td valign=top align=center>";
				DOMAIN_Message("Sorry! The E-Card you are requesting has already expired.", "ERROR");
				Echo "</td></tr></table><br>";
			}
			
		}
	}
	//************************************************************************************
?>
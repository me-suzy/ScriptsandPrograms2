<?php
	Require("Includes/i_Includes.php");
	Echo "<html><head><title></title>";
	Require("Includes/Nav/PHP_JK_CSS.php");
	Echo "</head><body>";
	DB_OpenDomains();
	DB_OpenImageGallery();
	INIT_LoginDetect();
	
	Main();
	Echo "</body></html>";
	
	DB_CloseDomains();
	
	
	//************************************************************************************
	//*																					*
	//*	Displays the info in the IG_Popup												*
	//*																					*
	//************************************************************************************
	Function Main()
	{	
		Global $sSiteURL;
		
		$iImageUnq = Trim(Request("iImageUnq"));
		$sImageNum = Trim(Request("sImageNum"));
		
		If ( ( $sImageNum != "0" ) && ( $sImageNum != "" ) && ( $iImageUnq != "" ) )
		{
			$sQuery			= "SELECT IG.PrimaryG, IG.PrimaryD, G.AccountUnq, I.Image" . $sImageNum . ", I.Image" . $sImageNum . "Desc, I.AltTag" . $sImageNum . ", I.XSize" . $sImageNum . ", I.YSize" . $sImageNum . " FROM ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK), Images I (NOLOCK) WHERE IG.ImageUnq = " . $iImageUnq . " AND IG.GalleryUnq = G.GalleryUnq AND I.ImageUnq = " . $iImageUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$iPrimaryG		= $rsRow["PrimaryG"];
				$sAccountUnq	= $rsRow["AccountUnq"];
				$sXSize			= Trim($rsRow["XSize" . $sImageNum]);
				$sYSize			= Trim($rsRow["YSize" . $sImageNum]);
				$sAltTag		= Trim($rsRow["AltTag" . $sImageNum]);
				$sDesc			= Trim($rsRow["Image" . $sImageNum . "Desc"]);
				
				If ( ( $sXSize != "" ) && ( $sXSize != "0" ) )
					$sXSize = "width = " . $sXSize;
				If ( ( $sYSize != "" ) && ( $sYSize != "0" ) )
					$sYSize = "height = " . $sYSize;
				$sImage = "<img src = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sImageNum=" . $sImageNum . "&sAccountUnq=" . $sAccountUnq . "&iGalleryUnq=" . $iPrimaryG . "&iImageUnq=" . $iImageUnq . "' alt = \"" . htmlentities($sAltTag) . "\" " . $sXSize . " " . $sYSize . " border=0>";
			}Else{
				$sImage = "<b>Invalid Image</b>";
			}
			
		}Else{
			If ( $iImageUnq == "" ) {
				$sQuery = "SELECT IG.PrimaryG, IG.PrimaryD, G.AccountUnq, I.Image, I.AltTag, I.XSize, I.YSize FROM ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK), Images I (NOLOCK) WHERE IG.ImageUnq = -1 AND IG.GalleryUnq = G.GalleryUnq AND I.ImageUnq = -1";
			}Else{
				$sQuery = "SELECT IG.PrimaryG, IG.PrimaryD, G.AccountUnq, I.Image, I.AltTag, I.XSize, I.YSize FROM ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK), Images I (NOLOCK) WHERE IG.ImageUnq = " . $iImageUnq . " AND IG.GalleryUnq = G.GalleryUnq AND I.ImageUnq = " . $iImageUnq;
			}
			$rsRecordSet = DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$iPrimaryG		= $rsRow["PrimaryG"];
				$sAccountUnq	= $rsRow["AccountUnq"];
				$sXSize			= Trim($rsRow["XSize"]);
				$sYSize			= Trim($rsRow["YSize"]);
				$sAltTag		= Trim($rsRow["AltTag"]);
				$sDesc			= "";
				
				If ( ( $sXSize != "" ) && ( $sXSize != "0" ) )
					$sXSize = "width = " . $sXSize;
				If ( ( $sYSize != "" ) && ( $sYSize != "0" ) )
					$sYSize = "height = " . $sYSize;
				$sImage = "<img src = '" . $sSiteURL . "/Attachments/DownloadAttach.php?sAccountUnq=" . $sAccountUnq . "&iGalleryUnq=" . $iPrimaryG . "&iImageUnq=" . $iImageUnq . "' alt = \"" . htmlentities($sAltTag) . "\" " . $sXSize . " " . $sYSize . " border=0>";
			}Else{
				$sImage = "<b>Invalid Image</b>";
			}
			
		}
		
		?>
		<center>
		<table cellpadding=0 cellspacing=0 border=0 width=100% height=100%><tr><td align=center valign=center>
		<table cellpadding=2 cellspacing=0 <?=$sYSize?> border=0 <?=$sXSize?>>
			<tr>
				<td valign=top bgcolor=<?=$GLOBALS["BGColor1"]?>>
					<center><a href = "JavaScript:parent.self.close();" class='MediumNav1'>Close Window</a>
				</td>
			</tr>
			<tr>
				<td height=100% valign=top align=center bgcolor = <?=$GLOBALS["BGColor1"]?>>
					<table cellpadding=0 cellspacing=0 border=0 <?=$sXSize?>>
						<tr><td colspan=3 bgcolor = <?=$GLOBALS["BorderColor2"]?>><img src = "Images/Blank.gif" width=1 height=1></td></tr>
						<tr>
							<td bgcolor = <?=$GLOBALS["BorderColor2"]?>><img src = "Images/Blank.gif" width=1 height=1></td>
							<td bgcolor = <?=$GLOBALS["BGColor1"]?>><a href = "JavaScript:self.close();"><?=$sImage?></a></td>
							<td bgcolor = <?=$GLOBALS["BorderColor2"]?>><img src = "Images/Blank.gif" width=1 height=1></td>
						</tr>
						<tr><td colspan=3 bgcolor = <?=$GLOBALS["BorderColor2"]?>><img src = "Images/Blank.gif" width=1 height=1></td></tr>
					</table>
				</td>
			</tr>
			<tr>
				<td valign=top bgcolor=<?=$GLOBALS["BGColor1"]?>>
					<font color='<?=$GLOBALS["TextColor1"]?>'><?=$sDesc?></font>
					<br><br>
					<center><a href = "JavaScript:parent.self.close();" class='MediumNav1'>Close Window</a>
				</td>
			</tr>
		</table>
		</td></tr></table>
		<?php 
	}
	//************************************************************************************
?>
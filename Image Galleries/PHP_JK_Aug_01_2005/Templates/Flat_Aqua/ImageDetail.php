<?php
$sImageDetail =<<<EOHTML
<table width=$iTableWidth cellpadding=0 cellspacing=0 border=0 class='TablePage'>
	<tr><td colspan=2><img src='Images/Blank.gif' Width=1 Height=5></td></tr>
	<tr>
		<td>
			$sDisplayCategoryDropDown
		</td>
		<td align=right>
			$sDisplayGalleriesDropDown
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<a href = 'index.php?$sDomainLink' class='MediumNavPage'>Galleries</a> <img src = '$sBreadcrumbArrow'> $sBreadCrumb<BR>
		</td>
	</tr>
	<tr><td colspan=2><hr></td></tr>
	<tr>
		<td valign=top colspan=2>
			<table cellpadding=0 cellspacing=0 border=0 width=100% class='TablePage'>
				<tr>
					<td width=33%>
						$sAddDate						
						<br>Image $iImagePos of $iTotalImages
						$sGalleryOwnerName
					</td>
					<td align=center width=33%>
						$sAricaurLink
					</td>
					<td align=right valign=top width=33%>
						Views: $iNumViews | $sImageSizeText
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan=2><img src='Images/Blank.gif' Width=1 Height=15></td></tr>
	<tr>
		<td colspan=2 valign=top>
			<center>
			$sDisplayImage
			<table cellpadding=10 class='TablePage'>	
				<tr>
					<td valign=top>
						$sDisplayRatings
						$sDisplayProducts
					</td>
					<td valign=top class='VertSeperator'>
						$sDisplayAltViews
					</td>
					<td><img src='/images/blank.gif' width=1 height=1></td>
					<td valign=top>
						$sEcardLink
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=2 valign=top>
			$sDisplayOtherLinks
		</td>
	</tr>
	<tr>
		<td colspan=2 valign=top>
			$sDisplayKeywords
		</td>
	</tr>
	<tr>
		<td colspan=2 valign=top>
			$sDisplayCopyrights
		</td>
	</tr>
	<tr><td colspan=2><img src='Images/Blank.gif' Width=1 Height=5></td></tr>
	<tr><td colspan=2><hr></td></tr>
	<tr><td colspan=2><img src='Images/Blank.gif' Width=1 Height=5></td></tr>
	<tr>
		<td colspan=2 valign=top>
			<table width=100% cellpadding=0 cellspacing=0 border=0 class='TablePage'>
				<tr>
					<td>
						$sPrevImage
					</td>
					<td align=center>
						$sDisplayTimerDown
					</td>
					<td align=right>
						$sNextImage
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td colspan=2><hr></td></tr>
	<tr><td colspan=2><img src='Images/Blank.gif' Width=1 Height=5></td></tr>
	<tr>
		<td colspan=2 valign=top>
			$sComments
		</td>
	</tr>
	<tr><td colspan=2><img src='Images/Blank.gif' Width=1 Height=5></td></tr>
	<tr><td colspan=2><hr></td></tr>
	<tr><td colspan=2><img src='Images/Blank.gif' Width=1 Height=5></td></tr>
	<tr>
		<td colspan=2 valign=top>
			$sDisplayCustomFields
		</td>
	</tr>
	<tr><td colspan=2><img src='Images/Blank.gif' Width=1 Height=5></td></tr>
	<tr><td colspan=2><hr></td></tr>
	<tr><td colspan=2><img src='Images/Blank.gif' Width=1 Height=5></td></tr>
</table>
EOHTML;
?>
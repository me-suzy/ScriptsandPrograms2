<?php
$sThumbnailView =<<<EOHTML
		<table width=$iTableWidth cellpadding=0 cellspacing=0 border=0 class='TablePage'>
			<tr>
				<td>
					$sDisplayCategoryDropDown
				</td>
				<td align=right>
					$sDisplayGalleriesDropDown
				</td>
			</tr>
			$sSuggestionLinks
			<tr>
				<td colspan=2>
					<a href = 'index.php?$sDomainLink' class='MediumNavPage'>Galleries</a> <img src = '$sBreadcrumbArrow'> $sBreadCrumb
				</td>
			</tr>
		</table>
		$sHeaderBar
		<table width=$iTableWidth cellpadding = 1 cellspacing = 0 border = 0 class='TablePage_Boxed'>
			<tr>
				<td bgcolor=$sPageBGColor>
					<table width=100% cellpadding=0 cellspacing=0 border=0 class='TablePage'>
						<tr>
							<td valign=top>
								<b>$sGalleryName</b>
							</td>
							<td valign=top align=right>
								<font size=-2><b>$iTtlNumItems &nbsp;Total Images</b>
								$sGalleryOwnerLink
							</td>
						</tr>
						<tr>
							<td colspan=2>
								$sGalleryDesc
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor=$sPageBGColor>
					<br>
					<table width=100% cellpadding=0 cellspacing=0 border=0 class='TablePage'>
						<tr>
							<td>
								$sDisplayNumPerPageDropDown
							</td>
							<td align=right>
								$sDisplaySortDropDown
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td bgcolor=$sPageBGColor>
					$sRecordsetNav
				</td>
			</tr>
		</table>
EOHTML;
?>
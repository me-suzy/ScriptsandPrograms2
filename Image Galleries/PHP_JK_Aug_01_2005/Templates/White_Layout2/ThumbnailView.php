<?php
$sThumbnailView =<<<EOHTML
		<table width=$iTableWidth cellpadding=0 cellspacing=0 border=0 class='TablePage'>
			<tr>
				<td>
					$sLinkSuggestGallery<br>
					$sLinkSubscribeGallery
				</td>
				<td align=right>
					$sDisplayCategoryDropDown
					$sDisplayGalleriesDropDown
				</td>
			</tr>
			<tr>
				<td colspan=2>
					<a href = 'index.php?$sDomainLink' class='MediumNavPage'>Galleries</a> <img src = '$sBreadcrumbArrow'> $sBreadCrumb
				</td>
			</tr>
		</table>
		$sHeaderBar
		<table cellpadding=0 width=$iTableWidth cellspacing = 5 border = 0 class='TablePage_Boxed'>
			<tr>
				<td>
					<table width=100% cellpadding=0 cellspacing=0 border=0 class='TablePage'>
						<tr>
							<td valign=top>
								<b>$sGalleryName</b>
							</td>
							<td valign=top align=right>
								$iTtlNumItems Total Images
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
				<td>
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
				<td>
					$sRecordsetNav
				</td>
			</tr>
		</table>
EOHTML;
?>
<?php
$sImageDetail =<<<EOHTML
<table width=$iTableWidth cellpadding=0 cellspacing=0 border=0 class='TablePage'>
	<tr><td colspan=2><img src='Images/Blank.gif' Width=5 Height=10></td></tr>
	<tr>
		<td>
			$sDisplayCategoryDropDown
		</td>
		<td align=right>
			$sDisplayGalleriesDropDown
		</td>
	</tr>
	<tr><td colspan=2><hr></td></tr>
	<tr>
		<td colspan=2>
			<a href = 'index.php?$sDomainLink' class='MediumNavPage'>Galleries</a> <img src = '$sBreadcrumbArrow'> $sBreadCrumb<BR>
		</td>
	</tr>
	<tr><td colspan=2><img src='Images/Blank.gif' Width=5 Height=10></td></tr>
	<tr>
		<td colspan=2 valign=top>
			<table cellpadding=0 cellspacing=0 border=0 class='TablePage' width=100%>
				<tr>
					<td valign=top>
						<table cellpadding=10 cellspacing=0 border=0 class='TablePage_Boxed'>
							<tr>
								<td>
									<center>$sDisplayImage</center>
								</td>
							</tr>
						</table>
					</td>
					<td valign=top align=right>
						<table cellpadding=0 cellspacing=0 border=0 class='TablePage'><tr><td align=center>
							$sAricaurLink
							<table cellpadding=10 width=171 cellspacing=0 border=0 class='TablePage_Boxed'>
								<tr>
									<td>
										<table cellpadding=5 cellspacing=0 border=0 width=100% class='TablePage'>
											<tr><td bgcolor=555555><font size=-2>$sAddDate</td></tr>
											<tr><td bgcolor=000000><font size=-2>Image $iImagePos of $iTotalImages</td></tr>
											<tr><td bgcolor=555555><font size=-2>$sGalleryOwnerName</td></tr>
											<tr><td bgcolor=000000><font size=-2>Views: $iNumViews | $sImageSizeText</td></tr>
											<tr><td bgcolor=555555><font size=-2>$sDisplayRatings</td></tr>
											<tr><td bgcolor=000000><font size=-2><center>$sEcardLink</center></td></tr>
											<tr><td bgcolor=555555><font size=-2>$sDisplayAltViews</td></tr>
										</table>

										<center>
										$sDisplayTimerDown
										<table width=100% cellpadding=0 cellspacing=0 border=0>
											<tr>
												<td>
													$sPrevImage												
												</td>
												<td>
													
												</td>
												<td align=right>
													$sNextImage
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td></tr></table>
					</td>
				</tr>
			</table>
			$sDisplayProducts
		</td>
	</tr>
	<tr>
		<td colspan=2 valign=top>
			<table cellpadding=0 cellspacing=0 border=0 class='TablePage' width=100%>
				<tr>
					<td>
						<br>
						$sDisplayOtherLinks
						<br><br>
						$sDisplayKeywords
						<br><br>
						$sDisplayCopyrights
						<br><br>
						$sComments
						<br><br>
						$sDisplayCustomFields
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<bR>
EOHTML;
?>
<table cellpadding=0 cellspacing=0 border=0 width=<?=$iTableWidth?>>
	<tr>
		<td bgcolor=<?=$BorderColor2?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1 Height=3></td>
		<td colspan=8 bgcolor=<?=$BGColor2?> width=23><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=23 Height=2></td>
		<td bgcolor=<?=$BorderColor2?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1 Height=3></td>
	</tr>
	<tr>
		<td bgcolor=<?=$BorderColor2?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1 Height=2></td>
		<?php
		If ( (ACCNT_ReturnRights("PHPJK_IG_ADD_CAT")) || (ACCNT_ReturnRights("PHPJK_IG_EDIT_CAT")) || (ACCNT_ReturnRights("PHPJK_IG_DEL_CAT")) ) {
			Echo "<td align=center bgcolor=" . $BGColor2 . ">[<a href='" . $sSiteURL . "/Admin/ManageCategories/' class='MediumNav2'>Categories</a>]</td>";
		}Else{
			Echo "<td align=center bgcolor=" . $BGColor2 . "></td>";
		}
		
		If ( (ACCNT_ReturnRights("PHPJK_IG_ADD_CR")) || (ACCNT_ReturnRights("PHPJK_IG_EDIT_CR")) || (ACCNT_ReturnRights("PHPJK_IG_DEL_CR")) ) {
			Echo "<td align=center bgcolor=" . $BGColor2 . ">[<a href='" . $sSiteURL . "/Admin/ManageCopyrights/' class='MediumNav2'>Copyrights</a>]</td>";
		}Else{
			Echo "<td align=center bgcolor=" . $BGColor2 . "></td>";
		}
		
		If ( (ACCNT_ReturnRights("PHPJK_IG_BULK")) || (ACCNT_ReturnRights("PHPJK_IG_BULK_ZIP")) ) {
			Echo "<td align=center bgcolor=" . $BGColor2 . ">[<a href='" . $sSiteURL . "/Admin/ManageEnMasse/' class='MediumNav2'>Bulk</a>]</td>";
		}Else{
			Echo "<td align=center bgcolor=" . $BGColor2 . "></td>";
		}
		
		If ( (ACCNT_ReturnRights("PHPJK_IG_ADD_PL")) || (ACCNT_ReturnRights("PHPJK_IG_EDIT_PL")) || (ACCNT_ReturnRights("PHPJK_IG_DEL_PL")) || (ACCNT_ReturnRights("PHPJK_IG_ADD_PROD")) || (ACCNT_ReturnRights("PHPJK_IG_EDIT_PROD")) || (ACCNT_ReturnRights("PHPJK_IG_DEL_PROD")) ) {
			Echo "<td align=center bgcolor=" . $BGColor2 . ">[<a href='" . $sSiteURL . "/Admin/ManagePL/' class='MediumNav2'>Products</a>]</td>";
		}Else{
			Echo "<td align=center bgcolor=" . $BGColor2 . "></td>";
		}
		
		If ( (ACCNT_ReturnRights("PHPJK_IG_PRIVATE")) || (ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL")) ) {
			Echo "<td align=center bgcolor=" . $BGColor2 . ">[<a href='" . $sSiteURL . "/Admin/ManagePrivateGalleries/' class='MediumNav2'>Private Galleries</a>]</td>";
		}Else{
			Echo "<td bgcolor=" . $BGColor2 . "></td>";
		}
		
		If ( (ACCNT_ReturnRights("PHPJK_IG_ADD_PROD_2GALLERIES")) || (ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY")) || (ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL")) ) {
			Echo "<td align=center bgcolor=" . $BGColor2 . ">[<a href='" . $sSiteURL . "/Admin/ManageGalleries/' class='MediumNav2'>Galleries</a>]</td>";
			Echo "<td align=center bgcolor=" . $BGColor2 . ">[<a href='" . $sSiteURL . "/Admin/ManageImages/' class='MediumNav2'>Images</a>]</td>";
		}Else{
			Echo "<td bgcolor=" . $BGColor2 . " colspan=2></td>";
		}
		?>
		<td align=center bgcolor=<?=$BGColor2?>>[<a href='<?=$sSiteURL?>/Admin/Configurations/' class='MediumNav2'>Site Admin -&gt;</a>]</td>
		<td bgcolor=<?=$BorderColor2?> width=1><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1 Height=2></td>
	</tr>
	<tr>
		<td bgcolor=<?=$BorderColor2?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1 Height=3></td>
		<td colspan=8 bgcolor=<?=$BGColor2?> width=23><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=23 Height=2></td>
		<td bgcolor=<?=$BorderColor2?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1 Height=3></td>
	</tr>
	<tr>
		<td bgcolor=<?=$BorderColor2?> width=1><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1 Height=1></td>
		<td colspan=8 bgcolor=<?=$BorderColor2?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=2 Height=1></td>
		<td bgcolor=<?=$BorderColor2?> width=1><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1 Height=1></td>
	</tr>
</table>
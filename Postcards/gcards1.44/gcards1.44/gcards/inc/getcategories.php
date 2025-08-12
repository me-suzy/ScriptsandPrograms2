<?
/*
 * gCards - a web-based eCard application
 * Copyright (C) 2003 Greg Neustaetter
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
$catsqlstmt = 'select * from '.$tablePrefix.'categories order by category';
$catrecordset = &$conn->Execute($catsqlstmt);

$totalCardsSQL = "SELECT COUNT(*) from ".$tablePrefix."cardinfo";
$totalCards = $conn->GetOne($totalCardsSQL);



if (!$catrecordset) 
	print $conn->ErrorMsg();
else
	{
		?>
<table cellpadding="5">
	<tr>
		<td class="subtitle" colspan="2">
			
			<? if ($catSearch) {?>
			<nobr><a href="<? echo $_SERVER['PHP_SELF']."?limit=$limit";?>"><? echo $index01;?></a> <? } else { ?> <b><? echo $index01;?></b> <? } ?> (<? echo $totalCards ?>)</nobr>
			
		</td>
	</tr>				
		<?
		

		while (!$catrecordset->EOF) 
			{
				$catid = $catrecordset->fields['catid'];
				$category = $catrecordset->fields['category'];
				$numCardsInCatSQL = "SELECT COUNT(*) from ".$tablePrefix."cardinfo where catid=$catid";
				$cardsInRow = $conn->GetOne($numCardsInCatSQL);
				if ($cardsInRow > 0)
				{
					if (isset($catSearch) && $catSearch == $catid)
					{
						echo "\n\t<tr>\n\t\t<td>\n\t\t\t&nbsp;</td><td><nobr><b>$category</b>";
						$selectedCategory = $category;
					}
					else
					{
						echo "\n\t<tr>\n\t\t<td>\n\t\t\t&nbsp;</td><td><nobr><a href=\"".$_SERVER['PHP_SELF']."?catSearch=$catid&limit=$limit\">$category</a>";
					}
					echo " ($cardsInRow)</nobr>\n\t\t</td>\n\t</tr>";
				}
				$catrecordset->MoveNext();
			}
		echo "\n</table>";
	}
$catrecordset->Close(); # optional

?>
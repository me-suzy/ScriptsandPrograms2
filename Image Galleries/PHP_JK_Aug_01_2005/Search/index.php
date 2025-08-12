<?php
	Require("../Includes/i_Includes.php");
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	DeleteOldIGSearchResults();
	SearchGalleries();
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");


	//************************************************************************************
	//*																					*
	//*	This displays the search form.													*
	//*																					*
	//************************************************************************************
	Function SearchGalleries()
	{
		Global $aVariables;
		Global $aValues;
		Global $iTableWidth;
		Global $iFormWidth;
		
		// Display the search form
		$sBGColor		= $GLOBALS["BGColor1"];
		$sTextColor		= $GLOBALS["TextColor1"];
		$sReturnPage	= "";
		
		If ( isset($_SERVER['HTTP_REFERER_http']) )
		{
			$sReturnPage		= $_SERVER["HTTP_REFERER_http"];
		}ElseIf ( isset($_SERVER['HTTP_REFERER']) )
		{
			$sReturnPage		= $_SERVER["HTTP_REFERER"];
		}
		?>
		<form action = "PrepareResults.php?<?=DOMAIN_Link("G")?>" method = "post" class='PageForm'>
			<input type = "hidden" name = "sAction" value = "1">
			<input type = "hidden" name = "sReferer" value = "<?=htmlentities($sReturnPage)?>">
			<table cellpadding=5 width=<?=$iTableWidth?> cellspacing = 0 border = 0 class='TablePage'><tr><td align=right><?php G_LINK_New_Image_Search();?></td></tr></table>
			<?php G_STRUCTURE_HeaderBar("SearchImagesHead.gif", "", "", "Galleries");?>
			<table width=<?=$iTableWidth?> cellpadding = 1 cellspacing = 0 border = 0 class='TablePage_Boxed'>
				<tr>
					<td colspan = 2 bgcolor = <?=$sBGColor?>>
						<font color='<?=$sTextColor?>'>
						<b>Enter your search criteria:</b><br>
						Search galleries and image descriptions. If you 
						are logged in, this will also search any PRIVATE galleries you have created 
						or any locked galleries you have access to. Locked galleries you 
						do not have access to will not be searched.
					</td>
				</tr>
				<tr>
					<td bgcolor = <?=$sBGColor?> valign=top>
						<font color='<?=$sTextColor?>'>
						Search&nbsp;Keywords:
					</td>
					<td bgcolor = <?=$sBGColor?>>
						<input type = "Text" name = "sKeywords" value = "" maxlength=32 size=<?=$iFormWidth?>>
					</td>
				</tr>
			</table>
			<input type='image' src="<?=G_STRUCTURE_DI("PerformSearch.gif", $GLOBALS["SCHEMEBASED"])?>" style="BORDER: none; vertical-align: sub;">
			<br><br>
		</form>
		<?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This deletes all old search results.											*
	//*																					*
	//************************************************************************************
	Function DeleteOldIGSearchResults()
	{
		$sQuery			= "SELECT * FROM IGSearches WHERE DateChanged < '" . DOMAIN_FormatDate(DateAdd("h", -1, time()), "L") . "'";
		$rsRecordSet	= DB_Query($sQuery);
		While ( $rsRow = DB_Fetch($rsRecordSet) )
			DB_Update ("DELETE FROM IGSearchResults WHERE SearchID = " . $rsRow["SearchID"]);

		// delete the entries of these old temp tables
		DB_Update ("DELETE FROM IGSearches WHERE DateChanged < '" . DOMAIN_FormatDate(DateAdd("h", -1, time()), "L") . "'");
	}
	//************************************************************************************
?>
<?php
	Require("../Includes/i_Includes.php");
	Require("../Includes/Config/i_SuggestCategory.php");
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	Main();

	?>
	<table width=<?=$iTableWidth?> cellpadding = 0 cellspacing = 0 border = 0 class='TablePage'><tr><td>
	<a href='/'><img src='<?=G_STRUCTURE_DI("Return.gif", $GLOBALS["SCHEMEBASED"])?>' border=0></a>
	</TD></TR></TABLE>
	<br>
	<?php
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iTableWidth;
		Global $CONF_SuggestCat;
		
		$sAction = Trim(Request("sAction"));

		If ( $sAction == "SendEmail" )
		{
			Echo "<br>";
			G_STRUCTURE_HeaderBar("SuggestNewCatHead.gif", "Suggest new category", "", "Galleries");

			$sEmail = DOMAIN_Conf("IMAGEGALLERY_SUGGESTCATEGORY_TO_EMAIL");
			// Send the email*********
			$sFullLetter = $CONF_SuggestCat;
			$sFullLetter = str_replace("1:", Request("sCatName"), $sFullLetter);
			$sFullLetter = str_replace("2:", Request("sDescription"), $sFullLetter);
			$sFullLetter = str_replace("3:", $_SERVER["SERVER_NAME"], $sFullLetter);
				
			$sEmailResponse = DOMAIN_Send_EMail($sFullLetter, DOMAIN_Conf("IMAGEGALLERY_SUGGESTCATEGORY_FROM_NAME"), DOMAIN_Conf("IMAGEGALLERY_SUGGESTCATEGORY_FROM_EMAIL"), "Administrator", $sEmail, $_SERVER["SERVER_NAME"] . " Image Gallery Category Suggestion", FALSE);
			if ( ( $sEmailResponse === True ) || ( trim($sEmailResponse) == "" ) ) {
				DOMAIN_Message("Thank you for your input.", "SUCCESS");
			}Else{
				DOMAIN_Message(str_replace("1:", $sEmail, "Mail failure sending to 1:. Check mail host server name and tcp/ip connection...<br>") . $sEmailResponse, "ERROR");
			}
			Echo "<br>";
		}Else{
			WriteForm();
		}
	}
	//************************************************************************************

	
	//************************************************************************************
	//*																					*
	//*	This allows the users to suggest a new category									*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $iTableWidth;
		Global $aVariables;
		Global $aValues;
		Global $iFormWidth;
		Global $iFormColumns;
		
		$sReturnPage	= "";
		
		If ( isset($_SERVER['HTTP_REFERER_http']) )
		{
			$sReturnPage		= $_SERVER["HTTP_REFERER_http"];
		}ElseIf ( isset($_SERVER['HTTP_REFERER']) )
		{
			$sReturnPage		= $_SERVER["HTTP_REFERER"];
		}
		
		?>
		<br>
		<form action = "index.php?<?=DOMAIN_Link("G")?>&sAction=SendEmail" method = "post" class='PageForm'>
		<?php G_STRUCTURE_HeaderBar("SuggestNewCatHead.gif", "Suggest new category", "", "Galleries");?>
			<input type = "hidden" name = "sReferer" value = "<?=$sReturnPage?>">
			<table width=<?=$iTableWidth?> cellpadding = 10 cellspacing = 0 border = 0 class='TablePage_Boxed'>
				<tr>
					<td valign=top>
						Please enter the category name to add:
						<br>
						<input type = "text" name = "sCatName" size=<?=$iFormWidth?> maxlength=255>
						<br>
						Please describe the category and why you think it should be added:
						<br>
						<TEXTAREA COLS=<?=$iFormColumns?> ROWS=10 WRAP="soft" NAME="sDescription"></TEXTAREA>
					</td>
				</tr>
			</table>
			<input type='image' src="<?=G_STRUCTURE_DI("SubmitCatSuggestion.gif", $GLOBALS["SCHEMEBASED"])?>" style="BORDER: none; vertical-align: sub;">
		</form>
		<?php 
	}
	//************************************************************************************
?>
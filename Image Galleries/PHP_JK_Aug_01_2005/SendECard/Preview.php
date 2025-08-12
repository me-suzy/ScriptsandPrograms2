<?php
	Require("../Includes/i_Includes.php");
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	Require("i_ECard.php");
	Main();
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iTableWidth;
		Global $iTextScheme;
		Global $iColorScheme;
		Global $aVariables;
		Global $aValues;
		
		$iCardUnq = Trim(Request("iCardUnq"));

		$sQuery			= "SELECT SenderName, SenderEmail FROM IGECards (NOLOCK) WHERE CardUnq = " . $iCardUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$sSenderEmail	= Trim($rsRow["SenderEmail"]);
			$sSenderName	= Trim($rsRow["SenderName"]);

			// display card
			L_Display_ECard($iCardUnq);
			
			// display links to either submit or edit
			?>
			<form name='PreviewECard' action='index.php' method='post' class='PageForm'>
				<?php 
				$aVariables[0] = "sAction";
				$aVariables[1] = "iCardUnq";
				$aValues[0] = "Edit";
				$aValues[1] = $iCardUnq;
				Echo DOMAIN_Link("P");
				?>
				<table width=<?=$iTableWidth?> class='TablePage'>
					<tr>
						<td colspan=2>
							From: <?=$sSenderName?><br>
							<?=$sSenderEmail?>
						</td>
					</tr>
					<tr>
						<td align=center>
							<input type='image' src="<?=G_STRUCTURE_DI("SendECard2.gif", $GLOBALS["SCHEMEBASED"])?>" style="BORDER: none; vertical-align: sub;" onClick='document.forms.PreviewECard.sAction.value="SendFromPreview";'>
						</td>
						<td align=center>
							<input type='image' src="<?=G_STRUCTURE_DI("EditECard.gif", $GLOBALS["SCHEMEBASED"])?>" style="BORDER: none; vertical-align: sub;" onClick='document.forms.PreviewECard.sAction.value="Edit";'>
						</td>
					</tr>
				</table>
				<br>
			</form>
			<?php 
		}Else{
			DOMAIN_Message("Unable to preview your E-Card! Please alert the webmaster.", "ERROR");
		}
	}
	//************************************************************************************
?>
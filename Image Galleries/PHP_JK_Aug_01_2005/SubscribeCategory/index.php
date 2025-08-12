<?php
	Require("../Includes/i_Includes.php");
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_OPEN.php");
	Main();
	Require("../Templates/" . $sTemplates . "/Nav/PHP_JK_GALLERIES_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $iLoginAccountUnq;
		
		$iCategoryUnq = Trim(Request("iCategoryUnq"));

		If ( $iCategoryUnq != "" )
		{
			If ( G_ADMINISTRATION_HasCategorySubscription($iLoginAccountUnq, $iCategoryUnq) ) {
				$sMessage = "You are already subscribed to this category.";
			}Else{
				DB_Insert ("INSERT INTO IG_Subscriptions VALUES (" . $iCategoryUnq . ", 0, " . $iLoginAccountUnq . ", 'N')");
				$sMessage = "You are now subscribed to this category and will receive an email when new images are added to galleries within this category.<br><br>To unsubscribe, return to the category page and click the \"Unsubscribe Category\" button.";
			}
		}Else{
			$sMessage = "Unable to subscribe to the category -- missing CategoryUnq.";
		}
		
		WriteForm($sMessage);
	}
	//************************************************************************************

	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function WriteForm($sMessage)
	{
		Global $iTableWidth;
		
		$sReturnPage	= "";
		
		If ( isset($_SERVER['HTTP_REFERER_http']) )
		{
			$sReturnPage		= $_SERVER["HTTP_REFERER_http"];
		}ElseIf ( isset($_SERVER['HTTP_REFERER']) )
		{
			$sReturnPage		= $_SERVER["HTTP_REFERER"];
		}
		
		Echo "<BR>";
		G_STRUCTURE_HeaderBar("SubscribeCatHead.gif", "", "", "Galleries");
		?>
		<table width=<?=$iTableWidth?> cellpadding = 10 cellspacing = 0 border = 0 class='TablePage_Boxed'>
			<tr>
				<td valign=top>
					<br><?=$sMessage?><br><br>
				</td>
			</tr>
		</table>
		<br>
		<table width=<?=$iTableWidth?> cellpadding = 0 cellspacing = 0 border = 0 class='TablePage'><tr><td>
		<a href='<?=$sReturnPage?>'><img src='<?=G_STRUCTURE_DI("Return.gif", $GLOBALS["SCHEMEBASED"])?>' border=0></a>
		</TD></TR></TABLE>
		<br>
		<?php
	}
	//************************************************************************************
?>

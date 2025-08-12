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
				DB_Update ("DELETE FROM IG_Subscriptions WHERE AccountUnq = " . $iLoginAccountUnq . " AND CategoryUnq = " . $iCategoryUnq);
				$sMessage = "You are no longer subscribed to this category and will no longer receive emails when new images are added to galleries within it.<bR><br>You may still receive emails regarding new images from galleries within this category that you subscribed to individually.";
			}Else{
				$sMessage = "You do not have a subscription to this category.";
			}
		}Else{
			$sMessage = "Unable to unsubscribe from the category -- missing CategoryUnq.";
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
		G_STRUCTURE_HeaderBar("CancelCatSubHead.gif", "", "", "Galleries");
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
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
		
		$iGalleryUnq = Trim(Request("iGalleryUnq"));

		If ( $iGalleryUnq != "" )
		{
			If ( G_ADMINISTRATION_HasGallerySubscription($iLoginAccountUnq, $iGalleryUnq) ) {
				DB_Update ("DELETE FROM IG_Subscriptions WHERE AccountUnq = " . $iLoginAccountUnq . " AND GalleryUnq = " . $iGalleryUnq);
				$sMessage = "You are no longer subscribed to this gallery and will no longer receive emails when new images are added to it.";
			}Else{
				$sMessage = "You do not have a subscription to this gallery.";
			}
		}Else{
			$sMessage = "Unable to unsubscribe from the gallery -- missing GalleryUnq.";
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
		G_STRUCTURE_HeaderBar("CancelGalSubHead.gif", "", "", "Galleries");
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

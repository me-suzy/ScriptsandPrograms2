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
		Global $iTableWidth;
		
		$iCardUnq = Trim(Request("iCardUnq"));
		
		$sQuery = "SELECT RName, REmail FROM IGECards (NOLOCK) WHERE CardUnq = " . $iCardUnq;
		$rsRecordSet	= DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
		{
			$sREmail	= Trim($rsRow["REmail"]);
			$sRName		= Trim($rsRow["RName"]);
		}

		Echo "<br>";
		G_STRUCTURE_HeaderBar_ReallySpecific("SendECardHead.gif", "", "", "/PHPJK/", "Galleries");
		Echo "<table width=" . $iTableWidth . " cellpadding = 1 cellspacing = 0 border = 0 class='TablePage_Boxed'>\n";
		?>
		<tr>
			<td valign=top align=center>
				<b>Your E-Card has been sent!</b>
				<br><br>
				<b>Recipient: </b><?=$sRName?><br>
				<?=$sREmail?>
			</td>
		</tr>
		</table>
		<br>
		<?php 
	}
	//************************************************************************************
?>
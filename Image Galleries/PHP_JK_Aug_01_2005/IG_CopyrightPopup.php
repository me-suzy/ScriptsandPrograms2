<?php
	Require("Includes/i_Includes.php");
	DB_OpenDomains();
	DB_OpenImageGallery();
	INIT_LoginDetect();

	// Cannot put this in a Main() function because the CSS that is included below must have access to the global color variables
		
		$iCopyrightUnq	= Trim(Request("iCopyrightUnq"));
		$sDetails		= "";
		$sCopyright		= "";

		If ( $iCopyrightUnq != "" )
		{			
			$sQuery			= "SELECT Copyright, Details FROM IGCopyrights (NOLOCK) WHERE CopyUnq = " . $iCopyrightUnq;
			$rsRecordSet	= DB_Query($sQuery);
			If ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				$sDetails	= Trim($rsRow["Details"]);
				$sCopyright	= Trim($rsRow["Copyright"]);
			}
		}
		?>
		<html>
		<head>
			<title><?=$sCopyright?></title>
			<?php Require("Includes/Nav/PHP_JK_CSS.php"); ?>
		</head>
		<body>
		<center>
		<table cellpadding=0 cellspacing=0 border=0 width=100% height=100%><tr><td align=center valign=center bgcolor=<?=$GLOBALS["BorderColor1"]?>>
		<table cellpadding=5 cellspacing=1 border=0 width=100% height=100%>
			<tr>
				<td valign=top bgcolor = <?=$GLOBALS["BGColor1"]?> width=100% height=100%>
					<font color=<?=$GLOBALS["TextColor1"]?>>
					<?=$sDetails?>
				</td>
			</tr>
			<tr>
				<td valign=top bgcolor=<?=$GLOBALS["BGColor1"]?>>
					<center><a href = "JavaScript:parent.self.close();" class='MediumNav1'>Close Window</a>
				</td>
			</tr>
		</table>
		</td></tr></table>
		</body>
		</html>
		<?php 

	DB_CloseDomains();
?>
<?php
	require("../Includes/i_Includes.php");
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
		$sA = Trim(Request("sA"));
		If ( $sA != "" ) {
			$sReferer = Request("sReferer");
			If ( $sA == "Y" )
			{
				setcookie("GAL1", "", -1, "/", $_SERVER["SERVER_NAME"], 0);
				setcookie("GAP1", "", -1, "/", $_SERVER["SERVER_NAME"], 0);
				setcookie("GAA1", "", -1, "/", $_SERVER["SERVER_NAME"], 0);
				setcookie("GAAuto1", "", -1, "/", $_SERVER["SERVER_NAME"], 0);
			}
			
			If ( Trim($sReferer) == "" ) {
				// go home on an emergency where neither the referrer nor return page have data
				?>
				<script language='JavaScript1.2' type='text/javascript'>
				
					document.location = "/";
				
				</script>
				<?php
			}Else{
				?>
				<script language='JavaScript1.2' type='text/javascript'>
				
					document.location = "<?=$sReferer?>";
				
				</script>
				<?php
			}
			ob_flush();
			exit;
		}
		WriteForm();
	}
	//************************************************************************************	
	
	
	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $aVariables;
		Global $aValues;
		Global $iTableWidth;
		?>
		<center>
		<br>
		<table cellpadding = 5 cellspacing = 5 border = 0 width=<?=$iTableWidth?> class='TablePage_Boxed'>
			<tr>
				<td align = center>
					<b>Are you sure you want to log out and disable your auto-login?</b>
				</td>
			</tr>
			<tr>
				<table cellpadding = 5 cellspacing = 0 border = 1 width=<?=$iTableWidth?> class='Table1'>
					<tr>
						<td align = center width = 50%>
							<?php 
							$aVariables[0] = "sA";
							$aVariables[1] = "sReferer";
							$aValues[0] = "Y";
							$aValues[1] = URLEncode(Request("sReferer"));
							?>
							<a href = "DisableAutoLogin.php?<?=DOMAIN_Link("G")?>" class='MediumNav1'>YES!</a>
						</td>
						<td align=center width = 50%>
							<?php 
							$aVariables[0] = "sA";
							$aValues[0] = "N";
							?>
							<a href = "DisableAutoLogin.php?<?=DOMAIN_Link("G")?>" class='MediumNav1'>NO!</a>
						</td>
					</tr>
				</table>
			</tr>
		</table>
		<br>
		<?php 
	}
	//************************************************************************************
?>

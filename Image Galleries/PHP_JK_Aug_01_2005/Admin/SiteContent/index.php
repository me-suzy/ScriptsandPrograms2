<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_ADMIN_DEFAULT.php");
	include("../../fckeditor/fckeditor.php") ;
	
	If ((ACCNT_ReturnRights("PHPJK_MCONF_UPDATE"))) {
		HeaderHTML();
		Main();
	}Else{
		$iRow = 1;
		WriteScripts();
		DOMAIN_Message("You must login with Domains rights.<br>", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		$sAction		= Trim(Request("sAction"));
		$sError			= "";
		$sSuccess		= "";
		
		If ( $sAction == "SaveChanges" ) {
			If ( ! ACCNT_ReturnRights("PHPJK_MCONF_UPDATE")) {
				$sError = "You must login with Domains rights.<br>";
			}Else{
				$sHOMEPAGE_CONTENT = Trim(Request("sHOMEPAGE_CONTENT"));

				$sQuery = "SELECT * FROM TextConstants (NOLOCK) WHERE TextConstants = 'HOMEPAGE_CONTENT'";
				$rsRecordSet = DB_Query($sQuery);
				If ( DB_NumRows($rsRecordSet) > 0 )
				{
					DB_Update ("UPDATE TextConstants SET Value = '" . SQLEncode($sHOMEPAGE_CONTENT) . "' WHERE TextConstants = 'HOMEPAGE_CONTENT'");
				}Else{
					DB_Insert ("INSERT INTO TextConstants (TextConstants,Value,Description,DomainUnq) VALUES ('HOMEPAGE_CONTENT', '" . SQLEncode($sHOMEPAGE_CONTENT) . "', 'Homepage content', 0)");
				}
				If ( $sSuccess == "" )
					$sSuccess = "Your changes have been saved.";
			}
		}
		
		If ( $sError != "" )
			DOMAIN_Message($sError, "ERROR");
		If ( $sSuccess != "" )
			DOMAIN_Message($sSuccess, "SUCCESS");
		
		WriteScripts();
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

		$sBGColor	= $GLOBALS["BGColor2"];
		$sTextColor	= $GLOBALS["TextColor2"];
		
		$sQuery = "SELECT * FROM TextConstants (NOLOCK) WHERE TextConstants = 'HOMEPAGE_CONTENT'";
		$rsRecordSet = DB_Query($sQuery);
		If ( $rsRow = DB_Fetch($rsRecordSet) )
			$sHOMEPAGE_CONTENT = $rsRow["Value"];
		
		?>
		<center>
		<form name='ManageSiteContent' action='index.php' method='post'>
		<?php 
		$aVariables[0] = "sAction";
		$aValues[0] = "SaveChanges";
		Echo DOMAIN_Link("P");
		
		DOMAIN_Link_Clear();
		 ?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Manage Site Content</b></font>
					<br><br>
					<b>Enter your art and text here to be placed on the top of the gallery homepage.</b>
					<?php
					// fckeditor is VERY sensitive of the url and it can't have two /'s in it. so if the admin entered
					//	something like http://www.asb.com/ and /fckeditor/ then it'd be http://www.asb.com//fckeditor
					//	and it wouldn't work. And you can't just do a str_replace on // because it'll remove the one
					//	at the end of the http://
					$sBasePath = DOMAIN_Conf("IMAGEGALLERY_SITEURL");
					If ( $sBasePath[strlen($sBasePath)-1] == "/" )
					{
						$sBasePath = DOMAIN_Conf("IMAGEGALLERY_SITEURL") . "fckeditor/";
					}Else{
						$sBasePath = DOMAIN_Conf("IMAGEGALLERY_SITEURL") . "/fckeditor/";
					}
					
					$ofckeditor = new fckeditor('sHOMEPAGE_CONTENT') ;
					$ofckeditor->BasePath	= $sBasePath ;
					$ofckeditor->Value		= $sHOMEPAGE_CONTENT;
					$ofckeditor->Create() ;
					?>
				</td>
			</tr>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		</form>
		<?php 
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This writes the JavaScript out even if the Main() Sub isn't called in case the	*
	//*		admin has creation rights, but not edit rights.								*
	//*																					*
	//************************************************************************************
	Function WriteScripts()
	{
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.ManageSiteContent.sAction.value = sAction;
				document.ManageSiteContent.submit();
			}
			
		</script>
		<?php 
	}
	//************************************************************************************
	
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//************************************************************************************
	Function HeaderHTML()
	{
		Global $aVariables;
		Global $aValues;
		
		?>
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=4 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=4></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<?php
				If (ACCNT_ReturnRights("PHPJK_MCONF_UPDATE")) {
					Echo "<td bgcolor=FFFFFF width=1><input type='image' name='update' SRC='../../Images/Administrative/UpdateConfiguration.gif' ALIGN='absmiddle' Width=31 Height=44 Border=0 Alt='Save changes to site content or emails.' onClick='SubmitForm(\"SaveChanges\")'></td>";
				}Else{
					Echo "<td bgcolor=FFFFFF width=1>&nbsp;</td>";
				}
				?>
				<td bgcolor=FFFFFF width=50><img src='../../Images/Blank.gif' Width=50 Height=2></td>
				<td bgcolor=FFFFFF width=100%>&nbsp;</td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
				<td colspan=4 bgcolor=FFFFFF width=23><img src='../../Images/Blank.gif' Width=23 Height=2></td>
				<td bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=1 Height=2></td>
			</tr>
			<tr>
				<td colspan=6 bgcolor=<?=$GLOBALS["BorderColor2"]?>><img src='../../Images/Blank.gif' Width=2 Height=1></td>
			</tr>
		</table>
		<?php
	}
	//************************************************************************************
 ?>
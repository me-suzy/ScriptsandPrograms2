<?php
	Require("../../Includes/i_Includes.php");
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_OPEN.php");
	Require("../../Includes/Nav/PHP_JK_GALLERY_ADMIN_DEFAULT.php");
	
	$sAction			= Request("sAction");
	$iTtlNumItems		= Trim(Request("iTtlNumItems"));
	$iDBLoc				= Trim(Request("iDBLoc"));
	$iParentUnq			= Trim(Request("iParentUnq"));
	
	$iTemp = DOMAIN_Conf("IMAGEGALLERY_SCRIPTTIMEOUT");
	If ( $iTemp = "" )
		$iTemp = 5400;
	set_time_limit($iTemp);
	
	WriteScripts();
	
	If ( $iParentUnq == "" )
		$iParentUnq = "0";
	
	If ( ACCNT_ReturnRights("PHPJK_IG_EDIT_CAT") ) {
		HeaderHTML();
		Main();
	}Else{
		DOMAIN_Message("You must login with Image Gallery System rights.", "ERROR");
	}
	Require("../../Includes/Nav/PHP_JK_GALLERIES_ADMIN_CLOSE.php");
	
	
	//************************************************************************************
	//*																					*
	//*																					*
	//*																					*
	//************************************************************************************
	Function Main()
	{
		Global $sAction;
		
		If ( $sAction == "Recalculate" ) {
			// first, update the image totals for all categories w/ galleries in them (they could be either leaf or node categories)
			$sQuery = "SELECT CategoryUnq FROM IGCategories (NOLOCK)";
			$rsRecordSet	= DB_Query($sQuery);
			While ( $rsRow = DB_Fetch($rsRecordSet) )
			{
				//$sQuery = "SELECT Count(*) AS TtlImages, C.CategoryUnq FROM IGCategories C (NOLOCK), ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK) WHERE G.GalleryUnq = IG.GalleryUnq AND G.CategoryUnq = C.CategoryUnq GROUP BY C.CategoryUnq";
				$sQuery = "SELECT Count(*) AS TtlImages FROM IGCategories C (NOLOCK), ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK) WHERE G.GalleryUnq = IG.GalleryUnq AND G.CategoryUnq = C.CategoryUnq AND G.CategoryUnq = " . $rsRow["CategoryUnq"];
				$rsRecordSet2	= DB_Query($sQuery);
				While ( $rsRow2 = DB_Fetch($rsRecordSet2) ){
					DB_Update ("UPDATE IGCategories SET TtlImages = " . $rsRow2["TtlImages"] . " WHERE CategoryUnq = " . $rsRow["CategoryUnq"]);
				}
			}
			
			// second, go through each category
			RecurseCategories(0);
			
			DOMAIN_Message("Successfully finished recalculating category totals!", "SUCCESS");
		}
		
		WriteForm();
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function RecurseCategories($iCategoryUnq)
	{
		$sQuery = "SELECT CategoryUnq FROM IGCategories (NOLOCK) WHERE Parent = " . $iCategoryUnq;
		$rsRecordSet2	= DB_Query($sQuery);
		While ( $rsRow2 = DB_Fetch($rsRecordSet2) )
		{
			$iLocCatUnq = $rsRow2["CategoryUnq"];
			
			RecurseCategories($iLocCatUnq);
			
			// get the current categories galleries total so that we can add it to it's children's galleries totals...otherwise,
			//	we'll sum the children's galleries totals and overwrite the current categories galleries total.
			$iCatTotal = 0;
			$sQuery = "SELECT Count(*) AS TtlImages FROM IGCategories C (NOLOCK), ImagesInGallery IG (NOLOCK), Galleries G (NOLOCK) WHERE G.GalleryUnq = IG.GalleryUnq AND G.CategoryUnq = C.CategoryUnq AND G.CategoryUnq = " . $iLocCatUnq;
			$rsRecordSet3	= DB_Query($sQuery);
			If ( $rsRow3 = DB_Fetch($rsRecordSet3) )
				$iCatTotal = $rsRow3["TtlImages"];

			$sQuery = "SELECT SUM(TtlImages) AS CatTtl FROM IGCategories (NOLOCK) WHERE Parent = " . $iLocCatUnq;
			$rsRecordSet3	= DB_Query($sQuery);
			If ( $rsRow3 = DB_Fetch($rsRecordSet3) )
				$iCatTotal = $iCatTotal + $rsRow3["CatTtl"];

			DB_Update ("UPDATE IGCategories SET TtlImages = " . $iCatTotal . " WHERE CategoryUnq = " . $iLocCatUnq);
		}
	}
	//************************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This is the function to write out the form.										*
	//*																					*
	//************************************************************************************
	Function WriteForm()
	{
		Global $iParentUnq;
		Global $iTtlNumItems;
		Global $iDBLoc;
		
		$sBGColor = $GLOBALS["BGColor2"];
		?>
		<script language='JavaScript1.2' type='text/javascript'>
	
			function SubmitForm(sAction){
				document.Recalculate.sAction.value = sAction;
				document.Recalculate.submit();
			}
			
		</script>
		<form name='Recalculate' action='Recalculate.php' method='post'>
		<input type='hidden' name='iTtlNumItems' value='<?=$iTtlNumItems?>'>
		<input type='hidden' name='iDBLoc' value='<?=$iDBLoc?>'>
		<input type='hidden' name='iParentUnq' value='<?=$iParentUnq?>'>
		<input type='hidden' name='sAction' value='Recalculate'>
		
		<table cellpadding=0 cellspacing=0 border=0 width=671>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
			<tr>
				<td>
					<font color='<?=$GLOBALS["PageText"]?>'>
					<font size=+1><b>Recalculate Category Image Counts</b></font>
					<br><br>
					This will recalculate the total image counts for each category in the system. If you have a lot
					of categories, this may take a while. Running this multiple times is ok.
					<br><br>
					<input type='button' value='Click Here to Begin' onClick='SubmitForm("Recalculate");'>
				</td>
			</tr>
			<tr><td><img src='../../Images/Blank.gif' Width=1 Height=5></td></tr>
		</table>
		</form>
		<?php 
	}
	//********************************************************************************
	
	
	//************************************************************************************
	//*																					*
	//*	This writes the JavaScript out even if the Main() Function isn't called in case the	*
	//*		admin has creation rights, but not edit rights.								*
	//*																					*
	//************************************************************************************
	Function WriteScripts()
	{
		Global $iTtlNumItems;
		Global $iDBLoc;
		Global $iParentUnq;
		?>
		<SCRIPT LANGUAGE=javascript>
		<!--
			
			function ReturnToMain(){
				document.location = "index.php?<?=DOMAIN_Link("G")?>&iParentUnq=<?=$iParentUnq?>&iTtlNumItems=<?=$iTtlNumItems?>&iDBLoc=<?=$iDBLoc?>";
			}
			
		-->
		</SCRIPT>
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
				If ( ( ACCNT_ReturnRights("PHPJK_IG_ADMIN_ALL") ) || ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") ) ) {
					Echo "<td bgcolor=FFFFFF width=1><a href='JavaScript:ReturnToMain();'><img src='../../Images/Administrative/Return.gif' Width=30 Height=38 Border=0 Alt=''></a></td>";
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
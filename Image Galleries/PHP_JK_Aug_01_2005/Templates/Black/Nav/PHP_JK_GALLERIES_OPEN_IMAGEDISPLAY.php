<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
	<TITLE><?=htmlentities(substr($sKeywords,0,100))?></TITLE>
	<META name="Keywords" Content="<?=htmlentities(str_replace("--",",",$sKeywords))?>">
	<META name="Description" Content="PHPJK.com">
	<meta http-equiv="Content-Language" content="en-us">
	<meta name="revisit-after" content="2 days" />
	<META NAME="Author" CONTENT="USMKB.com">
	<META NAME="Date" CONTENT="2004-09-15">
	<META NAME="Copyright" CONTENT="USMKB.com">
	<META HTTP-EQUIV="Expires" CONTENT="Sat, 1 Jan 2000 00:00:00 GMT"> 
	<META HTTP-EQUIV="Pragma" CONTENT="no-cache"> 
	<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache"> 
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
	<META NAME="robots" CONTENT="INDEX, FOLLOW">
	<!--<?=htmlentities(str_replace("--",",",$sKeywords))?>-->
	<?=$sHeader?>
	<LINK REL=STYLESHEET TYPE="text/css" HREF="<?=$sSiteURL?>/Templates/<?=$sTemplates?>/TemplateStyles.css">
</HEAD>
<BODY>
	<center>
	<table cellpadding=0 cellspacing=0 border=0 width=<?=$iTableWidth?> class='HeadFoot'>
		<tr>
			<form action='<?=$sSiteURL?>/Search/PrepareResults.php?sAction=1'>
			<td width=20><img src='<?=$sSiteURL?>/Images/Blank.gif' Height=20></td>
			<td width=10><a href='/' class='MediumNav3'>Home</a></td>
			<td width=20><img src='<?=$sSiteURL?>/Images/Blank.gif' Height=20></td>
			<td width=10><a href='<?=$sSiteURL?>/' class='MediumNav3'>Gallery</a></td>
			<td width=20><img src='<?=$sSiteURL?>/Images/Blank.gif' Height=20></td>
			<td width=10>
				<?php
				If ( ACCNT_ReturnRights("PHPJK_IG_CREATE_GALLERY") )
					Echo "<a href='" . $sSiteURL . "/Admin/ManageGalleries/index.php' class='MediumNav3'>Manage</a>";
				?>
			</td>
			<td width=100% align=right>
				<table cellpadding=0 cellspacing=0 border=0><tr><td>
				<?php
				If ( $bHasAccount ) {
					Echo "<td><a href='" . $sSiteURL . "/UserArea/UserData/index.php' class='MediumNav3'>Preferences</a>";
				}Else{
					Echo "<td><a href='" . $sSiteURL . "/UserArea/NewAccounts/index.php' class='MediumNav3'>New&nbsp;Account</a>";
				}
				?>
				</td>
				<td width=20><img src='<?=$sSiteURL?>/Images/Blank.gif' Height=20></td>
				<td>
				<?php
				If ( $bHasAccount ) {
					Echo "<td><a href='" . $sSiteURL . "/UserArea/Logout.php' class='MediumNav3'>Logout</a>";
				}Else{
					Echo "<td><a href='" . $sSiteURL . "/UserArea/Login.php' class='MediumNav3'>Login</a>";
				}
				?>
				</td></tr></table>
			</td>
			<td width=20><img src='<?=$sSiteURL?>/Images/Blank.gif' Height=20></td>
			<td align=right>
			<?php
			Echo "<table cellpadding=0 cellspacing=0 border=0><tr><td><input type='text' name='sKeywords' value='' size=10 maxlength=255>";
			Echo "</td><td>";
			Echo "<input type='image' src=\"" . G_STRUCTURE_DI("Search.gif", $GLOBALS["SCHEMEBASED"]) . "\" style=\"BORDER: none; vertical-align: sub;\" Alt='Search for images'>";
			Echo "</td></tr></table>";
			?>
			</td>
			<td width=6><img src='<?=$sSiteURL?>/Images/Blank.gif' Height=6></td>
			<?php
			Echo "</form>";
			?>
		</tr>
	</table>
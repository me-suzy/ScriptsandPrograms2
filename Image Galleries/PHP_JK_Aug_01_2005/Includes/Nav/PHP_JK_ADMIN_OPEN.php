<?php
Global $bHasAccount;
Global $sSiteURL;
DB_OpenDomains();
DB_OpenImageGallery();
INIT_LoginDetect();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
	<TITLE>Domains Administration</TITLE>
	<?php require("PHP_JK_CSS.php"); ?>
	<http-equiv="pragma" content="NO-CACHE">
	<META HTTP-EQUIV='Keywords' Content='www.phpjk.com, message boards, free message board, free message boards, ASP message board, message board software, free image gallery, asp image gallery, image gallery software, free mp3 catalog, www.phpjk.com'>
	<META HTTP-EQUIV='Description' Content='www.phpjk.com, Free message board, ASP message board, message board software, free image gallery, asp image gallery, image gallery software, free mp3 catalog, community website, polls, mailing lists, account management and more! Uses ASP and MSSQL.'>
	<script language = "javascript">

		function toForm() {
			if (document.forms.length > 0) {
				for (var i = 0; i < document.forms[0].elements.length; i++) 
				{
					if ( document.forms[0].elements[i].type == "text" || document.forms[0].elements[i].type == "textarea" )
					{
						document.forms[0].elements[i].focus();
						i = document.forms[0].elements.length + 1;
					}
				}
			}
		}

	</script>
</HEAD>
<BODY onLoad="toForm();" BGCOLOR='#<?=$PageBGColor?>' TEXT='#<?=$TextColor1?>' TOPMARGIN=3 LEFTMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
	<center>
	<table cellpadding=0 cellspacing=0 border=0 width=<?=$iTableWidth?>>
		<tr><td colspan=8 bgcolor=<?=$BorderColor2?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1></td></tr>
		<tr>
			<td width=1 bgcolor=<?=$BorderColor2?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1 Height=26></td>
			<td width=26 bgcolor=<?=$BGColor3?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Height=26></td>
			<td bgcolor=<?=$BGColor3?> width=10><a href='/' class='LargeNav3'>Home</a></td>
			<td width=26 bgcolor=<?=$BGColor3?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Height=26></td>
			<td bgcolor=<?=$BGColor3?> width=10><a href='<?=$sSiteURL?>/' class='LargeNav3'>Gallery</a></td>
			<td width=100% bgcolor=<?=$BGColor3?> align=right>
				<table cellpadding=0 cellspacing=0 border=0><tr><td>
				<?php
				If ( $bHasAccount ) {
					Echo "<td><a href='" . $sSiteURL . "/UserArea/UserData/index.php' class='MediumNav3'>Preferences</a>";
				}Else{
					Echo "<td><a href='" . $sSiteURL . "/UserArea/NewAccounts/index.php' class='MediumNav3'>New&nbsp;Account</a>";
				}
				?>
				</td>
				<td width=26 bgcolor=<?=$BGColor3?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Height=26></td>
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
			<td width=26 bgcolor=<?=$BGColor3?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Height=26></td>
			<td width=1 bgcolor=<?=$BorderColor2?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1 Height=26></td>
		</tr>
		<tr><td colspan=8 bgcolor=<?=$BorderColor2?>><img src='<?=$sSiteURL?>/Images/Blank.gif' Width=1></td></tr>
	</table>
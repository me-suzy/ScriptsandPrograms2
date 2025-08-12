<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
  <tr>
    <td width="74%"><? if($pagetitle){ echo "$pagetitle<br />"; } ?>
	<? if($settings[site_welcome]){ echo "$settings[site_welcome]<br /><br />"; } ?>
	<? if($no_results){ echo "$no_results"; } ?>
	<? mainPage(); ?></td>
    <td width="6%" rowspan="3" valign="top">&nbsp;</td>
    <td width="20%" rowspan="3" valign="top"><? include("sidemenu.php"); ?></td>
  </tr>
  <tr>
    <td><? if($numrows){ include ("admin/navbar.php");	} ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

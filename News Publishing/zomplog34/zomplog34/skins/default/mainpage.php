<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
  <tr>
    <td width="65%"><? if($pagetitle){ echo "$pagetitle<br />"; } ?>
	<? if($no_results){ echo "$no_results"; } ?>
	<? if($settings[site_welcome]){ echo "$settings[site_welcome]<br /><br />"; } ?>
	<? mainPage(); ?></td>
    <td width="2%" rowspan="3" valign="top">&nbsp;</td>
    <td width="33%" rowspan="3" valign="top"><? include("sidemenu.php"); ?></td>
  </tr>
  <tr>
    <td><? if($numrows){ include ("admin/navbar.php");	} ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>

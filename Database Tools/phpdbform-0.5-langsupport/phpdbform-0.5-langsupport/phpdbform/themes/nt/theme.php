<?php
require_once( "phpdbform/phpdbform_theme.php" );
class phpdbform_theme extends phpdbform_theme_base
{
	var $name = "nt";
	function phpdbform_theme_nt()
	{
		$this->phpdbform_theme_base();
	}

	function draw_menu()
	{
		global $phpdbform_main;
?>
<table border="0" cellpadding="2" cellspacing="0" width="95%" height="100%" align="center">
<tr><td class="conteudo" valign="top">
<?php
		if( $phpdbform_main->access->check_login(0) )
		{
			$menu = $phpdbform_main->menu->get_menu($phpdbform_main->access->get_level());
			reset( $menu );
			while( $item = each($menu) )
			{
				print "<strong>{$item[0]}</strong><br>\n";
				while( $subitem = each($item[1]) )
				{
					print "<a href=\"{$subitem[1]}\">{$subitem[0]}</a><br>\n";
				}
				print "<br>";
			}
			print "<a href=\"index.php?act=logout\"><strong>Logout</strong></a><br>\n";
		} else print "&nbsp;";
?>
</td></tr></table>
<?php
	}

// header code expects any code that you want to put in the header tag
// like javascript code or stylesheets
	function draw_adm_header( $title )
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?php print $title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Generator" content="phpDBform">
	<link href="phpdbform/themes/nt/images/dbform.css" rel="stylesheet" type="text/css">
	<?php print $this->header_code; ?>
</head>
<body>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td align="center" valign="top" width="160"><br><br><?php $this->draw_menu(); ?></td>
<td align="center" valign="middle">
<table border="0" cellpadding="0" cellspacing="0" width="<?php print $this->width; ?>">
  <tr>
   <td width="8"><img src="phpdbform/themes/nt/images/spacer.gif" width="8" height="1" border="0" alt=""></td>
   <td width="18"><img src="phpdbform/themes/nt/images/spacer.gif" width="18" height="1" border="0" alt=""></td>
   <td width="100%"><img src="phpdbform/themes/nt/images/spacer.gif" width="288" height="1" border="0" alt=""></td>
   <td width="54"><img src="phpdbform/themes/nt/images/spacer.gif" width="54" height="1" border="0" alt=""></td>
   <td width="8"><img src="phpdbform/themes/nt/images/spacer.gif" width="8" height="1" border="0" alt=""></td>
  </tr>

  <tr>
   <td colspan="2"><img src="phpdbform/themes/nt/images/lgth_tp_esq.png" width="26" height="23" border="0" alt=""></td>
   <td background="phpdbform/themes/nt/images/lgth_tp_meio.png" class="titulo"><?php print $title; ?></td>
   <td colspan="2"><img src="phpdbform/themes/nt/images/lgth_tp_dir.png" width="62" height="23" border="0" alt="" usemap="#Menu"></td>
  </tr>
<tr>
   <td><img src="phpdbform/themes/nt/images/lgth_bts_eq.png" width="8" height="33" border="0" alt=""></td>
   <td colspan="3" background="phpdbform/themes/nt/images/lgth_bts_md.png" valign="middle">
   	<table border=0 cellpadding="4" cellspacing="0">
		<tr>
			<td><a href="index.php?act=logout"><img src="phpdbform/themes/nt/images/lgth_botao_cancelar.png" alt="Logout" width="21" height="25" border="0"></a></td>
			<td><a href="<?php print basename($_SERVER["PHP_SELF"]); ?>"><img src="phpdbform/themes/nt/images/lgth_botao_refresh.png" width="21" height="25" alt="Refresh" border="0"></a></td>
			<td><a href="index.php"><img src="phpdbform/themes/nt/images/lgth_botao_home.png" width="25" height="25" alt="Go to index" border="0"></a></td>
		</tr>
	</table>
   </td>
   <td><img src="phpdbform/themes/nt/images/lgth_bts_dr.png" width="8" height="33" border="0" alt=""></td>
  </tr>
  <tr>
   <td><img src="phpdbform/themes/nt/images/lgth_ct_tp_eq.png" width="8" height="8" border="0" alt=""></td>
   <td colspan="3" background="phpdbform/themes/nt/images/lgth_ct_tp_md.png"><img src="phpdbform/themes/nt/images/spacer.gif" width="4" height="8" border="0" alt=""></td>
   <td><img src="phpdbform/themes/nt/images/lgth_ct_tp_dr.png" width="8" height="8" border="0" alt=""></td>
  </tr>
  <tr>
   <td background="phpdbform/themes/nt/images/lgth_ct_eq.png"><img src="phpdbform/themes/nt/images/spacer.gif" width="8" height="128" border="0" alt=""></td>
   <td colspan="3" valign="top" class="conteudo"><table width="95%">
		<tr><td valign="top" class="conteudo">
<?php
	}

	function draw_adm_footer()
	{
?>
<br><br><hr><a href="http://www.phpdbform.com" target="_blank">phpDBform site</a>
</td></tr>
	</table></td>
   <td background="phpdbform/themes/nt/images/lgth_ct_dr.png"><img src="phpdbform/themes/nt/images/spacer.gif" width="8" height="128" border="0" alt=""></td>
  </tr>
  <tr>
   <td><img src="phpdbform/themes/nt/images/lgth_ct_bx_eq.png" width="8" height="8" border="0" alt=""></td>
   <td colspan="3" background="phpdbform/themes/nt/images/lgth_ct_bx_md.png"><img src="phpdbform/themes/nt/images/spacer.gif" width="1" height="8" border="0" alt=""></td>
   <td><img src="phpdbform/themes/nt/images/lgth_ct_bx_dr.png" width="8" height="8" border="0" alt=""></td>
  </tr>
</table>
</td></tr></table>
<map name="Menu">
<area alt="Logout" coords="40,6,56,20" href="index.php?act=logout">
</map>
</body>
</html>
<?php
	}
}
?>

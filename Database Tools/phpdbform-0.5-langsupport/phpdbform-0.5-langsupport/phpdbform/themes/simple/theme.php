<?php
require_once( "phpdbform/phpdbform_theme.php" );
class phpdbform_theme extends phpdbform_theme_base
{
	var $name = "simple";
	function phpdbform_theme_nt()
	{
		$this->phpdbform_theme_base();
	}

	function draw_menu()
	{
		global $phpdbform_main;
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
	}

// header code expects any code that you want to put in the header tag
// like javascript code or stylesheets
	function draw_adm_header( $title )
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title><?php print $title; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Generator" content="phpDBform">
	<link href="phpdbform/themes/simple/images/dbform.css" rel="stylesheet" type="text/css">
	<?php print $this->header_code; ?>
</head>
<body>
<div id="menu"><?php $this->draw_menu(); ?></div>
<div id="extmain">
<div id="topmain"><?php print $title; ?></div>
<div id="btmain" align="right"><a href="index.php?act=logout" title="Logout"><img src="phpdbform/themes/nt/images/lgth_botao_cancelar.png" alt="Logout" width="21" height="25" border="0"></a>
<a href="<?php print basename($_SERVER["PHP_SELF"]); ?>" title="Refresh"><img src="phpdbform/themes/nt/images/lgth_botao_refresh.png" width="21" height="25" alt="Refresh" border="0"></a>
<a href="index.php" title="Go to index"><img src="phpdbform/themes/nt/images/lgth_botao_home.png" width="25" height="25" alt="Go to index" border="0"></a></div>
<div id="dform">
<?php
	}

	function draw_adm_footer()
	{
?>
</div>
<div id="footnote"><a href="http://www.phpdbform.com" target="_blank" title="Visit phpDBform site for updates.">phpDBform site</a></div>
</div>
<br>
</body>
</html>
<?php
	}
}
?>

<?php
$main = "Place your FAQ HTML code in faq.php, here.  Remember to escape quotes.";
?>
<?php
//Ad Code
$adcode="
<script language='JavaScript' type='text/javascript' src='www.http://zetaproductions.com/ads/adx.js'></script>
<script language='JavaScript' type='text/javascript'>
<!--
   if (!document.phpAds_used) document.phpAds_used = ',';
   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
   
   document.write (\"<\" + \"script language='JavaScript' type='text/javascript' src='\");
   document.write (\"http://www.zetaproductions.com/ads/adjs.php?n=\" + phpAds_random);
   document.write (\"&amp;what=zone:1&amp;source=zihs\");
   document.write (\"&amp;exclude=\" + document.phpAds_used);
   if (document.referrer)
      document.write (\"&amp;referer=\" + escape(document.referrer));
   document.write (\"'><\" + \"/script>\");
//-->
</script><noscript><a href='http://www.zetaproductions.com/ads/adclick.php?n=a2ffa6dc' target='_blank'><img src='http://zetaproductions.com/ads/adview.php?what=zone:1&amp;source=zihs&amp;n=a2ffa6dc' border='0' alt=''></a></noscript>
";

//copyright
$copyright="Copyright &copy;2005 <a href=\"http://www.zihs.com\">ZIHS</a> - Script Coded by <a href=\"http://www.iron-muskie-inc.com\">Iron Muskie, Inc.</a>";

//display ad code and copyright
include_once('settings.php');
//Page Title
$page_title = ' - FAQ';
$main = $main . '<center><br />' . $adcode . '<br />' . $copyright . '</center>';
// put full path to Smarty.class.php
define('SMARTY_DIR', 'smartyfiles/Smarty/libs/');
require_once(SMARTY_DIR.'Smarty.class.php');
$smarty = new Smarty();

$smarty->template_dir = 'smarty/templates';
$smarty->compile_dir = 'smarty/templates_c';
$smarty->cache_dir = 'smarty/cache';
$smarty->config_dir = 'smarty/configs';

$smarty->assign('title', $site_title.$page_title);
$smarty->assign('heading', 'FAQ');
$smarty->assign('main', $main);
$smarty->assign('adcode', $adcode);
$smarty->assign('copyright', $copyright);
$smarty->display('index2.tpl');

?>
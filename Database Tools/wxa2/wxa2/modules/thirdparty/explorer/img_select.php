<?
$libdir="../../../";
?><head>
<script src=<?echo $libdir?>jss/general.js></script>
<link href=<?echo $libdir?>jss/style.css  type=text/css rel=stylesheet></head><body topmargin="0">
<?include($libdir . "modules/inc/global.inc.php");
include($libdir . "modules/thirdparty/explorer/explorer.class.php");
$explorer = new Explorer($libdir . "i/upload" .  $folder,$libdir . "i/upload". $folder);
$explorer->title = "Téléchargement";
$explorer->auth_mod_topdir=1;
$explorer->filelinelink="javascript:window.opener.imgSelect('--','','')";
$explorer->display_date=0;
$explorer->display_size=0;
$explorer->display_type=0;
?>
<table  bgcolor=efefef style="border:solid 1px <?echo $explorer->insidebordercolor?>">
<tr><td width=400><?
echo $explorer->toHTML();
echo $explorer->HTML_buttons();
echo $explorer->frm_new();
?></td></tr></table></body>
<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
require_once('./functions.php');
include_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
if(!language_avail($act_lang))
	faqe_die_asc("Language <b>$act_lang</b> not configured");
include_once('./language/lang_'.$act_lang.'.php');
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
?>
<html>
<head>
<?php
if(file_exists("metadata.php"))
	include_once("./metadata.php");
else
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$contentcharset\">";
if($show_proglist==0)
	unset($limitprog);
$commonparams="$langvar=$act_lang&navframe=$navsync";
if(isset($layout))
	$commonparams="&layout=$layout";
if(isset($programm))
	$commonparams.="&programm=$programm";
if(isset($category))
	$commonparams.="&category=$category";
if(isset($subcategory))
	$commonparams.="&subcategory=$subcategory";
if(isset($expanded))
	$commonparams.="&expanded=$expanded";
if(isset($limitprog))
	$commonparams.="&limitprog=$limitprog";
if(isset($catnr))
	$commonparams.="&catnr=$catnr";
if(isset($subcatnr))
	$commonparams.="&subcatnr=$subcatnr";
if(isset($kbnr))
	$commonparams.="&kbnr=$kbnr";
$contentlink="kb.php?".$commonparams;
if(isset($os))
	$contentlink.="&os=$os";
if(isset($progversion))
	$contentlink.="&progversion=$progversion";
if(isset($prog))
	$contentlink.="&prog=$prog";
else if(isset($limitprog))
	$contentlink.="&prog=$limitprog";
if(isset($mode))
	$contentlink.="&mode=$mode";
if(isset($highlight))
	$contentlink.="&highlight=$highlight";
if(isset($backurl))
	$contentlink.="&backurl=".urlencode($backurl);
$navbarlink="kbnav.php?".$commonparams;
if(isset($prog))
	$navbarlink.="&prog=$prog";
?>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta name="fid" content="022a9b32a909bf2b875da24f0c8f1225">
</head>
<?php
if($navtreepos==0)
{
?>
<!-- frames -->
<frameset cols="<?php echo $navbarwidth?>,*" border="0">
    <frame name="kbnavbar" src="<?php echo $navbarlink?>" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0" noresize>
    <frame name="kbcontent" src="<?php echo $contentlink?>" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
</frameset>
<?php
}
else
{
?>
<!-- frames -->
<frameset cols="*,<?php echo $navbarwidth?>" border="0">
    <frame name="kbcontent" src="<?php echo $contentlink?>" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
    <frame name="kbnavbar" src="<?php echo $navbarlink?>" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0" noresize>
</frameset>
<?php
}
?>
</html>

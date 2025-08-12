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
	$commonparams.="&layout=$layout";
if(isset($expanded))
	$commonparams.="&expanded=$expanded";
if(isset($limitprog))
	$commonparams.="&limitprog=$limitprog";
if(isset($catnr))
	$commonparams.="&catnr=$catnr";
if(isset($faqnr))
	$commonparams.="&faqnr=$faqnr";
if(isset($subcatnr))
	$commonparams.="&subcatnr=$subcatnr";
if(isset($special) && ($special=="question"))
{
	if(isset($question))
		$commonparams.="&question=$question";
	$contentlink="question.php?mode=read&".$commonparams;
	if(isset($backurl))
		$contentlink.="&backurl=".urlencode($backurl);
}
else
	$contentlink="faq.php?".$commonparams;
if(isset($prog))
	$contentlink.="&prog=$prog";
else if(isset($limitprog))
	$contentlink.="&prog=$limitprog";
if(isset($list))
	$contentlink.="&list=$list";
if(isset($onlynewfaq))
	$contentlink.="&onlynewfaq=$onlynewfaq";
if(isset($display))
	$contentlink.="&display=$display";
if(isset($highlight))
	$contentlink.="&highlight=$highlight";
$navbarlink="faqnav.php?".$commonparams;
if(isset($prog))
	$navbarlink.="&prog=$prog";
if(isset($list) && ($list=="questions"))
	$navbarlink.="&qlink=1";
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
    <frame name="faqnavbar" src="<?php echo $navbarlink?>" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0" noresize>
    <frame name="faqcontent" src="<?php echo $contentlink?>" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
</frameset>
<?php
}
else
{
?>
<!-- frames -->
<frameset cols="*,<?php echo $navbarwidth?>" border="0">
    <frame name="faqcontent" src="<?php echo $contentlink?>" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
    <frame name="faqnavbar" src="<?php echo $navbarlink?>" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0" noresize>
</frameset>
<?php
}
?>
</html>

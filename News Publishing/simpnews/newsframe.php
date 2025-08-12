<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
if($heading)
	$pageheading=$heading;
else
	$pageheading=$l_news;
switch($catframenewslist)
{
	case 1:
		$newsscript = "news4.php";
		break;
	case 2:
		$newsscript = "news5.php";
		if(!isset($sortorder))
			$sortorder=1;
		break;
	default:
		$newsscript = "news.php";
		break;
}
if(!isset($sortorder))
	$sortorder=0;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="SimpNews v<?php echo $version?>, <?php echo $copyright_asc?>">
<meta name="fid" content="<?php echo $fid?>">
<?php
if(file_exists("metadata.php"))
	include ("metadata.php");
else
{
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $pageheading?></title>
<?php
}
?>
</head>
<!-- frames -->
<frameset cols="<?php echo $clwidth?>,*" border="0">
    <frame name="catlist" src="catlist.php?<?php echo "$langvar=$act_lang"?>&catframe=1&layout=<?php echo $layout?>&sortorder=<?php echo $sortorder?>" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0" noresize>
    <frame name="newscontent" src="<?php echo $newsscript?>?<?php echo "$langvar=$act_lang"?>&category=0&catframe=1&layout=<?php echo $layout?>&sortorder=<?php echo $sortorder?>" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
</frameset>
</html>

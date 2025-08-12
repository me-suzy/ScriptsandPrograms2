<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($category))
	$category=0;
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
if(is_ns4() && $ns4style)
	echo"<link rel=stylesheet href=\"$ns4style\" type=\"text/css\">\n";
else if(is_ns6() && $ns6style)
	echo"<link rel=stylesheet href=\"$ns6style\" type=\"text/css\">\n";
else if(is_opera() && $operastyle)
	echo"<link rel=stylesheet href=\"$operastyle\" type=\"text/css\">\n";
else if(is_konqueror() && $konquerorstyle)
	echo"<link rel=stylesheet href=\"$konquerorstyle\" type=\"text/css\">\n";
else if(is_gecko() && $geckostyle)
	echo"<link rel=stylesheet href=\"$geckostyle\" type=\"text/css\">\n";
else
	echo"<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">\n";
include_once('./includes/styles.inc');
$baseurl=$newsscript."?$langvar=$act_lang&catframe=1&sortorder=$sortorder";
?>
</head>
<body bgcolor="<?php echo $pagebgcolor?>" text="<?php echo $contentfontcolor?>" <?php echo $addbodytags?>>
<div align=<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" class="sntable" align="<?php echo $tblalign?>">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $clheadingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $clheadingfont?>" size="<?php echo $clheadingfontsize?>" color="<?php echo $clheadingfontcolor?>"><b><?php echo $l_catlist?></b></font></td></tr>
<tr bgcolor="<?php echo $clcontentbgcolor?>"><td align="left">
<?php
$catlink="$baseurl&layout=$layout&category=0";
?>
<a class="catlistlink" href="<?php echo $catlink?>" target="newscontent">
<font size="<?php echo $clcontentfontsize?>" face="<?php echo $clcontentfont?>" color="<?php echo $clcontentfontcolor?>">
<?php echo $l_general?></font></a></td></tr>
<?php
$sql = "select * from ".$tableprefix."_categories where hideincatlist=0 order by displaypos asc";
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
while($myrow=mysql_fetch_array($result))
{
	$catlink=$baseurl;
	if($myrow["newsframelayout"])
		$catlink.="&layout=".$myrow["newsframelayout"];
	else
		$catlink.="&layout=$layout";
	$catlink.="&category=".$myrow["catnr"];
	$cattext=display_encoded(stripslashes($myrow["catname"]));
	$tmpsql="select * from ".$tableprefix."_catnames where catnr=".$myrow["catnr"]." and lang='".$act_lang."'";
	if(!$tmpresult=mysql_query($tmpsql,$db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if($tmprow=mysql_fetch_array($tmpresult))
	{
		if(strlen($tmprow["catname"])>0)
			$cattext=display_encoded(stripslashes($tmprow["catname"]));
	}
?>
<tr bgcolor="<?php echo $clcontentbgcolor?>"><td align="left">
<a class="catlistlink" href="<?php echo $catlink?>" target="newscontent">
<font size="<?php echo $clcontentfontsize?>" face="<?php echo $clcontentfont?>" color="<?php echo $clcontentfontcolor?>">
<?php echo $cattext?></font></a></td></tr>
<?php
}
?>
</table></td></tr></table></body></html>
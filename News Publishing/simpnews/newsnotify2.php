<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$path_simpnews=dirname(__FILE__);
require_once($path_simpnews.'/config.php');
require_once($path_simpnews.'/functions.php');
if(!isset($category))
	$category=0;
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include($path_simpnews.'/language/lang_'.$act_lang.'.php');
include($path_simpnews.'/includes/get_settings.inc');
include($path_simpnews.'/includes/styles2.inc');
$sql = "select * from ".$tableprefix."_misc";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
if ($myrow = mysql_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	exit;
}
?>
<div align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth2?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" class="sntable">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
$actdate = date("Y-m-d 23:59:59");
if(bittst($announceoptions,BIT_11))
{
		$acttime=transposetime(time(),$servertimezone,$displaytimezone);
		$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
		if(isset($cookiedate))
			$tmpsql.= "and date >= '$cookiedate' ";
		if($separatebylang==1)
			$tmpsql.="and lang='$act_lang' ";
		if($category>0)
			$tmpsql.= "and (category='$category' or category=0)";
		else if($category==0)
			$tmpsql.= "and category=0";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("Unable to connect to database.".mysql_error());
		$numentries=mysql_num_rows($tmpresult);
		if($numentries>0)
		{
?>
<tr bgcolor="<?php echo $tablebgcolor?>"><td align="center">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<a href="<?php echo $url_simpnews?>/announce.php?<?php echo "$langvar=$act_lang"?>&amp;layout=<?php echo $layout?>&amp;category=<?php echo $category?>">
<?php
			echo "<img src=\"$url_gfx/$announcepic\" border=\"0\" align=\"absmiddle\"> $l_announcements";
		}
}
$sql = "select * from ".$tableprefix."_data ";
$firstarg=1;
if($category>=0)
	$sql.="where category='$category' ";
else
{
	$sql.="where linknewsnr=0 ";
	$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("Unable to connect to database.".mysql_error());
	while($tmprow=mysql_fetch_array($tmpresult))
		$sql.="and category!=".$tmprow["catnr"]." ";
}
if($showfuturenews==0)
	$sql.="and date<='$actdate' ";
if($separatebylang==1)
	$sql.="and lang='$act_lang' ";
if(isset($cookiedate))
	$sql.= "and date >= '$cookiedate' ";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.");
$numentries=mysql_numrows($result);
?>
<tr bgcolor="<?php echo $tablebgcolor?>"><td align="center">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<a href="<?php echo $url_simpnews?>/news.php?<?php echo "$langvar=$act_lang"?>&amp;layout=<?php echo $layout?>&amp;category=<?php echo $category?>">
<?php
if($numentries>0)
	echo "<img src=\"$url_gfx/$newssignal_on\" border=\"0\" align=\"absmiddle\"> $l_newnews";
else
	echo "<img src=\"$url_gfx/$newssignal_off\" border=\"0\" align=\"absmiddle\"> $l_nonewnews";
?>
</a></font></td></tr></table></td></tr></table></div>

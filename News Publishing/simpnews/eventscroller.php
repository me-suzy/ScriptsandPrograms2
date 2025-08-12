<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$path_simpnews=dirname(__FILE__);
require_once($path_simpnews.'/config.php');
require_once($path_simpnews.'/functions.php');
if(!isset($category))
	$category=0;
if(!isset($sortorder))
	$sortorder=1;
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include($path_simpnews.'/language/lang_'.$act_lang.'.php');
include($path_simpnews.'/includes/get_settings.inc');
$sql = "select * from ".$tableprefix."_misc";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
		include_once($path_simpnews.'/includes/styles2.inc');
?>
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" class="sntable">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if(strlen($heading)>0)
{
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $heading?></b></font></td></tr>
<?php
}
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $contentbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</font></td></tr></table></td></tr></table>";
		echo "</td></tr></table>";
		include($path_simpnews.'/includes/footer2.inc');
		echo "</body></html>";
		exit;
	}
}
if(isset($download))
{
	header('Content-Type: application/octetstream');
	header('Content-Disposition: filename="eventscroller_'.$act_lang.'.ht"');
}
$acttime=transposetime(time(),$servertimezone,$displaytimezone);
if(!isset($limitdays) || ($limitdays==0))
	$displaydate=date($newsscrollerdateformat,$acttime);
else
{
	$displaydate=date($newsscrollerdateformat,$acttime);
	$today=getdate($acttime);
	$datedays=dateToJuliandays($today["mday"],$today["mon"],$today["year"]);
	$enddate=juliandaysToDate($datedays+$limitdays,$newsscrollerdateformat);
	$displaydate.=" - $enddate";
}
if(!isset($scrolltype))
	$scrolltype=$newsscrollertype;
if(!isset($limitentries))
	$limitentries=$newsscrollermaxentries;
if(!isset($limitdays))
	$limitdays=0;
$datasourceurl="$url_simpnews/eventscroller2.php?limitentries=$limitentries&scrolltype=$scrolltype&$langvar=$act_lang&layout=$layout&category=$category&limitdays=$limitdays&sortorder=$sortorder";
if(isset($maxannounce))
	$datasourceurl.="&maxannounce=$maxannounce";
include_once($path_simpnews.'/includes/styles2.inc');
if($eventscrolleractdate==1)
{
?>
<div align="<?php echo $tblalign?>">
<table border="0" cellpadding="1" cellspacing="1" align="<?php echo $tblalign?>" width="<?php echo $TableWidth?>" class="sntable">
<tr><td align="center"><font face="<?php echo $contenfont?>" size="<?php echo $contentfontsize?>">
<b><?php echo "$l_eventsfor $displaydate:"?></b></font></td></tr>
<tr><td align="center">
<?php
}
?>
<applet archive="<?php echo $url_simpnews?>/applet/scroller.jar"
	codebase="<?php echo $url_simpnews?>/applet"
	code="de.boesch_it.simpnews.scroller.NewsScroller"
	width="<?php echo $newsscrollerwidth?>"
	height="<?php echo $newsscrollerheight?>">
<param name="datasource" value="<?php echo $datasourceurl?>">
<param name="separator" value="<?php echo $sep_char?>">
<param name="scrollspeed" value="<?php echo $newsscrollerscrollspeed?>">
<param name="scrolldelay" value="<?php echo $newsscrollerscrolldelay?>">
<param name="pause" value="<?php echo $newsscrollerscrollpause?>">
<param name="scrolltype" value="<?php echo $scrolltype?>">
<param name="onmouseoverstop" value="<?php echo $newsscrollermousestop?>">
<param name="xoffset" value="<?php echo $newsscrollerxoffset?>">
<param name="yoffset" value="<?php echo $newsscrolleryoffset?>">
<param name="maxlines" value="<?php echo $newsscrollermaxlines+2?>">
<?php
if(isset($dodebug))
	echo "<param name=\"debugmode\" value=\"on\">";
if($newsscrollerwordwrap==1)
	echo "<param name=\"wordwrap\" value=\"on\">";
if($newsscrollerbgimage)
	echo "<param name=\"backgroundimage\" value=\"$newsscrollerbgimage\">";
if($newsscrollerfgimage)
	echo "<param name=\"foregroundimage\" value=\"$newsscrollerfgimage\">";
echo "</applet>";
if($eventscrolleractdate==1)
	echo "</td></tr></table>";
?>
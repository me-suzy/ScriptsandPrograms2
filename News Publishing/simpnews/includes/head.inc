<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(!isset($page))
	$page="";
if(!isset($usebbcode))
	$usebbcode=false;
if(isset($storeuserdata))
{
	$cookieexpire=time()+(365*24*60*60);
	if(isset($poster) && $poster)
		setcookie($cookiename."[username]",$poster,$cookieexpire,$url_simpnews,$cookiedomain,$cookiesecure);
	else
		setcookie($cookiename."[username]","",$cookieexpire,$url_simpnews,$cookiedomain,$cookiesecure);
	if(isset($email) && $email)
		setcookie($cookiename."[usermail]",$email,$cookieexpire,$url_simpnews,$cookiedomain,$cookiesecure);
	else
		setcookie($cookiename."[usermail]","",$cookieexpire,$url_simpnews,$cookiedomain,$cookiesecure);
}
if($page=="proplist")
	$colspan="4";
else
	$colspan="2";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="SimpNews v<?php echo $version?> <?php echo $copyright_asc?>">
<meta name="fid" content="<?php echo $fid?>">
<?php
if(!isset($redirect))
	$redirect=0;
if(file_exists("metadata.php"))
	include ("metadata.php");
else
{
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $pageheading?></title>
<?php
}
if(($redirect==1) && ($redirectdelay>=0))
{
	if(isset($redirecturl))
		$target_url=$redirecturl;
	else if(isset($backurl))
		$target_url=$backurl;
	else
	{
		$target_url="news.php?$langvar=$act_lang&layout=$layout";
		if(isset($start))
			$target_url.="&start=$start";
	}
	echo "<META HTTP-EQUIV=\"refresh\" content=\"$redirectdelay; URL=$target_url\">\n";
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
if($page && file_exists('./includes/js/'.$page.'.inc'))
	include_once('./includes/js/'.$page.'.inc');
if($usebbcode)
	include_once('./includes/js/bbcode.inc');
?>
</head>
<body bgcolor="<?php echo $pagebgcolor?>" text="<?php echo $contentfontcolor?>" <?php echo $addbodytags?>>
<?php
if($usecustomheader==1)
{
	if(($headerfile) && ($headerfilepos==0))
	{
		if(is_phpfile($headerfile))
			include_once($headerfile);
		else
			file_output($headerfile);
		echo "\n";
		if(($customheader) && ($cheadnobr==0))
			echo "<br>\n";
	}
	if($customheader)
		echo "$customheader\n";
	if(($headerfile) && ($headerfilepos==1))
	{
		if(($customheader) && ($cheadnobr==0))
			echo "<br>\n";
		if(is_phpfile($headerfile))
			include_once($headerfile);
		else
			file_output($headerfile);
		echo "\n";
	}
}
?>
<div align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" class="sntable">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if(strlen($heading)>0)
{
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="<?php echo $colspan?>">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $heading?></b></font></td></tr>
<?php
}
$sql = "select * from ".$tableprefix."_misc";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $contentbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="<?php echo $colspan?>"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</font></td></tr></table></td></tr></table>";
		echo "</div>\n";
		include ("./includes/footer.inc");
		exit;
	}
}
?>
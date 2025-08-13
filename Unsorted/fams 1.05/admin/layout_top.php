<?php

header('Pragma: no-cache');
header('Expires: 0');

/* ------------------------------------------------------------ */
/*								*/
/*	File Area Management System (FAMS)			*/
/*								*/
/*	Copyright (c) 2001 by Bastian 'Buddy' Grimm		*/
/*	Autor: Bastian Grimm					*/
/*	Publisher: [ BG-Studios ]				*/
/*	eMail: bastian@bg-studios.de				*/
/*	WebSite: http://www.bg-studios.de			*/
/*	Date: 03.05.01		Version: Admin Layout 1.10	*/
/*	Last changed: 24.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("../config.php");

require("./lang.php");

if(!$nick)
{
include("layout_top_e.php");
echo $loginfailed;
include("layout_down_e.php");
exit;
}
else
{
	if(!$passwort)
	{
	include("layout_top_e.php");
	echo $loginfailed;
	include("layout_down_e.php");
	exit;
	}
	else
	{

	$logon = mysql_query("SELECT * FROM dl_users WHERE (user_name = '$nick') && (user_pwd = '$passwort')");
	$loggedon=mysql_fetch_array($logon);	
	if (!$loggedon)
	{
	include("layout_top_e.php");
	echo $loginfailed;
	include("layout_down_e.php");
	exit;		
	}
	else
	{

		$getstatus = mysql_query("SELECT * FROM dl_users WHERE (user_name = '$nick') && (user_pwd = '$passwort')");
		while ($status1=mysql_fetch_array($getstatus))	
		{
		$level = $status1['user_level'];		
		}


?>


<HTML>

<HEAD>
<TITLE>
FAMS by [ BG-Studios ] - Admin Area
</TITLE>
<META NAME="copyright" CONTENT="Bastian Grimm">
<META NAME="publisher" CONTENT="BG-Studios">
<META NAME="publisher-email" CONTENT="info@bg-studios.de">
<META NAME="author" CONTENT="Bastian Grimm">

<link rel="stylesheet" href="./admin_style.css">

</HEAD>

<BODY>
<CENTER>


<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2 WIDTH=615 BGCOLOR=#FFFFFF>
<TR>
<TD VALIGN=top WIDTH=150 HEIGHT=400 CLASS=nav-background>
<BR>

<CENTER>

<!-- navigation - groups -->



<?php

if ($level == '1')
{
?>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>


<IMG BORDER=0 SRC=../images/group-link.gif><B>Downloads</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=60>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./index1.php>Hauptauswahl</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./add_file.php>Datei hinzufügen</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./edit_file.php>Datei bearbeiten</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./logout.php>Ausloggen</A><BR>

</TD>
</TR>
</TABLE>
<P>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>

<IMG BORDER=0 SRC=../images/group-link.gif><B>Statistiken</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=70>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_info.php TARGET=new>Allgemeine Übersicht</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_top20.php TARGET=new>Top20 Downloads</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_newfiles.php TARGET=new>Neuste Files</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_today.php TARGET=new>Heutige Files</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../search.php TARGET=new>Files suchen</A><BR>

</TD>
</TR>
</TABLE>
<P>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>

<IMG BORDER=0 SRC=../images/group-link.gif><B>[ BG-Studios ]</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=50>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./help.php>Hilfe / FAQs</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=mailto:info@bg-studios.de>eMail Us</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=http://www.bg-studios.de TARGET=new>Website</A><BR>


</TD>
</TR>
</TABLE>
<P>

</CENTER>

<?php
}
elseif ($level == '2')
{
?>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>


<IMG BORDER=0 SRC=../images/group-link.gif><B>Downloads</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=70>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./index1.php>Hauptauswahl</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./add_file.php>Datei hinzufügen</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./edit_file.php>Datei bearbeiten</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./del_file.php>Datei löschen</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./logout.php>Ausloggen</A><BR>

</TD>
</TR>
</TABLE>
<P>


<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>

<IMG BORDER=0 SRC=../images/group-link.gif><B>Kommentar-System</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=35>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./edit_comments.php>Kommentar editieren</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./del_comments.php>Kommentar löschen</A><BR>

</TD>
</TR>
</TABLE>
<P>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>

<IMG BORDER=0 SRC=../images/group-link.gif><B>Statistiken</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=70>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_info.php TARGET=new>Allgemeine Übersicht</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_top20.php TARGET=new>Top20 Downloads</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_newfiles.php TARGET=new>Neuste Files</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_today.php TARGET=new>Heutige Files</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../search.php TARGET=new>Files suchen</A><BR>

</TD>
</TR>
</TABLE>
<P>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>

<IMG BORDER=0 SRC=../images/group-link.gif><B>[ BG-Studios ]</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=50>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./help.php>Hilfe / FAQs</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=mailto:info@bg-studios.de>eMail Us</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=http://www.bg-studios.de TARGET=new>Website</A><BR>


</TD>
</TR>
</TABLE>
<P>

<?php
}
elseif ($level == '3')
{
?>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>


<IMG BORDER=0 SRC=../images/group-link.gif><B>Downloads</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=70>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./index1.php>Hauptauswahl</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./add_file.php>Datei hinzufügen</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./edit_file.php>Datei bearbeiten</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./del_file.php>Datei löschen</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./logout.php>Ausloggen</A><BR>

</TD>
</TR>
</TABLE>
<P>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>

<IMG BORDER=0 SRC=../images/group-link.gif><B>Einstellungen</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=60>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./overview.php>Übersicht</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./add_cat.php>Kategorie hinzufügen</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./edit_cat.php>Kategorie editieren</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./del_cat.php>Kategorie löschen</A><BR>

</TD>
</TR>
</TABLE>
<P>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>

<IMG BORDER=0 SRC=../images/group-link.gif><B>User-Verwaltung</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=35>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./add_user.php>Benutzer hinzufügen</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./del_user.php>Benutzer löschen</A><BR>

</TD>
</TR>
</TABLE>
<P>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>

<IMG BORDER=0 SRC=../images/group-link.gif><B>Kommentar-System</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=35>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./edit_comments.php>Kommentar editieren</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./del_comments.php>Kommentar löschen</A><BR>

</TD>
</TR>
</TABLE>
<P>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>

<IMG BORDER=0 SRC=../images/group-link.gif><B>Statistiken</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=70>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_info.php TARGET=new>Allgemeine Übersicht</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_top20.php TARGET=new>Top20 Downloads</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_newfiles.php TARGET=new>Neuste Files</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../view_today.php TARGET=new>Heutige Files</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=../search.php TARGET=new>Files suchen</A><BR>

</TD>
</TR>
</TABLE>
<P>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>

<IMG BORDER=0 SRC=../images/group-link.gif><B>[ BG-Studios ]</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=50>

<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=./help.php>Hilfe / FAQs</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=mailto:info@bg-studios.de>eMail Us</A><BR>
<IMG BORDER=0 SRC=../images/content-link.gif><A HREF=http://www.bg-studios.de TARGET=new>Website</A><BR>


</TD>
</TR>
</TABLE>
<P>

<?php
}

?>



<!-- End navigation left -->

</TD>
<TD VALIGN=top WIDTH=450 CLASS=main>
<BR>



<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=450 HEIGHT=96% BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=450 CLASS=main-headline HEIGHT=18>


<IMG BORDER=0 SRC=../images/group-link.gif><B>File Area Management System - Administration</B>

</TD>
</TR>
<TR>
<TD WIDTH=450 VALIGN=top CLASS=main-content>
<BR>

<?php

}
}
}
?>
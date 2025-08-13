<?php

/* ------------------------------------------------------------ */
/*								*/
/*	File Area Management System (FAMS)			*/
/*								*/
/*	Copyright (c) 2001 by Bastian 'Buddy' Grimm		*/
/*	Autor: Bastian Grimm					*/
/*	Publisher: [ BG-Studios ]				*/
/*	eMail: bastian@bg-studios.de				*/
/*	WebSite: http://www.bg-studios.de			*/
/*	Date: 06.05.01		Version: Main Layout Head 1.02	*/
/*	Last changed: 08.05.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("./config.php");

$pagetyp = "main";

?>


<HTML>

<HEAD>
<TITLE>
LinkArea by [ Red Universe Community ] - Admin Area
</TITLE>
<META NAME="copyright" CONTENT="Mellesoft">
<META NAME="publisher" CONTENT="Mellesoft">
<META NAME="publisher-email" CONTENT="info@reduniverse.de">
<META NAME="author" CONTENT="Mellesoft">

<link rel="stylesheet" href="./main_style.css">

</HEAD>

<BODY>
<CENTER>


<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2 WIDTH=615 BGCOLOR=#FFFFFF>
<TR>
<TD VALIGN=top WIDTH=150 HEIGHT=400 CLASS=nav-background>
<BR>

<CENTER>

<!-- navigation - groups -->

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>


<IMG BORDER=0 SRC=./images/group-link.gif><B>Main</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=95>

<IMG BORDER=0 SRC=./images/content-link.gif><A HREF=./index.php>News</A><BR>
<IMG BORDER=0 SRC=./images/content-link.gif><A HREF=./view_info.php>Statistiken</A><BR>
<IMG BORDER=0 SRC=./images/content-link.gif><A HREF=./view_top20.php>Top20 Downloads</A><BR>
<IMG BORDER=0 SRC=./images/content-link.gif><A HREF=./view_newfiles.php>Neuste Files</A><BR>
<IMG BORDER=0 SRC=./images/content-link.gif><A HREF=./view_today.php>Heutige Files</A><BR>
<IMG BORDER=0 SRC=./images/content-link.gif><A HREF=./search.php>Files suchen</A><BR>
<IMG BORDER=0 SRC=./images/content-link.gif><A HREF=mailto:<?php print($site_email); ?>>Kontakt</A><BR>
<IMG BORDER=0 SRC=./images/content-link.gif><A HREF=<?php print($site_url); ?> TARGET=new>Hauptseite</A><BR>
<IMG BORDER=0 SRC=./images/content-link.gif><A HREF=./admin/index.php>Administration</A><BR>

<BR>
</TD>
</TR>
</TABLE>
<P>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=140 BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=100% CLASS=nav-group HEIGHT=18>

<IMG BORDER=0 SRC=./images/group-link.gif><B>Downloads</B>

</TD>
</TR>
<TR>
<TD WIDTH=100% VALIGN=top CLASS=nav-content HEIGHT=10>

<?php

	$result = mysql_query("SELECT EID, cat_names FROM dl_categories ORDER BY cat_names");
	
	while ($db=mysql_fetch_array($result))	
	{
	Echo "<IMG BORDER=0 SRC=./images/content-link.gif><A HREF=\"./cat.php?sec=main&show=".$db['EID']."\">".$db['cat_names']."</A><BR>";
	}

?>
<BR>
</TD>
</TR>
</TABLE>
<P>



</CENTER>

<!-- End navigation left -->

</TD>
<TD VALIGN=top WIDTH=450 CLASS=main>
<BR>



<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=450 HEIGHT=96% BGCOLOR=#FFFFFF>
<TR>
<TD WIDTH=450 CLASS=main-headline HEIGHT=18>


<IMG BORDER=0 SRC=./images/group-link.gif><B><?php print($site_name ); ?> Download-Bereich</B>

</TD>
</TR>
<TR>
<TD WIDTH=450 VALIGN=top CLASS=main-content>
<BR>


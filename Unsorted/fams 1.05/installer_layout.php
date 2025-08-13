<?php

/* ------------------------------------------------------------ */
/*								*/
/*	Guestbook						*/
/*								*/
/*	Copyright (c) 2001 by Bastian 'Buddy' Grimm		*/
/*	Autor: Bastian Grimm					*/
/*	Publisher: [ BG-Studios ]				*/
/*	eMail: bastian@bg-studios.de				*/
/*	WebSite: http://www.bg-studios.de			*/
/*	Date: 17.05.01		Version: Installer Layout 1.00	*/
/*	Geändert am: 17.05.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("./config.php");

function layout_top()
{


?>
<HTML>

<HEAD>
<TITLE>
FAMS by [ BG-Studios ] - Installer
</TITLE>
<META NAME="copyright" CONTENT="Bastian Grimm">
<META NAME="publisher" CONTENT="BG-Studios">
<META NAME="publisher-email" CONTENT="info@bg-studios.de">
<META NAME="author" CONTENT="Bastian Grimm">

<link rel="stylesheet" href="./installer_style.css">

</HEAD>

<BODY>
<CENTER>


<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=2 WIDTH=500 BGCOLOR=#000000>
<TR>
<TD VALIGN=top WIDTH=500 CLASS=nav-background>
<BR>
<CENTER>

<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=2 WIDTH=480 HEIGHT=100 BGCOLOR=#000000>
<TR>
<TD WIDTH=450 CLASS=main-headline HEIGHT=18>


<B>FAMS Installer v1.10</B>

</TD>
</TR>
<TR>
<TD WIDTH=480 VALIGN=top CLASS=main-content>
<BR>



<?php

}

function layout_down()
{

?>
</TD>
</TR>
</TABLE>

</TD>
</TR>
<TR>
<TD HEIGHT=10 CLASS=nav-background>
</TD>
</TABLE>

</CENTER>
</BODY>
</HTML>

<?php

}

?>

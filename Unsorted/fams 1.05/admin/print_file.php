
<HTML>

<HEAD>

<META NAME="copyright" CONTENT="BG-Studios">
<META NAME="publisher" CONTENT="BG-Studios">
<META NAME="publisher-email" CONTENT="info@bg-studios.de">
<META NAME="author" CONTENT="BG-Studios">
<meta http-equiv="refresh" content="0;url=javascript:window.print()">
<TITLE>
Datei Informationen
</TITLE>

<link rel="stylesheet" href="./printer_style.css">

</HEAD>
<BODY BGCOLOR=#FFFFFF>
<FONT FACE=Verdana SIZE=2 COLOR=#000000> 

<?php

require("../config.php");

$view = mysql_query("SELECT * FROM dl_files WHERE (EID = '$EID')");
	
	while ($db=mysql_fetch_array($view))	
		{

Echo "

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD WIDTH=130 CLASS=file>
<B>File URL:</B>
</TD>
<TD CLASS=file>
".$db['file_url']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>Mirror URL:</B>
</TD>
<TD CLASS=file>
".$db['mirror']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>Screenshot 1:</B>
</TD>
<TD CLASS=file>
".$db['image1']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>Screenshot 2:</B>
</TD>
<TD CLASS=file>
".$db['image2']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>Screenshot 3:</B>
</TD>
<TD CLASS=file>
".$db['image3']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>Screenshot 4:</B>
</TD>
<TD CLASS=file>
".$db['image4']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>Screenshot 5:</B>
</TD>
<TD CLASS=file>
".$db['image5']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>File Name:</B>
</TD>
<TD CLASS=file>
".$db['file_name']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>Autor:</B>
</TD>
<TD CLASS=file>
".$db['autor']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>Autor eMail:</B>
</TD>
<TD CLASS=file>
".$db['autor_contact']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>File Größe:</B>
</TD>
<TD CLASS=file>
".$db['file_size']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>Hits:</B>
</TD>
<TD CLASS=file>
".$db['hits']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>Hinzugefügt am:</B>
</TD>
<TD CLASS=file>
".$db['date_added']."
</TD>
</TR>
<TR>
<TD WIDTH=130 CLASS=file>
<B>Kategorie:</B>
</TD>
<TD CLASS=file>
".$db['cat']." 
</TD>
</TR>
<TR>
<TD WIDTH=130 VALIGN=top CLASS=file>
<B>Beschreibung:</B>
</TD>
<TD CLASS=file>
".$db['file_description']."
</TD>
</TR>
</TABLE>
<P>
	";

	}

?>

<BR><BR>
<HR COLOR=#000000>
<FONT FACE=Verdana SIZE=1><B>Copyright &copy; 2001 by [ BG-Studios ] - Contact: info@bg-studios.de</B>


</BODY>
</HTML>

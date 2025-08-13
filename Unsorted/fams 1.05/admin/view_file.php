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
/*	Date: 04.05.01		Version: View File 1.03		*/
/*	Geändert am: 24.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("../config.php");
require("./show_cats.php");
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


$pagetyp = "admin";


if($sec) {
   switch($sec) {
	
	case 'view_selected':

include("./layout_top.php");



Echo "Der Eintrag enthält folgende Daten:<P>";


$view = mysql_query("SELECT * FROM dl_files WHERE (EID = '$EID')");
	
	while ($db=mysql_fetch_array($view))	
		{

Echo "



<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD WIDTH=100 CLASS=file>
<B>File URL:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['file_url']."\">".$db['file_url']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>Mirror URL:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['mirror']."\">".$db['mirror']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>Screenshot 1:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['image1']."\">".$db['image1']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>Screenshot 2:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['image2']."\">".$db['image2']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>Screenshot 3:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['image3']."\">".$db['image3']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>Screenshot 4:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['image4']."\">".$db['image4']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>Screenshot 5:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['image5']."\">".$db['image5']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>File Name:</B>
</TD>
<TD CLASS=file>
".$db['file_name']."
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>Autor:</B>
</TD>
<TD CLASS=file>
".$db['autor']."
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>Autor eMail:</B>
</TD>
<TD CLASS=file>
".$db['autor_contact']."
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>File Größe:</B>
</TD>
<TD CLASS=file>
".$db['file_size']."
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>Hits:</B>
</TD>
<TD CLASS=file>
".$db['hits']."
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>Hinzugefügt am:</B>
</TD>
<TD CLASS=file>
".$db['date_added']."
</TD>
</TR>
<TR>
<TD WIDTH=100 CLASS=file>
<B>Kategorie:</B>
</TD>
<TD CLASS=file>
".$db['cat']." - [ <A HREF=\"./cat_info.php\" onClick=\"window.open('./cat_info.php','Form','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,height=300,width=200');return false\"><I>Anzeigen</I></A> ] 
</TD>
</TR>
<TR>
<TD WIDTH=100 VALIGN=top CLASS=file>
<B>Beschreibung:</B>
</TD>
<TD CLASS=file>
".$db['file_description']."
</TD>
</TR>
</TABLE>
<P>
	";


echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>";
Echo "<TR><TD WIDTH=100% CLASS=file>[ <A HREF=\"javascript:history.back()\">Zurück</A>";
Echo "&nbsp;| <A HREF=\"./edit_file.php?sec=view_selected&EID=".$db['EID']."\">Eintrag bearbeiten</A>";
Echo "&nbsp;| <A HREF=\"./del_file.php?sec=view_selected&EID=".$db['EID']."\">Eintrag löschen</A>";
Echo "&nbsp;| <A HREF=\"./print_file.php?sec=view_selected&EID=".$db['EID']."\" onClick=\"window.open('./print_file.php?sec=view_selected&EID=".$db['EID']."','Form','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,height=500,width=500');return false\">Eintrag drucken</A> ]</TD></TR>";
echo "</TABLE>";

	}

		
include("./layout_down.php");

	exit;
	break;
	}
}

}
}
}
?>
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
/*	Date: 04.05.01		Version: Delte File 1.05	*/
/*	Geändert am: 24.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("../config.php");
require("./show_cats.php");
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

    case 'del_check':
	
include("./layout_top.php");

$result = "DELETE FROM dl_files WHERE (EID = '$EID')";
      	if(!$result = mysql_query($result))
	die("Fehler! Die Datenbank  konnte nicht aktualisiert werden!");

echo "Der Eintrag wurde erfolgreich gelöscht...";

include("./layout_down.php");
	
	exit;
	break;
	
	case 'view_selected':

include("./layout_top.php");



Echo "Folgender Eintrag wird gelöscht:<P>";


$view = mysql_query("SELECT * FROM dl_files WHERE (EID = '$EID')");
	
	while ($db=mysql_fetch_array($view))	
		{

Echo "

<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF?sec=del_check&EID=".$db['EID']."\">

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD WIDTH=110 CLASS=file>
<B>File URL:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['file_url']."\">".$db['file_url']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>Mirror URL:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['mirror']."\">".$db['mirror']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>Screenshot 1:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['image1']."\">".$db['image1']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>Screenshot 2:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['image2']."\">".$db['image2']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>Screenshot 3:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['image3']."\">".$db['image3']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>Screenshot 4:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['image4']."\">".$db['image4']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>Screenshot 5:</B>
</TD>
<TD CLASS=file>
<A HREF=\"".$db['image5']."\">".$db['image5']."</A>
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>File Name:</B>
</TD>
<TD CLASS=file>
".$db['file_name']."
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>Autor:</B>
</TD>
<TD CLASS=file>
".$db['autor']."
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>Autor eMail/URL:</B>
</TD>
<TD CLASS=file>
".$db['autor_contact']."
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>File Größe:</B>
</TD>
<TD CLASS=file>
".$db['file_size']."
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>Hits:</B>
</TD>
<TD CLASS=file>
".$db['hits']."
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>Hinzugefügt am:</B>
</TD>
<TD CLASS=file>
".$db['date_added']."
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
<B>Kategorie:</B>
</TD>
<TD CLASS=file>
";

$temp2 = $db['cat'];

$popdown = mysql_query("SELECT * FROM dl_categories ORDER BY cat_names");
while ($db4=mysql_fetch_array($popdown))	
	{
	$temp = $db4['EID'];

		if ($temp == $temp2)
		{
		echo $db4['cat_names'];
		}	
	}

echo "
</TD>
</TR>
<TR>
<TD WIDTH=110 VALIGN=top CLASS=file>
<B>Beschreibung:</B>
</TD>
<TD CLASS=file>
".$db['file_description']."
</TD>
</TR>
<TR>
<TD WIDTH=110 VALIGN=top CLASS=file>
</TD>
<TD CLASS=file>
<BR>

<INPUT TYPE=hidden NAME=sec VALUE=\"del_check\">
<INPUT TYPE=\"submit\" VALUE=\"Eintrag löschen\">

</TD>
</TR>
</TABLE>
</FORM>
	";

	}


include("./layout_down.php");

	exit;
	break;
	}
}

include("./layout_top.php");

echo "Folgende Downloads sind in der Datenbank vorhanden:<P>";

echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>";
$view = mysql_query("SELECT * FROM dl_files ORDER BY EID");
	
	while ($db=mysql_fetch_array($view))	
		{
		Echo "<TR><TD WIDTH=20 ALIGN=center CLASS=file>".$db['EID']."</TD><TD WIDTH=170 CLASS=file><B>".$db['file_name']."</B></TD>";
		Echo "<TD WIDTH=115 CLASS=file>[ <A HREF=\"./view_file.php?sec=view_selected&EID=".$db['EID']."\">Details anzeigen</A>&nbsp;|</TD>";
	   	Echo "<TD WIDTH=115 CLASS=file><A HREF=\"./del_file.php?sec=view_selected&EID=".$db['EID']."\">Eintrag löschen</A> ]</TD></TR>";
		}
echo "</TABLE>";



include("./layout_down.php");

}
}
}

?>
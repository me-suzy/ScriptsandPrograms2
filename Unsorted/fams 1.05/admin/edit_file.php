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
/*	Date: 03.05.01		Version: Edit File 1.05		*/
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

    case 'edit_check':
	
include("./layout_top.php");

$result = "UPDATE dl_files SET file_url='$file_url', mirror='$mirror', file_name='$file_name', autor='$autor', autor_contact='$autor_contact', file_size='$file_size', hits='$hits', date_added='$date_added', cat='$cat', file_description='$file_description', image1='$image1', image2='$image2', image3='$image3', image4='$image4', image5='$image5' WHERE (EID = '$EID')";
if(!$result = mysql_query($result)) 
	{
	 die("Fehler! Die Datenbank konnte nicht aktualisiert werden!");
     	}

echo "Der Eintrag wurde erfolgreich aktualisiert...";

include("./layout_down.php");
	
	exit;
	break;
	
	case 'view_selected':

include("./layout_top.php");



Echo "Folgender Eintrag wird editiert:<P>";


$view = mysql_query("SELECT * FROM dl_files WHERE (EID = '$EID')");
	
	while ($db=mysql_fetch_array($view))	
		{


Echo "

<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF?sec=edit_check&EID=".$db['EID']."\">

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD WIDTH=110 CLASS=file>
File URL:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=file_url SIZE=40 VALUE=\"".$db['file_url']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
Mirror URL:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=mirror SIZE=40 VALUE=\"".$db['mirror']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
Screenshot 1:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=image1 SIZE=40 VALUE=\"".$db['image1']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
Screenshot 2:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=image2 SIZE=40 VALUE=\"".$db['image2']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
Screenshot 3:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=image3 SIZE=40 VALUE=\"".$db['image3']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
Screenshot 4:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=image4 SIZE=40 VALUE=\"".$db['image5']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
Screenshot 5:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=image5 SIZE=40 VALUE=\"".$db['image5']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
File Name:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=file_name SIZE=20 VALUE=\"".$db['file_name']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
Autor:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=autor SIZE=20 VALUE=\"".$db['autor']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
Autor eMail/URL:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=autor_contact SIZE=20 VALUE=\"".$db['autor_contact']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
File Größe:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=file_size SIZE=10 VALUE=\"".$db['file_size']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
Hinzugefügt am:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=date_added SIZE=10 VALUE=\"".$db['date_added']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
Hits:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=hits SIZE=5 VALUE=\"".$db['hits']."\">
</TD>
</TR>
<TR>
<TD WIDTH=110 CLASS=file>
Kategorie:
</TD>
<TD CLASS=file>
";

$temp2 = $db['cat'];
echo "<SELECT NAME=\"cat\" CLASS=\"menu\">";
echo "<OPTION VALUE=\"-1\">Kategorie wählen</OPTION>";
echo "<OPTION VALUE=\"-1\">---------------------</OPTION>";

$popdown = mysql_query("SELECT * FROM dl_categories ORDER BY cat_names");
while ($db4=mysql_fetch_array($popdown))	
	{
	$temp = $db4['EID'];

		if ($temp == $temp2)
		{
		echo "<OPTION VALUE=\"$temp\" selected>".$db4['cat_names']."</OPTION>\n";
		}	
		else
		{
		echo "<OPTION VALUE=\"$temp\">".$db4['cat_names']."</OPTION>\n";
		}
	}

echo "</SELECT>";

echo "
</TD>
</TR>
<TR>
<TD WIDTH=110 VALIGN=top CLASS=file>
<BR>
Beschreibung:
</TD>
<TD CLASS=file>
<TEXTAREA NAME=file_description ROWS=7 COLS=30>".$db['file_description']."
</TEXTAREA>
</TD>
</TR>
<TR>
<TD WIDTH=110 VALIGN=top CLASS=file>
</TD>
<TD CLASS=file>
<BR>

<INPUT TYPE=hidden NAME=sec VALUE=\"edit_check\">
<INPUT TYPE=\"submit\" VALUE=\"Änderungen speichern\">

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
	   	Echo "<TD WIDTH=115 CLASS=file><A HREF=\"./edit_file.php?sec=view_selected&EID=".$db['EID']."\">Eintrag bearbeiten</A> ]</TD></TR>";
		}
echo "</TABLE>";



include("./layout_down.php");

}
}
}
?>
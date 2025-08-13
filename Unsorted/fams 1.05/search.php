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
/*	Date: 05.05.01		Version: Search Engine 1.12	*/		
/*	Geändert am: 28.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("./config.php");


$pagetyp = "main";


if($sec) {
   switch($sec) {

    case 'view_results':

include("main_layout_head.php");


	$result = mysql_query("SELECT * FROM dl_files WHERE (1 and file_url like '%$name%') OR (1 and mirror like '%$name%') OR (1 and file_name like '%$name%') OR (1 and autor like '%$name%') OR (1 and autor_contact like '%$name%')");
	$check = mysql_fetch_array($result);
	if (!$check)
	{
	echo "Es wurde kein passender Datensatz in der Datenbank gefunden!<P>[ <A HREF=javascript:history.back()>Zurück</A> ]";
	}
	else
	{
		$result = mysql_query("SELECT * FROM dl_files WHERE (1 and file_url like '%$name%') OR (1 and mirror like '%$name%') OR (1 and file_name like '%$name%') OR (1 and autor like '%$name%') OR (1 and autor_contact like '%$name%')");
		while ($db = mysql_fetch_array($result))
		{	

		echo "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 CLASS=tabledownload WIDTH=425>";
		echo "<TR>";
		echo "<TD CLASS=downloadheader WIDTH=225><A HREF=\"./file.php?id=".$db['EID']."\"><B>".$db['file_name']."</B></A></TD>";
		echo "<TD CLASS=downloadheader WIDTH=200 ALIGN=right>";
		$autor_email = "".$db['autor_contact']."";
		if($autor_email == '')
		{
		echo "<B>Autor:</B> ".$db['autor']."";
		}
		else
		{
			$autor_con = $autor_email;
			$autor_con = trim($autor_con);
			if(substr(strtolower($autor_con), 0, 7) != "http://")
			{
				if(substr(strtolower($autor_con), 0, 4) == "www.")
				{
					$autor_con = "http://$autor_con";
				}
				else
				{
					$autor_con = "mailto:$autor_con";
				}
			}
		echo "<B>Autor:</B> <A HREF=$autor_con>".$db['autor']."</A>";	
		}
		echo "</TD>";
		echo "</TR>";
		echo "</TABLE>";
		echo "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 CLASS=tabledownload WIDTH=425>";
		echo "<TR>";
		echo "<TD CLASS=download WIDTH=425 COLSPAN=3><BR>".$db['file_description']."<BR><BR></TD>";
		echo "</TR>";
		echo "<TR>";
		echo "<TD CLASS=download WIDTH=100><B>Download:</B> <A HREF=\"./file.php?id=".$db['EID']."\">Hier!</A></TD>";
		echo "<TD CLASS=download WIDTH=150>";
		$screen = "".$db['image_url']."";
		if($screen == '')
		{
		echo "<B>Screenshot:</B> ---";
		}
		else
		{
		echo "<B>Screenshot:</B> <A HREF=\"".$db['image_url']."\" TARGET=new>Hier!</A>";
		}
		echo "</TD>";
		echo "<TD CLASS=download WIDTH=90><B>Hits:</B> ".$db['hits']."</TD>";
		echo "</TR>";
		echo "<TR>";	
		echo "<TD CLASS=download WIDTH=100>";		
		$mirror = "".$db['mirror']."";
		if($mirror == '')
		{
		echo "<B>Alternativ:</B> ---";
		}
		else
		{
		echo "<B>Alternativ:</B> <A HREF=\"".$db['mirror']."\">Hier!</A>";
		}
		echo "</TD>";
		echo "<TD CLASS=download WIDTH=150><B>Hinzugefügt:</B> ".$db['date_added']."</TD>";
		echo "<TD CLASS=download WIDTH=90><B>Size:</B> ".$db['file_size']."</TD>";
		echo "</TR>";
		echo "</TABLE><P>";
	
		}	
	}
include("main_layout_down.php");

	exit;
	break;
	}
}


include("main_layout_head.php");

echo "<B>Download Suche:</B><BR>";
echo "Geben Sie in der Suchmaske den gewünschten Suchberiff ein und die Datenbank wird nach passenden Begriffen durchsucht.";

?>

<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF ?>">

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD WIDTH=110 CLASS=file>
Suchbegriff:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=name SIZE=30>
</TD>
</TR>

<TR>
<TD WIDTH=110 VALIGN=top CLASS=file>
</TD>
<TD CLASS=file>
<BR>

<INPUT TYPE=hidden NAME=sec VALUE="view_results">
<INPUT TYPE="submit" VALUE="Suche starten">

</TD>
</TR>
</TABLE>
</FORM>


<?php

include("main_layout_down.php");

?>

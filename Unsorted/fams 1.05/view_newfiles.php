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
/*	Date: 05.05.01		Version: DL New Files 1.11	*/		
/*	Geändert am: 06.05.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("./config.php");


$pagetyp = "main";

include("main_layout_head.php");

echo "<B>Download Area New Files:</B><BR>";
echo "Hier finden sie jeweils die 3 neusten Files jeder eingerichteten Kategorie.";


	$result2 = mysql_query("SELECT EID, cat_names FROM dl_categories");
	while ($db2=mysql_fetch_array($result2))	
	{

	$nummern = "".$db2['EID']."";

	Echo "<P><BR><B>$nummern. Kategorie: ".$db2['cat_names']."</B><P>";	


		$result = mysql_query("SELECT * FROM dl_files WHERE (cat = '$nummern') ORDER BY EID DESC LIMIT 3");
		while ($db=mysql_fetch_array($result))	
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

?>

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
/*	Date: 05.05.01		Version: DL Top20 1.01		*/		
/*	Geändert am: 06.05.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("./config.php");

$pagetyp = "main";

include("main_layout_head.php");

echo "<B>Download Area Top 20 Files:</B><P>";

		echo "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 CLASS=tabledownload>";
		echo "<TR><TD CLASS=downloadheader WIDTH=150><B>File Name:</B></TD><TD CLASS=downloadheader WIDTH=120><B>Autor:</B></TD><TD CLASS=downloadheader WIDTH=70><B>Size:</B></TD><TD CLASS=downloadheader WIDTH=60><B>Hits:</B></TD></TR>";
		echo "</TABLE>";

		echo "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 CLASS=tabledownload>";
		$result = mysql_query("SELECT * FROM dl_files ORDER BY hits DESC LIMIT 20");
		while ($db=mysql_fetch_array($result))	
		{
		Echo "<TR><TD CLASS=download WIDTH=150><A HREF=\"./file.php?id=".$db['EID']."\">".$db['file_name']."</A></TD>";
		echo "<TD CLASS=download WIDTH=120>";
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
		Echo "<TD CLASS=download WIDTH=70>".$db['file_size']."</TD><TD CLASS=download WIDTH=60>".$db['hits']."</TD></TR>";
	   	}
		echo "</TABLE>";
	



include("main_layout_down.php");

?>

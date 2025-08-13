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
/*	Date: 06.05.01		Version: Show Cats 1.13		*/		
/*	Geändert am: 24.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("./config.php");


$pagetyp = "main";


if($sec) {
   switch($sec) {

    case 'main':



include("main_layout_head.php");


$result5 = mysql_query("SELECT * FROM dl_files WHERE (cat = '$show') ORDER BY EID DESC");
$show3 = mysql_fetch_array($result5);	
if (!$show3)
{
echo "In dieser Kategorie sind noch keine Dateien vorhanden...";
}
else
{

	$result2 = mysql_query("SELECT EID, cat_names FROM dl_categories WHERE (EID = '$show')");
	while ($db2=mysql_fetch_array($result2))	
	{

	echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=425>";
	echo "<TR><TD WIDTH=60% CLASS=main-content><B>Download Bereich: ".$db2['cat_names']."</B>";
	echo "</TD><TD WIDTH=40% CLASS=main-content ALIGN=right>";	

	if (!$start) 
	{
	$start = "0";
	}


	$showit = "1";
	$next = $start + $topics;
	$anzahl = mysql_numrows(mysql_query("SELECT EID FROM dl_files WHERE (cat = '$show') ORDER BY EID"));

	if ($anzahl > $topics)
	{
	   	if ($next < $anzahl)
	   	{
	   	echo "<a href=./cat.php?sec=main&show=$show&start=$next>Nächste Seite</a> | ";
	   	}
		for($x = 0; $x < $anzahl; $x++) 
 	  	{
 	     		if(0 == ($x % $topics)) 
 	     		{
				if($x == $start)
		   		echo "<B><U>$showit</U></B>\n|\n";
		 		else
				echo "<a href=./cat.php?sec=main&show=$show&start=$x>$showit</a>\n|\n";
	 		
		 	$showit++;
		 	if(!($showit % 10)) 
		 	echo "<BR>";
   	   		}
   		}	
	}

	$next = "0"; 
	echo "</TD></TR></TABLE><P>";

		$result = mysql_query("SELECT * FROM dl_files WHERE (cat = '$show') ORDER BY EID DESC LIMIT $start, $topics");
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

		$screen1 = $db['image1'];
		$screen2 = $db['image2'];
		$screen3 = $db['image3'];
		$screen4 = $db['image4'];
		$screen5 = $db['image5'];
		$id = $db['EID'];

		if (empty($screen1))
		{
		echo "<B>Screens:</B> ---";
		} 
		else
		{
		$size1 = GetImageSize("$screen1");
		$height1 = $size1[1] + 100;
		$width1 = $size1[0] + 40;
		echo "<B>Screens:</B> <A HREF=\"./show_image.php?id=$id&s=1&image=$screen1\" onClick=\"window.open('./show_image.php?id=$id&s=1&image=$screen1','Show_1','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,height=$height1,width=$width1');return false\">1</A>\n";		
		
		// scr1 bleibt offen somit kann keiner nur scr 2 nutzen 

		if (empty($screen2))
		{
		echo "";
		} 
		else
		{
		$size2 = GetImageSize("$screen2");
		$height2 = $size2[1] + 100;
		$width2 = $size2[0] + 40;
		echo "| <A HREF=\"./show_image.php?id=$id&s=2&image=$screen2\" onClick=\"window.open('./show_image.php?id=$id&s=2&image=$screen2','Show_2','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,height=$height2,width=$width2');return false\">2</A>\n";
		}

		if (empty($screen3))
		{
		echo "";
		} 
		else
		{
		$size3 = GetImageSize("$screen3");
		$height3 = $size3[1] + 100;
		$width3 = $size3[0] + 40;
		echo "| <A HREF=\"./show_image.php?id=$id&s=3&image=$screen3\" onClick=\"window.open('./show_image.php?id=$id&s=3&image=$screen3','Show_3','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,height=$height3,width=$width3');return false\">3</A>\n";
		}

		if (empty($screen4))
		{
		echo "";
		} 
		else
		{
		$size4 = GetImageSize("$screen4");
		$height4 = $size4[1] + 100;
		$width4 = $size4[0] + 40;
		echo "| <A HREF=\"./show_image.php?id=$id&s=4&image=$screen4\" onClick=\"window.open('./show_image.php?id=$id&s=4&image=$screen4','Show_4','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,height=$height4,width=$width4');return false\">4</A>\n";
		}

		if (empty($screen5))
		{
		echo "";
		} 
		else
		{
		$size5 = GetImageSize("$screen5");
		$height5 = $size5[1] + 100;
		$width5 = $size5[0] + 40;
		echo "| <A HREF=\"./show_image.php?id=$id&s=5&image=$screen5\" onClick=\"window.open('./show_image.php?id=$id&s=5&image=$screen5','Show_5','toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0,height=$height5,width=$width5');return false\">5</A>\n";
		}
		} // Close screen 1

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
		echo "<TD CLASS=download WIDTH=140><B>Added:</B> ".$db['date_added']."</TD>";
		echo "<TD CLASS=download WIDTH=90><B>Size:</B> ".$db['file_size']."</TD>";
		echo "</TR>";
		echo "<TR>";
		echo "<TD WIDTH=280 CLASS=download COLSPAN=2><B><A HREF=./detail.php?id=$id>File bewerten:</A></B>\n";


		$getem = mysql_query("SELECT * FROM dl_wertung WHERE (id = '$id') ORDER BY EID DESC");
		$ch=mysql_fetch_array($getem);
		if (!$ch)
		{
		echo "Bisher keine Bewertung\n";
		}
		else
		{

			$result2 = mysql_query("SELECT * FROM dl_wertung WHERE (id = '$id') ORDER BY EID DESC");
			while ($db2=mysql_fetch_array($result2))	
			{
			$stimmen = $db2['stimmen'];
			$wertung = $db2['wertung'];	

			$bewertung = bcdiv($wertung, $stimmen, "1");

				if ($stimmen == '1')
				{			
				echo "Bisher $bewertung / 10.0 bei $stimmen Stimme";
				}
				elseif ($stimmen >= '2')
				{
				echo "Bisher $bewertung / 10.0 bei $stimmen Stimmen";
				}				
		
			}

		}

		$comments = mysql_numrows(mysql_query("SELECT EID FROM dl_comments WHERE (id = '$id')"));

		echo "</TD>";
		echo "<TD WIDTH=90 CLASS=download><B><A HREF=./detail.php?id=$id>Kommentare:</A></B> $comments </TD>";
		echo "</TR>";

		echo "</TABLE><P>";
	   	}

	}	

}

include("main_layout_down.php");

	exit;
	break;
	}
}



?>

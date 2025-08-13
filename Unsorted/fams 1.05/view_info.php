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
/*	Date: 04.05.01		Version: DL Statistiken 1.07	*/
/*	Geändert am: 23.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("./config.php");


$pagetyp = "main";

include("main_layout_head.php");

echo "<B>Download Area allgemeine Statistiken:</B><P>";

$result2 = mysql_query("SELECT EID FROM dl_files ORDER BY EID DESC LIMIT 1");
		while ($db2=mysql_fetch_array($result2))	
		{
		$check1 = "".$db2['EID']."";
		Echo "<IMG BORDER=0 SRC=./images/content-link.gif>";
			if ($check1 != 1)
			{
			echo "Es sind insgesamt ".$db2['EID']." Dateien vorhanden.<BR>";
			}
			else 
			{
			echo "Es ist ".$db2['EID']." Datei vorhanden.<BR>";
			}
	   	}

	
$result = mysql_query("SELECT EID FROM dl_categories ORDER BY EID DESC LIMIT 1");
		while ($db=mysql_fetch_array($result))	
		{
		$check2 = "".$db['EID']."";
		Echo "<IMG BORDER=0 SRC=./images/content-link.gif>";
			if ($check2 != 1)
			{
			echo "Es sind insgesamt ".$db['EID']." Kategorien eingerichtet.<BR>";
			}
			else 
			{
			echo "Es ist ".$db['EID']." Kategorie eingerichtet.<BR>";
			}
	   	}


$result3 = mysql_query("SELECT * FROM dl_files ORDER BY EID LIMIT 1");
		while ($db3=mysql_fetch_array($result3))	
		{
		Echo "<IMG BORDER=0 SRC=./images/content-link.gif>Das älteste File stammt vom ".$db3['date_added'].".<BR>";
	   	}

$result4 = mysql_query("SELECT * FROM dl_files ORDER BY EID DESC LIMIT 1");
		while ($db4=mysql_fetch_array($result4))	
		{
		Echo "<IMG BORDER=0 SRC=./images/content-link.gif>Das neuste File stammt vom ".$db4['date_added'].".<BR>";
	   	}

$result5 = mysql_query("SELECT * FROM dl_files ORDER BY hits DESC LIMIT 1");
		while ($db5=mysql_fetch_array($result5))	
		{
		Echo "<IMG BORDER=0 SRC=./images/content-link.gif>Das File mit den meisten Downloads ist \"".$db5['file_name']."\" (".$db5['hits']."";
			$check3 = "".$db5['hits']."";
			if ($check3 != 1)
			{
			echo "&nbsp;Downloads).<BR>";
			}
			else
			{
			Echo "&nbsp;Download).<BR>";
	   		}
		}

$result6 = mysql_query("SELECT * FROM dl_files ORDER BY hits LIMIT 1");
		while ($db6=mysql_fetch_array($result6))	
		{
		Echo "<IMG BORDER=0 SRC=./images/content-link.gif>Das File mit den wenigsten Downloads ist \"".$db6['file_name']."\" (".$db6['hits']."";
			$check4 = "".$db6['hits']."";
			if ($check4 != 1)
			{
			echo "&nbsp;Downloads).<BR>";
			}
			else
			{
			Echo "&nbsp;Download).<BR>";
	   		}
	   	}



include("main_layout_down.php");

?>

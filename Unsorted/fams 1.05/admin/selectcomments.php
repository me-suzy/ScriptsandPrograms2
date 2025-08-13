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
/*	Date: 24.07.01		Version: Select Comments 1.00	*/
/*	Geändert am: 24.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/



require("../config.php");


if (!$start) 
{
$start = "0";
}

$showit = "1";
$next = $start + $topics;
$anzahl = mysql_numrows(mysql_query("SELECT * FROM dl_comments ORDER BY EID DESC"));

if ($anzahl > $topics)
{
   	if ($next < $anzahl)
   	{
   	echo "<a href=$modefile?start=$next>Nächste Seite</a> | ";
   	}
	for($x = 0; $x < $anzahl; $x++) 
   	{
      		if(0 == ($x % $topics)) 
      		{
			if($x == $start)
	   		echo "<B><U>$showit</U></B>\n|\n";
	 		else
			echo "<a href=$modefile?start=$x>$showit</a>\n|\n";
	 		
	 	$showit++;
	 	if(!($showit % 10)) 
	 	echo "<BR><BR>";
      		}
   	}	
}

$next = "0"; 



echo "<P>Bitte wählen Sie das entsprechende Download-File aus, um die Kommentare anzuzeigen (neuste Files oben):<P>";

echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%>";



$view = mysql_query("SELECT * FROM dl_files ORDER BY EID DESC LIMIT $start, $topics");
while ($db=mysql_fetch_array($view))	
{
	$id = "".$db['EID']."";
	$anzahl = mysql_numrows(mysql_query("SELECT * FROM dl_comments WHERE (id = '$id')"));

	
	if($anzahl == '0')
	{
	echo "<TR><TD WIDTH=100% CLASS=file><B>[ Keine Kommentare ]";
	}

		if($anzahl == '1')
		{
		echo "<TR><TD WIDTH=100% CLASS=file><B>[ <A HREF=$modefile?sec=show&id=".$db['EID'].">$anzahl Kommentar $mode</A> ]";
		}

		if($anzahl >= '2')
		{
		echo "<TR><TD WIDTH=100% CLASS=file><B>[ <A HREF=$modefile?sec=show&id=".$db['EID'].">$anzahl Kommentare $mode</A> ]";
		}
	

 	echo "&nbsp;- ".$db['file_name']."</B></TD></TR>";
	echo "<TR><TD WIDTH=100% CLASS=file VALIGN=top>";


		$comments = mysql_query("SELECT * FROM dl_comments WHERE (id = '$id') ORDER BY EID DESC LIMIT 1");
		while ($db2=mysql_fetch_array($comments))	
		{
		
		echo "Letzter Kommentar: ".$db2['date']." um ".$db2['time']." Uhr";

		}


		$temp2 = $db['cat'];

		$popdown = mysql_query("SELECT * FROM dl_categories ORDER BY cat_names");
		while ($db4=mysql_fetch_array($popdown))	
		{
		$temp = $db4['EID'];

			if ($temp == $temp2)
			{
				if ($anzahl == '0')
				{
				echo "Kategorie: ".$db4['cat_names']."<BR><BR></TD></TR>";
				}
				elseif ($anzahl >= '1')
				{
				echo "&nbsp;|| Kategorie: ".$db4['cat_names']."<BR><BR></TD></TR>";
				}
			}	
		}


}


echo "</TABLE>";


?>
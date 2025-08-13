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
/*	Date: 24.07.01		Version: Show Comments 1.00	*/
/*	Geändert am: 24.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/



require("../config.php");


echo "Folgende Kommentare sind vorhanden und können jetzt bearbeitet werden:<P>";
	$result2 = mysql_query("SELECT * FROM dl_comments WHERE (id = $id)");
	while ($db2=mysql_fetch_array($result2))	
	{
	echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%>";
	echo "<TR><TD WIDTH=70% HEIGHT=15 CLASS=file><B>".$db2['headline']."</B></TD><TD WIDTH=30% CLASS=file ALIGN=right>";
		$autor_email = "".$db2['email']."";
		if($autor_email == '')
		{
		echo "Posted by: ".$db2['name']."";
		}
		else
		{
		echo "Posted by: <A HREF=mailto:$autor_email>".$db2['name']."</A>";	
		}
	echo "</TD></TR>";
	echo "<TR><TD WIDTH=70% HEIGHT=20 CLASS=file VALIGN=top>".$db2['date']." um ".$db2['time']." Uhr</TD>";
	echo "<TD WIDTH=30% HEIGHT=20 CLASS=file VALIGN=top ALIGN=right><B>[ <A HREF=$modefile?sec=go&id=".$db2['id']."&id2=".$db2['EID'].">$do</A> ]</B></TR>";
	echo "<TR><TD WIDTH=100% CLASS=file VALIGN=top COLSPAN=2><DIV ALIGN=justify>";
	$message = "".$db2['comment']."";
	$message = htmlspecialchars($message);
	$message = nl2br($message);
	echo $message;
	echo "</DIV></TD></TR>";
	echo "</TABLE><BR><HR COLOR=#FFFFFF SIZE=1 NOSHADOW WIDTH=100%><BR>";
	}



?>
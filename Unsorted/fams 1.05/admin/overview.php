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
/*	Date: 04.05.01		Version: Overview 1.03		*/
/*	Geändert am: 07.05.01					*/
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

		$getstatus = mysql_query("SELECT * FROM dl_users WHERE (user_name = '$nick') && (user_pwd = '$passwort')");
		while ($status1=mysql_fetch_array($getstatus))	
		{
		$level = $status1['user_level'];	

			if ($level <= '2')
			{
			include("layout_top_e.php");
			echo $nopermission;
			include("layout_down_e.php");
			exit;		
			}		
		}


$pagetyp = "admin";


include("./layout_top.php");




$result1 = mysql_query("SELECT * FROM dl_categories ORDER BY EID");
	
	$cats = mysql_fetch_array($result1);
		
	if (!$cats)
	{
	Echo "Keine Kategorien vorhanden!";
	}
	else
	{
		echo "<B>Folgende Kategorien sind bereits eingerichtet:</B><P>";
		$result = mysql_query("SELECT * FROM dl_categories ORDER BY EID");
		while ($db=mysql_fetch_array($result))	
		{
		Echo "Kategorie ".$db['EID']." = <B><A HREF=\"../cat.php?sec=main&show=".$db['EID']."\" TARGET=new>".$db['cat_names']."</A></B><BR>";
	   	}
	}
   		



echo "<P><TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>";
Echo "<TR><TD WIDTH=100% CLASS=file>[ <A HREF=\"javascript:history.back()\">Zurück</A>";
Echo "&nbsp;| <A HREF=\"./add_cat.php\">Kategorie hinzufügen</A>";
Echo "&nbsp;| <A HREF=\"./edit_cat.php\">Kategorie bearbeiten</A>";
Echo "&nbsp;| <A HREF=\"./del_cat.php\">Kategorie löschen</A> ]</TD></TR>";
echo "</TABLE>";

include("./layout_down.php");

}
}
}
?>
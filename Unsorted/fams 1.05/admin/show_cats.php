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
/*	Date: 03.05.01		Version: List Cats 1.00		*/
/*	Geändert am: 25.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

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

function show_cats()
{

echo "Folgende Kategorien haben Sie bereits eingerichtet:";

$result = mysql_query("SELECT EID, cat_names FROM dl_categories ORDER BY EID");
	
	while ($db=mysql_fetch_array($result))	
		{
		Echo "Kategorie ".$db['EID']." = <B>".$db['cat_names']."</B><BR>";
	   	}

}

function cat_dropdown()
{


echo "<SELECT NAME=\"cat\" CLASS=\"menu\">";

echo "<OPTION VALUE=\"-1\">Kategorie wählen</OPTION>";
echo "<OPTION VALUE=\"-1\">---------------------</OPTION>";

$result2 = mysql_query("SELECT EID, cat_names FROM dl_categories ORDER BY EID");
	
	while ($db2=mysql_fetch_array($result2))	
		{
		Echo "<OPTION VALUE=\"".$db2['EID']."\">".$db2['cat_names']."</OPTION>";
	   	}
echo "</SELECT>";


}

}
}
}

?>
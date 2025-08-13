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
/*	Date: 04.05.01		Version: Edit Cat 1.00		*/
/*	Geändert am: 25.07.01					*/
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


if($sec) {
   switch($sec) {

    case 'editcat_check':
	
include("./layout_top.php");

$result = "UPDATE dl_categories SET cat_names='$cat_names' WHERE (EID = '$EID')";
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



Echo "Folgende Kategorie wird editiert:<P>";


$view = mysql_query("SELECT * FROM dl_categories WHERE (EID = '$EID')");
	
	while ($db=mysql_fetch_array($view))	
		{

Echo "

<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF?sec=editcat_check&EID=".$db['EID']."\">

<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD WIDTH=130 CLASS=file>
Kategorie Name:
</TD>
<TD CLASS=file>
<INPUT TYPE=text NAME=cat_names SIZE=20 VALUE=\"".$db['cat_names']."\">
</TD>
</TR>
<TR>
<TD WIDTH=130 VALIGN=top CLASS=file>
</TD>
<TD CLASS=file>
<BR>

<INPUT TYPE=hidden NAME=sec VALUE=\"editcat_check\">
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

echo "Folgende Kategorien sind bereits eingerichtet:<P>";

echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>";
$view = mysql_query("SELECT * FROM dl_categories ORDER BY EID");
	
	while ($db=mysql_fetch_array($view))	
		{
		Echo "<TR><TD WIDTH=100 ALIGN=center CLASS=file>Kategorie Nr. ".$db['EID']."</TD><TD WIDTH=170 CLASS=file><B>".$db['cat_names']."</B></TD>";
	   	Echo "<TD WIDTH=135 CLASS=file>[ <A HREF=\"./edit_cat.php?sec=view_selected&EID=".$db['EID']."\">Kategorie bearbeiten</A> ]</TD></TR>";
		}
echo "</TABLE>";



include("./layout_down.php");

}
}
}

?>
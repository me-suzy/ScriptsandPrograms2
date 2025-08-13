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
/*	Date: 25.07.01		Version: Add User 1.03		*/
/*	Geändert am: 25.07.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("../config.php");
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

if($sec) {
   switch($sec) {
	case 'go':

include("layout_top.php");

$result = "DELETE FROM dl_users WHERE (EID = '$id')";
      	if(!$result = mysql_query($result))
	die("Fehler! Die Datenbank  konnte nicht aktualisiert werden!");

?>



Der Benutzer wurde erfolgreich gelöscht...<P> 
[ <A HREF=./index1.php>Hauptauswahl</A> ]
<BR>
<?php

	exit;
	break;
	}
}

include("layout_top.php");

echo "Folgende Benutzer können jetzt aus dem System gelöscht werden und haben danach keinen Zutritt mehr zur Admin-Oberfläche:<P>";

$getall = mysql_query("SELECT * FROM dl_users ORDER BY EID DESC");
while ($us=mysql_fetch_array($getall))	
{

echo "<B>".$us['user_name']."</B> || User-Level: ".$us['user_level']." - [ <A HREF=\"./del_user.php?sec=go&id=".$us['EID']."\">Benutzer löschen</A> ]<P>";

}

include("layout_down.php");

}
}
}
?>


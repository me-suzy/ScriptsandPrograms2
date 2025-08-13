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
/*	Date: 03.05.01		Version: Installer 1.33		*/
/*	Geändert am: 08.05.01					*/
/*								*/
/* ------------------------------------------------------------	*/



require("config.php");
require("installer_layout.php");


if($next) {
   switch($next) {

/* open config - write server infos */

    case 'database':

layout_top();      

      $config_file = file("./config.php");
      if(!strstr($config_file[count($config_file)-1], '?>') ) 
	{  
	if(!$fp = fopen("./config.php", "a"))
	die("Error beim Öffnen der Config Datei.");
	
	
	$config_data = 

	'mysql_connect("'.$dbserver.'", "'.$dbuser.'", "'.$dbpass.'") or die("Datenbank konnte nicht konnektiert werden!");'."\n".

	'mysql_select_db("'.$dbname.'") or die("Fehler beim Öffnen der Datenbank!");'."\n".

	'$site_name = "'.$sitename.'";'."\n".
	'$site_url = "'.$siteurl.'";'."\n".
	'$site_email = "'.$siteemail.'";'."\n".
	'$admin_path = "'.$admin_direc.'";'."\n".
	'$topics = "'.$fs.'";'."\n".	
	'?>'."\n";

	fputs($fp, $config_data);
	fclose($fp);
	} 
	else 
	{
	print "Informationen sind bereits vorhanden und können nicht noch einmal hinzugefügt werden!<BR>";
	}
	flush();
	
	echo "Informationen wurden in die Config Dateien geschrieben...<BR>";
	echo "Erstelle SQL-Tabellen...<P>";
	flush();

	mysql_connect("$dbserver", "$dbuser", "$dbpass") or die("Datenbank konnte nicht konnektiert werden!");
	mysql_select_db("$dbname") or die("Fehler beim Öffnen der Datenbank!");

		$tables = array (
			
			"dl_categories" => "CREATE TABLE dl_categories
			(
                	EID bigint(80) DEFAULT '0' NOT NULL AUTO_INCREMENT,
			cat_names varchar(100) NOT NULL,
			cat_numbers varchar(10) NOT NULL,
			PRIMARY KEY (EID)
			)",

			"dl_files" => "CREATE TABLE dl_files
			(
                	EID bigint(80) DEFAULT '0' NOT NULL AUTO_INCREMENT,
			file_url varchar(200) NOT NULL,
			mirror varchar(200) NOT NULL,
			file_name varchar(60) NOT NULL,
			file_description blob,
			file_size varchar(20) NOT NULL,
			autor varchar(60) NOT NULL,
			autor_contact varchar(150) NOT NULL,
			cat bigint(20) DEFAULT '0' NOT NULL,
			hits bigint(20) DEFAULT '0' NOT NULL,
			date_added varchar(20) NOT NULL,
			image1 varchar(200) NOT NULL,
			image1hits bigint(20) DEFAULT '0' NOT NULL,
			image2 varchar(200) NOT NULL,
			image2hits bigint(20) DEFAULT '0' NOT NULL,
			image3 varchar(200) NOT NULL,
			image3hits bigint(20) DEFAULT '0' NOT NULL,
			image4 varchar(200) NOT NULL,
			image4hits bigint(20) DEFAULT '0' NOT NULL,
			image5 varchar(200) NOT NULL,
			image5hits bigint(20) DEFAULT '0' NOT NULL,
			PRIMARY KEY (EID)
			)",

			"dl_comments" => "CREATE TABLE dl_comments
			(
                	EID bigint(80) DEFAULT '0' NOT NULL AUTO_INCREMENT,
			name varchar(100) NOT NULL,
			email varchar(100) NOT NULL,
			url varchar(200) NOT NULL,
			icq varchar(20) NOT NULL,
			headline varchar(255) NOT NULL,
			date varchar(20) NOT NULL,
			time varchar(20) NOT NULL,
			comment text NOT NULL,
			id varchar(20) NOT NULL,
			ip varchar(20) NOT NULL,
			sec2 varchar(100) NOT NULL,
			PRIMARY KEY (EID)
			)",

			"dl_wertung" => "CREATE TABLE dl_wertung
			(
                	EID bigint(80) DEFAULT '0' NOT NULL AUTO_INCREMENT,
			wertung varchar(100) NOT NULL,
			stimmen varchar(100) NOT NULL,
			id char(3) NOT NULL,
			PRIMARY KEY (EID)
			)",

			"dl_users" => "CREATE TABLE dl_users
			(
                	EID bigint(80) DEFAULT '0' NOT NULL AUTO_INCREMENT,
			user_name varchar(100) NOT NULL,
			user_mail varchar(100) NOT NULL,
			user_pwd varchar(200) NOT NULL,
			user_level char(3) NOT NULL,
			PRIMARY KEY (EID)
			)"
				);

			
	flush();
	
	echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>";
	while(list($name, $table) = each($tables)) 
		{
		echo "<TR><TD CLASS=main WIDTH=200>Creating table $name</TD>";
	    		if(!$r = mysql_query($table))
	     		die("<TD CLASS=main>ERROR! Tabelle konnte nicht erstellt werden. Fehler: <b>". mysql_error()."</b></TD></TR>");
	    	echo "<TD CLASS=main>[OK]</TD></TR>";
		flush();
		}	
	Echo "</TABLE><BR>";
	
	echo "Datenbank erfolgreich eingerichtet!<P> ";

?>
	
	<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF ?>">
	<INPUT TYPE="HIDDEN" NAME="next" VALUE="setadmin">
	<INPUT TYPE="SUBMIT" VALUE="Nächster Schritt >>">
	</FORM>	

<?php

layout_down();     

	exit;
	break;

case 'setadmin':

layout_top(); 
?>

Bitte legen Sie jetzt den Administrator des Systemes fest. Dazu geben Sie bitte im untenstehenden Formular Benutzername und Passwort ein.
<BR>
Die hier eingegeben Daten benötigen Sie später, wenn Sie sich in die Admin-Oberfläche einloggen wollen. Die Daten werden verschlüsselt gespeichert und 
sind für keinen anderen User sichtbar.
<P>


<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF ?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="80%">
<TR ALIGN="LEFT">
<TD CLASS=main>Benutzername:
</TD>
<TD><INPUT TYPE="TEXT" NAME="user_name" SIZE="30"></TD>
</TR>
<TR ALIGN="LEFT">
<TD CLASS=main>eMail Adresse:
</TD>
<TD><INPUT TYPE="TEXT" NAME="user_mail" SIZE="30"></TD>
</TR>
<TR ALIGN="LEFT">
<TD CLASS=main>Passwort:
</TD>
<TD><INPUT TYPE="PASSWORD" NAME="user_pass" SIZE="30"></TD>
</TR>
<TR>
<TD COLSPAN="2">
<INPUT TYPE="HIDDEN" NAME="next" VALUE="checkadmin">
<INPUT TYPE="SUBMIT" VALUE="Benutzer speichern">
</TD>
</TR>
</TABLE>
</FORM>

<?php

layout_down();
 
	exit;
	break;

case 'checkadmin':

layout_top();

if ($user_name == '')
	{
	Echo "<B>Fehler!</B><BR> Sie müssen einen Benutzernamen eingeben!<P>";
	Echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
	exit;
	} 
if ($user_mail == '')
	{
	Echo "<B>Fehler!</B><BR> Sie müssen die eMail Adresse des Users eingeben eingeben!<P>";
	Echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
	exit;
	} 
if ($user_pass == '')
	{
	Echo "<B>Fehler!</B><BR> Sie müssen ein Passwort für den User vergeben.<P>";
	Echo "[ <A HREF=javascript:history.back()>Zurück</A> ]";
	exit;
	} 

$user_pwd = md5($user_pass);
$user_level = "3";

$upuser = mysql_query("INSERT INTO dl_users (user_name, user_mail, user_level, user_pwd) VALUES ('$user_name', '$user_mail', '$user_level', '$user_pwd')");
flush();

Echo "Die Daten wurden erfolgreich gespeichert...<BR>";
flush();


?>
<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF ?>">
<INPUT TYPE="HIDDEN" NAME="next" VALUE="setcats">
<INPUT TYPE="SUBMIT" VALUE="Nächster Schritt >>">
</FORM>
<?php

layout_down();

	exit;
	break;

case 'setcats':

layout_top();     

?>
Bitte geben sie im folgenden die Kategorie-Namen an, die sie zum Download anbieten wollen (z.B. Updates, Tools, etc.). Es können maximal 100 Kategorieren verwaltet werden!
<P>

Es besteht natürlich auch die Möglichkeit, über den Admin Bereich weitere Kategorien hinzuzüfügen, vorhandene zu editieren oder zu löschen!
<P>

<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF ?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="80%">
<TR ALIGN="LEFT">
<TD CLASS=main>Kategorie hinzufügen:
</TD>
<TD><INPUT TYPE="TEXT" NAME="cat_names" SIZE="30"></TD>
</TR>
<TR>
<TD COLSPAN="2">
<INPUT TYPE="HIDDEN" NAME="next" VALUE="insertcats">
<INPUT TYPE="SUBMIT" VALUE="Kategorie speichern">
</TD>
</TR>
</TABLE>
</FORM>


<?php

layout_down();     

	exit;
	break;
	
	case 'insertcats':

layout_top();     

$result = mysql_query("INSERT INTO dl_categories (cat_names, cat_numbers) VALUES ('$cat_names', '$cat_numbers')");

echo "Kategorie wurde erfolgreich abgespeichert!";
echo "<P>";
?>

<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF ?>">
<INPUT TYPE="HIDDEN" NAME="next" VALUE="setcats">
<INPUT TYPE="SUBMIT" VALUE="Weitere Kategorie hinzufügen">
</FORM>

<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF ?>">
<INPUT TYPE="HIDDEN" NAME="next" VALUE="finish">
<INPUT TYPE="SUBMIT" VALUE="Einrichtung beenden">
</FORM>

<?php

layout_down();     

	exit;
	break;

	case 'finish':

layout_top();     

?>

Die Einrichtung des File Area Management Systems wurde erfolgreich abgeschlossen! Sie können jetzt die einzelnen Einstellungen vornehmen und die
gewünschten Download Files in die Datenbank einfügen und somit zum Download anbieten!
<P>

<?php 
Echo "[ <A HREF=./admin/index.php>Zum Administrations-Bereich</A> ]<P>";



layout_down();     

	exit;
	break;
		} // Switch next
	} // Mode next

layout_top();     

?>

Vielen Dank, dass Sie sich für ein Produkt der [ <A HREF=http://www.bg-studios.de TRAGET=new>BG-Studios</A> ] entschieden haben. Dieser Installer wird Sie durch das Setup des Programmes führen und alle Schritte geau erläutern!
<P>
Bitte geben Sie in dem folgenden Formular die mySQL Server Zugangsdaten und einige Informationen zu Ihrer Homepage ein und klicken dann auf "Nächster Schritt".<BR> 
Bei "Homepage Adresse" geben Sie bitte nur Ihre Domain an (ohne Slash am Ende)! Und danach bei "FAMS Pfad" z.B. "filearea" wenn Sie das FAMS im Unterverz. "filearea" installieren, ansonsten entsprechend anpassen (Wichtig: auch keine am Anfang oder Ende Slashes setzen)! 

<P>

<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF ?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="80%">
<TR ALIGN="LEFT">
<TD CLASS=main>Datenbank Server Addresse:
</TD>
<TD><INPUT TYPE="TEXT" NAME="dbserver" SIZE="30"></TD>
</TR>
<TR>
<TD CLASS=main>Datenbank Name:
</TD>
<TD><INPUT TYPE="TEXT" NAME="dbname" SIZE="30">
</TD>
<TR>
<TD CLASS=main>Datenbank User Name:
</TD>
<TD><INPUT TYPE="TEXT" NAME="dbuser" SIZE="30">
</TD>
</TR>
<TR>
<TD CLASS=main>Datenbank Password:
</TD>
<TD><INPUT TYPE="PASSWORD" NAME="dbpass" SIZE="30">
</TD>
</TR>
<TR>
<TD CLASS=main>Homepage Name:
</TD>
<TD><INPUT TYPE="TEXT" NAME="sitename" SIZE="30">
</TD>
</TR>
<TR>
<TD CLASS=main>Homepage Adresse:
</TD>
<TD><INPUT TYPE="TEXT" NAME="siteurl" SIZE="30">
</TD>
</TR>
<TR>
<TD CLASS=main>Kontakt eMail:
</TD>
<TD><INPUT TYPE="TEXT" NAME="siteemail" SIZE="30">
</TD>
</TR>
<TR>
<TD CLASS=main>FAMS Pfad:
</TD>
<TD><INPUT TYPE="TEXT" NAME="admin_direc" SIZE="30" VALUE="filearea">
</TD>
</TR>
<TR>
<TD CLASS=main>Files pro Seite:
</TD>
<TD><INPUT TYPE="TEXT" NAME="fs" SIZE="5" VALUE="10">
</TD>
</TR>

<TR>
<TD COLSPAN="2" VALIGN="bottom" HEIGHT="25">
<INPUT TYPE="HIDDEN" NAME="next" VALUE="database">
<INPUT TYPE="SUBMIT" VALUE="Nächster Schritt >>">
</TD>
</TR>
</TABLE>
</FORM>
<P><BR>

Sollten Probleme auftreten, die sich nicht mit Hilfe der Readme Datei lösen lassen, so können Sie mit uns via eMail in Kontakt treten. Nutzen Sie dazu einfach die Adresse
<A HREF=mailto:info@bg-studios.de>info@bg-studios.de</A> und wir werden Ihre Anfrage umgehend bearbeiten! Achten sie bitte darauf, dass die Fehlerbeschreibung so exakt wie möglich ist.
<BR>

<?php
layout_down(); 
?>    

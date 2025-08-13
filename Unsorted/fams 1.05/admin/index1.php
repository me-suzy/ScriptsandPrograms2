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
/*	Date: 03.05.01		Version: Admin Index 1.00	*/
/*	Geändert am: 04.05.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("../config.php");

$pagetyp = "admin";

include("./layout_top.php");

?>

Herzlich Willkommen im Administrationsbereich des FAMS.<P> Bitte wählen Sie eine Option aus dem linken Index um mit der Datenbankverwaltung zu beginnen.
<P> 
Bei Fragen oder Probleme schauen Sie sich bitte zu erst einmal die <A HREF=./help.php>Hilfe Datei</A> an. Sollten dann noch immer Probleme
vorliegen, kontaktieren Sie uns einfach per eMail.

<?php

include("./layout_down.php");

?>

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
/*	Date: 06.05.01		Version: Main Index 1.01	*/
/*	Geändert am: 08.05.01					*/
/*								*/
/* ------------------------------------------------------------	*/

require("./config.php");


$pagetyp = "main";

include("./main_layout_head.php");

?>

Herzlich Willkommen im <A HREF=<?php print($site_url); ?> TARGET=new><?php print($site_name); ?></A> Download Bereich.
<P><DIV ALIGN=justify>
Das linke Menü erlaubt Ihnen, nach entsprechenden Dateien, die nach Kategorien angeordnet sind, zu suchen. Weiterhin bietet Ihnen der Download Bereich umfangreiche Statistiken und
erlaubt es, auf einen Blick zu sehen, wann welche Veränderungen getätigt worden sind.
<P>
Sollten Sie beim Surfen nicht fündig werden, bleibt Ihnen immernoch die Möglichkeit, die File Suchmaschiene zu nutzen oder den Besitzer der Seite per eMail 
(<A HREF=mailto:<?php print($site_email); ?>><?php print($site_email); ?></A>) zu kontaktieren.
</DIV>

<?php

include("./main_layout_down.php");
?>

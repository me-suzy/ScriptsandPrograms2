<?php


//  Formular Titel
$GLOBALS["tFormTitle"] = 'Dateitypen verwalten';

//  List Headings
$GLOBALS["tFileCategory"] = 'Kategorie';
$GLOBALS["tFileType"] = 'Dateityp';
$GLOBALS["tMIMEType"] = 'MIME Typen';
$GLOBALS["tFileIcon"] = 'Symbol';

//  Kategorien
$GLOBALS["tFileCatBackup"] = 'Backup';
$GLOBALS["tFileCatDownload"] = 'Download';
$GLOBALS["tFileCatImage"] = 'Bild';
$GLOBALS["tFileCatScript"] = 'Script';

//  Funktionen listen
$GLOBALS["tAddNewFiletype"] = 'Neuen Dateityp hinzuf&uuml;gen';
$GLOBALS["tViewFiletype"] = 'Dateityp ansehen';
$GLOBALS["tEditFiletype"] = 'Dateityp &auml;ndern';
$GLOBALS["tDeleteFiletype"] = 'Dateityp l&ouml;schen';

//  Form Block Titles
$GLOBALS["thFiletypeGeneral"] = 'Dateitypen-Details';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'In diesem Formular k&ouml;nnen Sie benutzerdefinierte Datentypen f&uuml;r die Verf&uuml;gbarkeit innerhalb von ezContents erstellen.';
$GLOBALS["hFileCategory"] = 'Die Kategorie bestimmt, welche Datei-Verwaltungsfunktionen f&uuml;r den jeweiligen Datentyp der Dateiendung erlaubt sind.';
$GLOBALS["hFileType"] = 'Dies ist der Dateitypen-Code, basierend auf der Dateiendung.';
$GLOBALS["hMIMEType"] = 'Dies ist eine Liste der g&uuml;ltigen MIME-Typen f&uuml;r Dateien mit dieser Endung.<br /><br />Sie k&ouml;nnen mehrere MIME_Typen eingeben, getrennt mit Semikolons (<b>;</b>), wenn eventuell mehrere MIME-Typen mit einer bestimmten Dateiendung assoziiert sind.<br />Ein Beispiel daf&uuml;r w&auml;ren .jpg-Dateien, die den MIME-Typ <b>image/jpeg</b> oder <b>image/pjpeg</b> haben k&ouml;nnen.';
$GLOBALS["hFileIcon"] = 'Dies ist ein Symbol f&uuml;r die Anzeige des Dateityps.';

//  Error Messages
$GLOBALS["eNoFiletype"] = 'Dateityp Code kann nicht leer gelassen werden.';
$GLOBALS["eNoMIMEType"] = 'MIME-Typ kann nicht leer gelassen werden.';

?>

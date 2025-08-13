<?php

##################################################

# /admin/tabfile-upload.php

##################################################

define( "TFUPL_HEADER", "TAB-GETRENNTER DATEI UPLOAD" );

define( "TFUPL_ERROR", "Fehler beim Datei Upload." );
define( "TFUPL_ERROR_NOTXT", "Keine Textdatei gefunden" );

define( "TFUPL_PROCEED", "Weiter zum Import" );
define( "TFUPL_RETURN", "Zurück zum Upload-Dialog" );

define( "TFUPL_COLS_TR", "Titel" );
define( "TFUPL_COLS_AR", "Interpret" );
define( "TFUPL_COLS_AL", "Album" );
define( "TFUPL_COLS_GE", "Genre" );
define( "TFUPL_COLS_FS", "Grösse" );
define( "TFUPL_COLS_TI", "Dauer" );
define( "TFUPL_COLS_TN", "Titel Number" );
define( "TFUPL_COLS_TC", "Anzahl gehört" );
define( "TFUPL_COLS_YR", "Jahr" );
define( "TFUPL_COLS_DT", "Datum" );
define( "TFUPL_COLS_DA", "Datum hinzugefügt" );
define( "TFUPL_COLS_BR", "Bitrate" );
define( "TFUPL_COLS_SR", "Sample Rate" );
define( "TFUPL_COLS_VA", "Lautstärke Einstellung" );
define( "TFUPL_COLS_FK", "Typ" );
define( "TFUPL_COLS_CT", "Kommentar" );
define( "TFUPL_COLS_LC", "Ablageort" );

define( "TFUPL_CAPTION_1", "Dateien zum Upload müssen durch Tabs getrennt die folgenden Spalten haben:" );
define( "TFUPL_CAPTION_2", "Das Import-Skript wird alle \":\" im Ablageort-Feld ersetzen, falls es nicht folgendes enthält \"://\", und \"/\" um mit der Mac OS Verzeichnisstrennung, da diese Option ursprünglich für C&G Soundjam und Apple iTunes text export format geplant war. Beachten Sie das <i>manche Software</i> die Angewohnheit haben, Dateinamen im Ablageort-Feld umzubennenen die mehr als 31 Zeichen haben ..." );
define( "TFUPL_CAPTION_3", "Der Upload ist auf mit 2MB pro Datei beschränkt." );
define( "TFUPL_CAPTION_4", "Netjuke unterstützt (bis jetzt) nicht den upload von Audio-Dateien da das senden von grossen Dateien von einer Webseite extrem unzuverlässig sein kann." );

define( "TFUPL_BTN", "Upload der Tab-getrennten Dateien" );

##################################################

?>
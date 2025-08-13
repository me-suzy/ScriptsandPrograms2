<?php

##################################################

# /admin/index.php

##################################################

define( "ADMNDX_SYS_HEADER", "SYSTEM ADMINISTRATION" );
define( "ADMNDX_CONT_HEADER", "MUSIK ADMINISTRATION" );

define( "ADMNDX_EDIT_HELP", "Hier klicken um auf das Admin-Tool zuzugreifen." );
define( "ADMNDX_INFO_HELP", "HILFE: Hier klicken um mehr Informationen über das Admin-Tool zu erhalten." );

define( "ADMNDX_PREFEDIT", "EINSTELLUNGS-DATEI" );
define( "ADMNDX_PREFEDIT_HELP", "Benutzen Sie diese Seite um die Einstelluns-Datei zu bearbeiten, welche die Datenbank-Anmelde-Information, wichtige Dateipfade, html köpfe & füße, etc. enthält." );

define( "ADMNDX_USERMAINT", "BENUTZER-MANAGER" );
define( "ADMNDX_USERMAINT_HELP", "Erlaubt ihnen Benutzer anzuzeigen, zu bearbeiten und zu löschen." );

define( "ADMNDX_HIDFILE", "VERSTECKTE DATEIEN SUCHEN" );
define( "ADMNDX_HIDFILE_HELP", "Durchsucht den angegebenen Pfad nach versteckten Dateien oder Verzeichnissen (deren Namen mit einem .Punkt beginnen) und erstellt eine entsprechende Batch-Datei um diese von der Kommandzeile aus zu löschen. Dies ist besonders beim Upload von einem Mac auf Unix nützlich (.AppleDouble, .DS_Store, etc)" );

define( "ADMNDX_PHPINFO", "PHP INFO" );
define( "ADMNDX_PHPINFO_HELP", "Script um wichtige PHP-relevante Konfigurations-Informationen des Computers auf dem Netjuke ausgeführt wird, anzuzeigen." );

define( "ADMNDX_TRADD", "NEUE TITEL-INFORMATION HINZUFüGEN" );
define( "ADMNDX_TRADD_HELP", "Hiermit können Sie neue Titel-Informationen zur Datenbank hinzufügen. Dies ist besonders bei entfernten Quellen interessant die nicht im Musik-Verzeichniss gescannt werden können, wie zB Internet Radio Stationen und Streams anderer Seiten." );

define( "ADMNDX_MP3UPL", "AUDIO-DATEIEN UPLOAD" );
define( "ADMNDX_MP3UPL_HELP", "Hiermit können Sie Audio-Datien auf den lokalen Server uploaden. Die Dateien können einzeln als .mp3/.ogg oder in Gruppen als .zip/.tar.gz/.tgz Archive kopiert werden." );

define( "ADMNDX_MP3FIND", "VERZEICHNISSE NACH AUDIO-DATEIEN DURCHSUCHEN" );
define( "ADMNDX_MP3FIND_HELP", "Durchsucht das angegebne Verzeichniss und falls angegeben dessen Unterverzeichnisse nach Dateien mit den Endunger \".mp3\" or \".ogg\" or \".wma\" (case insensitive). Falls ID3-Tags (oder ähnliche) gefunden werden, wird automatisch eine Import-Datei generiert. Am Ende wird optional eine Seite angezeigt, um die Daten der Dateien mit fehlendem Tag einzugeben." );

define( "ADMNDX_TABFILEUPL", "TAB-GETRENNTER DATEI UPLOAD" );
define( "ADMNDX_TABFILEUPL_HELP", "Hiermit können spezielle, tab-getrennte Text-Dateien kopiert werden um diese dann in die Datenbank zu importieren. Für mehr Information auf Dateiformat, etc klicken." );

define( "ADMNDX_TABFILEIMP", "TAB-GETRENNTER DATEI IMPORT" );
define( "ADMNDX_TABFILEIMP_HELP", "Dieses Skript verarbeitet alle tab-getrennten Text-Dateien die kopiert wurden, und importiert diese in die Datenbank." );

define( "ADMNDX_DBMAINTAIN", "MUSIK DATENBANK PFLEGE" );
define( "ADMNDX_DBMAINTAIN_HELP", "Werkzeuge um Backups anzulegen, nach fehlenden Audio-Datein zu suchen, etc." );

##################################################

?>
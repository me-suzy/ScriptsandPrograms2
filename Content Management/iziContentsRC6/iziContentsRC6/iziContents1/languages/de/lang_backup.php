<?php


//  Formular Titel
$GLOBALS["tFormTitle"] = 'Datenbank Backup/R&uuml;cksicherung';
$GLOBALS["tFormTitle1"] = 'Upload Backup/Backup Unterverzeichnisse erstellen';
$GLOBALS["tFormTitle2"] = 'Upload Backup';
$GLOBALS["tFormTitle3"] = 'Unterverzeichnis erstellen';
$GLOBALS["tFormTitle4"] = 'Datenbank Backup';

//  Funktionen listen
$GLOBALS["tBackupDB"] = 'Backup Datenbank';
$GLOBALS["tCreateBackup"] = 'Databank Backup erstellen';
$GLOBALS["tDownloadBackup"] = 'Diese Backup-Datei auf die lokale Festplatte herunterladen';
$GLOBALS["tUploadBackup"] = 'Backup Datei hochladen';
$GLOBALS["tRestoreBackup"] = 'Wiederherstellen der Datenbank mit dieser Backup-Datei';
$GLOBALS["tDeleteBackup"] = 'Diese Backup-Datei l&ouml;schen';
$GLOBALS["tDeleteBackupDir"] = 'Backup Unterverzeichnis l&ouml;schen';

//  Überschriften listen
$GLOBALS["tBackupFilename"] = 'Backup Dateiname';
$GLOBALS["tFilesize"] = 'Dateigr&ouml;sse';
$GLOBALS["tFiledate"] = 'Backup Datum/Zeit';

//  Andere Nachrichten
$GLOBALS["tConfirmRestore"] = 'Sind Sie sich sicher, dass Sie die Datenbank mit dieser Backup-Datei &uuml;berschreiben wollen?';

//  Formularfeld Überschriften
$GLOBALS["tFilename"] = 'Backup Dateiname';
$GLOBALS["tUseGzip"] = 'gzip Backup Datei';
$GLOBALS["tSubdirectoryname"] = 'Unterverzeichnis Name';

//  Form Field Options
$GLOBALS["tGzipCompression"] = 'komprimieren';
$GLOBALS["tGzipNoCompression"] = 'nicht komprimieren';
$GLOBALS["tGzipTest"] = 'Komprimierung testen';

//  Formular Text Beschreibung
$GLOBALS["tDetails"] = 'Geben Sie bitte einen Namen f&uuml;r diese Backup-Datei ein.';
$GLOBALS["hFilename"] = 'Bitte geben Sie einen Dateinamen f&uuml;r das Backup an. <br />F&uuml;gen Sie keine Datei-Endung an, diese wird automatisch erstellt.';
$GLOBALS["hUseGzip"] = 'Markieren Sie hier, wenn die gzip-Kompression verwendet werden soll. Nur, wenn Ihr Server dies unterst&uuml;tzt.';

//  Formular Buttons
$GLOBALS["bUpload"] = 'Hochladen!';
$GLOBALS["bCreateDir"] = 'Erstellen!';

//  Fehlernachrichten
$GLOBALS["eFilenameEmpty"] = 'Sie m&uuml;ssen einen Dateinamen eingeben';
$GLOBALS["eNoFile"] = 'Sie m&uuml;ssen eine Datei f&uuml;r den Upload ausw&auml;hlen.';
$GLOBALS["eNoDir"] = 'Sie m&uuml;ssen einen Verzeichnisnamen eingeben.';
$GLOBALS["eDirAlreadyExists"] = 'Dieses Unterverzeichnis besteht bereits';
$GLOBALS["eZeroByteFile"] = 'Datei ist 0 bytes gross';
$GLOBALS["eNoFileUpload"] = 'Die Datei konnte nicht erfolgreich hochgeladen werden!';
$GLOBALS["eInvalidFileSize"] = 'Die Datei ist zu gross zum hochladen';
$GLOBALS["eInvalidFileType"] = 'Dies ist keine g&uuml;ltige Backup-Datei';
$GLOBALS["eInvalidMimeType"] = 'MIME typ der Datei ist kein g&uuml;ltiger Backup-Typ';
$GLOBALS["mBackupComplete"] = 'Backup vollst&auml;ndig';
$GLOBALS["mRestoreComplete"] = 'R&uuml;cksicherung vollst&auml;ndig';
$GLOBALS["eERROR"] = 'BACKUP/R&uuml;cksicherungs FEHLER';
$GLOBALS["eBackupFailed"] = 'Backup konnte nicht erstellt werden';
$GLOBALS["eRestoreFailed"] = 'Backup konnte nicht zur&uuml;ckgesichert werden';
$GLOBALS["eNoGzip"] = 'Das Backup wurde mit gzip erstellt, aber leider kann dieser Webserver das Verfahren nicht unterst&uuml;tzen.';

?>

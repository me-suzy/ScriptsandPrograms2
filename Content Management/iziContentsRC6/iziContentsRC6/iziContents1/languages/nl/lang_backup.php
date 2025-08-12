<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'database backup/terugzetten';
$GLOBALS["tFormTitle1"] = 'upload backup/maak backup subdirectories';
$GLOBALS["tFormTitle2"] = 'Upload backup';
$GLOBALS["tFormTitle3"] = 'Creeër een Subdirectory';
$GLOBALS["tFormTitle4"] = 'database backup';

//  List Functions
$GLOBALS["tBackupDB"] = 'Backup database';
$GLOBALS["tCreateBackup"] = 'Creeër een database backup';
$GLOBALS["tDownloadBackup"] = 'Download backup naar lokaal station';
$GLOBALS["tUploadBackup"] = 'Upload backup file';
$GLOBALS["tRestoreBackup"] = 'Herstel database met behulp van backup file';
$GLOBALS["tDeleteBackup"] = 'Delete backup file';
$GLOBALS["tDeleteBackupDir"] = 'Delete backup subdirectory';

//  List Headings
$GLOBALS["tBackupFilename"] = 'Backup filenaam';
$GLOBALS["tFilesize"] = 'File grootte';
$GLOBALS["tFiledate"] = 'Backup datum/tijd';

//  Other Messages
$GLOBALS["tConfirmRestore"] = 'Weet je zeker dat je de database wilt herstellen met behulp van deze backup file';

//  Form Field Headings
$GLOBALS["tFilename"] = 'backup file naam';
$GLOBALS["tUseGzip"] = 'gzip backup file';
$GLOBALS["tSubdirectoryname"] = 'Subdirectory naam';

//  Form Field Options
$GLOBALS["tGzipCompression"] = 'Comprimeer';
$GLOBALS["tGzipNoCompression"] = 'Niet comprimeren';
$GLOBALS["tGzipTest"] = 'Test compressie';

//  Form Text Description
$GLOBALS["tDetails"] = 'Geef naam aan dit backup file.';
$GLOBALS["hFilename"] = 'Enter naam voor de backup.<br />Geef geen extensie naam dit wordt automatisch gedaan.';
$GLOBALS["hUseGzip"] = 'Vink dit aan om gzip compressie mogelijk te maken mits mogelijk op deze server.';

//  Form Buttons
$GLOBALS["bUpload"] = 'Upload!';
$GLOBALS["bCreateDir"] = 'Creeëren!';

//  Error Messages
$GLOBALS["eFilenameEmpty"] = 'Je moet een file naam geven';
$GLOBALS["eNoFile"] = 'je moet een file selecteren voor upload.';
$GLOBALS["eNoDir"] = 'Je moet een directory naam opgeven.';
$GLOBALS["eDirAlreadyExists"] = 'subdirectory bestaat al';
$GLOBALS["eZeroByteFile"] = 'File is 0 bytes in grootte';
$GLOBALS["eNoFileUpload"] = 'File kon niet correct worden geupload.';
$GLOBALS["eInvalidFileSize"] = 'File is te groot voor upload';
$GLOBALS["eInvalidFileType"] = 'File is geen correct backup file';
$GLOBALS["eInvalidMimeType"] = 'MIME type van het File is niet een correct backup type';

$GLOBALS["mBackupComplete"] = 'Backup kompleet';
$GLOBALS["mRestoreComplete"] = 'Herstel kompleet';

$GLOBALS["eERROR"] = 'BACKUP/HERSTEL ERROR';
$GLOBALS["eBackupFailed"] = 'Kon geen backup maken';
$GLOBALS["eRestoreFailed"] = 'Herstel backup mislukt';
$GLOBALS["eNoGzip"] = 'Backup was gemaakt met gzip, maar deze webserver geeft daar geen support voor.';

?>

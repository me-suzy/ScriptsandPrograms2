<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'database backup/restore';
$GLOBALS["tFormTitle1"] = 'upload backup/create backup subdirectories';
$GLOBALS["tFormTitle2"] = 'Upload backup';
$GLOBALS["tFormTitle3"] = 'Create Subdirectory';
$GLOBALS["tFormTitle4"] = 'database backup';

//  List Functions
$GLOBALS["tBackupDB"] = 'Backup database';
$GLOBALS["tCreateBackup"] = 'Create a database backup';
$GLOBALS["tDownloadBackup"] = 'Download this backup file to local disk';
$GLOBALS["tUploadBackup"] = 'Upload backup file';
$GLOBALS["tRestoreBackup"] = 'Restore database from this backup file';
$GLOBALS["tDeleteBackup"] = 'Delete this backup file';
$GLOBALS["tDeleteBackupDir"] = 'Delete backup subdirectory';

//  List Headings
$GLOBALS["tBackupFilename"] = 'Backup filename';
$GLOBALS["tFilesize"] = 'File size';
$GLOBALS["tFiledate"] = 'Backup date/time';

//  Other Messages
$GLOBALS["tConfirmRestore"] = 'Are you sure you wish to restore the database from this backup file';

//  Form Field Headings
$GLOBALS["tFilename"] = 'backup file name';
$GLOBALS["tUseGzip"] = 'gzip backup file';
$GLOBALS["tSubdirectoryname"] = 'Subdirectory name';

//  Form Field Options
$GLOBALS["tGzipCompression"] = 'Compress';
$GLOBALS["tGzipNoCompression"] = 'Don\'t compress';
$GLOBALS["tGzipTest"] = 'Test compression';

//  Form Text Description
$GLOBALS["tDetails"] = 'Enter a name for this backup file.';
$GLOBALS["hFilename"] = 'Enter a file name for the backup.<br />Do not include the file extension as this will be tagged on automatically.';
$GLOBALS["hUseGzip"] = 'Set this flag to enable gzip compression if your server supports it.';

//  Form Buttons
$GLOBALS["bUpload"] = 'Upload!';
$GLOBALS["bCreateDir"] = 'Create!';

//  Error Messages
$GLOBALS["eFilenameEmpty"] = 'You must enter a file name';
$GLOBALS["eNoFile"] = 'You need to select a file to upload.';
$GLOBALS["eNoDir"] = 'You need to enter a directory name.';
$GLOBALS["eDirAlreadyExists"] = 'subdirectory already exists';
$GLOBALS["eZeroByteFile"] = 'File is 0 bytes in length';
$GLOBALS["eNoFileUpload"] = 'File could not be uploaded successfully.';
$GLOBALS["eInvalidFileSize"] = 'File is too large for upload';
$GLOBALS["eInvalidFileType"] = 'File is not a valid backup file';
$GLOBALS["eInvalidMimeType"] = 'MIME type of File is not a valid backup type';

$GLOBALS["mBackupComplete"] = 'Backup Complete';
$GLOBALS["mRestoreComplete"] = 'Restore Complete';

$GLOBALS["eERROR"] = 'BACKUP/RESTORE ERROR';
$GLOBALS["eBackupFailed"] = 'Unable to create backup';
$GLOBALS["eRestoreFailed"] = 'Unable to restore backup';
$GLOBALS["eNoGzip"] = 'Backup was created with gzip, but this webserver is not configured to support it.';

?>

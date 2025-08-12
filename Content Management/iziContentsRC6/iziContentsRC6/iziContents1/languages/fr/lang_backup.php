<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'sauvegarde/Restaure de base de données';
$GLOBALS["tFormTitle1"] = 'télécharger sauvegarde/créer sous-directorie';
$GLOBALS["tFormTitle2"] = 'Télécharger sauvegarde';
$GLOBALS["tFormTitle3"] = 'Créer sous-directorie';
$GLOBALS["tFormTitle4"] = 'base de données sauvegarde';

//  List Functions
$GLOBALS["tBackupDB"] = 'Sauvegarde base de données';
$GLOBALS["tCreateBackup"] = 'Créer une sauvegarde de la base de données';
$GLOBALS["tDownloadBackup"] = 'Télécharger ce fichier de sauvegarde au disque local';
$GLOBALS["tUploadBackup"] = 'Télécharger le fichier de sauvegarde';
$GLOBALS["tRestoreBackup"] = 'Restaurer la base de données de ce fichier de sauvegarde';
$GLOBALS["tDeleteBackup"] = 'Effacer ce fichier de sauvegarde';
$GLOBALS["tDeleteBackupDir"] = 'Effacer subdirectory de sauvegarde';

//  List Headings
$GLOBALS["tBackupFilename"] = 'Nom de fichier de sauvegarde';
$GLOBALS["tFilesize"] = 'Taille de fichier';
$GLOBALS["tFiledate"] = 'Sauvegarder la date/heure';

//  Other Messages
$GLOBALS["tConfirmRestore"] = 'Vous souhaitez restaurer la base de données de ce fichier de sauvegarde';

//  Form Field Headings
$GLOBALS["tFilename"] = 'sauvegarder le nom de fichier';
$GLOBALS["tUseGzip"] = 'le fichier de sauvegarde gzip';
$GLOBALS["tSubdirectoryname"] = 'Nom sous-directorie';

//  Form Field Options
$GLOBALS["tGzipCompression"] = 'Compresser';
$GLOBALS["tGzipNoCompression"] = 'Pas compresser';
$GLOBALS["tGzipTest"] = 'Tester compression';

//  Form Text Description
$GLOBALS["tDetails"] = 'Entrer un nom pour ce fichier de sauvegarde.';
$GLOBALS["hFilename"] = 'Entrer un nom de fichier pour la sauvegarde.<br />Ne pas inclure une extension de fichier comme ceci sera étiqueté sur automatiquement.';
$GLOBALS["hUseGzip"] = 'Régler ce drapeau pour rendre capable la compression de gzip si votre serveur le soutient.';

//  Form Buttons
$GLOBALS["bUpload"] = 'Télécharger!';
$GLOBALS["bCreateDir"] = 'Créer!';

//  Error Messages
$GLOBALS["eFilenameEmpty"] = 'Vous devez entrer un nom de fichier';
$GLOBALS["eNoFile"] = 'Vous avez besoin de choisir un fichier pour télécharger.';
$GLOBALS["eNoDir"] = 'Vous avez besoin entrer un nom annuaire.';
$GLOBALS["eDirAlreadyExists"] = 'subdirectory existe déjà';
$GLOBALS["eZeroByteFile"] = 'Le fichier est 0 octets dans la longueur';
$GLOBALS["eNoFileUpload"] = 'Fichier ne pourrait pas être téléchargé avec succès.';
$GLOBALS["eInvalidFileSize"] = 'Le fichier est trop grand pour télécharge';
$GLOBALS["eInvalidFileType"] = 'Le fichier est pas un fichier de sauvegarde valide';
$GLOBALS["eInvalidMimeType"] = 'Le type de MIME du fichier est pas un type de sauvegarde valide';

$GLOBALS["mBackupComplete"] = 'La sauvegarde complèter';
$GLOBALS["mRestoreComplete"] = 'Restaureration complèter';

$GLOBALS["eERROR"] = 'SAUVEGARDE/RESTAURE ERREUR';
$GLOBALS["eBackupFailed"] = 'Incapable de créer la sauvegarde';
$GLOBALS["eRestoreFailed"] = 'Incapable de restaurer la sauvegarde';
$GLOBALS["eNoGzip"] = 'La sauvegarde a été créée avec gzip, mais ce webserver n est pas configuré pour le soutenir.';

?>

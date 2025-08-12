<?php

//  Form Titles
$GLOBALS["tFormTitle"] = 'maintain filetypes';

//  List Headings
$GLOBALS["tFileCategory"] = 'Category';
$GLOBALS["tFileType"] = 'File type';
$GLOBALS["tMIMEType"] = 'MIME types';
$GLOBALS["tFileIcon"] = 'Icon';

//  Categories
$GLOBALS["tFileCatBackup"] = 'Backup';
$GLOBALS["tFileCatDownload"] = 'Download';
$GLOBALS["tFileCatImage"] = 'Image';
$GLOBALS["tFileCatScript"] = 'Script';

//  List Functions
$GLOBALS["tAddNewFiletype"] = 'Add new filetype';
$GLOBALS["tViewFiletype"] = 'View filetype';
$GLOBALS["tEditFiletype"] = 'Edit filetype';
$GLOBALS["tDeleteFiletype"] = 'Delete filetype';

//  Form Block Titles
$GLOBALS["thFiletypeGeneral"] = 'Filetype Details';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'This form lets you define custom filetypes for use within ezContents.';
$GLOBALS["hFileCategory"] = 'Category determines which file maintenance functions are permitted to use this file type.';
$GLOBALS["hFileType"] = 'This is the filetype code, based on the file extension.';
$GLOBALS["hMIMEType"] = 'This is a list of the valid MIME types for files with this extension.<br /><br />You can enter several MIME types separated by semi-colons (<b>;</b>) where there may be several MIME Types associated with this particular file extension.<br />An example of this is jpeg files which can have a MIME type of <b>image/jpeg</b> or <b>image/pjpeg</b>.';
$GLOBALS["hFileIcon"] = 'This is an icon to use for the filetype display.';

//  Error Messages
$GLOBALS["eNoFiletype"] = 'Filetype code can not be left empty.';
$GLOBALS["eNoMIMEType"] = 'MIME type can not be left empty.';

?>

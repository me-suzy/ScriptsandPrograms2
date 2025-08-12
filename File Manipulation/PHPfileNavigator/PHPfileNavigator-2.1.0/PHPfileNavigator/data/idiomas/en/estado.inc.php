<?php
/****************************************************************************
* data/idiomas/en/estado.inc.php
*
* Textos para el idioma English
*

PHPfileNavigator versión 2.1.0

Copyright (C) 2004-2005 Lito <lito@eordes.com>

http://phpfilenavigator.litoweb.net/

Este programa es software libre. Puede redistribuirlo y/o modificarlo bajo los
términos de la Licencia Pública General de GNU según es publicada por la Free
Software Foundation, bien de la versión 2 de dicha Licencia o bien (según su
elección) de cualquier versión posterior.

Este programa se distribuye con la esperanza de que sea útil, pero SIN NINGUNA
GARANTÍA, incluso sin la garantía MERCANTIL implícita o sin garantizar la
CONVENIENCIA PARA UN PROPÓSITO PARTICULAR. Véase la Licencia Pública General de
GNU para más detalles.

Debería haber recibido una copia de la Licencia Pública General junto con este
programa. Si no ha sido así, escriba a la Free Software Foundation, Inc., en
675 Mass Ave, Cambridge, MA 02139, EEUU.
*******************************************************************************/

defined('OK') or die();

return array(
	'estado.crear_dir' => array(
		'0' => 'A mistake has occured when creating the folder.',
		'1' => 'Folder succesfully created.',
		'2' => 'There is already a folder with that name.',
		'3' => 'Folder without writting permissions.',
		'4' => 'The name has non allowed characters, choose another name for the folder.',
		'5' => 'The size limit for this root is already surpassed.',
	),
	'estado.subir_arq' => array(
		'0' => 'there has been a mistake when uploading one of the files.',
		'1' => 'File succesfully  uploaded.',
		'2' => 'The name has non allowed characters, change the file\\\'s name.',
		'3' => 'There is already a file with that name.',
		'4' => 'Destiny folder without written permissions.',
		'5' => 'The file weights more than the permited weight for this configuration.',
		'6' => 'The file surpasses the maximum limit of size for this root.',
		'7' => 'The file surpasses the limit of bandwidth permited for this month.',
	),
	'estado.eliminar_dir' => array(
		'0' => 'The folder or part of this folder has not been totally deleted, you have to proove its permissions with the administrator.',
		'1' => 'Folder succesfully deleted.',
		'2' => 'Are you sure you want to delete this empty folder?',
		'3' => 'This folder is not empty, <br />Are you sure you want to delete this folder and all its content?',
		'4' => 'The folder you intend to delete doesn\\\'t exist.',
	),
	'estado.eliminar_arq' => array(
		'0' => 'The file couldn\\\'t be deleted, check for its permissions.',
		'1' => 'File succesfully deleted.',
		'2' => 'Are you sure you want to delete this file?',
		'4' => 'The file you want to delete doesn\\\'t exist.',
	),
	'estado.renomear' => array(
		'0' => 'The name couldn\\\'t be changed, check for its permissions.',
		'1' => 'Name succesfully changed.',
		'2' => 'There is already a folder with that name.',
		'3' => 'There is already a file with that name.',
		'4' => 'The new name contains an non allowed text.',
	),
	'estado.mover_dir' => array(
		'0' => 'The folder or part of it couldn\\\'t be moved, check for its permissions and for the destination place.',
		'1' => 'Folder succesfully moved.',
		'2' => 'This folder is not empty,<br />Select a destination to move this folder and all its content.',
		'3' => 'Select the destination to move this empty folder.',
		'4' => 'The destination folder doesn\\\'t exist.',
		'5' => 'Destination folder without writing permissions.',
		'6' => 'There is already a folder in the destination that contains this name.',
		'7' => 'You can\\\'t make a copy over its own.',
	),
	'estado.mover_arq' => array(
		'0' => 'The file couldn\\\'t be moved, check for origin and destination permissions.',
		'1' => 'File succesfully moved.',
		'2' => 'Choose a destination for this file.',
		'3' => 'There is already a file in the destination folder with this name.',
		'4' => 'There isn\\\'t a destination folder.',
		'5' => 'Destination folder without written permissions.',
		'6' => 'A copy was created in the destination, but the original couldn\\\'t be deleted.',
	),
	'estado.copiar_dir' => array(
		'0' => 'The folder and part of its content couldn\\\'t be copied, check for origin and destination permissions.',
		'1' => 'Folder succesfully copied.',
		'2' => 'This folder is not empty,<br />Select a destination to copy this folder and its content.',
		'3' => 'Select a destinyation to copy this empty folder.',
		'4' => 'The destination folder doesn\\\'t exist.',
		'5' => 'Destination folder without writing permissions.',
		'6' => 'There is already a folder in the destinyation with that name.',
		'7' => 'A folder can\\\'t be copied in the same folder.',
		'8' => 'You can\\\'t copy this folder bacause it surpasses the limit of this root.',
	),
	'estado.copiar_arq' => array(
		'0' => 'The file couldn\\\'t be copied, check for origin and destination permissions.',
		'1' => 'File succesfully copied.',
		'2' => 'Select a destination for this file.',
		'3' => 'There is already a file in the destination folder with the same name.',
		'4' => 'The destination folder doesn\\\'t exist.',
		'5' => 'Destination folder without written permissions.',
		'6' => 'You can\\\'t copy this file for it surpasses the limit for this root.',
	),
	'estado.enlazar_dir' => array(
		'0' => 'The folder or part of it couldn\\\'t be lace, check for origin and destination permissions.',
		'1' => 'Folder succesfully lace.',
		'2' => 'The folder of destination doesn\\\'t exist.',
		'3' => 'Destination folder without written permissions.',
		'4' => 'There is already a folder in the destination with this name.',
	),
	'estado.enlazar_arq' => array(
		'0' => 'The file couldn\\\'t be laced, check for origin and destination permissions.',
		'1' => 'File succesfully laced.',
		'2' => 'Select a destination place for this file.',
		'3' => 'There is already a file in the folder of destination with this name.',
		'4' => 'The destination folder doesn\\\'t exist.',
		'5' => 'Destination folder without writing permissions.',
	),
	'estado.editar' => array(
		'0' => 'A mistake occured editating this file.',
		'1' => 'File succesfully editated.              .',
		'2' => 'File without writing permissions.',
		'3' => 'The file to edit doesn\\\'t exist.',
		'4' => 'It\\\'s not permitted to edit this file.',
	),
	'estado.subir_url' => array(
		'0' => 'An error has ocurred with that URL.',
		'1' => 'The asked URL has been saved correctly.',
		'2' => 'A file with that name already exists.',
		'3' => 'The destination directory doesn\\\'t have writing permissions.',
		'4' => 'Take into account that the waiting time can be very long if you choose weighted files. It is recommended to choose text files, such as web pages.',
		'5' => 'Please wait while the asked URL is being downloaded.<br /><br />Take into account that if the asked document is very weighted the waiting time might be very long.',
		'6' => 'The URL downloading process has been canceled correctly.',
		'7' => 'The given address cannot be downloaded because it exceedes the limit of the choosen root.',
		'8' => 'The name choosen for the file has non allowed characters.',
		'9' => 'With that file the bandwidth limit for this month will be exceeded.',
	),
	'estado.extraer' => array(
		'0' => 'It has been impossible to extract any of the files, the compressed file might be faulty or might have a incorrect format.',
		'1' => 'All the files have been extracted correctly.',
		'2' => 'The file doesn\\\'t have a valid extension (tar,gz,gzip,tgz).',
		'3' => 'This application doesn\\\'t support extractions of that file type.',
		'4' => 'Could\\\'nt be extracted, it is corrupted.',
		'5' => 'Some of the files were not extracted, they already exist.',
		'6' => 'Some of the files could not have been opened for writing.',
		'7' => 'The extraction could not be finished because the content exceedes the maximun weight for this root.',
		'8' => 'Some of the files have not allowed names or were empty, so they were not extracted.',
		'9' => 'Some of the directories needed to the extraction of the content could not be created.',
	),
	'estado.multiple_copiar' => array(
		'0' => 'The directory/file could not be copied, check the permissions in both origin and destination.',
		'1' => 'All the directories or files were copied correctly.',
		'2' => 'Choose the destination of the directories or files to be copied.',
		'3' => 'A file or directory with the given name already exists on the destination:',
		'4' => 'The destination directory doesn\\\'t exist for:',
		'5' => 'The destination directory doesn\\\'t have writing permissions for:',
		'6' => 'This directory/file cannot be copied because it exceedes the limit for this root:',
		'7' => 'Some of the choosen directories or files do not exist or are not readable.',
		'8' => 'The rest of directories and files were copied succesfully.',
		'9' => 'The directory cannot be copied inside its own.',
	),
	'estado.multiple_eliminar' => array(
		'0' => 'The file or directory could not be removed, check the permissions of the target.',
		'1' => 'All the files or directories were removed correctly.',
		'2' => 'Are you sure that you want to remove all these files or directories?',
		'3' => 'The rest of the files or directories were removed correctly.',
		'4' => 'The file you are willing to remove doesn\\\'t exist.',
	),
	'estado.multiple_mover' => array(
		'0' => 'The file/directory could not have been moved, please check the permissions in the origin and the destination.',
		'1' => 'All the directories or files were moved succesfully.',
		'2' => 'Choos the destination for the directories or files to be moved.',
		'3' => 'A file or directory with the given name already exists on the destination.',
		'4' => 'The destination directory doesn\\\'t exist for:',
		'5' => 'The destination directory does not have writing permissions for:',
		'6' => 'A copy of the destination has been created, but the original could not have been removed:',
		'7' => 'The rest of the directories and files were moved correctly.',
		'8' => 'A directory cannot be moved inside its own:',
		'9' => 'Some of the choosen directories or files don\\\'t exist or aren\\\'t readable.:',
	),
	'estado.multiple_permisos' => array(
		'0' => 'The permissions of the directory/file could not be changed:',
		'1' => 'Permissions changed correctly.',
		'2' => 'File does not exist or the permissions about it are not available:',
		'3' => 'The rest of the files or directories were changed correctly.',
	),
	'estado.permisos' => array(
		'0' => 'The permissions of the directory/file could not be changed:',
		'1' => 'Permissions changed correctly.',
		'2' => 'File does not exist or the permissions about it are not available.',
	),
	'estado.descargar' => array(
		'0' => 'The selected file does not exist or it is non readable.',
		'2' => 'The actual root cannot be downloaded because it would exceed the bandwidth available for this week.',
		'3' => 'The registration file could not be opened for saving the downloaded data. Please check the [*$this->paths["info"]*] directory.',
	),
	'estado.redimensionar' => array(
		'0' => 'The thumbnail was canceled.',
		'1' => 'The thumbnail was created correctly.',
		'2' => 'The thumbnail was deleted successfully',
	),
	'estado.ver_comprimido' => array(
		'1' => 'File selected isn\\\'t a valid compressed file.',
	),
);
?>
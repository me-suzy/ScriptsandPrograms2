<?php
/****************************************************************************
* data/idiomas/en/axuda.inc.php
*
* Textos para el idioma English
*

PHPfileNavigator versión 2.0.0

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
	'h1_quero_facer' => 'What do I want to do?',
	'tit_crear_dir' => 'Make directory',
	'txt_crear_dir' => 'To creat a folder first click on the upper option <strong><img src="[*$this->g("estilo")*]imx/crear_dir.png" alt="Make directory" /> Make directory</strong>. Once done this, you will have to cover all the required fields, although, only the name is required.',
	'tit_subir_arq' => 'File Upload',
	'txt_subir_arq' => 'To upload a file first click on the upper option <strong><img src="[*$this->g("estilo")*]imx/subir_arq.png" alt="File Upload" /> File Upload</strong>. Once done this, you will have to cover all the required fields, although to select the file you will upload is required.<br /><br />If an image is to be Uploaded, there will be two options to creat a miniature copy of the image that is in <strong>Reduced Image</strong>, if not, do not take in account this option.',
	'tit_subir_url' => 'Get a file from another web',
	'txt_subir_url' => 'To upload a file that already exists in another web, first click on the option <strong><img src="[*$this->g("estilo")*]imx/subir_url.png" alt="Upload URL" /> Upload URL</strong>.<br /><br />Once done this, you will have to write the <strong>URL Adress</strong> that you want to store, take into account that it has to be a complete adress, for example,this is better <i>http://phpfilenavigator.litoweb.net/index.php</i> than this <i>http://phpfilenavigator.litoweb.net</i>, because in this last option it could fail, after the URL Adress appears <strong>Name of the file to create</strong> where you have to a write a name that allows us to identify it later, as <i>PHPfileNavigator Web</i>.',
	'tit_miniaturas' => 'View the images in a miniature version in the file list',
	'txt_miniaturas' => 'You only have to click in the upper option <strong><img src="[*$this->g("estilo")*]imx/ver_imaxes.png" alt="Thumbnails" /> Thumbnails</strong> in able to see the images in a miniature version when navegating in the file list.',
	'tit_arbore' => 'View all files and folders in one page',
	'txt_arbore' => 'To view all the content of one root all in once, click on the upper option <strong><img src="[*$this->g("estilo")*]imx/arbore.png" alt="Tree View" /> Tree View</strong>, and there will appear all the folders of the root. If you  also want to view all the files of each folder, click on the right option <strong>[Complete Tree]</strong> and there will appear a list of the root\\\'s content on which you are.',
	'tit_buscar' => 'Search a file or a text in her metas',
	'txt_buscar' => 'You have two options to search for a file, the first one is by the upper menu <strong>Search</strong> and the second one, by writing part of the name in the field of <strong>Search:</strong> and then click in magnifying glass.<br /><br />On this search form screen you only have to write the text that belongs to the file or the folder you need to find, choose where you want to search (in this folder or root), in the fields where you want to search the text and press the buttom of <strong>Accept</strong>. And you will view underneath the form all the founded results.',
	'tit_accions' => 'Some action with only one file or folder like copy, move, delete...',
	'txt_accions' => 'You can make any action that you want with a file or folder from the column of <strong>Actions</strong> that is at the last line listed by file or folder.<br />This column allows you, in case of being able to use it, the actions of <strong>View Information</strong>, <strong>Copy</strong>, <strong>Move</strong>, <strong>Rename</strong>, <strong>Delete</strong>, <strong>Change Permissions</strong> or <strong>Download</strong>.',
	'tit_accions_multiple' => 'Some action with many files or folders at same time',
	'txt_accions_multiple' => 'If you have the neccesary permissions, you will be able to make a series of actions with multiple files and folders all at the same time. The actions that can be done are <strong>Copy</strong>, <strong>Move</strong>, <strong>Delete</strong>, <strong>Change Permissions</strong> and <strong>Download</strong>.',
	'h1_accions' => 'Which actions can I do on each file or folder listed?',
	'txt_info' => '<strong>View Information: </strong>This option allows you to view detail information as the size, date of creation, allowments or related data to aditional information as title and description, and a form to modify these data.',
	'txt_copiar' => '<strong>Copy: </strong>Allows to do a copy from a file or folder in a choosen place, if its a folder, it will copy all the information in the desired place.',
	'txt_mover' => '<strong>Move: </strong>Allows to move one folder to a desired place in the actual root. The selected file or folder will be copied in the desired place and then the original one will be deleted.',
	'txt_renomear' => '<strong>Rename: </strong>Allows to change the name of a file or folder.',
	'txt_eliminar' => '<strong>Delete: </strong>Deletes a file or folder and all its content.',
	'txt_permisos' => '<strong>Permissions: </strong>Allows to change real allowments from a file or folder.',
	'txt_descargar' => '<strong>File Download: </strong>Forces the download a file to computer. All the discharches that have been done will be measured by their use and also the times they have been discharged.',
	'txt_comprimir' => '<strong>Compress: </strong>Compresses a file or folder and all its content to be dicharched as a unique file saving bandwith, due to the fact that the wieght will be less than in a regular discharge.',
	'txt_redimensionar' => '<strong>Reduce Immage Copy: </strong>Allows to creat a smaller size from an image. The reduced copy will be an exact copy from the original but in a smaller version or you can select a part from the original image an creat a reduced copy.',
	'txt_extraer' => '<strong>Dicompress: </strong>Allows tp dicompress a packed file with TAR/GZ/TGZ/GZIP. Extracts all the recognized content creating an exact structure from the original files and folders. A file could not be extracted due to an invalid name, but it will continue with the rest of the list.',
	'txt_ver_contido' => '<strong>View Content: </strong>Allows to view an aditable text file. In case of bieng a used file web (such as PHP o HTML) the code will be colored.',
	'txt_editar' => '<strong>Edit: </strong>Allows to modify the content of a text file.',
	'h1_accions_multiple' => 'What actions can I do over a lot of files or folders at same time?',
	'txt_multiple_copiar' => '<strong>Copy: </strong>Allows to copy many files and folders at the same time. the copy will continue although a mistake may occur and then it will inform about the result.',
	'txt_multiple_mover' => '<strong>Move: </strong>Allows to move many files and folders at the same time. The selected ones will be moved even if a mistake may occur when moving one of them and then it will inform you about the result.',
	'txt_multiple_eliminar' => '<strong>Delete: </strong>Allows to delete many files and folders at the same time. The process will continue even if a mistake may occur when deleting one of them and then it will inform you about the result.',
	'txt_multiple_permisos' => '<strong>Permissions change: </strong>Allows to change the permissions to many files and folders at the same time. The process will continue even if a mistake may occur when changing one of them and then it will inform you in the result.',
	'txt_multiple_comprimir' => '<strong>Packed Download: </strong>Allows to discharge all the selected files and folders in one compress packet in order to save bandwith. The created file will be in ZIP format.',
	'h1_problemas' => 'How can I fix this problem?',
	'tit_problema_subir_arq' => 'I can\\\'t upload a file or an URL',
	'txt_problema_subir_arq' => 'If you can\\\'t upload a file and a URL you must check if have enough space in the disc in order to save it. To proove this, at the bottom of the page there must appear something like <strong> free space: XX MB</strong> that indicates the limit of weight to save in this root. Omit this information in case it doesn\\\'t appear.',
	'tit_problema_crear_dir' => 'I can\\\'t create a folder',
	'txt_problema_crear_dir' => 'The most frequent cause of not permiting to creat a folder is because the place where you want to creat doesn\\\'t have permissions. If this happens an advertisement will appear showing you the problem. If this problem can\\\'t be fixed by the user, please contact your Administrator.',
	'tit_problema_buscador' => 'The seeker doesn\\\'t find want I\\\'m looking for',
	'txt_problema_buscador' => 'If the seeker can\\\'t find the file you are looking for and  you know that exists in the root you are, ask the Administrator to reindex your root content to update the related stored data.',
	'tit_problema_miniaturas' => 'I can\\\'t view the miniature images',
	'txt_problema_miniaturas' => 'If you click <img src="[*$this->g("estilo")*]imx/ver_imaxes.png" alt="View Images" /> Miniatures</strong> the miniature images that come from big ones don\\\'t appear in the list, this means that you haven\\\'t created them. To do this click in <strong>View Information</strong>in the selected image and then click in <strong>Reduced Copy of the Image</strong> where you can creat a personalized copy or a proportional reduced one.',
	'tit_problema_paxinar' => 'I can\\\'t view all folder content',
	'txt_problema_paxinar' => 'When a folder is too extense (more than [*$this->g("paxinar")*] files or folders) the result is paginated. If you want to go to the last or next pages you can find at the bottom of a list where you can choose any page you want to be on.',
	'tit_problema_sesion' => 'If I spend some time without using the web page at the end the system logs me out.',
	'txt_problema_sesion' => 'The reason of this is that the system has a time limit for each session to avoid illegal access after you leave your computer alone. The session usually spends half an hour since you load the last page you use.',
);
?>
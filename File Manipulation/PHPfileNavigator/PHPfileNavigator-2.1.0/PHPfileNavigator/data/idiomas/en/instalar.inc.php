<?php
/****************************************************************************
* data/idiomas/en/instalar.inc.php
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
	'benvido' => 'Welcome to the PHPfileNavigator installation',
	'idioma' => 'Language',
	'email' => 'Administrator\\\'s email',
	'gd2' => 'GD2 library support',
	'zlib' => 'ZLIB library support',
	'si' => 'Yes',
	'non' => 'No',
	'enviar' => 'Send',
	'base_datos' => 'Data Base Information',
	'host' => 'Host',
	'db_nome' => 'Data Base',
	'nome' => 'Name',
	'usuario' => 'User',
	'contrasinal' => 'Password',
	'db_prefixo' => 'Table Prefix',
	'administrador' => 'Administrator Data',
	'rep_contrasinal' => 'Repeat Password',
	'raiz' => 'Main Root Data',
	'ra_path' => 'Absolute Rute',
	'ra_web' => 'Rute from web',
	'ra_conf' => 'Configuration File',
	'avisos_instalacion' => 'Installation alerts',
	'erros' => array(
		'1' => 'Data Base: The HOST is missing',
		'2' => 'Data Base: The NAME of the data base is missing',
		'3' => 'Data Base: The USER is missing',
		'4' => 'Administrator: The NAME is missing',
		'5' => 'Administrator: The USER is missing',
		'6' => 'Administrator: The PASSWORD is missing',
		'7' => 'Administrator: The passwords are different',
		'8' => 'Initial Root: The NAME is missing',
		'9' => 'Initial Root: The ABSOLUTE RUTE is missing',
		'10' => 'Initial Root: The WEB RUTE is missing',
		'11' => 'Initial Root: The HOST is missing',
		'12' => 'Initial Root: The CONFIGURATION FILE is missing',
		'13' => 'EMAIL is missing',
		'14' => 'Initial Root: The ABSOLUT RUTE folder doesn\\\'t exist',
		'15' => 'Initial Root: The ABSOLUTE RUTE folder doesn\\\'t have writing permissions',
		'16' => 'Initial Root: The CONFIGURATION FILE doesn\\\'t exist',
		'17' => 'Data Base: The HOST, NAME or PASSWORD conection data aren\\\'t correct',
		'18' => 'Data Base: The data base NAME doesn\\\'t exist',
		'19' => 'The folder data/conf/ must have writing permissions',
		'20' => 'This application has already been instaled before, if you try to install it again, all the saved data in the MySQL tables will be deleted (Except to actualize it).<br /><br />If you don\\\'t want to install this aplication, please delete the folder <i>instalar/</i>.',
		'21' => 'The tmp/ folder must have writing permissions',
		'22' => 'The data/logs/ folder must have writing permissions',
		'23' => 'The data/info/ folder must have writing permissions',
		'24' => 'Don\\\'t exists an previous version to update or data/conf/basicas.inc.php file don\\\'t have writing permissions.',
		'25' => 'With an update from a version previous to 2.0.0 and later than 1.5.7, It will do changes in the database structure without affect to content, also the logical update and improvements in the application files.<br /><br />To make a correct installation, only need overwrite the previous version with this, take care when overwrite the data/conf/defaults.inc.php and all will be installed correctly.<br /><br />You bear in mind that the config file data/conf/defaults.inc.php can contain config vars more recents that yours version, please, before overwrite this file check the diferences and use the new file.',
		'26' => 'It don\\\'t do some action.<br /><br />If you have a version equal than 2.0.0, only need overwrite your installation with this, taking care when overwrite the data/conf/defaults.inc.php and all will be installed correctly.<br /><br />You bear in mind that the config file data/conf/defaults.inc.php can contain config vars more recents that yours version, please, before overwrite this file check the diferences and use the new file.',
		'27' => 'File data/conf/basicas.inc.php don\\\'t have writing permissions.',
		'28' => 'You need choose a Charset',
		'29' => 'Some querys executed given an error. Try launch the installation again.',
		'30' => 'I can\\\'t update from a version equal or upper than this package. Please review that the version installed isn\\\'t the same that you are try install.',
	),
	'axuda' => array(
		'accion' => 'You can choose a installation mode:<br /><br /><strong>Installation: </strong>allow make a new installation deleteting the tables, if exists, and overwriting the config files.<br /><strong>Update from version >1.5.7 and <2.0.0: </strong>allow update a previous version installed, without lose data.<br /><strong>Do nothing: </strong>it don\\\'t modify the database nor change the config data.',
		'idioma' => 'You can choose the PHPfileNavigator language and use.',
		'gd2' => 'If the server has installed GD2 libraries to manage images and allow create good quality image thumbnails.',
		'zlib' => 'If the server has ZLIB libraries to compress and extract files.',
		'charset' => 'Your server charset.',
		'db_host' => 'Your MySQL server. <strong>f.e.: localhost</strong>',
		'db_nome' => 'Database name to be installed. <strong>It must exists before the installation.</strong>',
		'db_usuario' => 'MySQL user to access to database. He must have permissions to create and modify tables.',
		'db_contrasinal' => 'Password to access with this users.',
		'db_rep_contrasinal' => 'Repeat previous password.',
		'db_prefixo' => 'Table prefix. To avoid that you overwrite other tables with same name.',
		'ad_nome' => 'Admin user common name.',
		'ad_usuario' => 'User nick to login.',
		'ad_contrasinal' => 'Password to admin user.',
		'ad_rep_contrasinal' => 'Repeat previous password.',
		'ad_email' => 'Admin email. To this mail will be sent security alerts or access problems.',
		'ra_nome' => 'Generic name to this root. Allow to identificate in the root list if you have access to more than one. <strong>f.e.: Main Root</strong>',
		'ra_path' => 'Absolute route from server root. Before you can create more accesible roots.<br />Remember that you must use / instead inverted bar. <strong>f.e.: /var/www/html/docs/</strong>',
		'ra_web' => 'Web accesible path from domain root. <strong>f.e.: /docs/</strong>',
		'ra_host' => 'Domain name to manage. Without http <strong>f.e.: www.mydomain.com</strong>',
		'raices_atopadas' => 'It',
		'usuarios_atopados' => 'This is the relation with a group. When update you can choose only in this list, but then you can create and manage all users and groups.',
		'configuracions_atopadas' => 'Config file founded. In the new admin zone you can create, modify or delete config files and assing to groups or roots.',
		'aviso_instalacion' => 'If you check this option, the installation will sent to PHPfileNavigator developer a new installation advice mail. Only will sent the admin mail and host. <strong>Don\\\'t send</strong> any personal information as paths, user data or passwords. This allow to you to be informed of new versions or security advices.',
	),
	'instalacion_correcta' => 'PHPfileNavigator was installed correctly.<br /><br />You need delete instalar/ folder to finish the installation.<br /><br />Thanks to use this application.',
	'accion' => 'Action',
	'a:instalar' => 'Install',
	'a:actualizar_168' => 'Update from version > 1.5.7 and < 2.0.0',
	'a:nada' => 'Do Nothing',
	'usuarios' => 'Users',
	'charset' => 'Charset',
	'datos_xerais' => 'Generic Data',
	'raices_atopadas' => 'Founded Roots',
	'usuarios_atopados' => 'Founded Users',
	'admins' => 'Admins',
	'configuracions_atopadas' => 'Founded Confs',
	'doazon' => 'If you like this application or has been used in a company or integred in a non-free proyect, please make a donation, Thanks!!!!',
	'aviso_instalacion' => 'Intallation advice',
);
?>
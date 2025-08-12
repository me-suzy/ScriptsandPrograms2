<?php
/****************************************************************************
* data/idiomas/es/instalar.inc.php
*
* Textos para el idioma Spanish
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
	'benvido' => 'Bienvenido al instalador de PHPfileNavigator',
	'idioma' => 'Idioma',
	'email' => 'Email del Administrador',
	'gd2' => 'Soporte librer&iacute;as GD2',
	'zlib' => 'Soporte librer&iacute;as ZLIB',
	'si' => 'Si',
	'non' => 'No',
	'enviar' => 'Enviar',
	'base_datos' => 'Datos de la Base de Datos',
	'host' => 'Host',
	'db_nome' => 'Base de datos',
	'nome' => 'Nombre',
	'usuario' => 'Usuario',
	'contrasinal' => 'Contrase&ntilde;a',
	'db_prefixo' => 'Prefijo de las Tablas',
	'administrador' => 'Datos del Administrador',
	'rep_contrasinal' => 'Repetir Contrase&ntilde;a',
	'raiz' => 'Datos de la Ra&iacute;z Inicial',
	'ra_path' => 'Ruta absoluta',
	'ra_web' => 'Ruta desde web',
	'ra_conf' => 'Fichero de Configuraci&oacute;n',
	'avisos_instalacion' => 'Avisos de la instalaci&oacute;n',
	'erros' => array(
		'1' => 'Base de Datos: Falta el HOST',
		'2' => 'Base de Datos: Falta el NOMBRE de la base de datos',
		'3' => 'Base de Datos: Falta el USUARIO',
		'4' => 'Administrador: Falta el NOMBRE',
		'5' => 'Administrador: Falta el USUARIO',
		'6' => 'Administrador: Falta a CONTRASE&Ntilde;A',
		'7' => 'Administrador: Las contrase&ntilde;as son distintas',
		'8' => 'Ra&iacute;z Inicial: Falta el NOMBRE',
		'9' => 'Ra&iacute;z Inicial: Falta la RUTA ABSOLUTA',
		'10' => 'Ra&iacute;z Inicial: Falta la RUTA WEB',
		'11' => 'Ra&iacute;z Inicial: Falta el HOST',
		'12' => 'Ra&iacute;z Inicial: Falta el FICHERO DE CONFIGURACI&Oacute;N',
		'13' => 'Falta EMAIL',
		'14' => 'Ra&iacute;z Inicial: No existe el directorio RUTA ABSOLUTA',
		'15' => 'Ra&iacute;z Inicial: El directorio RUTA ABSOLUTA no tiene permisos de escritura',
		'16' => 'Ra&iacute;z Inicial: No existe el FICHERO DE CONFIGURACI&Oacute;N',
		'17' => 'Base de Datos: Los datos de conexi&oacute;n HOST, NOMBRE o CONTRASE&Ntilde;A no son correctos',
		'18' => 'Base de Datos: No existe la base de datos NOMBRE',
		'19' => 'El directorio data/conf/ debe tener permisos de escritura.',
		'20' => 'Esta aplicaci&oacute;n ya fue instalada con anterioridad, si vuelve a ejecutar la instalaci&oacute;n, eliminar&aacute; todos los datos almacenados en las tablas de MySQL.<br /><br />Si no quiere volver a instalar esta aplicaci&oacute;n, por favor borre el directorio <i>instalar/</i>',
		'21' => 'El directorio tmp/ debe tener permisos de escritura.',
		'22' => 'El directorio data/logs/ debe tener permisos de escritura.',
		'23' => 'El directorio data/info/ debe tener permisos de escritura.',
		'24' => 'No existe una instalaci&oacute;n anterior que actualizar el fichero data/conf/basicas.inc.php no tiene permisos de escritura.',
		'25' => 'Con la actualizaci&oacute;n desde una version anterior a 2.0.0 y posterior a 1.5.7, se har&aacute;n cambios en la base de datos que no afectar&aacute;n al contenido, adem&aacute;s de la l&oacute;gica actualizaci&oacute;n y mejoras mejoras en los ficheros que componen esta aplicaci&oacute;n.<br /><br />Para realizar una correcta instalaci&oacute;n, solo tiene que sobreescribir la instalaci&oacute;n anterior con esta, teniendo cuidado de mantener las configuraciones de data/conf/defaults.inc.php, y todo ser&aacute; instalado correctamente.<br /><br />Tenga en cuenta que el fichero de configuraciones data/conf/defaults.inc.php puede contener variables de configuraci&oacute;n m&aacute;s recentes que las que dispone la versi&oacute;n instalada actualmente, revise eses cambios y sobreescriba el fichero anterior con el nuevo incluido en esta versi&oacute;n.',
		'26' => 'No se realizar&aacute; ninguna operaci&oacute;n para la instalaci&oacute;n.<br /><br />Si dispone de una versi&oacute;n igual a 2.0.0 solo tiene que sobreescribir la instalaci&oacute;n anterior con esta, teniendo cuidado de mantener las configuraciones de data/conf/defaults.inc.php, y todo ser&aacute; instalado correctamente.<br /><br />Tenga en cuenta que el fichero de configuraciones data/conf/defaults.inc.php puede conter variables de configuraci&oacute;n m&aacute;s recientes que las que dispone la versi&oacute;n instalada actualmente, revise ese cambios y sobreescriba el fichero anterior con el nuevo incluido en esta versi&oacute;n.',
		'27' => 'El fichero data/conf/basicas.inc.php no tiene permisos de escritura.',
		'28' => 'Debes seleccionar un Juego de caracteres',
		'29' => 'Alguna de las consultas ha devuelto un error. Intente lanzar de nuevo la instalaci&oacute;n.',
		'30' => 'No se puede actualizar desde una versi&oacute;n igual o superior a la de este paquete. Por favor revise que la versi&oacute;n ya instalada no es ma misma que la que est&aacute; intentando instalar.',
	),
	'axuda' => array(
		'accion' => 'Puedes seleccionar el modo de instalaci&oacute;n.<br /><br /><strong>Instalaci&oacute;n: </strong>permite realizar una instalaci&oacute;n desde cero, vaciando las tablas en caso de que ya existieran y reescribiendo los ficheros de configuraci&oacute;n.<br /><strong>Actualizar desde versi&oacute;n > 1.5.7 y < 2.0.0: </strong>permite la instalaci&oacute;n de la aplicaci&oacute;n sin perder los datos almacenados en la base de datos ni los ficheros de configuraci&oacute;n. Adem&aacute;s modificar&aacute; autom&aacute;ticamente la estructura de las tablas que var&iacute;an y completar&aacute; las configuraci&oacute;nes nuevas.<br /><strong>No hacer nada: </strong>no modifica la base de datos ni var&iacute;a los datos de configuraci&oacute;n existentes.',
		'idioma' => 'Puedes seleccionar el idimoa que deseas para instalaci&oacute;n y uso del PHPfileNavigator.',
		'gd2' => 'Si el servidor dispone de las librer&iacute;as de tratamiento gr&aacute;fico GD2, para permitir crear copias de im&aacute;genes reducidas de buena calidad.',
		'zlib' => 'Si el servidor dispone de las librer&iacute;as para compresi&oacute;n y descompresi&oacute;n de ficheros.',
		'charset' => 'El juego de caractecres que deseas usar. Lo normal es que coincida con el servidor.',
		'db_host' => 'El servidor en el que est&aacute; instalado el MySQL. <strong>p.e..: localhost</strong>',
		'db_nome' => 'El nombre de la base de datos en donde ser&aacute; instalado. <strong>Debe existir en el momento de la instalaci&oacute;n.</strong>',
		'db_usuario' => 'El usuario mediante el cual se acceder&aacute; a la base de datos. Debe tener permisos de creaci&oacute;n y modificaci&oacute;n de tablas.',
		'db_contrasinal' => 'Contrase&ntilde;a de acceso del usuario a la base de datos.',
		'db_rep_contrasinal' => 'Repetir la contrase&ntilde;a anterior.',
		'db_prefixo' => 'Prefijo para las tablas. As&iacute; evitar&aacute;s que se pueda sobreescribir otras ya existentes con el mismo nombre.',
		'ad_nome' => 'Nombre com&uacute;n del usuario administrador.',
		'ad_usuario' => 'Usuario con el que acceder&aacute; a la aplicaci&oacute;n.',
		'ad_contrasinal' => 'Contrase&ntilde;a de acceso del usuario al PHPfileNavigator.',
		'ad_rep_contrasinal' => 'Repetir la contrase&ntilde;a anterior.',
		'ad_email' => 'Correo electr&oacute;nico del administrador. A este correo llegar&aacute;n las alertas de seguridad por intentos de instrusi&oacute;n o problemas de acceso.',
		'ra_nome' => 'Nombre gen&eacute;rico para esta ra&iacute;z. Sirve para identificarla en listado de ra&iacute;ces y en caso de que tengas acceso a m&aacute;s de una. <strong>p.e.: Ra&iacute;z Principal</strong>',
		'ra_path' => 'La ruta del directorio que se va a gestionar. Debe ser la absoluta desde la ra&iacute;z del servidor. Despu&eacute;s podr&aacute;s crear m&aacute;s raices de acceso.<br />Recuerda que debes usar / en vez de  en sistema windows. <strong>p.e.: /var/www/html/docs/</strong>',
		'ra_web' => 'La ruta de acceso por web desde la ra&iacute;z del dominio. <strong>p.e.: /docs/</strong>',
		'ra_host' => 'Nombre del dominio que se va a gestionar. Sin http. <strong>p.e.: www.midominio.com</strong>',
		'raices_atopadas' => 'Se encontraron las siguientes ra&iacute;ces que ser&aacute;n configuradas.',
		'usuarios_atopados' => 'Esta es la relaci&oacute;n de usuario con un determinado grupo. En la actualizaci&oacute;n podr&aacute;s seleccionar solo entre esta lista, pero una vez terminada podr&aacute;s gestionar todos los usuarios y grupos de manera m&aacute;s completa.',
		'configuracions_atopadas' => 'Ficheros de configuraci&oacute;n encontrados. En la nueva zona de administrador te permitir&aacute; duplicar, modificar o eliminar configuraciones as&iacute; como asignarlas por grupos y ra&iacute;ces.',
		'aviso_instalacion' => 'Si se marca esta opci&oacute;n se enviar&aacute; al desarrollador del PHPfileNavigator un correo de aviso de nueva instalaci&oacute;n en el que se remitir&aacute; &uacute;nicamente el correo electr&oacute;nico del administrador y el host en el que fue instalado. <strong>No se enviar&aacute;</strong> ning&uacute;n tipo de informaci&oacute;n personal como rutas, datos de usuario o contrase&ntilde;as. Esto te permite estar informado de las nuevas versiones o avisos de seguridad.<br />Puedes revisar el c&oacute;digo de env&iacute;o del correo en el fichero instalar/index.php entre las l&iacute;neas 84 y 100.',
	),
	'instalacion_correcta' => 'El PHPfileNavigator se ha instalado de forma correcta.<br /><br />Para iniciar su uso debe borrar el directorio instalar/ o se seguir&aacute; a cargar a pantalla de instalaci&oacute;n.<br /><br />Muchas gracias por usar esta aplicaci&oacute;n.',
	'accion' => 'Acci&oacute;n',
	'a:instalar' => 'Instalar',
	'a:actualizar_168' => 'Actualizar desde versi&oacute;n &gt; 1.5.7 y &lt; 2.0.0',
	'a:nada' => 'No hacer nada',
	'usuarios' => 'Usuarios',
	'charset' => 'Juego de caracteres',
	'datos_xerais' => 'Datos Gen&eacute;ricos',
	'raices_atopadas' => 'Ra&iacute;ces Encontradas',
	'usuarios_atopados' => 'Usuarios Encontrados',
	'admins' => 'Administradores',
	'configuracions_atopadas' => 'Configuraciones Encontradas',
	'doazon' => 'Si te gusta esta aplicaci&oacute;n o va a ser usada en una empresa o integrada en un proyecto no gratuito, por favor realiza una donaci&oacute;n. Gracias!!!!!',
	'aviso_instalacion' => 'Aviso de instalaci&oacute;n',
);
?>

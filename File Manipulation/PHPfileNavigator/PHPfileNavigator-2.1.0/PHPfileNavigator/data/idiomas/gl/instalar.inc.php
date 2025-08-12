<?php
/****************************************************************************
* data/idiomas/gl/instalar.inc.php
*
* Textos para el idioma Galician
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
	'benvido' => 'Benvido ao instalador do PHPfileNavigator',
	'idioma' => 'Idioma',
	'email' => 'Email',
	'gd2' => 'Soporte librer&iacute;as GD2',
	'zlib' => 'Soporte librer&iacute;as ZLIB',
	'si' => 'Si',
	'non' => 'Non',
	'enviar' => 'Enviar',
	'base_datos' => 'Datos de MySQL',
	'host' => 'Host',
	'db_nome' => 'Base de datos',
	'nome' => 'Nome',
	'usuario' => 'Usuario',
	'contrasinal' => 'Contrasinal',
	'db_prefixo' => 'Prefixo das T&aacute;boas',
	'administrador' => 'Datos do Administrador',
	'rep_contrasinal' => 'Repetir Contrasinal',
	'raiz' => 'Datos da Ra&iacute;z Inicial',
	'ra_path' => 'Ruta absoluta',
	'ra_web' => 'Ruta dende web',
	'ra_conf' => 'Arquivo de Configuraci&oacute;n',
	'avisos_instalacion' => 'Avisos da instalaci&oacute;n',
	'erros' => array(
		'1' => 'Base de Datos: Falta o HOST',
		'2' => 'Base de Datos: Falta o NOME da base de datos',
		'3' => 'Base de Datos: Falta o USUARIO',
		'4' => 'Administrador: Falta o NOME',
		'5' => 'Administrador: Falta o USUARIO',
		'6' => 'Administrador: Falta a CONTRASINAL',
		'7' => 'Administrador: As contrasinais son distintas',
		'8' => 'Ra&iacute;z Inicial: Falta o NOME',
		'9' => 'Ra&iacute;z Inicial: Falta a RUTA ABSOLUTA',
		'10' => 'Ra&iacute;z Inicial: Falta a RUTA WEB',
		'11' => 'Ra&iacute;z Inicial: Falta o HOST',
		'12' => 'Ra&iacute;z Inicial: Falta o ARQUIVO DE CONFIGURACI&Oacute;N',
		'13' => 'Falta EMAIL',
		'14' => 'Ra&iacute;z Inicial: Non existe o directorio RUTA ABSOLUTA',
		'15' => 'Ra&iacute;z Inicial: O directorio RUTA ABSOLUTA no ten permisos de escritura',
		'16' => 'Ra&iacute;z Inicial: Non existe o ARQUIVO DE CONFIGURACI&Oacute;N',
		'17' => 'Base de Datos: Os datos de conexi&oacute;n HOST, NOME ou CONTRASINAL non son correctos',
		'18' => 'Base de Datos: Non existe a base de datos NOME',
		'19' => 'O directorio data/conf/ debe ter permisos de escritura.',
		'20' => 'Esta aplicaci&oacute;n xa foi instalada con anterioridade, se volve a executar a instalaci&oacute;n, eliminar&aacute; todos os datos almacenados nas t&aacute;boas de MySQL.<br /><br />Se non quere volver a instalar esta aplicaci&oacute;n, por favor borre o directorio <i>instalar/</i> ou escolla outra <i>acci&oacute;n</i> a realizar.',
		'21' => 'O directorio tmp/ debe ter permisos de escritura.',
		'22' => 'O directorio data/logs/ debe ter permisos de escritura.',
		'23' => 'O directorio data/info/ debe ter permisos de escritura.',
		'24' => 'Non existe unha instalaci&oacute;n anterior que actualizar ou o arquivo data/conf/basicas.inc.php non ten permisos de escritura.',
		'25' => 'Coa actualizaci&oacute;n dende unha version anterior ou igual a 1.5.7, faranse cambios na base de datos que non afectar&aacute;n ao contido, ademais da l&oacute;xica actualizaci&oacute;n e melloras nos arquivos que compo&ntilde;en esta aplicaci&oacute;n.<br /><br />Para realizar unha correcta instalaci&oacute;n, s&oacute; ten que sobreescribir a instalaci&oacute;n anterior con esta, tendo coidado de manter as configuraci&oacute;ns de data/conf/defaults.inc.php, e todo ser&aacute; instalado correctamente.<br /><br />Te&ntilde;a en conta que o arquivo de configuraci&oacute;ns data/conf/defaults.inc.php pode conter variables de configuraci&oacute;n m&aacute;is recentes que as que disp&oacute;n a versi&oacute;n instalada actualmente, revise ese cambios e sobreescriba o arquivo anterior c&oacute; novo incluido en esta versi&oacute;n.',
		'26' => 'Non se realizar&aacute; ningunha operaci&oacute;n para a instalaci&oacute;n.<br /><br />Se disp&oacute;n de unha versi&oacute;n posterior a 1.5.7, s&oacute; ten que sobreescribir a instalaci&oacute;n anterior con esta, tendo coidado de manter as configuraci&oacute;ns de data/conf/defaults.inc.php, e todo ser&aacute; instalado correctamente.<br /><br />Te&ntilde;a en conta que o arquivo de configuraci&oacute;ns data/conf/defaults.inc.php pode conter variables de configuraci&oacute;n m&aacute;is recentes que as que disp&oacute;n a versi&oacute;n instalada actualmente, revise ese cambios e sobreescriba o arquivo anterior c&oacute; novo incluido en esta versi&oacute;n.',
		'27' => 'O arquivo data/conf/basicas.inc.php non ten permisos de escritura.',
		'28' => 'Debese escoller un Xogo de caracteres.',
		'29' => 'Algunha das consultas devolveu un erro. Intente lanzar de novo a instalaci&oacute;n.',
		'30' => 'Non se pode actualizar dende unha versi&oacute;n igual ou superior a de este paquete. Por favor revisa que a versi&oacute;n xa instalada non &eacute; a mesma que a que estas intentando instalar.',
	),
	'axuda' => array(
		'accion' => 'Podes escoller o modo de instalaci&oacute;n:<br /><br /><strong>Instalaci&oacute;n: </strong>permite realizar unha instalaci&oacute;n dende cero, vaciando as t&aacute;boas en caso de que xa existan e sobreescribindo os arquivos de configuraci&oacute;n.<br /><strong>Actualizar dende versi&oacute;n > 1.5.7. e < 2.0.0: </strong>permite a instalaci&oacute;n da aplicaci&oacute;n sen perder os datos almacenados na base de datos nin os arquivos de configuraci&oacute;n. Ademais modificar&aacute; autom&aacute;ticamente a estructura das t&aacute;boas que var&iacute;an e completar&aacute; as novas configuraci&oacute;ns.<br /></strong>Non facer nada: </strong>non modificar&aacute; a base de datos nin variar&aacute; os datos das configuraci&oacute;ns existentes.',
		'idioma' => 'Podes escoller o idioma que desexas para a instalaci&oacute;n e uso do PHPfileNavigator.',
		'gd2' => 'Si o servidor disp&oacute;n das librer&iacute;as de tratamento gr&aacute;fico GD2, para permitir crear copias das imaxes reducidas de boa calidade.',
		'zlib' => 'Si o servidor disp&ccedil;on das librer&iacute;as para compresi&oacute;n e descompresi&oacute;n de arquivos.',
		'charset' => 'O xogo de caract&eacute;res que desexas usar. O normal e que coincida co do servidor.',
		'db_host' => 'O servidor no que est&aacute; instalado o MySQL. <strong>p.e.: localhost</strong>',
		'db_nome' => 'O nome da base de datos en donde ser&aacute; instalado. <strong>Debe existir no momento da instalaci&oacute;n.</strong>',
		'db_usuario' => 'O usuario mediante o cal se acceder&aacute; a base de datos. Debe ter permisos de creaci&oacute;n e modificaci&oacute;n de t&aacute;boas.',
		'db_contrasinal' => 'Contrasinal de acceso do usuario a base de datos.',
		'db_rep_contrasinal' => 'Repetir o contrasinal anterior.',
		'db_prefixo' => 'Prefixo para as t&aacute;boas. As&iacute; evitar&aacute;s que se poida sobreescribir outras xa existentes co mesmo nome.',
		'ad_nome' => 'Nome com&uacute;n do usuario administrador.',
		'ad_usuario' => 'Usuario co que se acceder&aacute; a aplicaci&oacute;n.',
		'ad_contrasinal' => 'Contrasinal de acceso do usuario ao PHPfileNavigator.',
		'ad_rep_contrasinal' => 'Repetir o contrasinal anterior.',
		'ad_email' => 'Correo electr&oacute;nico do administrador. A este correo chegar&aacute;n as alertas de seguridade por intentos de intrusi&oacute;n ou problemas de acceso.',
		'ra_nome' => 'Nome xen&eacute;rico para esta ra&iacute;z. Sirve para identificala no listado de ra&iacute;ces e no caso de que te&ntilde;as acceso a m&aacute;is de unha. <strong>p.e.: Ra&iacute;z Inicial</strong>',
		'ra_path' => 'A ruta do directorio que desexas administrar. Debe ser dende a ra&iacute;z do servidor. Unha vez instalada poder&aacute;s crear m&aacute;is ra&iacute;ces de acceso.<br />Recorda que debes usar / en vez da barra invertida en sistemas Windows. <strong>p.e.: /var/www/html/docs/</strong>',
		'ra_web' => 'Ruta de acceso por web. Debe ser dende a ra&iacute;z do dominio. <strong>p.e.: /docs/</strong>',
		'ra_host' => 'Nome do dominio que se vai a xestionar. Sen http. <strong>p.e.: www.omeudominio.com</strong>',
		'raices_atopadas' => 'Atop&aacute;ronse as seguintes ra&iacute;ces que ser&aacute;n configuradas.',
		'usuarios_atopados' => 'Esta &eacute; a relaci&oacute;n de usuarios con un determinado grupo. Na actualizaci&oacute;n poder&aacute;s seleccionar s&oacute; entre esta lista, pero unha vez rematada poder&aacute;s xestionar todos os usuarios e grupos de maneira moito m&aacute;is completa.',
		'configuracions_atopadas' => 'Arquivos de configuraci&oacute;n atopados. Na nova &Aacute;rea de Administrador permitirache duplicar, modificar ou eliminar configuraci&oacute;ns, as&iacute; como asignalas por grupos e ra&iacute;ces.',
		'aviso_instalacion' => 'Si se marca esta opci&oacute;n enviarase ao desenrolador do PHPfileNavigator un correo de aviso de nova instalaci&oacute;n no que se remitir&aacute; &uacute;nicamente o correo electr&oacute;nico do administrador e o host no que que foi instalado. <strong>Non se enviar&aacute;</strong> ning&uacute;n tipo de informaci&oacute;n persoal como rutas, datos de usuario ou contrasinais. Isto permiteche estar informado das novas versi&oacute;ns ou avisos de seguridade.<br />Podes revisar o c&oacute;digo de env&iacute;o do correo no arquivo instalar/index.php entre as li&ntilde;as 84 e 100.',
	),
	'instalacion_correcta' => 'O PHPfileNavigator instalouse de forma correcta.<br /><br />Para comenzar o seu uso debe borrar o directorio instalar/ ou se seguir&aacute; a cargar a pantalla de instalaci&oacute;n.<br /><br />Moitas gracias por usar esta aplicaci&oacute;n.',
	'accion' => 'Acci&oacute;n',
	'a:instalar' => 'Instalar',
	'a:actualizar_168' => 'Actualizar dende versi&oacute;n > 1.5.7 e < 2.0.0',
	'a:nada' => 'Non facer nada',
	'usuarios' => 'Usuarios',
	'charset' => 'Xogo de caracteres',
	'datos_xerais' => 'Datos Xen&eacute;ricos',
	'raices_atopadas' => 'Ra&iacute;ces Atopadas',
	'usuarios_atopados' => 'Usuarios Atopados',
	'admins' => 'Administradores',
	'configuracions_atopadas' => 'Configuraci&oacute;ns Atopadas',
	'doazon' => 'Si che gusta esta aplicaci&oacute;n ou vai a ser usada en unha empresa ou integrada en un proxecto non gratuito, por favor fai un doaz&oacute;n. Gracias!!!!',
	'aviso_instalacion' => 'Aviso de instalaci&oacute;n',
);
?>

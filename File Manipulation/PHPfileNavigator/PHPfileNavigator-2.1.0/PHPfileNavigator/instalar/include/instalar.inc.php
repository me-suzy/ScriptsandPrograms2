<?php
/*******************************************************************************
* instalar/include/instalar.inc.php
*
* Ejectula la acción de nueva instalación
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

$accion = 'instalar';
$email = trim($vars->post('email'));
$gd2 = trim($vars->post('gd2'));
$zlib = trim($vars->post('zlib'));
$charset = trim($vars->post('charset'));
$db_host = trim($vars->post('db_host'));
$db_nome = trim($vars->post('db_nome'));
$db_usuario = trim($vars->post('db_usuario'));
$db_contrasinal = trim($vars->post('db_contrasinal'));
$db_prefixo = trim($vars->post('db_prefixo'));
$ad_nome = trim($vars->post('ad_nome'));
$ad_usuario = trim($vars->post('ad_usuario'));
$ad_contrasinal = trim($vars->post('ad_contrasinal'));
$rep_contrasinal = trim($vars->post('rep_contrasinal'));
$ra_nome = trim($vars->post('ra_nome'));
$ra_host = trim($vars->post('ra_host'));

$ra_path = str_replace('\\','/',trim($vars->post('ra_path'))).'/';
$ra_path = str_replace('//','/',$ra_path);
$ra_web = str_replace('\\','/',trim($vars->post('ra_web'))).'/';
$ra_web = str_replace('//','/',$ra_web);

if (empty($db_host)) $erro[] = 1;
if (empty($db_nome)) $erro[] = 2;
if (empty($db_usuario)) $erro[] = 3;
if (empty($ad_nome)) $erro[] = 4;
if (empty($ad_usuario)) $erro[] = 5;
if (empty($ad_contrasinal)) $erro[] = 6;
if ($ad_contrasinal != $rep_contrasinal) $erro[] = 7;
if (empty($ra_nome)) $erro[] = 8;
if (empty($ra_path)) $erro[] = 9;
if (empty($ra_web)) $erro[] = 10;
if (empty($ra_host)) $erro[] = 11;
if (empty($email)) $erro[] = 13;
if (empty($charset)) $erro[] = 28;

if (!is_dir($ra_path)) $erro[] = 14;
if ($con = @mysql_connect($db_host, $db_usuario, $db_contrasinal)) {
	if (!@mysql_select_db($db_nome, $con)) $erro[] = 18;
} else $erro[] = 17;
if (!is_writable($paths['conf'])) $erro[] = 19;
if (!is_writable($paths['tmp'])) $erro[] = 21;
if (!is_writable($paths['logs'])) $erro[] = 22;
if (!is_writable($paths['info'])) $erro[] = 23;

if (is_array($erro)) {
	include ($paths['instalar'].'plantillas/cab_instalar.inc.php');
} else {
	$arq_mysql = $paths['instalar'].'mysql/instalar.sql';
	$consultas = @fread(@fopen($arq_mysql, 'r'), @filesize($arq_mysql));
	$consultas = str_replace('EXISTS ', "EXISTS $db_prefixo", $consultas);
	$consultas = explode(';', $consultas);
	$erro_q = array();

	foreach ((array)$consultas as $q) {
		$q = trim($q);

		if (empty($q)) {
			continue;
		}

		if (!@mysql_query($q, $con)) {
			$erro[] = 29;
			$erro_q[] = '['.$q.'] = '.mysql_error($con);
		}
	}

	$consultas = include ($paths['instalar'].'mysql/instalar.php');

	foreach ($consultas as $q) {
		$q = trim($q);

		if (empty($q)) {
			continue;
		}

		if (!@mysql_query($q, $con)) {
			$erro[] = 29;
			$erro_q[] = '['.$q.'] = '.mysql_error($con);
		}
	}

	if (!is_dir($paths['info'].'usuario1')) {
		@mkdir($paths['info'].'usuario1');
	}

	if (!is_dir($paths['info'].'raiz1')) {
		@mkdir($paths['info'].'raiz1');
	}

	include_once ($paths['include'].'class_arquivos.php');

	$conf->inicial('default');
	$arquivos = new PFN_Arquivos($conf);
	$arquivos->crear_htaccess($ra_path);

	if ($erro) {
		include ($paths['instalar'].'plantillas/cab_instalar.inc.php');
	} else {
		include ($paths['instalar'].'include/basicas.inc.php');

		basicas(array(
			'version' => $PFN_version,
			'idioma' => $idioma,
			'estilo' => 'estilos/pfn/',
			'email' => $email,
			'gd2' => $gd2,
			'zlib' => $zlib,
			'charset' => $charset,
			'db:host' => $db_host,
			'db:base_datos' => $db_nome,
			'db:usuario' => $db_usuario,
			'db:contrasinal' => $db_contrasinal,
			'db:prefixo' => $db_prefixo
		));

		include ($paths['instalar'].'plantillas/ok.inc.php');
	}
}

if ($con) {
	@mysql_close($con);
}
?>

<?php
/*******************************************************************************
* instalar/include/actualizar_168.inc.php
*
* Ejecuta la actualización desde una version anterior a la 2.0.0
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

if (is_array($actual) && is_writable($paths['conf'].'basicas.inc.php')) {
	if ($con = @mysql_connect($actual['db']['host'], $actual['db']['usuario'], $actual['db']['contrasinal'])) {
		if (!@mysql_select_db($actual['db']['base_datos'], $con)) $erro[] = 18;
	} else $erro[] = 17;	
} else {
	$erro[] = 24;
}

if ($actual['version'] >= '200') {
	$erro[] = 30;
}

if (!is_writable($paths['conf'])) $erro[] = 19;
if (!is_writable($paths['tmp'])) $erro[] = 21;
if (!is_writable($paths['logs'])) $erro[] = 22;
if (!is_writable($paths['info'])) $erro[] = 23;

$accion = 'actualizar_168';
$charset = trim($vars->post('charset'));
$ids_grupo = $vars->post('ids_grupo');

if (empty($charset)) $erro[] = 28;

if (is_array($erro)) {
	include ($paths['instalar'].'plantillas/cab_instalar.inc.php');
} else {
	$db_prefixo = $actual['db']['prefixo'];

	$arq_mysql = $paths['instalar'].'mysql/actualizar_168.sql';
	$consultas = @fread(@fopen($arq_mysql, 'r'), @filesize($arq_mysql));
	$consultas = str_replace('EXISTS ', 'EXISTS '.$actual['db']['prefixo'], $consultas);
	$consultas = str_replace('ALTER IGNORE TABLE ', 'ALTER IGNORE TABLE '.$actual['db']['prefixo'], $consultas);
	$consultas = explode(';', $consultas);

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

	$consultas = include ($paths['instalar'].'mysql/actualizar_168.php');

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

	include_once ($paths['include'].'formatear.php');
	include_once ($paths['include'].'mysql.php');
	include_once ($paths['include'].'clases.php');
	include_once ($paths['include'].'class_usuarios.php');
	include_once ($paths['include'].'class_niveles.php');
	include_once ($paths['include'].'class_arquivos.php');

	$usuarios = new PFN_Usuarios($conf);
	$niveles = new PFN_Niveles($conf);
	$arquivos = new PFN_Arquivos($conf);

	$conf->inicial('default');

	$q = 'INSERT INTO '.$db_prefixo.'raices_grupos_configuracions'
		.' (id_raiz,id_grupo,id_conf) VALUES';

	for ($usuarios->init('raices'); $usuarios->mais(); $usuarios->seguinte()) {
		$q .= ' ('.$usuarios->get('id').',1,3), ('.$usuarios->get('id').',2,3),';

		if (!is_dir($paths['info'].'raiz'.$usuarios->get('id'))) {
			@mkdir($paths['info'].'raiz'.$usuarios->get('id'));
		}

		$arquivos->crear_htaccess($usuarios->get('path'));
	}

	if (!@mysql_query((substr($q, 0, -1).';'), $con)) {
		$erro[] = 29;
		$erro_q[] = '['.$q.'] = '.mysql_error($con);
	}

	for ($usuarios->init('usuarios'); $usuarios->mais(); $usuarios->seguinte()) {
		$q = 'UPDATE '.$db_prefixo.'usuarios'
			.' SET id_grupo = "'.intval($ids_grupo[$usuarios->get('id')]).'"'
			.' WHERE id = "'.$usuarios->get('id').'"'
			.' LIMIT 1;';

		if (!@mysql_query($q, $con)) {
			$erro[] = 29;
			$erro_q[] = '['.$q.'] = '.mysql_error($con);
		}

		if (!is_dir($paths['info'].'usuario'.$usuarios->get('id'))) {
			@mkdir($paths['info'].'usuario'.$usuarios->get('id'));
		}
	}

	$lista = $niveles->carga_contido($paths['conf'], true, true);

	$mais = false;
	$q = 'INSERT INTO '.$db_prefixo.'configuracions'
		.' (conf,vale) VALUES';

	foreach ($lista['nome'] as $v) {
		if (ereg('\.inc\.php$', $v)
		&& ($v != 'default.inc.php')
		&& ($v != 'basicas.inc.php')
		&& ($v != 'login.inc.php')) {
			$mais = true;
			$q .= ' ("'.str_replace('.inc.php', '', $v).'",1),';
		}
	}

	if ($mais) {
		if (!@mysql_query((substr($q, 0, -1).';'), $con)) {
			$erro[] = 29;
			$erro_q[] = '['.$q.'] = '.mysql_error($con);
		}
	}

	if ($erro) {
		include ($paths['instalar'].'plantillas/cab_instalar.inc.php');
	} else {
		include ($paths['instalar'].'include/basicas.inc.php');

		basicas(array(
			'version' => $PFN_version,
			'idioma' => $actual['idioma'],
			'estilo' => 'estilos/pfn/',
			'email' => $actual['email'],
			'gd2' => $actual['gd2'],
			'zlib' => $actual['zlib'],
			'charset' => $charset,
			'db:host' => $actual['db']['host'],
			'db:base_datos' => $actual['db']['base_datos'],
			'db:usuario' => $actual['db']['usuario'],
			'db:contrasinal' => $actual['db']['contrasinal'],
			'db:prefixo' => $actual['db']['prefixo']
		));

		include ($paths['instalar'].'plantillas/ok.inc.php');
	}
}

@mysql_close($con);
?>

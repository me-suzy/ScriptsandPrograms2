<?php
/*******************************************************************************
* instalar/index.php
*
* Carga lo necesario para la visualización de la pantalla de instalación
*

PHPfileNavigator versión 2.0.1

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

$paths['instalar'] = $paths['web'].'instalar/';

$idioma = $vars->get('idioma')?$vars->get('idioma'):$vars->post('idioma');
$idiomas_valen = array();

if (is_file($paths['conf'].'basicas.inc.php')) {
	$actual = include ($paths['conf'].'basicas.inc.php');
} else {
	$actual = false;
}

$conf->inicial('default');
$conf->p('estilos/pfn/','estilo');
$conf->p('en','idioma');
$conf->textos('idiomas');

foreach ($conf->t('lista_idiomas') as $k => $v) {
	if (is_dir($paths['idiomas'].$k)) {
		$idiomas_valen[$k] = $v;
	}
}

if (empty($idioma)) {
	if (is_array($actual) && isset($idiomas_valen[$actual['idioma']])) {
		$idioma = $actual['idioma'];
	} else {
		$idioma = 'es';
	}
} else {
	$idioma = empty($idiomas_valen[$idioma])?'es':$idioma;
}

$conf->p($idioma, 'idioma');
$conf->textos('web');
$conf->textos('instalar');
$conf->textos('idiomas');

include ($paths['plantillas'].'cab.inc.php');

$erro = false;
$accion = $vars->get('accion')?$vars->get('accion'):$vars->post('accion');

$tempo->rexistra('preplantillas');

if ($vars->post('executa') == 'true') {
	if ($accion == 'instalar') {
		include ($paths['instalar'].'include/instalar.inc.php');
	} elseif ($accion == 'actualizar_168') {
		include ($paths['instalar'].'include/actualizar_168.inc.php');
	} else {
		include ($paths['instalar'].'include/nada.inc.php');
	}

	if ($vars->post('aviso_instalacion') == 'true') {
		if ($accion == 'instalar') {
			$msx_tit = 'Aviso de nova instalacion';
			$msx_txt = 'Host de instalacion: '.$ra_host
				."\n\n".'Servidor: '.getenv('SERVER_NAME')
				."\n\n".'Correo: '.$email;
			$msx_email = $email;
		} elseif ($accion == 'actualizar_168') {
			$msx_tit = 'Aviso de actualizacion';
			$msx_txt = 'Servidor: '.getenv('SERVER_NAME')."\n\n".'Correo: '.$actual['email'];
			$msx_email = $actual['email'];
		} else {
			$msx_tit = 'Aviso de re-instalacion';
			$msx_txt = 'Servidor: '.getenv('SERVER_NAME')."\n\n".'Correo: '.$actual['email'];
			$msx_email = $actual['email'];
		}

		@mail('phpfilenavigator@litoweb.net', $msx_tit, $msx_txt, 'FROM: '.$msx_email);
	}
} else {
	if (is_array($actual)) {
		$gd2 = $actual['gd2'];
		$zlib = $actual['zlib'];
		$db_nome = $actual['db']['base_datos'];
		$db_usuario = $actual['db']['usuario'];
		$db_host = $actual['db']['host'];
		$db_prefixo = $actual['db']['prefixo'];
		$email = $actual['email'];
	} else {
		$db_host = 'localhost';
		$db_prefixo = 'pfn_';
	}

	$ra_web = dirname($vars->server('PHP_SELF')).'/';
	$ra_path = dirname($vars->server('PATH_TRANSLATED')).'/';
	$ra_host = $vars->server('SERVER_NAME');

	if (!is_dir($ra_path)) $erro[] = 14;
	if (!is_writable($paths['conf'])) $erro[] = 19;
	if (!is_writable($paths['tmp'])) $erro[] = 21;
	if (!is_writable($paths['logs'])) $erro[] = 22;
	if (!is_writable($paths['info'])) $erro[] = 23;

	if (empty($accion)) {
		if (is_array($actual)) {
			if ($actual['version'] < 200) {
				$erro[] = 25;
				$accion = 'actualizar_168';
			} else {
				$erro[] = 26;
				$accion = 'nada';
			}
		} else {
			$accion = 'instalar';
		}
	} elseif ($accion == 'instalar') {
		if (is_array($actual)) $erro[] = 20;
	} elseif ($accion == 'actualizar_168') {
		if (!is_array($actual)) $erro[] = 24;
		if ($actual['version'] >= 200) $erro[] = 30;
	} else {
		$accion = 'nada';
		if (!is_array($actual)) $erro[] = 24;
		$erro[] = 26;
	}

	include ($paths['instalar'].'plantillas/cab_instalar.inc.php');
}

include ($paths['plantillas'].'pe.inc.php');
?>

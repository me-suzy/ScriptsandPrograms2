<?php
/****************************************************************************
* data/accions/redimensionar.inc.php
*
* Realiza la visualización o acción de crear un thumbnail de una imagen
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

defined('OK') && defined('ACCION') or die();

include ($paths['plantillas'].'cab.inc.php');

$tempo->rexistra('precodigo');

$destino = $conf->g('raiz','path').$niveles->path_correcto($dir);
$imx_path = $niveles->path_correcto($destino.'/'.$vars->get('cal'));
$mais = $vars->get('mais_0');
$fin = false;
$estado_accion = '';
$estado = true;

if ($vars->get('executa')) {
	@set_time_limit($conf->g('tempo_maximo'));
	@ini_set('memory_limit', $conf->g('memoria_maxima'));

	if ($vars->get('modo') == 'recortar') {
		$imaxes->recortar($imx_path, $vars->get('posX'), $vars->get('posY'), $vars->get('ancho'), $vars->get('alto'));
	} else {
		$imaxes->reducir($imx_path);
	}

	if (empty($mais)) {
		$fin = true;
	} else {
		$vars->get('cal', $mais);
		$vars->get('mais_0', '');

		for ($i = 0, $cnt = 0; $i < $conf->g('inc','limite'); $i++) {
			if ($vars->get("mais_$i") != '') {
				$vars->get("mais_$cnt", $vars->get("mais_$i"));
				$vars->get("mais_$i", '');
				$cnt++;
			}
		}

		$imx_path = $niveles->path_correcto($destino.'/'.$vars->get('cal'));
	}
}

if ($vars->post('eliminar_peq')) {
	@unlink($imaxes->nome_pequena($imx_path));
	$estado_accion = $conf->t('estado.redimensionar', 2);
	$fin = true;
}

if (!$fin && $conf->g('imaxes','pequena') && $datos = $imaxes->e_imaxe($imx_path)) {
	$accions->arquivos($arquivos);

	$estado = $accions->log_ancho_banda(@filesize($imx_path));

	if ($estado === true) {
		$hai_pequena = is_file($imaxes->nome_pequena($imx_path));

		include ($paths['plantillas'].'redimensionar.inc.php');
	} else {
		$fin = true;
	}
}

if ($fin) {
	if (!$estado_accion) {
		if ($estado === true) {
			$estado_accion = $conf->t('estado.redimensionar', 1);
		} elseif ($estado === -1) {
			$estado_accion = $conf->t('estado.descargar', 3);
		} else {
			$estado_accion = $conf->t('estado.descargar', 2);
		}
	}

	include ($paths['web'].'opcions.inc.php');
	include ($paths['web'].'navega.inc.php');
}

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

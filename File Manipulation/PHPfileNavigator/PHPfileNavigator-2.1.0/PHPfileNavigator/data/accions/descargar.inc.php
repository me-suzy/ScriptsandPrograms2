<?php
/****************************************************************************
* data/accions/descargar.inc.php
*
* Realiza la acción de descarga de un fichero
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

defined('OK') && defined('ACCION') or die();

PFN_quita_url_SERVER('zlib');

$header = 'Content-Type: application/octet-stream'
	."\n".'Content-Type: application/force-download'
	."\n".'Content-Type: application/download'
	."\n".'Content-Transfer-Encoding: binary'
	."\n".'Pragma:no-cache'
	."\n".'Expires:0';

include_once ($paths['include'].'class_arquivos.php');
include_once ($paths['include'].'class_inc.php');

$arquivos = new PFN_Arquivos($conf);
$inc = new PFN_INC($conf);

$inc->arquivos($arquivos);
$inc->carga_datos($arquivo);
$accions->arquivos($arquivos);

if ($vars->get('zlib')
&& ($conf->g('zlib') == true)
&& $conf->g('permisos', 'comprimir')) {
	@set_time_limit($conf->g('tempo_maximo'));
	@ini_set('memory_limit', $conf->g('memoria_maxima'));

	include_once ($paths['include'].'class_easyzip.php');

	$EasyZIP->comeza($arquivo);
	$contido = &$EasyZIP->zipFile();
	$tamano = strlen($contido);

	$estado = $accions->log_ancho_banda($tamano);

	if ($estado === true) {
		$inc->mais_datos('descargado', ($inc->valor('descargado') + 1));
		$inc->crea_inc($arquivo.(($tipo == 'dir')?'/':''), $tipo);

		$header .= "\n".'Content-Disposition: attachment; filename='
			.str_replace(' ','_',"$cal.zip")
			."\n".'Content-Length: '.$tamano;

		Header($header);

		echo $contido;
	} elseif ($estado === -1) {
		$erro = true;
		$estado_accion = $conf->t('estado.descargar', 3);
	} else {
		$erro = true;
		$estado_accion = $conf->t('estado.descargar', 2);
	}

	unset($contido);
} elseif (is_file($arquivo)) {
	@set_time_limit($conf->g('tempo_maximo'));
	@ini_set('memory_limit', $conf->g('memoria_maxima'));

	$tamano = PFN_espacio_disco($arquivo, true);

	$estado = $accions->log_ancho_banda($tamano);

	if ($estado === true) {
		$inc->mais_datos('descargado', ($inc->valor('descargado') + 1));
		$inc->crea_inc($arquivo, 'arq');

		$modo = ($vars->get('modo') == '')?$conf->g('descarga_defecto'):$vars->get('modo');

		if ($vars->get('modo') == 'descargar') {
			$header .= "\n".'Content-Disposition: attachment; filename='.str_replace(' ','_',$vars->get('cal'))
				."\n".'Content-Length: '.$tamano;

			Header($header);

			echo $arquivos->get_contido($arquivo);
		} else {
			Header('Location: '.$niveles->enlace($dir, $cal));
			exit;
		}
	} elseif ($estado === -1) {
		$erro = true;
		$estado_accion = $conf->t('estado.descargar', 3);
	} else {
		$erro = true;
		$estado_accion = $conf->t('estado.descargar', 2);
	}
} else {
	$erro = true;
}

if ($erro) {
	$tempo->rexistra('preplantillas');

	include ($paths['plantillas'].'cab.inc.php');
	include ($paths['web'].'opcions.inc.php');

	$tempo->rexistra('precodigo');

	include ($paths['web'].'navega.inc.php');

	$tempo->rexistra('postcodigo');

	include ($paths['plantillas'].'pe.inc.php');
}
?>

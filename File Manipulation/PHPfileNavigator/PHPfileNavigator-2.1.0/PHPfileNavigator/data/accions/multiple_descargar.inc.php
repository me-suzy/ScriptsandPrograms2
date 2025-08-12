<?php
/****************************************************************************
* data/accions/multiple_descargar.inc.php
*
* Realiza la visualización o acción de descargar multiples ficheros y
* directorios
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

PFN_quita_url_SERVER('nome_comprimido');

$nome_comprimido = $vars->get('nome_comprimido');
$multiple_escollidos = (array)$vars->post('multiple_escollidos');
$erro = false;

if ($conf->g('columnas','multiple')
&& ($conf->g('zlib') == true)
&& (count($multiple_escollidos) > 0)
&& !empty($nome_comprimido)
&& !empty($dir)) {
	$header = 'Content-Type: application/octet-stream'
		."\n".'Content-Type: application/force-download'
		."\n".'Content-Type: application/download'
		."\n".'Content-Transfer-Encoding: binary'
		."\n".'Pragma:no-cache'
		."\n".'Expires:0';

	@set_time_limit($conf->g('tempo_maximo'));
	@ini_set('memory_limit', $conf->g('memoria_maxima'));

	include_once ($paths['include'].'class_easyzip.php');
	$EasyZIP->pon_dirbase($conf->g('raiz','path')
		.$accions->path_correcto($dir.'/')
		.'/'.$multiple_escollidos[0]);

	foreach ($multiple_escollidos as $v) {
		$erro = false;
		$cal = $accions->nome_correcto($v);
		$arquivo = $conf->g('raiz','path').$accions->path_correcto($dir.'/').'/'.$cal;

		if (!file_exists($arquivo)) {
			$erro = true;
		}

		if (!$erro && $accions->e_dir($arquivo)) {
			$EasyZIP->addDir($arquivo);
		} elseif (!$erro) {
			$EasyZIP->addFile($arquivo);
		}
	}

	$contido = &$EasyZIP->zipFile();

	include_once ($paths['include'].'class_arquivos.php');
	include_once ($paths['include'].'class_inc.php');

	$arquivos = new PFN_Arquivos($conf);
	$inc = new PFN_INC($conf);

	$inc->arquivos($arquivos);
	$inc->carga_datos($arquivo);
	$accions->arquivos($arquivos);

	$tamano = strlen($contido);

	$estado = $accions->log_ancho_banda($tamano);

	if ($estado === true) {
		$nome_comprimido = strstr($nome_comprimido, '.zip')?$nome_comprimido:($nome_comprimido.'.zip');
		$header .= "\n".'Content-Disposition: attachment; filename='.str_replace(' ','_',$nome_comprimido)
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
} else {
	$erro = true;
}

if ($erro) {
	include ($paths['plantillas'].'cab.inc.php');
	include ($paths['web'].'opcions.inc.php');

	$tempo->rexistra('precodigo');

	include ($paths['web'].'navega.inc.php');

	$tempo->rexistra('postcodigo');

	include ($paths['plantillas'].'pe.inc.php');
}
?>

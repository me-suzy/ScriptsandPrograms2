<?php
/****************************************************************************
* data/accions/subir_url.inc.php
*
* Realiza la visualización o acción de subir un una url remota al servidor
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


include ($paths['plantillas'].'cab.inc.php');
include ($paths['web'].'opcions.inc.php');

$tempo->rexistra('precodigo');

if ($conf->g('inc','estado')) {
	include_once ($paths['include'].'class_inc.php');
	$inc = new PFN_INC($conf);
}

$erro = false;

if ($vars->post('executa')) {
	$donde = $conf->g('raiz','path').$niveles->path_correcto($dir.'/');
	$cal = $niveles->nome_correcto($vars->post('nome_arquivo'));
	$nome_url = stripslashes($vars->post('nome_url'));
	$nome_url = eregi("^http",$nome_url)?$nome_url:"http://$nome_url";

	if (!strstr($cal, '.')) {
		if (ereg('\/$', $nome_url)) {
			$cal .= '.html';
		} elseif (ereg('^http[s]*:\/\/.+\/.+$', $nome_url)) {
			if (strstr($nome_url,'?')) {
				$docu = explode('?', $nome_url);
				$docu = $docu[0];
			} else {
				$docu = $nome_url;
			}

			$docu = explode('.', $docu);
			$ext = end($docu);

			if (strlen($ext) > 2 && strlen($ext) < 5) {
				$cal .= ".$ext";
			} else {
				$cal .= '.html';
			}
		} else {
			$cal .= '.html';
		}
	}

	if ($vars->post('cancelar') != '') {
		if (is_file($donde.'/'.$cal)) {
			if ($conf->g('raiz','peso_maximo') > 0) {
				$peso_este = PFN_espacio_disco($donde.'/'.$cal, true);
				$peso_este = $conf->g('raiz', 'peso_actual') - $peso_este;

				$usuarios->accion('peso', $peso_este, $conf->g('raiz','id'));
			}

			unlink($donde.'/'.$cal);
		}

		$estado_accion = $conf->t('estado.subir_url',6);
	} elseif ($nome_url != '' && $vars->post('nome_arquivo') != '') {
		$accions->arquivos($arquivos);

		if ($vars->post('sobreescribir') == 1 && file_exists($donde.'/'.$cal)) {
			$imaxe = @GetImageSize($donde.'/'.$cal);

			if (is_array($imaxe) && file_exists($imaxes->nome_pequena($donde.'/'.$cal))) {
				@unlink($imaxes->nome_pequena($donde.'/'.$cal));
			}

			@unlink($donde.'/'.$cal);
		}

		$accions->subir_url($nome_url, $donde, $cal);
		$estado = $accions->estado_num('subir_url');
		$estado_accion = $conf->t('estado.subir_url',intval($estado));

		if ($accions->estado('subir_url')) {
			if ($conf->g('raiz','peso_maximo') > 0) {
				$peso_este = PFN_espacio_disco($donde.'/'.$cal, true);

				if ($peso_este + $conf->g('raiz', 'peso_actual') > $conf->g('raiz','peso_maximo')) {
					@unlink($donde.'/'.$cal);
					$estado_accion = $conf->t('estado.subir_url', 7).'<br />';
					$erro = true;
				}
			}

			$ancho_banda = $accions->log_ancho_banda($peso_este);

			if (!$ancho_banda) {
				@unlink($donde.'/'.$cal);
				$estado_accion = $conf->t('estado.subir_url', 9).'<br />';
				$erro = true;
			}

			if (!$erro && $conf->g('inc','estado')) {
				$inc->arquivos($arquivos);
				$arq_inc = $inc->crea_inc($donde.'/'.$cal,'url');
			}

			if (!$erro && $conf->g('inc','indexar')) {
				include_once ($paths['include'].'class_indexador.php');

				$indexador = new PFN_Indexador($conf);
				$indexador->alta_modificacion("$dir/", $cal, $arq_inc);
			}

			if (!$erro && $conf->g('raiz','peso_maximo') > 0) {
				$peso_este += $conf->g('raiz', 'peso_actual');

				if ($conf->g('inc','estado')) {
					$peso_este += PFN_espacio_disco($arq_inc, true);
				}

				$conf->p($peso_este, 'raiz', 'peso_actual');
				$usuarios->init('peso', $peso_este, $conf->g('raiz','id'));
			}
		}
	}

	include ($paths['web'].'navega.inc.php');
} else {
	$msx_adv = $conf->t('estado.subir_url',4);

	include ($paths['plantillas'].'posicion.inc.php');
	include ($paths['plantillas'].'subir_url.inc.php');
}

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

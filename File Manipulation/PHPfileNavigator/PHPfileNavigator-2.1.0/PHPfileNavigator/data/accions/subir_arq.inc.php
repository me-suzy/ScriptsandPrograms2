<?php
/****************************************************************************
* data/accions/subir_arq.inc.php
*
* Realiza la visualización o acción de subir un fichero al servidor
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

$cantos = $vars->get('cantos')?intval($vars->get('cantos')):1;

PFN_quita_url_SERVER('cantos');

$tempo->rexistra('precodigo');

if ($conf->g('inc','estado')) {
  include_once ($paths['include'].'class_inc.php');
  $inc = new PFN_INC($conf);
}

if ($vars->post('executa')) {
	@set_time_limit($conf->g('tempo_maximo'));
	@ini_set('memory_limit', $conf->g('memoria_maxima'));

	$imaxes->arquivos($arquivos);
	$accions->arquivos($arquivos);

	if ($conf->g('inc','estado')) {
		$inc->arquivos($arquivos);
	}

	if ($conf->g('inc','indexar')) {
		include_once ($paths['include'].'class_indexador.php');
		$indexador = new PFN_Indexador($conf);
	}

	$upload_dir = $conf->g('raiz','path').$niveles->path_correcto($dir.'/');
	$files = $vars->files('');
	$files = $files['nome_arquivo'];
	$opc_imaxes = $vars->post('imaxe');
	$sobreescribir = $vars->post('sobreescribir');
	$recortar = false;
	$i = 1;

	foreach ((array)$files['name'] as $k => $v) {
		if ((!empty($v) && ($files['size'][$k] == 0 || empty($files['tmp_name'][$k])))
		|| $files['size'][$k] > $conf->g('inc','peso')) {
			$estado_accion .= $v.': '.$conf->t('estado.subir_arq', 5).'<br />';
			continue;
		} elseif (empty($v)) {
			continue;
		} else {
			if ($conf->g('raiz','peso_maximo') > 0) {
				$peso_este = $files['size'][$k];

				if ($peso_este + $conf->g('raiz', 'peso_actual') > $conf->g('raiz','peso_maximo')) {
					$estado_accion .= $v.': '.$conf->t('estado.subir_arq', 6).'<br />';
					continue;
				}
			}

			$ancho_banda = $accions->log_ancho_banda($files['size'][$k]);

			if (!$ancho_banda) {
				$estado_accion .= $v.': '.$conf->t('estado.subir_arq', 7).'<br />';
				continue;
			}

			$imaxe = '';
			$v = $niveles->nome_correcto($v);

			if ($sobreescribir[$i] == 1 && file_exists($upload_dir.'/'.$v)) {
				$imaxe = @GetImageSize($upload_dir.'/'.$v);

				if (is_array($imaxe) && file_exists($imaxes->nome_pequena($upload_dir.'/'.$v))) {
					@unlink($imaxes->nome_pequena($upload_dir.'/'.$v));
				}

				@unlink($upload_dir.'/'.$v);
			}

			$accions->upload($v, $files['tmp_name'][$k], $upload_dir);
			$estado = $accions->estado_num('subir_arq');

			if ($accions->estado('subir_arq')) {
				if ($conf->g('inc','estado')) {
					$inc->multiple($i);
					$arq_inc = $inc->crea_inc($upload_dir.'/'.$v,'arq');
				}

				if ($conf->g('inc','indexar')) {
					$indexador->alta_modificacion("$dir/", $v, $arq_inc);
				}

				if ($conf->g('imaxes','pequena') && $opc_imaxes[$i] != '') {
					if (!is_array($imaxe)) {
						$imaxe = @GetImageSize($upload_dir.'/'.$v);
					}

					if (in_array($imaxe[2],$conf->g('imaxes','validas'))) {
						if ($opc_imaxes[$i] == 'reducir') {
							$imaxes->reducir($upload_dir.'/'.$v);
						} elseif ($opc_imaxes[$i] == 'recortar') {
							$recortar[] = $v;
						}
					}
				}

				if ($conf->g('raiz','peso_maximo') > 0) {
					$peso_este += $conf->g('raiz', 'peso_actual');

					if ($conf->g('inc','estado')) {
						$peso_este += PFN_espacio_disco($arq_inc, true);
					}

					if ($conf->g('imaxes','pequena') && $opc_imaxes[$i] != 'reducir') {
						$peso_este += PFN_espacio_disco($imaxes->nome_pequena($upload_dir.'/'.$v), true);
					}

					$conf->p($peso_este, 'raiz', 'peso_actual');
					$usuarios->accion('peso', $peso_este, $conf->g('raiz','id'));
				}
			}

			$estado_accion .= $v.': '.$conf->t('estado.subir_arq',intval($estado)).'<br />';
		}

		if ($i++ && ($i > $conf->g('inc','limite'))) {
			break;
		}
	}

	$tempo->rexistra('preplantillas');

	if (is_array($recortar) && count($recortar)) {
		$cal = $arquivo = $recortar[0];
		$vars->get('cal', $cal);
		unset($recortar[0]);

		if (count($recortar)) {
			foreach ($recortar as $k => $v) {
				$vars->get("mais_$k", $v);
			}
		}

		include ($paths['accions'].'redimensionar.inc.php');
	} else {
		include ($paths['plantillas'].'cab.inc.php');

		$tempo->rexistra('precodigo');

		include ($paths['web'].'opcions.inc.php');
		include ($paths['web'].'navega.inc.php');

		$tempo->rexistra('postcodigo');

		include ($paths['plantillas'].'pe.inc.php');
	}
} else {
	include ($paths['plantillas'].'cab.inc.php');

	include ($paths['web'].'opcions.inc.php');
	include ($paths['plantillas'].'posicion.inc.php');
	include ($paths['plantillas'].'subir_arq.inc.php');

	$tempo->rexistra('postcodigo');

	include ($paths['plantillas'].'pe.inc.php');
}
?>

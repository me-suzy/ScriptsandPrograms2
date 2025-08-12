<?php
/****************************************************************************
* data/accions/multiple_eliminar.inc.php
*
* Realiza la visualización o acción de eliminar multiples ficheros
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

$multiple_escollidos = (array)$vars->post('multiple_escollidos');
$estado_accion = '';
$cnt_erros = 0;
$adv = '';

include ($paths['plantillas'].'cab.inc.php');
include ($paths['web'].'opcions.inc.php');

$tempo->rexistra('precodigo');

if ($conf->g('columnas','multiple')
&& ($vars->post('executa') || !$conf->g('confirmar_eliminar'))
&& (count($multiple_escollidos) > 0)) {
	if (!empty($dir)) {
		if ($conf->g('inc','estado')) {
			include_once ($paths['include'].'class_inc.php');
			$inc = new PFN_INC($conf);
		}

		if ($conf->g('inc','indexar')) {
			include_once ($paths['include'].'class_indexador.php');
			$indexador = new PFN_Indexador($conf);
		}

		foreach ($multiple_escollidos as $v) {
			$erro = false;
			$cal = $v = $accions->nome_correcto($v);
			$arquivo = $conf->g('raiz','path').$accions->path_correcto($dir.'/')
				.'/'.$cal;
			$e_dir = $accions->e_dir($arquivo);

    	if (empty($v) || ($v == '.') || ($v == './') || !file_exists($arquivo)) {
				$erro = true;
				$estado = 4;
			}

			if (!$erro) {
				if ($conf->g('raiz','peso_maximo') > 0) {
					if ($e_dir) {
						$peso_este = $accions->get_tamano("$arquivo/");
					} else {
						$peso_este = PFN_espacio_disco($arquivo, true);
					}
				}

				$accions->eliminar($arquivo, $dir);
				$estado = $accions->estado_num('multiple_eliminar');
			}

			if (!$erro && $accions->estado('multiple_eliminar')) {
				if (!$e_dir && $conf->g('inc','estado') && is_file($inc->nome_inc($arquivo))) {
					$peso_este += PFN_espacio_disco($inc->nome_inc($arquivo), true);
					$inc->eliminar($arquivo);
				}

				if ($conf->g('inc','indexar')) {
					$indexador->eliminar("$dir/", $cal.($e_dir?'/':''));
				}

				if (!$e_dir && $conf->g('imaxes','pequena') && is_file($imaxes->nome_pequena($arquivo))) {
					$peso_este += PFN_espacio_disco($imaxes->nome_pequena($arquivo), true);
					$imaxes->eliminar($arquivo);
				}
			} else {
				$estado_accion .= $conf->t('estado.multiple_eliminar',intval($estado)).' '.$cal.'<br />';
				$cnt_erros++;

				if ($e_dir && $estado != 4) {
					clearstatcache();
					$peso_este = $accions->get_tamano("$arquivo/");
				}
			}

			if (($estado !== 4) && ($e_dir || !$erro) && ($conf->g('raiz','peso_maximo') > 0)) {
				$peso_este = $conf->g('raiz', 'peso_actual') - $peso_este;

				$peso_este = ($peso_este < 0)?0:$peso_este;

				$conf->p($peso_este, 'raiz', 'peso_actual');
				$usuarios->accion('peso', $peso_este, $conf->g('raiz','id'));
			}
		}
	}

	if ($cnt_erros == 0) {
		$estado_accion = $conf->t('estado.multiple_eliminar', 1);
	} elseif ($cnt_erros != count($multiple_escollidos)) {
		$estado_accion .= $conf->t('estado.multiple_eliminar', 3);
	}

	include ($paths['web'].'navega.inc.php');
} elseif ($conf->g('columnas','multiple') && count($multiple_escollidos) > 0) {
	foreach ($multiple_escollidos as $k => $v) {
		$v = $accions->nome_correcto($v);
		$arquivo = $conf->g('raiz','path').$accions->path_correcto($dir.'/').'/'.$v;

    if (empty($v) || ($v == '.') || ($v == './') || !file_exists($arquivo)) {
			$adv = $conf->t('estado.multiple_eliminar', 7).' '.$v.'<br />';
			unset($multiple_escollidos[$k]);
		} else {
			$multiple_escollidos[$k] = $v;
		}
	}

	if (count($multiple_escollidos) > 0) {
		include ($paths['plantillas'].'posicion.inc.php');
		include ($paths['plantillas'].'multiple_eliminar.inc.php');
	} else {
		include ($paths['web'].'navega.inc.php');
	}
} else {
	include ($paths['web'].'navega.inc.php');
}

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

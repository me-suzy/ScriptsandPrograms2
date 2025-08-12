<?php
/****************************************************************************
* data/accions/copiar.inc.php
*
* Realiza la visualización o acción de copiar (ficheros o directorios)
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

$erro = false;

include ($paths['plantillas'].'cab.inc.php');
include ($paths['web'].'opcions.inc.php');

$tempo->rexistra('precodigo');

if ($vars->post('executa')) {
	if (!empty($cal) && !empty($dir)) {
		$orixinal = $arquivo;
		$destino = $conf->g('raiz','path').$accions->path_correcto($vars->post('escollido').'/')
			.'/'.$cal;

		if (strstr($destino, $orixinal)) {
			$estado_accion = $conf->t('estado.copiar_dir',7);
			$erro = true;
		}

		if (!$erro && $tipo == 'dir') {
			if ($conf->g('raiz','peso_maximo') > 0) {
				if ($conf->g('raiz','peso_actual') >= $conf->g('raiz','peso_maximo')) {
					$estado_accion .= $conf->t('estado.copiar_dir', 8).'<br />';
					$erro = true;
				} else {
					$peso_este = $accions->get_tamano("$orixinal/", true);
	
					if ($peso_este + $conf->g('raiz', 'peso_actual') > $conf->g('raiz','peso_maximo')) {
						$estado_accion .= $conf->t('estado.copiar_dir', 8).'<br />';
						$erro = true;
					}
				}
			}

			if (!$erro) {
				$accions->copiar($orixinal, $destino);
				$estado = $accions->estado_num('copiar_dir');
				$estado_accion = $conf->t('estado.copiar_dir',intval($estado));
			}

			if (!$erro && $accions->estado('copiar_dir')) {
				if ($conf->g('raiz','peso_maximo') > 0) {
					$peso_este += $conf->g('raiz', 'peso_actual');

					$conf->p($peso_este, 'raiz', 'peso_actual');
					$usuarios->accion('peso', $peso_este, $conf->g('raiz','id'));
				}

				if ($conf->g('inc','indexar')) {
					include_once ($paths['include'].'class_indexador.php');
					$indexador = new PFN_Indexador($conf);

					$i_destino = $accions->path_correcto($vars->post('escollido').'/');
					$indexador->copiar("$dir/", "$i_destino/", "$cal/");
				}
			}
		} elseif (!$erro) {
			if ($conf->g('raiz','peso_maximo') > 0) {
				$peso_este = PFN_espacio_disco($orixinal, true);

				if ($peso_este + $conf->g('raiz', 'peso_actual') > $conf->g('raiz','peso_maximo')) {
					$estado_accion .= $conf->t('estado.copiar_arq', 6).'<br />';
					$erro = true;
				}
			}

			if (!$erro) {
				$accions->copiar($orixinal, $destino);
				$estado = $accions->estado_num('copiar_arq');
				$estado_accion = $conf->t('estado.copiar_arq',intval($estado));
			}

			if ($accions->estado('copiar_arq')) {
				if ($conf->g('inc','estado')) {
					include_once ($paths['include'].'class_inc.php');

					$inc = new PFN_INC($conf);
					$inc->copiar($orixinal, $destino);
				}

				if ($conf->g('inc','indexar')) {
					include_once ($paths['include'].'class_indexador.php');

					$i_destino = $accions->path_correcto($vars->post('escollido').'/');

					$indexador = new PFN_Indexador($conf);
					$indexador->copiar("$dir/", "$i_destino/", $cal);
				}

				if ($conf->g('imaxes','pequena')) {
					$imaxes->copiar($orixinal, $destino);
				}

				if ($conf->g('raiz','peso_maximo') > 0) {
					$peso_este += $conf->g('raiz', 'peso_actual');

					if ($conf->g('inc','estado')) {
						$peso_este += PFN_espacio_disco($inc->nome_inc($orixinal), true);
					}

					if ($conf->g('imaxes','pequena')) {
						$peso_este += PFN_espacio_disco($imaxes->nome_pequena($orixinal), true);
					}

					$conf->p($peso_este, 'raiz', 'peso_actual');
					$usuarios->accion('peso', $peso_este, $conf->g('raiz','id'));
				}
			}
		}
	}

	include ($paths['web'].'navega.inc.php');
} else {
	if (file_exists($arquivo)) {
		include_once ($paths['include'].'class_arbore.php');
		$arbore = new PFN_Arbore($conf);

		$arbore->imaxes($imaxes);
		$arbore->pon_radio('escollido');
		$arbore->pon_enlaces(false);

		if ($tipo == 'dir') {
			$contido = $accions->get_contido($arquivo);
			$arbore->pon_copia($arquivo);
	
			if (count($contido['dir']['nome']) || count($contido['arq']['nome'])) {
				$adv = $conf->t('estado.copiar_dir',2);
			} else {
				$adv = $conf->t('estado.copiar_dir',3);
			}
		} else {
			$adv = $conf->t('estado.copiar_arq',2);
		}

		$arbore->carga_arbore($conf->g('raiz','path'), "./", false);

		include ($paths['plantillas'].'posicion.inc.php');
		include ($paths['plantillas'].'info_cab.inc.php');
		include ($paths['plantillas'].'copiar.inc.php');
	} else {
		include ($paths['web'].'navega.inc.php');
	}
}

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

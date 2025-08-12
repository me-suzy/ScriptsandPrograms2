<?php
/****************************************************************************
* data/accions/eliminar.inc.php
*
* Realiza la visualización o acción de eliminar un fichero o directorio
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

if ($vars->post('executa') || !$conf->g('confirmar_eliminar')) {
	if (!empty($cal) && !empty($dir)) {
		if ($tipo == 'dir') {
			if ($conf->g('raiz','peso_maximo') > 0) {
				$peso_este = $accions->get_tamano("$arquivo/", true);
			}

			$accions->eliminar($arquivo);
			$estado = $accions->estado_num('eliminar_dir');
			$estado_accion = $conf->t('estado.eliminar_dir',intval($estado));

			if ($accions->estado('eliminar_dir')) {
				if ($conf->g('inc','indexar')) {
					include_once ($paths['include'].'class_indexador.php');

					$indexador = new PFN_Indexador($conf);
					$indexador->eliminar("$dir/", "$cal/");
				}
			} else {
				clearstatcache();
				$peso_este = $accions->get_tamano("$arquivo/", true);
			}

			if ($conf->g('raiz','peso_maximo') > 0) {
				$peso_este = $conf->g('raiz', 'peso_actual') - $peso_este;

				$peso_este = ($peso_este < 0)?0:$peso_este;
				$conf->p($peso_este, 'raiz', 'peso_actual');
				$usuarios->accion('peso', $peso_este, $conf->g('raiz','id'));
			}
		} else {
			if ($conf->g('raiz','peso_maximo') > 0) {
				$peso_este = PFN_espacio_disco($arquivo, true);
			}

			$accions->eliminar($arquivo);
			$estado = $accions->estado_num('eliminar_arq');
			$estado_accion = $conf->t('estado.eliminar_arq',intval($estado));

			if ($accions->estado('eliminar_arq')) {
				if ($conf->g('inc','estado')) {
					include_once ($paths['include'].'class_inc.php');
					$inc = new PFN_INC($conf);

					if (is_file($inc->nome_inc($arquivo))) {
						$peso_este += PFN_espacio_disco($inc->nome_inc($arquivo), true);
						$inc->eliminar($arquivo);
					}
				}

				if ($conf->g('inc','indexar')) {
					include_once ($paths['include'].'class_indexador.php');

					$indexador = new PFN_Indexador($conf);
					$indexador->eliminar("$dir/", $cal);
				}

				if ($conf->g('imaxes','pequena')) {
					if (is_file($imaxes->nome_pequena($arquivo))) {
						$peso_este += PFN_espacio_disco($imaxes->nome_pequena($arquivo), true);
						$imaxes->eliminar($arquivo);
					}
				}

				if ($conf->g('raiz','peso_maximo') > 0) {
					$peso_este = $conf->g('raiz', 'peso_actual') - $peso_este;

					$peso_este = ($peso_este < 0)?0:$peso_este;
					$conf->p($peso_este, 'raiz', 'peso_actual');
					$usuarios->accion('peso', $peso_este, $conf->g('raiz','id'));
				}
			}
		}
	}

	include ($paths['web'].'navega.inc.php');
} else {
	if (file_exists($arquivo)) {
		if ($tipo == 'dir') {
			$contido = $accions->get_contido($arquivo);
	
			if (count($contido['dir']['nome']) || count($contido['arq']['nome'])) {
				include_once ($paths['include'].'class_arbore.php');
				$arbore = new PFN_Arbore($conf);

				$arbore->imaxes($imaxes);
				$arbore->carga_arbore("$arquivo/", "$dir/$cal/", true);

				$adv = $conf->t('estado.eliminar_dir',3);
			} else {
				$adv = $conf->t('estado.eliminar_dir',2);
			}
	
			include ($paths['plantillas'].'posicion.inc.php');
			include ($paths['plantillas'].'info_cab.inc.php');
			include ($paths['plantillas'].'eliminar_dir.inc.php');
		} else {
			include ($paths['plantillas'].'posicion.inc.php');
			include ($paths['plantillas'].'info_cab.inc.php');
			include ($paths['plantillas'].'eliminar_arq.inc.php');
		}
	} else {
		include ($paths['web'].'navega.inc.php');
	}
}

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

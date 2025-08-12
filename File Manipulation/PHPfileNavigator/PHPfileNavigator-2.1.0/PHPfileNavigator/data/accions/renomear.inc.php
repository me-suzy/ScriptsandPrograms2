<?php
/****************************************************************************
* data/accions/renomar.inc.php
*
* Realiza la visualización o acción de renombrar un fichero o directorio
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

$tempo->rexistra('precodigo');

include ($paths['plantillas'].'cab.inc.php');
include ($paths['web'].'opcions.inc.php');

if ($vars->post('executa')) {
	if ($vars->post('novo_nome') != '' && !empty($dir) && !empty($cal)) {
		$antes = $conf->g('raiz','path').$accions->path_correcto($dir.'/').'/'.$cal;
		$agora = $conf->g('raiz','path').$accions->path_correcto($dir.'/')
			.'/'.$accions->nome_correcto($vars->post('novo_nome'));

		if (!eregi('\.[a-z0-9]+$', $agora) && is_file($antes)) {
			$partes = explode('.', $antes);
			$agora .= '.'.end($partes);
		}

		$accions->renomear($antes, $agora);
		$estado = $accions->estado_num('renomear');
		$estado_accion = $conf->t('estado.renomear',intval($estado));

		if ($accions->estado('renomear')) {
			if ($conf->g('inc','estado')) {
				include_once ($paths['include'].'class_inc.php');

				$inc = new PFN_INC($conf);
				$inc->renomear($antes, $agora);
			}

			if ($conf->g('imaxes','pequena')) {
				$imaxes->renomear($antes, $agora);
			}

			if ($conf->g('inc','indexar')) {
				include_once ($paths['include'].'class_indexador.php');

				$i_antes = $accions->nome_correcto(end(explode('/',$antes)));
				$i_agora = $accions->nome_correcto(end(explode('/',$agora)));

				$indexador = new PFN_Indexador($conf);

				if ($accions->e_dir($agora)) {
					$indexador->renomear("$dir/", "$i_antes/", "$i_agora/");
				} else {
					$indexador->renomear("$dir/", $i_antes, $i_agora);
				}
			}
		}
	}

	include ($paths['web'].'navega.inc.php');
} else {
	if (file_exists($arquivo)) {
		include ($paths['plantillas'].'posicion.inc.php');
		include ($paths['plantillas'].'info_cab.inc.php');
		include ($paths['plantillas'].'renomear.inc.php');
	} else {
		include ($paths['web'].'navega.inc.php');
	}
}

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

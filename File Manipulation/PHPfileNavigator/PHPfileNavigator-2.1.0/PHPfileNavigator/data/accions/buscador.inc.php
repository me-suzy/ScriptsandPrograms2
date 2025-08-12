<?php
/****************************************************************************
* data/accions/buscador.inc.php
*
* Realiza la visualización da accion de buscar
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

$tempo->rexistra('preplantillas');

include ($paths['plantillas'].'cab.inc.php');
include ($paths['web'].'opcions.inc.php');

$tempo->rexistra('precodigo');

include ($paths['plantillas'].'posicion.inc.php');

if ($vars->post('executa')
	&& $vars->post('palabra_buscar') != ''
	&& is_array($vars->post('campos_buscar'))
) {
	include_once ($paths['include'].'class_indexador.php');
	$indexador = new PFN_Indexador($conf);

	$cada = array();
	$resultados = $indexador->buscar(
		"$dir/",
		$vars->post('palabra_buscar'),
		$vars->post('campos_buscar'),
		$vars->post('donde_buscar')
	);

	if (count($resultados)) {
		foreach ($resultados as $k => $v) {
			$cada = $conf->g('raiz','path').$accions->path_correcto($v['directorio'])
				.'/'.$v['arquivo'];
		}

		include_once ($paths['include'].'class_inc.php');

		$inc = new PFN_INC($conf);
		$arquivos->niveles($niveles);
	}

	include ($paths['plantillas'].'buscador_formulario.inc.php');
	include ($paths['plantillas'].'buscador_resultados.inc.php');
} else {
	include ($paths['plantillas'].'buscador_formulario.inc.php');
}

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

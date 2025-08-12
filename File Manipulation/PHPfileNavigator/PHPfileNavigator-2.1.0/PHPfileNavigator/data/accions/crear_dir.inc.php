<?php
/****************************************************************************
* data/accions/crear_dir.inc.php
*
* Realiza la visualización o acción de crear un directorio
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

if ($vars->post('executa') && $vars->post('nome_directorio') != '') {
	$donde = $conf->g('raiz','path').$accions->path_correcto($dir.'/');
	$cal = $accions->nome_correcto($vars->post('nome_directorio'));
	
	$accions->crear_dir($donde, $cal);
	$estado = $accions->estado_num('crear_dir');
	$estado_accion = $conf->t('estado.crear_dir',intval($estado));
	
	if ($accions->estado('crear_dir')) {
		if ($conf->g('inc','estado')) {
			include_once ($paths['include'].'class_inc.php');

			$inc = new PFN_INC($conf);

			$inc->arquivos($arquivos);
			$arq_inc = $inc->crea_inc($donde.'/'.$cal.'/','dir');
		}

		if ($conf->g('inc','indexar')) {
			include_once ($paths['include'].'class_indexador.php');

			$indexador = new PFN_Indexador($conf);
			$indexador->alta_modificacion("$dir/", "$cal/", $arq_inc);
		}
	}

	include ($paths['web'].'navega.inc.php');
} else {
	include_once ($paths['include'].'class_inc.php');

	$inc = new PFN_INC($conf);

	include ($paths['plantillas'].'posicion.inc.php');
	include ($paths['plantillas'].'crear_dir.inc.php');
}

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

<?php
/****************************************************************************
* data/accions/permisos.inc.php
*
* Realiza la visualización o acción de cambiar los permisos a un fichero
* o directorio
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

if ($vars->post('executa')) {
	if (!empty($cal) && !empty($dir)) {
		$perimisos = 0;

		foreach (array('p400','p200','p100','p040','p020','p010','p004','p002','p001') as $v) {
			$permisos += $vars->post($v);
		}

		$accions->permisos($arquivo, $permisos);
		$estado = $accions->estado_num('permisos');
		$estado_accion = $conf->t('estado.permisos',intval($estado));
	}

	include ($paths['web'].'navega.inc.php');
} else {
	$actuales = fileperms($arquivo);

	include ($paths['plantillas'].'posicion.inc.php');
	include ($paths['plantillas'].'info_cab.inc.php');
	include ($paths['plantillas'].'permisos.inc.php');
}

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

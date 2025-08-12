<?php
/****************************************************************************
* nagega.inc.php
*
* Carga lo necesario para la visualización de la navegación principal
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

defined('OK') or die();

$tempo->rexistra('i:navega');

$lista = $vars->get('lista');
$orde = $vars->get('orde');
$pos = $vars->get('pos');

$niveles->posicion($lista);
$cada = $niveles->get_contido($conf->g('raiz','path').$dir,$orde,$pos);

$cnt_dir = $niveles->cnt('dir');
$cnt_arq = $niveles->cnt('arq');
$cnt_peso = PFN_peso($niveles->cnt('peso'));

if ($conf->g('inc','estado')) {
	include_once ($paths['include'].'class_inc.php');
	$inc = new PFN_INC($conf);

	$inc->carga_datos($conf->g('raiz','path').$dir.'/');
}

$tempo->rexistra('f:navega');

include ($paths['plantillas'].'posicion.inc.php');

$tempo->rexistra('posicion');

include ($paths['plantillas'].'navega.inc.php');
?>

<?php
/****************************************************************************
* index.php
*
* Carga lo necesario para la visualización de la pantalla de login
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

$sPFN = '';

session_start();
session_register('sPFN');
session_unset('sPFN');
session_write_close();

include ('paths.php');
include_once ($paths['include'].'class_tempo.php');
include_once ($paths['include'].'class_conf.php');
include_once ($paths['include'].'class_vars.php');

if (is_dir($paths['web'].'instalar/')) {
	include ($paths['web'].'instalar/index.php');
	exit;
}

$conf->textos('web');

$tempo->rexistra('preplantillas');

include ($paths['plantillas'].'cab.inc.php');
include ($paths['plantillas'].'login.inc.php');
include ($paths['plantillas'].'pe.inc.php');
?>

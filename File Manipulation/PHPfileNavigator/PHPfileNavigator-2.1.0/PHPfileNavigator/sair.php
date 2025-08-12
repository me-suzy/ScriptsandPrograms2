<?php
/****************************************************************************
* sair.php
*
* Cierra sesión y redirige hacia el destino especificado
*

PHPfileNavigator versión 1.6.7

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

include ('paths.php');
include_once ($paths['include'].'class_tempo.php');
include_once ($paths['include'].'borra_cache.php');
include_once ($paths['include'].'class_conf.php');
include_once ($paths['include'].'class_vars.php');
include_once ($paths['include'].'class_sesion.php');

$sesion->encriptar(true,false);

include_once ($paths['include'].'mysql.php');
include_once ($paths['include'].'clases.php');
include_once ($paths['include'].'class_usuarios.php');
include_once ($paths['include'].'usuarios.php');

$usuarios->garda_rexistro('sair',0);
$conf->carga();

$url = $conf->g('saida');

$sPFN = '';

session_register('sPFN');
session_unregister('sPFN');

if ($conf->g('manter_sesion')) {
	$url .= (strstr($url, '?')?'&':'?').session_name().'='.session_id();
} else {
	session_unset();
	session_destroy();
}

session_write_close();

Header('Location: '.$url);
exit;
?>

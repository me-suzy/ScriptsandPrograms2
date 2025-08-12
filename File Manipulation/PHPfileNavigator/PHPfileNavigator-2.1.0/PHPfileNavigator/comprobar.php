<?php
/****************************************************************************
* comprobar.php
*
* Control de login que redirije para el menú o vuelve al index
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

include ('paths.php');
include_once ($paths['include'].'borra_cache.php');
include_once ($paths['include'].'class_conf.php');
include_once ($paths['include'].'class_vars.php');
include_once ($paths['include'].'mysql.php');
include_once ($paths['include'].'clases.php');
include_once ($paths['include'].'class_usuarios.php');
include_once ($paths['include'].'class_sesion.php');

$sesion->encriptar(false,true);

$conf->inicial('login');
$usuarios->vars($vars);

$sPFN = array();
$usuario = $usuarios->login('usuario');
$contrasinal = $usuarios->login('contrasinal');

if ($usuarios->init('login',$usuario,$contrasinal)) {
	$sPFN['usuario'] = array(
		'id' => $usuarios->get('id'),
		'usuario' => $usuario,
		'contrasinal' => $contrasinal,
		'admin' => $usuarios->get('admin'),
		'id_grupo' => $usuarios->get('id_grupo'),
		'conf_defecto' => $usuarios->get('conf_defecto'),
		'mantemento' => $usuarios->get('mantemento'),
		'descargas_maximo' => $usuarios->get('descargas_maximo'),
	);

	if (!$usuarios->sesion_iniciada) {
		session_start();
	}

	session_register('sPFN');
	$vars->session('sPFN', $sPFN);

	session_write_close();

	$usuarios->garda_rexistro('login',1);

	Header('Location: menu.php?'.session_name().'='.session_id()) && exit;
} else {
	$usuarios->garda_rexistro('login',0);

	$url = $vars->server('HTTP_REFERER');

	if (empty($url)) {
		$url = 'index.php?erro=1';
	} elseif (!strstr($url, 'erro=1')) {
		$url .= (strstr($url, '?')?'&':'?').'erro=1';
	}
	
	Header('Location: '.$url) && exit;
}
?>

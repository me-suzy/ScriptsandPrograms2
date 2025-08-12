<?php
/****************************************************************************
* data/include/usuarios.php
*
* Controla el acceso en cada petición
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

function envia_erro ($erro) {
	global $vars, $conf, $relativo;

	$erros = array(
		1 => 'Intento de colar datos de usuario',
		2 => 'No existen los datos de usuario',
		3 => 'Datos de usuario incorrectos',
		4 => "Intento de acceso en la administración de usuario no válido",
	);

	$t = 'Alerta de seguridad en '.$vars->server('SERVER_NAME');
	$m = 'Alerta por intento de acceso al servidor '.$vars->server('SERVER_NAME')
		."\nEn la URL ".$vars->server('PHP_SELF')
		."\nA las ".date('Y-m-d H:i')
		."\nDesde la IP ".$vars->server('REMOTE_ADDR')
		."\n\n".$erros[$erro];

	ob_start();
	echo "\n\nDatos de GET:\n";
	var_dump($vars->get(''));

	echo "\n\nDatos de POST:\n";
	var_dump($vars->post(''));

	echo "\n\nDatos de SESSION:\n";
	var_dump($vars->session(''));

	$m .= ob_get_contents();
	ob_end_clean();

	$conf->inicial('default');

	@session_start();

	$sPFN = '';
	session_register('sPFN');
	session_unregister('sPFN');
	
	$url = $conf->g('saida');
	$url = 'index.php'?"$relativo$url":$url;
	$url .= (strstr($url, '?')?'&':'?');

	if ($conf->g("manter_session")) {
		$url .= session_name().'='.session_id().'&';
	} else {
		@session_unset();
		@session_destroy();
	}

	session_write_close();

	@mail($conf->g('email'),$t,$m);
	Header('Location: '.$url.'erro=1') && exit;
}

unset($erro);

$sPFN = trim($vars->get('sPFN'));
$sPFN .= trim($vars->post('sPFN'));

if (!empty($sPFN)) {
	$usuarios->garda_rexistro('colar', 0);
	envia_erro(1);
}

session_start();

$sPFN = $vars->session('sPFN');

if (!is_array($sPFN) || !count($sPFN)) {
	$usuarios->garda_rexistro('vacios', 0);
	envia_erro(2);
}

$id = $vars->get('id');

if (empty($id)) {
	unset($id);
}

if (empty($id)
&& empty($sPFN['raiz']['id'])
&& basename($vars->server('PHP_SELF')) != 'menu.php') {
	session_write_close();

	Header('Location: '.$relativo.'menu.php?'.session_name().'='.session_id());
	exit;
} elseif (!empty($id)) {
	$sPFN['raiz']['id'] = $id;

	session_register('sPFN');
	$vars->session('sPFN', $sPFN);
}

if ($usuarios->init('session')) {
	$usuarios->actualiza_acceso();
} else {
	$usuarios->garda_rexistro('session', 0);
	envia_erro(3);
}

$conf->p($sPFN['raiz']['id'],'raiz','id');
$conf->p($sPFN['raiz']['unica'],'raiz','unica');
$conf->p($usuarios->get('nome'),'raiz','nome');
$conf->p($usuarios->get('path'),'raiz','path');
$conf->p($usuarios->get('web'),'raiz','web');
$conf->p($usuarios->get('host'),'raiz','host');
$conf->p($usuarios->get('conf'),'raiz','conf');
$conf->p($usuarios->get('mantemento'),'raiz','mantemento');
$conf->p($usuarios->get('peso_maximo'),'raiz','peso_maximo');
$conf->p($usuarios->get('peso_actual'),'raiz','peso_actual');

foreach ($sPFN['usuario'] as $k => $v) {
	$conf->p($v, 'usuario', $k);
}
?>

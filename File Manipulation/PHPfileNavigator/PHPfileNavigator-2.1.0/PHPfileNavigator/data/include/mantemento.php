<?php
/****************************************************************************
* data/include/mantemento.php
*
* Realiza una comprobación periódica del estado del las raíces del servidor
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

defined('OK') or die();

$data_mantemento = date('Y-m-d', mktime(0,0,0,date('m'),date('d')-5,date('Y')));

if ($conf->g('raiz','peso_maximo') && ($conf->g('raiz','mantemento') < $data_mantemento)) {
	$actual = $niveles->get_tamano($conf->g('raiz','path'));

	$usuarios->accion('peso', $actual, $conf->g('raiz','id'));
	$usuarios->accion('mantemento_raiz', $conf->g('raiz','id'));

	$conf->p($actual, 'raiz', 'peso_actual');
}

if ($conf->g('usuario','mantemento') < $data_mantemento) {
	$data_mantemento = date('Ym', mktime(0,0,0,date('m')-$conf->g('logs_usuarios'),1,date('Y')));
	$lista_dir_usuario = $niveles->carga_contido($conf->g('info_usuario'), true, true);

	foreach ($lista_dir_usuario['nome'] as $v) {
		$atopa_log = array();
		ereg('^descargas\.([0-9]{6}).php', $v, $atopa_log);

		if (!empty($atopa_log[1]) && ($atopa_log[1] < $data_mantemento)) {
			@unlink($conf->g('info_usuario').'/'.$v);
		}
	}

	$usuarios->accion('mantemento_usuario', $conf->g('usuario','id'));

	$sPFN['usuario']['mantemento'] = date('Y-m-d');

	session_register($sPFN);
	$vars->session('sPFN',$sPFN);
}
?>

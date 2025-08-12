<?php
/****************************************************************************
* xestion/configuracions/duplicar.php
*
* Duplica un fichero de configuración
*

PHPfileNavigator versión 2.0.1

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

$relativo = '../../';

include ($relativo.'paths.php');
include_once ($paths['include'].'basicweb.php');
include_once ($paths['include'].'Xusuarios.php');

PFN_quita_url_SERVER(array('id_conf','novo'));

session_write_close();

$erros = array();
$ok = 0;

$id_conf = $vars->get('id_conf');

$existe = $usuarios->init('configuracion', $id_conf);
$conf_orix = $usuarios->get('conf');
$vale = $usuarios->get('vale');
$nome_orix = $niveles->path_correcto($paths['conf'].$conf_orix.'.inc.php');

$conf->p(false, 'logs', 'accions');
$conf->p(true, 'nome_riguroso');

$conf_copia = str_replace('.inc.php', '', $niveles->nome_correcto($vars->get('novo')));
$conf_copia= trim(str_replace('.php', '', $conf_copia));
$nome_copia = $niveles->path_correcto($paths['conf'].$conf_copia.'.inc.php');

if (!$existe || !is_file($nome_orix)) {
	$erros[] = 18;
} elseif (empty($conf_copia)) {
	$erros[] = 20;
} elseif (is_file($nome_copia)) {
	$erros[] = 21;
} elseif ($usuarios->init('configuracion_nome', $conf_copia)) {
	$erros[] = 22;
} else {
	if ($novo_id = $usuarios->accion('conf_crear', $conf_copia)) {
		include_once ($paths['include'].'class_accions.php');

		$accions = new PFN_Accions($conf);
		$estado = $accions->copiar($nome_orix, $nome_copia);

		if ($accions->estado('copiar_arq')) {
			$id_conf = $novo_id;
			$vars->get('id_conf', $id_conf);
		} else {
			$usuarios->accion('conf_eliminar', $novo_id);
			$erros[] = 24;
		}
	} else {
		$erros[] = 23;
	}
}

$ok = count($erros)?false:3;

Header('Location: index.php?'.PFN_cambia_url(array('id_conf','ok','erros'), array($id_conf,$ok,implode(',', $erros)), false, true));
exit;
?>

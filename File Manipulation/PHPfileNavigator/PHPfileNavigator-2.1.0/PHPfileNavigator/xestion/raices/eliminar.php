<?php
/****************************************************************************
* xestion/raices/eliminar.php
*
* Elimina una raíz y su relación con los usuarios
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

$relativo = '../../';

include ($relativo.'paths.php');
include_once ($paths['include'].'basicweb.php');
include_once ($paths['include'].'Xusuarios.php');

PFN_quita_url_SERVER(array('id_raiz','borrar_inc'));

session_write_close();

$erros = array();
$id_raiz = intval($vars->get('id_raiz'));
$borrar_inc = intval($vars->get('borrar_inc'));

if (empty($id_raiz) || $sPFN['raiz']['id'] == $id_grupo) {
	$erros[] = 5;
} else {
	$usuarios->init('raiz', $id_raiz);
	$path_raiz = $usuarios->get('path');

	$query = 'DELETE FROM '.$usuarios->tabla('raices')
		.' WHERE id = "'.$id_raiz.'";';
	
	if ($usuarios->actualizar($query) == -1) {
		$erros[] = 6;
	} else {
		$query = 'DELETE FROM '.$usuarios->tabla('r_u')
			.' WHERE id_raiz = "'.$id_raiz.'";';
		$usuarios->actualizar($query);

		$query = 'DELETE FROM '.$usuarios->tabla('r_g_c')
			.' WHERE id_raiz = "'.$id_raiz.'";';
		$usuarios->actualizar($query);

		include_once ($paths['include'].'class_indexador.php');

		$indexador = new PFN_Indexador($conf);
		$indexador->eliminar_raiz($id_raiz);

		$info_raiz = $niveles->path_correcto($paths['info'].'raiz'.$id_raiz);

		if (is_dir($info_raiz)) {
			include_once ($paths['include'].'class_accions.php');

			$conf->p(false, 'logs', 'accions');

			$accions = new PFN_Accions($conf);
			$accions->eliminar($info_raiz);
		}

		if (is_file($path_raiz.'.htaccess')) {
			@unlink($path_raiz.'.htaccess');
		}

		if ($borrar_inc === 1) {
			include_once ($paths['include'].'class_inc.php');

			$inc = new PFN_INC($conf);
			$inc->vacia_path($path_raiz);
		}
	}
}

$ok = count($erros)?false:2;

Header('Location: ../index.php?'.PFN_cambia_url(array('id_raiz','opc','erros','ok'), array('',1,implode(',', $erros),$ok), false, true));
exit;
?>

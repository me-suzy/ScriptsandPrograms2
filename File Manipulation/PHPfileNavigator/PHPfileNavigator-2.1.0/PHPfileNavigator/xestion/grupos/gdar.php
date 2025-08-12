<?php
/****************************************************************************
* xestion/grupos/gdar.php
*
* Guarda las modificaciónes de datos de un grupo
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

session_write_close();

$id_grupo = intval($vars->post('id_grupo'));
$nome = addslashes(trim($vars->post('nome')));
$id_conf = intval($vars->post('id_conf'));
$estado = intval($vars->post('estado'));

$query = '';
$erros = array();
$ok = 0;

if (empty($nome) || empty($id_conf)) {
	$erros[] = 1;
} elseif (($id_grupo == $sPFN['usuario']['id_grupo']) && ($estado == 0)) {
	$erros[] = 14;
} elseif ($usuarios->init('existe_grupo', $nome, $id_grupo)) {
	$erros[] = 15;
} else {
	if (empty($id_grupo)) {
		$query = 'INSERT INTO '.$usuarios->tabla('grupos')
			.' SET nome="'.$nome.'"'
			.', id_conf="'.$id_conf.'"'
			.', estado="'.$estado.'";';
	} else {
		$query = 'UPDATE '.$usuarios->tabla('grupos')
			.' SET nome="'.$nome.'"'
			.', id_conf="'.$id_conf.'"'
			.', estado="'.$estado.'"'
			.' WHERE id="'.$id_grupo.'"'
			.' LIMIT 1;';
	}

	if ($usuarios->actualizar($query) == -1) {
		$erros[] = 2;
	}
}

if (!count($erros)) {
	if (empty($id_grupo)) {
		$id_grupo = $usuarios->id_ultimo();

		$usuarios->init('raices');

		$query = 'INSERT INTO '.$usuarios->tabla('r_g_c')
			.' (id_raiz,id_grupo,id_conf) VALUES ';

		for (; $usuarios->mais(); $usuarios->seguinte()) {
			$query .= '("'.$usuarios->get('id').'","'.$id_grupo.'","'.$id_conf.'"),';
		}

		$usuarios->actualizar(substr($query,0,-1).';');
	}

	$ok = 1;
}	

$tempo->rexistra('precarga');

include ($paths['plantillas'].'cab.inc.php');
include ($paths['xestion'].'Xopcions.inc.php');

$tempo->rexistra('precodigo');

include ($paths['xestion'].'grupos/index.inc.php');
include ($paths['plantillas'].'Xgrupos.inc.php');

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

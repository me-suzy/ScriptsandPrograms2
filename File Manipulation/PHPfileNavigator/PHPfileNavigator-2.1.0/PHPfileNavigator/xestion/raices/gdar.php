<?php
/****************************************************************************
* xestion/raices/gdar.php
*
* Guarda las modificaciónes de datos de una raíz y su relación con los usuarios
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

$relativo = '../../';

include ($relativo.'paths.php');
include_once ($paths['include'].'basicweb.php');
include_once ($paths['include'].'Xusuarios.php');

session_write_close();

$query = '';
$erros = array();
$ok = 0;

$id_raiz = intval($vars->post('id_raiz'));
$nome = addslashes(trim($vars->post('nome')));
$path = $niveles->path_correcto($vars->post('path')).'/';
$web = $niveles->path_correcto($vars->post('web')).'/';
$host = addslashes(trim($vars->post('host')));
$peso_maximo = addslashes(trim($vars->post('peso_maximo')));
$peso_maximo = empty($peso_maximo)?0:$peso_maximo;
$unidades = addslashes(trim($vars->post('unidades')));
$estado = intval($vars->post('estado'));

$revisar_peso_actual = trim($vars->post('revisar_peso_actual'));
$borrar_inc = trim($vars->post('borrar_inc'));
$borrar_imx = trim($vars->post('borrar_imx'));

$Fusuarios = (array)$vars->post('Fusuarios');
$Fgrupos = (array)$vars->post('Fgrupos');
$Fconfs = (array)$vars->post('Fconfs');

if (empty($nome) || empty($path) || empty($web) || empty($host)) {
	$erros[] = 1;
} elseif (($id_raiz == $sPFN['raiz']['id']) && ($estado == 0)) {
	$erros[] = 3;
} elseif (($sPFN['raiz']['id'] == $id_raiz)
&& !@in_array($sPFN['usuario']['id'], $Fusuarios[$sPFN['usuario']['id_grupo']])) {
	$erros[] = 7;
} elseif (!@is_dir($path)) {
	$erros[] = 31;
} elseif (!eregi('[0-9\.,]+', $peso_maximo)) {
	$erros[] = 36;
} else {
	if (($peso_maximo > 0) && (($id_raiz == 0) || $revisar_peso_actual)) {
		$peso_actual = $niveles->get_tamano($path);
	}

	if (empty($id_raiz)) {
		$query = 'INSERT INTO '.$usuarios->tabla('raices')
			.' SET nome = "'.$nome.'"'
			.', path = "'.$path.'"'
			.', web = "'.$web.'"'
			.', host = "'.$host.'"'
			.', estado = "'.$estado.'"'
			.', peso_maximo = "'.($peso_maximo*(($unidades == 'MB')?1024*1024:1024*1024*1024)).'"'
			.', peso_actual = "'.$peso_actual.'";';
	} else {
		$query = 'UPDATE '.$usuarios->tabla('raices')
			.' SET nome = "'.$nome.'"'
			.', path = "'.$path.'"'
			.', web = "'.$web.'"'
			.', host = "'.$host.'"'
			.', estado = "'.$estado.'"'
			.($revisar_peso_actual?(', peso_actual = "'.$peso_actual.'"'):'')
			.', peso_maximo = "'.($peso_maximo*(($unidades == 'MB')?1024*1024:1024*1024*1024)).'"'
			.' WHERE id = "'.$id_raiz.'"'
			.' LIMIT 1;';
	}

	if ($usuarios->actualizar($query) == -1) {
		$erros[] = 2;
	}
}

if (!count($erros)) {
	$id_raiz = empty($id_raiz)?$usuarios->id_ultimo():$id_raiz;

	$query = 'DELETE FROM '.$usuarios->tabla('r_u')
		.' WHERE id_raiz="'.$id_raiz.'";';
	$usuarios->actualizar($query);

	if (is_array($Fusuarios) && count($Fusuarios)) {
		$query = 'INSERT INTO '.$usuarios->tabla('r_u')
			.' (id_raiz,id_usuario) VALUES ';

		foreach ($Fusuarios as $v) {
			if (is_array($v)) {
				foreach ($v as $v2) {
					$query .= '("'.$id_raiz.'","'.intval($v2).'"),';
				}
			} else {
				$query .= '("'.$id_raiz.'","'.intval($v).'"),';
			}
		}

		$clases->actualizar(substr($query,0,-1).';');
	}

	if (is_array($Fgrupos) && count($Fgrupos)) {
		$query = 'REPLACE INTO '.$usuarios->tabla('r_g_c')
			.' (id_raiz,id_grupo,id_conf) VALUES ';

		foreach ($Fgrupos as $k => $v) {
			$query .= '("'.$id_raiz.'","'.intval($v).'","'.intval($Fconfs[$k]).'"),';
		}

		$clases->actualizar(substr($query,0,-1).';');
	}

	include_once ($paths['include'].'class_arquivos.php');

	$arquivos = new PFN_Arquivos($conf);

	if (!is_file($path.'.htaccess')) {
		$arquivos->crear_htaccess($path);
	}

	$info_raiz = $niveles->path_correcto($paths['info'].'raiz'.$id_raiz);

	if (!is_dir($info_raiz)) {
		@mkdir($info_raiz, 0755);
	}

	if ($borrar_inc || $borrar_imx) {
		include_once ($paths['include'].'class_inc.php');

		$inc = new PFN_INC($conf);
		$inc->vacia_path($path, $borrar_inc, $borrar_imx);
	}

	$ok = (count($erros) > 0)?0:1;
}

$tempo->rexistra('precarga');

include ($paths['plantillas'].'cab.inc.php');
include ($paths['xestion'].'Xopcions.inc.php');

$tempo->rexistra('precodigo');

include ($paths['xestion'].'raices/index.inc.php');
include ($paths['plantillas'].'Xraices.inc.php');

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

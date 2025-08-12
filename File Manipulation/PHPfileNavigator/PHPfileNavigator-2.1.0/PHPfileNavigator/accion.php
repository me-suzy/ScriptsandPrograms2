<?php
/****************************************************************************
* accion.php
*
* Controla y ejecuta los permisos sobre la realización de una acción
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
include ($paths['include'].'basicweb.php');

session_write_close();

$tempo->rexistra('precarga');

$vars->server('PHP_SELF','navega.php');

$accion = $vars->get('accion').$vars->post('accion');

include_once ($paths['include']."class_imaxes.php");
include_once ($paths['include']."class_arquivos.php");

$imaxes = new PFN_Imaxes($conf);
$arquivos = new PFN_Arquivos($conf);

if (!empty($accion) && $conf->g('permisos',$accion)
&& file_exists($paths['accions']."$accion.inc.php")) {
	define('ACCION', true);

	$conf->textos('estado');

	include_once ($paths['include']."class_accions.php");
	$accions = new PFN_Accions($conf);

	$cal = $arquivo = $ucal = $tipo = '';
	$e_imaxe = $redimensionar = $ver_contido = $editar = $extraer = $ver_comprimido = $descargar = false;

	if (!in_array($accion, array('crear_dir','subir_arq','subir_url','multiple_copiar','multiple_mover','multiple_eliminar','multiple_permisos','multiple_descargar','buscador'))) {
		$cal = $vars->post('executa')?$vars->post('cal'):$vars->get('cal');
		$cal = $accions->nome_correcto($cal);
		$arquivo = $conf->g('raiz','path').$accions->path_correcto($dir.'/').'/'.$cal;
		$ucal = rawurlencode($cal);
		$tipo = is_file($arquivo)?'arq':(is_dir($arquivo)?'dir':'');

		if (empty($tipo) || empty($cal) || (!$niveles->filtrar($cal) && $cal != '.')) {
			Header('Location: '.PFN_quita_url(array('cal','accion'), true, true));
			exit;
		} elseif ($tipo == 'arq') {
			$e_imaxe = $imaxes->e_imaxe($arquivo);
			$redimensionar = $e_imaxe && $conf->g('permisos','redimensionar');
			$ver_contido = !$e_imaxe && $arquivos->editable($cal) && $conf->g('permisos','ver_contido');
			$editar = !$e_imaxe && $arquivos->editable($cal) && $conf->g('permisos','editar');
			$extraer = !$e_imaxe && $arquivos->vale_extraer($arquivo);
			$ver_comprimido = !$e_imaxe && $arquivos->vale_extraer($arquivo, true);
			$descargar = $conf->g('permisos','descargar');
		} else {
			$ver_contido = $editar = $e_imaxe = $redimensionar = $extraer = $descargar = false;
		}
	}

	$tempo->rexistra('preaccion');

	include ($paths['accions']."$accion.inc.php");

	$tempo->rexistra('postaccion');
} else {
	$tempo->rexistra('preplantillas');

	include ($paths['plantillas'].'cab.inc.php');
	include ($paths['web'].'opcions.inc.php');

	$tempo->rexistra('precodigo');

	include ($paths['web'].'navega.inc.php');

	$tempo->rexistra('postcodigo');

	include ($paths['plantillas'].'pe.inc.php');
}
?>

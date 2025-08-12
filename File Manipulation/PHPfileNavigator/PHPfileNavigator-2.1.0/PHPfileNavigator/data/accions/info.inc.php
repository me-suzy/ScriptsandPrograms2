<?php
/****************************************************************************
* data/accions/info.inc.php
*
* Carga lo necesario para la visualización de la ventana popup de información
* adicional
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

defined('OK') && defined('ACCION') or die();

PFN_quita_url_SERVER(array('accion','calcula_tamano'));

$arquivos->niveles($niveles);

$erro = false;
$fin = '';
$datos_inc = array();
$protexido = false;
$capas = $conf->g('info','capas');
$zlib = $conf->g('zlib');

if ($tipo == 'dir') {
	$icono = $imaxes->icono('dir');
	$fin = '/';
} elseif (is_file($arquivo)) {
	$icono = $imaxes->sello($dir.'/'.$cal,false,false);
}

if (in_array('descricion', $capas)) {
	$datos = stat($arquivo);
}

if ($conf->g('inc','estado')) {
	include_once ($paths['include'].'class_inc.php');
	$inc = new PFN_INC($conf);
}

if ($vars->post('executa')) {
	if (($vars->post('formulario') == 'form_inc') && in_array('formulario', $capas)) {
		if ($conf->g('inc','estado')) {
			$inc->arquivos($arquivos);
			$arq_inc = $inc->crea_inc($arquivo.$fin, $tipo);
		}

		if ($conf->g('inc','indexar')) {
			include_once ($paths['include'].'class_indexador.php');

			$indexador = new PFN_Indexador($conf);
			$indexador->alta_modificacion("$dir/", "$cal$fin", $arq_inc);
		}
	} elseif (($vars->post('formulario') == 'protexer')
	&& in_array('protexer', $capas)
	&& $conf->g('usuario','admin')
	&& ($tipo == 'dir')) {
		if (trim($vars->post('ht_usuario')) == '') {
			$arquivos->eliminar_htpasswd("$arquivo/");
		} else {
			$arquivos->crear_htaccess("$arquivo/", true);
		}
	}
}

$ahref = '<a href="'.$niveles->enlace($dir, $vars->get('cal').$fin)
	.'" target="_blank" class="ao14">'.$vars->get('cal').'</a>';

if ($tipo == 'dir') {
	if ($vars->get('calcula_tamano')) {
		$tamano_real = $niveles->get_tamano("$arquivo/");
		$tamano_disco = PFN_peso(PFN_espacio_disco($tamano_real));
		$tamano_real .= ' Bytes';
	} else {
		$tamano_real = '<a href="accion.php?'.PFN_cambia_url(array('cal','accion','calcula_tamano'),array($cal,'info',true), false)
			.'">'.$conf->t('calcular_tamano').'</a>';
		$tamano_disco = '&nbsp;';
	}
} else {
	$tamano_real = PFN_espacio_disco($arquivo, true);
	$tamano_disco = PFN_peso(PFN_espacio_disco($tamano_real));
	$tamano_real .= ' Bytes';
}

$permisos = PFN_permisos(fileperms($arquivo.$fin));

if ($conf->g('inc','estado')) {
	$inc->carga_datos($arquivo.$fin);

	if (in_array('descricion', $capas)) {
		$datos_inc['desc'] = $inc->crea_descricion($tipo);
	}

	if (in_array('formulario', $capas)) {
		$datos_inc['form'] = $inc->crea_formulario($tipo);
	}
}

if (in_array('protexer',$capas) && $conf->g('usuario','admin') && ($tipo == 'dir')) {
	$protexido = is_file("$arquivo/.htpasswd");
}

if (in_array('enlaces',$capas)) {
	$enlace_abs = $niveles->enlace($dir, $cal).$fin;
	$enlace_rel = $niveles->enlace($dir, $cal, false).$fin;

	if ($conf->g('inc','estado')) {
		$enlace_href = htmlentities('<a href="'.$enlace_abs.'">'.$inc->valor($conf->g('inc','tit_enlaces')).'</a>');
		$enlace_wiki = "[ ".$inc->valor($conf->g('inc','tit_enlaces'))." | ".$enlace_abs." ]";
	} else {
		$enlace_href = htmlentities('<a href="'.$enlace_abs.'">'.$cal.'</a>');
		$enlace_wiki = "[ ".$cal." | ".$enlace_abs." ]";
	}
}

include ($paths['plantillas'].'cab.inc.php');

$tempo->rexistra('precodigo');

include ($paths['web'].'opcions.inc.php');
include ($paths['plantillas'].'posicion.inc.php');
include ($paths['plantillas'].'info_cab.inc.php');
include ($paths['plantillas'].'info_corpo.inc.php');

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

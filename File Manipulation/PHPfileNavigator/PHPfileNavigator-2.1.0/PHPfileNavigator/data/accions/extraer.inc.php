<?php
/****************************************************************************
* data/accions/extraer.inc.php
*
* Descomprime un fichero tar/gzip/bzip2 en el servidor
*

PHPfileNavigator versión 2.0.2

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

$erro = 0;

if ($arquivos->vale_extraer($arquivo)) {
	include_once ($paths['include'].'class_extraer.php');

	switch (end(explode('.', $cal))) {
		case 'tar':
			$extraer = new PFN_tar_file($arquivo);
			break;
		case 'gz':
		case 'tgz':
		case 'gzip':
			$extraer = new PFN_gzip_file($arquivo);
			break;
		case 'bzip':
		case 'bzip2':
		case 'bz':
		case 'bz2':
//			$extraer = new PFN_bzip_file($arquivo);
//			break;
		default:
			$erro = 1;
			break;
	}

	if ($erro) {
		$estado_accion = $conf->t('estado.extraer', 2);
	} else {
		@set_time_limit($conf->g('tempo_maximo'));
		@ini_set('memory_limit', $conf->g('memoria_maxima'));

		$visto = array();
		$estado_accion = '';

		if ($conf->g('inc','indexar')) {
			include_once ($paths['include'].'class_indexador.php');

			$indexador = new PFN_Indexador($conf);
			$extraer->indexador($indexador, "$dir/");
		}

		$extraer->pon_opcion('overwrite', intval($vars->get('sobreescribir')));
		$extraer->niveles($niveles);
		$extraer->limite_peso($conf->g('raiz','peso_actual'), $conf->g('raiz','peso_maximo'));

		$erro = $extraer->extract_files();

		$accions->log_accion('extraer', $arquivo);

		if ($conf->g('raiz','peso_maximo') > 0) {
			$peso_este = $extraer->get_actual();

			$conf->p($peso_este, 'raiz', 'peso_actual');
			$usuarios->accion('peso', $peso_este, $conf->g('raiz','id'));
		}

		if (count($erro)) {
			foreach ($erro as $v) {
				if (!in_array($v, $visto)) {
					$visto[] = $v;
					$estado_accion .= '<br />'.$conf->t('estado.extraer', $v);
				}
			}
		} else {
			$estado_accion = $conf->t('estado.extraer', 1);
		}
	}
}

$tempo->rexistra('preplantillas');
	
include ($paths['plantillas'].'cab.inc.php');
include ($paths['web'].'opcions.inc.php');
	
$tempo->rexistra('precodigo');
	
include ($paths['web'].'navega.inc.php');
	
$tempo->rexistra('postcodigo');
	
include ($paths['plantillas'].'pe.inc.php');
?>

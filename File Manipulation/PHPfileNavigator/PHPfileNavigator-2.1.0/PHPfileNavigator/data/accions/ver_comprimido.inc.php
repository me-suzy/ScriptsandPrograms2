<?php
/****************************************************************************
* data/accions/ver_comprimido.inc.php
*
* Visualiza el contenido de un fichero tar/gzip/bzip2/zip
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

defined('OK') && defined('ACCION') or die();

PFN_quita_url_SERVER(array('orde_comprimido','pos_comprimido'));

$erro = 0;

$tempo->rexistra('preplantillas');

include ($paths['plantillas'].'cab.inc.php');
include ($paths['web'].'opcions.inc.php');

$tempo->rexistra('precodigo');

if ($arquivos->vale_extraer($arquivo, true)) {
	switch (end(explode('.', $cal))) {
		case 'tar':
			include_once ($paths['include'].'class_extraer.php');
			$ver = new PFN_tar_file($arquivo);
			break;
		case 'gz':
		case 'tgz':
		case 'gzip':
			include_once ($paths['include'].'class_extraer.php');
			$ver = new PFN_gzip_file($arquivo);
			break;
		case 'zip':
			include_once ($paths['include'].'class_easyzip.php');
			$ver = new dUnzip2($arquivo);
			break;
		case 'bzip':
		case 'bzip2':
		case 'bz':
		case 'bz2':
//			include_once ($paths['include'].'class_extraer.php');
//			$ver = new PFN_bzip_file($arquivo);
//			break;
		default:
			$erro = 1;
			break;
	}

	if ($erro) {
		$estado_accion = $conf->t('estado.ver_comprimido', 1);
	} else {
		@set_time_limit($conf->g('tempo_maximo'));
		@ini_set('memory_limit', $conf->g('memoria_maxima'));

		$visto = array();
		$estado_accion = '';

		$contido = $ver->listar_contido();
		$orde = ($vars->get('orde_comprimido') != '')?$vars->get('orde_comprimido'):'nome';
		$orde = in_array($orde, array('nome','tamano','data','propietario','grupo','permisos'))?$orde:'nome';
		$pos = ($vars->get('pos_comprimido') == 'DESC')?'DESC':'ASC';

		natcasesort($contido[$orde]);

		if ($pos == 'DESC') {
			$contido[$orde] = array_reverse($contido[$orde], true);
		}

		$pos = ($pos == 'ASC')?'DESC':'ASC';
		$i = $cnt_peso = $cnt_cantos['dir'] = $cnt_cantos['arq'] = 0;
		$txt = '';

		foreach ($contido[$orde] as $k => $v) {
			$i++;

			$nome = $contido['nome'][$k];
			$peso = PFN_peso($contido['tamano'][$k]);
			$data = date($conf->g('data'), $contido['data'][$k]);
			$prop = $contido['propietario'][$k];
			$grupo = $contido['grupo'][$k];
			$perms = PFN_permisos($contido['permisos'][$k]);

			$e_dir = (substr($nome, -1) == '/');

			$cnt_peso += $contido['tamano'][$k];
			$cnt_cantos[($e_dir?'dir':'arq')]++;
			$txt .= "\n\t\t\t\t".'<tr'.((($i % 2) == 0)?'':' class="tr_par"').'>'
				."\n\t\t\t\t\t".'<td>'.($e_dir?('<strong>'.$nome.'</strong>'):$nome).'</td>'
				."\n\t\t\t\t\t".'<td>'.($e_dir?('<strong>'.$peso.'</strong>'):$peso).'</td>'
				."\n\t\t\t\t\t".'<td>'.($e_dir?('<strong>'.$data.'</strong>'):$data).'</td>'
				."\n\t\t\t\t\t".'<td>'.($e_dir?('<strong>'.$prop.'</strong>'):$prop).'</td>'
				."\n\t\t\t\t\t".'<td>'.($e_dir?('<strong>'.$grupo.'</strong>'):$grupo).'</td>'
				."\n\t\t\t\t\t".'<td>'.($e_dir?('<strong>'.$perms.'</strong>'):$perms).'</td>'
				."\n\t\t\t\t\t".'</tr>';
		}
	}
} else {
	$erro = 1;
	$estado_accion = $conf->t('estado.ver_comprimido', 1);
}

if ($erro) {
	include ($paths['web'].'navega.inc.php');
} else {
	include ($paths['plantillas'].'posicion.inc.php');
	include ($paths['plantillas'].'info_cab.inc.php');
	include ($paths['plantillas'].'ver_comprimido.inc.php');
}


$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

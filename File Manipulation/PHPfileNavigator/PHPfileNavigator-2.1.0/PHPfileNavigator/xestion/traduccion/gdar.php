<?php
/****************************************************************************
* xestion/traduccion/gdar.inc.php
*
* Garda el resultado de la traduccion en el idioma seleccionado
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

$conf->textos('idiomas');

$tr_charset = $vars->post('tr_charset');
$tr_orixe = $vars->post('tr_orixe');
$tr_destino = $vars->post('tr_destino');
$tr_arquivo = $vars->post('tr_arquivo');
$tr_listar = $vars->post('tr_listar');
$executa = $vars->post('executa');
$erros = array();
$ok = false;

$lista_idiomas = $conf->t('lista_idiomas');
asort($lista_idiomas);

foreach ($lista_idiomas as $k => $v) {
	if (is_dir($paths['idiomas'].$k)) {
		$idiomas_valen[$k] = $v;
	}
}

if ($executa == 'gardar') {
	$idioma_orixe = $paths['idiomas'].$niveles->path_correcto($tr_orixe).'/'.$niveles->nome_correcto($tr_arquivo).'.inc.php';

	if (is_file($idioma_orixe)) {
		$path_destino = $paths['idiomas'].$niveles->path_correcto($tr_destino);
		$idioma_destino = $path_destino.'/'.$niveles->nome_correcto($tr_arquivo).'.inc.php';

		if (is_file($idioma_destino) && !is_writable($idioma_destino)) {
			$erro[] = 33;
		} elseif (is_writable($paths['idiomas']) && (is_dir($path_destino)?is_writable($path_destino):true)) {
			include_once ($paths['include'].'class_arquivos.php');
			$arquivos = new PFN_Arquivos($conf);

			if (!is_dir($path_destino)) {
				mkdir($path_destino);
				$arquivos->abre_escribe($path_destino.'/index.html', '');
			}

			$datos_orixe = include ($idioma_orixe);
			$datos_destino = $vars->post('idioma_i');
			$txt = '';
			$enti['mal'] = array(
				'á','é','í','ó','ú','à','è','ì','ò','ù',
				'â','ê','î','ô','û','ä','ë','ï','ö','ü',
				'Á','É','Í','Ó','Ú','À','È','Ì','Ò','Ù',
				'Â','Ê','Î','Ô','Û','Ä','Ë','Ï','Ö','Ü',
				'ñ','Ñ','ç','Ç',"'"
			);
			$enti['ben'] = array(
				'&aacute;','&eacute;','&iacute;','&oacute;','&uacute;',
				'&agrave;','&egrave;','&igrave;','&ograve;','&ugrave;',
				'&acirc;','&ecirc;','&icirc;','&ocirc;','&ucirc;','&auml;',
				'&euml;','&iuml;','&ouml;','&uuml;','&Aacute;','&Eacute;',
				'&Iacute;','&Oacute;','&Uacute;','&Agrave;','&Egrave;',
				'&Igrave;','&Ograve;','&Ugrave;','&Acirc;','&Ecirc;',
				'&Icirc;','&Ocirc;','&Ucirc;','&Auml;','&Euml;','&Iuml;',
				'&Ouml;','&Uuml;','&ntilde;','&Ntilde;','&ccedil;','&Ccedil;',
				'\\\\\''
			);

			foreach ($datos_orixe as $k => $v) {
				if (is_array($v)) {
					$txt .= "\n\t".'\''.$k.'\' => array(';

					foreach ($v as $k2 => $v2) {
						unset($v2);

						$cad = PFN_textoForm2interno($datos_destino[$k][$k2]);
						$cad = str_replace('"','||',$cad);
						$cad = str_replace($enti['mal'],$enti['ben'],$cad);
						$cad = str_replace('||', '"', $cad);

						$txt .= "\n\t\t".'\''.$k2.'\' => \''.$cad.'\',';
					}

					$txt .= "\n\t".'),';
				} else {
					$cad = PFN_textoForm2interno($datos_destino[$k]);
					$cad = str_replace('"','||',$cad);
					$cad = str_replace($enti['mal'],$enti['ben'],$cad);
					$cad = str_replace('||', '"', $cad);

					$txt .= "\n\t".'\''.$k.'\' => \''.$cad.'\',';
				}
			}

			$licencia['script'] = 'data/idiomas/'.$tr_destino.'/'.$tr_arquivo.'.inc.php';
			$licencia['descricion'] = 'Textos para el idioma '.$conf->t('lista_idiomas',$tr_destino);

			$txt_licencia = include ($paths['include'].'licencia.php');
			$txt = '<?php'
				."\n".$txt_licencia
				."\n\n".'defined(\'OK\') or die();'
				."\n\n".'return array('
				.$txt
				."\n".');'
				."\n".'?>';

			$ok = $arquivos->abre_escribe($idioma_destino, $txt);

			if ($ok) {
				$datos_destino = include ($idioma_destino);

				foreach ($datos_orixe as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $k2 => $v2) {
							$datos_orixe[$k][$k2] = str_replace('\\','',$v2);
							$datos_destino[$k][$k2] = str_replace('\\','',$datos_destino[$k][$k2]);
						}
					} else {
						$datos_orixe[$k] = str_replace('\\','',$v);
						$datos_destino[$k] = str_replace('\\','',$datos_destino[$k]);
					}
				}
			} else {
				$erros[] = 34;
			}
		} else {
			$erros[] = 33;
		}
	} else {
		$erros[] = 32;
	}
}

$tempo->rexistra('precarga');

$tr_charset = empty($tr_charset)?$conf->g('charset'):$tr_charset;

$conf->p($tr_charset,'charset');

include ($paths['plantillas'].'cab.inc.php');
include ($paths['xestion'].'Xopcions.inc.php');

$tempo->rexistra('precodigo');

include ($paths['plantillas'].'Xtraduccion.inc.php');

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

<?php
/****************************************************************************
* data/accions/ver_contido.inc.php
*
* Visualiza el contenido de un fichero de texto
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

$tempo->rexistra('precodigo');

include ($paths['plantillas'].'cab.inc.php');
include ($paths['web'].'opcions.inc.php');

if ($niveles->filtrar($cal) && is_file($arquivo)) {
	$accions->arquivos($arquivos);

	$estado = $accions->log_ancho_banda(@filesize($arquivo));

	if ($estado === true) {
		$ext = strtolower(end(explode('.', $arquivo)));
		$e_php = (($ext == 'php') || ($ext == 'php3') || ($ext == 'phtml'));

		include ($paths['plantillas'].'posicion.inc.php');
		include ($paths['plantillas'].'info_cab.inc.php');
		include ($paths['plantillas'].'ver_contido.inc.php');
	} elseif ($estado === -1) {
		$estado_accion = $conf->t('estado.descargar', 3);
		include ($paths['web'].'navega.inc.php');
	} else {
		$estado_accion = $conf->t('estado.descargar', 2);
		include ($paths['web'].'navega.inc.php');
	}
} else {
	include ($paths['web'].'navega.inc.php');
}

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

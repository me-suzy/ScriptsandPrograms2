<?php
/****************************************************************************
* xestion/varios/indexador.inc.php
*
* Realiza el proceso de reindexación de ficheros y directorios
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

$indexador_id_raiz = intval($vars->post('indexador_id_raiz'));

if ($indexador_id_raiz > 0) {
	$usuarios->init('raiz', $indexador_id_raiz);

	if ($usuarios->filas() === 1) {
		include_once ($paths['include'].'class_inc.php');
		include_once ($paths['include'].'class_indexador.php');

		$inc = new PFN_INC($conf);
		$indexador = new PFN_Indexador($conf);

		$indexador->niveles($niveles);
		$indexador->inc($inc);

		$txt = $indexador->reindexar($usuarios->get('id'), $usuarios->get('path'));
	} else {
		$erros[] = 36;
	}
} else {
	$erros[] = 36;
}
?>

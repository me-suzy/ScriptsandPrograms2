<?php
/****************************************************************************
* xestion/informes/index.php
*
* Carga la pantalla para la visualización de informes sobre los logs
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

PFN_quita_url_SERVER(array('ud_ordenar','ud_modo','executa','ab_ordenar','ab_modo','ab_mes','ab_ano'));

$tempo->rexistra('precarga');

include ($paths['plantillas'].'cab.inc.php');
include ($paths['xestion'].'Xopcions.inc.php');

$tempo->rexistra('precodigo');

include ($paths['xestion'].'informes/index.inc.php');
include ($paths['plantillas'].'Xinformes.inc.php');

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

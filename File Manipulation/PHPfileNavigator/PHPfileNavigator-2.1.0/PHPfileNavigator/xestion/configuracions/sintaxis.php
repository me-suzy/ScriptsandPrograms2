<?php
/****************************************************************************
* xestion/configuracions/sintaxis.php
*
* Carga la pantalla para la visualización para comprobación de la sintaxis de un
* fichero de configuración
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

PFN_quita_url_SERVER('id_conf');

session_write_close();

$id_conf = $vars->get('id_conf');

$erros = array();
$existe = $usuarios->init('configuracion', $id_conf);
$nome_arq = $niveles->path_correcto($paths['conf'].$usuarios->get('conf').'.inc.php');

if (!$existe || !is_file($nome_arq)) {
	$erros[] = 18;
}

$tempo->rexistra('precarga');

include ($paths['plantillas'].'cab.inc.php');

$tempo->rexistra('precodigo');

include_once ($paths['include'].'class_arquivos.php');
$arquivos = new PFN_Arquivos($conf);

include ($paths['plantillas'].'Xconfiguracions_sintaxis.inc.php');

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

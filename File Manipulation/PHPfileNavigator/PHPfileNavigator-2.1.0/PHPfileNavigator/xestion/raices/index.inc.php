<?php
/****************************************************************************
* xestion/raices/index.inc.php
*
* Comprobaciones antes de modificar o añadir una raiz
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

defined('OK') && defined('XESTION') or die();

$Dgrupos = $Dconfs = array();

if (!is_writable($paths['info'])) {
	$erro[] = 'Xinfonon_writable';
}

if (empty($id_raiz)) {
	$usuarios->init('grupos_configuracions_usuarios');
} else {
	$usuarios->init('grupos_configuracions_usuarios_raiz', $id_raiz);
}

for (; $usuarios->mais(); $usuarios->seguinte()) {
	$Dgrupos[$usuarios->get('id_grupo')]['nome'] = $usuarios->get('grupo');
	$Dgrupos[$usuarios->get('id_grupo')]['id_conf'] = $usuarios->get('id_conf');
	$Dgrupos[$usuarios->get('id_grupo')]['usuarios'][$usuarios->get('id_usuario')] = array($usuarios->get('usuario'), $usuarios->get('relacion'));
}

$usuarios->init('configuracions_valen');

for (; $usuarios->mais(); $usuarios->seguinte()) {
	$Dconfs[$usuarios->get('id')] = $usuarios->get('conf');
}

$usuarios->init('raiz', $id_raiz);

$peso_maximo = $usuarios->get('peso_maximo');
$peso_actual = $usuarios->get('peso_actual');
?>

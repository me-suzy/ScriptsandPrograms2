<?php
/****************************************************************************
* crea_imaxe.php
*
* Visualizar una imágen según los parámetros recibidos
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

include ('paths.php');
include_once ($paths['include'].'basicweb.php');

session_write_close();

include_once ($paths['include'].'class_arquivos.php');
include_once ($paths['include'].'class_imaxes.php');

$arquivos = new PFN_Arquivos($conf);
$imaxes = new PFN_Imaxes($conf);
$imaxes->arquivos($arquivos);

$imaxe = $conf->g('raiz','path').$niveles->path_correcto($dir.'/'.$vars->get('cal'));

echo $imaxes->volcar_imaxe($imaxe, $vars->get('ancho'), $vars->get('alto'));

exit;
?>

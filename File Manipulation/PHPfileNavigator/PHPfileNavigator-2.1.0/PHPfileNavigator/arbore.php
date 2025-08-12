<?php
/****************************************************************************
* arbore.php
*
* Precarga los includes para arbore.inc.php
*

PHPfileNavigator versión 1.6.7

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
include ($paths['include'].'basicweb.php');

session_write_close();

$tempo->rexistra('preplantillas');

include ($paths['plantillas'].'cab.inc.php');
include ($paths['web'].'opcions.inc.php');

$tempo->rexistra('precodigo');

include ($paths['plantillas'].'posicion.inc.php');
include ($paths['web'].'arbore.inc.php');

$tempo->rexistra('postcodigo');

include ($paths['plantillas'].'pe.inc.php');
?>

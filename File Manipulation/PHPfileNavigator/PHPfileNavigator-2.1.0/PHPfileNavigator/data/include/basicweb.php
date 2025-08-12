<?php
/****************************************************************************
* data/include/basicweb.php
*
* Realiza los includes basicos para el correcto funcionamiento de todas las
* secciones
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

defined('OK') or die();

include_once ($paths['include'].'class_tempo.php');
include_once ($paths['include'].'borra_cache.php');
include_once ($paths['include'].'class_conf.php');
include_once ($paths['include'].'class_vars.php');
include_once ($paths['include'].'class_sesion.php');
include_once ($paths['include'].'mysql.php');
include_once ($paths['include'].'clases.php');
include_once ($paths['include'].'class_usuarios.php');
include_once ($paths['include'].'usuarios.php');
include_once ($paths['include'].'formatear.php');
include_once ($paths['include'].'class_niveles.php');
include_once ($paths['include'].'prepara.php');
include_once ($paths['include'].'mantemento.php');
?>

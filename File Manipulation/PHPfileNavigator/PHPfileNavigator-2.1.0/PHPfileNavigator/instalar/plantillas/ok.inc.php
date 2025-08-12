<?php
/*******************************************************************************
* instalar/plantillas/ok.inc.php
*
* plantilla para la visualización de la pantalla del resultado de una
* instalación correcta
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
?>
<script type="text/javascript"><!--

document.write('<link rel="stylesheet" href="instalar/instalar.css" type="text/css" />');

//--></script>

<div id="inst_benvido"><?php echo $conf->t('benvido'); ?></div>
<div style="margin: 10px; text-align: center;"><img src="estilos/pfn/imx/logo.png" alt="PHPfileNavigator v2" /></div>
<div id="inst_correcto"><?php echo $conf->t('instalacion_correcta'); ?></div>
<div class="inst_aviso"><a href="http://sourceforge.net/donate/index.php?group_id=142312" onclick="window.open(this.href,'_blank'); return false;"><img src="http://images.sourceforge.net/images/project-support.jpg" width="88" height="32" border="0" alt="Support This Project" id="inst_doazon" /></a><?php echo $conf->t('doazon'); ?><br class="nada" /></div>
